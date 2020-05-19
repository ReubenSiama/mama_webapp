
@extends('layouts.app')
@section('content')   
<span class="pull-right"> @include('flash-message')</span>
  
    <div class="col-md-8 col-md-offset-2" >
    <div class="panel panel-default" style="overflow: scroll;">
            <div class="panel-heading" style="background-color:#158942;color:white;font-size:1.4em;"> <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myModal">Add Today bank Transactions</button>
         <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-7px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color: black;"></i></button>
              <form action="{{URL::to('/')}}/testbank" method="POST" id="form">
                   <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 style="color:black;" class="modal-title">Transactions Details</h4>
        </div>
        <div class="modal-body">
             {{csrf_field()}}
             <table class="table table-hover table-striped"> 
               <tr>
                <td>Customer Number</td>
                <td>:</td>
                <td><input type="Number" name="mobile" class="form-control">
                </td>
              </tr>
              <tr>
                <td>Invoice Number</td>
                <td>:</td>
                <td><input type="text" name="invoice" class="form-control">
                </td>
              </tr>
              <tr>
                <td>Date</td>
                <td>:</td>
                <td><input type="date" name="date" class="form-control">
                </td>
              </tr>
              <tr>
                <td>Amount</td>
                <td>:</td>
                <td><input type="Number" name="amount" class="form-control">
                </td>
              </tr>
              <tr>
                <td>Debit/Credit </td>
                <td>:</td>
                <td><label >
                  <input required value="Debit" id="rmc" type="radio" name="drcr"><span>&nbsp;</span>Debit</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
               <label ><input required value="Credit" id="rmc2" type="radio" name="drcr"><span>&nbsp;</span>Credit</label> 
                </td>
              </tr>
                <tr>
                <td>Description</td>
                <td>:</td>
                <td><textarea  name="desc" class="form-control"></textarea>
              </tr>
             </table>
            
         <center><button class="btn btn-sm btn-warning" type="submit" onclick="document.getElementById('form').submit()">Submit</button></center>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
       </form>

            </div>  
         <div class="panel-body" id="page">
       <table class="table table-hover table-striped" border="1">
                <thead>
                  <th>Slno</th>
                  <th>Name</th>
                  <th>Date</th>
                  <th>Invoice Number/description</th>
                 <th>Debit Amount</th>
                 <th>Credit Amount</th>
                 <th>Remark</th>
               </thead>
                <tbody>
             <?php $i =1; ?>
            @foreach($projects as $project)
                <tr>
                  <td>{{$i++}}</td>
                    <td id="projname-{{$project->id}}"><?php $name = App\CustomerDetails::where('mobile_num',$project->number)->pluck('first_name')->first(); ?> {{$name}} <br> {{ $project->number }}</td>
                     <td id="projname-{{$project->id}}">{{ date('d-m-y',strtotime($project->date)) }}</td>
                    <td id="projname-{{$project->id}}">{{ $project->invoiceno }}</td>
                     <td  style="text-align:center">
                        @if($project->debit != NULL)
                            {{$project->debit}}
                        @else 
                          -
                       @endif      
                    </td>
                  
                    
                   <td>
                       @if($project->credit != NULL)
                            {{$project->credit}}
                        @else 
                          -
                       @endif  
                    
                   </td>
                    <td id="projproc-{{$project->id}}">
                                        {{ $project->remark}}
                                    
                    </td>

                  </tr>
@endforeach

</tbody>
</table>


</div>
        
  </div>
 </div>
  
  @endsection
