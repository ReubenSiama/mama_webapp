<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierProject extends Model
{
    protected $connection = 'suplier_db';
    protected $table = 'supplier_projects';
}
