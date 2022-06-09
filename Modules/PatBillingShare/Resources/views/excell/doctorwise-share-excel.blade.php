<table>
    <thead>
    <tr>
        <th></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="5">
            <b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b>
        </th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="5">
            <b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b>
        </th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="5"><b>Doctor Share Summary</b></th>
        <th colspan="5"></th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="10"><b>From: {{$from_date}} {{ isset($eng_from_date) ? "(". $eng_from_date .")" : ''}} TO
                {{$to_date}} {{ isset($eng_to_date) ? "(" .$eng_to_date . ")":'' }}</b></th>
    </tr>


    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="5"><b>Print
                Date: {{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse(\Carbon\Carbon::now())->format('Y-m-d'))->full_date }}</b>
        </th>
    </tr>
    <tr>
        @for($i=1;$i<4;$i++)
            <th></th>
        @endfor
        <th colspan="3"><b>{{$doc_detail->fldtitlefullname}}</b></th>
    </tr>

    <tr>
        <th></th>
    </tr>
    <tr>
        <th style="text-align: center;">Source Head</th>
        <th style="text-align: center;">Dr. Share</th>
        <th style="text-align: center;">TDS</th>
        <th style="text-align: center;">Net Dr. Share</th>
    </tr>
    </thead>
    <tbody>
    @php
        $grandshare = 0.00;
        $grandusrshare = 0.00;
        $grandtds = 0.00;
        $grandnetshare = 0.00;
        $grandnetcancelshare = 0.00;
    @endphp
    @foreach($results as $itemtype => $result)
        @php
            $grand_amt = 0.00;
            $grand_num = 0;
            $grand_share = 0.00;
            $grand_usr_share = 0.00;
            $grand_tds = 0.00;
            $grand_net_share = 0.00;
            $grand_net_cancel_share = 0.00;
        @endphp
        @foreach($result as $itemname => $res)
            @php
                $tot_amt = 0.00;
                $tot_share = 0.00;
                $tot_usr_share = 0.00;
                $tot_tds = 0.00;
                $tot_net_share = 0.00;
                $tot_net_cancel_share = 0.00;
            @endphp
            @foreach($res as $isReturn => $re)
                @if($isReturn == 0)
                    @php
                        $tot_num = count($re);
                    @endphp
                    @foreach($re as $r)
                        @php
                            $tot_amt += $r->fldditemamt;
                            $tot_share += $r->share;
                            $tot_usr_share += $r->user_share;
                            // $tot_tds += $r->flditemtax;
                            $tax_amt = ($r->tax_amt) ? $r->tax_amt : 0;
                            $tot_tds += $tax_amt;
                            $payment = $r->share - $tax_amt;
                            $tot_net_share += $payment;
                        @endphp
                    @endforeach
                @else
                    @php
                        $tot_num = count($re);
                    @endphp
                    @foreach($re as $r)
                        @php
                            $can_tax_amt = ($r->tax_amt) ? $r->tax_amt : 0;
                            $can_payment = $r->share - $can_tax_amt;
                            $tot_net_cancel_share += $can_payment;
                        @endphp
                    @endforeach
                @endif
            @endforeach
            @php
                $grand_amt += $tot_amt;
                $grand_num += $tot_num;
                $grand_share += $tot_share;
                $grand_usr_share += $tot_usr_share;
                $grand_tds += $tot_tds;
                $grand_net_share += $tot_net_share;
                $grand_net_cancel_share += $tot_net_cancel_share;
            @endphp
        @endforeach
        @php
            $grandshare += $grand_share;
            $grandusrshare += $grand_usr_share;
            $grandtds += $grand_tds;
            $grandnetshare += $grand_net_share;
            $grandnetcancelshare += $grand_net_cancel_share;
        @endphp
        <tr>
            <td>{{(strtolower($itemtype) != "payable") ? $itemtype : "Others"}}</td>
            {{-- (Rs. {{\App\Utils\Helpers::numberFormat($grand_usr_share)}}) --}}
            <td>Rs. {{\App\Utils\Helpers::numberFormat($grand_share)}}</td>
            <td>Rs. {{\App\Utils\Helpers::numberFormat($grand_tds)}}</td>
            <td>Rs. {{\App\Utils\Helpers::numberFormat($grand_net_share)}}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th style="text-align: center;">Grand Total</th>
        {{-- (Rs. {{\App\Utils\Helpers::numberFormat($grandusrshare)}}) --}}
        <th>Rs. {{\App\Utils\Helpers::numberFormat($grandshare)}}</th>
        <th>Rs. {{\App\Utils\Helpers::numberFormat($grandtds)}}</th>
        <th>Rs. {{\App\Utils\Helpers::numberFormat($grandnetshare)}}</th>
    </tr>
    <tr>
        <th style="text-align: center;">Total Cancel(s)</th>
        {{-- <th>Rs. {{\App\Utils\Helpers::numberFormat($grandshare)}}</th>
        <th>Rs. {{\App\Utils\Helpers::numberFormat($grandtds)}}</th> --}}
        <th colspan="3" style="text-align: right;">Rs. {{\App\Utils\Helpers::numberFormat($grandnetcancelshare)}}</th>
    </tr>
    </tfoot>

    <tr>
        @for($i=1;$i<2;$i++)
            <th></th>
        @endfor
        <th colspan="2"><b>Doctor Share Details</b></th>
        <th colspan="2"></th>
    </tr>

    @foreach($results as $itemtype => $result)
        @php
            $qtycount = 0;
        @endphp
        <table class="table content-body">
            <thead>
            <tr>
                <th colspan="8"><b>{{(strtolower($itemtype) != "payable") ? $itemtype : "Others"}}</b></th>
            </tr>

            <tr>
                <th style="text-align: center;">Name</th>
                @if(in_array($itemtype,['OT Dr. Individual','OT Dr. Group','OT Anesthesia','OT  OT Assistant','ICU Procedure']))
                    <th></th>
                    <th></th>
                @endif
                <th style="text-align: center;">Numbers</th>
                <th style="text-align: center;">Total Amount</th>
                <th style="text-align: center;">Dr. Share</th>
                <th style="text-align: center;">TDS</th>
                <th style="text-align: center;">Net Dr. Share</th>
            </tr>


            </thead>
            <tbody>
            @php
                $grand_amt = 0.00;
                $grand_num = 0;
                $grand_share = 0.00;
                $grand_usr_share = 0.00;
                $grand_tds = 0.00;
                $grand_net_share = 0.00;

                $ret_grand_amt = 0.00;
                $ret_grand_num = 0;
                $ret_grand_share = 0.00;
                $ret_grand_usr_share = 0.00;
                $ret_grand_tds = 0.00;
                $ret_grand_net_share = 0.00;
            @endphp
            @foreach($result as $itemname => $res)
                @php
                    $tot_num = 0;
                    $tot_amt = 0.00;
                    $tot_share = 0.00;
                    $tot_usr_share = 0.00;
                    $tot_tds = 0.00;
                    $tot_net_share = 0.00;

                    $ret_tot_num = 0;
                    $ret_tot_amt = 0.00;
                    $ret_tot_share = 0.00;
                    $ret_tot_usr_share = 0.00;
                    $ret_tot_tds = 0.00;
                    $ret_tot_net_share = 0.00;
                @endphp
                @foreach($res as $isReturn => $re)
                    @if($isReturn == 0)
                        @php
                            $tot_num = count($re);
                        @endphp
                        @foreach($re as $r)
                            @php
                                $tot_amt += $r->fldditemamt;
                                $tot_share += $r->share;
                                $tot_usr_share += $r->user_share;
                                // $tot_tds += $r->flditemtax;
                                $tax_amt = ($r->tax_amt) ? $r->tax_amt : 0;
                                $tot_tds += $tax_amt;
                                $payment = $r->share - $tax_amt;
                                $tot_net_share += $payment;
                            @endphp
                        @endforeach
                    @else
                        @php
                            $ret_tot_num = count($re);
                        @endphp
                        @foreach($re as $r)
                            @php
                                $ret_tot_amt += $r->fldditemamt;
                                $ret_tot_share += $r->share;
                                $ret_tot_usr_share += $r->user_share;
                                // $ret_tot_tds += $r->flditemtax;
                                $ret_tax_amt = ($r->tax_amt) ? $r->tax_amt : 0;
                                $ret_tot_tds += $ret_tax_amt;
                                $ret_payment = $r->share - $ret_tax_amt;
                                $ret_tot_net_share += $ret_payment;
                            @endphp
                        @endforeach
                    @endif
                @endforeach
                <tr>
                    <td>{{$itemname}} </td>
                    @if(in_array($itemtype,['OT Dr. Individual','OT Dr. Group','OT Anesthesia','OT  OT Assistant','ICU Procedure']))
                        <td></td>
                        <td></td>
                    @endif
                    {{--                        <td>{{$tot_num}}</td>--}}
                    <td></td>
                    <td>Rs. {{\App\Utils\Helpers::numberFormat($tot_amt)}}</td>
                    {{-- (Rs. {{\App\Utils\Helpers::numberFormat($tot_usr_share)}}) --}}
                    <td>Rs. {{\App\Utils\Helpers::numberFormat($tot_share)}}</td>
                    <td>Rs. {{\App\Utils\Helpers::numberFormat($tot_tds)}}</td>
                    <td>Rs. {{\App\Utils\Helpers::numberFormat($tot_net_share)}}</td>
                </tr>

                @if(in_array($itemtype,['OPD Consultation','IPD Round']))
                    <tr>
                        <th colspan="10">&nbsp;</th>
                        {{--                            <th style="text-align: left; font-weight: bold;" colspan="5">{{$itemname}}</th>--}}
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>Total Qty</th>
                        <th>Total Amount</th>
                        <th>Dr Share</th>
                        <th>TDS</th>
                        <th></th>
                    </tr>
                    @php
                        $patientdetail = \App\Utils\Helpers::getTestedDatewise($itemname,$bill_no,$eng_from_date,$eng_to_date,$flditemname,$doc_name,$itemtype);
                    @endphp
                    @if($patientdetail)
                        @foreach($patientdetail as $patient)
                            <tr>
                                <td>{{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($patient->fldordtime)->format('Y-m-d'))->full_date }}</td>

                                <td>{{$patient->flditemqty}} </td>
                                <td>Rs.{{\App\Utils\Helpers::numberFormat($patient->fldditemamt)}} </td>
                                <td>Rs.{{\App\Utils\Helpers::numberFormat($patient->share)}} </td>
                                <td>Rs.{{\App\Utils\Helpers::numberFormat($patient->tax_amt)}} </td>
                                <td></td>
                            </tr>
                            @php
                                $qtycount += $patient->flditemqty;
                            @endphp
                        @endforeach
                    @endif
                    <tr>
                        <th colspan="10">&nbsp;</th>
                        {{--                            <th style="text-align: left; font-weight: bold;" colspan="5">{{$itemname}}</th>--}}
                    </tr>
                @endif



                @if(in_array($itemtype,['OT Dr. Individual','OT Dr. Group','OT Anesthesia','OT  OT Assistant','ICU Procedure']))
                    <tr>
                        <th>OT Bill Date</th>
                        <th>Patient</th>
                        <th>Service</th>
                        <th>QTY</th>
                        <th>Total Amount</th>
                        <th>Dr Share</th>
                        <th>TDS</th>
                        <th></th>
                    </tr>

                    @php
                        $patientdetail = Helpers::getTestedPatientByTestname($itemname,$bill_no,$eng_from_date,$eng_to_date,$flditemname,$doc_name);
                    @endphp
                    @if($patientdetail)
                        @foreach($patientdetail as $patient)
                            <tr>
                                <td>{{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($patient->fldordtime)->format('Y-m-d'))->full_date }}</td>
                                <td>{{ucwords($patient->fldptnamefir) . ' ' . ucwords($patient->fldmidname) . ' ' . ucwords($patient->fldptnamelast)}}</td>
                                <td>{{$itemname}}</td>
                                <td>{{$patient->flditemqty}} </td>
                                <td>Rs.{{\App\Utils\Helpers::numberFormat($patient->fldditemamt)}} </td>
                                <td>Rs.{{\App\Utils\Helpers::numberFormat($patient->share)}} </td>
                                <td>Rs.{{\App\Utils\Helpers::numberFormat($patient->tax_amt)}} </td>
                                <td></td>
                            </tr>
                            @php
                                $qtycount += $patient->flditemqty;
                            @endphp
                            @endforeach
                            @endif

                            @endif


{{--                            </tr>--}}
                            @php
                                $grand_amt += $tot_amt;
                                $grand_num += $tot_num;
                                $grand_share += $tot_share;
                                $grand_usr_share += $tot_usr_share;
                                $grand_tds += $tot_tds;
                                $grand_net_share += $tot_net_share;

                                $ret_grand_amt += $ret_tot_amt;
                                $ret_grand_num += $ret_tot_num;
                                $ret_grand_share += $ret_tot_share;
                                $ret_grand_usr_share += $ret_tot_usr_share;
                                $ret_grand_tds += $ret_tot_tds;
                                $ret_grand_net_share += $ret_tot_net_share;
                            @endphp
                        @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th style="text-align: center;">Total</th>
                @if(in_array($itemtype,['OT Dr. Individual','OT Dr. Group','OT Anesthesia','OT  OT Assistant','ICU Procedure']))
                    <th></th>
                    <th></th>
                @endif
                <th>@if($qtycount == 0) {{$grand_num}}  @else{{$qtycount}}@endif</th>
                <th>Rs. {{\App\Utils\Helpers::numberFormat($grand_amt)}}</th>
                {{-- (Rs. {{\App\Utils\Helpers::numberFormat($grand_usr_share)}}) --}}
                <th>Rs. {{\App\Utils\Helpers::numberFormat($grand_share)}}</th>
                <th>Rs. {{\App\Utils\Helpers::numberFormat($grand_tds)}}</th>
                <th>Rs. {{\App\Utils\Helpers::numberFormat($grand_net_share)}}</th>
            </tr>
            <tr>
                <th style="text-align: center;">Cancel(s)</th>
                @if(in_array($itemtype,['OT Dr. Individual','OT Dr. Group','OT Anesthesia','OT  OT Assistant','ICU Procedure']))
                    <th></th>
                    <th></th>
                @endif
                <th>{{$ret_grand_num}}</th>
                <th>Rs. {{\App\Utils\Helpers::numberFormat($ret_grand_amt)}}</th>
                {{-- (Rs. {{\App\Utils\Helpers::numberFormat($ret_grand_usr_share)}}) --}}
                <th>Rs. {{\App\Utils\Helpers::numberFormat($ret_grand_share)}}</th>
                <th>Rs. {{\App\Utils\Helpers::numberFormat($ret_grand_tds)}}</th>
                <th>Rs. {{\App\Utils\Helpers::numberFormat($ret_grand_net_share)}}</th>
            </tr>
            </tfoot>
        </table>
    @endforeach


</table>



