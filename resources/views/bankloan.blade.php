
@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row">
        <form method="GET" action="{{ URL::to('/') }}/bankloan">
                    <div class="col-md-2">
                             <select required class="form-control" name="status">
                                   <option value="">---Select----</option>
                                   <option value="project">Projects</option>
                                   <option value="manufacturer">Manufacturer</option>

                               </select>
                            </div>
                           
                             <div class="col-md-2">
                              <button type="submit" class="form-control  btn btn-warning btn-sm">submit</button>  
                            </div>
          </div>    
          </form>
      </div>
           <br> 
           <div class="row">             
        <div class="col-md-12">
            <div class="panel panel-default" style="border-color:#0e877f;width:100%;">
                 
                   
                <div class="panel-heading" style="background-color:#0e877f;color:white;"><span style="font-weight:bold;">No of  Projects : {{ count($projects) }}   </span>   </div>
                <div class="panel-body">
                  <table id="dtDynamicVerticalScrollExample" class="table table-striped table-bordered table-sm" cellspacing="0"
                        width="100%">
                       <thead>
                        <th>No 
                          </th>
                         <th>Customer Name</th>
                         <th>project id/Manufacturer</th>
                        
                        <th> Number</th>
                        <th>Stage</th>
                        <th>Bank Loan</th>
                        <th>Upload Documents</th>
                         
                       </thead>
                      <tbody>
                        <?php $z=1;
                           
                        ?>
                        @foreach($projects as $project)
                       <tr>
                         <td>{{$z++}}</td>
                        <td>{{$project->procurementdetails != null ?$project->procurementdetails->procurement_name :''}}
                          {{$project->proc != null ?$project->proc->name :''}}</td>
                              <td>
                              
                               <a target="_none" href="{{URL::to('/')}}/showThisProject?id={{$project->project_id}}">{{$project->project_id}} </a>
                               <a href="{{ URL::to('/') }}/viewmanu?id={{ $project->id }}">{{$project->id}}</a>
                              </td>
                             <td>
                              {{$project->procurementdetails != null ?$project->procurementdetails->procurement_contact_no :''}}
                              {{$project->proc != null ?$project->proc->contact :''}}
                              </td> 
                            
                              <td>
                                {{$project->project_status}}
                              </td>
                              <td>{{$project->interested_in_loan }} </td>
                              <td><a href="#">Upload Documents</a></td>
                            </tr>
                         @endforeach

                      </tbody>


                   </table>
                       
                   
                </div>
            </div>
        </div>
      
    </div>
  </div>





@endsection
