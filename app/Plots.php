<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Traits\LogsActivity;
class Plots extends Model
{
    use SoftDeletes;
         use LogsActivity;
protected $table = 'plot_details';

protected static $logFillable = true;
     protected static $logOnlyDirty = true; 
      protected static $causerId = 3;

  public function subward()
    {
      return $this->hasOne('App\SubWard','id','sub_ward_id');
    
    } 
     public function user(){
        return $this->belongsTo('App\User','listing_engineer_id','id');
    }
   public function plot()
    {
      return $this->hasOne('App\PlotsCustomers','plot_id','plot_id');
    
    } 
}
