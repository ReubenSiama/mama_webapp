@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        <form method="POST" onsubmit="validateform()" action="{{ URL::to('/') }}/plots_add" enctype="multipart/form-data">
            <div class="panel panel-default">
                <div class="panel-heading" style="background-color:#42c3f3;color:#ffffffe3;padding:5px;">
             <div id="currentTime" class="pull-right" style="margin--5px;"></div>
            <div id="currentTime" class="pull-center" style="margin--5px;">
              <h4><center>Listing Of Plots Or Villas</center></h4>
            </div>
                </div>
                <div class="panel-body">
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
                                   <td><input id="rWidth" required onkeyup="check('rWidth')"  required type="number" placeholder="Road Width In Sq. Ft." class="form-control input-sm" name="rWidth" value="{{ old('rWidth') }}" required></td>
                                  
                               </tr>
                               <tr class="{{ $errors->has('address') ? ' has-error' : '' }}">
                                   <td>Full Address</td>
                                   <td>:</td>
                                   <td><input readonly id="address" required type="text" placeholder="Full Address" class="form-control input-sm" name="address" value="{{ old('address') }}"></td>
                               </tr>
                               <tr>
                                <td>Interested In Bank Loans ?</td>
                                <td>:</td>
                                <td>
                                   
                                     <label><input required value="Yes" id="loan1" type="radio" name="loaninterest"><span>&nbsp;</span>Yes</label>
                                     <span>&nbsp;&nbsp;&nbsp;  </span>
                                 
                                     <label><input required value="No" id="loan2" type="radio" name="loaninterest"><span>&nbsp;</span>No</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                               
                                     <label><input checked="checked" required value="None" id="loan3" type="radio" name="loaninterest"><span>&nbsp;</span>None</label>
                                  
                                </td>
                              </tr>
                              <tr>
                                <td>Interested In JV(Joint Venture)?</td>
                                <td>:</td>
                                <td>
                                   
                                     <label><input required value="Yes" id="jv1" type="radio" name="jvinterest"><span>&nbsp;</span>Yes</label>
                                     <span>&nbsp;&nbsp;&nbsp;  </span>
                                 
                                     <label><input required value="No" id="jv2" type="radio" name="jvinterest"><span>&nbsp;</span>No</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                               
                                     <label><input checked="checked" required value="None" id="jv3" type="radio" name="jvinterest"><span>&nbsp;</span>None</label>
                                  
                                </td>
                              </tr>
                              <tr>
                                <td>Architects Are Required?</td>
                                <td>:</td>
                                <td>
                                   
                                     <label><input required value="Yes" id="ar1" type="radio" name="architects_required"><span>&nbsp;</span>Yes</label>
                                     <span>&nbsp;&nbsp;&nbsp;  </span>
                                 
                                     <label><input required value="No" id="ar2" type="radio" name="architects_required"><span>&nbsp;</span>No</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                               
                                     <label><input checked="checked" required value="None" id="ar3" type="radio" name="architects_required"><span>&nbsp;</span>None</label>
                                  
                                </td>
                              </tr>
                              <tr>
                                <td>Project Type</td>
                                <td>:</td>
                                <td>
                                <label required class="checkbox-inline" style="color:#42c3f3;"><input id="constructionType1" name="type[]" type="checkbox" value="Plots">Plots</label>
                                <label required class="checkbox-inline" style="color:#42c3f3;"><input id="constructionType1" name="type[]" type="checkbox" value="Apartments">Apartments</label>
                                <label required class="checkbox-inline" style="color:#42c3f3;"><input id="constructionType2" name="type[]" type="checkbox" value="Flats">Flats</label> 
                                <label required class="checkbox-inline" style="color:#42c3f3;"><input id="constructionType2" name="type[]" type="checkbox" value="Commercial_Complexes">Commercial Complexes</label> 
                                <label required class="checkbox-inline" style="color:#42c3f3;"><input id="constructionType2" name="type[]" type="checkbox" value="Individual_villas">Individual Villas </label> 
                                
                            
                            </td>
                              </tr>
                               <tr>
                                   <td>Plot Size</td>
                                   <td>:</td>
                                   <td>
                                    <div class="row">
                                        <div class="col-md-3">
                                          <input value="{{ old('length') }}" required  id="length" name="length" type="text" autocomplete="off" class="form-control input-sm" placeholder="Length" >
                                        </div>
                                        <div class="col-md-2">
                                          <b style="font-size: 20px; text-align: center">*</b>
                                        </div>
                                      <div class="col-md-3">
                                        <input value="{{ old('breadth') }}" required onkeyup="checkthis()" autocomplete="off" name="breadth" id="breadth" type="text" class="form-control" placeholder="Breadth">
                                      </div>
                                      <div class="col-md-3">
                                        <p id="totalsize"></p>
                                      </div>
                                    </div>
                                    </td>
                               </tr>
                             
                                <tr>
                                    <td>Plot Size (Approx.)</td>
                                    <td>:</td>
                                    <td id= "totalofsize">
                                     <div class="col-md-4 pull-left">
                                     <input value="{{ old('pSize') }}" id="pSize" required placeholder="Plot Size In Sq. Ft." type="text" class="form-control input-sm" name="total_plot_size" >
                                     </div>
                                     <div class="col-md-8 alert-success pull-right" id="pSizeTag"></div>
                                   </td>
                                </tr>
                               <tr>
                                 <td>Budget Type</td>
                                 <td>:</td>
                                 <td >
                                    <label ><input id="constructionType3" name="budgetType[]"  type="radio" value="Structural" required ><span>&nbsp;</span>Structural</label>
                                    <span>&nbsp;&nbsp;</span>
                                    <label ><input id="constructionType4" name="budgetType[]"  type="radio" value="Finishing" required><span>&nbsp;</span>Finishing </label> 
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
                               
                           </table>
                          </div>
<button style="width: 100%;font-size: 20px;"  class="btn btn btn-primary btn-sm">Customer Details</button><br>
<br>

  <center><label> Plot Owner Details</label></center>
  <br>
                           <table class="table" border="1">
                               <tr>
                                   <td>Owner Name</td>
                                   <td>:</td>
                                   <td><input value="{{ old('oName') }}" required type="text" placeholder="Owner Name" class="form-control input-sm" name="oName" ></td>
                               </tr>
                               <tr>
                                   <td>Owner Email</td>
                                   <td>:</td>
                                   <td><input value="{{ old('oEmail') }}" onblur="checkmail('oEmail')" placeholder="Owner Email" type="email"  class="form-control input-sm" name="oEmail" ></td>
                               </tr>
                               <tr>
                                   <td>Owner Contact No.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ old('oContact') }}" required onblur="checklength('oContact');" onkeyup="check('oContact','1')" maxlength="10"  minlength="10" placeholder="Owner Contact No." type="text" class="form-control input-sm" name="oContact" ></td>
                               </tr>
                           </table>
                        



                       <br>
                <center><label>Builder Or Developer Details</label></center><br>
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
                            <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
                            <button type="submit" id="sub" class="form-control btn btn-info" onclick="pageNext()" onsubmit="show()">Save </button>
                          <!--  <li class="next"><a id="next" href="#" onclick="pageNext()">Next</a></li> -->
                     </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://maps.google.com/maps/api/js?sensor=true"></script>
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

  function fileuploadimage(){ 
    var count = document.getElementById('pImage').files.length;
    if(count > 4){
      document.getElementById('pImage').value="";
      alert('You are allowed to upload a maximum of 4 files');
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
    alert("Clear the Location Info from your Browser " + errorMessage);
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
    function checkthis(arg)
    {
      var x = document.getElementById('length').value;
      var y = document.getElementById('breadth').value;
         var total = (x*y);
    document.getElementById("pSize").value = total;
    document.getElementById("totalsize").value = total;
      document.getElementById("pSizeTag").innerHTML = "This Is Recommended Size. You Can Change If Required!!";

    }
  </script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4qwyvakFX_G7zDFRcaUjk0mLMygpX4XE&libraries=geometry&callback=initMap"></script>

@endsection