<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Events\ResetChrono;
use App\Events\StartChrono;
use App\Repositories\MessageRepository;
use App\Team;
use App\FictitiousMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class GameMasterController extends Controller
{
    function login()
    {
        return view('gm.login');
    }

    function checklogin(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'password' => 'required|alphaNum|min:3'
        ]);

        $user_data = array(
            'name' => $request->get('name'),
            'password' => $request->get('password')
        );

        if (Auth::attempt($user_data)) {
            return redirect('gm');
        } else {
            return back()->with('error', 'Mot de passe ou identifiant erronés');
        }
    }

    function home()
    {
        if (Auth::check() and Auth::user()->grade === 1) {
            $gm = Auth::user();
            return view('gm.home', ['logout_url' => 'gm/logout'])->withTitle($gm->getAttribute('name'));
        } else if (Auth::check())
            return redirect('/');
        else
            return redirect('gm/login');
    }

    function logout()
    {
        Auth::logout();
        return redirect('gm/login');
    }

	function exportResult()
	{
        $this->authorize('isGM', Team::class);
        $output = [];

		foreach (Team::all() as $team) {
            if ($team->grade > 1) continue;
            $riddles = [];

			foreach ($team->riddles->all() as $riddle) {
                array_push($riddles, riddle_info_for_gm($riddle, $team));
            }
            if (!empty($riddles)) {
                array_push($output,
                     [
                        'name' => $team->getAttribute('name'),
                        'start_date' => $team->getAttribute('start_date'),
                        'end_date' => $team->getAttribute('end_date'),
						'score' => $team->getAttribute('score')
                   ]);
            }

        }


		// create a file pointer connected to the output stream
		$file = fopen('report.csv', 'w');
		fputcsv($file,['name','start_date','end_date','score']);
        foreach ($output as $row) {
            fputcsv($file, $row);
        }

        fclose($file);


	}

    function startChrono(Request $request){

        $this->authorize('isGM', Team::class);

        if ($request->input( 'action') == 'reset'){
            return $this->resetChrono($request);
        }
        else if($request->input('action') == 'trigger'){
            $ids = [];
            for($i = 1; $i<=5; $i++){
                $ids[] = $i*100 + intval($request->input('selectedVague'));
            }

            $teams = Team::whereIn('id', $ids)->get();

            if(all($teams, function($team){
                return is_null($team->start_date);
            })
            ){
                $alerts = FictitiousMessage::whereNotNull('time')->get();
                $date = now('Europe/Paris');
                foreach ($teams as $team){
                    $team->start_date = $date;
                    $team->saveOrFail();
                    foreach ($alerts as $alert) {
                        MessageRepository::generateAlert($team,$alert);
                    }
                }
                event(new StartChrono());
                return JsonResponse::create([
                    'status' => [
                        'type' => 'success',
                        'message' => 'Le timer de cette vague est lancé avec succès!',
                        'display' => true
                    ],
                ]);
            }
            else{
                return JsonResponse::create([
                    'status' => [
                        'type' => 'error',
                        'message' => 'Cette vague d\'équipes a déjà commencé!',
                        'display' => true
                    ]
                ]);
            }
        }
        else{
            throw new Exception('Not valid input in startChrono');
        }

    }

    private function resetChrono(Request $request){
        $ids = [];
        for($i = 1; $i<=5; $i++){
            $ids[] = $i*100 + intval($request->input('selectedVague'));
        }

        $teams = Team::whereIn('id', $ids)->get();

        if(any($teams,function($t){
            return $t->end_date != null;
        })){
            return JsonResponse::create([
                'status' => [
                    'type' => 'error',
                    'message' => 'Action interdite! Une ou plusieurs équipes ont déjà fini la partie! Demandez à l\'admin de vider la base de donnée.',
                    'display' => true
                ],
            ]);
        }else{
            foreach ($teams as $team) {
                $team->start_date = null;
                $team->saveOrFail();
            }

            event(new ResetChrono());

            return JsonResponse::create([
                'status' => [
                    'type' => 'success',
                    'message' => 'Le timer de cette vague a été remis à zéro avec succès!',
                    'display' => true
                ],
            ]);
        }
    }
}
