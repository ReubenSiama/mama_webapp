@extends('layouts.app')
@section('content')  

  <span class="pull-right"> @include('flash-message')</span>
	<div class="row">
<div class="col-md-8 col-md-offset-2 ">
       
        <div class="panel panel-danger" style="border-color:rgb(244, 129, 31) ">
            <div class="panel-heading" style="background-color: rgb(244, 129, 31);color:white;font-weight:bold;font-size:15px;">
              <center>   Reject Invoice Details </center>
                
            </div>
            <div style="overflow-x: scroll;" class="panel-body">

                   
                    <div class="col-md-12" >
                  <form method="GET" action="{{ URL::to('/') }}/rejectinvoice">
                 <div class="col-md-4">
                  
                <label>Invoice(From Date)</label>
                <input value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from">
              </div>
              <div class="col-md-4">
                <label>Invoice(To Date)</label>
                <input  value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="date" class="form-control" name="to">
              </div>
                  <div class="col-md-4">
                <label>submit</label>
                <input type="submit" value="Fetch" class="form-control btn btn-primary">
              </div>
                </form>
                  </div> 
                <br><br><br>
                
                <table id="manufacturer" class="table table-responsive" border=1><br>
                    <thead>
                        <th>Slno</th>
                        <th>OrderId</th>
                          <th>Invoice No</th>
                    </thead>
                    <?php $i=1 ?>
                    <tbody>
                    	@foreach($data as $project)
                         <tr>
                          <td>{{$i++}}</td>
                          <td>{{$project->order_id}}</td>
                          <td>{{$project->invoiceno}}</td>
                        </tr>

                      @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</div>

@endsection