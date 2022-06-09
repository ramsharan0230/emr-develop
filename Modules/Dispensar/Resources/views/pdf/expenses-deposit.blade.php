<!DOCTYPE html>
<html>
<head>
    <title>{{$certificate}}</title>
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
                <p><strong>REPORT:</strong>{{$certificate}} REPORT</p>
            </td>
            <td style="width: 185px;">
                <p><strong>EncID:</strong> {{ $encounterId }}</p>
                <p><strong>DOReg:</strong> {{ $encounterData->fldregdate ? \Carbon\Carbon::parse($encounterData->fldregdate)->format('d/m/Y'):'' }}</p>
                <p><strong>Phone: </strong></p>
            </td>
{{--            <td style="width: 130px;">{!! \App\Utils\Helpers::generateQrCode($encounterId)!!}</td>--}}
        </tr>
        </tbody>
    </table>

    <table style="width: 100%;" border="1px" rules="all" class="content-body">
        <tbody>
        <tr>
            <th></th>
            <th>DateTime</th>
            <th>Bill No.</th>
            <th>Category</th>
            <th>Particulars</th>
        </tr>
        {!! $html !!}
        </tbody>
    </table>
    @php
        $signatures = \App\Utils\Helpers::getSignature('opd');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
