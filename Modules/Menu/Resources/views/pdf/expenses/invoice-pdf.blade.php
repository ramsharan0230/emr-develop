<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laboratory PDF</title>
    <style>
        .table-design {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .table-design td, .table-design th {
            border: 1px solid #ddd;
            padding: 8px;
            text-transform: uppercase;
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
                <p><strong>Name:</strong> {{ Options::get('system_patient_rank')  == 1 && (isset($encounterData)) && (isset($encounterData->fldrank) ) ?$encounterData->fldrank:''}} {{ ucwords(strtolower($patientInfo->fldptnamefir . ' ' . $patientInfo->fldmidname . ' ' . $patientInfo->fldptnamelast)) }} ({{$patientInfo->fldpatientval}})</p>
                <p><strong>Age/Sex:</strong> {{ $patientInfo->fldagestyle }} / {{ $patientInfo->fldptsex??"" }}</p>
                {{-- <p><strong>Age/Sex:</strong> {{ \Carbon\Carbon::parse($patientInfo->fldptbirday??"")->age }} Years / {{ $patientInfo->fldptsex??"" }}</p> --}}
                <p><strong>Address:</strong> {{ $patientInfo->fldptaddvill??"" . ', ' . $patientInfo->fldptadddist??"" }}</p>
            </td>
            <td style="width: 185px;">
                <p><strong>EncID:</strong> {{ $encounterId }}</p>
                <p><strong>DOReg:</strong> {{ $encounterData->fldregdate ? \Carbon\Carbon::parse($encounterData->fldregdate)->format('d/m/Y'):'' }}</p>
                <p><strong>Phone: </strong></p>
            </td>
            <td style="width: 130px;">{!! Helpers::generateQrCode($encounterId)!!}</td>
        </tr>
        <tr>
            <td style="width: 200px;"><p><strong>REPORT:</strong> Incoices</p></td>
        </tr>
        </tbody>
    </table>

    <table class="table-design">
        <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Invoice NO</th>
            <th>Prev Depo</th>
            <th>SubTotal</th>
            <th>Tax AMT</th>
            <th>Disc AMT</th>
            <th>Total AMT</th>
            <th>Recv AMT</th>
            <th>Curr Depo</th>
            <th>Category</th>
            <th>Value</th>
        </tr>
        </thead>

        <tbody>
        @if(count($tblpatbilling))
            @php $total_amount = 0; @endphp
            @php $grand_total = 0; @endphp
            @php $received_total = 0; @endphp
            @php $sub_total = 0; @endphp
            @php $tax_total = 0; @endphp
            @php $disc_total = 0; @endphp
            @foreach($tblpatbilling as $billing)
                <tr>
                    <td>{{ $billing->fldtime }}</td>
                    <td>{{ $billing->fldbilltype }}</td>
                    <td>{{ $billing->fldbillno }}</td>
                    <td>Rs. {{ $billing->fldprevdeposit }} /-</td>
                    <td>Rs. {{ $billing->flditemamt }} /-</td>
                    <td>Rs. {{ $billing->fldtaxamt }} /-</td>
                    <td>Rs. {{ $billing->flddiscountamt }} /-</td>
                    @php $total_amount = $billing->flditemamt + $billing->fldtaxamt - $billing->flddiscountamt; @endphp
                    <td>Rs. {{ $total_amount }} /-</td>
                    <td>Rs. {{ $billing->fldreceivedamt }} /-</td>
                    <td>Rs. {{ $billing->fldcurdeposit }} /-</td>
                    <td></td>
                    <td></td>
                </tr>
                @php $grand_total += $total_amount; @endphp
                @php $received_total += $billing->fldreceivedamt; @endphp
                @php $sub_total += $billing->flditemamt; @endphp
                @php $tax_total += $billing->fldtaxamt; @endphp
                @php $disc_total += $billing->flddiscountamt; @endphp
            @endforeach
        @else
            <tr>
                <td colspan="8" style="text-align: center;">*** Bill Clear ***</td>
            </tr>
        @endif
        </tbody>

        <tfoot>
        @if(count($tblpatbilling))
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Rs. {{ $sub_total }} /-</td>
            <td>Rs. {{ $tax_total }} /-</td>
            <td>Rs. {{ $disc_total }} /-</td>
            <td>Rs. {{ $grand_total }} /-</td>
            <td>Rs. {{ $received_total }} /-</td>
            <td></td>
            <td></td>
            <td></td>
        @endif
        </tfoot>
    </table>

    <p>Admin, {{ date('M j, Y') }}</p>
</main>
</body>
</html>
