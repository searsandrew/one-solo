<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Game;
use App\Models\Player;
use App\Models\Team;
use App\Services\GameLinePlanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('games.index', [
            'games' => Game::query()->orderBy('scheduled_at')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('games.create', [
            'game' => new Game(Team::current()->formationCounts()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGameRequest $request): JsonResponse|RedirectResponse
    {
        $game = Game::query()->create($request->validated());

        return $this->respondWithRedirect(
            $request,
            'games.show',
            $game,
            'Game added to the schedule.',
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game, GameLinePlanner $planner): View
    {
        $game->load([
            'lines.assignments.player',
            'lines.assignments.substitutions.incomingPlayer',
            'lines.assignments.substitutions.outgoingPlayer',
            'availabilities',
        ]);

        return view('games.show', [
            'game' => $game,
            'players' => Player::query()->active()->orderBy('name')->get(),
            'availabilityByPlayer' => $game->availabilities->keyBy('player_id'),
            'nextLineNumber' => $game->nextLineNumber(),
            'satPlayers' => $planner->satPlayers($game, $game->lines->last()),
            'unavailablePlayerIds' => $game->unavailablePlayerIds(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game): View
    {
        return view('games.edit', [
            'game' => $game,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameRequest $request, Game $game): JsonResponse|RedirectResponse
    {
        $game->update($request->validated());

        return $this->respondWithRedirect(
            $request,
            'games.show',
            $game,
            'Game details updated.',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game): RedirectResponse
    {
        $game->delete();

        return redirect()
            ->to(route('games.index', absolute: false))
            ->with('status', 'Game removed from the schedule.');
    }
}
