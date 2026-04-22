<?php

namespace Database\Factories;

use App\Models\GameLine;
use App\Models\GameLineAssignment;
use App\Models\Player;
use App\PositionGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameLineAssignment>
 */
class GameLineAssignmentFactory extends Factory
{
    protected $model = GameLineAssignment::class;

    public function definition(): array
    {
        return [
            'game_line_id' => GameLine::factory(),
            'position' => fake()->randomElement(PositionGroup::ordered())->value,
            'slot_number' => 1,
            'player_id' => Player::factory(),
        ];
    }
}
