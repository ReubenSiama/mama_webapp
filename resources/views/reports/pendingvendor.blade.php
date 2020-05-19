@extends('layouts.app')
<style>
table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}
</style>
@section('content')
<?php $url = Helpers::geturl(); ?>

<div class="container">
  <span class="pull-right"> @include('flash-message')</span>
  
<div class="panel panel-default" style="border-color:green;"> 
 <div class="panel-heading text-center" style="background-color:#5cb85c;color:black;padding:10px;"><b>Pending   Vendors 
 </div>
</div>
</b>
</div>
 <div style="overflow-x:auto;" class="col-sm-10 col-sm-offset-1">
             
               <table class="table table-responsive table-striped table-hover" border="1" >
               	<thead>

                       
                        <th>Company Name</th>
                        <th>Vendor Type</th>
                       
                        <th>Category</th>
                       
                        <th>GST</th>
                        <th>Registered Office</th>
                        <th>PAN</th>
                        <th>Production Capacity</th>
                        <th>Factory Location</th>
                        <th>Warehouse Location</th>
                        <th>MD</th>
                        <th>CEO</th>
                        <th>Status</th>
                        
                    </thead>
              <tbody>
                        <?php $count = 0; $count1 = 0; ?>
                        @foreach($mfdetails as $detail)
                        <tr>
                       

                            <td>{{ $detail->company_name }}</td>
                            <td>{{ $detail->vendor_type }}</td>
                           
                            <td>{{ $detail->category }}</td>
                           
                            <td>{{ $detail->gst }}</td>
                            <td>{{ $detail->registered_office }}</td>
                            <td>@if($detail->pan != NULL) <a href="{{ $url }}/pan/{{ $detail->pan }}">View</a>@endif</td>
                            <td>{{ $detail->production_capacity }}</td>
                            <td>{{ $detail->factory_location }}</td>
                            <td>{{ $detail->ware_house_location }}</td>
                            <td>{{ $detail->md }}</td>
                            <td>{{ $detail->ceo }}</td>
                            <td>
                                @if($detail->approve == 1)
                                     Pending for Approval 
                                 @else   
                                   Rejected
                                   @endif


                            </td>
                            <td>
                                 <div class="btn-group">
                                  <a href="{{URL::to('/')}}/Vendoraccept?id={{$detail->manufacturer_id}}" class="btn btn-primary">Accept</a>
                                  <a href="{{URL::to('/')}}/Vendorreject?id={{$detail->manufacturer_id}}" class="btn btn-danger">Reject</a>
                                  
                                </div>

                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
          </table>
</div>
</div>
</div>

@endsection