<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class RiddleTeamSeeder extends Seeder
{
    /**
     *  !!!!!!!!!!!!!!!!!! CLEAR TABLE BEFORE SEEDING !!!!!!!!!!!!!!!!!!!!!!!
     *
     * @return void
     */
    public function run()
    {
        DB::table('riddles_teams')->truncate();
    }
}
