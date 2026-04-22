<x-layouts.app :title="'Game vs '.$game->opponent">
    <section class="rounded-4xl border border-emerald-200/80 bg-emerald-950 p-5 text-white shadow-xl shadow-emerald-950/20">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-200">Game board</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight">vs {{ $game->opponent }}</h1>
                <p class="mt-2 text-sm text-emerald-100">
                    {{ $game->scheduled_at->format('D, M j \a\t g:i A') }}
                    @if ($game->location)
                        · {{ $game->location }}
                    @endif
                </p>
            </div>

            <a href="{{ route('games.edit', $game, false) }}" class="rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white backdrop-blur">
                Edit
            </a>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3">
            <a href="{{ route('games.lines.create', $game, false) }}" class="rounded-2xl bg-white px-4 py-3 text-center text-sm font-semibold text-emerald-950">
                Build line {{ $nextLineNumber }}
            </a>
            <a href="{{ route('games.lines.create', [$game, 'autofill' => 1], false) }}" class="rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-center text-sm font-semibold text-white backdrop-blur">
                Auto-build line {{ $nextLineNumber }}
            </a>
        </div>
    </section>

    <section class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-lg shadow-emerald-950/5 backdrop-blur">
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-3xl bg-slate-950 px-4 py-4 text-white">
                <p class="text-xs uppercase tracking-[0.22em] text-slate-300">Formation</p>
                <p class="mt-2 text-3xl font-semibold">
                    {{ $game->goalkeepers_count }}-{{ $game->defenders_count }}-{{ $game->midfielders_count }}-{{ $game->forwards_count }}
                </p>
                <p class="mt-1 text-sm text-slate-300">{{ $game->players_on_field }} players on the field</p>
            </div>

            <div class="rounded-3xl bg-amber-100 px-4 py-4 text-amber-950">
                <p class="text-xs uppercase tracking-[0.22em] text-amber-700">Lines planned</p>
                <p class="mt-2 text-3xl font-semibold">{{ $game->lines->count() }}</p>
                <p class="mt-1 text-sm text-amber-800">ready to rotate</p>
            </div>
        </div>

        @if ($game->lines->isNotEmpty())
            <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50/80 p-4">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Who sat last line</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @forelse ($satPlayers as $player)
                        <span class="rounded-full bg-white px-3 py-2 text-sm font-medium text-slate-900 shadow-sm">
                            {{ $player->displayName() }}
                        </span>
                    @empty
                        <p class="text-sm text-slate-600">No one sat the last planned line yet.</p>
                    @endforelse
                </div>
            </div>
        @endif

        @if ($game->notes)
            <div class="mt-4 rounded-3xl border border-slate-200 bg-white px-4 py-4">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Notes</p>
                <p class="mt-2 text-sm leading-6 text-slate-700">{{ $game->notes }}</p>
            </div>
        @endif
    </section>

    <section class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-lg shadow-emerald-950/5 backdrop-blur">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Game day</p>
                <h2 class="mt-1 text-xl font-semibold text-slate-950">Availability</h2>
            </div>

            @if ($unavailablePlayerIds !== [])
                <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-rose-800">
                    {{ count($unavailablePlayerIds) }} unavailable
                </span>
            @endif
        </div>

        <div class="mt-4 flex flex-col gap-3">
            @foreach ($players as $player)
                @php
                    $isAvailable = ! in_array($player->id, $unavailablePlayerIds, true);
                @endphp

                <div class="rounded-3xl border border-slate-200/80 bg-slate-50/80 px-4 py-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-base font-semibold text-slate-950">{{ $player->displayName() }}</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-700">
                                    {{ $player->preferred_position?->label() ?? 'Flexible' }}
                                </span>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $isAvailable ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $isAvailable ? 'Available' : 'Unavailable' }}
                                </span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('games.players.availability.update', [$game, $player], false) }}" data-native-form>
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_available" value="{{ $isAvailable ? 0 : 1 }}">
                            <button type="submit" class="rounded-2xl px-4 py-3 text-sm font-semibold {{ $isAvailable ? 'bg-rose-600 text-white shadow-lg shadow-rose-900/15' : 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/15' }}">
                                {{ $isAvailable ? 'Mark unavailable' : 'Mark available' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-lg shadow-emerald-950/5 backdrop-blur">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Rotation</p>
                <h2 class="mt-1 text-xl font-semibold text-slate-950">Lines</h2>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-700">
                Next line {{ $nextLineNumber }}
            </span>
        </div>

        <div class="mt-4 flex flex-col gap-3">
            @forelse ($game->lines as $line)
                <a href="{{ route('games.lines.show', [$game, $line], false) }}" class="rounded-3xl border border-slate-200/80 bg-slate-50/80 px-4 py-4 transition hover:border-emerald-200 hover:bg-white">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-lg font-semibold text-slate-950">{{ $line->label ?? 'Line '.$line->line_number }}</p>
                            <p class="mt-1 text-sm text-slate-600">
                                @foreach ($line->assignments as $assignment)
                                    {{ $assignment->position->label() }} {{ $assignment->slot_number }}: {{ $assignment->currentPlayer()?->displayName() ?? 'Needs player' }}@if (! $loop->last), @endif
                                @endforeach
                            </p>
                        </div>
                        <span class="text-sm font-semibold text-emerald-700">Open</span>
                    </div>
                </a>
            @empty
                <p class="rounded-3xl bg-slate-50 px-4 py-4 text-sm text-slate-600">
                    No lines yet. Build the first one manually or use the auto-build button above.
                </p>
            @endforelse
        </div>
    </section>
</x-layouts.app>
