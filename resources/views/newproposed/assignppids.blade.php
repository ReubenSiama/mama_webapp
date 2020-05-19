@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
   <form method="GET" action="{{ URL::to('/') }}/assignppids">
                    <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-4">
                                <label>Search (Phone Number or Project Id)</label>
                                <input  type="text" class="form-control" name="sid">
                            </div>
                           
                            
                             <div class="col-md-2">
                              <label></label>
                              <button type="submit" class="form-control  btn btn-warning btn-sm">submit</button>  
                            </div>
          </div>    
          </form>
        
      <br><br>
      <br>
          <div>
          <form method="GET" action="{{ URL::to('/') }}/assignppids">
                    <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-6">
                                <label>Search with Wards  </label>

                                <?php $ward = App\Ward::All(); ?>
                               <select class="form-control" name="ward_id" >
                               <option value="" >Select Ward</option>
                               @foreach($ward as $wards)
                                 
                                <option value="{{$wards->id}}" >{{$wards->ward_name}}</option>
                               @endforeach
                              </select>
                            </div>
                           
                            
                             <div class="col-md-2">
                              <label></label>
                              <button type="submit" class="form-control  btn btn-info btn-sm">Submit</button>  
                            </div>
          </div>    <br>
      @if(count($data) > 0) <br>
      <h3 style="text-align:center";> <span style="background-color:#777777;color:white;font-weight:bold;"> <br> Total Customers {{$data->total()}} </span>  </h3>
      @endif
     

      <table class="table" border="1" >
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
                if($ward_id!=Null){
                  $dd = App\SubWard::where('ward_id',$ward_id)->pluck('id')->toarray();
                  $dd1 = App\Ward::where('id',$ward_id)->pluck('ward_name')->take(1)->first();
               $projects =App\ProjectDetails::where('p_p_c_id',$dump->p_p_c_id)->whereIn('sub_ward_id',$dd)->get();
                }
                   else{
                    $projects =App\ProjectDetails::where('p_p_c_id',$dump->p_p_c_id)->get();

                   }    
                        
                         

                         ?>
                         <p> </p>
             <td>{{$i++}}</td>      
              
             <td>{{$dump->p_p_c_id}}</td>
             <td>{{$dump->products_count}}</td>

             <td>
                <table class="table" border="1">
                   <thead>
                     <th>Project Id</th>
                     <th>Status</th>
                     <th>Project Size</th>
                     <th>Sub  Ward</th>
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
                      <?php  $subward=App\SubWard::where('id',$pro->sub_ward_id)->pluck('sub_ward_name')->first(); ?>
                      <td>{{$subward}}</td>
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
  <a  href="{{URL::to('/')}}/Holdingproposed?id={{$dump->p_p_c_id}}" class="btn btn-sm btn-primary">Temporarily Holding </a>
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
