<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportsResult extends Model
{
    protected $fillable = [
        'competition', 'home_team', 'away_team', 'home_score', 'away_score',
        'city', 'venue', 'played_at', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'home_score' => 'integer',
            'away_score' => 'integer',
            'played_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }
}
