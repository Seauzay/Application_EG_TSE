<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Riddle extends Model
{
    /*
     * Available fields:
     * integer id
     * string name
     * string url
     * string code
     * boolean disabled
     */

    public $timestamps = false;

    protected $casts = [
        'disabled' => 'boolean'
    ];

    public function teams()
    {
        return $this->belongsToMany('App\Team', 'riddles_teams')->withPivot('start_date', 'end_date');
    }

    public function parcours()
    {
        return $this->hasMany('App\Parcours');
    }

    public function getParentsAttribute()
    {
        try{
            return Riddle::where('line','=',$this->line-1)->get();
        }
        catch(Exception $e){
            return null;
        }
    }

    public function getChildrenAttribute()
    {
        try{
            return Riddle::where('line','=',$this->line+1)->get();
        }
        catch(Exception $e){
            return null;
        }
    }

    public function postResolutionMessage()
    {
        return $this->hasOne('App\FictitiousMessage');
    }
}
