<?php
    $use = Auth::user()->group_id;
    $ext = ($use == 1 || 2? "layouts.app":"layouts.leheader");
?>
<?php $url = Helpers::geturl(); ?>
@extends($ext)
<style type="text/css">
  /* === card component ====== 
 * Variation of the panel component
 * version 2018.10.30
 * https://codepen.io/jstneg/pen/EVKYZj
 */
.card{ background-color: #fff; border: 1px solid transparent; border-radius: 6px; }
.card > .card-link{ color: #333; }
.card > .card-link:hover{  text-decoration: none; }
.card > .card-link .card-img img{ border-radius: 6px 6px 0 0; }
.card .card-img{ position: relative; padding: 0; display: table; }
.card .card-img .card-caption{
  position: absolute;
  right: 0;
  bottom: 16px;
  left: 0;
}
.card .card-body{ display: table; width: 100%; padding: 12px; }
.card .card-header{ border-radius: 6px 6px 0 0; padding: 8px; }
.card .card-footer{ border-radius: 0 0 6px 6px; padding: 8px; }
.card .card-left{ position: relative; float: left; padding: 0 0 8px 0; }
.card .card-right{ position: relative; float: left; padding: 8px 0 0 0; text-overflow: hidden; }
.card .card-body h1:first-child,
.card .card-body h2:first-child,
.card .card-body h3:first-child, 
.card .card-body h4:first-child,
.card .card-body .h1,
.card .card-body .h2,
.card .card-body .h3, 
.card .card-body .h4{ margin-top: 0; }
.card .card-body .heading{ display: block;  }
.card .card-body .heading:last-child{ margin-bottom: 0; }

.card .card-body .lead{ text-align: center; }

@media( min-width: 768px ){
  .card .card-left{ float: left; padding: 0 8px 0 0; }
  .card .card-right{ float: left; padding: 0 0 0 8px; }
    
  .card .card-4-8 .card-left{ width: 33.33333333%; }
  .card .card-4-8 .card-right{ width: 66.66666667%; }

  .card .card-5-7 .card-left{ width: 41.66666667%; }
  .card .card-5-7 .card-right{ width: 58.33333333%; }
  
  .card .card-6-6 .card-left{ width: 50%; }
  .card .card-6-6 .card-right{ width: 50%; }
  
  .card .card-7-5 .card-left{ width: 58.33333333%; }
  .card .card-7-5 .card-right{ width: 41.66666667%; }
  
  .card .card-8-4 .card-left{ width: 66.66666667%; }
  .card .card-8-4 .card-right{ width: 33.33333333%; }
}

/* -- default theme ------ */
.card-default{ 
  border-color: #ddd;
  background-color: #fff;
  margin-bottom: 24px;
}
.card-default > .card-header,
.card-default > .card-footer{ color: #333; background-color: #ddd; }
.card-default > .card-header{ border-bottom: 1px solid #ddd; padding: 8px; }
.card-default > .card-footer{ border-top: 1px solid #ddd; padding: 8px; }
.card-default > .card-body{  }
.card-default > .card-img:first-child img{ border-radius: 6px 6px 0 0; }
.card-default > .card-left{ padding-right: 4px; }
.card-default > .card-right{ padding-left: 4px; }
.card-default p:last-child{ margin-bottom: 0; }
.card-default .card-caption { color: #fff; text-align: center; text-transform: uppercase; }


/* -- price theme ------ */
.card-price{ border-color: #999; background-color: #ededed; margin-bottom: 24px; }
.card-price > .card-heading,
.card-price > .card-footer{ color: #333; background-color: #fdfdfd; }
.card-price > .card-heading{ border-bottom: 1px solid #ddd; padding: 8px; }
.card-price > .card-footer{ border-top: 1px solid #ddd; padding: 8px; }
.card-price > .card-img:first-child img{ border-radius: 6px 6px 0 0; }
.card-price > .card-left{ padding-right: 4px; }
.card-price > .card-right{ padding-left: 4px; }
.card-price .card-caption { color: #fff; text-align: center; text-transform: uppercase; }
.card-price p:last-child{ margin-bottom: 0; }

.card-price .price{ 
  text-align: center; 
  color: #337ab7; 
  font-size: 3em; 
  text-transform: uppercase;
  line-height: 0.7em; 
  margin: 24px 0 16px;
}
.card-price .price small{ font-size: 0.4em; color: #66a5da; }
.card-price .details{ list-style: none; margin-bottom: 24px; padding: 0 18px; }
.card-price .details li{ text-align: center; margin-bottom: 8px; }
.card-price .buy-now{ text-transform: uppercase; }
.card-price table .price{ font-size: 1.2em; font-weight: 700; text-align: left; }
.card-price table .note{ color: #666; font-size: 0.8em; }
</style>
@section('content')
<div class="col-md-12 col-sm-12">
	<div class="panel panel-primary" style="overflow-x: scroll;">
	<div class="panel-heading ">
		<div class="text-left">
		<b style="color:white;font-size:1.1em">
			Today Attendence Deatils &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		</b>
	</div>
		
	</div>
        <div class="row">
        	<form action="{{URL::to('/')}}/hrverify" method="get" id="hr">
									{{ csrf_field() }}

               <div class="col-sm-2 col-sm-offset-1">
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

  <center> <span style="font-weight:bold;font-size:20px;font-style:italic;background-color:#9f905d;">Attendece Details</span><br>
  	<span style="font-weight:bold;font-size:10px;font-style:italic;background-color:#0cd988;">Highlighted Colour Indicates Working Over Time</span>
  </center><br>
  <div class="row">
   
    @foreach($time as $df)
   <div class="col-sm-3">
  <div class="card card-default">
  
  <form action="{{URL::to('/')}}/hrapproved" method="post" id="yes{{$df->id}}">
									{{ csrf_field() }}
  <input type="hidden" name="userid" value="{{$df->user_id}}">
  <input type="hidden" name="logindate" value="{{$df->logindate}}">
  <input type="hidden" name="loginid" value="{{$df->id}}">
   <div class="card-header" style="background-color:#ccafaf;">
     <?php $user = App\User::where('id',$df->user_id)->pluck('name')->first(); ?>

  {{$user}}</div>
  <div class="card-body card-5-7">
    
    <div class="card-right">
       <table class="table">
      <tr>
      	<th>Logindate</th>
      	
      	<th>Hours Worked</th>
      </tr>
      	@if($df->totalhours > 9)
      <tr style="background-color:#0cd988;">
      	<td>{{$df->logindate}}</td>
      	<td class="price"><input type="text" name="totalthours" class="form-control" value="{{round($df->totalhours,2)}}"></td>
      </tr>
      @else
       <tr>
      	<td>{{$df->logindate}}</td>
      	<td class="price"><input type="text" name="totalthours" class="form-control" value="{{round($df->totalhours,2)}}"></td>
      </tr>
      @endif
     
    </table>
    <table class="table">
    	<tr>Rmarks : <textarea class="form-control" name="remark">
     	
     </textarea></tr>
    </table>
    </div>
  </div>
  <div class="card-footer" style="background-color:#ccafaf;">
    <center><center>
  	<button onclick="submit('{{$df->id}}')" class="btn btn-sm btn-success">Approve</button></center></center>
  </div>
</form>
</div>


    </div>

    @endforeach
    
  </div>
  
  
</div>  

	</div>
</div>
</div>

<script type="text/javascript">
	function subnit(arg){

		document.getElementById('yes'+arg).submit();
	}

</script>

@endsection