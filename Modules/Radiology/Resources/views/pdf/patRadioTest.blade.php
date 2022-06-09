@extends('inpatient::pdf.layout.main')

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
            <th>&nbsp;</th>
            <th>Examination</th>
            <th>Observation</th>
            <th>Visibility</th>
            <th>Method</th>
            <th>Evaluation</th>
            <th>ReportDate</th>
            <th>Comment</th>
            <th>Conditions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tests as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data->fldtestid }}</td>
                <td>-</td>
                <td>{{ $data->flvisible }}</td>
                <td>{{ $data->fldmethod }}</td>
                <td>{{ $data->fldsampletype }}</td>
                <td>{{ $data->fldtime_report }}</td>
                <td>{{ $data->fldcomment }}</td>
                <td>{{ $data->fldcondition }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
