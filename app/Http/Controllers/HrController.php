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
use App\Interview;
use App\Manager_Deatils;
use App\FirstRound;
use App\SecoundRound;
use Auth;
use App\ContractorDetails;
use DB;
use App\SiteEngineerDetails;
use App\Builder;

use App\EmployeeDetails;
use App\Mail\FieldLoginApprove;
use App\Mail\FieldLoginReject;
use App\Mprocurement_Details;
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
use App\OwnerDetails;
use App\WardAssignment;
use App\SubWard;
use App\Ward;
use App\WardMap;
use App\Salescontact_Details;
use App\Mowner_Deatils;
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
use Illuminate\Support\Facades\Storage;
date_default_timezone_set("Asia/Kolkata");


class HrController extends Controller
{
    
 
  public function delete(){

    return view('errors.deleteddata');
  }
  public function default(){
    return view('/errors.default');
  }
  public function getgstvalue(request $request ){
        
        $cat = Category::where('id',$request->cat)->pluck('category_name')->first();

        


  	  $value = Gst::where('state',$request->state)->where('category',$cat)->get();

  	   return response()->json($value);
  }
   public function getgstvalue1(request $request ){
        
        
        if($request->state == 1){

      $value = Gst::where('state',$request->state)->where('category',$request->cat)->pluck('gstpercent')->first();
       

        }else{
      $value = Gst::where('state',$request->state)->where('category',$request->cat)->pluck('gstpercent')->first();
        

        }



       return response()->json($value);
  }

   public function getgstvalue12(request $request ){
        
        
        $value = Gst::where('state',$request->state)->where('category',$request->cat)->get();

       return response()->json($value);
  } 
 
 
  public function customergeneration(request $request){
           
                    
    $check = CustomerDetails::where('customer_id',$request->customerid)->count();
      $subwardid = Subward::where('sub_ward_name',$request->subward)->pluck('id')->first();
      if($request->project != null){
      $latitude = SiteAddress::where('project_id',$request->project)->pluck('latitude')->first();
      $long = SiteAddress::where('project_id',$request->project)->pluck('longitude')->first();

      }else{
        $latitude = Manufacturer::where('id',$request->manuid)->pluck('latitude')->first();
        $long = Manufacturer::where('id',$request->manuid)->pluck('longitude')->first();
      }
      if($check == 0){
          $data = new CustomerDetails;
          $data->first_name = $request->customername;
          $data->mobile_num = $request->number;
          $data->customer_type = $request->customertype;
          $data->sub_customer_type = $request->sub_customertype;
          $data->sub_ward_id =  $subwardid;
          $data->latitude = $latitude;
          $data->longitude = $long; 
          $data->save();
           $year = date('Y');
            $country_code = Country::pluck('country_code')->first();
            $zone = Zone::pluck('zone_number')->first();
            $invoiceno = "MH_".$country_code."_".$zone."_C".$data->id;
         
        $ino = CustomerDetails::where('id',$data->id)->update([
            'customer_id'=>$invoiceno
        ]);

        $customerid = CustomerDetails::where('id',$data->id)->pluck('customer_id')->first();
        }
        else
        {
          $customerid =$request->customerid;
          $ino = CustomerDetails::where('customer_id',
          $request->customerid)->update([
            'first_name' => $request->customername,
            'mobile_num' => $request->number,
            'customer_type' => $request->customertype,
            'sub_customer_type' => $request->sub_customertype
            
            ]); 
         
      }
     $customerid =  $customerid;
    
    $supplierid =SuplierDetails::where('suplier_id',$request->supplierid)->count();
    if($supplierid == 0){
       $suplierdata = new SuplierDetails;
       $suplierdata->suplier_id = $request->supplierid;
       $suplierdata->supplier_firm_name = $request->suppliername;
       $suplierdata->save();
    }
    if($request->manuid != null){

          $smanu = SuplpierManufacture::where('manufacturer_id',$request->manuid)->count();
          if($smanu == 0){

             $sdata = new SuplpierManufacture;
             $sdata->customer_id = $customerid;
             $sdata->supplier_id = $request->supplierid;
             $sdata->manufacturer_id = $request->manuid;
             $sdata->save();
          }else{
                 SuplpierManufacture::where('manufacturer_id',$request->manuid)->update([
                   'customer_id' => $request->customerid,
                   'supplier_id' => $request->supplierid,
                   'manufacturer_id' => $request->manuid
                 ]);

          }
      }
         $sorder = SupplierOrder::where('order_id',$request->orderid)->count();
               
           if($sorder == 0){
              $sorderdata = new SupplierOrder;
              $sorderdata->supplier_id = $request->supplierid;
              $sorderdata->order_id = $request->orderid;
              $sorderdata->customer_id = $customerid;
              $sorderdata->project_id = $request->project;
             
              $sorderdata->save();

           }else{
            SupplierOrder::where('order_id',$request->orderid)->update([

              'supplier_id' => $request->supplierid,
              'order_id' => $request->orderid,
              'customer_id' => $request->customerid,
              'project_id' => $request->project,
              'brand'=>$request->bid

            ]);
           }
       if($request->project != null){

         $sproject = SupplierProject::where('project_id',$request->project)->count();
             if($sproject == 0){
                 $sprojectdata = new SupplierProject;
                 $sprojectdata->supplier_id = $request->supplierid;
                 $sprojectdata->project_id = $request->project;
                 $sprojectdata->customer_id = $customerid;
                 $sprojectdata->save();

             }else{
                 SupplierProject::where('project_id',$request->project)->update([
                 'supplier_id' => $request->supplierid,
                 'project_id' => $request->project,
                 'customer_id' => $request->customerid
                 ]);
             }  
 }


     $sgstno = SupplierGst::where('suplier_id',$request->supplierid)->where('gst_number',$request->suppliergstnumber)->count();

       if($sgstno == 0){
           $suppliergst = new SupplierGst;
           $suppliergst->suplier_id = $request->supplierid;
           $suppliergst->gst_number = $request->suppliergstnumber;
           $suppliergst->state = $request->stateid;
           $suppliergst->save();
       }else{
         SupplierGst::where('suplier_id',$request->supplierid)->where('gst_number',$request->suppliergstnumber)->update([
           'suplier_id' => $request->supplierid,
           'gst_number' => $request->suppliergstnumber,
           'state'=>$request->stateid
         ]);
       }

    $checkorder = CustomerOrder::where('order_id',$request->orderid)->count();
     if($checkorder == 0){
        $orderdetails = new CustomerOrder;
        $orderdetails->customer_id = $customerid;
        $orderdetails->order_id = $request->orderid;
        $orderdetails->project_id = $request->project;
        $orderdetails->manu_id = $request->manuid;
        $orderdetails->orderconfirmname = $request->orderconfirmname;
        $orderdetails->orderconvertedname = $request->orderconvertedname;
        $orderdetails->orderotherexpenses = $request->orderotherexpenses;
        $orderdetails->orderotherexpensesremark = $request->orderotherexpensesremark;
        $orderdetails->deliverydate = $request->deliverydate;
        $orderdetails->prifitwithgst = $request->prifitwithgst;
        $orderdetails->profitaftergst = $request->profitaftergst;
        $orderdetails->supplier_id = $request->supplierid;
        $orderdetails->save();
     }else{
         CustomerOrder::where('order_id',$request->orderid)->update([
        'customer_id' => $request->customerid,
        'order_id' => $request->orderid,
        'project_id' => $request->project,
        'manu_id' => $request->manuid,
        'orderconfirmname' => $request->orderconfirmname,
        'orderconvertedname' => $request->orderconvertedname,
        'orderotherexpenses' => $request->orderotherexpenses,
        'orderotherexpensesremark' => $request->orderotherexpensesremark,
        'deliverydate' => $request->deliverydate,
        'prifitwithgst' => $request->prifitwithgst,
        'profitaftergst' =>$request->profitaftergst,
        'supplier_id' =>$request->supplierid
       
         ]);
        
     }
     if($request->project != null){

 $projects = CustomerProject::where('project_id',$request->project)->count();

  if($projects == 0){

     $projectdata = new CustomerProject;
     $projectdata->customer_id = $customerid;
     $projectdata->project_id = $request->project;
     $projectdata->ward = $subwardid;
     $projectdata->save();
  }else{
    CustomerProject::where('project_id',$request->project)->update([
     'customer_id' => $request->customerid,
     'project_id' => $request->project,
     'ward' => $subwardid
    ]);
  }
     }

  if($request->manuid != null){
  $manus = CustomerManufacturer::where('manufacturer_id',$request->manuid)->count();
       if($manus == 0){
         $manudata = new CustomerManufacturer;
         $manudata->customer_id =$customerid;
         $manudata->manufacturer_id = $request->manuid;
         $manudata->ward=$subwardid;
         $manudata->save();
       }else{
        CustomerManufacturer::where('manufacturer_id',$request->manuid)->update([
         'customer_id' =>$request->customerid,
         'manufacturer_id' => $request->manuid,
         'ward'=>$subwardid

        ]);
        
       }
  }
 $custgst = GstTable::where('gst_number',$request->customergstnumber)->count();
  if($custgst == 0){

       $gstdata = new GstTable;
       $gstdata->customer_id = $customerid;
       $gstdata->gst_number = $request->customergstnumber;
       $gstdata->state = $request->district;
       $gstdata->save();
  }else{
    GstTable::where('gst_number',$request->customergstnumber)->update([
        'customer_id' => $request->customerid,
       'gst_number' => $request->customergstnumber,
       'state' =>$request->district
    ]);
  }
   
   $customerinvoice = CustomerInvoice::where('invoiceno',$request->invoiceno)->count(); 

   //   if($request->invoicefile != null){
        
   //   $invoicefile = $request->file('invoicefile');
   //   $imageFileName1 = time() . '.' . $invoicefile->getClientOriginalExtension();
   //   $s3 = \Storage::disk('azure');
   //   $filePath = '/customerInvoice/' . $imageFileName1;
   //   $s3->put($filePath, file_get_contents($invoicefile), 'public'); 

   // }else{
   //   $imageFileName1 = "";
   // }


     // if($request->customerpaymentrefimg != null){
     // $customerpaymentrefimage = $request->file('customerpaymentrefimg');
     // $imageFileName2 = time() . '.' . $customerpaymentrefimage->getClientOriginalExtension();
     // $s3 = \Storage::disk('azure');
     // $filePath = '/customerpayment/' . $imageFileName2;
     // $s3->put($filePath, file_get_contents($customerpaymentrefimage), 'public');

     // }else{
     //  $imageFileName2 = "";
     // }
  
         if($request->customerpaymentrefimg != NULL){
      $i = 0;
    $imageFileName2 = "";
        foreach($request->customerpaymentrefimg as $customerpaymentrefimg){

     $imageName2 = $customerpaymentrefimg;
     $imageFileName = $i.time() . '.' . $imageName2->getClientOriginalExtension();
     $s3 = \Storage::disk('azure');
     $filePath = '/customerpayment/' . $imageFileName;
     $s3->put($filePath, file_get_contents($imageName2), 'public');


          
            // $imageName2 = $i.time().'.'.$oApprove->getClientOriginalExtension();
            // $oApprove->move(public_path('projectImages'),$imageName2);
            if($i == 0){
                $imageFileName2 .= $imageFileName;
            }else{
                $imageFileName2 .= ", ".$imageFileName;
            }
            $i++;
        }
    }else{
      $imageFileName2 = "";
    }
















   if($request->ewaybillimg != null){
      $ewaybill = $request->file('ewaybillimg');
     $imageFileName3 = time() . '.' . $ewaybill->getClientOriginalExtension();
     $s3 = \Storage::disk('azure');
     $filePath = '/customerpayment/' . $imageFileName3;
     $s3->put($filePath, file_get_contents($ewaybill), 'public');

     
   }else{
     $imageFileName3="";
   }

   if($request->mhpaymentrefimg != null){
      $mhpaymentrefimg = $request->file('mhpaymentrefimg');
     $imageFileName4 = time() . '.' . $mhpaymentrefimg->getClientOriginalExtension();
     $s3 = \Storage::disk('azure');
     $filePath = '/mhpaymentrefimg/' . $imageFileName4;
     $s3->put($filePath, file_get_contents($mhpaymentrefimg), 'public');
    
  }else{
    $imageFileName4 = '';
  }

     
    if($request->invoicefile){
      $i = 0;
    $imageFileName1 = "";
        foreach($request->invoicefile as $invoicefile){

     $imageName2 = $invoicefile;
     $imageFileName = $i.time() . '.' . $imageName2->getClientOriginalExtension();
     $s3 = \Storage::disk('azure');
     $filePath = '/customerInvoice/' . $imageFileName;
     $s3->put($filePath, file_get_contents($imageName2), 'public');


          ;
            // $imageName2 = $i.time().'.'.$oApprove->getClientOriginalExtension();
            // $oApprove->move(public_path('projectImages'),$imageName2);
            if($i == 0){
                $imageFileName1 .= $imageFileName;
            }else{
                $imageFileName1 .= ", ".$imageFileName;
            }
            $i++;
        }
    }





    
  

   if($customerinvoice == 0){
      $invoicedata = new CustomerInvoice;
      $invoicedata->customer_id = $customerid;
      $invoicedata->order_id = $request->orderid;
      $invoicedata->invoiceno = $request->invoiceno;
      $invoicedata->invoicedate = $request->invoicedate;
      $invoicedata->category = $request->category;
      $invoicedata->modeofqunty = $request->modeofqunty;
      $invoicedata->invoicenoqnty = $request->invoicenoqnty;
      $invoicedata->mhunitprice = $request->mhunitprice;
      $invoicedata->mhInvoiceamount = $request->mhInvoiceamount;
      $invoicedata->basevalue = $request->basevalue;
      $invoicedata->mhpaymentremark = $request->mhpaymentremark;
      $invoicedata->mhpaymentrefimage = $imageFileName4;
      $invoicedata->mhpaymentref = $request->mhpaymentref;
      $invoicedata->customerpaymentrefimage = $imageFileName2;
      $invoicedata->ewaybill = $imageFileName3;
      $invoicedata->ewaybillno = $request->ewaybill;
      $invoicedata->customerpaymentrefno = $request->customerpaymentref;
      $invoicedata->customerpaymentremark = $request->customerpaymentremark;
      $invoicedata->custmodeofgst = $request->custmodeofgst;
      $invoicedata->customergstpercent = $request->customergstpercent;
      $invoicedata->customergstamount = $request->customergstamount;
      $invoicedata->invoicefile = $imageFileName1;
      $invoicedata->supplier_id = $request->supplierid;
      $invoicedata->brand = $request->bid;


      $invoicedata->save();
   }else{
    CustomerInvoice::where('invoiceno',$request->invoiceno)->update([
     'customer_id' => $request->customerid,
      'order_id' => $request->orderid,
      'invoiceno' => $request->invoiceno,
      'invoicedate' => $request->invoicedate,
      'category' => $request->category,
      'modeofqunty' => $request->modeofqunty,
      'invoicenoqnty' => $request->invoicenoqnty,
      'mhunitprice' => $request->mhunitprice,
      'mhInvoiceamount' => $request->mhInvoiceamount,
      'basevalue' =>$request->basevalue,
      'mhpaymentremark' => $request->mhpaymentremark,
      'mhpaymentrefimage' => $imageFileName4,
      'customerpaymentrefimage' => $imageFileName2,
      'ewaybill' => $imageFileName3,
      'mhpaymentremark'=> $request->customerpaymentremark,
      'custmodeofgst' => $request->custmodeofgst,
      'customergstpercent' => $request->customergstpercent,
      'customergstamount' => $request->customergstamount,
      'invoicefile'=>$imageFileName1,
      'brand'=>$request->bid,
      'ewaybillno'=>$request->ewaybill,'mhpaymentref'=>$request->mhpaymentref,'customerpaymentrefno'=>$request->customerpaymentref,'supplier_id'=>$request->supplierid
    ]);
   }
   
  $delivery = customer_delivery::where('invoiceno',$request->invoiceno)->count();

  if($request->customeraudio != null){
       $customeraudio = $request->file('customeraudio');
     $imageFileName22 = time() . '.' . $customeraudio->getClientOriginalExtension();
     $s3 = \Storage::disk('azure');
     $filePath = '/customeraudio/' . $imageFileName22;
     $s3->put($filePath, file_get_contents($customeraudio), 'public');
  
   }else{
     $imageFileName22 = "";
   }
     
     if($request->truckimage != null){
     $truckimage = $request->file('truckimage');
     $imageFileName33 = time() . '.' . $truckimage->getClientOriginalExtension();
     $s3 = \Storage::disk('azure');
     $filePath = '/truckimage/' . $imageFileName33;
     $s3->put($filePath, file_get_contents($truckimage), 'public');
     }else{
      $imageFileName33 = "";
     }
  
   if($request->truckvideo != null){
     $truckvideo = $request->file('truckvideo');
     $imageFileName44 = time() . '.' . $truckvideo->getClientOriginalExtension();
     $s3 = \Storage::disk('azure');
     $filePath = '/truckvideo/' . $imageFileName44;
     $s3->put($filePath, file_get_contents($truckvideo), 'public');
       
   }else{
     $imageFileName44="";
   }



    if($delivery == 0){

        $deliverydata = new customer_delivery;
        $deliverydata->customer_id = $customerid;
        $deliverydata->invoiceno = $request->invoiceno;
        $deliverydata->order_id = $request->orderid;
        $deliverydata->customersatisfaction = $request->customersatisfaction;
        $deliverydata->yes = $request->yes;
        $deliverydata->customeraudio = $imageFileName22;
        $deliverydata->customerremark = $request->customerremark;
        $deliverydata->deliverylocation = $request->deliverylocation;
        $deliverydata->district = $request->district;
        $deliverydata->postalcode = $request->postalcode;
        $deliverydata->trucknumber = $request->trucknumber;
        $deliverydata->truckimage = $imageFileName33;
        $deliverydata->truckvideo = $imageFileName44;
        $deliverydata->generalremark = $request->generalremark;
        $deliverydata->supplier_id = $request->supplierid;

        $deliverydata->save();


    }else{
       
       customer_delivery::where('invoiceno',$request->invoiceno)->update([
                        'customer_id' => $request->customerid,
                        'invoiceno' => $request->invoiceno,
                        'order_id' => $request->orderid,
                        'customersatisfaction' => $request->customersatisfaction,
                        'yes' => $request->yes,
                        'customeraudio' => $imageFileName22,
                        'customerremark' => $request->customerremark,
                        'deliverylocation' => $request->deliverylocation,
                        'district' => $request->district,
                        'postalcode' => $request->postalcode,
                        'trucknumber' => $request->trucknumber,
                        'truckimage' => $imageFileName33,
                        'truckvideo' => $imageFileName44,
                        'generalremark' => $request->generalremark,
                        'supplier_id' =>$request->supplierid
                       ]);

        }

   //     if($request->supplierinvoice != null){
   //    $supplierinvoiceimg = $request->file('supplierinvoice');
   //   $imageFileName = time() . '.' . $supplierinvoiceimg->getClientOriginalExtension();
   //   $s3 = \Storage::disk('azure');
   //   $filePath = '/supplierinvoicedata/' . $imageFileName;
   //   $s3->put($filePath, file_get_contents($supplierinvoiceimg), 'public');
     
   // }else{
   //   $imageFileName="";
   // }

              if($request->supplierinvoice){
           $i = 0;
             $imageFileName23 = "";
             foreach($request->supplierinvoice as $supplierinvoice){

          $imageName2 = $supplierinvoice;
          $imageFileName = $i.time() . '.' . $imageName2->getClientOriginalExtension();
          $s3 = \Storage::disk('azure');
          $filePath = '/supplierinvoicedata/' . $imageFileName;
          $s3->put($filePath, file_get_contents($imageName2), 'public');


               ;
                 // $imageName2 = $i.time().'.'.$oApprove->getClientOriginalExtension();
                 // $oApprove->move(public_path('projectImages'),$imageName2);
                 if($i == 0){
                     $imageFileName23 .= $imageFileName;
                 }else{
                     $imageFileName23.= ", ".$imageFileName;
                 }
                 $i++;
             }
         }else{

              $imageFileName23 = "";
           }
         
       

         

         


        $supplierinvoice = SupplierInvoicedata::where('invoiceno',$request->invoiceno)->count();
          if($supplierinvoice == 0){
              $supplierinvoicedata = new SupplierInvoicedata;
              $supplierinvoicedata->customer_id = $customerid;
              $supplierinvoicedata->supplier_id = $request->supplierid;
              $supplierinvoicedata->order_id = $request->orderid;
              $supplierinvoicedata->invoiceno = $request->invoiceno;
              $supplierinvoicedata->supplierinvoicedate = $request->supplierinvoicedate;
              $supplierinvoicedata->supplierinvoicenumber = $request->supplierinvoicenumber;
              $supplierinvoicedata->supplierinvoiceamount = $request->supplierinvoiceamount;
              $supplierinvoicedata->smodeofgst = $request->smodeofgst;
              $supplierinvoicedata->suppliergstamount = $request->suppliergstamount;
              $supplierinvoicedata->supplierinvoice  = $imageFileName23;
              $supplierinvoicedata->state = $request->stateid;
              $supplierinvoicedata->save();


          }else{
            SupplierInvoicedata::where('invoiceno',$request->invoiceno)->update([
              'customer_id' => $request->customerid,
              'order_id' => $request->orderid,
              'invoiceno' => $request->invoiceno,
              'supplierinvoicedate' => $request->supplierinvoicedate,
              'supplierinvoicenumber' => $request->supplierinvoicenumber,
              'supplierinvoiceamount' => $request->supplierinvoiceamount,
              'smodeofgst' =>$request->smodeofgst,
              'suppliergstamount' => $request->suppliergstamount,
              'supplierinvoice'  => $imageFileName23,
              'supplier_id'=>$request->supplierid,
              'state'=>$request->stateid
      

            ]);
          }

return back()->with('customerid',$customerid);
}
  public function getsupliers(request $request){
  
      $d = floatval($request->cat);
     $supliers = DB::table('manufacturer_details')->where('state',$d)->get();

     return response()->json($supliers);
  }
  public function getsuplierid(request $request){
      $d = floatval($request->cat);
       $f = floatval($request->brand);
       $data = DB::table('manufacturer_details')->where('state',$d)->where('manufacturer_id',$f)->pluck('supplier_id')->first();

       return response()->json($data);

  }
  public function getdistict(request $request){
      $d = floatval($request->cat);
       
         $data = DB::table('states_dists')->where('parent_id',$d)->get();
         return response()->json($data);

  }
  public function getpostalcode(request $request){
      $d = floatval($request->s);
       $fa = DB::table('states_dists')->where('id',$d)->pluck('pin')->first();
         return response()->json($fa);
  }
   public function getcustomerid(request $request){
      $d = floatval($request->number);
        
       $fa = CustomerDetails::where('mobile_num',$request->number)->pluck('customer_id')->first();
       $name = CustomerDetails::where('mobile_num',$request->number)->pluck('first_name')->first();
         $gst = GstTable::where('customer_id',$fa)->pluck('gst_number')->first();
         return response()->json(['fa'=>$fa,'name'=>$name,'gst'=>$gst]);
  }
  public function customermap(request $request){

    $wardMaps = null;
    $projects = null;
    $multisubward = null;
    if($request->subward == "All" && $request->quality == "yup" && $request->type == "Project" && $request->wards && $request->manutype=="---select---"){

              $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
        $wardMaps = WardMap::where('ward_id',$request->wards)->first();
        if($wardMaps == null ){
            $wardMaps = "None";
        }
        $projects = ProjectDetails::leftJoin('site_addresses','project_details.project_id','site_addresses.project_id')
                    ->select('site_addresses.*','project_details.quality','project_details.*')
                    ->whereIn('project_details.sub_ward_id',$subwards)
                    ->get();
    }

    else if($request->subward == "All" && $request->quality && $request->type == "Project" && $request->wards && $request->manutype=="---select---"){

        $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
        $wardMaps = WardMap::where('ward_id',$request->wards)->first();
        if($wardMaps == null ){
            $wardMaps = "None";
        }
        $projects = ProjectDetails::leftJoin('site_addresses','project_details.project_id','site_addresses.project_id')
                    ->select('site_addresses.*','project_details.quality','project_details.*')
                    ->where('project_details.quality',$request->quality)
                    ->whereIn('project_details.sub_ward_id',$subwards)
                    ->get();
    }

    else if($request->subward  && $request->quality == "yup" && $request->type == "Project" && $request->wards && $request->manutype=="---select---"){
        $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
        $wardMaps = WardMap::where('ward_id',$request->wards)->first();
        if($wardMaps == null ){
            $wardMaps = "None";
        }
        $projects = ProjectDetails::leftJoin('site_addresses','project_details.project_id','site_addresses.project_id')
                    ->select('site_addresses.*','project_details.quality','project_details.*')
                    ->whereIn('project_details.sub_ward_id',$subwards)
                    ->get();
    }
else if($request->wards && $request->quality && $request->type == "Project" && $request->subward && $request->manutype=="---select---"){

      $subwards = SubWard::where('id',$request->subward)->pluck('id')->toArray();
      $wardMaps = SubWardMap::where('sub_ward_id',$request->subward)->first();
      if($wardMaps == null ){
          $wardMaps = "None";
      }
      $projects = ProjectDetails::leftJoin('site_addresses','project_details.project_id','site_addresses.project_id')
                  ->select('site_addresses.*','project_details.quality','project_details.*')
                  ->where('project_details.quality',$request->quality)
                  ->whereIn('project_details.sub_ward_id',$subwards)
                  ->get();
    
    
                }else if( $request->subward == "All" && $request->quality=="yup" && $request->type == "Manufacturer" && $request->manutype=="All" && $request->wards){

                  $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
                  $wardMaps = WardMap::where('ward_id',$request->wards)->first();
                  if($wardMaps == null ){
                      $wardMaps = "None";
                  }
                            $projects = Manufacturer::whereIn('sub_ward_id',$subwards)
                           
                            ->get();
                           
                           
                        }else if( $request->subward == "All" && $request->quality && $request->type == "Manufacturer" && $request->manutype=="All" && $request->wards){

                          $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
                          $wardMaps = WardMap::where('ward_id',$request->wards)->first();
                          if($wardMaps == null ){
                              $wardMaps = "None";
                          }
                            $projects = Manufacturer::whereIn('sub_ward_id',$subwards)
                           
                            ->get();
                            
                           
                        }
       else  if($request->subward == "All" && $request->quality && $request->type == "Manufacturer" && $request->wards && $request->manutype=="---select---"){

        $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
        $wardMaps = WardMap::where('ward_id',$request->wards)->first();
        if($wardMaps == null ){
            $wardMaps = "None";
        }
                  $projects = Manufacturer::where('quality',$request->quality)
                  ->whereIn('sub_ward_id',$subwards)
                  ->get();
              }
  else if($request->wards && $request->quality && $request->type == "Manufacturer" && $request->subward && $request->manutype=="---select---"){

                $subwards = SubWard::where('id',$request->subward)->pluck('id')->toArray();
                $wardMaps = SubWardMap::where('sub_ward_id',$request->subward)->first();
                if($wardMaps == null ){
                    $wardMaps = "None";
                }
                $projects = Manufacturer::where('quality',$request->quality)
                ->whereIn('sub_ward_id',$subwards)
                ->get();
              
                                }
          else if($request->wards && $request->quality == "yup" && $request->type == "Manufacturer" && $request->subward == "All" && $request->manutype ){

          $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
              $wardMaps = WardMap::where('ward_id',$request->wards)->first();
              if($wardMaps == null ){
                  $wardMaps = "None";
              }
          $projects = Manufacturer::whereIn('sub_ward_id',$subwards)
          ->where('manufacturer_type',$request->manutype)
          ->get();
          }
    
  else  if( $request->subward == "yup" && $request->quality && $request->type == "Manufacturer" && $request->manutype && $request->wards){

                            $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
                            $wardMaps = WardMap::where('ward_id',$request->wards)->first();
                            if($wardMaps == null ){
                                $wardMaps = "None";
                            }
                            $projects = Manufacturer::where('quality',$request->quality)
                            ->whereIn('sub_ward_id',$subwards)
                            ->where('manufacturer_type',$request->manutype)
                            ->get();
                            
                           
                        }
                       
  else if($request->wards && $request->quality && $request->type == "Manufacturer" && $request->subward && $request->manutype ){
          
                          $subwards = SubWard::where('id',$request->subward)->pluck('id')->toArray();
                          $wardMaps = SubWardMap::where('sub_ward_id',$request->subward)->first();
                          if($wardMaps == null ){
                              $wardMaps = "None";
                          }
                          $projects = Manufacturer::where('quality',$request->quality)
                          ->whereIn('sub_ward_id',$subwards)
                          ->where('manufacturer_type',$request->manutype)
                          ->get();
                        
        }
        

          else if($request->wards && $request->quality = "All" && $request->type == "Manufacturer" && $request->subward && $request->manutype ){
          
            $subwards = SubWard::where('id',$request->subward)->pluck('id')->toArray();
            $wardMaps = SubWardMap::where('sub_ward_id',$request->subward)->first();
            if($wardMaps == null ){
                $wardMaps = "None";
            }
            $projects = Manufacturer::whereIn('sub_ward_id',$subwards)
            ->where('manufacturer_type',$request->manutype)
            ->get();
          
  
}


              
    else{
        $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
        $wardMaps = WardMap::where('ward_id',$request->wards)->first();
        if($wardMaps == null ){
            $wardMaps = "None";
        }
        $projects = Manufacturer::where('quality',$request->quality)
                    ->whereIn('sub_ward_id',$subwards)
                    ->get();
    }
   
            
    
    if($request->custtype){
      if($request->wards && $request->subward=="All" && $request->custtype=="All"){
   
      $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
      $wardMaps = WardMap::where('ward_id',$request->wards)->first();
      if($wardMaps == null ){
          $wardMaps = "None";
      }
       $projects = CustomerDetails::whereIn('sub_ward_id',$subwards)->get(); 

    }else if($request->wards  && $request->subward && $request->custtype=="All"){
          
    $subwards = SubWard::where('id',$request->subward)->pluck('id')->toArray();
    $wardMaps = SubWardMap::where('sub_ward_id',$request->subward)->first();
    if($wardMaps == null ){
        $wardMaps = "None";
    }
    $projects = CustomerDetails::whereIn('sub_ward_id',$subwards)->get(); 
    
  
              }
     if($request->wards && $request->subward="All" && $request->custtype && $request->subcustname){
      $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
      $wardMaps = WardMap::where('ward_id',$request->wards)->first();
      
      if($wardMaps == null ){
          $wardMaps = "None";
      }
       $projects = CustomerDetails::whereIn('sub_ward_id',$subwards)->where('sub_customer_type',$request->subcustname)->get(); 
           
    } 
    if($request->wards && $request->subward && $request->custtype && $request->subcustname){
      $subwards = SubWard::where('id',$request->subward)->pluck('id')->toArray();
    $wardMaps = SubWardMap::where('sub_ward_id',$request->subward)->first();
      
      if($wardMaps == null ){
          $wardMaps = "None";
      }
       $projects = CustomerDetails::where('sub_ward_id',$subwards)->where('sub_customer_type',$request->subcustname)->get(); 
         
    } 
    if($request->wards && $request->subward && $request->custtype && $request->subcustname == "All"){
      $subwards = SubWard::where('id',$request->subward)->pluck('id')->toArray();
    $wardMaps = SubWardMap::where('sub_ward_id',$request->subward)->first();
      
      if($wardMaps == null ){
          $wardMaps = "None";
      }
       $id = CustomerType::where('cust_type_id',$request->custtype)->pluck('id')->first();
       $projects = CustomerDetails::where('sub_ward_id',$subwards)->where('customer_type',$id)->get(); 
         
    } 
     if($request->wards && $request->subward="All" && $request->custtype && $request->subcustname == "All"){
     $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
      $wardMaps = WardMap::where('ward_id',$request->wards)->first();
      if($wardMaps == null ){
          $wardMaps = "None";
      }
      $id = CustomerType::where('cust_type_id',$request->custtype)->pluck('id')->first();
       $projects = CustomerDetails::whereIn('sub_ward_id',$subwards)->where('customer_type',$id)->get(); 
        
    }

  }
  $wards = Ward::all();
  $zone = Zone::all();
  $customertype = CustomerType::where('sub_customer_id',NULL)->get();

  return view('customermap',['wardMaps'=>$wardMaps,'projects'=>$projects,'wards'=>$wards,'zone'=>$zone,'multisubward'=>$multisubward,'customertype'=>$customertype]);
}
public function customerprojects(request $request){
  $number = CustomerDetails::where('customer_id',$request->customer_id)->pluck('mobile_num')->first();
 $projectids = CustomerProject::where('customer_id',$request->customer_id)->pluck('project_id');


 $p = ProcurementDetails::where('procurement_contact_no',$number)->pluck('project_id')->toArray();
 $pi = ContractorDetails::where('contractor_contact_no',$number)->pluck('project_id')->toArray();
 $pid = Builder::where('builder_contact_no',$number)->pluck('project_id')->toArray();

 $pids = SiteEngineerDetails::where('site_engineer_contact_no',$number)->pluck('project_id')->toArray();
  
 $pidss = OwnerDetails::where('owner_contact_no',$number)->pluck('project_id')->toArray();

      $merge = array_merge($p,$pi, $pid,$pids,$pidss);
      $filtered = array_unique($merge);

 $projects = ProjectDetails::whereIn('project_id',$projectids)
 ->where('quality','!=',"Fake")
 ->orWhereIn('project_id',$filtered)
 ->with('siteaddress','procurementdetails','upuser','subward')
 ->withTrashed()
 ->get();

    $mids1 = Mprocurement_Details::where('contact',$number)->pluck('manu_id')->toArray();
    $mids2 = Mowner_Deatils::where('contact',$number)->pluck('manu_id')->toArray();
    $mids3 = Salescontact_Details::where('contact',$number)->pluck('manu_id')->toArray();
    $mids4 = Manager_Deatils::where('contact',$number)->pluck('manu_id')->toArray();

     
     $merges = array_merge($mids1,$mids2,$mids3,$mids4);
      $filtereds= array_unique($merges);

   $manuids = CustomerManufacturer::where('customer_id',$request->customer_id)->pluck('manufacturer_id');
 

 $manufacturer = Manufacturer::whereIn('id',$manuids)->orWhereIn('id',$filtereds)->with('proc','user1')->withTrashed()->get();

 $enqu = Requirement::whereIn('project_id',$projectids)->orWhereIn('project_id',$filtered)->pluck('id')->toarray();

  
  $enq = Requirement::whereIn('manu_id',$manuids)->orWhereIn('id',$filtereds)->pluck('id')->toarray();
 
    
  
   $enquirys =array_filter(array_merge($enqu,$enq));

  
 
   $enquiry = Requirement::whereIn('id',$enquirys)->with('user')->withTrashed()->get();
  $enquiry_user = Requirement::whereIn('id',$enquirys)->with('user')->withTrashed()->pluck('generated_by');
  

    $or = DB::table('orders')->whereIn('project_id',$projectids)->orWhereIn('project_id',$filtered)->pluck('id');
    $sr = DB::table('orders')->whereIn('manu_id',$manuids)->orWhereIn('id',$filtereds)->pluck('id');


 $neworders = CustomerInvoice::where('customer_id',$request->customer_id)->pluck('order_id')->toarray();

 $order = DB::table('orders')->whereIn('id',$neworders)->get();
  $order1 = [];
 

 $mh = CustomerInvoice::where('customer_id',$request->customer_id)->get();

 
 $total1 = CustomerInvoice::where('customer_id',$request->customer_id)->pluck('mhInvoiceamount')->sum();

  return view('/contactnumer',['projects'=>$projects,'$enquiry_user'=>$enquiry_user,'manufacturer'=>$manufacturer,'enquiry'=>$enquiry,'order'=>$order,'order1'=>$order1,'mh'=>$mh,'sumtotal'=>$total1]);



}
public function customerdetailslist(request $request)
{
  
     $check = Assigncustomlist::where('user_id',$request->user)->count();
         if($request->number){
            $y = implode(",",$request->number);
         } else{
          $y="null";
         }
      
  
     if($check == 0){

        $data = new Assigncustomlist;
        $data->user_id = $request->user;
        $data->cust_phone = $y;
        $data->save();
     }else{
         if($request->number){
            $y = implode(",",$request->number);
         } else{
          $y="null";
         }
         Assigncustomlist::where('user_id',$request->user)->update(['cust_phone'=>$y]);
     }

return back();

}
public function customerlist(request $request){

     $project = Assigncustomlist::where('user_id',Auth::user()->id)->pluck('cust_phone')->first();
    
     $projects  = explode(",",$project);


     return view('/customerlist',['projects'=>$projects]);
}
public function bankloan(request $request){

  if($request->status == "project"){

  $projects = ProjectDetails::where('interested_in_loan','LIKE','Yes')->where('quality','!=',"FAKE")->get();
}else if($request->status == "manufacturer"){
  $projects = Manufacturer::where('interested_in_loan','LIKE','Yes')->where('quality','!=',"FAKE")->get();
   
}else{

   $projects = [];
}
  return view('/bankloan',['projects'=>$projects]);
}
public function fixdate(request $request){

     ProjectDetails::where('project_id',$request->project)->update([

                     'fixdate'=>$request->fixdate


      ]);

     return back();
}
public function reassign(request $request){

  $projects = ProjectDetails::where('fixdate','LIKE',date('Y-m-d'))->pluck('project_id');  
  $pname = ProcurementDetails::whereIn('project_id',$projects)->distinct()->pluck('procurement_contact_no');
 $new = [];

       foreach ($pname as $projects) {
         $procname = ProcurementDetails::where('procurement_contact_no',$projects)->pluck('procurement_name')->first();
          $procnumber = ProcurementDetails::where('procurement_contact_no',$projects)->pluck('procurement_contact_no')->first();

          $s = ProcurementDetails::where('procurement_contact_no',$projects)->pluck('project_id');
          $customerprojectcount = ProjectDetails::whereIn('project_id',$s)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->count();
          $full = ProjectDetails::whereIn('project_id',$s)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->get();
          $total =  ProjectDetails::whereIn('project_id',$s)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->pluck('project_size')->sum();

       array_push($new,["procname"=>$procname,'customerprojectcount'=>$customerprojectcount,'full'=>$full,'totalsize'=>$total,'procnumber'=>$procnumber]);
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
        $perPage =20;
 
        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
 
        // Create our paginator and pass it to the view
        $paginatedItems= new \Illuminate\Pagination\LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
 
        // set url path for generted links
        $paginatedItems->setPath($request->url());



       return view('/reassign',['project'=>$paginatedItems,'project_ids'=>count($projects),'projectscount'=>$ms,'users'=>User::where('department_id','!=',10)->get(),]);
   
}
public function loadsubcust(request $request ){


        $subwards = CustomerType::where('sub_customer_id',$request->ward_id)
                            ->select('cust_type','id')
                            ->get();
         return response()->json($subwards);
}
public function graderange(request $request){

     $data = new GradeRange;
     $data->grade = $request->type;
     $data->from_range = $request->from;
     $data->to_range = $request->to;
     $data->save();

     return back();
}
public function quatation(request $request){
 
  $req = Requirement::where('id',$request->enqid)->first();

  $state = DB::table('states')->get();

 

  return view('/productquatation',['req'=>$req,'state'=>$state]);
}
 
 public function getproductquan(request $request){

          
         $enq = Gst::where('state',$request->gsttype)->where('category',$request->description)->first();
        
        
         if($request->product){
           $product = implode(",",$request->product );
         }else{
          $product = "";
         }
         if($request->desc){
          $desc = implode(",", $request->desc);
         }else{
          $desc = "";
         }
         if($request->quantity){
           $quantity = implode(",", $request->quantity);
         }else{
          $quantity = "";
         }
          if($request->price){
           $price = implode(",", $request->price);
         }else{
          $price = "";
         }


          if($request->total){
           $total = implode(",", $request->total);
         }else{
          $total = "";
         }
        
        $year = date('Y');
        $country_code = Country::pluck('country_code')->first();
        $zone = Zone::pluck('zone_number')->first();
        $check = Quotation::where('quotation_id',$request->id)->first();
         
        $number = round($request->withgstamount);
        $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number.'&token=fshadvjfa67581232';
        $response = file_get_contents($url);
        $data = json_decode($response,true);
        $dtow = $data['message'];
           

         
            $quot = new Quotation;
            
            $quot->req_id =$request->enqid;
            $quot->manu_id = $request->manuid;
            $quot->project_id = $request->projectid;
            $quot->product = $product;
            $quot->category = $request->description;
            $quot->desct = $desc;
            $quot->multiquantity = $quantity;
            $quot->price = $price;
            $quot->total = $total;
            $quot->totalmultiamount = $request->totalamount;
            $quot->totalgst = $request->cgst;
            $quot->totalsgst = $request->sgst;
            $quot->totaligst =$request->igst;
            $quot->withgstamount = $request->withgstamount;
           $quot->shipaddress  = $request->ship;
           $quot->billaddress   = $request->bill;
           $quot->cgstpercent = $enq->cgst;
           $quot->sgstpercent = $enq->sgst;
           $quot->igst = ($enq->cgst + $enq->sgst);
           $quot->gstamount_word = $dtow;
           $quot->save();
        
        

     
        $enquiries = Requirement::where('id',$request->id)->update([
                'quotation'=> "generated"
        ]);
           return back()->with('success','Generted successfully ! Go Back');
    }





 

  public function getgstdata(request $request){

      
   
    if($request->gsttype == 1){
        $p = Gst::where('state',$request->gsttype)->where('category',$request->description)->pluck('sgst')->first();
          $percent = $p / 100;
       }else{
        $p = Gst::where('state',$request->gsttype)->where('category',$request->description)->pluck('igst')->first();
         $s = $p / 2; 
         $percent = $s /100;
       }
        
         $to =[];
         $gst1=[];
         $sgst1=[];
         $igst1 =[]; 
        $gst2=[];
         $sgst2=[];
         $igst2 =[];
         $withgst = [];
     for($i=0;$i<sizeof($request->price);$i++){
         $x =$request->price[$i];
         $y =$request->quantity[$i];
         $withoutgst = ($x/$request->gstpercent);
         $total =round($withoutgst * $y); 
        array_push($to,$total);   
      if($request->gsttype == 1){
      $gst = ($total * $percent);
      $sgst = ($total * $percent);
       $igst = "";
       $with = ($total + $gst + $sgst );
         array_push($gst2, $gst);
         array_push($sgst2, $sgst);
         array_push($igst2, $igst);
         array_push($withgst, $with);  
       }else{
        $total =round($withoutgst * $y);
       $gst1 = ($total * $percent);
       $sgst1 = ($total * $percent);
         $igst = ($gst1 + $sgst1);
         $gst = "";
         $sgst ="";
         $with = ($total + $igst );
         array_push($gst2, $gst);
         array_push($sgst2, $sgst);
         array_push($igst2, $igst);
          array_push($withgst, $with);  
       }

     }  
       
     return response()->json(['total'=>array_sum($withgst),'gst'=>array_sum($gst2),'sgst'=>array_sum($sgst2),'withgst'=>array_sum($to),'igst'=>array_sum($igst2)]);

  }
  public function deletequan(request $request){

     Quotation::where('id',$request->id)->delete();
     return back();
  }

  public function updatepaymentmode(request $request){

      
         
         if($request->paymentmode == "CASH IN HAND"){
          if($request->image){
             $imageName1 = $request->file('image');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/cash_images/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');
        

            PaymentHistory::where('id',$request->id)->update([
                       "cash_image"=>$imageFileName,"file"=>$imageFileName]);
      }else{
         $imageFileName="";
      }
            PaymentHistory::where('id',$request->id)->update([
                 "payment_mode"=>$request->paymentmode,
                 "cash_holder"=>$request->cashrecive,
                 "totalamount"=>$request->totalamount,
                 "payment_note"=>$request->note
                 
              ]);
         }
        if($request->paymentmode == "CHEQUE"){
               if($request->image){
             $imageName1 = $request->file('image');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/CHEQUE/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');
                 
                    PaymentHistory::where('id',$request->id)->update([
                       "cheque_image"=>$imageFileName]);

               }else{
         $imageFileName="";
      }
            PaymentHistory::where('id',$request->id)->update([
                 "payment_mode"=>$request->paymentmode,
                 "date"=>$request->ChequeDeposit,
                 "cheque_number"=>$request->checqnumber,
                 "totalamount"=>$request->totalamount,
                 "payment_note"=>$request->note
                


              ]);

         }
          if($request->paymentmode == "RTGS"){
            if($request->image){
           
             $imageName1 = $request->file('image');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/RTGS/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');


              
                 PaymentHistory::where('id',$request->id)->update([
                       "rtgs_file"=>$imageFileName]);
            }else{
         $imageFileName="";
      }
            PaymentHistory::where('id',$request->id)->update([
                 "payment_mode"=>$request->paymentmode,
                 "date"=>$request->date,
                 "cheque_number"=>$request->refnumber,
                 "totalamount"=>$request->totalamount,
                 "payment_note"=>$request->note,
                 "branch_name"=>$request->branchname
                  

    

              ]);
         }
       return back();
  }
  public function updatepaymentmode1(request $request){

      
         
         if($request->paymentmode == "CASH"){
             if($request->image){
              $imageName1 = $request->file('image');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/cash_images/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');
       
                 

                  PaymentDetails::where('id',$request->id)->update([
                       "cash_image"=>$imageName1,"file"=>$imageFileName]);

      }else{
         $imageFileName="";
      }
            PaymentDetails::where('id',$request->id)->update([
                 "payment_mode"=>$request->paymentmode,
                 "cash_holder"=>$request->cashrecive,
                 "totalamount"=>$request->totalamount,
                 "payment_note"=>$request->note
               


              ]);
         }
        if($request->paymentmode == "CHEQUE"){
              if($request->image){
                 $imageName1 = $request->file('image');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/cheque_images/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');
               
                    
                    PaymentDetails::where('id',$request->id)->update([
                       "cheque_image"=>$imageFileName]);

              }else{
         $imageFileName="";
      }
            PaymentDetails::where('id',$request->id)->update([
                 "payment_mode"=>$request->paymentmode,
                 "date"=>$request->ChequeDeposit,
                 "cheque_number"=>$request->checqnumber,
                 "totalamount"=>$request->totalamount,
                 "payment_note"=>$request->note
                 

              ]);
         }
          if($request->paymentmode == "RTGS"){
                  if($request->image){
             $imageName1 = $request->file('image');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/rtgs_files/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');



                     
                       PaymentDetails::where('id',$request->id)->update([
                       "rtgs_file"=>$imageFileName]);

                  }else{
         $imageFileName="";
      }
            PaymentDetails::where('id',$request->id)->update([
                 "payment_mode"=>$request->paymentmode,
                 "date"=>$request->date,
                 "cheque_number"=>$request->refnumber,
                 "totalamount"=>$request->totalamount,
                 "payment_note"=>$request->note,
                 "branch_name"=>$request->branchname
                

              ]);
         }
       return back();
  }

  public function getcustomerinvoices(request $request){
            if($request->number){
            $customer = CustomerDetails::where('mobile_num',$request->number)->pluck('customer_id')->first();

              $invoice = CustomerInvoice::where('customer_id',$customer)->with('CustomerDetails')->get();
            }else{
              $invoice = [];
            }


               

      return view('/getcustomerinvoices',['invoice'=>$invoice]);
  }
  public function getAprrovalpage(request $request){
         $current_timestamp = Carbon::now()->timestamp;
        $info = DB::table('notifications')->where('id',$request->id)->update([
           'read_at'=>$current_timestamp
        ]);
        if($request->id){
         $user = DB::table('notifications')->where('id',$request->id)->where('approve',NULL)->pluck('notifiable_id')->first();
         $users = FieldLogin::where('id',$user)->get();
       }else{

        $user = DB::table('notifications')->pluck('notifiable_id');
         $users = FieldLogin::whereIn('id',$user)->get();
       }
           
      return view('/getAprrovalpage',['users'=>$users]);
  }

  public function approvepage(request $request){
      
 
     $info = DB::table('notifications')->where('notifiable_id',$request->id)->update([
           'approve'=>1,
           'approvedby'=>Auth::user()->id
        ]); 
            $s = DB::table('notifications')->where('notifiable_id',$request->id)->pluck('user_id')->first();
              $em = User::where('id',$s)->pluck('employeeId')->first();
              $email =  EmployeeDetails::where('employee_id',$em)->pluck('official_email')->first();
           Mail::to($email)->send(new FieldLoginApprove());
  

     return back();
  }

   public function rejectpage(request $request){

     $info = DB::table('notifications')->where('notifiable_id',$request->id)->update([
           'approve'=>2
        ]); 
       $s = DB::table('notifications')->where('notifiable_id',$request->id)->pluck('user_id')->first();
              $em = User::where('id',$s)->pluck('employeeId')->first();
              $email =  EmployeeDetails::where('employee_id',$em)->pluck('official_email')->first();
           Mail::to($email)->send(new FieldLoginReject());
  

     return back();
  }
  public function leaccept(request $request){

     DB::table('orders')->where('id',$request->id)->update(['leaccept'=>1]);
      DB::table('orders')->where('id',$request->id)->update(['lename'=>$request->userid]);

     return back();
  }
   public function lereject(request $request){

     DB::table('orders')->where('id',$request->id)->update(['leaccept'=>2]);
      DB::table('orders')->where('id',$request->id)->update(['lename'=>$request->userid]);

     return back();
  }
 
 public function adddeliverydetails(request $request){

       
           

              
           $projectimage = "";
            $i = 0;
            if($request->truckimage){
                foreach($request->truckimage as $pimage){

             $image = $pimage;
             $imageFileName = time() . '.' . $image->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_truck/' . $imageFileName;
             $s3->put($filePath, file_get_contents($image), 'public'); 
                     
                     if($i == 0){
                        $projectimage .= $imageFileName;
                     }
                     else{
                            $projectimage .= ",".$imageFileName;
                     }
                     $i++;
                }
        
            }else{
               $projectimage = "";
            }

              if($request->cashimage != NULL){
             $imageName1 = $request->file('cashimage');
             $imageFileName1 = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_cashimages/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($imageName1), 'public');    
            }else{
                $imageFileName1 = "N/A";
            }


             if($request->rtgsimage != NULL){

             $imageName2 = $request->file('rtgsimage');
             $imageFileName2 = time() . '.' . $imageName2->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_rtgsimages/' . $imageFileName2;
             $s3->put($filePath, file_get_contents($imageName2), 'public'); 
                
            }else{
                $imageFileName2 = "N/A";
            }


             if($request->chequeimage != NULL){
                   
             $imageName3 = $request->file('chequeimage');
             $imageFileName3 = time() . '.' . $imageName3->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_chequeimages/' . $imageFileName3;
             $s3->put($filePath, file_get_contents($imageName3), 'public');

                
            }else{
                $imageFileName3 = "N/A";
            }

             if($request->truckvideo != NULL){

             $imageName4 = $request->file('truckvideo');
             $imageFileName4 = time() . '.' . $imageName4->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_truckvideo/' . $imageFileName4;
             $s3->put($filePath, file_get_contents($imageName4), 'public');

                
            }else{
                $imageFileName4 = "N/A";
            }



             if($request->status){
            $collect = implode(",", $request->status);   
             }else{
                $collect = "";
             }
           $check = DeliveryDetails::where('order_id',$request->id)->first();
           if(count($check) == 0){
         $data = new DeliveryDetails;
         $data->order_id = $request->id;    
         $data->delivery_done =$request->userid;
         $data->payment_method = $collect;
         $data->cashamount = $request->cashamount;
         $data->rtgsamount = $request->rtgsamount;
         $data->chequeamount = $request->chequeamount;
         $data->cashimage = $imageFileName1;
         $data->rtgsimage = $imageFileName2;
         $data->chequeimage = $imageFileName3;
         $data->truckimage = $projectimage;
         $data->truckvideo = $imageFileName4;
         $data->totalamount = $request->totalamount;
         $data->save();

       }else{
         $check->order_id = $request->id;    
         $check->delivery_done =$request->userid;
         $check->payment_method = $collect;
         $check->cashamount = $request->cashamount;
         $check->rtgsamount = $request->rtgsamount;
         $check->chequeamount = $request->chequeamount;
         $check->cashimage = $imageFileName1;
         $check->rtgsimage = $imageFileName2;
         $check->chequeimage = $imageFileName3;
         $check->truckimage = $projectimage;
         $check->truckvideo = $imageFileName4;
         $check->totalamount = $request->totalamount;
         $check->save();
       }


    return back();
           
          
  }
  public function deletedelivery(request $request){

       $check = DeliveryDetails::where('id',$request->id)->delete();

       return back();

  }
  public function assignunupdateproject(request $request){
      
    $check = Assignstage::where('user_id',$request->user)->count();
         if($request->number){
            $y = implode(",",$request->number);
         } else{
          $y="null";
         }

        
  
     if($check == 0){
        
        $data = new AssignStage;
        $data->user_id = $request->user;
        $data->projectids = $y;
        $data->save();
     }else{
         if($request->number){
            $y = implode(",",$request->number);
         } else{
          $y="null";
         }
         AssignStage::where('user_id',$request->user)->update(['projectids'=>$y]);
     }

return back();

  }
}
