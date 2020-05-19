
@extends('layouts.app')
@section('content')
<span class="pull-right"> @include('flash-message')</span>

<div class="container">
    <div class="row">
        <form method="GET" action="{{ URL::to('/') }}/enquiryassign">
                    <div class="col-md-12">
                            <div class="col-md-12">
                    <form method="GET" action="{{ URL::to('/') }}/categoryenquiry">
                        <div class="col-md-2">
                <label>Choose Ward :</label><br>
                          <select name="ward" class="form-control" id="ward" onchange="loadsubwards()">
                              <option value="">--Select--</option>
                              <option value="All">All</option>
                              @foreach($wards as $ward)
                              <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                              @endforeach
                          </select>
              </div>
              <div class="col-md-2">
                <label>Choose Subward :</label><br>
                          <select name="subward" class="form-control" id="subward">
                          </select>
              </div>
                                 <div class="col-md-2">
                                <label>Select Category</label>
                               <select required class="form-control" name="cat">
                                <?php $category = App\Category::all(); ?>
                                  
                                 <option value="" selected="">--Select Category--</option>
                                  
                                   @foreach($category as $cat)
                                   <option value="{{$cat->category_name}}">{{$cat->category_name}}</option>
                                   @endforeach
                               </select>
                            </div><br>
                             <div class="col-md-2">
                              <button type="submit" class="form-control  btn btn-warning btn-sm">submit</button>  
                            </div>
                            <div class="col-md-2">
                              <a href="{{URL::to('/')}}/Categorywisecustomers" class="form-control  btn btn-warning btn-sm">Category wise customers</a>  
                            </div>
          </div>    
          </form>
      </div>
           <br> <br>
            <div class="row"> 
            <br>  <br>
            @if(count($enquiry) != 0)          
        <div class="col-md-12">
            <div class="panel panel-default" style="border-color:#0e877f;width:100%;">
                 
                   
                <div class="panel-heading" style="background-color:#0e877f;color:white;"> <span style="font-weight:bold;">   </span> &nbsp;&nbsp; <span style="font-weight:bold;">No Of Enquiries : {{ count($enquiry)  }}  </span>   </div>
                <div class="panel-body">
                  <form action="{{URL::to('/')}}/categoryenquiry" method="post" >
                    
                 {{ csrf_field() }}
                 <div class="col-md-4">
                 <select  name="user"  style="width:300px;" class="form-control">
                    <option value="">--Select--</option>
                   <?php $users = App\User::where('department_id','!=',10)->get();  ?>
                   @foreach($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>
                     @endforeach
                  </select>
                </div>
                 <div class="col-md-2">
                  <input type="submit" class="btn btn-sm btn-warning" value="Assign">
                </div>
                  <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0"
                        width="100%">
                       <thead>
                        <th>Select All <br>
                          <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th>
                         <th>Customer Name</th>
                         <th>Category</th>
                        <th>Enquiry Id</th>
                        <th>IDS</th>
                        <th>Enquiry Status</th>
                        <th>Order Id</th>
                        <th>Order Status</th>
                        <th>Remark</th>

                       </thead>
                      <tbody>
                        <?php $z=1; 
                           
                        ?>
                        @foreach($enquiry as $pro)
                       <tr>
                         
                        <td><input type="checkbox" name="number[]" class="regular-checkbox name"  id="checkbox-1-1" value="{{$pro->id}}" /><label for="checkbox-1-1"></label></td>
                              <td>
                               <br>
                               <?php $s = App\ProcurementDetails::where('project_id',$pro->id)->pluck('procurement_name')->first(); ?>
                               {{$s}}
                              </td>
                             <td>
                               {{$pro->main_category}}
                              </td> 
                              <td>
                                <a href="{{ URL::to('/') }}/editenq?reqId={{ $pro->id }}">{{$pro->id}} </a>
                              </td>
                              <td>
                                @if($pro->project_id != NULL)
                              <a target="_none" href="{{URL::to('/')}}/showThisProject?id={{$pro->project_id}}">  P: {{$pro->project_id}}</a>
                                @else
                               <a href="{{ URL::to('/') }}/viewmanu?id={{ $pro->manu_id }}">M: {{$pro->manu_id}}</a>
                               @endif
                                
                              </td>
                              <td>
                                 {{$pro->status }}


                              </td>
                              <td>  <?php $ss = DB::table('orders')->where('req_id',$pro->id)->pluck('id')->first(); ?>
                                {{$ss}}
                              </td>
                              <td>{{$pro->order != null ? $pro->order->status : ''}}</td>
                              <td>
                                   {{$pro->notes}}

                              </td>
                              </tr>
                         @endforeach

                      </tbody>


                   </table>
                         
                    </form>
                </div>
            </div>
        </div>
        @endif
           
  </div>
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
                        var html = "<option value='' disabled selected>---Select---</option>";
                          html  += "<option value='All'  >All</option>";
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
<script type="text/javascript">
  
    $(function () {
        // add multiple select / deselect functionality
        $("#selectall").click(function () {
            $('.name').attr('checked', this.checked);
        });
 
        // if all checkbox are selected, then check the select all checkbox
        // and viceversa
        $(".name").click(function () {
 
            if ($(".name").length == $(".name:checked").length) {
                $("#selectall").attr("checked", "checked");
            } else {
                $("#selectall").removeAttr("checked");
            }
 
        });
    });
</script>
@endsection
