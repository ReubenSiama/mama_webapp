@extends('layouts.app')
@section('content')
	<div class="container">
  <span class="pull-right"> @include('flash-message')</span>
    
      <form action="{{URL::to('/')}}/multisdhg" method="POST" id="yes">
    
                   <input type="hidden"  name="gstpercent" class="form-control" step="0.01" id="gstpercent">
                   <input type="hidden"  name="states" class="form-control" step="0.01" id="stateval">
   
  
      <table class="table table-responsive table-striped" border="1">
        <input type="hidden" name="req_id" value="{{$req_id}}">
         <tr>
   <td>Order Id : </td> 
      <td><input type="text" name="orderid" value="{{$data}}" class="form-control" readonly id="orderid">  </td>
  </tr>
    <tr>
                                        <td>Supplier Name :</td>
                                        <td> 
                                          <?php $manudetails = DB::table('manufacturer_details')->get(); ?>
                                        <select required class="form-control" id="name" name="sname"  onchange="getaddress()">
                                          <option>--Select--</option>
                                     @foreach($manudetails as $manu)           
                                                
                                            <option value="{{$manu->company_name}}" >{{$manu->company_name}}</option>
                                     
                                    @endforeach
                                        </select>
                                      </td>
                                    </tr>
      <tr>
   <td>Billing Address : </td> 
      <td><textarea  required type="text" name="bill" class="form-control" style="resize: none;" rows="5" id="address"></textarea></td>
  </tr>
  <tr>
      <td>Shipping Address : </td> 
      
       <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">{{$ship}}
     </textarea></td>
  </tr>
   <td>Supplier GST: </td> 
      <td><input type="text" name="cgst" value="" class="form-control" id="suppliergst" >  </td>
  </tr>
  
 
   </table>
    <?php $mmm = [40,41,52,48]; $eqs = App\FLOORINGS::where('req_id',$req_id)->whereIn('category',$mmm)->first(); ?>
     @if(count($eqs) == 0)
    <table id="myTable" class="table order-list" border="1">
    <thead>
        <tr>
            <th>Category</th>
            <th>Description</th>
          
            <th>Quantity</th>
            <th>Price</th>
            <th>unit</th>
            <th>state</th>
            <th>GST<span id="gstlable"></span></th>
            <th>WithGST </th>
            <th>Total </th>
        </tr>
    </thead>
    <tbody>
        <tr>
         {{ csrf_field() }}
         
            <td >
               	<?php $categories = App\Category::all(); ?>
                       <select id="category2" onchange="brands()" class="form-control" name="cat[]" >
                        <option>--Select Category--</option>
                        @foreach($categories as $category)

                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
            </td> 

            <td class="hidden"> 
            <input type="hidden" name="unitprice[]" id="unitprice"></td>
            <td >
               <textarea class="form-control" name="desc[]"></textarea>
            </td>
           
             
             <td>
                   <input type="text"  name="quan[]" class="form-control" id="quan" onkeyup="getgst()">
             </td>
             <td>
                   <input type="text"  name="price[]" class="form-control" step="0.01" id="price" onkeyup="getgst()">
             </td>
             <td>
                    <?php $statef = App\Category::distinct()->get(); ?>
                            <select  name="unit[]" class="form-control" >
                              <option>--select--</option>
                              @foreach($statef as $state)
                              <option value="{{$state->measurement_unit}}">{{$state->measurement_unit}}</option>
                             @endforeach
                          </select>
                      </td>
             <td>
                   	<?php $states = App\State::all(); ?>
                            <select  name="state[]" class="form-control" onchange="getgst()" id="state">
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
@else
  <?php $mmm = [40,41,52,48];
$Pluming = App\FLOORINGS::where('req_id',$req_id)->whereIn('category',$mmm)->get(); 



?>

        <table id="myTable" class="table order-listss" border="1">
    <thead>
        <tr>
            <th>Category</th>
            <th>Band</th>
            <th>Description</th>
            <th>HSN</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>unit</th>
            <th>state</th>
            <th>GST<span id="gstlable1"></span></th>
            <th>WithGST </th>
            <th>AV </th>
        </tr>
    </thead>
    <tbody>
         @foreach($Pluming as $pp)
        <tr>
         {{ csrf_field() }}
         <input type="hidden" name="ids[]" value="{{$pp->id}}">
            <td >
                <?php $ids = [40,41,52,48];
                 $categories = App\Category::whereIn('id',$ids)->get(); ?>
                       <select id="category2156{{$pp->id}}" onchange="brands('{{$pp->id}}')" class="form-control" name="cat255[]" >
                        <option value="">--Select Category--</option>
                        @foreach($categories as $category)
          
                        <option {{$pp->category == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
            </td> 
            <td> <select id="brands24{{$pp->id}}"  class="form-control" name="brand55[]">
                    <option value="{{$pp->subcat}}"><?php $d = App\brand::where('id',$pp->subcat)->pluck('brand')->first() ?>{{$d}}</option>     
                    </select></td>
            <input type="hidden"  name="gstpercent1" class="form-control" step="0.01" id="gstpercent1{{$pp->id}}" >
                   <input type="hidden"  name="states1" class="form-control" step="0.01" id="stateval1{{$pp->id}}" >
        
            <input type="text" name="unitprice1[]" id="unitprice1{{$pp->id}}" >
            <td >
               <textarea class="form-control" name="desc1[]" >{{$pp->description}}</textarea>
            </td>
           <td>
                   <input type="text"  name="hsn1[]" class="form-control"  id="hsn" value="{{$pp->hsn}}">
             </td>
            
             <td>
                   <input type="text"  name="quan1[]" class="form-control" id="quan8900{{$pp->id}}" onkeyup="getgst1('{{$pp->id}}')" value="{{$pp->quan}}">
             </td>
             <td>
                   <input type="text"  name="price1[]" class="form-control" step="0.01" id="price8900{{$pp->id}}" onkeyup="getgst1('{{$pp->id}}')" value="{{$pp->price}}">
             </td>
             <td>
                    <?php $statef = App\Category::distinct()->get(['measurement_unit']); ?>
                            <select  name="unit1[]" class="form-control" >
                              <option>--select--</option>
                              @foreach($statef as $state)
                              <option  {{$pp->unit == $state->measurement_unit ? 'selected':''}} value="{{$state->measurement_unit}}">{{$state->measurement_unit}}</option>
                             @endforeach
                          </select>
                      </td>
             <td>
                    <?php $states = App\State::all(); ?>
                            <select  name="state12[]" class="form-control" onchange="getgst1('{{$pp->id}}')" id="state8900{{$pp->id}}">
                              <option>--select--</option>
                              @foreach($states as $state)
                              <option  {{$pp->state == $state->id ? 'selected':''}} value="{{$state->id}}">{{$state->state_name}}</option>
                             @endforeach
                          </select>
                      </td>
               <td>
                   <input type="text" readonly name="gst1[]" class="form-control" step="0.01" id="gst1{{$pp->id}}" value="{{$pp->gst}}">

             </td>
               <td>
                   <input type="text" readonly name="withgst1[]" class="form-control" step="0.01" id="withgst1{{$pp->id}}" value="{{$pp->withgst}}">
             </td>
               <td>
                   <input type="text" readonly name="withoutgst1[]" class="form-control" step="0.01" id="withoutgst1{{$pp->id}}" value="{{$pp->withoutgst}}">
             </td>

          

    
 

        </tr>
        @endforeach
    </tbody>
    

</table>
 
   
</div>

<script type="text/javascript">
  function brands(arg){
    
        var e = document.getElementById('category2156'+arg);
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
                document.getElementById('brands24'+arg).innerHTML = ans;
                $("body").css("cursor", "default");
            }
        });
    }

</script>




<script type="text/javascript">
  function getgst1(arg){
         var e = document.getElementById('state8900'+arg);

         var state = e.options[e.selectedIndex].value;

        var f = document.getElementById('category2156'+arg);

        var cat = f.options[f.selectedIndex].value;

     

        var qua = document.getElementById('quan8900'+arg).value;
        var price = document.getElementById('price8900'+arg).value;
        

        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getgstcal",
            async:false,
            data:{cat : cat,state : state,qua : qua,price : price },
            success: function(response)
            {
                console.log(response);

                document.getElementById('gst1'+arg).value=response['gst'];
                document.getElementById('withgst1'+arg).value=response['withgst'];
                document.getElementById('withoutgst1'+arg).value=response['without'];
                document.getElementById('gstpercent1'+arg).value=response['gstpercent'];
                document.getElementById('stateval1'+arg).value=response['state'];
                document.getElementById('unitprice1'+arg).value=response['unitprice'];
                document.getElementById('gstlable1'+arg).innerHTML = response['gstlable'];
              
              

            },
             error: function (error) {
                     
                      console.log(error);
                    
                    }
        });

         
       }

</script>
@endif
 
     <!-- <div class="container">
       <div class="col-md-3 col-md-offset-4">
        <center>  <button class="btn-sm btn btn-warning" type="submit">Get Total Details</button></center>
         <label>Total Gst Amount <input type="text" name="totalgst" class="form-control" id="totalgst"></label>
         <label>Total WithGST Amount <input type="text" name="totalwithgst" class="form-control" id="totalwithgst"></label>
          <label>Total WithOutGst Amount<input type="text" name="totalwithoutgst" class="form-control" id="totalwithoutgst"></label>
          
         </div> 
     </div> -->
     
        <br><br>
        <center>  <button type="button" class="btn btn-sm btn-warning" onclick="submitfrom()" >Submit</button></center>
      </form>
</div>
<script type="text/javascript">
	$(document).ready(function () {
    var counter = 0;
      
    $("#addrow").on("click", function () {
        var newRow = $("<tr>");
        var cols = "";
               	<?php $categories = App\Category::all(); 

                 
               	?>
           <?php $states = App\State::all(); ?>
           <?php $statef = App\Category::distinct()->get(); ?>

        cols += '<td> <select id="category3'+counter+'" onchange="brands1('+counter+')" class="form-control" name="cat[]"> <option>--Select Category--</option><?php foreach ($categories as $category ): ?><option value={{$category->id}}>{{$category->category_name }}</option><?php endforeach ?></select></td>';
       
          cols += '<td> <textarea id="brands'+counter+'"  class="form-control" name="desc[]"></textarea></td>';
         
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
</script>
<script type="text/javascript">
	function brands1(arg){
	
	    var y = arg;
		
        var e = document.getElementById('category3'+arg);
           
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
               
                document.getElementById('brands3'+arg).innerHTML = ans;
                $("body").css("cursor", "default");
            }
        });
    }
function Subs1(arg)
    {
    	var y = arg;
    	 
        var e = document.getElementById('category3'+arg);
        var f = document.getElementById('brands3'+arg);
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
                document.getElementById('sub3'+arg).innerHTML = ans;
                $("body").css("cursor", "default");
            }
        });
    }
</script>



<script type="text/javascript">
	function getgst(){
   var e = document.getElementById('state');

        var state = e.options[e.selectedIndex].value;
     
		   
        var cat = document.getElementById('category2').value;
        var qua = document.getElementById('quan').value;
        var price = document.getElementById('price').value;
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
   
        var cat = document.getElementById('category3'+arg).value;
        var qua = document.getElementById('quan'+arg).value;
        var price = document.getElementById('price'+arg).value;

      
          
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
                document.getElementById('withgst'+arg).value=response['withgst'];
                document.getElementById('withoutgst'+arg).value=response['without'];
                document.getElementById('unitprice'+arg).value=response['unitprice'];
                document.getElementById('gstlable').innerHTML = response['gstlable'];
              
              

            }
        });

         
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
function getaddress(){
  var x = document.getElementById('name');
  var name = x.options[x.selectedIndex].value;
  var x = document.getElementById('orderid').value;
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
                         document.getElementById('address').value = name; 
                         document.getElementById('suppliergst').value = gst;

                         console.log(response['gst']);
                    }
                });
            }
</script>
@endsection
