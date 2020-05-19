<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOtherNumbers extends Model
{
     protected $connection = 'customer_db';
    protected $table = 'customer_other_numbers';
}
