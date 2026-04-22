<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'opponent' => fake()->city().' United',
            'location' => fake()->optional()->company().' Park',
            'scheduled_at' => fake()->dateTimeBetween('now', '+30 days'),
            'players_on_field' => 9,
            'goalkeepers_count' => 1,
            'defenders_count' => 3,
            'midfielders_count' => 3,
            'forwards_count' => 2,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
