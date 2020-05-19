<?php
    $user = Auth::user()->group_id;
    $ext = ($user == 1? "layouts.teamheader":"layouts.app");
?>
@extends($ext)
@section('content')
<?php $url = Helpers::geturl(); ?>

<form method="POST" onsubmit="validateform()" action="{{ URL::to('/') }}/updateretail" enctype="multipart/form-data">
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
               
          <table class='table table-responsive table-striped' style="color:black" border="1">
            
              <tr>
                <td>Subward Name</td>
                <td>:</td>
                <td>{{$project->subward->sub_ward_name ?? ''}}</td>
            </tr>
            <tr>
                <td>ID</td>
                <td>:</td>
                <td><input type="text" name="id" value="{{$project->id}}" readonly></td>
            </tr>
             <tr>
                <td>Owner Number</td>
                <td>:</td>
                <td><input type="text" name="number" value="{{$project->number}}"></td>
            </tr>
             <tr>
                <td>Owner Name</td>
                <td>:</td>
                <td><input type="text" name="pName" value="{{$project->name}}"></td>
            </tr>
            <tr>
                <td>GST</td>
                <td>:</td>
                <td><input type="text" name="gst" value="{{$project->gst}}"></td>
            </tr>
            <tr>
                <td>Address</td>
                <td>:</td>
                <td>{{$project->address}}</td>
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
                                    <?php $product =explode(",",$project->Product); ?>
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
                <td>remarks</td>
                <td>:</td>
                <td><textarea name="remarks" value="{{$project->remarks}}" class="form-control">{{$project->remarks}}</textarea></td>
            </tr><br>
             
          </table>
                <center><button type="submit" class="btn btn-sm btn-warning ">Update</button></center>        
        </div>
      </div>
    </div>
         
            @endforeach  
</form>
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
@endsection