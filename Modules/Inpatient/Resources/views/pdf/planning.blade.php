@extends('inpatient::pdf.layout.main')

@section('title')
IPD Planning
@endsection

@section('report_type')
Clinician Plan
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
            <th>Problem</th>
            <th>Subjective</th>
            <th>Objective</th>
            <th>Assessment</th>
            <th>Clinican Plan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($planning as $key => $dates)
            <tr>
                <td colspan="6" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
            <tr>
                <td>{{ $data->time }}</td>
                <td>{!! $data->fldproblem !!}</td>
                <td>{!! $data->fldsubjective !!}</td>
                <td>{!! $data->fldobjective   !!}</td>
                <td>{!! $data->fldassess !!}</td>
                <td>{!! $data->fldplan !!}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection
