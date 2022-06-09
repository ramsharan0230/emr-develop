@extends('inpatient::pdf.layout.main')

@section('title')
    Inventory Report
@endsection

@section('content')

    <style>
    .content-body th,.content-body td{
        text-align: left;
    }
    .text-right{
        text-align: right !important;
    }
    .header {
        margin-bottom: 20px;
    }
    .header tbody tr td:nth-child(2) {
        text-align: right;
    }
    </style>
    <table style="width: 100%;" class="header">
        <tbody>
        <tr>
            <td>
                <b>From Date:</b> %
            </td>
            <td>
                <b>Printed at:</b> %
            </td>
        </tr>
        <tr>
            <td>
                <b>To Date:</b> %
            </td>
            <td>
                <b>Printed By:</b> %
            </td>
        </tr>
        </tbody>
    </table>
    <div class="table-responsive res-table" style="max-height: none;">
    <table class="table content-body">
        <thead class="thead-light">
        <tr>
            <th style="width: 10px">SNo</th>
            @if($is_supplier)
                <th style="width: 10px">Supplier Name</th>
            @endif
            <th style="width: 10px">Supplier Bill No.</th>
            <th>Purchase Reference No.</th>
            <th>Volume</th>
            <th style="width: 50px">Generic</th>
            <th>Brand</th>
            <th class="text-right">Stock No.</th>
            <th class="text-right">Pur Qty</th>
            <th class="text-right">Sup Cost</th>
            <th class="text-right">Sell Cost</th>
            <th class="text-right">Total Amount</th>
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
                    <td class="text-right">{{ $medicine->fldstockno }}</td>
                    <td class="text-right">{{ $medicine->qty }}</td>
                    <td class="text-right">{{  \App\Utils\Helpers::numberFormat(($medicine->flsuppcost)) }}</td>
                    <td class="text-right">{{  \App\Utils\Helpers::numberFormat(($medicine->fldsellprice)) }}</td>
                    <td class="text-right">{{  \App\Utils\Helpers::numberFormat(($medicine->tot)) }}</td>
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
@endsection
