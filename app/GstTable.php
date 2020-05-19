<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GstTable extends Model
{
    protected $connection = 'customer_db';
    protected $table = 'gst_tables';
}
