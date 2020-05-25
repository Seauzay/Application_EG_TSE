<?php

namespace App\Http\Controllers;

use App\Riddle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidationMdpController extends Controller
{
    function checkMdp($id, Request $request)
    {
        $riddledb = Riddle::find($id);
        $this->authorize('validateRiddle', $riddledb);

        if ($riddledb->code == $request->input('code')) {
            end_riddle($riddledb, Auth::user());
            return JsonResponse::create([
                'status' => [
                    'type' => 'success',
                    'message' => 'Énigme Validée',
                    'display' => true
                ]
            ]);
        }

        return JsonResponse::create([
            'status' => [
                'type' => 'error',
                'message' => 'Code invalide',
                'display' => true
            ]
        ]);
    }

}