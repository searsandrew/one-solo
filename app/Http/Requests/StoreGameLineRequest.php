<?php

namespace App\Http\Requests;

use App\PositionGroup;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreGameLineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $game = $this->route('game');

        return [
            'notes' => ['nullable', 'string'],
            'assignments' => ['required', 'array', sprintf('size:%d', $game->players_on_field)],
            'assignments.*.position' => ['required', Rule::enum(PositionGroup::class)],
            'assignments.*.slot_number' => ['required', 'integer', 'between:1,3'],
            'assignments.*.player_id' => ['required', 'integer', Rule::exists('players', 'id')],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $game = $this->route('game');
                $selectedPlayerIds = collect($this->input('assignments', []))
                    ->pluck('player_id')
                    ->filter()
                    ->all();

                if (count($selectedPlayerIds) !== count(array_unique($selectedPlayerIds))) {
                    $validator->errors()->add('assignments', 'A player can only be used once in the same line.');
                }

                $expectedKeys = collect(PositionGroup::ordered())
                    ->flatMap(function (PositionGroup $position) use ($game) {
                        return collect(range(1, $game->{$position->countColumn()}))
                            ->map(fn (int $slotNumber): string => sprintf('%s:%d', $position->value, $slotNumber));
                    })
                    ->sort()
                    ->values()
                    ->all();

                $actualKeys = collect($this->input('assignments', []))
                    ->map(fn (array $assignment): string => sprintf('%s:%d', $assignment['position'], $assignment['slot_number']))
                    ->sort()
                    ->values()
                    ->all();

                if ($expectedKeys !== $actualKeys) {
                    $validator->errors()->add('assignments', 'This line no longer matches the saved formation for the game.');
                }

                $unavailablePlayerIds = $game->unavailablePlayerIds();

                if (array_intersect($selectedPlayerIds, $unavailablePlayerIds) !== []) {
                    $validator->errors()->add('assignments', 'Unavailable players cannot be assigned to a line.');
                }
            },
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'assignments' => 'line assignments',
        ];
    }
}
