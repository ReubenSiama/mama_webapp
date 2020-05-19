<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerInvoice extends Model
{
    protected $connection = 'customer_db';
    protected $table = 'customer_invoices';



      function CustomerDetails(){
    	return $this->hasOne('App\CustomerDetails','customer_id','customer_id');
    }
    function orders(){
    	return $this->hasOne('App\Order','order_id','id');
    }
}