@extends('inpatient::pdf.layout.main')

@section('title')
IPD COUNSELING
@endsection

@section('report_type')
COUNSELING
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
            <th>Medicine</th>
            <th>Regimen</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $data->flditem }}</td>
            <td>{{ $data->fldroute }} {{ $data->flddose }} mg X {{ $data->fldfreq }} X {{ $data->flddays }}</td>
            <td>&nbsp;</td>
        </tr>
    </tbody>
</table>
@endsection
