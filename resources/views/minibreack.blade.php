@extends('layouts.app')
@section('content')
<div class="container">
  
    <div class="col-md-7">
        <div class="panel panel-primary">
            <div class="panel-heading text-center" ><b>Today BreakTime</b>
            <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color: black;"></i></button>
            </div>
            <div class ="panel-body">
              <form method="GET" action="{{ URL::to('/') }}/mhbreakhistory">
                {{ csrf_field() }}
                <div class="col-md-12">
                            <div class="col-md-4">
                                <label>From Date</label>
                                <input required value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from">
                            </div>
                            <div class="col-md-4">
                                <label>To   Date</label>
                                <input required value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="date" class="form-control" name="to">
                            </div>
                           
                        <div class="col-md-3">
                            <label></label>
                            <input type="submit" value="Fetch" class="form-control btn btn-primary">
                        </div>
                    </div> 
                </form>
                <br>
                       <table class="table table-hover">
                           <thead>
                               <th>Date</th>
                               <th>Name</th>
                               <th>Total Time Taken</th>
                               <th>Total Count of Breaks</th>
                           </thead>
                            <tbody>
                              @foreach($breacktime as $break)
                              <?php $time = array_sum($break['usertime']);

                              if($time > 90){
                                  $time = 0;

                              }elseif($time > 90){
                                $time=2;
                                  }
                              else{
                                $time=1;

                              }
                             
                              
                              ?>
                                    @if($time ==0)
                                   <tr style="{{ $time == 0 ? 'background-color: #ffbaba;' : '' }}">
                                           @else
                                     <tr style="{{ $time == 2 ? 'background-color:white;' : '' }}">
                                        @endif   
                                    <td>{{ date('d-m-Y',strtotime($break['date']))}}</td>
                                    <td>{{$break['name'] }}
                                    </td>
                                    <td>
                                       <?php $s = array_sum($break['usertime']);
                                        ?>
                                        {{$s}} Minutes
                                    </td>
                                    <td>
                                      <?php $count = count($break['usertime']);
                                      ?>
                                      {{$count}}
                                    </td>
                              @endforeach        
                            </tbody>
                       </table>
            </div>
        </div>
    </div>

                             @if(!isset($_GET['from']))
     <div class="col-md-3">
        <div class="panel panel-primary">
            <div class="panel-heading text-center" ><b>On BreakTime</b>
            </div>
            <div class ="panel-body">
             <table class="table table-hover">
                          <thead>
                              <th style="text-align:center;font-size:16px;font-weight:bold;">Name</th>
                           </thead>
                            <tbody>
                              @foreach($on as $online)
                              <tr>
                            <td style="text-align:center;">
                              {{$online->name}} &nbsp;<br>
                            </td>
                                </tr>
                              @endforeach
                            </tbody>
                       </table>
            </div>
        </div>
    </div>
                              @endif
</div>

@if(session('Success'))
<script>
    swal("success","{{ session('Success') }}","success");
</script>
@endif
@if(session('Success'))
<script>
    swal("error","{{ session('error') }}","error");
</script>
@endif
@endsection
