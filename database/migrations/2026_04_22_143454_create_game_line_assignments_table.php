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
        Schema::create('game_line_assignments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('game_line_id')->constrained()->cascadeOnDelete();
            $table->string('position');
            $table->unsignedTinyInteger('slot_number');
            $table->foreignUlid('player_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->unique(['game_line_id', 'position', 'slot_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_line_assignments');
    }
};
