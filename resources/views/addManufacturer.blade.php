<!DOCTYPE html>
<html>
<head>
  <title>Add Manufacturer</title>
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
 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
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
<script type="text/javascript"> 
var map;
var marker;
var myLatlng = new google.maps.LatLng(12.979690500000002,77.61923349999999);
var geocoder = new google.maps.Geocoder();
var infowindow = new google.maps.InfoWindow();
function initialize(){
var mapOptions = {
zoom:10,
center: myLatlng,
mapTypeId: google.maps.MapTypeId.ROADMAP
};

map = new google.maps.Map(document.getElementById("myMap"), mapOptions);

marker = new google.maps.Marker({
map: map,
position: myLatlng,
draggable: true 
}); 

geocoder.geocode({'latLng': myLatlng }, function(results, status) {
if (status == google.maps.GeocoderStatus.OK) {
if (results[0]) {
$('#latitude,#longitude').show();
$('#address').val(results[0].formatted_address);
$('#latitude').val(marker.getPosition().lat());
$('#longitude').val(marker.getPosition().lng());
infowindow.setContent(results[0].formatted_address);
infowindow.open(map, marker);
}
}
});

google.maps.event.addListener(marker, 'dragend', function() {

geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
if (status == google.maps.GeocoderStatus.OK) {
if (results[0]) {
$('#address').val(results[0].formatted_address);
$('#latitude').val(marker.getPosition().lat());
$('#longitude').val(marker.getPosition().lng());
infowindow.setContent(results[0].formatted_address);
infowindow.open(map, marker);
}
}
});
});

}
google.maps.event.addDomListener(window, 'load', initialize);
</script>

</head>
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
    <!-- <center><a href="{{ URL::previous()  }}" class="btn btn-danger">Back</a></center><br> -->
            <form action="{{ URL::to('/') }}/saveManufacturer" onsubmit="return validate()" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-default">
               <div class="panel-heading" style="background-color:#42c3f3;padding:20px;">
                 
                            <div id="currentTime" class="pull-right" style="color:white;margin-top:-5px;"></div>
                             <div  class="pull-left" style="color:white;margin-top:-5px;">Assigned ward is : {{ $subwards->sub_ward_name ?? '' }}</div>
                            
                        </div>
                        <div class="panel-body">
                       
                  @if(Auth::user()->group_id == 1|| Auth::user()->group_id == 2)

                          <div id="myMap" style="width:700px;height:250px;object-fit: cover;"></div>
                          @endif
               <center> <label id="headingPanel"> Manufacturer Details</label></center><br>
               <center> <button type="button" id="getBtn"  class="btn btn-success btn-sm " onclick="getLocation()">Get Location</button></center><br>
                            <table class="table table-hover">
                                <tr>
                                    <td>Manufacturer Type</td>
                                    <td>:</td>
                                    <td>
                                        <select required onchange="hideordisplay(this.value);" name="type" id="type" class="form-control">
                                            <option value="">--Select--</option>
                                            <option value="RMC">RMC</option>
                                            <option value="Blocks">BLOCKS</option>
                                            <option value="M-Sand">M-SAND</option>
                                            <option value="AGGREGATES">AGGREGATES</option>
                                            <option value="Fabricators">Fabricators</option>
                                            <option value="RingandPavers">Ring and Pavers</option>
                                            
                                            
                                        </select>
                                    </td>
                                </tr>
                                <input type="hidden" name="subward_id" id="subwardid" >
                                <tr>
                                    <td>Production Type</td>
                                    <td>:</td>
                                    <td>
                                 <label  class="checkbox-inline"><input id="constructionType1" name="production[]" type="checkbox" value="RMC">RMC </label>
                                    <label  class="checkbox-inline"><input id="constructionType2" name="production[]" type="checkbox" value="BLOCKS">BLOCKS</label> 
                                  <label  class="checkbox-inline"><input id="constructionType2" name="production[]" type="checkbox" value="M-SAND">M-SAND</label> 
                                      <label  class="checkbox-inline"><input id="constructionType2" name="production[]" type="checkbox" value="AGGREGATES">AGGREGATES</label> 
                                        <label  class="checkbox-inline"><input id="constructionType2" name="production[]" type="checkbox" value="Fabricators">FABRICATORS</label> 
                                    </td>
                                </tr>
                                <tr>
                                    <td>Plant Name</td>
                                    <td>:</td>
                                    <td>
                                        <input required placeholder="Plant Name" type="text" name="plant_name" id="name" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                   <td>Location</td>
                                   <td>:</td>
                                   <td id="x">
                                    <div class="col-sm-6">
                                      <label>Longitude:</label>
                                        <input placeholder=" Latitude" class="form-control input-sm" required readonly type="text" name="longitude" value="{{ old('longitude') }}" id="longitude">
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Latitude:</label>
                                        <input placeholder="Longitude" class="form-control input-sm" required readonly type="text" name="latitude" value="{{ old('latitude') }}" id="latitude">
                                    </div>
                                   </td>
                               </tr>
                                        <tr>
                                    <td>Road With</td>
                                    <td>:</td>
                                    <td>
                                        <input  required placeholder="roadwidth" type="text" name="roadwidth" id="roadwidth" class="form-control">

                                    </td>
                                </tr>
                                <tr>
                                    <td>Road Name</td>
                                    <td>:</td>
                                    <td>
                                        <input  required placeholder="Road Name" type="text" name="roadname" id="name" class="form-control">

                                    </td>
                                </tr>
                                <tr>
                                    <td>Cement Storage Capacity(Bags)</td>
                                    <td>:</td>
                                    <td>
                                        <input required placeholder="storage capacity" type="text" name="storage" id="storage" class="form-control">

                                    </td>
                                </tr>
                                   <tr>
                                   <td>Manufacturer Images</td>
                                   <td>:</td>
                                   <td><input id="pImage" oninput="fileuploadimage()" required type="file" accept="image/*" class="form-control input-sm" name="pImage[]" onchange="validateFileType()" multiple><p id="errormsg"></p></td>
                               </tr>
                                <tr>
                                   <td>Business card  Images</td>
                                   <td>:</td>
                                   <td><input id="pImage" oninput="fileuploadimage()" required type="file" accept="image/*" class="form-control input-sm" name="bImage[]" onchange="validateFileType()" multiple><p id="errormsg"></p></td>
                               </tr>
                              

                                <tr>
                                    <td>Address</td>
                                    <td>:</td>
                                    <td>
                                        <input required placeholder="Address" type="text" name="address" id="address" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Area(Sqft)</td>
                                    <td>:</td>
                                    <td>
                                        <input required placeholder="Total Area" min="0" type="number" name="total_area" id="area" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Production Capacity (Per Day)</td>
                                    <td>:</td>
                                    <td>
                                        <input placeholder="Production Capacity (Per Day)" min="0" type="number" name="capacity" id="capacity" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                 <td> Do You Have Silo Facility ?</td>
                                 <td>:</td>
                                 <td>
                                     
                                      <label ><input required value="Yes" id="rmc" type="radio" name="silo"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                      <label ><input required value="No" id="rmc2" type="radio" name="silo"><span>&nbsp;</span>No</label> 
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                      <label><input checked="checked" value="None" id="rmc3" type="radio" name="silo"><span>&nbsp;</span>None</label>
                                 </td>
                               </tr>
                                @if(Auth::user()->group_id != 6 || Auth::user()->group_id != 7)
                                 <tr>
                                   <td>Reference Customer Id / Number</td>
                                   <td>:</td>
                                   <td><input  onkeyup="getcustomerid()"   type="text" placeholder="Enter customer id or Number" class="form-control input-sm" name="cid"  id="mid"  >
                                  <p id="cids">
                                    
                                  </p></td>
                               </tr>
                               @endif

                                <tr>
                                    <td>Quantity Of Cement Required <br>(Per Month)</td>
                                    <td>:</td>
                                    <td>
                                        <div class="col-md-6 radio">
                                            <label for="tons"><input type="radio" value="Tons" checked="true" name="cement_required" id="tons">Tons</label>&nbsp;&nbsp;
                                            <label for="bags"><input type="radio" value="Bags" name="cement_required" id="bags">Bags</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input placeholder="Cement Required" min="0" type="number" name="cement_requirement" id="cement_requirement" class="form-control">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>M-Sand Required(Tons per Month)</td>
                                    <td>:</td>
                                    <td>
                                        <input  placeholder="M-Sand Required" min="0" type="number" name="sand_requirement" id="sand_requirement" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Aggregates Required(Tons per Month)</td>
                                    <td>:</td>
                                    <td>
                                        <input placeholder="Aggregates Required" min="0" type="number" name="aggregate_requirement" id="aggregate_requirement" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Intrested Cement Brands</td>
                                    <td>:</td>
                                    <td>
                                        <input placeholder="Intrested Cement Brands" type="text" name="brand" id="brand" class="form-control">
                                    </td>
                                </tr>
                                
                                <tr>
                                 <td>Interested In GGBS?</td>
                                 <td>:</td>
                                 <td>
                                    
                                      <label><input required value="Yes" id="dandw1" type="radio" name="ggbss"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                   
                                      <label><input required value="No" id="dandw2" type="radio" name="ggbss"><span>&nbsp;</span>No</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                   
                                      <label><input checked="checked" required value="None" id="dandw3" type="radio" name="ggbss"><span>&nbsp;</span>None</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                 </td>
                               </tr>
                                 <tr>
                                 <td>Interested In CCTV?</td>
                                 <td>:</td>
                                 <td>
                                   
                                      <label><input required value="Yes" id="dandw1" type="radio" name="cctv"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                   
                                      <label><input required value="No" id="dandw2" type="radio" name="cctv"><span>&nbsp;</span>No</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                   
                                      <label><input checked="checked"  required value="None" id="dandw3" type="radio" name="cctv"><span>&nbsp;</span>None</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                 </td>
                               </tr>
                               <tr>
                                 <td>Interested In Chemical?</td>
                                 <td>:</td>
                                 <td>
                                   
                                      <label><input required value="Yes" id="dandw1" type="radio" name="chemical"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                   
                                      <label><input required value="No" id="dandw2" type="radio" name="chemical"><span>&nbsp;</span>No</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                   
                                      <label><input checked="checked"  required value="None" id="dandw3" type="radio" name="chemical"><span>&nbsp;</span>None</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                 </td>
                               </tr>
                             </table>

                                <table id="myTable" class="table order-list" border="1" style="width:100%">
                             <h3>   <center>   Using Cement Brand</center></h3>
    <thead>
        <tr>
            
            <th>Brand</th>
           
            <th>Quantity</th>
            <th>Price</th>
             <th>Supplier Name</th>
             <th>min quantity  purchase at a time?(Bags) </th>
            <th> <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#flipFlop" >Add New Brand </button>

              

                

            </th>
        </tr>
    </thead>
    <tbody>

        <tr>
        
         
            
          <td><a class="deleteRow"></a></td>
 

        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="1" style="text-align:center;">
                <input type="button" class="btn btn-sm btn-block  btn-danger" id="addrow" value="Add Row" />
            </td>
        </tr>
        
    </tfoot>

</table>  
<table class="table table-hover">

</tr>
                      

                            
                                <!-- <tr id="blockTypes1" class="hidden">
                                    <td style="background-color:#cfedaa; text-align:center" colspan=3>Block Types</td>
                                </tr>
                                <tr id="blockTypes2" class="hidden">
                                    <td colspan=3> -->
                                       <!--  <table class="table table-hover" id="types">
                                            <tr>
                                                <th style="text-align:center">Block Type</th>
                                                <th style="text-align:center">Block Size</th>
                                                <th style="text-align:center">Price</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <select title='Please Select Appropriate Type' name="blockType[]" id="bt" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="Concrete">Concrete</option>
                                                        <option value="Cellular">Cellular</option>
                                                        <option value="Light Weight">Light Weight</option>
                                                        <!-- <option value="">All</option> -->
                                                    <!-- </select>
                                                </td>
                                                <td>
                                                    <select title='Please Select Appropriate Size' name="blockSize[]" id="bs" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="4 inch">4 inch</option>
                                                        <option value="6 inch">6 inch</option>
                                                        <option value="8 inch">8 inch</option>
                                                        <option value="12 inch">12 inch</option>
                                                        <!-- <option value="">All</option> -->
                                                    <!-- </select>
                                                </td>
                                                <td>
                                                    <input min="1" type="number" name="price[]" id="bp" placeholder="Price" class="form-control">
                                                </td>
                                            </tr>
                                        </table> --> 
                                            <!-- <div class="btn-group">
                                                <button type="button" onclick="myFunction()" class="btn btn-warning btn-sm">
                                                    &nbsp; <span class="glyphicon glyphicon-plus"></span>&nbsp;
                                                </button>
                                                <button type="button" onclick="myDelete()" class="btn btn-danger btn-sm">
                                                    &nbsp; <span class="glyphicon glyphicon-minus"></span>&nbsp;
                                                </button>
                                            </div> -->
                                    </td>
                                </tr>
                               
                                            
                                        </table>
                                            <div class="btn-group">
                                                <button type="button" onclick="addRMC()" class="btn btn-warning btn-sm">
                                                    &nbsp; <span class="glyphicon glyphicon-plus"></span>&nbsp;
                                                </button>
                                                <button type="button" onclick="RMC()" class="btn btn-danger btn-sm">
                                                    &nbsp; <span class="glyphicon glyphicon-minus"></span>&nbsp;
                                                </button>
                                            </div>
                                    </td>
                                </tr>




                             <tr id="fab1" class="hidden">
                                    <td style="background-color:#cfedaa; text-align:center" colspan=3> Fabricators Manufactured</td>
                                </tr>
                                <tr id="fab2" class="hidden">
                                    <td colspan=3>
                                        <table class="table table-hover" id="fabc">
                                            <tr>
                                                <th style="text-align:center"> Fabricators Type</th>
                                                <!-- <th style="text-align:center">Grade Size</th> -->
                                                <th style="text-align:center">Price</th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <select title='Please Select Appropriate Type' name="fab[]" id="gt" class="form-control">
                                                        <option value="">--Select--</option>
                                                        <option value="metal">Metal</option>
                                                        <option value="wood">Wood</option>
                                                        <option value="upvc">UPVC</option>
                                                      
                                                    </select>
                                                </td>
                                                <td>
                                                    <input min="1" type="number" name="fabprice[]" id="gp" placeholder="Price" class="form-control">
                                                </td>
                                            </tr>
                                            
                                        </table>
                                            <div class="btn-group">
                                                <button type="button" onclick="addfab()" class="btn btn-warning btn-sm">
                                                    &nbsp; <span class="glyphicon glyphicon-plus"></span>&nbsp;
                                                </button>
                                                <button type="button" onclick="fab()" class="btn btn-danger btn-sm">
                                                    &nbsp; <span class="glyphicon glyphicon-minus"></span>&nbsp;
                                                </button>
                                            </div>
                                    </td>
                                </tr>

<!--  <tr id="mfType" class="hidden">
                                    <td>Blocks Manufacturing Type</td>
                                    <td>:</td>
                                    <td>
                                        <div class="radio">
                                            <label><input type="radio" name="manufacturing_type" value="Manual" id="manual">Manual</label>
                                        </div>
                                        <div class="radio">
                                            <label><input type="radio" name="manufacturing_type" value="Machine" id="machine">Machine</label>
                                        </div>
                                    </td>
                                </tr> -->
                                <tr id="moq" class="hidden">
                                    <td>MOQ For Free Pumping (CUM)</td>
                                    <td>:</td>
                                    <td>
                                        <input type="number" min="1" name="moq" id="moq2" placeholder="MOQ For Free Pumping (CUM)" class="form-control">
                                    </td>
                                </tr>




                            </table>
                            <div class="tab"  id="second" style="overflow: hidden;
    border: 1px solid #ccc;
    background-color:#42c3f3;
   ">
  <button style="background-color: inherit;
    
    border: none;
    outline: none;
    cursor: pointer;
    padding: 12px 16px;
    transition: 0.3s;
    font-size: 17px;
     color:white;"  class="tablinks" onclick="openCity(event, 'owner')">Owner Details</button><br>
      <button style="background-color: inherit;
    
    border: none;
    outline: none;
    cursor: pointer;
    padding: 12px 16px;
    transition: 0.3s;
    font-size: 17px;
     color:white;" class="tablinks" onclick="openCity(event, 'contractor')">Manager Details  </button><br>

<!--   <button style="background-color: inherit;
    
    border: none;
    outline: none;
    cursor: pointer;
    padding: 12px 16px;
    transition: 0.3s;
    font-size: 17px;
     color:white;" class="tablinks" onclick="openCity(event, 'site')">Site Engineer Details</button><br> -->
  <button style="background-color: inherit;
    
    border: none;
    outline: none;
    cursor: pointer;
    padding: 12px 16px;
    transition: 0.3s;
    font-size: 17px;
     color:white;" class="tablinks" onclick="openCity(event, 'procurement')">Procurement Details</button><br>
<!-- 
<button style="background-color: inherit;
    
    border: none;
    outline: none;
    cursor: pointer;
    padding: 12px 16px;
    transition: 0.3s;
    font-size: 17px;
     color:white;" class="tablinks" onclick="openCity(event, 'Builder')">Builder Details</button>
</div> -->
</div>

<div id="owner" class="tabcontent" style="display: none;padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;">
    <br>
  <center><label>Owner Details</label></center>
  <br>
                           <table class="table" border="1">
                               <tr>
                                   <td>Owner Name</td>
                                   <td>:</td>
                                   <td><input value="{{ old('oName') }}" type="text" placeholder="Owner Name" class="form-control input-sm" name="oName" id="oName"></td>
                               </tr>
                               <tr>
                                   <td>Owner Email</td>
                                   <td>:</td>
                                   <td><input value="{{ old('oEmail') }}" onblur="checkmail('oEmail')" placeholder="Owner Email" type="email"  class="form-control input-sm" name="oEmail" id="oEmail"></td>
                               </tr>
                               <tr>
                                   <td>Owner Contact No 1.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('oContact') }}" onblur="checklength('oContact');" onkeyup="check('oContact','1')" maxlength="10"  minlength="10" placeholder="Owner Contact No 1." type="text" class="form-control input-sm" name="oContact" id="oContact"></td>
                               </tr>
                                <tr>
                                   <td>Owner Contact No 2.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('oContact') }}" onblur="checklength('oContact');" onkeyup="check('oContact','1')" maxlength="10"  minlength="10" placeholder="Owner Contact No 2." type="text" class="form-control input-sm" name="oContact1" id="oContact"></td>
                               </tr>
                               <tr>
                                   <td>Owner WhatsApp No .</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('oContact') }}" onblur="checklength('oContact');" onkeyup="check('oContact','1')" maxlength="10"  minlength="10" placeholder="Owner WhatsApp No 2." type="text" class="form-control input-sm" name="owhatsapp" id="oContact"></td>
                               </tr>
                           </table>
</div>
<div id="contractor" class="tabcontent" style="display: none;padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;"><br>
   <center><label>Manager Details</label></center>
   <br>
                           <table class="table"  border="1">
                               <tr>
                                   <td>Manager Name</td>
                                   <td>:</td>
                                   <td><input value="{{ old('cName') }}" type="text" placeholder="Manager Name" class="form-control input-sm" name="cName" id="cName"></td>
                               </tr>
                               <tr>
                                   <td>Manager Email</td>
                                   <td>:</td>
                                   <td><input value="{{ old('cEmail') }}" placeholder="Manager Email" type="email" class="form-control input-sm" name="cEmail" id="cEmail" onblur="checkmail('cEmail')" ></td>
                               </tr>
                               <tr>
                                   <td>Manager Contact No 1.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('cContact') }}" onblur="checklength('cPhone');" id="cContact" onkeyup="check('cPhone','1')" placeholder="Manager Contact No." type="text" maxlength="10" class="form-control input-sm" name="cContact"></td>
                               </tr>
                                <tr>
                                   <td>Manager Contact No 2.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('cContact') }}" onblur="checklength('cPhone');" id="cContact" onkeyup="check('cPhone','1')" placeholder="Manager Contact No." type="text" maxlength="10" class="form-control input-sm" name="cContact1"></td>
                               </tr>
                                <tr>
                                   <td>Manager WhatsApp No .</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('cContact') }}" onblur="checklength('cPhone');" id="cContact" onkeyup="check('cPhone','1')" placeholder="Manager WhatsApp No." type="text" maxlength="10" class="form-control input-sm" name="mwhatsapp"></td>
                               </tr>
                           </table>
</div>


<div id="procurement" class="tabcontent" style="display: none;padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;"><br>
   <center><label>Procurement Details</label></center><br>
                           <table class="table"  border="1">
                               <tr>
                                   <td>Procurement Name</td>
                                   <td>:</td>
                                   <td><input id="prName" required type="text" placeholder="Procurement Name" class="form-control input-sm" name="prName" value="{{ old('prName') }}"></td>
                               </tr>
                               <tr>
                                   <td>Procurement Email</td>
                                   <td>:</td>
                                   <td><input value="{{ old('pEmail') }}" placeholder="Procurement Email" type="email" class="form-control input-sm" name="pEmail" id="pEmail" onblur="checkmail('pEmail')" ></td>
                               </tr>
                               <tr>
                                   <td>Procurement Contact No 1.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('pContact') }}" required  minlength=10 onblur="checklength('prPhone');" required placeholder="Procurement Contact No." type="text" class="form-control input-sm" name="prPhone" maxlength="10" id="prPhone" onkeyup="check('prPhone','1')"></td>
                               </tr>
                                <tr>
                                   <td>Procurement Contact No 2.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('pContact') }}"   minlength=10 onblur="checklength('prPhone');" placeholder="Procurement Contact No." type="text" class="form-control input-sm" name="prPhone1" maxlength="10" id="prPhone1" onkeyup="check('prPhone','1')"></td>
                               </tr>
                                <tr>
                                   <td>Procurement WhatsApp No .</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('pContact') }}"   minlength=10 onblur="checklength('prPhone');" placeholder="Procurement WhatsApp No." type="text" class="form-control input-sm" name="pwhatsapp" maxlength="10" id="prPhone1" onkeyup="check('prPhone','1')"></td>
                               </tr>
                           </table>
</div>

                        <table class="table table-responsive" >
                          <tr>
                            <td>Remarks</td>
                            <td>:</td>
                            <td>
                          <textarea style="resize: none;" class="form-control" placeholder="Remarks (Optional)"  name="remarks"></textarea>
                          </td>
                        </tr>
                        </table>

                        </div>
                        <div class="panel-footer">
                            <button type="submit" class="btn btn-success form-control" onclick="pageNext()">Save</button>
                        </div>
                    </div>
                </div>
            </form>
<div class="modal fade" id="flipFlop" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                              <div class="modal-content">
                              <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                              </button>
                              <h4 class="modal-title" id="modalLabel">Add New Brand</h4>
                              </div>
                              <div class="modal-body">
                                 <form action="{{URL::to('/')}}/addBrand" method="post" id="yadav"> 
                                    {{ csrf_field() }}

                                    <input type="hidden" name="cat" value="36">
                                    <label>Enter Brand Name
                                      
                                    <input type="text" name="brand" class="form-control">
                                    </label>
                                   <button class="btn btn-sm btn-warning" type="submit" onclick="document.getElementById('yadav').submit();">Add</button>
                                 </form>
                              </div>
                              <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              </div>
                              </div>
                              </div>
                              </div> 
          
<script type="text/javascript">
function getcustomerid(){
        
      var c = document.getElementById('mid').value;

        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getcustomername",
            async:false,
            data:{cat : c},
            success: function(response)
            {
                console.log(response);
                
                document.getElementById('cids').innerHTML ="<br>Name&nbsp;&nbsp;"+response.name+"<br>Customer Id &nbsp;&nbsp; "+response.id;
                $("body").css("cursor", "default");
            }
        });
      
    }
</script>
<script>
    function pageNext(){
      if(document.getElementById('type').value == ""){
        swal("You Have Not Selected Manufacturing Type");
      }else if(document.getElementById('name').value == ""){
        swal("You Have Not Entered the Plant Name")
      }else if(document.getElementById('longitude').value == ""){
        swal("Please click The Location Button")
      }
      else if(document.getElementById('area').value == ""){
        swal("You Have Not Entered the Total Area")
      }
      else if(document.getElementById('prName').value == ""){
        swal("You Have Not Entered the Procurement Name")
      }
      else if(document.getElementById('prPhone').value == ""){
        swal("You Have Not Entered the Procurement Number")
      }
    }
    function checklength(data){
         if(data=='prPhone')
            {
                var y = document.getElementById('prPhone').value;
                const Http = new XMLHttpRequest();
               var x = y;
                const url='{{URL::to('/')}}/checkmanu?id='+x;
                Http.open("GET", url);
              Http.send();

            Http.onreadystatechange=(e)=>{
                var s = (Http.responseText);

                var obj =JSON.parse(s);
                 console.log(obj);
                  if((Http.responseText) != " "){
                              swal({
                                  title:"Are you sure?",
                                  
                                  type: "info",
                                   // imageUrl: 'thumbs-up.jpg',
                                    html: "Already Project is listes with number You wan to add Second project?"+ '<br>' + "Manufactured_Id="+obj[0]['manu_id'] + '<br>' + "procurement Name="+obj[0]['name'] + '<br>' + "procurement Number="+obj[0]['contact']+'<br>'+
                                   '<a class="btn btn-primary btn-sm" href='+"{{ URL::to('/') }}/updateManufacturerDetails?id="+obj[0]['manu_id']+'>Edit Project</a>',
                                  showCancelButton: true,
                                  closeOnConfirm: false,
                                  showLoaderOnConfirm: true
                                });
                            
                        }
           }
                 
    }

}

    function check(arg){
    var input = document.getElementById(arg).value;
    if(isNaN(input)){
      while(isNaN(document.getElementById(arg).value)){
      var str = document.getElementById(arg).value;
      str     = str.substring(0, str.length - 1);
      document.getElementById(arg).value = str;
      }
    }
    else{
      input = input.trim();
      document.getElementById(arg).value = input;
    }
}
</script>

<script type="text/javascript">
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "None";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

     
</script>



        <script>
            function myFunction() {
                var table = document.getElementById("types");
                var row = table.insertRow(-1);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                cell1.innerHTML = "<select title='Please Select Appropriate Type' required name='blockType[]' id='' class='form-control'>" +
                                "<option value=''>--Select--</option>" +
                                "<option value='Concrete'>Concrete</option>" +
                                "<option value='Cellular'>Cellular</option>" +
                                "<option value='Light Weight'>Light Weight</option>" +
                            "</select>"
                cell2.innerHTML = "<select title='Please Select Appropriate Size' required name='blockSize[]' id='' class='form-control'>" +
                                        "<option value=''>--Select--</option>" +
                                        "<option value='4 inch'>4 inch</option>" +
                                        "<option value='6 inch'>6 inch</option>" +
                                        "<option value='8 inch'>8 inch</option>" +
                                    "</select>";
                cell3.innerHTML = "<input min='1' type='number' required name='price[]' id='' placeholder='Price' class='form-control'>";
            }
            function myDelete() {
                var table = document.getElementById("types");
                if(table.rows.length >= 3){
                    document.getElementById("types").deleteRow(-1);
                }
            }

            
            function RMC() {
                var table = document.getElementById("types1");
                if(table.rows.length >= 3){
                    document.getElementById("types1").deleteRow(-1);
                }
            }

 function addfab() {
                var table = document.getElementById("fabc");
                var row = table.insertRow(-1);
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                cell1.innerHTML = "<select title='Please Select Appropriate Type' required name='fab[]' id='' class='form-control'>" +
                                "<option value=''>--Select--</option>" +
                                "<option value='metal'>Metal</option>" +
                                "<option value='wood'>Wood</option>" +
                                "<option value='steel'>Steel</option> </select>";
                cell2.innerHTML = "<input type='number' min='1' required name='fabprice[]' id='' placeholder='Price' class='form-control'>";
            }
            function fab() {
                var table = document.getElementById("types1");
                if(table.rows.length >= 3){
                    document.getElementById("types1").deleteRow(-1);
                }
            }




          function hideordisplay(arg){
              if(arg == "Blocks"){
                document.getElementById('blockTypes1').className = "";
                document.getElementById('blockTypes2').className = "";
                document.getElementById('mfType').className = "";
                document.getElementById('grades1').className = "hidden";
                document.getElementById('fab1').className = "hidden";
                document.getElementById('fab2').className = "hidden";

                document.getElementById('grades2').className = "hidden";
                document.getElementById('moq').className = "hidden";
              }else if(arg=="RMC"){
                document.getElementById('blockTypes1').className = "hidden";
                document.getElementById('blockTypes2').className = "hidden";
                document.getElementById('mfType').className = "hidden";
                document.getElementById('grades1').className = "";
                document.getElementById('grades2').className = "";
                    document.getElementById('fab1').className = "hidden";
                document.getElementById('fab2').className = "hidden";
                document.getElementById('moq').className = "";
              }else if(arg=="Fabricators"){
                    document.getElementById('blockTypes1').className = "hidden";
                document.getElementById('blockTypes2').className = "hidden";
                document.getElementById('mfType').className = "hidden";
                document.getElementById('grades1').className = "hidden";
                document.getElementById('fab1').className = "";
                document.getElementById('fab2').className = ""
              }else{
                document.getElementById('blockTypes1').className = "hidden";
                document.getElementById('blockTypes2').className = "hidden";
                document.getElementById('mfType').className = "hidden";
                document.getElementById('grades1').className = "hidden";
                document.getElementById('grades2').className = "hidden";
                document.getElementById('fab1').className = "hidden";
                document.getElementById('fab2').className = "hidden"
                  console.log(arg);
              }
          }
          function checkPhNo(x){
            var phoneno = /^[6-9][0-9]\d{8}$/;
            if(x != "" && !x.match(phoneno))
            {
                alert('Please Enter 10 Digits Phone Number');
                document.getElementById("phNo").value = '';
                document.getElementById("phNo").focus();
                return false;
            }
          }
            
        </script>
<script type="text/javascript" charset="utf-8">
    function getLocation(){
        document.getElementById("getBtn").className = "hidden";
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
      document.getElementById('latitude').value=latitude;
      document.getElementById('longitude').value=longitude;
      getAddressFromLatLang(latitude,longitude);
      initMap();
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
<script type="text/javascript">
  function initMap() {
       var latitude = document.getElementById("latitude").value; 
       var longitude  = document.getElementById("longitude").value;
       if(latitude != ""){
      var faultyward = "{{json_encode($ward)}}";
      var ward = faultyward.split('&quot;,&quot;').join('","');
      ward = ward.split('&quot;').join('"');

      var ss = JSON.parse(ward);
      var shouldAlert;
      for(var i=0; i<Object(ss['original'].length); i++){
        
        var finalward = [];
        finalward = ss['original'][i]['lat'].map(s => eval('null,' +s ));

       var bermudaTriangle = new google.maps.Polygon({paths: finalward});  
        var locat = new google.maps.LatLng(latitude,longitude);
       shouldAlert = google.maps.geometry.poly.containsLocation(locat, bermudaTriangle);

               if(shouldAlert == true){
                     // alert("ward id :" +ss['original'][i]['ward']);
                      getBrands(ss['original'][i]['ward']);
                          break;

                }
           
      }
      if(shouldAlert == false){
        alert("no ward found");
      }
}    
  }
function getBrands(data){
    const Http = new XMLHttpRequest();
    var x = data;
    // alert(x);
  const url='{{URL::to('/')}}/subfind?id='+x;
   Http.open("GET", url);
   Http.send();

Http.onreadystatechange=(e)=>{
              
  
           initsubward(Http.responseText);
            
            
            }
  

  }

  function initsubward(data){
     var latitude = document.getElementById("latitude").value; 
     var longitude  = document.getElementById("longitude").value;
        var subfaulty = data;
      var subs = JSON.parse(subfaulty);
     

      var shouldAlert;
      for(var i=0; i<Object(subs.length); i++){
        
        var finalsubward = [];
        finalsubward = subs[i]['lat'].map(s => eval('null,' +s ));

         console.log(finalsubward);

       var bermudaTriangle = new google.maps.Polygon({paths: finalsubward});  
        var locat = new google.maps.LatLng(latitude,longitude);
      shouldAlert = google.maps.geometry.poly.containsLocation(locat, bermudaTriangle);

              
               if(shouldAlert == true){
                  // alert("subward id: " +subs[i]['subward']);
                      document.getElementById('subwardid').value=subs[i]['subward'];
                       break;
                }
           
      }
      if(shouldAlert== false){
        alert("Subward Not Found");
      }



  }


</script>
<script type="text/javascript">
   function doDate()
  {
      var str = "";

      var days = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
      var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

      var now = new Date();

      str += "Today Is: " + days[now.getDay()] + ", " + now.getDate() + " " + months[now.getMonth()] + " " + now.getFullYear() + " " + now.getHours() +":" + now.getMinutes() + ":" + now.getSeconds();
      document.getElementById("currentTime").innerHTML = str;
  }

  setInterval(doDate, 1000);
  function validateFileType(){
    var fileName = document.getElementById("pImage").value;
    var idxDot = fileName.lastIndexOf(".") + 1;
    var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
    if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
          document.getElementById('errormsg').innerHTML = "";
    }else{
          document.getElementById('errormsg').innerHTML = "Only <b>'.JPG'</b> , <b>'.JPEG'</b> and <b>'.PNG'</b> files are allowed!";
          document.getElementById("pImage").value = '';
          return false;
         }   
  }
</script>
@if(session('Success'))
<script>
    swal("success","{{ session('Success') }}","success");
</script>
<script type="text/javascript">
    function fileuploadimage(){ 
      var count = document.getElementById('pImage').files.length;
      if(count > 4){
        document.getElementById('pImage').value="";
        alert('You are allowed to upload a maximum of 4 files');
      }
    }
</script>

@endif
<script type="text/javascript">
  $(document).ready(function () {
    var counter = 0;
      
    $("#addrow").on("click", function () {
        var newRow = $("<tr>");
        var cols = "";
                <?php $categories = App\brand::where('category_id',36)->get(); 

                 
                ?>
          
        cols += '<td> <select id="category3'+counter+'" class="form-control" name="brand1[]"> <option value=>--Select Brand--</option><?php foreach ($categories as $category ): ?><option value={{$category->id}}>{{$category->brand }}</option><?php endforeach ?> </select></td>';
         
          cols += '<td><input type="text" class="form-control" name="quan[]" id="quan'+counter+'"></td>'; 
          cols += '<td><input type="text" class="form-control"  name="price[]" id="price'+counter+'" step="0.01"></td>';
           cols += '<td><input type="text"  name="Suppliername[]" class="form-control" id="Suppliername'+counter+'" ></td>';
           cols +='<td><input type="text"  name="minquan[]" class="form-control"  id="minquan'+counter+'"></td>'; 
           cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
            newRow.append(cols);
        $("table.order-list").append(newRow);
        counter++;
    });



    $("table.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
    });


});




</script>
</body>
</html>
