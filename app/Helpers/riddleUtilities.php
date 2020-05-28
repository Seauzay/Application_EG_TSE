<?php


use App\Repositories\MessageRepository;
use App\Riddle;
use App\Team;
use Illuminate\Support\Carbon;
use App\Parcours;
use Illuminate\Support\Facades\Log;

if (!function_exists('is_riddle_completed')) {
    function is_riddle_completed(Riddle $riddle, Team $team)
    {
        $riddle_team = $riddle->teams->where('id', $team->id)->first();
        return !is_null($riddle_team) and !is_null($riddle_team->pivot->end_date);
    }
}

if (!function_exists('is_riddle_in_parcours')) {
    function is_riddle_in_parcours(Riddle $riddle, Team $team)
    {
        $parcours = Parcours::where('team_id', $team->id)
                                ->where('riddle_id', $riddle->id)
                                ->first();

        return !is_null($parcours);
    }
}

if (!function_exists('is_riddle_started')) {
    function is_riddle_started(Riddle $riddle, Team $team)
    {
        $riddle_team = $riddle->teams->where('id', $team->id)->first();
        return !is_null($riddle_team) and !is_null($riddle_team->pivot->start_date);
    }
}


if (!function_exists('start_riddle')) {
    function start_riddle(Riddle $riddle, Team $team)
    {
        $riddle_team = $riddle->teams->where('id', $team->id)->first();
        if (is_null($riddle_team)) {
            $riddle->teams()->attach($team, ['start_date' => now('Europe/Paris')]);
        } else if (is_null($riddle_team->pivot->start_date)) {
            $riddle->teams()->updateExistingPivot($team->id, ['start_date' => now('Europe/Paris')]);
        } else {
            throw new Exception("Riddle already started");
        }
        if (is_null($team->start_date)) {
            $team->start_date = now('Europe/Paris');
            $team->saveOrFail();
        }
    }
}

if (!function_exists('cancel_riddle')) {
    function cancel_riddle(Riddle $riddle, Team $team)
    {
        $riddle_team = $riddle->teams->where('id', $team->id)->first();
        if (is_null($riddle_team) || is_null($riddle_team->pivot->start_date))
            throw new Exception("Riddle not started");
        if (!is_null($riddle_team->pivot->end_start))
            throw new Exception("Riddle already finished");
        $riddle->teams()->updateExistingPivot($team->id, ['start_date' => null]);
    }
}

if (!function_exists('end_riddle')) {
    function end_riddle(Riddle $riddle, Team $team)
    {
        $riddle_team = $riddle->teams->where('id', $team->id)->first();
        if (is_null($riddle_team) || is_null($riddle_team->pivot->start_date))
            throw new Exception("Riddle not started");
        if (!is_null($riddle_team->pivot->end_start))
            throw new Exception("Riddle already finished");

        $riddle->teams()->updateExistingPivot($team->id, ['end_date' => now('Europe/Paris')]);

        if (all(Riddle::all(), function ($r) use ($team) {
            return $r->isDisabled || !is_riddle_in_parcours($r, $team) || is_riddle_completed($r, $team);
        })) {
            $team->end_date = now('Europe/Paris');
            $team->saveOrFail();
        }
    }
}


if (!function_exists('riddle_info')) {
    function riddle_info(Riddle $riddle, Team $team)
    {
        $riddle_team = $riddle->teams->where('id', $team->id)->first();
        return [
            'id' => $riddle->id,
            'name' => $riddle->name,
            'description' => $riddle->description,
            'post_resolution_message' => (is_null($riddle->postResolutionMessage) ? NULL : $riddle->postResolutionMessage->content),
            'url' => $riddle->url,
            'start_date' => is_null($riddle_team) || is_null($riddle_team->pivot->start_date) ? null : new Carbon($riddle_team->pivot->start_date),
            'end_date' => is_null($riddle_team) || is_null($riddle_team->pivot->end_date) ? null : new Carbon($riddle_team->pivot->end_date),
            'line' => $riddle->line
        ];
    }
}

if (!function_exists('riddle_info_for_gm')) {
    function riddle_info_for_gm(Riddle $riddle, Team $team)
    {
        $riddle_team = $riddle->teams->where('id', $team->id)->first();
        return [
            'id' => $riddle->id,
            'start_date' => is_null($riddle_team) || is_null($riddle_team->pivot->start_date) ? null : new Carbon($riddle_team->pivot->start_date),
            'end_date' => is_null($riddle_team) || is_null($riddle_team->pivot->end_date) ? null : new Carbon($riddle_team->pivot->end_date),
        ];
    }
}
