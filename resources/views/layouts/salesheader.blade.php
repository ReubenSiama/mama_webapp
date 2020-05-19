
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MamaHome</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ URL::to('/') }}/css/countdown.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/appblade.css" />
</head>
<body>
    <div id="app">
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
                    <a href="#" class="navbar-brand" style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</a>
                    
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @if(Auth::check())
                        <li><a href="{{ URL::to('/') }}/home" style="font-size:1.1em"><b>Home</b></a></li>
                        <li><a href="{{ URL::to('/') }}/eqpipeline" style="font-size:1.1em;font-family:Times New Roman"><b>Enquiry Pipelined</b></a></li>
                        <li><a href="{{ URL::to('/') }}/tltraining" style="font-size:1.1em"><b>Training Video <span class="badge">&nbsp;&nbsp;</span></b></a></li>
                        <li style="padding-top: 10px;">
                        <button id="getBtn"  class="btn btn-success btn-sm hidden-xs hidden-sm" onclick="teamlogin()">Login</button></li>
                        <li style="padding-top: 10px;padding-left: 10px;"> 
                        <button class="btn btn-primary btn-sm hidden-xs hidden-sm" data-toggle="modal" data-target="#break">Break</button>
                       </li>
                        <li style="padding-top: 10px;padding-left: 10px;"> 
                        <button class="btn btn-danger btn-sm hidden-xs hidden-sm" data-toggle="modal" onclick="confirmit()">Logout</button>
                       </li>
                        @endif
                       
                                       
                   
                      
                    
                    </ul>
                
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
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
            <!-- Modal -->
                            <div id="break" class="modal fade" role="dialog">
                              <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content" style="width:50%;" >
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Break Time</h4>
                                  </div>
                                  <div class="modal-body">

                                    <p>Click On Start To Take a Break?</p>
                                  <form id="timer" action="{{ URL::to('/') }}/breaktime" method="POST">
                                      {{ csrf_field() }}
                                    <button type="submit" class="btn btn-success btn-sm">START</button>
                                  </form>
                                  <form id="timer" action="{{ URL::to('/') }}/sbreaktime" method="POST">
                                      {{ csrf_field() }}
                                    <button style="margin-top:-20%;margin-left: 70px;" type="submit" class="btn btn-danger btn-sm">STOP</button>
                                  </form>
                                  </div>
                                  <div class="modal-footer">
                                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                   
                                  </div>
                                </div>

                              </div>
                            </div>
                            <!-- mpdal end -->
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

        <div id="mySidenav" class="sidenav">
          <a href="javascript:void(0)" onclick="closeNav()">&times;</a>
          @if(Auth::check() && Auth::user()->group_id == 7 || Auth::user()->group_id == 1)     
            <a href="{{ URL::to('/') }}/projectsUpdate"> Assigned Task </a>
         <a href="{{ URL::to('/customer') }}">Assigned Customers</a>
           <a href="{{ URL::to('/customermanu') }}">Assigned Manufacturer Customers</a>
         
    <a href="{{ URL::to('/') }}/sales_manufacture" id="updates" >Assigned Manufacture</a>
    <a href="{{ URL::to('/') }}/enquirywise" style="font-size:1.1em">Assigned Enquiry </a>   
     
    <a href="{{ URL::to('/allprice') }}">Products Prices</a>

     <a href="{{ URL::to('/') }}/sms"  >Assigned Phone Numbers</a>
      <a href="{{ URL::to('/projectDetailsForTL') }}">Project Search</a>
      <a href="{{ URL::to('/') }}/inputview">Add Enquiries</a>
    <a href="{{ URL::to('/') }}/manuenquiry">Add Manufacturer  Enquiry</a>
      <a href="{{ URL::to('/getquotation') }}">Get Quotation</a>
    <a href="{{ URL::to('/') }}/followupproject" >Follow Up projects</a>
    <a href="{{ URL::to('/') }}/myreport" >My Report</a>
    <a href="{{ URL::to('/') }}/kra" >KRA</a>
         
        @endif
        </div>
                <!-- <form method="POST"  action="{{ URL::to('/') }}/teamlogin" >
                  {{ csrf_field() }}
                    <button id="team" class="hidden" onsubmit="show()" type="submit" >Submit</button>
                </form> -->
                <form method="POST"  action="{{ URL::to('/') }}/teamlogin" >
                  {{ csrf_field() }}
                                    <!-- <input  class="hidden" type="text" name="longitude" value="{{ old('longitude') }}" id="longitudeteam"> 
                                    <input  class="hidden" type="text" name="latitude" value="{{ old('latitude') }}" id="latitudeteam">
                                    <input class="hidden" id="addressteam" type="text" placeholder="Full Address" class="form-control input-sm" name="address" value="{{ old('address') }}"> -->
                        <button id="team" class="hidden" onsubmit="show()" type="submit" >Submit</button>
                </form>
                 <form method="POST"  action="{{ URL::to('/') }}/teamlogout" >
                  {{ csrf_field() }}
                    <button id="lteam" class="hidden" onsubmit="show()" type="submit" >Submit</button>
                </form>
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ URL::to('/') }}/js/countdown.js"></script>
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
    @if(session('Success'))
    <script>
    $( document ).ready(function(){go(50)});
        $('#ok').click(function(){go(500)});
        
        function go(nr) {
          $('.bb').fadeToggle(200);
          $('.message').toggleClass('comein');
          $('.check').toggleClass('scaledown');
          $('#go').fadeToggle(nr);
        }
    </script>
    @endif
     @if(session('error'))
    <script>
    $( document ).ready(function(){go(50)});
        $('#ok').click(function(){go(500)});
        
        function go(nr) {
          $('.bb').fadeToggle(200);
          $('.message').toggleClass('comein');
          $('.check').toggleClass('scaledown');
          $('#go').fadeToggle(nr);
        }
    </script>
    @endif
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

  function teamlogin(){
    document.getElementById("team").form.submit();
  }
  function teamlogout(){
    document.getElementById("lteam").form.submit();
  }

  function confirmit()
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

@if(session('TeamSuccess'))
  <div class="modal fade" id="teamSuccess" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #5cb85c;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Success</h4>
        </div>
        <div class="modal-body">
          <p style="text-align:center;">{!! session('TeamSuccess') !!}</p>
        </div>
        <div class="modal-footer">
          <button type="button" style="background-color: #c9ced6;" class="btn btn-default" data-dismiss="modal" onClick="window.location.reload()">Close</button>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
  $(document).ready(function(){
      $("#teamSuccess").modal('show');
  });
</script>
@endif
@if(session('TeamLate'))
  <div class="modal fade" id="teamlate" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #f27d7d;color:white;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Late Login</h4>
        </div>
        <div class="modal-body">
          <!-- <form action="{{ URL::to('/') }}/teamlate" method="POST" > -->
          <p style="text-align:center;">{!! session('TeamLate') !!}</p>
             <!-- {{ csrf_field() }}
          <center><button type="submit" class="btn btn-success" >Submit</button></center>
         </form> -->
        </div>
        <div class="modal-footer">
          <button type="button" style="background-color: #c9ced6;" class="btn btn-default" data-dismiss="modal" onClick="window.location.reload()">Close</button>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript">
  $(document).ready(function(){
      $("#teamlate").modal('show');
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
</body>
</html>
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
