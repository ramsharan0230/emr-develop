{{-- <!DOCTYPE html>
<!-- saved from url=(0040)file:///C:/Users/DELL/Downloads/pdf.html -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>@if(isset($discharge_invoice_title)) {{ $discharge_invoice_title }}  @else Invoice @endif   {{ (isset($patbillingDetails)) ?$patbillingDetails->fldbillno:'' }} </title>
</head>
<body>
<style>
        @page {
            margin: 24mm 0 25mm;
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
    </style> --}}
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
@extends('inpatient::pdf.layout.main')

@section('title')
@if(isset($discharge_invoice_title)) {{ $discharge_invoice_title }}  @else Invoice @endif   {{ (isset($patbillingDetails)) ?$patbillingDetails->fldbillno:'' }}
@endsection

@section('content')
<div class="a4">
    @php
        $fldbillno = $patbillingDetails?$patbillingDetails->fldbillno:'';
        $payables = \App\Utils\BillHelpers::getBillPayables($fldbillno);
    @endphp

@if(isset($fldbillno) && (strtoupper(substr($fldbillno, 0,3)) == 'CAS' ))
        @php
            $bill_type = Options::get('Discharge-Billing-Header');
        @endphp
        @if($bill_type == "header1")
        @include('pdf-header-footer.header1')
        @elseif($bill_type == "header2")
        @include('pdf-header-footer.header2')
        @elseif($bill_type == "header3")
        @include('pdf-header-footer.header3')
        @endif
@endif
@if(isset($fldbillno) && (strtoupper(substr($fldbillno, 0,3)) == 'DEP' ))
        @php
            $bill_type = Options::get('Deposit-Billing-Header');
        @endphp
        @if($bill_type == "header1")
        @include('pdf-header-footer.header1')
        @elseif($bill_type == "header2")
        @include('pdf-header-footer.header2')
        @elseif($bill_type == "header3")
        @include('pdf-header-footer.header3')
        @endif
@endif

<div class="main-body" >
    <div class="pdf-container" style="margin: 0 auto; width: 95%;">

        <h5 class="bill-title">
            @if(isset($invoice_title))
                {{ strtoupper($invoice_title) }}
            @else
                 INVOICE
            @endif
            {{--@if($billCount > 1) (COPY OF ORIGINAL) Print-{{ $billCount-1 }}@endif--}}
        </h5>
        <div style="width: 100%;"></div>
        <table style="width: 70%; float: left;">

            <tbody>
            <tr>
                <td>EncID: {{ $enpatient->fldencounterval }}</td>
            </tr>
            <tr>
                <td style="width: 60%;">
                    Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? ucwords(strtolower($enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast)):''}} ({{ $enpatient->fldpatientval }})</td>
            </tr>
            <tr>
                <td style="width: 60%;">Address: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fulladdress:'' }}</td>
            </tr>
            <tr>
                <td style="width: 35%;">Phone No: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptcontact:'' }}</td>
            </tr>
            <tr>
                <td style="width: 35%;">Payment: <b> {{ ucfirst($patbillingDetails?$patbillingDetails->fldbilltype:'') }}</b></td>
            </tr>
            </tbody>
        </table>
        <table style="width: 30%;float:right;">
            <tbody>
            <tr>
                <td style="width: 35%;">Bill Number: {{ $patbillingDetails?$patbillingDetails->fldbillno:'' }}</td>
            </tr>
            <tr>
                <td style="width: 35%;">Transactions Date: {{ $patbillingDetails ? \App\Utils\Helpers::dateToNepali($patbillingDetails->fldtime):'' }}</td>
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
    <div class="pdf-container">
        <div class="table-dental2" style="margin-top: 16px;">
            @if(isset($eachpatbilling) && $eachpatbilling)
                @foreach($eachpatbilling as $billing)
                    <h4>
                        {{$billing['category']}}
                    </h4>

                    <table class="table content-body">
                        <thead class="thead-light">
                        <tr>
                            <th style="text-align: left; width: 40%">Particulars</th>
                            <th style="text-align: left; width: 10%">QTY</th>
                            <th style="text-align: left; width: 10%">Svr Tax</th>
                            <th style="text-align: left; width: 10%">Discount</th>
                            <th style="text-align: left; width: 10%">Total</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if($billing['details'])
                            @foreach($billing['details'] as $bill_detail)
                                <tr>
                                    <td>{{$bill_detail->flditemname}}</td>
                                    <td>{{$bill_detail->flditemqty}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->fldtaxamt)}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->flddiscamt)}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->fldditemamt)}}</td>
                                    @php
                                        $flditemrate +=$bill_detail->fldditemamt;
                                        $flddiscountamt +=$bill_detail->flddiscamt;
                                        $flditemtax +=$bill_detail->fldtaxamt;
                                    @endphp
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                @endforeach
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

        <div class="" style="width: 20%; float: left; margin-top: 8%;padding-left:10px;">
            {{-- <p>{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})</p>
            <p>{{ date('Y-m-d H:i:s') }}</p> --}}
            @if(isset($fldbillno) && (strtoupper(substr($fldbillno, 0,3)) == 'CAS' ))
                    @php
                        $bill_total = Options::get('Discharge-Billing-Total');
                    @endphp
                    @if($bill_total == "total1")
                    @include('pdf-header-footer.total1')
                    @elseif($bill_total == "total2")
                    @include('pdf-header-footer.total2')
                    @endif
            @endif
            @if(isset($fldbillno) && (strtoupper(substr($fldbillno, 0,3)) == 'DEP' ))
                    @php
                        $bill_total = Options::get('Deposit-Billing-Total');
                    @endphp
                    @if($bill_total == "total1")
                    @include('pdf-header-footer.total1')
                    @elseif($bill_total == "total2")
                    @include('pdf-header-footer.total2')
                    @endif
            @endif
        </div>

    </div>
</div>
</div>
@endsection
@push('after-script')
<script src="{{asset('assets/js/jquery-3.4.1.min.js')}}"></script>
<script>
    $(document).ready(function () {
        setTimeout(function () {
            window.print();
        }, 3000);
    });
</script>
@if(isset($success) && $success === false)
    <script>
        alert('No data available.');
        window.close();
    </script>
@endif
@endpush