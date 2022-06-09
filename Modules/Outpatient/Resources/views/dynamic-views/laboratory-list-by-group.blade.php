@if(count($itemsForMultiselect))
    @php
        $counter = 1;
    @endphp
    @foreach($itemsForMultiselect as $fldName)
        <li class="form-check">
            <input type="checkbox" name="labreport[]" class="form-check-input lab-radio-check" {{--id="items{{ $counter }}"--}} value="{{ $fldName->fldgroupname }}">
            <label class="form-check-label" for="items{{ $counter }}">{{ $fldName->fldgroupname }}</label>
        </li>
        @php
            $counter++;
        @endphp
    @endforeach
@endif
