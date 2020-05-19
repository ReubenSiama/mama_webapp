@extends('layouts.app')
@section('content')

 <?php $url = Cathelpers::getusertarget(); ?>
<?php $cates['categorytarget'] = "";
      $cates['invoice'] = "";
      $cates['category'] = ""; ?>
    <div class="row">
         <div class="col-md-3">
        <div class="panel panel-default">
        <div class="panel-heading"><center><b>Target Remainder</b></center></div>
        <div class="panel-body">
               <div id="piechart" style="height:270px; width: 100%;"></div>
           </div>
         </div>
       </div>



        <!-- <div class="col-md-3">
        <div class="panel panel-default">
        <div class="panel-heading"><center><b>Category </b></center></div>
        <div class="panel-body"> -->
              <!-- <div id="chartContainer" style="height:270px; width: 100%;"></div> -->
   <?php $cat = CategoryTargethelpers::getcattarget(); 
    
    $dataPoints = [];
    for($i=0;$i<sizeof($cat);$i++){

      array_push($dataPoints,['label'=>$cat[$i]['category'],'y'=>$cat[$i]['invoice']]);
    }
  

   ?>
  <?php 
    
   $dataPoints2 = array(
  array("label"=> "TotalTarget", "y"=> $url['totaltarget'],'color'=>"#ff001f"),
  array("label"=> "Yet to Achived", "y"=> $url['baltarget'],'color'=> "green" )
 
);

$dataPoints10 = array(
  array("label"=> "TotalTarget", "y"=> $cates[0]['categorytarget'],'color'=>"#2F4F4F"),
  array("label"=> "Yet to Achived", "y"=> $cates[0]['invoice'],'color'=> "green" )
 
);

    
//    $dataPoints2 = array(
//   array("label"=> "TotalTarget", "y"=>{{ $url['totaltarget'] }}),
//   array("label"=> "Achived", "y"=> {{ $url['achive'] }})
 
// );
   ?>

          <!--  </div>
         </div>
       </div> --> 
         <div class="col-md-6" style="overflow:scroll;overflow-y:scroll;overflow-x:hidden;height:350px">
        <div class="panel panel-default">
        <div class="panel-heading" style="padding:25px;"><b class="pull-left">Categorywise Target</b>
<!-- <span class="pull-left"> @include('flash-message')</span>
 -->
          <div class="pull-right" style="margin-top:-20px;">
            <?php $names = App\Category::all() ?>
            <form action="{{URL::to('/')}}/teamLead" method="get" id="formget">
              
             <select class="form-control" name="name" onchange="this.form.submit()">
               <option value="">--Selet Category--</option>
               @foreach($names as $name)
                <option value="{{$name->id}}">{{$name->category_name }}</option>
                @endforeach
             </select>

            </form>
          </div>

        </div>
        <div class="panel-body">
          <div class="row">
               
              <div class="col-md-12">
              <h6 style="text-align:center;font-weight:bold;">{{$cates[0]['category']}}</h6>
               <div id="piechart10" style="height:270px; width: 100%;"></div> 
                    

              </div>
          </div>
           </div>
         </div>
        </div>
         <div class="col-md-3">
        <div class="panel panel-default">
          
        <div class="panel-heading"><center><b>Customer Visit History</b></center></div>
        <div class="panel-body">
      <div class="pull-right" style="margin-right:10px;">
  
        <table class='table table-responsive table-striped' border="1" >

    <tr>
      <td>Assigned Customers</td>
      <td>:</td>
      <td><a href="{{URL::to('/')}}/customervisit">{{$total}}</a></td>
    </tr>
     <tr>
      <td>Balance Customers</td>
      <td>:</td>
      <td><a href="{{URL::to('/')}}/customervisit?bal=bal">{{$bal}}</a></td>
    </tr>
     <tr>
      <td>Today Visit Customers</td>
      <td>:</td>
      <td><a href="{{URL::to('/')}}/customervisit?today=today">{{$today}}</a></td>
    </tr>
  </table>
</div>
<div class="pull-left">
   <center><h4>Today Followup Enquiries<a href="{{URL::to('/')}}/Assignfollowup">({{count($followup)}})</a></h4></center><br>
 <center>     <button class="btn btn-default " data-toggle="modal" data-target="#changeEmployee" style="background-color:green;color:white;font-weight:bold;">Change Your Designation</button></center>
</div>

           </div>
         </div>
       </div>
</div>
  
<script>
window.onload = function () {
  CanvasJS.addColorSet("greenShades",
                [//colorSet Array
                "#2F4F4F",
                "#008080",
                "#2E8B57",
                "#3CB371",
                "#90EE90"                
                ]);
var chart = new CanvasJS.Chart("chartContainer", {
   colorSet: "greenShades",
  animationEnabled: true,
  exportEnabled: false,
  theme: "light2", // "light1", "light2", "dark1", "dark2"
 
  data: [{
    type: "pie", //change type to bar, line, area, pie, etc
    indexLabel: "{y}", //Shows y value on all Data Points
    indexLabelFontColor: "#5A5757",
    indexLabelPlacement: "outside", 
    indexLabel: "#percent%",
      percentFormatString: "#0.##",
      toolTipContent: "{y} (#percent%) {label}",  
    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
  }]
});

var chart3 = new CanvasJS.Chart("piechart", {
  
  animationEnabled: true,
  exportEnabled: false,
  theme: "light1", // "light1", "light2", "dark1", "dark2"

  data: [{
    type: "pie", //change type to bar, line, area, pie, etc
    //indexLabel: "{y}", //Shows y value on all Data Points
    indexLabelFontColor: "#5A5757",
    indexLabelPlacement: "outside", 
    indexLabel: "#percent%",
      percentFormatString: "#0.##",
      toolTipContent: "{y} (#percent%) {label}",  
    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
  }]
});
var chart4 = new CanvasJS.Chart("piechart10", {
  
  animationEnabled: true,
  exportEnabled: false,
  theme: "light1", // "light1", "light2", "dark1", "dark2"

  data: [{
    type: "pie", //change type to bar, line, area, pie, etc
    //indexLabel: "{y}", //Shows y value on all Data Points
    indexLabelFontColor: "#5A5757",
    indexLabelPlacement: "outside",
    indexLabel: "#percent%",
      percentFormatString: "#0.##",
      toolTipContent: "{y} (#percent%) {label}",   
    dataPoints: <?php echo json_encode($dataPoints10, JSON_NUMERIC_CHECK); ?>
  }]
});

chart4.render();
chart3.render();



chart.render();
 
}
</script>
 









  <form method="post" action="{{ URL::to('/') }}/changedesc">
    {{ csrf_field() }}
  <div class="modal fade" id="changeEmployee" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#f4811f;color:white;fon-weight:bold">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change Designation</h4>
        </div>
        <div class="modal-body">
          <table class="table table-hover">
              <tbody>
                  <tr>
                   <td><label>Users</label></td>
                      <td><select required class="form-control" name="user">
                      <option value="">--Select--</option>
                      <?php $userq = App\User::where('id',Auth::user()->id)->get(); ?>
                      @foreach($userq as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                      @endforeach
                  </select></td>
                  </tr>
                  <tr>
                    <td><label>Department</label></td>
                      <td><select required class="form-control" name="dept">
                      <option value="">--Select--</option>
                      @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->dept_name }}</option>
                      @endforeach
                  </select></td>
                  </tr>
                  <tr>
                    <td><label>Designation</label></td>
                    <td> <select required class="form-control" name="designation">
                      <option value="">--Select--</option>
                      @foreach($groups as $designation)
                        <option value="{{ $designation->id }}">{{ $designation->group_name }}</option>
                      @endforeach
                  </select></td>
                  </tr> 
                </tbody>
              </table>
            </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</form>
<div class="row">
<div class="col-md-4">
    <div class="panel panel-default">
        <div class="panel-heading"><center><b>MINI ATTENDANCE ({{ date('d-m-Y') }})</b></center></div>
        <div class="panel-body">
        <table class="table table-hover">
        <thead>
            <tr>
                <th>Employee-Id</th>
                <th>Name</th>
                <th>Login Time</th>
                <th>Logout Time</th>
            </tr>
           </thead>
                       


            @foreach($loggedInUsers as $loggedInUser)
              
                 
                <tr>
                    <td>{{ $loggedInUser->employeeId }}</td>
                    <td>{{ $loggedInUser->name }}</td>
                    <td>{{ $loggedInUser->logintime }}</td>
                    <td>{{ $loggedInUser->logout }}</td>
               </tr>
            
            @endforeach
            
        </table>
        </div>
    </div>
</div>
<div class="col-md-4"> 
<div class="panel panel-default">
        <div class="panel-heading"><center><b>Today Employees</b></center></div>
        <div class="panel-body">
        <table class="table table-hover">
        <thead>
            <tr>
                <th>Online Employees</th>
              
                <th>Offline Employees</th>
            </tr>
           </thead>
           
          
                <tr>
                    <td><?php 
                    $date = date("Y-m-d");

                    $user = DB::table('notifications')->where('logout',NULL)->where('created_at','LIKE',$date.'%')->where('approve',1)->pluck('user_id'); 
        if(count($user) != 0){

              $users = App\User::whereIn('id',$user)->get();
        }else{
             $users = [];
        }
                 
        
   ?>
   @foreach($users as $user)    
       {{$user->name}}&nbsp;&nbsp;<img src="https://icons.iconarchive.com/icons/oxygen-icons.org/oxygen/256/Status-user-online-icon.png" width="10px" height="10px"><br>
@endforeach
   </td>
                    <td>
                        <?php $user1 = DB::table('notifications')->where('created_at','LIKE',$date.'%')->where('approve',NULL)->pluck('user_id'); 
                       
    if(count($user1) != 0){
      $users1 = App\User::whereIn('id',$user)->get();
    }else{
         $users1 = [];
    }
   ?>
   @foreach($users1 as $user1)    
       {{$user1->name}}<br>
@endforeach
                    </td>
                   
               </tr>
           
            
        </table>
        </div>

</div>

      </div>
</div>
</div>
<div class="col-sm-4">
<div class="panel panel-default">
        <div class="panel-heading"><center><b>Today Not Interested Customers</b></center></div>
        <div class="panel-body">
        <table class="table table-hover">
  <thead>
  <tr>
    <th>Manufacturer/Project Id</th>
    <th>Name</th>
    <th>Number</th> 
  </tr>
</thead>
<tbody>
  @foreach($notinterest as $inc)
  <tr>
   <td>

    <a href="{{URL::to('/')}}/viewmanu?id={{$inc->id}}"> Manufacturer: {{$inc->id}}</a></td>
   <td>{{$inc->proc != null ? $inc->proc->name : ''}}</td>
   <td>{{$inc->proc != null ? $inc->proc->contact : ''}}</td>
  </tr>
  @endforeach
   @foreach($notinterests as $incs)
  <tr>
   <td>
    
  <a href="{{URL::to('/')}}/showThisProject?id={{$incs->id}}">Project: {{$incs->project_id}}</a></td>
   <td>{{$incs->procurementdetails != null ? $incs->procurementdetails->procurement_name : ''}}</td>
   <td>{{$incs->procurementdetails != null ? $incs->procurementdetails->procurement_contact_no : ''}}</td>
  </tr>
  @endforeach
</tbody>
</table>

</div>
</div>

</div>
</div>
@endsection
