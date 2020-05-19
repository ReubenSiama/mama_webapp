<?php
  $user = Auth::user()->group_id;
  $ext = ($user == 4? "layouts.amheader":"layouts.app");
?>
@extends($ext)
@section('content')

<div class="container">
    <div class="col-md-12">
        <div class="panel panel-default" style="text-align: center;">
                    <div class="panel-heading"><b>Here You can add price for each Brands with respect to category
                    </div>
                    <form method="POST" action="{{ URL::to('/') }}/addpricetobrand" enctype="multipart/form-data">
                        {{ csrf_field() }}
                         <div class="container">          
                             <div class="col-md-4">
                                 <br>
                                 <div class="panel panel-default" style="text-align: left;">
                                        <div class="panel-heading"><b>select Date
                                        </div> 
                                        <br>
                                           <input type="date" class="form-control" name="date" required>
                                            
                                       <br>
                                </div>
                                    <div class="panel panel-default" style="text-align: left;">
                                            <div class="panel-heading"><b>select Category
                                            </div> 
                                               <br>
                                                <select id="cat"  onchange="get_Category()"  class="form-control" required name="cat_id">
                                                        @foreach($cat as $cats) 
                                                    <option value="{{$cats->id}}" >
                                                   {{$cats->category_name}}
                                                    </option>
                                                        @endforeach
                                                </select>
                                                
                                           <br>
                                    </div>
                               </div>
                     </div>
                     <div class="container">          
                            <div class="col-md-4">
                                <br>
                                   <div class="panel panel-default" style="text-align: left;">
                                           <div class="panel-heading"><b>Select Sub Category 
                                           </div> 
                                              <br>
                                               <select  class="form-control" name="sub_cat_id" id="sub_cat" required></select>
                                               
                                          <br>
                                   </div>
                              </div>
                    </div>
                    <div class="container">          
                            <div class="col-md-4">
                                <br>
                                   <div class="panel panel-default" style="text-align: left;">
                                           <div class="panel-heading"><b>Select Supplier
                                           </div> 
                                              <br>
                                               <select  class="form-control" name="supplier_id" id="sp" required>
                                               </select>
                                          <br>
                                   </div>
                              </div>
                    </div>

                    <div class="container">          
                            <div class="col-md-4">
                                <br>
                                   <div class="panel panel-default" style="text-align: left;">
                                           <div class="panel-heading"><b>Select Brand  
                                           </div> 
                                              <br>
                                               <select id="brand" class="form-control" name="brand_id" required>
                                               </select>
                                               
                                          <br>
                                   </div>
                                   <input type="text" name="pricebrand" placeholder= "enter price" class="form-control" required>
                                   <br>
                                   <div>
                                   <button type="submit" class="btn btn-success input-sm">Submit</button>
                                   </div>
                                   <br>
                             <br>
                                </div>
                   
                            </div>
                    
     </div>
 </div>      
</form>
</div>
</div>
<script type="text/javascript">

    function get_Category()
    {
        
        var sel= document.getElementById('cat').value ;
       
        if(sel)
        {
            $.ajax({
                type: "GET",
                url: "{{URL::to('/')}}/get_cat",
                data: { cat_id: sel },
                async: false,
                success: function(response)
                {
                    if(response == 'No category Found !!!')
                    {
                        document.getElementById('error').innerHTML = '<h4>No category Found !!!</h4>';
                        document.getElementById('error').style,display = 'initial';
                    }
                    else
                    
                    {    
                        console.log(response);
                     var html = "<option value='' disabled selected>---Select---</option>";
                     var html2 = "<option value='' disabled selected>---Select---</option>";
                     var html3 = "<option value='' disabled selected>---Select---</option>";

                         for(var i=0; i< response.new.length; i++)
                    { 
                        html += "<option value='"+response.new[i].id+"'>"+response.new[i].sub_cat_name+"</option>";
                    }
                    for(var i=0; i< response.old.length; i++)
                    { 
                         html2 += "<option value='"+response.old[i].id+"'>"+response.old[i].brand+"</option>";
                    }   
                    for(var i=0; i< response.sp.length; i++)
                    { 
                         html3 += "<option value='"+response.sp[i].supplier_id+"'>"+response.sp[i].company_name+"</option>";
                    }                 
                    document.getElementById('sub_cat').innerHTML = html;
                    document.getElementById('brand').innerHTML = html2;
                    document.getElementById('sp').innerHTML = html3;

                  }
                    
                },
                error:function(error){
                    console.log(error);
                }
            });
        }
    }

   

   
</script>
@endsection


