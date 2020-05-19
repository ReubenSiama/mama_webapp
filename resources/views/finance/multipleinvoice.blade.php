<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 26? "finance.layouts.headers":"layouts.app");
?>
@extends($ext)
@section('content')
<?php 
   $cgstpercent = App\MamahomePrice::where('order_id',$id)->pluck('cgstpercent')->first();
  $sgstpercent = App\MamahomePrice::where('order_id',$id)->pluck('sgstpercent')->first();
  $gstpercent = App\MamahomePrice::where('order_id',$id)->pluck('gstpercent')->first();
  $igstpercent = App\MamahomePrice::where('order_id',$id)->pluck('igstpercent')->first();


?>

<div class="col-md-6 col-md-offset-2">
   <form action="{{ URL::to('/') }}/multipleinvoicedata" method="post">
      {{ csrf_field() }}
   <table class="table table-responsive table-striped" border="1">
     <input  type="hidden" name="g1" id="g1" value="{{$cgstpercent}}">
     <input type="hidden" name="g2" id="g2" value="{{$sgstpercent}}">
     <input type="hidden" name="g3" id="g3" value="{{$gstpercent}}">
     <input type="hidden" name="i1" id="i1" value="{{$igstpercent}}">
     <input type="hidden" name="order_id" value="{{$id}}">
     <input type="hidden" name="project_id" value="{{$projectid}}">
     <input type="hidden" name="manu_id" value="{{$manu_id}}">
<tr>
    <td>Category</td>
    <td><select id="category2" onchange="brands()" class="form-control" name="cat">
          <option>--Select Category--</option>
          @foreach($categories as $category)

          <option value="{{ $category->id }}">{{ $category->category_name }}</option>
          @endforeach
      </select>
    </td>
</tr>
<tr>
      <td>Select Brand</td>
      <td><select id="brands2" onchange="Subs()" class="form-control" name="brand">
                              
          </select>
      </td>
</tr>
<tr>
    <td>Select Subcategory</td>
    <td><select id="sub2"  class="form-control" name="subcat">
                            
        </select>
     </td>
</tr>
<tr>
    <td>Select State</td>
    <td>
      <select id="state"  class="form-control" name="state" onclick="getgst()">
          <option value="">----Select----</option>
          <option value="1">Karnataka</option>
          <option value="2">AndraPradesh</option>
          <option value="3">TamilNadu</option>

      </select>
     </td>
</tr>
<tr>
    <td>Billing Address : </td> 
    <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5"></textarea></td>
</tr>
<tr>
    <td>Shipping Address : </td> 
    
    <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5"></textarea></td>
</tr>
  <tr>
     <td>Customer Gst : </td>
     <td>
      <?php
      $num = App\ProcurementDetails::where('project_id',$project_id)->pluck('procurement_contact_no')->first();
     if($num == null){
       $num = App\Mprocurement_Details::where('manu_id',$manu_id)->pluck('contact')->first();
     }
      $gst = App\CustomerGst::where('customer_phonenumber',$num)->pluck('customer_gst')->first();
      ?>
      <input type="text" value="{{$gst}}" name="customergst" class="form-control">
      </td>
  </tr>

                   


                      <tr>
                             <td>Total Quantity : </td>
                             <?php 
                             $quantity = App\MamahomePrice::where('order_id',$id)->pluck('quantity')->first();
                              $unit = App\MamahomePrice::where('order_id',$id)->pluck('unit')->first();
                              $unitwithoutgst = App\MamahomePrice::where('order_id',$id)->pluck('unitwithoutgst')->first();
                              $mamahome_price = App\MamahomePrice::where('order_id',$id)->pluck('mamahome_price')->first();
                              $totalamount = App\MamahomePrice::where('order_id',$id)->pluck('totalamount')->first();
                              ?>
                             <td><input required type="number" class="form-control" name="quantity" value="{{$quantity}}" id="quan"></td>
                           </tr>       
                            <tr>
                              <td>Unit of Measurement : </td>
                              <td><input  type="text" name="unit" value="{{$unit}}" class="form-control" readonly>
                           
                            </tr>
                            <tr>
                              <td>Price(Per Unit) : </td>
                              <td><input required type="number" id="unit"  class="form-control" name="price" value="{{$mamahome_price}}" onkeyup="getcalculation()"  ></td>
                            </tr>  
                              <tr>
                                        <td>Unit Price without GST :</td>
                                        <td>&nbsp;&nbsp;&nbsp;RS.<label class=" alert-success pull-left" id="withoutgst"></label>/-
                                            <input readonly id="withoutgst1" type="text" name="unitwithoutgst"  value="{{$unitwithoutgst}}">
                                       </td>
                              </tr>
                                    
                              <tr>
                                        <td>Total Amount : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="display"></label>/-
                                              <input readonly id="amount" type="text" name="tamount" value="{{$totalamount}}">
                                              <label class=" alert-success pull-right" id="lblWord"></label>
                                        </td>
                                    </tr>
                                           <?php $cgstpercent=App\MamahomePrice::where('order_id',$id)->pluck('cgstpercent')->first(); 
                                        

                                          ?>
                                    <tr>
                                        <td id="c10">CGST({{$cgstpercent}}%) : </td>
                                        <td>
                                          <?php $cgst=App\MamahomePrice::where('order_id',$id)->pluck('cgst')->first(); 
                                          ?>
                                              &nbsp;&nbsp;&nbsp;CGST <label class=" alert-success pull-left" id="cgst"></label>/-
                                              <input readonly  id="cgst1" type="text" name="cgst" value="{{$cgst}}">

                                        </td>
                                    </tr>

                                     
                                      <?php 
                                         $cgst = App\MamahomePrice::where('order_id',$id)->pluck('cgstpercent')->first();
                                          $sgst = App\MamahomePrice::where('order_id',$id)->pluck('sgstpercent')->first();
                                           $igst = App\MamahomePrice::where('order_id',$id)->pluck('igstpercent')->first();
                                            $sgst1 = App\MamahomePrice::where('order_id',$id)->pluck('sgst')->first();
                                            $totaltax=App\MamahomePrice::where('order_id',$id)->pluck('totaltax')->first();
                                            $igst1=App\MamahomePrice::where('order_id',$id)->pluck('igst')->first();
                                            $amountwithgst = App\MamahomePrice::where('order_id',$id)->pluck('amountwithgst')->first();
                                      ?>
                                 <tr>
                                        <td id="s10">SGST({{$sgst}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;SGST <label class=" alert-success pull-left" id="sgst"></label>/-
                                             <input readonly  id="sgst1" type="text" name="sgst" value="{{$sgst1}}">
                                        </td>
                                    </tr>
                                      <tr>
                                      <td>Total Tax :</td>
                                      <td>&nbsp;&nbsp;&nbsp;<label class=" alert-success pull-left" id="totaltax"></label>Total
                                        <input readonly id="totaltax1" type="text" name="totaltax" value="{{$totaltax}}">
                                        <label class=" alert-success pull-right" id="lblWord1"></label>
                                      </td>
                                    </tr>
                                     <tr>
                                        <td id="i10">IGST({{$igst}}%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;IGST<label class=" alert-success pull-left" id="igst"></label>/-
                                             <input readonly  id="igst1" type="text" name="igst" value="{{$igst1}}">
                                             <label class=" alert-success pull-right" id="lblWord3"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount (Including GST) :</td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withgst"></label> /-
                                              <input readonly id="amountwithgst" type="text" name="gstamount" value="{{$amountwithgst}}">
                                              <label class=" alert-success pull-right" id="lblWord2"></label>
                                        </td>
                                    </tr>
</table>
                           
                           <center>
                            <button class="btn btn-sm btn-success form-control" style="text-align: center;">Confirm</button></center>

  </form>                         
</div>


<script type="text/javascript">
  function brands(){
        var e = document.getElementById('category2');
        var cat = e.options[e.selectedIndex].value;
        $("html body").css("cursor", "progress");
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getBrands",
            async:false,
            data:{cat : cat},
            success: function(response)
            {
                console.log(response);
                var ans = "<option value=''>--Select--</option>";
                for(var i=0;i<response[0].length;i++)
                {
                    ans += "<option value='"+response[0][i].id+"'>"+response[0][i].brand+"</option>";
                }
                document.getElementById('brands2').innerHTML = ans;
                $("body").css("cursor", "default");
            }
        });
       
    }
    function Subs()
    {
        var e = document.getElementById('category2');
        var f = document.getElementById('brands2');
        var cat = e.options[e.selectedIndex].value;
        var brand = f.options[f.selectedIndex].value;
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getSubCatPrices",
            async:false,
            data:{cat : cat, brand : brand},
            success: function(response)
            {
                console.log(response);
                var ans = "<option value=''>--Select--</option>";

               
                for(var i=0;i<response[1].length;i++)
                {
                     ans += "<option value='"+response[1][i].id+"'>"+response[1][i].sub_cat_name+"</option>";
                   
                }
                document.getElementById('sub2').innerHTML = ans;
                $("body").css("cursor", "default");
            }
        });
    }

 function getgst(){
var e = document.getElementById('category2');
var cat = e.options[e.selectedIndex].value;     
var state = document.getElementById('state');
var st = state.options[state.selectedIndex].value;

    $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getgstvalue",
            async:false,
            data:{cat : cat, state : st},
            success: function(response)
            {
                  

                console.log(response[0]);

                setval(response[0]);
             }
       });   

  

 }
function setval(arg){
      var arg = arg;
      var gstpercent = arg['gstpercent'];
      var cgst = arg['cgst'];
      var sgst = arg['sgst'];
      var igst = arg['igst'];
                 

               document.getElementById("g3").value = gstpercent;
               document.getElementById("g1").value = cgst;
               document.getElementById("g2").value = sgst;
               document.getElementById("i1").value = igst;
               if(igst == null){
                   document.getElementById("i10").innerHTML ="IGST(0)%";

             }else{

                   document.getElementById("i10").innerHTML ="IGST("+igst+")%";
             }
               if(cgst == null){
                     document.getElementById("c10").innerHTML ="CGST(0)%";

               }else{
                    document.getElementById("c10").innerHTML ="CGST("+cgst+")%";
               }
                 if(sgst == null){

                     document.getElementById("s10").innerHTML ="SGST(0)%";
                 }else{
                  document.getElementById("s10").innerHTML ="SGST("+sgst+")%";

                 }
}
function getcalculation(){

var x =document.getElementById('unit').value;
var y = document.getElementById('quan').value;

var g1 = document.getElementById('g1').value;
var g2 = document.getElementById('g2').value;
var g3 = document.getElementById('g3').value;
var g4 = document.getElementById('g1').value;
var g5 = document.getElementById('g2').value;
var i1 = document.getElementById('i1').value;
var i2 = document.getElementById('i1').value;
if(g3 == ""){
  var g3=i1;

}else{
  var g3 = document.getElementById('g3').value;
}
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
document.getElementById('display').innerHTML = t;
document.getElementById('cgst').innerHTML = gst;
document.getElementById('sgst').innerHTML = sgt;
document.getElementById('igst').innerHTML = igst;
document.getElementById('withgst').innerHTML = withgst;
document.getElementById('withoutgst').innerHTML = withoutgst;
document.getElementById('withoutgst1').value = withoutgst;
document.getElementById('amount').value = f;
document.getElementById('cgst1').value = gst1;
document.getElementById('sgst1').value = sgt1;
document.getElementById('igst1').value = igst1;
document.getElementById('totaltax').innerHTML = tt;
document.getElementById('totaltax1').value = totaltax;
document.getElementById('amountwithgst').value = final;
}

</script>

</script>
@endsection
