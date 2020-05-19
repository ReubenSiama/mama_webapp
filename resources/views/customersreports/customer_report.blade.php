@extends('layouts.app')
@section('content') 

<div class="container">
<div class="col-md-4">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: #a9dfa9;color:white;padding:15px;"><b>Total Customers Count: {{array_sum($total)}} </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Type of Customers</th>
                 <th>Total Cuatomers</th>
               </thead>
               <tbody>
                 @foreach($data as $df)
                  <tr>
                     <td>{{$df['type']}}</td> 
                     <td>{{$df['count']}}</td>
                 </tr>
                 @endforeach
               </tbody>
            </table>


   </div>
   </div>
   <div class="col-md-4">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: #a9dfa9;color:white;padding:15px;"><b>Total Direct Company Customers {{array_sum($company)}} </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Type of Customers</th>
                 <th>Total Cuatomers</th>
               </thead>
               <tbody>
                 @foreach($dddd as $df)
                  <tr>
                     <td>{{$df['type']}}</td> 
                     <td>{{$df['count']}}</td>
                 </tr>
                 @endforeach
               </tbody>
            </table>


   </div>
   </div>
   
<div class="col-md-4">

    <div class="panel panel-default" style="border-color:green;"> 
                <div class="panel-heading text-center" style="background-color: #a9dfa9;color:white;padding:15px;"><b>Assigned Customers {{array_sum($totalassign)}} </b>
             </div>
            <table class="table" border="1">
               <thead>
                 <th>Type of Customers</th>
                 <th>Total Cuatomers</th>
               </thead>
               <tbody>

                 @foreach($assignedcustids as $df)
                  <tr>
                     <td>{{$df['type']}}</td> 
                     <td>{{$df['count']}}</td>
                 </tr>
                 @endforeach
               </tbody>
            </table>


   </div>
   </div>
 </div>
 @endsection