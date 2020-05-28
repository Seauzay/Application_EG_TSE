<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FictitiousMessage extends Model
{
    //

    protected $table = 'messages';

    public function messages()
    {
        return $this->belongsToMany('App\Message', 'messaging');
    }

    public function riddle()
    {
        return $this->belongsTo('App\Riddle','riddle_id');
    }
}
