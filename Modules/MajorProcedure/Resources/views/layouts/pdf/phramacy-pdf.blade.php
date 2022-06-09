<!DOCTYPE html>
<html>
<head>
    <title>Phramacy Sheet</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
@include('pdf-header-footer.header-footer')
<main>
@php
    $patientInfo = $encounterData->patientInfo;
@endphp

<table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 200px;">
            <p><strong>Name:</strong> {{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{ $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }} ({{$patientInfo->fldpatientval}})</p>
            <p><strong>Age/Sex:</strong> {{ $patientInfo->fldagestyle }} /{{ $patientInfo->fldptsex??"" }}</p>
            {{-- <p><strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }}yrs/{{ $patientInfo->fldptsex??"" }}</p> --}}
            <p><strong>Address:</strong> {{ $patientInfo->fldptaddvill??"" . ', ' . $patientInfo->fldptadddist??"" }}</p>
            <p><strong>REPORT LABORATORY:</strong> OUTPATIENT REPORT</p>
        </td>
        <td style="width: 185px;">
            <p><strong>EncID:</strong> {{ $encounterId }}</p>
            <p><strong>DOReg:</strong> {{ $encounterData->fldregdate ? \Carbon\Carbon::parse($encounterData->fldregdate)->format('d/m/Y'):'' }}</p>
            <p><strong>Phone: </strong></p>
        </td>
        <td style="width: 130px;">{!! Helpers::generateQrCode($encounterId)!!}</td>
    </tr>
    </tbody>
</table>

    <table style="width: 100%;" border="1px" rules="all" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th" scope="col"></th>
            <th class="tittle-th" scope="col">StartDate</th>
            <th class="tittle-th" scope="col">Routine</th>
            <th class="tittle-th" scope="col">Medicine</th>
            <th class="tittle-th" scope="col">Dose</th>
            <th class="tittle-th" scope="col">Freq</th>
            <th class="tittle-th" scope="col">Days</th>
            <th class="tittle-th" scope="col">Status</th>
        </tr>
        </thead>
        <tbody class="show-all-phramacy">
            @if(count($patdosing))
            @foreach($patdosing as $dosing)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $dosing->fldstarttime }}</td>
                <td>{{ $dosing->fldroute }}</td>
                <td>{{ $dosing->flditem }}</td>
                <td>{{ $dosing->flddose }}</td>
                <td>{{ $dosing->fldfreq }}</td>
                <td>{{ $dosing->flddays }}</td>
                <td>{{ $dosing->fldcurval }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('majorprocedure');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
