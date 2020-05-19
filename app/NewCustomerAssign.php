<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewCustomerAssign extends Model
{
     protected $connection = 'customer_db';
    protected $table = 'new_customer_assigns';
}
