<?php

namespace App\Http\Controllers;

use App\Riddle;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use RefreshDBSeeder;
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
                'url' =>$riddle->url,
                'disabled' => $riddle->disabled,
                'post-msg' => $riddle->post_resolution_message
            ];
        })->all();

        $adm = Auth::user();
        $logout_url =['logout_url' => 'admin/logout'];

        return view('admin.home', compact('riddles'))->with(['logout_url' => 'admin/logout'])->withTitle($adm->getAttribute('name'));
    }


    public function refreshDB()
    {
        $this->authorize('isAdmin', Team::class);
        $seeder = new refreshDBSeeder();
        $seeder->run();
        return redirect('admin');
    }

    function modifyRiddle(Request $request)
    {
        $this->authorize('isAdmin', Team::class);

        $riddle = Riddle::where('id', '=', $request->input('id'))->first();

        $riddle->name = $request->input('name') ?? $riddle->name;
        $riddle->description = $request->input('description') ?? $riddle->description;
        $riddle->code = $request->input('code') ?? $riddle->code;
        $riddle->url = $request->input('url')?? $riddle->url;
        $riddle->post_resolution_message = $request->input('post-msg')?? $riddle->post_resolution_message;
        $riddle->disabled = $request->input('disabled') ? true : false;

        $riddle->saveOrFail();
        return redirect('admin');
    }

    function addGM(Request $request){
        $this->authorize('isAdmin', Team::class);

        $alreadyInDbGM = Team::where('grade','=',1)
            ->where('name','=',$request->input('name'))->get()->first();
        if(is_null($alreadyInDbGM)){
            DB::table('teams')->insert([
                'id' => DB::table('teams')->where('id','<',101)->max('id')+1,
                'name' => $request->input('name'),
                'password' => bcrypt($request->input('password')),
                'grade' => 1,
            ]);
        }else{
            $alreadyInDbGM->password = bcrypt($request->input('password'));
            $alreadyInDbGM->saveOrFail();
        }
        return redirect('admin');
    }

    function logout()
    {
        Auth::logout();
        return redirect('admin/login');
    }

}
