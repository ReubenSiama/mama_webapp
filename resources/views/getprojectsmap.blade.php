
@extends('layouts.app')
@section('content')

    <div id="map" style=" height:70%;margin-right:200px;margin-left:100px;width:100%;"></div>
   <div id="floating-panel" style="position: absolute;
        top: -10px;
        left:75%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;background: #fff;
        padding: 5px;
        font-size: 14px;
        font-family: Arial;
        border: 1px solid #ccc;
        box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4);
        display: none;margin-top:50px;
        ">
      <strong>Start:</strong>
      <select id="start" class="form-control" style="font-size: 15px; width: 100%;">
        <option value="">--select start ---</option>
        @foreach($projects as $project)
        <option value="{{ $project->siteaddress->address ?? ''}} ,in">{{ $project->project_id}},{{$project->procurementdetails->procurement_name ?? '' }}</option>
        @endforeach
      </select>
      <br>
      <strong>End:</strong>
      <select id="end" class="form-control" style="font-size: 15px; width: 100%;">
        <option value="">--select End ---</option>

        @foreach($projects as $project)
        <option value="{{ $project->siteaddress->address ?? ''}} ,in">{{ $project->project_id}},{{$project->procurementdetails->procurement_name ?? '' }}</option>
        @endforeach
      </select>
    </div>
    <div id="right-panel" style="font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;font-size: 15px; width: 100%; height: 100%;
        float: right;
        width: 390px;
        overflow: auto;float: none;
          width: auto;"></div>
    <script>
      function initMap() {
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var directionsService = new google.maps.DirectionsService;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 7,
          center: {lat: 13.046413, lng: 77.5963265}
        });
        directionsDisplay.setMap(map);
        directionsDisplay.setPanel(document.getElementById('right-panel'));

        var control = document.getElementById('floating-panel');
        control.style.display = 'block';
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(control);

        var onChangeHandler = function() {
          calculateAndDisplayRoute(directionsService, directionsDisplay);
        };
        document.getElementById('start').addEventListener('change', onChangeHandler);
        document.getElementById('end').addEventListener('change', onChangeHandler);
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        var start = document.getElementById('start').value;
        var end = document.getElementById('end').value;
        directionsService.route({
          origin: start,
          destination: end,
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
    </script>
 <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGSf_6gjXK-5ipH2C2-XFI7eUxbHg1QTU&callback=initMap">
    </script>
<script>

</script>

@endsection
