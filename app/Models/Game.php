<?php

namespace App\Models;

use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /** @use HasFactory<GameFactory> */
    use HasFactory, HasUlids;

    protected $fillable = [
        'opponent', 'location', 'scheduled_at', 'players_on_field', 'goalkeepers_count', 'defenders_count', 'midfielders_count', 'forwards_count', 'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];
}
