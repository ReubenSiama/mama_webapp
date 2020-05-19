<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 7? "layouts.sales":"layouts.app");
?>
@extends($ext)
@section('content')
	
           
<div class="col-md-12">
        <div class="col-md-12">
          <div class="panel panel-default" style="overflow: scroll;">
            <div class="panel-heading" style="background-color:#158942;color:white;font-size:1.4em;font-weight:bold">Last 10 Days ... ! SMS SENT REPORT</div>  
            <div class="panel-body">
            
              <table class="order-table table table table-hover" id="myTable">
                <thead>
                    <th>Name</th>
                  <th>Sim Number</th>
             
                </thead>
              @foreach($data as $project)
              <tr>
                    <td>
                         {{$project->user->name ?? ''}}
                      
                    </td>
                    <td>
                         {{$project->sim_number}}
                    </td>
                                      
                   
                  </tr>
                                
                  @endforeach
                </tbody>
              </table>
                </table>
                </div>
                </div>
                </div>
                </div>





 @endsection