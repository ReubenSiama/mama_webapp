@extends('layouts.app')

@section('content')
<?php $count = 1; ?>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <table class="table table-hover" border=1>
                <center><label for="Points">{{ $user->name }}'s Points For {{ date('d-M-Y',strtotime($date)) }}</label></center>
                <thead>
                    <th>Reason For Earning Point</th>
                    <th>Points Earned</th>
                </thead>
                <tbody>
                    @foreach($points_indetail as $points)
                    <tr>
                        <td>
                            {!! $points->reason !!}
                            @if($points->confirmation == 0)
                            <a href="{{ URL::to('/') }}/approvePoint?id={{ $points->id }}" class="btn btn-primary btn-xs pull-right">Approve</a>
                            @endif
                        </td>
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
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Report of {{ $user->name }} for {{ date('d-M-Y',strtotime($date)) }}
                </div>
             
                    <table class="table">
                        <thead>
                            <th>Sl.No.</th>
                            <th>Report</th>
                            <th>Start</th>
                            <th>End</th>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $report->report }}</td>
                                <td>{{ $report->start }}</td>
                                <td>{{ $report->end }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Grade Limit<span class="pull-right"><button class="btn btn-sm btn-success" data-toggle="modal" data-target="#myModal12">Add grade</button></span>
                    </div>
                    <form action="{{ URL::to('/') }}/graderange" method="post">
                        {{ csrf_field() }}
                        <!-- Modal -->
                        <div id="myModal12" class="modal fade" role="dialog">
                          <div class="modal-dialog">
                            center
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Grade Range</h4>
                              </div>
                              <div class="modal-body">
                                <table class="table table-hover">
                                <input type="hidden" name="userId" value="{{ $user->id }}">
                                <input type="hidden" name="date" value="{{ date('Y-m-d H:i:s',strtotime($date)) }}">
                                    <tr>
                                        <td>Grade Type</td>
                                        <td>:</td>
                                        <td>
                                            <select name="type" id="" class="form-control">
                                                <option value="">--Select--</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                                <option value="E">E</option>
                                                <option value="F">F</option>
                                              
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>From</td>
                                        <td>:</td>
                                        <td>
                                            <input type="number" name="from"  class="form-control" placeholder="from"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>To</td>
                                        <td>:</td>
                                        <td><input type="number" name="to" class="form-control" placeholder="to"></td>
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
                      
                        <table class="table">
                            <thead>
                                <th>Sl.No.</th>
                                <th>Grade</th>
                                <th>From</th>
                                <th>to</th>
                            </thead>
                            <tbody>
                                @foreach($grades as $report)
                                <tr>
                                    <td>{{ $report->id  }}</td>
                                    <td>{{ $report->grade }}</td>
                                    <td>{{ $report->from_range }}</td>
                                    <td>{{ $report->to_range }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>
<h3 style="text-align:center">Today Grade is  {{$grade}}
</h3>
<form action="{{ URL::to('/') }}/aMaddPoints" method="post">
{{ csrf_field() }}
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Points To {{ $user->name }}</h4>
      </div>
      <div class="modal-body">
        <table class="table table-hover">
		<input type="hidden" name="userId" value="{{ $user->id }}">
		<input type="hidden" name="date" value="{{ date('Y-m-d H:i:s',strtotime($date)) }}">
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
@endsection