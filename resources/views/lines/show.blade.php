<x-layouts.app :title="$line->label ?? 'Line '.$line->line_number">
    <section class="rounded-4xl border border-emerald-200/80 bg-emerald-950 p-5 text-white shadow-xl shadow-emerald-950/20">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-200">Live line</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight">{{ $line->label ?? 'Line '.$line->line_number }}</h1>
                <p class="mt-2 text-sm text-emerald-100">
                    vs {{ $game->opponent }} · {{ $game->scheduled_at->format('M j, g:i A') }}
                </p>
            </div>

            <div class="flex flex-col gap-2">
                <a href="{{ route('games.show', $game, false) }}" class="rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white backdrop-blur">
                    Back
                </a>
                <a href="{{ route('games.lines.create', [$game, 'autofill' => 1], false) }}" class="rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-emerald-950">
                    Plan next line
                </a>
            </div>
        </div>
    </section>

    <section class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-lg shadow-emerald-950/5 backdrop-blur">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Bench</p>
                <h2 class="mt-1 text-xl font-semibold text-slate-950">Who is sitting right now</h2>
            </div>

            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-amber-800">
                {{ $benchPlayers->count() }} available
            </span>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            @forelse ($benchPlayers as $player)
                <span class="rounded-full bg-white px-3 py-2 text-sm font-medium text-slate-900 shadow-sm">
                    {{ $player->displayName() }}
                </span>
            @empty
                <p class="text-sm text-slate-600">Everyone available is already in this line.</p>
            @endforelse
        </div>
    </section>

    <section class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-lg shadow-emerald-950/5 backdrop-blur">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">On the field</p>
                <h2 class="mt-1 text-xl font-semibold text-slate-950">Current assignments</h2>
            </div>

            @if ($nextLineExists)
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-700">
                    Another line is already planned
                </span>
            @endif
        </div>

        <div class="mt-4 flex flex-col gap-4">
            @foreach ($line->assignments as $assignment)
                @php
                    $currentPlayer = $assignment->currentPlayer();
                    $buckets = $substitutionBuckets->get($assignment->id, []);
                @endphp

                <article class="rounded-3xl border border-slate-200 bg-slate-50/80 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-lg font-semibold text-slate-950">{{ $assignment->position->label() }} {{ $assignment->slot_number }}</p>
                            <p class="mt-1 text-sm text-slate-600">
                                @if ($currentPlayer)
                                    {{ $currentPlayer->displayName() }}
                                @else
                                    Needs a player
                                @endif
                            </p>
                        </div>

                        @if ($assignment->substitutions->isNotEmpty())
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-amber-800">
                                {{ $assignment->substitutions->count() }} sub{{ $assignment->substitutions->count() === 1 ? '' : 's' }}
                            </span>
                        @endif
                    </div>

                    @if ($assignment->player && $currentPlayer?->isNot($assignment->player))
                        <p class="mt-3 text-sm text-slate-600">
                            Started with {{ $assignment->player->displayName() }}.
                        </p>
                    @endif

                    @if ($assignment->substitutions->isNotEmpty())
                        <div class="mt-3 rounded-3xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Sub history</p>
                            <div class="mt-3 flex flex-col gap-2">
                                @foreach ($assignment->substitutions as $substitution)
                                    <p class="text-sm text-slate-700">
                                        {{ $substitution->outgoingPlayer->displayName() }} out, {{ $substitution->incomingPlayer->displayName() }} in
                                        @if ($substitution->reason !== 'substitution')
                                            · {{ $substitution->reason }}
                                        @endif
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($currentPlayer && $buckets !== [])
                        <form method="POST" action="{{ route('games.lines.assignments.substitutions.store', [$game, $line, $assignment], false) }}" data-native-form class="mt-4 rounded-3xl border border-slate-200 bg-white px-4 py-4">
                            @csrf
                            <label for="incoming_player_id_{{ $assignment->id }}" class="text-sm font-semibold text-slate-800">
                                Quick substitution
                            </label>
                            <select id="incoming_player_id_{{ $assignment->id }}" name="incoming_player_id" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none focus:border-emerald-400">
                                <option value="">Choose who went in</option>
                                @foreach ($buckets as $label => $players)
                                    <optgroup label="{{ $label }}">
                                        @foreach ($players as $player)
                                            <option value="{{ $player->id }}">{{ $player->displayName() }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>

                            <div class="mt-3">
                                <label for="reason_{{ $assignment->id }}" class="text-sm font-semibold text-slate-800">Reason</label>
                                <input id="reason_{{ $assignment->id }}" name="reason" type="text" value="Injury sub" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none focus:border-emerald-400">
                            </div>

                            <label class="mt-3 flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Mark {{ $currentPlayer->name }} unavailable for the rest of the game</p>
                                    <p class="mt-1 text-sm text-slate-600">Future lines will swap them out automatically if possible.</p>
                                </div>
                                <div class="shrink-0">
                                    <input type="hidden" name="mark_player_unavailable" value="0">
                                    <input type="checkbox" name="mark_player_unavailable" value="1" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                </div>
                            </label>

                            <button type="submit" class="mt-3 w-full rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white">
                                Save substitution
                            </button>
                        </form>
                    @elseif (! $currentPlayer)
                        <p class="mt-4 text-sm text-rose-600">This slot still needs a player before the line is ready.</p>
                    @else
                        <p class="mt-4 text-sm text-slate-600">No bench players are available for a substitution here.</p>
                    @endif
                </article>
            @endforeach
        </div>
    </section>
</x-layouts.app>
