<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 03/06/19
 * Time: 15:29
 */

namespace App\Repositories;


use App\FictitiousMessage;
use App\Message;
use App\Room;
use App\Team;
use App\Jobs\GenerateAlert;
use Illuminate\Support\Carbon;

class MessageRepository
{

    public static function create(Team $team, Room $room, FictitiousMessage $content)
    {
        $msg = new Message();
        $msg->date = now('Europe/Paris');
        $msg->team_id = $team->id;
        $msg->room_id = $room->id;
        $msg->message_id = $content->id;

        $msg->saveOrFail();
    }

    public static function generateAlert(Team $team, FictitiousMessage $content)
    {
        dispatch(new GenerateAlert($team, $content))->delay(Carbon::now()->addMinutes($content->time));
    }

    public static function getMessages(Room $room)
    {
        return $room->messages()->get();
    }
}
