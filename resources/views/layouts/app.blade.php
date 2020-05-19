﻿<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Creative Button Styles  - Modern and subtle styles &amp; effects for buttons" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MamaHome</title>
    <!-- Styles -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
   
    <meta name="description" content="Sticky Notes by Edmond Ko">
    <meta name="author" content="Edmond Ko">
    <link href='https://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <script type="text/javascript" src="{{asset('js/gmaps.js')}}"></script>
    <script src="{{ URL::to('/') }}/js/jscolor.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js'></script>

    <!-- <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css" /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/some.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/appblade.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/app.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="{{ URL::to('/') }}/css/countdown.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <script src="https://unpkg.com/sweetalert2@7.17.0/dist/sweetalert2.all.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
   <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
  window.console = window.console || function(t) {};  
</script>
  <script>
  if (document.location.search.match(/type=embed/gi)) {
    window.parent.postMessage("resize", "*");
  }
</script>

</head>
<body>
    <div id="app">
      
      
                    @if(Auth::check())  
                   
        <nav class="navbar navbar-default navbar-static-top">
       
            <div class="container">
            
                <div class="navbar-header">
               
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                      
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                  
                    <!-- Branding Image -->
                    <!-- <a class="navbar-brand" href="{{ url('/') }}">
                        <img style="height: 25px; width: 170px;" src="https://www.mamahome360.com/webapp/MAMA-HOME-LOGO.png">
                    </a> -->
                   
                    @if(Auth::user()->group_id == 1)
                  
                        <a href="#" class="navbar-brand" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</a>
                    @elseif(Auth::user()->group_id == 2 && Auth::user()->department_id == 1)
                        <a href="#" class="navbar-brand" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</a>
                @elseif(Auth::user()->group_id == 4 && Auth::user()->department_id == 1)
                        <a href="#" class="navbar-brand" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</a>
                    @elseif(Auth::user()->group_id == 17 && Auth::user()->department_id == 2)
                        <a href="#" class="navbar-brand" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</a>
                    @elseif(Auth::user()->group_id == 8 && Auth::user()->department_id == 3)
                        <a href="#" class="navbar-brand" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</a>
                     @elseif(Auth::user()->group_id == 7 && Auth::user()->department_id == 2)
                        <a href="#" class="navbar-brand" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</a>
                     @elseif(Auth::user()->group_id == 14)
                        <a href="#" class="navbar-brand" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</a>
                     @elseif(Auth::user()->group_id == 23)
                        <a href="#" class="navbar-brand" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</a>
                          @elseif(Auth::user()->group_id == 4 && Auth::user()->department_id == 1)
                        <a href="#" class="navbar-brand" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</a>
                    @endif
                    @endif
                    
                </div>
                           
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    
                    <ul class="nav navbar-nav">
                        @if(Auth::check())
                      
                        <li><a href="{{ URL::to('/') }}/home" style="font-size:1.1em"><b>Home</b></a></li>
                        
                        <!--  -->
                        @if(Auth::user()->department_id == 2  && Auth::user()->group_id == 7)
                         <li><a href="{{ URL::to('/') }}/eqpipeline" style="font-size:1.1em;font-family:Times New Roman"><b>Enquiry Pipelined</b></a></li>
                        @endif
                        @if(Auth::user()->department_id == 1  && Auth::user()->group_id == 6)
                         <li><a href="{{ URL::to('/') }}/eqpipeline" style="font-size:1.1em"><b>Enquiry Pipelined</b></a></li>
                        @endif
                       <!--  @if(Auth::user()->department_id == 0  && Auth::user()->group_id == 1)
                         <li><a href="{{ URL::to('/') }}/eqpipeline" style="font-size:1.1em"><b>Enquiry Pipelined</b></a></li>
                        @endif -->
                         @if(Auth::user()->department_id == 2  && Auth::user()->group_id == 17)
                         <li><a href="{{ URL::to('/') }}/eqpipeline" style="font-size:1.1em"><b>Enquiry Pipelined</b></a></li>
                        @endif
                          @if(Auth::user()->department_id == 1  && Auth::user()->group_id == 4)
                         <li><a href="{{ URL::to('/') }}/eqpipeline" style="font-size:1.1em"><b>Enquiry Pipelined</b></a></li>
                        @endif
                        @if(Auth::user()->department_id == 2  && Auth::user()->group_id == 23)
                         <li><a href="{{ URL::to('/') }}/eqpipeline" style="font-size:1.1em"><b>Enquiry Pipelined</b></a></li>
                        @endif
                        <?php $d =0 ?>
                         @if(Auth::user()->group_id == 14)
                        <li><a href="{{ URL::to('/') }}/adtraining" style="font-size:1.1em"><b>Training Video <span class="badge">&nbsp;&nbsp;</span></b></a></li>
                        @endif
                        @if(Auth::user()->department_id == 2  && Auth::user()->group_id == 7)
                          <li><a href="{{ URL::to('/') }}/setraining" style="font-size:1.1em"><b>Training Video <span class="badge">&nbsp;{{ $d }}&nbsp;</span></b></a></li>
                        @endif
                       
                        @if(Auth::user()->department_id == 1  && Auth::user()->group_id == 2)
                          <li><a href="{{ URL::to('/') }}/tltraining" style="font-size:1.1em"><b>Training Video <span class="badge">&nbsp;{{ $d }}&nbsp;</span></b></a></li>
                        @endif
                         @if(Auth::user()->department_id == 1  && Auth::user()->group_id == 17)
                          <li><a href="{{ URL::to('/') }}/asttraining" style="font-size:1.1em"><b>Training Video <span class="badge">&nbsp;{{ $d }}&nbsp;</span></b></a></li>
                        @endif

                         @if(Auth::user()->department_id == 1  && Auth::user()->group_id == 4)
                          <li><a href="{{ URL::to('/') }}/asttraining" style="font-size:1.1em"><b>Training Video <span class="badge">&nbsp;{{ $d }}&nbsp;</span></b></a></li>
                        @endif

                        @if(Auth::user()->department_id == 0  && Auth::user()->group_id == 1)
                          <li><a href="{{ URL::to('/') }}/home" style="font-size:1.1em"><b>Reminder  <span class="badge">&nbsp;{{ $d }}&nbsp;</span></b></a></li>
                        @endif 
                        @if(Auth::user()->department_id == 0  && Auth::user()->group_id == 1)
                          <li><a href="{{ URL::to('/') }}/adtraining" style="font-size:1.1em"><b>Training Video <span class="badge">&nbsp;{{ $d }}&nbsp;</span></b></a></li>
                        @endif
                       
                         @if(Auth::user()->department_id == 2  && Auth::user()->group_id == 17)
                          <li><a href="{{ URL::to('/') }}/sctraining" style="font-size:1.1em"><b>Training Video <span class="badge">&nbsp;{{ $d }}&nbsp;</span></b></a></li>
                        @endif

                        <li style="padding-top: 10px;padding-left: 10px;">
                       
                          <button id="appblade" class="btn btn-success btn-sm hidden-xs hidden-sm" onclick="submitapp()"> Attendance Login</button>
                        </li>
                       <li style="padding-top: 10px;padding-left: 10px;"> 
                        <button class="btn btn-primary btn-sm  hidden-xs hidden-sm" data-toggle="modal" data-target="#break">Break</button>
                       </li>
                        <li style="padding-top: 10px;padding-left: 10px;"> 
                        <button class="btn btn-danger btn-sm hidden-xs hidden-sm" data-toggle="modal" onclick="confirmthis()">Attendance Logout</button>
                       </li>
                     
                        @endif

                    </ul>
                
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <!-- <li><a href="{{ route('login') }}">Login</a></li> -->
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a href="{{ URL::to('/') }}/profile ">Profile</a></li>
                                    @if(Auth::user()->department_id == 2 && Auth::user()->group_id == 7)
                                    <li><a href="{{ URL::to('/') }}/salescompleted ">Completed</a></li>
                                    @endif
                                    @if(Auth::user()->department_id == 0 && Auth::user()->group_id == 1)
                                    <li><a href="{{ URL::to('/') }}/admincompleted?id={{ Auth::user()->id }}">Completed</a></li>
                                    @endif
                                    
                                    <li><a href="{{ URL::to('/')}}/changePassword">Change Password</a></li>
                                    
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('authlogout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
         @if(Auth::check())
                                    <!-- Modal -->
                            <div id="break" class="modal fade" role="dialog">
                              <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content" style="width:50%;" >
                                  <div class="modal-header" style="background-color:#F2B33F">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Break Time</h4>
                                  </div>
                                  <div class="modal-body">

                                    <?php
                                        $check = App\BreakTime::where('user_id',Auth::user()->id)->where('date',date('Y-m-d'))->where('stop_time',"")->count();
                                
                                            if($check == 1){
                                                     $btn = "stop";
                                            }
                                            else{
                                                    $btn = "start";
                                          }
                                    ?>
                                  @if($btn == "start")
                                    <p>Click On Start To Take a Break</p>
                                  <form id="timer" action="{{ URL::to('/') }}/breaktime" method="POST">
                                      {{ csrf_field() }}
                                    <button type="submit" class="btn btn-success btn-sm">START</button>
                                  </form>
                                  @else
                                  <p>Click On Stop To End The Break</p>
                                  <form id="timer" action="{{ URL::to('/') }}/sbreaktime" method="POST">
                                      {{ csrf_field() }}
                                    <button  type="submit" class="btn btn-danger btn-sm">STOP</button>
                                  </form>
                                  @endif
                                    <label id="currentTime" class="alert-success"></label>
                                  
                                  </div>
                                  <div class="modal-footer">
                                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                   
                                  </div>
                                </div>

                              </div>
                            </div>
                            <!-- mpdal end -->
              @endif
                            <!-- Modal -->
                            <div id="report" class="modal fade" role="dialog">
                              <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                  <div class="modal-header" style="background-color:rgb(244, 129, 31);color:white;">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">MAMAHOME EMPLOYEE ATTENDANCE</h4>
                                  </div>
                                  <div class="modal-body">
                                  <form action="{{ URL::to('/') }}/empreports" method="POST">
                                      {{ csrf_field() }}
                                      
                                                  <table class="table table-hover" id="reports">
                                                      <thead>
                                                          <th>Report</th>
                                                          <th>From</th>
                                                          <th>To</th>
                                                      </thead>
                                                      <tbody>
                                                          
                                                          <tr>
                                                              <td><input required type="text" name="report[]" id="report" class="form-control" placeholder="Report"></td>
                                                              <td><input required type="time" name="from[]" id="from" class="form-control"></td>
                                                              <td><input required type="time" name="to[]" id="to" class="form-control"></td>
                                                          </tr>
                                                      </tbody>
                                                  </table>
                                                  <div class="btn-group">
                                                      <button type="button" onclick="myFunction1()" class="btn btn-warning btn-sm">
                                                          &nbsp; <span class="glyphicon glyphicon-plus"></span>&nbsp;
                                                      </button>
                                                      <button type="button" onclick="myDelete1()" class="btn btn-danger btn-sm">
                                                          &nbsp; <span class="glyphicon glyphicon-minus"></span>&nbsp;
                                                      </button>
                                                  </div>
                                             
                                              <div class="panel-footer">
                                                  <input type="submit" value="Submit" class="form-control btn btn-success">
                                              </div>
                                  </form>
                                  </div>
                                </div>

                              </div>
                            </div>
                            <!-- mpdal end -->
@if(Auth::check()) 
@if(Auth::user()->group_id == 1)
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" onclick="closeNav()">&times;</a>
    <a href="#" data-toggle="collapse" data-target="#demo999">HR and Admin &#x21F2;</a>
<div id="demo999" class="collapse">
  <a href="{{ URL::to('/') }}/mhemployee">&nbsp;&nbsp;&nbsp; -  Employee Details</a>
     <a href="{{ URL::to('/') }}/assets">&nbsp;&nbsp;&nbsp; - Asset Management</a>    
    <a href="{{ URL::to('/') }}/holidays">&nbsp;&nbsp;&nbsp; - Holiday List</a> 
    <a href="{{ URL::to('/minibreack') }}">&nbsp;&nbsp;&nbsp; - Break Time Mini Report</a>
    <a href="{{ URL::to('/') }}/breaks">&nbsp;&nbsp;&nbsp; - BreakTime</a>
 <a href="{{ URL::to('/anr') }}">&nbsp;&nbsp;&nbsp; - Employee Reports</a>
 <a href="{{ URL::to('/') }}/amhumanresources">&nbsp;&nbsp;&nbsp; - Add And Remove Employees</a>
   <a href="{{ URL::to('/') }}/video">&nbsp;&nbsp;&nbsp; -  HR Training Videos</a>
        <a href="{{ URL::to('/') }}/adminlatelogin">&nbsp;&nbsp;&nbsp; - Late Logins</a>
</div>
<a href="#" data-toggle="collapse" data-target="#demo999111">Important Documents &#x21F2;</a>
<div id="demo999111" class="collapse">
  
        <a href="{{ URL::to('/') }}/check">&nbsp;&nbsp;&nbsp;Company Imaportant Documents Copy
</a>
</div>
<a href="#" data-toggle="collapse" data-target="#demo1">Operation &#x21F2;</a>
<div id="demo1" class="collapse">
     <a href="{{ URL::to('/') }}/mapping">&nbsp;&nbsp;&nbsp; - Zonal Maps </a>
    <a href="{{ URL::to('/') }}/teamlisteng">&nbsp;&nbsp;&nbsp; - Employee Tracking</a>
        <a href="#" data-toggle="collapse" data-target="#demo4224">Plot And Villas &#x21F2;</a>
<div id="demo4224" class="collapse">
 <a href="{{ URL::to('/') }}/plots_dailyslots">&nbsp;&nbsp;&nbsp; - Listed Plots Date Wise</a>
</div>
<a href="#" data-toggle="collapse" data-target="#demo44">Projects &#x21F2;</a>
<div id="demo44" class="collapse">
 <a href="{{ URL::to('/') }}/dailyslots">&nbsp;&nbsp;&nbsp; - Listed Projects Date wise</a>
 <a href="{{URL::to('/getprojectsize') }}">&nbsp;&nbsp;&nbsp; - Listed Project In Stage Wise Count </a> 
 <a href="{{ URL::to('/quality') }}">&nbsp;&nbsp;&nbsp; - Quality of Projects</a>
 <a href="{{ URL::to('/viewallProjects') }}">&nbsp;&nbsp;&nbsp; - Projects Search</a>
 <a href="{{ URL::to('/blocked_projects') }}">&nbsp;&nbsp;&nbsp; - Blocked Projects</a>
  <a href="{{ URL::to('/') }}/Unupdated">&nbsp;&nbsp;&nbsp; - Updated Projects</a>
  <a href="{{ URL::to('/') }}/dateUnupdated">&nbsp;&nbsp;&nbsp; - UnUpdated Projects</a>

<a href="{{ URL::to('/getprojectsizedata') }}">&nbsp;&nbsp;&nbsp; - Listed Projects Based On Sizes</a>

 <a href="{{ URL::to('/newActivityLog') }}">&nbsp;&nbsp;&nbsp; - Projects Updated Report</a>
</div>

<a href="#" data-toggle="collapse" data-target="#demo55">Manufacturers &#x21F2;</a>
<div id="demo55" class="collapse">
 <a href="{{ URL::to('/') }}/manudailyslot">&nbsp;&nbsp;&nbsp; - Listed Manufacturers Date wise</a>
 <a href="{{URL::to('/viewManufacturer') }}">&nbsp;&nbsp;&nbsp;- Listed Manufacturers Details </a>
 <a href="{{ URL::to('/manureport') }}">&nbsp;&nbsp;&nbsp; - Manufacturers ward wise Report</a>
 <a href="{{ URL::to('/manuupdate') }}">&nbsp;&nbsp;&nbsp; - Manufacturer Updated Report</a>
<a href="{{ URL::to('/') }}/unupdatedmanu">&nbsp;&nbsp;&nbsp; -UnUpdated Manufacturers</a>
<a href="{{ URL::to('/blocked_manu') }}">&nbsp;&nbsp;&nbsp; -Blocked Manufacturer</a>
         


</div>

<a href="#" data-toggle="collapse" data-target="#add9">Add Projects And Manufacturers &#x21F2;</a>
    <div id="add9" class="collapse">
      <a  href="{{ URL::to('/')}}/plots">&nbsp;&nbsp;&nbsp; - Add New Plot Or Villas</a>
      <a  href="{{ URL::to('/')}}/listingEngineer">&nbsp;&nbsp;&nbsp; - Add New Project</a>
      <a  href="{{ URL::to('/')}}/addManufacturer">&nbsp;&nbsp;&nbsp; - Add New Manufacturer</a>
      <a  href="{{ URL::to('/')}}/inputview">&nbsp;&nbsp;&nbsp; - Add New Project Enquiry</a>
    <a href="{{ URL::to('/') }}/manuenquiry">&nbsp;&nbsp;&nbsp; - Add New Manufacturer Enquiry</a>


    </div>

   
        
</div>

<a href="#" data-toggle="collapse" data-target="#demo88">Sales & Marketing &#x21F2;</a>
<div id="demo88" class="collapse">
        <a href="{{URL::to('/targetrem') }}">&nbsp;&nbsp;&nbsp; -Set Target Reminder</a>
 <a href="{{ URL::to('/monthlyinvoicedata') }}">&nbsp;&nbsp;&nbsp; - Sales Report</a>
  <a href="{{ URL::to('/orders') }}">&nbsp;&nbsp;&nbsp; - Orders</a>
  <a href="{{ URL::to('/financeDashboard') }}">&nbsp;&nbsp;&nbsp; - Confirmed Orders</a>
  <a href="{{ URL::to('/pendingorders') }}">&nbsp;&nbsp;&nbsp; -Approvel Pending Orders</a>
  <a href="{{ URL::to('/') }}/cash">&nbsp;&nbsp;&nbsp; - Generate Cash Receipt</a>
  <a href="{{ URL::to('/getquotation') }}">&nbsp;&nbsp;&nbsp; - Get Quotation</a>
  <a href="{{ URL::to('/allprice') }}">&nbsp;&nbsp;&nbsp; - Products Prices</a>
  <a href="{{ URL::to('/tlsalesreports') }}">&nbsp;&nbsp;&nbsp; - Sales Engineer Report</a>
  <a href="{{ URL::to('/') }}/tlenquirysheet">&nbsp;&nbsp;&nbsp; - Project Enquiry Sheet</a>
  <a href="{{ URL::to('/') }}/manuenquirysheet">&nbsp;&nbsp;&nbsp; - Manufacturer Enquiry Sheet</a>
  <a href="{{ URL::to('/')}}/enquiryCancell?project=project">&nbsp;&nbsp;&nbsp; - Project Enquiry cancelled</a>
  <a href="{{ URL::to('/')}}/enquiryCancell?project=manu">&nbsp;&nbsp;&nbsp; - Manufacturer Enquiry cancelled</a>

</div>
<a href="#" data-toggle="collapse" data-target="#demo100">Finance &#x21F2;</a>
<div id="demo100" class="collapse">
  <a href="{{ URL::to('/ledger') }}">&nbsp;&nbsp;&nbsp; - Ledger Sheet</a> 
  <a href="{{ URL::to('/gstinformation') }}">&nbsp;&nbsp;&nbsp; - GST Report</a>
 

</div>

<a href="#" data-toggle="collapse" data-target="#B">Business Intelligence  &#x21F2;</a>
<div id="B" class="collapse">
  <a href="#" data-toggle="collapse" data-target="#planning33">&nbsp;&nbsp;&nbsp; Sales Projection & Target &#x21F2;</a>
        <div id="planning33" class="collapse">
            <a href="{{ URL::to('/projection') }}">&nbsp;&nbsp;&nbsp; - Monthly Sales Projection</a>
            <a href="{{ URL::to('/stage') }}">&nbsp;&nbsp;&nbsp; - Monthly Sales Target</a>
            <a href="{{ URL::to('/yearly') }}">&nbsp;&nbsp;&nbsp; - Yearly Sales Projection</a>
            <a href="{{ URL::to('/fiveyears') }}">&nbsp;&nbsp;&nbsp; - Five Years Sales Projection</a>
          <!-- <a href="{{ URL::to('/fiveyearsWithZones') }}">&nbsp;&nbsp;&nbsp; - Five Years Sales Projection With Zone</a> -->
          <!-- <a href="{{ URL::to('/countryProjection') }}">&nbsp;&nbsp;&nbsp; - One Year India Country Projection</a> -->
            <a href="{{ URL::to('/daily') }}">&nbsp;&nbsp;&nbsp; - Daily Sales Target</a>
            <a href="{{ URL::to('/extensionPlanner') }}">&nbsp;&nbsp;&nbsp; - Extension Planner</a>
            <!-- <a href="{{ URL::to('/bulkBusiness') }}">&nbsp;&nbsp;&nbsp; - Bulk Business</a> -->
        </div>
        <a href="#" data-toggle="collapse" data-target="#Expenditure1">&nbsp;&nbsp;&nbsp;Expenditure &#x21F2;</a>
        <div id="Expenditure1" class="collapse">
            <a href="{{ URL::to('/expenditure') }}">&nbsp;&nbsp;&nbsp; - Expenditure</a>
            <a href="{{ URL::to('/five_years_expenditure') }}">&nbsp;&nbsp;&nbsp; - Five Years Expenditure</a>
        </div>

</div>

<a href="#" data-toggle="collapse" data-target="#supply">Supplier Management  &#x21F2;</a>
<div id="supply" class="collapse">
<a href="#" data-toggle="collapse" data-target="#direct">&nbsp;&nbsp;&nbsp; - Direct Aligned Partners &#x21F2;</a>
    <div id="direct" class="collapse">
    <a href="{{ URL::to('/manufacturerdetails') }}">&nbsp;&nbsp;&nbsp; - Suppliers</a>
    <a href="{{ URL::to('/lebrands') }}">&nbsp;&nbsp;&nbsp; - Brands</a>
</div>
<a href="{{ URL::to('/') }}/pendingvendor">&nbsp;&nbsp;&nbsp; - Pending Vendors</a>


</div>
 <a href="#" data-toggle="collapse" data-target="#demo909">MamaHome Customers &#x21F2;</a>
<div id="demo909" class="collapse">
  <a href="{{ URL::to('/') }}/customermap">&nbsp;&nbsp;&nbsp; - Listed Projects, Manufacturers & Customer Details</a>
        <a href="{{URL::to('/assignvistedcustomer') }}">&nbsp;&nbsp;&nbsp; -Visited Customers Report</a>
        <a href="{{URL::to('/customerbank') }}">&nbsp;&nbsp;&nbsp; -Customer Transactions Details </a>
        <a href="{{URL::to('/customerledger') }}">&nbsp;&nbsp;&nbsp; -Customer Ledger</a>
           <a href="{{ URL::to('/getcustomerinvoices') }}">&nbsp;&nbsp;&nbsp; -Customer Invoice Details</a>
        <a href="{{URL::to('/assigncustomers') }}">&nbsp;&nbsp;&nbsp; -Assign Customers To Visit</a>

       




     
</div>
 <a href="#" data-toggle="collapse" data-target="#demo9092">Dedicated Customers History &#x21F2;</a>
<div id="demo9092" class="collapse">
  <a href="{{ URL::to('/') }}/newcustomerassign">&nbsp;&nbsp;&nbsp; - Assign Dedicated Customers TO Sales Engineers</a>
        <a href="{{URL::to('/usercustomers') }}">&nbsp;&nbsp;&nbsp; -Assigned Customer Details</a>
       <!--  <a href="{{URL::to('/customerbank') }}">&nbsp;&nbsp;&nbsp; -Customer Transactions Details </a>
        <a href="{{URL::to('/customerledger') }}">&nbsp;&nbsp;&nbsp; -Customer Ledger</a>
           <a href="{{ URL::to('/getcustomerinvoices') }}">&nbsp;&nbsp;&nbsp; -Customer Invoice Details</a> -->
        
</div>
<div id="plan" class="collapse">

</div>
 <a href="#" data-toggle="collapse" data-target="#adfdssss"> Projects And Manufacturers Details with Map &#x21F2;</a>
       <div id="adfdssss" class="collapse">
              
              <a href="{{URL::to('/testdistance') }}">&nbsp;&nbsp;&nbsp; -Get Projects through map</a>
              <a href="{{URL::to('/getmanudistance') }}">&nbsp;&nbsp;&nbsp; -Get Manufacturers through map</a>
              
         
    
       </div>
<a href="#" data-toggle="collapse" data-target="#links1234"> Reports &#x21F2;</a>

<div id="links1234" class="collapse">
    <a href="{{ URL::to('/') }}/updatereport">Manufacturer Updated Reports</a>
    <a href="{{ URL::to('/') }}/usingbrand">Customer Using Brands</a>
    <a href="{{ URL::to('/') }}/totalreport">Employees Work Report</a>
    <a href="{{ URL::to('/') }}/totalcallattend">Employees Mini Work Report</a>
    <a href="{{ URL::to('/') }}/tototallog">Logistic Report</a>
    <a href="{{ URL::to('/') }}/customerstatus">Customer Status Report</a>
  


    
    </div>

    <a href="#" data-toggle="collapse" data-target="#links1234hj"> Closed Customers Data &#x21F2;</a>

<div id="links1234hj" class="collapse">
    <a href="{{ URL::to('/') }}/closedcontractor">Closed Customers</a>
    <a href="{{ URL::to('/') }}/assignedclosedcustomers">Assigned Closed Customers</a>
    
   

    
    </div>

<a href="#" data-toggle="collapse" data-target="#links1234m"> WhatsApp Reports &#x21F2;</a>

<div id="links1234m" class="collapse">
    <a href="{{ URL::to('/') }}/dedicatedwhatsapp">Dediacted customers Whatsapp</a>
    <a href="{{ URL::to('/') }}/Proposedprojectswhatsapp">Manufacturer And Projects Updated Whatsapp</a>
    
    
    
    </div>
   
<a href="#" data-toggle="collapse" data-target="#links123">Links  &#x21F2;</a>

<div id="links123" class="collapse">
    <a href="{{ URL::to('/') }}/mapping">MAMA Maps</a>
    <a href="{{ URL::to('/getprojectsize') }}">Listed Project & Sizes</a>
    <a href="{{ URL::to('/getprojectsizedata') }}">Listed ProjectData  Sizes</a>
    <a href="{{URL::to('/projectandward') }}">Project Report</a> 
    <a href="{{URL::to('/bankloan') }}">Bank Loan Interested Customers</a> 
    <a href="{{URL::to('/manureport') }}">Manufactureres Report</a>
    <a href="#" data-toggle="collapse" data-target="#planning">Sales Projection & Planning &#x21F2;</a>
        <div id="planning" class="collapse">
            <a href="{{ URL::to('/projection') }}">&nbsp;&nbsp;&nbsp; - Monthly Sales Projection</a>
            <a href="{{ URL::to('/stage') }}">&nbsp;&nbsp;&nbsp; - Monthly Sales Target</a>
            <a href="{{ URL::to('/yearly') }}">&nbsp;&nbsp;&nbsp; - Yearly Sales Projection</a>
            <a href="{{ URL::to('/fiveyears') }}">&nbsp;&nbsp;&nbsp; - Five Years Sales Projection</a>
            <a href="{{ URL::to('/fiveyearsWithZones') }}">&nbsp;&nbsp;&nbsp; - Five Years Sales Projection With Zone</a>
            <a href="{{ URL::to('/countryProjection') }}">&nbsp;&nbsp;&nbsp; - One Year India Country Projection</a>
            <a href="{{ URL::to('/daily') }}">&nbsp;&nbsp;&nbsp; - Daily Sales Target</a>
            <a href="{{ URL::to('/extensionPlanner') }}">&nbsp;&nbsp;&nbsp; - Extension Planner</a>
            <!-- <a href="{{ URL::to('/bulkBusiness') }}">&nbsp;&nbsp;&nbsp; - Bulk Business</a> -->
        </div>
        <a href="#" data-toggle="collapse" data-target="#Expenditure">Expenditure &#x21F2;</a>
        <div id="Expenditure" class="collapse">
            <a href="{{ URL::to('/expenditure') }}">&nbsp;&nbsp;&nbsp; - Expenditure</a>
            <a href="{{ URL::to('/five_years_expenditure') }}">&nbsp;&nbsp;&nbsp; - Five Years Expenditure</a>
        </div>
      
         <a href="#" data-toggle="collapse" data-target="#dailyslot">&nbsp;&nbsp;&nbsp;Daily Slots &#x21F2;</a>
          <div id="dailyslot" class="collapse">
                <a href="{{ URL::to('/dailyslots') }}">Projects Daily Slots</a>
                <a href="{{ URL::to('/manudailyslot') }}">Manufacturer Daily Slots</a>
                <a href="{{ URL::to('/plotsdailyslots') }}">Plots Daily Slots</a>

                <a href="{{ URL::to('/projectreport')}}">Today's Project Report</a>
                
                <a href="{{ URL::to('/newActivityLog') }}">Projects Updated Report</a>
                 <a href="{{ URL::to('/manuupdate') }}">Manufacturer Updated Report</a>
          </div>
    <a href="{{ URL::to('/salesreports') }}">Sales Engineer Report</a>
    <a href="#" data-toggle="collapse" data-target="#projects">Detailed Projects &#x21F2;</a>
        <div id="projects" class="collapse">
             <a href="{{ URL::to('/quality') }}">&nbsp;&nbsp;&nbsp; - Quality of Projects</a>
            <a href="{{ URL::to('/viewallProjects') }}">&nbsp;&nbsp;&nbsp; - View All Projects</a>
            <a href="{{ URL::to('/') }}/Unupdated">&nbsp;&nbsp;&nbsp; -Updated Projects</a>
            <a href="{{ URL::to('/') }}/dateUnupdated">&nbsp;&nbsp;&nbsp; - UnUpdated Projects</a>
            <a href="{{ URL::to('/allProjectsWithWards') }}">&nbsp;&nbsp;&nbsp; -Data Quality of Projects</a>
        </div>
    <a href="{{ URL::to('/') }}/marketingvendordetails">Vendor details</a>
    <a href="{{ URL::to('/ampricing') }}">Pricing</a>
    <a href="{{URL::to('/Pricetobrands') }}">Supplier And Brand Prices</a>
    <a href="{{ URL::to('/') }}/teamlisteng">Tracking</a>
    <a href="{{ URL::to('/') }}/customermap">Customer Details</a>

    <a href="{{ URL::to('/minibreack') }}">BreakTime Mini Report</a>
    <a href="{{ URL::to('/details') }}">Assign Customers</a>

    <a href="#" data-toggle="collapse" data-target="#enquiry">Enquiry &#x21F2;</a>
    <div id="enquiry" class="collapse">
            <a href="{{ URL::to('/adenquirysheet') }}">&nbsp;&nbsp;&nbsp; - Project Enquiry sheet</a>
            <a href="{{ URL::to('/') }}/manuenquirysheet">&nbsp;&nbsp;&nbsp; -Manufacturer Enquiry Sheet</a>
            <a href="{{ URL::to('/enquiryCancell') }}">&nbsp;&nbsp;&nbsp; - Enquiry cancelled</a>
             <!-- <a href="{{ URL::to('/getquotation') }}">&nbsp;&nbsp;&nbsp; - Get Quotation</a> -->
    </div>
    <a href="#" data-toggle="collapse" data-target="#orders">Orders &#x21F2;</a>
        <div id="orders" class="collapse">
            <a href="{{ URL::to('/salesStatistics') }}">&nbsp;&nbsp;&nbsp; - Sales Statistics</a>
            <a href="{{ URL::to('/orders') }}">&nbsp;&nbsp;&nbsp; - Orders</a>
            <a href="{{ URL::to('/financeDashboard') }}">&nbsp;&nbsp;&nbsp; - Confirmed Orders</a>
        </div>
    <a href="#" data-toggle="collapse" data-target="#demo">Human Resource &#x21F2;</a>
    <div id="demo" class="collapse">
    <a href="{{ URL::to('/') }}/holidays">Holiday List</a> 
    <a href="{{ URL::to('/') }}/breaks">BreakTime</a>

       <!--  <a href="#" data-toggle="collapse" data-target="#agent">Employee Attendance &#x21F2;</a> -->
        <div id="agent" class="collapse">
         
   <a href="{{ URL::to('/') }}/seniorteam">&nbsp;&nbsp;&nbsp; -Senior Team Leader</a> 
            <a href="{{ URL::to('/') }}/teamleader">&nbsp;&nbsp;&nbsp; -Team Leaders</a> 
            <a href="{{ URL::to('/') }}/saleseng">&nbsp;&nbsp;&nbsp; -Sales Engineer</a> 
            <a href="{{ URL::to('/') }}/marketexe"> &nbsp;&nbsp;&nbsp; -Marketing </a>
            <a href="{{ URL::to('/') }}/teamlisteng">&nbsp;&nbsp;&nbsp; -Listing Engineer</a> 
           
            <a href="{{ URL::to('/') }}/teamacceng"> &nbsp;&nbsp;&nbsp; -Account Executive</a>
            <a href="{{ URL::to('/') }}/market"> &nbsp;&nbsp;&nbsp; -Market Researcher</a>
            <a href="{{ URL::to('/') }}/hr"> &nbsp;&nbsp;&nbsp; -Human Resourse</a>
        </div>
         <a href="#" data-toggle="collapse" data-target="#foffice">Field and Office Logins &#x21F2;</a>
        <div id="foffice" class="collapse">
            <a href="{{ URL::to('/') }}/teamlisteng">&nbsp;&nbsp;&nbsp; -Listing Engineer</a>  
            <a href="{{ URL::to('/') }}/ofcemp"> &nbsp;&nbsp;&nbsp; -Office Employees</a>
        </div> 
        <a href="{{ URL::to('/humanresources') }}">&nbsp;&nbsp;&nbsp; - Employees</a>
        <a href="{{ URL::to('/') }}/mhemployee">&nbsp;&nbsp;&nbsp; - MAMAHOME Employee</a>
        <a href="{{ URL::to('/minibreack') }}">&nbsp;&nbsp;&nbsp;BreakTime Mini Report</a>

        <a href="{{ URL::to('/anr') }}">&nbsp;&nbsp;&nbsp; - Reports</a>
        <a href="{{ URL::to('/check') }}">&nbsp;&nbsp;&nbsp; - HR Files and Checklist</a>
        <a href="{{ URL::to('/') }}/assets">&nbsp;&nbsp;&nbsp; - Add Assets</a>
        <a href="{{ URL::to('/') }}/assignassets">&nbsp;&nbsp;&nbsp; - Assign Assets to Department</a>
        <a href="{{ URL::to('/video') }}">&nbsp;&nbsp;&nbsp; - Training Video</a>
        <a href="{{ URL::to('/') }}/adminlatelogin">&nbsp;&nbsp;&nbsp; - Late Logins</a>
        <a href="{{ URL::to('/') }}/breaks">&nbsp;&nbsp;&nbsp; - BreakTime</a>

        
    </div>
    <a href="#" data-toggle="collapse" data-target="#ap">All Departments &#x21F2;</a>
    <div id="ap" class="collapse">
       <!--  <a href="{{ URL::to('/amdashboard') }}">&nbsp;&nbsp;&nbsp; - Human Resource</a> -->
        <a href="{{ URL::to('/leDashboard') }}">&nbsp;&nbsp;&nbsp; - Operation (LE)</a>
        <a href="{{ URL::to('/teamLead') }}">&nbsp;&nbsp;&nbsp; - Operation (TL)</a>
        <a href="{{ URL::to('/salesEngineer') }}">&nbsp;&nbsp;&nbsp; - Sales Engineer</a>
        <a href="{{ URL::to('/marketing') }}">&nbsp;&nbsp;&nbsp; - Marketing</a>
        <a href="{{ URL::to('/amdashboard') }}">&nbsp;&nbsp;&nbsp; - Asst. Manager of sales</a>
    </div>
    <!-- <a href="{{ URL::to('/employeereports') }}">Attendance</a> -->
    <a href="{{ URL::to('/amdept') }}">Add Authorities</a>
   <!--  <a href="{{ URL::to('/finance') }}">Finance</a> -->
  <!--  <a href="{{ URL::to('/letracking') }}">Tracking</a> -->
    <a href="#" data-toggle="collapse" data-target="#manufacturer_details">View Manufacturer &#x21F2;</a>
    <div id="manufacturer_details" class="collapse">
       <!--  <a href="{{ URL::to('/amdashboard') }}">&nbsp;&nbsp;&nbsp; - Human Resource</a> -->
        <a href="{{ URL::to('/viewManufacturer?type=Blocks') }}">&nbsp;&nbsp;&nbsp; - Blocks</a>
        <a href="{{ URL::to('/viewManufacturer?type=RMC') }}">&nbsp;&nbsp;&nbsp; - RMC</a>
    </div>
    <a href="#" data-toggle="collapse" data-target="#manufacturer_details1">Direct Aligned Partners &#x21F2;</a>
    <div id="manufacturer_details1" class="collapse">
    <a href="{{ URL::to('/manufacturerdetails') }}">Suppliers</a>
    <a href="{{ URL::to('/lebrands') }}">Brands</a>
</div>
    <a href="{{ URL::to('/activitylog') }}">Activity Log</a>
    <a href="{{ URL::to('/assignadmin') }}">Assign wards to Admin</a>
    <!-- <a href="{{ URL::to('/confidential') }}">Confidential</a> -->
    <a href="{{ URL::to('/allProjectsWithWards') }}">Data Quality of Projects</a>
    <a href="{{ URL::to('payment') }}">Delivery order Details</a>
     <a href="{{ URL::to('/') }}/viewInvoices">Invoices</a>
  <a href="{{ URL::to('/setprice') }}">Price setting based on designation</a>
  <!--  <a href="{{ URL::to('checkdetailes') }}">Cheque Details</a> -->
  <a href="{{ URL::to('/cashdeposit') }}">Cash Deposit Details</a>
           <a href="{{ URL::to('/blocked_projects') }}">Blocked Projects</a>
           <a href="{{ URL::to('/blocked_manu') }}">Blocked Manufacturer</a>
           <a href="{{ URL::to('/cancelorders') }}">Canceled Orders</a>


           <a href="{{ URL::to('/wardreport') }}">Ward Assigned Report</a>
            
           <a href="{{ URL::to('/ledger') }}">Ledger Sheet</a>
           <a href="{{ URL::to('/gstinformation') }}">GST Report</a>
</div>
 <a href="#" data-toggle="collapse" data-target="#addssw">Petrol Allowence Details &#x21F2;</a>
       <div id="addssw" class="collapse">
        <a href="{{URL::to('/petrol') }}">LE Petrol Allowence</a>
       </div>
       <a href="#" data-toggle="collapse" data-target="#addsswm">Blocked Projects/Manufacturers &#x21F2;</a>
       <div id="addsswm" class="collapse">
        <a href="{{URL::to('/blocked') }}">Blocked Projects</a>
        <a href="{{URL::to('/manublocked') }}">Blocked Manufacturers</a>

       </div>         
      
       <a href="#" data-toggle="collapse" data-target="#addsswms">Updated Reports &#x21F2;</a>
       <div id="addsswms" class="collapse">
        <a href="{{URL::to('/projectupdatereport') }}">Updated Projects</a>
        <a href="{{URL::to('/manuupdatereport') }}">Updated Manufacturers</a>

       </div>

         <a href="#" data-toggle="collapse" data-target="#y">Proposed Customers  &#x21F2;</a>
        <div id="y" class="collapse">
        <a href="{{URL::to('/projects') }}">Project Customers</a>
        <a href="{{URL::to('/manufactured') }}">Manufacturer Customers</a>
        <a href="{{URL::to('/test') }}">Proposed Phone  Numbers</a>
        <a href="{{URL::to('/closedcontractor') }}">Closed Contractor details</a>
        
        
       
        </div>   

        <a href="#" data-toggle="collapse" data-target="#addsswmsm">Customers Reports &#x21F2;</a>
        <div id="addsswmsm" class="collapse">
        <a href="{{URL::to('/customer_report') }}">Customers Details</a>
        <a href="{{URL::to('/customersalesreport') }}">Customers Monthly Sales Reports</a>
        <a href="{{URL::to('/activecustomers') }}">Active Customers Deatils</a>
        
       </div> 
</div>

 
@elseif(Auth::user()->group_id == 2 && Auth::user()->department_id == 1)  
     @if(Auth::user()->id == 91)
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" onclick="closeNav()">&times;</a>
<a href="#" data-toggle="collapse" data-target="#demo999111">Important Documents &#x21F2;</a>
<div id="demo999111" class="collapse">
  
        <a href="{{ URL::to('/') }}/check">&nbsp;&nbsp;&nbsp;Company Imaportant Documents Copy
</a>
</div>
<a href="{{ URL::to('/') }}/cash">&nbsp;&nbsp;&nbsp; - Generate Cash Receipt</a>
<a href="#" data-toggle="collapse" data-target="#demo1">Operation &#x21F2;</a>
<div id="demo1" class="collapse">
     <a href="{{ URL::to('/') }}/mapping">&nbsp;&nbsp;&nbsp; - Zonal Maps </a>
    <a href="{{ URL::to('/') }}/teamlisteng">&nbsp;&nbsp;&nbsp; - Employee Tracking</a>
        <a href="#" data-toggle="collapse" data-target="#demo4224">Plot And Villas &#x21F2;</a>
<div id="demo4224" class="collapse">
 <a href="{{ URL::to('/') }}/plots_dailyslots">&nbsp;&nbsp;&nbsp; - Listed Plots Date Wise</a>

</div>
<a href="{{ URL::to('/') }}/assignListSlots">Assign Sub Ward & Field Employees Report </a>
     <a href="{{URL::to('/petrol') }}">LE Petrol Allowence</a>
<a href="#" data-toggle="collapse" data-target="#demo44">Projects &#x21F2;</a>
<div id="demo44" class="collapse">
 <a href="{{ URL::to('/') }}/dailyslots">&nbsp;&nbsp;&nbsp; - Listed Projects Date wise</a>
 <a href="{{URL::to('/getprojectsize') }}">&nbsp;&nbsp;&nbsp; - Listed Project In Stage Wise Count </a> 
 <a href="{{ URL::to('/quality') }}">&nbsp;&nbsp;&nbsp; - Quality of Projects</a>
 <a href="{{ URL::to('/viewallProjects') }}">&nbsp;&nbsp;&nbsp; - Projects Search</a>
 <a href="{{ URL::to('/blocked_projects') }}">&nbsp;&nbsp;&nbsp; - Blocked Projects</a>
  <a href="{{ URL::to('/') }}/Unupdated">&nbsp;&nbsp;&nbsp; - Updated Projects</a>
  <a href="{{ URL::to('/') }}/dateUnupdated">&nbsp;&nbsp;&nbsp; - UnUpdated Projects</a>

<a href="{{ URL::to('/getprojectsizedata') }}">&nbsp;&nbsp;&nbsp; - Listed Projects Based On Sizes</a>

 <a href="{{ URL::to('/newActivityLog') }}">&nbsp;&nbsp;&nbsp; - Projects Updated Report</a>
</div>
<a href="#" data-toggle="collapse" data-target="#demo55">Manufacturers &#x21F2;</a>
<div id="demo55" class="collapse">
 <a href="{{ URL::to('/') }}/manudailyslot">&nbsp;&nbsp;&nbsp; - Listed Manufacturers Date wise</a>
 <a href="{{URL::to('/viewManufacturer') }}">&nbsp;&nbsp;&nbsp;- Listed Manufacturers Details </a>
 <a href="{{ URL::to('/manureport') }}">&nbsp;&nbsp;&nbsp; - Manufacturers ward wise Report</a>
 <a href="{{ URL::to('/manuupdate') }}">&nbsp;&nbsp;&nbsp; - Manufacturer Updated Report</a>
<a href="{{ URL::to('/') }}/unupdatedmanu">&nbsp;&nbsp;&nbsp; -UnUpdated Manufacturers</a>
<a href="{{ URL::to('/blocked_manu') }}">&nbsp;&nbsp;&nbsp; -Blocked Manufacturer</a>
         


</div>

    </div>
    <a href="#" data-toggle="collapse" data-target="#demo88">Sales & Marketing &#x21F2;</a>
<div id="demo88" class="collapse">
        <a href="{{URL::to('/targetrem') }}">&nbsp;&nbsp;&nbsp; -Set Target Reminder</a>
 <a href="{{ URL::to('/monthlyinvoicedata') }}">&nbsp;&nbsp;&nbsp; - Sales Report</a>
  <a href="{{ URL::to('/orders') }}">&nbsp;&nbsp;&nbsp; - Orders</a>
  <a href="{{ URL::to('/financeDashboard') }}">&nbsp;&nbsp;&nbsp; - Confirmed Orders</a>
  <a href="{{ URL::to('/pendingorders') }}">&nbsp;&nbsp;&nbsp; -Approvel Pending Orders</a>

  <a href="{{ URL::to('/getquotation') }}">&nbsp;&nbsp;&nbsp; - Get Quotation</a>
  <a href="{{ URL::to('/allprice') }}">&nbsp;&nbsp;&nbsp; - Products Prices</a>
  <a href="{{ URL::to('/tlsalesreports') }}">&nbsp;&nbsp;&nbsp; - Sales Engineer Report</a>
  <a href="{{ URL::to('/') }}/tlenquirysheet">&nbsp;&nbsp;&nbsp; - Project Enquiry Sheet</a>
  <a href="{{ URL::to('/') }}/manuenquirysheet">&nbsp;&nbsp;&nbsp; - Manufacturer Enquiry Sheet</a>
  <a href="{{ URL::to('/')}}/enquiryCancell?project=project">&nbsp;&nbsp;&nbsp; - Project Enquiry cancelled</a>
  <a href="{{ URL::to('/')}}/enquiryCancell?project=manu">&nbsp;&nbsp;&nbsp; - Manufacturer Enquiry cancelled</a>

</div>
<a href="#" data-toggle="collapse" data-target="#adddproejectc">Closed Customers &#x21F2;</a>
          <div id="adddproejectc" class="collapse">
            <a href="{{ URL::to('/') }}/assignclosedcontractors"> Assigned Closed Contractor </a>
            <a href="{{ URL::to('/') }}/csite"> Assigned Closed Site Engineers </a>
            <a href="{{ URL::to('/') }}/cbuilders"> Assigned Closed Builders </a>
            <a href="{{ URL::to('/') }}/cowners"> Assigned Closed Owners </a>
            </div>
    <a href="{{ URL::to('/manufacturerdetails') }}">&nbsp;&nbsp;&nbsp; - Suppliers</a>

 

 <a href="#" data-toggle="collapse" data-target="#demo909">MamaHome Customers &#x21F2;</a>
<div id="demo909" class="collapse">
  <a href="{{ URL::to('/') }}/customermap">&nbsp;&nbsp;&nbsp; - Listed Projects, Manufacturers & Customer Details</a>
        <a href="{{URL::to('/assignvistedcustomer') }}">&nbsp;&nbsp;&nbsp; -Visited Customers Report</a>
        <a href="{{URL::to('/customerbank') }}">&nbsp;&nbsp;&nbsp; -Customer Transactions Details </a>
        <a href="{{URL::to('/customerledger') }}">&nbsp;&nbsp;&nbsp; -Customer Ledger</a>
           <a href="{{ URL::to('/getcustomerinvoices') }}">&nbsp;&nbsp;&nbsp; -Customer Invoice Details</a>
        <a href="{{URL::to('/assigncustomers') }}">&nbsp;&nbsp;&nbsp; -Assign Customers To Visit</a>  
</div>
 <a href="#" data-toggle="collapse" data-target="#demo9092">Dedicated Customers History &#x21F2;</a>
<div id="demo9092" class="collapse">
  <a href="{{ URL::to('/') }}/newcustomerassign">&nbsp;&nbsp;&nbsp; - Assign Dedicated Customers TO Sales Engineers</a>
        <a href="{{URL::to('/usercustomers') }}">&nbsp;&nbsp;&nbsp; -Assigned Customer Details</a>
       <!--  <a href="{{URL::to('/customerbank') }}">&nbsp;&nbsp;&nbsp; -Customer Transactions Details </a>
        <a href="{{URL::to('/customerledger') }}">&nbsp;&nbsp;&nbsp; -Customer Ledger</a>
           <a href="{{ URL::to('/getcustomerinvoices') }}">&nbsp;&nbsp;&nbsp; -Customer Invoice Details</a> -->
        
</div>
<div id="plan" class="collapse">

</div>
 <a href="#" data-toggle="collapse" data-target="#adfdssss">Near By Projects And Manufacturers Details &#x21F2;</a>
       <div id="adfdssss" class="collapse">
              
              <a href="{{URL::to('/testdistance') }}">&nbsp;&nbsp;&nbsp; -Get Near By Projects</a>
              <a href="{{URL::to('/getmanudistance') }}">&nbsp;&nbsp;&nbsp; -Get Near By Manufacturers</a>
              
         
    
       </div>
<a href="#" data-toggle="collapse" data-target="#links1234"> Reports &#x21F2;</a>

<div id="links1234" class="collapse">
    <a href="{{ URL::to('/') }}/updatereport">Manufacturer Updated Reports</a>
    <a href="{{ URL::to('/') }}/usingbrand">Customer Using Brands</a>
    <a href="{{ URL::to('/') }}/totalreport">Employees Work Report</a>
    <a href="{{ URL::to('/') }}/totalcallattend">Employees Mini Work Report</a>
    <a href="{{ URL::to('/') }}/tototallog">Logistic Report</a>
    <a href="{{ URL::to('/') }}/customerstatus">Customer Status Report</a>


    
    </div>

    <a href="#" data-toggle="collapse" data-target="#links1234hj"> Closed Customers Data &#x21F2;</a>

<div id="links1234hj" class="collapse">
    <a href="{{ URL::to('/') }}/closedcontractor">Closed Customers</a>
    <a href="{{ URL::to('/') }}/assignedclosedcustomers">Assigned Closed Customers</a>
    
   

    
    </div>

<a href="#" data-toggle="collapse" data-target="#links1234m"> WhatsApp Reports &#x21F2;</a>

<div id="links1234m" class="collapse">
    <a href="{{ URL::to('/') }}/dedicatedwhatsapp">Dediacted customers Whatsapp</a>
    <a href="{{ URL::to('/') }}/Proposedprojectswhatsapp">Manufacturer And Projects Updated Whatsapp</a>
    
    
    
    </div>
       
       <a href="{{ URL::to('/setprice') }}">Price setting based on designation</a>
       <a href="{{ URL::to('/cancelorders') }}">Canceled Orders</a>

        <a href="#" data-toggle="collapse" data-target="#add">Add &#x21F2;</a>
    <div id="add" class="collapse">
     
     
    
   
    <a href="{{ URL::to('/') }}/Materialhub">&nbsp;&nbsp;&nbsp; - Add Materialhub  </a>
    <a href="{{ URL::to('/') }}/Retailers">&nbsp;&nbsp;&nbsp; - Add Retailers  </a>
    <a href="{{ URL::to('/') }}/marketingvendordetails">&nbsp;&nbsp;&nbsp; - Add Vendor/Supplier Details</a>
    <a  href="{{ URL::to('/')}}/plots">&nbsp;&nbsp;&nbsp; - Add New Plot Or Villas</a>
      <a  href="{{ URL::to('/')}}/listingEngineer">&nbsp;&nbsp;&nbsp; - Add New Project</a>
      <a  href="{{ URL::to('/')}}/addManufacturer">&nbsp;&nbsp;&nbsp; - Add New Manufacturer</a>
      <a  href="{{ URL::to('/')}}/inputview">&nbsp;&nbsp;&nbsp; - Add New Project Enquiry</a>
    <a href="{{ URL::to('/') }}/manuenquiry">&nbsp;&nbsp;&nbsp; - Add New Manufacturer Enquiry</a>
    <a href="{{ URL::to('/marketing') }}">Add Products and Brand</a>
    <a href="{{ URL::to('/') }}/amhumanresources">Add And Remove Empployees</a>
    <a href="{{ URL::to('/') }}/assets">Add Assets</a>
            <a href="{{ URL::to('/') }}/assignassets">Assign Assets to Department</a>
            <a href="{{ URL::to('/') }}/video"> Add Training Video</a>
            <a href="{{ URL::to('/updateprice') }}">Add GST To Categories </a> 
      

    </div>
     <a href="#" data-toggle="collapse" data-target="#addss">Customer Details &#x21F2;</a>
       <div id="addss" class="collapse">
        <a href="{{URL::to('/customermap') }}">Customer Details</a>
        <a href="{{URL::to('/assigncustomers') }}">Assign Customers To Visit</a>
        <a href="{{ URL::to('/assign_project') }}">&nbsp;&nbsp;&nbsp; -Assign Project</a>
        <a href="{{URL::to('/customervisit') }}">-Assigned Customers For Field Visit</a>
       <a href="{{ URL::to('/details') }}">Assign Customers</a>
        <a href="{{URL::to('/assignvistedcustomer') }}">Visited Customers</a>

        <a href="{{URL::to('/customerbank') }}">Customer Transactions Details </a>
         <a href="{{ URL::to('/customer') }}">Assigned Customers</a>
        <a href="{{URL::to('/customerledger') }}">Customer Ledger</a>

           <a href="{{ URL::to('/getcustomerinvoices') }}">Customer Invoice Destials</a>
           <a href="{{ URL::to('/ledger') }}">Ledger Sheet</a>
       </div>
       <a href="#" data-toggle="collapse" data-target="#addproject256">Proposed Projects And Manufacturers &#x21F2;</a>
          <div id="addproject256" class="collapse">
                <a  href="{{ URL::to('/')}}/assignppids">&nbsp;&nbsp;&nbsp; -Proposed Projects</a>
                 <a  href="{{ URL::to('/')}}/holdingcust">&nbsp;&nbsp;&nbsp; -Proposed Holding Projects</a>
                <a  href="{{ URL::to('/')}}/assignmpids">&nbsp;&nbsp;&nbsp; -Proposed Manufacturers</a>
                          <a href="{{URL::to('/test') }}">Proposed Phone  Numbers</a>

        <!--  <a  href="{{ URL::to('/')}}/dnumbers">&nbsp;&nbsp;&nbsp; -SMS Numbers</a>
         <a  href="{{ URL::to('/')}}/dprojects">&nbsp;&nbsp;&nbsp; -Projects</a>
         <a  href="{{ URL::to('/')}}/dmanus">&nbsp;&nbsp;&nbsp; -Manufacturers</a>
          <a  href="{{ URL::to('/')}}/denquery">&nbsp;&nbsp;&nbsp; -Enquiries</a> -->
          </div>
         
    
          
            <a href="{{ URL::to('/') }}/mhemployee">MAMAHOME Employee</a>
            <a href="{{ URL::to('/') }}/minibreack">Employees breaktimes</a>
        <a href="{{ URL::to('/anr') }}">Reports</a>
            <a href="{{ URL::to('/') }}/amviewattendance">Attendance</a>
            <a href="{{ URL::to('/') }}/newamviewattendance">New Attendance</a>
            <a href="{{ URL::to('/') }}/check">HR Files and Checklist</a>
           
            <a href="{{ URL::to('/') }}/assignassets">Assign Assets to Department</a>
    
       <!--  <a href="#" data-toggle="collapse" data-target="#agent">Employee Attendance &#x21F2;</a> -->
        <div id="agent" class="collapse">
            <a href="{{ URL::to('/') }}/seniorteam">&nbsp;&nbsp;&nbsp; -Senior Team Leader</a> 
            <!-- <a href="{{ URL::to('/') }}/seniorteam1">&nbsp;&nbsp;&nbsp; -Senior Team Leader1</a> -->
            <a href="{{ URL::to('/') }}/teamleader">&nbsp;&nbsp;&nbsp; -Team Leaders</a> 
            <!-- <a href="{{ URL::to('/') }}/teamleader1">&nbsp;&nbsp;&nbsp; -Team Leaders1</a> -->
            <a href="{{ URL::to('/') }}/saleseng">&nbsp;&nbsp;&nbsp; -Sales Engineer</a> 
            <a href="{{ URL::to('/') }}/marketexe"> &nbsp;&nbsp;&nbsp; -Marketing </a>
            <a href="{{ URL::to('/') }}/teamlisteng">&nbsp;&nbsp;&nbsp; -Listing Engineer</a> 
             <a href="{{ URL::to('/') }}/listatt">&nbsp;&nbsp;&nbsp; -Listing Engineer Attendance</a> 
            <a href="{{ URL::to('/') }}/teamacceng"> &nbsp;&nbsp;&nbsp; -Account Executive</a>
            <a href="{{ URL::to('/') }}/market"> &nbsp;&nbsp;&nbsp; -Market Researcher</a>

        </div> 
        <a href="#" data-toggle="collapse" data-target="#foffice">Field and Office Logins &#x21F2;</a>
        <div id="foffice" class="collapse">
            <a href="{{ URL::to('/') }}/teamlisteng">&nbsp;&nbsp;&nbsp; -Listing Engineer</a>  
            <!-- <a href="{{ URL::to('/') }}/teamacceng"> &nbsp;&nbsp;&nbsp; -Account Executive</a> -->
            <a href="{{ URL::to('/') }}/ofcemp"> &nbsp;&nbsp;&nbsp; -Office Employees</a>
        </div> 
         <a href="{{ URL::to('/') }}/hrlatelogins">Late Logins</a>
         <a href="{{ URL::to('/') }}/holidays">Holiday List</a>
         <a href="{{ URL::to('/') }}/breaks">BreakTime</a>
       <!--  <a href="{{ URL::to('/') }}/breaktimes">Break Times</a> -->
   
  </div>
  @else
  
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" onclick="closeNav()">&times;</a>
      <!-- <a href="{{ URL::to('/assigntl') }}">Assign Team Leaders </a> -->
      <a href="{{ URL::to('/noneed') }}">Delete Numbers</a>
       <a href="{{URL::to('/getprojectsize') }}">Listed Projects & Sizes </a> 
     <a href="#" data-toggle="collapse" data-target="#adds">Reports &#x21F2;</a>
       <div id="adds" class="collapse">
        <a href="{{URL::to('/projectandward') }}">Project Report</a> 
        <a href="{{URL::to('/manureport') }}">Manufactureres Report</a>
        <a href="{{URL::to('/rejectinvoice') }}">Rejected Invoice Details </a>
         <a href="{{ URL::to('/SMSSentreport') }}">SMS Sent Report</a> 
           <a href="{{ URL::to('/wardreport') }}">Ward Assigned Report</a>
              
    
    <a href="{{ URL::to('/newActivityLog') }}">Projects Updated Report</a>
                 <a href="{{ URL::to('/manuupdate') }}">Manufacturer Updated Report</a>

       </div>
<a href="{{ URL::to('/') }}/cash">&nbsp;&nbsp;&nbsp; - Generate Cash Receipt</a>
       
        <a href="#" data-toggle="collapse" data-target="#addssw">Petrol Allowence Details &#x21F2;</a>
       <div id="addssw" class="collapse">
        <a href="{{URL::to('/petrol') }}">LE Petrol Allowence</a>
       </div>
         <a href="#" data-toggle="collapse" data-target="#addss">Customer Details &#x21F2;</a>
       <div id="addss" class="collapse">
        <a href="{{URL::to('/assigncustomers') }}">Assign Customers To Visit</a>

        <a href="{{URL::to('/customervisit') }}">-Assigned Customers For Field Visit</a>
       <a href="{{ URL::to('/details') }}">Assign Customers</a>
        <a href="{{URL::to('/assignvistedcustomer') }}">Visited Customers</a>

        <a href="{{URL::to('/customerbank') }}">Customer Transactions Details </a>
         <a href="{{ URL::to('/customer') }}">Assigned Customers</a>
        <a href="{{URL::to('/customerledger') }}">Customer Ledger</a>

           <a href="{{ URL::to('/getcustomerinvoices') }}">Customer Invoice Destials</a>
           <a href="{{ URL::to('/ledger') }}">Ledger Sheet</a>
       </div>

         <a href="#" data-toggle="collapse" data-target="#addssss">Maps &#x21F2;</a>
       <div id="addssss" class="collapse">
              <a href="{{ URL::to('/') }}/tlmaps">&nbsp;&nbsp;&nbsp; -Maps</a> 
              <a href="{{URL::to('/customermap') }}">&nbsp;&nbsp;&nbsp; -Customer Details Map</a>
              <a href="{{URL::to('/materialhubmap') }}">&nbsp;&nbsp;&nbsp; -MaterialHub Details Map</a>
              <a href="{{URL::to('/testdistance') }}">&nbsp;&nbsp;&nbsp; -Get Near By Projects</a>
              <a href="{{URL::to('/getmanudistance') }}">&nbsp;&nbsp;&nbsp; -Get Near By Manufacturers</a>
              
         
    
       </div>

 <a href="#" data-toggle="collapse" data-target="#links1234m"> WhatsApp Reports &#x21F2;</a>

<div id="links1234m" class="collapse">
    <a href="{{ URL::to('/') }}/dedicatedwhatsapp">Dediacted customers Whatsapp</a>
    <a href="{{ URL::to('/') }}/Proposedprojectswhatsapp">Manufacturer And Projects Updated Whatsapp</a>
    
    
    
    </div>
    <a href="#" data-toggle="collapse" data-target="#links1234hj"> Closed Customers Data &#x21F2;</a>

<div id="links1234hj" class="collapse">
    <a href="{{ URL::to('/') }}/closedcontractor">Closed Customers</a>
    <a href="{{ URL::to('/') }}/assignedclosedcustomers">Assigned Closed Customers</a>
    
   

    
    </div>

 <a href="#" data-toggle="collapse" data-target="#addsss">Projects And Manufacturers &#x21F2;</a>
       <div id="addsss" class="collapse">
         <a href="{{ URL::to('/unverifiedmanu') }}">&nbsp;&nbsp;&nbsp; -Unverifed Manufacturers</a>
               <a href="{{ URL::to('/') }}/unverifiedProjects">&nbsp;&nbsp;&nbsp; -Unverified Projects</a>
           <a href="{{ URL::to('/customermanu') }}">&nbsp;&nbsp;&nbsp; -Assigned Manufacturer Customers</a>
           <a href="{{ URL::to('/blocked_projects') }}">&nbsp;&nbsp;&nbsp; -Blocked Projects</a>
           <a href="{{ URL::to('/blocked_manu') }}">&nbsp;&nbsp;&nbsp; -Blocked Manufacturer</a>
       </div>
       
       <a href="#" data-toggle="collapse" data-target="#addproject22">Dedicated Customers &#x21F2;</a>
          <div id="addproject22" class="collapse">
                <a  href="{{ URL::to('/')}}/dCustomers">&nbsp;&nbsp;&nbsp; -Customer Deatils</a>
         <a  href="{{ URL::to('/')}}/dnumbers">&nbsp;&nbsp;&nbsp; -SMS Numbers</a>
         <a  href="{{ URL::to('/')}}/dprojects">&nbsp;&nbsp;&nbsp; -Projects</a>
         <a  href="{{ URL::to('/')}}/dmanus">&nbsp;&nbsp;&nbsp; -Manufacturers</a>
          <a  href="{{ URL::to('/')}}/denquery">&nbsp;&nbsp;&nbsp; -Enquiries</a>
          </div>
           
      <a href="#" data-toggle="collapse" data-target="#addproject2563">Proposed Projects And Manufacturers &#x21F2;</a>
          <div id="addproject2563" class="collapse">
                <a  href="{{ URL::to('/')}}/assignppids">&nbsp;&nbsp;&nbsp; -Proposed Projects</a>
                <a  href="{{ URL::to('/')}}/assignmpids">&nbsp;&nbsp;&nbsp; -Proposed Manufacturers</a>
                          <a href="{{URL::to('/test') }}">&nbsp;&nbsp;&nbsp;-Proposed Phone  Numbers</a>

        <!--  <a  href="{{ URL::to('/')}}/dnumbers">&nbsp;&nbsp;&nbsp; -SMS Numbers</a>
         <a  href="{{ URL::to('/')}}/dprojects">&nbsp;&nbsp;&nbsp; -Projects</a>
         <a  href="{{ URL::to('/')}}/dmanus">&nbsp;&nbsp;&nbsp; -Manufacturers</a>
          <a  href="{{ URL::to('/')}}/denquery">&nbsp;&nbsp;&nbsp; -Enquiries</a> -->
          </div>
          <a href="#" data-toggle="collapse" data-target="#adddprojectc">Closed Customers &#x21F2;</a>
          <div id="adddprojectc" class="collapse">
            <a href="{{ URL::to('/') }}/assignclosedcontractors"> Assigned Closed Contractor </a>
            <a href="{{ URL::to('/') }}/csite"> Assigned Closed Site Engineers </a>
            <a href="{{ URL::to('/') }}/cbuilders"> Assigned Closed Builders </a>
            <a href="{{ URL::to('/') }}/cowners"> Assigned Closed Owners </a>
            </div>
        <a href="#" data-toggle="collapse" data-target="#addssssss">All Other Links &#x21F2;</a>
       <div id="addssssss" class="collapse">

        <a href="{{URL::to('/targetrem') }}"> -Target Reminder</a>
        <a href="{{URL::to('/lcoorders') }}">&nbsp;&nbsp;&nbsp; - Delivery details</a>
       <a href="{{ URL::to('/marketing') }}">Add Products and Brand</a>
       <a href="{{ URL::to('/setprice') }}">Price setting based on designation</a>
       <a href="{{ URL::to('/cancelorders') }}">Canceled Orders</a>
         <a  href="{{ URL::to('/')}}/lebrands">Brands</a>
        <a href="{{ URL::to('/') }}/marketingvendordetails">Vendor details</a>
        <a href="{{ URL::to('/viewManufacturer') }}"> Manufacter Details</a>
        <a href="{{ URL::to('/') }}/teamkra"> Add KRA to Operation and Sales</a>
        <a href="{{ URL::to('/') }}/kra">KRA</a> 
       <a href="{{ URL::to('/') }}/teamlatelogin">Late Logins</a>
       <a href="{{ URL::to('/') }}/breaks">BreakTime</a>

       </div>    
       <a href="{{URL::to('/usercustomers') }}">&nbsp;&nbsp;&nbsp; -Assigned Customer Details</a>
      <!-- <a href="{{ URL::to('/assigntl') }}"></a> -->
      <!-- <a href="#" data-toggle="collapse" data-target="#so"> Sales Officers &#x21F2;</a>
    <div id="so" class="collapse">
        <a href="{{ URL::to('/cat') }}">&nbsp;&nbsp;&nbsp; - Assign Category</a>
        <a href="{{ URL::to('/catofficer') }}">&nbsp;&nbsp;&nbsp; -Category Officers Report </a>

    </div> -->
   <a href="#" data-toggle="collapse" data-target="#links1234"> Reports &#x21F2;</a>

<div id="links1234" class="collapse">
    <a href="{{ URL::to('/') }}/updatereport">Manufacturer Updated Reports</a>
    <a href="{{ URL::to('/') }}/usingbrand">Customer Using Brands</a>
    <a href="{{ URL::to('/') }}/totalreport">Employees Work Report</a>
    <a href="{{ URL::to('/') }}/totalcallattend">Employees Mini Work Report</a>
    
    
    </div>
    <a href="{{ URL::to('/') }}/mhemployee">Change Dashboard</a>
       <a href="#" data-toggle="collapse" data-target="#add">Add &#x21F2;</a>
    <div id="add" class="collapse">
      <a  href="{{ URL::to('/')}}/listingEngineer">&nbsp;&nbsp;&nbsp; - Add New Project</a>
      <a  href="{{ URL::to('/')}}/addManufacturer">&nbsp;&nbsp;&nbsp; - Add New Manufacturer</a>
      <a  href="{{ URL::to('/')}}/inputview">&nbsp;&nbsp;&nbsp; - Add New Enquiry</a>
    <a href="{{ URL::to('/') }}/manuenquiry">&nbsp;&nbsp;&nbsp; - Add Manufacturer  Enquiry</a>
    <a href="{{ URL::to('/') }}/Materialhub">&nbsp;&nbsp;&nbsp; - Add Materialhub  </a>
    <a href="{{ URL::to('/') }}/Retailers">&nbsp;&nbsp;&nbsp; - Add Retailers  </a>
    
      

    </div>
     
    
     <a href="#" data-toggle="collapse" data-target="#sales">Sales &#x21F2;</a>

        <div id="sales" class="collapse">

              <a href="{{ URL::to('/orders') }}">&nbsp;&nbsp;&nbsp; -Orders</a>
              <a href="{{ URL::to('/financeDashboard') }}">&nbsp;&nbsp;&nbsp; - Confirmed Orders</a>
              <a href="{{ URL::to('/getquotation') }}">&nbsp;&nbsp;&nbsp; - Get Quotation</a>
              <a href="{{ URL::to('/allprice') }}">&nbsp;&nbsp;&nbsp; -Products Prices</a>
               <a href="{{ URL::to('/updateprice') }}">Add GST to categories and brands</a>              
               <a href="{{ URL::to('/tlsalesreports') }}">&nbsp;&nbsp;&nbsp; -Sales Engineer Report</a>
              <a href="{{ URL::to('/') }}/tlenquirysheet">&nbsp;&nbsp;&nbsp; -Enquiry Sheet</a>
              <a href="{{ URL::to('/') }}/manuenquirysheet">&nbsp;&nbsp;&nbsp; -Manufacturer Enquiry Sheet</a>
              <a href="{{ URL::to('/')}}/enquiryCancell?project=project">&nbsp;&nbsp;&nbsp; -Project Enquiry cancelled</a>
              <a href="{{ URL::to('/')}}/enquiryCancell?project=manu">&nbsp;&nbsp;&nbsp; -Manufacturer Enquiry cancelled</a>

             
              <a href="{{ URL::to('/assign_project') }}">&nbsp;&nbsp;&nbsp; -Assign Project</a>
              <a href="{{ URL::to('/assign_number') }}">&nbsp;&nbsp;&nbsp; -Assign Phone Numbers</a>
              <a href="{{ URL::to('/assign_enquiry') }}">&nbsp;&nbsp;&nbsp; -Assign Enquiry</a>
              <a href="{{ URL::to('/enquiryassign') }}">&nbsp;&nbsp;&nbsp; -Assign Customer Enquiry</a>

              <a href="{{ URL::to('/assign_manufacturer') }}">&nbsp;&nbsp;&nbsp; -Assign Manufacturers</a>
        </div>
     <a href="#" data-toggle="collapse" data-target="#operation">Operation &#x21F2;</a>
        <div id="operation" class="collapse">
              <a href="{{ URL::to('/') }}/tlmaps">&nbsp;&nbsp;&nbsp; -Maps</a> 
             <!--  <a href="{{ URL::to('/tltracking') }}">&nbsp;&nbsp;&nbsp; -Tracking</a> -->

               <a href="{{ URL::to('/') }}/Unupdated">&nbsp;&nbsp;&nbsp; -Total Updated Projects</a>
               <a href="{{ URL::to('/') }}/dateUnupdated">&nbsp;&nbsp;&nbsp; - UnUpdated Projects</a>
               <a href="{{ URL::to('/') }}/unupdatedmanu">&nbsp;&nbsp;&nbsp; -Total UnUpdated Manufacturers</a>

               
              <a href="{{ URL::to('/') }}/projectWithNotes">&nbsp;&nbsp;&nbsp; -Projects With Notes</a>
               <a href="#" data-toggle="collapse" data-target="#dailyslot">&nbsp;&nbsp;&nbsp;Daily Slots &#x21F2;</a>
              <div id="dailyslot" class="collapse">


                    <a href="{{ URL::to('/dailyslots') }}">&nbsp;&nbsp;&nbsp; -Projects Daily Slots</a>
                    <a href="{{ URL::to('/manudailyslot') }}">&nbsp;&nbsp;&nbsp; -Manufacturer Daily Slots</a>
                <a href="{{ URL::to('/plots_dailyslots') }}">&nbsp;&nbsp;&nbsp; -Plots Daily Slots</a>
                <a href="{{ URL::to('/matirialslot') }}">&nbsp;&nbsp;&nbsp; -Materialhub Details</a>
                <a href="{{ URL::to('/retailerslot') }}">&nbsp;&nbsp;&nbsp; -Retailers Details</a>
                
               
                    <a href="{{ URL::to('/projectreport')}}">&nbsp;&nbsp;&nbsp; -Today's Project Report</a>
              </div>
              <a href="{{ URL::to('/projectDetailsForTL') }}">&nbsp;&nbsp;&nbsp; -Project Search</a>
              <a href="{{ URL::to('/') }}/assignListSlots">&nbsp;&nbsp;&nbsp; -Assign Subwards</a>
              <a href="{{ URL::to('/') }}/listatt">&nbsp;&nbsp;&nbsp; -Listing Engineer Attendance</a>
        </div>  
       
     <!-- <a href="#" data-toggle="collapse" data-target="#agent">Field Agents &#x21F2;</a>
      <div id="agent" class="collapse">
          <a href="{{ URL::to('/') }}/tlmaps">&nbsp;&nbsp;&nbsp; -Listing Engineer</a> 
          <a href="{{ URL::to('/tltracking') }}">&nbsp;&nbsp;&nbsp; -Account Executive</a>
      </div> -->
      <a href="#" data-toggle="collapse" data-target="#agent">Field and Office logins&#x21F2;</a>
      <div id="agent" class="collapse">
          <a href="{{ URL::to('/') }}/teamlisteng">&nbsp;&nbsp;&nbsp; -Listing Engineer</a> 
          <!-- <a href="{{ URL::to('/') }}/teamacceng"> &nbsp;&nbsp;&nbsp; -Account Executive</a> -->
          <a href="{{ URL::to('/') }}/ofcemp"> &nbsp;&nbsp;&nbsp; -Sales Engineer</a>
          <!-- <a href="{{ URL::to('/') }}/listatt">&nbsp;&nbsp;&nbsp; -Listing Engineer Attendance</a>  -->
          <!-- <a href="{{ URL::to('/') }}/allteamleader">&nbsp;&nbsp;&nbsp; -Team Leaders</a> 
          <a href="{{ URL::to('/') }}/allsaleseng">&nbsp;&nbsp;&nbsp; -Sales Engineer</a> --> 
      </div> 
     
</div>  
@endif
@elseif(Auth::user()->group_id == 4 && Auth::user()->department_id == 1)  
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" onclick="closeNav()">&times;</a>
      <!-- <a href="{{ URL::to('/assigntl') }}">Assign Team Leaders </a> -->
      <a href="{{ URL::to('/noneed') }}">Delete Numbers</a>
       <a href="{{URL::to('/getprojectsize') }}">Listed Projects & Sizes </a> 
     <a href="#" data-toggle="collapse" data-target="#adds">Reports &#x21F2;</a>
       <div id="adds" class="collapse">
        <a href="{{URL::to('/projectandward') }}">Project Report</a> 
        <a href="{{URL::to('/manureport') }}">Manufactureres Report</a>
        <a href="{{URL::to('/rejectinvoice') }}">Rejected Invoice Details </a>
         <a href="{{ URL::to('/SMSSentreport') }}">SMS Sent Report</a> 
           <a href="{{ URL::to('/wardreport') }}">Ward Assigned Report</a>
              
    
    <a href="{{ URL::to('/newActivityLog') }}">Projects Updated Report</a>
                 <a href="{{ URL::to('/manuupdate') }}">Manufacturer Updated Report</a>

       </div>

         <a href="#" data-toggle="collapse" data-target="#addss">Customer Details &#x21F2;</a>
       <div id="addss" class="collapse">
        <a href="{{URL::to('/customermap') }}">Customer Details</a>
        <a href="{{URL::to('/assigncustomers') }}">Assign Customers To Visit</a>

        <a href="{{URL::to('/customervisit') }}">-Assigned Customers For Field Visit</a>
       <a href="{{ URL::to('/details') }}">Assign Customers</a>
        <a href="{{URL::to('/assignvistedcustomer') }}">Visited Customers</a>

        <a href="{{URL::to('/customerbank') }}">Customer Transactions Details </a>
         <a href="{{ URL::to('/customer') }}">Assigned Customers</a>
        <a href="{{URL::to('/customerledger') }}">Customer Ledger</a>

           <a href="{{ URL::to('/getcustomerinvoices') }}">Customer Invoice Destials</a>
           <a href="{{ URL::to('/ledger') }}">Ledger Sheet</a>
       </div>

 <a href="#" data-toggle="collapse" data-target="#addsss">Projects And Manufacturers &#x21F2;</a>
       <div id="addsss" class="collapse">
         <a href="{{ URL::to('/unverifiedManufacturers') }}">Unverifed Manufacturers</a>
           <a href="{{ URL::to('/customermanu') }}">Assigned Manufacturer Customers</a>
           <a href="{{ URL::to('/blocked_projects') }}">Blocked Projects</a>
           <a href="{{ URL::to('/blocked_manu') }}">Blocked Manufacturer</a>
       </div>
       
                @if(Auth::user()->id == 7)
              <a href="{{ URL::to('/getprojectsizedata') }}">Listed ProjectData  Sizes</a>
              @endif
           
        <a href="#" data-toggle="collapse" data-target="#addssss">All Other Links &#x21F2;</a>
       <div id="addssss" class="collapse">
        <a href="{{URL::to('/targetrem') }}"> -Target Reminder</a> 
        <a href="{{URL::to('/lcoorders') }}">&nbsp;&nbsp;&nbsp;Delivery details</a>
        <a href="{{URL::to('/lcoorders') }}"> -Delivery details</a>
       <a href="{{ URL::to('/marketing') }}">Add Products and Brand</a>
       <a href="{{ URL::to('/setprice') }}">Price setting based on designation</a>
       <a href="{{ URL::to('/cancelorders') }}">Canceled Orders</a>
         <a  href="{{ URL::to('/')}}/lebrands">Brands</a>
        <a href="{{ URL::to('/') }}/marketingvendordetails">Vendor details</a>
        <a href="{{ URL::to('/viewManufacturer') }}"> Manufacter Details</a>
        <a href="{{ URL::to('/') }}/teamkra"> Add KRA to Operation and Sales</a>
        <a href="{{ URL::to('/') }}/kra">KRA</a> 
       <a href="{{ URL::to('/') }}/teamlatelogin">Late Logins</a>
       <a href="{{ URL::to('/') }}/breaks">BreakTime</a>

       </div>    
          
      <!-- <a href="{{ URL::to('/assigntl') }}"></a> -->
      <a href="#" data-toggle="collapse" data-target="#so"> Sales Officers &#x21F2;</a>
    <div id="so" class="collapse">
        <a href="{{ URL::to('/cat') }}">&nbsp;&nbsp;&nbsp; - Assign Category</a>
        <a href="{{ URL::to('/catofficer') }}">&nbsp;&nbsp;&nbsp; -Category Officers Report </a>

    </div>
       <a href="#" data-toggle="collapse" data-target="#add">Add &#x21F2;</a>
    <div id="add" class="collapse">
      <a  href="{{ URL::to('/')}}/listingEngineer">&nbsp;&nbsp;&nbsp; - Add New Project</a>
      <a  href="{{ URL::to('/')}}/addManufacturer">&nbsp;&nbsp;&nbsp; - Add New Manufacturer</a>
      <a  href="{{ URL::to('/')}}/inputview">&nbsp;&nbsp;&nbsp; - Add New Enquiry</a>
    <a href="{{ URL::to('/') }}/manuenquiry">&nbsp;&nbsp;&nbsp; - Add Manufacturer  Enquiry</a>
    <a href="{{ URL::to('/') }}/Materialhub">&nbsp;&nbsp;&nbsp; - Add Materialhub  </a>
    <a href="{{ URL::to('/') }}/Retailers">&nbsp;&nbsp;&nbsp; - Add Retailers  </a>
    
      

    </div>
     
    
     <a href="#" data-toggle="collapse" data-target="#sales">Sales &#x21F2;</a>

        <div id="sales" class="collapse">

              <a href="{{ URL::to('/orders') }}">&nbsp;&nbsp;&nbsp; -Orders</a>
              <a href="{{ URL::to('/financeDashboard') }}">&nbsp;&nbsp;&nbsp; - Confirmed Orders</a>
              <a href="{{ URL::to('/getquotation') }}">&nbsp;&nbsp;&nbsp; - Get Quotation</a>
              <a href="{{ URL::to('/allprice') }}">&nbsp;&nbsp;&nbsp; -Products Prices</a>
               <a href="{{ URL::to('/updateprice') }}">Add GST to categories and brands</a>              
               <a href="{{ URL::to('/tlsalesreports') }}">&nbsp;&nbsp;&nbsp; -Sales Engineer Report</a>
              <a href="{{ URL::to('/') }}/tlenquirysheet">&nbsp;&nbsp;&nbsp; -Enquiry Sheet</a>
              <a href="{{ URL::to('/') }}/manuenquirysheet">&nbsp;&nbsp;&nbsp; -Manufacturer Enquiry Sheet</a>
              <a href="{{ URL::to('/')}}/enquiryCancell?project=project">&nbsp;&nbsp;&nbsp; -Project Enquiry cancelled</a>
              <a href="{{ URL::to('/')}}/enquiryCancell?project=manu">&nbsp;&nbsp;&nbsp; -Manufacturer Enquiry cancelled</a>

             
              <a href="{{ URL::to('/assign_project') }}">&nbsp;&nbsp;&nbsp; -Assign Project</a>
              <a href="{{ URL::to('/assign_number') }}">&nbsp;&nbsp;&nbsp; -Assign Phone Numbers</a>
              <a href="{{ URL::to('/assign_enquiry') }}">&nbsp;&nbsp;&nbsp; -Assign Enquiry</a>
              <a href="{{ URL::to('/enquiryassign') }}">&nbsp;&nbsp;&nbsp; -Assign Customer Enquiry</a>

              <a href="{{ URL::to('/assign_manufacturer') }}">&nbsp;&nbsp;&nbsp; -Assign Manufacturers</a>
        </div>
     <a href="#" data-toggle="collapse" data-target="#operation">Operation &#x21F2;</a>
        <div id="operation" class="collapse">
              <a href="{{ URL::to('/') }}/tlmaps">&nbsp;&nbsp;&nbsp; -Maps</a> 
             <!--  <a href="{{ URL::to('/tltracking') }}">&nbsp;&nbsp;&nbsp; -Tracking</a> -->

               <a href="{{ URL::to('/') }}/Unupdated">&nbsp;&nbsp;&nbsp; -Total Updated Projects</a>
               <a href="{{ URL::to('/') }}/dateUnupdated">&nbsp;&nbsp;&nbsp; - UnUpdated Projects</a>
               <a href="{{ URL::to('/') }}/unupdatedmanu">&nbsp;&nbsp;&nbsp; -Total UnUpdated Manufacturers</a>

               
               <a href="{{ URL::to('/') }}/unverifiedProjects">&nbsp;&nbsp;&nbsp; -Unverified Projects</a>
              <a href="{{ URL::to('/') }}/projectWithNotes">&nbsp;&nbsp;&nbsp; -Projects With Notes</a>
               <a href="#" data-toggle="collapse" data-target="#dailyslot">&nbsp;&nbsp;&nbsp;Daily Slots &#x21F2;</a>
              <div id="dailyslot" class="collapse">


                    <a href="{{ URL::to('/dailyslots') }}">&nbsp;&nbsp;&nbsp; -Projects Daily Slots</a>
                    <a href="{{ URL::to('/manudailyslot') }}">&nbsp;&nbsp;&nbsp; -Manufacturer Daily Slots</a>
                <a href="{{ URL::to('/plots_dailyslots') }}">&nbsp;&nbsp;&nbsp; -Plots Daily Slots</a>
                <a href="{{ URL::to('/matirialslot') }}">&nbsp;&nbsp;&nbsp; -Materialhub Details</a>
                <a href="{{ URL::to('/retailerslot') }}">&nbsp;&nbsp;&nbsp; -Retailers Details</a>
                
               
                    <a href="{{ URL::to('/projectreport')}}">&nbsp;&nbsp;&nbsp; -Today's Project Report</a>
              </div>
              <a href="{{ URL::to('/projectDetailsForTL') }}">&nbsp;&nbsp;&nbsp; -Project Search</a>
              <a href="{{ URL::to('/') }}/assignListSlots">&nbsp;&nbsp;&nbsp; -Assign Subwards</a>
              <a href="{{ URL::to('/') }}/listatt">&nbsp;&nbsp;&nbsp; -Listing Engineer Attendance</a>
        </div>  
       
     <!-- <a href="#" data-toggle="collapse" data-target="#agent">Field Agents &#x21F2;</a>
      <div id="agent" class="collapse">
          <a href="{{ URL::to('/') }}/tlmaps">&nbsp;&nbsp;&nbsp; -Listing Engineer</a> 
          <a href="{{ URL::to('/tltracking') }}">&nbsp;&nbsp;&nbsp; -Account Executive</a>
      </div> -->
      <a href="#" data-toggle="collapse" data-target="#agent">Field and Office logins&#x21F2;</a>
      <div id="agent" class="collapse">
          <a href="{{ URL::to('/') }}/teamlisteng">&nbsp;&nbsp;&nbsp; -Listing Engineer</a> 
          <!-- <a href="{{ URL::to('/') }}/teamacceng"> &nbsp;&nbsp;&nbsp; -Account Executive</a> -->
          <a href="{{ URL::to('/') }}/ofcemp"> &nbsp;&nbsp;&nbsp; -Sales Engineer</a>
          <!-- <a href="{{ URL::to('/') }}/listatt">&nbsp;&nbsp;&nbsp; -Listing Engineer Attendance</a>  -->
          <!-- <a href="{{ URL::to('/') }}/allteamleader">&nbsp;&nbsp;&nbsp; -Team Leaders</a> 
          <a href="{{ URL::to('/') }}/allsaleseng">&nbsp;&nbsp;&nbsp; -Sales Engineer</a> --> 
      </div> 
     
</div>  

@elseif(Auth::user()->group_id == 17 && Auth::user()->department_id == 2)
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" onclick="closeNav()">&times;</a>
    <a href="{{ URL::to('/allprice') }}">Products Prices</a>
         <a href="{{ URL::to('/customer') }}">Assigned Customers</a>
           <a href="{{ URL::to('/customermanu') }}">Assigned Manufacturer Customers</a>

         <a href="{{ URL::to('/') }}/assignclosedcontractors"> Assigned Closed Contractor </a>
   
   <a href="{{ URL::to('/') }}/projectsUpdate"> Assigned Task </a>
    <a href="{{ URL::to('/') }}/sales_manufacture" id="updates"  >Assigned Manufacture</a>
    <a href="{{ URL::to('/') }}/sms" >Assigned Phone Numbers</a>
    <a href="{{ URL::to('/projectDetailsForTL') }}">Project Search</a>
    <a href="{{ URL::to('/') }}/scenquirysheet">Enquiry Sheet</a>
    <a href="{{ URL::to('/getquotation') }}">Get Quotation</a>
    <a href="{{ URL::to('/dailyslots') }}">Daily Slots</a>
    <a href="{{ URL::to('/manudailyslot') }}">Manufacturer Daily Slots</a>
    <a href="{{ URL::to('/') }}/enquirywise" style="font-size:1.1em">Assigned Enquiry </a>   
    <a href="{{ URL::to('/') }}/scmaps">Maps</a>
    <a href="{{ URL::to('/') }}/kra">KRA</a>
</div>

@elseif(Auth::user()->group_id == 23 && Auth::user()->department_id == 2)
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" onclick="closeNav()">&times;</a>
      <a href="#" data-toggle="collapse" data-target="#add">Add &#x21F2;</a>
         <div id="add" class="collapse">
      <a  href="{{ URL::to('/')}}/listingEngineer">Add New Project</a>
      <a  href="{{ URL::to('/')}}/addManufacturer"> Add New Manufacturer</a>
      <a  href="{{ URL::to('/')}}/inputview"> Add New Enquiry</a>
    <a href="{{ URL::to('/') }}/manuenquiry">Add Manufacturer  Enquiry</a>

    </div>
     <a href="{{ URL::to('/marketing') }}">Add Products and Brand</a>
         <a href="{{ URL::to('/customer') }}">Assigned Customers</a>
           <a href="{{ URL::to('/customermanu') }}">Assigned Manufacturer Customers</a>

     
    <a href="{{ URL::to('/') }}/projectsUpdate" >Projects</a>
    <a href="{{ URL::to('/') }}/enquirywise">Enquiries</a>
    <a href="{{ URL::to('/') }}/inputview">Add Enquiry</a>
    <a href="{{ URL::to('/') }}/manuenquiry">Add Manufacturer  Enquiry</a>

 

    <a href="{{ URL::to('/getquotation') }}">Get Quotation</a>
    <a href="{{ URL::to('/') }}/projectsUpdate?interested=interest">Interested Customers</a>   
    <a href="{{ URL::to('/') }}/kra">KRA</a>
</div>
@elseif(Auth::user()->group_id == 8 && Auth::user()->department_id == 3)
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" onclick="closeNav()">&times;</a>
     <a href="{{ URL::to('/marketing') }}">Add Products and Brand</a>
     <a href="{{ URL::to('/marketmanufacturerdetails') }}">Manufacturer Details</a>
     <a href="{{ URL::to('/') }}/marketingvendordetails">Vendor details</a>
     <a href="{{ URL::to('/marketingpricing') }}">Pricing</a>
    <a href="{{ URL::to('/') }}/viewInvoices">Invoices</a>
    <a href="{{ URL::to('/') }}/pending">Pending Invoices</a>
      <a href="{{ URL::to('/mrenquirysheet') }}">Enquiry Sheet</a>
      <a href="{{ URL::to('/ordersformarketing') }}">Orders</a>
      <a href="{{ URL::to('/getquotation') }}">Get Quotation</a>
       <a href="{{ URL::to('payment') }}">Delivery Order Details</a>
       <a href="{{ URL::to('checkdetailes') }}">Cheq Details</a>

      <a href="{{ URL::to('/') }}/kra">KRA</a>
  </div>
  @elseif(Auth::user()->group_id == 7 && Auth::user()->department_id == 2)
<div id="mySidenav" class="sidenav">
     <a href="javascript:void(0)" onclick="closeNav()">&times;</a>
      <a href="#" data-toggle="collapse" data-target="#addproject2">Dedicated Customers &#x21F2;</a>
          <div id="addproject2" class="collapse">
                <a  href="{{ URL::to('/')}}/dCustomers">&nbsp;&nbsp;&nbsp; -Customer Deatils</a>
         <a  href="{{ URL::to('/')}}/dnumbers">&nbsp;&nbsp;&nbsp; -SMS Numbers</a>
         <a  href="{{ URL::to('/')}}/dprojects">&nbsp;&nbsp;&nbsp; -Projects</a>
         <a  href="{{ URL::to('/')}}/dmanus">&nbsp;&nbsp;&nbsp; -Manufacturers</a>
          <a  href="{{ URL::to('/')}}/denquery">&nbsp;&nbsp;&nbsp; -Enquiries</a>
          </div>

     
      <a href="#" data-toggle="collapse" data-target="#addproject256">Proposed Projects And Manufacturers &#x21F2;</a>
          <div id="addproject256" class="collapse">
                <a  href="{{ URL::to('/')}}/assignppids">&nbsp;&nbsp;&nbsp; -Proposed Projects</a>
                 <a  href="{{ URL::to('/')}}/holdingcust">&nbsp;&nbsp;&nbsp; -Proposed Holding Projects</a>
                <a  href="{{ URL::to('/')}}/assignmpids">&nbsp;&nbsp;&nbsp; -Proposed Manufacturers</a>
                          <a href="{{URL::to('/test') }}">Proposed Phone  Numbers</a>

        <!--  <a  href="{{ URL::to('/')}}/dnumbers">&nbsp;&nbsp;&nbsp; -SMS Numbers</a>
         <a  href="{{ URL::to('/')}}/dprojects">&nbsp;&nbsp;&nbsp; -Projects</a>
         <a  href="{{ URL::to('/')}}/dmanus">&nbsp;&nbsp;&nbsp; -Manufacturers</a>
          <a  href="{{ URL::to('/')}}/denquery">&nbsp;&nbsp;&nbsp; -Enquiries</a> -->
          </div>

          <a href="#" data-toggle="collapse" data-target="#addprojectc">Closed Customers &#x21F2;</a>
          <div id="addprojectc" class="collapse">
            <a href="{{ URL::to('/') }}/assignclosedcontractors"> Assigned Closed Contractor </a>
            <a href="{{ URL::to('/') }}/csite"> Assigned Closed Site Engineers </a>
            <a href="{{ URL::to('/') }}/cbuilders"> Assigned Closed Builders </a>
            <a href="{{ URL::to('/') }}/cowners"> Assigned Closed Owners </a>

               
          </div>


        


         <a href="{{ URL::to('/') }}/projectsUpdate"> Assigned Task </a>
         <a href="{{ URL::to('/customer') }}">Assigned Customers</a>
         <a href="{{ URL::to('/customermanu') }}">Assigned Manufacturer Customers</a>
         <a href="{{ URL::to('/minibreack') }}">BreakTime Mini Report</a>
    <a href="{{ URL::to('/') }}/sales_manufacture" id="updates" >Assigned Manufacture</a>
    <a href="{{ URL::to('/') }}/enquirywise" style="font-size:1.1em">Assigned Enquiry </a>  
    <a href="{{ URL::to('/') }}/getassignenquiry" style="font-size:1.1em">Assigned customer Enquiry </a>
    <a href="{{ URL::to('/') }}/getcustomerenqlist" style="font-size:1.1em">Assigned Existing customer Enquiry </a>   
    
    
    
    <a href="{{ URL::to('/allprice') }}">Products Prices</a>
    
    <a href="{{ URL::to('/') }}/sms"  >Assigned Phone Numbers</a>
    <a href="{{ URL::to('/projectDetailsForTL') }}">Project Search</a>

    <a href="{{ URL::to('/') }}/inputview">Add Enquiries</a>
    <a href="{{ URL::to('/') }}/manuenquiry">Add Manufacturer  Enquiry</a>
      <a href="{{ URL::to('/getquotation') }}">Get Quotation</a>
    <!--  <a href="{{ URL::to('/mrenquirysheet') }}">Enquiry Sheet</a>  -->
      <!-- <a href="{{ URL::to('/') }}/projectsUpdate" id="updates" >Add Enquiry</a> -->
    <!--  <a href="{{ URL::to('/') }}/status_wise_projects" id="updates" >Statuswise Projects</a>
     <a  href="{{ URL::to('/') }}/date_wise_project" >Datewise Projects</a> -->
    <a href="{{ URL::to('/') }}/followupproject" >Follow Up projects</a>
    <a href="#" data-toggle="collapse" data-target="#addproject">Add Project &#x21F2;</a>
          <div id="addproject" class="collapse">
                <a  href="{{ URL::to('/')}}/listingEngineer">&nbsp;&nbsp;&nbsp; -Add New Project</a>
         <a  href="{{ URL::to('/')}}/seroads">&nbsp;&nbsp;&nbsp; -Update Projects</a>
         <a  href="{{ URL::to('/')}}/addManufacturer">&nbsp;&nbsp;&nbsp; -Add New Manufacturer</a>
         <a  href="{{ URL::to('/')}}/updateManufacturer">&nbsp;&nbsp;&nbsp; -Update Manufacturer</a>
          <a  href="{{ URL::to('/')}}/manu_map">&nbsp;&nbsp;&nbsp; -Manufacturers Map</a>
          <a  href="{{ URL::to('/customermanu') }}">&nbsp;&nbsp;&nbsp; -Assigned Manufacturer Customers</a>
          <a  href="{{ URL::to('/')}}/lebrands">&nbsp;&nbsp;&nbsp; -Brands</a>
          </div>


   









    <a href="{{ URL::to('/') }}/myreport" >My Report</a>
    <a href="{{ URL::to('/') }}/kra" >KRA</a>

  </div>
  @elseif(Auth::user()->group_id == 14)
   <div id="mySidenav" class="sidenav">
     <a href="javascript:void(0)" onclick="closeNav()">&times;</a>
            <a href="{{ URL::to('/') }}/amhumanresources">HR</a>
            <a href="{{ URL::to('/') }}/mhemployee">MAMAHOME Employee</a>
            <a href="{{ URL::to('/') }}/minibreack">Employees breaktimes</a>
        <a href="{{ URL::to('/anr') }}">Reports</a>
            <a href="{{ URL::to('/') }}/amviewattendance">Attendance</a>
            <a href="{{ URL::to('/') }}/newamviewattendance">New Attendance</a>
            <a href="{{ URL::to('/') }}/check">HR Files and Checklist</a>
            <a href="{{ URL::to('/') }}/assets">Add Assets</a>
            <a href="{{ URL::to('/') }}/assignassets">Assign Assets to Department</a>
            <a href="{{ URL::to('/') }}/video"> Add Training Video</a>
       <!--  <a href="#" data-toggle="collapse" data-target="#agent">Employee Attendance &#x21F2;</a> -->
        <div id="agent" class="collapse">
            <a href="{{ URL::to('/') }}/seniorteam">&nbsp;&nbsp;&nbsp; -Senior Team Leader</a> 
            <!-- <a href="{{ URL::to('/') }}/seniorteam1">&nbsp;&nbsp;&nbsp; -Senior Team Leader1</a> -->
            <a href="{{ URL::to('/') }}/teamleader">&nbsp;&nbsp;&nbsp; -Team Leaders</a> 
            <!-- <a href="{{ URL::to('/') }}/teamleader1">&nbsp;&nbsp;&nbsp; -Team Leaders1</a> -->
            <a href="{{ URL::to('/') }}/saleseng">&nbsp;&nbsp;&nbsp; -Sales Engineer</a> 
            <a href="{{ URL::to('/') }}/marketexe"> &nbsp;&nbsp;&nbsp; -Marketing </a>
            <a href="{{ URL::to('/') }}/teamlisteng">&nbsp;&nbsp;&nbsp; -Listing Engineer</a> 
             <a href="{{ URL::to('/') }}/listatt">&nbsp;&nbsp;&nbsp; -Listing Engineer Attendance</a> 
            <a href="{{ URL::to('/') }}/teamacceng"> &nbsp;&nbsp;&nbsp; -Account Executive</a>
            <a href="{{ URL::to('/') }}/market"> &nbsp;&nbsp;&nbsp; -Market Researcher</a>

        </div> 
        <a href="#" data-toggle="collapse" data-target="#foffice">Field and Office Logins &#x21F2;</a>
        <div id="foffice" class="collapse">
            <a href="{{ URL::to('/') }}/teamlisteng">&nbsp;&nbsp;&nbsp; -Listing Engineer</a>  
            <!-- <a href="{{ URL::to('/') }}/teamacceng"> &nbsp;&nbsp;&nbsp; -Account Executive</a> -->
            <a href="{{ URL::to('/') }}/ofcemp"> &nbsp;&nbsp;&nbsp; -Office Employees</a>
        </div> 
         <a href="{{ URL::to('/') }}/hrlatelogins">Late Logins</a>
         <a href="{{ URL::to('/') }}/holidays">Holiday List</a>
         <a href="{{ URL::to('/') }}/breaks">BreakTime</a>
       <!--  <a href="{{ URL::to('/') }}/breaktimes">Break Times</a> -->
    </div>
        @endif
        @endif
                
                <form method="POST"  action="{{ URL::to('/') }}/logintime" >
                  {{ csrf_field() }}
                                   <!--  <input  class="hidden" type="text" name="longitude" value="{{ old('longitude') }}" id="longitudeapp"> 
                                    <input  class="hidden" type="text" name="latitude" value="{{ old('latitude') }}" id="latitudeapp">
                                    <input class="hidden" id="addressapp" type="text" name="address" value="{{ old('address') }}"> -->
                        <button id="login" class="hidden" onsubmit="show()" type="submit" >Submit</button>
                </form> 
                 <!-- <form method="POST"  action="{{ URL::to('/') }}/emplogouttime" >
                  {{ csrf_field() }}
                    <button id="logout" class="hidden" onsubmit="show()" type="submit" >Submit</button>
                </form> -->
         <!-- <div id="notes" style="margin-top: 30px;height: 200px; width: 200px; color: #7f6c04; background: #f9dd45; border-radius: 10px; border: 0px; font-family: Helvetica, Arial, sans-serif; font-size: 13px; box-shadow: 2px 2px 10px rgba(0,0,0,0.4); overflow: hidden;"></div> -->
        @yield('content')
    </div>

 
    <!-- Scripts -->
    <script type="text/javascript">
       function confirmthis()
    {
        var ans = confirm('Are You Sure You Want To Logout ?');
        if(ans)
        {
            $(document).ready(function(){
              $("#report").modal('show');
          });
        }
    }
    </script>
<script type="text/javascript">
  function startbreak(){
    var id = "hello";
    $.ajax({
        type: 'GET',
        url: "{{ URL::to('/') }}/breaktime",
        async: false,
        data: { id : id},
        success: function(response){  
          setInterval(mytimer, 1000);
        }
    })
  }
  function mytimer(){
    var str = "";
      var now = new Date();

      str += "Your Break Time Started: " + now.getHours() +":" + now.getMinutes() + ":" + now.getSeconds();
      document.getElementById("currentTime").innerHTML = str;
  }
</script>
<script>

      function submitapp(){
        
    document.getElementById("login").form.submit();
  }
   
  </script>
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }
        
        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft= "0";
        }
      </script>
    <script>
        // Get the modal
        var modal = document.getElementById('myModal');
        
        // Get the image and insert it inside the modal - use its "alt" text as a caption
        function display(arg){
            var img = document.getElementById(arg);
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");
            img.onclick = function(){
                modal.style.display = "block";
                modalImg.src = this.src;
                captionText.innerHTML = this.alt;
            }
        }
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("imgClose")[0];      
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() { 
            modal.style.display = "none";
        }
        </script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ URL::to('/') }}/js/countdown.js"></script>

<!-- <script>

  function submitlogout(){
    document.getElementById("logout").form.submit();
  }
</script> -->
 <!-- get location -->
<script src="https://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" charset="utf-8">
  
  function submitapp1(){
    
      if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(
        displayCurrentLocationapp,
        displayErrorapp,
        { 
          maximumAge: 3000, 
          timeout: 5000, 
          enableHighAccuracy: true 
        });
    }else{
      alert("Oops.. No Geo-Location Support !");
    } 
  }
    
    function displayCurrentLocationapp(position){
      var latitude  = position.coords.latitude;
      var longitude = position.coords.longitude;
      document.getElementById("longitudeapp").value = longitude;
      document.getElementById("latitudeapp").value  = latitude;
      getAddressFromLatLangapp(latitude,longitude);
            initMap();
  }
   
  function  displayErrorapp(error){
    console.log("Entering ConsultantLocator.displayErrorapp()");
    var errorType = {
      0: "Unknown error",
      1: "Permission denied by user",
      2: "Position is not available",
      3: "Request time out"
    };
    var errorMessage = errorType[error.code];
    if(error.code == 0  || error.code == 2){
      errorMessage = errorMessage + "  " + error.message;
    }
    alert("Error Message " + errorMessage);
    console.log("Exiting ConsultantLocator.displayErrorapp()");
  }
  function getAddressFromLatLangapp(lat,lng){
    var geocoder = new google.maps.Geocoder();
    var latLng = new google.maps.LatLng(lat, lng);
    geocoder.geocode( { 'latLng': latLng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[0]) {
       
        document.getElementById("addressapp").value = results[0].formatted_address;
        document.getElementById("login").form.submit();
      }
    }else{
        alert("Geocode was not successful for the following reason: " + status);
     }
    });
  }
  // function lll(){
    
  // }
  
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGSf_6gjXK-5ipH2C2-XFI7eUxbHg1QTU"></script>
@if(session('empSuccess'))
  <div class="modal fade" id="empSuccess" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #5cb85c;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Success</h4>
        </div>
        <div class="modal-body">
          <p style="text-align:center;">{!! session('empSuccess') !!}</p>
        </div>
        <div class="modal-footer">
          <button type="button" style="background-color: #c9ced6;" class="btn btn-default" data-dismiss="modal" onClick="window.location.reload()">Close</button>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
  $(document).ready(function(){
      $("#empSuccess").modal('show');
  });
</script>
@endif
@if(session('Latelogin'))
  <div class="modal fade" id="emplate" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #f27d7d;color:white;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Late Login</h4>
        </div>
        <div class="modal-body">
       
          <p style="text-align:center;">{!! session('Latelogin') !!}</p>
             
        </div>
        <div class="modal-footer">
          <button type="button" style="background-color: #c9ced6;" class="btn btn-default" data-dismiss="modal" onClick="window.location.reload()">Close</button>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
  $(document).ready(function(){
      $("#emplate").modal('show');
  });
</script>
@endif
<script>
    function myFunction1() {
        var table = document.getElementById("reports");
        var row = table.insertRow(-1);
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        cell1.innerHTML = "<input required type='text' name='report[]' id='report' class='form-control' placeholder='Report'>";
        cell2.innerHTML = "<input required type='time' name='from[]' id='from' class='form-control'>";
        cell3.innerHTML = "<input required type='time' name='to[]' id='to' class='form-control'>";
    }
    function myDelete1() {
        var table = document.getElementById("reports");
        if(table.rows.length >= {{ 3 }}){
            document.getElementById("reports").deleteRow(-1);
        }
    }
 </script>
@if(session('Success'))
<script>
    swal("success","{{ session('Success') }}","success");
</script>
@endif
@if(session('error'))
<script>
    swal("error","{{ session('error') }}","error");
</script>
@endif
@if(session('earlylogout'))
  <div class="modal fade" id="emplate" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #f27d7d;color:white;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Early logout</h4>
        </div>
        <div class="modal-body">
          <p style="text-align:center;">{!! session('earlylogout') !!}</p>  
        </div>
        <div class="modal-footer">
          <button type="button" style="background-color: #c9ced6;" class="btn btn-default" data-dismiss="modal" onClick="window.location.reload()">Close</button>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
  
  $(document).ready(function(){
      $("#emplate").modal('show');
  });


</script>
@endif
<script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-41c52890748cd7143004e05d3c5f786c66b19939c4500ce446314d1748483e13.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script>
  $('.date').datepicker({
    multidate: true,
    format: 'dd-mm-yyyy' });
      //# sourceURL=pen.js
</script>
<!-- <script type="text/javascript">
  zIndex = 10;
$('#new').click(function(){

    $('#notes')
        .append('\
            <div class="sticky-note-pre ui-widget-content" style="">\
                <div class="handle">&nbsp;<div class="close">&times;</div></div>\
                <div contenteditable class="contents">awesome</div>\
            </div>\
         ')
        .find('.sticky-note-pre')
            //.position where we want it
              

        .end()
        //.do something else to $('#notes')
    ;
    $('.sticky-note-pre')
        .draggable({
            handle: '.handle'    
        })
        .resizable({
            resize: function(){
                var n = $(this);
                n.find('.contents').css({
                    width: n.width() - 40,
                    height: n.height() - 60
                });
            }
        })
        .bind('click hover focus mousedown', function(){
            $(this)
                .css('zIndex', zIndex++)
                .find('.ui-icon')
                    .css('zIndex', zIndex++)
                .end()
            ;
        })
        .find('.close')
            .click(function(){
                $(this).parents('.sticky-note').remove();
            })
        .end()
        .dblclick(function(){
            $(this).remove();
        })
        .addClass('sticky-note')
        .removeClass('sticky-note-pre')
    ;
});

$('#save').click(function(){
    var notes = [], i, note;
    $('.sticky-note').each(function(){
        notes.push($(this).find('.contents').html());
    });
    //do something with notes
    console.log(notes);
});
</script> -->

