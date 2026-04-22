@include('partials.errors')

<div class="flex flex-col gap-4">
    <div>
        <label for="opponent" class="text-sm font-semibold text-slate-800">Opponent</label>
        <input id="opponent" name="opponent" type="text" value="{{ old('opponent', $game->opponent) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none placeholder:text-slate-400 focus:border-emerald-400" placeholder="Lions FC">
        @error('opponent')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="scheduled_at" class="text-sm font-semibold text-slate-800">Date and time</label>
        <input id="scheduled_at" name="scheduled_at" type="datetime-local" value="{{ old('scheduled_at', $game->scheduled_at?->format('Y-m-d\TH:i')) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none focus:border-emerald-400">
        @error('scheduled_at')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="location" class="text-sm font-semibold text-slate-800">Location</label>
        <input id="location" name="location" type="text" value="{{ old('location', $game->location) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none placeholder:text-slate-400 focus:border-emerald-400" placeholder="Community Sports Complex">
        @error('location')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <section class="rounded-3xl border border-slate-200 bg-slate-50/80 p-4">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">Formation for this game</h2>
                <p class="mt-1 text-sm leading-6 text-slate-600">
                    Goalie stays at 1. Defenders, midfielders, and forwards can each be between 1 and 3 as long as the total matches the players on the field.
                </p>
            </div>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-3">
            <div class="col-span-2">
                <label for="players_on_field" class="text-sm font-semibold text-slate-800">Players on the field</label>
                <input id="players_on_field" name="players_on_field" type="number" min="4" max="10" value="{{ old('players_on_field', $game->players_on_field) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none focus:border-emerald-400">
                @error('players_on_field')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="goalkeepers_count" class="text-sm font-semibold text-slate-800">Goalies</label>
                <input id="goalkeepers_count" name="goalkeepers_count" type="number" min="1" max="1" value="{{ old('goalkeepers_count', $game->goalkeepers_count) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none focus:border-emerald-400">
                @error('goalkeepers_count')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="defenders_count" class="text-sm font-semibold text-slate-800">Defenders</label>
                <input id="defenders_count" name="defenders_count" type="number" min="1" max="3" value="{{ old('defenders_count', $game->defenders_count) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none focus:border-emerald-400">
                @error('defenders_count')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="midfielders_count" class="text-sm font-semibold text-slate-800">Midfielders</label>
                <input id="midfielders_count" name="midfielders_count" type="number" min="1" max="3" value="{{ old('midfielders_count', $game->midfielders_count) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none focus:border-emerald-400">
                @error('midfielders_count')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="forwards_count" class="text-sm font-semibold text-slate-800">Forwards</label>
                <input id="forwards_count" name="forwards_count" type="number" min="1" max="3" value="{{ old('forwards_count', $game->forwards_count) }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none focus:border-emerald-400">
                @error('forwards_count')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    <div>
        <label for="notes" class="text-sm font-semibold text-slate-800">Game notes</label>
        <textarea id="notes" name="notes" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-950 outline-none placeholder:text-slate-400 focus:border-emerald-400" placeholder="Anything to remember about rotations, field size, or game-day plans.">{{ old('notes', $game->notes) }}</textarea>
        @error('notes')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-900/20">
        {{ $submitLabel }}
    </button>
</div>
