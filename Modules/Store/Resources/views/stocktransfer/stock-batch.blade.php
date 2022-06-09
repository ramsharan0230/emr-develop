<option value="">Select</option>
@foreach($newOrderData as $batch=>$newOrder)
    @php
        $totqty = 0;
    @endphp
    @foreach($newOrder as $new)
        @php
            $dataqty = $new->fldqty;
        @endphp
        @if(count($new->pendingTransfer) > 0)
            @php
                $dataqty = $dataqty - $new->pendingTransfer->sum('fldqty');
            @endphp
        @endif
        @php
            $totqty += $dataqty;
        @endphp
    @endforeach
    <option value="{{ $batch }}" data-price="{{ $newOrder[0]->fldsellpr }}" data-qty="{{ $totqty }}" data-expiry="{{ $newOrder[0]->fldexpiry }}">{{ $batch }}</option>
@endforeach
