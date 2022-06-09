@extends('inpatient::pdf.layout.main')

@section('report_type')
RADIOLOGY
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
            <th>ReportDate</th>
            <th>Examination</th>
            <th>Evaluation</th>
            <th>Observation</th>
            <th>Conditions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($all_tests as $encounterId => $tests)
            <tr>
                <td colspan="5" style="text-align: center;">{{ $encounterId }}</td>
            </tr>
            @foreach($tests as $data)
                <tr>
                    <td>{{ $data->fldtime_report }}</td>
                    <td>{{ $data->fldtestid }}</td>
                    <td>{{ $data->fldsampletype }}</td>
                    <td>-</td>
                    <td>{{ $data->fldcondition }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection
