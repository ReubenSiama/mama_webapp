  @extends('layouts.app')
  @section('content') 
  <div class="container">
  <div class="col-md-4">

      <div class="panel panel-default" style="border-color:#81c784;"> 
                  <div class="panel-heading text-center" style="background-color: #81c784;color:white;padding:15px;"><b> Active Project Customers   {{array_sum($counts)}} </b> <span><a class="btn btn-warning btn-sm pull-right" href="{{URL::to('/')}}/activecustomers?project=1">View More</a></span>
               </div>
              <table class="table" border="1">
                 <thead>
                   <th>Type of Customers</th>
                   <th>Total Cuatomers</th>
                 </thead>
                 <tbody>
                   @foreach($type as $df)
                    <tr>
                       <td>{{$df['typename']}}</td> 
                       <td>{{$df['count']}}</td>
                       
                   </tr>
                   @endforeach
                 </tbody>
              </table>


     </div>
     </div>
     <div class="col-md-4">

      <div class="panel panel-default" style="border-color:#81c784;"> 
                  <div class="panel-heading text-center" style="background-color: #81c784;color:white;padding:15px;"><b>Active Manufacturer Customers  {{array_sum($typecount)}} </b><span><a class="btn btn-warning btn-sm pull-right" href="{{URL::to('/')}}/activecustomers?project=2">View More</a></span>
               </div>
              <table class="table" border="1">
                 <thead>
                   <th>Type of Customers</th>
                   <th>Total Cuatomers</th>
                 </thead>
                 <tbody>
                   @foreach($typesa as $df)
                    <tr>
                       <td>{{$df['typename']}}</td> 
                       <td>{{$df['count']}}</td>
                       
                   </tr>
                   @endforeach
                 </tbody>
              </table>


     </div>
     </div>

    <div class="col-md-4">

      <div class="panel panel-default" style="border-color:#81c784;"> 
                  <div class="panel-heading text-center" style="background-color: #81c784;color:white;padding:15px;"><b> InActive Project Customers  &nbsp;&nbsp;&nbsp; {{array_sum($inactivetypecount)}} </b> <span><a class="btn btn-warning btn-sm pull-right" href="{{URL::to('/')}}/activecustomers?project=3">View More</a></span>
               </div>
              <table class="table" border="1">
                 <thead>
                   <th>Type of Customers</th>
                   <th>Total Cuatomers</th>
                 </thead>
                 <tbody>
                   @foreach($inactivetype as $df)
                    <tr>
                       <td>{{$df['typename']}}</td> 
                       <td>{{$df['count']}}</td>
                       
                   </tr>
                   @endforeach
                 </tbody>
              </table>


     </div>
  </div>


     @if(count($active) > 0)
     <div class="col-md-12">

      <div class="panel panel-default" style="border-color:#81c784;"> 
                  <div class="panel-heading text-center" style="background-color: #81c784;color:white;padding:15px;"><b>Active Project Customers {{count($active)}} </b>
               </div>
              <table class="table" border="1">
                 <thead>
                  <th>Slno</th>
                   <th>Name</th>
                   <th>Mobile Number</th>
                   <th>customer Id</th>
                   <th>Project Id</th>
                   <th>Project Size</th>
                 </thead>
                 <tbody>
            <?php $i=1; ?>
                  @foreach($active as $project)
                    <tr>
                      <td>{{$i++}}</td>
                       <td>{{$project['name']}}</td>   
                       <td> {{$project['number']}} </td>  
                       <td>{{$project['cid']}}</td>       
                            
                         <td>
                          <table class="table">
                            <thead>
                              <th>Project Id</th>
                              <th>Project Size</th>
                              <th>Project Quality</th>
                              <th>Project Status</th>
                              
                            </thead>
                            <tbody>
                              <?php $sum=[]; ?>
                              @foreach($project['project'] as $pro)
                              <tr>
                                <td>{{$pro->project_id}}</td>
                                 <td>{{$pro->project_size}}
                                       <?php array_push($sum,$pro->project_size); ?>
                                </td>
                                <td>{{$pro->quality}}</td>
                                <td>{{$pro->project_status}}</td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>

                       </td>     
                       <td>{{array_sum($sum)}}</td> 
                   </tr>
                   @endforeach
                 </tbody>
              </table>


     </div>
     </div>
  @endif
  @if(count($a) > 0)
     <div class="col-md-12">

      <div class="panel panel-default" style="border-color:#81c784;"> 
                  <div class="panel-heading text-center" style="background-color: #81c784;color:white;padding:15px;"><b>Active Manufacturer Customers  {{count($a)}} </b>
               </div>
              <table class="table" border="1">
                 <thead>
                  <th>Slno</th>
                   <th>Name</th>
                   <th>Mobile Number</th>
                   <th>customer Id</th>
                   <th>Manufacturer Details</th>

                 
                 </thead>
                 <tbody>
          <?php $i=1; ?>
                  @foreach($a as $project)
                    <tr>
                      <td>{{$i++}}</td>
                       <td>{{$project['name']}}</td>   
                       <td> {{$project['number']}} </td>  
                       <td>{{$project['cid']}}</td>       
                            
                         <td>
                          <table class="table">
                            <thead>
                              <th>Manufacturer Id</th>
                              <th>Manufacturer Type</th>

                            </thead>
                            <tbody>
                              
                              @foreach($project['project'] as $pro)
                              <tr>
                                <td>{{$pro->id}}</td>
                                 <td>{{$pro->manufacturer_type}}
                                       
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>

                       </td>     
                     
                   </tr>
                   @endforeach
                 </tbody>
              </table>


     </div>
     </div>
  @endif
   @if(count($m) > 0)
     <div class="col-md-12">

      <div class="panel panel-default" style="border-color:#81c784;"> 
                  <div class="panel-heading text-center" style="background-color: #81c784;color:white;padding:15px;"><b>Active Project Customers {{count($m)}} </b>
               </div>
              <table class="table" border="1">
                 <thead>
                  <th>Slno</th>
                   <th>Name</th>
                   <th>Mobile Number</th>
                   <th>customer Id</th>
                   <th>Project Id</th>
                   <th>Project Size</th>
                 </thead>
                 <tbody>
            <?php $i=1; ?>
                  @foreach($m as $project)
                    <tr>
                      <td>{{$i++}}</td>
                       <td>{{$project['name']}}</td>   
                       <td> {{$project['number']}} </td>  
                       <td>{{$project['cid']}}</td>       
                            
                         <td>
                          <table class="table">
                            <thead>
                              <th>Project Id</th>
                              <th>Project Size</th>
                              <th>Project Quality</th>
                              <th>Project Status</th>
                              <th>Deleted</th>
                              
                            </thead>
                            <tbody>
                              <?php $sum=[]; ?>
                              @foreach($project['project'] as $pro)
                              <tr>
                                <td>{{$pro->project_id}}</td>
                                 <td>{{$pro->project_size}}
                                       <?php array_push($sum,$pro->project_size); ?>
                                </td>
                                <td>{{$pro->quality}}</td>
                                <td>{{$pro->project_status}}</td>
                                <td>{{$pro->deleted_at}}</td>

                              </tr>
                              @endforeach
                            </tbody>
                          </table>

                       </td>     
                       <td>{{array_sum($sum)}}</td> 
                   </tr>
                   @endforeach
                 </tbody>
              </table>


     </div>
     </div>
  @endif





       @endsection
