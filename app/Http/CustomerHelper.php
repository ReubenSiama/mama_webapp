
<?php 

use App\NewCustomerAssign;
use App\CustomerDetails;
use App\ProcurementDetails;
use App\Mprocurement_Details;
use App\Requirement;
class CustomerHelper extends Model{


	public static function getcustomer(){

      $number = NewCustomerAssign::pluck('customerids');
      $full = [];
      for ($i=0; $i < count($number); $i++) { 
      	
         $id =explode(",",$number[$i]);

          
         array_push($full,$id);

      }
      $data = array_merge(...$full);

      $numbers = CustomerDetails::whereIn('customer_id',$data)->pluck('mobile_num');
      $projectids = ProcurementDetails::whereIn('procurement_contact_no',$numbers)->pluck('project_id');
      $manufactures = Mprocurement_Details::whereIn('contact',$number)->pluck('manu_id');
      $enquirs = Requirement::whereIn('project_id',$projectids)->orwhereIn('manu_id',$manufactures)->pluck('id');

      $yup=['numbers'=>$numbers,'project'=>$projectids,'manu'=>$manufactures,'enquiry'=>$enquirs];    


      return $yup;

	}
}



?>