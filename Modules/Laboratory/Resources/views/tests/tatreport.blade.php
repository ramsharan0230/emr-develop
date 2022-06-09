@extends('inpatient::pdf.layout.main')

@section('title')
TAT REPORT
@endsection

@section('content')
    <style>
        @page {
            margin: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
    <div class="header">
        <p>Date: {{ $from }} to {{ $to }}</p>
        <p>Printed Date: {{ \Carbon\Carbon::now()->format('Y-m-d') }}</p>
    </div>
    <table style="width: 100%;" class="content-body">
        <thead class="thead-light">
            <tr>
                <th class="tittle-th">SN</th>
                <th class="tittle-th">Sample</th>
                <th class="tittle-th">Name</th>
                <th class="tittle-th">Particular</th>
                <th class="tittle-th">Request Date</th>
                <th class="tittle-th">Sample Date</th>
                <th class="tittle-th">Report Date</th>
                <th class="tittle-th">Verify Date</th>
                <th class="tittle-th">Days</th>
                <th class="tittle-th">Hour</th>
                <th class="tittle-th">Minute</th>
            </tr>
        </thead>
        <tbody id="js-tat-report-tbody">
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
@endsection
