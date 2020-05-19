<?php
    $user = Auth::user()->group_id;
    $ext ="layouts.app";
?>
@extends($ext)
@section('content')
 <?php $users = App\User::where('department_id','!=',10)->get() ?>
<span class="pull-right"> @include('flash-message')</span>
 
<div class="container">
<div class="col-md-12">
    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;">Customers Details
                <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color: black;"></i></button>
                @if(count($projects) != 0)
               
               Count : <b> {{ $projects->total() }}</b>
               
                @endif

                    @if(session('ErrorFile'))
                        <div class="alert-danger pull-right">{{ session('ErrorFile' )}}</div>
                    @endif 
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                    <form method="GET" action="{{ URL::to('/') }}/assignvistedcustomer">
                        <div class="col-md-4">
                <label>Choose Visited Employees :</label><br>
                          <select name="userid" class="form-control" id="ward" >
                              <option value="">--Select--</option>
                              <option value="All">All</option>
                              @foreach($users as $ward)
                              <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                              @endforeach
                          </select>
              </div>
               <div class="col-md-3">
                <label>Visit(From Date)</label>
                <input value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from">
              </div>
              <div class="col-md-3">
                <label>Visit(To Date)</label>
                <input  value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="date" class="form-control" name="to">
              </div>
            
              <div class="col-md-2">
                <label></label>
                <input type="submit" value="Fetch" class="form-control btn btn-primary">
              </div>

          </form>
          </div>
        <br><br><br><br>
        <form action="{{URL::to('/')}}/assignunupdatemanu" method="post" >
                    
                 {{ csrf_field() }}
                  <div class="col-md-4">
                    
                 <select  name="user"  style="width:300px;" class="form-control">
                    <option value="">--Select--</option>
                   @foreach($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>
                     @endforeach
                  </select>
                </div>
                 <div class="col-md-2">
                  <input type="submit" class="btn btn-sm btn-warning" value="Assign">
                </div>
          <table class="table table-hover">
          <thead>
                         <th>Select All <br>
                          <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th>
            <th>Customer Id</th>
            <th>Customer Name</th>
            <th>Last Update</th>
            <th>Interested Categories</th>
            <th>Remarks</th>
          </thead>
          
          @foreach($projects as $project)
          <tbody>
             <td><input type="checkbox" name="number[]" class="regular-checkbox name"  id="checkbox-1-1" value="{{$project->id}}" /><label for="checkbox-1-1"></label></td>
            <td style="text-align:center"><a href="{{ URL::to('/') }}/viewmanu?id={{ $project->id }}">{{$project->customer_id}}</a></td>
          
            
            <td>
    


              {{$project->cust->first_name ?? ''}}</td>
           
            <td style="width:10%;">
              {{ date('d-m-Y', strtotime($project->updated_at)) }}
              
            </td>
            <td>
              <?php $data = explode(",", $project->interestcat);

                   $yup = App\Category::whereIn('id',$data)->pluck('category_name');
    
                   ?>
                @foreach($yup as $y)
                    {{$y}}<br>
                @endforeach

            </td>
            <td>{{ $project->remark }}</td>
          </tbody>
          @endforeach
          
        </table>
             @if(count($projects) != 0)
                {{ $projects->appends($_GET)->links() }}
                @endif
                </div>
             
               
    </div>
   </div>
</div>

<script type="text/javascript">
  
    $(function () {
        // add multiple select / deselect functionality
        $("#selectall").click(function () {
            $('.name').attr('checked', this.checked);
        });
 
        // if all checkbox are selected, then check the select all checkbox
        // and viceversa
        $(".name").click(function () {
 
            if ($(".name").length == $(".name:checked").length) {
                $("#selectall").attr("checked", "checked");
            } else {
                $("#selectall").removeAttr("checked");
            }
 
        });
    });
</script>
@endsection
