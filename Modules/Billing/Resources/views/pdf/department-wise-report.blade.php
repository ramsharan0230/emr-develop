
<!DOCTYPE html>
<html>
<head>
    <title>Department Wise Revenue Report</title>
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
    </style>

</head>
<body>

<header>
    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 20%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" width="100" height="100"/></td>
            <td style="width:70%;">
                <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                <h4 style="text-align: center;margin-top:2px;margin-bottom:0;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                <h4 style="text-align: center;margin-top:2px;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                <h4 style="text-align: center;margin-top:2px;"> Contact No: {{ Options::get('system_telephone_no') ? Options::get('system_telephone_no'):'' }}</h4>
                <h4 style="text-align: center;margin-top:2px;">Department Wise Total Collection </h4>

            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
</header>


<main>

    <ul>

        <li>Date:
            {{$from_date}} {{ isset($from_date) ? "(". \App\Utils\Helpers::dateEngToNepdash($from_date)->full_date .")" : ''}} TO
            {{$to_date}} {{ isset($to_date) ? "(" .\App\Utils\Helpers::dateEngToNepdash($to_date)->full_date . ")":'' }}
        </li>
    </ul>

    <table style="width: 100%;" border="1px" class="content-body">
        <thead class="thead-light">
            <tr>
                <th>SNo.</th>
                <th>Department Name</th>
                <th>Cash Item Amount</th>
                <th>Credit Amount</th>
                <th>Total</th>
                <th>Free Con.Amt</th>
                <th>SVR Tax</th>

                <th>Refund Amount</th>
                <th>RF SVR Tax</th>
                <th>Net Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $gctot_itmamt = $gctot_taxamt = $gctot_disamt = $grtot_itmamt = $gnetamt = 0;
                $ctot_itmamt = $ctot_taxamt = $ctot_disamt = $rtot_itmamt = 0;
                $dtot_itmamt =0;
                $totally = 0;
                $netotally = 0;
            @endphp
            @foreach ($departments as $key=>$department)
                <tr>
                    <td>{{++$key}}</td>
                    <td>{{$department}} </td>
                    @if(array_key_exists($department, $CasResults->toArray()))
                        @php
                            $casdata = $CasResults->toArray()[$department][0];
                            $ctot_itmamt = ($casdata['tot_itmamt']) ?  $casdata['tot_itmamt'] :  0.00;
                            $ctot_taxamt = ($casdata['tot_taxamt']) ?  $casdata['tot_taxamt'] :  0.00;
                            $ctot_disamt = ($casdata['tot_disamt']) ?  $casdata['tot_disamt'] :  0.00;
                            $gctot_itmamt += $ctot_itmamt;
                            $gctot_taxamt += $ctot_taxamt;
                            $gctot_disamt += $ctot_disamt;
                        @endphp
                        <td>{{\App\Utils\Helpers::numberFormat($ctot_itmamt)}}</td>
                        <td>0.00</td>
                        @php
                        $totally = $ctot_itmamt;
                        $netotally += $totally;
                        @endphp
                        <td>{{\App\Utils\Helpers::numberFormat($totally)}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($ctot_disamt)}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($ctot_taxamt)}}</td>

                    @else
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                    @endif
                    @if(array_key_exists($department, $RetResults->toArray()))
                        @php
                            $retdata = $RetResults->toArray()[$department][0];
                            $rtot_itmamt = ($retdata['tot_itmamt']) ?  $retdata['tot_itmamt'] :  0.00;
                            $grtot_itmamt += $rtot_itmamt;
                        @endphp
                        <td>{{\App\Utils\Helpers::numberFormat($rtot_itmamt)}}</td>
                        <td>0.00</td>
                    @else
                        <td>0.00</td>
                        <td>0.00</td>
                    @endif
                    @php
                        $netamt = $ctot_itmamt + $ctot_taxamt - $ctot_disamt - $rtot_itmamt;
                        $gnetamt += $netamt;
                    @endphp
                    <td>{{\App\Utils\Helpers::numberFormat($netamt)}} </td>
                </tr>

                <!--for deposit -->
                @if(array_key_exists($department, $DepResults->toArray()))
                    @php
                        $depdata = $DepResults->toArray()[$department][0];
                        $dtot_itmamt = ($depdata['tot_itmamt']) ?  $depdata['tot_itmamt'] :  0.00;
                    @endphp
                @endif



            @endforeach
        </tbody>
        <tfoot>
            <th colspan="2">Total</th>
            <th>{{\App\Utils\Helpers::numberFormat($gctot_itmamt)}}</th>
            <th>0.00</th>
            <th>{{\App\Utils\Helpers::numberFormat($netotally)}}</th>
            <th>{{\App\Utils\Helpers::numberFormat($gctot_disamt)}}</th>
            <th>{{\App\Utils\Helpers::numberFormat($gctot_taxamt)}}</th>

            <th>{{\App\Utils\Helpers::numberFormat($grtot_itmamt)}}</th>
            <th>Refund tax</th>
            <th>{{\App\Utils\Helpers::numberFormat($gnetamt)}}</th>


        </tfoot>
    </table>
    <br>
    <ul style="list-style: none">
    <li>Gross Revenue: {{\App\Utils\Helpers::numberFormat($netotally)}}</li>
    <li>Free and Concession: {{\App\Utils\Helpers::numberFormat($gctot_disamt)}}</li>
    <li>Total SVR Tax: {{\App\Utils\Helpers::numberFormat($gctot_taxamt)}}</li>
    @php
    $Net_Realized_Revenue = 0;
    $Deposit_Collection = 0;
    $Total_Collection = 0;
    $Net_Realized_Revenue =  $netotally -  $gctot_disamt +  $gctot_taxamt;
    $Deposit_Collection =  $deposit;
    $Total_Collection =  $netotally -  $gctot_disamt + $gctot_taxamt + $deposit;
    @endphp
    <li>Net Realized Revenue: {{ \App\Utils\Helpers::numberFormat($Net_Realized_Revenue) }}</li>
    <li>Deposit Collection: {{\App\Utils\Helpers::numberFormat($Deposit_Collection)}} </li>
    <li>Total Collection: {{\App\Utils\Helpers::numberFormat($Total_Collection)}}</li>
    <li>Cash Refund amount: {{\App\Utils\Helpers::numberFormat($deposit_refund)}}</li>
    <li>Cash RF SVR tax: </li>
    <li>Deposit Adjustment: </li>
    <li>Credit(Dues): </li>
    <li>Credit colln(Discharge): </li>

    <li>Net Cash collection: </li>
    <li>Creit Refund Amt + Tax : </li>


    </ul>
</main>
</body>
</html>
