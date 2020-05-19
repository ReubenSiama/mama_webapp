@extends('layouts.app')
@section('content')  
  
  <span class="pull-right"> @include('flash-message')</span>
  <div class="row">
<div class="col-md-8 col-md-offset-2 ">
       
        <div class="panel panel-danger" style="border-color:#337ab7 ">
            <div class="panel-heading" style="background-color:#337ab7;color:white;font-weight:bold;font-size:15px;">
              <center>Dediacted Customers WhatsApp Details </center>
                
            </div>
            <div style="overflow-x: scroll;" class="panel-body">

               <?php $as = [];
                     $wt =[];
                     ?>
                <table id="manufacturer" class="table table-responsive" border=1><br>
                    <thead style="background-color:#ff9900">
                        <th>SLNO</th>
                        <th>User Name</th>
                        <th>Assigned Customers</th>
                         
                          <th>Whatsapp Updated Customers</th>
                         

                    </thead>
                    <?php $i=1 ?>
                    <tbody>
                      @foreach($data as $df)
                       <tr>
                        <td> {{$i++}}</td>
                          <td>{{$df['name']}}</td>
                          
                          <td>{{$df['Assigned']}}
                            <?php array_push($as, $df['Assigned']); ?>

                          </td>
                         
                          <td>{{$df['totalwhatsapp']}}

                                <?php array_push($wt, $df['totalwhatsapp']); ?>
                          </td>

                       </tr>
                       @endforeach
                       <tr style="background-color:#77778b ">
                         <td></td>
                         <td></td>
                         <td>{{array_sum($as)}}</td>
                         <td>{{array_sum($wt)}}</td>
                      </tr>
                    </tbody>
                   
                </table>
            </div>
        </div>
</div>

@endsection