<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<4;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>Registration Report</b></th>
        </tr>
        <tr>
            @for($i=1;$i<4;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
        </tr>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>Name:</b></th>
            <th colspan="2">{{ $name }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>From date:</b></th>
            <th colspan="2">{{ $from_date }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>To date:</b></th>
            <th colspan="2">{{ $to_date }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>Department:</b></th>
            <th colspan="2">{{ $department }}</th>
        </tr>
        <tr><th></th></tr>
        <tr>
            <th>S.N.</th>
            <th>Patient ID</th>
            <th>Enc ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Mobile</th>
            <th>Age/Sex</th>
            <th>Specialization</th>
            <th>Consultant</th>
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
                    <td>{{ ($patient->patientInfo) ? implode(', ', (array_filter([$patient->patientInfo->fldptaddvill, $patient->patientInfo->fldptadddist]))) : '' }}</td>
                    <td>{{ $patient->patientInfo->fldptcontact }}</td>
                    <td>{{ $patient->patientInfo->fldagestyle }}/{{ $patient->patientInfo->fldptsex }}</td>
                    <td>{{ $patient->fldcurrlocat }}</td>
                    <td>{{ ($getConsultant) ? $getConsultant : '' }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
