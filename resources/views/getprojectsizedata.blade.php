
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <form method="GET" action="{{ URL::to('/') }}/getprojectsizedata">
                    <div class="col-md-12">
                            <div class="col-md-3">
                                <label>From (projectsize in sqt)</label>
                                <input required value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="number" class="form-control" name="from">
                            </div>
                            <div class="col-md-2">
                                <label>To (projectsize in sqt)</label>
                                <input required value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="number" class="form-control" name="to">
                            </div>
                                 <div class="col-md-2">
                                <label>Select Stage</label>
                               <select required class="form-control" name="status">
                                   <option value="All">All</option>
                                   <option value="Planning">Planning</option>
                                   <option value="Digging">Digging</option>
                                 
                                   <option value="Pillars">Pillars</option>
                                   <option value="Walls">Walls</option>
                                 
                                 
                                   <option value="Roofing">Roofing</option>
                                   <option value="Electrical">Electrical</option>
                                   <option value="Plumbing">Plumbing</option>
                                   <option value="Plastering">Plastering</option>
                                   <option value="Flooring">Flooring</option>
                                   <option value="Carpentry">Carpentry</option>
                                   <option value="Paintings">Paintings</option>
                                   <option value="Fixtures">Fixtures</option>
                                   <option value="Completion">Completion</option>
                               </select>
                            </div>
                            <div class="col-md-2">
                          <?php $wards = App\Ward::all(); ?>
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
                             <div class="col-md-1">
                              <label>Get</label>
                              <button type="submit" class="form-control  btn btn-warning btn-sm">submit</button>  
                            </div>
          </div>    
          </form>
      </div>
           <br> 
           <div class="row">             
                    <div class="col-md-12">
                       <div class="panel panel-default" style="border-color:#0e877f;overflow-x: scroll;">
                 <?php $ddd = []; ?>
                   
                <div class="panel-heading" style="background-color:#0e877f;color:white;"> <span style="font-weight:bold;">No of  Customers : {{ $duplicates->total() }}   </span> &nbsp;&nbsp;  </div>
                <div class="panel-body">
                
                <script type="text/javascript">
  function yadav(){
  
     document.getElementById('form_id').submit();
  }
</script>
                  <form action="{{URL::to('/')}}/customerdetailslist" method="post" >
                    
                 {{ csrf_field() }}
                 <div class="col-md-4">
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

               
               
                


                  <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0"
                        width="100%">
                       <thead>
                        <th>Select All <br>
                          <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th>
                         <th>Customer Name</th>
                         <th>Customer Id</th>
                         <th>No Of projects</th>                        
                        <th> Projects Details</th>
                        <th>Total Size</th>
                       </thead>
                      <tbody>
                        <?php $z=1 
                           
                        ?>
                        @foreach($duplicates as $pro)
                         @if(count($pro->products_count) != 0)
                       <tr>
                      
                             
                         
                        <td><input type="checkbox" name="number[]" class="regular-checkbox name"  id="checkbox-1-1" value="{{$pro->procurement_contact_no}}" /><label for="checkbox-1-1"></label></td>
                              <td>
                               <br>
                               {{$pro->procurement_name}} 
                              </td>
                              <td>
                               <br>
                                   <?php $cid = App\CustomerDetails::where('mobile_num',$pro->procurement_contact_no)->pluck('customer_id')->first(); 
                                     

                                   ?>
                                   {{$cid}}
                              </td>
                             <td>
                               {{$pro->products_count}}
                                 <?php array_push($ddd,$pro->products_count); ?>
                              </td> 
                            
                              <td>
                              <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                           <thead>
                          <th>ProjectIds</th>
                        <!--  <th>Address</th> -->
                         <th>Last Updated</th>
                         <th>ProjectSize</th>
                         <th>Stage</th>
                         <th>Remarks</th>
                         <th>Fix Date </th>

                                      </thead>
                                      <tbody>
                                        <?php $proids = App\ProcurementDetails::where('procurement_contact_no',$pro->procurement_contact_no)->pluck('project_id');
                                             $projects = App\ProjectDetails::whereIn('project_id',$proids)->where('project_status','NOT LIKE',"Closed")->where('quality',"!=","Fake")->get(); 
                                             ?>
                                  @foreach($projects as $projectinfo)
                                        
                                        <tr>
                                          <td><a target="_none" href="{{URL::to('/')}}/showThisProject?id={{$projectinfo->project_id}}"> {{$projectinfo->project_id}}</a><br></td>
                                     

                                          <td>{{ date('d-m-Y', strtotime(  $projectinfo->updated_at)) }} <br> &nbsp;&nbsp; <span style="font-weight:bold;color:black;">{{$projectinfo->upuser !=null ? $projectinfo->upuser->name : ''}}</span></td>
                                       <td> {{$projectinfo->project_size}}<br></td>
                                       
                                       <td> {{$projectinfo->project_status}}<br></td>
                                       <td>

                                                 {{$projectinfo->remarks}}
                                            
                                          

                                          </td>
                                          <td>
                                            <form id="subform" action="{{URL::to('/')}}/fixdate" method="post">
                                               {{ csrf_field() }}
                                              <input type="date" name="fixdate">
                                              <input type="hidden" name="project" value="{{$projectinfo->project_id}}">
                                              <button onclick="submitform()"  class="btn btn-warning btn-sm">Fix</button>

                                          </form>
                                        </td>
                                        </tr>
                                @endforeach
                                      </tbody>
                                  </table>
                                
                              </td>
                              <td>
                                 {{$pro->yes}}


                              </td>
                
                            </tr>
                            @endif
                         @endforeach

                      </tbody>
                  </table>
                         {{ $duplicates->appends(request()->query())->links() }}
                    </form>
                </div>
            </div>
        </div>
      
    </div>
  </div>
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
<script type="text/javascript">
 function  submitform(){
   document.getElementById('subform').submit();
 }
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
                        var html = "<option value='' disabled selected>---Select---</option>";
                              html += "<option value='All'>All</option>";

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

<script>

</script>

@endsection

