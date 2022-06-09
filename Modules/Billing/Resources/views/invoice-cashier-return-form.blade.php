<!DOCTYPE html>
<!-- saved from url=(0040)file:///C:/Users/DELL/Downloads/pdf.html -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Invoice for {{ $patbillingDetails->fldbillno??"" }}</title>
</head>
<body>
<style>
    @page {
        margin: 21mm 0 12mm;
    }

    body {
        margin: 0 auto;
        padding: 10px 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .content-body {
        border-collapse: collapse;
    }

    .content-body td, .content-body th {
        border: 1px solid #ddd;
    }

    h2, h4 {
        line-height: 0.5rem;
    }

    ul {
        float: right;
    }

    ul li {
        list-style: none;
        padding-right: 2rem;
    }
</style>
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

<div class="pdf-container" style="margin: 0 auto; width: 98%;">

    <h5 style="text-align: center">
        @if(isset($invoice_title))
            {{ strtoupper($invoice_title) }}
        @else
            INVOICE
        @endif
        @if($billCount > 1) (COPY OF ORIGINAL) Print-{{ $billCount-1 }}@endif
    </h5>
    <div style="width: 100%;"></div>
    <table style="width: 70%; float: left;">

        <tbody>
        <tr>
            <td style="width: 60%;">EncID: {{ $enpatient->fldencounterval }}</td>
        </tr>
        <tr>
            <td style="width: 60%;">
                Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? ucwords(strtolower($enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast)):''}} ({{ $enpatient->fldpatientval }})
            </td>
        </tr>
        <tr>
            <td style="width: 60%;">Address: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptaddvill.' '.$enpatient->patientInfo->fldptadddist:'' }}</td>
        </tr>
        <tr>
            <td style="width: 35%;">Phone No: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptcontact:'' }}</td>
        </tr>
        <tr>
            <td style="width: 35%;">Payment: {{ ucfirst($patbillingDetails?$patbillingDetails->fldbilltype:'') }}</td>
        </tr>
        </tbody>
    </table>
    <table style="width: 30%;float:right;">
        <tbody>
        <tr>
            <td style="width: 35%;">Bill Number: {{ $patbillingDetails?$patbillingDetails->fldbillno:'' }}</td>
        </tr>
        <tr>
            <td style="width: 35%;">Transactions Date: {{ $patbillingDetails->fldtime }}</td>
        </tr>
        <tr>
            <td><img style="width: 50%" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode"/></td>
        </tr>
        <tr>
            <td>{{ $patbillingDetails?$patbillingDetails->flddiscountgroup:'' }} {{ $enpatient && $enpatient->fldclaimcode?'('.$enpatient->fldclaimcode.')':'' }}</td>
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
<div class="pdf-container">
    <div class="table-dental2" style="margin-top: 16px;">
        @if(isset($eachpatbilling) && $eachpatbilling)
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
                @foreach($eachpatbilling as $billing)
                    @php
                        $summaryTotal = 0;
                        $summaryTaxTotal = 0;
                        $summaryDiscountTotal = 0;
                    @endphp

                    @if($billing['details'])
                        @foreach($billing['details'] as $bill_detail)
                            @php
                                $summaryTotal += $bill_detail->fldditemamt;
                                $summaryTaxTotal +=$bill_detail->flddiscamt;
                                $summaryDiscountTotal +=$bill_detail->fldtaxamt;

                                $flditemrate +=$bill_detail->fldditemamt;
                                $flddiscountamt +=$bill_detail->flddiscamt;
                                $flditemtax +=$bill_detail->fldtaxamt;
                            @endphp
                        @endforeach
                        <tr>
                            <td>{{$billing['category']}}</td>
                            <td>{{\App\Utils\Helpers::numberFormat($summaryDiscountTotal)}}</td>
                            <td>{{\App\Utils\Helpers::numberFormat($summaryTaxTotal)}}</td>
                            <td>{{\App\Utils\Helpers::numberFormat($summaryTotal)}}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <ul>
        <li>Sub Total: Rs. {{ \App\Utils\Helpers::numberFormat($flditemrate) }}</li>
        <li>Discount: Rs. {{ \App\Utils\Helpers::numberFormat($flddiscountamt) }}</li>
        <li>Total Tax: Rs. {{ \App\Utils\Helpers::numberFormat($flditemtax) }}</li>
        <li>Total Amt: Rs. {{ \App\Utils\Helpers::numberFormat($fldditemamt) }}</li>
        <li>Recv Amt: Rs. {{ $patbillingDetails ? \App\Utils\Helpers::numberFormat($patbillingDetails->fldreceivedamt) : '0' }}</li>
    </ul>

    <strong style="float:left; padding-left: 2rem;padding-top: 1rem; ">In words: {{ $patbillingDetails? ucwords(\App\Utils\Helpers::numberToNepaliWords($patbillingDetails->fldreceivedamt)):'' }} /-</strong>
    <p>
        Remarks: {{ $patbillingDetails?$patbillingDetails->remarks:'' }}
    </p>
    <div style="clear: both"></div>

    <div class="" style="width: 20%; float: left; margin-top: 8%;">
        <p>{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})</p>
        <p>{{ date('Y-m-d H:i:s') }}</p>
    </div>

    <!-- <div class="signaturetitle" style="width: 20%; float: right; margin-top: 8%;">
        <label style="border-top: 1px dashed #000;">Authorized Signature</label>
    </div> -->
</div>

<div style="clear: both"></div>
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
        <table style="width: 70%">

            <tbody>
            <tr>
                <td style="width: 60%;">EncID: {{ $enpatient->fldencounterval }}</td>
            </tr>
            <tr>
                <td style="width: 60%;">
                    Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? $enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast:''}}</td>
            </tr>
            <tr>
                <td style="width: 60%;">Address: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fulladdress:'' }}</td>
            </tr>
            <tr>
                <td style="width: 35%;">Phone No: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptcontact:'' }}</td>
            </tr>
            <tr>
                <td style="width: 35%;">Payment: {{ ucfirst($parent_patbillingDetails?$parent_patbillingDetails->fldbilltype:'') }}</td>
            </tr>
            </tbody>
        </table>
        <table style="width: 30%;float:right; margin-top:-10%;">
            <tbody>
            <tr>
                <td style="width: 35%;">Bill Number: {{ $parent_patbillingDetails?$parent_patbillingDetails->fldbillno:'' }}</td>
            </tr>
            <tr>
                <td style="width: 35%;">Transactions Date: {{ $parent_patbillingDetails->fldtime }}</td>
            </tr>
            <tr>
                <td><img style="width: 50%" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode"/></td>
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
                    @foreach($eachpatbilling_parent as $billing)
                        @php
                            $summaryTotal = 0;
                            $summaryTaxTotal = 0;
                            $summaryDiscountTotal = 0;
                        @endphp

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
                                    <td>{{$bill_detail->flditemname}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->fldtaxamt)}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->flddiscamt)}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->fldditemamt)}}</td>
                                </tr>
                            @endforeach
                        @endif


                    @endforeach
                    </tbody>
                </table>

            @endif
        </div>
        <ul>
            <li>Sub Total: Rs. {{ \App\Utils\Helpers::numberFormat($parent_flditemrate) }}</li>
            <li>Discount: Rs. {{ \App\Utils\Helpers::numberFormat($parent_flddiscountamt) }}</li>
            <li>Total Tax: Rs. {{ \App\Utils\Helpers::numberFormat($parent_flditemtax) }}</li>
            <li>Total Amt: Rs. {{ \App\Utils\Helpers::numberFormat($parent_fldditemamt) }}</li>
            <li>Recv Amt: Rs. {{ $parent_patbillingDetails ? \App\Utils\Helpers::numberFormat($parent_patbillingDetails->fldreceivedamt) : '0' }}</li>
        </ul>

        <strong style="float:left; padding-left: 2rem;padding-top: 1rem; ">In words: {{ $parent_patbillingDetails? ucwords(\App\Utils\Helpers::numberToNepaliWords($parent_patbillingDetails->fldreceivedamt)):'' }} /-</strong>
        <div style="clear: both"></div>

        <div class="" style="width: 20%; float: left; margin-top: 8%;">
            <p>{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})</p>
            <p>{{ date('Y-m-d H:i:s') }}</p>
        </div>


    </div>
@endif

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
