<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRiddleTableAddPostResolutionMessageColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('riddles', function (Blueprint $table) {
            $table->string('post_resolution_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('riddles', function (Blueprint $table) {
            $table->dropColumn('post_resolution_message');
        });
    }
}
