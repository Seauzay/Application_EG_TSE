<?php

namespace App\Http\Controllers;

use App\Riddle;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use const Grpc\CALL_ERROR;

class AdminController extends Controller
{
    public function home()
    {
        $this->authorize('isAdmin', Team::class);

        $riddles = Riddle::all()->map(function ($riddle) {
            return [
                'id' => $riddle->id,
                'name' => $riddle->name,
                'description' => $riddle->description,
                'code' => $riddle->code,
                'disabled' => $riddle->disabled
            ];
        })->all();

        return view('admin.home', compact('riddles'));
    }

    public function refreshDB()
    {
        $this->authorize('isAdmin', Team::class);

        DB::table('messages')->truncate();
        DB::table('riddles_teams')->truncate();
        DB::table('rooms')->truncate();
        DB::table('rooms_teams')->truncate();
        DB::table('teams')->where('id', '>', '1')->delete();
        Artisan::call('db:seed');
        return redirect('admin');
    }

    function modifyRiddle(Request $request)
    {
        $this->authorize('isAdmin', Team::class);

        $riddle = Riddle::where('id', '=', $request->input('id'))->first();

        $riddle->name = $request->input('name') ?? $riddle->name;
        $riddle->description = $request->input('description') ?? $riddle->description;
        $riddle->code = $request->input('code') ?? $riddle->code;
        $riddle->disabled = $request->input('disabled') ? true : false;
        $riddle->saveOrFail();
        return redirect('admin');
    }

    function addGM(Request $request){
        $this->authorize('isAdmin', Team::class);

        DB::table('teams')->insert([
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password')),
            'grade' => 1,
        ]);

        return redirect('admin');
    }
}
