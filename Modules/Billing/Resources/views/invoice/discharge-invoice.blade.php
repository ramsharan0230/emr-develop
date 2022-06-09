{{-- <!DOCTYPE html>
<!-- saved from url=(0040)file:///C:/Users/DELL/Downloads/pdf.html -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title> @if(isset($discharge_invoice_title)) {{ $discharge_invoice_title }}  @else Invoice @endif   {{ (isset($patbillingDetails[0])) ?$patbillingDetails[0]->fldbillno:'' }} </title>


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
            height: auto;
            margin-top: 5px;
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
</head>
<body> --}}
@extends('inpatient::pdf.layout.main')

@section('title')
@if(isset($discharge_invoice_title)) {{ $discharge_invoice_title }}  @else Invoice @endif   {{ (isset($patbillingDetails[0])) ?$patbillingDetails[0]->fldbillno:'' }}
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

<div class="main-body">
<div class="pdf-container" style="margin: 0 auto; width: 95%;">
    <h5 class="bill-title">
    @if(isset($discharge_invoice_title)) {{ $discharge_invoice_title }}  @else INVOICE @endif
    </h5>
{{--@php--}}
{{--    $totaldepositsum =0;--}}
{{--@endphp--}}
    <!-- total deposit -->
    @if($depositDetail)
        @php
            $totaldepositsum = 0;
        @endphp
        @foreach($depositDetail as $k => $deposit)
            @php
                $totaldepositsum += $deposit->fldreceivedamt;
            @endphp
        @endforeach
    @endif
    <div>
        <table style="width: 60%; float: left;">
            <tbody>
            <tr>
                <td>EncID: {{ $enpatient->fldencounterval }}</td>
            </tr>
            <tr>
                <td>Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? ucwords(strtolower($enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast)):''}} ({{ $enpatient->fldpatientval }})</td>
            </tr>
            <tr>
                <td>Address:{{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fulladdress:'' }}</td>
            </tr>
            <tr>
                <td>Contact: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptcontact:'' }}</td>
            </tr>
            <tr>
                <td>Age/Sex: {{  ($enpatient && $enpatient->patientInfo) ? $enpatient->patientInfo->fldagestyle : '' }}/{{  ($enpatient && $enpatient->patientInfo) ? $enpatient->patientInfo->fldptsex : '' }}</td>
            </tr>
            @php
            $beddetail = [];
            if(isset($enpatient->patientInfo))
                $beddetail = \App\Utils\Helpers::getLatestBedDetail($enpatient->fldencounterval);

            @endphp
            <tr>
                <td>Ward Name: {{($beddetail) ? $beddetail['flddept'] : ''}}</td>
            </tr>
            <tr>
                <td>Roomtype: {{($beddetail) ? $beddetail['fldbedtype'] : '' }}</td>
            </tr>
            </tbody>
        </table>
        <table style="width: 40%;float:right;text-align:right;">
            <tbody>
            <tr>
                <td>Pan No:{{ Options::get('hospital_pan')?Options::get('hospital_pan'):Options::get('hospital_vat') }}</td>
            </tr>
            <tr>
                <td>IPT No: {{ $enpatient->fldencounterval }}</td>
            </tr>
            <tr>
                <td>Invoice No: {{ $patbillingDetails->fldbillno??"" }}</td>
            </tr>
            <tr>
                <td>Bill Date and time: {{ $patbillingDetails? \App\Utils\Helpers::dateToNepali($patbillingDetails->fldtime) :'' }} </td>
            </tr>
            <tr>
                <td>Admission Date:
{{--                    {{ (isset($enpatient)) ?$enpatient->flddoa:'' }} --}}
                    {{ (isset($enpatient)) ?  \App\Utils\Helpers::dateToNepali($enpatient->flddoa) :'' }}</td>
            </tr>
            <tr>
                <td>Discharge Date:
{{--                    {{ (isset($enpatient)) ?$enpatient->flddod:'' }} --}}
                    {{ (isset($enpatient)) ?  \App\Utils\Helpers::dateToNepali($enpatient->flddod)  :'' }}</td>
            </tr>
            <tr>
                <td>Billing Mode: {{ $enpatient && $enpatient->fldbillingmode?$enpatient->fldbillingmode:'' }} {{ $enpatient && $enpatient->fldclaimcode?'('.$enpatient->fldclaimcode.')':'' }}</td>
            </tr>
            <tr>
                <td>Discount Mode: {{ (isset($discount_mode) && isset($discount_mode->discount_mode)) ? $discount_mode->discount_mode:'' }}</td>
            </tr>

            <tr>
                <td>Referral Doctor: {{ isset($referable_doctor) ?  $referable_doctor :'' }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table-dental2" style="margin-top: 16px;">
    @php
            $summaryTotal = $summaryGrossTotal = $summaryTaxTotal = $summaryDiscountTotal = $flditemrate =$flddiscountamt =$flditemtax =$fldditemamt = $nettotal = 0;
            $billnumber = isset($pagefrom)?$pagefrom:'';
            @endphp
        @if(isset($eachpatbilling) && $eachpatbilling)
            <table class="table content-body">
                <thead class="thead-light">
                <tr>
                    <th>SN</th>
                    <th>Particulars</th>
                    @if(isset($patbillingDetails) && ($billnumber != 'pharmacy')  )
                    <th>Payable</th>
                    @endif
                    <th>QTY</th>
                    <th>Rate</th>
                    <th>Transaction Date</th>

                    <th>Total</th>

                </tr>
                </thead>
                <tbody>

                    @php
                    $count = 1;
                    @endphp
                @foreach($eachpatbilling as  $t =>$billing)

                    @if($billing['details'])

                        @foreach($billing['details'] as $b => $bill_detail)
                            @php

                                $summaryDiscountTotal +=$bill_detail->flddiscamt;
                                $summaryTaxTotal +=$bill_detail->fldtaxamt;

                                $summaryGrossTotal += $bill_detail->flditemqty* $bill_detail->flditemrate;
                            @endphp
                            <tr>
                                @if(isset($bill_detail->fldbillitem) and $bill_detail->fldbillitem !='')
                                    @php
                                        $itemname = $bill_detail->fldbillitem;
                                    @endphp
                                @else
                                    @php
                                        $itemname = $bill_detail->flditemname;
                                    @endphp
                                @endif

                                <td>{{$count}}</td>
                                <td>{{$itemname}}</td>
{{--                                <td>{!! Helpers::getNameByUsername($bill_detail->fldpayto)!!}</td>--}}
@if(isset($patbillingDetails) && ($billnumber != 'pharmacy')  )
                                <td>{{ isset($payables[$bill_detail->fldid]) ? implode(', ' , $payables[$bill_detail->fldid]) : '' }}</td>
                                @endif
{{--                                <td>{{ isset($payables[$bill_detail->fldid]) ? implode(', ' , $payables[$bill_detail->fldid]) : '' }}</td>--}}
                                <td>{{$bill_detail->flditemqty}}</td>
                                <td>{{\App\Utils\Helpers::numberFormat($bill_detail->flditemrate)}}</td>
{{--                                <td>{{\App\Utils\Helpers::numberFormat($bill_detail->fldtaxamt)}}</td>--}}
                                <td>{{$bill_detail->fldordtime}}</td>
                                <td>{{\App\Utils\Helpers::numberFormat($bill_detail->fldditemamt)}}</td>
                            </tr>
                            @php
                            $count++;
                            @endphp
                        @endforeach

                    @endif
                @endforeach

                </tbody>
            </table>
        @endif
    </div>

    <table style="width: 100%;">
        <tbody>
        @if(isset($eachpatbilling) && $eachpatbilling)
        @php
        $nettotal = $summaryGrossTotal-$summaryDiscountTotal+$summaryTaxTotal;
        @endphp
        <tr>
            <td><strong>In words: {{ $patbillingDetails? ucwords(\App\Utils\Helpers::numberToNepaliWords($patbillingDetails->fldreceivedamt)):'' }}/-</strong>
                <p>
                    Remarks: {{ $patbillingDetails?$patbillingDetails->remarks:'' }}
                </p>
                @if ($enpatient && $enpatient->fldencounterval)
                    <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode" />
                @endif
            </td>
            <td rowspan="3">
            <ul>
                    <li><span>Gross Amount:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($summaryGrossTotal) }}</span></li>
                    <li><span>Discount:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($summaryDiscountTotal) }}</span></li>
                    <li><span>Tax:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($summaryTaxTotal) }}</span></li>
                    <hr>
                    <li><span>Net Total:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($nettotal) }}</span></li>
                    <li><span>Previous Deposit:</span><span>Rs. {{ $patbillingDetails ? \App\Utils\Helpers::numberFormat($patbillingDetails->fldprevdeposit) : '0' }}</span></li>

                    <li><span>Recv Amt:</span><span>{{ $patbillingDetails ? \App\Utils\Helpers::numberFormat($patbillingDetails->fldreceivedamt) : '0' }}</span></li>
                </ul>
            </td>
        </tr>
        @endif
        <tr>
            <td></td>
        </tr>
        <tr>
            <td>
                {{-- <p>USER:{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})
                    </br>{{ \App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s')) }}</p> --}}
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
            </td>
        </tr>
        </tbody>
    </table>

    <h3 style="margin: 4px; text-decoration: underline;">Deposit Detail</h3>
    <table class="table content-body" style="width: 50%;;">
        <thead class="thead-light">
        <tr>
            <th>SN</th>
            <th>Bill No.</th>
            <th>Date</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody>
        @if($depositDetail)
            @php
                $totalsum = 0;
            @endphp
            @foreach($depositDetail as $k => $deposit)
                @php
                    $totalsum += $deposit->fldreceivedamt;
                @endphp
                <tr>
                    <td>{{$k+1}}</td>
                    <td>{{$deposit->fldbillno}}</td>
                    <td>{{$deposit->fldtime ? \App\Utils\Helpers::dateToNepali($deposit->fldtime) : ''}}</td>
                    <td>{{\App\Utils\Helpers::numberFormat($deposit->fldreceivedamt)}}</td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td><b>Total</b></td>
            <td colspan="3" style="text-align: center;"><b>{{\App\Utils\Helpers::numberFormat($totalsum)}}</b></td>
        </tr>
        </tbody>
    </table>
</div>
</div>
</div>
@endsection
