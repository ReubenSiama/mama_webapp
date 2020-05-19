<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirstRound extends Model
{
    
    protected $connection = 'interview';
     protected $table = 'first_rounds';
     public function user(){
        return $this->hasOne('App\Interview','id','interview_id');
    }
}
