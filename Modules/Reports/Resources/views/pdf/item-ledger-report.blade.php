<!DOCTYPE html>
<html>
<head>
    <title>Item Ledger Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .content-body tr td {
            padding: 5px;
        }

        p {
            margin: 4px 0;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body td, .content-body th {
            border: 1px solid #ddd;
        }

        .content-body {
            font-size: 12px;
        }

        .ledger-header-filter{
            list-style: none;
        }
    </style>

</head>
<body>
@include('pdf-header-footer.header-footer')
<main>
    <div class="row">
        <table style="width: 100%;" >
            <tr>
                <td style="width: 10%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100"/></td>
                <td style="width:80%;">
                    <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                    <h4 style="text-align: center;margin-top:4px;margin-bottom:0;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                    <h4 style="text-align: center;margin-top:4px;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                    <h4 style="text-align: center;">Item Ledger Report</h4>
                </td>
                <td style="width: 10%;"></td>
            </tr>
        </table>
    </div>
    <table style="width:100%">
        <tr>
            <td>
                Date:{{$from_date}}  TO
                {{$to_date}}</td>
            <td style="text-align: right">{{$medicine_name}} </td>

        </tr>
    </table>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead>
        <tr>
            <th class="tittle-th">SNo</th>

            <th class="tittle-th">Date</th>
            <th class="tittle-th">Description</th>
            <th class="tittle-th">Ref No</th>
            <th class="tittle-th">Rec/Pur Qty</th>
            <th class="tittle-th">Qty Issue</th>
            <th class="tittle-th">Bal Qty</th>
            <th class="tittle-th">Rate</th>
            <th class="tittle-th">Rec/Pur Amt</th>
            <th class="tittle-th">Issue Amt</th>
            <th class="tittle-th">Bal Val</th>
            <th class="tittle-th">Expiry</th>
            <th class="tittle-th">Batch</th>


        </tr>
        </thead>
        <tbody>
        @if(isset($opening_sql) )
            @if(isset($opening_sql[0]) )
                @php
                    $initialBalQty = $opening_sql[0]->BalanceQty;
                    $initialPurQty = $opening_sql[0]->PurQty;
                    $initialQtyIssue = $opening_sql[0]->QtyIssue;
                    $initialRate = $opening_sql[0]->Rate;
                    $initialBalAmt = $opening_sql[0]->BalAmt;
                @endphp
            @else
                @php
                $initialBalQty =0;
                $initialPurQty = 0;
                $initialQtyIssue = 0;
                $initialRate = 0;
                $initialBalAmt = 0;
                @endphp
            @endif

            <tr>
                <td></td>
                <td></td>
                <td>Opening</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ abs($initialBalQty) }}</td>
                <td>{{\App\Utils\Helpers::numberFormat($initialRate)}}</td>
                <td>{{$initialPurQty}}</td>
                <td>{{$initialQtyIssue}}</td>
                <td>{{ \App\Utils\Helpers::numberFormat(($initialBalAmt ))}}</td>
                <td></td>
                <td></td>
            </tr>
        @endif
        @php
            $totalIssueAmt = 0;
               $totalPurAmt = 0;
               $lastRate = 0;
               $balanceQty = 0;
        @endphp
        @if(isset($calculation_sql) and count($calculation_sql) > 0)
            @foreach($calculation_sql as $k=>$resultd)
                @php
                    $expiry  = \DB::table('tblentry')->select('fldexpiry')->where('fldstockid',$medicine_name)->first();
                    $expirydate = (isset($expiry) and !is_null($expiry)) ? $expiry->fldexpiry : "";
                    $sn = $k+1;
                @endphp

                <tr>
                    <td>{{$sn}}</td>
                    <td>{{$resultd->datetime}}</td>
                    <td>{{$resultd->description}}</td>
                    <td>{{$resultd->reference}}</td>
                    <td>{{$resultd->PurQty}}</td>
                    <td>{{$resultd->QtyIssue}}</td>
                    <td>{{$resultd->BalanceQty}}</td>
                    <td>{{\App\Utils\Helpers::numberFormat($resultd->Rate)}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat(($resultd->PurAmt))}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat(($resultd->IssueAmt))}}</td>
                    <td>{{ \App\Utils\Helpers::numberFormat(($resultd->BalAmt))}}</td>
                    <td>{{$expirydate}}</td>
                    <td>{{$resultd->batch}}</td>
                </tr>
                @php
                    $totalIssueAmt += $resultd->IssueAmt;
                        $totalPurAmt += $resultd->PurAmt;
                        $lastRate = $resultd->Rate;
                        $balanceQty = $resultd->BalanceQty;
                @endphp
            @endforeach
        @endif
        <tr>
            <td></td>
            <td></td>
            <td>Closing</td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$balanceQty}}</td>
            <td>{{\App\Utils\Helpers::numberFormat($lastRate)}}</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($totalPurAmt))}}</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($totalIssueAmt))}}</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($lastRate*$balanceQty))}}</td>
            <td></td>
            <td></td>

        </tr>

        </tbody>
    </table>
    @php
        $signatures = Helpers::getSignature('billing-report');
    @endphp
    @include('frontend.common.footer-signature-pdf')
</main>
</body>
</html>
