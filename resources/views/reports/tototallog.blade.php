@extends('layouts.app')
@section('content')
<div class="col-md-8 col-md-offset-2">
    <div class="col-md-12">
        <div class="panel panel-default" style="border-color:#337ab7">
            <div class="panel-heading text-center" style="background-color:#337ab7">
                <b style="color:white">Today Logistic  Details
               </b>
            </div>
         

            <div class="panel-body">
             <form action="{{ URL::to('/') }}/tototallog" method="GET" enctype="multipart/form-data"> 
                    {{ csrf_field() }}
                    <div class="col-md-3">
                   <h4><b>Select Employees</b></h4>
                   <?php $users = App\User::all(); ?>
                    <select   class="form-control" name="user_id" required>
                      <option  value="">--Employees--</option>
                      <option value="All">All</option>
                      @foreach($users as $user)
                      <option {{ isset($_GET['user_id']) ? $_GET['user_id'] : '' }} value="{{$user->id}}">{{$user->name}}</option>
                      @endforeach
                    </select>
                 </div>
                <div class="col-md-4">
                 <h4><b>Select From Date</b></h4>
                    <input class="form-control" value="{{ isset($_GET['fromdate']) ? $_GET['fromdate'] : '' }}" type="date" name="fromdate"  style="width:100%;" required>
                </div>
                   

               <div class="col-md-3">
                   <h4><b>Select To Date</b></h4>
                   <input class="form-control" value="{{ isset($_GET['todate']) ? $_GET['todate'] : '' }}" type="date" name="todate" style="width:100%;" required>
                     
                   </textarea>
                 </div> 
  
                  <div class="col-md-2">
                   
                    <button type="submit"  class="form-control btn btn-primary" value="submi" style="margin-top:40px;">Fetch Report</button> 
                 </div> 

            </div>

            </form>
      <table  class="table" border="1">
                <thead>
                    <tr style="background-color:#707378"> 
                        <th>Order Id</th>  
                        <th>Category</th>                      
                        <th>Logistic Name </th>
                        <th>Project/Manufacturer Id</th>
                        <th>Invoice Id</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach($orders as $dump)
                	<tr>

                     <td>{{$dump->id}}</td>
                     <td>{{$dump->main_category}}</td>
                     <td> <?php $n = explode(",", $dump->logistic); 
                                $username = App\User::select('name')->whereIn('id',$n)->get();
                          ?>  
                            @foreach($username as $name)
                                 {{$name->name}}<br>
                            @endforeach     
                        </td>

                     <td>
                       @if($dump->project_id != null)
                          <b> Project:</b> <a href="{{URL::to('/')}}/showThisProject?id={{$dump->project_id}}">{{$dump->project_id}}</a>
                        @else
                          <b>Manufacturer</b> :<a href="{{ URL::to('/') }}/viewmanu?id={{ $dump->manu_id }}">
                              {{$dump->manu_id}} </a>
                        @endif       
                     </td>
                     <td><?php $in = App\MamahomePrice::where('order_id',$dump->id)->pluck('invoiceno')->first();
                        $ins = App\MultipleInvoice::where('order_id',$dump->id)->first(); 
                          



                     ?>

                       @if($in ==NULL)
                          @if(count($ins) > 0)
                          <a type="button" href="{{ route('downloadTaxInvoice1',['invoiceno'=>$ins->invoiceno,'id'=>$ins->id]) }}"> {{$ins->invoiceno}}</a>
                          @endif
                       @else
                      <a type="button" href="{{ route('downloadTaxInvoice',['invoiceno'=>$in,'manu_id'=>$dump->manu_id]) }}"> {{$in}}</a>
                         @endif
                        


                     </td>
                     <td >
                       {{$dump->req->ship ?? ''}}
                     </td>
                	</tr>
 
                   @endforeach
                 
                </tbody>
         
                   </table>

      

</div>
</div>

@endsection