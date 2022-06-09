@extends('inpatient::pdf.layout.main')
@php
    // dd($depositDetail->toArray());
@endphp
@section('title')
 Invoice
@endsection

@section('content')
<div class="a4 billPreview">
    @if($depositDetail->fldpayitemname == 'Deposit Refund' || $depositDetail->fldpayitemname == 'Pharmacy Deposit Refund')
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
    @else
        @php
        $bill_type = Options::get('Deposit-Billing-Header');
        $bill_total = Options::get('Deposit-Billing-Total');
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
            <h5 class="bill-title">DEPOSIT INVOICE @if($billCount > 1) (COPY OF ORIGINAL)Print-{{ $billCount-1 }}@endif</h5>
            <div style="width: 100%;"></div>
            <table style="width: 60%; float: left;" >
                <tbody>
                <tr>
                    <td>EncID: {{ $encounterinfo->fldencounterval }}</td>
                </tr>
                <tr>
                    <td>Name:  {{ $encounterinfo->patientInfo->fldrankfullname }} ({{ $encounterinfo->fldpatientval }})</td>
                </tr>
                <tr>
                    <td>Age/Sex: {{ (isset($encounterinfo->patientInfo)) ? $encounterinfo->patientInfo->fldagestyle.'/'.$encounterinfo->patientInfo->fldptsex:'' }}</td>
                </tr>
                <tr>
                    <td>Address:  {{ $encounterinfo->patientInfo->fulladdress ?? "" }}</td>
                </tr>
                <tr>
                    <td>Phone no.:  {{ $encounterinfo->patientInfo->fldptcontact }}</td>
                </tr>
                </tbody>
            </table>
            <table  style="width: 40%;float:right;text-align:right;">
                <tbody>
                <tr>
                    <td>Hospital Pan No: 302628630</td>
                </tr>
                <tr>
                    <td>Bill Number: {{ $depositDetail->fldbillno }}</td>
                </tr>
                <tr>
                    <td>Transactions Date: {{ Helpers::dateToNepali($depositDetail->fldtime) }}</td>
                </tr>
                <tr>
                    <td>Doctor Name: {{ \App\Utils\BillHelpers::getBillReferals($depositDetail->fldbillno) }}</td>
                </tr>
                <tr>
                    <td>Billing Mode: {{ $encounterinfo->fldbillingmode }} </td>
                </tr>

                </tbody>
            </table>
            <div style="clear: both"></div>
        </div>
        <div class="pdf-container" style="margin: 0 auto; width: 95%;">
            <table class="table content-body">
                <thead class="thead-light">
                    <tr>
                        <th>&nbsp;</th>
                        <th>PARTICULAR</th>
                        <th>QTY</th>
                        <th>RATE</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>{{ $depositDetail->fldpayitemname }}</td>
                        <td>1</td>
                        <td>{{ \App\Utils\Helpers::numberFormat($depositDetail->fldreceivedamt) }}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat($depositDetail->fldreceivedamt) }}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table" style="margin-top:8px;">
                <tr>
                    <td>In words:{{ \App\Utils\Helpers::numberToNepaliWords($depositDetail->fldreceivedamt) }}
                    <br>
                   Payment: <b> {{ ($depositDetail->payment_mode !='') ? $depositDetail->payment_mode : $depositDetail->fldbilltype }} </b>
                   <br>
                    @if ($encounterinfo && $encounterinfo->fldencounterval)
                    <img class="bar-code" src="data:image/png;base64,{{DNS1D::getBarcodePNG($encounterinfo->fldencounterval, 'C128') }}" alt="barcode" />
                    @endif
                    {{-- <p>{{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}({{ Auth::guard('admin_frontend')->user()->flduserid }})
                        </br>{{\App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s'))  }}</p> --}}
                    @if($bill_total == "total1")
                    @include('pdf-header-footer.total1')
                    @elseif($bill_total == "total2")
                    @include('pdf-header-footer.total2')
                    @endif
                    </td>
                    <td>
                        <ul>
                            <li><span>Prev Deposit:</span><span>Rs.{{ \App\Utils\Helpers::numberFormat($depositDetail->fldprevdeposit) }}</span></li>
                            <li><span>Discount:</span><span>Rs. 0.00</span></li>
                            <li><span>Total Tax:</span><span>Rs. 0.00</span></li>
                        </ul>

                    </td>
                    <td>
                        <ul style="float: right;">
                            <li><span>Sub Total:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($depositDetail->fldreceivedamt) }}</span></li>
                            <li><span>Discount:</span><span>Rs. 0.00</span></li>
                            <li><span>Total Tax:</span><span>Rs. 0.00</span></li>
                            <li><span>Total Amt:</span><span>Rs. {{ \App\Utils\Helpers::numberFormat($depositDetail->fldreceivedamt) }}</span></li>
                        </ul>


                    </td>
                </tr>
            </table>
            <p class="footer">GET WELL SOON!</p>

        </div>
    </div>
</div>
@endsection
