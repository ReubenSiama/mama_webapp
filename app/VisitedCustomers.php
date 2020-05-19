<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisitedCustomers extends Model
{
    protected  $table = "visited_customers";

    public function cust()
    {
    	return $this->hasOne("App\CustomerDetails",'customer_id','customer_id');
    }
}
