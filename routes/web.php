<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('players', PlayerController::class)->except(['show', 'destroy']);
Route::resource('games', GameController::class)->except(['destroy']);
