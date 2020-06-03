<?php

use Illuminate\Database\Seeder;

class RoomTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms_teams')->truncate();

        $colors = [
            (object)array("name" => "Rouge", "base" => 100),
            (object)array("name" => "Jaune", "base" => 200),
            (object)array("name" => "Bleu", "base" => 300),
            (object)array("name" => "Vert", "base" => 400),
            (object)array("name" => "Violet", "base" => 500),
        ];

        $numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        foreach ($colors as $color) {

            foreach($numbers as $number) {

                DB::table('rooms_teams')->insert([
                    'id' => 10*$color->base + $number,
                    'team_id' => 2, // gamemaster team id
                    'room_id' => $color->base + $number,
                ]);

                DB::table('rooms_teams')->insert([
                    'id' => $color->base + $number,
                    'team_id' => $color->base + $number,
                    'room_id' => $color->base + $number,
                ]);
            }
        }
    }
}
