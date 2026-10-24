<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalEvent extends Model
{
    protected $fillable = [
        'title', 'summary', 'description', 'city', 'venue', 'organizer', 'url',
        'starts_at', 'ends_at', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }
}
