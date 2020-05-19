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
<b style="font-size: 1.3em;color:white;">Manufacturer Enquiry Sheet</b>
<br><br>
</div>
<div class="panel-body">
<form method="POST" id="sub" name="myform" action="{{URL::to('/')}}/manuinputdata">
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
<td style="width:70%"><input id="txtDate" type="date" name="txtDate"
id="edate" class="form-control" style="width:30%" required="" /></td>
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
<td >{{ $projects->proc !=
NULL?$projects->proc->contact:$projects->contact_no }}</td>
<input type="hidden" name="econtact" class="form-control" value="{{ $projects->proc !=
NULL?$projects->proc->contact:$projects->contact_no }}">
@endif
</tr>
<tr>
@if(!isset($_GET['projectId']))
<td><label>Manufacturer* : </label></td>
<td>
<select required class="form-control" id='manu_id'
name="manu_id" onchange="getAddress()">
</select>
</td>
@else
<td><label>Manufacturer ID : </label></td>
<input type="hidden" value="{{ $projects->id }}" name="manu_id">
<td >
<input type="hidden" value="{{ $projects->id }}" name="manu_id">
<input type="hidden" value="{{ $projects->sub_ward_id }}" name="sub_ward_id">

{{ $projects->id }}</td>
@endif
</tr>
<!-- model end -->
@if(Auth::user()->group_id == 6 || Auth::user()->group_id == 7 ||  Auth::user()->group_id == 11 || Auth::user()->group_id == 17)
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
    <input type="text" value="{{ $projects->address!= Null ?
    $projects->address : '' }}" name="elocation"
    id="elocation" class="form-control" />
    @else
    <input type="text" name="elocation" id="elocation" class="form-control" />
    @endif
    </td>
</tr>
<tr>
<td><label> Customer ID: </label></td>
<td>
 <input type="text" class="form-control" name="cid" id="cid" readonly>
</td>
</tr>
<td>
  <label> Customer Name: </label></td>
<td>
 <input type="text" class="form-control" name="cname" id="cname" readonly>
</td>
</tr>
<tr>
<td><label>Select Category:</label></td>
<td><button required type="button" class="btn btn-success"
data-toggle="modal" data-target="#myModal">Product</button></td>
<!-- model -->
<div class="modal fade" id="myModal" role="dialog">
<div class="modal-dialog" style="width:80%">
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header" style="background-color: rgb(244, 129, 31);color: white;" >
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
                                <input type="checkbox"  name="subcat[]" id="subcat{{ $subcategory->id }}" value="{{ $subcategory->id}}" id="" onclick="checkout()">{{ $subcategory->sub_cat_name}}
                                <!-- <input type="text" placeholder="Quantity"  id="quan{{$subcategory->id}}" onblur="quan('{{$subcategory->id }}')" onkeyup="check('quan{{$subcategory->id}}')"   name="quan[]" class="form-control"> -->
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
</tr>

<tr>
    <td><label>Billing And Shipping Address : </label></td>
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
            <textarea required id="val" placeholder="Enter Billing Address"  class="form-control" type="text" name="shipaddress" cols="50" rows="5" style="resize:none;">{{ $projects->address!= Null ? $projects->address : '' }}"
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
        <button type="button" class="btn btn-danger" onclick="clearit()">Reset</button>
        <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
      </div>
</div>
    </div>
  </div>
    </td>
</tr>
<tr>
            <td><label>Total Quantity : </label></td>
            <td><input type="text" onkeyup="checkthis('totalquantity')" name="totalquantity" placeholder="Enter Quantity In Only Numbers" id="totalquantity"  class="form-control" /></td>

</tr>
<tr>
            <td><label>Price: </label></td>
            <td><input type="text"  name="price" placeholder="Enter price In Only Numbers" id="totalquantity"  class="form-control" required /></td>

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
    <td><input type="text" name="cgst" class="form-control" value="" style="text-transform:uppercase" placeholder="Enter Customer GST" id="gstsa"></td>
</tr>
<td><label>Remarks :</label></td>
<td>
<textarea style="resize: none;" rows="4" cols="40" name="eremarks"
id="eremarks" class="form-control" /></textarea>
</td>
</tr>
</tbody>
</table>
<input type="hidden" id="measure" name="measure">
<div class="text-center">
<button type="button" name="" id="" class="btn btn-md btn-success"
style="width:40%" onclick="submitinputview()"  >Submit</button>
<input type="reset" name="" class="btn btn-md btn-warning" style="width:40%" />
</div>
</form>
</div>
</div>
</div>
</div>
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
    if(parseInt(document.getElementById('quan'+arg).value) < parseInt(document.getElementById('quantity'+arg).value)){
        alert("Minimum"+ document.getElementById('quantity'+arg).value + "quantity");
        document.getElementById('quan'+arg).value ="";
    }
}
</script>
<script>
function submitinputview(){
    // var z = document.getElementById('state');
    // var name = z.options[z.selectedIndex].value;
    var bill = document.getElementById('sp').value;
   if (document.getElementById('ss').checked) {
        var id = "";
    }
    else{
        var id ="none";
    }
     if(document.getElementById("totalquantity").value == ""){
            window.alert("You Have Not Entered Total Quantity");
          }
      // else if(document.getElementById("product").value == ""){
      //       window.alert("You Have Not Select Product");
      //     }else if(document.getElementById("edate").value == ""){
      //       window.alert("You Have Not Select date");
      //     }
          else if(document.getElementById('sp').value == "" && id == "none"){
                     
                        window.alert("You Have Not Entered Bill Address");
        }
         
        else{
            document.getElementById("sub").submit();
        }
}
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
function getSubCat(id,brandname)
{
    var brand = id;
    var subcategory =document.getElementById("brand"+id);
    if(subcategory.checked == true){
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getSubCat",
            async:false,
            data:{brand: brand},
            success: function(response)
            {
                console.log(response);
                var name =brandname;
                var text = document.getElementById('sCategory').innerHTML;
                var n = text.search(brandname);
                if(n != -1){
                  
                    document.getElementById(brandname).style.display = "";
                }else{
                    text += "<div id = '"+name+"' class='col-md-4'>"+"*"+name+"<br>";
                    for(var i=0; i < response[1].length; i++){
                        text += "<label class='checkbox-inline'>"+"<input name='subcat[]' type='checkbox' value="+response[1][i].id+">"+response[1][i].sub_cat_name+"</label>"+"<br>";
                    }
                    text += "<div>";
                    document.getElementById('sCategory').innerHTML = text;
                }
            }
        });
    }else{
        var check = document.getElementById("sCategory").innerHTML;
        var n = check.search(brandname);
        if(n != -1){
            document.getElementById(brandname).style.display = "none";
        }
    }
}
</script>
<script>
function quan(arg){
    if(parseInt(document.getElementById('quan'+arg).value) < parseInt(document.getElementById('quantity'+arg).value)){
        alert("Minimum"+ document.getElementById('quantity'+arg).value + "quantity");
        document.getElementById('quan'+arg).value ="";
    }
}
</script>
<script>
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

function getProjects()
{
    var x = document.getElementById('econtact').value;
    document.getElementById('error').innerHTML = '';
    if(x)
    {
        $.ajax({
            type: 'GET',
            url: "{{URL::to('/')}}/getmanuProjects",
            data: {contact: x},
            async: false,
            success: function(response)
            {
                if(response['x'] == 'Nothing Found')
                {
                    document.getElementById('econtact').style.borderColor = "red";
                    document.getElementById('error').innerHTML = "<br><div class='alert alert-danger'>No Manufacturers Found !!!</div>";
                    document.getElementById('econtact').value = '';
                }
                else
                {
                    var result = new String();
                    result = "<option value='' disabled selected>----SELECT----</option>";
                    for(var i=0; i<response['x'].length; i++)
                    {
                        result += "<option value='"+response['x'][i].manu_id+"'>"+response['x'][i].name+"</option>";
                    }
                    console.log(result);
                    console.log(response);
                    document.getElementById('manu_id').innerHTML =result;
                    
                }
                document.getElementById('cid').value =response['id'];
                document.getElementById('gstsa').value =response['gst'];
                document.getElementById('cname').value =response['cname'];

            }
        });
    }
}
function getAddress(){
    var e = document.getElementById('manu_id');
    var projectId = e.options[e.selectedIndex].value;
    
    $.ajax({
        type: 'GET',
        url: "{{ URL::to('/') }}/getmanuAddress",
        async: false,
        data: { projectId : projectId},
        success: function(response){
            
            document.getElementById('elocation').value = response.address;
            document.getElementById('val').value = response.address;

        }
    })
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
</script>
<script type="text/javascript">
var today = new Date().toISOString().split('T')[0];
document.getElementsByName("txtDate")[0].setAttribute('min', today);
</script>
@endsection

