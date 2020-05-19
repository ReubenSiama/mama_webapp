<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderExpenses extends Model
{
    protected $table = 'order_expenses';
    public function user(){
        return $this->belongsTo('App\User','selectuser','id');
    }
}
