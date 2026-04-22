<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'team_name' => 'Soccer Squad',
            'players_on_field' => 9,
            'goalkeepers_count' => 1,
            'defenders_count' => 3,
            'midfielders_count' => 3,
            'forwards_count' => 2,
        ];
    }
}
