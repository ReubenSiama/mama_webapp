@extends('layouts.app')

@section('content')
<div class="col-md-12">
    <div class="col-md-12">
  <span class="pull-right"> @include('flash-message')</span>

        <div class="panel panel-default" style="border-color:#337ab7">
            <div class="panel-heading text-center" style="background-color:#337ab7">
                <b style="color:white">Price Setting</b>
            </div>
            <div class="panel-body">
             <form action="{{ URL::to('/') }}/price" method="POST" enctype="multipart/form-data"> 
                    {{ csrf_field() }}
                <div class="col-md-2">
                 <h4><b>Select Category</b></h4>
                    <select id="category2" onchange="brands()" class="form-control" name="cat">
                        <option>--Select Category--</option>
                        @foreach($categories as $category)

                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                  <h4><b>Select Brand</b></h4>
                    <select id="brands2" onchange="Subs()" class="form-control" name="brand">
                        
                    </select>
                </div>
                 <div class="col-md-2">
                   <h4><b>Select Sub Category</b></h4>
                    <select id="sub2"  class="form-control" name="subcat"></select>
                </div>
                <div class="col-md-1">
                   <h4><b>Quantity</b></h4>
                    <input type="text" name="quan" class="form-control" required> 
                 </div>
                  <div class="col-md-2">
                   <h4><b>Team Leader Price</b></h4>
                    <input type="text" name="stl" class="form-control" required> 
                 </div>
                 <!--  <div class="col-md-2">
                   <h4><b>Asst-TLs Price</b></h4>
                    <input type="text" name="asstl" class="form-control" required> <br>
                 </div> -->
                  <div class="col-md-2">
                   <h4><b>LE And SE Price</b></h4>
                    <input type="text" name="leandse" class="form-control" required> 
                 </div> 


  
                  <div class="col-md-1">
                   
                    <button type="submit"  class="form-control btn btn-primary" value="submi" style="margin-top:40px;">Submit Data</button> 
                 </div> 

            </div>
            </form>
<table  class="table table-responsive table-striped" border="1">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Sub Category</th>
                        <!-- <th>Designation</th> -->
                        <th>Quantity</th>
                       <th>Senior-TL Price</th>
                       <!--  <th>Asst-TLs Price</th> -->
                         <th>LE And SE Price</th>
                        
                    </tr>
                </thead>

                <tbody>
                @foreach($myPrices as $myPrice)
                <tr>
                    <td>{{ $myPrice->category_name }}</td>
                    <td>{{ $myPrice->brand }}</td>
                    <td>{{ $myPrice->sub_cat_name }}</td>
                    <td>{{ $myPrice->quantity }}</td>
                    <td>{{ $myPrice->stl }}</td>
                   <!-- <td>{{ $myPrice->asstl }}</td> -->
                  <td>{{ $myPrice->leandse }} </td>
                 
                </tr>
                @endforeach
                   </tbody>
                   </table>

        </div>
    </div>
</div>
</div>
<script type="text/javascript">
	function check(arg){
	    var input = document.getElementById(arg).value;
	    if(isNaN(input)){
	      	while(isNaN(document.getElementById(arg).value)){
	      	var str = document.getElementById(arg).value;
	      	str     = str.substring(0, str.length - 1);
	      	document.getElementById(arg).value = str;
	      	}
	    }
	    else{
	      	input = input.trim();
	      	document.getElementById(arg).value = input;
	    }
	    return false;
	}
	function edit(arg){
	    var initial = document.getElementById(arg);
	    var getdetails = initial.getElementsByTagName("td");
	    var category = getdetails[0].innerText;
	    var subcategory = getdetails[1].innerText;
	    var measure = getdetails[2].innerText;
	    var price = getdetails[3].innerText;
	    document.getElementById('category').value = category;
	    document.getElementById('subcategory').value = subcategory;
	    document.getElementById('price').value = price;
	    document.getElementById('measure').value = measure;
	    document.getElementById('id').value = arg;
	}
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
@endsection