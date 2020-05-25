<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParcoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->unsigned()->index('fk_parcours_teams1_idx');
            $table->integer('riddle_id')->unsigned()->index('fk_parcours_riddles1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parcours');
    }
}
