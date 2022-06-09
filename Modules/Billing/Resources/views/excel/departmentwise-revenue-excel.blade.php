<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'' }}</b></th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
            <th></th>
            @endfor
            <th colspan="8"><b>{{ isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'' }}</b></th>
        </tr>
        <tr><th></th></tr>
        <tr>
            @for($i=1;$i<6;$i++)
                <th></th>
            @endfor
            <th colspan="8">Department Wise Revenue( OP+IP )</th>
        </tr>
        <tr>
            @for($i=1;$i<6;$i++)
                <th></th>
            @endfor
            <th colspan="8">Date From:  {{ $fromdate}} Date To: {{ $todate }}</th>
        </tr>
        <tr>
            <td>S.No</td>
            <td>DEPARTMENT</td>
            <td>CASHTOTAL</td>
            <td>CREDITTOTAL</td>
            <td>TOTAL</td>
            <td>GROSSTOTAL</td>
            <td>DISCOUNT</td>
            <td>CASHVRTAX</td>
            <td>CREDITSVRTAX</td>
            <td>REFUNDTOTAL</td>
            <td>REFUNDDISCOUNT</td>
            <td>REFUNDSVRTAX</td>
            <td>NETTOTAL</td>
        </tr>
    </thead>

    <tbody>
    @if(isset($result) and count($result))

        @foreach($result as $k=>$r)

            @php
                $departmentencountervals = \DB::table('tblencounter')->select('fldencounterval')->where('fldcurrlocat', $r->fldcurrlocat)->get();
                $encountervals = [];
                foreach($departmentencountervals as $k=>$e) {

                    $encountervals[$k] = $e->fldencounterval;
                }


                $cashtotal = \DB::table('tblpatbilldetail')
                            ->whereIn('fldencounterval', $encountervals)
                            ->whereBetween('fldtime',[$fromdate,$todate])
                            ->where('fldbilltype', 'Cash')
                            ->sum('flditemamt');
                $grandcashtotal[] = $cashtotal;

                $cashtaxtotal = \DB::table('tblpatbilldetail')
                            ->whereIn('fldencounterval', $encountervals)
                            ->whereBetween('fldtime',[$fromdate,$todate])
                            ->where('fldbilltype', 'Cash')
                            ->sum('fldtaxamt');

                $grandtotalcashtax[] = $cashtaxtotal;

                $credittotal = \DB::table('tblpatbilldetail')
                              ->whereIn('fldencounterval', $encountervals)
                              ->whereBetween('fldtime',[$fromdate,$todate])
                              ->where('fldbilltype', 'Credit')
                              ->sum('flditemamt');

                $grandcredittotal[] = $credittotal;

                $credittaxtotal = \DB::table('tblpatbilldetail')
                              ->whereIn('fldencounterval', $encountervals)
                              ->whereBetween('fldtime',[$fromdate,$todate])
                              ->where('fldbilltype', 'Credit')
                              ->sum('fldtaxamt');

                $grandtotalcredittax[] = $credittaxtotal;

                $total  = $cashtotal + $credittotal;

                $grandtotal[] = $total;

                $discountamtcash = \DB::table('tblpatbilldetail')
                                  ->whereIn('fldencounterval', $encountervals)
                                  ->whereBetween('fldtime',[$fromdate,$todate])
                                  ->where('fldbilltype', 'Cash')
                                  ->sum('flddiscountamt');

                $discountamtCredit = \DB::table('tblpatbilldetail')
                                        ->whereIn('fldencounterval', $encountervals)
                                        ->whereBetween('fldtime',[$fromdate,$todate])
                                        ->where('fldbilltype', 'Credit')
                                        ->sum('flddiscountamt');

                $totaldiscount = $discountamtcash + $discountamtCredit;
                $grandtotaldiscount[] = $totaldiscount;

                $cashrefund = \DB::table('tblpatbilldetail')
                                ->where('fldbill','Credit Note')
                                ->orWhere('fldbill','credit note')
                                ->whereIn('fldencounterval', $encountervals)
                                ->whereBetween('fldtime',[$fromdate,$todate])
                                ->where(\DB::raw('substr(fldbillno, 1, 3)'), '=' , 'RET')
                                ->orWhere(\DB::raw('substr(fldbillno, 1, 3)'), '=' , 'CRE')
                                ->sum('fldreceivedamt');

                $totalcashrefund = $cashrefund;
                $grandtotalcashrefund[] = $totalcashrefund;
                $grosstotal = $total - $totalcashrefund;
                $grandgrosstotal[] = $grosstotal;
                $nettotal = $grosstotal - $totaldiscount + $cashtaxtotal + $credittaxtotal;
                $grandnettotal[] = $nettotal;

            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $r->fldcurrlocat }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($cashtotal) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($credittotal) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($total) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($grosstotal) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($totaldiscount) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($cashtaxtotal) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($credittaxtotal) }}</td>
                <td>{{ \App\Utils\Helpers::numberFormat($totalcashrefund) }}</td>
                <td></td>
                <td></td>
                <td>{{ \App\Utils\Helpers::numberFormat($nettotal) }}</td>

            </tr>
        @endforeach

         <tr>
            <td colspan="2">Grand Total</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($grandcashtotal))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($grandcredittotal))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($grandtotal))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($grandgrosstotal))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($grandtotaldiscount))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($grandtotalcashtax))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($grandtotalcredittax))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($grandtotalcashrefund))}}</td>
            <td></td>
            <td></td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($grandnettotal))}}</td>
        </tr>

    @endif
    </tbody>
</table>
<?php //exit();?>
