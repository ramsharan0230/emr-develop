<!DOCTYPE html>
<html>
<head>
    <title>LABORATORY SAMPLE REPORT</title>
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
        <li>TESTS TO REPORT</li>
        <li>{{$category=="%"?"All":$category}}</li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">SNo</th>
            <th class="tittle-th">SamTime</th>
            <th class="tittle-th">Encounter</th>
            <th class="tittle-th">PatientName</th>
            <th class="tittle-th">SampleNo</th>
            <th class="tittle-th">TestName</th>
            <th class="tittle-th">Observation</th>
        </tr>
        </thead>
        <tbody>
        @if(count($pdfData))
            @foreach($pdfData as $sample)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sample->fldtime_sample }}</td>
                    <td>{{ $sample->fldencounterval }}</td>
                    <td>{{ $sample->patientEncounter?$sample->patientEncounter->patientInfo?$sample->patientEncounter->patientInfo->fldptnamefir . ' ' . $sample->patientEncounter->patientInfo->fldmidname .' '. $sample->patientEncounter->patientInfo->fldptnamelast:'':''}}</td>
                    <td>{{ $sample->fldsampleid }}</td>
                    <td>{{ $sample->fldtestid }}</td>
                    <td>{{ $sample->fldtest_type == "Quantitative"?$sample->fldreportquanti:$sample->fldreportquali }}</td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>

    @php
        $signatures = Helpers::getSignature('laboratory');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
