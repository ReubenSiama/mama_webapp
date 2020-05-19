@extends('layouts.app')
@section('content')
<?php $url = Helpers::geturl() ?>
<div class="container-fluid">
  <div  class="col-md-3"  style="border:2px solid gray;height: 50%;">
    <center><label>Payment Details</label></center>
    Order Id : {{$id}}<br>
    Customer Total Amount : {{$total}}<br><br>
    @if($payments != null)
        <?php
          $pending = ( $total - $payments->totalamount);
        ?>
          Payment Mode : {{$payments->payment_mode}}<br>
          Amount Received : {{$payments->totalamount}}RS/-
          <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal1{{$payments->id}}">Edit</button>
          <div id="myModal1{{$payments->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit</h4>
      </div>
      <div class="modal-body">
        <form action="{{URL::to('/')}}/updatepaymentmode1" method="post" enctype="multipart/form-data">
             {{ csrf_field() }}
          <input type="hidden" name="id" value="{{$payments->id}}">
         <table class="table table-responsive table-striped" border="1">
              <tr>
                <td>OrderId :</td>
                <td>{{$payments->order_id}}</td>
              </tr>
              <tr>
                <td>Payment Mode :</td>
                <td><input type="text" name="paymentmode" value="{{ $payments->payment_mode }}" readonly></td>
              </tr>
              @if($payments->payment_mode == "CASH")
              <tr>
                <td>Cash Image :</td>
                <td>
                  <?php
                                                     $images = explode(",", $payments->file );
                                                       
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($images); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{ $url}}/cash_images/{{ $images[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                                       <input type="file" name="image" class="form-control" accept="image/*">
                                                    </div>
                </td>
              </tr>
               <tr>
                <td>Cash Deposit Date :</td>
                <td> <input type="text" name="cashdepositdate" value="{{ date('d-m-Y',strtotime($payments->date))}}"></td>
              </tr>
             
              @endif
              @if($payments->payment_mode == "RTGS")
              <tr>
                  <td>RTGS Image: </td>
                  <td>
                      <?php
                             $images = explode(",", $payments->rtgs_file );
                            ?>
                           <div class="col-md-12">
                               @for($i = 0; $i < count($images); $i++)
                                   <div class="col-md-3">
                                        <img height="350" width="350" id="project_img" src="{{ $url }}/rtgs_files/{{ $images[$i] }}" class="img img-thumbnail">
                                   </div>
                               @endfor
                               <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                  </td>
              </tr>
               <tr>
                <td>Date :</td>
                <td><input type="text" name="date" value="{{date('d-m-Y',strtotime($payments->date))}}"></td>
              </tr>
              <tr>
                <td>Reference Number :</td>
                <td><input type="text" value="{{$payments->account_number}}" name="refnumber"><br></td>
              </tr>
              <tr>
                <td>Branch Name :</td>
                <td><input type="text" name="branchname" value="{{$payments->branch_name}}"><br></td>
              </tr>
              @endif
              @if($payments->payment_mode == "CHEQUE")
               <tr>
                <td>Cheque Deposit Date :</td>
                <td><input type="text" name="ChequeDeposit" value="{{date('d-m-Y',strtotime($payments->date))}}"></td>
              </tr>
              <tr>
                <td>Cheque Number :</td>
                <td><input type="text" name="checqnumber" value="{{$payments->cheque_number}}">
                </td>
                 <td>
                    <?php
                                                     $cimage = explode(",", $payments->cheque_image);
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($cimage); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{ $url}}/cheque_images/{{ $cimage[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                                    </div>
                </td> 
              </tr>
              @endif
              @if($payments->payment_mode == "CASH IN HAND")
              <tr>
                <td>Cash Holder Name : </td>
                <td>{{$payments->user != null?$payments->user->name :''}}</td>
              </tr>
                 <tr>
                <td> Cash Received Date :</td>
                <td><input type="text" name="cashrecive" value="{{date('d-m-Y',strtotime($payments->date))}}"></td>
              </tr>
              <tr>
                 <td>Uploaded image : </td>
                <td>
                    <?php
                                                     $cimage = explode(",", $payments->cash_image);
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($cimage); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{ $url }}/cash_images/{{ $cimage[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                                    </div>
                </td> 
              </tr>
              @endif
              <tr>
                <td>Total Amount :</td>
                <td><input type="text" name="totalamount" value="{{$payments->totalamount}}"></td>
              </tr>
             
              <tr>
                <td>Note :</td>
                <td><input type="text" name="note" value="{{$payments->payment_note}}"></td>
              </tr>
            </table>
           <center> <button type="submit" class="btn btn-success btn-sm">submit</button></center>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
         <br>
    @endif
          <?php 
                $amt = App\OrderExpenses::where('order_id',$id)->sum('amount');
                
          ?>
    @if($payhistory != null)
      @foreach($payhistory as $payment)
      <div class="container-fluid"> 
          Payment Mode : {{$payment->payment_mode}}<br>
          Amount Received : {{$payment->totalamount}}RS/-
          <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal{{$payment->id}}">Edit</button>

<!-- Modal -->
<div id="myModal{{$payment->id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit</h4>
      </div>
      <div class="modal-body">
        <form action="{{URL::to('/')}}/updatepaymentmode" method="post" enctype="multipart/form-data">
             {{ csrf_field() }}
          <input type="hidden" name="id" value="{{$payment->id}}">
         <table class="table table-responsive table-striped" border="1">
<?php $url = Helpers::geturl() ?>

              <tr>
                <td>OrderId :</td>
                <td>{{$payment->order_id}}</td>
              </tr>
              <tr>
                <td>Payment Mode :</td>
                <td><input type="text" name="paymentmode" value="{{ $payment->payment_mode }}" readonly></td>
              </tr>
              @if($payment->payment_mode == "CASH")
              <tr>
                <td>Cash Image :</td>
                <td>
                  <?php
                                                     $images = explode(",", $payment->file );
                                                    ?>
                                                   <div class="col-md-12">
                                                       @for($i = 0; $i < count($images); $i++)
                                                           <div class="col-md-3">
                                                                <img height="350" width="350" id="project_img" src="{{$url}}/payment_details/{{ $images[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                    <input type="file" name="image" class="form-control" accept="image/*">

                                                    </div>
                </td>
              </tr>
               <tr>
                <td>Cash Deposit Date :</td>
                <td> <input type="text" name="cashdepositdate" value="{{ date('d-m-Y',strtotime($payment->date))}}"></td>
              </tr>
             
              @endif
              @if($payment->payment_mode == "RTGS")
              <tr>
                  <td>RTGS Image: </td>
                  <td>
                      <?php
                             $images = explode(",", $payment->rtgs_file );
                              $url = Helpers::geturl();

                            ?>
                           <div class="col-md-12">
                               @for($i = 0; $i < count($images); $i++)
                                   <div class="col-md-3">
                                        <img height="350" width="350" id="project_img" src="{{$url}}/rtgs_files/{{ $images[$i] }}" class="img img-thumbnail">
                                   </div>
                               @endfor
                                    <input type="file" name="image" class="form-control" accept="image/*">

                            </div>
                  </td>
              </tr>
               <tr>
                <td>Date :</td>
                <td><input type="text" name="date" value="{{date('d-m-Y',strtotime($payment->date))}}"></td>
              </tr>
              <tr>
                <td>Reference Number :</td>
                <td><input type="text" value="{{$payment->account_number}}" name="refnumber"><br></td>
              </tr>
              <tr>
                <td>Branch Name :</td>
                <td><input type="text" name="branchname" value="{{$payment->branch_name}}"><br></td>
              </tr>
              @endif
              @if($payment->payment_mode == "CHEQUE")
               <tr>
                <td>Cheque Deposit Date :</td>
                <td><input type="text" name="ChequeDeposit" value="{{date('d-m-Y',strtotime($payment->date))}}"></td>
              </tr>
              <tr>
                <td>Cheque Number :</td>
                <td><input type="text" name="checqnumber" value="{{$payment->cheque_number}}">
                </td>
              </tr>
              <tr>
                  <td>Cheque Image: </td>
                  <td>
                      <?php
                             $images = explode(",", $payment->cheque_image );
                            ?>
                           <div class="col-md-12">
                               @for($i = 0; $i < count($images); $i++)
                                   <div class="col-md-3">
                                        <img height="350" width="350" id="project_img" src="{{$url}}/cheque_images/{{ $images[$i] }}" class="img img-thumbnail">
                                   </div>
                               @endfor
                                    <input type="file" name="image" class="form-control" accept="image/*">

                            </div>
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
                <td><input type="text" name="cashrecive" value="{{date('d-m-Y',strtotime($payment->date))}}"></td>
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
                                                                <img height="350" width="350" id="project_img" src="{{$url}}/cash_images/{{ $cimage[$i] }}" class="img img-thumbnail">
                                                           </div>
                                                       @endfor
                                    <input type="file" name="image" class="form-control" accept="image/*">

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
                <td><input type="text" name="totalamount" value="{{$payment->totalamount}}"></td>
              </tr>
              <!-- <tr>
                <td>Delivery Charges :</td>
                <td>{{$payment->damount}}/-</td>
              </tr> -->
              <tr>
                <td>Note :</td>
                <td><input type="text" name="note" value="{{$payment->payment_note}}"></td>
              </tr>
            </table>
           <center> <button type="submit" class="btn btn-success btn-sm">submit</button></center>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
        </div> 
      @endforeach
    OrderExpenses Amount : {{$amt}}/-<br>
      <?php
          $var = [];
          for($i= 0 ; $i<sizeof($payhistory) ; $i++){

            $amt =$payhistory[$i]['totalamount'];
            array_push($var,$amt);

          }
          $s = array_sum($var);
            if($payments != null){

          $pending = ( $total - $payments->totalamount - $s);
            }
        ?>
    @endif
     @if($payments != null)
        @if($pending == 0)
             <label class="alert-success">Payment Completed</label>
         @else
        <label class="alert-danger">Pending Amount : {{$pending}}RS/-</label>
        @endif
     @endif
  </div>

<div class="col-md-8">
<div class="panel panel-primary">
    <div class="panel-heading" align="center">Payment Method</div>
    <div class="panel-body">
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home"><b>Cash</b></a></li>
    <li><a data-toggle="tab" href="#menu1"><b>RTGS/NEFT</b></a></li>
    <!-- <li><a data-toggle="tab" href="#menu2"><b>Cheque</b></a></li> -->
    <!-- <li><a data-toggle="tab" href="#menu3"><b>Cash In Hand</b></a></li> -->
  </ul>

            <div class="tab-content">
              <br><br>
              <div id="home" class="tab-pane fade in active">
                <!-- radio select -->
<script type="text/javascript">
function ShowHideDiv(){
        var chkYes = document.getElementById("chkYes");
        var dvPassport = document.getElementById("cashdep");
        dvPassport.style.display = chkYes.checked ? "block" : "none";
        var chkYes = document.getElementById("chkNo");
        var dvPassport = document.getElementById("cashcol");
        dvPassport.style.display = chkYes.checked ? "block" : "none";
    }
function ShowHideDiv1(){
        var chkYes = document.getElementById("chkNo");
        var dvPassport = document.getElementById("cashcol");
        dvPassport.style.display = chkYes.checked ? "block" : "none";
        var chkYes = document.getElementById("chkYes");
        var dvPassport = document.getElementById("cashdep");
        dvPassport.style.display = chkYes.checked ? "block" : "none";
}
</script>
<label for="chkYes">
    <input type="radio" id="chkYes" name="chkPassPort" onclick="ShowHideDiv()" />
    Cash Deposit
</label>
<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
<label for="chkNo">
    <input type="radio" id="chkNo" name="chkPassPort" onclick="ShowHideDiv1()" />
    Cash Collection
</label>
<hr/>
<!-- end -->  <div id="cashdep" style="display: none">
                <form action="{{ URL::to('/') }}/savePaymentDetails?id= {{$id}}&&mid={{$mid}}&&pid={{$pid}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input name="method" class="hidden" value="CASH">
               <table class="table table-responsive table-striped" border="1" >
                        <tr>
                          <td>Bank Name :</td>
                        <td><input  class="form-control" name="bankname" type="text" placeholder="Bank Name"></td>
                        </tr>
                        <tr>
                        <td>Branch Name :</td>
                        <td><input  class="form-control" name="branchname" type="text" placeholder="Branch Name"></td>
                        </tr>
                        <tr>
                        <td>Cash Deposit Date :</td>
                        <td><input required class="form-control" type="date" name="date"></td>
                        </tr>   
                        <tr>
                        <td>Cash Image :</td>
                        <td><input required multiple type="file" name="payment_slip[]" id="payment_slip" accept="image/*" class="form-control input-sm" ></td>
                        </tr>
                        
                        <tr>
                          <td>Denomination :</td>
                          <td>
                            <!-- denomination -->
<table class="table table-hover" border="1">
<thead>
<tr>
<th class="text-left">Notes</th>
<th  class="text-left">Count</th>
<th  class="text-left">Amount</th>
</tr>
</thead>
<tbody>
<tr>
<td class="text-left" >2000X</td>
<td><input style="width: 100%" type="text" class="noteCount" id="x2000" name="INR2000"></td>
<td class="grand" id="t2000" name="t2000">0</td> </tr>
<tr>
<td class="text-left" >500X</td>
<td> <input style="width: 100%" type="text" class="noteCount" id="x500" name="INR500"></td>
<td class="grand" id="t500" name="t500">0</td>
</tr>
<tr>
<td class="text-left" >200X</td>
<td><input style="width: 100%" type="text" class="noteCount" id="x200" name="INR200"></td>
<td class="grand" id="t200">0</td>
</tr>
<tr>
<td class="text-left" >100X</td>
<td><input style="width: 100%" type="text" class="noteCount" id="x100" name="INR100"></td>
<td class="grand" id="t100">0</td>
</tr>
<tr>
<td class="text-left" >50X</td>
<td><input style="width: 100%" type="text" class="noteCount" id="x50" name="INR50"></td>
<td class="grand" id="t50">0</td>
</tr>
<tr>
<td class="text-left" >20X</td>
<td><input style="width: 100%" type="text" class="noteCount" id="x20" name="INR20"></td>
<td class="grand" id="t20">0</td>
</tr>
<tr>
<td class="text-left" >10X</td>
<td><input style="width: 100%" type="text" class="noteCount" id="x10" name="INR10"></td>
<td class="grand" id="t10">0</td>
</tr>
<tr>
<td class="text-left" >5X</td>
<td><input style="width: 100%" type="text" class="noteCount" id="x5" name="INR5"></td>
<td class="grand" id="t5">0</td>
</tr>
<tr>
<td class="text-left" >2X</td>
<td><input style="width: 100%" type="text" class="noteCount" id="x2" name="INR2"></td>
<td class="grand" id="t2">0</td>
</tr>
<tr>
<td class="text-left" >1X</td>
<td><input style="width: 100%" type="text" class="noteCount" id="x1" name="INR1"></td>
<td class="grand" id="t1">0</td>
</tr>
<tr>
<td class="text-left"><label>Total</label></td>
<td class="text-left"></td>
<td ><input style="width: 50%" readonly class="text-left" contenteditable="false" id="grandTotal" name="total"></td>
</tr>
</tbody>
</table>

                          </td>
                        </tr>
                        <tr>
                        <td>Deposit Amount :</td>
                        <td><input required class="form-control" name="totalamount" type="text" placeholder="Enter Amount" id="yadav"></td>
                        </tr>
                       <!--  <tr>
                          <td>Delivery charges(Driver) :</td>
                          <td><input class="form-control" name="damount" type="text" placeholder="Enter Amount"></td>
                        </tr> -->
                        <tr>
                          <td>Notes:</td>
                          <td><textarea name="notes" style="resize: none;"  cols="2" rows="2" placeholder="Notes" class="form-control"></textarea></td>
                        </tr>
                </table>
                        <button type="submit" class="form-control btn btn-success">Save</button>
                      </form>
              </div>
                <div id="cashcol" style="display: none">
                  <form action="{{ URL::to('/') }}/savePaymentDetails?id= {{$id}}&&mid={{$mid}}&&pid={{$pid}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                  <input name="method" class="hidden" value="CASH">
                <table class="table table-responsive table-striped" border="1">
                  <tr>
                    <td>Cash Collected Name:</td>
                        <td>
                          <select class="form-control" name="name">
                          <option value="">--Select--</option>
                          @foreach($users as $user)
                          <option {{ isset($_GET['user']) ? $_GET['user'] == $user->id ? 'selected' : '' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                          @endforeach
                        </select>
                        </td>
                  </tr>
                <tr>
                          <td>Cash Recieved Date :</td>
                        <td><input required class="form-control" name="date" type="date"></td>
                </tr>
                 <tr>
                          <td>Payment Confirm image :</td>
                          <td><input multiple type="file" name="cash_image[]" accept="image/*" class="form-control input-sm" ></td>
                        </tr>
                <tr>
                          <td>Total Amount :</td>
                        <td><input required class="form-control" name="totalamount" type="number"></td>
                </tr>
               <!--  <tr>
                          <td>Delivery charges(Driver) :</td>
                          <td><input class="form-control" name="damount" type="number" placeholder="Enter Amount"></td>
                </tr> -->
                <tr>
                          <td>Notes:</td>
                          <td><textarea name="notes" style="resize: none;" cols="2" rows="2" placeholder="Notes" class="form-control"></textarea></td>
                </tr>
              </table>
              <button type="submit" class="form-control btn btn-success">Save</button>
              </form>
                </div>
              </div>
              <div id="menu1" class="tab-pane fade">
                <form action="{{ URL::to('/') }}/savePaymentDetails?id= {{$id}}&&mid={{$mid}}&&pid={{$pid}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input name="method" class="hidden" value="RTGS">
                <table class="table table-responsive table-striped" border="1">
                        <tr>
                        <td>Refernce Number/Account Number :</td>
                        <td><input required class="form-control" type="texct" name="accnum" placeholder="Account Number"></td>
                        </tr>
                        <tr>
                        <td>Branch Name/Center :</td>
                        <td><input required name="accname" type="text" class="form-control input-sm" placeholder="Branch Name" ></td>
                        </tr>
                        <tr>
                          <td>Transaction Date :</td>
                        <td><input required class="form-control" name="date" type="date"></td>
                        </tr>
                        <tr>
                          <td>Cash Confirm Image From Bank</td>
                          <td><input multiple type="file" name="rtgs_file[]" id="payment_slip" accept="image/*" class="form-control input-sm" ></td>
                        </tr>
                        <tr>
                          <td>RTGS Amount :</td>
                          <td><input required class="form-control" name="totalamount" type="text" placeholder="Enter Amount"></td>
                        </tr>
                       <!--  <tr>
                          <td>Delivery charges(Driver) :</td>
                          <td><input class="form-control" name="damount" type="text" placeholder="Enter Amount"></td>
                        </tr> -->
                        <tr>
                          <td>Notes:</td>
                          <td><textarea name="notes" style="resize: none;" cols="2" rows="2" placeholder="Notes" class="form-control"></textarea></td>
                        </tr>
                </table>
                        <button type="submit" class="form-control btn btn-success">Save</button>
              </form>
              </div>
              <div id="menu2" class="tab-pane fade">
                <form action="{{ URL::to('/') }}/savePaymentDetails?id= {{$id}}&&mid={{$mid}}&&pid={{$pid}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input name="method" class="hidden" value="CHEQUE">
                <table class="table table-responsive table-striped" border="1">  
                        <tr>
                          <td>Bank Name :</td>
                        <td><input  class="form-control" name="bankname" type="text" placeholder="Bank Name"></td>
                        </tr>
                        <tr>
                        <td>Branch Name :</td>
                        <td><input class="form-control" name="branchname" type="text" placeholder="Branch Name"></td>
                        </tr>
                        <tr>
                          <td>Cheque Number :</td>
                          <td><input required class="form-control" name="cheque_num" type="text" placeholder="Cheque Number"></td>
                        </tr>
                        <tr>
                          <td>Cheque Received Date :</td>
                        <td><input required class="form-control" name="date" type="date"></td>
                        </tr>
                        <tr>
                          <td>Cheque Amount :</td>
                          <td><input required class="form-control" name="totalamount" type="text" placeholder="Enter Amount"></td>
                        </tr>
                         <tr>
                          <td>Cheque image :</td>
                          <td><input required class="form-control" name="cheque_image" type="file" placeholder="Enter Amount"></td>
                        </tr>
                       <!--  <tr>
                          <td>Delivery charges(Driver) :</td>
                          <td><input class="form-control" name="damount" type="text" placeholder="Enter Amount"></td>
                        </tr> -->
                        <tr>
                          <td>Notes:</td>
                          <td><textarea name="notes" style="resize: none;" cols="2" rows="2" placeholder="Notes" class="form-control"></textarea></td>
                        </tr>
                </table>
                        <button type="submit" class="form-control btn btn-success">Save</button>
                </form>
              </div>
             <!--  <div id="menu3" class="tab-pane fade">
              <form action="{{ URL::to('/') }}/savePaymentDetails?id= {{$id}}&&mid={{$mid}}&&pid={{$pid}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                
            </form>
              </div> -->
            </div>
          </div>
    </div>
  </div>
  
</div>
@if(session('Success'))
<script>
    swal("Success","{{ session('Success') }}","success");
</script>
@endif
<script>
$(document).ready(function(){
$('.noteCount').change(function() {
//alert("Content " +this.id);
countId=this.id;
denomination=countId.substring(1,countId.length);
amountId="t"+denomination;
amountEle=document.getElementById(amountId);
x=parseFloat(this.value)*parseFloat(denomination);
amountEle.innerHTML=x;
refreshTotal();
});
function refreshTotal() {
sum=0;
$('.grand').each(function( index ) {sum+=parseFloat(this.innerHTML);});
grandTotal=document.getElementById("grandTotal");
var yadav = document.getElementById("yadav");
yadav.value = sum;

grandTotal.value=sum;
};
});
</script>
@endsection
