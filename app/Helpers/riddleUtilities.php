<?php


use App\Events\ChangeEvent;
use App\Repositories\MessageRepository;
use App\Riddle;
use App\Team;
use App\FictitiousMessage;
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
        if (is_null($team->start_date)) {
            throw new Exception("Team not yet authorized");
        }
        $riddle_team = $riddle->teams->where('id', $team->id)->first();
        if (is_null($riddle_team)) {
            $riddle->teams()->attach($team, ['start_date' => now('Europe/Paris')]);
        } else if (is_null($riddle_team->pivot->start_date)) {
            $riddle->teams()->updateExistingPivot($team->id, ['start_date' => now('Europe/Paris')]);
        } else {
            throw new Exception("Riddle already started");
        }
        event(new ChangeEvent());
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
        event(new ChangeEvent());
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
			      return true;
        }
		    else{
			      return false;
		    }
        event(new ChangeEvent());
    }
}


if (!function_exists('riddle_info')) {
    function riddle_info(Riddle $riddle, Team $team, bool $can_start)
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
            'can_start' => $can_start
        ];
    }
}

if (!function_exists('riddle_info_for_gm')) {
    function riddle_info_for_gm(Riddle $riddle, Team $team)
    {
        $riddle_team = $riddle->teams->where('id', $team->id)->first();
        return [
            'id' => $riddle->id,
            'name' => $riddle->name,
            'start_date' => is_null($riddle_team) || is_null($riddle_team->pivot->start_date) ? null : $riddle_team->pivot->start_date,
            'end_date' => is_null($riddle_team) || is_null($riddle_team->pivot->end_date) ? null : $riddle_team->pivot->end_date,
        ];
    }
}

if (!function_exists('riddle_sisters')) {
    function riddle_sisters(Riddle $riddle)
    {
        $sisters = [$riddle];
        foreach ($riddle->parents as $parent){
            foreach ($parent->children as $child){
                if(!in_array($child,$sisters,true)){
                    $sisters[] = $child;
                }
            }
        }

        return $sisters;
    }
}

if (!function_exists('has_incomplete_sisters')){
    function has_incomplete_sisters(Riddle $riddle, Team $team)
    {
        return any(riddle_sisters($riddle), function ($r) use ($team) {
            return is_riddle_in_parcours($r, $team) && !is_riddle_completed($r, $team) && !$r->disabled;
        });
    }
}

if (!function_exists('calculerClassement')) {
    function calculerClassement($user)
    {
        $fin = substr($user->id, -1) . '$';
        return (DB::table('teams')->distinct('score')->where([
                    ['id', 'regexp', $fin],
                    ['score', '>', $user->score]
                ]
            )->count() + 1);
    }
}

if (!function_exists('team_progression')){
    function team_progression(Team $team)
    {
        $progression = 0;
        $count = 0;
        $parcrouss = $team->parcours;
        foreach ($parcrouss as $parcours){
            if(!$parcours->riddle->disabled){
                $count += 1;
            }
            if (is_riddle_completed($parcours->riddle,$team)){
                $progression += 1;
            }
        }
        $progression = $progression/$count;
        return $progression;
    }
}
