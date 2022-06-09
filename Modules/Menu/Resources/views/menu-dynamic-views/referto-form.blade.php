<form method="POST" action="{{route('patient.outcome.menu.addreferto')}}">
    @csrf
    <div class="from-group">
        <label for="name" class="">Select Refer Location</label>
        <div class="form-group">
            <select name="location" class="form-control" id="location">
                <option value=""></option>
                @if(isset($referlist) and count($referlist) > 0)
                    @foreach($referlist as $list)
                        <option value="{{$list->fldlocation}}" @if($enpatient->fldreferto == $list->fldcode)selected="selected"@endif>{{$list->fldlocation}}</option>
                    @endforeach
                @endif

            </select>
            <input type="hidden" name="encounterId" value="{{$encounterId}}">
        </div>
    </div>
    <div class="row top-req">
	    <div class="col-md-12">
	    	<div class="form-group text-right" >
	    		<input type="submit" value="Add" class="btn btn-primary">
	    		<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
	    	</div>
	    </div>
    </div>

</form>
