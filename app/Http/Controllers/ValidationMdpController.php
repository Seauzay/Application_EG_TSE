<?php

namespace App\Http\Controllers;

use App\Events\ChangeEvent;
use App\Events\SuccessEvent;
use App\Repositories\MessageRepository;
use App\Riddle;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidationMdpController extends Controller
{
    function checkMdp($id, Request $request)
    {
        $riddledb = Riddle::find($id);
        $this->authorize('validateRiddle', $riddledb);

        $user = Auth::user();
        if ($riddledb->code == $request->input('code')) {
            $finParcours = end_riddle($riddledb, Auth::user());
			$format = 'Y-m-d H:i:s';
            $dateFin = DateTime::createFromFormat($format, DB::table('riddles_teams')->where([
                ['team_id', '=', $user->id],
                ['riddle_id', '=', $id],
            ])->value('end_date'));
            $dateDebut = DateTime::createFromFormat($format, DB::table('riddles_teams')->where([
                ['team_id', '=', $user->id],
                ['riddle_id', '=', $id],
            ])->value('start_date'));
            $duration=$dateFin->getTimeStamp()-$dateDebut->getTimestamp();
            if ($duration<=480){
                $score=20;
            } elseif ($duration<=600) {
                $score=10;
            }
            else{
                $score=-10;
            }
            $user->score=$user->score+$score;
            $user->save();
            $this->submitMessage(Auth::user()->rooms->first(),$riddledb,Auth::user());
            event(new SuccessEvent($user));
            event(new ChangeEvent());

            return JsonResponse::create([
                'status' => [
                    'type' => 'success',
                    'message' => 'Énigme Validée',
                    'display' => true
                ],
                'score'  => $user->score,
              'fin' => $finParcours
            ]);
			
			
			
        }

        return JsonResponse::create([
            'status' => [
                'type' => 'error',
                'message' => 'Code invalide',
                'display' => true,
				'fin'=> false,
            ]
        ]);
    }

    private function submitMessage($room, $riddle, $team)
    {
        $fictitiousMessage = $riddle->postResolutionMessage;

        if(!is_null($fictitiousMessage)) {
            MessageRepository::create($team, $room, $fictitiousMessage);
        }

    }

}
