<!DOCTYPE html>
<!-- saved from url=(0040)file:///C:/Users/DELL/Downloads/pdf.html -->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Previous TP data</title>
</head>
<style>
    @page {
        margin: 24mm 0 11mm;
    }

    body {
        margin: 0 auto;
        padding: 10px 10px 5px;
        font-size: 13px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .bill-title {
        position: absolute;
        width: 100%;
        text-align: center;
        margin-bottom: 2px;
        margin-top: 3px;
    }


    .a4 {
        width: auto;
        margin: 0 auto;
    }

    .footer {
        /* position: absolute; */
        width: 100%;
        text-align: center;
        margin: 0;
        padding: 0;
    }

    .bar-code {
        width: 200px;
        height: 25px;
        margin-top: 5px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .pdf-container {
        margin: 0 auto;
        width: 95%;
    }

    .content-body {
        border-collapse: collapse;
    }

    .content-body table {
        page-break-inside: auto
    }

    .content-body tr {
        page-break-inside: avoid;
        page-break-after: auto
    }

    .content-body td:nth-child(1),
    .content-body th:nth-child(1),
    .content-body td:nth-child(2),
    .content-body th:nth-child(2),
    .content-body th:nth-child(3),
    .content-body td:nth-child(3) {
        text-align: left;

    }


    .content-body td,
    .content-body th {
        border: 1px solid #ddd;
        font-size: 13px;
        text-align: right;
        padding-right: 4px;
    }

    h2,
    h4 {
        line-height: 0.5rem;
    }

    ul {
        float: right;
        padding: 0;
        margin: 0;
    }

    ul li {
        text-align: right;;
        list-style: none;

    }

    ul li span:first-child {
        text-align: left;
    }

    ul li span:nth-child(2) {
        text-align: right;
        width: 150px;
        display: inline-block;
    }
</style>
<body>


<div class="pdf-container" style="margin: 0 auto; width: 95%;">

    <h5 class="bill-title">Previous TP</h5>
    <div style="width: 100%;"></div>
    <table style="width: 60%;float:left;">
        <tbody>
        <tr>
            <td>EncID: {{ $enpatient->fldencounterval }}</td>
        </tr>
        <tr>
            <td>
                Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? $enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast:''}} ({{ $enpatient->fldpatientval }})
            </td>
        </tr>
        <tr>
            <td>Age/Sex: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fldagestyle.'/'.$enpatient->patientInfo->fldptsex:'' }}</td>
        </tr>
        <tr>
            <td>Address: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fulladdress:'' }}</td>
        </tr>
        <tr>
            <td>Phone No: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptcontact:'' }}</td>
        </tr>
        </tbody>
    </table>

    <table style="width: 40%;float:right;text-align:right;">
        <tbody>
        <tr>
            <td>Pan Number: {{ Options::get('hospital_pan')?Options::get('hospital_pan'):Options::get('hospital_vat') }}</td>
        </tr>

        <tr>
            <td>Billing Mode: {{ $enpatient && $enpatient->fldbillingmode?$enpatient->fldbillingmode:'' }} {{ $enpatient && $enpatient->fldclaimcode?'('.$enpatient->fldclaimcode.')':'' }}</td>
        </tr>

        </tbody>
    </table>
    <div style="clear: both"></div>
</div>
<div class="pdf-container" style="margin: 0 auto; width: 95%;">
    <table class="table content-body">
        <thead>
        <tr>
            <th>SNo</th>
            <th>Bill No</th>
            <th>Name</th>
            <th>Qty</th>
            <th>Tax</th>
            <th>Discount</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        @php
            $subTotal = $taxTotal = $discountTotal = $amountTotal = 0;
        @endphp
        @if($totalAmountReceivedByEncounter)
            @foreach($totalAmountReceivedByEncounter as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->fldtempbillno }}</td>
                    <td>{{ $item->flditemname }}</td>
                    <td>{{ $item->flditemqty }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($item->fldtaxamt) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($item->flddiscamt)??0 }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($item->fldditemamt) }}</td>
                </tr>
                @php
                    $subTotal += $item->flditemqty*$item->flditemrate;
                    $taxTotal += $item->fldtaxamt;
                    $discountTotal += $item->flddiscamt;
                    $amountTotal += $item->fldditemamt-$item->flddiscamt+$item->fldtaxamt;
                @endphp
            @endforeach
        @endif
        </tbody>
    </table>

</div>
<div class="pdf-container" style="margin: 0 auto; width: 95%;">
    <table class="table" style="margin-top:8px;">
        <tr>
            <td>
                <p>In words: {{ ucwords(\App\Utils\Helpers::numberToNepaliWords($amountTotal))}} /-&nbsp;
                </p>
                @if ($enpatient && $enpatient->fldencounterval)
                    <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode"/>
                @endif

            </td>
            <td>
                <ul>
                    <li><span>Sub Total:</span><span>Rs.{{  \App\Utils\Helpers::numberFormat(($subTotal))}}</span></li>
                    <li><span>Discount:</span><span>Rs.{{  \App\Utils\Helpers::numberFormat($discountTotal) }}</span></li>
                    <li><span>Total Tax:</span><span>Rs.{{  \App\Utils\Helpers::numberFormat($taxTotal) }}</span></li>
                    <li><span>Recv Amt:</span><span>Rs.{{  \App\Utils\Helpers::numberFormat($amountTotal) }}</span></li>
                </ul>
            </td>


        </tr>
    </table>
</div>
</body>

</html>
