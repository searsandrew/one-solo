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
        Schema::create('teams', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('team_name')->default('Soccer Squad');
            $table->unsignedTinyInteger('players_on_field')->default(9);
            $table->unsignedTinyInteger('goalkeepers_count')->default(1);
            $table->unsignedTinyInteger('defenders_count')->default(3);
            $table->unsignedTinyInteger('midfielders_count')->default(3);
            $table->unsignedTinyInteger('forwards_count')->default(2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
