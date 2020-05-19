<?php

use App\CategoryTarget;
use App\MamahomePrice;
use App\Requirement;
use App\SalesTarget;
use App\Gst;
use App\Category;
use App\Supplierdetails;
class TotalCategoryhelper{
	
	public static function gettotalcat(){

       $sales = CategoryTarget::all();
       



	 $data = [];
   
    
       foreach ($sales as $sale) {
       
         
          
          $catname = Category::where('id',$sale->category)->pluck('category_name')->first();
           
          $invoice = MamahomePrice::where('description',$catname)->where('created_at','>=',$sale->start)->where('created_at','<=',$sale->end)->sum('amountwithgst');

           $gst = Gst::where('Category',$catname)->first();
                if($gst->cgst != NULL){

                	$gstval = ($gst->cgst + $gst->sgst);
                }else{
                	$gstval = ($gst->igst);
                }

                 $oders = MamahomePrice::where('description',$catname)->where('created_at','>=',$sale->start)->where('created_at','<=',$sale->end)->pluck('order_id');
           
           $sp = Supplierdetails::whereIn('order_id',$oders)->sum('totalamount');
       
           $total = $invoice - $sp;

           $tp = ($total * ($gstval/100));
            
            $finaltp = $total - $tp;
             
         array_push($data,['category'=>$catname,'invoice'=>$finaltp,'categorytarget'=>$sale->totalatpmount]);

       
      
      





   }

        return $data;
	


}

}

?>