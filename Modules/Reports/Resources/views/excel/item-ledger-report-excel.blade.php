<table>
    <thead>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th><b>From date:</b></th>
        <th colspan="2">{{ $from }} </th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th><b>To date:</b></th>
        <th colspan="2">{{ $to }} </th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th><b>Printed At:</b>{{ date('Y-m-d H:i:s') }}</th>

    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th><b>Printed By: </b> {{\App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid)}}</th>

    </tr>

    <tr><th></th></tr>
    <tr>
        <td>S.No</td>
        <td>Date</td>
        <td>Description</td>
        <td>RefNo.</td>
        <td>Rec/Pur QTY</td>
        <td>Qty Issue</td>
        <td>Bal Qty</td>
        <td>Rate</td>
        <td>Rec/Pur Amt</td>
        <td>Issue Amt</td>
        <td>Bal Value</td>
        <td>Expiry</td>
        <td>Batch</td>


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
            <td>{{ $initialBalQty}}</td>
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
                $expiry  = \DB::table('tblentry')->select('fldexpiry')->where('fldstockid',$medicinename)->first();
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
