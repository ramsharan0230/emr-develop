@extends('inpatient::pdf.layout.main')

@section('title')
IPD Process
@endsection

@section('report_type')
IP Monitoring
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
            <th>On Examination</th>
            <th>Treatment</th>
            <th>I/O Assessment</th>
            <th>Impression</th>
            <th>Nurse Plan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($progress as $key => $dates)
            <tr>
                <td colspan="7" style="text-align: center;"><strong>{{ $key }}</strong></td>
            </tr>
            @foreach($dates as $data)
            <tr>
                <td>{{ $data->time }}</td>
                <td>{!! $data->fldsubjective !!}</td>
                <td>
                    @foreach($data->exams as $exam)
                    {{ $exam->fldhead }}: {{ $exam->fldrepquali }} <br>
                    @endforeach
                </td>
                <td>{!! $data->fldobjective   !!}</td>
                <td>{!! $data->fldassess !!}</td>
                <td>{!! $data->fldproblem !!}</td>
                <td>{!! $data->fldplan !!}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endsection
