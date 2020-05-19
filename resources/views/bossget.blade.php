  <div class="panel panel-default" style="border-color:#0e877f">
        <div class="panel-heading" style="background-color:#0e877f;color:white;"> </div>
                <div class="panel-body" style="height:500px;max-height:500px;overflow-x:hidden; overflow-y:scroll;">       
          <div class="col-md-12">
           
        <table class="table" border="1">
        <thead>
          <tr>
                  <th>Project Name</th>
                  <th>Project Id</th>
                  <th>Sub Ward</th>
                  <th>Address</th>
                 <th>Procurement Name</th>
                  <th>Contact No.</th>
                  <th>Project Size</th>
                  <th>Project Quality</th>
                  <th>Project Status</th>
                </tr>
         </thead>  
         <tbody>
          @foreach($projects as $project)
             <tr>
              <td>{{ $project->procurementdetails->procurement_name ?? '' }}</td>
              <td><a href="{{ URL::to('/') }}/admindailyslots?projectId={{$project->project_id}}&&lename={{ $project->name }}" target="_blank">{{ $project->project_id }}</a></td>

              <td>   <a href="{{ URL::to('/')}}/viewsubward?projectid={{$project->project_id}} && subward={{ $project->subward->sub_ward_name ?? '' }}" target="_blank">
                                    {{$project->subward->sub_ward_name ?? ''}}
                                </a></td>

              <td><a target="_blank" href="https://maps.google.co.in?q={{ $project->siteaddress->address ?? '' }}">{{ $project->siteaddress->address ?? '' }}</a></td>
              <td> {{ $project->procurementdetails->procurement_name ?? '' }}</td>
              <td>{{ $project->procurementdetails->procurement_contact_no ?? '' }}</td>
              <td style="font-weight:bold;color:#070808;font-style:italic;">{{$project->project_size}}</td>
              <td>{{$project->quality}}</td>
              <td>{{$project->project_status}}</td>
            </tr>
              @endforeach
         </tbody>       
         
       </table>
      </div>
      
    </div>
 
  </div>

