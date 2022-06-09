<!DOCTYPE html>
<!-- saved from url=(0040)file:///C:/Users/DELL/Downloads/pdf.html -->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- <title>Invoice for {{ $patbillingDetails->fldbillno??"" }}</title> -->
</head>

<body>
    <style>
        @page {
            margin: 24mm 0 11mm;
        }

        body {
            margin: 0 auto;
            padding: 10px 10px 5px;
            font-size: 13px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .bill-title {
            position: absolute;
            width: 100%;
            text-align: center;
            margin-bottom: 2px;
            margin-top: 3px;
        }


        .a4 {
            width: auto;
            margin: 0 auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-body {
            border-collapse: collapse;
        }

        .content-body table {
            page-break-inside: auto
        }

        .content-body tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        .content-body td:nth-child(1),
        .content-body th:nth-child(1),
        .content-body td:nth-child(2),
        .content-body th:nth-child(2),
        .content-body td:nth-child(3),
        .content-body th:nth-child(3),
        .content-body td:nth-child(4),
        .content-body th:nth-child(4),
        .content-body td:nth-child(5),
        .content-body th:nth-child(5) {
            text-align: left;
        }


        .content-body td:nth-child(5),
        .content-body th:nth-child(5),
        .content-body td:nth-child(6),
        .content-body th:nth-child(6),
        .content-body td:nth-child(7),
        .content-body th:nth-child(7)  {
            text-align: left;
        }


        .content-body td,
        .content-body th {
            border: 1px solid #ddd;
            font-size: 13px;
            text-align: right;
            padding-right: 4px;
        }

        h2,
        h4 {
            line-height: 0.5rem;
        }

        ul {
            float: right;
            padding: 0;
            margin: 0;
        }

        ul li {
            text-align: right;
            ;
            list-style: none;

        }

        ul li span:first-child {
            text-align: left;
        }

        ul li span:nth-child() {
            text-align: right;
            width: 150px;
            display: inline-block;
        }

        .table thead {
            background-color: #fff;
        }
    </style>
    <div class="a4">
        @include('frontend.common.account-header')
        <div class="main-body">

            <div class="pdf-container" style="margin: 0 auto; width: 95%;">

                <div style="width: 100%;"></div>
                <table style="width: 60%; float: left;">
                    <tbody>
                        <tr>
                            <td>Account: {{ $account_name->AccountName }}</td>
                            <td>Code: {{ $account_name->account_group ? $account_name->account_group->GroupTree : '' }}</td>
                        </tr>
                    </tbody>
                </table>
                <table style="width: 40%;float:right;text-align:right;">
                    <tbody>
                        <tr>
                            <td>Date From: {{ $from_date }} {{ isset($from_date) ? "(". \App\Utils\Helpers::dateToNepali($from_date) . ")":'' }}</td>
                        </tr>
                        <tr>
                            <td>Date To: {{ $to_date }} {{ isset($to_date) ? "(". \App\Utils\Helpers::dateToNepali($to_date). ")" :'' }}</td>
                        </tr>
                    </tbody>
                </table>
                <div style="clear: both"></div>
            </div>
            <div class="pdf-container" style="margin: 0 auto; width: 95%;">
                <div class="table-dental2" style="margin-top: 16px;">
                    <table class="table content-body">
                        <thead class="thead-light">
                            <tr>
                                <th class="tittle-th">SNo</th>
                                <th class="tittle-th">DateBS</th>
                                <th class="tittle-th">DateAD</th>
                                <th class="tittle-th">V No.</th>
                                <th class="tittle-th">Sub Ledger</th>
                                <th class="tittle-th">Description</th>
                                <th class="tittle-th">Cheque No</th>
                                <th class="tittle-th">Dr Amount</th>
                                <th class="tittle-th">Cr Amount</th>
                                <th class="tittle-th">Balance</th>
                                <th class="tittle-th">DrCr</th>
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

        <!-- <td>DR</td> -->



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

                </div>

            </div>

        </div>
    </div>


</body>

</html>
