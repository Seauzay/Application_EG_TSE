<?php

namespace App\Http\Controllers;

use App\Riddle;
use App\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;
use Mockery\Exception;
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
                'post-msg' => $riddle->post_resolution_message,
                'level' => $riddle->line
            ];
        })->all();

        $adm = Auth::user();
        $logout_url =['logout_url' => 'admin/logout'];

        return view('admin.home', compact('riddles'))->with(['logout_url' => 'admin/logout'])->withTitle($adm->getAttribute('name'));
    }


    public function refreshDB(Request $request)
    {
        try{
            $this->authorize('isAdmin', Team::class);
            $seeder = new refreshDBSeeder();
            $refreshRiddles = false;
            $refreshGM = false;
            if($request->input('Riddles') == "on")
                $refreshRiddles = true;
            if($request->input('GMs') == "on")
                $refreshGM = false;
            $seeder->run($refreshRiddles,$refreshGM);
            DB::commit();
            return JsonResponse::create(['status' => [
                'type' => 'success',
                'message' => 'La base de donnée a été réinitialisée avec succès !',
                'display' => true
            ]]);
        }catch(Exception $e){
            DB::rollback();
            return JsonResponse::create(['status' => [
                'type' => 'error',
                'message' => 'Une erreur a été produite !',
                'display' => true
            ]]);
        }
    }

    function modifyRiddle(Request $requestJSON)
    {

        try{
            $this->authorize('isAdmin', Team::class);
            $request = $requestJSON->riddle;
            $riddle = Riddle::where('id', '=', $request['id'])->first();
            $creation = false;

            if( is_null($riddle)){
                $riddle = new Riddle;
                $creation = true;
                if(!is_null($request['name']) && !is_null($request['code'])){
                    $riddle->name = $request['name'];
                    $riddle->description = $request['description'] ?? null;
                    $riddle->code = $request['code'];
                    $riddle->url = $request['url'] ?? null;
                    $riddle->post_resolution_message = $request['post-resolution-message'] ?? null;
                    $riddle->disabled = ($request['disabled'] == "true")? true : false;
                    $riddle->id = $request['id'];
                    $riddle->line = $request['line'];
                }else{
                    return JsonResponse::create(['status' => [
                        'type' => 'error',
                        'message' => 'Veuillez rentrer un nom et un code de validation valides pour votre nouvelle énigme !',
                        'display' => true
                    ]]);
                }
            }else{
                $riddle->name = $request['name'] ?? $riddle->name;
                $riddle->description = $request['description'] ?? $riddle->description;
                $riddle->code = $request['code'] ?? $riddle->code;
                $riddle->url = $request['url']?? $riddle->url;
                $riddle->post_resolution_message = $request['post_resolution_message']?? $riddle->post_resolution_message;
                $riddle->disabled = ($request['disabled'] == "true")? true : false;
                $riddle->line = $request['line'];
            }

            if ($riddle->save()) {
                DB::commit();
                if($creation){
                    return JsonResponse::create(['status' => [
                        'type' => 'success',
                        'message' => 'La nouvelle énigme a été créée avec succès !',
                        'display' => true
                    ]]);
                }else{
                    return JsonResponse::create(['status' => [
                        'type' => 'success',
                        'message' => 'Énigme(s) modifiée(s) avec succès !',
                        'display' => true
                    ]]);
                }
            } else {
                DB::rollBack();
                return JsonResponse::create(['status' => [
                    'type' => 'error',
                    'message' => 'Une erreur a été produite lors de la sauvegarde !',
                    'display' => true
                ]]);
            }
        }catch (Exception $e){
            DB::rollBack();
            return JsonResponse::create(['status' => [
                'type' => 'error',
                'message' => 'Une erreur a été produite !',
                'display' => true
            ]]);
        }
    }

    function addGM(Request $request){
        try{
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
                DB::commit();
                return JsonResponse::create(['status' => [
                    'type' => 'success',
                    'message' => 'Le compte gamemaster a été créé avec succès !',
                    'display' => true
                ]]);
            }else{
                $alreadyInDbGM->password = bcrypt($request->input('password'));
                $alreadyInDbGM->saveOrFail();
                DB::commit();
                return JsonResponse::create(['status' => [
                    'type' => 'success',
                    'message' => 'Ce gamemaster existe déjà. Son mot de passe a été modifé avec succès !',
                    'display' => true
                ]]);
            }
        }catch(Exception $e){
            DB::rollBack();
            return JsonResponse::create(['status' => [
                'type' => 'error',
                'message' => 'Une erreur a été produite !',
                'display' => true
            ]]);
        }
    }

    function logout()
    {
        Auth::logout();
        return redirect('admin/login');
    }

}
