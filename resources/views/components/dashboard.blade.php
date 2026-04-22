<?php

use App\Models\Game;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    public Team $team {
        get {
            return Team::current();
        }
    }
    public int $playerCount {
        get {
            return Player::query()->active()->count();
        }
    }
    public ?Game $nextGame {
        get {
            return Game::query()->orderBy('scheduled_at')->with('lines.assignments.player')->first();
        }
    }
    public ?Collection $upcomingGames {
        get {
            return Game::query()->orderBy('scheduled_at')->take(4)->get();
        }
    }
};
?>

<dashboard class="flex flex-col space-y-5">
    <section class="rounded-4xl border border-white/80 bg-white/85 p-6 shadow-xl shadow-emerald-950/10 backdrop-blur">
        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Matchday board</p>
        <div class="mt-3 flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight text-slate-950">{{ $team->team_name }}</h1>
                <p class="mt-2 max-w-xs text-sm leading-6 text-slate-600">
                    Build balanced lines, track who sat, and keep game-day changes under control from the sideline.
                </p>
            </div>

            <div class="rounded-3xl bg-emerald-950 px-4 py-3 text-center text-white shadow-lg shadow-emerald-950/30">
                <p class="text-[11px] uppercase tracking-[0.24em] text-emerald-200">Default shape</p>
                <p class="mt-1 text-2xl font-semibold">
                    {{ $team->defenders_count }}-{{ $team->midfielders_count }}-{{ $team->forwards_count }}
                </p>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3">
            <div class="rounded-3xl bg-slate-950 px-4 py-4 text-white">
                <p class="text-xs uppercase tracking-[0.22em] text-slate-300">Roster</p>
                <p class="mt-2 text-3xl font-semibold">{{ $playerCount }}</p>
                <p class="mt-1 text-sm text-slate-300">active players</p>
            </div>

            <div class="rounded-3xl bg-amber-100 px-4 py-4 text-amber-950">
                <p class="text-xs uppercase tracking-[0.22em] text-amber-700">On the field</p>
                <p class="mt-2 text-3xl font-semibold">{{ $team->players_on_field }}</p>
                <p class="mt-1 text-sm text-amber-800">saved in settings</p>
            </div>
        </div>

        <div class="mt-5 flex gap-3">
            <a
                href="{{ route('games.create', absolute: false) }}"
                class="flex-1 rounded-2xl bg-emerald-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-lg shadow-emerald-900/20"
            >
                Add a game
            </a>
            <a
                href="{{ route('players.create', absolute: false) }}"
                class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-center text-sm font-semibold text-slate-900"
            >
                Add a player
            </a>
        </div>
    </section>

    @if ($nextGame)
        <section
            class="rounded-4xl border border-emerald-200/80 bg-emerald-950 p-5 text-white shadow-xl shadow-emerald-950/20">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200">Next up</p>
                    <h2 class="mt-2 text-2xl font-semibold">vs {{ $nextGame->opponent }}</h2>
                    <p class="mt-2 text-sm text-emerald-100">
                        {{ $nextGame->scheduled_at->format('D, M j \a\t g:i A') }}
                        @if ($nextGame->location)
                            · {{ $nextGame->location }}
                        @endif
                    </p>
                </div>

                <div class="rounded-3xl bg-white/10 px-4 py-3 text-right backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-100">Lines planned</p>
                    <p class="mt-1 text-3xl font-semibold">{{ $nextGame->lines->count() }}</p>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3">
                <a
                    href="{{ route('games.show', $nextGame, false) }}"
                    class="rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-emerald-950"
                >
                    Open game board
                </a>
                <a
                    href="{{ route('games.lines.create', [$nextGame, 'autofill' => 1], false) }}"
                    class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-center text-sm font-semibold text-white backdrop-blur"
                >
                    Auto-build next line
                </a>
            </div>
        </section>
    @else
        <section
            class="rounded-4xl border border-dashed border-slate-300 bg-white/80 p-6 text-center shadow-lg shadow-emerald-950/5 backdrop-blur">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-500">No games yet</p>
            <h2 class="mt-3 text-2xl font-semibold text-slate-950">Start with your first match</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">
                Add the schedule, then you can start planning lines around who sat last and who prefers each spot.
            </p>
            <a
                href="{{ route('games.create', absolute: false) }}"
                class="mt-5 inline-flex rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white"
            >
                Add the first game
            </a>
        </section>
    @endif

    <section class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-lg shadow-emerald-950/5 backdrop-blur">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Schedule</p>
                <h2 class="mt-1 text-xl font-semibold text-slate-950">Upcoming games</h2>
            </div>
            <a href="{{ route('games.index', absolute: false) }}" class="text-sm font-semibold text-emerald-700">See
                all</a>
        </div>

        <div class="mt-4 flex flex-col gap-3">
            @forelse ($upcomingGames as $game)
                <a
                    href="{{ route('games.show', $game, false) }}"
                    class="rounded-3xl border border-slate-200/80 bg-slate-50/80 px-4 py-4 transition hover:border-emerald-200 hover:bg-white"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-lg font-semibold text-slate-950">vs {{ $game->opponent }}</p>
                            <p class="mt-1 text-sm text-slate-600">
                                {{ $game->scheduled_at->format('D, M j \a\t g:i A') }}
                            </p>
                        </div>

                        <div
                            class="rounded-2xl bg-amber-100 px-3 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-amber-800">
                            {{ $game->players_on_field }} on
                        </div>
                    </div>
                </a>
            @empty
                <p class="rounded-3xl bg-slate-50 px-4 py-4 text-sm text-slate-600">
                    Once you add games, they’ll show up here for quick access.
                </p>
            @endforelse
        </div>
    </section>
</dashboard>
