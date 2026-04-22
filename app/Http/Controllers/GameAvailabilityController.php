<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGameAvailabilityRequest;
use App\Models\Game;
use App\Models\Player;
use App\Services\GameLinePlanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class GameAvailabilityController extends Controller
{
    public function update(UpdateGameAvailabilityRequest $request, Game $game, Player $player, GameLinePlanner $planner): JsonResponse|RedirectResponse
    {
        if ($request->boolean('is_available')) {
            $game->availabilities()->where('player_id', $player->id)->delete();
        } else {
            $game->availabilities()->updateOrCreate(
                ['player_id' => $player->id],
                ['is_available' => false],
            );
        }

        $planner->rebalanceAssignmentsForAvailability($game->fresh());

        return $this->respondWithRedirect(
            $request,
            'games.show',
            $game,
            $request->boolean('is_available')
                ? sprintf('%s is available again for this game.', $player->name)
                : sprintf('%s is now marked unavailable for this game.', $player->name),
        );
    }
}
