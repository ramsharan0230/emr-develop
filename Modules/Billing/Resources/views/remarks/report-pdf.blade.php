<style>
    table, th, td {
        border: 1px solid black;
        padding: 5px;
    }
    table {
        width: 100%;
    }
</style>
<table>
    <thead class="thead-light">
    <tr>
        <th class="tittle-th">SN</th>
        <th class="tittle-th">Encounter ID</th>
        <th class="tittle-th" width="250px">Patient Detail</th>
        <th class="tittle-th">Phone No.</th>
        <th class="tittle-th">Date</th>
        <th class="tittle-th">Remark</th>
    </tr>
    </thead>
    <tbody id="js-sampling-labtest-tbody">
    @if(isset($remarks) && $remarks)
        @foreach($remarks as $remark)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $remark->fldencounterval }}</td>
                <td>
                    {{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldfullname : '' }} <br>
                    {{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldagestyle . ' years' : '' }}/{{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldptsex : '' }}
                </td>
                <td>
                    {{ ($remark->encounter && $remark->encounter->patientInfo) ? $remark->encounter->patientInfo->fldptcontact : '' }}
                </td>
                <td>{{ explode(' ', $remark->fldtime)[0] }}</td>
                <td>{!! $remark->remarks !!}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
