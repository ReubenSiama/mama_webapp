<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
     protected $connection = 'suplier_db';
    protected $table = 'supplier_orders';
}
