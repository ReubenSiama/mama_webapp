
@extends('layouts.app')
@section('content')
<div class="container">
   
           <div class="row">             
        <div class="col-md-12">
            <div class="panel panel-default" style="border-color:#0e877f;width:100%;">
                 
                   
                <div class="panel-heading" style="background-color:#0e877f;color:white;"> <span style="font-weight:bold;">No of  Customers : {{ $project->total() }}   </span> &nbsp;&nbsp; <span style="font-weight:bold;">No of  Projects : {{ $projectscount }}   </span>   </div>
                <div class="panel-body">
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
                  <input type="submit" class="btn btn-sm btn-warning" value="ReAssign">
                </div>
                  <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0"
                        width="100%">
                       <thead>
                        <th>Select All <br>
                          <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th>
                         <th>Customer Name</th>
                         <th>No Of projects</th>
                        
                        <th> Projects Details</th>
                        <th>Total Size</th>
                       </thead>
                      <tbody>
                        <?php $z=1 
                           
                        ?>
                        @foreach($project as $pro)
                       <tr>
                         
                        <td><input type="checkbox" name="number[]" class="regular-checkbox name"  id="checkbox-1-1" value="{{$pro['procnumber']}}" /><label for="checkbox-1-1"></label></td>
                              <td>
                               <br>
                               {{$pro['procname']}}
                              </td>
                             <td>
                               {{$pro['customerprojectcount']}}
                              </td> 
                            
                              <td>
                              <table class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                                      <thead>
                          <th>ProjectIds</th>
                         <th>Last Updated</th>
                         <th>ProjectSize</th>
                         <th>Stage</th>
                         <th>Remarks</th>
                         <th>Fix Date </th>

                                      </thead>
                                      <tbody>
                              @foreach($pro['full'] as $projectinfo)
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
                                 {{$pro['totalsize']}}


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

@endsection
