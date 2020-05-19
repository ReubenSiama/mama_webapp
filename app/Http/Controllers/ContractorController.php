<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Collection;
use App\ContractorDetails;
use App\ProjectDetails;
use App\ProcurementDetails;
use App\SiteEngineerDetails;
use App\OwnerDetails;
use App\ConsultantDetails;
use App\RoomType;
use DB;
use Auth;
use App\training;
use App\Message;
use App\Ward;
use App\SubWard;

date_default_timezone_set("Asia/Kolkata");
class ContractorController extends Controller
{
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

    public function getUpdates()
    {
      if(Auth::user()->employeeId == "MH398"){
        return redirect('contractorDetails?page=1');
      }elseif(Auth::user()->employeeId == "MH401"){
        return redirect('contractorDetails?page=2');
      }elseif(Auth::user()->employeeId == "MH296"){
        return redirect('contractorDetails?page=3');
      }elseif(Auth::user()->employeeId == "MH390"){
        return redirect('contractorDetails?page=4');
      }else{
        return redirect('contractorDetails');
      }
    }
    public function getWhatYouWant()
    {
      $projectIds = OwnerDetails::where('owner_name','!=',null)->pluck('project_id');
      $projects = ProjectDetails::whereIn('project_id',$projectIds)->get();
      return view('owners',['projects'=>$projects]);
    }
    public function getContractorDetails()
    {
      $projectIds = OwnerDetails::whereNotNull('owner_contact_no')->orderby('owner_contact_no')->pluck('project_id');
      if(Auth::user()->employeeId == "MH296" || Auth::user()->employeeId == "MH390" || Auth::user()->employeeId == "MH404"){
        $projects = ProjectDetails::where('project_status',"Plastering")->paginate(30);
      }else{
        $projects = ProjectDetails::where('project_status',"Roofing")->paginate(30);
      }
    	return view('contractor',['projects'=>$projects]);
    }
   	public function getProjects(Request $request)
   	{
   		$projectIds = ContractorDetails::where('contractor_contact_no',$request->phone)->pluck('project_id');
   		$projects = ProjectDetails::whereIn('project_id',$projectIds)->get();
   		$tab = "";
   		if(count($projects) != 0){
	   		foreach($projects as $project){
	   			$tab .= "<tr><td>".$project->contractordetails->contractor_name."</td>
	   						<td>".$project->contractordetails->contractor_contact_no."</td>
	   						<td>".$project->contractordetails->contractor_email."</td>
	   						<td>".$project->siteaddress->address."</td>
	   						<td>".$project->budget." Cr. </td>
	   						<td>".$project->project_size." Sq.m</td>
	   						<td>".$project->project_status."</td>
	   						<td>".$project->quality."</td>
	   						<td><a target='_blank' href='https://mamahome360.com/webapp/ameditProject?projectId=".$project->project_id."' class='btn btn-primary btn-sm'>Edit</a></td></tr>";
	   		}
   		}else{
   			$tab .= "<tr style='background-color:orange; color:white;'><td colspan='5'><center>No records found</center></td></tr>";
   		}
   		return response()->json($tab);
   	}
   	public function getNoOfProjects(Request $request)
   	{
      if($request->phone){
        $projectIds = new Collection;
        $conName = ContractorDetails::where('contractor_contact_no',$request->phone)->pluck('project_id')->first();
        $projectIds = $projectIds->merge($conName);
        $procurement=ProcurementDetails::where('procurement_contact_no',$request->phone)->pluck('project_id')->first();
        $projectIds = $projectIds->merge($procurement);
        $consultant=ConsultantDetails::where('consultant_contact_no',$request->phone)->pluck('project_id')->first();
        $projectIds = $projectIds->merge($consultant);
        $siteEngineer=SiteEngineerDetails::where('site_engineer_contact_no',$request->phone)->pluck('project_id')->first();
        $projectIds = $projectIds->merge($siteEngineer);
        $owner=OwnerDetails::where('owner_contact_no',$request->phone)->pluck('project_id')->first();
        $projectIds = $projectIds->merge($owner);
        $projects = array();
        $projectIds = ContractorDetails::where('contractor_contact_no',$request->phone)->pluck('project_id');
        $projects[$request->phone] = ProjectDetails::whereIn('project_id',$projectIds)->count();
      }else{
        $contractors = ContractorDetails::groupby('contractor_contact_no')->pluck('contractor_contact_no');
        $conName = ContractorDetails::whereIn('contractor_contact_no',$contractors)->get();
        $projects = array();
        foreach($conName as $contractor){
          $projectIds = ContractorDetails::where('contractor_contact_no',$contractor->contractor_contact_no)->pluck('project_id');
          $projects[$contractor->contractor_contact_no] = ProjectDetails::whereIn('project_id',$projectIds)->count();
        }
      }
   		return view('contractorProjects',['projects'=>$projects,'conName'=>$conName]);
   	}

public function getNoOfProjects1(Request $request)
    {
  $wards = Ward::orderby('ward_name','ASC')->get();
     if($request->subward){
        if($request->ward == "All"){
                $subwards = SubWard::pluck('id');
            }else{
                $subwards = SubWard::where('ward_id',$request->ward)->pluck('id');
            }

        $contractors = ContractorDetails::groupby('contractor_contact_no')->pluck('contractor_contact_no');
        $conName = ContractorDetails::whereIn('contractor_contact_no',$contractors)->paginate(20);
        $projects = array();  
  
        foreach($conName as $contractor){
          $projectIds = ContractorDetails::where('contractor_contact_no',$contractor->contractor_contact_no)->pluck('project_id');
          $sub = ProjectDetails::whereIn('project_id',$projectIds)->whereIn('sub_ward_id',$subwards)->pluck('sub_ward_id');
         
          $projects[$contractor->contractor_contact_no] = ProjectDetails::whereIn('project_id',$projectIds)->whereIn('sub_ward_id',$sub)->pluck('project_id');
        }
       
      }
        else{
        $contractors = ContractorDetails::groupby('contractor_contact_no')->pluck('contractor_contact_no');
        $conName = ContractorDetails::whereIn('contractor_contact_no',$contractors)->paginate(20);
        $projects = array();

        foreach($conName as $contractor){
          $projectIds = ContractorDetails::where('contractor_contact_no',$contractor->contractor_contact_no)->pluck('project_id');
          $projects[$contractor->contractor_contact_no] = ProjectDetails::whereIn('project_id',$projectIds)->pluck('project_id');
          
        }
      }
      return view('underperson',['projects'=>$projects,'conName'=>$conName,'wards'=>$wards]);
    }






  public function viewProjects(Request $request)
    {
     
      
    
      $table = "<tr> <td colspan='8'><center>Material Estimation prices May Vary According To Market Price</center> </td></center></tr>
     
      <tr><th>Category</th><th>Total Required</th><th>Total Amount</th></tr>";
      $projectIds = ContractorDetails::where('contractor_contact_no',$request->no)->pluck('project_id');
      $procurement = ProcurementDetails::where('procurement_contact_no',$request->no)->pluck('project_id');
      $projectIds = $projectIds->merge($procurement);
      $projects = ProjectDetails::whereIn('project_id',$projectIds)->get();
      $roomTypes = RoomType::whereIn('project_id',$projectIds)->get();
      foreach($roomTypes as $type){
        $size = ProjectDetails::where('project_id',$type->project_id)->pluck('project_size')->first();
        $cases = $type->room_type;
        switch ($cases) {
          case '1BHK':
              $floors=1;
              $kitchen=1;
              $bedroom=1;
              $hall=1;
              $bathroom=1;
              $flatsfloors=1;
            break;
          case '2BHK':
              $floors=1;
              $kitchen=1;
              $bedroom=2;
              $hall=1;
              $bathroom=1;
              $flatsfloors=1;
            break;
          case '3BHK':
              $floors=1;
              $kitchen=1;
              $bedroom=3;
              $hall=1;
              $bathroom=1;
              $flatsfloors=1;
            break;
          default:
              $floors=1;
              $kitchen=1;
              $bedroom=1;
              $hall=1;
              $bathroom=1;
              $flatsfloors=1;
            break;
        }
        
      }
      $steel = array();
      $cement = array();

      $plumbing = array();
      $doors = array();
      $flooring = array();
      $sand = array();
      $aggregates = array();
      $blocks = array();
      $electrical = array();
      $bathroom = array();
      $wood = array();
      $paints = array();
      $wardk = array();
      $rails = array();
      $glass = array();
     
      $totalPlumbing =0;
      $totaldoors=0;
      $totalflooring=0;
      $totalcement = 0;
      $totalsteel = 0;
      $totalsand = 0;
      $totalaggregates = 0;
      $totalblocks = 0;
      $totalelectrical = 0;
      $totalbathroom = 0;
      $totalwood = 0;
      $totalpaints = 0;
      $totalward = 0;
      $totalrails = 0;
       $totalglass = 0;
      $i = 0;
      foreach($projects as $project){
        $Total_Flats = $project->project_type;
        $Total_Area =  $project->project_size;
        $stage = $project->project_status;
        switch ($stage) {
          case 'Planning':
             
              $plumbingRequirement = 100;
              $doorsRequirement = 100;
              $flooringRequirement = 100;
              $cementRequirement = 100;
              $steelRequirement  = 100;
              $sandRequirement = 100;
              $aggregatesRequirement = 100;
              $blocksRequirement = 100;
              $electricalRequirement = 100;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;
              // calculation Part
             
              $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100); 
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
              $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
              $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
              $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
              $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
              $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
              $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
              $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
              $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
              $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);
              $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);
              $glass[$i] = ((25 *  $Total_Area)/25)* ($glassReqirement/100);
           
            break;
           
          case 'Digging':
             
              $plumbingRequirement = 100;
              $doorsRequirement = 100;
              $flooringRequirement = 100;
              $cementRequirement = 100;
              $steelRequirement  = 100;
              $sandRequirement = 100;
              $aggregatesRequirement = 100;
              $blocksRequirement = 100;
              $electricalRequirement = 100;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;

          // calculation Part
             
              $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100); 
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
               $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
                $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
                $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
                $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
                $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
                $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
                $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
                $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
                $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);
                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);
                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);

             break;
               case 'Foundation':
             
              $plumbingRequirement = 100;
              $doorsRequirement = 100;
              $flooringRequirement = 100;
               $cementRequirement =85;
               $steelRequirement  = 70;
              $sandRequirement = 90;
              $aggregatesRequirement = 80;
              $blocksRequirement = 100;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $electricalRequirement = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;

          // calculation Part
              
              $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100); 
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
               $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
                $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
                $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
                $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);

                $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
                $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
                $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
                $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
                $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);
                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);
                $glass[$i] = ((25 *  $Total_Area)/25)* ($glassReqirement/100);

              break;
          case 'Pillars':
             
              $plumbingRequirement = 100;
              $doorsRequirement = 100;
              $flooringRequirement = 100;
               $cementRequirement = 70;
               $steelRequirement  = 35;
              $sandRequirement = 70;
              $aggregatesRequirement = 50;
              $blocksRequirement = 100;
              $electricalRequirement = 100;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;
              
              $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100);
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
               $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
                $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
                $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
                $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
                $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);

                $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
                $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
                $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
                $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);
                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);
                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);
              break;
          case 'Walls':
             
              $plumbingRequirement = 100;
              $doorsRequirement = 100;
              $flooringRequirement = 100;
               $cementRequirement = 55;
               $steelRequirement  = 35;
              $sandRequirement = 50;
               $aggregatesRequirement = 50;
              $blocksRequirement = 100;
              $electricalRequirement = 100;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;

   // calculation Part
             
               $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100); 
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
               $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
                $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
                $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
                $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);

                $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
                $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
                $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
                $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
                $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);
                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);
                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);
                break;
          case 'Roofing':
            
              $plumbingRequirement = 100;
              $doorsRequirement = 100;
              $flooringRequirement = 100;
               $cementRequirement = 25;
               $steelRequirement  = 35;
              $sandRequirement = 35;
               $aggregatesRequirement = 50;
              $blocksRequirement = 0;
              $electricalRequirement = 100;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;
// calculation Part
             
              $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100); 
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
               $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
                $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
                $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
                $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
                $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
                $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
                $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
                $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
                $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);
                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);
                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);
                  break;
          case 'Electrical':
              
              $plumbingRequirement = 100;
              $doorsRequirement = 100;
              $flooringRequirement = 100;
               $cementRequirement = 25;
               $steelRequirement  = 0;
              $sandRequirement = 35;
               $aggregatesRequirement = 0;
              $blocksRequirement = 0;
              $electricalRequirement = 100;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;
   // calculation Part
             
               $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100);
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
               $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
                $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
                $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
                $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
                $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
                $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
                $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
                $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
                $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);
                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);
                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);



break;
          case 'Plumbing':
            
              $plumbingRequirement = 100;
              $doorsRequirement = 100;
              $flooringRequirement = 100;
               $cementRequirement = 25;
               $steelRequirement  = 0;
              $sandRequirement = 35;
               $aggregatesRequirement = 0;
              $blocksRequirement = 0;
              $electricalRequirement = 0;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;
  // calculation Part
             
              $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100); 
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
               $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
                $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
                $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
                $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
                $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
                $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
                $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
                $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
                $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);
                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);
                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);

 break;
          case 'Plastering':
             
              $plumbingRequirement = 0;
              $doorsRequirement = 100;
              $flooringRequirement = 100;
               $cementRequirement = 10;
               $steelRequirement  = 0;
              $sandRequirement = 10;
               $aggregatesRequirement = 0;
              $blocksRequirement = 0;
              $electricalRequirement = 0;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;
// calculation Part
             
              $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100); 
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
               $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
                $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
                $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
                $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
                $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
                $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
                $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
                $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
                $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);
                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);

                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);

break;
          case 'Flooring':
             
              $plumbingRequirement = 0;
              $doorsRequirement = 100;
              $flooringRequirement = 100;
               $cementRequirement = 5;
               $steelRequirement  = 0;
              $sandRequirement = 0;
               $aggregatesRequirement = 0;
              $blocksRequirement = 0;
              $electricalRequirement = 0;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;
 // calculation Part
             $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100);
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
               $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
                $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
                $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
                $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
                $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
                $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
                $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
                $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
                $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);

                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);
                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);

 break;
          case 'Carpentry':
             
              $plumbingRequirement = 0;
              $doorsRequirement = 100;
              $flooringRequirement = 100;
               $cementRequirement = 0;
               $steelRequirement  = 0;
              $sandRequirement = 0;
               $aggregatesRequirement = 0;
              $blocksRequirement = 0;
              $electricalRequirement = 0;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;
 // calculation Part
           
              $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100); 
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
               $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
                $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
                $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
                $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
                $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
                $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
                $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
                $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
                $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);
                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);
                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);
              
// dd($plumbing[$i] );

            break;
          case 'Paintings':
              
              $plumbingRequirement = 0;
              $doorsRequirement = 0;
              $flooringRequirement = 100;
              $cementRequirement = 0;
              $steelRequirement  = 0;
              $sandRequirement = 0;
              $aggregatesRequirement = 0;
              $blocksRequirement = 0;
              $woodRequirement  = 100;
              $electricalRequirement = 0;
              $bathroomRequirement = 100;
              $paintsReqirement = 100;
              $wardReqirement = 100;
              $railsReqirement = 100;
              $glassReqirement = 100;
// calculation Part
              
              $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100); 
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
              $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
              $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
              $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
              $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
              $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
              $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
              $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
              $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
              $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);

                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);
                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);

break;
          case 'Fixtures':
              
              $plumbingRequirement = 0;
              $doorsRequirement = 0;
              $flooringRequirement = 0;
              $cementRequirement = 0;
              $steelRequirement  = 0;
              $sandRequirement = 0;
              $aggregatesRequirement = 0;
              $blocksRequirement = 0;
              $electricalRequirement = 0;
              $bathroomRequirement = 100;
              $woodRequirement  = 100;
              $paintsReqirement = 100;
              $wardReqirement = 0;
              $railsReqirement = 100;
              $glassReqirement = 0;
 // calculation Part
           
              $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100); 
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
              $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
              $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
              $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
              $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
              $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
              $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
              $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
              $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
              $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);
                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);

                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);

 break;
          case 'Completion':
            
              $plumbingRequirement = 0;
              $doorsRequirement = 0;
              $flooringRequirement = 0;
              $cementRequirement = 0;
               $steelRequirement  = 0;
              $sandRequirement = 0;
               $aggregatesRequirement = 0;
              $blocksRequirement = 0;

              $electricalRequirement = 0;
              $bathroomRequirement = 0;
              $woodRequirement  = 0;
              $paintsReqirement = 0;
              $wardReqirement = 0;
              $railsReqirement = 100;
              $glassReqirement = 0;



 // calculation Part
             
              $plumbing[$i] = ((50*$Total_Area)/50) * ($plumbingRequirement/100);
              $doors[$i] = ((350 *$Total_Area )/350) *($doorsRequirement/100);
              $flooring [$i] = ((0.8 *$Total_Area)/0.8) *($flooringRequirement/100);
              $cement[$i] = ((15 * $Total_Area)/50) * ($cementRequirement/100);
               $steel[$i] = ((4 * $Total_Area)/1000) *($steelRequirement/100);
                $sand[$i] = ((1.2 * $Total_Area)/23.35) * ($sandRequirement/100);
                $aggregates[$i] = ((1.35 * $Total_Area)/23.35) *($aggregatesRequirement/100);
                $blocks[$i] = ((4.167 * $Total_Area)/4.16) * ($blocksRequirement/100);
                $electrical[$i] = ((84 * $Total_Area)/84)*($electricalRequirement/100);
                $bathroom[$i] = ((70 * $Total_Area)/70) * ($bathroomRequirement/100);
                $wood[$i] = ((120 * $Total_Area)/120) * ($woodRequirement/100);
                $paints[$i] = ((55 * $Total_Area)/55) *($paintsReqirement/100);
              $wardk[$i] = ((60 * $Total_Area)/60) *($wardReqirement/100);

                $rails[$i] = ((50 * $Total_Area)/50) * ($railsReqirement/100);

                $glass[$i] = ((25 * $Total_Area)/25)* ($glassReqirement/100);
 break;
          default:
            # code...
            break;
        }

     
        if( ($cement[$i] || $plumbing[$i] ||  $doors[$i] || $flooring[$i] || $sand[$i] || $aggregates[$i] || $blocks[$i] || $electrical[$i] || $bathroom[$i] || $wood[$i] || $paints[$i] || $wardk[$i] || $rails[$i] || $glass[$i] ) > $i){
         

         $totalPlumbing += $plumbing[$i];
         $totaldoors +=  $doors[$i];
         $totalsteel += $steel[$i];
         $totalflooring +=  $flooring[$i];
         $totalcement += $cement[$i];
         $totalsand += $sand[$i];
         $totalaggregates += $aggregates[$i]; 
         $totalblocks += $blocks[$i]; 
         $totalelectrical += $electrical[$i]; 
         $totalbathroom += $bathroom[$i]; 
          $totalwood += $wood[$i];
          $totalpaints += $paints[$i]; 
           $totalward += $wardk[$i];
           $totalrails += $rails[$i];
           $totalglass += $glass[$i]; 
       }
        $i++;
      }
     

               $table .="<tr><td>Cement</td>
                 <td>".number_format($totalcement)." (Bags)</td>
                    
                <td>" .number_format(($cement1 = ($totalcement) * 270))."</td>
                     </tr>";

                 $table .="<tr><td>Steel</td>

                 <td>".number_format($totalsteel)." (Ton)</td>
                      
                 <td>".(number_format($steel1 = ($totalsteel) * 50000))."</td>
                      </tr>";


                 $table .="<tr><td>Sand</td>
                               
                <td>".number_format($totalsand)." (Ton)</td>
                 
                 <td>".(number_format($sand1 = ($totalsand) * 950))."</td></tr>";
                 
               

                $table .="<tr><td>Aggregates</td>
                <td>".number_format($totalaggregates)." (Ton)</td>
                   
                <td>".(number_format($agr=($totalaggregates) * 750))."</td>
               </tr>";

                  $table .="<tr><td>Electrical</td>
                <td>".number_format($totalelectrical)." (Sqft)</td>
                    
                <td>".(number_format($ele=($totalelectrical) * 84))."</td>
                     
                     
                </tr>";


                $table .="<tr><td>Blocks and Bricks</td>
                <td>".number_format($totalblocks)." (No.)</td>
                     
                <td>".(number_format($bl=($totalblocks) * 28))."</td>
                     </tr>";

        
               $table .="<tr><td>Plumbing</td>
                <td>".number_format($totalPlumbing)." (Sqft)</td>
                    
                <td>".(number_format($pl =($totalPlumbing) * 50))."</td>
                     </tr>";

                $table .="<tr><td>Doors and windows</td>
                <td>".number_format($totaldoors)." (Sqft)</td>
                    
                <td>".(number_format($door=($totaldoors) * 350))."</td>
                    </tr>";

                $table .="<tr><td>Flooring</td>
                <td>".number_format($totalflooring)." (Sqft)</td>
                   
                <td>".(number_format($floor=($totalflooring) * 45))."</td>
                  
                   </tr>";
                   $table .="<tr><td>Bathroom and sanitary</td>
                <td>".number_format($totalbathroom)." (Sqft)</td>
                   
                <td>".(number_format($bathroom=($totalbathroom) * 70))."</td>
                  
                   </tr>";

                    $table .="<tr><td>Wood and Adhesive</td>
                <td>".number_format($totalwood)." (Sqft)</td>
                   
                <td>".(number_format($wood=($totalwood) * 120))."</td>
                  
                   </tr>";

                    $table .="<tr><td>Paints</td>
                <td>".number_format($totalpaints)." (Sqft)</td>
                   
                <td>".(number_format($paints=($totalpaints) * 120))."</td>
                  
                   </tr>";
                     $table .="<tr><td>Wardrobes and kitchen </td>
                <td>".number_format($totalward)." (Sqft)</td>
                   
                <td>".(number_format($ward=($totalward) * 60))."</td>
                  
                   </tr>";
                     $table .="<tr><td>HandRails</td>
                <td>".number_format($totalrails)." (Sqft)</td>
                   
                <td>".(number_format($rail=($totalrails) * 50))."</td>
                  
                   </tr>";
          $table .="<tr><td>Glasses and facades</td>
                <td>".number_format($totalglass)." (Sqft)</td>
                   
                <td>".(number_format($glas=($totalglass) * 50))."</td>
                  
                   </tr>";
       

       $total = number_format($cement1+$steel1+$floor+$door+$pl+$bl+$ele+$agr+$sand1+$bathroom+$wood+$paints+$ward+$rail+$glas);
       $table .="<tr><th></th>
                <th>Total Approximate Material Cost</th>
                   
                <th>".  $total ."</th>
                  
                   </tr>";
                 
      return view('detailProjects',['projects'=>$projects->toArray(),'table'=>$table,'total'=>$total]);
    }
}
