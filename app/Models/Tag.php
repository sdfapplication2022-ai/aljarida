<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name','slug'];

    public function articles() { return $this->belongsToMany(Article::class); }
    protected static function booted(): void
    {
        static::saving(function (self $m) {
            if (empty($m->slug)) {
                $m->slug = \Illuminate\Support\Str::slug($m->name) ?: 'tag-'.($m->id ?? uniqid());
            }
        });
    }
}
