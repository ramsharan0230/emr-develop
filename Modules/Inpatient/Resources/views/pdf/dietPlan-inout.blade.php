@extends('inpatient::pdf.layout.main')

@section('title')
IPD Diet Plan
@endsection

@section('report_type')
Diet Plan
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
            <th>Type</th>
            <th>Particulars</th>
            <th>Dose</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($plannedDiets as $key => $data)
            <tr>
                <td>{{ $data->type }}</td>
                <td>{{ $data->particulars }}</td>
                <td>{{ $data->dose }}</td>
                <td>{{ $data->time }}</td>
                <td>{{ $data->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
