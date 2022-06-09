@extends('inpatient::pdf.layout.main')

@section('title')
IPD Diagnosis
@endsection

@section('report_type')
Clinical Demographics
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
            <th>SN</th>
            <th>Variable</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach($all_data as $key => $data)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $data->flditem }}</td>
                <td>{{ $data->fldreportquali }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
