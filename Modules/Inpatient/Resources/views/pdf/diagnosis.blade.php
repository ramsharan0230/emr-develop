@extends('inpatient::pdf.layout.main')

@section('title')
IPD Diagnosis
@endsection

@section('report_type')
PROVISIONAL DIAGNOSIS
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
            <th>Basis</th>
            <th>Disease</th>
            <th>Probablity</th>
            <th>Components</th>
        </tr>
    </thead>
    <tbody>
        @foreach($diagnosis_data as $key => $dates)
            <tr>
                <td colspan="4" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
            <tr>
                <td>{{ $data->fldchild }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection
