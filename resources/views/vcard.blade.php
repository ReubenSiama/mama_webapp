@extends('layouts.app')
@section('content')  
  <span class="pull-right"> @include('flash-message')</span>
  <div class="row">
<div class="col-md-6  ">
       
        <div class="panel panel-danger" style="border-color:#337ab7 ">
            <div class="panel-heading" style="background-color:#337ab7;color:white;font-weight:bold;font-size:15px;">
              <center>Proposed Projects customers WhatsApp Deatils </center>
                
            </div>
            <div style="overflow-x: scroll;" class="panel-body">
                <form action="{{URL::to('/')}}/vcard" method="get" >
                    
                 {{ csrf_field() }}
                  <div class="col-md-6">
                     <?php $users = App\User::where('department_id','!=',10)->get() ?>
                 <select  name="user"   class="form-control">
                    <option value="">--Select User--</option>
                   

                   @foreach($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>
                     @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                    
                 <select  name="days"   class="form-control">
                    <option value="">--Select Days--</option>
                    <option value="1"> 1 Day</option>
                    <option>2 Days</option>
                    <option>3 Days</option>
                    <option>4 Days</option>
                    <option>5 Days</option>
                    <option>6 Days</option>
                    <option>7 Days</option>
                    <option>8 Days</option>
                    <option>9 Days</option>
                    <option>10 Days</option>
                    <option>11 Days</option>
                    <option>12 Days</option>
                    <option>13 Days</option>
                    <option>14 Days</option>
                    <option>15 Days</option>
                    <option>16 Days</option>
                    <option>17 Days</option>
                    <option>18 Days</option>
                    <option>19 Days</option>
                    <option>20 Days</option>
                    <option>21 Days</option>
                    <option>22 Days</option>
                    <option>23 Days</option>


                   
                  </select>
                </div>
                 <div class="col-md-1">
                  <input type="submit" class="btn btn-sm btn-warning" value="Get Details">
                </div>
            </form>
                @if(count($pro) > 0)
                <table id="manufacturer" class="table table-responsive" border=1><br>

                    <thead style="background-color:#ff9900">

                        <th>SLNO</th>
                        <th>Customer Name</th>
                          <th>Whatsapp Number</th>
                          <th>#</th>
                    </thead>
                    <?php $i=1 ?>
                    <tbody>
                      @foreach($pro as $df)
                      <?php $ssm = App\CustomerDetails::where('mobile_num',$df->whatsapp)->first(); ?>
                          @if(count($ssm) == 0)
                       <tr>
                        <td> {{$i++}}</td>
                          <td>{{$df->procurement_name}}</td>
                          
                          <td>{{$df->whatsapp}} </td>
                         
                          <td> <a href="{{URL::to('/')}}/vcard?id={{$df->p_p_c_id}}" class="btn btn-sm btn-warning" >Get Contact</a><td>

                       </tr>
                       @endif
                       @endforeach
                       <?php $m=$i+1; ?>
                       @if(count($result) > 0)
                       @foreach($result as $res)
                         <?php 
   $p = App\ProcurementDetails::where('p_p_c_id',$res)->where('whatsapp',"!=",NULL)->first();

    if(count($p) > 0){

        $name = $p->procurement_name;
        $whatsapp = "p_".$p->whatsapp;
    }else if(count(App\OwnerDetails::where('p_p_c_id',$res)->where('whatsapp',"!=",NULL)->first()) > 0){

        $p = App\OwnerDetails::where('p_p_c_id',$res)->where('whatsapp',"!=",NULL)->first();
        $name = $p->owner_name;
        $whatsapp = "O_".$p->whatsapp;
    }else if(count(App\ContractorDetails::select('p_p_c_id','whatsapp')->where('p_p_c_id',$res)->where('whatsapp','!=',NULL)->first()) > 0){
         $p =App\ContractorDetails::select('p_p_c_id','whatsapp','contractor_name')->where('p_p_c_id',$res)->where('whatsapp','!=',NULL)->first();
        $name = $p->contractor_name;
        $whatsapp ="c_".$p->whatsapp;
    }else{
       $p =App\Builder::select('p_p_c_id','whatsapp','builder_name')->where('p_p_c_id',$res)->where('whatsapp','!=',NULL)->first();
        $name = $p->builder_name;
        $whatsapp = "B_".$p->whatsapp;     
    }





                         ?>
                          <?php $ssmm = App\CustomerDetails::where('mobile_num',$whatsapp)->first(); ?>
                          @if(count($ssmm) == 0)
                       <tr>
                          <td>{{$m++}}</td>
                         <td>{{$name}}</td>

                         <td>{{$whatsapp}}</td>
                         <td> <a href="{{URL::to('/')}}/vcard?id={{$res}}" class="btn btn-sm btn-warning" >Get Contact</a>
                       </td>
                     </tr>
                     @endif

          @endforeach   
          @endif            

                       
                    </tbody>
                   
                </table>
                @endif
            </div>
        </div>
</div>


<div class="col-md-6 ">
       
        <div class="panel panel-danger" style="border-color:#337ab7 ">
            <div class="panel-heading" style="background-color:#337ab7;color:white;font-weight:bold;font-size:15px;">
              <center>Proposed Manufacturers customers WhatsApp Deatils </center>
                
            </div>
            <div style="overflow-x: scroll;" class="panel-body">
                <form action="{{URL::to('/')}}/manuvcard" method="get" >
                    
                 {{ csrf_field() }}
                  <div class="col-md-6">
                     <?php $users = App\User::where('department_id','!=',10)->get() ?>
                 <select  name="user"   class="form-control">
                    <option value="">--Select user--</option>
                   

                   @foreach($users as $user)
                      <option value="{{$user->id}}">{{$user->name}}</option>
                     @endforeach
                  </select>
                </div>
                 <div class="col-md-4">
                    
                 <select  name="days"   class="form-control">
                    <option value="">--Select Days--</option>
                    <option value="1"> 1 Day</option>
                    <option>2 Days</option>
                    <option>3 Days</option>
                    <option>4 Days</option>
                    <option>5 Days</option>
                    <option>6 Days</option>
                    <option>7 Days</option>
                    <option>8 Days</option>
                    <option>9 Days</option>
                    <option>10 Days</option>
                    <option>11 Days</option>
                    <option>12 Days</option>
                    <option>13 Days</option>
                    <option>14 Days</option>
                    <option>15 Days</option>
                    <option>16 Days</option>
                    <option>17 Days</option>
                    <option>18 Days</option>
                    <option>19 Days</option>
                    <option>20 Days</option>
                    <option>21 Days</option>
                    <option>22 Days</option>
                    <option>23 Days</option>


                   
                  </select>
                </div>
                 <div class="col-md-1">
                  <input type="submit" class="btn btn-sm btn-warning" value="Get Details">
                </div>
            </form>
                @if(count($ppppp) > 0)
                <table id="manufacturer" class="table table-responsive" border=1><br>
                    <thead style="background-color:#ff9900">
                        <th>SLNO</th>
                        <th>Customer Name</th>
                          <th>Whatsapp Number</th>
                          <th>ppid</th>
                          <th>#</th>
                    </thead>
                    <?php $i=1 ?>
                    <tbody>
                      @foreach($ppppp as $dff)
                       <tr>
                        <td> {{$i++}}</td>
                          <td>{{$dff->name}}</td>
                          
                          <td>{{$dff->whatsapp}} </td>
                          <td>{{$dff->p_p_c_id}}</td>
                         
                          <td> <a href="{{URL::to('/')}}/manuvcard?id={{$dff->p_p_c_id}}" class="btn btn-sm btn-warning">Get Contact</a><td>

                       </tr>
                       @endforeach
                      
                    </tbody>
                   
                </table>
                @endif
            </div>
        </div>
</div>

</div>

@endsection