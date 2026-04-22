<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_line_substitutions', function (Blueprint $table) {
            $table->ulid();
            $table->foreignUlid('game_line_assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('outgoing_player_id')->constrained('players')->restrictOnDelete();
            $table->foreignUlid('incoming_player_id')->constrained('players')->restrictOnDelete();
            $table->string('reason')->default('substitution');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_line_substitutions');
    }
};
