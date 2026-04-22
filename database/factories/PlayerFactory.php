<?php

namespace Database\Factories;

use App\Models\Player;
use App\PositionGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Player>
 */
class PlayerFactory extends Factory
{
    public function definition(): array
    {
        $preferredPosition = fake()->optional()->randomElement(PositionGroup::ordered());

        return [
            'name' => fake()->name(),
            'jersey_number' => (string) fake()->unique()->numberBetween(1, 99),
            'preferred_position' => $preferredPosition?->value,
            'active' => true,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
