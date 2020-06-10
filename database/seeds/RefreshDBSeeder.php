<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefreshDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jobs')->truncate();
        $this->call([
            RiddlesSeeder::class,
            MessagesSeeder::class,
            MessagingSeeder::class,
            RoomsSeeder::class,
            RoomTeamSeeder::class,
            RiddleTeamSeeder::class,
            ParcoursSeeder::class
        ]);
    }
}
