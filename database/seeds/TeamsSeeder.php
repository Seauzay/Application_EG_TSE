<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    /**
     *  !!!!!!!!!!!!!!!!!! CLEAR TABLE BEFORE SEEDING !!!!!!!!!!!!!!!!!!!!!!!
     *
     * @return void
     */
    public function run()
    {
        DB::table('teams')->truncate();

        DB::table('teams')->insert([
            'id' => 1,
            'name' => 'admin',
            'password' => bcrypt('default'),
            'grade' => 2,
            'remember_token' => "",

        ]);
        DB::table('teams')->insert([
            'id' => 2,
            'name' => 'gamemaster',
            'password' => bcrypt('1234'),
            'grade' => 1,
            'remember_token' => "",

        ]);

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

                DB::table('teams')->insert([
                    'id' => $color->base + $number,
                    'name' => $color->name.' '.$number,
                    'grade' => 0,
                    'remember_token' => "",

                ]);
            }
        }
    }
}
