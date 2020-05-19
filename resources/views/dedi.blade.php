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
                <div class="panel-heading text-center" style="background-color: green;color:white;">

              </b> &nbsp;&nbsp;&nbsp;
              <b>Total : {{count($projects)}}</b>&nbsp;&nbsp;&nbsp;&nbsp; 
                

                    @if(session('ErrorFile'))
                        <div  class="alert-danger pull-right">{{ session('ErrorFile' )}}</div>
                    @endif 
                </div>
                <div class="panel-body">
                    
          
          <table class="table table-hover" border="1">
          <thead>
           
            <th>SlNo</th>
            <th>Customer Status</th>
            <th>Customer Id</th>
            <th>Customer Name</th>
            <th>Type Of customer</th>
            <th>Total Business Amount</th>
            <th>Phone Number</th>
            <th>Subward Name</th>
            <th>last Updated</th>
            <th>Status</th>
            <th>WhatsApp Number</th>

          </thead>
          <?php $i=1; ?>
          @foreach($projects as $project)
          <tbody>
            
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
          
          
            
             <td>
              
             <?php  $last_update = App\UpdatedReport::where('cid',$project->customer_id)->orderby('created_at','DESC')->first(); ?>
                 @if(count($last_update) > 0)
                   {{$last_update->updated_at}}
                   @endif
             </td>
             <td>@if(count($last_update) > 0)
                   {{$last_update->quntion}}
                   @endif </td>
             <td> <?php $ups = App\ProcurementDetails::where('procurement_contact_no',$project->mobile_num)->where('whatsapp',"!=",NULL)->pluck('whatsapp')->first();

                           if(count($ups) == 0){

                             $up = App\ContractorDetails::where('contractor_contact_no',$project->mobile_num)->where('whatsapp',"!=",NULL)->pluck('whatsapp')->first();
                             $ups = $up;
                           }else if(count($up) == 0 ){

                              $up1 = App\OwnerDetails::where('owner_contact_no',$project->mobile_num)->where('whatsapp',"!=",NULL)->pluck('whatsapp')->first();
                               $ups = $up1;
                           }else{
                               $ups = App\ProcurementDetails::where('procurement_contact_no',$project->mobile_num)->where('whatsapp',"!=",NULL)->pluck('whatsapp')->first();
                           } 

                    

                    $upmanus = App\Mprocurement_Details::where('contact',$project->mobile_num)->where('whatsapp',"!=",NULL)->pluck('whatsapp')->first();
                       if(count($upmanus) == 0){
                            $upman = App\Mowner_Deatils::where('contact',$project->mobile_num)->where('whatsapp',"!=",NULL)->pluck('whatsapp')->first(); 
                            $upmanus = $upman;
                       }else{
                          $upmanus = App\Mprocurement_Details::where('contact',$project->mobile_num)->where('whatsapp',"!=",NULL)->pluck('whatsapp')->first();

                       }
                    
                     
                    
               ?>
               {{$ups}} {{ $upmanus}}
             </td>
          </tbody>
          @endforeach
          
        </table>
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
