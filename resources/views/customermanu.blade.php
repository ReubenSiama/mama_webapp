@extends('layouts.app')
@section('content')   
    <div class="col-md-12">     
    <div class="col-md-12" >
 
    <div class="panel panel-default" style="overflow: scroll;">
            <div class="panel-heading" style="background-color:#158942;color:white;font-size:1.4em;">Manufacture Projects List  <p class="pull-right">Count&nbsp;:&nbsp; {{ $projectcount }} </p></div>  
         <div class="panel-body" id="page">
       <table class="table table-hover table-striped">
                <thead>
                  <th>Manufacture Id</th>
                  <th>Manufacture Name</th>
                  <th style="width:15%">Address</th>
                  <th>Contact No.</th>
                 <th>Action</th>
                   <th>Last updated </th> 
                   <th>Projects History</th>
                  
              
                
                
               </thead>
                <tbody>
              

       

            @foreach($projects as $project)
           <tr>
                   <td  style="text-align:center"><a href="{{ URL::to('/') }}/viewmanu?id={{ $project->id }}" target="_blank">{{$project->id}}</a></td>
                   <td>{{ $project->proc != null ? $project->proc->name :$project->name }}</td>
                    <td>
                                     <a target="_blank" href="https://maps.google.co.in?q={{ $project->address != null ? $project->address : '' }}">{{$project->address}}</a>
                                    </td>
                   <td>{{ $project->proc != null ? $project->proc->contact :$project->contact_no }}</td>
                    <td>
                                   
                                      <a class="btn btn-sm btn-success" name="addenquiry" href="{{ URL::to('/') }}/manuenquiry?projectId={{ $project->id }}" style="color:white;font-weight:bold;padding: 6px;">Add Enquiry</a>
                                      
                                     

</td>
<td>
{{$project->updated_at}}<br>
{{$project->upuser->name ?? '' }}

  </td>
  <td>
   <a href="{{ URL::to('/') }}/contactnumer?number={{ $project->procurementdetails->procurement_contact_no ?? '' }}" class="btn btn-sm btn-warning">
     Check Other projects in Same Number
   </a>

  </td>
      

</tr>
@endforeach
</tbody>
</table>

<center>{{$projects->links()}}</center>
</div>
</div>
</div>
</div>

  
  @endsection
