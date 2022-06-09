{{-- <!DOCTYPE html>
<!-- saved from url=(0040)file:///C:/Users/DELL/Downloads/pdf.html -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Invoice for {{ $patbillingDetails->fldbillno??"" }}</title>
</head>
<body>
@php
    $fldbillno = isset($orginalitemdata) ? $orginalitemdata[0]->fldbillno :'';
    $payables = \App\Utils\BillHelpers::getBillPayables($fldbillno);
    $bill = isset($orginalitemdata) ? strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) :'';
@endphp
@if(isset($orginalitemdata) and strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) == 'PHM')

    <style type="text/css">
        @page {
            margin: 4mm 0 4mm;
        }
    </style>
@endif
@if(isset($orginalitemdata) and strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) != 'PHM')

    <style type="text/css">
        @page {
            margin: 24mm 0 11mm;
        }
    </style>

@endif --}}
{{-- <style>


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
    .content-body th:nth-child(2),
    .content-body th:nth-child(3),
    .content-body td:nth-child(3) {
        text-align: left;
    }

    .content-body td,
    .content-body th {
        border: 1px solid #000;
        font-size: 13px;
        text-align: right;
        padding-right: 4px;
    }

    h2, h3, h4, h6 {
        line-height: 0.375rem;
        text-align: center;
    }

    ul {
        float: right;
        padding: 0;
        margin: 0;
    }

    ul li {
        text-align: right;
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

    table {
        width: 100%;
        font-size: 13px;
    }

    .content-body tr td {
        padding: 4px !important;
    }
</style> --}}
{{-- @if(isset($orginalitemdata) and strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) == 'PHM')
    <header>
        <table style="width: 100%;">
            <tbody>
            <tr>
                <td style="width: 15%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100" style="object-fit: cover;"/></td>
                <td style="width:70%;">
                    <h3>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                    <h4>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                    <h4>Pharmacy Unit</h4>
                    <h4>{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                    <h4>Contact No : {{Options::get('system_telephone_no')}}</h4>
                    <h6>INVOICE @if($billCount > 1) (COPY OF ORIGINAL)Print-{{ $billCount-1 }}@endif</h6>
                </td>
                <td style="width: 15%;"></td>
            </tr>
            </tbody>
        </table>
    </header>

@endif --}}
@extends('inpatient::pdf.layout.main')

@section('title')
Invoice for {{ $patbillingDetails->fldbillno??"" }}
@endsection
@section('styles')
<style>
table {
    width: 100%;
    font-size: 13px;
}
</style>
@endsection
@php
    $fldbillno = isset($orginalitemdata) ? $orginalitemdata[0]->fldbillno :'';
    $payables = \App\Utils\BillHelpers::getBillPayables($fldbillno);
    $bill = isset($orginalitemdata) ? strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) :'';
@endphp
@if(isset($orginalitemdata) and strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) == 'PHM')
@section('styles')
    <style type="text/css">
        @page {
            margin: 4mm 0 4mm;
        }
    </style>
@endsection
@endif
@if(isset($orginalitemdata) and strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) != 'PHM')
@section('styles')
    <style type="text/css">
        @page {
            margin: 24mm 0 11mm;
        }
    </style>
@endsection
@endif
@section('content')
<div class="a4">

    {{-- @if(isset($orginalitemdata) && (strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) == 'PHM' || strtoupper(substr($orginalitemdata[0]->fldbillno, 0,5)) == 'TPPHM' || strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) == 'CRE'))
        @php
            $bill_type = Options::get('Pharmacy-Billing-Header');
        @endphp
        @if($bill_type == "header1")
        @include('pdf-header-footer.header1')
        @elseif($bill_type == "header2")
        @include('pdf-header-footer.header2')
        @elseif($bill_type == "header3")
        @include('pdf-header-footer.header3')
        @endif
    @endif

    @if(isset($orginalitemdata) && (strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) == 'CAS' || strtoupper(substr($orginalitemdata[0]->fldbillno, 0,2)) == 'TP' || strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) == 'CRE' || strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) == 'REG'))
        @php
            $bill_type = Options::get('Service-Billing-Header');
        @endphp
        @if($bill_type == "header1")
        @include('pdf-header-footer.header1')
        @elseif($bill_type == "header2")
        @include('pdf-header-footer.header2')
        @elseif($bill_type == "header3")
        @include('pdf-header-footer.header3')
        @endif
    @endif --}}
@php
$bill_type = Options::get('Return-Billing-Header');
$bill_total = Options::get('Return-Billing-Total');

@endphp
@if($bill_type == "header1")
@include('pdf-header-footer.header1')
@elseif($bill_type == "header2")
@include('pdf-header-footer.header2')
@elseif($bill_type == "header3")
@include('pdf-header-footer.header3')
@endif
<div class="main-body">
<div class="pdf-container" style="margin: 0 auto; width: 95%;">
    <h5 class="bill-title">INVOICE @if($billCount > 1) (COPY OF ORIGINAL)Print-{{ $billCount-1 }}@endif</h5>
    <div style="width: 100%;"></div>
    <table style="width: 60%; float: left;">
        <tbody>
        <tr>
            <td>EncID: {{ $enpatient->fldencounterval }}</td>
        </tr>
        <tr>
            <td>
                Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? ucwords(strtolower($enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast)):''}} ({{ $enpatient->fldpatientval }})
            </td>
        </tr>
        <tr>
            <td>Age/Sex: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fldagestyle.'/'.$enpatient->patientInfo->fldptsex:'' }}</td>
        </tr>
        <tr>
            <td>Address: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fulladdress : '' }}</td>
        </tr>
        <tr>
            <td>Phone No: {{ (isset($enpatient->patientInfo)) ? $enpatient->patientInfo->fldptcontact : '' }}</td>
        </tr>
        </tbody>
    </table>
    <table style="width: 40%;float:right;text-align:right;">
        <tbody>
        <tr>
            <td>Pan Number: {{ Options::get('hospital_pan')?Options::get('hospital_pan'):Options::get('hospital_vat') }}</td>
        </tr>
        @if(isset($orginalitemdata) and strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) == 'PHM')
        <tr>
            <td>DDA REG No.: {{Options::get('dda_number')}}</td>
        </tr>
        @endif
        <tr>
            <td>Bill Number: {{ $patbillingDetails?$patbillingDetails->fldbillno:'' }}</td>
        </tr>
        <tr>
            <td>Transactions Date: {{ count($itemdata) ? Helpers::dateToNepali($itemdata[0]->fldtime) :'' }}</td>
        </tr>
        {{-- <tr>
            <td>Invoice Issue Date: {{ $patbillingDetails?Helpers::dateToNepali($patbillingDetails->fldtime):'' }}</td>
        </tr> --}}
        <tr>
            <td>Doctor Name: {{ \App\Utils\BillHelpers::getBillReferals($fldbillno) }}</td>
        </tr>
        <tr>
            <td>Billing Mode: {{ $enpatient->fldbillingmode}}</td>
        </tr>
        @if(isset($orginalitemdata) and strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) == 'PHM')
        <tr>
            <td>Payment: <b>{{ strtoupper($patbillingDetails?$patbillingDetails->payment_mode:'')}}</b></td>
        </tr>
        @endif
        <!-- <tr>
                  {{--
                  <td >Purchaser Pan:</td>
                  --}}
            <td >Payment: <b>{{ ucfirst($patbillingDetails?$patbillingDetails->fldbilltype:'') }}</b></td>
               </tr> -->
        </tbody>
    </table>
    <div style="clear: both"></div>
</div>
<div class="pdf-container" style="margin: 0 auto; width: 95%;">
    <table class="table content-body">
        <thead class="thead-light">
        <tr>
            <th>S/N</th>
            <th>Particulars</th>
            <th style="text-align:right;">QTY</th>
            <th>Rate</th>
            <th>TAX</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($itemdata) and count($itemdata) > 0)
            @foreach($itemdata as $k=>$itd)
                @php
                    $sn = $k+1;
                @endphp
                @if(!is_null($itd->serviceCost))
                    @php
                    $itemname = $itd->serviceCost->fldbillitem;
                    @endphp
                @else
                    @php
                    $itemname = $itd->flditemname;
                    @endphp
                @endif
                <tr>
                    <td>{{$sn}}</td>
                    <td>{{$itemname}}</td>
                    <td style="text-align:right;">{{$itd->flditemqty}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($itd->flditemrate) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat($itd->fldtaxamt) }}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat(($itd->flditemqty*$itd->flditemrate)) }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <table class="table" style="margin-top:8px;">
        <tr>
            <td>
                In words: {{ $patbillingDetails ? ucwords(\App\Utils\Helpers::numberToNepaliWords($patbillingDetails->fldreceivedamt)) : '' }} /-
                <br>
                @if(strtoupper(isset($orginalitemdata) and substr($orginalitemdata[0]->fldbillno, 0,3)) != 'PHM')
                Payment:<b>{{ ucfirst($patbillingDetails?$patbillingDetails->payment_mode:'') }}</b><br>
                @endif
                @if(isset($itemdata) and count($itemdata) > 0)
        Reason:

            <b>{{ strtoupper($itemdata?$itemdata[0]->fldreason:'')}}</b>


        @endif
        <br>
                @if($patbillingDetails)
                    @if($patbillingDetails->fldauthorizedby)
                        Authorized By: {{ $patbillingDetails ? $patbillingDetails->fldauthorizedby : "" }}<br>
                    @endif
                @endif
                @if ($enpatient && $enpatient->fldencounterval)
                    <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode" />
                @endif
                <br>

            </td>
            <td>
                <ul>
                    <li><span>Sub Total:</span><span> {{ \App\Utils\Helpers::numberFormat($patbillingDetails->flditemamt) }}</span></li>
                    <li><span>Discount:</span><span> {{ \App\Utils\Helpers::numberFormat($patbillingDetails->flddiscountamt) }}</span></li>
                    <li><span>Total Tax:</span><span>{{ \App\Utils\Helpers::numberFormat($patbillingDetails->fldtaxamt) }}</span></li>
                    <li><span>Total Amt:</span><span> {{ \App\Utils\Helpers::numberFormat(($patbillingDetails->flditemamt - $patbillingDetails->flddiscountamt + $patbillingDetails->fldtaxamt)) }}</span></li>
                    <li><span>Orginal Amt::</span><span> {{ \App\Utils\Helpers::numberFormat($orginaltotal) }}</span></li>
                    <li><span>Refund Amt::</span><span> {{ \App\Utils\Helpers::numberFormat($patbillingDetails->fldreceivedamt) }}</span></li>
                </ul>
            </td>
        </tr>
    </table>
    @if(isset($orginalitemdata) and strtoupper(substr($orginalitemdata[0]->fldbillno, 0,3)) != 'PHM')
    <h5 style="margin: 0px; margin-top:2%;">Original Bill Detail</h5>
    Bill Number: {{ count($orginalitemdata) ? $orginalitemdata[0]->fldbillno :'' }}
    <br>Transactions Date: {{ count($orginalitemdata) ? Helpers::dateToNepali($orginalitemdata[0]->fldtime) :'' }}

    <div style="clear: both"></div>
</div>
<div class="pdf-container" style="margin: 0 auto; width: 95%;">
    <div style="width: 100%;">
        <table class="table content-body">
            <thead>
            <tr>
                <th>S/N</th>
                <th>Particulars</th>
                <th>Payables</th>
                <th>QTY</th>
                <th>Rate</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($orginalitemdata) and count($orginalitemdata) > 0)
                @foreach($orginalitemdata as $oitem)
                    @if(!is_null($oitem->serviceCost))
                        @php
                        $oitemname = $oitem->serviceCost->fldbillitem;
                        @endphp
                    @else
                        @php
                        $oitemname = $oitem->flditemname;
                        @endphp
                    @endif
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $oitemname }}</td>
                        <td>{{ isset($payables[$oitem->fldid]) ? implode(', ' , $payables[$oitem->fldid]) : '' }}</td>
                        <td>{{ $oitem->flditemqty }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat($oitem->flditemrate) }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(($oitem->flditemqty*$oitem->flditemrate)) }}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <p style="margin: 0;">Total Amount after refund: {{ ($patbillingDetails && isset($orginaltotal)) ? \App\Utils\Helpers::numberFormat(($orginaltotal + $remainingtotal)) : '' }}</p>
        <div style="clear: both"></div>
        <!-- <div class="" style="width: 50%; float: left; margin-top: 2%;"> -->
            {{-- <p>{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }}) <br>
                {{\App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s'))  }}
            </p> --}}




        <!-- </div> -->
    </div>

    @endif
    <div>
    @if($bill_total == "total1")
    @include('pdf-header-footer.total1')
    @elseif($bill_total == "total2")
    @include('pdf-header-footer.total2')
    @endif
    </div>
</div>
</div>
</div>
@endsection
