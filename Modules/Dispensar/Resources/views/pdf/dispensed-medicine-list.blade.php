<!DOCTYPE html>
<html>
<head>
    <title>Requested Medicine List</title>
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
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 200px;">
                <p><strong>Name:</strong>  {{ Options::get('system_patient_rank')  == 1 ? $patientInfo->fldrank : '' }} {{ $patientInfo->patientInfo->fldptnamefir . ' ' . $patientInfo->patientInfo->fldmidname . ' ' . $patientInfo->patientInfo->fldptnamelast }} ({{$patientInfo->fldpatientval}})</p>
                <p><strong>Age/Sex:</strong> {{ $patientInfo->patientInfo->fldagestyle }} /{{ $patientInfo->patientInfo->fldptsex??"" }}</p>
                {{-- <p><strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->patientInfo->fldptbirday??"")->age }}yrs/{{ $patientInfo->patientInfo->fldptsex??"" }}</p> --}}
                <p><strong>Address:</strong> {{ $patientInfo->patientInfo->fldptaddvill??"" . ', ' . $patientInfo->patientInfo->fldptadddist??"" }}</p>
                <p><strong>REPORT:</strong>REQUESTED MEDICINE REPORT</p>
            </td>
            <td style="width: 185px;">
                <p><strong>EncID:</strong> {{ $patientInfo->fldencounterval }}</p>
                <p><strong>DOReg:</strong> {{ $patientInfo->fldregdate ? \Carbon\Carbon::parse($patientInfo->fldregdate)->format('d/m/Y'):'' }}</p>
                <p><strong>Phone: {{$patientInfo->patientInfo->fldptcontact}}</strong></p>
            </td>
{{--            <td style="width: 130px;">{!! Helpers::generateQrCode($encounterId)!!}</td>--}}
        </tr>
        </tbody>
    </table>

    <table style="width: 100%;" border="1px" rules="all" class="content-body">
        <tbody>
        <tr>
            <th></th>
            <th>DateTime</th>
            <th>Route</th>
            <th>Particulars</th>
            <th>Dose</th>
            <th>Freq</th>
            <th>Day</th>
            <th>Qty</th>
            <th>Sender</th>
        </tr>
        @if(isset($medicines) and count($medicines) > 0)
            @foreach($medicines as $medicine)
            <tr>
                <td style="text-align: center;">{{$loop->iteration}}</td>
                <td style="text-align: center;">{{$medicine->fldtime_order}}</td>
                <td style="text-align: center;">{{$medicine->fldroute}}</td>
                <td style="text-align: center;">{{$medicine->flditem}}</td>
                <td style="text-align: center;">{{$medicine->flddose}}</td>
                <td style="text-align: center;">{{$medicine->fldfreq}}</td>
                <td style="text-align: center;">{{$medicine->flddays}}</td>
                <td style="text-align: center;">{{$medicine->fldqtydisp}}</td>
                <td style="text-align: center;">{{$medicine->flduserid_order}}</td>
            </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('opd');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
