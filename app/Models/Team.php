<?php

namespace App\Models;

use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /** @use HasFactory<TeamFactory> */
    use HasFactory, HasUlids;

    protected $fillable = [
        'team_name', 'players_on_field', 'goalkeepers_count', 'defenders_count', 'midfielders_count', 'forwards_count',
    ];

    protected $casts = [
        'players_on_field' => 'integer',
        'goalkeepers_count' => 'integer',
        'defenders_count' => 'integer',
        'midfielders_count' => 'integer',
        'forwards_count' => 'integer',
    ];
}
