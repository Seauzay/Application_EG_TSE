<?php

namespace App\Http\Controllers;

use App\Room;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;


class TeamController extends Controller
{
    public function login()
    {
        return view('player.login', ['logout_url' => 'player/logout']);
    }

    function checklogin(Request $request)
    {
        $this->validate($request, [
            'color' => 'required',
            'num' => 'required|numeric'
        ]);

        $color = 0;
        switch ($request->input('color')) {
            case 1:
                $color = 'Rouge';
                break;
            case 2:
                $color = 'Vert';
                break;
            case 3:
                $color = 'Bleu';
                break;
            case 4:
                $color = 'Jaune';
                break;
            case 5:
                $color = 'Violet';
                break;
            default:
                throw new UnauthorizedException();
        }

        $num = $request->input('num');

        $name = $color . ' ' . $num;

        $user = Team::where('name', '=', $name)->first();

        if (is_null($user)) {
            $user = new Team();
            $user->name = $name;
            $user->grade = 0;
            $user->saveOrFail();

            $room = new Room();
            $room->name = 'Conversation ' . $name;
            $user->rooms()->save($room);
            $room_id = $room->id;

            $gms = Team::where('grade', '=', 1)->get();
            foreach ($gms as $gm){
                DB::table('rooms_teams')->insert([
                    'team_id' => $gm->id,
                    'room_id' => $room_id
                ]);
            }
        }
        Auth::login($user);
        return redirect('/');
    }

    function home()
    {
        if (Auth::check()) {
            $user = Auth::user();
            switch ($user->grade){
                case 0:
                    return view('player.home', ['logout_url' => 'player/logout']);
                    break;
                case 1:
                    return view('gm.home', ['logout_url' => 'gm/logout']);
                    break;
                case 2:
                    return redirect('/admin');
                    break;
                default:
                    throw new UnauthorizedException();
            }
        } else
            return redirect('player/play');
    }

    function firstMessage(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            switch ($user->grade){
                case 0:
                    return view('player.home', ['logout_url' => 'player/logout']);
                    break;
                case 1:
                    return view('gm.home', ['logout_url' => 'gm/logout']);
                    break;
                case 2:
                    return redirect('/admin');
                    break;
                default:
                    throw new UnauthorizedException();
            }
        } else
           // return redirect('player/play');
        return view('player.message',['logout_url' => 'player/logout']);
    }
    function logout()
    {
        $remember_token=false;
        Auth::logout();

        return redirect('/');
    }
}
