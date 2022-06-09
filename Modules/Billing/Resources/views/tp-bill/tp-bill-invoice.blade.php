<!DOCTYPE html>
<!-- saved from url=(0040)file:///C:/Users/DELL/Downloads/pdf.html -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- <title>Invoice for {{ $patbillingDetails->fldbillno??"" }}</title> -->
</head>
<body>
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
        margin:0;
        padding:0;
    }

    .bar-code {
        width: 200px;
        height: auto;
        margin-top:5px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
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
    .content-body th:nth-child(2) {
        text-align: left;

    }


    .content-body td,
    .content-body th {
        border: 1px solid #ddd;
        font-size: 13px;
        text-align: right;
        padding-right:4px;
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
        text-align: right;
    ;
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
    .table thead{
        background-color: #fff;
    }
</style>
<div class="a4">

    <div class="main-body">

        <div class="pdf-container" style="margin: 0 auto; width: 95%;">

            <h6 class="bill-title">
                TP RECEIPT
            </h6>
            <div style="width: 100%;"></div>
            <table style="width: 60%; float: left;">
                <tbody>
                <tr>
                    <td>EncID: {{ $enpatient->fldencounterval }}</td>
                </tr>
                <tr>
                    <td>
                        Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? ucwords(strtolower($enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast)):''}}</td>
                </tr>
                <tr>

                    <td>Age/Sex: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fldage.'/'.$enpatient->patientInfo->fldptsex:'' }}</td>
                </tr>
                <tr>
                    <td>Address: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptaddvill.' '.$enpatient->patientInfo->fldptadddist:'' }}</td>
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
                    <td>Bill Number: {{ $patbilling?$patbilling[0]->fldtempbillno:'' }}</td>
                </tr>
                <tr>
                    <td>Transactions Date: {{ date('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td><img style="width: 50%" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode"/></td>
                </tr>
                <tr>
                    <td>{{ $enpatient && $enpatient->fldbillingmode?$enpatient->fldbillingmode:'' }} {{ $enpatient && $enpatient->fldclaimcode?'('.$enpatient->fldclaimcode.')':'' }}</td>
                </tr>
                </tbody>
            </table>
            <div style="clear: both"></div>
        </div>
        @php
            $flditemrate = $fldditemamt = $flddiscountamt = $flditemtax = 0;
        @endphp
        <div class="pdf-container" style="margin: 0 auto; width: 95%;">
            <div class="table-dental2" style="margin-top: 16px;">
                <table class="table content-body">
                    <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Particulars</th>
                        <th>QTY</th>
                        <th>Rate</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($patbilling))
                        @forelse($patbilling as $billItem)
                            @php
                                $flditemtax += $billItem->fldtaxamt;
                                $flditemrate += $billItem->flditemrate * $billItem->flditemqty;
                                $flddiscountamt += $billItem->flddiscamt;
                                $fldditemamt += $billItem->fldditemamt;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $billItem->flditemname }}</td>
                                <td>{{ $billItem->flditemqty }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($billItem->flditemrate) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($billItem->flddiscamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($billItem->fldtaxamt) }}</td>
                                <td>{{ \App\Utils\Helpers::numberFormat($billItem->fldditemamt) }}</td>
                            </tr>
                        @empty

                        @endforelse
                    @endif

                    </tbody>
                </table>

                <ul>
                    <li><span>Sub Total: </span><span>Rs. {{  \App\Utils\Helpers::numberFormat($flditemrate) }}</span></li>
                    <li><span>Discount: </span><span>Rs. {{  \App\Utils\Helpers::numberFormat($flddiscountamt) }}</span></li>
                    <li><span>Total Tax: </span><span>Rs. {{  \App\Utils\Helpers::numberFormat($flditemtax) }}</span></li>
                    <li><span>Total Amt: </span><span>Rs. {{  \App\Utils\Helpers::numberFormat(($fldditemamt)) }}</span></li>
                    <li><span>Receive Amt: </span><span>Rs. 0.00</span></li>

                </ul>

                <div>
                    <p style="float:left; padding-left:10px;padding-top: 1rem;">In words: {{ ucwords(\App\Utils\Helpers::numberToNepaliWords($fldditemamt))}} /-</p>

                    <div style="clear: both"></div>

                    <div style="display:flex;justify-content:space-between;">
                        <div class="" style="width: 50%; float: left; margin-top: 0px;padding-left:10px;">
                            <p>{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})
                                <br>{{ date('Y-m-d H:i:s') }}</p>
                        </div>


                    </div>
                </div>
                <p  class="footer">GET WELL SOON!</p>
            </div>
        </div>
    </div>
</div>


</body>
<script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
<script>
    $(document).ready(function () {
        setTimeout(function () {
            window.print();
        }, 3000);
    });
</script>
</html>
