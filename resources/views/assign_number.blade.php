<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)

@section('content')
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">

        <div class="panel-heading" style="background-color: green;color: white;">Select Project Status Before Assigning The Phone Numbers  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
           

           <form method="GET" action="{{ URL::to('/') }}/assign_number">
                <input type="hidden" name="delete" value="delete">
                <input type="submit" value="Reset" class=" btn-danger btn btn-sm">
              </form>

       <center> <button class="btn btn-success " type="button" style="background-color: #00e676;color: white" data-toggle="modal" id="#myModal"  data-target="#myModal">Select Project Status</button>
        <button class="btn btn-success " type="button" style="background-color: #00e676;color: white" data-toggle="modal" id="#myModal10"  data-target="#myModal10">Select Manufacturer Type</button></center>
          <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-60px;margin-left:10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color:black;"></i></button>
        </div>
        <div class="panel-body">
           <form action="{{URL::to('/')}}/assign_number" method="get" id="yadav">
                            <select onchange="getrange()" name="range">
                              <option value="">--Select range --</option>
                              <option value="100">100</option>
                              <option value="200">200</option>
                              <option value="300">300</option>
                              <option value="400">400</option>
                              <option value="500">500</option>
                              <option value="600">600</option>
                              <option value="1000">1000</option>
                             <option value="2000">2000</option>

                          </select>
                        </form>
              <form method="POST" id="saveNumber" name="myform" action="{{ URL::to('/') }}/storecount" enctype="multipart/form-data">
                {{ csrf_field() }}    
<!--                 <input type="hidden" id="userId" name="user_id">
                <input type="hidden" id="number" name="num">               -->
             <table class="table table-responsive table-striped table-hover" class="table">       
                      <tr>
                        <td>
                          <h3 class="pull-right">
                         
                      </h3>
                         <h4>TOTAL :<b>{{$count }} <br><br>
                              </b> List Of Team Members</h4><br>
                              <?php
                                  $s=  $count;
                                ?> 
                <select name="user_id" onchange="this.form.submit()" class="form-control" style="width: 30%;">
                          <option value="">--Select--</option>
                          
                          @foreach($users as $user)  
                            <option value="{{ $user->id }}">{{$user->name}}</option>
                           @endforeach
                           @if(Auth::user()->group_id == 22)
                            @foreach($tlUsers as $user)  
                            <option value="{{ $user->id }}">{{$user->name}}</option>
                           @endforeach
                           @endif
                            </select>
                         
                          <center>  <h4>Phone Numbers</h4></center>

                        </td>
                     </tr> 

                   <tr>
                     <td>
                       <?php
                          $numbers = array();

                       ?>
                       <table class="table table-striped">
                       <tr>
                       <?php $i = 0; ?>
                        @foreach($number as $num)
                         <td>{{ $num->number }}</td>
                         <?php
                            $i++;
                            $temp = $num->number;
                            array_push($numbers, $temp);
                         
                          ?>
                         @if($i == 6)
                          </tr>
                          <?php $i = 0; ?>
                          @endif
                         @endforeach
                         <?php
                            $numb = implode(", ", $numbers);
                         ?>
                       </table>
                      <input type="hidden" name="num" value="{{ $numb }}">
                     </td>
                   </tr>
                </table>  
              </form> 
     {{$number->appends(request()->except('page'))->links()}}


     

      <form method="POST" name="myform" action="{{ URL::to('/') }}/sms" enctype="multipart/form-data">
     {{ csrf_field() }}
     <input type="hidden" id="userId" name="user_id">
      <!-- Modal -->
                <div id="myModal" class="modal fade" role="dialog">
                  <div class="modal-dialog" style="width: 70%;" >

                    <!-- Modal content-->
                    <div class="modal-content" >
                      <div class="modal-header" style="background-color:rgb(244, 129, 31);color:white">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="text-align: center;">Select Stages</h4>
                      </div>
                      <div class="modal-body">
                                    
                                   <div class="row">
                                     <div class="col-md-4">
                                       
                                     
                                       <label>Ward</label>
                <select name="ward" id="ward" onchange="loadsubwards()" class="form-control ">
                                    <option value="">--Select--</option>
                                   @foreach($ward as $wards2)
                                     <option value="{{$wards2->id}}">{{$wards2->ward_name}}</option>
                                @endforeach
                </select>
                                     </div>
                                       <div class="col-md-4">
                                       <label>Sub Wards</label>
                                        <select class="form-control" name="subward" id="subward">
                                       </select>
                                     </div>
                                   </div>
                                 
                                  <div class="row">
                                 <div class="col-sm-12">
                                    <table>
                                       <tr id="sp">
                                       <div class="checkbox">
                                      <lable><td style=" padding:20px 40px 20px 40px;" ><input type="checkbox" name="stage[]" value="Planning">&nbsp;&nbsp;Planning</td>
                                           <td  style=" padding:20px 40px 20px 40px;"><input type="checkbox" name="stage[]" value="Digging">&nbsp;&nbsp;Digging</td>
                                           <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Foundation">&nbsp;&nbsp;Foundation</td>
                                           <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Pillars">&nbsp;&nbsp;Pillars</td>
                                           <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Walls">&nbsp;&nbsp;Walls</td></lable>
                                         </div>
                                       </tr>    
                                         <tr id="sp">
                                       <div class="checkbox">
                                      <lable><td  style=" padding:20px 40px 20px 40px;"><input type="checkbox" name="stage[]" value="Roofing">&nbsp;&nbsp;Roofing</td>
                                           <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Electrical">&nbsp;&nbsp;Electrical</td>
                                           <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Plumbing">&nbsp;&nbsp;Plumbing</td>
                                           <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Plastering">&nbsp;&nbsp;Plastering</td>
                                           <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Flooring">&nbsp;&nbsp;Flooring</td></lable>
                                           </div>
                                       </tr>  
                                        <tr id="sp">
                                       <div class="checkbox">
                                      <lable>     <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Carpentry">&nbsp;&nbsp;Carpentry</td>
                                           <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Paintings">&nbsp;&nbsp;Paintings</td>
                                           <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Fixtures">&nbsp;&nbsp;Fixtures</td>
                                          <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Completion">&nbsp;&nbsp;Completion</td>
                                           <td  style=" padding:0 40px 0 40px;"><input type="checkbox" name="stage[]" value="Closed">&nbsp;&nbsp;Closed</td></lable>
                                           </div>
                                       </tr>    
                                      </table>
                                    </div>
                              </div>


                               <div class="modal-footer">
                                 <button type="submit"  class="btn btn-success">Submit</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>

                    </div>  
                  </div>
                </div>
              </div>
            </form>
        <form method="POST" name="myform" action="{{ URL::to('/') }}/sms" enctype="multipart/form-data">
     {{ csrf_field() }}
     <input type="hidden" id="userId" name="user_id">
      <!-- Modal -->
                <div id="myModal10" class="modal fade" role="dialog">
                  <div class="modal-dialog" style="width:70%;" >

                    <!-- Modal content-->
                    <div class="modal-content" >
                      <div class="modal-header" style="background-color:rgb(244, 129, 31);color:white">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="text-align: center;">Select Manufacturer Type</h4>
                      </div>
                      <div class="modal-body">
                                   <div class="row" style="text-align:center;">
                                     <div class="col-md-6">
                                       
                                    <input type="checkbox" name="type[]" value="RMC"><span style="font:bold;">&nbsp;&nbsp;&nbsp;RMC&nbsp;&nbsp;&nbsp;</span>
                                   <input type="checkbox" name="type[]" value="BLOCKS"> &nbsp;&nbsp;&nbsp;BLOCKS
                                    <input type="checkbox" name="type[]" value="M-SAND" > &nbsp;&nbsp;&nbsp;M-SAND&nbsp;&nbsp;&nbsp;
                                     <input type="checkbox" name="type[]" value="AGGREGATES" > &nbsp;&nbsp;&nbsp;AGGREGATES&nbsp;&nbsp;&nbsp;
                                      <input type="checkbox" name="type[]" value="Fabricators" > &nbsp;&nbsp;&nbsp;Fabricators&nbsp;&nbsp;&nbsp;
                                   </div>
                                     </div>
                               <div class="modal-footer">
                                 <button type="submit"  class="btn btn-success">Submit</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>

                    </div>  
                  </div>
                </div>
              </div>
            </form>
           </div>
    </div>
</div>
<script type="text/javascript">
  function makeUserId(arg){
  document.getElementById("userId").value = arg;
  
}
function submitMyNumber(arg){
  document.getElementById("userId").value = document.getElementById(arg+"userId").value;
  document.getElementById("number").value = document.getElementById(arg+"num").value;
  var form = document.getElementById("saveNumber");
  form.submit();
}
</script>
 @if(session('success'))
          <script>
            swal("Success","{{ session('success')}}","success");
          </script>
 @endif
 @if(session('NotAdded'))
          <script>
            swal("Error","{{ session('NotAdded')}}","error");
          </script>
  @endif
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
<script type="text/javascript">
  function getrange(){
   
  document.getElementById('yadav').submit();
  }

</script>
@endsection

