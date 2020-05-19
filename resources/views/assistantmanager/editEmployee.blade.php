@extends('layouts.app')
@section('content')
<?php $url = Helpers::geturl(); ?>
<div class="col-md-6 col-md-offset-3">
    <div class="panel panel-default" style="border-color: green;">
        <div class="panel-heading" style="background-color: green;color:white;padding-bottom: 20px;">Add details of {{ $user->name }}
             <a href="{{ url()->previous() }}" class="btn btn-danger input-sm pull-right">Back</a>
        </div>
        <div class="panel-body">
            <form method="POST" action="{{ URL::to('/') }}/amedit/save" enctype="multipart/form-data">
                {{ csrf_field() }}
                <?php $id = $_GET['UserId']; ?>
                <input type="hidden" name="userid" value="{{ $id }}">
                <table class="table table-responsive">
                    <tr>
                        <td>Date of Joining</td>
                        <td><input type="date" value="{{ $employeeDetails != NULL? $employeeDetails->date_of_joining:'' }}" name="doj" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Aadhar No.</td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->adhar_no:'' }}" name="aadhar" class="form-control input-sm" placeholder="Aadhar No."></td>
                    </tr>
                    <tr>
                        <td>Aadhar Card Image</td>
                        <td>
                            <input type="file" name="aadharImg" class="form-control input-sm"><br>
                            @if($employeeDetails != NULL)
                            @if($employeeDetails->aadhar_image != NULL)
                                <img height="200" width="200" class="img img-responsive" src="{{$url }}/employeeImages/{{ $employeeDetails->aadhar_image }}">
                            @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Date of Birth</td>
                        <td><input type="date" value="{{ $employeeDetails != NULL? $employeeDetails->dob:'' }}" name="dob" class="form-control input-sm"></td>
                    </tr>
                    <tr>
                        <td>Blood Group</td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->blood_group:'' }}" name="bloodGroup" class="form-control input-sm" placeholder="Blood Group"></td>
                    </tr>
                    <tr>
                        <td>Father's Name</td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->fathers_name:'' }}" name="fatherName" class="form-control input-sm" placeholder="Father's Name"></td>
                    </tr>
                    <tr>
                        <td>Mother's Name</td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->mothers_name:'' }}" name="motherName" class="form-control input-sm" placeholder="Mother's Name"></td>
                    </tr>
                    <tr>
                        <td>Spouse Name</td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->spouse_name:'' }}" name="spouseName" class="form-control input-sm" placeholder="Spouse Name"></td>
                    </tr>
                    <tr>
                        <td>Contact No.</td>
                        <td><input type="text" name="contact" value="{{ $user->contactNo }}" class="form-control" placeholder="Contact No."></td>
                    </tr>
                    <tr>
                        <td>Office Phone No.</td>
                        <td><input type="text" name="office" value="{{ $employeeDetails != NULL? $employeeDetails->office_phone:'' }}" class="form-control" placeholder="Office Phone No."></td>
                    </tr>
                    <tr>
                        <td>Alternative Phone No.</td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->alt_phone:'' }}" name="altPh" class="form-control input-sm" placeholder="Alternative phone No."></td>
                    </tr>
                    <tr>
                        <td>Official Email-id(gmail)</td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->official_email:'' }}" name="official_email" class="form-control input-sm" placeholder="Official Email-id"></td>
                    </tr>
                    <tr>
                        <td>Official Email-id(mamahome)</td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->mh_email:'' }}" name="mh_email" class="form-control input-sm" placeholder="Official Email-id"></td>
                    </tr>
                    <tr>
                        <td>Personal Email-id</td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->personal_email:'' }}" name="personal_email" class="form-control input-sm" placeholder="Personal Email-id"></td>
                    </tr>
                    <tr>
                        <td>Permanent Address</td>
                        <td><textarea style="resize: none;" class="form-control" name="perAdd" placeholder="Permanent address" rows="3" max-row="5">{{ $employeeDetails != NULL? $employeeDetails->permanent_address:'' }}</textarea></td>
                    </tr>
                    <tr>
                        <td>Permanent Address Proof</td>
                        <td>
                            <input type="file" class="form-control input-sm" name="permanenAddressProof">
                            @if($employeeDetails != NULL)
                            @if($employeeDetails->permanent_address_proof != NULL)
                                <img height="200" width="200" class="img img-responsive" src="{{ $url }}/employeeImages/{{ $employeeDetails->permanent_address_proof }}">
                            @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Present Address</td>
                        <td><textarea style="resize: none;" class="form-control" name="preAdd" placeholder="Present address" rows="3" max-row="5">{{ $employeeDetails != NULL? $employeeDetails->temporary_address:'' }}</textarea></td>
                    </tr>
                    <tr>
                        <td>Present Address Proof</td>
                        <td><input type="file" class="form-control input-sm" name="presentAddressProof">
                            @if($employeeDetails != NULL)
                            @if($employeeDetails->temporary_address_proof != NULL)
                                <img height="200" width="200" class="img img-responsive" src="{{ $url }}/employeeImages/{{ $employeeDetails->temporary_address_proof }}">
                            @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Emergency Contact 1 Name (Blood Relative) </td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->emergency_contact_name:'' }}" name="emergencyName" class="form-control input-sm" placeholder="Emergency Contact Name"></td>
                    </tr>
                    <tr>
                        <td>Emergency Contact 1 No. </td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->emergency_contact_no:'' }}" name="emergencyContact" class="form-control input-sm" placeholder="Emergency Contact No."></td>
                    </tr>
                    <tr>
                        <td>Confirmation Call Audio</td>
                        <td>
                            @if($employeeDetails != NULL)
                            @if($employeeDetails->confirmation_call != NULL)
                                <audio controls>
                                    <source src="{{ $url}}/employeeAudios/{{ $employeeDetails->confirmation_call }}" type="audio/ogg">
                                    <source src="{{ $url}}/employeeAudios/{{ $employeeDetails->confirmation_call }}" type="audio/mpeg">
                                </audio>
                            @endif
                            @endif
                            <input type="file" name="cfa" class="form-control input-sm" accept=".mp3">
                        </td>
                    </tr>
                    <tr>
                        <td>Emergency Contact 2 Name (Friend)</td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->emergency_contact2_name:'' }}" name="emergencyName2" class="form-control input-sm" placeholder="Emergency Contact Name"></td>
                    </tr>
                    <tr>
                        <td>Emergency Contact 2 No.</td>
                        <td><input type="text" value="{{ $employeeDetails != NULL? $employeeDetails->emergency_contact2_no:'' }}" name="emergencyContact2" class="form-control input-sm" placeholder="Emergency Contact No."></td>
                    </tr>
                    <tr>
                        <td>Confirmation Call Audio 2</td>
                        <td>
                            @if($employeeDetails != NULL)
                            @if($employeeDetails->confirmation_call2 != NULL)
                                <audio controls>
                                    <source src="{{ $url}}/employeeAudios/{{ $employeeDetails->confirmation_call2 }}" type="audio/ogg">
                                    <source src="{{ $url}}/employeeAudios/{{ $employeeDetails->confirmation_call2 }}" type="audio/mpeg">
                                </audio>
                            @endif
                            @endif
                            <input type="file" name="cfa2" class="form-control input-sm" accept=".mp3">
                        </td>
                    </tr>
                    <tr>
                        <td>Curriculum Vite (CV)</td>
                        <td><input type="file" name="cv" class="form-control input-sm">
                            @if($employeeDetails != NULL)
                            @if($employeeDetails->curriculum_vite != NULL)
                                <img height="200" width="200" class="img img-responsive" src="{{ $url }}/employeeImages/{{ $employeeDetails->curriculum_vite }}">
                            @endif
                            @endif
                        </td>
                    </tr>
                </table>
                <input type="submit" class="btn btn-success form-control" value="Save">
            </form>
        </div>
    </div>
    <div class="panel panel-default" style="border-color:green;">
        <div class="panel-heading"  style="background-color: green;color:white;">Bank Account details</div>
        <div class="panel-body">
            <form method="POST" action="{{ URL::to('/') }}/amedit/bank_account" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="userid" value="{{ $id }}">
                <table class="table table-responsive">
                    <tr>
                        <td>Bank Name</td>
                        <td><input type="text" value="{{ $bankDetails != NULL? $bankDetails->bank_name:''}}" name="bankName" class="form-control input-sm" placeholder="Bank Name"></td>
                    </tr>
                    <tr>
                        <td>Account Holder Name</td>
                        <td><input type="text" value="{{ $bankDetails != NULL? $bankDetails->accountHolderName:''}}" name="acHolder" class="form-control input-sm" placeholder="Account Holder Name"></td>
                    </tr>
                    <tr>
                        <td>Account No.</td>
                        <td><input type="text" value="{{ $bankDetails != NULL? $bankDetails->accountNo:''}}" name="acNo" class="form-control input-sm" placeholder="Account No."></td>
                    </tr>
                    <tr>
                        <td>IFSC</td>
                        <td><input type="text" value="{{ $bankDetails != NULL? $bankDetails->ifsc:''}}" name="ifsc" class="form-control input-sm" placeholder="IFSC"></td>
                    </tr>
                    <tr>
                        <td>Branch Name</td>
                        <td><input type="text" value="{{ $bankDetails != NULL? $bankDetails->branchName:''}}" name="branchName" class="form-control input-sm" placeholder="Branch Name"></td>
                    </tr>
                    <tr>
                        <td>Passbook</td>
                        <td>
                            <input type="file" name="passbook" class="form-control input-sm">
                            @if($bankDetails != NULL)
                            @if($bankDetails->passbook != NULL)
                                <br>
                                <img height="400" width="400" class="pull-right img-responsive" src="{{ $url }}/employeeImages/{{ $bankDetails->passbook }}">
                            @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Pan Card No.</td>
                        <td><input type="text" value="{{ $bankDetails != NULL? $bankDetails->pan_card_no:''}}" name="panCard" class="form-control input-sm" placeholder="Pan Card No."></td>
                    </tr>
                    <tr>
                        <td>Pan Card Image</td>
                        <td>
                            <input type="file" name="panCardImage" class="form-control input-sm">
                            @if($bankDetails != NULL)
                            @if($bankDetails->pan_card_image != NULL)
                                <br>
                                <img height="400" width="400" class="pull-right img-responsive" src="{{ $url }}/employeeImages/{{ $bankDetails->pan_card_image }}">
                            @endif
                            @endif
                        </td>
                    </tr>
                </table>
                <input type="submit" class="btn btn-success form-control" value="Save">
            </form>
        </div>
    </div>
    <div class="panel panel-default"  style="border-color: green;">
        <div class="panel-heading"  style="background-color: green;color:white;">Asset Info</div>
        <div class="panel-body">
            <table class="table table-responsive">
                <tr>
                    <td>Name</td>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <td>Designation</td>
                    <td>{{ $user->group->group_name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <td>Assets</td>
                    <td>
                        @if($assetInfos != NULL)
                        <table class="table table-responsive">
                            <thead>
                                <th>Type</th>
                                <th>Details</th>
                            </thead>
                            <tbody>
                                @foreach($assetInfos as $assetInfo)
                                <tr>
                                    <td>{{ $assetInfo->asset_type }}</td>
                                    <td>{{ $assetInfo->description }}</td>
                                    <td>
                                        <form method="POST" action="{{ URL::to('/') }}/deleteAsset">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{ $assetInfo->id }}">
                                            <input type="submit" value="Delete" class="btn btn-xs btn-danger">
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                       <!--  <form method="POST" action="{{ URL::to('/') }}/amedit/saveAssetInfo">
                            {{ csrf_field() }}
                            <input type="hidden" name="userId" value="{{ $user->employeeId }}">
                            <table id="asset" class="table table-responsive">
                                
                                <tbody>
                                    <tr>
                                        <td>
                                            <select required class="form-control" name="type[]">
                                                <option value="">--Select--</option>
                                                @foreach($assets as $asset)
                                                <option value="{{$asset->type}}">{{ $asset->type }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <textarea required class="form-control" placeholder="Asset description" name="details[]"></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button onclick="addRow()">+</button>
                            <button onclick="deleteRow()">-</button>
                            <input type="submit" class="form-control btn btn-success" value="Save">
                        </form> -->
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="panel panel-default" style="border-color: green;">
        <div class="panel-heading"  style="background-color: green;color:white;">Certificates</div>
        <div class="panel-body">
            <button onclick="addCertificateRow()">+</button>
            <form method="POST" action="{{ URL::to('/') }}/amedit/uploadCertificates" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" value="{{ $user->employeeId }}" name="userId">
            <table class="table table-responsive" id="Certificate">
                <thead>
                    <th>Certificate Name</th>
                    <th>File</th>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" class="form-control input-sm" placeholder="Certificate Type" name="type[]"></td>
                        <td><input type="file" class="form-control input-sm" name="certificateFile[]"></td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" value="Save" class="form-control btn btn-success">
            </form>
            @if($certificates != NULL)
            <table class="table table-responsive">
                <thead>
                    <th>Type</th>
                    <th>Details</th>
                </thead>
                <tbody>
                    @foreach($certificates as $certificate)
                    <tr>
                        <td>{{ $certificate->type }}</td>
                        <td>
                            <img onclick="display('certificate{{$certificate->id}}')" id="certificate{{$certificate->id}}" height="200" width="200" alt="{{ $user->name }}" class="img img-responsive myImg" src="{{ $url }}/employeeImages/{{ $certificate->location }}">
                        </td>
                        <td>
                            <form method="POST" action="{{ URL::to('/') }}/deleteCertificate">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $certificate->id }}">
                                <input type="submit" value="Delete" class="btn btn-xs btn-danger">
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
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
<script>
    // function addRow() {
    //         var table = document.getElementById("asset");
    //         var row = table.insertRow(-1);
    //         var cell1 = row.insertCell(0);
    //         var cell2 = row.insertCell(1);
    //         cell1.innerHTML = "<select required class=\"form-control\" name=\"type[]\"><option value=\"\">--Select--</option>@foreach($assets as $asset)<option value=\"{{$asset->type}}\">{{ $asset->type }}</option>@endforeach </select>";
    //         cell2.innerHTML = "<textarea required class=\"form-control\" placeholder=\"Asset description\" name=\"details[]\"></textarea>";
    //     }
    //     function deleteRow() {
    //         document.getElementById("asset").deleteRow(-1);
    //     }
        function addCertificateRow() {
            var table = document.getElementById("Certificate");
            var row = table.insertRow(-1);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            cell1.innerHTML = "<input type=\"text\" class=\"form-control input-sm\" placeholder=\"Certificate Type\" name=\"type[]\">";
            cell2.innerHTML = "<input type=\"file\" class=\"form-control input-sm\" name=\"certificateFile[]\">";
        }
        function deleteCertificateRow() {
            document.getElementById("asset").deleteRow(-1);
        }
</script>
@endsection