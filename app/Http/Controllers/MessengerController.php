<?php

namespace App\Http\Controllers;

use App\Repositories\MessageRepository;
use App\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessengerController extends Controller
{

    public function sendMessage($room_id, Request $request)
    {
        $room = Room::findOrFail($room_id);

        $this->authorize('sendmessage', $room);

        MessageRepository::create($request->user(), $room, $request->input('content'));

        return JsonResponse::create([
            'status' => [
                'type' => 'success',
                'message' => 'Message envoyé avec succès',
                'display' => false
            ]
        ]);
    }

    public function GetMessages($room_id, Request $request)
    {
        $room = Room::findOrFail($room_id);

        $this->authorize('getmessages', $room);

        $user_id = Auth::user()->id;

        $messages = MessageRepository::getMessages($room)->map(function ($msg) use ($user_id) {
            return [
                'date' => $msg->date,
                'content' => $msg->fictitiousMessage->content,
                'author' => $msg->fictitiousMessage->author,
                'self' => $msg->team_id == $user_id
            ];
        });

        return JsonResponse::create([
            'status' => [
                'type' => 'success',
                'message' => 'Message récupéré avec succès',
                'display' => false
            ],
            'messages' => $messages
        ]);
    }

    public function listRooms()
    {
        // TODO Tests et faire mieux

        return JsonResponse::create([
            'status' => [
                'type' => 'success',
                'message' => 'Liste des salons récupérée',
                'display' => false
            ],
            'rooms' => Auth::user()->rooms
        ]);
    }
}
