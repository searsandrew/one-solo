<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::resource('players', PlayerController::class)->except(['show', 'destroy']);
Route::resource('games', GameController::class)->except(['destroy']);
Route::resource('teams', TeamController::class)->except(['destroy']);
