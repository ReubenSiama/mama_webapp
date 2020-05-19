<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)
@section('content')
<div class="container">
<div class="col-md-12">
    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: green;color:white;">Unverified Manufacturers
                @if($totalmanu != 0)
               Count : <b>{{ $totalmanu }}</b>
                @endif

                    @if(session('ErrorFile'))
                        <div class="alert-danger pull-right">{{ session('ErrorFile' )}}</div>
                    @endif 
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                    <form method="GET" action="{{ URL::to('/') }}/unverifiedManufacturers">
                        <div class="col-md-2">
                <label>Choose Ward :</label><br>
                          <select required name="ward" class="form-control" id="ward" onchange="loadsubwards()">
                              <option value="">--Select--</option>
                              @if(Auth::user()->group_id != 22)
                              <option value="All">All</option>
                              @foreach($wards as $ward)
                              <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                              @endforeach
                              @else
                              @foreach($tlwards as $ward)
                              <option value="{{ $ward->id }}">{{ $ward->ward_name }}</option>
                              @endforeach
                              @endif
                          </select>
              </div>
              <div class="col-md-2">
                <label>Choose Subward :</label><br>
                          <select name="subward" class="form-control" id="subward">
                          </select>
              </div>
              <div class="col-md-2">
                  <label>Choose Type :</label><br>
                  <select  class="form-control" name="type" required>
                      <option value="">--Select Manufacturer Type--</option>
                      <option value="RMC">RMC</option>
                      <option value="M-Sand">M-Sand</option>
                      <option value="Fabricators">Fabricators</option>
                       <option value="AGGREGATES">Aggregates</option>
                      <option value="Blocks">Blocks</option>
  
                  </select>
                </div>
              <div class="col-md-2">
                <label></label>
                <input type="submit" value="Fetch" class="form-control btn btn-primary">
              </div>
          </form>
          </div>
        <br><br><br><br>
          <table class="table table-hover">
          <thead>
            <th>Manufacturers Id</th>
            
            <th>Manufacturers Type</th>
           
            <th>Address</th>
            <th>Last Update</th>
            <th>Remarks</th>
          </thead>
  
        
          @foreach($manu_data as $Manu)
          <tbody>
              <td style="text-align:center"><a href="{{ URL::to('/') }}/viewmanu?id={{$Manu->id}}" target="_blank">{{ $Manu->id }}</a></td>
             
            
             <td>{{ $Manu->manufacturer_type }}</td>
            
              <td> <a href="#" >{{ $Manu->address }}</a></td>
              <td style="width:10%;">
                  {{ date('d-m-Y', strtotime($Manu->updated_at)) }}
                  @foreach($names as $name)
                    @if($name->id == $Manu->updated_by)
                     {{ $name->name}} 
                    @endif
                    @endforeach
                </td>
                <td>{{ $Manu->remarks }}</td>
          @endforeach
        </table>
        @if(count($manu_data) != 0)
                {{ $manu_data->appends($_GET)->links() }}
                @endif
                </div>
             
    </div>
   </div>
</div>
<script type="text/javascript">
    function loadsubwards()
    {
        var x = document.getElementById('ward');
        var sel = x.options[x.selectedIndex].value;
        if(sel)
        {
            $.ajax({
                type: "GET",
                url: "{{URL::to('/')}}/loadsubwards",
                data: { ward_id: sel },
                async: false,
                success: function(response)
                {
                    if(response == 'No Sub Wards Found !!!')
                    {
                        document.getElementById('error').innerHTML = '<h4>No Sub Wards Found !!!</h4>';
                        document.getElementById('error').style,display = 'initial';
                    }
                    else
                    {
                        var html = "<option value='' disabled selected>---Select---</option>";
                        for(var i=0; i< response.length; i++)
                        {
                            html += "<option value='"+response[i].id+"'>"+response[i].sub_ward_name+"</option>";
                        }
                        document.getElementById('subward').innerHTML = html;
                    }
                    
                }
            });
        }
    }
</script>
@endsection
