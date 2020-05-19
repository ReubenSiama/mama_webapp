<?php
    
    $ext = "layouts.app";
?>
@extends($ext)
@section('content')
<div class="col-md-2" style="overflow-y:scroll; height:570px; max-height:570px">
        <div class="panel panel-primary" style="overflow-x:scroll">
            <div class="panel-heading text-center">
                <b style="color:white">BrandWise Report</b>
            </div>
            <?php $users = App\brand::where('category_id',36)->get(); ?>
            <div class="panel-body">
				@if(Auth::user()->department_id != 1)
            	<form method="GET" action="{{ URL::to('/') }}/usingbrand">
				@else
				<form method="GET" action="{{ URL::to('/') }}/usingbrand">
				@endif
                    <table class="table table-responsive">
	                    <tbody>
	                        <tr>
	                            <td>Select  Brand</td>
	                        </tr>
                            <tr>
                                <td>
                                    <select required name="brand" class="form-control" id="category2">
                                        <option disabled selected value="">(-- SELECT SE --)</option>
                                        
                                       
                                            @foreach($users as $list)
                                            <option {{ isset($_GET['cat']) ? $_GET['cat'] == $list->id ? 'selected' : '' : ''}}  value="{{$list->id}}">{{$list->brand}}</option>
                                            @endforeach
                                       
                                </select>
	                            </td>
	                        </tr>
	                       
	                        <tr>
	                            <td>Slect Manufacturer Type</td>
	                        </tr>
	                        <tr>
	                            <td>
	                   <select class="form-control" name="type">
                     <option value="">--Manufacuturer Type--</option>
                     <option value="RMC">RMC</option>
                     <option value="Blocks">BLOCKS</option>
                     <option value="M-SAND">M-SAND</option>
                     <option value="AGGREGATES">AGGREGATES</option>
                     <option value="Fabricators">FABRICATORS</option>
                 </select>
	                            </td>
	                        </tr>
	                        <tr class="text-center">
	                            <td>
	                                <button class="btn bn-md btn-success" style="width:100%">Get Report</button>
	                            </td>
	                        </tr>
	                    </tbody>
	                </table>
            	</form>
            </div>
        </div>
       
    </div>

<div class="col-md-10">
    <div class="panel panel-primary" style="overflow-x:scroll">
        <div class="panel-heading" id="panelhead">
            <label>
            	
            		
            </label>
            <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color:black;"></i></button>

            <span style="font-weight:bold;">Total Customers In  {{$brand}} &nbsp;&nbsp;{{count($data)}}</span>
           
        </div>
        <div class="panel-body" style="overflow-y:scroll; height:700px; max-height:700px">
            <table class='table table-responsive table-striped' style="color:black" border="1">
                <thead>
                    <tr>
                        <th>Manufacturer Id</th>
                        <th>Interested Brand</th>
                        <th>Price</th>
                       
                        <th>Name</th>
                        <th>Number</th>
                        <th>Subward</th>
                        <th>Manufacturer Type</th>
                      
                        
                       
                       
                    </tr>
                </thead>
                <tbody id="mainPanel">
                    @foreach($data as $df)
                	<tr>
                     <td><a href="{{URL::to('/')}}/viewmanu?id={{$df->id}}"> {{$df->id}}</a></td>
                     <td> <?php $br = $df->customerbrands->brand ?? ''; 
                       
                          $s = explode(",",$br);



                       ?>
                       @foreach($s as $bra)
                          <?php $name = App\brand::where('id',$bra)->pluck('brand')->first(); ?>
                         {{$name}}<br>
                         @endforeach
                       </td>
                     <td>
                       <?php $br = $df->customerbrands->price ?? ''; 
                       
                          $s = explode(",",$br);



                       ?>
                       @foreach($s as $bra)
                          
                         {{$bra}}<br>
                         @endforeach

                     </td>
                     <td>{{$df->proc != null ?$df->proc->name : ''}}</td>
                     <td>{{$df->proc != null ?$df->proc->contact : ''}}</td>
                      <td>{{$df->subward != null ?$df->subward->sub_ward_name : ''}}</td>
                      <td>{{$df->manufacturer_type}}</td>
                     
                    </tr>   
                    @endforeach 
                </tbody>
            </table>
            
        </div>
    </div>
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
</script>
@endsection
