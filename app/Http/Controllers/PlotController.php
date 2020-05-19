<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use App\Mail\registration;
use App\Department;
use App\User;
use Session;
use App\Group;
use App\WaradReport;
use App\Builder;
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
use App\EmployeeDetails;
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
use App\Plots;
use App\PlotsCustomers;
date_default_timezone_set("Asia/Kolkata");
class PlotController extends Controller
{
    public function Plots(Request $request)
    {


       
      
        return  view ('plots_projects.plots');
    }

    public function savePlots(Request $request)
    {   
      
        $subward = WardAssignment::where('user_id',Auth::user()->id)->pluck('subward_id')->first();

        
        $i = 0;
        $projectimage = '';
            if($request->pImage){
                foreach($request->pImage as $pimage){

                       $imageName3 = $pimage;
                     $imageFileName = $i.time() . '.' . $imageName3->getClientOriginalExtension();
                     $s3 = \Storage::disk('azure');
                     $filePath = '/plotimages/' . $imageFileName;
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
     
///////////////fill plots data
            $fill = New Plots;
            $fill->project_name = $request->pName;
            $fill->sub_ward_id = $subward;
            $fill->longitude = $request->longitude;
            $fill->latitude = $request->latitude;
            $fill->road_name = $request->rName;
            $fill->road_width = $request->rWidth;
            $fill->Address = $request->address;
            $fill->interested_in_bank_loans= $request->loaninterest;
            $fill->interested_in_jv= $request->jvinterest;
            $fill->architects_required= $request->architects_required;
            $fill->length = $request->length;
            $fill->breadth = $request->breadth;
            $fill->total_plot_size = $request->total_plot_size;
            $fill->listing_engineer_id =$request->user_id;
            $fill->image=$projectimage;
            $fill->budget = $request->budget;
            $fill->Budget_type = implode(",",$request->budgetType);
            $fill->remarks = $request->remarks;
            $fill ->project_type=implode(",", $request->type);
            $fill->save();

 ///////////fill customers
            $fill_customer = New PlotsCustomers;
            $fill_customer ->plot_id =$fill->id;
            $fill_customer->plot_owner_name = $request->oName;
            $fill_customer->owner_email = $request->oEmail;
            $fill_customer->owner_contact_no = $request->oContact;
            $fill_customer->builder_name = $request->bName;
            $fill_customer->builder_email = $request->bEmail;
            $fill_customer->builder_contact_no = $request->bPhone;
            $fill_customer->save();
            return view('plots_projects.plots');
         
    }

    public function plotsDailyslots (request $request){
                
       
        $date = date('Y-m-d');
        $groupid = [6,7,22,23,17,11];
        $users = User::whereIn('group_id',$groupid)
                    ->where('department_id','!=',10)
                    ->leftjoin('ward_assignments','users.id','ward_assignments.user_id')
                    ->leftjoin('sub_wards','ward_assignments.subward_id','sub_wards.id')
                    ->select('users.*','sub_wards.sub_ward_name')
                    ->get();
      
              if($request->list =="ALL" && $request->fromdate && $request->todate){
                      $from =$request->fromdate;
                      $to = $request->todate;
                      if($from == $to){
                       $projects = Plots::where('created_at','like',$from.'%')
                             ->where('created_at','LIKE',$to."%")
                             ->get();
                                    }
                  else{
                  $projects = Plots::where('created_at','>',$request->fromdate)
                              ->where('created_at','<=',$request->todate)
                             ->get(); 
                  }
              }
              
              elseif($request->list !="ALL" && $request->fromdate && $request->todate){
                      $from =$request->fromdate;
                      $to = $request->todate;
                      if($from == $to){
                       $projects = Plots::where('created_at','like',$from.'%')
                             ->where('created_at','LIKE',$to."%")
                             ->where('listing_engineer_id',$request->list)
                             ->get();
                       
                                      }
                  else{
                      $projects = Plots::where('created_at','>',$request->fromdate)
                              ->where('created_at','<=',$request->todate)
                             ->where('listing_engineer_id',$request->list)
                             ->get();     
                  }
              }else{
                
                   $projects =Plots::where('created_at','like',$date.'%')
                  ->get();
              }



        
              


$le = DB::table('users')->whereIn('group_id',$groupid)->where('department_id','!=',10)->get();
            
        $projcount = count($projects); 
        
        return view('plots_projects.plots_dailyslots', ['date' => $date,'users'=>$users, 'projcount' => $projcount, 'projects' => $projects,'le'=>$le
        ]);
    }        

    public function viewplotdetails (Request $request){
        
            $plot_id = $request->plot_id;
            
            $plot_details = Plots::where('plot_id',$plot_id)->get();
            $sub = Plots::where('plot_id',$plot_id)->pluck('sub_ward_id');
            $sub_ward = Subward::where('id',$sub)->get();
            $plot_customers = PlotsCustomers::where('plot_id',$plot_id)->get();
            $listed = Plots::where('plot_id',$plot_id)->pluck('listing_engineer_id');
            $listedby = user::where('id',$listed)->get();
            

            return view('plots_projects.viewplotsdetails',['sub_ward'=>$sub_ward,'plot_details'=>$plot_details,'plot_customers'=>$plot_customers,'listedby'=>$listedby]
        );
    }        


}
