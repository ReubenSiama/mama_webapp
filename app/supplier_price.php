<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class supplier_price extends Model
{
    protected $connection = 'suplier_db';
    protected $table = 'supplier_price';
}
