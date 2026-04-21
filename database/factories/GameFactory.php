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
            'opponent' => fake()->company(),
            'location' => fake()->optional()->city(),
            'scheduled_at' => fake()->dateTimeBetween('now', '+3 months'),
            'players_on_field' => 11,
            'goalkeepers_count' => 1,
            'defenders_count' => 4,
            'midfielders_count' => 4,
            'forwards_count' => 2,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
