<div class="table-responsive res-table" style="max-height: none;">
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th>SNo</th>
            <th>Brand</th>
            <th>Generic</th>
            <th>Volume</th>
            <th>Net Cost</th>
            <th>Transfer Qty</th>
            <th>Total Amount</th>
            <th>Time</th>
            <th>Purchase Reference No.</th>
        </tr>
        </thead>
        <tbody>
        @if($medicines)
            @forelse($medicines as $key => $medicine)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $medicine->fldbrand }}</td>
                    <td>{{ $medicine->generic }}</td>
                    <td>{{ $medicine->fldvolunit }}</td>
                    <td>{{  \App\Utils\Helpers::numberFormat(($medicine->fldnetcost)) }}</td>
                    <td>{{ $medicine->qty }}</td>
                    <td>{{  \App\Utils\Helpers::numberFormat(($medicine->tot)) }}</td>
                    <td>{{ $medicine->fldtoentrytime }}</td>
                    <td>{{ $medicine->fldreference }}</td>
                    {{--@if($request['medType'] === "med")
                        <td>{{ $medicine->flddosageform }}</td>
                    @elseif($request['medType'] === "surg")
                        <td>{{ $medicine->fldsurgcateg }}</td>
                    @elseif($request['medType'] === "extra")
                        <td>{{ $medicine->flddepart }}</td>
                    @endif--}}
                </tr>
            @empty
            @endforelse
        @endif
        </tbody>
    </table>
</div>
<div class="ajax-pagination mt-2">{{ $medicines->appends($request)->links() }}</div>

