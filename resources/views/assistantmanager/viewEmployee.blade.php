@extends('layouts.app')

@section('content')
<?php $url = Helpers::geturl(); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="border-color: green;">
                <div class="panel-heading" style="background-color: green;color:white;padding-bottom: 20px;">
                    {{ $user->employeeId }} : {{ $user->name }}
                    
            <a href="{{ url()->previous() }}" class="btn btn-danger input-sm pull-right">Back</a>
                </div>
                <div class="panel-body">
                    <center>
                        <img style="border-radius:50%; height:150px; width:150px;" src="{{ $url}}/profilePic/{{ $user->profilepic }}">
                        <form method="POST" action="{{ URL::to('/') }}/uploadProfilePicture" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="input-group col-md-6">
                        <input type="hidden" value="{{ $user->employeeId }}" name="userid">
                        <input oninput="display()" id="pp" required type="file" class="form-control" name="pp" accept="image/*">
                        <div class="input-group-btn">
                          <button class="btn btn-default" type="submit">
                            Submit
                          </button>
                        </div>
                      </div>
                    </form>
                    </center>
                    <br>
                    <table class="table table-responsive">
                        <tr>
                            <td>Name</td>
                            <td>: {{ $user->name }}</td>
                            <td>User Id of MMT</td>
                            <td>: {{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td>Department</td>
                            <td>: {{ $user->department != null ? $user->department->dept_name : '' }}</td>
                            <td>Designation</td>
                            <td>
                                : {{ $user->group->group_name }}
                            </td>
                        </tr>
                        @if($details != NULL)
                        <tr>
                            <td>Date of Joining</td>
                            <td>: {{ date('d-m-Y',strtotime($details->date_of_joining)) }}</td>
                            <td>Aadhar No.</td>
                            <td>: {{ $details->adhar_no }}</td>
                        </tr>
                        <tr>
                            <td>Date of Birth</td>
                            <td>: {{ date('d-m-Y',strtotime($details->dob)) }}</td>
                            <td>Blood Group</td>
                            <td>: {{ $details->blood_group }}</td>
                        </tr>
                        <tr>
                            <td>Father's Name</td>
                            <td>: {{ $details->fathers_name }}</td>
                            <td>Mother's Name</td>
                            <td>: {{ $details->mothers_name }}</td>
                        </tr>
                        <tr>
                            <td>Spouse Name</td>
                            <td>: {{ $details->spouse_name }}</td>
                            <td>Office Phone No.</td>
                            <td>: {{ $details->office_phone }}</td>
                        </tr>
                        <tr>
                            <td>Phone No.</td>
                            <td>: {{ $user->contactNo }}</td>
                            <td>Alternative Phone No.</td>
                            <td>: {{ $details->alt_phone }}</td>
                        </tr>
                        <tr>
                            <td>Official Email-id(gmail)</td>
                            <td>: {{ $details->official_email }}</td>
                            <td>official Email-id(mamahome)</td>
                            <td>: {{ $details->mh_email }}</td>
                        </tr>
                        <tr>
                            <td>personal Email-id</td>
                            <td>: {{ $details->personal_email }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Permanent Address</td>
                            <td>: {{ $details->permanent_address }}</td>
                            <td>Present Address</td>
                            <td>: {{ $details->temporary_address }}</td>
                        </tr>
                        <tr>
                            <td>Emergency Contact Name</td>
                            <td>{{ $details->emergency_contact_name }}</td>
                            <td>Emergency Contact No.</td>
                            <td>{{ $details->emergency_contact_no }}</td>
                        </tr>
                        <tr>
                            <td>Call Confirmation Audio</td>
                            <td><audio controls style="width:100%;">
                                    <source src="{{ $url }}/employeeAudios/{{ $details->confirmation_call }}" type="audio/ogg">
                                    <source src="{{ $url }}/employeeAudios/{{ $details->confirmation_call }}" type="audio/mpeg">
                                </audio>
                            </td>
                            <td>Call Confirmation Audio 2</td>
                            <td><audio controls style="width:100%;">
                                    <source src="{{ $url }}/employeeAudios/{{ $details->confirmation_call2 }}" type="audio/ogg">
                                    <source src="{{$url }}/employeeAudios/{{ $details->confirmation_call2 }}" type="audio/mpeg">
                                </audio>
                            </td>
                        </tr>
                        <tr>
                            <td>Emergency Contact 2 Name</td>
                            <td>{{ $details->emergency_contact2_name }}</td>
                            <td>Emergency Contact 2 No.</td>
                            <td>{{ $details->emergency_contact2_no }}</td>
                        </tr>
                        <tr>
                            <td>Curriculum Vite</td>
                            <td>
                                @if($details->curriculum_vite != NULL)
                                <a href="https://view.officeapps.live.com/op/embed.aspx?src={{ $url }}/employeeImages/{{ $details->curriculum_vite }}" class="btn btn-success btn-xs" target="_blank">Click Here To View CV</a>
                           
                                
                                @else
                                <i>*No CV provided*</i>
                                @endif
                            </td>
                            <td>Aadhar Image</td>
                            <td>
                                @if($details->aadhar_image != NULL)
                                <img onclick="display('aadhar')" id="aadhar" height="200" width="200" alt="{{ $user->name }}" class="img img-responsive myImg" src="{{ $url }}/employeeImages/{{ $details->aadhar_image }}">
                                @else
                                <i>*No aadhar image uploaded*</i>
                                @endif
                            </td>
                        </tr>
                        @endif
                    </table>
                    @if($bankdetails != NULL)
                    <table class="table table-responsive">
                        <h3><center>Bank Account Details</center></h3>
                        <tr>
                            <td>Account Holder Name</td>
                            <td>{{ $bankdetails->accountHolderName }}</td>
                            <td>Bank Name</td>
                            <td>{{ $bankdetails->bank_name }}</td>
                        </tr>
                        <tr>
                            <td>Account No.</td>
                            <td>{{ $bankdetails->accountNo }}</td>
                            <td>IFSC</td>
                            <td>{{ $bankdetails->ifsc }}</td>
                        </tr>
                        <tr>
                            <td>Branch Name</td>
                            <td>{{ $bankdetails->branchName }}</td>
                            <td>Pan Card No.</td>
                            <td>{{ $bankdetails->pan_card_no }}</td>
                        </tr>
                        <tr>
                            <td>Passbook Image</td>
                            <td>
                                @if($bankdetails->passbook != NULL)
                                <img onclick="display('passbook')" id="passbook" height="200" width="200" alt="{{ $user->name }}" class="img img-responsive myImg" src="{{ $url }}/employeeImages/{{ $bankdetails->passbook }}">
                                @else
                                <i>*No image uploaded*</i>
                                @endif
                            </td>
                            <td>Pan Card Image</td>
                            <td>
                                @if($bankdetails->pan_card_image != NULL)
                                <img onclick="display('pan')" id="pan" height="200" width="200" alt="{{ $user->name }}" class="img img-responsive myImg" src="{{ $url }}/employeeImages/{{ $bankdetails->pan_card_image }}">
                                @else
                                <i>*No pan card image*</i>
                                @endif
                                </td>
                        </tr>
                    </table>
                    @endif
                    <table class="table table-responsive">
                        <h3><center>Asset Info.</center></h3>
                        <thead><th>Asset Type</th><th>Description</th></thead>
                        <tbody>
                            @foreach($assets as $asset)
                            <tr>
                                <td>{{ $asset->asset_type }}</td>
                                <td>{{ $asset->description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table class="table table-responsive">
                        <h3><center>CERTIFICATES</center></h3>
                        <thead><th>Type</th><th>Image</th></thead>
                        <tbody>
                            @foreach($certificates as $certificate)
                            <tr>
                                <td>{{ $certificate->type }}</td>
                                <td><img onclick="display('certificate{{$certificate->id}}')" id="certificate{{$certificate->id}}" height="200" width="200" alt="{{ $user->name }}" class="img img-responsive myImg" src="{{ $url }}/employeeImages/{{ $certificate->location }}"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="myModal" class="imgModal">

  <!-- The Close Button -->
  <span class="imgClose">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="imgModal-content" id="img01">

  <!-- Modal Caption (Image Text) -->
  <div id="caption"></div>
</div>

@endsection
