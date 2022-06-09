<table>
    <thead>
    <tr>
        <th></th>
    </tr>
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
    <tr>
        <th></th>
    </tr>
    <tr>
        <th></th>
    </tr>
    <tr>

        <th rowspan="2">Department</th>
        <th colspan="6">OP Collection</th>

        <th colspan="6">IP Collection</th>

        <th rowspan="2">Deposit Card Payment</th>
        <th rowspan="2">Total Bill Collection</th>
        <th rowspan="2">Credit Collection</th>
        <th rowspan="2">Grand Total Collection</th>

    </tr>
    <tr>

        <th>Cash Bill(+)</th>
        <th>Card Bill(+)</th>
        <th>Cash Refund(-)</th>
        <th>Deposit(+)</th>
        <th>Deposit Refund(-)</th>
        <th>Net Total</th>
        <th>Cash Bill(+)</th>
        <th>Card Bill(+)</th>
        <th>Cash Refund(-)</th>
        <th>Deposit(+)</th>
        <th>Deposit Refund(-)</th>
        <th>Net Total</th>
        <!-- <th row="4"></th> -->
    </tr>
    </thead>
    <tbody>
    @php
        $totalopcashbill = array();
        $totalopcreditbill = array();
        $totalopcashrefund = array();
        $totalopdeposit = array();
        $totalopdepositref = array();
        $totalopnettotal = array();

        $totalipcashbill = array();
        $totalipcreditbill = array();
        $totalipcashrefund = array();
        $totalipdeposit = array();
        $totalipdepositref = array();
        $totalipnettotal = array();

        $totaldepositcardpayment = array();
        $finaltotalbillcollection = array();
        $finaltotalcreditcollection = array();
        $finalgrandtotal = array();
    @endphp
    @if(isset($result) and count($result))

        @foreach($result as $k=>$r)
            @php
                $opcashbillData = DB::table('tblpatbilldetail as pbd')
            ->whereBetween('pbd.fldtime', [$eng_from_date . ' 00:00:00', $eng_to_date . ' 23:59:59'])
            ->join('tblpatbilling as pb', 'pbd.fldbillno', 'pb.fldbillno')
            ->where('pbd.fldbill', 'Invoice')
            ->where('pbd.fldcomp', 'LIKE', $r->fldcomp)
            ->where('pb.fldopip', 'OP')
            ->where(function ($query) {
                $query->orWhere('pbd.fldbilltype', 'LIKE', 'Cash')
                    ->orWhere('pbd.fldbilltype', 'LIKE', 'cash');
            })
            ->groupBy(['pbd.fldid'])
            ->pluck('fldreceivedamt')->toArray();

        $totalopcashbill[] = $opcashbill = $opcashbillData ? array_sum($opcashbillData) : 0;
        $opcreditbillData = DB::table('tblpatbilldetail as pbd')
            ->whereBetween('pbd.fldtime', [$eng_from_date . ' 00:00:00', $eng_to_date . ' 23:59:59'])
            ->join('tblpatbilling as pb', 'pbd.fldbillno', 'pb.fldbillno')
            ->where('pbd.fldbill', 'Invoice')
            ->where('pbd.fldcomp', 'LIKE', $r->fldcomp)
            ->where('pb.fldopip', 'OP')
            ->where(function ($query) {
                $query->orWhere('pbd.fldbilltype', 'LIKE', 'Credit')
                    ->orWhere('pbd.fldbilltype', 'LIKE', 'credit');
            })
            ->groupBy(['pbd.fldid'])
            ->pluck('fldreceivedamt')->toArray();
        $totalopcreditbill[] = $opcreditbill = $opcreditbillData ? array_sum($opcreditbillData) : 0;
        $opcashrefundData = DB::table('tblpatbilldetail as pbd')
            ->whereBetween('pbd.fldtime', [$eng_from_date . ' 00:00:00', $eng_to_date . ' 23:59:59'])
            ->join('tblpatbilling as pb', 'pbd.fldbillno', 'pb.fldbillno')
            ->where('pbd.fldbill', 'Credit Note')
            ->orWhere('pbd.fldbill', 'credit note')
            ->where('pbd.fldcomp', 'LIKE', $r->fldcomp)
            ->where('pb.fldopip', 'OP')
            ->where(function ($query) {
                $query->orWhere(\DB::raw('substr(pbd.fldbillno, 1, 3)'), '=', 'RET')
                    ->orWhere(\DB::raw('substr(pbd.fldbillno, 1, 3)'), '=', 'CRE');
            })
            ->groupBy(['pbd.fldid'])
            ->pluck('fldreceivedamt')->toArray();
        $totalopcashrefund[] = $opcashrefund = $opcashrefundData ? array_sum($opcashrefundData) : 0;
        $opdepositData = DB::table('tblpatbilldetail as pbd')
            ->whereBetween('pbd.fldtime', [$eng_from_date . ' 00:00:00', $eng_to_date . ' 23:59:59'])
            ->join('tblpatbilling as pb', 'pbd.fldbillno', 'pb.fldbillno')
            ->where('pbd.fldbill', 'Invoice')
            ->where('pbd.fldcomp', 'LIKE', $r->fldcomp)
            ->where('pb.fldopip', 'OP')
            ->where(function ($query) {
                $query->orWhere('pbd.fldbilltype', 'LIKE', 'Cash')
                    ->orWhere('pbd.fldbilltype', 'LIKE', 'cash');
            })
            ->where(\DB::raw('substr(pbd.fldbillno, 1, 3)'), '=', 'DEP')
            ->groupBy(['pbd.fldid'])
            ->pluck('fldreceivedamt')->toArray();
        $totalopdeposit[] = $opdeposit = $opdepositData ? array_sum($opdepositData) : 0;
        $opdepositrefData = DB::table('tblpatbilldetail as pbd')
            ->whereBetween('pbd.fldtime', [$eng_from_date . ' 00:00:00', $eng_to_date . ' 23:59:59'])
            ->join('tblpatbilling as pb', 'pbd.fldbillno', 'pb.fldbillno')
            ->where('pbd.fldbill', 'Invoice')
            ->where('pbd.fldcomp', 'LIKE', $r->fldcomp)
            ->where('pb.fldopip', 'OP')
            ->where('pbd.fldbilltype', 'Credit')
            ->where(\DB::raw('substr(pbd.fldbillno, 1, 3)'), '=', 'needtobuild')
            ->groupBy(['pbd.fldid'])
            ->pluck('fldreceivedamt')->toArray();
        $totalopdepositref[] = $opdepositref = $opdepositrefData ? array_sum($opdepositrefData) : 0;
        $opnettotal = $opcashbill + $opcreditbill + $opcashrefund + $opdeposit + $opdepositref;
        $totalopnettotal[] = $opnettotal;

        $ipcashbillData = DB::table('tblpatbilldetail as pbd')
            ->whereBetween('pbd.fldtime', [$eng_from_date . ' 00:00:00', $eng_to_date . ' 23:59:59'])
            ->join('tblpatbilling as pb', 'pbd.fldbillno', 'pb.fldbillno')
            ->where('pbd.fldbill', 'Invoice')
            ->where('pbd.fldcomp', 'LIKE', $r->fldcomp)
            ->where('pb.fldopip', 'IP')
            ->where(function ($query) {
                $query->orWhere('pbd.fldbilltype', 'LIKE', 'Cash')
                    ->orWhere('pbd.fldbilltype', 'LIKE', 'cash');
            })
            ->groupBy(['pbd.fldid'])
            ->pluck('fldreceivedamt')->toArray();
        $totalipcashbill[] = $ipcashbill = $ipcashbillData ? array_sum($ipcashbillData) : 0;
        $ipcreditbillData = DB::table('tblpatbilldetail as pbd')
            ->whereBetween('pbd.fldtime', [$eng_from_date . ' 00:00:00', $eng_to_date . ' 23:59:59'])
            ->join('tblpatbilling as pb', 'pbd.fldbillno', 'pb.fldbillno')
            ->where('pbd.fldbill', 'Invoice')
            ->where('pbd.fldcomp', 'LIKE', $r->fldcomp)
            ->where('pb.fldopip', 'IP')
            ->where(function ($query) {
                $query->orWhere('pbd.fldbilltype', 'LIKE', 'Cash')
                    ->orWhere('pbd.fldbilltype', 'LIKE', 'cash');
            })
            ->groupBy(['pbd.fldid'])
            ->pluck('fldreceivedamt')->toArray();
        $totalipcreditbill[] = $ipcreditbill = $ipcreditbillData ? array_sum($ipcreditbillData) : 0;
        $ipcashrefundData = DB::table('tblpatbilldetail as pbd')
            ->whereBetween('pbd.fldtime', [$eng_from_date . ' 00:00:00', $eng_to_date . ' 23:59:59'])
            ->join('tblpatbilling as pb', 'pbd.fldbillno', 'pb.fldbillno')
            ->where('pbd.fldbill', 'Credit Note')
            ->orWhere('pbd.fldbill', 'credit note')
            ->where('pbd.fldcomp', 'LIKE', $r->fldcomp)
            ->where('pb.fldopip', 'IP')
            ->where(function ($query) {
                $query->orWhere(\DB::raw('substr(pbd.fldbillno, 1, 3)'), '=', 'RET')
                    ->orWhere(\DB::raw('substr(pbd.fldbillno, 1, 3)'), '=', 'CRE');
            })
            ->groupBy(['pbd.fldid'])
            ->pluck('fldreceivedamt')->toArray();
        $totalipcashrefund[] = $ipcashrefund = $ipcashrefundData ? array_sum($ipcashrefundData) : 0;
        $ipdepositData = DB::table('tblpatbilldetail as pbd')
            ->whereBetween('pbd.fldtime', [$eng_from_date . ' 00:00:00', $eng_to_date . ' 23:59:59'])
            ->join('tblpatbilling as pb', 'pbd.fldbillno', 'pb.fldbillno')
            ->where('pbd.fldbill', 'Invoice')
            ->where('pbd.fldcomp', 'LIKE', $r->fldcomp)
            ->where('pb.fldopip', 'IP')
            ->where(function ($query) {
                $query->orWhere('pbd.fldbilltype', 'LIKE', 'Cash')
                    ->orWhere('pbd.fldbilltype', 'LIKE', 'cash');
            })
            ->where(\DB::raw('substr(pbd.fldbillno, 1, 3)'), '=', 'DEP')
            ->groupBy(['pbd.fldid'])
            ->pluck('fldreceivedamt')->toArray();
        $totalipdeposit[] = $ipdeposit = $ipdepositData ? array_sum($ipdepositData) : 0;
        $ipdepositrefData = DB::table('tblpatbilldetail as pbd')
            ->whereBetween('pbd.fldtime', [$eng_from_date . ' 00:00:00', $eng_to_date . ' 23:59:59'])
            ->join('tblpatbilling as pb', 'pbd.fldbillno', 'pb.fldbillno')
            ->where('pbd.fldbill', 'Invoice')
            ->where('pbd.fldcomp', 'LIKE', $r->fldcomp)
            ->where('pb.fldopip', 'IP')
            ->where('pbd.fldbilltype', 'Credit')
            ->where(\DB::raw('substr(pbd.fldbillno, 1, 3)'), '=', 'needtobuild')
            ->groupBy(['pbd.fldid'])
            ->pluck('fldreceivedamt')->toArray();
                    $totalipdepositref[] = $ipdepositref = $ipdepositrefData ? array_sum($ipdepositrefData) : 0;
                $ipnettotal = $ipcashbill+$ipcreditbill+$ipcashrefund+$ipdeposit+$ipdepositref;
                $totalipnettotal[] = $ipnettotal;

                $depositcardpayment = $ipdeposit+$opdeposit;
                $totalbillcollection = $opcashbill+$ipcashbill;
                $totalcreditcollection = $opcreditbill+$ipcreditbill;
                $grandtotal = $opnettotal+$ipnettotal;

                $totaldepositcardpayment[] = $depositcardpayment;
                $finaltotalbillcollection[] = $totalbillcollection;
                $finaltotalcreditcollection[] = $totalcreditcollection;
                $finalgrandtotal[] = $grandtotal;

            @endphp
            <tr>

                <td>{{ App\Utils\Helpers::getDepartmentFromCompID($r->fldcomp)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($opcashbill)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($opcreditbill)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($opcashrefund)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($opdeposit)}}</td>
                <td>Refund Deposit</td>
                <td>{{\App\Utils\Helpers::numberFormat($opnettotal)}}</td>

                <td>{{\App\Utils\Helpers::numberFormat($ipcashbill)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($ipcreditbill)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($ipcashrefund)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($ipdeposit)}}</td>
                <td>IP Refund Deposit</td>
                <td>{{\App\Utils\Helpers::numberFormat($ipnettotal)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($depositcardpayment)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($totalbillcollection)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($totalcreditcollection)}}</td>
                <td>{{\App\Utils\Helpers::numberFormat($grandtotal)}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="1">Grand Total</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalopcashbill))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalopcreditbill))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalopcashrefund))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalopdeposit))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalopdepositref))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalopnettotal))}}</td>

            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalipcashbill))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalipcreditbill))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalipcashrefund))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalipdeposit))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalipdepositref))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totalipnettotal))}}</td>

            <td>{{\App\Utils\Helpers::numberFormat(array_sum($totaldepositcardpayment))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($finaltotalbillcollection))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($finaltotalcreditcollection))}}</td>
            <td>{{\App\Utils\Helpers::numberFormat(array_sum($finalgrandtotal))}}</td>

        </tr>
    @endif
    </tbody>
</table>
