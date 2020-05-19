<?php
$user = Auth::user()->group_id;
$ext = ($user == 4? "layouts.amheader":"layouts.app");
?>
@extends($ext)
@section('content')
<div class="col-md-12">
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default" style="border-color: #f4811f">
<div class="panel-heading" style="background-color: #f4811f;text-align:center">
<b style="font-size: 1.3em;color:white;">Enquiry Sheet</b>
  <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;"></i></button>
<br><br>
<p>(Add Only One Category With One Enquiry,<br>
Do Not Add All Category In Single Enquiry, <br>If You Want To Add All Categories Just Mension In Remarks)</p>
</div>
<div class="panel-body">
<form method="POST" id="sub" name="myform" action="{{URL::to('/')}}/inputdata">
{{csrf_field()}}
@if(SESSION('success'))
<div class="text-center alert alert-success">
<h3 style="font-size:1.8em">{{SESSION('success')}}</h3>
</div>
@endif
@if(session('NotAdded'))
<div class="alert alert-danger alert-dismissable">
<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
{{ session('NotAdded') }}
</div>
@endif
<table class="table table-responsive table-hover">
<tbody>
<tr>
<td style="width:30%"><label> Requirement Date* : </label></td>
<td style="width:70%"> <input type="date"  id="txtDate" required="Required" class="form-control" name="txtDate" placeholder="Select suitable date" style="width:30%"></td>
</tr>
<tr>
@if(!isset($_GET['projectId']))
<td><label>Contact Number* : </label></td>
<td><input required type="text" name="econtact" id='econtact'
maxlength="10" onkeyup="check('econtact')" onblur="getProjects()"
placeholder="10 Digits Only" class="form-control" /><div
id="error"></div></td>
@else
<td><label>Contact Number: </label></td>
<td >{{ $projects->procurementdetails !=
NULL?$projects->procurementdetails->procurement_contact_no:'' }}</td>
<input type="hidden" name="econtact" class="form-control" value="{{ $projects->procurementdetails !=
NULL?$projects->procurementdetails->procurement_contact_no:'' }}">
@endif
</tr>
<!-- <tr>
<td><label>Name* : </label></td>
<td><input required type="text" name="ename" id="ename"
class="form-control"/></td>
</tr> -->
<tr>
@if(!isset($_GET['projectId']))
<td><label>Project* : </label></td>
<td>
<select required class="form-control" id='selectprojects'
name="selectprojects" onchange="getAddress()">
</select>

</td>
@else
<td><label>Project_Id : </label></td>
<td>
<input type="hidden" value="{{ $projects->project_id }}" name="selectprojects">
{{ $projects->project_id }}</td>
@endif
</tr>

<tr>
<td>
  <label> Customer ID: </label></td>
<td>
 <input type="text" class="form-control" name="cid" id="cid" readonly>
</td>
</tr>

<tr>
<td>
  <label> Customer Name: </label></td>
<td>
 <input type="text" class="form-control" name="cname" id="cname" readonly>
</td>
</tr>





<tr>
<td><label>Select Category* :</label></td>
<td><button required type="button" class="btn btn-success"
data-toggle="modal" data-target="#myModal">Product</button>
<button required type="button" class="btn btn-success"
data-toggle="modal" data-target="#mysteel">Steel</button>
<button required type="button" class="btn btn-success"
data-toggle="modal" data-target="#myflooring">FLOORINGS</button>
<button required type="button" class="btn btn-success"
data-toggle="modal" data-target="#Electrical">Electrical,Pluming And Bath Room & Sanitary Fitting</button></td>
</tr>


<!-- model -->
<div class="modal fade" id="myModal" role="dialog">
<div class="modal-dialog" style="width:80%">
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header" style="background-color:rgb(244, 129, 31);color: white;" >
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title"><center>CATEGORY</center></h4>
</div>
<div class="modal-body" style="height:500px;overflow-y:scroll;">
    <br><br>
    <div class="row">
        @foreach($category as $cat)
        <div class="col-md-4">
            <div class="panel panel-success">
            <input type="hidden" name="cat[]" value="{{$cat->id}}">
                <div class="panel-heading">{{$cat->category_name}}</div>
                <div class="panel-body" style="height:300px; max-height:300; overflow-y: scroll;">
                @foreach($cat->brand as $brand)
                <div class="row">
                
                    <b class="btn btn-sm btn-warning form-control" style="border-radius: 0px;" data-toggle="collapse" data-target="#demo{{ $brand->id }}"><u>{{$brand->brand}}</u></b>
                    <br>
                    <div id="demo{{ $brand->id }}" class="collapse">
                        @foreach($brand->subcategory as $subcategory)
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="checkbox"  name="subcat[]"  id="subcat" value="{{ $subcategory->id}}" onclick="checkout()">{{ $subcategory->sub_cat_name}}
                               
                            </label>
                            <br><br>
                        @endforeach
                        <center><span id="total" >total:</span></center>
                    </div>
                    <br>
                </div><br>
                @endforeach
                </div>
            </div>
        </div>
        @if($loop->iteration % 3==0)
        </div>
        <div class="row">
        @endif
        @endforeach
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
</div>
</div>
</div>
</div>
<!-- model end -->
<!-- Modal -->
<div id="mysteel" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:50%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: rgb(244, 129, 31);color: white;" >
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Steel Category</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
         @foreach($steel as $stl)
         <div class="panel panel-success">
            <input type="hidden" name="steelcat[]" value="{{$stl->id}}">
                <div class="panel-heading">{{$stl->category_name}}</div>
                <div class="panel-body" style="height:300px; max-height:300; overflow-y: scroll;">
                 @foreach($stl->brand as $brand)
                 <div class="row">
                    <b class="btn btn-sm btn-warning form-control" style="border-radius: 0px;" data-toggle="collapse" data-target="#demo{{ $brand->id }}"><u>{{$brand->brand}}</u></b>
                    <br>
                    <div id="demo{{ $brand->id }}" class="collapse">
                                 @foreach($brand->subcategory as $subcategory)
                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label class="checkbox-inline">
                                    <input style="margin-top: 25px;" type="checkbox"  name="subcatsteel[]"  id="subcat" value="{{ $subcategory->id}}" onclick="checksteel()">{{ $subcategory->sub_cat_name}}
                                <div class="btn-group">
                                    <input type="text" placeholder="Quantity"  id="quan{{$subcategory->id}}" name="steelquan[]" class="form-control" >
                                    <input type="text" name="steelprice[]" placeholder="price" class="form-control"  id="price{{$subcategory->id}}">
                                </div>
                                </label>
                                <br><br>
                                 @endforeach
                     </div><br><br>
                 </div>
                  @endforeach
             </div>
         </div>
         @endforeach
        </div>
    </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@if(Auth::user()->group_id == 6  || Auth::user()->group_id == 7 ||  Auth::user()->group_id == 11 || Auth::user()->group_id == 17 || Auth::user()->group_id == 23)
<tr>
    <td><label>Initiator* : </label></td>
    <td>
        <select required class="form-control" name="initiator">
            <option value="{{Auth::user()->id}}">{{Auth::user()->name}}</option>
        </select>
    </td>
</tr>
@else
<tr>
    <td><label>Initiator* : </label></td>
    <td>
    <select required class="form-control" name="initiator">
    <option value="">--Select--</option>
    @foreach($users as $user)
    <option value="{{ $user->id }}">{{ $user->name }}</option>
    @endforeach
    </select>
    </td>
</tr>
@endif
<tr>
    <td><label>Location* : </label></td>
    <td>
    @if(isset($_GET['projectId']))
    <input disabled type="text" value="{{ $projects->siteaddress != Null ?
    $projects->siteaddress->address : '' }}" name="elocation"
    id="elocation" class="form-control disabled" />
    @else
    <input disabled type="text" name="elocation" id="elocation" class="form-control disabled" required />
    @endif
    </td>
</tr>
<tr>
    <td><label>Billing And Shipping Address* : </label></td>
    <td><button required type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal4">
 Address
</button>
<!-- The Modal -->
<div class="modal" id="myModal4">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style="background-color: rgb(244, 129, 31);color: white;">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" >Billing And Shipping Address </h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
         <label>Shipping Address</label>
          @if(isset($_GET['projectId']))
            <textarea required id="val" placeholder="Enter Billing Address"  class="form-control" type="text" name="shipaddress" cols="50" rows="5" style="resize:none;">{{ $projects->siteaddress != Null ?
    $projects->siteaddress->address : '' }}
          @else
            <textarea required id="val" placeholder="Enter Billing Address"  class="form-control" type="text" name="shipaddress" cols="50" rows="5" style="resize:none;">
            @endif
        </textarea>  
       <br>

        <div class="col-md-12">
            <div class="col-md-9">
               <label><input type="radio" name="name" id="ss" onclick="myfunction()">&nbsp;&nbsp;Same As Above</label><br><br>
            </div>
            
        </div>
        <label id="sp1">Billing Address</label>
            <textarea  required placeholder="Enter Shipping Address" class="form-control" id="sp" type="text" name="billaddress" cols="50" rows="5" style="resize:none;">
        </textarea>
           <script type="text/javascript">
               function myfunction(){
                var ans = document.getElementById('val').value;
                var ans1 = document.getElementById('sp').value;
                if(ans && ans1){
                 alert("Make sure You Have Selected Only One Address?");
                  document.getElementById('sp').focus();
                   document.getElementById('ss').checked =  false;
                }
                else if(ans){
                document.getElementById('sp').style.display = "none";
                document.getElementById('sp1').style.display = "none";
                    
                }
                else{
                    alert("You Have Not Entered Shipping Address");
                    document.getElementById('ss').checked = false;
                }
               }
               function clearit(){      
                     document.getElementById('val').value = " ";
                     document.getElementById('sp').value = " ";
                     document.getElementById('ss').checked = false;
               }
           </script> 
       <br>
        </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-danger" onclick="clearit()">Reset</button> -->
        <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
      </div>
</div>

      <!-- Modal footer -->

    </div>
  </div>



    </td>
</tr>
<tr id="tquantity" style="">
            <td ><label>Total Quantity* : </label></td>
            <td><input type="text" required onkeyup="checkthis('totalquantity')" name="totalquantity" placeholder="Enter Quantity" id="totalquantity"  class="form-control" /></td>

</tr>

<tr id="tprice">
            <td ><label>Enter Customer Price* : </label></td>
            <td><input type="text" onkeyup="checkthis('price')" name="prices" placeholder="Enter price In Only Numbers" id="price"  class="form-control" required /></td>

</tr>
<!-- <tr>
        <td><label>Select Supplier State* : </label></td>
        <td>
            <select  name="state" class="form-control" id="state">
                <option>--select--</option>
                @foreach($states as $state)
                <option value="{{$state->id}}">{{$state->state_name}}</option>
               @endforeach
            </select>
        </td>
</tr> -->
<tr>
    <td><label>Customer GST : </label></td>
    <td><input type="text" name="cgst" class="form-control" value="" style="text-transform:uppercase" placeholder="Enter Customer GST"   id="gstsa" onkeyup="myFunctions()" ></td>
</tr>
<tr>
  <?php $yup = ['KANNADA','TELUGU','TAMIL','HINDI','ENGLISH','MALAYALAM']; ?>

             
             <td><label>Customer Preferred Languages   : </label></td>
             <td>
                             <select size="7" id="menu" name="framework[]" multiple class="form-control" style='overflow:hidden' required>
                               
                            @foreach($yup as $user)
                              <option  value="{{$user}}"> {{ $user }}</option>
                             @endforeach
                            </select> 
                     </td>        
 </tr>

 <tr>
<td><label>Remarks :</label></td>
<td>
     <textarea style="resize: none;" rows="4" cols="40" name="eremarks"id="eremarks" class="form-control" /></textarea>
</td>
</tr>

</tbody>
</table>
<input type="hidden" id="measure" name="measure">
<div class="text-center">
<button type="button" name="" id="" class="btn btn-md btn-success"
style="width:40%" onclick="submitinputview()"  >Submit</button>
<input type="reset" name="" class="btn btn-md btn-warning" style="width:40%"  />
</div>
<!-- -----------------------------------Flooring ------------------------------------------------------------ -->
<div id="myflooring" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:80%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: rgb(244, 129, 31);color: white;" >
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">FLOORINGS CATEGORY</h4>
      </div>
      <div class="modal-body">
        <div class="row">
        <table id="myTable" class="table order-list" border="1">
    <thead>
        <tr>
            <th>Band</th>
            <th>Description</th>
            <th>HSN</th>
            <th>Sqrt</th>
            <th>Size Of Tiles</th>
            <th>Quantity(Box)</th>
            <th>Price</th>
            <th>Unit</th>
            <th>State</th>
            <th>GST<span id="gstlable"></span></th>
            <th>WithGST </th>
            <th>AV </th>
        </tr>
    </thead>
    <tbody>
        <tr>
         {{ csrf_field() }}
         
            <td >
                <?php $categories = App\brand::where('category_id',48)->get(); ?>
                       <select id="category21" class="form-control" name="cat25[]" >
                        <option>--Select Category--</option>
                        @foreach($categories as $category)

                        <option value="{{ $category->id }}">{{ $category->brand }}</option>
                        @endforeach
                    </select>
            </td> 
            <input type="hidden"  name="gstpercent" class="form-control" step="0.01" id="gstpercent">
                   <input type="hidden"  name="states" class="form-control" step="0.01" id="stateval">
            <td class="hidden"> 
            <input type="hidden" name="unitprice[]" id="unitprice"></td>
            <td >
               <textarea class="form-control" name="desc[]"></textarea>
            </td>
           <td>
                   <input type="text"  name="hsn[]" class="form-control"  id="hsn" >
             </td>
             <td>
              <input type="text" name="sqrt[]" class="form-control" placeholder="Sqrt" id="sqrt">
             <td>
                 <input type="text" name="l[]" id="l" class="form-control" placeholder="length" onkeyup="gettiles()">
                 <input type="text" name="b[]" id="b" class="form-control" placeholder="breadth" onkeyup="gettiles()">
                  
                  <label id="lab"></label>   
                
             </td>
             <td>
                   <input type="text"  name="quan[]" class="form-control" id="quan890" onkeyup="getgst()">
             </td>
             <td>
                   <input type="text"  name="price[]" class="form-control" step="0.01" id="price890" onkeyup="getgst()">
             </td>
             <td>
                    <?php $statef = App\Category::distinct()->get(['measurement_unit']); ?>
                            <select  name="unit[]" class="form-control" >
                              <option>--select--</option>
                              @foreach($statef as $state)
                              <option value="{{$state->measurement_unit}}">{{$state->measurement_unit}}</option>
                             @endforeach
                          </select>
                      </td>
             <td>
                    <?php $states = App\State::all(); ?>
                            <select  name="state[]" class="form-control" onchange="getgst()" id="state890">
                              <option>--select--</option>
                              @foreach($states as $state)
                              <option value="{{$state->id}}">{{$state->state_name}}</option>
                             @endforeach
                          </select>
                      </td>
               <td>
                   <input type="text" readonly name="gst[]" class="form-control" step="0.01" id="gst">

             </td>
               <td>
                   <input type="text" readonly name="withgst[]" class="form-control" step="0.01" id="withgst" >
             </td>
               <td>
                   <input type="text" readonly name="withoutgst[]" class="form-control" step="0.01" id="withoutgst">
             </td>

            <td ><a class="deleteRow"></a>

            </td>
 

        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="1" style="text-align:center;">
                <input type="button" class="btn btn-sm btn-block  btn-danger" id="addrow" value="Add Row" />
            </td>
        </tr>
        
    </tfoot>

</table>
 
    <!--  <div class="container">
       <div class="col-md-3 col-md-offset-4">
        <center>  <button class="btn-sm btn btn-warning" type="submit">Get Total Details</button></center>
         <label>Total Gst Amount <input type="text" name="totalgst" class="form-control" id="totalgst"></label>
         <label>Total WithGST Amount <input type="text" name="totalwithgst" class="form-control" id="totalwithgst"></label>
          <label>Total WithOutGst Amount<input type="text" name="totalwithoutgst" class="form-control" id="totalwithoutgst"></label>
          
         </div> 
     </div>
      -->
       <!--  <br><br>
        <center>  <button type="button" class="btn btn-sm btn-warning" onclick="submitfrom()" >Submit</button></center>
      </form> -->
</div>
<script type="text/javascript">
  $(document).ready(function () {
    var counter = 0;
      
    $("#addrow").on("click", function () {
        var newRow = $("<tr>");
        var cols = "";
                <?php $categories = App\brand::where('category_id',48)->get(); 
              
                 
                ?>
           <?php $states = App\State::all(); ?>
           
           <?php $statef = App\Category::distinct()->get(['measurement_unit']); ?>

        cols += '<td> <select id="category3'+counter+'" class="form-control" name="cat25[]"> <option>--Select Category--</option><?php foreach ($categories as $category ): ?><option value={{$category->id}}>{{$category->brand }}</option><?php endforeach ?></select></td>';
       
          cols += '<td> <textarea id="brands'+counter+'"  class="form-control" name="desc[]"></textarea></td>';
          cols += '<td> <input id="hsn'+counter+'"  class="form-control" name="hsn[]"></td>';

            cols += '<td><input type="text" name="sqrt[]" class="form-control" placeholder="Sqrt" id="sqrt'+counter+'">';
           cols += '<td><input type="text" name="l[]" id="l'+counter+'" class="form-control" placeholder="length" onkeyup="gettiless('+counter+')"><input type="text" name="b[]" id="b'+counter+'" class="form-control" placeholder="breadth" onkeyup="gettiless('+counter+')"><label id="lab'+counter+'"></label></td>';


         
          cols += '<td><input type="text" class="form-control" onkeyup="getgstval('+counter+')" name="quan[]" id="quan'+counter+'"></td>'; 
          cols += '<td><input type="text" class="form-control" onkeyup="getgstval('+counter+')" name="price[]" id="price'+counter+'" step="0.01"></td>'; 

             cols += '<td> <select id="statef'+counter+'" class="form-control" name="unit[]"> <option>--Select unit--</option><?php foreach ($statef as $state ): ?><option value={{$state->measurement_unit}}>{{$state->measurement_unit }}</option><?php endforeach ?></select></td>';

          cols += '<td> <select id="state'+counter+'" onchange="getgstval('+counter+')" class="form-control" name="state[]"> <option>--Select Category--</option><?php foreach ($states as $state ): ?><option value={{$state->id}}>{{$state->state_name }}</option><?php endforeach ?></select></td>';
            
           cols += '<td><input type="text" readonly class="form-control" name="gst[]" id="gst'+counter+'" step="0.01"></td>'; 
           cols += '<td><input type="text" readonly class="form-control" name="withoutgst[]" id="withoutgst'+counter+'" step="0.01"></td>'; 
           cols += '<td><input type="text" readonly class="form-control" name="withgst[]" id="withgst'+counter+'" step="0.01"></td>'; 
           cols += '<td><input type="hidden"  class="form-control" name="unitprice[]" id="unitprice'+counter+'" step="0.01"></td>'; 

           cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
            newRow.append(cols);
        $("table.order-list").append(newRow);
        counter++;
    });



    $("table.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
    });


});




</script>




<script type="text/javascript">
  function getgst(){
       var e = document.getElementById('state890');

        var state = e.options[e.selectedIndex].value;

           var f = document.getElementById('category21');

        var cat = 48;
     

        var qua = document.getElementById('quan890').value;
        var price = document.getElementById('price890').value;
        

        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getgstcal",
            async:false,
            data:{cat : cat,state : state,qua : qua,price : price },
            success: function(response)
            {
                console.log(response);

                document.getElementById('gst').value=response['gst'];
                document.getElementById('withgst').value=response['withgst'];
                document.getElementById('withoutgst').value=response['without'];
                document.getElementById('gstpercent').value=response['gstpercent'];
                document.getElementById('stateval').value=response['state'];
                document.getElementById('unitprice').value=response['unitprice'];
                document.getElementById('gstlable').innerHTML = response['gstlable'];
              
              

            },
             error: function (error) {
                     
                      console.log(error);
                    
                    }
        });

         
       }

</script>
<script type="text/javascript">
  function getgstval(arg){
   var e = document.getElementById('state'+arg);
        var state = e.options[e.selectedIndex].value;
   
        var cat = 48;
        var qua = document.getElementById('quan'+arg).value;
        var price = document.getElementById('price'+arg).value;
       
       if(cat != "" && state != "" && qua != "" && price != ""){
          
     $("html body").css("cursor", "progress");
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getgstcal",
            async:false,
            data:{cat : cat,state : state,qua : qua,price : price },
            success: function(response)
            {
                console.log(response);

                document.getElementById('gst'+arg).value=response['gst'];
                document.getElementById('withgst'+arg).value=response['without'];
                document.getElementById('withoutgst'+arg).value=response['withgst'];
                document.getElementById('unitprice'+arg).value=response['unitprice'];
                document.getElementById('gstlable').innerHTML = response['gstlable'];
              
              

            }
        });

        } 
       }

</script>
<script type="text/javascript">
  function submitfrom(){
    document.getElementById('yes').submit();
  }
</script>
<script type="text/javascript">
  
$(document).ready(function () {
  $('#yes').on('submit',function(e){
    
    e.preventDefault();

    $.ajax({
      type: "post",
      url: "{{URL::to('/')}}/gettotaldetails",
      data: $('#yes').serialize(),
      success: function (response) {
         console.log(response['gst']);
                
       document.getElementById('totalgst').value=response['gst'];
       document.getElementById('totalwithgst').value=response['withgst'];
       document.getElementById('totalwithoutgst').value=response['withoutgst'];
       



      },
     error: function (error) {
                     
                      console.log(error);
                    
                    }
    });


  });
});

</script>
<script type="text/javascript">
    function gettiles(){

       var l = document.getElementById('l').value;
      var b =  document.getElementById('b').value;
      var sqrt =   document.getElementById('sqrt').value;

       var mr = (l*b);
        var data = (sqrt/mr);

        document.getElementById('lab').innerHTML="Required Tiles are<br> "+data;
    }
</script>
<script type="text/javascript">
    function gettiless(data){

       var ls = document.getElementById('l'+data).value;
      var bs =  document.getElementById('b'+data).value;
      var sqrts =   document.getElementById('sqrt'+data).value;

       var mrs = (ls*bs);
        var datas = (sqrts/mrs);

        document.getElementById('lab'+data).innerHTML="Required Tiles are<br> "+datas;
    }
</script>
 
 <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick="clearit()">Reset</button>
        <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
      </div>  
    </div>
      </div>
    <!--   <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>




<!-- -----------------------------------------------------------------flooring End----------------------- -->


<!-- --------Electrical Pluming and  BATH ROOM & SANITARY  FITTINGS--------------------------------------- -->
<div id="Electrical" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width:80%">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: rgb(244, 129, 31);color: white;" >
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Electrical Pluming and  BATH ROOM & SANITARY  FITTINGS</h4>
      </div>
      <div class="modal-body">
        <div class="row">
     
   <!-- <tr>
                                 <td>Select Category</td>
                                 <td>:</td>
                                 <td>
                                    
                                      <label><input  value="Electrical" id="Electrical" type="radio" name="category"><span>&nbsp;</span>Electrical</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                  
                                      <label><input  value="PLUMBING" id="PLUMBING" type="radio" name="category"><span>&nbsp;</span>PLUMBING</label>
                                       <span>&nbsp;&nbsp;&nbsp;  </span>
                                
                                      <label><input   value="BATH ROOM & SANITARY FITTINGS" id="BATH ROOM & SANITARY FITTINGS" type="radio" name="category"><span>&nbsp;</span>BATH ROOM & SANITARY FITTINGS</label>
                                   
                                 </td>
                               </tr>
 -->

        <table id="myTable" class="table order-listss" border="1">
    <thead>
        <tr>
            <th>Category</th>
            <th>Band</th>
            <th>Description</th>
            <th>HSN</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Unit</th>
            <th>State</th>
            <th>GST<span id="gstlable1"></span></th>
            <th>WithGST </th>
            <th>AV </th>
        </tr>
    </thead>
    <tbody>
        <tr>
         {{ csrf_field() }}
         
            <td >
                <?php $ids = [40,41,52];
                 $categories = App\Category::whereIn('id',$ids)->get(); ?>
                       <select id="category2156" onchange="brands()" class="form-control" name="cat255[]" >
                        <option value="">--Select Category--</option>
                        @foreach($categories as $category)
          
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
            </td> 
            <td> <select id="brands24"  class="form-control" name="brand55[]">
                        
                    </select></td>
            <input type="hidden"  name="gstpercent1" class="form-control" step="0.01" id="gstpercent1">
                   <input type="hidden"  name="states1" class="form-control" step="0.01" id="stateval1">
            <td class="hidden"> 
            <input type="hidden" name="unitprice1[]" id="unitprice1"></td>
            <td >
               <textarea class="form-control" name="desc1[]"></textarea>
            </td>
           <td>
                   <input type="text"  name="hsn1[]" class="form-control"  id="hsn" >
             </td>
            
             <td>
                   <input type="text"  name="quan1[]" class="form-control" id="quan8900" onkeyup="getgst1()">
             </td>
             <td>
                   <input type="text"  name="price1[]" class="form-control" step="0.01" id="price8900" onkeyup="getgst1()">
             </td>
             <td>
                    <?php $statef = App\Category::distinct()->get(['measurement_unit']); ?>
                            <select  name="unit1[]" class="form-control" >
                              <option>--select--</option>
                              @foreach($statef as $state)
                              <option value="{{$state->measurement_unit}}">{{$state->measurement_unit}}</option>
                             @endforeach
                          </select>
                      </td>
             <td>
                    <?php $states = App\State::all(); ?>
                            <select  name="state12[]" class="form-control" onchange="getgst1()" id="state8900">
                              <option>--select--</option>
                              @foreach($states as $state)
                              <option value="{{$state->id}}">{{$state->state_name}}</option>
                             @endforeach
                          </select>
                      </td>
               <td>
                   <input type="text" readonly name="gst1[]" class="form-control" step="0.01" id="gst1">

             </td>
               <td>
                   <input type="text" readonly name="withgst1[]" class="form-control" step="0.01" id="withgst1" >
             </td>
               <td>
                   <input type="text" readonly name="withoutgst1[]" class="form-control" step="0.01" id="withoutgst1">
             </td>

            <td ><a class="deleteRow"></a>

            </td>
 

        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="1" style="text-align:center;">
                <input type="button" class="btn btn-sm btn-block  btn-danger" id="addrows" value="Add Row" />
            </td>
        </tr>
        
    </tfoot>

</table>
 
    <!--  <div class="container">
       <div class="col-md-3 col-md-offset-4">
        <center>  <button class="btn-sm btn btn-warning" type="submit">Get Total Details</button></center>
         <label>Total Gst Amount <input type="text" name="totalgst" class="form-control" id="totalgst"></label>
         <label>Total WithGST Amount <input type="text" name="totalwithgst" class="form-control" id="totalwithgst"></label>
          <label>Total WithOutGst Amount<input type="text" name="totalwithoutgst" class="form-control" id="totalwithoutgst"></label>
          
         </div> 
     </div>
      -->
       <!--  <br><br>
        <center>  <button type="button" class="btn btn-sm btn-warning" onclick="submitfrom()" >Submit</button></center>
      </form> -->
</div>
<script type="text/javascript">
  $(document).ready(function () {
    var counters = 0;
      
    $("#addrows").on("click", function () {
        var newRows = $("<tr>");
        var cols = "";
                <?php 
                          $ids = [40,41,52];
                $categories = App\Category::whereIn('id',$ids)->get(); 
              
                 
                ?>
           <?php $states = App\State::all(); ?>
           
           <?php $statef = App\Category::distinct()->get(['measurement_unit']); ?>

        cols += '<td> <select id="category3h333'+counters+'" onclick="brands12('+counters+')" class="form-control" name="cat255[]"> <?php foreach ($categories as $category ): ?><option value={{$category->id}}>{{$category->category_name }}</option><?php endforeach ?></select></td>';
           
           cols += '<td><select id="rrrr'+counters+'"  class="form-control" name="brand55[]"></select></td>';
          
          cols += '<td> <textarea id="brands'+counters+'"   class="form-control" name="desc1[]"></textarea></td>';
          cols += '<td> <input id="hsn'+counters+'"  class="form-control"  name="hsn1[]"></td>';
  
          cols += '<td><input type="text" class="form-control" onkeyup="getgstval1('+counters+')" name="quan1[]" id="quan'+counters+'"></td>'; 
          cols += '<td><input type="text" class="form-control" onkeyup="getgstval1('+counters+')" name="price1[]" id="price'+counters+'" step="0.01"></td>'; 

             cols += '<td> <select id="statef'+counters+'" class="form-control" name="unit1[]"> <option>--Select unit--</option><?php foreach ($statef as $state ): ?><option value={{$state->measurement_unit}}>{{$state->measurement_unit }}</option><?php endforeach ?></select></td>';

          cols += '<td> <select id="state'+counters+'" onchange="getgstval1('+counters+')" class="form-control" name="state12[]"> <option>--Select Category--</option><?php foreach ($states as $state ): ?><option value={{$state->id}}>{{$state->state_name }}</option><?php endforeach ?></select></td>';
            
           cols += '<td><input type="text" readonly class="form-control" name="gst1[]" id="gst1'+counters+'" step="0.01"></td>'; 
           cols += '<td><input type="text" readonly class="form-control" name="withoutgst1[]" id="withoutgst1'+counters+'" step="0.01"></td>'; 
           cols += '<td><input type="text" readonly class="form-control" name="withgst1[]" id="withgst1'+counters+'" step="0.01"></td>'; 
           cols += '<td><input type="hidden"  class="form-control" name="unitprice1[]" id="unitprice1'+counters+'" step="0.01"></td>'; 

           cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
            newRows.append(cols);
        $("table.order-listss").append(newRows);
        counters++;
    });



    $("table.order-listss").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counters -= 1
    });


});




</script>
<script type="text/javascript">
  function brands(){
    
        var e = document.getElementById('category2156');
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
                document.getElementById('brands24').innerHTML = ans;
                $("body").css("cursor", "default");
            }
        });
    }

</script>
<script type="text/javascript">
  function brands12(arg){
  
      var y = arg;
    
        var e = document.getElementById('category3h333'+arg);
           
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
                  
                document.getElementById('rrrr'+y).innerHTML = ans;
                $("body").css("cursor", "default");
            }
        });
    }

</script>



<script type="text/javascript">
  function getgst1(){
         var e = document.getElementById('state8900');

         var state = e.options[e.selectedIndex].value;

        var f = document.getElementById('category2156');

        var cat = f.options[f.selectedIndex].value;

     

        var qua = document.getElementById('quan8900').value;
        var price = document.getElementById('price8900').value;
        

        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getgstcal",
            async:false,
            data:{cat : cat,state : state,qua : qua,price : price },
            success: function(response)
            {
                console.log(response);

                document.getElementById('gst1').value=response['gst'];
                document.getElementById('withgst1').value=response['withgst'];
                document.getElementById('withoutgst1').value=response['without'];
                document.getElementById('gstpercent1').value=response['gstpercent'];
                document.getElementById('stateval1').value=response['state'];
                document.getElementById('unitprice1').value=response['unitprice'];
                document.getElementById('gstlable1').innerHTML = response['gstlable'];
              
              

            },
             error: function (error) {
                     
                      console.log(error);
                    
                    }
        });

         
       }

</script>
<script type="text/javascript">
  function getgstval1(arg){
   var e = document.getElementById('state'+arg);
        var state = e.options[e.selectedIndex].value;
   
        var f = document.getElementById('category3h333'+arg);
        var cat = f.options[f.selectedIndex].value;
        var qua = document.getElementById('quan'+arg).value;
        var price = document.getElementById('price'+arg).value;
       
       if(cat != "" && state != "" && qua != "" && price != ""){
          
     $("html body").css("cursor", "progress");
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getgstcal",
            async:false,
            data:{cat : cat,state : state,qua : qua,price : price },
            success: function(response)
            {
                console.log(response);

                document.getElementById('gst1'+arg).value=response['gst'];
                document.getElementById('withgst1'+arg).value=response['without'];
                document.getElementById('withoutgst1'+arg).value=response['withgst'];
                document.getElementById('unitprice1'+arg).value=response['unitprice'];
                document.getElementById('gstlable1').innerHTML = response['gstlable'];
              
              

            }
        });

        } 
       }

</script>
<script type="text/javascript">
  function submitfrom(){
    document.getElementById('yes').submit();
  }
</script>
<script type="text/javascript">
  
$(document).ready(function () {
  $('#yes').on('submit',function(e){
    
    e.preventDefault();

    $.ajax({
      type: "post",
      url: "{{URL::to('/')}}/gettotaldetails",
      data: $('#yes').serialize(),
      success: function (response) {
         console.log(response['gst']);
                
       document.getElementById('totalgst').value=response['gst'];
       document.getElementById('totalwithgst').value=response['withgst'];
       document.getElementById('totalwithoutgst').value=response['withoutgst'];
       



      },
     error: function (error) {
                     
                      console.log(error);
                    
                    }
    });


  });
});

</script>
<script type="text/javascript">
    function gettiles(){

       var l = document.getElementById('l').value;
      var b =  document.getElementById('b').value;
      var sqrt =   document.getElementById('sqrt').value;

       var mr = (l*b);
        var data = (sqrt/mr);

        document.getElementById('lab').innerHTML="Required Tiles are<br> "+data;
    }
</script>
<script type="text/javascript">
    function gettiless(data){

       var ls = document.getElementById('l'+data).value;
      var bs =  document.getElementById('b'+data).value;
      var sqrts =   document.getElementById('sqrt'+data).value;

       var mrs = (ls*bs);
        var datas = (sqrts/mrs);

        document.getElementById('lab'+data).innerHTML="Required Tiles are<br> "+datas;
    }
</script>
 
 <div class="modal-footer">
        <!-- <button type="button" class="btn btn-danger" onclick="clearit()">Reset</button> -->
        <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
      </div>  
    </div>
      </div>
    <!--   <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>

  </div>
</form>
</div>
</div>
</div>





<!-- --------------------------Electrical Pluming and  BATH ROOM & SANITARY  FITTINGS--------------------------- -->
<script src="http://code.jquery.com/jquery-3.3.1.js"></script>



 
<script type="text/javascript">
function check(arg){

    var input = document.getElementById(arg).value;
    if(isNaN(input)){
               document.getElementById(arg).value = "";
    }
    document.getElementById('econtact').style.borderColor = '';
   
    if(input){
       
        if(isNaN(input)){
            
            while(isNaN(document.getElementById(arg).value)){
                var str = document.getElementById(arg).value;
                str = str.substring(0, str.length - 1);
                document.getElementById(arg).value = str;
            }
        }
    }
}
//    var numArray = [];
//    var priceArray = [];
// function validate(arg){

//     var num = document.getElementById('quan'+arg).value;
//       numArray.push(num);
//       document.getElementById("totalquantity").value = numArray;
    
// }
// function pvalidate(arg){

//     var num = document.getElementById('price'+arg).value;
//       priceArray.push(num);
//       document.getElementById("price").value = priceArray;
    
// }
function getProjects()
{
    var x = document.getElementById('econtact').value;
    document.getElementById('error').innerHTML = '';
    if(x)
    {
        $.ajax({
            type: 'GET',
            url: "{{URL::to('/')}}/getProjects",
            data: {contact: x},
            async: false,
            success: function(response)
            {
                    console.log(response);

                if(response['x']== 'Nothing Found')
                {
                    document.getElementById('econtact').style.borderColor = "red";
                    document.getElementById('error').innerHTML = "<br><div class='alert alert-danger'>No Projects Found !!!</div>";
                    document.getElementById('econtact').value = '';
                }
                else
                {
                    var result = new String();
                    result = "<option value='' disabled selected>----SELECT----</option>";
                    for(var i=0; i<response['x'].length; i++)
                    {
                        result += "<option value='"+response['x'][i].project_id+"'>"+response['x'][i].project_name+" - "+response['x'][i].road_name+"</option>";
                    }
                    document.getElementById('selectprojects').innerHTML =result;
                }
                document.getElementById('cid').value =response['id'];
                document.getElementById('gstsa').value =response['gst'];
                document.getElementById('cname').value =response['cname'];
               
                
            }


        });
    }
}
var count = 0;
function getBrands(id,category_name){
  
    var e = id;
    var category = document.getElementById("mCategory"+id);
    if(category.checked == true){
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getBrands",
            async:false,
            data:{cat : e},
            success: function(response)
            {
                console.log(response);
                var ans = document.getElementById('brands').innerHTML;
                var name = category_name;
                var n = ans.search(category_name);
                if(n != -1){
                    document.getElementById(category_name).style.display = "";
                }else{
                    ans += "<div id = '"+name+"' class='col-md-4'>"+"*"+name+"<br>";
                    count++;
                    for(var i=0;i<response[0].length;i++)
                    {
                        ans += "<label class='checkbox-inline'>"+"<input name='bnd[]' id='brand"+response[0][i].id+"' type='checkbox' onchange=\"getSubCat('"+response[0][i].id+"','"+response[0][i].brand+"')\" value='"+response[0][i].id+"' >"+response[0][i].brand+"</label>"+"<br>";
                    }
                    ans += "</div>";
                    document.getElementById('brands').innerHTML = ans;
                }
            }
        });
    }else{
        var check = document.getElementById("brands").innerHTML;
        var n = check.search(category_name);
        if(n != -1){
            document.getElementById(category_name).style.display = "none";
        }
    }
}
// function getSubCat(id,brandname)
// {
//     var brand = id;
//     var subcategory =document.getElementById("brand"+id);
//     if(subcategory.checked == true){
//         $.ajax({
//             type:'GET',
//             url:"{{URL::to('/')}}/getSubCat",
//             async:false,
//             data:{brand: brand},
//             success: function(response)
//             {
//                 console.log(response);
//                 var name =brandname;
//                 var text = document.getElementById('sCategory').innerHTML;
//                 var n = text.search(brandname);
//                 if(n != -1){
                  
//                     document.getElementById(brandname).style.display = "";
//                 }else{
//                     text += "<div id = '"+name+"' class='col-md-4'>"+"*"+name+"<br>";
//                     for(var i=0; i < response[1].length; i++){
//                         text += "<label class='checkbox-inline'>"+"<input name='subcat[]' type='checkbox' value="+response[1][i].id+">"+response[1][i].sub_cat_name+"</label>"+"<br>";
//                     }
//                     text += "<div>";
//                     document.getElementById('sCategory').innerHTML = text;
//                 }
//             }
//         });
//     }else{
//         var check = document.getElementById("sCategory").innerHTML;
//         var n = check.search(brandname);
//         if(n != -1){
//             document.getElementById(brandname).style.display = "none";
//         }
//     }
// }
function getAddress(){
    var e = document.getElementById('selectprojects');
    var projectId = e.options[e.selectedIndex].value;
    $.ajax({
        type: 'GET',
        url: "{{ URL::to('/') }}/getAddress",
        async: false,
        data: { projectId : projectId},
        success: function(response){
            document.getElementById('elocation').value = response.address;
            document.getElementById('val').value = response.address;
            // document.getElementById('billing').value = response.address;
        }
    })
}
</script>
<script type="text/javascript">
function getquantity()
{
    var quan=document.myform.equantity.value;
    if(isNaN(quan)){
        document.getElementById('equantity').value="";
        myform.equantity.focus();
    }
}
</script>
<script>
function quan(arg){
    var input = document.getElementById(arg).value;
    if(parseInt(document.getElementById('quan'+arg).value) < parseInt(document.getElementById('quantity'+arg).value)){
        alert("Minimum"+ document.getElementById('quantity'+arg).value + "quantity");
        document.getElementById('quan'+arg).value ="";
    }
}
</script>
<script>
function checksteel(){
    var checkBox = document.getElementsByName('subcatsteel[]');
          var ln = checkBox.length;
          var count = 0;
          for(var i =0 ; i < ln ; i++){
            if(checkBox[i].checked == true){
               document.getElementById('tquantity').style.display = "none";
               document.getElementById('tprice').style.display = "none";
              
            } 
        }       
}
var acc = document.getElementsByClassName("accordion");
var i;
for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}
function checkthis(arg){
    var input = document.getElementById(arg).value;
    if(isNaN(input)){
        
               document.getElementById(arg).value = "";
    }
}
function submitinputview(){
  
              var x = document.getElementById("gstsa").value;
              if (x == "") {

                      alert("Are Sure Customer Dont have GST Number ?");
               }
               

                 document.getElementById("sub").submit();
               
        

}
function checkout(){
          var checkBox = document.getElementsByName('subcat[]');
          var ln = checkBox.length;
          var count = 0;
          for(var i =0 ; i < ln ; i++){
            if(checkBox[i].checked == true){
                count++;
            }
            if(count >1){        
                checkBox[i].checked = false;   
            }    
          }
          if(count > 1){
            alert("You Cannot Select Mutiple Category");      
          }         
}
// function checkhere(){
//           var checkBox = document.getElementsByName('subcatsteel[]');
//           var ln = checkBox.length;
//           var count = 0;
//           for(var i =0 ; i < ln ; i++){
//             if(checkBox[i].checked == true){
//                 alert(checkBox[i].checked.value);
//             }
               
//           }        
// }
</script>
<script type="text/javascript">
var today = new Date().toISOString().split('T')[0];
document.getElementsByName("txtDate")[0].setAttribute('min', today);
</script>
<script type="text/javascript">
                                             $(document).ready(function(){
                                          @foreach($yup as $user)
                                           $('#menu').multiselect({
                                              nonSelectedText: 'Select Languages',
                                              enableFiltering: true,
                                              enableCaseInsensitiveFiltering: true,
                                              buttonWidth:'200px',
                                               maxHeight: 200      
                                           });
                                           @endforeach
                                         });
                             </script>  

@endsection
