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
        Schema::create('games', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('opponent');
            $table->string('location')->nullable();
            $table->dateTime('scheduled_at');
            $table->unsignedTinyInteger('players_on_field');
            $table->unsignedTinyInteger('goalkeepers_count');
            $table->unsignedTinyInteger('defenders_count');
            $table->unsignedTinyInteger('midfielders_count');
            $table->unsignedTinyInteger('forwards_count');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
