@extends('layouts.app')
@section('content')
<div class="container">
    <div class="col-md-9 col-md-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading text-center" ><b>Field Employees Login Details</b></div>
            <div class ="panel-body">

                       
                       <table class="table table-hover">
                           <thead>
                               <th>Name</th>
                               <th>Login Time</th>
                             
                               <th>Approval done by</th>
                               <th>Login Time</th>
                               <th>Action</th>
                           </thead>
                            <tbody>
                            @foreach($users as $yup)
                                   <tr>
                                    <td>{{ $yup->user !=null ? $yup->user->name:''}}</td>
           
                                    <td>{{ $yup->logintime}} {{$yup->approve}}</td>

                                    <td><?php 
                                      $vals = DB::table('notifications')->where('notifiable_id',$yup->id)->pluck('approvedby')->first(); 

                                    $username = App\User::where('id',$vals)->pluck('name')->first(); ?>
                                       {{$username}} </td>
                                     
                                     <td><?php 
                                      $vals = DB::table('notifications')->where('notifiable_id',$yup->id)->pluck('created_at')->first(); ?>

                                         {{ date('d-m-Y', strtotime(  $vals)) }}<br>
                                         {{ date('h:i:s A', strtotime($vals)) }}
                                        </td>
   
 


                                  <td>
                                     <?php $val = DB::table('notifications')->where('notifiable_id',$yup->id)->pluck('approve')->first(); ?>
                                    @if($val == 2)
                                        <p  style="width:90%;">
                                                Rejected
                                        </p>
                                        @elseif($val == 1)
                                             <p  style="width:90%;">
                                                Approved
                                             </p>
                                        @else
                                            
                                           <div class="btn-group">
                                            <form action="{{ URL::to('/') }}/approvepage" method="post">
                                                 {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{ $yup->id }}">
                                            <button type="submit" class="btn btn-success btn-sm" style="width:90%;">
                                                Approve
                                            </button>
                                            </form>
                                            <form action="{{ URL::to('/') }}/rejectpage" method="post">
                                                 {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{ $yup->id }}">
                                            <button type="submit" class="btn btn-danger btn-sm" style="width:90%;margin-top:-81%;margin-left:90%;">
                                                Reject
                                            </button>
                                        </form>
                                        </div>
                                        @endif
                                  </td>
                                </tr>
                            @endforeach
                            </tbody>
                       </table>
            </div>
        </div>
    </div>
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
