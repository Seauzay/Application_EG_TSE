<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call([
            TeamsSeeder::class,
            RiddlesSeeder::class,
            MessagesSeeder::class,
            MessagingSeeder::class,
            RoomsSeeder::class,
            RoomTeamSeeder::class,
            //RiddlesRiddlesSeeder::class,
            RiddleTeamSeeder::class,
            ParcoursSeeder::class
        ]);
        /*Eloquent::unguard();
        $this->call(UsersTablesSeeder::class);*/

    }
}
