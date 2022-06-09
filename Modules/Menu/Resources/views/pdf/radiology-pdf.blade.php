<!DOCTYPE html>
<html>
<head>
    <title>OPD Sheet</title>
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

@php
    $patientInfo = $encounterData->patientInfo;
@endphp
@include('pdf-header-footer.header-footer')
<main>

    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p><strong>Name:</strong> {{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{ $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }} ({{$patientInfo->fldpatientval}})</p>
                <p><strong>Age/Sex:</strong> {{ $patientInfo->fldagestyle }} /{{ $patientInfo->fldptsex??"" }}</p>
                {{-- <p><strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }}yrs/{{ $patientInfo->fldptsex??"" }}</p> --}}
                <p><strong>Address:</strong> {{ $patientInfo->fldptaddvill??"" . ', ' . $patientInfo->fldptadddist??"" }}</p>
                <p><strong>REPORT RADIOLOGY:</strong> OUTPATIENT REPORT</p>
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
        <tbody>
        <tr>
            <th style="width: 96px; text-align: center;">Category</th>
            <th style="width: 467.2px; text-align: center;">Observations</th>
        </tr>
        <tr>
            <td>
                <p>Radio Diagnostics</p>
            </td>
            <td>

                @if(count($patRadioTest))
                    @php
                        $iterationCount = 1;
                    @endphp
                    @foreach($patRadioTest as $radioValue)
                        {{--<p>{{ $b->radioData }} : {{ $b->radioSubTest }}</p>--}}
                        {{ $iterationCount }}..
                        <br>
                        @if(count($radioValue->radioSubTest))
                            @foreach($radioValue->radioSubTest as $radioSubTestValue)
                                <p>{{ $radioSubTestValue->fldsubtest }}: {{ $radioSubTestValue->fldreport }}</p>
                            @endforeach

                        @endif
                    @endforeach
                    @php
                        $iterationCount++;
                    @endphp
                @endif

            </td>
        </tr>
        </tbody>
    </table>
</main>
</body>
</html>
