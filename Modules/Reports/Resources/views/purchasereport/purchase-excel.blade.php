
@php
    $totalamount = 0;
@endphp
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
        <th colspan="8"><b>Purchase Report</b></th>
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
        <th colspan="2">{{ (isset($orders[0])) ? $orders[0]->fldorddate : '' }}</th>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th>SN</th>
        <td>Datetime</td>
        <td>Supplier/Department</td>
        <td>Particular</td>
        <td>PO Ref No</td>
        <td>Quantity</td>
        <td>Rate</td>
        <td>Amount</td>
        <td>User</td>
    </tr>
    </thead>
    <tbody>
    @if($orders)
        @foreach($orders as $order)
            @php
                $totalamount += $order->fldamt;
            @endphp
            <tr data-fldid="{{ $order->fldid }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->fldorddate }}</td>
                <td>{{ $order->fldsuppname }}</td>
                <td>{{ $order->flditemname }}</td>
                <td>{{ $order->fldreference }}</td>
                <td>{{ $order->fldqty }}</td>
                <td>{{ $order->fldrate }}</td>
                <td>{{ $order->fldamt }}</td>
                <td>{{ $order->flduserid }}</td>
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
