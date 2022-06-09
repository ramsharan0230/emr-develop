<style>
    .res-table td ul{
        list-style: none;

    }

</style>
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
        <th colspan="2"><b>From Date:</b></th>
        <th colspan="2">{{ $from }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>To Date:</b></th>
        <th colspan="2">{{ $to }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Printed Date:</b></th>
        <th colspan="2">{{ \Carbon\Carbon::now()->format('Y-m-d') }}</th>
    </tr>
    <tr><th></th></tr>
    <tr>
        <th>SN.</th>
        <th>Sample</th>
        <th>Name</th>
        <th>Particular</th>
        <th>Request Date</th>
        <th>Sample Date</th>
        <th>Report Date</th>
        <th>Verify Date</th>
        <th>Days</th>
        <th>Hour</th>
        <th>Minute</th>
    </tr>
    </thead>
    <tbody>
        @foreach($tests as $test)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $test->fldsampleid }}</td>
                <td>{{ (isset($test->fldencounterval)) ? $test->fldencounterval : '' }}<br>
                    {{ (isset($test->patientEncounter->patientInfo)) ? $test->patientEncounter->patientInfo->fldfullname : '' }}<br>
                    {{ (isset($test->patientEncounter->patientInfo->fldagestyle) and is_string($test->patientEncounter->patientInfo->fldagestyle)) ? $test->patientEncounter->patientInfo->fldagestyle : '' }}/{{ (isset($test->patientEncounter->patientInfo)) ? $test->patientEncounter->patientInfo->fldptsex : '' }} {{ (isset($test->patientEncounter->patientInfo)) ? $test->patientEncounter->patientInfo->fldptcontact : '' }}<br>
                    {{ (isset($test->patientEncounter->patientInfo)) ? implode(', ', (array_filter([$test->patientEncounter->patientInfo->fldptaddvill, $test->patientEncounter->patientInfo->fldptadddist]))) : '' }}
                </td>
                <td>{{ $test->fldtestid }}</td>
                <td>{{ isset($test->patbill) ? $test->patbill->fldtime : '' }}</td>
                <td>{{ $test->fldtime_sample }}</td>
                <td>{{ $test->fldtime_report }}</td>
                <td>{{ $test->fldtime_verify }}</td>
                <td>{{ $test->day }}</td>
                <td>{{ $test->hour }}</td>
                <td>{{ $test->minute }}</td>
            </tr>
        @endforeach
    </tbody>
    </table>
