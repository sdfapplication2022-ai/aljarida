<?php

namespace App\Models;

use App\Models\Concerns\HasUploads;
use Illuminate\Database\Eloquent\Model;

class SportsResult extends Model
{
    use HasUploads;

    protected $fillable = [
        'competition', 'home_team', 'away_team', 'home_logo', 'away_logo', 'image',
        'home_score', 'away_score', 'city', 'venue', 'played_at', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'home_score'   => 'integer',
            'away_score'   => 'integer',
            'played_at'    => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }

    // ---- Uploads ----
    public function setHomeLogoAttribute($value): void
    {
        $this->uploadFile($value, 'home_logo', 'uploads/sports/logos', ['jpg', 'jpeg', 'png', 'webp']);
    }

    public function setAwayLogoAttribute($value): void
    {
        $this->uploadFile($value, 'away_logo', 'uploads/sports/logos', ['jpg', 'jpeg', 'png', 'webp']);
    }

    public function setImageAttribute($value): void
    {
        $this->uploadFile($value, 'image', 'uploads/sports', ['jpg', 'jpeg', 'png', 'webp']);
    }

    // ---- URLs ----
    public function getHomeLogoUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['home_logo'] ?? null);
    }

    public function getAwayLogoUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['away_logo'] ?? null);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['image'] ?? null);
    }
}
