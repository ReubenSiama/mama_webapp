<html>

<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body>
  <div class="topnav">
  <a class="active" href="{{ URL::to('/') }}/home" style="font-size:1.1em;font-family:Times New Roman;margin-left:15%;">Home</a>
</div><br><br>
<style>

.topnav {
  overflow: hidden;
  background-color:#e7e7e7;
  margin-right: 0;
margin-left: 0;
}

.topnav a {
  float: left;
  display: block;
  color: black;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  
  color: black;
}

.topnav .search-container {
  float: right;
}

.topnav input[type=text] {
  padding: 6px;
  margin-top: 8px;
  font-size: 17px;
  border: none;
}

.topnav .search-container button {
  float: right;
  padding: 6px 10px;
  margin-top: 8px;
  margin-right: 16px;
  background: #ddd;
  font-size: 17px;
  border: none;
  cursor: pointer;
}

.topnav .search-container button:hover {
  background: #ccc;
}

@media screen and (max-width: 600px) {
  .topnav .search-container {
    float: none;
  }
  .topnav a, .topnav input[type=text], .topnav .search-container button {
    float: none;
    display: block;
    text-align: left;
    width: 100%;
    margin: 0;
    padding: 14px;
  }
  .topnav input[type=text] {
    border: 1px solid #ccc;  
  }
}
</style>
  <div class="container">
<span class="pull-right"> @include('flash-message')</span>
    
    <h2 align="center">QUOtATION</h2>
    <div class="form-group">
       
           <form  action="{{URL::to('/')}}/getproductquan" method="post" id="mailajob" id="add_name">  
                   {{ csrf_field() }}
        <div class="table-responsive">
          <input type="hidden" name="enqid" value="{{$req->id}}">
          <input type="hidden" name="projectid" value="{{$req->project_id}}">
          <input type="hidden" name="manuid" value="{{$req->manu_id}}">
          
          <table class="table table-bordered" id="articles">
             <tr>
                                    <td>Description Of Goods : </td>
                                    <td><input type="text" name="description" value="{{$req->main_category}}" id="cat" class="form-control"></td>
                                </tr>
                              
                               <tr>
                                    <td>Ship Address : </td>
                                    <td><textarea required type="text" name="ship" class="form-control" style="resize: none;" rows="5">
                                       {{$req->ship}}
                                    </textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Bill Address : </td>
                                    <td><textarea required type="text" class="form-control" name="bill" style="resize: none;" rows="5">{{$req->billadress}}
                                    </textarea></td>
                                </tr>
                                 <tr>
                        <td>GST Info</td>
                      <td>
                        <select name="gsttype" class="form-control" onclick="yadav()" id="state">
                        <option value="">---select state---</option>
                        @foreach($state as $s)
                        <option value="{{$s->id}}">{{$s->state_name}}</option>
                         @endforeach
                         
                      </select>
                    </td>
                      <td><input type="text" name="gstpercent" class="form-control" placeholder="gst percent value" id="sval" readonly></td>
                      <td><button  type="submit" class="btn btn-info"  />Get GST data</td>  </td>
             </tr>
            <tr class="rrjeshta">

             <td>product name <input type="text" name="product[]" placeholder="Enter your Product Name" class="form-control name_list" /></td>

                   <td>Product Description<input type="text" name="desc[]" placeholder="Enter your Product Description" class="form-control name_list" /></td>
              <td>quantity<input  type="number" id="quantity-0" name="quantity[]" placeholder="quantity" class="form-control name_list" /></td>
              <td>price<input  type="number" id="price-0" name="price[]" placeholder="price" class="form-control name_list" /></td>

              <td>total<input type="text" id="total-0" name="total[]" placeholder="total" class="form-control name_list"  readonly /></td>
              
              <td><button type="button" name="add" id="add" class="btn btn-success">Add new</button></td>
            </tr>
           
          </table>
          
         <input type="button"  type="submit" class="btn btn-info" value="Submit" onclick="document.getElementById('mailajob').submit();" />  
        </div>
   <table class="table" border="1">
     <tr>
       <td>GROSS AMOUNT</td>
        <td><input type="text" id="total" name="totalamount"></td>
      </tr>
      <tr>
         <td>CGST</td>
         <td><input type="text" id="cgst" name="cgst"></td>

      </tr> 
       <tr>
         <td>SGST</td>
         <td><input type="text" id="sgst" name="sgst"></td>

      </tr> 
      <tr>
         <td>IGST</td>
         <td><input type="text" id="igst" name="igst"></td>

      </tr>   
      <tr>
         <td>Including gst Amount</td>
         <td><input type="text" id="withgstamount" name="withgstamount"></td>

      </tr>  
   </table>
      </form>
    </div>
  </div>
</body>
<script type="text/javascript">
  $(document).ready(function() {
  var i = 0;
  $("#quantity-" + i).change(function() {
    upd_art(i)
  });
  $("#price-" + i).change(function() {
    upd_art(i)
  });


  $('#add').click(function() {
    i++;
    $('#articles').append('<tr id="row' + i + '"><td><input type="text" name="product[]" placeholder="Enter your Product Name" class="form-control name_list" /></td><td><input type="text" name="desc[]" placeholder="Enter your Product Description" class="form-control name_list" /></td><td><input type="number"  id="quantity-' + i + '" name="quantity[]" placeholder="quantity" class="form-control name_list" /></td> <td><input type="number" id="price-' + i + '" name="price[]"  placeholder="price" class="form-control name_list" /></td> <td><input type="number" id="total-' + i + '" name="total[]" placeholder="total" class="form-control name_list"  /></td><td></td> <td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button></td></tr>');

    $("#quantity-" + i).change(function() {
      upd_art(i)
    });
    $("#price-" + i).change(function() {
      upd_art(i)
    });
    
  });


  $(document).on('click', '.btn_remove', function() {
    var button_id = $(this).attr("id");
    $('#row' + button_id + '').remove();
  });

  

  function upd_art(i) {

    var qty = $('#quantity-' + i).val();
    var price = $('#price-' + i).val();
    var totNumber = (qty * price);
    var tot = totNumber.toFixed(2);
    $('#total-' + i).val(tot);
    
     
   
  }

});

$(document).ready(function () {
  $('#mailajob').on('submit',function(e){
    
    e.preventDefault();

    $.ajax({
      type: "post",
      url: "{{URL::to('/')}}/getgstdata",
      data: $('#mailajob').serialize(),
      success: function (response) {
         console.log(response);
                document.getElementById('withgstamount').value=response['total'];
                document.getElementById('cgst').value=response['gst'];
                document.getElementById('sgst').value=response['sgst'];
                 document.getElementById('igst').value=response['igst'];
                document.getElementById('total').value=response['withgst'];
 




      },
     error: function (error) {
                     
                      console.log(error);
                    
                    }
    });


  });
});

function yadav(){
    
var e = document.getElementById('cat').value;
  
var state = document.getElementById('state');
var st = state.options[state.selectedIndex].value;
   
    $.ajax({
            type:'GET',
            url:"{{URL::to('/')}}/getgstvalue1",
            async:false,
            data:{cat : e, state : st},
            success: function(response)
            {
                  

                console.log(response);

                document.getElementById('sval').value = response;
             }
       });   

  

 }
</script>

</html>