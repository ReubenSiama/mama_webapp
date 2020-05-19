@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="border-color:#f4811f">
                <div class="panel-heading text-center" style="background-color:#f4811f;padding:10px;">
                    <span style="color:white;font-weight:bold;">{{$name}} Ledger Account </span>
                    @if(session('Error'))
                        <div class="alert-danger pull-right">{{ session('Error')}}</div>
                    @endif
                     <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color:black;"></i></button>
                </div>
                <div class="panel-body">  
               <form action="{{URL::to('/')}}/customerledger" method="get">
               	<div class="row">
                  <div class="col-md-3">
                  	<label>
                  		Phone Number
                  	<input type="number" name="Number" class="form-form-control" required>
                  	</label>
                  </div> 
                  <div class="col-md-2">
                  	<label>
                  		Date From
                  	<input type="date" name="from" class="form-control" required>
                  	</label>
                  </div> 
               	<div class="col-md-2">
                  	<label>
                  		Date To
                  	<input type="date" name="to" class="form-control " required>
                  	</label>
                  </div> 
               	<div class="col-md-2">
                     <button class="btn btn-lg btn-primary">Fetch</button>
                  </div> 
               </div>          	
               </form>

      <div class="container">
              <div class="pull-right" style="text-align:right;font-weight:bold; 
                    width: 96%;
                    border: 4px solid black;
                    padding:4px;
                    margin-right:40px;">
                 
                   <img src="http://mamahome360.com/img/logo.png" /><br>
                    <h4 style="font-weight:bold;">NO.363, 19TH MAIN ROAD 1ST BLOCK <br>
                      RAJAJINAGAR BANGALORE-560010</h4>
                    <h4 style="font-weight:bold;">PH-8548888940/41/42/43</h4>   
    
               <div  style="text-align:center;font-weight:bold; 
                    width: 100%;
                    background-color:#cccccc;
                    border:2px solid black;
                    padding:1px;
                    margin-right:40px;">
                    <h4 style="font-weight:bold;">  Ledger Account From {{$from}} to {{$to}}</h4>   
    
              </div> <br><br>

              <div  style="text-align:center;font-weight:bold; 
                    width:70%;
                   
                    border:5px solid black;
                    padding:5px;
                    margin-right:40px;margin-left:20%;">
                    <h4 style="font-weight:bold;text-align:center;">{{$name}}<br><?php  $id = App\CustomerDetails::where('mobile_num',$number)->pluck('customer_id')->first(); ?>ID : {{$id}}<br>Number : {{$number}}</h4>  
                    <h5 style="font-weight:bold;text-align:center;"> 84/1, Banjarpalya Agara grama,kengeri (Hobli)<br>
                              Bangalore 560082 
                  </h5>

                  

              </div><br>
          <table class="table table-responsive table-striped" border="1">

                      <thead style="background-color:#cccccc; ">
                        <th style="font-weight:bold;">SlNO</th>
                        <th style="font-weight:bold;">Invoice Number/Description</th>
                        <th style="font-weight:bold;">Date</th>

                        <th style="font-weight:bold;"> Debit Amount</th>
                        <th style="font-weight:bold;">Credit Amount</th>
                        <th style="font-weight:bold;">Balance</th>
                      </thead>
                    <tbody>
                      <?php $i=1; ?>
                      @foreach($invoice as $inc)
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$inc->invoiceno}}<br>{{$inc->remark}}</td>
                        <td>{{$inc->date }}</td>
                       <td  style="text-align:center">
                           {{$inc->debit}}
                            
                    </td>
                    
                   <td>
                           {{$inc->credit}}
                       
                   </td>
                        <td>
                          {{$inc->bal}}
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
               </table><br><br>

               <div  style="text-align:center;font-weight:bold; 
                    width: 100%;
                    background-color:#cccccc;
                    border:2px solid black;
                    padding:1px;
                    margin-right:40px;">
                    
                      @if($bal < 0 )
                    <h4 style="font-weight:bold;">
                     

s

                     Outstanding Amount as on {{$bal}} .DR
                             
                   </h4>  
                   @elseif($bal > 0)
                    <h4 style="font-weight:bold;">
                     
                   


                     Outstanding Amount as on {{$bal}} .CR
                             
                   </h4> 
                   @else
                     <h4 style="font-weight:bold;">
                     
                   


                     Outstanding Amount as on 
                             
                   </h4> 
                   @endif

                  
    
              </div>
                <center> <a href="{{URL::to('/')}}/downloadcustomerledger?from={{$from}}&&to={{$to}}&&Number={{$number}}" class="btn btn-sm btn-warning">Download</a></center>
              </div>

           
        
      </div>

 </div>
 </div>
 </div>
 </div>
 </div>







@endsection
