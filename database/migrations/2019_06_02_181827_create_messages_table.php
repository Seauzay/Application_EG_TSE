<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content', 65535);
            $table->dateTime('date');
            $table->integer('room_id')->unsigned()->index('fk_messages_rooms1_idx');
            $table->integer('team_id')->unsigned()->index('fk_messages_teams1_idx');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }

}
