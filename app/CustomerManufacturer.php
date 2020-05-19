<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerManufacturer extends Model
{
    protected $connection = 'customer_db';
    protected $table = 'customer_manufacturers';
}
