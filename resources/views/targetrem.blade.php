@extends('layouts.app')
@section('content')  

  <span class="pull-right"> @include('flash-message')</span>
	<div class="row">
<div class="col-md-4 ">
       
        <div class="panel panel-danger" style="border-color:rgb(244, 129, 31) ">
            <div class="panel-heading" style="background-color: rgb(244, 129, 31);color:white;font-weight:bold;font-size:15px;">
              <center>   Sales Employess Target </center><button type="button" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#myModal" style="margin-top:-20px;">Set Target</button>
                
            </div>
            <div style="overflow-x: scroll;" class="panel-body">

                <div class="col-md-12" >
                    
                </div>
                <br>
                <table id="manufacturer" class="table table-responsive" border=1><br>
                    <thead>
                        <th>User Name</th>
                        <th>TP Amount</th>
                        <th>Total Amount</th>
                          <th>Start</th>
                        <th>End</th>
                    </thead>
                    <tbody>
                    	@foreach($salesdata as $sales)
                       <tr>
                       	<td>{{$sales->user->name ?? ''}}</td>
                       	<td>{{number_format($sales->tpamount)}}</td>
                        <td>{{number_format($sales->totalamount)}}</td>
                        
                       	  <td>{{date('d-m-Y', strtotime($sales->start))}}</td>
                         <td>{{date('d-m-Y', strtotime($sales->end))}}</td>
                       </tr>
                       @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Sales Target</h4>
        </div>
        <div class="modal-body">
        	<form action="{{URL::to('/')}}/salestarget" method="POST" >
        		 {{ csrf_field() }}
          <table class="table table-responsive">
          	<tr>
          		<td>User Name</td>
          		<td>:</td>
          		<td>
                      <?php $users = App\User::where('department_id','!=',10)->get(); ?>

                      <select class="form-control " name="user">
                      	<option value="">----select User-----</option>

                      	@foreach($users as $user)
                      	<option value="{{$user->id}}">{{$user->name}}</option>
                      	@endforeach
                      </select>


          		</td>
          	</tr>
          		
          	<tr>
          		<td>TP Amount</td>
          		 <td>:</td>
          		 <td><input type="number" name="targetval" class="form-control"></td>
          		</tr>
              <tr>
              <td>Total Amount</td>
               <td>:</td>
               <td><input type="number" name="totalamount" class="form-control"></td>
              </tr>
          		<tr>
          			<tr>
          		<td>start Time</td>
          		 <td>:</td>
          		 <td><input type="date" name="start" class="form-control"></td>
          		</tr>
          		<tr>
          	  <tr>
          		<td>End Time</td>
          		 <td>:</td>
          		 <td><input type="date" name="end" class="form-control"></td>
          		</tr>
          		<tr>
          			<td></td>
          			<td>

          		<center><button type="submit" class="form-control btn-sm btn-warning" >Set Target</button></center>
          		</td>
          		<td></td>
          		</tr>
          </table>
      </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  <!-- <-------------------------------------------Dedicated customers----------------------------------------------------->
       <div class="col-md-4 ">
              
               <div class="panel panel-danger" style="border-color:rgb(244, 129, 31) ">
                   <div class="panel-heading" style="background-color: rgb(244, 129, 31);color:white;font-weight:bold;font-size:15px;">
                     <center>   Dedicated Customers Target </center><button type="button" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#myModal10" style="margin-top:-20px;">Set Target</button>
                       
                   </div>
                   <div style="overflow-x: scroll;" class="panel-body">

                       <div class="col-md-12" >
                           
                       </div>
                       <br>
                       <table id="manufacturer" class="table table-responsive" border=1><br>
                           <thead>
                               <th>User Name</th>
                               <th>TP Amount</th>
                               <th>Total Amount</th>
                                 <th>Start</th>
                               <th>End</th>
                           </thead>
                           <tbody>
                            @foreach($didicate as $sales)
                              <tr>
                                <td>{{$sales->user->name ?? ''}}</td>
                                <td>{{number_format($sales->tpamount)}}</td>
                               <td>{{number_format($sales->totalamount)}}</td>
                               
                                  <td>{{date('d-m-Y', strtotime($sales->start))}}</td>
                                <td>{{date('d-m-Y', strtotime($sales->end))}}</td>
                              </tr>
                              @endforeach
                           </tbody>
                       </table>
                   </div>
               </div>
       </div>
       <div class="modal fade" id="myModal10" role="dialog">
           <div class="modal-dialog">
           
             <!-- Modal content-->
             <div class="modal-content">
               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">Dedicated Customers Target</h4>
               </div>
               <div class="modal-body">
                <form action="{{URL::to('/')}}/decicatedcust" method="POST" >
                   {{ csrf_field() }}
                 <table class="table table-responsive">
                  <tr>
                    <td>User Name</td>
                    <td>:</td>
                    <td>
                             <?php $users = App\User::where('department_id','!=',10)->get(); ?>

                             <select class="form-control " name="user">
                              <option value="">----select User-----</option>

                              @foreach($users as $user)
                              <option value="{{$user->id}}">{{$user->name}}</option>
                              @endforeach
                             </select>


                    </td>
                  </tr>
                    
                  <tr>
                    <td>TP Amount</td>
                     <td>:</td>
                     <td><input type="number" name="targetval" class="form-control"></td>
                    </tr>
                     <tr>
                     <td>Total Amount</td>
                      <td>:</td>
                      <td><input type="number" name="totalamount" class="form-control"></td>
                     </tr>
                    <tr>
                      <tr>
                    <td>start Time</td>
                     <td>:</td>
                     <td><input type="date" name="start" class="form-control"></td>
                    </tr>
                    <tr>
                    <tr>
                    <td>End Time</td>
                     <td>:</td>
                     <td><input type="date" name="end" class="form-control"></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td>

                    <center><button type="submit" class="form-control btn-sm btn-warning" >Set Target</button></center>
                    </td>
                    <td></td>
                    </tr>
                 </table>
             </form>
               </div>
               <div class="modal-footer">
                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               </div>
             </div>
             
           </div>
         </div>
<!-- <--------------------------------------------------------------------------------------------------> 
<div class="col-md-4 ">
       
        <div class="panel panel-danger" style="border-color:rgb(244, 129, 31) ">
            <div class="panel-heading" style="background-color: rgb(244, 129, 31);color:white;font-weight:bold;font-size:20px;">
              <center>   Company Target </center><button type="button" class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#myModal1" style="margin-top:-20px;">Set Target</button>
                
            </div>
            <div style="overflow-x: scroll;" class="panel-body">
                <div class="col-md-12" >
                    
                </div>
                <br>
                <table id="manufacturer" class="table table-responsive" border=1><br>
                    <thead>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>total</th>
                        <th>Percent(%)</th>
                        <th>TP amount</th>
                        <th>Start</th>
                        <th>End</th>


                    </thead>
                    <tbody>
                    	@foreach($data as $yep)
                       <tr>
                         <td>{{$yep->cat->category_name ?? ''}}</td>
                         <td>{{$yep->cat->measurement_unit ?? ''}}</td>
                         <td>{{$yep->quantity}}</td>
                         <td>{{$yep->price}}</td>
                         <?php $total = ($yep->quantity * $yep->price ); ?>
                         <td>{{number_format($total)}}</td>
                         <td>{{$yep->percent}}</td>
                         <td>{{number_format($yep->totalatpmount)}}</td>
                         
                         <td>{{date('d-m-Y', strtotime($yep->start))}}</td>
                         <td>{{date('d-m-Y', strtotime($yep->end))}}</td>
                       </tr>
                       @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</div>


<div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Category Target</h4>
        </div>
        <div class="modal-body">
       <form action="{{URL::to('/')}}/cattarget" method="POST">
       	 {{ csrf_field() }}
          <table class="table table-responsive">
          	<tr>
          		<td>Category</td>
          		<td>:</td>
          		<td>
                      <?php $users = App\Category::get(); ?>

                      <select class="form-control " name="category">
                      	<option value="">----select category-----</option>

                      	@foreach($users as $user)
                      	<option value="{{$user->id}}">{{$user->category_name}}</option>
                      	@endforeach
                      </select>


          		</td>
          	</tr>
          	<tr>
          		<td>Unit</td>
          		<td>:</td>
          		<td>
                      <?php $users = App\Category::get(); ?>

                      <select class="form-control " name="unit">
                      	<option value="">----select Unit-----</option>

                      	@foreach($users as $user)
                      	<option value="{{$user->measurement_unit}}">{{$user->measurement_unit}}</option>
                      	@endforeach
                      </select>


          		</td>
          	</tr>
          	<tr>
          		<td>Quantity</td>
          		 <td>:</td>
          		 <td><input type="number" name="quantity" class="form-control" id="quan" onkeyup="getamount()"></td>
          		</tr>
          			<tr>
          		<td>Price</td>
          		 <td>:</td>
          		 <td><input type="number" name="price" class="form-control" id="price" onkeyup="getamount()"></td>
          		</tr>
              <td>Total Amount</td>
               <td>:</td>
               <td><input type="number" name="total" class="form-control" id="totalAM"></td>
              </tr>
          		<tr>
          			<td>Target(%)</td>
          			<td>:</td>
          			<td><input type="number" name="percernt" class="form-control" id="target" onkeyup="gettarget()"></td>
          	</tr>
          	<tr>
          		<td>Target  Tp amount</td>
          		 <td>:</td>
          		 <td><input type="number" name="targetval" class="form-control" id="total"></td>
          		</tr>
          		<tr>
          		<td>start Time</td>
          		 <td>:</td>
          		 <td><input type="date" name="start" class="form-control"></td>
          		</tr>
          		<tr>
          	  <tr>
          		<td>End Time</td>
          		 <td>:</td>
          		 <td><input type="date" name="end" class="form-control"></td>
          		</tr>
          	<tr>
          			<td></td>
          			<td>

          		<center><button type="submit" class="form-control btn-sm btn-warning" >Set Target</button></center>
          		</td>
          		<td></td>
          		</tr>
          </table>
      </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  <script type="text/javascript">
  	
      function  gettarget() {
       
       var quantity = document.getElementById('quan').value;
       var price = document.getElementById('price').value;
       var percent = document.getElementById('target').value;

      var total =(quantity*price); 

         var final = (total * percent)/100;




      document.getElementById('total').value=final;
      

      }


  </script>
  <script type="text/javascript">
    function getamount(){
       var quantity = document.getElementById('quan').value;
       var price = document.getElementById('price').value;
       var total = (quantity*price);

       document.getElementById('totalAM').value=total;

       gettarget();
    }
  </script>
@endsection