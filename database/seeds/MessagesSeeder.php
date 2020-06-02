<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        DB::table('messages')->truncate();

        DB::table('messages')->insert([
            'id' => 1,
            'content' => 'C\'est bon, vous avez retrouvé Théo ?? On est mal, plus qu\'une heure avant de poser le dossier 😱😱😱',
            'author' => 'BDE',
            'time' => '01:00:00'
        ]);

        DB::table('messages')->insert([
            'id' => 2,
            'content' => 'Vous avez reçu un Snap de Théo ! <a href="https://youtu.be/8xkPcRLftHY" target="_new">Cliquez ici</a>',
            'author' => 'Théo',
            'riddle_id' => 2
        ]);

        DB::table('messages')->insert([
            'id' => 3,
            'content' => 'Vous avez reçu un Snap de Théo ! <a href="https://youtu.be/8xkPcRLftHY" target="_new">Cliquez ici</a>',
            'author' => 'Théo',
            'riddle_id' => 5
        ]);

        DB::table('messages')->insert([
            'id' => 4,
            'content' => 'L\'administration attend toujours le dossier ! C\'est inadmissible. Je vous rappelle que vous risquez l\'annulation du WEI. Je vous laisse une demi-heure.',
            'author' => 'Bruno Sauviac',
            'time' => '01:30:00'
        ]);

        DB::table('messages')->insert([
            'id'=> 5,
            'content' => 'Vous avez reçu un nouveau message de Théo. Composez le 06 XX 78 XX 11 pour l\'écouter.',
            'author' => 'Théo',
            'riddle_id' => 9
        ]);

        DB::table('messages')->insert([
            'id' => 6,
            'content' => 'Vous avez reçu un nouveau message de Théo. Composez le 06 XX 78 XX 11 pour l\'écouter.',
            'author' => 'Théo',
            'riddle_id' => 88
        ]);

        DB::table('messages')->insert([
            'id' => 7,
            'content' => 'Vous avez reçu un nouveau message de Théo. Composez le 06 65 XX XX 11 pour l\'écouter.',
            'author' => 'Théo',
            'riddle_id' => 888
        ]);

        DB::table('messages')->insert([
            'id' => 8,
            'content' => 'Vous avez reçu un nouveau message de Théo. Composez le 06 65 XX XX 11 pour l\'écouter.',
            'author' => 'Théo',
            'riddle_id' => 100
        ]);

    }
}
