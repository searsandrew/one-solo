<?php

use App\Models\Player;

test('player factory can create a player', function () {
    $player = Player::factory()->create();

    $this->assertModelExists($player);
    expect($player->id)->not->toBeEmpty();
});

test('player model has the expected fillable attributes', function () {
    $player = new Player;

    expect($player->getFillable())->toBe([
        'name',
        'jersey_number',
        'preferred_position',
        'active',
        'notes',
    ]);
});

test('player active attribute is cast to boolean', function () {
    $player = Player::factory()->create([
        'active' => 1,
    ]);

    expect($player->active)->toBeBool()->toBeTrue();
});
