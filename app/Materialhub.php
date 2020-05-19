<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
class Materialhub extends Model
{
	use LogsActivity;
    
    protected $table = 'materialhubs';
    protected $primaryKey = 'id';

    public function subward()
    {
      return $this->hasOne('App\SubWard','id','subward_id');
    
    } 
   public function user(){
        return $this->belongsTo('App\User','user_id','id');
    }
  protected $fillable = [
'subward_id',
'longitude',
'latitude',
'onumber',
'trucknumber',
'address',
'Category',
'Capacity',
'remarks',
'pImage',
'user_id',
'name',
'Vehicaltype',
        ];
    protected static $causerId = 3;
    protected static $logName = "";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
}
