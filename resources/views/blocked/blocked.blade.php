@extends('layouts.app')
@section('content')  
  
  <span class="pull-right"> @include('flash-message')</span>
	<div class="row">
<div class="col-md-8 col-md-offset-2 ">
       
        <div class="panel panel-danger" style="border-color:rgb(244, 129, 31) ">
            <div class="panel-heading" style="background-color: rgb(244, 129, 31);color:white;font-weight:bold;font-size:15px;">
              <center> Blocked Projects Deatils </center>
                
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
                <label>Type</label>
                 <select class="form-control" name="type" id="type">
                   <option {{ isset($_GET['type']) ? $_GET['type']: '' }} value="">---Select---</option>
                  <option value="All">All</option>
                   <option {{ isset($_GET['type']) ? $_GET['type']: '' }} value="Fake">Fake</option>
                   <option {{ isset($_GET['type']) ? $_GET['type']: '' }} value="Genuine">Genuine</option>
                   <option {{ isset($_GET['type']) ? $_GET['type']: '' }} value="Closed">Closed</option>

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
                        <th>Project Id</th>
                        <th>Blocked Date</th>
                         <!--  <th>Blocked By</th> -->
                          <th>Status</th>
                          <th>Quality</th>
                          <th>Remark</th>

                    </thead>
                    <?php $i=1 ?>
                    <tbody>
                      @foreach($onlySoftDeleted as $data)
                      <tr>
                        <td><a href="{{ URL::to('/') }}/admindailyslots?projectId={{$data->project_id}}" target="_blank">{{$data->project_id}}</a></td>
                        <td>{{ date('d-m-Y', strtotime( $data->deleted_at)) }}<br>
                             {{ date('h:i', strtotime( $data->deleted_at)) }}
                        </td>
                       <!--  <td>{{$data->user_id}}</td> -->
                        <td>{{$data->project_status}}</td>
                        <td>{{$data->quality}}</td>
                        <td>{{$data->blockremark}}</td>


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
<script type="text/javascript">
  function getdeatis() {
  var from =  document.getElementById('from').value;
  var to =  document.getElementById('to').value;
  var t =  document.getElementById('type').value;
      
        var type = t.options[e.selectedIndex].value;     
     


          $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/blocked",
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