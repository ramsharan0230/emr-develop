@extends('inpatient::pdf.layout.main')

@section('title')
Registration Report
@endsection

@section('content')
<h3 style="display: flex; justify-content:center;">Registration Report</h3>
<table class="table content-body">
    <tr>
        <th>Name:</th>
        <th>{{$name }}</th>
        <th><b>From date:</b></th>
        <th>{{ $from_date }}</th>
    </tr>
    <tr>
        <th><b>Department:</b></th>
        <th>{{ $department }}</th>
        <th><b>To date:</b></th>
        <th>{{ $to_date }}</th>
    </tr>
</table>
<br>
<table class="table content-body">
    <thead>
        <tr>
            <th>S.N.</th>
            <th>Patient ID</th>
            <th>Enc ID</th>
            <th>Name</th>
            <th style="text-align: left">Address</th>
            <th style="text-align: left;">Mobile</th>
            <th style="text-align: left;">Age/Sex</th>
            <th style="text-align: left">Specialization</th>
            <th style="text-align: left">Consultant</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($patients) && $patients)
            @foreach($patients as $patient)
            @php
                $getConsultant = Helpers::getConsultant($patient->fldencounterval);
            @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $patient->patientInfo->fldpatientval }}</td>
                    <td>{{ $patient->fldencounterval }}</td>
                    <td>{{ $patient->patientInfo->fullname }}</td>
                    <td style="text-align: left">{{ ($patient->patientInfo) ? implode(', ', (array_filter([$patient->patientInfo->fldptaddvill, $patient->patientInfo->fldptadddist]))) : '' }}</td>
                    <td style="text-align: left;">{{ $patient->patientInfo->fldptcontact }}</td>
                    <td style="text-align: left;">{{ $patient->patientInfo->fldagestyle }}/{{ $patient->patientInfo->fldptsex }}</td>
                    <td style="text-align: left">{{ $patient->fldcurrlocat }}</td>
                    <td style="text-align: left">{{ ($getConsultant) ? $getConsultant : '' }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
@endsection
