@extends('layouts.app')
@section('content') 
<form method="GET" action="{{ URL::to('/') }}/test">
                    <div class="col-md-7 col-md-offset-2">
                            <div class="col-md-3">
                                <label>From (projectsize in sqt)</label>
                                <input  value = "{{ isset($_GET['from']) ? $_GET['from']: '' }}" type="number" class="form-control" name="from">
                            </div>
                            <div class="col-md-3">
                                <label>To (projectsize in sqt)</label>
                                <input  value = "{{ isset($_GET['to']) ? $_GET['to']: '' }}" type="number" class="form-control" name="to">
                            </div>
                                 <div class="col-md-3">
                                <label>Select Stage</label>
                               <select  class="form-control" name="status">
                                   <option value="">---Select---</option>
                                   <option value="Planning">Planning</option>
                                   <option value="Digging">Digging</option>
                                 
                                   <option value="Pillars">Pillars</option>
                                   <option value="Walls">Walls</option>
                                 
                                 
                                   <option value="Roofing">Roofing</option>
                                   <option value="Electrical">Electrical</option>
                                   <option value="Plumbing">Plumbing</option>
                                   <option value="Plastering">Plastering</option>
                                   <option value="Flooring">Flooring</option>
                                   <option value="Carpentry">Carpentry</option>
                                   <option value="Paintings">Paintings</option>
                                   <option value="Fixtures">Fixtures</option>
                                   <option value="Completion">Completion</option>
                               </select>
                            </div>
                            
                             <div class="col-md-3">
                              <label>Get</label>
                              <button type="submit" class="form-control  btn btn-warning btn-sm">submit</button>  
                            </div>
          </div>    
          </form>
          <br><br>
<div class="col-md-7 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading" style="background-color: green;"><p style="color:white;">Assigned Phone Numbers</p>
          <button type="button" onclick="history.back(-1)" class="bk-btn-triangle pull-right" style="margin-top:-30px;" > <i class="fa fa-arrow-circle-left" style="padding:5px;width:50px;"></i></button>
        </div>
        <div class="panel-body"> 
                <form method="POST" name="myform" action="{{URL::to('/')}}/savenumber">
                  {{csrf_field()}}
                    <table class="table table-responsive table-striped table-hover" class="table">
                              <tbody>
                        <td><label>Enter Phone Number</label></td>
                        <td>:</td>
                        <td><input required type="text" id="num" class="form-control" name="phNo" onblur="checklength('scontact');" onkeyup="getnum()" placeholder="Enter Your Mobile Number"></td>
                        <td><button type="submit">submit</button>
                    </tbody>
                    </table>
            </form>
            
          
                       
                         
                        
                            <table class="table table-striped">
                              <tr>
                            <?php
                              $j = 0;
                              $k =0;
                               
                                  
                              for($i = 0; $i < count($number); $i++){
                                echo("<td>".$number[$i]['procurement_contact_no']."</td>");
                                $j++;
                                
                                if($j == 5){
                                  $j = 0;
                                  echo("</tr>");
                                }
                                
                                 

                              }
                            ?>
                              </tr>
                             
                            </table>
                         
                                   
              
         
                          {{ $number->appends(request()->except('page'))->links() }}
               </div>
              </div>
           </div> 

<script type="text/javascript">
   function getnum()
  {

    var num=document.getElementById('num').value;

      if(isNaN(num)){
        
        document.getElementById('num').value="";
        myform.equantity.focus();
         }
  }
function checklength()
  {
    var x = document.getElementById('num');
    
        if(x.value.length != 10)
        {
            alert('Please Enter 10 Digits in Phone Number');
            document.getElementById('num').value = '';
            return false;
        }
      
  }
 
</script>
@if(session('success'))
          <script>
            swal("Success","{{ session('success')}}","success");
          </script>
 @endif
 @endsection
