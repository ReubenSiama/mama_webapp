<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesTarget extends Model
{
    protected $table = "sales_targets";
    public function user()
    {
    	return $this->hasOne("App\User",'id','user_id');
    }
}
