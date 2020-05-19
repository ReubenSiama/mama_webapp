@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-primary">
            <div class="panel-heading">
               <div class="col-md-4">Plot Details</div>
               <div class="pull-center col-md-4"><center>Plot Id </center></div>
               <div class="pull-right col-md-4">

               </div>
              <br>
            </div>
            <div class="panel-body">
                <table class="table table-responsive table-striped table-hover">
                    <tbody>
                        <tr>
                            <td style="width:40%"><b>Listed On : </b></td>
                            @foreach($plot_details as $details)
                             <td>{{ date('d-m-Y h:i:s A',strtotime($details->created_at)) }}</td>
                             @endforeach
                        </tr>
                        <tr>
                           <td><b>Listed By : </b></td>
                            @foreach($listedby as $listed)
                           <td>{{$listed->name}}</td>
                           @endforeach
                        </tr>
                        <tr>
                            <td style="width:40%"><b>Updated On : </b></td>
                            <td>{{ date('d-m-Y h:i:s A',strtotime($details->updated_at)) }}</td>
                        </tr>
                        <tr>
                            <td><b>Project Name : </b></td>
                            @foreach($plot_details as $details)
                            <td>{{$details->project_name}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td><b>Road Name/Road No./Landmark : <b></td>
                            @foreach($plot_details as $details)
                            <td>{{$details->road_name}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td><b>Road Width : <b></td>
                                @foreach($plot_details as $details)
                                <td>{{$details->road_width}}</td>
                                @endforeach
                        </tr>
                       <tr>
                            <td><b>Address : </b></td>
                            @foreach($plot_details as $details)
                           <td> <a target="_blank" href="https://maps.google.com?q={{$details->Address != null ? $details->Address : ''}}">{{$details->Address != null ? $details->Address : ''}}</a> </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td><b>Project Type	 :</b></td>
                            @foreach($plot_details as $details)
                            <td>{{$details->project_type}}</td>
                            @endforeach
                        </tr>
                      
                        <tr>
                            <td><b>Interested In Bank Loans ? :</b></td>
                            @foreach($plot_details as $details)
                            <td>{{$details->interested_in_bank_loans}}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <td><b>Interested In JV(Joint Venture)? ? :</b></td>
                            @foreach($plot_details as $details)
                            <td>{{$details->interested_in_jv}}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <td><b>Architects Are Required? :</b></td>
                            @foreach($plot_details as $details)
                            <td>{{$details->architects_required}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td style="width:40%"><b>Sub-ward : </b></td>
                            @foreach($sub_ward as $ward)
                            <td>{{$ward->sub_ward_name}}</td>
                            @endforeach
                           
                        </tr>
                       
                        <tr>
                            <td><b>Plot Size : </b></td>
                            @foreach($plot_details as $details)
                            <td>{{$details->total_plot_size}}</td>
                            @endforeach
                        </tr>
                      
                        <tr>
                                <td style="width:40%;"><b>Budget  :</b></td>
                                @foreach($plot_details as $details)
                                <td>{{$details->budget}}</td>
                                @endforeach
                        </tr>
                      
                        <tr>
                            <td><b>Quality :</b></td>
                            @foreach($plot_details as $details)
                            <td>{{$details->quality}}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td><b>Remarks : </b></td>
                            @foreach($plot_details as $details)
                            <td>{{$details->remarks}}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="col-md-12">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default" style="border-color:green">
            <div class="panel-heading" style="background-color:green">
               <b style="color:white">Owner Details</b> 
            </div>
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                        <th>Owner Name</th>
                        <th>Owner Contact</th>
                        <th>Owner Email</th>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($plot_customers as $plot_customerses)
                           <td>{{$plot_customerses->plot_owner_name}}</td>
                           <td>{{$plot_customerses->owner_contact_no}}</td>
                           <td>{{$plot_customerses->owner_email}}</td>
                           @endforeach
                        </tr>
                    </tbody>
                </table>        
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default" style="border-color:green">
            <div class="panel-heading" style="background-color:green">
               <b style="color:white">Builder Details</b> 
            </div>
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                        <th>Builder Name</th>
                        <th>Builder Contact</th>
                        <th>Builder Email</th>
                    </thead>
                    <tbody>
                        <tr>
                        @foreach($plot_customers as $plot_customerses)
                        <td>{{$plot_customerses->builder_name}}</td>
                        <td>{{$plot_customerses->builder_contact_no}}</td>
                        <td>{{$plot_customerses->builder_email}}</td>
                        @endforeach
                        </tr>
                    </tbody>
                </table>        
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
     background-color: #00acd6 

});
</script>

 
@endsection
