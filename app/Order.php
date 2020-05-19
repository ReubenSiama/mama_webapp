<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table = 'orders';
    public $incrementing = false;
    function payment(){
    	return $this->hasOne('App\Payment','order_id');
    }
    function deposit(){
    	return $this->hasOne('App\Deposit','orderId','id');
    }
    function check(){
    	return $this->hasOne(Check::class,'id','orderId');
    }
    function paymentDetails(){
        return $this->hasMany(PaymentDetails::class);
    }
    function admin(){
        return $this->hasOne('App\Requirement','project_id','project_id');
    }
    function req(){
        return $this->hasOne('App\Requirement','id','req_id');
    }
    function manudetails(){
        return $this->hasOne('App\ManufacturerDetail','category','category');
    }
     function  cat(){
        return $this->hasOne('App\Category','category_name','main_category');
    }
    public function manu()
    {
      return $this->hasOne('App\Manufacturer','id','manu_id');
    
    }
     public function siteaddress(){
        return $this->belongsTo('App\SiteAddress','project_id','project_id');
    }
    public function pro(){
        return $this->belongsTo('App\procurementDetails','project_id','project_id');
    }
      public function st(){
        return $this->belongsTo('App\State','state','id');
    }
    
    public function userid(){
        return $this->belongsTo('App\User','logistic','id');
    }
     public function lename(){
        return $this->belongsTo('App\User','lename','id');
    }
    public function invoice(){
        return $this->belongsTo('App\MamahomePrice','order_id','id');
    }
     public function sp(){
        return $this->belongsTo('App\ManufacturerDetail','order_id','id');
    }

   public function mr(){
        return $this->hasOne('App\MRSupplierdetails','order_id','id');
    }



    protected $fillable = [
            'project_id',
            'req_id',
            'main_category',
            'brand',
            'sub_category',
            'material_spec',
            'referral_image1',
            'referral_image2',
            'requirement_date',
            'measurement_unit',
            'unit_price',
            'quantity',
            'total',
            'notes',
            'status',
            'Truck',
            'payment_status',
            'delivery_status',
            'dispatch_status',
            'generated_by',
            'signature',
            'delivery_boy',
            'delivered_on',
            'payment_mode',
        ];
    protected static $causerId = 3;
    protected static $logName = "";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
}
