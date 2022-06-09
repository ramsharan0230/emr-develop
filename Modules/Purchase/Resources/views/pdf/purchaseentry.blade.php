@extends('inpatient::pdf.layout.main')

@section('title')
Purchase Entry
@endsection

@section('report_type')
Purchase Entry
@endsection

@section('content')
<style>
    @page {
        margin: 0;
    }

    #heading tr td {
        width: 33%;
    }

    p {
        margin: 5px;
    }

    .logo {
        width: 100px;
        height: 100px;
        object-fit: contain;
    }

    .body-details tr td, .body-details tr th {
        padding: 3px;
    }
</style>

@php
    $report_format = Options::get('report_format');
@endphp

<!-- Private Purchase entry Report  -->
@if($report_format == 'Default' || $report_format == '')
<div class="privatePUReport">
    <table style="width: 100%;" id="heading">
        <tr>
            <!-- \App\Utils\Helpers::dateToNepali($purchaseBillDetails->fldpurdate) -->
            <td colspan="1">Date: {{ isset($purchaseBillDetails) ? \Carbon\Carbon::parse($purchaseBillDetails->fldpurdate)->format('Y-m-d') :'' }} </td>
            <td style="text-align: center;" colspan="1"><p>Reference: {{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldreference : "" }}@if(($billCount) > 1)
                    (COPY OF ORIGINAL) Print-{{ $billCount-1 }}
                    @endif</p></td>
            <td colspan="1" style="text-align: right;">Payment: {{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldpurtype : "" }}</td>
        </tr>
        <tr>
            <td colspan="1">Supplier: {{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldsuppname : "" }}</td>
            <td colspan="1"></td>
            <td colspan="1"></td>
        </tr>
        <tr>
            <td colspan="1">Invoice: {{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldbillno : "" }}
        </td>
            <td colspan="1"></td>
            <td colspan="1"></td>
        </tr>
    </table>
    <table class="table content-body body-details">
        <thead>
            <tr>
                <td>&nbsp;</td>
                <td>Category</td>
                <td>Generic</td>
                <td>Brand</td>
                <td>Batch</td>
                <td>Expiry</td>
                <td>CasDisc</td>
                <td>Purchased Qty</td>
                <td>Qty Bon</td>
                <td>Total Qty</td>
                <td>NetCost</td>
                <td>VAT AMT</td>
                <td>CCost</td>
                <td>Sub Total</td>
                <td>Total Amount</td>
            </tr>
        </thead>
        @if($purchaseBillDetails)
        <tbody>
            @php
                $total_dsc = 0;
                $total_totl = 0;
                // $total_vat = 0;
                if(isset($purchaseBillDetails->fldtotalvat) && $purchaseBillDetails->fldtotalvat> 0){
                    $total_vat = ($purchaseBillDetails->fldtotalvat) ? $purchaseBillDetails->fldtotalvat : 0;

                }else{
                    $total_vat = (isset($purchaseBillDetails->fldtotaltax)) ? $purchaseBillDetails->fldtotaltax : 0;
                }
                $total_cc = 0;
                $total_subtotal = 0;
            @endphp
            @if(isset($purchaseEntries))
            @foreach($purchaseEntries as $entry)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $entry->fldcategory }}</td>
                @if ($entry->fldcategory == "Medicines")
                    <td>{{ (isset($entry->medbrand)) ? $entry->medbrand->flddrug : "" }}</td>
                    <td>{{ (isset($entry->medbrand)) ? $entry->medbrand->fldbrand : "" }}</td>
                @elseif ($entry->fldcategory == "Surgicals")
                    <td>{{ (isset($entry->surgbrand)) ? $entry->surgbrand->fldsurgid : "" }}</td>
                    <td>{{ (isset($entry->surgbrand)) ? $entry->surgbrand->fldbrand : "" }}</td>
                @else
                    <td>{{ (isset($entry->extrabrand)) ? $entry->extrabrand->fldextraid : "" }}</td>
                    <td>{{ (isset($entry->extrabrand)) ? $entry->extrabrand->fldbrand : "" }}</td>
                @endif
                <td>{{ $entry->Entry->fldbatch }}</td>
                <td>{{ $entry->Entry->fldexpiry }}</td>
                <td>Rs. {{ ($entry->fldcasdisc) ? $entry->fldcasdisc : "0.00" }}</td>
                <td>{{ $entry->fldtotalqty }}</td>
                <td>{{ ($entry->fldqtybonus) ? $entry->fldqtybonus : "0" }}</td>
                <td>{{ $entry->fldtotalqty + (($entry->fldqtybonus) ? $entry->fldqtybonus : 0) }}</td>
                <td>Rs. {{ ($entry->fldnetcost) ?  \App\Utils\Helpers::numberFormat(($entry->fldnetcost)) : "0.00" }}</td>
                @php
                    $vamt = ($entry->fldvatamt) ?  $entry->fldvatamt : 0.00;
                    $invcasdisc =  ($entry->fldcasdisc) ?  $entry->fldcasdisc : 0.00 ;
                @endphp
                <td>Rs. {{\App\Utils\Helpers::numberFormat($vamt)}}</td>
                @php
                    $carcost = ($entry->fldcarcost) ?  ($entry->fldcarcost) : 0.00;
                    $totcost = ($entry->fldtotalcost) ?  ($entry->fldtotalcost) : 0.00;
                    $total = $entry->fldnetcost * $entry->fldtotalqty;
                    $subtotal = $totcost - $vamt;
                @endphp
                <td>{{ \App\Utils\Helpers::numberFormat($carcost) }}</td>
                <td>Rs. {{  \App\Utils\Helpers::numberFormat(($total)) }}</td>
                <td>Rs. {{  \App\Utils\Helpers::numberFormat(($total + $carcost - $invcasdisc)) }}</td>
                @php
                    $total_dsc += ($entry->fldcasdisc) ?  $entry->fldcasdisc: 0.00;
                    $total_totl += ($totcost + $carcost);
                    $total_cc += $carcost;
                    // $total_vat += ($entry->fldvatamt) ? $entry->fldvatamt : 0.00;
                    $total_subtotal += $total;
                @endphp
            </tr>
            @endforeach
            @endif
        </tbody>
        @endif
    </table>
    @if($purchaseBillDetails)
    <div style="width: 30%;float: right;">
        <p>Sub Total: Rs. {{  \App\Utils\Helpers::numberFormat(($total_subtotal)) }}</p>
        <p>Total Vat Amt: Rs. {{  \App\Utils\Helpers::numberFormat(($total_vat)) }}</p>
        <p>Total Discount: Rs. {{  \App\Utils\Helpers::numberFormat(($total_dsc)) }}</p>
        <p>Group Discount: Rs. {{  \App\Utils\Helpers::numberFormat((isset($purchaseBillDetails->fldlastdisc)? $purchaseBillDetails->fldlastdisc : 0 )) }}</p>
        @if($total_cc > 0 )
        <p>Total Carry Cost: Rs. {{  \App\Utils\Helpers::numberFormat(($total_cc)) }}</p>
        @else
        <p>Total Carry Cost: Rs. {{  \App\Utils\Helpers::numberFormat(($purchaseBillDetails->cccharge)) }}</p>
        @endif
        @php
        $cccharge = 0;
        if($total_cc > 0 ){
            $cccharge = $total_cc;
        }else{
            $cccharge = $purchaseBillDetails->cccharge;
        }
            $totaftergroupdisc = $total_subtotal + $total_vat + $cccharge - (($purchaseBillDetails->fldlastdisc) ? $purchaseBillDetails->fldlastdisc : 0) - (($total_dsc) ? $total_dsc : 0);

        @endphp
        @if($total_cc < 0 )
        <p>Total Amount: Rs. {{  \App\Utils\Helpers::numberFormat(($totaftergroupdisc)) }}</p>
        @else
        <p>Total Amount: Rs. {{  \App\Utils\Helpers::numberFormat(($totaftergroupdisc)) }}</p>
        @endif

    </div>
    @endif
</div>
@endif

<!-- Goverment Purchase entry report  -->
@if($report_format == 'Government')
<div class="govPUReport">
    <header>
        <table style="width: 100%">
            <tr>
                <td style="width: 20%;">
                    <div class="logo border">
                        <img src="../assets/images/nepalgov.png" alt="logo" height="100" width="100">
                    </div>
                </td>
                <td style="width: 60%; text-align: center;">
                    <h2>Nepal Government</h2>
                    <h3>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                    <h5>{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h5>
                </td>
                <td style="width: 20%;"></td>
            </tr>
        </table>
    </header>
    <table style="width: 100%;" id="heading">
        <tr>
            <td>Supplier: {{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldsuppname : "" }}</td>
            <td style="text-align: right;">Payment: {{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldpurtype : "" }}</td>
        </tr>
        <tr>
            <td>Invoice: {{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldbillno : "" }}</td>
            <td style="text-align: right;">Reference: {{ (isset($purchaseBillDetails)) ? $purchaseBillDetails->fldreference : "" }}</td>
        </tr>
    </table>
    <table class="table content-body body-details">
        <thead>
            <tr>
                <th>SNo.</th>
                <th>खरिद आदेश  हस्तान्‍तरण  फारम नं</th>
                <th>जिन्सी बर्गिकरण संकेत नं</th>
                <th>जिन्सी खाता पाना नं</th>
                <th>सामान  को नाम </th>
                <th>स्पेसिफिकेशन</th>
                <th>सामान को पहिचान नं</th>
                <th>मोडल नं</th>
                <th>एकाई</th>
                <th>परिमाण</th>
                <th>पर्ति एकाई दर</th>
                <th>जम्मा मु. अ. कर बहेक</th>
                <th>मु. अ. कर</th>
                <th>सामन को जम्मा मुल्य</th>
                <th>आन्य खर्च</th>
                <th>आन्य खर्च समेत जम्मा रकम</th>
                <th>कैफियत</th>
            </tr>
        </thead>
        @if($purchaseBillDetails)
        <tbody>
            @php
                $total_dsc = 0;
                $total_totl = 0;
                // $total_vat = 0;
                if(isset($purchaseBillDetails->fldtotalvat) && $purchaseBillDetails->fldtotalvat> 0){
                    $total_vat = ($purchaseBillDetails->fldtotalvat) ? $purchaseBillDetails->fldtotalvat : 0;

                }else{
                    $total_vat = (isset($purchaseBillDetails->fldtotaltax)) ? $purchaseBillDetails->fldtotaltax : 0;
                }
                $total_cc = 0;
                $total_subtotal = 0;
            @endphp
            @if(isset($purchasereportdata))
            @foreach($purchasereportdata as $entry)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td></td>
                <td>{{$entry->fldstockno}}</td>
                <td></td>
                <td>{{$entry->generic}}</td>
                <td>{{ $entry->fldbrand }}</td>
                <td></td>
                <td></td>
                <td>{{ $entry->fldvolunit}}</td>
                <td>{{ $entry->fldtotalqty }}</td>
                <td>{{ ($entry->fldnetcost) ?  \App\Utils\Helpers::numberFormat(($entry->fldnetcost)) : "0.00" }}</td>
                <td>{{\App\Utils\Helpers::numberFormat($entry->fldtotalcost)}}</td>
                @php
                    $vamt = ($entry->fldvatamt) ? ($entry->fldvatamt) : 0.00;
                    $invcasdisc =  ($entry->fldcasdisc) ?  ($entry->fldcasdisc) : 0.00 ;
                @endphp
                <td>{{\App\Utils\Helpers::numberFormat($vamt)}}</td>
                @php
                    $carcost = ($entry->fldcarcost) ?  ($entry->fldcarcost) : 0.00;
                    $totcost = ($entry->fldtotalcost) ?  ($entry->fldtotalcost) : 0.00;
                    $total = $entry->fldnetcost * $entry->fldtotalqty;
                    $subtotal = $totcost - $vamt;
                @endphp
                <td>{{\App\Utils\Helpers::numberFormat($vamt+$entry->fldtotalcost)}}</td>
                <td></td>
                <td></td>
                <td></td>
                @php
                    $total_dsc += ($entry->fldcasdisc) ?  \App\Utils\Helpers::numberFormat(($entry->fldcasdisc)) : 0.00;
                    $total_totl += ($totcost + $carcost);
                    $total_cc += $carcost;

                    $total_subtotal += $total;
                @endphp
            </tr>
            @endforeach
            @endif
        </tbody>
        @endif
    </table>
    @if($purchaseBillDetails)
    <div style="width: 30%;float: right;">
        <p>Sub Total: Rs. {{  \App\Utils\Helpers::numberFormat(($total_subtotal)) }}</p>
        <p>Total Vat Amt: Rs. {{  \App\Utils\Helpers::numberFormat(($total_vat)) }}</p>
        <p>Total Discount: Rs. {{  \App\Utils\Helpers::numberFormat(($total_dsc)) }}</p>
        <p>Group Discount: Rs. {{  \App\Utils\Helpers::numberFormat((isset($purchaseBillDetails->fldlastdisc)? $purchaseBillDetails->fldlastdisc : 0 )) }}</p>
        @if($total_cc > 0 )
        <p>Total Carry Cost: Rs. {{  \App\Utils\Helpers::numberFormat(($total_cc)) }}</p>
        @else
        <p>Total Carry Cost: Rs. {{  \App\Utils\Helpers::numberFormat(($purchaseBillDetails->cccharge)) }}</p>
        @endif
        @php
        $cccharge = 0;
        if($total_cc > 0 ){
            $cccharge = $total_cc;
        }else{
            $cccharge = $purchaseBillDetails->cccharge;
        }
            $totaftergroupdisc = $total_subtotal + $total_vat + $cccharge - (($purchaseBillDetails->fldlastdisc) ? $purchaseBillDetails->fldlastdisc : 0) - (($total_dsc) ? $total_dsc : 0);

        @endphp
        @if($total_cc < 0 )
        <p>Total Amount: Rs. {{  \App\Utils\Helpers::numberFormat(($totaftergroupdisc)) }}</p>
        @else
        <p>Total Amount: Rs. {{  \App\Utils\Helpers::numberFormat(($totaftergroupdisc)) }}</p>
        @endif

    </div>
    @endif
</div>
@endif

@endsection
