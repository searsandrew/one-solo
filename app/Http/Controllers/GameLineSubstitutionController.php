<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameLineSubstitutionRequest;
use App\Models\Game;
use App\Models\GameLine;
use App\Models\GameLineAssignment;
use App\Models\GameLineSubstitution;
use App\Services\GameLinePlanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GameLineSubstitutionController extends Controller
{
    public function store(StoreGameLineSubstitutionRequest $request, Game $game, GameLine $line, GameLineAssignment $assignment, GameLinePlanner $planner): JsonResponse|RedirectResponse
    {
        $assignment->load('substitutions.incomingPlayer', 'player');

        $outgoingPlayerId = $assignment->currentPlayerId();

        $assignment->substitutions()->create([
            'outgoing_player_id' => $outgoingPlayerId,
            'incoming_player_id' => $request->integer('incoming_player_id'),
            'reason' => $request->string('reason')->toString() !== ''
                ? $request->string('reason')->toString()
                : 'substitution',
        ]);

        if ($request->boolean('mark_player_unavailable') && $outgoingPlayerId !== null) {
            $game->availabilities()->updateOrCreate(
                ['player_id' => $outgoingPlayerId],
                ['is_available' => false],
            );

            $planner->rebalanceAssignmentsForAvailability($game->fresh(), $line->line_number);
        }

        return $this->respondWithRedirect(
            $request,
            'games.lines.show',
            [$game, $line],
            'Substitution recorded and the next line order has been updated.',
        );
    }
}
