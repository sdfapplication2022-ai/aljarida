<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'content', 'article_id', 'user_id', 'is_approved',
        'guest_name', 'guest_email', 'visitor_token',
    ];

    protected $hidden = ['guest_email', 'visitor_token'];

    protected $casts = ['is_approved' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $c) {
            if (empty($c->user_id) && auth()->check()) {
                $c->user_id = auth()->id();
            }
        });
    }

    public function scopeApproved($q) { return $q->where('is_approved', true); }
    public function article() { return $this->belongsTo(Article::class); }
    public function user()    { return $this->belongsTo(User::class); }

    /** Nom affiché publiquement (jamais l'e-mail). */
    public function getAuthorNameAttribute(): string
    {
        return $this->user?->name ?: ($this->guest_name ?: 'زائر');
    }

    /** Ce commentaire appartient-il à cet utilisateur connecté ou à ce visiteur (cookie) ? */
    public function isOwnedBy(?User $user, ?string $token): bool
    {
        if ($user && $this->user_id === $user->id) {
            return true;
        }

        return $token && $this->visitor_token && hash_equals($this->visitor_token, $token);
    }
}
