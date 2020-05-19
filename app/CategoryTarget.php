<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryTarget extends Model
{
    protected $table = "category_targets";


     public function cat()
    {
    	return $this->hasOne("App\Category",'id','category');
    }
    
}
