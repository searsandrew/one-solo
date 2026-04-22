<?php

namespace App\Models;

use App\PositionGroup;
use Database\Factories\GameLineAssignmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameLineAssignment extends Model
{
    /** @use HasFactory<GameLineAssignmentFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'game_line_id',
        'position',
        'slot_number',
        'player_id',
        'outgoing_player_id',
        'incoming_player_id',
        'reason',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'position' => PositionGroup::class,
            'slot_number' => 'int',
        ];
    }

    public function gameLine(): BelongsTo
    {
        return $this->belongsTo(GameLine::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function substitutions(): HasMany
    {
        return $this->hasMany(GameLineSubstitution::class)
            ->orderBy('created_at')
            ->orderBy('id');
    }

    public function currentPlayerId(): ?int
    {
        return $this->substitutions->last()?->incoming_player_id ?? $this->player_id;
    }

    public function currentPlayer(): ?Player
    {
        return $this->substitutions->last()?->incomingPlayer ?? $this->player;
    }
}
