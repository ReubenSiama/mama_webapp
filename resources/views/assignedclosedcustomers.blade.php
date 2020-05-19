@extends('layouts.app')
@section('content')
<div class="container">

    <div class="row">
  <span class="pull-right"> @include('flash-message')</span>
    	  
  <div class="col-sm-8 ">
    <form action="{{URL::to('/')}}/assignedclosedcustomers" method="get" id="test1" >
       {{ csrf_field() }}
     
                  <div class="col-md-3 col-sm-offset-1">
                     <?php $users = App\User::where('department_id','!=',10)->get() ?>
                 <select  required name="user"  style="width:300px;" class="form-control">
                    <option value="">--Select--</option>
                   @foreach($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>
                     @endforeach
                  </select>
                </div>
                 <div class="col-md-3 col-sm-offset-2 ">
                   <select  required class="form-control" name="type">
                  <option value="">--Select Type of Customer--</option>
                  <option value="Owners">Owners</option>
                  <option value="SiteEngineer">Site Engineer</option>
                  <option value="Contractors">Contractors</option>
                  <option value="builders">Builders</option>
                  <option value="">None</option>

                   </select>
      
                </div>
                 <div class="col-md-1 col-sm-offset-2">
                  <input onclick="document.getElementById('test1').submit()" class="btn btn-sm btn-warning" value="Get">
                </div>
              
      <br><br><br>
 <table class="table" border="1">
         <thead style="background-color:#9fa8da">
            
          <th>SlNo</th>

	  	<th>Name</th>
	  	<th>Number</th>
	  	<th>Projects count</th>
      <th>#</th>
	  </thead>
	    <tbody>
        <?php $m=1; ?>
	    	@foreach($data as $dump)
	    	   <tr>
            
            <td>{{$m++}}</td>

                   <td>{{$dump->name}}</td>
                   <td>{{$dump->number}}</td>
                   <td>{{$dump->products_count}}
                        
                   </td>
                   <td><a href="{{URL::to('/')}}/deleteassignedclosecustomer?id={{$dump->number}}&&user=<?php echo $_GET['user']; ?>" class="btn btn-danger btn-sm">Delete</a></td>
               </tr>
                @endforeach     

	    </tbody>
</table>
@if(count($data) > 0)
<center>{{ $data->appends(request()->query())->links()}} </center>   
 
  @endif
</form>
</div>
 
</div>
</div>

@endsection