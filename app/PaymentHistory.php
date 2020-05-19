<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
class PaymentHistory extends Model
{
     use LogsActivity;
     protected $table = 'payment_history';
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
 'payment_mode',
 'date',
 'totalamount',
 'damount',
 'account_number',
 'branch_name',
 'cheque_number',
 'bank_name',
 'file',
 'rtgs_file',
 'cash_holder',
 'payment_note',
 'created_at',
 'updated_at',
 ];
     protected static $logFillable = true;
     protected static $logOnlyDirty = true; 
     protected static $causerId = 3;
}
