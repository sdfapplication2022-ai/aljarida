<?php

namespace App\Models;

use App\Models\Concerns\HasUploads;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasUploads;

    protected $table = 'team_members';
    protected $fillable = ['name','position','bio','photo','facebook','linkedin','twitter','instagram'];

    public function setPhotoAttribute($value): void
    {
        $this->uploadFile($value, 'photo', 'uploads/teams', ['jpg','jpeg','png','webp']);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->fileUrl($this->attributes['photo'] ?? null, asset('assets/img/default-service.png'));
    }
}
