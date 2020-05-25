<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class RiddleTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('riddles_teams')->truncate();
        $faker = Faker::create();
    }
}
