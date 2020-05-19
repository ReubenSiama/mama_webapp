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
<form action="{{ URL::to('/') }}/customermap" method="get">
    <div class="col-md-2">
     <!-- <label for="wards">Select Zones:</label> -->
        <!-- <select required name="zone" class="form-control" id="wards">
            @foreach($zone as $zoo)
            <option {{ isset($_GET['zones']) ? $_GET['zones'] == $zone->id ? 'selected' : '' : '' }} value="{{ $zoo->id }}">{{ $zoo->zone_name }}</option>
            @endforeach
        </select> -->
        <label for="wards">Select Type:</label>
        <select name="type" class="form-control" id="" required="required">
            <option>---select---</option>
           
            <option value="Project">Project </option>
            <option value="Manufacturer">Manufacturer</option>
           
        </select>
        <label for="wards">Select Wards:</label>
        <select required name="wards" class="form-control" id="ward"  onchange="loadsubwards()">
            <option>---select---</option>
          
            @foreach($wards as $ward)
            <option {{ isset($_GET['wards']) ? $_GET['wards'] == $ward->id ? 'selected' : '' : '' }} value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
            @endforeach
        </select>
        
            <label>Choose Subward :</label><br>
                      <select name="subward" class="form-control" id="subward">
                      </select>
        
        <label for="quality">Select Quality:</label>
        <select required name="quality" class="form-control" id="quality">
           <option>---select---</option> 
           <option {{ isset($_GET['quality']) ? $_GET['quality'] == "All" ? 'selected' : '' : ''}} value="yup">All</option>
            <option {{ isset($_GET['quality']) ? $_GET['quality'] == "Genuine" ? 'selected' : '' : ''}} value="Genuine">Genuine</option>
            <option {{ isset($_GET['quality']) ? $_GET['quality'] == "Fake" ? 'selected' : '' : ''}} value="Fake">Fake</option>
            <option {{ isset($_GET['quality']) ? $_GET['quality'] == "Unverified" ? 'selected' : '' : ''}} value="Unverified">Unverified</option>
        </select>
        <br>
        <label for="wards">Select Manufacturer Type:</label>
        <?php $manu =['RMC','BLOCKS','M-SAND','AGGREGATES','Fabricators']; ?>
        <select  name="manutype" class="form-control">
            <option>---select---</option>
            <option value="All">All</option>
            @foreach($manu as $manus)
            <option {{ isset($_GET['manus']) ? $_GET['manus'] == $ward->id ? 'selected' : '' : '' }} value="{{ $manus }}">{{ $manus }}</option>
            @endforeach
        </select><br>
        <input type="submit" value="Fetch" class="btn btn-primary form-control">
        <br><br>
        @if(isset($_GET['wards']))
            Total Projects : {{ count($projects) }}<br>
            <br>
            <!-- <button onclick="viewZoneMaps()" type="button" class="btn btn-success form-control">View All Wards Map</button> -->
        @endif
</form>
<form action="{{ URL::to('/') }}/customermap" method="get">
  
   <!-- <label for="wards">Select Zones:</label> -->
      <!-- <select required name="zone" class="form-control" id="wards">
          @foreach($zone as $zoo)
          <option {{ isset($_GET['zones']) ? $_GET['zones'] == $zone->id ? 'selected' : '' : '' }} value="{{ $zoo->id }}">{{ $zoo->zone_name }}</option>
          @endforeach
      </select> -->
      <label for="wards">Select Customer Type:</label>
      <select name="custtype" class="form-control" required="required" id="cust" onchange="loadsubcust()">
          <option>---select---</option>
          <option  value="All">All</option>
          @foreach($customertype as $cust)
          <option value="{{$cust->cust_type_id}}">{{$cust->cust_type}} </option>
          @endforeach

         
      </select>
       <label for="wards">Select Sub Customer Type:</label>
        <select name="subcustname" class="form-control" id="subcust">
                    </select><br>
      <label for="wards">Select Wards:</label>
      <select required name="wards" class="form-control" id="ward1"  onchange="loadsubwards1()">
          <option>---select---</option>
         
          @foreach($wards as $ward)
          <option {{ isset($_GET['wards']) ? $_GET['wards'] == $ward->id ? 'selected' : '' : '' }} value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
          @endforeach
      </select>
      
          <label>Choose Subward :</label><br>
                    <select name="subward" class="form-control" id="subward1">
                    </select><br>

                    <input type="submit" value="Fetch" class="btn btn-primary form-control">
                    <br><br>

  
</form>

</div>




    <div class="col-md-2">
    </div>
    <div class="col-md-10" style="border-style: ridge;">
        <div id="map" style="width:100%;height:600px"></div>
    </div>
    @if(isset($_GET['wards']))
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
    var custid = [];
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
    @foreach($projects as $project)
    <?php $x=explode(",",$project->image); 

          
    ?>
    <?php $url = Helpers::geturl(); ?>
        @if($project->project_id != null)
        locations.push(["<div class=card style=width:350px>  <img src={{ $url}}/projectImages/{{ $x[0] }}   style=width:200px height=200px><div class=container><h4>Project Id : <b><a target=_blank href={{URL::to('/')}}/showThisProject?id={{$project->project_id}}><b>{{$project->project_id }}  </b></a></h4><h4>Number:{{$project->procurementdetails !=null ? $project->procurementdetails->procurement_contact_no : ''}}</h4></h4><p><a href=\"https://maps.google.com/?q={{ $project->address }}\">Address: {{ $project->address }}</p></a></div></div>",{{ $project->latitude}}, {{ $project->longitude }}]);
        created.push("{{ $project->created_at}}");
        updated.push("{{ $project->updated_at}}");
        quality.push("{{ $project->quality }}");
        @elseif($project->customer_id != "")
        locations.push(["<div class=card style=width:350px><div class=container><h4><b>Customer Id :<a href='{{URL::to('/')}}/customerprojects?customer_id={{$project->customer_id }}' style=background-color:blue;color:white; class=btn btn-primary btn-sm >{{$project->customer_id }}</a></b></h4><h4>Name:&nbsp;{{$project->first_name}}</h4><h4>Number:{{$project->mobile_num}}</h4><p><a href=\"https://maps.google.com/?q={{ $project->adddress }}\">Address: {{ $project->adddress }}</p></a></div><p id='demo'></p></div>",{{ $project->latitude}}, {{ $project->longitude }}]);
        created.push("{{ $project->created_at}}");
        updated.push("{{ $project->updated_at}}");
        custid.push("{{$project->customer_id}}");
        @elseif($project->id != null)
        locations.push(["<div class=card style=width:400px><img src={{ $url}}/Manufacturerimage/{{ $x[0] }}  style=width:200px height=200px><div class=container><h4>Manufacturer Id : <b><a target=_blank href={{ URL::to('/') }}/viewmanu?id={{$project->id}}><b>{{$project->id }}</b></a></h4><h4>Number:{{$project->proc !=null ? $project->proc->contact : ''}}</h4><h4>Manufacturer Type : {{$project->manufacturer_type}}</h4><br><p><a href=\"https://maps.google.com/?q={{ $project->address }}\"><h4>Address: {{ $project->address }}</h4></p></a></div></div>",{{ $project->latitude}}, {{ $project->longitude }}]);
        created.push("{{ $project->created_at}}");
        updated.push("{{ $project->updated_at}}");
        quality.push("{{ $project->quality }}");
        @else
         locations.push(["<div class=card style=width:400px><img src={{ $url}}/Manufacturerimage/{{ $x[0] }}  style=width:200px height=200px><div class=container><h4>Manufacturer Id : <b><a target=_blank href={{ URL::to('/') }}/viewmanu?id={{$project->id}}><b>{{$project->id }}</b></a></h4><h4>Number:{{$project->proc !=null ? $project->proc->contact : ''}}</h4><h4>Manufacturer Type : {{$project->manufacturer_type}}</h4><br><p><a href=\"https://maps.google.com/?q={{ $project->address }}\"><h4>Address: {{ $project->address }}</h4></p></a></div></div>",{{ $project->latitude}}, {{ $project->longitude }}]);
        created.push("{{ $project->created_at}}");
        updated.push("{{ $project->updated_at}}");
        quality.push("{{ $project->quality }}");
        @endif
        // alert(quality);
    @endforeach
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: new google.maps.LatLng(locations[0][1], locations[0][2]),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {
            
        if(quality[i] == "Genuine" ){
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
        }
     
        if(quality[i] == "Fake"){
            var icon = {
                url: "https://images.vexels.com/media/users/3/136303/isolated/preview/7dbfd48c913dc03cf834af0525429826-location-marker-icon-by-vexels.png", // url
                scaledSize: new google.maps.Size(50, 50), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
            };
            marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: icon
            });
        }

        if(quality[i] == "Unverified"){
            var icon = {
                url: "https://cdn2.iconfinder.com/data/icons/IconsLandVistaMapMarkersIconsDemo/256/MapMarker_Flag_Right_Chartreuse.png", // url
                scaledSize: new google.maps.Size(50, 50), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
            };
            marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: icon
            });
        }
           if(custid[i] != null){
            var icon = {
                url: "https://www.sccpre.cat/mypng/full/216-2168745_identity-is-verified-know-your-customer-icon.png", // url
                scaledSize: new google.maps.Size(50, 50), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
            };
            marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: icon
            });
        }
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

   
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGSf_6gjXK-5ipH2C2-XFI7eUxbHg1QTU&callback=myMap"></script>
</body>
</html>
<script type="text/javascript">
  function loadsubwards()
  {
      var x = document.getElementById('ward');
      var sel = x.options[x.selectedIndex].value;
      if(sel)
      {
          $.ajax({
              type: "GET",
              url: "{{URL::to('/')}}/loadsubwards",
              data: { ward_id: sel },
              async: false,
              success: function(response)
              {
                  if(response == 'No Sub Wards Found !!!')
                  {
                      document.getElementById('error').innerHTML = '<h4>No Sub Wards Found !!!</h4>';
                      document.getElementById('error').style,display = 'initial';
                  }
                  else
                  {
                      var html = "<option value='' disabled selected>---Select---</option>";
                      html += "<option value='All'>All</option>";
                      for(var i=0; i< response.length; i++)
                      {
                          html += "<option value='"+response[i].id+"'>"+response[i].sub_ward_name+"</option>";
                      }
                      document.getElementById('subward').innerHTML = html;
                  }
                  
              }
          });
      }
  }
</script>
<script type="text/javascript">
  function loadsubwards1()
  {
      var x = document.getElementById('ward1');
      var sel = x.options[x.selectedIndex].value;
     
      if(sel)
      {
          $.ajax({
              type: "GET",
              url: "{{URL::to('/')}}/loadsubwards",
              data: { ward_id: sel },
              async: false,
              success: function(response)
              {
                  if(response == 'No Sub Wards Found !!!')
                  {
                      document.getElementById('error').innerHTML = '<h4>No Sub Wards Found !!!</h4>';
                      document.getElementById('error').style,display = 'initial';
                  }
                  else
                  {
                      var html = "<option value='' disabled selected>---Select---</option>";
                      html += "<option value='All'>All</option>";
                      for(var i=0; i< response.length; i++)
                      {
                          html += "<option value='"+response[i].id+"'>"+response[i].sub_ward_name+"</option>";
                      }
                      document.getElementById('subward1').innerHTML = html;
                  }
                  
              }
          });
      }
  }
</script>
<script type="text/javascript">
  function loadsubcust()
  {
      var x = document.getElementById('cust');
      var sel = x.options[x.selectedIndex].value;
    
      if(sel)
      {
          $.ajax({
              type: "GET",
              url: "{{URL::to('/')}}/loadsubcust",
              data: { ward_id: sel },
              async: false,
              success: function(response)
              {
                console.log(response);
                  if(response == 'No Sub customers  Found !!!')
                  {
                      document.getElementById('error').innerHTML = '<h4>No Sub customers Found !!!</h4>';
                      document.getElementById('error').style,display = 'initial';
                  }
                  else
                  {
                      var html = "<option value='' disabled selected>---Select---</option>";
                      html += "<option value='All'>All</option>";
                         
                      for(var i=0; i< response.length; i++)
                      {
                          html += "<option value='"+response[i].id+"'>"+response[i].cust_type+"</option>";
                      }
                      document.getElementById('subcust').innerHTML = html;
                  }
                  
              }
          });
      }
  }
</script>

@endsection
