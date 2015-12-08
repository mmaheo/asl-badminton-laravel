<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlayerSeasonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_season', function (Blueprint $table)
        {
            $table->increments('id')->index();

            $table->integer('player_id')->unsigned();
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade');

            $table->integer('season_id')->unsigned();
            $table->foreign('season_id')->references('id')->on('seasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('player_season');
    }
}