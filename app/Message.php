<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /*
     * Available fields:
     * integer id
     * string content
     * datetime date
     */
    public $timestamps = false;

    protected $casts = [
        'date' => 'datetime'
    ];

    public function author()
    {
        return $this->belongsTo('App\Team', 'team_id');
    }

    public function room()
    {
        return $this->belongsTo('App\Room');
    }
}
