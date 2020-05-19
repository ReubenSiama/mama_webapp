<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)
@section('content')
<?php $trainingCount = "" ?>
<div class="container">
<div class="col-md-12">
  <span class="pull-right"> @include('flash-message')</span>

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;"><b>Assign Customers Details</b>
                <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color: black;"></i></button>
                @if(count($projects) != 0)
                
               Count : <b> {{ count($projects)}}</b>
                
                @endif

                    @if(session('ErrorFile'))
                        <div  class="alert-danger pull-right">{{ session('ErrorFile' )}}</div>
                    @endif 
                </div>
                <div class="panel-body">
                    
         
          <table class="table table-hover" border="1">
          <thead>
             
            <th>SlNo</th>
            <th>Customer Id</th>
            <th>Customer Name</th>
            <th>Type of Customer</th>
            <th>Total Business Amount</th>
            <th>Assigned Employee Name</th>
            <th>Phone Number</th>
            <th>Subward Name</th>

          </thead>
          <?php $i=1; ?>
          @foreach($projects as $project)
          <tbody>
            
             <td>{{$i++}}</td>
            <td style="text-align:center"><a href="{{URL::to('/')}}/customerprojects?customer_id={{$project->customer_id }}" target="_blank">{{ $project->customer_id }}</a></td>
            <td>{{ $project->first_name }}</td>
            <td>{{$project->type->cust_type ?? ''}}
            <td><?php $amount = App\CustomerInvoice::where('customer_id',$project->customer_id)->sum('mhInvoiceamount'); ?>
              {{number_format($amount,2)}}
            </td>
            <td></td>
            <td>{{ $project->mobile_num }}</td>
            <td><?php $subward = App\SubWard::where('id',$project->sub_ward_id)->pluck('sub_ward_name')->first(); ?>
              
              {{$subward}}
            </td>
           
           
            
          </tbody>
          @endforeach
          
        </table>
      </label>
        
                </div>
             
               
    </div>
   </div>
</div>

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
