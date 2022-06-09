@extends('inpatient::pdf.layout.main')

@section('title')
IPD Transitions
@endsection

@section('report_type')
BED TRANSITION
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
            <th>Department</th>
            <th>BedNo</th>
            <th>InDate</th>
            <th>InBY</th>
            <th>OutDate</th>
            <th>OutBY</th>
            <th>Duration</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transitions as $data)
            <tr>
                @php $department = Helpers::getDepartmentFromBED($data->flditem); @endphp
                <td>{{ $department }}</td>
                <td>{{ $data->flditem }}</td>
                <td>{{ $data->fldfirsttime }}</td>
                <td>{{ $data->fldfirstuserid }}</td>
                <td>{{ $data->fldsecondtime }}</td>
                <td>{{ $data->fldseconduserid }}</td>
                <td>{{ $data->duration }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
