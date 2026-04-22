<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreGameLineSubstitutionRequest extends FormRequest
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
        return [
            'incoming_player_id' => ['required', 'integer', Rule::exists('players', 'id')],
            'reason' => ['nullable', 'string', 'max:255'],
            'mark_player_unavailable' => ['required', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $game = $this->route('game');
                $line = $this->route('line');
                $assignment = $this->route('assignment');
                $incomingPlayerId = $this->integer('incoming_player_id');

                if (in_array($incomingPlayerId, $game->unavailablePlayerIds(), true)) {
                    $validator->errors()->add('incoming_player_id', 'That player is marked unavailable for this game.');
                }

                if (in_array($incomingPlayerId, $line->participantIds(), true)) {
                    $validator->errors()->add('incoming_player_id', 'That player is already taking part in this line.');
                }

                if ($incomingPlayerId === $assignment->currentPlayerId()) {
                    $validator->errors()->add('incoming_player_id', 'Choose a different player for the substitution.');
                }
            },
        ];
    }
}
