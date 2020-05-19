@extends('layouts.app')
@section('content')
           
<div class="col-md-12">
        <div class="col-md-12">
          <div class="panel panel-default" style="overflow: scroll;">
            <div class="panel-heading" style="background-color:#158942;color:white;font-size:1.4em;font-weight:bold">Blocked Manufacturer List : {{count($projects)}}</div>  
            @if(session('success'))
<script>
    swal("success","{{ session('success') }}","success");
</script>
@endif
            <div class="panel-body">
             
             <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for Manufacuturer id.." title="Type in a name" class="form-control">
              <table class="order-table table table table-hover" id="myTable">
                <thead>
                    <th>Manufacturer Id</th>
                  <th>Procurement Name</th>
                  <th>Contact No.</th>
            <th>Sub-Ward Number</th>
            <th>Manufacturer Type</th>
            <th>Address</th>
            <th>Action</th>
            <th>Remark</th>
            <th>Blocked By</th>
                </thead>

              @foreach($projects as $project)
              <tr>
                    <td style="text-align:center"><a href="{{ URL::to('/') }}/viewmanu?id={{ $project->id }}" >{{ $project->id }}</a></td>
                    
                    <td id="projproc-{{$project->id}}">
                                        {{ $project->proc != NULL?$project->proc->name:'' }}
                                    </td>
                    <td id="projcont-{{$project->project_id}}"><address>{{ $project->proc != NULL?$project->proc->contact:'' }}</address></td>
                   
              
              <td>
                {{ $project->subward != null ? $project->subward->sub_ward_name:' ' }}
                                    </a></td>
             
            <td>
                {{ $project->manufacturer_type }}
                                    </a></td>
              <td>{{ $project->address }}</td>

                    <td>

                    <div class="btn-group btn-group-xs">
                                   <form action="{{ url('/toggle-manu')}}" method="post">
                                 {{csrf_field()}}
                                  <input value="off" type="hidden" name="deleted">
                                  <input type="hidden" name="id1" value="{{$project->id}}">
                                  <button type="submit" data-toggle="tooltip"onclick="return confirm('Are you sure you want to UnBlock ?');"  button class="btn btn-sm" style="background-color:#F57F1B;color:white;font-weight:bold">UnBlock
                               </button>
                              </form>
                            </div>
                    </td>
                    @if($project->blockremark !=NULL)
                    <td>{{$project->blockremark}}</td>
                    @else
                    <td>-</td>
                    @endif
                    <td>{{$project->block != null ? $project->block->name:''}}</td>
                  </tr>
                                
                  @endforeach
                </tbody>
              </table>
                </table>
                </div>
                </div>
                </div>
                </div>


<script>
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
</script>



 @endsection