<!DOCTYPE html>
<html>
<head>
    <title>PURCHASE RETURN(CREDIT NOTE)</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">

        body{
            margin:5px;
            font-family: 'Segoe UI', Roboto;
        }

        @page {
            size: A4;
            margin: 10px 5px;;
        }
        table {
            display: table;
            border-collapse: separate;
            box-sizing: border-box;
            text-indent: initial;
            white-space: normal;
            line-height: normal;
            font-weight: normal;
            font-size: small;
            font-style: normal;
            color: -internal-quirk-inherit;
            text-align: start;
            border-spacing: 2px;
            border-color: grey;
            font-variant: normal;
        }

        .content-body tr td {
            padding: 5px;
        }

        .content-body {
            border-collapse: collapse;
        }

        p {
            margin: 4px 0;
        }

        #total {
            width: 40%;
        }
        #total tr td {
            text-align: right;
        }
    </style>

</head>
<body>
@include('pdf-header-footer.hospital-header')
<h5 style="text-align: center;margin-top:2px;">Purchase Return(Credit Note) Report</h5>
<main>

<table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 200px;">
            <p><b>Purchase Return(Credit Note) Reference No:</b> {{ $fldnewreference }}
                @if(($billCount) > 1)
                    (COPY OF ORIGINAL) Print-{{ $billCount-1 }}
                @endif
            </p>
            @php $currentDatetime = Carbon\Carbon::now()->format('m/d/Y H:i'); @endphp
            <p><b>Date:</b> {{ $currentDatetime }}</p>
        </td>
    </tr>
    </tbody>
</table>

<table style="width: 100%;" border="1px" class="content-body">
    <thead>
    <tr>

        <th class="tittle-th">SNo</th>
        <th class="tittle-th">Category</th>
        <th class="tittle-th">Particulars</th>
        <th class="tittle-th">Batch</th>
        <th class="tittle-th">Expiry</th>
        <th class="tittle-th">CasDisc</th>
        <th class="tittle-th">Carry Cost</th>
        <th class="tittle-th">Return Reward QTY</th>
        <th class="tittle-th">Total Returned QTY</th>
        <th class="tittle-th">Cost</th>
        <th class="tittle-th">Supplier Name</th>
        <th class="tittle-th">RefNo</th>
    </tr>
    </thead>
    <tbody>
    @php
        $total_disc_amt = 0;
        $total_totl = 0;
         $total_vat = 0;
        $total_cc = 0;
        $total_subtotal = 0;
        $total_cash_dis = 0;
    @endphp
        @forelse($stockreturns as $stockreturn)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ ($stockreturn->Entry) ? $stockreturn->Entry->fldcategory : ''}}</td>
                <td>{{ $stockreturn->fldstockid }}</td>
                @php $fldbatch = ($stockreturn->Entry) ? $stockreturn->Entry->fldbatch : ''; @endphp
                <td>{{ $fldbatch }}</td>
                @php $fldexpiry = ($stockreturn->Entry && $stockreturn->Entry->fldexpiry != '' && $stockreturn->Entry->fldexpiry != NULL) ? Carbon\Carbon::parse($stockreturn->Entry->fldexpiry)->format('m/d/Y') : ''; @endphp
                <td>{{ $fldexpiry }}</td>
                <td>{{ (($stockreturn->fldcashdisc) ?  \App\Utils\Helpers::numberFormat(($stockreturn->fldcashdisc)) : 0.00)}}</td>
                <td>{{ (($stockreturn->fldcarcost) ?  \App\Utils\Helpers::numberFormat(($stockreturn->fldcarcost)) : 0.00) }}</td>
                <td>{{ $stockreturn->fldbonusretqty }}</td>
                <td>{{ $stockreturn->fldqty }}</td>
                <td>{{ (($stockreturn->fldcost) ?  \App\Utils\Helpers::numberFormat(($stockreturn->fldcost)) : 0.00) }}</td>
                <td>{{ $stockreturn->fldsuppname }}</td>
                <td>{{ $stockreturn->fldreference }}</td>
            </tr>
            @php
                $total_subtotal += $stockreturn->fldcost;
                   $total_cc += $stockreturn->fldcarcost;
                   $total_vat += $stockreturn->fldvatamt;
                   $total_disc_amt += $stockreturn->flddisamt;
                   $total_cash_dis += $stockreturn->fldcashdisc;

            @endphp
        @empty
        @endforelse
    @php
        $total_totl = $total_subtotal + $total_cc - $total_cash_dis -$total_disc_amt + $total_vat;
    @endphp
    </tbody>
</table>
    <div style="width: 50%;float: left;">
        <p>Sub Total: Rs. {{  \App\Utils\Helpers::numberFormat(($total_subtotal)) }}</p>
        <p>Total Vat Amt: Rs. {{  \App\Utils\Helpers::numberFormat(($total_vat)) }}</p>
        <p>Total Discount: Rs. {{  \App\Utils\Helpers::numberFormat(($total_cash_dis)) }}</p>
        <p>Group Discount: Rs. {{  \App\Utils\Helpers::numberFormat(($total_disc_amt)) }}</p>
        <p>Total Carry Cost: Rs. {{  \App\Utils\Helpers::numberFormat(($total_cc)) }}</p>
        <p>Total Amount: Rs. {{  \App\Utils\Helpers::numberFormat(($total_totl)) }}</p>
    </div>


@php
        $signatures = Helpers::getSignature('bedoccupancy');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>

