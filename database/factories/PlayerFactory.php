<?php

namespace Database\Factories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'jersey_number' => (string) fake()->numberBetween(0, 99),
            'preferred_position' => fake()->randomElement([
                'Guard',
                'Forward',
                'Center',
            ]),
            'active' => fake()->boolean(80),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
