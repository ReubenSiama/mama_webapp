@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
  <span class="pull-right"> @include('flash-message')</span>
      </div>
      <br>
      @if(count($result) > 0)
      <h3 style="text-align:center";> <span style="background-color:#777777;color:white;font-weight:bold;"> Total Projects And Manufacturesr Customers {{count($result)}} </span>  </h3>
      @endif
     

      <table class="table" border="1">
         <thead style="background-color:#9fa8da">
         
        
           <th>Customer Number</th>
           <th>Customer Name</th>
          
           <th>Projects And Manufacturer</th>
           
          
         </thead>
         <tbody>
          @foreach($result as $dump)
           <tr>
             
           <td>{{$dump}}</td>
           <td><?php $pname = App\ProcurementDetails::where('procurement_contact_no',$dump)->pluck('procurement_name')->first(); 
                  $mname = App\Mprocurement_Details::where('contact',$dump)->pluck('name')->first(); ?>

                  {{$pname}}<br>
                  {{$mname}}
              </td>
              <td>
                <?php $pid = App\ProcurementDetails::where('procurement_contact_no',$dump)->pluck('project_id')->toarray(); 
                  $mid = App\Mprocurement_Details::where('contact',$dump)->pluck('manu_id')->toarray();
                        

                   ?>
                       <table class="table" border="1">
                             <thead>
                               <th>Project Id</th>
                               <th>Manufacturer Id</th>

                             </thead>
                             <tbody>
                              <td>
                               @foreach($pid as $p)
                                <a href="{{URL::to('/')}}/showThisProject?id={{$p}}">{{$p}}</a><br>
                                 @endforeach
                               </td>
                                 <td>
                                 @foreach($mid as $m)
                                <a href="{{URL::to('/')}}/viewmanu?id={{$m}}"> {{$m}}</a><br>
                                 @endforeach
                               </td>
                             </tbody>
                       </table>

               </td>
           </tr>
           @endforeach
         </tbody>
      </table>
    </form>
 
    </div>
   
@endsection