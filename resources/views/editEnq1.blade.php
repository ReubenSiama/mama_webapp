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
				<?php $x=$enq->project_id ;
				?>
				 @if($x == "")   
				<b  class="pull-right" style="text-align:right;color:white;font-size:1.1em;">Manufacturer Type:&nbsp;{{$enq->manu != NULL ? $enq->manu->manufacturer_type:''}}</b>
				@endif
				<br><br>
      <p>(Add Only One Category With One Enquiry,<br>
        Do Not Add All Category In Single Enquiry, <br>If You Want To Add All Categories Just Mension In Remarks)</p>
			</div>
			<div class="panel-body">
				<form method="POST" id="sub" action="{{URL::to('/')}}/editinputdata">
					{{csrf_field()}}
					<input type="hidden" value="{{ $enq->id }}" name="reqId">
					@if(SESSION('success'))
					<div class="text-center alert alert-success">
						<h3 style="font-size:1.8em">{{SESSION('success')}}</h3>
					</div>
					@endif
					@if(session('Error'))
					<div class="alert alert-danger alert-dismissable">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					{{ session('Error') }}
					</div>
					@endif
					<table class="table table-responsive table-hover">
						<tbody>
							<tr>
								<td style="width:30%"><label>Requirement Date* : </label></td>
								<td style="width:70%"><input value="{{ $enq->requirement_date }}" required type="date" name="edate" id="edate" class="form-control" style="width:30%" /></td>
							</tr>
							<tr>
								<td><label>Contact Number* : </label></td>
								<td>
									{{ $enq->procurement_contact_no }} {{ $enq->contractor_contact_no }} {{ $enq->site_engineer_contact_no }}
									{{ $enq->owner_contact_no }} {{ $enq->consultant_contact_no }}

									 {{$enq->manu != NULL ? $enq->manu->contact_no:''}} {{$enq->proc != NULL ? $enq->proc->contact:''}}
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
									{{ $enq->project_id }}{{$enq->manu_id}}
									<input type="hidden" name="pid" value="{{$enq->project_id}}">
									<input type="hidden" name="mid" value="{{$enq->manu_id}}">
								</td>
							</tr>
							<tr>
<td><label> Customer ID: </label></td>
<td>
 <input type="text" class="form-control" name="cid" id="cid" value="{{$enq->cid}}" readonly>
</td>
</tr>
<?php                   $mmm = [40,41,52];
                                  $count = App\FLOORINGS::where('req_id',$enq->id)->where('category',48)->count();
                                   $counts = App\FLOORINGS::where('req_id',$enq->id)->whereIn('category',$mmm)->count(); 

                                  ?>	
							@if($enq->main_category == "STEEL")
							<tr>
								<td><label>Category :</label></td>
								<td><button required type="button" class="btn btn-success"
								data-toggle="modal" data-target="#mysteel">Steel</button></td></td>
							</tr>


                              
                          
                                     
							@elseif($count > 0)
                              <tr>
								<td><label>Select category:</label></td>
								<td>
									
									 <button required type="button" class="btn btn-success"
                              data-toggle="modal" data-target="#myflooring">FLOORINGS</button>
								</td>

									
							</tr>
							@elseif($counts > 0)
                              <tr>
								<td><label>Select category:</label></td>
								<td>
									<button required type="button" class="btn btn-success"
data-toggle="modal" data-target="#Electrical">Electrical,Pluming And Bath Room & Sanitary Fitting</button></td>
								</td>

									
							</tr>
							@else

							<tr>
								<td><label>Select category:</label></td>
								<td>
									
									<button id="mybutton" type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Product</button></td>

									
							</tr>
							@endif
<?php
	$quan = explode(", ",$enq->sub_category);
    $quantity = explode(",",$enq->steelquantity);               
	$price = explode(",",$enq->steelprice);
?>
<!-- model end -->
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
                                	<?php
                                	$k = 0;
                                	?>
                 <div class="row">
                    <b class="btn btn-sm btn-warning form-control" style="border-radius: 0px;" data-toggle="collapse" data-target="#demo{{$brand->id}}"><u>{{$brand->brand}}</u></b>
                    <br>
                    <div id="demo{{$brand->id}}" class="collapse">
                                 @foreach($brand->subcategory as $subcategory)
                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                           
                                <label class="checkbox-inline">
                                         <input style="margin-top: 25px;" {{ in_array($subcategory->id, $quan) ? 'checked' : '' }} type="checkbox" name="subcatsteel[]" value="{{ $subcategory->id}}">
                                    {{ $subcategory->sub_cat_name}}
                                <div class="btn-group">  
	                                  	<input type="text" value=" {{ in_array($subcategory->id, $quan) ? $quantity[$k] : '' }} " placeholder="Quantity"  id="quan{{$subcategory->id}}" name="steelquan[]" class="form-control" >
	                                    <input type="text" value=" {{ in_array($subcategory->id, $quan) ? $price[$k] : '' }} " name="steelprice[]" placeholder="price" class="form-control"  id="price{{$subcategory->id}}" >
                                </div>
                                </label>
                                <br><br>
                                <?php
                                $k++;
                                ?>
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
<!-- modal end -->							
							
<?php
	$sub = explode(", ", $enq->sub_category);
	$brands = explode(", ",$enq->brand);
?>

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
                <div class="panel-heading">{{$cat->category_name}}</div>
                <div class="panel-body" style="height:300px; max-height:300; overflow-y: scroll;">
						<?php
							$i = 0;
						?>
                @foreach($cat->brand as $brand)
                <div class="row">
                    <b class="btn btn-sm btn-warning form-control" style="border-radius: 0px;" data-toggle="collapse" data-target="#demo{{ $brand->id }}"><u>{{$brand->brand}}</u></b>
                    <br>
                    <div id="demo{{ $brand->id }}" class="collapse">
                        @foreach($brand->subcategory as $subcategory)
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="hidden" id="quantity{{ $subcategory->id }}" value="{{ $subcategory->Quantity }}">
                                
                                <input {{ in_array($subcategory->id,$sub) ? 'checked' : '' }} type="checkbox" name="subcat[]" id="subcat{{ $subcategory->id }}" value="{{ $subcategory->id}}" onclick="checkout()">
                                {{ $subcategory->sub_cat_name}}
								<!-- <input value= "{{ in_array($subcategory->sub_cat_name, explode(' :',$sub[$i])) ? '': '' }}" type="text" placeholder="Quantity" id="quan{{$subcategory->id}}" onblur="quan('{{$subcategory->id }}')" onkeyup="check('quan{{$subcategory->id}}')" autocomplete="off" name="quan[]" class="form-control"> -->
                            </label>
                            <br><br>
                        @endforeach
                        <!-- <center><span id="total" >total:</span></center> -->
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


							@if(Auth::user()->group_id == 7)
							
							<tr>
								<td><label>Initiator* : </label></td>
								<td>	
									<select required class="form-control"  name="initiator">
										@foreach($users as $user)
										<option value="{{$user->id}}">{{$user->name}}</option>
										@endforeach
									</select>
								</td>
							</tr>
								
							@elseif(Auth::user()->group_id == 6)
							<tr>
								<td><label>Initiator* : </label></td>
								<td>	
									<select required class="form-control"  name="initiator">
										@foreach($users1 as $user)
										<option value="{{$user->id}}">{{$user->name}}</option>
										@endforeach
									</select>
								</td>
							</tr>
							@else
									@if($enq->name == null)
									<tr>
										<td><label>Initiator* : </label></td>
										<td>	
											<select required class="form-control"  name="initiator">
												<option value="">--Select--</option>
												@foreach($users2 as $user)
												<option value="{{$user->id}}">{{$user->name}}</option>
												@endforeach
											</select>
										</td>
									</tr>
									@else
									<tr>
										<td><label>Initiator* : </label></td>
										<td>	
											<select required class="form-control" name="initiator">
												
												
												<option value="{{$enq->name}}">{{$enq->name}}</option>
												
											</select>
										</td>
									</tr>
								@endif
							@endif
							<tr>
								<td><label>Location* : </label></td>
								<td>{{$enq->manu != NULL ? $enq->manu->address: $enq->address}}</td>
							</tr>

                              <tr>
    <td><label>Billing And Shipping Address* : </label></td>
    <td><button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal4">
 Address
</button>
<!-- The Modal -->
<div class="modal" id="myModal4">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style="background-color: rgb(244, 129, 31);color: white;">
        <h4 class="modal-title">Billing And Shipping Address </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
       
      <label>Shipping Address</label>
            <textarea required id="val"  class="form-control" type="text" name="shipaddress" cols="50" rows="5" style="resize:none;">{{$enq->ship != NULL ? $enq->ship : ($enq->manu != NULL ? $enq->manu->address: $enq->address) }}
        </textarea>  
       <br>

        <div class="col-md-12">
            <div class="col-md-9">
               <label><input type="radio" name="name" id="ss" onclick="myfunction()">&nbsp;&nbsp;Same As Above</label><br><br>
            </div>
            
        </div>
        <label id="sp1">Billing Address</label>
            <textarea  required placeholder="Enter Shipping Address" class="form-control" id="sp" type="text" name="billaddress" cols="50" rows="5" style="resize:none;">{{$enq->billadress != NULL ? $enq->billadress :''}}
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
        <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
      </div>
</div>

      <!-- Modal footer -->

    </div>
  </div>
    </td>
</tr>
							<tr>
							<td><label>Brand :</label></td>
							<td>{{ $enq->brand }}(Note: Only One Brand For One Enquiry)</td>
							</tr>
							<tr>
								<td><label>Quantity : </label></td>
								<td>{{ $enq->quantity }}</td>
							</tr>
							<!-- <tr>
								<td><label>Enquiry Quantity : </label></td>
								<td><input type="text" value="{{ $enq->enquiry_quantity !=null ? $enq->enquiry_qantity : $enq->quantity }}" name="enquiryquantity" id="tquantity" class="form-control" />
								Before Entering the Enquiry Quantity Make Sure You Have Selected The Proper Sub-Category And Brand From Above Selection.<br>
								(Ex : 53 Grade:1500 )</td>
							</tr> -->
							@if($enq->main_category != "STEEL")
							<tr>
								<td><label>Total Quantity* : </label></td>
								<td><input  type="text" onkeyup="checkthis('totalquantity')" value="{{ $enq->total_quantity != null ? $enq->total_quantity : $enq->steelquantity }}" name="totalquantity" id="totalquantity" title="Three letter country code" class="form-control" />
								
								</td>

							</tr>
							<tr>
				            <td><label>Price* : </label></td>
				            <td><input type="text"  name="price" placeholder="Enter price In Only Numbers" id="totalquantity"  class="form-control" required value="{{ $enq->price != null ? $enq->price : $enq->steelprice}}" /></td>
                          </tr>
                          @endif
                          
                          <tr>
								<?php 
								if($enq->procurement_contact_no != null){
									$num = ( $enq->procurement_contact_no  != null ? $enq->procurement_contact_no : '');
								}
								else{

								$num = ( $enq->proc != NULL ? $enq->proc->contact:'');
								}
									$gst = App\CustomerGst::where('customer_phonenumber',$num)->pluck('customer_gst')->first();
								?>

								<td><label>Customer GST : </label></td>
								<td><input type="text" value="{{$gst}}" name="cgst" class="form-control" style="text-transform:uppercase" placeholder="Enter Customer GST" id="gstsa"></td>
							</tr>
							<tr>
								<td><label>Followup Date </label></td>
								<td>
									<input type="date" name="fdate" class="form-control" value="{{$enq->follow_up}}"> 
								</td>
							</tr>
							 <tr>
                                 <td><label>Customer requires a repeat Order ?</label></td>
                               
                                 <td>
                                     
                                      <label ><input required value="Yes" id="rmc" type="radio" name="interestorder"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                      <label ><input required value="No" id="rmc2" type="radio" name="interestorder"><span>&nbsp;</span>No</label> 
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                      <label><input checked="checked" value="None" id="rmc3" type="radio" name="interestorder"><span>&nbsp;</span>None</label>
                                 </td>
                               </tr>
                               <tr>
  <?php $yup = ['KANNADA','TELUGU','TAMIL','HINDI','ENGLISH','MALAYALAM']; ?>

             
             <td><label>Preferred Languages   : </label></td>
             <td>
                             <select size="7" id="menu" name="framework[]" multiple class="form-control" style='overflow:hidden' required>
                             	 <?php $lang = explode(",", $enq->language) ?>
                               
                            @foreach($yup as $user)

                              <option  {{ in_array($user, $lang) ? 'checked': ' '}}  value="{{$user}}"> {{ $user }}</option>
                             @endforeach
                            </select> 
                    @foreach($lang as $l)
                             [{{$l}}]
                              
                     	 @endforeach
                     </td>  
                       
 </tr>
							<tr>
								<td><label>Remarks : </label></td>
								<td>
									<textarea rows="4" cols="40" name="eremarks" id="eremarks" class="form-control" />{{ $enq->notes }}</textarea>
								</td>
							</tr>


						</tbody>
					</table>
					<input type="hidden" id="measure" name="measure">
					<div class="text-center">
						<button type="button" onclick="submiteditenq()" name="" id="" class="btn btn-md btn-success" style="width:40%" >Submit</button>
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
            <th>Brand</th>
            <th>Description</th>
            <th>HSN</th>
            <th>Sqrt</th>
            <th>Size Of Tiles</th>
            <th>Quantity(Box)</th>
            <th>Customer Price With GST</th>
            <th>Unit</th>
            <th>State</th>
            <th>GST<span id="gstlable"></span></th>
            <th>WithGST </th>
            <th>AV </th>
        </tr>
    </thead>
    <tbody>

    	<?php 
          if($count > 0){

    	$datas = App\FLOORINGS::where('req_id',$enq->id)->get(); 
    }else{
    	$datas=[];
    }

    	?>
    	 @foreach($datas as $data)
        <tr>
   
         <input type="hidden" name="ids[]" value="{{$data->id}}">
            <td >
                <?php $categories = App\brand::where('category_id',48)->get(); ?>
                       <select id="category3" onchange="brands1()" class="form-control" name="cat25[]" >
                        <option>--Select Category--</option>
                        @foreach($categories as $category)

                        <option {{$data->subcat == $category->id ? 'selected' : ''}} value="{{ $category->id }}">{{ $category->brand }}</option>
                        @endforeach
                    </select>
            </td> 
            <input type="hidden"  name="gstpercent" class="form-control" step="0.01" id="gstpercent">
                   <input type="hidden"  name="states" class="form-control" step="0.01" id="stateval">
            <td class="hidden"> 
            <input type="hidden" name="unitprice[]" id="unitprice{{$data->id}}" value="{{$data->unitprice}}"></td>
            <td >
               <textarea class="form-control" name="desc[]" id="brands{{$data->id}}">{{$data->description}}</textarea>
            </td>
           <td>
                   <input type="text"  name="hsn[]" class="form-control"  id="hsn{{$data->id}}" value="{{$data->hsn}}">
             </td>
             <td>
              <input type="text" name="sqrt[]" class="form-control" placeholder="Sqrt" id="sqrt{{$data->id}}" value="{{$data->sqrt}}">
             <td>
                 <input type="text" name="l[]" id="l{{$data->id}}" class="form-control" placeholder="length" onkeyup="gettiless('{{$data->id}}')" value="{{$data->l}}">
                 <input type="text" name="b[]" id="b{{$data->id}}" class="form-control" placeholder="breadth" onkeyup="gettiless('{{$data->id}}')" value="{{$data->b}}">
                  
                  <label id="lab{{$data->id}}"></label>   
                
             </td>
             <td>
                   <input type="text"  name="quan[]" class="form-control" id="quan{{$data->id}}" onkeyup="getgstval('{{$data->id}}')" value="{{$data->quan}}">
             </td>
             <td>
                   <input type="text"  name="price[]" class="form-control" step="0.01" id="price{{$data->id}}" onkeyup="getgstval('{{$data->id}}')" value="{{$data->price}}">
             </td>
             <td>
                    <?php $statef = App\Category::distinct()->get(['measurement_unit']); ?>
                            <select  name="unit[]" class="form-control" >
                              <option>--select--</option>
                              @foreach($statef as $state)
                              <option {{$data->unit == $state->measurement_unit ? 'selected' : ''}} value="{{$state->measurement_unit}}">{{$state->measurement_unit}}</option>
                             @endforeach
                          </select>
                      </td>
             <td>
                    <?php $states = App\State::all(); ?>
                            <select  name="state[]" class="form-control" onchange="getgstval('{{$data->id}}')" id="state{{$data->id}}">
                              <option>--select--</option>
                              @foreach($states as $state)
                              <option {{$data->state == $state->id ? 'selected' : ''}} value="{{$state->id}}">{{$state->state_name}}</option>
                             @endforeach
                          </select>
                      </td>
               <td>
                   <input type="text" readonly name="gst[]" class="form-control" step="0.01" id="gst{{$data->id}}" value="{{$data->gst}}">

             </td>
               <td>
                   <input type="text" readonly name="withgst[]" class="form-control" step="0.01" id="withgst{{$data->id}}" value="{{$data->withgst}}" >
             </td>
               <td>
                   <input type="text" readonly name="withoutgst[]" class="form-control" step="0.01" id="withoutgst{{$data->id}}" value="{{$data->withoutgst}}">
             </td>

            <td ><a class="deleteRow"></a>

            </td>
 

        </tr>
        @endforeach
    </tbody>
    <tfoot>
       
        
    </tfoot>

</table>
 
   
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
       alert(data);
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






<!-- -----------------------------------------------------------------flooring End----------------------- -->

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
     
    <?php $mmm = [40,41,52];
$Pluming = App\FLOORINGS::where('req_id',$enq->id)->whereIn('category',$mmm)->get(); 



?>

        <table id="myTable" class="table order-listss" border="1">
    <thead>
        <tr>
            <th>Category</th>
            <th>Band</th>
            <th>Description</th>
            <th>HSN</th>
            <th>Quantity</th>
            <th>Customer Price With GST</th>
            <th>Unit</th>
            <th>State</th>
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
                <?php $ids = [40,41,52];
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
            <td class="hidden"> 
            <input type="hidden" name="unitprice1[]" id="unitprice1{{$pp->id}}" ></td>
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
</div>
<script src="http://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript">
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
	function checkthis(arg){
    var input = document.getElementById(arg).value;
    if(isNaN(input)){
               document.getElementById(arg).value = "";
    }

}
function submiteditenq(){
  
         var x = document.getElementById("gstsa").value;
              if (x == "") {

                      alert("Are Sure Customer Dont have GST Number ?");
               }
               

                 document.getElementById("sub").submit();

            
       
}

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
