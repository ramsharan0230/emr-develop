@extends('inpatient::pdf.layout.main')

@section('title')
IPD MEDICATION REVIEW
@endsection

@section('report_type')
MEDICATION REVIEW
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
            <th>Observation</th>
            <th>Recommendation</th>
        </tr>
    </thead>
    <tbody>
        @foreach($all_data as $data)
        <tr>
            <td>{{ $data->flditem }}</td>
            <td>{{ $data->fldobservation }}</td>
            <td>{{ $data->fldrecommendation }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
