@extends('inpatient::pdf.layout.main')

@section('title')
    Doctor Share Report
@endsection

@section('content')
<header>
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 20%;"><img src="{{ asset('uploads/config/'.Options::get('brand_image')) }}" alt="" style="max-width:100%;" /></td>
                    <td style="width:60%;">
                        <h3 style="text-align: center;margin-bottom:8px;">{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</h3>
                        <h4 style="text-align: center;margin-top:2px;margin-bottom:0;">{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</h4>
                        <h4 style="text-align: center;margin-top:2px;">{{ isset(Options::get('siteconfig')['system_address'])?Options::get('siteconfig')['system_address']:'' }}</h4>
                        <h4 style="text-align: center;margin-top:2px;"> Contact No: {{ Options::get('system_telephone_no') ? Options::get('system_telephone_no'):'' }}</h4>
                        @if(isset($certificate))
                        <h4 style="text-align: center;margin-top:2px;">{{ucfirst($certificate)}} REPORT</h4>
    @endif

                    </td>
                    <td style="width: 20%;"></td>
                </tr>
            </tbody>
        </table>
    </header>
    <div style="width: 100%;">
        <div style="width: 50%;float: left;">
            <p>From Date: {{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($from_date)->format('Y-m-d'))->full_date }}</p>
            <p>To Date: {{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse($to_date)->format('Y-m-d'))->full_date }}</p>
        </div>
        <div style="width: 50%;float: left; text-align: right;">
            <p>Print Date: {{ \App\Utils\Helpers::dateEngToNepdash(\Carbon\Carbon::parse(\Carbon\Carbon::now())->format('Y-m-d'))->full_date }}</p>
        </div>
    </div>
    <div style="width: 100%;">
        <div style="width: 50%;">
            <h3>{{$doc_detail->fldtitlefullname}}</h3>
        </div>
    </div>
    <div style="width: 100%;">
        <div style="width: 20%;">
            <p><b>Doctor Share Summary</b></p>
        </div>
        <div style="width: 60%;">
            <table class="table content-body">
                <thead>
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
            </table>
        </div>
    </div>
    <div style="width: 100%;">
        <div style="width: 50%;">
            <p><b>Doctor Share Details</b></p>
        </div>
    </div>
    @foreach($results as $itemtype => $result)
        <div>
            <table class="table content-body">
                <thead>
                <tr>
                    <th colspan="10"><b>{{(strtolower($itemtype) != "payable") ? $itemtype : "Others"}}</b></th>
                </tr>
                <tr>
                    <th style="text-align: center;">Name</th>

                    @if(in_array($itemtype,['OT Dr. Individual','OT Dr. Group','OT Anesthesia','OT  OT Assistant','ICU Procedure']))
                    <th></th>
                    <th></th>
                    @endif
                    <th style="text-align: center;">Qty</th>
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
                    $grand_num_qty = 0;
                    $grand_share = 0.00;
                    $grand_usr_share = 0.00;
                    $grand_tds = 0.00;
                    $grand_net_share = 0.00;

                    $ret_grand_amt = 0.00;
                    $ret_grand_num = 0;
                    $ret_grand_num_qty = 0;
                    $ret_grand_share = 0.00;
                    $ret_grand_usr_share = 0.00;
                    $ret_grand_tds = 0.00;
                    $ret_grand_net_share = 0.00;
                @endphp
                @foreach($result as $itemname => $res)
                    @php
                        $tot_num = 0;
                        $tot_num_qty = 0;
                        $tot_amt = 0.00;
                        $tot_share = 0.00;
                        $tot_usr_share = 0.00;
                        $tot_tds = 0.00;
                        $tot_net_share = 0.00;

                        $ret_tot_num = 0;
                        $ret_tot_num_qty = 0;
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
                                $tot_num_qty = ($re->sum('flditemqty'));
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
                                $ret_tot_num_qty = ($re->sum('flditemqty'));
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
                    @if($tot_num != 0)
                        <tr>
                            <td>{{$itemname}}  </td>
                            @if(in_array($itemtype,['OT Dr. Individual','OT Dr. Group','OT Anesthesia','OT  OT Assistant','ICU Procedure']))
                    <td></td>
                    <td></td>
                    @endif
                            <td>{{$tot_num_qty}}</td>
                            <td>Rs. {{\App\Utils\Helpers::numberFormat($tot_amt)}}</td>
                            {{-- (Rs. {{\App\Utils\Helpers::numberFormat($tot_usr_share)}}) --}}
                            <td>Rs. {{\App\Utils\Helpers::numberFormat($tot_share)}}</td>
                            <td>Rs. {{\App\Utils\Helpers::numberFormat($tot_tds)}}</td>
                            <td>Rs. {{\App\Utils\Helpers::numberFormat($tot_net_share)}}</td>

                        </tr>

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
                            $patientdetail = Helpers::getTestedPatientByTestname($itemname,$bill_no,$eng_from_date,$eng_to_date,$flditemname,$doc_name,$itemtype);
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
                                    <td>Rs.{{\App\Utils\Helpers::numberFormat(($patient->share - $patient->tax_amt))}}</td>
                                </tr>
                            @endforeach
                        @endif

                        @endif



                        @php
                            $grand_amt += $tot_amt;
                            $grand_num += $tot_num_qty;
                            $grand_num_qty += $tot_num_qty;
                            $grand_share += $tot_share;
                            $grand_usr_share += $tot_usr_share;
                            $grand_tds += $tot_tds;
                            $grand_net_share += $tot_net_share;

                            $ret_grand_amt += $ret_tot_amt;
                            $ret_grand_num += $ret_tot_num;
                            $ret_grand_num_qty += $ret_tot_num_qty;
                            $ret_grand_share += $ret_tot_share;
                            $ret_grand_usr_share += $ret_tot_usr_share;
                            $ret_grand_tds += $ret_tot_tds;
                            $ret_grand_net_share += $ret_tot_net_share;
                        @endphp
                    @endif
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th style="text-align: center;">Total</th>

                    <th>{{$grand_num}}</th>
                    <!-- <th>{{$grand_num_qty}}</th> -->
                    <th>Rs. {{\App\Utils\Helpers::numberFormat($grand_amt)}}</th>
                    {{-- (Rs. {{\App\Utils\Helpers::numberFormat($grand_usr_share)}}) --}}
                    <th>Rs. {{\App\Utils\Helpers::numberFormat($grand_share)}}</th>
                    <th>Rs. {{\App\Utils\Helpers::numberFormat($grand_tds)}}</th>
                    <th>Rs. {{\App\Utils\Helpers::numberFormat($grand_net_share)}}</th>
                    @if(in_array($itemtype,['OT Dr. Individual','OT Dr. Group','OT Anesthesia','OT  OT Assistant','ICU Procedure']))
                    <th></th>
                    <th></th>
                    @endif

                </tr>
                <tr>
                    <th style="text-align: center;">Cancel(s)</th>

                    <th>{{$ret_grand_num}}</th>
                    <!-- <th>{{$ret_grand_num_qty}}</th> -->
                    <th>Rs. {{\App\Utils\Helpers::numberFormat($ret_grand_amt)}}</th>
                    {{-- (Rs. {{\App\Utils\Helpers::numberFormat($ret_grand_usr_share)}}) --}}
                    <th>Rs. {{\App\Utils\Helpers::numberFormat($ret_grand_share)}}</th>
                    <th>Rs. {{\App\Utils\Helpers::numberFormat($ret_grand_tds)}}</th>
                    <th>Rs. {{\App\Utils\Helpers::numberFormat($ret_grand_net_share)}}</th>
                    @if(in_array($itemtype,['OT Dr. Individual','OT Dr. Group','OT Anesthesia','OT  OT Assistant','ICU Procedure']))
                    <th></th>
                    <th></th>
                    @endif
                </tr>
                </tfoot>
            </table>
        </div><br>
    @endforeach
@endsection
