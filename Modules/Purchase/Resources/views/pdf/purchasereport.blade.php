@extends('inpatient::pdf.layout.main')

@section('title')
Purchase order
@endsection

@section('content')
	@php
	    $totalamount = 0;
    @endphp
    <table style="width: 100%">
        <tr>
            <td style="width: 50%; text-align: left;">Supplier/Department: {{ (isset($orders[0])) ? $orders[0]->fldsuppname : '' }}</td>
            <td style="width: 50%; text-align: right;">Datetime: {{ (isset($orders[0])) ? $orders[0]->fldorddate : '' }}</td>
        </tr>
        <tr>
            <td style="width: 50%; text-align: left;">BillNo: {{ (isset($orders[0])) ? $orders[0]->fldreference : '' }}</td>
        </tr>
    </table>
    <table class="table content-body">
        <thead>
            <tr>
                <th>SN</th>
            	<td>Datetime</td>
            	<td>Supplier/Department</td>
            	<td>Particular</td>
            	<td>Quantity</td>
            	<td>Rate</td>
            	<td>Amount</td>
            	<td>User</td>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            @php
            $totalamount += $order->fldamt;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->fldorddate }}</td>
                <td>{{ $order->fldsuppname }}</td>
                <td>{{ $order->flditemname }}</td>
                <td>{{ $order->fldqty }}</td>
                <td>{{ $order->fldrate }}</td>
                <td>{{ $order->fldamt }}</td>
                <td>{{ $order->flduserid }}</td>
            </tr>
            @endforeach
            <tr>
            	<td>&nbsp;</td>
            	<td>&nbsp;</td>
            	<td>&nbsp;</td>
            	<td>&nbsp;</td>
            	<td>&nbsp;</td>
            	<td>&nbsp;</td>
            	<td>{{ $totalamount }}</td>
            	<td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%;text-align: left;">IN WORDS: {{ \App\Utils\Helpers::numberToNepaliWords($totalamount) }}</td>
            <td style="width: 50%;text-align: right;">TOTAL AMT: {{ $totalamount }}</td>
        </tr>
    </table>
@endsection
