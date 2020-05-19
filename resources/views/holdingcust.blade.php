@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
  
      @if(count($data) > 0)
      <h3 style="text-align:center";> <span style="background-color:#777777;color:white;font-weight:bold;"> Total Holding Customers {{$data->total()}} </span>  </h3>
      @endif
     

      <table class="table" border="1">
         <thead style="background-color:#9fa8da">
          <!--  <th>Select All <br> -->
          <!-- <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th> -->
            <th>Slno</th>
           <th>Project Proposed Id</th>
           <th>NO Of Projects</th>
          
           <th>Project Ids</th>
           <th>Project Size(Sqrt)</th>
            <th>WhatsApp Number</th>
            <th>Action</th>

         </thead>
         <tbody>
          <?php $i=1; ?>
          @foreach($data as $dump)
           <tr>
           <!--   <td><input type="checkbox" name="number[]" class="regular-checkbox name"  id="checkbox-1-1" value="{{$dump->p_p_c_id}}" /><label for="checkbox-1-1"></label></td> -->
            <?php   
                $projects =App\ProjectDetails::where('p_p_c_id',$dump->p_p_c_id)->get();
                         
                         

                         ?>
             <td>{{$i++}}</td>            
             <td>{{$dump->p_p_c_id}}</td>
             <td>{{$dump->products_count}}</td>

             <td>
                <table class="table" border="1">
                   <thead>
                     <th>Project Id</th>
                     <th>Status</th>
                     <th>Project Size</th>
                      <th>Last Update</th>
                     <th>Remarks</th>
                     <th>Status</th>
                   </thead>
                   <tbody>
                    @foreach($projects as $pro)
                     <tr>
                      <td><a href="{{ URL::to('/') }}/admindailyslots?projectId={{$pro->project_id}}" target="_blank">{{ $pro->project_id }}</a></td>
                      <td>{{$pro->project_status}}</td>
                      <td>{{$pro->project_size}}</td>
                       <?php $da = App\UpdatedReport::where('project_id',$pro->project_id)->orderBy('id', 'DESC')->first(); ?>
                       @if(count($da) > 0)
                       <td>{{$da->updated_at}}</td>
                       <td>{{$da->remarks}}</td>
                       <td>{{$da->quntion}}</td>
                       @endif
                     </tr>
                     @endforeach
                   </tbody>
                </table>  
                    
             </td>
             <td>
              {{$dump->yes}}
             </td>
  <td><?php $nums = App\ProcurementDetails::where('p_p_c_id',$dump->p_p_c_id)->where('whatsapp','!=',NULL)->pluck('whatsapp')->first(); ?>
              {{$nums}}</td>
              <td>
                <div class="btn-group" role="group" aria-label="Basic example">
      Temporarily Holding
  <a  href="{{URL::to('/')}}/removeproposed?id={{$dump->p_p_c_id}}" class="btn btn-sm btn-warning">Remove Completely</a>
  
</div>
              </td>
           </tr>
           @endforeach
         </tbody>
      </table>





        @if(count($data) > 0)
        <center>{{ $data->appends(request()->query())->links()}} </center>
       @endif
    </div>
    

@endsection
