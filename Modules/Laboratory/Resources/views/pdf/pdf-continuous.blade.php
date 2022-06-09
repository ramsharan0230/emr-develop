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

@php
    $patientInfo = $encounterData->patientInfo;
    $iterationCount = 1;
@endphp
@include('pdf-header-footer.header-footer')
<main>

    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p><strong>Name:</strong>  {{ Options::get('system_patient_rank')  == 1 ? $patientInfo->fldrank : '' }} {{ $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }} ({{$patientInfo->fldpatientval}})</p>
                <p><strong>Age/Sex:</strong> {{ $patientInfo->fldagestyle }} /{{ $patientInfo->fldptsex??"" }}</p>
                {{-- <p><strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }}yrs/{{ $patientInfo->fldptsex??"" }}</p> --}}
                <p><strong>Address:</strong> {{ $patientInfo->fldptaddvill??"" . ', ' . $patientInfo->fldptadddist??"" }}</p>
                <p><strong>REPORT:</strong>LABORATORY SAMPLE REPORT</p>
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

    <table style="width: 100%;" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">SN</th>
            <th class="tittle-th">SamID</th>
            <th class="tittle-th" style="width: 150px;">Test Name</th>
            <th class="tittle-th">Flag</th>
            <th class="tittle-th">Observation</th>
            {{--        <th class="tittle-th">Visibility</th>--}}
            <th class="tittle-th">Specimen</th>
            <th class="tittle-th">Method</th>
            <th class="tittle-th">Sample Time</th>
            <th class="tittle-th">Report Time</th>
            <th class="tittle-th">Comment</th>
        </tr>
        </thead>
        <tbody>
        @if(count($samples))
            @foreach($samples as $key => $sample)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sample->fldsampleid??"" }}</td>
                    <td>{{ $sample->fldtestid??"" }}</td>
                    <td>{!! (isset($sample->fldabnormal) && $sample->fldabnormal == '0') ? '<div style="background-color: green;width: 15px;height: 15px;"></div>' : '<div style="background-color: red;width: 15px;height: 15px;"></div>' !!}</td>
                    <td>{{ ($sample->fldtest_type == 'Quantitative' ? $sample->fldreportquanti : $sample->fldreportquali) }}</td>
                    {{--                <td>{{ $sample->flvisible }}</td>--}}
                    <td>{{ $sample->fldsampletype }}</td>
                    <td>{{ $sample->fldmethod }}</td>
                    <td>{{ $sample->fldtime_sample }}</td>
                    <td>{{ $sample->fldtime_report }}</td>
                    <td>{{ $sample->fldcomment }}</td>
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
