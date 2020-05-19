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
			</div>
			<div class="panel-body">
				<form method="POST" action="{{URL::to('/')}}/editinputdata">
					{{csrf_field()}}
					<input type="hidden" value="{{ $enq->id }}" name="reqId">
					@if(SESSION('success'))
					<div class="text-center alert alert-success">
						<h3 style="font-size:1.8em">{{SESSION('success')}}</h3>
					</div>
					@endif
					<table class="table table-responsive table-hover">
						<tbody>
							<tr>
								<td style="width:30%"><label>Date* : </label></td>
								<td style="width:70%"><input value="{{ $enq->requirement_date }}" required type="date" name="edate" id="edate" class="form-control" style="width:30%" /></td>
							</tr>
							<tr>
								<td><label>Contact Number* : </label></td>
								<td>
									{{ $enq->procurement_contact_no }} {{ $enq->contractor_contact_no }} {{ $enq->site_engineer_contact_no }}
									{{ $enq->owner_contact_no }} {{ $enq->consultant_contact_no }}
									<!-- <input value="" required type="text" name="econtact" id='econtact' maxlength="10" onkeyup="check('econtact')" onblur="getProjects()" placeholder="10 Digits Only" class="form-control" /><div id="error"></div> -->
								</td>
							</tr>
							<!-- <tr>
								<td><label>Name* : </label></td>
								<td><input required type="text" name="ename" id="ename" class="form-control"/></td>
							</tr> -->
							<tr>
								<td><label>Project* : </label></td>
								<td>
									{{ $enq->project_name }}
								</td>
							</tr>	
							<tr>
								<td><label>Select category:</label></td>
								<td><button required type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Product</button>

                                  <?php $count = App\FLOORINGS::where('req_id',$enq->id)->count(); 

                                  ?>
                              @if($count != 0)
                           <button required type="button" class="btn btn-success"
                              data-toggle="modal" data-target="#myflooring">FLOORINGS</button>
                                      @endif
</td>


								</td>
							</tr>

<!-- model -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="width:80%">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: green;color: white;" >
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><center>CATEGORY</center></h4>
        </div>
        <div class="modal-body" style="height:500px;overflow-y:scroll;">
        <br>
        <br>
        <div class="row">
		<?php
			$subcategories = explode(", ",$enq->sub_category);
			$brands = explode(", ",$enq->brand);
		?>
		@foreach($category as $cat)
			
			<div class="col-md-4" >
					<div class="thumbnail" style="border: 1px solid black;min-height: 100px;">
	                  <button style="background-color:#b8b894;width:100%;color:black;" class="btn btn-default " name="mCategory[]" id="mCategory{{ $cat->id }}"   >{{$cat->category_name}}</button>

	                  @foreach($cat->brand as $brand)
	                   <div class="row">
	                   		<div class="col-md-6">
			                  	<b><u>{{$brand->brand}}</u></b><br>
			                  @foreach($brand->subcategory as $subcategory)
			                  		<!-- <div class="col-md-6"> -->
			                  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			                  			<label class="checkbox-inline">
			                  			 <input {{ in_array($subcategory->sub_cat_name, $subcategories) && in_array($brand->brand, $brands)  ? 'checked': ''}} type="checkbox" name="subcat[]" value="{{ $subcategory->id}}" id="">{{ $subcategory->sub_cat_name}}
			                  			</label>
			                  			<br>
			                  		<!-- </div> -->
			                  @endforeach
			                  </div>
			           </div>
	                  @endforeach
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

							
							<tr>
								<td><label>Initiator* : </label></td>
								<td>	
									<select required class="form-control" name="initiator">
										<option value="">--Select--</option>
										@foreach($users as $user)
										<option {{$enq->generated_by == $user->id ? 'selected' : ''}} value="{{$user->id}}">{{$user->name}}</option>
										@endforeach
									</select>
								</td>
							</tr>
						
							<tr>
								<td><label>Location* : </label></td>
								<td>{{ $enq->address }}</td>
							</tr>
							<tr>
								<td><label>Quantity* : </label></td>
								<td><input type="text" value="{{ $enq->quantity }}" name="equantity" id="equantity" class="form-control" /></td>
							</tr>
							<tr>
								<td><label>Remarks* : </label></td>
								<td>
									<textarea rows="4" cols="40" name="eremarks" id="eremarks" class="form-control" />{{ $enq->notes }}</textarea>
								</td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" id="measure" name="measure">
					<div class="text-center">
						<input type="submit" name="" id="" class="btn btn-md btn-success" style="width:40%" />
						<input type="reset" name="" class="btn btn-md btn-warning" style="width:40%" />
					</div>
<!-- -----------------------------------Flooring ------------------------------------------------------------ -->

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
            <th>Category</th>
            <th>Description</th>
            <th>HSN</th>
            <th>Sqrt</th>
            <th>Size Of Tiles</th>
            <th>Quantity(Box)</th>
            <th>Price</th>
            <th>unit</th>
            <th>state</th>
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
                       <select id="category21" onchange="brands()" class="form-control" name="cat25[]" >
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

        cols += '<td> <select id="category3'+counter+'" onchange="brands1('+counter+')" class="form-control" name="cat25[]"> <option>--Select Category--</option><?php foreach ($categories as $category ): ?><option value={{$category->id}}>{{$category->brand }}</option><?php endforeach ?></select></td>';
       
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
</div>
</form>
</div>
</div>
</div>
</div>



<!-- -----------------------------------------------------------------flooring End----------------------- -->

<!-- -----------------------------------------------------------------flooring End----------------------- -->
				</form>
			</div>
		</div>
	</div>
</div>
<script src="http://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript">
	function check(arg){
	    document.getElementById('econtact').style.borderColor = '';
	    var input = document.getElementById(arg).value;
	    if(input){
		    if(isNaN(input)){
		      while(isNaN(document.getElementById(arg).value)){
		      var str = document.getElementById(arg).value;
		      str     = str.substring(0, str.length - 1);
		      document.getElementById(arg).value = str;
		      }
		    }
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
				url: "{{URL::to('/')}}/getProjects",
				data: {contact: x},
				async: false,
				success: function(response)
				{
					if(response == 'Nothing Found')
					{
						document.getElementById('econtact').style.borderColor = "red";
						document.getElementById('error').innerHTML = "<br><div class='alert alert-danger'>No Projects Found !!!</div>"; 
						document.getElementById('econtact').value = '';
					}
					else
					{
						var result = new String();
						result = "<option value='' disabled selected>----SELECT----</option>";
						for(var i=0; i<response.length; i++)
						{
							result += "<option value='"+response[i].project_id+"'>"+response[i].project_name+" - "+response[i].road_name+"</option>";
						}
						console.log(result);
						document.getElementById('selectprojects').innerHTML =result;	
					}
					
				}
			});
		}
	}    
	function getBrands(){
		var e = document.getElementById('mCategory');
	    var cat = e.options[e.selectedIndex].value;
	    if(cat == "All"){
	    	document.getElementById('brand').innerHTML = "<option value='All'>All</option>";
	    	document.getElementById('sCategory').innerHTML = "<option value='All'>All</option>";
	    }else{
	    	    $.ajax({
	    	        type:'GET',
	    	        url:"{{URL::to('/')}}/getBrands",
	    	        async:false,
	    	        data:{cat : cat},
	    	        success: function(response)
	    	        {
	    	            console.log(response);
	    	            var ans = "<option value=''>--Select--</option><option value='All'>All</option>";
	    	            for(var i=0;i<response[0].length;i++)
	    	            {
	    	                ans += "<option value='"+response[0][i].id+"'>"+response[0][i].brand+"</option>";
	    	            }
	    	            document.getElementById('brand').innerHTML = ans;
	    	        }
	    	    });
	    	}
	}
	function getSubCat()
    {
        var e = document.getElementById("mCategory");
        var cat = e.options[e.selectedIndex].value;
        var brand = document.getElementById("brand").value;
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getSubCat",
            async:false,
            data:{cat : cat, brand: brand},
            success: function(response)
            {
                var text = "<option value='' disabled selected>----Select----</option><option value='All'>All</option>";
                for(var i=0; i < response[1].length; i++)
                {
                    text += "<option value="+response[1][i].id+">"+response[1][i].sub_cat_name+"</option>";
                }
                document.getElementById('sCategory').innerHTML = text;
                document.getElementById('measure').value = response[0].measurement_unit;
            }
        });    
    }
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
@endsection
