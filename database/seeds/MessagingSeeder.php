<?php

use Illuminate\Database\Seeder;

class MessagingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('messaging')->truncate();
    }
}
