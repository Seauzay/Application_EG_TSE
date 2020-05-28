<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 03/06/19
 * Time: 15:29
 */

namespace App\Repositories;


use App\FictuousMessage;
use App\Message;
use App\Room;
use App\Team;

class MessageRepository
{

    public static function create(Team $team, Room $room, FictuousMessage $content)
    {
        $msg = new Message();
        $msg->date = now('Europe/Paris');
        $msg->team_id = $team->id;
        $msg->room_id = $room->id;
        $msg->message_id = $content->id;

        $msg->saveOrFail();
    }

    public static function getMessages(Room $room)
    {
        return $room->messages()->get();
    }
}
