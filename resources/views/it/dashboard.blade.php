@extends('layouts.app')
@section('content')
<div style="background-color:white" class="container" >
<h2 ><center>WELCOME TO MAMA MICRO TECHNOLOGY
<BR><BR>
    <SMALL>You must know your responsibilities and carry out your tasks responsibly.<br>
    We appreciate you services.
    </SMALL>
</center></h2></div>

@foreach($date_today as $date)
<center>         
<h4>Today's Attendance</h4>
        <p><b>Login</b> :{{$date->logintime}} </p>
        <p><b>Logout </b>:{{$date->logout}} </p>
        </center>
        @endforeach 

@if(session('Success'))
<script>
    swal("success","{{ session('Success') }}","success");
</script>
@endif
@if(session('error'))
<script>
    swal("success","{{ session('error') }}","success");
</script>
@endif


@if(session('earlylogout'))
  <div class="modal fade" id="emplate" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #f27d7d;color:white;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Early logout</h4>
        </div>
        <div class="modal-body">
          <p style="text-align:center;">{!! session('earlylogout') !!}</p>  
        </div>
        <div class="modal-footer">
          <button type="button" style="background-color: #c9ced6;" class="btn btn-default" data-dismiss="modal" onClick="window.location.reload()">Close</button>
        </div>
      </div>
    </div>
  </div>
  
<script type="text/javascript">
  $(document).ready(function(){
      $("#emplate").modal('show');
  });
</script>
@endif
@endsection
