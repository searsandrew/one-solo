<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GameAvailability;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameAvailability>
 */
class GameAvailabilityFactory extends Factory
{
    protected $model = GameAvailability::class;

    public function definition(): array
    {
        return [
            'game_id' => Game::factory(),
            'player_id' => Player::factory(),
            'is_available' => false,
        ];
    }
}
