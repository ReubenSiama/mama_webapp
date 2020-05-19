<!DOCTYPE html>
<html>
<head>
  <title>Add Project</title>
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet"/>







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
        <div class="col-md-8 col-md-offset-2">
        <form method="POST" onsubmit="validateform()" action="{{ URL::to('/') }}/addProject" enctype="multipart/form-data">
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
                  @if(Auth::user()->group_id == 1|| Auth::user()->group_id == 2)
                  <div id="myMap" style="width:700px;height:250px;object-fit: cover;"></div>
                  @endif
                   <center> <label id="headingPanel"></label></center>
                   <br>              
                     <center>       
                     <button id="getBtn"  class="btn btn-success btn-sm" onclick="getLocation()">Get Location</button></center><br>
                    <div id="first">
                    {{ csrf_field() }}
                            <table class="table">
                                <tr>
                                   <td>Project Name</td>
                                   <td>:</td>
                                   <td><input id="pName" required type="text" placeholder="Project Name" class="form-control input-sm" name="pName" value="{{ old('pName') }}" ></td>
                               </tr>
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
                                   <td>Road Name/Road No./Landmark</td>
                                   <td>:</td>
                                   <td><input id="road" required type="text" placeholder="Road Name / Road No." class="form-control input-sm" name="rName" value="{{ old('rName') }}"></td>
                               </tr>
                              
                               <tr>
                                   <td>Road Width</td>
                                   <td>:</td>
                                   <td><input id="rWidth" required onkeyup="check('rWidth')"  required type="text" placeholder="Road Width In Feet" class="form-control input-sm" name="rWidth" value="{{ old('rWidth') }}" required></td>
                                  
                               </tr>
                                @if(Auth::user()->group_id == 2 || Auth::user()->group_id == 1 )
                               <tr class="{{ $errors->has('address') ? ' has-error' : '' }}">
                                   <td>Full Address</td>
                                   <td>:</td>
                                   <td><input  id="address" required type="text" placeholder="Full Address" class="form-control input-sm" name="address" value="{{ old('address') }}"></td>
                               </tr>
                               @else
                                 <tr class="{{ $errors->has('address') ? ' has-error' : '' }}">
                                   <td>Full Address</td>
                                   <td>:</td>
                                   <td><input readonly id="address" required type="text" placeholder="Full Address" class="form-control input-sm" name="address" value="{{ old('address') }}"></td>
                               </tr>
                              




                               @endif
                               <tr>
                                 <td>Construction Type</td>
                                 <td>:</td>
                                 <td>
                                    <label required class="checkbox-inline" ><input  id="constructionType1" name="constructionType[]" type="checkbox" value="Residential">Residential</label>
                                    <label required class="checkbox-inline"><input id="constructionType2" name="constructionType[]" type="checkbox" value="Commercial">Commercial</label> <br>
                          <div id="autoUpdate" class="autoUpdate" style="display:none;">
                                 <label required class="checkbox-inline" style="color:#42c3f3;"><input id="constructionType1" name="apart[]" type="checkbox" value="Apartments">Apartments </label>
                                    <label required class="checkbox-inline" style="color:#42c3f3;"><input id="constructionType2" name="apart[]" type="checkbox" value="Duplex">Duplex</label> 
                                     <label required class="checkbox-inline" style="color:#42c3f3;"><input id="constructionType2" name="apart[]" type="checkbox" value="villas">Independent villas</label> 
                        </div>
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
                               @if(Auth::user()->group_id ==1|| Auth::user()->group_id == 2)
                                 <tr>
                                   <td>Reference Customer Id / Number</td>
                                   <td>:</td>
                                   <td><input  onkeyup="getcustomerid()"   type="text" placeholder="Enter customer id or Number" class="form-control input-sm" name="cid"  id="mid"  >
                                  <p id="cids">
                                    
                                  </p></td>
                               </tr>
                               @endif




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
                                 <td>Interested Ininterior Design?</td>
                                 <td>:</td>
                                 <td>
                                     
                                      <label ><input required value="Yes" id="rmc" type="radio" name="Ininterior"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                      <label ><input required value="No" id="rmc2" type="radio" name="Ininterior"><span>&nbsp;</span>No</label> 
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                      <label><input checked="checked" value="None" id="rmc3" type="radio" name="Ininterior"><span>&nbsp;</span>None</label>
                                 </td>
                               </tr>


                             
                              
                                <tr>
                                 <td>Interested In Kitchen Cabinates and Wardrobes?</td>
                                 <td>:</td>
                                 <td>
                                    
                                      <label><input required value="Yes" id="dandw1" type="radio" name="dandwinterest"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                   
                                      <label><input required value="No" id="dandw2" type="radio" name="dandwinterest"><span>&nbsp;</span>No</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                   
                                      <label><input checked="checked" required value="None" id="dandw3" type="radio" name="dandwinterest"><span>&nbsp;</span>None</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                 </td>
                               </tr>
                               <tr>
                                 <td>Interested In UPVC Doors and Windows?</td>
                                 <td>:</td>
                                 <td>
                                    
                                      <label><input required value="Yes" id="dandw1" type="radio" name="upvc"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                   
                                      <label><input required value="No" id="dandw2" type="radio" name="upvc"><span>&nbsp;</span>No</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                   
                                      <label><input checked="checked" required value="None" id="dandw3" type="radio" name="upvc"><span>&nbsp;</span>None</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                 </td>
                               </tr>
                              
                               <tr>
                                 <td>Interested In Home Automation ?</td>
                                 <td>:</td>
                                 <td>
                                    
                                      <label><input required value="Yes" id="loan1" type="radio" name="automation"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                  
                                      <label><input required value="No" id="loan2" type="radio" name="automation"><span>&nbsp;</span>No</label>
                                       <span>&nbsp;&nbsp;&nbsp;  </span>
                                
                                      <label><input checked="checked" required value="None" id="loan3" type="radio" name="automation"><span>&nbsp;</span>None</label>
                                   
                                 </td>
                               </tr>
                                <tr>
                                 <td>Interested In Premium Products ?</td>
                                 <td>:</td>
                                 <td>
                                    
                                      <label><input required value="Yes" id="premium1" type="radio" name="premium"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                  
                                      <label><input required value="No" id="premium2" type="radio" name="premium"><span>&nbsp;</span>No</label>
                                       <span>&nbsp;&nbsp;&nbsp;  </span>
                                
                                      <label><input checked="checked" required value="None" id="premium3" type="radio" name="premium"><span>&nbsp;</span>None</label>
                                   
                                 </td>
                               </tr>

                               <tr>
                                <td>Type of &nbsp;&nbsp;
                                Contract ?</td>
                                <td>:</td>
                                <td>
                                  <select required class="form-control" name="contract" id="contract" class="requiredn">
                                    <option   value="" disabled selected>--- Select ---</option>
                                    <option    value="Labour Contract">Labour Contract</option>
                                    <option  value="Material Contract">Material Contract</option>
                                     <option  value="None">None</option>
                                </select>
                              </td>
                            </tr>
                               <!-- <tr>
                                   <td>Municipal Approval</td>
                                   <td>:</td>
                                   <td><input type="file" accept="image/*" class="form-control input-sm" name="mApprove"></td>
                               </tr> -->
                               <!-- <tr>
                                   <td>Govt. Approvals<br>(Municipal, BBMP, ETC)</td>
                                   <td>:</td>
                                   <td><input  oninput="fileUpload()" id="oApprove" multiple type="file" accept="image/*" class="form-control input-sm" name="oApprove[]"></td>
                               </tr> -->
                                <tr>
                                   <td>Project Status</td>
                                   <td>:</td>
                                   <td>
                                          <div class="col-md-3" >
                                            <label required class="checkbox-inline">
                                              <input style="width: 33px;" id="planning" type="checkbox" onchange="count()" name="status[]" value="Planning"><span>&nbsp;&nbsp;&nbsp;</span>Planning
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="digging" type="checkbox" onchange="count()" name="status[]" value="Digging">Digging
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" type="checkbox" onchange="count()" name="status[]" value="Foundation">Foundation
                                            </label>
                                         
                                             <label class="checkbox-inline">
                                              <input id="pillars" type="checkbox" onchange="count()" name="status[]" value="Pillars">Pillars
                                            </label>

                                            <label class="checkbox-inline">
                                            <input id="walls" type="checkbox" onchange="count()" name="status[]" value="Walls">Walls
                                          </label>
                                          </div>
                                         <div class="col-md-3">
                                          
                                          <label class="checkbox-inline">
                                          <input id="roofing" style="width: 33px;" type="checkbox" onchange="count()" name="status[]" value="Roofing"><span>&nbsp;&nbsp;&nbsp;</span>Roofing
                                        </label>
                                       
                                          <label class="checkbox-inline">
                                          <input id="electrical" type="checkbox" onchange="count()" name="status[]" value="Electrical">Electrical
                                        </label>
                                       
                                          <label class="checkbox-inline">
                                          <input id="plumbing" type="checkbox" onchange="count()" name="status[]" value="Plumbing">Plumbing
                                        </label>

                                        <label class="checkbox-inline">
                                          <input id="plastering" type="checkbox" onchange="count()" name="status[]" value="Plastering">Plastering
                                        </label>
                                       
                                          <label class="checkbox-inline">
                                          <input id="flooring" type="checkbox" onchange="count()" name="status[]" value="Flooring">Flooring
                                        </label>

                                      </div>
                                       <div class="col-md-3">
                                        
                                          <label class="checkbox-inline">
                                          <input id="carpentry" style="width: 33px;" type="checkbox" onchange="count()" name="status[]" value="Carpentry"><span>&nbsp;&nbsp;&nbsp;</span>Carpentry
                                        </label>
                                       
                                          <label class="checkbox-inline">
                                          <input id="paintings" type="checkbox" onchange="count()" name="status[]" value="Paintings">Paintings
                                        </label>

                                        <label class="checkbox-inline">
                                          <input id="fixtures" type="checkbox" onchange="count()" name="status[]" value="Fixtures">Fixtures
                                        </label>
                                      
                                          <label class="checkbox-inline">
                                          <input id="completion" type="checkbox" onchange="count()" name="status[]" value="Completion">Completion
                                        </label>
                                        
                                          <label class="checkbox-inline">
                                          <input id="closed" type="checkbox" onchange="count()" name="status[]" value="Closed">Closed
                                        </label>
                                       </div>
                                       

                                   </td>
                               </tr>
                               <tr>
                                      <td>Project Type</td>
                                      <td>:</td>
                                      <td>
                                      <div class="row">
                                      <div class="col-md-3">
                                      <input value="{{ old('basement') }}" required  onkeyup="check('basement')"
                                      id="basement" name="basement" type="text" autocomplete="off"
                                      class="form-control input-sm" placeholder="Basement" id="email">
                                      </div>
                                      <div class="col-md-2">
                                      <b style="font-size: 20px; text-align: center">+</b>
                                      </div>
                                      <div class="col-md-3">
                                      <input value="{{ old('ground') }}" required onkeyup="check('ground');"
                                      autocomplete="off" name="ground" id="ground" type="text"
                                      class="form-control" placeholder="Floor">
                                      </div>
                                      <div class="col-md-3">
                                      <p id="total"></p>
                                      </div>
                                      </div>
                                      </td>
                              </tr>
                               <tr>
                                   <td>Plot Size</td>
                                   <td>:</td>
                                   <td>
                                    <div class="row">
                                        <div class="col-md-3">
                                          <input value="{{ old('length') }}" required onkeyup="checkthis('length')" id="length" name="length" type="text" autocomplete="off" class="form-control input-sm" placeholder="Length" >
                                        </div>
                                        <div class="col-md-2">
                                          <b style="font-size: 20px; text-align: center">*</b>
                                        </div>
                                      <div class="col-md-3">
                                        <input value="{{ old('breadth') }}" required onkeyup="checkthis('breadth');" autocomplete="off" name="breadth" id="breadth" type="text" class="form-control" placeholder="Breadth">
                                      </div>
                                      <div class="col-md-3">
                                        <p id="totalsize"></p>
                                      </div>
                                    </div>
                                    </td>
                               </tr>
                               <!-- <tr>
                                    <td>Recommended Project Size Is</td>
                                     <td>:</td>
                                    <td>
                                     
                                    </td>
                                </tr> -->
                               <tr>
                                   <td>Project Size (Approx.)</td>
                                   <td>:</td>
                                   <td id= "totalofsize">
                                    <div class="col-md-4 pull-left">
                                    <input value="{{ old('pSize') }}" id="pSize" required placeholder="Project Size In Sq. Ft." type="text" class="form-control input-sm" name="pSize" onkeyup="check('pSize')">
                                    </div>
                                    <div class="col-md-8 alert-success pull-right" id="pSizeTag"></div>
                                  </td>
                               </tr>
                               <tr>
                                 <td>Budget Type</td>
                                 <td>:</td>
                                 <td >
                                    <label ><input id="constructionType3" name="budgetType[]"  type="radio" value="Structural"><span>&nbsp;</span>Structural</label>
                                    <span>&nbsp;&nbsp;</span>
                                    <label ><input id="constructionType4" name="budgetType[]"  type="radio" value="Finishing"><span>&nbsp;</span>Finishing </label> 
                                 </td>
                               </tr>
                               <tr>
                                   <td>Budget (Approx.)</td>
                                   <td>:</td>
                                   <td><input value="{{ old('budget') }}" id="budget" required placeholder="Budget In Crores" type="text" onkeyup="check('budget')" class="form-control input-sm" name="budget"></td>
                               </tr>
                               <tr>
                                   <td>Project Images</td>
                                   <td>:</td>
                                   <td><input id="pImage" oninput="fileuploadimage()" required type="file" accept="image/*" class="form-control input-sm" name="pImage[]" onchange="validateFileType()" multiple><p id="errormsg"></p></td>
                               </tr>
                               <tr>
                                    <td>Room Types</td>
                                    <td>:</td>
                                    <td>
                                        <table id="bhk" class="table table-responsive">
                                            <tr id="selection">
                                                
                                            </tr>
                                            <tr>
                                                <td colspan=3>
                                                    <button onclick="addRow();" type="button" class="btn btn-primary form-control">Add More</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                               </tr>
                           </table>
                          </div>
<button style="width: 100%;font-size: 20px;" class="btn btn-sm">Customer Details</button>
<div class="tab"  id="second" style="overflow: hidden;
    border: 1px solid #ccc;
    background-color: #337ab7;
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
     color:white;" class="tablinks" onclick="openCity(event, 'contractor')">Contractor Details </button><br>
  <button style="background-color: inherit;
    
    border: none;
    outline: none;
    cursor: pointer;
    padding: 12px 16px;
    transition: 0.3s;
    font-size: 17px;
     color:white;" class="tablinks" onclick="openCity(event, 'consultant')">Consultant Details</button><br>
  <button style="background-color: inherit;
    
    border: none;
    outline: none;
    cursor: pointer;
    padding: 12px 16px;
    transition: 0.3s;
    font-size: 17px;
     color:white;" class="tablinks" onclick="openCity(event, 'site')">Site Engineer Details</button><br>
  <button style="background-color: inherit;
    
    border: none;
    outline: none;
    cursor: pointer;
    padding: 12px 16px;
    transition: 0.3s;
    font-size: 17px;
     color:white;" class="tablinks" onclick="openCity(event, 'procurement')">Procurement Details</button><br>

<button style="background-color: inherit;
    
    border: none;
    outline: none;
    cursor: pointer;
    padding: 12px 16px;
    transition: 0.3s;
    font-size: 17px;
     color:white;" class="tablinks" onclick="openCity(event, 'Builder')">Builder Details</button>
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
                                   <td>Owner Contact No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('oContact') }}" onblur="checklength('oContact');" onkeyup="check('oContact','1')" maxlength="10"  minlength="10" placeholder="Owner Contact No." type="text" class="form-control input-sm" name="oContact" id="oContact"></td>
                               </tr>
                               <tr>
                                   <td>Owner WhatsApp No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input  maxlength="10"  minlength="10" placeholder="Owner WhatsApp No." type="text" class="form-control input-sm" name="owhatsapp" ></td>
                               </tr>
                           </table>
</div>
<div id="contractor" class="tabcontent" style="display: none;padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;"><br>
   <center><label>Contractor Details</label></center>
   <br>
                           <table class="table"  border="1">
                               <tr>
                                   <td>Contractor Name</td>
                                   <td>:</td>
                                   <td><input value="{{ old('cName') }}" type="text" placeholder="Contractor Name" class="form-control input-sm" name="cName" id="cName"></td>
                               </tr>
                               <tr>
                                   <td>Contractor Email</td>
                                   <td>:</td>
                                   <td><input value="{{ old('cEmail') }}" placeholder="Contractor Email" type="email" class="form-control input-sm" name="cEmail" id="cEmail" onblur="checkmail('cEmail')" ></td>
                               </tr>
                               <tr>
                                   <td>Contractor Contact No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('cContact') }}" onblur="checklength('cPhone');" id="cContact" onkeyup="check('cPhone','1')" placeholder="Contractor Contact No." type="text" maxlength="10" class="form-control input-sm" name="cContact"></td>
                               </tr>
                               <tr>
                                   <td>Contractor WhatsApp No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input  placeholder="Contractor WhatsApp No." type="text" maxlength="10" class="form-control input-sm" name="cwhatsapp"></td>
                               </tr>
                           </table>
</div>

<div id="consultant" class="tabcontent" style="display: none;padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;"><br>
  <center><label>Consultant Details</label></center><br>
                           <table class="table"  border="1">
                               <tr>
                                   <td>Consultant Name</td>
                                   <td>:</td>
                                   <td><input value="{{ old('coName') }}" type="text" placeholder="Consultant Name" class="form-control input-sm" name="coName"></td>
                               </tr>
                               <tr>
                                   <td>Consultant Email</td>
                                   <td>:</td>
                                   <td><input value="{{ old('coEmail') }}" placeholder="Consultant Email" type="email" class="form-control input-sm" name="coEmail" id="coEmail" onblur="checkmail('coEmail')"></td>
                               </tr>
                               <tr>
                                   <td>Consultant Contact No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('coContact') }}" onblur="checklength('coContact');" placeholder="Consultant Contact No." type="text" class="form-control input-sm" name="coContact" maxlength="10" id="coContact" onkeyup="check('coContact','1')"></td>
                               </tr>
                               <tr>
                                   <td>Consultant WhatsApp No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input  placeholder="Consultant WhatsApp No." type="text" class="form-control input-sm" name="ccwhatsapp" maxlength="10" id="coContact" ></td>
                               </tr>
                           </table>

</div>
<div id="site" class="tabcontent" style="display: none;padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;"><br>
   <center><label>Site Engineer Details</label></center><br>
                           <table class="table"  border="1">
                               <tr>
                                   <td>Site Engineer Name</td>
                                   <td>:</td>
                                   <td><input value="{{ old('eName') }}" type="text" placeholder="Site Engineer Name" class="form-control input-sm" name="eName" id="eName"></td>
                               </tr>
                               <tr>
                                   <td>Site Engineer Email</td>
                                   <td>:</td>
                                   <td><input value="{{ old('eEmail') }}"  placeholder="Site Engineer Email" type="email" class="form-control input-sm" name="eEmail" id="eEmail" onblur="checkmail('eEmail')"></td>
                               </tr>
                               <tr>
                                   <td>Site Engineer Contact No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('eContact') }}" onblur="checklength('eContact');"  placeholder="Site Engineer Contact No." type="text" class="form-control input-sm" name="eContact" id="eContact" maxlength="10" onkeyup="check('eContact','1')"></td>
                               </tr>
                               <tr>
                                   <td>Site Engineer WhatsApp No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input    placeholder="Site Engineer WhatsApp No." type="text" class="form-control input-sm" name="swhatsapp" id="eContact" maxlength="10" ></td>
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
                                   <td>Procurement Contact No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('pContact') }}" required  minlength=10 onblur="checklength('prPhone');" required placeholder="Procurement Contact No." type="text" class="form-control input-sm" name="prPhone" maxlength="10" id="prPhone" onkeyup="check('prPhone','1')"></td>
                               </tr>
                               <tr>
                                   <td>Procurement WhatsApp No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input   minlength=10  placeholder="Procurement WhatsApp No." type="text" class="form-control input-sm" name="pwhatsapp" maxlength="10" id="prPhone" ></td>
                               </tr>
                           </table>
</div>
<div id="Builder" class="tabcontent" style="display: none;padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;"><br>
   <center><label>Builder Details</label></center><br>
                           <table class="table"  border="1">
                               <tr>
                                   <td>Builder Name</td>
                                   <td>:</td>
                                   <td><input id="prName"  type="text" placeholder="Builder Name" class="form-control input-sm" name="bName" value="{{ old('prName') }}"></td>
                               </tr>
                               <tr>
                                   <td>Builder Email</td>
                                   <td>:</td>
                                   <td><input value="{{ old('pEmail') }}" placeholder="Builder Email" type="email" class="form-control input-sm" name="bEmail" id="pEmail" onblur="checkmail('pEmail')" ></td>
                               </tr>
                               <tr>
                                   <td>Builder Contact No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('pContact') }}"  minlength=10 onblur="checklength('prPhone');" placeholder="Builder Contact No." type="text" class="form-control input-sm" name="bPhone" maxlength="10" id="prPhone" onkeyup="check('prPhone','1')"></td>
                               </tr>
                               <tr>
                                   <td>Builder WhatsApp No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input   minlength=10  placeholder="Builder WhatsApp No." type="text" class="form-control input-sm" name="bwhatsapp" maxlength="10"></td>
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
                            <button type="submit" id="sub" class="form-control btn btn-primary" onclick="pageNext()" onsubmit="show()">Submit Data</button>
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
<!--This line by Siddharth -->
<script type="text/javascript">
  // window.onload = function(){
  //   var current = new Date();
  //   document.getElementById("currentTime").innerHTML = current.toLocaleTimeString();
  // }
  

   
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
  function checklength(arg)
  {
    var x = document.getElementById(arg);
    if(x.value)
    {
        var phoneno = /^[6-9][0-9]\d{8}$/;
        if(!x.value.match(phoneno))
        {
            alert('Please Enter 10 Digits in Phone Number');
            document.getElementById(arg).value = '';
            return false;
        }
        else
        {
            if(arg=='oContact')
            {
                var y = document.getElementById('oContact').value;
                $.ajax({
                    type:'GET',
                    url: '{{URL::to('/')}}/checkDupPhoneOwner',
                    data: {arg: y},
                    async: false,
                    success:function(response)
                    {
                        if(response > 0)
                        {
                              swal({
                                  title:"Are you sure?",
                                  text: "Already Project is listes with number You wan to add Second project?",
                                  type: "info",
                                   // imageUrl: 'thumbs-up.jpg',
                                  showCancelButton: true,
                                  closeOnConfirm: false,
                                  showLoaderOnConfirm: true
                                }, function () {
                                  setTimeout(function () {
                                    swal("Your request Is accepted  Thank You!");
                                  }, 1000);
                                });
                            
                        }
                    }
                });
            }
            if(arg=='coContact')
            {
                var y = document.getElementById('coContact').value;
                $.ajax({
                    type:'GET',
                    url: '{{URL::to('/')}}/checkDupPhoneConsultant',
                    data: {arg: y},
                    async: false,
                    success:function(response)
                    {
                        if(response > 0)
                        {
                              swal({
                                  title:"Are you sure?",
                                  text: "Already Project is listes with number You wan to add Second project?",
                                  type: "info",
                                   // imageUrl: 'thumbs-up.jpg',
                                  showCancelButton: true,
                                  closeOnConfirm: false,
                                  showLoaderOnConfirm: true
                                }, function () {
                                  setTimeout(function () {
                                    swal("Your request Is accepted  Thank You!");
                                  }, 1000);
                                });
                            
                        }
                    }
                });
            }
            if(arg=='cPhone')
            {
                var y = document.getElementById('cPhone').value;
                $.ajax({
                    type:'GET',
                    url: '{{URL::to('/')}}/checkDupPhoneContractor',
                    data: {arg: y},
                    async: false,
                    success:function(response)
                    {
                        if(response > 0)
                        {
                              swal({
                                  title:"Are you sure?",
                                  text: "Already Project is listes with number You wan to add Second project?",
                                  type: "info",
                                   // imageUrl: 'thumbs-up.jpg',
                                  showCancelButton: true,
                                  closeOnConfirm: false,
                                  showLoaderOnConfirm: true
                                }, function () {
                                  setTimeout(function () {
                                    swal("Your request Is accepted  Thank You!");
                                  }, 1000);
                                });
                            
                            // alert('Phone Number '+y+' Already Stored in Database. Are you sure you want to add the same number?');
                        }
                    }
                });
            }
            if(arg=='eContact')
            {
                var y = document.getElementById('eContact').value;
                $.ajax({
                    type:'GET',
                    url: '{{URL::to('/')}}/checkDupPhoneSite',
                    data: {arg: y},
                    async: false,
                    success:function(response)
                    {
                        if(response > 0)
                        {
                              swal({
                                  title:"Are you sure?",
                                  text: "Already Project is listes with number You wan to add Second project?",
                                  type: "info",
                                   // imageUrl: 'thumbs-up.jpg',
                                  showCancelButton: true,
                                  closeOnConfirm: false,
                                  showLoaderOnConfirm: true
                                }, function () {
                                  setTimeout(function () {
                                    swal("Your request Is accepted  Thank You!");
                                  }, 1000);
                                });
                            
                        }
                    }
                });
            }
            if(arg=='prPhone')
            {
                var y = document.getElementById('prPhone').value;
                
                const Http = new XMLHttpRequest();
               var x = y;
                const url='{{URL::to('/')}}/checkDupPhoneProcurement?id='+x;
                Http.open("GET", url);
              Http.send();
            Http.onreadystatechange=(e)=>{
                var s = (Http.responseText);

                var obj =JSON.parse(s);
                 console.log(obj);

                  
                  if((Http.responseText) != " "){
                              swal({
                                  title:"Are you sure?",
                                  text: "Already Project is listes with number You wan to add Second project?"+ '<br>' + "project_id="+obj[0]['project_id'] + '<br>' + "procurement Name="+obj[0]['procurement_name'] + '<br>' + "procurement Number="+obj[0]['procurement_contact_no'],
                                  type: "info",
                                   // imageUrl: 'thumbs-up.jpg',
                                    html: "Already Project is listes with number You wan to add Second project?"+ '<br>' + "project_id="+obj[0]['project_id'] + '<br>' + "procurement Name="+obj[0]['procurement_name'] + '<br>' + "procurement Number="+obj[0]['procurement_contact_no']+'<br>'+
                                   '<a class="btn btn-primary btn-sm" href='+"{{ URL::to('/') }}/admindailyslots?projectId="+obj[0]['project_id']+'>Edit Project</a>',
                                  showCancelButton: true,
                                  closeOnConfirm: false,
                                  showLoaderOnConfirm: true
                                });
                            
                        }
                    }
              
            }
        }  
        }

    
    return false;
  }
  
    function checkPhone(arg, table)
    {
        var temp;
        $.ajax({
            type: 'GET',
            url: '{{URL::to('/')/checkDupPhone',
            data: {arg: arg, table:table},
            async: false,
            success:function(response)
            {
                console.log(response);
                temp = false;
            }
        });
        return temp;
    }
  
  function check(arg){

    var input = document.getElementById(arg).value;
    if(input){
    if(isNaN(input)){
      while(isNaN(document.getElementById(arg).value)){
      var str = document.getElementById(arg).value;
      alert(str);
      str     = str.substring(0, str.length - 1);
      document.getElementById(arg).value = str;
      }
    }
    else{
      input = input.trim();
      document.getElementById(arg).value = input;
    }
    if(arg == 'ground' || arg == 'basement'){
      var basement = parseInt(document.getElementById("basement").value);
      var ground   = parseInt(document.getElementById("ground").value);
   
      if(!isNaN(basement) && !isNaN(ground)){

        var floor    = 'B('+basement+')' + ' + G + ('+ground+') = ';
        sum          = basement+ground+1;
        floor       += sum;
        
        if(document.getElementById("total").innerHTML != null)
          document.getElementById("total").innerHTML = floor;
        else
          document.getElementById("total").innerHTML = '';
      }
    }
    
  }
    return false;
  }
</script>

<script type="text/javascript">
  function checkthis(arg)
  {
    
     var input = document.getElementById(arg).value;

    if(isNaN(input)){
      while(isNaN(document.getElementById(arg).value)){
          var str = document.getElementById(arg).value;
          str     = str.substring(0, str.length - 1);
          document.getElementById(arg).value = str;
      }
    }
   
          if(arg == 'length' || arg == 'breadth'){
           
            var breadth = parseInt(document.getElementById("breadth").value);
            var length   = parseInt(document.getElementById("length").value);
             if(isNaN(length)){

                length = 0;
               
              }
              if(isNaN(breadth)){

               
                breadth = 0;
              }
            if(!isNaN(breadth) && !isNaN(length)){
              
              var t1 = parseInt(document.getElementById("basement").value);
              var t2 = parseInt(document.getElementById("ground").value);
              sumup  = t1+t2+1;

              var Size    = 'L('+length+')' + '*' + 'B('+breadth+') = ';
              sum1   = length*breadth;
              Size    += sum1;
              var total = sumup * sum1;
              if(document.getElementById("totalsize").innerHTML != null)
                document.getElementById("totalsize").innerHTML = Size;
              else
                document.getElementById("totalsize").innerHTML = '';
               if(document.getElementById("pSize").value != null){
                 document.getElementById("pSize").value = total;
                 document.getElementById("pSizeTag").innerHTML = "This Is Recommended Size. You Can Change If Required!!";
               }else
                document.getElementById("pSize").value = '';
            }
          }
     
    return false;
  }
</script>




<script type="text/javascript">
    var current = "first";
    document.getElementById('headingPanel').innerHTML = 'Project Details';
    function pageNext(){
        var ctype1 = document.getElementById('constructionType1');
        var ctype2 = document.getElementById('constructionType2');
        var rmc = document.getElementById('rmc');
        var rmc2= document.getElementById('rmc2');
         var rmc3= document.getElementById('rmc3');
        if(current == 'first')
        { 
          if(document.getElementById("pName").value == ""){
            swal("You Have Not Entered Project Name");
          }else if(document.getElementById("longitude").value == ""){
            swal("Please Click On Get Location Button");
          }else if(document.getElementById("latitude").value == ""){
            swal("Kindly Click On Get Location Button");
          }else if(document.getElementById("road").value == ""){
            swal("You Have Not Entered Road Name");
          } else if(document.getElementById('rWidth').value == ""){
            swal("You Have Not Entered Road Width");
          }else if(ctype1.checked == false && ctype2.checked == false){
            swal("Please Choose The Construction Type");
          }else if(document.getElementById("contract").value == ""){
            swal("Please Select Contract Type");

          }else if(ctype1.checked == true && ctype2.checked == true){
                countinput = document.querySelectorAll('input[type="checkbox"]:checked').length - 2;
          }else if(ctype1.checked == true || ctype2.checked == true){
                countinput = document.querySelectorAll('input[type="checkbox"]:checked').length - 1;
          }else{
                countinput = document.querySelectorAll('input[type="checkbox"]:checked').length;
                 
          }
          if(countinput == 0){

             swal("Select Atleast One Project Status");
          
          }else if(document.getElementById("basement").value == ""){
           swal("You Have Not Entered Basement Value");
            
          } else if(document.getElementById("ground").value == ""){
           swal("You Have Not Entered Floor Value");
            
          }else if(document.getElementById("length").value == ""){
           swal("You Have Not Entered Length Value");
           
          }else if(document.getElementById("breadth").value == ""){
           swal("You Have Not Entered Breadth Value");
             
          }else if(document.getElementById("pSize").value == ""){
           swal("You Have Not Entered Project Size");
          }else if(constructionType3.checked == false && constructionType4.checked == false){
           swal("Please Choose The Budget Type");
          }else if(document.getElementById("budget").value == ""){
           swal("You have Not Entered Budget");
          }else if (document.getElementById("pImage").value == ""){
           swal("You Have Not Chosen a File To Upload");
          }else if(document.getElementById('floorNo').value == ''){
             swal("Please Select Base/Floor");
          }else if(document.getElementById('rt').value == ''){
            swal("Please Select Room Type");
          }else if(document.getElementById('fsize').value == ''){
            swal("Please Select No.of Houses/No. of Flats");
          }else if(document.getElementById('prName').value == ''){
                    alert('Please Enter a Procurement Details');
                    document.getElementById('prName').focus();
          }else if(document.getElementById('prPhone').value== ''){
                    alert('Please Enter Procurement Phone Number');
                    document.getElementById('prPhone').focus();
          }else{
                          document.getElementById("sub").submit();
           }
           
          
        }
   
</script>

<script type="text/javascript">
 function checkmail(arg){
    var mail = document.getElementById(arg);
    if(mail.value.length > 0 ){
      if (/[a-z0-9._%+-]+@[a-zA-Z]+\.[a-z]{2,3}$/.test(mail.value))  {  
        return true;  
      }  
      else{
        alert("Invalid Email Address!");  
        mail.value = ''; 
        mail.focus(); 
      }
    }
     return false;
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
    //For ground and basement generation
    if(arg == 'ground' || arg == 'basement'){
      var basement = parseInt(document.getElementById("basement").value);
      var ground   = parseInt(document.getElementById("ground").value);
    
      if(isNaN(ground)){

        ground = 0;
       
      }
      if(isNaN(basement)){

       
        basement = 0;
      }
      var opts = "<option value=''>--Floor--</option>";
      if(!isNaN(basement) && !isNaN(ground)){ 
      
        var floor    = 'B('+basement+')' + ' + G + ('+ground+') = ';
        sum          = basement+ground+1;
        fsum          = ground+1;
        var base         = basement;
        floor       += sum;
        
        if(document.getElementById("total").innerHTML != null)
        {
          document.getElementById("total").innerHTML = floor;
          var ctype1 = document.getElementById('constructionType1');
          var ctype2 = document.getElementById('constructionType2');
          if(ctype1.checked == true && ctype2.checked == true){
            // both residential and commercial
            
            var sel = "<td><select class=\"form-control\" name=\"floorNo[]\" id=\"floorNo\">"+
                      "</select></td>"+
                      "<td><select name=\"roomType[]\" value='Commercial Floor' id=\"rt\" class=\"form-control\" >"+
                      "<option value=''>--Select--</option>"+
                      "<option value='1RK'>1RK</option>"+
                      "<option value='1BHK'>1BHK</option>"+
                      "<option value='2BHK'>2BHK</option>"+
                      "<option value='3BHK'>3BHK</option>"+
                      "<option value='4BHK'>4BHK</option>"+
                      "<option value='5BHK'>5BHK</option></select>"+
                      "</td><td>"+
                      "<input type=\"text\" name=\"number[]\" id=\"fsize\" class=\"form-control\" placeholder=\"Floor Size / No. of Houses\"></td>";
            document.getElementById('selection').innerHTML = sel;
           
          }else if(ctype1.checked == true && ctype2.checked == false){
            // residential only

            var sel = "<td><select class=\"form-control\" name=\"floorNo[]\" id=\"floorNo\">"+
                      "</select></td>"+
                      "<td><select name=\"roomType[]\" value='Commercial Floor' id=\"rt\" class=\"form-control\" onchange =\"load();\">"+
                      "<option value=''>--Select--</option>"+
                      "<option value='1RK'>1RK</option>"+
                      "<option value='1BHK'>1BHK</option>"+
                      "<option value='2BHK'>2BHK</option>"+
                      "<option value='3BHK'>3BHK</option>"+
                      "<option value='4BHK'>4BHK</option>"+
                      "<option value='5BHK'>5BHK</option></select>"+
                      "</td><td>"+
                      "<input type=\"text\" name=\"number[]\"  id=\"fsize\" class=\"form-control\" placeholder=\"No. of Houses/No. of Flats\"></td>";
            document.getElementById('selection').innerHTML = sel;
          }else if(ctype1.checked == false && ctype2.checked == true){
            // commercial only
            var sel = "<td><select class=\"form-control\" name=\"floorNo[]\" id=\"floorNo\">"+
                      "</select></td>"+
                      "<td><input name=\"roomType[]\" readonly value='Commercial Floor' id=\"rt\" class=\"form-control\">"+
                      "</td><td>"+
                      "<input type=\"text\" name=\"number[]\" id=\"fsize\" class=\"form-control\" placeholder=\"Floor Size\"></td>";
            document.getElementById('selection').innerHTML = sel;
          }
          for(var i = base; i>0; i--){
            opts += "<option value='Base "+i+"'>Base "+i+"</option>";
          }
          opts += "<option value='Ground'>Ground</option>";
          for(var i = 1; i<fsum; i++){
            opts += "<option value='Floor "+i+"'>Floor "+i+"</option>";
          }

          document.getElementById("floorNo").innerHTML = opts;
        }
        else
          document.getElementById("total").innerHTML = '';

      }
    }
    return false;
  }
  function addRow() {
        var table = document.getElementById("bhk");
        var row = table.insertRow(0);
        var cell3 = row.insertCell(0);
        var cell1 = row.insertCell(1);
        var cell2 = row.insertCell(2);
        var ctype1 = document.getElementById('constructionType1');
        var ctype2 = document.getElementById('constructionType2');
        var existing = document.getElementById('floorNo').innerHTML;
        if(ctype1.checked == true && ctype2.checked == false){
          cell3.innerHTML = "<select onchange='enableoption()' name='floorNo[]' class='form-control'>"+existing+"</select>";
          cell1.innerHTML = "<select onchange='checkinput(this.value)' name=\"roomType[]\" class=\"form-control\">"+
                                                          "<option value=\"\">--Select--</option><option value=\"1RK\">1RK</option>"+
                                                          "<option value=\"1BHK\">1BHK</option>"+
                                                          "<option value=\"2BHK\">2BHK</option>"+
                                                          "<option value=\"3BHK\">3BHK</option>"+
                                                          "<option value=\"4BHK\">4BHK</option>"+
                                                          "<option value=\"5BHK\">5BHK</option>"+
                                                      "</select>";
          cell2.innerHTML = "<input name=\"number[]\" type=\"text\" class=\"form-control\" placeholder=\"No. of houses\">";
        }
        if(ctype1.checked == false && ctype2.checked == true){
          cell3.innerHTML = "<select name='floorNo[]' class='form-control'>"+existing+"</select>";
          cell1.innerHTML = "<input name=\"roomType[]\" value='Commercial Floor' id=\"\" class=\"form-control\">";
          cell2.innerHTML = "<input type=\"text\" name=\"number[]\" class=\"form-control\" placeholder=\"Floor Size\"></td>";
        }
        if(ctype1.checked == true && ctype2.checked == true){
          // both residential and commercial
          cell3.innerHTML = "<select onchange='enableoption()' name='floorNo[]' class='form-control' id=\"floorNo2\" onchange =\"count(this.value);\">"+existing+"</select>";
          cell1.innerHTML = " <select onchange='checkinput(this.value)' name=\"roomType[]\" class=\"form-control\" onchange =\"count(this.value);\" id=\"floorNo1\">"+
                                                          "<option value=\"\">--Select</option><option value=\"Commercial Floor\">Commercial Floor</option>"+
                                                          "<option value=\"1RK\">1RK</option>"+
                                                          "<option value=\"1BHK\">1BHK</option>"+
                                                          "<option value=\"2BHK\">2BHK</option>"+
                                                          "<option value=\"3BHK\">3BHK</option>"+
                                                          "<option value=\"4BHK\">4BHK</option>"+
                                                          "<option value=\"5BHK\">5BHK</option>"+
                                                      "</select>";
          cell2.innerHTML = "<input name=\"number[]\" type=\"text\" class=\"form-control\" placeholder=\"No. of houses\">";
        }
    }
    var numbers = [];
    function checkinput(arg){
      var floorNo = document.getElementsByName('floorNo[]');
      var roomType = document.getElementsByName('roomType[]');
      var floors = [];
      var rooms = [];
      var myIndex = roomType[0].selectedIndex;
      for(var i = 0; i < floorNo.length; i++){
        floors.push(floorNo[i].value);
        rooms.push(roomType[i].value);
      }
      for(var j = 0; j < floors.length; j++){
        if(floors[j] == floors[j + 1]){
          for(i = j+1; i < rooms.length; i++){
            if(rooms[j] == rooms[i]){
              alert("This room type has been already selected");
              roomType[0].options[myIndex].disabled = true;
              roomType[0].selectedIndex = 0;
              break;
            }
          }
        }
      }
    }
    function enableoption(){
      var roomType = document.getElementsByName('roomType[]');
      for(var i = 1; i < 6; i++){
        roomType[0].options[i].disabled = false;
      }
    }
    function count(){

      var status = document.getElementsByName('status[]');
      var selected = "";
      var check = 0;
      // first 3 stages + last stage
      for(var i = 0; i < status.length; i++){
        if(status[i].checked == true)
          check += 1;
      }
      if(check == 0){
        for(var i = 0; i < status.length; i++){
          status[i].disabled = false;
        }
      }
      if(check == 1){
        if(status[0].checked == true || status[1].checked == true || status[2].checked == true || status[status.length - 1].checked == true){
          for(var i = 0; i < status.length; i++){
            if(status[i].checked != true)
              status[i].disabled = true;
          }
        }else if(status[3].checked == true){
          // pillars
          numbers = [3,4,5];
          for(var i = 0; i < status.length; i++){
            var a = numbers.indexOf(i);
            if(a == -1){
              status[i].disabled = true;

            }
          }
        }else if(status[4].checked == true){
          // walls
          numbers = [3,4,5,6,7];
          for(var i = 0; i < status.length; i++){
            var a = numbers.indexOf(i);
            if(a == -1){
              status[i].disabled = true;
            }
          }
        }else if(status[5].checked == true){
          // roofing
          numbers = [4,5,6,7];
          for(var i = 0; i < status.length; i++){
            var a = numbers.indexOf(i);
            if(a == -1){
              status[i].disabled = true;
            }
          }
        }else if(status[6].checked == true){
          // electrical
          numbers = [6,7,9,12];
          for(var i = 0; i < status.length; i++){
            var a = numbers.indexOf(i);
            if(a == -1){
              status[i].disabled = true;
            }
          }
        }else if(status[7].checked == true){
          // plumbing
          numbers = [6,7,8,10,12];
          for(var i = 0; i < status.length; i++){
            var a = numbers.indexOf(i);
            if(a == -1){
              status[i].disabled = true;
            }
          }
        }else if(status[8].checked == true){
          // plastering
          numbers = [6,7,8];
          for(var i = 0; i < status.length; i++){
            var a = numbers.indexOf(i);
            if(a == -1){
              status[i].disabled = true;
            }
          }
        }else if(status[9].checked == true){
          // flooring
          numbers = [9,10,12,13];
          for(var i = 0; i < status.length; i++){
            var a = numbers.indexOf(i);
            if(a == -1){
              status[i].disabled = true;
            }
          }
        }else if(status[10].checked == true){
          // carpentry
          numbers = [10,11,12];
          for(var i = 0; i < status.length; i++){
            var a = numbers.indexOf(i);
            if(a == -1){
              status[i].disabled = true;
            }
          }
        }else if(status[11].checked == true){
          // paintings
          numbers = [6,10,11,12];
          for(var i = 0; i < status.length; i++){
            var a = numbers.indexOf(i);
            if(a == -1){
              status[i].disabled = true;
            }
          }
        }else if(status[12].checked == true){
          // fixtures
          numbers = [10,12];
          for(var i = 0; i < status.length; i++){
            var a = numbers.indexOf(i);
            if(a == -1){
              status[i].disabled = true;
            }
          }
        }else if(status[13].checked == true){
          // plastering
          numbers = [8,11,12,13];
          for(var i = 0; i < status.length; i++){
            var a = numbers.indexOf(i);
            if(a == -1){
              status[i].disabled = true;
            }
          }
        }else{
          for(var i = 0; i < status.length; i++){
            status[i].disabled = false;
          }
        }
      }
      // var ctype1 = document.getElementById('constructionType1');
      // var ctype2 = document.getElementById('constructionType2');
      // var ctype3 = document.getElementById('constructionType3');
      // var ctype4 = document.getElementById('constructionType4');
      // var countinput;
    //   if(ctype1.checked == true && ctype2.checked == true && ctype3.checked == false && ctype4.checked == false){
    //     //   both construction type
    //     countinput = document.querySelectorAll('input[type="checkbox"]:checked').length - 2;
    //   }else if(ctype1.checked == true && ctype2.checked == true && ctype3.checked == true && ctype4.checked == true){
    //     //   all construction type and budget type
    //     countinput = document.querySelectorAll('input[type="checkbox"]:checked').length - 4;
    //   }else if(ctype1.checked == true && ctype2.checked == true && (ctype3.checked == true || ctype4.checked == true)){
    //     //   both construction type and either budget type
    //     countinput = document.querySelectorAll('input[type="checkbox"]:checked').length - 3;
    //   }else if((ctype1.checked == true || ctype2.checked == true) && (ctype3.checked == true || ctype4.checked == true)){
    //     //   
    //     countinput = document.querySelectorAll('input[type="checkbox"]:checked').length - 2;
    //   }else if(ctype1.checked == true || ctype2.checked == true){
    //     countinput = document.querySelectorAll('input[type="checkbox"]:checked').length - 1;
    //   }else{
    //     countinput = document.querySelectorAll('input[type="checkbox"]:checked').length;
    //   }
    //   if(countinput >= 5){
    //     $('input[type="checkbox"]:not(:checked)').attr('disabled',true);
    //     $('#constructionType1').attr('disabled',false);
    //     $('#constructionType2').attr('disabled',false);
    //     $('#constructionType3').attr('disabled',false);
    //     $('#constructionType4').attr('disabled',false);
    //   }else if(countinput == 0){
    //       return "none";
    //   }else{
    //     $('input[type="checkbox"]:not(:checked)').attr('disabled',false);
    //   }
      // if(document.getElementById('planning').checked == true || document.getElementById('closed').checked == true){
      //   $('input[type="checkbox"]:not(:checked)').attr('disabled',true);
      //   $('#constructionType1').attr('disabled',false);
      //   $('#constructionType2').attr('disabled',false);
      //   $('#constructionType3').attr('disabled',false);
      //   $('#constructionType4').attr('disabled',false);
      // }else{
      //   $('input[type="checkbox"]:not(:checked)').attr('disabled',false);
      // }
    }
    function fileUpload(){
      var count = document.getElementById('oApprove').files.length;
      if(count > 5){
        document.getElementById('oApprove').value="";
        alert('You are allowed to upload a maximum of 5 files');
      }
    }
    function fileuploadimage(){ 
      var count = document.getElementById('pImage').files.length;
      if(count > 4){
        document.getElementById('pImage').value="";
        alert('You are allowed to upload a maximum of 4 files');
      }
    }

</script>
  <!-- Modal -->
@if(session('test'))
  <div class="modal fade" id="Material" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #c9ced6;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Success</h4>
        </div>
        <div class="modal-body">
          <p style="text-align:center;">{!! session('test') !!}</p>
        </div>
        <div class="modal-footer">
          <button type="button" style="background-color: #c9ced6;" class="btn btn-default" data-dismiss="modal" onClick="window.location.reload()">Close</button>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
  $(document).ready(function(){
      $("#Material").modal('show');
  });
</script>
@endif

<script>
function display(){
    if (document.getElementById("constructionType3").checked){
        document.getElementById('constructionType4').disabled=true;
    }
}
function validateForm(arg)
{
    var x=document.getElementById(arg).value;
    var atpos=x.indexOf("@");
    var dotpos=x.lastIndexOf(".");
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
    {
        alert("Please enter a valid email address.");
        return false;
    }
}

</script>


  <!-- Modal -->
@if(session('test'))
  <div class="modal fade" id="Material" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #c9ced6;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Success</h4>
        </div>
        <div class="modal-body">
          <p style="text-align:center;">{!! session('test') !!}</p>
        </div>
        <div class="modal-footer">
          <button type="button" style="background-color: #c9ced6;" class="btn btn-default" data-dismiss="modal" onClick="window.location.reload()">Close</button>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
  $(document).ready(function(){
      $("#Material").modal('show');
  });
</script>
@endif
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

</body>
</html>

