<?php
    
    $ext = "layouts.app";
?>
@extends($ext)
@section('content')
<div class="col-md-2" style="overflow-y:scroll; height:570px; max-height:570px">
        <div class="panel panel-primary" style="overflow-x:scroll">
            <div class="panel-heading text-center">
                <b style="color:white">Sales Report</b>
            </div>
            <?php $users = App\User::all(); ?>
            <div class="panel-body">
				@if(Auth::user()->department_id != 1)
            	<form method="GET" action="{{ URL::to('/') }}/updatereport">
				@else
				<form method="GET" action="{{ URL::to('/') }}/updatereport">
				@endif
                    <table class="table table-responsive">
	                    <tbody>
	                        <tr>
	                            <td>Select  Employees</td>
	                        </tr>
                            <tr>
                                <td>
                                    <select required name="user" class="form-control" id="selectle">
                                        <option disabled selected value="">(-- SELECT SE --)</option>
                                        <option   value="All">All</option>
                                       
                                            @foreach($users as $list)
                                            <option {{ isset($_GET['user']) ? $_GET['user'] == $list->employeeId ? 'selected' : '' : ''}}  value="{{$list->id}}">{{$list->name}}</option>
                                            @endforeach
                                       
	                                </select>
	                            </td>
	                        </tr>
	                        <tr>
	                            <td>Select From Date</td>
	                        </tr>
	                        <tr>
	                            <td>
	                                <input value="{{ isset($_GET['fromdate']) ? $_GET['fromdate'] : '' }}" type="date" placeholder= "From Date" class="form-control" id="fromdate" name="fromdate" />
	                            </td>
	                        </tr>
	                        <tr>
	                            <td>Select To Date</td>
	                        </tr>
	                        <tr>
	                            <td>
	                                <input value="{{ isset($_GET['todate']) ? $_GET['todate'] : '' }}" type="date"  placeholder= "To Date" class="form-control" id="todate" name="todate" />
	                            </td>
	                        </tr>
	                        <tr class="text-center">
	                            <td>
	                                <button class="btn bn-md btn-success" style="width:100%">Get Date Range Details</button>
	                            </td>
	                        </tr>
	                    </tbody>
	                </table>
            	</form>
            </div>
        </div>
       
    </div>

<div class="col-md-10">
    <div class="panel panel-primary" style="overflow-x:scroll">
        <div class="panel-heading" id="panelhead">
            <label>
            	
            		
            </label>
            <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color:black;"></i></button>
            <p style="font-weight:bold;font-size:15px;"><span>Call Attend : {{$callattend}}</span>&nbsp; &nbsp;&nbsp;<span>
            Busy and not Reachable : {{$Busy}}</span> &nbsp; &nbsp;&nbsp;<span>Switched Off : {{$switched}}</span>&nbsp; &nbsp;&nbsp; 
            <span>Call Not Answered : {{$notanswer}}</span> &nbsp; &nbsp;&nbsp;<span>Not Instrested : {{$notinterest}}</span></p>
        </div>
        <div class="panel-body" style="overflow-y:scroll; height:700px; max-height:700px">
            <table class='table table-responsive table-striped' style="color:black" border="1">
                <thead>
                    <tr>
                        <th style="text-align:center">Manufacturer /Project Id</th>

                        <th style="text-align:center">Call Attended</th>
                        <th style="text-align:center">Busy and not Reachable</th>
                        <th style="text-align:center">Switched Off</th>
                        <th style="text-align:center" >Call Not Answered</th>
                        <th style="text-align:center">Not Instrested</th>
                        <th style="text-align:center" >Remarks</th>
                        
                        
                       
                       
                    </tr>
                </thead>
                <tbody id="mainPanel">
                    @foreach($data as $df)
                	<tr>
                   <td>
                    @if($df->manu_id != null)

                   <a href="{{URL::to('/')}}/viewmanu?id={{$df->manu_id}}" > Manufacturer : {{$df->manu_id}}</a>
                    @elseif($df->project_id != null)
                   <a href="{{URL::to('/')}}/showThisProject?id={{$df->project_id}}">Project :  {{$df->project_id}}</a>
                   @else
                    <a href="{{URL::to('/')}}/editmat?id={{$df->Materialhub_id}}">Materialhub : {{$df->Materialhub_id}}</a>
                    @endif
                    
                  </td>
                   <td>
                       @if($df->quntion == "Call_attended") 
                          Called Attended
                          @else
                          -
                       @endif
                   </td>
                   <td>
                        @if($df->quntion == "Busy") 
                          Busy
                          @else
                          -
                       @endif
                   </td>
                   <td>
                        @if($df->quntion == "switched_off") 
                          Switched Off
                          @else
                          -
                       @endif
                   </td>
                   <td>
                        @if($df->quntion == "Call_Not_Answered") 
                          Call Not Answered
                          @else
                          -
                       @endif
                   </td>
                    <td>
                        @if($df->quntion == "Not_Instrested") 
                        Not Instrested
                          @else
                          -
                       @endif
                   </td>
                   <td>
                    {{$df->remarks}}
                     
                   </td>
                  
                    </tr>   
                    @endforeach 
                </tbody>
            </table>
            
        </div>
    </div>
</div>

@endsection
