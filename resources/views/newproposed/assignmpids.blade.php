@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
      <form method="GET" action="{{ URL::to('/') }}/assignmpids">
                    <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-4">
                                <label>Search (Phone Number or Project Id)</label>
                                <input  type="text" class="form-control" name="sid">
                            </div>
                           
                            
                             <div class="col-md-2">
                              <label>Get</label>
                              <button type="submit" class="form-control  btn btn-warning btn-sm">submit</button>  
                            </div>
          </div>    
          </form><br><br>
      @if(count($data) > 0)
      <h3 style="text-align:center";> <span style="background-color:#777777;color:white;font-weight:bold;"> Total : {{$data->total()}} </span>  </h3>
      @endif
     

      <table class="table" border="1">
         <thead style="background-color:#9fa8da">
          <!--  <th>Select All <br> -->
          <!-- <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th> -->
            <th>Slno</th>
           <th>Manufacturer Proposed Id</th>
           <th>NO Of Manufactures</th>
           <th>Manufacturer Ids</th>
            <th>WhatsApp Number</th>
           
         </thead>
         <tbody>
          <?php $i=1; ?>
          @foreach($data as $dump)
           <tr>
           <!--   <td><input type="checkbox" name="number[]" class="regular-checkbox name"  id="checkbox-1-1" value="{{$dump->p_p_c_id}}" /><label for="checkbox-1-1"></label></td> -->
            <?php   
                $projects =App\Manufacturer::where('p_p_c_id',$dump->p_p_c_id)->get();
                         
                         

                         ?>
             <td>{{$i++}}</td>            
             <td>{{$dump->p_p_c_id}}</td>
             <td>{{$dump->products_count}}</td>
             <td>
                <table class="table" border="1">
                   <thead>
                     <th>Manufacturer Id</th>
                     <th>Type</th>
                     <th>Last Updated</th>
                     <th>Remarks</th>
                   
                   </thead>
                   <tbody>
                    @foreach($projects as $pro)
                     <tr>
                      <td><a href="{{ URL::to('/') }}/viewmanu?id={{$pro->id}}" target="_blank">{{ $pro->id }}</a></td>
                      <td>{{$pro->manufacturer_type}}</td>
                      <?php $da = App\UpdatedReport::where('manu_id',$pro->id)->orderBy('id', 'DESC')->first(); ?>
                       @if(count($da) > 0)
                       <td>{{$da->updated_at}}</td>
                       <td>{{$da->remarks}}</td>
                       @endif
                     </tr>
                     @endforeach
                   </tbody>
                </table>  
                    
             </td>
             <td><?php $nums = App\Mprocurement_Details::where('p_p_c_id',$dump->p_p_c_id)->where('whatsapp','!=',NULL)->pluck('whatsapp')->first(); ?>
              {{$nums}}
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