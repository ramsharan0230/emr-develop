<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<4;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>Chirayu National Hospital and Medical Institute</b></th>
        </tr>
        <tr>
            @for($i=1;$i<4;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>Basundhara Kathmanudu, Nepal</b></th>
        </tr>
        <tr>
            @for($i=1;$i<4;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>Contact No: </b></th>
        </tr>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>From date:</b></th>
            <th colspan="2">{{ $eng_from_date }}({{ $nep_from_date }})</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="2"><b>To date:</b></th>
            <th colspan="2">{{ $eng_to_date }}({{ $nep_to_date }})</th>
        </tr>
        <tr><th></th></tr>
        <tr>
            <th rowspan="2">SNo.</th>
            <th rowspan="2">Department Name</th>
            <th colspan="2">Cash Item Amount</th>
            <th colspan="2">Free Con. Amount</th>
            <th colspan="2">SVR Tax</th>
            <th rowspan="2">Item Amount</th>
            <th colspan="2">Credit Amount</th>
            <th colspan="2">Refund Amount</th>
            <th colspan="2">RF SVR Tax</th>
            <th rowspan="2">Net Amount</th>
        </tr>
        <tr>
            <th>OP</th>
            <th>IP</th>
            <th>OP</th>
            <th>IP</th>
            <th>OP</th>
            <th>IP</th>
            <th>OP</th>
            <th>IP</th>
            <th>OP</th>
            <th>IP</th>
            <th>OP</th>
            <th>IP</th>
        </tr>
    </thead>
    @php
    $item_amount = [];
    $net_amount = [];
    @endphp
    <tbody>
        @if(count($reports) > 0)
        @foreach($reports as $key => $report)
        @php
        $item_amount[$key] = $report->OP_Cash_Amount + $report->IP_Cash_Amount - $report->OP_Discount_Amount - $report->IP_Discount_Amount;
        $net_amount[$key] = $item_amount[$key] + $report->OP_Return_Amount + $report->IP_Return_Amount - ($report->OP_Return_Tax_Amount + $report->IP_Return_Tax_Amount);
        $net_amount[$key] = $report->OP_Credit_Amount + $report->IP_Credit_Amount +  $item_amount[$key] + $report->OP_Return_Amount + $report->IP_Return_Amount - ($report->OP_Return_Tax_Amount + $report->IP_Return_Tax_Amount);
        @endphp
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $report->dept }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Cash_Amount) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Cash_Amount) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Discount_Amount) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Discount_Amount) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Tax_Amount) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Tax_Amount) }}</td>
            <td><b>{{ \App\Utils\Helpers::numberFormat($item_amount[$key]) }}</b></td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Credit_Amount) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Credit_Amount) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Return_Amount) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Return_Amount) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->OP_Return_Tax_Amount) }}</td>
            <td>{{ \App\Utils\Helpers::numberFormat($report->IP_Return_Tax_Amount) }}</td>
            <td><b>{{ \App\Utils\Helpers::numberFormat($net_amount[$key]) }}</b></td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="17">No records found.</td>
        </tr>
        @endif
        @if(count($reports) > 0)
            <tr>
                <td colspan="2">Total Amount:</td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Cash_Amount'))}} </td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Cash_Amount'))  }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Discount_Amount')) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Discount_Amount')) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Tax_Amount')) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Tax_Amount')) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat(array_sum($item_amount)) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Credit_Amount')) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Credit_Amount')) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Return_Amount')) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Return_Amount')) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('OP_Return_Tax_Amount')) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($reports->sum('IP_Return_Tax_Amount')) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat(array_sum($net_amount)) }}</td>
            </tr>
        @endif
    </tbody>
</table>
<table>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<6;$i++)
        <th></th>
        @endfor
        <th colspan="2"><b>OP Collection</b></th>
        <th colspan="2">{{\App\Utils\Helpers::numberFormat(($reports->sum('OP_Cash_Amount') + $reports->sum('OP_Return_Amount') - $reports->sum('OP_Return_Tax_Amount')- $reports->sum('OP_Discount_Amount')))}}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
        <th></th>
        @endfor
        <th colspan="2"><b>IP Collection</b></th>
        <th colspan="2">{{\App\Utils\Helpers::numberFormat(($reports->sum('IP_Cash_Amount') + $reports->sum('IP_Return_Amount') - $reports->sum('IP_Return_Tax_Amount') - $reports->sum('IP_Discount_Amount')))}}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
        <th></th>
        @endfor
        <th colspan="2"><b>Net Realized Revenue</b></th>
        <th colspan="2">{{\App\Utils\Helpers::numberFormat(array_sum($net_amount) - $reports->sum('OP_Credit_Amount') - $reports->sum('IP_Credit_Amount'))}}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
        <th></th>
        @endfor
        <th colspan="2"><b>Deposit Only</b></th>
        <th colspan="2">{{\App\Utils\Helpers::numberFormat($deposit)}}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
        <th></th>
        @endfor
        <th colspan="2"><b>Deposit Refund</b></th>
        <th colspan="2">{{\App\Utils\Helpers::numberFormat($deposit_refund)}}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
        <th></th>
        @endfor
        <th colspan="2"><b>Adjustment from Previous Deposit</b></th>
        <th colspan="2">{{\App\Utils\Helpers::numberFormat($Previous_Deposit_of_Discharge_Clearence)}}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
        <th></th>
        @endfor
        <th colspan="2"><b>Amount Received while Discharge Patient</b></th>
        <th colspan="2">{{\App\Utils\Helpers::numberFormat($Received_Deposit_of_Discharge_Clearence)}}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
        <th></th>
        @endfor
        <th colspan="2"><b>Total Collection</b></th>
        <th colspan="2">{{\App\Utils\Helpers::numberFormat($rev_amount_sum)}}</th>
    </tr>
</table>
