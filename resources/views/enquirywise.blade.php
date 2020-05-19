@extends('layouts.app')
@section('content')
<div class="col-md-12" >
        <div class="panel panel-primary"  style="overflow-x:scroll">
           <div class="panel-heading text-center">
                    <a href="{{ URL::to('/') }}/inputview" class="btn btn-danger btn-sm pull-left">Add Enquiry</a>
                    <a class="pull-right btn btn-sm btn-danger" href="{{url()->previous()}}">Back</a>
                Enquiry Data&nbsp;&nbsp;&nbsp;   COUNT: [{{$projects->total()}}]
            </div>
            <div class="panel-body">
                <table class='table table-responsive table-striped' style="color:black" border="1">
                    <thead>
                        <tr>
                            <th style="text-align:center">Enquiry Id</th>

                            <th style="text-align:center">Project Id/Manufacturer Id</th>
                            <th style="text-align:center">Customer Name</th>
                            <th style="text-align:center">Category</th>

                            <th style="text-align:center">requirnment Date</th>
                            <th style="text-align:center"> Contact Number</th>
                            <th style="text-align:center">Quantity</th>
                            <th style="text-align:center">status</th>
                            <th style="text-align:center"> Click here to cancell enquiry</th>
                            <th style="text-align:center">Remark</th>
                            <th style="text-align:center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="mainPanel">
                       @foreach($projects as $project)
                       <tr>
                        <td style="text-align:center;">{{$project->id}} </td>
                            <td style="text-align:center;">
                                 @if($project->project_id != NULL)
                                <a  href="{{ URL::to('/') }}/admindailyslots?projectId={{$project->project_id}}&&lename={{ $project->name }}" >project : {{ $project->project_id }}</a>
                                <?php $pr= App\ProcurementDetails::where('project_id',$project->project_id)->pluck('procurement_name') ;?>
                                @else
                                   <a  href="{{ URL::to('/') }}/viewmanu?id={{ $project->manu_id }}" >Manufacturer : {{ $project->manu_id }}</a>
                                   <?php $pr= App\Mprocurement_Details::where('id',$project->manu_id)->pluck('name') ;?>
                                 @endif

                            </td>
                            <td>{{ $pr }}</td>
                            <td>{{$project->main_category}}</td>
                            <td>{{ $project->requirement_date }}  </td> 
                            <td>{{ $project->procurementdetails->procurement_contact_no ?? ' '   }} {{$project->proc->contact ?? ''}}</td>
                            <td>{{ $project->quantity }}  </td>                     
                            <td>{{ $project->status }}  </td> 
                            <td>
                            

 
@if($project->status=='Enquiry On Process')
<form method="get" action="{{ URL::to('/') }}/enquiryCancells">
									{{ csrf_field() }} 
                  <input type="hidden" name="eid" value="{{$project->id}}" >

									
              
                <input type="submit" value="Click to Cancell" class="form-control" > 
</form> </td>
@endif
                            <td>{{ $project->notes }} </td>
                            
                            <td><a href="{{ URL::to('/') }}/editenq?reqId={{ $project->id }}" class="btn btn-xs btn-primary">Edit</a></td>

                       </tr>
                       @endforeach
                    </tbody>
                </table>
            </div>
            <div class="panel-footer">
                <center>{{ $projects->links() }}</center>
            </div>
        </div>
    </div>
    
<script type="text/javascript">
	function edit(arg){
		document.getElementById('now'+arg).className = "hidden";
		document.getElementById('next'+arg).className = "";
		document.getElementById('next'+arg).focus();
	}
	function editm(arg){
		document.getElementById('noww'+arg).className = "hidden";
		document.getElementById('nextt'+arg).className = "form-control";
	}
</script>
<script type="text/javascript">
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  // Loop through all table rows, and hide those who don't match the search query
  
  if(filter == "ALL"){
  	for (i = 0; i < tr.length; i++) {
        tr[i].style.display = "";
	  }
	}else{
		for (i = 0; i < tr.length; i++) {
	    td = tr[i].getElementsByTagName("td")[7];
	    if (td) {
	      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
	        tr[i].style.display = "";
	      } else {
	        tr[i].style.display = "none";
	      }
	    }
	  }
	}
}
function searchphone(){
	var input, filter, table, tr, td, i;
  input = document.getElementById("searchphone");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  // Loop through all table rows, and hide those who don't match the search query
  
  if(filter == "ALL"){
  	for (i = 0; i < tr.length; i++) {
        tr[i].style.display = "";
	  }
	}else{
		for (i = 0; i < tr.length; i++) {
	    td = tr[i].getElementsByTagName("td")[4];
	    if (td) {
	      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
	        tr[i].style.display = "";
	      } else {
	        tr[i].style.display = "none";
	      }
	    }
	  }
	}
}
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
     background-color: #00acd6 

});

</script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
     background-color: #00acd6 

});
</script>
@endsection
