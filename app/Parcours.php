<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parcours extends Model
{
    public $timestamps = false;
    
    public function team()
    {
        return $this->belongsTo('App\Team');
    }
    
    public function riddle()
    {
        return $this->belongsTo('App\Riddle');
    }
}
