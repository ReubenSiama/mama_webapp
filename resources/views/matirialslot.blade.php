<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)
@section('content')
<?php $url = Helpers::geturl(); ?>
    
    <div class="col-md-12" >
        <div class="panel panel-primary" style="overflow-x:scroll;">
            <div class="panel-heading" id="panelhead" style="padding:20px;">
                <label> 
                   
                </b></label>
                 <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color:black;"></i></button> 
            </div>
            <div class="panel-body">
                <table class='table table-responsive table-striped' style="color:black" border="1">
                    <thead>
                        <tr>
                            <th style="text-align:center">Subward Name</th>
                            <th style="text-align:center">ID</th>
                            <th style="text-align:center">OwnerNumber</th>
                            <th style="text-align:center">OwnerName</th>
                            <th style="text-align:center">Address</th>
                            <th style="text-align:center">Category</th>
                            <th style="text-align:center">Listing Engineer</th>
                            <th style="text-align:center">Action</th>
                            <!--<th style="text-align:center">Verification</th>-->
                        </tr>
                    </thead>
                    <tbody id="mainPanel">
                        @foreach($data as $project)
                        <tr>
                        <td>{{$project->subward->sub_ward_name ?? ''}}</td>
                        <td>{{$project->id}}</td>

                        <td>{{$project->onumber}}</td>
                        <td>{{$project->name}}</td>
                        <td>{{$project->address}}</td>
                        <td>{{$project->Category}}</td>
                        <td>{{$project->user->name ?? ''}}</td>
                       <td><a href="{{URL::to('/')}}/editmat?id={{$project->id}}"><i class="fa fa-edit"></i></a>
                       <i class="fa fa-eye" data-toggle="modal" data-target="#myModal{{$project->id}}"></i>
                       </td>
                             </tr>
                      @endforeach
                    </tbody>
                </table>
                @foreach($data as $project)
                 <div class="modal fade" id="myModal{{$project->id}}" role="dialog">
              <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Materialhub Details</h4>
        </div>
        <div class="modal-body">
          <table class='table table-responsive table-striped' style="color:black" border="1">
              <tr>
                <td>Subward Name</td>
                <td>:</td>
                <td>{{$project->subward->sub_ward_name ?? ''}}</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>:</td>
                <td>{{$project->id}}</td>
            </tr>
             <tr>
                <td>OwnerNumber</td>
                <td>:</td>
                <td>{{$project->onumber}}</td>
            </tr>
             <tr>
                <td>OwnerName</td>
                <td>:</td>
                <td>{{$project->name}}</td>
            </tr>
            <tr>
                <td>Address</td>
                <td>:</td>
                <td>{{$project->address}}</td>
            </tr>
             <tr>
                <td>Category</td>
                <td>:</td>
                <td>{{$project->Category}}</td>
            </tr>
             <tr>
                <td>Capacity</td>
                <td>:</td>
                <td>{{$project->Capacity}}</td>
            </tr>
            <tr>
                <td>remarks</td>
                <td>:</td>
                <td>{{$project->remarks}}</td>
            </tr>
             <tr>
                            <td><b>Project Image  </b></td>
                            <td>:</td>
                            <td>
                               <?php
                                               $images = explode(",", $project->pImage);
                                               ?>
                                             <div class="row">
                                                 @for($i = 0; $i < count($images); $i++)
                                                     <div class="col-md-3">
                                                          <img height="350" width="350"  src="{{ $url }}/Materialhub/{{ $images[$i] }}" class="img img-thumbnail">
                                                     </div>
                                                 @endfor
                                              </div>
                            </td>
                           
                        </tr>
          </table>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
            @endforeach  










                <center>
                    <div id="wait" style="display:none;width:200px;height:200px;"><img src='https://www.tradefinex.org/assets/images/icon/ajax-loader.gif' width="200" height="200" /></div>
                </center>
            </div>
        </div>
    </div>
    <script src="http://code.jquery.com/jquery-3.3.1.js"></script>
    
    <script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
     background-color: #00acd6 

});

</script>
@endsection
