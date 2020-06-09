<?php

namespace App\Http\Controllers;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

	function exportResult()
	{
		//$this->authorize('isGMorAdmin', Team::class);
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

}
