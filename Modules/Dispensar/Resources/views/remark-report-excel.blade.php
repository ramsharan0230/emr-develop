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
        <tr><th></th></tr>
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
            <th colspan="2"><b>Encounter:</b></th>
            <th colspan="2">{{ $encounter_id }}</th>
        </tr>
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
            <th colspan="2"><b>Phone No:</b></th>
            <th colspan="2">{{ $phone }}</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>Remarks:</b></th>
            <th colspan="2">{{ $remark }}</th>
        </tr>
        <tr><th></th></tr>
        <tr>
            <th>SN</th>
            <th>Encounter ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Phone No.</th>
            <th>Date</th>
            <th>Remark</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($remarks) && $remarks)
            @foreach($remarks as $remark)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $remark->fldencounterval }}</td>
                    <td>{{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldfullname : '' }}</td>
                    <td>{{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldagestyle . ' years' : '' }}</td>
                    <td>{{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldptsex : '' }}</td>
                    <td>{{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldptcontact : '' }}</td>
                    <td>{{ explode(' ', $remark->fldtime)[0] }}</td>
                    <td>{!! $remark->fldremark !!}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
