@extends('layouts.salesheader')
@section('content')  
<div class="col-md-12">     
    <div class="col-md-12" >
    <div class="panel panel-default" style="overflow: scroll;">
    	 <?php $mmm = App\ProcurementDetails::whereIn('procurement_contact_no',$projects)->pluck('project_id'); 
                                     
                                    $projectcount = App\ProjectDetails::whereIn('project_id',$mmm)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->count();
                                     
                                ?>
            <div class="panel-heading" style="background-color:#158942;color:white;font-size:1.4em;"> <span style="font-weight:bold;">No of  Customers : {{ count($projects) }}   </span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-weight:bold;">No of Projects : {{ $projectcount }} </span>
        
              
            </div>  
         <div class="panel-body" id="page">
       <table class="table table-hover table-striped" border="1">
                <thead>
                 
                 <th>Procurement Name</th>
                  <th>Contact No.</th>
                  <th>No of Projects</th>
                  <th>Project Id</th>
                  <th>Project Size</th>
                <th>Last updated </th> 
                  <th>Total Size</th>
               
                 <th>Action</th>
                
               </thead>
                <tbody>
             <?php $ii=0; ?>
            @foreach($projects as $project)
              <tr>
                   <td><?php $s = App\ProcurementDetails::where('procurement_contact_no',$project)->pluck('project_id'); 
                                   $sp = App\ProjectDetails::whereIn('project_id',$s)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->pluck('project_id');

                                    $sm = App\ProcurementDetails::whereIn('project_id',$sp)->pluck('procurement_name')->first();

                              ?>
                                
                                {{$sm}}
                              </td>
                  <td><?php $ss = App\ProcurementDetails::where('procurement_contact_no',$project)->pluck('project_id'); 
                                   $sp = App\ProjectDetails::whereIn('project_id',$ss)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->pluck('project_id');

                                    $sm = App\ProcurementDetails::whereIn('project_id',$sp)->pluck('procurement_contact_no')->first();

                              ?>
                                
                                {{$sm}}
                              </td>
                               <td>
                                <?php $mm = App\ProcurementDetails::where('procurement_contact_no',$project)->pluck('project_id'); 
                                     
                                    $m = App\ProjectDetails::whereIn('project_id',$mm)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->count();
                                     
                                ?>
                                @if($m > 0)
                                {{$m}}<br>
                                @endif
                              </td>
                              <td>
                                <?php $yupp = App\ProcurementDetails::where('procurement_contact_no',$project)->pluck('project_id');
                                    $yup = App\ProjectDetails::whereIn('project_id',$yupp)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->get();

                                    $yupms = App\ProjectDetails::whereIn('project_id',$yupp)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->pluck('project_id');

                                     
                                 ?>
                                
                                @foreach($yup as $yum)
                                       <a target="_none" href="{{URL::to('/')}}/showThisProject?id={{$yum}}">{{$yum->project_id}}</a><br>
                                    @endforeach
                                   </td>
                                       <td>
                                <?php $p = App\ProcurementDetails::where('procurement_contact_no',$project)->pluck('project_id');
                                     
                                     $m = App\ProjectDetails::whereIn('project_id',$p)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->pluck('project_size');

                                 ?>
                                
                               @foreach($m as $yumm)

                                           {{ number_format($yumm) }}<br>
                                   @endforeach
                              </td>
                              <td>
                                <?php $yuppp = App\ProcurementDetails::where('procurement_contact_no',$project)->pluck('project_id');
                                    $yupp = App\ProjectDetails::whereIn('project_id',$yuppp)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->get();
                                   
                                 ?>
                                
                                @foreach($yupp as $yum)
                                     {{ date('d-m-Y', strtotime(  $yum->updated_at)) }} <span style="font-weight:bold;color:black;">({{$yum->upuser !=null ? $yum->upuser->name : ''}})</span>

                                           <br>
                                   @endforeach
                              </td>
                              <td>
                                <?php $b = App\ProcurementDetails::where('procurement_contact_no',$project)->pluck('project_id');
                                     
                                     $c = App\ProjectDetails::whereIn('project_id',$b)->where('project_status','NOT LIKE',"%Closed%")->where('quality','!=',"FAKE")->pluck('project_size')->sum();

                                 ?>
                                @if($c > 0)
                               {{ number_format($c) }}<br>
                                @endif
                              </td>
                                
                                   <td>
                                    @foreach($yup as $yum)
                                        <form method="post" action="{{ URL::to('/') }}/confirmedProject" >
                                      {{ csrf_field() }}
                                      <input type="hidden" value="{{ $yum->project_id }}" name="id">
                                      <div >
                                      <!-- <button  type="button" data-toggle="modal" data-target="#myModal{{ $yum->project_id }}" class="btn btn-sm btn-warning " style="color:white;font-weight:bold;padding: 6px;width:80px;" id="viewdet({{$yum->project_id}})">Edit</button> -->
                                      <a class="btn btn-sm btn-success " name="addenquiry" href="{{ URL::to('/') }}/requirements?projectId={{ $yum->project_id }}" style="color:white;font-weight:bold;padding: 6px;">Add Enquiry</a>
                                      
                                      @if( $yum->confirmed !== "0" ||  $yum->confirmed == "true" )
                                   <button  type="button" id="demo"  style="padding: 5.5px;background-color:#e57373;color:white" class="btn btn-sm " {{ $yum->confirmed !== "0" ||  $yum->confirmed == "true" ? 'checked': ''}}  name="confirmed" onclick="this.form.submit()">Called
                                   <span class="badge">&nbsp;{{  $yum->confirmed }}&nbsp;</span>
                                   </button>
                                  @endif
                                   @if( $yum->confirmed == "0" ||  $yum->confirmed == "false" )
                                   <button style="padding: 5.5px;background-color: #aed581;color:white" id="demo"  type="button" class="btn  btn-sm "  {{ $yum->confirmed !== "0" ||  $yum->confirmed == "true" ? 'checked': ''}}  name="confirmed" onclick="this.form.submit()">Called
                                    <span class="badge">&nbsp;{{  $yum->confirmed }}&nbsp;</span>
                                   </button></div>
                                  @endif
                                  <button  type="button" data-toggle="modal" data-target="#myquestions{{ $yum->project_id }}" class="btn btn-sm btn-warning " style="color:white;font-weight:bold;padding: 6px;width:80px;">Questions</button>
                                </form>
<!-- Modal -->  
<div id="myquestions{{ $yum->project_id }}" class="modal fade" role="dialog">
  <div class="modal-dialog">
 <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color:rgb(245, 127, 27);color: white;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Select The Questions</h4>
        </div>
        <div class="modal-body">
          <form method="get" action="{{ URL::to('/') }}/storequery">
            {{ csrf_field() }}
           <input type="hidden" value="{{ $yum->project_id }}" name="id">
          <table class="table table-responsive">
            <tr>

              <td><label>Questions :</label></td>
              <td>
                          <select required style="width: 100%" class="form-control" name="qstn">
                                    <option disabled selected>--- Select ---</option>
                                    <option value="NOT INTERESTED">NOT INTERESTED</option>
                                    <option  value="BUSY">BUSY</option>
                                    <option  value="WRONG NO">WRONG NO</option>
                                    <option  value="PROJECT CLOSED">PROJECT CLOSED</option>
                                    <option  value="CALL BACK LATER">CALL BACK LATER</option>
                                    <option value="THEY WILL CALL BACK WHEN REQUIRED">THEY WILL CALL BACK WHEN REQUIRED</option>
                                    <option value="CALL NOT ANSWERED">CALL NOT ANSWERED</option>
                                    <option value="FINISHING">FINISHING</option>
                                    <option  value="SWITCHED OFF">SWITCHED OFF</option>
                                    <option  value="SAMPLE REQUEST">SAMPLE REQUEST</option>
                                    <option  value="MATERIAL QUOTATION">MATERIAL QUOTATION</option>
                                    <option  value="WILL FOLLOW UP AFTER DISCUSSION WITH OWNER">WILL FOLLOW UP AFTER DISCUSSION WITH OWNER</option>
                                    <option  value="DUPLICATE NUMBER">DUPLICATE NUMBER</option>
                                    <option  value="NOT REACHABLE">NOT REACHABLE</option>
                                    <option  value="THEY HAVE REGULAR SUPPLIERS">THEY HAVE REGULAR SUPPLIERS</option>
                                    <option  value="CREDIT FACILITY">CREDIT FACILITY</option>
                                  </select>
                        </td>
                      </tr>
                      <tr>
                        <td><label>Call Remark : </label></td>
                        <td><textarea required style="resize: none;" class="form-control" placeholder="Remarks" name="remarks" ></textarea></td>
                      </tr>
                    </table>
       
      <button type="submit" class=" form-control btn btn-primary">Submit</button>
      </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>  
                                         
                                   @endforeach
                       </td>
                      
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
                
                    
                   
                   
                    
  
  @endsection
