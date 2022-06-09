<html>
<head>
    <style>
        @page {
            margin: 0px 1mm 0 2px;
        }

        body {
            margin: 0px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        p {
            margin: 0;
            padding: 1px 2px;
        }
    </style>
</head>
<body>

<!-- <h4 style="margin-left: 10px; margin-bottom: 5px; white-space:nowrap">{{(isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'')}} </h4> -->
<table class='header_table' style="text-align: left; width: 100%; font-size:8pt; margin-bottom: 0;" id="headtable">
    <tr>
        <td rowspan="1" style="width: 16%;">
            {!! Helpers::generateQrCode(($encounter) ? $encounter->fldpatientval : '')!!}
            <p style="margin-left:1px;margin-top:8px;font-size:8px;">
               Rs. {{ Helpers::numberFormat($registrationCost) }}
            </p>
            </td>
        <td>
            <p >
                Dept:  <b style="font-size:9pt;font-weight:600;">{{ $dept }}</b>
            </p>

            <p >
                Doc:  <b style="font-size:9.5pt;line-height:8pt;font-weight:600;text-transform:uppercase;">{{ $doc }}</b>
            </p>
            <p >
                Name:  <b style="font-size:9.5pt;line-height:10pt;font-weight:600;">{{  ($patient && $patient->patientInfo) ? $patient->patientInfo->fldfullname : '' }} [{{ $patient->fldvisit }}]</b>
            </p>
            <p>
                Patient No.:  <b>{{ ($patient && $patient->patientInfo) ? $patient->patientInfo->fldpatientval : '' }}</b>
            </p>
            <p>
                EncID:  <b>{{($encounter) ? $encounter->fldencounterval : ''}}</b>
            </p>
            @if($encounter && ((strtolower($encounter->fldbillingmode) == 'health insurance') || (strtolower($encounter->fldbillingmode) == 'healthinsurance') || (strtolower($encounter->fldbillingmode) == 'hi')) )
             <p>
                NHSI No:  <b>{{ ($patient && $patient->patientInfo) ? $patient->patientInfo->fldnhsiid : ''}}</b>
            </p>
             <p>
                Claim Code:  <b>{{($encounter) ? $encounter->fldclaimcode : ''}}</b>
            </p>
            @endif


            <p>
                Age/Sex: {{  ($patient && $patient->patientInfo) ? $patient->patientInfo->fldagestyle : '' }}/{{  ($patient && $patient->patientInfo) ? $patient->patientInfo->fldptsex : '' }}
            </p>

            <p>
                Add.: @if($patient->patientInfo->fldcountry !='NEPAL') {{$patient->patientInfo->fldcountry}},{{$patient->patientInfo->fldptaddvill}} @else {{  ($patient && $patient->patientInfo) ? "{$patient->patientInfo->fldmunicipality}-{$patient->patientInfo->fldwardno}, {$patient->patientInfo->fldptadddist},{$patient->patientInfo->fldptaddvill}, {$patient->patientInfo->fldprovince}" : '' }} @endif
            </p>


            <p>
                PH: {{  ($patient && $patient->patientInfo) ? $patient->patientInfo->fldptcontact : '' }}
            </p>
            <p>
            Guardian: {{ ($patient && $patient->patientInfo) ?  strtoupper($patient->patientInfo->fldptguardian) : ''}} @if($patient->patientInfo->fldrelation) ({{ strtoupper($patient->patientInfo->fldrelation) }})@endif
             </p>
            <p>
                USER: {{ ($patient && $patient->consultant) ? "{$patient->consultant->fldorduserid} " . \App\Utils\Helpers::dateToNepali($patient->consultant->fldtime) : $patient->created_by }}
            </p>

        </td>

    </tr>

</table>
</body>

</html>
