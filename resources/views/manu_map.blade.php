<!DOCTYPE html>
<html>
<head>
  <title> Manufacturer Map</title>
  <style>
#myMap {
   height: 350px;
   width: 680px;
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGSf_6gjXK-5ipH2C2-XFI7eUxbHg1QTU&sensor=false"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script type="text/javascript" src="{{asset('js/gmaps.js')}}"></script>
    <script src="{{ URL::to('/') }}/js/jscolor.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js'></script>

    <!-- <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/some.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/appblade.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/app.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="{{ URL::to('/') }}/css/countdown.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <script src="https://unpkg.com/sweetalert2@7.17.0/dist/sweetalert2.all.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
   <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
</script>
  <?php $url = Helpers::geturl(); ?>
  <body>
<div class="topnav">
  <a class="active" href="{{ URL::to('/') }}/home" style="font-size:1.1em;font-family:Times New Roman;margin-left:15%;">Home</a>
</div><br><br>
<style>
* {box-sizing: border-box;}

body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  overflow: hidden;
  background-color:#e7e7e7;
  margin-right: 0;
margin-left: 0;
}

.topnav a {
  float: left;
  display: block;
  color: black;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  
  color: black;
}

.topnav .search-container {
  float: right;
}

.topnav input[type=text] {
  padding: 6px;
  margin-top: 8px;
  font-size: 17px;
  border: none;
}

.topnav .search-container button {
  float: right;
  padding: 6px 10px;
  margin-top: 8px;
  margin-right: 16px;
  background: #ddd;
  font-size: 17px;
  border: none;
  cursor: pointer;
}

.topnav .search-container button:hover {
  background: #ccc;
}

@media screen and (max-width: 600px) {
  .topnav .search-container {
    float: none;
  }
  .topnav a, .topnav input[type=text], .topnav .search-container button {
    float: none;
    display: block;
    text-align: left;
    width: 100%;
    margin: 0;
    padding: 14px;
  }
  .topnav input[type=text] {
    border: 1px solid #ccc;  
  }
}
</style>
<center> 
 <h2> Manufacturers Details</h2>

  <div class="col-md-12"><br><br>
     
      <div id="map" style="width:900px;height:500px"></div>
      </div></center>
<script type="text/javascript" scr="https://maps.google.com/maps/api/js?sensor=false"></script>
@if(count($projects) == 0)
<script type="text/javascript">
 
    window.onload = function() {
    var locations = new Array();
    var created = new Array();
    var updated = new Array();
    var status = new Array();
    var newpath = [];
    @if($subwardMap != "None")
    var latlng = "{{ $subwardMap->lat }}";
    var col = "{{ $subwardMap->color }}";
    @else
    var latlng = "";
    var col = "456369"
    @endif
    var places = latlng.split(",");
    for(var i=0;i<places.length;i+=2){
          newpath.push({lat: parseFloat(places[i]), lng: parseFloat(places[i+1])});
    }

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 20,
      center: new google.maps.LatLng(12.9716, 77.5946),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();
   
    var marker, i;
    var subward = new google.maps.Polygon({
        paths:  newpath,
        strokeColor: '#'+col,
        strokeOpacity: 1,
        strokeWeight: 2,
        fillColor: '#'+col,
        fillOpacity: 0.5
      });
  subward.setMap(map);
  }
  </script>
@else
  <script type="text/javascript">
    
    
    window.onload = function() {
    var locations = new Array();
    var created = new Array();
    var updated = new Array();
    var status = new Array();
    var newpath = [];
    @if($subwardMap != "None")
    var latlng = "{{ $subwardMap->lat }}";

    var col = "{{ $subwardMap->color }}";
    @else
    var latlng = "";
    var col = "456369"
    @endif
    var places = latlng.split(",");
    for(var i=0;i<places.length;i+=2){
          newpath.push({lat: parseFloat(places[i]), lng: parseFloat(places[i+1])});
    } 

    @foreach($projects as $project)
     locations.push(["<div class=card style=width:300px><img src={{ $url}}/Manufacturerimage/{{ $project->image }}  style=width:200px height=200px><div class=container><h4 class=pull-left>Manufacturers Id :<a target=_blank href={{ URL::to('/') }}/viewmanu?id={{ $project->id }}><b>{{$project->id }}</b></a></h4><br><h4 class=pull-left style=margin-left:-180px>Number:{{$project->proc !=null ? $project->proc->contact : ''}}</h4><br><h4 class=pull-left style=margin-left:-180px><p><a href=\"https://maps.google.com/?q={{ $project->address }}\">Address: {{ $project->address }}</a></p></h4></div></div>",{{ $project->latitude}}, {{ $project->longitude }}]);
      

      created.push("{{ $project->created_at}}");
      updated.push("{{ $project->updated_at}}");
      
    @endforeach
   
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      center: new google.maps.LatLng(locations[0][1], locations[0][2]),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) { 
    if(created[i] == updated[i]){
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
      });
    }else{
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
      });
    }

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
    if(newpath.length > 1){
    
      var subward = new google.maps.Polygon({
          paths: newpath,
          strokeColor: '#'+col,
          strokeOpacity: 1,
          strokeWeight: 2,
          fillColor: '#'+col,
          fillOpacity: 0.4
        });
    subward.setMap(map);
    }
  }
  </script>
@endif
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGSf_6gjXK-5ipH2C2-XFI7eUxbHg1QTU&callback=map"></script>
</body>
</html>
