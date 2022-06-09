@php
$chaargedamt = $sumresult->sum('flditemamt') + $sumresult->sum('fldtaxamt') - $sumresult->sum('flddiscountamt');
@endphp

<table style="width: 100%;    border: 1px solid #a79c9c;    border-top: 0;">
    <thead>
        <tr>
            <th>Deposit: Rs. {{ \App\Utils\Helpers::numberFormat($totaldep[0]->totaldepo)  }}</th>
            <th>Deposit refund: Rs. {{ \App\Utils\Helpers::numberFormat($totalrefdep[0]->totalrefund)  }}</th>
            <th>Amount: Rs. {{ \App\Utils\Helpers::numberFormat($sumresult->sum('flditemamt'))  }}</th>
            <th>Tax: Rs. {{ \App\Utils\Helpers::numberFormat($sumresult->sum('fldtaxamt')) }}</th>
            <th>Discount: Rs. {{ \App\Utils\Helpers::numberFormat($sumresult->sum('flddiscountamt')) }}</th>
            <th>Received: Rs. {{ \App\Utils\Helpers::numberFormat($sumresult->sum('fldreceivedamt'))  }}</th>
        </tr>
    </thead>
</table>