<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Proforma Invoice</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        body{
            font-size: 11px;
        }
        table {
    border-collapse: collapse;
    border: 1px solid black;
  }
  th, td {
    border: 1px solid black;
  }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default" style="border-color:#f4811f">
                <div class="panel-heading text-center" style="background-color:#f4811f;padding:5px;">
                    <span style="color:white;font-weight:bold;">{{$data['name']}} Ledger Account </span>
                  
                </div>
                <div class="panel-body">  

             <div class="container">
              <div class="pull-right" style="text-align:right;font-weight:bold; 
                    width: 100%;
                    border: 4px solid black;
                    padding:4px;
                    margin-right:40px;">
                 
                   <img src="http://mamahome360.com/img/logo.png" /><br>
                    <h6 style="font-weight:bold;margin-right:10px;">NO.363, 19TH MAIN ROAD 1ST BLOCK <br>
                      RAJAJINAGAR BANGALORE-560010</h6>
                    <h6 style="font-weight:bold;margin-right:10px;">PH-8548888940/41/42/43</h6>   
    
               <div  style="text-align:center;font-weight:bold; 
                    width: 100%;
                    background-color:#cccccc;
                    border:2px solid black;
                    padding:1px;
                    ">   
                    <h6 style="font-weight:bold;">  Ledger Account From {{ date('d-m-Y', strtotime($data['from'])) }}  to {{ date('d-m-Y', strtotime($data['to'])) }}</h6>   
    
              </div> <br><br>

              <div  style="text-align:center;font-weight:bold; 
                    width:70%;
                   
                    border:5px solid black;
                    padding:5px;
                    margin-right:40px;margin-left:20%;">
                   <h6 style="font-weight:bold;text-align:center;">{{$data['name']}}<br><?php  $id = App\CustomerDetails::where('mobile_num',$data['number'])->pluck('customer_id')->first(); ?>ID : {{$id}}<br>Number : {{$data['number']}}</h6>  
                    <h5 style="font-weight:bold;text-align:center;"> 84/1, Banjarpalya Agara grama,kengeri (Hobli)<br>
                              Bangalore 560082 
                  </h5>

                  

              </div><br>
            
                  <table class="table" border="1">
                      <thead style="background-color:#cccccc; ">
                      <tr>
                        <th style="font-weight:bold;">SlNO</th>
                        <th style="font-weight:bold;">Invoice Number/description</th>
                        <th style="font-weight:bold;">Date</th>

                        <th style="font-weight:bold;"> Debit Amount</th>
                        <th style="font-weight:bold;">Credit Amount</th>
                        <th style="font-weight:bold;">Balance</th>
                      </tr>
                      </thead>
                      
                     <tbody>
                      <?php $i=1; ?>
                      @foreach($data['invoice'] as $inc)
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$inc->invoiceno}}<br>{{$inc->remark}}</td>
                        <td>{{date('d-m-Y', strtotime($inc->date))}}</td>
                       <td  style="text-align:center">
                           {{$inc->debit}}
                            
                    </td>
                    
                   <td>
                           {{$inc->credit}}
                       
                   </td>
                        <td>
                          {{$inc->bal}}
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
               </table>
          <br><br>

               <div  style="text-align:center;font-weight:bold; 
                    width: 100%;
                    background-color:#cccccc;
                    border:2px solid black;
                    padding:1px;
                   ">
                    
                      @if($data['bal'] < 0 )
                    <h6 style="font-weight:bold;">
                     



                     Outstanding Amount as on {{$data['bal']}} .DR
                             
                   </h6>  
                   @elseif($data['bal'] > 0)
                    <h6 style="font-weight:bold;">
                     
                   


                     Outstanding Amount as on {{$data['bal']}} .CR
                             
                   </h6> 
                   @else
                     <h6 style="font-weight:bold;">
                     
                   


                     Outstanding Amount as on 
                             
                   </h6> 
                   @endif

                  
    
              </div>
              
              </div>

           
        
      </div>

 </div>
 </div>
 </div>
 </div>
 </div>

</body>
</html>




