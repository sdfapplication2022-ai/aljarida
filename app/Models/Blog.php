<?php

namespace App\Models;

use App\Models\Concerns\HasUploads;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasUploads;

    protected $fillable = ['title','content','author','published_at','image'];
    protected $casts = ['published_at' => 'datetime'];
    public function scopePublished(Builder $q): \Illuminate\Database\Eloquent\Builder
    {
        return $q->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }
    public function setImageAttribute($value): void
    {
        $this->uploadFile($value, 'image', 'uploads/blogs', ['jpg','jpeg','png','webp']);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['image'] ?? null);
    }
}
