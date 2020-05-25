<?php

namespace App\Policies;

use App\Riddle;
use App\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class RiddlePolicy
{
    use HandlesAuthorization;

    public function validateRiddle(Team $team, Riddle $riddle){
        return !is_null($team) && $team->grade === 0
            && !is_null($riddle->teams->where('id', $team->id)->first())
            && !is_null($riddle->teams->where('id', $team->id)->first()->pivot->start_date)
            && is_null($riddle->teams->where('id', $team->id)->first()->pivot->end_date);
    }

    public function cancelRiddle(Team $team, Riddle $riddle)
    {
        return !is_null($team) && $team->grade === 0
            && !is_null($riddle->teams->where('id', $team->id)->first())
            && !is_null($riddle->teams->where('id', $team->id)->first()->pivot->start_date)
            && is_null($riddle->teams->where('id', $team->id)->first()->pivot->end_date);
    }

    public function startRiddle(Team $team, Riddle $riddle)
    {
        return !is_null($team) && $team->grade === 0;
    }

    public function listRiddles(Team $team)
    {
        return !is_null($team) && $team->grade === 0;
    }

}
