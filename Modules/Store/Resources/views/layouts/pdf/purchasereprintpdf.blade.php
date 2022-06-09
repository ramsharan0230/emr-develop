<!DOCTYPE html>
<html>
<head>
    <title>PURCHASE ENTRY</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">

        @font-face {
            font-family: "Preeti";
            font-style: normal;
            font-weight: normal;
            src: url({{asset("assets/fonts/PREETI.TTF")}}) format('truetype');
        }
        @font-face {
            font-family: "Preeti Bold";
            font-style: normal;
            font-weight: bold;
            src: url({{asset("assets/fonts/PREETI.TTF")}}) format('truetype');
        }

        .unicode{
            font-family: Preeti, "Preeti Bold";
            font-size: 18px;
            line-height: 1.8;
        }

        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }
    </style>

</head>
<body>

@include('pdf-header-footer.header-footer')
<main>

<table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 200px;">
            <p>SUPPLIER: OPENING STOCK </p>
            <p>INVOICE: XXXXX</p>
        </td>
        <td style="width: 200px;">
            <p>PAYMENT: Credit Payment</p>
            <p>REFERENCE: {{ $fldreference }}</p>
        </td>
    </tr>
    </tbody>
</table>

<table style="width: 100%;" border="1px" class="content-body">
    <thead>
    <tr>

        <th class="tittle-th">SNo</th>
        <th class="tittle-th unicode">lhG;L vftf kfgf g</th>
        <th class="tittle-th unicode">lhG;L alu{s/0f ;+s]t g</th>
        <th class="tittle-th unicode">;fdfg sf] gfd</th>
        <th class="tittle-th unicode">:k]lslkms];g</th>
        <th class="tittle-th unicode">k/Ldfb</th>
        <th class="tittle-th unicode">klt{ PsfO{ b/</th>
        <th class="tittle-th unicode">e' c s/ ktL{ PsfO{</th>
        <th class="tittle-th unicode">PsfO{ d'No</th>
        <th class="tittle-th unicode">hDdf</th>
        <th class="tittle-th unicode">s}okmot</th>
    </tr>
    </thead>
    <tbody>
        @forelse($purchaseentries as $k=>$purchaseentry)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td></td>
                <td></td>
                <td>{{ $purchaseentry->fldstockid }}</td>
                <td></td>
                <td>{{ $purchaseentry->fldtotalqty }}</td>
                <td>Rs. {{ $purchaseentry->flsuppcost }}</td>
                <td></td>
                <td></td>
                    <td>Rs. {{ $purchaseentry->fldtotalqty * $purchaseentry->flsuppcost }}</td>
                <td></td>
            </tr>
        @empty
        @endforelse
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Sub Total</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Rs. {{ $subtotalcost }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Extra Charge/Discount</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Rs. {{ $extrachargediscount }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Tax</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Rs. {{ $tax }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Grand Total</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Rs. {{ $grandtotalcost }}</td>
            <td></td>
        </tr>
    </tbody>
</table>
<table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 200px;">
            <p>admin, {{ Carbon\Carbon::now()->format('m/d/Y') }} </p>
        </td>
    </tr>
    </tbody>
</table>
@php
        $signatures = Helpers::getSignature('bedoccupancy');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>

