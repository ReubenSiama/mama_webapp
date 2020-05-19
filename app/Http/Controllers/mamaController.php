<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Notifiable;
use App\Mail\registration;
use App\Mail\FieldLoginRequest;
use App\EmployeeDetails;
use App\FLOORINGS;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;
use App\Notifications\FiledLogin;
use App\Department;
use Notification;
use App\User;
use App\CustomerBrands;
use Session;
use App\Group;
use App\WaradReport;
use App\Builder;
use App\UpdatedReport;
use App\ProjectUpdate;
use App\AssignCategory;
use App\Ward;
use App\SubWard;
use App\Country;
use App\Territory;
use App\WardAssignment;
use App\ProjectDetails;
use App\ConsultantDetails;
use App\ContractorDetails;
use App\ProcurementDetails;
use App\OwnerDetails;
use App\SiteAddress;
use App\SiteEngineerDetails;
use App\State;
use App\Zone;
use App\loginTime;
use App\Requirement;
use Auth;
use App\attendance;
use Validator;
use Hash;
use App\salesassignment;
use App\BankDetails;
use App\AssetInfo;
use App\Certificate;
use App\Category;
use App\SubCategory;
use App\CategoryPrice;
use App\ManufacturerDetail;
use App\RoomType;
use App\ActivityLog;
use App\RecordData;
use App\Order;
use App\Map;
use App\brand;
use App\WardMap;
use App\Point;
use App\ZoneMap;
use App\SubWardMap;
use App\Asset;
use App\Check;
use App\Manufacturer;
use App\ManufacturerProduce;
use App\MamahomeAsset;
use App\ProjectImage;
use App\Tlwards;
use App\FieldLogin;
use Carbon\Carbon;
use App\TrackLocation;
use App\Report;
use App\Salescontact_Details;
use App\Manager_Deatils;
use App\Mprocurement_Details;
use App\Mowner_Deatils;
use App\Gst;
use Spatie\Activitylog\Models\Activity;
// use LogsActivity;
use App\Quotation;
use App\MamahomePrice;
use DB;
use App\PaymentDetails;
use App\CustomerGst;
use App\Supplierdetails;

date_default_timezone_set("Asia/Kolkata");
class mamaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addDepartment(Request $request)
    {
        $department = New Department;
        $department->dept_name = $request->dept_name;
        if($department->save()){
            $activity = new ActivityLog;
            $activity->time = date('Y-m-d H:i A');
            $activity->employee_id = Auth::user()->employeeId;
            $activity->activity = Auth::user()->name." has added a new department named ".$request->dept_name." at ".date('H:i A');
            $activity->save();
            return back()->with('Success','New Department Added');
        }else{
            $activity = new ActivityLog;
            $activity->time = date('Y-m-d H:i A');
            $activity->employee_id = Auth::user()->employeeId;
            $activity->activity = Auth::user()->name." has tried to add a new department on ".date('H:i A')." but failed";
            $activity->save();
            return back()->with('Error','Seems there is some problem in the input');
        }
    }
    public function deleteDepartment(Request $request)
    {
        User::where('department_id',$request->id)->update([
            'department_id' => 1
        ]);
        Department::where('id',$request->id)->delete();
        return back()->with('Success','Department deleted');
    }
    public function addEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employeeId' => 'required|unique:users'
        ]);
        if ($validator->fails()) {
            return back()
                    ->with('NotAdded','User already exists')
                    ->withErrors($validator)
                    ->withInput();
        }
        $user = New User;
        $user->employeeId = $request->employeeId;
        $user->department_id = $request->dept;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->group_id = $request->designation;
        $user->contactNo = '';
        $user->password = bcrypt('mama@home123');
        if($user->save()){
            $empdetails = new EmployeeDetails;
            $empdetails->employee_id = $user->employeeId;
            $empdetails->save();
            if($user->department_id == 1 && $user->group_id == 6){
                $assignment = new WardAssignment;
                $assignment->user_id = $user->id;
                $assignment->subward_id = 1;
                $assignment->status = "Completed";
                $assignment->save();
            }
            return back()->with('Added','Employee Added Successfully');
        }else{
            return back()->with('NotAdded','Employee add unsuccessful');
        }
    }
    public function deleteEmployee(Request $request)
    {
        User::where('id',$request->id)->delete();
        return back()->with('UserSuccess','User deleted');
    }
    public function assignDesignation($id,Request $request)
    {
        User::where('id',$id)->update([
            'group_id' => $request->designation
        ]);
        return back();
    }
    public function addDesignation(Request $request)
    {
        $group = New Group;
        $group->group_name = $request->desig_name;
        if($group->save()){
            return back();
        }else{
            return back();
        }
    }
    public function deleteDesignation(Request $request)
    {
        Group::where('id',$request->id)->delete();
        return back();
    }
    public function addCountry(Request $request)
    {
        $country = New Country;
        $country->country_code = $request->code;
        $country->country_name = $request->name;
        if($country->save()){
            return back();
        }else{
            return back();
        }
    }
    // public function addState(Request $request)
    // {
    //  $state = New State;
    //  $state->zone_id = $request->zone_id;
    //  $state->state_name = $request->state_name;
    //  $state->save();
    //  return back();
    // }
    public function addZone(Request $request)
    {
        $check = Zone::where('zone_name',$request->zone_name)->first();
        if(count($check) != 0){
            return back()->with('ErrorZone','Zone Name exists. Try another name');
        }
        // $imageName1 = time().'.'.request()->image->getClientOriginalExtension();
        // $request->image->move(public_path('zoneimages'),$imageName1);

            $imageName1 = $request->file('image');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/zoneimages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');




        // $extension = $request->file('upload_file')->extension();
        //  $mimetype = $request->file('upload_file')->getMimeType();
        // $path = Storage::disk('spaces')->putFileAs('uploads',$request->file('upload_file'),time().'.'.$extension);
        $zone = New Zone;
        $zone->country_id = $request->sId;
        $zone->zone_name = $request->zone_name;
        $zone->zone_number = $request->zone_no;
        $zone->zone_image = $imageFileName;
        $zone->save();
        return back();
    }
     
     public function view_zone(Request $id){
    
        $zone = Zone::where('id',$id->zoneId)->first();
        return view('viewzone',['zone'=>$zone]);
    }
    public function save_edit(Request $request){

        //  $zoneimage = time().'.'.request()->image->getClientOriginalExtension();
        // $request->image->move(public_path('zoneimages'),$zoneimage);
            
             $zoneimage = $request->file('image');
             $imageFileName = time() . '.' . $zoneimage->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/zoneimages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($zoneimage), 'public');




        Zone::where('id',$request->zoneId)->update([
            'zone_name'=> $request->zone_name,
            'zone_number' => $request->zone_no,
             'zone_image' => $imageFileName,
        ]);

        return redirect()->back()->with('Success','zone updated sucessfully');
    }
    public function addWard(Request $request)
    {
        $cCode = Country::where('id',$request->country)->pluck('country_code')->first();
        $zone = Zone::where('id', $request->zone)->pluck('zone_number')->first();
         
            $imageName = $request->file('image');
             $imageFileName = time() . '.' . $imageName->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/wardImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName), 'public');


        // $imageName = time().'.'.request()->image->getClientOriginalExtension();
        // $request->image->move(public_path('wardImages'),$imageName);
        $ward = New Ward;
        $ward->country_id = $request->country;
        $ward->zone_id = $request->zone;
        // $ward->state_id = $request->state;
        $ward->ward_name = "MH_".$cCode."_".$zone."_".$request->name;
        $ward->ward_image = $imageFileName;
        $ward->save();
        return back();

    }
    public function salesreport()
    {
        $group = Group::where('group_name','Listing Engineer')->pluck('id')->first();
        $users = User::where('group_id',$group)
                    ->where('department_id','!=','10')
                    ->get();
                               
        return view('assistantmanager.salesreport',['users'=>$users,'pageName'=>'Sales Report']);
    }
    public function getSalesReport(Request $request)
    {
        $le = '';
        $fromdate = '';
        $todate = '';
        $userId = array();
        $totallistings = array();
        $confirmlistings = array();
        $initiatedlistings = array();
        $initiatedcount = array();
        $username = '';
        $totalcount = array();
        $confirmcount =array();
        
        $group = Group::where('group_name','Listing Engineer')->pluck('id')->first();
        $users = User::where('group_id',$group)
                    ->where('department_id','!=','10')
                    ->get();
        if(!$request->listengs || !$request->fromdate || !$request->todate) //If any one of the inputs is null
        {
            return back()->with('error','Please Select All The Fields');
        }
        else //All three inputs are given
        {
            $le = $request->listengs;
            $fromdate = $request->fromdate;
            $todate = $request->todate;
            if($le == 'All') //All listing engineers should be listed 
            {
                $group = Group::where('group_name','Listing Engineer')->pluck('id')->first();
                $users = User::where('group_id',$group)
                            ->where('department_id','!=','10')
                            ->get();   
                $i = 0;
                $userid = array();
                $username = array();
                foreach ($users as $user) {
                    $userid[$i++] = $user->id;
                }
                $i = 0;
                if($fromdate == $todate) //Both times are same
                {
                    foreach ($users as $user) 
                    {
                        $totalcount[$user->id] = ProjectDetails::join('users','project_details.listing_engineer_id','=','users.id')
                                                ->leftjoin('requirements','requirements.project_id','=','project_details.project_id')
                                                ->where('project_details.created_at','LIKE',$fromdate.'%')
                                                ->where('users.id', $user->id)
                                                ->count();       
                        $confirmcount[$user->id] = ProjectDetails::join('users','project_details.listing_engineer_id','=','users.id')
                                                ->leftjoin('requirements','requirements.project_id','=','project_details.project_id')
                                                ->where('project_details.created_at','LIKE',$fromdate.'%')
                                                ->where('users.id', $user->id)
                                                ->where('requirements.status','Order Confirmed')
                                                ->count();

                        $initiatedcount[$user->id] = ProjectDetails::join('users','project_details.listing_engineer_id','=','users.id')
                                        ->leftjoin('requirements','requirements.project_id','=','project_details.project_id')
                                        ->where('project_details.created_at','>',$fromdate.'%')
                                        ->where('users.id',$user->id)
                                        ->where('requirements.status','Order Initiated')
                                        ->count();
                    }   
                }
                else //Both dates are different
                {
                    $fromdate .= ' 00:00:00';
                    $todate .= ' 23:59:59';
                    foreach ($users as $user) 
                    {
                        $totalcount[$user->id] = ProjectDetails::join('users','project_details.listing_engineer_id','=','users.id')
                                                ->leftjoin('requirements','requirements.project_id','=','project_details.project_id')
                                                ->where('project_details.created_at','>',$fromdate)
                                                ->where('project_details.created_at','<',$todate)
                                                ->where('users.id', $user->id)
                                                ->count();

                    
                        $confirmcount[$user->id] = ProjectDetails::leftjoin('requirements','requirements.project_id','=','project_details.project_id')
                                                ->where('project_details.created_at','>',$fromdate)
                                                ->where('project_details.created_at','<',$todate)
                                                ->where('project_details.listing_engineer_id', $user->id)
                                                ->where('requirements.status','Order Confirmed')
                                                ->count();

                        $initiatedcount[$user->id] = ProjectDetails::leftjoin('requirements','requirements.project_id','=','project_details.project_id')
                                        ->where('project_details.created_at','>',$fromdate)
                                        ->where('project_details.created_at','<',$todate)
                                        ->where('project_details.listing_engineer_id', $user->id)
                                        ->where('requirements.status','Order Initiated')
                                        ->count();
                    }     
                }
            }//End All Listing Engineers Selection 
            
            else //Only one selection of Listing Engineer is made
            {
                $username = User::where('id',$le)->first();
                if($fromdate == $todate) //Both times are same
                {                   
                    $totalcount[$le] = ProjectDetails::where('project_details.created_at','LIKE',$fromdate.'%')
                                        ->where('listing_engineer_id',$le)
                                        ->count();

                    $confirmcount[$le] =  ProjectDetails::
                                            leftjoin('requirements','requirements.project_id','=','project_details.project_id')
                                            ->where('project_details.created_at','LIKE',$fromdate.'%')
                                            ->where('listing_engineer_id',$le)
                                            ->where('requirements.status','Order Confirmed')
                                            ->count();

                    $initiatedcount[$le] = ProjectDetails::
                                            leftjoin('requirements','requirements.project_id','=','project_details.project_id')
                                            ->where('project_details.created_at','LIKE',$fromdate.'%')
                                            ->where('listing_engineer_id',$le)
                                            ->where('requirements.status','Order Initiated')
                                            ->count();                                         
                }
                else //Both dates are different
                {
                    $fromdate .= ' 00:00:00';
                    $todate .= ' 23:59:59';
                    
                    $totalcount[$le] = ProjectDetails::where('listing_engineer_id',$le)
                                        ->where('project_details.created_at','>',$fromdate)
                                        ->where('project_details.created_at','<',$todate)
                                        ->count();                
                    
                    $confirmcount[$le] =  ProjectDetails::join('requirements','requirements.project_id','=','project_details.project_id')
                                            ->where('project_details.created_at','>',$fromdate)
                                            ->where('project_details.created_at','<',$todate)
                                            ->where('project_details.listing_engineer_id',$le)
                                            ->where('requirements.status','Order Confirmed')
                                            ->count();

                    $initiatedcount[$le] = ProjectDetails::join('requirements','requirements.project_id','=','project_details.project_id')
                                            ->where('project_details.created_at','>',$fromdate)
                                            ->where('project_details.created_at','<',$todate)
                                            ->where('project_details.listing_engineer_id',$le)
                                            ->where('requirements.status','Order Initiated')
                                            ->count();
                }
            }//End One selection of Listing Engineer     
        }//End All three Inputs Provided
        return view('assistantmanager.salesreport',['le'=>$le, 'fromdate'=>$fromdate,'todate'=>$todate,'totallistings' => $totallistings,'confirmlistings'=>$confirmlistings,'initiatedlistings'=> $initiatedlistings,'users'=>$users,'pageName' => 'Sales Report','totalcount'=>$totalcount,'username'=>$username,'confirmcount'=>$confirmcount,'initiatedcount'=>$initiatedcount]);
    }
    public function addSubWard(Request $request)
    {
        $ward = Ward::where('id',$request->ward)->pluck('ward_name')->first();
        $zone = Ward::where('id',$request->ward)->pluck('zone_id')->first();

             $imageName = $request->file('image');
             $imageFileName = time() . '.' . $imageName->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/subWardImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName), 'public');






        // $imageName = time().'.'.request()->image->getClientOriginalExtension();
        // $request->image->move(public_path('subWardImages'),$imageName);
        $subWard = New SubWard;
        $subWard->ward_id = $request->ward;
        $subWard->sub_ward_name = $ward.$request->name;
        $subWard->sub_ward_image = $imageName;
        $subWard->zone_id = $zone;
        if($subWard->save()){
            return back();
        }else{
            return back();
        }
    }
    public function assignWards($id,Request $request)
    {

       
        $assigned = WardAssignment::where('user_id',$id)->first();
        if($assigned != NULL){
            $assigned->prev_subward_id = $assigned->subward_id;
            $assigned->subward_id = $request->subward;
            $assigned->status = "Not Completed";
            $assigned->time = $request->time;

            $assigned->save();
        }else{
           
            $assignWard = new WardAssignment;
            $assignWard->user_id = $id;
            $assignWard->subward_id = $request->subward;
            $assignWard->prev_subward_id = '';
            $assignWard->status = 'Not Completed';
            $assignWard->time = $request->time;
            
            $assignWard->save();
        }
        
      $user = User::findOrFail($id);
      $subward = SubWard::findOrFail($request->subward);
       $data = new WaradReport;
       $data->user_id = $id;
       $data->sub_ward_id = $request->subward;
       $data->tlid =Auth::user()->id;
       $data->save();
       
       
       
       
       
       
        $activity = New ActivityLog;
        $activity->time = date('H:i:s A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->activity = Auth::user()->name . " has assigned " . $user->name . " to a new Sub Ward " . $subward->sub_ward_name;
        $activity->typeofactivity = "Assignation of sub ward";
        $activity->sub_ward_id = $request->subward;
        $activity->save();

        return back()->with('Success','Assigned Successfully');
    }
    public function addProject(Request $request)
    {
             
             $result = (new HomeController)->getid();
             // dd($result);

        $point = 0;
        // counting points
        // project name
        $point = $request->pName != null ? $point+2 : $point+0;
        // road name
        $point = $request->rName != null ? $point+2 : $point+0;        
        // road width
        $point = $request->rWidth != null ? $point+4 : $point+0;
        // Construction type
        $point = $request->constructionType != null ? $point+5 : $point+0;
        // interested in rmc
        $point = $request->rmcinterest != null ? $point+3 : $point+0;
        // type of contract
        $point = $request->contract != null ? $point+6 : $point+0;
        // project status
        $point = $request->status != null ? $point+5 : $point+0;
        // project type
        $point = $request->basement != null && $request->ground != null ? $point+5 : $point+0;
        // project size
        $point = $request->pSize != null ? $point+8 : $point+0;
        // budgettype
        $point = $request->budgetType != null ? $point+3 : $point+0;
        // total budget
        $point = $request->budget != null ? $point+5 : $point+0;
        // project Image
        $point = $request->pImage != null ? $point+6 : $point+0;
        // room types
        $point = $request->roomType[0] != null ? $point+5 : $point+0;
        // owner details
        $point = $request->oName != null ? $point+5 : $point+0;
        $point = $request->oEmail != null ? $point+5 : $point+0;
        $point = $request->oContact != null ? $point+5 : $point+0;
        // contractor details
        $point = $request->cName != null ? $point+3 : $point+0;
        $point = $request->cEmail != null ? $point+3 : $point+0;
        $point = $request->cContact != null ? $point+3 : $point+0;
        // consultant details
        // $point = $request->coName != null ? $point+3 : $point+0;
        // $point = $request->coEmail != null ? $point+3 : $point+0;
        // $point = $request->coContact != null ? $point+3 : $point+0;
        // site engineer details
        $point = $request->eName != null ? $point+3 : $point+0;
        $point = $request->eEmail != null ? $point+3 : $point+0;
        $point = $request->eContact != null ? $point+3 : $point+0;
        // procurement details
        $point = $request->prName != null ? $point+3 : $point+0;
        $point = $request->pEmail != null ? $point+3 : $point+0;
        $point = $request->prPhone != null ? $point+3 : $point+0;
        $point = $request->remarks != null ? $point+10 : $point+0;
        
        // store points to database
        $points = new Point;
        $points->user_id = Auth::user()->id;
        $points->point = $point;
        $points->type = "Add";
        $points->reason = "Adding a project";
        $points->save();

        $cType = count($request->constructionType);
        $type = $request->constructionType[0];
        $otherApprovals = "";
        $projectimage = "";
        if($cType != 1){
            $type .= ", ".$request->constructionType[1];
        }
       $bapart = count($request->apart);
        if($request->apart != 0){
            $btype = implode(", ",$request->apart);
        }

     
        $btype = count($request->budgetType);
        if($request->budgetType != 0){
            $type2 = implode(", ",$request->budgetType);
        }
        else{
            $type2 = null;
        }
        $statusCount = count($request->status);
        $validator = Validator::make($request->all(), [
                'address' => 'required',
                'basement' => 'required',
                'ground' => 'required',
                'pName' => 'required',
                'rName' => 'required',
                'status' => 'required',
              
            ]);
            if ($validator->fails()) {
                return back()
                ->with('Error','Please check some of the fields again')
                ->withErrors($validator)
                ->withInput();
            }
            $statuses = $request->status[0];
            if($statusCount > 1){
                for($i = 1; $i < $statusCount; $i++){
                    $statuses .= ", ".$request->status[$i];
                }
            }
            $basement = $request->basement;
            $ground = $request->ground;
            $floor = $basement + $ground + 1;
            $length = $request->length;
            $breadth = $request->breadth;
            $length = $request->length;
            $breadth = $request->breadth;
            $size = $length * $breadth;
            if($request->mApprove != NULL){

                      
              $imageName1 = $request->file('mApprove');
             $imageFileName1 = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/projectImages/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($imageName1), 'public');
               

                // $imageName1 = time().'.'.request()->mApprove->getClientOriginalExtension();
                // $request->mApprove->move(public_path('projectImages'),$imageName1);
            }else{
                $imageFileName1 = "N/A";
            }

            $i = 0;
            if($request->oApprove){
                foreach($request->oApprove as $oApprove){

             $imageName2 = $oApprove;
             $imageFileName = $i.time() . '.' . $imageName2->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/projectImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName2), 'public');


                  ;
                    // $imageName2 = $i.time().'.'.$oApprove->getClientOriginalExtension();
                    // $oApprove->move(public_path('projectImages'),$imageName2);
                    if($i == 0){
                        $otherApprovals .= $imageFileName;
                    }else{
                        $otherApprovals .= ", ".$imageFileName;
                    }
                    $i++;
                }
            }
            
            $i = 0;
            if($request->pImage){
                foreach($request->pImage as $pimage){

                       $imageName3 = $pimage;
                     $imageFileName = $i.time() . '.' . $imageName3->getClientOriginalExtension();
                     $s3 = \Storage::disk('azure');
                     $filePath = '/projectImages/' . $imageFileName;
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
             

              if(count($request->subward_id) != 0){
                 $ward= $request->subward_id;
             
              }else{

             $ward=WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
              }

            $projectdetails = New ProjectDetails;
            $projectdetails->sub_ward_id = $ward;
            $projectdetails->project_name = $request->pName;
            $projectdetails->road_width = $request->rWidth;
            $projectdetails->construction_type = $type;
            $projectdetails->interested_in_rmc = $request->rmcinterest;
            $projectdetails->interested_in_loan = $request->loaninterest;
            $projectdetails->Kitchen_Cabinates = $request->dandwinterest;
            $projectdetails->interested_in_doorsandwindows = $request->upvc;
            $projectdetails->interested_in_premium = $request->premium;
            $projectdetails->road_name = $request->rName;
            $projectdetails->municipality_approval = $imageFileName1;
            $projectdetails->other_approvals = $otherApprovals;
            $projectdetails->project_status = $statuses;
            $projectdetails->basement = $basement;
            $projectdetails->ground = $ground;
            $projectdetails->project_type = $floor;
            $projectdetails->length = $length;
            $projectdetails->breadth = $breadth;
            $projectdetails->plotsize = $size;
            $projectdetails->project_size = $request->pSize;
            $projectdetails->budget = $request->budget;
            $projectdetails->image = $projectimage;
            $projectdetails->listing_engineer_id = Auth::user()->id;
            $projectdetails->remarks = $request->remarks;
            $projectdetails->contract = $request->contract;
            $projectdetails->budgetType = $type2;
            $projectdetails->automation=$request->automation;
            $projectdetails->brilaultra=$request->brila;
            $projectdetails->res = $btype;
            $projectdetails->solar = $request->solar;
            $projectdetails->Ininterior = $request->Ininterior;
            $projectdetails->refcustid = $request->cid;
            $projectdetails->silo = $request->silo;



          $projectdetails->save();
       // $activity = new ActivityLog;
       //  $activity->time = date('Y-m-d H:i A');
       //  $activity->employee_id = Auth::user()->employeeId;
       //  $activity->activity = Auth::user()->name." has added a new project id: ".$projectdetails->id." at ".date('H:i A');
       //  $project = ProjectDetails::where('project_id',$projectdetails->project_id)->pluck('sub_ward_id')->first();
       //  $uproject = ProjectDetails::where('project_id',$projectdetails->project_id)->pluck('updated_by')->first();
       //  $qproject = ProjectDetails::where('project_id',$projectdetails->project_id)->pluck('quality')->first();
       //  $fproject = ProjectDetails::where('project_id',$projectdetails->project_id)->pluck('followup')->first();
       //  $eproject = Requirement::where('project_id',$projectdetails->project_id)->pluck('generated_by')->first();
       //  $activity->updater = Auth::user()->id;
       //  $activity->quality = $qproject;
       //  $activity->followup = $fproject;
       //  $activity->project_id = $projectdetails->project_id;
       //  $activity->sub_ward_id = $project;
       //  $activity->enquiry  = $eproject;
       //  $activity->typeofactivity = "Add Project" ;
       //  $activity->save();

$room_types = $request->roomType[0]." (".$request->number[0].")";
            $count = count($request->roomType);
            for($i = 0;$i<$count;$i++){
                $roomtype = new RoomType;
                $roomtype->floor_no = $request->floorNo[$i];
                $roomtype->room_type = $request->roomType[$i];
                $roomtype->no_of_rooms = $request->number[$i];
                $roomtype->project_id = $projectdetails->project_id;
                $roomtype->save();
            }

            $siteaddress = New SiteAddress;
            $siteaddress->project_id = $projectdetails->project_id;
            $siteaddress->latitude = $request->latitude;
            $siteaddress->longitude = $request->longitude;
            $siteaddress->address = $request->address;
            $siteaddress->save();
            $ownerDetails = New OwnerDetails;
            $ownerDetails->project_id = $projectdetails->project_id;
            $ownerDetails->owner_name = $request->oName;
            $ownerDetails->owner_email = $request->oEmail;
            $ownerDetails->owner_contact_no = $request->oContact;
            $ownerDetails->whatsapp = $request->owhatsapp;

        $ownerDetails->save();

        $contractorDetails = New ContractorDetails;
        $contractorDetails->project_id = $projectdetails->project_id;
        $contractorDetails->contractor_name = $request->cName;
        $contractorDetails->contractor_email = $request->cEmail;
        $contractorDetails->contractor_contact_no = $request->cContact;
        $contractorDetails->whatsapp = $request->cwhatsapp;
        
        $contractorDetails->save();

        $consultantDetails = New ConsultantDetails;
        $consultantDetails->project_id = $projectdetails->project_id;
        $consultantDetails->consultant_name = $request->coName;
        $consultantDetails->consultant_email = $request->coEmail;
        $consultantDetails->consultant_contact_no = $request->coContact;
        $consultantDetails->whatsapp = $request->ccwhatsapp;

        $consultantDetails->save();

        $siteEngineerDetails = New SiteEngineerDetails;
        $siteEngineerDetails->project_id = $projectdetails->project_id;
        $siteEngineerDetails->site_engineer_name = $request->eName;
        $siteEngineerDetails->site_engineer_email = $request->eEmail;
        $siteEngineerDetails->site_engineer_contact_no = $request->eContact;
        $siteEngineerDetails->whatsapp = $request->swhatsapp;

        $siteEngineerDetails->save();

        $procurementDetails = New ProcurementDetails;
        $procurementDetails->project_id = $projectdetails->project_id;
        $procurementDetails->procurement_name = $request->prName;
        $procurementDetails->procurement_email = $request->pEmail;
        $procurementDetails->procurement_contact_no = $request->prPhone;
        $procurementDetails->whatsapp = $request->pwhatsapp;

        $procurementDetails->save();
 
        $builder = New Builder;
        $builder->project_id = $projectdetails->project_id;
        $builder->builder_name = $request->bName;
        $builder->builder_email = $request->bEmail;
        $builder->builder_contact_no = $request->bPhone;
        $builder->whatsapp = $request->bwhatsapp;
        $builder->save();
       $no = $request->prPhone;
        $pid = $projectdetails->project_id;
        $newtime = date('H:i A');
        // $newtime = date('H:i A',strtotime('+5 hour +30 minutes',strtotime($time)));
        loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'lastListingTime' => $newtime
        ]);
        $first = loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->first();
        $assigned = subWard::where('id',$ward)->pluck('sub_ward_name')->first();
        if($first->firstListingTime == NULL){
            loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
                'firstListingTime' => $newtime
            ]);
        }
        $check = mktime(12,00);
        $checktime = date('H:i A',$check);
        $morningcheck=loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->first();
        if($newtime <= $checktime){
            if($morningcheck->noOfProjectsListedInMorning == NULL){
                loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
                    'noOfProjectsListedInMorning' => 1
                ]);                
            }else{
                $number=loginTime::where('user_id',Auth::user()->id)
                    ->where('logindate',date('Y-m-d'))
                    ->pluck('noOfProjectsListedInMorning')
                    ->first();
                loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
                    'noOfProjectsListedInMorning' => $number + 1
                ]); 
            }
        }
        if($morningcheck->TotalProjectsListed == NULL){
             loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
                    'TotalProjectsListed' => 1
                ]);
        }else{
            $number2=loginTime::where('user_id',Auth::user()->id)
                    ->where('logindate',date('Y-m-d'))
                    ->pluck('TotalProjectsListed')
                    ->first();
                loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
                    'TotalProjectsListed' => $number2 + 1
                ]);
        }
        $subward = Subward::where('id',$ward)->pluck('sub_ward_name')->first();
      $text = "Project Added Successfully in Subward : ".$subward.".<br><a  class='btn btn-success btn-xs' href='viewProjects?no=".$no." && id=".$pid."'>Click Here</a><br>To View Approximate Material Calculation";
        return back()->with('test',$text);
    }
    public function updateProject($id, Request $request)
    {
        
        $point = 0;
        $project = ProjectDetails::where('project_id',$id)->first();
        $projectimages = ProjectImage::where('project_id',$id)->first();
        $today = date('Y-m-d');
        $created = date('Y-m-d',strtotime($project->created_at));
        $updated = date('Y-m-d',strtotime($project->updated_at));
        if($today != $created || $today != $updated){
            if($request->quality && $project->quality != $request->quality){
                $points = new Point;
                $points->user_id = Auth::user()->id;
                $points->point = 20;
                $points->type = "Add";
                $points->reason = "Marking project Id: <a href=\"\">".$id."</a> as ".$request->quality;
                $points->save();
            }
            
            $point = $request->pName != null && $project->project_name != $request->pName ? $point+2 : $point+0;
            // road name
            $point = $request->rName != null && $project->road_name != $request->rName ? $point+2 : $point+0;        
            // road width
            $point = $request->rWidth != null && $project->road_width != $request->rWidth ? $point+4 : $point+0;
            // Construction type
            if($request->construction_type != 0){
                $construction_types = implode(", ",$request->constructionType);
            }else{
                $construction_types = $project->construction_type;
            }
            $point = $request->constructionType != null && $project->construction_type != $construction_types ? $point+5 : $point+0;
            // interested in rmc
            $point = $request->rmcinterest != null && $project->interested_in_rmc != $request->rmcinterest ? $point+3 : $point+0;
            // type of contract
            $point = $request->contract != null && $project->contract != $request->contract ? $point+6 : $point+0;
            // project status
            if(count($request->status) != 0){
                $statuses = implode(", ",$request->status);
            }else{
                $statuses = $project->project_status;
            }
            $point = $request->status != null && $project->project_status != $statuses ? $point+5 : $point+0;
            // project type
            $point = ($request->basement != null && $request->ground != null) && ($request->basement != $project->basement && $request->ground != $project->ground) ? $point+5 : $point+0;
            // project size
            $point = $request->pSize != null && $project->project_size != $request->pSize ? $point+8 : $point+0;
            // budgettype
            $point = $request->budgetType != null && $project->budgetType != $request->budgetType ? $point+3 : $point+0;
            // total budget
            $point = $request->budget != null && $project->budget != $project->budget ? $point+5 : $point+0;
            // project Image
            $point = $request->pImage != null ? $point+6 : $point+0;
            // room types
            $point = $request->floorNo[0] != null ? $point+5 : $point+0;
            // owner details
            $point = $request->oName != null && $project->ownerdetails->owner_name != $request->oName ? $point+5 : $point+0;
            $point = $request->oEmail != null && $project->ownerdetails->owner_email != $request->oEmail ? $point+5 : $point+0;
            $point = $request->oContact != null && $project->ownerdetails->owner_contact_no != $request->oContact ? $point+5 : $point+0;
            // contractor details
            $point = $request->cName != null && $project->contractordetails->contractor_name != $request->cName ? $point+3 : $point+0;
            $point = $request->cEmail != null && $project->contractordetails->contractor_email != $request->cEmail ? $point+3 : $point+0;
            $point = $request->cContact != null && $project->contractordetails->contractor_contact_no != $request->cContact ? $point+3 : $point+0;
            // consultant details
            // $point = $request->coName != null ? $point+3 : $point+0;
            // $point = $request->coEmail != null ? $point+3 : $point+0;
            // $point = $request->coContact != null ? $point+3 : $point+0;
            // site engineer details
            $point = $request->eName != null && $project->siteengineerdetails->site_engineer_name != $request->eName ? $point+3 : $point+0;
            $point = $request->eEmail != null && $project->siteengineerdetails->site_engineer_email != $request->eEmail ? $point+3 : $point+0;
            $point = $request->eContact != null && $project->siteengineerdetails->site_engineer_contact_no != $request->eContact ? $point+3 : $point+0;
            // procurement details
            $point = $request->prName != null && $project->procurementdetails->procurement_name != $request->prName ? $point+3 : $point+0;
            $point = $request->pEmail != null && $project->procurementdetails->procurement_email != $request->pEmail ? $point+3 : $point+0;
            $point = $request->prPhone != null && $project->procurementdetails->procurement_contact_no != $request->prPhone ? $point+3 : $point+0;
            $point = $request->remarks != null && $project->remarks != $request->remarks ? $point+10 : $point+0;
            
            // store points to database
            if($point != 0){
                $points = new Point;
                $points->user_id = Auth::user()->id;
                $points->point = $point;
                $points->type = "Add";
                $points->reason = "Updating a project";
                $points->save();
            }
        }
              

        $basement = $request->basement;
        $ground = $request->ground;
        $floor = $basement + $ground + 1;
        $length = $request->length;
        $breadth = $request->breadth;
        $size = $length * $breadth;
        if($request->mApprove != NULL){

             $imageName1 = $request->file('mApprove');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/projectImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');




            // $imageName1 = time().'.'.request()->mApprove->getClientOriginalExtension();
            // $request->mApprove->move(public_path('projectImages'),$imageName1);
            ProjectDetails::where('project_id',$id)->update([
                'municipality_approval' => $imageFileName
            ]);
        }
        if($request->oApprove != 0){
            $i = 0;
            $otherApprovals = "";
            foreach($request->oApprove as $oApprove){

              $imageName2 = $oApprove;
             $imageFileName =$i. time() . '.' . $imageName2->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/projectImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName2), 'public');



                // $imageName2 = $i.time().'.'.$oApprove->getClientOriginalExtension();
                // $oApprove->move(public_path('projectImages'),$imageName2);
                if($i == 0){
                    $otherApprovals .= $imageFileName;
                }else{
                    $otherApprovals .= ", ".$imageFileName;
                }
                $i++;
            }
            ProjectDetails::where('project_id',$id)->update([
                'other_approvals' => $otherApprovals
            ]);
        }
        if($request->pImage){
                        if($request->pImage != null){
                                   $i = 0;
                                    $projectimage = ""; 

                                    foreach($request->pImage as $pimage){


                                              $imageName3 = $pimage;
                                             $imageFileName =$i. time() . '.' . $imageName3->getClientOriginalExtension();
                                             $s3 = \Storage::disk('azure');
                                             $filePath = '/projectImages/' . $imageFileName;
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
                            $statusCount = count($request->status);
                            $statuses = $request->status[0];
                            if($statusCount > 1){
                                for($i = 1; $i < $statusCount; $i++){
                                    $statuses .= ", ".$request->status[$i];
                                }
                            }
                            $project_image = new ProjectImage;
                            $project_image->project_id = $id;
                            $project_image->project_status = $statuses;
                            $project_image->image = $projectimage;
                            $project_image->save();
        }
        else{
             $project_image = new ProjectImage;
             $project_image->image = '';
        }

        if($request->remarks != NULL){
            ProjectDetails::where('project_id',$id)->update([
                'remarks' => $request->remarks
            ]);
        }
        $cType = count($request->constructionType);
        $type = $request->constructionType[0];
        if($cType != 1){
            $type .= ", ".$request->constructionType[1];
        }
        $statusCount = count($request->status);
        $statuses = $request->status[0];
        if($statusCount > 1){
            for($i = 1; $i < $statusCount; $i++){
                $statuses .= ", ".$request->status[$i];
            }
        }
        $projectBeforeUpdate = ProjectDetails::where('project_id',$id)->first();

        //
        if($request->apart != 0){
            $btype = implode(", ",$request->apart);
        }else{
           $btype = "null";  
        }
        
        if($request->follow == "Yes"){
            $pro = ProjectDetails::where('project_id',$id)->first();
             $pro->follow_up_by = Auth::user()->id;
             $pro->save();
        }
 $projectval = ProjectDetails::where('project_id',$id)->first();
        
      
        $result = Input::except('pImage','_token');
       
     $new = new UpdatedReport;
      $new->project_id = $request->project_id;
      $new->user_id = Auth::user()->id;
      $new->quntion = $request->quntion;
      $new->remarks = $request->remarks;
      $new->allfileds = serialize($result);
      $new->old = serialize($projectval);
      $new->p_p_c_id =ProjectDetails::where('project_id',$id)->pluck('p_p_c_id')->first(); 
      $new->save(); 

        $projectdetails = ProjectDetails::where('project_id',$id)->first();
            $projectdetails->project_name = $request->pName;
            $projectdetails->road_name = $request->rName;
            $projectdetails->road_width = $request->rWidth;
            $projectdetails->project_status = $statuses;
            $projectdetails->brilaultra = $request->brila;
            $projectdetails->basement = $basement;
            $projectdetails->ground = $ground;
            $projectdetails->length = $length;
            $projectdetails->breadth = $breadth;
            $projectdetails->plotsize = $size;
            $projectdetails->quality = ($request->quality != null ? $request->quality : 'Unverified');
            $projectdetails->project_type = $floor;
            $projectdetails->project_size = $request->pSize;
            $projectdetails->interested_in_rmc = $request->rmcinterest;
            $projectdetails->interested_in_loan = $request->loaninterest;
            $projectdetails->Kitchen_Cabinates = $request->dandwinterest;
            $projectdetails->interested_in_doorsandwindows = $request->upvc;
            $projectdetails->interested_in_premium = $request->premium;
            $projectdetails->construction_type = $type;
            $projectdetails->follow_up_date =$request->follow_up_date;
            $projectdetails->followup = $request->follow;
            $projectdetails->budget = $request->budget;
            $projectdetails->brilaultra = $request->brila;
            $projectdetails->contract = $request->contract;
            $projectdetails->with_cont = $request->qstn;
            $projectdetails->budgetType = $request->budgetType;
            $projectdetails->automation =  $request->automation;
            $projectdetails->plotsize = $size;
            $projectdetails->length =  $length;
            $projectdetails->breadth = $breadth;
            $projectdetails->res = $btype;
            $projectdetails->updated_by = Auth::user()->id;
            $projectdetails->call_attended_by = Auth::user()->id;
            $projectdetails->solar  = $request->solar;
            $projectdetails->Ininterior = $request->Ininterior;
            $projectdetails->refcustid = $request->cid;
            $projectdetails->silo = $request->silo;
             
            $projectdetails->save();
       
          
        OwnerDetails::where('project_id',$id)->update([
            'owner_name' => $request->oName,
            'owner_email' => $request->oEmail,
            'owner_contact_no' => $request->oContact,
            'whatsapp' => $request->owhatsapp
            
        ]);
        ContractorDetails::where('project_id',$id)->update([
            'contractor_name' => $request->cName,
            'contractor_email' => $request->cEmail,
            'contractor_contact_no' => $request->cContact,
             'whatsapp' => $request->cwhatsapp
        ]);
        ConsultantDetails::where('project_id',$id)->update([
            'consultant_name' => $request->coName,
            'consultant_email' => $request->coEmail,
            'consultant_contact_no' => $request->coContact,
             'whatsapp' => $request->ccwhatsapp
        ]);
        SiteEngineerDetails::where('project_id',$id)->update([
            'site_engineer_name' => $request->eName,
            'site_engineer_email' => $request->eEmail,
            'site_engineer_contact_no' => $request->eContact,
             'whatsapp' => $request->swhatsapp
        ]);
        ProcurementDetails::where('project_id',$id)->update([
            'procurement_name' => $request->pName,
            'procurement_email' => $request->pEmail,
            'procurement_contact_no' => $request->pContact,
             'whatsapp' => $request->pwhatsapp
        ]);

        Builder::where('project_id',$id)->update([
            'builder_name' => $request->bName,
            'builder_email' => $request->bEmail,
            'builder_contact_no' => $request->bPhone,
             'whatsapp' => $request->bwhatsapp
        ]);


        date_default_timezone_set("Asia/Kolkata");
        loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'lastUpdateTime' => date('H:i A')
        ]);
        $ward = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
        $first = loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->first();
        $assigned = subWard::where('id',$ward)->pluck('sub_ward_name')->first();
        if($assigned != null){
        if($first->firstUpdateTime != NULL){
            loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
                'firstUpdateTime' => date('H:i A'),
                'allocatedWard' => $assigned,
            ]);
        }
    }
        $check = mktime(12,00,00);
        $checktime = date('H:i:sA',$check);
        $morningcheck=loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->first();
        if(date('H:i:sA') <= $checktime){
              if($morningcheck != null){
            if($morningcheck->noOfProjectsUpdatedInMorning == NULL){
                loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
                    'noOfProjectsUpdatedInMorning' => 1
                ]);                
            }
        }else{
                $number=loginTime::where('user_id',Auth::user()->id)
                    ->where('logindate',date('Y-m-d'))
                    ->pluck('noOfProjectsUpdatedInMorning')
                    ->first();
                loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
                    'noOfProjectsUpdatedInMorning' => $number + 1
                ]); 
            }
        }
        if($morningcheck != null){
        if($morningcheck->totalProjectsUpdated == NULL){
             loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
                    'totalProjectsUpdated' => 1
                ]);
        }
    }else{
            $number2=loginTime::where('user_id',Auth::user()->id)
                    ->where('logindate',date('Y-m-d'))
                    ->pluck('totalProjectsUpdated')
                    ->first();
                loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
                    'totalProjectsUpdated' => $number2 + 1
                ]);
        }
        $room_types = $request->roomType[0]." (".$request->number[0].")";
        $count = count($request->roomType);
        for($i = 0;$i<$count;$i++){
            if($request->number[$i] != null)
            {
                $roomtype = new RoomType;
                $roomtype->floor_no = $request->floorNo[$i];
                $roomtype->room_type = $request->roomType[$i];
                $roomtype->no_of_rooms = $request->number[$i];
                $roomtype->project_id = $id;
                $roomtype->save();
            }
        }
        $project = ProjectDetails::where('project_id',$id)->pluck('sub_ward_id')->first();
        
        $activity = new ActivityLog;
        $uproject = ProjectDetails::where('project_id',$id)->pluck('updated_by')->first();
        $qproject = ProjectDetails::where('project_id',$id)->pluck('quality')->first();
        $fproject = ProjectDetails::where('project_id',$id)->pluck('followup')->first();
        $eproject = Requirement::where('project_id',$id)->pluck('generated_by')->first();
       
       $activity->updater = $uproject;
       $activity->quality = $qproject;
       $activity->followup = $fproject;
        if(count($eproject) != 0){
       $activity->enquiry = $eproject;
       }
        else{
       $activity->enquiry ="null";
        }
        $activity->time = date('Y-m-d H:i A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->activity = Auth::user()->name." has updated a project id: ".$id." at ".date('H:i A');
        $activity->project_id = $id;
        $activity->sub_ward_id = $project;
        $activity->typeofactivity = "Updated Project" ;
        $activity->save();
       
        if(Auth::user()->group_id != 6 && Auth::user()->group_id != 17){
        $qproject = ProjectDetails::where('project_id',$id)->pluck('quality')->first();
        $project = ProjectDetails::where('project_id',$id)->pluck('sub_ward_id')->first();
         $cat = AssignCategory::where('user_id',Auth::user()->id)->pluck('cat_id')->first();
        $projectupdate = new ProjectUpdate;
        $projectupdate->project_id = $id;
        $projectupdate->user_id = Auth::user()->id;
        $projectupdate->lat = " ";
        $projectupdate->lag = " ";
        $projectupdate->location = "";

        $projectupdate->sub_ward_id = $project;
        $projectupdate->quality=$qproject;
        $projectupdate->cat_id=$cat;
        $projectupdate->save();
            
        }
        
    

        return back()->with('Success','Updated Successfully');
    }
    // uses gtracing column to store morning meter reading
    public function addMorningMeter(Request $request)
    {
        // $imageName1 = time().'.'.request()->morningMeter->getClientOriginalExtension();
        // $request->morningMeter->move(public_path('meters'),$imageName1);
          
           $imageName1 = $request->file('morningMeter');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/meters/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');

             
              

        date_default_timezone_set("Asia/Kolkata");
        loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'morningMeter' => $imageFileName,
            'gtracing' => $request->morningMeterReading
        ]);
        return back();
    }
    // uses afternoonData to store morning data reading
    public function addMorningData(Request $request)
    {
        // $imageName1 = time().'.'.request()->morningData->getClientOriginalExtension();
        // $request->morningData->move(public_path('data'),$imageName1);
        

            $imageName1 = $request->file('morningData');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/data/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');



        date_default_timezone_set("Asia/Kolkata");
        loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'morningData' => $imageFileName,
            'afternoonData' => $request->morningDataReading
        ]);
        return back();
    }
    public function afternoonMeter(Request $request)
    {
        // $imageName1 = time().'.'.request()->afternoonmMeterImage->getClientOriginalExtension();
        // $request->afternoonmMeterImage->move(public_path('meters'),$imageName1);

             $imageName1 = $request->file('afternoonmMeterImage');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/meters/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');

         

        date_default_timezone_set("Asia/Kolkata");
        loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'afternoonMeter' => $imageFileName
        ]);
        return back();
    }
    public function afternoonData(Request $request)
    {
        // $imageName1 = time().'.'.request()->afternoonDataImage->getClientOriginalExtension();
        // $request->afternoonDataImage->move(public_path('data'),$imageName1);
        
            $imageName1 = $request->file('afternoonDataImage');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/data/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');




        date_default_timezone_set("Asia/Kolkata");
        loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'afternoonData' => $imageFileName
        ]);
        return back();
    }
    // uses afternoonMeter column to store evening meter reading
    public function eveningMeter(Request $request)
    {
        // $imageName1 = time().'.'.request()->eveningMeterImage->getClientOriginalExtension();
        // $request->eveningMeterImage->move(public_path('meters'),$imageName1);

           $imageName1 = $request->file('eveningMeterImage');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/meters/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');




        date_default_timezone_set("Asia/Kolkata");
        loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'eveningMeter' => $imageFileName,
            'afternoonMeter' => $request->eveningMeterReading
        ]);
        return back();
    }
    // uses afternoonRemarks column to store evening data reading
    public function eveningData(Request $request)
    {
        // $imageName1 = time().'.'.request()->eveningDataImage->getClientOriginalExtension();
        // $request->eveningDataImage->move(public_path('data'),$imageName1);
        
           $imageName1 = $request->file('eveningDataImage');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/data/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');

           


        date_default_timezone_set("Asia/Kolkata");
        // $lastrecord = projectdetails::where('listing_engineer_id',Auth::user()->id)
        // ->where('created_at','like',date('Y-m-d').'%')
        // ->orderBy('created_at','desc')->first();
        // $takeTime = $lastrecord->created_at->format('H:i A');
        loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'eveningData' => $imageFileName,
            'afternoonRemarks'=>$request->eveningDataReading
        ]);
        return back();
    }
    public function morningRemark($id, Request $request)
    {
        loginTime::where('id',$id)->update([
            'morningRemarks' => $request->mRemark
        ]);
       return back()->with('success','Report Submitted');
    }
    public function afternoonRemark($id, Request $request)
    {
        loginTime::where('id',$id)->update([
            'afternoonRemarks' => $request->aRemark
        ]);
       return back()->with('success','Report Submitted');
    }
    public function eveningRemark($id, Request $request)
    {
        
        
        loginTime::where('id',$id)->update([
            'total_kilometers' => $request->totalKm,
            'eveningRemarks' => $request->eRemark,
            'amount'=>$request->amount,
            'otherexpense'=>$request->other,
            'tluser'=>Auth::user()->id
        ]);
       return back()->with('success','Report Submitted');
    }
    public function addRequirement(Request $request)
    {
        $requirement = New Requirement;
        $requirement->project_id = $request->pId;
        $categoryname = Category::where('id',$request->mCategory)->pluck('category_name')->first();
        $requirement->main_category = $categoryname;
        $subcategoryname = SubCategory::where('id',$request->sCategory)->pluck('sub_cat_name')->first();
        $requirement->sub_category = $subcategoryname;
        $requirement->material_spec = $request->mSpec;
        if($request->rfImage1){
            // $imageName1 = time().'.'.request()->rfImage1->getClientOriginalExtension();
            // $request->rfImage1->move(public_path('requirements'),$imageName1);

                $imageName1 = $request->file('rfImage1');
             $imageFileName = time() . '.' . $imageName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/requirements/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName1), 'public');



            $requirement->referral_image1 = $imageFileName;
        }
        if($request->rfImage2){
            // $imageName2 = time().'.'.request()->rfImage2->getClientOriginalExtension();
            // $request->rfImage2->move(public_path('requirements'),$imageName2);

                $imageName2 = $request->file('rfImage2');
             $imageFileName1 = time() . '.' . $imageName2->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/requirements/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($imageName2), 'public');
            
            $requirement->referral_image2 = $imageFileName1;
        }
        $requirement->requirement_date = $request->rDate;
        $requirement->measurement_unit = $request->measure;
        $requirement->unit_price = $request->uPrice;
        $requirement->brand = $request->brand;
        $requirement->quantity = $request->quantity;
        $requirement->total = $request->total;
        $requirement->notes = $request->notes;
        if($request->truck){
            $requirement->truck = $request->truck;
        }
        $requirement->generated_by = Auth::user()->id;
        $requirement->save();
        
        $activity = new ActivityLog;
        $activity->time = date('Y-m-d H:i A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->activity = Auth::user()->name." has added a new requirement for project id: ".$request->pId." at ".date('H:i A');
        $uproject = ProjectDetails::where('project_id',$request->pId)->pluck('updated_by')->first();
        $qproject = ProjectDetails::where('project_id',$request->pId)->pluck('quality')->first();
        $fproject = ProjectDetails::where('project_id',$request->pId)->pluck('followup')->first();
        $eproject = Requirement::where('project_id',$request->pId)->pluck('generated_by')->first();
        $project = ProjectDetails::where('project_id',$request->pId)->pluck('sub_ward_id')->first();
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

        $activity->project_id = $request->pId;
        $activity->req_id = $requirement->id;
        $activity->typeofactivity = "Add Enquiry";
        $activity->save();

        return back();
    }
    public function placeOrder($id, Request $request)
    {
        $counting = count($request->requirement);
        if($counting == 0){
            return back()->with('Error','Please select requirements to place order');
        }else{
            for($i = 0;$i<$counting;$i++){
                Requirement::where('project_id',$id)->where('id',$request->requirement[$i])->update(['status' => "Order Placed"]);
            }
        }
        $orders = Requirement::where('project_id',$id)->where('status','Order Placed')->get();
        return view('confirm',['orders'=>$orders,'id'=>$id])->with('Success','Order has been placed successfully');
    }
    public function confirmOrder($id, Request $request)
    {
        $project = projectdetails::where('project_id',$id)->first();
        Requirement::where('project_id',$id)->where('status','Order Placed')->update(['status' => "Order Confirmed"]);
        $orders = Requirement::where('project_id',$id)->where('status','Order Confirmed')->get();
        return redirect($id.'/confirmedOrder')->with('Confirmed','Order has been confirmed')
        ->withInput();
    }
    public function postOrder(Request $request)
    {
        $secret_key = $request->secretkey;
        return view('payment.posting',['secretkey'=>$secret_key]);
    }
    public function getPaymentResponse()
    {
        return view('payment.response');
    }
    public function addTracing(Request $request,$id)
    {
        if($request->gTracing){

             $imageName2 = $request->file('gTracing');
             $imageFileName = time() . '.' . $imageName2->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/uploads/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName2), 'public');




            // $imageName2 = time().'.'.request()->gTracing->getClientOriginalExtension();
            // $request->gTracing->move(public_path('uploads'),$imageName2);
            loginTime::where('id',$id)->update([
                'gtracing' => $imageFileName
            ]);
        }else if($request->wTracingI){
               $imageName2 = $request->file('wTracingI');
             $imageFileName = time() . '.' . $imageName2->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/uploads/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName2), 'public');


            // $imageName2 = time().'.'.request()->wTracingI->getClientOriginalExtension();
            // $request->wTracingI->move(public_path('uploads'),$imageName2);
            loginTime::where('id',$id)->update([
                'ward_tracing_image' => $imageFileName
            ]);
        }else if($request->ewTracingI){

                $imageName2 = $request->file('ewTracingI');
             $imageFileName = time() . '.' . $imageName2->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/uploads/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName2), 'public');

            // $imageName2 = time().'.'.request()->ewTracingI->getClientOriginalExtension();
            // $request->ewTracingI->move(public_path('uploads'),$imageName2);
            loginTime::where('id',$id)->update([
                'evening_ward_tracing_image' => $imageFileName
            ]);
        }else if ($request->TracingIWtH) {
            // $imageName2 = time().'.'.request()->TracingIWtH->getClientOriginalExtension();
            // $request->TracingIWtH->move(public_path('uploads'),$imageName2);
            $imageName2 = $request->file('TracingIWtH');
             $imageFileName = time() . '.' . $imageName2->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/uploads/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName2), 'public');

            loginTime::where('id',$id)->update([
                'tracing_image_w_to_h' => $imageFileName
            ]);
        }
        return back();
    }
    public function addComments($id, Request $request)
    {
        if($request->googleKm){
            loginTime::where('id',$id)->update([ 'kmfromhtw' => $request->googleKm ]);
        }else if($request->kmfromts){
            loginTime::where('id',$id)->update([ 'km_from_software' => $request->kmfromts]);
        }else if($request->ekmfromts){
            loginTime::where('id',$id)->update(['evening_km_from_tracking' => $request->ekmfromts]);
        }else if($request->ekmwth){
            loginTime::where('id',$id)->update(['km_from_w_to_h' => $request->ekmwth]);
        }else if($request->loginTimeInWard){
            $check = mktime(12,00);
            $checktime = date('H:i',$check);
            if($request->loginTimeInWard < $checktime){
                $time = 'AM';
            }else{
                $time = 'PM';
            }
            loginTime::where('id',$id)->update([ 'login_time_in_ward' => $request->loginTimeInWard.' '.$time]);
        }else if($request->totalKilometers){
            loginTime::where('id',$id)->update([ 'total_kilometers' => $request->totalKilometers]);
        }
        return back();
    }
    public function giveGrade($userid, $reportid, Request $request)
    {
        loginTime::where('user_id',$userid)->where('id',$reportid)->update(['AmGrade'=>$request->grade,'AmRemarks'=>$request->remarks]);
        return back();
    }
    public function giveRemarks($userid, $reportid, Request $request)
    {
        loginTime::where('user_id',$userid)->where('id',$reportid)->update(['AmRemarks'=>$request->amremarks]);
        return back();
    }
    public function editGrade($userid, $reportid, Request $request){
        loginTime::where('user_id',$userid)->where('id',$reportid)->update(['AmRemarks'=>$request->remarks, 'AmGrade'=>$request->grade]);
        return back();
    }
    public function postRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users'
        ]);
        if ($validator->fails()) {
            return back()
                    ->with('Error','User already exists')
                    ->withErrors($validator)
                    ->withInput();
        }
        $user = new User;
        $user->employeeId = $request->email;
        $user->department_id = 100;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contactNo = $request->number;
        $user->category = $request->category;
        $user->password = bcrypt($request->password);
        $user->save();
        if($request->category == "Buyer"){
            View::share('password',$request->password);
            View::share('email',$request->email);
            View::share('name',$request->name);
            Mail::to($request->email)->send(new registration($user));
        }
       
        if($user->save()){  
                    return response()->json(['message'=>'Registered']);
                 }else{
                    return response()->json(['message'=>'Something went wrong']);
                 }


        return back()->with('Success','Thank you for your registration. Mama team will contact you shortly.');
    }
    public function confirmUser(Request $request)
    {
        User::where('id',$request->id)->update(['confirmation'=>1]);
        return back();
    }
    public function totalKm($id, Request $request){
        loginTime::where('user_id',$id)->where('id',$reportid)->update(['total_kilometers'=>$request->tkm]);
        return back();
    }
    public function postChangePassword(Request $request){
        $old = User::where('id',Auth::user()->id)->pluck('password')->first();
        if (Hash::check($request->oldPwd, $old)) {
            User::where('id',Auth::user()->id)->update(['password'=>bcrypt($request->newPwd)]);
            $activity = new ActivityLog;
            $activity->time = date('Y-m-d H:i A');
            $activity->employee_id = Auth::user()->employeeId;
            $activity->activity = Auth::user()->name." has changed the password.";
            $activity->save();
            return redirect('/home');
        }else{
            $activity = new ActivityLog;
            $activity->time = date('Y-m-d H:i A');
            $activity->employee_id = Auth::user()->employeeId;
            $activity->activity = Auth::user()->name." attempted to change password but failed.";
            $activity->save();
            return back()->with('Error','Invalid password');
        }
    }
    public function forgotPw(){
        return view('forgotpassword');
    }
    public function forgot(Request $request){
        $password = User::where('id',$request->id)->update(['password'=>bcrypt($request->newpassword)]);
        return back();
    }
    public function assignthisSlot($id, Request $request){
        $x = salesassignment::where('user_id',$id)->get();
        if(count($x) > 0)
        {
            $assignment = salesassignment::where('user_id',$id)->first();
            $ward = Ward::where('id',$assignment->assigned_date)->first();
            if($ward != null){
                $assignment->prev_assign = $ward->ward_name;
            }
           
            $assignment->assigned_date = $request->subward;
            $assignment->status = 'Not Completed';
            $assignment->save();
            // salesassignment::where('user_id',$id)->update(['status'=>'Not Completed','assigned_date' => $request->date]);
            $activity = new ActivityLog;
            $activity->time = date('Y-m-d H:i A');
            $activity->employee_id = Auth::user()->employeeId;
            $activity->activity = Auth::user()->name." has assigned ".$id." to a daily slot for the date ".$request->date;
            $activity->save();
            return back();
        }
        else
        {
            $user = User::where('id',$request->id)->first();
            $assignment = new salesassignment;
            $assignment->user_id = $id;
            $assignment->assigned_date = $request->subward;
            $assignment->status = 'Not Completed';
            $assignment->save();
            $activity = new ActivityLog;
            $activity->time = date('Y-m-d H:i A');
            $activity->employee_id = Auth::user()->employeeId;
            $activity->activity = Auth::user()->name." has assigned ".$id." to a daily slot for the date ".$request->date;
            $activity->save();
            return back()->with('Success','Daily Slot assigned to '.$user->name);
        }
    }
    public function completedAssignment( Request $request)
    {
        $id = $request->id;
        $sub = WardAssignment::where('user_id',$id)->first();
        WardAssignment::where('user_id',$id)->update(['status' => 'Completed','prev_subward_id'=> $sub->subward_id]);
        $activity = new ActivityLog;
        $activity->time = date('Y-m-d H:i A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->activity = Auth::user()->name." has marked ".$id."'s assignment as completed.";
        $activity->save();
        return back();
    }
    public function editMorninRemarks($id, Request $request){
        loginTime::where('id',$id)->update(['morningRemarks'=>$request->remark]);
         return back()->with('success','Report Submitted');
    }
    public function editEveningRemarks($id, Request $request){
        loginTime::where('id',$id)->update(['eveningRemarks'=>$request->remark]);
        return back()->with('success','Report Submitted');
    }
    public function salesUpdateProject($id, Request $request){
        $point = 0;
        $project = ProjectDetails::where('project_id',$id)->first();
        if(count($request->status) != 0){
            $statuses = implode(", ",$request->status);
        }else{
            $statuses = $project->project_status;
        }
        if(count($request->constructionType) != 0){
            $type = implode(", ",$request->constructionType);
        }else{
            $type = $project->construction_type;
        }
        $point = $project->construction_type != $type ? $point+5 : $point+0;
        $point = $project->project_status != $statuses ? $point+5 : $point+0;
        $point = $project->road_width != $request->rWidth ? $point+4 : $point+0;
        $point = $project->quality != $request->quality ? $point+20 : $point+0;
        $point = $project->contract != $request->contract ? $point+6 : $point+0;
        $points = new Point;
        $points->user_id = Auth::user()->id;
        $points->point = $point;
        $points->type = "Add";
        $points->reason = "Updating project";
        $points->save();


        projectDetails::where('project_id',$id)->update([
            'project_status'=>$statuses,
            'remarks'=>$request->materials,
            'with_cont'=>$request->qstn,
            'reqDate' =>$request->reqDate,
            'followup'=>$request->followup,
            'follow_up_date' =>$request->follow_up_date,
            'construction_type'=>$type,
            'road_width'=>$request->rWidth,
            'quality'=>$request->quality,
            'contract'=>$request->contract,
            'note'=>$request->note,
            'automation'=>$request->automation,
            'follow_up_by'=>Auth::user()->id,
            'call_attended_by'=>Auth::user()->id
            ]);
 

        siteAddress::where('project_id',$id)->update([
            'address'=>$request->address
            ]);
        $activity = new ActivityLog;
        $activity->time = date('Y-m-d H:i A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->project_id = $id;
         $uproject = ProjectDetails::where('project_id',$id)->pluck('updated_by')->first();
        $qproject = ProjectDetails::where('project_id',$id)->pluck('quality')->first();
        $fproject = ProjectDetails::where('project_id',$id)->pluck('followup')->first();
        $eproject = Requirement::where('project_id',$id)->pluck('generated_by')->first();
       
       $activity->updater = $uproject;
       $activity->quality = $qproject;
       $activity->followup = $fproject;
        if(count($eproject) != 0){
        
       $activity->enquiry = $eproject;
       }
        else{
       $activity->enquiry ="null";

        }
        $project = ProjectDetails::where('project_id',$id)->pluck('sub_ward_id')->first();
        $activity->sub_ward_id = $project;
        $activity->typeofactivity = "Project Updated";
        $activity->activity = Auth::user()->name." has updated a project id: ".$id." at ".date('H:i A')." with data - Status: ".$statuses.", Remarks: ".$request->materials.", Question: ".$request->qstn.", Followup: ".$request->follow.", Quality: ".$request->quality.", With contractor: ".$request->contract.", Note: ".$request->note;
        $activity->save();
        return back();
    }
    public function gradetoEmp(Request $request){
        // attendance::where('empId',$request->userId)->where('date',$request->date)->update(['grade'=>$request->grade,'remarks'=>$request->remark,'am_remarks'=>$request->amremark]);
        // dd($request->userId);

        $empid = User::where('employeeId',$request->userId)->pluck('id')->first();
        FieldLogin::where('user_id',$empid)->where('logindate',$request->date)->update(['grade'=>$request->grade,'remarks'=>$request->remark,'am_remarks'=>$request->amremark]);

        return back();
    }
    public function saveEdit(Request $request){
        $check = EmployeeDetails::where('employee_id',$request->userid)->first();
        User::where('employeeId',$request->userid)->update(['contactNo'=>$request->contact]);
        if(count($check)==0){
            $empDetails = new EmployeeDetails;
            $empDetails->employee_id = $request->userid;
            $empDetails->date_of_joining = $request->doj;
            $empDetails->adhar_no = $request->aadhar;
            if($request->aadharImg != NULL){
                     
             $aadhar = $request->file('aadharImg');
             $imageFileName =  "aadhar".time() . '.' . $aadhar->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($aadhar), 'public');
       
                 

                // $aad har = "aadhar".time().'.'.request()->aadharImg->getClientOriginalExtension();
                // $requ est->aadharImg->move(public_path('employeeImages'),$aadhar);
                $empDetails->aadhar_image = $imageFileName;
            }
            $empDetails->dob =$request->dob;
            $empDetails->blood_group = $request->bloodGroup;
            $empDetails->fathers_name = $request->fatherName;
            $empDetails->mothers_name = $request->motherName;
            $empDetails->spouse_name = $request->spouseName;
            $empDetails->alt_phone = $request->altPh;
            $empDetails->office_phone = $request->office;
            $empDetails->official_email = $request->official_email;
            $empDetails->mh_email = $request->mh_email;
            $empDetails->personal_email = $request->personal_email;
            $empDetails->permanent_address = $request->perAdd;
            if($request->permanenAddressProof != NULL){

             $address = $request->file('permanenAddressProof');
             $imageFileName =  "address1".time() . '.' . $address->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($address), 'public');

                // $address = "address1".time().'.'.request()->permanenAddressProof->getClientOriginalExtension();
                // $request->permanenAddressProof->move(public_path('employeeImages'),$address);
                $empDetails->permanent_address_proof = $imageFileName;
            }
            $empDetails->temporary_address = $request->preAdd;
            if($request->presentAddressProof != NULL){

             $present = $request->file('presentAddressProof');
             $imageFileName =  "address2".time() . '.' . $present->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($present), 'public');
               
                // $present = "address2".time().'.'.request()->presentAddressProof->getClientOriginalExtension();
                // $request->presentAddressProof->move(public_path('employeeImages'),$present);
                $empDetails->temporary_address_proof = $imageFileName;
            }
            $empDetails->emergency_contact_name = $request->emergencyName;
            $empDetails->emergency_contact_no = $request->emergencyContact;
            $empDetails->emergency_contact2_name = $request->emergencyName2;
            $empDetails->emergency_contact2_no = $request->emergencyContact2;
            if($request->cv != NULL){

             $cvv = $request->file('cv');
             $imageFileName =  "cv".time() . '.' . $cvv->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($cvv), 'public');

                // $cvv = "cv".time().'.'.request()->cv->getClientOriginalExtension();
                // $request->cv->move(public_path('employeeImages'),$cvv);
                $empDetails->curriculum_vite = $imageFileName;
            }
            if($request->cfa != NULL){

            $cfa = $request->file('cfa');
             $imageFileName1 =  "audio".time() . '.' . $cfa->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeAudios/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($cfa), 'public');


                // $cfa = time().'.'.request()->cfa->getClientOriginalExtension();
                // $request->cfa->move(public_path('employeeAudios'),$cfa);
                $empDetails->confirmation_call = $imageFileName1;
            }
            if($request->cfa2 != NULL){

             $cfa2 = $request->file('cfa2');
            
             $imageFileName =  "cv".time() . '.' . $cfa2->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeAudios/' . $imageFileName;
             $s3->put($filePath, file_get_contents($cfa2), 'public');




                // $cfa2 = $request->emergencyName2.time().'.'.request()->cfa2->getClientOriginalExtension();
                // $request->cfa2->move(public_path('employeeAudios'),$cfa2);
                $empDetails->confirmation_call2 = $imageFileName;
            }
            $empDetails->save();
        }else{
            $check->employee_id = $request->userid;
            $check->date_of_joining = $request->doj;
            $check->adhar_no = $request->aadhar;
            if($request->aadharImg != NULL){

             $aadhar = $request->file('aadharImg');
             $imageFileName =  "aadhar".time() . '.' . $aadhar->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($aadhar), 'public');


                // $aadhar = "aadhar".time().'.'.request()->aadharImg->getClientOriginalExtension();
                // $request->aadharImg->move(public_path('employeeImages'),$aadhar);
                $check->aadhar_image = $imageFileName;
            }
            $check->dob =$request->dob;
            $check->blood_group = $request->bloodGroup;
            $check->fathers_name = $request->fatherName;
            $check->mothers_name = $request->motherName;
            $check->spouse_name = $request->spouseName;
            $check->alt_phone = $request->altPh;
            $check->office_phone = $request->office;
            $check->official_email = $request->official_email;
            $check->mh_email = $request->mh_email;
            $check->personal_email = $request->personal_email;
            $check->permanent_address = $request->perAdd;
            if($request->permanenAddressProof != NULL){

             $address = $request->file('permanenAddressProof');
             $imageFileName =  "address1".time() . '.' . $address->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($address), 'public');


                // $address = "address1".time().'.'.request()->permanenAddressProof->getClientOriginalExtension();
                // $request->permanenAddressProof->move(public_path('employeeImages'),$address);
                $check->permanent_address_proof = $imageFileName;
            }
            $check->temporary_address = $request->preAdd;
            if($request->presentAddressProof != NULL){
            
             $present = $request->file('presentAddressProof');
             $imageFileName =  "address2".time() . '.' . $present->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($present), 'public');


                // $present = "address2".time().'.'.request()->presentAddressProof->getClientOriginalExtension();
                // $request->presentAddressProof->move(public_path('employeeImages'),$present);
                $check->temporary_address_proof = $imageFileName;
            }
            $check->emergency_contact_name = $request->emergencyName;
            $check->emergency_contact_no = $request->emergencyContact;
            $check->emergency_contact2_name = $request->emergencyName2;
            $check->emergency_contact2_no = $request->emergencyContact2;
            if($request->cv != NULL){

             $cvv = $request->file('cv');
             $imageFileName =  "cv".time() . '.' . $cvv->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($cvv), 'public');


                // $cvv = "cv".time().'.'.request()->cv->getClientOriginalExtension();
                // $request->cv->move(public_path('employeeImages'),$cvv);
                $check->curriculum_vite = $imageFileName;
            }
            if($request->cfa != NULL){

             $cfa = $request->file('cfa');
             $imageFileName =  time() . '.' . $cfa->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeAudios/' . $imageFileName;
             $s3->put($filePath, file_get_contents($cfa), 'public');


                // $cfa = time().'.'.request()->cfa->getClientOriginalExtension();
                // $request->cfa->move(public_path('employeeAudios'),$cfa);
                $check->confirmation_call = $imageFileName;
            }
            if($request->cfa2 != NULL){
                // $cfa2 = $request->emergencyName2.time().'.'.request()->cfa2->getClientOriginalExtension();
                // $request->cfa2->move(public_path('employeeAudios'),$cfa2);

             $cfa2 = $request->file('cfa2');
             $imageFileName1 =  "emergencyName2".time() . '.' . $cfa2->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeAudios/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($cfa2), 'public');


                $check->confirmation_call2 = $imageFileName1;
            }
            $check->save();
        }
        $activity = new ActivityLog;
        $activity->time = date('Y-m-d H:i A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->activity = Auth::user()->name." has updated ".$request->userid." details.";
        $activity->save();
        return back();
    }
    public function saveBankDetails(Request $request){
        $check = BankDetails::where('employeeId',$request->userid)->first();
        if(count($check)==0){
            $bankDetails = new BankDetails;
            $bankDetails->employeeId = $request->userid;
            $bankDetails->accountHolderName = $request->acHolder;
            $bankDetails->bank_name = $request->bankName;
            $bankDetails->accountNo = $request->acNo;
            $bankDetails->ifsc = $request->ifsc;
            $bankDetails->branchName = $request->branchName;
            if($request->passbook != NULL){
             $cvv = $request->file('passbook');
             $imageFileName =  "pas".time() . '.' . $cvv->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($cvv), 'public');

                // $cvv = 'pas'.time().'.'.request()->passbook->getClientOriginalExtension();
                // $request->passbook->move(public_path('employeeImages'),$cvv);
                $bankDetails->passbook = $imageFileName;
            }
            $bankDetails->pan_card_no = $request->panCard;
            if($request->panCardImage != NULL){
                // $cvv = 'pan'.time().'.'.request()->panCardImage->getClientOriginalExtension();
                // $request->panCardImage->move(public_path('employeeImages'),$cvv);
                   
            $cvv = $request->file('panCardImage');
             $imageFileName =  "pan".time() . '.' . $cvv->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($cvv), 'public');


                $bankDetails->pan_card_image = $imageFileName;
            }
            $bankDetails->save();
        }else{
            $check->accountHolderName = $request->acHolder;
            $check->bank_name = $request->bankName;
            $check->accountNo = $request->acNo;
            $check->ifsc = $request->ifsc;
            $check->branchName = $request->branchName;
            if($request->passbook != NULL){

             $cvv = $request->file('passbook');
             $imageFileName =  "pas".time() . '.' . $cvv->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($cvv), 'public');

                // $cvv = 'pas'.time().'.'.request()->passbook->getClientOriginalExtension();
                // $request->passbook->move(public_path('employeeImages'),$cvv);
                $check->passbook = $imageFileName;
            }
            $check->pan_card_no = $request->panCard;
            if($request->panCardImage != NULL){

             $cvv = $request->file('panCardImage');
             $imageFileName =  "pan".time() . '.' . $cvv->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($cvv), 'public');

                // $cvv = 'pan'.time().'.'.request()->panCardImage->getClientOriginalExtension();
                // $request->panCardImage->move(public_path('employeeImages'),$cvv);
                $check->pan_card_image = $imageFileName;
            }
            $check->save();
        }
        return back();
    }
    public function saveAssetInfo(Request $request){
        $count = count($request->type);

        // $empimage = time().'.'.request()->emp_image->getClientOriginalExtension();
        // $request->emp_image->move(public_path('empsignature'),$empimage);

           $empimage = $request->file('emp_image');
             $imageFileName1 =  time() . '.' . $empimage->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/empsignature/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($empimage), 'public');



        // $mimage = time().'.'.request()->manager_image->getClientOriginalExtension();
        // $request->manager_image->move(public_path('managersignature'),$mimage);


             $mimage = $request->file('manager_image');
             $imageFileName2 =  time() . '.' . $mimage->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/managersignature/' . $imageFileName2;
             $s3->put($filePath, file_get_contents($mimage), 'public');


        for($i = 0; $i<$count;$i++){
            $assetInfo = new AssetInfo;

            $assetInfo->employeeId = $request->userId;
            $type = Asset::where('id',$request->type[$i])->pluck('type')->first();
            $assetInfo->asset_type = $type;
            $name = MamahomeAsset::where('id',$request->mname)->pluck('name')->first();
            $assetInfo->name = $name;
            $assetInfo->emp_signature = $imageFileName1;
            $assetInfo->manager_signature = $imageFileName2;
            $snum = MamahomeAsset::where('id',$request->number)->pluck('sl_no')->first();
            $assetInfo->serial_no = $snum;
            $assetInfo->assign_date =$request->tdate;
            $assetInfo->remark = $request->remark[$i];
            $assetInfo->description = $request->details;
            $assetInfo->mh_id = $request->mname;
            $assetInfo->save();
        }
            $mhasset = MamahomeAsset::where('id',$request->mname)->first();
            $mhasset->status = "Assigned";
            $mhasset->save();
      
        return back();
    }
    public function uploadCertificates(Request $request){
        $count = count($request->type);
        for($i=0;$i<$count;$i++){
            $certificates = new Certificate;
            $certificates->employeeId = $request->userId;
            $certificates->type = $request->type[$i];
            if($request->certificateFile[$i] != NULL){

             $cvv = $request->certificateFile[$i];
            
             $imageFileName = $request->userId.$i. time() . '.' . $cvv->getClientOriginalExtension();

            
             $s3 = \Storage::disk('azure');
             $filePath = '/employeeImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($cvv), 'public');

                // $cvv = $request->userId.$i.time().'.'.request()->certificateFile[$i]->getClientOriginalExtension();
                // $request->certificateFile[$i]->move(public_path('employeeImages'),$cvv);
                $certificates->location = $imageFileName;
            }
            $certificates->save();
        }
        return back();
    }
    public function insertCat(Request $request){
        CategoryPrice::where('category_sub_id',$request->subcategory)
            ->update([
                'price'=>$request->price,
                'gst'=>$request->gst,
                'transportation_cost'=>$request->tc,
                'royalty'=>$request->royalty
            ]);
        return back();
    }
    public function addManufacturer(Request $request){
        $cat = Category::where('id',$request->category)->pluck('category_name')->first();
        if($request->pan != null){

             $pan = $request->file('pan');
             $imageFileName = $request->companyName. time() . '.' . $pan->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/pan/' . $imageFileName;
             $s3->put($filePath, file_get_contents($pan), 'public');



              
        }
        else{
            $imageFileName = null;
        }

  
           $val =ManufacturerDetail::max('final');
                $i = intval($val);
                $z = $i+1;

                $year = date('Y');
                $country_code = Country::pluck('country_code')->first();
                $zone = Zone::pluck('zone_number')->first();
                $invoiceno = "MH_".$country_code."_".$zone."_"."S".$z;
                                

        $manufacturer = new ManufacturerDetail;
        $manufacturer->vendortype = $request->vendortype;
        $manufacturer->company_name = $request->companyName;
        $manufacturer->state = $request->state;
        $manufacturer->category = $cat;
        $manufacturer->cin = $request->cin;
        $manufacturer->gst = $request->gst;
        $manufacturer->registered_office = $request->regOffice;
        $manufacturer->pan = $imageFileName;
        $manufacturer->production_capacity = $request->productionCapacity;
        $manufacturer->factory_location = $request->factoryLocation;
        $manufacturer->ware_house_location = $request->warehouselocation;
        $manufacturer->md = $request->md;
        $manufacturer->ceo = $request->ceo;
        $manufacturer->sales_contact = $request->salesContact;
        $manufacturer->finance_contact = $request->financeContact;
        $manufacturer->quality_department = $request->qualityDept;
        $manufacturer->supplier_id = $invoiceno;
        $manufacturer->final = $z;
        $manufacturer->approve =1;

        $manufacturer->save();

        
                     
                     






        return back()->with('Success','Manufacturer details added successfully');
    }

public function addupdatemanufacturer(request $request){
   
     $cat = Category::where('id',$request->category)->pluck('category_name')->first();
        if($request->pan != null){
             $pan = $request->file('pan');
             $imageFileName = $request->companyName. time() . '.' . $pan->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/pan/' . $imageFileName;
             $s3->put($filePath, file_get_contents($pan), 'public');



              
        }
        else{
            $imageFileName = null;
        }

        
          


        $manufacturer = ManufacturerDetail::where('manufacturer_id',$request->id)->first();
        $manufacturer->vendortype = $request->vendortype;
        $manufacturer->company_name = $request->companyName;
        $manufacturer->state = $request->state;
        $manufacturer->category = $cat;
        $manufacturer->cin = $request->cin;
        $manufacturer->gst = $request->gst;
        $manufacturer->registered_office = $request->regOffice;
        
        $manufacturer->production_capacity = $request->productionCapacity;
        $manufacturer->factory_location = $request->factoryLocation;
        $manufacturer->ware_house_location = $request->warehouselocation;
        $manufacturer->md = $request->md;
        $manufacturer->ceo = $request->ceo;
        $manufacturer->sales_contact = $request->salesContactNo;
        $manufacturer->quality_department = $request->qualityDept;
        $manufacturer->save();
         if($request->pan != null){
               $manufacturer->pan = $imageFileName;
               $manufacturer->save();
         }

        return back()->with('Success','Manufacturer details Updated successfully');

}

    public function editsubwardimage(Request $request){
        $subwardName = SubWard::where('id',$request->subwardId)->first();

             $subward = $request->file('subwardimage');
             $imageFileName = $subwardName->sub_ward_name.time() . '.' . $subward->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/subWardImages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($subward), 'public');




        // $subward = $subwardName->sub_ward_name.time().'.'.request()->subwardimage->getClientOriginalExtension();
        // $request->subwardimage->move(public_path('subWardImages'),$subward);
        $subwardName->sub_ward_image = $imageFileName;
        $subwardName->save();
        return back();
    }
    public function updateLogoutTime(Request $request)
    {
        loginTime::where('id',$request->id)->update(['logoutTime'=>$request->logoutTime]);
        return back()->with('Success','Logout time updated');
    }
    public function markLeave(Request $request){
        $check = loginTime::where('user_id',$request->id)->where('logindate',$request->date)->first();
        if($check == null){
            $login = new loginTime;
            $login->user_id =$request->id;
            $login->loginTime = "N/a";
            $login->logindate = $request->date;
            $login->login_time_in_ward = $request->leave;
            $login->save();
        }else{
            loginTime::where('user_id',$request->id)->where('logindate',$request->date)->update(['login_time_in_ward'=>$request->leave]);
        }
        return back();
    }
    public function markProject(Request $request)
    {
        ProjectDetails::where('project_id',$request->id)->update(['quality'=>$request->quality]);
        $points = new Point;
        $points->user_id = Auth::user()->id;
        $points->point = 20;
        $points->type = "Add";
        $points->reason = "Marking project Id: ".$id." as ".$request->quality;
        $points->save();
        $activity = new ActivityLog;
        $activity->time = date('Y-m-d H:i A');
        $activity->project_id = $request->id;
         $uproject = ProjectDetails::where('project_id',$request->id)->pluck('updated_by')->first();
        $qproject = ProjectDetails::where('project_id',$request->id)->pluck('quality')->first();
        $fproject = ProjectDetails::where('project_id',$request->id)->pluck('followup')->first();
        $eproject = Requirement::where('project_id',$request->id)->pluck('generated_by')->first();
       
       $activity->updater = $uproject;
       $activity->quality = $qproject;
       $activity->followup = $fproject;
       if(count($eproject) != 0){
        
       $activity->enquiry = $eproject;
       }
        else{
       $activity->enquiry ="null";

        }
        $project=ProjectDetails::where('project_id',$request->id)->pluck('sub_ward_id')->first();
        $activity->sub_ward_id = $project;
        $activity->employee_id = Auth::user()->employeeId;
        $activity->activity = Auth::user()->name." has marked project ".$request->id." as ".$request->quality;
        $activity->save();
        return back();
    }
    public function editEnquiry(Request $request)
    {
           
             // if($request->paytype == "Cashagainestdelivery"){
             //    dd($request->paytype);
             // }
            

            if($request->note != null){
          Requirement::where('id',$request->id)->update(['notes'=>$request->note]);
          $requirement = Requirement::where('id',$request->id)->first();
           
        }elseif($request->status != null){
            
            Requirement::where('id',$request->eid)->update(['status'=>$request->status,'converted_by'=>Auth::user()->id,'spname'=>$request->sname,'spstate'=>$request->state,'spamount'=>$request->spamount,'price'=>$request->mamaprice,'measurement_unit'=>$request->Unit,'total_quantity'=>$request->quantity,'paytype'=>$request->paytype]);
            $requirement = Requirement::where('id',$request->eid)->first();



            if($requirement->status == "Enquiry Confirmed"){



                 
                $project1 = Manufacturer::where('id',$requirement->manu_id)->first();
                
                $project = ProjectDetails::where('project_id',$requirement->project_id)->first();
                
                if(!$request->manu_id){
                $subward = SubWard::where('id',$project->sub_ward_id)->first();
                }else{
                $subward = SubWard::where('id',$project1->sub_ward_id)->first();        
              
                }

                $ward = Ward::where('id',$subward->ward_id)->first();
                $zone = Zone::where('id',$ward->zone_id)->first();
                $country = Country::where('id',$ward->country_id)->first();
                $year = date('Y');
                $country_initial = "O";
                $all =Order::withTrashed()->count();
                $count = $all + 1;
                $number = sprintf("%03d", $count);
                $orderNo = "MH_".$country->country_code."_".$zone->zone_number."_".$year."_".$country_initial.$number;
		
                $check = Order::where('req_id',$request->eid)->first();
              
                if($check == null){
                $order = new Order;
                $order->id = $orderNo;
                $order->req_id = $request->eid;
                $order->project_id = $requirement->project_id;
                $order->main_category = $requirement->main_category;
                $order->brand = $requirement->brand;
                $order->sub_category = $requirement->sub_category;
                $order->material_spec = $requirement->material_spec;
                $order->referral_image1 = $requirement->referral_image1;
                $order->referral_image2 = $requirement->referral_image2;
                $order->requirement_date = $requirement->requirement_date;
                $order->measurement_unit = $requirement->measurement_unit;
                $order->unit_price = $requirement->unit_price;
                $order->quantity = $requirement->quantity;
                $order->total = $requirement->total;
                $order->notes = $requirement->notes;
                $order->status = $requirement->status;
                $order->dispatch_status = $requirement->dispatch_status;
                $order->generated_by  = $requirement->generated_by;
                $order->manu_id =$requirement->manu_id;
                $order->spname = $requirement->spname;
                $order->spstate = $requirement->spstate;
                $order->spamount = $requirement->spamount;
                $order->cid = $requirement->cid;
                $order->paytype = $request->paytype;

                $order->save();
           
                }else{
                $check->id = $orderNo;
                $check->req_id = $request->eid;
                $check->project_id = $requirement->project_id;
                $check->main_category = $requirement->main_category;
                $check->brand = $requirement->brand;
                $check->sub_category = $requirement->sub_category;
                $check->material_spec = $requirement->material_spec;
                $check->referral_image1 = $requirement->referral_image1;
                $check->referral_image2 = $requirement->referral_image2;
                $check->requirement_date = $requirement->requirement_date;
                $check->measurement_unit = $requirement->measurement_unit;
                $check->unit_price = $requirement->unit_price;
                $check->quantity = $requirement->quantity;
                $check->total = $requirement->total;
                $check->notes = $requirement->notes;
                $check->status = $requirement->status;
                $check->dispatch_status = $requirement->dispatch_status;
                $check->generated_by  = $requirement->generated_by;
                $check->manu_id =$requirement->manu_id;
                 $check->spname = $requirement->spname;
                $check->spstate = $requirement->spstate;
                $check->spamount = $requirement->spamount;
                $check->cid = $requirement->cid;
                $check->paytype = $request->paytype;
                
                $check->save(); 
                }

                
            }
        }

     
        $id =$orderNo;
        $x = Order::where('id',$id)->first();
        
        $x->status = 'Order Confirmed';
        $x->order_placed="yes";
        $x->confirmed_by =Auth::user()->id;
        $x->save();

        if($requirement->project_id != null){
        $num = procurementDetails::where('project_id',$requirement->project_id)->pluck('procurement_contact_no')->first();
        $contractor = ContractorDetails::where('project_id',$request->selectprojects)->pluck('contractor_contact_no')->first();
            $site = SiteEngineerDetails::where('project_id',$request->selectprojects)->pluck('site_engineer_contact_no')->first();
            $owner = OwnerDetails::where('project_id',$request->selectprojects)->pluck('owner_contact_no')->first();
            $consult = ConsultantDetails::where('project_id',$request->selectprojects)->pluck('consultant_contact_no')->first();
       }
       else{
        $num = Mprocurement_Details::where('manu_id',$requirement->manu_id)->pluck('contact')->first();
        $owner = Mowner_Deatils::where('id',$requirement->manu_id)->pluck('contact')->first();
        $contractor = null;
        $site = null;
        $owner = null;
        $consult = null;
       }
        $customer = CustomerGst::where('customer_phonenumber',$num)->get()->first();
        $checkit = count($customer);
        if($checkit == 1){
            $country_code = Country::pluck('country_code')->first();
            $zone = Zone::pluck('zone_number')->first();
            $cus_id = "MH_".$country_code."_".$zone."_C".$customer->id;
            $cid = CustomerGst::where('customer_phonenumber',$num)->update(['customer_id'=>$cus_id]);
        }
        else{

            $cust = new CustomerGst;
            $cust->customer_phonenumber = $num;
            $cust->contractor_no = $contractor;
            $cust->site_engineer_no = $site;
            $cust->owner_no = $owner;
            $cust->consultant_no = $consult;
            $cust->save();

            $country_code = Country::pluck('country_code')->first();
            $zone = Zone::pluck('zone_number')->first();
            $cus_id = "MH_".$country_code."_".$zone."_C".$cust->id;
            $cid = CustomerGst::where('customer_phonenumber',$num)->update(['customer_id'=>$cus_id]);
        }
        $activity = new ActivityLog;
        $activity->time = date('Y-m-d H:i A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->req_id = $request->eid;
        $activity->project_id = $requirement->project_id;
        $project = ProjectDetails::where('project_id',$requirement->project_id)->pluck('sub_ward_id')->first();
        $activity->sub_ward_id = $project;
         $uproject = ProjectDetails::where('project_id',$requirement->project_id)->pluck('updated_by')->first();
        $qproject = ProjectDetails::where('project_id',$requirement->project_id)->pluck('quality')->first();
        $fproject = ProjectDetails::where('project_id',$requirement->project_id)->pluck('followup')->first();
        $eproject = Requirement::where('project_id',$requirement->project_id)->pluck('generated_by')->first();
       
       $activity->updater = $uproject;
       $activity->quality = $qproject;
       $activity->followup = $fproject;
       if(count($eproject) != 0){
        $activity->enquiry = $eproject;
       }
        else{
           $activity->enquiry ="null";
          }
         $activity->typeofactivity = "Enquiry Updated";
        $activity->activity = Auth::user()->name." has updated requirement id: ".$request->eid." as ".$request->note.$request->status;
        $activity->save();
        return back();
        }
    
    public function editManualEnquiry(Request $request)
    {
        RecordData::where('id',$request->id)->update(['rec_remarks'=>$request->note]);
        $activity = new ActivityLog;
        $activity->time = date('Y-m-d H:i A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->activity = Auth::user()->name." has updated enquiry id: ".$request->id." as ".$request->note;
        $activity->save();
        return back();
    }
    public function deleteProject(request $request)
    {
        
        ProjectDetails::where('project_id',$request->projectId)->update(['blockremark'=>$request->blockremark,'user_id'=>Auth::user()->id]);
        
        
        $project = ProjectDetails::find($request->projectId)->delete();
      
        return back();
    }
    public function editinputdata(Request $request)
    { 

         // dd($request->all());
        
        if($request->name){

              $billaddress = $request->shipaddress;
         }
         else{
            
           $billaddress = $request->billaddress;
         }

        $var1 =Requirement::where('id',$request->reqId)->pluck('total_quantity')->first();
        $var2 =Requirement::where('id',$request->reqId)->pluck('price')->first();
        $var3 =Requirement::where('id',$request->reqId)->pluck('billadress')->first();
        $var4 =Requirement::where('id',$request->reqId)->pluck('ship')->first();

        if($var1 == $request->totalquantity){
            $quan = $request->totalquantity;//nt changed
        }
        else{
            $quan ="none";//changed
        }
        if($var2 == $request->price){
            $price = $request->price;
        }
        else{
            $price = "none";
        }
        if($var3 == $billaddress){
            $bill = $billaddress;//ntchanged
        }
        else{
            $bill = "none";
        }
        if($var4 == $request->shipaddress){
            $ship = $request->shipaddress;//ntchanged
        }
        else{
            $ship = "none";
        }
        if($request->subcatsteel){
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
                
            // dd($request->subcatsteel));
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
        }else if(count($request->withgst)  != 0 && count($request->withgst1)  == 0 && !$request->subcatsteel){
           
               $categoryNames = "FLOORINGS";
               $brandnames =  brand::where('id',$request->cat25[0])->pluck('brand')->first();
               $subcategories = "";
               $qnty = array_sum($request->quan);
               $price =$request->price[0];
               $totalquantity=array_sum($request->quan);
               $steel_price=0;
               $steel_quantity=0;

            
           }else if(count($request->withgst1)  != 0 && count($request->withgst)  == 0 && !$request->subcatsteel){
             
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
        // for fetching sub categories
        $validator = Validator::make($request->all(), [
        'subcat' => 'required'
        ]);
        if ($validator->fails()) {
            return back()
            ->with('Error','Select Category Before Submit')
            ->withErrors($validator)
            ->withInput();
        }
        $sub_cat_name = SubCategory::whereIn('id',$request->subcat)->pluck('id')->toArray();
        $subcategories = implode(", ", $sub_cat_name);
            // fetching brands
        $brand_ids = SubCategory::whereIn('id',$request->subcat)->pluck('brand_id')->toArray();
        $brand = brand::whereIn('id',$brand_ids)->pluck('brand')->toArray();
        $brandnames = implode(", ", $brand);
       
        $sub = SubCategory::where('id',$request->subcat)->pluck('sub_cat_name')->first();
         $qnty = $sub.":".$request->totalquantity;//new code

        $category_ids = SubCategory::whereIn('id',$request->subcat)->pluck('category_id')->toArray();
        $category= Category::whereIn('id',$category_ids)->pluck('category_name')->toArray();
        $categoryNames = implode(", ", $category);
        $totalquantity = $request->totalquantity;
        $price = $request->price;
        $steel_price = null;
        $steel_quantity = null;
        }
         

         if($request->framework){
          $lng = implode(",",$request->framework);
       }else{
        $lng = "";
       }

        $x = Requirement::where('id',$request->reqId)->update([
            'main_category' => $categoryNames,
            'brand' => $brandnames,
            'sub_category'  =>$subcategories,
            'updated_by' =>Auth::user()->id,
            'quantity' => $qnty,
            'enquiry_quantity' =>$request->enquiryquantity,
            'total_quantity' =>$totalquantity,
             'notes' => $request->eremarks,
            'requirement_date' => $request->edate,
            'price' =>$price,
            'state'=>$request->state[0],
            'ship'=>$request->shipaddress,
            'billadress'=>$billaddress,
            
            'steelprice'=>$steel_price,
            'steelquantity'=>$steel_quantity,
            'follow_up'=>$request->fdate,
            'follow_up_by'=>Auth::user()->id,
            'interestedorder'=>$request->interestorder,
            'language'=>$lng,
            'cid'=>$request->cid
        ]);

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
$ids = $request->ids;

  for ($i=0; $i <count($ids); $i++) { 
       
       $newdata =FLOORINGS::where('id',$ids[$i])->first();
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
       $newdata->req_id = $request->reqId;

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
$ids = $request->ids;
  for ($i=0; $i <count($ids); $i++) { 
       
       $newdata =FLOORINGS::where('id',$ids[$i])->first();         
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
       

      $newdata->save();
  }

       


        }









        // customer table  
       if($request->pid != null){
        $number = procurementDetails::where('project_id',$request->pid)->pluck('procurement_contact_no')->first();
       }
       else{
        $number = Mprocurement_Details::where('manu_id',$request->mid)->pluck('contact')->first();
       }
     
        $check = CustomerGst::where('customer_phonenumber',$number)->count();
        
        if($check == 1 && $request->cgst != null && $request->pid != null){
             $customergst = strtoupper($request->cgst);
            $gst = CustomerGst::where('customer_phonenumber',$number)->update(['customer_gst'=>$customergst]);
        }
        if($check == 1 && $request->cgst != null && $request->mid != null){
             $customergst = strtoupper($request->cgst);
            $gst = CustomerGst::where('customer_phonenumber',$number)->update(['customer_gst'=>$customergst]);
        }
        if($check == 0 && $request->pid != null){
            $contractor = ContractorDetails::where('project_id',$request->pid)->pluck('contractor_contact_no')->first();
            $site = SiteEngineerDetails::where('project_id',$request->pid)->pluck('site_engineer_contact_no')->first();
            $owner = OwnerDetails::where('project_id',$request->pid)->pluck('owner_contact_no')->first();
            $consult = ConsultantDetails::where('project_id',$request->pid)->pluck('consultant_contact_no')->first();
            $customergst = strtoupper($request->cgst);
        $customer = new CustomerGst;
        $customer->customer_gst = $customergst;
        $customer->customer_phonenumber = $number;
        $customer->contractor_no = $contractor;
        $customer->site_engineer_no = $site;
        $customer->owner_no = $owner;
        $customer->consultant_no = $consult;
        $customer->save();
            $req  =  requirement::where('id',$request->reqId)->where('status',"Enquiry Confirmed")->count();
            if($req == 1){
                    $country_code = Country::pluck('country_code')->first();
                    $zone = Zone::pluck('zone_number')->first();
                    $cus_id = "MH_".$country_code."_".$zone."_C".$customer->id;
                    $cid = CustomerGst::where('customer_phonenumber',$number)->update(['customer_id'=>$cus_id]);
                }
        }

        if($check == 0 && $request->mid != null){
            $owner = Mowner_Deatils::where('manu_id',$request->mid)->pluck('contact')->first();
        $customergst = strtoupper($request->cgst);
        $customer = new CustomerGst;
        $customer->customer_gst = $customergst;
        $customer->customer_phonenumber = $number;
        $customer->owner_no = $owner;
        $customer->save();
            $req  =  requirement::where('id',$request->reqId)->where('status',"Enquiry Confirmed")->count();
            if($req == 1){
                    $country_code = Country::pluck('country_code')->first();
                    $zone = Zone::pluck('zone_number')->first();
                    $cus_id = "MH_".$country_code."_".$zone."_C".$customer->id;
                    $cid = CustomerGst::where('customer_phonenumber',$number)->update(['customer_id'=>$cus_id]);
                }
        }
         $y =Order::where('req_id',$request->reqId)->update([
                  'main_category' => $categoryNames,
                  'brand' => $brandnames,
                 'sub_category'  =>$subcategories,
                 'quantity' => $request->totalquantity
         ]);
        
        $inv = DB::table('orders')->where('req_id',$request->reqId)->pluck('id')->first();
        $invgenerate = DB::table('orders')->where('req_id',$request->reqId)->pluck('confirm_payment')->first();
        $payment = DB::table('orders')->where('req_id',$request->reqId)->pluck('payment_status')->first();
      
        if($payment != null){
                PaymentDetails::where('order_id',$inv)->update([
                'quantity'=>$request->totalquantity,
                'mamahome_price'=>$request->price
            ]);
        }
        $invoice = MamahomePrice::where('order_id',$inv)->get()->first();

            if($invoice != null && $invgenerate != null){

                    if($quan == "none" || $price == "none"){
                            // calculation part
                       
                        $q = $request->totalquantity;
                        $p = $request->price;
                        $igst = $invoice->igstpercent;
                        $unitwithgst = ($p/$invoice->gstpercent);
                        $totalamount = ($q *  $unitwithgst);//int
                        $tamount = (int)$totalamount;
                        $cgst = ($totalamount * $invoice->cgstpercent)/100;
                        $sgst =  ($totalamount * $invoice->sgstpercent)/100;
                        $tt = $cgst + $sgst;//int
                        $totaltax = (int)$tt;
                        $igst = ($totalamount * $invoice->igstpercent)/100;//int
                        $tigst = (int)$igst;
                        $withgst = ($totalamount + $tt + $igst);//int
                        $finalamount = (int)$withgst;

                        // convert to wordwrap
        $number = $tamount;
        $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number.'&token=fshadvjfa67581232';
        $response = file_get_contents($url);
        $data = json_decode($response,true);
        $dtow = $data['message'];

        $number1 = $totaltax;
        $url1 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number1.'&token=fshadvjfa67581232';
        $response1 = file_get_contents($url1);
        $data1 = json_decode($response1,true);
        $dtow1 = $data1['message'];

        $number2 = $finalamount;
        $url2 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number2.'&token=fshadvjfa67581232';
        $response2 = file_get_contents($url2);
        $data2 = json_decode($response2,true);
        $dtow2 = $data2['message'];

        $number3 = $tigst;
        $url3 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number3.'&token=fshadvjfa67581232';
        $response3 = file_get_contents($url3);
        $data3 = json_decode($response3,true);
        $dtow3 = $data3['message'];

                    $invoice = MamahomePrice::where('order_id',$inv)->update([
                        'category' =>$categoryNames,
                        'quantity'=>$q,
                        'mamahome_price'=>$p,
                        'unitwithoutgst'=>$unitwithgst,
                        'totalamount'=>$tamount,
                        'cgst'=>$cgst,
                        'sgst'=>$sgst,
                        'igst'=>$tigst,
                        'totaltax'=>$totaltax,
                        'amountwithgst'=>$finalamount,
                        'amount_word'=>$dtow,
                        'tax_word'=>$dtow1,
                        'gstamount_word'=>$dtow2,
                        'igsttax_word'=>$dtow3
                       
                    ]);
                }
                 if($bill == "none" || $ship == "none" ){

                        $invoice = MamahomePrice::where('order_id',$inv)->update([
                        'billaddress' =>$billaddress,
                        'shipaddress' =>$request->shipaddress
                    ]);
                 }
               
            }

        $supplier = Supplierdetails::where('order_id',$inv)->where('state','!=',null)->get()->first();
         if($supplier != null){
           
                    if($quan == "none"){
                            // calculation part
                       
                        $q = $request->totalquantity;
                        $p = $supplier->unit_price;
                        $igst = $supplier->igstpercent;
                        $unitwithgst = ($p/$supplier->gstpercent);
                        $totalamount = ($q * $unitwithgst);//int
                        $tamount = (int)$totalamount;
                        $cgst = ($totalamount * $supplier->cgstpercent)/100;
                        $sgst =  ($totalamount * $supplier->sgstpercent)/100;
                        $tt = $cgst + $sgst + $totalamount ;//int
                        $totaltax = (int)$tt;
                        // convert to wordwrap
                        $number = $tamount;
                        $url = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number.'&token=fshadvjfa67581232';
                        $response = file_get_contents($url);
                        $data = json_decode($response,true);
                        $dtow = $data['message'];

                        $number1 = $totaltax;
                        $url1 = 'https://www.mamamicrotechnology.com/api/public/convert_cur?number='.$number1.'&token=fshadvjfa67581232';
                        $response1 = file_get_contents($url1);
                        $data1 = json_decode($response1,true);
                        $dtow1 = $data1['message'];
                
                        // dd($p ,$q , $unitwithgst ,$tamount,$cgst,$sgst ,$totaltax,$dtow ,$dtow1);
                         Supplierdetails::where('order_id',$inv)->update([
                        'category' =>$categoryNames,
                        'quantity'=>$q,
                        'unit_price'=>$p,
                        'unitwithoutgst'=>$unitwithgst,
                        'amount'=>$tamount,
                        'totalamount'=>$totaltax,
                        'amount_words'=> $dtow,
                        'tamount_words'=> $dtow1
                            ]);
                    }
                                     if($ship == "none" ){
                                     Supplierdetails::where('order_id',$inv)->update([
                                    'ship' =>$request->shipaddress
                                        ]);
                                     }
                }
           
        $pro = Requirement::where('id',$request->reqId)->pluck('project_id')->first();
        $activity = new ActivityLog;
        $activity->time = date('Y-m-d H:i A');
        $activity->employee_id = Auth::user()->employeeId;
        $activity->req_id = $request->reqId;
        $activity->project_id = $pro;
        $project = ProjectDetails::where('project_id',$pro)->pluck('sub_ward_id')->first();
        $activity->sub_ward_id = $project;
        $uproject = ProjectDetails::where('project_id',$pro)->pluck('updated_by')->first();
        
        $qproject = ProjectDetails::where('project_id',$pro)->pluck('quality')->first();
        $fproject = ProjectDetails::where('project_id',$pro)->pluck('followup')->first();
        $eproject = Requirement::where('project_id',$pro)->pluck('generated_by')->first();
        $activity->updater = $uproject;
       $activity->quality = $qproject;
       $activity->followup = $fproject;
       if(count($eproject) != 0){
        $activity->enquiry = $eproject;
       }
        else{
           $activity->enquiry ="null";
          }
         $activity->typeofactivity = "Enquiry Updated";
        $activity->activity = Auth::user()->name." has updated requirement id: ".$pro." as ".$request->note.$request->status;
        $activity->save();

       if($x)
        {
            return back()->with('success','Enquiry updated Successfully !!!');
        }
        else
        {
            return back()->with('success','Error Occurred !!!');
        }
    }
    public function saveMap(Request $request)
    {
        $convert = str_replace('(', '', $request->path);
        $path = str_replace(')','', $convert);
        $zone_id = SubWard::where('id',$request->zone)->pluck('zone_id')->first();
     
        if($request->page == "Zone"){
            if($check = ZoneMap::where('zone_id',$request->zone)->count() == 0){
                $map = new ZoneMap;
                $map->zone_id = $request->zone;
                $map->lat = $path;
                $map->color = $request->color;
                $map->save();
            }else{
                $check = ZoneMap::where('zone_id',$request->zone)->first();
                $check->lat = $path;
                $check->color = $request->color;
                $check->save();
            }
        }elseif($request->page == "Ward"){
            if($check = WardMap::where('ward_id',$request->zone)->count() == 0){
                $map = new WardMap;
                $map->ward_id = $request->zone;
                $map->lat = $path;
                $map->color = $request->color;
                $map->save();
            }else{
                $check = WardMap::where('ward_id',$request->zone)->first();
                $check->lat = $path;
                $check->color = $request->color;
                $check->save();
            }
        }elseif($request->page == "Sub Ward"){
            if($check = SubWardMap::where('sub_ward_id',$request->zone)->count() == 0){
                $map = new SubWardMap;
                $map->sub_ward_id = $request->zone;
                $map->lat = $path;
                $map->color = $request->color;
                $map->zone_id = $zone_id;
                $map->save();
            }else{
                $check = SubWardMap::where('sub_ward_id',$request->zone)->first();
                $check->lat = $path;
                $check->color = $request->color;
                $check->save();
            }
        }
        return back();
    }
    public function saveWardMap(Request $request)
    {
        $check = WardMap::where('ward_id',$request->ward_id)->first();
        if(count($check)== 0){
            $wardmaps = new WardMap;
            $wardmaps->ward_id = $request->ward_id;
            $wardmaps->lat = $request->path;
            $wardmaps->color = $request->color;
            $wardmaps->save();
        }else{
            $check->ward_id = $request->ward_id;
            $check->lat = $request->path;
            $check->color = $request->color;
            $check->save();
        }
        return back();
    }
    public function addPoints(Request $request)
    {
        $point = new Point;
        $point->user_id = $request->userId;
        $point->point = $request->point;
        $point->type = $request->type;
        $point->reason = $request->reason;
        $point->confirmation = Auth::user()->department_id == 1 ? 0 : 1;
        $point->created_at = $request->date;
        $point->updated_at = $request->date;
        $point->save();
        return back()->with('Success','Your request has been sent and is waiting for admin approval');
    }
    public function addDeliveryBoy(Request $request)
    {
        Order::where('id',$request->orderId)->update(['delivery_boy'=>$request->delivery]);

        return back();
    }
     public function paymentmode(Request $request)
    {

        Order::where('id',$request->orderId)->update(['payment_mode'=>$request->payment]);
        
        return back();
    }
  public function clearcheck(Request $request)
    {
       Order::where('id',$request->id)->update(['payment_mode'=>$request->satus]);
        return back();
    }


    public function check(request $request){
        
        // $empimage = time().'.'.request()->image->getClientOriginalExtension();
        // $request->image->move(public_path('chequeimages'),$empimage);

              $empimage = $request->file('image');
             $imageFileName = time() . '.' . $empimage->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/chequeimages/' . $imageFileName;
             $s3->put($filePath, file_get_contents($empimage), 'public');




        $check = new Check;
        $check->project_id=$request->project_id;
        $check->orderId = $request->orderId;
        $check->checkno  = $request->checkno; 
        $check->amount = $request->amount;
        $check->bank = $request->bank;

        $check->date = $request->date;
        $check->image = $imageFileName;
        $check->save();
        $check = "Check";
        Order::where('id',$request->orderId)->update(['payment_mode' =>$check]);
        return back();
    }
    public function checkdetailes(request $request){
        $details = Check::all();
        $countrec = count($details);
        $check = Order::all();

        return view('checkdetailes',['details' => $details,'countrec'=>$countrec,'check'=>$check]);
    }
   
   

    public function postSaveManufacturer(Request $request)
    {
        

             if(count($request->sub_ward_id) != 0){
                  $wardsAssigned = $request->subward_id;
             }else{
                
             $wardsAssigned = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();
             }


           if($request->production){
            $pro = implode(",",$request->production);
           }else{
            $pro = "null";
           }
        $projectimage = "";
            $i = 0;
            if($request->pImage){
                foreach($request->pImage as $pimage){

                     $imageName3 = $pimage;
             $imageFileName = time() . '.' . $imageName3->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/Manufacturerimage/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName3), 'public'); 






                     // $imageName3 = $i.time().'.'.$pimage->getClientOriginalExtension();
                     // $pimage->move(public_path('Manufacturerimage'),$imageName3);
                     if($i == 0){
                        $projectimage .= $imageFileName;
                     }
                     else{
                            $projectimage .= ",".$imageFileName;
                     }
                     $i++;
                }
        
            }

           $cardimages = "";
            $i = 0;
            if($request->bImage){
                foreach($request->bImage as $pimage){

                     $imageName3 = $pimage;
             $imageFileName = time() . '.' . $imageName3->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/Manufacturerbusinesscard/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName3), 'public'); 






                     // $imageName3 = $i.time().'.'.$pimage->getClientOriginalExtension();
                     // $pimage->move(public_path('Manufacturerimage'),$imageName3);
                     if($i == 0){
                        $cardimages .= $imageFileName;
                     }
                     else{
                            $cardimages .= ",".$imageFileName;
                     }
                     $i++;
                }
        
            }







           $point = 0;
        // counting points
        // plant name
        $point = $request->plant_name != null ? $point+2 : $point+0;
        // capacity
        $point = $request->capacity != null ? $point+2 : $point+0;        
        // cement_requirement 
        $point = $request->cement_requirement != null ? $point+4 : $point+0;
        // cement_required type
        $point = $request->cement_required != null ? $point+5 : $point+0;
        // interested in rmc
        $point = $request->brand != null ? $point+3 : $point+0;
        // type of contract
        $point = $request->sand_requirement != null ? $point+6 : $point+0;
        // project status
        $point = $request->aggregate_requirement != null ? $point+5 : $point+0;
        // project type
        $point = $request->manufacturing_type != null && $request->ground != null ? $point+5 : $point+0;
        // project size
        $point = $request->moq != null ? $point+8 : $point+0;
        // budgettype
        $point = $request->total_area != null ? $point+3 : $point+0;

        // contractor details
        $point = $request->cName != null ? $point+3 : $point+0;
        $point = $request->cEmail != null ? $point+3 : $point+0;
        $point = $request->cContact != null ? $point+3 : $point+0;
        // consultant details
        // $point = $request->coName != null ? $point+3 : $point+0;
        // $point = $request->coEmail != null ? $point+3 : $point+0;
        // $point = $request->coContact != null ? $point+3 : $point+0;
        // site engineer details
        $point = $request->oName != null ? $point+3 : $point+0;
        $point = $request->oEmail != null ? $point+3 : $point+0;
        $point = $request->oContact != null ? $point+3 : $point+0;
        // procurement details
        $point = $request->prName != null ? $point+3 : $point+0;
        $point = $request->pEmail != null ? $point+3 : $point+0;
        $point = $request->prPhone != null ? $point+3 : $point+0;
        $point = $request->remarks != null ? $point+10 : $point+0;
        
        // store points to database
        $points = new Point;
        $points->user_id = Auth::user()->id;
        $points->point = $point;
        $points->type = "Add";
        $points->reason = "Adding manufacturer project";
        $points->save();

        $manufacturer = new Manufacturer;
        $manufacturer->listing_engineer_id = Auth::user()->id;
        $manufacturer->name = $request->name;
        $manufacturer->image = $projectimage;

        $manufacturer->sub_ward_id = $wardsAssigned;
        $manufacturer->plant_name = $request->plant_name;
        $manufacturer->latitude = $request->latitude;
        $manufacturer->longitude = $request->longitude;
        $manufacturer->address = $request->address;
        $manufacturer->contact_no = $request->phNo;
        $manufacturer->capacity = $request->capacity;
        $manufacturer->cement_requirement = $request->cement_requirement;
        $manufacturer->cement_requirement_measurement = $request->cement_required;
        
        $manufacturer->sand_requirement = $request->sand_requirement;
        $manufacturer->aggregates_required = $request->aggregate_requirement;
        $manufacturer->manufacturer_type = $request->type;
        $manufacturer->type = $request->manufacturing_type;
        $manufacturer->moq = $request->moq;
        $manufacturer->total_area = $request->total_area;
        $manufacturer->remarks = $request->remarks;
        $manufacturer->production_type = $pro;
        $manufacturer->interested_in_loan = $request->loaninterest;
        $manufacturer->ggbss = $request->ggbss;
        $manufacturer->chemical = $request->chemical;
        $manufacturer->roadwidth =$request->roadwidth;
        $manufacturer->roadname=$request->roadname;
        $manufacturer->storage=$request->storage;
        $manufacturer->cctv=$request->cctv;
        $manufacturer->cardimages = $request->cardimages;
        $manufacturer->refcustid = $request->cid;
        $manufacturer->silo = $request->silo;


        $manufacturer->save();
        $sales = new Salescontact_Details;
       $sales->manu_id =  $manufacturer->id;
       $sales->name = $request->coName;
       $sales->email = $request->coEmail;
       $sales->contact = $request->coContact;
       $sales->contact1 = $request->coContact1;
       
       $sales->save();

       $manager = new Manager_Deatils;
       $manager->manu_id =  $manufacturer->id;
       $manager->name = $request->cName;
       $manager->email = $request->cEmail;
       $manager->contact = $request->cContact;
       $manager->contact1 = $request->cContact1;
       $manager->whatsapp = $request->mwhatsapp;

       $manager->save();
    
       $proc = new Mprocurement_Details;
       $proc->manu_id =  $manufacturer->id;
       $proc->name = $request->prName;
       $proc->email = $request->pEmail;
       $proc->contact = $request->prPhone;
       $proc->contact1 = $request->prPhone1;
       $proc->whatsapp = $request->pwhatsapp;

       $proc->save();

        $owner = new Mowner_Deatils;
       $owner->manu_id =  $manufacturer->id;
       $owner->name = $request->oName;
       $owner->email = $request->oEmail;
       $owner->contact = $request->oContact;
       $owner->contact1 = $request->oContact1;
       $owner->whatsapp = $request->owhatsapp;

       $owner->save();

        
        if($request->type == "Blocks"){
            // saving product details
            for($i = 0; $i < count($request->blockType); $i++){
                $products = new ManufacturerProduce;
                $products->manufacturer_id = $manufacturer->id;
                $products->block_type = $request->blockType[$i];
                $products->block_size = $request->blockSize[$i];
                $products->price = $request->price[$i];
                $products->save();
            }
        }elseif($request->type == "RMC"){
            // saving product details
            for($i = 0; $i < count($request->grade); $i++){
                $products = new ManufacturerProduce;
                $products->manufacturer_id = $manufacturer->id;
                $products->block_type = $request->grade[$i];
                $products->price = $request->gradeprice[$i];
                $products->save();
            }
        }elseif($request->type == "Fabricators"){
            // saving product details
            for($i = 0; $i < count($request->fab); $i++){
                $products = new ManufacturerProduce;
                $products->manufacturer_id = $manufacturer->id;
                $products->Fabricators_type = $request->fab[$i];
                $products->price = $request->fabprice[$i];
                $products->save();
            }
        }
      if($request->Suppliername){
        $Suppliername =implode(",", $request->Suppliername);
      }else{
        $Suppliername = "";
      }
      if($request->minquan){
        $minquan =implode(",", $request->minquan);
      }else{
        $minquan = "";
      }
   
      if($request->brand1){
        $brand =implode(",", $request->brand1);
      }else{
        $brand = "";
      }


      if($request->quan){
        $quan =implode(",", $request->quan);
      }else{
        $quan = "";
      }
      
      
       if($request->price){
             $s = $request->price;
             array_pop($s);
        $price =implode(",", $s);
      }else{
        $price = "";
      }
     


     if($brand != null){
     $bransyup = new CustomerBrands;
     $bransyup->manu_id = $manufacturer->id;
     $bransyup->user_id = Auth::user()->id;
      $bransyup->Suppliername = $Suppliername;
     $bransyup->minquan = $minquan; 
     $bransyup->brand = $brand;
     $bransyup->price = $price;
     $bransyup->quan = $quan;
     $bransyup->save();
     }
     
        $ward = SubWard::where('id',$request->subward_id)->pluck('sub_ward_name')->first();
               $text = "Manufacturer Saved Successfully in Subward is : ".$ward."";
        return back()->with('Success',$text);
    }

    public function listeng(request $request){
       // $s = User::pluck('id');
       // $tl = Tlwards::whereIn('user_id',$s)->pluck('users');

         $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
        $userIds = explode(",", $tl);
       
      $listengs= User::whereIn('users.id',$userIds)
                        ->where('users.group_id',6)
                        ->leftjoin('ward_assignments','ward_assignments.user_id','=','users.id')
                        ->leftjoin('sub_wards','sub_wards.id','=','ward_assignments.subward_id')
                        ->leftjoin('wards','wards.id','=','sub_wards.ward_id' )
                        ->leftjoin('employee_details','users.employeeId','=','employee_details.employee_id') 
                        ->where('department_id','!=','10')
                        ->select('users.employeeId','users.id','users.name','ward_assignments.status','sub_wards.sub_ward_name','sub_wards.sub_ward_image','ward_assignments.prev_subward_id','employee_details.office_phone')
                        ->get();
        
        return view('listeng',['listengs'=>$listengs]);
    }
    public function teamlisteng(request $request){
       
       $dpt = [1,2];
      $listengs= User::whereIn('users.department_id',$dpt)
                        ->leftjoin('ward_assignments','ward_assignments.user_id','=','users.id')
                        ->leftjoin('sub_wards','sub_wards.id','=','ward_assignments.subward_id')
                        ->leftjoin('wards','wards.id','=','sub_wards.ward_id' )
                        ->leftjoin('employee_details','users.employeeId','=','employee_details.employee_id') 
                        ->where('department_id','!=','10')
                        ->select('users.employeeId','users.id','users.name','ward_assignments.status','sub_wards.sub_ward_name','sub_wards.sub_ward_image','ward_assignments.prev_subward_id','employee_details.office_phone')
                        ->get();
                    
        return view('listeng',['listengs'=>$listengs]);
    }

    
    public function acceng(request $request){
       // $s = User::pluck('id');
       // $tl = Tlwards::whereIn('user_id',$s)->pluck('users');
         $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
        $userIds = explode(",", $tl);
       
      $accengs= User::whereIn('users.id',$userIds)
                        ->where('users.group_id',11)
                        ->leftjoin('ward_assignments','ward_assignments.user_id','=','users.id')
                        ->leftjoin('sub_wards','sub_wards.id','=','ward_assignments.subward_id')
                        ->leftjoin('wards','wards.id','=','sub_wards.ward_id' )
                        ->leftjoin('employee_details','users.employeeId','=','employee_details.employee_id') 
                        ->where('department_id','!=','10')
                        ->select('users.employeeId','users.id','users.name','ward_assignments.status','sub_wards.sub_ward_name','sub_wards.sub_ward_image','ward_assignments.prev_subward_id','employee_details.office_phone')
                        ->get();
                       
        return view('acceng',['accengs'=>$accengs]);
    }
    public function teamacceng(request $request){
      $accengs= User::where('users.group_id',11)
                        ->leftjoin('ward_assignments','ward_assignments.user_id','=','users.id')
                        ->leftjoin('sub_wards','sub_wards.id','=','ward_assignments.subward_id')
                        ->leftjoin('wards','wards.id','=','sub_wards.ward_id' )
                        ->leftjoin('employee_details','users.employeeId','=','employee_details.employee_id') 
                        ->where('department_id','!=','10')
                        ->select('users.employeeId','users.id','users.name','ward_assignments.status','sub_wards.sub_ward_name','sub_wards.sub_ward_image','ward_assignments.prev_subward_id','employee_details.office_phone')
                        ->get();
        return view('acceng',['accengs'=>$accengs]);
    }
    public function getmap(request $request)
    {
    $date = date('Y-m-d');
      $name = $request->name;
      $id = user::where('name',$name)->pluck('id');
      $login = FieldLogin::where('user_id',$id)->where('logindate',date('Y-m-d'))->get();
      $wardsAssigned = WardAssignment::where('user_id',$id)->where('status','Not Completed')->pluck('subward_id')->first();
      $subwards = SubWard::where('id',$wardsAssigned)->first();
      $projects = FieldLogin::where('user_id',$id)->where('logindate',date('Y-m-d'))->first();
      $todaylist = ProjectDetails::where('listing_engineer_id',$id)
                                                ->where('project_details.created_at','LIKE',$date.'%')
                                                ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                                                ->select('site_addresses.*','project_details.*')->get();

                                             
        $totalListing = count($todaylist);
         
        $todaymanulist = Manufacturer::where('listing_engineer_id',$id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->get();
        $totalmanu = count($todaymanulist);                            
        $rmc = Manufacturer::where('listing_engineer_id',$id)
                                ->where('created_at','LIKE',$date.'%')
                                ->where('manufacturer_type',"RMC")
                                ->count();
        $blocks = Manufacturer::where('listing_engineer_id',$id)
                                ->where('created_at','LIKE',$date.'%')
                                ->where('manufacturer_type',"Blocks")
                                ->count();
        if($subwards != null){
            $subwardMap = SubWardMap::where('sub_ward_id',$subwards->id)->first();
            
        }else{
            $subwardMap = "None";
        }   
        if($subwardMap == Null){
            $subwardMap = "None";
        }          
        $ward = user::where('users.id',$id)
            ->leftjoin('ward_assignments','ward_assignments.user_id','=','users.id')
            ->leftjoin('sub_wards','sub_wards.id','=','ward_assignments.subward_id')
            ->leftjoin('wards','wards.id','=','sub_wards.ward_id' )
            ->where('department_id','!=','10')
            ->pluck('sub_wards.sub_ward_name')->first();



       if($request->getmap){
        $date = $request->getmap;
        $todaylist = ProjectDetails::where('listing_engineer_id',$id)
                                                ->where('project_details.created_at','LIKE',$date.'%')
                                                ->leftjoin('site_addresses','project_details.project_id','=','site_addresses.project_id')
                                                ->select('site_addresses.*','project_details.*')->get();
        $totalListing = count($todaylist);
        $todaymanulist = Manufacturer::where('listing_engineer_id',$id)
                                                ->where('created_at','LIKE',$date.'%')
                                                ->get();
        $totalmanu = count($todaymanulist);                    
        $rmc = Manufacturer::where('listing_engineer_id',$id)
                                ->where('created_at','LIKE',$date.'%')
                                ->where('manufacturer_type',"RMC")
                                ->count();
        $blocks = Manufacturer::where('listing_engineer_id',$id)
                                ->where('created_at','LIKE',$date.'%')
                                ->where('manufacturer_type',"Blocks")
                                ->count();
            $storoads = TrackLocation::where('user_id',$id)
                        ->where('date',$request->getmap)
                         ->first();

                   


            $start = [];
            $end = [];
              $login = FieldLogin::where('user_id',$id)->where('logindate',$request->getmap)->get();
              $projects = FieldLogin::where('user_id',$id)->where('logindate',$request->getmap)->first();
              $wardsAssigned = WardAssignment::where('user_id',$id)->pluck('id');
              $activity =Activity::where('subject_id',$wardsAssigned)->where('created_at','LIKE',$date.'%')->get();
              // dd($activity);
              $wardid = " ";
              foreach($activity as $act){
                  $act2 = $act->changes();
                   foreach ($act2['attributes'] as $act3=>$value) {
                        if($act3 == "subward_id"){
                            $wardid = $value;
                        }
                   }
              }

                if($wardid ==" "){

                    $subwards = null;
                }
                else{

                       $subwards = SubWard::where('id',$wardid)->first();
                            if($subwards != null){
                            $subwardMap = SubWardMap::where('sub_ward_id',$subwards->id)->first();
                            
                            }else{
                                $subwardMap = "None";
                            }   
                            if($subwardMap == Null){
                                $subwardMap = "None";
                            } 
                }
               $ward = SubWard::where('id',$wardid)->pluck('sub_ward_name')->first();
              

        }
        else{
                $storoads = TrackLocation::where('user_id',$id)
                        ->where('date',date('Y-m-d'))
                        ->first();
                        $start = [];
                        $end = [];
               
        }
            if($storoads != null){
                $i = explode("|", $storoads->lat_long);
                $k = current($i);
                $start = explode(",",$k);
                $b = end($i);
                $end = explode(",",$b);
            }

          

      return view('getmap',['name'=>$name,'ward'=>$ward,'subwards'=>$subwards,'projects'=>$projects,'subwardMap'=>$subwardMap,'login'=>$login,'storoads'=>$storoads,'totalListing'=>$totalListing,'totalmanu'=>$totalmanu,'rmc'=>$rmc,'blocks'=>$blocks,'start'=>$start,'end'=>$end,'todaylist'=>$todaylist,'todaymanulist'=>$todaymanulist]);
    }
    public function getaccmap(request $request)
    {
      $name = $request->name;
      $id = user::where('name',$name)->pluck('id');
      $login = FieldLogin::where('user_id',$id)->where('logindate',date('Y-m-d'))->get();
      $wardsAssigned = WardAssignment::where('user_id',$id)->where('status','Not Completed')->pluck('subward_id')->first();
      $subwards = SubWard::where('id',$wardsAssigned)->first();
      $projects = FieldLogin::where('user_id',$id)->where('logindate',date('Y-m-d'))->first();
       
      if($subwards != null){
            $subwardMap = SubWardMap::where('sub_ward_id',$subwards->id)->first();
        }else{
            $subwardMap = "None";
        }   
        if($subwardMap == Null){
            $subwardMap = "None";
        }          
        $ward = user::where('users.id',$id)
        ->leftjoin('ward_assignments','ward_assignments.user_id','=','users.id')
        ->leftjoin('sub_wards','sub_wards.id','=','ward_assignments.subward_id')
        ->leftjoin('wards','wards.id','=','sub_wards.ward_id' )
        ->where('department_id','!=','10')
        ->pluck('sub_wards.sub_ward_name')->first();

        if($request->getmap){
             $date = $request->getmap;
            $storoads = TrackLocation::where('user_id',$id)
                        ->where('date',$request->getmap)
                         ->first();
             $login = FieldLogin::where('user_id',$id)->where('logindate',$request->getmap)->get(); 
             $projects = FieldLogin::where('user_id',$id)->where('logindate',$request->getmap)->first();
             $wardsAssigned = WardAssignment::where('user_id',$id)->pluck('id');
              $activity =Activity::where('subject_id',$wardsAssigned)->where('created_at','LIKE',$date.'%')->get();
              // dd($activity);
              $wardid = " ";
              foreach($activity as $act){
                  $act2 = $act->changes();
                   foreach ($act2['attributes'] as $act3=>$value) {
                        if($act3 == "subward_id"){
                            $wardid = $value;
                        }
                   }
              }
                 if($wardid ==" "){
                    
                    $subwards = null;
                }
                else{
               $subwards = SubWard::where('id',$wardid)->first();
                    if($subwards != null){
                    $subwardMap = SubWardMap::where('sub_ward_id',$subwards->id)->first();
                    
                    }else{
                        $subwardMap = "None";
                    }   
                    if($subwardMap == Null){
                        $subwardMap = "None";
                    } 
                }
               $ward = SubWard::where('id',$wardid)->pluck('sub_ward_name')->first();
        }
        else{
                $storoads = TrackLocation::where('user_id',$id)
                        ->where('date',date('Y-m-d'))
                        ->first();
        }

      return view('getaccmap',['name'=>$name,'ward'=>$ward,'login'=>$login,'subwards'=>$subwards,'projects'=>$projects,'subwardMap'=>$subwardMap,'storoads'=>$storoads]);
    }

    // public function teammap(request $request)
    // {
    //   $name = $request->name;
    //   $id = user::where('name',$name)->pluck('id');
    //   $login = FieldLogin::where('user_id',$id)->where('logindate',date('Y-m-d'))->get();
    //  $projects = FieldLogin::where('user_id',$id)->where('logindate',date('Y-m-d'))->first();   
    
    //  $wardsAssigned = WardAssignment::where('user_id',$id)->where('status','Not Completed')->pluck('subward_id')->first();
    // $subwards = SubWard::where('id',$wardsAssigned)->first();
    //     $projects = FieldLogin::where('user_id',$id)->where('logindate',date('Y-m-d'))->first();
       
    //   if($subwards != null){
    //         $subwardMap = SubWardMap::where('sub_ward_id',$subwards->id)->first();
    //     }else{
    //         $subwardMap = "None";
    //     }   
    // if($subwardMap == Null){
    //     $subwardMap = "None";
    // }      
   
    //   return view('teammap',['name'=>$name,'login'=>$login,'subwards'=>$subwards,'projects'=>$projects,'subwardMap'=>$subwardMap]);
    // }
    public function recordtime(Request $request)
    {

        if($request->remark != null){
                $remark = $request->remark;
        }
        else{
            $remark = null;
        }
       $id = user::where('id',Auth::user()->id)->pluck('id')->first();
       $check = FieldLogin::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->get();  
       $lat = $request->latitude;
       $lon = $request->longitude;
       $address = $request->address;
       $start = "08:00 AM";
       $now = date('H:i A');
      if( $now > $start && count($check)== 0 && $remark == null){
            $text = " <form action='lateremark?latitude=".$lat." && longitude=".$lon." && address=".$address."' method='POST'> <input type='hidden' name='_token' value='".Session::token()."'> <textarea required style='resize:none;'  name='remark' placeholder='Reason For Late Login..' class='form-control' type='text'></textarea><br><center><button type='submit' class='btn btn-success' >Submit</button></center></form>";
            return back()->with('Late',$text); 
            }
        else
        {
                    
                  
                        $field = new FieldLogin;
                        $field->user_id = $id;
                        $field->logindate = date('Y-m-d');
                        $field->logintime = date(' H:i A');
                        $field->remark = $remark;
                        $field->latitude = $request->latitude;
                        $field->longitude = $request->longitude;
                        $field->address = $request->address;
                        $field->tlapproval = "Pending";
                        $field->adminapproval = "Pending";
                        $field->status = "Pending";
                        $field->hrapproval = "Pending";
                        $field->logout_remark = "";
                        $field->save();
                        $text = "You Have Logged In Successfully!..";

                    Notification::send($field,new FiledLogin(FieldLogin::latest('user_id')->first()));
                    
                      $data = DB::table('notifications')->latest('fid')->first();

                      DB::table('notifications')->where('fid',$data->fid)->update(['user_id'=>Auth::user()->id]);
                      $emails = User::where('group_id',2)->pluck('employeeId');
                        
                      $email = EmployeeDetails::orwhere('employee_id',Auth::user()->employeeId)->orWhereIn('employee_id',$emails)->where('official_email','!=',NULL)->pluck('official_email');

              
                             View::share('name',Auth::user()->name);
                             View::share('time',date(' H:i A'));
                             View::share('address',$request->address);
            
                         for($i=0;$i<sizeof($email);$i++){

                           Mail::to($email[$i])->send(new FieldLoginRequest($field));

                  
                         }
                           
                         return back()->with('Success',$text);
                         
                    }
                 
            }
    
    public function latelogin(Request $request){
        $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
        $userIds = explode(",", $tl);
        $users = FieldLogin::whereIn('user_id',$userIds)->where('logindate',date('Y-m-d'))
        ->where('remark','!='," ")
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();
              Notification::send($users,new FiledLogin(FieldLogin::latest('id')->first()));
              $data = DB::table('notifications')->latest('fid')->first();
              DB::table('notifications')->where('fid',$data->fid)->update(['user_id'=>Auth::user()->id]);
              $email = EmployeeDetails::where('employee_id',Auth::user()->employeeId)->pluck('official_email')->first();
                     
           View::share('name',Auth::user()->name);
            View::share('time',date(' H:i A'));
            

            Mail::to($email)->send(new FieldLoginRequest($users));
              return back();            
                        
       
    }
    public function teamlatelogin(Request $request){
        
        $dept = [1,2];
        $userIds = User::whereIn('department_id',$dept)->pluck('id');
        $users = FieldLogin::whereIn('user_id',$userIds)->where('logindate',date('Y-m-d'))
        ->where('remark','!='," ")
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();
        return view('latelogin',['users'=>$users]);
    }
     public function adminlatelogin(Request $request){
        $dept = [1,2,3,4,5,6];
        $thiMonth = date('Y-m');
        $userIds = User::whereIn('department_id',$dept)->pluck('id');
        $users = FieldLogin::whereIn('field_login.user_id',$userIds)
        ->where('field_login.created_at','LIKE',$thiMonth."%")
        ->where('remark','!='," ")
        ->leftjoin('users','field_login.user_id','users.id')
        ->orderBy('created_at','desc')
        ->select('field_login.*','users.name')
        ->get();
        $dates = FieldLogin::where('field_login.created_at','LIKE',$thiMonth."%")
        ->where('remark','!='," ")
        ->distinct()
        ->pluck('logindate')->toarray();
        return view('adminlatelogin',['users'=>$users,'dates'=>$dates]);
    }
    public function hrlatelogins(Request $request){
        // $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
        // $userIds = explode(",", $tl);
        $dept = [1,2,3,4,6];
        $thiMonth = date('Y-m');
        $userIds = User::whereIn('department_id',$dept)->pluck('id');
        $users = FieldLogin::whereIn('field_login.user_id',$userIds)
        ->where('field_login.created_at','LIKE',$thiMonth."%")
        ->where('remark','!='," ")
        ->leftjoin('users','field_login.user_id','users.id')
         ->orderBy('created_at','desc')
        ->select('field_login.*','users.name')
        ->get();
        $dates = FieldLogin::where('field_login.created_at','LIKE',$thiMonth."%")
        ->where('remark','!='," ")
        ->distinct()
        ->pluck('logindate')->toarray();


        return view('hrlatelogins',['users'=>$users,'dates'=>$dates]);
    }
    public function logouttime(Request $request){
        $check = FieldLogin::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->pluck('logindate');
          
            $dat = DB::table('notifications')->where('user_id',Auth::user()->id)->latest()->first();
            
            $data = DB::table('notifications')->where('id',$dat->id)->update(['logout'=>1]); 

        if(count($check)== 0){

            $text = "Please Login Before Logout.";
            return back()->with('Late',$text);
        }
        else{

            FieldLogin::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'logout' =>date('H:i A'),
            'logout_lat' => $request->latitude,
            'logout_long' => $request->longitude,
            'logout_address' => $request->address
        ]);
            $text = "You Have Logged out Successfully!..";
            return back()->with('Success',$text);
        }
    }
    public function empreports(Request $request){
        if($request->remark != null){
                $remark = $request->remark;
        }
        else{
            $remark = null;
        }
        $check = FieldLogin::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->pluck('logindate'); 
        $logout = FieldLogin::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->pluck('logout');

        $new =User::where('timeto',"18:00")->pluck('employeeId');
       $use = User::whereIn('employeeId',$new)->pluck("employeeId")->toArray();
       if(in_array(Auth::user()->id, $use)){
             $start = "18:00 ";
             $now = date('H:i ');
        }
        else{
             $start = "17:00";
             $now = date('H:i ');
        }
       
            if( $now < $start && $remark == null){
                $text = " <form action='earlyremark' method='POST'> <input type='hidden' name='_token' value='".Session::token()."'> <textarea required style='resize:none;'  name='remark' placeholder='Reason For early Logout..' class='form-control' type='text'></textarea><br><center><button type='submit' class='btn btn-success' >Submit</button></center></form>";
                for($i = 0; $i < 1; $i++){
                    $report = new Report;
                    $report->empId = Auth::user()->employeeId;
                    $report->report = $request->report[$i];
                    $report->start = $request->from[$i];
                    $report->end = $request->to[$i];
                    $report->save();
                }
            return back()->with('earlylogout',$text); 
            }
            else{
                if($logout == null){
                    for($i = 0; $i < 1; $i++){
                        $report = new Report;
                        $report->empId = Auth::user()->employeeId;
                        $report->report = $request->report[$i];
                        $report->start = $request->from[$i];
                        $report->end = $request->to[$i];
                        $report->save();
                    }
                }
            }
        if(count($check)== 0){
                return back()->with('error','Your Have To Login Before Logout');
        }
        else{
            $data = FieldLogin::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'logout' => date('h:i A'),
            'logout_remark' => $remark
        ]);
            $data = loginTime::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'logoutTime' => date('h:i A')
            
        ]);
              
        }
        return back()->with('Success','Your Report Has Been Saved Successfully');   
    }
    public function teamlogout(Request $request){

        $check = FieldLogin::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->pluck('logindate'); 
        if(count($check)== 0){

            $text = "Please Login Before Logout.";
            return back()->with('TeamLate',$text);
        }
        else{
            FieldLogin::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->update([
            'logout' => date('h:i A')
        ]);
            $text = "You Have Logged out Successfully!..";
            return back()->with('TeamSuccess',$text);
        }
    }
    public function approve(Request $request)
    {  
        FieldLogin::where('user_id',$request->id)->where('logindate',date('Y-m-d'))->update([
            'tlapproval' => Auth::user()->name." has Approved"
        ]);
        return back()->with('Success',"Approved Successfully!");
    }
    public function reject(Request $request)
    {  
        FieldLogin::where('user_id',$request->id)->where('logindate',date('Y-m-d'))->update([
            'tlapproval' => Auth::user()->name." has Rejected"
        ]);
        return back()->with('error',"Permission Rejected.");
    }
    public function adminapprove(Request $request)
    {  
       
        $x = FieldLogin::where('user_id',$request->id)->where('logindate',$request->logindate)->pluck('user_id')->first();
        $grp = [6,11];
        $user = user::where('id',$x)->whereIn('group_id',$grp)->pluck('id')->first();
        if($user != null){
        FieldLogin::where('user_id',$request->id)->where('logindate',$request->logindate)->update([
            'logintime' => "07.30 AM",
            'adminapproval' => Auth::user()->name. " has Approved"
        ]);
        }
        else{
            FieldLogin::where('user_id',$request->id)->where('logindate',$request->logindate)->update([
            'logintime' => "08.00 AM",
            'adminapproval' => Auth::user()->name. " has Approved"
        ]);

        }

        return back()->with('Success',"Approved Successfully!");
    }
    public function adminreject(Request $request)
    {  
        FieldLogin::where('user_id',$request->id)->where('logindate',$request->logindate)->update([
            'adminapproval' => Auth::user()->name. " has Rejected"
        ]);
        return back()->with('error',"Permission Rejected.");
    }
    public function hrapprove(Request $request)
    {  
       
        $x = FieldLogin::where('user_id',$request->id)->where('logindate',$request->logindate)->pluck('user_id')->first();
        $grp = [6,11];
        $user = user::where('id',$x)->whereIn('group_id',$grp)->pluck('id')->first();
        if($user != null){
        FieldLogin::where('user_id',$request->id)->where('logindate',$request->logindate)->update([
           
            'hrapproval' => Auth::user()->name. " has Approved"
        ]);
        }
        else{
            FieldLogin::where('user_id',$request->id)->where('logindate',$request->logindate)->update([
            
            'hrapproval' => Auth::user()->name. " has Approved"
        ]);

        }
        return back()->with('Success',"Approved Successfully!");
    }
    public function hrreject(Request $request)
    {  
        FieldLogin::where('user_id',$request->id)->where('logindate',$request->logindate)->update([
            'hrapproval' => Auth::user()->name." has Rejected"
        ]);
        return back()->with('error',"Permission Rejected.");
    }
    public function teamlogin(Request $request)
    {

        if($request->remark != null){
                $remark = $request->remark;
        }
        else{
            $remark = null;
        }
        $id = user::where('id',Auth::user()->id)->pluck('id')->first();
       $check = FieldLogin::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->get();  
       $lat = $request->latitude;
       $lon = $request->longitude;
       $address = $request->address; 
                        $start = "07:30 AM";
                        $now = date('H:i A');
        if( $now > $start && count($check)== 0 && $remark == null){
           
            // $text = "<textarea required style='resize:none;'  name='remark' placeholder='Reason For Late Login..' class='form-control' type='text'></textarea><br>";
            $text = " <form action='teamlate' method='POST'> <input type='hidden' name='_token' value='".Session::token()."'> <textarea required style='resize:none;'  name='remark' placeholder='Reason For Late Login..' class='form-control' type='text'></textarea><br><center><button type='submit' class='btn btn-success' >Submit</button></center></form>";
            return back()->with('TeamLate',$text); 
            }
        else
        {
                    if(count($check)== 0){
                        $field = new FieldLogin;
                        $field->user_id = $id;
                        $field->logindate = date('Y-m-d');
                        $field->logintime = date(' H:i A');
                        $field->remark = $remark;
                         $field->latitude = "";
                        $field->longitude = "";
                        $field->address = "";
                        $field->tlapproval = "Pending";
                        $field->adminapproval = "Pending";
                        $field->status = "Pending";
                         $field->hrapproval = "Pending";
                        $field->logout_remark = "";
                        $field->save();


                        $text = "You Have Logged In Successfully!..";
                        return back()->with('TeamSuccess',$text);
                    }
                    else{
                        return back();
                    }
            }
    }
    public function seniorteam(){

       $group = [2];
       $name = Group::where('id',2)->pluck('group_name')->first();
       $thiMonth = date('Y-m');
        $userIds = User::whereIn('group_id',$group)->pluck('id');
        $users = FieldLogin::whereIn('user_id',$userIds)->where('field_login.created_at','LIKE',$thiMonth."%")
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();
        return view('seniorteam',['users'=>$users,'name'=>$name]);

    }
    public function seniorteam1(){
                     
         $teams= User::where('users.group_id',2)
                        ->where('department_id','!=','10')
                        ->get();
        return view('stlogin',['teams'=>$teams]);

    }
    public function teamleader1(){
                     
         $teams= User::where('users.group_id',22)
                        ->where('department_id','!=','10')
                        ->get();
                
        return view('teamleader',['teams'=>$teams]);

    }
    public function teamleader(){

       $group = [22];
       $name = Group::where('id',22)->pluck('group_name')->first();
       $thiMonth = date('Y-m');
        $userIds = User::whereIn('group_id',$group)->pluck('id');
        $users = FieldLogin::whereIn('user_id',$userIds)->where('field_login.created_at','LIKE',$thiMonth."%")
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();
        return view('seniorteam',['users'=>$users,'name'=>$name]);

    }
    public function allteamleader(){

       $group = [22];
       $name = Group::where('id',22)->pluck('group_name')->first();
       $thiMonth = date('Y-m');
        $userIds = User::whereIn('group_id',$group)->pluck('id');
        $users = FieldLogin::whereIn('user_id',$userIds)->where('logindate',date('Y-m-d'))
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();
        return view('seniorteam',['users'=>$users,'name'=>$name]);

    }
    public function saleseng(){

       $group = [7,17];
       $name = Group::where('id',7)->pluck('group_name')->first();
       $thiMonth = date('Y-m');
        $userIds = User::whereIn('group_id',$group)->pluck('id');
        $users = FieldLogin::whereIn('user_id',$userIds)->where('field_login.created_at','LIKE',$thiMonth."%")
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();
        return view('seniorteam',['users'=>$users,'name'=>$name]);

    }
    public function listatt(){

       $group = [6,11];
        $group1 = [6,11,7,17,22,23,2];
       $name = Group::where('id',6)->pluck('group_name')->first();
       $thiMonth = date('Y-m');
        $userIds = User::whereIn('group_id',$group)->where('department_id','!=',10)->pluck('id');
        $userat = User::whereIn('group_id',$group1)->where('department_id','!=',10)->get();

        $users = FieldLogin::whereIn('user_id',$userIds)->where('field_login.created_at','LIKE',$thiMonth."%")
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();

        $ss = [];
          foreach ($userat as $user) {
              
        $att = FieldLogin::where('user_id',$user->id)->where('created_at','LIKE',$thiMonth."%")->count();
        $at = FieldLogin::where('user_id',$user->id)->where('logout','<',strtotime('3 pm ' . date('d-m-Y')))->where('created_at','LIKE',$thiMonth."%")->count();


                
                array_push($ss, ['working'=>$att,'halfday'=>$at,'name'=>$user->name]);
          }

        return view('seniorteam',['users'=>$users,'name'=>$name,'work'=>$ss]);

    }
    public function allsaleseng(){

       $group = [7,17];
       $name = Group::where('id',7)->pluck('group_name')->first();
       $thiMonth = date('Y-m');
        $userIds = User::whereIn('group_id',$group)->pluck('id');
        $users = FieldLogin::whereIn('user_id',$userIds)->where('logindate',date('Y-m-d'))
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();
        return view('seniorteam',['users'=>$users,'name'=>$name]);

    }

    public function accexe(){

       $group = [11];
       $name = Group::where('id',11)->pluck('group_name')->first();
       $thiMonth = date('Y-m');
        $userIds = User::whereIn('group_id',$group)->pluck('id');
        $users = FieldLogin::whereIn('user_id',$userIds)->where('field_login.created_at','LIKE',$thiMonth."%")
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();
        return view('seniorteam',['users'=>$users,'name'=>$name]);

    }
    public function market(){

       $group = [16];
       $name = Group::where('id',16)->pluck('group_name')->first();
       $thiMonth = date('Y-m');
        $userIds = User::whereIn('group_id',$group)->pluck('id');
        $users = FieldLogin::whereIn('user_id',$userIds)->where('field_login.created_at','LIKE',$thiMonth."%")
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();
        return view('seniorteam',['users'=>$users,'name'=>$name]);

    }
    public function marketexe(){

       $group = [8];
       $name = Group::where('id',8)->pluck('group_name')->first();
       $thiMonth = date('Y-m');
        $userIds = User::whereIn('group_id',$group)->pluck('id');
        $users = FieldLogin::whereIn('user_id',$userIds)->where('field_login.created_at','LIKE',$thiMonth."%")
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();
        return view('seniorteam',['users'=>$users,'name'=>$name]);
    }
    public function hr(){
       $group = [14];
       $name = Group::where('id',14)->pluck('group_name')->first();
       $thiMonth = date('Y-m');
        $userIds = User::whereIn('group_id',$group)->pluck('id');
        $users = FieldLogin::whereIn('user_id',$userIds)->where('field_login.created_at','LIKE',$thiMonth."%")
        ->leftjoin('users','field_login.user_id','users.id')
        ->select('field_login.*','users.name')->get();
        return view('seniorteam',['users'=>$users,'name'=>$name]);
    }
     public function teamsales(request $request){
       
         $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
        $userIds = explode(",", $tl);
       $groupid = [7,17];
       $group = [7];
       $name = Group::where('id',7)->pluck('group_name')->first();
      $sales= User::whereIn('users.id',$userIds)
                        ->whereIn('users.group_id',$groupid)
                        ->pluck('id');
        $users = FieldLogin::whereIn('user_id',$sales)
        ->leftjoin('users','field_login.user_id','users.id')->where('logindate',date('Y-m-d'))
        ->select('field_login.*','users.name')->get();
            
        return view('seniorteam',['users'=>$users,'name'=>$name]);
    }
    public function saveUpdatedManufacturer(Request $request)
    {

        $manufacturer = Manufacturer::findOrFail($request->id);
      $manufacture = Manufacturer::where('id',$request->id)->first();
           

        if(count($request->subward) == 0){
            $wardsAssigned = $request->subward;
        }else{
            
        $wardsAssigned = Manufacturer::where('id',$request->id)->pluck('sub_ward_id')->first();
        }

       if($request->production){
            $pro = implode(",",$request->production);
           }else{
            $pro = "null";
           }
           
        $manufacturer->name = $request->name;
          $projectimage = "";
        if($request->pImage){
            if($request->pImage != null){
                       $i = 0;
                       foreach($request->pImage as $pimage){

                               $imageName3 = $pimage;
             $imageFileName = time() . '.' . $imageName3->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/Manufacturerimage/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName3), 'public'); 

                             // $imageName3 = $i.time().'.'.$pimage->getClientOriginalExtension();
                             // $pimage->move(public_path('Manufacturerimage'),$imageName3);
                        
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

     
      $cardimages = "";
            $i = 0;
            if($request->bImage){
                foreach($request->bImage as $pimage){

                     $imageName3 = $pimage;
             $imageFileName = time() . '.' . $imageName3->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/Manufacturerbusinesscard/' . $imageFileName;
             $s3->put($filePath, file_get_contents($imageName3), 'public'); 






                     // $imageName3 = $i.time().'.'.$pimage->getClientOriginalExtension();
                     // $pimage->move(public_path('Manufacturerimage'),$imageName3);
                     if($i == 0){
                        $cardimages .= $imageFileName;
                     }
                     else{
                            $cardimages .= ",".$imageFileName;
                     }
                     $i++;
                }
                
            }
        $result = Input::except('pImage','bImage');

      $new = new UpdatedReport;
      $new->manu_id = $request->id;
      $new->user_id = Auth::user()->id;
      $new->quntion = $request->quntion;
      $new->remarks = $request->remarks;
      $new->allfileds = serialize($result);
      $new->old = serialize($manufacture); 
      $new->p_p_c_id = Manufacturer::where('id',$request->id)->pluck('p_p_c_id')->first();
      
      $new->save(); 
             
            $pimage = Manufacturer::where('id',$request->id)->pluck('image')->first();
            $card = Manufacturer::where('id',$request->id)->pluck('cardimages')->first();
      
           $p = $pimage.','.$projectimage;
           $c = $card.','.$cardimages;
             
        $manufacturer->sub_ward_id = $wardsAssigned;
        $manufacturer->plant_name = $request->plant_name;
        $manufacturer->latitude = $request->latitude;
        $manufacturer->longitude = $request->longitude;
        $manufacturer->address = $request->address;
        $manufacturer->contact_no = $request->phNo;
        $manufacturer->capacity = $request->capacity;
        $manufacturer->remarks = $request->remarks;
        $manufacturer->cement_requirement = $request->cement_requirement;
        $manufacturer->cement_requirement_measurement = $request->cement_required;
        
        $manufacturer->sand_requirement = $request->sand_requirement;
        $manufacturer->aggregates_required = $request->aggregate_requirement;
        $manufacturer->manufacturer_type = $request->type;
        $manufacturer->type = $request->manufacturing_type;
        $manufacturer->moq = $request->moq;
        $manufacturer->total_area = $request->total_area;
        $manufacturer->production_type = $pro;
        $manufacturer->updated_by = Auth::user()->id;
        $manufacturer->quality  = $request->quality;
        $manufacturer->sample  = $request->sample;
        $manufacturer->ggbs  = $request->ggbs;
        $manufacturer->other  = $request->other;
        $manufacturer->exbrand   = $request->exbrand;
        $manufacturer->brandquantity   = $request->brandquantity;
        $manufacturer->interested_in_loan = $request->loaninterest;
        $manufacturer->ggbss = $request->ggbss;
        $manufacturer->chemical = $request->chemical;
        $manufacturer->question = $request->qstn;
        $manufacturer->roadwidth =$request->roadwidth;
        $manufacturer->roadname=$request->roadname;
        $manufacturer->storage=$request->storage;
        $manufacturer->cctv=$request->cctv;
        $manufacturer->image = $p;
        $manufacturer->cardimages = $c;
        $manufacturer->refcustid = $request->cid;
        $manufacturer->silo = $request->silo;

        $manufacturer->save();
       
        Salescontact_Details::where("manu_id",$request->id)->update([
       'manu_id' =>  $manufacturer->id,
       'name' => $request->coName,
       'email' => $request->coEmail,
       'contact' => $request->coContact,
       'contact1' => $request->coContact1

        ]);
       Manager_Deatils::where("manu_id",$request->id)->update([
       'manu_id' =>  $manufacturer->id,
       'name' => $request->cName,
       'email' => $request->cEmail,
       'contact' => $request->cContact,
       'contact1' => $request->cContact1,
       'whatsapp' => $request->mwhatsapp


       ]);
     $check = Mprocurement_Details::where("manu_id",$request->id)->first();
       if(count($check) == 0){

            $proc = new  Mprocurement_Details;
            $proc->manu_id = $request->id;
            $proc->name = $request->prName;
            $proc->email = $request->pEmail;
            $proc->contact = $request->prPhone;
            $proc->contact1 =$request->prPhone1;
            $proc->whatsapp =$request->pwhatsapp;
            $proc->save(); 
        }else{
            $check->manu_id = $request->id;
            $check->name = $request->prName;
            $check->email = $request->pEmail;
            $check->contact = $request->prPhone;
            $check->contact1 =$request->prPhone1;
            $check->whatsapp =$request->pwhatsapp;

            $check->save(); 
        }

     

Mowner_Deatils::where("manu_id",$request->id)->update([
       'manu_id' =>  $manufacturer->id,
       'name' => $request->oName,
       'email' => $request->oEmail,
       'contact'=> $request->oContact,
       'contact1'=> $request->oContact1,
       'whatsapp'=> $request->owhatsapp


       ]);

        
        if($request->type == "Blocks"){
            // saving product details
            for($i = 0; $i < count($request->product_id1); $i++){
                $products = ManufacturerProduce::find($request->product_id1[$i]);
                $products->manufacturer_id = $manufacturer->id;
                $products->block_type = $request->blockType[$i];
                $products->block_size = $request->blockSize[$i];
                $products->price = $request->price[$i];
                $products->save();
            }
            for($j = $i; $i < count($request->blockType); $i++){
                $products = new ManufacturerProduce;
                $products->manufacturer_id = $manufacturer->id;
                $products->block_type = $request->blockType[$j];
                $products->block_size = $request->blockSize[$j];
                $products->price = $request->price[$j];
                $products->save();
            }
        }elseif($request->type == "RMC"){
            // saving product details
            for($i = 0; $i < count($request->product_id2); $i++){
                $products = ManufacturerProduce::find($request->product_id2[$i]);
                $products->manufacturer_id = $manufacturer->id;
                $products->block_type = $request->grade[$i];
                $products->price = $request->gradeprice[$i];
                $products->save();
            }
            for($j = $i; $j < count($request->grade); $j++){
                $products = new ManufacturerProduce;
                $products->manufacturer_id = $manufacturer->id;
                $products->block_type = $request->grade[$j];
                $products->price = $request->gradeprice[$j];
                $products->save();
            }
        }elseif($request->type == "Fabricators"){
            // saving product details
            for($i = 0; $i < count($request->product_id3); $i++){
                $products = ManufacturerProduce::find($request->product_id3[$i]);
                $products->manufacturer_id = $manufacturer->id;
                $products->Fabricators_type = $request->fab[$i];
                $products->price = $request->fabprice[$i];
                $products->save();
            }
            for($j = $i; $j < count($request->fab); $j++){
                $products = new ManufacturerProduce;
                $products->manufacturer_id = $manufacturer->id;
                $products->Fabricators_type = $request->fab[$j];
                $products->price = $request->fabprice[$j];
                $products->save();
            }
        }
        
      
    
      if($request->Suppliername){
        $Suppliername =implode(",", $request->Suppliername);
      }else{
        $Suppliername = "";
      }
      if($request->minquan){
        $minquan =implode(",", $request->minquan);
      }else{
        $minquan = "";
      }
   
      if($request->brand1){
        $brand =implode(",", $request->brand1);
      }else{
        $brand = "";
      }


      if($request->quan){
        $quan =implode(",", $request->quan);
      }else{
        $quan = "";
      }
      
      
       if($request->price){
             $s = $request->price;
             array_pop($s);
        $price =implode(",", $s);
      }else{
        $price = "";
      }
     


     if($brand != null){
        
     $brandcheck = CustomerBrands::where('manu_id',$request->id)->first();
      
      if(count($brandcheck) == 0){

     $bransyup = new CustomerBrands;
     $bransyup->manu_id = $request->id;
     $bransyup->user_id = Auth::user()->id;
      $bransyup->Suppliername = $Suppliername;
     $bransyup->minquan = $minquan; 
     $bransyup->brand = $brand;
     $bransyup->price = $price;
     $bransyup->quan = $quan;
     $bransyup->save();
     
      }else{
     
      $brandcheck->user_id = Auth::user()->id;
      $brandcheck->Suppliername = $Suppliername;
      $brandcheck->minquan = $minquan; 
      $brandcheck->brand = $brand;
      $brandcheck->price = $price;
      $brandcheck->quan = $quan;
      $brandcheck->save();

      }
     
     }


        return back()->with('Success','Manufacturer Saved Successfully');
    }
    public function officeemp(request $request){
    if(Auth::user()->group_id == 14 ){
        $dept = [1,2,3,4,6,7,8] ; 
        $ofcemps= User::whereIn('users.department_id',$dept)
                        ->select('users.employeeId','users.id','users.name')
                        ->get();

    }
    else if(Auth::user()->group_id == 1){
        $dept = [1,2,3,4,5,7] ; 
        $ofcemps= User::whereIn('users.department_id',$dept)
                        ->select('users.employeeId','users.id','users.name')
                        ->get();
                       
    }
    else if(Auth::user()->group_id == 22){
        $tl = Tlwards::where('user_id',Auth::user()->id)->pluck('users')->first();
        $userIds = explode(",", $tl);
        $grp = [17,7];
            $ofcemps= User::whereIn('users.id',$userIds)
                        ->whereIn('group_id',$grp)
                        ->select('users.employeeId','users.id','users.name')
                        ->get();
                       
    }
    else{
      $grp = [7,22,17] ; 
      $ofcemps= User::whereIn('users.group_id',$grp)
                        ->where('department_id','!=','10')
                        ->select('users.employeeId','users.id','users.name')
                        ->get();
    }
        return view('ofcemp',['ofcemps'=>$ofcemps]);
    }
    public function officemap(request $request)
    {
      $name = $request->name;
      $id = user::where('name',$name)->pluck('id');
      $login = FieldLogin::where('user_id',$id)->where('logindate',date('Y-m-d'))->pluck('id')->first();
      if($login != null){
        $login = FieldLogin::where('user_id',$id)->where('logindate',date('Y-m-d'))->get();
        
      }
      else{
        $login = "None";
      }
      return view('officemap',['name'=>$name,'login'=>$login]);
    }
    public function atreject(request $request){
        FieldLogin::where('id',$request->id)->update([
            'logintime' => " ",
            'logindate' => " ",
            'status'=> "rejected"
        ]);
        return response()->json(['message'=>'Rejected']);
    }
    public function atapprove(request $request){
        FieldLogin::where('id',$request->id)->update([
            'status'=> "approved"
        ]);
        return response()->json(['message'=>'Approved']);
    }
    public function breakreport(){
        $date= date('Y-m-d');
       $time =  BreakTime::where('created_at','LIKE',$date.'%')->get();
       
    }
   public function holidays(){
    return view('holidays');
   }
   public function getquotation(Request $request){
   
    $states = state::all();
                if($request->quot == "Project" && !$request->category){
                     $enquiries = Requirement::where('requirements.project_id',$request->id)->where('requirements.status',"Enquiry Confirmed")
                     ->get();
                     $id = $request->id;
                     $manu_id = "";
                     $manu = "";
                }
                else if($request->quot == "Project" && $request->category){
                     $enquiries = Requirement::where('requirements.project_id',$request->id)
                     ->where('requirements.status',"Enquiry Confirmed")
                     ->where('main_category',$request->category)
                     ->get();
                     $id = $request->id;
                     $manu_id = "";
                     $manu = "";
                } 
                else if($request->quot == "Manufacturer" && !$request->category){
                   $enquiries = Requirement::where('requirements.manu_id',$request->id)->where('requirements.status',"Enquiry Confirmed")
                     ->get();
                     $manu_id = $request->id;
                     $manu = Manufacturer::where('id',$request->id)->pluck('manufacturer_type')->first();
                     $id = "";
                }
                else if($request->quot == "Manufacturer" && $request->category){
                   $enquiries = Requirement::where('requirements.manu_id',$request->id)
                   ->where('requirements.status',"Enquiry Confirmed")
                   ->where('main_category',$request->category)
                   ->get();
                     $manu_id = $request->id;
                     $manu = Manufacturer::where('id',$request->id)->pluck('manufacturer_type')->first();
                     $id = "";
                }
                else if($request->quotid){
                    $qid = Quotation::where('quotation_id',$request->quotid)->first();      
                   
                    $enquiries = Requirement::where('requirements.id',$qid->req_id)
                   ->where('requirements.status',"Enquiry Confirmed") 
                   ->get();
                   if($qid->project_id == null){
                    $id = "";
                    $manu_id = $qid->manu_id;
                    $manu = Manufacturer::where('id',$qid->manu_id)->pluck('manufacturer_type')->first();
                   }
                   else{
                    $id = $qid->project_id;
                    $manu_id = "";
                    $manu = "";
                   }    
                }
                else{
                    $enquiries = "";
                    $manu_id = "";
                     $id = "";
                     $manu = "";

                }
        $categories = Category::all();

        $quotations = Quotation::all();
        return view('/quotation',['enquiries'=>$enquiries,'quotations'=>$quotations,'categories'=>$categories,'id'=>$id,'manu_id'=>$manu_id,'manu'=>$manu,'states'=>$states]);
    }
    public function generatequotation(Request $request){
        $cgst = round($request->cgst,2);
        $sgst = round($request->sgst,2);
        $igst = round($request->igst,2);
    
        $year = date('Y');
        $country_code = Country::pluck('country_code')->first();
        $zone = Zone::pluck('zone_number')->first();
        $check = Quotation::where('req_id',$request->id)->first();
        if(count($check) == 0){
           $quot = new Quotation;
           $quot->quotation_id =  "MH_".$country_code."_".$zone."_".$year."_Q".$request->id; 
           $quot->req_id =$request->id;
           $quot->manu_id = $request->manu_id;
           $quot->project_id = $request->pid;
           $quot->quantity = $request->quantity;
           $quot->unitprice = $request->price;
           $quot->pricewithoutgst = $request->withoutgst;
           $quot->totalamount =$request->display;
           $quot->cgst  = $cgst;
           $quot->sgst  = $sgst;
           $quot->igst = $igst;
           $quot->totaltax  = $request->totaltax;
           $quot->amountwithgst  = $request->withgst;
           $quot->unit = $request->unit;
           $quot->amount_word  = $request->dtow1; 
           $quot->tax_word  = $request->dtow2;
           $quot->gstamount_word  = $request->dtow3;
           $quot->description = $request->description;
           $quot->shipaddress  = $request->ship;
           $quot->billaddress   = $request->bill;
           $quot->cgstpercent = $request->cgstpercent;
           $quot->sgstpercent = $request->sgstpercent;
           $quot->igstpercent = $request->igstpercent;
           $quot->gstpercent =$request->gstpercent;
           $quot->save();
      }
      else{
          $check->quantity = $request->quantity;
           $check->unitprice = $request->price;
           $check->pricewithoutgst = $request->withoutgst;
           $check->totalamount =$request->display;
           $check->cgst  = $cgst;
           $check->sgst  = $sgst;
           $check->totaltax  = $request->totaltax;
           $check->amountwithgst  = $request->withgst;
           $check->amount_word  = $request->dtow1; 
           $check->tax_word  = $request->dtow2;
           $check->gstamount_word  = $request->dtow3;
           $check->description = $request->description;
           $check->shipaddress  = $request->ship;
           $check->billaddress   = $request->bill;
           $check->save();
      }
        $enquiries = Requirement::where('id',$request->id)->update([
                'quotation'=> "generated"
        ]);
           return back();
    }
    public function getgstvalue(Request $request){
       
        $gstvalue = Gst::where('category',$request->name)->where('state',$request->state)->get();
        $array = [];
        $id = $request->x;
        array_push($array,['gstvalue'=>$gstvalue,'id'=>$id]);
        return response()->json($array);
    }
    public function deletemanuProject(request $request){
         
       $d =  Manufacturer::where('id',$request->projectId)->update(['blockremark'=>$request->remark,'user_id'=>Auth::user()->id]);

        Manufacturer::where('id',$request->projectId)->delete();
        return back() ;
    }

  public function lockmanu(request $request){

  
         
       $d =  Manufacturer::where('id',$request->projectId)->update(['lockremark'=>$request->remark,'user_id'=>Auth::user()->id,'templock'=>1]);

        // Manufacturer::where('id',$request->projectId)->delete();
        return back()->with('sucuss','Manufacturer Locked') ;
    }




    public function logintime(Request $request)
    {
        if($request->remark != null){
                $remark = $request->remark;
        }
        else{
            $remark = null;
        }
       $id = user::where('id',Auth::user()->id)->pluck('id')->first();
       $check = FieldLogin::where('user_id',Auth::user()->id)->where('logindate',date('Y-m-d'))->get(); 
       $lat = $request->latitude;
       $lon = $request->longitude;
       $address = $request->address; 
       $new = User::where('timefrom',"08:30")->pluck('employeeId');
       $use = User::whereIn('employeeId',$new)->pluck("id")->toArray();
       if(in_array(Auth::user()->id, $use)){
                $start = "08:30";
                $now = date('H:i A');
        }
        else{
             $start = "08:30";
             $now = date('H:i A');
        }
        if($now > $start && count($check)== 0 && $remark == null){         
            $text = " <form action='emplate' method='POST'> <input type='hidden' name='_token' value='".Session::token()."'> <textarea required style='resize:none;'  name='remark' placeholder='Reason For Late Login..' class='form-control' type='text'></textarea><br><center><button type='submit' class='btn btn-success' >Submit</button></center></form>";
            
                
            return back()->with('Latelogin',$text); 
        
            }
        else
        {
                    if(count($check)== 0){
                        $field = new FieldLogin;
                        $field->user_id = $id;
                        $field->logindate = date('Y-m-d');
                        $field->logintime = date(' H:i A');
                        $field->remark = $remark;
                        $field->latitude = null;
                        $field->longitude =null ;
                        $field->address = null;
                        $field->tlapproval = "Pending";
                        $field->adminapproval = "Pending";
                        $field->status = "Pending";
                        $field->hrapproval = "Pending";
                        $field->logout_remark = "";
                        $field->save();


                        $text = "You Have Logged In Successfully!..";
                        return back()->with('empSuccess',$text);
                    }
                    else{
                        return back();
                    }
            }
    }
}
