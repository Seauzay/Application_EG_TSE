<?php

namespace App\Http\Controllers;

use App\Events\StartChrono;
use App\Repositories\MessageRepository;
use App\Team;
use App\FictitiousMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return back()->with('error', 'Wrong Login Details');
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

    function startChrono(Request $request){

        $this->authorize('isGM', Team::class);

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
}
