@extends('layouts.app')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mamahome</title>
    <script type="text/javascript" src="{{asset('js/gmaps.js')}}"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<br>
<div class="container">
    <div class="row">
  <span class="pull-right"> @include('flash-message')</span>
      
        <div class="col-md-8 col-md-offset-2">
        <form method="get" onsubmit="validateform()" id="km" action="{{ URL::to('/') }}/getmanudistance" enctype="multipart/form-data">
            <div class="panel panel-default">
                <div class="panel-heading" style="background-color:#42c3f3;color:#ffffffe3;padding:20px;">
             <div id="currentTime" class="pull-right" style="margin--5px;"></div>
            <div id="currentTime" class="pull-left" style="margin--5px;">
              
            </div>
                </div>
                <div class="panel-body">

                <!-- <div id="myMap" style="width:700px;height:250px;object-fit: cover;"></div> -->
                  

    
                     <a id="getBtn"  class="btn btn-success btn-sm" onclick="getLocation()">Get Location</a></center><br>
                    <div id="first">
                    {{ csrf_field() }}
                            <table class="table">
                               
                               <tr class="hidden">
                                   <td>Location</td>
                                   <td>:</td>
                                   <td id="x">
                                    <div class="col-sm-6">
                                      <label>Longitude:</label>
                                        <input placeholder="Longitude" class="form-control input-sm" required readonly type="text" name="longitude" value="{{ old('longitude') }}" id="longitude">
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Latitude:</label>
                                        <input placeholder="Latitude" class="form-control input-sm" required readonly type="text" name="latitude" value="{{ old('latitude') }}" id="latitude">
                                    </div>
                                   </td>
                               </tr>
                               <tr>
                                <td>
                                  
                                  
                                    <div class="col-sm-6">
                          <label>Choose Kilometers :</label><br>
                          <select name="km"  class="form-control" id="ward">
                              <option value="">--Select--</option>
                              <option value="0.1">0.1KM</option>
                              <option value="0.2">0.2KM</option>
                              <option value="0.3">0.3KM</option>
                              <option value="0.4">0.4KM</option>
                              <option value="0.5">0.5KM</option>
                              <option value="0.6">0.6KM</option>
                              <option value="0.7">0.7KM</option>
                              <option value="0.8">0.8KM</option>
                              <option value="0.9">0.9KM</option>
                              <option value="1.0">1.0KM</option>
                              <option value="1.1">1.1KM</option>
                              <option value="1.2">1.2KM</option>
                              <option value="1.3">1.3KM</option>
                              <option value="1.4">1.4KM</option>
                              <option value="1.5">1.5KM</option>
                              <option value="1.6">1.6KM</option>
                              <option value="1.7">1.7KM</option>
                              <option value="1.8">1.8KM</option>
                              <option value="1.9">1.9KM</option>
                              <option value="2.0">2.0KM</option>    
                          </select>
                      </div>
                      <div class="col-sm-6">
                         <label>Get Projects :</label><br>
                          <button type="submit" class="btn btn-success btn-sm"> Submit</button>
                      </div>
                      </td>
                  
                      </tr>
             
              </div>
      </table>
  </div>
</div>
</div>
</form>
</div>
</div>
</div>

<!-- 
   <table class="table">
       <thead>
          <th>slno</th>
          <th>Manufacturer Id</th>
          <th>distance</th>
       </thead>
       <tbody>
        <?php $i=1; ?>
        @foreach($projects as $city)
        <tr>
          @if($city->id  != "")
          <td>{{$i++}}</td>
            <td><a href="{{URL::to('/')}}/viewmanu?id={{$city->id}}" >{{$city->id }} </a>
            </td>
            
            <td>{{$city->distance}}</td>  
            @endif 
        </tr>
        @endforeach
       </tbody>
   </table> -->
  @if(count($projects) > 0))
   
    <div class="col-md-8 col-md-offset-2">
        <div id="map" style="width:100%;height:400px"></div>
    </div>
    @endif
    @if(count($projects) > 0))
    <script type="text/javascript" scr="https://maps.google.com/maps/api/js?sensor=false"></script>

    <script type="text/javascript">
    window.onload = function() {
    var locations = new Array();
    var created = new Array();
    var updated = new Array();
    var status = new Array();
    var newpath = [];
    var mysubpath = [];
    var latlng = [];
    var col = [];
    var places = [];
    var quality = [];
    @if($wardMaps != "None")
        var latlng = "{{ $wardMaps->lat }}";
        var col = "{{ $wardMaps->color }}";
    @else
        var latlng = "";
        var col = "456369"
    @endif
    var places = latlng.split(",");
    for(var i=0;i<places.length;i+=2){
          newpath.push({lat: parseFloat(places[i]), lng: parseFloat(places[i+1])});
    }
    <?php $url = Helpers::geturl(); ?>
    @foreach($projects as $project)
     <?php $x=explode(",",$project->image); 
          
    ?>
      locations.push(["<div class=card style=width:350px>  <img src={{ $url}}/Manufacturerimage/{{ $x[0] }}   style=width:200px height=200px><div class=container><h4>Manufacturer Id : <b><a target=_blank href={{URL::to('/')}}/viewmanu?id={{$project->id}}><b>{{$project->id }}  </b></a></h4><h4>Number:{{$project->proc !=null ? $project->proc->contact : ''}}</h4></h4><p><a href=\"https://maps.google.com/?q={{ $project->address }}\">Address: {{ $project->address }}</p></a></div></div>",{{ $project->latitude}}, {{ $project->longitude }}]);
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
        
            var icon = {
                url: "http://www.free-icons-download.net/images/green-map-marker-icon-50710.png", // url
                scaledSize: new google.maps.Size(50, 50), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
            };
            marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: icon
            });
       
        // else{
        //     marker = new google.maps.Marker({
        //     position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        //     map: map,
        //     icon: 'nothing'
        //     });
        // }
        

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
            infowindow.setContent(locations[i][0]);
            infowindow.open(map, marker);
        }
        })(marker, i));
    }
    var subward = new google.maps.Polygon({
        paths: newpath,
        strokeColor: '#'+col,
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#'+col,
        fillOpacity: 0.35
      });
  subward.setMap(map);
    }
    </script>
    @endif

   
    




<script type="text/javascript" charset="utf-8">


  function getLocation(){
   
    
      document.getElementById("getBtn").className = "hidden";
      console.log("Entering getLocation()");
      if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(
        displayCurrentLocation,
        displayError,
        { 
          maximumAge: 3000, 
          timeout: 5000, 
          enableHighAccuracy: true 
        });
    }else{
      alert("Oops.. No Geo-Location Support !");
     
    } 
  }
    
    function displayCurrentLocation(position){
      var latitude  = position.coords.latitude;
      var longitude = position.coords.longitude;
      document.getElementById("longitude").value = longitude;
      document.getElementById("latitude").value  = latitude;
      getAddressFromLatLang(latitude,longitude);
          
    }
   
  function  displayError(error){
    console.log("Entering ConsultantLocator.displayError()");
    var errorType = {
      0: "Unknown error",
      1: "Permission denied by user",
      2: "Position is not available",
      3: "Request time out"
    };
    var errorMessage = errorType[error.code];
    if(error.code == 0  || error.code == 2){
      errorMessage = errorMessage + "  " + error.message;
    }
    alert("Error Message " + errorMessage);
    console.log("Exiting ConsultantLocator.displayError()");
  }
  function getAddressFromLatLang(lat,lng){
    var geocoder = new google.maps.Geocoder();
    var latLng = new google.maps.LatLng(lat, lng);
    geocoder.geocode( { 'latLng': latLng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[0]) {
        document.getElementById("address").value = results[0].formatted_address;
      }
    }else{
        alert("Geocode was not successful for the following reason: " + status);
     }
    });
  }
</script>

 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGSf_6gjXK-5ipH2C2-XFI7eUxbHg1QTU&callback=myMap"></script>
</body>
</html>
@endsection