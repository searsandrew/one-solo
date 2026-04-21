<?php

namespace App\Models;

use App\PositionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory, HasUlids;

    protected $fillable = [
        'name', 'jersey_number', 'preferred_position', 'active', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'preferred_position' => PositionGroup::class,
            'active' => 'bool',
        ];
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }
}
