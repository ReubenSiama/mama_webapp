<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)
@section('content')
<?php $trainingCount = "" ?>
<div class="container">
<div class="col-md-10">
  <span class="pull-right"> @include('flash-message')</span>

    <div class="panel panel-left" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;"><b>Manufacturers Customers List </b>
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
            <th>Phone Number</th>
            

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
            <td>{{ $project->mobile_num }}</td>
            
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
