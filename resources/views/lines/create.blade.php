<x-layouts.app :title="'Build Line '.$nextLineNumber">
    <section class="rounded-4xl border border-emerald-200/80 bg-emerald-950 p-5 text-white shadow-xl shadow-emerald-950/20">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-200">Line planner</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight">Line {{ $nextLineNumber }}</h1>
                <p class="mt-2 text-sm text-emerald-100">
                    {{ $game->players_on_field }} on the field with a {{ $game->goalkeepers_count }}-{{ $game->defenders_count }}-{{ $game->midfielders_count }}-{{ $game->forwards_count }} shape.
                </p>
            </div>

            <div class="flex flex-col gap-2">
                <a href="{{ route('games.show', $game, false) }}" class="rounded-2xl bg-white/10 px-4 py-3 text-sm font-semibold text-white backdrop-blur">
                    Back
                </a>
                <a href="{{ route('games.lines.create', [$game, 'autofill' => 1], false) }}" class="rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-emerald-950">
                    Auto-fill
                </a>
            </div>
        </div>
    </section>

    @if ($previousLine || $satPlayers->isNotEmpty() || $unavailablePlayers->isNotEmpty())
        <section class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-lg shadow-emerald-950/5 backdrop-blur">
            @if ($previousLine)
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Last line played</p>
                    <p class="mt-2 text-lg font-semibold text-slate-950">{{ $previousLine->label ?? 'Line '.$previousLine->line_number }}</p>
                </div>
            @endif

            @if ($satPlayers->isNotEmpty())
                <div class="mt-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Sat last line</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($satPlayers as $player)
                            <span class="rounded-full bg-amber-100 px-3 py-2 text-sm font-semibold text-amber-900">
                                {{ $player->displayName() }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($unavailablePlayers->isNotEmpty())
                <div class="mt-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Unavailable today</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($unavailablePlayers as $player)
                            <span class="rounded-full bg-rose-100 px-3 py-2 text-sm font-semibold text-rose-900">
                                {{ $player->displayName() }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    @endif

    <form method="POST" action="{{ route('games.lines.store', $game, false) }}" data-native-form class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-lg shadow-emerald-950/5 backdrop-blur">
        @csrf
        @include('partials.errors')

        <div class="flex flex-col gap-4">
            @foreach ($slots as $index => $slot)
                @php
                    $selectedPlayerId = $selectedBySlot->get($slot['key']);
                    $slotBuckets = $candidateBuckets->get($slot['key'], []);
                @endphp

                <div class="rounded-3xl border border-slate-200 bg-slate-50/80 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-lg font-semibold text-slate-950">{{ $slot['position']->label() }} {{ $slot['slot_number'] }}</p>
                            <p class="mt-1 text-sm text-slate-600">
                                Sat players are listed first, then anyone who prefers this spot.
                            </p>
                        </div>

                        <span class="rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-white">
                            {{ $slot['position']->pluralLabel() }}
                        </span>
                    </div>

                    <input type="hidden" name="assignments[{{ $index }}][position]" value="{{ $slot['position']->value }}">
                    <input type="hidden" name="assignments[{{ $index }}][slot_number]" value="{{ $slot['slot_number'] }}">

                    <select name="assignments[{{ $index }}][player_id]" class="mt-4 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none focus:border-emerald-400">
                        <option value="">Choose a player</option>
                        @foreach ($slotBuckets as $label => $players)
                            <optgroup label="{{ $label }}">
                                @foreach ($players as $player)
                                    <option value="{{ $player->id }}" @selected((string) old("assignments.$index.player_id", $selectedPlayerId) === (string) $player->id)>
                                        {{ $player->displayName() }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>

                    @error("assignments.$index.player_id")
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach

            <div>
                <label for="notes" class="text-sm font-semibold text-slate-800">Line notes</label>
                <textarea id="notes" name="notes" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none placeholder:text-slate-400 focus:border-emerald-400" placeholder="Optional reminders for this line.">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20">
                Save line {{ $nextLineNumber }}
            </button>
        </div>
    </form>
</x-layouts.app>
