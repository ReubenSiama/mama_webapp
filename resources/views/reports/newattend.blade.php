@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-color:#337ab7">
            <div class="panel-heading text-center" style="background-color:#337ab7">
                <b style="color:white">Today Employees Attendence Details
               </b>
            </div>
         

            <div class="panel-body">
             <form action="{{ URL::to('/') }}/newattend" method="GET" enctype="multipart/form-data"> 
                    {{ csrf_field() }}
                    <div class="col-md-2">
                   <h4><b>Select Employees</b></h4>
                   <?php $users = App\User::where('department_id',"!=",10)->get();  ?>
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
                        <th>Login Time</th>
                        <th>Logout Time</th>
                        <th>No Of Late logins(In Month)</th>
                        <th>Total Break Taken</th>
                        <th>Total Hours Worked</th>
                        <th>Final Working</th>
                       
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($logintime as $login)
                     <tr>
                        <td>{{$login->user->name ?? ''}}</td>
                        <td>{{$login->logintime}}</td>
                        <td>{{$login->logout}}</td>
                       <?php $date = "08:30";

                                     $log = App\FieldLogin::where('user_id',$login->user_id)->whereTime('logintime','>',$date.":00") ->whereYear('created_at',Carbon\Carbon::now()->year)
                                           ->whereMonth('created_at',Carbon\Carbon::now()->month)->count();   ?> 
                      @if($log >= 3)
                        <td style="background-color:#8b7777">
                        {{$log}}   
                        </td>
                           @else
                              <td>
                                {{$log}}
                                </td>
                         
                        @endif         
     
                        <td>
                            <?php   
                            $date_t=date('Y-m-d');
                            $totalhours = App\BreakTime::where('user_id',$login->user_id)->where('created_at','LIKE',$login->logindate."%")->sum('totaltime'); 
                               
                              $hours = intdiv($totalhours, 60).'.'. ($totalhours % 60);
                            ?>
                           {{$hours}}
                            
                        </td>
                        <td>
                            
                         <?php 
                             $loginTime = strtotime($login->logintime);
                             $logout =  strtotime($login->logout);
                              $time_diff = $logout - $loginTime;
                              $min = $time_diff/60;
                              $totalhours = $min/60;
                           
                           
                                               
                         ?> 
                         {{$totalhours}}
                                       
                        </td>
                        <td>
                            <?php $d = $totalhours - $hours ; ?>
                           {{$totalhours - $hours}}
                        </td>
                    
                    </tr>
                    @endforeach
                	
                </tbody>
         
                   </table>

        </div>
    </div>
</div>
</div>

@endsection