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
    public function run($refreshRiddles,$refreshGM)
    {
        DB::table('jobs')->truncate();
        if($refreshRiddles){
            $this->call([RiddlesSeeder::class,
                ParcoursSeeder::class]);
        }
        if($refreshGM){
            $this->call([TeamsSeeder::class]);
        }
        else{
            DB::table('teams')->where('grade','=',0)->update(['start_date' => null]);
            DB::table('teams')->where('grade','=',0)->update(['end_date' => null]);
            DB::table('teams')->where('grade','=',0)->update(['score' => 0]);
        }
        $this->call([
            RiddleTeamSeeder::class,
            MessagesSeeder::class,
            MessagingSeeder::class,
            RoomsSeeder::class,
            RoomTeamSeeder::class,
        ]);
    }
}
