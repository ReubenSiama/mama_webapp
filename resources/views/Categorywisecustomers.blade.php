
@extends('layouts.app')
@section('content')
<div class="container">
     @if(Auth::user()->group_id == 2)
<span class="pull-right"> @include('flash-message')</span>
     
    <div class="row">
        <form method="GET" action="{{ URL::to('/') }}/Categorywisecustomers">
                    <div class="col-md-12">
                            <div class="col-md-12">
                    <form method="GET" action="{{ URL::to('/') }}/Categorywisecustomers">
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
                            
          </div>    
          </form>
      </div>
      @endif
           <br> <br>
          
            <div class="row">             
        <div class="col-md-12">
            <div class="panel panel-default" style="border-color:#0e877f;width:100%;">
                 
                   
                <div class="panel-heading" style="background-color:#0e877f;color:white;"> <span style="font-weight:bold;">No of  Customers : {{ $project->total() }}   </span> &nbsp;&nbsp; <span style="font-weight:bold;">No of  Enquiries : {{ $projectscount }} &nbsp;&nbsp;&nbsp;&nbsp;  </span>    </div>
                <div class="panel-body">
                  <form action="{{URL::to('/')}}/storecustomerenq" method="post" >
                    
                 {{ csrf_field() }}
                  @if(Auth::user()->group_id == 2)
                 <div class="col-md-4">
                  <?php $users = App\User::all() ?>
                 <select  name="user"  style="width:300px;" class="form-control">
                    <option value="">--Select--</option>
                   @foreach($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>
                     @endforeach
                  </select>
                </div>
                 <div class="col-md-2">
                  <input type="submit" class="btn btn-sm btn-warning" value="Assign">
                </div>
                @endif
                  <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0"
                        width="100%">
                       <thead>
                         @if(Auth::user()->group_id == 2)
                        <th>Select All <br>
                          <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th>
                        @endif    
                         <th>Customer Name</th>
                         <th>No Of Enquiries</th>
                           
                        <th> Enquiry Details</th>
                        
                       </thead>
                      <tbody>
                        <?php $z=1 
                           
                        ?>
                        @foreach($project as $pro)
                       <tr>
                          @if(Auth::user()->group_id == 2)
                        <td><input type="checkbox" name="number[]" class="regular-checkbox name"  id="checkbox-1-1" value="{{$pro['procnumber']}}" /><label for="checkbox-1-1"></label></td>
                        @endif
                              <td>
                               <br>
                               {{$pro['procname']}}
                              </td>
                             <td>
                               {{ count($pro['full']) }}
                              </td> 
                            
                              <td>
                              <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                      <thead>
                          <th>IDS</th>
                         <th>Enquiry Id</th>
                         <th>Category</th>
                         <th>Status</th>
                         <th>Order Id</th>
                         <th>Order Status</th>
                         <th>Remarks</th>

                                      </thead>
                                      <tbody>
                              @foreach($pro['full'] as $projectinfo)
                                        <tr>
                                          <td>
                                              @if($projectinfo->project_id != NULL)     
                                            <a target="_none" href="{{URL::to('/')}}/showThisProject?id={{$projectinfo->project_id}}"> P : {{$projectinfo->project_id}}</a>
                                            @else

                                              <a href="{{ URL::to('/') }}/viewmanu?id={{ $projectinfo->manu_id }}"> M : {{$projectinfo->manu_id}}</a>

                                             @endif
                                            <br></td>
                                          <td><a href="{{ URL::to('/') }}/editenq?reqId={{ $projectinfo->id }}">{{$projectinfo->id}} </a></span></td>
                                          <td>{{$projectinfo->main_category}}</td>
                                          <td> {{$projectinfo->status}}<br></td>
                                       <td> <?php $sss = DB::table('orders')->where('req_id',$projectinfo->id)->pluck('id')->first(); ?>
                                         {{$sss}}
                                       </td>
                                       <td>

                                                 {{$projectinfo->order !=null ? $projectinfo->order->status : ''}}
                                            
                                          

                                          </td>
                                          <td>
                                             {{$projectinfo->notes}}
                                        </td>
                                        </tr>
                                @endforeach
                                      </tbody>
                                  </table>
                                
                              </td>
                              </tr>
                         @endforeach

                      </tbody>


                   </table>
                         {{ $project->appends(request()->query())->links() }}
                    </form>
                </div>
            </div>
        </div>
      
    </div>
           
           
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
