@extends('layouts.app')
@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading " style="background-color: #3097d1;text-align: center;"><p style="color: white">
               Get Quotation For Manufacturers and Project
                 </p>
                  <a onclick="history.back(-1)" class="btn btn-default pull-right btn-xs" style="margin-top:-30px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;"></i></a>
            </div>
            <div class="panel-body" >
                
                <form method="GET" action="{{ URL::to('/') }}/getprojects">
                    <div class="col-md-12">
                        <div class="col-md-2">
                        <label>Project Type :</label>
                            <select required name="quot" onchange="getSubwards()" id="ward" class="form-control">
                                <option value="">--Select--</option>
                            <option value="Project">Project</option>
                            <option value="Manufacturer">Manufacturer</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                        <label>Search for Project ID/Manufacturer ID</label>
                            <input required type="text" name="id" placeholder="Enter Confirmed Project Id/Manufacturer Id" class="form-control">
                        </div>
                        <div class="col-md-2">
                        <label>Category</label>
                            <select id="categ" class="form-control" name="category">
                                <option value="">--Select--</option>
                                @foreach($categories as $category)
                                <option {{ isset($_GET['category']) ? $_GET['category'] == $category->category_name ? 'selected' : '' : '' }} value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                           
                            <button style="margin-top: 25px;" type="submit" class="btn btn-success" >Fetch</button>
                        </div>
                    </div>
                </form>
                 <div class="col-md-12 col-md-offset-9" style="margin-top: -35px;">
                <form method="GET" action="{{ URL::to('/') }}/getprojects">
                <div  class="col-md-2">    
                  <input required class="form-control" type="text" placeholder="Search Quotation Id" name="quotid">
                </div>
                  <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
                </form>
                </div><br>
                <table class="table table-hover" border="1"> 
                    <br><br><br><br>
                    <thead>
                   @if($id )
                    <tr  bgcolor="#c7e2de">
                       <td colspan="12" style="text-align: center">  <a target="_blank" href="{{URL::to('/')}}/showThisProject?id={{$id}}">
                                    <b>Project ID : {{$id}}</b>
                                </a></td>
                    </tr>
                   @elseif($manu_id)
                   <tr  bgcolor="#c7e2de">
                       <td colspan="12" style="text-align: center">  <a href="{{ URL::to('/') }}/updateManufacturerDetails?id={{ $manu_id}}">Manufacturer ID : {{$manu_id}}&nbsp;({{ $manu }})
                       </a></td>
                   </tr>
                    @else

                   @endif
                    @if($enquiries != null)
                        <tr>
                            <th style="text-align: center">Enquiry ID</th>
                            <th style="text-align: center">Requirement Date</th>
                            <!-- <th style="text-align: center">Enquiry Date</th> -->
                            <th style="text-align: center">Contact</th>
                            <th style="text-align: center">Product</th>
                            <th style="text-align: center">Quantity</th>
                            <th style="text-align: center">Total Quantity</th>
                            <th style="text-align: center">Initiator</th>
                            <th style="text-align: center">Status</th>
                            <th style="text-align: center">Remarks</th>
                            <th style="text-align: center">Quotation ID</th>
                            <th style="text-align: center">Quotation</th>
                            <th style="text-align: center">Action</th>
                            <th style="text-align: center">Get Quotation For Multiple Sub Categories</th>

                        </tr>
                    </thead>
                        @foreach($enquiries  as $enquiry)
                    <tbody>
                        <tr>
                            <td style="text-align: center"><b>{{$enquiry->id}}</b>
                               </td>  
                            <td style="text-align: center">{{$newDate = date('d/m/Y', strtotime($enquiry->requirement_date)) }}</td>
                            <td style="text-align: center">{{ $enquiry->procurementdetails != null ? $enquiry->procurementdetails->procurement_contact_no : '' }}
                             {{ $enquiry->proc != null ? $enquiry->proc->contact :''  }}</td>
                            <td style="text-align: center;width: 30px;" ><b>{{$enquiry->brand}}</b><br>{{$enquiry -> main_category}} ({{ $enquiry->sub_category }}), {{ $enquiry->material_spec }} {{ $enquiry->product }} 
                            </td>
                            <td style="text-align: center">
                                <?php $quantity = explode(", ",$enquiry->quantity); ?>
                                @for($i = 0; $i<count($quantity); $i++)
                                {{ $quantity[$i] }}<br>
                                @endfor
                            </td>
                            <td style="text-align: center">{{ $enquiry->total_quantity }}</td>
                            <td style="text-align: center">{{ $enquiry->user != null ? $enquiry->user->name : '' }}</td>
                            <td style="text-align: center">
                                {{ $enquiry->status}}
                            </td>
                            <td style="text-align: center">
                                {{ $enquiry->notes}}
                            </td>
                            @if($enquiry->quotation == null)
                            <td>
                               Not Yet Generated
                            </td>
                            <td><button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#quotation{{$enquiry->id}}{{$enquiry->manu_id}}">Get Quotation</button>
                            </td>
                            @else
                            <td>
                               {{$enquiry->quot != null ? $enquiry->quot->quotation_id : ""}}</td>
                            <td><a type="button" href="{{ route('downloadquotation',['id'=>$enquiry->id,'manu_id'=>$enquiry->manu_id]) }}" style="background-color: #42413b;color:white" class="btn btn-sm">QUOTATION</a></td>
                            @endif
                            @if($enquiry->quotation != null)
                                <td><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#quotation{{$enquiry->id}}{{$enquiry->manu_id}}">Edit</button></td>
                            @else
                            <td>
                                <div>
                                 <a disabled type="button" class="btn btn-warning btn-sm" href="#">Edit</a>
                            </div>
                            </td>
                            @endif
                            <td>
                                <a href="{{URL::to('/')}}/productquatation?enqid={{$enquiry->id}}" class="btn btn-warning btn-sm">Get Quotation</a>
                                 <?php 
                

                $multi = App\Quotation::where('req_id',$enquiry->id)->get();

              

            ?>
            @foreach($multi as $mul)
               
                    <a type="button" href="{{ route('downloadquotation1',['id'=>$mul->id,'manu_id'=>$mul->manu_id,'project_id'=>$mul->project_id]) }}" style="background-color: rgb(34, 207, 72);color:white" class="btn btn-sm">QUOTATION &nbsp;{{$mul->id }}</a> <br>
               

               <a type="button" href="{{URL::to('/')}}/deletequan?id={{$mul->id}}" style="background-color: rgb(231, 60, 23);color:white" class="btn btn-sm">Delete &nbsp;{{$mul->id }}</a> <br>


            @endforeach
                             </td>   
                        </tr>   
                    </tbody>
                    @endforeach
                </table>
                @foreach($enquiries  as $enquiry)
                    <!-- Modal -->
                        <div id="quotation{{$enquiry->id}}{{$enquiry->manu_id}}" class="modal fade" role="dialog">
                          <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Quotation</h4>
                              </div>
                              <div class="modal-body">
                                    
                             <form action="{{ URL::to('/') }}/generatequotation?id={{$enquiry->id}}&&manu_id={{$enquiry->manu_id}}&&pid={{$enquiry->project_id}}" method="post">
                            {{ csrf_field() }}
                            @foreach($enquiries as $enq )
                            @if($enq->id == $enquiry->id)
                            <input type="hidden"  name="dtow1" id="dtow1{{$enq->id}}" value="">
                            <input type="hidden" name="dtow2" id="dtow2{{$enq->id}}" value="">
                            <input type="hidden" name="dtow3" id="dtow3{{$enq->id}}" value="">
                             <input type="text" class="hidden" value="" id="cgstpercent{{$enq->id}}" name="cgstpercent" >
        <input type="text" class="hidden" value="" id="sgstpercent{{$enq->id}}" name="sgstpercent" >
        <input type="text" class="hidden" value="" id="gstpercent{{$enq->id}}" name="gstpercent" >
        <input type="text" class="hidden" value="" id="igstpercent{{$enq->id}}" name="igstpercent" >
                            <table class="table table-responsive table-striped" border="1">
                            <?php 
                                     $rec =count($enq->quotation);
                             ?>
                             @if($rec == 0) 
                             <tr>
                                <td>Select Category : </td>
                                <td>
                                <select required id="cat{{$enquiry->id}}"  class="form-control" >
                                        <option>--Select Category--</option>
                                        @foreach($categories as $category)
                                       <option {{ isset($_GET['category']) ? $_GET['category'] == $category->category_name ? 'selected' : '' : '' }} value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                                        @endforeach 
                                </select>  
                               
                                </td> 
                            </tr>
                            <tr>
                                <td>Select State : </td>
                                <td>
                                <select required id="state{{$enquiry->id}}"  onchange="getsuppliername('{{ $enquiry->id}}')" class="form-control"> 
                                    <option>--Select--</option>
                                @foreach($states as $state)
                                    <option value="{{$state->id}}">{{$state->state_name}}</option>
                                @endforeach
                                </select>
                                <br>
                                CGST : <label id="g1{{$enquiry->id}}" class="alert-success"></label>%
                                SGST : <label id="g2{{$enquiry->id}}" class="alert-success"></label>%
                                IGST : <label id="g3{{$enquiry->id}}" class="alert-success"></label>%
                            </td>
                            </tr>
                                <tr>
                                    <td>Description Of Goods : </td>
                                    <td><input type="text" name="description" value="{{$enq->brand}}" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td>Ship Address : </td>
                                    <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">
                                        @if($enq->project_id == null)
                                        {{$enq->manu != null ? $enq->manu->address : ''}}
                                        @else
                                        {{$enq->siteaddress != null ? $enq->siteaddress->address : ''}}
                                        @endif
                                    </textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bill Address : </td>
                                    <td><textarea required type="text" class="form-control" name="bill" style="resize: none;" rows="5">{{$enq->billadress}}
                                    </textarea></td>
                                </tr>
                                <tr>
                                    <td> Total Quantity :</td>
                                    <td><input required type="number" class="form-control" name="quantity" placeholder="quantity" id="quan{{$enq->id}}"  value="{{$enq->total_quantity}}"></td>
                                </tr>
                                <tr>
                                    <td>Unit :</td>
                                    <td>
                                        <input type="radio" name="unit" value="tons" >Tons
                                        <input type="radio" name="unit" value="Bags" checked> Bags
                                    </td>
                                </tr>
                            @else
                            @foreach($quotations as $quot)
                            @if($quot->req_id == $enquiry->id)
                                <tr>
                                    <td>Description Of Goods : </td>
                                    <td><input type="text" name="description" value="{{$quot->description}}" class="form-control"></td>
                                </tr>
                                <tr>
                                    <td>Ship Address: </td>
                                    <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">
                                        {{$quot->shipaddress}}</textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bill Address : </td>
                                    <td><textarea required type="text" class="form-control" name="bill" style="resize: none;" rows="5">{{$quot->billaddress}}
                                    </textarea></td>
                                </tr>
                                <tr>
                                    <td> Total Quantity :</td>
                                    <td><input required type="number" class="form-control" name="quantity" placeholder="quantity" id="quan{{$enq->id}}"  value="{{$quot->quantity}}"></td>
                                </tr>
                           @endif
                           @endforeach
                           @endif 
                                <tr>
                                    <td>Price(Per Unit) :</td>
                                    <td>
                                        <label class="alert-success pull-left">Enquiry Price : {{$enq->price != null ? $enq->price : ""}}</label>
                                        <input required type="number" id="unit{{$enq->id}}"  class="form-control" name="price" placeholder="Unit Price" onkeyup="getcalculation('{{$enquiry->id}}')">
                                    </td>
                                </tr> 
                                <tr>
                                        <td>Unit Price without GST :</td>
                                        <td>&nbsp;&nbsp;&nbsp;RS.<label class=" alert-success pull-left" id="withoutgst{{$enq->id}}"></label>/-
                                           <input class="hidden" id="withoutgst1{{$enq->id}}" type="text"  name="withoutgst"  value="">
                                       </td>
                                 </tr>
                                 <tr>
                                        <td>Total Amount : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="display{{$enq->id}}"></label>/-
                                              <input class="hidden" id="display1{{$enq->id}}" value="" name="display">
                                              <label class=" alert-success pull-right" id="lblWord{{$enq->id}}"></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CGST(<label  id="tax1{{$enq->id}}"></label>%) : </td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;CGST <label class=" alert-success pull-left" id="cgst{{$enq->id}}"></label>/-
                                              <input class="hidden" id="cgst1{{$enq->id}}" value="" name="cgst">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>SGST(<label  id="tax2{{$enq->id}}"></label>%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;SGST <label class=" alert-success pull-left" id="sgst{{$enq->id}}"></label>/-
                                              <input class="hidden" id="sgst1{{$enq->id}}" value="" name="sgst">
                                        </td>
                                    </tr>
                                    <tr>
                                      <td>Total Tax :</td>
                                      <td>&nbsp;&nbsp;&nbsp;<label class=" alert-success pull-left" id="totaltax{{$enq->id}}"></label>Total
                                        <input class="hidden" id="totaltax1{{$enq->id}}" value="" name="totaltax">
                                        <label class=" alert-success pull-right" id="lblWord1{{$enq->id}}"></label>
                                      </td>
                                    </tr>
                                    <tr>
                                        <td>IGST( <label  id="tax3{{$enq->id}}"></label>%) : </td>
                                        <td>
                                             &nbsp;&nbsp;&nbsp;IGST <label class=" alert-success pull-left" id="igst{{$enq->id}}"></label>/-
                                             <input id="igst1{{$enq->id}}" class="hidden" type="text" name="igst">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Amount (Including GST) :</td>
                                        <td>
                                              &nbsp;&nbsp;&nbsp;RS .<label class=" alert-success pull-left" id="withgst{{$enq->id}}"></label>/-
                                              <input class="hidden" type="text" id="withgst1{{$enq->id}}" name="withgst" value="">
                                              <label class=" alert-success pull-right" id="lblWord2{{$enq->id}}"></label>
                                        </td>
                                    </tr>         
                            </table>
                                <center><button type="submit" class="btn btn-sm btn-success" onclick="finalsubmit('{{$enq->id}}')">Confirm</button>
                          @endif
                          @endforeach   
                            </form>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
<script type="text/javascript">
    function NumToWord(inputNumber, outputControl,arg){
    var str = new String(inputNumber)
    var splt = str.split("");
    var rev = splt.reverse();
    var once = ['Zero', ' One', ' Two', ' Three', ' Four', ' Five', ' Six', ' Seven', ' Eight', ' Nine'];
    var twos = ['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
    var tens = ['', 'Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety'];

    numLength = rev.length;
    var word = new Array();
    var j = 0;

    for (i = 0; i < numLength; i++) {
        switch (i) {

            case 0:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = '' + once[rev[i]];
                }
                word[j] = word[j];
                break;

            case 1:
                aboveTens();
                break;

            case 2:
                if (rev[i] == 0) {
                    word[j] = '';
                }
                else if ((rev[i - 1] == 0) || (rev[i - 2] == 0)) {
                    word[j] = once[rev[i]] + " Hundred ";
                }
                else {
                    word[j] = once[rev[i]] + " Hundred and";
                }
                break;

            case 3:
                if (rev[i] == 0 || rev[i + 1] == 1) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if ((rev[i + 1] != 0) || (rev[i] > 0)) {
                    word[j] = word[j] + " Thousand";
                }
                break;

                
            case 4:
                aboveTens();
                break;

            case 5:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if (rev[i + 1] !== '0' || rev[i] > '0') {
                    word[j] = word[j] + " Lakh";
                }
                 
                break;

            case 6:
                aboveTens();
                break;

            case 7:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if (rev[i + 1] !== '0' || rev[i] > '0') {
                    word[j] = word[j] + " Crore";
                }                
                break;

            case 8:
                aboveTens();
                break;

            //            This is optional. 

            //            case 9:
            //                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
            //                    word[j] = '';
            //                }
            //                else {
            //                    word[j] = once[rev[i]];
            //                }
            //                if (rev[i + 1] !== '0' || rev[i] > '0') {
            //                    word[j] = word[j] + " Arab";
            //                }
            //                break;

            //            case 10:
            //                aboveTens();
            //                break;

            default: break;
        }
        j++;
    }

    function aboveTens() {
        if (rev[i] == 0) { word[j] = ''; }
        else if (rev[i] == 1) { word[j] = twos[rev[i - 1]]; }
        else { word[j] = tens[rev[i]]; }
    }

    word.reverse();
    var finalOutput = '';
    for (i = 0; i < numLength; i++) {
        finalOutput = finalOutput + word[i];
    }
    document.getElementById("dtow1"+arg).value = finalOutput;
    document.getElementById(outputControl).innerHTML = finalOutput;
}
function NumToWord1(inputNumber, outputControl,arg) {
    var str = new String(inputNumber)
    var splt = str.split("");
    var rev = splt.reverse();
    var once = ['Zero', ' One', ' Two', ' Three', ' Four', ' Five', ' Six', ' Seven', ' Eight', ' Nine'];
    var twos = ['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
    var tens = ['', 'Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety'];

    numLength = rev.length;
    var word = new Array();
    var j = 0;

    for (i = 0; i < numLength; i++) {
        switch (i) {

            case 0:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = '' + once[rev[i]];
                }
                word[j] = word[j];
                break;

            case 1:
                aboveTens();
                break;

            case 2:
                if (rev[i] == 0) {
                    word[j] = '';
                }
                else if ((rev[i - 1] == 0) || (rev[i - 2] == 0)) {
                    word[j] = once[rev[i]] + " Hundred ";
                }
                else {
                    word[j] = once[rev[i]] + " Hundred and";
                }
                break;

            case 3:
                if (rev[i] == 0 || rev[i + 1] == 1) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if ((rev[i + 1] != 0) || (rev[i] > 0)) {
                    word[j] = word[j] + " Thousand";
                }
                break;

                
            case 4:
                aboveTens();
                break;

            case 5:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if (rev[i + 1] !== '0' || rev[i] > '0') {
                    word[j] = word[j] + " Lakh";
                }
                 
                break;

            case 6:
                aboveTens();
                break;

            case 7:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if (rev[i + 1] !== '0' || rev[i] > '0') {
                    word[j] = word[j] + " Crore";
                }                
                break;

            case 8:
                aboveTens();
                break;

            //            This is optional. 

            //            case 9:
            //                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
            //                    word[j] = '';
            //                }
            //                else {
            //                    word[j] = once[rev[i]];
            //                }
            //                if (rev[i + 1] !== '0' || rev[i] > '0') {
            //                    word[j] = word[j] + " Arab";
            //                }
            //                break;

            //            case 10:
            //                aboveTens();
            //                break;

            default: break;
        }
        j++;
    }

    function aboveTens() {
        if (rev[i] == 0) { word[j] = ''; }
        else if (rev[i] == 1) { word[j] = twos[rev[i - 1]]; }
        else { word[j] = tens[rev[i]]; }
    }

    word.reverse();
    var finalOutput = '';
    for (i = 0; i < numLength; i++) {
        finalOutput = finalOutput + word[i];
    }
    document.getElementById("dtow2"+arg).value = finalOutput;
    document.getElementById(outputControl).innerHTML = finalOutput;
}
function NumToWord2(inputNumber, outputControl,arg){
    var str = new String(inputNumber)
    var splt = str.split("");
    var rev = splt.reverse();
    var once = ['Zero', ' One', ' Two', ' Three', ' Four', ' Five', ' Six', ' Seven', ' Eight', ' Nine'];
    var twos = ['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
    var tens = ['', 'Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety'];

    numLength = rev.length;
    var word = new Array();
    var j = 0;

    for (i = 0; i < numLength; i++) {
        switch (i) {

            case 0:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = '' + once[rev[i]];
                }
                word[j] = word[j];
                break;

            case 1:
                aboveTens();
                break;

            case 2:
                if (rev[i] == 0) {
                    word[j] = '';
                }
                else if ((rev[i - 1] == 0) || (rev[i - 2] == 0)) {
                    word[j] = once[rev[i]] + " Hundred ";
                }
                else {
                    word[j] = once[rev[i]] + " Hundred and";
                }
                break;

            case 3:
                if (rev[i] == 0 || rev[i + 1] == 1) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if ((rev[i + 1] != 0) || (rev[i] > 0)) {
                    word[j] = word[j] + " Thousand";
                }
                break;

                
            case 4:
                aboveTens();
                break;

            case 5:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if (rev[i + 1] !== '0' || rev[i] > '0') {
                    word[j] = word[j] + " Lakh";
                }
                 
                break;

            case 6:
                aboveTens();
                break;

            case 7:
                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
                    word[j] = '';
                }
                else {
                    word[j] = once[rev[i]];
                }
                if (rev[i + 1] !== '0' || rev[i] > '0') {
                    word[j] = word[j] + " Crore";
                }                
                break;

            case 8:
                aboveTens();
                break;

            //            This is optional. 

            //            case 9:
            //                if ((rev[i] == 0) || (rev[i + 1] == 1)) {
            //                    word[j] = '';
            //                }
            //                else {
            //                    word[j] = once[rev[i]];
            //                }
            //                if (rev[i + 1] !== '0' || rev[i] > '0') {
            //                    word[j] = word[j] + " Arab";
            //                }
            //                break;

            //            case 10:
            //                aboveTens();
            //                break;

            default: break;
        }
        j++;
    }

    function aboveTens() {
        if (rev[i] == 0) { word[j] = ''; }
        else if (rev[i] == 1) { word[j] = twos[rev[i - 1]]; }
        else { word[j] = tens[rev[i]]; }
    }

    word.reverse();
    var finalOutput = '';
    for (i = 0; i < numLength; i++) {
        finalOutput = finalOutput + word[i];
    }
    document.getElementById('dtow3'+arg).value = finalOutput;
    document.getElementById(outputControl).innerHTML = finalOutput;
}
function getcalculation(arg){
    // gst category wise
var z = document.getElementById('cat'+arg);
  var name = z.options[z.selectedIndex].value;
  var x = document.getElementById('state'+arg);
  var state = x.options[x.selectedIndex].value;
if(name == "CEMENT" && state == "1"){
    var percent = 1.28;
    var gstvalue = 14;
    var sgstvalue = 14;
     var igstvalue = "";
}
else if(name == "CEMENT" && state == "2"){
    var percent = 1.28;
    var gstvalue = 14;
    var sgstvalue = 14;
    var igstvalue = 28;
}
else if(name == "M-SAND" && state == "1"){
    var percent = 1.05;
    var gstvalue = 2.5;
    var sgstvalue = 2.5;
    var igstvalue = "";
}
else if(name == "M-SAND" && state == "2"){
    var percent = 1.05;
    var gstvalue = 2.5;
    var sgstvalue = 2.5;
    var igstvalue = 5;
}
else if( (state == "1") && (name == "PLUMBING" || "STEEL" || "ELECTRICALS") ){
    var percent = 1.18;
    var gstvalue = 9;
    var sgstvalue = 9;
    var igstvalue = "";

   
}
else if( (state == "2") && (name == "PLUMBING" || "STEEL" || "ELECTRICALS") ){
    var percent = 1.18;
    var gstvalue = 9;
    var sgstvalue = 9;
    var igstvalue = 18;

}
else{
    var percent = 1.28;
    var gstvalue = 14;
    var sgstvalue = 14;
    var igstvalue = "";
}
var t1 = gstvalue;
var t2 = sgstvalue;
document.getElementById('tax1'+arg).innerHTML = t1
document.getElementById('tax2'+arg).innerHTML = t2;
if(igstvalue != ""){
    
var t3 = igstvalue;
document.getElementById('tax3'+arg).innerHTML = t3;
}
else{
   
    var t3 = "";
document.getElementById('tax3'+arg).innerHTML = t3;
}
document.getElementById('cgstpercent'+arg).value = gstvalue;
document.getElementById('sgstpercent'+arg).value = sgstvalue;
document.getElementById('gstpercent'+arg).value = percent;
document.getElementById('igstpercent'+arg).value = igstvalue;
// end

var x =document.getElementById('unit'+arg).value;
var y = document.getElementById('quan'+arg).value;
var withoutgst = (x /percent);
var t = (withoutgst * y);
var f = Math.round(t);
var gst = (t * gstvalue)/100;
var sgt = (t * sgstvalue)/100;
var igst = (gst + sgt);
var withgst = (gst + sgt + t );
var final = Math.round(withgst);
var tt = (gst + sgt);
var totaltax = Math.round(tt);

document.getElementById('display'+arg).innerHTML = t;
document.getElementById('display1'+arg).value = f;
document.getElementById('cgst'+arg).innerHTML = gst;
document.getElementById('sgst'+arg).innerHTML = sgt;
if(igstvalue != ""){
document.getElementById('igst'+arg).innerHTML = igst;
document.getElementById('igst1'+arg).value = igst;

}
else{
    document.getElementById('igst'+arg).innerHTML = "";
    document.getElementById('igst1'+arg).value = "";
}
document.getElementById('cgst1'+arg).value = gst;
document.getElementById('sgst1'+arg).value = sgt;
document.getElementById('withgst'+arg).innerHTML = withgst;
document.getElementById('withgst1'+arg).value = final;
document.getElementById('withoutgst'+arg).innerHTML = withoutgst;
document.getElementById('withoutgst1'+arg).value = withoutgst;
document.getElementById('totaltax'+arg).innerHTML = tt;
document.getElementById('totaltax1'+arg).value = totaltax;

}
function finalsubmit(arg){
  var input =  document.getElementById('display1'+arg).value;
  var output = document.getElementById('totaltax1'+arg).value;
  var inout = document.getElementById('withgst1'+arg).value;
  document.getElementById('display1'+arg).addEventListener("click", NumToWord(input,'lblWord'+arg,arg));
  document.getElementById('totaltax1'+arg).addEventListener("click", NumToWord1(output,'lblWord1'+arg,arg));
  document.getElementById('withgst1'+arg).addEventListener("click", NumToWord2(inout,'lblWord2'+arg,arg));
}
</script>
<script type="text/javascript">
function getsuppliername(arg) {
  var x = document.getElementById('cat'+arg);
  var name = x.options[x.selectedIndex].value;
  var y = document.getElementById('state'+arg);
  var state = y.options[y.selectedIndex].value;
  var x = arg;
  $.ajax({
                    type:'GET',
                    url:"{{URL::to('/')}}/getgstvalue",
                    async:false,
                    data:{name : name , x : x , state :state},
                    success: function(response)
                    {    
                        var id = response[0]['id'];
                        console.log(response);
                        var cgst = response[0]['gstvalue'][0]['cgst'];
                        var sgst = response[0]['gstvalue'][0]['sgst'];
                        var igst = response[0]['gstvalue'][0]['igst'];
                        document.getElementById('g1'+id).innerHTML = cgst;
                        document.getElementById('g2'+id).innerHTML = sgst;
                        document.getElementById('g3'+id).innerHTML = igst;

                    }
                });
    }
</script>
@endsection