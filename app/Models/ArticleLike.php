<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleLike extends Model
{
    protected $fillable = ['article_id', 'user_id', 'visitor_token', 'ip'];

    public function article() { return $this->belongsTo(Article::class); }
    public function user()    { return $this->belongsTo(User::class); }
}
