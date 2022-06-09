@extends('inpatient::pdf.layout.main')

@section('title')
   Demand vs Order Vs Purchase Report
@endsection
@include('pdf-header-footer.hospital-header')
<h4 style="text-align: center;margin-top:2px;">Demand VS Order VS Purchase</h4>
@section('content')
    <p align="center" style="margin-left: 210px;"><strong> Demand vs Order Vs Purchase Report</strong></p>

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
            <td>Supplier</td>
            <td>Particular</td>
            <td>Demand Qty</td>
            <td>Order Qty</td>
            <td>Purchase Qty</td>
            <td>Route</td>
            <td>Ref No</td>
            <td>user</td>
        </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            <tr data-fldid="{{ $order->fldid }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->fldorddate }}</td>
                <td>{{ $order->fldsuppname }}</td>
                <td>{{ $order->flditemname }}</td>
                <td>{{ (($order->demand) ? $order->demand->fldquantity : '') }}</td>
                <td>{{ $order->fldqty }}</td>
                <td>{{ (($order->purchase) ? $order->purchase->fldsubtotal :'') }}</td>
                <td>{{ $order->fldroute }}</td>
                <td>{{ $order->fldreference }}</td>
                <td>{{ $order->flduserid }}</td>
            </tr>
        @empty

        @endforelse
        </tbody>
    </table>
@endsection
