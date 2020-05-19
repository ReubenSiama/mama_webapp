<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierGst extends Model
{
     protected $connection = 'suplier_db';
    protected $table = 'supplier_gsts';
}
