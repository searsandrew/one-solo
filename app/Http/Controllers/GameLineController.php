<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameLine;
use App\Models\Player;
use App\Services\GameLinePlanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameLineController extends Controller
{
    public function create(Game $game, Request $request, GameLinePlanner $planner): View
    {
        $previousLine = $game->lines()->with('assignments.substitutions')->latest('line_number')->first();
        $slots = $planner->positionSlots($game);
        $autoAssignments = $request->boolean('autofill')
            ? collect($planner->autoAssignments($game, $previousLine))
            : collect();

        $selectedBySlot = $autoAssignments->mapWithKeys(
            fn (array $assignment): array => [
                $planner->slotKey($assignment['position'], $assignment['slot_number']) => $assignment['player_id'],
            ],
        );

        return view('lines.create', [
            'game' => $game,
            'previousLine' => $previousLine,
            'nextLineNumber' => $game->nextLineNumber(),
            'slots' => $slots,
            'selectedBySlot' => $selectedBySlot,
            'candidateBuckets' => $slots->mapWithKeys(
                fn (array $slot): array => [
                    $slot['key'] => $planner->candidateBuckets($game, $slot['position'], $previousLine),
                ],
            ),
            'satPlayers' => $planner->satPlayers($game, $previousLine),
            'unavailablePlayers' => $game->availabilities()
                ->where('is_available', false)
                ->with('player')
                ->get()
                ->pluck('player'),
        ]);
    }

    public function store(StoreGameLineRequest $request, Game $game): JsonResponse|RedirectResponse
    {
        $lineNumber = $game->nextLineNumber();

        $line = $game->lines()->create([
            'line_number' => $lineNumber,
            'label' => sprintf('Line %d', $lineNumber),
            'notes' => $request->validated('notes'),
        ]);

        $line->assignments()->createMany(
            collect($request->validated('assignments'))
                ->map(fn (array $assignment): array => [
                    'position' => $assignment['position'],
                    'slot_number' => $assignment['slot_number'],
                    'player_id' => $assignment['player_id'],
                ])
                ->all(),
        );

        return $this->respondWithRedirect(
            $request,
            'games.lines.show',
            [$game, $line],
            sprintf('Line %d is ready.', $lineNumber),
        );
    }

    public function show(Game $game, GameLine $line, GameLinePlanner $planner): View
    {
        $line->load([
            'game',
            'assignments.player',
            'assignments.substitutions.incomingPlayer',
            'assignments.substitutions.outgoingPlayer',
        ]);

        /** @var Collection<int, array<string, Collection<int, Player>>> $substitutionBuckets */
        $substitutionBuckets = $line->assignments->mapWithKeys(
            fn ($assignment): array => [
                $assignment->id => $planner->substitutionCandidateBuckets($assignment),
            ],
        );

        return view('lines.show', [
            'game' => $game,
            'line' => $line,
            'benchPlayers' => $planner->benchPlayers($line),
            'substitutionBuckets' => $substitutionBuckets,
            'nextLineExists' => $game->lines()->where('line_number', '>', $line->line_number)->exists(),
        ]);
    }
}
