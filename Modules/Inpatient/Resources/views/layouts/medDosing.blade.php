@extends('inpatient::pdf.layout.main')

@section('title')
IPD Dosing Report
@endsection

@section('report_type')
Dosing Report
@endsection

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>Medicine</th>
            <th>Regimen</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
        @foreach($medDosing as $key => $data)
            <tr>
                <td>{{ $data->flditem }}</td>
                <td>{{ $data->fldroute }} X {{ $data->fldfreq }} X 1</td>
                <td>&nbsp;</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection