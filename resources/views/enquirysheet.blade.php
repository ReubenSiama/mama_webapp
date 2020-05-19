<?php
	$user = Auth::user()->group_id;
	$ext = ($user == 4 ? "layouts.amheader":"layouts.app");
?>
@extends($ext)
@section('content')
<div class="">
	<div class="col-md-12">
  <span class="pull-right"> @include('flash-message')</span>
		
		<div class="panel panel-primary">
			<div class="panel-heading text-center">
					<a href="{{ URL::to('/') }}/inputview" class="btn btn-danger btn-sm pull-left">Add Enquiry</a>
					
					<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
					<p class="pull-left" style="padding-left: 50px;" id="display" >
				</p>
					
				Enquiry Data : {{$totalenq}}
				 <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color: black;"></i></button>
				
					
				
			</div>
			<div class="panel-body" style="overflow-x: auto">
			
					
			@if(Auth::user()->group_id == 1)
				<form method="GET" action="{{ URL::to('/') }}/adenquirysheet">
			@elseif(Auth::user()->group_id == 17)
				<form method="GET" action="{{ URL::to('/') }}/scenquirysheet">
			@else
				<form method="GET" action="{{ URL::to('/') }}/tlenquirysheet">
			@endif
					<div class="col-md-12">
							<div class="col-md-2">
								<label>From (Enquiry Date)</label>
								<input value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from">
							</div>
							<div class="col-md-2">
								<label>To (Enquiry Date)</label>
								<input  value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="date" class="form-control" name="to">
							</div>
							 @if(Auth::user()->group_id == 22)
							<div class="col-md-2">
								<label>Ward</label>
								<select   name="enqward" id="ward" onchange="loadsubwards()" class="form-control ">
									<option value="">--Select--</option>

									@foreach($mainward as $wards2)
									           @foreach($wardwise as $yadav)
									             @if($wards2->id == $yadav->id)
		                                 <option value="{{$wards2->id}}">{{$wards2->ward_name}}</option>
		                                         @endif
		                                 @endforeach
		                            @endforeach
								</select>
							</div>
                            @else

							<div class="col-md-2">
								<label>Ward</label>
								<select name="enqward" id="ward" onchange="loadsubwards()" class="form-control ">
									<option value="">--Select--</option>

									@foreach($mainward as $wards2)
									          
		                                 <option value="{{$wards2->id}}">{{$wards2->ward_name}}</option>
		                                        
		                            @endforeach
								</select>
							</div>
							@endif
							<div class="col-md-2">
								<label>Sub Wards</label>
								<select class="form-control" name="ward" id="subward">
									<!-- <option value="">--Select--</option>
									<option value="">All</option> -->
									<!-- @foreach($wards as $ward)
									<option {{ isset($_GET['ward']) ? $_GET['ward'] == $ward->id ? 'selected' : '' : '' }} value="{{ $ward->id }}">{{ $ward->sub_ward_name }}</option>
									@endforeach -->
								</select>
							</div>
						<div class="col-md-2">
							<label>Initiator</label>
							<select class="form-control" name="initiator">
								<option value="">--Select--</option>
								<option value="">All</option>
								@foreach($initiators as $initiator)
								<option {{ isset($_GET['initiator']) ? $_GET['initiator'] == $initiator->id ? 'selected' : '' : '' }} value="{{ $initiator->id }}">{{ $initiator->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="col-md-2">
							<label>Category:</label>
							<select id="categ" class="form-control" name="category">
								<option value="">--Select--</option>
								<option value="">All</option>
								@foreach($category as $category)
								<option {{ isset($_GET['category']) ? $_GET['category'] == $category->category_name ? 'selected' : '' : '' }} value="{{ $category->category_name }}">{{ $category->category_name }}</option>
								@endforeach
							</select>
						</div>
				
						<div class="col-md-12">
						<div class="col-md-2 col-md-offset-10">
							<input style="margin-top: 20px;margin-left:17px;" type="submit" value="Fetch" class="form-control btn btn-primary">
						</div>
					</div>
				</div>
				
				<br><br>
			</form>
				<div class="col-md-3" style="margin-top: -20px;">
					<div class="col-md-2">
						<label>Status: </label>
					</div>
					<div class="col-md-6">
						<select id="myInput" required name="status" onchange="myFunction()" class="form-control input-sm">
							<option value="">--Select--</option>
							<option value="all">All</option>
							<option value="Enquiry On Process">Enquiry On Process</option>
							<option value="Enquiry Confirmed">Enquiry Confirmed</option>
						</select>
					</div>
			     
                  </div>
                  <div class="col-md-3" style="margin-top: -20px;">
					<div class="col-md-2">
						<label style="color:black;font-weight:bold;">Show Entries: </label>
					</div>
					<div class="col-md-6">
						<form method="GET" action="{{ URL::to('/') }}/tlenquirysheet">
						<select   name="yup" onchange="this.form.submit()" class="form-control input-sm">
							<option value="">--Select--</option>
							<option value="200">200</option>
							<option value="300">300</option>
							<option value="400">400</option>
							<option value="500">500</option>
							<option value="600">600</option>
							<option value="700">700</option>
							<option value="800">800</option>
							<option value="900">900</option>
							<option value="1000">1000</option>
							<option value="{{$totalenq}}">{{$totalenq}}</option>
						</select>
					</form>
					</div>
					
                  </div>
               
                <br><br>
				<table id="myTable" class="table table-responsive table-striped table-hover" >
					<thead>
						<tr>
							<th style="text-align: center">Project_Id</th>
							<th style="text-align: center">SubWard No</th>
							<th style="text-align: center">Project Name</th>
							<th style="text-align: center">Requirement Date</th>
							<th style="text-align: center">Enquiry Date</th>
							<th style="text-align: center">Customer Contact</th>
							<th style="text-align: center">Product</th>
							<th style="text-align: center">Enquiry Quantity</th>
							<!-- <th style="text-align: center">Enquiry Quantity</th> -->
							<th style="text-align: center">Total Quantity</th>
							<th style="text-align: center">Price</th>
							<th style="text-align: center">Total Amount</th>
							<th style="text-align: center">Initiator</th>
							<th style="text-align: center">Confirmed by</th>
							<th style="text-align: center">Last Updated</th>
							<th style="text-align: center">Enquiry Status</th>
							<th style="text-align: center">Remarks</th>
							<th style="text-align: center">Status</th>
							<!-- <th style="text-align: center">Update Status</th> -->
							<th style="text-align: center">Edit</th>
						</tr>
					</thead>
					<tbody>
						<?php $pro=0; $con=0; $total=0; $sum=0; $sum1=0; $sum2=0; ?>
						@foreach($enquiries as $enquiry)

							@if($enquiry->status == "Enquiry On Process")
							<?php	$pro++; ?>
								<?php $sum = $sum + $enquiry->total_quantity; 
								 ?>
								
							@endif

							@if($enquiry->status == "Enquiry Confirmed")
							<?php	$con++; 
							 ?>
									<?php $sum1 = $sum1 + $enquiry->total_quantity; 
									 ?>
							@endif

							@if($enquiry->status == "Enquiry Confirmed" || $enquiry->status == "Enquiry On Process")
							<?php  $total++; 
							?>
								
									<?php $sum2 = $sum2 + $enquiry->total_quantity; 
									 ?>
								
							@endif
                        @endforeach
                        
						@foreach($enquiries as $enquiry)
                        @if($enquiry->status != "Not Processed")
                            @if($enquiry->project_id != NULL)
							<td style="text-align: center">
								<a target="_blank" href="{{URL::to('/')}}/showThisProject?id={{$enquiry -> project_id}}">
									<b>{{$enquiry->project_id }}</b>
								</a> 
							</td>
							
							<td style="text-align: center">
                               @foreach($wards as $ward)
                                 @if($ward->id ==($enquiry->project != null ? $enquiry->project->sub_ward_id : $enquiry->sub_ward_id) )
                                <a href="{{ URL::to('/')}}/viewsubward?projectid={{$enquiry->project_id}} && subward={{ $ward->sub_ward_name }}" target="_blank">
                                    {{$ward->sub_ward_name}}
                                </a>
                                  @endif
                               @endforeach
                            </td>


							<td style="text-align: center">{{ $enquiry->procurementdetails != null ? $enquiry->procurementdetails->procurement_name :''  }}
                       {{ $enquiry->proc != null ? $enquiry->proc->name :''  }}
							</td>
							<td style="text-align: center">{{$newDate = date('d/m/Y', strtotime($enquiry->requirement_date)) }}</td>
							<td style="text-align: center">{{ date('d/m/Y', strtotime($enquiry->created_at)) }}</td>
							<td style="text-align: center">{{ $enquiry->procurementdetails != null ? $enquiry->procurementdetails->procurement_contact_no : '' }}
							 {{ $enquiry->proc != null ? $enquiry->proc->contact :''  }}</td>
							<td style="text-align: center"><b>{{$enquiry->brand}}</b><br>{{$enquiry -> main_category}} 
								@if($enquiry->main_category == "STEEL" )
								<?php
									$id = explode(",",$enquiry->sub_category);
								
								?>
								(
								@for($i=0; $i<count($id) ; $i++)

								   <?php
								   $name = App\SubCategory::where('id',$id[$i])->pluck('sub_cat_name')->first();
								   if($name == null){
								   	$name = $enquiry->sub_category;
								   }
								   ?>
											{{$name}},
								@endfor
								)
								@else
								<?php
								   $name = App\SubCategory::where('id',$enquiry->sub_category)->pluck('sub_cat_name')->first();
								   if($name == null){
								   	$name = $enquiry->sub_category;
								   }
								   ?>
								({{$name}})
								@endif
							</td>
							<td style="text-align: center">
								<?php $quantity = explode(", ",$enquiry->quantity); ?>
								@for($i = 0; $i<count($quantity); $i++)
								{{ $quantity[$i] }}<br>
								@endfor
							</td>
							<!-- <td style="text-align: center">{{ $enquiry->enquiry_quantity }}</td> -->
							@if($enquiry->main_category == "STEEL" )
							<td style="text-align: center">
								<?php $quan = explode(", ",$enquiry->steelquantity); ?>
								@for($i = 0; $i<count($quan); $i++)
								{{ $quan[$i] }}<br>
								@endfor
							</td>
							<td style="text-align: center">
								<?php $price = explode(", ",$enquiry->steelprice); ?>
								@for($i = 0; $i<count($quan); $i++)
								{{ $price[$i] }}<br>
								@endfor
							</td>
							<td style="text-align: center">
								<?php $price = explode(", ",$enquiry->steelprice);
								$quan = explode(",",$enquiry->steelquantity);
								 ?>
								@for($i = 0; $i<count($quan); $i++)
							
								@endfor
							</td>
							@else
							<td style="text-align: center">{{ $enquiry->total_quantity }}</td>
							<td style="text-align: center">{{$enquiry->price }}</td>
                                <td> <?php  $total=(( $enquiry->total_quantity ) * ($enquiry->price ) ) ?>
                                    {{$total}}
                                </td>
							@endif
							<!-- <td style="text-align: center">{{ $enquiry->user != null ? $enquiry->user->name : '' }}</td> -->
							<?php $up1 = App\Requirement::where('id',$enquiry->id)->pluck('generated_by')->first();
							$up = App\User::where('id',$up1)->pluck('name')->first();
							  ?>
							<td>{{ $up }}</td>
							<td style="text-align: center">
							{{ $enquiry->conuser != null ? $enquiry->conuser->name : '' }}
							</td>
							<td style="text-align: center">
                            <?php $up = App\Requirement::where('id',$enquiry->id)->pluck('updated_at')->first(); ?>
								{{ $up }}
								{{ $enquiry->user2 != null ? $enquiry->user2->name : '' }}
							</td>
							<td style="text-align: center">
								{{ $enquiry->status}}
							</td>
							<td style="text-align: center" onclick="edit('{{ $enquiry->id }}')" id="{{ $enquiry->id }}">
								<form method="POST" action="{{ URL::to('/') }}/editEnquiry">
									{{ csrf_field() }}
									<input type="hidden" value="{{$enquiry->id}}" name="id">
									<input onblur="this.className='hidden'; document.getElementById('now{{ $enquiry->id }}').className='';" name="note" id="next{{ $enquiry->id }}" type="text" size="35" class="hidden" value="{{ $enquiry->notes }}"> 
									<p id="now{{ $enquiry->id }}">{{$enquiry->notes}}</p>
								</form>
							</td>

                           


							@if($enquiry->status == "Enquiry Confirmed")
							<td>
										<label>
										 Enquiry Confirmed 
									<input {{ $enquiry->status = "Enquiry Confirmed" ? 'checked' : '' }}  type="radio" name="status" class="form-control" data-toggle="modal" data-target="#myModal{{$enquiry->id}}">
									</label>
                                      <label>
										 Enquiry Cancelled  
									<input type="radio" name="status" class="form-control" data-toggle="modal" data-target="#myModal11{{$enquiry->id}}">
									</label>
                                     </td>
                               @else
                                     <td>
                                     	<label>
										 Enquiry Confirmed 
									<input {{ $enquiry->status = "Enquiry Confirmed" ? 'checked' : '' }}  type="radio" name="status" class="form-control" data-toggle="modal" data-target="#myModal{{$enquiry->id}}">
									</label>
									  <label>
										 Enquiry Cancelled  
									<input type="radio" name="status" class="form-control" data-toggle="modal" data-target="#myModal11{{$enquiry->id}}">
									</label>
							       </td>
                                  @endif
							<td>
								<a href="{{ URL::to('/') }}/editenq?reqId={{ $enquiry->id }}" class="btn btn-xs btn-primary">Edit</a>
							</td>
							
						</tr>
                       @endif
						@endif
						@endforeach   
						
					</tbody>
					<!--  <tr>
						<td style="text-align    : center"></td>
					        <td style="text-align: center"></td>
					        <td style="text-align: center"></td>
					        <td style="text-align: center"></td>
					        <td style="text-align: center"></td>
					        <td style="text-align: center"></td>
					        <td style="text-align: center">Total</td>
						 	<td style="text-align: center">{{ $totalofenquiry }}</td>
						 	<td style="text-align: center"></td>
					        <td style="text-align: center"></td>
					        <td style="text-align: center"></td>
					        <td style="text-align: center"></td>
					        <td style="text-align: center"></td>
					</tr> -->
					
				</table>
				<!-- <table>
					<tbody>
						<tr>total</tr>
					</tbody>
				</table> -->
			</div>
			<div class="panel-footer">
				
			</div>
		</div>
	</div>
</div>
@foreach($enquiries as $enquiry)
  <div class="modal fade" id="myModal11{{$enquiry->id}}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Enquiry Details</h4>
        </div>
        <div class="modal-body">
<form method="get" action="{{ URL::to('/') }}/enquiryCancells" id="rrr{{$enquiry->id}}">
									{{ csrf_field() }}
<input type="hidden" value="{{$enquiry->id}}" name="eid">

 <label>Remarks</label>
   <textarea name="remark" class="form-control">
   	   
   </textarea>	<br>								

           
          <center><a class="btn btn-sm btn-warning"  onclick="document.getElementById('rrr{{$enquiry->id}}').submit()">Cancel Enquiry</a></center>
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
@endforeach










@foreach($enquiries as $enquiry)
  <div class="modal fade" id="myModal{{$enquiry->id}}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Enquiry Details</h4>
        </div>
        <div class="modal-body">
<form method="POST" action="{{ URL::to('/') }}/editEnquiry" id="yupsvs{{$enquiry->id}}">
	{{ csrf_field() }}
<input type="hidden" value="{{$enquiry->id}}" name="eid">
<input type="hidden" value="{{$enquiry->manu_id}}" name="manu_id">
<input type="hidden" name="status" value="Enquiry Confirmed">
          <table class="table">
          	<tr>
          		<td>Supllier Name</td>
          		<td>:</td>
          		<td> <?php $manudetails = App\ManufacturerDetail::where('category',$enquiry->main_category)->get(); ?>
                                        <select required class="form-control" id="name" name="sname" >
                                          <option>--Select--</option>
                                     @foreach($manudetails as $manu)           
                                                 
                                            <option {{ ( $enquiry->spname ==$manu->manufacturer_id) ? 'selected' : '' }}   value="{{$manu->manufacturer_id}}" >{{$manu->company_name}}</option>
                                     
                                    @endforeach
                                        </select>
                                      </td>
            </tr>	
            <tr>
          		<td>Supllier State</td>
          		<td>:</td>
          		<td>
      <select id="state"  class="form-control" name="state" >
          <option value="">----Select----</option>
          <option value="1"  {{ ( $enquiry->spstate ==1) ? 'selected' : '' }} >Karnataka</option>
          <option value="2" {{ ( $enquiry->spstate ==2) ? 'selected' : '' }} >AndraPradesh</option>
          <option value="3" {{ ( $enquiry->spstate ==3) ? 'selected' : '' }} >TamilNadu</option>

      </select>
     </td>
            </tr>	
            <tr>
          		<td>MamaHome  Amount</td>
          		<td>:</td>
          		<td><input type="text" name="mhamount" class="form-control" value="{{$enquiry->price * $enquiry->total_quantity}}"></td>
            </tr>
            <tr>
          		<td>Supplier Amount</td>
          		<td>:</td>
          		<td><input type="text" name="spamount" class="form-control" value="{{$enquiry->spamount}}"></td>
            </tr>
            <tr>
          		<td> Total Quantity</td>
          		<td>:</td>
          		<td><input  required type="number" class="form-control" name="quantity" placeholder="quantity" id="quan" onkeyup="checkthis('quan')" value="{{$enquiry->total_quantity}}"></td>
            </tr>

             <tr>
          		<td> Measurement Unit</td>
          		<td>:</td>
          		<td><input  type="text" name="Unit" value="{{$enquiry->measurement_unit}}" class="form-control" placeholder="Bags/Tons" required data-readonly></td>
            </tr>

             <tr>
          		<td>Mamahome Price(Per Unit) :</td>
          		<td>:</td>
          		<td> <input required type="number" id="unit"  class="form-control" name="mamaprice" placeholder="Unit Price"  value="{{$enquiry->price}}"></td>
            </tr>
           
             <tr>
          		<td>Payment Type</td>
          		<td>:</td>
          		<td>
		<select   class="form-control" name="paytype" >
          <option value="">----Select----</option>
          <option value="Cash"  {{ ( $enquiry->paytype =="Cash") ? 'selected' : '' }} >Cash (Full Payment Advance)</option>
          <option value="Advance" {{ ( $enquiry->paytype =="Advance") ? 'selected' : '' }} >Part Payment</option>
          <option value="Cashagainestdelivery" {{ ( $enquiry->paytype =="Cashagainestdelivery") ? 'selected' : '' }} >Cash Against Delivery</option>
          <option value="RTGS" {{ ( $enquiry->paytype =="RTGS") ? 'selected' : '' }} >RTGS(Online Payment)</option>

      </select>
     </td>
            </tr>



          </table>
          <center><a class="btn btn-sm btn-warning"  onclick="document.getElementById('yupsvs{{$enquiry->id}}').submit()">Get Order Id</a></center>
</form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
@endforeach
<script type="text/javascript">
	function edit(arg){
		document.getElementById('now'+arg).className = "hidden";
		document.getElementById('next'+arg).className = "";
		document.getElementById('next'+arg).focus();
	}
	function editm(arg){
		document.getElementById('noww'+arg).className = "hidden";
		document.getElementById('nextt'+arg).className = "form-control";
	}

</script>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script type="text/javascript">
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");

  filter = input.value.toUpperCase();

  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  // Loop through all table rows, and hide those who don't match the search query
  
  if(filter == "ALL"){
  	for (i = 0; i < tr.length; i++) {
        tr[i].style.display = "";
	  }
	}else{
		for (i = 0; i < tr.length; i++) {
	    td = tr[i].getElementsByTagName("td")[14];
	    if (td) {
	      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
	        tr[i].style.display = "";
	      } else {
	        tr[i].style.display = "none";
	      }
	    }
	  }
	}
	if(document.getElementById("myInput").value  == "Enquiry On Process"){
		
		if(document.getElementById("categ").value  != "All"){
		
				document.getElementById("display").innerHTML = "Enquiry On Process  :  {{  $pro }}   /  Quantity On Process : {{ $sum }} "
		 }
	}
	else if(document.getElementById("myInput").value == "Enquiry Confirmed"){
		if(document.getElementById("categ").value  != "All"){
		document.getElementById("display").innerHTML = "Enquiry Confirmed  :  {{  $con }}   /   Quantity On Confirmed : {{ $sum1 }}"
		}
	}
	else {

		if(document.getElementById("categ").value  != "All"){
		document.getElementById("display").innerHTML = "Total Enquiry Count  :  {{  $total }}   /   Total Quantity  :  {{ $sum2 }}  "
		}
	}


	// if(document.getElementById("myInput").value  == "Enquiry On Process"){

	// 	if(document.getElementById("categ").value  == "All Category"){
			
	// 	document.getElementById("display").innerHTML = "Enquiry On Process  :  {{  $pro }}"
	// 	}
	// }
	// else if(document.getElementById("myInput").value == "Enquiry Confirmed"){
		
	// 	if(document.getElementById("categ").value  == "All Category"){
	// 	document.getElementById("display").innerHTML = "Enquiry Confirmed  :  {{  $con }}"
	// 	}
	// }
	// else {
	// 	if(document.getElementById("categ").value  == "All Category"){
	// 	document.getElementById("display").innerHTML = "Total Enquiry Count  :  {{  $total }}"
	// }
	// }
}
</script>
 <script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
     background-color: #00acd6 

});
</script>
<script type="text/javascript">
    function loadsubwards()
    {
        var x = document.getElementById('ward');
        var sel = x.options[x.selectedIndex].value;
        if(sel)
        {
            $.ajax({
                type: "GET",
                url: "{{URL::to('/')}}/loadsubwards",
                data: { ward_id: sel },
                async: false,
                success: function(response)
                {
                    if(response == 'No Sub Wards Found !!!')
                    {
                        document.getElementById('error').innerHTML = '<h4>No Sub Wards Found !!!</h4>';
                        document.getElementById('error').style,display = 'initial';
                    }
                    else
                    {
                        var html = "<option value='' disabled selected>---Select---</option>"+"<option value='All'>ALL</option>";
                        for(var i=0; i< response.length; i++)
                        {
                            html += "<option value='"+response[i].id+"'>"+response[i].sub_ward_name+"</option>";
                        }
                        document.getElementById('subward').innerHTML = html;
                    }
                    
                }
            });
        }
    }
</script>
@endsection
