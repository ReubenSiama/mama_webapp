<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
class Manufacturer extends Model
{
    use LogsActivity;
     use SoftDeletes;
    public function manufacturerProduct(){
        return $this->hasMany('App\ManufacturerProduce');
    }
     public function Manager()
    {
    	return $this->hasOne('App\Manager_Deatils','manu_id','id');
    }
    public function sales()
    {
    	return $this->hasOne('App\Salescontact_Details','manu_id','id');
    }
    public function proc()
    {
    	return $this->hasOne('App\Mprocurement_Details','manu_id','id');
    }
    public function owner()
    {
    	return $this->hasOne('App\Mowner_Deatils','manu_id','id');
    }
     public function subward()
    {
        return $this->hasOne('App\SubWard','id','sub_ward_id');
    }
    public function user()
    {
        return $this->hasOne('App\User','id','listing_engineer_id');
    }
     public function user1()
    {
        return $this->hasOne('App\User','id','updated_by');
    }
    public function customerbrands()
    {
      return $this->hasOne('App\CustomerBrands','manu_id','id');
    
    } 
    protected $fillable = [
            'sub_ward_id',
            'name',
            'address',
            'area',
            'capacity',
            'present_utilization',
            'cement_requirement',
            'prefered_cement_brand',
            'deliverability',
            'sand_requirement',
            'type',
            'payment_mode',
            'cement_used',
            'moq',
            'manufacturer_type',
            'plant_name',
            'latitude',
            'longitude',
            'cement_requirement_measurement',
            'contact_no',
            'listing_engineer_id',
            'aggregates_required',
            'total_area',
            'confirmed',
            'remarks',
            'production_type',
        ];
    protected static $causerId = 3;
    protected static $logName = "";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
}



