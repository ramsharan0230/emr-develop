@extends('inpatient::pdf.layout.main')

@section('title', 'GROUP CATEGORY REPORT')

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

    {{-- <ul>
        <li><b>Date:</b>
            {{$finalfrom}} {{ isset($finalfrom) ? "(". \App\Utils\Helpers::dateEngToNepdash($finalfrom)->full_date .")" : ''}}&nbsp;&nbsp;<b>TO</b>&nbsp;&nbsp;
            {{$finalto}} {{ isset($finalto) ? "(" .\App\Utils\Helpers::dateEngToNepdash($finalto)->full_date . ")":'' }}
        </li>
        <li><b>Billing Mode:</b> {{ $billingmode }}</li>
        <li><b>Comp:</b>{{ $comp }}</li>
    </ul> --}}
    <table style="width: 100%;">
        <colgroup>
            <col style="width: 50%" />
            <col style="width: 25%" />
            <col style="width: 25%" />
        </colgroup>
        <tr style="margin-bottom:4px;">
            <td><b>Date:</b>
                {{$finalfrom}} {{ isset($finalfrom) ? "(". \App\Utils\Helpers::dateEngToNepdash($finalfrom)->full_date .")" : ''}} &nbsp;&nbsp;<b>To</b>&nbsp;&nbsp;
                {{$finalto}} {{ isset($finalto) ? "(" .\App\Utils\Helpers::dateEngToNepdash($finalto)->full_date . ")":'' }}</td>
            <td><b>Billing Mode:</b> {{ $billingmode }}</td>
            <td><b>Comp:</b>{{ $comp }}</td>
        </tr>
    </table>

    <table style="width: 100%;"  class="content-body">
        <thead class="thead-light">
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
            @php

                $ctot_itmamt = $ctot_taxamt = $ctot_disamt = $rtot_itmamt = 0;

            @endphp
            <tr>
                <td>{{++$key}}</td>
                <td>{{$report}}</td>
                @if(array_key_exists($report, $CasResults->toArray()))
                    @php
                    $casdata = $CasResults->toArray()[$report][0];
                    $ctot_itmamt = ($casdata['totalamount']) ?  $casdata['totalamount'] :  0.00;
                    $ctot_itmedamt = ($casdata['tot_itemamt']) ?  $casdata['tot_itemamt']:  0.00;
                    $ctot_taxamt = ($casdata['tot_taxamt']) ?  $casdata['tot_taxamt'] : 0.00;
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
                    <td>{{\App\Utils\Helpers::numberFormat($ctot_itmamt+$cretot_itmamt)}}</td>
                    <td>{{\App\Utils\Helpers::numberFormat($ctot_disamt)}}</td>
                    <td>{{\App\Utils\Helpers::numberFormat($ctot_taxamt)}}</td>
                    <td>{{\App\Utils\Helpers::numberFormat($ctot_itmamt+$cretot_itmamt-$ctot_disamt+$ctot_taxamt)}} </td>
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


                  @if(array_key_exists($report, $ForNetResults->toArray()))
                @php
                $ForNetResultsdata = $ForNetResults->toArray()[$report][0];
                $fntot_itmamt = $ctot_itmamt + $cretot_itmamt- $ctot_disamt+$ctot_taxamt + $rtot_itmamt;
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
            <th>{{\App\Utils\Helpers::numberFormat($gtot_itmedamt)}} </th>
            <th>{{ \App\Utils\Helpers::numberFormat(($grtot_itmamt))}}</th>
            <th>0.00</th>
            <th>{{ \App\Utils\Helpers::numberFormat(($gfntot_itmamt))}}</th>
            </tr>
        </tbody>




    </table>

    <br>
    <table class="table" style="width:50%;border:1px solid #ddd;">
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
        </table>

   @endsection
