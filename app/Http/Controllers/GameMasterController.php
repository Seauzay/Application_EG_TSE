<?php

namespace App\Http\Controllers;

use App\Events\StartChrono;
use App\Team;
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

        $this->validate($request, [
            'teams' => 'required',
        ]);

        $date = now('Europe/Paris');

        if(all($request->teams, function($teamId){
            $team = App\Teams::find($teamId);
            return is_null($team->start_date);
        }))
        {
            foreach ($request->teams as $teamId){
                $team = App\Teams::find($teamId);
                $team->start_date = $date;
                $team->saveOrFail();
                $alerts = FictitiousMessage::whereNotNull('time')->get();
                foreach ($alerts as $alert) {
                    MessageRepository::generateAlert($team,$alert);
                }
            }
            event(new StartChrono());
            return JsonResponse::create([
                'status' => [
                    'type' => 'success',
                    'message' => 'Liste des équipes récupérée avec succès!',
                    'display' => true
                ],
                'teams' => App\Teams::whereNull('updated_at')->whereNotIn('id', [1, 2])->pluck('id', 'name')->get()
            ]);
        }
        else{
            return JsonResponse::create([
                'status' => [
                    'type' => 'error',
                    'message' => 'Une des équipes a déjà commencé!',
                    'display' => true
                ],
                'teams' => App\Teams::whereNull('updated_at')->whereNotIn('id', [1, 2])->pluck('id', 'name')->get()
            ]);
        }


    }

    function listPlayersNotYetStarted(Request $request){

        $this->authorize('isGM', Team::class);

        $teams = App\Teams::whereNull('updated_at')->whereNotIn('id', [1, 2])->pluck('id', 'name')->get();

        JsonResponse::create([
            'status' => [
            'type' => 'success',
            'message' => 'Liste des équipes récupérée avec succès!',
            'display' => false
        ],
            'teams' => $teams
        ]);
    }

}
