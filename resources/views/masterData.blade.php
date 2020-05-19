@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default" style="border-color: green;">
                <div class="panel-heading" style="background-color: green;color:white;" >Countries</div>
                <div class="panel-body">
                    <form method="POST" action="{{ URL::to('/') }}/addCountry">
                        {{ csrf_field() }}
                        <table class="table">
                            <tr>
                                <td>
                                    <select required onchange="countryCode();" name="name" id="country" class="form-control input-sm">

                                    </select>
                                </td>
                                <td><input required id="code" readonly required type="text" class="form-control input-sm" name="code" placeholder="Code"></td>
                                <td><input type="submit" class="btn btn-primary btn-sm" value="Add"></td>
                            </tr>
                        </table>
                    </form>
                    <table class="table">
                        <thead>
                            <th>Country Code</th>
                            <th>Country Name</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach($countries as $country)
                                <tr>
                                    <td>{{ $country->country_code }}</td>
                                    <td>{{ $country->country_name }}</td>
                                    <td><button class="btn btn-sm btn-danger">Delete</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default" style="border-color: green;" >
                <div class="panel-heading" style="background-color: green;color:white;" >Zones
                    @if(session('ErrorZone'))
                        <div class="alert-danger pull-right">{{ session('ErrorZone' )}}</div>
                    @endif 
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ URL::to('/') }}/addZone" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <table class="table">
                                <tr>
                                    <td>
                                        <select  class="form-control input-sm" name="sId" required="" id="sId">
                                            <option value="">--Country--</option>
                                            @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select required name="zone_name" id="zoneName" class="form-control input-sm">
                                            <option value="">--Select--</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="zone_no" id="zno" onkeyup="checkthis('zno')" required class="form-control input-sm" placeholder="Zone No."></td>

                                    <td><input type="file" name="image" required class="form-control input-sm" accept="image/*"></td>
                                    <td><button type="submit" class="btn btn-success input-sm">Add</button></td>
                                </tr>
                            </table>
                        </form>
                        <table class="table">
                            <thead>
                                <th>Country</th>
                                <th>Zone Name</th>
                                <th>Zone No</th>
                                <th>Zone Map</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach($zones as $zone)
                                    <tr>
                                        <td>{{ $zone->country->country_name }} </td>
                                        <td>{{ $zone->zone_name }}</td>
                                        <td>{{ $zone->zone_number }}</td>
                                        <td style="width:10%"><center><a href="{{ URL::to('/')}}/viewMap?zoneId={{ $zone->id}}" class="btn btn-sm btn-primary" target="_blank">Map With Subwards</a>
                                        <a href="{{ URL::to('/') }}/wardmaping?zoneId={{ $zone->id }}" class="btn btn-success btn-sm">Map Without Subwards</a></center>
                                            </td>
                                        </td>
                                        <td>
                                            <a href="{{ URL::to('/') }}/wardmaping?zoneId={{ $zone->id }}" class="btn btn-success btn-sm">Edit</a>
                                            </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default" style="border-color: rgb(244, 129, 31);" >
                <div class="panel-heading" style="background-color: rgb(244, 129, 31);color:white;" >Wards</div>
                <div class="panel-body">
                    <form method="POST" action="{{ URL::to('/') }}/addWard" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <table class="table">
                            <tr>
                                <td>
                                    <select name="country" required class="input-sm form-control">
                                        <option value="">--Country--</option>
                                        @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select required name="zone" class="input-sm form-control">
                                        <option value="">--Zone--&nbsp;&nbsp;&nbsp;</option>
                                        @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->zone_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="text" name="name" required placeholder="Ward Name" class="form-control input-sm"></td>
                                <td><input type="file" name="image" required class="form-control input-sm" accept="image/*" ></td>
                            </tr>
                        </table>
                        <button type="submit" class="form-control btn btn-success input-sm">Add</button>
                    </form>
                    <br>
                        <table class="table table-responsive">
                            <thead>
                                <th>Ward Name</th>
                                <th><center>Ward Map</center></th>
                                <th><center>Action</center></th>
                            </thead>
                            <tbody>
                                    @foreach($wards as $ward)
                                    <tr>
                                        <td style="width:20%">{{ $ward->ward_name }}</td>
                                       
                                        <td style="width:33%"><center><a href="{{ URL::to('/') }}/viewMap?wardId={{ $ward->id }}" class="btn btn-sm btn-primary" target="_blank">View Map</a></center></td>
                                        <td><a href="{{ URL::to('/') }}/wardmaping?wardId={{ $ward->id }}" class="btn btn-success btn-sm form-control">Edit</a></td>
                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default" style="border-color:rgb(244, 129, 31); " >
                <div class="panel-heading" style="background-color: rgb(244, 129, 31);color:white;" >Sub Wards</div>
                <div class="panel-body" style=" height: 700px; max-height: 700px; overflow-y:scroll; overflow-x: hidden;">
                    <form method="POST" action="{{ URL::to('/') }}/addSubWard" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <table class="table table-responsive table-hover">
                            <tr>
                                <td>
                                    <select name="ward" required class="form-control input-sm">
                                        <option value="">Ward&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                                        @foreach($wards as $ward)
                                            <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="name" required placeholder="Subward Name" class="form-control input-sm"></td>
                                <td><input type="file" name="image" required class="form-control input-sm" accept="image/*"></td>
                                <td><button type="submit" class="btn btn-success input-sm">Add</button></td>
                            </tr>
                        </table>
                    </form>
                        <table class="table table-responsive table-hover">
                            <thead>
                                <th>Subward Name</th>
                                <th><center>Subward Map</center></th>
                                <th><center>Action</center></th>
                            </thead>
                            <tbody>
                                    @foreach($subwards as $ward)
                                        <tr>
                                            <td style="width:20%">{{ $ward->sub_ward_name }}</td>
                                            <td style="width:33%"><center><a href="{{ URL::to('/')}}/viewMap?subWardId={{ $ward->id}}" class="btn btn-sm btn-primary" target="_blank">View Map</a></center></td>
                                            <td><a href="{{ URL::to('/') }}/wardmaping?subWardId={{ $ward->id }}" class="btn btn-success btn-sm form-control">Edit</a></td>
                                        </tr>
                                    @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>

   
    
    
    <div class="col-md-6">
            <div class="panel panel-default" style="border-color:rgb(244, 129, 31); " >
                <div class="panel-heading" style="background-color: rgb(244, 129, 31);color:white;" ><b> Add Districts to wards</b></div>
                <div class="panel-body" style=" height: 700px; max-height: 700px; overflow-y:scroll; overflow-x: hidden;">
                <form method="POST" action="{{ URL::to('/') }}/addDistrict" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <table class="table table-responsive table-hover">
                           
                            
                           <table>
                                <thead>
                                <th>Zones</th>
                            <td>
                                    <select required name="zone" class="input-sm form-control"   id="zone_wise" onchange="loadwards()">
                                        <option value="">--Zone--&nbsp;&nbsp;&nbsp;</option>
                                        @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->zone_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                </tr>
                                <br>
                                <thead>
                            <th>Wards</th>
                                <td>
                                    <select name="ward" required   class="input-sm form-control" id="ward_wise">
                                        
                                       
                                    </select>
                                </td>
                            </tr>
                      <thead>
                          <tr>
                              <th></th>
                        <td></td>  
                        </tr>

                       
                        <tr>
                        <thead>
                        <th><b>Select States</th>
                        <td>  
                            
                                    <select required name="dist_states"    class="input-sm form-control" id="dist_states" onchange="loadstatesdists()">
                                        <option value="">--state--&nbsp;&nbsp;&nbsp;</option>
                                        @foreach($dist_states as $dist_state)
                                        <option value="{{ $dist_state->id }}">{{ $dist_state->name }}</option>
                                      
                                        @endforeach
                                    </select>
                                </tr>
                               
                                </td> <tr>
                                <br>
                                <tr>
                              
                         <thead>
                                <th><b>Select Districts</th>
                                                  
                                 <td><select required   name="dist" class="input-sm form-control" id="dist" >  
                                     <option value="">--dist--&nbsp;&nbsp;&nbsp;</option></select> </td>
                                </tr>
                                <td>
                                <br>
                                <br>

                        <button type="submit" class="btn btn-success input-sm">Add</button>
                    </form>
                </table>
                </div>
                </div>
                </div>

                <div class="col-md-6">
                <div class="panel panel-default" style="border-color:rgb(244, 129, 31); " >
                <div class="panel-heading" style="background-color: rgb(244, 129, 31);color:white;" >Districts</div>
                <div class="panel-body" style=" height: 700px; max-height: 700px; overflow-y:scroll; overflow-x: hidden;">
                <div class="col-sm-6">
                        <table class="table table-responsive table-hover">
                            <thead>
                            <tr>
                            <p>List of wards</p>
                            <tr>  
                                        <tr>
                            <tr>  
                                        <tr>
                            <tr>  
                                        <tr>
                            <tr>  
                                @foreach($wards as $ward)
                                        <tr>
                                        <td style="width:20%"> <a onclick="getdists({{$ward->id}})"  type="submit" class="btn btn-success input-sm">{{ $ward->ward_name }}</button></td></a>
                                        </tr>
                                    @endforeach
                            </tr>
                        </table>
                    </div>
                  
                    <div class="col-sm-6">
                    <div class="panel-heading"  style="background-color: rgb(244, 129, 31);color:white;" >List of Districts</div>
                    <div class="panel-body">
                                      
                                                    <p id="distandstate"> </p>  
                    </div>
                </div>       
                        </div>
                        </div>
                    </div>
                  
                <div class="col-md-12">
                    <div class="panel panel-default" style="border-color:rgb(244, 129, 31);">
                        <div class="panel-heading" style="background-color: rgb(244, 129, 31);color:white;" ><b> Add Customer  Type</b></div>
                        <div class="panel-body">
                                <div class="col-md-3">  
                            <form method="POST" action="{{ URL::to('/') }}/addCustomertype" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                            
                                    <table class="table">
                                        <tr>
                                            <thead>
                                                    <label>Customer Type Name</label>
                                            </thead>
                                        </tr>
                                        <br>
                                        <thead>
                                          <tr>  
                                                <td><input type="text" name="cust_type" required placeholder="Enter Customer Type Name " class="form-control input-sm"></td>
                                                <td><button type="submit" class="form-control btn btn-success input-sm">Add</button></td>   
                                         </tr>
                                            
                                           
                                            </thead>
                                        </table>
                            </form>
                        </div>
                    

                             <div class="col-sm-3">
                                <div class="panel-heading"  style="background-color: rgb(244, 129, 31);color:white;" >List of Customer Types</div>
                                    <div class="panel-body">
                                        <table class="table" border="0">         
                                        <thead>
                                        <tr>
                                         <td>
                                                <form method="POST" action="{{ URL::to('/') }}/addSubCustomers" enctype="multipart/form-data">
                                                    {{ csrf_field() }}
                                        <select class="form-control" name="cust_name">
                                            <option value="">---Select  Type----</option>    
                                            @foreach($customer_details as $customer_detailsa) 
                                            
                                                <option value="{{$customer_detailsa->cust_type_id}}"> {{$customer_detailsa->cust_type}}</option>
                                            @endforeach
                                        </select>
                                        <br>
                                        <label>Add Sub Customer Type</label>
                                        <br>
                                        <input type="text" name="cust_type" required placeholder="Enter Sub Customer Type Name " class="form-control input-sm">
                                        </label>
                                        <br>
                                        <button type="submit" class="form-control btn btn-success input-sm">Add</button> 
                                        
                                        </form>
                                        </td>
                                    </tr> 
                                   
                                            <table>
                                            <thead>
                                                
                                                </thead>
                                        </table>
                                    </div>
                              
                            </table>
                             </div>
                             <div class="col-sm-6">
                                    <div class="panel-heading"  style="background-color: rgb(244, 129, 31);color:white;" >List of Sub Customer Types</div>
                                        <div class="panel-body">
                                        
                                             <table class="table" border="1">
                                                 <thead>
                                                      <th>Customer Types</th>
                                                      <th>Sub Customers</th>
                                                 </thead>
                                                 <tbody>
                                                        @foreach($sub_customer_details as $sub_customer_detailss) 
                                                     <tr>
                                                         <td> <?php $s = App\CustomerType::where('cust_type_id',$sub_customer_detailss->sub_customer_id)->pluck('cust_type')->first(); ?>
                                                            {{$s}}</td>
                                                        <td> {{$sub_customer_detailss->cust_type}}   </td>
                                                     </tr>
                                                     @endforeach
                                                    </tbody>
                                                </table>

                                            
                                        
                                        </div>
                                        
                            </div>    
                             </div>
                            </form>
                            </div>
                          
                                 
                </div>
                
               
                             
<div id="coutries" class="hidden">
    

<div id="countriesAndStates" class="hidden">

<div id="states&dist" class="hidden">



    

   
    <script type="text/javascript"> 
    var xmlhttp, myObj, x, txt = "", displaycountries = "";
    var countries = [];
    var single = [];
    function editwardimage(arg)
    {
        var photo = document.getElementById("wardimage"+arg);
        alert(photo);
        return false;
    }

    function displaySelectedStates(){
        var countryChosen = document.getElementById("sId").selectedIndex;
        var textForCountry = document.getElementById("sId").options[countryChosen].text;
        var differentLocations = document.getElementById("disp" + textForCountry).innerHTML;
        var states = differentLocations.split(",");
        var str = "<option value=''>--Select--</option>";
        for(var i = 0; i < states.length; i++){
            str += "<option value='" + states[i] + "'>" + states[i] + "</option>";
        }
        document.getElementById("zoneName").innerHTML = str;
    }

    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            myObj = JSON.parse(this.responseText);
            txt += "<option value=''>--Select--</option>";
            for (x in myObj) {
                txt += "<option value='" + myObj[x]["country"] + "'>" + myObj[x]["country"] + "</option>";
                displaycountries += "<div id='" + myObj[x]["country"] + "'>" + myObj[x]["code"] + "</div>";
            }
            document.getElementById("country").innerHTML = txt;
            document.getElementById("coutries").innerHTML = displaycountries;
        }
    };
    xmlhttp.open("GET", "http://localhost/mamabig/mamabig/countries.json", true);
    xmlhttp.send();
    function countryCode(){
        var countrySelected = document.getElementById('country').value;
        var countryCode = document.getElementById(countrySelected).innerHTML;
        document.getElementById('code').value = countryCode;
    }
    var displayStates = "";
    xmlhttp2 = new XMLHttpRequest();
    xmlhttp2.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            myObj = JSON.parse(this.responseText);
            for (x in myObj) {
                displayStates += "<div id='disp" + x +"'>" + myObj[x] + "</div>";
            }
            document.getElementById("countriesAndStates").innerHTML = displayStates;
        }
    };
    xmlhttp2.open("GET", "http://localhost/mamabig/mamabig/states&dist.json", true);
    xmlhttp2.send();


    
</script>
<script>
    function checkthis(arg){
        var input = document.getElementById(arg).value;
         if(isNaN(input)){
        
               document.getElementById(arg).value = "";
        }
    }
</script>

<script type="text/javascript">

    function getdists(arg)
    {
        
        var sel = arg;
        if(sel)
        {
            $.ajax({
                type: "GET",
                url: "{{URL::to('/')}}/getDistwithstates",
                data: { ward_id: sel },
                async: false,
                success: function(response)
                {
                    if(response == 'No disticts Found !!!')
                    {
                        document.getElementById('error').innerHTML = '<h4>No disticts Found !!!</h4>';
                        document.getElementById('error').style,display = 'initial';
                    }
                    else
                    {
                        var html="";
                        
                        for(var i=0; i< response.length; i++)
                        {
                            html +=  "<table  class=table table-responsive table-hover><thead><th>Districts Name</th><th>Pincode</th></thead><tbody><tr><td><p>"+response[i].name+"</p></td><td><p>"+response[i].pin +"</p></td></tr></tbody></table>";       
                        }
                        document.getElementById('distandstate').innerHTML = html;
                  }
                    
                }
               
            });
        }
    }

    function loadstatesdists()
    {
        var x = document.getElementById('dist_states');
        var sel = x.options[x.selectedIndex].value;
        if(sel)
        {
            $.ajax({
                type: "GET",
                url: "{{URL::to('/')}}/loaddist",
                data: { dist_id: sel },
                async: false,
                success: function(response)
                {
                    if(response == 'No disticts Found !!!')
                    {
                        document.getElementById('error').innerHTML = '<h4>No disticts Found !!!</h4>';
                        document.getElementById('error').style,display = 'initial';
                    }
                    else
                    {
                        var html = "<option value='' disabled selected>---Select---</option>";
                        for(var i=0; i< response.length; i++)
                        {
                            html += "<option value='"+response[i].id+"'>"+response[i].name+"</option>";
                           
                        }
                        document.getElementById('dist').innerHTML = html;
                        document.getElementById('distone').value =response[0].pin;
                    }
                    
                }
            });
        }
    }

    function loadwards()
    {
        var x = document.getElementById('zone_wise');
        var sel = x.options[x.selectedIndex].value;
       
        if(sel)
        {
            $.ajax({
                type: "GET",
                url: "{{URL::to('/')}}/load_wards",
                data: { zone_id: sel },
                async: false,
                success: function(response)
                {
                    console.log(response);
                    if(response == 'No wards Found !!!')
                    {

                        document.getElementById('error').innerHTML = '<h4>No wards Found !!!</h4>';
                        document.getElementById('error').style,display = 'initial';
                    }

               
                    else
                    {
                        var html = "<option value='' disabled selected>---Select---</option>";
                        for(var i=0; i< response.length; i++)
                        {
                            html += "<option value='"+response[i].id+"'>"+response[i].ward_name+"</option>";
                           
                        }
                        document.getElementById('ward_wise').innerHTML = html;
                     
                    }
                    
                }
            });
        }
    }
</script>
@endsection
