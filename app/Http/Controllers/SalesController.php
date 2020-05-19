<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\ProcurementDetails;
use JeroenDesloovere\VCard\VCard;
use App\ProposedProjects;
use Illuminate\Support\Facades\Response;
use App\ProposedManufacturers;
use Auth;
use App\Mprocurement_Details;
use DB;
use App\ProjectDetails;
use App\MamahomePrice;
use Carbon\Carbon;
use App\MultipleInvoice;
use App\ContractorDetails;
use App\Builder;
use App\OwnerDetails;
use App\CustomerDetails;
use App\ManufacturerDetail;
use App\FieldLogin;
use App\Mowner_Deatils;
use App\Manager_Deatils;
use App\SiteEngineerDetails;
use Barryvdh\DomPDF\Facade as PDF;
use App\CashRecipt;
class SalesController extends Controller
{
    //

   public function tototallog(request $request){
        $date=date('Y-m-d');
         $past = date('Y-m-d',strtotime("-30 days",strtotime($date)));


           if($request->user_id == "All" && $request->fromdate && $request->todate){

                      $orders = Order::where('created_at','>=',$request->fromdate)->where('logistic',"!=",NULL)->where('created_at','<=',$request->todate)->with('userid','req')->get();
           }else if($request->user_id  && $request->fromdate && $request->todate){

              $orders = Order::where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->with('userid','req')->where('logistic','LIKE',"%".$request->user_id."%")->get();
           }else{
                     $orders = Order::where('created_at','LIKE',$date."%")->where('logistic',"!=",NULL)->with('userid','req')->get();

           }

      



      
    return view('/reports.tototallog',['orders'=>$orders]);
   }

public function vcard(request $request){

  if($request->user){
  $ids = ProposedProjects::where('user_id',$request->user)->pluck('p_p_c_id')->toarray();
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
       
         $CustomerDetails = CustomerDetails::pluck('mobile_num')->toarray();
      $date = \Carbon\Carbon::today()->subDays($request->days);
 $pro = DB::table('procurement_details')->select('p_p_c_id','procurement_name','whatsapp','updated_at')->whereIn('p_p_c_id',$sum)->distinct()->groupBy('p_p_c_id')->where('updated_at', '>=', date($date))->where('whatsapp',"!=",NULL)->get();

       $pr = DB::table('procurement_details')->select('p_p_c_id','procurement_name','whatsapp')->whereIn('p_p_c_id',$sum)->distinct()->groupBy('p_p_c_id')->where('whatsapp',"!=",NULL)->pluck('p_p_c_id')->toarray();
         $result = array_diff($sum, $pr);


     }else{

        $pro=[];
        $result =[];
     }


  if($request->id){ 
 
     $p = ProcurementDetails::where('p_p_c_id',$request->id)->where('whatsapp',"!=",NULL)->first();

    if(count($p) > 0){

        $name = "Customer_".$p->procurement_name;
        $whatsapp = $p->whatsapp;
    }else if(count(OwnerDetails::where('p_p_c_id',$request->id)->where('whatsapp',"!=",NULL)->first()) > 0){

        $p = OwnerDetails::where('p_p_c_id',$request->id)->where('whatsapp',"!=",NULL)->first();
        $name = "Customer_".$p->owner_name;
        $whatsapp = $p->whatsapp;
    }else if(count(ContractorDetails::select('p_p_c_id','whatsapp')->where('p_p_c_id',$request->id)->where('whatsapp','!=',NULL)->first()) > 0){
         $p =ContractorDetails::select('p_p_c_id','whatsapp','contractor_name')->where('p_p_c_id',$request->id)->where('whatsapp','!=',NULL)->first();
        $name = "Customer_".$p->contractor_name;
        $whatsapp = $p->whatsapp;
    }else{
       $p =Builder::select('p_p_c_id','whatsapp','builder_name')->where('p_p_c_id',$request->id)->where('whatsapp','!=',NULL)->first();
        $name = "Customer_".$p->builder_name;
        $whatsapp = $p->whatsapp;     
    }







$vcard = new VCard();
// define variables
$lastname = $name;
// add personal data
$vcard->addName($lastname);
// add work data
// $vcard->addCompany('Siesqo');
// $vcard->addJobtitle('Web Developer');
// $vcard->addRole('Data Protection Officer');
// $vcard->addEmail('info@jeroendesloovere.be');
$vcard->addPhoneNumber($whatsapp, 'PREF;WORK');
$vcard->addPhoneNumber($whatsapp, 'WORK');
// $vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
// $vcard->addLabel('street, worktown, workpostcode Belgium');
// $vcard->addURL('http://www.jeroendesloovere.be');
    return $vcard->download();

  } 
    
    return view('/vcard',['pro'=>$pro,'ppppp'=>[],'result'=>$result]);   
 
 
         
}
public function manuvcard(request $request){

     if($request->user){
     $data = ProposedManufacturers::where('user_id',$request->user)->pluck('p_p_c_id')->toarray();
       $u = array_unique($data);
          $date = \Carbon\Carbon::today()->subDays($request->days);
       $pro = DB::table('mprocurement_details')->select('p_p_c_id','name','whatsapp','contact','updated_at')->whereIn('p_p_c_id',$u)->where('whatsapp',"!=",NULL)->where('updated_at', '>=', date($date))->groupBy('p_p_c_id')->distinct('p_p_c_id')->get();
     }else{

        $pro=[];
     }




  if($request->id){  
       $p = Mprocurement_Details::where('p_p_c_id',$request->id)->where('whatsapp',"!=",NULL)->first();
$vcard = new VCard();
// define variables
$lastname = "Customer_".$p->name;
// add personal data
$vcard->addName($lastname);
// add work data
// $vcard->addCompany('Siesqo');
// $vcard->addJobtitle('Web Developer');
// $vcard->addRole('Data Protection Officer');
// $vcard->addEmail('info@jeroendesloovere.be');
$vcard->addPhoneNumber($p->whatsapp, 'PREF;WORK');
$vcard->addPhoneNumber($p->whatsapp, 'WORK');
// $vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
// $vcard->addLabel('street, worktown, workpostcode Belgium');
// $vcard->addURL('http://www.jeroendesloovere.be');
    return $vcard->download();

  } 

  
    return view('/vcard',['ppppp'=>$pro,'pro'=>[],'result'=>[]]);   
 
         
}

public function cvcard(request $request){

 $vcard = new VCard();
// define variables
$lastname ="dedicatedCustomer_".$request->name;
// add personal data
$vcard->addName($lastname);
// add work data
// $vcard->addCompany('Siesqo');
// $vcard->addJobtitle('Web Developer');
// $vcard->addRole('Data Protection Officer');
// $vcard->addEmail('info@jeroendesloovere.be');
$vcard->addPhoneNumber($request->wno, 'PREF;WORK');
$vcard->addPhoneNumber($request->wno, 'WORK');
// $vcard->addAddress(null, null, 'street', 'worktown', null, 'workpostcode', 'Belgium');
// $vcard->addLabel('street, worktown, workpostcode Belgium');
// $vcard->addURL('http://www.jeroendesloovere.be');
    return $vcard->download();

  } 

public function pendingvendor(){

    $vendor = ManufacturerDetail::leftJoin('vendor','vendor.id','=','manufacturer_details.vendortype')->whereIn('manufacturer_details.approve',[1,2])->get();

    return view('/reports.pendingvendor',['mfdetails'=>$vendor]);
}

public function Vendoraccept(request $request){

        $vendor = ManufacturerDetail::where('manufacturer_id',$request->id)->update(['approve'=>NULL]);

         return back()->with('info',"Approved Successfully !!!");

}
public function Vendorreject(request $request){

        $vendor = ManufacturerDetail::where('manufacturer_id',$request->id)->update(['approve'=>2]);

         return back()->with('info',"Approved Successfully !!!");

}

public function closedcontractor(request $request){


     if($request->type == "Contractors"){


   $projects = ProjectDetails::where('project_status',"Closed")->where('quality',"Genuine")->pluck('project_id')->toarray();
    $owners =CustomerDetails::pluck('mobile_num')->toarray();
    $ds = ContractorDetails::whereIn('contractor_contact_no',$owners)->pluck('project_id')->toarray();
    $MamahomePrice = MamahomePrice::pluck('project_id')->toarray();
    $d = array_unique(array_merge($ds,$MamahomePrice));
    $finals = array_diff($projects, $d);  
    $ff = ProposedProjects::where('Contractor',1)->pluck('p_p_c_id')->toarray();
    $f = ContractorDetails::whereIn('contractor_contact_no',$ff)
         ->pluck('project_id')->toarray();    
    $final = array_diff($finals, $f);
    $duplicates = ContractorDetails::where('contractor_contact_no','!=',NULL)
               ->select('contractor_contact_no as number','contractor_name as name','project_id','p_p_c_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
               ->whereIn('project_id',$final)
               ->whereNotIn('contractor_contact_no',$ff)
               ->groupBy('contractor_contact_no')
               ->havingRaw('COUNT(*) >= 1')
               ->orderBy('products_count','DSC')
               ->paginate(100); 
    $pro = ContractorDetails::where('contractor_contact_no','!=',NULL)
    ->whereIn('project_id',$final)->pluck('project_id')->toarray();         

     }elseif($request->type == "Owners"){
       $projects = ProjectDetails::where('project_status',"Closed")->where('quality',"Genuine")->pluck('project_id')->toarray();
    $owners =CustomerDetails::pluck('mobile_num')->toarray();
    $ds = OwnerDetails::whereIn('owner_contact_no',$owners)->pluck('project_id')->toarray();
    $MamahomePrice = MamahomePrice::pluck('project_id')->toarray();
    $d = array_unique(array_merge($ds,$MamahomePrice));
    $finals = array_diff($projects, $d);  
    $ff = ProposedProjects::where('Contractor',1)->pluck('p_p_c_id')->toarray();
    $f = OwnerDetails::whereIn('owner_contact_no',$ff)
         ->pluck('project_id')->toarray();    
    $final = array_diff($finals, $f);
    $duplicates = OwnerDetails::where('owner_contact_no','!=',NULL)
               ->select('owner_contact_no as number','owner_name as name','project_id','p_p_c_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
               ->whereIn('project_id',$final)
               ->whereNotIn('owner_contact_no',$ff)
               ->groupBy('owner_contact_no')
               ->havingRaw('COUNT(*) >= 1')
               ->orderBy('products_count','DSC')
               ->paginate(100); 

    $pro = OwnerDetails::where('owner_contact_no','!=',NULL)
    ->whereIn('project_id',$final)->pluck('project_id')->toarray();

     }elseif($request->type == "SiteEngineer"){
       $projects = ProjectDetails::where('project_status',"Closed")->where('quality',"Genuine")->pluck('project_id')->toarray();
    $owners =CustomerDetails::pluck('mobile_num')->toarray();
    $ds = SiteEngineerDetails::whereIn('site_engineer_contact_no',$owners)->pluck('project_id')->toarray();
    $MamahomePrice = MamahomePrice::pluck('project_id')->toarray();
    $d = array_unique(array_merge($ds,$MamahomePrice));
    $finals = array_diff($projects, $d);  
    $ff = ProposedProjects::where('Contractor',1)->pluck('p_p_c_id')->toarray();
    $f = SiteEngineerDetails::whereIn('site_engineer_contact_no',$ff)
         ->pluck('project_id')->toarray();    
    $final = array_diff($finals, $f);
    $duplicates = SiteEngineerDetails::where('site_engineer_contact_no','!=',NULL)
               ->select('site_engineer_contact_no as number','site_engineer_name as name','p_p_c_id','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
               ->whereIn('project_id',$final)
               ->whereNotIn('site_engineer_contact_no',$ff)
               ->groupBy('site_engineer_contact_no')
               ->havingRaw('COUNT(*) >= 1')
               ->orderBy('products_count','DSC')
               ->paginate(100); 
             
    $pro = SiteEngineerDetails::where('site_engineer_contact_no','!=',NULL)
    ->whereIn('project_id',$final)->pluck('project_id')->toarray();

     }elseif($request->type == "builders"){
       $projects = ProjectDetails::where('project_status',"Closed")->where('quality',"Genuine")->pluck('project_id')->toarray();
    $owners =CustomerDetails::pluck('mobile_num')->toarray();
    $ds = Builder::whereIn('builder_contact_no',$owners)->pluck('project_id')->toarray();
    $MamahomePrice = MamahomePrice::pluck('project_id')->toarray();
    $d = array_unique(array_merge($ds,$MamahomePrice));
    $finals = array_diff($projects, $d);  
    $ff = ProposedProjects::where('Contractor',1)->pluck('p_p_c_id')->toarray();
    $f = Builder::whereIn('builder_contact_no',$ff)
         ->pluck('project_id')->toarray();    
    $final = array_diff($finals, $f);
    $duplicates = Builder::where('builder_contact_no','!=',NULL)
               ->select('builder_contact_no as number','p_p_c_id','builder_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
               ->whereIn('project_id',$final)
               ->whereNotIn('builder_contact_no',$ff)
               ->groupBy('builder_contact_no')
               ->havingRaw('COUNT(*) >= 1')
               ->orderBy('products_count','DSC')
               ->paginate(100); 
             
    $pro = Builder::where('builder_contact_no','!=',NULL)
    ->whereIn('project_id',$final)->pluck('project_id')->toarray();

     }



     else{
            $duplicates =[];
            $pro =[];
            $owners = [];

     }


return view('/reports.closedcontractor',['duplicates'=>$duplicates,'projects'=>$pro,'owners'=>$owners]);
} 
 public function newattend(request $request){
            $date_t=date('Y-m-d');


 if($request->user_id && $request->fromdate && $request->todate){

       $logintime = FieldLogin::with('user')->where('user_id',$request->user_id)->whereDate('logindate','>=',$request->fromdate)->whereDate('logindate','<=',$request->todate)->get();



 }else{

 $logintime = FieldLogin::with('user')->where('logindate','LIKE',$date_t."%")->get();

 }
                 
       $date = "08:30";

   $log = FieldLogin::whereTime('logintime','>',$date.":00") ->whereYear('created_at', Carbon::now()->year)
                        ->whereMonth('created_at', Carbon::now()->month)->get();                          

      




return view('/reports.newattend',['logintime'=>$logintime]);
 }

public function getcustomername(request $request){

     $data = CustomerDetails::where('mobile_num',$request->cat)->orwhere('customer_id',$request->cat)->first();

     return response()->json(['name'=>$data->first_name,'id'=>$data->customer_id]);
}
public function projectmanuppid(){

   $pnumbers = ProcurementDetails::pluck('procurement_contact_no')->toarray();
   $powner = OwnerDetails::pluck('owner_contact_no')->toarray();
   $pcont  = ContractorDetails::pluck('contractor_contact_no')->toarray();
   $pbuild = Builder::pluck('builder_contact_no')->toarray();

    $a1 = array_unique(array_merge($pnumbers,$powner,$pcont,$pbuild));

    $mpnumbers = Mprocurement_Details::pluck('contact')->toarray();
     $mowner = Mowner_Deatils::pluck('contact')->toarray();
     $manager = Manager_Deatils::pluck('contact')->toarray();

     $a2 = array_unique(array_merge($mpnumbers,$mowner,$manager));
 
    $result= array_filter(array_intersect($a2,$a1));


                                                         
    $block = DB::table('project_details')->where('quality',"Genuine")->where('project_status','!=',"Closed")->select('project_id','project_status','quality','project_size')->where('project_size',0)->get();

  return view('/newproposed.projectmanuppid',['result'=>$result]);
}

public function storecontractors(request $request){

       $type = $request->type;

     for($i=0;$i<count($request->number);$i++){

         $data = new ProposedProjects;
         $data->p_p_c_id = $request->number[$i];
         $data->user_id = $request->user;
         $data->Contractor = 1;
         $data->type =$type;
         $data->save();
     }

   return back();
    
}
public function assignclosedcontractors(request $request){

      $data = ProposedProjects::where('user_id',Auth::user()->id)->where('type',"Contractors")->where('Contractor',1)->pluck('p_p_c_id')->toarray();
      $pms = ContractorDetails::where('contractor_contact_no',$request->sid)->orWhere('project_id',$request->sid)->pluck('p_p_c_id')->toarray();
       $ward_id=$request->ward_id;
         if($request->cnd){
             
       $duplicates = ContractorDetails::
       select('contractor_contact_no as number','contractor_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                 ->where('contractor_contact_no',$request->cnd)

                 ->groupBy('contractor_contact_no')
                 ->orderBy('updated_at','ASC')
                 ->havingRaw('COUNT(*) >= 1')
                 ->paginate(20);

         }else{
          $duplicates = ContractorDetails::
          select('contractor_contact_no as number','contractor_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                    ->whereIn('contractor_contact_no',$data)
                    ->groupBy('contractor_contact_no')
                    ->orderBy('updated_at','ASC')
                    ->havingRaw('COUNT(*) >= 1')
                    ->paginate(20);
         }



  

      return view('/reports.assignclosedcontractors',['data'=>$duplicates,'type'=>"Contractors"]);
}




public function csite(request $request){

      $data = ProposedProjects::where('user_id',Auth::user()->id)->where('type',"SiteEngineer")->where('Contractor',1)->pluck('p_p_c_id')->toarray();

      $ward_id='';
      if($request->cnd){
             
        $duplicates = SiteEngineerDetails::
                           select('site_engineer_contact_no as number','site_engineer_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                                     ->where('site_engineer_contact_no',$request->cnd)
                                     ->groupBy('site_engineer_contact_no')
                                     ->havingRaw('COUNT(*) >= 1')
                                     ->paginate(20);
 
          }else{



       $duplicates = SiteEngineerDetails::
                           select('site_engineer_contact_no as number','site_engineer_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                                     ->whereIn('site_engineer_contact_no',$data)
                                     ->groupBy('site_engineer_contact_no')
                                     ->havingRaw('COUNT(*) >= 1')
                                     ->paginate(20);
          }

          if($request->sid) {

            $duplicates = SiteEngineerDetails::
            select('site_engineer_contact_no as number','site_engineer_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                      ->where('site_engineer_contact_no',$request->sid)
        
                      ->groupBy('site_engineer_contact_no')
                      ->orderBy('updated_at','ASC')
                      ->havingRaw('COUNT(*) >= 1')
                      ->paginate(20);
                            }
                  
                      else
                      {
        
                      $duplicates = SiteEngineerDetails::
                      select('site_engineer_contact_no as number','site_engineer_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                                ->whereIn('site_engineer_contact_no',$data)
                                ->groupBy('site_engineer_contact_no')
                                ->orderBy('updated_at','ASC')
                                ->havingRaw('COUNT(*) >= 1')
                                ->paginate(20);
                      }



      return view('/reports.assignclosedcontractors',['data'=>$duplicates,'type'=>"SiteEngineer"]);
}

public function cbuilders(request $request){

      $data = ProposedProjects::where('user_id',Auth::user()->id)->where('type',"builders")->where('Contractor',1)->pluck('p_p_c_id')->toarray();
      $ward_id='';


      if($request->cnd){
             
        $duplicates = Builder::
        select('builder_contact_no as number','builder_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                  ->where('builder_contact_no',$request->cnd)
                  ->groupBy('builder_contact_no')
                  ->havingRaw('COUNT(*) >= 1')
                  ->paginate(20);
 
          }else{


       $duplicates = Builder::
                           select('builder_contact_no as number','builder_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                                     ->whereIn('builder_contact_no',$data)
                                     ->groupBy('builder_contact_no')
                                     ->havingRaw('COUNT(*) >= 1')
                                     ->paginate(20);
          }

          if($request->sid) {

            $duplicates = Builder::
            select('builder_contact_no as number','builder_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                      ->where('builder_contact_no',$request->sid)
        
                      ->groupBy('builder_contact_no')
                      ->orderBy('updated_at','ASC')
                      ->havingRaw('COUNT(*) >= 1')
                      ->paginate(20);
                            }
                  
                      else
                      {
        
                      $duplicates = Builder::
                      select('builder_contact_no as number','builder_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                                ->whereIn('builder_contact_no',$data)
                                ->groupBy('builder_contact_no')
                                ->orderBy('updated_at','ASC')
                                ->havingRaw('COUNT(*) >= 1')
                                ->paginate(20);
                      }

                         


      return view('/reports.assignclosedcontractors',['data'=>$duplicates,'type'=>"builders"]);
}

public function cowners(request $request){

      $data = ProposedProjects::where('user_id',Auth::user()->id)->where('type',"Owners")->where('Contractor',1)->pluck('p_p_c_id')->toarray();
      $ward_id='';


      if($request->cnd){
        $duplicates = OwnerDetails::
        select('owner_contact_no as number','owner_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                  ->where('owner_contact_no',$request->cnd)
                  ->groupBy('owner_contact_no')
                  ->havingRaw('COUNT(*) >= 1')
                  ->paginate(20);
 
          }else{


       $duplicates = OwnerDetails::
                           select('owner_contact_no as number','owner_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                                     ->whereIn('owner_contact_no',$data)
                                     ->groupBy('owner_contact_no')
                                     ->havingRaw('COUNT(*) >= 1')
                                     ->paginate(20);

          }
          if($request->sid) {

            $duplicates = OwnerDetails::
            select('owner_contact_no as number','owner_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                      ->where('owner_contact_no',$request->sid)
        
                      ->groupBy('owner_contact_no')
                      ->orderBy('updated_at','ASC')
                      ->havingRaw('COUNT(*) >= 1')
                      ->paginate(20);
                            }
                  
                      else
                      {
        
                      $duplicates = OwnerDetails::
                      select('owner_contact_no as number','owner_name as name','project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
                                ->whereIn('owner_contact_no',$data)
                                ->groupBy('owner_contact_no')
                                ->orderBy('updated_at','ASC')
                                ->havingRaw('COUNT(*) >= 1')
                                ->paginate(20);
                      }

      return view('/reports.assignclosedcontractors',['data'=>$duplicates,'type'=>"Owners"]);
}






public function assignedclosedcustomers(request $request){

    if($request->user && $request->type =="Owners"){
      
          $ids =  ProposedProjects::where('user_id',$request->user)->where('type',"Owners")->where('Contractor',1)->pluck('p_p_c_id')->toarray();


        $data = OwnerDetails::leftJoin('project_details','project_details.project_id','owner_details.project_id')->where('owner_contact_no','!=',NULL)->where('project_details.project_status',"Closed")
               ->select('owner_details.owner_contact_no as number','owner_details.owner_name as name','owner_details.project_id', DB::raw('COUNT(*) as products_count','owner_details.p_p_c_id'))
               ->whereIn('owner_details.owner_contact_no',$ids)
               ->groupBy('owner_details.owner_contact_no')
               ->havingRaw('COUNT(*) >= 1')
               ->orderBy('products_count','DSC')
                  ->paginate(100);

    }elseif($request->user && $request->type == "Contractors"){
      $ids =  ProposedProjects::where('user_id',$request->user)->where('type',"Contractors")->where('Contractor',1)->pluck('p_p_c_id')->toarray();


      $data = ContractorDetails::leftJoin('project_details','project_details.project_id','contractor_details.project_id')
          ->where('project_details.project_status',"Closed")->where('contractor_contact_no','!=',NULL)
               ->select('contractor_details.contractor_contact_no as number','contractor_details.contractor_name as name','contractor_details.project_id', DB::raw('COUNT(*) as products_count','contractor_details.p_p_c_id'))
            
               ->whereIn('contractor_details.contractor_contact_no',$ids)
               ->groupBy('contractor_details.contractor_contact_no')
               ->havingRaw('COUNT(*) >= 1')
               ->orderBy('products_count','DSC')
               ->paginate(100); 
 }elseif($request->user && $request->type == "SiteEngineer"){
 $ids =  ProposedProjects::where('user_id',$request->user)->where('type',"SiteEngineer")->where('Contractor',1)->pluck('p_p_c_id')->toarray();

  $data = SiteEngineerDetails::leftJoin('project_details','project_details.project_id','site_engineer_details.project_id')->where('project_details.project_status',"Closed")->where('site_engineer_details.site_engineer_contact_no','!=',NULL)
               ->select('site_engineer_details.site_engineer_contact_no as number','site_engineer_details.site_engineer_name as name','site_engineer_details.project_id', DB::raw('COUNT(*) as products_count','p_p_c_id'))
              
               ->whereIn('site_engineer_details.site_engineer_contact_no',$ids)
               ->groupBy('site_engineer_details.site_engineer_contact_no')
               ->havingRaw('COUNT(*) >= 1')
               ->orderBy('products_count','DSC')
               ->paginate(100); 
 }elseif($request->user && $request->type == "builders"){
 
 $ids =  ProposedProjects::where('user_id',$request->user)->where('type',"builders")->where('Contractor',1)->pluck('p_p_c_id')->toarray();

    


 $data = Builder::leftJoin('project_details','project_details.project_id','builders.project_id')->where('project_details.project_status',"Closed")->where('builders.builder_contact_no','!=',NULL)
               ->select('builders.builder_contact_no as number','builders.builder_name as name','builders.project_id', DB::raw('COUNT(*) as products_count','builders.p_p_c_id'))
              
               ->whereIn('builders.builder_contact_no',$ids)
               ->groupBy('builders.builder_contact_no')
               ->havingRaw('COUNT(*) >= 1')
               ->orderBy('products_count','DSC')
               ->paginate(100); 
  }
    else{
        $data=[];
    }
   
      return view('/assignedclosedcustomers',['data'=>$data]);
}

public function deleteassignedclosecustomer(request $request){

  
    $data = ProposedProjects::where('p_p_c_id',$request->id)->where('user_id',$request->user)->delete();
   
     return back()->with('info',"Deleted Successfully !");

}
public function customerstatus(request $request){


  if($request->type == "Looking Credit"){

    $data = CustomerDetails::where('status',"Looking Credit")->select('customer_id','first_name','mobile_num','status','remarks')->get();
  }elseif($request->type == "Others"){
    $data = CustomerDetails::where('status',"Others")->select('customer_id','first_name','mobile_num','status','remarks')->get();
  }elseif($request->type == "Dealers"){
    $data = CustomerDetails::where('status',"Dealers")->select('customer_id','first_name','mobile_num','status','remarks')->get();
  }elseif($request->type == "Closed Customers"){
    $data = CustomerDetails::where('status',"Closed Customers")->select('customer_id','first_name','mobile_num','status','remarks')->get();
  }elseif($request->type == "Blocked"){
     $data = CustomerDetails::where('status',"Blocked")->select('customer_id','first_name','mobile_num','status','remarks')->get();
  }


  else{
      $data=[];
  }

  return view('/reports.customerstatus',['data'=>$data]);
}

public function Holdingproposed(request $request){

     $data = ProposedProjects::where('user_id',Auth::user()->id)->where('p_p_c_id',$request->id)->update(['holding'=>1]);

     return back()->with('info',"Successfully Holeded !");
}
public function removeproposed(request $request){

     $data = ProposedProjects::where('user_id',Auth::user()->id)->where('p_p_c_id',$request->id)->update(['remove'=>1]);

     return back()->with('info',"Successfully Removed !");
}
public function cashrecipt(){

    $orders = DB::table('orders')->select('id','project_id','req_id','main_category','measurement_unit','created_at','status','manu_id')->orderBy('created_at','DESC')->paginate('80');


    return view('/cash',['orders'=>$orders]);



}
function storecash(request $request){

$chekck = CashRecipt::where('order_id',$request->orderid)->first();

        $number2 = $request->Advance;
         $url2 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number2.'&token=fshadvjfa67581232';
         $response2 = file_get_contents($url2);
         $data2 = json_decode($response2,true);
         $dtow2 = $data2['message'];

 if(count($chekck) == 0){

     $data = new CashRecipt;
     $data->order_id = $request->orderid;
$data->name = $request->name;
$data->ship = $request->ship;
$data->bill = $request->bill;
$data->description = $request->desc;
$data->Quantity = $request->Quantity;
$data->price = $request->price;
$data->Advance = $request->Advance;
$data->total = $request->total;
$data->bal  = $request->bal;
$data->unit  = $request->unit;
$data->totalamountinwords = $dtow2;
 $data->save();

 }else{

$chekck->name = $request->name;
$chekck->ship = $request->ship;
$chekck->bill = $request->bill;
$chekck->description = $request->desc;
$chekck->Quantity = $request->Quantity;
$chekck->price = $request->price;
$chekck->Advance = $request->Advance;
$chekck->total = $request->total;
$chekck->bal  = $request->bal;
$chekck->unit  = $request->unit;
$chekck->totalamountinwords = $dtow2;

 $chekck->save();

 }
return back()->with('info',"Successfully Done !");

}
function downloadcash(Request $request){
              
         
        $data =CashRecipt::where('order_id',$request->invoiceno)->first(); 
        view()->share('data',$data);
        $pdf = PDF::loadView('pdf.cashrecipt')->setPaper('a4','portrait');
        if($request->has('download')){
            return $pdf->download(time().'.pdf');
        }else{
            return $pdf->stream('cashrecipt'.'('.$data->id.')'.'.pdf');
        }
    }
    public function cancelcash(request $request){

        $data =CashRecipt::where('order_id',$request->id)->delete();  

        return back()->with('info','Successfully Cancelled !');
    }
}

