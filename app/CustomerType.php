<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
   protected $connection = 'customer_db';
   protected $table = "customer_type";
}
