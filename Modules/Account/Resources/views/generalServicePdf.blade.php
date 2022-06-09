 RATES@extends('inpatient::pdf.layout.main')

@section('title')
GENERAL SERVICES RATES
@endsection

@section('report_type')
GENERAL SERVICES RATES
@endsection

@section('content')
    <style>
        .content-body {
            border-collapse: collapse;
        }
        .content-body td, .content-body th{
            border: 1px solid #ddd;
        }
        .content-body {
            font-size: 12px;
        }
    </style>
    <ul>
        <li>General Services Rates</li>
        <li>{{ \App\Utils\Helpers::dateEngToNepdash(date('Y-m-d'))->full_date }} {{ date('H:i:s') }}</li>
    </ul>
    <table class="table content-body">
        <thead>
            <tr>
                <th></th>
                <th>Particulars</th>
                <th>Fee</th>
                <th>Rate For</th>
                <th>BillMode</th>
                <th>Section</th>
            </tr>
        </thead>
        <tbody id="js-otheritem-item-tbody">
            @foreach($all_items as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->flditemname }}</td>
                <td>{{ $item->flditemcost }}</td>
                <td>{{ $item->fldtarget }}</td>
                <td>{{ $item->fldgroup }}</td>
                <td>{{ $item->fldreport }}</td>
            </tr>
            @endforeach
        </tbody>
</table>
@endsection
