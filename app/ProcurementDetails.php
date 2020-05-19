<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
class ProcurementDetails extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table = 'procurement_details';
    public function projectdetails()
    {
    	return $this->belongsTo("App\ProjectDetails");
    }
    protected $fillable = [ 	
        'project_id',
        'procurement_name',
        'procurement_contact_no',
        'procurement_email',
    ];
    protected static $causerId = 3;
    protected static $logName = "";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function requirement()
    {
        return $this->belongsTo("App\Requirement");
    }
    public function project(){

         return $this->hasOne('App\ProjectDetails','project_id','project_id');
    }
}
