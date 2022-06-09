


        <table style="width: 100%;" border="1px" class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>SNo.</th>

                    <th>Department Name</th>
                    <th>Cash Item Amt</th>

                    <th>Credit Amt</th>
                    <th>Total</th>
                    <th>Free Con.Amt</th>
                    <th>SVR Tax</th>
                    <th> Item Amt</th>
                    <th>Refund Amt</th>
                    <th>RF SVR Tax</th>
                    <th>Net Amt</th>
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
                @php

                $ctot_itmamt = $ctot_taxamt = $ctot_disamt = $rtot_itmamt = 0;

                @endphp
                <tr>
                    <td>{{++$key}}</td>
                    <td>{{$report}}</td>
                    @if(array_key_exists($report, $CasResults->toArray()))
                        @php
                        $casdata = $CasResults->toArray()[$report][0];
                        $ctot_itmamt = ($casdata['totalamount']) ? $casdata['totalamount'] : 0.00;
                        $ctot_itmedamt = ($casdata['tot_itemamt']) ? $casdata['tot_itemamt'] :0.00;
                        $ctot_taxamt = ($casdata['tot_taxamt']) ? $casdata['tot_taxamt'] : 0.00;
                        $ctot_disamt = ($casdata['tot_discamt']) ? $casdata['tot_discamt'] : 0.00;
                        $gtot_itmamt += $ctot_itmamt;
                        $gtot_itmedamt += $ctot_itmedamt;
                        $gtot_taxamt += $ctot_taxamt;
                        $gtot_disamt += $ctot_disamt;
                        @endphp
                        @if(array_key_exists($report, $CreResults->toArray()))
                            @php
                                $credata = $CreResults->toArray()[$report][0];
                                $cretot_itmamt = ($credata['totalamount']) ? $credata['totalamount'] : 0.00;
                                $gcretot_itmamt += $cretot_itmamt;
                            @endphp
                        @else
                            @php
                                $cretot_itmamt = 0.00;
                            @endphp
                        @endif

                        <td>{{\App\Utils\Helpers::numberFormat($ctot_itmamt)}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($cretot_itmamt)}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($ctot_itmamt+$cretot_itmamt)}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($ctot_disamt)}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($ctot_taxamt)}}</td>
                        <td>{{\App\Utils\Helpers::numberFormat($ctot_itmamt+$cretot_itmamt-$ctot_disamt+$ctot_taxamt)}} </td>
                    @else
                        @if(array_key_exists($report, $CreResults->toArray()))
                            @php
                                $credata = $CreResults->toArray()[$report][0];
                                $cretot_itmamt = ($credata['totalamount']) ? $credata['totalamount'] : 0.00;
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
                    $rtot_itmamt = ($retdata['tot_itemamt']) ? $retdata['tot_itemamt'] : 0.00;
                    $grtot_itmamt += $rtot_itmamt;
                    @endphp
                    <td>{{\App\Utils\Helpers::numberFormat($rtot_itmamt)}}</td>
                    <td>0.00</td>
                    @else
                    <td>0.00</td>
                    <td>0.00</td>
                    @endif


                      @if(array_key_exists($report, $ForNetResults->toArray()))
                    @php
                    $ForNetResultsdata = $ForNetResults->toArray()[$report][0];

                    $fntot_itmamt = $ctot_itmamt+$cretot_itmamt-$ctot_disamt+$ctot_taxamt + $rtot_itmamt;
                    $gfntot_itmamt += $fntot_itmamt;
                    @endphp
                    <td>{{\App\Utils\Helpers::numberFormat($fntot_itmamt)}}</td>

                    @else
                    <td>0.00</td>

                    @endif
                </tr>



                @endforeach
            </tbody>
            <tfoot>
                <th colspan="2">Total</th>
                <th>{{\App\Utils\Helpers::numberFormat($gtot_itmamt)}}</th>
                <th>{{\App\Utils\Helpers::numberFormat($gcretot_itmamt)}}</th>
                <th>{{\App\Utils\Helpers::numberFormat($gtot_itmamt+$gcretot_itmamt)}}</th>
                <th>{{\App\Utils\Helpers::numberFormat($gtot_disamt)}}</th>
                <th>{{\App\Utils\Helpers::numberFormat($gtot_taxamt)}}</th>
                <th>{{\App\Utils\Helpers::numberFormat($gtot_itmedamt)}} </th>
                <th>{{\App\Utils\Helpers::numberFormat($grtot_itmamt)}}</th>
                <th>0.00</th>
                <th>{{\App\Utils\Helpers::numberFormat($gfntot_itmamt)}}</th>
            </tfoot>
        </table>


        <table class="table table-bordered" style="width:50%">
<tr>
    <td>OP Collection</td>
    <td>{{\App\Utils\Helpers::numberFormat($OP_patbilling)}}</td>
</tr>
<tr>
    <td>IP Collection</td>
    <td>{{\App\Utils\Helpers::numberFormat($IP_Patbilling)}}</td>
</tr>
<tr>
    <td>Net Realized Revenue</td>
    <td>{{\App\Utils\Helpers::numberFormat($OP_patbilling+$IP_Patbilling)}}</td>
</tr>
<tr>
    <td>Deposit Only</td>
    <td>{{\App\Utils\Helpers::numberFormat($deposit)}}</td>
</tr>
<tr>
    <td>Deposit Refund</td>
    <td>{{\App\Utils\Helpers::numberFormat($deposit_refund)}}</td>
</tr>
<tr>
    <td>Adjustment from Previous Deposit</td>
    <td>{{\App\Utils\Helpers::numberFormat($Previous_Deposit_of_Discharge_Clearence)}}</td>
</tr>
<tr>
    <td>Amount Received while Discharge Patient</td>
    <td>{{\App\Utils\Helpers::numberFormat($Received_Deposit_of_Discharge_Clearence)}}</td>
</tr>
<tr>
    <td>Total Collection</td>
    <td>{{\App\Utils\Helpers::numberFormat($rev_amount_sum)}}</td>
</tr>
        </table>
