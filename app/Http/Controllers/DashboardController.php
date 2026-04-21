<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use App\Models\Team;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        return view('dashboard', [
            'team' => Team::current(),
            'playerCount' => Player::query()->active()->count(),
            'nextGame' => Game::query()
                ->orderBy('scheduled_at')
                ->with('lines.assignments.player')
                ->first(),
            'upcomingGames' => Game::query()
                ->orderBy('scheduled_at')
                ->take(4)
                ->get(),
        ]);
    }
}
