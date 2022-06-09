@extends('inpatient::pdf.layout.main')

@section('title')
IPD symptoms
@endsection

@section('report_type')
CLINICAL SYMPTOMS
@endsection

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>SN</th>
            <th>Examination</th>
            <th>&nbsp;</th>
            <th>Observation</th>
            <th>ReportTime</th>
        </tr>
    </thead>
    <tbody>
        @foreach($exam_data as $key => $data)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $data->fldhead }}</td>
                <td>{!! ($data->fldabnormal == '0') ? '<div style="background-color: green;width: 15px;height: 15px;"></div>' : '<div style="background-color: red;width: 15px;height: 15px;"></div>' !!}</td>
                <td>{{ $data->fldrepquali }}</td>
                <td>{{ $data->fldtime }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection