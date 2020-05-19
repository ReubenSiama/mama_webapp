@extends('layouts.app')
@section('content')
 <?php $url = Cathelpers::getusertarget(); ?>
<?php $cates['categorytarget'] = "";
      $cates['invoice'] = "";
      $cates['category'] = ""; ?>
    <div class="row">
        
        
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
<h3 style ="color:green">
  <center>Welcome To Zonal Manager  Dashboard - Zone 1 <br><br> <br>
  <img src="./public/b2.jpg"> <br><br>
  <h4 style ="color:black;margin-left: 364px;"><b>Powered By</b></h4> <br>
   <img style="margin-left: 760px;"  width="350px" hight="750px" src="./public/APS-LOGO-FINAL%20(1).png">
    <h4 style ="color:gray;margin-left: 760px;">Auto Pilot System (APS)</h4>
  </center>
 

      
         <!-- <img align="right" src="https://www.mamahome360.com/webapp/APS-LOGO-FINAL%20(1).png" > -->
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
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

</center>
{{-- <center class="countdownContainer">
    <h1>Operation <i style="color:yellow; font-size: 50px;" class="fa fa-bolt"></i> Lightning</h1>
    <div id="clockdiv">
        <div>
            <span class="days"></span>
            <div class="smalltext">Days</div>
        </div>
        <div>
            <span class="hours"></span>
            <div class="smalltext">Hours</div>
        </div>
        <div>
            <span class="minutes"></span>
            <div class="smalltext">Minutes</div>
        </div>
        <div>
            <span class="seconds"></span>
            <div class="smalltext">Seconds</div>
        </div>
    </div>
</center> --}}
<br>
<div class="row">
<div class="col-md-5 col-md-offset-1">
    <div class="panel panel-default">
        <div class="panel-heading"><b>MINI ATTENDANCE ({{ date('d-m-Y') }}) &nbsp;&nbsp;&nbsp; Office Employees</b></div>
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
             @if($loggedInUser->group_id != 6 && $loggedInUser->group_id != 11)
                <tr>
                    <td>{{ $loggedInUser->employeeId }}</td>
                    <td>{{ $loggedInUser->name }}</td>
                    <td>{{ $loggedInUser->logintime }}</td>
                    <td>{{ $loggedInUser->logout }}</td>
                </tr>
            @endif
            @endforeach
        </table>
        </div>
    </div>
</div>

<div class="col-md-6">
  

  
  
<div class="panel panel-default">
        <div class="panel-heading"><center><b>Today field Employees</b></center></div>
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
       {{$user->name}}&nbsp;&nbsp;<img src="http://icons.iconarchive.com/icons/oxygen-icons.org/oxygen/256/Status-user-online-icon.png" width="10px" height="10px"><br>
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
<div class="row">
<div class="col-md-5 col-md-offset-1">
    <div class="panel panel-default">
        <div class="panel-heading"><b>MINI ATTENDANCE</b></div>
        <div class="panel-body">
        <table class="table table-hover">
          
           <tr>
            <td>Total Employees Present</td>
            <td>{{$present }}</td>
            </tr>
            <tr>
                <td>Total Employees Absent</td>
                <td>{{$absent}}</td>
            </tr>
           @foreach($ntlogins as $ntlogin)
             <tr>
                <td>{{$ntlogin->employeeId}}</td>
                <td>{{$ntlogin->name}}</td>
            </tr>

            @endforeach
        </table>
        </div>
    </div>
</div>
</div>

@endsection
