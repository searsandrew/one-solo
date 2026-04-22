<?php

namespace App\Models;

use Database\Factories\GameLineFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameLine extends Model
{
    /** @use HasFactory<GameLineFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'game_id',
        'line_number',
        'label',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'line_number' => 'int',
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(GameLineAssignment::class)
            ->orderByRaw(
                "case position when 'goalkeeper' then 0 when 'defender' then 1 when 'midfielder' then 2 else 3 end"
            )
            ->orderBy('slot_number');
    }

    public function previousLine(): ?self
    {
        return $this->game
            ->lines()
            ->where('line_number', '<', $this->line_number)
            ->with('assignments.substitutions')
            ->latest('line_number')
            ->first();
    }

    /**
     * @return array<int, int>
     */
    public function participantIds(): array
    {
        $assignments = $this->relationLoaded('assignments')
            ? $this->assignments
            : $this->assignments()->with('substitutions')->get();

        return $assignments
            ->flatMap(fn (GameLineAssignment $assignment) => collect([$assignment->player_id])
                ->merge($assignment->substitutions->pluck('outgoing_player_id'))
                ->merge($assignment->substitutions->pluck('incoming_player_id')))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
