<?php
  $user = Auth::user()->group_id;
  $ext = ($user == 4? "layouts.amheader":"layouts.app");
?>
@extends($ext)
@section('content')

<div class="container">
    <div class="col-md-14">
        <div class="panel panel-default" style="text-align: center;">
                    <div class="panel-heading"><b>Todays Price
                    </div>
                   
                          
        </div>
        <div class="col-md-4">
                
                <div class="panel panel-default" style="text-align: center;">
                        <div class="panel-heading"><b>Choose Supplier and Category
                        </div>
                        <div class="panel-body">
                                <select required class="input-sm form-control">
                                        <option value="">--Select Category--</option>
                                        
                                        <option ></option>
                                      
                                    </select>
                                <br>
                               
                                    <select required class="input-sm form-control">
                                            <option value="">--Select Supplier--</option>
                                          
                                            <option ></option>
                                          
                                        </select>
                        </div>
                </div>
                
                


                   
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
                         html3 += "<option value='"+response.sp[i].Supplier_id+"'>"+response.sp[i].company_name+"</option>";
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


