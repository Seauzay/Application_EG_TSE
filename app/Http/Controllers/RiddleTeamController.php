<?php

namespace App\Http\Controllers;

use App\Riddle;
use App\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiddleTeamController extends Controller
{
    public function listRiddlesTeams(Request $request)
    {
        $this->authorize('isGMorAdmin', Team::class);
        $output = [];
        foreach (Team::all() as $team) {
            if ($team->grade > 1) continue;
            $riddles = [];
            foreach ($team->riddles->all() as $riddle) {
                array_push($riddles, riddle_info_for_gm($riddle, $team));
            }
            if (!empty($riddles)) {
                array_push($output, [
                    'team' => [
                        'id' => $team->getAttribute('id'),
                        'name' => $team->getAttribute('name'),
                        'start_date' => $team->getAttribute('start_date'),
                        'end_date' => $team->getAttribute('end_date')
                    ],
                    'riddles' => $riddles
                ]);
            }

        }

        return JsonResponse::create([
            'status' => [
                'type' => 'success',
                'message' => 'Énigmes de chaque équipe',
                'display' => false
            ],
            'data' => $output,
            'riddle_names' => DB::table('riddles')->pluck('name'),
            'riddle_number' => Riddle::where('disabled', 0)->count()
        ]);
    }

    public function listAllRiddles(Request $request)
    {
        $this->authorize('isGMorAdmin', Team::class);
        $riddles = Riddle::all();

        return JsonResponse::create([
            'status' => [
                'type' => 'success',
                'message' => 'Énigmes récupérées avec succès',
                'display' => false
            ],
            'riddles' => $riddles,
        ]);
    }

    public function modParcours(Request $request){
       /* $this->authorize('isGMorAdmin', Team::class);
        foreach ($request->parcours as $parcour){

        }*/
    }
}
