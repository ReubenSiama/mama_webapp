 @extends('layouts.app')
<style>
table {
  border-collapse: collapse;
  border-spacing: 0;
 /* width: 100%;*/
  border: 1px solid #ddd;
}

/*th, td {
  text-align: left;
  padding: 8px;
}*/

tr:nth-child(even){background-color: #f2f2f2}
</style>
@section('content') 
<?php $url = Helpers::geturl(); ?>

<div class="container">
  <span class="pull-right"> @include('flash-message')</span>
  
<div class="panel panel-default" style="border-color:green;"> 
 <div class="panel-heading text-center" style="background-color: #5cb85c;color:white;padding:10px;">
  <a href="{{URL::to('/')}}/tpwithuser" class="btn btn-sm btn-primary pull-left" style="margin-top:-5px;">Back</a>
  <b>Invoice Details   ({{count($products)}}) Orders &nbsp;&nbsp;&nbsp;Month ({{$month}}) &nbsp;&nbsp;&nbsp;Year ({{$year}})
   <div class="pull-right" style="margin-top:-7px;">
        <form action="{{URL::to('/')}}/getmonthtpwithuserreport" method="get">
             <input type="hidden" name="month" value="{{$month}}">
             <input type="hidden" name="year" value="{{$year}}">

          <?php 
          $d = [1,2]; 
          $users = App\User::where('department_id','!=',10)->whereIn('department_id',$d)->get(); 

           ?>
              <select class="form-control" name="user" onchange="this.form.submit()">
                    <option value="">--Select Employee--</option>
                     @foreach($users as $user)
                       <option value="{{$user->id}}">{{$user->name}}</option>
                       @endforeach
              </select>
        </form>

   </div>
 
 </div>
</div>
</b>
</div>
 <div style="overflow-x:auto;">
             
               <table class="table table-responsive table-striped" border="1" >
                <thead style="background-color:#8c9eff;">
                  <th>SLNO</th>
                  <th>MH INVOICE NO</th>
                  <th>MH ORDER ID</th>
                  <th>MATERIAL </th>
                  <th>MH INVOICE AMOUNT</th>
                  <th>SUPPLIER INVOICE VALUE</th>
                  <th> TP WITH GST </th>
                  <th>GST %</th>
                  <th>GST AMOUNT </th>
                  <th> TP WITHOUT GST</th>
                  <th>OTHER EXPENSES</th>
                  <th>OTHER EXPENSE REMARKS</th>
                   <th>COMAPANY EXPENSES</th>
                   <th>COMAPANY EXPENSES REMARKS</th>
                  <th>FINAL TP</th>
                  <th>##</th>
                 
                 

                </thead>
                <?php
                    $mhamount =[]; 
                    $spamounts = [];  
                    $tpwithgsts=[];
                    $gsts = [];
                    $gstamounts = [];
                    $tpwithoutgsts =[];
                    $ot = [];
                    $finaltps=[];
                    $cps=[];

                ?>
                <tbody>
                  <?php $i=1; ?>
                  @foreach($products as $items)
                    <tr>
                      <td> {{$i++}}</td>
                       <td>{{$items->invoiceno}}</td>
                       <td>{{$items->order_id}}</td>
                        <td>{{$items->category}}</td> 
                        <td>{{$items->mhInvoiceamount}} <?php array_push($mhamount, $items->mhInvoiceamount); ?></td> 
                        <td><?php 
                        $spamount = App\SupplierInvoicedata::where('order_id',$items->order_id)->pluck('supplierinvoiceamount')->first(); 
                            if($spamount==null){
                               $spamount =0;
                            }else{
                              $spamount =$spamount;
                            }
                           array_push($spamounts, $spamount);
                        ?>
                          {{$spamount}}
                        </td>
                       
                        <td>
                          <?php $tpwithgst = ($items->mhInvoiceamount - $spamount); 

                            array_push($tpwithgsts, $tpwithgst); 
                          ?>
                                {{$tpwithgst}}
                        </td>
                         <td>
                          <?php 
                                  if($items->custmodeofgst == "CGST & SGST"){

                                      $gst = ($items->customergstpercent * 2);
                                  }else{
                                    $gst = ($items->customergstpercent);
                                  }
          
                              array_push($gsts, $gst); 

                          ?>
                            {{$gst}}
                          
                        </td>
                        <td>
                            <?php 
                            $gstamount = ($tpwithgst * $gst)/100; 
                                array_push($gstamounts, $gstamount);
                            ?>

                              {{$gstamount}}
                        </td>
                        <td>
                           <?php $tpwithoutgst = ($tpwithgst - $gstamount); 
                                array_push($tpwithoutgsts,$tpwithoutgst);
                           ?>
                             {{$tpwithoutgst}}
                        </td>
                         <td><?php
                                $deliver =App\DeliveryDetails::where('order_id',$items->order_id)->first();
                                 if(count($deliver) > 0){
                                   $other = $deliver->other;
                                   $otherre =$deliver->remark;  
                                   array_push($ot, $other);

                                 }else{
                                   $other = 0;
                                   $otherre ="";  
                                 } 
                        ?>
                          {{$other}}</td>
                        <td>{{$otherre}}</td>
                        <td>{{$items->otheramount}}
                              
                           </td>
                        <td>{{$items->otherremark}}</td>
                        <td>
                          <?php
                                  if($items->otheramount == null){
                                     $cp = 0;
                                  } else{
                                    $cp = $items->otheramount;
                                  }
                                  array_push($cps, $cp);
                               $finaltp = ($tpwithoutgst) - ($other) - ($cp);
                                 array_push($finaltps, $finaltp);
                            ?> 
                           {{$finaltp}}
                        </td>
                        <td>
                         <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal{{$items->invoiceno}}">Add Other Expenses</button>
                        </td>
                    </tr>
                    @endforeach
                    <tr style="background-color:#B0B0B0 ">
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>{{array_sum($mhamount)}} </td>
                      <td>{{array_sum($spamounts)}} </td>
                      <td>{{array_sum($tpwithgsts)}} </td>
                      <td></td>
                      <td>{{array_sum($gstamounts)}} </td>
                      <td>{{array_sum($tpwithoutgsts)}} </td>
                      <td>{{array_sum($ot)}} </td>
                      <td></td>
                      <td>{{array_sum($cps)}}</td>
                      <td></td>
                      <td>{{array_sum($finaltps)}} </td>
                      <td>##</td>

</tr>




                </tbody>
               
               </table>
 @foreach($products as $items)
  <div class="modal fade" id="myModal{{$items->invoiceno}}" role="dialog" >
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style=" background-color:#FFAB00">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Other Expenses Details</h4>
        </div>
        <div class="modal-body">
            
        <form   id="yes{{$items->invoiceno}}">
           <input type="hidden" name="invoiceno" value="{{$items->invoiceno}}" id="invoiceno{{$items->invoiceno}}">
            <table class="table" border="1">
               <tr>
                 <td>Amount</td>
                 <td>:</td>
                 <td><input type="text" name="amount" class="form-control" id="amount{{$items->invoiceno}}"></td>
               </tr>
               <tr>
                 <td>Remarks</td>
                 <td>:</td>
                 <td><textarea class="form-control" name="remark" id="Remarks{{$items->invoiceno}}"></textarea></td>
               </tr>
            </table>
               <center><a  class="btn-sm btn btn-warning" onclick="getsubmit('{{$items->invoiceno}}')">Submit</a></center>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  @endforeach
      </div>

</div>
</div>
<script type="text/javascript">
  function  getsubmit (arg) {
       var invoiceno = document.getElementById('invoiceno'+arg).value;
       var amount = document.getElementById('amount'+arg).value;

       var Remarks = document.getElementById('Remarks'+arg).value;
       
    $.ajax({
        type: 'GET',
        url: "{{ URL::to('/') }}/addotherexpensetoorder",
        async: false,
        data: { invoiceno : invoiceno,amount:amount,Remarks:Remarks},
        success: function(response){
             
             alert(response);
             location.reload();

        }
    })
}

</script>
  @endsection