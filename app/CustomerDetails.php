<?php

namespace App;
use App\CustomerType;
use Illuminate\Database\Eloquent\Model;

class CustomerDetails extends Model
{
	 protected $connection = 'customer_db';
    protected $table = 'customer_details';


     public function subward()
    {
      return $this->hasOne('App\SubWard','id','sub_ward_id');
    
    } 

    public function type(){

    	 return $this->hasOne('App\CustomerType','id','sub_customer_type');
    }

   public function orders(){

       return $this->hasOne('App\CustomerOrder','customer_id','customer_id');
    }
     

    public static function  typeis(){
         
          $data = CustomerType::get();


    	 return $data;
    }

}

