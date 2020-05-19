@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-color:#337ab7">
            <div class="panel-heading text-center" style="background-color:#337ab7">
                <b style="color:white">Self Report of Today
               </b>
            </div>
         

            <div class="panel-body">
             <form action="{{ URL::to('/') }}/myreport" method="GET" enctype="multipart/form-data"> 
                    {{ csrf_field() }}
                <div class="col-md-2">
                 <h4><b>Select From Date</b></h4>
                    <input class="form-control" value="{{ isset($_GET['fromdate']) ? $_GET['fromdate'] : '' }}" type="date" name="fromdate"  style="width:100%;" required>
                </div>
                   

               <div class="col-md-2">
                   <h4><b>Select To Date</b></h4>
                   <input class="form-control" value="{{ isset($_GET['todate']) ? $_GET['todate'] : '' }}" type="date" name="todate" style="width:100%;" required>
                     
                   </textarea>
                 </div> 
  
                  <div class="col-md-2">
                   
                    <button type="submit"  class="form-control btn btn-primary" value="submi" style="margin-top:40px;">Fetch Report</button> 
                 </div> 

            </div>
            </form>
            <table  class="table" border="1">
                <thead>
                    <tr> 
                        <th>Employee Name</th>                        
                        <th>Added Projects</th>
                        <th>Added manufactures</th>
                        <th>Updated Projects</th>
                        <th>Updated manufactures</th>
                        <th>Confirmed Orders</th>
                        <th>Call Attend </th>
                        <th>Busy and not Reachable</th>
                        <th>Switched Off</th>
                        <th>Call Not Answered </th>
                        <th>Not Instrested</th>
                        <th>No of logistic</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $dump)
                    <tr>
                     <td>{{$dump['name']}}</td>
                     <td>{{$dump['addproject']}}</td>
                     <td>{{$dump['addmanu']}}</td>
                     <td>{{$dump['updateprojects']}}</td>
                     <td>{{$dump['updatedmanu']}}</td>
                     <td>{{$dump['order']}}</td>
                     <td>{{$dump['callattend']}}</td>
                     <td>{{$dump['callbusy']}}</td>
                     <td>{{$dump['switchoff']}}</td>
                     <td>{{$dump['notanswer']}}</td>
                     <td>{{$dump['notinterest']}}</td>
                     <td>{{$dump['logistic']}}</td>
                     
                    </tr>

                   @endforeach
                </tbody>
         
                   </table>

        </div>
    </div>
</div>
</div>

@endsection