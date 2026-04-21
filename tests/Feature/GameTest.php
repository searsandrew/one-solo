<?php

use App\Models\Game;

test('game factory can create a game', function () {
    $game = Game::factory()->create();

    $this->assertModelExists($game);
    expect($game->id)->not->toBeEmpty();
});

test('game model has the expected fillable attributes', function () {
    $game = new Game;

    expect($game->getFillable())->toBe([
        'opponent',
        'location',
        'scheduled_at',
        'players_on_field',
        'goalkeepers_count',
        'defenders_count',
        'midfielders_count',
        'forwards_count',
        'notes',
    ]);
});

test('game scheduled_at attribute is cast to datetime', function () {
    $game = Game::factory()->create([
        'scheduled_at' => '2026-04-21 10:30:00',
    ]);

    expect($game->scheduled_at)->toBeInstanceOf(Carbon\Carbon::class);
});
