<div class="table-responsive res-table" style="max-height: none;">
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th>SNo</th>
            @if($is_supplier)
            <th>Supplier Name</th>
            @endif
            <th>Supplier Bill No.</th>
            <th>Purchase Reference No.</th>
            <th>Volume</th>
            <th>Generic</th>
            <th>Brand</th>
            <th>Stock No.</th>
            <th>Pur Qty</th>
            <th>Sup Cost</th>
            <th>Sell Cost</th>
            <th>Total Amount</th>
{{--            <th>Dosage</th>--}}
        </tr>
        </thead>
        <tbody>
        @if($medicines)
            @forelse($medicines as $medicine)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    @if($is_supplier)
                    <td>{{ $medicine->fldsuppname }}</td>
                    @endif
                    <td>{{ $medicine->fldbillno }}</td>
                    <td>{{ $medicine->fldreference }}</td>
                    <td>{{ $medicine->fldvolunit }}</td>
                    <td>{{ $medicine->generic }}</td>
                    <td>{{ $medicine->fldbrand }}</td>
                    <td>{{ $medicine->fldstockno }}</td>
                    <td>{{ $medicine->qty }}</td>
                    <td>{{  \App\Utils\Helpers::numberFormat(($medicine->flsuppcost)) }}</td>
                    <td>{{  \App\Utils\Helpers::numberFormat(($medicine->fldsellprice)) }}</td>
                    <td>{{  \App\Utils\Helpers::numberFormat(($medicine->tot)) }}</td>
{{--                    @if($request['medType'] === "med")--}}
{{--                        <td>{{ $medicine->flddosageform }}</td>--}}
{{--                    @elseif($request['medType'] === "surg")--}}
{{--                        <td>{{ $medicine->fldsurgcateg }}</td>--}}
{{--                    @elseif($request['medType'] === "extra")--}}
{{--                        <td>{{ $medicine->flddepart }}</td>--}}
{{--                    @endif--}}
                </tr>
            @empty
            @endforelse
        @endif
        </tbody>
    </table>
</div>
<div class="ajax-pagination mt-2">{{ $medicines->appends($request)->links() }}</div>
