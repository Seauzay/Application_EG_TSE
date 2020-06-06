<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
        $table->dropColumn('content');
        $table->dropIndex('fk_messages_rooms1_idx');
        $table->dropIndex('fk_messages_teams1_idx');
        });
        Schema::rename('messages', 'messaging');
        Schema::table('messaging', function (Blueprint $table) {
            $table->integer('message_id');
            $table->boolean('read')->default(0);
            $table->index('room_id','fk_messaging_rooms1_idx');
            $table->index('team_id','fk_messaging_teams1_idx');
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
            $table->dropColumn('message_id');
            $table->dropIndex('fk_messaging_rooms1_idx');
            $table->dropIndex('fk_messaging_teams1_idx');
        });
        Schema::rename('messaging', 'messages');
        Schema::table('messages', function (Blueprint $table) {
            $table->text('content');
            $table->index('room_id','fk_messages_rooms1_idx');
            $table->index('team_id','fk_messages_teams1_idx');
        });
    }
}
