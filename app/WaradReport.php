<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WaradReport extends Model
{
    


    protected $table = 'warad_reports';


    public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
}
