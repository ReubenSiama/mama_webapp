<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class PaymentDetails extends Model
{
     use LogsActivity;
    protected $table = 'payment_details';
     protected $primaryKey = 'id';
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
     public function user(){
        return $this->belongsTo('App\User','cash_holder','id');
    }
     protected $fillable = [

 'order_id',
 'file',
 'payment_mode',
 'date',
 'totalamount',
 'damount',
 'account_number',
 'branch_name',
 'cheque_number',
 'payment_note',
 'quantity',
 'mamahome_price',
 'status',
 'unit',
 'manufacturer_price',
 'bank_name',
 'cash_holder',
 'project_id',
 'manu_id',
 'rtgs_file',
 'category',
 'created_at', 
 'updated_at', 
 ];
     protected static $logFillable = true;
     protected static $logOnlyDirty = true; 
     protected static $causerId = 3;
}
