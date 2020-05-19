<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecoundRound extends Model
{
    protected $connection = 'interview';
     protected $table = 'secound_rounds';
       public function user(){
        return $this->hasOne('App\Interview','id','interview_id');
    }
}
