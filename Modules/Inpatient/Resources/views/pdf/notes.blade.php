@extends('inpatient::pdf.layout.main')

@section('title')
IPD symptoms
@endsection

@section('report_type')
CLINICAL SYMPTOMS
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
            <th>Time</th>
            <th>Category</th>
            <th>Note</th>
            <th>Impression</th>
        </tr>
    </thead>
    <tbody>
        @foreach($notes as $key => $dates)
            <tr>
                <td colspan="4" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
            <tr>
                <td>{{ $data->time }}</td>
                <td>{{ $data->flditem }}</td>
                <td>{!! $data->flddetail !!}</td>
                <td>{{ $data->fldreportquali }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection
