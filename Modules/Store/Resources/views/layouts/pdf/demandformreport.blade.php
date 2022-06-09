@extends('inpatient::pdf.layout.main')

@section('title')
Demand order
@endsection

@section('content')
	@php
	    $totalamount = 0;
    @endphp
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p>Supplier/Department: {{ (isset($orders[0])) ? $orders[0]->fldsuppname : '' }}</p>
        </div>
        <div style="width: 50%;float: left;">
            <p>Datetime: {{ (isset($orders[0])) ? $orders[0]->fldtime_order : '' }}</p>
        </div>
        <div style="width: 50%;float: left;">
            <p>Quotation No.: {{ (isset($quotationno)) ? $quotationno : '' }}</p>
        </div>
    </div>
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
            $totalamount += $order->fldtotal;
            @endphp
            <tr data-fldid="{{ $order->fldid }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->fldordersavedtime }}</td>
                <td>{{ $order->fldsuppname }}</td>
                <td>{{ $order->fldstockid }}</td>
                <td>{{ $order->fldquantity }}</td>
                <td>{{ $order->fldrate }}</td>
                <td>{{ $order->fldtotal }}</td>
                <td>{{ $order->flduserid_order }}</td>
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
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p>IN WORDS: {{ \App\Utils\Helpers::numberToNepaliWords($totalamount) }}</p>
        </div>
        <div style="width: 50%;float: left;">
            <p>TOTAL AMT: {{ $totalamount }}</p>
        </div>
    </div>
@endsection
