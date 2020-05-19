@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-color:#337ab7">
            <div class="panel-heading text-center" style="background-color:#337ab7">
                <b style="color:white">Today Employees Work Details
               </b>
            </div>
         

            <div class="panel-body">
             <form action="{{ URL::to('/') }}/totalreport" method="GET" enctype="multipart/form-data"> 
                    {{ csrf_field() }}
                    <div class="col-md-2">
                   <h4><b>Select Employees</b></h4>
                   <?php $group = [6,7,1,2];
     $d=[10];
   $users = App\User::whereIn('group_id',$group)->whereNotIn('department_id',$d)->get();?>
   
                    <select   class="form-control" name="user_id" required>
                      <option  value="">--Employees--</option>
                      @foreach($users as $user)
                      <option {{ isset($_GET['user_id']) ? $_GET['user_id'] : '' }} value="{{$user->id}}">{{$user->name}}</option>
                      @endforeach
                    </select>
                 </div>
                <div class="col-md-2">
                 <h4><b>Select From Date</b></h4>
                    <input class="form-control" value="{{ isset($_GET['fromdate']) ? $_GET['fromdate'] : '' }}" type="date" name="fromdate"  style="width:100%;" required>
                </div>
                   

               <div class="col-md-2">
                   <h4><b>Select To Date</b></h4>
                   <input class="form-control" value="{{ isset($_GET['todate']) ? $_GET['todate'] : '' }}" type="date" name="todate" style="width:100%;" required>
                     
                   </textarea>
                 </div> 
  
                  <div class="col-md-2">
                   
                    <button type="submit"  class="form-control btn btn-primary" value="submi" style="margin-top:40px;">Fetch Report</button> 
                 </div> 

            </div>
            </form>
            <table  class="table" border="1">
                <thead>
                    <tr> 
                        <th>Employee Name</th>                        
                        <th>Added Projects</th>
                        <th>Added Manufactures</th>
                        <th>Updated Projects</th>
                        <th>Updated Manufactures</th>
                        <th>Enquiry Initiated </th>
                        <th>Confirmed Orders</th>
                       
                        <th>Call Attend </th>
                        <th>Busy and not Reachable</th>
                        <th>Switched Off</th>
                        <th>Call Not Answered </th>
                        <th>Not Interested</th>
                        <th>No Of Logistic</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach($data as $dump)
                	<tr>
                     <td>{{$dump['name']}}</td>
                     <td>{{$dump['addproject']}}</td>
                     <td>{{$dump['addmanu']}}</td>
                     <td>{{$dump['updateprojects']}}</td>
                     <td>{{$dump['updatedmanu']}}</td>
                     <td>{{$dump['enq']}}</td>

                     <td>{{$dump['order']}}</td>
                 
                     <td>{{$dump['callattend']}}</td>
                     <td>{{$dump['callbusy']}}</td>
                     <td>{{$dump['switchoff']}}</td>
                     <td>{{$dump['notanswer']}}</td>
                     <td>{{$dump['notinterest']}}</td>
                     <td>{{$dump['logistic']}}</td>
                     
                	</tr>

                   @endforeach
                </tbody>
         
                   </table>

        </div>
    </div>
</div>
</div>

@endsection