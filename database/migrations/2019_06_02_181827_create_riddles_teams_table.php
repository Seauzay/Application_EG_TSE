<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRiddlesTeamsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riddles_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->unsigned()->index('fk_teams_has_riddles_teams1_idx');
            $table->integer('riddle_id')->unsigned()->index('fk_teams_has_riddles_riddles1_idx');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('riddles_teams');
    }

}
