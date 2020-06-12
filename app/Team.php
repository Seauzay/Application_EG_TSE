<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Team extends Authenticatable
{
    use Notifiable;

    /*
     * Available fields:
     * integer id
     * string name
     * string|null password
     * integer grade
     * datetime|null start_date
     * datetime|null end_date
     */


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];


    public $timestamps = false;


    public function rooms()
    {
        return $this->belongsToMany('App\Room', 'rooms_teams');
    }

    public function parcours()
    {
        return $this->hasMany('App\Parcours');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function riddles()
    {
        return $this->belongsToMany('App\Riddle', 'riddles_teams')->withPivot('start_date', 'end_date');
    }
}
