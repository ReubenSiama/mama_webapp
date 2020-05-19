@extends('layouts.app')
@section('content')
<div class="container">
	 <form action="{{URL::to('/')}}/closedcontractor" method="get" id="type">
    <div class="col-sm-4">
       <select class="form-control" name="type">
          <option value="">--Select Type of Customer--</option>
          <option value="Owners">Owners</option>
          <option value="SiteEngineer">Site Engineer</option>
          <option value="Contractors">Contractors</option>
          <option value="builders">Builders</option>
          <option value="">None</option>

       </select>
       </div>
       <div class="col-sm-2">
       
         <button onclick="document.getElementById('type').submit()" class="btn btn-warning btn-sm">Get Deatils</button>
       </div> 
   </form>  
  



<br><br>

    <div class="row">
    	 @if(count($duplicates) > 0)
      <h3 style="text-align:center";> <span style="background-color:#777777;color:white;font-weight:bold;"> Total Closed Proposed <?php echo $_GET['type'] ?>  {{$duplicates->total()}} </span> <br>Total Project {{count($projects)}} </h3>
      @endif
  <div class="col-sm-8 col-sm-offset-2">
    <form action="{{URL::to('/')}}/storecontractors" method="post" id="test1" >
       {{ csrf_field() }}
       @if(count($duplicates) > 0)
            <input type="hidden" name="type" value="<?php echo $_GET['type'] ?>">
                  <div class="col-md-3 col-sm-offset-2">
                     <?php $users = App\User::where('department_id','!=',10)->get() ?>
                 <select  name="user"  style="width:300px;" class="form-control">
                    <option value="">--Select--</option>
                   @foreach($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>
                     @endforeach
                  </select>
                </div>
                 <div class="col-md-1 col-sm-offset-2">
                  <input onclick="document.getElementById('test1').submit()" class="btn btn-sm btn-warning" value="Assign">
                </div>
                @endif
      <br><br>
 <table class="table" border="1">
         <thead style="background-color:#9fa8da">
              <th>Select All &nbsp;&nbsp;
          <input type="checkbox" id="selectall" class="regular-checkbox" /><label for="selectall"></th>
          <th>SlNo</th>

	  	<th>Name</th>
	  	<th>Number</th>
      <th>Proposed Id</th>
	  	<th>Projects count</th>
	  </thead>
	    <tbody>
        <?php $m=1; ?>
        <?php $data = App\ProposedProjects::where('Contractor',NULL)->pluck('p_p_c_id')->toarray(); ?>
	    	@foreach($duplicates as $dump)
                <?php $d[] = $dump->p_p_c_id; ?>
                @if(!in_array($data,$d))
	    	   <tr>
            <td><input type="checkbox" name="number[]" class="regular-checkbox name"  id="checkbox-1-1" value="{{$dump->number}}" /><label for="checkbox-1-1"></label></td>
            <td>{{$m++}}</td>

                   <td>{{$dump->name}}</td>
                   <td>{{$dump->number}}</td>
                   <td>{{$dump->p_p_c_id}}</td>
                   <td>{{$dump->products_count}}
                        
                   </td>
               </tr>
               @endif
                @endforeach     
        
	    </tbody>
</table>
@if(count($duplicates) > 0)
            <center>{{ $duplicates->appends(request()->query())->links()}} </center>   
 
  @endif
</form>
</div>
 
</div>
</div>
<script type="text/javascript">
  
    $(function () {
        // add multiple select / deselect functionality
        $("#selectall").click(function () {
            $('.name').attr('checked', this.checked);
        });
 
        // if all checkbox are selected, then check the select all checkbox
        // and viceversa
        $(".name").click(function () {
 
            if ($(".name").length == $(".name:checked").length) {
                $("#selectall").attr("checked", "checked");
            } else {
                $("#selectall").removeAttr("checked");
            }
 
        });
    });
</script>
@endsection