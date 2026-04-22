<x-layouts.app title="Edit Game">
    <section class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-xl shadow-emerald-950/10 backdrop-blur">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Schedule</p>
                <h1 class="mt-1 text-3xl font-semibold tracking-tight text-slate-950">vs {{ $game->opponent }}</h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Adjust the game setup if the format or field size changes for this match.
                </p>
            </div>
            <a href="{{ route('games.show', $game, false) }}" class="text-sm font-semibold text-emerald-700">Back</a>
        </div>
    </section>

    <form method="POST" action="{{ route('games.update', $game, false) }}" data-native-form class="rounded-4xl border border-white/80 bg-white/85 p-5 shadow-lg shadow-emerald-950/5 backdrop-blur">
        @csrf
        @method('PUT')
        @include('games._form', ['submitLabel' => 'Save changes'])
    </form>
</x-layouts.app>
