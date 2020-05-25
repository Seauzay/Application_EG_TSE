<?php

namespace App\Policies;

use App\Room;
use App\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessengerPolicy
{
    use HandlesAuthorization;


    public function roomaccess(Team $team, Room $room)
    {
        return $room->teams()->where('teams.id', $team->id)->exists();
    }

    public function sendmessage(Team $team, Room $room)
    {
        return $this->roomaccess($team, $room);
    }

    public function getmessages(Team $team, Room $room)
    {
        return $this->roomaccess($team, $room);
    }
}
