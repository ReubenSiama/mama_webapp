<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)
@section('content')
<?php $url = Helpers::geturl(); ?>

<form method="POST" onsubmit="validateform()" action="{{ URL::to('/') }}/updatematirial" enctype="multipart/form-data">
{{ csrf_field() }}
 @foreach($data as $project)
 <div class="col-md-6 col-md-offset-3">
  <span class="pull-right"> @include('flash-message')</span>

                    <div class="panel panel-primary">
                        <div class="panel-heading" style="height:50px;background-color:#42c3f3;color:black;">
                      <span class="pull-lect" style="color:white;">  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
                      <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-left" style="margin-top:-10px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;color:black;"></i></button>
                           <div  class="pull-right" style="color:#ffffffe3;">
                              Last updated 0n  {{$project->updated_at}} /   Listed On :  {{$project->created_at}}
                           </div>
                           
                        </div>
                        <div class="panel-body">
               
          <table class='table table-responsive table-striped' style="color:black" >
            
              <tr>
                <td>Subward Name</td>
                <td>:</td>
                <td>{{$project->subward->sub_ward_name ?? ''}}</td>
            </tr>
            <tr>
                <td>MaterialHub ID</td>
                <td>:</td>
                <td><input type="text" name="id" value="{{$project->id}}" readonly></td>
            </tr>
             <tr>
                <td>Truck OwnerNumber</td>
                <td>:</td>
                <td><input type="text" name="onumber" value="{{$project->onumber}}"></td>
            </tr>
             <tr>
                <td>Truck OwnerName</td>
                <td>:</td>
                <td><input type="text" name="oname" value="{{$project->name}}"></td>
            </tr>
             <tr>
                                   <td>Truck Type</td>
                                   <td>:</td>
                                   <td>

                                    <label ><input required {{ $project->tracktype == "Truck" ? 'checked':''}} value="Truck" id="rmc" type="radio" name="tracktypetype"><span>&nbsp;</span>Truck</label>
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                      <label ><input required value="Tractor"   {{ $project->tracktype == "Tractor" ? 'checked':''}}  id="rmc2" type="radio" name="tracktypetype"><span>&nbsp;</span>Tractor</label> 
                                      <span>&nbsp;&nbsp;&nbsp;  </span>
                                     
                                   </td>
                                  
                               </tr>
            <tr>
                <td>Address</td>
                <td>:</td>
                <td>{{$project->address}}</td>
            </tr>
             <tr>
                <td>Category</td>
                <td>:</td>
                <td>
                   <select  class="form-control" name="Category" id="contract" class="requiredn">
                                    <option    value="" disabled selected>--- Select ---</option>
                                    <option  {{ $project->Category == "riversand" ? 'selected':''}}   value="riversand ">River Sand </option>
                                    <option  {{ $project->Category == "m-sand" ? 'selected':''}} value="m-sand">M-Sand</option>
                                     <option {{ $project->Category == "cement" ? 'selected':''}}  value="cement">Cement</option>
                                    <option  {{ $project->Category == "aggregates" ? 'selected':''}} value="ggregates">Aggregates</option>

                                     <option   {{ $project->Category == "redbricks" ? 'selected':''}} value="redbricks">Red Bricks</option>

                                </select>
                </td>
            </tr>
               <tr>
                                <td>Vehical Capicity Type</td>
                                <td>:</td>
                                <td>
                                  <select required class="form-control" name="Vehicaltype" id="contract" class="required">
                                    <option   value="" disabled selected>--- Select ---</option>
                                    <option {{ $project->Vehicaltype == "6" ? 'selected':''}}   value="6 ">6 wheelers</option>
                                    <option {{ $project->Vehicaltype == "10" ? 'selected':''}} value="10"> 10 wheelers</option>
                                    <option {{ $project->Vehicaltype == "12" ? 'selected':''}} value="12"> 12 wheelers</option>
                                     

                                    

                                </select>
                              </td>
                            </tr>

             
                
                  <tr>
                                   <td>Parking Vehical Capicity</td>
                                   <td>:</td>
                                   <td><input type="text" name="Capacity" class="form-control" placeholder="Capacity From" value="{{$project->Capacity}}">
                                    <input type="text" name="Capacity1" class="form-control" placeholder="Capacity to" value="{{$project->Capacity1}}">




                               </tr>


               
           <tr>
               <table class="table" border="1">
                             <thead>
                              <th>Product Name</th>
                              <th>Product price</th>
                             </thead>
                             <tbody>
                               <tr>
                                 <td>
                                    <?php $product =explode(",",$project->product); 

                                            
                                    ?>
                                      @foreach($product as $pro)
                                        @if($pro != null)
                                      <input type="text" name="Product[]" value="{{$pro}}"> <br>
                                      @endif
                                      @endforeach   

                                 </td>
                                 <td>
                                    <?php $product =explode(",",$project->price); ?>
                                      @foreach($product as $pro)
                                       @if($pro != null)
                                       <input type="text" name="price[]" value=" {{$pro}}"> <br>
                                       @endif
                                      @endforeach   

                                 </td>
                               </tr>
                             </tbody>
                          </table>
                           <div id="POItablediv">
  
  <table id="POITable" border="1" class="table">
    <tr>
      <td>Slno</td>
      <td>Product</td>
      <td>Price</td>
      <td>Delete?</td>
      <td>Add Rows?</td>
    </tr>
    <tr>
      <td>1</td>
      <td><input size=25 type="text" id="latbox" name="Product[]" placeholder="Product Name" /></td>
      <td><input size=25 type="text" id="lngbox" name="price[]" placeholder="price" /></td>
      <td><input type="button" id="delPOIbutton" value="Delete" onclick="deleteRow(this)"  class="btn btn-sm btn-danger" /></td>
      <td><input type="button" id="addmorePOIbutton" value="Add" onclick="insRow()" class="btn btn-sm btn-success" /></td>
    </tr>
  </table>
   </div>
            </tr>
             <tr>
                            <td><b>Project Image  </b></td>
                            <td>:</td>
                            <td>
                               <?php
                                               $images = explode(",", $project->pImage);
                                               ?>
                                             <div class="row">
                                                 @for($i = 0; $i< count($images); $i++)
                                                     <div class="col-md-3">
                                                          <img height="350" width="350"  src="{{ $url }}/Materialhub/{{ $images[$i] }}" class="img img-thumbnail">
                                                     </div>
                                                 @endfor

                                     <input id="pImage" oninput="fileuploadimage()"  type="file" accept="image/*" class="form-control input-sm" name="pImage[]" onchange="validateFileType()" multiple><p id="errormsg"></p>

                                              </div>
                            </td>
                           
                        </tr>
                       
                         
                        
  
<div class="w3-bar w3-blue">
  <a  class="w3-bar-item w3-button" onclick="openCity('London')">Co-Ordinator Deatils</a>
  <a  class="w3-bar-item w3-button" onclick="openCity('Paris')">Broker Details</a>
  <a class="w3-bar-item w3-button" onclick="openCity('Tokyo')">Bank Details</a>
</div>


   <div id="London" class="w3-container city" >
  <table class="table" border="1">
                               <tr>
                                   <td>Co-Ordinator Name</td>
                                   <td>:</td>
                                   <td><input value="{{$project->name}}" type="text" placeholder="Co-Ordinator Name" class="form-control input-sm" name="name" id="oName"></td>
                               </tr>
                               <tr>
                                   <td>Co-Ordinator Contact Number1</td>
                                   <td>:</td>
                                   <td><input value="{{$project->coordinaternumber1}}" onblur="checkmail('oEmail')" placeholder="Co-Ordinator Contact Number1" type="text" maxlength="10"  minlength="10"   class="form-control input-sm" name="cnumber1" id="oEmail"></td>
                               </tr>
                               <tr>
                                   <td>Co-Ordinator Contact Number2.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{ $project->coordinaternumber2 }}" onblur="checklength('oContact');" onkeyup="check('oContact','1')" maxlength="10"  minlength="10" placeholder="Co-Ordinator Contact Number2." type="text" class="form-control input-sm" name="cnumber2" id="oContact"></td>
                               </tr>
                           </table>
</div>

 <div id="Paris" class="w3-container city" style="display:none">
  <table class="table" border="1">
                               <tr>
                                   <td>Broker Name</td>
                                   <td>:</td>
                                   <td><input value="{{$project->brokername }}" type="text" placeholder="Broker Name" class="form-control input-sm" name="bname" id="oName"></td>
                               </tr>
                               <tr>
                                   <td>Broker Contact Number1</td>
                                   <td>:</td>
                                   <td><input value="{{ $project->brokernumber1 }}" onblur="checkmail('oEmail')" placeholder="Broker Contact Number1" type="text" maxlength="10"  minlength="10"   class="form-control input-sm" name="bnumber1" id="oEmail"></td>
                               </tr>
                               <tr>
                                   <td>Broker Contact Number2.</td>
                                   <td>: <p class="pull-right">+91</p></td>
                                   <td><input value="{{$project->brokernumber2}}" onblur="checklength('oContact');" onkeyup="check('oContact','1')" maxlength="10"  minlength="10" placeholder="Broker Contact Number2." type="text" class="form-control input-sm" name="bnumber2" id="oContact"></td>
                               </tr>
                           </table>
</div>

 <div id="Tokyo" class="w3-container city" style="display:none">
  <table class="table" border="1">
                               <tr>
                                   <td>Bank Name</td>
                                   <td>:</td>
                                   <td><input value="{{$project->bankname}}" type="text" placeholder="Bank Name" class="form-control input-sm" name="bankname" id="oName"></td>
                               </tr>
                               <tr>
                                   <td> Account Number</td>
                                   <td>:</td>
                                   <td><input value="{{ $project->accountnumber }}" onblur="checkmail('oEmail')" placeholder="Account Number" type="text" maxlength="20"  minlength="10"  class="form-control input-sm" name="accountnumber" id="oEmail"></td>
                               </tr>
                               <tr>
                                   <td>IFS CODE</td>
                                   <td>:</td>
                                   <td><input value="{{ $project->ifscode }}" onblur="checklength('oContact');" onkeyup="check('oContact','1')" maxlength="15"  minlength="0" placeholder="IFS CODE." type="text" class="form-control input-sm" name="ifs" id="oContact"></td>
                               </tr>
                           </table>
</div>                      
</tr>



<tr>
                            
                                    <td>Call Updates</td>
                                    <td>:</td><br>
                                            <?php 

                                             $data = App\UpdatedReport::where('Materialhub_id',$project->id)->orderBy('created_at', 'desc')->first(); 

                                            
                                             
                                            ?>
                                    
                                            @if($data != null)
                                          <td>
                                 <label required class="checkbox-inline"><input  onclick="getclear()" {{ $data->quntion == "Busy" ? 'checked' : ''}} id="constructionType1" name="quntion" type="radio" value="Busy">Busy And Not Reachable </label>
                                    <label required class="checkbox-inline"><input onclick="getclear()"   {{ $data->quntion == "switched" ? 'checked' : ''}} id="constructionType2" name="quntion" type="radio" value="switched">Switched Off</label> 
                                  <label required class="checkbox-inline"><input  onclick="getclear()"  {{ $data->quntion == "notanswer" ? 'checked' : ''}}  id="constructionType2" name="quntion" type="radio" value="notanswer">Call Not Answered</label> 
                                      <label required class="checkbox-inline"><input  onclick="getclear()"  {{ $data->quntion == "notinterest" ? 'checked' : ''}} id="constructionType2" name="quntion" type="radio" value="notinterest">Not Instrested</label> 
                                      <label required class="checkbox-inline"><input onclick="getclear()"  {{ $data->quntion == "attend" ? 'checked' : ''}} id="constructionType2" name="quntion" type="radio" value="attend">Call Attended.</label> 
                                    </td>
                                    @else
                                     <td>
                                 <label required class="checkbox-inline"><input onclick="getclear()"  id="constructionType1" name="quntion" type="radio" value="Busy">Busy And Not Reachable </label>
                                    <label required class="checkbox-inline"><input  onclick="getclear()" id="constructionType2" name="quntion" type="radio" value="switched">Switched Off</label> 
                                  <label required class="checkbox-inline"><input  onclick="getclear()"   id="constructionType2" name="quntion" type="radio" value="notanswer">Call Not Answered</label> 
                                      <label required class="checkbox-inline"><input onclick="getclear()"  id="constructionType2" name="quntion" type="radio" value="notinterest">Not Instrested</label> 
                                      <label required class="checkbox-inline"><input onclick="getclear()"  id="constructionType2" name="quntion" type="radio" value="attend">Call Attended.</label> 
                                    </td>
                                    @endif
                                   
                          

                               </tr>

                    
                    <tr>

                            <td>Remarks</td>
                            <td>:</td>
                            <td>
                          <textarea  class="form-control" placeholder="Remarks (Optional)"  name="remarks" value="{{$project->remarks}}" id="clear" required>{{$project->remarks}}</textarea>
                          </td>
                        </tr>
          </table>
                <center><button type="submit" class="btn btn-sm btn-warning">Update</button></center>        
        </div>
      </div>
    </div>
         
            @endforeach  
</form>
<script type="text/javascript">
  function getclear(){
      
    document.getElementById('clear').value = "";
  }
</script>
<script type="text/javascript">
function deleteRow(row) {
  var i = row.parentNode.parentNode.rowIndex;
  document.getElementById('POITable').deleteRow(i);
}


function insRow() {
  console.log('hi');
  var x = document.getElementById('POITable');
  var new_row = x.rows[1].cloneNode(true);
  var len = x.rows.length;
  new_row.cells[0].innerHTML = len;

  var inp1 = new_row.cells[1].getElementsByTagName('input')[0];
  inp1.id += len;
  inp1.value = '';
  var inp2 = new_row.cells[2].getElementsByTagName('input')[0];
  inp2.id += len;
  inp2.value = '';
  x.appendChild(new_row);
}
</script>
<script>
function openCity(cityName) {
  var i;
  var x = document.getElementsByClassName("city");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  document.getElementById(cityName).style.display = "block";  
}
</script>
@endsection