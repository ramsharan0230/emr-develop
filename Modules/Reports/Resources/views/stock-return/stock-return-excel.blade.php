
<table>
    <thead>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>Contact No: {{ Options::get('system_telephone_no') ? Options::get('system_telephone_no'):'' }}</b></th>
    </tr>

    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>Stock Return Report</b></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>From date:</b></th>
        <th colspan="2">{{ $from_date }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>To date:</b></th>
        <th colspan="2">{{ $to_date }}</th>
    </tr>
{{--    <tr>--}}
{{--        @for($i=1;$i<6;$i++)--}}
{{--            <th></th>--}}
{{--        @endfor--}}
{{--        <th colspan="2"><b>Supplier/Department:</b></th>--}}
{{--        <th colspan="2">{{ (isset($orders[0])) ? $orders[0]->fldsuppname : '' }}</th>--}}
{{--    </tr>--}}
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Datetime:</b></th>
        <th colspan="2">{{ \Carbon\Carbon::now() }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Reference No: </b></th>
        <th colspan="2">{{ (isset($references[0])) ? $references[0]->fldnewreference : '' }}</th>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th>SN</th>
        <td>Location</td>
        <td>Reference</td>
        <td>Supplier</td>
        <td>Particulars</td>
        <td>Batch</td>
        <td>Expiry</td>
        <td>Qty</td>
        <td>Cost</td>
        <td>Amount</td>
    </tr>
    </thead>
    <tbody>
    @if($references)
        @foreach($references as $reference)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $reference->fldcomp }}</td>
                <td>{{ $reference->fldreference }}</td>
                <td>{{ $reference->fldsuppname }}</td>
                <td>{{ $reference->fldstockid }}</td>
                <td>{{ (($reference->entry->fldbatch) ? $reference->entry->fldbatch : '' ) }}</td>
                <td>{{ (($reference->entry->fldexpiry) ? $reference->entry->fldexpiry : '' ) }}</td>
                <td>{{ $reference->fldqty }}</td>
                <td>{{ ($reference->fldcost) ? 'Rs.'.$reference->fldcost :'Rs.0' }}</td>
                @php
                    $amount = ($reference->fldqty) * ($reference->fldcost);
                @endphp
                <td>{{ $amount ? 'Rs.'.$amount : 'Rs.0' }}</td>
            </tr>
        @endforeach
    @endif
{{--    <tr>--}}
{{--        <td>&nbsp;</td>--}}
{{--        <td>&nbsp;</td>--}}
{{--        <td>&nbsp;</td>--}}
{{--        <td>&nbsp;</td>--}}
{{--        <td>&nbsp;</td>--}}
{{--        <td>&nbsp;</td>--}}
{{--        <td>{{ $totalamount }}</td>--}}
{{--        <td>&nbsp;</td>--}}
{{--    </tr>--}}
    </tbody>
</table>

{{--<div style="width: 100%;">--}}
{{--    <div style="width: 50%;float: left;">--}}
{{--        <p>IN WORDS: {{ \App\Utils\Helpers::numberToNepaliWords($totalamount) }}</p>--}}
{{--    </div>--}}
{{--    <div style="width: 50%;float: left;">--}}
{{--        --}}{{--            <p>TOTATAMT: {{ $totalamount }}</p>--}}
{{--    </div>--}}
{{--</div>--}}
