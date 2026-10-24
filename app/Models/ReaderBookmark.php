<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReaderBookmark extends Model
{
    protected $fillable = ['reader_key', 'article_id'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
