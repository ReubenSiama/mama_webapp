<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    protected $table = "retailers";

     public function subward()
    {
      return $this->hasOne('App\SubWard','id','subward_id');
    
    } 
   public function user(){
        return $this->belongsTo('App\User','test','id');
    }

}
