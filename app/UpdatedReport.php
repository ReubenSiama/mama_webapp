<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpdatedReport extends Model
{
    protected $table = "updated_reports";

      public function manu()
    {
      return $this->hasOne('App\Manufacturer','id','manu_id');
    
    } 
     public function project()
    {
      return $this->hasOne('App\ProjectDetails','project_id','project_id');
    
    } 
      public function user()
    {
      return $this->hasOne('App\User','id','user_id');
    
    }
public function updateuser(){

      return $this->hasOne('App\User','id','user_id');
    } 
   
}
