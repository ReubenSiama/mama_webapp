@extends('layouts.app')
@section('content')  
  
  <span class="pull-right"> @include('flash-message')</span>
	<div class="row">
<div class="col-md-10 col-md-offset-1 ">
       
        <div class="panel panel-danger" style="border-color:rgb(244, 129, 31) ">
            <div class="panel-heading" style="background-color: rgb(244, 129, 31);color:white;font-weight:bold;font-size:15px;">
              <center> Updated Manufacturer Deatils </center>
                
            </div>
            <div style="overflow-x: scroll;" class="panel-body">

                   
                    <div class="col-md-12" >
                  <form>
                 <div class="col-md-3">
                  
                <label>(From Date)</label>
                <input value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from" id="from">
              </div>
              <div class="col-md-3">
                <label>(To Date)</label>
                <input  value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="date" class="form-control" name="to" id="to">
              </div>
              <div class="col-md-3">

                <label>User Name</label>
                 <select class="form-control" name="type" id="type">
                   <option {{ isset($_GET['type']) ? $_GET['type']: '' }} value="">---Select---</option>
                  <option value="All">All</option>
                   @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                   @endforeach   
                 </select>
              </div>
                  <div class="col-md-2">
                <label>submit</label>
                <button onclick="getdeatis()" value="Fetch" class="form-control btn btn-primary">Fetch</button>
              </div>
                </form>
                  </div> 
                <br><br><br>
                @if(count($onlySoftDeleted) != 0)
                 <h3>Total Projects {{$onlySoftDeleted->total()}}</h3>
                 @endif
                <table id="manufacturer" class="table table-responsive" border=1><br>
                    <thead>
                        <th>Manufacturer Id</th>
                        <th>Called Date</th>
                         <!--  <th>Blocked By</th> -->
                          <th>Call Status</th>
                          <th>Quality</th>
                          <th>Updated By</th>
                          <th>Remark</th>
                          <td>Updated Values</td>

                    </thead>
                    <?php $i=1 ?>
                    <tbody>
                      @foreach($onlySoftDeleted as $data)
                      <tr>
                        <td><a href="{{ URL::to('/') }}/admindailyslots?projectId={{$data->project_id}}" target="_blank">{{$data->manu_id}}</a></td>
                        <td>{{ date('d-m-Y', strtotime( $data->created)) }}<br>
                             {{ date('h:i', strtotime( $data->created)) }}
                        </td>
                       <!--  <td>{{$data->user_id}}</td> -->
                        <td>{{$data->quntion}}</td>
                        <td>{{$data->manu->quality ?? ''}}</td>
                        <th>{{$data->user->name ?? ''}}</th>
                        <td>{{$data->remarks}} </td>
                         
                        <td><button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal{{$data->project_id}}">Check Key Values</button>
                        

  
                        </td>
                       
                      </tr>
                      @endforeach
                    	
                    </tbody>
                     @if(count($onlySoftDeleted) != 0)
                    {{ $onlySoftDeleted->appends(request()->query())->links()}}
                    @endif
                </table>
            </div>
        </div>
</div>
@foreach($onlySoftDeleted as $data)
<div class="modal fade" id="myModal{{$data->project_id}}" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Updated Manufacturer Deatils</h4>
        </div>
        <div class="modal-body">
           <div class="col-sm-6">
                  <?php $old = unserialize($data->old); 
                          if($data->old != NULL){
                            $old = $old;
                          }else{
                            $old ="";
                          }    
                           
                          ?>
              <h4 style="font-weight:bold;color:white;background-color:rgb(136, 130, 121);">Old values</h4>
              @if($data->old != NULL)
            <table class="table">
                            <tr>
                              <td>Manufacturer Type</td>
                              <td>:</td>
                              <td>{{$old->manufacturer_type}}</td>

                            </tr>
                             <tr>
                              <td>Total Area</td>
                              <td>:</td>
                              <td>{{$old->total_area}}</td>

                            </tr>
                           
                          </table>
                          @endif
                        </div>
                        <div class="col-sm-6">
              <h4 style="font-weight:bold;color:white;background-color:rgb(136, 130, 121); ">Updated values</h4>
                              <?php $new = unserialize($data->allfileds); 
                                     
                          if($data->allfileds != NULL){
                            $new = $new;
                          }else{
                            $new ="";
                          }    
                            
                          ?>
                           @if($data->allfileds != NULL)
                           <table class="table">
                             <tr>
                              <td>Manufacturer Type</td>
                              <td>:</td>
                              <td>{{$new['type']}}</td>

                            </tr>
                             <tr>
                              <td>Total Area</td>
                              <td>:</td>
                              <td>{{$new['total_area']}}</td>

                            </tr>
                           
                          </table>
                          @endif
                        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
@endforeach
<script type="text/javascript">
  function getdeatis() {
  var from =  document.getElementById('from').value;
  var to =  document.getElementById('to').value;
  var t =  document.getElementById('type').value;
      
        var type = t.options[e.selectedIndex].value;     
     


          $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/manuupdatereport",
            async:false,
            data:{from : from,to:to,type:type},
            success: function(response)
            {
                    console.log(response);
                    
              
                
             }
       });

 

  }
</script>
@endsection