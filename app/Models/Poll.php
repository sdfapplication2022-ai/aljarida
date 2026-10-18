<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = ['question', 'is_active', 'closes_at', 'options_text'];

    protected $casts = ['is_active' => 'boolean', 'closes_at' => 'datetime'];

    /** Texte saisi dans l'admin (une option par ligne), appliqué après l'enregistrement. */
    protected ?string $pendingOptions = null;

    protected static function booted(): void
    {
        static::saved(function (self $poll) {
            if ($poll->pendingOptions === null) {
                return;
            }

            $labels = collect(preg_split('/\R/u', $poll->pendingOptions))
                ->map(fn ($l) => trim($l))->filter()->unique()->take(8)->values();

            // supprime les options retirées, conserve les votes de celles qui restent
            $poll->options()->whereNotIn('label', $labels->all())->delete();
            foreach ($labels as $i => $label) {
                $poll->options()->updateOrCreate(['label' => $label], ['position' => $i]);
            }

            $poll->pendingOptions = null;
        });
    }

    public function options() { return $this->hasMany(PollOption::class)->orderBy('position')->orderBy('id'); }
    public function votes()   { return $this->hasMany(PollVote::class); }

    public function setOptionsTextAttribute($value): void
    {
        $this->pendingOptions = (string) $value;
    }

    public function getOptionsTextAttribute(): string
    {
        return $this->exists ? $this->options->pluck('label')->implode("\n") : '';
    }

    public function isOpen(): bool
    {
        return $this->is_active && (! $this->closes_at || $this->closes_at->isFuture());
    }

    /** Le sondage à afficher : le plus récent parmi les sondages actifs et non clos. */
    public function scopeCurrent($q)
    {
        return $q->where('is_active', true)
            ->where(fn ($w) => $w->whereNull('closes_at')->orWhere('closes_at', '>', now()))
            ->latest('id');
    }
}
