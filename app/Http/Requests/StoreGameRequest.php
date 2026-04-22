<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreGameRequest extends FormRequest
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
            'opponent' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'scheduled_at' => ['required', Rule::date()],
            'players_on_field' => ['required', 'integer', 'between:4,10'],
            'goalkeepers_count' => ['required', 'integer', 'in:1'],
            'defenders_count' => ['required', 'integer', 'between:1,3'],
            'midfielders_count' => ['required', 'integer', 'between:1,3'],
            'forwards_count' => ['required', 'integer', 'between:1,3'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $totalPlayers = $this->integer('goalkeepers_count')
                    + $this->integer('defenders_count')
                    + $this->integer('midfielders_count')
                    + $this->integer('forwards_count');

                if ($totalPlayers !== $this->integer('players_on_field')) {
                    $validator->errors()->add(
                        'players_on_field',
                        'Goalie, defenders, midfielders, and forwards must add up to the players on the field.',
                    );
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
            'players_on_field' => 'players on the field',
            'goalkeepers_count' => 'goalies',
            'defenders_count' => 'defenders',
            'midfielders_count' => 'midfielders',
            'forwards_count' => 'forwards',
        ];
    }
}
