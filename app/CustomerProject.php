<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerProject extends Model
{
    protected $connection = 'customer_db';
    protected $table = 'customer_projects';
}
