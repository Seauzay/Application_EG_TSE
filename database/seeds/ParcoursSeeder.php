<?php

use Illuminate\Database\Seeder;

class ParcoursSeeder extends Seeder
{
    /**
     *  !!!!!!!!!!!!!!!!!! CLEAR TABLE BEFORE SEEDING !!!!!!!!!!!!!!!!!!!!!!!
     *
     * @return void
     */
    public function run()
    {
        DB::table('parcours')->truncate();

        $colorsBleuVert = [
            (object)array("name" => "Bleu", "base" => 300),
            (object)array("name" => "Vert", "base" => 400),
        ];

        $colorsJauneRouge = [
            (object)array("name" => "Rouge", "base" => 100),
            (object)array("name" => "Jaune", "base" => 200),

        ];

        $colorsViolet= [
            (object)array("name" => "Violet", "base" => 500),
        ];

        $numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        // parcours bleu vert
        foreach ($colorsBleuVert as $color) {

            foreach($numbers as $number) {

                // amphitheatre
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 1
                ]);

                // bat A
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 4
                ]);

                // admin
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 5
                ]);

                // cours des matieres
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 7
                ]);

                // specifique bleu
                if ($color->name == 'Bleu') {

                    // mixeur
                    DB::table('parcours')->insert([
                        'team_id' => $color->base + $number,
                        'riddle_id' => 9
                    ]);

                    // tour
                    DB::table('parcours')->insert([
                        'team_id' => $color->base + $number,
                        'riddle_id' => 8
                    ]);
                }

                // specifique vert
                if ($color->name == 'Vert') {

                    // mixeur
                    DB::table('parcours')->insert([
                        'team_id' => $color->base + $number,
                        'riddle_id' => 99
                    ]);

                    // tour
                    DB::table('parcours')->insert([
                        'team_id' => $color->base + $number,
                        'riddle_id' => 88
                    ]);
                }

                // numero
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 11
                ]);

                // D003
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 12
                ]);
            }
        }



        // parcours jaune rouge
        foreach ($colorsJauneRouge as $color) {

            foreach($numbers as $number) {

                // amphitheatre
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 1
                ]);

                // assoc
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 3
                ]);

                // newsplex
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 2
                ]);

                // cours des matieres
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 7
                ]);

                // specifique jaune
                if ($color->name == 'Jaune') {

                    // fablab
                    DB::table('parcours')->insert([
                        'team_id' => $color->base + $number,
                        'riddle_id' => 10
                    ]);

                    // tour
                    DB::table('parcours')->insert([
                        'team_id' => $color->base + $number,
                        'riddle_id' => 888
                    ]);
                }

                // specifique rouge
                if ($color->name == 'Rouge') {

                    // fablab
                    DB::table('parcours')->insert([
                        'team_id' => $color->base + $number,
                        'riddle_id' => 100
                    ]);

                    // tour
                    DB::table('parcours')->insert([
                        'team_id' => $color->base + $number,
                        'riddle_id' => 8888
                    ]);
                }

                // numero
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 11
                ]);

                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 12
                ]);
            }
        }


        // parcours violet
        foreach ($colorsViolet as $color) {

            foreach($numbers as $number) {

                // amphitheatre
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 1
                ]);

                // newsplex
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 2
                ]);

                // admin
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 5
                ]);

                // cours des matieres
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 7
                ]);

                // mixeur
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 9
                ]);

                // fab lab
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 10
                ]);

                // numero
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 11
                ]);

                // D003
                DB::table('parcours')->insert([
                    'team_id' => $color->base + $number,
                    'riddle_id' => 12
                ]);
            }
        }
    }
}
