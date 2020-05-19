<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{



	 protected $connection = 'interview';
     protected $table = 'interviews';


     public function fuser(){
        return $this->belongsTo('App\FirstRound','id','interview_id');
    }
    public function suser(){
        return $this->belongsTo('App\SecoundRound','id','interview_id');
    }




}
