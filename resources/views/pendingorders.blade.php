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
<div class="container">
  <span class="pull-right"> @include('flash-message')</span>
  
<div class="panel panel-default" style="border-color:green;"> 
 <div class="panel-heading text-center" style="background-color:#5cb85c;color:black;padding:10px;"><b>Pending Orders
 </div>
</div>
</b>
</div>
 <div style="overflow-x:auto;" class="col-sm-8 col-sm-offset-2">
             
               <table class="table table-responsive table-striped table-hover" border="1" >
               	<thead style="background-color:#337ab7;color:#dddcdc;">
                  <th>Slno</th>
               	  <th>Order Id</th>
                  <th>Category</th>
                  <th>Brand</th>
               	  <th>Quantity</th>
                  <th>Price </th>
                  <th>#</th>
              </thead>
              <tbody>
              	<?php $i=1; ?>
              	@foreach($orders as $order)
              	<tr>
              		<td>{{$i++}}</td>
              		<td>{{$order->id}}</td>
              		<td>{{$order->main_category}}</td>
              		<td>{{$order->brand}}</td>

              		<td>{{$order->req->total_quantity ?? ''}}</td>
              		<td>{{$order->req->price ?? ''}}</td>
                     <td><a class="btn btn-sm btn-primary"  href="{{URL::to('/')}}/approveorder?id={{$order->id}}">Approve</a></td>
              	</tr>
              	@endforeach
              </tbody>
          </table>
</div>
</div>
</div>

@endsection