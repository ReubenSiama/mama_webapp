<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)
@section('content')
<?php $trainingCount = "" ?>

<div class="col-md-12">
  <span class="pull-right"> @include('flash-message')</span>
     
    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;"><b>Remaining  Customers to Assign 
                  @if(count($projects) != 0)
                
               Count : <b> {{$projects->total()}}</b>
                
                @endif 

              </b> &nbsp;&nbsp;&nbsp;
              <b>Total : {{$total}}</b>&nbsp;&nbsp;&nbsp;&nbsp; <b>Assigned : {{$assign}} </b>
                

                    @if(session('ErrorFile'))
                        <div  class="alert-danger pull-right">{{ session('ErrorFile' )}}</div>
                    @endif 
                </div>
                <div class="panel-body">
                    
          @if(Auth::user()->group_id == 2 || Auth::user()->group_id == 1)
          <div class="pull-right">
               <form action="{{URL::to('/')}}/newcustomerassign" method="get" id="test">
                     <div class="col-md-4">
                  
                <label>(From Date)</label>
                <input value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="date" class="form-control" name="from" id="from">
              </div>
              <div class="col-md-4">
                <label>(To Date)</label>
                <input  value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="date" class="form-control" name="to" id="to">
              </div>
                   <div class="col-md-1">
 <label>(Fetch)</label>
                  <a onclick="document.getElementById('test').submit()" class="btn btn-sm btn-warning">Fetch Details</a>
                </div>
                  
                   </form>

            </div>
          <form action="{{URL::to('/')}}/storenewcustomer" method="post" id="test1" >
                    
                 {{ csrf_field() }}
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
          <table class="table table-hover" border="1">
          <thead>
             @if(Auth::user()->group_id == 2 || Auth::user()->group_id == 1)
             <th>Select All <br>
          <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th>
            @endif
            <th>SlNo</th>
            <th>Customer Status</th>
            <th>Customer Id</th>
            <th>Customer Name</th>
            <th>Type Of customer</th>
            <th>Total Business Amount</th>
            <th>Phone Number</th>
            <th>Subward Name</th>
            <th>Last Updated</th>
            <th>Status</th>
            <th>remark</th>
            <!-- <th>WhatsApp Number</th> -->

 



          </thead>
          <?php $i=1; ?>
          @foreach($projects as $project)
          <tbody>
             @if(Auth::user()->group_id == 2 || Auth::user()->group_id == 1)
             <td><input type="checkbox" name="number[]" class="regular-checkbox name"  id="checkbox-1-1" value="{{$project->customer_id}}" /><label for="checkbox-1-1"></label></td>
             @endif
             <td>{{$i++}}</td>
             <td style="text-align:center">{{ $project->status }}</td>
            <td style="text-align:center"><a href="{{URL::to('/')}}/customerprojects?customer_id={{$project->customer_id }}" target="_blank">{{ $project->customer_id }}</a></td>
            <td>{{ $project->first_name }}</td>
             <td>{{$project->type->cust_type ?? ''}}</td>
            <td><?php $amount = App\CustomerInvoice::where('customer_id',$project->customer_id)->sum('mhInvoiceamount'); ?>
              
              {{number_format($amount,2)}}
            </td>
            <td>{{ $project->mobile_num }}</td>
            <td><?php $subward = App\SubWard::where('id',$project->sub_ward_id)->pluck('sub_ward_name')->first(); ?>
              
              {{$subward}}
            </td>
             
             <td><?php $up = App\ProcurementDetails::where('procurement_contact_no',$project->mobile_num)->pluck('project_id')->toarray(); 
                   $upmanu = App\Mprocurement_Details::where('contact',$project->mobile_num)->pluck('manu_id')->toarray();

          $last_update=App\UpdatedReport::whereIn('manu_id',$upmanu)->orWhereIn('project_id',$up)->orderby('created_at','DESC')->first();  

?>
@if(count($last_update) > 0)
{{$last_update->updated_at}}
@endif
</td>
         <td>@if(count($last_update) > 0) {{$last_update->quntion}} @endif </td>
         <td>@if(count($last_update) > 0) {{$last_update->remarks}} @endif</td> 
            

          </tbody>
          @endforeach
          
        </table>
     <center>{{ $projects->appends(request()->query())->links()}} </center>   
        
      </label>
        
                </div>
             
    </div>
   </div>


<script type="text/javascript">
  
    $(function () {
        // add multiple select / deselect functionality

        $("#selectall").click(function () {
           alert();
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
