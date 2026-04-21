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
        'team_name',
        'players_on_field',
        'goalkeepers_count',
        'defenders_count',
        'midfielders_count',
        'forwards_count',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'team_name' => 'Soccer Squad',
        'players_on_field' => 9,
        'goalkeepers_count' => 1,
        'defenders_count' => 3,
        'midfielders_count' => 3,
        'forwards_count' => 2,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'players_on_field' => 'integer',
            'goalkeepers_count' => 'integer',
            'defenders_count' => 'integer',
            'midfielders_count' => 'integer',
            'forwards_count' => 'integer',
        ];
    }

    public static function current(): Model
    {
        return self::query()->firstOrCreate([], [
            'team_name' => 'Soccer Squad',
            'players_on_field' => 9,
            'goalkeepers_count' => 1,
            'defenders_count' => 3,
            'midfielders_count' => 3,
            'forwards_count' => 2,
        ]);
    }

    /**
     * @return array<string, int>
     */
    public function formationCounts(): array
    {
        return [
            'players_on_field' => $this->players_on_field,
            'goalkeepers_count' => $this->goalkeepers_count,
            'defenders_count' => $this->defenders_count,
            'midfielders_count' => $this->midfielders_count,
            'forwards_count' => $this->forwards_count,
        ];
    }
}
