@extends('hrmanage')
@section('content')
        <div class="row">
          <div class="col-lg-11 col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h2><center><i class="fa fa-group"></i><strong>Selected Users</strong></center></h2>
              </div>
              <div class="panel-body">
                <table class="table bootstrap-datatable countries">
                  <thead>
                    <tr>
                      <th>User Pic</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Interview Score</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $user)
                    <tr>
                      <td><img src="{{ URL::to('/') }}/public/interview/{{ $user->picture }}" style="height:50px; margin-top:-2px;"></td>
                      <td>{{$user->name}}</td>
                      <td>{{$user->email}}</td>
                      <td>
                        <?php
                         $f = App\FirstRound::where('interview_id',$user->id)->pluck('other')->first();
                         $f1 = App\FirstRound::where('interview_id',$user->id)->pluck('technical')->first();
                        $s = (($f+$f1)/2);
                       
                          $s = App\SecoundRound::where('interview_id',$user->id)->pluck('other')->first();
                         $s1 = App\SecoundRound::where('interview_id',$user->id)->pluck('communication')->first();
                        $s1 = (($s+$s1)/2);
                       
                       
                       $final = (($s + $s1)/2);


                      ?>
                        {{$final}}<br>


                      </td>
                      <td>
                        Selected
                      </td>
                      <td>
                        <div class="btn-group">
                <a href="{{ URL::to('/') }}/public/interview/{{ $user->resume }}" class="btn btn-primary">Resume</a>
                    <div class="btn-group">
                        <a  class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                     Interview Audio <span class="caret"></span></a>
                          <?php 

                                $f = App\FirstRound::where('interview_id',$user->id)->pluck('video')->first();
                                $s = App\SecoundRound::where('interview_id',$user->id)->pluck('video')->first();

                          ?>

                        <ul class="dropdown-menu" role="menu">

                                <li>
                                  <audio controls title="hello world">
                                  <source src="{{ URL::to('/') }}/public/interviewaudio/{{$f }}" type="audio/ogg">
                                  <source src="{{ URL::to('/') }}/public/interviewaudio/{{$f }}" type="audio/mpeg">
                                 
                                </audio> 
                              </li>
                                <li><audio controls>
                                  <source src="{{ URL::to('/') }}/public/interviewaudio/{{$s}}" type="audio/ogg">
                                  <source src="{{ URL::to('/') }}/public/interviewaudio/{{$s}}" type="audio/mpeg">
                                 Secound Round
                                </audio> </li>
                                                        
                        </ul>
                      </div>
                <a class="btn btn-info">Send Joning Letter</a>
                    </div> 
                      </td>
                    </tr>
                 @endforeach
                  </tbody>
                </table>
              </div>

            </div>

          </div>
        
          <!--/col-->
        

        </div>
@endsection