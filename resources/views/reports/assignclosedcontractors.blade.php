@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
    <form method="GET" action="{{ URL::to('/') }}/assignclosedcontractors">
                    <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-4">
                                <label>Search with Phone Number </label>
                                <input  type="text" class="form-control" name="sid">
                            </div>
                           
                            
                             <div class="col-md-2">
                              <label></label>
                              <button type="submit" class="form-control  btn btn-warning btn-sm">Submit</button>  
                            </div>
          </div>    
          </form>
          <br>
          <br>
          <div>
          <form method="GET" action="{{ URL::to('/') }}/assignclosedcontractors">
                    <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-6">
                                <label>Search with Wards  </label>

                                <?php $ward = App\Ward::All(); ?>
                               <select class="form-control" name="ward_id" >
                               @foreach($ward as $wards)
                                <option value="{{$wards->id}}">{{$wards->ward_name}}</option>
                               @endforeach
                              </select>
                            </div>
                           
                            
                             <div class="col-md-2">
                              <label></label>
                              <button type="submit" class="form-control  btn btn-info btn-sm">Submit</button>  
                            </div>
          </div>    
          </div>
          </form>
  <span class="pull-right"> @include('flash-message')</span>
  <br><br>
  <center><h2>Closed {{$type}} Customer details : {{$data->total()}}</h2></center><br><br>
<table class="table" border="1">
         <thead style="background-color:#9fa8da">
          <th>SlNo</th>
            <th> Name</th>
           <th> Number</th>
           <th>Project Ids</th>
            <th>Project Details</th>
         </thead>
         <tbody>
          <?php $s =1; ?>
          @foreach($data as $dump)
           <tr>
            <td>{{$s++}}</td>
            <td>{{$dump->name}}</td>
            <td>{{$dump->number}}</td>
            <?php  
                    if($type == "Owners"){
                  $ids = App\OwnerDetails::where('owner_contact_no',$dump->number)->pluck('project_id')->toarray();  

                    }elseif($type == "builders"){
                  $ids = App\Builder::where('builder_contact_no',$dump->number)->pluck('project_id')->toarray();  

                }elseif($type=="SiteEngineer"){
                  $ids = App\SiteEngineerDetails::where('site_engineer_contact_no',$dump->number)->pluck('project_id')->toarray();  

                }elseif($type == "Contractors"){

                  $ids = App\ContractorDetails::where('contractor_contact_no',$dump->number)->pluck('project_id')->toarray();  

                }else{
                  $ids =[];
                }
                $ward_id=Null;
                if($ward_id!=Null){
                  $dd = App\SubWard::where('ward_id',$ward_id)->pluck('id')->toarray();
                  
                    $projects =App\ProjectDetails::whereIn('project_id',$ids)->whereIn('sub_ward_id',$dd)->where('project_status',"Closed")->where('quality',"Genuine")->groupBy('sub_ward_id')->get();

                  }
                     else{
                      $projects =App\ProjectDetails::whereIn('project_id',$ids)->where('project_status',"Closed")->where('quality',"Genuine")->get();

                     }    
                         

                         ?>
            <b></b>
             <td>{{count($projects)}}  </td>
             <td> 
                <table class="table" border="1">
                   <thead>
                     <th>Project Id</th>
                     <th>Status</th>
                     <th>Project Size</th>
                     <th>Sub  Ward</th>
                     <th>Last Update</th>
                   </thead>
                   <tbody>
                    @foreach($projects as $pro)
                     <tr>
                     <td><a href="{{ URL::to('/') }}/admindailyslots?projectId={{$pro->project_id}}" target="_blank">{{ $pro->project_id }}</a></td>
                      <td>{{$pro->project_status}}</td>
                      <td>{{$pro->project_size}}</td>
                      <?php  $subward=App\SubWard::where('id',$pro->sub_ward_id)->pluck('sub_ward_name')->first(); ?>
                      <td>{{$subward}}</td>
                     
                      <td>{{$pro->updated_at}}</td>

                     </tr>
                     @endforeach
                   </tbody>
                </table>  
                    
             </td>
             <td>
              {{$dump->yes}}
             </td>
             
           
           </tr>
           @endforeach
         </tbody>
<center>{{ $data->appends(request()->query())->links()}} </center> 
         
      </table>
   </div>
   </div>

   @endsection   
   
