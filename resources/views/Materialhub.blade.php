<!DOCTYPE html>
<html>
<head>
  <title>Material Hub</title>
  <style>
#myMap {
   height: 350px;
   width: 680px;
}
</style>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGSf_6gjXK-5ipH2C2-XFI7eUxbHg1QTU&sensor=false"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script type="text/javascript" src="{{asset('js/gmaps.js')}}"></script>
    <script src="{{ URL::to('/') }}/js/jscolor.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
<div class="container">
    <div class="row">
  <span class="pull-right"> @include('flash-message')</span>
      
        <form method="POST" id="yadav"  action="{{ URL::to('/') }}/addmatirial" enctype="multipart/form-data">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" style="background-color:#42c3f3;color:#ffffffe3;padding:20px;">
             <div id="currentTime" class="pull-right" style="margin--5px;"></div>
            <div id="currentTime" class="pull-left" style="margin--5px;">
               @if($subwards != null)
                Assigned Ward id :{{$subwards != null ? $subwards->sub_ward_name : ''}}
              @else
                <p style="text-align: center;color: red;">No Ward Assigned To You..</p>
              @endif
            </div>
                </div>
                <div class="panel-body">
                <div id="myMap" style="width:700px;height:250px;object-fit: cover;"></div>
                  


                   <center> <label id="headingPanel"></label></center>
                   <br>              
                     <center>       
                     <button id="getBtn"  class="btn btn-success btn-sm" onclick="getLocation()">Get Location</button></center><br>
                    <div id="first">
                    {{ csrf_field() }}
                            <table class="table">
                               <!--  <tr>
                                   <td>Co-Ordinator/broker name</td>
                                   <td>:</td>
                                   <td><input id="pName" required type="text" placeholder="Co-Ordinator Name" class="form-control input-sm" name="pName" value="{{ old('pName') }}" ></td>
                               </tr> -->
                               <input type="hidden" name="subward_id" id="subwardid" >
                                
                               <tr>
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
                                   <td>Truck owner number</td>
                                   <td>:</td>
                                   <td><input id="road" required type="text" placeholder="Truck owner number" class="form-control input-sm" name="onumber" value="{{ old('rName') }}"></td>
                               </tr>
                              
                               <tr>
                                   <td>Truck no</td>
                                   <td>:</td>
                                   <td><input id="rWidth" required onkeyup="check('rWidth')"  required type="text" placeholder="Truck no" class="form-control input-sm" name="rWidth" value="{{ old('rWidth') }}" required></td>
                                  
                               </tr>
                                <tr>
                                   <td>Truck Type</td>
                                   <td>:</td>
                                   <td>

                                    <label ><input required value="Truck" id="rmc" type="radio" name="tracktypetype"><span>&nbsp;</span>Truck</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                      <label ><input required value="Tractor" id="rmc2" type="radio" name="tracktypetype"><span>&nbsp;</span>Tractor</label> 
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                     
                                   </td>
                                  
                               </tr>
                               <tr class="{{ $errors->has('address') ? ' has-error' : '' }}">
                                   <td>Full Address</td>
                                   <td>:</td>
                                   <td><input readonly id="address" required type="text" placeholder="Full Address" class="form-control input-sm" name="address" value="{{ old('address') }}"></td>
                               </tr>
                              
                               <script type="text/javascript">
                                 $(document).ready(function(){
                                      $('#constructionType1').change(function(){
                                      if(this.checked)
                                      $('#autoUpdate').fadeIn('slow');
                                      else
                                      $('#autoUpdate').fadeOut('slow');

                                      });
                                      });
                               </script>
                               
                              
                               <tr>
                                <td>Category </td>
                                <td>:</td>
                                <td>
                                  <select required class="form-control" name="Category" id="contract" class="requiredn">
                                    <option   value="" disabled selected>--- Select ---</option>
                                    <option    value="riversand ">River Sand </option>
                                    <option  value="m-sand">M-Sand</option>
                                     <option  value="cement">Cement</option>
                                    <option  value="aggregates">Aggregates</option>
                                     <option  value="redbricks">RedBricks</option>
                                     <option  value="stones">Stones</option>
                                     <option  value="jelly">Jelly</option>


                                </select>
                              </td>
                            </tr>

                                   <tr>
                                <td>Vehical Capicity Type</td>
                                <td>:</td>
                                <td>
                                  <select required class="form-control" name="Vehicaltype" id="contract" class="required">
                                    <option   value="" disabled selected>--- Select ---</option>
                                    <option    value="6 ">6 wheelers</option>
                                    <option  value="10"> 10 wheelers</option>
                                    <option  value="12"> 12 wheelers</option>
                                     

                                    

                                </select>
                              </td>
                            </tr>

                             
                               <tr>
                                   <td>Parking Vehical Capicity</td>
                                   <td>:</td>
                                   <td><input type="text" name="Capacity" class="form-control" placeholder="Capacity From" value="">
                                    <input type="text" name="Capacity1" class="form-control" placeholder="Capacity to" value="">




                               </tr>
                               <tr>
                                   <td>MaterialHub Images</td>
                                   <td>:</td>
                                   <td><input id="pImage" oninput="fileuploadimage()" required type="file" accept="image/*" class="form-control input-sm" name="pImage[]" onchange="validateFileType()" multiple><p id="errormsg"></p></td>
                               </tr>

                                 <div id="POItablediv">
  
  <table id="POITable" border="1" class="table">
    <tr>
      <td>Slno</td>
      <td>Product</td>
      <td>Price</td>
      <td>Delete?</td>
      <td>Add Rows?</td>
    </tr>
    <tr>
      <td>1</td>
      <td><input size=25 type="text" id="latbox" name="Product[]" placeholder="Product Name" /></td>
      <td><input size=25 type="text" id="lngbox" name="price[]" placeholder="price" /></td>
      <td><input type="button" id="delPOIbutton" value="Delete" onclick="deleteRow(this)"  class="btn btn-sm btn-danger" /></td>
      <td><input type="button" id="addmorePOIbutton" value="Add" onclick="insRow()" class="btn btn-sm btn-success" /></td>
    </tr>
  </table>
   </div>
                             
                           </table>
                          </div>
                        
                         

<div class="w3-bar w3-blue">
  <a  class="w3-bar-item w3-button" onclick="openCity('London')">Co-Ordinator Deatils</a>
  <a  class="w3-bar-item w3-button" onclick="openCity('Paris')">Broker Details</a>
  <a class="w3-bar-item w3-button" onclick="openCity('Tokyo')">Bank Details</a>
</div>


   <div id="London" class="w3-container city" >
  <table class="table" border="1">
                               <tr>
                                   <td>Co-Ordinator Name</td>
                                   <td>:</td>
                                   <td><input value="{{ old('oName') }}" type="text" placeholder="Co-Ordinator Name" class="form-control input-sm" name="pName" id="oName"></td>
                               </tr>
                               <tr>
                                   <td>Co-Ordinator Contact Number1</td>
                                   <td>:</td>
                                   <td><input value="{{ old('oEmail') }}" onblur="checkmail('oEmail')" placeholder="Co-Ordinator Contact Number1" type="text" maxlength="10"  minlength="10"   class="form-control input-sm" name="cnumber1" id="oEmail"></td>
                               </tr>
                               <tr>
                                   <td>Co-Ordinator Contact Number2.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('oContact') }}" onblur="checklength('oContact');" onkeyup="check('oContact','1')" maxlength="10"  minlength="10" placeholder="Co-Ordinator Contact Number2." type="text" class="form-control input-sm" name="cnumber2" id="oContact"></td>
                               </tr>
                           </table>
</div>

 <div id="Paris" class="w3-container city" style="display:none">
  <table class="table" border="1">
                               <tr>
                                   <td>Broker Name</td>
                                   <td>:</td>
                                   <td><input value="{{ old('oName') }}" type="text" placeholder="Broker Name" class="form-control input-sm" name="bname" id="oName"></td>
                               </tr>
                               <tr>
                                   <td>Broker Contact Number1</td>
                                   <td>:</td>
                                   <td><input value="{{ old('oEmail') }}" onblur="checkmail('oEmail')" placeholder="Broker Contact Number1" type="text" maxlength="10"  minlength="10"   class="form-control input-sm" name="bnumber1" id="oEmail"></td>
                               </tr>
                               <tr>
                                   <td>Broker Contact Number2.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('oContact') }}" onblur="checklength('oContact');" onkeyup="check('oContact','1')" maxlength="10"  minlength="10" placeholder="Broker Contact Number2." type="text" class="form-control input-sm" name="bnumber2" id="oContact"></td>
                               </tr>
                           </table>
</div>

 <div id="Tokyo" class="w3-container city" style="display:none">
  <table class="table" border="1">
                               <tr>
                                   <td>Bank Name</td>
                                   <td>:</td>
                                   <td><input value="{{ old('oName') }}" type="text" placeholder="Bank Name" class="form-control input-sm" name="bankname" id="oName"></td>
                               </tr>
                               <tr>
                                   <td> Account Number</td>
                                   <td>:</td>
                                   <td><input value="{{ old('oEmail') }}" onblur="checkmail('oEmail')" placeholder="Account Number" type="text" maxlength="20"  minlength="10"  class="form-control input-sm" name="accountnumber" id="oEmail"></td>
                               </tr>
                               <tr>
                                   <td>IFS CODE</td>
                                   <td>:</td>
                                   <td><input value="{{ old('oContact') }}" onblur="checklength('oContact');" onkeyup="check('oContact','1')" maxlength="15"  minlength="0" placeholder="IFS CODE." type="text" class="form-control input-sm" name="ifs" id="oContact"></td>
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
                            <button onclick="submitform()" id="sub" class="form-control btn btn-primary">Submit Data</button>
                          <!--  <li class="next"><a id="next" href="#" onclick="pageNext()">Next</a></li> -->
                     </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- get location -->

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
     console.log(subs);

      var shouldAlert;
      for(var i=0; i<Object(subs.length); i++){
        
        var finalsubward = [];
        finalsubward = subs[i]['lat'].map(s => eval('null,' +s ));

         // console.log(finalsubward);

       var bermudaTriangle = new google.maps.Polygon({paths: finalsubward});  
        var locat = new google.maps.LatLng(latitude,longitude);
      shouldAlert = google.maps.geometry.poly.containsLocation(locat, bermudaTriangle);

              
               if(shouldAlert == true){
                   // alert(" your in subward : " +subs[i]['subward']);
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
function deleteRow(row) {
  var i = row.parentNode.parentNode.rowIndex;
  document.getElementById('POITable').deleteRow(i);
}


function insRow() {
  console.log('hi');
  var x = document.getElementById('POITable');
  var new_row = x.rows[1].cloneNode(true);
  var len = x.rows.length;
  new_row.cells[0].innerHTML = len;

  var inp1 = new_row.cells[1].getElementsByTagName('input')[0];
  inp1.id += len;
  inp1.value = '';
  var inp2 = new_row.cells[2].getElementsByTagName('input')[0];
  inp2.id += len;
  inp2.value = '';
  x.appendChild(new_row);
}
</script>

<script>
function openCity(cityName) {
  var i;
  var x = document.getElementsByClassName("city");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  document.getElementById(cityName).style.display = "block";  
}
</script>
<script type="text/javascript">
  function submitform(){

     document.getElementById('yadav').submit();
  }
</script>

</body>
</html>

