@extends('inpatient::pdf.layout.main')

@section('title')
Patient List Report
@endsection
@include('pdf-header-footer.hospital-header')
<h4 style="text-align: center;margin-top:2px;">Patient Checked List Report</h4>
@section('content')
{{-- @php $grandtotal = 0; @endphp --}}
    <ul>
{{--        <li>Medicine Expiry Report</li>--}}
    </ul>
    <div class="header">
        <p>Date: {{ $from_date }} to {{ $to_date }}</p>
        <p>Total CheckIn: {{ $patients_counts }}</p>
    </div>
    <table class="table content-body">
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Patient ID/Enc ID</th>
                <th>Patient Detail</th>
                {{-- <th>Check In</th>
                <th>Follow Date</th> --}}
                <th>Specialization</th>
                <th>Consultant</th>
                <th>Follow Date</th>
                <th>Follow Up Department</th>
                <th>Follow Up Consultant</th>
            </tr>
        </thead>
        <tbody>
            @if ($patients)
                @foreach ($patients as $key=>$patient)
                {{-- @php
                    $total = $medicine->fldqty*$medicine->fldsellpr;
                    $grandtotal += $total;
                @endphp --}}
                    @php
                    $consultant = Helpers::consultant($patient->fldencounterval);
                    $getLastestFollowup = Helpers::getLastestFollowup($patient->fldencounterval);
                    $getConsultantFollowup = Helpers::getConsultantFollowup($patient->fldencounterval);
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td> {{ ($patient->patientInfo) ? $patient->patientInfo->fldpatientval : '' }}/{{ $patient->fldencounterval }}</td>
                        <td>
                            {{ ($patient->patientInfo) ? $patient->patientInfo->fldfullname : '' }}<br>
                            {{ (isset($patient->patientInfo->fldagestyle) and is_string($patient->patientInfo->fldagestyle)) ? $patient->patientInfo->fldagestyle : '' }}/{{ ($patient->patientInfo) ? $patient->patientInfo->fldptsex : '' }} {{ ($patient->patientInfo) ? $patient->patientInfo->fldptcontact : '' }}<br>
                            {{ ($patient->patientInfo) ? implode(', ', (array_filter([$patient->patientInfo->fldptaddvill, $patient->patientInfo->fldptadddist]))) : '' }}
                        </td>
                        {{-- <td>{{ (isset($patient->fldfollowdate) ? 'Yes':'No') }}</td> --}}
                        {{-- <td>{{ (isset($patient->fldfollowdate)) ? \Carbon\Carbon::parse($patient->fldfollowdate)->format('Y-m-d'):'' }}</td> --}}
                        <td>{{ ($patient->allConsultant) ? implode(', ', array_filter($patient->allConsultant->pluck('fldconsultname')->toArray())) : '' }}</td>
                        <td>{{ ($consultant) ? $consultant : '' }}</td>
                        <td>{{ (isset($patient->fldfollowdate)) ? \Carbon\Carbon::parse($patient->fldfollowdate)->format('Y-m-d'):'' }}</td>
                        {{-- <td>{{ (isset($patient->fldfollowdate) ? 'Yes':'No') }}</td> --}}
                        <td>{{ ($getLastestFollowup['fldconsultname']) ? $getLastestFollowup['fldconsultname'] : ''}}</td>
                        <td>{{ ($getConsultantFollowup) ? $getConsultantFollowup : ''  }}</td>
                    </tr>
                @endforeach
            @endif
            {{-- <tr>
                <td colspan="7">Total</td>
                <td colspan="2">{{ $grandtotal }}</td>
            </tr> --}}
        </tbody>
    </table>
@endsection