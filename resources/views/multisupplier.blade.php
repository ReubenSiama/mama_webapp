@extends('layouts.app')
@section('content')
	<div class="container">
  <span class="pull-right"> @include('flash-message')</span>
    
      <form action="{{URL::to('/')}}/sdhg" method="POST" id="yes">
    
                   <input type="hidden"  name="gstpercent" class="form-control" step="0.01" id="gstpercent">
                   <input type="hidden"  name="states" class="form-control" step="0.01" id="stateval">
   
  
      <table class="table table-responsive table-striped" border="1">
        <input type="hidden" name="req_id" value="{{$req_id}}">
         <tr>
   <td>Order Id : </td> 
      <td><input type="text" name="orderid" value="{{$data}}" class="form-control" readonly>  </td>
  </tr>
  <tr>
      <td>Shipping Address : </td> 
      
       <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">{{$ship}}
     </textarea></td>
  </tr>
    
                                       <tr>
   <td> GST: </td> 
      <td><input type="text" name="cgst" value="" class="form-control" >  </td>
  </tr>
  
   </table>

    <table id="myTable" class="table order-list" border="1">
    <thead>
        <tr>
            <th>Category</th>
            <th>Brand</th>
            <th>Subcat</th>
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
         
            <td class="col-sm-1">
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
            <td class="col-sm-1">
                <select id="brands2" onchange="Subs()" class="form-control" name="brand[]">
                        
                    </select>
            </td>
            <td class="col-sm-1">
               <select id="sub2"  class="form-control" name="subcat[]">
                        
                    </select>
            </td>
             
             <td  class="col-sm-1">
                   <input type="text"  name="quan[]" class="form-control" id="quan" onkeyup="getgst()">
             </td>
             <td  class="col-sm-1">
                   <input type="text"  name="price[]" class="form-control" step="0.01" id="price" onkeyup="getgst()">
             </td>
             <td  class="col-sm-1">
                    <?php $statef = App\Category::distinct()->get(); ?>
                            <select  name="unit[]" class="form-control" >
                              <option>--select--</option>
                              @foreach($statef as $state)
                              <option value="{{$state->measurement_unit}}">{{$state->measurement_unit}}</option>
                             @endforeach
                          </select>
                      </td>
             <td  class="col-sm-1">
                   	<?php $states = App\State::all(); ?>
                            <select  name="state[]" class="form-control" onchange="getgst()" id="state">
                              <option>--select--</option>
                              @foreach($states as $state)
                              <option value="{{$state->id}}">{{$state->state_name}}</option>
                             @endforeach
                          </select>
                      </td>
               <td  class="col-sm-1">
                   <input type="text" readonly name="gst[]" class="form-control" step="0.01" id="gst">

             </td>
               <td  class="col-sm-1">
                   <input type="text" readonly name="withgst[]" class="form-control" step="0.01" id="withgst" >
             </td>
               <td  class="col-sm-1">
                   <input type="text" readonly name="withoutgst[]" class="form-control" step="0.01" id="withoutgst">
             </td>

            <td class="col-sm-1"><a class="deleteRow"></a>

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
 
     <div class="container">
       <div class="col-md-3 col-md-offset-4">
        <center>  <button class="btn-sm btn btn-warning" type="submit">Get Total Details</button></center>
         <label>Total Gst Amount <input type="text" name="totalgst" class="form-control" id="totalgst"></label>
         <label>Total WithGST Amount <input type="text" name="totalwithgst" class="form-control" id="totalwithgst"></label>
          <label>Total WithOutGst Amount<input type="text" name="totalwithoutgst" class="form-control" id="totalwithoutgst"></label>
          
         </div> 
     </div>
     
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
       
          cols += '<td> <select id="brands3'+counter+'" onchange="Subs1('+counter+')" class="form-control" name="brand[]"></select></td>';
          cols += '<td> <select id="sub3'+counter+'"  class="form-control" name="subcat[]"></select></td>';
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

@endsection
