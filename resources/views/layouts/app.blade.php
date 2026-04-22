<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, viewport-fit=cover">
        <title>{{ $title ?? config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="nativephp-safe-area min-h-screen text-slate-950">
        <div class="relative min-h-screen overflow-x-hidden pb-28">
            <div class="pointer-events-none absolute inset-x-0 top-0 h-64 bg-linear-to-b from-emerald-200/70 via-lime-100/40 to-transparent"></div>
            <div class="pointer-events-none absolute -right-12 top-12 h-44 w-44 rounded-full bg-amber-200/45 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-16 top-52 h-56 w-56 rounded-full bg-emerald-300/35 blur-3xl"></div>

            <main class="relative mx-auto flex min-h-screen w-full max-w-md flex-col px-4 pb-6 pt-4">
                {{ $slot }}
            </main>
        </div>

        <native:bottom-nav>
            <native:bottom-nav-item
                id="home"
                icon="home"
                label="Home"
                :url="route('dashboard')"
                :active="request()->routeIs('dashboard')"
            />
            <native:bottom-nav-item
                id="games"
                icon="calendar"
                label="Games"
                :url="route('games.index')"
                :active="request()->routeIs('games.*')"
            />
            <native:bottom-nav-item
                id="roster"
                icon="person.3.fill"
                label="Roster"
                :url="route('players.index')"
                :active="request()->routeIs('players.*')"
            />
            <native:bottom-nav-item
                id="settings"
                icon="gearshape.fill"
                label="Settings"
                :url="route('teams.index')"
                :active="request()->routeIs('teams.*')"
            />
        </native:bottom-nav>
        @livewireScripts
    </body>
</html>
