<table>
    <thead>
    <tr>
        <th></th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b>
        </th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th colspan="8">
            <b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b>
        </th>
    </tr>

    <tr>
        <th>Headwise Ledger Report</th>
    </tr>
    <tr>
        <th>{{isset($from_date) ? \App\Utils\Helpers::dateToNepali($from_date) :''}} To {{isset($to_date) ? \App\Utils\Helpers::dateToNepali($to_date) :''}}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
         <th>Account: {{ $account_name->AccountName }}</th>

    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th>Code: {{ $account_name->account_group ? $account_name->account_group->GroupTree : '' }}</th>
    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th><b>Printed At:</b>{{ date('Y-m-d H:i:s') }}</th>

    </tr>
    <tr>
        @for($i=1;$i<6;$i++)
            <th></th>
        @endfor
        <th><b>Printed By: </b> {{\App\Utils\Helpers::getNameByUsername(\Auth::guard('admin_frontend')->user()->flduserid)}}</th>

    </tr>
    <tr>
        <th>S.No</th>
        <th>DateBS</th>
        <th>DateAd</th>
        <th>V No</th>
        <th>Sub Ledger</th>
        <th>Description</th>
        <th>ChequeNo</th>
        <th>DrAmount</th>
        <th>CrAmount</th>
        <th>Balance</th>
        <th>DrCr</th>

    </tr>
    </thead>
    <tbody>
        @if($transactionData)
        @php
        $balance = 0;
        $currenttype = '';
        $type = '';
        $drAmount = 0;
        $crAmount = 0;

        @endphp

        @foreach($transactionData as $transaction)


<tr>
<td>{{ $loop->iteration }}</td>
    <td>{{ ((isset($transaction['TranDate']) ? \App\Utils\Helpers::dateToNepali($transaction['TranDate']) :'')) }}</td>

    <td>{{ $transaction['TranDate'] }}</td>
    <td class="voucher_details" style="cursor: pointer">{{ $transaction['VoucherNo'] }}</td>
    <td>{{ $transaction['AccountName'] }}</td>
    <td>{{ $transaction['Narration'] }}</td>
    <td>{{ $transaction['ChequeNo'] }}</td>


    @if($transaction['TranAmount'] > 0)
    @php
    $drAmount +=$transaction['TranAmount'];
    @endphp
    @php
    $currenttype = 'DR';
    @endphp
    <td>{{  \App\Utils\Helpers::numberFormat($transaction['TranAmount']) }}</td>
    <td></td>
    <td>
        <!-- balance debit aayecha bhane new bal = dr+dr-cr;
balance credit aayencha cr-dr+cr; -->

        @php
        if(abs($balance) > abs($transaction['TranAmount'])){


        if(($type == 'DR' && $type == 'DR') || ($type == 'CR' && $currenttype == 'CR') ){
        $balance = abs($balance)+abs($transaction['TranAmount']);
        }
        else{
        $balance = abs($balance)-abs($transaction['TranAmount']);
        }
        if($type == ''){
        $currenttype = 'DR';
        }else{
        $currenttype = $type;
        }

        }else{



        if(($type == 'DR' && $currenttype == 'DR') || ($type == 'CR' && $currenttype == 'CR') ){
        $balance = abs($balance)+abs($transaction['TranAmount']);
        }
        else{
        $balance = abs($balance)-abs($transaction['TranAmount']);
        }
        }





        @endphp





        {{  \App\Utils\Helpers::numberFormat(abs($balance)) }}
    </td>
    @else
    @php
    $currenttype = 'CR';
    @endphp
    @php
    $crAmount +=$transaction['TranAmount'];
    @endphp
    <td></td>
    <td>{{  \App\Utils\Helpers::numberFormat(abs($transaction['TranAmount'])) }}</td>
    <td>

        @php
        if(abs($balance) > abs($transaction['TranAmount'])){

        if(($type == 'DR' && $currenttype == 'DR') || ($type == 'CR' && $currenttype == 'CR') ){
        $balance = abs($balance)+abs($transaction['TranAmount']);
        }
        else{
        $balance = abs($balance)-abs($transaction['TranAmount']);
        }
        if($type == ''){
        $currenttype = 'CR';
        }else{
        $currenttype = $type;
        }
        }else{

        if(($type == 'DR' && $currenttype == 'DR') || ($type == 'CR' && $currenttype == 'CR') ){
        $balance = abs($balance)+abs($transaction['TranAmount']);
        }
        else{
        $balance = abs($balance)-abs($transaction['TranAmount']);
        }
        }





        @endphp
        <!-- <td>CR</td> -->

        {{  \App\Utils\Helpers::numberFormat(abs($balance)) }}
    </td>
    @endif

    @php
    if($transaction['TranAmount'] > 0){
    $dr = abs($balance);
    $dramount = abs($transaction['TranAmount']);
    $cr = 0;
    $cramount = 0;
    $type = $currenttype;

    }else{
    $cr = abs($balance);
    $cramount = abs($transaction['TranAmount']);
    $dr = 0;
    $dramount = 0;
    $type = $currenttype;
    }
    @endphp



    <td>{{$currenttype}}</td>



</tr>

@endforeach

        <tr>
        <td colspan="7" style="text-align: right;"><strong>Grand Total</strong></td>
        <td style="text-align: right;"><strong>{{  \App\Utils\Helpers::numberFormat(abs($drAmount)) }}</strong></td>
        <td style="text-align: right;"><strong>{{  \App\Utils\Helpers::numberFormat(abs($crAmount)) }}</strong></td>

        <td></td>
        <td></td>
    </tr>
        @endif
    </tbody>
</table>
