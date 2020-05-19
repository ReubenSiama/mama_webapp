<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\hr;
use App\MRInvoice;
use App\Interview;
use App\Transport;
use App\Materialhub;
use App\FirstRound;

use App\SecoundRound;
use App\ManufacturerDetail;
use Auth;
use App\Dedicatedsalestarget;
use App\CustomerBrands;
use App\MultipleInvoice;
use App\BankTransactions;
use DB;
use App\UpdatedReport;
use App\MultipleSupplierInvoice;
use App\loginTime;
use App\Department;
use App\Group;
use App\Pricing;
use App\Tlwards;
use App\EmployeeDetails;
use App\Mail\FieldLoginApprove;
use App\Mail\FieldLoginReject;
use App\NewCustomerAssign;
use App\PaymentDetails;
use App\PaymentHistory;
use App\brand;
use App\Category;
use App\Gst;
use App\FieldLogin;
use App\CustomerDetails;
use App\Country;
use App\Zone;
use App\CustomerOrder;
use App\CustomerProject;
use App\GstTable;
use App\CustomerInvoice;
use App\customer_delivery;
use App\SuplierDetails;
use App\SuplpierManufacture;
use App\SupplierOrder;
use App\SupplierProject;
use App\SupplierGst;
use App\SupplierInvoicedata;
use App\StatesDist;
use App\SubWardMap;
use App\WardAssignment;
use App\SubWard;
use App\Ward;
use App\WardMap;
use App\ProjectDetails;
use App\Manufacturer;
use App\Requirement;
use App\CustomerManufacturer;
use App\SiteAddress;
use App\Assigncustomlist;
use App\MamahomePrice;
use App\ProcurementDetails;
use App\User;
use App\CustomerType;
use App\GradeRange;
use App\Quotation;
use App\DeliveryDetails;
use App\AssignStage;
use App\Assignenquiry;
use App\Mprocurement_Details;
use App\MamaSms;
use App\CategoryTarget;
use App\SalesTarget;
use App\assign_manufacturers;
use App\VisitedCustomers;
use App\customerassign;
use App\Supplierdetails;
use App\Order;
use App\History;
use App\Salesofficer;
use App\ProjectUpdate;
use Spatie\Activitylog\Models\Activity;
date_default_timezone_set("Asia/Kolkata");
class NewdevlopController extends Controller
{
    
    public function enquiryassign(request $request){

            $wards = Ward::all();
           $enquiry=[];

       if($request->ward && $request->subward && $request->cat){

       	  $enquiry = Requirement::where('main_category','LIKE',$request->cat."%")->where('sub_ward_id',$request->subward)->get();
       	  
           
       	  
       }
       if($request->ward && $request->subward =="All" && $request->cat){
                
          $wa = SubWard::where('ward_id',$request->ward)->pluck('id');
       	  $enquiry = Requirement::where('main_category',$request->cat)->whereIn('sub_ward_id',$wa)->get();
       	 
         

       	   
       }
       if($request->ward =="All" && !$request->subward  && $request->cat){
               
       	  $enquiry = Requirement::where('main_category',$request->cat)->get();

       	 
           

       	  
       }
       	 
         
       
           return view('enquiryassign',['enquiry'=>$enquiry,'wards'=>$wards]);
   }

  public function Categorywisecustomers(request $request){

           $wards = Ward::all();
           $enquiry=[];
           $enquiry1 = [];
       if($request->ward && $request->subward && $request->cat){

       	  $enquiry = Requirement::where('main_category','LIKE',$request->cat."%")->where('sub_ward_id',$request->subward)->get();
       	  $enquiry1 = Requirement::where('main_category','LIKE',$request->cat."%")->where('sub_ward_id',$request->subward)->pluck('id');
           
       	  
       }
       if($request->ward && $request->subward =="All" && $request->cat){
                
          $wa = SubWard::where('ward_id',$request->ward)->pluck('id');
       	  $enquiry = Requirement::where('main_category',$request->cat)->whereIn('sub_ward_id',$wa)->get();
       	  $enquiry1 = Requirement::where('main_category',$request->cat)->whereIn('sub_ward_id',$wa)->pluck('id');
         

       	   
       }
       if($request->ward =="All" && !$request->subward  && $request->cat){
               
       	  $enquiry = Requirement::where('main_category',$request->cat)->get();

       	  $enquiry1 = Requirement::where('main_category',$request->cat)->pluck('id');
           

       	  
       }

       	  $e = Requirement::whereIn('id',$enquiry1)->pluck('project_id');
       	  $e1 = Requirement::whereIn('id',$enquiry1)->pluck('manu_id');

       	   $pame =ProcurementDetails::whereIn('project_id',$e)->pluck('procurement_contact_no')->unique();
       	  
       	   $pame1 =Mprocurement_Details::whereIn('manu_id',$e1)->pluck('contact')->unique();

           $pname = CustomerDetails::whereIn('mobile_num',$pame)->orWhereIn('mobile_num',$pame1)->pluck('mobile_num')->unique(); 
          
          $new = [];

      

       foreach ($pname as $projects) {
         $procname = ProcurementDetails::where('procurement_contact_no',$projects)->pluck('procurement_name')->first();
          $procnumber = ProcurementDetails::where('procurement_contact_no',$projects)->pluck('procurement_contact_no')->first();
          $procnumber1 =Mprocurement_Details::where('contact',$projects)->pluck('contact')->first();
          $s = ProcurementDetails::where('procurement_contact_no',$projects)->pluck('project_id');
          $s1 = Mprocurement_Details::where('contact',$projects)->pluck('manu_id');
          
          $full = Requirement::whereIn('project_id',$s)->orWhereIn('manu_id',$s1)->get();
          

       array_push($new,["procname"=>$procname,'full'=>$full,'procnumber'=>$procnumber]);
       }     
      $yup = [];

           for($i=0;$i<sizeof($new);$i++){

              $y = count($new[$i]['full']);
              array_push($yup,$y);
           }
          $ms = array_sum($yup);

      
         // Get current page form url e.x. &page=1
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
 
        // Create a new Laravel collection from the array data
        $itemCollection = collect($new);
 
        // Define how many items we want to be visible in each page
        $perPage =50;
 
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
 
        // Create our paginator and pass it to the view
        $paginatedItems= new \Illuminate\Pagination\LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
 
        // set url path for generted links
        $paginatedItems->setPath($request->url());

       
       
           
           return view('/Categorywisecustomers',['wards'=>$wards,'project'=>$paginatedItems,'projectscount'=>$ms,'countcat'=>count($enquiry1),'catsub'=>$request->cat]);
   }





   public function categoryenquiry(request $request){

           
 


        $check = Assignenquiry::where('user_id',$request->user)->count();
         if($request->number){
            $y = implode(",",$request->number);
         } else{
          $y="null";
         }
      
  
     if($check == 0){

        $data = new Assignenquiry;
        $data->user_id = $request->user;
        $data->eids = $y;
        $data->save();
     }else{
         if($request->number){
            $y = implode(",",$request->number);
         } else{
          $y="null";
         }
   	    Assignenquiry::where('user_id',$request->user)->update(['eids'=>$y]);
         
     }

   	    return back()->with('success','Assigned successfully !');
   }

 public function storecustomerenq(request $request){
  

         $check = Assignenquiry::where('user_id',$request->user)->count();
         if($request->number){
            $y = implode(",",$request->number);
         } else{
          $y="null";
         }
      
  
     if($check == 0){

        $data = new Assignenquiry;
        $data->user_id = $request->user;
        $data->numbers = $y;
        $data->save();
     }else{
         if($request->number){
            $y = implode(",",$request->number);
         } else{
          $y="null";
         }
   	    Assignenquiry::where('user_id',$request->user)->update(['numbers'=>$y]);
         
     }

   	    return back()->with('success','Assigned successfully !');

 	 
 }
 public function getcustomerenqlist(request $request){
            $wards = Ward::all();
           $enquiry=[];
           $enquiry1 = [];
         $id = Assignenquiry::where('user_id',Auth::user()->id)->pluck('numbers')->first();

           $ids = explode(",", $id);
           $dd = ProjectDetails::getcustomer();
       $pname = CustomerDetails::whereIn('mobile_num',$ids)->whereNotIn('mobile_num',$dd['numbers'])->pluck('mobile_num')->unique(); 
          
          $new = [];

      

       foreach ($pname as $projects) {
         $procname = ProcurementDetails::where('procurement_contact_no',$projects)->pluck('procurement_name')->first();
          $procnumber = ProcurementDetails::where('procurement_contact_no',$projects)->pluck('procurement_contact_no')->first();

          $s = ProcurementDetails::where('procurement_contact_no',$projects)->pluck('project_id');
          
          $full = Requirement::whereIn('project_id',$s)->get();
          

       array_push($new,["procname"=>$procname,'full'=>$full,'procnumber'=>$procnumber]);
       }     
      $yup = [];

           for($i=0;$i<sizeof($new);$i++){

              $y = count($new[$i]['full']);
              array_push($yup,$y);
           }
          $ms = array_sum($yup);

      
         // Get current page form url e.x. &page=1
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
 
        // Create a new Laravel collection from the array data
        $itemCollection = collect($new);
 
        // Define how many items we want to be visible in each page
        $perPage =50;
 
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
 
        // Create our paginator and pass it to the view
        $paginatedItems= new \Illuminate\Pagination\LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
 
        // set url path for generted links
        $paginatedItems->setPath($request->url());


 	 return view('/Categorywisecustomers',['wards'=>$wards,'project'=>$paginatedItems,'projectscount'=>$ms,'countcat'=>count($enquiry1),'catsub'=>$request->cat]);
 }






  public function getassignenquiry(request $request){
 $date=date('Y-m-d');
  	$ids = Assignenquiry::where('user_id',Auth::user()->id)->pluck('eids')->first();
    $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
  	  $s = explode(",", $ids);
      $dd = ProjectDetails::getcustomer();
  	   $projects = Requirement::whereIn('id',$s)
                         ->whereNotIn('id',$dd['enquiry'])
                         ->with('procurementdetails','proc')
                        ->paginate(20);
  	  return view('enquirywise',['projects'=>$projects,'log'=>$log,'$log1'=>$log1]);
  }
  public function Assignfollowup() {

  	    $date=date('Y-m-d');
        $followup = Requirement::where('follow_up','LIKE',$date.'%')->paginate('100');

         return view('/Assignfollowup',['enquiry'=>$followup]);
  }
  public function resetwards(request $request){
    
      
        WardAssignment::whereIn('user_id',$request->number)->update(['status'=>"Completed"]);

        return back()->with('success','Deactive successfully !');

  }
  public function SMSSentreport(){

        $date = date('Y-m-d', strtotime('-10 days'));
        $data = MamaSms::where('created_at','>=',$date."%")->with('user')->get();

         return view('/SMSSentreport',['data'=>$data]);

  }

  public function invoicegen(request $request){
      
       $data =$request->orderid;

      $ship = Requirement::where('id',$request->enqid)->pluck('ship')->first();
      $bill = Requirement::where('id',$request->enqid)->pluck('billadress')->first();
      $invoiceno = $request->invoiceno;



    return view('/invoicegen',['data'=>$data,'ship'=>$ship,'bill'=>$bill,'req_id'=>$request->enqid,'invoiceno'=>$invoiceno]);
  }
  public function multisupplier(request $request){
      
       $data =$request->orderid;

      $ship = Requirement::where('id',$request->enqid)->pluck('ship')->first();
      $bill = Requirement::where('id',$request->enqid)->pluck('billadress')->first();




    return view('/multisupplier',['data'=>$data,'ship'=>$ship,'bill'=>$bill,'req_id'=>$request->enqid]);
  }
 
 public function getgstcal(request $request){


        

     $category = Category::where('id',$request->cat)->pluck('category_name')->first();

     $gst = Gst::where('state',$request->state)->where('category',$category)->first();
     $count = $gst->igst;
     
       $unitprice = ($request->price/$gst->gstpercent);

         $x =$request->price;
         $y =$request->qua;
         $withoutgst = ($x/$gst->gstpercent);
         $amount =round($withoutgst * $y);
           if($count == null){   
                   $t = $gst->cgst/100;
                  $cgst = ($amount * $t);
                  $sgst = ( $amount * $t);
                  $igst = "";
                      $gstamount = $cgst + $sgst;
                  $gstpercent= $gst->cgst;
                   $gstlable = "(".$gst->cgst."+".$gst->cgst.")%" ;
                  $state = $gst->state;    
                    }
           else{
                   $cgst = "";
                   $sgst = "";
                     $t = $gst->igst /100;

                    $t1 = ($amount * $t);
                    
                    $igst = $t1;
                  $gstamount=$t1;
                  $gstpercent= $gst->igst;
                   $gstlable = "(".$gst->igst.")%";

                  $state = $gst->state;
                }


         $withgst = ($request->price * $request->qua);
         $without = ($withgst - $gstamount);


    return response()->json(['gst'=>$gstamount,'withgst'=>$withgst,'without'=>$without,'gstpercent'=>$gstpercent,'state'=>$state,'unitprice'=>$unitprice,'gstlable'=>$gstlable]);
 }
public function storeinvoice(request $request){
 
   



  if(count($request->cat255) != 0){
 if($request->cat255){
   $category = implode(",", $request->cat255);
 }else{
  $category = "";
 }
if($request->brand55){
   $brand = implode(",", $request->brand55);
 }else{
  $brand = "";
 }
if($request->desc1){
   $subcat = implode(",", $request->desc1);
 }else{
  $subcat = "";
 }
if($request->quan1){
   $quan = implode(",", $request->quan1);
 }else{
  $quan = "";
 }
if($request->price1){
   $price = implode(",", $request->price1);
 }else{
  $price = "";
 }
if($request->unit1){
   $unit = implode(",", $request->unit1);
 }else{
  $unit = "";
 }
 if($request->state12){
   $state = implode(",", $request->state12);
 }else{
  $state = "";
 }  
  if($request->gst1){
   $gst = implode(",", $request->gst1);
 }else{
  $gst = "";
 }  
  if($request->withgst1){
   $withgst = implode(",", $request->withgst1);
 }else{
  $withgst = "";
 }  
  if($request->withoutgst1){
   $withoutgst = implode(",", $request->withoutgst1);
 }else{
  $withoutgst = "";
 } 
  if($request->status){
     $paymentmode = implode(",", $request->status);
  }else{
    $paymentmode = "";
  }
   if($request->unitprice1){
     $unitprice = implode(",", $request->unitprice1);
  }else{
    $unitprice ="";
  }
  if($request->hsn1){
     $hsn = implode(",", $request->hsn1);
  }else{
    $hsn ="";
  }

      $gstamountinwords = array_sum($request->gst1);
        $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$gstamountinwords.'&token=fshadvjfa67581232'
;
        $response = file_get_contents($url);
        $data = json_decode($response,true);
        $dtow = $data['message'];
       

        $totalamountinwords = array_sum($request->withgst1);
        $url1 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$totalamountinwords.'&token=fshadvjfa67581232'
;
        $response1 = file_get_contents($url1);
        $data1 = json_decode($response1,true);
        $dtow1 = $data1['message'];

$gstamt = array_sum($request->gst1);
$totalwith =  array_sum($request->withgst1);
$totalwithout = array_sum($request->withoutgst1);
$percent = Gst::where('category',$request->cat255[0])->where('state',$request->state12[0])->pluck('gstpercent')->first();
$state = $request->state12[0];
$imageFileName3 = "";


  }else{

 if($request->cat){
   $category = implode(",", $request->cat);
 }else{
  $category = "";
 }
if($request->brand){
   $brand = implode(",", $request->brand);
 }else{
  $brand = "";
 }
if($request->desc){
   $subcat = implode(",", $request->desc);
 }else{
  $subcat = "";
 }
if($request->quan){
   $quan = implode(",", $request->quan);
 }else{
  $quan = "";
 }
if($request->price){
   $price = implode(",", $request->price);
 }else{
  $price = "";
 }
if($request->unit){
   $unit = implode(",", $request->unit);
 }else{
  $unit = "";
 }
 if($request->state){
   $state = implode(",", $request->state);
 }else{
  $state = "";
 }  
  if($request->gst){
   $gst = implode(",", $request->gst);
 }else{
  $gst = "";
 }  
  if($request->withgst){
   $withgst = implode(",", $request->withgst);
 }else{
  $withgst = "";
 }  
  if($request->withoutgst){
   $withoutgst = implode(",", $request->withoutgst);
 }else{
  $withoutgst = "";
 } 
  if($request->status){
     $paymentmode = implode(",", $request->status);
  }else{
    $paymentmode = "";
  }
   if($request->unitprice){
     $unitprice = implode(",", $request->unitprice);
  }else{
    $unitprice ="";
  }
  if($request->hsn){
     $hsn = implode(",", $request->hsn);
  }else{
    $hsn ="";
  }

      $gstamountinwords = $request->totalgst;
        $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$gstamountinwords.'&token=fshadvjfa67581232'
;
        $response = file_get_contents($url);
        $data = json_decode($response,true);
        $dtow = $data['message'];
       

        $totalamountinwords = $request->totalwithgst;
        $url1 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$totalamountinwords.'&token=fshadvjfa67581232'
;
        $response1 = file_get_contents($url1);
        $data1 = json_decode($response1,true);
        $dtow1 = $data1['message'];

     if($request->eway != null){
              $ewaybill = $request->file('eway');
             
             $imageFileName3 = time() . '.' . $ewaybill->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/invoiceewaybill/' . $imageFileName3;
             $s3->put($filePath, file_get_contents($ewaybill), 'public');

             
           }else{
             $imageFileName3="";
           }

$gstamt = $request->totalgst;
$totalwith =  $request->totalwithgst;
$totalwithout = $request->totalwithoutgst;
$percent = $request->gstpercent;
$state = $request->states;

}


$data = new  MultipleInvoice;
$data->order_id =$request->orderid; 
$data->category = $category;
$data->brand = $brand;
$data->subcat = $subcat;
$data->quantity = $quan;
$data->price = $price;
$data->unit = $unit;
$data->states = $state;
$data->gstamount = $gst;
$data->withgst = $withgst;
$data->withoutgst = $withoutgst;
$data->bill = $request->bill;
$data->ship =$request->ship; 
$data->totalgst = $gstamt;
$data->totalwithgst = $totalwith;
$data->totalwithoutgst = $totalwithout ;
$data->HSN = $hsn;
$data->req_id = $request->req_id;
$data->customergst = $request->cgst; 
$data->paymentmode = $paymentmode;
$data->state = $state;
$data->gstpercent = $percent;
$data->totalamountinwords = $dtow;
$data->gstinwords = $dtow1;
$data->unitprice = $unitprice;
$data->eway = $imageFileName3;
$data->ewaynumber = $request->ewaynumber;
$data->save();

       

                $val =MamahomePrice::max('final');
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
                $invoiceno = "MH_".$country_code."_".$zone."_".$year."_IN".$z;
                                $ino = MultipleInvoice::where('order_id',$request->orderid)->update([
                                    'invoiceno'=>$invoiceno,
                                    'final'=>$z,
                                    'invoicedate'=>date('Y-m-d')
                                ]);
        


return back()->with('info',"Invoice Generated successfully !");

}
public function assignunupdatemanu(request $request){


 $check = assign_manufacturers::where('user_id',$request->user)->count();
         if($request->number){
            $y = implode(",",$request->number);
         } else{
          $y="null";
         }
  
     if($check == 0){

        $data = new assign_manufacturers;
        $data->user_id = $request->user;
        $data->manuids = $y;
        $data->save();
     }else{
         if($request->number){
            $y = implode(",",$request->number);
         } else{
          $y="null";
         }
         assign_manufacturers::where('user_id',$request->user)->update(['manuids'=>$y]);
     }

   return back()->with('success','Assigned successfully !');

  
}
public function targetrem(){

    $data = CategoryTarget::with('cat')->get();
    $salesdata = SalesTarget::with('user')->get();
    $didicate = Dedicatedsalestarget::with('user')->get();
    return view('/targetrem',['data'=>$data,'salesdata'=>$salesdata,'didicate'=>$didicate]);
}
public function salestarget(request $request){
 $check = SalesTarget::where('user_id',$request->user)->first();

   if(count($check) == 0){

       $data = new SalesTarget;
       $data->user_id = $request->user;
       $data->tpamount = $request->targetval;
        $data->start = $request->start;
       $data->end = $request->end;
       $data->totalamount = $request->totalamount;
     
       $data->save();
   }else{
       $check->user_id = $request->user;
       $check->tpamount = $request->targetval;
        $check->start = $request->start;
       $check->end = $request->end;
       $check->totalamount = $request->totalamount;

      
       $check->save();
   }
  return back()->with('success','Target Assigned successfully!');


}
public function decicatedcust(request $request){
 $check = Dedicatedsalestarget::where('user_id',$request->user)->first();

   if(count($check) == 0){

       $data = new Dedicatedsalestarget;
       $data->user_id = $request->user;
       $data->tpamount = $request->targetval;
        $data->start = $request->start;
       $data->end = $request->end;
       $data->totalamount = $request->totalamount;
     
       $data->save();
   }else{
       $check->user_id = $request->user;
       $check->tpamount = $request->targetval;
        $check->start = $request->start;
       $check->end = $request->end;
       $check->totalamount = $request->totalamount;

      
       $check->save();
   }
  return back()->with('success','Target Assigned successfully!');


}


public function cattarget(request $request){

 
   $check = CategoryTarget::where('category',$request->category)->first();

   if(count($check) == 0){

       $data = new CategoryTarget;
       $data->category = $request->category;
       $data->unit = $request->unit;
       $data->quantity = $request->quantity;
       $data->price = $request->price;
       $data->percent = $request->percernt;
       $data->totalatpmount = $request->targetval;
       $data->start = $request->start;
       $data->end = $request->end;

       $data->save();
   }else{
       $check->category = $request->category;
       $check->unit = $request->unit;
       $check->quantity = $request->quantity;
       $check->price = $request->price;
       $check->percent = $request->percernt;
       $check->totalatpmount = $request->targetval;
        $check->start = $request->start;
       $check->end = $request->end;
       $check->save();
   }
  return back()->with('info',"Category Target created successfully!");
}
public function assigncustomers(){
     
     $depts = [1,2];
     $wardsAndSub = [];
    $users = User::whereIn('users.department_id',$depts)
              ->leftjoin('assignstage','assignstage.user_id','users.id')
              ->leftjoin('departments','departments.id','users.department_id'   )
              ->leftjoin('groups','groups.id','users.group_id')

              ->select('users.*','departments.dept_name','groups.group_name','assignstage.prv_ward','assignstage.prv_subward','assignstage.prv_date','assignstage.prv_stage','assignstage.state' )->paginate(20);

             


 //      $details= $request->Search;
 // $detail = User::where(['name', 'LIKE', '%' . $details . '%'])->get();


   
    $wards = Ward::all();
    $subwards = SubWard::leftjoin('project_details','sub_ward_id','sub_wards.id')
               ->select('sub_wards.*')->get();
    $assign = AssignStage::pluck('state');
    foreach($wards as $ward){
        $subward = SubWard::where('ward_id',$ward->id)->get();
        array_push($wardsAndSub,['ward'=>$ward->id,'subWards'=>$subward]);
    }


 return view('assigncustomers',['wardsAndSub'=>$wardsAndSub,'subwards'=>$subwards, 'users'=>$users,'wards'=>$wards]);


    
}
public function customerstore(request $request){

   
  if($request->all =="ALL" && !$request->ward && !$request->subward )
  {
     $subward = SubWard::pluck('id');

    $customer = CustomerDetails::whereIn('sub_ward_id',$subward)->pluck('customer_id')->toArray();

  }else if(!$request->all && $request->ward && $request->subward){

    $customer = CustomerDetails::whereIn('sub_ward_id',$request->subward)->pluck('customer_id')->toArray();

  }else{
      $customer = [];
  }
  
    $cust = implode(",", $customer);

  $check = customerassign::where('user_id', $request->user_id)->first();

  if(count($check) == 0){

      $data = new customerassign;
      $data->user_id = $request->user_id;
      $data->customerids = $cust;
      $data->save();
  }else{
      $check->user_id = $request->user_id;
      $check->customerids = $cust;
      $check->save();
  }




return redirect()->back()->with('success',count($customer).'Customers Assigned Successfully');


}

public function customervisit(request $request){
   $projectids = customerassign::where('user_id',Auth::user()->id)->pluck('customerids')->first();
      
      $ids = explode(",", $projectids);
      
      if($request->today=="today" ){
            
              $date=date('Y-m-d');
             $today = VisitedCustomers::where('user_id',Auth::user()->id)->pluck('customer_id');

             $projects = CustomerDetails::whereIn('customer_id',$today)->get();
        
      }
    else  if($request->bal=="bal"){

              $customerids = customerassign::where('user_id',Auth::user()->id)->pluck('customerids');
              $totalcount = explode(",", $customerids);
              $today=date('Y-m-d');
              $past = date('Y-m-d',strtotime("-30 days",strtotime($today)));
              $visit = VisitedCustomers::where('user_id',Auth::user()->id)->whereIn('customer_id',$ids)->pluck('customer_id');

            
           
               $projects = CustomerDetails::whereNotIn('customer_id',$visit)->whereIn('customer_id',$ids)->get();
      }else{

      $projects = CustomerDetails::whereIn('customer_id',$ids)->get();
      }

      
     

     


   

   return view('/customervisit',['projects'=>$projects]);
}
public function customerfeedback(request $request){
        
        if($request->cat){
           $interest = implode(",", $request->cat);
        }else{
          $interest = '';
        }

       $check = VisitedCustomers::where('customer_id',$request->cid)->first();
       if(count($check) == 0){
        $data = new VisitedCustomers;
        $data->user_id = $request->userid;
        $data->customer_id = $request->cid;
        $data->interestcat = $interest;
        $data->remark = $request->feedback;

        $data->save();

      }else{
        $check->user_id = $request->userid;
        $check->customer_id = $request->cid;
        $check->interestcat = $interest;
        $check->remark = $request->feedback;
        $check->save();
      }

    return back()->with('success','Submited successfully !');





    //customerassign::where('user_id',$request->userid)->
}
 public function assignvistedcustomer(request $request){
      
      

       if($request->userid == "All" && $request->from &&  $request->to){
        
        $projects = VisitedCustomers::where('updated_at','>=',$request->from)->where('updated_at','<=',$request->to)->with('cust')->paginate('40');



       }
   else if($request->userid  && $request->from &&  $request->to){
          $projects = VisitedCustomers::where('updated_at','>=',$request->from)->where('updated_at','<=',$request->to)->where('user_id',$request->userid)->with('cust')->paginate('40');

       }else{
           $projects = [];
       }
    
    return view('/assignvistedcustomer',['projects'=>$projects]);
 
 }

public function customerledger(request $request){

     
       $number = $request->Number;
       $from = $request->from;
       $to = $request->to;
       
      $bal =BankTransactions::where('number',$number)->pluck('bal')->last(); 

       if($request->Number && !$request->from && !$request->to){
           
           $invoice = BankTransactions::where('number',$number)->get();
           $name = CustomerDetails::where('mobile_num',$number)->pluck('first_name')->first();
           

       }else if($request->Number && $request->from && $request->to){

         $invoice = BankTransactions::where('number',$number)->wheredate('created_at','>=',$from)->wheredate('created_at','<=',$to)->get();

       $name = CustomerDetails::where('mobile_num',$number)->pluck('first_name')->first();

       }
       

       else{

        $invoice = [];
        $bal = "";
        $name = "";
        $from = "";
        $to = "";
       }  
       

   return view('/customerledger',['invoice'=>$invoice,'bal'=>$bal,'name'=>$name,'from'=>$from,'to'=>$to,'number'=>$number]);
}

public function visithistory(request $request){



     $data = DB::table('visited_customers')->where('customer_id',$request->cid)->update(['sitevisited'=>$request->site,'customervisited'=>$request->cvisit,'user_id'=>$request->userid]);

    
     return back()->with('info','Submited successfully !');
}
public function getbanktrans(){

  $projects = BankTransactions::orderBy('id','DESC')->get()->take(100);


 
  return view('/customerbank',['projects'=>$projects]);
}

public function testbank(request $request){


    
    $customerid = CustomerDetails::where('mobile_num',$request->mobile)->pluck('customer_id')->first();
    
       if($request->drcr == "Credit"){
         $credit = $request->amount;
             
          $bal =BankTransactions::where('number',$request->mobile)->pluck('bal')->last();
          
          $balc = (($bal) + ($request->amount));    

       }else{
        $credit = "";
       }
       if($request->drcr == "Debit"){

         $debit = $request->amount;
          $bal =BankTransactions::where('number',$request->mobile)->pluck('bal')->last();
          
          $balc = (($bal) - ($request->amount)); 

       }else{
        $debit = "";
       }
      $data = new BankTransactions;
      $data->customer_id = $customerid;
      $data->number = $request->mobile;
      $data->invoiceno=$request->invoice;
      $data->date=$request->date;
      $data->credit=$credit;
      $data->debit = $debit;
      $data->drcr=$request->drcr;
      $data->remark=$request->desc;
      $data->bal = $balc;
      
      $data->save();

                        
              return back()->with('success','Submited successfully !');
      

}
public function gethelper(request $request){

$sale = CategoryTarget::where('category',$request->name)->first();
       



         $cates = [];
   
          $catname = Category::where('id',$sale->category)->pluck('category_name')->first();
           
          $invoice = MamahomePrice::where('description',$catname)->wheredate('created_at','>=',$sale->start)->wheredate('created_at','<=',$sale->end)->sum('amountwithgst');

           $gst = Gst::where('Category',$catname)->first();
                if($gst->cgst != NULL){

                  $gstval = ($gst->cgst + $gst->sgst);
                }else{
                  $gstval = ($gst->igst);
                }

                 $oders = MamahomePrice::where('description',$catname)->wheredate('created_at','>=',$sale->start)->wheredate('created_at','<=',$sale->end)->pluck('order_id');
           
           $sp = Supplierdetails::whereIn('order_id',$oders)->sum('totalamount');
       
           $total = $invoice - $sp;

           $tp = ($total * ($gstval/100));
            
            $finaltp = $total - $tp;
             
         array_push($cates,['category'=>$catname,'invoice'=>$finaltp,'categorytarget'=>$sale->totalatpmount]);

            $departments = [];
            $groups = [];
            $loggedInUsers = [];
        return view('/teamLeader',['cates'=>$cates,'departments'=>$departments,'groups'=>$groups]);
    
}
public function gettotaldetails(request $request){

      
       $gst = array_sum($request->gst);

       $withgst = array_sum($request->withgst);
       $withoutgst = array_sum($request->withoutgst);

          
       

      return response()->json(['gst'=>round($gst),'withgst'=>round($withgst),'withoutgst'=>round($withoutgst)]);

}
public function newcustomerassign(request $request){

    
    $dd = ProjectDetails::getcustomer();

  $numbers = NewCustomerAssign::pluck('customerids')->toArray();
  $numbers = array_filter($numbers);
         $data=[];
     foreach ($numbers as $key => $da) {
           

              $arraydata = explode(",",$da);
                
                
              array_push($data,$arraydata);
              

     }
     $arr = [];
       for($i=0;$i<sizeof($data);$i++){

                $arr = array_merge($arr,$data[$i]);
          }
        $final = array_filter($arr);
        $arr = array_diff($final,['null']);
        $bb = array_diff($arr, ["1"]);
        $arr1 = array_diff($bb,['"']);

    $project = CustomerDetails::whereNotIn('customer_id',$final)->with('type')->get();

    $total =CustomerDetails::count();
        $assign = CustomerDetails::whereIn('customer_id',$arr1)->count();



     if($request->from && $request->to){
       $projectnumbers = [];
       $manunumber=[];
        foreach ($project as  $ss) {
           $numbers = ProcurementDetails::where('procurement_contact_no',$ss->mobile_num)->pluck('project_id');

            
           $updated = ProjectDetails::whereIn('project_id',$numbers)->whereDate('updated_at','>=',$request->from)->whereDate('updated_at','<=',$request->to)->first();
           
           if(count($updated) != 0){
            $n = $ss->mobile_num;;
           }else{
            $n =""; 
           }
           array_push($projectnumbers, $n);

           $numberss = Mprocurement_Details::where('contact',$ss->mobile_num)->pluck('manu_id');


           $updateds = Manufacturer::whereIn('id',$numberss)->whereDate('updated_at','>=',$request->from)->whereDate('updated_at','<=',$request->to)->first();
           
          if(count($updateds) == 0){
            $m ="";
           }else{
            $m =$ss->mobile_num; 
           }

           array_push($manunumber,$m);
        
        }           
        $pro = array_filter($projectnumbers);
        $manu = array_filter($manunumber);
        $final =  array_merge($pro,$manu);      
        $projects = CustomerDetails::whereIn('mobile_num',$final)->with('type')->paginate(10);
       
     }else{
      $projects = CustomerDetails::whereNotIn('customer_id',$final)->with('type')->paginate(10);
     }


  return view('/newcustomerassign',['projects'=>$projects,'total'=> $total,'assign'=>$assign]);
}








public function storenewcustomer(request $request){



    
        $number = CustomerDetails::whereIn('customer_id',$request->number)->pluck('mobile_num');
       $project = ProcurementDetails::whereIn('procurement_contact_no',$number)->pluck('project_id');
       $Manufacturer = Mprocurement_Details::whereIn('contact',$number)->pluck('manu_id');
      $enquiry = Requirement::whereIn('project_id',$project)->orWhereIn('manu_id',$Manufacturer)->pluck('id');
      $orders = DB::table('orders')->whereIn('project_id',$project)->orWhereIn('manu_id',$Manufacturer)->pluck('id');
      $invoice = MamahomePrice::whereIn('order_id',$orders)->pluck('invoiceno');
      
      if($request->number){
        $datas = implode(",", $request->number);
      }else{
        $datas=[];
      }
       $num = NewCustomerAssign::where('user_id',$request->user)->pluck('customerids')->first();
      $final = $datas.','.$num;
    
      $check = NewCustomerAssign::where('user_id',$request->user)->first();
       if(count($check) == 0){

      $data = new NewCustomerAssign;
      $data->customerids= $final;
      $data->projects= $project;
      $data->manuids= $Manufacturer;
      $data->enquiry= $enquiry;
      $data->orders= $orders;
      $data->invoices= $invoice;
      $data->user_id=$request->user;
      $data->numbers =  $number;
      $data->save();
       }else{
      $check->customerids= $final;
      $check->projects= $project;
      $check->manuids= $Manufacturer;
      $check->enquiry= $enquiry;
      $check->orders= $orders;
      $check->invoices= $invoice;
      $check->user_id=$request->user;
      $check->numbers =  $number;
      $check->save();
       }

       if($request->bus){
       $data = $request->bus;
       $number = $request->number;
       $user_id = $request->user;
        for ($i=0;$i<sizeof($number);$i++) { 
             $yup = NewCustomerAssign::where('cid',$number[$i])->first();
         
             if(count($yup) == 0){
               
             $target = new NewCustomerAssign;
             $target->cid = $number[$i];
             $target->mothlytarget = $data[$i];
             $target->mid = $user_id;
             $target->save();

             }else{
             
             $yup->cid = $number[$i];
             $yup->mothlytarget = $data[$i];
             $yup->mid = $user_id;
             $yup->save();
             }
        }
      }

      return back()->with("info",'Assigned successfully');






}
public function dCustomers(){

  $id = NewCustomerAssign::where('user_id',Auth::user()->id)->pluck('customerids')->first();
   $ids = explode(",",$id);
   $total = "";
  
$projects = CustomerDetails::whereIn('customer_id',$ids)->paginate(30);



return view('/newcustomerassign',['projects'=>$projects,'total'=>$total,'assign'=>"",'up'=>0]);
}
public function dnumbers(){
 
      $number = NewCustomerAssign::where('user_id',Auth::user()->id)->pluck('customerids')->first();

      $expo = explode(",", $number);

     $numbers = CustomerDetails::whereIn('customer_id',$expo)->pluck('mobile_num');

return view('/dnumbers',['num'=>$numbers]);

      
}






public function dprojects(){
 
      $numbe = NewCustomerAssign::where('user_id',Auth::user()->id)->pluck('customerids')->first();
      $num = explode(",", $numbe);

      $number = CustomerDetails::whereIn('customer_id',$num)->pluck('mobile_num');
    
     
     

     $numbers = ProcurementDetails::whereIn('procurement_contact_no',$number)->pluck('project_id');

      $projects = ProjectDetails::whereIn('project_id',$numbers)->paginate('20');

     
 $his = History::all();
        // $assigncount = new  AssignStage();
        $assigncount = AssignStage::where('user_id',Auth::user()->id)->first();
       
        $category = Category::all();
        $sales = Salesofficer::all();
        $orders = Order::all();
        $projectupdat=ProjectUpdate::all(); 
       
       return view('salesengineer',[
                'projects'=>$projects,
                
                // 'requirements' =>$requirements,
                
                'his'=>$his,
                
                
                'category'=>$category,
                'sales'=>$sales,
                'projectupdat'=>$projectupdat,
              


            ]);
    }
    public function dmanus(){
 
      $numbe = NewCustomerAssign::where('user_id',Auth::user()->id)->pluck('customerids')->first();
      $num = explode(",", $numbe);

      $number = CustomerDetails::whereIn('customer_id',$num)->pluck('mobile_num');
    
     
     

     $numbers = Mprocurement_Details::whereIn('contact',$number)->pluck('manu_id');

      $projects = Manufacturer::whereIn('id',$numbers)->paginate('20');

     
      $his = History::all();
        // $assigncount = new  AssignStage();
       
        
       
      return view('sales_manufacture',[
                'projects'=>$projects,
                'his'=>$his,
                'projectcount'=>count($projects)


            ]);
    }

   public function denquery(){
      $numbe = NewCustomerAssign::where('user_id',Auth::user()->id)->pluck('customerids')->first();
      $num = explode(",", $numbe);

       $number = CustomerDetails::whereIn('customer_id',$num)->pluck('mobile_num');
    
       $project = ProcurementDetails::whereIn('procurement_contact_no',$number)->pluck('project_id');
       $Manufacturer = Mprocurement_Details::whereIn('contact',$number)->pluck('manu_id');

       $projects = Requirement::whereIn('project_id',$project)->orWhereIn('manu_id',$Manufacturer)->paginate('20');



 return view('enquirywise',['projects'=>$projects]);
      
}

public function getprojectsmap(request $request){
  
      $id = implode(",",$request->project);

     function multiexplode ($delimiters,$string) {
            
            $ready = str_replace($delimiters, $delimiters[0], $string);
            $launch = explode($delimiters[0], $ready);
            return  $launch;
        }
        
       
        
    $projectids = multiexplode(array(",","[","]"),$id);
   
   $projects = ProjectDetails::leftJoin('site_addresses','project_details.project_id','site_addresses.project_id')
                    ->select('site_addresses.*','project_details.quality','project_details.*')
                    ->whereIn('project_details.project_id',$projectids)
                     ->with('procurementdetails','siteaddress')
                    ->get();  
    


    

  return view('/getprojectsmap',['projects'=>$projects]); 

}



public  function usercustomers(request $request)
{
   if($request->user && $request->user != "All"){
    $projectids = NewCustomerAssign::where('customerids',"!=",NULL)->where('user_id',$request->user)->pluck('customerids');
    $cids = NewCustomerAssign::where('user_id',$request->user)->where('cid',"!=",NULL)->pluck('cid')->toarray();
             $data=[];
          for($i=0;$i<sizeof($projectids);$i++){

              $array=explode(",",$projectids[$i]);
                 array_push($data, $array);
          }
              if(count($projectids) >0 ){

        $projectids = array_merge(...$data);
      } else{

         return "No customers Assigned";
         
      }

        $finalids = array_merge($cids,$projectids);
    
       
      
      $projects = CustomerDetails::with('type')->whereIn('customer_id',$finalids)->get();

      $sum = NewCustomerAssign::whereIn('cid',$finalids)->sum('mothlytarget');

      $projectss = CustomerDetails::whereIn('customer_id',$finalids)->pluck('customer_id');
 
    
  
$Project =CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',1)->count();
$Contractor = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',2)->count();
$Procurement = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',3)->count();
$Owner = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',4)->count();
$Manufacturer = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',6)->count();
$Blocks = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',7)->count();
$RMC = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',8)->count();
$Builder = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',11)->count();
$BuilderDeveloper = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',12)->count();
$SiteEngineer = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',13)->count();
$Consultant  = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',14)->count(); 
   }
else if($request->user == "All"){
   $projectids = NewCustomerAssign::where('customerids',"!=",NULL)->pluck('customerids');
   $cids = NewCustomerAssign::where('cid',"!=",NULL)->pluck('cid')->toarray();
             $data=[];
          for($i=0;$i<sizeof($projectids);$i++){

              $array=explode(",",$projectids[$i]);
                 array_push($data, $array);
          }
               
        $projectids = array_merge(...$data);

        $finalids = array_merge($cids,$projectids);
      
        
       
      
      $projects = CustomerDetails::with('type')->whereIn('customer_id',$finalids)->get();



      $sum = NewCustomerAssign::whereIn('cid',$finalids)->sum('mothlytarget');

      $projectss = CustomerDetails::whereIn('customer_id',$finalids)->pluck('customer_id');
 
    
  
$Project =CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',1)->count();
$Contractor = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',2)->count();
$Procurement = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',3)->count();
$Owner = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',4)->count();
$Manufacturer = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',6)->count();
$Blocks = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',7)->count();
$RMC = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',8)->count();
$Builder = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',11)->count();
$BuilderDeveloper = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',12)->count();
$SiteEngineer = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',13)->count();
$Consultant  = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',14)->count(); 
   }


   else{

   $projects = [];
$Project= '';
$Contractor= '';
$Procurement= '';
$Owner= '';
$Manufacturer= '';
$Blocks= '';
$RMC= '';
$Builder= '';
$BuilderDeveloper= '';
$SiteEngineer= '';
$Consultant= '';
$sum = '';
   }

   if($request->bus){
      dd($request->all());
       $data = $request->bus;
       $number = $request->number;
       $user_id = $request->user;
        for ($i=0;$i<sizeof($number);$i++) { 
             $yup = NewCustomerAssign::where('cid',$number[$i])->first();


         
             if(count($yup) == 0){
               
             $target = new NewCustomerAssign;
             $target->cid = $number[$i];
             $target->mothlytarget = $data[$i];
             $target->mid = $user_id;
             $target->save();

             }else{
             
             $yup->cid = $number[$i];
             $yup->mothlytarget = $data[$i];
             $yup->mid = $user_id;
             $yup->save();
             }
             return back();
        }



   }


   return view('/usercustomers',['projects'=>$projects,
'Project'=>$Project,
'Contractor'=>$Contractor,
'Procurement'=>$Procurement,
'Owner'=>$Owner,
'Manufacturer'=>$Manufacturer,
'Blocks'=>$Blocks,
'RMC'=>$RMC,
'Builder'=>$Builder,
'BuilderDeveloper'=>$BuilderDeveloper,
'SiteEngineer'=>$SiteEngineer,
'Consultant'=>$Consultant,'sum'=>$sum]);
}

public function Materialhub(){

       $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
       $tlwards = Subward::where('ward_id',$tl)->get();
        $this->variable=$tlwards;
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $wardsAssigned = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
        $a =Subward::where('id',$wardsAssigned)->pluck('ward_id')->first();
        $acc = Subward::where('ward_id',$a)->get();
        $Wards = [];
      $hello = Ward::all();
     foreach($hello as $user){
           
                $noOfwards = WardMap::where('ward_id',$user->id)->first();
                array_push($Wards,['ward'=>$noOfwards,'wardid'=>$user->id]);
            }
              $allwardlats = [];
              foreach ($Wards as $all) {

               
                  $allx = explode(",",$all['ward']['lat']);
                  $wardid = $all['wardid'];
               
                  array_push($allwardlats, ['lat'=>$allx,'wardid'=>$wardid]);
               }
             
         
    $a = [];
    for($j = 0; $j<sizeof($allwardlats);$j++){
        $finalward = [];

        $wardId = $allwardlats[$j]['wardid'];
        
    for($i=0;$i<sizeof($allwardlats[$j]['lat'])-3; $i+=2){

         $lat = $allwardlats[$j]['lat'][$i];
         $long =  $allwardlats[$j]['lat'][$i+1];
        $latlong = "{lat: ".$lat.", lng: ".$long."}";
       
         array_push($finalward,$latlong);

    }

       array_push($a,['lat'=>$finalward,'ward'=>$wardId]);

   }

    $d = response()->json($a);

     $subwards = SubWard::where('id',$wardsAssigned)->first();
        return view('Materialhub',['subwards'=>$subwards,'log'=>$log,'log1'=>$log1,'tlwards'=>$tlwards,'acc'=>$acc,'ward'=>$d]);



}
public function addmatirial(request $request){
  
 if(count($request->subward_id) != 0){
                 $ward= $request->subward_id;
             
              }else{

             $ward=WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
              }
              $projectimage="";
        $i = 0;
            if($request->pImage){
                foreach($request->pImage as $pimage){

                       $imageName3 = $pimage;
                     $imageFileName = $i.time() . '.' . $imageName3->getClientOriginalExtension();
                     $s3 = \Storage::disk('azure');
                     $filePath = '/Materialhub/' . $imageFileName;
                     $s3->put($filePath, file_get_contents($imageName3), 'public');


                     // $imageName3 = $i.time().'.'.$pimage->getClientOriginalExtension();
                     // $pimage->move(public_path('projectImages'),$imageName3);
                     if($i == 0){
                        $projectimage .= $imageFileName;
                     }
                     else{
                            $projectimage .= ",".$imageFileName;
                     }
                     $i++;
                }
        
            }


if($request->Product){
        $product = implode(",",$request->Product);
    }else{
       $product = "";
    }
if($request->price){
        $price = implode(",",$request->price);
    }else{
       $price = "";
    }



 $data = new Materialhub;
$data->subward_id = $ward;
$data->longitude = $request->longitude;
$data->latitude = $request->latitude;
$data->onumber =$request->onumber;  
$data->trucknumber  = $request->rWidth;
$data->address = $request->address;
$data->name = $request->pName;
$data->Category = $request->Category;
$data->Capacity = $request->Capacity;
$data->Capacityto = $request->Capacity1;

$data->remarks = $request->remarks;
$data->pImage = $projectimage;
$data->Vehicaltype = $request->Vehicaltype;
$data->product = $product;
$data->price = $price;
$data->user_id = Auth::user()->id;

$data->coordinaternumber1 = $request->cnumber1;
$data->coordinaternumber2 = $request->cnumber2;
$data->bankname = $request->bankname;
$data->accountnumber=$request->accountnumber;
$data->ifscode = $request->ifs;
$data->brokername = $request->bname;
$data->brokernumber1 = $request->bnumber1;
$data->brokernumber2 = $request->bnumber2;
$data->tracktype = $request->tracktypetype;







$data->save();


return back()->with('info',"Added successfully");

}
public function matirialslot(){


     $data = Materialhub::with('subward','user')->get();

return view('/matirialslot',['data'=>$data]);
}
public function updatematirial(request $request){

     if($request->pImage != null){

 $projectimage="";
        $i = 0;
            if($request->pImage){
                foreach($request->pImage as $pimage){

                       $imageName3 = $pimage;
                     $imageFileName = $i.time() . '.' . $imageName3->getClientOriginalExtension();
                     $s3 = \Storage::disk('azure');
                     $filePath = '/Materialhub/' . $imageFileName;
                     $s3->put($filePath, file_get_contents($imageName3), 'public');


                     // $imageName3 = $i.time().'.'.$pimage->getClientOriginalExtension();
                     // $pimage->move(public_path('projectImages'),$imageName3);
                     if($i == 0){
                        $projectimage .= $imageFileName;
                     }
                     else{
                            $projectimage .= ",".$imageFileName;
                     }
                     $i++;
                }
        
            }
     }

      if($request->Product){
        $product = implode(",",$request->Product);
    }else{
       $product = "";
    }
if($request->price){
        $price = implode(",",$request->price);
    }else{
       $price = "";
    }



        if($request->pImage != NULL){

      $data =  Materialhub::where('id',$request->id)->update(['onumber'=>$request->onumber,'name'=>$request->oname,'Capacity'=>$request->Capacity,'remarks'=>$request->remarks,'pImage'=>$projectimage,'Category'=>$request->Category,'Vehicaltype'=>$request->Vehicaltype,'product'=>$product,'price'=>$price,'coordinaternumber1' => $request->cnumber1,
'coordinaternumber2' => $request->cnumber2,
'bankname' => $request->bankname,
'accountnumber'=>$request->accountnumber,
'ifscode' => $request->ifs,
'brokername' => $request->bname,
'brokernumber1' => $request->bnumber1,
'brokernumber2' => $request->bnumber2,
'tracktype' => $request->tracktypetype,'Capacityto'=>$request->Capacity1]);
    }else{
       
      $data =  Materialhub::where('id',$request->id)->update(['onumber'=>$request->onumber,'name'=>$request->oname,'Capacity'=>$request->Capacity,'remarks'=>$request->remarks,'Category'=>$request->Category,'Vehicaltype'=>$request->Vehicaltype,'product'=>$product,'price'=>$price,'coordinaternumber1' => $request->cnumber1,
'coordinaternumber2' => $request->cnumber2,
'bankname' => $request->bankname,
'accountnumber'=>$request->accountnumber,
'ifscode' => $request->ifs,
'brokername' => $request->bname,
'brokernumber1' => $request->bnumber1,
'brokernumber2' => $request->bnumber2,
'tracktype' => $request->tracktypetype,'Capacityto'=>$request->Capacity1]);

     
    }


      

      $new = new UpdatedReport;
      $new->Materialhub_id = $request->id;
      $new->user_id = Auth::user()->id;
      $new->quntion = $request->quntion;
      $new->remarks = $request->remarks;

      $new->save(); 

    return back()->with('success',"Updated successfully");
}
public function editmat(request $request){

     $data = Materialhub::where('id',$request->id)->get();

     return view('/editmat',['data'=>$data]);
}
  public function rejectinvoice(request $request){

     if($request->from && $request->to){
          $data = MamahomePrice::wheredate('created_at','>=',$request->from)->wheredate('created_at','<=',$request->to)->where('amountwithgst',NULL)->get();

     } else{

      $today = date('d-m-y');
     $past = date('Y-m-d',strtotime("-30 days",strtotime($today)));

     $data = MamahomePrice::where('created_at','>=',$past)->where('amountwithgst',NULL)->get();
     }
 

      return view('/rejectinvoice',['data'=>$data]);
  }

public function deleteprice (request $request){
           dd($request->pid);
          Pricing::where('id',$request->pid)->delete();

          return back()->with('success','successfully Deleted !');

}
  public function teamleads(request $request){

         

         $depts=[1,2];

          
         
        $loggedInUsers = FieldLogin::where('logindate',date('Y-m-d'))
                        ->join('users','field_login.user_id','users.id')
                        ->whereIn('department_id',$depts)
                        ->select('users.name','field_login.*','users.employeeId')
                        ->get();


              

        
        $tl1= Tlwards::where('group_id','=',22)->get();
        $usersId = "null";
        $userid = Auth::user()->id;


        foreach($tl1 as $searchWard){
            if($searchWard->user_id == $userid){
                
            $usersId = explode(",",$searchWard->users);
            }
        }
 

        $depts=[1,2];
        $users = User::all();

        $leLogins = loginTime::where('logindate',date('Y-m-d'))
                        ->join('users','login_times.user_id','users.id')
                        ->leftjoin('departments','users.department_id','departments.id')
                        ->select('users.name','users.employeeId','login_times.*','departments.id')
                        ->get();


          $login = loginTime::where('logindate',date('Y-m-d'))->where('user_id',Auth::user()->id)->first();

          $newwards = [];
         foreach($users as $user){
                $tlwards = Tlwards::where('user_id',$user->id)->first();
                if($tlwards == null){   
                }
                else{
                $wardids = explode(",",$tlwards->ward_id);
                $noofwardids = Ward::whereIn('id',$wardids)->get()->toArray();
                $userIds = explode(",",$tlwards->users);
                $noOfUsers = User::whereIn('id',$userIds)->get()->toArray();
                array_push($newwards,['tl_id'=>$user->id,'wardtl'=>$noofwardids,'tlusers'=>$noOfUsers]);
            }
        }
        $date=date('Y-m-d');
         $departments = Department::whereIn('id',[1,2])->get();
         $groups = Group::whereIn('id',[2,6,7,22])->get();
        $followup = Requirement::where('follow_up','LIKE',$date.'%')->get();
     
         $customerids = customerassign::where('user_id',Auth::user()->id)->pluck('customerids')->first();
             $today = date('d-m-y');
          $totalcount = explode(",", $customerids);
         $past = date('Y-m-d',strtotime("-30 days",strtotime($today)));
         $visit = VisitedCustomers::where('user_id',Auth::user()->id)->count();
             
           $bal = count($totalcount) - $visit ;
          
           $date=date('Y-m-d');
        
         $today = VisitedCustomers::where('user_id',Auth::user()->id)->where('updated_at','LIKE',$date."%")->count();

          if($request->name){

         $sale = CategoryTarget::where('category',$request->name)->first();

          if(count($sale) == 0){
            return back()->with('error',"Seleted  Category is not Targeted please select other category");
          }
       

        
         $cates = [];
   
          $catname = Category::where('id',$sale->category)->pluck('category_name')->first();
           
          $invoice = MamahomePrice::where('category',$catname)->where('invoicedate','>=',$sale->start)->where('invoicedate','<=',$sale->end)->sum('amountwithgst');
          
           $gst = Gst::where('Category',$catname)->first();
                if($gst->cgst != NULL){

                  $gstval = ($gst->cgst + $gst->sgst);
                }else{
                  $gstval = ($gst->igst);
                }

                 $oders = MamahomePrice::where('category',$catname)->where('invoicedate','>=',$sale->start)->where('invoicedate','<=',$sale->end)->pluck('order_id');
           
           $sp = Supplierdetails::whereIn('order_id',$oders)->sum('totalamount');
       
           $total = $invoice - $sp;

           $tp = ($total * ($gstval/100));
            
            $bals = $total - $tp;
            $finaltp = ($sale->totalatpmount) - ($bals);
            
              $catamount = ($sale->quantity * $sale->price);
            $yet = $catamount - $invoice;
         array_push($cates,['category'=>$catname,'invoice'=>$yet,'categorytarget'=>$catamount]);

          }else{
           $sale = CategoryTarget::where('category',36)->first();

          
            $cates = [];
   
          $catname = Category::where('id',$sale->category)->pluck('category_name')->first();


           
          $invoice = MamahomePrice::where('category',$catname)->where('created_at','>=',$sale->start)->where('created_at','<=',$sale->end)->sum('amountwithgst');
          
           $gst = Gst::where('Category',$catname)->first();
                if($gst->cgst != NULL){

                  $gstval = ($gst->cgst + $gst->sgst);
                }else{
                  $gstval = ($gst->igst);
                }

                 $oders = MamahomePrice::where('category',$catname)->wheredate('created_at','>=',$sale->start)->wheredate('created_at','<=',$sale->end)->pluck('order_id');
           
           $sp = Supplierdetails::whereIn('order_id',$oders)->sum('totalamount');
       
           $total = $invoice - $sp;

           $tp = ($total * ($gstval/100));
            
            $bals = $total - $tp;
            $finaltp = ($sale->totalatpmount) - ($bals);
            
              $catamount = ($sale->quantity * $sale->price);
            $yet = $catamount - $invoice;
         array_push($cates,['category'=>$catname,'invoice'=>$yet,'categorytarget'=>$catamount]);

          }
         return view('/teamleads',['loggedInUsers'=>$loggedInUsers,'leLogins'=> $leLogins,'users'=>$users,'usersId'=>$usersId,'newwards'=>$newwards,'followup'=>$followup,'departments'=>$departments,'groups'=>$groups,'today'=>$today,'total'=>count($totalcount),'bal'=>$bal,'cates'=>$cates,'login'=>$login]);
   }

public function mreway(request $request){

           $image = $request->file('mrebilimg');
             $imageFileName ="MR". time() . '.' . $image->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/Mrewaybill/' . $imageFileName;
             $s3->put($filePath, file_get_contents($image), 'public');

  $data = MamahomePrice::where('order_id',$request->orderid)->update(['mrewayno'=>$request->mrebilno,"mrewayimg"=>$imageFileName]);


return back()->with('info','successfully Added !');

}

public function mixedeway(request $request){

    
     if($request->type == "MR"){
              if($request->mrebilimg){

              $image = $request->file('mrebilimg');
             $imageFileName ="MR". time() . '.' . $image->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/Mrewaybill/' . $imageFileName;
             $s3->put($filePath, file_get_contents($image), 'public');
      
              }else{
                 $imageFileName = "N/A"; 
              }
            

             
      $data = MRInvoice::where('order_id',$request->orderid)->update(['e_way_no'=>$request->mrebilno,"mrewayimg"=>$imageFileName,'HSN'=>$request->hsn,'truckno_mr'=>$request->truckno]);


     }else{


             if($request->mrebilimg){
            $image = $request->file('mrebilimg');
             $imageFileName ="MH". time() . '.' . $image->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/MHewaybill/' . $imageFileName;
             $s3->put($filePath, file_get_contents($image), 'public');
             }else{
              $imageFileName = "N/A"; 
             }
  $data = MultipleInvoice::where('order_id',$request->orderid)->update(['ewaynumber'=>$request->mrebilno,'eway'=>$imageFileName,'updated_at'=>$request->taxdate,'truckno_mh'=>$request->truckno]);


   $data = MamahomePrice::where('order_id',$request->orderid)->update(['e_way_no'=>$request->mrebilno,'HSN'=>$request->hsn,'truckno_mh'=>$request->truckno,'mrewayimg'=>$imageFileName]);
     }

return back()->with('info','successfully Added !');


}
 public function multiplesuplier(request $request){
      
       $data =$request->orderid;

      $ship = Requirement::where('id',$request->enqid)->pluck('ship')->first();
      $bill = Requirement::where('id',$request->enqid)->pluck('billadress')->first();
    



    return view('/multiplesuplier',['data'=>$data,'ship'=>$ship,'bill'=>$bill,'req_id'=>$request->enqid]);
  }
 
 public function trasportinvoice(request $request){
      
       $data =$request->orderid;
        $reqid = Order::where('id',$request->orderid)->pluck('req_id')->first();
      $ship = Requirement::where('id',$reqid)->pluck('ship')->first();
      $bill = Requirement::where('id',$reqid)->pluck('billadress')->first();
    



    return view('/trasportinvoice',['data'=>$data,'ship'=>$ship,'bill'=>$bill,'req_id'=>$request->enqid]);
  }



  public function multisdhg(request $request){

     if(count($request->cat255[0]) != 0){

        if($request->cat255){
          $category = implode(",", $request->cat255);
        }else{
         $category = "";
        }

       if($request->brand55){
          $brand = implode(",", $request->brand55);
        }else{
         $brand = "";
        }



  if($request->desc1){
   $subcat = implode(",", $request->desc1);
 }else{
  $subcat = "";
 }
 if($request->quan1){
   $quan = implode(",", $request->quan1);
 }else{
  $quan = "";
 }
if($request->price1){
   $price = implode(",", $request->price1);
 }else{
  $price = "";
 }
if($request->unit1){
   $unit = implode(",", $request->unit1);
 }else{
  $unit = "";
 }
 if($request->state12){
   $state = implode(",", $request->state12);
 }else{
  $state = "";
 }  
  if($request->gst1){
   $gst = implode(",", $request->gst1);
 }else{
  $gst = "";
 }  
  if($request->withgst1){
   $withgst = implode(",", $request->withgst1);
 }else{
  $withgst = "";
 }  
  if($request->withoutgst1){
   $withoutgst = implode(",", $request->withoutgst1);
 }else{
  $withoutgst = "";
 } 
 
   if($request->unitprice1){
     $unitprice = implode(",", $request->unitprice1);
  }else{
    $unitprice ="";
  }
  if($request->hsn1){
     $hsn = implode(",", $request->hsn1);
  }else{
    $hsn ="";
  }

      $gstamountinwords = array_sum($request->gst1);
        $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$gstamountinwords.'&token=fshadvjfa67581232'
;
        $response = file_get_contents($url);
        $data = json_decode($response,true);
        $dtow = $data['message'];
       

        $totalamountinwords = array_sum($request->withgst1);
        $url1 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$totalamountinwords.'&token=fshadvjfa67581232'
;
        $response1 = file_get_contents($url1);
        $data1 = json_decode($response1,true);
        $dtow1 = $data1['message'];

        $totalwithoutgst = array_sum($request->withoutgst1);
         $state = $request->state12[0];
         $percent = Gst::where('category',$request->cat255[0])->where('state',$state)->pluck('gstpercent')->first();


     }

else{




      if($request->cat){
   $category = implode(",", $request->cat);
 }else{
  $category = "";
 }

if($request->desc){
   $subcat = implode(",", $request->desc);
 }else{
  $subcat = "";
 }
if($request->quan){
   $quan = implode(",", $request->quan);
 }else{
  $quan = "";
 }
if($request->price){
   $price = implode(",", $request->price);
 }else{
  $price = "";
 }
if($request->unit){
   $unit = implode(",", $request->unit);
 }else{
  $unit = "";
 }
 if($request->state){
   $state = implode(",", $request->state);
 }else{
  $state = "";
 }  
  if($request->gst){
   $gst = implode(",", $request->gst);
 }else{
  $gst = "";
 }  
  if($request->withgst){
   $withgst = implode(",", $request->withgst);
 }else{
  $withgst = "";
 }  
  if($request->withoutgst){
   $withoutgst = implode(",", $request->withoutgst);
 }else{
  $withoutgst = "";
 } 
 
   if($request->unitprice){
     $unitprice = implode(",", $request->unitprice);
  }else{
    $unitprice ="";
  }
  if($request->hsn){
     $hsn = implode(",", $request->hsn);
  }else{
    $hsn ="";
  }
  if($request->brand){
     $brand = implode(",", $request->brand);
  }else{
    $brand ="";
  }


      $gstamountinwords = array_sum($request->gst);
        $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$gstamountinwords.'&token=fshadvjfa67581232'
;
        $response = file_get_contents($url);
        $data = json_decode($response,true);
        $dtow = $data['message'];
       

        $totalamountinwords = array_sum($request->withgst);
        $url1 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$totalamountinwords.'&token=fshadvjfa67581232'
;
        $response1 = file_get_contents($url1);
        $data1 = json_decode($response1,true);
        $dtow1 = $data1['message'];

        $totalwithoutgst = array_sum($request->withoutgst);

      $state = $request->states;
      $percent = $request->gstpercent;


     
}


$data = new  MultipleSupplierInvoice;
$data->order_id =$request->orderid; 
$data->category = $category;
$data->subcat = $subcat;
$data->quantity = $quan;
$data->price = $price;
$data->unit = $unit;
$data->states = $state;
$data->gstamount = $gst;
$data->withgst = $withgst;
$data->withoutgst = $withoutgst;
$data->bill = $request->bill;
$data->ship =$request->ship; 
$data->totalgst = $gstamountinwords;
$data->totalwithgst = $totalamountinwords;
$data->totalwithoutgst = $totalwithoutgst;
$data->req_id = $request->req_id;
$data->suppliergstgst = $request->cgst; 
$data->state = $state;
$data->gstpercent = $percent;
$data->totalamountinwords = $dtow;
$data->gstinwords = $dtow1;
$data->unitprice = $unitprice;
$data->sname = $request->sname;
$data->hsn = $hsn;
$data->brand = $brand;
$data->save();

       
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
                     
                     

                     

                                $ino = MultipleSupplierInvoice::where('id',$data->id)->update([
                                    'lpo'=>$invoiceno,'final'=>$z
                                    
                                ]);
                  

return back()->with('info',"Supllier Purchase  Generated successfully !");
  }

public function multisdhgs(request $request){


      if($request->cat){
   $category = implode(",", $request->cat);
 }else{
  $category = "";
 }

if($request->desc){
   $subcat = implode(",", $request->desc);
 }else{
  $subcat = "";
 }
if($request->quan){
   $quan = implode(",", $request->quan);
 }else{
  $quan = "";
 }
if($request->price){
   $price = implode(",", $request->price);
 }else{
  $price = "";
 }
if($request->unit){
   $unit = implode(",", $request->unit);
 }else{
  $unit = "";
 }
 if($request->state){
   $state = implode(",", $request->state);
 }else{
  $state = "";
 }  
  if($request->gst){
   $gst = implode(",", $request->gst);
 }else{
  $gst = "";
 }  
  if($request->withgst){
   $withgst = implode(",", $request->withgst);
 }else{
  $withgst = "";
 }  
  if($request->withoutgst){
   $withoutgst = implode(",", $request->withoutgst);
 }else{
  $withoutgst = "";
 } 
 
   if($request->unitprice){
     $unitprice = implode(",", $request->unitprice);
  }else{
    $unitprice ="";
  }

      $gstamountinwords = $request->totalgst;
        $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$gstamountinwords.'&token=fshadvjfa67581232'
;
        $response = file_get_contents($url);
        $data = json_decode($response,true);
        $dtow = $data['message'];
       

        $totalamountinwords = $request->totalwithgst;
        $url1 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$totalamountinwords.'&token=fshadvjfa67581232'
;
        $response1 = file_get_contents($url1);
        $data1 = json_decode($response1,true);
        $dtow1 = $data1['message'];

     


$data = new  MultipleSupplierInvoice;
$data->order_id =$request->orderid; 
$data->category = $category;
$data->subcat = $subcat;
$data->quantity = $quan;
$data->price = $price;
$data->unit = $unit;
$data->states = $state;
$data->gstamount = $gst;
$data->withgst = $withgst;
$data->withoutgst = $withoutgst;
$data->bill = $request->bill;
$data->ship =$request->ship; 
$data->totalgst = $request->totalgst;
$data->totalwithgst = $request->totalwithgst;
$data->totalwithoutgst = $request->totalwithoutgst;
$data->req_id = $request->req_id;
$data->suppliergstgst = $request->cgst; 
$data->state = $request->states;
$data->gstpercent = $request->gstpercent;
$data->totalamountinwords = $dtow;
$data->gstinwords = $dtow1;
$data->unitprice = $unitprice;
$data->sname = $request->sname;
$data->save();

       
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
                     
                     

                     

                                $ino = MultipleSupplierInvoice::where('id',$data->id)->update([
                                    'lpo'=>$invoiceno,'final'=>$z
                                    
                                ]);


    $spname = ManufacturerDetail::where('manufacturer_id',$request->sname)->pluck('company_name')->first();
      $cate = Category::where('id',$request->cat[0])->pluck('category_name')->first();
     $gstpercent = Gst::where('category',$cate)->where('state',$request->state[0])->where('cgst','!=',NULL)->pluck('cgst')->first();

     if(count($gstpercent) > 0){
        $cgst = $request->totalgst/2;
        $sgst = $request->totalgst/2;
        $igst = 0;
        $percent = $gstpercent *2;
     }else{
        $cgst = 0;
        $sgst = 0;
        $igst = $request->totalgst;
        $percent = $gstpercent;
     }

     
$data = new Transport;
$data->orderid  = $request->orderid;
$data->mhlpo = $invoiceno;

$data->suppliername =$spname;
$data->supplergst = $request->cgst;
$data->supplierav = $request->totalwithoutgst;

$data->suppliercgst = $cgst;
$data->suppliersgst = $sgst;
$data->supplierigst = $igst;
$data->supplierinvoiceval = $request->totalwithoutgst;
$data->supplierpercent = $percent;
$data->lpid = $data->id;


$data->save();   





return back()->with('info',"Supllier Purchase  Generated successfully !");
  }









public function resetcustomer(request $request){


    $data = CustomerBrands::where('manu_id',$request->manuid)->delete();
   

   return back()->with('Success','reset successfully!');
}
public function updatereport(request $request){
     if($request->user == "All"){
     $data  =UpdatedReport::where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->get();
       $callattend = UpdatedReport::where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->where('quntion',"Call_attended")->count();
     $Busy = UpdatedReport::where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->where('quntion',"Busy")->count();
      $switched = UpdatedReport::where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->where('quntion',"switched_off")->count();
     $notanswer = UpdatedReport::where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->where('quntion',"Call_Not_Answered")->count();
     $notinterest = UpdatedReport::where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->where('quntion',"Not_Instrested")->count();
     
     
       

   }
     else if($request->user){
     $data  =UpdatedReport::where('user_id',$request->user)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->get();
       $callattend = UpdatedReport::where('user_id',$request->user)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->where('quntion',"attend")->count();
     $Busy = UpdatedReport::where('user_id',$request->user)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->where('quntion',"Busy")->count();
      $switched = UpdatedReport::where('user_id',$request->user)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->where('quntion',"switched")->count();
     $notanswer = UpdatedReport::where('user_id',$request->user)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->where('quntion',"notanswer")->count();
     $notinterest = UpdatedReport::where('user_id',$request->user)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->where('quntion',"notinterest")->count();


   }




   else{

     $data = [];
$callattend="";
$Busy="";
$switched="";
$notanswer="";
$notinterest="";
   }

    return view('/updatereport',['data'=>$data,'callattend'=>$callattend,'Busy'=>$Busy,'switched'=>$switched,'notanswer'=>$notanswer,'notinterest'=>$notinterest]);
}
function usingbrand(request $request){

  if( $request->brand && !$request->type){

     $dataids =CustomerBrands::where('brand','LIKE','%'.$request->brand.'%')->pluck('manu_id');
        
        

   $data = Manufacturer::withTrashed()->whereIn('id',$dataids)->with('customerbrands')->get();
  }elseif($request->brand && $request->type){

    $dataids =CustomerBrands::where('brand','LIKE','%'.$request->brand.'%')->pluck('manu_id');
       
    $data = Manufacturer::withTrashed()->where('manufacturer_type',$request->type)->whereIn('id',$dataids)->with('customerbrands')->get();
  }
  else{

  $data = [];
  }
  
$brand = brand::where('id',$request->brand)->pluck('brand')->first();


  return view('/usingbrand',['data'=>$data,'brand'=>$brand,'bro'=>$request->brand]);
   }

}
