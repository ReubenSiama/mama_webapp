<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Order;
use App\MRInvoice;

use App\MRSupplierdetails;

use App\SiteAddress;
use App\ProcurementDetails;
use App\Mowner_Deatils;
use App\PaymentDetails;
use App\Message;
use App\User;
use App\Tlwards;
use App\MamahomePrice;
use App\Supplierdetails;
use App\Country;
use App\Zone;
use App\ManufacturerDetail;
use App\Manufacturer;

use App\Mprocurement_Details;
use App\Requirement;
use App\Quotation;
use App\Gst;
use App\Category;
use App\SupplierInvoice;
use App\brand;
use App\PaymentHistory;
use App\Denomination;
use DB;
use Auth;
use App\BankTransactions;
use App\MultipleInvoice;
use Illuminate\Support\Facades\Redirect;
use PDF;
use App\MultipleSupplierInvoice;
use App\Ledger;
use App\CustomerDetails;
date_default_timezone_set("Asia/Kolkata");
class FinanceDashboard extends Controller
{
    public function financeIndex()
    {
        return view('finance.index');
    }
    public function getFinanceDashboard(request $request)
    {

            

        if(Auth::user()->group_id == 22){
            $tlward = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
            $ward = explode(",",$tlward);
            $orders = DB::table('orders')->where('status','Order Confirmed')->orderBy('updated_at','desc')
                      ->leftjoin('project_details','project_details.project_id','orders.project_id')
                       ->leftjoin('sub_wards','sub_wards.id','project_details.sub_ward_id')
                       ->leftJoin('wards','wards.id','sub_wards.ward_id')
                       ->whereIn('wards.id',$ward)
                       ->where('orders.deleted_at','!=',null)
                       ->select('orders.*','project_details.sub_ward_id')
                       ->paginate('20');
                      
        }
        else if($request->projectId){
              $orders = DB::table('orders')->where('project_id',$request->projectId)->orwhere('manu_id',$request->projectId)->orwhere('id',$request->projectId)->where('status','Order Confirmed')->orderBy('updated_at','desc')->paginate('20');
        }else if(!$request->projectId && $request->from && $request->to && !$request->user && !$request->category){

                                            
                   $orders =DB::table('orders')
                      ->whereDate('created_at','>=',$request->from)
                      ->whereDate('created_at','<=',$request->to)
                      ->where('status','Order Confirmed')
                      ->orderBy('updated_at','desc')->paginate('20');
           }else if(!$request->projectId && $request->from && $request->to && $request->user && $request->category){

                                            
                   $orders =DB::table('orders')
                      ->whereDate('created_at','>=',$request->from)
                      ->whereDate('created_at','<=',$request->to)
                      ->where('generated_by',$request->user)
                      ->where('main_category',$request->category)
                       ->where('status','Order Confirmed')
                      ->orderBy('updated_at','desc')->paginate('20');
           }else if(!$request->projectId && $request->from && $request->to && $request->user && !$request->category){

                                            
                   $orders = DB::table('orders')
                      ->whereDate('created_at','>=',$request->from)
                      ->whereDate('created_at','<=',$request->to)
                      ->where('generated_by',$request->user)
                      ->where('status','Order Confirmed')
                      ->orderBy('updated_at','desc')->paginate('20');
                   
           }else if(!$request->projectId && $request->from && $request->to && !$request->user && $request->category){

                                            
                   $orders = DB::table('orders')
                         ->whereDate('created_at','>=',$request->from)
                         ->whereDate('created_at','<=',$request->to)
                         ->where('main_category',$request->category)
                         ->where('status','Order Confirmed')
                         ->orderBy('updated_at','desc')->paginate('20');
           }else if(!$request->projectId && !$request->from && !$request->to && $request->user && !$request->category){

                                            
                   $orders = DB::table('orders')
                      ->where('generated_by',$request->user)
                      ->where('status','Order Confirmed')
                      ->orderBy('updated_at','desc')->paginate('20');


                     
                    
           }else if(!$request->projectId && !$request->from && !$request->to && !$request->user && $request->category){

                                            
                   $orders = DB::table('orders')
                      
                      ->where('main_category',$request->category)
                       ->where('status','Order Confirmed')
                      ->orderBy('updated_at','desc')->paginate('20');
           }


        else{
            
            $orders = DB::table('orders')->where('status','Order Confirmed')->orderBy('updated_at','desc')->paginate('20');
        }
                      
        $count = count($orders);
        $reqs = Requirement::all();
        $payments = PaymentDetails::get();
        $data = MamahomePrice::distinct()->select('mamahome_invoices.order_id','mamahome_invoices.id')->pluck('mamahome_invoices.id','mamahome_invoices.order_id');
        $mamaprices = MamahomePrice::whereIn('id',$data)->get();
        $messages = Message::all();
        $counts = array();
        $users = User::all();
         $payhistory = PaymentHistory::all();
        foreach($orders as $order){
            $counts[$order->id] = Message::where('to_user',$order->id)->count();
        }
            
        return view('finance.financeOrders',['mamaprices'=>$mamaprices,'users'=>$users,'orders'=>$orders,'payments'=>$payments,'messages'=>$messages,'counts'=>$counts,'reqs'=>$reqs,'payhistory'=>$payhistory,'count'=>$count]);
    }
    public function clearOrderForDelivery(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->clear_for_delivery = "Yes";
        $order->save();
        return back()->with('Success','Order Cleared For Delivery');
    }
    public function downloadquotation(Request $request)
    {
        $price = Quotation::where('req_id',$request->id)->first();
        $procurement = ProcurementDetails::where('project_id',$price->project_id)->first();
        $mprocurement = Mprocurement_Details::where('manu_id',$request->manu_id)->first();
        $gst = Quotation::where('req_id',$request->id)->pluck('gstpercent')->first();
        if($gst == 1.28){
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
        else if($gst == 1.18){
            $cgst = 9;
            $sgst = 9;
            $igst = 18;
        }
       else if($gst == 1.05){
            $cgst = 2.5;
            $sgst = 2.5;
            $igst = 5;
        }
        else{
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
        $data = array(
            'price'=>$price,
            'procurement'=>$procurement,
            'mprocurement'=>$mprocurement,
            'igst'=>$igst
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.quotation')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('pdf.quotation');
        }
    }

   public function downloadquotation1(Request $request)
    {

        $price = Quotation::where('id',$request->id)->first();
       
        $procurement = ProcurementDetails::where('project_id',$price->project_id)->first();
        $mprocurement = Mprocurement_Details::where('manu_id',$request->manu_id)->first();
        $gst = Quotation::where('req_id',$request->id)->pluck('gstpercent')->first();
        if($gst == 1.28){
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
        else if($gst == 1.18){
            $cgst = 9;
            $sgst = 9;
            $igst = 18;
        }
       else if($gst == 1.05){
            $cgst = 2.5;
            $sgst = 2.5;
            $igst = 5;
        }
        else{
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
        $data = array(
            'price'=>$price,
            'procurement'=>$procurement,
            'mprocurement'=>$mprocurement,
            'igst'=>$igst
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.quotation1')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('pdf.quotation1');
        }
    }






    public function downloadInvoice(Request $request)
    {
        $id = MamahomePrice::where('invoiceno',$request->invoiceno)->pluck('order_id')->first();
        $e_way_no = MamahomePrice::where('invoiceno',$request->invoiceno)->pluck('e_way_no');
        $products = DB::table('orders')->where('id',$id)->first();
        $address = SiteAddress::where('project_id',$products->project_id)->first();
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $payment = PaymentDetails::where('order_id',$id)->first();
        $invoicedate = Supplierdetails::where('order_id',$id)->pluck('updated_at')->first();
        if(count($invoicedate) == 0){
            $invoicedate = MamahomePrice::where('order_id',$id)->pluck('updated_at')->first();
        }
        $price = MamahomePrice::where('order_id',$id)->orderby('created_at','DESC')->first()->getOriginal();
        $gst = MamahomePrice::where('order_id',$id)->pluck('gstpercent')->first();
        if($gst == 1.28){
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
        else if($gst == 1.18){
            $cgst = 9;
            $sgst = 9;
            $igst = 18;
        }
       else if($gst == 1.05){
            $cgst = 2.5;
            $sgst = 2.5;
            $igst = 5;
        }
        else{
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
         if( $request->manu_id != null){
        $manu = Manufacturer::where('id',$request->manu_id)->first()->getOriginal();
        $mprocurement = Mprocurement_Details::where('manu_id',$request->manu_id)->first()->getOriginal();
            }
            else{
                $mprocurement = "";
                $manu = "";
            }
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'price'=>$price,
            'manu'=>$manu,
            'mprocurement'=>$mprocurement,
            'cgst'=>$cgst,
            'sgst'=>$sgst,
            'e_way_no'=>$e_way_no,
            'igst'=>$igst,
            'invoicedate'=>$invoicedate,
            'id'=>$request->invoiceno,
            'mr'=>$request->mr
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.invoice')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('ProformaInvoice'.'('.$id.')'.'.pdf');
        }
    }

     public function downloadmrInvoice(Request $request)
    {
        $id = MRInvoice::where('invoiceno',$request->invoiceno)->pluck('order_id')->first();
        $e_way_no = MRInvoice::where('invoiceno',$request->invoiceno)->pluck('e_way_no');
        $products = DB::table('orders')->where('id',$id)->first();
        $address = SiteAddress::where('project_id',$products->project_id)->first();
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $payment = PaymentDetails::where('order_id',$id)->first();
        $invoicedate = Supplierdetails::where('order_id',$id)->pluck('updated_at')->first();
        if($invoicedate == null){
            $invoicedate = MRInvoice::where('order_id',$id)->pluck('updated_at')->first();
        }
        $price = MRInvoice::where('order_id',$id)->orderby('created_at','DESC')->first()->getOriginal();
        $gst = MRInvoice::where('order_id',$id)->pluck('gstpercent')->first();
        if($gst == 1.28){
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
        else if($gst == 1.18){
            $cgst = 9;
            $sgst = 9;
            $igst = 18;
        }
       else if($gst == 1.05){
            $cgst = 2.5;
            $sgst = 2.5;
            $igst = 5;
        }
        else{
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
         if( $request->manu_id != null){
        $manu = Manufacturer::where('id',$request->manu_id)->first()->getOriginal();
        $mprocurement = Mprocurement_Details::where('manu_id',$request->manu_id)->first()->getOriginal();
            }
            else{
                $mprocurement = "";
                $manu = "";
            }
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'price'=>$price,
            'manu'=>$manu,
            'mprocurement'=>$mprocurement,
            'cgst'=>$cgst,
            'sgst'=>$sgst,
            'e_way_no'=>$e_way_no,
            'igst'=>$igst,
            'invoicedate'=>$invoicedate,
            'id'=>$request->invoiceno,
            'mr'=>$request->mr
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.mrinvoice')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('ProformaInvoice'.'('.$id.')'.'.pdf');
        }
    }
    
public function downloadInvoice1(Request $request)
    {
        $id = MultipleInvoice::where('id',$request->id)->where('invoiceno',$request->invoiceno)->pluck('order_id')->first();

        $products = MultipleInvoice::where('id',$request->id)->first();
        $projectid = DB::table('orders')->where('id',$id)->pluck('project_id')->first();
        $address = SiteAddress::where('project_id',$projectid)->first();
        $procurement = ProcurementDetails::where('project_id',$projectid)->first();
        $payment = PaymentDetails::where('order_id',$id)->first();
        $invoicedate = Supplierdetails::where('order_id',$id)->pluck('updated_at')->first();
      
        if(count($invoicedate) == 0){
            $invoicedate = MultipleInvoice::where('order_id',$id)->pluck('updated_at')->first();
        }
        $price = MultipleInvoice::where('id',$request->id)->orderby('created_at','DESC')->first()->getOriginal();
        
         if( $request->manu_id != null){
        $manu = Manufacturer::where('id',$request->manu_id)->first()->getOriginal();
        $mprocurement = Mprocurement_Details::where('manu_id',$request->manu_id)->first()->getOriginal();
            }
            else{
                $mprocurement = "";
                $manu = "";
            }
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'price'=>$price,
            'manu'=>$manu,
            'mprocurement'=>$mprocurement,           
            
            'invoicedate'=>$invoicedate,
            'id'=>$request->invoiceno,
            'mr'=>$request->mr
        );

        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.invoice1')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('ProformaInvoice'.'('.$id.')'.'.pdf');
        }
    }

function downloadTaxInvoice(Request $request){
        $id = MamahomePrice::where('invoiceno',$request->invoiceno)->pluck('order_id')->first();
        $e_way_no = MamahomePrice::where('invoiceno',$request->invoiceno)->pluck('e_way_no')->first();
        $products = DB::table('orders')->where('id',$id)->first();
        $address = SiteAddress::where('project_id',$products->project_id)->first();
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $payment = PaymentDetails::where('order_id',$id)->first();
        $price = MamahomePrice::where('order_id',$id)->orderby('created_at','DESC')->first()->getOriginal();
        $gst = MamahomePrice::where('order_id',$id)->pluck('gstpercent')->first();
        $payment = paymentDetails::where('order_id',$id)->first();
        $invoicedate = MamahomePrice::where('invoiceno',$request->invoiceno)->pluck('invoicedate')->first();
        if(count($invoicedate) == 0){
            $invoicedate = MamahomePrice::where('order_id',$id)->pluck('updated_at')->first();
        }
        if($gst == 1.28){
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
        else if($gst == 1.18){
            $cgst = 9;
            $sgst = 9;
            $igst = 18;
        }
       else if($gst == 1.05){
            $cgst = 2.5;
            $sgst = 2.5;
            $igst = 5;
        }else if($gst == 1.12){
            $cgst = 6;
            $sgst = 6;
            $igst = 12;
        }
        else{
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
        if( $request->manu_id != null){
        $manu = Manufacturer::where('id',$request->manu_id)->first()->getOriginal();  
        $mprocurement = Mprocurement_Details::where('manu_id',$request->manu_id)->first()->getOriginal();
            }
            else{
                 $mprocurement = "";
                $manu = "";
            }
        
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'price'=>$price,
             'manu'=>$manu,
            'mprocurement'=>$mprocurement,
            'e_way_no'=>$e_way_no,
            'payment'=>$payment,
            'cgst'=>$cgst,
            'sgst'=>$sgst,
            'igst'=>$igst,
            'invoicedate'=>$invoicedate,
            'id'=>$request->invoiceno,
            'mr'=>$request->mr
           

        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.proformaInvoice')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('TaxInvoice'.'('.$id.')'.'.pdf');
        }
    }
     function downloadmrTaxInvoice(Request $request){
        $id = MRInvoice::where('invoiceno',$request->invoiceno)->pluck('order_id')->first();
        $e_way_no = MRInvoice::where('invoiceno',$request->invoiceno)->pluck('e_way_no')->first();
        $products = DB::table('orders')->where('id',$id)->first();
        $address = SiteAddress::where('project_id',$products->project_id)->first();
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $payment = PaymentDetails::where('order_id',$id)->first();
        $price = MRInvoice::where('order_id',$id)->orderby('created_at','DESC')->first()->getOriginal();
        $gst = MRInvoice::where('order_id',$id)->pluck('gstpercent')->first();
        $payment = paymentDetails::where('order_id',$id)->first();
        $invoicedate = MRInvoice::where('invoiceno',$request->invoiceno)->pluck('invoicedate')->first();
        if($invoicedate == null){
            $invoicedate = MRInvoice::where('order_id',$id)->pluck('updated_at')->first();
        }
        if($gst == 1.28){
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
        else if($gst == 1.18){
            $cgst = 9;
            $sgst = 9;
            $igst = 18;
        }
       else if($gst == 1.05){
            $cgst = 2.5;
            $sgst = 2.5;
            $igst = 5;
        }
        else{
            $cgst = 14;
            $sgst = 14;
            $igst = 28;
        }
        if( $request->manu_id != null){
        $manu = Manufacturer::where('id',$request->manu_id)->first()->getOriginal();  
        $mprocurement = Mprocurement_Details::where('manu_id',$request->manu_id)->first()->getOriginal();
            }
            else{
                 $mprocurement = "";
                $manu = "";
            }
        
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'price'=>$price,
             'manu'=>$manu,
            'mprocurement'=>$mprocurement,
            'e_way_no'=>$e_way_no,
            'payment'=>$payment,
            'cgst'=>$cgst,
            'sgst'=>$sgst,
            'igst'=>$igst,
            'invoicedate'=>$invoicedate,
            'id'=>$request->invoiceno,
            'mr'=>$request->mr
           

        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.mrproformaInvoice')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('TaxInvoice'.'('.$id.')'.'.pdf');
        }
    }
function downloadTaxInvoice1(Request $request){
        $id = MultipleInvoice::where('id',$request->id)->where('invoiceno',$request->invoiceno)->pluck('order_id')->first();
            
        $products = MultipleInvoice::where('id',$request->id)->first();
        $projectid = DB::table('orders')->where('id',$id)->pluck('project_id')->first();

        $address = SiteAddress::where('project_id',$projectid)->first();
        $procurement = ProcurementDetails::where('project_id',$projectid)->first();
        
        $payment = PaymentDetails::where('order_id',$id)->first();
        $invoicedate = Supplierdetails::where('order_id',$id)->pluck('updated_at')->first();
      
        if(count($invoicedate) == 0){
            $invoicedate = MultipleInvoice::where('order_id',$id)->pluck('updated_at')->first();
        }
        $price = MultipleInvoice::where('id',$request->id)->orderby('created_at','DESC')->first();
        $m = DB::table('orders')->where('id',$id)->pluck('manu_id')->first();
         if($m != null){
        $manu = Manufacturer::where('id',$m)->first();
        $mprocurement = Mprocurement_Details::where('manu_id',$m)->first();
            }
            else{
                $mprocurement = "";
                $manu = "";
            }
          
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'price'=>$price,
            'manu'=>$manu,
            'mprocurement'=>$mprocurement,           
            
            'invoicedate'=>$invoicedate,
            'id'=>$request->invoiceno,
            'mr'=>$request->mr
        );
         
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.taxinvoice1')->setPaper('a3','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('taxinvoice1'.'('.$id.')'.'.pdf');
        }
    }



    function downloadSupplierInvoice(Request $request){



        $products = DB::table('orders')->where('id',$request->id)->first();
         if( $products->project_id != null){
        $address = SiteAddress::where('project_id',$products->project_id)->first();
            }
            else{
                $address = "";
            }
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $payment = PaymentDetails::where('order_id',$request->id)->first();
        $sp = Supplierdetails::where('order_id',$request->id)->pluck('id')->first();
        $supplier = Supplierdetails::where('id',$sp)->first()->getOriginal();
        $invoice = SupplierInvoice::where('order_id',$request->id)->first();
        if( $request->mid != null){
                $manu = Manufacturer::where('id',$request->mid)->first()->getOriginal();
                $mprocurement = Manufacturer::where('id',$request->mid)->first()->getOriginal();
            }
        else{
                $manu = "";
                $mprocurement = "";
        }
            
        $suppliername = Supplierdetails::where('order_id',$request->id)->pluck('supplier_name')->first();
        $supplierimage = brand::where('brand',$suppliername)->pluck('brandimage')->first();
        
        $invoiceimage = SupplierInvoice::where('order_id',$request->id)->pluck('file1')->first();

        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'manu'=>$manu,
            'supplier'=>$supplier,
            'supplierimage'=>$supplierimage,
            'invoiceimage'=>$invoiceimage,
            'invoice'=>$invoice,
            'mprocurement'=>$mprocurement
        );

        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.supplierinvoice')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('supplier.pdf');
        }
    }



function downloadcustomerledger(Request $request){


       $number = $request->Number;
       $from = $request->from;
       $to = $request->to;
       
      $bal =BankTransactions::where('number',$number)->pluck('bal')->last(); 
       $invoice = BankTransactions::where('number',$number)->where('created_at','>=',$from)->where('created_at','<=',$to)->get();
       $name = CustomerDetails::where('mobile_num',$number)->pluck('first_name')->first();

    
       

       $data = array(
            'invoice'=>$invoice,
            'bal'=>$bal,
            'name'=>$name,
            'from'=>$from,
            'to'=>$to,
            'number'=>$number
            
        );

       view()->share('data',$data);
        $pdf = PDF::loadView('pdf.customerlegerpdf')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('customerleger.pdf');
        }

}


function downloadpurchaseOrder(Request $request){
         
         
    $id = $request->id;
    $products = DB::table('orders')->where('id',$request->id)->first();
     if( $products->project_id != null){
    $address = SiteAddress::where('project_id',$products->project_id)->first();
        }
        else{
            $address = "";
        }
    $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
    $payment = PaymentDetails::where('order_id',$request->id)->first();
    $sp = Supplierdetails::where('order_id',$request->id)->pluck('id')->first();
    $supplier = Supplierdetails::where('id',$sp)->first()->getOriginal();
   
    if( $request->mid != null){
            $manu = Manufacturer::where('id',$request->mid)->first()->getOriginal();
            $mprocurement = Mprocurement_Details::where('manu_id',$request->mid)->first()->getOriginal();
        }
        else{  
            $manu = "";
            $mprocurement = "";
        }
   
    $data = array(
        'products'=>$products,
        'address'=>$address,
        'procurement'=>$procurement,
        'payment'=>$payment,
        'manu'=>$manu,
        'supplier'=>$supplier,
        'mprocurement'=>$mprocurement,
        'mr'=>$request->mr
    );
  
    view()->share('data',$data);
    $pdf = PDF::loadView('pdf.purchaseOrder')->setPaper('a4','portrait');
    if($request->has('download')){
        return $pdf->download(time().'.pdf');
    }else{
        return $pdf->stream('PurchaseOrder'.'('.$id.')'.'.pdf');
    }
}

function mrdownloadpurchaseOrder(Request $request){
         
         
        $id = $request->id;
        $products = DB::table('orders')->where('id',$request->id)->first();
         if( $products->project_id != null){
        $address = SiteAddress::where('project_id',$products->project_id)->first();
            }
            else{
                $address = "";
            }
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $payment = PaymentDetails::where('order_id',$request->id)->first();
        $sp =MRSupplierdetails::where('order_id',$request->id)->pluck('id')->first();
        $supplier = MRSupplierdetails::where('id',$sp)->first()->getOriginal();
       
        if( $request->mid != null){
                $manu = Manufacturer::where('id',$request->mid)->first()->getOriginal();
                $mprocurement = Mowner_Deatils::where('manu_id',$request->mid)->first()->getOriginal();
            }
            else{
                $manu = "";
                $mprocurement = "";
            }
       
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'manu'=>$manu,
            'supplier'=>$supplier,
            'mprocurement'=>$mprocurement,
            'mr'=>$request->mr
        );
       
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.mrpurchaseOrder')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('PurchaseOrder'.'('.$id.')'.'.pdf');
        }
    }

 

   function downloadpurchaseOrder1(Request $request){
         
         
        $id = $request->id;
        $products = DB::table('orders')->where('id',$request->id)->first();
         if( $products->project_id != null){
        $address = SiteAddress::where('project_id',$products->project_id)->first();
            }
            else{
                $address = "";
            }
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $payment = PaymentDetails::where('order_id',$request->id)->first();
        $sp = MultipleSupplierInvoice::where('order_id',$request->id)->pluck('id')->first();
        $supplier = MultipleSupplierInvoice::where('id',$sp)->first()->getOriginal();
       
        if( $request->mid != null){
                $manu = Manufacturer::where('id',$request->mid)->first()->getOriginal();
                $mprocurement = Mowner_Deatils::where('manu_id',$request->mid)->first()->getOriginal();
            }
            else{
                $manu = "";
                $mprocurement = "";
            }
       
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement,
            'payment'=>$payment,
            'manu'=>$manu,
            'supplier'=>$supplier,
            'mprocurement'=>$mprocurement,
            'mr'=>$request->mr
        );
      
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.purchaseOrder1')->setPaper('a3','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('PurchaseOrder'.'('.$id.')'.'.pdf');
        }
    }

 




    public function savePaymentDetails(Request $request)
    {


          if(count($request->branchname) > 0){
             $f = $request->branchname;
          }else{
             $f = $request->accname;

          }
         $ledger = new Ledger;
         $ledger->order_id = $request->id;
         $ledger->amount = $request->totalamount;
         $ledger->payment_mode = $request->method;
         $ledger->debitcredit = $request->totalamount;
         $ledger->bank =$request->bankname;
         $ledger->branch = $f;
         $ledger->val_date = $request->date;
         $ledger->remark = $request->notes;
         $ledger->save();
        $totalRequests = count($request->payment_slip);
        $category =  Order::where('id',$request->id)->pluck('main_category')->first();
        $i = 0;
        $paymentimage ="";
            if($request->payment_slip){
                foreach($request->payment_slip as $pimage){

             $imageName3 = $pimage;
             $imageFileName = time() . '.' . $imageName3->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/payment_details/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName3), 'public'); 
                

                     if($i == 0){
                        $paymentimage .= $imageFileName;
                     }
                     else{
                            $paymentimage .= ",".$imageFileName;
                     }
                     $i++;
                }
            }
            $rtgsimage ="";
            if($request->rtgs_file){
                foreach($request->rtgs_file as $pimage){

             $imageName3 = $pimage;
             $imageFileName = time() . '.' . $imageName3->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/rtgs_files/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName3), 'public'); 
                     
                     if($i == 0){
                        $rtgsimage .= $imageFileName;
                     }
                     else{
                            $rtgsimage .= ",".$imageFileName;
                     }
                     $i++;
                }
            }
            $cashimage ="";
            if($request->cash_image){
                foreach($request->cash_image as $cimage){

             $imageName4 = $cimage;
             $imageFileName = time() . '.' . $imageName4->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/cash_images/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName4), 'public'); 

                     
                     if($i == 0){
                        $cashimage .= $imageFileName;
                     }
                     else{
                            $cashimage .= ",".$imageFileName;
                     }
                     $i++;
                }
            }
        $check = PaymentDetails::where('order_id',$request->id)->count();
        if($check == 0){
        $order = Order::where('id',$request->id)->first();
        $order->payment_status = "Payment Received";
        $order->payment_mode = $request->method;
        $order->save();
        if($request->method == "CASH"){
               
                    $paymentDetails = new PaymentDetails;
                    $paymentDetails->order_id = $request->id;
                    $paymentDetails->payment_mode = $request->method;
                    $paymentDetails->date = $request->date;
                    $paymentDetails->Totalamount = $request->totalamount;
                    $paymentDetails->damount = $request->damount;
                    $paymentDetails->file = $paymentimage;
                    $paymentDetails->payment_note = $request->notes;
                    $paymentDetails->bank_name =$request->bankname;
                    $paymentDetails->branch_name = $request->branchname;
                    $paymentDetails->category = $category;
                    $paymentDetails->project_id = $request->pid;
                    $paymentDetails->manu_id = $request->mid;
                    $paymentDetails->save();
            }
            else if($request->method == "RTGS"){
                    $paymentDetails = new PaymentDetails;
                    $paymentDetails->order_id = $request->id;
                    $paymentDetails->payment_mode = $request->method;
                    $paymentDetails->account_number = $request->accnum;
                    $paymentDetails->branch_name =$request->accname;
                    $paymentDetails->date =$request->date;
                    $paymentDetails->Totalamount = $request->totalamount;
                    $paymentDetails->damount = $request->damount;
                    $paymentDetails->file = "";
                    $paymentDetails->payment_note = $request->notes;
                    $paymentDetails->category = $category;
                    $paymentDetails->project_id = $request->pid;
                    $paymentDetails->manu_id = $request->mid;
                    $paymentDetails->rtgs_file = $rtgsimage;
                    $paymentDetails->save();
            }
            else if($request->method == "CHEQUE"){

             $imageName1 = $request->file('cheque_image');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/cheque_images/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public'); 


                
                $paymentDetails = new PaymentDetails;
                $paymentDetails->order_id = $request->id;
                $paymentDetails->payment_mode = $request->method;
                $paymentDetails->cheque_number =$request->cheque_num;
                $paymentDetails->date =$request->date;
                $paymentDetails->Totalamount = $request->totalamount;
                $paymentDetails->damount = $request->damount;
                $paymentDetails->file = "";
                $paymentDetails->payment_note = $request->notes;
                $paymentDetails->bank_name =$request->bankname;
                $paymentDetails->branch_name = $request->branchname;
                $paymentDetails->category = $category;
                $paymentDetails->project_id = $request->pid;
                $paymentDetails->manu_id = $request->mid;
                
                 $paymentDetails->cheque_image=$imageFileName;
                $paymentDetails->save();
            }
            else{
                $paymentDetails = new PaymentDetails;
                $paymentDetails->order_id = $request->id;
                $paymentDetails->payment_mode = $request->method;
                $paymentDetails->cash_holder = $request->name;
                $paymentDetails->date =$request->date;
                $paymentDetails->damount = $request->damount;
                $paymentDetails->payment_note = $request->notes;
                $paymentDetails->Totalamount = $request->totalamount;
                $paymentDetails->category = $category;
                $paymentDetails->project_id = $request->pid;
                $paymentDetails->manu_id = $request->mid;
                $paymentDetails->cash_image = $cashimage;
                $paymentDetails->save();
            }
                    if($request->total != null){
                       $denom = new Denomination;
                       $denom->order_id = $request->id;
                       $denom->x2000 = $request->INR2000;
                       $denom->x500 = $request->INR500;
                       $denom->x200 = $request->INR100;
                       $denom->x50 = $request->INR50;
                       $denom->x20 = $request->INR20;
                       $denom->x10 = $request->INR10;
                       $denom->x5 = $request->INR5;
                       $denom->x2 = $request->INR2;
                       $denom->x1 = $request->INR1;
                       $denom->x2000 = $request->INR2000;
                       $denom->total = $request->total;
                       $denom->save();
                    }
        }
        else{
        $payment = Order::where('id',$request->id)->pluck('payment_mode')->first();
        $payment .= ", ".$request->method;

        $order = Order::where('id',$request->id)->first();
        $order->payment_mode = $payment;
        $order->save();
                if($request->method == "CASH"){
               dd();
                    $paymentDetails = new PaymentHistory;
                    $paymentDetails->order_id = $request->id;
                    $paymentDetails->payment_mode = $request->method;
                    $paymentDetails->date = $request->date;
                    $paymentDetails->Totalamount = $request->totalamount;
                    $paymentDetails->damount = $request->damount;
                    $paymentDetails->file = $paymentimage;
                    $paymentDetails->payment_note = $request->notes;
                    $paymentDetails->bank_name =$request->bankname;
                    $paymentDetails->branch_name = $request->branchname;
    
                    $paymentDetails->save();
            }
            else if($request->method == "RTGS"){
                    $paymentDetails = new PaymentHistory;
                    $paymentDetails->order_id = $request->id;
                    $paymentDetails->payment_mode = $request->method;
                    $paymentDetails->account_number = $request->accnum;
                    $paymentDetails->branch_name =$request->accname;
                    $paymentDetails->date =$request->date;
                    $paymentDetails->Totalamount = $request->totalamount;
                    $paymentDetails->damount = $request->damount;
                    $paymentDetails->file = "";
                    $paymentDetails->payment_note = $request->notes;
    
                    $paymentDetails->rtgs_file = $rtgsimage;
                    $paymentDetails->save();
            }
            else if($request->method == "CHEQUE"){
                    
             $imageName1 = $request->file('cheque_image');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/cheque_images/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public'); 




               
                $paymentDetails = new PaymentHistory;
                $paymentDetails->order_id = $request->id;
                $paymentDetails->payment_mode = $request->method;
                $paymentDetails->cheque_number =$request->cheque_num;
                $paymentDetails->date =$request->date;
                $paymentDetails->Totalamount = $request->totalamount;
                $paymentDetails->damount = $request->damount;
                $paymentDetails->file = "";
                $paymentDetails->payment_note = $request->notes;
                $paymentDetails->bank_name =$request->bankname;
                $paymentDetails->branch_name = $request->branchname;
                $paymentDetails->cheque_image=$imageFileName;
                 

                $paymentDetails->save();
            }
            else{
            
                $paymentDetails = new PaymentHistory;
                $paymentDetails->order_id = $request->id;
                $paymentDetails->payment_mode = $request->method;
                $paymentDetails->cash_holder = $request->name;
                $paymentDetails->date =$request->date;
                $paymentDetails->damount = $request->damount;
                $paymentDetails->payment_note = $request->notes;
                $paymentDetails->Totalamount = $request->totalamount;
                $paymentDetails->cash_image = $cashimage;
                $paymentDetails->save();
            }
            if($request->total != null){
                       $denom = new Denomination;
                       $denom->order_id = $request->id;
                       $denom->multiple_pay = "yes";
                       $denom->x2000 = $request->INR2000;
                       $denom->x500 = $request->INR500;
                       $denom->x200 = $request->INR100;
                       $denom->x50 = $request->INR50;
                       $denom->x20 = $request->INR20;
                       $denom->x10 = $request->INR10;
                       $denom->x5 = $request->INR5;
                       $denom->x2 = $request->INR2;
                       $denom->x1 = $request->INR1;
                       $denom->x2000 = $request->INR2000;
                       $denom->total = $request->total;
                       $denom->save();
                    }
        }
          
          $eqid = Order::where('id',$request->id)->pluck('req_id')->first();

           $req = Requirement::where('id',$eqid)->first();
         


          PaymentDetails::where('order_id',$request->id)->update([
            'quantity'=>$req->total_quantity,
            'mamahome_price'=>$req->price,
            'unit'=>$req->measurement_unit
        ]);
        $cat = Order::where('id',$request->id)->pluck('main_category')->first();
        $projectid = Order::where('id',$request->id)->pluck('project_id')->first();
        $cgstval = Gst::where('category',$cat)->where('state',$req->spstate)->pluck('cgst')->first();
        $sgstval = Gst::where('category',$cat)->where('state',$req->spstate)->pluck('sgst')->first();
        $igstval =  Gst::where('category',$cat)->where('state',$req->spstate)->pluck('igst')->first();
        if($cgstval == 14 || $igstval == 28){
            $percent = 1.28;
            $g1 = 14;
            $g2 = 14;
        }
        else if($cgstval == 2.5 || $igstval == 5){
            $percent = 1.05;
            $g1 = 2.5;
            $g2 = 2.5;
        }
        else if($cgstval == 9 || $igstval == 18){
            $percent = 1.18;
            $g1 = 9;
            $g2 = 9;
        }
        else{
            $cgstval = 14;
            $sgstval = 14;
            $percent = 1.28;
            $g1 = 14;
            $g2 = 14;
        }
       
        $unitwithgst = ($req->price/$percent);
        $totalamount = ($req->total_quantity *  $unitwithgst);
        $x = (int)$totalamount;
        if($igstval != null){
        $cgst = 0;
        $sgst = 0;
        $t1 = ($totalamount * $g1)/100;
        $t2 =  ($totalamount * $g2)/100;
        $igst = ($t1 +  $t2);
        }
        else{
            $cgst = ($totalamount * $g1)/100;
            $sgst =  ($totalamount * $g2)/100;
            $igst = 0;
        }
        $tt = $cgst + $sgst;
        $totaltax = (int)$tt;
        $igstint = (int)$igst;
        $withgst = $cgst + $sgst + $totalamount + $igst;
        $y = (int)$withgst;
        $check = MamahomePrice::where('order_id',$request->id)->first();
        if(count($check) == 0){
                $invoice = new MamahomePrice;
                $invoice->req_id = $req->id;
                $invoice->order_id = $request->id;
                $invoice->quantity = $req->total_quantity;
                $invoice->mamahome_price = $req->price;
                $invoice->unitwithoutgst = $unitwithgst;
                $invoice->totalamount = $x;
                $invoice->cgst = $cgst;
                $invoice->sgst = $sgst;
                $invoice->igst = $igstint;
                $invoice->totaltax = $totaltax;
                $invoice->amountwithgst = $y;    
                $invoice->cgstpercent = $cgstval;
                $invoice->sgstpercent = $sgstval;
                $invoice->gstpercent = $percent;
                $invoice->igstpercent = $igstval;
                $invoice->unit = $request->unit;
                $invoice->category = $cat;
                $invoice->project_id = $projectid;
                $invoice->state = $req->spstate;
                $invoice->save();
                // generate invoice
                $year = date('Y');
                $country_code = Country::pluck('country_code')->first();
                $zone = Zone::pluck('zone_number')->first();
                $invoiceno = "MH".$country_code."".$zone."".$year."IN".$invoice->id;
                 $ino = MamahomePrice::where('order_id',$request->id)->first();
                // $ino = MamahomePrice::where('order_id',$id)->update([
                //     'invoiceno'=>$invoiceno
                // ]);
        }
        else{
                $check->quantity = $req->total_quantity;
                $check->mamahome_price = $req->price;
                $check->unitwithoutgst = $unitwithgst;
                $check->totalamount = $x;
                $check->cgst = $cgst;
                $check->sgst = $sgst;
                $check->igst = $igstint;
                $check->totaltax = $totaltax;
                $check->amountwithgst = $y;    
                $check->cgstpercent = $cgstval;
                $check->sgstpercent = $sgstval;
                $check->gstpercent = $percent;
                $check->igstpercent = $igstval;
                $check->unit = $req->measurement_unit;
                $check->category = $cat;
                $check->project_id = $projectid;
                $check->state = $req->spstate;
                $check->save();
        }
 




        return back()->with('Success','Payment Details Saved Successfully');
    }
    public function getFinanceAttendance()
    {
        return view('finance.attendance');
    }
    public function getViewProformaInvoice(Request $request)
    {
        $products = DB::table('orders')->where('id',$request->id)->first();
        $address = SiteAddress::where('project_id',$products->project_id)->first();
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.proformaInvoice')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('pdf.proformaInvoice');
        }
    }
    public function getViewPurchaseOrder(Request $request)
    {
        $products = DB::table('orders')->where('id',$request->id)->first();
        $address = SiteAddress::where('project_id',$products->project_id)->first();
        $procurement = ProcurementDetails::where('project_id',$products->project_id)->first();
        $data = array(
            'products'=>$products,
            'address'=>$address,
            'procurement'=>$procurement
        );
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.purchaseOrder')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('pdf.purchaseOrder');
        }
    }
    public function sendMessage(Request $request)
    {
        $message = New Message;
        $message->from_user = Auth::user()->id;
        $message->to_user = $request->orderId;
        $message->body = $request->message;
        $message->save();
        return back()->with('info','Submited successfully !');
    }
    // public function confirmpayment(Request $request){    
    //     Order::where('id',$request->id)->update([
    //         'final_payment'=>"Received"
    //     ]);
    //     return back()->with('Success','Payment Received Successfully');
    // }
    public function paymentmode(Request $request){
        $users = User::where('department_id','!=',10)->get();
        $quan = Requirement::where('id',$request->reqid)->pluck('total_quantity')->first();
        $price = Requirement::where('id',$request->reqid)->pluck('price')->first();
          if($price == null){
                $total = null;
          }
          else{
                $total = $quan * $price;
          }
        $payments = PaymentDetails::where('order_id',$request->id)->first();
        $payhistory = PaymentHistory::where('order_id',$request->id)->get();
       return view('finance.payment',['id'=>$request->id,'users'=>$users,'mid'=>$request->mid,'pid'=>$request->pid,'total'=>$total,'payments'=>$payments,'payhistory'=>$payhistory]);
    }
         public function saveunitprice(Request $request){
       
        // invoice
        
         // roundoff
        
              if($request->status){
             $mode = implode(",",$request->status);
           }else{
             $mode = "";
           }
                
             $ppp = MamahomePrice::where('order_id',$request->id)->pluck('invoiceno')->first();

             
                 
                if($ppp == null){

                

                // generate invoice
                 
                   $val =MamahomePrice::max('final');
                   
                   $val2 = MultipleInvoice::max('final');

                   if($val > $val2){
                     $val = $val;
                   }else{
                     $val = $val2;
                   }

                $i = intval($val);
                $z = $i+1;

                $year = date('Y');
                $country_code = Country::pluck('country_code')->first();
                $zone = Zone::pluck('zone_number')->first();
                $invoiceno = "MH".$country_code."".$zone."".$year."IN".$z;
                                $ino = MamahomePrice::where('order_id',$request->id)->update([
                                    'invoiceno'=>$invoiceno,
                                    'final'=>$z,
                                    'invoicedate'=>date('Y-m-d')
                                ]);
                     
 
                }





         $yadav =DB::table('supplierdetails')->where('order_id',$request->id)->pluck('lpo')->first();
         $lpoamount = Supplierdetails::where('lpo',$yadav)->pluck('totalamount')->first();
         $create = Supplierdetails::where('lpo',$yadav)->pluck('created_at')->first();
         $check = Ledger::where('lpo_no',$yadav)->first();
         $e_way_no=$request->e_way_no;

           if($request->invoicedate == null){
             $invoicedate =$create; 
           }else{

         $invoicedate=$request->invoicedate;
           }

         
         
         if(count($check) == 0){
           $lpodata = new Ledger;
           $lpodata->val_date = $create;
           $lpodata->amount = $lpoamount;
           $lpodata->credit = $lpoamount;
           $lpodata->order_id = $request->id;
           $lpodata->lpo_no = $yadav;
           $lpodata->save();  
         }
         else
         {
           $check->val_date = $create;
           $check->amount = $lpoamount;
           $check->credit =$lpoamount;
           $check->order_id = $request->id;
           $check->lpo_no = $yadav;
           $check->save();  

         }
          


   
 
          $yadav =DB::table('supplierdetails')->where('order_id',$request->id)->pluck('lpo')->first();
          $lpoamount = Supplierdetails::where('lpo',$yadav)->pluck('totalamount')->first();
          $create = Supplierdetails::where('lpo',$yadav)->pluck('created_at')->first();
          $check = Ledger::where('lpo_no',$yadav)->first();
          $e_way_no=$request->e_way_no;
          $invoicedate=$request->invoicedate;
          
          if(count($check) == 0){
            $lpodata = new Ledger;
            $lpodata->val_date = $create;
            $lpodata->amount = $lpoamount;
            $lpodata->credit = $lpoamount;
            $lpodata->order_id = $request->id;
            $lpodata->lpo_no = $yadav;
            $lpodata->save();  
          }
          else
          {
            $check->val_date = $create;
            $check->amount = $lpoamount;
            $check->credit =$lpoamount;
            $check->order_id = $request->id;
            $check->lpo_no = $yadav;
            $check->save();  
 
          }
           
         $unitwithoutgst = $request->unitwithoutgst;
         $cgst = round($request->cgst,2);
         $sgst = round($request->sgst,2);
         $igst = round($request->igst,2);
    
         $number = $request->tamount;
         $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number.'&token=fshadvjfa67581232';
         $response = file_get_contents($url);
         $data = json_decode($response,true);
         $dtow = $data['message'];
 
         $number1 = $request->totaltax;
         $url1 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number1.'&token=fshadvjfa67581232';
         $response1 = file_get_contents($url1);
         $data1 = json_decode($response1,true);
         $dtow1 = $data1['message'];
 
         $number2 = $request->gstamount;
         $url2 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number2.'&token=fshadvjfa67581232';
         $response2 = file_get_contents($url2);
         $data2 = json_decode($response2,true);
         $dtow2 = $data2['message'];
 
         $number3 = $igst;
         $url3 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number3.'&token=fshadvjfa67581232';
         $response3 = file_get_contents($url3);
         $data3 = json_decode($response3,true);
         $dtow3 = $data3['message'];
 
          
         $price = MamahomePrice::where('order_id',$request->id)->first();
         $price->unit = $request->unit;
         $price->mamahome_price = $request->price;
         $price->unitwithoutgst = round($unitwithoutgst,2);
         $price->totalamount = round($request->tamount,2);
         $price->cgst = round($cgst,2);
         $price->sgst = round($sgst,2);
         $price->igst = round($igst,2);
         $price->totaltax = round($request->totaltax,2);
         $price->amountwithgst = round($request->gstamount,2);
         $price->amount_word = $dtow;
         $price->tax_word = $dtow1;
         $price->gstamount_word =  $dtow2;
         $price->igsttax_word = $dtow3;
         $price->quantity = $request->quantity;
         $price->manu_id = $request->manu_id;
         $price->description = $request->desc;
         $price->billaddress = $request->bill;
         $price->shipaddress = $request->ship;
         $price->updated_by = Auth::user()->id;
         $price->cgstpercent = $request->g1;
         $price->sgstpercent = $request->g2;
         $price->gstpercent = $request->g3;
         $price->igstpercent = $request->i1;
         $price->edited = "No";
         $price->e_way_no=$e_way_no;
         $price->invoicedate=$invoicedate;
         $price->customer_gst = $request->customergst;
         $price->customer_name = $request->customer_name;
         $price->HSN = $request->HSN;
         $price->payment_mode = $mode;
	 $price->sales_person_name = $request->sales_person;
         $price->save();
         
        
        
        $order = Order::where('id',$request->id)->first();
        $order->confirm_payment = "Received";
        $order->save();

         PaymentDetails::where('order_id',$request->id)->update([
            'status'=>"Received"
        ]);
        
        return back()->with('Success','Invoice Generated');
    }


public function multidata(request $request){

        $unitwithoutgst = $request->unitwithoutgst;
        $cgst = round($request->cgst,2);
        $sgst = round($request->sgst,2);
        $igst = round($request->igst,2);

        $number = $request->tamount;
        $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number.'&token=fshadvjfa67581232';
        $response = file_get_contents($url);
        $data = json_decode($response,true);
        $dtow = $data['message'];

        $number1 = $request->totaltax;
        $url1 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number1.'&token=fshadvjfa67581232';
        $response1 = file_get_contents($url1);
        $data1 = json_decode($response1,true);
        $dtow1 = $data1['message'];

        $number2 = $request->gstamount;
        $url2 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number2.'&token=fshadvjfa67581232';
        $response2 = file_get_contents($url2);
        $data2 = json_decode($response2,true);
        $dtow2 = $data2['message'];

        $number3 = $igst;
        $url3 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number3.'&token=fshadvjfa67581232';
        $response3 = file_get_contents($url3);
        $data3 = json_decode($response3,true);
        $dtow3 = $data3['message'];
         $catename = Category::where('id',$request->cat)->pluck('category_name')->first();
      
        $price = new MultipleInvoice;
        $price->unit = $request->unit;
        $price->mamahome_price = $request->price;
        $price->unitwithoutgst = $unitwithoutgst;
        $price->totalamount = $request->tamount;
        $price->cgst = $cgst;
        $price->sgst = $sgst;
        $price->igst = $igst;
        $price->totaltax = $request->totaltax;
        $price->amountwithgst = $request->gstamount;
        $price->amount_word = $dtow;
        $price->tax_word = $dtow1;
        $price->gstamount_word =  $dtow2;
        $price->igsttax_word = $dtow3;
        $price->quantity = $request->quantity;
        $price->manu_id = $request->manu_id;
        $price->description = $catename;
        $price->billaddress = $request->bill;
        $price->shipaddress = $request->ship;
        $price->updated_by = Auth::user()->id;
        $price->cgstpercent = $request->g1;
        $price->sgstpercent = $request->g2;
        $price->gstpercent = $request->g3;
        $price->igstpercent = $request->i1;
        $price->edited = "No";
        $price->customer_gst = $request->customergst;
        $price->order_id = $request->order_id;
        $price->project_id = $request->project_id;
        $price->category = $request->cat;
        $price->brand = $request->brand;
        $price->subcat = $request->subcat;
        $price->save();
             $val =MamahomePrice::max('final');
                   
                   $val2 = MultipleInvoice::max('final');

                   if($val > $val2){
                     $val = $val;
                   }else{
                     $val = $val2;
                   }

                $i = intval($val);
                $z = $i+1;
         $year = date('Y');
                $country_code = Country::pluck('country_code')->first();
                $zone = Zone::pluck('zone_number')->first();
                $invoiceno = "MH".$country_code."".$zone."".$year."IN".$z;
                $ino = MultipleInvoice::where('id',$price->id)->update([
                    'invoiceno'=>$invoiceno
                ]);
  
   return back();
            

}


    public function resetpo(Request $request)
    {
        $check = supplierdetails::where('order_id',$request->resetid)->update([

'gst'  =>null,
'category' =>null,
'description' =>null,
'quantity' =>null,
'unit' =>null,
'unit_price' =>null,
'amount' =>null,
'amount_words' =>null,
'totalamount' =>null,
'tamount_words' =>null,
'unitwithoutgst' =>null,
'address' =>null,
'ship' =>null,
'cgstpercent' =>null,
'sgstpercent' =>null,
'gstpercent' =>null,
'igstpercent' =>null,
'state' =>null,
        ]);

        $order = Order::where('id',$request->resetid)->update([
            'purchase_order'=>null
        ]);
        return back();

    }
    public function resetinvoice(Request $request)
    {
         $check = MamahomePrice::where('order_id',$request->resetid)->update([
           'quantity' => null,
               'mamahome_price' =>null,
               'unitwithoutgst' =>null,
               'totalamount' =>null, 
               'cgst' =>null, 
               'sgst' =>null, 
               'igst' =>null, 
               'totaltax' =>null,
               'amountwithgst' =>null,  
               'cgstpercent' =>null,
               'sgstpercent' =>null,
               'gstpercent' =>null,
               'igstpercent' =>null,
               'unit' =>null,
               'category' =>null,
               'state' =>null,
               'amount_word'=>null,
               'tax_word'=>null,
               'gstamount_word'=>null,
               'description'=>null,
               'billaddress'=>null,
               'shipaddress'=>null
         ]);
         $x = Order::where('id',$request->resetid)->update([
            'status' => 'Enquiry Confirmed',
            'confirm_payment' => null

         ]);
         return back();
    }
    public function savesupplierdetails(Request $request){
     
      
           
        $number = $request->amount;
        $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number.'&token=fshadvjfa67581232'
;
        $response = file_get_contents($url);
        $data = json_decode($response,true);
        $dtow = $data['message'];
       

        $number1 = $request->totalamount;
        $url1 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number1.'&token=fshadvjfa67581232'
;
        $response1 = file_get_contents($url1);
        $data1 = json_decode($response1,true);
        $dtow1 = $data1['message'];
      
        $check = Supplierdetails::where('order_id',$request->id)->first();
        $po = order::where('id',$request->id)->pluck('purchase_order')->first();
        
        if(count($check) == 0 && $po == null){

        $projectid = Order::where('id',$request->id)->pluck('project_id')->first();
        $order = Order::where('id',$request->id)->update([
            'purchase_order'=>"yes"
        ]);

        $order = Order::where('id',$request->id)->update([
            'Mr'=>1
        ]);


        $year = date('Y');
        $country_initial = "O";
        $country_code = Country::pluck('country_code')->first();
        $zone = Zone::pluck('zone_number')->first();
        $sname = ManufacturerDetail::where('company_name',$request->name)->pluck('company_name')->first();
        

        $supply = New Supplierdetails;
        $supply->project_id = $projectid;
        $supply->category = $request->category;
        $supply->manu_id = $request->mid;
        $supply->address = $request->address;
        $supply->ship = $request->ship;
        $supply->order_id = $request->id;
        $supply->supplier_name = $sname;
        $supply->gst = $request->gst;
        $supply->description = $request->desc;
        $supply->quantity = $request->quantity;
        $supply->unit = $request->munit;
        $supply->unit_price = round($request->uprice,2);
        $supply->amount = round($request->amount,2);
        $supply->amount_words = $dtow;
        $supply->totalamount = round($request->totalamount,2);
        $supply->tamount_words = $dtow1;
        $supply->unitwithoutgst = round($request->unitwithoutgst,2);
        $supply->cgstpercent =round($request->cgstpercent,2);
        $supply->sgstpercent = round($request->sgstpercent,2);
        $supply->gstpercent = round($request->gstpercent,2);
        $supply->igstpercent = round($request->igstpercent,2);
        $supply->state = $request->state;
        $supply->generated_by = Auth::user()->id;
        $supply->save();
        

        // $lpoNo = "MH_".$country_code."_".$zone."_LPO_".$year."_".$supply->id; 
        // $supply =Supplierdetails::where('id',$supply->id)->update(['lpo'=>
        //     $lpoNo]);

                  $val =Supplierdetails::max('final');
                   
                   $val2 = MultipleSupplierInvoice::max('final');

                   if($val > $val2){
                     $val = $val;
                   }else{
                     $val = $val2;
                   }

                $i = intval($val);
                $z = $i+1;
                

                

                $year = date('Y');
                $country_code = Country::pluck('country_code')->first();
                $zone = Zone::pluck('zone_number')->first();
                $invoiceno = "MH_".$country_code."_".$zone."_LPO_".$year."_".$z;
                      $ino = Supplierdetails::where('id',$supply->id)->update([
                                    'lpo'=>$invoiceno,'final'=>$z
                                    
                                ]);

           

        }

        else if(count($check) == 1 && $po == null){
                $sname = ManufacturerDetail::where('company_name',$request->name)->pluck('company_name')->first();

                 
                    $check->address = $request->address;
                    $check->ship = $request->ship;
                    $check->gst = $request->gst;
                     $check->supplier_name = $sname;
                    $check->description = $request->desc;
                    $check->quantity = $request->quantity;
                    $check->unit = $request->munit;
                    $check->unit_price = round($request->uprice,2);
                    $check->amount = round($request->amount,2);
                    $check->amount_words = $dtow;
                    $check->totalamount =round($request->totalamount,2);
                    $check->tamount_words = $dtow1;
                    $check->unitwithoutgst = round($request->unitwithoutgst,2);
                    $check->cgstpercent = round($request->cgstpercent,2);
                    $check->sgstpercent = round($request->sgstpercent,2);
                    $check->gstpercent = round($request->gstpercent,2);
                    $check->igstpercent = round($request->igstpercent,2);
                    $check->state = $request->state;
                    $check->save();
                        $order = Order::where('id',$request->id)->update([
                        'purchase_order'=>"yes"
                    ]);
         }
        else{
                   $sname = ManufacturerDetail::where('company_name',$request->name)->pluck('company_name')->first();
            
                $check->address = $request->edit1;
                $check->ship = $request->edit2;  
               
                $check->gst = $request->edit3;
                $check->description = $request->edit4;
                $check->quantity = $request->edit6;
                $check->unit = $request->edit5;
                $check->unit_price = round($request->uprice,2);
                $check->amount =round($request->amount,2);
                $check->amount_words = $dtow;
                $check->totalamount = round($request->totalamount,2);
                $check->tamount_words = $dtow1;
                $check->unitwithoutgst = round($request->unitwithoutgst,2);
                $check->cgstpercent = round($request->cgstpercent,2);
                $check->sgstpercent = round($request->sgstpercent,2);
                $check->gstpercent = round($request->gstpercent,2);
                $check->igstpercent = round($request->igstpercent,2);
                $check->save();
                $order = Order::where('id',$request->resetid)->update([
                    'purchase_order'=>"yes"
                ]);
        }
     
        return back()->with('success','Submited successfully !');
    }

// --------------------------------------MR Deatils------------------------------------------------

  public function mrsavesupplierdetails(Request $request){
     
     
       
        
        $number = $request->amount1;
        $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number.'&token=fshadvjfa67581232'
;
        $response = file_get_contents($url);
        $data = json_decode($response,true);
        $dtow = $data['message'];
       

        $number1 = $request->totalamount1;
        $url1 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number1.'&token=fshadvjfa67581232'
;
        $response1 = file_get_contents($url1);
        $data1 = json_decode($response1,true);
        $dtow1 = $data1['message'];
      
        $check = MRSupplierdetails::where('order_id',$request->id)->first();

        // $po = order::where('id',$request->id)->pluck('Mr')->first();
        
       
        if(count($check) == 0 ){

        $projectid = Order::where('id',$request->id)->pluck('project_id')->first();
        $order = Order::where('id',$request->id)->update([
            'mrpurchase_order'=>"yes",'Mr'=>1
        ]);
        $year = date('Y');
        $country_initial = "O";
        $country_code = Country::pluck('country_code')->first();
        $zone = Zone::pluck('zone_number')->first();
        $sname = ManufacturerDetail::where('company_name',$request->name1)->pluck('company_name')->first();
        

        $supply = New MRSupplierdetails;
        $supply->project_id = $projectid;
        $supply->category = $request->category;
        $supply->manu_id = $request->mid;
        $supply->address = $request->address1;
        $supply->ship = $request->ship1;
        $supply->order_id = $request->id;
        $supply->supplier_name = $sname;
        $supply->gst = $request->gst1;
        $supply->description = $request->desc1;
        $supply->quantity = $request->quantity1;
        $supply->customer_name = $request->customer_name;
        $supply->unit = $request->munit1;
        $supply->unit_price = round($request->uprice1,2);
        $supply->amount = round($request->amount1,2);
        $supply->amount_words = $dtow;
        $supply->totalamount = round($request->totalamount1,2);
        $supply->tamount_words = $dtow1;
        $supply->unitwithoutgst = round($request->unitwithoutgst1,2);
        $supply->cgstpercent =round($request->cgstpercent1,2);
        $supply->sgstpercent = round($request->sgstpercent1,2);
        $supply->gstpercent = round($request->gstpercent1,2);
        $supply->igstpercent = round($request->igstpercent1,2);
        $supply->state = $request->state;
        $supply->generated_by = Auth::user()->id;
        $supply->save();
        

        // $lpoNo = "MH_".$country_code."_".$zone."_LPO_".$year."_".$supply->id; 
        // $supply =Supplierdetails::where('id',$supply->id)->update(['lpo'=>
        //     $lpoNo]);

                  $val =Supplierdetails::max('final');
                   
                   $val2 = MultipleSupplierInvoice::max('final');

                   if($val > $val2){
                     $val = $val;
                   }else{
                     $val = $val2;
                   }

                $i = intval($val);
                $z = $supply->id;
                

                

                $year = date('Y');
                $country_code = Country::pluck('country_code')->first();
                $zone = Zone::pluck('zone_number')->first();
                $invoiceno = "MR_".$country_code."_".$zone."_LPO_".$year."_".$z;
                      $ino = MRSupplierdetails::where('id',$supply->id)->update([
                                    'lpo'=>$invoiceno,'final'=>$z
                                    
                                ]);

           

        }
        else if(count($check) == 1 ){
                $sname = ManufacturerDetail::where('company_name',$request->name1)->pluck('company_name')->first();

                 
                    $check->address = $request->address1;
                    $check->ship = $request->ship1;
                    $check->gst = $request->gst1;
                     $check->supplier_name = $sname;
                    $check->description = $request->desc1;
                    $check->quantity = $request->quantity1;
                    $check->unit = $request->munit1;
                    $check->unit_price = round($request->uprice1,2);
                    $check->amount = round($request->amount1,2);
                    $check->amount_words = $dtow;
                    $check->totalamount =round($request->totalamount1,2);
                    $check->tamount_words = $dtow1;
                    $check->unitwithoutgst = round($request->unitwithoutgst1,2);
                    $check->cgstpercent = round($request->cgstpercent1,2);
                    $check->sgstpercent = round($request->sgstpercent1,2);
                    $check->gstpercent = round($request->gstpercent1,2);
                    $check->igstpercent = round($request->igstpercent1,2);
                    $check->state = $request->state;
                    $check->save();
                        $order = Order::where('id',$request->id)->update([
                        'mrpurchase_order'=>"yes"
                    ]);
         }
        else{
            
                $check->address = $request->edit11;
                $check->ship = $request->edit21;  
               
                $check->gst = $request->edit31;
                $check->description = $request->edit41;
                $check->quantity = $request->edit61;
                $check->unit = $request->edit51;
                $check->unit_price = round($request->uprice1,2);
                $check->amount =round($request->amount1,2);
                $check->amount_words = $dtow;
                $check->totalamount = round($request->totalamount1,2);
                $check->tamount_words = $dtow1;
                $check->unitwithoutgst = round($request->unitwithoutgst1,2);
                $check->cgstpercent = round($request->cgstpercent1,2);
                $check->sgstpercent = round($request->sgstpercent1,2);
                $check->gstpercent = round($request->gstpercent1,2);
                $check->igstpercent = round($request->igstpercent1,2);
                $check->save();
                $order = Order::where('id',$request->resetid)->update([
                    'mrpurchase_order'=>"yes"
                ]);
        }
     
        return back()->with('success','Submited successfully !');
    }










// --------------------------------------MR Deatils------------------------------------------------


public function getgst(Request $request){
    $res = ManufacturerDetail::where('company_name',$request->name)->pluck('registered_office')->first();
    $gst = ManufacturerDetail::where('company_name',$request->name)->pluck('gst')->first();
    $category = ManufacturerDetail::where('company_name',$request->name)->pluck('category')->first();
    $unit = Category::where('category_name',$category)->pluck('measurement_unit')->first();
    $id = $request->x;
    return response()->json(['res'=>$res,'id'=>$id,'gst'=>$gst,'category'=>$category,'unit'=>$unit]);
}
    public function supplierinvoice(Request $request){   
        $image = "";
        $i = 0;    
       if($request->file){
                foreach($request->file as $pimage){

             $imageName3 = $pimage;
             $imageFileName = time() . '.' . $imageName3->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/supplierinvoice/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName3), 'public'); 

                     if($i == 0){
                        $image .=$imageFileName;
                     }
                     else{
                            $image .= ",".$imageFileName;
                     }
                     $i++;
                }
            }

        $lpo = Supplierdetails::where('order_id',$request->id)->pluck('lpo')->first();
        $invoice = New SupplierInvoice;
        $invoice->lpo_number = $lpo;
        $invoice->order_id = $request->id;
        $invoice->invoice_number = $request->supplierinvoice;
        $invoice->invoice_date = $request->invoicedate;
        $invoice->file1 = $image;
        $invoice->project_id = $request->project_id;
        $invoice_manu_id = $request->mid;
        $invoice->save();
        $order = Order::where('id',$request->id)->first();
        $order->supplier_invoice = "yes";
        $order->save();

        return back();
    }
    public function uploadimage(Request $request){
      
    }

    public function multipleinvoice(request $request){
          
           $categories = Category::all();
           $project_id = DB::table('orders')->where('id',$request->orderid)->pluck('project_id')->first();
           $manu_id = DB::table('orders')->where('id',$request->orderid)->pluck('manu_id')->first();
           $orderid =$request->orderid;
           $projectid = $request->project_d;
           
           $manu_id = $request->manu_id;

        return view('finance.multipleinvoice',['categories'=>$categories,'project_id'=>$project_id,'manu_id'=>$manu_id,'id'=>$orderid,'projectid'=>$projectid,'manu_id'=>$manu_id]);
    }
    public function demodata(request $request){
   $categories = Category::all();
           return view('/combinepurchase',['id'=>$request->id,'project_id'=>$request->project_id,'manu_id'=>$request->manu_id,'categories'=>$categories]);
    }
}
