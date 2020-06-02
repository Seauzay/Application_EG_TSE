<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewMessagesTable extends Migration
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
                $table->text('content');
                $table->string('author');
                $table->integer('riddle_id')->unsigned()->nullable()->index('fk_messages_riddles1_idx');
                $table->integer('time')->nullable();
            });
        Schema::table('messaging', function (Blueprint $table) {
            $table->index('message_id','fk_messaging_messages1_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messaging', function (Blueprint $table) {
            $table->dropIndex('fk_messaging_messages1_idx');
        });
        Schema::dropIfExists('messages');
    }
}
