<?php

namespace App\Models;

use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    /** @use HasFactory<GameFactory> */
    use HasFactory, HasUlids;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'opponent',
        'location',
        'scheduled_at',
        'players_on_field',
        'goalkeepers_count',
        'defenders_count',
        'midfielders_count',
        'forwards_count',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'players_on_field' => 'int',
            'goalkeepers_count' => 'int',
            'defenders_count' => 'int',
            'midfielders_count' => 'int',
            'forwards_count' => 'int',
        ];
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(GameAvailability::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(GameLine::class)->orderBy('line_number');
    }

    public function nextLineNumber(): int
    {
        return (int) $this->lines()->max('line_number') + 1;
    }

    /**
     * @return array<string, int>
     */
    public function formationCounts(): array
    {
        return [
            'players_on_field' => $this->players_on_field,
            'goalkeepers_count' => $this->goalkeepers_count,
            'defenders_count' => $this->defenders_count,
            'midfielders_count' => $this->midfielders_count,
            'forwards_count' => $this->forwards_count,
        ];
    }

    /**
     * @return array<int, int>
     */
    public function unavailablePlayerIds(): array
    {
        return $this->availabilities()
            ->where('is_available', false)
            ->pluck('player_id')
            ->all();
    }

    public function availablePlayersQuery(): Builder
    {
        $unavailablePlayerIds = $this->unavailablePlayerIds();

        return Player::query()
            ->active()
            ->when(
                $unavailablePlayerIds !== [],
                fn (Builder $query): Builder => $query->whereNotIn('id', $unavailablePlayerIds),
            )
            ->orderBy('name');
    }
}
