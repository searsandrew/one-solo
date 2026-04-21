<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_name' => fake()->words(2, true),
            'players_on_field' => 9,
            'goalkeepers_count' => 1,
            'defenders_count' => 3,
            'midfielders_count' => 3,
            'forwards_count' => 2,
        ];
    }
}
