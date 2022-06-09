<!DOCTYPE html>
<html>

<head>
    <title>Equipment Report</title>
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
    </style>
</head>

<body>

@include('pdf-header-footer.header-footer')
<main>
    <ul>
        <li>TOTAL : {{$total}}</li>
    </ul>
    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">Index</th>
            <th class="tittle-th">EncID</th>
            <th class="tittle-th">Name</th>
            <th class="tittle-th">Gender</th>
            <th class="tittle-th">Start</th>
            <th class="tittle-th">Stop</th>
        </tr>
        </thead>
        <tbody>

        @if(isset($result) and count($result) > 0)
            @foreach($result as $patient)
                <tr>
                    <td> {{ $loop->iteration }} </td>
                    <td> {{$patient->fldencounterval}} </td>
                    <td> {{ Options::get('system_patient_rank')  == 1 && (isset($patient->encounter)) && (isset($patient->encounter->fldrank) ) ?$patient->encounter->fldrank:''}}  {{$patient->encounter->patientInfo->fldptnamefir??""}} {{$patient->encounter->patientInfo->fldmidname??""}} {{$patient->encounter->patientInfo->fldptnamelast??""}} </td>
                    <td> {{$patient->encounter->patientInfo->fldptsex??""}} </td>
                    <td> {{$patient->fldfirsttime}} </td>
                    <td> {{$patient->fldsecondtime}} </td>
                    </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9">No Data Available</td>
            </tr>
        @endif

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('equipment');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>

</body>

</html>
