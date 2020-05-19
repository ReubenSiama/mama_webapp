<?php

namespace App\Http\Controllers;
use App\Category;
use App\MamahomePrice;
use App\Order;
use DB;
use App\CustomerInvoice;
use App\CustomerOrder;
use App\User;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function index(Request $request){


 ///////inputs from user
        $cats_data = $request->category;
        $date_from=$request->from;
        $date_to=$request->to;  
        $user_by=$request->user_id;

///////fetching  from db
        $cats = Category::All();
        $users = User::All();
       
        
////////////// condtions 
        if($request->category == "all" && $date_from == null && $date_to == null && $request->user_id == null)
        {
       
        $invoice_data = CustomerInvoice::All()->get();
        $invoice_data_total = CustomerInvoice::All()->sum('mhInvoiceamount');
     
         } 
       
         elseif($request->user_id && $request->category ){
            $cats_data = $request->category;
            $orders_user= CustomerOrder::where('orderconfirmname',$request->user_id)->pluck('order_id');
            $invoice_data = CustomerInvoice::whereIn('order_id',$orders_user)->where('category',$cats_data)->get();

        }
        elseif($request->user_id && $request->category == null){
            $cats_data = $request->category;
            $orders_user= CustomerOrder::where('orderconfirmname',$request->user_id)->pluck('order_id');
            $invoice_data = CustomerInvoice::whereIn('order_id',$orders_user)->get();
            $invoice_data_total = CustomerInvoice::where('category',$cats_data)->sum('mhInvoiceamount');
           
        }

         elseif($request->category && $request->user_id == "") {
            $cats_data = $request->category;
            $invoice_data = CustomerInvoice::where('category',$cats_data)->get();
            $invoice_data_total = CustomerInvoice::where('category',$cats_data)->sum('mhInvoiceamount');
            
         }
         
         elseif($request->category && $request->category != "all" && $request->user_id = "" ) {
            $cats_data = $request->category;
            $invoice_data = CustomerInvoice::where('category',$cats_data)->get();
            $invoice_data_total = CustomerInvoice::where('category',$cats_data)->sum('mhInvoiceamount');
            
         }

         elseif($date_from != "" && $date_to != "" ){
            $invoice_data = CustomerInvoice::where('invoicedate','>=',$date_from ,'AND', 'invoicedate','=<',$date_to)->get();
         }
         
         elseif($request->category == "all" && $date_from != "" && $date_to != "" ){
            $invoice_data = CustomerInvoice::where('invoicedate','>=',$date_from ,'AND', 'invoicedate','=<',$date_to)->get();
            $invoice_data_total = CustomerInvoice::where('category',$cats_data)->where('invoicedate','>=',$date_from ,'AND', 'invoicedate','=<',$date_to)->sum('mhInvoiceamount');
         }
         
         elseif($request->category != "all" && $date_from != "" && $date_to != "" ){
            $invoice_data = CustomerInvoice::where('invoicedate','>=',$date_from ,'AND', 'invoicedate','=<',$date_to)->get();
            $invoice_data_total = CustomerInvoice::where('invoicedate','>=',$date_from ,'AND', 'invoicedate','=<',$date_to)->sum('mhInvoiceamount');
         
        }
        elseif( $request->category != "all" &&  !$request->date_from != "" && !$request->date_to != "" &&  !$request->user_by != "" ) {
            $invoice_data = CustomerInvoice::All();
            $invoice_data_total = CustomerInvoice::All()->sum('mhInvoiceamount');
            
         } 
       
         elseif($request->category && $request->category != "all")
        {
       
        $cats_data = $request->category;
        $invoice_data = CustomerInvoice::where('category',$cats_data)->get();
        $invoice_data_total = CustomerInvoice::where('category',$cats_data)->sum('mhInvoiceamount');
      
         } 
        
         

         else{
            $invoice_data=[];
            $invoice_data_total=0;
         }  
        
         $invoice_data_total=0;
        $count_invoice=count($invoice_data);
        return  view ('salesreports.reports_sales',([
            'users'=>$users,
            'count_invoice'=>$count_invoice,
            'invoice_data'=>$invoice_data,'users'=>$users,
            'cats'=>$cats, 
            'invoice_data_total'=>$invoice_data_total, 
           ]
    ));
   }




}
