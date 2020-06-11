<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTableRiddlesRiddles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('riddles_riddles');//
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('riddles_riddles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->index('fk_riddles_has_riddles_riddles_idx');
            $table->integer('child_id')->unsigned()->index('fk_riddles_has_riddles_riddles1_idx');
        });//
    }
}
