<?php

use App\CategoryTarget;
use App\MamahomePrice;
use App\Requirement;
use App\SalesTarget;
use App\Gst;
use App\Category;
use App\Supplierdetails;
class CategoryTargethelpers{
	
	public static function getcattarget(){

       $sales = CategoryTarget::all();
       $sep = SalesTarget::where('user_id',Auth::user()->id)->first();



	 $yup = [];
   if(count($sep) > 0){
    
       foreach ($sales as $sale) {
       
         $req = Requirement::where('generated_by',Auth::user()->id)->pluck('id');


          $catname = Category::where('id',$sale->category)->pluck('category_name')->first();
           
          $invoice = MamahomePrice::whereIn('req_id',$req)->where('category',$catname)->where('created_at','>=',$sep->start)->where('created_at','<=',$sep->end)->where('amountwithgst','!=',NULL)->sum('amountwithgst');

        
           $gst = Gst::where('Category',$catname)->first();
                if($gst->cgst != NULL){

                	$gstval = ($gst->cgst + $gst->sgst);
                }else{
                	$gstval = ($gst->igst);
                }

        $oders = MamahomePrice::whereIn('req_id',$req)->where('category',$catname)->where('created_at','>=',$sep->start)->where('created_at','<=',$sep->end)->where('amountwithgst','!=',NULL)->pluck('order_id');
           
          $sp = Supplierdetails::whereIn('order_id',$oders)->sum('totalamount');
       
           $total = $invoice - $sp;

           $pen = $sep->tpamount;
           $rem = ($pen - $total);

           $tp = ($total * ($gstval/100));
            
            $finaltp = $total - $tp;
             
         array_push($yup,['category'=>$catname,'invoice'=>$invoice]);

       }
      
      


        return $yup;



   }

	


}

}

?>