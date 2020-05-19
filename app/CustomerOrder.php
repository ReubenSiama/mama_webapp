<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    protected $connection = 'customer_db';
    protected $table = 'customer_orders';
}
