<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','role','bio','photo']; // role: admin, editor, user

    protected $hidden = ['password','remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isStaff(): bool { return in_array($this->role, ['admin','editor'], true); }
    public function isAdmin(): bool { return $this->role === 'admin'; }

    public function articles() { return $this->hasMany(Article::class); }
    public function comments() { return $this->hasMany(Comment::class); }
}
