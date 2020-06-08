<?php

namespace App\Http\Controllers;

use App\Riddle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RiddleController extends Controller
{
    public function listRiddles(Request $request)
    {
        $this->authorize('listRiddles', Riddle::class);

        $user = Auth::user();

        $riddles = [];
		$longueur_parcours=0;
		$nb_completes=0;

        foreach ($user->parcours as $parcrous) {
			
            $riddle = $parcrous->riddle;
			if (!$riddle->disabled
                && is_riddle_in_parcours($riddle, $user)){
			$longueur_parcours=$longueur_parcours+1;
			}
			if (!$riddle->disabled
                && is_riddle_in_parcours($riddle, $user)&& is_riddle_completed($riddle, $user)) {
			$nb_completes=$nb_completes+1;
			}
            if (!$riddle->disabled
                && all($riddle->parents,
                        function ($r) use ($user) {
                            return $r->disabled || !is_riddle_in_parcours($r, $user) || is_riddle_completed($r, $user);
                        })
                && has_incomplete_sisters($riddle,$user)
            ){
                $riddles[] = riddle_info($riddle, $user);
            }
        }

        return JsonResponse::create([
            'status' => [
                'type' => 'success',
                'message' => 'Énigmes récupérées avec succès',
                'display' => false
            ],
            'riddles' => $riddles,
            'time' => [
                'start_date' => $user->start_date,
                'end_date' => $user->end_date
            ],
			'progression' => $nb_completes/$longueur_parcours
        ]);
    }


    public function startRiddle($id, Request $request)
    {
        $riddle = Riddle::find($id);

        $this->authorize('startRiddle', $riddle);

        // TODO Use the starting code

        start_riddle($riddle, Auth::user());

        return JsonResponse::create([
            'status' => [
                'type' => 'success',
                'message' => 'Énigme commencée avec succès',
                'display' => false
            ]
        ]);
    }


    public function cancelRiddle($id, Request $request)
    {
        $riddle = Riddle::find($id);

        $this->authorize('cancelRiddle', $riddle);

        cancel_riddle($riddle, Auth::user());

        return JsonResponse::create([
            'status' => [
                'type' => 'success',
                'message' => 'Énigme annulée avec succès',
                'display' => false
            ]
        ]);
    }
}
