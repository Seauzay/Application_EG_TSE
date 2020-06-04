<?php

namespace App\Policies;

use App\Team;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function isPlayer(Team $team)
    {
        return !is_null($team) && $team->grade === 0;
    }

    public function isGM(Team $team)
    {
        return !is_null($team) && $team->grade === 1;
    }

    public function isAdmin(Team $team)
    {
        return !is_null($team) && $team->grade === 2;
    }
    public function isGMorAdmin(Team $team)
    {
        return ($this->isGM($team)||$this->isAdmin($team));
    }
}
