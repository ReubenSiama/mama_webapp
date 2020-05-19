<?php
   
    $ext = "layouts.app";
?>
<?php $url = Helpers::geturl(); ?>
@extends($ext)

@section('content')
<div class="col-md-12 col-sm-12">
	<div class="panel panel-primary" style="overflow-x: scroll;">
	<div class="panel-heading ">
		<div class="text-left">
		<b style="color:white;font-size:1.1em">
			 Attendence Deatils &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		</b>
	</div>
		
	</div>
        <div class="row">
        	<form action="{{URL::to('/')}}/attendencereport" method="get" id="hr">
									{{ csrf_field() }}
                 <div class="col-sm-2 col-sm-offset-1">
				Select user 
				<?php 
				$user = App\User::where('department_id','!=',10)->get(); ?>
               	 <select class="form-control" name="user_id">
               	 	<option value="">--Select User--</option>
               	 	@foreach($user as $users)
               	 	<option value="{{$users->id}}">{{$users->name}}</option>
               	 	@endforeach
               	 </select>
               </div>
               <div class="col-sm-2">
				Date Form :<input type="date" name="dateform" class="form-control">
               	
               </div>
               <div class="col-sm-2">
				Date To :<input type="date" name="dateto" class="form-control">
               </div>
               <div class="col-sm-2">
               <br>	<button onclick="document.getElementById('hr').submit()" class="btn btn-sm btn-primary">Get Attendence Details</button>
               </div>
           </form>
			</div>
	<div id="orders" class="panel-body">

    <div class="container">
  <span class="pull-right"> @include('flash-message')</span>
  <table class="table table-responsive" border="1">
  	 <thead>
  	 	<th>Working days</th>
  	 	<th>working date</th>
  	 	<th>working Hours</th>
  	 	<th>Extra Hours</th>
  	 	<th>TL Remark</th>
  	 	<th>HR Remark</th>

  	 </thead>
  	 <tbody>
  	 	<?php $i=1; 
           $extra = [];
           $totalhours =[];
  	 	?>
  	 	@foreach($data as $da)
  	 	<tr>
  	 		<td>{{$i++}}</td>
  	 		<td> {{ date('d-m-Y', strtotime($da->logindate)) }}</td>
  	 		<td>
                 <?php 

                 if($da->totalhours > 11){
                 	 $h = $da->totalhours;
                 	 $d = $h - 9;
                      $hours =9;
                      $dh = $d;
                      array_push($extra, $dh);
                      array_push($totalhours, $hours);


                 }else{
                 	$hours = $da->totalhours;
                 	$d = 0;
                  array_push($totalhours, $hours);

                 }

  	 			?>
                {{$hours}} 
  	 		</td>
  	 		<td>{{$d}}</td>
  	 		<td>{{$da->tlremark}}</td>
  	 		<td>{{$da->hrremark}}</td>
  	 	</tr>
  	 	@endforeach

  	 </tbody>

  </table>
 
   <div class="container">
       <div class="col-md-3 col-md-offset-4">
        
         <label>Gross Salary : 12900 </label>
          <label>No Of days Working :<?php $c = array_sum($totalhours); $days =($c/9);  ?> {{round($days,2)}}  </label><br>
          <label>Total Extra Working :<?php $data = array_sum($extra);
                    $ex = ($data/8);
           ?> {{round($ex,2)}} </label><br>
          <label>Total salary :
            <?php 
              $salary =  (12900 / 22);

                $workingsalary = ($salary * $days);
                $extrasalary = ($salary * $ex);

                 $total = ($workingsalary + $extrasalary);



            ?>

           {{round($total,2)}}

           </label>
          

          
         </div> 
     </div>


  
  </div>
  
  
</div>  

	
</div>
</div>



@endsection