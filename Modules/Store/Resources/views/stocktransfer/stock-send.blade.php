<option value="">Select</option>
@foreach($newOrderData as $new)
    <option value="{{ $new->fldstockid }}">{{ $new->fldstockid }}</option>
@endforeach
