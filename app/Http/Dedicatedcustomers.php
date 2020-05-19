<?php

use App\Category;
use App\SalesTarget;
use App\MamahomePrice;
use App\Supplierdetails;
use App\Requirement;
use App\Gst;
use App\Dedicatedsalestarget;
use App\NewCustomerAssign;
use App\ProcurementDetails;
use App\Mprocurement_Details;
use App\CustomerDetails;
class Dedicatedcustomers{


  public static function getalltarget(){
    $sales = Dedicatedsalestarget::where('user_id',Auth::user()->id)->first();
    $salessss = Dedicatedsalestarget::where('user_id',Auth::user()->id)->count();
     $reqids=NewCustomerAssign::where('user_id',Auth::user()->id)->pluck('customerids')->first();
      $custids = explode(",",$reqids);
      $numbers = CustomerDetails::whereIn('customer_id',$custids)->pluck('mobile_num');
        $projects = ProcurementDetails::whereIn('procurement_contact_no',$numbers)->pluck('project_id');

        $manus =Mprocurement_Details::whereIn('contact',$numbers)->pluck('manu_id'); 
    if($salessss != 0){
      
         $enq = Requirement::whereIn('project_id',$projects)->orWhereIn('manu_id',$manus)->where('status',"")->pluck('id');
         

         $invoice = MamahomePrice::whereIn('req_id',$enq)->where('created_at','>=',$sales->start)->where('amountwithgst','!=',NULL)->where('created_at','<=',$sales->end)->sum('amountwithgst');
            $orderid = MamahomePrice::whereIn('req_id',$enq)->where('created_at','>=',$sales->start)->where('amountwithgst','!=',NULL)->where('created_at','<=',$sales->end)->get(); 
                 $totalnew = [];
                 $totaltps = [];
                 foreach ($orderid as $order) {
                    $sup = Supplierdetails::where('order_id',$order->order_id)->pluck('totalamount')->first();
                    $cat = Supplierdetails::where('order_id',$order->order_id)->pluck('description')->first();
                    $invoiceamount = MamahomePrice::where('order_id',$order->order_id)->pluck('amountwithgst')->first();
                    $state = MamahomePrice::where('order_id',$order->order_id)->pluck('state')->first();
                      if($state == 1){
                       $gt = Gst::where('Category',$cat)->pluck('cgst')->first();
                        $gst = $gt + $gt;
                      }else{
                       $gst = Gst::where('Category',$cat)->pluck('igst')->first();
                         
                      }
                    $tp = $invoiceamount - $sup;
                    $gstcal = ($tp * ($gst/100));
                  
                   $totaltp = ($tp - $gstcal);

                  array_push($totalnew, $invoiceamount);
                  array_push($totaltps,$totaltp);
                 }
                $am = array_sum($totalnew);
                $usertarget = ($sales->tpamount) - $am;
                $baltp = ($sales->tpamount) - array_sum($totaltps) ;
            return ['baltarget'=>$baltp,'achive'=>$am,'totaltarget'=>$sales->totalamount,'start'=>$sales->start,'end'=>$sales->end,'totaltps'=>array_sum($totaltps)];

               
    }


    }
}

























?>