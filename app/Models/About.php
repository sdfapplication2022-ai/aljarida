<?php

namespace App\Models;

use App\Models\Concerns\HasUploads;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasUploads;

    protected $table = 'abouts';
    protected $fillable = ['title','subtitle','description','main_image','secondary_image','video_url','team_links'];
    protected $casts = ['team_links' => 'array'];

    public function setMainImageAttribute($value): void
    {
        $this->uploadFile($value, 'main_image', 'uploads/about', ['jpg','jpeg','png','webp']);
    }

    public function setSecondaryImageAttribute($value): void
    {
        $this->uploadFile($value, 'secondary_image', 'uploads/about', ['jpg','jpeg','png','webp']);
    }

    public function setVideoUrlAttribute($value): void
    {
        $this->uploadFile($value, 'video_url', 'uploads/videos', ['mp4']);
    }

    public function getMainImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['main_image'] ?? null);
    }

    public function getSecondaryImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['secondary_image'] ?? null);
    }

    public function getVideoPlayerUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['video_url'] ?? null);
    }
}
