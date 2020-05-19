<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pricing extends Model
{
    use LogsActivity;
     use SoftDeletes;

    protected $table = 'pricing';
    protected $fillable = ['pid','cat','brand','suncat','quantity','stl','leandse','created_at','updated_at','deleted_at'];
    protected static $causerId = 3;
    protected static $logName = "";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
}
