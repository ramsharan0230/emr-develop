{{-- <!DOCTYPE html>
<!-- saved from url=(0040)file:///C:/Users/DELL/Downloads/pdf.html -->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>@if(isset($discharge_invoice_title)) {{ $discharge_invoice_title }}  @else Invoice @endif  for {{ $patbillingDetails->fldbillno??"" }}</title>

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
            margin: 0;
            padding: 0;
        }

        .bar-code {
            width: 200px;
            height: auto;
            margin-top: 8px;
            margin-bottom: 8px;
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

        p {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body> --}}
@extends('inpatient::pdf.layout.main')

@section('title')
@if(isset($discharge_invoice_title)) {{ $discharge_invoice_title }}  @else Invoice @endif  for {{ $patbillingDetails->fldbillno??"" }}
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
            $bill_type = Options::get('Return-Billing-Header');
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
                Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? ucwords(strtolower($enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast)):''}} ({{ $enpatient->fldpatientval }})</td>
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
            <td>Bill Number: {{ $patbillingDetails?$patbillingDetails->fldbillno:'' }}</td>
        </tr>
        <tr>
            <td>Transactions Date: {{ $patbillingDetails ? \App\Utils\Helpers::dateToNepali($patbillingDetails->fldtime) :'' }}</td>
        </tr>
        <tr>
            <td>Doctor Name (Referral): {{ isset($referable_doctor) ? $referable_doctor :''  }}</td>
        </tr>

        <tr>
            <td>{{ $enpatient && $enpatient->fldbillingmode?$enpatient->fldbillingmode:'' }} {{ $enpatient && $enpatient->fldclaimcode?'('.$enpatient->fldclaimcode.')':'' }}</td>
        </tr>
        <tr>
            <td>Discount Mode: {{ (isset($discount_mode) && isset($discount_mode->discount_mode)) ? $discount_mode->discount_mode:'' }}</td>
        </tr>
        <tr>
            <td>Payment: <b>{{ (isset($patbillingDetails->payment_mode) && $patbillingDetails->payment_mode !='') ? strtoupper($patbillingDetails->payment_mode) : ""}}</b></td>
        </tr>
        </tbody>
    </table>
    <div style="clear: both"></div>
</div>
@php
$flditemrate = $fldditemamt = $flddiscountamt = $flditemtax = $grossotal = $nettotal=  0;
$billnumber = isset($pagefrom)?$pagefrom:'';
@endphp
<div class="pdf-container" style="margin: 0 auto; width: 95%;">
    <div class="table-dental2" style="margin-top: 16px;">
        @if(isset($eachpatbilling) && $eachpatbilling)
            @foreach($eachpatbilling as $billing)
                <h4 class="billing-category">
                    {{$billing['category']}}
                </h4>

                <table class="table content-body">
                    <thead class="thead-light">
                    <tr>
                        <th>Particulars</th>
                       @if(isset($patbillingDetails) && ($billnumber != 'pharmacy')  )
                        <th>Payables</th>
                        @endif
                        <th>Qty</th>
                        <th>Rate</th>

                        <th>Total</th>

                    </tr>
                    </thead>
                    <tbody>
                    @if($billing['details'])
                    @foreach($billing['details'] as $bill_detail)

                            @if(!is_null($bill_detail->serviceCost))
                                @php
                                    $itemname = $bill_detail->serviceCost->fldbillitem;
                                @endphp
                            @else
                                @php
                                    $itemname = $bill_detail->flditemname;
                                @endphp
                            @endif
                            <tr>
                                <td>{{$itemname}}</td>
                                @if(isset($patbillingDetails) && ($billnumber != 'pharmacy')  )
                                <td>{{ isset($payables[$bill_detail->fldid]) ? implode(', ' , $payables[$bill_detail->fldid]) : '' }}</td>
                                @endif
{{--                                <td>{!! Helpers::getNameByUsername($bill_detail->fldpayto)!!}</td>--}}
{{--                                <td>{{ isset($payables[$bill_detail->fldid]) ? implode(', ' , $payables[$bill_detail->fldid]) : '' }}</td>--}}
{{--                                <td>{{\App\Utils\Helpers::numberFormat($bill_detail->fldtaxamt)}}</td>--}}
                                <td>{{$bill_detail->flditemqty}}</td>
                                <td>{{\App\Utils\Helpers::numberFormat($bill_detail->flditemrate)}}</td>
                                <td>{{\App\Utils\Helpers::numberFormat($bill_detail->fldditemamt)}}</td>
                                @php
                                    $fldditemamt +=$bill_detail->fldditemamt;
                                    $flddiscountamt +=$bill_detail->flddiscamt;
                                    $flditemtax +=$bill_detail->fldtaxamt;
                                    $grossotal += $bill_detail->flditemqty* $bill_detail->flditemrate;

                                @endphp
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>


            @endforeach
        @endif
    </div>

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



    <table class="table">
        @if(isset($eachpatbilling) && $eachpatbilling)
        @php
        $nettotal = $grossotal-$flddiscountamt+$flditemtax
        @endphp
            <tr>
                <td><strong>In words: {{ $patbillingDetails? ucwords(\App\Utils\Helpers::numberToNepaliWords($patbillingDetails->fldreceivedamt)):'' }}/-</strong></td>
                <td rowspan="3">
                    <ul>
                        <li><span>Gross Amount:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($grossotal) }}</span></li>
                        <li><span>Discount:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($flddiscountamt) }}</span></li>
                        <li><span>Tax:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($flditemtax) }}</span></li>
                        <hr>
                        <li><span>Net Total:</span><span>Rs. {{\App\Utils\Helpers::numberFormat($nettotal) }}</span></li>
                        <li><span>Previous Deposit:</span><span>Rs. {{ $patbillingDetails ? \App\Utils\Helpers::numberFormat($patbillingDetails->fldprevdeposit) : '0' }}</span></li>
{{--                        <li><span>Deposit:</span><span>Rs. {{\App\Utils\Helpers::numberFormat($previousDeposit)}}</span></li>--}}
                        <li><span>Recv Amt: </span><span>Rs. {{ $patbillingDetails ? \App\Utils\Helpers::numberFormat($patbillingDetails->fldreceivedamt) : '0' }}</span></li>
                        <li><span>To be Paid:</span><span>Rs. {{ (($patbillingDetails) and $patbillingDetails->fldcurdeposit < 0) ? \App\Utils\Helpers::numberFormat(abs($patbillingDetails->fldcurdeposit)) : '0' }}</span></li>
                    </ul>
                </td>
            </tr>
        @endif
        <tr>
            <td>
                <p>
                    Remarks: {{ $patbillingDetails?$patbillingDetails->remarks:'' }}
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode"/>
            </td>
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
                                $bill_total = Options::get('Return-Billing-Total');
                            @endphp
                            @if($bill_total == "total1")
                            @include('pdf-header-footer.total1')
                            @elseif($bill_total == "total2")
                            @include('pdf-header-footer.total2')
                            @endif
                    @endif
            </td>
        </tr>
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
                    <td>{{$deposit->fldtime ? \App\Utils\Helpers::dateToNepali($deposit->fldtime) :''}}</td>
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

{{-- Parent billing --}}
@if(isset($eachpatbilling_parent) && $eachpatbilling_parent)
    <div class="clearfix" style="page-break-after: always;"></div>
    <div class="pdf-container" style="margin: 0 auto; width: 95%;">

        <h5 style="text-align: center">
            @if(isset($invoice_title))
                {{ strtoupper($invoice_title) }}
            @else
                INVOICE
            @endif
            @if($billCount > 1) (COPY OF ORIGINAL) Print-{{ $parent_billCount-1 }}@endif
        </h5>
        <div style="width: 100%;"></div>
        <table style="width: 60%;text-align:left;">

            <tbody>
            <tr>
                <td>EncID: {{ $enpatient->fldencounterval }}</td>
            </tr>
            <tr>
                <td>
                    Name: {{ Options::get('system_patient_rank')  == 1 && (isset($enpatient)) && (isset($enpatient->fldrank) ) ?$enpatient->fldrank:''}} {{isset($enpatient->patientInfo) ? $enpatient->patientInfo->fldptnamefir . ' '. $enpatient->patientInfo->fldmidname . ' '. $enpatient->patientInfo->fldptnamelast:''}}</td>
            </tr>
            <tr>
                <td>Address: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fulladdress:'' }}</td>
            </tr>
            <tr>
                <td>Phone No: {{ (isset($enpatient->patientInfo)) ?$enpatient->patientInfo->fldptcontact:'' }}</td>
            </tr>
            <tr>
                <td>Payment: {{ ucfirst($parent_patbillingDetails?$parent_patbillingDetails->fldbilltype:'') }}</td>
            </tr>
            </tbody>
        </table>
        <table style="width: 40%;float:right;text-align:right;">
            <tbody>
            <tr>
                <td>Pan Number: {{ Options::get('hospital_pan')?Options::get('hospital_pan'):Options::get('hospital_vat') }}</td>
            </tr>
            <tr>
                <td>Bill Number: {{ $parent_patbillingDetails?$parent_patbillingDetails->fldbillno:'' }}</td>
            </tr>
            <tr>
                <td>Transactions Date: {{ $parent_patbillingDetails->fldtime ? \App\Utils\Helpers::dateToNepali($parent_patbillingDetails->fldtime) :'' }}</td>
            </tr>
            <tr>
                <td>Doctor Name (Referal):</td>
            </tr>

            </tbody>
        </table>
    </div>
    @php
        $parent_flditemrate = $parent_fldditemamt = $parent_flddiscountamt = $parent_flditemtax = 0;
    @endphp
    <div class="pdf-container" style="margin: 0 auto; width: 95%;">
        <div class="table-dental2" style="margin-top: 16px;">
            @if(isset($eachpatbilling_parent) && $eachpatbilling_parent)
                @foreach($eachpatbilling_parent as $billing)
                    <h4 class="billing-category">
                        {{$billing['category']}}
                    </h4>

                    <table class="table content-body">
                        <thead class="thead-light">
                        <tr>
                            <th>Particulars</th>
                            <th>Svr Tax</th>
                            <th>Discount</th>
                            <th>Total</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if($billing['details'])
                            @foreach($billing['details'] as $bill_detail)
                                <tr>
                                    <td>{{$bill_detail->flditemname}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->fldtaxamt)}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->flddiscamt)}}</td>
                                    <td>{{\App\Utils\Helpers::numberFormat($bill_detail->fldditemamt)}}</td>
                                    @php
                                        $parent_flditemrate +=$bill_detail->fldditemamt;
                                        $parent_flddiscountamt +=$bill_detail->flddiscamt;
                                        $parent_flditemtax +=$bill_detail->fldtaxamt;
                                    @endphp
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                @endforeach
            @endif
        </div>

        <table class="table">
            <tr>
                <td>

                    <strong>In words: {{ $parent_patbillingDetails? ucwords(\App\Utils\Helpers::numberToNepaliWords($parent_patbillingDetails->fldreceivedamt)):'' }} /-</strong>

                    <div>
                        <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($enpatient->fldencounterval, 'C128') }}" alt="barcode"/>
                    </div>
                    <div class="">
                        <p>{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})
                            <br>{{ date('Y-m-d H:i:s') }}
                        </p>
                    </div>

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


    </div>
    @if(!empty($depositDetail))
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
                        <td>{{$deposit->fldtime}}</td>
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
    @endif
@endif
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
@endpush