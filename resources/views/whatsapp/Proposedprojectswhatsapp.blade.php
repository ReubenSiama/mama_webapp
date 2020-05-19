@extends('layouts.app')
@section('content')  
  
  <span class="pull-right"> @include('flash-message')</span>
  <div class="row">
<div class="col-md-10 col-md-offset-1 ">
       
        <div class="panel panel-danger" style="border-color:#337ab7 ">
            <div class="panel-heading" style="background-color:#337ab7;color:white;font-weight:bold;font-size:15px;">
              <center>Proposed Projects And Manufacturers  WhatsApp Details </center>
                
            </div>
            <div style="overflow-x: scroll;" class="panel-body">
                <?php $pa=[];
                      $pw=[]; ?> 
               <div class="col-sm-6">
                <h2>Proposed Projects</h2>
                <table id="manufacturer" class="table table-responsive" border=1><br>
                    <thead style="background-color:#ff9900">
                        <th>SLNO</th>
                        <th>User Name</th>
                        <th>Assigned Proposed Projects</th>
                         
                          <th>Whatsapp No Updated Projects</th>
                         

                    </thead>
                    <?php $i=1 ?>

                    <tbody>
                     @foreach($data as $df)
                       <tr>
                        <td> {{$i++}}</td>
                          <td>{{$df['name']}}</td>
                          <td>{{$df['Assigned']}}
                                 <?php array_push($pa, $df['Assigned']); ?>
                          </td>
                          <td>{{$df['totalwhatsapp']}}
                                 <?php array_push($pw,$df['totalwhatsapp']); ?>
                                  

                          </td>

                       </tr>
                       @endforeach
                       <tr style="background-color:#77778b ">
                         <td></td>
                         <td></td>
                         <td>{{array_sum($pa)}}</td>
                         <td>{{array_sum($pw)}}</td>
                       </tr>
                    </tbody>
                   
                </table>
              </div>
               <div class="col-sm-6">
                <h2>Proposed Manufacturers (Blocks)</h2>
                 <?php $ma=[];
                      $mw=[]; ?> 
                <table id="manufacturer" class="table table-responsive" border=1><br>
                    <thead style="background-color:#ff9900">
                        <th>SLNO</th>
                        <th>User Name</th>
                        <th>Assigned Proposed Manufacturers</th>
                         
                          <th>Whatsapp No Updated Manufacturers</th>
                         

                    </thead>
                    <?php $i=1 ?>
                    <tbody>
                     @foreach($data1 as $df1)
                       <tr>
                        <td> {{$i++}}</td>
                          <td>{{$df1['name1']}}</td>
                          <td>{{$df1['Assigned1']}}
                              <?php array_push($ma, $df1['Assigned1']); ?>
                          </td>

                          <td>{{$df1['totalwhatsapp1']}}
                             <?php array_push($mw,$df1['totalwhatsapp1']); ?>
                          </td>

                       </tr>
                       @endforeach
                        <tr style="background-color:#77778b ">
                         <td></td>
                         <td></td>
                         <td>{{array_sum($ma)}}</td>
                         <td>{{array_sum($mw)}}</td>
                       </tr>
                    </tbody>
                   
                </table>
              </div>
            </div>
           
            <div class="col-sm-6">
                <h2>Proposed Closed Contractors</h2>
                 <?php $ma1=[];
                      $mw1=[]; ?> 
                 <table id="manufacturer" class="table table-responsive" border=1><br>
                    <thead style="background-color:#ff9900">
                        <th>SLNO</th>
                        <th>User Name</th>
                        <th>Assigned Proposed Closed Contractors</th>
                         
                          <th>Whatsapp Updated Closed Contractors</th>
                         

                    </thead>
                    <?php $i=1 ?>

                    <tbody>
                     @foreach($data2 as $df4)
                       <tr>
                       <?php $pa1=[];
                      $pw2=[]; ?>
                        <td> {{$i++}}</td>
                          <td>{{$df4['name00']}}</td>
                          <td>{{$df4['Assigned00']}}
                                 <?php array_push($pa1, $df4['Assigned00']); ?>
                          </td>
                          <td>{{$df4['totalwhatsapp00']}}
                                 <?php array_push($pw2,$df4['totalwhatsapp00']); ?>
                                  

                          </td>

                       </tr>
                       @endforeach
                       <!-- <tr style="background-color:#77778b ">
                         <td></td>
                         <td></td>
                        <td>{{array_sum($pa1)}}</td>
                         <td>{{array_sum($pw2)}}</td> 
                       </tr> -->
                    </tbody>
                   
                </table>
              </div>
            </div>
       
        </div>
</div>

@endsection