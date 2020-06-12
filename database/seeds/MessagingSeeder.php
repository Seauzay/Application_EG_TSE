<?php

use Illuminate\Database\Seeder;

class MessagingSeeder extends Seeder
{
    /**
     *  !!!!!!!!!!!!!!!!!! CLEAR TABLE BEFORE SEEDING !!!!!!!!!!!!!!!!!!!!!!!
     *
     * @return void
     */
    public function run()
    {
        DB::table('messaging')->truncate();
    }
}
