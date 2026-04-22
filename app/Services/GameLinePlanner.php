<?php

namespace App\Services;

use App\Models\Game;
use App\Models\GameLine;
use App\Models\GameLineAssignment;
use App\Models\Player;
use App\PositionGroup;
use Illuminate\Support\Collection;

class GameLinePlanner
{
    /**
     * @return Collection<int, array{position: PositionGroup, slot_number: int, key: string}>
     */
    public function positionSlots(Game $game): Collection
    {
        return collect(PositionGroup::ordered())
            ->flatMap(function (PositionGroup $position) use ($game): Collection {
                return collect(range(1, $game->{$position->countColumn()}))
                    ->map(fn (int $slotNumber): array => [
                        'position' => $position,
                        'slot_number' => $slotNumber,
                        'key' => $this->slotKey($position, $slotNumber),
                    ]);
            })
            ->values();
    }

    public function slotKey(PositionGroup $position, int $slotNumber): string
    {
        return sprintf('%s:%d', $position->value, $slotNumber);
    }

    /**
     * @return array<int, int>
     */
    public function satPlayerIds(Game $game, ?GameLine $previousLine = null): array
    {
        if ($previousLine === null) {
            return [];
        }

        return array_values(array_diff(
            $game->availablePlayersQuery()->pluck('id')->all(),
            $previousLine->participantIds(),
        ));
    }

    /**
     * @return Collection<int, Player>
     */
    public function satPlayers(Game $game, ?GameLine $previousLine = null): Collection
    {
        $playerIds = $this->satPlayerIds($game, $previousLine);

        if ($playerIds === []) {
            return collect();
        }

        return Player::query()
            ->whereIn('id', $playerIds)
            ->orderBy('name')
            ->get();
    }

    /**
     * @return array<string, Collection<int, Player>>
     */
    public function candidateBuckets(
        Game $game,
        PositionGroup $position,
        ?GameLine $previousLine = null,
        array $excludePlayerIds = [],
    ): array {
        $players = $game->availablePlayersQuery()
            ->when(
                $excludePlayerIds !== [],
                fn ($query) => $query->whereNotIn('id', $excludePlayerIds),
            )
            ->get();

        $satPlayerIds = $this->satPlayerIds($game, $previousLine);

        $satPlayers = $players
            ->filter(fn (Player $player): bool => in_array($player->id, $satPlayerIds, true))
            ->values();

        $preferredPlayers = $players
            ->reject(fn (Player $player): bool => in_array($player->id, $satPlayerIds, true))
            ->filter(fn (Player $player): bool => $player->preferred_position === $position)
            ->values();

        $otherPlayers = $players
            ->reject(fn (Player $player): bool => in_array($player->id, $satPlayerIds, true))
            ->reject(fn (Player $player): bool => $player->preferred_position === $position)
            ->values();

        return array_filter([
            'Sat last line' => $satPlayers,
            sprintf('Prefers %s', $position->label()) => $preferredPlayers,
            'Everyone else' => $otherPlayers,
        ], fn (Collection $players): bool => $players->isNotEmpty());
    }

    /**
     * @return Collection<int, Player>
     */
    public function orderedCandidates(
        Game $game,
        PositionGroup $position,
        ?GameLine $previousLine = null,
        array $excludePlayerIds = [],
    ): Collection {
        return collect($this->candidateBuckets($game, $position, $previousLine, $excludePlayerIds))
            ->collapse()
            ->values();
    }

    /**
     * @return Collection<int, Player>
     */
    public function benchPlayers(GameLine $line): Collection
    {
        return $line->game->availablePlayersQuery()
            ->whereNotIn('id', $line->participantIds())
            ->get();
    }

    /**
     * @return array<string, Collection<int, Player>>
     */
    public function substitutionCandidateBuckets(GameLineAssignment $assignment): array
    {
        $line = $assignment->gameLine()->with('game', 'assignments.substitutions')->firstOrFail();
        $benchPlayers = $this->benchPlayers($line);

        $preferredPlayers = $benchPlayers
            ->filter(fn (Player $player): bool => $player->preferred_position === $assignment->position)
            ->values();

        $otherPlayers = $benchPlayers
            ->reject(fn (Player $player): bool => $player->preferred_position === $assignment->position)
            ->values();

        return array_filter([
            sprintf('Bench players who prefer %s', $assignment->position->label()) => $preferredPlayers,
            'Other bench players' => $otherPlayers,
        ], fn (Collection $players): bool => $players->isNotEmpty());
    }

    /**
     * @return array<int, array{position: PositionGroup, slot_number: int, player_id: int|null}>
     */
    public function autoAssignments(Game $game, ?GameLine $previousLine = null): array
    {
        $selectedPlayerIds = [];

        return $this->positionSlots($game)
            ->map(function (array $slot) use ($game, $previousLine, &$selectedPlayerIds): array {
                $player = $this->orderedCandidates(
                    $game,
                    $slot['position'],
                    $previousLine,
                    $selectedPlayerIds,
                )->first();

                if ($player !== null) {
                    $selectedPlayerIds[] = $player->id;
                }

                return [
                    'position' => $slot['position'],
                    'slot_number' => $slot['slot_number'],
                    'player_id' => $player?->id,
                ];
            })
            ->all();
    }

    public function rebalanceAssignmentsForAvailability(Game $game, int $startAfterLineNumber = 0): void
    {
        $unavailablePlayerIds = $game->unavailablePlayerIds();

        if ($unavailablePlayerIds === []) {
            return;
        }

        $lines = $game->lines()
            ->where('line_number', '>', $startAfterLineNumber)
            ->with('assignments')
            ->get();

        foreach ($lines as $line) {
            $reservedPlayerIds = $line->assignments
                ->pluck('player_id')
                ->filter(fn (?int $playerId): bool => $playerId !== null && ! in_array($playerId, $unavailablePlayerIds, true))
                ->values()
                ->all();

            foreach ($line->assignments as $assignment) {
                if ($assignment->player_id !== null && ! in_array($assignment->player_id, $unavailablePlayerIds, true)) {
                    continue;
                }

                $replacement = $this->orderedCandidates(
                    $game,
                    $assignment->position,
                    $line->previousLine(),
                    $reservedPlayerIds,
                )->first();

                $assignment->update([
                    'player_id' => $replacement?->id,
                ]);

                if ($replacement !== null) {
                    $reservedPlayerIds[] = $replacement->id;
                }
            }
        }
    }
}
