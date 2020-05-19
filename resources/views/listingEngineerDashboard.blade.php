@extends('layouts.leheader')
@section('content')

  @if($ldate < $lodate)
  <div>You are ahead of time.</div>
  @elseif($ldate > $outtime)
  <div>You are done for today. Take a rest.</div>
  @else

<div class="container">
    <div class="row">
      
      @if($subwards)
      <div class="col-md-3"> 
         You are in {{$subwards->sub_ward_name}}<br><br>
        @if(Auth::user()->group_id == 6 && Auth::user()->department_id == 1)
       @if($log != 0)
             
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/listingEngineer">Add New Project</a><br><br>
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/roads"> Projects</a><br><br>
         <!-- <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/requirementsroads">Add New Enquiry</a><br><br> -->
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/addManufacturer">Add New Manufacturer</a><br><br>
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/updateManufacturer">Update Manufacturer</a><br><br>
          <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/manu_map">Manufacturers Map</a><br><br>
         <a class="btn btn-primary form-control" href="{{ URL::to('/customer') }}">Assigned Customers</a><br><br>
          <a class="btn btn-primary form-control" href="{{ URL::to('/customermanu') }}">Assigned Manufacturer Customers</a><br><br>
          <a class="btn btn-primary form-control" href="{{ URL::to('/lcoorders') }}">Assigned Logistics</a><br><br>
          <a href="{{ URL::to('/') }}/manuenquiry" class="btn btn-primary form-control">Add Manufacturer Enquiry</a><br><br>
          <a href="{{ URL::to('/') }}/inputview" class="btn btn-primary form-control">Add Project Enquiry</a><br><br>
  <ul class="nav nav-tabs">
    <li class="dropdown">
      <a class="dropdown-toggle btn btn-primary form-control" data-toggle="dropdown" href="#">Dedicated Customers Details <span class="caret"></span></a><br><br>
      <ul class="dropdown-menu">
         <li><a  href="{{ URL::to('/')}}/dCustomers" class="btn btn-primary form-control" >Customer Deatils</a></li>
         <li><a  href="{{ URL::to('/')}}/dnumbers" class="btn btn-primary form-control" >SMS Numbers</a></li>
         <li><a  href="{{ URL::to('/')}}/dprojects" class="btn btn-primary form-control" >Projects</a></li>
         <li><a  href="{{ URL::to('/')}}/dmanus" class="btn btn-primary form-control" >Manufacturers</a></li>
         <li> <a  href="{{ URL::to('/')}}/denquery" class="btn btn-primary form-control" >Enquiries</a> </li>                 
      </ul>
    </li>
  </ul>

         @endif
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/lebrands">Brands</a><br><br>
          <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/allprice">Brands With Prices</a><br><br>
        <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/lcoorders">Orders</a><br><br>
        <!-- <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/public/subWardImages/{{$subwards->sub_ward_image }}"> SubWard image</a><br><br><!--  -->
         @elseif(Auth::user()->group_id == 1 && Auth::user()->department_id == 0)
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/listingEngineer">Add New Project</a><br><br>
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/roads"> Projects</a><br><br>
         <!-- <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/requirementsroads">Add New Enquiry</a><br><br> -->
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/addManufacturer">Add New Manufacturer</a><br><br>
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/updateManufacturer">Update Manufacturer</a><br><br>
          <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/manu_map">Manufacturers Map</a><br><br>
         <a class="btn btn-primary form-control" href="{{ URL::to('/customer') }}">Assigned Customers</a><br><br>
          <a class="btn btn-primary form-control" href="{{ URL::to('/customermanu') }}">Assigned Manufacturer Customers</a><br><br> 
          <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/lebrands">Brands</a><br><br>
        <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/lcoorders">Orders</a><br><br>
          <a href="{{ URL::to('/') }}/manuenquiry" class="btn btn-primary form-control">Add Manufacturer Enquiry</a><br><br>

          <a href="{{ URL::to('/') }}/inputview" class="btn btn-primary form-control">Add Project Enquiry</a><br><br>
          
         
        @elseif(Auth::user()->group_id == 11 && Auth::user()->department_id == 2)
          <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/accountlistingEngineer">Add New Project</a><br><br>
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/accountroads">Projects</a><br><br>
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/accountrequirementsroads">Project Enquiry</a><br><br>
         <!-- <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/accountreports">My Report</a><br><br> -->
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/lebrands">Brands</a><br><br>
         <a class="btn btn-primary form-control" href="{{ URL::to('/customer') }}">Assigned Customers</a><br><br>
          <a class="btn btn-primary form-control" href="{{ URL::to('/customermanu') }}">Assigned Manufacturer Customers</a><br><br>

         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/lcoorders">Orders</a><br><br>
        <!--  <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/public/subWardImages/{{$subwards->sub_ward_image }}"> SubWard image</a><br><br> -->
        <a href="{{ URL::to('/') }}/kra" class="form-control btn btn-primary">KRA</a><br><br>
       <!--  <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/public/subWardImages/{{$subwards->sub_ward_image }}"> SubWard image</a><br><br> -->
        <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/projectsUpdate" id="updates">Account Executive Projects</a><br><br>  
        <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/addManufacturer">Add New Manufacturer</a><br><br>
         <a class="btn btn-primary form-control" href="{{ URL::to('/')}}/updateManufacturer">Update Manufacturer</a><br><br>
    <a class="btn btn-primary form-control" href="{{ URL::to('/') }}/scmaps">Maps</a>
          <a href="{{ URL::to('/') }}/manuenquiry" class="btn btn-primary form-control">Add Manufacturer Enquiry</a><br><br>

          <a href="{{ URL::to('/') }}/inputview" class="btn btn-primary form-control">Add Project Enquiry</a><br><br>
             
</div>

          @endif
          <br><br>
         <table class="table table-responsive table-striped table-hover" style="border: 2px solid gray;">
          <tbody >
                <!-- <tr>
                  <td style="border: 1px solid gray;"> <label>Total Number of Projects Listed till nOw</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $numbercount }}</strong></td>
                </tr> -->
                <tr>
                  <td style="border: 1px solid gray;"> <label>Total Number of Projects Listed in Last 30Days</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $lastmonth}}</strong></td>
                </tr>
                <tr>  
                  <td style="border: 1px solid gray;"><label>Total Number of Projects Listed Today</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $total }}</strong></td>
                </tr>
                <tr>
                  <td style="border: 1px solid gray;"><label>Total Number of Enquiries Initiated in Last 30Days</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $ordersInitiated }}</strong></td>
                </tr>
                <tr>
                  <td style="border: 1px solid gray;"><label>Total Number of Enquiries Confirmed in Last 30Days</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $ordersConfirmed }}<strong></td>
                </tr>
          </tbody>
        </table>
         @if(Auth::user()->group_id == 6 && Auth::user()->department_id == 1)
        <!--  <table  class="table table-responsive table-striped table-hover" style="border: 2px  solid gray;">
          <tbody>
            <thead>
              <th style="text-align: center;" colspan="2">Total Listings</th>
          
            </thead>
         
            @foreach($users as $user)
              <tr>
                  <td style="border: 1px solid gray;"><label>{{ $user->name }}</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $totalListing[$user->id] }}</strong></label></td>
              </tr>
            @endforeach
          </tbody>
        </table> -->
        @endif

         
        <!-- <table class="table table-responsive table-striped table-hover" style="border: 2px solid gray;">
          <tbody > -->
                <!-- <tr>
                  <td style="border: 1px solid gray;"> <label>Total Number of Projects Listed till nOw</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $numbercount }}</strong></td>
                </tr> -->
               <!--  <tr>
                  <td style="border: 1px solid gray;"> <label>TOtal number of projects in {{$subwards->sub_ward_name}}</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $totalprojects}}</strong></td>
                </tr>
                <tr>  
                  <td style="border: 1px solid gray;"><label>Genuine Projects</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $genuineprojects }}</strong></td>
                </tr>
                <tr>
                  <td style="border: 1px solid gray;"><label>Unverified Projects</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $unverifiedprojects }}</strong></td>
                </tr>
                <tr>
                  <td style="border: 1px solid gray;"><label>Fake Projects</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $fakeprojects }}<strong></td>
                </tr>
                <tr>
                  <td style="border: 1px solid gray;"><label>Last 30 Days Updated Projects</label></td>
                  <td style="border: 1px solid gray;"><strong>{{ $update  }}<strong></td>
                </tr>
                <tr>
                  <td style="border: 1px solid gray;"><label>remaining Projects</label></td>
                  <td style="border: 1px solid gray;">{{ $bal }}<strong><strong></td>
                </tr>
          </tbody>
        </table> -->
        @else
        <div style="text-align: center;">
          <div class="col-md-6 col-md-offset-3">
            <p style="font-size: 25px;padding-top: 70px;color:gray;">Ward is not assigned.Contact Your teamleader</p>
          </div>
        </div>
        @endif
       </div>
        <div class="col-md-8"><br><br>
     
      <div id="map" style="width:900px;height:500px"></div>
      </div>

    </div>
    <!-- <div class="row hidden">
      <div class="col-md-4 col-md-offset-4">
        <table class="table table-hover" border=1>
        <center><label for="Points">Your Points For Today</label></center>
          <thead>
            <th>Reason For Earning Point</th>
            <th>Point Earned</th>
          </thead>
          <tbody>
            @foreach($points_indetail as $points)
            <tr>
              <td>{!! $points->reason !!}</td>
              <td style="text-align: right">{{ $points->type == "Add" ? "+".$points->point : "-".$points->point }}</td>
            </tr>
            @endforeach
            <tr>
              <td style="text-align: right;"><b>Total</b></td>
              <td style="text-align: right">{{ $total }}</td>
            </tr>
          </tbody>
        </table>
        </div>
    </div> -->
 <!-- Modal -->
     
</div>
<div id="question" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Please Choose</h4>
      </div>
      <div class="modal-body">
        <p>What kind of manufacturer are you adding?</p><br>
        <a class="btn btn-success" href="{{ URL::to('/')}}/addManufacturer?type=blocks">Blocks</a>
        <a class="btn btn-warning pull-right" href="{{ URL::to('/')}}/addManufacturer?type=rmc">&nbsp;RMC&nbsp;&nbsp;</a><br><br>
      </div>
    </div>

  </div>
</div>
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
    <?php $url = Helpers::geturl(); ?>
    @foreach($projects as $project)
     <?php $x=explode(",",$project->image); 
          
    ?>
      locations.push(["<div class=card style=width:350px>  <img src={{ $url}}/projectImages/{{ $x[0] }}   style=width:200px height=200px><div class=container><h4>Project Id : <b><a target=_blank href={{URL::to('/')}}/showThisProject?id={{$project->project_id}}><b>{{$project->project_id }}  </b></a></h4><h4>Number:{{$project->procurementdetails !=null ? $project->procurementdetails->procurement_contact_no : ''}}</h4></h4><p><a href=\"https://maps.google.com/?q={{ $project->address }}\">Address: {{ $project->address }}</p></a></div></div>",{{ $project->latitude}}, {{ $project->longitude }}]);
      created.push("{{ $project->created_at}}");
      updated.push("{{ $project->updated_at}}");
      status.push("{{ $project->status }}");
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
    }else if(status[i] == "Order Confirmed"){
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'
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

<script>

</script>
@endif
<script>
  function vali(arg){

    alert("Please Update The Remaing Projects:" +arg);

  }


</script>
@endsection


