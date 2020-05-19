
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2">
            
            <div class="panel panel-default" style="border-color:#0e877f">
                <div class="panel-heading" style="background-color:#0e877f;color:white;">MINI REPORT</div>
                <div class="panel-body">
                    @foreach($users as $user)
                        <?php 
                            $content = explode(" ",$user->id);
                            $con = implode("",$content);
                        ?>
                        <a id="{{ $con }}" class="list-group-item" href="#">{{ $user->name }}</a>
                    @endforeach
                    <!-- <a id="FormerEmployees" class="list-group-item" href="#">Former Employees</a> -->
                </div>
            </div>
        </div>
        <div class="col-md-10" id="disp">

        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
@foreach($users as $user)
<?php 
    $content = explode(" ",$user->id);
    $con = implode("",$content);
?>
<script type="text/javascript">
$(document).ready(function () {
    $("#{{ $con }}").on('click',function(){
        $(document.body).css({'cursor' : 'wait'});
        $("#disp").load("{{ URL::to('/') }}/getprojectreport?name="+encodeURIComponent("{{ $user->id }}"), function(responseTxt, statusTxt, xhr){
            if(statusTxt == "error")
                alert("Error: " + xhr.status + ": " + xhr.statusText);
        });
        $(document.body).css({'cursor' : 'default'});
    });
});
</script>
@endforeach
@endsection
