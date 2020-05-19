 @extends('layouts.app')
@section('content') 
<?php
$yearArray = range(2016, 2050);



?>
<div class="container">
<div class="col-md-12">
  <span class="pull-right"> @include('flash-message')</span>

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;padding:15px;"><b>Monthly Sales Details   </b>
                  <div class="pull-left">
                  	<form action="{{ URL::to('/') }}/customersalesreport" method="GET">
                  	<select class="form-control" name="year" onchange="this.form.submit()">
                  		<option value="">--Select Year--</option>
                  		@foreach($yearArray as $yy)
                  		  <option value="{{$yy}}">{{$yy}}</option>
                  		@endforeach  
                  	</select>
                  </form>
                  </div>
              

                    @if(session('ErrorFile'))
                        <div  class="alert-danger pull-right">{{ session('ErrorFile' )}}</div>
                    @endif 
                </div>
                <div class="panel-body">
               <table class="table">
               	<thead>
               		<th>Month</th>
               		<th>InvoiceAmount</th>
                  <th>Supplier Amount</th>
                  <th>TP(With Gst)</th>
                  <th>GST Amount</th>
                  <th>TP(Without Gst)</th>
               	</thead>
               	<tbody>

               		@foreach($final as $finaldata)
               		<tr>
                    
                       <td>
                        <?php $date = date_parse($finaldata['month']); 
                            
                        ?>

                        <a href="{{URL::to('/')}}/monthlycustomerdeatils?year={{$finaldata['year']}}&&month={{$date['month']}}">
                            
                       {{$finaldata['month']}} </a></td>
                       <td>
                       {{number_format($finaldata['invoiceamount'])}}</td>
                       <td>{{number_format($finaldata['spamount'])}}</td>
                       <td>{{number_format($finaldata['withgst'])}} </td>
                       <td>
                        <?php $sp =($finaldata['withgst'] - $finaldata['finalamt']); ?>
                            {{number_format($sp)}} 
                        </td>
                       <td>{{number_format($finaldata['finalamt'])}}</td>
               		</tr>
               		@endforeach
               	</tbody>
               </table>
 
      

</div>
</div>
</div>
</div>
                    

@endsection












































