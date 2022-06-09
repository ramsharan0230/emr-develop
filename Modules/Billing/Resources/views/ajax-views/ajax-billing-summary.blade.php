
@php
    $chaargedamt = $sumresult->sum('flditemamt') + $sumresult->sum('fldtaxamt') - $sumresult->sum('flddiscountamt');
@endphp

<tr>
    <td>Deposit: Rs. {{ \App\Utils\Helpers::numberFormat($totaldep[0]->totaldepo)  }}</td>
    <td>Deposit refund: Rs. {{ \App\Utils\Helpers::numberFormat($totalrefdep[0]->totalrefund)  }}</td>
    <td>Amount: Rs. {{ \App\Utils\Helpers::numberFormat($sumresult->sum('flditemamt'))  }}</td>
    <td>Tax: Rs. {{ \App\Utils\Helpers::numberFormat($sumresult->sum('fldtaxamt')) }}</td>
    <td>Discount: Rs. {{ \App\Utils\Helpers::numberFormat($sumresult->sum('flddiscountamt')) }}</td>
    <td>Received: Rs. {{ \App\Utils\Helpers::numberFormat($sumresult->sum('fldreceivedamt'))  }}</td>
</tr>

