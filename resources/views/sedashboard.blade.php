@extends('layouts.app')
@section('content')
<?php $url = Cathelpers::getusertarget(); ?>
<?php $ydata = Dedicatedcustomers::getalltarget(); 

 

?>

    <div class="row">
       <div class="col-md-4">
        <div class="panel panel-default">
        <div class="panel-heading"><center><b>Dedicated Customers Target Remender</b></center></div>
        <div class="panel-body">
               <div id="piechart234" style="height:270px; width: 100%;"></div>
           </div>
         </div>
       </div>
         <div class="col-md-4">
        <div class="panel panel-default">
        <div class="panel-heading"><center><b>TP Target Remender</b></center></div>
        <div class="panel-body">
               <div id="piechart" style="height:270px; width: 100%;"></div>
           </div>
         </div>
       </div>
        <div class="col-md-4">
        <div class="panel panel-default">
        <div class="panel-heading"><center><b>Category Target Remainder</b></center></div>
        <div class="panel-body">
          
              <div id="chartContainer" style="height:270px; width: 100%;"></div>
              
   <?php $cat = CategoryTargethelpers::getcattarget(); 

   
    
   $dataPoints = [];
   $cat_color= [//colorSet Array

    "blue",
    "red",
    "green",
    "pink",
    "gray",        
    "brown",
    "black",
    "gray",
    "#ff001f",
    "#4f057f"

                  
    ];
    for($i=0;$i<sizeof($cat);$i++){

      array_push($dataPoints,['label'=>$cat[$i]['category'],'y'=>$cat[$i]['invoice'],'color'=>  $cat_color[$i]] );
    }
  

   ?>
  
 <?php 
   
   $dataPoints2 = array(
  array("label"=> "TotalTarget", "y"=> $url['totaltarget'],'color'=> "#ff001f"),
  array("label"=> "Achived", "y"=> $url['achive'],'color'=> "#ee6002" ),
  array("label"=> "AchivedTp", "y"=> $url['totaltps'],'color'=> "#42A5F5" ),
  array("label"=> "Yet To AchivedTp", "y"=> $url['baltarget'],'color'=> "#263238" )
 
);
    
    

 
// );
   ?>
   <?php 
   
   $dataPoints22 = array(
  array("label"=> "TotalTarget", "y"=> $ydata['totaltarget'],'color'=> "#6200EE"),
  array("label"=> "Achived", "y"=> $ydata['achive'],'color'=> "#03DAC6" ),
  array("label"=> "AchivedTp", "y"=> $ydata['totaltps'],'color'=> "#018786" ),
  array("label"=> "Yet To AchivedTp", "y"=> $ydata['baltarget'],'color'=> "#00C853" )
 
);
    
    

 
// );
   ?>
           </div>
         </div>
       </div>
     </div>
     @foreach($date_today as $date)
         <center>
           <h4>Today Attendance</h4>
         <p>Login :{{$date->logintime}} </p>
         <p>Logout :{{$date->logout}} </p>
         </center><br><br><br>
         @endforeach
<script>
window.onload = function () {
  CanvasJS.addColorSet("greenShades",
                [//colorSet Array

                "blue",
                "red",
                "green",
                "pink",
                "gray",    
                "brown",
                "black",
                "gray",
                "#ff001f",
                "#4f057f"
                              
                ]);
var chart = new CanvasJS.Chart("chartContainer", {
   colorSet: "greenShades",
  animationEnabled: true,
  exportEnabled: false,
  theme: "light2", // "light1", "light2", "dark1", "dark2"
  
  data: [{
    type: "doughnut", //change type to bar, line, area, pie, etc
    //indexLabel: "{y}", //Shows y value on all Data Points
    indexLabelFontColor: "#5A5757",
    indexLabelPlacement: "outside",  
    indexLabel: "#percent%",
      percentFormatString: "#0.##",
      toolTipContent: "{y} (#percent%) {label}", 
      indexLabelFontSize:12,
    indexLabelFontWeight: "bolder",
    showInLegend: true,
    legendText: "{label}",
    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
  }]
});

var chart3 = new CanvasJS.Chart("piechart", {
   colorSet: "greenShades",
  animationEnabled: true,
  exportEnabled: false,
  theme: "light1", // "light1", "light2", "dark1", "dark2"

  data: [{
    type: "pie", //change type to bar, line, area, pie, etc
    //indexLabel: "{y}", //Shows y value on all Data Points
    indexLabelFontColor: "#5A5757",
    indexLabelPlacement: "outside",
    // indexLabel: "#percent%",
    indexLabel: "{y}",
    percentFormatString: "#0.##",
    toolTipContent: "{y} (#percent%) {label}", 
    indexLabelFontSize:12,
    indexLabelFontWeight: "bolder",
    showInLegend: true,
    legendText: "{label}",  
    dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
  }]
});
var chart4 = new CanvasJS.Chart("piechart234", {
   colorSet: "greenShades",
  animationEnabled: true,
  exportEnabled: false,
  theme: "light1", // "light1", "light2", "dark1", "dark2"

  data: [{
    type: "pie", //change type to bar, line, area, pie, etc
    //indexLabel: "{y}", //Shows y value on all Data Points
    indexLabelFontColor: "#5A5757",
    indexLabelPlacement: "outside",
    // indexLabel: "#percent%",
    indexLabel: "{y}",
    percentFormatString: "#0.##",
    toolTipContent: "{y} (#percent%) {label}", 
    indexLabelFontSize:12,
    indexLabelFontWeight: "bolder",
    showInLegend: true,
    legendText: "{label}",  
    dataPoints: <?php echo json_encode($dataPoints22, JSON_NUMERIC_CHECK); ?>
  }]
});
chart3.render();
chart4.render();



chart.render();
 
}
</script>


<div class="col-sm-4 col-md-offset-1">
 <center><h2>Today Followup Enquiries</h2></center><br>
<center><table class="table" border="1">
    <thead>
        <td>SlNo</td>
        <td>Requirement Date</td>
        <td>Enquiry Id</td>
    </thead>
    <tbody>

         <?php $i=1; ?>
        @foreach($followup as $follow)
        <tr>
           <td>{{$i++}} </td>
           <td>{{$follow->requirement_date}}</td>
           <td><a href="{{ URL::to('/') }}/editenq?reqId={{ $follow->id }}" class="btn btn-xs btn-primary">{{$follow->id}}</a></td>
        </tr>  
        @endforeach  
    </tbody>
</table></center>
</div>
<div class="col-sm-4 col-md-offset-1">
 <center><h2>Today Call Report [ Count : {{$callreport->total()}} ]</h2></center><br>
<center><table class="table" border="1">
    <thead>
        <td>SlNo</td>
        <td> project/Manufacturer</td>
        <td>call Time</td>
    </thead>
    <tbody>

         <?php $i=1; ?>
        @foreach($callreport as $call)
        <tr>
           <td>{{$i++}} </td>
           <td>
            @if(count($call->project_id) != 0)

            <a href="{{ URL::to('/') }}/admindailyslots?projectId={{$call->project_id}}" target="_blank">Project : {{ $call->project_id }}</a>
            @else
            <a href="{{ URL::to('/') }}/viewmanu?id={{ $call->manu_id }}"> Manufacturer : {{$call->manu_id}}</a>
          @endif
      </td>
           <td>{{ date('h:i:s A', strtotime($call->called_Time)) }}</td>
        </tr>  
        @endforeach  
         <center>{{ $callreport->links() }}</center>
    </tbody>
</table></center>
</div>

<script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }
        
        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft= "0";
        }
</script>
<script type="text/javascript">
  function startbreak(){
    var id = "hello";
    $.ajax({
        type: 'GET',
        url: "{{ URL::to('/') }}/breaktime",
        async: false,
        data: { id : id},
        success: function(response){  
          setInterval(mytimer, 1000);
        }
    })
  }
  function mytimer(){
    var str = "";
      var now = new Date();

      str += "Your Break Time Started: " + now.getHours() +":" + now.getMinutes() + ":" + now.getSeconds();
      document.getElementById("currentTime").innerHTML = str;
  }
</script>
@endsection
