<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoomsTeamsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->unsigned()->index('fk_teams_has_rooms_teams1_idx');
            $table->integer('room_id')->unsigned()->index('fk_teams_has_rooms_rooms1_idx');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rooms_teams');
    }

}
