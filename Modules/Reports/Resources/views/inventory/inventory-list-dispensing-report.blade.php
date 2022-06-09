@extends('inpatient::pdf.layout.main')

@section('title')
    Inventory Report
@endsection

@section('content')

    <style>
    .content-body th,.content-body td{
        text-align: left;
        max-width: 150px;
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
                <th>SNo</th>
                <th>Supplier Bill Number</th>
                <th>Encounter</th>
                <th>Generic</th>
                <th>Brand</th>
                <th>Volume</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Disp Qty</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Total Amount</th>
                <th class="text-right">Time</th>
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
                        <td class="text-right">{{  \App\Utils\Helpers::numberFormat(($medicine->flditemrate)) }}</td>
                        <td class="text-right">{{ $medicine->qty }}</td>
                        <td class="text-right">{{  \App\Utils\Helpers::numberFormat(($medicine->fldtaxper)) }}</td>
                        <td class="text-right">{{  \App\Utils\Helpers::numberFormat(($medicine->tot)) }}</td>
                        <td class="text-right">{{ $medicine->fldtime }}</td>
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
@endsection
