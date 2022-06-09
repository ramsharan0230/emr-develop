@extends('inpatient::pdf.layout.main')

@section('title')
IPD Radiology
@endsection

@section('report_type')
Radiology
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
            <th>Date</th>
            <th>Examination</th>
            <th>Evaluation</th>
            <th>Observation</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
        @foreach($radiologies as $data)
            <tr>
                <td>{{ $data->fldtime_report }}</td>
                <td>{{ $data->fldtestid }}</td>
                <td>{{ $data->fldtest_type }}</td>
                <td>{{ $data->fldmethod }}</td>
                <td>&nbsp;</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
