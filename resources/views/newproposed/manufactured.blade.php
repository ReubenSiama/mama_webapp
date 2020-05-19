@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
  <span class="pull-right"> @include('flash-message')</span>

        <form method="GET" action="{{ URL::to('/') }}/manufactured">
                    <div class="col-md-12">
                            <!-- <div class="col-md-3">
                                <label>From (projectsize in sqt)</label>
                                <input required value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="number" class="form-control" name="from">
                            </div>
                            <div class="col-md-2">
                                <label>To (projectsize in sqt)</label>
                                <input required value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="number" class="form-control" name="to">
                            </div> -->
                                 <div class="col-md-2">
                                <label> Manufacturer Type</label>
                               <select required class="form-control" name="status">
                                <option value="">---Select---</option>
                                   <option value="All">All</option>
                                   <option value="RMC">RMC</option>
                                   <option value="Blocks">BLOCKS</option>
                                   <option value="M-SAND">M-SAND</option>
                                   <option value="AGGREGATES">AGGREGATES</option>
                                   <option value="Fabricators">FABRICATORS</option>
                                   
                               </select>
                            </div>
                            <div class="col-md-2">
                          <?php $wards = App\Ward::all(); ?>
                           <label>Choose Ward :</label><br>
                          <select name="ward" class="form-control" id="ward" onchange="loadsubwards()">
                              <option value="">--Select--</option>
                              
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
      @if(count($data) > 0)
      <h3 style="text-align:center";> <span style="background-color:#777777;color:white;font-weight:bold;"> Total Customers {{$data->total()}} </span>  <br>  Total Manufacturers {{$manuids}} </h3>
      @endif
      <form action="{{URL::to('/')}}/storeproposedmanu" method="post" id="test1" >
                    
                 {{ csrf_field() }}
       @if(count($data) > 0)
                  <div class="col-md-4">
                     <?php $users = App\User::where('department_id','!=',10)->get() ?>
                 <select  name="user"  style="width:300px;" class="form-control">
                    <option value="">--Select--</option>
                   @foreach($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>
                     @endforeach
                  </select>
                </div>
                 <div class="col-md-1">
                  <input onclick="document.getElementById('test1').submit()" class="btn btn-sm btn-warning" value="Assign">
                </div>
                @endif
      <br><br>

      <table class="table" border="1">
         <thead style="background-color:#9fa8da">
           <th>Select All <br>
          <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th>
           <th>Manufacturer Proposed Id</th>
           <th>NO Of Manufacturers</th>
           <th>Manufacturer Ids</th>
           <th>Grade</th>
           <th>##</th>
         </thead>
         <tbody>
          @foreach($data as $dump)
           <tr>
             <td><input type="checkbox" name="number[]" class="regular-checkbox name"  id="checkbox-1-1" value="{{$dump->p_p_c_id}}" /><label for="checkbox-1-1"></label></td>
            <?php   
                $projects =App\Manufacturer::where('p_p_c_id',$dump->p_p_c_id)->where('quality','Genuine')->get();
                         
                         

                         ?>
             <td>{{$dump->p_p_c_id}}</td>
             <td>{{$dump->products_count}}
                 
             </td>
             <td>
                <table class="table" border="1">
                   <thead>
                     <th>Manufacturer Id</th>
                     <th>Type</th>
                     
                   </thead>
                   <tbody>
                    @foreach($projects as $pro)
                     <tr>
                     <td><a href="{{ URL::to('/') }}/viewmanu?id={{$pro->id}}" target="_blank">{{ $pro->id }}</a></td>
                      <td>{{$pro->manufacturer_type}}</td>
                      

                     </tr>
                     @endforeach
                   </tbody>
                </table>  
                    
             </td>
            
             <td>{{$dump->grade}}</td>
            <td>
              <input type="hidden" name="id{{$dump->p_p_c_id}}">
              <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal{{$dump->p_p_c_id}}">set Grade </a>
            </td>
 
           </tr>
           @endforeach
         </tbody>
      </table>
    </form>
 @foreach($data as $dump)
 <div class="modal fade" id="myModal{{$dump->p_p_c_id}}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color:#718fcb">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Set Grade</h4>
        </div>
        <div class="modal-body">
           <form action="{{URL::to('/')}}/setgrademanu" method="post" id="yes">
                 {{ csrf_field() }} 
                
              
             <table class="table">
               <tr>
                 <td>Project Proposed Id</td>
                 <td>:</td>
                 <td><input type="text" name="id" class="form-control" value="{{$dump->p_p_c_id}}" readonly>  </td>
               </tr>
               <tr>
                 <td>Select Grade</td>
                 <td>:</td>
                 <td><select class="form-control" name="grade">
                       <option value="">---Select Grade---</option>
                       <option value="A">A</option>
                       <option value="B">B</option>
                       <option value="C">C</option>
                       <option value="D">D</option>
                       <option value="E">E</option>
                       <option value="F">F</option>
                       <option value="G">G</option>

                 </select></td>

             </table>
             <center><button class="btn btn-sm btn-warning" onclick="document.getElementById('yes').submit()" >Submit</button></center>
           </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
@endforeach




        @if(count($data) > 0)
       <center>{{ $data->appends(request()->query())->links()}} </center>
       
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
<script type="text/javascript">
   function getassign(arg){
   
      var x = arg;
       alert(x);
        if(x)
        {
            $.ajax({
                type: "GET",
                url: "{{URL::to('/')}}/deleteproposedprojects",
                data: { id: x },
                async: false,
                success: function(response)
                {

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
