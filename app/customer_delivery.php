<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customer_delivery extends Model
{
    protected $connection = 'customer_db';
    protected $table = 'customer_deliveries';
}
