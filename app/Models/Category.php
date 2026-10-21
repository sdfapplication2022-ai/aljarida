<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','slug','description'];

    public function articles() { return $this->hasMany(Article::class); }
    protected static function booted(): void
{
    static::saving(function (self $m) {
        if (empty($m->slug)) {
            $m->slug = \Illuminate\Support\Str::slug($m->name) ?: 'category-'.($m->id ?? uniqid());
        }
    });
}
}
