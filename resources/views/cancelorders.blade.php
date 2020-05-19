@extends('layouts.app')
@section('content')
           
<div class="col-md-12">
        <div class="col-md-12">
          <div class="panel panel-default" style="overflow: scroll;">
            <div class="panel-heading" style="background-color:#158942;color:white;font-size:1.4em;font-weight:bold">Cancelled Orders List : {{count($projects)}}</div>  
            @if(session('success'))
<script>
    swal("success","{{ session('success') }}","success");
</script>
@endif
            <div class="panel-body">
             
             <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for Project id.." title="Type in a name" class="form-control">
              <table class="order-table table table table-hover" id="myTable">
                <thead>
                    <th>Project ID</th>
                  <th>Enquiry ID</th>
                  <th> Order Id.</th>
            <th>Required</th>
            <th>Quantity</th>
            <th>Remark</th>
           <!--  <th>Action</th> -->
            <th>Order Status</th>
                </thead>

              @foreach($projects as $project)
              <tr>
                   <td>
                            <a href="{{URL::to('/')}}/showThisProject?id={{$project->project_id}}">{{$project -> project_id}}</a>
                             @if($project -> project_id == null)
                            <a href="{{ URL::to('/') }}/viewmanu?id={{ $project->manu_id }}">Manufacturer {{$project -> manu_id}}</a>
                            @endif
                        </td>
                        <td>
                                <a href="{{ URL::to('/') }}/editenq?reqId={{ $project->req_id }}" >{{$project->req_id}}</a>
                        </td>
                    <td>
                        {{$project->id}}
                      </td>
                       
             
              <td>
                            {{$project -> main_category}}<br>
                            <!-- {{$project -> sub_category}} -->
                            @if($project->main_category == "STEEL" )
                                <?php
                                    $id = explode(",",$project->sub_category);
                                
                                ?>
                                (
                                @for($i=0; $i<count($id) ; $i++)

                                   <?php
                                   $name = App\SubCategory::where('id',$id[$i])->pluck('sub_cat_name')->first();
                                   ?>
                                            {{$name}},
                                @endfor
                                )
                                @else
                                <?php
                                   $name = App\SubCategory::where('id',$project->sub_category)->pluck('sub_cat_name')->first();
                                   ?>
                                ({{$name}})
                            @endif
                                <br>
                            {{$project -> brand}}<br>
                        </td>
                        <td>{{$project->quantity}} {{$project->measurement_unit}}</td>
                        <td>{{$project->notes}}</td>
                  <!--   <td>

                    <div class="btn-group btn-group-xs">
                                   <form action="{{ url('/toggle-manu')}}" method="post">
                                 {{csrf_field()}}
                                  <input value="off" type="hidden" name="deleted">
                                  <input type="hidden" name="id1" value="{{$project->id}}">
                                  <button type="submit" data-toggle="tooltip"onclick="return confirm('Are you sure you want to UnBlock this Project?');"  button class="btn btn-sm" style="background-color:#F57F1B;color:white;font-weight:bold">UnBlock
                               </button>
                              </form>
                            </div>
                    </td> -->
                    @if($project->status !=NULL)
                    <td>{{$project->status}}</td>
                    @else
                    <td>-</td>
                    @endif
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