<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ProjectDetails; 
use App\Manufacturer;
use App\User;
use App\Payment;
use App\FieldLogin;
use Carbon\Carbon;
use App\UpdatedReport;
use App\Requirement;
use App\PaymentDetails;
use App\ProcurementDetails;
use App\CustomerDetails;
use App\PaymentHistory;
use DateTime;
use App\MamahomePrice;
use App\MultipleInvoice;
use DB;
use App\Ledger;
use App\MRInvoice;
use App\MRSupplierdetails;
use App\Supplierdetails;
use App\Transport;
use App\Order;
use Auth;
use App\Country;
use App\Manager_Deatils;
use App\Mowner_Deatils;
use App\ProposedManufacturers;
use App\Salescontact_Details;
use App\CustomerInvoice;
use App\Zone;
use App\OwnerDetails;
use App\ConsultantDetails;
use App\ContractorDetails;
use App\SiteEngineerDetails;
use App\Mprocurement_Details;
use App\SubWard;
use App\ProposedProjects;
use App\Builder;
use App\CustomerType;
use App\NewCustomerAssign;

class AdminController extends Controller
{
   

 
  public  function blocked(request $request)
  {
    if($request->type && $request->from && $request->to && $request->type != "All"){
               
    if($request->type == "Fake" || $request->type == "Genuine" && $request->type != "Closed" && $request->type != "All"){
           $onlySoftDeleted = ProjectDetails::onlyTrashed()->orderBy('deleted_at','DESC')->whereDate('deleted_at','>=',$request->from)->whereDate('deleted_at','<=',$request->to)->where('quality',$request->type)->paginate('50');
           
    	 }else{

    	 	$onlySoftDeleted = ProjectDetails::orderBy('deleted_at','DESC')->whereDate('deleted_at','>=',$request->from)->whereDate('deleted_at','<=',$request->to)->where('project_status','LIKE',"%".$request->type."%")->onlyTrashed()->paginate('50');
           
       }

}else if($request->type=="All" && $request->from && $request->to){

 $onlySoftDeleted = ProjectDetails::onlyTrashed()->orderBy('deleted_at','DESC')->whereDate('deleted_at','>=',$request->from)->whereDate('deleted_at','<=',$request->to)->paginate('50');

}

else{
    $onlySoftDeleted = [];

}

    return view('/blocked.blocked',['onlySoftDeleted'=>$onlySoftDeleted]);    

  }
  public  function manublocked(request $request)
  {
    if($request->type && $request->from && $request->to && $request->type != "All"){
            if($request->type == "Fake" || $request->type == "Genuine" && $request->type != "All"){
                   $onlySoftDeleted = Manufacturer::onlyTrashed()->orderBy('deleted_at','DESC')->whereDate('deleted_at','>=',$request->from)->whereDate('deleted_at','<=',$request->to)->where('quality',$request->type)->paginate('50');
           
            }else{

               $onlySoftDeleted = Manufacturer::onlyTrashed()->orderBy('deleted_at','DESC')->whereDate('deleted_at','>=',$request->from)->whereDate('deleted_at','<=',$request->to)->where('manufacturer_type',$request->type)->paginate('50');
            }
   

              
           
       }
       else if($request->type=="All" && $request->from && $request->to){
           $onlySoftDeleted = Manufacturer::orderBy('deleted_at','DESC')->whereDate('deleted_at','>=',$request->from)->whereDate('deleted_at','<=',$request->to)->onlyTrashed()->paginate('50');
           
           
       }
       else{
        $onlySoftDeleted = [];

}
    return view('/blocked.manublocked',['onlySoftDeleted'=>$onlySoftDeleted]);    

  }
   
   public function cancelMr(request $request){

          // DB::table('orders')->where('id',$request->id)->update(['Mr'=>NULL,'mrpurchase_order'=>NULL]);
        
$check = MRSupplierdetails::where('order_id',$request->id)->update([

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
'state' =>null
        ]);

        
        





         return back()->with('info',"successfully Reset ! Thank You ");
   }





   public function projectupdatereport(request $request){
             
      
      
        if($request->from && $request->to && $request->type=="All"){
            
             $onlySoftDeleted = UpdatedReport::whereDate('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to)->with('project')->where('project_id','!=',NULL)->orderBy('created_at','DESC')->paginate('20');

        }else if($request->from && $request->to && $request->type) {
           $onlySoftDeleted = UpdatedReport::whereDate('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to)->where('user_id',$request->type)->where('project_id','!=',NULL)->orderBy('created_at','DESC')->with('project')->paginate('20');
        } else{
           $onlySoftDeleted=[];
        }  
        $new = UpdatedReport::where('project_id',10880)->pluck('allfileds')->first();

          // $dd = unserialize($new);
          //   dd($dd);
     $users = User::where('department_id','!=',10)->whereIn('group_id',[2,1,6,7,23])->get();
    return view('/updatedreport.projectupdatereport',['onlySoftDeleted'=>$onlySoftDeleted,'users'=>$users]);
   }
    public function manuupdatereport(request $request){
             
      
      
        if($request->from && $request->to && $request->type=="All"){
            
             $onlySoftDeleted = UpdatedReport::whereDate('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to)->with('project')->where('manu_id','!=',NULL)->orderBy('created_at','DESC')->paginate('20');

        }else if($request->from && $request->to && $request->type) {
           $onlySoftDeleted = UpdatedReport::whereDate('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to)->where('user_id',$request->type)->where('manu_id','!=',NULL)->orderBy('created_at','DESC')->with('project')->paginate('20');
        } else{
           $onlySoftDeleted=[];
        }  
        $new = UpdatedReport::where('project_id',10880)->pluck('allfileds')->first();

          // $dd = unserialize($new);
          //   dd($dd);
     $users = User::where('department_id','!=',10)->whereIn('group_id',[2,1,6,7,23])->get();
    return view('/updatedreport.manuupdatereport',['onlySoftDeleted'=>$onlySoftDeleted,'users'=>$users]);
   }
    public function totalcallattend(request $request){
  
     $today = date('y-m-d');
     $group = [6,7,2,1];
       $fids =FieldLogin::where('created_at','LIKE','%'.$today.'%')->pluck('user_id')->toarray();
   $users = User::whereIn('id',$fids)->where('department_id','!=',10)->get();
     $data = [];
     $pid="MH_91_Z1_PM";
     $totalcal = [];
     $totalenq = [];
     $whatsapp_projects = [];
     $mwhatsapp_projects =[];
     $orders = [];
    
       $attend = ['Call_attended','attend']; 
      if(!$request->user_id){
       
                
     foreach ($users as $user) {


       $cid = NewCustomerAssign::where('user_id',$user->id)->pluck('customerids')->first();
        
        $cids =explode(",", $cid);
       
        $final = array_filter($cids);
        $arr = array_diff($final,['null']);
        $bb = array_diff($arr, ["1"]);
        $arr1 = array_diff($bb,['"']);
        $arr2 = NewCustomerAssign::where('user_id',$user->id)->pluck('cid')->toarray();
        $sm = array_filter(array_merge($arr1,$arr2));
    
        // $callattend = UpdatedReport::where('user_id',$user->id)->whereIn('quntion',$attend)->where('created_at','LIKE','%'.$today.'%')->get();
        $numof = CustomerDetails::whereIn('customer_id',$sm)->pluck('mobile_num')->toarray();
        $pms = ProcurementDetails::whereIn('procurement_contact_no',$numof)->pluck('project_id')->toarray();
        $pmm = Mprocurement_Details::whereIn('contact',$numof)->pluck('id')->toarray();

        $cal = DB::table('updated_reports')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'))
                      ->whereIn('quntion',$attend)->where('created_at','LIKE','%'.$today.'%')
                      ->where('user_id',$user->id)
                      ->groupBy('p_p_c_id')
                      ->havingRaw('COUNT(*) >= 1')
                      ->get(); 

             $smg = DB::table('updated_reports')
                       ->where('created_at','LIKE','%'.$today.'%')
                      ->where('user_id',$user->id)
                      ->whereIn('quntion',$attend)
                      ->where('p_p_c_id',NULL)
                      ->get(); 
              $cals = count($smg);               

          $callattend = count($cal) + $cals;

           $totalupdate = DB::table('updated_reports')
                       ->where('created_at','LIKE','%'.$today.'%')
                      ->where('user_id',$user->id)
                      ->count(); 
        $enq = Requirement::where('generated_by',$user->id)->where('created_at','LIKE','%'.$today.'%')->count();
     
        $name = User::where('id',$user->id)->pluck('name')->first();
         $pwhatsapp = UpdatedReport::where('user_id',$user->id)->where('created_at','LIKE','%'.$today.'%')->pluck('project_id');

                 


            $pwt = ProjectDetails::whereIn('project_id',$pwhatsapp)->distinct('p_p_c_id')->pluck('p_p_c_id')->toarray();
              $finalpwt = array_filter($pwt);
           $wthatsappproposed = ProcurementDetails::select('p_p_c_id')->groupBy('p_p_c_id')->distinct('p_p_c_id')->whereIn('p_p_c_id',$finalpwt)->where('whatsapp','!=',NULL)->get();   
             $finalwtp = count($wthatsappproposed);
            
             // ----------------------manu------------------------
            $pwhatsappm = UpdatedReport::where('user_id',$user->id)->where('created_at','LIKE','%'.$today.'%')->where('p_p_c_id','LIKE','%'.$pid.'%')->pluck('manu_id');
         
            $pwtm = Manufacturer::whereIn('id',$pwhatsappm)->distinct('p_p_c_id')->pluck('p_p_c_id')->toarray();
            
                  $wthatsappproposedm = DB::table('mprocurement_details')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'))
                      ->whereIn('manu_id',$pwhatsappm)
                     
                      ->groupBy('p_p_c_id')
                      ->havingRaw('COUNT(*) >= 1')
                      ->get();
                   


             $finalwtpm = count($wthatsappproposedm);



             //----------------------------------manu end------------------
              //----------------------------------Dedicated---------------------

        


         $finalassined = CustomerDetails::whereIn('customer_id',$sm)->pluck('customer_id')->toarray() ;
       
        $numbers = CustomerDetails::whereIn('customer_id',$finalassined)->pluck('mobile_num')->toarray();
          
        $count = ProcurementDetails::select('procurement_contact_no','whatsapp','updated_at')->where('whatsapp','!=',NULL)->groupBy('procurement_contact_no')->distinct()->whereIn('procurement_contact_no',$numbers)->where('updated_at','LIKE','%'.$today.'%')->pluck('procurement_contact_no')->toarray();
       

        $count21 = Mprocurement_Details::select('contact','whatsapp','updated_at')->where('updated_at','LIKE','%'.$today.'%')->where('whatsapp','!=',NULL)->groupBy('contact')->distinct()->whereIn('contact',$numbers)->pluck('contact')->toarray();
       
        



          $sum =array_unique(array_merge($count,$count21));


          
            $order = Order::where('generated_by',$user->id)->where('status','Order Confirmed')->where('created_at','LIKE','%'.$today.'%')->count();


             //----------------------------Dedicaed-------------------------------------------

          if($callattend > 0){

               array_push($data,['callattend'=>$callattend,'name'=>$name,'enq'=>$enq,'whatsapp_projects'=>$finalwtp,'mwhatsapp_projects'=>$finalwtpm,'sum'=>count($sum),'total'=>$totalupdate,'order'=>$order]);
               array_push($totalcal,$callattend);
               array_push($totalenq,$enq);
               array_push($whatsapp_projects, $finalwtp);
               array_push($mwhatsapp_projects, $finalwtpm);
               array_push($orders, $order);

          }


     }
   }
     else{
             $user = $request->user_id;

           if($request->fromdate && $request->todate && $request->user_id && $request->user_id != "All") {

          // $callattend = UpdatedReport::where('user_id',$user)->whereIn('quntion',$attend)->groupBy('p_p_c_id')->distinct('p_p_c_id')->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();

           
            $cal = DB::table('updated_reports')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'))
                      ->whereIn('quntion',$attend)
                      ->wheredate('created_at','>=',$request->fromdate)
                      ->wheredate('created_at','<=',$request->todate)
                      ->where('user_id',$user->id)
                      ->groupBy('p_p_c_id')
                      ->havingRaw('COUNT(*) >= 1')
                      ->get(); 

          $callattend = count($cal);
          
           





        $enq = Requirement::where('generated_by',$request->user_id)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();
          $name = User::where('id',$user)->pluck('name')->first();
             $pwhatsapp = UpdatedReport::where('user_id',$user->id)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->pluck('project_id');
            $pwt = ProjectDetails::whereIn('project_id',$pwhatsapp)->distinct('p_p_c_id')->pluck('p_p_c_id')->toarray();
              $finalpwt = array_filter($pwt);
           $wthatsappproposed = ProcurementDetails::select('p_p_c_id')->groupBy('p_p_c_id')->distinct('p_p_c_id')->whereIn('p_p_c_id',$finalpwt)->where('whatsapp','!=',NULL)->get();   
             $finalwtp = count($wthatsappproposed);
      
                   // ----------------------manu------------------------
            $pwhatsappm =UpdatedReport::where('user_id',$user->id)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->pluck('manu_id');
            $pwtm = Manufacturer::whereIn('id',$pwhatsappm)->distinct('p_p_c_id')->pluck('p_p_c_id')->toarray();
              $finalpwtm = array_filter($pwtm);
           $wthatsappproposedm = Mprocurement_Details::select('p_p_c_id')->groupBy('p_p_c_id')->distinct('p_p_c_id')->whereIn('p_p_c_id',$finalpwtm)->where('whatsapp','!=',NULL)->get();   
             $finalwtpm = count($wthatsappproposedm);



             //----------------------------------manu end------------------


             if($callattend > 0){

               array_push($data,['callattend'=>$callattend,'sum'=>0,'name'=>$name,'enq'=>$enq,'whatsapp_projects'=>$finalwtp,'mwhatsapp_projects'=>$finalwtpm,'order'=>[]]);
               array_push($totalcal,$callattend);
               array_push($totalenq,$enq);
               array_push($whatsapp_projects, $finalwtp);
               array_push($mwhatsapp_projects, $finalwtpm);



          }
           }

           else if($request->fromdate && $request->todate && $request->user_id = "All") {
           
              foreach ($users as $user) {
                      // $callattend = UpdatedReport::where('user_id',$user->id)->whereIn('quntion',$attend)->groupBy('p_p_c_id')->distinct('p_p_c_id')->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();

                      $cal = DB::table('updated_reports')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'))
                      ->whereIn('quntion',$attend)
                      ->wheredate('created_at','>=',$request->fromdate)
                      ->wheredate('created_at','<=',$request->todate)
                      
                      ->groupBy('p_p_c_id')
                      ->havingRaw('COUNT(*) >= 1')
                      ->get(); 

                      $callattend = count($cal);
     
                      $enq = Requirement::where('generated_by',$user->id)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();
                        $name = User::where('id',$user->id)->pluck('name')->first();

         $pwhatsapp = UpdatedReport::wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->pluck('project_id');
            $pwt = ProjectDetails::whereIn('project_id',$pwhatsapp)->distinct('p_p_c_id')->pluck('p_p_c_id')->toarray();
              $finalpwt = array_filter($pwt);
           $wthatsappproposed = ProcurementDetails::select('p_p_c_id')->groupBy('p_p_c_id')->distinct('p_p_c_id')->whereIn('p_p_c_id',$finalpwt)->where('whatsapp','!=',NULL)->get();   
             $finalwtp = count($wthatsappproposed);
                // ----------------------manu------------------------
            $pwhatsappm =UpdatedReport::wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->pluck('manu_id');
            $pwtm = Manufacturer::whereIn('id',$pwhatsappm)->distinct('p_p_c_id')->pluck('p_p_c_id')->toarray();
              $finalpwtm = array_filter($pwtm);
           $wthatsappproposedm = Mprocurement_Details::select('p_p_c_id')->groupBy('p_p_c_id')->distinct('p_p_c_id')->whereIn('p_p_c_id',$finalpwtm)->where('whatsapp','!=',NULL)->get();   
             $finalwtpm = count($wthatsappproposedm);
                  


             //----------------------------------manu end------------------

            if($callattend > 0){

               array_push($data,['sum'=>0,'callattend'=>$callattend,'name'=>$name,'enq'=>$enq,'whatsapp_projects'=>$finalwtp,'mwhatsapp_projects'=>$finalwtpm,'order'=>[]]);
               array_push($totalcal,$callattend);
               array_push($totalenq,$enq);
               array_push($whatsapp_projects, $finalwtp);
               array_push($mwhatsapp_projects, $finalwtpm);


          }
           }
         }
   }
   
   return view('/reports.totalcallattend',['data'=>$data,'totalcal'=>array_sum($totalcal),'totalenq'=>array_sum($totalenq),'whatsapp_projects'=>$whatsapp_projects,'totalwhatsapp_projects'=>array_sum($whatsapp_projects),'mwhatsapp_projects'=>array_sum($mwhatsapp_projects),'sum'=>count($sum),'orders'=>array_sum($orders)]);
}
public function projects(request $request){


  
     $existingnumbers = CustomerDetails::where('mobile_num','!=',NULL)->where('mobile_num','!='," ")->pluck('mobile_num');
    
  $number = ProcurementDetails::where('procurement_contact_no','!=',NULL)->where('p_p_c_id',NULL)->whereNotIn('procurement_contact_no',$existingnumbers)->pluck('procurement_contact_no')->toarray();
$OwnerDetails  = OwnerDetails::where('owner_contact_no','!=',NULL)->where('p_p_c_id',NULL)->whereNotIn('owner_contact_no',$existingnumbers)->pluck('owner_contact_no')->toarray();
$ContractorDetails = ContractorDetails::where('contractor_contact_no','!=',NULL)->where('p_p_c_id',NULL)->whereNotIn('contractor_contact_no',$existingnumbers)->pluck('contractor_contact_no')->toarray();
$Builder   = Builder::where('builder_contact_no','!=',NULL)->where('p_p_c_id',NULL)->whereNotIn('builder_contact_no',$existingnumbers)->pluck('builder_contact_no')->toarray();
   
 

//   $numbers= array_merge($number,$OwnerDetails,$ContractorDetails,$Builder);
  
//   $finalnum = array_unique($numbers);
  
    

//        $new = [];
//        if($finalnum){

//       foreach ($finalnum as $num) {
//                    $projects =ProcurementDetails::where('procurement_contact_no',$num)->pluck('project_id')->toarray();


//                     $year = date('Y');
//                     $country_code = Country::pluck('country_code')->first();
//                     $zone = Zone::pluck('zone_number')->first();
//                     $invoiceno = "MH_".$country_code."_".$zone."_PP_".$num;
//                     $s[] =$num;
//                     for($i=0;$i<sizeof($projects);$i++){
//                       ProcurementDetails::where('project_id',$projects[$i])->update(['p_p_c_id'=>$invoiceno]);


//                       // ProjectDetails::where('project_id',$projects[$i])->update(['p_p_c_id'=>$invoiceno]);
//                         $test = ProjectDetails::where('project_id',$projects[$i])->pluck('updated_at')->first();
//                     $dd = ProjectDetails::where('project_id',$projects[$i])->update(['p_p_c_id' =>$invoiceno,'updated_at' =>$test]);
                      
//                       OwnerDetails::where('project_id',$projects[$i])->update(['p_p_c_id'=>$invoiceno]);
//                       ContractorDetails::where('project_id',$projects[$i])->update(['p_p_c_id'=>$invoiceno]);
//                       Builder::where('project_id',$projects[$i])->update(['p_p_c_id'=>$invoiceno]);
//                     }
//                  }
//        }
       $ids = ProposedProjects::pluck('p_p_c_id')->toarray();
    if($request->from && $request->to  && $request->status && $request->status != "All" && !$request->ward && !$request->subward ){
                 

             $duplicates = DB::table('project_details')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                      ->where('project_status','NOT LIKE',"Closed")
                      ->where('quality','!=',"Fake")
                     ->whereNotIn('p_p_c_id',$ids)
                     ->where('quality','!=',"Fake")
                     ->where('quality','!=',"Unverified")
                     ->havingRaw('SUM(project_size) >='.$request->from.'')
                     ->havingRaw('SUM(project_size) <='.$request->to.'')
                     ->where('project_status',$request->status)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) >= 1')
                     ->paginate(100); 

       } else if($request->from && $request->to  && $request->status=="All"  && !$request->ward && !$request->subward ){
                 

             $duplicates = DB::table('project_details')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                     ->groupBy('p_p_c_id')
                     ->where('quality','!=',"Fake")
                     ->whereNotIn('p_p_c_id',$ids)
                     ->where('quality','!=',"Unverified")
                     ->where('project_status','NOT LIKE',"Closed")
                     ->havingRaw('SUM(project_size) >='.$request->from.'')
                     ->havingRaw('SUM(project_size) <='.$request->to.'')
                     ->paginate(100); 
                

       }else if($request->from && $request->to  && $request->status=="All"  && $request->ward && $request->subward =="All" ){
             $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
             
           $duplicates = DB::table('project_details')->where('p_p_c_id','!=',NULL)
                      ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                     ->where('project_status','NOT LIKE',"Closed")
                     ->havingRaw('SUM(project_size) >='.$request->from.'')
                     ->havingRaw('SUM(project_size) <='.$request->to.'')
                     ->whereNotIn('p_p_c_id',$ids)
                     ->where('quality','!=',"Fake")
                     ->where('quality','!=',"Unverified")
                     ->whereIn('sub_ward_id',$subwards)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) >= 1')
                     ->paginate(100); 


             
       }else if($request->from && $request->to  && $request->status=="All"  && $request->ward && $request->subward !="All" && $request->subward ){
             $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
        
           $duplicates = DB::table('project_details')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                     ->where('project_status','NOT LIKE',"Closed")
                     ->havingRaw('SUM(project_size) >='.$request->from.'')
                     ->havingRaw('SUM(project_size) <='.$request->to.'')
                     ->where('sub_ward_id',$request->subward)
                     ->where('quality','!=',"Fake")
                     ->where('quality','!=',"Unverified")
                     ->whereNotIn('p_p_c_id',$ids)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) >= 1')
                     ->paginate(100); 




       }else if($request->from && $request->to  && $request->status !="All"  && $request->ward && $request->subward !="All" && $request->subward ){
             $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
        
           $duplicates = DB::table('project_details')->where('p_p_c_id','!=',NULL)
                      ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                     ->where('project_status','NOT LIKE',"Closed")
                     ->whereNotIn('p_p_c_id',$ids)
                     ->where('quality','!=',"Fake")
                     ->where('quality','!=',"Unverified")
                     ->havingRaw('SUM(project_size) >='.$request->from.'')
                     ->havingRaw('SUM(project_size) <='.$request->to.'')
                     ->where('sub_ward_id',$request->subward)
                     ->where('project_status',$request->status)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) >= 1')
                     ->paginate(100); 

            


       }else if($request->from && $request->to  && $request->status =="All"  && $request->ward && $request->subward =="All"  ){
             $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
        
           $duplicates = DB::table('project_details')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                    ->where('project_status','NOT LIKE',"Closed")
                    ->whereNotIn('p_p_c_id',$ids)
                     ->havingRaw('SUM(project_size) >='.$request->from.'')
                     ->havingRaw('SUM(project_size) <='.$request->to.'')
                     ->whereIn('sub_ward_id',$subwards)
                     ->where('quality','!=',"Fake")
                     ->where('quality','!=',"Unverified")
                     ->where('project_status',$request->status)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) >= 1')
                     ->paginate(100); 

            


       }


       else{
         $duplicates = [];
                   
       }
                    

                                          
 
 return view('/newproposed.projects',['data'=>$duplicates]);

}


public function manufactured(request $request){


  
     $existingnumbers = CustomerDetails::take(300)->where('mobile_num','!=',NULL)->where('mobile_num','!='," ")->pluck('mobile_num');
    
  $number = Mprocurement_Details::take(300)->where('contact','!=',NULL)->where('p_p_c_id',NULL)->whereNotIn('contact',$existingnumbers)->pluck('contact')->toarray();

$OwnerDetails  = Mowner_Deatils::take(300)->where('contact','!=',NULL)->where('p_p_c_id',NULL)->whereNotIn('contact',$existingnumbers)->pluck('contact')->toarray();

$ContractorDetails = Salescontact_Details::take(300)->where('contact','!=',NULL)->where('p_p_c_id',NULL)->whereNotIn('contact',$existingnumbers)->pluck('contact')->toarray();


$Builder   = Manager_Deatils::take(300)->where('contact','!=',NULL)->where('p_p_c_id',NULL)->whereNotIn('contact',$existingnumbers)->pluck('contact')->toarray();
   
 

  $numbers= array_merge($number,$OwnerDetails,$ContractorDetails,$Builder);
  
  $finalnum = array_unique($numbers);
  

       $new = [];
       if($finalnum){

      foreach ($finalnum as $num) {
          
             $projects =Mprocurement_Details::where('contact',$num)->pluck('manu_id')->toarray();

            
                   $year = date('Y');
                    $country_code = Country::pluck('country_code')->first();
                    $zone = Zone::pluck('zone_number')->first();
                    $invoiceno = "MH_".$country_code."_".$zone."_PM_".$num;

                     $s[] =$num;
                   

                    
                    for($i=0;$i<sizeof($projects);$i++){
                           $updatedat = Manufacturer::where('id',$projects[$i])->pluck('updated_at')->first();
                       Mprocurement_Details::where('manu_id',$projects[$i])->update(['p_p_c_id'=>$invoiceno,'updated_at'=>$updatedat]);
                      Manufacturer::where('id',$projects[$i])->update(['p_p_c_id'=>$invoiceno]);


                      Mowner_Deatils::where('manu_id',$projects[$i])->update(['p_p_c_id'=>$invoiceno]);
                      Salescontact_Details::where('manu_id',$projects[$i])->update(['p_p_c_id'=>$invoiceno]);
                      Manager_Deatils::where('manu_id',$projects[$i])->update(['p_p_c_id'=>$invoiceno]);


                    }
              
            
              
                 }
       }
         $idm = ProposedManufacturers::pluck('p_p_c_id')->toarray();

        $mids = MamahomePrice::pluck('manu_id')->toarray();
        $ms = Manufacturer::whereIn('id',$mids)->pluck('p_p_c_id')->toarray();
      
        $idsm = array_merge($idm,$ms);
        $ids = array_filter($idsm);
       

    if( $request->status && $request->status != "All" && !$request->ward && !$request->subward ){
                 

             $duplicates = DB::table('manufacturers')->where('templock',NULL)->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),'grade')
                      ->whereNotIn('p_p_c_id',$ids)
                      ->where('quality',"Genuine")                    
                      ->where('manufacturer_type',$request->status)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) >= 1')
                     ->paginate(3000); 
                      
       } else if( $request->status=="All"  && !$request->ward && !$request->subward ){
                 

             $duplicates = DB::table('manufacturers')->where('templock',NULL)->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),'grade')
                     ->groupBy('p_p_c_id')
                     ->whereNotIn('p_p_c_id',$ids)
                     ->where('quality',"Genuine")
                     ->paginate(3000); 
                

       }else if($request->status=="All"  && $request->ward && $request->subward =="All" ){
             $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
             
           $duplicates = DB::table('manufacturers')->where('templock',NULL)->where('p_p_c_id','!=',NULL)
                      ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),'grade')
                      ->where('quality',"Genuine")
                     ->whereNotIn('p_p_c_id',$ids)
                     ->whereIn('sub_ward_id',$subwards)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) >= 1')
                     ->paginate(3000); 




       }else if( $request->status=="All"  && $request->ward && $request->subward !="All" && $request->subward ){
             $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
        
           $duplicates = DB::table('manufacturers')->where('templock',NULL)->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),'grade')
                     ->where('quality',"Genuine")
                     ->where('sub_ward_id',$request->subward)
                     ->whereNotIn('p_p_c_id',$ids)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) >= 1')
                     ->paginate(3000); 




       }else if($request->status !="All"  && $request->ward && $request->subward !="All" && $request->subward ){
             $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
        
           $duplicates = DB::table('manufacturers')->where('templock',NULL)->where('p_p_c_id','!=',NULL)
                      ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),'grade')
                     ->where('quality',"Genuine")
                     ->whereNotIn('p_p_c_id',$ids)
                     ->where('quality',"Genuine")
                     ->where('sub_ward_id',$request->subward)
                     ->where('manufacturer_type',$request->status)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) >= 1')
                     ->paginate(3000); 

            


       }else if($request->status =="All"  && $request->ward && $request->subward =="All"  ){
             $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
        
           $duplicates = DB::table('manufacturers')->where('templock',NULL)->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),'grade')
                   ->where('quality',"Genuine")
                    ->whereNotIn('p_p_c_id',$ids)
                     ->where('quality',"Genuine")
                     ->whereIn('sub_ward_id',$subwards)
                     ->where('manufacturer_type',$request->status)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) >= 1')
                     ->paginate(3000); 

            


       }


       else{
         $duplicates = [];
                   
       }
          if(count($duplicates) >= 1)
      {
           $manuids = [];
       for ($i=0; $i <count($duplicates)  ; $i++) { 
           
             $dta = $duplicates[$i]->products_count;

            array_push($manuids, $dta);

       }
      }else{
          $manuids = [];
      }
                    
 
 return view('/newproposed.manufactured',['data'=>$duplicates,'manuids'=>array_sum($manuids)]);

}

public function storeproposedprojects(request $request)
{
  
      $numbers =$request->number;
    
      $check = ProposedProjects::whereIn('p_p_c_id',$numbers)->pluck('p_p_c_id'); 
         if(count($check) == 0){
       for($i=0;$i<sizeof($numbers);$i++){

         $checks = ProposedProjects::where('p_p_c_id',$numbers[$i])->first(); 
           if(count($checks) == 0){
           $data =new ProposedProjects;
           $data->user_id = $request->user;
           $data->p_p_c_id = $numbers[$i]; 
            $data->save();

          }else{
            $checks->user_id = $request->user;
            $checks->p_p_c_id = $numbers[$i]; 
            $checks->save();

          }
     }

        return back()->with('success','Assigned successfully !');


   }else{

        return back()->with('success','its Assegned  !'.$check);
      
   }

 }

public function storeproposedmanu(request $request)
{
  
      $numbers =$request->number;
    
      $check = ProposedManufacturers::whereIn('p_p_c_id',$numbers)->pluck('p_p_c_id'); 
         if(count($check) == 0){
       for($i=0;$i<sizeof($numbers);$i++){

         $checks = ProposedManufacturers::where('p_p_c_id',$numbers[$i])->first(); 
           if(count($checks) == 0){
           $data =new ProposedManufacturers;
           $data->user_id = $request->user;
           $data->p_p_c_id = $numbers[$i]; 
            $data->save();

          }else{
            $checks->user_id = $request->user;
            $checks->p_p_c_id = $numbers[$i]; 
            $checks->save();

          }
     }

        return back()->with('success','Assigned successfully !');


   }else{

        return back()->with('success','its Assegned  !'.$check);
      
   }

 }






public function deleteproposedprojects(request $request){
        
   $check = ProposedProjects::where('p_p_c_id',$irequest->d)->delete(); 
   return back()->with('success','Deleted successfully !');
     
}

public function setgradeproject(request $request)
{
     $id = $request->id;
      

      ProjectDetails::where('p_p_c_id',$request->id)->update(['grade'=>$request->grade]);
     
   return back()->with('success','Set The Grade successfully'.$request->grade);    
}


public function setgrademanu(request $request)
{
     $id = $request->id;
      

      Manufacturer::where('p_p_c_id',$request->id)->update(['grade'=>$request->grade]);
     
   return back()->with('success','Set The Grade successfully'.$request->grade);    
}



public function bosstest()
{
  
     $type = CustomerType::get();
       
        $data =[];

      foreach ($type as $types) {
       
           $num = CustomerDetails::where('customer_type',$types->id)->count();

         array_push($data,['count'=>$num,'type'=>$types->cust_type]);

             

      }

  //----------------------------total customers-----------------------    
   
     $assign =NewCustomerAssign::pluck('customerids')->toarray();

     $assignum =NewCustomerAssign::pluck('cid')->toarray();

      $as = array_filter($assign); 
       $numbers = [];
       foreach ($as as $a) {
         
        
           $df = explode(",", $a);

           array_push($numbers, $df);
 
       }
 

    $custids =  array_merge(...$numbers);
    
    $finalids = array_merge($custids,$assignum);
      $assignedcustids = [];
    foreach ($type as $types) {
       
           $num = CustomerDetails::whereIn('customer_id',$finalids)->where('customer_type',$types->id)->count();
             
         array_push($assignedcustids,['count'=>$num,'type'=>$types->cust_type]);

             

      }

      $dddd=[];
      $fr = CustomerDetails::whereIn('customer_id',$finalids)->pluck('customer_id')->toarray();
         $ff = CustomerDetails::whereNotIn('customer_id',$fr)->pluck('customer_id')->toarray();

      foreach ($type as $types) {
       
           $num = CustomerDetails::whereIn('customer_id',$ff)->where('sub_customer_type',$types->id)->count();
             
         array_push($dddd,['count'=>$num,'type'=>$types->cust_type]);

             

      }
       
    //---------------------assigned customers------------------  

      $from = "2019-08-01";
      $to = "2019-08-31";

     $t =CustomerInvoice::wheredate('invoicedate','>=',$from)->wheredate('invoicedate','<=',$to)->pluck('customer_id')->toarray();
     $thismonth = CustomerDetails::whereIn('customer_id',$t)->wheredate('created_at','>=',$from)->wheredate('created_at','<=',$to)->with('type')->get();
     
      // dd($thismonthid);
//------------------------------this month customers----------------------
  
       $users = User::whereIn('id',[113,162,177,258,265,272,280])->get();
    $fff = [];
   foreach ($users as $user) {
              
                $ss = DB::table('orders')->where('generated_by',$user->id)->where('status',"Order Confirmed")->pluck('id')->toarray();
               
                 
                 $s = DB::table('mamahome_invoices')->wheredate('created_at','>=',$from)->whereIn('order_id',$ss)->sum('amountwithgst');

                 $s1 = DB::table('multiple_invoices')->wheredate('created_at','>=',$from)->whereIn('order_id',$ss)->sum('totalwithoutgst');
                
           
              $fi = $s+$s1;
               
               array_push($fff,['user_id'=>$user->id,'amt'=>$fi]);
            }         
   
      $projectcustomers = CustomerDetails::where('customer_type',1)->get();
       
        
 


      $manucustomers = CustomerDetails::where('customer_type',6)->get();



return view('/thismonth',['dddd'=>$dddd,'data'=>$data,'assignedcustids'=>$assignedcustids,'thismonth'=>$thismonth,'projectcustomers'=>$projectcustomers,'manucustomers'=>$manucustomers]);
          
  }
public function customer_report()
{
  
     $type = CustomerType::get();
       
        $data =[];
        $total = [];
      foreach ($type as $types) {
       
           $num = CustomerDetails::where('sub_customer_type',$types->id)->count();
               
         array_push($data,['count'=>$num,'type'=>$types->cust_type]);
           array_push($total, $num);
             

      }

  //----------------------------total customers-----------------------    
   
     $assign =NewCustomerAssign::pluck('customerids')->toarray();

     $assignum =NewCustomerAssign::pluck('cid')->toarray();

      $as = array_filter($assign); 
       $numbers = [];
       foreach ($as as $a) {
         
        
           $df = explode(",", $a);

           array_push($numbers, $df);
 
       }
 

    $custids =  array_merge(...$numbers);
    
    $finalids = array_merge($custids,$assignum);
      $assignedcustids = [];
      $totalassign =[];
    foreach ($type as $types) {
       
           $num = CustomerDetails::whereIn('customer_id',$finalids)->where('sub_customer_type',$types->id)->count();
             array_push($totalassign,$num);
             array_push($assignedcustids,['count'=>$num,'type'=>$types->cust_type]);

             

      }
       
      $dddd=[];
         $fr = CustomerDetails::whereIn('customer_id',$finalids)->pluck('customer_id')->toarray();
         $ff = CustomerDetails::whereNotIn('customer_id',$fr)->pluck('customer_id')->toarray();
         $company = [];
      foreach ($type as $types) {
       
           $num = CustomerDetails::whereIn('customer_id',$ff)->where('sub_customer_type',$types->id)->count();
              array_push($company, $num);
         array_push($dddd,['count'=>$num,'type'=>$types->cust_type]);

             

      }
       
    //---------------------assigned customers------------------  

      $from = "2019-08-01";
      $to = "2019-08-31";

     $t =CustomerInvoice::wheredate('invoicedate','>=',$from)->wheredate('invoicedate','<=',$to)->pluck('customer_id')->toarray();
     $thismonth = CustomerDetails::whereIn('customer_id',$t)->wheredate('created_at','>=',$from)->wheredate('created_at','<=',$to)->with('type')->get();
     
      // dd($thismonthid);
//------------------------------this month customers----------------------
  
       $users = User::whereIn('id',[113,162,177,258,265,272,280])->get();
    $fff = [];
   foreach ($users as $user) {
              
                $ss = DB::table('orders')->where('generated_by',$user->id)->where('status',"Order Confirmed")->pluck('id')->toarray();
               
                 
                 $s = DB::table('mamahome_invoices')->wheredate('created_at','>=',$from)->whereIn('order_id',$ss)->sum('amountwithgst');

                 $s1 = DB::table('multiple_invoices')->wheredate('created_at','>=',$from)->whereIn('order_id',$ss)->sum('totalwithoutgst');
                
           
              $fi = $s+$s1;
               
               array_push($fff,['user_id'=>$user->id,'amt'=>$fi]);
            }         
   
      $projectcustomers = CustomerDetails::where('customer_type',1)->get();
       
        
 


      $manucustomers = CustomerDetails::where('customer_type',6)->get();


return view('/customersreports.customer_report',['dddd'=>$dddd,'data'=>$data,'assignedcustids'=>$assignedcustids,'thismonth'=>$thismonth,'projectcustomers'=>$projectcustomers,'manucustomers'=>$manucustomers,'total'=>$total,'totalassign'=>$totalassign,'company'=>$company]);
          
  }

public function customersalesreport(request $request){
  
   $date = $request->year;


   if($request->year){
 
   $products = DB::connection('customer_db')->table('customer_invoices')
            ->whereYear('invoicedate', $date)
             ->orderby('invoicedate','ASC')
            ->get()
            ->groupBy(function($val) {
      return  Carbon::parse($val->invoicedate)->format('M');
});
    
        $final = [];
    foreach ($products as $key => $pro) {
          $data =[]; 
          $sup =[];
          $withoutgst = [];
         foreach ($pro as $pr) {
               
              
            $suplier = DB::connection('suplier_db')->table('supplier_invoicedatas')->where('invoiceno',$pr->invoiceno)->pluck('supplierinvoiceamount')->first();
      

              if($pr->custmodeofgst == "CGST & SGST"){
                $gst =($pr->customergstpercent)*2;
              }else{
                $gst = $pr->customergstpercent;
              }
             $total = (floatval($pr->mhInvoiceamount)) - (floatval($suplier));

             
            $withoutgstamt = ($gst*$total)/100;

             $finals = ($total - $withoutgstamt);


            array_push($data,$pr->mhInvoiceamount);
            array_push($sup,$suplier);
            array_push($withoutgst,$finals);
         }
          $invoiceamount = array_sum($data);
          $supplierinvoiceamount = array_sum($sup);    
          $withgst = $invoiceamount - $supplierinvoiceamount;
          $finalamt = array_sum($withoutgst);

         array_push($final,['invoiceamount'=>$invoiceamount,'month'=>$key,'withgst'=>$withgst,'spamount'=>$supplierinvoiceamount,'finalamt'=>$finalamt,'year'=>$date]);
    }

   }else{

      $final = [];
   }
   


 
    return view('/customersreports.customersalesreport',['final'=>$final]);
}

public function monthlydetails(request $request){
     
      $month = $request->month;
      $year = $request->year;
  
       // $products = DB::connection('customer_db')->table('customer_invoices')
       //      ->whereYear('invoicedate', $year)
       //      ->whereMonth('invoicedate',$month)
       //      ->orderby('invoicedate','ASC') 
       //      ->get();

              $dt = DateTime::createFromFormat('!m', $month);
              $f = $dt->format('F'); 

               
       
     
    

               $yes = DB::connection('customer_db')->table('customer_invoices')
                 ->select('customer_id', DB::raw('count(*) as total'))
                 ->whereYear('invoicedate', $year)
                 ->whereMonth('invoicedate',$month)
                 ->groupBy('customer_id')
                 ->pluck('customer_id')->toarray();



 
                 
          $mnt = $dt->format('m'); 
          $date =$year."-".$mnt;
          
         
          $new =$yes;

          $old = CustomerInvoice::whereIn('customer_id',$yes)->where('invoicedate','NOT LIKE',"%".$date."%")->pluck('customer_id')->toarray();
           $finalnew = array_diff($new, $old);
          

           $finalold = DB::connection('customer_db')->table('customer_invoices')
                 ->whereYear('invoicedate', $year)
                 ->whereMonth('invoicedate',$month)
                 ->whereNotIn('customer_id',$finalnew)
                 ->select('customer_id', DB::raw('count(*) as total'))
                 ->groupBy('customer_id')
                 ->pluck('customer_id')->toarray();

          




  $type = CustomerType::get();
   $newcustomersdata=[];
   $totalassign=[];


   foreach ($type as $types) {
       
           $num = CustomerDetails::whereIn('customer_id',$finalnew)->where('sub_customer_type',$types->id)->count();
             array_push($totalassign,$num);
             array_push($newcustomersdata,['count'=>$num,'type'=>$types->cust_type]);

             

      }

     
  $oldcustomersdata=[];
   $oldtotalassign=[];


   foreach ($type as $types) {
       
           $num = CustomerDetails::whereIn('customer_id',$finalold)->where('sub_customer_type',$types->id)->count();
             array_push($oldtotalassign,$num);
             array_push($oldcustomersdata,['count'=>$num,'type'=>$types->cust_type]);

             

      }
        $thismonth = DB::connection('customer_db')->table('customer_invoices')
                 ->select('customer_id', DB::raw('count(*) as total'))
                 ->whereIn('customer_id',$finalold)
                 ->whereYear('invoicedate', $year)
                 ->whereMonth('invoicedate',$month)
                 ->groupBy('customer_id')
                 ->get();
                 $newthismonth = DB::connection('customer_db')->table('customer_invoices')
                 ->select('customer_id', DB::raw('COUNT(*) as total'))
                 ->whereIn('customer_id',$finalnew)
                 ->whereYear('invoicedate', $year)
                 ->whereMonth('invoicedate',$month)
                 ->groupBy('customer_id')
                 ->get();

        

//-------Dedicate customers--------------------------------------------------------------------------
         $assign =NewCustomerAssign::pluck('customerids')->toarray();

     $assignum =NewCustomerAssign::pluck('cid')->toarray();

      $as = array_filter($assign); 
       $numbers = [];
       foreach ($as as $a) {
         
        
           $df = explode(",", $a);

           array_push($numbers, $df);
 
       }
 

    $custids =  array_merge(...$numbers);    
     
       $olddedicated = array_intersect($custids,$finalold);
       $oldcompany = array_diff($finalold,$olddedicated);


    $old_dedicated = DB::connection('customer_db')->table('customer_invoices')
                 ->select('customer_id', DB::raw('count(*) as total'))
                 ->whereIn('customer_id',$olddedicated)
                 ->whereYear('invoicedate', $year)
                 ->whereMonth('invoicedate',$month)
                 ->groupBy('customer_id')
                 ->get();

     $old_company = DB::connection('customer_db')->table('customer_invoices')
                 ->select('customer_id', DB::raw('count(*) as total'))
                 ->whereIn('customer_id',$oldcompany)
                 ->whereYear('invoicedate', $year)
                 ->whereMonth('invoicedate',$month)
                 ->groupBy('customer_id')
                 ->get();
    
     return view('/customersreports.monthlycustomerdeatils',['thismonth'=>$thismonth,'month'=>$request->month,'year'=>$request->year,'dt'=>$dt,'f'=>$f,'newcustomersdata'=>$newcustomersdata,'totalassign'=>$totalassign,'oldcustomersdata'=>$oldcustomersdata,'oldtotalassign'=>$oldtotalassign,'newthismonth'=>$newthismonth,'old_dedicated'=>$old_dedicated,'old_company'=>$old_company]);
}

public function activecustomers(request $request){
    

      $projectcustomers = CustomerDetails::whereIn('customer_type',[1,11])->pluck('customer_id')->toarray();
      $projectcustomerss = CustomerDetails::whereIn('customer_type',[1,11])->get();
      $manufacturers = CustomerDetails::where('customer_type',6)->get();
     
      $yessss = CustomerDetails::where('customer_type',6)->pluck('customer_id')->toarray();
      

     $type = CustomerType::get();
      $ty = [];
      $counts=[];
  //--------------------------------Active Projects Cout ----------------------------
        $close = "Closed";      
        $active1 = [];         
        
      foreach ($projectcustomerss as  $data) {
                $d = $data->mobile_num;
                 // $users = ProjectDetails::select('project_id','project_status','project_size')->where('project_status','NOT LIKE',"%".$close."%")->whereHas('procurementdetails', function($q) use($d){
                 //     $q->where('procurement_contact_no', '=',$d);

                 //      })->get();
$number = ProcurementDetails::where('procurement_contact_no','!=',NULL)->where('procurement_contact_no',$d)->pluck('project_id')->toarray();
$OwnerDetails  = OwnerDetails::where('owner_contact_no','!=',NULL)->where('owner_contact_no',$d)->pluck('project_id')->toarray();
$ContractorDetails = ContractorDetails::where('contractor_contact_no','!=',NULL)->where('contractor_contact_no',$d)->pluck('project_id')->toarray();
$Builder   = Builder::where('builder_contact_no','!=',NULL)->where('builder_contact_no',$d)->pluck('project_id')->toarray();
  $numbers= array_merge($number,$OwnerDetails,$ContractorDetails,$Builder);
  $finalnum = array_unique($numbers);

       
         $num = 0;

          $duplicates = DB::table('project_details')->select('project_id','project_status','project_size',DB::raw('SUM(project_size) as yes'),'quality')
                      ->where('project_status','NOT LIKE',"Closed")
                      ->whereIn('project_id',$finalnum)
                      ->where('quality','NOT LIKE',"Fake")
                      ->havingRaw('SUM(project_size) !='.$num.'')
                      ->get();
                      
                      for($i=0;$i<count($duplicates);$i++) {
                            
                     if($duplicates[$i]->yes != 0){
                      array_push($active1,$data->customer_id);
                     }

                      }
                     

              
                         
 

        }
     

   //-------In active ---------------


  
//--------------------------------close Active Customers--------------------------------
    foreach ($type as $types) {
       
           $num = CustomerDetails::whereIn('customer_id',$active1)->where('sub_customer_type',$types->id)->count();
           
           
             array_push($ty,['count'=>$num,'typename'=>$types->cust_type]);

             array_push($counts, $num);

      }
    
   

    $typesa = [];
    $typecount = [];
  foreach ($type as $types) {
       
           $num = CustomerDetails::whereIn('customer_id',$yessss)->where('sub_customer_type',$types->id)->count();
           
           
             array_push($typesa,['count'=>$num,'typename'=>$types->cust_type]);

             array_push($typecount, $num);

      }





  $inactive = array_merge($active1,$yessss);

  $inactivecust =CustomerDetails::whereNotIn('customer_id',$inactive)->get();

  


    $inactivetype = [];
    $inactivetypecount = [];
  foreach ($type as $types) {
       
           $num = CustomerDetails::whereNotIn('customer_id',$inactive)->where('sub_customer_type',$types->id)->count();
           
           
             array_push($inactivetype,['count'=>$num,'typename'=>$types->cust_type]);

             array_push($inactivetypecount, $num);

      }

     
  

  if($request->project==2){

        $a = [];
        $active=[];
        $m=[];


     foreach ($manufacturers as $act) {
       $Mprocurement_Details = Mprocurement_Details::where('contact',$act->mobile_num)->pluck('manu_id')->toarray();
       $Mowner_Deatils = Mowner_Deatils::where('contact',$act->mobile_num)->pluck('manu_id')->toarray();
       $Manager_Deatils = Manager_Deatils::where('contact',$act->mobile_num)->pluck('manu_id')->toarray();
       $Salescontact_Details = Salescontact_Details::where('contact',$act->mobile_num)->pluck('manu_id')->toarray();
       $num = array_merge($Mprocurement_Details,$Mowner_Deatils,$Manager_Deatils,$Salescontact_Details);
       $final = array_unique($num);


         $duplicates =DB::table('manufacturers')->select('id','manufacturer_type')
                      ->whereIn('id',$final)
                      ->get();

            array_push($a,['cid'=>$act->customer_id,'project'=>$duplicates,'number'=>$act->mobile_num,'name'=>$act->first_name]);
          
     }
   }else if($request->project == 1){

        $close = "Closed";      
        $active = [];         
        $a = [];
        $m=[];

      foreach ($projectcustomerss as  $data) {
                $d = $data->mobile_num;
                 // $users = ProjectDetails::select('project_id','project_status','project_size')->where('project_status','NOT LIKE',"%".$close."%")->whereHas('procurementdetails', function($q) use($d){
                 //     $q->where('procurement_contact_no', '=',$d);

                 //      })->get();
$number = ProcurementDetails::where('procurement_contact_no','!=',NULL)->where('procurement_contact_no',$d)->pluck('project_id')->toarray();
$OwnerDetails  = OwnerDetails::where('owner_contact_no','!=',NULL)->where('owner_contact_no',$d)->pluck('project_id')->toarray();
$ContractorDetails = ContractorDetails::where('contractor_contact_no','!=',NULL)->where('contractor_contact_no',$d)->pluck('project_id')->toarray();
$Builder   = Builder::where('builder_contact_no','!=',NULL)->where('builder_contact_no',$d)->pluck('project_id')->toarray();
  $numbers= array_merge($number,$OwnerDetails,$ContractorDetails,$Builder);
  $finalnum = array_unique($numbers);

       
         $num = 0;

          $duplicates = DB::table('project_details')->select('project_id','project_status','project_size',DB::raw('SUM(project_size) as yes'),'quality')
                      ->where('project_status','NOT LIKE',"Closed")
                      ->whereIn('project_id',$finalnum)
                      ->where('quality','NOT LIKE',"Fake")
                      ->havingRaw('SUM(project_size) !='.$num.'')
                      ->get();
                      
                      for($i=0;$i<count($duplicates);$i++) {
                            
                     if($duplicates[$i]->yes != 0){
                      array_push($active,['cid'=>$data->customer_id,'project'=>$duplicates,'number'=>$d,'name'=>$data->first_name]);
                     }

                      }
                     

              
                         
 

        }
       
      }else if($request->project == 3){

        $close = "Closed";      
        $active = [];         
        $a = [];
        $m=[];


        
      foreach ($inactivecust as  $data) {
                $d = $data->mobile_num;
                 // $users = ProjectDetails::select('project_id','project_status','project_size')->where('project_status','NOT LIKE',"%".$close."%")->whereHas('procurementdetails', function($q) use($d){
                 //     $q->where('procurement_contact_no', '=',$d);

                 //      })->get();
        $number = ProcurementDetails::where('procurement_contact_no','!=',NULL)->where('procurement_contact_no',$d)->pluck('project_id')->toarray();
        $OwnerDetails  = OwnerDetails::where('owner_contact_no','!=',NULL)->where('owner_contact_no',$d)->pluck('project_id')->toarray();
        $ContractorDetails = ContractorDetails::where('contractor_contact_no','!=',NULL)->where('contractor_contact_no',$d)->pluck('project_id')->toarray();
        $Builder   = Builder::where('builder_contact_no','!=',NULL)->where('builder_contact_no',$d)->pluck('project_id')->toarray();
        $numbers= array_merge($number,$OwnerDetails,$ContractorDetails,$Builder);
        $finalnumss= array_unique($numbers);

       
         $num = 0;

          $duplicates =ProjectDetails::withTrashed()->select('project_id','project_status','project_size',DB::raw('SUM(project_size) as yes'),'quality')
                      ->whereIn('project_id',$finalnumss)
                       ->get();
       array_push($m,['cid'=>$data->customer_id,'project'=>$duplicates,'number'=>$d,'name'=>$data->first_name]);
              
        }
      }
      else{
       $active=[];
       $a=[];
       $m=[];
   }
   
return view('/customersreports.activecustomers',
  ['type'=>$ty,'active'=>$active,'counts'=>$counts,'a'=>$a,'typesa'=>$typesa,'typecount'=>$typecount,'inactivetype'=>$inactivetype,'inactivetypecount'=>$inactivetypecount,'m'=>$m


]);

}

public function assignppids(request $request){

 // $ids = ProposedProjects::where('user_id',Auth::user()->id)->where('remove',NULL)->where('holding',NULL)->where('type',NULL)->pluck('p_p_c_id')->toarray();
 //  $proids = MultipleInvoice::pluck('order_id')->toarray();
 //  $finalproids = Order::whereIn('id',$proids)->pluck('project_id')->toarray();
 //  $merge = array_merge($projectids,$finalproids);
 //  $unique = array_unique($merge);
 //  $ppids = ProjectDetails::whereIn('project_id',$unique)->where('p_p_c_id','!=',NULL)->pluck('p_p_c_id')->toarray();
 //  $finalppids = array_unique($ppids);
 //  $ppidsfinals = array_diff($ids, $finalppids);
 $pms = ProcurementDetails::where('procurement_contact_no',$request->sid)->orWhere('project_id',$request->sid)->pluck('p_p_c_id')->toarray();
 $ward_id=$request->ward_id;

  $ids = ProposedProjects::where('user_id',Auth::user()->id)->where('remove',NULL)->where('holding',NULL)->where('type',NULL)->pluck('p_p_c_id')->toarray();
             $projectids = MamahomePrice::pluck('project_id')->toarray();
  $proids = MultipleInvoice::pluck('order_id')->toarray();
  $finalproids = Order::whereIn('id',$proids)->pluck('project_id')->toarray();
  $merge = array_merge($projectids,$finalproids);
  $unique = array_unique($merge);
  $ppids = ProjectDetails::whereIn('project_id',$unique)->where('p_p_c_id','!=',NULL)->pluck('p_p_c_id')->toarray();
  $finalppids = array_unique($ppids);
  $ppidsfinals = array_diff($ids, $finalppids);



  if($request->sid){
   
                    $duplicates = DB::table('project_details')
                      ->leftjoin('procurement_details','procurement_details.project_id' ,'=','project_details.project_id')
                      ->select('project_details.p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_details.project_size) as yes'),'project_details.project_status','project_details.grade','procurement_details.procurement_contact_no')
                      ->whereIn('project_details.p_p_c_id',$pms)
                      ->groupBy('project_details.p_p_c_id')
                      ->where('project_details.project_status','NOT LIKE',"Closed")
                      ->where('project_details.quality',"Genuine")
                      ->paginate(10);
                   }
                   else{

                      $duplicates = DB::table('project_details')
                      ->leftjoin('procurement_details','procurement_details.project_id' ,'=','project_details.project_id')
                      ->select('project_details.p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_details.project_size) as yes'),'project_details.project_status','project_details.grade','procurement_details.procurement_contact_no')
                      ->whereIn('project_details.p_p_c_id',$ppidsfinals)
                      ->groupBy('project_details.p_p_c_id')
                      ->where('project_details.project_status','NOT LIKE',"Closed")
                      ->where('project_details.quality',"Genuine")
                      ->paginate(10);



                      
                   }

    return view('/newproposed.assignppids',['data'=>$duplicates,'ward_id'=>$ward_id]);

}

public function holdingcust(request $request){

 $ids = ProposedProjects::where('user_id',Auth::user()->id)->where('holding',1)->pluck('p_p_c_id')->toarray();
             $projectids = MamahomePrice::pluck('project_id')->toarray();
  $proids = MultipleInvoice::pluck('order_id')->toarray();
  $finalproids = Order::whereIn('id',$proids)->pluck('project_id')->toarray();
  $merge = array_merge($projectids,$finalproids);
  $unique = array_unique($merge);
  $ppids = ProjectDetails::whereIn('project_id',$unique)->where('p_p_c_id','!=',NULL)->pluck('p_p_c_id')->toarray();
  $finalppids = array_unique($ppids);
  $ppidsfinals = array_diff($ids, $finalppids);
         $duplicates = DB::table('project_details')
                      ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                      ->whereIn('p_p_c_id',$ppidsfinals)
                      ->groupBy('p_p_c_id')
                        ->where('quality',"Genuine")
                      ->where('project_status','!=',"Closed")
                      ->paginate(10); 




    return view('/holdingcust',['data'=>$duplicates]);

}






public function assignmpids(request $request){

 $ids = ProposedManufacturers::where('user_id',Auth::user()->id)->pluck('p_p_c_id')->toarray();

$projectids = MamahomePrice::pluck('manu_id')->toarray();
$proids = MultipleInvoice::pluck('order_id')->toarray();
$finalproids = Order::whereIn('id',$proids)->pluck('manu_id')->toarray();
 $merge = array_merge($projectids,$finalproids);

 $unique = array_unique($merge);

  $ppids = Manufacturer::whereIn('id',$unique)->where('p_p_c_id','!=',NULL)->pluck('p_p_c_id')->toarray();

   $finalppids = array_unique($ppids);
 
  $ppidsfinals = array_diff($ids, $finalppids);


 $pms = Mprocurement_Details::where('contact',$request->sid)->orWhere('manu_id',$request->sid)->pluck('p_p_c_id')->toarray();

       if($request->sid){

            $duplicates = DB::table('manufacturers')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),'grade')
                    ->whereIn('p_p_c_id',$pms)
                     ->groupBy('p_p_c_id')
                     ->paginate(10);

       }else{

          $duplicates = DB::table('manufacturers')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),'grade')
                    ->whereIn('p_p_c_id',$ppidsfinals)
                     ->groupBy('p_p_c_id')
                     ->paginate(10); 

       }
  
    return view('/newproposed.assignmpids',['data'=>$duplicates]);

}




public function pendingorders(){
         $payment = PaymentDetails::where('totalamount',0)->pluck('order_id')->toarray();
         $payments = PaymentHistory::where('totalamount',0)->pluck('order_id')->toarray();
           $orderids = array_merge($payment,$payments);

     $orders = Order::where('paytype',"Cashagainestdelivery")->orWhereIn('id',$orderids)->where('paytype',"1=","Approved")->with('req')->get();  

     return view('/pendingorders',['orders'=>$orders]);
}
public function approveorder(request $request){

      $order = Order::where('id',$request->id)->update(['paytype'=>"Approved"]);

      $ids = Order::where('id',$request->id)->pluck('req_id')->first();

      $re = Requirement::where('id',$ids)->update(['paytype'=>"Approved"]);


      return back();


}
public function addtransport(request $request){
    

      if($request->spimage){
                   $i = 0;
                 $imageFileName23 = "";
                     foreach($request->spimage as $supplierinvoice){

                  $imageName2 = $supplierinvoice;
                  $imageFileName = $i.time() . '.' . $imageName2->getClientOriginalExtension();
                  $s3 = \Storage::disk('azure');
                  $filePath = '/transportimages/' . $imageFileName;
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
                                         
                          $imageFileName23 = Transport::where('mhlpo',$request->mhlpo)->pluck('supplierimage')->first();
                 }
     
$data = Transport::where('mhlpo',$request->mhlpo)->first();
$data->orderid  = $request->orderid;
$data->mhlpo = $request->mhlpo;
$data->suppliernumber = $request->spnumber;
$data->suppliername = $request->spname;
$data->supplergst = $request->spgst;
$data->supplierav = $request->spav;
$data->suppliercgst = $request->spcgst;
$data->suppliersgst = $request->spsgst;
$data->supplierigst = $request->spigst;
$data->supplierinvoiceval = $request->spinval;
$data->supplierpercent = $request->spgstpercent;
$data->supplierimage  = $imageFileName23;
$data->customer_id = $request->cid;
$data->save();

return back()->with('success',"successfully Added");

}

public function pnumbers(request $request){
 
      $number = ProposedProjects::where('user_id',Auth::user()->id)->pluck('p_p_c_id')->toarray();


   if($request->from && $request->to && $request->status){
    $duplicates = DB::table('project_details')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                    ->where('project_status','NOT LIKE',"Closed")
                    ->whereIn('p_p_c_id',$number)
                    ->havingRaw('SUM(project_size) >='.$request->from.'')
                     ->havingRaw('SUM(project_size) <='.$request->to.'')  
                    ->where('project_status',$request->status)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) > 1')
                     ->pluck('p_p_c_id')->toArray();
                      $num = ProcurementDetails::select('procurement_contact_no')->whereIn('p_p_c_id',$duplicates)->distinct()->paginate(100);
                     
                   }else if($request->from && $request->to && !$request->status)
                    {
                        $duplicates = DB::table('project_details')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                    ->where('project_status','NOT LIKE',"Closed")
                    ->whereIn('p_p_c_id',$number)
                     ->havingRaw('SUM(project_size) >='.$request->from.'')
                     ->havingRaw('SUM(project_size) <='.$request->to.'')
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) > 1')
                     ->pluck('p_p_c_id')->toArray();
                      $num = ProcurementDetails::select('procurement_contact_no')->whereIn('p_p_c_id',$duplicates)->distinct()->paginate(100);


                    }else if(!$request->from && !$request->to && $request->status){
                    $duplicates = DB::table('project_details')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                    ->where('project_status','NOT LIKE',"Closed")
                    ->whereIn('p_p_c_id',$number)
                    ->where('project_status',$request->status)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) > 1')
                     ->pluck('p_p_c_id')->toArray();
                      $num = ProcurementDetails::select('procurement_contact_no')->whereIn('p_p_c_id',$duplicates)->distinct()->paginate(100);
                      
                    }
                   else{
                      $duplicates = DB::table('project_details')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                    ->where('project_status','NOT LIKE',"Closed")
                    ->whereIn('p_p_c_id',$number)
                     ->groupBy('p_p_c_id')
                     ->havingRaw('COUNT(*) > 1')
                     ->pluck('p_p_c_id')->toArray();

                      $num = ProcurementDetails::select('procurement_contact_no')->whereIn('p_p_c_id',$number)->distinct()->paginate(100);
                   }


return view('newproposed.test',['number'=>$num]);

      
}
public function savemrunitprice(request $request){
 

  
    if($request->status){
            $mode = implode(",",$request->status);
          }else{
            $mode = "";
          }
        
         $yadav =DB::table('supplierdetails')->where('order_id',$request->id)->pluck('lpo')->first();
         $lpoamount = Supplierdetails::where('lpo',$yadav)->pluck('totalamount')->first();
         $create = Supplierdetails::where('lpo',$yadav)->pluck('created_at')->first();
         $check = Ledger::where('lpo_no',$yadav)->first();
         $e_way_no=$request->e_way_no;
         $invoicedate=$request->invoicedate;
         
         
          
        $unitwithoutgst = round($request->unitwithoutgst,2);
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

      
         
        $price = MRInvoice::where('order_id',$request->id)->first();
        if(count($price) != 0){
        $price->project_id = DB::table('orders')->where('id',$request->id)->pluck('project_id')->first();
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
       
        $price->invoicedate=$invoicedate;
        $price->customer_gst = $request->customergst;
       
        $price->payment_mode = $mode;
        $price->state = $request->state;
        $price->save();
        }else{
        $data = new MRInvoice;
        $data->order_id = $request->id;  
        $data->project_id = DB::table('orders')->where('id',$request->id)->pluck('project_id')->first();
        $data->unit = $request->unit;
        $data->mamahome_price = $request->price;
        $data->unitwithoutgst = round($unitwithoutgst,2);
        $data->totalamount = round($request->tamount,2);
        $data->cgst = round($cgst,2);
        $data->sgst = round($sgst,2);
        $data->igst = round($igst,2);
        $data->totaltax = round($request->totaltax,2);
        $data->amountwithgst = round($request->gstamount,2);
        $data->amount_word = $dtow;
        $data->tax_word = $dtow1;
        $data->gstamount_word =  $dtow2;
        $data->igsttax_word = $dtow3;
        $data->quantity = $request->quantity;
        $data->manu_id = $request->manu_id;
        $data->description = $request->desc;
        $data->billaddress = $request->bill;
        $data->shipaddress = $request->ship;
        $data->updated_by = Auth::user()->id;
        $data->cgstpercent = $request->g1;
        $data->sgstpercent = $request->g2;
        $data->gstpercent = $request->g3;
        $data->igstpercent = $request->i1;
        $data->edited = "No";
        $data->e_way_no=$e_way_no;
         $data->invoicedate=$invoicedate;
        $data->customer_gst = $request->customergst;
        $data->HSN = $request->HSN;
        $data->payment_mode = $mode;
        $data->state = $request->state;
        $data->save();
        
            $z = $data->id;

                $year = date('Y');
                $country_code = Country::pluck('country_code')->first();
                $zone = Zone::pluck('zone_number')->first();
                $invoiceno = "MR".$country_code."".$zone."".$year."IN".$z;
                                $ino = MRInvoice::where('order_id',$request->id)->update([
                                    'invoiceno'=>$invoiceno,
                                    'final'=>$z,
                                    'invoicedate'=>date('Y-m-d')
                                ]);

        }

       

         PaymentDetails::where('order_id',$request->id)->update([
            'status'=>"Received"
        ]);
        
        return back()->with('Success','Invoice Generated');
    
}
public function getcustomerremarks(request $request){

  $data = CustomerDetails::where('customer_id',$request->cid)->first();
  $data->status = $request->Status;
  $data->remarks = $request->remarks;
  $data->save();

  $updatedata = New UpdatedReport;
  $updatedata->cid = $request->cid;
  $updatedata->quntion = $request->Status;
  $updatedata->user_id = Auth::user()->id;
  $updatedata->remarks =$request->remarks;
  $updatedata->save();




  return back()->with("info","Updated successfully !");

}


   public function dedicatedwhatsapp(){
   
  $userid = NewCustomerAssign::pluck('user_id')->toarray();

    $id = array_unique($userid);
  
    $users = User::whereIn('id',$id)->where('department_id','!=',10)->get();

    $data = [];

     foreach ($users as $user) {
        
        $cid = NewCustomerAssign::where('user_id',$user->id)->pluck('customerids')->first();
        
        $cids =explode(",", $cid);
       
        $final = array_filter($cids);
        $arr = array_diff($final,['null']);
        $bb = array_diff($arr, ["1"]);
        $arr1 = array_diff($bb,['"']);
        $arr2 = NewCustomerAssign::where('user_id',$user->id)->pluck('cid')->toarray();
        $sm = array_filter(array_merge($arr1,$arr2));


         $finalassined = CustomerDetails::whereIn('customer_id',$sm)->pluck('customer_id')->toarray() ;
       
        $numbers = CustomerDetails::whereIn('customer_id',$finalassined)->pluck('mobile_num')->toarray();
          
        $count = ProcurementDetails::select('procurement_contact_no','whatsapp')->where('whatsapp','!=',NULL)->groupBy('procurement_contact_no')->distinct()->whereIn('procurement_contact_no',$numbers)->pluck('procurement_contact_no')->toarray();
        $count2 = ContractorDetails::select('contractor_contact_no','whatsapp')->where('whatsapp','!=',NULL)->groupBy('contractor_contact_no')->distinct()->whereIn('contractor_contact_no',$numbers)->pluck('contractor_contact_no')->toarray();
        $count3 = Builder::select('builder_contact_no','whatsapp')->where('whatsapp','!=',NULL)->groupBy('builder_contact_no')->distinct()->whereIn('builder_contact_no',$numbers)->pluck('builder_contact_no')->toarray();
         $count4 = OwnerDetails::select('owner_contact_no','whatsapp')->where('whatsapp','!=',NULL)->groupBy('owner_contact_no')->distinct()->whereIn('owner_contact_no',$numbers)->pluck('owner_contact_no')->toarray();

        $count21 = Mprocurement_Details::select('contact','whatsapp')->where('whatsapp','!=',NULL)->groupBy('contact')->distinct()->whereIn('contact',$numbers)->pluck('contact')->toarray();
        $count22 = Mowner_Deatils::select('contact','whatsapp')->groupBy('contact')->where('whatsapp','!=',NULL)->distinct()->whereIn('contact',$numbers)->pluck('contact')->toarray();
        



          $sum =array_unique(array_merge($count,$count2,$count3,$count4,$count22,$count21));
      
       array_push($data,['name'=>$user->name,'Assigned'=>count($finalassined),'totalwhatsapp'=>count($sum)]);

     }

 




    return view('/whatsapp.dedicatedwhatsapp',['data'=>$data]);
}
public function Proposedprojectswhatsapp(){

    $userid = ProposedProjects::pluck('user_id')->toarray();

    $id = array_unique($userid);
    $data =[];
  
    $users = User::whereIn('id',$id)->where('department_id','!=',10)->get();

      foreach ($users as $user) {
        
  $ids = ProposedProjects::where('user_id',$user->id)->pluck('p_p_c_id')->toarray();
  $projectids = MamahomePrice::pluck('project_id')->toarray();
  $proids = MultipleInvoice::pluck('order_id')->toarray();
  $finalproids = Order::whereIn('id',$proids)->pluck('project_id')->toarray();
  $merge = array_merge($projectids,$finalproids);
  $unique = array_unique($merge);
  $ppids = ProjectDetails::whereIn('project_id',$unique)->where('p_p_c_id','!=',NULL)->pluck('p_p_c_id')->toarray();
  $finalppids = array_unique($ppids);
  
  $num = array_diff($ids, $finalppids);
      $numbers = DB::table('project_details')
                      ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'),DB::raw('SUM(project_size) as yes'),'project_status','grade')
                      ->whereIn('p_p_c_id',$num)
                      ->groupBy('p_p_c_id')
                      ->where('project_status','NOT LIKE',"Closed")
                      ->where('quality',"!=","Fake")
                      ->pluck('p_p_c_id')->toarray();
         

        $count = ProcurementDetails::select('p_p_c_id','whatsapp')->where('whatsapp','!=',NULL)->groupBy('p_p_c_id')->distinct()->whereIn('p_p_c_id',$numbers)->pluck('p_p_c_id')->toarray();
        $count2 = ContractorDetails::select('p_p_c_id','whatsapp')->where('whatsapp','!=',NULL)->groupBy('p_p_c_id')->distinct()->whereIn('p_p_c_id',$numbers)->pluck('p_p_c_id')->toarray();
        $count3 = Builder::select('p_p_c_id','whatsapp')->where('whatsapp','!=',NULL)->groupBy('p_p_c_id')->distinct()->whereIn('p_p_c_id',$numbers)->pluck('p_p_c_id')->toarray();
         $count4 = OwnerDetails::select('p_p_c_id','whatsapp')->where('whatsapp','!=',NULL)->groupBy('p_p_c_id')->distinct()->whereIn('p_p_c_id',$numbers)->pluck('p_p_c_id')->toarray();

          $sum =array_unique(array_merge($count,$count2,$count3,$count4));

         
        array_push($data,['name'=>$user->name,'Assigned'=>count($numbers),'totalwhatsapp'=>count($sum)]);
      }
 




   $userid1 = ProposedManufacturers::pluck('user_id')->toarray();

    $id1 = array_unique($userid1);
    $data1 =[];
  
    $users1 = User::whereIn('id',$id1)->where('department_id','!=',10)->get();



      foreach ($users1 as $user1) {
        
        $numbers1 = ProposedManufacturers::where('user_id',$user1->id)->pluck('p_p_c_id')->toarray();
        $count21 = Mprocurement_Details::select('p_p_c_id','whatsapp')->where('whatsapp','!=',NULL)->groupBy('p_p_c_id')->distinct()->whereIn('p_p_c_id',$numbers1)->pluck('p_p_c_id')->toarray();
        $count22 = Mowner_Deatils::select('p_p_c_id','whatsapp')->groupBy('p_p_c_id')->where('whatsapp','!=',NULL)->distinct()->whereIn('p_p_c_id',$numbers1)->pluck('p_p_c_id')->toarray();

          $sum1 =array_unique(array_merge($count21,$count22));

         
        array_push($data1,['name1'=>$user1->name,'Assigned1'=>count($numbers1),'totalwhatsapp1'=>count($sum1)]);
      }






      $userid12 = ProposedProjects::pluck('user_id')->toarray();
      
      $id2 = array_unique($userid12);
     
      $data2 =[];
      
      $usersdata = User::whereIn('id',$id2)->where('department_id','!=',10)->get();
      
      foreach ($users as $user) { 
       
    $ids = ProposedProjects::where('user_id',$user->id)->where('type','Contractors')->where('Contractor',1)->pluck('p_p_c_id')->toarray();
  
    $projectids = MamahomePrice::pluck('project_id')->toarray();
  
    $proids = MultipleInvoice::pluck('order_id')->toarray();
    
    $finalproids = Order::whereIn('id',$proids)->pluck('project_id')->toarray();
   
    $merge = array_merge($projectids,$finalproids);
   
    $unique = array_unique($merge);
   
    $ppids = ProjectDetails::whereIn('project_id',$unique)->where('p_p_c_id','!=',NULL)->pluck('p_p_c_id')->toarray();
    
    $finalppids = array_unique($ppids);
   
 
              $num = array_diff($ids,$finalppids);
              
             
        
        $count99 = ProcurementDetails::select('p_p_c_id','whatsapp')->where('whatsapp','!=',NULL)->groupBy('p_p_c_id')->distinct()->whereIn('procurement_contact_no',$num)->pluck('p_p_c_id')->toarray();
        $count100 = ContractorDetails::select('p_p_c_id','whatsapp')->where('whatsapp','!=',NULL)->groupBy('p_p_c_id')->distinct()->whereIn('contractor_contact_no',$num)->pluck('p_p_c_id')->toarray();
        
        $count200 = Builder::select('p_p_c_id','whatsapp')->where('whatsapp','!=',NULL)->groupBy('p_p_c_id')->distinct()->whereIn('builder_contact_no',$num)->pluck('p_p_c_id')->toarray();
         $count300 = OwnerDetails::select('p_p_c_id','whatsapp')->where('whatsapp','!=',NULL)->groupBy('p_p_c_id')->distinct()->whereIn('owner_contact_no',$num)->pluck('p_p_c_id')->toarray();

          $sum8 =array_unique(array_merge($count99,$count100,$count200,$count300));

         
        array_push($data2,['name00'=>$user->name,'Assigned00'=>count($num),'totalwhatsapp00'=>count($sum8)]);
     
   
      }

    


   return view('/whatsapp.Proposedprojectswhatsapp',['data2'=>$data2,'data'=>$data,'data1'=>$data1]);
}


public function getmonthtpwithuserreport(request $request){

      $month = $request->month;
      $year = $request->year;
  
     
         
           if($request->user){

           $ids = DB::table('orders')
            ->orderby('created_at','ASC') 
            ->where('status',"Order Confirmed")
            ->where('generated_by',$request->user)
            ->pluck('id');

           $products = DB::connection('customer_db')->table('customer_invoices')
            ->whereYear('invoicedate', $year)
            ->whereMonth('invoicedate',$month)
            ->orderby('invoicedate','ASC') 
            ->whereIn('order_id',$ids)
            ->get();
             
           } else{

              $products = [];
           }


            $dt = DateTime::createFromFormat('!m', $month);
              $f = $dt->format('F'); 

   return view('/tpwithuser.getmonthtpwithuserreport',['month'=>$month,'year'=>$year,'products'=>$products]);
}
public function addotherexpensetoorder(request $request){

        CustomerInvoice::where('invoiceno',$request->invoiceno)->update(['otheramount'=>$request->amount,'otherremark'=>$request->Remarks]);


      return  response()->json("Added successfully! It will  Refresh The Page Click Ok)");
}



}
