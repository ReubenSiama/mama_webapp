<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MRSupplierdetails extends Model
{
    protected $table = "m_r_supplierdetails";


public function sts(){
        return $this->belongsTo('App\State','state','id');
    }


}
