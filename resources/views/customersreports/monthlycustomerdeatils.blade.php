
@extends('layouts.app')
@section('content') 
<div class="col-md-4 col-md-offset-1">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: #8ed28e;color:white;padding:15px;"><b>{{$f}} Month New Customers {{array_sum($totalassign)}} &nbsp;&nbsp;&nbsp;  Year ({{$year}})</b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Type of Customers</th>
                 <th>Total Cuatomers</th>
               </thead>
               <tbody>
                 @foreach($newcustomersdata as $df)
                  <tr>
                     <td>{{$df['type']}}</td> 
                     <td>{{$df['count']}}</td>
                 </tr>
                 @endforeach
               </tbody>
            </table>


   </div>
   </div>
   <div class="col-md-4 col-md-offset-1">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: #8ed28e;color:white;padding:15px;"><b>{{$f}} Month Old Customers {{array_sum($oldtotalassign)}} &nbsp;&nbsp;&nbsp;  Year ({{$year}})</b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Type of Customers</th>
                 <th>Total Cuatomers</th>
               </thead>
               <tbody>
                 @foreach($oldcustomersdata as $df)
                  <tr>
                     <td>{{$df['type']}}</td> 
                     <td>{{$df['count']}}</td>
                 </tr>
                 @endforeach
               </tbody>
            </table>


   </div>
   </div>
     <div class="col-md-6">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: #8ed28e;color:white;padding:15px;"><b>{{$f}} Month New Customers Total &nbsp; {{count($newthismonth)}} &nbsp;&nbsp;&nbsp;  Year ({{$year}}) </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Customer Id</th>
                 <th>Customer Name</th>
                 <th>Customer Type</th>
                 <th>Total Invoices</th>
                 <th>Total Business</th>
                 <th>Initiator Name</th>
               </thead>
               <tbody>
                 <?php $tot=[];
$amt=[];  ?>
                   @foreach($newthismonth as $df)
                  <tr>
                     <td>{{$df->customer_id}}</td>
                     <?php $data = App\CustomerDetails::with('type','orders')->where('customer_id',$df->customer_id)->first(); ?>
                     <td>{{$data['first_name']}}</td>
                     <td>{{$data->type->cust_type ?? ''}}</td>
                     <td>{{$df->total}}
                         <?php array_push($tot,$df->total); ?>
                      </td>
                  
                       
                        <td>         <?php   
                             
                         $totalbuss = App\CustomerInvoice::whereYear('invoicedate',$year)->whereMonth('invoicedate',$month)->where('customer_id',$df->customer_id)->sum('mhInvoiceamount'); 
                              
                              array_push($amt,$totalbuss);
 
                         ?>
                                 {{$totalbuss}} </td>
                   
                      <td>
                          <?php 
                           $id = App\CustomerInvoice::where('customer_id',$df->customer_id)->pluck('order_id')->first();

                            $userid = DB::table('orders')->where('id',$id)->pluck('generated_by')->first();
                            $name = App\User::where('id',$userid)->pluck('name')->first();

                          ?>
                          {{$name}}
                    </td>  
                 </tr>
                 @endforeach
                 <tr style="background-color:#b38b7b;">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td style="font-weight:bold;">{{array_sum($tot)}}</td>
                  <td style="font-weight:bold;">{{array_sum($amt)}}</td>
                 </tr>
               </tbody>
            </table>


   </div>
   </div>
<div class="col-md-6">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: #8ed28e;color:white;padding:15px;"><b>{{$f}} Month Old Customers Total &nbsp; {{count($thismonth)}} &nbsp;&nbsp;&nbsp;  Year ({{$year}}) </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Customer Id</th>
                 <th>Customer Name</th>
                 <th>Customer Type</th>
                 <th>Total Invoices</th>
                 <th>Total Business</th>
                 <!-- <th>Initiator Name</th> -->
               </thead>
               <tbody>
                 <?php $tot=[];
$amt=[];  ?>
                   @foreach($thismonth as $df)
                  <tr>
                     <td>{{$df->customer_id}}</td>
                     <?php $data = App\CustomerDetails::with('type','orders')->where('customer_id',$df->customer_id)->first(); ?>
                     <td>{{$data['first_name']}}</td>
                     <td>{{$data->type->cust_type ?? ''}}</td>
                     <td>{{$df->total}}
                         <?php array_push($tot,$df->total); ?>
                      </td>
                  
                       
                        <td>         <?php   
                             
                         $totalbuss = App\CustomerInvoice::whereYear('invoicedate',$year)->whereMonth('invoicedate',$month)->where('customer_id',$df->customer_id)->sum('mhInvoiceamount'); 
                              
                              array_push($amt,$totalbuss);
 
                         ?>
                                 {{$totalbuss}} </td>
                   
                    <!--  <td>
                          <?php $name = App\User::where('id',$data->orders->orderconvertedname ?? '')->pluck('name')->first() ?>
                         {{$name}}
                    </td>  --> 
                 </tr>
                 @endforeach
                 <tr style="background-color:#b38b7b;">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td style="font-weight:bold;">{{array_sum($tot)}}</td>
                  <td style="font-weight:bold;">{{array_sum($amt)}}</td>
                 </tr>
               </tbody>
            </table>


   </div>
   </div>

<div class="col-md-6">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: #8ed28e;color:white;padding:15px;"><b>{{$f}} Month Old Dedicated Customers Total &nbsp; {{count($old_dedicated)}} &nbsp;&nbsp;&nbsp;  Year ({{$year}}) </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Customer Id</th>
                 <th>Customer Name</th>
                 <th>Customer Type</th>
                 <th>Total Invoices</th>
                 <th>Total Business</th>
                 <!-- <th>Initiator Name</th> -->
               </thead>
               <tbody>
                 <?php $tot=[];
$amt=[];  ?>
                   @foreach($old_dedicated as $df)
                  <tr>
                     <td>{{$df->customer_id}}</td>
                     <?php $data = App\CustomerDetails::with('type','orders')->where('customer_id',$df->customer_id)->first(); ?>
                     <td>{{$data['first_name']}}</td>
                     <td>{{$data->type->cust_type ?? ''}}</td>
                     <td>{{$df->total}}
                         <?php array_push($tot,$df->total); ?>
                      </td>
                  
                       
                        <td>         <?php   
                             
                         $totalbuss = App\CustomerInvoice::whereYear('invoicedate',$year)->whereMonth('invoicedate',$month)->where('customer_id',$df->customer_id)->sum('mhInvoiceamount'); 
                              
                              array_push($amt,$totalbuss);
 
                         ?>
                                 {{$totalbuss}} </td>
                   
                    <!--  <td>
                          <?php $name = App\User::where('id',$data->orders->orderconvertedname ?? '')->pluck('name')->first() ?>
                         {{$name}}
                    </td>  --> 
                 </tr>
                 @endforeach
                 <tr style="background-color:#b38b7b;">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td style="font-weight:bold;">{{array_sum($tot)}}</td>
                  <td style="font-weight:bold;">{{array_sum($amt)}}</td>
                 </tr>
               </tbody>
            </table>


   </div>
   </div>
   <div class="col-md-6">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: #8ed28e;color:white;padding:15px;"><b>{{$f}} Month Old Company Customers Total &nbsp; {{count($old_company)}} &nbsp;&nbsp;&nbsp;  Year ({{$year}}) </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Customer Id</th>
                 <th>Customer Name</th>
                 <th>Customer Type</th>
                 <th>Total Invoices</th>
                 <th>Total Business</th>
                 <!-- <th>Initiator Name</th> -->
               </thead>
               <tbody>
                 <?php $tot=[];
$amt=[];  ?>
                   @foreach($old_company as $df)
                  <tr>
                     <td>{{$df->customer_id}}</td>
                     <?php $data = App\CustomerDetails::with('type','orders')->where('customer_id',$df->customer_id)->first(); ?>
                     <td>{{$data['first_name']}}</td>
                     <td>{{$data->type->cust_type ?? ''}}</td>
                     <td>{{$df->total}}
                         <?php array_push($tot,$df->total); ?>
                      </td>
                  
                       
                        <td>         <?php   
                             
                         $totalbuss = App\CustomerInvoice::whereYear('invoicedate',$year)->whereMonth('invoicedate',$month)->where('customer_id',$df->customer_id)->sum('mhInvoiceamount'); 
                              
                              array_push($amt,$totalbuss);
 
                         ?>
                                 {{$totalbuss}} </td>
                   
                    <!--  <td>
                          <?php $name = App\User::where('id',$data->orders->orderconvertedname ?? '')->pluck('name')->first() ?>
                         {{$name}}
                    </td>  --> 
                 </tr>
                 @endforeach
                 <tr style="background-color:#b38b7b;">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td style="font-weight:bold;">{{array_sum($tot)}}</td>
                  <td style="font-weight:bold;">{{array_sum($amt)}}</td>
                 </tr>
               </tbody>
            </table>


   </div>
   </div>


   @endsection
 
