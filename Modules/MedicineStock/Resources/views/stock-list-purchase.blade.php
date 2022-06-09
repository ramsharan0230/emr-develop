<table class="table table-sm table-striped">
    <thead>
    <tr>
        <th style="width: 580px">Item Name</th>
        <th class="text-center">Opening Stock</th>
        <th class="text-center"><i class="fas fa-arrow-up text-success"></i> Purchase</th>
        {{--        <th><i class="fas fa-arrow-down text-danger"></i> Sales/Transaction</th>--}}
        <th class="text-center">Remaining</th>
    </tr>
    </thead>
    <tbody>
    @if($tableEntry)
        @forelse($tableEntry as $entry)
            <tr>
                <td>{{ $entry->Entry ? $entry->Entry->fldstockid : '' }}</td>
                @php
                    //sales quantity
                    $sales = $entry->EntryByStockName->patBillingByName && isset($entry->EntryByStockName->patBillingByName->qty) ? $entry->EntryByStockName->patBillingByName->qty : 0;
                    //opening stock, current stock + sales
                    $currentQty = $entry->EntryByStockName ? $entry->EntryByStockName->where('fldstockid', $entry->fldstockid)->where('fldcomp', $entry->fldcomp)->sum('fldqty'):0;
                    $openingStock =  $currentQty + $sales;
                    // purchase
                    $purchase = ($entry) ? $entry->where('fldstockid', $entry->fldstockid)->where('fldcomp', $entry->fldcomp)->sum('fldtotalqty'):0;

                    $bulkSale = $entry->EntryByStockName && count($entry->EntryByStockName->bulkSale) ? $entry->EntryByStockName->bulkSale->sum('fldqtydisp') - $entry->EntryByStockName->bulkSale->sum('fldqtyret'):0;

                    $fldcompqty = $entry->EntryByStockName && count($entry->EntryByStockName->adjustment) && isset($entry->EntryByStockName->adjustment->fldcompqty) ? $entry->EntryByStockName->adjustment->sum('fldcompqty'):0;
                    $fldcurrqty = $entry->EntryByStockName && count($entry->EntryByStockName->adjustment) && isset($entry->EntryByStockName->adjustment->fldcurrqty) ? $entry->EntryByStockName->adjustment->sum('fldcurrqty'):0;

                    $adjustment = $entry->EntryByStockName && count($entry->EntryByStockName->adjustment) ? $fldcompqty - $fldcurrqty:0;

                    if ($departmentComp != 0){
                        $transferFrom = $entry->EntryByStockName && count($entry->EntryByStockName->transfer) ? $entry->EntryByStockName->transfer->where('fldfromcomp', $departmentComp)->sum('fldqty'):0;
                        $transferTo = $entry->EntryByStockName && count($entry->EntryByStockName->transfer) ? $entry->EntryByStockName->transfer->where('fldtocomp', $departmentComp)->sum('fldqty'):0;
                    //curqty - (purqty + recvqty) + (salqty + bulqty + sentqty + adjqty)
                    }else{
                        $transferFrom = 0;
                        $transferTo = 0;
                    }
                @endphp
                {{--opening stock--}}
                <td class="text-center">{{ $openingStock + $transferTo - $transferFrom + $adjustment }}</td>
                {{--purchase--}}
                <td class="text-center">{{ $purchase }}</td>
                {{--sales/transaction--}}
                {{--                <td class="text-center">{{ $sales }}</td>--}}
                {{--remaining--}}
                @php
                    $remaining = $currentQty + $purchase;
                @endphp
                <td class='{{ $purchase > $sales ?"text-success":"text-danger" }} text-center'>{{ $remaining }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No data found.</td>
            </tr>
        @endforelse
    @endif
    </tbody>
</table>
