<div class="table-responsive res-table" style="max-height: none;">
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th>SNo</th>
            <th>Supplier Bill No.</th>
            <th>Encounter</th>
            <th>Generic</th>
            <th>Brand</th>
            <th>Volume</th>
            <th>Rate</th>
            <th>Disp Qty</th>
            <th>Tax</th>
            <th>Total Amount</th>
            <th>Time</th>

        </tr>
        </thead>
        <tbody>
        @if($medicines)
            @forelse($medicines as $medicine)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $medicine->fldbillno }}</td>
                    <td>{{ $medicine->fldencounterval }}</td>
                    <td>{{ $medicine->generic }}</td>
                    <td>{{ $medicine->fldbrand }}</td>
                    <td>{{ $medicine->fldvolunit }}</td>
                    <td>{{  \App\Utils\Helpers::numberFormat(($medicine->flditemrate)) }}</td>
                    <td>{{ $medicine->qty }}</td>
                    <td>{{  \App\Utils\Helpers::numberFormat(($medicine->fldtaxper)) }}</td>
                    <td>{{  \App\Utils\Helpers::numberFormat(($medicine->tot)) }}</td>
                    <td>{{ $medicine->fldtime }}</td>
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
