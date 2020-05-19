<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="border-color:#f4811f">
                <div class="panel-heading text-center" style="background-color:#f4811f;padding:20px;">
                    <span style="color:white;font-weight:bold;"> Assign Customers To Visit </span>
                    @if(session('Error'))
                        <div class="alert-danger pull-right">{{ session('Error')}}</div>
                    @endif
                     <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color:black;"></i></button>
                </div>
                <div class="panel-body">  
                     
                 
             <div class="panel-body">
             <table class="table table-responsive table-striped table-hover" class="table">
                        <thead>
                            <th style="width:15%">Name</th>
                            <th style="width:15%">Designation</th>
                           <th style="width:15%">Action </th>
                           <th></th>

                           <!-- <th style="width:15%">Status </th> -->
                            
                          </thead>
                           <tr>
                        
                          @foreach($users as $user)  
                            <td>{{$user->name}}</td>
                            <td>{{ $user->group_name }}</td>
                           
                           
                            
                             <td><button onclick="makeUserId('{{ $user->id }}')" type="button" style="background-color: #f4811f;color: white" data-toggle="modal" id="#myModal"  data-target="#myModal"  class="btn  pull-left">Assign</button></td>
                             
                          
                             

                             <!-- <td><button  type="button" style="background-color: #757575;color: white" data-toggle="modal" id="#myModal5"  data-target="#myModal5{{ $user->id }}"  class="btn  pull-left">Assign Instructions</button></td> -->
                         
                          </tr>
            @endforeach
           
              
 </table>
    
        

<form method="POST" name="myform" action="{{ URL::to('/') }}/customerstore" enctype="multipart/form-data">
  {{ csrf_field() }}
  <input type="hidden" id="userId" name="user_id">
 <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="background-color: rgb(244, 129, 31);padding:5px;color:white;">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="text-align: center;">Assign Project</h4>
      </div>
      <div class="modal-body">
        <div id="first">
        <div id="wards">  
        <h4 style="background-color:#9e9e9e;color:white;border: 1px solid gray;width:25%;font-family: Times;padding:5px;border-radius: 5px;margin-left: 20px;">Choose Ward</h4>
        <input style=" padding: 5px;margin-left: 23px;"  type="checkbox" value="ALL"  name="all">&nbsp;&nbsp;All
        <br></br>
        <div class="row" style="margin-left: 8px;">

        @foreach($wards as $ward)
        <div class="col-sm-2">
            <input id="wardid{{ $ward->id }}" onclick="hide('{{ $ward->id }}')"  style=" padding: 5px;" data-toggle="modal" data-target="#myModal{{ $ward->id }}" type="checkbox" value="{{ $ward->id }}"  name="ward[]">&nbsp;&nbsp;{{ $ward->ward_name }}
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          </div>
        @endforeach
        </div>
        </div>
        </div>
         @foreach($wardsAndSub as $ward)
          <div id="subwards{{ $ward['ward'] }}" class="hidden">
           <h4 style="background-color:#9e9e9e;color:white;border: 1px solid gray;width:25%;font-family: Times;padding:5px;border-radius: 5px;margin-left:20px;">Choose Subward</h4>
            <label style="margin-left: 23px;" class="checkbox-inline"><input id="check{{ $ward['ward'] }}" type="checkbox" name="sub" value="submit" onclick="checkall('{{$ward['ward']}}');">All</label>
          <br><br>    
          <div id="ward{{ $ward['ward'] }}">
          <div class="row" style="margin-left: 8px;"> 
              @foreach($ward['subWards'] as $subward)
              <div class="col-sm-2" >
                    <label class="checkbox-inline">
                      
                      <input  type="checkbox"  name="subward[]" value="{{$subward->id}}">
                      &nbsp;&nbsp;{{$subward->sub_ward_name}}
                     </label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
              @endforeach
          </div>
          </div>   
          </div>
          <!-- <button id="back{{ $ward['ward'] }}" onclick="back('{{$ward['ward'] }}')" type="button" class="hidden">Back</button> -->
          @endforeach
          
           <center>
                  <button type="submit" id="submit" class="btn btn-success">Submit Data</button>
                </center>
    </div>
  </div>
</div>
</div>  
</form>   
</div>
  
  </div>
</div>
</div>
</div>
</div>   
@if(session('success'))
<script>
    swal("success","{{ session('success') }}","success");
</script>
@endif

<script>
function makeUserId(arg){
  document.getElementById("userId").value = arg;
}
 var current = "first";
  function pageNext(){


         if(current == 'first')
        {
                document.getElementById("first").className = "hidden";
                document.getElementById("second").className = "";
                document.getElementById("prev").className = "previous";
                document.getElementById("next").className = "hidden";
                current = "second";
        }   
     else { 
            document.getElementById("second").className = "next";
            document.getElementById("third").className = "";
            current = "third";
            document.getElementById("prev").className = "hidden";
                document.getElementById("next").className = "hidden";
            // document.getElementById("next").className = "hidden";
          }
  
   } 
 
 function pagePrevious()
 {
  
        document.getElementById("next").className = "next";
        document.getElementById("prev").className = "previous";
         if(current == 'third'){
            document.getElementById("third").className = "hidden";
            document.getElementById("second").className = "";
            document.getElementById('headingPanel').innerHTML = 'Assign Stages';
            current = "second"
        }else if(current == 'second'){
            document.getElementById("second").className = "hidden";
            document.getElementById("first").className = "";
            document.getElementById('headingPanel').innerHTML = 'Assign Wards';
            current = "first"
        }
       else{
            document.getElementById("next").className = "disabled";
        }
      }
</script>



<style type="text/css">
  hr.style-two {
border: 0;
height: 0;
border-top: 1px solid rgba(0, 0, 0, 0.1);
border-bottom: 1px solid rgba(255, 255, 255, 0.3);
}
</style>
<script>
function check(arg){
    var input = document.getElementById(arg).value;
    if(input){
    if(isNaN(input)){
      while(isNaN(document.getElementById(arg).value)){
      var str = document.getElementById(arg).value;
      str     = str.substring(0, str.length - 1);
      document.getElementById(arg).value = str;
      }
    }
    else{
      input = input.trim();
      document.getElementById(arg).value = input;
    }
    if(arg == 'ground' || arg == 'basement'){
      var basement = parseInt(document.getElementById("basement").value);
      var ground   = parseInt(document.getElementById("ground").value);
      if(!isNaN(basement) && !isNaN(ground)){
        var floor    = 'B('+basement+')' + ' + G + ('+ground+') = ';
        sum          = basement+ground+1;
        floor       += sum;
      
        if(document.getElementById("total").innerHTML != null)
          document.getElementById("total").innerHTML = floor;
        else
          document.getElementById("total").innerHTML = '';
      }
    }
  }
    return false;
  }
</script>
<script type="text/javascript">

function hide(arg){
  // document.getElementById('wards').className = "hidden";
  if(document.getElementById('wardid'+arg).checked == true){
  document.getElementById('subwards'+arg).className = "";
  document.getElementById('back'+arg).className = "btn btn-primary pull-left";  
  }
  else{
     document.getElementById('subwards'+arg).className = "hidden";
  }
}
function back(arg){
  document.getElementById('wards').className = "";
  document.getElementById('subwards'+arg).className = "hidden";
  document.getElementById('back'+arg).className = "hidden";
}
</script>



<script language="JavaScript">
  function selectAll(source) {
    checkboxes = document.getElementsByName('stage[]');
    for(var i in checkboxes)
      checkboxes[i].checked = source.checked;
  }
</script>

<script>
function checkall(arg){
var clist = document.getElementById('ward'+arg).getElementsByTagName('input');
if(document.getElementById('check'+arg).checked == true){
  for (var i = 0; i < clist.length; ++i) 
    clist[i].checked = true; 
}else{
  for (var i = 0; i < clist.length; ++i) 
    clist[i].checked = false; 
}
  
}
function submit(){
  document.getElementById('time').submit();
}

</script>
<script type="text/javascript">
 $(document).ready(function () {
        var today = new Date();
        $('.datepicker').datepicker({
            format: 'mm-dd-yyyy',
            autoclose:true,
            endDate: "today",
            maxDate: today
        }).on('changeDate', function (ev) {
                $(this).datepicker('hide');
            });


        $('.datepicker').keyup(function () {
            if (this.value.match(/[^0-9]/g)) {
                this.value = this.value.replace(/[^0-9^-]/g, '');
            }
        });
    });

</script>

@endsection
