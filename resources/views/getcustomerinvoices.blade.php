<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 7? "layouts.sales":"layouts.app");
?>
@extends($ext)
@section('content')
	
           
<div class="container">
          <div class="panel panel-default" style="overflow: scroll;">
            <div class="panel-heading" style="background-color:#158942;color:white;font-size:1.4em;font-weight:bold">Customer Invoice Details </div>  
            <div class="panel-body">
            <form action="{{URL::to('/')}}/getcustomerinvoices" method="get">
          
             <div class="col-sm-6">
             <input type="text" id="myInput"  placeholder="enter phone number.." title="Type in a name" class="form-control"  name="number">
     
               </div>
               <div class="col-sm-3">
                <button type="submit" class="btn btn-sm btn-primary">Search</button> 
              
              </div>
              </form>
              <table class="order-table table table table-hover" id="myTable">
                <thead>
                  <th>Invoice Id</th>
                  <th>Procurement Name</th>
                  <th>Contact No.</th>
                  <th>Customer Id</th>
           
                </thead>
                    <tbody>
                       @foreach($invoice as $in)
                         <tr>
                            <td>{{$in->invoiceno}}</td>
                            <td>{{$in->CustomerDetails->first_name ?? ''}}</td>
                            <td>{{$in->CustomerDetails->mobile_num ?? ''}}</td>
                            <td>{{$in->CustomerDetails->customer_id ?? ''}}</td>
                             
                         </tr>
                      @endforeach      
                    </tbody>
               
                      
                </table>
                </div>
                </div>
               

</div>


 @endsection