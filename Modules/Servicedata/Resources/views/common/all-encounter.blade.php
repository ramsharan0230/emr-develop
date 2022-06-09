<div class="form-group row">
	
   @if(isset($encounter) and count($encounter) > 0)
   		<ul>
   			@foreach($encounter as $data)
   				<li>{{$data->fldencounterval}}</li>
   			@endforeach
   		</ul>
   @endif
   
</div>