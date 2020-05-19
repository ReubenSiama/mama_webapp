<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)
@section('content')
<?php $trainingCount = "";
 ?>

  <span class="pull-right"> @include('flash-message')</span>

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;">
                    <b class="pull-left"><?php $name=App\User::where('id',isset($_GET['user']) ? $_GET['user']: '')->pluck('name')->first(); ?>User Name : {{$name}}</b>
               <center>
                  <b>Assigned Customers Details</b>
                @if(count($projects) != 0)
                
               Count : <b> {{ count($projects)}}</b>
                
                @endif

</center>

                    @if(session('ErrorFile'))
                        <div  class="alert-danger pull-right">{{ session('ErrorFile' )}}</div>
                    @endif 
                </div>
                <div class="panel-body">
                  <div class="pull-right" >
                    <table class="table">
                       <tr>
                        <b class="pull-right">
                          <td></td>
                          <td>Contractor = {{$Contractor}}</td>
                          <td>Procurement = {{$Procurement}}</td>
                          <td>Owner = {{$Owner}}</td>
                          <td></td>
                          <td>Blocks = {{$Blocks}}</td>
                          <td>RMC = {{$RMC}}</td>
                          <td>Builder = {{$Builder}}</td>
                          <td>BuilderDeveloper = {{$BuilderDeveloper}}</td>
                          <td>SiteEngineer = {{$SiteEngineer}}</td>
                          <td>Consultant = {{$Consultant}}</td>
                         

                          </b>
                      </tr>
                      
                    </table>
 
                  </div> 
          @if(Auth::user()->group_id == 2 || Auth::user()->group_id == 1)
          <form action="{{URL::to('/')}}/usercustomers" method="get" >
                    
                 {{ csrf_field() }}
                  <div class="col-md-4">
                     <?php $users = App\User::where('department_id','!=',10)->get() ?>
                 <select  name="user"  style="width:300px;" class="form-control">
                    <option value="">--Select--</option>
                    <option value="All">All</option>

                   @foreach($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>
                     @endforeach
                  </select>
                </div>
                 <div class="col-md-2">
                  <input type="submit" class="btn btn-sm btn-warning" value="Get Details">
                </div>
            </form>
                @endif
          <table class="table table-hover" border="1">
          <thead>
            <!--  <th>Select All <br>
          <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th> -->
           
            <th>SlNo</th>
            <th>Customer Status</th>
            <th>Customer Id</th>
            <th>Customer Name</th>
            <th>Type of Customer</th>
            <th>Total Business Amount</th>
            <th>Phone Number</th>
            <th>WhatsApp Number</th>
            <th>Subward Name</th>
            <th>Monthly Target</th>
            <th>Remarks</th>
            <th>Date from</th>
            <th>Date To</th>
            <th>Action</th>
          </thead>
          <?php $i=1; 
              $datas = [];

              
          ?>
          @foreach($projects as $project)

        <input type="hidden" name="user" value="{{ isset($_GET['user']) ? $_GET['user']: '' }}" id="id{{$project->customer_id}}">
            
          <tbody>
            
            <input type="hidden" name="cid" class="regular-checkbox name"  id="cid{{$project->customer_id}}" value="{{$project->customer_id}}" /><label for="checkbox-1-1"></label>
             <td>{{$i++}}</td>
             <td><span style="font-weight:bold;">{{$project->status}}</span><br>

                <p style="color:#2ab27b">{{$project->remarks}} </p>
                  
             </td>
            <td style="text-align:center"><a href="{{URL::to('/')}}/customerprojects?customer_id={{$project->customer_id }}" target="_blank">{{ $project->customer_id }}</a></td>
            <td>{{ $project->first_name }}</td>
             <td>{{$project->type->cust_type ?? ''}}</td>
            <td><?php $amount = App\CustomerInvoice::where('customer_id',$project->customer_id)->sum('mhInvoiceamount'); ?>
              {{number_format($amount,2)}}
            </td>
            
            <td>{{ $project->mobile_num }}</td>
             <td><?php 

            $nums =App\ProcurementDetails::where('procurement_contact_no',$project->mobile_num)->where('whatsapp',"!=",NULL)->pluck('whatsapp')->first(); 
              if(count($nums) == 0){
                  $nums =App\Mprocurement_Details::where('contact',$project->mobile_num)->where('whatsapp',"!=",NULL)->pluck('whatsapp')->first();

              }

             if(count($nums) > 0){

                 
                 array_push($datas, $nums);
               
             }


            ?> 
                 {{$nums}}
                   @if(count($nums) > 0)

                     <a href="{{URL::to('/')}}/cvcard?wno={{$nums}}&&name={{$project->first_name}}" class="btn btn-sm btn-warning" >Get Contact</a>
     

                 @endif

              
                
                
               </td>


            <td><?php $subward = App\SubWard::where('id',$project->sub_ward_id)->pluck('sub_ward_name')->first(); ?>
              
              {{$subward}}
            </td>
            
              <?php 
              $target = App\NewCustomerAssign::where('cid',$project->customer_id)->pluck('mothlytarget')->first(); 
              $remarks = App\NewCustomerAssign::where('cid',$project->customer_id)->pluck('remark')->first(); 
              $datefrom = App\NewCustomerAssign::where('cid',$project->customer_id)->pluck('datefrom')->first(); 
              $datato = App\NewCustomerAssign::where('cid',$project->customer_id)->pluck('dateto')->first(); 
              

              ?>
                
             <td>
           
                 <input type="text"  name="bus" class="form-control" value="{{$target}}" id="bus{{$project->customer_id}}">

           </td>
           <td>
               <textarea class="form-control" name="remark" id="remark{{$project->customer_id}}">{{$remarks}}</textarea>

           </td>
             <td>
              <input type="date" name="datefrom" class="form-control" value="{{$datefrom}}" id="datefrom{{$project->customer_id}}"></td>
              <td>
                <input type="date" name="dateto" class="form-control" value="{{$datato}}" id="dateto{{$project->customer_id}}">
            </td>
     

          <td>
            <button class="btn btn-sm btn-primary" onclick="get('{{$project->customer_id}}')" >Lock Target</button>

            <a href="{{URL::to('/')}}/deleteyes?id={{$project->customer_id}}&&user_id={{ isset($_GET['user']) ? $_GET['user']: '' }}" class="btn btn-danger btn-sm">delete</a>


          </td>
       
          </tbody>

      
          @endforeach
        </table>
    

             <h4 class="pull-right">

                 <b> Total Target : {{$sum}}</b>
               </h4>
               <h4 class="pull-left">
                
                 <b> WhatsApp Numbers : {{count($datas)}}</b>
               </h4>
        
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

<script type="text/javascript">
  function get(arg){
    
     var cid = document.getElementById('cid'+arg).value;
     var remark = document.getElementById('remark'+arg).value;
     var  bus = document.getElementById('bus'+arg).value;
     var datefrom = document.getElementById('datefrom'+arg).value;
     var dateto = document.getElementById('dateto'+arg).value;
      var user_id = document.getElementById('id'+arg).value;
     
        
        $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/updatemonthly",
            async:false,
             data: { cid:cid, remark:remark,datefrom:datefrom,dateto:dateto,user_id:user_id,bus:bus }, 
            success: function(response)
            {
                   

         alert(response);

              
             },
             error: function (error) {
                     
                      alert(error);
                    
                    }
       });   

   
   
  }
</script>
@endsection
