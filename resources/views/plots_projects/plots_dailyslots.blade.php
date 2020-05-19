<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)
@section('content')

    <div class="col-md-3">
        <div class="panel panel-primary" style="overflow-x:scroll">
            <div class="panel-heading text-center">
                <b style="color:white">Custom Daily Slot</b>
            </div>
            <div class="panel-body">
                <form action="{{ URL::to('/') }}/plots_dailyslots" method="GET">
                     {{ csrf_field() }}
                <table class="table table-responsive">
                    <tbody >
                        <tr>
                            <td>Select Listing Engineer</td>
                        </tr>
                        <tr>
                            <td>
                                <select class="form-control" name="list" required>
                                    <option disabled selected value="">(-- SELECT LE --)</option>
                                    
                                    <option value="ALL">All Listing Engineers</option>
                                    @if(Auth::user()->group_id != 22)
                                    @foreach($le as $list)
                                    <option value="{{$list->id}}">{{$list->name}}</option>
                                    @endforeach
                                    @else
                                    @foreach($tlUsers as $list)
                                    <option value="{{$list->id}}">{{$list->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Select From Date</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="date" placeholder= "From Date" class="form-control" id="fromdate" name="fromdate" required />
                            </td>
                        </tr>
                        <tr>
                            <td>Select To Date</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="date"  placeholder= "To Date" class="form-control" id="todate" name="todate" required />
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                <button type="submit" class="btn bn-md btn-success" style="width:100">Get Date Range Details</button>
                            </td>
                        </tr>
                        <!--<tr class="text-center">-->
                        <!--    <td>-->
                        <!--        <a class="btn bn-md btn-primary" style="width:100%" onclick="showtodayrecordsle()">Get Date Details</a>-->
                        <!--    </td>-->
                        <!--</tr>-->
                    </tbody>
                </table>
            </form>
            </div>
        </div>
       
    </div>
    <div class="col-md-9" >
        <div class="panel panel-primary" style="overflow-x:scroll">
            <div class="panel-heading" id="panelhead">
                <label> 
                    @if(isset($_GET['fromdate']))
                       Projects Listed From {{ date('d-m-Y',strtotime($_GET['fromdate'])) }} To {{date('d-m-Y',strtotime($_GET['todate']))}}   &nbsp;&nbsp;&nbsp;&nbsp;Total Count :
                    @else
                        Daily Listings For The Date :<b>{{ date('d-m-Y',strtotime($date)) }}</b> &nbsp;&nbsp;&nbsp;&nbsp;Projects Added : <b>
                    @endif

                @if( $projcount  == 0)
                    <?php 
                            echo "<script>
                           swal({
                                  title: 'No Projects Found',
                                  width: 400,
                                  hight: 400,
                                  padding: '3em',
                                  type: 'info',
                                
                                });
                            
                            </script>";
                    ?>
                  No Projects Found
                @else
                  {{$projcount}}
                @endif
                </b></label>
                 <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color:black;"></i></button> 
            </div>
            <div class="panel-body">
                <table class='table table-responsive table-striped' style="color:black" border="1">
                    <thead>
                        <tr>
                            <th style="text-align:center">Subward Name</th>
                            <th style="text-align:center">Plot-ID</th>
                            <th style="text-align:center">Owner Contact Number</th>
                           
                            <th style="text-align:center">Builder Contact Number</th>
                            <th style="text-align:center">Owner Name</th>
                            <th style="text-align:center">List Engineer Name</th>
                           
                        </tr>
                    </thead>
                    <tbody id="mainPanel">
                        <?php $users = []; ?>
                        @foreach($projects as $project)
                            @if($project->quality == "Fake")
                            <tr style='background-color:#d2d5db'>
                            @else
                                @if(!in_array($project->listing_engineer_id, $users))
                                <tr style='background-color:#91dd71'>
                                @else 
                                    <tr>
                                @endif
                            @endif
                            <?php array_push($users, $project->listing_engineer_id); ?>
                           

                           <td style="text-align:center" >
                                <a href="{{ URL::to('/')}}/viewsubward?projectid={{$project->project_id}} && subward={{ $project->subward != null ? $project->subward->sub_ward_name : '' }}" data-toggle="tooltip" data-placement="top" title="click here to view map" class="red-tooltip" target="_blank">{{ $project->subward != null ? $project->subward->sub_ward_name : '' }}
                             </a></td>
                            <td style="text-align:center">{{ $project->plot_id }}</a></td>
                            <td style="text-align:center">{{$project->plot != null ? $project->plot->owner_contact_no  : '' }}</td>
                            <td style="text-align:center">{{$project->plot != null ? $project->plot->builder_contact_no  : '' }}</td>
                           
                            <td style="text-align:center">{{$project->plot != null ? $project->plot->plot_owner_name   : '' }}</td>
                            
                            <td style="text-align:center">
                                {{$project->user !=null ? $project->user->name :''}}
                                
                            </td>
                            <!--<td style="text-align:center"><a onclick="" class="btn btn-sm btn-danger">Verify</a></td>-->
                        


                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <center>
                    <div id="wait" style="display:none;width:200px;height:200px;"><img src='https://www.tradefinex.org/assets/images/icon/ajax-loader.gif' width="200" height="200" /></div>
                </center>
            </div>
        </div>
    </div>
    <script src="http://code.jquery.com/jquery-3.3.1.js"></script>
    <script type='text/javascript'>
        $( document ).ready(function() {
            var arr = new Array();
            var ids = new Array();
            for(var i=0; i<10000; i++)
            {
                if(document.getElementById('listname-'+i))
                {
                    arr[i] = document.getElementById('listname-'+i).innerText; //Pulling all names in arr array
                    ids[i] = document.getElementById('hiddeninp-'+i).value;
                }
            }
            var unique = arr.filter(function(item, i, ar){ return ar.indexOf(item) === i; }); //Filtering out unique names from arr array
            var uniqueids = ids.filter(function(item, i, ar){ return ar.indexOf(item) === i; }); //Filtering out unique IDs from ids array
            var ans = new Array();
            for(var i=0;i<unique.length;i++)
            {
                var x = unique[i];
                ans[x] = 0;
                for(var k=0;k<arr.length;k++)
                {
                    if(x == arr[k])
                    {
                        ans[x]++;
                    }
                }
            }


        });
    </script>
    <script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
     background-color: #00acd6 

});

</script>
@endsection

