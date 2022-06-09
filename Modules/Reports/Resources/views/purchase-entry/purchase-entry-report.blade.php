@extends('inpatient::pdf.layout.main')

@section('title')
    Purchase Entry Report
@endsection
@include('pdf-header-footer.hospital-header')
<h4 style="text-align: center;margin-top:2px;">Purchase Entry Report</h4>
@section('content')
{{--    <p align="center" style="margin-left: 210px;"><strong>Purchase Entry Report</strong></p>--}}
    @php
        $totalamount = 0;
    @endphp

    <style>
    .table tr th,.table tr td{
    text-align: left;
    }

    .text-right{
        text-align: right !important;
    }

    .total-data li{
        width:350px;

    }
    .total-data li span:nth-child(1){
        text-align: left;
        width:150px;

    }
    .total-data li span:nth-child(2){
        text-align: right;
        width:200px;

    }
    </style>
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p>Datetime: {{ (isset($entries[0])) ? $entries[0]->fldexpiry : '' }}</p>
        </div>
    </div>
    <table class="table content-body">
        <thead>
        <tr>
            <th>SNo.</th>
            <th>Supplier Name</th>
            <th>Purchase Date</th>
            <th>Category</th>
            <th>Item Name</th>
            <th>Batch</th>
            <th>Expiry</th>
            <th>P.E Ref No</th>
            <th>GRN No.</th>
            {{-- <th>Quantity</th> --}}
            <th>Pur. Qty</th>
            <th>Qty Bon</th>
            <th>Total Qty</th>
            <th class="text-right"> NetCost</th>
            <th class="text-right">VAT AMT</th>
            <th class="text-right">CCost</th>
            <th class="text-right">Sub Total</th>
            <th class="text-right">Total Amount</th>
            {{-- <th>Sell Rate</th> --}}
            <th>Department</th>
        </tr>
        </thead>
        <tbody>
        @if($entries)
            @php
                $i = 1;
            @endphp
            @php
                $total_dsc = 0;
                $total_totl = 0;
                $total_vat = 0;
                $total_cc = 0;
                $total_subtotal = 0;
            @endphp
            @foreach ($entries as $entry)
                @foreach ($entry->purchase as $purchase)
                    <tr>
                        {{-- @php
                            $totalamount += ($entry->fldqty * $entry->fldsellpr)
                        @endphp --}}
                        <td>{{ $i }}</td>
                        <td>{{ $purchase->fldsuppname }}</td>
                        <td>{{ \Carbon\Carbon::parse($purchase->fldpurdate)->format('Y-m-d') ?? null }}</td>
                        <td>{{ $entry->fldcategory }}</td>
                        <td>{{ $entry->fldstockid }}</td>
                        <td>{{ $entry->fldbatch }}</td>
                        <td>{{ \Carbon\Carbon::parse($entry->fldexpiry)->format('Y-m-d')}}</td>
                        <td>{{ $purchase->fldreference ?? null }}</td>
                        <td>{{ $purchase->fldgrnno ?? null }}</td>
                        {{-- <td>{{ $entry->fldqty }}</td> --}}
                        <td>{{ $purchase->fldtotalqty }}</td>
                        <td>{{ ($purchase->fldqtybonus) ? $purchase->fldqtybonus : "0" }}</td>
                        <td>{{ $purchase->fldtotalqty + (($purchase->fldqtybonus) ? $purchase->fldqtybonus : 0) }}</td>
                        {{-- <td>{{ $entry->fldsellpr }}</td> --}}
                        <td class="text-right">Rs. {{ ($purchase->fldnetcost) ?  \App\Utils\Helpers::numberFormat(($purchase->fldnetcost)) : "0.00" }}</td>
                        @php
                            $vamt = ($purchase->fldvatamt) ?  $purchase->fldvatamt : 0.00;
                        @endphp
                        <td class="text-right">Rs. {{\App\Utils\Helpers::numberFormat($vamt)}}</td>
                        @php
                            $carcost = ($purchase->fldcarcost) ?  $purchase->fldcarcost : 0.00;
                            $totcost = ($purchase->fldtotalcost) ?  $purchase->fldtotalcost : 0.00;
                            $total = (($purchase->fldnetcost) ?  $purchase->fldnetcost : 0) * $entry->fldtotalqty;
                            $subtotal = $totcost - $vamt;
                        @endphp
                        <td class="text-right">{{ \App\Utils\Helpers::numberFormat($carcost) }}</td>
                        <td class="text-right">Rs. {{  \App\Utils\Helpers::numberFormat(($subtotal)) }}</td>
                        <td class="text-right">Rs. {{  \App\Utils\Helpers::numberFormat(($totcost + $carcost)) }}</td>
                        <td>{{ \App\Utils\Helpers::getDepartmentFromComp($entry->fldcomp) }}</td>
                        @php
                            $total_dsc += ($purchase->fldcasdisc) ?  $purchase->fldcasdisc : 0.00;
                            $total_totl += ($totcost + $carcost);
                            $total_cc += $carcost;
                            $total_vat += ($purchase->fldvatamt) ? $purchase->fldvatamt : 0.00;
                            $total_subtotal += $subtotal;
                        @endphp
                    </tr>
                    @php
                        ++$i;
                    @endphp
                @endforeach
            @endforeach
        @endif
        </tbody>
    </table>
<br>
<ul class="total-data">
    <li><span>Sub Total: </span> <span>{{  \App\Utils\Helpers::numberFormat(($total_subtotal)) }}</span></li>
    <li><span>Total Vat Amt: </span> <span>{{  \App\Utils\Helpers::numberFormat(($total_vat)) }}</span></li>
    <li><span>Total Discount: </span> <span>{{  \App\Utils\Helpers::numberFormat(($total_dsc)) }}</span></li>
    <li><span>Total Carry Cost:</span> <span>Rs. {{  \App\Utils\Helpers::numberFormat(($total_cc)) }}</span></li>
    @php
        $totaftergroupdisc = $total_subtotal + $total_vat + $total_cc - (($total_dsc) ? $total_dsc : 0);
    @endphp
    <li><span>Total Amount: </span> <span>{{  \App\Utils\Helpers::numberFormat(($totaftergroupdisc)) }}</span></li>
    {{-- <li>Total Amount: </span><span>{{  \App\Utils\Helpers::numberFormat($totalamount) }} </span></li>
    <li><span>VAT Amount:</span></li>
    <li><span>Discount Amount: </span>  </li> --}}
</ul>

@endsection
