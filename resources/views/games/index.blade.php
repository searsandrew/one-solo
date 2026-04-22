<x-layouts.app title="Games">
    <section class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-xl shadow-emerald-950/10 backdrop-blur">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Schedule</p>
                <h1 class="mt-1 text-3xl font-semibold tracking-tight text-slate-950">Games</h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Each game keeps its own availability list, formation, and stack of planned lines.
                </p>
            </div>
            <a href="{{ route('games.create', absolute: false) }}" class="rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20">Add game</a>
        </div>
    </section>

    <section class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-lg shadow-emerald-950/5 backdrop-blur">
        <div class="flex flex-col gap-3">
            @forelse ($games as $game)
                <a href="{{ route('games.show', $game, false) }}" class="rounded-3xl border border-slate-200/80 bg-slate-50/80 px-4 py-4 transition hover:border-emerald-200 hover:bg-white">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-lg font-semibold text-slate-950">vs {{ $game->opponent }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $game->scheduled_at->format('D, M j \a\t g:i A') }}</p>
                            @if ($game->location)
                                <p class="mt-1 text-sm text-slate-500">{{ $game->location }}</p>
                            @endif
                        </div>

                        <div class="rounded-3xl bg-amber-100 px-3 py-2 text-right text-xs font-semibold uppercase tracking-[0.18em] text-amber-800">
                            <p>{{ $game->players_on_field }} on</p>
                            <p class="mt-1">{{ $game->goalkeepers_count }}-{{ $game->defenders_count }}-{{ $game->midfielders_count }}-{{ $game->forwards_count }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <p class="rounded-3xl bg-slate-50 px-4 py-4 text-sm text-slate-600">
                    No games are on the schedule yet. Add one to start planning rotations.
                </p>
            @endforelse
        </div>
    </section>
</x-layouts.app>
