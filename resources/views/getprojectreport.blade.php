  <div class="panel panel-default" style="border-color:#0e877f">
        <div class="panel-heading" style="background-color:#0e877f;color:white;">Project Report of {{$name}}</div>
                <div class="panel-body" style="height:500px;max-height:500px;overflow-x:hidden; overflow-y:scroll;">
                  
          <div class="col-md-4">    
        <table class="table table-responsive table-hover" border="1" >
          <head>
            <th colspan="2" style="text-align: center;">Today's Report</th>
          </head>
          <tr>
            <td></td>
            <td>Total Count</td>
          </tr>
            <tr>
              <td>Projects Added</td>
              <td>{{$lecount}}</td>
            </tr>
            <tr>
            <td>Projects Updated</td>
            <td>{{$updatecount}}</td>
            </tr>
            <tr>
              <td>Manufacturers Added</td>
              <td>{{$m}}</td>
            </tr>
            <tr>
              <td>Manufacturers Updated</td>
              <td>{{$manuupdates}}</td>
            </tr>
            <tr>
              <td>RMC</td>
              <td>{{$rmc}}</td>
            </tr>
            <tr>
              <td>BLOCKS</td>
              <td>{{$blocks}}</td>
            </tr>
            <tr>
              <td>M-SAND</td>
              <td>{{$msand}}</td>
            </tr>
            <tr>
              <td>AGGREGATES</td>
              <td>{{$aggregates}}</td>
            </tr>
            <tr>
              <td>FABRICATORS</td>
              <td>{{$fabricators}}</td>
            </tr>
            <tr>
              <td>Total Enquiry Generated</td>
              <td>{{$enquiry}}</td>
            </tr>
        </table>
      </div>
       <div class="col-md-8">
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
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
</style>
<div class="tab">
  <button class="tablinks active" id="defaultOpen" onclick="openCity(event, 'London')">Projects</button>
  <button class="tablinks" onclick="openCity(event, 'Paris')">Manufacturers</button>
</div>

<div id="London" class="tabcontent active" style="overflow-y: scroll;">
  <table class="table" border="1">
    <thead>
      <tr>
      <th>Project-Id</th>
      <th>Date </th>
      <th>Time</th>
      <th>procurement-contact-number</th>
      <th>SubWard</th>
    </tr>
    </thead>
<tbody>
  @foreach($projectsd as $projects)
  <tr>
  <td><a href="{{ URL::to('/') }}/admindailyslots?projectId={{$projects->project_id}}&&lename={{ $projects->name }}" target="_blank">{{$projects->project_id}}</a></td>

   <td>{{ date('d-m-Y', strtotime( $projects->created_at)) }}</td>
   <td>
      {{ date('h:i:s A', strtotime($projects->created_at)) }}
    </td>



  <td>{{$projects->procurementdetails->procurement_contact_no ?? ''}}
  <td>{{$projects->subward->sub_ward_name ?? ''}}</td>
</tr>
@endforeach
</tbody>
  </table>
 
</div>

<div id="Paris" class="tabcontent" style="overflow-y: scroll;">
 <table class="table" border="1">
    <thead>
      <tr>
      <th>Manufacturers-Id</th>
      <th>Manufacturers-Type</th>
      <th>Date </th>
      <th>Time</th>
      <th>SubWard</th>
    </tr>
    </thead>
<tbody>
  @foreach($manufacturerd as $manu)
  <tr>
  <td><a href="{{ URL::to('/') }}/viewmanu?id={{ $manu->id }}">{{$manu->id}}</a></td>
  <td>{{$manu->manufacturer_type}}</td>
  <td>{{ date('d-m-Y', strtotime( $manu->created_at)) }}</td>
   <td>
      {{ date('h:i:s A', strtotime($manu->created_at)) }}
    </td>
  <td>{{$manu->subward->sub_ward_name ?? ''}}</td>
</tr>
@endforeach
</tbody>
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

