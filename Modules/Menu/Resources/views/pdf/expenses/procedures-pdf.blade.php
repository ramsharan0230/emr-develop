<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Procedures PDF</title>
    <style>
        .table-design {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .table-design td, .table-design th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table-design tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-design tr:hover {
            background-color: #ddd;
        }

        .table-design th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #999;
            color: white;
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
                <p><strong>Name:</strong> {{ Options::get('system_patient_rank')
== 1 && (isset($encounterData))
&& (isset($encounterData->fldrank) ) ?
$encounterData->fldrank:''}}
                    {{ $patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast }}
                    ({{$patientInfo->fldpatientval}})</p>
                <p>
                    <strong>Age/Sex:</strong> {{ $patientInfo->fldagestyle }}
                    {{-- <strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }} --}}
                     / {{ $patientInfo->fldptsex??"" }}</p>
                <p>
                    <strong>Address:</strong> {{ $patientInfo->fldptaddvill??"" . ', ' . $patientInfo->fldptadddist??"" }}
                </p>
            </td>
            <td style="width: 185px;">
                <p><strong>EncID:</strong> {{ $encounterId }}</p>
                <p>
                    <strong>DOReg:</strong> {{ $encounterData->fldregdate ? \Carbon\Carbon::parse($encounterData->fldregdate)->format('d/m/Y'):'' }}
                </p>
                <p><strong>Phone: </strong></p>
            </td>
            <td style="width: 130px;">{!! Helpers::generateQrCode($encounterId)!!}</td>
        </tr>
        <tr>
            <td style="width: 200px;"><p><strong>REPORT:</strong> Procedures</p></td>
        </tr>
        </tbody>
    </table>

    <table class="table-design">
        <thead>
        <tr>
            <th>Date</th>
            <th>Particulars</th>
            <th>Rate</th>
            <th>Tax%</th>
            <th>Disc%</th>
            <th>QTY</th>
            <th>Total</th>
            <th>Invoice</th>
        </tr>
        </thead>

        <tbody>
        @if(count($tblpatbilling))
            @php $total_amount = 0; @endphp
            @foreach($tblpatbilling as $billing)
                <tr>
                    <td>{{ $billing->fldtime }}</td>
                    <td>{{ $billing->flditemtype }}</td>
                    <td>{{ $billing->flditemrate }}</td>
                    <td>{{ $billing->fldtaxper }}</td>
                    <td>{{ $billing->flddiscper }}</td>
                    <td>{{ $billing->flditemqty }}</td>
                    <td>{{ $billing->tot }}</td>
                    <td>{{ $billing->fldbillno }}</td>
                </tr>
                @php $total_amount += $billing->tot; @endphp
            @endforeach
        @else
            <tr>
                <td colspan="8" style="text-align: center;">*** Bill Clear ***</td>
            </tr>
        @endif
        </tbody>

        <tfoot>
        @if(count($tblpatbilling))
            <td colspan="6"></td>
            <td>{{ $total_amount }}</td>
            <td></td>
        @endif
        </tfoot>
    </table>

    <p>Admin, {{ date('M j, Y') }}</p>
</main>
</body>
</html>
