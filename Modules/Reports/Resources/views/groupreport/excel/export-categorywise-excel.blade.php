<table>
    <thead>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
    </tr>
    <tr><th></th></tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{ $selectedItem }}</b></th>
        <th colspan="5"></th>
    </tr>
    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>{{$finalfrom}} {{ isset($finalfrom) ? "(". \App\Utils\Helpers::dateNepToEng($finalfrom)->full_date .")" : ''}} TO
                {{$finalto}} {{ isset($finalto) ? "(" .\App\Utils\Helpers::dateNepToEng($finalto)->full_date . ")":'' }}</b></th>
    </tr>


    <tr>
        @for($i=1;$i<3;$i++)
            <th></th>
        @endfor
        <th colspan="5" style="text-align: center"><b>Datetime: {{ \Carbon\Carbon::now() }}</b></th>
        <th style="text-align: center"></th>
    </tr>

    <tr><th></th></tr>
    <tr>
        <th>SNo.</th>
        <th>Department Name</th>
        <th>Cash Item Amount</th>
        <th>Credit Amount</th>
        <th>Total</th>
        <th>Free Con.Amt</th>
        <th>SVR Tax</th>
        <th> Item Amount</th>
        <th>Refund Amount</th>
        <th>RF SVR Tax</th>
        <th>Net Amount</th>
    </tr>
    </thead>
    <tbody>
        @php
            $gtot_itmamt = $gcretot_itmamt = $gtot_taxamt = $gtot_disamt = $grtot_itmamt = $gnetamt = 0;
            $ctot_itmamt = $ctot_taxamt = $ctot_disamt = $rtot_itmamt = 0;
            $dtot_itmamt =  $gtot_itmedamt = 0;
            $fntot_itmamt = 0;
            $gfntot_itmamt= 0;
        @endphp
        @foreach ($reports as $key=>$report)
        <tr>
            <td>{{++$key}}</td>
            <td>{{htmlspecialchars($report)}}</td>
            @if(array_key_exists($report, $CasResults->toArray()))
                @php
                $casdata = $CasResults->toArray()[$report][0];
                $ctot_itmamt = ($casdata['totalamount']) ?  $casdata['totalamount'] :  0.00;
                $ctot_itmedamt = ($casdata['tot_itemamt']) ?  $casdata['tot_itemamt'] :  0.00;
                $ctot_taxamt = ($casdata['tot_taxamt']) ?  $casdata['tot_taxamt'] :  0.00;
                $ctot_disamt = ($casdata['tot_discamt']) ?  $casdata['tot_discamt'] :  0.00;
                $gtot_itmamt += $ctot_itmamt;
                $gtot_itmedamt += $ctot_itmedamt;
                $gtot_taxamt += $ctot_taxamt;
                $gtot_disamt += $ctot_disamt;
                @endphp
                @if(array_key_exists($report, $CreResults->toArray()))
                    @php
                        $credata = $CreResults->toArray()[$report][0];
                        $cretot_itmamt = ($credata['totalamount']) ?  $credata['totalamount'] :  0.00;
                        $gcretot_itmamt += $cretot_itmamt;
                    @endphp
                @else
                    @php
                        $cretot_itmamt = 0.00;
                    @endphp
                @endif
                <td>{{\App\Utils\Helpers::numberFormat($ctot_itmamt)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($cretot_itmamt)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($ctot_itmamt)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($ctot_disamt)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($ctot_taxamt)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($ctot_itmedamt)}} </td>
            @else
                @if(array_key_exists($report, $CreResults->toArray()))
                    @php
                        $credata = $CreResults->toArray()[$report][0];
                        $cretot_itmamt = ($credata['totalamount']) ?  $credata['totalamount'] :  0.00;
                        $gcretot_itmamt += $cretot_itmamt;
                    @endphp
                @else
                    @php
                        $cretot_itmamt = 0.00;
                    @endphp
                @endif
                <td>0.00</td>
                <td>{{\App\Utils\Helpers::numberFormat($cretot_itmamt)}}</td>
                <td>0.00</td>
                <td>0.00</td>
                <td>0.00</td>
                <td>0.00</td>
            @endif
            @if(array_key_exists($report, $RetResults->toArray()))
            @php
            $retdata = $RetResults->toArray()[$report][0];
            $rtot_itmamt = ($retdata['tot_itemamt']) ?  $retdata['tot_itemamt'] :  0.00;
            $grtot_itmamt += $rtot_itmamt;
            @endphp
            <td>{{\App\Utils\Helpers::numberFormat($rtot_itmamt)}}</td>
            <td>0.00</td>
            @else
            <td>0.00</td>
            <td>0.00</td>
            @endif
            @php
            $netamt = $ctot_itmamt + $ctot_taxamt - $ctot_disamt + $rtot_itmamt;
            $gnetamt += $netamt;
            @endphp

                @if(array_key_exists($report, $ForNetResults->toArray()))
            @php
            $ForNetResultsdata = $ForNetResults->toArray()[$report][0];
            $fntot_itmamt = ($ForNetResultsdata['tot_itemamt']) ?  \App\Utils\Helpers::numberFormat(($ForNetResultsdata['tot_itemamt'])) :  \App\Utils\Helpers::numberFormat((0.00));
            $gfntot_itmamt += $fntot_itmamt;
            @endphp
            <td>{{\App\Utils\Helpers::numberFormat($fntot_itmamt)}}</td>

            @else
            <td>0.00</td>

            @endif
        </tr>
        @endforeach
        <tr>
        <th colspan="2">Total</th>
        <th style="text-align: right;">{{ \App\Utils\Helpers::numberFormat(($gtot_itmamt))}}</th>
        <th>{{ \App\Utils\Helpers::numberFormat(($gcretot_itmamt))}}</th>
        <th>{{ \App\Utils\Helpers::numberFormat(($gtot_itmamt))}}</th>
        <th>{{ \App\Utils\Helpers::numberFormat(($gtot_disamt))}}</th>
        <th>{{ \App\Utils\Helpers::numberFormat(($gtot_taxamt))}}</th>
        <th>{{$gtot_itmedamt}} </th>
        <th>{{ \App\Utils\Helpers::numberFormat(($grtot_itmamt))}}</th>
        <th>0.00</th>
        <th>{{ \App\Utils\Helpers::numberFormat(($gfntot_itmamt))}}</th>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td>OP Collection</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($OP_patbilling))}}</td>
        </tr>
        <tr>
            <td>IP Collection</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($IP_Patbilling))}}</td>
        </tr>
        <tr>
            <td>Net Realized Revenue</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($OP_patbilling+$IP_Patbilling))}}</td>
        </tr>
        <tr>
            <td>Deposit Only</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($deposit))}}</td>
        </tr>
        <tr>
            <td>Deposit Refund</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($deposit_refund))}}</td>
        </tr>
        <tr>
            <td>Adjustment from Previous Deposit</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($Previous_Deposit_of_Discharge_Clearence))}}</td>
        </tr>
        <tr>
            <td>Amount Received while Discharge Patient</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($Received_Deposit_of_Discharge_Clearence))}}</td>
        </tr>
        <tr>
            <td>Total Collection</td>
            <td>{{ \App\Utils\Helpers::numberFormat(($rev_amount_sum))}}</td>
        </tr>
    </tfoot>
</table>
