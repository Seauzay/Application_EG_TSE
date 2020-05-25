<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /*
     * Available fields:
     * integer id
     * string name
     */

    public $timestamps = false;


    public function teams()
    {
        return $this->belongsToMany('App\Team', 'rooms_teams');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}
