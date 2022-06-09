<table class="table table-sm table-striped">
    <thead>
    <tr>
        <th style="width: 580px">Item Name(Generic)</th>
        <th class="text-center">Opening Stock</th>
        <!-- <th class="text-center">Batch</th>
        <th class="text-center">Rate</th> -->
        {{--        <th><i class="fas fa-arrow-up text-success"></i> Purchase</th>--}}
        <th class="text-center"><i class="fas fa-arrow-down text-danger"></i> Sales/Transaction</th>
        <th class="text-center">Remaining</th>
    </tr>
    </thead>
    <tbody>
    @if($tableEntry)
        @forelse($tableEntry as $entry)
            <tr>
                <td>{{ $entry->medicine ? $entry->medicine->fldstockid : '' }}</td>
                @php
                    //sales quantity
                    $sales = $entry?$entry->qty:0;
                    //opening stock, current stock + sales
                    $currentQty = $entry->medicine ? $entry->medicine->where('fldcomp', $entry->fldcomp)->where('fldstockid', $entry->flditemname)->sum('fldqty'):0;
                    $openingStock =  $currentQty + $sales;

                    $bulkSale = $entry->medicine && count($entry->medicine->bulkSale) ? $entry->medicine->bulkSale->sum('fldqtydisp') - $entry->medicine->bulkSale->sum('fldqtyret'):0;

                    $adjustment = $entry->adjustment && count($entry->medicine->adjustment) ? $entry->medicine->adjustment->sum('fldcompqty') - $entry->adjustment->sum('fldcurrqty'):0;
                    if ($departmentComp != 0){
                        $transferFrom = $entry->transfer && count($entry->medicine->transfer) ? $entry->medicine->transfer->where('fldfromcomp', $departmentComp)->sum('fldqty'):0;
                        $transferTo = $entry->transfer && count($entry->medicine->transfer) ? $entry->medicine->transfer->where('fldtocomp', $departmentComp)->sum('fldqty'):0;
                    //curqty - (purqty + recvqty) + (salqty + bulqty + sentqty + adjqty)
                    }else{
                        $transferFrom = 0;
                        $transferTo = 0;
                    }
                @endphp
                {{--opening stock--}}
                <td class="text-center">{{ $openingStock + $transferTo - $transferFrom + $adjustment }}</td>
                {{--purchase--}}
                {{--                <td class="text-center">{{ $purchase }}</td>--}}
                {{--sales/transaction--}}
                <td class="text-center">{{ $sales }}</td>
                {{--remaining--}}
                @php
                    $remaining = $currentQty;
                @endphp
                <td class='text-center'>{{ $remaining }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No data found.</td>
            </tr>
        @endforelse
    @endif

    </tbody>
</table>
