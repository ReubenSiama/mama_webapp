@extends('layouts.app')
@section('content') 

<div class="container">
<div class="col-md-4">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;padding:15px;"><b>Total Customers With Type </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Type of Customers</th>
                 <th>Total Cuatomers</th>
               </thead>
               <tbody>
                 @foreach($data as $df)
                  <tr>
                     <td>{{$df['type']}}</td> 
                     <td>{{$df['count']}}</td>
                 </tr>
                 @endforeach
               </tbody>
            </table>


   </div>
   </div>
   <div class="col-md-4">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;padding:15px;"><b>Total Company Customers </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Type of Customers</th>
                 <th>Total Cuatomers</th>
               </thead>
               <tbody>
                 @foreach($dddd as $df)
                  <tr>
                     <td>{{$df['type']}}</td> 
                     <td>{{$df['count']}}</td>
                 </tr>
                 @endforeach
               </tbody>
            </table>


   </div>
   </div>
   
<div class="col-md-4">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;padding:15px;"><b>Assigned Customers With Type </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Type of Customers</th>
                 <th>Total Cuatomers</th>
               </thead>
               <tbody>

                 @foreach($assignedcustids as $df)
                  <tr>
                     <td>{{$df['type']}}</td> 
                     <td>{{$df['count']}}</td>
                 </tr>
                 @endforeach
               </tbody>
            </table>


   </div>
   </div>
   

   <div class="col-md-12">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;padding:15px;"><b>Aug Month Customers With Type total {{count($thismonth)}} </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Customer Name</th>
                 <th>Customer Id</th>
                 <th>Customer Type</th>
                 <th>Total Business</th>
                 <th>User Name</th>
               </thead>
               <tbody>

                 @foreach($thismonth as $df)
                  <tr>
                     <td>{{$df->first_name}}</td> 
                     <td>{{$df->customer_id}}</td>
                     <td>{{$df->type->cust_type ?? ''}}</td>
                        <td>         <?php   
                              $from = "2019-08-01";
                              $to = "2019-08-31";

                         $totalbuss = App\CustomerInvoice::wheredate('created_at','>=',$from)->wheredate('created_at','<=',$to)->where('customer_id',$df->customer_id)->sum('mhInvoiceamount'); ?>
                                 {{$totalbuss}} </td>
                    <td>
                          <?php $name = App\User::where('id',$df->orders->orderconvertedname ?? '')->pluck('name')->first() ?>
                         {{$name}}
                    </td>             
                      
                 </tr>
                 @endforeach
               </tbody>
            </table>


   </div>
   </div>

 <div class="col-md-12">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;padding:15px;"><b>Aug Month sales Target Details</b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Name</th>
                 <th>Total Target</th>
                 <th>Achived Target</th>
                 <th>Balance</th>
               </thead>
               <tbody>

                
                  <tr>
                     <td>Nagesh N</td>       
                     <td>902000</td>       
                     <td>555587</td>       
                     <td>346413</td>       
                      
                 </tr>
                 <tr>
                     <td>Chandana</td>       
                     <td>3123000</td>       
                     <td>875180</td>       
                     <td>2247820</td>       
                      
                 </tr>
                 <tr>
                     <td>Krishna Kumar J</td>       
                     <td>1652750</td>       
                     <td>566200</td>       
                     <td>1086550</td>       
                      
                 </tr>
                 <tr>
                     <td></td>       
                     <td></td>       
                     <td></td>       
                     <td></td>       
                      
                 </tr>
                 <tr>
                     <td>Sowjanya H R</td>       
                     <td>1295150</td>       
                     <td>560080</td>       
                     <td>735070</td>       
                      
                 </tr>
                 <tr style="background-color:#7b6b6b;">
                     <td>Deepak M K</td>       
                     <td>320850</td>       
                     <td>539884</td>       
                     <td>-219034</td>       
                      
                 </tr>
                 <tr>
                     <td>Maurya Mahishi</td>       
                     <td>-</td>       
                     <td>58170</td>       
                     <td>-</td>       
                      
                 </tr>
                 <tr style="background-color:#7b6b6b;">
                     <td>Deepika S</td>       
                     <td>100000</td>       
                     <td>288013</td>       
                     <td>-188013</td>       
                      
                 </tr>
               </tbody>
            </table>


   </div>
   </div>
 <div class="col-md-12">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;padding:15px;"><b>Active Project Customers {{count($projectcustomers)}} </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Name</th>
                 <th>customer Id</th>
                 <th>Project Id</th>
                 <th>Project Size</th>
               </thead>
               <tbody>

                @foreach($projectcustomers as $project)
                  <tr>
                     <td>{{$project->first_name}}</td>       
                     <td>{{$project->customer_id}}</td>       
                     <td><?php 
                          $st = "Closed";
                         $projectid = App\ProcurementDetails::where('procurement_contact_no',$project->mobile_num)->pluck('project_id')->toarray();
                           $ids = App\OwnerDetails::where('owner_contact_no',$project->mobile_num)->pluck('project_id')->toarray(); 
                           $id = App\ContractorDetails::where('contractor_contact_no',$project->mobile_num)->pluck('project_id')->toarray(); 
                       $projectids = App\ProjectDetails::whereIn('project_id',$projectid)->where('project_status','NOT LIKE',"%".$st."%")->orwhereIn('project_id',$ids)->orwhereIn('project_id',$id)->get();

                       $size = App\ProjectDetails::whereIn('project_id',$projectid)->where('project_status','NOT LIKE',"%".$st."%")->orwhereIn('project_id',$ids)->orwhereIn('project_id',$id)->sum('project_size'); 


                      ?>
                         <table class="table">
                             <thead>
                               
                            <th>Project Id</th>
                            <th>Status</th>
                             </thead>
                             <tbody>
                               @foreach($projectids  as $as)
                                <tr>
                                  <td>{{$as->project_id}}</td>
                                  <td>{{$as->project_status}}</td>
                                </tr>
                                @endforeach
                             </tbody>
                         </table>


                      </td>       
                     <td>{{$size}}</td>       
                      
                 </tr>
                 @endforeach
               </tbody>
            </table>


   </div>
   </div>

   <div class="col-md-12">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;padding:15px;"><b>Active Manufacturers Customers {{count($manucustomers)}} </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Name</th>
                 <th>customer Id</th>
                 <th>Project Id</th>
                 <th>Project Size</th>
               </thead>
               <tbody>

                @foreach($manucustomers as $project)
                  <tr>
                     <td>{{$project->first_name}}</td>       
                     <td>{{$project->customer_id}}</td>       
                     <td><?php 
                        
                         $projectid = App\Mprocurement_Details::where('contact',$project->mobile_num)->pluck('id')->toarray();  
                       $projectids = App\Manufacturer::whereIn('id',$projectid)->get();

                       // $size = App\ProjectDetails::whereIn('project_id',$projectid)->where('project_status','!=',"%".$st."%")->sum('project_size'); 


                      ?>
                         <table class="table">
                             <thead>
                               
                            <th>Manufacturer Id</th>
                            <th>Manufacturer Type</th>
                             </thead>
                             <tbody>
                               @foreach($projectids  as $as)
                                <tr>
                                  <td>{{$as->id}}</td>
                                  <td>{{$as->manufacturer_type}}</td>
                                </tr>
                                @endforeach
                             </tbody>
                         </table>


                      </td>       
                     <td>Active</td>       
                      
                 </tr>
                 @endforeach
               </tbody>
            </table>


   </div>
   </div>



   </div>
@endsection                
