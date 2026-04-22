<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GameLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameLine>
 */
class GameLineFactory extends Factory
{
    protected $model = GameLine::class;

    public function definition(): array
    {
        return [
            'game_id' => Game::factory(),
            'line_number' => 1,
            'label' => 'Line 1',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
