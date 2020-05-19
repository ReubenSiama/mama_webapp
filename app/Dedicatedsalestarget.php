<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dedicatedsalestarget extends Model
{
    protected $table = "dedicatedsalestargets";
    public function user()
    {
    	return $this->hasOne("App\User",'id','user_id');
    }
}
