<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Tlwards;
use App\FieldLogin;
use App\SubWard;
use App\Ward;
use App\WardMap;
use App\Country;
use Carbon\Carbon;
use App\Supplierdetails;
use App\Requirement;
use Auth;
use App\CustomerOtherNumbers;
use App\ContractorDetails;
use App\SiteEngineerDetails;
use App\Builder;
use App\Manager_Deatils;
use App\Mowner_Deatils;
use App\Salescontact_Details;
use App\SuplpierManufacture;
use App\SupplierOrder;
use App\SupplierProject;
use App\Retailer;
use App\WardAssignment;
use App\SiteAddress;
use App\GstTable;
use App\OwnerDetails;
use DB;
use App\customer_delivery;
use App\SupplierGst;
use App\SupplierInvoice;
use App\CustomerOrder;
use App\Materialhub;
use Illuminate\Support\Collection;
use App\Zone;
use App\User;
use App\Manufacturer;
use App\SubWardMap;
use App\loginTime;
use DateTime;
use App\ProjectDetails;
use App\SuplierDetails;
use App\UpdatedReport;
use App\CustomerInvoice;
use App\MamahomePrice;
use App\Order;
use App\MultipleInvoice;
use App\SupplierInvoicedata;
use App\AttendenceCal;
use App\MultipleSupplierInvoice;
use App\CustomerDetails;
use App\NewCustomerAssign;
use App\Mprocurement_Details;
use App\ProcurementDetails;
date_default_timezone_set("Asia/Kolkata");

class RetailerController extends Controller
{
    public function Retailers(Request $Request){

       $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
       $tlwards = Subward::where('ward_id',$tl)->get();
       
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
        return view('/Retailers',['subwards'=>$subwards,'log'=>$log,'log1'=>$log1,'tlwards'=>$tlwards,'acc'=>$acc,'ward'=>$d]);



}
public function addretail(request $request){

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
  if($request->subward_id != null){
     $subid =$request->subward_id; 
  }else{
     $subid =WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
  }
  



$data = new Retailer;
$data->name =$request->pName;
$data->subward_id =$subid;
$data->longitude =$request->longitude;
$data->latitude =$request->latitude;
$data->email =$request->email;
$data->number =$request->number;
$data->address =$request->address;
$data->Product =$product;
$data->price =$price;
$data->gst =$request->gst;
$data->remarks  =$request->remarks;
$data->test = Auth::user()->id;
$data->save();

return back()->with('success','Added successfully !');

}
public function retailerslot(){
 $data = Retailer::with('subward','user')->get();

return view('/retailerslot',['data'=>$data]);
    
}
 public function editretailer(request $request){

     $data = Retailer::where('id',$request->id)->get();

     return view('/editretailer',['data'=>$data]);
}
public function updateretail(request $request){

$data =Retailer::where('id',$request->id)->first(); 
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

$data->name =$request->pName;
$data->email =$request->email;
$data->number =$request->number;
$data->Product =$product;
$data->price =$price;
$data->gst =$request->gst;
$data->remarks  =$request->remarks;
$data->save();
 
 return back()->with('info','Updated successfully');
}
public function testdistance(request $request){
 

  $longitude  =$request->longitude;
  $latitude=$request->latitude;
  $dis =$request->km;;
  // $cities = SiteAddress::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
  //   ->having('distance', '<=',$dis)
  //   ->orderBy('distance')
  //   ->get();

    
 
   if($request->km){

     $cities = SiteAddress::select('project_id', 'latitude', 'longitude','address',
'updated_at', DB::raw(sprintf(
            '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(latitude)))) AS distance',
            $latitude,
            $longitude
        )))
        ->having('distance','<',1)
        ->with('projectdetails')
        ->orderBy('distance', 'asc')
        ->get();
         
        $subwards = SubWard::where('ward_id',1)->pluck('id')->toArray();
            $wardMaps = WardMap::where('ward_id',1)->first();
            if($wardMaps == null ){
                $wardMaps = "None";
            }
   }else{

        $cities = [];
      $wardMaps = [];
          
   }

   $cities1 = SiteAddress::select('project_id', 'latitude', 'longitude','address',
   'updated_at', DB::raw(sprintf(
               '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(latitude)))) AS distance',
               $latitude,
               $longitude
           )))
           ->having('distance','<',1)
           ->with('projectdetails')
           ->orderBy('distance', 'asc')
           ->pluck('project_id')
           ->toArray();

   $status=['planning','digging','foundation','pillers','walls','roofing'];

    $details=ProjectDetails::whereIn('project_id',$cities1)
    ->whereIn('project_status',$status)
    ->where('deleted_at',null)
    ->where('quality','Genuine')
    ->get();
  

   
    return view('/testdistance',['projects'=>$cities,'wardMaps'=>$wardMaps]);
}
public function getmanudistance(request $request){

  $longitude  =$request->longitude;
  $latitude=$request->latitude;
  $dis =$request->km;;
  // $cities = SiteAddress::select(DB::raw('*, ( 6367 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) AS distance'))
  //   ->having('distance', '<=',$dis)
  //   ->orderBy('distance')
  //   ->get();

    
 
   if($request->km){

     $cities = Manufacturer::select('id','manufacturer_type','address','image','created_at','quality','updated_at','latitude', 'longitude','image', DB::raw(sprintf(
            '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(latitude)))) AS distance',
            $latitude,
            $longitude
        )))
        ->where('quality','Genuine')
        ->having('distance', '<=',$dis)
        ->orderBy('distance', 'asc')
        ->get();
           



           $subwards = SubWard::where('ward_id',1)->pluck('id')->toArray();
            $wardMaps = WardMap::where('ward_id',1)->first();
            if($wardMaps == null ){
                $wardMaps = "None";
            }
   }else{

     $cities = [];
     $wardMaps = [];
   }

  

    
   

   
    
   
  


 

    return view('/getmanudistance',['projects'=>$cities,'wardMaps'=>$wardMaps]);
}

public function unverifiedmanu(request $request){

    $wards = Ward::orderby('ward_name','ASC')->get();
    $wardid = $request->subward;
    $previous = date('Y-m-d',strtotime('-30 days'));
    $today = date('Y-m-d');
    $total = "";
    $site = SiteAddress::all();
    $names = User::get();
    $status =  $request->status;
    $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
         $tll = explode(",", $tl);
        $tlwards = Ward::whereIn('id',$tll)->get();
       
    
    if(!$request->subward && $request->ward){
        $from="";
        $to="";
        if($request->ward == "All"){
            $subwards = SubWard::pluck('id');
        }else{
            $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
        }
        $projectid = Manufacturer::where( 'quality',NULL)
                 ->with('proc')
                 ->whereIn('sub_ward_id',$subwards)
                 ->paginate('20');
        $totalproject =Manufacturer::where( 'quality', NULL)
                
                ->whereIn('sub_ward_id',$subwards)->count();
              
    }
     else if($request->subward == "All" && $request->ward){
        $from=$request->from;
        $to=$request->to;
     
         $subward = Subward::where('ward_id',$request->ward)->pluck('id');
        $projectid = Manufacturer::where('quality',NULL)
                        ->with('proc')
                        ->whereIn('sub_ward_id',$subward)
                        ->paginate('20');
        $totalproject = Manufacturer::where('quality',NULL)
                    
                    ->whereIn('sub_ward_id',$subward)
                    ->count();
    }
    else if($request->subward && $request->ward){
        $from=$request->from;
        $to=$request->to;
         
        $projectid = Manufacturer::where('quality',NULL)
                        ->with('proc')
                        ->where('sub_ward_id',$request->subward)
                        ->paginate('20');
        $totalproject = Manufacturer::where('quality',NULL)
                    
                    ->where('sub_ward_id',$request->subward)
                    ->count();
    }


    else{
            $projectid = new Collection;
            $total = "";
            $from = "";
            $to = "";
            $totalproject = "";
            $site = "";
    }
    return view('manufacturers.unverifiedmanu',['projects'=>$projectid,'wards'=>$wards,'from'=>$from,'to'=>$to,'total'=>$total,'totalproject'=>$totalproject,'site'=>$site,'previous'=>$previous,'today'=>$today,'names'=>$names,'tlwards'=>$tlwards]);

}
public function materialhubmap(request $request){
     $wardMaps = null;
    $projects = null;
    $multisubward = null;
   if($request->wards && $request->subward=="All"){
   
      $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
      $wardMaps = WardMap::where('ward_id',$request->wards)->first();
      if($wardMaps == null ){
          $wardMaps = "None";
      }
       $projects = Materialhub::whereIn('subward_id',$subwards)->get(); 

    }else if($request->subward   && $request->wards ){
       $subwards = SubWard::where('id',$request->subward)->pluck('id')->toArray();
        $wardMaps = SubWardMap::where('sub_ward_id',$request->subward)->first();
      if($wardMaps == null ){
          $wardMaps = "None";
      }
        $projects = Materialhub::whereIn('subward_id',$subwards)
                    ->get();
    }
    $wards = Ward::all();
    $zone = Zone::all();




   return view('/materialhub.materialhubmap',['wardMaps'=>$wardMaps,'projects'=>$projects,'wards'=>$wards,'zone'=>$zone,'multisubward'=>$multisubward]);
}
public function petrol(request $request){
  $other="";
   $today = date('Y-m-d');
   $datetime = new DateTime('tomorrow');
    $fdate = $datetime->format('Y-m-d h:i:s');
       if($request->from && $request->to && !$request->userid){
      $data = LoginTime::where('amount','!=',NULL)->wheredate('logindate','>=',$request->from)->wheredate('logindate','<=',$request->to)->where('approve',NULL)->with('user')->get();
       $totalamout = "";
       $other = "";
       $admindata = [];

    }elseif($request->from && $request->to && $request->userid){
      $data = LoginTime::where('user_id',$request->userid)->where('amount','!=',NULL)->wheredate('logindate','>=',$request->from)->wheredate('logindate','<=',$request->to)->where('approve',NULL)->with('user')->get();
       $totalamout =LoginTime::where('user_id',$request->userid)->where('amount','!=',NULL)->wheredate('logindate','>=',$request->from)->wheredate('logindate','<=',$request->to)->where('approve',NULL)->sum('amount');
       $other = LoginTime::where('user_id',$request->userid)->where('amount','!=',NULL)->wheredate('logindate','>=',$request->from)->wheredate('logindate','<=',$request->to)->where('approve',NULL)->sum('otherexpense');
       $admindata = [];

    }
    else{

      $data = LoginTime::where('amount','!=',NULL)->where('logindate','LIKE',$today.'%')->where('approve',NULL)->with('user')->get();
       $totalamout = "";
       $other = "";
       $admindata =[];
    }
    if(Auth::user()->id == 1){
 if($request->from && $request->to && $request->userid){
      $admindata = LoginTime::where('user_id',$request->userid)->where('amount','!=',NULL)->wheredate('logindate','>=',$request->from)->wheredate('logindate','<=',$request->to)->where('approve','!=',NULL)->with('user')->where('petrolpaymentdone',NULL)->get();
      $totalamout = LoginTime::where('user_id',$request->userid)->where('amount','!=',NULL)->wheredate('logindate','>=',$request->from)->wheredate('logindate','<=',$request->to)->where('approve','!=',NULL)->where('petrolpaymentdone',NULL)->sum('amount');
       $other = LoginTime::where('user_id',$request->userid)->where('amount','!=',NULL)->wheredate('logindate','>=',$request->from)->wheredate('logindate','<=',$request->to)->where('approve','!=',NULL)->where('petrolpaymentdone',NULL)->sum('otherexpense');

     
       $data=[];
     }elseif($request->from && $request->to && !$request->user){
         $admindata = LoginTime::where('amount','!=',NULL)->where('logindate','>=',$request->from)->where('logindate','<=',$request->to)->where('approve','!=',NULL)->with('user')->where('petrolpaymentdone',NULL)->get();
         $totalamout = "";
         $other = "";
         $data=[];
     }else{
      $admindata = LoginTime::where('amount','!=',NULL)->where('logindate','LIKE',$today.'%')->where('approve','!=',NULL)->with('user')->where('petrolpaymentdone',NULL)->get();
         $totalamout="";
         $other = "";
         $data=[];

     }
}
    return view('/petrolallowance.petrol',['data'=>$data,'admindata'=>$admindata,'totalamout'=>$totalamout,'other'=>$other]);
  

}
public function petrolapprove(request $request){

    LoginTime::where('id',$request->id)->update(['approve'=>Auth::user()->id]);

    return back()->with('success','successfully Approved');
}
public function petrolpaymentdone(request $request){

    LoginTime::where('id',$request->id)->update(['petrolpaymentdone'=>Auth::user()->id]);

    return back()->with('success','successfully Approved');
}
public function totalreport(request $request){
     $today = date('y-m-d');
     $group = FieldLogin::where('logindate','LIKE','%'.$today.'%')->pluck('user_id')->toArray();
     $users = User::whereIn('id',$group)->where('department_id','!=',10)->get();
     $data = [];
      $attend =['Call_attended','attend']; 
      $notattend = ['notanswer','Call_Not_Answered'];
      $swit = ['switched_off','switched'];
      if(!$request->user_id){


     foreach ($users as $user) {
       $addprojects = ProjectDetails::where('listing_engineer_id',$user->id)->where('created_at','LIKE','%'.$today.'%')->count(); 
       $addmanu = Manufacturer::where('listing_engineer_id',$user->id)->where('created_at','LIKE','%'.$today.'%')->count(); 
       $updateprojects = UpdatedReport::where('user_id',$user->id)->where('project_id','!=',NULL)->where('created_at','LIKE','%'.$today.'%')->count();
       $updatedmanu = UpdatedReport::where('user_id',$user->id)->where('manu_id','!=',NULL)->where('created_at','LIKE','%'.$today.'%')->count();

       
       //  $callat = UpdatedReport::select('p_p_c_id','quntion','created_at')->groupby('p_p_c_id')->
       // where('user_id',$user)->where('quntion',"attend")->where('user_id',$user->id)->where('quntion',"attend")->where('created_at','LIKE','%'.$today.'%')->distinct('p_p_c_id')->count();

           $callat = DB::table('updated_reports')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'))
                      ->whereIn('quntion',$attend)->where('created_at','LIKE','%'.$today.'%')
                      ->where('user_id',$user->id)
                      ->groupBy('p_p_c_id')
                      ->havingRaw('COUNT(*) >= 1')
                      ->get();




            $callattend = count($callat);



       $callbusy = UpdatedReport::where('user_id',$user->id)->where('quntion',"Busy")->where('created_at','LIKE','%'.$today.'%')->count();


       $notinterest = UpdatedReport::where('user_id',$user->id)->where('quntion',"notinterest")->where('created_at','LIKE','%'.$today.'%')->count();
       $notanswer = UpdatedReport::where('user_id',$user->id)->whereIn('quntion',$notattend)->where('created_at','LIKE','%'.$today.'%')->count();
       $switchoff = UpdatedReport::where('user_id',$user->id)->whereIn('quntion',$swit)->where('created_at','LIKE','%'.$today.'%')->count();
        $logistic = Order::where('logistic','LIKE','%'.$user->id.'%')->where('created_at','LIKE','%'.$today.'%')->count();
        $name = User::where('id',$user->id)->pluck('name')->first();
        
        $order = Order::where('generated_by',$user->id)->where('status','Order Confirmed')->where('created_at','LIKE','%'.$today.'%')->count();
        $enq = Requirement::where('generated_by',$user->id)->where('created_at','LIKE','%'.$today.'%')->count();


      array_push($data,['addproject'=>$addprojects,'addmanu'=>$addmanu,'updateprojects'=>$updateprojects,'updatedmanu'=>$updatedmanu,'callattend'=>$callattend,'callbusy'=>$callbusy,'notinterest'=>$notinterest,'notanswer'=>$notanswer,'switchoff'=>$switchoff,'logistic'=>$logistic,'name'=>$name,'order'=>$order,'enq'=>$enq]);

     }
   }
     else{
         $user = $request->user_id;

           if($request->fromdate && $request->todate && $request->user_id) {

       $addprojects = ProjectDetails::where('listing_engineer_id',$user)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count(); 
       $addmanu = Manufacturer::where('listing_engineer_id',$user)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count(); 
       $updateprojects = UpdatedReport::where('user_id',$user)->where('project_id','!=',NULL)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();
       $updatedmanu = UpdatedReport::where('user_id',$user)->where('manu_id','!=',NULL)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();

       // $callat = UpdatedReport::select('p_p_c_id','quntion','created_at')->groupby('p_p_c_id')->
       // where('user_id',$user)->where('quntion',"attend")->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->distinct('p_p_c_id')->get();
       //      $callattend = count($callat);
 
        $callat = DB::table('updated_reports')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'))
                      ->whereIn('quntion',$attend)
                     ->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)
                      ->where('user_id',$user)
                      ->groupBy('p_p_c_id')
                      ->havingRaw('COUNT(*) >= 1')
                      ->get();




            $callattend = count($callat);




       $callbusy = UpdatedReport::where('user_id',$user)->where('quntion',"Busy")->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();

       $notinterest = UpdatedReport::where('user_id',$user)->where('quntion',"notinterest")->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();

       $notanswer = UpdatedReport::where('user_id',$user)->where('quntion',"notanswer")->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();

       $switchoff = UpdatedReport::where('user_id',$user)->where('quntion',"switched")->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();

        $logistic = Order::where('logistic','LIKE','%'.$user.'%')->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();

        $name = User::where('id',$user)->pluck('name')->first();
        
        $order = Order::where('generated_by',$user)->where('status','Order Confirmed')->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();
          
          $enq = Requirement::where('generated_by',$user)->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();

      array_push($data,['addproject'=>$addprojects,'addmanu'=>$addmanu,'updateprojects'=>$updateprojects,'updatedmanu'=>$updatedmanu,'callattend'=>$callattend,'callbusy'=>$callbusy,'notinterest'=>$notinterest,'notanswer'=>$notanswer,'switchoff'=>$switchoff,'logistic'=>$logistic,'name'=>$name,'order'=>$order,'enq'=>$enq]);
           }

     


     }
    
   

    return view('/reports.totalreport',['data'=>$data]);
}
public function resetmultiinvoice(request $request){

    MultipleInvoice::where('id',$request->id)->delete();

    return back()->with("success",'successfully deleted');
}
public function attendence(request $request){
  if($request->dateform && $request->dateto){
           $time = DB::table("field_login")
             ->wheredate('logindate','>=',$request->dateform)
            ->wheredate('logindate','<=',$request->dateto)
            
            ->where('at_id',NULL)
            ->get();
         
    $attendence = [];
    $extra = [];
     for($i=0;$i<count($time);$i++){
     
        $loginTime = strtotime($time[$i]->logintime);
        $logout =  strtotime($time[$i]->logout);

        $time_diff = $logout - $loginTime;
      
         $min = $time_diff/60;
         $hours = $min/60;

        if($hours > 11){
          $data = $hours;
             
          array_push($extra,['extra'=>$data,'userid'=>$time[$i]->user_id,'mrgremark'=>$time[$i]->remark,'evngmark'=>$time[$i]->logout_remark,'full'=>$time[$i]]);
        }


     array_push($attendence, ['total'=>$hours,'userid'=>$time[$i]->user_id,'mrgremark'=>$time[$i]->remark,'evngmark'=>$time[$i]->logout_remark,'full'=>$time[$i]]);

   }


           }else{



     $date = date('y-m-d');
  $time = DB::table("field_login")

            ->where('created_at','LIKE','%'.$date.'%')
            ->where('at_id',NULL)
            ->get();
         
    $attendence = [];
    $extra = [];
     for($i=0;$i<count($time);$i++){
     
        $loginTime = strtotime($time[$i]->logintime);
        $logout =  strtotime($time[$i]->logout);

        $time_diff = $logout - $loginTime;
      
         $min = $time_diff/60;
         $hours = $min/60;

        if($hours > 11){
          $data = $hours;
             
          array_push($extra,['extra'=>$data,'userid'=>$time[$i]->user_id,'mrgremark'=>$time[$i]->remark,'evngmark'=>$time[$i]->logout_remark,'full'=>$time[$i]]);
        }


     array_push($attendence, ['total'=>$hours,'userid'=>$time[$i]->user_id,'mrgremark'=>$time[$i]->remark,'evngmark'=>$time[$i]->logout_remark,'full'=>$time[$i]]);

   }
    
           }

    return view('/attendence.attendence',['attendence'=>$attendence,'extra'=>$extra]);
}
public function attendenceapproval(request $request){
  $check = AttendenceCal::where('user_id',$request->userid)->where('logindate',$request->logindate)->first();

  DB::table("field_login")->where('id',$request->loginid)->update(['at_id'=>1]);
    
   if(count($check) == 0){
   $data = new AttendenceCal;
   $data->user_id = $request->userid; 
   $data->logindate = $request->logindate; 
   $data->totalhours = $request->totalthours; 
   $data->tlremark = $request->remark; 
   $data->tlid = Auth::user()->id; 
   $data->loginid = $request->loginid;
   $data->save();
   }else{
  $f = AttendenceCal::where('user_id',$request->userid)->where('logindate',$request->logindate)->pluck('totalhours')->first();
     $finalhours= ($f + $request->totalthours);

   $check->user_id = $request->userid; 
   $check->logindate = $request->logindate; 
   $check->totalhours = $finalhours; 
   $check->tlremark = $request->remark; 
   $check->tlid = Auth::user()->id; 
   $check->loginid = $request->loginid;
   $check->save();

   }

   return back()->with('success','successfully Approved');
   

}
public function hrverify(request $request){    
     if($request->dateform && $request->dateto){
            $time = AttendenceCal::where('logindate','>=',$request->dateform)
            ->where('logindate','<=',$request->dateto)
            ->where('hrapprove',NULL)
            ->get();  
     }else{

     $date = date('y-m-d');
     $time = DB::table("attendence_cals")
            ->where('logindate','LIKE','%'.$date.'%')
            ->where('hrapprove',NULL)

            ->get();
     }
     return view('/attendence.hrverify',['time'=>$time]);
}
public function hrapproved(request $request){

   $data = AttendenceCal::where('id',$request->loginid)->update(['hrremark'=>$request->remark,'hrid'=>Auth::user()->id,'hrapprove'=>1,'totalhours'=>$request->totalthours]);

    return back()->with('info',"successfully Approved");
}
public function attendencereport(request $request){
 
   if($request->dateform && $request->dateto){
     
    $data = AttendenceCal::where('user_id',$request->user_id)->where('logindate','>=',$request->dateform)->where('logindate','<=',$request->dateto)->get();

   }else{

     $data = [];
   }

   return view('/attendence.attendencereport',['data'=>$data]);
}
public function deletemultiplesuplier(request $request){

    $data = MultipleSupplierInvoice::where('order_id',$request->id)->delete();

    return back()->with('success','successfully deleted please Regenerate the Lpo!');
}
public function customerfulldetails(){

     $projects = CustomerDetails::with('type')->get();

      $dd = ProjectDetails::getcustomer();
      

  return view('/customerfulldetails',['projects'=>$projects]);
}

public function deleteyes(request $request){

    
    $yup = NewCustomerAssign::where('user_id',$request->user_id)->pluck('customerids')->first();

     function multiexplode ($delimiters,$string) {
            
            $ready = str_replace($delimiters, $delimiters[0], $string);
            $launch = explode($delimiters[0], $ready);
            return  $launch;
        }
   $data = multiexplode(array(",","[","]"),$yup);
      
    
    $find = array($request->id);
    $replace = array("null");


    $da = str_replace($find,$replace,$data);
    $ids = implode(",", $da);
    

    $s = NewCustomerAssign::where('user_id',$request->user_id)->first();

    $s->customerids = $ids;
    $s->save();
          NewCustomerAssign::where('user_id',$request->user_id)->where('cid',$request->id)->delete();

    return back()->with('success','successfully deleted !');

}
public function updatemonthly(request $request){
       

      
    $yup = NewCustomerAssign::where('cid',$request->cid)->first();

             if(count($yup) == 0){
               
             $target = new NewCustomerAssign;
             $target->cid = $request->cid;
             $target->mothlytarget = $request->bus;
             $target->mid = $request->user_id;
             $target->remark = $request->remark;
             $target->datefrom = $request->datefrom;
             $target->dateto = $request->dateto;
             $target->save();

             }else{
             
             $yup->cid = $request->cid;
             $yup->mothlytarget = $request->bus;
             $yup->mid = $request->user_id;
             $yup->remark =  $request->remark;

             $yup->datefrom = $request->datefrom;

             $yup->dateto =  $request->dateto;

             $yup->save();
             }

             return response()->json("successfully Added");
}
public function monthlyinvoicedata(request $request){
  
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
   


 
    return view('/salesreports.monthlyinvoicedata',['final'=>$final]);
}
public function getmonthreport(request $request){
     
      $month = $request->month;
      $year = $request->year;
  
       $products = DB::connection('customer_db')->table('customer_invoices')
            ->whereYear('invoicedate', $year)
            ->whereMonth('invoicedate',$month)
            ->orderby('invoicedate','ASC')->get();
            

   
            $dt = DateTime::createFromFormat('!m', $month);
              $f = $dt->format('F'); 

            



           
     return view('/getmonthreport',['products'=>$products,'month'=>$f,'year'=>$year]);
}

public function editinvoicedata(request $request){



   $data = CustomerInvoice::where('invoiceno',$request->invoiceno)->first();
   $data->order_id = $request->orderid;
   $data->invoicedate = $request->indate;
   $data->invoicenoqnty = $request->quan;
   $data->modeofqunty  = $request->unit;
   $data->mhInvoiceamount  = $request->inamount;
   $data->save();
    

      if($request->spfile){
                   $i = 0;
                 $imageFileName23 = "";
                     foreach($request->spfile as $supplierinvoice){

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
                 }
 if($request->spfile){
  $yup = SupplierInvoicedata::where('invoiceno',$request->invoiceno)->first();
  $yup->supplierinvoiceamount = $request->spamount;
  $yup->supplierinvoice = $imageFileName23;
  $yup->supplierinvoicenumber = $request->spnumber;
  $yup->suppliergstamount = $request->spgst;
  $yup->save();
 }else{
  $yup = SupplierInvoicedata::where('invoiceno',$request->invoiceno)->first();
  $yup->supplierinvoiceamount = $request->spamount;
  $yup->suppliergstamount = $request->spgst;
  $yup->supplierinvoicenumber = $request->spnumber;
  $yup->save();
    

 }
     
       $n = Supplierdetails::where('order_id',$request->orderid)->first();
       $n->amount = $request->spav;
       $n->totalamount = $request->spamount;
       $n->save();

  return back()->with('success','successfully Updated');

}
public function getcustomerid(request $request){
      
          
     $invoiceno = $request->invoiceno;
     $orderid = $request->orderId;
     
     $delivery = DB::table('delivery_details')->where('order_id',$orderid)->first();
    
     $invoice = MamahomePrice::where('invoiceno',$invoiceno)->first();
          if(count($invoice) == 0){

            $invoice = MultipleInvoice::where('invoiceno',$invoiceno)->first();
          }

     $order = Order::where('id',$orderid)->first();
       
        if($request->othernumber){

     $newnumbers = explode(",", $request->othernumber);
   }else{
    $newnumbers = [];
   }






  //       if($invoice->project_id != NULL){

  // $p = ProcurementDetails::where('project_id',$invoice->project_id)->pluck('procurement_contact_no')->toArray();
  // $pi = ContractorDetails::where('project_id',$invoice->project_id)->pluck('contractor_contact_no')->toArray();
  // $pid = Builder::where('project_id',$invoice->project_id)->pluck('builder_contact_no')->toArray();

  // $pids = SiteEngineerDetails::where('project_id',$invoice->project_id)->pluck('site_engineer_contact_no')->toArray();
   
  // $pidss = OwnerDetails::where('project_id',$invoice->project_id)->pluck('owner_contact_no')->toArray();

  //      $merge = array_merge($p,$pi, $pid,$pids,$pidss);
  //      $filtered = array_unique($merge);
      
  //      $cust = array_filter($filtered);
    
      
  //     $name = ProcurementDetails::where('project_id',$invoice->project_id)->pluck('procurement_name')->first();

  //      $subwardid = ProjectDetails::where('project_id',$invoice->project_id)->pluck('sub_ward_id')->first();
  //   }else{
  //     $number = Mprocurement_Details::where('manu_id',$invoice->manu_id)->pluck('contact')->first();
  //     $subwardid = Manufacturer::where('id',$invoice->manu_id)->pluck('sub_ward_id')->first();
  //     $name = Mprocurement_Details::where('manu_id',$invoice->manu_id)->pluck('name')->first();
  //      $mids1 = Mprocurement_Details::where('manu_id',$invoice->manu_id)->pluck('contact')->toArray();
  //    $mids2 = Mowner_Deatils::where('manu_id',$invoice->manu_id)->pluck('contact')->toArray();
  //    $mids3 = Salescontact_Details::where('manu_id',$invoice->manu_id)->pluck('contact')->toArray();
  //    $mids4 = Manager_Deatils::where('manu_id',$invoice->manu_id)->pluck('contact')->toArray();

      
  //       $merges = array_merge($mids1,$mids2,$mids3,$mids4);
  //      $filtered= array_unique($merges);
  //      $cust = array_filter($filtered);


  //   }

 // ---------------------------------------customer-----------------------------------------------------------------
            if($invoice->project_id != NULL){
 $subwardid = ProjectDetails::where('project_id',$invoice->project_id)->pluck('sub_ward_id')->first();
}else{
$subwardid = Manufacturer::where('id',$invoice->manu_id)->pluck('sub_ward_id')->first();

}
              
             $check = CustomerDetails::where('mobile_num',$request->number)->first();
           
           
                  
              if($invoice->project != null){
              $latitude = SiteAddress::where('project_id',$invoice->project_id)->pluck('latitude')->first();
              $long = SiteAddress::where('project_id',$invoice->project_id)->pluck('longitude')->first();

              }else{
                $latitude = Manufacturer::where('id',$invoice->manu_id)->pluck('latitude')->first();
                $long = Manufacturer::where('id',$invoice->manu_id)->pluck('longitude')->first();
              }
           
              if(count($check) == 0){
               
                  $data = new CustomerDetails;
                  $data->first_name = $request->custname;
                  $data->mobile_num = $request->number;
                  $data->sub_customer_type = $request->sub_customertype;
                  $data->customer_type = $request->customertype;
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
               
                
                
                }
                else
                {
                  $customerid =$check->customer_id;
                  $ino = CustomerDetails::where('mobile_num',
                  $request->number)->update([
                    'first_name' => $request->custname,
                    'mobile_num' => $request->number
                   
                    
                    ]); 
                 
              }

               if(count($check) == 0){

                $customerid =$invoiceno; 

             }else{
               $customerid =$check->customer_id; 

             }
              //---------------------------------New number stores--------------------
                  
                  if(count($newnumbers) > 0){
                         for($i=0;$i<sizeof($newnumbers); $i++){
                          

                       $data = new CustomerOtherNumbers;   
                       $data->number = $newnumbers[$i];
                       $data->customer_id = $customerid;
                       $data->save();
                         }
                  }

    // ---------------------------------------supplier-----------------------------------------------------------------
            $supplierid =DB::table('supplierdetails')->where('order_id',$orderid)->first();
                    if(count($supplierid) == 0){
                     $supplierid =DB::table('multiple_supplier_invoices')->where('order_id',$orderid)->first();

                    }
               if($supplierid == NULL){
                  return "please generate LPO for this".$orderid;
               }
            $spid = DB::table('manufacturer_details')->where('company_name',$supplierid->supplier_name)->pluck('supplier_id')->first();


           
           
     // --------------------------------supplier Manufacturer----------------------------------------------
               
      if($invoice->manu_id != null){

                  $smanu = SuplpierManufacture::where('manufacturer_id',$invoice->manu_id)->count();
                  if($smanu == 0){

                     $sdata = new SuplpierManufacture;
                     $sdata->customer_id = $customerid;
                     $sdata->supplier_id = $spid;
                     $sdata->manufacturer_id = $invoice->manu_id;
                     $sdata->save();
                  }else{
                         SuplpierManufacture::where('manufacturer_id',$request->manuid)->update([
                           'customer_id' => $customerid,
                           'supplier_id' => $spid,
                           'manufacturer_id' => $invoice->manu_id
                         ]);

                  }
              }

     // --------------------------------supplier orders----------------------------------------------

        $sorder = SupplierOrder::where('order_id',$orderid)->count();
                       
                   if($sorder == 0){
                      $sorderdata = new SupplierOrder;
                      $sorderdata->supplier_id = $spid;
                      $sorderdata->order_id = $orderid;
                      $sorderdata->customer_id = $customerid;
                      $sorderdata->project_id = $invoice->project_id;
                      $sorderdata->manu_id = $invoice->manu_id;

                     
                      $sorderdata->save();

                   }else{
                    SupplierOrder::where('order_id',$orderid)->update([

                      'supplier_id' => $spid,
                      'order_id' => $orderid,
                      'customer_id' => $customerid,
                      'project_id' => $invoice->project_id,
                      'manu_id'=>$invoice->manu_id
                      

                    ]);
                   }
     // --------------------------------Supplier Project Details----------------------------------------------

                   if($invoice->project_id != null){

                 $sproject = SupplierProject::where('project_id',$invoice->project_id)->count();
                     if($sproject == 0){
                         $sprojectdata = new SupplierProject;
                         $sprojectdata->supplier_id = $spid;
                         $sprojectdata->project_id = $invoice->project;
                         $sprojectdata->customer_id = $customerid;
                         $sprojectdata->save();

                     }else{
                         SupplierProject::where('project_id',$invoice->project)->update([
                         'supplier_id' => $spid,
                         'project_id' => $invoice->project,
                         'customer_id' => $customerid
                         ]);
                     }  
         }
     // --------------------------------Supplier GST Details----------------------------------------------
            $supplierid =DB::table('supplierdetails')->where('order_id',$orderid)->first();

         $sp = DB::table('manufacturer_details')->where('company_name',$supplierid->supplier_name)->first();
        
        $sgstno = SupplierGst::where('suplier_id',$spid)->where('gst_number',$supplierid->gst)->count();
               if($sgstno == 0){
                   $suppliergst = new SupplierGst;
                   $suppliergst->suplier_id = $spid;
                   $suppliergst->gst_number = $supplierid->gst;
                   $suppliergst->state = $supplierid->state;
                   $suppliergst->save();
               }else{
                 SupplierGst::where('suplier_id',$spid)->where('gst_number',$supplierid->gst)->update([
                   'suplier_id' => $spid,
                   'gst_number' => $supplierid->gst,
                   'state'=>$supplierid->state
                 ]);
               }

     // --------------------------------Customer Order Details----------------------------------------------
                $checkorder = CustomerOrder::where('order_id',$orderid)->count();
                   $orderotherexpenses = DB::table('order_expenses')->where('order_id',$orderid)->sum('amount');
                   $orderotherexpensesrem = DB::table('order_expenses')->where('order_id',$orderid)->pluck('remark')->first();
                   $delivery = DB::table('delivery_details')->where('order_id',$orderid)->first();


             if($checkorder == 0){
                $orderdetails = new CustomerOrder;
                $orderdetails->customer_id = $customerid;
                $orderdetails->order_id = $orderid;
                $orderdetails->project_id = $order->project_id;
                $orderdetails->manu_id = $order->manu_id;
                $orderdetails->orderconfirmname = $order->confirmed_by;
                $orderdetails->orderconvertedname = $request->generated_by;
                $orderdetails->orderotherexpenses = $orderotherexpenses;
                $orderdetails->orderotherexpensesremark = $orderotherexpensesrem;
               
                $orderdetails->supplier_id = $spid;
                $orderdetails->save();
             }else{
                 CustomerOrder::where('order_id',$orderid)->update([
                'customer_id' => $customerid,
                'order_id' => $orderid,
                'project_id' => $order->project,
                'manu_id' => $order->manuid,
                'orderconfirmname' => $order->confirmed_by,
                'orderconvertedname' => $order->generated_by,
                'orderotherexpenses' => $orderotherexpenses,
                'orderotherexpensesremark' => $orderotherexpensesrem,
                
                'supplier_id' =>$spid
               
                 ]);
                
             }
             // --------------------------------Customer Project Details----------------------------------------------

         if($invoice->project != null){

         $projects = CustomerProject::where('project_id',$invoice->project)->count();

          if($projects == 0){

             $projectdata = new CustomerProject;
             $projectdata->customer_id = $customerid;
             $projectdata->project_id = $invoice->project;
             $projectdata->ward = $subwardid;
             $projectdata->save();
          }else{
            CustomerProject::where('project_id',$request->project)->update([
             'customer_id' => $customerid,
             'project_id' => $invoice->project,
             'ward' => $subwardid
            ]);
          }
             }
             // --------------------------------Customer Manufacturer Details----------------------------------------------

        if($request->manuid != null){
          $manus = CustomerManufacturer::where('manufacturer_id',$invoice->manuid)->count();
               if($manus == 0){
                 $manudata = new CustomerManufacturer;
                 $manudata->customer_id =$customerid;
                 $manudata->manufacturer_id = $invoice->manuid;
                 $manudata->ward=$subwardid;
                 $manudata->save();
               }else{
                CustomerManufacturer::where('manufacturer_id',$invoice->manuid)->update([
                 'customer_id' =>$customerid,
                 'manufacturer_id' => $invoice->manuid,
                 'ward'=>$subwardid

                ]);
                
               }
          }
             // --------------------------------Customer gst Details----------------------------------------------
          $custgst = GstTable::where('gst_number',$request->gstcust)->count();
          if($custgst == 0){

               $gstdata = new GstTable;
               $gstdata->customer_id = $customerid;
               $gstdata->gst_number = $request->gstcust;
               $gstdata->state = $invoice->state;
               $gstdata->save();
          }else{
            GstTable::where('gst_number',$invoice->customer_gst)->update([
                'customer_id' => $customerid,
               'gst_number' => $request->gstcust,
               'state' =>$invoice->state
            ]);
          }
             // --------------------------------Customer Invoice Details----------------------------------------------
           $customerinvoice = CustomerInvoice::where('invoiceno',$invoice->invoiceno)->count(); 

            if($invoice->igstpercent == null){
               $mode = "CGST & SGST";
               $gst = $invoice->sgstpercent;
            }else{
              $mode="IGST";
              $gst = $invoice->igstpercent;
            }

          if($customerinvoice == 0){
              $invoicedata = new CustomerInvoice;
              $invoicedata->customer_id = $customerid;
              $invoicedata->order_id = $orderid;
              $invoicedata->invoiceno = $request->invoiceno;
              $invoicedata->invoicedate = $request->invoicedate;
              $invoicedata->category = $invoice->category;
              $invoicedata->modeofqunty = $invoice->unit;
              $invoicedata->invoicenoqnty = $invoice->quantity;
              $invoicedata->mhunitprice = $invoice->mamahome_price;
              $invoicedata->mhInvoiceamount = $invoice->amountwithgst;
              $invoicedata->basevalue = $invoice->totalamount;
              $invoicedata->ewaybill = $invoice->e_way_no;
            
              $invoicedata->custmodeofgst = $mode;
              

              $invoicedata->customergstpercent = $gst;
              $invoicedata->customergstamount = $invoice->totaltax;
              $invoicedata->supplier_id = $spid;


              $invoicedata->save();
           }else{
            CustomerInvoice::where('invoiceno',$invoice->invoiceno)->update([
             'customer_id' => $customerid,
              'order_id' => $orderid,
              'invoiceno' => $request->invoiceno,
              'invoicedate' => $request->invoicedate,
              'category' => $invoice->category,
              'modeofqunty' => $invoice->unit,
              'invoicenoqnty' => $invoice->quantity,
              'mhunitprice' => $invoice->mamahome_price,
              'mhInvoiceamount' => $invoice->amountwithgst,
              'basevalue' =>$invoice->totalamount,
             
              'custmodeofgst' => $mode,
              'customergstpercent' => $gst,
              'customergstamount' => $invoice->totaltax,
              'ewaybillno'=>$invoice->e_way_no,'supplier_id'=>$spid
            ]);
           }
             // --------------------------------Customer Delivery Details----------------------------------------------

        // $deliver = customer_delivery::where('invoiceno',$invoice->invoiceno)->count();
        //    if($deliver == 0){

        //         $deliverydata = new customer_delivery;
        //         $deliverydata->customer_id = $customerid;
        //         $deliverydata->invoiceno = $invoice->invoiceno;
        //         $deliverydata->order_id = $orderid;
        //         $deliverydata->deliverylocation = $delivery->location_picture;
        //         $deliverydata->district = $invoice->state;
        //         $deliverydata->trucknumber = $delivery->vehicle_no;
        //         $deliverydata->truckimage = $delivery->truckimage;
        //         $deliverydata->truckvideo = $delivery->truckvideo;
        //         $deliverydata->supplier_id = $spid;

        //         $deliverydata->save();
 

        //     }else{
               
        //        customer_delivery::where('invoiceno',$request->invoiceno)->update([
        //                         'customer_id' => $customerid,
        //                         'invoiceno' => $invoice->invoiceno,
        //                         'order_id' => $orderid,
        //                         'deliverylocation' => $delivery->location_picture,
        //                         'district' => $invoice->state,
        //                         'trucknumber' => $delivery->vehicle_no,
        //                         'truckimage' => $delivery->truckimage,
        //                         'truckvideo' => $delivery->truckvideo,
        //                         'supplier_id' =>$spid
        //                        ]);

        //         }

             // --------------------------------Supplier  Invoice Details----------------------------------------------
              
               $spfile = DB::table('delivery_details')->where('order_id',$orderid)->pluck('spfile')->first();

           $supplierinvoice = SupplierInvoicedata::where('invoiceno',$invoice->invoiceno)->count();
                  if($supplierinvoice == 0){
                      $supplierinvoicedata = new SupplierInvoicedata;
                      $supplierinvoicedata->customer_id = $customerid;
                      $supplierinvoicedata->supplier_id = $spid;
                      $supplierinvoicedata->order_id = $orderid;
                      $supplierinvoicedata->invoiceno = $request->invoiceno;
                      $supplierinvoicedata->supplierinvoicedate = $supplierid->created_at;
                      $supplierinvoicedata->supplierinvoicenumber = $supplierid->lpo;
                      $supplierinvoicedata->supplierinvoiceamount = $supplierid->totalamount;
                      $supplierinvoicedata->supplierinvoice = $spfile;
                     
                      $supplierinvoicedata->state = $supplierid->state;
                      $supplierinvoicedata->save();


                  }else{
                    SupplierInvoicedata::where('invoiceno',$invoice->invoiceno)->update([
                      'customer_id' => $customerid,
                      'order_id' => $orderid,
                      'invoiceno' => $request->invoiceno,
                      'supplierinvoicedate' => $supplierid->created_at,
                      'supplierinvoicenumber' => $supplierid->lpo,
                      'supplierinvoiceamount' => $supplierid->totalamount,
                      'supplier_id'=>$spid,
                      'state'=>$supplierid->state,
                       'supplierinvoice' => $spfile
              
 
                    ]);
                  }


     return back()->with('success',"customer id".$customerid);



}
public function getsuperdetails(request $request){

     $data = CustomerDetails::where('mobile_num',$request->number)->first();
        if(count($data->customer_id) > 0){

         $gst = GstTable::where('customer_id',$data->customer_id)->pluck('gst_number')->first();
        }else{
           $gst = "";
        }

        return response()->json(['data'=>$data,'gst'=>$gst]);

}

}
