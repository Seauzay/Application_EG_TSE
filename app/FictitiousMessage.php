<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FictitiousMessage extends Model
{
    //
    public $timestamps = false;

    protected $table = 'messages';

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function riddle()
    {
        return $this->belongsTo('App\Riddle','riddle_id');
    }
}
