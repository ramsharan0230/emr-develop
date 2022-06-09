<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Report</title>
    <style>
        body {
            font-size: small;
        }
        @page {
            margin-top: 35mm;
            margin-bottom:30mm;
        }
        @media print {
            .main-body {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
        table {
            page-break-inside: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }


        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            bottom: 0;
        }

        h2, p {
            display: flex;
            justify-content: center;
        }
        .heading {
            display:flex;
            justify-content: space-around;
            align-items: center;
        }
        .heading-2 , .heading-2 td{
            width: 80px;
        }
        .custom-height {
            height: 60px;
        }
        .boldtext {
            font-weight: "600";
        }
        .right-align {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="main-body">
        <h2>खरिद खाता</h2>
        <p>(नियम २३ को उपनियम (१) को खण्ड  (ज) संग सम्बन्धित ) </p>
        <table width="100%">
            <tr>
                <th colspan="15">
                    <div class="heading">
                        <span>करदाता दर्ता नं (PAN) : {{ Options::get('hospital_pan')}}</span>
                        <span>करदाताको नाम: {{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</span>
                        <span>साल: {{$year}}</span>
                        <span>महिना: {{$month}}</span>
                    </div>
                </th>
            </tr>
            <tr class="heading-2">
                <td>मिति</td>
                <td>बीजक नं.</td>
                <td>आपूर्तिकर्ताको नाम</td>
                <td>आपूर्तिकर्ताको स्थायी लेखा नम्बर</td>
                <td>कर छुट हुने वस्तु वा सेवाको खरिद / पैठारी मूल्य (रु)</td>
                <td>करयोग्य खरिद (पूंजीगत बाहेक) मूल्य (रु)</td>
                <td>करयोग्य खरिद (पूंजीगत बाहेक) मूल्य कर (रु)</td>
                <td class="right-align">जम्मा खरिद मूल्य (रु)</td>
            </tr>



            @if(isset($purchasedata) and count($purchasedata) > 0)
                @php
                $taxable_amount = 0;
                $non_taxable_amount = 0;
                $tax = 0;
                $total = 0;
                @endphp
                @foreach($purchasedata as $report)
                    @php
                        $taxable_amount += $report->Taxable_Amount;
                        $non_taxable_amount += $report->NonTaxable_Amount;
                        $tax += $report->Tax;
                        $total += $report->NonTaxable_Amount + $report->Taxable_Amount + $report->Tax;

                        $nepalidate = \App\Utils\Helpers::dateEngToNepdash(date('Y-m-d',strtotime($report->Pur_Date)));
                        $pan_no = Helpers::getSuppliersinfo($report->Supplier_Name);
                    @endphp
                    <tr>
                        <td>{{$nepalidate->year}}-{{$nepalidate->month}}-{{$nepalidate->date}}</td>
                        <td>{{$report->Purchase_Reference}}</td>
                        <td>{{$report->Supplier_Name}}</td>
                        @if(isset($pan_no->fldpanno) && isset($pan_no->fldvatno))
                            <td>{{$pan_no->fldpanno}}/{{$pan_no->fldvatno}}</td>
                        @elseif((isset($pan_no->fldpanno)))
                            <td>{{$pan_no->fldpanno}}</td>
                        @elseif((isset($pan_no->fldvatno)))
                            <td>{{$pan_no->fldvatno}}</td>
                        @else
                            <td></td>
                        @endif
                        <td>{{ \App\Utils\Helpers::numberFormat(($report->NonTaxable_Amount))}}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(($report->Taxable_Amount))}}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(($report->Tax))}}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(($report->NonTaxable_Amount + $report->Taxable_Amount + $report->Tax))}}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td></td>
                        <td>{{ \App\Utils\Helpers::numberFormat(($taxable_amount))}}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(($non_taxable_amount))}}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(($tax))}}</td>
                        <td>{{ \App\Utils\Helpers::numberFormat(($total))}}</td>
                    </tr>
            @endif
        </table>
    </div>
</body>
</html>
