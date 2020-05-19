<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
     protected $connection = 'suplier_db';
    protected $table = 'transports';
}
