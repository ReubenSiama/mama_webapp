@extends('hrmanage')
@section('content')
<div class="container-fluid">
    <div class="row">
    <div class="box box-solid box-info">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading" style="background-color:#1db0f1">Send E-Mail Invitation</div>
                <div class="panel-body">
                   
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/api') }}/invitation">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-envelope"></i> Send Invitation Link
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <div class="col-md-6">
            <div class="panel panel-default" >
                <div class="panel-heading" style="background-color:#1db0f1">Select Date Wise</div>
                <div class="panel-body">
                   

                    <form class="form-horizontal" role="form" method="GET" action="{{ url('/') }}/sendinvite">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Select date From</label>

                            <div class="col-md-6">
                                <input id="email" type="date" class="form-control" name="fromdate" value="{{ old('email') }}">

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Select date To</label>

                            <div class="col-md-6">
                                <input id="email" type="date" class="form-control" name="todate" value="{{ old('email') }}">

                            </div>
                        </div><br>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-user"></i> Get Employees
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
 <div class="panel panel-default" >
     <div class="panel-heading" style="background-color:#1db0f1"><h3 style="color:white;font-weight:bold;text-align:center;">Register Users</h3></div><br>
 @foreach($data as $invite)
    <div style="overflow: hidden;" class="col-md-3 col-md-offset-1">
    <img class="card-img-top" src="{{ URL::to('/') }}/public/interview/{{ $invite->picture }}" alt="Card image" style="width:300px;height:300px;">
    <h4 class="card-title">{{$invite->name}}</h4>
      <h4 class="card-title">{{$invite->email}}</h4>
      <h4 class="card-title">{{$invite->number}}</h4>
      <h4 class="card-title">{{$invite->qualification}}</h4>
      <h4 class="card-title">{{$invite->address}}</h4>
       <a href="{{ URL::to('/') }}/public/interview/{{ $invite->resume }}" class="btn btn-warning btn-sm">See Resume</a>
       <a href="{{ URL::to('/') }}/public/interview/{{ $invite->resume }}" class="btn btn-info btn-sm">Send Call Letter</a>
       @if($invite->status == null)
        <form role="form" method="POST" action="{{ url('/api') }}/attend">
        <input type="hidden" name="id" value="{{$invite->id}}">
        <button type="submit"  class="btn btn-danger btn-sm">Pending</button>  
         </form>
        @else
        <a href="#" class="btn btn-success btn-sm">Attended</a>
        @endif
    
    @if($loop->iteration % 3==0)
        </div>
 
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div class="row">
        @endif
   </div>
@endforeach  
</div>



@endsection