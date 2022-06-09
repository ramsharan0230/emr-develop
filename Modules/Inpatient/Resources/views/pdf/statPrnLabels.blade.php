@extends('inpatient::pdf.layout.main')

@section('title')
IPD Medication Labels
@endsection

@section('report_type')
Medication Labels
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
            <th>StartDate</th>
            <th>Medicine</th>
            <th>Regimen</th>
            <th>Direction</th>
            <th>Advice</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $data->fldstarttime }}</td>
            <td>{{ $data->flditem }}</td>
            <td>1 {{ $data->fldvolunit }} Every 8 Hour Difference {{ $data->flddays }} Day</td>
            <td>{{ $data->fldfreq }}</td>
            <td>{{ isset($comment->fldipinfo) ? $comment->fldipinfo : '-' }}</td>
        </tr>
    </tbody>
</table>
@endsection
