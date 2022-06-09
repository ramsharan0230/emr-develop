<!DOCTYPE html>
<html>

<head>
    <title>Summarize Report</title>
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
    $patientInfo = $encounter_data->patientInfo;
    $iterationCount = 1;
@endphp
@include('pdf-header-footer.header-footer')
<main>
    <table style="width: 100%; float: left;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p><strong>Name:</strong> {{ Options::get('system_patient_rank')  == 1 && (isset($encounter_data)) && (isset($encounter_data->fldrank) ) ?$encounter_data->fldrank:''}} {{ $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }} ({{$patientInfo->fldpatientval}})</p>
                <p><strong>Age/Sex:</strong> {{ $patientInfo->fldagestyle }} /{{ $patientInfo->fldptsex??"" }}</p>
                {{-- <p><strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }}yrs/{{ $patientInfo->fldptsex??"" }}</p> --}}
                <p><strong>Address:</strong> {{ $patientInfo->fldptaddvill??"" . ', ' . $patientInfo->fldptadddist??"" }}</p>
                <p><strong>Department:</strong> % </p>
            </td>
            <td style="width: 185px;">
                <p><strong>EncID:</strong> {{ $encounter_data->fldencounterval }}</p>
                <p><strong>DOReg:</strong> {{ $encounter_data->fldregdate ? \Carbon\Carbon::parse($encounter_data->fldregdate)->format('d/m/Y'):'' }}</p>
                <p><strong>Phone: </strong></p>
            </td>
            <td style="width: 130px;">{!! $barcodeData !!}</td>
        </tr>
        </tbody>
    </table>
    <div style="clear: both"></div>
    {{--<ul>
        <li>SUMMARY: {{strtoupper($pdfType)}}</li>
        <li>DATE: {{$date_from}} TO {{$date_to}}</li>
        <li>TOTAL : {{$total}}</li>
    </ul>--}}
    <table style="width: 100%;" class="content-body">
        <thead>
        <tr style="background-color: #dddddd;border: 1px solid #dddddd;">
            <th class="tittle-th" style="width: 200px;">Test Name</th>
            <th class="tittle-th">Observation</th>
        </tr>
        </thead>
        <tbody>
        @php
            $department = [];
        @endphp
        @if(count($testsData))
            @foreach($testsData as $test)
                @if (!in_array($test->fldcategory, $department))
                    @php
                        array_push($department, $test->fldcategory);
                        $newData = $testsData->where('fldcategory', $test->fldcategory)->all();
                    @endphp

                    <tr>
                        <td colspan="2"><h3>{{ $test->fldcategory }}</h3></td>
                    </tr>
                    @foreach($newData as $da)
                        <tr>
                            <td>
                                <strong style="font-weight: bold">{{ $da->fldtestid }}</strong>
                                <p>[{{$da?$da->fldmethod:''}}]</p>
                            </td>
                            <td></td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
    <hr>

    @php
        $signatures = Helpers::getSignature('laboratory');
    @endphp
    @include('frontend.common.footer-signature-pdf')
    <p>admin, {{date('Y-m-d')}}
    </p>
</main>
</body>

</html>
