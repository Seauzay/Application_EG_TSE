<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimeController extends Controller
{
    public function now(Request $request)
    {
        return JsonResponse::create([
            'status' => [
                'type' => 'success',
                'message' => 'Time is returned in Europe/Paris tz.',
                'display' => false
            ],
            'now' => now('Europe/Paris')
            ]);
    }
}
