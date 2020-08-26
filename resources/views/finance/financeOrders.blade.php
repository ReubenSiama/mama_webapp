<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 26? "finance.layouts.headers":"layouts.app");
?>
<?php $url = Helpers::geturl(); ?>
@extends($ext)
@section('content')
<div class="col-md-12">
    <div class="panel panel-primary" style="overflow-x: scroll;">
        <div class="panel-heading text-center" style="width:98%;position:absolute;">
            <b style="color:white;font-size:1.4em">Confirmed Orders</b>
           <button type="button" onclick="history.back(-1)" class="btn btn-default pull-right" style="margin-top:-3px;" > <i class="fa fa-arrow-circle-left" style="width:30px;"></i></button>
            <h4 class="pull-left" style="margin-top: -0.5px;">Total Count : {{ $count }}</h4>
                  
        </div><br><br>
        <div id="myordertable" class="panel-body">
          <form action="financeDashboard" method="get" id="d">
          <div class="pull-left">
             <div class="col-md-2">
                <lable>From </lable>
                <input value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from">
              </div>
              <div class="col-md-2">
                <lable>To</lable>
                <input  value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="date" class="form-control" name="to">
              </div>
              
                   <div class="col-md-3">
                <lable><b>Select Initiator </b></lable>
                <?php $ins = App\User::where('department_id','!=',10)->get(); ?>
                   <select class="form-control" name="user">
                      <option value="">---Select----</option>
                        @foreach($ins as $in)
                          <option value="{{$in->id}}">{{$in->name}}</option>
                        @endforeach

                     
                   </select>                  
              </div>
              <div class="col-md-3">
                <lable><b>Select Category</b></lable>
                <?php $inss = App\Category::get(); ?>
                   <select class="form-control" name="category">
                      <option value="">---Select----</option>
                        @foreach($inss as $ins)
                          <option value="{{$ins->category_name}}">{{$ins->category_name}}</option>
                        @endforeach

                     
                   </select>    
              </div>
                <div class="col-md-2">
                 <label><b>Fetch</b></label><br>
                 <button class="btn btn-warning btn-sm" onclick="document.getElementById('d').submit()">Fetch</button>
                </div>


          </div>
        </form>
 <form action="financeDashboard" method="get">
                <div class="input-group col-md-3 pull-right">
                    <input type="text" class="form-control pull-left" placeholder="Enter Project Id Or Order Id" name="projectId" id="projectId">
                    
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                </div>
</form><br><br>
<div class="col-md-12">
<span class="pull-right"> @include('flash-message')</span>
  <br><br>
    <table class="table table-responsive" border=1>

        <th>Requirement Date</th>
        <th>Project Id</th>
        <th>Order Id</th>
        <th>MH Tax Invoice Date</th>
        <th>Category</th>
        <th style="width:30%">Quantity</th>
        <th>Invoice No</th>
        <!-- <th>Customer ID</th> -->
        <th>Payment Details</th>
        @if(Auth::user()->group_id != 22)
        <th> Generate Invoice</th>
        <th>MAMAHOME Invoice</th>
        <th>MR Invoice  </th>
        <th>Multiple Products</th>
        <th>Generate Customer ID</th>
        @endif
        @foreach($orders as $order)
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                  <p>.</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
              
            </div>
          </div>
        <tr style="{{ date('Y-m-d') == $order->requirement_date ? 'background-color:#ccffcc': '' }}">
            <td>{{ date('d M, y',strtotime($order->requirement_date)) }}</td>
             <td style="text-align:center"><a href="{{ URL::to('/') }}/admindailyslots?projectId={{$order->project_id}}&&lename=" target="_blank">{{ $order->project_id }}</a>
              @if($order-> project_id == null)
                            <a href="{{ URL::to('/') }}/viewmanu?id={{$order->manu_id}}">Manufacturer{{$order->manu_id}}</a>
              @endif
             </td>
           
            <td>{{ $order->id }}</td>
            <td> <?php
             $date = App\MamahomePrice::where('order_id',$order->id)->pluck('created_at')->first(); 
             $date1 = App\MultipleInvoice::where('order_id',$order->id)->pluck('created_at')->first(); 
                      
               ?>
             @if($date!=null) 

               {{ date('d M, y',strtotime($date)) }}
            
             @else
            
            
             @endif
             
             </td>
            <td>{{ $order->main_category }}

            


            </td>
            <td style="width:30%">{{ $order->quantity }}</td>
            <?php $invoice = App\MamahomePrice::where('order_id',$order->id)->pluck('invoiceno')->first(); 
                  $number = App\MultipleInvoice::where('order_id',$order->id)->pluck('invoiceno')->first();
             ?>
            <td>
             @if(count($invoice) > 0)
            {{ $invoice }}
            @else
             {{$number}}
             @endif
          </td>
            <td>
                @if($order->payment_status == "Payment Received")
                <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal{{ $order->id }}">
                    Payment Details
                    <span class="badge">{{ $counts[$order->id] }}</span>    
                </button>
                @endif
            </td>
              @if(Auth::user()->group_id != 22)
            <td>
            <?php 
                $rec =count($order->confirm_payment); 
             ?> 
                @if($rec == 0)
                   @if($order->payment_status == "Payment Received")
             <?php $mmm = [40,41,52,48]; 
             $eqs = App\FLOORINGS::where('req_id',$order->req_id)->whereIn('category',$mmm)->first();  
                  
             ?>
             @if(count($eqs) != 0)
                  Just Click Multiple Products Invoice Button

                @else
                 <div class="btn-group">
                                <!-- <a class="btn btn-xs btn-success" href="{{URL::to('/')}}/confirmpayment?id={{ $order->id }}">Confirm</a> -->
                               <button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#payment{{$order->id}}"> Confirm</button>
                                <button class="btn btn-xs btn-danger pull-right" data-toggle="modal" data-target="#clear{{$order->id}}">Cancel</button>
                </div>
                @endif
                   @else
                     Payment pending
                   @endif
                 @else
                  <button type="button" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#payment{{$order->id}}">Edit</button>
                 @endif             
            </td>
            <td>
                
                    
            <?php 
                $rec =count($order->confirm_payment); 
              
             ?> 
                @if($rec == 1)
                 @foreach($mamaprices as $price )  
                  @if($price->order_id == $order->id)
                    <div class="btn-group">
                    <a type="button" href="{{ route('downloadInvoice',['invoiceno'=>$price->invoiceno,'manu_id'=>$order->manu_id]) }}" class="btn btn-primary btn-xs">MH PROFORMA</a>
              
                    <a type="button" href="{{ route('downloadTaxInvoice',['invoiceno'=>$price->invoiceno,'manu_id'=>$order->manu_id]) }}" class="btn btn-success btn-xs">MH TAX</a><br>

                    <!-- <a type="buttoassignppin"  href="{{ route('downloadpurchaseOrder',['id'=>$order->id]) }}" class="btn btn-danger btn-xs">PUCHASE</a> -->
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModaly{{$price->id}}"> Eway and Others</button>

                          <!-- Modal -->
  <!-- <div class="modal fade" id="myModal{{$price->id}}" role="dialog">
    <div class="modal-dialog"> -->
    
      <!-- Modal content-->
      

                       







    
      <!-- Modal content-->
   <div class="modal fade" id="myModaly{{$price->id}}" role="dialog">

    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">E-Way and Others</h4>
        </div>
        <div class="modal-body">

 
          <form  action="{{URL::to('/')}}/mixedeway" method="post"  enctype="multipart/form-data">
            {{ csrf_field() }}
          <table class="table">
         <input type="hidden" name="orderid" value="{{$order->id}}">
          <tr>
              <td>Select Type</td>
              <td>:</td>
              <td><select class="form-control" name="type">
                  <option value="">--Select--</option>
                  <option value="MH">MH(Mamahome)</option>
                  <option value="MR">MR(M R INFRASTRUCTURE DEVELOPERS)</option>

              </select>

              </td>

            </tr><br>
            <tr>
              <td>E-Way Bill No</td>
              <td>:</td>
              <td><input type="text" name="mrebilno" class="form-control"></td>

            </tr><br>
            <tr>
              <td>E-Way Bill image</td>
              <td>:</td>
              <td><input type="file" name="mrebilimg" class="form-control"></td>

            </tr>
             <tr>
              <td>Tax Invoice Date</td>
              <td>:</td>
              <td><input type="date" name="taxdate" class="form-control"></td>

            </tr>
              <tr>
              <td>Truck No</td>
              <td>:</td>
              <td><input type="text" name="truckno" class="form-control"></td>

            </tr>
              <tr>
              <td>HSN</td>
              <td>:</td>
              <td><input type="text" name="hsn" class="form-control"></td>

            </tr>

            </table>
            <center><button type="submit" class="btn btn-sm btn-warning">Submit</button></center>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
      
    </div>

  
</div>


                  </div>
                  @endif
                  @endforeach
               @else
                    <div class="btn-group">
    <a disabled type="button" href="{{ route('downloadInvoice',['id'=>$order->id]) }}" class="btn btn-primary btn-xs">PROFORMA</a>
    <a disabled type="button" href="{{ route('downloadTaxInvoice',['id'=>$order->id]) }}" class="btn btn-success btn-xs">TAX</a>
    
  </div>
            
                @endif
                @if($rec == 1)
                     <button style="font-size:15px" onclick="reset('{{$order->id}}')" class="btn btn-default btn-xs"><img src="https://cdn4.iconfinder.com/data/icons/settings-8/24/Reset-Settings-512.png" height="20px" width="20px;"></button>
                     <form method="POST" action="{{ URL::to('/') }}/resetinvoice" >
                                          {{ csrf_field() }}
                                          <input id="resetid{{$order->id}}" type="hidden" name="resetid" value="">
                                      <button id="reset{{$order->id}}" class="hidden" type="submit" >Submit</button>
                    </form>
                    @else
                @endif
            </td>
            <td>
                 @if($order->brand == "CHETTINAD")
                    <?php $sm = App\MRInvoice::where('order_id',$order->id)->first() ?>
                       @if(count($sm) == 0)

                    <button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#mrpayment{{$order->id}}"> MR Confirm</button>
                    @else
                    <button type="button" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#mrpayment{{$order->id}}">Edit MR</button> <br>
                                {{$sm->invoiceno}} <br>
          <a type="button" href="{{ route('downloadmrInvoice',['invoiceno'=>$sm->invoiceno,'manu_id'=>$order->manu_id]) }}" class="btn btn-info btn-xs">MR PROFORMA</a>
          <a type="button" href="{{ route('downloadmrTaxInvoice',['invoiceno'=>$sm->invoiceno,'manu_id'=>$order->manu_id]) }}" class="btn btn-danger btn-xs">MR TAX</a><br>
                     @endif
                     @else
                       N/A          
                 @endif    
            </td>
          @endif
          <td>
            <?php 
                $orderid =$order->id;

                $mul = App\MultipleInvoice::where('order_id',$orderid)->first();

                $manu_id = DB::table('orders')->where('id',$orderid)->pluck('manu_id')->first();

              

            ?>
              @if(count($mul) > 0)
               <div class="btn-group">
                    <a type="button" href="{{ route('downloadInvoice1',['invoiceno'=>$mul->invoiceno !=null ?$mul->invoiceno:'' ,'id'=>$mul->id,'manu_id'=>$manu_id]) }}" class="btn btn-primary btn-xs">MH PROFORMA</a>
                    
                    <a type="button" href="{{ route('downloadTaxInvoice1',['invoiceno'=>$mul->invoiceno,'id'=>$mul->id]) }}" class="btn btn-success btn-xs">MH TAX&nbsp;</a>
                  
                  
                    <!-- <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModaly"> Ewaybill</button> -->
                     <button style="font-size:15px" onclick="resetmulti('{{$mul->id}}')" class="btn btn-default btn-xs"><img src="https://cdn4.iconfinder.com/data/icons/settings-8/24/Reset-Settings-512.png" height="20px" width="20px;"></button>
                     <form method="POST" action="{{ URL::to('/') }}/resetmultiinvoice" id="multi{{$mul->id}}" >
                                          {{ csrf_field() }}
                                          <input id="rese{{$mul->id}}" type="hidden" name="id" value="">
                                      <button id="reset{{$order->id}}" class="hidden" type="submit" >Submit</button>
                    </form>

                          <!-- Modal -->
 
  
</div>
                  
                   
                   
                  </div>
              @else
              @if($order->payment_status == "Payment Received")
              <a  href="{{URL::to('/')}}/invoicegen?orderid={{$order->id}}&&enqid={{$order->req_id}}&&project_d={{$order->project_id}}&&invoiceno={{$invoice}}" class="btn btn-xs btn-warning">Multiple Products Invoice</a>
              @else
              Payment Pending
              @endif
               @endif

          </td>
          <td>
            <?php 
                  $invoice = App\MamahomePrice::where('order_id',$order->id)->pluck('invoiceno')->first();

                    if(count($invoice) == 0){
                     $invoice = App\MultipleInvoice::where('order_id',$order->id)->pluck('invoiceno')->first();

                    }
                  $no = App\CustomerInvoice::where('invoiceno',$invoice)->pluck('customer_id')->first(); ?>
          
                
              @if(count($no) == 0)
            <a  class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModalcustomer{{$order->id}}">Close The Order </a>


  <!-- Modal -->
  <form action="{{URL::to('/')}}/getcustomerids" method="POST" id="cust">
                            {{ csrf_field() }}
        
  <div class="modal fade" id="myModalcustomer{{$order->id}}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Verify All Order Details</h4>
        </div>
        <div class="modal-body">
 <a  href="#" class="btn btn-warning btn-sm"  onclick="show('Page1{{$order->id}}');">Invoice Details</a> 
 <a href="#"  class="btn btn-primary btn-sm" onclick="show('Page2{{$order->id}}');">Delivery Details</a>
  <a  href="#" class="btn btn-danger btn-sm" onclick="show('Page3{{$order->id}}');">Supplier Details</a>.
  <a  href="#" class="btn btn-success btn-sm" onclick="show('Page4{{$order->id}}');">Generate Customer Id</a>.



<script type="text/javascript">
  function show(elementID) {

    var ele = document.getElementById(elementID);
    if (!ele) {
        alert("no such element");
        return;
    }
    var pages = document.getElementsByClassName('page');
    for(var i = 0; i < pages.length; i++) {
        pages[i].style.display = 'none';
    }
    ele.style.display = 'block';
}
</script>

<div id="Page1{{$order->id}}" class="page" style="display:none">
    <?php $invoics = App\MamahomePrice::where('order_id',$orderid)->first(); 
           
        if(count($invoics) == 0){
            $info = App\MultipleInvoice::where('order_id',$orderid)->first(); 
        }else{
          $info = "";
        }   

    ?>
    <h3 style="text-align:center;font-weight:bold;">Invoice Details</h3>
    @if(count($invoics) > 0)
   <table class="table">
      <tr>
        <td>Invoice No</td>
        <td>:</td>
        <td>
        
          <input type="text" name="invoiceno" class="form-control" value="{{$invoics->invoiceno}}">
          
          
          
        </td>
      </tr>
      <tr>
        <td>Invoice Date</td>
        <td>:</td>
        <td><input type="text" name="invoicedate" class="form-control" value="{{$invoics->created_at}}"></td>
      </tr>
      <tr>
        <td>Category</td>
        <td>:</td>
        <td><input type="text" name="category" class="form-control" value="{{$invoics->category}}"></td>
      </tr>
      <tr>
        <td>Quantity</td>
        <td>:</td>
        <td><input type="text" name="quantity" class="form-control" value="{{$invoics->quantity}}"></td>
      </tr>
      <tr>
        <td>Mamahome Price</td>
        <td>:</td>
        <td><input type="text" name="mamahome_price" class="form-control" value="{{$invoics->mamahome_price}}"></td>
      </tr>

       <tr>
        <td>Total Amount</td>
        <td>:</td>
        <td><input type="text" name="totalamount" class="form-control" value="{{$invoics->totalamount}}"></td>
      </tr>

       <tr>
        <td>Totaltax</td>
        <td>:</td>
        <td><input type="text" name="mamahome_price" class="form-control" value="{{$invoics->totaltax}}"></td>
      </tr>
     
   </table>
   @endif
  @if(count($info) > 0 && count($invoics) == 0)
   <table class="table">
      <tr>
        <td>Invoice No</td>
        <td>:</td>
        <td>
        
          <input type="text" name="invoiceno" class="form-control" value="{{$info->invoiceno}}">
          
          
          
        </td>
      </tr>
      <tr>
        <td>Invoice Date</td>
        <td>:</td>
        <td><input type="text" name="invoicedate" class="form-control" value="{{$info->created_at}}"></td>
      </tr>
      <tr>
        <td>Category</td>
        <td>:</td>
        <?php $cat = explode(",", $info->category); 
              $category = App\Category::where('id',$cat[0])->pluck('category_name')->first(); 
         ?>
        <td><input type="text" name="category" class="form-control" value="{{$category}}"></td>
      </tr>
      <tr>
        <td>Quantity</td>
        <td>:</td>
        <td><input type="text" name="quantity" class="form-control" value="{{$info->quantity}}"></td>
      </tr>
      <tr>
        <td>Mamahome Price</td>
        <td>:</td>
        <td><input type="text" name="mamahome_price" class="form-control" value="{{$info->price}}"></td>
      </tr>

       <tr>
        <td>Total Amount</td>
        <td>:</td>
        <td><input type="text" name="totalamount" class="form-control" value="{{$info->totalwithgst}}"></td>
      </tr>

       <tr>
        <td>Totaltax</td>
        <td>:</td>
        <td><input type="text" name="mamahome_price" class="form-control" value="{{$info->totalgst}}"></td>
      </tr>
     
   </table>

   @endif
    <div class="warning" style="background-color: #ffffcc;
  border-left: 6px solid #ffeb3b;">
  <p style="font-weight:bold;">Any changes required or Details are empty please go to confirm orders page and search the order and chage the values !</p>
</div>
</div>
<div id="Page2{{$order->id}}" class="page" style="display:none">
    <h3 style="text-align:center;font-weight:bold;">Delivery Details</h3>
   <?php $data = App\DeliveryDetails::where('order_id',$orderid)->get()->first(); ?>
    @if(count($data) != 0)
     <table class="table">
         <tr>
          <td>Order Id</td>
          <td>:</td>
          <td><input type="text" name="orderid" class="form-control" value="{{$order->id}}"></td>
         </tr>
        
  <tr>
    <td>Truck Video</td>
    <td>:</td>
    <td>
      <video controls width="250">

    <source src="{{ $url}}/delivery_truckvideo/{{$data->truckvideo}}"
            type="video/webm">

    <source src="{{ $url}}/delivery_truckvideo/{{$data->truckvideo}}"
            type="video/mp4">

    Sorry, your browser doesn't support embedded videos.
</video>
 

      </td>
  </tr>
   <tr>
    <td>Truck  Image</td>
    <td>:</td>
    <td><?php
                                                     $images = explode(",", $data->truckimage );
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($images); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{ $url}}/delivery_truck/{{ $images[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor</td>
  </tr>
   <tr>
    <td>Total Amount</td>
    <td>:</td>
    <td>{{$data->totalamount}}</td>
  </tr>
  <tr>
    <td>Payment Method</td>
    <td>:</td>
    <td>
     <?php $s = explode(",", $data->payment_method); ?>
     @foreach($s as $pay)
      {{$pay}} <br>
      @endforeach
    </td>
  </tr>
  <tr>
    <td>Cash Amount</td>
    <td>:</td>
    <td>{{ $data->cashamount != null  ? $data->cashamount :'-' }}</td>
  </tr>
  <tr>
    <td>Cash Image</td>
    <td>:</td>
    <td>
      @if($data->cashimage != "N/A")
      <img height="150" width="150" id="project_img" src="{{ $url}}/delivery_cashimages/{{$data->cashimage}}" class="img img-thumbnail">
    @endif
</td>
  </tr>
   <tr>
    <td>RTGS Amount</td>
    <td>:</td>
    <td>{{ $data->rtgsamount != null  ? $data->rtgsamount :'-' }}</td>
  </tr>
  <tr>
    <th>RTGS Image</th>
    <td>:</td>
    <td>
       @if($data->rtgsimage != "N/A")
      <img height="150" width="150" id="project_img" src="{{ $url}}/delivery_rtgsimages/{{$data->rtgsimage}}" class="img img-thumbnail">
    @endif
</td>
  </tr>
   <tr>
    <td>Cash Amount</td>
    <td>:</td>
    <td>{{ $data->chequeamount != null  ? $data->chequeamount :'-' }}</td>
  </tr>
  <tr>
    <td>Cash Image</td>
    <td>:</td>
       @if($data->chequeimage != "N/A")
    <td><img height="150" width="150" id="project_img" src="{{ $url}}/delivery_chequeimages/{{$data->chequeimage}}" class="img img-thumbnail"></td>
    @endif
  </tr>
  <tr>
    <td>Location</td>
    <td>:</td>
    <td>{{$data->address}}
</td>
</tr> 
<tr>
    <td>Supplier Amount</td>
    <td>:</td>
    <td>{{$data->spamount}}
</td>
</tr> 
<tr>
 <td>Supplier invoice</td>
 <td>:</td>
 <td> <?php
                                               $images = explode(",", $data->spfile);
                                               ?>
                                             
                                             <div class="row">

                                                 @for($i = 0; $i < count($images); $i++)
                                                     <div class="col-md-3">
                                                          <img height="350" width="350" id="project_img" src="{{$url}}/delivery_spinvoice/{{ $images[$i] }}" class="img img-thumbnail">
                                                     </div>
                                                 @endfor
                                              </div>
                                            
                                            <br>
    </td> 
    </tr>  



     </table>
     @endif
      <div class="warning" style="background-color: #ffffcc;
  border-left: 6px solid #ffeb3b;">
  <p style="font-weight:bold;">Any changes required or Details are empty,Inform Logistic Co-ordinators to Add All details !</p>
</div>
</div>
<div id="Page3{{$order->id}}" class="page" style="display:none">
     <h3 style="text-align:center;font-weight:bold;">Supplier Details</h3>
   <?php $p = App\Supplierdetails::where('order_id',$order->id)->first(); 
         
   ?>
    @if(count($p) > 0)
     <table class="table">
        <tr>
            <td>Order Id</td>
            <td>:</td>
            <td><input type="text" name="orderid" class="form-control" value="{{$order->id}}"></td>
        </tr> 

        <tr>
            <td>Supplier Name</td>
            <td>:</td>
            <td><input type="text" name="spname" class="form-control" value="{{$p->supplier_name}}"></td>
        </tr>  

        <tr>
            <td>LPO Number</td>
            <td>:</td>
            <td><input type="text" name="lpo" class="form-control" value="{{$p->lpo}}"></td>
        </tr>  

        <tr>
            <td>Supplier Amount</td>
            <td>:</td>
            <td>
               <p style="background-color:#ffffcc;font-weight:bold;">LPO Amount is {{$p->totalamount}}</p>
              <input type="text" name="orderid" class="form-control" value="{{$order->spamount}}"></td>
        </tr>    

     </table>
     @endif
    <div class="warning" style="background-color: #ffffcc;
  border-left: 6px solid #ffeb3b;">
  <p style="font-weight:bold;">Any changes required or Details are empty,Change in Purchase order,Go to orders page and change the  details !</p>
</div>
     
</div>

          <div id="Page4{{$order->id}}" class="page" style="">
          <input type="hidden" name="orderId" value="{{$order->id}}">
          <input type="hidden" name="invoiceno" value="{{$invoice}}">
          <table class="table">
            <?php  $sub_customer_details= App\CustomerType::where('sub_customer_id','!=',null)->get();
                   $customer_details = App\CustomerType::where('sub_customer_id',null)->get();

        ?>
              <tr>
                <td>Customer Number</td>
                 <td>:</td>
                 <td><input type="text" name="number" class="form-control" onkeyup="getcustomerdeatials('{{$order->id}}')" id="number{{$order->id}}"></td>
               </tr>
               <tr>
                <td>Customer Id</td>
                 <td>:</td>
                 <td><input type="text" name="custid" class="form-control" id="cid{{$order->id}}" readonly></td>
               </tr>
               <tr>
                 <td>Customer Type</td>
                  <td>:</td>
                  <td><select  name="customertype" id="type{{$order->id}}" class="form-control input-sm"  >
                          
                                    <option value="">---Select  Type----</option>    
                                    @foreach($customer_details as $customer_detailsa) 
                                    
                                        <option value="{{$customer_detailsa->id}}"> {{$customer_detailsa->cust_type}}</option>
                                    @endforeach
                                </select>
                              </td>
                            </tr>
                            <tr>
                 <td>Sub Customer Type</td>
                  <td>:</td>
                  <td><select  name="sub_customertype" id="subtype{{$order->id}}" class="form-control input-sm"  >
                                <option value="">--Select--</option>
                             
                                @foreach($sub_customer_details as $sub_customer_detailss) 
                                                
                                <option value="{{$sub_customer_detailss->id}}"> {{$sub_customer_detailss->cust_type}}</option>
                            @endforeach
                              
                            </select>
                              </td>
                            </tr>
               <tr>
                <td>Customer Name</td>
                 <td>:</td>
                 <td><input type="text" name="custname" class="form-control" id="custname{{$order->id}}"></td>
               </tr>
               <tr>
                <td>Customer GST</td>
                 <td>:</td>
                 <td><input type="text" name="gstcust" class="form-control" id="gstcust{{$order->id}}"></td>
               </tr>
               
                <tr>
                <td>Add Other Numbers</td>
                 <td>:</td>
                 <td><input type="text" name="othernumber" class="form-control" placeholder=" Enter other numbers with (,)"></td>
               </tr>
          </table>
           <center><button class="btn btn-warning btn-sm" onclick="document.getElementById('cust').submit()">Get Customer</button></center>
        </div>
      </div>




        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  </form>
               @else

             <h5 style="color:black;background-color:#d2d211;">  {{$no}}</h5>

             @endif  


          </td>
        </tr>
          
                    
        @endforeach
    </table>
  
     <center>{{ $orders->appends(request()->query())->links()}} </center>   
</div>
</div>

</div>
</div>

<!-- ------------------------------------------------------------MR INvoice------------------------------------------------ -->
    @foreach($orders as $order)
        <!-- Modal -->
                    <div id="mrpayment{{$order->id}}" class="modal fade" role="dialog">
                      <div class="modal-dialog" style="width:50%">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header" style="background-color: #5cb85c;color:white">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Generate MR Invoice</h4>
                          </div>
                          <div class="modal-body">
                           <form action="{{ URL::to('/') }}/savemrunitprice?id={{$order->id}}&&manu_id={{$order->manu_id}}" method="post" id="sendMessage">
                            {{ csrf_field() }}
                           <!--  <input class="hidden" type="text" name="dtow1" id="dtow1{{$order->id}}" value="">
                            <input type="hidden" name="dtow2" id="dtow2{{$order->id}}" value="">
                            <input type="hidden" name="dtow3" id="dtow3{{$order->id}}" value="">
                            <input type="hidden" name="dtow4" id="dtow4{{$order->id}}" value=""> -->
                                <?php $mrprice = App\MRInvoice::where('order_id',$order->id)->get(); 

                                   $mmmm = App\MRSupplierdetails::where('order_id',$order->id)->pluck('unit_price')->first(); 

                                ?>
                                @if(count($mrprice) == 0)
                             @foreach($mamaprices as $price )  
                            @if($price->order_id == $order->id)
                           <table class="table table-responsive table-striped" border="1">
                            <input  type="hidden" name="g1" id="g11{{$order->id}}" value="{{$price->cgstpercent}}">
                            <input type="hidden" name="g2" id="g21{{$order->id}}" value="{{$price->sgstpercent}}">
                            <input type="hidden" name="g3" id="g31{{$order->id}}" value="{{$price->gstpercent}}">
                            <input type="hidden" name="i1" id="i11{{$order->id}}" value="{{$price->igstpercent}}">
                            
                           
                           <tr>
                            <?php 
                                     $rec =count($order->confirm_payment); 
                             ?>  
                              <td>Description of Goods : </td>
                             @if($rec == 0)
                                  <td><input required type="text" name="desc" class="form-control" value="{{ $order->main_category }}" id="category21{{$order->id}}"></td>
                             @else
                                  <td><input required type="text" name="desc" class="form-control" value="{{ $order->main_category }}" id="category21{{$order->id}}"></td>
                                  @endif
                           </tr>
                           <tr>
    <td>Select State</td>
    <td>
      <select id="state1{{$order->id}}"  class="form-control" name="state" onclick="getgst1('{{$order->id}}')">
          <option value="">----Select----</option>
          <option value="1">Karnataka</option>
          <option value="2">AndraPradesh</option>
          <option value="3">TamilNadu</option>

      </select>
     </td>
</tr>
                              <?php 
                                     $rec =count($order->confirm_payment); 
                             ?> 
                         @if($rec == 0)
                                   @foreach($reqs as $req)
                                    @if($req->id == $order->req_id)
                                          <tr>
                                              <td>Billing Address : </td> 
                                              <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5">{{$req->billadress}}</textarea></td>
                                          </tr>
                                          <tr>
                                              <td>Shipping Address : </td> 
                                               @if($order-> project_id == null)
                                               <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">{{$req->req != null ? $req->req->ship : ''}}</textarea></td>
                                               @else
                                              <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">{{$req->siteaddress != null ? $req->siteaddress->address : ''}}</textarea></td>
                                              @endif
                                          </tr>
                                        @endif
                                       @endforeach  
                                       <tr>
                                         <td>Customer Gst : </td>
                                         <td>
                                          <?php
                                          $num = App\ProcurementDetails::where('project_id',$order->project_id)->pluck('procurement_contact_no')->first();
                                         if($num == null){
                                           $num = App\Mprocurement_Details::where('manu_id',$order->manu_id)->pluck('contact')->first();
                                         }
                                          $gst = App\CustomerGst::where('customer_phonenumber',$num)->pluck('customer_gst')->first();
                                          ?>
                                          <input type="text" value="{{$gst}}" name="customergst" class="form-control">
                                          </td>
                                       </tr>
                                       <tr>
                                  <td>Customer Name</td>
                                 
                                  <td><input type="text" name="customer_name" required class="form-control"></td>
                                </tr>
                                        <tr>
                                         <td>HSN</td>
                                         <td>
                                          <?php $hsn = App\Category::where('category_name',$order->main_category)->pluck('HSN')->first(); ?>
                                          <input type="text" value="{{$hsn}}" name="HSN" class="form-control">
                                          </td>
                                       </tr>
                                      <tr>
                                         <td>Payment Method</td>
                                         <td>
                                          <label class="checkbox-inline">
                                              <input style="width: 33px;" id="planning" type="checkbox"  name="status[]" value="CASH"><span>&nbsp;&nbsp;&nbsp;</span>CASH
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="digging" type="checkbox"  name="status[]" value="RTGS">RTGS
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" type="checkbox"  name="status[]" value="CHEQUE">CHEQUE
                                            </label>
                                          </td>
                                       </tr>
                         @else
                                       <tr>
                                         <td>Payment Method</td>
                                         <td>
                                           
                                          <?php  
                                                      $statuses = explode(",",$price->payment_mode);
                                                      
                                                      
                                                  ?>
                                              
                                          <label class="checkbox-inline">

                                              <input  {{ in_array('CASH', $statuses) ? 'checked': ''}} style="width: 33px;" id="planning" type="checkbox"  name="status[]" value="CASH"><span>&nbsp;&nbsp;&nbsp;</span>CASH
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input  {{ in_array('RTGS', $statuses) ? 'checked': ''}} id="digging" type="checkbox"  name="status[]" value="RTGS">RTGS
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" {{ in_array('CHEQUE', $statuses) ? 'checked': ''}} type="checkbox"  name="status[]" value="CHEQUE">CHEQUE
                                            </label>
                                          </td>
                                </tr>
                              <tr>
                                  <td>Billing Address : </td> 
                                  <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5">{{$price->billaddress}}</textarea></td>
                              </tr>
                              <tr>
                                  <td>Shipping Address : </td> 
                                  <td><textarea required type="text" name="ship" class="form-control"  style="resize: none;" rows="5">{{$price->shipaddress}}</textarea></td>
                              </tr>
                         @endif 
                        
                           <tr>
                             <td>Total Quantity : </td>
                             <td><input required type="number" class="form-control" name="quantity" value="{{$price->quantity}}" id="quan1{{$order->id}}"></td>
                           </tr>
                            <tr>
                              <td>Unit of Measurement : </td>
                              <td><input  type="text" name="unit" value="{{$price->unit}}" class="form-control" >
                           
                            </tr>
                            <tr>
                              <td>Price(Per Unit) : </td>
                                
                              <td> <label>MR Price : {{$mmmm}}</label><input required type="text" id="unit1{{$order->id}}"  class="form-control" name="price" value=""  onkeyup="getcalculation1('{{$order->id}}')"></td>
                            </tr>  
                            <tr>
                                        <td>Unit Price without GST :</td>
                                        <td>&nbsp;&nbsp;&nbsp;RS.<label class=" alert-success pull-left" id="withoutgst1{{$order->id}}"></label>/-
                                            <input readonly id="withoutgst11{{$order->id}}" type="text" name="unitwithoutgst"  value="">
                                       </td>
                              </tr>
                                    <tr>
                                        <td>Total Amount : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="display1{{$order->id}}"></label>/-
                                              <input readonly id="amount1{{$order->id}}" type="text" name="tamount" value="">
                                              <label class=" alert-success pull-right" id="lblWord1{{$order->id}}"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="c101{{$order->id}}">CGST({{$price->cgstpercent}}%) : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;CGST <label class=" alert-success pull-left" id="cgst1{{$order->id}}"></label>/-
                                              <input readonly  id="cgst11{{$order->id}}" type="text" name="cgst" value="">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="s101{{$order->id}}">SGST({{$price->sgstpercent}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;SGST <label class=" alert-success pull-left" id="sgst1{{$order->id}}"></label>/-
                                             <input readonly  id="sgst11{{$order->id}}" type="text" name="sgst" value="">
                                        </td>
                                    </tr>
                                    <tr>
                                      <td>Total Tax :</td>
                                      <td>&nbsp;&nbsp;&nbsp;<label class=" alert-success pull-left" id="totaltax1{{$order->id}}"></label>Total
                                        <input readonly id="totaltax11{{$order->id}}" type="text" name="totaltax" value="">
                                        <label class=" alert-success pull-right" id="lblWord11{{$order->id}}"></label>
                                      </td>
                                    </tr>
                                    <tr>
                                        <td id="i101{{$order->id}}">IGST({{$price->igstpercent}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;IGST<label class=" alert-success pull-left" id="igst1{{$order->id}}"></label>/-
                                             <input readonly  id="igst11{{$order->id}}" type="text" name="igst" value="">
                                             <label class=" alert-success pull-right" id="lblWord31{{$order->id}}"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount (Including GST) :</td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withgst1{{$order->id}}"></label> /-
                                              <input readonly id="amountwithgst1{{$order->id}}" type="text" name="gstamount" value="">
                                              <label class=" alert-success pull-right" id="lblWord21{{$order->id}}"></label>
                                        </td>
                                    </tr>
				    <tr>
                                      <td>Sales Person Name :</td>
                                      <td><input type="text" name="sales_person" id="sales_person"></td>
                                    </tr>
                            </table>
                            <center>
                            <button class="btn btn-sm btn-success" style="text-align: center;" onclick="document.getElementById('sendMessage').submit()">Confirm</button></center>
                            @endif
                            @endforeach

                            @else

                             @foreach($mrprice as $price )  
                           <table class="table table-responsive table-striped" border="1">
                            <input  type="hidden" name="g1" id="g11{{$order->id}}" value="{{$price->cgstpercent}}">
                            <input type="hidden" name="g2" id="g21{{$order->id}}" value="{{$price->sgstpercent}}">
                            <input type="hidden" name="g3" id="g31{{$order->id}}" value="{{$price->gstpercent}}">
                            <input type="hidden" name="i1" id="i11{{$order->id}}" value="{{$price->igstpercent}}">
                            
                           
                           <tr>
                            <?php 
                                     $rec =count($order->confirm_payment); 
                             ?>  
                              <td>Description of Goods : </td>
                             @if($rec == 0)
                                  <td><input required type="text" name="desc" class="form-control" value="{{ $order->main_category }}" id="category21{{$order->id}}"></td>
                             @else
                                  <td><input required type="text" name="desc" class="form-control" value="{{ $order->main_category }}" id="category21{{$order->id}}"></td>
                                  @endif
                           </tr>
                           <tr>
    <td>Select State</td>
    <td>
      <select id="state1{{$order->id}}"  class="form-control" name="state" onclick="getgst1('{{$order->id}}')">
          <option value="">----Select----</option>
          <option {{ $price->state == 1 ? 'selected' : ''}} value="1" >Karnataka</option>
          <option {{ $price->state == 2 ? 'selected' : ''}} value="2">AndraPradesh</option>
          <option {{ $price->state == 3 ? 'selected' : ''}} value="3">TamilNadu</option>

      </select>
     </td>
</tr>
                              <?php 
                                     $rec =count($order->confirm_payment); 
                             ?> 
                         @if($rec == 0)
                                   @foreach($reqs as $req)
                                    @if($req->id == $order->req_id)
                                          <tr>
                                              <td>Billing Address : </td> 
                                              <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5">{{$price->billaddress}}</textarea></td>
                                          </tr>
                                          <tr>
                                              <td>Shipping Address : </td> 
                                            
                                              <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">{{$price->shipaddress }}</textarea></td>
                                             
                                          </tr>
                                        @endif
                                       @endforeach  
                                       <tr>
                                         <td>Customer Gst : </td>
                                         <td>
                                          <?php
                                          $num = App\ProcurementDetails::where('project_id',$order->project_id)->pluck('procurement_contact_no')->first();
                                         if($num == null){
                                           $num = App\Mprocurement_Details::where('manu_id',$order->manu_id)->pluck('contact')->first();
                                         }
                                          $gst = App\CustomerGst::where('customer_phonenumber',$num)->pluck('customer_gst')->first();
                                          ?>
                                          <input type="text" value="{{$price->customer_gst}}" name="customergst" class="form-control">
                                          </td>
                                       </tr>
                                       <tr>
                                      <td>Customer Name</td>
                                    
                                      <td><input type="text" name="customer_name"  required class="form-control"></td>
                                    </tr>
                                        <tr>
                                         <td>HSN</td>
                                         <td>
                                          <?php $hsn = App\Category::where('category_name',$order->main_category)->pluck('HSN')->first(); ?>
                                          <input type="text" value="{{$hsn}}" name="HSN" class="form-control">
                                          </td>
                                       </tr>
                                      <tr>
                                         <td>Payment Method</td>
                                         <td>
                                          <label class="checkbox-inline">
                                              <input style="width: 33px;" id="planning" type="checkbox"  name="status[]" value="CASH"><span>&nbsp;&nbsp;&nbsp;</span>CASH
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="digging" type="checkbox"  name="status[]" value="RTGS">RTGS
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" type="checkbox"  name="status[]" value="CHEQUE">CHEQUE
                                            </label>
                                          </td>
                                       </tr>
                         @else
                                       <tr>
                                         <td>Payment Method</td>
                                         <td>
                                           
                                          <?php  
                                                      $statuses = explode(",",$price->payment_mode);
                                                      
                                                      
                                                  ?>
                                              
                                          <label class="checkbox-inline">

                                              <input  {{ in_array('CASH', $statuses) ? 'checked': ''}} style="width: 33px;" id="planning" type="checkbox"  name="status[]" value="CASH"><span>&nbsp;&nbsp;&nbsp;</span>CASH
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input  {{ in_array('RTGS', $statuses) ? 'checked': ''}} id="digging" type="checkbox"  name="status[]" value="RTGS">RTGS
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" {{ in_array('CHEQUE', $statuses) ? 'checked': ''}} type="checkbox"  name="status[]" value="CHEQUE">CHEQUE
                                            </label>
                                          </td>
                                </tr>
                              <tr>
                                  <td>Billing Address : </td> 
                                  <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5">{{$price->billaddress}}</textarea></td>
                              </tr>
                              <tr>
                                  <td>Shipping Address : </td> 
                                  <td><textarea required type="text" name="ship" class="form-control"  style="resize: none;" rows="5">{{$price->shipaddress}}</textarea></td>
                              </tr>
                         @endif
                        
                           <tr>
                             <td>Total Quantity : </td>
                             <td><input required type="number" class="form-control" name="quantity" value="{{$price->quantity}}" id="quan1{{$order->id}}"></td>
                           </tr>
                            <tr>
                              <td>Unit of Measurement : </td>
                              <td><input  type="text" name="unit" value="{{$price->unit}}" class="form-control" >
                           
                            </tr>
                            <tr>
                              <td>Price(Per Unit) : </td>
                              <td><input required type="text" id="unit1{{$order->id}}"  class="form-control" name="price" value="{{$price->mamahome_price}}"  onkeyup="getcalculation1('{{$order->id}}')"></td>
                            </tr>  
                            <tr>
                                        <td>Unit Price without GST :</td>
                                        <td>&nbsp;&nbsp;&nbsp;RS.<label class=" alert-success pull-left" id="withoutgst1{{$order->id}}"></label>/-
                                            <input readonly id="withoutgst11{{$order->id}}" type="text" name="unitwithoutgst"  value="{{$price->unitwithoutgst}}">
                                       </td>
                              </tr>
                                    <tr>
                                        <td>Total Amount : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="display1{{$order->id}}"></label>/-
                                              <input readonly id="amount1{{$order->id}}" type="text" name="tamount" value="{{$price->totalamount}}">
                                              <label class=" alert-success pull-right" id="lblWord1{{$order->id}}"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="c101{{$order->id}}">CGST({{$price->cgstpercent}}%) : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;CGST <label class=" alert-success pull-left" id="cgst1{{$order->id}}"></label>/-
                                              <input readonly  id="cgst11{{$order->id}}" type="text" name="cgst" value="{{$price->cgst}}">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="s101{{$order->id}}">SGST({{$price->sgstpercent}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;SGST <label class=" alert-success pull-left" id="sgst1{{$order->id}}"></label>/-
                                             <input readonly  id="sgst11{{$order->id}}" type="text" name="sgst" value="{{$price->sgst}}">
                                        </td>
                                    </tr>
                                    <tr>
                                      <td>Total Tax :</td>
                                      <td>&nbsp;&nbsp;&nbsp;<label class=" alert-success pull-left" id="totaltax1{{$order->id}}"></label>Total
                                        <input readonly id="totaltax11{{$order->id}}" type="text" name="totaltax" value="{{$price->totaltax}}">
                                        <label class=" alert-success pull-right" id="lblWord11{{$order->id}}"></label>
                                      </td>
                                    </tr>
                                    <tr>
                                        <td id="i101{{$order->id}}">IGST({{$price->igstpercent}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;IGST<label class=" alert-success pull-left" id="igst1{{$order->id}}"></label>/-
                                             <input readonly  id="igst11{{$order->id}}" type="text" name="igst" value="{{$price->igst}}">
                                             <label class=" alert-success pull-right" id="lblWord31{{$order->id}}"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount (Including GST) :</td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withgst1{{$order->id}}"></label> /-
                                              <input readonly id="amountwithgst1{{$order->id}}" type="text" name="gstamount" value="{{$price->amountwithgst}}">
                                              <label class=" alert-success pull-right" id="lblWord21{{$order->id}}"></label>
                                        </td>
                                    </tr>
				    <tr>
                                      <td>Sales Person Name :</td>
                                      <td><input type="text" name="sales_person" id="sales_person"></td>
                                    </tr>
                            </table>
                            <center>
                            <button class="btn btn-sm btn-success" style="text-align: center;" onclick="document.getElementById('sendMessage').submit()">Confirm</button></center>
                          
                            @endforeach
                            @endif
                           </form>
                          </div>
                          <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
@endforeach

<script type="text/javascript">
  function getgst1(arg){
var data=arg; 
var cat = document.getElementById('category21'+arg).value;
var state = document.getElementById('state1'+arg);
var st = state.options[state.selectedIndex].value;
    $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getgstvalue12",
            async:false,
            data:{cat : cat, state : st},
            success: function(response)
            {
                  

                console.log(response[0]);

                setval1(response[0],data);
             }
       });   

  

 }
function setval1(arg,data){
      var arg = arg;
      var gstpercent = arg['gstpercent'];
      var cgst = arg['cgst'];
      var sgst = arg['sgst'];
      var igst = arg['igst'];
                 


               document.getElementById('g31'+data).value = gstpercent;
               document.getElementById('g11'+data).value = cgst;
               document.getElementById('g21'+data).value = sgst;
               document.getElementById('i11'+data).value = igst;
               if(igst == null){
                   document.getElementById('i101'+data).innerHTML ="IGST(0)%";

             }else{

                   document.getElementById('i101'+data).innerHTML ="IGST("+igst+")%";
             }
               if(cgst == null){
                     document.getElementById('c101'+data).innerHTML ="CGST(0)%";

               }else{
                    document.getElementById('c101'+data).innerHTML ="CGST("+cgst+")%";
               }
                 if(sgst == null){

                     document.getElementById('s101'+data).innerHTML ="SGST(0)%";
                 }else{
                  document.getElementById('s101'+data).innerHTML ="SGST("+sgst+")%";

                 }
                
}



function getcalculation1(arg){
var x =document.getElementById('unit1'+arg).value;
var y = document.getElementById('quan1'+arg).value;
var g1 = document.getElementById('g11'+arg).value;
var g2 = document.getElementById('g21'+arg).value;
var g3 = document.getElementById('g31'+arg).value;
var g4 = document.getElementById('g11'+arg).value;
var g5 = document.getElementById('g21'+arg).value;
var i1 = document.getElementById('i11'+arg).value;
var i2 = document.getElementById('i11'+arg).value;
var withoutgst = (x /g3);
var t = (withoutgst * y);
var f = Math.round(t);
var gst = (t * g1)/100;
var sgt = (t * g2)/100;
var igst = (t * i1)/100;
var gst1 = (t * g4)/100;
var sgt1 = (t * g5)/100;
var ig = (t * i2)/100;
var igst1 = Math.round(ig);
var withgst = (gst + sgt + t + igst);
var final = Math.round(withgst);
var tt = (gst + sgt);

var totaltax = Math.round(tt);
document.getElementById('display1'+arg).innerHTML = t;
document.getElementById('cgst1'+arg).innerHTML = gst;
document.getElementById('sgst1'+arg).innerHTML = sgt;
document.getElementById('igst1'+arg).innerHTML = igst;
document.getElementById('withgst1'+arg).innerHTML = withgst;
document.getElementById('withoutgst1'+arg).innerHTML = withoutgst;
document.getElementById('withoutgst11'+arg).value = withoutgst;
document.getElementById('amount1'+arg).value = f;
document.getElementById('cgst11'+arg).value = gst1;
document.getElementById('sgst11'+arg).value = sgt1;
document.getElementById('igst11'+arg).value = igst1;
document.getElementById('totaltax1'+arg).innerHTML = tt;
document.getElementById('totaltax11'+arg).value = totaltax;
document.getElementById('amountwithgst1'+arg).value = final;
}


</script>




<!-- ---------------------------------------------------------MR Invoice end-------------------------------------------------- -->
 


    @foreach($orders as $order)
        <!-- Modal -->
                    <div id="mrpayment{{$order->id}}" class="modal fade" role="dialog">
                      <div class="modal-dialog" style="width:50%">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header" style="background-color: #5cb85c;color:white">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Generate MR Invoice</h4>
                          </div>
                          <div class="modal-body">
                           <form action="{{ URL::to('/') }}/savemrunitprice?id={{$order->id}}&&manu_id={{$order->manu_id}}" method="post" id="sendMessage">
                            {{ csrf_field() }}
                           <!--  <input class="hidden" type="text" name="dtow1" id="dtow1{{$order->id}}" value="">
                            <input type="hidden" name="dtow2" id="dtow2{{$order->id}}" value="">
                            <input type="hidden" name="dtow3" id="dtow3{{$order->id}}" value="">
                            <input type="hidden" name="dtow4" id="dtow4{{$order->id}}" value=""> -->
                                <?php $mrprice = App\MRInvoice::where('order_id',$order->id)->get(); 

                                   $mmmm = App\MRSupplierdetails::where('order_id',$order->id)->pluck('unit_price')->first(); 

                                ?>
                                @if(count($mrprice) == 0)
                             @foreach($mamaprices as $price )  
                            @if($price->order_id == $order->id)
                           <table class="table table-responsive table-striped" border="1">
                            <input  type="hidden" name="g1" id="g11{{$order->id}}" value="{{$price->cgstpercent}}">
                            <input type="hidden" name="g2" id="g21{{$order->id}}" value="{{$price->sgstpercent}}">
                            <input type="hidden" name="g3" id="g31{{$order->id}}" value="{{$price->gstpercent}}">
                            <input type="hidden" name="i1" id="i11{{$order->id}}" value="{{$price->igstpercent}}">
                            
                           
                           <tr>
                            <?php 
                                     $rec =count($order->confirm_payment); 
                             ?>  
                              <td>Description of Goods : </td>
                             @if($rec == 0)
                                  <td><input required type="text" name="desc" class="form-control" value="{{ $order->main_category }}" id="category21{{$order->id}}"></td>
                             @else
                                  <td><input required type="text" name="desc" class="form-control" value="{{ $order->main_category }}" id="category21{{$order->id}}"></td>
                                  @endif
                           </tr>
                           <tr>
    <td>Select State</td>
    <td>
      <select id="state1{{$order->id}}"  class="form-control" name="state" onclick="getgst1('{{$order->id}}')">
          <option value="">----Select----</option>
          <option value="1">Karnataka</option>
          <option value="2">AndraPradesh</option>
          <option value="3">TamilNadu</option>

      </select>
     </td>
</tr>
                              <?php 
                                     $rec =count($order->confirm_payment); 
                             ?> 
                         @if($rec == 0)
                                   @foreach($reqs as $req)
                                    @if($req->id == $order->req_id)
                                          <tr>
                                              <td>Billing Address : </td> 
                                              <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5">{{$req->billadress}}</textarea></td>
                                          </tr>
                                          <tr>
                                              <td>Shipping Address : </td> 
                                               @if($order-> project_id == null)
                                               <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">{{$req->manu != null ? $req->manu->address : ''}}</textarea></td>
                                               @else
                                              <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">{{$req->siteaddress != null ? $req->siteaddress->address : ''}}</textarea></td>
                                              @endif
                                          </tr>
                                        @endif
                                       @endforeach  
                                       <tr>
                                         <td>Customer Gst : </td>
                                         <td>
                                          <?php
                                          $num = App\ProcurementDetails::where('project_id',$order->project_id)->pluck('procurement_contact_no')->first();
                                         if($num == null){
                                           $num = App\Mprocurement_Details::where('manu_id',$order->manu_id)->pluck('contact')->first();
                                         }
                                          $gst = App\CustomerGst::where('customer_phonenumber',$num)->pluck('customer_gst')->first();
                                          ?>
                                          <input type="text" value="{{$gst}}" name="customergst" class="form-control">
                                          </td>
                                       </tr>
                                       <tr>
                                    <td>Customer Name</td>
                                  
                                    <td><input type="text" name="customer_name" required  class="form-control"></td>
                                  </tr>
                                        <tr>
                                         <td>HSN</td>
                                         <td>
                                          <?php $hsn = App\Category::where('category_name',$order->main_category)->pluck('HSN')->first(); ?>
                                          <input type="text" value="{{$hsn}}" name="HSN" class="form-control">
                                          </td>
                                       </tr>
                                      <tr>
                                         <td>Payment Method</td>
                                         <td>
                                          <label class="checkbox-inline">
                                              <input style="width: 33px;" id="planning" type="checkbox"  name="status[]" value="CASH"><span>&nbsp;&nbsp;&nbsp;</span>CASH
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="digging" type="checkbox"  name="status[]" value="RTGS">RTGS
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" type="checkbox"  name="status[]" value="CHEQUE">CHEQUE
                                            </label>
                                          </td>
                                       </tr>
                         @else
                                       <tr>
                                         <td>Payment Method</td>
                                         <td>
                                           
                                          <?php  
                                                      $statuses = explode(",",$price->payment_mode);
                                                      
                                                      
                                                  ?>
                                              
                                          <label class="checkbox-inline">

                                              <input  {{ in_array('CASH', $statuses) ? 'checked': ''}} style="width: 33px;" id="planning" type="checkbox"  name="status[]" value="CASH"><span>&nbsp;&nbsp;&nbsp;</span>CASH
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input  {{ in_array('RTGS', $statuses) ? 'checked': ''}} id="digging" type="checkbox"  name="status[]" value="RTGS">RTGS
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" {{ in_array('CHEQUE', $statuses) ? 'checked': ''}} type="checkbox"  name="status[]" value="CHEQUE">CHEQUE
                                            </label>
                                          </td>
                                </tr>
                              <tr>
                                  <td>Billing Address : </td> 
                                  <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5">{{$price->billaddress}}</textarea></td>
                              </tr>
                              <tr>
                                  <td>Shipping Address : </td> 
                                  <td><textarea required type="text" name="ship" class="form-control"  style="resize: none;" rows="5">{{$price->shipaddress}}</textarea></td>
                              </tr>
                         @endif 
                        
                           <tr>
                             <td>Total Quantity : </td>
                             <td><input required type="text" class="form-control" name="quantity" value="{{$price->quantity}}" id="quan1{{$order->id}}"></td>
                           </tr>
                            <tr>
                              <td>Unit of Measurement : </td>
                              <td><input  type="text" name="unit" value="{{$price->unit}}" class="form-control" >
                           
                            </tr>
                            <tr>
                              <td>Price(Per Unit) : </td>
                                
                              <td> <label>MR Price : {{$mmmm}}</label><input required type="text" id="unit1{{$order->id}}"  class="form-control" name="price" value=""  onkeyup="getcalculation1('{{$order->id}}')"></td>
                            </tr>  
                            <tr>
                                        <td>Unit Price without GST :</td>
                                        <td>&nbsp;&nbsp;&nbsp;RS.<label class=" alert-success pull-left" id="withoutgst1{{$order->id}}"></label>/-
                                            <input readonly id="withoutgst11{{$order->id}}" type="text" name="unitwithoutgst"  value="">
                                       </td>
                              </tr>
                                    <tr>
                                        <td>Total Amount : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="display1{{$order->id}}"></label>/-
                                              <input readonly id="amount1{{$order->id}}" type="text" name="tamount" value="">
                                              <label class=" alert-success pull-right" id="lblWord1{{$order->id}}"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="c101{{$order->id}}">CGST({{$price->cgstpercent}}%) : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;CGST <label class=" alert-success pull-left" id="cgst1{{$order->id}}"></label>/-
                                              <input readonly  id="cgst11{{$order->id}}" type="text" name="cgst" value="">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="s101{{$order->id}}">SGST({{$price->sgstpercent}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;SGST <label class=" alert-success pull-left" id="sgst1{{$order->id}}"></label>/-
                                             <input readonly  id="sgst11{{$order->id}}" type="text" name="sgst" value="">
                                        </td>
                                    </tr>
                                    <tr>
                                      <td>Total Tax :</td>
                                      <td>&nbsp;&nbsp;&nbsp;<label class=" alert-success pull-left" id="totaltax1{{$order->id}}"></label>Total
                                        <input readonly id="totaltax11{{$order->id}}" type="text" name="totaltax" value="">
                                        <label class=" alert-success pull-right" id="lblWord11{{$order->id}}"></label>
                                      </td>
                                    </tr>
                                    <tr>
                                        <td id="i101{{$order->id}}">IGST({{$price->igstpercent}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;IGST<label class=" alert-success pull-left" id="igst1{{$order->id}}"></label>/-
                                             <input readonly  id="igst11{{$order->id}}" type="text" name="igst" value="">
                                             <label class=" alert-success pull-right" id="lblWord31{{$order->id}}"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount (Including GST) :</td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withgst1{{$order->id}}"></label> /-
                                              <input readonly id="amountwithgst1{{$order->id}}" type="text" name="gstamount" value="">
                                              <label class=" alert-success pull-right" id="lblWord21{{$order->id}}"></label>
                                        </td>
                                    </tr>
				    <tr>
                                      <td>Sales Person Name :</td>
                                      <td><input type="text" name="sales_person" id="sales_person"></td>
                                    </tr>
                            </table>
                            <center>
                            <button class="btn btn-sm btn-success" style="text-align: center;" onclick="document.getElementById('sendMessage').submit()">Confirm</button></center>
                            @endif
                            @endforeach

                            @else

                             @foreach($mrprice as $price )  
                           <table class="table table-responsive table-striped" border="1">
                            <input  type="hidden" name="g1" id="g11{{$order->id}}" value="{{$price->cgstpercent}}">
                            <input type="hidden" name="g2" id="g21{{$order->id}}" value="{{$price->sgstpercent}}">
                            <input type="hidden" name="g3" id="g31{{$order->id}}" value="{{$price->gstpercent}}">
                            <input type="hidden" name="i1" id="i11{{$order->id}}" value="{{$price->igstpercent}}">
                            
                           
                           <tr>
                            <?php 
                                     $rec =count($order->confirm_payment); 
                             ?>  
                              <td>Description of Goods : </td>
                             @if($rec == 0)
                                  <td><input required type="text" name="desc" class="form-control" value="{{ $order->main_category }}" id="category21{{$order->id}}"></td>
                             @else
                                  <td><input required type="text" name="desc" class="form-control" value="{{ $order->main_category }}" id="category21{{$order->id}}"></td>
                                  @endif
                           </tr>
                           <tr>
    <td>Select State</td>
    <td>
      <select id="state1{{$order->id}}"  class="form-control" name="state" onclick="getgst1('{{$order->id}}')">
          <option value="">----Select----</option>
          <option {{ $price->state == 1 ? 'selected' : ''}} value="1" >Karnataka</option>
          <option {{ $price->state == 2 ? 'selected' : ''}} value="2">AndraPradesh</option>
          <option {{ $price->state == 3 ? 'selected' : ''}} value="3">TamilNadu</option>

      </select>
     </td>
</tr>
                              <?php 
                                     $rec =count($order->confirm_payment); 
                             ?> 
                         @if($rec == 0)
                                   @foreach($reqs as $req)
                                    @if($req->id == $order->req_id)
                                          <tr>
                                              <td>Billing Address : </td> 
                                              <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5">{{$price->billaddress}}</textarea></td>
                                          </tr>
                                          <tr>
                                              <td>Shipping Address : </td> 
                                            
                                              <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">{{$price->shipaddress }}</textarea></td>
                                             
                                          </tr>
                                        @endif
                                       @endforeach  
                                       <tr>
                                         <td>Customer Gst : </td>
                                         <td>
                                          <?php
                                          $num = App\ProcurementDetails::where('project_id',$order->project_id)->pluck('procurement_contact_no')->first();
                                         if($num == null){
                                           $num = App\Mprocurement_Details::where('manu_id',$order->manu_id)->pluck('contact')->first();
                                         }
                                          $gst = App\CustomerGst::where('customer_phonenumber',$num)->pluck('customer_gst')->first();
                                          ?>
                                          <input type="text" value="{{$price->customer_gst}}" name="customergst" class="form-control">
                                          </td>
                                       </tr>
                                       <tr>
                                      <td>Customer Name</td>
                                   
                                      <td><input type="text" name="customer_name" required class="form-control"></td>
                                    </tr>
                                        <tr>
                                         <td>HSN</td>
                                         <td>
                                          <?php $hsn = App\Category::where('category_name',$order->main_category)->pluck('HSN')->first(); ?>
                                          <input type="text" value="{{$hsn}}" name="HSN" class="form-control">
                                          </td>
                                       </tr>
                                      <tr>
                                         <td>Payment Method</td>
                                         <td>
                                          <label class="checkbox-inline">
                                              <input style="width: 33px;" id="planning" type="checkbox"  name="status[]" value="CASH"><span>&nbsp;&nbsp;&nbsp;</span>CASH
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="digging" type="checkbox"  name="status[]" value="RTGS">RTGS
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" type="checkbox"  name="status[]" value="CHEQUE">CHEQUE
                                            </label>
                                          </td>
                                       </tr>
                         @else
                                       <tr>
                                         <td>Payment Method</td>
                                         <td>
                                           
                                          <?php  
                                                      $statuses = explode(",",$price->payment_mode);
                                                      
                                                      
                                                  ?>
                                              
                                          <label class="checkbox-inline">

                                              <input  {{ in_array('CASH', $statuses) ? 'checked': ''}} style="width: 33px;" id="planning" type="checkbox"  name="status[]" value="CASH"><span>&nbsp;&nbsp;&nbsp;</span>CASH
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input  {{ in_array('RTGS', $statuses) ? 'checked': ''}} id="digging" type="checkbox"  name="status[]" value="RTGS">RTGS
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" {{ in_array('CHEQUE', $statuses) ? 'checked': ''}} type="checkbox"  name="status[]" value="CHEQUE">CHEQUE
                                            </label>
                                          </td>
                                </tr>
                              <tr>
                                  <td>Billing Address : </td> 
                                  <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5">{{$price->billaddress}}</textarea></td>
                              </tr>
                              <tr>
                                  <td>Shipping Address : </td> 
                                  <td><textarea required type="text" name="ship" class="form-control"  style="resize: none;" rows="5">{{$price->shipaddress}}</textarea></td>
                              </tr>
                         @endif
                        
                           <tr>
                             <td>Total Quantity : </td>
                             <td><input required type="text" class="form-control" name="quantity" value="{{$price->quantity}}" id="quan1{{$order->id}}"></td>
                           </tr>
                            <tr>
                              <td>Unit of Measurement : </td>
                              <td><input  type="text" name="unit" value="{{$price->unit}}" class="form-control" >
                           
                            </tr>
                            <tr>
                              <td>Price(Per Unit) : </td>
                              <td><input required type="text" id="unit1{{$order->id}}"  class="form-control" name="price" value="{{$price->mamahome_price}}"  onkeyup="getcalculation1('{{$order->id}}')"></td>
                            </tr>  
                            <tr>
                                        <td>Unit Price without GST :</td>
                                        <td>&nbsp;&nbsp;&nbsp;RS.<label class=" alert-success pull-left" id="withoutgst1{{$order->id}}"></label>/-
                                            <input readonly id="withoutgst11{{$order->id}}" type="text" name="unitwithoutgst"  value="{{$price->unitwithoutgst}}">
                                       </td>
                              </tr>
                                    <tr>
                                        <td>Total Amount : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="display1{{$order->id}}"></label>/-
                                              <input readonly id="amount1{{$order->id}}" type="text" name="tamount" value="{{$price->totalamount}}">
                                              <label class=" alert-success pull-right" id="lblWord1{{$order->id}}"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="c101{{$order->id}}">CGST({{$price->cgstpercent}}%) : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;CGST <label class=" alert-success pull-left" id="cgst1{{$order->id}}"></label>/-
                                              <input readonly  id="cgst11{{$order->id}}" type="text" name="cgst" value="{{$price->cgst}}">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="s101{{$order->id}}">SGST({{$price->sgstpercent}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;SGST <label class=" alert-success pull-left" id="sgst1{{$order->id}}"></label>/-
                                             <input readonly  id="sgst11{{$order->id}}" type="text" name="sgst" value="{{$price->sgst}}">
                                        </td>
                                    </tr>
                                    <tr>
                                      <td>Total Tax :</td>
                                      <td>&nbsp;&nbsp;&nbsp;<label class=" alert-success pull-left" id="totaltax1{{$order->id}}"></label>Total
                                        <input readonly id="totaltax11{{$order->id}}" type="text" name="totaltax" value="{{$price->totaltax}}">
                                        <label class=" alert-success pull-right" id="lblWord11{{$order->id}}"></label>
                                      </td>
                                    </tr>
                                    <tr>
                                        <td id="i101{{$order->id}}">IGST({{$price->igstpercent}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;IGST<label class=" alert-success pull-left" id="igst1{{$order->id}}"></label>/-
                                             <input readonly  id="igst11{{$order->id}}" type="text" name="igst" value="{{$price->igst}}">
                                             <label class=" alert-success pull-right" id="lblWord31{{$order->id}}"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount (Including GST) :</td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withgst1{{$order->id}}"></label> /-
                                              <input readonly id="amountwithgst1{{$order->id}}" type="text" name="gstamount" value="{{$price->amountwithgst}}">
                                              <label class=" alert-success pull-right" id="lblWord21{{$order->id}}"></label>
                                        </td>
                                    </tr>
				    <tr>
                                      <td>Sales Person Name :</td>
                                      <td><input type="text" name="sales_person" id="sales_person"></td>
                                    </tr>
                            </table>
                            <center>
                            <button class="btn btn-sm btn-success" style="text-align: center;" onclick="document.getElementById('sendMessage').submit()">Confirm</button></center>
                          
                            @endforeach
                            @endif
                           </form>
                          </div>
                          <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
@endforeach

<script type="text/javascript">
  function getgst1(arg){
var data=arg; 
var cat = document.getElementById('category21'+arg).value;
var state = document.getElementById('state1'+arg);
var st = state.options[state.selectedIndex].value;
    $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getgstvalue12",
            async:false,
            data:{cat : cat, state : st},
            success: function(response)
            {
                  

                console.log(response[0]);

                setval1(response[0],data);
             }
       });   

  

 }
function setval1(arg,data){
      var arg = arg;
      var gstpercent = arg['gstpercent'];
      var cgst = arg['cgst'];
      var sgst = arg['sgst'];
      var igst = arg['igst'];
                 


               document.getElementById('g31'+data).value = gstpercent;
               document.getElementById('g11'+data).value = cgst;
               document.getElementById('g21'+data).value = sgst;
               document.getElementById('i11'+data).value = igst;
               if(igst == null){
                   document.getElementById('i101'+data).innerHTML ="IGST(0)%";

             }else{

                   document.getElementById('i101'+data).innerHTML ="IGST("+igst+")%";
             }
               if(cgst == null){
                     document.getElementById('c101'+data).innerHTML ="CGST(0)%";

               }else{
                    document.getElementById('c101'+data).innerHTML ="CGST("+cgst+")%";
               }
                 if(sgst == null){

                     document.getElementById('s101'+data).innerHTML ="SGST(0)%";
                 }else{
                  document.getElementById('s101'+data).innerHTML ="SGST("+sgst+")%";

                 }
                
}



function getcalculation1(arg){
var x =document.getElementById('unit1'+arg).value;
var y = document.getElementById('quan1'+arg).value;
var g1 = document.getElementById('g11'+arg).value;
var g2 = document.getElementById('g21'+arg).value;
var g3 = document.getElementById('g31'+arg).value;
var g4 = document.getElementById('g11'+arg).value;
var g5 = document.getElementById('g21'+arg).value;
var i1 = document.getElementById('i11'+arg).value;
var i2 = document.getElementById('i11'+arg).value;
var withoutgst = (x /g3);
var t = (withoutgst * y);
var f = Math.round(t);
var gst = (t * g1)/100;
var sgt = (t * g2)/100;
var igst = (t * i1)/100;
var gst1 = (t * g4)/100;
var sgt1 = (t * g5)/100;
var ig = (t * i2)/100;
var igst1 = Math.round(ig);
var withgst = (gst + sgt + t + igst);
var final = Math.round(withgst);
var tt = (gst + sgt);

var totaltax = Math.round(tt);
document.getElementById('display1'+arg).innerHTML = t;
document.getElementById('cgst1'+arg).innerHTML = gst;
document.getElementById('sgst1'+arg).innerHTML = sgt;
document.getElementById('igst1'+arg).innerHTML = igst;
document.getElementById('withgst1'+arg).innerHTML = withgst;
document.getElementById('withoutgst1'+arg).innerHTML = withoutgst;
document.getElementById('withoutgst11'+arg).value = withoutgst;
document.getElementById('amount1'+arg).value = f;
document.getElementById('cgst11'+arg).value = gst1;
document.getElementById('sgst11'+arg).value = sgt1;
document.getElementById('igst11'+arg).value = igst1;
document.getElementById('totaltax1'+arg).innerHTML = tt;
document.getElementById('totaltax11'+arg).value = totaltax;
document.getElementById('amountwithgst1'+arg).value = final;
}


</script>




<!-- ---------------------------------------------------------MR Invoice end-------------------------------------------------- -->
 


    @foreach($orders as $order)
        <!-- Modal -->
                    <div id="payment{{$order->id}}" class="modal fade" role="dialog">
                      <div class="modal-dialog" style="width:50%">
                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header" style="background-color: #5cb85c;color:white">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Generate Invoice</h4>
                          </div>
                          <div class="modal-body">
                           <form action="{{ URL::to('/') }}/saveunitprice?id={{$order->id}}&&manu_id={{$order->manu_id}}" method="post">
                            {{ csrf_field() }}
                           <!--  <input class="hidden" type="text" name="dtow1" id="dtow1{{$order->id}}" value="">
                            <input type="hidden" name="dtow2" id="dtow2{{$order->id}}" value="">
                            <input type="hidden" name="dtow3" id="dtow3{{$order->id}}" value="">
                            <input type="hidden" name="dtow4" id="dtow4{{$order->id}}" value=""> -->

                             @foreach($mamaprices as $price )  
                            @if($price->order_id == $order->id)
                           <table class="table table-responsive table-striped" border="1">
                            <input  type="hidden" name="g1" id="g1{{$order->id}}" value="{{$price->cgstpercent}}">
                            <input type="hidden" name="g2" id="g2{{$order->id}}" value="{{$price->sgstpercent}}">
                            <input type="hidden" name="g3" id="g3{{$order->id}}" value="{{$price->gstpercent}}">
                            <input type="hidden" name="i1" id="i1{{$order->id}}" value="{{$price->igstpercent}}">
                            
                           
                           <tr>
                            <?php 
                                     $rec =count($order->confirm_payment); 
                             ?>  
                              <td>Description of Goods : </td>
                             @if($rec == 0)
                                  <td><input required type="text" name="desc" class="form-control" value="{{ $order->main_category }}" id="category2{{$order->id}}"></td>
                             @else
                                  <td><input required type="text" name="desc" class="form-control" value="{{ $order->main_category }}" id="category2{{$order->id}}"></td>
                                  @endif
                           </tr>
                           <tr>
    <td>Select State</td>
    <td>
      <select id="state{{$order->id}}"  class="form-control" name="state" onclick="getgst('{{$order->id}}')">
          <option value="">----Select----</option>
          <option value="1">Karnataka</option>
          <option value="2">AndraPradesh</option>
          <option value="3">TamilNadu</option>

      </select>
     </td>
</tr>
                              <?php 
                                     $rec =count($order->confirm_payment); 
                             ?> 
                         @if($rec == 0)
                                   @foreach($reqs as $req)
                                    @if($req->id == $order->req_id)
                                          <tr>
                                              <td>Billing Address : </td> 
                                              <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5">{{$req->billadress}}</textarea></td>
                                          </tr>
                                          <tr>
                                              <td>Shipping Address : </td> 
                                               @if($order-> project_id == null)
                                               <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">{{$req->manu != null ? $req->manu->address : ''}}</textarea></td>
                                               @else
                                              <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">{{$req->siteaddress != null ? $req->siteaddress->address : ''}}</textarea></td>
                                              @endif
                                          </tr>
                                        @endif
                                       @endforeach  
                                       <tr>
                                         <td>Customer Gst : </td>
                                         <td>
                                          <?php
                                          $num = App\ProcurementDetails::where('project_id',$order->project_id)->pluck('procurement_contact_no')->first();
                                         if($num == null){
                                           $num = App\Mprocurement_Details::where('manu_id',$order->manu_id)->pluck('contact')->first();
                                         }
                                          $gst = App\CustomerGst::where('customer_phonenumber',$num)->pluck('customer_gst')->first();
                                          ?>
                                          <input type="text" value="{{$gst}}" name="customergst" class="form-control">
                                          </td>
                                       </tr>
                                       <tr>
                                      <td>Customer Name</td>
                                  
                                      <td><input type="text" name="customer_name"  required class="form-control"></td>
                                    </tr>
                                        <tr>
                                         <td>HSN</td>
                                         <td>
                                          <?php $hsn = App\Category::where('category_name',$order->main_category)->pluck('HSN')->first(); ?>
                                          <input type="text" value="{{$hsn}}" name="HSN" class="form-control">
                                          </td>
                                       </tr>
                                      <tr>
                                         <td>Payment Method</td>
                                         <td>
                                          <label class="checkbox-inline">
                                              <input style="width: 33px;" id="planning" type="checkbox"  name="status[]" value="CASH"><span>&nbsp;&nbsp;&nbsp;</span>CASH
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="digging" type="checkbox"  name="status[]" value="RTGS">RTGS
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" type="checkbox"  name="status[]" value="CHEQUE">CHEQUE
                                            </label>
                                          </td>
                                       </tr>
                         @else
                                       <tr>
                                         <td>Payment Method</td>
                                         <td>
                                           
                                          <?php  
                                                      $statuses = explode(",",$price->payment_mode);
                                                      
                                                      
                                                  ?>
                                              
                                          <label class="checkbox-inline">

                                              <input  {{ in_array('CASH', $statuses) ? 'checked': ''}} style="width: 33px;" id="planning" type="checkbox"  name="status[]" value="CASH"><span>&nbsp;&nbsp;&nbsp;</span>CASH
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input  {{ in_array('RTGS', $statuses) ? 'checked': ''}} id="digging" type="checkbox"  name="status[]" value="RTGS">RTGS
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" {{ in_array('CHEQUE', $statuses) ? 'checked': ''}} type="checkbox"  name="status[]" value="CHEQUE">CHEQUE
                                            </label>
                                          </td>
                                </tr>
                              <tr>
                                  <td>Billing Address : </td> 
                                  <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5">{{$price->billaddress}}</textarea></td>
                              </tr>
                              <tr>
                                  <td>Shipping Address : </td> 
                                  <td><textarea required type="text" name="ship" class="form-control"  style="resize: none;" rows="5">{{$price->shipaddress}}</textarea></td>
                              </tr>
                                <tr>
                                         <td>Customer Gst : </td>
                                         <td>
                                          <?php
                                          $num = App\ProcurementDetails::where('project_id',$order->project_id)->pluck('procurement_contact_no')->first();
                                         if($num == null){
                                           $num = App\Mprocurement_Details::where('manu_id',$order->manu_id)->pluck('contact')->first();
                                         }
                                          $gst = App\CustomerGst::where('customer_phonenumber',$num)->pluck('customer_gst')->first();
                                          ?>
                                          <input type="text" value="{{$gst}}" name="customergst" class="form-control">
                                          </td>
                                       </tr>
                                       <tr>
                <td>Customer Name</td>
                
                 <td><input type="text" name="customer_name" required class="form-control"></td>
               </tr>
                         @endif
                         
                           <tr>
                             <td>Total Quantity : </td>
                             <td><input required type="text" class="form-control" name="quantity" value="{{$price->quantity}}" id="quan{{$order->id}}"></td>
                           </tr>
                            <tr>
                              <td>Unit of Measurement : </td>
                              <td><input  type="text" name="unit" value="{{$price->unit}}" class="form-control" >
                           
                            </tr>
                            <tr>
                              <td>Price(Per Unit) : </td>
                              <td><input required type="text" id="unit{{$order->id}}"  class="form-control" name="price" value="{{$price->mamahome_price}}"  onkeyup="getcalculation('{{$order->id}}')"></td>
                            </tr>  
                            <tr>
                                        <td>Unit Price without GST :</td>
                                        <td>&nbsp;&nbsp;&nbsp;RS.<label class=" alert-success pull-left" id="withoutgst{{$order->id}}"></label>/-
                                            <input readonly id="withoutgst123{{$order->id}}" type="text" name="unitwithoutgst"  value="{{$price->unitwithoutgst}}">
                                       </td>
                              </tr>
                                    <tr>
                                        <td>Total Amount : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="display{{$order->id}}"></label>/-
                                              <input readonly id="amount{{$order->id}}" type="text" name="tamount" value="{{$price->totalamount}}">
                                              <label class=" alert-success pull-right" id="lblWord{{$order->id}}"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="c10{{$order->id}}">CGST({{$price->cgstpercent}}%) : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;CGST <label class=" alert-success pull-left" id="cgst{{$order->id}}"></label>/-
                                              <input readonly  id="cgst123{{$order->id}}" type="text" name="cgst" value="{{$price->cgst}}">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="s10{{$order->id}}">SGST({{$price->sgstpercent}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;SGST <label class=" alert-success pull-left" id="sgst{{$order->id}}"></label>/-
                                             <input readonly  id="sgst123{{$order->id}}" type="text" name="sgst" value="{{$price->sgst}}">
                                        </td>
                                    </tr>
                                    <tr>
                                      <td>Total Tax :</td>
                                      <td>&nbsp;&nbsp;&nbsp;<label class=" alert-success pull-left" id="totaltax{{$order->id}}"></label>Total
                                        <input readonly id="totaltax123{{$order->id}}" type="text" name="totaltax" value="{{$price->totaltax}}">
                                        <label class=" alert-success pull-right" id="lblWord1{{$order->id}}"></label>
                                      </td>
                                    </tr>
                                    <tr>
                                        <td id="i10{{$order->id}}">IGST({{$price->igstpercent}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;IGST<label class=" alert-success pull-left" id="igst{{$order->id}}"></label>/-
                                             <input readonly  id="igst123{{$order->id}}" type="text" name="igst" value="{{$price->igst}}">
                                             <label class=" alert-success pull-right" id="lblWord3{{$order->id}}"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount (Including GST) :</td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withgst{{$order->id}}"></label> /-
                                              <input readonly id="amountwithgst{{$order->id}}" type="text" name="gstamount" value="{{$price->amountwithgst}}">
                                              <label class=" alert-success pull-right" id="lblWord2{{$order->id}}"></label>
                                        </td>
                                    </tr>
				    <tr>
                                      <td>Sales Person Name :</td>
                                      <td><input type="text" name="sales_person" id="sales_person"></td>
                                    </tr>
                            </table>
                            <center>
                            <button class="btn btn-sm btn-success" style="text-align: center;">Confirm</button></center>
                            @endif
                            @endforeach
                           </form>
                          </div>
                          <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
<!-- Modal -->
<div id="clear{{$order->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancel Remark</h4>
      </div>
       <div class="modal-body">
          <form action="{{URL::to('/')}}/cancelinvoice" method="get">
                {{ csrf_field() }}
                <label>Eneter remark
                    <textarea class="form-control" name="remark">
                        
                    </textarea>
                </label><br>
                    <input type="hidden" name="id" value="{{$price->id}}">
                   <button type="submit" class="btn btn-sm btn-warning">Submit</button>
          </form>         
               
     </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- modal end -->
        <div id="myModal{{ $order->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header" style="background-color: #337ab7;color:white;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Payment Details</h4>
                </div>
                <div class="modal-body">
                    @foreach($payments as $payment)
            @if($payment->order_id == $order->id)
            <table class="table table-responsive table-striped" border="1">
              <tr>
                <td>OrderId :</td>
                <td>{{$payment->order_id}}</td>
              </tr>
              <tr>
                <td>Payment Mode :</td>
                <td>{{ $payment->payment_mode }}</td>
              </tr>
              <tr>
                <td>Date :</td>
                <td>{{ date('d-m-Y',strtotime($payment->date))}}</td>
              </tr>
              @if($payment->payment_mode == "CASH")
              <tr>
                <td>Cash Deposit Slip :</td>

                <td>
                    <!-- <img src="{{ URL::to('/') }}/payment_details/{{ $payment->file }}" alt="" class="img img-responsive"> -->
                    <?php
                                                     $images = explode(",", $payment->file );
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($images); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{ $url}}/payment_details/{{ $images[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                                    </div>
                </td>
              </tr>
              @endif
              @if($payment->payment_mode == "RTGS")
              <tr>
                <td>RTGS Image: </td>
                  <td>
                      <?php
                             $images = explode(",", $payment->rtgs_file );
                            ?>
                           <div class="col-md-12">
                               @for($i = 0; $i < count($images); $i++)
                                   <div class="col-md-3">
                                        <img height="350" width="350" id="project_img" src="{{$url }}/rtgs_files/{{ $images[$i] }}" class="img img-thumbnail">
                                   </div>
                               @endfor
                            </div>
                  </td>
              </tr>
              <tr>
                <td>Reference Number :</td>
                <td>{{$payment->account_number}}<br></td>
              </tr>
              <tr>
                <td>Branch Name :</td>
                <td>{{$payment->branch_name}}<br></td>
              </tr>
              @endif
              @if($payment->payment_mode == "CHEQUE")
              <tr>
                <td>Cheque Number :</td>
                <td>{{$payment->cheque_number}}
                </td>
              </tr>
              @endif
              @if($payment->payment_mode == "CASH IN HAND")
              <tr>
                <td>Cash Holder Name :</td>
               <td>{{$payment->user != null?$payment->user->name :''}}</td>
              </tr>
              <tr>
                <td> Cash Received Date :</td>
                <td>{{date('d-m-Y',strtotime($payment->date))}}</td>
              </tr>
              <tr>
                 <td>Uploded image : </td>
                <td>
                    <?php
                                                     $cimage = explode(",", $payment->cash_image);
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($cimage); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{ $url }}/cash_images/{{ $cimage[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                                    </div>
                </td> 
              </tr>
              @endif
              <tr>
                <td>Amount :</td>
                <td>{{$payment->totalamount}}/-</td>
              </tr>
              <!-- <tr>
                <td>Delivery Charges :</td>
                <td>{{$payment->damount}}/-</td>
              </tr> -->
              <tr>
                <td>Note :</td>
                <td>{{$payment->payment_note}}</td>
              </tr>
            </table>
            <!-- <img src="{{ URL::to('/') }}/payment_details/{{ $payment->file }}" alt="" class="img img-responsive"> -->
            @endif 
            @endforeach 
            @foreach($payhistory as $payment)
            @if($payment->order_id == $order->id)
                <table class="table table-responsive table-striped" border="1">
              <tr>
                <td>OrderId :</td>
                <td>{{$payment->order_id}}</td>
              </tr>
              <tr>
                <td>Payment Mode :</td>
                <td>{{ $payment->payment_mode }}</td>
              </tr>
              @if($payment->payment_mode == "CASH")
              <tr>
                <td>Cash Deposit Slip :</td>
                <td>
                  <?php
                                                     $images = explode(",", $payment->file );
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($images); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{ $url }}/payment_details/{{ $images[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                                    </div>
                </td>
              </tr>
               <tr>
                <td>Cash Deposit Date :</td>
                <td>{{ date('d-m-Y',strtotime($payment->date))}}</td>
              </tr>
              @endif
              @if($payment->payment_mode == "RTGS")
              <tr>
                  <td>RTGS Image: </td>
                  <td>
                      <?php
                             $images = explode(",", $payment->rtgs_file );
                            ?>
                           <div class="col-md-12">
                               @for($i = 0; $i < count($images); $i++)
                                   <div class="col-md-3">
                                        <img height="350" width="350" id="project_img" src="{{ $url }}/rtgs_files/{{ $images[$i] }}" class="img img-thumbnail">
                                   </div>
                               @endfor
                            </div>
                  </td>
              </tr>
               <tr>
                <td>Date :</td>
                <td>{{date('d-m-Y',strtotime($payment->date))}}</td>
              </tr>
              <tr>
                <td>Reference Number :</td>
                <td>{{$payment->account_number}}<br></td>
              </tr>
              <tr>
                <td>Branch Name :</td>
                <td>{{$payment->branch_name}}<br></td>
              </tr>
              @endif
              @if($payment->payment_mode == "CHEQUE")
               <tr>
                <td>Cheque Deposit Date :</td>
                <td>{{date('d-m-Y',strtotime($payment->date))}}</td>
              </tr>
              <tr>
                <td>Cheque Number :</td>
                <td>{{$payment->cheque_number}}
                </td>
              </tr>
              @endif
              @if($payment->payment_mode == "CASH IN HAND")
              <tr>
                <td>Cash Holder Name : </td>
                <td>{{$payment->user != null?$payment->user->name :''}}</td>
              </tr>
                 <tr>
                <td> Cash Received Date :</td>
                <td>{{date('d-m-Y',strtotime($payment->date))}}</td>
              </tr>
              <tr>
                 <td>Uploded image : </td>
                <td>
                    <?php
                                                     $cimage = explode(",", $payment->cash_image);
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($cimage); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{ $url }}/cash_images/{{ $cimage[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                                    </div>
                </td> 
              </tr>
              @endif
              <tr>
                <td>Total Amount :</td>
                <td>{{$payment->totalamount}}/-</td>
              </tr>
              <!-- <tr>
                <td>Delivery Charges :</td>
                <td>{{$payment->damount}}/-</td>
              </tr> -->
              <tr>
                <td>Note :</td>
                <td>{{$payment->payment_note}}</td>
              </tr>
            </table>
            @endif
            @endforeach 
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            Messages: <br>
                            @foreach($messages as $message)
                                @if($message->to_user == $order->id)
                                    <p
                                        style="width:70%;
                                            border-style:ridge;
                                            padding:10px;
                                            border-width:2px;
                                            border-radius:10px;
                                            {{ $message->from_user == Auth::user()->id ? 'border-bottom-left-radius:0px;' : 'border-bottom-right-radius:0px;' }}
                                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
                                            "
                                            class="text-justify {{ $message->from_user == Auth::user()->id ? 'pull-right' : 'pull-left' }}">
                                        @foreach($users as $user)
                                            @if($user->id == $message->from_user)
                                                <b>- {{ $user->name }} : </b><br>
                                            @endif
                                        @endforeach
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $message->body }}
                                        <br>
                                        <span class="pull-right"><i>{{ $message->created_at->diffforHumans() }}</i></span>
                                    </p>
                                @endif
                            @endforeach
                        </div>
                        <div class="col-md-12">
                            <form action="{{ URL::to('/') }}/sendMessage" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="orderId" value="{{ $order->id }}">    
                                <div class="input-group">
                                    <input type="text" name="message" id="message" placeholder="Type Your Message Here" class="form-control">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-success">Send</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>
    @endforeach
@if(session('Success'))
<script>
    swal('Success',"{{ session('Success') }}",'success');
</script>
@endif
<script type="text/javascript">
    function checkthis(arg){
    var input = document.getElementById(arg).value;
    if(isNaN(input)){
        
               document.getElementById(arg).value = "";
    }
}
 function checkthis1(arg){
    var input = document.getElementById(arg).value;
    if(isNaN(input)){
        
               document.getElementById(arg).value = "";
    }
}
</script>
<script type="text/javascript">
  function onlyNumbers(evt) {
    var e = event || evt; // For trans-browser compatibility
    var charCode = e.which || e.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
function reset(arg){
    var ans = confirm('Are You Sure You Want Clear This PO? Note: Changes Made Once Cannot Be Undone');
    if(ans){ 
        document.getElementById("resetid"+arg).value = arg ;
        document.getElementById("reset"+arg).form.submit();
    }
}
function resetmulti(arg){
    var ans = confirm('Are You Sure You Want Clear This PO? Note: Changes Made Once Cannot Be Undone');
    if(ans){ 
        document.getElementById("rese"+arg).value = arg ;
        document.getElementById("multi"+arg).submit();
    }
}





 function getgst(arg){
var data=arg; 
var cat = document.getElementById('category2'+arg).value;
var state = document.getElementById('state'+arg);
var st = state.options[state.selectedIndex].value;
    $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getgstvalue12",
            async:false,
            data:{cat : cat, state : st},
            success: function(response)
            {
                  

                console.log(response[0]);

                setval(response[0],data);
             }
       });   

  

 }
function setval(arg,data){
      var arg = arg;
      var gstpercent = arg['gstpercent'];
      var cgst = arg['cgst'];
      var sgst = arg['sgst'];
      var igst = arg['igst'];
                 


               document.getElementById('g3'+data).value = gstpercent;
               document.getElementById('g1'+data).value = cgst;
               document.getElementById('g2'+data).value = sgst;
               document.getElementById('i1'+data).value = igst;
               if(igst == null){
                   document.getElementById('i10'+data).innerHTML ="IGST(0)%";

             }else{

                   document.getElementById('i10'+data).innerHTML ="IGST("+igst+")%";
             }
               if(cgst == null){
                     document.getElementById('c10'+data).innerHTML ="CGST(0)%";

               }else{
                    document.getElementById('c10'+data).innerHTML ="CGST("+cgst+")%";
               }
                 if(sgst == null){

                     document.getElementById('s10'+data).innerHTML ="SGST(0)%";
                 }else{
                  document.getElementById('s10'+data).innerHTML ="SGST("+sgst+")%";

                 }
                
}



function getcalculation(arg){
var x =document.getElementById('unit'+arg).value;
var y = document.getElementById('quan'+arg).value;
var g1 = document.getElementById('g1'+arg).value;
var g2 = document.getElementById('g2'+arg).value;
var g3 = document.getElementById('g3'+arg).value;
var g4 = document.getElementById('g1'+arg).value;
var g5 = document.getElementById('g2'+arg).value;
var i1 = document.getElementById('i1'+arg).value;
var i2 = document.getElementById('i1'+arg).value;
var withoutgst = (x /g3);
var t = (withoutgst * y);
var f = Math.round(t);
var gst = (t * g1)/100;
var sgt = (t * g2)/100;
var igst = (t * i1)/100;
var gst1 = (t * g4)/100;
var sgt1 = (t * g5)/100;
var ig = (t * i2)/100;
var igst1 = Math.round(ig);
var withgst = (gst + sgt + t + igst);
var final = Math.round(withgst);
var tt = (gst + sgt);


var totaltax = Math.round(tt);
document.getElementById('withoutgst123'+arg).value = withoutgst;
document.getElementById('display'+arg).innerHTML = t;
document.getElementById('cgst'+arg).innerHTML = gst;
document.getElementById('sgst'+arg).innerHTML = sgt;
document.getElementById('igst'+arg).innerHTML = igst;
document.getElementById('withgst'+arg).innerHTML = withgst;
document.getElementById('withoutgst'+arg).innerHTML = withoutgst;
document.getElementById('amount'+arg).value = f;
document.getElementById('cgst123'+arg).value = gst1;
document.getElementById('sgst123'+arg).value = sgt1;
document.getElementById('igst123'+arg).value = igst1;
document.getElementById('totaltax'+arg).innerHTML = tt;
document.getElementById('totaltax123'+arg).value = totaltax;
document.getElementById('amountwithgst'+arg).value = final;
}
// function finalsubmit(arg){
//   var input =  document.getElementById('amount'+arg).value;
//   var output = document.getElementById('totaltax1'+arg).value;
//   var inout = document.getElementById('amountwithgst'+arg).value;
//   var tax = document.getElementById('igst1'+arg).value;
//   document.getElementById('amount'+arg).addEventListener("click", NumToWord(input,'lblWord'+arg,arg));
//   document.getElementById('totaltax1'+arg).addEventListener("click", NumToWord1(output,'lblWord1'+arg,arg));
//   document.getElementById('amountwithgst'+arg).addEventListener("click", NumToWord2(inout,'lblWord2'+arg,arg));
//   document.getElementById('igst1'+arg).addEventListener("click", NumToWord3(tax,'lblWord3'+arg,arg));

// }
</script>



<script type="text/javascript">
  function getcustomerdeatials(arg){
  
 
        var number =  document.getElementById('number'+arg).value;
          $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getsuperdetails",
            async:false,
            data:{number : number},
            success: function(response)
            {
                    console.log(response);
                     var element = document.getElementById('type'+arg);
                     element.value = response['data']['customer_type'];
                      
                   var element1 = document.getElementById('subtype'+arg);
                     element1.value = response['data']['sub_customer_type'];
               
              
              
              document.getElementById('cid'+arg).value = response['data']['customer_id'];
              document.getElementById('custname'+arg).value = response['data']['first_name'];
              document.getElementById('gstcust'+arg).value = response['gst'];
              
                
             }
       });
  }
</script>
@endsection

