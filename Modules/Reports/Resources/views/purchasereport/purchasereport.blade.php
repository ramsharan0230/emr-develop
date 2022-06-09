@extends('inpatient::pdf.layout.main')

@section('title')
    Purchase Report
@endsection
@include('pdf-header-footer.hospital-header')
<h4 style="text-align: center;margin-top:2px;">Purchase Report</h4>
@section('content')
{{--    <p align="center" style="margin-left: 210px;"><strong>Purchase Report</strong></p>--}}
    @php
        $totalamount = 0;
    @endphp
    <div style="width: 100%;">
        {{--        <div style="width: 50%;float: left;">--}}
        {{--            <p>Supplier/Department: {{ (isset($orders[0])) ? $orders[0]->fldsuppname : '' }}</p>--}}
        {{--        </div>--}}
        <div style="width: 50%;float: left;">
            <p>Datetime: {{ (isset($orders[0])) ? $orders[0]->fldorddate : '' }}</p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
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
        {{--        <tr>--}}
        {{--            <td>&nbsp;</td>--}}
        {{--            <td>&nbsp;</td>--}}
        {{--            <td>&nbsp;</td>--}}
        {{--            <td>&nbsp;</td>--}}
        {{--            <td>&nbsp;</td>--}}
        {{--            <td>&nbsp;</td>--}}
        {{--            <td>{{ $totalamount }}</td>--}}
        {{--            <td>&nbsp;</td>--}}
        {{--        </tr>--}}
        </tbody>
    </table>
    {{--    <div style="width: 100%;">--}}
    {{--        <div style="width: 50%;float: left;">--}}
    {{--            <p>IN WORDS: {{ \App\Utils\Helpers::numberToNepaliWords($totalamount) }}</p>--}}
    {{--        </div>--}}
    {{--        <div style="width: 50%;float: left;">--}}
    {{--            --}}{{--            <p>TOTATAMT: {{ $totalamount }}</p>--}}
    {{--        </div>--}}
    {{--    </div>--}}
@endsection
