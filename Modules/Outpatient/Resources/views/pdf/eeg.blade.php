<!DOCTYPE html>
<html>

<head>
    <title>History Sheet</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body td, .content-body th {
            border: 1px solid #ddd;
        }

        .content-body {
            font-size: 12px;
        }

        body {
            margin-top: 3.5cm;
            margin-bottom: 1cm;
        }

        @page {
            margin: 0.5cm 0.5cm;
        }

        table tr td h2, h4 {
            line-height: 0.5rem;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2rem;

        }
    </style>
</head>

<body>

<header>
    @php
        $patientInfo = $encounterData->patientInfo;
        $encounterId = $encounterData->fldencounterval;
    @endphp
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p><strong>Name:</strong> {{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ? $encounterData->fldrank:''}} {{ $patientInfo->fullname }} ({{$patientInfo->fldpatientval}})</p>
                <p><strong>Age/Sex:</strong> {{ $patientInfo->fldagestyle }} /{{ $patientInfo->fldptsex??"" }}</p>
                {{-- <p><strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }}yrs/{{ $patientInfo->fldptsex??"" }}</p> --}}
                <p><strong>Address:</strong> {{ $patientInfo->fulladdress??"" }}</p>
                <p><strong>REPORT:</strong> EEG</p>
            </td>
            <td style="width: 185px;">
                <p><strong>EncID:</strong> {{ $encounterId }}</p>
                <p><strong>DOReg:</strong> {{ $encounterData->fldregdate ? \Carbon\Carbon::parse($encounterData->fldregdate)->format('d/m/Y'):'' }}</p>
                <p><strong>Phone: </strong></p>
            </td>
        </tr>
        </tbody>
    </table>
</header>
    <main>
        {!! $examData->flddetail !!}
    </main>
</body>

</html>
