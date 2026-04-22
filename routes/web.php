<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameAvailabilityController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameLineController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'dashboard')->name('dashboard');

Route::resource('players', PlayerController::class)->except(['show', 'destroy']);
Route::resource('games', GameController::class)->except(['destroy']);
Route::resource('teams', TeamController::class)->except(['destroy']);

Route::put('/games/{game}/players/{player}/availability', [GameAvailabilityController::class, 'update'])
    ->name('games.players.availability.update');

Route::scopeBindings()->group(function (): void {
    Route::get('/games/{game}/lines/create', [GameLineController::class, 'create'])
        ->name('games.lines.create');
    Route::post('/games/{game}/lines', [GameLineController::class, 'store'])
        ->name('games.lines.store');
    Route::get('/games/{game}/lines/{line}', [GameLineController::class, 'show'])
        ->name('games.lines.show');
});
