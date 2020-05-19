<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)
@section('content')
<div class="container">
<div class="col-md-12">
    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;">Updated Projects
                <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color: black;"></i></button>
                @if(count($projects) != 0)
                 <!-- <span>&nbsp;&nbsp;&nbsp;</span>From <b>{{ date('d-m-Y', strtotime($previous)) }}</b> To <b>{{ date('d-m-Y', strtotime($today)) }}</b> -->
               Count : <b> {{ $projects->total() }}</b>
                 <!-- @if(isset($_GET['from']))
                <p class="pull-right"> Projects Not Been Updated from {{$_GET['from']}} to {{$_GET['to']}}</p>
                 @else
                <p class="pull-right"> Projects Not Been Updated In 30 Days.</p>
                 @endif -->
                @endif

                    @if(session('ErrorFile'))
                        <div class="alert-danger pull-right">{{ session('ErrorFile' )}}</div>
                    @endif 
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                    <form method="GET" action="{{ URL::to('/') }}/Unupdated">
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
                <label>Projects(From Date)</label>
                <input value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from">
              </div>
              <div class="col-md-2">
                <label>Projects(To Date)</label>
                <input  value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="date" class="form-control" name="to">
              </div>
              <div class="col-md-2">
                <label></label>
                <input type="button" value="Select Status" style="background-color:#444743;color:white;"  class="form-control btn btn-primary" data-toggle="modal" data-target="#unupdate">
              </div>
              <div class="col-md-2">
                <label></label>
                <input type="submit" value="Fetch" class="form-control btn btn-primary">
              </div>

                  <!-- Modal -->
                  <div class="modal fade" id="unupdate" role="dialog">
                    <div class="modal-dialog">
                    
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header" style="background-color: #478229;color: white;">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Project Status</h4>
                        </div>
                        <div class="modal-body">
                          

                             <div class="col-sm-12">
                                    <div class="col-md-3" >
                                            <label class="checkbox-inline">
                                              <input style="width: 33px;" id="planning" type="checkbox"  name="status[]" value="Planning"><span>&nbsp;&nbsp;&nbsp;</span>Planning
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="digging" type="checkbox"  name="status[]" value="Digging">Digging
                                            </label>
                                          
                                             <label class="checkbox-inline">
                                              <input id="foundation" type="checkbox"  name="status[]" value="Foundation">Foundation
                                            </label>
                                         
                                             <label class="checkbox-inline">
                                              <input id="pillars" type="checkbox"  name="status[]" value="Pillars">Pillars
                                            </label>

                                            <label class="checkbox-inline">
                                            <input id="walls" type="checkbox"  name="status[]" value="Walls">Walls
                                          </label>
                                          </div>
                                         <div class="col-md-3">
                                          
                                          <label class="checkbox-inline">
                                          <input id="roofing" style="width: 33px;" type="checkbox"  name="status[]" value="Roofing"><span>&nbsp;&nbsp;&nbsp;</span>Roofing
                                        </label>
                                       
                                          <label class="checkbox-inline">
                                          <input id="electrical" type="checkbox"  name="status[]" value="Electrical">Electrical
                                        </label>
                                       
                                          <label class="checkbox-inline">
                                          <input id="plumbing" type="checkbox"  name="status[]" value="Plumbing">Plumbing
                                        </label>

                                        <label class="checkbox-inline">
                                          <input id="plastering" type="checkbox"  name="status[]" value="Plastering">Plastering
                                        </label>
                                       
                                          <label class="checkbox-inline">
                                          <input id="flooring" type="checkbox"  name="status[]" value="Flooring">Flooring
                                        </label>

                                      </div>
                                       <div class="col-md-3">
                                        
                                          <label class="checkbox-inline">
                                          <input id="carpentry" style="width: 33px;" type="checkbox"  name="status[]" value="Carpentry"><span>&nbsp;&nbsp;&nbsp;</span>Carpentry
                                        </label>
                                       
                                          <label class="checkbox-inline">
                                          <input id="paintings" type="checkbox"  name="status[]" value="Paintings">Paintings
                                        </label>

                                        <label class="checkbox-inline">
                                          <input id="fixtures" type="checkbox"  name="status[]" value="Fixtures">Fixtures
                                        </label>
                                      
                                          <label class="checkbox-inline">
                                          <input id="completion" type="checkbox"  name="status[]" value="Completion">Completion
                                        </label>
                                        
                                          <label class="checkbox-inline">
                                          <input id="closed" type="checkbox"  name="status[]" value="Closed">Closed
                                        </label>
                                       </div>
                                 </div>
                             <div class="col-sm-5">
                    <h4 style="background-color:#9e9e9e;color:white;border: 1px solid gray;padding:5px;border-radius: 5px;">Contract Type</h4>
                    <label required class="checkbox-inline"><input id="constructionType3" name="contract_type" type="radio" value="Labour Contract">&nbsp;&nbsp;Labour Contract</label><br>
                    <label required class="checkbox-inline"><input id="constructionType4" name="contract_type" type="radio" value="Material Contract">&nbsp;&nbsp;Material Contract </label>     
                  </div>

                      </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Save</button>
                        </div>
                      
                    </div>
                  </div>
                  <!-- modal end -->
                  </div>




          </form>
          </div>
        <br><br><br><br>
          <form action="{{URL::to('/')}}/assignunupdateproject" method="post" >
                    
                 {{ csrf_field() }}
                  <div class="col-md-4">
                     <?php $users = App\User::where('department_id','!=',10)->get() ?>
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
          <table class="table table-hover">
          <thead>
             <th>Select All <br>
                          <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th>
            <th>Project Id</th>
            <th>Project Name</th>
            
            <th>Project Status</th>
            <th>Quality</th>
            <th>Address</th>
            <th>Last Update</th>
            <th>Remarks</th>
          </thead>
          
          @foreach($projects as $project)
          <tbody>
             <td><input type="checkbox" name="number[]" class="regular-checkbox name"  id="checkbox-1-1" value="{{$project->project_id}}" /><label for="checkbox-1-1"></label></td>
            <td style="text-align:center"><a href="{{ URL::to('/') }}/admindailyslots?projectId={{$project->project_id}}&&lename={{ $project->name }}" target="_blank">{{ $project->project_id }}</a></td>
            <td>{{ $project->project_name }}</td>
            
            <td>{{ $project->project_status }}</td>
            <td>{{ $project->quality }}</td>
            <td>
            @foreach($site as $sites)
              @if($sites->project_id == $project->project_id)
              <a href="#" >{{ $sites->address }}</a>
              @endif
              @endforeach
            </td>
            <td style="width:10%;">
              {{ date('d-m-Y', strtotime($project->updated_at)) }}
              @foreach($names as $name)
                @if($name->id == $project->updated_by)
                 {{ $name->name}} 
                @endif
                @endforeach
            </td>
            <td>{{ $project->remarks }}</td>
            
          </tbody>
          @endforeach
          
        </table>
      </label>
        @if(count($projects) != 0)
                {{ $projects->appends($_GET)->links() }}
                @endif
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
        console.log(this.checked);
 
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
