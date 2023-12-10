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
        Schema::create('matches', function (Blueprint $table) {
            $table->id('match_id');
            $table->dateTime('started_at');
            $table->unsignedBigInteger('home_team_id')->unsigned();
            $table->foreign('home_team_id')->references('team_id')->on('teams');
            $table->unsignedBigInteger('away_team_id')->unsigned();
            $table->foreign('away_team_id')->references('team_id')->on('teams');
            $table->integer('home_team_score')->nullable();
            $table->integer('away_team_score')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=Pending, 1=Ongoing, 2=Finished');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
