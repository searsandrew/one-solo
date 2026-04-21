<?php

use App\Models\Team;

test('team factory can create a team', function () {
    $team = Team::factory()->create();

    $this->assertModelExists($team);
    expect($team->id)->not->toBeEmpty();
});

test('team model has the expected fillable attributes', function () {
    $team = new Team;

    expect($team->getFillable())->toBe([
        'team_name',
        'players_on_field',
        'goalkeepers_count',
        'defenders_count',
        'midfielders_count',
        'forwards_count',
    ]);
});

test('team numeric attributes are cast to integers', function () {
    $team = Team::factory()->create([
        'players_on_field' => '9',
        'goalkeepers_count' => '1',
        'defenders_count' => '3',
        'midfielders_count' => '3',
        'forwards_count' => '2',
    ]);

    expect($team->players_on_field)->toBeInt()->toBe(9)
        ->and($team->goalkeepers_count)->toBeInt()->toBe(1)
        ->and($team->defenders_count)->toBeInt()->toBe(3)
        ->and($team->midfielders_count)->toBeInt()->toBe(3)
        ->and($team->forwards_count)->toBeInt()->toBe(2);
});
