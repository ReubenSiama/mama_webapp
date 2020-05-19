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
 <div class="panel-heading text-center" style="background-color: green;color:white;padding:10px;"><b>Invoice Details   ({{count($products)}}) Invocies &nbsp;&nbsp;&nbsp;Month ({{$month}}) &nbsp;&nbsp;&nbsp;Year ({{$year}})
 
 </div>
</div>
</b>
</div>
 <div style="overflow-x:auto;">
             
               <table class="table" border="1" >
                <thead style="background-color:#8c9eff;">
                  <th>SLNO</th>
                  <th style="width: 200px">MH INVOICE DATE</th>
                  <th>MH INVOICE NO</th>
                  <th>CUSTOMER NAME</th>
                  <th>CUSTOMER GST</th>
                  <th>MH ORDER ID</th>
                  <th>MATERIAL </th>
                  <th>QUANTITY</th>
                  <th>UNIT</th>
                  <th>BRAND</th>
                  <th>HSN CODE</th>
                  <th> MH AV </th>
                  <th>MH CGST</th>
                  <th>MH SGST</th>
                  <th>MH IGST</th>
                  <th>MH GST AMOUNT</th>
                  <th>MH INVOICE AMOUNT</th>
                  <th>MH LPO</th>
                  <th>SUPPLIER INVOICE NO</th>
                   <th>SUPPLIER INVOICE IMAGE</th>
                   <th>SUPPLIER NAME</th> 
                   <th>SUPPLIER GST NO</th>
                  <th>SUPPLIER AV</th>
                  <th>SUPPLIER CGST</th>
                  <th>SUPPLIER SGST</th>
                  <th>SUPPLIER IGST</th>
                  <th>TOTAL SUPPLIER GST AMOUNT</th>
                  <th>SUPPLIER INVOICE VALUE</th>
                  <th>GST %</th>
                  <th>MH TP WITH GST </th>
                  <th>MH TP WITHOUT GST</th>
                  <th>OTHER EXPENSES</th>
                  <th>REMARKS</th>
                  <th>FINAL TP</th>
                 
                  <th>##</th>
                  <th>##</th>
                  <th>##</th>

                </thead>
                <tbody style=" overflow:scroll;">
                  <?php 
                     $ot = []; 
                     $supplierinvoiceamount = [];
                     $mhInvoiceamount = [];
                     $withgsta = [];
                     $gsts = [];
                     $tps = [];
                     $finaltps=[];
                     $cgsts=[];
                     $sgsts=[];
                     $igsts=[];
                     $avs = [];
                     $supllierAv = [];
                     $s_cgstamt=[];
                     $s_sgstamt=[];
                     $s_igstamt=[];
                     $s_totalgst = [];
                     ?>


                  <?php $i =1 ?>
                  @foreach($products as $data)
                  <tr>
                      <td>
                         {{$i++}}
                        
                        
                      </td>
                       <td style="width: 200px">{{ date('d-m-Y', strtotime($data->invoicedate)) }}</td>

                      <?php   $project = App\MamahomePrice::where('invoiceno',$data->invoiceno)->first(); 
                              
                              $transport =App\Transport::where('orderid',$data->order_id)->first();
                              $ids = App\MultipleInvoice::where('invoiceno',$data->invoiceno)->first();
                              $name = App\CustomerDetails::where('customer_id',$data->customer_id)->first(); 
                              $amt =App\SupplierInvoicedata::where('invoiceno',$data->invoiceno)->first();
                      ?>
                      <td>
                       @if(count($project) > 0)
                       @if($project->totalamount != null)
                     <a href="{{ route('downloadTaxInvoice',['invoiceno'=>$data->invoiceno,'manu_id'=>$project->manu_id]) }}"> {{$data->invoiceno}}</a>
                       @elseif(count($ids) > 0)
                       <a href="{{ route('downloadTaxInvoice1',['invoiceno'=>$data->invoiceno,'id'=>$ids->id]) }}"> {{$data->invoiceno}}</a>
                       @else
                     {{$data->invoiceno}}
                        @endif
                        @else
                       {{$data->invoiceno}}
                       @endif
                       </td>
                      
                       <td>
                         @if(count($name)>0)
                         {{$name->first_name}}
                         @endif
                       </td>
                       <td>
                         @if(count($project) > 0)
                         {{$project->customer_gst}}
                         @elseif(count($ids) > 0)
                             
                            {{$ids->customergst}}
                            @else
                              0  
                         @endif
                       </td>
                       <td>{{$data->order_id}}</td>
                       <td>{{$data->category}} </td>
                       <td>
                          {{$data->invoicenoqnty}}
                        </td>
                        <td>{{$data->modeofqunty}}</td>

                       <td>
                         <?php 
                               
                                $b = DB::table('orders')->where('id',$data->order_id)->pluck('brand')->first();
                              
 
                         ?>

                        {{$b}}</td>
                        <td>

                         @if(count($project) > 0)
                         {{$project->HSN}}
                         @elseif(count($ids) > 0)
                         {{$ids->HSN}}
                            @else
                              N/A
                         @endif
                       </td>
                        <td>
                         
                     @if(count($project) > 0)
                         {{$project->totalamount}}
                         <?php array_push($avs,$project->totalamount); ?>
                         @elseif(count($ids) > 0)
                         {{$ids->totalwithoutgst}}
                         <?php array_push($avs,$ids->totalwithoutgst); ?>
                          @else
                          0
                         @endif
                        </td>
                            

                                  <?php 
                                     if(count($amt) > 0){
                                         if(count($amt->supplierinvoiceamount) !=0){
                                          $amount = $amt->supplierinvoiceamount;


                                         }else{
                                          $amount=0;
                                         }
                                     }
                                     else{
                                        $amount = 0;

                                     }
                                   // $amt =App\SupplierInvoicedata::where('invoiceno',$data->invoiceno)->pluck('supplierinvoiceamount')->first();

                                    if(count($transport) >0 ){
                                        if(count($data->mhInvoiceamount) == 0){
                                          $mm = 0;
                                        }else{
                                           $mm = $data->mhInvoiceamount;
                                        }
                                    $withgst = ($mm)-($amount + $transport->supplierinvoiceval);
                                    array_push($withgsta, $withgst);
                                       }else{
                                           if($data->mhInvoiceamount == null){
                                          $mm = 0;
                                        }else{
                                           $mm = $data->mhInvoiceamount;
                                        }
                                        $withgst = ($mm)-($amount);
                                           array_push($withgsta, $withgst);
                                       }

                               

                            ?>

                        <?php $gt = $data->custmodeofgst;  
                                
                               if($gt =="CGST & SGST" ){

                                      if($data->customergstpercent == null){
                                         $gst = 0;
                                      }else{
                                         $gst = ($data->customergstpercent)*2;

                                      }
                                    
                                     if(count($project) >0){
                                          $sgst = $project->sgst;
                                          $cgst = $project->cgst;

                                     }else{
                                          $sgst = 0;
                                          $cgst =0 ;
                                     }
                                  if($sgst == null && $cgst == null){

                                               if(count($ids) > 0){
                                                  $s = $ids->totalgst;
                                               }else{
                                                 $s = 0;
                                               }
                                             // $s = App\MultipleInvoice::where('invoiceno',$data->invoiceno)->pluck('totalgst')->first();

                                                if($s != 0) {
                                                $cgst=($s/2);
                                                $sgst =($s/2);

                                              }else{
                                                $cgst=0;
                                                $sgst=0;
                                              }

                                             
                                         }

                                     array_push($sgsts, $sgst);
                                     array_push($cgsts, $cgst);

                                     $igst = 0;

                               }else{
                                if($data->customergstpercent == null){
                                        $gst = 0;
                                      }else{
                                      $gst = ($data->customergstpercent)*2;

                                      }

                                 if(count($project) > 0){

                                  $igst =$project->igst; 
                                 }else{
                                  $igst = 0;
                                 }
                                     $cgst = 0;
                                     $sgst = 0;
                                   if($igst == 0){
                                         
                                         if(count($ids) > 0){
                                          $s = $ids->totalgst;
                                         }

                                         $igst = $s;

                                   }

                                 array_push($igsts, $igst);
                               }
                              $total =$withgst;

                             $amount = ($withgst * $gst)/100;

                              $final = ($total - $amount);

                            array_push($gsts, $amount);
                         ?>
                       <td>{{$cgst}}</td>
                       <td>{{$sgst}}</td>
                       <td>{{$igst}}</td>
                        <td>

                            <?php 
                             
                                 if(count($project) > 0){
                                  $totaltax=$project->totaltax;
                                 }else{
                                  $totaltax = 0;
                                 }
                                if($totaltax == null){
                                   if(count($ids) > 0){
                                   $totaltax = $ids->totalgst;

                                 }else{
                                   $totaltax = 0;
                                 }
                                }
 
                            ?>

                         {{$totaltax }} </td>
                       <td>
                          
                        {{$data->mhInvoiceamount}} 
                            <?php array_push($mhInvoiceamount, $data->mhInvoiceamount); ?>
                      </td>
                       <td>
                            <?php 
                            $no =App\Supplierdetails::where('order_id',$data->order_id)->first();
                             ?> 
                             @if($no != null)
                             @if($no->manu_id != null)
                            <a href="{{ route('downloadpurchaseOrder',['id'=>$no->order_id,'mid'=>$no->manu_id]) }}">{{$no->lpo}}</a>
                          @else
                          <a href="{{ route('downloadpurchaseOrder',['id'=>$no->order_id]) }}"> {{$no->lpo}}</a>

                       @endif
                       @endif

                          @if(count($transport) > 0)
                      
                       <br>
                              
                               {{$transport->mhlpo}}
                             
                           @endif   



                                  

                                </td>
                        <td><?php  
                            
                              if(count($amt) > 0){
                                $spnumber = $amt->supplierinvoicenumber;
                              }else{

                                $spnumber = "";
                              }
                        ?>

                         {{$spnumber}}
                         @if(count($transport) > 0)
                       
                <br>
                              
                               {{$transport->suppliernumber}}
                             
                           @endif 

                       </td>
                          <?php 



                             
                              if(count($amt) > 0){
                                $spamount = $amt->supplierinvoiceamount;
                                $number = $amt->supplierinvoice;
                                $numbers = $amt->supplierinvoicenumber;
                              }else{
                                $spamount = 0;
                                $number = 0;
                                $numbers =[];
                              }

                          ?>

                          <?php
                                $images = explode(",", $number);
                                               ?>
                       <td><a href="{{$url}}/supplierinvoicedata/{{ $images[0] }}">
                       View 
                     </a>

                            @if(count($transport) > 0)
                             <br>
                                              <?php
                                               $image = explode(",", $transport->supplierimage);
                                               ?>
                              <a href="{{$url}}/transportimages/{{ $image[0] }}">
                       View 
                     </a>
                             
                           @endif



                     </td>

                       <td><?php 

                          $spid = App\SupplierInvoicedata::where('invoiceno',$data->invoiceno)->pluck('supplier_id')->first(); 
                            if(count($spid) > 0){
                              $spnames = App\ManufacturerDetail::where('supplier_id',$spid)->pluck('company_name')->first();
                              $spgst = App\ManufacturerDetail::where('supplier_id',$spid)->pluck('gst')->first();
                            }else{
                              $spnames = "";
                              $spgst = "";
                            }

                          ?>
                        {{$spnames}}
                         @if(count($transport) > 0)
                                <br>
                               {{$transport->suppliername}}
                           @endif
                      </td>

                        <td>{{$spgst}}

                           @if(count($transport) > 0)
                                <br>
                               {{$transport->supplergst}}
                           @endif

                        </td>
                         <td>
                         


                            </td>
                           
                            <td>
                                @if(count($transport) > 0)
                                <br>
                               {{$transport->suppliersgst}}
                           @endif


                            </td>
                            <td>
                           @if(count($transport) > 0)
                                <br>
                            
                           @endif
                            </td>
                            <td>
                                   
                           @if(count($transport) > 0)
                                <br>
                              
                           @endif
                               </td>
 



                       <td>
                          @if(count($amt) > 0)
                          {{$amt->supplierinvoiceamount}}

                           <?php array_push($supplierinvoiceamount, $amt->supplierinvoiceamount); ?>
                           @endif
                          @if(count($transport) > 0)
                                <br>
                               {{$transport->supplierinvoiceval}}
                           @endif
                       </td>

                          <td>
                          <?php 
                                 if(count($project) > 0){
                                  $cat = $project->category;

                                 }else{
                                  $cat = "";
                                 }
                          
                                if(count($cat) > 0){
                                    $category = $cat;

                                }else{
                                       if(count($ids) > 0){
                                        $cate = $ids->category;
                                       }else{
                                        $cate = "";
                                       }
                                   // $cate = App\MultipleInvoice::where('invoiceno',$data->invoiceno)->pluck('category')->first();
                                    $daf = explode(",", $cate);
                                    $catename =App\Category::where('id',$daf[0])->pluck('category_name')->first(); 
                                     $category = $catename;  

                                }    

                                $percent = App\Gst::where('category',$category)->where('cgst','!=',NULL)->pluck('cgst')->first();
                                $finalgstper = ($percent * 2);


                          ?> 
                           {{ $finalgstper}}
                            @if(count($transport) > 0)
                                <br>
                               {{$transport->supplierpercent}}
                           @endif

                       </td>

                        <?php 

                                    if(count($amt) > 0){

                              if($amt->supplierinvoiceamount == null){
                                $sps = 0;
                              }else{
                                $sps = $amt->supplierinvoiceamount;
                              }

                                $withgst = ($data->mhInvoiceamount)-($sps);
                              }else{
                                
                             $withgst=0;
                              }
                            ?>
                       
                       <td>
                        
                          {{$withgst}}
                           

                       </td>
                        


                          

                       <td>

                         {{$final}}
                           <?php array_push($tps,$final); ?>
                       </td>
                       

                       <td><?php
                                $deliver =App\DeliveryDetails::where('order_id',$data->order_id)->first();
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

                        <td>
                          <?php 
                               $finaltp = ($final) - ($other);
                                array_push($finaltps, $finaltp)
                            ?>
                           {{$finaltp}}
                        </td>
                      
                     <td> <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal{{$data->order_id}}">edit</button></td>
                      <td> <a class="btn btn-sm btn-warning" href="{{URL::to('/')}}/trasportinvoice?orderid={{$data->order_id}}">Generate  Transport Invoice</button></td>
                         <td> <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal11{{$data->order_id}}">Edit Transport</button></td>
                  </tr>

                  @endforeach
                  <tr style="background-color:#86c8f2">
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td> </td>
                  <td></td>
                  <td></td>
                  <td></td>
                 <td></td>
                 <td></td>
                 <td></td>
                   
                  <td style="font-weight:bold;"> {{array_sum($avs)}}</td>
                  <td style="font-weight:bold;"> {{array_sum($cgsts)}}</td>
                    <td style="font-weight:bold;"> {{array_sum($sgsts)}}</td>
                     <td style="font-weight:bold;"> {{array_sum($igsts)}}</td>
                  <td style="font-weight:bold;">{{array_sum($gsts)}}</td>
                  <td style="font-weight:bold;"> {{array_sum($mhInvoiceamount)}}</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td style="font-weight:bold;">{{array_sum($supllierAv)}}</td>
                  <td style="font-weight:bold;">{{array_sum($s_cgstamt)}}</td>
                  <td style="font-weight:bold;">{{array_sum($s_sgstamt)}}</td>
                  <td style="font-weight:bold;">{{array_sum($s_igstamt)}}</td>
                  <td style="font-weight:bold;">{{array_sum($s_totalgst)}}</td>

                  <td style="font-weight:bold;">{{array_sum($supplierinvoiceamount)}}</td>

                 <td></td>

                  <td style="font-weight:bold;">{{array_sum($withgsta)}} </td>
                  <td style="font-weight:bold;">{{array_sum($tps)}} </td>
                  <td style="font-weight:bold;">{{array_sum($ot)}} </td>
                  <td></td>
                  <td style="font-weight:bold;">{{array_sum($finaltps)}} </td>
                  <!-- <td>Sales Inititor</td> -->
                  <td>##</td>
                  <td>##</td>
                  <td>##</td>
                  </tr>

                </tbody>
               </table>

      </div>

@foreach($products as $data)
  <!-- Modal -->
    <?php $sun = App\Transport::where('orderid',$data->order_id)->first(); ?>

      @if(count($sun) > 0)
  <form action="{{URL::to('/')}}/addtransport" method="post" enctype="multipart/form-data" id="xwork">
                  {{ csrf_field() }}
     
  <div class="modal fade" id="myModal11{{$data->order_id}}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Transport Details(X-Work)</h4>
        </div>
        <div class="modal-body">
         <table class="table">
          <input type="hidden" name="cid" class="form-control" value="{{$data->customer_id}}">
             <tr>
              <td>Order Id</td>
              <td>:</td>
              <td><input type="text" name="orderid" class="form-control" value="{{$data->order_id}}"></td>
            </tr>
             <tr>
              <td>MH LPO</td>
              <td>:</td>
              <td><input type="text" name="mhlpo" class="form-control" value="{{$sun->mhlpo}}"></td>
            </tr>
             <tr>
              <td>SUPPLIER INVOICE NO </td>
              <td>:</td>
              <td><input type="text" name="spnumber" class="form-control" value="{{$sun->suppliernumber}}"></td>
            </tr>
             <tr>
              <td>SUPPLIER INVOICE IMAGE</td>
              <td>:</td>
              <td><input type="file" name="spimage[]" class="form-control" multiple  >
             
                                               <?php $imagess = explode(",", $sun->supplierimage); 

                                                   
                                               ?>
                                               
                                                
                                              
                                             @if(count($imagess) >= 1)
                                             <div class="row">

                                                 @for($i = 0; $i < count($imagess); $i++)
                                                     <div class="col-md-3">
                                                          <img height="350" width="350" id="project_img" src="{{$url}}/transportimages/{{ $imagess[$i] }}" class="img img-thumbnail">
                                                     </div>
                                                 @endfor
                                              </div>
                                              @endif
                                            
                                            <br>

</td>
            </tr>
             <tr>
              <td>SUPPLIER NAME</td>
              <td>:</td>
              <td>
                <?php $sp = App\ManufacturerDetail::all(); ?>
                  <select class="form-control" name="spname">
                       <option value="">---select---</option>
                       @foreach($sp as $s)
                         <option {{$sun->suppliername = $s->company_name ? 'selected' : '' }} value="{{$s->company_name}}">{{$s->company_name}}</option>
                         @endforeach
                  </select>
              </td>
            </tr>
           
            <tr>
              <td>SUPPLIER GST NO</td>
              <td>:</td>
             <td><input type="text" name="spgst" class="form-control" value="{{$sun->supplergst}}"></td>
            </tr>
            <tr>
              <td>SUPPLIER AV</td>
              <td>:</td>
              <td><input type="text" name="spav" class="form-control" value="{{$sun->supplierav}}" ></td>
            </tr>
             <tr>
              <td> SUPPLIER CGST AMOUNT</td>
              <td>:</td>
              <td><input type="text" name="spcgst" class="form-control" value="{{$sun->suppliercgst}}"></td>
             </tr>
            <tr>
              <td> SUPPLIER SGST AMOUNT</td>
              <td>:</td>
              <td><input type="text" name="spsgst" class="form-control" value="{{$sun->suppliersgst}}"></td>
            </tr>
             <tr>
              <td> SUPPLIER IGST AMOUNT</td>
              <td>:</td>
              <td><input type="text" name="spigst" class="form-control" value="{{$sun->supplierigst}}"></td>
            </tr>
            <tr>
              <td> TOTAL SUPPLIER GST AMOUNT</td>
              <td>:</td>
              <td><input type="text" name="spgst" class="form-control" value="{{$sun->suppliercgst +$sun->suppliersgst +$sun->supplierigst}}"></td>
            </tr>
            <tr>
              <td> SUPPLIER INVOICE VALUE</td>
              <td>:</td>
              <td><input type="text" name="spinval" class="form-control" value="{{$sun->supplierinvoiceval}}"></td>
            </tr>
            <tr>
              <td> GST %</td>
              <td>:</td>
              <td><input type="text" name="spgstpercent" class="form-control" value="{{$sun->supplierpercent}}"></td>
            </tr>

         </table>
         


         <center><button class="btn btn-sm btn-warning" onclick="document.getElementById('xwork').submit()" type="submit">Update</button></center>
        </div>
      
         
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  </form>
  @endif
  @endforeach




@foreach($products as $data)
  <!-- Modal -->
  <form action="{{URL::to('/')}}/editinvoicedata" method="post" enctype="multipart/form-data">
                  {{ csrf_field() }}
     
  <div class="modal fade" id="myModal{{$data->order_id}}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Invoice</h4>
        </div>
        <div class="modal-body">
         <table class="table">
             <tr>
              <td>Order Id</td>
              <td>:</td>
              <td><input type="text" name="orderid" class="form-control" value="{{$data->order_id}}"></td>
            </tr>
             <tr>
              <td>Invoice No</td>
              <td>:</td>
              <td><input type="text" name="invoiceno" class="form-control" value="{{$data->invoiceno}}"></td>
            </tr>
             <tr>
              <td>Invoice date </td>
              <td>:</td>
              <td><input type="text" name="indate" class="form-control" value="{{$data->invoicedate}}"></td>
            </tr>
             <tr>
              <td>Quantity</td>
              <td>:</td>
              <td><input type="text" name="quan" class="form-control" value="{{$data->invoicenoqnty}}"></td>
            </tr>
             <tr>
              <td>Unit</td>
              <td>:</td>
              <td><input type="text" name="unit" class="form-control" value="{{$data->modeofqunty}}"></td>
            </tr>
           


            <tr>
              <td>MH Invoice Amount</td>
              <td>:</td>
              <td><input type="text" name="inamount" class="form-control" value="{{$data->mhInvoiceamount}}"></td>
            </tr>
               <?php $amt =App\SupplierInvoicedata::where('invoiceno',$data->invoiceno)->first(); 
               ?>
               @if($amt != NULL)
             
            <tr>
              <td>Supplier Amount</td>
              <td>:</td>
                    


              <td><input type="text" name="spamount" class="form-control" value="{{$amt->supplierinvoiceamount}}" onkeyup="getvals('{{$data->order_id}}')" id="spamount{{$data->order_id}}"></td>
            </tr>

                 <?php $supplierinvoiceamount =App\SupplierInvoicedata::where('invoiceno',$data->invoiceno)->pluck('supplierinvoiceamount')->first();
                 $spgst =App\SupplierInvoicedata::where('invoiceno',$data->invoiceno)->pluck('suppliergstamount')->first();
                     if(count($supplierinvoiceamount) != 0 && count($spgst) != 0 ){
                        $av = ($supplierinvoiceamount - $spgst);
                     }else{
                          $sptotal = App\Supplierdetails::where('order_id',$data->order_id)->pluck('totalamount')->first();
                          $av = App\Supplierdetails::where('order_id',$data->order_id)->pluck('amount')->first();
                         $spgst=($sptotal - $av );
                          
                     }
                    

 
                 ?>
            <tr>
              <td>Supplier AV Amount</td>
              <td>:</td>
              <td><input type="text" name="spav" class="form-control" value="{{$av}}" onkeyup="getvals('{{$data->order_id}}')" id="spav{{$data->order_id}}"></td>
            </tr>
            <tr>
              <td>Supplier GST Amount</td>
              <td>:</td>
              <td><input type="text" name="spgst" class="form-control" value="{{$spgst}}" onkeyup="getvals('{{$data->order_id}}')" id="spgst{{$data->order_id}}"></td>
            </tr>
            <tr>
              <td>Supplier Number</td>
              <td>:</td>
              <td><input type="text" name="spnumber" class="form-control" value="{{$amt->supplierinvoicenumber}}"></td>
            </tr>
              @endif
             <tr>
              <td> Supplier Invoice file</td>
              <td>:</td>
              <td><input type="file" name="spfile[]" class="form-control" multiple></td>
            </tr>
         </table>
         <center><button class="btn btn-sm btn-warning" type="submit">Update</button></center>
        </div>
       
         <?php
                                                if($amt != NULL){

                                               $images = explode(",", $amt->supplierinvoice);
                                                }
                                                else{
                                                  
                                                  $images = [];
                                                }
                                                
                                               ?>
                                             @if(count($images) > 1)
                                             <div class="row">

                                                 @for($i = 0; $i < count($images); $i++)
                                                     <div class="col-md-3">
                                                          <img height="350" width="350" id="project_img" src="{{$url}}/supplierinvoicedata/{{ $images[$i] }}" class="img img-thumbnail">
                                                     </div>
                                                 @endfor
                                              </div>
                                              @endif
                                            
                                            <br>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  </form>
  @endforeach
  

  @endsection