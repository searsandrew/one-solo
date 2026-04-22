<?php

namespace App\Models;

use Database\Factories\GameLineSubstitutionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameLineSubstitution extends Model
{
    /** @use HasFactory<GameLineSubstitutionFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'game_line_assignment_id',
        'outgoing_player_id',
        'incoming_player_id',
        'reason',
    ];

    public function gameLineAssignment(): BelongsTo
    {
        return $this->belongsTo(GameLineAssignment::class);
    }

    public function outgoingPlayer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'outgoing_player_id');
    }

    public function incomingPlayer(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'incoming_player_id');
    }
}
