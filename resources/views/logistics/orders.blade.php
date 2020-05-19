<?php
    $use = Auth::user()->group_id;
      $da = [1,2];
    if((in_array($da, $da))){
    $ext = "layouts.app";
    }else{
     $ext = "layouts.leheader";
    }
?>
<?php $url = Helpers::geturl(); ?>
@extends($ext)
@section('content')

<div class="col-md-12 col-sm-12">
	<div class="panel panel-primary" style="overflow-x: scroll;">
	<div class="panel-heading text-center">
		<b style="color:white;font-size:1.4em">
			Assigned Logistic Orders &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total Count : {{ count($view) }}
		</b>
		<a class="pull-right btn btn-sm btn-danger" href="{{URL::to('/')}}/home" id="btn1" style="color:white;">
			<b>Back</b>
		</a>
	</div>

	<div id="orders" class="panel-body">
		<table class="table table-responsive table-striped">
			<thead>
				<th style="text-align:center">Project ID</th>
				<th style="text-align: center;">Order Id</th>
				<th style="text-align:center">Product</th>
				<th style="text-align:center">Quantity</th>					
				
				<th style="text-align:center">Payment Status</th>
				<th style="text-align:center">Delivery Address</th>
				<th style="text-align:center">Action</th>
				<th style="text-align: center">cash collection</th>
				

				
				
			</thead>
			<tbody>
				@foreach($view as $rec)
					<tr id="row-{{$rec->id}}">

           @if($rec->project_id != null)
						<td style="text-align:center"><a href="{{URL::to('/')}}/showProjectDetails?id={{$rec->project_id}}">Projectid : {{$rec->project_id}}</a>
           @else
           <td style="text-align:center"><a href="{{URL::to('/')}}/viewmanu?id={{$rec->manu_id}}">Manufactuerer :{{$rec->manu_id}}</a>
          @endif


            </td>
						<td style="text-align:center">{{ $rec->id }}</td>
						<td>
							{{ $rec->main_category }}<br>
							({{ $rec->sub_category }})
						</td>
						<td style="text-align:center">{{$rec->quantity}} {{$rec->measurement_unit}}</td>
            <td style="text-align: center;">

             <?php $st = App\PaymentDetails::where('order_id',$rec->id)->sum('totalamount');

                    $st2 = App\PaymentHistory::where('order_id',$rec->id)->sum('totalamount'); 



                                      $amount = $st + $st2;

                                      $b = App\Requirement::where('id',$rec->req_id)->first();

                                      $bal = ((($b->total_quantity) * ($b->price)) - $amount) ;



                    ?>   

                    Amount Done : {{$amount}}<br>

             Amount Due :{{$bal}}      

           </td>

          <td style="text-align:center">

                        {{$b->ship}}



           </td>
					 
						<td style="text-align:center">
							@if( $rec->leaccept == NULL)
							 <div class="btn-group" role="group" aria-label="Basic example">

							  <a href="{{URL::to('/')}}/leaccept?id={{$rec->id}}&&userid={{Auth::user()->name}}" class="btn  btn-sm btn-warning">Accept</a>
							  <a href="{{URL::to('/')}}/lereject?id={{$rec->id}}&&userid={{Auth::user()->name}}" class="btn  btn-sm btn-danger ">Recject</a>
							 
							</div>
							@elseif( $rec->leaccept == 1)
                                    Accepted
                                    @else
                                    Rejected
							@endif

												
					  </td>
					<td>
 <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal{{$rec->id}}">Delivery Details</button>

  <!-- Modal -->
  <div class="modal fade" id="myModal{{$rec->id}}" role="dialog" >
   <form class="form-control" action="{{URL::to('/')}}/adddeliverydetails" enctype="multipart/form-data" method="post">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Delivery Details</h4>
        </div><br>
            <!-- <center> <a id="getBtn2{{$rec->id}}"  class="btn btn-success btn-sm" onclick="getLocations('{{$rec->id}}')">Get Location</a></center><br> -->
        <div class="modal-body">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $rec->id }}">
            <input type="hidden" name="userid" value="{{Auth::user()->id}}">
            <table class="table">
            <tr>
                  <td>Truck images</td>
                  <td>:</td>
                  <td>
            		   <input type="file" name="truckimage[]" class="form-control" multiple accept="image/*">
                  </td>
            </tr>
                
            <tr>
              <td>Truck video</td>
              <td>:</td>
              <td>
            		<input type="file" name="truckvideo" class="form-control"></td>
          </tr>
          <tr>
            	<td>
            		Cash Collection Type</td>
                <td>:</td>
                <td>
            		<label class="checkbox-inline">
                                              <input style="width: 33px;" id="myCheck{{$rec->id}}"  onclick="myFunction('{{$rec->id}}')" type="checkbox"  name="status[]" value="CASH"><span>&nbsp;&nbsp;&nbsp;</span>CASH
                                              <div id="text{{$rec->id}}" style="display:none">
                                              	<label>Cash Amount
                                              		
                                              <input type="text" name="cashamount"   placeholder="enter cash amount" class="form-control"> 	
                                              	</label>
                                              <label>
                                              	Cash Image
                                              <input type="file" name="cashimage"  class="form-control" >
                                              </label>
                                              </div>
                                            </label><br>
                                          

                                          
                                             <label class="checkbox-inline">
                                              <input  type="checkbox" id="myCheck1{{$rec->id}}"  onclick="myFunction1('{{$rec->id}}')"  name="status[]" value="RTGS">RTGS
                                              <div id="text1{{$rec->id}}" style="display:none">
                                              	<label>RTGS Amount
                                              		
                                              <input type="text" name="rtgsamount"   placeholder="enter cash amount" class="form-control"> 	
                                              	</label>
                                              <label>
                                              	RTGS Image
                                              <input type="file" name="rtgsimage"  class="form-control" >
                                              </label>
                                              </div>
                                              
                                            </label><br>
                                          
                                             <label class="checkbox-inline">
                                              <input  type="checkbox"  id="myCheck2{{$rec->id}}"  onclick="myFunction2('{{$rec->id}}')" name="status[]" value="CHEQUE">CHEQUE
                                              <div id="text2{{$rec->id}}" style="display:none">
                                              	<label>CHEQUE Amount
                                              		
                                              <input type="text" name="chequeamount"   placeholder="enter cash amount" class="form-control"> 	
                                              	</label>
                                              <label>
                                              	CHEQUE Image
                                              <input type="file" name="chequeimage"  class="form-control" >
                                              </label>
                                              </div>
                                             
                                            </label>
            	 </td>
              </tr>
              <tr>
            	<td>Total</td>
               <td>:</td>
            		<td><input type="text" name="totalamount" class="form-control"></td>
                
            	
                            <!--    
                               <tr class="">
                                   <td>Location</td>
                                   <td>:</td>
                                   <td id="x">
                                    <div class="col-sm-6">
                                      <label>Longitude:</label>
                                        <input placeholder="Longitude" class="form-control input-sm"  readonly type="text" name="longitude" value="{{ old('longitude') }}" id="longitude1">
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Latitude:</label>
                                        <input placeholder="Latitude" class="form-control input-sm"  readonly type="text" name="latitude" value="{{ old('latitude') }}" id="latitude1">
                                    </div>
                                   </td>
                               </tr>
                               <tr class="{{ $errors->has('address') ? ' has-error' : '' }}">
                                   <td>Full Address</td>
                                   <td>:</td>
                                   <td><input readonly id="address1"  type="text" placeholder="Full Address" class="form-control input-sm" name="address" value="{{ old('address') }}"></td>
                               </tr> -->
                                <tr class="">
                                   <td>Other Expense</td>
                                   <td>:</td>
                                   <td><input  id=""  type="text" placeholder="otherexpense" class="form-control input-sm" name="other" ></td>
                               </tr>
                                </tr>
                                   <tr class="">
                                   <td>Truck Number</td>
                                   <td>:</td>
                                   <td><input  id=""  type="text" placeholder="truck Number" class="form-control input-sm" name="trucknumber" ></td>
                               </tr>
                                <tr class="">
                                   <td>Supplier Amount</td>
                                   <td>:</td>
                                   <td><input  id=""  type="text" placeholder="supplier" class="form-control input-sm" name="spamount" ></td>
                               </tr>
                               <tr class="">
                                   <td>Supplier Ivoicefile</td>
                                   <td>:</td>
                                   <td><input  id=""  type="file" placeholder="supplier" class="form-control input-sm" name="spfile[]" multiple ></td>

                               </tr>
                               <tr class="">
                                   <td>Remark</td>
                                   <td>:</td>
                                   <td><textarea name="remark" class="form-control">
                                     
                                   </textarea></td>
                               </tr>
                               
                             </table>
            	<button type="submit" class="form-control btn btn-sm btn-warning">Submit</button><br><br>

           
             <?php $data = App\DeliveryDetails::where('order_id',$rec->id)->get()->first(); ?>
                @if(count($data) != 0)
      
                       
   <table   border="1" class="table table-responsive table-striped" style="width:50%">
   	<tr>
    <th>Truck Video</th>
    <td>
    	<video controls width="250">

    <source src="{{ $url}}/delivery_truckvideo/{{$data->truckvideo}}"
            type="video/webm">

    <source src="{{ $url}}/delivery_truckvideo/{{$data->truckvideo}}"
            type="video/mp4">

    Sorry, your browser doesn't support embedded videos.
</video>
 

    	</td>
  </tr>
   <tr>
    <th>Truck  Image</th>
    <td><?php
                                                     $images = explode(",", $data->truckimage );
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($images); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{ $url}}/delivery_truck/{{ $images[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor</td>
  </tr>
   <tr>
    <th>Total Amount</th>
    <td>{{$data->totalamount}}</td>
  </tr>
  <tr>
    <th>Payment Method</th>
    <td>
	   <?php $s = explode(",", $data->payment_method); ?>
	   @foreach($s as $pay)
	    {{$pay}} <br>
      @endforeach
    </td>
  </tr>
  
  <tr>
    <th>Other Expense Amount</th>
    <td>{{ $data->other != null  ? $data->other :'-' }}</td>
  </tr>
  
   <tr>
    <th>RTGS Amount</th>
    <td>{{ $data->rtgsamount != null  ? $data->rtgsamount :'-' }}</td>
  </tr>
  <tr>
    <th>RTGS Image</th>
    <td>
       @if($data->rtgsimage != "N/A")
    	<img height="150" width="150" id="project_img" src="{{ $url}}/delivery_rtgsimages/{{$data->rtgsimage}}" class="img img-thumbnail">
    @endif
</td>
  </tr>
   <tr>
    <th>Cash Amount</th>
    <td>{{ $data->chequeamount != null  ? $data->chequeamount :'-' }}</td>
  </tr>
  <tr>
    <th>Cash Image</th>
       @if($data->chequeimage != "N/A")
    <td><img height="150" width="150" id="project_img" src="{{ $url}}/delivery_chequeimages/{{$data->chequeimage}}" class="img img-thumbnail"></td>
    @endif
  </tr>
  <tr>
    <th>Location</th>
    <td>{{$data->address}}
</td>
</tr> 
<tr>
    <th>Supplier Amount</th>
    <td>{{$data->spamount}}
</td>
</tr> 
<tr>
 <th>Supplier invoice</th>
 <td> <?php
                                               $images = explode(",", $data->spfile);
                                               ?>
                                             
                                             <div class="row">

                                                 @for($i = 0; $i < count($images); $i++)
                                                     <div class="col-md-3">
                                                          <img height="350" width="350" id="project_img" src="{{$url}}/delivery_spinvoice/{{ $images[$i] }}" class="img img-thumbnail">
                                                     </div>
                                                 @endfor
                                              </div>
                                            
                                            <br>
    </td> 
    </tr>                                       
</table>
        @endif

           


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </form>
  </div>






					</td>
						
						
						
					</tr>
				@endforeach
			</tbody>
		</table>
		<br>
     <center>{{$view->links()}}</center>
		
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
	function rtgs(){
		document.getElementById('rtgs').className = "form-control input-sm";
		document.getElementById('cash').className = "hidden";
	}
	function cash(){
		document.getElementById('cash').className = "form-control input-sm";
		document.getElementById('rtgs').className = "hidden";
	}
	$(document).ready(function(){
		$('[data-toggle="popover"]').popover({html:true});   
	});
</script>
<script>
function myFunction(arg) {
  var checkBox = document.getElementById("myCheck"+arg);
  var text = document.getElementById("text"+arg);
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
</script>
<script>
function myFunction1(arg) {

  var checkBox = document.getElementById("myCheck1"+arg);
  var text = document.getElementById("text1"+arg);
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
</script>
<script>
function myFunction2(arg) {
  var checkBox = document.getElementById("myCheck2"+arg);
  var text = document.getElementById("text2"+arg);
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
</script>
<script type="text/javascript">

	function pay(arg)
	{
		var ans = confirm('Are You Sure ? Note: Changes Made Once CANNOT Be Undone');
		if(ans){
			$.ajax({
				type: 'GET',
				url: "{{URL::to('/')}}/updateampay",
				data: {id: arg},
				async: false,
				success: function(response){
					console.log(response);
				}
			});
		}
		return false;
	}

	function updateDispatch(arg)
	{
		var ans = confirm('Are You Sure ? Note: Changes Made Once CANNOT Be Undone');
		if(ans){
			$.ajax({
				type: 'GET',
				url: "{{URL::to('/')}}/updateamdispatch",
				data: {id: arg},
				async: false,
				success: function(response){
					console.log(response);
						$("#orders").load(location.href + " #orders>*", "");
				}
			});
		}
		return false;	
	}

	function deliverOrder(arg)
	{
		var ans = confirm('Are You Sure To Confirm This Order ?');
		if(ans)
		{
			$.ajax({
				type:'GET',
				url: "{{URL::to('/')}}/deliverOrder",
				data: {id : arg},
				async: false,
				success: function(response)
				{
					console.log(response);
					$("#orders").load(location.href + " #orders>*", "");
				}
			});
		}    
	}

	function cancelOrder(arg)
	{
		var ans = confirm('Are You Sure To Cancel This Order ?');
		if(ans)
		{
			$.ajax({
				type:'GET',
				url: "{{URL::to('/')}}/cancelOrder",
				data: {id : arg},
				async: false,
				success: function(response)
				{
					console.log(response);
					$("#orders").load(location.href + " #orders>*", "");
				}
			});
		}
	}
	function changeValue(val, id){
	//use comparison operator   
		if(val=="Cheque" || val=="RTGS" || val=="Cash" )
			document.getElementById('show'+id).className = "";
		else{
			document.getElementById('show'+id).className="hidden";
		}
	}

</script>
<script type="text/javascript" charset="utf-8">


  function getLocations(arg){
   
    
      document.getElementById("getBtn2"+arg).className = "hidden";
      console.log("Entering getLocation()");
      if(navigator.geolocation){
          var s = arg;
        navigator.geolocation.getCurrentPosition(

        displayCurrentLocation1,
        displayError1,
        { 
          maximumAge: 3000, 
          timeout: 5000, 
          enableHighAccuracy: true 
        });
    }else{
      alert("Oops.. No Geo-Location Support !");
     
    } 
  }
    
    function displayCurrentLocation1(position){
            
      var latitude  = position.coords.latitude;
      var longitude = position.coords.longitude;
       

      document.getElementById("longitude1").value = longitude;
      document.getElementById("latitude1").value  = latitude;
      getAddressFromLatLang1(latitude,longitude);
          
    }
   
  function  displayError1(error){
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
  function getAddressFromLatLang1(lat,lng){


    var geocoder = new google.maps.Geocoder();
    var latLng = new google.maps.LatLng(lat, lng);
    geocoder.geocode( { 'latLng': latLng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[0]) {
       
        document.getElementById("address1").value = results[0].formatted_address;
        alert("tets: " + results[0].formatted_address);
      }
    }else{
        alert("Geocode was not successful for the following reason: " + status);
     }
    });
  }
</script>

@endsection
