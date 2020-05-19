
@extends('layouts.app')
<?php $url = Helpers::geturl(); ?>
@section('content')
<div class="col-md-4 col-md-offset-1">
	<table class="table table-hover" border=1>
		<center><label for="Points">{{ $username->name }}'s Points For {{ isset($_GET['date']) ? date('d-m-Y',strtotime($_GET['date'])) : 'Today' }}</label></center>
		<thead>
			<th>Reason For Earning Point</th>
			<th>Points Earned</th>
		</thead>
		<tbody>
			@foreach($points_indetail as $points)
			<tr>
				<td>{!! $points->reason !!}</td>
				<td style="text-align: right">{{ $points->type == "Add" ? "+".$points->point : "-".$points->point }}</td>
			</tr>
			@endforeach
			<tr>
				<td colspan=2>
					<button type="button" class="btn btn-info btn-sm form-control" data-toggle="modal" data-target="#myModal">Add More Point</button>
				</td>
			</tr>
			<tr>
				<td style="text-align: right;"><b>Total</b></td>
				<td style="text-align: right">{{ $total }}</td>
			</tr>
		</tbody>
	</table>
</div>
<div class="col-md-6">
	<div class="panel panel-default">
		<div class="panel-heading">
			@if($loginTimes)
			Report of {{ $username->name }} ({{ date('d-m-Y',strtotime($loginTimes->logindate)) }})
		@else
			User may have failed to log in today
			@endif
			@if(session('Error'))
			<div class="alert-danger pull-right">

			
			{{ session('Error') }} </div>
			

			@endif
		</div>
		<div class="panel-body">
			<div class="row">
				<form method="GET" action="{{ URL::to('/') }}/viewReport">
				    <input type="hidden" name="UserId" value="{{ $username->id }}">
					<div class="col-md-3">
						Choose Date:
					</div>
					<div class="col-md-4">
						<input required type="date" name="date" class="form-control input-sm">
					</div>
					<div>
						<button type="submit">Submit</button>
					</div>
				</form>
			</div>
			<br>
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<form method="POST" action="{{ URL::to('/') }}/markLeave">
						{{ csrf_field() }}
						<input required type="date" name="date" class="form-control input-sm">
						<input type="hidden" name="id" value="{{ $username->id }}">
						<div class="radio">
							<label><input name="leave" type="radio" value="Leave">Leave</label>
						</div><div class="radio">
							<label><input name="leave" type="radio" value="Half Day">Half Day</label>
						</div>
						<input type="submit" value="Save" class="form-control btn btn-success">
					</form>
				</div>
			</div>
			<br>
			@if($loginTimes)
			<label>Morning</label>
			<table class="table">
				<tr>
					<td>Login Time in Web App</td>
					<td>:</td>
					<td>{{ $loginTimes->loginTime }}</td>
				</tr>
				<!-- <tr>
					<td>Login Time in Tracking App</td>
					<td>:</td>
					<td>{{ $loginTimes->tracktime }}</td>
				</tr> -->
				<!-- <tr>
					<td>Login Time to ward</td>
					<td>:</td>
					<td>
						@if($loginTimes->login_time_in_ward != NULL)
						{{ $loginTimes->login_time_in_ward }}
						@else
						<form method="post" action="{{ URL::to('/') }}/{{ $loginTimes->id }}/addComment">
							{{ csrf_field() }}
						  <div class="input-group">
						    <input type="time" class="form-control" name="loginTimeInWard">
						    <div class="input-group-btn">
						      <button class="btn btn-default" type="submit">Submit</button>
						    </div>
						  </div>
						</form>
						@endif
					</td>
				</tr> -->
				<!-- <tr>
					<td>Logout Time</td>
					<td>:</td>
					<td>{{ $loginTimes->logoutTime }}</td>
				</tr> -->
				<tr>
					<td>Allocated Ward</td>
					<td>:</td>
					<td>{{ $loginTimes->allocatedWard }}</td>
				</tr>
				<!-- <tr>
					<td>First Listing Time</td>
					<td>:</td>
					<td>{{ $loginTimes->firstListingTime }}</td>
				</tr>
				<tr>
					<td>First Update Time</td>
					<td>:</td>
					<td>{{ $loginTimes->firstUpdateTime }}</td>
				</tr>
				<tr>
					<td>No. of projects listed <br> in the morning</td>
					<td>:</td>
					<td>{{ $loginTimes->noOfProjectsListedInMorning }}</td>
				</tr>
				<tr>
					<td>No. of projects updated <br> in the morning</td>
					<td>:</td>
					<td>{{ $loginTimes->noOfProjectsUpdatedInMorning }}</td>
				</tr>
				<tr>
					<td>Morning Remarks</td>
					<td>:</td>
					<td>
					@if($loginTimes->morningRemarks == NULL)
					<form method="post" action="{{ URL::to('/') }}/{{ $loginTimes->id }}/morningRemark">
						{{ csrf_field() }}
						<textarea style="resize: none;" required class="form-control" name="mRemark"></textarea><br>
						<button class="form-control" type="submit">Save</button>
					</form>
					@else
					    <form method="post" action="{{ URL::to('/') }}/{{ $loginTimes->id }}/editMorningRemarks">
					        {{ csrf_field() }}
					    <textarea style="resize: none;" name="remark" id="mEdit" class="hidden">{!! nl2br($loginTimes->morningRemarks) !!}</textarea>
						<p id="mCurrent">{!! nl2br($loginTimes->morningRemarks) !!}</p>
					    <input id="mSaveBtn" type="submit" value="Save" class="hidden">
					    </form>
						<!--<button id="mEditBtn" class="btn btn-primary" onclick="editMorning()">Edit</button>-->
					@endif
					<!-- </td>

				</tr> --> 
				<tr>
					<td>Morning Meter Image</td>
					<td>:</td>
					<td>
					    @if($loginTimes->morningMeter != NULL || $loginTimes->morningMeter == NULL )
						<img src="{{ $url}}/meters/{{ $loginTimes->morningMeter }}" height="100" width="200" class="img img-thumbnail">
						 <form method="POST" action="{{ URL::to('/') }}/deleteReportImage" id="from1" enctype="multipart/form-data">
					        {{ csrf_field() }}

						 	 <input type="file" name="mrngmeter" class="form-control">
						 	<input type="hidden" name="id" value="{{ $loginTimes->id }}"><br>
						  <a  class="btn btn-warning btn-sm" onclick="document.getElementById('from1').submit()">Update</a>
						 </form>
						@endif
					</td>
				</tr>
				<tr>
					<td>Morning Meter Reading</td>
					<td>:</td>
					<td>
					    @if($loginTimes->morningMeter != NULL || $loginTimes->morningMeter == NULL)
					    {{ $loginTimes->gtracing }}
						@endif
					</td>
				</tr>
				<tr>
					<td>Morning Data Image</td>
					<td>:</td>
					<td>
					    @if($loginTimes->morningData != NULL || $loginTimes->morningData == NULL)
					    <img src="{{ $url}}/data/{{ $loginTimes->morningData }}" height="100" width="200" class="img img-thumbnail">
					    <form method="POST" action="{{ URL::to('/') }}/deleteReportImage2" id="from2" enctype="multipart/form-data">
					        {{ csrf_field() }}

						 	 <input type="file" name="mrngdata" class="form-control">
						 	<input type="hidden" name="id" value="{{ $loginTimes->id }}"><br>
						  <a  class="btn btn-warning btn-sm" onclick="document.getElementById('from2').submit()">Update</a>
						 </form>
					   
					    @endif
					</td>
				</tr>
				<tr>
					<td>Morning Data Reading</td>
					<td>:</td>
					<td>
					    @if($loginTimes->morningData != NULL || $loginTimes->morningData == NULL)
					    {{ $loginTimes->afternoonData }}
					    @endif
					</td>
				</tr>
				<!--<tr>-->
				<!--	<td>Morning Google tracing image</td>-->
				<!--	<td>:</td>-->
				<!--	<td>-->
				<!--		@if($loginTimes->gtracing == NULL)-->
				<!--		<form method="POST" action="{{ URL::to('/') }}/{{ $loginTimes->id }}/addTracing" enctype="multipart/form-data">-->
				<!--			<input type="file" class="form-control" accept="image/*" onchange="this.form.submit()" name="gTracing">-->
				<!--			{{ csrf_field() }}-->
				<!--		</form>-->
				<!--		@else-->
				<!--		<img src="{{ URL::to('/') }}/public/uploads/{{ $loginTimes->gtracing }}" height="100" width="200" class="img img-thumbnail">-->
				<!--		@endif-->
				<!--	</td>-->
				<!--</tr>-->
				<!-- <tr>
					<td>Morning KM from google Home to Ward</td>
					<td>:</td>
					<td>
						@if($loginTimes->kmfromhtw == NULL)
						<form method="POST" action="{{ URL::to('/') }}/{{ $loginTimes->id }}/addComment">
							{{ csrf_field() }}
						  <div class="input-group">
						    <input type="text" class="form-control" name="googleKm" placeholder="KM from google">
						    <div class="input-group-btn">
						      <button class="btn btn-default" type="submit">Submit</button>
						    </div>
						  </div>
						</form>
						@else
						{{ $loginTimes->kmfromhtw }}
						@endif
					</td>
				</tr> -->
			</table>
			<label>Evening</label>
			<table class="table">
				<tr>
					<td>Evening Meter Image</td>
					<td>:</td>
					<td>
					    @if($loginTimes->eveningMeter != NULL || $loginTimes->eveningMeter == NULL)
					    <img src="{{ $url}}/meters/{{ $loginTimes->eveningMeter }}" height="100" width="200" class="img img-thumbnail">
					     <form method="POST" action="{{ URL::to('/') }}/deleteReportImage5" id="from3" enctype="multipart/form-data">
					        {{ csrf_field() }}

						 	 <input type="file" name="mrngdata" class="form-control">
						 	<input type="hidden" name="id" value="{{ $loginTimes->id }}"><br>
						  <a  class="btn btn-warning btn-sm" onclick="document.getElementById('from3').submit()">Update</a>
						 </form>
					   
					
					@endif
					</td>
				</tr>
				<tr>
					<td>Evening Meter Reading</td>
					<td>:</td>
					<td>
					    @if($loginTimes->eveningMeter != NULL || $loginTimes->eveningMeter == NULL)
					    {{ $loginTimes->afternoonMeter }}
					    @endif
					</td>
				</tr>
				<tr>
					<td>Evening Data Image</td>
					<td>:</td>
					<td>
					    @if($loginTimes->eveningData != NULL || $loginTimes->eveningData == NULL)
					    <img src="{{ $url}}/data/{{ $loginTimes->eveningData }}" height="100" width="200" class="img img-thumbnail">
					 <form method="POST" action="{{ URL::to('/') }}/deleteReportImage6" id="from9" enctype="multipart/form-data">
					        {{ csrf_field() }}

						 	 <input type="file" name="mrngdata" class="form-control">
						 	<input type="hidden" name="id" value="{{ $loginTimes->id }}"><br>
						  <a  class="btn btn-warning btn-sm" onclick="document.getElementById('from9').submit()">Update</a>
						 </form>
					   
					    @endif
					</td>
				</tr>
				<tr>
					<td>Evening Data Reading</td>
					<td>:</td>
					<td>
					    @if($loginTimes->eveningData != NULL || $loginTimes->eveningData == NULL)
					    {{ $loginTimes->afternoonRemarks }}
					    @endif
					</td>
				</tr>
				<!-- <tr>
					<td>Evening Ward Tracing Image (Hello Tracks)</td>
					<td>:</td>
					<td>
						@if($loginTimes->evening_ward_tracing_image == NULL)
						<form method="POST" action="{{ URL::to('/') }}/{{ $loginTimes->id }}/addTracing" enctype="multipart/form-data">
							<input type="file" class="form-control" accept="image/*" onchange="this.form.submit()" name="ewTracingI">
							{{ csrf_field() }}
						</form>
						@else
						<img src="{{$url}}/uploads/{{ $loginTimes->evening_ward_tracing_image }}" height="100" width="200" class="img img-thumbnail">
						@endif
					</td>
				</tr>
				<tr>
					<td>Evening Km From tracking software</td>
					<td>:</td>
					<td>
						@if($loginTimes->evening_km_from_tracking == NULL)
						<form method="POST" action="{{ URL::to('/') }}/{{ $loginTimes->id }}/addComment">
							{{ csrf_field() }}
						  <div class="input-group">
						    <input type="text" class="form-control" name="ekmfromts" placeholder="KM from google">
						    <div class="input-group-btn">
						      <button class="btn btn-default" type="submit">Submit</button>
						    </div>
						  </div>
						</form>
						@else
						{{ $loginTimes->evening_km_from_tracking }}
						@endif
					</td>
				</tr>
				<tr>
					<td>Evening Tracking image from ward to home</td>
					<td>:</td>
					<td>@if($loginTimes->tracing_image_w_to_h == NULL)
						<form method="POST" action="{{ URL::to('/') }}/{{ $loginTimes->id }}/addTracing" enctype="multipart/form-data">
							<input type="file" class="form-control" accept="image/*" onchange="this.form.submit()" name="TracingIWtH">
							{{ csrf_field() }}
						</form>
						@else
						<img src="{{ $url}}/uploads/{{ $loginTimes->tracing_image_w_to_h }}" height="100" width="200" class="img img-thumbnail">
						@endif</td>
				</tr>
				<tr>
					<td>Evening Km from ward to home</td>
					<td>:</td>
					<td>
						@if($loginTimes->km_from_w_to_h == NULL)
						<form method="POST" action="{{ URL::to('/') }}/{{ $loginTimes->id }}/addComment">
							{{ csrf_field() }}
						  <div class="input-group">
						    <input type="text" class="form-control" name="ekmwth" placeholder="KM from google">
						    <div class="input-group-btn">
						      <button class="btn btn-default" type="submit">Submit</button>
						    </div>
						  </div>
						</form>
						@else
						{{ $loginTimes->km_from_w_to_h }}
						@endif	
					</td>
				</tr>
				<tr>
					<td>Last Listing Time</td>
					<td>:</td>
					<td>{{ $loginTimes->lastListingTime }}</td>
				</tr>
				<tr>
					<td>Last Update Time</td>
					<td>:</td>
					<td>{{ $loginTimes->lastUpdateTime }}</td>
				</tr>
				<tr>
					<td>Total Projects Listed today</td>
					<td>:</td>
					<td>{{ $loginTimes->TotalProjectsListed }}</td>
				</tr>
				<tr>
					<td>Total Projects Updated today</td>
					<td>:</td>
					<td>{{ $loginTimes->totalProjectsUpdated }}</td>
				</tr> -->
					<tr>
						<td>Logout Time</td>
						<td>:</td>
						<td>
							@if($loginTimes->logoutTime != 'N/A')
								{{ $loginTimes->logoutTime }}
							@else
								<form method="POST" action="{{ URL::to('/') }}/updateLogoutTime">
									{{ csrf_field() }}								
									<input type="hidden" value="{{ $loginTimes->id }}" name="id">
									<div class="input-group">
									<input type="time" name="logoutTime" class="form-control">
									<div class="input-group-btn">
									<input type="submit" value="save" class="btn btn-primary">
									</div></div>
								</form>
							@endif
						</td>
					</tr>
				</table>
				@if($loginTimes->eveningRemarks == NULL)
    				<form method="post" action="{{ URL::to('/') }}/{{ $loginTimes->id }}/eveningRemark">
				@else
					<form method="post" action="{{ URL::to('/') }}/{{ $loginTimes->id }}/editEveningRemarks">
				@endif
				<table class="table table-hover">
    				<tr>
    					<td>Total Kilometers By LE</td>
    					<td>:</td>
    					<td>
    					    @if( $loginTimes->afternoonMeter - $loginTimes->gtracing <= 0)
    					        {{ $total = ($loginTimes->afternoonMeter - $loginTimes->gtracing) * -1 }} KM<br>
    					       
    					    @else
    					        {{ $total = $loginTimes->afternoonMeter - $loginTimes->gtracing }}KM
    					        
    					    @endif
    					</td>
    				</tr>
    				<tr>
    					<td>Total Amount</td>
    					<td>:</td>
    					<td>
    					    @if( $loginTimes->afternoonMeter - $loginTimes->gtracing <= 0)
    					       
    					        {{$total * 2}}
    					    @else
    					        
    					         {{$total * 2}}
    					    @endif
    					</td>
    				</tr>
    				@if(Auth::user()->group_id ==1 ||   Auth::user()->group_id ==2) 				
    				<tr>
    					<td>Total Kilometers From TL</td>
    					<td>:</td>
    					<td>
    					    @if($loginTimes->total_kilometers != NULL)
    					    <label>Kilometers&nbsp;&nbsp;&nbsp;    					    	
    					    <input id="ktotal" type="text" name="totalKm" value="{{ $loginTimes->total_kilometers }}" onkeyup="getvalue()" class="">
    					    </label>
    					    <label>Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
    					    	
    					    <input id="atotal" type="text" name="amount" value="{{ $loginTimes->amount }}" class="">
    					    </label>
    					     <label>Other Expense Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
					        <input id="" type="text" name="other" value="{{ $loginTimes->otherexpense }}" class="">
					    </label>
    					    @else
    					    <label>Kilometers&nbsp;&nbsp;&nbsp;   
					        <input id="ktotal" type="text" name="totalKm" value="{{ $loginTimes->total_kilometers }}" onkeyup="getvalue()" class="form-control">
					    </label>
					    <label>Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
					        <input id="atotal" type="text" name="amount" value="{{ $loginTimes->amount }}" class="">
					    </label>
					     <label>Other Expense Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
					        <input id="" type="text" name="other" value="{{ $loginTimes->otherexpense }}" class="">
					    </label>
					        @endif
    					</td>
    				</tr>
    				@endif
    				<tr>
    					<td>Evening Remarks</td>
    					<td>:</td>
    					<td >
					        {{ csrf_field() }}
					        @if($loginTimes->eveningRemarks != NULL)
					        <p id="eCurrent">{!! nl2br($loginTimes->eveningRemarks) !!}</p>
					         <textarea  style="resize: none;" name="eRemark" rows="3" id="eEdit" class="form-control">{!! nl2br($loginTimes->eveningRemarks) !!}</textarea>
					        @else
    					    <textarea style="resize: none;" name="eRemark" rows="3" id="eEdit" class="form-control">{!! nl2br($loginTimes->eveningRemarks) !!}</textarea>
    					    @endif
    					</td>
    				</tr>
    			</table>
    		    <!--<button type="button" id="eEditBtn" onclick="editEvening()" class="{{ $loginTimes->eveningRemarks != NULL ? 'form-control': 'hidden' }}">Edit</button><br>-->
				<input id="SaveBtn" type="submit" value="Save" class="btn btn-primary form-control">
		    </form>
		</div>
		@endif
	</div>
</div>

<form action="{{ URL::to('/') }}/addPoints" method="post">
{{ csrf_field() }}
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Points To {{ $username->name }}</h4>
      </div>
      <div class="modal-body">
        <table class="table table-hover">
		<input type="hidden" name="userId" value="{{ $username->id }}">
		<input type="hidden" name="date" value="{{ isset($_GET['date']) ? $_GET['date'].' '.date('H:i:s') : date('Y-m-d H:i:s') }}">
			<tr>
				<td>Type</td>
				<td>:</td>
				<td>
					<select name="type" id="" class="form-control">
						<option value="">--Select--</option>
						<option value="Add">Add</option>
						<option value="Subtract">Subtract</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>Reason</td>
				<td>:</td>
				<td>
					<textarea name="reason" rows="3" class="form-control" placeholder="Reason for adding points"></textarea>
				</td>
			</tr>
			<tr>
				<td>Points</td>
				<td>:</td>
				<td><input type="number" name="point" class="form-control" placeholder="Amount you want to add"></td>
			</tr>
		</table>
      </div>
      <div class="modal-footer">
		<button type="submit" class="btn btn-success pull-left">Add</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</form>

<script>
    function editMorning(){
        if(document.getElementById("mEdit").className == "form-control"){
            document.getElementById("mEdit").className = "hidden";
            document.getElementById("mCurrent").className = "";
            document.getElementById("mEditBtn").innerHTML = "Edit";
            document.getElementById("mSaveBtn").className = "hidden";
        }else{
            document.getElementById("mEdit").className = "form-control";
            document.getElementById("mCurrent").className = "hidden";
            document.getElementById("mEditBtn").innerHTML = "Cancel";
            document.getElementById("mSaveBtn").className = "form-control";
        }
    }
    function editEvening(){
        if(document.getElementById("eEdit").className == "form-control"){
            document.getElementById("eEdit").className = "hidden";
            document.getElementById("eCurrent").className = "";
            document.getElementById("eEditBtn").innerHTML = "Edit";
        }else{
            document.getElementById("eEdit").className = "form-control";
            document.getElementById("eCurrent").className = "hidden";
            document.getElementById("eEditBtn").innerHTML = "Cancel";
        }
    }
</script>
@if(session('success'))
<script>
    swal("success","{{ session('success') }}","success");
</script>
@endif
<script type="text/javascript">
	function getvalue(){

	var x =	document.getElementById('ktotal').value;
       var y = (x*2)
	 document.getElementById('atotal').value = y;
	}
</script>
@endsection