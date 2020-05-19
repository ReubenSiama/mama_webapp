<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerBrands extends Model
{
   protected $connection = 'customer_db';
    protected $table = 'customer_brands';

  public function manu()
    {
      return $this->hasOne('App\Manufacturer','id','manu_id');
    
    } 



}
