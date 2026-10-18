<?php

namespace App\Models;

use App\Models\Concerns\HasUploads;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasUploads;

    protected $fillable = ['title','icon','description','photo'];

    public function setPhotoAttribute($value): void
    {
        $this->uploadFile($value, 'photo', 'uploads/services', ['jpg','jpeg','png','webp']);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['photo'] ?? null, asset('assets/img/default-service.png'));
    }
}
