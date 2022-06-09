<!DOCTYPE html>
<!-- saved from url=(0040)file:///C:/Users/DELL/Downloads/pdf.html -->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Invoice for {{ $patbillingDetails->fldbillno??"" }}</title>

    <style>
        @page {
            margin: 24mm 0 11mm
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
            height: auto;
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
        .content-body th:nth-child(2) {
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
    </style>
</head>

<body>

    <!--<header class="heading" style="margin: 0 auto; width: 98%;text-align:center ">
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 20%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100"/></td>
            <td style="width:70%;">
                <h2 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h2>
                <h4 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                <h4 style="text-align: center;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                <h5>Pan No.: {{ Options::get('hospital_pan')?Options::get('hospital_pan'):Options::get('hospital_vat') }}</h5>
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
</header>-->

    <div class="pdf-container" style="margin: 0 auto; width: 95%;">

        <h5 class="bill-title">
            @if(isset($invoice_title))
            {{ strtoupper($invoice_title) }}
            @else
            INVOICE
            @endif
            @if($billCount > 1) (COPY OF ORIGINAL) Print-{{ $billCount-1 }}@endif
        </h5>
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
                    <td>Age/Sex: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fldagestyle.'/'.$enpatient->patientInfo->fldptsex:'' }}</td>
                </tr>
                <tr>
                    <td>Address: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptaddvill.' '.$enpatient->patientInfo->fldptadddist:'' }}</td>
                </tr>
                <tr>
                    <td >Phone No: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptcontact:'' }}</td>
                </tr>
                <tr>
                    <td >Payment: {{ ucfirst($patbillingDetails?$patbillingDetails->fldbilltype:'') }}</td>
                </tr>
            </tbody>
        </table>
        <table style="width: 40%;float:right;text-align:right;">
            <tbody>
                <tr>
                    <td>Pan Number: {{ Options::get('hospital_pan')?Options::get('hospital_pan'):Options::get('hospital_vat') }}</td>
                </tr>
                <tr>
                    <td>Bill Number: {{ $patbillingDetails?$patbillingDetails->fldbillno:'' }}</td>
                </tr>
                <tr>
                    <td>Transactions Date: {{ $patbillingDetails->fldtime ? \App\Utils\Helpers::dateToNepali( $patbillingDetails->fldtime) : '' }}</td>
                </tr>
                <tr>
                    <td>Doctor Name (Referal):{{ ($patbilling->isNotEmpty() && $patbilling[0]->referUserdetail) ? $patbilling[0]->referUserdetail->fldfullname : '' }}</td>
                </tr>
                <tr>
                    <td><img style="width: 50%" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode" /></td>
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
    <div class="pdf-container" >
        <div class="table-dental2" style="margin-top: 16px;">
            @if(isset($eachpatbilling) && $eachpatbilling)

            @foreach($eachpatbilling as $billing)
            @php
            $summaryTotal = 0;
            $summaryTaxTotal = 0;
            $summaryDiscountTotal = 0;
            @endphp
            <table class="table content-body">
                <thead class="thead-light">
                    <tr>
                        <th style="text-align: left; width: 40%">Particulars</th>
                        <th style="text-align: left; width: 10%">Svr Tax</th>
                        <th style="text-align: left; width: 10%">Discount</th>
                        <th style="text-align: left; width: 10%">Total</th>

                    </tr>
                </thead>
                <tbody>
                    @if($billing['details'])
                    @foreach($billing['details'] as $bill_detail)
                    <tr>
                        @php
                        $summaryTotal += $bill_detail->fldditemamt;
                        $summaryTaxTotal +=$bill_detail->flddiscamt;
                        $summaryDiscountTotal +=$bill_detail->fldtaxamt;

                        $flditemrate +=$bill_detail->fldditemamt;
                        $flddiscountamt +=$bill_detail->flddiscamt;
                        $flditemtax +=$bill_detail->fldtaxamt;
                        @endphp
                    </tr>
                    @endforeach
                    <tr>
                        <td>{{$billing['category']}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($summaryDiscountTotal)}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($summaryTaxTotal)}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($summaryTotal)}}</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            @endforeach
            @endif
        </div>

        <table class="table" style="margin-top:8px;">
            <tr>
                <td>
                    <p>In words: {{ $patbillingDetails? ucwords(\App\Utils\Helpers::numberToNepaliWords($patbillingDetails->fldreceivedamt)):'' }} /-

                    </p>
                    <p>{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})
                        <br>{{ date('Y-m-d H:i:s') }}
                    </p>
                </td>
                <td>
                    <ul>
                        <li><span>Sub Total:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($flditemrate) }}</span></li>
                        <li><span>Discount:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($flddiscountamt) }}</span></li>
                        <li><span>Total Tax:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($flditemtax) }}</span></li>
                        <li><span>Total Amt:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($fldditemamt) }}</span></li>
                        <li><span>Recv Amt:</span><span>Rs. {{ $patbillingDetails ? \App\Utils\Helpers::numberFormat($patbillingDetails->fldreceivedamt) : '0' }}</span></li>
                    </ul>
                </td>

            </tr>
        </table>



        <!-- <div class="signaturetitle" style="width: 20%; float: right; margin-top: 8%;">
        <label style="border-top: 1px dashed #000;">Authorized Signature</label>
    </div> -->
    </div>


    {{-- Parent billing --}}
    @if(isset($eachpatbilling_parent) && $eachpatbilling_parent)
    <div class="clearfix" style="page-break-after: always;"></div>
    <div class="pdf-container" style="margin: 0 auto; width: 98%;">

        <h5 style="text-align: center">
            @if(isset($invoice_title))
            {{ strtoupper($invoice_title) }}
            @else
            INVOICE
            @endif
            @if($parent_billCount > 1) (COPY OF ORIGINAL) Print-{{ $parent_billCount-1 }}@endif
        </h5>
        <div style="width: 100%;"></div>
        <table style="width: 60%">

            <tbody>
                <tr>
                    <td>EncID: {{ $enpatient->fldencounterval }}</td>
                </tr>
                <tr>
                    <td>
                        Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? $enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast:''}} ({{ $enpatient->fldpatientval }})</td>
                </tr>
                <tr>
                    <td>Age/Sex: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fldagestyle.'/'.$enpatient->patientInfo->fldptsex:'' }}</td>
                </tr>
                <tr>
                    <td>Address: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptaddvill.' '.$enpatient->patientInfo->fldptadddist:'' }}</td>
                </tr>
                <tr>
                    <td>Phone No: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptcontact:'' }}</td>
                </tr>
                <tr>
                    <td>
                        {{ ucfirst($parent_patbillingDetails?$parent_patbillingDetails->fldbilltype:'') }}
                    </td>
                </tr>

            </tbody>
        </table>
        <table style="width: 40%;float:right; text-align:right;">
            <tbody>
                <tr>
                    <td>Pan Number: {{ Options::get('hospital_pan')?Options::get('hospital_pan'):Options::get('hospital_vat') }}</td>
                </tr>
                <tr>
                    <td>Bill Number: {{ $parent_patbillingDetails?$parent_patbillingDetails->fldbillno:'' }}</td>
                </tr>
                <tr>
                    <td>Transactions Date: {{ $parent_patbillingDetails->fldtime }}</td>
                </tr>
                <tr>
                    <td>Doctor Name (Referal):{{ ($patbilling->isNotEmpty() && $patbilling[0]->referUserdetail) ? $patbilling[0]->referUserdetail->fldfullname : '' }}</td>
                </tr>

                <tr>
                    <td>Discount Mode: {{ (isset($discount_mode) && isset($discount_mode->discount_mode)) ? $discount_mode->discount_mode:'' }}</td>
                </tr>

                <tr>
                    <td>
                    <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode" />
                    </td>
                </tr>

                <tr>
                    <td>{{ $enpatient && $enpatient->fldbillingmode?$enpatient->fldbillingmode:'' }} {{ $enpatient && $enpatient->fldclaimcode?'('.$enpatient->fldclaimcode.')':'' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @php
    $parent_flditemrate = $parent_fldditemamt = $parent_flddiscountamt = $parent_flditemtax = 0;
    @endphp
    <div class="pdf-container">
        <div class="table-dental2" style="margin-top: 16px;">
            @if(isset($eachpatbilling_parent) && $eachpatbilling_parent)

            @foreach($eachpatbilling_parent as $billing)
            @php
            $summaryTotal = 0;
            $summaryTaxTotal = 0;
            $summaryDiscountTotal = 0;
            @endphp
            <table class="table content-body">
                <thead class="thead-light">
                    <tr>
                        <th style="text-align: left; width: 40%">Particulars</th>
                        <th style="text-align: left; width: 10%">Svr Tax</th>
                        <th style="text-align: left; width: 10%">Discount</th>
                        <th style="text-align: left; width: 10%">Total</th>

                    </tr>
                </thead>
                <tbody>
                    @if($billing['details'])
                    @foreach($billing['details'] as $bill_detail)
                    <tr>
                        @php
                        $summaryTotal += $bill_detail->fldditemamt;
                        $summaryTaxTotal +=$bill_detail->flddiscamt;
                        $summaryDiscountTotal +=$bill_detail->fldtaxamt;

                        $parent_flditemrate +=$bill_detail->fldditemamt;
                        $parent_flddiscountamt +=$bill_detail->flddiscamt;
                        $parent_flditemtax +=$bill_detail->fldtaxamt;
                        @endphp
                    </tr>
                    @endforeach
                    <tr>
                        <td>{{$billing['category']}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($summaryDiscountTotal)}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($summaryTaxTotal)}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($summaryTotal)}}</td>
                    </tr>
                    @endif
                </tbody>
            </table>

        </div>
        @endforeach
        @endif

        <table class="table" style="margin-top:8px;">
            <tr>
                <td>

                    <p>
                        In words: {{ $parent_patbillingDetails? ucwords(\App\Utils\Helpers::numberToNepaliWords($parent_patbillingDetails->fldreceivedamt)):'' }} /-
                    </p>


                </td>

                <td>
                    <ul>
                        <li><span>Sub Total:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($parent_flditemrate) }}</span></li>
                        <li><span>Discount:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($parent_flddiscountamt) }}</span></li>
                        <li><span>Total Tax:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($parent_flditemtax) }}</span></li>
                        <li><span>Total Amt:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($parent_fldditemamt) }}</span></li>
                        <li><span>Recv Amt:</span><span>Rs. {{ $parent_patbillingDetails ? \App\Utils\Helpers::numberFormat($parent_patbillingDetails->fldreceivedamt) : '0' }}</span></li>
                    </ul>

                </td>


            </tr>
        </table>

        <!-- <div class="" style="width: 50%; float: left; margin-top: 8%;padding-left:10px;">
            <p>{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})</p>
            <p>{{ date('Y-m-d H:i:s') }}</p>
        </div> -->


    </div>
    @endif

</body>
<script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            window.print();
        }, 3000);
    });
</script>

</html>
