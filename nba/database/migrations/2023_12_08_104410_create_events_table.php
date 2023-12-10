<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id("event_id");
            $table->string('action', 255);
            $table->string('description', 255);
            $table->unsignedBigInteger('match_id')->unsigned()->nullable();
            $table->foreign('match_id')->references('match_id')->on('matches');
            $table->unsignedBigInteger('team_id')->unsigned()->nullable();
            $table->foreign('team_id')->references('team_id')->on('teams');
            $table->unsignedBigInteger('player_id')->unsigned()->nullable();
            $table->foreign('player_id')->references('player_id')->on('players');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
