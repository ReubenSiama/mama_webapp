
@extends('layouts.app')
@section('content')   
<span class="pull-right"> @include('flash-message')</span>
  
    <div class="col-md-8 col-md-offset-2" >
    <div class="panel panel-default" style="overflow: scroll;">
            <div class="panel-heading" style="background-color:#158942;color:white;font-size:1.4em;">Total Customers Count : {{count($projects)}} 
         <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-7px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color: black;"></i></button>
              
            </div>  
         <div class="panel-body" id="page">
       <table class="table table-hover table-striped">
                <thead>
                  <th>Customer Name</th>
                  <th>Customer Id</th>
                  <th>Contact No.</th>
                 <th>Action</th>
               </thead>
                <tbody>
             
            @foreach($projects as $project)
                <tr>
                    <td id="projname-{{$project->id}}">{{ $project->first_name }}</td>
                     <td  style="text-align:center"><a href="{{ URL::to('/') }}/customerprojects?customer_id={{$project->customer_id}}" target="_blank">{{ $project->customer_id }}</a></td>
                  
                    <td id="projproc-{{$project->project_id}}">
                                        {{ $project->mobile_num}}
                                    </td>
                    
                   <td>
                      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Customer Feedback</button>
                      <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal2">Visited Feedback</button>


          

          <form action="{{URL::to('/')}}/test" method="POST" id="form">
                   <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Customer Feedback</h4>
        </div>
        <div class="modal-body">
             {{csrf_field()}}
             <input type="hidden" name="userid" value="{{Auth::user()->id}}">
             <input type="hidden" name="cid" value="{{$project->customer_id}}">

         <label style="color:green;font-weight:bold;">Interested Categories</label><br>
          <?php $cat = App\Category::all() ?>
           @foreach($cat as $c)
            <div class="col-md-4">

              <input type="checkbox" name="cat[]" value="{{$c->id}}">{{$c->category_name}}
               @if($loop->iteration % 3==0)
                              </div>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <div class="row">
                              @endif
            </div>
            @endforeach

        <br><br>
         
         <label style="color:green;font-weight:bold;">
           Feedback Information :

         </label>
         <textarea rows="4" cols="40" name="feedback" id="eremarks" class="form-control" /></textarea>
         <center><button class="btn btn-sm btn-warning" type="submit" onclick="document.getElementById('form').submit()">Submit</button></center>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
       </form>


                   </td>

                  </tr>
@endforeach

</tbody>
</table>


</div>
        
  </div>
 </div>
   @foreach($projects as $project)
<form action="{{URL::to('/')}}/testpull" method="POST" id="form1">
                   <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Visited Feedback</h4>
        </div>
        <div class="modal-body">
             {{csrf_field()}}
             <input type="hidden" name="userid" value="{{Auth::user()->id}}">
             <input type="hidden" name="cid" value="{{$project->customer_id}}">
                 <tr>
                                 <td>Customer Visited ?</td>
                                 <td>:</td>
                                 <td>
                                     
                                      <label ><input required value="Yes" id="rmc" type="radio" name="cvisit"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                      <label ><input required value="No" id="rmc2" type="radio" name="cvisit"><span>&nbsp;</span>No</label> 
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                      <label><input checked="checked" value="None" id="rmc3" type="radio" name="cvisit"><span>&nbsp;</span>None</label>
                                 </td>
                               </tr>
                               <tr><br>
                                 <td>Site Visited ?</td>
                                 <td>:</td>
                                 <td>
                                    
                                      <label><input required value="Yes" id="loan1" type="radio" name="site"><span>&nbsp;</span>Yes</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                  
                                      <label><input required value="No" id="loan2" type="radio" name="site"><span>&nbsp;</span>No</label>
                                       <span>&nbsp;&nbsp;&nbsp;  </span>
                                
                                      <label><input checked="checked" required value="None" id="loan3" type="radio" name="site"><span>&nbsp;</span>None</label>
                                   
                                 </td>
                               </tr><br><br>
        
         <center><button class="btn btn-sm btn-warning" type="submit" onclick="document.getElementById('form1').submit()">Submit</button></center>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
       </form>
@endforeach
  
  @endsection
