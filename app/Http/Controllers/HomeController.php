<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Barryvdh\Debugbar\Facade as Debugbar;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Notifications\FiledLogin;
use App\CategoryTarget;
use App\GstTable;
use App\Mail\orderconfirmation;
use Illuminate\Support\Facades\Crypt;
use App\Mail\invoicomee;
use App\FLOORINGS;
use App\Department;
use App\AssignCategory;
use App\assign_manufacturers;
use App\User;
use App\CustomerDetails;
use App\Builder;
use App\UpdatedReport;
use App\Group;
use App\Salesofficer;
use App\Ward;
use App\MultipleInvoice;
use App\Tlwards;
use App\Country;
use App\SubWard;
use App\WardAssignment;
use App\ProjectDetails;
use App\SiteAddress;
use App\Territory;
use App\State;
use App\Zone;
use App\Checklist;
use App\training;
use App\loginTime;
use App\Requirement;
use App\ProcurementDetails;
use App\SiteEngineerDetails;
use App\OwnerDetails;
use App\ConsultantDetails;
use App\attendance;
use App\ContractorDetails;
use App\salesassignment;
use App\Report;
use App\RoomType;
use App\WardMap;
use Auth;
use DB;
use App\Manager_Deatils;
use App\Salescontact_Details;
use App\Mowner_Deatils;
use App\EmployeeDetails;
use Validator;
use App\BankDetails;
use App\Asset;
use App\AssetInfo;
use App\Category;
use App\SubCategory;
use App\CategoryPrice;
use App\ManufacturerDetail;
use App\Certificate;
use App\MhInvoice;
use App\ProjectUpdate;
use App\ActivityLog;
use App\Order;
use App\Stages;
use App\Dates;
use App\Map;
use App\brand;
use App\Point;
use App\Message;
use App\ZoneMap;
use App\SubWardMap;
use App\UserLocation;
use App\AssignStage;
use App\History;
use App\Assignenquiry;
use App\ProjectImage;
use App\EnquiryQuantity;
use App\AssignNumber;
use App\MamaSms;
use Carbon\Carbon;
use App\numbercount;
use App\numbers;
use App\Payment;
use App\Detail;
use App\Projection;
use App\Conversion;
use App\Utilization;
use App\Planning;
use App\CapitalExpenditure;
use App\OperationalExpenditure;
use App\NumberOfZones;
use App\Pricing;
use GuzzleHttp\Client;
use App\Manufacturer;
use App\Manufacturers;
use App\FieldLogin;
use App\BreakTime;
use App\PaymentDetails;
use App\MamahomePrice;
use App\Supplierdetails;
use App\Mprocurement_Details;
use App\Gst;
use App\SupplierInvoice;
use App\assign_states_dist;
use App\CustomerType;
use App\PaymentHistory;
use App\Denomination;
use App\CustomerGst;
use App\GradeRange;
use App\customerassign;
use App\VisitedCustomers;
use Spatie\Activitylog\Models\Activity;
use App\states_dists;
date_default_timezone_set("Asia/Kolkata");
class HomeController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user= Auth::user();
            $message = Message::where('read_by','NOT LIKE',"%".$this->user->id."%")->count();
            View::share('chatcount', $message);
            $trainingCount = training::where('dept',$this->user->department_id)
                            ->where('designation',$this->user->group_id)
                            ->where('viewed_by','NOT LIKE',"%".$this->user->id."%")->count();
            View::share('trainingCount',$trainingCount);
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function authlogin()
    {
        date_default_timezone_set("Asia/Kolkata");
        $check = loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->get();
        if(count($check)==0){
            $login = New loginTime;
            $login->user_id = Auth::user()->id;
            $login->logindate = date('Y-m-d');
            $login->loginTime = date('H:i A');
            $login->logoutTime = "N/A";
            $login->save();
        }
        $activity = new ActivityLog;
        $activity->time = date('Y-m-d H:i A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->activity = Auth::user()->name." has logged in to the system at ".date('H:i A');
        $activity->save();
    return redirect('/home');
    }
    public function authlogout(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");
        $activity = new ActivityLog;
        $activity->time = date('Y-m-d H:i A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->activity = Auth::user()->name." has logged out of the system at ".date('H:i A');
        $activity->save();
        Auth()->logout();
        $request->session()->invalidate();
        return redirect('/');
    }
    public function inputview(Request $request)
    {
           


        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $category = Category::where('category_name','!=',"STEEL")->get();
        $steel = Category::where('category_name',"STEEL")->get();
        $brand = brand::leftjoin('category','category.id','=','brands.category_id')
                ->select('brand')->get();

        $depart1 = [6];
        $depart2 = [7];
        $depart = [2,4,8,6,7,15,17,16,1,11,22,10];
        $projects = ProjectDetails::where('project_id', $request->projectId)->first();
        $users = User::whereIn('group_id',$depart)->get();
       $users1 = User::whereIn('group_id',$depart1)->where('department_id','!=',10)->where('name',Auth::user()->name)->get();
       $users2 = User::whereIn('group_id',$depart2)->where('department_id','!=',10)->where('name',Auth::user()->name)->get();
       $states = State::all();
       $categories = Category::all();
        return view('inputview',['category'=>$category,'users'=>$users,'users1'=>$users1,'users2'=>$users2,'projects'=>$projects,'brand'=>$brand,'log'=>$log,'log1'=>$log1,'states'=>$states,'steel'=>$steel,'categories'=>$categories]);
    }
    public function inputdata(Request $request)
    {

          
           $user_id = User::where('id',Auth::user()->id)->pluck('id')->first();
           $cat = category::where('id',$request->cat)->pluck('id')->first();

         if($request->name){

              $billaddress = $request->shipaddress;
         }
         else{
            
           $billaddress = $request->billaddress;
         }


            if($request->subcat != null){

             if(count($request->withgst) == 0){
            $validator = Validator::make($request->all(), [
            'subcat' => 'required'


            ]);
            if ($validator->fails()) {
                return back()
                ->with('NotAdded','Select Category Before Submit')
                ->withErrors($validator)
                ->withInput();
            }
             }
          
            $sub = SubCategory::where('id',$request->subcat)->pluck('sub_cat_name')->first();
            $qnty = $sub.":".$request->totalquantity;//new code
            $sub_cat_name = SubCategory::whereIn('id',$request->subcat)->pluck('id')->toArray();
            $subcategories = implode(", ", $sub_cat_name);
            // fetching brands
            $brand_ids = SubCategory::whereIn('id',$request->subcat)->pluck('brand_id')->toArray();
            $brand = brand::whereIn('id',$brand_ids)->pluck('brand')->toArray();
            $brandnames = implode(", ", $brand);

            $category_ids = SubCategory::whereIn('id',$request->subcat)->pluck('category_id')->toArray();
            $category= Category::whereIn('id',$category_ids)->pluck('category_name')->toArray();
            $categoryNames = implode(", ", $category);
            $totalquantity = $request->totalquantity;
            $price = $request->prices;
            $steel_price = null;
            $steel_quantity = null;
           }
           else if($request->subcat == null &&  count($request->subcatsteel) != 0 && count($request->cat255[0]) == 0 ){
            // $sub_cat_name = SubCategory::whereIn('id',$request->subcatsteel)->pluck('sub_cat_name')->toArray();
             if(count($request->withgst) == 0){
            $validator = Validator::make($request->all(), [
            'subcatsteel' => 'required'
            ]);
            if ($validator->fails()) {
                return back()
                ->with('NotAdded','Select Category Before Submit')
                ->withErrors($validator)
                ->withInput();
            }
          }
            $subcategories = implode(", ",$request->subcatsteel);
            // fetching brands
            $brand_ids = SubCategory::whereIn('id',$request->subcatsteel)->pluck('brand_id')->toArray();
            $brand = brand::whereIn('id',$brand_ids)->pluck('brand')->toArray();
            $brandnames = implode(", ", $brand);

            $categoryNames = "STEEL";
            $totalquantity = null;
            $price = null;
            $get = implode(", ",array_filter($request->steelquan));
            $another = explode(", ",$get);
                
            ($request->subcatsteel);
             for($i = 0;$i < count($request->subcatsteel); $i++){
                if($i == 0){
                    $sub = SubCategory::where('id',$request->subcatsteel[$i])->pluck('sub_cat_name')->first();
                         $qnty = $sub.":".$another[$i];
                    }else{
                         $sub = SubCategory::where('id',$request->subcatsteel[$i])->pluck('sub_cat_name')->first();
                         $qnty .= ", ".$sub.":".$another[$i];

                     }
                 }
            $steel_quantity = implode(",",array_filter($request->steelquan));
            $steel_price = implode(",",array_filter($request->steelprice));
           }

           else if(count($request->cat255[0]) > 0 && count($request->withgst[0]) == 0 && $request->subcat == null){
                    
                $categoryNames =Category::where('id',$request->cat255[0])->pluck('category_name')->first();
                $brandnames =  brand::where('id',$request->brand55[0])->pluck('brand')->first();
               $subcategories = "";
               $qnty = array_sum($request->quan1);
               $price =$request->price1[0];
               $totalquantity=array_sum($request->quan1);
               $steel_price=0;
               $steel_quantity=0;
           }

           else{
               $categoryNames = "FLOORINGS";
               $brandnames =  brand::where('id',$request->cat25[0])->pluck('brand')->first();
               $subcategories = "";
               $qnty = array_sum($request->quan);
               $price =$request->price[0];
               $totalquantity=array_sum($request->quan);
               $steel_price=0;
               $steel_quantity=0;
           }

            $points = new Point;
            $points->user_id = $request->initiator;
            $points->point = 100;
            $points->type = "Add";
            $points->reason = "Generating an enquiry";
            $points->save();
            $var = count($request->subcat);
            $var1 = count($brandnames);
       // dd($request->totalquantity);

       if($request->framework){
          $lng = implode(",",$request->framework);
       }else{
        $lng = "";
       }


     


        $x = DB::table('requirements')->insert(['project_id'    =>$request->selectprojects,
                                                'main_category' => $categoryNames,
                                                'brand' => $brandnames,
                                                'sub_category'  =>$subcategories,
                                                'follow_up' =>'',
                                                'follow_up_by' =>'',
                                                'material_spec' =>'',
                                                'referral_image1'   =>'',
                                                'referral_image2'   =>'',
                                                'requirement_date'  =>$request->txtDate,
                                                'measurement_unit'  =>$request->measure != null?$request->measure:'',
                                                'unit_price'   => '',
                                                 'quantity'     =>$qnty,
                                                'total'   =>0,
                                                'notes'  =>$request->eremarks,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s'),
                                                'status' => "Enquiry On Process",
                                                'dispatch_status' => "Not yet dispatched",
                                                'generated_by' => $request->initiator,
                                                'billadress'=>$billaddress,
                                                'ship' =>$request->shipaddress,
                                                'price' =>$price,
                                                'State'=>$request->states,
                                                'total_quantity' =>$totalquantity,
                                                'steelprice'=>$steel_price,
                                                'steelquantity'=>$steel_quantity,
                                                'language'=>$lng,
                                                'cid'=>$request->cid
                                                
                                        ]);
                                $x = DB::getPdo()->lastInsertId();
        
// --------------------------------------flooring detaisl-----------------------------------------------------------
               
        if(count($request->withgst[0]) != 0){
           
$category =48;
$subcat = $request->cat25;           
$unitprice = $request->unitprice;
$desc = $request->desc;
$hsn = $request->hsn;
$sqrt = $request->sqrt;
$l = $request->l;
$b = $request->b;
$quan = $request->quan;
$price = $request->price;
$unit = $request->unit;
$state = $request->state;
$gst = $request->gst;
$withgst = $request->withgst;
$withoutgst = $request->withoutgst;
$unitprice = $request->unitprice;

  for ($i=0; $i <count($gst); $i++) { 
       
       $newdata =  new FLOORINGS;
       $newdata->category =48;
       $newdata->subcat =$subcat[$i];
       $newdata->unitprice =$unitprice[$i];
       $newdata->description =$desc[$i];
       $newdata->sqrt =$sqrt[$i];
       $newdata->l =$l[$i];
       $newdata->b =$b[$i];
        $newdata->hsn =$hsn[$i];
       $newdata->unit =$unit[$i];
       $newdata->quan =$quan[$i]; 
       $newdata->price =$price[$i];
       $newdata->state =$state[$i];
       $newdata->gst =$gst[$i];
       $newdata->withgst = $withgst[$i];
       $newdata->withoutgst = $withoutgst[$i];
       $newdata->req_id = $x;
       $newdata->unitprice = $unitprice[$i];

      $newdata->save();
  }

       


        }

if(count($request->cat255[0]) != 0){
           
$category = $request->cat255;
$subcat = $request->brand55;           
$unitprice = $request->unitprice1;
$desc = $request->desc1;
$hsn = $request->hsn1;
$sqrt = $request->sqrt1;
$quan = $request->quan1;
$price = $request->price1;
$unit = $request->unit1;
$state = $request->state12;
$gst = $request->gst1;
$withgst = $request->withgst1;
$withoutgst = $request->withoutgst1;

  for ($i=0; $i <count($gst); $i++) { 
       
       $newdata =  new FLOORINGS;
       $newdata->category =$category[$i];
       $newdata->subcat =$subcat[$i];
       $newdata->unitprice =$unitprice[0];
       $newdata->description =$desc[$i];
       $newdata->sqrt =$sqrt[$i];
        $newdata->hsn =$hsn[$i];
       $newdata->unit =$unit[$i];
       $newdata->quan =$quan[$i]; 
       $newdata->price =$price[$i];
       $newdata->state =$state[$i];
       $newdata->gst =$gst[$i];
       $newdata->withgst = $withgst[$i];
       $newdata->withoutgst = $withoutgst[$i];
       $newdata->req_id = $x;
       $newdata->unitprice = $unitprice[$i];

      $newdata->save();
  }

       


        }




// -------------------------------------------------end flooring------------------------------------------------------------
       
       //customer gst 
        if($request->cgst != null){
            $number = $request->econtact;
            $check = CustomerGst::where('customer_phonenumber',$number)->count();
            
            if($check == 0){
            $customergst = strtoupper($request->cgst);
            $contractor = ContractorDetails::where('project_id',$request->selectprojects)->pluck('contractor_contact_no')->first();
            $site = SiteEngineerDetails::where('project_id',$request->selectprojects)->pluck('site_engineer_contact_no')->first();
            $owner = OwnerDetails::where('project_id',$request->selectprojects)->pluck('owner_contact_no')->first();
            $consult = ConsultantDetails::where('project_id',$request->selectprojects)->pluck('consultant_contact_no')->first();

            $customer = new CustomerGst;
            $customer->customer_gst = $customergst;
            $customer->customer_phonenumber = $number;
            $customer->contractor_no = $contractor;
            $customer->site_engineer_no = $site;
            $customer->owner_no = $owner;
            $customer->consultant_no = $consult;
            $customer->save();
            // $country_code = Country::pluck('country_code')->first();
            // $zone = Zone::pluck('zone_number')->first();
            // $cus_id = "MH_".$country_code."_".$zone."_C".$customer->id;
            // $cid = CustomerGst::where('customer_phonenumber',$number)->update(['customer_id'=>$cus_id]);
            }
        }
       // end
        // activitylog
        $activity = new ActivityLog;
        $activity->time = date('Y-m-d H:iconv(in_charset, out_charset, str) A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->activity = Auth::user()->name." has added a new requirement for project id: ".$request->selectprojects." at ".date('H:i A');
        $uproject = ProjectDetails::where('project_id',$request->selectprojects)->pluck('updated_by')->first();
        $qproject = ProjectDetails::where('project_id',$request->selectprojects)->pluck('quality')->first();
        $fproject = ProjectDetails::where('project_id',$request->selectprojects)->pluck('followup')->first();
        $eproject = Requirement::where('project_id',$request->selectprojects)->pluck('generated_by')->first();
         $project = ProjectDetails::where('project_id',$request->selectprojects)->pluck('sub_ward_id')->first();
        $activity->sub_ward_id = $project;
        $activity->updater = $uproject;
        $activity->quality = $qproject;
        $activity->followup = $fproject;
        if(count($eproject) != 0){
        
       $activity->enquiry = $eproject;
       }
        else{
       $activity->enquiry ="null";

        }

        $activity->project_id = $request->selectprojects;
        // $activity->req_id = $requirement->id;
        $activity->typeofactivity = "Add Enquiry";
        $activity->save();
        // $y = DB::table('quantity')->insert(['req_id' =>$request->requirements->id,
        //                                     'project_id'=>$request->selectprojects


        //         ]);
        if($x)
        {
            return back()->with('success','Enquiry Raised Successfully !!!');
        }
        else
        {
            return back()->with('success','Error Occurred !!!');
        }
    }

    public function getProjects(Request $request)
    {
        $contact = $request->contact;
        $x = ProjectDetails::join('procurement_details','procurement_details.project_id','=','project_details.project_id')
                            ->where('procurement_details.procurement_contact_no',$contact)
                            ->get();
        if(count($x)==0){
            $x = ProjectDetails::join('consultant_details','consultant_details.project_id','=','project_details.project_id')
                                ->where('consultant_details.consultant_contact_no',$contact)
                                ->get();
            if(count($x) == 0)
            {
                $x = ProjectDetails::join('site_engineer_details','site_engineer_details.project_id','=','project_details.project_id')
                        ->where('site_engineer_details.site_engineer_contact_no',$contact)
                        ->get();
                if(count($x) == 0)
                {
                    $x = ProjectDetails::join('contractor_details','contractor_details.project_id','=','project_details.project_id')
                        ->where('contractor_details.contractor_contact_no',$contact)
                        ->get();
                    if(count($x) == 0)
                    {
                        $x = ProjectDetails::join('owner_details','owner_details.project_id','=','project_details.project_id')
                                            ->where('owner_details.owner_contact_no',$contact)
                                            ->get();
                        if(count($x) == 0){
                            $x = 'Nothing Found';
                        }
                    }
                }
            }
        }
            $id = CustomerDetails::where('mobile_num',$contact)->pluck('customer_id')->first();

            $gst = GstTable::where('customer_id',$id)->pluck('gst_number')->first();
            $cname =CustomerDetails::where('customer_id',$id)->pluck('first_name')->first(); 
        
        if($x)
        {
            return response()->json(['x'=>$x,'id'=>$id,'gst'=>$gst,'cname'=>$cname]);
        }
        else
        {
            return response()->json('Nothing Found');
        }
    }


 public function getmanuProjects(Request $request)
    {
        $contact = $request->contact;
        
    
       $x = Manufacturer::join('mprocurement_details','mprocurement_details.manu_id','=','manufacturers.id')
                                ->where('mprocurement_details.contact',$contact)
                                ->get();
        if(count($x)==0){
               $x = Manufacturer::join('manager_details','manager_details.manu_id','=','manufacturers.id')
                            ->where('manager_details.contact',$contact)
                            ->get();

            if(count($x) == 0)
            {
                $x = Manufacturer::join('mowner_details','mowner_details.manu_id','=','manufacturers.id')
                        ->where('mowner_details.contact',$contact)
                        ->get();
                if(count($x) == 0)
                {                                                                                                                                                               
                    $x = Manufacturer::join('salescontact_details','salescontact_details.manu_id','=','manufacturers.id')
                        ->where('salescontact_details.contact',$contact)
                        ->get();
                        if(count($x) == 0){
                            $x = 'Nothing Found';
                        }
                }
            }
        }
            $id = CustomerDetails::where('mobile_num',$contact)->pluck('customer_id')->first();
            $gst = GstTable::where('customer_id',$id)->pluck('gst_number')->first();
            $cname =CustomerDetails::where('customer_id',$id)->pluck('first_name')->first(); 
        
        if($x)
        {
            return response()->json(['x'=>$x,'id'=>$id,'gst'=>$gst,'cname'=>$cname]);
        }
        else
        {
            return response()->json('Nothing Found');
        }
    }






    public function enquirysheet1(Request $request)
    {
          
        $totalofenquiry = "";
        $totalenq = "";
        
        $s = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
       $etl = explode(",",$s);
       $wardwise =Ward::whereIn('id',$etl)->get();

        $wardss = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
         $tlward = explode(",",$wardss);
         $totalofenquiry = "";
        $totalenq = "";
        $converter = user::get();
        $ward = Ward::get();
        $wards = SubWard::orderby('sub_ward_name','ASC')->get();

        $sub = SubWard::whereIn('ward_id',$tlward)->pluck('id');
        $pids = ProjectDetails::whereIn('sub_ward_id',$sub)->pluck('project_id');
        $this->variable=$pids;



        $ward = ward::orderby('ward_name','ASC')->get();
        $category = Category::all();
        $depart = [1,6,7,8,11,15,16,17,22,2];
        $initiators = User::whereIn('group_id',$depart)->get();
             // dd($request->status);
        if($request->status && !$request->category){
            if($request->status != "all"){

                $enquiries = Requirement::where('status','like','%'.$request->status)
                            ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')
                            ->select('requirements.*')
                            ->get();
               $converter = user::get();
            $totalenq = count($enquiries);
                 
                }
            else{

                $enquiries = Requirement::where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')

                            ->get();
                $converter = user::get();
            $totalenq = count($enquiries);
                
            }
        }elseif($request->status && $request->category){
            if($request->status != "all"){

                $enquiries = Requirement::where('status','like','%'.$request->status)
                            ->where('main_category',$request->category)
                            ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')

                            ->get();
                $converter = user::get();
            $totalenq = count($enquiries);
               
            }else{

                $enquiries = Requirement::where('main_category',$request->category)
                            ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')

                            ->get();
                $converter = user::get();
            $totalenq = count($enquiries);

               
            }
        }elseif($request->from && $request->to  && !$request->initiator && !$request->category && !$request->ward){
            // only from and to

            $from = $request->from;
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::orderBy('created_at','DESC')
                            ->where('created_at','LIKE',$from."%")
                            ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')
                            
                            ->get();
            $converter = user::get();
            $totalenq = count($enquiries);

            }else{
                $enquiries = Requirement::orderBy('created_at','DESC')
                            ->wheredate('created_at','>=',$from)
                            ->wheredate('created_at','<=',$to)
                            ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')

                            ->get();
            $converter = user::get();
            $totalenq = count($enquiries);

            }
            
        }elseif(!$request->from && !$request->to && !$request->initiator && !$request->category && $request->ward && $request->enqward){
          
           if($request->ward == "All"){
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');    
            }else{
            $subwardid = Subward::where('id',$request->ward)->pluck('id');    
            }
            // only ward
            $enquiries = Requirement::leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                        ->whereIn('project_details.sub_ward_id',$subwardid)
                        ->where('requirements.status','!=',"Enquiry Cancelled")
                 ->orderby('requirements.created_at','DESC')

                        ->get();
            $converter = user::get();
            $totalenq = count($enquiries);
            
        }elseif(!$request->from && !$request->to && !$request->initiator && $request->category && !$request->ward && !$request->enqward){
            // only category

            $enquiries = Requirement::where('main_category',$request->category)
                        ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')
                       
                        ->get();
            $totalenq = count($enquiries);


          $converter = user::get();

            $totalofenquiry = Requirement::where('main_category',$request->category)->where('requirements.status','!=',"Enquiry Cancelled")->sum('quantity');
        }elseif(!$request->from && !$request->to && $request->initiator && !$request->category && !$request->ward){
            // only initiator
            $enquiries = Requirement::where('generated_by',$request->initiator)
                        ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')

                        ->get();
            $converter = user::get();
            $totalenq = count($enquiries);

        }elseif($request->from && $request->to && $request->initiator && $request->category && $request->ward && $request->enqward){
            // everything
        
            if($request->ward == "All"){
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');    
            }else{
            $subwardid = Subward::where('id',$request->ward)->pluck('id');    
            }
            $from = $request->from;
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                ->whereIn('project_details.sub_ward_id',$subwardid)
                ->where('requirements.generated_by',$request->initiator)
                ->where('requirements.created_at','LIKE',$from."%")
                ->where('requirements.status','!=',"Enquiry Cancelled")
                ->where('requirements.main_category','LIKE',"%".$request->category."%")
                 ->orderby('requirements.created_at','DESC')

                ->get();
            $converter = user::get();
            $totalenq = count($enquiries);
               
            }else{
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');
               
                $enquiries = Requirement::leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                            ->whereIn('project_details.sub_ward_id',$subwardid)
                            ->where('requirements.generated_by',$request->initiator)
                            ->where('requirements.created_at','>=',$from)
                            ->where('requirements.created_at','<=',$to)
                            ->where('requirements.status','!=',"Enquiry Cancelled")
                            ->where('requirements.main_category','LIKE',"%".$request->category."%")
                            ->orderby('requirements.created_at','DESC')
                            ->get();
                $converter = user::get();
            $totalenq = count($enquiries);

            }
        }elseif($request->from && $request->to && !$request->initiator && !$request->category && $request->ward && $request->enqward){

            // from, to and ward
            if($request->ward == "All"){
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');    
            }else{
            $subwardid = Subward::where('id',$request->ward)->pluck('id');    
            }
            $from = $request->from;
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                ->orderBy('requirements.created_at','DESC')
                ->whereIn('project_details.sub_ward_id',$subwardid)
                ->where('requirements.created_at','LIKE',$from."%")
                ->where('requirements.status','!=',"Enquiry Cancelled")
                
                ->get();
                $converter = user::get();
            $totalenq = count($enquiries);

            }else{

                $enquiries = Requirement::leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                ->orderBy('requirements.created_at','DESC')
                ->whereIn('project_details.sub_ward_id',$subwardid)
                ->where('requirements.created_at','>=',$from)
                ->where('requirements.created_at','<=',$to)
                ->where('requirements.status','!=',"Enquiry Cancelled")
                

                ->get();
                $converter = user::get();
            $totalenq = count($enquiries);
                
            }
        }elseif($request->from && $request->to && $request->initiator && !$request->category && !$request->ward){
            // from, to and initiator
            $from = $request->from;
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::orderBy('created_at','DESC')
                ->where('generated_by','=',$request->initiator)
                ->where('created_at','LIKE',$from."%")
                ->where('status','!=',"Enquiry Cancelled")
                 
                ->get();
               $converter = user::get();
            $totalenq = count($enquiries);
                
            }else{
                $enquiries = Requirement::orderBy('created_at','DESC')
                ->where('generated_by','=',$request->initiator)
                ->wheredate('created_at','>=',$from)
                      
                ->wheredate('created_at','<=',$to)
                ->where('status','!=',"Enquiry Cancelled")
                 
                ->get();
               $converter = user::get();
            $totalenq = count($enquiries);
                
            }
        }elseif($request->from && $request->to && !$request->initiator && $request->category && !$request->ward){
            // from, to and category
            $from = $request->from;
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::orderBy('created_at','DESC')
                ->where('main_category','=',$request->category)
                ->where('created_at','LIKE',$from."%")
                ->where('status','!=',"Enquiry Cancelled")
                       
               
                ->get();
                $converter = user::get();
            $totalenq = count($enquiries);
                
            }else{
                $enquiries = Requirement::orderBy('created_at','DESC')
                ->where('main_category','=',$request->category)
                ->wheredate('created_at','>=',$from)
                ->wheredate('created_at','<=',$to)
                ->where('status','!=',"Enquiry Cancelled")
                      

                ->get();
                $converter = user::get();
            $totalenq = count($enquiries);
               
            }
        }elseif($request->from && $request->to && $request->initiator && $request->category && !$request->ward){
            // from, to, initiator and category
            $from = $request->from;
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::orderBy('created_at','DESC')
                ->where('main_category','=',$request->category)
                ->where('generated_by','=',$request->initiator)
                ->where('created_at','LIKE',$from."%")
                ->where('status','!=',"Enquiry Cancelled")
                      
                ->get();
                $converter = user::get();
            $totalenq = count($enquiries);
                
            }else{
                $enquiries = Requirement::orderBy('created_at','DESC')
                ->where('main_category','=',$request->category)
                ->where('generated_by','=',$request->initiator)
                ->wheredate('created_at','>=',$from)
                ->wheredate('created_at','<=',$to)
                ->where('status','!=',"Enquiry Cancelled")
                      

                ->get();
                $converter = user::get();
            $totalenq = count($enquiries);

                
            }
        }elseif($request->from && $request->to && !$request->initiator && $request->category && $request->ward && $request->enqwrad){
            // from, to, wards and category
            $from = $request->from;
          if($request->ward == "All"){
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');    
            }else{
            $subwardid = Subward::where('id',$request->ward)->pluck('id');    
            }
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                            ->orderBy('requirements.created_at','DESC')
                            ->where('requirements.main_category','=',$request->category)
                            ->whereIn('project_details.sub_ward_id',$subwardid)
                            ->where('requirements.created_at','LIKE',$from."%")
                            ->where('requirements.status','!=',"Enquiry Cancelled")
                      

                            ->get();
            $totalenq = count($enquiries);

                        $converter = user::get();
            }else{
                $enquiries = Requirement::leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                            ->orderBy('requirements.created_at','DESC')
                            ->where('requirements.main_category','=',$request->category)
                            ->whereIn('project_details.sub_ward_id',$subwardid)
                            ->where('requirements.created_at','>=',$from)
                            ->where('requirements.created_at','<=',$to)
                            ->where('requirements.status','!=',"Enquiry Cancelled")
                       
                            
                            ->get();
                $converter = user::get();
            $totalenq = count($enquiries);
                }
            
        }elseif($request->from && $request->to && $request->initiator && !$request->category && $request->ward && $request->enqward){
            // from, to, wards and initiator
           if($request->ward == "All"){
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');    
            }else{
            $subwardid = Subward::where('id',$request->ward)->pluck('id');    
            }

            $from = $request->from;
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                            ->orderBy('requirements.created_at','DESC')
                            ->where('requirements.generated_by','=',$request->initiator)
                            ->whereIn('project_details.sub_ward_id',$subwardid)
                            ->where('requirements.created_at','LIKE',$from."%")
                            ->where('requirements.status','!=',"Enquiry Cancelled")
                            ->select('requirements.*','project_details.sub_ward_id')
                       
                            ->get();
            $totalenq = count($enquiries);
                
            }else{
                $enquiries = Requirement::leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                            ->orderBy('requirements.created_at','DESC')
                            ->where('requirements.generated_by','=',$request->initiator)
                            ->whereIn('project_details.sub_ward_id',$subwardid)
                            ->where('requirements.created_at','>=',$from)
                            ->where('requirements.created_at','<=',$to)
                            ->where('requirements.status','!=',"Enquiry Cancelled")
                      

                            ->get();
            $totalenq = count($enquiries);
                
            }
        }elseif(!$request->from && !$request->to && $request->initiator && $request->category && !$request->ward){
            //initiator and category
            $from = $request->from; 
            $to = $request->to;
            $enquiries = Requirement::where('main_category','=',$request->category)
                        ->where('generated_by','=',$request->initiator)
                        ->where('status','!=',"Enquiry Cancelled")
                       ->orderby('created_at','DESC')

                        ->get();
            $totalenq = count($enquiries);
            
        }elseif($request->manu){
                         if($request->manu != "manu"){
                            $enquiries = Requirement::where('manu_id','!=',NULL)
                                        ->where('status','like','%'.$request->manu)
                                        ->orderby('created_at','DESC')
                                        ->select('requirements.*')
                                        ->get();
                           $converter = user::get();
                        $totalenq = count($enquiries);
                            }
                        else{
                           
                             $enquiries = Requirement::where('manu_id','!=',NULL)
                                           ->where('status','!=',"Enquiry Cancelled")
                                           ->orderby('created_at','DESC')
                                           ->get();
                                $converter = user::get();
                                $totalenq = count($enquiries);

                            }
                        }
          elseif($request->enqward && !$request->category  && !$request->from && !$request->to && !$request->initiator && !$request->ward){
           // only ward
          $wardtotal = Subward::where('ward_id',$request->enqward)->pluck('id');
          $pro = ProjectDetails::whereIn('sub_ward_id',$wardtotal )->pluck('project_id');

         $enquiries = Requirement::whereIn('project_id',$pro)
                        ->where('status','!=',"Enquiry Cancelled")
                       ->orderby('created_at','DESC')
                        ->get();
                       
            $converter = user::get();
            $totalenq = count($enquiries);
        }
        elseif($request->category && $request->enqward && !$request->from && !$request->to && !$request->initiator && !$request->ward){
          
            // ward and category
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');
            $enquiries = Requirement::leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                        ->whereIn('project_details.sub_ward_id',$subwardid)
                        ->where('requirements.status','!=',"Enquiry Cancelled")
                        ->where('main_category',$request->category)
                        ->where('status','!=',"Enquiry Cancelled")
                        ->orderby('requirements.created_at','DESC')
                        ->get();
                        
            $converter = user::get();
            $totalenq = count($enquiries);

        }
        else{
            // no selection
            // $enquiries = Cache::remember('')
              if(!$request->yup){
              $yup = 100;
                
              }else{
                $yup =$request->yup;
              }

            $enquiries = Requirement::where('status','!=',"Enquiry Cancelled")
                       ->orderby('created_at','DESC')
                       ->where('manu_id',NULL) 
                       ->limit($yup)->get();
                       
            $enquiries1 = Requirement::where('status','!=',"Enquiry Cancelled")
                       ->orderby('created_at','DESC')
                       ->get();
            $converter = user::get();
            $totalenq = count($enquiries1);


         
            }
        $projectOrdersReceived = Order::whereIn('status',["Order Confirmed","Order Cancelled"])->pluck('project_id')->toArray();
        return view('enquirysheet',[
            'totalenq' =>$totalenq,
            'totalofenquiry'=>$totalofenquiry,
            'enquiries'=>$enquiries,
            'wardwise'=>$wardwise,
            'category'=>$category,
            'initiators'=>$initiators,
            'wards'=>$wards,
            'projectOrdersReceived'=>$projectOrdersReceived,
            'mainward'=>$ward,
            
        ]);
    }



 public function enquirysheet(Request $request)
    {
     if(Auth::user()->group_id != 22 ){
       return  $this->enquirysheet1($request);
     }   
       $s = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
       $etl = explode(",",$s);
       $wardwise =Ward::whereIn('id',$etl)->get();

        $wardss = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
         $tlward = explode(",",$wardss);
         $totalofenquiry = "";
        $totalenq = "";
        $converter = user::get();
        $ward = Ward::get();
        $wards = SubWard::orderby('sub_ward_name','ASC')->whereIn('ward_id',$tlward)->get();
        $sub = SubWard::whereIn('ward_id',$tlward)->pluck('id');
        $pids = ProjectDetails::whereIn('sub_ward_id',$sub)->pluck('project_id');
        $this->variable=$pids;

        $category = Category::all();
        $depart = [1,6,2,7,8,11,15,16,17,22];
        $initiators = User::whereIn('group_id',$depart)->where('department_id','!=',10)->get();
        $subwards2 = array();


        if($request->status && !$request->category){
            if($request->status != "all"){
               $tlward = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
                $enquiries = Requirement::whereIn('project_id',$pids)
                            ->where('status','like','%'.$request->status)
                            ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')
                            
                            ->get();

                       $enquiries = Response::Json( $enquiries);

            $totalenq = count($enquiries);
                      


               
            }else{

                $enquiries = Requirement::whereIn('project_id',$pids)
                            ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')
                           
                            ->get();
                             $enquiries = Response::Json( $enquiries);
            $totalenq = count($enquiries);
               
            }
        }elseif($request->status && $request->category){

            if($request->status != "all"){

                $enquiries = Requirement::whereIn('project_id',$pids)
                            ->where('status','like','%'.$request->status)
                            ->where('main_category',$request->category)
                            ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')

                            ->get();
            $totalenq = count($enquiries);
                
            }else{

                $enquiries = Requirement::whereIn('project_id',$pids)
                            ->where('main_category',$request->category)
                            ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')
                           
                            ->get();
            $totalenq = count($enquiries);
               
            }
        }elseif($request->from && $request->to  && !$request->initiator && !$request->category && !$request->ward){
            // only from and to
            $from = $request->from;
            $to = $request->to;


            if($from == $to){
                $enquiries = Requirement::whereIn('project_id',$pids)
                            ->orderBy('created_at','DESC')
                            ->where('created_at','LIKE',$from."%")
                            ->where('status','!=',"Enquiry Cancelled")
                 
                            ->get();
           
            $totalenq = count($enquiries);

            }
            else{
                $enquiries = Requirement::whereIn('project_id',$pids)
                            ->orderBy('created_at','DESC')
                            ->wheredate('created_at','>=',$from)
                            ->wheredate('created_at','<=',$to)
                            ->where('status','!=',"Enquiry Cancelled")
                 
                            ->get();
            $totalenq = count($enquiries);

       
            }
            

        }elseif(!$request->from && !$request->to && !$request->initiator && !$request->category && $request->ward && $request->enqward){
            // only ward
           if($request->ward == "All"){
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');    
            }else{
            $subwardid = Subward::where('id',$request->ward)->pluck('id');    
            }


            $enquiries = Requirement::whereIn('requirements.project_id',$pids)
                        ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                        ->whereIn('project_details.sub_ward_id',$subwardid)
                        ->where('requirements.status','!=',"Enquiry Cancelled")
                 ->orderby('project_details.created_at','DESC')

                        ->get();
            $totalenq = count($enquiries);
           
        }elseif(!$request->from && !$request->to && !$request->initiator && $request->category && !$request->ward){
            // only category
            $enquiries = Requirement::whereIn('project_id',$pids)
                        ->where('main_category',$request->category)
                        ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')
                        
                        ->get();


            $totalenq = count($enquiries);
          

            $totalofenquiry = Requirement::where('main_category',$request->category)->where('requirements.status','!=',"Enquiry Cancelled")->sum('quantity');


            
        }elseif(!$request->from && !$request->to && $request->initiator && !$request->category && !$request->ward){
            // only initiator
            $enquiries = Requirement::whereIn('project_id',$pids)
                        ->where('generated_by',$request->initiator)
                        ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')

                        ->get();
            $totalenq = count($enquiries);
           
        }elseif($request->from && $request->to && $request->initiator && $request->category && $request->ward && $request->enqward){
            // everything
            $from = $request->from;
            $to = $request->to;
            if($request->ward == "All"){
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');    
            }else{
            $subwardid = Subward::where('id',$request->ward)->pluck('id');    
            }
            if($from == $to){
                $enquiries = Requirement::whereIn('requirements.project_id',$pids)
                ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                 ->orderBy('requirements.created_at','DESC')
                ->whereIn('project_details.sub_ward_id',$subwardid)
                ->where('requirements.generated_by',$request->initiator)
                ->where('requirements.created_at','LIKE',$from."%")
                ->where('requirements.status','!=',"Enquiry Cancelled")
                ->where('requirements.main_category','LIKE',"%".$request->category."%")
                ->get();
            $totalenq = count($enquiries);
          
            }else{
                $enquiries = Requirement::whereIn('requirements.project_id',$pids)
                            ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                            ->orderBy('requirements.created_at','DESC')
                            ->whereIn('project_details.sub_ward_id',$subwardid)
                            ->where('requirements.generated_by',$request->initiator)
                            ->where('requirements.created_at','>=',$from)
                            ->where('requirements.created_at','<=',$to)
                            ->where('requirements.status','!=',"Enquiry Cancelled")
                            ->where('requirements.main_category','LIKE',"%".$request->category."%")
                 

                            ->get();
            $totalenq = count($enquiries);

            }
        }elseif($request->from && $request->to && !$request->initiator && !$request->category && $request->ward && $request->enqward){
            // from, to and ward
            if($request->ward == "All"){
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');    
            }else{
            $subwardid = Subward::where('id',$request->ward)->pluck('id');    
            }


            $from = $request->from;
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::whereIn('requirements.project_id',$pids)
                ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                ->orderBy('requirements.created_at','DESC')
                ->whereIn('project_details.sub_ward_id',$subwardid)
                ->where('requirements.created_at','LIKE',$from."%")
                ->where('requirements.status','!=',"Enquiry Cancelled")
                 
                ->get();
            $totalenq = count($enquiries);
                                  
            }else{
                $enquiries = Requirement::whereIn('requirements.project_id',$pids)
                ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                ->orderBy('requirements.created_at','DESC')
                ->whereIn('project_details.sub_ward_id',$subwardid)
                ->where('requirements.created_at','>=',$from)
                ->where('requirements.created_at','<=',$to)
                ->where('requirements.status','!=',"Enquiry Cancelled")
             
                
                ->get();
            $totalenq = count($enquiries);
               
            }
        }elseif($request->from && $request->to && $request->initiator && !$request->category && !$request->ward){
            // from, to and initiator
            $from = $request->from;
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::whereIn('project_id',$pids)
                ->orderBy('created_at','DESC')
                ->where('generated_by','=',$request->initiator)
                ->where('created_at','LIKE',$from."%")
                ->where('status','!=',"Enquiry Cancelled")
            
               
                ->get();
            $totalenq = count($enquiries);
               
            }else{
                $enquiries = Requirement::whereIn('project_id',$pids)
                ->orderBy('created_at','DESC')
                ->where('generated_by','=',$request->initiator)
                ->wheredate('created_at','>=',$from)
                ->wheredate('created_at','<=',$to)
                ->where('status','!=',"Enquiry Cancelled")
                
               
                ->get();
            $totalenq = count($enquiries);
              
            }
        }elseif($request->from && $request->to && !$request->initiator && $request->category && !$request->ward){
            // from, to and category
            $from = $request->from;
            $to = $request->to;
            if($from == $to){

                $enquiries = Requirement::whereIn('project_id',$pids)
                ->orderBy('created_at','DESC')
                ->where('main_category','=',$request->category)
                ->where('created_at','LIKE',$from."%")
                ->where('status','!=',"Enquiry Cancelled")
            

                ->get();
            $totalenq = count($enquiries);
               
            }else{
                $enquiries = Requirement::whereIn('project_id',$pids)
                ->orderBy('created_at','DESC')
                ->where('main_category','=',$request->category)
                ->wheredate('created_at','>=',$from)
                ->wheredate('created_at','<=',$to)
                ->where('status','!=',"Enquiry Cancelled")
                
              
                ->get();
            $totalenq = count($enquiries);
                
            }
        }elseif($request->from && $request->to && $request->initiator && $request->category && !$request->ward){
            // from, to, initiator and category
            $from = $request->from;
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::whereIn('project_id',$pids)
                ->orderBy('created_at','DESC')
                ->where('main_category','=',$request->category)
                ->where('generated_by','=',$request->initiator)
                ->where('created_at','LIKE',$from."%")
                ->where('status','!=',"Enquiry Cancelled")
                

                ->get();
            $totalenq = count($enquiries);
                
            }else{
                $enquiries = Requirement::whereIn('project_id',$pids)
                ->orderBy('created_at','DESC')
                ->where('main_category','=',$request->category)
                ->where('generated_by','=',$request->initiator)
                ->wheredate('created_at','>=',$from)
                ->wheredate('created_at','<=',$to)
                ->where('status','!=',"Enquiry Cancelled")
                
                
                ->get();
            $totalenq = count($enquiries);
                
            }
        }elseif($request->from && $request->to && !$request->initiator && $request->category && $request->ward && $request->enqward){
            // from, to, wards and category
            if($request->ward == "All"){
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');    
            }else{
            $subwardid = Subward::where('id',$request->ward)->pluck('id');    
            }


            $from = $request->from;
            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::whereIn('requirements.project_id',$pids)
                            ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                             ->orderBy('requirements.created_at','DESC')
                            ->where('requirements.main_category','=',$request->category)
                            ->whereIn('project_details.sub_ward_id',$subwardid)
                            ->where('requirements.created_at','LIKE',$from."%")
                            ->where('requirements.status','!=',"Enquiry Cancelled")
                 

                            ->get();
            $totalenq = count($enquiries);
                       
            }else{
                $enquiries = Requirement::whereIn('requirements.project_id',$pids)
                            ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                            ->orderBy('requirements.created_at','DESC')
                            ->where('requirements.main_category','=',$request->category)
                            ->whereIn('project_details.sub_ward_id',$subwardid)
                            ->where('requirements.created_at','>=',$from)
                            ->where('requirements.created_at','<=',$to)
                            ->where('requirements.status','!=',"Enquiry Cancelled")
                 

                             ->get();
            $totalenq = count($enquiries);
              
            }
        }elseif($request->from && $request->to && $request->initiator && !$request->category && $request->ward && $request->enqward){
            // from, to, wards and initiator
            $from = $request->from;
            if($request->ward == "All"){
            $subwardid = Subward::where('ward_id',$request->enqward)->pluck('id');    
            }else{
            $subwardid = Subward::where('id',$request->ward)->pluck('id');    
            }

            $to = $request->to;
            if($from == $to){
                $enquiries = Requirement::whereIn('requirements.project_id',$pids)
                            ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                            ->orderBy('requirements.created_at','DESC')
                            ->where('requirements.generated_by','=',$request->initiator)
                            ->whereIn('project_details.sub_ward_id',$subwardid)
                            ->where('requirements.created_at','LIKE',$from."%")
                            ->where('requirements.status','!=',"Enquiry Cancelled")
                
                            
                            ->get();
            $totalenq = count($enquiries);
                
            }else{
                $enquiries = Requirement::whereIn('requirements.project_id',$pids)
                            ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                            ->orderBy('requirements.created_at','DESC')
                            ->where('requirements.generated_by','=',$request->initiator)
                            ->whereIn('project_details.sub_ward_id',$subwardid)
                            ->where('requirements.created_at','>=',$from)
                            ->where('requirements.created_at','<=',$to)
                            ->where('requirements.status','!=',"Enquiry Cancelled")
               

                            ->get();
            $totalenq = count($enquiries);
                
            }
        }elseif(!$request->from && !$request->to && $request->initiator && $request->category && !$request->ward){
            //initiator and category
            $from = $request->from;
            $to = $request->to;
            $enquiries = Requirement::whereIn('project_id',$pids)
            
                        ->where('main_category','=',$request->category)
                        ->where('generated_by','=',$request->initiator)
                        ->where('status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')


                        ->get();
            $totalenq = count($enquiries);
            
        }

        elseif($request->manu){
         $enquiries = Requirement::where('manu_id','!=',NULL)
                       ->where('requirements.status','!=',"Enquiry Cancelled")
                       ->orderby('created_at','DESC')
                        ->get();
            $converter = user::get();
            $totalenq = count($enquiries);

        }

        else{
            
            $enquiries = Requirement::whereIn('project_id',$pids)
                        ->where('requirements.status','!=',"Enquiry Cancelled")
                 ->orderby('created_at','DESC')
                        
                        ->get();
         

          
            $totalenq = count($enquiries);
            
        }

        $filtered = new Collection;
        $projectOrdersReceived = Order::whereIn('status',["Order Confirmed","Order Cancelled"])->pluck('project_id')->toArray();

            $wardf = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
            $tlwardf = explode(",",$wardf);
            $wardsss = SubWard::orderby('sub_ward_name','ASC')->whereIn('ward_id',$tlwardf)->pluck('id');

           $pp = ProjectDetails::where('sub_ward_id',$wardsss)->pluck('project_id');

            $r = Requirement::whereIn('project_id',$pp)->pluck('id');


    $totalenq = count($enquiries);

        return view('enquirysheet',[
            'totalenq' =>$totalenq,
            'totalofenquiry'=>$totalofenquiry,
            'subwards2'=>$subwards2,
            'enquiries'=>$enquiries,
            'wards'=>$wards,
            'category'=>$category,
            'initiators'=>$initiators,
            'mainward'=>$ward,
            'projectOrdersReceived'=>$projectOrdersReceived,'totalenq'=>$totalenq,'wardwise'=>$wardwise
        ]);
    }

 public function enquiryCancell(Request $request)
    {
        if(Auth::user()->group_id == 22)
       {
        return $this->enquiryCancell1($request);
       }
       
        $cancelcount = 0;
        $wards = SubWard::orderby('sub_ward_name','ASC')->get();
        $category = Category::all();
        $depart = [6,7,2];
        $initiators = User::whereIn('group_id',$depart)->where('department_id','!=',10)->get();
        $subwards2 = array();
         if($request->project == "project"){

        $enquiries = Requirement::where('status',"Enquiry Cancelled")
                        ->where('manu_id',NULL)->paginate("20");
         }
       else{

        $enquiries = Requirement::where('status',"Enquiry Cancelled")
                        ->where('manu_id','!=',NULL)->paginate("20");
       }
                     
             
        $cancelcount = count( $enquiries);
            // foreach($enquiries as $enquiry){
            //     $subwards2[$enquiry->project != null ?$enquiry->project->project_id : ''] = SubWard::where('id',$enquiry->sub_ward_id)->pluck('sub_ward_name')->first();
            // }
            //  $tlward = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
            // $wardss = SubWard::orderby('sub_ward_name','ASC')->where('ward_id',$tlward)->pluck('id')->first;
            // $pp = ProjectDetails::where('sub_ward_id',$wardss)->pluck('project_id');

       
       
        return view('enquiryCancell',[
            'cancelcount' =>$cancelcount,
            'subwards2'=>$subwards2,
            'enquiries'=>$enquiries,
            'wards'=>$wards,
            'category'=>$category,
            'initiators'=>$initiators
        ]);
        
       
    }


public function enquiryCancells(Request $request){

     
        $r=$request->eid;
   
     $u=Requirement::where('id',$request->eid)->update(['status'=>"Enquiry Cancelled",'remark'=>$request->remark]);

     return back()->with("Successfully Cancelled The Enquiry ,  Thank You !");


}




 public function enquiryCancell1(Request $request)
    {
        $tl= Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
        $tlward =explode(",",$tl);
        $cancelcount = 0;
        $wards = SubWard::orderby('sub_ward_name','ASC')->get();
        $category = Category::all();
        $depart = [6,7,2];
        $initiators = User::whereIn('group_id',$depart)->where('department_id','!=',10)->get();
        $subwards2 = array();
        $enquiries = Requirement::leftjoin('users','users.id','=','requirements.generated_by')
                    ->leftjoin('procurement_details','procurement_details.project_id','=','requirements.project_id')
                    ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                    ->leftjoin('sub_wards','sub_wards.id','project_details.sub_ward_id')
                    ->leftjoin('wards','wards.id','sub_wards.ward_id')
                    ->whereIn('wards.id',$tlward)
                    ->select('requirements.*','procurement_details.procurement_name','procurement_details.procurement_contact_no','procurement_details.procurement_email','users.name','project_details.sub_ward_id')
                    ->where('requirements.status',"Enquiry Cancelled")
                    ->get();

        $cancelcount = count( $enquiries);
            foreach($enquiries as $enquiry){
                $subwards2[$enquiry->project_id] = SubWard::where('id',$enquiry->sub_ward_id)->pluck('sub_ward_name')->first();
            }

        return view('enquiryCancell',[
            'cancelcount' =>$cancelcount,
            'subwards2'=>$subwards2,
            'enquiries'=>$enquiries,
            'wards'=>$wards,
            'category'=>$category,
            'initiators'=>$initiators
        ]);
    }
    public function myenquirysheet()
    {
        $wards = SubWard::orderby('sub_ward_name','ASC')->get();
        $category = Category::all();
        $depart = [6,7,2];
        $initiators = User::whereIn('group_id',$depart)->where('department_id','!=',10)->get();
        $subwards2 = array();
        $enquiries = Requirement::leftjoin('users','users.id','=','requirements.generated_by')
                    ->leftjoin('procurement_details','procurement_details.project_id','=','requirements.project_id')
                    ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                    ->select('requirements.*','procurement_details.procurement_name','procurement_details.procurement_contact_no','procurement_details.procurement_email','users.name','project_details.sub_ward_id')
                    ->where('requirements.generated_by',Auth::user()->id)
                    ->get();
        foreach($enquiries as $enquiry){
            $subwards2[$enquiry->project_id] = SubWard::where('id',$enquiry->sub_ward_id)->pluck('sub_ward_name')->first();
        }

        return view('enquirysheet',[
            'subwards2'=>$subwards2,
            'enquiries'=>$enquiries,
            'wards'=>$wards,
            'category'=>$category,
            'initiators'=>$initiators
        ]);
    }
    public function editEnq(Request $request)
    {

        $category = Category::all();
        $depart = [2,4,6,7,8,17];
        $users = User::whereIn('group_id',$depart)->where('department_id','!=',10)->get();
        $enq = Requirement::where('requirements.id',$request->reqId)
                    ->leftjoin('users','users.id','=','requirements.generated_by')
                    ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                    ->leftjoin('procurement_details','requirements.project_id','=','procurement_details.procurement_contact_no')
                    ->leftjoin('contractor_details','requirements.project_id','contractor_details.project_id')
                    ->leftjoin('owner_details','requirements.project_id','owner_details.project_id')
                    ->leftjoin('site_engineer_details','requirements.project_id','site_engineer_details.project_id')
                    ->leftjoin('consultant_details','requirements.project_id','consultant_details.project_id')
                    ->leftjoin('site_addresses','requirements.project_id','=','site_addresses.project_id')
                    ->select('requirements.*','users.name','project_details.project_name','procurement_details.procurement_contact_no','site_addresses.address','contractor_details.contractor_contact_no','owner_details.owner_contact_no','site_engineer_details.site_engineer_contact_no','consultant_details.consultant_contact_no','requirements.id')
                    ->first();

        return view('editEnq',['enq'=>$enq,'category'=>$category,'users'=>$users]);
    }
    public function editEnq1(Request $request)
    {

        $states = State::all();
        $category = Category::where('category_name','!=',"STEEL")->get();
        $steel = Category::where('category_name','STEEL')->get();
        $depart = [7,1];
       $users = User::whereIn('group_id',$depart)->where('department_id','!=',10)->where('name',Auth::user()->name)->get();
        $depart1 = [6,7];
       $users1 = User::whereIn('group_id',$depart1)->where('department_id','!=',10)->where('name',Auth::user()->name)->get();
        $depart2 = [2,4,6,7,8,17,11,10,1];
        $users2 = User::whereIn('group_id',$depart2)->get();

        $enq = Requirement::where('requirements.id',$request->reqId)
                    ->leftjoin('users','users.id','=','requirements.generated_by')
                    ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                    ->leftjoin('procurement_details','requirements.project_id','=','procurement_details.project_id')
                    ->leftjoin('contractor_details','requirements.project_id','contractor_details.project_id')
                    ->leftjoin('owner_details','requirements.project_id','owner_details.project_id')
                    ->leftjoin('site_engineer_details','requirements.project_id','site_engineer_details.project_id')
                    ->leftjoin('consultant_details','requirements.project_id','consultant_details.project_id')
                    ->leftjoin('site_addresses','requirements.project_id','=','site_addresses.project_id')
                    ->select('requirements.*','users.name','project_details.project_name','procurement_details.procurement_contact_no','site_addresses.address','contractor_details.contractor_contact_no','owner_details.owner_contact_no','site_engineer_details.site_engineer_contact_no','consultant_details.consultant_contact_no')
                    ->first();
                  
        return view('editEnq1',['enq'=>$enq,'category'=>$category,'users'=>$users,'users1'=>$users1,'users2'=>$users2,'states'=>$states,'steel'=>$steel]);
    }
    public function eqpipelineedit(Request $request)
    {
        $category = Category::all();
        $depart = [2,4,6,7,8,17];
        $users = User::whereIn('group_id',$depart)->where('department_id','!=',10)->get();
        $enq = Requirement::where('requirements.id',$request->reqId)
                    ->leftjoin('users','users.id','=','requirements.generated_by')
                    ->leftjoin('project_details','project_details.project_id','=','requirements.project_id')
                    ->leftjoin('procurement_details','requirements.project_id','=','procurement_details.procurement_contact_no')
                    ->leftjoin('contractor_details','requirements.project_id','contractor_details.project_id')
                    ->leftjoin('owner_details','requirements.project_id','owner_details.project_id')
                    ->leftjoin('site_engineer_details','requirements.project_id','site_engineer_details.project_id')
                    ->leftjoin('consultant_details','requirements.project_id','consultant_details.project_id')
                    ->leftjoin('site_addresses','requirements.project_id','=','site_addresses.project_id')
                    ->select('requirements.*','users.name','project_details.project_name','procurement_details.procurement_contact_no','site_addresses.address','contractor_details.contractor_contact_no','owner_details.owner_contact_no','site_engineer_details.site_engineer_contact_no','consultant_details.consultant_contact_no')
                    ->first();
        return view('editEnq',['enq'=>$enq,'category'=>$category,'users'=>$users]);
    }



    // public function index1(Request $request )
    // {



    //     $check =DB::table('stages')->where('list',Auth::user()->name)
    //                 ->orderby('created_at','DESC')->pluck('status');

    //     $count = count($check);
    //     $ss = ProjectDetails::pluck('project_status');

    //      $cc = explode("," , $ss);

    //     $projects = ProjectDetails::where($cc ,'LIKE' ,$check)->get();

    //      dd($projects);

    //     $totalListing = ProjectDetails::where('project_status',$check)->count();

    //     return view('status_wise_projects', ['projects' => $projects, 'totalListing'=>$totalListing,'status'=>$check ,'stages'=>$stages]);
    //    }

       public function datewise(Request $request )
       {
            $assigndate =AssignStage::where('user_id',Auth::user()->id)
                           ->orderby('created_at','DESC')->pluck('assigndate')->first();
            $check =DB::table('assignstage','user_id',Auth::user()->id)->pluck('stage');
            $projects =ProjectDetails::where('project_details.created_at','like',$assigndate."%")
                ->whereOr('project_details.project_status','asssign_satge')
                ->leftjoin('users','users.id','=','project_details.listing_engineer_id')
                 ->whereOr('stage' , $check)
               ->leftjoin('assignstage', 'project_details.sub_ward_id',  'assignstage.sub_id')
               ->select('project_details.*')
               ->paginate(15);

            $totalListing = ProjectDetails::where('created_at','LIKE',$assigndate."%")->count();

           return view('date_wise_project',['projects' => $projects,'assigndate'=>$assigndate,'totalListing'=>$totalListing ]);
          }

    public function index(request $request)
    {


        if(Auth::user()->confirmation == 0){
            return view('companypolicy');
        }
        $group = Group::where('id',Auth::user()->group_id)->pluck('group_name')->first();

        $dept = Department::where('id',Auth::user()->department_id)->pluck('dept_name')->first();
        $users = User::where('department_id','!=',0)->paginate(10);
        $departments = Department::all();
        if($group == "Team Lead" && $dept == "Operation"){

            return redirect('teamLead');
        }else if($group == "Team Leader" && $dept == "Operation"){
            return redirect('teamLead');
        }
        else if($group == "Listing Engineer" && $dept == "Operation"){
            return redirect('leDashboard');
        }else if($group == "Team Lead" && $dept == "Sales"){
            return redirect('salesTL');
        }else if($group == "Sales Engineer" && $dept == "Sales"){
            return redirect('salesEngineer');
        }else if($dept == "Human Resource"){
            return redirect('amdashboard');
        }else if($group == "Logistic Co-ordinator (Sales)"){
            return redirect('lcodashboard');
        }else if($group == "Account Executive"){
            return redirect('accountExecutive');
        }else if($group == "Asst.Manager" && $dept == "Operation"){
            return redirect('teamleads');
        }else if($group == "Asst. Manager" && $dept == "Sales"){
            return redirect('teamleads');
        }


        else if($group == "Admin"){
        $groups = Group::where('group_name','!=','Admin')->get();
        $loggedInUsers = FieldLogin::where('logindate',date('Y-m-d'))
                        ->join('users','field_login.user_id','users.id')
                        ->where('users.department_id','!=',0)
                        ->select('users.name','field_login.*','users.group_id','users.employeeId')
                        ->get();
                       
        $leLogins = loginTime::where('logindate',date('Y-m-d'))
                        ->join('users','login_times.user_id','users.id')
                        ->select('users.name','users.employeeId','login_times.*','users.group_id')
                        ->get();
        $log =  FieldLogin::where('logindate',date('Y-m-d'))
                    ->join('users','field_login.user_id','users.id')
                    ->where('users.department_id','!=',0)->pluck('field_login.user_id');
        $dept =[1,2,3,4,5,6,7];
        $ntlogins = user::whereIn('department_id',$dept)->whereNotIn('id',$log)->
                select('users.name','users.employeeId')->get();
        $present = count($log);
        $absent = count($ntlogins);      
         $projects = ProjectDetails::where('fixdate','LIKE',date('Y-m-d'))->count();
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

                 $oders = MamahomePrice::where('description',$catname)->wheredate('created_at','>=',$sale->start)->wheredate('created_at','<=',$sale->end)->pluck('order_id');
           
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
            
            $bals = $total - $tp;
            $finaltp = ($sale->totalatpmount) - ($bals);
            
              $catamount = ($sale->quantity * $sale->price);
            $yet = $catamount - $invoice;
         array_push($cates,['category'=>$catname,'invoice'=>$yet,'categorytarget'=>$catamount]);

          }                  
         return view('/home',['departments'=>$departments,'users'=>$users,'groups'=>$groups,'loggedInUsers'=>$loggedInUsers,'leLogins'=>$leLogins,'present'=>$present,'absent'=>$absent,'ntlogins'=>$ntlogins,'projects'=>$projects,'cates'=>$cates]);  
        }else if($group == "Sales Converter" && $dept == "Sales"){
            return redirect('scdashboard');
        }else if($group == "Marketing Exective" && $dept == "Marketing"){
            return redirect('marketingdashboard');
        }else if(Auth::user()->department_id == 10){
            Auth()->logout();
            return view('errors.403error');
        }else if($group == "Auditor"){
            return redirect('auditor');
        }else if($dept == 'IT'){
            return redirect('itdashboard');
        }else if($dept == 'Research and Development'){
            return redirect('RandDdashboard');
        }else if($group == 'Sales Officer'){
             return redirect('salesofficer');
        }else if($group == 'Finance'){
            return redirect('financeIndex');
        }else{
            return redirect('chat');
        }
          
        


       
       
        return view('/home',['departments'=>$departments,'users'=>$users,'groups'=>$groups]);
    }
    public function amDept()
    {
        $users = User::where('department_id','!=',0)->paginate(10);
        $departments = Department::all();
        $groups = Group::where('group_name','!=','Admin')->get();
        return view('depdesign',['departments'=>$departments,'users'=>$users,'groups'=>$groups]);
    }
   public function quality()
    {
    
        $closed = ProjectDetails::where('project_status','LIKE',"%Closed%")->pluck('project_id');
        $genuine = ProjectDetails::where('quality',"GENUINE")->whereNotIn('project_id',$closed)->count();
        
        
        $fake = ProjectDetails::where('quality',"FAKE")->count();
        
        $notConfirmed = ProjectDetails::where('quality',"Unverified")->whereNotIn('project_id',$closed)->where('quality','!=',"FAKE")->count();
        
        $le = User::where('group_id','6')->get();
        $notes = ProjectDetails::groupBy('with_cont')
                    ->where('with_cont','!=',"DUPLICATE NUMBER")
                    ->where('with_cont','!=',"FINISHING")
                    ->where('with_cont','!=',"NOT INTERESTED")
                    ->where('with_cont','!=',"PROJECT CLOSED")
                    ->where('with_cont','!=',"THEY HAVE REGULAR SUPPLIERS")
                    ->where('with_cont','!=',"WRONG NO")
                    ->pluck('with_cont');
        $count = array();
        foreach($notes as $note){
            $count[$note] = ProjectDetails::where('with_cont',$note)->count();
        }

        $projects = ProjectDetails::join('users','users.id','=','project_details.listing_engineer_id')->orderBy('project_details.created_at','DESC')->get();
        return view('Qualityproj', ['notes'=>$notes,'count'=>$count,'le' => $le, 'projects' => $projects,'genuine'=>$genuine,'fake'=>$fake,'notConfirmed'=>$notConfirmed,'closed'=>$closed]);
    }
    public function getquality(Request $request)
    {
        $id = $request->id;
        $quality = $request->quality;
        $date1 = $request->date1;
        $date2 = $request->date2;
        $records = array();
        if($date1 == $date2)
        {
            $date1 .= " 00:00:00";
            $date2 .= " 23:59:59";
            if($quality == 'ALL')
            {
                if($id == 'ALL'){
                    $records[0] = ProjectDetails::join('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                    ->join('users','users.id','=','project_details.listing_engineer_id')
                    ->where('project_details.created_at','>',$date1)
                    ->where('project_details.created_at','<',$date2)
                    ->select('project_details.project_id','sub_wards.sub_ward_name','project_details.created_at','project_details.contract')
                    ->get();
                    $records[2] = count($records[0]);
                }else{
                    $records[0] = ProjectDetails::join('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                    ->join('users','users.id','=','project_details.listing_engineer_id')
                    ->where('users.id',$id)
                    ->where('project_details.created_at','>',$date1)
                    ->where('project_details.created_at','<',$date2)
                    ->select('project_details.project_id','sub_wards.sub_ward_name','project_details.created_at','project_details.contract')
                    ->get();
                    $records[2] = count($records[0]);
                }
            }
            else
            {
                if($id == 'ALL'){
                    $records[0] = ProjectDetails::join('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                    ->join('users','users.id','=','project_details.listing_engineer_id')
                    ->where('project_details.created_at','>',$date1)
                    ->where('project_details.created_at','<',$date2)
                    ->where('quality',$quality)
                    ->select('project_details.project_id','sub_wards.sub_ward_name','project_details.created_at','project_details.contract')
                    ->get();
                    $records[2] = count($records[0]);
                }else{
                    $records[0] = ProjectDetails::join('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                    ->join('users','users.id','=','project_details.listing_engineer_id')
                    ->where('users.id',$id)
                    ->where('project_details.created_at','>',$date1)
                    ->where('project_details.created_at','<',$date2)
                    ->where('quality',$quality)
                    ->select('project_details.project_id','sub_wards.sub_ward_name','project_details.created_at','project_details.contract')
                    ->get();
                    $records[2] = count($records[0]);
                }
            }

        }
        else
        {
            $date1 .= " 00:00:00";
            $date2 .= " 23:59:59";
            if($quality == 'ALL')
            {
                if($id == 'ALL'){
                    $records[0] = ProjectDetails::join('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                    ->join('users','users.id','=','project_details.listing_engineer_id')
                    // ->where('users.id',$id)
                    ->where('project_details.created_at','>',$date1)
                    ->where('project_details.created_at','<',$date2)
                    ->select('project_details.project_id','sub_wards.sub_ward_name','project_details.created_at','project_details.contract')
                    ->get();
                    $records[2] = count($records[0]);
                }else{
                    $records[0] = ProjectDetails::join('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                    ->join('users','users.id','=','project_details.listing_engineer_id')
                    ->where('users.id',$id)
                    ->where('project_details.created_at','>',$date1)
                    ->where('project_details.created_at','<',$date2)
                    ->select('project_details.project_id','sub_wards.sub_ward_name','project_details.created_at','project_details.contract')
                    ->get();
                    $records[2] = count($records[0]);
                }
            }
            else
            {
                if($id == 'ALL'){
                    $records[0] = ProjectDetails::join('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                    ->join('users','users.id','=','project_details.listing_engineer_id')
                    // ->where('users.id',$id)
                    ->where('project_details.created_at','>',$date1)
                    ->where('project_details.created_at','<',$date2)->where('quality',$quality)
                    ->select('project_details.project_id','sub_wards.sub_ward_name','project_details.created_at','project_details.contract')
                    ->get();
                    $records[2] = count($records[0]);
                }else{
                    $records[0] = ProjectDetails::join('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                    ->join('users','users.id','=','project_details.listing_engineer_id')
                    ->where('users.id',$id)->where('project_details.created_at','>',$date1)
                    ->where('project_details.created_at','<',$date2)->where('quality',$quality)
                    ->select('project_details.project_id','sub_wards.sub_ward_name','project_details.created_at','project_details.contract')
                    ->get();
                    $records[2] = count($records[0]);
                }
            }

        }
        $records[1] = $id.' '.$quality.' '.$date1.' '.$date2;
        $records[4] = date('d-m-Y',strtotime($date2));
        $records[3] = date('d-m-Y',strtotime($date1));
        return response()->json($records);
    }
    public function viewEmployee(Request $id)
    {
        $user = User::where('employeeId',$id->UserId)->first();
        $details = EmployeeDetails::Where('employee_id',$id->UserId)->first();
        $bankdetails = BankDetails::where('employeeId',$id->UserId)->first();
        $assets = AssetInfo::where('employeeId',$id->UserId)->get();
        $certificates = Certificate::where('employeeId',$id->UserId)->get();
        return view('viewEmployee',['user'=>$user,'details'=>$details,'bankdetails'=>$bankdetails,'assets'=>$assets,'certificates'=>$certificates]);
    }
    public function teamLeadHome(request $request){

           $this->getid();
           $data=$this->variable;

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
           
          $invoice = MamahomePrice::where('category',$catname)->where('created_at','>=',$sale->start)->where('created_at','<=',$sale->end)->sum('amountwithgst');
          
           $gst = Gst::where('Category',$catname)->first();
                if($gst->cgst != NULL){

                  $gstval = ($gst->cgst + $gst->sgst);
                }else{
                  $gstval = ($gst->igst);
                }

                 $oders = MamahomePrice::where('category',$catname)->where('created_at','>=',$sale->start)->where('created_at','<=',$sale->end)->pluck('order_id');
           
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


           
          $invoice = MamahomePrice::where('category',$catname)->wheredate('created_at','>=',$sale->start)->wheredate('created_at','<=',$sale->end)->sum('amountwithgst');
          
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

         $no = UpdatedReport::where('created_at','LIKE',$date.'%')->where('quntion',"notinterest")->pluck('manu_id');
          $nos = UpdatedReport::where('created_at','LIKE',$date.'%')->where('quntion',"notinterest")->pluck('project_id');
         $notinterest = Manufacturer::whereIn('id',$no)->get();
         $notinterests = ProjectDetails::whereIn('project_id',$nos)->get();  
          }
         return view('/teamLeader',['loggedInUsers'=>$loggedInUsers,'leLogins'=> $leLogins,'users'=>$users,'usersId'=>$usersId,'newwards'=>$newwards,'followup'=>$followup,'departments'=>$departments,'groups'=>$groups,'today'=>$today,'total'=>count($totalcount),'bal'=>$bal,'cates'=>$cates,'login'=>$login,'notinterest'=>$notinterest,'notinterests'=>$notinterests]);
   }
     public function assignListSlots(){
    // $group = Group::where('group_name','Listing Engineer')->pluck('id')->first();
    $group = [6,11,7,17,2,23,22,1];
        $users = User::whereIn('group_id',$group)
                        ->leftjoin('ward_assignments','ward_assignments.user_id','=','users.id')
                        ->leftjoin('sub_wards','sub_wards.id','=','ward_assignments.subward_id')
                        ->leftjoin('wards','wards.id','=','sub_wards.ward_id' )
                        ->leftjoin('employee_details','users.employeeId','=','employee_details.employee_id')
                        ->where('department_id','!=','10')
                        ->select('users.employeeId','users.id','users.name','ward_assignments.status','sub_wards.sub_ward_name','sub_wards.sub_ward_image','ward_assignments.prev_subward_id','employee_details.office_phone')
                        ->get();
                        
        $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
        $userIds = explode(",", $tl);
      $tlUsers= User::whereIn('users.id',$userIds)
                        ->whereIn('users.group_id',$group)
                        ->leftjoin('ward_assignments','ward_assignments.user_id','=','users.id')
                        ->leftjoin('sub_wards','sub_wards.id','=','ward_assignments.subward_id')
                        ->leftjoin('wards','wards.id','=','sub_wards.ward_id' )
                        ->leftjoin('employee_details','users.employeeId','=','employee_details.employee_id')
                        ->where('department_id','!=','10')
                        ->select('users.employeeId','users.id','users.name','ward_assignments.status','sub_wards.sub_ward_name','sub_wards.sub_ward_image','ward_assignments.prev_subward_id','employee_details.office_phone')
                        ->get();

       $totalcount = User::whereIn('group_id',$group)
                            ->where('department_id','!=','10')
                            ->count();

        $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
        $tlward = explode(",",$tl);


         if(Auth::user()->group_id != 22){
        $wards = Ward::orderby('ward_name','ASC')->get();
         }else{
             $wards = Ward::orderby('ward_name','ASC')->whereIn('id',$tlward)->get();
         }

        $zones = Zone::all();
        $subwardsAssignment = WardAssignment::all();
        $subwards = SubWard::orderby('sub_ward_name','ASC')->get();



        return view('assignListSlots',['users'=>$users,'subwards'=>$subwards,'subwardsAssignment'=>$subwardsAssignment,'wards'=>$wards,'zones'=>$zones,'totalcount'=>$totalcount,'tlUsers'=> $tlUsers]);
    }
     public function assignadmin(){
    $group = Group::where('group_name','Admin')->pluck('id')->first();
        $users = User::where('group_id',$group)
                        ->leftjoin('ward_assignments','ward_assignments.user_id','=','users.id')
                        ->leftjoin('sub_wards','sub_wards.id','=','ward_assignments.subward_id')
                        ->leftjoin('wards','wards.id','=','sub_wards.ward_id' )
                        ->leftjoin('employee_details','users.employeeId','=','employee_details.employee_id')
                        ->where('department_id','!=','10')
                        ->select('users.employeeId','users.id','users.name','ward_assignments.status','sub_wards.sub_ward_name','sub_wards.sub_ward_image','ward_assignments.prev_subward_id','employee_details.office_phone')
                        ->get();


        $wards = Ward::orderby('ward_name','ASC')->get();
        $zones = Zone::all();
        $subwardsAssignment = WardAssignment::all();
        $subwards = SubWard::orderby('sub_ward_name','ASC')->get();

        return view('assignadmin',['users'=>$users,'subwards'=>$subwards,'subwardsAssignment'=>$subwardsAssignment,'wards'=>$wards,'zones'=>$zones]);
    }
     public function tlmaps(request $request)
    {
         if(Auth::user()->group_id != 22){
            return $this->tlmaps1($request);
         }

        $tl= Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
        $tlward = explode(",",$tl); 
        $wards = Ward::orderby('ward_name','ASC')
        ->whereIn('id',  $tlward)->get();
        $zones = Zone::all();
        return view('tlMaps',['wards'=>$wards,'zones'=>$zones]);
    }
    public function tlmaps1(request $request)
    {
        $wards = Ward::orderby('ward_name','ASC')->get();
        $zones = Zone::all();
        return view('tlMaps',['wards'=>$wards,'zones'=>$zones]);
    }

    public function loadSubWards(Request $request)
    {
        $subwards = Subward::where('ward_id',$request->ward_id)
                            ->orderby('sub_ward_name','ASC')
                            ->select('id','sub_ward_name')
                            ->get();
        if(count($subwards) > 0)
        {
            return response()->json($subwards);
        }
        else
        {
            return response()->json('No Sub Wards Found !!!');
        }
    }
    public function masterData(Request $request)
    {
        $wards = Ward::orderBy('ward_name','ASC')->get();
        $countries = Country::all();
        $subwards = SubWard::orderby('sub_ward_name','ASC')->get();
        $states = State::all();
        $zones = Zone::all();

        $dist_states = states_dists::All();
        $customertypes= CustomerType::All();
        $customer_type= $request->id;
        $sub_customer_details= CustomerType::where('sub_customer_id','!=',null)->get();
        $customer_details = CustomerType::where('sub_customer_id',null)->get();
       
        return view('masterData',[
            'sub_customer_details'=>$sub_customer_details,             
            'customertypes'=>$customertypes, 
                        'dist_states'=>$dist_states, 
                        'wards'=>$wards,
                        'countries'=>$countries,
                        'subwards'=>$subwards,
                        'states'=>$states,
                        'zones'=>$zones,
                        'customer_type'=> $customer_type,
                        'customer_details'=> $customer_details
                        ]
                    )
                ;
     }

    public function addCustomertype(Request $request)
    {
        $customer_type= $request->cust_type;
        $customer = new CustomerType;
        $customer->cust_type=$customer_type;
        $customer->cust_type_id=md5(microtime(true).mt_Rand());
        $customer->save();
        return back();
    }

    
    public function addSubCustomers(Request $request)
    {
       $sub_name = $request->cust_name;
       $sub_cust_id=$request->cust_type_id; 
       $customer = new CustomerType;
       $customer -> cust_type =$request->cust_type ;
       $customer->cust_type_id=md5(microtime(true).mt_Rand());
       $customer->sub_customer_id = $sub_name;
       $customer->save();
       return back();
    }

    public function addDistrict(Request $request)
    {
        $id= $request->id;
        $zone_id= $request->zone;
        $ward_id=$request->ward;
        $parent_id=$request->dist_states;
        $dist_id=$request->dist;
        $store_dist= New assign_states_dist;
        $store_dist->zone_id=$zone_id;
        $store_dist->ward_id=$ward_id;
        $store_dist->parent_id=$parent_id;
        $store_dist->dist_id=$dist_id;
        $store_dist->save();
        return back();
    }

    public function loaddist_states(Request $request)
    {
     
        $dist= states_dists::where("parent_id",$request->dist_id)->get();
        return  response()->json($dist);   
    }

    public function load_wards(Request $request)
    {
        
        $ward = Ward::where("zone_id",$request->zone_id)->get();
        return  response()->json($ward);   
    }

    public function getDistwithstates(Request $request)
    {
        
        $dist_with_ward= assign_states_dist::where("ward_id",$request->ward_id)->pluck('dist_id');
        $dist_with_states = states_dists::whereIn("id",$dist_with_ward)->get();

       
        return  response()->json($dist_with_states);   
    }

    public function listingEngineer()
    {  
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
        return view('listingEngineer',['subwards'=>$subwards,'log'=>$log,'log1'=>$log1,'tlwards'=>$tlwards,'acc'=>$acc,'ward'=>$d]);
    }
    
    public function leDashboard()
    {
        $date_t=date('Y-m-d');
        $date_today = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date_t.'%')->first();
        $date = date('Y-m-d');
           $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
           
           $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();

      
          
        $users = User::where('department_id','1')->where('group_id','6')
                    ->leftjoin('ward_assignments','users.id','ward_assignments.user_id')
                    ->leftjoin('sub_wards','ward_assignments.subward_id','sub_wards.id')
                    ->select('users.*','sub_wards.sub_ward_name')
                    ->get();
        $accusers = User::where('department_id','2')->where('group_id','11')
                    ->leftjoin('ward_assignments','users.id','ward_assignments.user_id')
                    ->leftjoin('sub_wards','ward_assignments.subward_id','sub_wards.id')
                    ->select('users.*','sub_wards.sub_ward_name')
                    ->get();
        foreach($accusers as $user){
                $totalaccount[$user->id] = ProjectDetails::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->count();
        }
        
        foreach($users as $user){
                $totalListing[$user->id] = ProjectDetails::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->count();
        }
        $ordersInitiated = Requirement::where('generated_by',Auth::user()->id)
                            ->where( 'created_at', '>=', Carbon::now()->firstOfMonth())
                            ->count();


        $ordersConfirmed = Requirement::where('generated_by',Auth::user()->id)
                           ->where( 'created_at', '>=', Carbon::now()->firstOfMonth())
                            ->where('status','Order Confirmed')->count();



        $check = loginTime::where('user_id',Auth::user()->id)
            ->where('logindate',date('Y-m-d'))->first();
        if(($check)== null){
            $login = New loginTime;
            $login->user_id = Auth::user()->id;
            $login->logindate = date('Y-m-d');
            $login->loginTime = date('H:i A');
            $login->logoutTime = "N/A";
            $login->save();
        }
        date_default_timezone_set("Asia/Kolkata");
        $loginTime = mktime(05,15,00);
        $logoutTime = mktime(22,45,00);
        $outtime = date('H:i:sA',$logoutTime);
        $ldate = date('H:i:sA');
        $lodate = date('H:i:sA',$loginTime);
        $today = date('Y-m-d');
        $projectCount = count(ProjectDetails::where('listing_engineer_id',Auth::user()->id)
            ->where('created_at','like',$today.'%')->get());
        $loginTimes = loginTime::where('user_id',Auth::user()->id)->where('logindate',$today)->first();
        $totalLists = $loginTimes->TotalProjectsListed;

        $numbercount = count(ProjectDetails::where('listing_engineer_id',Auth::user()->id)->get());
        $wardsAssigned = WardAssignment::where('user_id',Auth::user()->id)->where('status','Not Completed')->pluck('subward_id')->first();
        $subwards = SubWard::where('id',$wardsAssigned)->first();


       $projects = ProjectDetails::join('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at','project_details.image')
                        ->get();




        $genuineprojects = count(ProjectDetails::where('quality','Genuine')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());

        $unverifiedprojects = count(ProjectDetails::where('quality','Unverified')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());
        $fakeprojects = count(ProjectDetails::where('quality','Fake')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());

        $totalprojects = count($projects);




       $today = date('Y-m-d');
        $past = date('Y-m-d',strtotime("-30 days",strtotime($today)));
       $update =User::leftjoin('project_details','project_details.listing_engineer_id','users.id')
                    ->where('project_details.sub_ward_id',$wardsAssigned)
                    ->where('users.id',Auth::user()->id)
                   ->where('project_details.updated_at',">=",$past)->pluck('project_details.project_id')->count();

         $bal = $totalprojects  -  $update;



        $prices = CategoryPrice::all();
        $points_earned_so_far = Point::where('user_id',Auth::user()->id)->where('confirmation',1)->where('created_at','LIKE',date('Y-m-d')."%")->where('type','Add')->sum('point');
        $points_subtracted = Point::where('user_id',Auth::user()->id)->where('confirmation',1)->where('created_at','LIKE',date('Y-m-d')."%")->where('type','Subtract')->sum('point');
        $points_indetail = Point::where('user_id',Auth::user()->id)->where('confirmation',1)->where('created_at','LIKE',date('Y-m-d')."%")->get();
        if($subwards != null){
            $subwardMap = SubWardMap::where('sub_ward_id',$subwards->id)->first();
        }else{
            $subwardMap = "None";
        }
        if($subwardMap == Null){
            $subwardMap = "None";
        }
        // $total = $points_earned_so_far - $points_subtracted;
         $lastmonth = count(ProjectDetails::where('listing_engineer_id',Auth::user()->id)->where( 'created_at', '>=', Carbon::now()->firstOfMonth())->get());
                 $dat = DB::table('notifications')->where('user_id',Auth::user()->id)->latest()->first();
                    if($dat == null){
                        $data = 1; 
                    }else{

                       $data = DB::table('notifications')->where('id',$dat->id)->where('approve',1)->count();
                    }
         $dat = DB::table('notifications')->where('user_id',Auth::user()->id)->latest()->first();
                       if($dat != null){
                       $dataf = DB::table('notifications')->where('id',$dat->id)->where('logout',1)->count(); 

                     }else{
                       $dataf =1;

                     }
                  
                       
        return view('listingEngineerDashboard',['date_today'=>$date_today,
                                                'prices'=>$prices,
                                                'subwards'=>$subwards,
                                                'projects'=>$projects,
                                                'numbercount'=>$numbercount,
                                                'lastmonth' =>$lastmonth,
                                                'ldate'=>$ldate,
                                                'lodate'=>$lodate,
                                                'outtime'=>$outtime,
                                                'total'=>$totalLists,
                                                'ordersInitiated'=>$ordersInitiated,
                                                'ordersConfirmed'=>$ordersConfirmed,
                                                'points_indetail'=>$points_indetail,
                                                'points_earned_so_far'=>$points_earned_so_far,
                                                'points_subtracted'=>$points_subtracted,
                                                'subwardMap'=>$subwardMap,
                                                'totalprojects'=>$totalprojects,
                                                'genuineprojects'=>$genuineprojects,
                                                'unverifiedprojects'=>$unverifiedprojects,
                                                'fakeprojects'=>$fakeprojects,
                                                'totalListing'=> $totalListing,
                                                'users'=>$users,
                                                'accusers'=>$accusers,
                                                // 'totalaccount'=>$totalaccount,
                                                'update' =>  $update,
                                                'bal'=>$bal,
                                                'log'=>$log,
                                                'log1'=>$log1,
                                                 'data'=>$data,
                                                 'dataf'=>$dataf
                                                // 'total'=>$total
                                                ]);
    }


public function sales(Request $request){



     $wardsAssigned = WardAssignment::where('user_id',$request->userId)->where('status','Not Completed')->pluck('subward_id')->first();
        $subwards = SubWard::where('id',$wardsAssigned)->first();

$projects = ProjectDetails::join('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get();

        $genuineprojects = count(ProjectDetails::where('quality','Genuine')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());

        $unverifiedprojects = count(ProjectDetails::where('quality','Unverified')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());
        $fakeprojects = count(ProjectDetails::where('quality','Fake')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());

        $totalprojects = count($projects);




       $update =User::leftjoin('project_details','project_details.listing_engineer_id','users.id')
                    ->where('project_details.sub_ward_id',$wardsAssigned)
                   ->where('project_details.updated_at','LIKE',date('Y-m-d')."%")->pluck('project_details.project_id')->count();

         $bal = $totalprojects  -  $update ;


         return response()->json(['balance'=>$bal]);


}



    public function projectList()
    {
        $projectlist = ProjectDetails::where('listing_engineer_id',Auth::user()->id)->get();
        return view('projectlist',['projectlist'=>$projectlist]);
    }
    public function editProject(request $request)
    {
       
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $projectdetails = ProjectDetails::withTrashed()->where('project_id',$request->projectId)->with('ownerdetails')->first();
        $projectimages = ProjectImage::where('project_id',$request->projectId)->get();
        $projectupdate = ProjectImage::where('project_id',$request->projectId)->pluck('created_at')->last();
        if(Auth::user()->group_id == 22){
           $wardsAssigned = $request->subward; 
        }else{
            
        $wardsAssigned = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
        }
        $subwards = SubWard::where('id',$wardsAssigned)->first();
        $roomtypes = RoomType::where('project_id',$request->projectId)->get();
        $projectward = SubWard::where('id',$projectdetails->sub_ward_id)->pluck('sub_ward_name')->first();
        $user = User::where('id',$projectdetails->listing_engineer_id)->pluck('name')->first();
        $updater = User::where('id',$projectdetails->updated_by)->first();
        // $projectdetails['budgetType'] = explode(",", $projectdetails['budgetType']);
       
        return view('update',[
                    'updater'=>$updater,
                    'username'=>$user,
                    'subwards'=>$subwards,
                    'projectdetails'=>$projectdetails,
                    'projectimages'=>$projectimages,
                    'projectward'=>$projectward,
                    'projectupdate'=>$projectupdate,
                    'roomtypes'=>$roomtypes,
                    'log'=>$log,
                    'log1'=>$log1

                ]);
    }


    public function viewAll()
    {
        $allProjects = ProjectDetails::all();
        return view('allProjects',['allProjects'=>$allProjects]);
    }
    public function viewDetails($id)
    {
        $projectdetails = ProjectDetails::where('project_id',$id)->first();
        return view('viewDetails',['projectdetails'=>$projectdetails]);
    }
    public function getRoads()
    {
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
       $assignment = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
        // $roads = ProjectDetails::where('sub_ward_id',$assignment)->select('road_name')->groupby('road_name')->paginate(10);

        // $roadname = ProjectDetails::where('sub_ward_id',$assignment)->groupby('road_name')->pluck('road_name');

         $todays = ProjectDetails::where('listing_engineer_id',Auth::user()->id)->where('created_at','LIKE',date('Y-m-d')."%")->count();

        // $projectcount = array();

        // foreach($roadname as $roadname){
            $genuine = ProjectDetails::where('quality','Genuine')
                                                    ->where('sub_ward_id',$assignment)
                                                    ->paginate(10);
             $genuine1 = ProjectDetails::where('quality','Genuine')
                                                    ->where('sub_ward_id',$assignment)
                                                    ->count();                                        

            $null = ProjectDetails::where('quality','Unverified')
                                                    ->where('sub_ward_id',$assignment)
                                                    ->paginate(10);
               $null1 = ProjectDetails::where('quality','Unverified')
                                                    ->where('sub_ward_id',$assignment)
                                                    ->count();
            $fake = ProjectDetails::where('quality','Fake')
                                                    ->where('sub_ward_id',$assignment)
                                                    ->paginate(10);
            $fake1 = ProjectDetails::where('quality','Fake')
        ->where('sub_ward_id',$assignment)
        ->count();


            // $projectCount = $null + $genuine; + $fake;

        // }

      date_default_timezone_set("Asia/Kolkata");
        $loginTime = mktime(05,15,00);
        $logoutTime = mktime(22,45,00);
        $outtime = date('H:i:sA',$logoutTime);
        $ldate = date('H:i:sA');
        $lodate = date('H:i:sA',$loginTime);

        $today = date('Y-m-d');
        $projectCount = count(ProjectDetails::where('listing_engineer_id',Auth::user()->id)
            ->where('created_at','like',$today.'%')->get());
        $loginTimes = loginTime::where('user_id',Auth::user()->id)->where('logindate',$today)->first();
        $totalLists = $loginTimes->TotalProjectsListed;

        $numbercount = count(ProjectDetails::where('listing_engineer_id',Auth::user()->id)->get());
        $wardsAssigned = WardAssignment::where('user_id',Auth::user()->id)->where('status','Not Completed')->pluck('subward_id')->first();
        $subwards = SubWard::where('id',$wardsAssigned)->first();


       $projects = ProjectDetails::join('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get();




        $genuineprojects = count(ProjectDetails::where('quality','Genuine')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());

        $unverifiedprojects = count(ProjectDetails::where('quality','Unverified')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());
        $fakeprojects = count(ProjectDetails::where('quality','Fake')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());

        $totalprojects = count($projects);

       $gc = $genuine1 + $null1 + $fake1;


       $today = date('Y-m-d');
        $past = date('Y-m-d',strtotime("-30 days",strtotime($today)));
       $update =User::leftjoin('project_details','project_details.listing_engineer_id','users.id')
                    ->where('project_details.sub_ward_id',$wardsAssigned)
                    ->where('users.id',Auth::user()->id)
                   ->where('project_details.updated_at',">=",$past)->pluck('project_details.project_id')->count();

         $bal = $totalprojects  -  $update;                                             


        return view('requirementsroad',['todays'=>$todays,'genuine'=>$genuine,'null'=>$null,'fake'=>$fake, 'subwards'=>$subwards,
             'projects'=>$projects,
            'numbercount'=>$numbercount,
             'totalprojects'=>$totalprojects,
            'genuineprojects'=>$genuineprojects,
            'unverifiedprojects'=>$unverifiedprojects,
            'fakeprojects'=>$fakeprojects,
            'bal'=>$bal,'update'=>$update,
             'log'=>$log,
             'log1'=>$log1,
                  'gc'=>$gc]);



    }
    public function viewProjectList(Request $request)
    {
        if($request->today){
            $projectlist = ProjectDetails::where('created_at','LIKE',date('Y-m-d')."%")->where('listing_engineer_id',Auth::user()->id)->get();
        }else{
            $assignment = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
            $projectlist = ProjectDetails::where('road_name',$request->road)
            ->where('sub_ward_id',$assignment)
            ->get();
        }
        if($request->quality){
            $assignment = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
            $projectlist = ProjectDetails::where('quality',$request->quality)
            ->where('sub_ward_id',$assignment)
            ->get();
          
        }

        return view('projectlist',['projectlist'=>$projectlist,'pageName'=>"Update"]);
    }

    public function getMyReports(Request $request)
    {
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
        $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $now = date('H:i:s');
        $currentURL = url()->current();;
        $display = "";
        $evening = "";
        
        $url ="https://mamahome.blob.core.windows.net/media";

        

        if(!$request->date){
            date_default_timezone_set("Asia/Kolkata");
            $today = date('Y-m-d');
            $projectCount = count(ProjectDetails::where('listing_engineer_id',Auth::user()->id)
                ->where('created_at','like',$today.'%')->get());
            $loginTimes = loginTime::where('user_id',Auth::user()->id)->where('logindate',$today)->first();
            $display .= "<tr><td>Login Time</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->loginTime : '').
                        "</td></tr><tr><td>Allocated Ward</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->allocatedWard : '').
                        "</td></tr><tr><td>First Listing Time</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->firstListingTime : '').
                        "</td></tr><tr><td>First Update Time</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->firstUpdateTime : '').
                        "</td></tr><tr><td>No. of Projects Listed <br> In The Morning</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->noOfProjectsListedInMorning : '').
                        "</td></tr><tr><td>No. of Projects Updated <br> In The Morning</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->noOfProjectsUpdatedInMorning : '').
                        "</td></tr><tr><td>Meter Image</td><td>:</td><td>".
                        ($loginTimes != null ? ($loginTimes->morningMeter != null ? "<img src='"
                        .$url."/meters/".$loginTimes->morningMeter.
                        "' height='100' width='200' class='img img-thumbnail'>" : '*No Image Uploaded*') : '*No Image Uploaded*').
                        "</td></tr><tr><td>Meter Reading</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->gtracing : '').
                        "</td></tr><tr><td>Data Image</td><td>:</td><td>".
                        ($loginTimes != null ? ($loginTimes->morningData != null ? "<img src='"
                        .$url."/data/".$loginTimes->morningData.
                        "' height='100' width='200' class='img img-thumbnail'>" : '*No Image Uploaded*') : '*No Image Uploaded*').
                        "</td></tr><tr><td>Data Reading</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->afternoonData : '').
                        "</td></tr><tr><td>Team Leader Remarks</td><td>:</td><td>".
                        ($loginTimes != null ? $loginTimes->morningRemarks : '')."</td></tr>";

                    $evening .= "<tr><td>Last Listing Time</td><td>:</td><td>"
                    .($loginTimes != null ? $loginTimes->lastListingTime : '').
                    "</td></tr><tr><td>Last Update Time</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->lastUpdateTime : '').
                    "</td></tr><tr><td>Total Projects Listed</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->TotalProjectsListed : '').
                    "</td></tr><tr><td>Total Projects Updated</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->totalProjectsUpdated : '').
                    "</td></tr><tr><td>Meter Image</td><td>:</td><td>".
                    ($loginTimes != null ? ($loginTimes->eveningMeter != null ? "<img src='"
                    .$url."/meters/".$loginTimes->eveningMeter.
                    "' height='100' width='200' class='img img-thumbnail'>" : '*No Image Uploaded*') : '*No Image Uploaded*').
                    ($loginTimes != null ? $loginTimes->eveningMeter : '').
                    "</td></tr><tr><td>Meter Reading</td><td>:</td><td>".
                    "</td></tr><tr><td>Data Image</td><td>:</td><td>".
                    ($loginTimes != null ? ($loginTimes->afternoonMeter != null ? "<img src="
                    .$url."/data/".$loginTimes->eveningData.
                    " height='100' width='200' class='img img-thumbnail'>"
                    : '*No Image Uploaded*') : '*No Image Uploaded*').
                    ($loginTimes != null ? $loginTimes->eveningData : '').
                    // "</td></tr><tr><td>Data Reading</td><td>:</td><td>".
                    // ($loginTimes != null ? $loginTimes->afternoonRemarks : '').
                     "</td></tr><tr><td>Team Leader Remark</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->eveningRemarks : '').
                    "</td></tr><tr><td>Asst. Manager Remarks</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->AmRemarks : '').
                    "</td></tr><tr><td>Grade</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->AmGrade : '').
                    "</td></tr></table>";
            return view('reports',[
                'evening'=>$evening,
                'display'=>$display,
                'loginTimes'=>$loginTimes,
                'projectCount'=>$projectCount,
                'now'=>$now,
                'log'=>$log,
                'log1'=>$log1
            ]);
        }else{
            $projectCount = count(ProjectDetails::where('listing_engineer_id',Auth::user()->id)
                ->where('created_at','like',$request->date.'%')->get());
            $loginTimes = loginTime::where('user_id',Auth::user()->id)->where('logindate',$request->date)->first();
            $display .= "<tr><td>Login Time</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->loginTime : '').
                        "</td></tr><tr><td>Allocated Ward</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->allocatedWard : '').
                        "</td></tr><tr><td>First Listing Time</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->firstListingTime : '').
                        "</td></tr><tr><td>First Update Time</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->firstUpdateTime : '').
                        "</td></tr><tr><td>No. of projects listed <br> in the morning</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->noOfProjectsListedInMorning : '').
                        "</td></tr><tr><td>No. of projects updated <br> in the morning</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->noOfProjectsUpdatedInMorning : '').
                        "</td></tr><tr><td>Meter Image</td><td>:</td><td>".
                        ($loginTimes != null ? ($loginTimes->morningMeter != null ? "<img src='"
                        .$url."/meters/".$loginTimes->morningMeter.
                        "' height='100' width='200' class='img img-thumbnail'>" : '*No Image Uploaded*') : '*No Image Uploaded*').
                        "</td></tr><tr><td>Meter Reading</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->gtracing : '').
                        "</td></tr><tr><td>Data Image</td><td>:</td><td>".
                        ($loginTimes != null ? ($loginTimes->morningData != null ? "<img src='"
                        .$url."/data/".$loginTimes->morningData.
                        "' height='100' width='200' class='img img-thumbnail'>" : '*No Image Uploaded*') : '*No Image Uploaded*').
                        "</td></tr><tr><td>Data Reading</td><td>:</td><td>"
                        .($loginTimes != null ? $loginTimes->afternoonData : '').
                        "</td></tr><tr><td>Team Leader Remarks</td><td>:</td><td>".
                        ($loginTimes != null ? $loginTimes->morningRemarks : '')."</td></tr>";

                        $evening .= "<tr><td>Last Listing Time</td><td>:</td><td>"
                    .($loginTimes != null ? $loginTimes->lastListingTime : '').
                    "</td></tr><tr><td>Last Update Time</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->lastUpdateTime : '').
                    "</td></tr><tr><td>Total Projects Listed</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->TotalProjectsListed : '').
                    "</td></tr><tr><td>Total Projects Updated</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->totalProjectsUpdated : '').
                    "</td></tr><tr><td>Meter Image</td><td>:</td><td>".
                    ($loginTimes != null ? ($loginTimes->eveningMeter != null ? "<img src='"
                    .$url."/meters/".$loginTimes->eveningMeter.
                    "' height='100' width='200' class='img img-thumbnail'>" : '*No Image Uploaded*') : '*No Image Uploaded*').
                    ($loginTimes != null ? $loginTimes->eveningMeter : '').
                    "</td></tr><tr><td>Meter Reading</td><td>:</td><td>".
                    "</td></tr><tr><td>Data Image</td><td>:</td><td>".
                    ($loginTimes != null ? ($loginTimes->afternoonMeter != null ? "<img src="
                    .$url."/meters/".$loginTimes->afternoonMeter.
                    " height='100' width='200' class='img img-thumbnail'>"
                    : '*No Image Uploaded*') : '*No Image Uploaded*').
                    ($loginTimes != null ? $loginTimes->eveningData : '').
                    "</td></tr><tr><td>Data Reading</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->afternoonRemarks : '').

                     "</td></tr><tr><td>Team Leader Remark</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->eveningRemarks : '').

                    "</td></tr><tr><td>Asst. Manager Remarks</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->AmRemarks : '').
                    "</td></tr><tr><td>Grade</td><td>:</td><td>".
                    ($loginTimes != null ? $loginTimes->AmGrade : '').
                    "</td></tr></table>";
            return view('reports',[
                'loginTimes'=>$loginTimes,
                'projectCount'=>$projectCount,
                'display'=>$display,
                'evening'=>$evening,
                'now'=>$now,
                'log'=>$log,
                'log1'=>$log1
            ]);
        }
    }
    public function updateAssignment(){
        WardAssignment::where('user_id',Auth::user()->id)->delete();
        return back();
    }
    public function viewLeReport(Request $request)
    {
        $id = $request->UserId;
        $username = User::where('id',$id)->first();
        if($request->date){
            $points_earned_so_far = Point::where('user_id',$id)
                                ->where('created_at','LIKE',$request->date."%")
                                ->where('confirmation',1)
                                ->where('type','Add')
                                ->sum('point');
            $points_subtracted = Point::where('user_id',$id)
                                ->where('created_at','LIKE',$request->date."%")
                                ->where('confirmation',1)
                                ->where('type','Subtract')
                                ->sum('point');
            $points_indetail = Point::where('user_id',$id)
                                ->where('created_at','LIKE',$request->date."%")
                                ->where('confirmation',1)
                                ->get();
            $total = $points_earned_so_far - $points_subtracted;
            $loginTimes = loginTime::where('user_id',$id)
                ->where('logindate',$request->date)->first();
            if($loginTimes != NULL){
                return view('lereportbytl',[
                    'points_earned_so_far' => $points_earned_so_far,
                    'points_subtracted'=>$points_subtracted,
                    'points_indetail'=>$points_indetail,
                    'total'=>$total,
                    'loginTimes'=>$loginTimes,
                    'userId'=>$id,
                    'username'=>$username
                    ]);
            }else{
                $loginTimes = loginTime::where('user_id',$id)
                    ->where('logindate',date('Y-m-d'))->first();
                return back()->with('Error','No Records found');
            }
        }
        $points_earned_so_far = Point::where('user_id',$id)
                                ->where('created_at','LIKE',date('Y-m-d')."%")
                                ->where('confirmation',1)
                                ->where('type','Add')
                                ->sum('point');
        $points_subtracted = Point::where('user_id',$id)
                                ->where('created_at','LIKE',date('Y-m-d')."%")
                                ->where('confirmation',1)
                                ->where('type','Subtract')
                                ->sum('point');
        $points_indetail = Point::where('user_id',$id)
                                ->where('created_at','LIKE',date('Y-m-d')."%")
                                ->where('confirmation',1)
                                ->get();
        $total = $points_earned_so_far - $points_subtracted;
        $loginTimes = loginTime::where('user_id',$id)
            ->where('logindate',date('Y-m-d'))->first();
        return view('lereportbytl',[
            'points_earned_so_far' => $points_earned_so_far,
            'points_subtracted'=>$points_subtracted,
            'points_indetail'=>$points_indetail,
            'total'=>$total,
            'loginTimes'=>$loginTimes,
            'userId'=>$id,
            'username'=>$username
            ]);
    }
    public function getRequirementRoads()
    {
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
        $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $assignment = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
        $roadname = ProjectDetails::where('sub_ward_id',$assignment)->groupBy('road_name')->pluck('road_name');
        $roads = ProjectDetails::where('sub_ward_id',$assignment)->select('road_name')->groupBy('road_name')->paginate(10);
        $projectcount = array();
        $todays = ProjectDetails::where('listing_engineer_id',Auth::user()->id)->where('created_at','LIKE',date('Y-m-d')."%")->count();


        $name = ProjectDetails::where('sub_ward_id',$assignment)->where('quality','Genuine')->select("road_name")->groupBy('road_name')->pluck("project_id");

        foreach($roadname as $roadw){
            $genuine = ProjectDetails::where('road_name',$roadw)
                                                    ->where('quality','Genuine')
                                                    ->where('sub_ward_id',$assignment)
                                                    ->count();
            $null = ProjectDetails::where('road_name',$roadw)
                                                    ->where('quality','Unverified')
                                                    ->where('sub_ward_id',$assignment)
                                                    ->count();
            $projectcount[$roadw] = $null + $genuine;


$genuine1 = ProjectDetails::where('quality','Genuine')
                                                    ->where('sub_ward_id',$assignment)
                                                    ->count(); 
$null1 = ProjectDetails::where('quality','Unverified')
                                                    ->where('sub_ward_id',$assignment)
                                                    ->count();
$fake1 = ProjectDetails::where('quality','Fake')
        ->where('sub_ward_id',$assignment)
        ->count();

date_default_timezone_set("Asia/Kolkata");
        $loginTime = mktime(05,15,00);
        $logoutTime = mktime(22,45,00);
        $outtime = date('H:i:sA',$logoutTime);
        $ldate = date('H:i:sA');
        $lodate = date('H:i:sA',$loginTime);

        $today = date('Y-m-d');
        $projectCount = count(ProjectDetails::where('listing_engineer_id',Auth::user()->id)
            ->where('created_at','like',$today.'%')->get());
        $loginTimes = loginTime::where('user_id',Auth::user()->id)->where('logindate',$today)->first();
        $totalLists = $loginTimes->TotalProjectsListed;

        $numbercount = count(ProjectDetails::where('listing_engineer_id',Auth::user()->id)->get());
        $wardsAssigned = WardAssignment::where('user_id',Auth::user()->id)->where('status','Not Completed')->pluck('subward_id')->first();
        $subwards = SubWard::where('id',$wardsAssigned)->first();


       $projects = ProjectDetails::join('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get();




        $genuineprojects = count(ProjectDetails::where('quality','Genuine')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());

        $unverifiedprojects = count(ProjectDetails::where('quality','Unverified')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());
        $fakeprojects = count(ProjectDetails::where('quality','Fake')
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get());
        $today = date('Y-m-d');
        $past = date('Y-m-d',strtotime("-30 days",strtotime($today)));
       $update =User::leftjoin('project_details','project_details.listing_engineer_id','users.id')
                    ->where('project_details.sub_ward_id',$wardsAssigned)
                    ->where('users.id',Auth::user()->id)
                   ->where('project_details.updated_at',">=",$past)->pluck('project_details.project_id')->count();


        $totalprojects = count($projects);

       $gc = $genuine1 + $null1 + $fake1;
         $bal = $totalprojects  -  $update;                                             

        }
        return view('requirementsroad',['todays'=>$todays,'roads'=>$roads,'projectcount'=>$projectcount,'roadname'=>$roadname,'subwards'=>$subwards,
             'projects'=>$projects,
            'numbercount'=>$numbercount,
             'totalprojects'=>$totalprojects,
            'genuineprojects'=>$genuineprojects,
            'unverifiedprojects'=>$unverifiedprojects,
            'fakeprojects'=>$fakeprojects,
            'bal'=>$bal,'update'=>$update,
             'log'=>$log,
             'log1'=>$log1,
                  'gc'=>$gc
                                        
]);
        return view('requirementsroad',['todays'=>$todays,'roads'=>$roads,'projectcount'=>$projectcount,'roadname'=>$roadname,'log'=>$log,'log1'=>$log1]);
    }
    public function projectRequirement(Request $request)
    {


        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        if($request->today){
            $projectlist = ProjectDetails::where('created_at','LIKE',date('Y-m-d')."%")->where('listing_engineer_id',Auth::user()->id)->get();

        } 
        if($request->today){
            $projectlist1 = ProjectDetails::where('created_at','LIKE',date('Y-m-d')."%")->where('listing_engineer_id',Auth::user()->id)->count();
        }
        else{
        $assignment = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
        $projectlist = ProjectDetails::where('road_name',$request->road)
        ->where('sub_ward_id',$assignment)
            ->get();
        }
        if($request->quality){
            $assignment = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
            $projectlist = ProjectDetails::where('quality',$request->quality)
            ->where('sub_ward_id',$assignment)
                ->paginate(10);
        }
         if($request->quality){
            $assignment = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
            $projectlist1 = ProjectDetails::where('quality',$request->quality)
            ->where('sub_ward_id',$assignment)
                ->count();

        }
        if($request->quality == "UnUpdate"){
            $previous = date('Y-m-d',strtotime('-30 days'));
            $assignment = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
             $projectlist = ProjectDetails::where( 'updated_at', '<=', $previous)
                    ->where('sub_ward_id',$assignment)
                    ->where('quality','!=',"Fake")
                    ->where('project_status','NOT LIKE','%Closed%')
                    ->paginate(10);
                 $projectlist1 = count( $projectlist);
        }
         $details = array();
         $ids = array();
        if($request->phNo){
            $details[0] = ContractorDetails::where('contractor_contact_no',$request->phNo )->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[1] = ProcurementDetails::where('procurement_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[2] = SiteEngineerDetails::where('site_engineer_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[3] = ConsultantDetails::where('consultant_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[4] = OwnerDetails::where('owner_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[5] = ProjectDetails::where('project_id',$request->phno)->get();
            for($i = 0; $i < count($details); $i++){
                for($j = 0; $j<count($details[$i]); $j++){
                    array_push($ids, $details[$i][$j]);
                }

            }

            $assignment = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
            $projectlist = ProjectDetails::whereIn('project_id',$ids)
                             ->where('sub_ward_id',$assignment)
                           ->get();

                $projectlist1 = count( $projectlist);    
        }

        return view('projectlist',['projectlist'=>$projectlist,'projectlist1'=>$projectlist1,'pageName'=>"Requirements",'log'=>$log,'log1'=>$log1,'date'=>$date]);
        
    }
    public function getRequirements(Request $request)
    {
        $depart = [2,4,8,6,7];
        $users = User::whereIn('group_id',$depart)->where('department_id','!=',10)->get();
        $assignment = salesassignment::where('user_id',Auth::user()->id)->pluck('assigned_date')->first();
        $requirements = Requirement::where('project_id',$request->projectId)->get();
        $projects = ProjectDetails::where('project_id', $request->projectId)->first();
        $category = Category::all();
        return view('requirements',['category'=>$category, 'requirements'=>$requirements,'id'=>$request->projectId,'projects'=>$projects,'assignment'=>$assignment,'users'=>$users]);
    }
    public function deleteReportImage(request $request)
    { 
          
             $image = $request->file('mrngmeter');

             $imageFileName ="mrngmeter". time() . '.' . $image->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/meters/' . $imageFileName;
             $s3->put($filePath, file_get_contents($image), 'public');
 

        loginTime::where('id',$request->id)->update([
            'morningMeter' => $imageFileName,
        ]);
        return back();
    }
    public function deleteReportImage2(request $request)
    {
       $image = $request->file('mrngdata');

             $imageFileName ="mrngdata". time() . '.' . $image->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/data/' . $imageFileName;
             $s3->put($filePath, file_get_contents($image), 'public');
        loginTime::where('id',$request->id)->update([
            'morningData' => $imageFileName,
        ]);
        return back();
    }
    public function deleteReportImage3($id)
    {
        $file = loginTime::where('id',$id)->pluck('afternoonMeter')->first();
        $file_path = "public/meters/".$file;
        if(file_exists($file_path)){
            @unlink($file_path);
        }
        loginTime::where('id',$id)->update([
            'afternoonMeter' => Null,
        ]);
        return back();
    }
    public function deleteReportImage4($id)
    {
        $file = loginTime::where('id',$id)->pluck('afternoonData')->first();
        $file_path = "public/data/".$file;
        if(file_exists($file_path)){
            @unlink($file_path);
        }
        loginTime::where('id',$id)->update([
            'afternoonData' => Null,
        ]);
        return back();
    }
    public function deleteReportImage5(request $request)
    {
             $image = $request->file('mrngdata');

             $imageFileName ="evngmeter". time() . '.' . $image->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/meters/' . $imageFileName;
             $s3->put($filePath, file_get_contents($image), 'public');
       
        
        loginTime::where('id',$request->id)->update([
            'eveningMeter' => $imageFileName,
        ]);
        return back();
    }
    public function deleteReportImage6(request $request)
    { 
         $image = $request->file('mrngdata');

             $imageFileName ="evngdata". time() . '.' . $image->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/data/' . $imageFileName;
             $s3->put($filePath, file_get_contents($image), 'public');
       
        
        loginTime::where('id',$request->id)->update([
            'eveningData' =>$imageFileName,
        ]);
        return back();
    }
    public function getConfirmOrder($id)
    {
        $orders = Requirement::where('project_id',$id)->where('status','Order Confirmed')->get();
        $project = projectdetails::where('project_id',$id)->first();
        return view('confirmed',['orders'=>$orders,'project'=>$project,'id'=>$id]);
    }
    public function getPayment(Request $request)
    {
        $total = $request->total;

        return view('payment.payment',['total'=>$total]);
    }
    public function getAMReports()
    {
        $group = Group::where('group_name','Listing Engineer')->pluck('id')->first();
        $group2 = Group::where('group_name','Sales Engineer')->pluck('id')->first();
        $users = User::where('group_id',$group)->orwhere('group_id',$group2)->paginate(10);
        return view('reportsbyam',['users'=>$users]);
    }
    public function getViewReports($id,$date)
    {
        $user = User::where('id',$id)->first();
        $logintimes = loginTime::where('user_id',$id)->where('logindate',$date)->first();
        $points_earned_so_far = Point::where('user_id',$id)
                                ->where('created_at','LIKE',$date."%")
                                // ->where('confirmation',1)
                                ->where('type','Add')
                                ->sum('point');
        $points_subtracted = Point::where('user_id',$id)
                            ->where('created_at','LIKE',$date."%")
                            // ->where('confirmation',1)
                            ->where('type','Subtract')
                            ->sum('point');
        $points_indetail = Point::where('user_id',$id)
                            ->where('created_at','LIKE',$date."%")
                            // ->where('confirmation',1)
                            ->get();
        $total = $points_earned_so_far - $points_subtracted;

         
        return view('/amreport',[
                'logintimes'=>$logintimes,
                'user'=>$user,
                'date'=>$date,
                'points_earned_so_far'=>$points_earned_so_far,
                'points_subtracted'=>$points_subtracted,
                'points_indetail'=>$points_indetail,
                'total'=>$total
            ]);
    }
    public function amreportdates($uid, Request $request){
        if($request->month != null){
            $today = $request->year."-".$request->month;
        }else{
            $today = date('Y-m');
        }
     
        $dates = FieldLogin::where('user_id',$uid)->where('logindate','like',$today.'%')->orderby('logindate','ASC')->get();
    
             

        $user = User::where('id',$uid)->first();
  
        return view('choosedates',['dates'=>$dates,'uid'=>$uid,'user'=>$user]);
    }
    public function placeOrder($id, Request $request)
    {
        $requirement = Requirement::where('id',$id)->first();
        $requirement->status = 'Order Placed';
        $requirement->save();
        $orders = Requirement::where('id',$id)->first();
        return view('confirm',['orders'=>$orders,'id'=>$id])->with('Success','Order has been placed successfully');
    }
    public function invoice($id, Request $request)
    {
        $requirement = Requirement::where('id',$id)->first();
        Mail::to($request->email)->send(new invoice($id));
        return redirect('/requirementsroads');
    }
    public function completethis(Request $id)
    {
        $assignment = salesassignment::where('user_id',$id->userid)->first();
        $ward = SubWard::where('id',$assignment->assigned_date)->first();
        $assignment->prev_assign = $ward->sub_ward_name;
        $assignment->status = 'Completed';
        $assignment->save();
        salesassignment::where('user_id',Auth::user()->id)->delete();
        return back();
    }
     public function completethis1(Request $user_id)
    {
        $assignment =AssignStage::where('user_id',Auth::user()->id)->first();
        $assignment->state = 'Completed';
        $assignment->save();
        AssignStage::where('user_id',Auth::user()->id)->delete();
        return back();
    }

    // sales
    public function getSalesTL(){
        $id = Department::where('dept_name',"Sales")->pluck('id')->first();
        $users = User::where('department_id',$id)
                        ->leftjoin('salesassignments','salesassignments.user_id','=','users.id')
                        ->leftjoin('sub_wards','salesassignments.assigned_date','=','sub_wards.id')
                        ->select('salesassignments.*','users.employeeId','users.name','users.id','sub_wards.sub_ward_name')
                        ->get();
        $subwardsAssignment = salesassignment::where('status','Not Completed')->get();
        $wards = Ward::all();
        return view('salestl',['users'=>$users,'subwardsAssignment'=>$subwardsAssignment,'pageName'=>'Assign','wards'=>$wards]);
    }
    public function getSalesEngineer()
    {
         
        $tl1= Tlwards::where('group_id','=',22)->get();
        $userid = Auth::user()->id;
        $found1 = null;
        foreach($tl1 as $searchWard){
            $usersId = explode(",",$searchWard->users);
            if(in_array($userid, $usersId)){
                $found1 = $searchWard->ward_id;
            }
        }
        $found = explode(",",$found1);
        $ward =Ward::whereIn('id',$found)->get();
        // $today = date('Y-m');
        // $requests = User::where('department_id', 100)->where('confirmation',0)->orderBy('created_at','DESC')->get();
        // $reqcount = count($requests);
        // $assignment = salesassignment::where('user_id',Auth::user()->id)->pluck('assigned_date')->first();
        // $projects = ProjectDetails::where('created_at','like',$assignment.'%')->paginate(10);
        // $calls = ProjectDetails::where('call_attended_by',Auth::user()->id)->count();
        // $followups = ProjectDetails::where('follow_up_by',Auth::user()->id)->count();
        // $ordersinitiate = Requirement::where('generated_by',Auth::user()->id)
        //                     ->where('status','Order Initiated')
        //                     ->count();
        // $ordersConfirmed = Requirement::where('generated_by',Auth::user()->id)
        //                     ->where('status','Order Confirmed')
        //                     ->count();
        // $fakeProjects = ProjectDetails::where('quality','Fake')
        //                         ->where('call_attended_by',Auth::user()->id)
        //                         ->count();
        // $genuineProjects = ProjectDetails::where('quality','Genuine')
        //                         ->where('call_attended_by',Auth::user()->id)
        //                         ->count();
        // $total = $fakeProjects + $genuineProjects;
        // $prices = CategoryPrice::all();
        // $points_earned_so_far = Point::where('user_id',Auth::user()->id)->where('created_at','LIKE',date('Y-m-d')."%")->where('type','Add')->sum('point');
        // $points_subtracted = Point::where('user_id',Auth::user()->id)->where('created_at','LIKE',date('Y-m-d')."%")->where('type','Subtract')->sum('point');
        // $points_indetail = Point::where('user_id',Auth::user()->id)->where('created_at','LIKE',date('Y-m-d')."%")->get();
        // $total = $points_earned_so_far - $points_subtracted;
        $stages = AssignStage::where('user_id',Auth::user()->id)->first();
        $date=date('Y-m-d');
        $date_today = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->get();

         $followup = Requirement::where('follow_up','LIKE',$date.'%')->where('follow_up_by',Auth::user()->id)->get();
         $callreport = History::where('called_Time','LIKE',$date.'%')->where('user_id',Auth::user()->id)->paginate("10");
        
        return view('sedashboard',[
            // 'projects'=>$projects,
            // 'reqcount'=>$reqcount,
            // 'assignment'=>$assignment,
            // 'prices'=>$prices,
            // 'calls'=>$calls,
            // 'followups'=>$followups,
            // 'ordersinitiate'=>$ordersinitiate,
            // 'ordersConfirmed'=>$ordersConfirmed,
            // 'fakeProjects'=>$fakeProjects,
            // 'genuineProjects'=>$genuineProjects,
            // 'points_indetail'=>$points_indetail,
            // 'points_earned_so_far'=>$points_earned_so_far,
            // 'points_subtracted'=>$points_subtracted,
            // 'total'=>$total,
            // 'stages'=>$stages,
            'ward' =>$ward,'date_today'=>$date_today,
            'followup'=>$followup,'callreport'=>$callreport
        ]);
    }
    public function printLPO($id, Request $request)
    {
        $order = Order::where('id',$id)->first();

        $rec = ProjectDetails::where('project_id', $order->project_id)->first();

        return view('printLPO', ['rec' => $rec,'order'=>$order,'id'=>$id]);
    }
    public function ampricing(Request $request){
        $prices = CategoryPrice::all();
        $categories = Category::all();
        $check =Gst::where('subcat',$request->subcat)->where('state',$request->state)->first();
           $cate = Category::where('id',$request->cat)->pluck('category_name')->first();
        if(count($check) == 0){
           
            $gst =  new Gst;
            $gst->category = $cate;
            $gst->subcat = $request->subcat;
            $gst->cgst = $request->cgst;
            $gst->sgst = $request->sgst;
            $gst->igst = $request->igst;
            $gst->state = $request->state;
            $gst->save();

        }
        else{
           $check->category = $cate;
           $check->cgst = $request->cgst;
           $check->sgst = $request->sgst;
           $check->igst = $request->igst;
           $check->subcat = $request->subcat;
           $check->save();
        }
        $gstvalue =  Gst::all();
        $states = State::all();
        return view('updateprice',['prices'=>$prices,'categories'=>$categories,'gstvalue'=>$gstvalue,'states'=>$states]);
    }

    public function setprice(request $request){
        $prices = CategoryPrice::all();
        $categories = Category::all();

        $myPrices = Pricing::leftJoin('category','pricing.cat','category.id')
                            ->leftJoin('brands','pricing.brand','brands.id')
                            ->leftJoin('category_sub','pricing.suncat','category_sub.id')
                            ->get();

             $this->variable = $categories;                
        return view('setprice',['prices'=>$prices,'categories'=>$categories,'myPrices'=>$myPrices]);
    }
 public function allprice(request $request){
       $myPrices = Pricing::leftJoin('category','pricing.cat','category.id')
                            ->leftJoin('brands','pricing.brand','brands.id')
                            ->leftJoin('category_sub','pricing.suncat','category_sub.id')
                            ->get();
         $users = User::get();
        return view('allprice',['myPrices'=>$myPrices,'users'=>$users]);
    }
  public function amorders1(Request $request)
    {
         $manusuppliers = ManufacturerDetail::all();
         $project_id = $request->projectId;
         $id= $request->projectId;
         $manu_id = $request->projectId;
         $lpo_id = $request->projectId;
        if($request->projectId){
            $view = Order::orderby('orders.created_at','DESC')
                    ->leftJoin('users','orders.generated_by','=','users.id')
                    ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                    ->leftjoin('requirements','orders.req_id','requirements.id')
                    ->leftjoin('supplierdetails','supplierdetails.order_id','=','orders.id')
                    ->orwhere('supplierdetails.lpo',$lpo_id)
                    ->orwhere('orders.id',$id)
                    ->orwhere('orders.project_id',$project_id)
                    ->orwhere('orders.manu_id',$manu_id)
                    ->select('orders.*','supplierdetails.*','orders.status as order_status','orders.delivery_status as order_delivery_status',
                    'requirements.*','orders.id as orderid','users.name','users.group_id','orders.project_id','orders.requirement_date',
                    'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material',
                    'delivery_details.delivery_video','delivery_details.delivery_date' ,'orders.payment_status as ostatus')
                    ->paginate(25);
        }else if(!$request->projectId && $request->from && $request->to && !$request->user && !$request->category){

                                            
                   $view = Order::orderby('orders.created_at','DESC')
                      ->whereDate('orders.created_at','>=',$request->from)
                      ->whereDate('orders.created_at','<=',$request->to)
                    ->leftJoin('users','orders.generated_by','=','users.id')
                    ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                    ->leftjoin('requirements','orders.req_id','requirements.id')
                    ->whereIn('orders.status',['Enquiry Confirmed','Order Confirmed','Order Cancelled'])
                    ->select('orders.*','orders.status as order_status','orders.delivery_status as order_delivery_status','requirements.*','orders.id as orderid','users.name','users.group_id','orders.project_id','orders.requirement_date',
                    'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material','delivery_details.delivery_video','delivery_details.delivery_date' ,'orders.payment_status as ostatus')
                    ->paginate(25);
           }else if(!$request->projectId && $request->from && $request->to && $request->user && $request->category){

                                            
                   $view = Order::orderby('orders.created_at','DESC')
                      ->whereDate('orders.created_at','>=',$request->from)
                      ->whereDate('orders.created_at','<=',$request->to)
                      ->where('orders.generated_by',$request->user)
                      ->where('orders.main_category',$request->category)
                    ->leftJoin('users','orders.generated_by','=','users.id')
                    ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                    ->leftjoin('requirements','orders.req_id','requirements.id')
                    ->whereIn('orders.status',['Enquiry Confirmed','Order Confirmed','Order Cancelled'])
                    ->select('orders.*','orders.status as order_status','orders.delivery_status as order_delivery_status','requirements.*','orders.id as orderid','users.name','users.group_id','orders.project_id','orders.requirement_date',
                    'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material','delivery_details.delivery_video','delivery_details.delivery_date' ,'orders.payment_status as ostatus')
                    ->paginate(25);
           }else if(!$request->projectId && $request->from && $request->to && $request->user && !$request->category){

                                            
                   $view = Order::orderby('orders.created_at','DESC')
                      ->whereDate('orders.created_at','>=',$request->from)
                      ->whereDate('orders.created_at','<=',$request->to)
                      ->where('orders.generated_by',$request->user)
                     
                    ->leftJoin('users','orders.generated_by','=','users.id')
                    ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                    ->leftjoin('requirements','orders.req_id','requirements.id')
                    ->whereIn('orders.status',['Enquiry Confirmed','Order Confirmed','Order Cancelled'])
                    ->select('orders.*','orders.status as order_status','orders.delivery_status as order_delivery_status','requirements.*','orders.id as orderid','users.name','users.group_id','orders.project_id','orders.requirement_date',
                    'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material','delivery_details.delivery_video','delivery_details.delivery_date' ,'orders.payment_status as ostatus')
                    ->paginate(25);
           }else if(!$request->projectId && $request->from && $request->to && !$request->user && $request->category){

                                            
                   $view = Order::orderby('orders.created_at','DESC')
                      ->whereDate('orders.created_at','>=',$request->from)
                      ->whereDate('orders.created_at','<=',$request->to)
                      
                      ->where('orders.main_category',$request->category)
                    ->leftJoin('users','orders.generated_by','=','users.id')
                    ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                    ->leftjoin('requirements','orders.req_id','requirements.id')
                    ->whereIn('orders.status',['Enquiry Confirmed','Order Confirmed','Order Cancelled'])
                    ->select('orders.*','orders.status as order_status','orders.delivery_status as order_delivery_status','requirements.*','orders.id as orderid','users.name','users.group_id','orders.project_id','orders.requirement_date',
                    'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material','delivery_details.delivery_video','delivery_details.delivery_date' ,'orders.payment_status as ostatus')
                    ->paginate(25);
           }else if(!$request->projectId && !$request->from && !$request->to && $request->user && !$request->category){

                                            
                   $view = Order::orderby('orders.created_at','DESC')
                     
                      ->where('orders.generated_by',$request->user)
                     
                    ->leftJoin('users','orders.generated_by','=','users.id')
                    ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                    ->leftjoin('requirements','orders.req_id','requirements.id')
                    ->whereIn('orders.status',['Enquiry Confirmed','Order Confirmed','Order Cancelled'])
                    ->select('orders.*','orders.status as order_status','orders.delivery_status as order_delivery_status','requirements.*','orders.id as orderid','users.name','users.group_id','orders.project_id','orders.requirement_date',
                    'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material','delivery_details.delivery_video','delivery_details.delivery_date' ,'orders.payment_status as ostatus')
                    ->paginate(25);
           }else if(!$request->projectId && !$request->from && !$request->to && !$request->user && $request->category){

                                            
                   $view = Order::orderby('orders.created_at','DESC')
                      
                      ->where('orders.main_category',$request->category)
                    ->leftJoin('users','orders.generated_by','=','users.id')
                    ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                    ->leftjoin('requirements','orders.req_id','requirements.id')
                    ->whereIn('orders.status',['Enquiry Confirmed','Order Confirmed','Order Cancelled'])
                    ->select('orders.*','orders.status as order_status','orders.delivery_status as order_delivery_status','requirements.*','orders.id as orderid','users.name','users.group_id','orders.project_id','orders.requirement_date',
                    'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material','delivery_details.delivery_video','delivery_details.delivery_date' ,'orders.payment_status as ostatus')
                    ->paginate(25);
           }



        else{
            $view = Order::orderby('orders.created_at','DESC')
                    ->leftJoin('users','orders.generated_by','=','users.id')
                    ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                    ->leftjoin('requirements','orders.req_id','requirements.id')
                    ->whereIn('orders.status',['Enquiry Confirmed','Order Confirmed','Order Cancelled'])
                    ->select('orders.*','orders.status as order_status','orders.delivery_status as order_delivery_status','requirements.*','orders.id as orderid','users.name','users.group_id','orders.project_id','orders.requirement_date',
                    'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material','delivery_details.delivery_video','delivery_details.delivery_date' ,'orders.payment_status as ostatus')
                    ->paginate(25);
        }
        if(Auth::user()->group_id == 23){
            $ac = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
            $catsub = Category::where('id',$ac)->pluck('category_name')->first();
             $view = Order::orderby('orders.id','DESC')
                    ->where('orders.main_category',$catsub)
                    ->leftJoin('users','orders.generated_by','=','users.id')
                    ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                    ->leftjoin('requirements','orders.req_id','requirements.id')->where('requirements.status','=','Enquiry Confirmed')
                    ->select('orders.*','orders.status as order_status','orders.delivery_status as order_delivery_status','requirements.*','orders.id as orderid','users.name','users.group_id','users.id as userid',
                    'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material','delivery_details.delivery_video','delivery_details.delivery_date' ,'orders.payment_status as ostatus')
                    ->paginate(25);
        }
        
        $depts = [1,2];
        $users = User::whereIn('department_id',$depts)->get();
        $req = Requirement::pluck('project_id');
        $paymentDetails = PaymentDetails::all();
        $payhistory = PaymentHistory::all();
        $message = Message::all();
        $chatUsers = User::all();
        $counts = array();
        $suppliers = Supplierdetails::all();
        $manudetails = ManufacturerDetail::all();
        foreach($view as $order){
            $counts[$order->orderid] = Message::where('to_user',$order->orderid)->count();
        }
        $categories = Category::all();
        $invoice = SupplierInvoice::all();   
        $denoms = Denomination::all();
        $states = State::all();
        $eusers = User::where('department_id','!=', 10)->get();
      
        return view('ordersadmin',[
            'view' => $view, 
            'users'=>$users,
            'req'=>$req,
            'paymentDetails'=>$paymentDetails,
            'messages'=>$message,
            'chatUsers'=>$chatUsers,
            'manusuppliers'=>$manusuppliers,
            'suppliers'=>$suppliers,
            'counts'=>$counts,
            'invoice'=>$invoice,
            'categories'=>$categories,
            'payhistory'=>$payhistory,
            'manudetails'=>$manudetails,
            'denoms'=>$denoms,
            'states'=>$states,
            'eusers'=>$eusers
        ]);
    }
  public function amorders(Request $request)
       {
             if(Auth::user()->group_id != 22 || Auth::user()->group_id == 23){

               return $this->amorders1($request);
             }



              $tlward = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
              $ward = explode(",",$tlward);
           if($request->projectId){
               $view = Order::orderby('orders.created_at','DESC')
                       ->leftJoin('users','orders.generated_by','=','users.id')
                       ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                       ->leftjoin('project_details','project_details.project_id','orders.project_id')
                       ->leftjoin('sub_wards','sub_wards.id','project_details.sub_ward_id')
                       ->leftJoin('wards','wards.id','sub_wards.ward_id')
                       ->select('orders.*','orders.status as order_status','orders.delivery_status as order_delivery_status','orders.id as orderid','users.name','users.group_id',
                            'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material','delivery_details.delivery_video','delivery_details.delivery_date','orders.payment_status as ostatus','orders.quantity')
                       ->where('project_details.project_id',$request->projectId)
                       ->whereIn('wards.id',$ward)
                      ->paginate(25);
                  
           }else if(!$request->projectId && $request->from && $request->to){

                    $view = Order::orderby('orders.created_at','DESC')
                        ->whereDate('orders.updated_at','>=',$request->from)
                       ->whereDate('orders.updated_at','<=',$request->to)
                       ->leftJoin('users','orders.generated_by','=','users.id')
                       ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                       ->leftjoin('project_details','project_details.project_id','orders.project_id')
                       ->leftjoin('sub_wards','sub_wards.id','project_details.sub_ward_id')
                       ->leftJoin('wards','wards.id','sub_wards.ward_id')
                       ->select('orders.*','orders.status as order_status','orders.delivery_status as order_delivery_status','orders.id as orderid','users.name','users.group_id',
                            'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material','delivery_details.delivery_video','delivery_details.delivery_date','orders.payment_status as ostatus','orders.quantity')
                       ->where('project_details.project_id',$request->projectId)
                       ->whereIn('wards.id',$ward)

                      ->paginate(25);
          

           
           }


    
           else{
               $view = Order::orderby('orders.created_at','DESC')
                       ->leftJoin('users','orders.generated_by','=','users.id')
                       ->leftJoin('delivery_details','orders.id','delivery_details.order_id')
                       ->leftjoin('requirements','orders.req_id','requirements.id')->where('requirements.status','=','Enquiry Confirmed')
                        ->leftjoin('project_details','project_details.project_id','orders.project_id')
                       ->leftjoin('sub_wards','sub_wards.id','project_details.sub_ward_id')
                       ->leftJoin('wards','wards.id','sub_wards.ward_id')
                       ->whereIn('wards.id',$ward)
                       
                       ->select('orders.*','orders.status as order_status','orders.delivery_status as order_delivery_status','requirements.*','orders.id as orderid','users.name','users.group_id',
                       'delivery_details.vehicle_no','delivery_details.location_picture','delivery_details.quality_of_material','delivery_details.delivery_video','delivery_details.delivery_date','orders.payment_status as ostatus','orders.quantity')
                       ->paginate(25);
           }
           $depts = [1,2];
           $users = User::whereIn('department_id',$depts)->get();
           $req = Requirement::pluck('project_id');
           $message = Message::all();
            $paymentDetails = PaymentDetails::all();
           $chatUsers = User::all();
            $counts = array();
            foreach($view as $order){
            $counts[$order->orderid] = Message::where('to_user',$order->orderid)->count();
            }
            $eusers = User::where('department_id','!=', 10)->get();
            return view('ordersadmin',[
                'view' => $view,
                'users'=>$users,
                'req'=>$req,
                 'paymentDetails'=>$paymentDetails,
                'messages'=>$message,
                'chatUsers'=>$chatUsers,
                'counts'=>$counts,
                'eusers'=>$eusers
            ]);
       }
    public function getSubCat(Request $request)
    {
        $cat = $request->cat;
        $category = Category::where('id',$cat)->first();
        $subcat = SubCategory::where('brand_id',$request->brand)->get();
        $res = array();
        $res[0] = $category;
        $res[1] = $subcat;
        return response()->json($res);
    }
    public function getSubCatPrices(Request $request){
        $cat = $request->cat;
        $brand = $request->brand;
        $category = Category::where('id',$cat)->first();
        $subcat = SubCategory::leftJoin('category_price','category_sub.id','=','category_price.category_sub_id')
            ->select('category_sub.*','category_price.price')
            ->where('category_sub.category_id',$cat)
           ->where('category_sub.brand_id',$brand)
            ->get();
        $res = array();
        $res[0] = $category;
        $res[1] = $subcat;
        return response()->json($res);
    }
     public function catsub(Request $request){
        $cat = $request->cat;
        
        $res = SubCategory::where('category_id',$cat)->get();
        
        return response()->json($res);
    }
    
    public function confirmOrder(Request $request)
    { 
        $id = $request->id;
        $x = Order::where('id',$id)->first();
        $x->status = 'Order Confirmed';
        $x->order_date=$request->order_placed_date;
        $x->order_value=$request->order_value;
        $x->order_placed="yes";
        $x->confirmed_by =Auth::user()->id;
        $x->save();
        // price may change ,so updates here
        PaymentDetails::where('order_id',$id)->update([
            'quantity'=>$request->quantity,
            'mamahome_price'=>$request->mamaprice,
            'unit'=>$request->unit
        ]);
                
                

                $invoice = new MamahomePrice;
                $invoice->order_id = $request->id;
                $invoice->req_id = $request->eid;
                $invoice->save();

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
                $invoiceno = "MH_".$country_code."_".$zone."_".$year."_IN".$z;
                     
                      $icount = MamahomePrice::where('invoiceno',$invoiceno)->count();

                      if($icount == 0){

                                $ino = MamahomePrice::where('order_id',$request->id)->update([
                                    'invoiceno'=>$invoiceno,
                                    'final'=>$z
                                ]);
                      }


   


 


        $cat = Order::where('id',$request->id)->pluck('main_category')->first();
        $projectid = Order::where('id',$request->id)->pluck('project_id')->first();
        $cgstval = Gst::where('category',$cat)->where('state',$request->state)->pluck('cgst')->first();
        $sgstval = Gst::where('category',$cat)->where('state',$request->state)->pluck('sgst')->first();
        $igstval =  Gst::where('category',$cat)->where('state',$request->state)->pluck('igst')->first();
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
       
        $unitwithgst = ($request->mamaprice/$percent);
        $totalamount = ($request->quantity *  $unitwithgst);
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
        $check = MamahomePrice::where('order_id',$id)->first();
        if(count($check) == 0){
                $invoice = new MamahomePrice;
                $invoice->req_id = $request->rid;
                $invoice->order_id = $id;
                $invoice->quantity = $request->quantity;
                $invoice->mamahome_price = $request->mamaprice;
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
                $invoice->state = $request->state;
                $invoice->save();
                // generate invoice
                $year = date('Y');
                $country_code = Country::pluck('country_code')->first();
                $zone = Zone::pluck('zone_number')->first();
                $invoiceno = "MH_".$country_code."_".$zone."_".$year."_IN".$invoice->id;
                 $ino = MamahomePrice::where('order_id',$id)->first();
                $ino = MamahomePrice::where('order_id',$id)->update([
                    'invoiceno'=>$invoiceno
                ]);
        }
        else{
                $check->quantity = $request->quantity;
                $check->mamahome_price = $request->mamaprice;
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
                $check->unit = $request->unit;
                $check->category = $cat;
                $check->project_id = $projectid;
                $check->state = $request->state;
                $check->save();
        }
        // dd(count($check));
        return back()->with('success','Submited successfully !');
    }
    public function cancelOrder(Request $request)
    {
        $id = $request->id;
        $y =  Order::where('id', $id)->first();
        $y->status = 'Order Cancelled';
        $y->save();
       
       
          Order::where('id',$id)->update(['cancelremark'=>$request->remark]);
        Order::where('id', $id)->delete();
        return back()->with('success','Order cancelled successfully !');
    }
     public function cancelinvoice(Request $request)
    {
        $id = $request->id;
       
        
          MamahomePrice::where('id',$id)->update(['cancelremark'=>$request->remark]);
          MamahomePrice::where('id', $id)->delete();
        return back()->with('success','invoice Cancelled  successfully !');
    }
    
    public function getPrice(Request $request)
    {
        $cat = $request->cat;
        $subcat = $request->subcat;
        $price = CategoryPrice::where('category_sub_id',$subcat)->where('category_id',$cat)->first();
        return response()->json($price);
    }

    public function updateOwner(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $phone = $request->phone;
        $email = $request->email;
        $project = OwnerDetails::where('project_id',$id)->first();
        $point = 0;
        if($project != null){
            $point = $name != $project->owner_name ? $point+5 : $point+0;
            $point = $phone != $project->owner_contact_no ? $point+5 : $point+0;
            $point = $email != $project->owner_email ? $point+5 : $point+0;
            $points = new Point;
            $points->user_id = Auth::user()->id;
            $points->point = $point;
            $points->type = "Add";
            $points->reason = "Updating owner details";
            $points->save();
        }
        $x = OwnerDetails::where('project_id',$id)->first();
        $x->owner_name = $name;
        $x->owner_contact_no = $phone;
        $x->owner_email = $email;
        if($x)
        {
            return response()->json('Success !!!');
        }
        else
        {
            return response()->json('Error !!!');
        }
    }
    public function updateContractor(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $phone = $request->phone;
        $email = $request->email;
        $project = ContractorDetails::where('project_id',$id)->first();
        $point = 0;
        if($project != null){
            $point = $name != $project->contractor_name ? $point+3 : $point+0;
            $point = $phone != $project->contractor_contact_no ? $point+3 : $point+0;
            $point = $email != $project->contractor_email ? $point+3 : $point+0;
            $points = new Point;
            $points->user_id = Auth::user()->id;
            $points->point = $point;
            $points->type = "Add";
            $points->reason = "Updating contractor details";
            $points->save();
        }
        $x = ContractorDetails::where('project_id',$id)->first();
        $x->contractor_name = $name;
        $x->contractor_contact_no = $phone;
        $x->contractor_email = $email;
        if($x->save())
        {
            return response()->json('Success !!!');
        }
        else
        {
            return response()->json('Error !!!');
        }
    }
    public function updateConsultant(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $phone = $request->phone;
        $email = $request->email;
        $project = ConsultantDetails::where('project_id',$id)->first();
        $point = 0;
        if($project != null){
            $point = $name != $project->consultant_name ? $point+3 : $point+0;
            $point = $phone != $project->consultant_contact_no ? $point+3 : $point+0;
            $point = $email != $project->consultant_email ? $point+3 : $point+0;
            $points = new Point;
            $points->user_id = Auth::user()->id;
            $points->point = $point;
            $points->type = "Add";
            $points->reason = "Updating consultant details";
            $points->save();
        }
        $x = ConsultantDetails::where('project_id',$id)->first();
        $x->consultant_name = $name;
        $x->consultant_contact_no = $phone;
        $x->consultant_email = $email;
        if($x->save())
        {
            return response()->json('Success !!!');
        }
        else
        {
            return response()->json('Error !!!');
        }
    }
    public function updateProcurement(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $phone = $request->phone;
        $email = $request->email;
        $project = ProcurementDetails::where('project_id',$id)->first();
        $point = 0;
        if($project != null){
            $point = $name != $project->procurement_name ? $point+3 : $point+0;
            $point = $phone != $project->procurement_contact_no ? $point+3 : $point+0;
            $point = $email != $project->procurement_email ? $point+3 : $point+0;
            $points = new Point;
            $points->user_id = Auth::user()->id;
            $points->point = $point;
            $points->type = "Add";
            $points->reason = "Updating procurement details";
            $points->save();
        }
        $x = ProcurementDetails::where('project_id',$id)->first();
        $x->procurement_name = $name;
        $x->procurement_contact_no = $phone;
        $x->procurement_email = $email;
        if($x->save())
        {
            return response()->json('Success !!!');
        }
        else
        {
            return response()->json('Error !!!');
        }
    }
    public function updateampay(Request $request)
    {
        $id = $request->id;
        $update = $request->payment;
        $x = Order::where('id', $id)->first();
        $x->payment_status = $update;
        if($x->save())
        {
            return response()->json($update);
        }
        else
        {
            return response()->json('Error');
        }
    }
    public function updateamdispatch(Request $request)
    {
        $id = $request->id;
        $x = Order::where('id', $id)->first();
        $x->dispatch_status = "Yes";
        $x->delivery_status = 'Not Delivered';
        if($x->save())
        {
            return response()->json("Updated");
        }
        else
        {
            return response()->json('Error');
        }
    }
    public function deliverOrder(Request $request)
    {
        $id = $request->id;
        $today = date('Y-m-d');
        $x = Order::where('id', $id)->first();
        $x->delivery_status = 'Delivered';
        $x->delivered_on = $today;
        if($x->save())
        {
            return response()->json("Updated");
        }
        else
        {
            return response()->json('Error');
        }
    }
    public function checkDupPhoneContractor(Request $request)
    {
        $arg = $request->only('arg');
        $check = ContractorDetails::where('contractor_contact_no',$arg)->get();
        $c = count($check);
        return response()->json($c);
    }
    public function checkDupPhoneOwner(Request $request)
    {
        $arg = $request->only('arg');
        $check = OwnerDetails::where('owner_contact_no',$arg)->get();
        $c = count($check);
        return response()->json($c);
    }
    public function checkDupPhoneProcurement(Request $request)
    {
        
        
        $check = ProcurementDetails::where('procurement_contact_no',$request->id)->get();
        $c = count($check);
       
        return response()->json($check);
    }
    public function checkmanu(request $request)
    {

        
        $arg = $request->id;
        $check = Mprocurement_Details::where('contact',$arg)->get();
        $c = count($check);
        
        return response()->json($check);
    }

    public function checkDupPhoneConsultant(Request $request)
    {
        $arg = $request->only('arg');
        $check = ConsultantDetails::where('consultant_contact_no',$arg)->get();
        $c = count($check);
        return response()->json($c);
    }
    public function checkDupPhoneSite(Request $request)
    {
        $arg = $request->only('arg');
        $check = SiteEngineerDetails::where('site_engineer_contact_no',$arg)->get();
        $c = count($check);
        return response()->json($c);
    }
    public function confirmstatus($id, Request $request)
    {
        $var2 = $request->only('opt');
        $var = ProjectDetails::where('project_id',$var2['opt'])->first();
        $var->status = "Ready";
        $var->save();
        $c = count($check);
        return response()->json($c);
    }
    public function confirmthis($id, Request $request)
    {
        $var = $request->only('opt');
        $var2 = ProjectDetails::where('project_id',$id)->first();
        $var2->with_cont = $var['opt'];
        $var2->save();
        return response()->json($var2);
    }
    public function updateNoteFollowUp(Request $request)
    {
        $id = $request->id;
        $value = $request->value;
        $x = ProjectDetails::where('project_id', $id)->first();
        $x->note = $value;
        if($x->save()){
            return response()->json('Successful !!');
        }
        else{
            return response()->json('Error !!');
        }
    }
    public function updateStatusReq(Request $request)
    {
        $id = $request->id;
        $x =DB::table('requirements')->where('id', $id)->first();
        $x->status = 'Order Initiated';
        if($x->save())
        {
            return response()->json('Successful !!!');
        }
        else
        {
            return response()->json('Error !!!');
        }
    }
    public function updatestatus($id, Request $request)
    {
        $view = $request->only('opt');
        $view = $view['opt'];
        $project = ProjectDetails::where('project_id', $id)->first();
        $project->project_status = $view;
        $project->save();
        return response()->json($view);
    }
    public function updateMat($id, Request $request)
    {
        $view = $request->only('opt');
        $view = $view['opt'];
        $project = ProjectDetails::where('project_id', $id)->first();
        $project->remarks = $view;
        $project->save();
        return response()->json($view);
    }
    public function updatelocation($id, Request $request)
    {
        $view = $request->only('newtext');
        $view = $view['newtext'];
        $address = SiteAddress::where('project_id', $id)->first();
        $address->address = $view;
        $address->save();
        return response()->json($view);
    }
    public function smstonumber(Request $request)
    {
        if(Auth::user()->group_id != 22){
            return $this->smstonumber1($request);
        }
        
        
        
        if(!$request->stage ){
            $stages =null;
        }else{
            $stages = $request->stage;
        }

        if($request->subward == "All"){

            $subward = Subward::where('ward_id',$request->ward)->pluck('id');
        }else{
            $subward = Subward::where('id',$request->subward)->pluck('id');
        }
        
       



        $tl= Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
        $tlward = explode(",",$tl);
        $subwards = Subward::whereIn('ward_id',$tlward)->pluck('id');
        $projectids = new Collection();
        $orders = Order::where('status','Order Confirmed')->pluck('project_id');
        if($stages != null){
            $projectids = ProjectDetails::whereIn('sub_ward_id',$subwards)
            ->whereIn('project_status',$stages)
            ->where('quality','!=','Fake')
             
            ->whereNotIn('project_id',$orders)
            ->pluck('project_id');
        }else{
            $projectids = null;
        }


        if($projectids != null){

           //fetch phonee numbers//
            $procurement =ProcurementDetails::whereIn('project_id',$projectids)->whereNotIn('project_id',$dd['project'])->where('procurement_contact_no','!=',null)->pluck('procurement_contact_no')->toarray();


            $siteeng =SiteEngineerDetails::whereIn('project_id',$projectids)->whereNotIn('project_id',$dd['project'])->where('site_engineer_contact_no','!=',null)->pluck('site_engineer_contact_no')->toarray();

            $contractor =ContractorDetails::whereIn('project_id',$projectids)->whereNotIn('project_id',$dd['project'])->where('contractor_contact_no','!=',null)->pluck('contractor_contact_no')->toarray();

            $consultant =ConsultantDetails::whereIn('project_id',$projectids)->whereNotIn('project_id',$dd['project'])->where('consultant_contact_no','!=',null)->pluck('consultant_contact_no')->toarray();

            $owner =OwnerDetails::whereIn('project_id',$projectids)->whereNotIn('project_id',$dd['project'])->where('owner_contact_no','!=',null)->pluck('owner_contact_no')->toarray();
            $builder =Builder::whereIn('project_id',$projectids)->whereNotIn('project_id',$dd['project'])->where('builder_contact_no','!=',null)->pluck('builder_contact_no')->toarray();


           $merge = array_merge($procurement,$siteeng, $contractor,$consultant,$owner,$builder);
                     $filtered = array_unique($merge);

           $unique = array_combine(range(1,count($filtered)), array_values($filtered));


           for($ss=1;$ss<count($unique);$ss++){
             DB::insert('insert into numbers (number) values(?)',[$unique[$ss] ]);

           }
           $count = count($unique);
        }else{
            return "NO Numbers assigned";
        }

       return back();
    }


   public function smstonumber1(Request $request)
    {
        $dd = ProjectDetails::getcustomer();

       
   
   $type = $request->type;   
    if($type != null)
    {
        $projectids = Manufacturer::whereIn('manufacturer_type',$type)
             
             ->pluck('id');
      


if($projectids != null){
           //fetch phonee numbers//
            $procurement =Manager_Deatils::whereIn('manu_id',$projectids)->where('contact','!=',null)->pluck('contact')->toarray();


            $siteeng =Salescontact_Details::whereIn('manu_id',$projectids)->where('contact','!=',null)->pluck('contact')->toarray();

            $contractor =Mprocurement_Details::whereIn('manu_id',$projectids)->where('contact','!=',null)->pluck('contact')->toarray();
            $consultant =Mowner_Deatils::whereIn('manu_id',$projectids)->where('contact','!=',null)->pluck('contact')->toarray();
            
           $merge = array_merge($procurement,$siteeng, $contractor,$consultant);
           $filtered = array_unique($merge);
                  $s = $dd['numbers']->toarray();
                  $fill = array_diff($filtered,$s);
           $unique = array_combine(range(1,count($fill)), array_values($fill));
           for($ss=1;$ss<count($unique);$ss++){
             DB::insert('insert into numbers (number) values(?)',[$unique[$ss] ]);

           }
           $count = count($unique);
        }else{
            return "NO Numbers assigned";
        }
   }
else{

if(!$request->stage ){
            $stages =null;
        }else{
            $stages = $request->stage;
        }

           if($request->subward == "All"){

            $subward = Subward::where('ward_id',$request->ward)->pluck('id');
        }else{
            $subward = Subward::where('id',$request->subward)->pluck('id');
        }
       

  $tlward = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();

         $projectids = new Collection();
         $orders = Order::where('status','Order Confirmed')->pluck('project_id');

         if($stages != null && !$request->subward){
             $projectids = ProjectDetails::
             whereIn('project_status',$stages)
             ->where('quality','!=','Fake')
              
             ->pluck('project_id');
         }else if($stages != null && $request->subward){
             
            $projectids = ProjectDetails::
             whereIn('project_status',$stages)
              ->whereIn('sub_ward_id',$subward)
             ->where('quality','!=','Fake')
              
             ->pluck('project_id');
            
         }else if($stages == null && $request->subward){

              $projectids = ProjectDetails::where('quality','!=','Fake')
              ->whereIn('sub_ward_id',$subward)
              
             ->pluck('project_id');
         }else{
            $projectids = null;
         }
    if($projectids != null){

           //fetch phonee numbers//
            $procurement =ProcurementDetails::whereIn('project_id',$projectids)->where('procurement_contact_no','!=',null)->pluck('procurement_contact_no')->toarray();

           

            $siteeng =SiteEngineerDetails::whereIn('project_id',$projectids)->where('site_engineer_contact_no','!=',null)->pluck('site_engineer_contact_no')->toarray();

            $contractor =ContractorDetails::whereIn('project_id',$projectids)->where('contractor_contact_no','!=',null)->pluck('contractor_contact_no')->toarray();

            $consultant =ConsultantDetails::whereIn('project_id',$projectids)->where('consultant_contact_no','!=',null)->pluck('consultant_contact_no')->toarray();

            $owner =OwnerDetails::whereIn('project_id',$projectids)->where('owner_contact_no','!=',null)->pluck('owner_contact_no')->toarray();
             $builder =Builder::whereIn('project_id',$projectids)->where('builder_contact_no','!=',null)->pluck('builder_contact_no')->toarray();
            
           $merge = array_merge($procurement,$siteeng, $contractor,$consultant,$owner,$builder);
           $filtered = array_unique($merge);

               $s = $dd['numbers']->toarray();
                  $fill = array_diff($filtered,$s);
           $unique = array_combine(range(1,count($fill)), array_values($fill));

            

           for($ss=1;$ss<count($unique);$ss++){
             DB::insert('insert into numbers (number) values(?)',[$unique[$ss] ]);

           }
           $count = count($unique);
        }else{
            return "NO Numbers assigned";
        }
}

       return back();
    }





     public function projectsUpdate(Request $request)
    {   
        Debugbar::startMeasure('render','Time for rendering');
         $date=date('Y-m-d');

        
         $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
//          

        $subwards = AssignStage::where('user_id',Auth::user()->id)->pluck('subward')->first();
        
        $subwardNames = explode(", ", $subwards);

         if($subwards != "null"){
            $subwardid = Subward::whereIn('sub_ward_name',$subwardNames)->pluck('id')->toArray();
         }else{
            $subwardid = "";
         }



         $roomtypes = RoomType::all();
       $projectOrdersReceived = Order::whereIn('status',["Order Confirmed","Order Cancelled"])->pluck('project_id');
        function multiexplode ($delimiters,$string) {
            
            $ready = str_replace($delimiters, $delimiters[0], $string);
            $launch = explode($delimiters[0], $ready);
            return  $launch;
        }
        
        $projecti = AssignStage::where('user_id',Auth::user()->id)->pluck('projectids')->first();
        
    $projectids = multiexplode(array(",","[","]"),$projecti);

    $projects = ProjectDetails::with('siteaddress','procurementdetails','upuser')
                    ->whereIn('project_id',$projectids)
                    ->orderBy('project_id','ASC')
                    ->paginate(10);
        

        



         $d = ProjectDetails::whereIn('project_id',$projectids)->pluck('construction_type');
         $cat = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
       
          if(Auth::user()->group_id == 23){
             if($request->update){
              $cat = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
                $spro = ProjectUpdate::where('user_id',Auth::user()->id)->where('cat_id',$cat)->pluck('project_id');
                $projects = ProjectDetails::whereIn('project_id',$spro)
                    ->select('project_details.*','project_id')
                    ->orderBy('project_id','ASC')
                     
                     ->with('siteaddress','procurementdetails','upuser')

                    ->paginate(15);
                    
            }elseif($request->update1){
                $date = date('Y-m-d', strtotime('-30 days'));
              $cat = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
                $spro = ProjectUpdate::where('user_id',Auth::user()->id)->where('cat_id',$cat)->pluck('project_id');
                $projects = ProjectDetails::whereIn('project_id',$spro)
                    ->select('project_details.*','project_id')
                    ->where('created_at','>=',$date)
                     
                     ->with('siteaddress','procurementdetails','upuser')

                    ->orderBy('project_id','ASC')
                    ->paginate(15);
                    
            }elseif($request->unupdate){
              $cat = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
               $ac = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
               $catsub = Category::where('id',$ac)->pluck('category_name')->first();
               $sprojectids = Requirement::where('main_category',$catsub)->pluck('project_id');
              $spro = ProjectUpdate::where('user_id',Auth::user()->id)->where('cat_id',$cat)->pluck('project_id');
                     
            $projects = ProjectDetails::whereIn('project_id',$sprojectids)
                         ->whereNotIn('project_id',$spro)
                         ->select('project_details.*','project_id')
                         ->orderBy('project_id','ASC')
                          
                     ->with('siteaddress','procurementdetails','upuser')
    
                         ->paginate(15);

            }elseif($request->unupdate1){
                $date = date('Y-m-d', strtotime('-30 days'));
              $cat = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
               $ac = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
               $catsub = Category::where('id',$ac)->pluck('category_name')->first();
               $sprojectids = Requirement::where('main_category',$catsub)->pluck('project_id');
              $spro = ProjectUpdate::where('user_id',Auth::user()->id)->where('cat_id',$cat)->pluck('project_id');
                     
            $projects = ProjectDetails::whereIn('project_id',$sprojectids)
                         ->whereNotIn('project_id',$spro)
                         ->select('project_details.*','project_id')
                         // ->where('created_at','>=',$date)
                     ->with('siteaddress','procurementdetails','upuser')
    
                         ->orderBy('project_id','ASC')
                         ->paginate(15);

            }elseif($request->interested){
                 $cat = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
                  $ac = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
                  $catsub = Category::where('id',$ac)->pluck('category_name')->first(); 
                  $cate = Salesofficer::where('category','LIKE',"%".$catsub."%")->pluck('project_id');
                   $projects = ProjectDetails::whereIn('project_id',$cate)
                    ->select('project_details.*','project_id')
                     ->with('siteaddress','procurementdetails','upuser')
                    ->orderBy('project_id','ASC')
                    ->paginate(15);
            }else{
          $ac = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
          $catsub = Category::where('id',$ac)->pluck('category_name')->first();
          $sprojectids = Requirement::where('main_category',$catsub)->pluck('project_id');
        
          $projects = ProjectDetails::whereIn('project_id',$sprojectids)
                    ->select('project_details.*','project_id')
                     ->with('siteaddress','procurementdetails','upuser')
                    ->orderBy('project_id','ASC')
                    ->paginate(15);
                 $projectcount = ProjectDetails::whereIn('project_id',$sprojectids)->count();                 
            }

             }
     $scount = ProjectDetails::whereIn('project_id',$projectids)->whereNotIn('project_id',$projectOrdersReceived)->count();
     //dd($scount);

        // $requirements = array();
        // foreach($projects as $project){
        //     $req = Requirement::where('project_id',$project->project_id)->pluck('id')->toArray();
        //     $pid = $project->project_id;
        //     array_push($requirements, [$pid,$req]);
        // }
        $his = History::all();
        // $assigncount = new  AssignStage();
        $assigncount = AssignStage::where('user_id',Auth::user()->id)->first();
        if($assigncount != null){
            $assigncount->count = $scount;
            $assigncount->save();
        }
        $category = Category::all();
        $sales = Salesofficer::all();
        $orders = Order::all();
        $projectupdat=ProjectUpdate::all(); 
        Debugbar::stopMeasure('render');
// Debugbar::addMeasure('now', LARAVEL_START, microtime(true));
       return view('salesengineer',[
                'projects'=>$projects,
                'roomtypes'=>$roomtypes,
                // 'requirements' =>$requirements,
                'subwardid' => $subwardid,
                'his'=>$his,
                'orders' => $orders,
                'log'=>$log,
                'log1'=>$log1,
                'category'=>$category,
                'sales'=>$sales,
                'projectupdat'=>$projectupdat,
              


            ]);
    }
 public function dailywiseProjects(Request $request){
        $today = date('Y-m-d');
        $date = date('Y-m-d',strtotime('-1 day',strtotime($today)));
        $projectCount = ProjectDetails::where('created_at','like',$date.'%')->count();
         $projects = DB::table('project_details')
            ->rightjoin('owner_details', 'project_details.project_id', '=', 'owner_details.project_id')
            ->rightjoin('sub_wards', 'project_details.sub_ward_id', '=', 'sub_wards.id')
            ->rightjoin('procurement_details', 'procurement_details.project_id', '=', 'project_details.project_id')
            ->rightjoin('users','users.id','=','project_details.listing_engineer_id')
            ->rightjoin('site_engineer_details','site_engineer_details.project_id','=','project_details.project_id')
            ->rightjoin('contractor_details','contractor_details.project_id','=','project_details.project_id')
            ->rightjoin('consultant_details','consultant_details.project_id','=','project_details.project_id')
            ->where('project_details.created_at','like',$date.'%')
            ->select('project_details.*', 'procurement_details.procurement_contact_no','contractor_details.contractor_contact_no','consultant_details.consultant_contact_no','site_engineer_details.site_engineer_contact_no', 'owner_details.owner_contact_no','users.name','sub_wards.sub_ward_name')
            ->paginate(4);
             return view('dailywiseProjects', ['date' => $date,'today'=>$today,'projects'=> $projects,'projectCount'=>$projectCount]);
    }
    public function dailyslots(Request $request)
    {

        $totalaccountlist = array();
        $totalListing = array();
        $totalRMCListing = array();
        $totalBlocksListing = array();
        $date = date('Y-m-d');
        $groupid = [6,7,22,23,17,11];
        $users = User::whereIn('group_id',$groupid)
                    ->where('department_id','!=',10)
                    ->leftjoin('ward_assignments','users.id','ward_assignments.user_id')
                    ->leftjoin('sub_wards','ward_assignments.subward_id','sub_wards.id')
                    ->select('users.*','sub_wards.sub_ward_name')
                    ->get();

         $accusers = User::where('department_id','2')->where('group_id','11')
                    ->where('department_id','!=',10)
                    ->leftjoin('ward_assignments','users.id','ward_assignments.user_id')
                    ->leftjoin('sub_wards','ward_assignments.subward_id','sub_wards.id')
                    ->select('users.*','sub_wards.sub_ward_name')
                    ->get();

        $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
        $userIds = explode(",", $tl);
        // total project of list in stl
        $tllistuser = DB::table('users')->where('group_id',6)->whereIn('id',$userIds)
        ->pluck('id');
        $tlcount = ProjectDetails::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$tllistuser)->count();
        $tlRMCcount = Manufacturer::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$tllistuser)->where('manufacturer_type',"RMC")->count();
        $tlBlocksCount = Manufacturer::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$tllistuser)->where('manufacturer_type',"Blocks")->count();

        $tlupcount = ProjectDetails::where('updated_at','like',$date.'%')->whereIn('updated_by',$tllistuser)->count();

        // total project of list in tl
        $tlaccuser = DB::table('users')->where('group_id',11)->whereIn('id',$userIds)
        ->pluck('id');
        $tlacount = ProjectDetails::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$tlaccuser)->count();
        $tlAcRMCcount = Manufacturer::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$tlaccuser)->where('manufacturer_type',"RMC")->count();
        $tlAcBlocksCount = Manufacturer::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$tlaccuser)->where('manufacturer_type',"Blocks")->count();
        $tlaupcount = ProjectDetails::where('updated_at','like',$date.'%')->whereIn('updated_by',$tlaccuser)->count();


        $tlUsers = User::whereIn('id',$userIds)
            ->where('group_id',6)->simplePaginate();

         $tlUsers1 = User::whereIn('id',$userIds)
           ->where('group_id',11)->simplePaginate();
        // total project of list in st
        $list = DB::table('users')->where('group_id',6)->where('department_id','!=',10)->pluck('id');
        $lcount = ProjectDetails::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$list)->count();
        $lupcount = ProjectDetails::where('updated_at','like',$date.'%')->whereIn('updated_by',$list)->count();
        $lRMCcount = Manufacturer::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$list)->where('manufacturer_type',"RMC")->count();
        $lBlocksCount = Manufacturer::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$list)->where('manufacturer_type',"Blocks")->count();
            // total prolect of account in st
        $account = DB::table('users')->where('group_id',11)->where('department_id','!=',10)->pluck('id');
        $acount = ProjectDetails::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$account)->count();
        $aRMCcount = Manufacturer::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$account)->where('manufacturer_type',"RMC")->count();
        $aBlocksCount = Manufacturer::where('created_at','like',$date.'%')->whereIn('listing_engineer_id',$account)->where('manufacturer_type',"Blocks")->count();
        $aupcount = ProjectDetails::where('updated_at','like',$date.'%')->whereIn('updated_by',$account)->count();
        $projects = ProjectDetails::where('created_at','like',$date.'%')->get();
        $groupid = [6,11,22,23,2,17,7];
        $le = DB::table('users')->whereIn('group_id',$groupid)->where('department_id','!=',10)->get();
        
        if(Auth::user()->group_id != 22){
              if($request->list =="ALL" && $request->fromdate && $request->todate){
                      $from =$request->fromdate;
                      $to = $request->todate;
                      if($from == $to){
                       $projects = ProjectDetails::where('created_at','like',$from.'%')
                             ->where('created_at','LIKE',$to."%")
                             ->get();
                                    }
                  else{
                  $projects = ProjectDetails::wheredate('created_at','>',$request->fromdate)
                              ->wheredate('created_at','<=',$request->todate)
                             ->get(); 
                  }
              }elseif($request->list !="ALL" && $request->fromdate && $request->todate){
                      $from =$request->fromdate;
                      $to = $request->todate;
                      if($from == $to){
                       $projects = ProjectDetails::where('created_at','like',$from.'%')
                             ->where('created_at','LIKE',$to."%")
                             ->where('listing_engineer_id',$request->list)
                             ->get();
                       
                                      }
                  else{
                      $projects = ProjectDetails::wheredate('created_at','>',$request->fromdate)
                              ->wheredate('created_at','<=',$request->todate)
                             ->where('listing_engineer_id',$request->list)
                             ->get();     
                  }
              }else{

                   $projects =ProjectDetails::where('created_at','like',$date.'%')
                  ->get();
              }




          
}else{
            $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
              $tll = explode(",",$tl);
            $ward = Ward::whereIn('id',$tll)->pluck('id');
            $sub  = Subward::whereIn('ward_id',$ward)->pluck('id');


               if($request->list =="ALL" && $request->fromdate && $request->todate){
                      $from =$request->fromdate;
                      $to = $request->todate;
                      if($from == $to){
                       $projects = ProjectDetails::where('created_at','like',$from.'%')
                             ->where('created_at','LIKE',$to."%")
                             ->whereIn('sub_ward_id',$sub)
                             ->get();
                       
                                    }
                  else{
                  $projects = ProjectDetails::wheredate('created_at','>',$request->fromdate)
                              ->wheredate('created_at','<=',$request->todate)
                             ->whereIn('sub_ward_id',$sub)
                             ->get(); 
                      }
                  }elseif($request->list !="ALL" && $request->fromdate && $request->todate){
                      $from =$request->fromdate;
                      $to = $request->todate;
                      if($from == $to){
                       $projects = ProjectDetails::where('created_at','like',$from.'%')
                             ->where('created_at','LIKE',$to."%")
                             ->where('listing_engineer_id',$request->list)
                             ->whereIn('sub_ward_id',$sub)
                             ->get();
                       
                                      }
                  else{
                      $projects = ProjectDetails::wheredate('created_at','>',$request->fromdate)
                              ->wheredate('created_at','<=',$request->todate)
                             ->where('listing_engineer_id',$request->list)
                             ->whereIn('sub_ward_id',$sub)
                             ->get();     
                  }
                 }
                 else{

                     $projects =ProjectDetails::whereIn('sub_ward_id',$sub)
                        ->where('created_at','like',$date.'%')->get();
                 }
              }
        
   foreach($users as $user){
                $totalListing[$user->id] = ProjectDetails::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->count();
                $totalRMCListing[$user->id] = Manufacturer::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->where('manufacturer_type',"RMC")
                                                ->count();
                $totalBlocksListing[$user->id] = Manufacturer::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->where('manufacturer_type',"Blocks")
                                                ->count();
            }
            foreach($tlUsers as $user){
                $totalListing[$user->id] = ProjectDetails::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->count();
                $totalRMCListing[$user->id] = Manufacturer::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->where('manufacturer_type',"RMC")
                                                ->count();
                $totalBlocksListing[$user->id] = Manufacturer::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->where('manufacturer_type',"Blocks")
                                                ->count();
            }
            foreach($accusers as $user){
                $totalaccountlist[$user->id] = ProjectDetails::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->count();
                $totalAccountRMCListing[$user->id] = Manufacturer::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->where('manufacturer_type',"RMC")
                                                ->count();
                $totalAccountBlocksListing[$user->id] = Manufacturer::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->where('manufacturer_type',"Blocks")
                                                ->count();
            }
             foreach($tlUsers1 as $user){
                $totalaccountlist[$user->id] = ProjectDetails::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->count();
                $totalAccountRMCListing[$user->id] = Manufacturer::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->where('manufacturer_type',"RMC")
                                                ->count();
                $totalAccountBlocksListing[$user->id] = Manufacturer::where('listing_engineer_id',$user->id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->where('manufacturer_type',"Blocks")
                                                ->count();
            }
            foreach($users as $user){
                 $totalupdates[$user->id] =  Activity::where('causer_id',$user->id)->where('description','updated')->where('subject_type','App\ProjectDetails')->where('created_at','>=', $date.'%')->count();
            }
            foreach($tlUsers as $user){
               $totalupdates[$user->id] =  Activity::where('causer_id',$user->id)->where('description','updated')->where('subject_type','App\ProjectDetails')->where('created_at','>=', $date.'%')->count();
            }

            foreach($accusers as $user){
                $totalaccupdates[$user->id] =  Activity::where('causer_id',$user->id)->where('description','updated')->where('subject_type','App\ProjectDetails')->where('created_at','>=', $date.'%')->count();
            }
            foreach($tlUsers1 as $user){
               $totalaccupdates[$user->id] =  Activity::where('causer_id',$user->id)->where('description','updated')->where('subject_type','App\ProjectDetails')->where('created_at','>=', $date.'%')->count();
            }
            
        $projcount = count($projects); 
        // if(Auth::user()->group_id == 22){
        //     $teamprojcount = count($teamprojects);
        // }
        // else{
        //     $teamprojcount = 0;
        // }
        return view('dailyslots', ['date' => $date,'users'=>$users,'accusers'=>$accusers, 'projcount' => $projcount, 'projects' => $projects, 'le' => $le, 'totalListing'=>$totalListing,'totalaccountlist'=>$totalaccountlist,'tlUsers'=>$tlUsers,'tlUsers1'=>$tlUsers1,'lcount'=>$lcount,'acount'=>$acount,'lupcount'=>$lupcount,'aupcount'=>$aupcount,'tlcount'=>$tlcount,'tlupcount'=>$tlupcount,'tlacount'=>$tlacount,'tlaupcount'=>$tlaupcount,
           
          
        ]);
    }

    // public function getleinfo(Request $request)
    // {
    //     $records = array();
    //     $id = $request->id;
    //     $from = $request->from;
    //     $to = $request->to;
    //     if($from == $to){
    //         return redirect('/gettodayleinfo?from='.$from.'&id='.$id.'&to='.$to);
    //     }
    //     if($id !== 'ALL')
    //     {
    //            if(Auth::user()->group_id != 22){
    //             $records[0] =ProjectDetails::where('project_details.created_at','>',$from)
    //             ->where('project_details.created_at','<',$to)
    //             ->where('listing_engineer_id',$id)
                
    //             ->get();
    //     $records[1] = count($records[0]);
    //   }else{
    //     $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
    //     $ward = Ward::where('id',$tl)->pluck('id')->first();
    //     $sub  = Subward::where('ward_id',$ward)->pluck('id');
    //     $records[0] =ProjectDetails::where('created_at','>',$from)
    //     ->where('created_at','<',$to)
    //     ->where('listing_engineer_id',$id)
    //     ->get();
    //   $records[1] = count($records[0]);
    //   }

    //     }
    //     else
    //     {
    //        if(Auth::user()->group_id != 22){
    //         $records[0] =ProjectDetails::where('created_at','>',$from)
    //         ->where('created_at','<',$to)
    //         ->get();
    //         $records[1] = count($records[0]);
    //       }else{
    //         $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
    //         $ward = Ward::where('id',$tl)->pluck('id')->first();
    //         $sub  = Subward::where('ward_id',$ward)->pluck('id');
    //         $records[0] =ProjectDetails::where('created_at','>',$from)
    //                      ->where('created_at','<',$to)
    //                     ->get();
    //         $records[1] = count($records[0]);
    //       }
    //     }
    //     return response()->json($records);
    // }
    // public function gettodayleinfo(Request $request)
    // {
    //     $records = array();
    //     $id = $request->id;
    //     $from = $request->from_date;
    //     $to = date('Y-m-d', strtotime($request->to));
    //     if($id !== 'ALL')
    //     {
    //             if(Auth::user()->group_id != 22){
    //     $records[0] = ProjectDetails::where('created_at','LIKE',$from."%")
    //             ->where('created_at','LIKE',$to."%")
    //             ->where('listing_engineer_id',$id)
    //             ->get();
    //     $records[1] = count($records[0]);
    //   }else{
    //     $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
    //     $ward = Ward::where('id',$tl)->pluck('id')->first();
    //     $sub  = Subward::where('ward_id',$ward)->pluck('id');

    //     $records[0] =ProjectDetails::where('created_at','LIKE',$from."%")
    //             ->where('created_at','LIKE',$to."%")
    //             ->where('listing_engineer_id',$id)
    //             ->get();
    //     $records[1] = count($records[0]);
    //   }
    //     }
    //     else
    //     {
    //          if(Auth::user()->group_id != 22){
    //         $records[0] =ProjectDetails::where('created_at','like',$from.'%')
    //             ->where('created_at','LIKE',$to."%")
    //             ->get();
    //     $records[1] = count($records[0]);
    //   }else{
    //     $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
    //     $ward = Ward::where('id',$tl)->pluck('id')->first();
    //     $sub  = Subward::where('ward_id',$ward)->pluck('id');
    //     $records[0] =ProjectDetails::where('created_at','like',$from.'%')
    //         ->where('created_at','LIKE',$to."%")
    //         ->get();
    // $records[1] = count($records[0]);
    //   }
    //     }
    //     return response()->json($records);
    // }
    public function regReq()
    {
        $requests = User::where('department_id', 100)->where('confirmation',0)->orderBy('created_at','DESC')->get();
        $reqcount = count($requests);
        return view('regreq',['requests'=>$requests,'reqcount'=>$reqcount]);
    }
    public function getHRPage(){
        $departments = Department::all();
        $groups = Group::all();
        $depts = array();

        foreach($departments as $department){
            $depts[$department->dept_name] = User::where('department_id',$department->id)->count();
        }
        $depts["FormerEmployees"] = User::where('department_id',10)->count();
        return view('humanresource',['departments'=>$departments,'groups'=>$groups,'page'=>"hr",'depts'=>$depts]);
    }
    public function getHRDept($dept, Request $request){

        if($dept == "Formeremployee"){
            $users = User::where('department_id',10)->leftjoin('employee_details','users.employeeId','=','employee_details.employee_id')
                        ->select('users.*','employee_details.office_phone')
                        ->get();
        }else{
            $deptId = Department::where('dept_name',$dept)->pluck('id')->first();
            $users = User::where('department_id',$deptId)
                        ->leftjoin('employee_details','users.employeeId','=','employee_details.employee_id')
                        ->select('users.*','employee_details.office_phone')
                        ->get();
        }
        return view('hremp',['users'=>$users,'dept'=>$dept, 'page'=>$request->page]);
    }
    public function getFinance()
    {
        $departments = Department::all();
        return view('finance',['departments'=>$departments]);
    }
    public function getEmpDetails($dept, Request $request)
    {
        $deptId = Department::where('dept_name',$dept)->pluck('id')->first();
        $users = User::where('department_id',$deptId)->get();
        if($dept == 'Operation'){
            if($request->from){
                return $request->from;
            }
            $start_date = date("Y-m-d", strtotime("-1 week"));
            $end_date = date("Y-m-d");
            // $this->db->where("store_date >= '" . $start_date . "' AND store_date <= '" . $end_date . "'");

            $previous_week = strtotime("-1 week +1 day");

            $start_week = strtotime("last sunday midnight",$previous_week);
            $end_week = strtotime("next saturday",$start_week);

            $start_week = date("Y-m-d",$start_week);
            $end_week = date("Y-m-d",$end_week);

            $expenses = loginTime::where('logindate','>=',$start_date)->where('logindate','<',$end_date)->get();
            echo "Last week (".$start_date." to ".$end_date.") expenses.";
        }else{
            $expenses = "Null";
        }
        return view('empdetails',['users'=>$users,'dept'=>$dept,'expenses'=>$expenses]);
    }
    public function getProjectSize(Request $request)
    {

       
         $wards = Ward::all();
         
        
        $qualityCheck = ['Genuine','Unverified'];

        // getting total no of projects
        $wardsselect = Ward::pluck('id');
        $subwards = SubWard::whereIn('ward_id',$wardsselect)->pluck('id');
        $planningCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Planning%')->count();
        $planningSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Planning%')->sum('project_size');
        $foundationCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Foundation%')->count();
        $foundationSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Foundation%')->sum('project_size');
        $roofingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Roofing%')->count();
        $roofingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Roofing%')->sum('project_size');
        $wallsCount         = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Walls%')->count();
        $wallsSize          = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Walls%')->sum('project_size');
        $completionCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Completion%')->count();
        $completionSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Completion%')->sum('project_size');
        $fixturesCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Fixtures%')->count();
        $fixturesSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Fixtures%')->sum('project_size');
        $pillarsCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Pillars%')->count();
        $pillarsSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Pillars%')->sum('project_size');
        $paintingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Paintings%')->count();
        $paintingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Paintings%')->sum('project_size');
        $flooringCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Flooring%')->count();
        $flooringSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Flooring%')->sum('project_size');
        $plasteringCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Plastering%')->count();
        $plasteringSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Plastering%')->sum('project_size');
        $diggingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Digging%')->count();
        $diggingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Digging%')->sum('project_size');
        $ele                = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Electrical%')->pluck('project_id');
        $plum               = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
        $ele                = $ele->merge($plum);

        $enpCount           = ProjectDetails::whereIn('project_id',$ele)->count();
        $enpSize            = ProjectDetails::where('project_id',$ele)->sum('project_size');
        $carpentryCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Carpentry%')->count();
        $carpentrySize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Carpentry%')->sum('project_size');
        $closedCount        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Closed%')->count();
        $closedSize         = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Closed%')->sum('project_size');

        $totalProjects = $planningCount + $diggingCount + $foundationCount + $pillarsCount + $completionCount + $fixturesCount + $paintingCount + $carpentryCount + $flooringCount + $plasteringCount + $enpCount + $roofingCount + $wallsCount;
        
        $ward_Data =[];
        if($request->ward && !$request->subward){
            if($request->ward == "All"){
                   $ward_Data = [];
                   foreach($wards as $ward){
                    $subcount = Subward::where('ward_id',$ward->id)->get();
                    $subwardproject = [];
                    $subwardmanu = [];
                    $subwardbuilder=[];
                    $subwardlabour=[];
                    $subwardmaterial=[];
                    foreach($subcount as $subward)
                    {
                        $projects = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','NOT LIKE',  'Closed%')->where('sub_ward_id',$subward->id)->count();
                        $manufacturers = Manufacturer::whereIn('quality',$qualityCheck)->where('sub_ward_id',$subward->id)->count();
                        $builders= ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','NOT LIKE','Closed%')->where('sub_ward_id',$subward->id)->pluck('project_id');
                        
                        $bulilders_count = Builder::whereIn('project_id',$builders)->where('builder_contact_no',"!=",NULL)->count();
                        $labour_contract_count= ProjectDetails::whereIn('project_id',$builders)->where('contract',"=","Labour Contract")->count();
                        $Material_contract_count= ProjectDetails::whereIn('project_id',$builders)->where('contract',"=","Material Contract")->count();
                     
                        array_push($subwardproject,$projects);
                        array_push($subwardmanu,$manufacturers);
                        array_push($subwardbuilder,$bulilders_count);
                        array_push($subwardlabour,$labour_contract_count);
                        array_push($subwardmaterial,$Material_contract_count);
                    }
                    array_push($ward_Data,
                    ["ward"=>count($subcount),
                    'wardname'=>$ward->ward_name,
                    'wardproject'=>array_sum($subwardproject),
                    'wardmanu'=>array_sum($subwardmanu),
                    'wardbuilder'=>array_sum($subwardbuilder),
                    'wardlabour'=>array_sum($subwardlabour),
                    'wardmaterial'=>array_sum($subwardmaterial),
                    ]
                    
                );
                }

             
               
                $planningCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->count();
                $planningSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->sum('project_size');
                $foundationCount    = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->count();
                $foundationSize     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->sum('project_size');
                $roofingCount       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->count();
                $roofingSize        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->sum('project_size');
                $wallsCount         = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->count();
                $wallsSize          = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->sum('project_size');
                $completionCount    = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->count();
                $completionSize     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->sum('project_size');
                $fixturesCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->count();
                $fixturesSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->sum('project_size');
                $pillarsCount       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->count();
                $pillarsSize        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->sum('project_size');
                $paintingCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->count();
                $paintingSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->sum('project_size');
                $flooringCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->count();
                $flooringSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->sum('project_size');
                $plasteringCount    = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->count();
                $plasteringSize     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->sum('project_size');
                $diggingCount       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->count();
                $diggingSize        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->sum('project_size');
                $ele                = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->pluck('project_id');
                $plum               = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
                $ele                = $ele->merge($plum);
                $enpCount           = ProjectDetails::whereIn('project_id',$ele)->count();
                $enpSize            = ProjectDetails::whereIn('project_id',$ele)->sum('project_size');
                $carpentryCount     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->count();
                $carpentrySize      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->sum('project_size');
                $closedCount        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->count();
                $closedSize         = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->sum('project_size');
                $wardname = "All";
                $subwards = SubWard::all();
            }else
           
            {
                $ward_Data =[]; 
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
                $planningCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->count();
                $planningSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->sum('project_size');
                $foundationCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->count();
                $foundationSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->sum('project_size');
                $roofingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->count();
                $roofingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->sum('project_size');
                $wallsCount         = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->count();
                $wallsSize          = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->sum('project_size');
                $completionCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->count();
                $completionSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->sum('project_size');
                $fixturesCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->count();
                $fixturesSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->sum('project_size');
                $pillarsCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->count();
                $pillarsSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->sum('project_size');
                $paintingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->count();
                $paintingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->sum('project_size');
                $flooringCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->count();
                $flooringSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->sum('project_size');
                $plasteringCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->count();
                $plasteringSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->sum('project_size');
                $diggingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->count();
                $diggingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->sum('project_size');
                $ele                = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->pluck('project_id');
                $plum               = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
                $ele                = $ele->merge($plum);
                $enpCount           = ProjectDetails::whereIn('project_id',$ele)->count();
                $enpSize            = ProjectDetails::whereIn('project_id',$ele)->sum('project_size');

                $carpentryCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->count();
                $carpentrySize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->sum('project_size');
                $closedCount        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->count();
                $closedSize         = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->sum('project_size');
                $wardname = Ward::where('id',$request->ward)->first();
                $subwards = SubWard::where('ward_id',$request->ward)->get();



            }
           
            return view('projectSize',[
                'planningCount'=>$planningCount,'planningSize'=>$planningSize,
                'ward_Data'=>$ward_Data,
                'foundationCount'=>$foundationCount,'foundationSize'=>$foundationSize,
                'roofingCount'=>$roofingCount,'roofingSize'=>$roofingSize,
                'wallsCount'=>$wallsCount,'wallsSize'=>$wallsSize,
                'completionCount'=>$completionCount,'completionSize'=>$completionSize,
                'fixturesCount'=>$fixturesCount,'fixturesSize'=>$fixturesSize,
                'pillarsCount'=>$pillarsCount,'pillarsSize'=>$pillarsSize,
                'paintingCount'=>$paintingCount,'paintingSize'=>$paintingSize,
                'flooringCount'=>$flooringCount,'flooringSize'=>$flooringSize,
                'plasteringCount'=>$plasteringCount,'plasteringSize'=>$plasteringSize,
                'diggingCount'=>$diggingCount,'diggingSize'=>$diggingSize,
                'enpCount'=>$enpCount,'enpSize'=>$enpSize,
                'carpentryCount'=>$carpentryCount,'carpentrySize'=>$carpentrySize,
                'closedSize'=>$closedSize,'closedCount'=>$closedCount,
                'wards'=>$wards,
                'wardname'=>$wardname,
                'subwards'=>$subwards,'wardId'=>$request->ward,'planning'=>NULL,'subwardId'=>NULL,'subwardName'=>NULL,'totalProjects' => $totalProjects
            ]);
        }
        
        if($request->subward){
            $ward_Data =[];
            $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            $subwardQuality = ['Genuine','Fake','Unverified'];
            $planningCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->count();
            $planningSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->sum('project_size');
            $foundationCount   = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->count();
            $foundationSize    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->sum('project_size');
            $roofingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->count();
            $roofingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->sum('project_size');
            $wallsCount        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->count();
            $wallsSize         = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->sum('project_size');
            $completionCount   = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->count();
            $completionSize    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->sum('project_size');
            $fixturesCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->count();
            $fixturesSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->sum('project_size');
            $pillarsCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->count();
            $pillarsSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->sum('project_size');
            $paintingCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->count();
            $paintingSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->sum('project_size');
            $flooringCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->count();
            $flooringSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->sum('project_size');
            $plasteringCount   = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->count();
            $plasteringSize    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->sum('project_size');
            $diggingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->count();
            $diggingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->sum('project_size');
            $ele               = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->pluck('project_id');
            $plum              = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
            $ele               = $ele->merge($plum);
            $enpCount          = ProjectDetails::whereIn('project_id',$ele)->count();
            $enpSize           = ProjectDetails::whereIn('project_id',$ele)->sum('project_size');
            $carpentryCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->count();
            $carpentrySize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->sum('project_size');
            $closedCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->count();
            $closedSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->sum('project_size');

            $wardname = Ward::where('id',$request->ward)->first();
            $subwards = SubWard::where('ward_id',$request->ward)->get();
            $total = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->count();
            $planning   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Planning%')->sum('project_size');
            $foundation = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Foundation%')->sum('project_size');
            $roofing    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Roofing%')->sum('project_size');
            $walls      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Walls%')->sum('project_size');
            $completion = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Completion%')->sum('project_size');
            $fixtures   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Fixtures%')->sum('project_size');
            $pillars    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Pillars%')->sum('project_size');
            $painting   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Paintings%')->sum('project_size');
            $flooring   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Flooring%')->sum('project_size');
            $plastering = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Plastering%')->sum('project_size');
            $digging    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Digging%')->sum('project_size');
            $ele2        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->where('sub_ward_id',$request->subward)->pluck('project_id');
            $plum2       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->where('sub_ward_id',$request->subward)->pluck('project_id');
            $ele2        = $ele2->merge($plum2);
            $enp    = ProjectDetails::whereIn('project_id',$ele2)->sum('project_size');
            $carpentry  = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Carpentry%')->sum('project_size');
            $closed     = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Closed%')->sum('project_size');

            $Cplanning      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Planning%')->count();
            $Cfoundation    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Foundation%')->count();
            $Croofing       = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Roofing%')->count();
            $Cwalls         = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Walls%')->count();
            $Ccompletion    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Completion%')->count();
            $Cfixtures      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Fixtures%')->count();
            $Cpillars       = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Pillars%')->count();
            $Cpainting      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Paintings%')->count();
            $Cflooring      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Flooring%')->count();
            $Cplastering    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Plastering%')->count();
            $Cdigging       = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Digging%')->count();
            $Cenp   = ProjectDetails::whereIn('project_id',$ele2)->count();
            // $Cenp           = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Electrical%')
            //                     ->orWhere('project_status','LIKE','Plumbing%')->count();
            $Ccarpentry     = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Carpentry%')->count();
            $Cclosed        = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Closed%')->count();

            $subwardname = SubWard::where('id',$request->subward)->pluck('sub_ward_name')->first();
            $totalsubward = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->sum('project_size');
            return view('projectSize',[
                'ward_Data'=>$ward_Data,
                'planningCount'=>$planningCount,'planningSize'=>$planningSize,
                'foundationCount'=>$foundationCount,'foundationSize'=>$foundationSize,
                'roofingCount'=>$roofingCount,'roofingSize'=>$roofingSize,
                'wallsCount'=>$wallsCount,'wallsSize'=>$wallsSize,
                'completionCount'=>$completionCount,'completionSize'=>$completionSize,
                'fixturesCount'=>$fixturesCount,'fixturesSize'=>$fixturesSize,
                'pillarsCount'=>$pillarsCount,'pillarsSize'=>$pillarsSize,
                'paintingCount'=>$paintingCount,'paintingSize'=>$paintingSize,
                'flooringCount'=>$flooringCount,'flooringSize'=>$flooringSize,
                'plasteringCount'=>$plasteringCount,'plasteringSize'=>$plasteringSize,
                'diggingCount'=>$diggingCount,'diggingSize'=>$diggingSize,
                'enpCount'=>$enpCount,'enpSize'=>$enpSize,
                'carpentryCount'=>$carpentryCount,'carpentrySize'=>$carpentrySize,
                'closedSize'=>$closedSize,'closedCount'=>$closedCount,
                'wards'=>$wards,'wardname'=>$wardname,
                'subwards'=>$subwards,'wardId'=>$request->ward,
                'totalProjects' => $totalProjects,
                'planning'=>$planning,
                'foundation'=>$foundation,
                'roofing'=>$roofing,
                'walls'=>$walls,
                'completion'=>$completion,
                'fixtures'=>$fixtures,
                'pillars'=>$pillars,
                'painting'=>$painting,
                'flooring'=>$flooring,
                'plastering'=>$plastering,
                'digging'=>$digging,
                'enp'=>$enp,
                'carpentry'=>$carpentry,
                'Cplanning'=>$Cplanning,
                'Cfoundation'=>$Cfoundation,
                'Croofing'=>$Croofing,
                'Cwalls'=>$Cwalls,
                'Ccompletion'=>$Ccompletion,
                'Cfixtures'=>$Cfixtures,
                'Cpillars'=>$Cpillars,
                'Cpainting'=>$Cpainting,
                'Cflooring'=>$Cflooring,
                'Cplastering'=>$Cplastering,
                'Cdigging'=>$Cdigging,
                'Cenp'=>$Cenp,
                'Ccarpentry'=>$Ccarpentry,
                'closed'=>$closed,
                'Cclosed'=>$Cclosed,
                'subwardId'=>$request->subward,
                'subwardName'=>$subwardname,
                'total'=>$total,
                'totalsubward'=>$totalsubward
            ]);
        }

       
        
        return view('projectSize',['wards'=>$wards,'planningCount'=>NULL,'subwards'=>NULL,'wardId'=>NULL,'planning'=>NULL,'subwardId'=>NULL,'subwardName'=>NULL,'totalProjects' => $totalProjects,'ward_Data'=>$ward_Data]);
    }
    public function getLEDetails($userid){
        $projectDetails = ProjectDetails::where('listing_engineer_id',$userid)->get();
        return view('ledetails',['projectDetails'=>$projectDetails]);
    }
    public function changePassword(){
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        return view('changepassword',['log'=>$log,'$log1'=>$log1]);
    }
    public function hrAttendance($id, Request $request){
        
        if($request->month != null){
            $today = $request->year."-".$request->month;
        }else{
            $today = date('Y-m');
        }
        $user = User::where('employeeId',$id)->first();
          
        $attendances = FieldLogin::where('user_id',$user->id)->where('logindate','like',$today.'%')->orderby('logindate')->leftjoin('users','field_login.user_id','users.id')->get();  
       
        return view('empattendance',['attendances'=>$attendances,'user'=>$user]);
    }
    public function forgotPw(){
        return view('forgotpassword');
    }
    public function updateSalesAssignment(){
        salesassignment::where('user_id',Auth::user()->id)->delete();
        return redirect('/home');
    }
    public function updateAdminAssignment(){
         
        salesassignment::where('user_id',Auth::user()->id)->delete();
        return redirect('/leDashboard');
    }
    public function viewDailyReport($uId, $date){

        
        $reports = Report::where('empId',$uId)->where('created_at','like',$date.'%')->get();
        $grades = GradeRange::all();
        $user = User::where('employeeId',$uId)->first();
        // $attendance = attendance::where('empId',$uId)->where('date',$date)->first();
        $attendance = FieldLogin::where('user_id',$user->id)->where('logindate',$date)->first();
        $points_earned_so_far = Point::where('user_id',$user->id)->where('confirmation',1)->where('created_at','LIKE',$date."%")->where('type','Add')->sum('point');
        $points_subtracted = Point::where('user_id',$user->id)->where('confirmation',1)->where('created_at','LIKE',$date."%")->where('type','Subtract')->sum('point');
        $points_indetail = Point::where('user_id',$user->id)->where('confirmation',1)->where('created_at','LIKE',$date."%")->get();
        $total = $points_earned_so_far - $points_subtracted;
        $alltotal = Point::where('user_id',$user->id)->where('created_at','LIKE',$date."%")->where('type',"Add")->sum('point');
        $alltotal1 = Point::where('user_id',$user->id)->where('created_at','LIKE',$date."%")->where('type',"Subtract")->sum('point');
        $grandtotal = $alltotal -  $alltotal1;
       
        $grade = GradeRange::where('from_range','<',$grandtotal)->where('to_range','>',$grandtotal)->pluck('grade')->first();
       
        return view('viewdailyreport',[
            'reports'=>$reports,
            'date'=>$date,
            'user'=>$user,
            'attendance'=>$attendance,
            'points_indetail'=>$points_indetail,
            'points_earned_so_far'=>$points_earned_so_far,
            'points_subtracted'=>$points_subtracted,
            'total'=>$total,'grades'=>$grades,'grade'=>$grade
        ]);
    }
    public function followup(Request $request){
        $today = date('Y-m-d');
        $from = $request->from;
        $to = $request->to;
        if($request->from && $request->to)
                      {
                            $from = $request->from;
                            $to = $request->to;
                            if($from == $to)
                             {
                                    $projects = ProjectDetails::
                                    where('follow_up_by',Auth::user()->id)
                                    ->where('follow_up_date','>=',$from)
                                    ->where('follow_up_date','<=',$to)
                                     ->paginate(10);
                             }
                             else{

                                    $projects = ProjectDetails::
                                    where('follow_up_by',Auth::user()->id)
                                    ->where('follow_up_date','>=',$from)
                                    ->where('follow_up_date','<=',$to)
                                     ->paginate(10);


                             }


                      }
        else{
        $projects = ProjectDetails::where('followup',"yes")->where('follow_up_by',Auth::user()->id)->get();
        }
        return view('followup',['projects'=>$projects]);
    }
    public function confirmedProject(Request $request){

           ProjectDetails::where('project_id',$request->id)->increment('confirmed');

           $call  = date('Y-m-d H:i:s');
         
         
           $check = Activity::where('subject_id',$request->id)->where('created_at','like',$call."%")->where('subject_type','App\ProjectDetails')->where('description','updated')->where('called',"null")->first();
           $project_id = ProjectDetails::where('project_id',$request->id)->pluck('project_id')->first();
           $user_id = User::where('id',Auth::user()->id)->pluck('id')->first();
           $username = User::where('name',Auth::user()->name)->pluck('name')->first();
         

   $x= DB::insert('insert into history (user_id,project_id,called_Time,username) values(?,?,?,?)',[$user_id,$project_id,$call,$username]);
       if($check == null ){
            Activity::where('subject_id',$request->id)->where('causer_id',Auth::user()->id)->where('subject_type','App\ProjectDetails')->update(['called'=>1]);
        }
        return back()->with('success','Called successfully !');
    }
public function confirmedvisit(Request $request){
       ProjectDetails::where('project_id',$request->id)->increment('deleted');
           $subward = ProjectDetails::where('project_id',$request->id)->pluck('sub_ward_id')->first();
           $quality = ProjectDetails::where('project_id',$request->id)->pluck('quality')->first();
           $user_id = User::where('id',Auth::user()->id)->pluck('id')->first();
           $cat = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
            $projectvisit = new ProjectUpdate;
            $projectvisit->project_id =$request->id;
            $projectvisit->user_id =$user_id;
            $projectvisit->location =$request->address;
            $projectvisit->lat =$request->latitude;
            $projectvisit->lag =$request->longitude;
            $projectvisit->sub_ward_id =$subward;
            $projectvisit->quality =$quality;
            $projectvisit->cat_id =$cat;
            $projectvisit->save();
  return redirect()->back();
    }
 public function confirmedmanufacture(Request $request){

           $check1 = Manufacturer::where('id',$request->id)->first();
           $project_id = Manufacturer::where('id',$request->id)->pluck('id')->first();
           $user_id = User::where('id',Auth::user()->id)->pluck('id')->first();
           $username = User::where('name',Auth::user()->name)->pluck('name')->first();
           $call  = date('Y-m-d H:i:s');
              
           $check = Activity::where('subject_id',$request->id)->where('created_at','like',$call."%")->where('subject_type','App\Manufacturer')->where('description','updated')->where('called',"null")->first();
         

    DB::insert('insert into history (user_id,manu_id,called_Time,username) values(?,?,?,?)',[$user_id,$project_id,$call,$username]);
if($check == null ){
            Activity::where('subject_id',$request->id)->where('causer_id',Auth::user()->id)->where('subject_type','App\Manufacturer')->update(['called'=>1]);
        }

        if($check1->confirmed == null || $check1->confirmed == "true" || $check1->confirmed == "false"){
            Manufacturer::where('id',$request->id)->update(['confirmed'=>1]);
        }else{
            Manufacturer::where('id',$request->id)

           ->increment('confirmed');
        }

        return back()->with('success','Called successfully !');
    }






    public function projectadmin(Request $id){


               
        $details = projectDetails::withTrashed()->where('project_id',$id->projectId)->first();
         $projectupdate = ProjectImage::where('project_id',$id->projectId)->pluck('created_at')->last();
       $check =projectDetails::where('project_id',Auth::user()->name)
                    ->orderby('created_at','DESC')->pluck('project_id')->first();
        $roomtypes = RoomType::where('project_id',$id->projectId)->get();
        $followupby = User::where('id',$details->follow_up_by)->first();
        $callAttendedBy = User::where('id',$details->call_attended_by)->first();
        $listedby = User::where('id',$details->listing_engineer_id)->first();
        $subward = SubWard::where('id',$details->sub_ward_id)->pluck('sub_ward_name')->first();
$Wards = [];
      $wards = Ward::all();
     foreach($wards as $user){
           
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
        return view('viewDailyProjects',[
                'details'=>$details,
                'roomtypes'=>$roomtypes,
                'followupby'=>$followupby,
                'callAttendedBy'=>$callAttendedBy,
                'listedby'=>$listedby,
                'subward'=>$subward,
                'projectupdate'=>$projectupdate,
                'check'=>$check,'ward'=>$d
            ]);
    }
    public function showProjectDetails(Request $request)
    {
        $id = $request->id;
        $rec = ProjectDetails::where('project_id',$id)->first();
         $projectupdate = ProjectImage::where('project_id',$id)->pluck('created_at')->last();
        $username = User::where('id',$rec->listing_engineer_id)->first();
        $callAttendedBy = User::where('id',$rec->call_attended_by)->first();
        $followupby = User::where('id',$rec->follow_up_by)->first();
        $roomtypes = RoomType::where('project_id',$id)->get();
        $listedby = User::where('id',$rec->listing_engineer_id)->first();
        $subward = SubWard::where('id',$rec->sub_ward_id)->pluck('sub_ward_name')->first();
 
     $Wards = [];
      $wards = Ward::all();
     foreach($wards as $user){
           
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


        return view('adminprojectdetails',[
                'rec' => $rec,
                'username'=>$username,
                'callAttendedBy'=>$callAttendedBy,
                'roomtypes'=>$roomtypes,
                'followupby'=>$followupby,
                'listedby'=>$listedby,
                'projectupdate'=>$projectupdate,
                'subward'=>$subward,
                'ward'=>$d
            ]);
    }
     public function projectadmin1(Request $id){
        $details = projectDetails::where('project_id',$id->projectId)->first();
        $roomtypes = RoomType::where('project_id',$id->projectId)->get();
        $followupby = User::where('id',$details->follow_up_by)->first();
        $callAttendedBy = User::where('id',$details->call_attended_by)->first();
        $listedby = User::where('id',$details->listing_engineer_id)->first();
        $subward = SubWard::where('id',$details->sub_ward_id)->pluck('sub_ward_name')->first();
        $Wards = [];
      $wards = Ward::all();
     foreach($wards as $user){
           
                $noOfwards = WardMap::where('ward_id',$user->id)->first()->toArray();
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
        return view('viewDailyProjects1',[
                'details'=>$details,
                'roomtypes'=>$roomtypes,
                'followupby'=>$followupby,
                'callAttendedBy'=>$callAttendedBy,
                'listedby'=>$listedby,
                'subward'=>$subward,'ward'=>$d
            ]);
    }
    public function editEmployee(Request $request){
        $user = User::where('employeeId', $request->UserId)->first();
        $employeeDetails = EmployeeDetails::where('employee_id',$request->UserId)->first();
        $bankDetails = BankDetails::where('employeeId',$request->UserId)->first();
        $assets = Asset::all();
        $assetInfos = AssetInfo::where('employeeId',$request->UserId)->get();
        $certificates = Certificate::where('employeeId',$request->UserId)->get();
        return view('editEmployee',['user'=>$user,'employeeDetails'=>$employeeDetails,'bankDetails'=>$bankDetails,'assets'=>$assets,'assetInfos'=>$assetInfos,'certificates'=>$certificates]);
    }
    public function acceptConfidentiality(Request $request){
        User::where('id',$request->UserId)->update(['confirmation'=>1]);
        return redirect('/home');
    }
    public function manufacturerDetails(){
        $mfdetails = ManufacturerDetail::all();
        $category = ManufacturerDetail::groupBy('category')->pluck('category');
        return view('manufacturerdetails',['mfdetails'=>$mfdetails,'category'=>$category]);
    }
    public function getKRA(){
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $kras = DB::table('key_results')
            ->join('departments', 'key_results.department_id', '=', 'departments.id')
            ->join('groups', 'key_results.group_id', '=', 'groups.id')
            ->select('key_results.*', 'departments.dept_name', 'groups.group_name')
            ->where('key_results.group_id','=',Auth::user()->group_id)
            ->where('key_results.department_id','=',Auth::user()->department_id)
            ->get();
        return view('kra',['kras'=>$kras,'log'=>$log,'log1'=>$log1]);
    }
    public function getMyProfile(){
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        return view('my_Profile',['log'=>$log,'log1'=>$log1]);
    }
    public function postMyProfile(Request $request){
        if($request->userid){
            $name =  User::where('employeeId',$request->userid)->pluck('name')->first();
            $name1 = explode(" ",$name);


             $image = $request->file('pp');
             $imageFileName =$name1[0]. time() . '.' . $image->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/profilePic/' . $imageFileName;
             $s3->put($filePath, file_get_contents($image), 'public');

           




            User::where('employeeId',$request->userid)->update(['profilepic'=>$imageFileName]);
            return back()->with('Success','Profile picture added successfully');
        }else{

             $imageName1 = $request->file('pp');
             $imageFileName =Auth::user()->name. time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/profilePic/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');



           
            User::where('id',Auth::user()->id)->update(['profilepic'=>$imageFileName]);
            return redirect('/home')->with('Success','Profile picture added successfully');
        }
    }
    public function getMhOrders(Request $request){
        $invoices = MhInvoice::leftJoin('requirements','mh_invoice.requirement_id','=','requirements.id')->get();
      $details =MhInvoice::where('project_id',$request->phNo)->orwhere('invoice_number',$request->phNo)->get();
        return view('mhOrders',['invoices'=>$invoices,'details'=>$details]);
    }
    public function getAnR(){
        $departments = Department::all();
        $groups = Group::all();
        return view('anr',['departments'=>$departments,'groups'=>$groups,'page'=>"anr"]);
    }
    public function getCheck(Request $request)
    {
        $lists = Checklist::all();
        return view('getCheck',['lists'=>$lists]);
    }
    public function trainingVideo(Request $request)
    {
        $titles = training::all();
        $depts = Department::all();
        $grps = Group::all();
        if(!$request->dept){
            return view('trainingVideo',['depts'=>$depts,'grps'=>$grps,'videos'=>"none",'titles'=>$titles]);
        }else{
            $videos = training::where('dept',$request->dept)
                        ->where('designation',$request->designation)
                        ->get();
            return view('trainingVideo',['depts'=>$depts,'grps'=>$grps,'videos'=>$videos,'titles'=>$titles]);
        }
    }
    public function uploadfile(Request $request){
        


             $files = $request->file('upload');
             $imageFileName = time() . '.' . $files->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/hrfiles/' . $imageFileName;
             $s3->put($filePath, file_get_contents($files), 'public');





        $list = New Checklist;
        $list->id=$request->id;
        $list->name= $request->name;
        $list->upload= $imageFileName;
        $list->company = $request->company;
        $list->save();
        return back()->with('success','Submited successfully !');

    }
     public function uploadvideo(Request $request){

     
        
            $vedio = $request->file('upload');
             $imageFileName = time() . '.' . $vedio->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/trainingvideo/' . $imageFileName;
             $s3->put($filePath, file_get_contents($vedio), 'public');






        $train = New training;
        $train->dept=$request->dept;
        $train->designation= $request->designation;
        $train->upload= $imageFileName;
        $train->remark= $request->remark;
        $train->save();
        return back();

    }
    public function deletelist(Request $id)
    {

        Checklist::where('id',$id->id)->delete();
        return back()->with('info','Deleted successfully !');
    }
    public function deleteentry(Request $id)
    {

        training::where('id',$id->id)->delete();
        return back();
    }


    public function getSalesStatistics(){
        $notProcessed = Requirement::where('status',"Not Processed")->count();
        $initiate = Requirement::where('status',"Order Initiated")->count();
        $confirmed = Requirement::where('status',"Order Confirmed")->count();
        $placed = Requirement::where('status',"Order Placed")->count();
        $cancelled = Requirement::where('status',"Order cancelled")->count();
        $genuine = ProjectDetails::where('quality',"GENUINE")->count();
        $fake = ProjectDetails::where('quality',"FAKE")->count();
        $notConfirmed = ProjectDetails::where('quality',null)->count();
        return view('salesstats',[
            'initiate'=>$initiate,
            'confirmed'=>$confirmed,
            'placed'=>$placed,
            'cancelled'=>$cancelled,
            'genuine'=>$genuine,'fake'=>$fake,'notConfirmed'=>$notConfirmed,
            'notProcessed'=>$notProcessed
            ]);
    }
    public function postapprove(Request $request)
    {
        User::where('id',$request->id)->update(['confirmation'=>2]);
        return back();
    }
    public function wardsForLe(Request $request)
    {
        $assignment = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
        $ward = SubWard::where('id',$assignment)->pluck('ward_id')->first();
        $subward = Subward::where('ward_id',$ward)->pluck('id');
        $projects = ProjectDetails::where('quality','Genuine')->where('project_status','Walls')->paginate(10);
        $projectscount = ProjectDetails::where('quality','Genuine')->count();
        return view('salesengineer',['projects'=>$projects,'subwards'=>$assignment,'projectscount'=>$projectscount,'links'=>$subward]);
    }
    public function activityLog()
    {
    
        $activities = ActivityLog::orderby('time','DESC')->get();
        return view('activitylog',['activities'=>$activities]);
    }
    public function eqpipeline(Request $request)
    {
         $f = ProjectDetails::getcustomer();
        $enqde = $f['enquiry']->toarray();
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
                $category = Category::all();
                $today = date('Y-m-d');
               if(!$request){
                    $pipelines = Requirement::where('requirements.generated_by',Auth::user()->id)
                                    ->whereNotIn('id',$$enqde) 
                                    ->leftjoin('procurement_details','requirements.project_id','procurement_details.project_id')
                                    ->leftjoin('manufacturers','manufacturers.id','requirements.manu_id')
                                    ->where('requirements.requirements.status','!=',"Enquiry Cancelled")
                                    ->select('requirements.*','procurement_details.procurement_contact_no','procurement_details.procurement_name','manufacturers.sub_ward_id','manufacturers.name as mname','manufacturers.product','manufacturers.contact_no','requirements.manu_id')
                                    ->get();


                 }

             elseif($request->eqpipeline == 'today'){

                 $pipelines = Requirement::where('requirements.generated_by',Auth::user()->id)
                  
                ->leftjoin('procurement_details','requirements.project_id','procurement_details.project_id')
                ->leftjoin('manufacturers','manufacturers.id','requirements.manu_id')
                ->where('requirements.status','!=',"Enquiry Cancelled" )
                ->where('requirements.created_at','LIKE',$today."%")
                ->whereNotIn('requirements.id',$enqde)
                ->select('requirements.*','procurement_details.procurement_contact_no','procurement_details.procurement_name','manufacturers.sub_ward_id','manufacturers.name','manufacturers.product','manufacturers.contact_no','requirements.manu_id','manufacturers.name as mname')
                ->get() ;


             }
             elseif($request->category)
             {

                $pipelines = Requirement::where('requirements.generated_by',Auth::user()->id)
                ->leftjoin('procurement_details','requirements.project_id','procurement_details.project_id')
                ->where('requirements.status','!=',"Enquiry Cancelled" )
                 ->whereNotIn('requirements.id',$enqde)
                ->where('requirements.main_category',$request->category)
                ->select('requirements.*','procurement_details.procurement_contact_no','procurement_details.procurement_name')
                ->get() ;
             }
             else
            {
                $pipelines = Requirement::where('requirements.generated_by',Auth::user()->id)
                ->where('requirements.status','!=',"Enquiry Cancelled")
                ->whereNotIn('requirements.id',$enqde)
                ->get() ;
            }

        $subwards2 = array();
        foreach($pipelines as $enquiry){

            $pId = ProjectDetails::where('project_id',$enquiry->project_id)->first();
            if(count($pId) != 0){
                $subwards2[$enquiry->project_id] = SubWard::where('id',$pId->sub_ward_id)->pluck('sub_ward_name')->first();
            }else{
                $subwards2[$enquiry->project_id] = "";
            }
        }
        $sub=Subward::all();
        $manu = Manufacturer::all();


        return view('eqpipeline',['pipelines'=>$pipelines,'manu'=>$manu,'sub'=>$sub,'subwards2'=>$subwards2,'category'=>$category,'log'=>$log,'log1'=>$log1]);
    }
    public function letraining(Request $request)
    {
        
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
        $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $depts = Department::all();
        $grps = Group::all();
        $users = User::all();
        $videos = training::where('dept',"1")
                        ->where('designation',"6")
                        ->get();
        foreach($videos as $video){
            if($video->viewed_by == "none"){
                $video->viewed_by = Auth::user()->id;
                $video->save();
            }else{
                $newList = $video->viewed_by.", ".Auth::user()->id;
                $video->viewed_by = $newList;
                $video->save();
            }
        }
        return view('letraining',['video'=>$videos,'depts'=>$depts,'grps'=>$grps,'users'=>$users,'log'=>$log,'$log1'=>$log1]);

    }
    public function setraining(Request $request)
        {
            $depts = Department::all();
            $grps = Group::all();
            $videos = training::where('dept',"2")
                            ->where('designation',"7")
                            ->get();
            foreach($videos as $video){
                if($video->viewed_by == "none"){
                    $video->viewed_by = Auth::user()->id;
                    $video->save();
                }else{
                    $newList = $video->viewed_by.", ".Auth::user()->id;
                    $video->viewed_by = $newList;
                    $video->save();
                }
            }
        return view('setraining',['video'=>$videos,'depts'=>$depts,'grps'=>$grps]);
    }
    public function asttraining(Request $request)
    {
        $depts = Department::all();
        $grps = Group::all();
        $videos = training::where('dept',"5")
                        ->where('designation',"4")
                        ->get();
        foreach($videos as $video){
            if($video->viewed_by == "none"){
                $video->viewed_by = Auth::user()->id;
                $video->save();
            }else{
                $newList = $video->viewed_by.", ".Auth::user()->id;
                $video->viewed_by = $newList;
                $video->save();
            }
        }
        return view('asttraining',['video'=>$videos,'depts'=>$depts,'grps'=>$grps]);
    }
    public function adtraining(Request $request)
    {
        $depts = Department::all();
        $grps = Group::all();
        $videos = training::where('dept',"5")
                        ->where('designation',"3")
                        ->get();
        foreach($videos as $video){
            if($video->viewed_by == "none"){
                $video->viewed_by = Auth::user()->id;
                $video->save();
            }else{
                $newList = $video->viewed_by.", ".Auth::user()->id;
                $video->viewed_by = $newList;
                $video->save();
            }
        }
        return view('adtraining',['video'=>$videos,'depts'=>$depts,'grps'=>$grps]);
    }
    public function tltraining(Request $request)
    {
        $depts = Department::all();
        $grps = Group::all();
        $videos = training::where('dept',"1")
                        ->where('designation',"2")
                        ->get();
        foreach($videos as $video){
            if($video->viewed_by == "none"){
                $video->viewed_by = Auth::user()->id;
                $video->save();
            }else{
                $newList = $video->viewed_by.", ".Auth::user()->id;
                $video->viewed_by = $newList;
                $video->save();
            }
        }
        return view('tltraining',['video'=>$videos,'depts'=>$depts,'grps'=>$grps]);
    }
    public function employeereports(Request $request)
    {
        $depts = [1,2,3,4,5,6];
        $users = User::whereIn('department_id',$depts)->where('name','NOT LIKE','%test%')->orderBy('department_id','ASC')->get();
        if($request->month){
            $year = $request->year;
            $month = ($request->month < 10 ? "0".$request->month : $request->month);
            $today = $year."-".$month;
            $text = "";
        }else{
            $today = date('Y-m');
            $text = "";
            $year = date('Y');
            $month = date('m');
        }
        $ofdays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        foreach($users as $user){
            $count = 0;
            $text .= "<tr><td>".$user->employeeId."</td><td>".$user->name."<br>(".($user->Group != null ? $user->Group->group_name: '').")</td>";
            for($i = 1;$i<=$ofdays;$i++){
                if($i < 10){
                    $date = $today."-0".$i;
                }else{
                    $date = $today."-".$i;
                }
                if($user->group_id == "6"){
                    $att = loginTime::where('user_id',$user->id)->where('logindate',$date)->first();
                    if($att == null){
                        $text .= "<td style='background-color:rgba(999,111,021,0.3); color:black;'>Leave</td>";
                    }else{
                        $text .= "<td style='background-color:green; color:white;'>".$att->loginTime."<br>".$att->logoutTime."</td>";
                        $count++;
                    }
                }else{
                    $att = attendance::where('empId',$user->employeeId)->where('date',$date)->first();
                    if($att == null){
                        $text .= "<td style='background-color:rgba(999,111,021,0.3); color:black;'>Leave</td>";
                    }else{
                        $text .= "<td style='background-color:green; color:white;'>".$att->inTIme."<br>".$att->outTime."</td>";
                        $count++;
                    }
                }
            }
            $text .= "<td>".$count."</td></tr>";
        }
        return view('employeereports',['text'=>$text]);
    }
    public function newemployeereports(Request $request)
    {

        $depts = [1,2,3,4,5,6];
        $users = User::whereIn('department_id',$depts)->where('name','NOT LIKE','%test%')->orderBy('department_id','ASC')->get();
        if($request->month){
            $year = $request->year;
            $month = ($request->month < 10 ? "0".$request->month : $request->month);
            $today = $year."-".$month;
            $text = "";
        }else{
            $today = date('Y-m');
            $text = "";
            $year = date('Y');
            $month = date('m');
        }
        $ofdays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        foreach($users as $user){
            $count = 0;
            $text .= "<tr><td>".$user->employeeId."</td><td>".$user->name."<br>(".($user->Group != null ? $user->Group->group_name: '').")</td>";
            for($i = 1;$i<=$ofdays;$i++){
                if($i < 10){
                    $date = $today."-0".$i;
                }else{
                    $date = $today."-".$i;
                    
                }
                    $att = FieldLogin::where('user_id',$user->id)->where('logindate',$date)->first();
                    if($att == null){
                        $text .= "<td style='background-color:rgba(999,111,021,0.3); color:black;'>Leave</td>";
                    }else{
                        $text .= "<td style='background-color:green; color:white;'>".$att->logintime."<br>".$att->logout."</td>";
                        $count++;
                    }
            }
            $text .= "<td>".$count."</td></tr>";
        }

       
        return view('newemployeereports',['text'=>$text]);
    }
    public function getAddress(Request $request)
    {
        $address = SiteAddress::where('project_id',$request->projectId)->first();
        return response()->json($address);
    }
    public function getmanuAddress(Request $request)
    {
        $address = Manufacturer::where('id',$request->projectId)->first();
        return response()->json($address);
    }
    public function getsupplier(Request $request)
    {
        $supplier = ManufacturerDetail::where('category',$request->name)->where('state',$request->state)->get();

        $array = [];
        $id = $request->x;
        array_push($array,['supplier'=>$supplier,'id'=>$id]);
        return response()->json($array);
    }
    public function viewallProjects(Request $request)
    {
        $details = array();
        $wards = Ward::all();
        $users = User::all();
        $ids = array();
        if($request->phNo )
        {
            $details[0] = ContractorDetails::where('contractor_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[1] = ProcurementDetails::where('procurement_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[2] = SiteEngineerDetails::where('site_engineer_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[3] = ConsultantDetails::where('consultant_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[4] = OwnerDetails::where('owner_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
             $details[5] = ProjectDetails::where('project_id',$request->phno)->orwhere('project_id',$request->phNo)->pluck('project_id');
            for($i = 0; $i < count($details); $i++){
                for($j = 0; $j<count($details[$i]); $j++){
                    array_push($ids, $details[$i][$j]);
                }
            }
            $projects = ProjectDetails::whereIn('project_details.project_id',$ids)
                             ->where('project_details.type',NULL)
                            ->leftjoin('users','users.id','=','project_details.listing_engineer_id')
                            ->leftjoin('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                            ->leftjoin('site_addresses','site_addresses.project_id','=','project_details.project_id')
                            ->select('project_details.*','users.name','sub_wards.sub_ward_name','site_addresses.address')
                            
                            ->get();
             $projectimages = ProjectImage::whereIn('project_id',$ids)->get();
            return view('viewallprojects',['projects'=>$projects,'wards'=>$wards,'users'=>$users,'projectimages'=>$projectimages]);
        }
        if($request->subward && $request->ward){
            $projects = ProjectDetails::where('project_details.sub_ward_id',$request->subward)
                            ->leftjoin('users','users.id','=','project_details.listing_engineer_id')
                            ->leftjoin('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                            ->leftjoin('site_addresses','site_addresses.project_id','=','project_details.project_id')
                            ->select('project_details.*','users.name','sub_wards.sub_ward_name','site_addresses.address')
                            
                            ->get();
            $projectimages = ProjectImage::whereIn('project_id',$ids)->get();
        }elseif(!$request->subward && $request->ward){
            if($request->ward == "All"){
            $projects = ProjectDetails::leftjoin('users','users.id','=','project_details.listing_engineer_id')
                            ->leftjoin('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                            ->leftjoin('site_addresses','site_addresses.project_id','=','project_details.project_id')
                            ->select('project_details.*','users.name','sub_wards.sub_ward_name','site_addresses.address')
                            ->get();
            $projectimages = ProjectImage::whereIn('project_id',$ids)->get();        
            }
            else{
                 $subwards = SubWard::where('ward_id',$request->ward)->get()->pluck('id');
            $projects = ProjectDetails::whereIn('project_details.sub_ward_id',$subwards)
                            ->leftjoin('users','users.id','=','project_details.listing_engineer_id')
                            ->leftjoin('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                            ->leftjoin('site_addresses','site_addresses.project_id','=','project_details.project_id')
                            ->select('project_details.*','users.name','sub_wards.sub_ward_name','site_addresses.address')
                            ->get();
            $projectimages = ProjectImage::whereIn('project_id',$ids)->get();                
            }
        }
        else{
            $projects = "None";
                       
        }
         $projectimages = ProjectImage::whereIn('project_id',$ids)->get();
        return view('viewallprojects',['projects'=>$projects,'wards'=>$wards,'users'=>$users,'projectimages'=>$projectimages]);
    }

 public function projectDetailsForTL(Request $request)
    {
 $assigned = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id');
     
      $details = array();
        $wards = Ward::all();
        $users = User::all();
        $ids = array();
        $tll= Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
        $tl = explode(",",$tll);
        $tl1= Tlwards::where('group_id','=',22)->get();
        $userid = Auth::user()->id;
        $found1 = null;
        foreach($tl1 as $searchWard){
            $usersId = explode(",",$searchWard->users);
            if(in_array($userid, $usersId)){
                $found1 = $searchWard->ward_id;
            }
        }
     $sub_ward = Subward::where('ward_id',$found1)->pluck('id');
        if($request->phNo){
            $details[0] = ContractorDetails::where('contractor_contact_no',$request->phNo )->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[1] = ProcurementDetails::where('procurement_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[2] = SiteEngineerDetails::where('site_engineer_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[3] = ConsultantDetails::where('consultant_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[4] = OwnerDetails::where('owner_contact_no',$request->phNo)->orwhere('project_id',$request->phNo)->pluck('project_id');
            $details[5] = ProjectDetails::where('project_id',$request->phno)->get();
            for($i = 0; $i < count($details); $i++){
                for($j = 0; $j<count($details[$i]); $j++){
                    array_push($ids, $details[$i][$j]);
                }

            }
        if(Auth::user()->group_id == 7 || Auth::user()->group_id == 17){
           
            $projects = ProjectDetails::withTrashed()->whereIn('project_details.project_id',$ids)
                            ->leftjoin('users','users.id','=','project_details.listing_engineer_id')
                             ->leftjoin('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                            ->leftjoin('wards','wards.id','sub_wards.ward_id')
                            ->where('wards.id',$found1)
                            ->where('project_details.type',NULL)
                            ->leftjoin('site_addresses','site_addresses.project_id','=','project_details.project_id')
                            ->select('project_details.*','users.name','sub_wards.sub_ward_name','site_addresses.address')->get();
                        


            }elseif(Auth::user()->group_id == 22){
                 $projects = ProjectDetails::withTrashed()->whereIn('project_details.project_id',$ids)
                            ->leftjoin('users','users.id','=','project_details.listing_engineer_id')
                            ->leftjoin('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                            ->leftjoin('wards','wards.id','sub_wards.ward_id')
                            ->whereIn('wards.id',$tl)
                            ->where('project_details.type',NULL)
                            ->leftjoin('site_addresses','site_addresses.project_id','=','project_details.project_id')
                            ->select('project_details.*','users.name','sub_wards.sub_ward_name','site_addresses.address')
                            
                            ->get();
            }elseif(Auth::user()->group_id == 6){
                    $dd = ProjectDetails::getcustomer();
                 $projects = ProjectDetails::whereIn('project_details.project_id',$ids)->whereNotIn('project_details.project_id',$dd['project'])
                            ->leftjoin('users','users.id','=','project_details.listing_engineer_id')
                             ->leftjoin('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                            ->leftjoin('wards','wards.id','sub_wards.ward_id')
                            ->where('sub_ward_id',$assigned)
                            ->where('project_details.type',NULL)                            
                            ->leftjoin('site_addresses','site_addresses.project_id','=','project_details.project_id')
                            ->select('project_details.*','users.name','sub_wards.sub_ward_name','site_addresses.address')->get();

            }
            else
            {
                $projects = ProjectDetails::withTrashed()->whereIn('project_details.project_id',$ids)
                            ->leftjoin('users','users.id','=','project_details.listing_engineer_id')
                            ->leftjoin('sub_wards','project_details.sub_ward_id','=','sub_wards.id')
                            ->leftjoin('site_addresses','site_addresses.project_id','=','project_details.project_id')
                            ->select('project_details.*','users.name','sub_wards.sub_ward_name','site_addresses.address')
                            ->get();
            }


       
            $projectdetails = ProjectDetails::withTrashed()->whereIn('project_id',$ids)->pluck('updated_by');
            $updater = User::whereIn('id',$projectdetails)->first();
            $projectimages = ProjectImage::whereIn('project_id',$ids)->get();
         
            return view('viewallprojects',['wards'=>$wards,'users'=>$users,'projects'=>$projects,'wards'=>$wards,'users'=>$users,'updater'=>$updater,'projectimages'=>$projectimages]);
        }else{
           
        $projectimages = ProjectImage::whereIn('project_id',$ids)->get();
            return view('viewallprojects',['wards'=>$wards,'users'=>$users,'projects'=>"None",'projectimages'=>$projectimages]);
        }
    }

    public function deleteRoomType(Request $request)
    {
        RoomType::findOrFail($request->roomId)->delete();
        return back()->with('Success','Room type deleted');
    }
    public function salesreports(Request $request)
       {
                     if(Auth::user()->group_id == 22){
                       return $this->salesreports1($request);
                     }
            if($request->se == "ALL" && $request->fromdate && !$request->todate){
                  $date = $request->fromdate;
                  $str = ActivityLog::where('time','LIKE',$date.'%')->where('typeofactivity','!=','NULL')->get();
              }
              elseif($request->se != "ALL" && $request->fromdate && !$request->todate){
                  $date = $request->fromdate;
                  $str = ActivityLog::where('time','LIKE',$request->fromdate.'%')
                          ->where('employee_id',$request->se)->where('typeofactivity','!=','NULL')
                          ->get();
                          
              }elseif($request->se == "ALL" && $request->fromdate && $request->todate){
                  $date = $request->fromdate;
                  $from= $request->fromdate;
                  $to= $request->todate;
                  
                  if($from == $to){
                       $str = ActivityLog::where('time','like',$from.'%')
                             ->where('time','LIKE',$to."%")->where('typeofactivity','!=','NULL')
                             ->get();
                       
                  }
                  else{
                  $str = ActivityLog::where('time','>',$request->fromdate)
                          ->where('time','<=',$request->todate)->where('typeofactivity','!=','NULL')
                             ->get();
                            
                        
                  }
              }elseif($request->se != "ALL" && $request->fromdate && $request->todate){
               
                  $date = $request->fromdate;
                  $from= $request->fromdate;
                  $to= $request->todate;
                  if($from == $to){

                  $str = ActivityLog::where('time','like',$from.'%')
                      ->where('time','LIKE',$to."%")
                      ->where('employee_id',$request->se)->where('typeofactivity','!=','NULL')
                        ->get();
                            
                  }
                  else{
                  $str = ActivityLog::where('time','>',$request->fromdate)
                          ->where('time','<=',$request->todate)
                          ->where('employee_id',$request->se)->where('typeofactivity','!=','NULL')
                           ->get();
                  }
              }else{
                  $date = date('Y-m-d');
                  $str = ActivityLog::where('time','LIKE',$date.'%')->where('typeofactivity','!=','NULL')->get();
              }
                   
           $today = date('Y-m-d');
           $exploded = array();
              $projectIds = array();
              foreach($str as $strings){
                  array_push($exploded,explode(" ",$strings->activity));
              }

           for($i = 0;$i<count($exploded);$i++){
                  $key = array_search("id:", $exploded[$i]);
                  $name = array_search("has", $exploded[$i]);
                  $quality = array_search("Quality:", $exploded[$i]);
                  $projectIds[$i]['projectId'] = $exploded[$i][$key+1];
                  if($name == 3){
                      $projectIds[$i]['updater'] = $exploded[$i][$name-3]." ".$exploded[$i][$name-2]." ".$exploded[$i][$name-1];
                  }elseif($name == 2){
                      $projectIds[$i]['updater'] = $exploded[$i][$name-2]." ".$exploded[$i][$name-1];
                  }else{
                      $projectIds[$i]['updater'] = $exploded[$i][$name-1];
                  }
                  $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
                  $ward = Ward::where('id',$tl)->pluck('id')->first();
                  $sub  = Subward::where('ward_id',$ward)->pluck('id');

                  $project = ProjectDetails::where('project_id',$projectIds[$i]['projectId'])->first();
                  if($project != null){
                    $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
                    $userIds = explode(",", $tl);



    $projectIds[$i]['quality'] = $project->quality;
    $projectIds[$i]['followup'] = $project->followup;
    $projectIds[$i]['followupby'] = User::where('id',$project->follow_up_by)->pluck('name')->first();
    $projectIds[$i]['caller'] = User::where('id',$project->call_attended_by)->pluck('name')->first();
    $projectIds[$i]['sub_ward_name'] = SubWard::where('id',$project->sub_ward_id)->pluck('sub_ward_name')->first();
    $projectIds[$i]['enquiryInitiated'] = Requirement::where('project_id',$projectIds[$i]['projectId'])->count();
    $projectIds[$i]['enquiryInitiatedBy'] = 
    Requirement::where('requirements.project_id',$project->project_id)
                 ->leftjoin('users','requirements.generated_by','users.id')
                  ->select('users.name','requirements.id')
                  ->get();
                       }else{
                      $projectIds[$i]['quality'] = "";
                      $projectIds[$i]['followup'] = "";
                      $projectIds[$i]['followupby'] = "";
                      $projectIds[$i]['caller'] = "";
                      $projectIds[$i]['sub_ward_name'] = "";
                      $projectIds[$i]['enquiryInitiated'] = "";
                      $projectIds[$i]['enquiryInitiatedBy'] = "";
                 }

              }

           $noOfCalls = array();
           $users = User::where('department_id',2)
                       ->leftjoin('salesassignments','salesassignments.user_id','users.id')
                       ->leftJoin('sub_wards','sub_wards.id','salesassignments.assigned_date')
                       ->select('users.*','sub_wards.sub_ward_name')
                       ->get();
                $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
                $userIds = explode(",", $tl);
              
                $tluser =User::whereIn('users.id',$userIds)
                       ->where('department_id',2)
                       ->leftjoin('salesassignments','salesassignments.user_id','users.id')
                       ->leftJoin('sub_wards','sub_wards.id','salesassignments.assigned_date')
                       ->select('users.*','sub_wards.sub_ward_name')
                       ->get();

         foreach($tluser as $user){
           $today = date('Y-m-d');

               $noOfCalls[$user->id]['calls'] = History::where('called_Time','LIKE',$today.'%')
                                           ->where('user_id',$user->id)
                                           ->count();
               $noOfCalls[$user->id]['fake'] = ActivityLog::where('created_at','LIKE',$today.'%')
                                           ->where('employee_id',$user->employeeId)
                                           ->where('quality','Fake')
                                           ->count();

               $noOfCalls[$user->id]['genuine'] = ActivityLog::where('created_at','LIKE',$today.'%')
                                           ->where('employee_id',$user->employeeId)
                                           ->where('quality',' Genuine')
                                           ->count();
               $noOfCalls[$user->id]['initiated'] = Requirement::where('created_at','LIKE',$today.'%')
                                                       ->where('generated_by',$user->id)
                                                       ->count();
           }


           foreach($users as $user){
               $noOfCalls[$user->id]['calls'] = History::where('called_Time','LIKE',$today.'%')
                                           ->where('user_id',$user->id)
                                           ->count();
               $noOfCalls[$user->id]['fake'] = ActivityLog::where('created_at','LIKE',$today.'%')
                                           ->where('employee_id',$user->employeeId)
                                           ->where('quality','Fake')
                                           ->count();
               $noOfCalls[$user->id]['genuine'] = ActivityLog::where('created_at','LIKE',$today.'%')
                                           ->where('employee_id',$user->employeeId)
                                           ->where('quality','Genuine')
                                           ->count();
               $noOfCalls[$user->id]['initiated'] = Requirement::where('created_at','LIKE',$today.'%')
                                                       ->where('generated_by',$user->id)
                                                       ->count();
           }
           $projectsCount = count($str);

            $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
            $userIds = explode(",", $tl);
           
            $tlUsers = User::whereIn('id',$userIds)
               ->where('department_id',2)->get();

           return view('salesReport',['users'=>$users,
                   'date'=>$date,
                   'projectsCount'=>$projectsCount,
                   'noOfCalls'=>$noOfCalls,
                   'tl' =>$tl,
                   'str'=>$str,
                   'tlUsers'=>$tlUsers,
                   'tluser'=>$tluser,
                   'projectIds'=>$projectIds
               ]);
       }
       public function salesreports1(Request $request)
          {
                  $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
                   $eward = explode(",",$tl);
                  $tlward  = Subward::whereIn('ward_id',$eward)->pluck('id');
                
                 

              if($request->se == "ALL" && $request->fromdate && !$request->todate){
                  $date = $request->fromdate;
                  $str = ActivityLog::where('time','LIKE',$date.'%')->whereIn('sub_ward_id',$tlward)->where('typeofactivity','!=','NULL')->get();
              }
              elseif($request->se != "ALL" && $request->fromdate && !$request->todate){
                  $date = $request->fromdate;
                  $str = ActivityLog::where('time','LIKE',$request->fromdate.'%')
                          ->where('employee_id',$request->se)
                          ->whereIn('sub_ward_id',$tlward)->where('typeofactivity','!=','NULL')->get();
                          
              }elseif($request->se == "ALL" && $request->fromdate && $request->todate){
                  $date = $request->fromdate;
                  $from= $request->fromdate;
                  $to= $request->todate;
                  
                  if($from == $to){
                       $str = ActivityLog::where('time','like',$from.'%')
                             ->where('time','LIKE',$to."%")
                             ->whereIn('sub_ward_id',$tlward)->where('typeofactivity','!=','NULL')->get();
                            
                       
                  }
                  else{ 
                  $str = ActivityLog::where('time','>',$request->fromdate)
                          ->where('time','<=',$request->todate)
                             ->whereIn('sub_ward_id',$tlward)->where('typeofactivity','!=','NULL')->get();
                        
                  }
              }elseif($request->se != "ALL" && $request->fromdate && $request->todate){

                  $date = $request->fromdate;
                  $from= $request->fromdate;
                  $to= $request->todate;
                  if($from == $to){

                  $str = ActivityLog::where('time','like',$from.'%')
                      ->where('time','LIKE',$to."%")
                      ->where('employee_id',$request->se)
                        ->whereIn('sub_ward_id',$tlward)->where('typeofactivity','!=','NULL')->get();
                            
                  }
                  else{
                  $str = ActivityLog::where('time','>',$request->fromdate)
                          ->where('time','<=',$request->todate)
                          ->where('employee_id',$request->se)
                           ->whereIn('sub_ward_id',$tlward)->where('typeofactivity','!=','NULL')->get();
                  }
              }else{
                  $date = date('Y-m-d');
                  $str = ActivityLog::where('time','LIKE',$date.'%')->whereIn('sub_ward_id',$tlward)->where('typeofactivity','!=','NULL')->get();
              }
              $today = date('Y-m-d');
              $exploded = array();
              $projectIds = array();
              foreach($str as $strings){
                  array_push($exploded,explode(" ",$strings->activity));
              }

              for($i = 0;$i<count($exploded);$i++){
                  $key = array_search("id:", $exploded[$i]);
                  $name = array_search("has", $exploded[$i]);
                  $quality = array_search("Quality:", $exploded[$i]);
                  $projectIds[$i]['projectId'] = $exploded[$i][$key+1];
                  if($name == 3){
                      $projectIds[$i]['updater'] = $exploded[$i][$name-3]." ".$exploded[$i][$name-2]." ".$exploded[$i][$name-1];
                  }elseif($name == 2){
                      $projectIds[$i]['updater'] = $exploded[$i][$name-2]." ".$exploded[$i][$name-1];
                  }else{
                      $projectIds[$i]['updater'] = $exploded[$i][$name-1];
                  }
                  $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
                   $ward = explode(",",$tl);
                  $sub  = Subward::where('ward_id',$ward)->pluck('id');

                  $project = ProjectDetails::where('project_id',$projectIds[$i]['projectId'])->whereIn('sub_ward_id',$sub)->first();
                  if($project != null){
                    $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
                    $userIds = explode(",", $tl);



    $projectIds[$i]['quality'] = $project->quality;
    $projectIds[$i]['followup'] = $project->followup;
    $projectIds[$i]['followupby'] = User::where('id',$project->follow_up_by)->pluck('name')->first();
    $projectIds[$i]['caller'] = User::where('id',$project->call_attended_by)->pluck('name')->first();
    $projectIds[$i]['sub_ward_name'] = SubWard::where('id',$project->sub_ward_id)->pluck('sub_ward_name')->first();
    $projectIds[$i]['enquiryInitiated'] = Requirement::where('project_id',$projectIds[$i]['projectId'])->count();
    $projectIds[$i]['enquiryInitiatedBy'] = 
    Requirement::where('requirements.project_id',$project->project_id)
                 ->leftjoin('users','requirements.generated_by','users.id')
                  ->select('users.name','requirements.id')
                  ->get();
                       }else{
                      $projectIds[$i]['quality'] = "";
                      $projectIds[$i]['followup'] = "";
                      $projectIds[$i]['followupby'] = "";
                      $projectIds[$i]['caller'] = "";
                      $projectIds[$i]['sub_ward_name'] = "";
                      $projectIds[$i]['enquiryInitiated'] = "";
                      $projectIds[$i]['enquiryInitiatedBy'] = "";
                 }

              }
              $noOfCalls = array();
              $users = User::where('department_id',2)
                          ->leftjoin('salesassignments','salesassignments.user_id','users.id')
                          ->leftJoin('sub_wards','sub_wards.id','salesassignments.assigned_date')
                          ->select('users.*','sub_wards.sub_ward_name')
                          ->get();
                   $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
                   $userIds = explode(",", $tl);
                   
                 
                   $tluser =User::whereIn('users.id',$userIds)
                           ->where('department_id',2)
                          ->leftjoin('salesassignments','salesassignments.user_id','users.id')
                          ->leftJoin('sub_wards','sub_wards.id','salesassignments.assigned_date')
                          ->select('users.*','sub_wards.sub_ward_name')
                          ->get();


            
         foreach($tluser as $user){
           $today = date('Y-m-d');

               $noOfCalls[$user->id]['calls'] = History::where('called_Time','LIKE',$today.'%')
                                           ->where('user_id',$user->id)
                                           ->count();
               $noOfCalls[$user->id]['fake'] = ActivityLog::where('created_at','LIKE',$today.'%')
                                           ->where('employee_id',$user->employeeId)
                                           ->where('quality','Fake')
                                           ->count();
         }

              foreach($users as $user){
                  $noOfCalls[$user->id]['calls'] = ProjectDetails::where('updated_at','LIKE',$today.'%')
                                              ->where('call_attended_by',$user->id)
                                              ->count();
                  $noOfCalls[$user->id]['fake'] = ActivityLog::where('time','LIKE',$today.'%')
                                              ->where('employee_id',$user->employeeId)
                                              ->where('activity','LIKE','%Quality: Fake%')
                                              ->count();
                  $noOfCalls[$user->id]['genuine'] = ActivityLog::where('time','LIKE',$today.'%')
                                              ->where('employee_id',$user->employeeId)
                                              ->where('activity','LIKE','%Quality: Genuine%')
                                              ->count();
                  $noOfCalls[$user->id]['initiated'] = Requirement::where('created_at','LIKE',$today.'%')
                                                          ->where('generated_by',$user->id)
                                                          ->count();
              }
              $projectsCount = count($projectIds);


           foreach($users as $user){
               $noOfCalls[$user->id]['calls'] = History::where('called_Time','LIKE',$today.'%')
                                           ->where('user_id',$user->id)
                                           ->count();
               $noOfCalls[$user->id]['fake'] = ActivityLog::where('created_at','LIKE',$today.'%')
                                           ->where('employee_id',$user->employeeId)
                                           ->where('quality','Fake')
                                           ->count();
               $noOfCalls[$user->id]['genuine'] = ActivityLog::where('created_at','LIKE',$today.'%')
                                           ->where('employee_id',$user->employeeId)
                                           ->where('quality','Genuine')
                                           ->count();
               $noOfCalls[$user->id]['initiated'] = Requirement::where('created_at','LIKE',$today.'%')
                                                       ->where('generated_by',$user->id)
                                                       ->count();
           }
               $projectsCount = count($str);
              
                
            
               $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
               $userIds = explode(",", $tl);
                $grp = [6,7,17];
               $tlUsers = User::whereIn('id',$userIds)
                 ->where('department_id',2)->get();

              return view('salesReport',['users'=>$users,
                      'date'=>$date,
                      'projectsCount'=>$projectsCount,
                      'noOfCalls'=>$noOfCalls,
                      'str'=>$str,
                      'tl' =>$tl,
                      'tlUsers'=>$tlUsers,
                      'tluser'=>$tluser,
                      'projectIds'=>$projectIds
                  ]);
          }
        
    

    public function stages(Request $request)
    {
       $users = User::where('users.department_id','!=',10)
                    ->leftjoin('departments','departments.id','users.department_id')
                    ->leftjoin('groups','groups.id','users.group_id')
                    ->leftjoin('stages','stages.list','users.name')
                    ->select('users.*','departments.dept_name','groups.group_name')

                    ->paginate(10);
             $stages = Stages::where('status','')->get();


            $wards = Ward::all();

         $le = DB::table('users')->where('department_id','1')->where('group_id','6')->get();
          $se = DB::table('users')->where('department_id','2')->where('group_id','7')->get();
         return view('assignStages',['le' => $le ,'se' => $se,'users'=>$users]);

    }

     public function store(Request $request)
    {

        $this->validate($request, [

            'list' => 'required|max:500',
            'status' => 'required|max:500',

        ]);
        Stages::create([
        'list'=> $request['list'],
        'status'=> $request['status'],

      ]);
        return redirect()->back();

    }
     public function datestore(Request $request)
    {

        $this->validate($request, [

            'name' => 'required|max:500',
            'assigndate' => 'required|max:500',

        ]);
        $dates = new Dates;
        $dates->user_id = $request->name;
        $dates->assigndate = $request->assigndate;
        $dates->save();
        return redirect()->back();

    }
    public function salesConverterDashboard()
    {
         $tl1= Tlwards::where('group_id','=',22)->get();
        $userid = Auth::user()->id;
        $found1 = null;
        foreach($tl1 as $searchWard){
            $usersId = explode(",",$searchWard->users);
            if(in_array($userid, $usersId)){
                $found1 = $searchWard->ward_id;
            }
        }
        $stages = AssignStage::where('user_id',Auth::user()->id)->first();
           $found = explode(",", $found1);
       $ward =Ward::whereIn('id',$found)->get();
        return view('scdashboard',['ward'=>$ward,'stages'=>$stages]);
    }
 public function getChat()
    {
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
        $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $reads = Message::where('read_by','NOT LIKE',"%".Auth::user()->id."%")->get();
        foreach($reads as $read){
            $reader = $read->read_by;
            if($reader == "none"){
                $read->read_by = Auth::user()->id;
                $read->save();
            }else{
                $read->read_by = $reader.", ".Auth::user()->id;
                $read->save();
            }
        }

        return view('chat',['log'=>$log,'log1'=>$log1]);
    }

public function approval(request $request  )
    {
      ProjectDetails::where('project_id',$request->id)
        ->update([
            'deleted'=>2
        ]);
      return back();
    }
    public function getWardMaping(Request $request)
    {
        if($request->zoneId){
            $zones = Zone::leftjoin('zone_maps','zones.id','zone_maps.zone_id')
                        ->select('zones.*','zone_maps.lat','zone_maps.color','zone_maps.zone_id','zones.zone_name as name')
                        ->where('zones.id',$request->zoneId)
                        ->first();
            $page = "Zone";
        }elseif($request->wardId){
            $zones = Ward::leftjoin('ward_maps','wards.id','ward_maps.ward_id')
                        ->select('wards.*','ward_maps.lat','ward_maps.color','ward_maps.ward_id','wards.ward_name as name')
                        ->where('wards.id',$request->wardId)
                        ->first();
            $page = "Ward";
        }elseif($request->subWardId){
            $zones = SubWard::leftjoin('sub_ward_maps','sub_wards.id','sub_ward_maps.sub_ward_id')
                        ->select('sub_wards.*','sub_ward_maps.lat','sub_ward_maps.color','sub_ward_maps.sub_ward_id','sub_wards.sub_ward_name as name')
                        ->where('sub_wards.id',$request->subWardId)
                        ->first();
                        
            $page = "Sub Ward";
        }
        return view('maping.wardmaping',['zones'=>$zones,'page'=>$page]);
    }
    public function getWards(Request $request)
    {
        $wards = Ward::where('zone_id',$request->id)
                    ->leftjoin('ward_maps','wards.id','ward_maps.ward_id')
                    ->select('wards.*','ward_maps.lat','ward_maps.color')
                    ->get();
        return response()->json($wards);
    }
public function myreport(request $request)
{
          $data = [];
     $today = date('y-m-d');
     $call = ['attend','call_attended'];
     $int = ['Not_Instrested','notinterest'];
     $not_ans =['Call_Not_Answered','notanswer'];
     $swichedoff=['switched','switched_off'];
    
     if(!$request->fromdate){


         $user = Auth::user()->id;
       $addprojects = ProjectDetails::where('listing_engineer_id',$user)->where('created_at','LIKE','%'.$today.'%')->count(); 
       $addmanu = Manufacturer::where('listing_engineer_id',$user)->where('created_at','LIKE','%'.$today.'%')->count(); 
       $updateprojects = UpdatedReport::where('user_id',$user)->where('project_id','!=',NULL)->where('created_at','LIKE','%'.$today.'%')->count();
       $updatedmanu = UpdatedReport::where('user_id',$user)->where('manu_id','!=',NULL)->where('created_at','LIKE','%'.$today.'%')->count();
      $cal = DB::table('updated_reports')->where('p_p_c_id','!=',NULL)
                     ->select('p_p_c_id', DB::raw('COUNT(*) as products_count'))
                      ->whereIn('quntion',$call)->where('created_at','LIKE','%'.$today.'%')
                      ->where('user_id',$user)
                      ->groupBy('p_p_c_id')
                      ->havingRaw('COUNT(*) >= 1')
                      ->get(); 
               $ns = DB::table('updated_reports')->where('p_p_c_id',NULL)->whereIn('quntion',$call)->where('created_at','LIKE','%'.$today.'%')->where('user_id',$user)->count();

               


             $callattend = count($cal) + $ns;
       $callbusy = UpdatedReport::where('user_id',$user)->where('quntion',"Busy")->where('created_at','LIKE','%'.$today.'%')->count();
       $notinterest = UpdatedReport::where('user_id',$user)->whereIn('quntion',$int)->where('created_at','LIKE','%'.$today.'%')->count();
       $notanswer = UpdatedReport::where('user_id',$user)->whereIn('quntion',$not_ans)->where('created_at','LIKE','%'.$today.'%')->count();
       $switchoff = UpdatedReport::where('user_id',$user)->whereIn('quntion',$swichedoff)->where('created_at','LIKE','%'.$today.'%')->count();
        $logistic = Order::where('logistic','LIKE','%'.$user.'%')->where('created_at','LIKE','%'.$today.'%')->count();
        $name = User::where('id',$user)->pluck('name')->first();
        
        $order = Order::where('generated_by',$user)->where('status','Order Confirmed')->where('created_at','LIKE','%'.$today.'%')->count();


      array_push($data,['addproject'=>$addprojects,'addmanu'=>$addmanu,'updateprojects'=>$updateprojects,'updatedmanu'=>$updatedmanu,'callattend'=>$callattend,'callbusy'=>$callbusy,'notinterest'=>$notinterest,'notanswer'=>$notanswer,'switchoff'=>$switchoff,'logistic'=>$logistic,'name'=>$name,'order'=>$order]);

     
   }
     else{
         $user = Auth::user()->id;

           if($request->fromdate && $request->todate) {

       $addprojects = ProjectDetails::where('listing_engineer_id',$user)->where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->count(); 
       $addmanu = Manufacturer::where('listing_engineer_id',$user)->where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->count(); 
       $updateprojects = UpdatedReport::where('user_id',$user)->where('project_id','!=',NULL)->where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->count();
       $updatedmanu = UpdatedReport::where('user_id',$user)->where('manu_id','!=',NULL)->where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->count();
       $callattend = UpdatedReport::where('user_id',$user)->whereIn('quntion', $call)->where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->count();
       $callbusy = UpdatedReport::where('user_id',$user)->where('quntion',"Busy")->where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->count();
       $notinterest = UpdatedReport::where('user_id',$user)->whereIn('quntion',$int)->where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->count();
       $notanswer = UpdatedReport::where('user_id',$user)->whereIn('quntion',$not_ans)->where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->count();
       $switchoff = UpdatedReport::where('user_id',$user)->whereIn('quntion',$swichedoff)->where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->count();
        $logistic = Order::where('logistic','LIKE','%'.$user.'%')->where('created_at','>=',$request->fromdate)->where('created_at','<=',$request->todate)->count();
        $name = User::where('id',$user)->pluck('name')->first();
        
        $order = Order::where('generated_by',$user)->where('status','Order Confirmed')->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->count();


      array_push($data,['addproject'=>$addprojects,'addmanu'=>$addmanu,'updateprojects'=>$updateprojects,'updatedmanu'=>$updatedmanu,'callattend'=>$callattend,'callbusy'=>$callbusy,'notinterest'=>$notinterest,'notanswer'=>$notanswer,'switchoff'=>$switchoff,'logistic'=>$logistic,'name'=>$name,'order'=>$order]);
           }

     


     }
      return view('/myreport',['data'=>$data]);
    
}
public function assigndate(request $request )
{
     $users = User::where('users.department_id','!=',10)
                    ->leftjoin('departments','departments.id','users.department_id')
                    ->leftjoin('groups','groups.id','users.group_id')
                    ->leftjoin('stages','stages.list','users.name')
                    ->select('users.*','departments.dept_name','groups.group_name')

                    ->paginate(10);
             $stages = Stages::where('status','')->get();


            $wards = Ward::all();

         $le = DB::table('users')->where('department_id','1')->where('group_id','6')->get();
          $se = DB::table('users')->where('department_id','2')->where('group_id','7')->get();
         return view('assigndate',['le' => $le ,'se' => $se,'users'=>$users]);

}
    public function approvePoint(Request $request)
    {
        $point = Point::where('id',$request->id)->first();
        $point->confirmation = 1;
        $point->save();
        return back();
    }
    public function getLeTracking(Request $request)
    {
        $tracking = UserLocation::where('created_at','LIKE',date('Y-m-d')."%")->pluck('user_id')->toArray();
        $users = User::whereIn('id',$tracking)->get();
         $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
              $userIds = explode(",", $tl);
              $tlUsers = User::whereIn('id',$userIds)->where('group_id',6)->simplePaginate();
        if($request->userId){
            $track = UserLocation::where('user_id',$request->userId)
                        ->where('created_at','LIKE',date('Y-m-d')."%")
                        ->get();
            return view('letracking',['users'=>$users,'track'=>$track,'tlUsers'=>$tlUsers]);
        }
        return view('letracking',['users'=>$users,'tlUsers'=>$tlUsers]);
    }
    public function confidential(Request $request){

        $wards = Ward::orderby('ward_name','ASC')->get();
        
        $qualityCheck = $request->quality;
        // getting total no of projects
        $wardsselect = Ward::pluck('id');
        $subwards = SubWard::whereIn('ward_id',$wardsselect)->pluck('id');
        $planningCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Planning%')->count();
        $planningSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Planning%')->sum('project_size');
        $foundationCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Foundation%')->count();
        $foundationSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Foundation%')->sum('project_size');
        $roofingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Roofing%')->count();
        $roofingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Roofing%')->sum('project_size');
        $wallsCount         = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Walls%')->count();
        $wallsSize          = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Walls%')->sum('project_size');
        $completionCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Completion%')->count();
        $completionSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Completion%')->sum('project_size');
        $fixturesCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Fixtures%')->count();
        $fixturesSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Fixtures%')->sum('project_size');
        $pillarsCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Pillars%')->count();
        $pillarsSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Pillars%')->sum('project_size');
        $paintingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Paintings%')->count();
        $paintingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Paintings%')->sum('project_size');
        $flooringCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Flooring%')->count();
        $flooringSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Flooring%')->sum('project_size');
        $plasteringCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Plastering%')->count();
        $plasteringSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Plastering%')->sum('project_size');
        $diggingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Digging%')->count();
        $diggingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Digging%')->sum('project_size');
        $ele                = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Electrical%')->pluck('project_id');
        $plum               = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
        $ele                = $ele->merge($plum);
        $enpCount           = ProjectDetails::whereIn('project_id',$ele)->count();
        $enpSize            = ProjectDetails::where('project_id',$ele)->sum('project_size');
        $carpentryCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Carpentry%')->count();
        $carpentrySize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Carpentry%')->sum('project_size');
        // $closedCount        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Closed%')->count();
        // $closedSize         = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Closed%')->sum('project_size');

        $totalProjects = $planningCount + $diggingCount + $foundationCount + $pillarsCount + $completionCount + $fixturesCount + $paintingCount + $carpentryCount + $flooringCount + $plasteringCount + $enpCount + $roofingCount + $wallsCount ;

        if($request->ward && !$request->subward){
            if($request->ward == "All"){
                $planningCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->count();
                $planningSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->sum('project_size');
                $foundationCount    = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->count();
                $foundationSize     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->sum('project_size');
                $roofingCount       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->count();
                $roofingSize        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->sum('project_size');
                $wallsCount         = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->count();
                $wallsSize          = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->sum('project_size');
                $completionCount    = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->count();
                $completionSize     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->sum('project_size');
                $fixturesCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->count();
                $fixturesSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->sum('project_size');
                $pillarsCount       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->count();
                $pillarsSize        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->sum('project_size');
                $paintingCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->count();
                $paintingSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->sum('project_size');
                $flooringCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->count();
                $flooringSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->sum('project_size');
                $plasteringCount    = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->count();
                $plasteringSize     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->sum('project_size');
                $diggingCount       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->count();
                $diggingSize        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->sum('project_size');
                $ele                = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->pluck('project_id');
                $plum               = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
                $ele                = $ele->merge($plum);
                $enpCount           = ProjectDetails::whereIn('project_id',$ele)->count();
                $enpSize            = ProjectDetails::whereIn('project_id',$ele)->sum('project_size');
                $carpentryCount     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->count();
                $carpentrySize      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->sum('project_size');
                // $closedCount        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->count();
                // $closedSize         = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->sum('project_size');
                $wardname = "All";
                $subwards = SubWard::all();

            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
                $planningCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->count();
                $planningSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->sum('project_size');
                $foundationCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->count();
                $foundationSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->sum('project_size');
                $roofingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->count();
                $roofingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->sum('project_size');
                $wallsCount         = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->count();
                $wallsSize          = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->sum('project_size');
                $completionCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->count();
                $completionSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->sum('project_size');
                $fixturesCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->count();
                $fixturesSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->sum('project_size');
                $pillarsCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->count();
                $pillarsSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->sum('project_size');
                $paintingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->count();
                $paintingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->sum('project_size');
                $flooringCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->count();
                $flooringSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->sum('project_size');
                $plasteringCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->count();
                $plasteringSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->sum('project_size');
                $diggingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->count();
                $diggingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->sum('project_size');
                $ele                = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->pluck('project_id');
                $plum               = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
                $ele                = $ele->merge($plum);
                $enpCount           = ProjectDetails::whereIn('project_id',$ele)->count();
                $enpSize            = ProjectDetails::whereIn('project_id',$ele)->sum('project_size');
                $carpentryCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->count();
                $carpentrySize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->sum('project_size');
                // $closedCount        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->count();
                // $closedSize         = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->sum('project_size');
                $wardname = Ward::where('id',$request->ward)->first();
                $subwards = SubWard::where('ward_id',$request->ward)->get();
            }
            return view('confidential',[
                'planningCount'=>$planningCount,'planningSize'=>$planningSize,
                'foundationCount'=>$foundationCount,'foundationSize'=>$foundationSize,
                'roofingCount'=>$roofingCount,'roofingSize'=>$roofingSize,
                'wallsCount'=>$wallsCount,'wallsSize'=>$wallsSize,
                'completionCount'=>$completionCount,'completionSize'=>$completionSize,
                'fixturesCount'=>$fixturesCount,'fixturesSize'=>$fixturesSize,
                'pillarsCount'=>$pillarsCount,'pillarsSize'=>$pillarsSize,
                'paintingCount'=>$paintingCount,'paintingSize'=>$paintingSize,
                'flooringCount'=>$flooringCount,'flooringSize'=>$flooringSize,
                'plasteringCount'=>$plasteringCount,'plasteringSize'=>$plasteringSize,
                'diggingCount'=>$diggingCount,'diggingSize'=>$diggingSize,
                'enpCount'=>$enpCount,'enpSize'=>$enpSize,
                'carpentryCount'=>$carpentryCount,'carpentrySize'=>$carpentrySize,
                // 'closedSize'=>$closedSize,'closedCount'=>$closedCount,
                'wards'=>$wards,
                'wardname'=>$wardname,
                'subwards'=>$subwards,'wardId'=>$request->ward,'planning'=>NULL,'subwardId'=>NULL,'subwardName'=>NULL,'totalProjects' => $totalProjects
            ]);
        }
        if($request->subward){
            $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            $subwardQuality = $request->subwardquality;
            $planningCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->count();
            $planningSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->sum('project_size');
            $foundationCount   = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->count();
            $foundationSize    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->sum('project_size');
            $roofingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->count();
            $roofingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->sum('project_size');
            $wallsCount        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->count();
            $wallsSize         = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->sum('project_size');
            $completionCount   = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->count();
            $completionSize    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->sum('project_size');
            $fixturesCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->count();
            $fixturesSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->sum('project_size');
            $pillarsCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->count();
            $pillarsSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->sum('project_size');
            $paintingCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->count();
            $paintingSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->sum('project_size');
            $flooringCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->count();
            $flooringSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->sum('project_size');
            $plasteringCount   = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->count();
            $plasteringSize    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->sum('project_size');
            $diggingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->count();
            $diggingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->sum('project_size');
            $ele               = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->pluck('project_id');
            $plum              = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
            $ele               = $ele->merge($plum);
            $enpCount          = ProjectDetails::whereIn('project_id',$ele)->count();
            $enpSize           = ProjectDetails::whereIn('project_id',$ele)->sum('project_size');
            $carpentryCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->count();
            $carpentrySize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->sum('project_size');
            // $closedCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->count();
            // $closedSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->sum('project_size');

            $wardname = Ward::where('id',$request->ward)->first();
            $subwards = SubWard::where('ward_id',$request->ward)->get();
            $total = ProjectDetails::withTrashed()->where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->count();
            $planning   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Planning%')->sum('project_size');
            $foundation = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Foundation%')->sum('project_size');
            $roofing    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Roofing%')->sum('project_size');
            $walls      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Walls%')->sum('project_size');
            $completion = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Completion%')->sum('project_size');
            $fixtures   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Fixtures%')->sum('project_size');
            $pillars    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Pillars%')->sum('project_size');
            $painting   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Paintings%')->sum('project_size');
            $flooring   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Flooring%')->sum('project_size');
            $plastering = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Plastering%')->sum('project_size');
            $digging    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Digging%')->sum('project_size');
            $ele2        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->pluck('project_id');
            $plum2       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
            $ele2        = $ele2->merge($plum2);
            $enp    = ProjectDetails::whereIn('project_id',$ele2)->sum('project_size');
            $carpentry  = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Carpentry%')->sum('project_size');
            // $closed     = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Closed%')->sum('project_size');

            $Cplanning      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Planning%')->count();
            $Cfoundation    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Foundation%')->count();
            $Croofing       = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Roofing%')->count();
            $Cwalls         = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Walls%')->count();
            $Ccompletion    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Completion%')->count();
            $Cfixtures      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Fixtures%')->count();
            $Cpillars       = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Pillars%')->count();
            $Cpainting      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Paintings%')->count();
            $Cflooring      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Flooring%')->count();
            $Cplastering    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Plastering%')->count();
            $Cdigging       = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Digging%')->count();
            $Cenp   = ProjectDetails::whereIn('project_id',$ele2)->count();
            // $Cenp           = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Electrical%')
            //                     ->orWhere('project_status','LIKE','Plumbing%')->count();
            $Ccarpentry     = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Carpentry%')->count();
            // $Cclosed        = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Closed%')->count();

            $subwardname = SubWard::where('id',$request->subward)->pluck('sub_ward_name')->first();
            $totalsubward = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->sum('project_size');
            return view('confidential',[
                'planningCount'=>$planningCount,'planningSize'=>$planningSize,
                'foundationCount'=>$foundationCount,'foundationSize'=>$foundationSize,
                'roofingCount'=>$roofingCount,'roofingSize'=>$roofingSize,
                'wallsCount'=>$wallsCount,'wallsSize'=>$wallsSize,
                'completionCount'=>$completionCount,'completionSize'=>$completionSize,
                'fixturesCount'=>$fixturesCount,'fixturesSize'=>$fixturesSize,
                'pillarsCount'=>$pillarsCount,'pillarsSize'=>$pillarsSize,
                'paintingCount'=>$paintingCount,'paintingSize'=>$paintingSize,
                'flooringCount'=>$flooringCount,'flooringSize'=>$flooringSize,
                'plasteringCount'=>$plasteringCount,'plasteringSize'=>$plasteringSize,
                'diggingCount'=>$diggingCount,'diggingSize'=>$diggingSize,
                'enpCount'=>$enpCount,'enpSize'=>$enpSize,
                'carpentryCount'=>$carpentryCount,'carpentrySize'=>$carpentrySize,
                // 'closedSize'=>$closedSize,'closedCount'=>$closedCount,
                'wards'=>$wards,'wardname'=>$wardname,
                'subwards'=>$subwards,'wardId'=>$request->ward,
                'totalProjects' => $totalProjects,
                'planning'=>$planning,
                'foundation'=>$foundation,
                'roofing'=>$roofing,
                'walls'=>$walls,
                'completion'=>$completion,
                'fixtures'=>$fixtures,
                'pillars'=>$pillars,
                'painting'=>$painting,
                'flooring'=>$flooring,
                'plastering'=>$plastering,
                'digging'=>$digging,
                'enp'=>$enp,
                'carpentry'=>$carpentry,
                'Cplanning'=>$Cplanning,
                'Cfoundation'=>$Cfoundation,
                'Croofing'=>$Croofing,
                'Cwalls'=>$Cwalls,
                'Ccompletion'=>$Ccompletion,
                'Cfixtures'=>$Cfixtures,
                'Cpillars'=>$Cpillars,
                'Cpainting'=>$Cpainting,
                'Cflooring'=>$Cflooring,
                'Cplastering'=>$Cplastering,
                'Cdigging'=>$Cdigging,
                'Cenp'=>$Cenp,
                'Ccarpentry'=>$Ccarpentry,
                // 'closed'=>$closed,
                // 'Cclosed'=>$Cclosed,
                'subwardId'=>$request->subward,
                'subwardName'=>$subwardname,
                'total'=>$total,
                'totalsubward'=>$totalsubward
            ]);
        }
        return view('confidential',['wards'=>$wards,'planningCount'=>NULL,'subwards'=>NULL,'wardId'=>NULL,'planning'=>NULL,'subwardId'=>NULL,'subwardName'=>NULL,'totalProjects' => $totalProjects]);
    }
 public function numberwise(request $request){
    $depts = [1,2];
    $users = User::whereIn('users.department_id',$depts)
              ->leftjoin('groups','groups.id','users.group_id')
              ->select('users.*','groups.group_name')->get();
             $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
              $userIds = explode(",", $tl);
              $tlUsers = User::whereIn('id',$userIds)->simplePaginate();

        $count = numbers::count();
       
        if($request->range){
        $number = numbers::paginate($request->range);
           
        }else{

        $number = numbers::paginate(100);
        }

        if($request->delete){
            numbers::truncate();
            return back();
        }
      $ward = Ward::all();
         return view('assign_number',['users'=>$users,'number'=>$number,'count'=>$count,'tlUsers'=>$tlUsers,'ward'=>$ward]);

}
public function savenumber(request $request){


        $check = new MamaSms;
        $check->sim_number = $request->phNo;
        $check->user_id = Auth::user()->id;
        $check->totalnumber = 100;
        $check->save();
        return back()->with('success',"Phone Number Added Successfully");

}
public function storenumber(request $request){
    if($request->stage ){
        $stages = implode(", ", $request->stage);
    } else{
        $stages ="null";
    }
    $validator = Validator::make($request->all(), ['stage' => 'required']);
            if ($validator->fails()) {
                return back()
                ->with('NotAdded','Select Stage Before Submit')
                ->withErrors($validator)
                ->withInput();
            }
    $check = AssignNumber::where('user_id',$request->user_id)->first();
            if(count($check) == 0){
            $anumber = new AssignNumber;
            $anumber->user_id = $request->user_id ;
            $anumber->stage = $stages;
            $anumber->save();

    }
    else{
            $check->stage = $stages;
            $check->save();

    }

     return redirect()->back()->with('Success','Assigned successfully');

}

public function projectwise1(request $request){
     $depts = [1,2];
     $wardsAndSub = [];
    $users = User::whereIn('users.department_id',$depts)
              ->leftjoin('assignstage','assignstage.user_id','users.id')
              ->leftjoin('departments','departments.id','users.department_id'   )
              ->leftjoin('groups','groups.id','users.group_id')

              ->select('users.*','departments.dept_name','groups.group_name','assignstage.prv_ward','assignstage.prv_subward','assignstage.prv_date','assignstage.prv_stage','assignstage.state' )->paginate(20);

               $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
              $userIds = explode(",", $tl);
              $tlUsers = User::whereIn('id',$userIds)->get();


 //      $details= $request->Search;
 // $detail = User::where(['name', 'LIKE', '%' . $details . '%'])->get();


   $assignstage=AssignStage::all();
    $wards = Ward::all();
    $subwards = SubWard::leftjoin('project_details','sub_ward_id','sub_wards.id')
               ->select('sub_wards.*')->get();
    $assign = AssignStage::pluck('state');
    foreach($wards as $ward){
        $subward = SubWard::where('ward_id',$ward->id)->get();
        array_push($wardsAndSub,['ward'=>$ward->id,'subWards'=>$subward]);
    }


 return view('assign_project',['wardsAndSub'=>$wardsAndSub,'subwards'=>$subwards, 'users'=>$users,'wards'=>$wards,'assign'=>$assign,'assignstage'=>$assignstage,'tlUsers'=>$tlUsers]);

}


public function projectwise(request $request){

    if(Auth::user()->group_id != 22){
    return $this->projectwise1($request);

}

 $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();

  $tlward = explode(",",$tl);

     $depts = [1,2];
     $wardsAndSub = [];
    $users = User::whereIn('users.department_id',$depts)
              ->leftjoin('assignstage','assignstage.user_id','users.id')
              ->leftjoin('departments','departments.id','users.department_id'   )
              ->leftjoin('groups','groups.id','users.group_id')

              ->select('users.*','departments.dept_name','groups.group_name','assignstage.prv_ward','assignstage.prv_subward','assignstage.prv_date','assignstage.prv_stage','assignstage.state' )->paginate(20);
    $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
              $userIds = explode(",", $tl);
    $tlUsers = User::whereIn('users.id',$userIds)
              ->leftjoin('assignstage','assignstage.user_id','users.id')
              ->leftjoin('departments','departments.id','users.department_id'   )
              ->leftjoin('groups','groups.id','users.group_id')

              ->select('users.*','departments.dept_name','groups.group_name','assignstage.prv_ward','assignstage.prv_subward','assignstage.prv_date','assignstage.prv_stage','assignstage.state' )->paginate(20);




 //      $details= $request->Search;
 // $detail = User::where(['name', 'LIKE', '%' . $details . '%'])->get();


   $assignstage=AssignStage::all();
    $wards = Ward::whereIn('wards.id',$tlward)->get();

    $subwards = SubWard::leftjoin('project_details','sub_ward_id','sub_wards.id')
               ->select('sub_wards.*')->get();
    $assign = AssignStage::pluck('state');
    foreach($wards as $ward){
        $subward = SubWard::where('ward_id',$ward->id)->get();
        array_push($wardsAndSub,['ward'=>$ward->id,'subWards'=>$subward]);
    }
 return view('assign_project',['wardsAndSub'=>$wardsAndSub,'subwards'=>$subwards, 'users'=>$users,'wards'=>$wards,'assign'=>$assign,'assignstage'=>$assignstage,'tlUsers'=>$tlUsers]);

}






public function manufacturerwise1(request $request){


      //$ss = $request->$scount;

     $depts = [1,2];
     $wardsAndSub = [];
    $users = User::whereIn('users.department_id',$depts)
              ->leftjoin('assign_manufacturers','assign_manufacturers.user_id','users.id')
              ->leftjoin('departments','departments.id','users.department_id'   )
              ->leftjoin('groups','groups.id','users.group_id')

              ->select('users.*','departments.dept_name','groups.group_name','assign_manufacturers.ward','assign_manufacturers.subward','assign_manufacturers.data' )->paginate(20);

               $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
              $userIds = explode(",", $tl);
              $tlUsers = User::whereIn('id',$userIds)->get();


 //      $details= $request->Search;
 // $detail = User::where(['name', 'LIKE', '%' . $details . '%'])->get();


   $assignstage=assign_manufacturers::all();
    $wards = Ward::all();
    $subwards = SubWard::leftjoin('project_details','sub_ward_id','sub_wards.id')
               ->select('sub_wards.*')->get();
  
    foreach($wards as $ward){
        $subward = SubWard::where('ward_id',$ward->id)->get();
        array_push($wardsAndSub,['ward'=>$ward->id,'subWards'=>$subward]);
    }


 return view('assign_manufacturer',['wardsAndSub'=>$wardsAndSub,'subwards'=>$subwards, 'users'=>$users,'wards'=>$wards,'assignstage'=>$assignstage,'tlUsers'=>$tlUsers]);

}


public function manufacturerwise(request $request){

if(Auth::user()->group_id != 22){
    return $this->manufacturerwise1($request);
}

 $tl1= Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
 $tlward = explode(",",$tl1); 

     $depts = [1,2];
     $wardsAndSub = [];
    $users = User::whereIn('users.department_id',$depts)
              ->leftjoin('assign_manufacturers','assign_manufacturers.user_id','users.id')
              ->leftjoin('departments','departments.id','users.department_id'   )
              ->leftjoin('groups','groups.id','users.group_id')

              ->select('users.*','departments.dept_name','groups.group_name','assign_manufacturers.ward','assign_manufacturers.subward','assign_manufacturers.data')->paginate(20);
    $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
              $userIds = explode(",", $tl);
    $tlUsers = User::whereIn('users.id',$userIds)
              ->leftjoin('assign_manufacturers','assign_manufacturers.user_id','users.id')
              ->leftjoin('departments','departments.id','users.department_id'   )
              ->leftjoin('groups','groups.id','users.group_id')

              ->select('users.*','departments.dept_name','groups.group_name','assign_manufacturers.ward','assign_manufacturers.subward','assign_manufacturers.data')->paginate(20);


   $assignstage=assign_manufacturers::all();
    $wards = Ward::whereIn('wards.id',$tlward)->get();

    $subwards = SubWard::leftjoin('project_details','sub_ward_id','sub_wards.id')
               ->select('sub_wards.*')->get();
    
    foreach($wards as $ward){
        $subward = SubWard::where('ward_id',$ward->id)->get();
        array_push($wardsAndSub,['ward'=>$ward->id,'subWards'=>$subward]);
    }
 return view('assign_manufacturer',['wardsAndSub'=>$wardsAndSub,'subwards'=>$subwards, 'users'=>$users,'wards'=>$wards,'assignstage'=>$assignstage,'tlUsers'=>$tlUsers]);

}









public function projectwisedel(request $request){

AssignStage::where('user_id',Auth::user()->id)->delete();
return redirect()->back();

}

public function projectstore(request $request)
{
     
   
   
    if($request->ward){
    $wards = implode(", ", $request->ward);
    }
    else if($request->all){
    $ward = Ward::pluck('ward_name')->toArray();
    $wards = implode(", ",$ward);
   }else{
        if(Auth::user()->group_id == 22){
            $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
            $tlWard=explode(",",$tl);
            $ward = Ward::whereIn('id',$tlWard)->pluck('ward_name');
            $wards = $ward;
        }else{
            $wards = "null";
        }
    }
  if($request->contract_type){
    $contract = implode(",", $request->contract_type);
  }else{
    $contract = "null";
  }

 if($request->auto){
    $auto = implode(",", $request->auto);
  }else{
    $auto = "null";
  }
  if($request->door){
    $door = implode(",", $request->door);
  }else{
    $door = "null";
  }
if($request->upvc){
    $upvc = implode(",", $request->upvc);
  }else{
    $upvc = "null";
  }
  if($request->brila){
    $brila = implode(",", $request->brila);
  }else{
    $brila = "null";
  }
 if($request->bank){
    $bank = implode(",", $request->bank);
  }else{
    $bank = "null";
  }

 if($request->pre){
    $Premium = implode(",", $request->pre);
  }else{
    $Premium = "null";
  }

    if($request->rmc)
    {
        $rmc = implode(",", $request->rmc);
    }else{
        $rmc="null";
    }
    if($request->subward )
     {
    $subwards = implode(", ", $request->subward);

    } else if($request->all){
        $subwards1 = SubWard::pluck('sub_ward_name')->toarray();
        $subwards = implode(", ", $subwards1);
    }else{
        $subwards = "null";
    }

    if($request->stage )  {

    $stages = implode(", ", $request->stage);
    } else{
        $stages ="null";
    }
    if($request->constraction_type ){
    $contracts = implode(", ",$request->constraction_type);

    }else{
       $contracts = "null";
    }
   if($request->budget_type){

    $budgets = implode(", ", $request->budget_type);
   }else{
    $budgets = "null";
   }
$check = AssignStage::where('user_id',$request->user_id)->first();


if($check == null){

        $projectassign = new AssignStage;

        $projectassign->user_id = $request->user_id;

        $projectassign->ward = $wards;
        $projectassign->subward = $subwards;
        $projectassign->stage = $stages;
        $projectassign->assigndate = $request->assigndate;
        $projectassign->project_type = $request->project_type;
        $projectassign->project_size = $request->project_size;
        $projectassign->budget = $request->budget;
        $projectassign->contract_type =$contract;
        $projectassign->constraction_type = $contracts;
        $projectassign->rmc = $rmc;
        $projectassign->budget_type = $budgets;
        $projectassign->prv_ward =$wards ;
        $projectassign->state = "Not Completed ";
        $projectassign->prv_subward =$subwards;
        $projectassign->prv_date =$request->assigndate;
        $projectassign->prv_stage =$stages;
        $projectassign->Floor = $request->Floor;
        $projectassign->basement = $request->basement;
        $projectassign->base = $request->base;
        $projectassign->Floor2 = $request->Floor2;
        $projectassign->total = $request->total;
        $projectassign->projectsize = $request->projectsize;
        $projectassign->budgetto = $request->budgetto;
        $projectassign->quality = $request->quality;
        $projectassign->auto = $auto;
        $projectassign->bank = $bank;
        $projectassign->Premium = $Premium;
        $projectassign->door = $door;
        $projectassign->fromdate = $request->fromundate;
        $projectassign->todate = $request->toundate;
        $projectassign->upvc = $upvc;
        $projectassign->brila = $brila;
        $projectassign->builder = $request->builder;
         $projectassign->listid = $request->list_id;

        $projectassign->save();
}else{

        $check->ward = $wards;
        $check->subward = $subwards;
        $check->stage = $stages;
        $check->assigndate = $request->assigndate;
        $check->project_type = $request->project_type;
        $check->project_size = $request->project_size;
        $check->budget = $request->budget;
        $check->contract_type = $contract;
        $check->constraction_type = $contracts;
        $check->rmc = $rmc;
        $check->budget_type = $budgets;
        $check->state = "Not Completed ";
        $check->prv_ward =$wards ;
        $check->prv_subward =$subwards;
        $check->prv_date =$request->assigndate;
        $check->prv_stage =$stages;
        $check->Floor =$request->Floor;
        $check->basement =$request->basement;
        $check->basement = $request->basement;
        $check->base = $request->base;
        $check->Floor2 = $request->Floor2;
        $check->total = $request->total;
        $check->projectsize = $request->projectsize;
        $check->budgetto = $request->budgetto;
        $check->quality = $request->quality;
        $check->project_ids = null;
        $check->time = $request->settime;
        $check->instruction = $request->inc;
        $check->auto = $auto;
        $check->bank = $bank;
        $check->Premium = $Premium;
        $check->door = $door;
        $check->fromdate = $request->fromundate;
        $check->todate = $request->toundate;
        $check->upvc = $upvc;
        $check->brila = $brila;
        $check->builder = $request->builder;
         $check->listid = $request->list_id;

        $check->save();
}
$date=date('Y-m-d');
$log = FieldLogin::where('user_id',$request->user_id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',$request->user_id)->where('logout','!=','NULL')->pluck('logout')->count();
         if($request->user_id == 191){
            return $this->auto($request);
        }
        $assigndate =AssignStage::where('user_id',$request->user_id)
                     ->orderby('assigndate','DESC')->pluck('assigndate')->first();

         $fromdate =AssignStage::where('user_id',$request->user_id)
                     ->pluck('fromdate')->first();   
         $todate =AssignStage::where('user_id',$request->user_id)
                     ->pluck('todate')->first();
         $rmc =AssignStage::where('user_id',$request->user_id)->pluck('rmc')->first();
         $auto = AssignStage::where('user_id',$request->user_id)->pluck('auto')->first();
         $bank = AssignStage::where('user_id',$request->user_id)->pluck('bank')->first();
         $Premium = AssignStage::where('user_id',$request->user_id)->pluck('Premium')->first();
         $door = AssignStage::where('user_id',$request->user_id)->pluck('door')->first();
         $upvc = AssignStage::where('user_id',$request->user_id)->pluck('upvc')->first();
         $brila = AssignStage::where('user_id',$request->user_id)->pluck('brila')->first();
          $listing = AssignStage::where('user_id',$request->user_id)->pluck('listid')->first();

         $project_type = AssignStage::where('user_id',$request->user_id)->pluck('project_type')->first();

         $projectSize = AssignStage::where('user_id',$request->user_id)->pluck('project_size')->first();

         $budget = AssignStage::where('user_id',$request->user_id)->pluck('budget')->first();

         $tlWard = AssignStage::where('user_id',$request->user_id)->pluck('ward')->first();


         $ward = Ward::where('ward_name',$tlWard)->pluck('id')->first();
         $assignedSubWards = SubWard::where('ward_id',$ward)->pluck('id');

         // dd($ward."<br>".$assignedSubWards);

        $subwards = AssignStage::where('user_id',$request->user_id)->pluck('subward')->first();
        
        $subwardNames = explode(", ", $subwards);

         if($subwards != "null"){
            $subwardid = Subward::whereIn('sub_ward_name',$subwardNames)->pluck('id')->toArray();
         }else{
            $subwardid = $assignedSubWards;
         }


        $builder = AssignStage::where('user_id',$request->user_id)->pluck('builder')->first();

        $bud = AssignStage::where('user_id',$request->user_id)->pluck('budget_type')->first();

        $lab = AssignStage::where('user_id',$request->user_id)->pluck('contract_type')->first();

        $constraction = AssignStage::where('user_id',$request->user_id)->pluck('constraction_type')->first();


        $ground = AssignStage::where('user_id',$request->user_id)->pluck('Floor')->first();

        $basement = AssignStage::where('user_id',$request->user_id)->pluck('basement')->first();

        $date =  AssignStage::where('user_id',$request->user_id)->pluck('assigndate')->first();

        $budgetto = AssignStage::where('user_id',$request->user_id)->pluck('budgetto')->first();

        $total = AssignStage::where('user_id',$request->user_id)->pluck('total')->first();

        $base1 = AssignStage::where('user_id',$request->user_id)->pluck('base')->first();

        $Floor2 = AssignStage::where('user_id',$request->user_id)->pluck('Floor2')->first();

        $projectsize1 = AssignStage::where('user_id',$request->user_id)->pluck('projectsize')->first();

        $quality = AssignStage::where('user_id',$request->user_id)->pluck('quality')->first();
        $roomtypes = RoomType::all();
        $projectids = new Collection();
        $projects = ProjectDetails::whereIn('sub_ward_id',$subwardid)->pluck('project_id');
        
        
         if(count($projects) > 0){
            $projectids = $projectids->merge($projects);
        }

        if(count($projectids) != 0){
            $budgettypes = ProjectDetails::whereIn('project_id',$projectids)->where('budgetType',"LIKE","%".$bud."%")->pluck('project_id');
        }else{
            $budgettypes = ProjectDetails::where('budgetType',"LIKE","%".$bud."%")->pluck('project_id');
        }
        if(count($budgettypes) != 0){
            $projectids = $budgettypes;
        }
        if($projectSize != null){
            $project_sizes = new Collection;
            if(count($projectids) != 0){
                $projectSize = floatval($projectSize);
                for($i = 0; $i < count($projectids); $i++){
                    $get_project = ProjectDetails::where('project_id',$projectids[$i])->first();
                    if(round($get_project->project_size) >= $projectSize && round($get_project->project_size) <= $projectsize1){
                        $project_sizes = $project_sizes->merge($get_project->project_id);
                    }
                }
            }else{
                $get_project = ProjectDetails::get();
                foreach($get_project as $project){
                    if(round($project->project_size) >= $projectSize && round($project->project_size) <= $projectsize1){
                        $project_sizes = $project_sizes->merge($project->project_id);
                    }
                }
                }
                if(count($project_sizes) != 0){
                    $projectids = $project_sizes;
                }
            }
            $contractInt = explode(",", $lab);
            if($contractInt[0] != "null"){
                if(count($projectids) != 0){
                    $labc = ProjectDetails::whereIn('project_id',$projectids)->where('contract',$contractInt)->pluck('project_id');
                }else{
                    $labc = ProjectDetails::where('contract',$contractInt)->pluck('project_id');
                }
                if(count($labc) != 0){
                    $projectids = $labc;
                }
            }
            if($ground != null){
                if(count($projectids) != 0){
                    $grd = ProjectDetails::whereIn('project_id',$projectids)->where('ground','<=',$ground  !=null ? $ground :0 )->where('ground','>=',$Floor2  !=null ? $Floor2 :0 )->pluck('project_id');
                }else{
                    $grd = ProjectDetails::where('ground','>=',$ground  !=null ? $ground :0 )->where('ground','<=',$Floor2  !=null ? $Floor2 :0 )->pluck('project_id');
                }
                if(Count($grd) > 0){
                    $projectids = $grd;
                }
            }
   
         
            if($basement != null){
                if(count($projectids) != 0){
                    $base = ProjectDetails::whereIn('project_id',$projectids)->where('basement', '<=',$basement !=null ? $basement :0 )->where('basement', '>=',$base1 !=null ? $base1 :0 )->pluck('project_id');
                }else{
                    $base = ProjectDetails::where('basement', '<=',$basement !=null ? $basement :0 )->where('basement', '>=',$base1 !=null ? $base1 :0 )->pluck('project_id');
                }
                if(Count($base) > 0){
                    $projectids = $base;
                }
            }
            $stage = AssignStage::where('user_id',$request->user_id)->pluck('stage')->first();
            $stagec = explode(", ", $stage);
            $projectsat = new Collection;
            if($stagec[0] != "null"){
                if(count($projectids) != 0){
                    for($i = 0; $i<count($stagec); $i++)
                    {
                        $projectsats = ProjectDetails::whereIn('project_id',$projectids)->where('project_status' ,'LIKE', "%".$stagec[$i]."%")->pluck('project_id');
                        $projectsat = $projectsat->merge($projectsats);
                    }
                }else{
                    for($i = 0; $i<count($stagec); $i++)
                    {
                        $projectsat = ProjectDetails::where('project_status' ,'LIKE', "%".$stagec[$i]."%")->pluck('project_id');
                    }
                }
                if(count($projectsat) != 0){
                    $projectids = $projectsat;
                }
            }
            $sta = explode(", ", $constraction);
            if($sta[0] != "null"){
                if(count($projectids) != 0){
                    for($i=0;$i<count($sta);$i++){

                        $cons =ProjectDetails::whereIn('project_id',$projectids)->where('construction_type','LIKE', "%".$sta[$i]."%")->pluck('project_id');
                    }
                }
                else{
                    for($i=0;$i<count($sta);$i++){
                        $cons =ProjectDetails::where('construction_type','LIKE', "%".$sta[$i]."%")->pluck('project_id');
                    }
                }
                   
                if(Count($cons) > 0){
                    $projectids = $cons;
                }
            }
            if(count($date) != 0){
                if(count($projectids) != 0){
                    $datec = ProjectDetails::whereIn('project_id',$projectids)->where('created_at','LIKE' ,$date."%")->pluck('project_id');
                }else{
                    $datec = ProjectDetails::where('created_at','LIKE' ,$date."%")->pluck('project_id');
                }
                if($datec != null){
                    $projectids = $datec;
                }
            }
    

            $rmcInt = explode(",", $rmc);
            if($rmcInt[0] != "null"){
                if(count($projectids) != 0){
                    $rmcs = ProjectDetails::whereIn('project_id',$projectids)->whereIn('interested_in_rmc',$rmcInt)->pluck('project_id');
                }else{
                    $rmcs = ProjectDetails::whereIn('interested_in_rmc',$rmcInt)->pluck('project_id');
                }
                if(count($rmcs) != 0){
                    $projectids = $rmcs;
                }
            }

           $autoInt = explode(",", $auto);
            if($autoInt[0] != "null"){
                if(count($projectids) != 0){
                    $autos = ProjectDetails::whereIn('project_id',$projectids)->whereIn('automation',$autoInt)->pluck('project_id');
                }else{
                    $autos = ProjectDetails::whereIn('automation',$autoInt)->pluck('project_id');
                }
                if(count($autos) != 0){
                    $projectids = $autos;
                }
            }

         $doorInt = explode(",", $door);
            if($doorInt[0] != "null"){
                if(count($projectids) != 0){
                    $doors = ProjectDetails::whereIn('project_id',$projectids)->whereIn('Kitchen_Cabinates',$doorInt)->pluck('project_id');
                }else{
                    $doors = ProjectDetails::whereIn('Kitchen_Cabinates',$doorInt)->pluck('project_id');
                }
                if(count($doors) != 0){
                    $projectids = $doors;
                }
            }

$upvcInt = explode(",", $upvc);
            if($upvcInt[0] != "null"){
                if(count($projectids) != 0){
                    $upvcs = ProjectDetails::whereIn('project_id',$projectids)->whereIn('interested_in_doorsandwindows',$upvcInt)->pluck('project_id');
                }else{
                    $upvcs = ProjectDetails::whereIn('interested_in_doorsandwindows',$upvcInt)->pluck('project_id');
                }
                if(count($upvcs) != 0){
                    $projectids = $upvcs;
                }
            }

        $brilaInt = explode(",", $brila);
            if($brilaInt[0] != "null"){
                if(count($projectids) != 0){
                    $brilas = ProjectDetails::whereIn('project_id',$projectids)->whereIn('brilaultra',$brilaInt)->pluck('project_id');
                }else{
                    $brilas = ProjectDetails::whereIn('brilaultra',$brilaInt)->pluck('project_id');
                }
                if(count($brilas) != 0){
                    $projectids = $brilas;
                }
            }

 $bankInt = explode(",", $bank);
            if($bankInt[0] != "null"){
                if(count($projectids) != 0){
                    $banks = ProjectDetails::whereIn('project_id',$projectids)->whereIn('interested_in_loan',$bankInt)->pluck('project_id');
                }else{
                    $banks = ProjectDetails::whereIn('interested_in_loan',$bankInt)->pluck('project_id');
                }
                if(count($banks) != 0){
                    $projectids = $banks;
                }
            }

 $PremiumInt = explode(",", $Premium);
            if($PremiumInt[0] != "null"){
                if(count($projectids) != 0){
                    $Premiums = ProjectDetails::whereIn('project_id',$projectids)->whereIn('interested_in_premium',$PremiumInt)->pluck('project_id');

                }else{
                    $Premiums = ProjectDetails::whereIn('interested_in_premium',$PremiumInt)->pluck('project_id');
                }
                if(count($Premiums) != 0){
                    $projectids = $Premiums;
                }
            }



 if(count($projectids) != 0){
                $project_types = ProjectDetails::whereIn('project_id',$projectids)->where('project_type', '>=',$project_type !=null ? $project_type :0 )->where('project_type', '<=',$total !=null ? $total :0 )->pluck('project_id');
            }else{
                $project_types = ProjectDetails::where('project_type', '>=',$project_type !=null ? $project_type :0 )->where('project_type', '<=',$total !=null ? $total :0 )->pluck('project_id');
            }
            if(count($project_types) != 0){
                $projectids = $project_types;
            }

            if($budget != null){
                if(count($projectids) != 0){
                    $budgets = ProjectDetails::whereIn('project_id',$projectids)->where('budget','>=',$budget != null ? $budget : 0 )->where('budget','<=',$budgetto != null ? $budgetto : 0 )->pluck('project_id');
                }else{
                    $budgets = ProjectDetails::where('budget','>=',$budget != null ? $budget : 0 )->where('budget','<=',$budgetto != null ? $budgetto : 0 )->pluck('project_id');
                }
                if(count($budgets) > 0){
                    $projectids = $budgets;
                }
            }

            if( $quality != null){
                if(count( $projectids) != 0){
                    $qua = ProjectDetails::whereIn('project_id',$projectids)->where('quality', $quality )->pluck('project_id');
                }else{
                    $qua = ProjectDetails::where('quality', $quality )->pluck('project_id');
                }

                if(count($qua) > 0){
                    $projectids = $qua;
                }
            }
              if($builder != null){
                if(count( $projectids) != 0){
                    $builders = Builder::whereIn('project_id',$projectids)->where('builder_name','!=',NULL)->pluck('project_id');
                }else{
                    $builders = Builder::where('builder_name','!=',NULL)->pluck('project_id');
                }

                if(count($builders) > 0){
                    $projectids = $builders;
                }
              }
            
             if($listing != null){
                if(count( $projectids) != 0){
                    $list = ProjectDetails::whereIn('project_id',$projectids)->where('listing_engineer_id',$listing)->pluck('project_id');
                }else{
                    $list = ProjectDetails::where('listing_engineer_id',$listing)->pluck('project_id');
                }

                if(count($list) > 0){
                    $projectids = $list;
                }
              }

           





         // if($undate != null){
         //        if(count( $projectids) != 0){
         //            $qdate = ProjectDetails::whereIn('project_id',$projectids)
         //            ->where('updated_at','<=',$undate)->pluck('project_id');
         //        }else{
         //            $qdate = ProjectDetails::where('updated_at','<=',$undate )->pluck('project_id');
         //        }

         //        if(count($qdate) > 0){
         //            $projectids = $qdate;
         //        }
         //    }
            if($fromdate != null){
                if(count( $projectids) != 0){
                    $qdate = ProjectDetails::
                        whereIn('project_id',$projectids)
                        ->wheredate('created_at','>=',$fromdate)
                        ->wheredate('created_at','<=',$todate)
                        ->whereColumn('created_at','updated_at')
                        ->pluck('project_id');
                }else{
                    $qdate = ProjectDetails::wheredate('created_at','>=',$fromdate)
                        ->wheredate('created_at','<=',$todate)
                        ->pluck('project_id');
                }
                if(count($qdate) > 0){
                    $projectids = $qdate;
                }
            }
         $dd = ProjectDetails::getcustomer(); 
          

     $count = count($projectids); 

      $array = $dd['project']->toarray();
     $project = ProjectDetails::where('type',NULL)
                     ->where('quality','!=',"Fake")
                    ->where('project_status','NOT LIKE','%Closed%')
                    ->whereIn('project_id',$projectids)
                     ->whereNotIn('project_id',$array)
                    ->pluck('project_id');
     
     AssignStage::where('user_id',$request->user_id)->update(['projectids'=>$project]);

 return redirect()->back()->with('success',count($project).'Projects Assigned Successfully');
}

public function projectstore1(request $request){
    $check = AssignStage::where('user_id',$request->user_id)->first();

       if(count($check) != 0){
        $check->time = $request->settime;
        $check->instruction = $request->inc;
        $check->save();

}
      return redirect()->back()->with('success',' Assigned Successfully');
}
public function reject(request $request){
    $check = AssignStage::where('user_id',$request->user_id)->first();
    
       if(count($check) != 0){
     $check->remark = $request->remark;
     $check->adate = $request->date;
     $check->time = $request->time;
        $check->save();
}
 return redirect()->back();
}


public function enquirywise(request $request){

        if(Auth::user()->group_id != 22){
            return $this->enquirywise1($request);
        }

           $ss= Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
           $tlward =explode(",",$ss);
           $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
          $userIds = explode(",", $tl);

         $wards = Ward::whereIn('wards.id',$tlward)->get();
         $depts = [17,6,7,11];
         $wardsAndSub = []; 
         $users = User::whereIn('users.group_id',$depts)
                    ->where('department_id','!=',10)
                      ->leftjoin('departments','departments.id','users.department_id')
                      ->leftjoin('groups','groups.id','users.group_id')
                      ->select('users.*','departments.dept_name','groups.group_name')->paginate(20);
           $tlUsers = User::whereIn('users.id',$userIds)
                      ->leftjoin('departments','departments.id','users.department_id')
                      ->leftjoin('groups','groups.id','users.group_id')
                      ->select('users.*','departments.dept_name','groups.group_name')->paginate(20);
                        $category = Category::all();
                        $brands = brand::all();
                        $sub = SubCategory::all();
                        foreach($wards as $ward){
                            $subward = SubWard::where('ward_id',$ward->id)->get();
                            array_push($wardsAndSub,['ward'=>$ward->id,'subwards'=>$subward]);
                        }
    return view('/assign_enquiry',['wardsAndSub'=>$wardsAndSub, 'users'=>$users,'sub'=>$sub,'wards'=> $wards,'category'=>$category,'brands'=>$brands,'tlUsers'=>$tlUsers ]);
}

public function enquirywise1(request $request){

 $depts = [17,6,7,11];
 $wardsAndSub = [];
 $users = User::whereIn('users.group_id',$depts)
              ->where('department_id','!=',10)
              ->leftjoin('departments','departments.id','users.department_id')
              ->leftjoin('groups','groups.id','users.group_id')
              ->select('users.*','departments.dept_name','groups.group_name')->paginate(20);

   $wards = Ward::all();
                $category = Category::all();
                $brands = brand::all();
                $sub = SubCategory::all();
    foreach($wards as $ward){
        $subward = SubWard::where('ward_id',$ward->id)->get();
        array_push($wardsAndSub,['ward'=>$ward->id,'subwards'=>$subward]);
    }
    return view('/assign_enquiry',['wardsAndSub'=>$wardsAndSub, 'users'=>$users,'sub'=>$sub,'wards'=> $wards,'category'=>$category,'brands'=>$brands ]);
}




function enquirystore(request $request){

    if($request->ward){
    $wards = implode(", ", $request->ward);
    }else{
        if(Auth::user()->group_id == 22){
            $ss = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
            $tlWard =explode(",",$ss);
            $ward = Ward::whereIn('id',$tlWard)->pluck('ward_name');
            $wards = $ward;
           
        }else{
            $wards = "null";
        }
    }
    if($request->subward )
    {
    $subwards = implode(", ", $request->subward);

    } else{
        $subwards = "null";
    }
    if($request->cat){
        $cat = implode(",", $request->cat);
    }else{
        $cat = "null";
    }
    if($request->brand){
        $brand = implode(",", $request->brand);
    }else{
        $brand = "null";
    }

    // if($request->subcat){
    //     $sub = implode(",",$request->subcat);
    // }else{
    //     $sub = "null";
    // }
        $check = Assignenquiry::where('user_id',$request->user_id)->first();
        if($check == null){
            $enquiry = new Assignenquiry;
            $enquiry ->user_id = $request->user_id;
            $enquiry->ward = $wards;
            $enquiry->subward = $subwards;
            $enquiry->cat =$cat;
            $enquiry->brand=$brand;
            $enquiry->dateenq=$request->dateenq;
            // $enquiry->sub=$sub;
            $enquiry->save();
        }else{
            $check->ward = $wards;
            $check->subward = $subwards;

            $check->cat =$cat;
            $check->brand=$brand;
            $check->dateenq=$request->dateenq;
            // $check->sub=$sub;
            $check->save();
        }
        return redirect()->back()->with('message','Enquiry Assigned Successfully');
    }
  public function enqwise(Request $request){
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $assigndate =Assignenquiry::where('user_id',Auth::user()->id)
                       ->orderby('dateenq','DESC')->pluck('dateenq')->first();
         $tlWard = Assignenquiry::where('user_id',Auth::user()->id)->pluck('ward')->first();
         $ward = Ward::where('ward_name',$tlWard)->pluck('id')->first();
         $assignedSubWards = SubWard::where('ward_id',$ward)->pluck('id');

         // dd($ward."<br>".$assignedSubWards);

       $subwards = Assignenquiry::where('user_id',Auth::user()->id)->pluck('subward');

        $subwardNames = explode(", ", $subwards);
        $subwardid = Subward::whereIn('sub_ward_name',$subwardNames)->pluck('id')->toArray();

         if($subwardid != "null"){
             $subwardid = $assignedSubWards;
         }

         $cat =  Assignenquiry::where('user_id',Auth::user()->id)->pluck('cat')->first();
         $brand = Assignenquiry::where('user_id',Auth::user()->id)->pluck('brand');
         $sub = Assignenquiry::where('user_id',Auth::user()->id)->pluck('sub');
       
        $projectids = new Collection();
         //feching wardwise
        $project = ProjectDetails::leftjoin('requirements','requirements.project_id','project_details.project_id')
                    ->whereIn('project_details.sub_ward_id',$subwardid)
                    ->pluck('requirements.id');

        if(count($project) > 0){
            $projectids = $projectids->merge($project);

        }
       


        $catc = explode(", ", $cat);
            $catlist = new Collection;
        if($catc[0] != ""){
            for($i = 0; $i<count($catc); $i++)
            {
                if(count($projectids) != 0){
                    $category = Requirement::whereIn('id',$projectids)->where('main_category' ,'LIKE', $catc[$i]."%")->pluck('id');

                    $catlist = $catlist->merge($category);

                }else{
                    $category = Requirement::where('main_category' ,'LIKE', "%".$catc[$i]."%")->pluck('id');
                    $catlist = $catlist->merge($category);

                }
            }
            $projectids = $projectids->merge($catlist);
        }
           
        $brandc = $brand->toArray();
       $brandlist = new Collection;


        if(count($projectids) != 0){
            for($i = 0; $i<count($brandc); $i++)
            {
                $brands = Requirement::whereIn('id',$projectids)->where('brand' ,'LIKE', "%".$brandc[$i]."%")->pluck('id');

                $brandlist = $brandlist->merge($brands);
            }
        }else{
            for($i = 0; $i<count($brandc); $i++)
            $brandlist= Requirement::where('brand' ,'LIKE', "%".$brandc[$i]."%")->pluck('id');

        }
         $projectids = $projectids->merge($brandlist);
        if( $assigndate != NULL){
            if(count($projectids) != 0)
                $datec = Requirement::whereIn('id',$projectids)->where('created_at','LIKE' , $assigndate."%")->pluck('id');
            else
                $datec = Requirement::where('created_at','LIKE' , $assigndate."%")->pluck('id');
            $projectids=$projectids->merge($datec);
        }
        
      
          
      
     

        $manuids = new Collection();
         //feching wardwise
        $project = Manufacturer::leftjoin('requirements','requirements.manu_id','manufacturers.id')
                    ->whereIn('manufacturers.sub_ward_id',$subwardid)
                    ->pluck('requirements.id');
        
        if(count($project) > 0){
            $manuids = $manuids->merge($project);

        }
        $catc = explode(", ", $cat);

        if($catc[0] != ""){
            for($i = 0; $i<count($catc); $i++)
            {
                if(count($manuids) != 0){
                    $category = Requirement::whereIn('id',$manuids)->where('main_category' ,'LIKE', $catc[$i]."%")->pluck('id');

                }else{
                    $category = Requirement::where('main_category' ,'LIKE', "%".$catc[$i]."%")->pluck('id');
                }
            }
            $manuids = $manuids->merge($category);
        }
        $brandc = $brand->toArray();
       $brandlist = new Collection;


        if(count($manuids) != 0){
            for($i = 0; $i<count($brandc); $i++)
            {
                $brands = Requirement::whereIn('id',$manuids)->where('brand' ,'LIKE', "%".$brandc[$i]."%")->pluck('id');

                $brandlist = $brandlist->merge($brands);
            }
        }else{
            for($i = 0; $i<count($brandc); $i++)
            $brandlist= Requirement::where('brand' ,'LIKE', "%".$brandc[$i]."%")->pluck('id');

        }
         $manuids = $manuids->merge($brandlist);
        if( $assigndate != NULL){
            if(count($manuids) != 0)
                $datec = Requirement::whereIn('id',$manuids)->where('created_at','LIKE' , $assigndate."%")->pluck('id');
            else
                $datec = Requirement::where('created_at','LIKE' , $assigndate."%")->pluck('id');
            $manuids=$manuids->merge($datec);
        }
        


      
      $p = Requirement::whereIn('id',$projectids)->pluck('id')->toarray();
      $m = Requirement::whereIn('id',$manuids)->pluck('id')->toarray();

       $final =array_merge($p,$m);

       
 
              $dd = ProjectDetails::getcustomer();

        // $enq = Requirement:: where('status','=',"Enquiry On Process")->pluck('project_id');
           $projects = Requirement::whereIn('requirements.id',$final)
                         
                         ->where('requirements.status','=',"Enquiry On Process")
                        ->leftjoin('procurement_details','requirements.project_id', '=' ,'procurement_details.project_id')
                        ->select('requirements.*','procurement_details.procurement_contact_no')
                        ->paginate(20);
      
               

       if(Auth::user()->group_id == 23){

              if($request->salesenq){
                $ac = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
             $catsub = Category::where('id',$ac)->pluck('category_name')->first();
               $enq = Requirement::where('generated_by',Auth::user()->id)->where('main_category',$catsub)->pluck('project_id');

              $projects = Requirement::where('requirements.main_category',$catsub)
                         ->whereIn('requirements.project_id',$enq)
                         ->where('requirements.status','=',"Enquiry On Process")
                        ->leftjoin('procurement_details','requirements.project_id', '=' ,'procurement_details.project_id')
                        ->select('requirements.*','procurement_details.procurement_contact_no')
                        ->paginate(20);

              }elseif($request->salesenq1){
                  $date = date('Y-m-d', strtotime('-30 days'));
                $ac = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
                $catsub = Category::where('id',$ac)->pluck('category_name')->first();
                $enq = Requirement::where('generated_by',Auth::user()->id)->where('main_category',$catsub)->pluck('project_id');
                $projects = Requirement::where('requirements.main_category',$catsub)
                         ->whereIn('requirements.project_id',$enq)
                         ->where('requirements.status','=',"Enquiry On Process")
                         ->where('requirements.created_at','>=',$date)
                        ->leftjoin('procurement_details','requirements.project_id', '=' ,'procurement_details.project_id')
                        ->select('requirements.*','procurement_details.procurement_contact_no')
                        ->paginate(20);

              }
              else{
             $ac = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
             $catsub = Category::where('id',$ac)->pluck('category_name')->first();

            $projects = Requirement::where('requirements.main_category',$catsub)
                         ->where('requirements.status','=',"Enquiry On Process")
                        ->leftjoin('procurement_details','requirements.project_id', '=' ,'procurement_details.project_id')
                        ->select('requirements.*','procurement_details.procurement_contact_no')
                        ->paginate(20);
                    }
              }


        return view('enquirywise',['projects'=>$projects,'log'=>$log,'$log1'=>$log1]);
    }
    public function viewMap(Request $request)
    {
        $wards = Ward::where('zone_id',$request->zoneId)->pluck('id');
        $zone_id = $request->zoneId;
        if($request->zoneId == 1){
        $zones = SubWardMap::leftJoin('sub_wards','sub_wards.id','sub_ward_maps.sub_ward_id')
                    ->where('sub_wards.zone_id',1)
                    ->where('sub_ward_maps.zone_id',1)
                    ->select('sub_ward_maps.*','sub_wards.sub_ward_name as name')
                    ->get();
        }
        else{ 
        $zones = SubWardMap::leftJoin('sub_wards','sub_wards.id','sub_ward_maps.sub_ward_id')
                    ->where('sub_wards.zone_id',2)
                    ->where('sub_ward_maps.zone_id',2)
                    ->select('sub_ward_maps.*','sub_wards.sub_ward_name as name')
                    ->get();
        }
        if($request->wardId){
            $wards = SubWard::where('ward_id',$request->wardId)->pluck('id');
            $zones = SubWardMap::whereIn('sub_wards.id',$wards)
                    ->leftJoin('sub_wards','sub_wards.id','sub_ward_maps.sub_ward_id')
                    ->select('sub_ward_maps.*','sub_wards.sub_ward_name as name')
                    ->get();
        }
        if($request->subWardId){
            // $wards = SubWard::where('ward_id',$request->subWardId)->pluck('id');
            $zones = SubWardMap::where('sub_wards.id',$request->subWardId)
                    ->leftJoin('sub_wards','sub_wards.id','sub_ward_maps.sub_ward_id')
                    ->select('sub_ward_maps.*','sub_wards.sub_ward_name as name')
                    ->get();
        }
        if($request->allSubwards){
            $zones = SubWardMap::leftJoin('sub_wards','sub_wards.id','sub_ward_maps.sub_ward_id')
                    ->select('sub_ward_maps.*','sub_wards.sub_ward_name as name')
                    ->get();
        }
        return view('maping.viewmap',['zones'=>$zones,'zone_id'=>$zone_id]);
    }
    public function allProjectsWithWards(Request $request)
    {

        $wardMaps = null;
        $projects = null;
        if($request->wards && $request->quality && $request->type == "Project"){

            $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
            $wardMaps = WardMap::where('ward_id',$request->wards)->first();
            if($wardMaps == null ){
                $wardMaps = "None";
            }
            $projects = ProjectDetails::leftJoin('site_addresses','project_details.project_id','site_addresses.project_id')
                        ->select('site_addresses.*','project_details.quality')
                        ->where('project_details.quality',$request->quality)
                        ->whereIn('project_details.sub_ward_id',$subwards)
                        ->get();
        }else{
            $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
            $wardMaps = WardMap::where('ward_id',$request->wards)->first();
            if($wardMaps == null ){
                $wardMaps = "None";
            }
            $projects = Manufacturer::where('quality',$request->quality)
                        ->whereIn('sub_ward_id',$subwards)
                        ->get();
        }
        

        $wards = Ward::all();
        $zone = Zone::all();
        return view('maping.allProjectsWithWards',['wardMaps'=>$wardMaps,'projects'=>$projects,'wards'=>$wards,'zone'=>$zone]);
    }

    public function storecount(request $request){

    $check = numbercount::where('user_id',$request->user_id)->first();
    $numberexist = numbercount::where('num',$request->num)->first();
    if($numberexist != null){
        $userName = User::where('id',$numberexist->user_id)->pluck('name')->first();
        $text = "These numbers are Already Assigned to ".$userName;
        return back()->with('NotAdded',$text);
    }
            if($check == null){
                $number = new numbercount;
                $number ->user_id = $request->user_id;
                $number->num = $request->num;
                $number->save();
            }else{
                $check->num=$request->num;
                $check->save();
            }
            return redirect()->back()->with('success','Phone Numbers Assigned');
    }

       public function sms(request $request){

        $users = User::all();;
        $ss = numbercount::where('user_id',Auth::user()->id)->pluck('num')->first();

        $num =MamaSms::all();
        $numbers = explode(", ",$ss);

                                  $data = ProcurementDetails::select('procurement_contact_no')->whereIn('procurement_contact_no',$numbers)->distinct()->paginate(100);
        
        return view('/sms',['users'=>$users,'ss'=>$ss,'num'=>$num,'data'=>$data]);
    }
    
    public function payment( request $request){

       $payment = Payment::all();
       $pay = Payment::all()->count();
       $converter = user::all();

        return view('/payment',['payment'=>$payment,'pay'=>$pay,'converter'=>$converter]);
    }
public function display(request $request){
    
        $wardMaps = null;
        $projects = null;
        if($request->wards && $request->quality){
            $subwards = SubWard::where('ward_id',$request->wards)->pluck('id')->toArray();
            $wardMaps = WardMap::where('ward_id',$request->wards)->first();
            if($wardMaps == null){
                $wardMaps = "None";
            }
            $projects = ProjectDetails::leftJoin('site_addresses','project_details.project_id','site_addresses.project_id')
                        ->select('site_addresses.*','project_details.quality')
                        ->where('project_details.quality',$request->quality)
                        ->whereIn('project_details.sub_ward_id',$subwards)
                        ->get();
        }
        $wards = Ward::all();
        $war = WardMap::all();

        return view('maping.map',['wardMaps'=>$wardMaps,'projects'=>$projects,'wards'=>$wards,'war'=>$war]);


}
// public function getProjection(Request $request)
    // public function sendSMS(Request $request)
    // {
        // $nexmo = app('Nexmo\Client');
        // if($request->number && $request->content){
            // $nexmo->message()->send([
            //     'to'   => $request->number,
            //     'from' => "MAMAHOME",
            //     'text' => $request->content
            // ]);
            // return redirect('/sendSMS');
        // }
    //     return view('sendSMS');
    // }
    public function getProjection(Request $request)
    {
        $conversions = Conversion::all();
        if($request->category){
            
            $conversion = Conversion::where('id',$request->category)->first();
            $utilizations = Utilization::where('id',$request->category)->first();
            View::share('conversion', $conversion);
            View::share('utilization',$utilizations);
        }else{
            View::share('conversion',null);
        }
       
        $wards = Ward::all();
       $qualityCheck = ['Genuine','Fake','Unverified'];
       // getting total no of projects
       $wardsselect = Ward::pluck('id');
       $subwards = SubWard::whereIn('ward_id',$wardsselect)->pluck('id');
       $planningCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Planning%')->count();
       $planningSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Planning%')->sum('project_size');
       $foundationCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Foundation%')->count();
       $foundationSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Foundation%')->sum('project_size');
       $roofingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Roofing%')->count();
       $roofingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Roofing%')->sum('project_size');
       $wallsCount         = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Walls%')->count();
       $wallsSize          = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Walls%')->sum('project_size');
       $completionCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Completion%')->count();
       $completionSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Completion%')->sum('project_size');
       $fixturesCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Fixtures%')->count();
       $fixturesSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Fixtures%')->sum('project_size');
       $pillarsCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Pillars%')->count();
       $pillarsSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Pillars%')->sum('project_size');
       $paintingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Paintings%')->count();
       $paintingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Paintings%')->sum('project_size');
       $flooringCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Flooring%')->count();
       $flooringSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Flooring%')->sum('project_size');
       $plasteringCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Plastering%')->count();
       $plasteringSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Plastering%')->sum('project_size');
       $diggingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Digging%')->count();
       $diggingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Digging%')->sum('project_size');
       $ele                = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Electrical%')->pluck('project_id');
       $plum               = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
       $ele                = $ele->merge($plum);
       $enpCount           = ProjectDetails::whereIn('project_id',$ele)->count();
       $enpSize            = ProjectDetails::where('project_id',$ele)->sum('project_size');
       $carpentryCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Carpentry%')->count();
       $carpentrySize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Carpentry%')->sum('project_size');
       $closedCount        = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Closed%')->count();
       $closedSize         = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Closed%')->sum('project_size');

       $totalProjects = $planningCount + $diggingCount + $foundationCount + $pillarsCount + $completionCount + $fixturesCount + $paintingCount + $carpentryCount + $flooringCount + $plasteringCount + $enpCount + $roofingCount + $wallsCount + $closedCount;

       if($request->ward && !$request->subward){

           if($request->ward == "All"){
               $planningCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->count();
               $planningSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->sum('project_size');
               $foundationCount    = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->count();
               $foundationSize     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->sum('project_size');
               $roofingCount       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->count();
               $roofingSize        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->sum('project_size');
               $wallsCount         = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->count();
               $wallsSize          = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->sum('project_size');
               $completionCount    = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->count();
               $completionSize     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->sum('project_size');
               $fixturesCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->count();
               $fixturesSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->sum('project_size');
               $pillarsCount       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->count();
               $pillarsSize        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->sum('project_size');
               $paintingCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->count();
               $paintingSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->sum('project_size');
               $flooringCount      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->count();
               $flooringSize       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->sum('project_size');
               $plasteringCount    = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->count();
               $plasteringSize     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->sum('project_size');
               $diggingCount       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->count();
               $diggingSize        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->sum('project_size');
               $ele                = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->pluck('project_id');
               $plum               = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
               $ele                = $ele->merge($plum);
               $enpCount           = ProjectDetails::whereIn('project_id',$ele)->count();
               $enpSize            = ProjectDetails::whereIn('project_id',$ele)->sum('project_size');
               $carpentryCount     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->count();
               $carpentrySize      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->sum('project_size');
               $closedCount        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->count();
               $closedSize         = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->sum('project_size');
               $wardname = "All";
               $subwards = SubWard::all();
           }else{
               $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
               $planningCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->count();
               $planningSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->sum('project_size');
              
               $foundationCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->count();
               $foundationSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->sum('project_size');
               $roofingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->count();
               $roofingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->sum('project_size');
               $wallsCount         = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->count();
               $wallsSize          = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->sum('project_size');
               $completionCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->count();
               $completionSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->sum('project_size');
               $fixturesCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->count();
               $fixturesSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->sum('project_size');
               $pillarsCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->count();
               $pillarsSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->sum('project_size');
               $paintingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->count();
               $paintingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->sum('project_size');
               $flooringCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->count();
               $flooringSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->sum('project_size');
               $plasteringCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->count();
               $plasteringSize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->sum('project_size');
               $diggingCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->count();
               $diggingSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->sum('project_size');
               $ele                = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->pluck('project_id');
               $plum               = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
               $ele                = $ele->merge($plum);
               $enpCount           = ProjectDetails::whereIn('project_id',$ele)->count();
               $enpSize            = ProjectDetails::whereIn('project_id',$ele)->sum('project_size');
               $carpentryCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->count();
               $carpentrySize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->sum('project_size');
               $closedCount        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->count();
               $closedSize         = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->sum('project_size');
               $wardname = Ward::where('id',$request->ward)->first();
               $subwards = SubWard::where('ward_id',$request->ward)->get();
           }
        
           return view('projection',[
               'planningCount'=>$planningCount,'planningSize'=>$planningSize,
               'foundationCount'=>$foundationCount,'foundationSize'=>$foundationSize,
               'roofingCount'=>$roofingCount,'roofingSize'=>$roofingSize,
               'wallsCount'=>$wallsCount,'wallsSize'=>$wallsSize,
               'completionCount'=>$completionCount,'completionSize'=>$completionSize,
               'fixturesCount'=>$fixturesCount,'fixturesSize'=>$fixturesSize,
               'pillarsCount'=>$pillarsCount,'pillarsSize'=>$pillarsSize,
               'paintingCount'=>$paintingCount,'paintingSize'=>$paintingSize,
               'flooringCount'=>$flooringCount,'flooringSize'=>$flooringSize,
               'plasteringCount'=>$plasteringCount,'plasteringSize'=>$plasteringSize,
               'diggingCount'=>$diggingCount,'diggingSize'=>$diggingSize,
               'enpCount'=>$enpCount,'enpSize'=>$enpSize,
               'carpentryCount'=>$carpentryCount,'carpentrySize'=>$carpentrySize,
               'closedSize'=>$closedSize,'closedCount'=>$closedCount,
               'wards'=>$wards,
               'wardname'=>$wardname,'conversions'=>$conversions,
               'subwards'=>$subwards,'wardId'=>$request->ward,'planning'=>NULL,'subwardId'=>NULL,'subwardName'=>NULL,'totalProjects' => $totalProjects
           ]);
       }
       if($request->subward){
           $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
           $subwardQuality = ['Genuine','Fake','Unverified'];
           $planningCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->count();
           $planningSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->sum('project_size');
           $foundationCount   = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->count();
           $foundationSize    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->sum('project_size');
           $roofingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->count();
           $roofingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->sum('project_size');
           $wallsCount        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->count();
           $wallsSize         = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->sum('project_size');
           $completionCount   = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->count();
           $completionSize    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->sum('project_size');
           $fixturesCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->count();
           $fixturesSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->sum('project_size');
           $pillarsCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->count();
           $pillarsSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->sum('project_size');
           $paintingCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->count();
           $paintingSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->sum('project_size');
           $flooringCount     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->count();
           $flooringSize      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->sum('project_size');
           $plasteringCount   = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->count();
           $plasteringSize    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->sum('project_size');
           $diggingCount      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->count();
           $diggingSize       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->sum('project_size');
           $ele               = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->pluck('project_id');
           $plum              = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
           $ele               = $ele->merge($plum);
           $enpCount          = ProjectDetails::whereIn('project_id',$ele)->count();
           $enpSize           = ProjectDetails::whereIn('project_id',$ele)->sum('project_size');
           $carpentryCount    = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->count();
           $carpentrySize     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->sum('project_size');
           $closedCount       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->count();
           $closedSize        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->sum('project_size');

           $wardname = Ward::where('id',$request->ward)->first();
           $subwards = SubWard::where('ward_id',$request->ward)->get();
           $total = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->count();
           $planning   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Planning%')->sum('project_size');
           $foundation = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Foundation%')->sum('project_size');
           $roofing    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Roofing%')->sum('project_size');
           $walls      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Walls%')->sum('project_size');
           $completion = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Completion%')->sum('project_size');
           $fixtures   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Fixtures%')->sum('project_size');
           $pillars    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Pillars%')->sum('project_size');
           $painting   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Paintings%')->sum('project_size');
           $flooring   = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Flooring%')->sum('project_size');
           $plastering = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Plastering%')->sum('project_size');
           $digging    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Digging%')->sum('project_size');
           $ele2        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Electrical%')->where('sub_ward_id',$request->subward)->pluck('project_id');
           $plum2       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plumbing%')->where('sub_ward_id',$request->subward)->pluck('project_id');
           $ele2        = $ele2->merge($plum2);
           $enp    = ProjectDetails::whereIn('project_id',$ele2)->sum('project_size');
           $carpentry  = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Carpentry%')->sum('project_size');
           $closed     = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Closed%')->sum('project_size');

           $Cplanning      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Planning%')->count();
           $Cfoundation    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Foundation%')->count();
           $Croofing       = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Roofing%')->count();
           $Cwalls         = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Walls%')->count();
           $Ccompletion    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Completion%')->count();
           $Cfixtures      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Fixtures%')->count();
           $Cpillars       = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Pillars%')->count();
           $Cpainting      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Paintings%')->count();
           $Cflooring      = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Flooring%')->count();
           $Cplastering    = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Plastering%')->count();
           $Cdigging       = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Digging%')->count();
           $Cenp   = ProjectDetails::whereIn('project_id',$ele2)->count();
           // $Cenp           = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Electrical%')
           //                     ->orWhere('project_status','LIKE','Plumbing%')->count();
           $Ccarpentry     = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Carpentry%')->count();
           $Cclosed        = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->where('project_status','LIKE','Closed%')->count();

           $subwardname = SubWard::where('id',$request->subward)->pluck('sub_ward_name')->first();
           $totalsubward = ProjectDetails::where('sub_ward_id',$request->subward)->whereIn('quality',$subwardQuality)->sum('project_size');

           return view('projection',[
               'planningCount'=>$planningCount,'planningSize'=>$planningSize,
               'foundationCount'=>$foundationCount,'foundationSize'=>$foundationSize,
               'roofingCount'=>$roofingCount,'roofingSize'=>$roofingSize,
               'wallsCount'=>$wallsCount,'wallsSize'=>$wallsSize,
               'completionCount'=>$completionCount,'completionSize'=>$completionSize,
               'fixturesCount'=>$fixturesCount,'fixturesSize'=>$fixturesSize,
               'pillarsCount'=>$pillarsCount,'pillarsSize'=>$pillarsSize,
               'paintingCount'=>$paintingCount,'paintingSize'=>$paintingSize,
               'flooringCount'=>$flooringCount,'flooringSize'=>$flooringSize,
               'plasteringCount'=>$plasteringCount,'plasteringSize'=>$plasteringSize,
               'diggingCount'=>$diggingCount,'diggingSize'=>$diggingSize,
               'enpCount'=>$enpCount,'enpSize'=>$enpSize,
               'carpentryCount'=>$carpentryCount,'carpentrySize'=>$carpentrySize,
               'closedSize'=>$closedSize,'closedCount'=>$closedCount,
               'wards'=>$wards,'wardname'=>$wardname,
               'subwards'=>$subwards,'wardId'=>$request->ward,
               'totalProjects' => $totalProjects,
               'planning'=>$planning,
               'foundation'=>$foundation,
               'roofing'=>$roofing,
               'walls'=>$walls,
               'completion'=>$completion,
               'fixtures'=>$fixtures,
               'pillars'=>$pillars,
               'painting'=>$painting,
               'flooring'=>$flooring,
               'plastering'=>$plastering,
               'digging'=>$digging,
               'enp'=>$enp,
               'carpentry'=>$carpentry,
               'Cplanning'=>$Cplanning,
               'Cfoundation'=>$Cfoundation,
               'Croofing'=>$Croofing,
               'Cwalls'=>$Cwalls,
               'Ccompletion'=>$Ccompletion,
               'Cfixtures'=>$Cfixtures,
               'Cpillars'=>$Cpillars,
               'Cpainting'=>$Cpainting,
               'Cflooring'=>$Cflooring,
               'Cplastering'=>$Cplastering,
               'Cdigging'=>$Cdigging,
               'Cenp'=>$Cenp,
               'Ccarpentry'=>$Ccarpentry,
               'closed'=>$closed,
               'Cclosed'=>$Cclosed,
               'subwardId'=>$request->subward,
               'subwardName'=>$subwardname,
               'total'=>$total,
               'totalsubward'=>$totalsubward,
               'conversions'=>$conversions
           ]);
       }
       return view('projection',['wards'=>$wards,'planningCount'=>NULL,'subwards'=>NULL,'wardId'=>NULL,'planning'=>NULL,'subwardId'=>NULL,'subwardName'=>NULL,'totalProjects' => $totalProjects,'conversions'=>$conversions]);
    }
    public function getLockProjection(Request $request){
        $wardsselect = Ward::pluck('id');
        $check = Detail::where('created_at','LIKE',date('Y-m').'%')->first();
        $qualityCheck = ['Genuine','Fake','Unverified'];
        if($check == null){
            foreach($wardsselect as $wards){
                // planning
                $subwards = SubWard::where('ward_id',$wards)->pluck('id');
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Planning";
                $details->size       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->sum('project_size');
                $details->save();

                // digging
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Digging";
                $details->size        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->sum('project_size');
                $details->save();

                // foundation
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Foundation";
                $details->size     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->sum('project_size');
                $details->save();

                // pillars
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Pillars";
                $details->size        = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->sum('project_size');
                $details->save();

                // walls
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Walls";
                $details->size          = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->sum('project_size');
                $details->save();



                // roofing
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Roofing";
                $details->size       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->sum('project_size');
                $details->save();

                $ele                = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Electrical%')->pluck('project_id');
                $plum               = ProjectDetails::whereIn('sub_ward_id',$subwards)->where('project_status','LIKE','Plumbing%')->pluck('project_id');
                $ele                = $ele->merge($plum);

                // electrical & plumbing
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Electrical & Plumbing";
                $details->size            = ProjectDetails::whereIn('project_id',$ele)->whereIn('quality',$qualityCheck)->sum('project_size');
                $details->save();

                // plastering
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Plastering";
                $details->size     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->sum('project_size');
                $details->save();

                // flooring
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Flooring";
                $details->size       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->sum('project_size');
                $details->save();

                // carpentry
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Carpentry";
                $details->size      = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->sum('project_size');
                $details->save();

                // painting
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Painting";
                $details->size       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->sum('project_size');
                $details->save();

                // fixture
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Fixture";
                $details->size       = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->sum('project_size');
                $details->save();

                // completion
                $details = new Detail;
                $details->ward_id = $wards;
                $details->stage = "Completion";
                $details->size     = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->sum('project_size');
                $details->save();

                // closed
                // $details = new Detail;
                // $details->ward_id = $wards;
                // $details->stage = "Closed";
                // $details->size         = ProjectDetails::whereIn('sub_ward_id',$subwards)->whereIn('quality',$qualityCheck)->where('project_status','LIKE','Closed%')->sum('project_size');
                // $details->save();
            }
             // planning
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Planning";
             $details->size       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Planning%')->sum('project_size');
             $details->save();

             // digging
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Digging";
             $details->size        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Digging%')->sum('project_size');
             $details->save();

             // foundation
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Foundation";
             $details->size     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Foundation%')->sum('project_size');
             $details->save();

             // pillars
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Pillars";
             $details->size        = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Pillars%')->sum('project_size');
             $details->save();

             // walls
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Walls";
             $details->size          = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Walls%')->sum('project_size');
             $details->save();



             // roofing
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Roofing";
             $details->size       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Roofing%')->sum('project_size');
             $details->save();

             $ele                = ProjectDetails::where('project_status','LIKE','Electrical%')->pluck('project_id');
             $plum               = ProjectDetails::where('project_status','LIKE','Plumbing%')->pluck('project_id');
             $ele                = $ele->merge($plum);

             // electrical & plumbing
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Electrical & Plumbing";
             $details->size            = ProjectDetails::whereIn('project_id',$ele)->whereIn('quality',$qualityCheck)->sum('project_size');
             $details->save();

             // plastering
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Plastering";
             $details->size     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Plastering%')->sum('project_size');
             $details->save();

             // flooring
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Flooring";
             $details->size       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Flooring%')->sum('project_size');
             $details->save();

             // carpentry
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Carpentry";
             $details->size      = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Carpentry%')->sum('project_size');
             $details->save();

             // painting
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Painting";
             $details->size       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Paintings%')->sum('project_size');
             $details->save();

             // fixture
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Fixture";
             $details->size       = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Fixtures%')->sum('project_size');
             $details->save();

             // completion
             $details = new Detail;
             $details->ward_id = "all";
             $details->stage = "Completion";
             $details->size     = ProjectDetails::whereIn('quality',$qualityCheck)->where('project_status','LIKE','Completion%')->sum('project_size');
             $details->save();
         }
         $cat = Conversion::where('id',$request->category)->pluck('category')->first();
        $check2 = Projection::where('category',$cat)->first();
        if($check2 == null){
            $projection = new Projection;
            $projection->category = $cat;
            $projection->price = $request->price;
            $projection->business_cycle = $request->businessCycle;
            $projection->target = $request->monthlyTarget;
            $projection->transactional_profit = $request->transactionalProfit;
            $projection->from_date = $request->from;
            $projection->to_date = $request->to;
            if($request->incrementalPercentage){
                $projection->incremental_percentage = $request->incrementalPercentage;
            }
            $projection->save();
        }else{
            return back()->with('Error','Projection Has Already Been Set For This Category');    
        }
        return back()->with('Success','Projection Has Been Set Successfully');;
   }
   public function getLockedProjection()
   {
       $projections = Projection::pluck('category')->toArray();
       $categories = Utilization::all();
       return view('projection.projectionFirst',['categories'=>$categories,'projections'=>$projections]);
   }
   public function getLockedStage(Request $request){
    $categories = Projection::all();
        $wards = Ward::all();
        $text = "<br><br><br><br><button type='button' class='btn btn-danger' data-toggle='modal' data-target='#reset'>Master Reset</button><br><br><table class='table table-hover' border=1>";
        $totalRequirement = 0;
        $totalPrice = 0;
        $totalMonthly = 0;
        $totalMonthlyPrice = 0;
        $totalTP = 0;
        $totalTarget = 0;
        if($request->ward){
            $projections = Detail::where('details.ward_id',$request->ward)
                            ->leftJoin('wards','wards.id','details.ward_id')
                            ->select('details.*','wards.ward_name')
                            ->get();
            if($request->category == 'all'){
                $category = Projection::all()->toArray();
                foreach($category as $category){
                    $totalCategory = 0;
                    $totalCategoryPrice = 0;
                    $conversion = Conversion::where('category',$category['category'])->first();
                    $utilizations = Utilization::where('category',$category['category'])->first();
                    $text .= "<tr><th colspan=6>".ucwords($category['category'])."</th></tr>";
                    foreach($projections as $projection){
                        if($projection['stage'] == "Electrical & Plumbing")
                            $stage = "electrical";
                        else
                            $stage = $projection['stage'];
                        $totalCategory += ($projection['size'] * $conversion->minimum_requirement/$conversion->conversion)/100*($utilizations[strtolower($stage)]);
                        $totalCategoryPrice += ($projection['size'] * $conversion->minimum_requirement/$conversion->conversion)/100*($utilizations[strtolower($stage)]) * $category['price'];
                    }
                    $text .= "<tr>
                                <th></th>
                                <th style='text-align:right'>Total ".$conversion['unit']."</th>
                                <th style='text-align:right'>Amount</th>
                                <th style='text-align:center'>Business Cycle</th>
                                <td style='text-align:center'><b>Monthly Target</b><br>
                                (".$category['target']."% From The Existing Monthly Requirement)</td>
                                <td style='text-align:center'><b>Transactional Profit</b><br>(".$category['transactional_profit']."% From The Monthly Target)</td>
                            </tr>";
                    $totalRequirement += $totalCategory;
                    $totalPrice += $totalCategoryPrice;
                    $text .= "<tr><td>Total Requirement In The Listed Projects</td>";

                    if($category['incremental_percentage'] != null){
                        $text .= "<td style='text-align:right'>".number_format($totalCategory + $totalCategory / 100 * $category['incremental_percentage'])."</td>
                        <td style='text-align:right'>".number_format($totalCategoryPrice + $totalCategoryPrice / 100 * $category['incremental_percentage'])."</td><td></td><td></td><td></td>
                        </tr>
                        <tr>
                        <td>Monthly Requirement In The Listed Projects</td>";

                        $text .="<td style='text-align:right'>".number_format($monthly = $totalCategory/$category['business_cycle'] + $totalCategory/$category['business_cycle'] / 100 * $category['incremental_percentage'])."</td>
                        <td style='text-align:right'>".number_format($monthlyPrice = $totalCategoryPrice/$category['business_cycle'] + $totalCategoryPrice/$category['business_cycle'] / 100 * $category['incremental_percentage'])."</td>";
                    }else{
                        $text .= "<td style='text-align:right'>".number_format($totalCategory)."</td>
                        <td style='text-align:right'>".number_format($totalCategoryPrice)."</td><td></td><td></td><td></td>
                        </tr>
                        <tr>
                        <td>Monthly Requirement In The Listed Projects</td>";

                        $text .="<td style='text-align:right'>".number_format($monthly = $totalCategory/$category['business_cycle'])."</td>
                        <td style='text-align:right'>".number_format($monthlyPrice = $totalCategoryPrice/$category['business_cycle'])."</td>";
                    }
                    $totalMonthly += $totalCategory/$category['business_cycle'];
                    $totalMonthlyPrice += $totalCategoryPrice/$category['business_cycle'];

                    $totalMonthly/100*$category['target'];
                    $totalTarget += $amount = $monthlyPrice/100*$category['target'];
                    $totalTP += $tp = $amount/100*$category['transactional_profit'];
                    $text .= "<td style='text-align:center'>".$category['business_cycle'].
                                "</td><td style='text-align:right'>".number_format($amount).
                                "</td><td style='text-align:right'>".number_format($tp).
                            "</td></tr>";
                }
                $text .= "<tr><th colspan=6></th></tr><tr><th style='background-color:#236281; color:white;' colspan=6><center>Total</center></th></tr>
                    <tr>
                    <th>Total Monthly Target</th><th></th><th></th><th></th>
                    <th style='text-align:right'>".number_format($totalTarget)."</th>
                    <th></th>
                    </tr>
                    <tr>
                    <th>Total Monthly Transactional Profit</th>
                    <th></th><th></th><th></th><th></th>
                    <th style='text-align:right'>".number_format($totalTP)."</th>
                    </tr>
                    </table>";
                return view('projection.projectionStage',['category'=>$category,'wards'=>$wards,'categories'=>$categories,'text'=>$text]);
            }else{
                $category = Projection::where('category',$request->category)->first();
                $conversion = Conversion::where('category',$request->category)->first();
                $utilizations = Utilization::where('category',$request->category)->first()->toArray();
            }
            $total = Detail::where('ward_id',$request->ward)->sum('size');
            return view('projection.projectionStage',['projections'=>$projections,'category'=>$category,'wards'=>$wards,'total'=>$total,'conversion'=>$conversion,'utilizations'=>$utilizations,'categories'=>$categories]);
        }
       return view('projection.projectionStage',['wards'=>$wards,'categories'=>$categories]);
    }
    public function viewwardmap(Request $request){
        $id=$request->UserId;
        $wardsAssigned = WardAssignment::where('user_id',$id)->where('status','Not Completed')->pluck('subward_id')->first();
        $subwards = SubWard::where('id',$wardsAssigned)->first();
        $projects = ProjectDetails::join('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->leftJoin('requirements','project_details.project_id','=','requirements.project_id')
                        ->where('project_details.sub_ward_id',$wardsAssigned)
                        ->select('requirements.status','site_addresses.address','site_addresses.latitude','site_addresses.longitude','project_details.project_name','project_details.project_id','project_details.created_at','project_details.updated_at')
                        ->get();
        if($subwards != null){
            $subwardMap = SubWardMap::where('sub_ward_id',$subwards->id)->first();
            
        }else{
            $subwardMap = "None";
        }
        if($subwardMap == Null){
            $subwardMap = "None";
        }
        return view('viewwardmap',['subwards'=>$subwards,'projects'=>$projects,'subwardMap'=>$subwardMap]);
    }
    public function viewsubward(Request $request){
       
                $subwardid = SubWard::where('sub_ward_name',$request->subward)->pluck('id');
                $subwardMap = SubWardMap::where('sub_ward_id',$subwardid)->first();
                $projects = ProjectDetails::where('project_details.project_id',$request->projectid)
                        ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                        ->select('site_addresses.*')
                        ->get();   
                    
             return view('viewsubward',['projects'=>$projects,'subwardMap'=>$subwardMap]);            
    }
    public function manufacturemap(Request $request){
                $name = SubWard::where('id',$request->subwardid)->pluck('sub_ward_name')->first();
                $subwardMap = SubWardMap::where('sub_ward_id',$request->subwardid)->first();
                $projects = Manufacturer::where('id',$request->id)
                        ->get();   
             return view('manufacturemap',['projects'=>$projects,'subwardMap'=>$subwardMap,'name'=>$name]);
    }
    public function dateUnupdated(request $request)
    {
        $from="2015-08-05";
        $day = date('Y-m-d',strtotime('-45 days'));
      
        $to=$day;        

        $wards = Ward::orderby('ward_name','ASC')->get();
        $status =  $request->status;
        $site = SiteAddress::all();
        $names = user::get();
        $projectid = null;
        if($status != null){
            $projectsat = new Collection;
            for($i = 0; $i<count($status); $i++)
            {
                $project = ProjectDetails::where('project_status' ,'LIKE', "%".$status[$i]."%")->pluck('project_id');
                $projectsat = $projectsat->merge($project);
            }

        }

            if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }
            if($request->ward && !$request->subward && $from && $to && !$request->status){

                 $projectid = ProjectDetails::
                        where('updated_at','>',$from)
                        ->where('updated_at','<',$to)
                        ->whereIn('sub_ward_id',$subwards)
                       ->whereColumn('updated_at','updated_at')
                        ->pluck('project_id');                  
            }
            else if($request->ward && !$request->subward && $from && $to && $request->status){
                    $projectid = ProjectDetails::
                        where('updated_at','>',$from)
                        ->where('updated_at','<',$to)
                        ->whereIn('sub_ward_id',$subwards)
                        ->whereIn('project_id',$projectsat)  
                       ->whereColumn('updated_at','updated_at')
                        ->pluck('project_id');
            }
            else if($request->ward && $request->subward && $from && $to && !$request->status){
                    $projectid = ProjectDetails::
                        where('updated_at','>',$from)
                        ->where('updated_at','<',$to)
                        ->where('sub_ward_id',$request->subward)
                       ->whereColumn('updated_at','updated_at')
                        ->pluck('project_id');
            }
            else if($request->ward && $request->subward && $from && $to && $request->status){
                $projectid = ProjectDetails::
                        where('updated_at','>',$from)
                        ->where('updated_at','<',$to)
                        ->where('sub_ward_id',$request->subward)
                        ->whereIn('project_id',$projectsat)  
                       ->whereColumn('updated_at','updated_at')
                        ->pluck('project_id');
            }
            if($projectid != null){
            $unupdate = ProjectDetails::whereIn('project_id',$projectid)
                        ->where('quality','!=',"Fake")
                        ->where('type',NULL)
                        ->where('project_status','NOT LIKE','%Closed%')
                        ->where('deleted_at',null)
                        ->paginate(50);
            $total = ProjectDetails::whereIn('project_id',$projectid)
                        ->where('quality','!=',"Fake")
                        ->where('type',NULL)
                        ->where('project_status','NOT LIKE','%Closed%')
                        ->where('deleted_at',null)
                        ->count();
            }
            else{
                $unupdate = new Collection;
                $total = 0;
            }
       return view('datewiseunupdate',['wards'=>$wards,'unupdate'=>$unupdate,'site'=>$site,'names'=>$names,'total'=>$total]);
}
public function Unupdated(request $request){
    if(Auth::user()->group_id == 22){
        return $this->Unupdated1($request);
    }

         $dd = ProjectDetails::getcustomer();
        $wards = Ward::orderby('ward_name','ASC')->get();
        $wardid = $request->subward;
        $previous = date('Y-m-d',strtotime('-30 days'));
        $today = date('Y-m-d');
        $total = "";
        $site = SiteAddress::all();
        $names = user::get();
        $status =  $request->status;
        if($status != null){
            $projectsat = new Collection;
            for($i = 0; $i<count($status); $i++)
            {
                $project = ProjectDetails::where('project_status' ,'LIKE', "%".$status[$i]."%")->pluck('project_id');
                $projectsat = $projectsat->merge($project);
            }

        }
        if(!$request->subward && $request->ward && $request->status && !$request->from && !$request->to){
            $from="";
            $to="";
            if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }
            $projectid = ProjectDetails::where('quality','!=',"Fake")
                    ->where('type',NULL)
                    ->where( 'updated_at', '<=', $previous)
                    ->whereIn('sub_ward_id',$subwards)
                    ->whereNotIn('project_id',$dd['project']->toarray())
                    ->whereIn('project_id',$projectsat)
                    ->paginate('100');

            $totalproject =ProjectDetails::where('quality','!=',"Fake")
                    ->where('type',NULL)
                    ->where( 'updated_at', '<=', $previous)
                    ->whereIn('sub_ward_id',$subwards)
                    ->whereNotIn('project_id',$dd['project']->toarray())

                    ->whereIn('project_id',$projectsat)
                    ->count();
        }
        else if(!$request->subward && $request->ward && !$request->from && !$request->to){
            $from="";
            $to="";
            if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }
            $projectid = ProjectDetails::where('quality','!=',"Fake")
                    ->where('type',NULL)
                    ->where('project_status','NOT LIKE','%Closed%')
                    ->where( 'updated_at', '<=', $previous)
                    ->whereIn('sub_ward_id',$subwards)
                    ->whereNotIn('project_id',$dd['project']->toarray())

                    ->paginate('100');

            $totalproject =ProjectDetails::where('quality','!=',"Fake")
                    ->where('type',NULL)
                    ->where('project_status','NOT LIKE','%Closed%')
                    ->whereNotIn('project_id',$dd['project']->toarray())

                    ->where( 'updated_at', '<=', $previous)
                    ->whereIn('sub_ward_id',$subwards)->count();
                    


             // return view('unupdated',['project'=>$projectid,'wards'=>$wards,'site'=>$site,'from'=>$from,'to'=>$to,'total'=>$total,'totalproject'=>$totalproject]);
        }
        else if($request->subward && $request->ward && $request->status && !$request->from && !$request->to){
         $from=$request->from;
         $to=$request->to;

        $projectid = ProjectDetails::whereIn('project_id',$projectsat)
                        ->where('type',NULL)
                        ->where('sub_ward_id',$request->subward)
                         ->where('quality','!=',"Fake")
                        ->where('updated_at','<=',$previous)
                    ->whereNotIn('project_id',$dd['project']->toarray())

                        ->paginate('100');
        $totalproject = ProjectDetails::where('updated_at','<=',$previous)
                        ->where('type',NULL)
                        ->where('sub_ward_id',$request->subward)
                         ->where('quality','!=',"Fake")
                        ->whereIn('project_id',$projectsat)
                    ->whereNotIn('project_id',$dd['project']->toarray())

                        ->count();
                   

        }
        else if($request->subward && $request->ward && !$request->from && !$request->to){
            $from=$request->from;
            $to=$request->to;
            $projectid = ProjectDetails::where('updated_at','<=',$previous)
                            ->where('type',NULL)
                        ->where('sub_ward_id',$request->subward)
                    ->whereNotIn('project_id',$dd['project']->toarray())

                        ->where('quality','!=',"Fake")
                        ->where('project_status','NOT LIKE','%Closed%')
                        ->paginate('100');
            $totalproject = ProjectDetails::where('updated_at','<=',$previous)
                            ->where('type',NULL)
                            ->where('sub_ward_id',$request->subward)
                    ->whereNotIn('project_id',$dd['project']->toarray())

                            ->where('quality','!=',"Fake")
                            ->where('project_status','NOT LIKE','%Closed%')
                            ->count();
                           
             // return view('unupdated',['project'=>$projectid,'wards'=>$wards,'site'=>$site,'from'=>$from,'to'=>$to,'total'=>$total,'totalproject'=>$totalproject]);
        }else if($request->ward && $request->subward && $request->from && $request->to && $request->status){
            
                 $from=$request->from;
                 $to=$request->to;
                 $projectid = ProjectDetails::where('updated_at','>=',$from)
                        ->where('type',NULL)
                        ->where('updated_at','<=',$to)

                        ->where('sub_ward_id',$request->subward)
                        ->where('quality','!=',"Fake")
                    ->whereNotIn('project_id',$dd['project']->toarray())

                        ->where('project_status','NOT LIKE','%Closed%')
                        ->whereIn('project_id',$projectsat)
                        ->paginate('100');
             
        }
        else if($request->ward && $request->subward && $request->from && $request->to && !$request->status){
           $project = ProjectDetails::where('sub_ward_id',$request->subward)->pluck('project_id');
            $from=$request->from;
                 $to=$request->to;
                 $projectid = ProjectDetails::where('sub_ward_id',$request->subward)
                        ->where('type',NULL)
                        ->where('updated_at','>=',$from)
                    ->whereNotIn('project_id',$dd['project']->toarray())

                        ->where('updated_at','<=',$to)
                        ->where('quality','!=',"Fake")
                        ->where('project_status','NOT LIKE','%Closed%')
                        ->paginate('100');
           
       
        }
        else if($request->ward == "All" && !$request->subward && $request->from && $request->to && $request->status){
            if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }

                 $from=$request->from;
                 $to=$request->to;
                 $projectid = ProjectDetails::where('updated_at','>=',$from)
                        ->where('type',NULL)
                        ->where('updated_at','<=',$to)

                    ->whereNotIn('project_id',$dd['project']->toarray())

                        ->whereIn('sub_ward_id',$subwards)
                        ->where('quality','!=',"Fake")
                        ->where('project_status','NOT LIKE','%Closed%')
                        ->whereIn('project_id',$projectsat)
                        ->paginate('100');
             
        }else if($request->ward == "All" && !$request->subward && $request->from && $request->to && !$request->status){
            if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }

                 $from=$request->from;
                 $to=$request->to;
                 $projectid = ProjectDetails::where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)

                    ->whereNotIn('project_id',$dd['project']->toarray())

                        ->where('type',NULL)
                        ->whereIn('sub_ward_id',$subwards)
                        ->where('quality','!=',"Fake")
                        ->where('project_status','NOT LIKE','%Closed%')
                        ->paginate('100');
                        
            
        }else if($request->from && $request->to && !$request->ward && !$request->subward && !$request->status){

            $subwards = SubWard::pluck('id');
            $from=$request->from;
                 $to=$request->to;
                 $projectid = ProjectDetails::where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                        ->where('type',NULL)
                    ->whereNotIn('project_id',$dd['project']->toarray())

                        ->whereIn('sub_ward_id',$subwards)
                        ->where('quality','!=',"Fake")
                        ->where('project_status','NOT LIKE','%Closed%')
                        ->paginate('100');
        }else if($request->ward && !$request->subward && $request->from && $request->to && !$request->status){
            $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            $from=$request->from;
                 $to=$request->to;
                 $projectid = ProjectDetails::where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                    ->whereNotIn('project_id',$dd['project']->toarray())


                        ->where('type',NULL)
                        ->whereIn('sub_ward_id',$subwards)
                        ->where('quality','!=',"Fake")
                        ->where('project_status','NOT LIKE','%Closed%')
                        ->paginate('100');
                    
        }else if($request->ward && !$request->subward && $request->from && $request->to && $request->status){
            $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            $from=$request->from;
                 $to=$request->to;
                 $projectid = ProjectDetails::where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                    ->whereNotIn('project_id',$dd['project']->toarray())


                        ->where('type',NULL)
                        ->whereIn('sub_ward_id',$subwards)
                        ->where('quality','!=',"Fake")
                        ->where('project_status','NOT LIKE','%Closed%')
                        ->whereIn('project_id',$projectsat)
                        ->paginate('100');           
        }else if(!$request->ward && !$request->subward && $request->from && $request->to && $request->status){
           
                 $from=$request->from;
                 $to=$request->to;
                 $projectid = ProjectDetails::where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                    ->whereNotIn('project_id',$dd['project']->toarray())


                        ->where('type',NULL)
                        ->where('quality','!=',"Fake")
                        ->where('project_status','NOT LIKE','%Closed%')
                        ->whereIn('project_id',$projectsat)
                        ->paginate('100');
             
        }
        else if(!$request->ward && !$request->subward && $request->from && $request->to && $request->status){

            $from=$request->from;
            $to=$request->to;
            $projectid = ProjectDetails::where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)

                   ->where('type',NULL)
                   ->where('quality','!=',"Fake")
                   ->where('project_status','NOT LIKE','%Closed%')
                    ->whereNotIn('project_id',$dd['project']->toarray())

                    ->whereIn('project_id',$projectsat)
                   ->paginate('100'); 
   }
        else{
                $projectid = new Collection;
                $total = "";
                $from = "";
                $to = "";
                $totalproject = "";
                $site = "";

        }
        return view('unupdated',['projects'=>$projectid,'wards'=>$wards,'from'=>$from,'to'=>$to,'total'=>$total,'site'=>$site,'previous'=>$previous,'today'=>$today,'names'=>$names]);
    }


public function unupdatedmanu(request $request){

         $dd = ProjectDetails::getcustomer();

        $today = date('Y-m-d');
        $past = date('Y-m-d',strtotime("-10 days",strtotime($today)));
        $wards = Ward::orderby('ward_name','ASC')->get();
        $wardid = $request->subward;
        $previous = date('Y-m-d',strtotime('-30 days'));
        $today = date('Y-m-d');
        $total = "";
        $site = SiteAddress::all();
        $names = user::get();
        $status =  $request->status;
        if($status != null){
            $projectsat = new Collection;
            for($i = 0; $i<count($status); $i++)
            {
                $project = Manufacturer::where('templock',NULL)->where('manufacturer_type' ,'LIKE', "%".$status[$i]."%")->pluck('id');
                $projectsat = $projectsat->merge($project);

            }
          
        }
        if(!$request->subward && $request->ward && $request->status && !$request->from && !$request->to){
            $from="";
            $to="";
            if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }
            $projectid = Manufacturer::where('templock',NULL)->where('quality','!=',"Fake")
                    ->where('type',NULL)
                    ->whereNotIn('id',$dd['manu']->toarray())
                    
                    ->whereIn('sub_ward_id',$subwards)
                    ->whereIn('id',$projectsat)
                    ->paginate('20');
                  
            $totalproject =Manufacturer::where('templock',NULL)->where('quality','!=',"Fake")
                    ->where('type',NULL)
                   
                    ->whereNotIn('id',$dd['manu']->toarray())
                    
                    ->whereIn('sub_ward_id',$subwards)
                    ->whereIn('id',$projectsat)
                    ->count();
        }
        else if(!$request->subward && $request->ward && !$request->from && !$request->to){
            $from="";
            $to="";
            if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }
            $projectid = Manufacturer::where('templock',NULL)->where('quality','!=',"Fake")
                    ->where('type',NULL)
                    ->whereNotIn('id',$dd['manu']->toarray())
                    ->whereIn('sub_ward_id',$subwards)
                    ->paginate('20');

            $totalproject =Manufacturer::where('templock',NULL)
                    ->where('quality','!=',"Fake")
                    ->where('type',NULL)
                    ->whereNotIn('id',$dd['manu']->toarray())
                    ->whereIn('sub_ward_id',$subwards)->count();
                    


             // return view('unupdated',['project'=>$projectid,'wards'=>$wards,'site'=>$site,'from'=>$from,'to'=>$to,'total'=>$total,'totalproject'=>$totalproject]);
        }
        else if($request->subward && $request->ward && $request->status && !$request->from && !$request->to){
         $from=$request->from;
         $to=$request->to;

        $projectid = Manufacturer::where('templock',NULL)->whereIn('id',$projectsat)
                        ->where('type',NULL)
                        ->where('sub_ward_id',$request->subward)
                        ->whereNotIn('id',$dd['manu']->toarray())
                         ->where('quality','!=',"Fake") 
                        ->paginate('20');
        $totalproject = Manufacturer::where('templock',NULL)
                        ->where('type',NULL)
                        ->where('sub_ward_id',$request->subward)
                        ->whereNotIn('id',$dd['manu']->toarray())
                         ->where('quality','!=',"Fake")
                        ->whereIn('id',$projectsat)
                        ->count();
                   

        }
        else if($request->subward && $request->ward && !$request->from && !$request->to){
            $from=$request->from;
            $to=$request->to;
            $projectid = Manufacturer::where('templock',NULL)
                    ->whereNotIn('id',$dd['manu']->toarray())
                            ->where('type',NULL)
                        ->where('sub_ward_id',$request->subward)
                        ->where('quality','!=',"Fake")
                       
                        ->paginate('20');
            $totalproject = Manufacturer::where('templock',NULL)
                            ->where('type',NULL)
                            ->where('sub_ward_id',$request->subward)
                    ->whereNotIn('id',$dd['manu']->toarray())
                            
                            ->where('quality','!=',"Fake")
                           
                            ->count();
                           
             // return view('unupdated',['project'=>$projectid,'wards'=>$wards,'site'=>$site,'from'=>$from,'to'=>$to,'total'=>$total,'totalproject'=>$totalproject]);
        }else if($request->ward && $request->subward && $request->from && $request->to && $request->status){
            
                 $from=$request->from;
                 $to=$request->to;
                 $projectid = Manufacturer::where('templock',NULL)
                        ->where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                        ->where('type',NULL)
                        ->where('sub_ward_id',$request->subward)
                        ->whereNotIn('id',$dd['manu']->toarray())
                        ->where('quality','!=',"Fake")
                        ->whereIn('id',$projectsat)
                        ->paginate('50');
             
        }
        else if($request->ward && $request->subward && $request->from && $request->to && !$request->status){
           $project = Manufacturer::where('templock',NULL)->where('sub_ward_id',$request->subward)->pluck('id');
            $from=$request->from;
                 $to=$request->to;
                 $projectid = Manufacturer::where('sub_ward_id',$request->subward)
                    ->whereNotIn('id',$dd['manu']->toarray())
                        ->where('type',NULL)
                        ->where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                        ->where('quality','!=',"Fake")
                        ->paginate('50');
           
       
        }
        else if($request->ward == "All" && !$request->subward && $request->from && $request->to && $request->status){
            if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }
              
                 $from=$request->from;
                 $to=$request->to;
                 $projectid = Manufacturer::where('templock',NULL)
                        ->where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                    ->whereNotIn('id',$dd['manu']->toarray())
                        

                        ->whereIn('sub_ward_id',$subwards)
                        ->where('quality','!=',"Fake")
                        ->whereIn('id',$projectsat)
                        ->paginate('50');
                       
             
        }else if($request->ward == "All" && !$request->subward && $request->from && $request->to && !$request->status){
            if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }

                 $from=$request->from;
                 $to=$request->to;
                 $projectid = Manufacturer::where('templock',NULL)
                        ->where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                        ->where('type',NULL)
                        ->whereIn('sub_ward_id',$subwards)
                        ->whereNotIn('id',$dd['manu']->toarray())
                        ->where('quality','!=',"Fake") 
                        ->paginate('50');
                        
            
        }else if($request->from && $request->to && !$request->ward && !$request->subward && !$request->status){

            $subwards = SubWard::pluck('id');
            $from=$request->from;
                 $to=$request->to;
                 $projectid = Manufacturer::where('templock',NULL)
                        ->where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                        ->where('type',NULL)
                        ->whereNotIn('id',$dd['manu']->toarray()) 
                        ->whereIn('sub_ward_id',$subwards)
                        ->where('quality','!=',"Fake")
                        ->paginate('50');
        }else if($request->ward && !$request->subward && $request->from && $request->to && !$request->status){
            $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            $from=$request->from;
                 $to=$request->to;
                 $projectid = Manufacturer::where('templock',NULL)
                        ->where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                        ->where('type',NULL)
                        ->whereNotIn('id',$dd['manu']->toarray())
                        ->whereIn('sub_ward_id',$subwards)
                        ->where('quality','!=',"Fake")
                        ->paginate('50');
                    
        }else if($request->ward && !$request->subward && $request->from && $request->to && $request->status){
            $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            $from=$request->from;
                 $to=$request->to;
                 $projectid = Manufacturer::where('templock',NULL)
                        ->where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                        ->where('type',NULL)
                        ->whereNotIn('id',$dd['manu']->toarray())
                        ->whereIn('sub_ward_id',$subwards)
                        ->where('quality','!=',"Fake")
                        ->whereIn('id',$projectsat)
                        ->paginate('50');           
        }else if(!$request->ward && !$request->subward && $request->from && $request->to && $request->status){
           
                 $from=$request->from;
                 $to=$request->to;
                 $projectid = Manufacturer::where('templock',NULL)
                        ->where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                        ->where('type',NULL)
                        ->whereNotIn('id',$dd['manu']->toarray())
                        ->where('quality','!=',"Fake")
                        ->whereIn('id',$projectsat)
                        ->paginate('50');
             
        }
        else if(!$request->ward && !$request->subward && $request->from && $request->to && $request->status){

            $from=$request->from;
            $to=$request->to;
            $projectid = Manufacturer::where('templock',NULL)
                        ->where('updated_at','>=',$from)
                        ->where('updated_at','<=',$to)
                        ->whereNotIn('id',$dd['manu']->toarray()) 
                        ->where('type',NULL)
                        ->where('quality','!=',"Fake")
                        ->whereIn('id',$projectsat)
                        ->paginate('50'); 
   }
        else{
                $projectid = new Collection;
                $total = "";
                $from = "";
                $to = "";
                $totalproject = "";
                $site = "";

        }
        return view('unupdatedmanu',['projects'=>$projectid,'wards'=>$wards,'from'=>$from,'to'=>$to,'total'=>$total,'site'=>$site,'previous'=>$previous,'today'=>$today,'names'=>$names]);
    }

     public function Unupdated1(Request $request){

        $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
        $tlward = explode(",", $tl); 
        $wards = Ward::orderby('ward_name','ASC')->whereIn('id',$tlward)->get();
        $wardid = $request->subward;
        $previous = date('Y-m-d',strtotime('-30 days'));
        $today = date('Y-m-d');
        $total = "";
        $site = SiteAddress::all();
        $names = user::get();
        $status =  $request->status;
        if($status != null){
            $projectsat = new Collection;
            for($i = 0; $i<count($status); $i++)
            {
                $project = ProjectDetails::where('project_status' ,'LIKE', "%".$status[$i]."%")->pluck('project_id');
                $projectsat = $projectsat->merge($project);
            }

        }
        if(!$request->subward && $request->ward && $request->status){
            $from="";
            $to="";
            if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }
            $projectid = ProjectDetails::where( 'updated_at', '<=', $previous)
                    ->whereIn('sub_ward_id',$subwards)
                    ->whereIn('project_id',$projectsat)
                    ->paginate('20');

            $totalproject =ProjectDetails::where( 'updated_at', '<=', $previous)
                    ->whereIn('sub_ward_id',$subwards)
                    ->whereIn('project_id',$projectsat)
                    ->count();
        }
        else if(!$request->subward && $request->ward && !$request->status){
            $from="";
            $to="";
            if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }
            $projectid = ProjectDetails::where( 'updated_at', '<=', $previous)
                    ->whereIn('sub_ward_id',$subwards)
                    ->paginate('20');

            $totalproject =ProjectDetails::where( 'updated_at', '<=', $previous)
                    ->whereIn('sub_ward_id',$subwards)->count();

             // return view('unupdated',['project'=>$projectid,'wards'=>$wards,'site'=>$site,'from'=>$from,'to'=>$to,'total'=>$total,'totalproject'=>$totalproject]);
        }
        else if($request->subward && $request->ward && $request->status){
         $from=$request->from;
        $to=$request->to;

        $projectid = ProjectDetails::whereIn('project_id',$projectsat)
                        ->where('sub_ward_id',$request->subward)
                        ->where('updated_at','<=',$previous)
                        ->paginate('20');
        $totalproject = ProjectDetails::where('updated_at','<=',$previous)
                        ->where('sub_ward_id',$request->subward)
                        ->whereIn('project_id',$projectsat)
                        ->count();
        }
        else if($request->subward && $request->ward){
            $from=$request->from;
            $to=$request->to;
            $projectid = ProjectDetails::where('updated_at','<=',$previous)
                        ->where('sub_ward_id',$request->subward)
                        ->paginate('20');
            $totalproject = ProjectDetails::where('updated_at','<=',$previous)
                            ->where('sub_ward_id',$request->subward)
                            ->count();
             // return view('unupdated',['project'=>$projectid,'wards'=>$wards,'site'=>$site,'from'=>$from,'to'=>$to,'total'=>$total,'totalproject'=>$totalproject]);
        }

        else{
                $projectid = new Collection;
                $total = "";
                $from = "";
                $to = "";
                $totalproject = "";
                $site = "";

        }
        return view('unupdated',['projects'=>$projectid,'wards'=>$wards,'from'=>$from,'to'=>$to,'total'=>$total,'totalproject'=>$totalproject,'site'=>$site,'previous'=>$previous,'today'=>$today,'names'=>$names]);
    }
    public function getDaily()
    {
        $totalRequirement = 0;
        $totalPrice = 0;
        $totalMonthly = 0;
        $totalMonthlyPrice = 0;
        $totalTP = 0;
        $totalTarget = 0;
        $projections = Detail::where('details.ward_id','all')
                            ->leftJoin('wards','wards.id','details.ward_id')
                            ->select('details.*','wards.ward_name')
                            ->get();
        $category = Projection::all()->toArray();
        foreach($category as $category){
            $totalCategory = 0;
            $totalCategoryPrice = 0;
            $conversion = Conversion::where('category',$category['category'])->first();
            $utilizations = Utilization::where('category',$category['category'])->first()->toArray();
            foreach($projections as $projection){
                if($projection['stage'] == "Electrical & Plumbing")
                    $stage = "electrical";
                else
                    $stage = $projection['stage'];
                $totalCategory += ($projection['size'] * $conversion->minimum_requirement/$conversion->conversion)/100*($utilizations[strtolower($stage)]);
                $totalCategoryPrice += ($projection['size'] * $conversion->minimum_requirement/$conversion->conversion)/100*($utilizations[strtolower($stage)]) * $category['price'];
            }
            $totalRequirement += $totalCategory;
            $totalPrice += $totalCategoryPrice;
            $monthly = $totalCategory/$category['business_cycle'];
            $monthlyPrice = $totalCategoryPrice/$category['business_cycle'];
            $totalMonthly += $totalCategory/$category['business_cycle'];
            $totalMonthlyPrice += $totalCategoryPrice/$category['business_cycle'];

            // $totalMonthly/100*$category['target'];
            $totalTarget += $amount = $monthlyPrice/100*$category['target'];
            $totalTP += $tp = $amount/100*$category['transactional_profit'];
        }
        $projection = Projection::pluck('from_date')->first();
        $toDate = Projection::pluck('to_date')->first();
        return view('projection.daily',['projection'=>$projection,'totalTarget'=>$totalTarget,'totalTP'=>$totalTP,'toDate'=>$toDate]);
    }
    public function lockYearly(Request $request)
    {
        $planning = new Planning;
        $planning->incremental_percentage = $request->incremental_percentage;
        $planning->type = $request->type;
        $planning->totalTarget = $request->totalTarget;
        $planning->totalTP = $request->totalTP;
        $planning->save();
        return back()->with('Success','Yearly Planning Locked');
    }
    public function getCountryProjection()
    {
        $planning = Planning::where('type','yearly')->first();
        $zone = Zone::first();
        $country = Country::where('id',$zone->id)->first();
        $zone_name = "MH_".$country->country_code."_".$zone->zone_number;
        $dates = Projection::first();
        $numberOfZones = NumberOfZones::all()->toArray();
        return view('projection.country',['planning'=>$planning,'zone_name'=>$zone_name,'zone'=>$zone,'dates'=>$dates,'numberOfZones'=>$numberOfZones]);
    }
    public function getAuditorDashboard()
    {
        return view('auditor.dashboard');
    }
    public function getExpenditure(Request $request)
    {
        $capitalExpenditure = CapitalExpenditure::first();
        $operationalExpenditure = OperationalExpenditure::first();
        if(count($capitalExpenditure) != 0 && !$request->edit){
            return redirect('/viewExpenditure');
        }
        return view('expenditure.expenditures',['capitalExpenditure'=>$capitalExpenditure,'operationalExpenditure'=>$operationalExpenditure]);
    }
    public function saveExpenditure(Request $request)
    {
        $check1 = CapitalExpenditure::first();
        if($check1 == null){
            $capital = new CapitalExpenditure;
            $capital->rental = $request->deposit;
            $capital->assets = $request->assets;
            $capital->save();
        }else{
            $check1->rental = $request->deposit;
            $check1->assets = $request->assets;
            $check1->save();
        }
        $check2 = OperationalExpenditure::first();
        if($check2 == null){
            $operational = new OperationalExpenditure;
            $operational->salary = $request->salary;
            $operational->office_rent = $request->rent;
            $operational->petrol = $request->petrol;
            $operational->travelling = $request->travel;
            $operational->mmt_user_fee = $request->mmt_fees;
            $operational->telephone_charges = $request->phone_charges;
            $operational->miscellineous = $request->miscellineous;
            $operational->save();
        }else{
            $check2->salary = $request->salary;
            $check2->office_rent = $request->rent;
            $check2->petrol = $request->petrol;
            $check2->travelling = $request->travel;
            $check2->mmt_user_fee = $request->mmt_fees;
            $check2->telephone_charges = $request->phone_charges;
            $check2->miscellineous = $request->miscellineous;
            $check2->save();
        }
        return redirect('/viewExpenditure');
    }
    public function viewExpenditure()
    {
        $capitalExpenditure = CapitalExpenditure::first();
        $operationalExpenditure = OperationalExpenditure::first();
        $planning = Planning::where('type','yearly')->first();
        if(count($capitalExpenditure) == 0)
            return redirect('/expenditure');
        return view('expenditure.viewExpenditures',['capitalExpenditure'=>$capitalExpenditure,'operationalExpenditure'=>$operationalExpenditure,'planning'=>$planning]);
    }
    public function getFiveYearsExpenditure()
    {
        $capitalExpenditure = CapitalExpenditure::first();
        $operationalExpenditure = OperationalExpenditure::first();
        $planning = Planning::where('type','yearly')->first();
        return view('expenditure.five_years',['capitalExpenditure'=>$capitalExpenditure,'operationalExpenditure'=>$operationalExpenditure,'planning'=>$planning]);
    }
    public function save(Request $request)
    {
        NumberOfZones::truncate();
        for($i = 0; $i < count($request->month); $i++){
            $zones = new NumberOfZones;
            $zones->month = $request->month[$i];
            $zones->grade_a = $request->gradeA[$i];
            $zones->grade_b = $request->gradeB[$i];
            $zones->grade_c = $request->gradeC[$i];
            $zones->grade_d = $request->gradeD[$i];
            $zones->grade_e = $request->gradeE[$i];
            $zones->save();
        }
        return back();
    }
    public function getExtensionPlanner()
    {
        $dates = Projection::first();
        return view('projection.extension',['dates'=>$dates]);
    }
    public function getFiveYears()
    {
        $totalTarget = Planning::where('type','yearly')->pluck('totalTarget')->first();
        $totalTP = Planning::where('type','yearly')->pluck('totalTP')->first();
        $projection = Projection::pluck('from_date')->first();
        return view('projection.fiveYears',['totalTarget'=>$totalTarget,'totalTP'=>$totalTP,'projection'=>$projection]);
    }
    public function getReset(Request $request)
    {
        if($request->category == "all"){
            Projection::truncate();
            Detail::truncate();
            Planning::truncate();
        }else{
            Projection::where('category',$request->category)->delete();
        }
        return redirect('/stage');
    }
    public function getYearlyPlanning(Request $request)
    {
        $totalRequirement = 0;
        $totalPrice = 0;
        $totalMonthly = 0;
        $totalMonthlyPrice = 0;
        $totalTP = 0;
        $totalTarget = 0;
        $projections = Detail::where('details.ward_id','all')
                            ->leftJoin('wards','wards.id','details.ward_id')
                            ->select('details.*','wards.ward_name')
                            ->get();
        $category = Projection::all()->toArray();
        foreach($category as $category){
            $totalCategory = 0;
            $totalCategoryPrice = 0;
            $conversion = Conversion::where('category',$category['category'])->first();
            $utilizations = Utilization::where('category',$category['category'])->first();
            foreach($projections as $projection){
                if($projection['stage'] == "Electrical & Plumbing")
                    $stage = "electrical";
                else
                    $stage = $projection['stage'];
                $totalCategory += ($projection['size'] * $conversion->minimum_requirement/$conversion->conversion)/100*($utilizations[strtolower($stage)]);
                $totalCategoryPrice += ($projection['size'] * $conversion->minimum_requirement/$conversion->conversion)/100*($utilizations[strtolower($stage)]) * $category['price'];
            }
            $totalRequirement += $totalCategory;
            $totalPrice += $totalCategoryPrice;
            $monthly = $totalCategory/$category['business_cycle'];
            $monthlyPrice = $totalCategoryPrice/$category['business_cycle'];
            $totalMonthly += $totalCategory/$category['business_cycle'];
            $totalMonthlyPrice += $totalCategoryPrice/$category['business_cycle'];

            $totalMonthly/100*$category['target'];
            $totalTarget += $amount = $monthlyPrice/100*$category['target'];
            $totalTP += $tp = $amount/100*$category['transactional_profit'];
        }
        $projection = Projection::pluck('from_date')->first();
        $categories = Projection::all();
        return view('projection.yearly',['projection'=>$projection,'totalTarget'=>$totalTarget,'totalTP'=>$totalTP,'categories'=>$categories]);
    }
    public function getEditProjectionPlanner()
    {
        $dates = Projection::first();
        $projections = NumberOfZones::all()->toArray();
        return view('projection.extension',['dates'=>$dates,'projections'=>$projections]);
    }
    // public function assigntl(request $request){

    //       $users = User::where('group_id',22)
    //         ->paginate(10);
    //         $def =[1,2];
    //         $user1 = User::whereIn('department_id',$def)
    //         ->where('users.group_id','!=',2)
    //         ->get();
    //       $ward = Ward::all();
    //       $tlward = Tlwards::all();

    //     return view('/assigntl',['users'=>$users,'ward'=>$ward,'user1'=>$user1]);
    // }
    public function assigntl(request $request){

          $users = User::where('group_id',22)->where('department_id','!=',10)
            ->paginate(10);
            $def =[1,2];
            $user1 = User::whereIn('department_id',$def)
            ->where('users.group_id','!=',2)->where('department_id','!=',10)
            ->get();
            $newUsers = [];
            $user1 = User::leftjoin('departments','departments.id','users.department_id')
            ->whereIn('department_id',$def)
            ->where('department_id','!=', 10)
            ->select('departments.*','departments.dept_name','users.name','users.id')->get();
              $ward = Ward::all();
             $tluserids = Tlwards::where('group_id',22)->get();

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

            $tl = Tlwards::leftjoin('users','users.id','tlwards.user_id')
                 ->pluck('tlwards.users');
               $tt = explode(",", $tl) ;
            
            foreach($users as $user){

                $tlwards = Tlwards::where('user_id',$user->id)->first();
                if($tlwards == null){
                    
                }
                else{
                $userIds = explode(",",$tlwards->users);
                $noOfUsers = User::whereIn('id',$userIds)->get()->toArray();
                array_push($newUsers,['tl_id'=>$user->id,'employees'=>$noOfUsers]);
            }
        }
        return view('/assigntl',['newUsers' =>$newUsers,'users'=>$users,'ward'=>$ward,'user1'=>$user1,'newwards'=>$newwards,'tluserids'=>$tluserids]);
    }

    public function tlward(request $request){
        $check = Tlwards::where('user_id',$request->user_id)->first();
        if($request->ward_id){
        $ward = implode(",", $request->ward_id);
        }else{
            $ward="null";
        }
        if($request->framework){

        $users = implode(",", $request->framework);
        }else{
            $users="null";
        }

       if(($check) == null){
            $tlward=new Tlwards;
            $tlward->user_id = $request->user_id;
             $tlward->group_id = $request->group_id;
             $tlward->users = $users;
              $tlward->ward_id = $ward;
              $tlward->save();
        }else{
             $check->user_id = $request->user_id;
             $check->group_id = $request->group_id;
              $check->ward_id = $ward;
              $check->users = $users;
              $check->save();
        }
        return redirect()->back()->with('success','Ward and Users Assigned Successfully');
    }
    public function simple(request $request){


         return view('/simple');
    }
   public function tickets(request $request)
    {
        $options['timeout'] = 300;
        $url = 'https://mamamicrotechnology.com/clients/MH/webapp/api/req';
       $client = new \GuzzleHttp\Client();
       $request = $client->get($url,$options);
       $response = $request->getBody();
       $data = json_decode($response);
   return view('/ticket',['data'=>$data]);
    }
    public function chat(request $request)
    {

    $client = new \GuzzleHttp\Client();
    $request = $client->get('http://localhost:8000/api/ticket');
   $response = $request->getBody();
   $data = json_decode($response);

        return view('/ticketchat',['data'=>$data]);
    }
    public function storedetails(Request $request){
         $id = $request->id;
         $value= $request->value;
        $x = ProjectDetails::where('project_id',$id)->first();
        $x->detailed_mcal = $request->value;
        $x->save();
         if($x && $value== "yes")
        {
            return back()->with('success','MAMAHOME Executive Will Contact You Shortly.');
        }
        else{
            return back()->with('success','Thank You :)');
        }
    }
    public function addManufacturer()
    {
        $this->listingEngineer();
         $tlwards=$this->variable;
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $wardsAssigned = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
        $subwards = SubWard::where('id',$wardsAssigned)->first();
         $a =Subward::where('id',$wardsAssigned)->pluck('ward_id')->first();
        $acc = Subward::where('ward_id',$a)->get();



             $Wards = [];
      $wards = Ward::all();
     foreach($wards as $user){
           
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


 



        return view('addManufacturer',['subwards'=>$subwards,'log'=>$log,'log1'=>$log1,'tlwards'=>$tlwards,'acc'=>$acc,'ward'=>$d]);
    }
    public function viewManufacturer(Request $request)
    {
        if(Auth::user()->group_id == 22){
            return $this->viewManufacturer1($request);
        }
        $his = History::get();
        $dd= $request->type;
        if($request->type){
            $manufacturers = Manufacturer::where('templock',NULL)->where('manufacturer_type',$request->type)->orderBy('id','DESC')
                    ->paginate(20);
           $count = Manufacturer::where('templock',Null)->where('manufacturer_type',$request->type)->count();
                    
         }else{

        $manufacturers = Manufacturer::where('templock',NULL)->orderBy('id','DESC')->paginate(20);
          $count = Manufacturer::where('templock',NULL)->count();
         }

        return view('viewManufacturer',['manufacturers'=>$manufacturers,'count'=>$count,'dd'=>$dd,'his'=>$his]);
    }
public function viewManufacturer1(Request $request)
    {

       $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
       $ward = Subward::where('ward_id',$tl)->pluck('id');
         $dd= $request->type;
          $his = History::get();
         if($request->type){
            $manufacturers = Manufacturer::whereIn('sub_ward_id',$ward)->where('manufacturer_type',$request->type)
                    ->paginate(20);
    $count = Manufacturer::whereIn('sub_ward_id',$ward)->where('manufacturer_type',$request->type)->count();

         }else{

        $manufacturers = Manufacturer::whereIn('sub_ward_id',$ward)
                    ->paginate(20);
    $count = Manufacturer::whereIn('sub_ward_id',$ward)->count();
         }
                   
        return view('viewManufacturer',['manufacturers'=>$manufacturers,'count'=>$count,'dd'=>$dd,'his'=>$his]);
    }

    public function lebrands(){

        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
         $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        return view('lebrands',['log'=>$log,'log1'=>$log1]);
    }
    public function storequery(Request $request){


        $id = History::where('project_id',$request->id)->pluck('id')->last();
        $hist = History::where('id',$id)->first();
        $hist->question=$request->qstn;
        $hist->remarks=$request->remarks;
        $hist->save();
        return back()->with('success','Submited successfully !');
    }

 public function manustorequery(Request $request){


        $id = History::where('manu_id',$request->id)->pluck('id')->last();
        History::where('id',$id)->update(['question'=>$request->qstn,
                                            'remarks'=>$request->remarks
            ]);

         return back();
    }

    public function auto(request $requests){
     $projects = ProjectDetails::where('automation',"Yes")->where('quality','!=','Fake')->paginate(10); 
     $projectcount= count($projects); 
      $roomtypes = RoomType::all();
    $his = History::all();
    $orders = Order::all();
    $requirements=Requirement::all();
     return view('salesengineer',['projects'=>$projects,'projectcount'=>$projectcount,'roomtypes'=>$roomtypes,'his'=>$his,'orders'=>$orders,'requirements'=>$requirements ]);

  }
  public function getUpdateManufacturer()
  {
        $date=date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
        $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $wardsAssigned = WardAssignment::where('user_id',Auth::user()->id)->where('status','Not Completed')->pluck('subward_id')->first();







        $manufacturers = Manufacturer::where('listing_engineer_id',Auth::user()->id)->get();
        return view('updateManufacturer',['manufacturers'=>$manufacturers,'log'=>$log,'log1'=>$log1]);
  }
  public function updateManufacturerDetails(Request $request)
  {
   
      $manufacturer = Manufacturer::findOrFail($request->id);
      $ward=Subward::leftjoin('manufacturers','manufacturers.sub_ward_id','sub_wards.id')
      ->where('manufacturers.id',$request->id)->pluck('sub_wards.sub_ward_name')->first();
     
  return view('updateManufacturers',['manufacturer'=>$manufacturer,'ward'=>$ward]);
  }
  public function getUnverifiedProjects(Request $request)
  {
    $wards = Ward::orderby('ward_name','ASC')->get();
    $wardid = $request->subward;
    $previous = date('Y-m-d',strtotime('-30 days'));
    $today = date('Y-m-d');
    $total = "";
    $site = SiteAddress::all();
    $names = user::get();
    $status =  $request->status;
    $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('ward_id')->first();
         $tll = explode(",", $tl);
        $tlwards = Ward::whereIn('id',$tll)->get();
       
    if($status != null){
        $projectsat = new Collection;
        for($i = 0; $i<count($status); $i++)
        {
            $project = ProjectDetails::where('project_status' ,'LIKE', "%".$status[$i]."%")->pluck('project_id');
            $projectsat = $projectsat->merge($project);
        }

    }
    if(!$request->subward && $request->ward){
        $from="";
        $to="";
        if($request->ward == "All"){
            $subwards = SubWard::pluck('id');
        }else{
            $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
        }
        $projectid = ProjectDetails::where( 'quality', 'Unverified')
                 
                 ->where('project_status','NOT LIKE','%Closed%')
                 ->whereIn('sub_ward_id',$subwards)
                 ->paginate('20');
        $totalproject =ProjectDetails::where( 'quality', 'Unverified')
                
                 ->where('project_status','NOT LIKE','%Closed%')
                ->whereIn('sub_ward_id',$subwards)->count();
              
    }
    else if($request->subward && $request->ward){
        $from=$request->from;
        $to=$request->to;

        $projectid = ProjectDetails::where('quality','Unverified')
                        
                        ->where('project_status','NOT LIKE','%Closed%')
                        ->where('sub_ward_id',$request->subward)
                        ->paginate('20');
        $totalproject = ProjectDetails::where('quality','Unverified')
                    
                    ->where('project_status','NOT LIKE','%Closed%')
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
    return view('unverifiedProjects',['projects'=>$projectid,'wards'=>$wards,'from'=>$from,'to'=>$to,'total'=>$total,'totalproject'=>$totalproject,'site'=>$site,'previous'=>$previous,'today'=>$today,'names'=>$names,'tlwards'=>$tlwards]);
  }
  public function getProjectsBasedOnNotes(Request $request)
  {
        $site = SiteAddress::all();
        $names = user::get();
        $wards = Ward::all();


        if($request->note){
            if($request->ward && !$request->subward){
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
                $projectid = ProjectDetails::where('with_cont',$request->note)->whereIn('sub_ward_id',$subwards)->paginate('20');
                $totalproject = ProjectDetails::where('with_cont',$request->note)->whereIn('sub_ward_id',$subwards)->count();
                $subward = Subward::where('ward_id',$request->ward)->get();
            }elseif($request->ward && $request->subward){
                $projectid = ProjectDetails::where('with_cont',$request->note)->where('sub_ward_id',$request->subward)->paginate('20');
                $totalproject = ProjectDetails::where('with_cont',$request->note)->where('sub_ward_id',$request->subward)->count();
                $subward = Subward::where('ward_id',$request->ward)->get();
            }else{
                $projectid = ProjectDetails::where('with_cont',$request->note)->paginate('20');
                $totalproject = ProjectDetails::where('with_cont',$request->note)->count();
            }
        }else{
            $projectid = new Collection;
            $totalproject = 0;
            $subward = "";
        }
        return view('projectsWithNotes',['totalproject'=>$totalproject,'projects'=>$projectid,'site'=>$site,'names'=>$names,'wards'=>$wards,'subward'=>$subward]);
    }
    public function getdashboard(Request $request){
        $today = date('Y-m-d');
        $reports = Report::where('empId',Auth::user()->employeeId)->where('created_at','LIKE',$today.'%')->get();
        return view('RandD.dashboard',['reports'=>$reports]);
  }
  public function getsalesofficer(Request $request){
         $date = date('Y-m-d', strtotime('-30 days'));;
         $cat = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
         $catname = Category::where('id',$cat)->pluck('category_name')->first();
         $categories = Category::where('id',$cat)->get();
         $ac = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
         $catsub = Category::where('id',$ac)->pluck('category_name')->first();
          $sprojectids = Requirement::where('main_category',$catsub)->pluck('project_id');

          $projects = ProjectDetails::whereIn('project_id',$sprojectids)
                    ->select('project_details.*','project_id')
                    ->orderBy('project_id','ASC')
                    ->count();
         $cat = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
            $enq = Requirement::where('generated_by',Auth::user()->id)->where('main_category',$catsub)->count(); 
          
            $enq1 = Requirement::where('generated_by',Auth::user()->id)->where('main_category',$catsub)
                ->where('created_at','>=',$date)
            ->count();        

          $updateprojects = ProjectUpdate::where('user_id',Auth::user()->id)->where('cat_id',$cat)->count();
           $updateprojects1 = ProjectUpdate::where('user_id',Auth::user()->id)->where('cat_id',$cat)
           ->where('created_at','>=',$date)->count();
           $ins = AssignCategory::where('user_id',Auth::user()->id)->pluck('instraction')->first();      
                  
    
        return view('Salesofficer.dashboard',['catname'=>$catname,'categories'=>$categories,'projects'=>$projects,'updateprojects'=>$updateprojects,'updateprojects1'=>$updateprojects1,'enq'=>$enq,'ins'=>$ins,'enq1'=>$enq1]);
  }
  public function postdashboard(Request $request){
    
        for($i = 0; $i < count($request->report); $i++){
            $report = new Report;
            $report->empId = Auth::user()->employeeId;
            $report->report = $request->report[$i];
            $report->start = $request->from[$i];
            $report->end = $request->to[$i];
            $report->save();
        }
        $fieldLogin = FieldLogin::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->first();
        $fieldLogin->logout = date('h:i A');
        $fieldLogin->save();
        return back()->with('Success','Your Report Has Been Saved Successfully');
    
  }
  public function breaktime(Request $request)
  {
          
    //     $time = New BreakTime;
    //         $time->user_id = Auth::user()->id;
    //         $time->date = date('Y-m-d');
    //         $time->start_time = date('h:i A');
    //         $time->stop_time = null;
    //         $time->save();
    //    $break ="break started";
    // return response()->json($break);
    $time = New BreakTime;
            $time->user_id = Auth::user()->id;
            $time->date = date('Y-m-d');
            $time->start_time = date('h:i A');
            $time->stop_time = "";
            $time->save();
        return back()->with('Success','Your Break Time Started');
        
  }
  public function sbreaktime(Request $request)
  {

    $x = BreakTime::where('user_id',Auth::user()->id)->where('date',date('Y-m-d'))->pluck('id')->last();
     $y = BreakTime::where('user_id',Auth::user()->id)->where('date',date('Y-m-d'))->pluck('start_time')->last();
    
   $A = strtotime($y);
   $B = strtotime(date('h:i A'));
   $diff = $B - $A;
    $z =  $diff / 60 ;

   BreakTime::where('id',$x)->update([
            'stop_time' => date('h:i A'),
            'totaltime' =>$z

        ]);
     $total = BreakTime::where('user_id',Auth::user()->id)->where('date',date('Y-m-d'))->pluck('totaltime')->last();
     $text = "             Your Break Time Stopped.                                Total Time Taken ".$total."mins";
     return back()->with('Success',$text);
  }
 
  public function getid(){
        $tl1= Tlwards::where('group_id','=',22)->get();
        $userid = Auth::user()->id;
        $found1 = null;
        foreach($tl1 as $searchWard){
            $usersId = explode(",",$searchWard->users);
            if(in_array($userid, $usersId)){
                $found1 = $searchWard->ward_id;
            }
        }
    $ward =Ward::where('id',$found1)->pluck('ward_name')->first();
     $this->variable = $ward;
     
      
     // return $tl1;
    return array(
        'ward' =>$ward,'tls'=>$tl1,'user_id'=>$userid,'found'=>$found1);
  }
  public function deleteward(Request $request){

     $tlward = Tlwards::where('user_id',$request->id)->update([
        'users'=>null,
        ]);
        return response()->json('Success');
  }

 public function enqticket(request $request)
    {
        $options['timeout'] = 300;
        $url = 'https://mamamicrotechnology.com/clients/MH/webapp/api/req1';
       $client = new \GuzzleHttp\Client();
       $request = $client->get($url,$options);
       $response = $request->getBody();
       $data = json_decode($response);
   return view('/enq',['data'=>$data]);
    }

  public function Assigncat(request $request){
        $categories = Category::all();
        $nofprojects=array();
        $nofenquirys=array();
        foreach($categories as $cat){
            $nofproject = Requirement::where('main_category',$cat->category_name)->pluck('project_id');
            $project = ProjectDetails::whereIn('project_id',$nofproject)->count();
            $nofprojects[$cat->id] = $project;
            $nofenquiry = Requirement::where('main_category',$cat->category_name)->pluck('id')->count();
            $nofenquirys[$cat->id] = $nofenquiry;

        }
        $users = User::where('group_id',23)->get();
        $cat = AssignCategory::all();
      return view('/cat',['categories'=>$categories,'users'=>$users,'cat'=>$cat,'nofprojects'=>$nofprojects,'nofenquirys'=>$nofenquirys]);

  }


  public function getNewActivityLog(request $request)
  {
    $userid =[6,17,22,23,7];
   
    $date = date('Y-m-d');
     $userChecks = User::all();
if($request->list !="ALL" && $request->fromdate && $request->todate){
                      $from =$request->fromdate;
                      $to = $request->todate;
    $user = User::where('id',$request->list)->get();
                      if($from == $to){
                                   foreach ($user as $users) {
                                $noOfCalls[$users->id]['data'] = Activity::where('causer_id',$users->id)->where('description','updated')->where('subject_type','App\ProjectDetails')->where('created_at','like',$from.'%')->where('created_at','LIKE',$to."%")->where('causer_id',$request->list)->get();
                                                           }
  }
else{
    
          foreach ($user as $users) {
        $noOfCalls[$users->id]['data'] = Activity::where('causer_id',$users->id)->where('description','updated')->where('subject_type','App\ProjectDetails')->wheredate('created_at','>=',$request->fromdate)->wheredate('created_at','<=',$request->todate)->where('causer_id',$request->list)->get();
                                   }
    
}
                 

}

else{

 $user = User::whereIn('group_id',$userid)->where('department_id','!=',10)->get();
     foreach ($user as $users) {
        $noOfCalls[$users->id]['data'] = Activity::where('causer_id',$users->id)->where('description','updated')->where('subject_type','App\ProjectDetails')->where('created_at','like',$date.'%')->get();
       
    }
    
}
    

     foreach ($user as $users) {
        $noOfCall[$users->id]['count'] = Activity::where('causer_id',$users->id)->where('description','updated')->where('subject_type','App\ProjectDetails')->where('created_at','like',$date.'%')->count();
       
                               }

foreach ($user as $users) {
        $noOf[$users->id]['history'] = Activity::where('causer_id',$users->id)->where('created_at','like',$date.'%')->where('called',1)->count();

    }

       $sub = Subward::all();    
        return view('/newActivityLog',['userChecks'=>$userChecks,'noOfCalls'=>$noOfCalls,'users'=>$user,'noOfCall'=>$noOfCall,'noOf'=>$noOf,'sub'=>$sub]);
  }


  public function breaks(Request $request){
       $date = date('Y-m');
       if(Auth::user()->group_id == 2){
       $dept = [1,2];
       $userIds = User::whereIn('department_id',$dept)->select('id')->get();
       }else if(Auth::user()->group_id == 1){
            $dept = [1,2,3,4,5,6];
            $userIds = User::whereIn('department_id',$dept)->select('id')->get();
       }else{
       $dept = [1,2,3,4,6];
       $userIds = User::whereIn('department_id',$dept)->select('id')->get();
       }
        $breaks = breaktime::whereIn('user_id',$userIds)->where('breaktime.created_at','>=', Carbon::now()->subMonth(2))
        ->leftjoin('users','breaktime.user_id','users.id')
        ->orderBy('created_at','desc')->
        select('breaktime.*','users.name')->get();
        return view('breaks',['breaks'=>$breaks]);
    }
    public function loginhistory(Request $request){
            $from = $request->from;
            $to = $request->to;
            if(Auth::user()->group_id == 1){
                    $depts = [1,2,3,4,5,6,8];
            }
            else{
                    $depts = [1,2,3,4,6,8];
            }
            $userIds = User::whereIn('department_id',$depts)->pluck('id');
            if($from == $to){
                $users = fieldLogin::orderBy('field_login.created_at','DESC')
                            ->whereIn('user_id',$userIds)
                            ->where('field_login.created_at','LIKE',$from."%")
                            ->leftjoin('users','field_login.user_id','users.id')
                            ->select('field_login.*','users.name')  
                            ->get();
            }else{
                $users = fieldLogin::orderBy('field_login.created_at','DESC')
                            ->whereIn('user_id',$userIds)
                            ->where('field_login.created_at','>',$from)
                            ->where('field_login.created_at','<',$to)
                            ->leftjoin('users','field_login.user_id','users.id')
                            ->select('field_login.*','users.name')
                            ->get();
            }
            return view('hrlatelogins',['users'=>$users]);
    }
    public function breakhistory(Request $request){
            $from = $request->from;
            $to = $request->to;
            if(Auth::user()->group_id == 1){
                    $depts = [1,2,3,4,5,6,8];
            }
            else{
                    $depts = [1,2,3,4,6,8];
            }
            $userIds = User::whereIn('department_id',$depts)->pluck('id');
            if($from == $to){
                $breaks = BreakTime::orderBy('breaktime.created_at','DESC')
                            ->whereIn('user_id',$userIds)
                            ->where('breaktime.created_at','LIKE',$from."%")
                            ->leftjoin('users','breaktime.user_id','users.id')
                            ->select('breaktime.*','users.name')  
                            ->get();
            }else{
                $breaks = BreakTime::orderBy('breaktime.created_at','DESC')
                            ->whereIn('user_id',$userIds)
                            ->where('breaktime.created_at','>',$from)
                            ->where('breaktime.created_at','<',$to)
                            ->leftjoin('users','breaktime.user_id','users.id')
                            ->select('breaktime.*','users.name')
                            ->get();
            }
            return view('breaks',['breaks'=>$breaks]);
    }
    public function logistic(Request $request)
    {
        
      $u=$request->framework;
     
        $r= Order::where('id',$request->logicid)->update(['logistic' => $u,]);
         return back()->with('info','Assigned successfully !');
    }
    
}
