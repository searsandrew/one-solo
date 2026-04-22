<?php

namespace Database\Factories;

use App\Models\GameLineAssignment;
use App\Models\GameLineSubstitution;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameLineSubstitution>
 */
class GameLineSubstitutionFactory extends Factory
{
    protected $model = GameLineSubstitution::class;

    public function definition(): array
    {
        return [
            'game_line_assignment_id' => GameLineAssignment::factory(),
            'outgoing_player_id' => Player::factory(),
            'incoming_player_id' => Player::factory(),
            'reason' => 'substitution',
        ];
    }
}
