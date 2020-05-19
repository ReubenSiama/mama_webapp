@extends('layouts.app')
<style type="text/css">
  /* === card component ====== 
 * Variation of the panel component
 * version 2018.10.30
 * https://codepen.io/jstneg/pen/EVKYZj
 */
.card{ background-color: #fff; border: 1px solid transparent; border-radius: 6px; }
.card > .card-link{ color: #333; }
.card > .card-link:hover{  text-decoration: none; }
.card > .card-link .card-img img{ border-radius: 6px 6px 0 0; }
.card .card-img{ position: relative; padding: 0; display: table; }
.card .card-img .card-caption{
  position: absolute;
  right: 0;
  bottom: 16px;
  left: 0;
}
.card .card-body{ display: table; width: 100%; padding: 12px; }
.card .card-header{ border-radius: 6px 6px 0 0; padding: 8px; }
.card .card-footer{ border-radius: 0 0 6px 6px; padding: 8px; }
.card .card-left{ position: relative; float: left; padding: 0 0 8px 0; }
.card .card-right{ position: relative; float: left; padding: 8px 0 0 0; }
.card .card-body h1:first-child,
.card .card-body h2:first-child,
.card .card-body h3:first-child, 
.card .card-body h4:first-child,
.card .card-body .h1,
.card .card-body .h2,
.card .card-body .h3, 
.card .card-body .h4{ margin-top: 0; }
.card .card-body .heading{ display: block;  }
.card .card-body .heading:last-child{ margin-bottom: 0; }

.card .card-body .lead{ text-align: center; }

@media( min-width: 768px ){
  .card .card-left{ float: left; padding: 0 8px 0 0; }
  .card .card-right{ float: left; padding: 0 0 0 8px; }
    
  .card .card-4-8 .card-left{ width: 33.33333333%; }
  .card .card-4-8 .card-right{ width: 66.66666667%; }

  .card .card-5-7 .card-left{ width: 41.66666667%; }
  .card .card-5-7 .card-right{ width: 58.33333333%; }
  
  .card .card-6-6 .card-left{ width: 50%; }
  .card .card-6-6 .card-right{ width: 50%; }
  
  .card .card-7-5 .card-left{ width: 58.33333333%; }
  .card .card-7-5 .card-right{ width: 41.66666667%; }
  
  .card .card-8-4 .card-left{ width: 66.66666667%; }
  .card .card-8-4 .card-right{ width: 33.33333333%; }
}

/* -- default theme ------ */
.card-default{ 
  border-color: #ddd;
  background-color: #fff;
  margin-bottom: 24px;
}
.card-default > .card-header,
.card-default > .card-footer{ color: #333; background-color: #ddd; }
.card-default > .card-header{ border-bottom: 1px solid #ddd; padding: 8px; }
.card-default > .card-footer{ border-top: 1px solid #ddd; padding: 8px; }
.card-default > .card-body{  }
.card-default > .card-img:first-child img{ border-radius: 6px 6px 0 0; }
.card-default > .card-left{ padding-right: 4px; }
.card-default > .card-right{ padding-left: 4px; }
.card-default p:last-child{ margin-bottom: 0; }
.card-default .card-caption { color: #fff; text-align: center; text-transform: uppercase; }


/* -- price theme ------ */
.card-price{ border-color: #999; background-color: #ededed; margin-bottom: 24px; }
.card-price > .card-heading,
.card-price > .card-footer{ color: #333; background-color: #fdfdfd; }
.card-price > .card-heading{ border-bottom: 1px solid #ddd; padding: 8px; }
.card-price > .card-footer{ border-top: 1px solid #ddd; padding: 8px; }
.card-price > .card-img:first-child img{ border-radius: 6px 6px 0 0; }
.card-price > .card-left{ padding-right: 4px; }
.card-price > .card-right{ padding-left: 4px; }
.card-price .card-caption { color: #fff; text-align: center; text-transform: uppercase; }
.card-price p:last-child{ margin-bottom: 0; }

.card-price .price{ 
  text-align: center; 
  color: #337ab7; 
  font-size: 3em; 
  text-transform: uppercase;
  line-height: 0.7em; 
  margin: 24px 0 16px;
}
.card-price .price small{ font-size: 0.4em; color: #66a5da; }
.card-price .details{ list-style: none; margin-bottom: 24px; padding: 0 18px; }
.card-price .details li{ text-align: center; margin-bottom: 8px; }
.card-price .buy-now{ text-transform: uppercase; }
.card-price table .price{ font-size: 1.2em; font-weight: 700; text-align: left; }
.card-price table .note{ color: #666; font-size: 0.8em; }
</style>
@section('content')

@if(Auth::user()->group_id == 2 )
<div class="container">
  <span class="pull-right"> @include('flash-message')</span>

    <div class="panel-body">
                    <div class="col-md-12">
             <form method="GET" action="{{ URL::to('/') }}/petrol">
                <div class="col-md-2">
                <label>Projects(From Date)</label>
                <input value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from">
              </div>
              <div class="col-md-2">
                <label>Projects(To Date)</label>
                <input  value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="date" class="form-control" name="to">
              </div>
              <div class="col-md-2">
                <label>Select User</label>
                <?php $grp=[2,6,7,11]; $users = App\User::whereIn('group_id',$grp)->where('department_id','!=',10)->get(); ?>
                  <select class="form-control" name="userid">
                    <option value="">---Select user---</option>
                     @foreach($users as $user)
                     <option value="{{$user->id}}">{{$user->name}}</option>
                      @endforeach
                  </select>
              </div>
               <div class="col-md-2">
                <label>Get Details</label>
                <input type="submit" value="Fetch" class="form-control btn btn-primary">
              </div>
          </form>
          </div>
        </div>
      </div>


  <center> <span style="font-weight:bold;font-size:20px;font-style:italic;background-color:#9f905d;"></span><br>
   <h2> <b> Total Amount of Petrol Expense :  {{$totalamout}}</b> </h2><br>
    <h2><b>Total Amount of OtherExpenses {{$other}}</b></h2><br>
   </center><br>
  <div class="row">
   
    @foreach($data as $df)
    <div class="col-sm-3">
  <div class="card card-default">
  <div class="card-header">{{$df->user->name ?? ''}} <span class="pull-right">{{ date('d-m-Y', strtotime($df->created_at)) }}</span></div>
  <div class="card-body card-5-7">
    
    <div class="card-right">
      <h4>Kilometers : {{$df->total_kilometers}}</h4>
      <h4>Amount : {{$df->amount}}</h4>
      <h4>OtherExpenses : {{$df->otherexpense}}</h4>
    </div>
  </div>
  <div class="card-footer">
    <center><a href="{{URL::to('/')}}/petrolapprove?id={{$df->id}}" class="btn btn-sm btn-warning">Approve</a></center>
  </div>
</div>

    </div>

    @endforeach
    
  </div>
  
  
</div>
@endif
@if(Auth::user()->group_id == 1)
<div class="container">
  <span class="pull-right"> @include('flash-message')</span>

    <div class="panel-body">
                    <div class="col-md-12">
             <form method="GET" action="{{ URL::to('/') }}/petrol">
                <div class="col-md-2">
                <label>Projects(From Date)</label>
                <input value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from">
              </div>
              <div class="col-md-2">
                <label>Projects(To Date)</label>
                <input  value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="date" class="form-control" name="to">
              </div>
              <div class="col-md-2">
                <label>Select User</label>
                <?php $grp=[2,6,7,11]; $users = App\User::whereIn('group_id',$grp)->where('department_id','!=',10)->get(); ?>
                  <select class="form-control" name="userid">
                    <option value="">---Select user---</option>
                     @foreach($users as $user)
                     <option value="{{$user->id}}">{{$user->name}}</option>
                      @endforeach
                  </select>
              </div>
               <div class="col-md-2">
                <label>Get Details</label>
                <input type="submit" value="Fetch" class="form-control btn btn-primary">
              </div>
          </form>
          </div>
        </div>
      </div>
<div class="container">
  <span class="pull-right"> @include('flash-message')</span>

  <center> <span style="font-weight:bold;font-size:20px;font-style:italic;background-color:#9f905d;">Petrol Details<br>
  
   @if($totalamout != "")
     Total Amount {{$totalamout}}<br>
     OtherExpenses {{$other}}<br>
    @endif 
         
  </span></center><br>
  <div class="row">
   
    @foreach($admindata as $df)
    <div class="col-sm-4">
  <div class="card card-default">
  <div class="card-header">{{$df->user->name ?? ''}} <span class="pull-right">{{ date('d-m-Y', strtotime($df->created_at)) }}</span></div>
  <div class="card-body card-5-7">
    <div class="card-left">
      <h6>Paytm QR_Code </h6>
       <img src="https://www.qrcode-monkey.com/img/blog/qrcode-classic-blue.png" class="img-responsive">
    </div>
    <div class="card-right">
      <h4>Kilometers : {{$df->total_kilometers}}</h4>
      <h4>Amount : {{$df->amount}}</h4>
      <h4>OtherExpenses : {{$df->otherexpense}} </h4>
    </div>
  </div>
  <div class="card-footer">
    <center><a href="{{URL::to('/')}}/petrolpaymentdone?id={{$df->id}}" class="btn btn-sm btn-primary">Pay</a></center>
  </div>
</div>

    </div>

    @endforeach
    
  </div>
  
  
</div>



@endif

@endsection
