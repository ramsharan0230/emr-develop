<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<4;$i++)
                <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<4;$i++)
                <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<4;$i++)
                <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<4;$i++)
                <th></th>
            @endfor
            <th colspan="8"><b>Contact No: {{ Options::get('system_telephone_no') ? Options::get('system_telephone_no'):'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
                <th></th>
            @endfor
            <th colspan="2"><b>From Date:</b></th>
            <th colspan="2">{{ $from_date }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
                <th></th>
            @endfor
            <th colspan="2"><b>To Date:</b></th>
            <th colspan="2">{{ $to_date }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
                <th></th>
            @endfor
            <th colspan="2"><b>Total CheckIn:</b></th>
            <th colspan="2">{{ $patients_counts }}</th>
        </tr>
        <tr>
            @for($i=1;$i<4;$i++)
                <th></th>
            @endfor
            <th colspan="8"><b>Patient Checked List Report</b></th>
        </tr>
        <tr><th></th></tr>
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
                    {{-- <td>{{ (isset($patient->fldfollowdate) ? 'Yes':'No') }}</td>
                    <td>{{ (isset($patient->fldfollowdate)) ? \Carbon\Carbon::parse($patient->fldfollowdate)->format('Y-m-d'):'' }}</td> --}}
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
            <td colspan="6">Total</td>
            <td colspan="2">{{ $grandtotal }}</td>
        </tr> --}}
    </tbody>
</table>
