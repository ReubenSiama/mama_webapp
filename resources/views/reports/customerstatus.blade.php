@extends('layouts.app')
@section('content')
<div class="container">

    <div class="row">
  <span class="pull-right"> @include('flash-message')</span>
    	  
  <div class="col-sm-8 ">
    <form action="{{URL::to('/')}}/customerstatus" method="get" id="test1" >
       {{ csrf_field() }}
                 <div class="col-md-6 col-sm-offset-2 ">
                   <select class="form-control" name="type">
                  <option value="">--Select Status of Customer--</option>
                  <option value="Looking Credit">Looking Credit</option>
                  <option value="Others"> Others</option>
                  <option value="Dealers">Dealers</option>
                  <option value="Closed Customers">Closed Customers</option>
                  <option value="Blocked">Blocked</option>


                   </select>
      
                </div>
                 <div class="col-md-1 ">
                  <input onclick="document.getElementById('test1').submit()" class="btn btn-sm btn-warning" value="GEt Details">
                </div>
              
      <br><br><br>
 <table class="table" border="1">
         <thead style="background-color:#9fa8da">
            
          <th>SlNo</th>

	  	<th>Name</th>
	  	<th>Number</th>
	  	<th>Customer Id</th>
      <th>Remarks</th>
     
	  </thead>
	    <tbody>
        <?php $m=1; ?>
	    	@foreach($data as $dump)
	    	   <tr>
            
            <td>{{$m++}}</td>

                   <td>{{$dump->first_name}}</td>
                   <td>{{$dump->mobile_num}}</td>
                   <td><a href="{{URL::to('/')}}/customerprojects?customer_id={{$dump->customer_id }}" target="_blank">{{ $dump->customer_id }}</a>
                        
                   </td>
                   <td>{{$dump->remarks}}</td>
                  
               </tr>
                @endforeach     

	    </tbody>
</table>

</form>
</div>
 
</div>
</div>

@endsection