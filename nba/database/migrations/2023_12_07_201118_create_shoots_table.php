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
        Schema::create('shoots', function (Blueprint $table) {
            $table->id('shoot_id');
            $table->unsignedBigInteger('match_id')->unsigned();
            $table->foreign('match_id')->references('match_id')->on('matches');
            $table->unsignedBigInteger('team_id')->unsigned();
            $table->foreign('team_id')->references('team_id')->on('teams');
            $table->unsignedBigInteger('player_id')->unsigned();
            $table->foreign('player_id')->references('player_id')->on('players');
            $table->unsignedBigInteger('assisted_by')->unsigned()->nullable();
            $table->foreign('assisted_by')->references('player_id')->on('players');
            $table->boolean('success');
            $table->tinyInteger('point')->unsigned()->nullable();
            $table->tinyInteger('quarter')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shoots');
    }
};
