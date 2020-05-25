<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms')->truncate();
        
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
                
                DB::table('rooms')->insert([
                    'id' => $color->base + $number,
                    'name' => 'Conversation ' .$color->name.' '.$number,
                ]);
            }
        }
    }
}
