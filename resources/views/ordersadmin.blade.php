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
<script>
function openCitytest(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
</head>
<body>

<div class="col-md-12">
    <div class="panel panel-primary" style="overflow-x: scroll;">
        <div class="panel-heading text-center" style="width:98%;position:absolute;">
            <b style="color:white;font-size:1.4em">Orders</b>
           <button type="button" onclick="history.back(-1)" class="btn btn-default pull-right" style="margin-top:-3px;" > <i class="fa fa-arrow-circle-left" style="width:30px;"></i></button>
            <h4 class="pull-left" style="margin-top: -0.5px;">Total Count : {{ $view->total() }}</h4>

        </div><br><br>
  <span class="pull-right"> @include('flash-message')</span>
        
        <div id="myordertable" class="panel-body">
             <form action="{{URL::to('/')}}/orders" method="get" id="d">
          <div class="pull-left">
             <div class="col-md-2">
                <lable><b>From </b></lable>
                <input value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from">
              </div>
              <div class="col-md-2">
                <lable><b>To</b></lable>
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
            <form action="{{URL::to('/')}}/orders" method="get">
                <div class="input-group col-md-3 pull-right">
                    <input type="text" class="form-control pull-left" placeholder="Enter project id" name="projectId" id="projectId">
                    
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                </div>
            </form>
             
            <br><br>
            <table class="table table-responsive table-striped" border="1">
                <thead>
                    <tr>
                        <th>Project ID</th>
                        <th>Enquiry ID</th>
                        <th>Order Id</th>
                        <th>Order Inititated By</th>
                        <th>Required</th>
                        <th>Quantity</th>
                        <th>Order Value</th>
                        <th>Order Placed Date</th>
                        <th>Assign Logistics</th>
                        <th>Requirement Date</th>
                        <th>Payment Type</th>
                        <th>Payment Mode</th>
                        <th>Order Status</th>
                        <th>LPO</th>
                        <th>Get Purchase Order</th>
                          <th> Multiple Products  PO generation</th>
                        <th>Edit/Cancel</th>
                        <th>Generate MR LPO</th>
                        <th>LPO Generated By</th>
                        <th>Order Confirmed By</th>   
                    </tr>
                </thead>
                <tbody>
                    @foreach($view as $rec)
                    <tr style="{{ $rec->order_status == 'Order Cancelled' ? 'background-color: #ffbaba;' : '' }}" id="row-{{$rec->id}}">
                        <td>
                            <a href="{{URL::to('/')}}/showThisProject?id={{$rec->project_id}}">{{$rec -> project_id}}</a>
                             @if($rec -> project_id == null)
                            <a href="{{ URL::to('/') }}/viewmanu?id={{ $rec->manu_id }}">Manufacturer {{$rec -> manu_id}}</a>
                            @endif
                        </td>
                        <td>
                                <a href="{{ URL::to('/') }}/editenq?reqId={{ $rec->req_id }}" >{{$rec->req_id}}</a>
                        </td>
                        <td>{{ $rec->orderid }}  </td>
                        <td>{{$rec->name}}</td>
                        <td>
                            {{$rec -> main_category}}<br>
                            <!-- {{$rec -> sub_category}} -->
                            @if($rec->main_category == "STEEL" )
                                <?php
                                    $id = explode(",",$rec->sub_category);
                                
                                ?>
                                (
                                @for($i=0; $i<count($id) ; $i++)

                                   <?php
                                   $name = App\SubCategory::where('id',$id[$i])->pluck('sub_cat_name')->first();
                                   ?>
                                            {{$name}},
                                @endfor
                                )
                                @else
                                <?php
                                   $name = App\SubCategory::where('id',$rec->sub_category)->pluck('sub_cat_name')->first();
                                   ?>
                                ({{$name}})
                            @endif
                                <br>
                            {{$rec -> brand}}<br>
                        </td>
                        <td>{{$rec->quantity}} {{$rec->measurement_unit}}</td>
                          <td>{{$rec->order_value}}</td>
                          <td><?php  
                            if($rec->order_date!=null){ 
                              $date1 = date("d-m-Y", strtotime($rec->order_date));
                            }
                            else{
                              $date1='';
                            }
                            ?>
                              
                              {{$date1}}
                            
                            </td>  
                        <td>
                          <?php 
                          $count = count($rec->ostatus);
                          ?>
                          @if($count == 1)
                          <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#logistics{{$rec->orderid}}">Logistics</button>
                          @else
                          <button disabled type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#logistics{{$rec->orderid}}">Logistics</button>
                          @endif
                        </td>
                        <td>{{ date('d-m-Y',strtotime($rec -> requirement_date)) }}</td>
                        <td>{{$rec->paytype}}</td>
                        <!-- <td class="text-center" id="paymenttd-{{$rec->orderid}}">
                            @if($rec->ostatus == "Payment Received")
                                {{ $rec->ostatus }}
                            @else
                                {{ $rec->ostatus }}
                            @endif
                        </td> -->
                        <td>
                            @if($rec->ostatus == "Payment Received")
                                <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#paymentModal{{ $rec->orderid }}">
                                <?php $pay = explode(", ",$rec->payment_mode); 
                               
                                $pay = implode(" / ", $pay);        
                                ?>
                                   {{$pay}}
                                    <span class="badge">{{ $counts[$rec->orderid] }}</span>
                                   
                                </button>
                                
        
        </div>
                                <a href="{{URL::to('/')}}/paymentmode?id={{$rec->orderid}}&&pid={{$rec->project_id}}&&mid={{$rec->manu_id}}&&reqid={{$rec->id}}" target="_blank" class="btn btn-default btn-xs" ><i class="fa fa-plus"></i></a>
                                <?php $zero = App\PaymentDetails::where('order_id',$rec->orderid)->where('totalamount',0)->count(); 
                                 

 

                                ?>
                            @elseif($rec->order_status != "Order Cancelled")
                                   @if($rec->paytype !="Cashagainestdelivery" )
                                <a href="{{URL::to('/')}}/paymentmode?id={{$rec->orderid}}&&pid={{$rec->project_id}}&&mid={{$rec->manu_id}}&&reqid={{$rec->id}}" target="_blank" class="btn btn-success btn-xs">Payment Details</a>
                                   @else
                                  Pending Admin Aproval(Cash Againest Delivery) 
                                  @endif
                            @endif<br><br>
                                       

                                        <?php $data =App\OrderExpenses::where('order_id',$rec->orderid)->get();
                                        ?>
                                         @if($rec->paytype !="Cashagainestdelivery")
    <button type="button" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#expense{{$rec->orderid}}"> Other Expenses<span class="badge"><?php $s = count($data); ?>&nbsp;{{$s}}&nbsp;</span> </button>
@endif
 <div id="expense{{$rec->orderid}}" class="modal fade" role="dialog">
                      <div class="modal-dialog" style="width:30%">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header" style="background-color: #5cb85c;color:white">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Other Expenses</h4>
                          </div>
                          <div class="modal-body">
                                        @foreach($data as $da)
                                <table class="table table-responsive table-striped" border="1">
                                    <tr>

                                        <td>User name</td>
                                        <td>{{$da->user != null ? $da->user->name : ''}}</td>   
                                    </tr>
                                    <tr>

                                        <td>Head</td>
                                        <td>{{$da->head}}</td>   
                                    </tr>
                                    <tr>
                                        <td>Amount</td>
                                        <td>{{ $da->amount }}</td>
                                    </tr>
                                    <tr>
                                        <td>Remarks</td>
                                        <td>{{ $da->remark }}</td>
                                    </tr>
                                </table><br>
                                    @endforeach
                          <center><span style="color:black;font-weight:bold;font-size:20px;">Add expenses</span></center> <br>         
                           <form action="{{ URL::to('/') }}/addexpense" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="order_id" value="{{$rec->orderid}}">
                            <input type="hidden" name="manuid" value="{{$rec->manu_id}}">
                            <input type="hidden" name="req_id" value="{{$rec->id}}">
                           <label> Type Of Head : </label>
                            <select required name="type" class="form-control">
                                <option  value="">-----Select-----</option>
                                <option  value="Tip">Tips</option>
                                <option  value="Delivery">Delivery Charges</option>

                            </select><br>
                             <label> Select Employee : </label>
                            <select required name="userid" class="form-control">
                                <option  value="">-----Select-----</option>
                                @foreach($eusers as $use)
                                <option  value="{{$use->id}}">{{$use->name}}</option>
                                @endforeach
                            </select>
                             
                            <br>
                            <label>Amount :</label>
                           <input  type="text" name="amount"  class="form-control" placeholder="Eneter Amount" required>
                            <br>
                            <label>Remark : </label>
                            <textarea name="remark" class="form-control" placeholder="remark">
                                  
                            </textarea>
                            
                            <input type="hidden" value="{{$rec->price}}" id="test1{{$rec->orderid}}">

                            <br>
                            <center><button class="btn btn-success" type="submit">Submit</button></center>
                           
                            <br>
                           </form>
                          </div>
                          <div class="modal-footer">
                            <button  type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
</div>
</div>
                    <div id="payment{{$rec->orderid}}" class="modal fade" role="dialog">
                      <div class="modal-dialog" style="width:30%">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header" style="background-color: #5cb85c;color:white">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Confirm Payment</h4>
                          </div>
                          <div class="modal-body">
                                <table class="table table-responsive table-striped" border="1">
                                    <tr>
                                        <td>Payment Mode :</td>
                                        <td><?php $pay = explode(", ",$rec->payment_mode);
                                        $pay = implode(" / ", $pay);        
                                        ?>
                                   {{$pay}}</td>   
                                    </tr>
                                    <tr>
                                        <td>Category :</td>
                                        <td>{{ $rec->main_category }}</td>
                                    </tr>
                                    <tr>
                                        <td>Quantity :</td>
                                        <td>{{ $rec->quantity }}</td>
                                    </tr>
                                </table>
                           <form id="sub{{$rec->orderid}}" action="{{ URL::to('/') }}/confirmOrder?id={{$rec->orderid}}&&mid={{$rec->manu_id}}&&rid={{$rec->id}}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="cat" value="{{$rec->main_category}}">
                            <label> Total Quantity : </label>
                            <input readonly required type="text" class="form-control" name="quantity" placeholder="quantity" id="quan" onkeyup="checkthis('quan')" value="{{$rec->total_quantity}}">
                            <br>
                            <!-- <label> State : </label><label class="alert-danger">{{$rec->st != null ? '' : "Select State From Enquiry"}}</label>
                            <select required name="state" class="form-control">
                                <option  value="{{ $rec->st != null ? $rec->st->id : ''}}">{{$rec->st != null ? $rec->st->state_name : '' }}</option>
                            </select>
                            <br> -->
                            <label>Select State(To Generate Invoice) : </label>
                            <select id="istate{{$rec->orderid}}" name="state" class="form-control" >
                              <option>--select--</option>
                              @foreach($states as $state)
                              <option value="{{$state->id}}">{{$state->state_name}}</option>
                             @endforeach
                          </select>
                          <br>
                            <label>Measurement Unit : </label>
                           <input  type="text" name="unit" value="{{$rec->cat != null ? $rec->cat->measurement_unit: ''}}" class="form-control" placeholder="Bags/Tons" required data-readonly>
                            <br>
                            
                            <label>Mamahome Price(Per Unit) : </label>
                            <input required type="text" id="unit{{$rec->orderid}}"  class="form-control" name="mamaprice" placeholder="Unit Price"  value="{{$rec->price}}">
                            <input type="hidden" value="{{$rec->price}}" id="test1{{$rec->orderid}}">
                            <br>
                            <center><button type="button" class="btn btn-success" onclick="gothr('{{$rec->orderid}}')">Confirm</button></center>  
                           
                            <br>
                           </form>
                          </div>
                          <div class="modal-footer">
                            <button  type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
<!-- Modal -->
<!-- Modal -->
<div id="logistics{{$rec->orderid}}" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:30%">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: #31b0d5;color: white;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Assign Logistic Coordinators</h4>
      </div>
      <div class="modal-body" >
       <form method="POST" id="assign" action="{{ url('/logistic')}}" >
          {{ csrf_field() }}
          <input type="hidden" name="logicid" value="{{$rec->orderid}}">
                            <div class="form-group">
                             <select size="7" id="menu{{$rec->orderid}}" name="framework" multiple class="form-control" style='overflow:hidden' required>
                               
                            @foreach($users as $user)
                              <option  value="{{$user->id}}"> {{ $user->name }}</option>
                             @endforeach
                            </select> 
                              <button class="btn btn-success" type="submit">Submit</button>
                             </div> 
          </form>    
        @if($rec->logistic != null)
        <b>Assigned Logistic Coordinators : </b><br><br>
        <?php $topic_ids = explode(',', $rec->logistic);

            $names = App\User::whereIn('id', $topic_ids)->get();
        ?>
        @if(count($topic_ids)>1)
          @foreach($names as $user)
         <a href="#" data-toggle="tooltip" data-placement="right" title="
                    name : {{ $user->name}}
Department : {{ $user->department->dept_name }}
Designation : {{ $user->group->group_name }}
Personal Phone No : {{ $user->contactNo }}
Office Phone No : {{ $user->emp != null ? $user->emp->office_phone : '' }}"
           class="red-tooltip">{{ $user->name}}</a><br>
          @endforeach
       @endif
        <br>

        @endif
                             <script type="text/javascript">
                                             $(document).ready(function(){
                                          @foreach($users as $user)
                                           $('#menu{{$rec->orderid}}').multiselect({
                                              nonSelectedText: 'Select Users',
                                              enableFiltering: true,
                                              enableCaseInsensitiveFiltering: true,
                                              buttonWidth:'200px',
                                               maxHeight: 200      
                                           });
                                           @endforeach
                                         });
                             </script>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="supplier{{$rec->orderid}}{{$rec->manu_id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header" style="background-color: green;color:white;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"  style="text-align: center;">Supplier Invoice</h4>
      </div>
      <div class="modal-body">
        <form action="{{ URL::to('/') }}/supplierinvoice?id={{$rec->orderid}}&&mid={{$rec->manu_id}}&&project_id={{$rec->project_id}}" method="post"  enctype="multipart/form-data">
            {{ csrf_field() }}
            <table class="table table-responsive table-striped" border="1">
                <tr>
                    <td>
                      <label>Supplier Invoice Number : </label>
                    </td>
                    <td><input required type="text" name="supplierinvoice" class="form-control"></td>
                </tr>
                <tr>
                    <td>
                       <label> Invoice Date : </label>
                    </td>
                    <td>
                      <input required type="date" name="invoicedate" class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Upload Files : </label>
                    </td>
                    <td>
                        <input required type="file" multiple name="file[]" class="form-control" accept="image/*"><br>
                         
                    </td>
                </tr>
            </table><center>
           <button type="submit" name="btnSubmit" align="center" class="btn btn-success">Submit</button></center>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- payment details modal -->
<!-- cancel modal -->
<!-- Modal -->
<div id="cancel{{$rec->orderid}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="width: 70%;">
      <div class="modal-header" style="background-color:#776e69;color: white">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cancel Order</h4>
      </div>
      <div class="modal-body">
                    <button href="{{URL::to('/')}}/storedetails" onclick="refund('{{$rec->orderid}}')" class=" btn btn-sm btn-success" >Refund</button>
                    <a href="{{URL::to('/')}}/storedetails"  onclick="show()" class=" btn btn-sm btn-danger " href="{{url()->previous()}}" >Adjust</a>
                    <br>
                    <div id="cancelorder{{$rec->orderid}}" style="display: none">
                    <form action="{{ URL::to('/') }}/savesupplierdetails?id={{$rec->orderid}}&&mid={{$rec->manu_id}}" method="post">
            {{ csrf_field() }}
                        <input type="text" name="orderid" value="{{$rec->orderid}}" id="orderid{{$rec->orderid}}" class="form-control" readonly>
                    </form>
                    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script type="text/javascript">
    function refund(arg){

        var dvPassport = document.getElementById("cancelorder"+arg);

        dvPassport.style.display =  "block" ;
    }
</script>
<!-- end -->
<!-- -----------------------------------------------MR Details------------------------------------------------------------------- -->
<div id="mrpurchase{{$rec->orderid}}{{$rec->manu_id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: green;color:white;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align: center;">MR Purchase Order</h4>
      </div>
      <div class="modal-body">
        <form action="{{ URL::to('/') }}/mrsavesupplierdetails?id={{$rec->orderid}}&&mid={{$rec->manu_id}}" method="post">
            {{ csrf_field() }}
       <!--  <input type="text" class="hidden" value="" id="dtow{{$rec->orderid}}" name="dtow" >
        <input type="text" class="hidden" value="" id="dtow1{{$rec->orderid}}" name="dtow1" > -->
        <input type="text" class="hidden" value="" id="cgstpercent1{{$rec->orderid}}" name="cgstpercent1" >
        <input type="text" class="hidden" value="" id="sgstpercent1{{$rec->orderid}}" name="sgstpercent1" >
        <input type="text" class="hidden" value="" id="gstpercent1{{$rec->orderid}}" name="gstpercent1" >
        <input type="text" class="hidden" value="" id="igstpercent1{{$rec->orderid}}" name="igstpercent1" >

       <table class="table table-responsive table-striped" border="1">
                                    <?php 
                                        $poo = App\MRSupplierdetails::where('order_id',$rec->orderid)->where('unit_price','!=',NULL)->first();


                                        
                                     ?> 
                                @if(count($poo) == 0)
        
                                    <tr>
                                    <td>Category :</td>
                                    <td>
                                        <?php
                                                $s1 =  $rec->main_category;
                                        ?>
                                    <select required name="category" id="supply1{{$rec->orderid}}"  class="form-control" >
                                        <option value="{{ $rec->main_category }}">{{ $rec->main_category }}</option>
                                      
                                    </select>
                                </td>
                                    </tr>
                                    <tr>
                                        <td>Supplier State : </td>
                                        <td>
                                            <select id="state1{{$rec->orderid}}"  class="form-control" name="state" >
          <option value="">----Select----</option>
          <option value="1"  {{ ( $rec->enq != null ? $rec->enq->spstate : '' ==1) ? 'selected' : '' }} >Karnataka</option>
          <option value="2" {{ ( $rec->enq != null ? $rec->enq->spstate  : '' ==2) ? 'selected' : '' }} >AndraPradesh</option>
          <option value="3" {{ ( $rec->enq != null ? $rec->enq->spstate : '' ==3) ? 'selected' : '' }} >TamilNadu</option>

      </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Supplier Name :</td>
                                        <td> 
                                        <select required class="form-control" id="name1{{$rec->orderid}}" name="name1"  onchange="getaddress1('{{$rec->orderid}}')">
                                          <option>--Select--</option>
                                     @foreach($manudetails as $manu)           
                                                
                                            <option value="{{$manu->company_name}}" >{{$manu->company_name}}</option>
                                   
                                    @endforeach
                                        </select>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>Registered Office :
                                      </td>
                                      <td><textarea required type="text" class="form-control" name="address1" id="address1{{$rec->orderid}}" name="address" rows="5" style="resize: none;">{{$rec->supplier != null ? $rec->supplier->registered_office : ''}}</textarea></td>
                                    </tr>
                                                <tr>
                                                    <td>Shipping Address : </td>
                                                    @if($rec->manu_id == null)
                                                    <td>
                                                    <textarea required type="text" class="form-control" name="ship1"  rows="5" style="resize: none;">{{$rec->req != null ? $rec->req->ship : ''}}</textarea>
                                                    </td>
                                                    @else
                                                    <td>
                                                    <textarea required type="text" class="form-control" name="ship1"  rows="5" style="resize: none;">{{$rec->req != null ? $rec->req->ship : ''}}</textarea>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>GST :</td>
                                                    <td><input required type="text" id="suppliergst1{{$rec->orderid}}" name="gst1" class="form-control" value="{{$rec->supplier != null ? $rec->supplier->gst : ''}}" ></td>
                                                </tr>
                                                <tr>
                                                    <td>Description of Goods : </td>
                                                    <td><input required type="text" name="desc1" id="desc1{{$rec->orderid}}" class="form-control" value="{{$rec->supplier != null ? $rec->supplier->category : ''}}" ></td>
                                                </tr>
                                                 <tr>
                                                    <td>Unit of Measurement:</td>
                                                    <td><input required type="unit" name="munit1" id="munit1{{$rec->orderid}}" class="form-control" value="{{$rec->cat != null ? $rec->cat->measurement_unit: ''}}" ></td>
                                                </tr>
                                    <tr>
                                        <td>Quantity : </td>
                                        <td><input required type="text" name="quantity1" class="form-control" id="qu1{{$rec->orderid}}" value="{{$rec->total_quantity}}" onkeyup="showthis1('{{$rec->orderid}}')"></td>
                                    </tr>
                                    <tr>
                                        <td>Customer Name : </td>
                                        <td><input required type="text" name="customer_name" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td> Supplier Unit Price :</td>
                                        <td>@if($rec->price != null)
                                            <?php $mhprice = App\Supplierdetails::where('order_id',$rec->orderid)->pluck('unit_price')->first(); ?>
                                            <label class="alert-success">MH price : {{$mhprice}}</label>
                                            <input type="hidden" value="{{$rec->price}}" id="test{{$rec->orderid}}">
                                            @endif
                                            <input required type="text" id="unitprice1{{$rec->orderid}}" name="uprice1" class="form-control" onkeyup="showthis1('{{$rec->orderid}}')" value="" >
                                       </td>
                                    </tr>
                                    <tr>
                                          <td>Unit Price without GST :</td>
                                        <td>&nbsp;&nbsp;&nbsp;<label class=" alert-success pull-left" id="fff{{$rec->orderid}}"></label>Rs./-
                                             <input  id="withoutgst11{{$rec->orderid}}" type="hidden" name="unitwithoutgst1" value="">
                                       </td>
                                    </tr>
                                    <tr>
                                        <td>Total Amount : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="display1{{$rec->orderid}}"></label>/-
                                        </td>
                                    </tr>
                                    <tr>
                                        
                                        <td>CGST( <label  id="tax11{{$rec->orderid}}"></label>%) : </td>
                                        <td>

                                              &nbsp;&nbsp;&nbsp;CGST <label class=" alert-success pull-left" id="cgst1{{$rec->orderid}}"></label>/-
                                        </td>
                                    </tr>
                                     <tr>
                                        <td>SGST( <label  id="tax21{{$rec->orderid}}"></label>%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;SGST <label class=" alert-success pull-left" id="sgst1{{$rec->orderid}}"></label>/-
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>IGST( <label  id="tax31{{$rec->orderid}}"></label>%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;IGST <label class=" alert-success pull-left" id="igst1{{$rec->orderid}}"></label>/-
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount (Including GST) :</td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withgst1{{$rec->orderid}}"></label> /-
                                        </td>
                                    </tr>
                                    @else
                                         <?php $suppliersw = App\MRSupplierdetails::where('order_id',$rec->orderid)->get(); ?>

                                                 @foreach($suppliersw as $supply)
                                                 @if($supply->order_id == $rec->orderid)
                                                             <tr>
                                                <td>Category :</td>
                                                <td>
                                                <select  name="category1" id="supply1{{$rec->orderid}}"  class="form-control" >
                                                   
                                                    <option value="{{ $supply->category }}">{{ $supply->description }}</option>
                                                   
                                                </select>
                                            </td>
                                                </tr>
                                    <tr>
                                            <td>Supplier State : </td>
                                            <td>
                                              
                                              
                                            <select id="state1{{$rec->orderid}}" name="state1" class="form-control">
                                                 <option value="{{ $rec->sts != null ? $rec->sts->id : ''}}">{{$supply->sts != null ? $supply->sts->state_name : '' }}
                                               </option>
                                            </select>
                                              
                                                            
                                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Supplier Name :</td>
                                        <td> 
                                        <input required class="form-control"  name="name1"  value="{{$supply->supplier_name}}"> 
                                      </td>

                                    </tr>
                                    
                                                <tr>
                                                  <td>Registered Office :
                                                  </td>
                                                  <td><textarea required type="text" class="form-control" rows="5" name="edit11" style="resize: none;">{{$supply->address}}</textarea></td>
                                                </tr>
                                                <tr>
                                                    <td>Shipping Address :
                                                  </td>
                                                    <td><textarea required type="text" class="form-control" name="edit21"  rows="5" style="resize: none;">{{$supply->ship}}</textarea></td>
                                                </tr>
                                                 <tr>
                                                    <td>GST :</td>
                                                    <td><input required type="text"  name="edit31" value="{{$supply->gst}}" class="form-control" ></td>
                                                </tr>
                                                <tr>
                                                    <td>Description of Goods : </td>
                                                    <td><input required type="text" name="edit41" class="form-control" value="{{$supply->description}}" ></td>
                                                </tr>
                                                 <tr>
                                                    <td>Unit of Measurement:</td>
                                                    <td><input required type="unit" name="edit51" class="form-control" value="{{$supply->unit}}" ></td>
                                                </tr>
                                                <tr>
                                                    <td>Quantity : </td>
                                                    <td><input required type="text" name="edit61" id="qu1{{$rec->orderid}}" class="form-control"  value="{{$supply->quantity}}" onkeyup="showthis1('{{$rec->orderid}}')"></td>
                                                </tr>
                                                <tr>
                                                    <td> Supplier Unit Price :</td>
                                                    <td>
                                                        <input required type="text" id="unitprice1{{$rec->orderid}}" name="uprice1" class="form-control" onkeyup="showthis1('{{$rec->orderid}}')" value="{{$supply->unit_price}}">
                                                         <input type="hidden" value="{{$rec->price}}" id="test1{{$rec->orderid}}">
                                                   </td>
                                                </tr>
                                                <tr>
                                                <td>Unit Price without GST :</td>
                                                <td>&nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withoutgst11{{$rec->orderid}}"></label>/-
                                                    <input readonly id="withoutgst11{{$rec->orderid}}" type="text" name="unitwithoutgst1" value="{{$supply->unitwithoutgst}}"> 
                                               </td>
                                                </tr>
                                                <tr>
                                        <td>Total Amount : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="display1{{$rec->orderid}}"></label>/-
                                               
                                                    <input readonly type="text" id="display12{{$rec->orderid}}" name="" value="{{$supply->amount}}">
                                           
                                        </td>
                                    </tr>
                                    <tr>
                                            <?php
                                    $t1 = ($supply->amount * $supply->cgstpercent)/100;
                                    $t2 = ($supply->amount * $supply->sgstpercent)/100;
                                    $t3 = ($supply->amount * $supply->igstpercent)/100;
                                            ?>
                                        
                                        <td>CGST(<label  id="tax11{{$rec->orderid}}">{{$supply->cgstpercent}}</label>%) : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;CGST <label class=" alert-success pull-left" id="cgst1{{$rec->orderid}}"></label>/-
                                              <input readonly type="text" name="" id="cgst12{{$rec->orderid}}"
                                                                              value="{{$t1}}">
                                        </td>
                                    </tr>

                                            



                                    <tr>
                                        <td>SGST(<label  id="tax21{{$rec->orderid}}"> {{$supply->sgstpercent}}</label>%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;SGST <label class=" alert-success pull-left" id="sgst1{{$rec->orderid}}"></label>/-
                                             <input readonly name="" value="{{$t2}}" id="sgst12{{$rec->orderid}}" type="text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>IGST( <label  id="tax31{{$rec->orderid}}">{{$supply->igstpercent}}</label>%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;IGST <label class=" alert-success pull-left" id="igst1{{$rec->orderid}}"></label>/-

                                             <input readonly type="text" name="" id="igst12{{$rec->orderid}}" value="{{$t3}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount (Including GST) :</td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withgst1{{$rec->orderid}}"></label> /-
                                             
                                                    <input readonly type="text" name=""  id="tamount12{{$rec->orderid}}" value="{{$supply->totalamount}}">
                                            
                                        </td>
                                    </tr>
                                        @endif
                                        @endforeach        
                                    @endif
                                        <input id="amount1{{$rec->orderid}}" required type="hidden" name="amount1" maxlength="9"   class="form-control" />
                             <label class=" alert-success pull-right" id="lblWord{{$rec->orderid}}"></label>
                                       <input  id="tamount1{{$rec->orderid}}" type="hidden" name="totalamount1" maxlength="9"  class="form-control" />
                             <label class=" alert-success pull-right" id="lblWord1{{$rec->orderid}}"></label>
                               
        </table>
        <button  class="btn btn-success" onclick="fill('{{$rec->orderid}}')">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>





<!-- ----------------------------------------------------MR End---------------------------------------------------------------------- -->
<div id="purchase{{$rec->orderid}}{{$rec->manu_id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: green;color:white;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="text-align: center;">Purchase Order</h4>
      </div>
      <div class="modal-body">
        <form action="{{ URL::to('/') }}/savesupplierdetails?id={{$rec->orderid}}&&mid={{$rec->manu_id}}" method="post">
            {{ csrf_field() }}
       <!--  <input type="text" class="hidden" value="" id="dtow{{$rec->orderid}}" name="dtow" >
        <input type="text" class="hidden" value="" id="dtow1{{$rec->orderid}}" name="dtow1" > -->
        <input type="text" class="hidden" value="" id="cgstpercent{{$rec->orderid}}" name="cgstpercent" >
        <input type="text" class="hidden" value="" id="sgstpercent{{$rec->orderid}}" name="sgstpercent" >
        <input type="text" class="hidden" value="" id="gstpercent{{$rec->orderid}}" name="gstpercent" >
        <input type="text" class="hidden" value="" id="igstpercent{{$rec->orderid}}" name="igstpercent" >

       <table class="table table-responsive table-striped" border="1">
                                    <?php 
                                        $po =count($rec->purchase_order); 

                                     ?> 
                                @if($po == 0)
        
                                    <tr>
                                    <td>Category :</td>
                                    <td>
                                        <?php
                                                $s1 =  $rec->main_category;
                                        ?>
                                    <select required name="category" id="supply{{$rec->orderid}}"  class="form-control" >
                                        <option value="{{ $rec->main_category }}">{{ $rec->main_category }}</option>
                                      
                                    </select>
                                </td>
                                    </tr>
                                    <tr>
                                        <td>Supplier State : </td>
                                        <td>
                                            <select id="state{{$rec->orderid}}"  class="form-control" name="state" >
          <option value="">----Select----</option>
          <option value="1"  {{ ( $rec->enq != null ? $rec->enq->spstate : '' ==1) ? 'selected' : '' }} >Karnataka</option>
          <option value="2" {{ ( $rec->enq != null ? $rec->enq->spstate  : '' ==2) ? 'selected' : '' }} >AndraPradesh</option>
          <option value="3" {{ ( $rec->enq != null ? $rec->enq->spstate : '' ==3) ? 'selected' : '' }} >TamilNadu</option>

      </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Supplier Name :</td>
                                        <td> 
                                        <select required class="form-control" id="name{{$rec->orderid}}" name="name"  onchange="getaddress('{{$rec->orderid}}')">
                                          <option>--Select--</option>
                                     @foreach($manudetails as $manu)           
                                                
                                            <option value="{{$manu->company_name}}" >{{$manu->company_name}}</option>
                                   
                                    @endforeach
                                        </select>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>Registered Office :
                                      </td>
                                      <td><textarea required type="text" class="form-control" name="address" id="address{{$rec->orderid}}" name="address" rows="5" style="resize: none;">{{$rec->supplier != null ? $rec->supplier->registered_office : ''}}</textarea></td>
                                    </tr>
                                                <tr>
                                                    <td>Shipping Address : </td>
                                                    @if($rec->manu_id == null)
                                                    <td>
                                                    <textarea required type="text" class="form-control" name="ship"  rows="5" style="resize: none;">{{$rec->req != null ? $rec->req->ship : ''}}</textarea>
                                                    </td>
                                                    @else
                                                    <td>
                                                    <textarea required type="text" class="form-control" name="ship"  rows="5" style="resize: none;">{{$rec->req != null ? $rec->req->ship : ''}}</textarea>
                                                    </td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>GST :</td>
                                                    <td><input required type="text" id="suppliergst{{$rec->orderid}}" name="gst" class="form-control" value="{{$rec->supplier != null ? $rec->supplier->gst : ''}}" ></td>
                                                </tr>
                                                <tr>
                                                    <td>Description of Goods : </td>
                                                    <td><input required type="text" name="desc" id="desc{{$rec->orderid}}" class="form-control" value="{{$rec->supplier != null ? $rec->supplier->category : ''}}" ></td>
                                                </tr>
                                                 <tr>
                                                    <td>Unit of Measurement:</td>
                                                    <td><input required type="unit" name="munit" id="munit{{$rec->orderid}}" class="form-control" value="{{$rec->cat != null ? $rec->cat->measurement_unit: ''}}" ></td>
                                                </tr>
                                    <tr>
                                        <td>Quantity : </td>
                                        <td><input required type="text" name="quantity" class="form-control" id="qu{{$rec->orderid}}" value="{{$rec->total_quantity}}" onkeyup="showthis('{{$rec->orderid}}')"></td>
                                    </tr>
                                    <tr>
                                        <td> Supplier Unit Price :</td>
                                        <td>@if($rec->price != null)
                                            <label class="alert-success">Enquiry price : {{$rec->price}}</label>
                                            <input type="hidden" value="{{$rec->price}}" id="test{{$rec->orderid}}">
                                            @endif
                                            <input required type="text" id="unitprice{{$rec->orderid}}" name="uprice" class="form-control" onkeyup="showthis('{{$rec->orderid}}')" value="" >
                                       </td>
                                    </tr>
                                    <tr>
                                          <td>Unit Price without GST :</td>
                                        <td>&nbsp;&nbsp;&nbsp;<label class=" alert-success pull-left" id="withoutgstf{{$rec->orderid}}"></label>Rs./-
                                             <input  id="withoutgstf{{$rec->orderid}}" type="hidden" name="unitwithoutgst" value="">
                                       </td>
                                    </tr>
                                    <tr>
                                        <td>Total Amount : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="display{{$rec->orderid}}"></label>/-
                                        </td>
                                    </tr>
                                    <tr>
                                        
                                        <td>CGST( <label  id="tax1{{$rec->orderid}}"></label>%) : </td>
                                        <td>

                                              &nbsp;&nbsp;&nbsp;CGST <label class=" alert-success pull-left" id="cgst{{$rec->orderid}}"></label>/-
                                        </td>
                                    </tr>
                                     <tr>
                                        <td>SGST( <label  id="tax2{{$rec->orderid}}"></label>%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;SGST <label class=" alert-success pull-left" id="sgst{{$rec->orderid}}"></label>/-
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>IGST( <label  id="tax3{{$rec->orderid}}"></label>%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;IGST <label class=" alert-success pull-left" id="igst{{$rec->orderid}}"></label>/-
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount (Including GST) :</td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withgst{{$rec->orderid}}"></label> /-
                                        </td>
                                    </tr>
                                    @else

                                                 @foreach($suppliers as $supply)
                                                 @if($supply->order_id == $rec->orderid)

                                                             <tr>
                                                <td>Category :</td>
                                                <td>
                                                <select  name="category" id="supply{{$rec->orderid}}"  class="form-control" >
                                                   
                                                    <option value="{{ $supply->category }}">{{ $supply->description }}</option>
                                                   
                                                </select>
                                            </td>
                                                </tr>
                                    <tr>
                                            <td>Supplier State : </td>
                                            <td>
                                              
                                              
                                            <select id="state{{$rec->orderid}}" name="state" class="form-control">
                                                 <option value="{{ $rec->st != null ? $rec->st->id : ''}}">{{$supply->st != null ? $supply->st->state_name : '' }}
                                               </option>
                                            </select>
                                              
                                                            
                                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Supplier Name :</td>
                                        <td> 
                                        <input required class="form-control"  name="name"  value="{{$supply->supplier_name}}"> 
                                      </td>

                                    </tr>
                                    
                                                <tr>
                                                  <td>Registered Office :
                                                  </td>
                                                  <td><textarea required type="text" class="form-control" rows="5" name="edit1" style="resize: none;">{{$supply->address}}</textarea></td>
                                                </tr>
                                                <tr>
                                                    <td>Shipping Address :
                                                  </td>
                                                    <td><textarea required type="text" class="form-control" name="edit2"  rows="5" style="resize: none;">{{$supply->ship}}</textarea></td>
                                                </tr>
                                                 <tr>
                                                    <td>GST :</td>
                                                    <td><input required type="text"  name="edit3" value="{{$supply->gst}}" class="form-control" ></td>
                                                </tr>
                                                <tr>
                                                    <td>Description of Goods : </td>
                                                    <td><input required type="text" name="edit4" class="form-control" value="{{$supply->description}}" ></td>
                                                </tr>
                                                 <tr>
                                                    <td>Unit of Measurement:</td>
                                                    <td><input required type="unit" name="edit5" class="form-control" value="{{$supply->unit}}" ></td>
                                                </tr>
                                                <tr>
                                                    <td>Quantity : </td>
                                                    <td><input required type="text" name="edit6" id="qu{{$rec->orderid}}" class="form-control"  value="{{$supply->quantity}}" onkeyup="showthis('{{$rec->orderid}}')"></td>
                                                </tr>
                                                <tr>
                                                    <td> Supplier Unit Price :</td>
                                                    <td>
                                                        <input required type="text" id="unitprice{{$rec->orderid}}" name="uprice" class="form-control" onkeyup="showthis('{{$rec->orderid}}')" value="{{$supply->unit_price}}">
                                                         <input type="hidden" value="{{$rec->price}}" id="test{{$rec->orderid}}">
                                                   </td>
                                                </tr>
                                                <tr>
                                                <td>Unit Price without GST :</td>
                                                <td>&nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withoutgstf{{$rec->orderid}}"></label>/-
                                                    <input readonly id="withoutgstf{{$rec->orderid}}" type="text" name="unitwithoutgst" value="{{$supply->unitwithoutgst}}"> 
                                               </td>
                                                </tr>
                                                <tr>
                                        <td>Total Amount : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="display{{$rec->orderid}}"></label>/-
                                               
                                                    <input readonly type="text" name="" value="{{$supply->amount}}">
                                           
                                        </td>
                                    </tr>
                                    <tr>
                                            <?php
                                    $t1 = ($supply->amount * $supply->cgstpercent)/100;
                                    $t2 = ($supply->amount * $supply->sgstpercent)/100;
                                    $t3 = ($supply->amount * $supply->igstpercent)/100;
                                            ?>
                                        
                                        <td>CGST(<label  id="tax1{{$rec->orderid}}">{{$supply->cgstpercent}}</label>%) : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;CGST <label class=" alert-success pull-left" id="cgst{{$rec->orderid}}"></label>/-
                                              <input readonly type="text" name="" value="{{$t1}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>SGST(<label  id="tax2{{$rec->orderid}}"> {{$supply->sgstpercent}}</label>%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;SGST <label class=" alert-success pull-left" id="sgst{{$rec->orderid}}"></label>/-
                                             <input readonly name="" value="{{$t2}}" type="text">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>IGST( <label  id="tax3{{$rec->orderid}}">{{$supply->igstpercent}}</label>%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;IGST <label class=" alert-success pull-left" id="igst{{$rec->orderid}}"></label>/-
                                             <input readonly type="text" name="" value="{{$t3}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount (Including GST) :</td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withgst{{$rec->orderid}}"></label> /-
                                             
                                                    <input readonly type="text" name="" value="{{$supply->totalamount}}">
                                            
                                        </td>
                                    </tr>
                                        @endif
                                        @endforeach        
                                    @endif
                                        <input id="amount{{$rec->orderid}}" required type="hidden" name="amount" maxlength="9"   class="form-control" />
                             <label class=" alert-success pull-right" id="lblWord{{$rec->orderid}}"></label>
                                       <input  id="tamount{{$rec->orderid}}" type="hidden" name="totalamount" maxlength="9"  class="form-control" />
                             <label class=" alert-success pull-right" id="lblWord1{{$rec->orderid}}"></label>
                               
        </table>
        <button  class="btn btn-success" onclick="fill('{{$rec->orderid}}')">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
                             <!-- The Modal -->
      <div class="modal" id="paymentModal{{ $rec->orderid }}">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header" style="width:100%;padding:2px;background-color:green;">
        <center>  <h4 class="modal-title" style="color: white;">Payment Details</h4></center>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        
        <!-- Modal body -->
        <div class="modal-body">
            @foreach($paymentDetails as $payment)
            @if($payment->order_id == $rec->orderid)
            <table class="table table-responsive table-striped" border="1">
              <tr>
                <td>OrderId :</td>
                <td>{{$payment->order_id}}</td>
              </tr>
              <tr>
                <td>Payment Mode :</td>
                <td>
                    {{ $payment->payment_mode }}</td>
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
                                                                <img height="350" width="350" id="project_img" src="{{ URL::to('/') }}/public/payment_details/{{ $images[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                                    </div>
                </td>
              </tr>
               <tr>
                <td>Cash Deposit Date :</td>
                <td>{{ date('d-m-Y',strtotime($payment->date))}}</td>
              </tr>
               @foreach($denoms as $denom)
              @if($denom->order_id == $rec->orderid && $denom->multiple_pay == null)
              <!-- denomination -->
              <tr>
                <td>Denomination :</td>
                <td>
                  <table class="table table-hover">
                    <tr><td>Notes</td>
                    <td>Count</td>
                    <td>Total</td>
                    </tr>
                     
                    <tr>
                      @if($denom->x2000 != null)
                      <td>2000</td>
                      <td>{{$denom->x2000}}</td>
                      <td>{{2000 * $denom->x2000}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x500 != null)
                      <td>500</td><td>{{$denom->x500}}</td>
                      <td>{{500 * $denom->x500}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x200 != null)
                      <td>200</td><td>{{$denom->x200}}</td>
                      <td>{{200 * $denom->x200}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x100 != null)
                      <td>100</td><td>{{$denom->x100}}</td>
                      <td>{{100 * $denom->x100}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x50 != null)
                      <td>50</td><td>{{$denom->x50}}</td>
                      <td>{{50 * $denom->x50}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x20 != null)
                      <td>20</td><td>{{$denom->x20}}</td>
                      <td>{{20 * $denom->x20}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x10 != null)
                      <td>10</td><td>{{$denom->x10}}</td>
                      <td>{{10 * $denom->x10}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x5 != null)
                      <td>5</td><td>{{$denom->x5}}</td>
                      <td>{{5 * $denom->x5}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x1 != null)
                      <td>1</td><td>{{$denom->x1}}</td>
                      <td>{{1 * $denom->x1}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->total != null)
                      <td>Total</td>
                      <td></td>
                      <td>{{$denom->total}}</td>
                      @endif
                    </tr>
                  </table>
                </td>
              </tr>
              <!-- end -->
              @endif
              @endforeach
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
                                        <img height="350" width="350" id="project_img" src="{{ URL::to('/') }}/public/rtgs_files/{{ $images[$i] }}" class="img img-thumbnail">
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
                 <td>Uploaded image : </td>
                <td>
                    <?php
                                                     $cimage = explode(",", $payment->cash_image);
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($cimage); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{ URL::to('/') }}/public/cash_images/{{ $cimage[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                                    </div>
                 <!-- <br><br>  
                <table>
                    <tr>
                        <form method="post" action="{{ URL::to('/') }}/uploadimage?id={{$payment->id}}">     
                        <td>     
                          <input required multiple type="file" name="payment_slip[]" id="payment_slip" accept="image/*" style="width:70%;" class="form-control input-sm" >
                      </td>
                      <td>
                          <button type="submit" class="btn btn-success btn-sm">Save</button>
                      </td>
                        </form>
                    </tr>
                </table> -->
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
              <!-- <img src="{{ URL::to('/') }}/payment_details/{{ $payment->file }}" alt="" class="img img-responsive"> -->
            @endif
            @endforeach
            @foreach($payhistory as $payment)
            @if($payment->order_id == $rec->orderid)
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
                                                                <img height="350" width="350" id="project_img" src="{{ URL::to('/') }}/public/payment_details/{{ $images[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                                    </div>
                </td>
              </tr>
               <tr>
                <td>Cash Deposit Date :</td>
                <td>{{ date('d-m-Y',strtotime($payment->date))}}</td>
              </tr>
              @foreach($denoms as $denom)
              @if($denom->order_id == $rec->orderid && $denom->multiple_pay != null)
              <!-- denomination -->
              <tr>
                <td>Denomination :</td>
                <td>
                  <table class="table table-hover">
                    <tr>
                      <td>Notes</td>
                      <td>Count</td>
                      <td>Total</td>
                    </tr>
                    <tr>
                      @if($denom->x2000 != null)
                      <td>2000</td>
                      <td>{{$denom->x2000}}</td>
                      <td>{{2000 * $denom->x2000}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x500 != null)
                      <td>500</td><td>{{$denom->x500}}</td>
                      <td>{{500 * $denom->x500}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x200 != null)
                      <td>200</td><td>{{$denom->x200}}</td>
                      <td>{{200 * $denom->x200}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x100 != null)
                      <td>100</td><td>{{$denom->x100}}</td>
                      <td>{{100 * $denom->x100}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x50 != null)
                      <td>50</td><td>{{$denom->x50}}</td>
                      <td>{{50 * $denom->x50}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x20 != null)
                      <td>20</td><td>{{$denom->x20}}</td>
                      <td>{{20 * $denom->x20}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x10 != null)
                      <td>0</td><td>{{$denom->x10}}</td>
                      <td>{{10 * $denom->x10}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x5 != null)
                      <td>5</td><td>{{$denom->x5}}</td>
                      <td>{{5 * $denom->x5}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->x1 != null)
                      <td>1</td><td>{{$denom->x1}}</td>
                      <td>{{1 * $denom->x1}}</td>
                      @endif
                    </tr>
                    <tr>
                      @if($denom->total != null)
                      <td>Total</td>
                      <td></td>
                      <td>{{$denom->total}}</td>
                      @endif
                    </tr>
                  </table>
                </td>
              </tr>
              <!-- end -->
              @endif
              @endforeach
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
                                        <img height="350" width="350" id="project_img" src="{{ URL::to('/') }}/public/rtgs_files/{{ $images[$i] }}" class="img img-thumbnail">
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
                 <td>Uploaded image : </td>
                <td>
                    <?php
                                                     $cimage = explode(",", $payment->cash_image);
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($cimage); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{ URL::to('/') }}/public/cash_images/{{ $cimage[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                                    </div>
                <!-- <br></br>
                <table>
                    <tr>
                        <form method="post" action="{{ URL::to('/') }}/uploadimage?id={{$payment->id}}">     
                        <td>     
                          <input required multiple type="file" name="payment_slip[]" id="payment_slip" accept="image/*" style="width:70%;" class="form-control input-sm" >
                      </td>
                      <td>
                          <button type="submit" class="btn btn-success btn-sm">Save</button>
                      </td>
                        </form>
                    </tr>
                </table> -->
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
                        @if($message->to_user == $rec->orderid)
                            <p
                                style="width:70%;
                                    border-style:ridge;
                                    padding:10px;
                                    border-width:2px;
                                    border-radius:10px;
                                    {{ $message->from_user == Auth::user()->id ? 'border-bottom-left-radius:0px;' : 'border-bottom-right-radius:0px;' }}
                                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);"
                                    class="text-justify {{ $message->from_user == Auth::user()->id ? 'pull-right' : 'pull-left' }}">
                                @foreach($chatUsers as $user)
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
                        <input type="hidden" name="orderId" value="{{ $rec->orderid }}">    
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
        <!-- Modal footer -->
        <div class="modal-footer" style="padding:2px">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
</div>
</td>

                        
                        <!-- <td>
                            <a href="{{URL::to('/')}}/{{$rec->orderid}}/printLPO" target="_blank" class="btn btn-sm btn-primary" >Print Invoice</a>
                        </td> -->
                        <td>
                            @if($rec->order_status == "Enquiry Confirmed" && ($rec->ostatus == "Payment Received"))
                            <div class="btn-group">
                                <!-- <a class="btn btn-xs btn-success" href="{{URL::to('/')}}/confirmOrder?id={{ $rec->orderid }}">Confirm</a> -->
                                <button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#payment{{$rec->orderid}}"> Confirm</button>
                                <button class="btn btn-xs btn-danger pull-right" data-toggle="modal" data-target="#cancel{{$rec->orderid}}" >Cancel</button>
                               Order is not confirm 
                            </div>
                            @else
                           {{ $rec->order_status }}
                            @endif
                          </td>
                        <td>
                            <?php $lll = App\MultipleSupplierInvoice::where('order_id',$rec->orderid)->pluck('lpo')->first(); ?>
                          @foreach($suppliers as $supply)
                            @if($supply->order_id == $rec->orderid)
                            {{$supply->lpo}}
                            @endif
                          @endforeach
                          {{$lll}}
                        </td>

                        <td>

                        <?php $dataf = App\MultipleSupplierInvoice::where('order_id',$rec->orderid)->count(); ?>
                        @if($rec->purchase_order == "yes"  )
                            @if($rec->manu_id != null)
                            <a href="{{ route('downloadpurchaseOrder',['id'=>$rec->orderid,'mid'=>$rec->manu_id]) }}" class="btn btn-xs" style="background-color: rgb(204, 102, 153);color:white;">Manufacturer Purchase Order</a>
                           <!--  <a href="{{ route('downloadpurchaseOrder',['id'=>$rec->orderid,'mid'=>$rec->manu_id,'mr'=>1]) }}" class="btn btn-xs" style="background-color: rgb(204, 102, 153);color:white;">MR Manufacturer Purchase Order</a> -->
                            <button style="font-size:15px" onclick="reset('{{$rec->orderid}}')" class="btn btn-default btn-xs"><img src="https://cdn4.iconfinder.com/data/icons/settings-8/24/Reset-Settings-512.png" height="20px" width="20px;"></button>
                            <form method="POST" action="{{ URL::to('/') }}/resetpo" >
                                {{ csrf_field() }}
                                <input id="resetid{{$rec->orderid}}" type="hidden" name="resetid" value="">
                            <button id="reset{{$rec->orderid}}" class="hidden" type="submit" >Submit</button>
                            </form>
                            @else
                             <a href="{{ route('downloadpurchaseOrder',['id'=>$rec->orderid]) }}" class="btn btn-xs" style="background-color: rgb(204, 102, 153);color:white;">Purchase Order</a>
                        
                           <!--  <a href="{{ route('downloadpurchaseOrder',['id'=>$rec->orderid,'mr'=>1]) }}" class="btn btn-xs" style="background-color: rgb(204, 102, 153);color:white;">MR Purchase Order</a> -->
                            

                             <button style="font-size:15px" onclick="reset('{{$rec->orderid}}')" class="btn btn-default btn-xs"><img src="https://cdn4.iconfinder.com/data/icons/settings-8/24/Reset-Settings-512.png" height="20px" width="20px;"></button>
                             <form method="POST" action="{{ URL::to('/') }}/resetpo" >
                                {{ csrf_field() }}
                                <input id="resetid{{$rec->orderid}}" type="hidden" name="resetid" value="">
                            <button id="reset{{$rec->orderid}}" class="hidden" type="submit" >Submit</button>
                            </form>
                              @endif
                          @else
                           @if($rec->ostatus == "Payment Received")
                            <?php $mmm = [40,41,52,48]; $eqs = App\FLOORINGS::where('req_id',$rec->req_id)->whereIn('category',$mmm)->first(); ?>
                           @if(count($eqs) != 0)
                              @if($dataf == 0)
                             <a href="{{URL::to('/')}}/multiplesuplier?orderid={{$rec->orderid}}&&enqid={{$rec->req_id}}" class="btn btn-xs" style="background-color: rgb(204, 102, 153);color:white;">Get Purchase order</a>
                              @else
                               <a href="{{ route('downloadpurchaseOrder1',['id'=>$rec->orderid,'mid'=>$rec->manu_id]) }}" class="btn btn-xs btn-warning" style=";color:white;">Mutiple  Purchase Order</a>
                               @endif  
                          @else
                          @if($rec->project_id == null)
                            <button type="button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#purchase{{$rec->orderid}}{{$rec->manu_id}}">Get Manufacturer Purchase Order</button>
                            
                            @else
                                <button type="button" class="btn btn-success btn-xs"  data-toggle="modal" data-target="#purchase{{$rec->orderid}}{{$rec->manu_id}}">Get Purchase Order</button>
                          @endif
                          @endif
                          @else
                          Payment Pending
                          @endif

                         @endif<br>
                       
                     </td>
                    <td>
                         @if($rec->ostatus == "Payment Received")
                          @if($dataf == 0)
                        <a href="{{URL::to('/')}}/multiplesuplier?orderid={{$rec->orderid}}&&enqid={{$rec->req_id}}" class="btn btn-xs" style="background-color: rgb(204, 102, 153);color:white;">Multiple generation of Purchase order</a>
                    </td>
                         @else
                    <a href="{{ route('downloadpurchaseOrder1',['id'=>$rec->orderid,'mid'=>$rec->manu_id]) }}" class="btn btn-xs btn-warning" style=";color:white;">Mutiple  Purchase Order</a>
                    <a href="{{URL::to('/')}}/deletemultiplesuplier?id={{$rec->orderid}}&&enqid={{$rec->req_id}}" class="btn btn-xs" style="color:white;"><img src="https://cdn4.iconfinder.com/data/icons/settings-8/24/Reset-Settings-512.png" height="20px" width="20px;">Multiple Supplier  LPO</a>
                    @endif
                    @else
                     Payment Pending

                     @endif
                                 
                       <td>

                             <div class="btn-group">
                                <!-- <a class="btn btn-xs btn-success" href="{{URL::to('/')}}/confirmOrder?id={{ $rec->orderid }}">Confirm</a> -->
                               
                                <!-- <a href="{{ URL::to('/') }}/editenq?reqId={{ $rec->id }}" class="btn btn-xs btn-primary">Edit</a> -->
                            <?php 
                                $po =count($rec->purchase_order); 

                             ?> 
                                @if($po == 0)
                                        <button disabled type="button" class="btn btn-primary btn-xs">Edit</button>
                                        <a href="{{ URL::to('/') }}/cancelOrder?id={{$rec->orderid}}" class="btn btn-xs btn-danger pull-right" >Cancel</a>
                                @else
                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#purchase{{$rec->orderid}}{{$rec->manu_id}}">Edit</button>
                                        <a href="{{ URL::to('/') }}/cancelOrder?id={{$rec->orderid}}" class="btn btn-xs btn-danger pull-right" >Cancel</a>
                                @endif
                          
                            </div>
                       </td>
                       @if($rec->brand == "CHETTINAD")
                         <td>
                                 <?php $lpos = App\MRSupplierdetails::where('order_id',$rec->orderid)->where('unit_price','!=',NULL)->pluck('lpo')->first(); ?>
                                 @if(count($lpos) > 0)   
                           <!-- <button type="button" class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#mrpurchase{{$rec->orderid}}{{$rec->manu_id}}">Edit</button> -->
                           <a type="button" class="btn btn-danger btn-xs" href="{{URL::to('/')}}/cancelMr?id={{$rec->orderid}}">Reset</a>
                               @else
                               <button type="button" class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#mrpurchase{{$rec->orderid}}{{$rec->manu_id}}">Get MR Purchase Order</button>
                               @endif
                                <br>
                                 {{$lpos}}
                              @if(count($lpos) > 0) 
                                    @if($rec->manu_id != null)
                            <a href="{{ route('mrdownloadpurchaseOrder',['id'=>$rec->orderid,'mid'=>$rec->manu_id]) }}" class="btn btn-xs" style="background-color: rgb(204, 102, 153);color:white;">Manufacturer Purchase Order</a>
                        
                             @else
                             <a href="{{ route('mrdownloadpurchaseOrder',['id'=>$rec->orderid]) }}" class="btn btn-xs" style="background-color: rgb(204, 102, 153);color:white;">Purchase Order</a>

                              @endif  
                              @endif

                         </td>
                         @else
                         <td>N/A</td>
                      @endif   
                       <td>
                           @foreach($suppliers as $supply)
                            @if($supply->order_id == $rec->orderid)
                        <?php
                         $user = App\User::where('id',$supply->generated_by)->pluck('name')->first();
                        ?>
                            {{$user}}
                            @endif
                          @endforeach
                        </td>
                       <td>
                         <?php
                         $user = App\User::where('id',$rec->confirmed_by)->pluck('name')->first();
                        ?>
                            {{$user}}
                       </td>
                    </tr>
                    @endforeach
                </tbody>    
            </table>
            <br>
            <center>{{ $view->appends(request()->query())->links()}} </center>   
        </div>
    </div>
</div>


<script type="text/javascript">
 
function reset(arg){
    var ans = confirm('Are You Sure You Want Clear This PO? Note: Changes Made Once Cannot Be Undone');
    if(ans){ 
        document.getElementById("resetid"+arg).value = arg ;
        document.getElementById("reset"+arg).form.submit();
    }
}
    function pay(arg)
    {
        var e = document.getElementById("selectPayment-"+arg);
        var strUser = e.options[e.selectedIndex].value;
        var ans = confirm('Are You Sure ? Note: Changes Made Once Cannot Be Undone');
        if(ans){
            $.ajax({
                type: 'GET',
                url: "{{URL::to('/')}}/updateampay",
                data: {payment: strUser, id: arg},
                async: false,
                success: function(response){
                    console.log(response);
                }
            });
        }
        return false;
    }

    function updateDispatch(arg)
    {
        var e = document.getElementById("selectdispatch-"+arg);
        var strUser = e.options[e.selectedIndex].value;
        var ans = confirm('Are You Sure ? Note: Changes Made Once CANNOT Be Undone');
        if(ans){
            $.ajax({
                type: 'GET',
                url: "{{URL::to('/')}}/updateamdispatch",
                data: {dispatch: strUser, id: arg},
                async: false,
                success: function(response){
                    console.log(response);    
                }
            });
        }
        return false;    
    }
    
    function confirmOrder(arg)
    {
        var ans = confirm('Are You Sure To Confirm This Order ?');
        if(ans)
        {
            $.ajax({
               type:'GET',
               url: "{{URL::to('/')}}/confirmOrder",
               data: {id : arg},
               async: false,
               success: function(response)
               {
                   console.log(response);
                       
                   $("#myordertable").load(location.href + " #myordertable>*", "");
               }
            });
        }    
    }
    
    function cancelOrder(arg)
    {
        var ans = confirm('Are You Sure To Cancel This Order ?');
        if(ans)
        {
            $.ajax({
                type:'GET',
                url: "{{URL::to('/')}}/cancelOrder",
                data: {id : arg},
                async: false,
                success: function(response)
                {
                   console.log(response);
                   $("#myordertable").load(location.href + " #myordertable>*", "");
                }
            });
        }
    }
</script>
<script type="text/javascript">
    function paymethod(){
        var input = document.getElementById("payment_mode").value;
       if(input == "RTGS"){
            document.getElementById("input1").className = "";
       }
       else if(input == "CASH"){
            document.getElementById("payment_slip").className = "";
            document.getElementById("lb1").className = "";
            document.getElementById("input1").className = "";
       }
       
    }
</script>
<script type="text/javascript">
function description(arg) {
     var z = document.getElementById('supply'+arg);
  var name = z.options[z.selectedIndex].value;
  document.getElementById('category'+arg).value = name;
}
function fill(arg){
  var x =document.getElementById('unitprice'+arg).value;
  var y =document.getElementById('test'+arg).value;

  // if(x > y){
  //   document.getElementById('unitprice'+arg).value = "";
  //   alert("Purchase Order Price should be Less Than Enquiry Price");
  // }
}
// ------------------------------------------MR-----------------------------
function showthis1(arg){

  
  var z = document.getElementById('supply1'+arg);
  var name = z.options[z.selectedIndex].value;
  var x = document.getElementById('state1'+arg);
  var state = x.options[x.selectedIndex].value;

if(name == "CEMENT" && state == "1"){
    var percent = 1.28;
    var gstvalue = 14;
    var sgstvalue = 14;
     var igstvalue = "";
}
else if(name == "CEMENT" && (state == "2" || state === "3")){
    var percent = 1.28;
    var gstvalue = 14;
    var sgstvalue = 14;
    var igstvalue = 28;
}
else if(name == "M-SAND" && state == "1"){
    var percent = 1.05;
    var gstvalue = 2.5;
    var sgstvalue = 2.5;
    var igstvalue = "";
}
else if(name == "M-SAND" && (state == "2" || state === "3")){
    var percent = 1.05;
    var gstvalue = 2.5;
    var sgstvalue = 2.5;
    var igstvalue = 5;
}
else if( (state == "1") && (name == "PLUMBING" || "STEEL" || "ELECTRICALS") ){
    var percent = 1.18;
    var gstvalue = 9;
    var sgstvalue = 9;
    var igstvalue = "";

   
}
else if( (state == "2" || state == "3") && (name == "PLUMBING" || "STEEL" || "ELECTRICALS") ){
    var percent = 1.18;
    var gstvalue = 9;
    var sgstvalue = 9;
    var igstvalue = 18;

}else if( (state == "2" || state == "3") && (name == "GGBS") ){
    var percent = 1.05;
    var gstvalue = 2.5;
    var sgstvalue = 2.5;
    var igstvalue = 5;

}else if(state == "1" && name == "GGBS" ){
    var percent = 1.05;
    var gstvalue = 2.5;
    var sgstvalue = 2.5;
    var igstvalue = 5;

}
else{
    var percent = 1.28;
    var gstvalue = 14;
    var sgstvalue = 14;
    var igstvalue = "";
}

var g1 = gstvalue;
var g2 = sgstvalue;
document.getElementById('tax11'+arg).innerHTML = g1;
document.getElementById('tax21'+arg).innerHTML = g2;
if(igstvalue != ""){
    
var g3 = igstvalue;
document.getElementById('tax31'+arg).innerHTML = g3;
}
else{
   
    var g3 = "";
document.getElementById('tax31'+arg).innerHTML = g3;
}
var x =document.getElementById('unitprice1'+arg).value;
var y = document.getElementById('qu1'+arg).value;
var withoutgst = (x /percent);
var i = parseFloat(withoutgst).toFixed(2);
var t = (withoutgst * y);
var f = Math.round(t);
var gst = (t * gstvalue)/100;
var sgt = (t * sgstvalue)/100;
var igst = (gst + sgt);
var withgst = (gst + sgt + t);
var final = Math.round(withgst);
document.getElementById('cgstpercent1'+arg).value = gstvalue;
document.getElementById('sgstpercent1'+arg).value = sgstvalue;
document.getElementById('gstpercent1'+arg).value = percent;
document.getElementById('igstpercent1'+arg).value = igstvalue;
document.getElementById('display1'+arg).innerHTML = t;
document.getElementById('cgst1'+arg).innerHTML = gst;
document.getElementById('sgst1'+arg).innerHTML = sgt;
if(igstvalue != ""){
document.getElementById('igst1'+arg).innerHTML = igst;
}
else{
    document.getElementById('igst1'+arg).innerHTML = "";
}
document.getElementById('fff'+arg).innerHTML = withoutgst;
document.getElementById('withgst1'+arg).innerHTML = withgst;
document.getElementById('withoutgst11'+arg).value = withoutgst;
document.getElementById('amount1'+arg).value = f;
document.getElementById('tamount1'+arg).value = final;
document.getElementById('tamount12'+arg).value = final;
document.getElementById('display12'+arg).value = f;
document.getElementById('cgst12'+arg).value = gst;
document.getElementById('sgst12'+arg).value = sgt;


if(igstvalue != ""){
document.getElementById('igst12'+arg).value = igst;
}
else{
    document.getElementById('igst12'+arg).value = "";
}
}

function getaddress1(arg){
  var x = document.getElementById('name1'+arg);
  var name = x.options[x.selectedIndex].value;
  var x = arg;
  $.ajax({
                    type:'GET',
                    url:"{{URL::to('/')}}/getgst",
                    async:false,
                    data:{name : name , x : x},
                    success: function(response)
                    {
                       
                                                 for(var i=0;i<response.length;i++)
                        {
                           var text = response[i].cin;
                        }
                        var id = response.id;
                        var name = response.res;
                        var gst = response.gst;
                        var cat = response.category;
                        var unit = response.unit;
                         document.getElementById('address1'+id).value = name; 
                         document.getElementById('suppliergst1'+id).value = gst;
                         document.getElementById('desc1'+id).value = cat;
                         document.getElementById('munit1'+id).value = unit; 
                    }
                });
            }




// ----------------------------MR End------------------------------------------------------------------
function showthis(arg){

  
  var z = document.getElementById('supply'+arg);
  var name = z.options[z.selectedIndex].value;
  var x = document.getElementById('state'+arg);
  var state = x.options[x.selectedIndex].value;

if(name == "CEMENT" && state == "1"){
    var percent = 1.28;
    var gstvalue = 14;
    var sgstvalue = 14;
     var igstvalue = "";
}
else if(name == "CEMENT" && (state == "2" || state === "3")){
    var percent = 1.28;
    var gstvalue = 14;
    var sgstvalue = 14;
    var igstvalue = 28;
}
else if(name == "M-SAND" && state == "1"){
    var percent = 1.05;
    var gstvalue = 2.5;
    var sgstvalue = 2.5;
    var igstvalue = "";
}
else if(name == "M-SAND" && (state == "2" || state === "3")){
    var percent = 1.05;
    var gstvalue = 2.5;
    var sgstvalue = 2.5;
    var igstvalue = 5;
}
else if( (state == "1") && (name == "PLUMBING" || "STEEL" || "ELECTRICALS") ){
    var percent = 1.18;
    var gstvalue = 9;
    var sgstvalue = 9;
    var igstvalue = "";

   
}
else if( (state == "2" || state == "3") && (name == "PLUMBING" || "STEEL" || "ELECTRICALS") ){
    var percent = 1.18;
    var gstvalue = 9;
    var sgstvalue = 9;
    var igstvalue = 18;

}else if( (state == "2" || state == "3") && (name == "GGBS") ){
    var percent = 1.05;
    var gstvalue = 2.5;
    var sgstvalue = 2.5;
    var igstvalue = 5;

}else if(state == "1" && name == "GGBS" ){
    var percent = 1.05;
    var gstvalue = 2.5;
    var sgstvalue = 2.5;
    var igstvalue = 5;

}
else{
    var percent = 1.28;
    var gstvalue = 14;
    var sgstvalue = 14;
    var igstvalue = "";
}

var g1 = gstvalue;
var g2 = sgstvalue;
document.getElementById('tax1'+arg).innerHTML = g1;
document.getElementById('tax2'+arg).innerHTML = g2;
if(igstvalue != ""){
    
var g3 = igstvalue;
document.getElementById('tax3'+arg).innerHTML = g3;
}
else{
   
    var g3 = "";
document.getElementById('tax3'+arg).innerHTML = g3;
}
var x =document.getElementById('unitprice'+arg).value;
var y = document.getElementById('qu'+arg).value;
var withoutgst = (x /percent);
var i = parseFloat(withoutgst).toFixed(2);
var t = (withoutgst * y);
var f = Math.round(t);
var gst = (t * gstvalue)/100;
var sgt = (t * sgstvalue)/100;
var igst = (gst + sgt);
var withgst = (gst + sgt + t);
var final = Math.round(withgst);
document.getElementById('cgstpercent'+arg).value = gstvalue;
document.getElementById('sgstpercent'+arg).value = sgstvalue;
document.getElementById('gstpercent'+arg).value = percent;
document.getElementById('igstpercent'+arg).value = igstvalue;
document.getElementById('display'+arg).innerHTML = t;
document.getElementById('cgst'+arg).innerHTML = gst;
document.getElementById('sgst'+arg).innerHTML = sgt;
if(igstvalue != ""){
document.getElementById('igst'+arg).innerHTML = igst;
}
else{
    document.getElementById('igst'+arg).innerHTML = "";
}

document.getElementById('amount'+arg).value = f;
document.getElementById('tamount'+arg).value = final;
document.getElementById('withgst'+arg).innerHTML = withgst;
document.getElementById('withoutgstf'+arg).innerHTML = withoutgst;
document.getElementById('withoutgstf'+arg).value = withoutgst;
}

function gothr(arg){
  var x =document.getElementById('unit'+arg).value;
  var y =document.getElementById('test1'+arg).value;
  var z = document.getElementById('istate'+arg);
  var name = z.options[z.selectedIndex].value;
  if(x < y){
      document.getElementById('unit'+arg).value = "";
      alert("Mamahome Price Cannot be Less Than Enquiry Price");
  }
  else if(name == "--select--"){
            window.alert("You Have Not Selected State");
    }
    else{
      document.getElementById('sub'+arg).submit();
    }
}

function getaddress(arg){
  var x = document.getElementById('name'+arg);
  var name = x.options[x.selectedIndex].value;
  var x = arg;
  $.ajax({
                    type:'GET',
                    url:"{{URL::to('/')}}/getgst",
                    async:false,
                    data:{name : name , x : x},
                    success: function(response)
                    {
                       
                                                 for(var i=0;i<response.length;i++)
                        {
                           var text = response[i].cin;
                        }
                        var id = response.id;
                        var name = response.res;
                        var gst = response.gst;
                        var cat = response.category;
                        var unit = response.unit;
                         document.getElementById('address'+id).value = name; 
                         document.getElementById('suppliergst'+id).value = gst;
                         document.getElementById('desc'+id).value = cat;
                         document.getElementById('munit'+id).value = unit; 
                    }
                });
            }
</script>
<script type="text/javascript">
function getsuppliername(arg) {
  var x = document.getElementById('supply'+arg);
  var name = x.options[x.selectedIndex].value;
  var y = document.getElementById('state'+arg);
  var state = y.options[y.selectedIndex].value;
  var x = arg;
  $.ajax({
                    type:'GET',
                    url:"{{URL::to('/')}}/getsupplier",
                    async:false,
                    data:{name : name , x : x , state : state},
                    success: function(response)
                    {

                        
                       var id = response[0]['id'];
                        
                        var html = "<option value='' disabled selected>---Select---</option>";
                        for(var i=0; i< response[0]['supplier'].length; i++)
                        {
                            html += "<option value='"+response[0]['supplier'][i]['company_name']+"'>"+response[0]['supplier'][i]['company_name']+"</option>";
                        }
                        document.getElementById('name'+id).innerHTML = html;
                    }
                });
    }
</script>
 <script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
     background-color: #00acd6 
});
</script>
@endsection
