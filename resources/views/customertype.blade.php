 <?php
    $user = Auth::user()->group_id;
    $ext = ($user == 2? "layouts.app":"layouts.app");
?>
@extends($ext)
@section('content')

 <form method="POST" action="{{ URL::to('/') }}/customergeneration" enctype="multipart/form-data">
                {{ csrf_field() }}
                @if(SESSION('customerid'))
<div class="text-center alert alert-success">
<h3 style="font-size:1.8em">Customer ID is :{{SESSION('customerid')}}</h3>
</div>
@endif
            <div class="container container-fluid" style="border-style:dashed;border-width:5px;">
             <br> 
                 
               <div class="row">
                    <div class="col-sm-2"  style="background-color:#138808">
                 <div class="row">
                    <h4  style="text-align:center;font-weight:bold;"><font color="#fffff">MamaHome Invoice Details</h4>
                    <div class="col-md-12 ">MH INVOICE NUMBER</div>
                     <div class="col-md-12"><input type="text" placeholder="Enter Invoice Number" name="invoiceno" class="form-control input-sm" value="{{$val}}" id="invoiceno" onchange="getgst()" ></div>
                 </div>
                   
                <br>
                <div class="row">
                    <div class="col-md-12 ">MH INVOICE DATE</div>
                    <div class="col-md-12">
                        <input type="date" placeholder="MH INVOICE DATE" name="invoicedate" class="form-control input-sm" ><br>
                       <lable>MH Invoice Created Date </lable><input type="text"  style="background-color:#138808" id="invoice"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">MH INVOICE COPY</div>
                   <div class="col-md-12"><input type="file" placeholder="MH INVOICE COPY" name="invoicefile[]" class="form-control input-sm" multiple></div>
                </div><br>
                 <div class="row">
                    <div class="col-md-12 ">MATERIAL</div>
                    <div class="col-md-12"><input type="text" placeholder="MATERIAL" id="category" name="category" class="form-control input-sm"></div>
                </div><br>
                  <div class="row">
                    <div class="col-md-12 ">MODE OF QUANTITY</div>
                    <div class="col-md-12"><input type="text" placeholder="MODE OF QTY" id="modeofqunty" name="modeofqunty" class="form-control input-sm"></div>
                </div><br>
               <div class="row">
                    <div class="col-md-12 ">QUANTITY</div>
                    <div class="col-md-12"><input type="text" placeholder="QTY"  name="invoicenoqnty" id="customerquantity"  class="form-control input-sm"></div>
                </div><br>
                  
                <div class="row">
                    <div class="col-md-12 ">MH UNIT PRICE</div>
                    <div class="col-md-12"><input type="text" placeholder="MH UNIT PRICE" id="mhunitprice" name="mhunitprice" class="form-control input-sm"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">MH INVOICE AMOUNT</div>
                    <div class="col-md-12"><input type="text" placeholder="MH INVOICE AMOUNT" name="mhInvoiceamount"  id="mhInvoiceamount" class="form-control input-sm" onkeyup="gettotal()"></div>
                </div><br>
                     <div class="row">
                    <div class="col-md-12 ">BASE VALUE OF MH INVOICEAMOUNT</div>
                    <div class="col-md-12"><input type="text" placeholder="BASE VALUE OF MH INVOICEAMOUNT" name="basevalue" id="base" class="form-control input-sm"></div>
                </div><br>
                    <div class="row">
                    <div class="col-md-12 ">MH PAYMENT REF NO</div>
                    <div class="col-md-12"><input type="text" placeholder="MH PAYMENT REF NO" name="mhpaymentref" class="form-control input-sm"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">MH PAYMENT REF IMAGE</div>
                    <div class="col-md-12"><input type="file" placeholder="MH PAYMENT REF IMAGE" name="mhpaymentrefimg"  class="form-control input-sm" multiple></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">MH PAYMENT REMARK</div>
                    <div class="col-md-12"><input type="text" placeholder="MH PAYMENT REMARK" name="mhpaymentremark" class="form-control input-sm"></div>
                </div><br>
               
            </div>
            <div> 
            <div class="col-sm-2" style="background-color:#FF9933">

                <h4 style="text-align:center;font-weight:bold;">Customer Details</h4>
                  <div class="row">
                    <div class="col-md-12 ">CUSTOMER ID</div>
                    <div class="col-md-12"><input type="text" id="customerid" placeholder="CUSTOMER ID" name="customerid" class="form-control input-sm" ></div>
                </div><br>
                   <div class="row">
                    <div class="col-md-12 ">CUSTOMER NAME</div>
                    <div class="col-md-12"><input type="text" id="customername" placeholder="CUSTOMER NAME" name="customername" class="form-control input-sm"></div>
                </div><br>
                 <div class="row">
                    <div class="col-md-12 ">CUSTOMER GST NUMBER</div>
                   <div class="col-md-12"><input type="text" placeholder="CUSTOMER GST NUMBER" name="customergstnumber" class="form-control input-sm" id="customergstnumber"percent></div>
                </div><br>
                  <div class="row">
                      
                    <div class="col-md-12 ">CUSTOMER TYPE</div>
                    <div class="col-md-12">  <select  name="customertype" id="" class="form-control input-sm"  >
                          
                                    <option value="">---Select  Type----</option>    
                                    @foreach($customer_details as $customer_detailsa) 
                                    
                                        <option value="{{$customer_detailsa->id}}"> {{$customer_detailsa->cust_type}}</option>
                                    @endforeach
                                </select></div>
                </div><br>
                <div class="row">
                      
                        <div class="col-md-12 ">SUB CUSTOMER TYPE</div>
                        <div class="col-md-12">  <select  name="sub_customertype" id="" class="form-control input-sm"  >
                                <option value="">--Select--</option>
                             
                                @foreach($sub_customer_details as $sub_customer_detailss) 
                                                
                                <option value="{{$sub_customer_detailss->id}}"> {{$sub_customer_detailss->cust_type}}</option>
                            @endforeach
                              
                            </select></div>
                    </div><br>
                <div class="row">
                    <div class="col-md-12 ">CUSTOMER PAYMENT REF NO</div>
                    <div class="col-md-12"><input type="text" placeholder="CUSTOMER PAYMENT REF NO"  name="customerpaymentref" id="gmContact" class="form-control input-sm"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">CUSTOMER PAYMENT REF IMAGE</div>
                    <div class="col-md-12"><input type="file" placeholder="CUSTOMER PAYMENT REF IMAGE" name="customerpaymentrefimg[]" id="gmContactNo" class="form-control input-sm" multiple></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">CUSTOMER PAYMENT REMARK</div>
                    <div class="col-md-12"><input type="text" placeholder="CUSTOMER PAYMENT REMARK" name="customerpaymentremark" class="form-control input-sm"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">CUSTOMER(MODE OF GST)</div>
                    <div class="col-md-12"><input type="text" placeholder="MODE OF GST" name="custmodeofgst" id="custmodeofgst"   class="form-control input-sm"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">CUSOMER(GST %)</div>
                    <div class="col-md-12"><input type="text" placeholder="CUSOMER(GST %)" id="customergstpercent" name="customergstpercent" class="form-control input-sm"></div>
                </div><br>
               
                 <div class="row">
                    <div class="col-md-12 ">CUSOMER GST AMOUNT</div>
                    <div class="col-md-12"><input type="text" placeholder="GST AMOUNT" name="customergstamount" id="customergstamount" class="form-control input-sm"></div>
                </div><br>
                  <div class="row">
                    <div class="col-md-12 ">EWAY BILL NO</div>
                    <div class="col-md-12"><input type="text" placeholder="EWAY BILL NO" name="ewaybill" id='qualityDeptNo'  onkeyup="checknumber('qualityDeptNo')" class="form-control input-sm"></div>
                </div><br>
                   <div class="row">
                    <div class="col-md-12 ">EWAY BILL IMAGE</div>
                    <div class="col-md-12"><input type="file" placeholder="EWAY BILL IMAGE" name="ewaybillimg" id="financeContactNo"  onkeyup="checknumber('financeContactNo')" class="form-control input-sm"></div>
                </div><br>
               
               
                
            </div>
            <div class="col-sm-2" style="background-color:#138808">
                <h4 style="text-align:center;font-weight:bold;">SUPPLIER Deatils</h4>
                 <div class="row">
                    <div class="col-md-12 ">SUPPLIER STATE </div>
                    <div class="col-md-12">
                       <select id="category2" onchange="brands()" class="form-control" name="stateid" >
                        <option>--Select Category--</option>
                        @foreach($states as $category)

                        <option value="{{ $category->id }}">{{ $category->state_name }}</option>
                        @endforeach
                    </select>
                    </div>
                </div><br>
                 <div class="row">
                    <div class="col-md-12 ">SUPPLIERS </div>
                    <div class="col-md-12">
                            <select id="brands2" onchange="Subs()" class="form-control" name="brand" >
                        
                    </select>
                    </div>
                </div><br>
                 <div class="row">
                    <div class="col-md-12 ">Brand </div>
                    <div class="col-md-12">
                       <select   class="form-control" name="bid" >
                        <option>--Select brand--</option>
                        @foreach($brand as $yadav)

                        <option value="{{ $yadav->id }}">{{ $yadav->brand }}</option>
                        @endforeach
                    </select>
                    </div>
                </div><br>
                  <div class="row">
                    <div class="col-md-12 ">SUPPLIER ID </div>
                    <div class="col-md-12"><input type="text" placeholder="SUPPLIER INVOICE DATE" name="supplierid" id="supplierid" class="form-control input-sm" readonly></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">SUPPLIER INVOICE DATE</div>
                    <div class="col-md-12"><input type="date" placeholder="SUPPLIER INVOICE DATE" name="supplierinvoicedate" id="supplierinvoicedate" class="form-control input-sm"></div>
                </div><br>
                 
                <div class="row">
                    <div class="col-md-12 ">SUPPLIER  INVOICE NUMBER</div>
                    <div class="col-md-12"><input type="text" placeholder="SUPPLIER  INVOICE NUMBER" name="supplierinvoicenumber" id="supplierinvoicenumber" class="form-control input-sm"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">SUPPLIER NAME</div>
                    <div class="col-md-12"><input type="text" placeholder="SUPPLIER NAME" name="suppliername" class="form-control input-sm" id="suppliername" ></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 "> SUPPLIER INVOICE AMOUNT</div>
                    <div class="col-md-12"><input type="text" placeholder="SUPPLIER INVOICE AMOUNT" name="supplierinvoiceamount" class="form-control input-sm" id="supplierinvoiceamount" onkeyup="gettotal()"></div>
                </div><br>
                 <div class="row">
                    <div class="col-md-12 "> SUPPLIER GST NUMBER</div>
                    <div class="col-md-12"><input type="text" placeholder=" SUPPLIER GST NUMBER" name="suppliergstnumber" class="form-control input-sm" id="suppliergstnumber"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 "> SUPPLIER INVOICE COPY</div>
                    <div class="col-md-12"><input type="file" placeholder="Finance Contact" name="supplierinvoice[]"  class="form-control input-sm" multiple></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">SUPPLIER MODE OF GST</div>
                    <div class="col-md-12"><input type="text" placeholder="SUPPLIER MODE OF GST" name="smodeofgst" class="form-control input-sm" id="smodeofgst"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">SUPPLIER(GST AMOUNT)</div>
                    <div class="col-md-12"><input type="text" placeholder="GST AMOUNT" name="suppliergstamount" id='suppliergstamount'  class="form-control input-sm"></div>
                </div><br>
              
              
            </div>
            <div class="col-sm-2" style="background-color:#FF9933">
                 <h4 style="font-weight:bold;text-align:center;">Order Details</h4>
                   <div class="row">
                    <div class="col-md-12 col-md-12 ">ORDER NUMBER</div>
                    <div class="col-md-12"><input type="text" id="orderid" placeholder="ORDER NUMBER" name="orderid" class="form-control input-sm"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">PROJECT ID</div>
                    <div class="col-md-12"><input type="text" placeholder="PROJECT ID" name="project" class="form-control input-sm" id="project"></div>
                </div><br>
                  <div class="row">
                    <div class="col-md-12 ">MUNUFACTURER ID</div>
                    <div class="col-md-12"><input type="text" placeholder="MUNUFACTURER ID" id="manuid" name="manuid" class="form-control input-sm"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">ORDER INITIATOR </div>
                    <div class="col-md-12">
                            <label style="background-color:blue" id="orderconfirmname"> </label>
                            <select name="orderconfirmname"  class="form-control input-sm"  >
                              <option value="">-----Select Employee----</option>
                             @foreach($users as $user)
                             <option value="{{$user->id}}">{{$user->name}}</option>
                             @endforeach
                     </select> 
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">ORDER CONVERTER</div>
                    <div class="col-md-12">
                            <label style="background-color:blue" id="orderconvertedname"> </label>
                        <select  name="orderconvertedname"   class="form-control input-sm" >
                                <option value="">-----Select Employee----</option>
                             @foreach($users as $user)
                             <option value="{{$user->id}}">{{$user->name}}</option>
                             @endforeach
                     </select> 
                       
                        </div>
                </div><br>
                  <div class="row">
                    <div class="col-md-12 ">LOGISTIC CODINATE</div>
                    <div class="col-md-12">
                            <label style="background-color:blue" id="orderlogisticname"> </label>
                    <select  name="orderlogisticname"  class="form-control input-sm" >
                        <option value="">-----Select Employee----</option>
                             @foreach($users as $user )
                             <option value="{{$user->id}}">{{$user->name}}</option>
                             @endforeach
                     </select> 
          
                    </div>
                </div><br>
                   <div class="row">
                    <div class="col-md-12 ">ORDER DELIVERED DATE</div>
                    <div class="col-md-12"><input type="date" placeholder="ORDER EXECUTED DATE" name="deliverydate" id="financeContactNo"  class="form-control input-sm" ></div>
                </div><br>
                 <div class="row">
                    <div class="col-md-12 ">OTHER EXPENSES</div>
                    <div class="col-md-12"><input type="text" placeholder="OTHER EXPENSES" name="orderotherexpenses" id='orderotherexpenses'  class="form-control input-sm"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">OTHER EXPENSES REMARKS</div>
                    <div class="col-md-12"><input type="text" placeholder="OTHER EXPENSES REMARKS" name="orderotherexpensesremark" class="form-control input-sm" id="orderotherexpensesremark"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">PROFIT WITH GST</div>
                    <div class="col-md-12"><input type="text" placeholder="PROFIT WITH GST" name="prifitwithgst" id="pwitgst"   class="form-control input-sm" ></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">PROFIT AFTER GST</div>
                    <div class="col-md-12"><input type="text" placeholder="PROFIT AFTER GST" name="profitaftergst" class="form-control input-sm"  id="pwithoutgst"></div>
                </div><br>
               </div>
                <div class="col-sm-2" style="background-color:#138808;">
                    <h4 style="font-weight:bold;text-align:center;">Customer FeedBack</h4>
                     
                      <div class="row">
                    <div class="col-md-12 ">Ward </div>
                    <div class="col-md-12"><input type="text" placeholder="ward" class="form-control input-sm" name="subward"  id="subward">   </div>
                </div><br>
                   <div class="row">
                    <div class="col-md-12 ">Number </div>
                    <div class="col-md-12"><input type="text" placeholder="number" class="form-control" name="number"  id="number" onchange="getcustomerid()" >   </div>
                </div><br>
                      <div class="row">
                    <div class="col-md-12 ">CUSTOMER SATISFACTION VERIFICATIN CALL DON BY </div>
                    <div class="col-md-12"><select   name="customersatisfaction" class="form-control">
                        <option value="">-----Select Employee----</option>
                             @foreach($users as $user )
                             <option value="{{$user->id}}">{{$user->name}}</option>
                             @endforeach
                     </select> 

                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">CUSTOMER SATISFIED OR NOT</div>
                    <div class="col-md-12">
                    <ul>
                      <li  style="list-style:none;">
                        <input type="radio" name="yes" value="YES" />
                        YES
                        <input type="radio" name="yes" 
                        value="NO" id="custom_venuetype_private" />
                        NO
                      </li>
                    </ul>
                    </div>
                </div><br>
                 <div class="row">
                    <div class="col-md-12 ">CUSTOMER SATISFIED CALL RECORDED FILE</div>
                    <div class="col-md-12"><input type="file" placeholder="Quality Department" name="customeraudio" id='qualityDeptNo'  onkeyup="checknumber('qualityDeptNo')" class="form-control input-sm"></div>
                </div><br>
                 <div class="row">
                    <div class="col-md-12 ">CUSTOMER REMARK</div>
                    <div class="col-md-12"><input type="text" placeholder="CUSTOMER REMARK" name="customerremark" id='qualityDeptNo'  onkeyup="checknumber('qualityDeptNo')" class="form-control input-sm"></div>
                </div><br>
                </div>
                <div class="col-sm-2" style="background-color:#FF9933">
                    <h4 style="font-weight:bold;text-align:center;"> DELIVERY Details</h4>
                     <div class="row">
                    <div class="col-md-12 ">DELIVERY LOCATION</div>
                    <div class="col-md-12"><input type="text" placeholder="DELIVERY LOCATION" name="deliverylocation" class="form-control input-sm"></div>
                </div><br>

                          <div class="row">
                    <div class="col-md-12 ">Customer STATE </div>
                    <div class="col-md-12">
                       <select id="state" onchange="statecode()" class="form-control" name="stateid">
                        <option>--Select Category--</option>
                        @foreach($stateswith as $category)

                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    </div>
                </div><br>
                 <div class="row">
                    <div class="col-md-12 ">DISTRICT </div>
                    <div class="col-md-12">
                            <select id="brands22" onchange="Subscode()" class="form-control" name="district">
                        
                    </select>
                    </div>
                </div><br>
                  <div class="row">
                    <div class="col-md-12 ">POSTEL CODE </div>
                    <div class="col-md-12"><input type="text" placeholder="SUPPLIER INVOICE DATE" name="postalcode" id="postid" class="form-control input-sm" ></div>
                </div><br>
                 <div class="row">
                    <div class="col-md-12 ">TRUCK NUMBER</div>
                    <div class="col-md-12"><input type="text" placeholder="TRUCK NUMBER" name="trucknumber" id='qualityDeptNo'  onkeyup="checknumber('qualityDeptNo')" class="form-control input-sm"></div>
                </div><br>
                   <div class="row">
                    <div class="col-md-12 "> TRUCK IMAGE</div>
                    <div class="col-md-12"><input type="file" placeholder="Finance Contact" name="truckimage" id="financeContactNo"  onkeyup="checknumber('financeContactNo')" class="form-control input-sm"></div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 ">TRUCK VIDEO</div>
                    <div class="col-md-12"><input type="file" placeholder="Quality Department" name="truckvideo" class="form-control input-sm"></div>
                </div><br>
              
                 <div class="row">
                    <div class="col-md-12 ">GENERAL REMARK</div>
                    <div class="col-md-12"><input type="text" placeholder="GENERAL REMARK" name="generalremark" id='qualityDeptNo'  onkeyup="checknumber('qualityDeptNo')" class="form-control input-sm"></div>
                </div><br>
            </div>
           </div>
     
  </div>
  <button class="btn btn-sm btn-success form-control" type="submit">Submit Data</button>    
</form>
<script type="text/javascript">
    function gettotal(){
     var mhInvoiceamount = document.getElementById('mhInvoiceamount').value;
     var supplierinvoiceamount = document.getElementById('supplierinvoiceamount').value;
     var custmodeofgst = document.getElementById('custmodeofgst').value;
     var customergstpercent = document.getElementById('customergstpercent').value;
        
       var pwitgst = (mhInvoiceamount - supplierinvoiceamount);
         
           if(custmodeofgst == "CGST & SGST"){
                var yy = (customergstpercent * 2);
                  var s = (yy /100); 
              pwithoutgst = (pwitgst * s);
               var final =(pwitgst -  pwithoutgst);
           }else{
              var yy = customergstpercent;
              var s = (yy /100); 
              pwithoutgst = (pwitgst * s);
               var final =(pwitgst -  pwithoutgst);

           }




       
 
              document.getElementById('pwitgst').value = pwitgst;
              document.getElementById('pwithoutgst').value = final;
    }





</script>
<script type="text/javascript">
    function getgst(){
     
        var invoice = document.getElementById('invoiceno').value;
         
           $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getinvoice",
            async:false,
            data:{invoice : invoice},
            success: function(response)
            {
                   

                console.log(response);

               setval(response);
             },
             error: function (error) {
                     
                      console.log(error);
                    
                    }
       });   

    }
function setval(data){
 var basemhinvoceamount = data['basemhinvoceamount'];
 var custgst = data['custgst'];
 var custgstamount = data['custgstamount'];
 var custname = data['custname'];
 var customergst = data['customergst'];
 var custquantity = data['custquantity'];
 var invoicedate = data['invoicedate'];
 var manu = data['manu'];
 var meterial = data['meterial'];
 var mhInvoiceamount = data['mhInvoiceamount'];
 var mhunitprice = data['mhunitprice'];
 var modeofgst = data['modeofgst'];
 var modeofqunty = data['modeofqunty'];
 var order = data['order'];
 var project = data['project'];
 var suppliergstnumber = data['suppliergstnumber'];
 var supplierinvoicedate = data['supplierinvoicedate'];
 var suppliername = data['suppliername'];
 var supplierinvoicenumber = data['supplierinvoicenumber'];
 var supplierinvoiceamount = data['supplierinvoiceamount'];
 var smodeofgst = data['smodeofgst'];
 var orderotherexpenses =data['orderotherexpenses'];
 var orderotherexpensesremark = data['orderotherexpensesremark'];
 var orderconfirmname = data['orderconfirmname'];
 var orderconvertedname = data['orderconvertedname'];
 var orderlogisticname = data['orderlogisticname'];
 var suppliergstamount = data['suppliergstamount'];
 var sub = data['sub'];
 var number = data['number'];
 var customerid = data['customerid'];
document.getElementById('base').value = basemhinvoceamount;
document.getElementById('customergstnumber').value = custgst;
document.getElementById('customergstamount').value= custgstamount;
document.getElementById('customername').value=custname;
document.getElementById('customergstpercent').value = customergst;
document.getElementById('customerquantity').value = custquantity;
document.getElementById('invoice').value = invoicedate;
document.getElementById('manuid').value = manu;
document.getElementById('category').value = meterial;
document.getElementById('mhInvoiceamount').value = mhInvoiceamount;
document.getElementById('mhunitprice').value = mhunitprice;
document.getElementById('custmodeofgst').value = modeofgst;
document.getElementById('modeofqunty').value = modeofqunty;
document.getElementById('orderid').value = order;
document.getElementById('project').value = project;
document.getElementById('suppliergstnumber').value = suppliergstnumber;
document.getElementById('supplierinvoicedate').value = supplierinvoicedate;
document.getElementById('suppliername').value = suppliername;
document.getElementById('supplierinvoicenumber').value = supplierinvoicenumber;
document.getElementById('supplierinvoiceamount').value = supplierinvoiceamount;
document.getElementById('smodeofgst').value = smodeofgst;
document.getElementById('orderotherexpenses').value = orderotherexpenses;
document.getElementById('orderotherexpensesremark').value=orderotherexpensesremark;
var s = document.getElementById('orderconfirmname')

s.innerHTML =orderconfirmname;




document.getElementById('orderconvertedname').innerHTML =orderconvertedname;
document.getElementById('orderlogisticname').innerHTML = orderlogisticname;
document.getElementById('suppliergstamount').value = suppliergstamount;
document.getElementById('subward').value=sub;
document.getElementById('number').value=number;
document.getElementById('customerid').value = customerid;
}
</script>
<script type="text/javascript">
function brands(){
        var e = document.getElementById('category2');
        var cat = e.options[e.selectedIndex].value;

        $("html body").css("cursor", "progress");
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getsupliers",
            async:false,
            data:{cat : cat},
            success: function(response)
            {
                console.log(response);
                var ans = "<option value=''>--Select--</option>";
                for(var i=0;i<response.length;i++)
                {
                    ans += "<option value='"+response[i].manufacturer_id+"'>"+response[i].company_name+"</option>";
                }
                document.getElementById('brands2').innerHTML = ans;
                $("body").css("cursor", "default");
            },
             error: function (error) {
                     
                      console.log(error);
                    
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
            url:"{{URL::to('/')}}/getsuplierid",
            async:false,
            data:{cat : cat, brand : brand},
            success: function(response)
            {
                
                document.getElementById('supplierid').value=response;
                
                $("body").css("cursor", "default");
            },
             error: function (error) {
                     
                      console.log(error);
                    
                    }
        });
    }
</script>
<script type="text/javascript">
function statecode(){
        var e = document.getElementById('state');
        var cat = e.options[e.selectedIndex].value;
         $("html body").css("cursor", "progress");
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getdistict",
            async:false,
            data:{cat : cat},
            success: function(response)
            {
                console.log(response);
                var ans = "<option value=''>--Select--</option>";
                for(var i=0;i<response.length;i++)
                {
                    ans += "<option value='"+response[i].id+"'>"+response[i].name+"</option>";
                }
                document.getElementById('brands22').innerHTML = ans;
                $("body").css("cursor", "default");
            },
             error: function (error) {
                     
                      console.log(error);
                    
                    }
        });
    }
    function Subscode()
    {
       
        var f = document.getElementById('brands22');
        var s = f.options[f.selectedIndex].value;
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getpostalcode",
            async:false,
            data:{ s : s},
            success: function(response)
            {
                 console.log(response);
                document.getElementById('postid').value=response;
                
                
            },
             error: function (error) {
                     
                      console.log(error);
                    
                    }
        });
    }
</script>

<script type="text/javascript">
    function getcustomerid(){
        
       var data = document.getElementById('number').value;

       
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getcustomerid",
            async:false,
            data:{ number : data},
            success: function(response)
            {
                 console.log(response);
                document.getElementById('customerid').value=response['fa'];
                document.getElementById('customername').value=response['name'];
                document.getElementById('customergstnumber').value=response['gst'];
                
                
            },
             error: function (error) {
                     
                      console.log(error);
                    
                    }
        });

    }
</script>
@endsection
