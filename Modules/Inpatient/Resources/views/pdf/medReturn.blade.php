@extends('inpatient::pdf.layout.main')

@section('title')
IPD UNUSED MEDICATION
@endsection

@section('report_type')
UNUSED MEDICATION
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
<table class="table content-body">
    <thead>
        <tr>
            <th>SNo</th>
            <th>Particulars</th>
            <th>Regimen</th>
            <th>Status</th>
            <th>DispQTY</th>
            <th>AdminQTY</th>
            <th>UnusedQTY</th>
        </tr>
    </thead>
    <tbody>
        @foreach($medReturn as $key => $data)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $data->flditem }}</td>
                <td>{{ $data->fldroute }} {{ $data->flddose }} mg X {{ $data->fldfreq }} X {{ $data->flddays }}</td>
                <td>{{ $data->fldcurval }}</td>
                <td>{{ $data->fldqtydisp }}</td>
                <td>{{ $data->fldqtyadmin }}</td>
                <td>{{ $data->fldqtydisp - $data->fldqtyadmin }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
