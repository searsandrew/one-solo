<?php

namespace App\Models;

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

    protected $casts = [
        'active' => 'boolean',
    ];
}
