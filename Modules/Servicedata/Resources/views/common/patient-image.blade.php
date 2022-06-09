<div class="form-group row">
	@if(isset($imagedata) and $imagedata->fldpic !='')
   <img src="{{$imagedata->fldpic}}" style="margin-left: 150px;">
   @else
   <p style="margin-left: 150px;">Not Available !!</p>
   @endif
</div>