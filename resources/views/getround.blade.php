  <div class="panel panel-default" style="border-color:#0e877f">
        <div class="panel-heading" style="background-color:#1db0f1"><h3 style="color:white;font-weight:bold;text-align:center;">{{$data}}</h3> </div>
                <div class="panel-body" style="height:500px;max-height:500px;overflow-x:hidden; overflow-y:scroll;">       
          <div class="col-md-3">
           
        <table class="table table-responsive table-hover " border="1" >
          <thead class="thead-dark">
          <th colspan="2" class="thead-dark" style="text-align: center;">Interview Report</th>
          </thead>
          <tr>
            <td></td>
            <td>Interview Deatils</td>
          </tr>
            <tr>
              <td>Frist Round</td>
               @foreach($first as $f)
              <td>{{($f->other + $f->technical)/2}}</td>
              @endforeach
            </tr>
            <tr>
            <td>Secound Round</td>
             @foreach($sec as $f)
              <td>{{($f->other + $f->communication)/2}}</td>
              @endforeach
            </tr>
            <!-- <tr>
              <td>Third Round</td>
              <td></td>
            </tr> -->
        </table>
     
      </div>
       <div class="col-md-9">
        <style>
body {font-family: Arial;}

/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding:6px 8px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #68c1c3;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
</style>
<div class="tab" style="background-color:#0e877f;">
  <button class="tablinks active" id="defaultOpen" onclick="openCity(event, 'London')">First Round</button>
  <button class="tablinks" onclick="openCity(event, 'Paris')">Secound Roud</button>
  <button class="tablinks" onclick="openCity(event, 'yadav')">Third Round</button>

</div>

<div id="London" class="tabcontent active" style="overflow-y: scroll;">
  @if(count($first) > 0)  
  <table class="table table-responsive table-hover" border="1" >
          <head>
            <th colspan="2" style="text-align: center;">HR Interview Report</th>
          </head>
          @foreach($first as $f)
          <tr>
            <td>Name</td>
            <td>{{$f->user->name ?? ''}}</td>
          </tr>
            <tr>
              <td>Technical Skill</td>
              <td>{{$f->technical}}</td>
            </tr>
            <tr>
            <td>Other Skill</td>
            <td>{{$f->other}}</td>
            </tr>
            <tr>
              <td> Interview Taken By</td>
              <td>{{$f->who_takes}}</td>
            </tr>
            @endforeach
        </table>
        
        @else
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
   Add Your Marks
  </button>
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Rate Skilles out of 10</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/') }}/firstmarks" enctype="multipart/form-data">
                 {{ csrf_field() }}
      <input type="hidden" name="interviewid" value="{{$id}}">
      <input type="hidden" name="wname" value="{{Auth::user()->name}}">
           
                 
  <label>
    <p class="label-txt">Communication Skills</p>
    <input type="text" class="input"  name="cname" required>
    <div class="line-box">
      <div class="line"></div>
    </div>
  </label>
  <label>
    <p class="label-txt">Other skills</p>
    <input type="text" class="input" name="oname" required>
    <div class="line-box">
      <div class="line"></div>
    </div>
  </label>
 <label>
    <p class="label-txt">Audio File</p>
    <input type="file" class="input"  name="video" required>
    <div class="line-box">
      <div class="line"></div>
    </div>
  </label>
  <button type="submit">submit</button>
</form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
        @endif
      </div>


<div id="Paris" class="tabcontent" style="overflow-y: scroll;">
   @if(count($sec) > 0)  
  <table class="table table-responsive table-hover" border="1" >
          <head>
            <th colspan="2" style="text-align: center;">Technical Team Report</th>
          </head>
          @foreach($sec as $f)
          <tr>
            <td>Name</td>
            <td>{{$f->user->name ?? ''}}</td>
          </tr>
            <tr>
              <td>Technical Skill</td>
              <td>{{$f->communication}}</td>
            </tr>
            <tr>
            <td>Other Skill</td>
            <td>{{$f->other}}</td>
            </tr>
            <tr>
              <td> Interview Taken By</td>
              <td>{{$f->who_take}}</td>
            </tr>
            @endforeach
        </table>
        
        @else
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
   Add Your Marks
  </button>
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Rate Skilles out of 10</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/') }}/secmarks" enctype="multipart/form-data">
                 {{ csrf_field() }}
      <input type="hidden" name="interviewid" value="{{$id}}">
      <input type="hidden" name="wname" value="{{Auth::user()->name}}">
           
                 
  <label>
    <p class="label-txt">Communication Skills</p>
    <input type="text" class="input"  name="cname" required>
    <div class="line-box">
      <div class="line"></div>
    </div>
  </label>
  <label>
    <p class="label-txt">Other skills</p>
    <input type="text" class="input" name="oname" required>
    <div class="line-box">
      <div class="line"></div>
    </div>
  </label>
 <label>
    <p class="label-txt">Audio File</p>
    <input type="file" class="input"  name="video" required>
    <div class="line-box">
      <div class="line"></div>
    </div>
  </label>
  <button type="submit">submit</button>
</form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
        @endif
      </div>
<div id="yadav" class="tabcontent" style="overflow-y: scroll;">
    <table class="table table-responsive table-hover" border="1" >
          <head>
          <th colspan="2" style="text-align: center;">Interview Report</th>
          </head>
            <tr>
              <td>HR Round</td>
               @foreach($first as $f)
              <td>{{($f->other + $f->technical)/2}}</td>
              @endforeach
            </tr>
            <tr>
            <td>Technical Team </td>
             @foreach($sec as $f)
              <td>{{($f->other + $f->communication)/2}}</td>
            </tr>
            <tr>
              <?php 
                  $id = App\Interview::where('id',$f->interview_id)->pluck('status')->first();
              ?>
              <td>Accept Result</td>

              @if($id != 2)
              <td><a href="{{ url('/') }}/acceptboss?id={{$f->interview_id}}" class="btn btn-sm btn-warning">Accept</a></td>
              @else
              <td>Accepted</td>
              @endif
            </tr>
              <tr>
              <td>Reject Result</td>
              @if($id == 2)
              <td>--</td>
              @elseif($id != 3)
              <td><a href="{{ url('/') }}/rejectboss?id={{$f->interview_id}}" class="btn btn-sm btn-danger">Reject</a></td>
              @else
              <td>----</td>
              @endif
            </tr>
              @endforeach
        </table>  
   

</div>
       </div>    
    </div>
    <script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
document.getElementById("defaultOpen").click();
</script>
  </div>

