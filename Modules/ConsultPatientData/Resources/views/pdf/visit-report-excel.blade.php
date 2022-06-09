<table>
    <thead>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th colspan="8">
                <b>{{ $certificate }}</b>
            </th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><b>From date:</b></th>
            <th>{{ \Carbon\Carbon::parse($from_date)->format('Y-m-d') }}</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><b>To date:</b></th>
            <th>{{ \Carbon\Carbon::parse($to_date)->format('Y-m-d') }}</th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th>Index</th>
            <th>EncID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>DORec</th>
            <th>DOAdmission</th>
            <th>DODischarge</th>
            <th>Stay Days</th>
            <th>Status</th>
            <th>Department</th>
            <th>Consultation</th>
            <th>Registration No.</th>
        </tr>
    </thead>
    <tbody>
        @php
            $pdf = '';
            $countpdf = 0;
        @endphp
        @foreach ($resultArray as $patient)
            @php
                $getconsultDep = Helpers::getEncounterConsultantVisitDepartment($patient->fldencounterval);
                $getconsultDoc = Helpers::getEncounterConsultantVisitDocName($patient->fldencounterval);
                $getconsultReg = Helpers::getEncounterConsultantVisitReg($patient->fldencounterval);

                if ($last_status == 'Admitted' || $patient->fldadmission == 'Admitted') {
                    $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddoa)));
                    $now = \Carbon\Carbon::now();
                    $noofdays = $datework->diffInDays($now);
                }
                if ($last_status == 'Registered' || $patient->fldadmission == 'Registered') {
                    $noofdays = 0;
                }
                if ($last_status == 'Discharged' || $patient->fldadmission == 'Discharged') {
                    $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddoa)) . '00:00:00');
                    $now = $patient->flddod;
                    $noofdays = $datework->diffInDays($now);
                }
                if ($last_status == 'Absconder' || $patient->fldadmission == 'Absconder') {
                    $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddod)) . '00:00:00');
                    $now = $patient->flddod;
                    $noofdays = $datework->diffInDays($now);
                }
                if ($last_status == 'LAMA' || $patient->fldadmission == 'LAMA') {
                    $datework = \Carbon\Carbon::createFromDate(date('Y-m-d', strtotime($patient->flddod)) . '00:00:00');
                    $now = $patient->flddod;
                    $noofdays = $datework->diffInDays($now);
                }
            @endphp

            <tr>
                <td>{{ ++$countpdf }}</td>
                <td>{{ $patient->fldencounterval }}</td>
                @php $user_rank = ((Options::get('system_patient_rank') == 1) && isset($patient) && isset($patient->fldrank)) ? $patient->fldrank : ''; @endphp
                @if ($patient->patientInfo)
                    <td>{{ $user_rank . ' ' . $patient->patientInfo->fldptnamefir . ' ' . $patient->patientInfo->fldmidname . ' ' . $patient->patientInfo->fldptnamelast }}</td>
                    <td>{{ $patient->patientInfo->fldagestyle }}</td>
                    <td>{{ $patient->patientInfo->fldptsex }}</td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
                @php $noofdays = $noofdays != 0 ? $noofdays + 1 : 0; @endphp
                <td>{{ $patient->fldregdate ? $patient->fldregdate : '' }}</td>
                <td>{{ $patient->flddoa ? $patient->flddoa : '' }}</td>
                <td>{{ $patient->flddod ? $patient->flddod : '' }}</td>
                <td>{{ $noofdays }}</td>
                <td>{{ $patient->fldadmission }}</td>
                <td>{!! $getconsultDep !!}</td>
                <td>{!! $getconsultDoc ? $getconsultDoc : '' !!}</td>
                <td>{!! $getconsultReg ? $getconsultReg : '' !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
