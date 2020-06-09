<?php

namespace App\Http\Controllers;

use App\Parcours;
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

    public function modParcours(Request $request)
    {
        $this->authorize('isGMorAdmin', Team::class);
        $parcoursToModify = $request->parcours;
        DB::table('parcours')->delete();
        try {
            foreach (Team::all() as $team) {
                foreach ($parcoursToModify as $parc) {
                    $v = $parc['team_color'];
                    if (strpos($team->name, $parc['team_color']) !== false) {
                        foreach ($parc['riddles_id'] as $riddle_id) {
                            $parcours = new Parcours();
                            $parcours->team_id = $team->id;
                            $parcours->riddle_id = $riddle_id;
                            $parcours->saveOrFail();
                        }
                    }
                }
            }
        }catch (Exception $e){
            return JsonResponse::create([
                'status' => [
                    'type' => 'error',
                    'message' => $e->getMessage(),
                    'display' => false
                ]
            ]);
        }

        return JsonResponse::create([
            'status' => [
                'type' => 'success',
                'message' => "Modification done",
                'display' => false
            ]
        ]);
    }


    public function getTeamsParcours(Request $request)
    {
        $this->authorize('isGMorAdmin', Team::class);
        $riddles = Riddle::all();
        $parcours = Parcours::all();

        $color_available = array(0 => 'Rouge', 1 => 'Vert', 2 => 'Bleu', 3 => 'Jaune', 4 => 'Violet');
        $parcoursToSend = [];
        foreach (Team::all() as $team) {
            $idx = -1;
            foreach ($color_available as $clr ){
                if (strpos($team->name, $clr) !== false) {
                    //there is at least one
                    $color_available = array_diff($color_available, array($clr));
                    $riddleTeam = $parcours->where('team_id', $team->id)->all();
                    $riddlesParcours =[];
                    foreach ($riddleTeam as $riddleId) {
                        array_push($riddlesParcours, $riddleId->riddle_id);
                    }
                    $tmp = [
                        'team_color' => $clr,
                        'riddles_id' => $riddlesParcours
                    ];
                    array_push($parcoursToSend, $tmp);
                }
            }
        }
        return JsonResponse::create([
            'status' => [
                'type' => 'success',
                'message' => 'Énigmes récupérées avec succès',
                'display' => false
            ],
            'parcours' => $parcoursToSend,
        ]);
    }
}
