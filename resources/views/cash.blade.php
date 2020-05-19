@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html>
<head>
  <style>
input[data-readonly] {
  pointer-events: none;
}
</style>
  <style>
* {box-sizing: border-box}
/* Style the tab */
.tab {
    float: left;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
    width: 30%;
    height: 300px;
}

/* Style the buttons inside the tab */
.tab button {
    display: block;
    background-color: inherit;
    color: black;
    padding: 22px 16px;
    width: 100%;
    border: none;
    outline: none;
    text-align: left;
    cursor: pointer;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current "tab button" class */
.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    float: left;
    padding: 0px 12px;
    border: 1px solid #ccc;
    width: 70%;
    border-left: none;
    height: 300px;
    display: none;
}

/* Clear floats after the tab */
.clearfix::after {
    content: "";
    clear: both;
    display: table;
}
</style>

</head>
<body>

<div class="col-md-12">
    <div class="panel panel-primary" style="overflow-x: scroll;">
        <div class="panel-heading text-center" style="width:98%;position:absolute;">
            <b style="color:white;font-size:1.4em">Generate Cash Receipt For Orders</b>
           <button type="button" onclick="history.back(-1)" class="btn btn-default pull-right" style="margin-top:-3px;" > <i class="fa fa-arrow-circle-left" style="width:30px;"></i></button>
            <h4 class="pull-left" style="margin-top: -0.5px;">Total Orders : {{ $orders->total() }}</h4>

        </div><br><br>
  <span class="pull-right"> @include('flash-message')</span>
        
        <div id="myordertable" class="panel-body">
         
            
            <table class="table table-responsive table-striped" border="1">
                <thead>
                    <tr>
                        <th>Order Id</th>
                        <th>Project ID</th>
                        <th>Enquiry ID</th>
                        <th>Category</th>
                        <th>Status</th>  
                        <th>Action</th> 
                    </tr>
                </thead>
                <tbody>
                   @foreach($orders as $order) 
                      <tr>
                        <td>{{$order->id}}</td> 
                        <td>{{$order->project_id}} {{$order->manu_id}}</td> 
                        <td>{{$order->req_id}}</td> 
                        <td>{{$order->main_category}}</td> 
                        <td>{{$order->status}}</td> 
                        <td> <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal{{$order->id}}">Generate Cash Receipt</button>
                          <?php $yes = App\CashRecipt::where('order_id',$order->id)->first(); ?>
                            @if(count($yes) > 0)
                          <a type="button" href="{{ route('downloadcash',['invoiceno'=>$order->id]) }}" class="btn btn-success btn-xs">Download Cash Recepit</a>
                          <a  href="{{URL::to('/')}}/cancelcash?id={{$order->id}}" class="btn btn-danger btn-xs">Cancel Cash Recepit</a>
                          @endif


                        </td>


                      </tr>
                    @endforeach
   
                </tbody>
   </table>
  <center> {{$orders->links()}}</center>
   </div>
 @foreach($orders as $order) 
  <form action="{{URL::to('/')}}/storecash" method="POST" >
                            {{ csrf_field() }}
            
      <div class="modal fade" id="myModal{{$order->id}}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Generate Cash Receipt for ({{$order->id}})</h4>
        </div>
        <div class="modal-body">
         <table class="table">
             <tr>
              <td>Order Id </td>
              <td>:</td>
              <td><input type="text" name="orderid" class="form-control" value="{{$order->id}}"></td>
             </tr>
             
             <tr>
              <td>Customer Name: </td>
              <?php 
                      if($order->project_id != null){

                         $name = App\ProcurementDetails::where('project_id',$order->project_id)->pluck('procurement_name')->first();
                      }else{
                        $name = App\Mprocurement_Details::where('manu_id',$order->manu_id)->pluck('name')->first();
                      }
 
                ?>
              <td>:</td>
              <td><input type="text" name="name" class="form-control" value="{{$name}}"></td>
             </tr>
             <tr>
              <td>Description</td>
              <td>:</td>
                
              <td>
                <textarea name="desc" class="form-control">{{$order->main_category}}</textarea>
              </td>
             </tr>
              <tr>
              <td>Unit </td>
              <td>:</td>
              <td><input type="text" name="unit" class="form-control" value="{{$order->measurement_unit}}"></td>
             </tr>
              <?php $req = App\Requirement::where('id',$order->req_id)->select('billadress','ship')->first(); ?>
            @if(count($req) > 0)
           <tr>
                  <td>Billing Address  </td> 
                  <td>:</td>
                   <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5">{{$req->billadress}}</textarea></td>
                </tr>
                 <tr>
                  <td>Shipping Address  </td> 
                     <td>:</td>                         
                   <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">{{$req->ship}}</textarea></td>
                   </tr>
                   @else
                    <tr>
                  <td>Billing Address  </td> 
                  <td>:</td>
                   <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5"></textarea></td>
                </tr>
                 <tr>
                  <td>Shipping Address  </td> 
                    <td>:</td>                          
                   <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5"></textarea></td>
                   </tr>
          @endif
             <tr>
                  <td>Quantity  </td> 
                    <td>:</td>                          
                   <td><input type="text" name="Quantity" id="quan{{$order->id}}" class="form-control" onkeyup="gettotal('{{$order->id}}')"></td>
                   </tr>
                   <tr>
                  <td>Price  </td> 
                    <td>:</td>                          
                   <td><input type="text" name="price" id="price{{$order->id}}" class="form-control" onkeyup="gettotal('{{$order->id}}')"></td>
                   </tr>
                      <tr>
                  <td>Advance Amount  </td> 
                    <td>:</td>                          
                   <td><input type="text" name="Advance" id="ad{{$order->id}}" class="form-control" onkeyup="gettotal('{{$order->id}}')"></td>
                   </tr>
                     <tr>
                  <td>Total Amount  </td> 
                    <td>:</td>                          
                   <td><input type="text" name="total" id="total1{{$order->id}}" class="form-control" onkeyup="gettotal('{{$order->id}}')"></td>
                   </tr>
                    <td>Balance Amount  </td> 
                    <td>:</td>                          
                   <td><input type="text" name="bal" id="bal1{{$order->id}}" class="form-control" onkeyup="gettotal('{{$order->id}}')"></td>
                   </tr>
         </table>
         <center><button type="submit" class="btn btn-warning btn-sm">Submit</button></center>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  </form>
@endforeach

   <script type="text/javascript">
     function gettotal(arg){
var quan = document.getElementById('quan'+arg).value;
var price = document.getElementById('price'+arg).value;

var ad = document.getElementById('ad'+arg).value;

var total = (quan * price);
 document.getElementById('total1'+arg).value = total;
var bal  = (total - ad);
        document.getElementById('bal1'+arg).value = bal;



     }
   </script>

   </div>
   </div>
   </boby>



  </html>
  @endsection                         