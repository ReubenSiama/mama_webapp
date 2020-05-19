@extends('layouts.app')
@section('content')
<div class="col-md-8 col-md-offset-2">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-color:#337ab7">
            <div class="panel-heading text-center" style="background-color:#337ab7">
                <b style="color:white">Today Employees Work Details
               </b>
            </div>
         

            <div class="panel-body">
            <!--  <form action="{{ URL::to('/') }}/totalcallattend" method="GET" enctype="multipart/form-data"> 
                    {{ csrf_field() }}
                    <div class="col-md-3">
                   <h4><b>Select Employees</b></h4>
                   <?php $users = App\User::all(); ?>
                    <select   class="form-control" name="user_id" required>
                      <option  value="">--Employees--</option>
                      <option value="All">All</option>
                      @foreach($users as $user)
                      <option {{ isset($_GET['user_id']) ? $_GET['user_id'] : '' }} value="{{$user->id}}">{{$user->name}}</option>
                      @endforeach
                    </select>
                 </div>
                <div class="col-md-4">
                 <h4><b>Select From Date</b></h4>
                    <input class="form-control" value="{{ isset($_GET['fromdate']) ? $_GET['fromdate'] : '' }}" type="date" name="fromdate"  style="width:100%;" required>
                </div>
                   

               <div class="col-md-3">
                   <h4><b>Select To Date</b></h4>
                   <input class="form-control" value="{{ isset($_GET['todate']) ? $_GET['todate'] : '' }}" type="date" name="todate" style="width:100%;" required>
                     
                   </textarea>
                 </div> 
  
                  <div class="col-md-2">
                   
                    <button type="submit"  class="form-control btn btn-primary" value="submi" style="margin-top:40px;">Fetch Report</button> 
                 </div> 

            </div>
            </form> -->
          <center>  <table  class="table" border="1" style="width:60%;align-items:center;">
                <thead>
                    <tr> 
                        <th>Employee Name</th>                        
                        <th>Call Attended </th>
                         <th>Total Updated Projects/Manufacturers</th>
                        <th>Enquiries </th>
                         <th>Updated Proposed Projects Whatsapp  </th>
                         <th>Updated Proposed Manufacturers Whatsapp  </th>
                         <th>Updated Dedicated Customers Whatsapp  </th>
                         <th>Confirmed Orders</th>
                       
                    </tr>
                </thead>
                <tbody>
                  <?php $dedi=[];
                   $to=[]; ?>
                  @foreach($data as $dump)
                  <tr>
                     <td>{{$dump['name']}}</td>
                     <td>{{$dump['callattend']}}</td>
                     <td>{{$dump['total']}} <?php array_push($to, $dump['total']); ?> </td>
                  
                     <td>{{$dump['enq']}}</td>
                     <td>{{$dump['whatsapp_projects']}}</td>
                     <td>{{$dump['mwhatsapp_projects']}}</td>
                     <td>{{$dump['sum']}}<?php array_push($dedi, $dump['sum']); ?></td>
                     <td>{{$dump['order']}}</td>
                	</tr>
 
                   @endforeach
                   <tr>
                    <td>Total</td>
                    <td>{{$totalcal}}</td>
                    <td>{{array_sum($to)}}</td>
                  
                    <td>{{$totalenq}}</td>
                    <td>{{$totalwhatsapp_projects}}</td>
                    <td>{{$mwhatsapp_projects}}</td>
                    <td>{{array_sum($dedi)}}</td>
                    <td>{{$orders}}</td>
                  </tr>
                </tbody>
         
                   </table></center>

        </div>
    </div>
</div>
</div>

@endsection