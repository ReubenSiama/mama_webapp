@extends('layouts.app')
@section('content')

 

<div class="container" >
        <div class="row">
            <div class="col-md-12 col-md-offset-0">
                <div class="panel panel-default">
                    <div class="panel-heading"><center><b>Sales Reports</b></center></div>
                     <br><br>
                     <form  action="{{ URL::to('/') }}/sal_reports" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }} 
                        <center>
                          <br>
                      <div class="col-md-2">
                          <label>Select Category </label> :
                        <select name="category"  class="form-control input-sm">
                              <option  value="">--Select--</option>
                              <option value="all">All</option>
                              @foreach($cats as $cats)
                              <option value="{{$cats->category_name}}" >{{$cats->category_name}}</option>
                              @endforeach
                        </select>
                      </div>
                       
                      <div class="col-md-2">
                        <label>Order Inititor </label> :
                      <select name="user_id"  class="form-control input-sm">
                            <option>--Select--</option>
                            <option value="all">All</option>
                            @foreach($users as $user)
                              <option value="{{$user->id}}" >{{$user->name}}</option>
                              @endforeach
                      </select>
                      </div>
                        
                      <div class="col-md-2">
                        <label>Date of Invoice From </label> :
                      <input type="date" class="form-control" name="from" >
                      </div>
                      <div class="col-md-2">
                        <label>Date of Invoice To</label> :
                        <input type="date" class="form-control" name="to" >
                      </div>
                        <br>
                        <br>

                        <input type="submit"  style="background-color:green;color:Black;font-weight:bold"> 
                       
                       <br>
                       <br>
                      </form>    
                  
                    </center>
                </div>
                </div>
                
            </div> <br><br>
        </div>

            <div class="container">
                <nav>
                    <div class="container-fluid">
                    
                      <ul class="nav nav-pills">
                          <li class="active"><a data-toggle="pill" href="#menu2">Sales & Orders</a></li>
                          {{--  <li><a data-toggle="pill" href="#menu1">Profit</a></li>   --}}
                     
                        </ul>
                      {{--  <right> <button class="btn btn-danger navbar-btn">Button</button></right>  --}}
                    </div>
                  </nav>
                  <div class="container" align="right">
                 <h2> Total Count : {{$count_invoice}}</h2> 
                 
                 <h2> Total Value : {{number_format($invoice_data_total)}} </h2>
                  </div>
                </div>

               

           
             <div id="sales_orders" class="tab-pane fade in active">
            <div class="container" >
                    <div class="row">
                    <div class="col-md-6 col-md-offset-0">
                    <h2><center>Business Generated</center> </h2> 
                    <br>  
                    <table class="table" border=1>
                      <thead>
                        <tr>
                          <th>Category</th>
                          <th>Order No</th>
                          <th>Invoice No</th>
                          <th>Invoice Value</th>
                        </tr>
                      </thead>
                      <tbody>
                            @foreach($invoice_data as $invoice_data)
                        <tr>
                                <td>{{$invoice_data->category}}</td>
                                <td>{{$invoice_data->order_id}}</td>
                                <td><a href="{{URL::to('/')}}/downloadTaxInvoice?invoiceno={{$invoice_data->invoiceno}}">{{$invoice_data->invoiceno}}</a></td>
                                <td>{{number_format($invoice_data->mhInvoiceamount)}}</td>
                        </tr> @endforeach
                        
                        
                      </tbody>
                    </table>
            {{--  </div>
                <div class="col-md-6 col-md-offset-0">
                    <h2><center>Orders Placed <center></h2>
                    <br>
                    <table class="table" border=1>
                      <thead>
                        <tr>
                            <th>Category</th>
                            <th>Order No</th>
                            <th>Order Value</th>
                        </tr>
                      </thead>
                      <tbody>
                            @foreach($order_data as $order_data)
                            <tr>
                            <td>{{$order_data->main_category}}</td>
                            <td>{{$order_data->id}}</td>
                            <td>{{number_format($order_data->order_value)}}</td>
                            </tr>
                            @endforeach
                        <tr>   
                            <td></td>
                            <td><b>Total Value<b></td>
                            <td><b></b></td>
                            </tr>
                      </tbody>
                    </table>
                  </div>
                </div>  --}}
           </div>
        
            </div>

            </div>

@endsection
