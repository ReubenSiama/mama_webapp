@extends('layouts.sales')
@section('content')
<div class="">
	
	<div class="col-md-12">
		<div class="panel panel-default" style="border-color:green">
			 <div class="panel-heading" style="background-color: green;color:white;"><b>
			 Sales Engineer Training Video		 	
			 </b>
                    @if(session('ErrorFile'))
                        <div class="alert-danger pull-right">{{ session('ErrorFile' )}}</div>
                    @endif 
                </div>
			<div class="panel-body">
				   <?php $url = Helpers::geturl(); ?>
                     <table class="table table-responsive" >
                     	
                            @foreach($video as $video)
							
									<div class="col-md-4 " style="border: solid 1px green;">
		    						<br>
									<video class="img img-responsive" controls controlslist="nodownload">
		                                      <source src="{{ $url}}/trainingvideo/{{ $video->upload }}" type="video/mp4">
		                                      <source src="{{ $url}}/trainingvideo/{{ $video->upload }}" type="video/ogg">
		                              
		                             </video><br>
		                              <center style="color: green;font-size:20px;">{{$video->remark }}</center>
		                           </div>
                             @endforeach
                              
                         </table>
	       	</div>
	    </div>
	</div>
</div> 
                    
						
@endsection
