@extends('inpatient::pdf.layout.main')

@section('title')
Delivery Report - {{ $flddept }}
@endsection

@section('report_type')
{{ $flddept }}
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
            <th class="tittle-th"></th>
            <th class="tittle-th">Examination</th>
            <th class="tittle-th"></th>
            <th class="tittle-th">Observation</th>
            <th class="tittle-th">Report Time</th>
        </tr>
    </thead>
    <tbody>
        @foreach($all_data as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data->fldhead}}</td>
                <td>{!! ($data->fldabnormal == '0') ? '<div style="background-color: green;width: 15px;height: 15px;"></div>' : '<div style="background-color: red;width: 15px;height: 15px;"></div>' !!}</td>
                <td>{{ $data->fldrepquali}}</td>
                <td>{{ $data->fldtime}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
