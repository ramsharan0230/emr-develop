
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
        <th colspan="8"><b>Stock Consume</b></th>
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
        <th colspan="2">{{ (isset($references[0])) ? $references[0]->fldreference : '' }}</th>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th>SN</th>
        <td>ID</td>
        <td>Date</td>
        <td>Target</td>
        <td>Category</td>
        <td>Particulars</td>
        <td>Batch</td>
        <td>Expiry</td>
        <td>Qty</td>
    </tr>
    </thead>
    <tbody>
    @if($references)
        @foreach($references as $reference)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $reference->fldid }}</td>
                <td>{{ $reference->fldbulktime }}</td>
                <td>{{ $reference->fldtarget }}</td>
                <td>{{ $reference->fldcategory }}</td>
                <td>{{ $reference->fldstockid }}</td>
                <td>{{ (($reference->stock->fldbatch) ? $reference->stock->fldbatch : '' ) }}</td>
                <td>{{ (($reference->stock->fldexpiry) ? $reference->stock->fldexpiry : '' ) }}</td>
                <td>{{ $reference->fldqtydisp }}</td>
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
