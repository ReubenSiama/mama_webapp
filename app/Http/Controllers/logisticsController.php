<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Auth;
use DB;
use App\Ward;
use App\Country;
use App\SubWard;
use App\Zone;
use App\CategoryPrice;
use App\Category;
use App\Requirement;
use App\ProjectDetails;
use App\Department;
use App\loginTime;
use App\User;
use App\Group;
use App\EmployeeDetails;
use App\BankDetails;
use App\Asset;
use App\AssetInfo;
use App\Certificate;
use App\Report;
use App\attendance;
use App\ManufacturerDetail;
use App\KeyResult;
use App\MhInvoice;
use App\Order;
use App\DeliveryDetails;
use App\RoomType;
use App\Message;
use App\training;
use App\Point;
use App\SiteAddress;
use App\OwnerDetails;
use App\Payment;
use App\PaymentDetails;
use App\Deposit;
use App\FieldLogin;


class logisticsController extends Controller
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
    public function dashboard()
    {
        return view('logistics.lcodashboard');
    }
    
    public function report(Request $request)
    {
        if(!$request->date){
            date_default_timezone_set("Asia/Kolkata");
            $today = date('Y-m-d');
            $projectCount = count(ProjectDetails::where('listing_engineer_id',Auth::user()->id)
                            ->where('created_at','like',$today.'%')
                            ->get());
            
            $loginTimes = loginTime::where('user_id',Auth::user()->id)
                            ->where('logindate',$today)->first();
            return view('logistics.myreport',['loginTimes'=>$loginTimes,'projectCount'=>$projectCount]);
        }
        else
        {
            $projectCount = count(ProjectDetails::where('listing_engineer_id',Auth::user()->id)
                            ->where('created_at','like',$request->date.'%')
                            ->get());
            
            $loginTimes = loginTime::where('user_id',Auth::user()->id)
                            ->where('logindate',$request->date)
                            ->first();

            return view('logistics.myreport',['loginTimes'=>$loginTimes,'projectCount'=>$projectCount]);
        }
    }
    
    public function orders(Request $request)
    {
                $ip= \Request::ip();
              $data = \Location::get($ip);
   
                $wow = [];
       $v = DB::table('orders')->where('logistic','!=',NULL)->get();
      
           foreach ($v as $order) {
                $log = explode(",", $order->logistic);   
               
                if(in_array(Auth::user()->id, $log)){

                    array_push($wow,$order->id);
                }


           }
           $view=DB::table('orders')->whereIn('id',$wow)->where('leaccept',NULL)->orWhere('leaccept',1)->paginate('10');

          
        return view('logistics.orders',['view'=>$view]);
            
           
    }
    
    public function showProjectDetails(Request $id)
    {
        $id = $id->id;
        $rec = ProjectDetails::where('project_id',$id)->first();
        $roomtypes = RoomType::where('project_id',$id)->get();
        return view('logistics.projectdetails',['rec' => $rec,'roomtypes'=>$roomtypes]);
    }

    
    public function confirmDelivery(Request $request){
        $requirement = Requirement::where('id',$request->id)->first();
        $project = ProjectDetails::where('project_id',$request->projectId)->first();
        $subward = SubWard::where('id',$project->sub_ward_id)->pluck('sub_ward_name')->first();
        return view('logistics.confirmDelivery',['pageName'=>'Orders','requirement'=>$requirement,'project'=>$project,'subward'=>$subward]);
    }
    public function postconfirmDelivery(Request $request){
        $invoiceCount = count(MhInvoice::all()) + 1;
        $no = sprintf("%04d", $invoiceCount);
        $project = ProjectDetails::where('project_id',$request->projectId)->first();
        $subward = SubWard::where('id',$project->sub_ward_id)->first();
        $ward = Ward::where('id',$subward->ward_id)->first();
        $country = Country::where('id',$ward->country_id)->first();
        $zone = Zone::where('id',$ward->zone_id)->first();
        $invoiceNo = "MH_".$country->country_code."_".$zone->zone_number."_".date('Y')."_".$country->country_code.$no;
        $invoice = new MhInvoice;
        $invoice->project_id = $request->projectId;
        $invoice->requirement_id = $request->requiremntId;
        $invoice->customer_name = $request->customerName;
        $invoice->deliver_location = $request->location;
        $invoice->sub_ward = $request->subward;
        $invoice->invoice_number = $invoiceNo;
        $invoice->amount_received = $request->amount;
        $invoice->receive_date = $request->rDate;
        $invoice->payment_method = $request->paymentMethod;
        $invoice->transactional_details = $request->transactionNo;
        $invoice->save();
        Requirement::where('id',$request->requiremntId)->update(['delivery_status'=>"Delivered"]);
        return redirect('orders');
    }
    public function deliveredorders()
    {
        $rec = Order::where('delivery_boy',Auth::user()->id)->Where('delivery_status','Delivered')->get();
        $countrec = count($rec);
        return view('logistics.deliveredorders',['rec'=>$rec,'countrec'=>$countrec]);
    }
    public function takesignature(Request $request)
    {
        $date = date('Y-m-d');
        $log = FieldLogin::where('user_id',Auth::user()->id)->where('created_at','LIKE',$date.'%')->count();
        $log1 = FieldLogin::where('user_id',Auth::user()->id)->where('logout','!=','NULL')->pluck('logout')->count();
        $deliveryDetails = DeliveryDetails::where('order_id',$request->orderId)->first();
        return view('logistics.takesignature',['log'=>$log,'log1'=>$log1,'deliveryDetails'=>$deliveryDetails]);
    }
    public function saveSignature(Request $request)
    {
        $data = $request->all();
        $png_project = "project_image-".time().".jpg";
        $path = public_path() . "/signatures/" . $png_project;
        $img = $request->sign;
        $img = substr($img, strpos($img, ",")+1);
        $decoded = base64_decode($img);
        $success = file_put_contents($path, $decoded);

        $check = DeliveryDetails::where('order_id',$request->orderId)->first();
          
            $vehicleNo = $request->file('vno');
             $imageFileName1 = "vehicle".time() . '.' . $vehicleNo->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($vehicleNo), 'public');

             $locationPicture = $request->file('lp');
             $imageFileName2 ="loction".time() . '.' . $locationPicture->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName2;
             $s3->put($filePath, file_get_contents($locationPicture), 'public');

             $quality = $request->file('qm');
             $imageFileName3 = "quality".time() . '.' . $quality->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName3;
             $s3->put($filePath, file_get_contents($quality), 'public');



        //$vehicleNo = "vehicle".time().'.'.request()->vno->getClientOriginalExtension();
        //$request->vno->move(public_path('delivery_details'),$vehicleNo);

        //$locationPicture = "loction".time().'.'.request()->lp->getClientOriginalExtension();
        //$request->lp->move(public_path('delivery_details'),$locationPicture);

        //$quality = "quality".time().'.'.request()->qm->getClientOriginalExtension();
        //$request->qm->move(public_path('delivery_details'),$quality);

        $deliveryDetails = new DeliveryDetails;
        $deliveryDetails->order_id = $request->orderId;
        $deliveryDetails->vehicle_no = $imageFileName1;
        $deliveryDetails->location_picture = $imageFileName2;
        $deliveryDetails->quality_of_material = $imageFileName3;
        $deliveryDetails->signature = $request->sign;
        $deliveryDetails->delivery_date = date('Y-m-d h:i:s A');

        $deliveryDetails->save();
        $order = Order::where('id',$request->orderId)->first();
        $order->delivery_status = "Delivered";
        $order->save();

        return back()->with('Success','Payment Received');
    }
   public function payment(Request $request){



             //$signatureName = Auth::user()->id."signature".time().'.'.request()->signature->getClientOriginalExtension();
             //$request->signature->move(public_path('signatures'),$signatureName);

            

             $signatureName = $request->file('qm');
             $imageFileName1 = Auth::user()->id."signature".time() . '.' . $signatureName->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/signatures/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($signatureName), 'public');


             if($request->signature1){
               

             $signatureName1 = $request->file('qm');
             $imageFileName2 = Auth::user()->id."cheque".time() . '.' . $signatureName1->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/signatures/' . $imageFileName2;
             $s3->put($filePath, file_get_contents($signatureName1), 'public');


            //$signatureName1 = Auth::user()->id."cheque".time().'.'.request()->signature1->getClientOriginalExtension();
            //$request->signature1->move(public_path('signatures'),$signatureName1);
             }else{
                $imageFileName2 = "";
             }
             // dd($request->advanceAmount);
             if($request->payment_method){

            $paymode = implode(", ", $request->payment_method);
             }else{
                  $paymode="null";
             }
            
             $pays = Payment::where('order_id',$request->orderid)->first();

               if($pays == NULL){
               $pay = new Payment;

                
                $pay->payment_status = "Payment Received";
                $pay->project_id = $request->project_id;
                $pay->amount = $request->amount;
                $pay->rtgs = $request->rtgs;

                $pay->p_method =  $paymode;
                $pay->log_name = $request->log_name;
                $pay->order_id = $request->orderId;
                $pay->signature=$imageFileName1;
                $pay->signature1=$imageFileName2;
                $pay->c_name = $request->c_name;
                $pay->save();
             }
               else{
                   $pays->project_id = $request->project_id;
                   $pays->p_method = $request-> $paymode;
                   $pays->log_name = $request->log_name;
                   $pays->order_id = $request->orderId;
                   $pays->signature=$imageFileName1;
                    $pays->signature1=$imageFileName2;
                   $pays->c_name = $request->c_name;
                    $pays->payment_status = "Payment Received";
                    $pays->amount = $request->amount;
                    $pay->rtgs = $request->rtgs;

                   $pays->save();
               }
             
        $points = new Point;
        $points->user_id = Auth::user()->id;
        $points->point = 400;
        $points->type = "Add";
        $points->reason = "Receiving payment";
        $points->save();
       return back();




    }
    public function saveDeliveryDetails(Request $request)
    {

    $check = DeliveryDetails::where('order_id',$request->orderId);
     
     if($check != null){

        if(!$request->vid){
            // $vehicleNo = "vehicle".time().'.'.request()->vno->getClientOriginalExtension();
            // $request->vno->move(public_path('delivery_details'),$vehicleNo);
             
             $vehicleNo = $request->file('vno');
             $imageFileName1 = "vehicle".time().time() . '.' . $vehicleNo->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($vehicleNo), 'public');

             $locationPicture = $request->file('lp');
             $imageFileName2 = "loction".time().time() . '.' . $locationPicture->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName2;
             $s3->put($filePath, file_get_contents($locationPicture), 'public');

             $quality = $request->file('qm');
             $imageFileName3 = "quality".time().time() . '.' . $quality->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName3;
             $s3->put($filePath, file_get_contents($quality), 'public');


            // $locationPicture = "loction".time().'.'.request()->lp->getClientOriginalExtension();
            // $request->lp->move(public_path('delivery_details'),$locationPicture);

            // $quality = "quality".time().'.'.request()->qm->getClientOriginalExtension();
            // $request->qm->move(public_path('delivery_details'),$quality);

            $deliveryDetails = new DeliveryDetails;
            $deliveryDetails->order_id = $request->orderId;
            $deliveryDetails->vehicle_no = $imageFileName1;
            $deliveryDetails->location_picture = $imageFileName2;
            $deliveryDetails->quality_of_material = $imageFileName3;
            $deliveryDetails->delivery_date = date('Y-m-d h:i:s A');
            $deliveryDetails->save();
        }else{

        // $vehicleNo = "vehicle".time().'.'.request()->vno->getClientOriginalExtension();
        // $request->vno->move(public_path('delivery_details'),$vehicleNo);
        
        // $locationPicture = "loction".time().'.'.request()->lp->getClientOriginalExtension();
        // $request->lp->move(public_path('delivery_details'),$locationPicture);
        
        // $quality = "quality".time().'.'.request()->qm->getClientOriginalExtension();
        // $request->qm->move(public_path('delivery_details'),$quality);
            $vehicleNo = $request->file('vno');
             $imageFileName1 = "vehicle".time().time() . '.' . $vehicleNo->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($vehicleNo), 'public');

             $locationPicture = $request->file('lp');
             $imageFileName2 = "loction".time().time() . '.' . $locationPicture->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName2;
             $s3->put($filePath, file_get_contents($locationPicture), 'public');

             $quality = $request->file('qm');
             $imageFileName3 = "quality".time().time() . '.' . $quality->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName3;
             $s3->put($filePath, file_get_contents($quality), 'public');
     }
 }

     else

     {
         if(!$request->vid){
            // $vehicleNo = "vehicle".time().'.'.request()->vno->getClientOriginalExtension();
            // $request->vno->move(public_path('delivery_details'),$vehicleNo);

            // $locationPicture = "loction".time().'.'.request()->lp->getClientOriginalExtension();
            // $request->lp->move(public_path('delivery_details'),$locationPicture);

            // $quality = "quality".time().'.'.request()->qm->getClientOriginalExtension();
            // $request->qm->move(public_path('delivery_details'),$quality);
            $vehicleNo = $request->file('vno');
             $imageFileName1 = "vehicle".time().time() . '.' . $vehicleNo->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($vehicleNo), 'public');

             $locationPicture = $request->file('lp');
             $imageFileName2 = "loction".time().time() . '.' . $locationPicture->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName2;
             $s3->put($filePath, file_get_contents($locationPicture), 'public');

             $quality = $request->file('qm');
             $imageFileName3 = "quality".time().time() . '.' . $quality->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName3;
             $s3->put($filePath, file_get_contents($quality), 'public');
            $check->order_id = $request->orderId;
            $check->vehicle_no = $imageFileName1;
            $check->location_picture = $imageFileName2;
            $check->quality_of_material = $imageFileName3;
            $check->delivery_date = date('Y-m-d h:i:s A');
            $check->save();
        }
        else
        {

        // $vehicleNo = "vehicle".time().'.'.request()->vno->getClientOriginalExtension();
        // $request->vno->move(public_path('delivery_details'),$vehicleNo);
        
        // $locationPicture = "loction".time().'.'.request()->lp->getClientOriginalExtension();
        // $request->lp->move(public_path('delivery_details'),$locationPicture);
        
        // $quality = "quality".time().'.'.request()->qm->getClientOriginalExtension();
        // $request->qm->move(public_path('delivery_details'),$quality);
            $vehicleNo = $request->file('vno');
             $imageFileName1 = "vehicle".time().time() . '.' . $vehicleNo->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName1;
             $s3->put($filePath, file_get_contents($vehicleNo), 'public');

             $locationPicture = $request->file('lp');
             $imageFileName2 = "loction".time().time() . '.' . $locationPicture->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName2;
             $s3->put($filePath, file_get_contents($locationPicture), 'public');

             $quality = $request->file('qm');
             $imageFileName3 = "quality".time().time() . '.' . $quality->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName3;
             $s3->put($filePath, file_get_contents($quality), 'public');
        $check->order_id = $request->orderId;
        $check->vehicle_no = $imageFileName1;
        $check->location_picture = $imageFileName2;
        $check->quality_of_material = $imageFileName3;
        $check->delivery_date = date('Y-m-d h:i:s A');
        if($request->vid){
             $video = $request->file('vid');
             $imageFileName = "quality".time().time() . '.' . $video->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/delivery_details/' . $imageFileName;
             $s3->put($filePath, file_get_contents($video), 'public');
            
            // $video = "video".time().'.'.request()->vid->getClientOriginalExtension();
            // $request->vid->move(public_path('delivery_details'),$video);
            $check->delivery_video = $imageFileName;
        }
         
     }
 }
        
       

        Order::where('id',$request->orderId)->update(['delivery_status'=>"Delivered",'delivered_on'=>date('Y-m-d')]);

        $reasonText = date('H:i:s') > "20:00:00" ? "Delivering material at night" : "Delivering material";
        $point = date('H:i:s') > "20:00:00" ? 500 : 250;
        $points = new Point;
        $points->user_id = Auth::user()->id;
        $points->point = $point;
        $points->type = "Add";
        $points->reason = $reasonText;
        $points->save();
        return back();

    }

    

    public function getinvoice(Request $request)
    {
        $invoices = MhInvoice::where('invoice_id',$request->id)->get();
        $number = 48035;
        $length = strlen($number);
        if($number < 20){
            $length = 1;
        }
        $ones = array(
            0 => "", 
            1 => "one", 
            2 => "two", 
            3 => "three", 
            4 => "four", 
            5 => "five", 
            6 => "six", 
            7 => "seven", 
            8 => "eight", 
            9 => "nine", 
            10 => "ten", 
            11 => "eleven", 
            12 => "twelve", 
            13 => "thirteen", 
            14 => "fourteen", 
            15 => "fifteen", 
            16 => "sixteen", 
            17 => "seventeen", 
            18 => "eighteen", 
            19 => "nineteen" 
        ); 
        $tens = array(
            0 => "and",
            2 => "twenty", 
            3 => "thirty", 
            4 => "forty", 
            5 => "fifty", 
            6 => "sixty", 
            7 => "seventy", 
            8 => "eighty", 
            9 => "ninety" 
        ); 
        $hundreds = array( 
            "hundred", 
            "thousand", 
            "lakhs", 
            "crores", 
            "trillion", 
            "quadrillion" 
        );
        switch($length){
            case 1:
            // ones
                $text = $ones[$number];
                break;
            case 2:
            // tens
                $first = substr($number,0,1);
                $second = substr($number,-1);
                if($second != 0){
                    $text = $tens[$first]." ".$ones[$second];
                }else{
                    $text = $tens[$first];
                }
                break;
            case 3:
            // hundreds
                $first = substr($number,0,1);
                $text = $ones[$first]." ".$hundreds[0];
                $second = substr($number,-2);
                if($second != 0){
                    $number = $second;
                    $first = substr($number,0,1);
                    $second = substr($number,-1);
                    if($second != 0){
                        $text .= " ".$tens[$first]." ".$ones[$second];
                    }else{
                        $text .= " ".$tens[$first];
                    }
                }
            break;
            case 4:
            // thounsands
                $first = substr($number,0,1);
                $text = $ones[$first]." ".$hundreds[1];
                $second = substr($number,-3);
                if($second != 0){
                    $number = $second;
                    $first = substr($number,0,1);
                    if($first != 0){
                        $text .= " ".$ones[$first]." ".$hundreds[0];
                    }
                    $second = substr($number,-2);
                    if($second != 0){
                        $number = $second;
                        $first = substr($number,0,1);
                        $second = substr($number,-1);
                        if($second != 0){
                            $text .= " ".$tens[$first]." ".$ones[$second];
                        }else{
                            $text .= " ".$tens[$first];
                        }
                    }
                }
                break;
            case 5:
            // ten thousands
                $first = substr($number,0,2);
                if($first < 20){
                    $text = $ones[$first]." ".$hundreds[1];
                }else{
                    $another = substr($first,0,1);
                    $and = substr($first,-1);
                    $text = $tens[$another]." ".$ones[$and]." ".$hundreds[1];
                }
                $second = substr($number,-3);
                if($second != 0){
                    $number = $second;
                    $first = substr($number,0,1);
                    if($first != 0){
                        $text .= " ".$ones[$first]." ".$hundreds[0];
                    }
                    $second = substr($number,-2);
                    if($second != 0){
                        $number = $second;
                        $first = substr($number,0,1);
                        $second = substr($number,-1);
                        if($second != 0){
                            $text .= " ".$tens[$first]." ".$ones[$second];
                        }else{
                            $text .= " ".$tens[$first];
                        }
                    }
                }
                break;
            case 6:
            // lakhs
                $first = substr($number,0,1);
                if($first == 1){
                    $text = $ones[$first]." lakh";
                }else{
                    $text = $ones[$first]." ".$hundreds[2];
                }
                $first = substr($number,1,2);
                $check = substr($first,0,1);
                if($check == 0){
                    $first = substr($first,1,1);
                }
                if($first < 20){
                    $text .= " ".$ones[$first]." ".$hundreds[1];
                }else{
                    $another = substr($first,0,1);
                    $and = substr($first,-1);
                    $text .= " ".$tens[$another]." ".$ones[$and]." ".$hundreds[1];
                }
                $second = substr($number,-3);
                if($second != 0){
                    $number = $second;
                    $first = substr($number,0,1);
                    if($first != 0){
                        $text .= " ".$ones[$first]." ".$hundreds[0];
                    }
                    $second = substr($number,-2);
                    if($second != 0){
                        $number = $second;
                        $first = substr($number,0,1);
                        $second = substr($number,-1);
                        if($second != 0){
                            $text .= " ".$tens[$first]." ".$ones[$second];
                        }else{
                            $text .= " ".$tens[$first];
                        }
                    }
                }
                break;
        }
        return view('logistics.getinvoice',['text'=>$text,'invoices'=>$invoices]);
    }
    public function inputinvoice(Request $request)
    {
        $orders = Order::where('id',$request->id)->first();
        $project = projectdetails::all();
        $address = SiteAddress::where('project_id',$orders->project_id)->first();
        $owner = OwnerDetails::where('project_id',$orders->project_id)->first();
        return view('logistics.inputinvoice',['orders'=>$orders,'address'=>$address,'owner'=>$owner]);
    }
    public function lcinvoice(request $request){
        $x = MhInvoice::leftJoin('orders','mh_invoice.requirement_id','orders.id')
              ->where('orders.delivery_boy',Auth::user()->id)
              ->select('mh_invoice.*')->get();
        $invoice =count($x);
        return view('lcinvoice',['invoice'=>$invoice,'x'=>$x]);
    }
    public function feedback(Request $request)
    {
             
        $check = Order::where('id',$request->orderId)->first();
        if($check != NULL){
            $check->happy = $request->happy;
            $check->quality = $request->quan;
            $check->issue = $request->issue;
            $check->feedback = $request->note;
            $check->save();
            return back();
        }
    }
    public function deposit(Request $request){
        // $signatureName = Auth::user()->id."deposit".time().'.'.request()->image->getClientOriginalExtension();
        // $request->image->move(public_path('lcpayment'),$signatureName);
        
             $signatureName = $request->file('image');
             $imageFileName = Auth::user()->id."deposit".time() . '.' . $signatureName->getClientOriginalExtension();
             $s3 = \Storage::disk('azure');
             $filePath = '/lcpayment/' . $imageFileName;
             $s3->put($filePath, file_get_contents($signatureName), 'public');
  

        $pays = Deposit::where('orderId',$request->orderid)->first();

        if($pays == NULL){
            $pay = new Deposit;
            $pay->orderId = $request->orderId;
            $pay->user_id = $request->user_id;
            $pay->bankname =  $request->bankname;
            $pay->Amount = $request->Amount;
            $pay->bdate = $request->bdate;
            $pay->image=$imageFileName;
            $pay->zone_id = $request->zone_id;
            
            $pay->location = $request->location;
            $pay->save();
        }
        else{
            $pays->orderId = $request->orderId;
            $pays->user_id = $request->user_id;
            $pays->bankname =  $request->bankname;
            $pays->Amount = $request->Amount;
            $pays->bdate = $request->bdate;
            $pay->zone_id = $request->zone_id;
            
            $pays->image=$imageFileName;
            $pays->location = $request->location;
            $pays->save();
        }
        return back();
    }
    public function close(request $request){
        //dd($request->orderid);     
         Order::where('id',$request->orderid)->update(['payment_status'=>"Closed"]);
            return back();
    }
}
