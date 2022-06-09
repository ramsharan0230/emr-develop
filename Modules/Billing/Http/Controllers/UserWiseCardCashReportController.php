<?php

namespace Modules\Billing\Http\Controllers;

use App\BillingSet;
use App\CogentUsers;
use App\Encounter;
use App\HospitalDepartment;
use App\HospitalDepartmentUsers;
use App\PatBillCount;
use App\PatBillDetail;
use App\PatBilling;
use App\Utils\Helpers;
use Auth;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;

class UserWiseCardCashReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data['billingset'] = Cache::remember('billing_set', 60 * 60 * 24, function () {
            return BillingSet::get();
        });


        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['hospital_departments'] = HospitalDepartment::where('status', 'active')->with('branchData')->get();
       // dd($data['hospital_departments']);
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('billing::userwisecardcash.user_wise_card_cash', $data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchUserWiseCardDetail(Request $request)
    {
        try {
            $result = PatBillDetail::select('flduserid', 'fldcomp')
                ->when($request->department != "" && $request->department != null, function ($q) use ($request) {
                    return $q->where('fldcomp',$request->department);
                })
                ->groupBy('flduserid')->get();

            $html = '';
            $totalopcashbill = $totalopdeposit = $totalopdepositref = array();
            $totalipnettotal = 0;
            $finaltotalbillcollection = 0;
            $finalgrandtotal = 0;
            $totalopcashbill = 0;
            $totalopcashrefund = 0;
            $totalopnettotal = 0;
            $totalipcashbill = 0;
            $totalipcashrefund = 0;

            $totalopcreditbill = $totalcreditopdeposit = $totalcreditopdepositref = array();
            $totalcreditipnettotal = 0;
            $totalopcreditbill = 0;
            $totalopcreditrefund = 0;
            $totalcreditopnettotal = 0;
            $totalipcreditbill = 0;
            $totalipcreditrefund = 0;
            //for op
            $totalopcardbill = 0;
            $totalopcardrefund = 0;
            $alltotalopnetcard=0;
            $totalopfonepaybill = 0;
            $totalopfonepayrefund = 0;
            $alltotalopnetfonepay=0;
            $totalopnettotal_collection=0;

            //for ip
            $totalipcardbill=0;
            $totalipcardrefund=0;
            $totalipcardnettotal=0;
            $totalipfonepaybill=0;
            $totalipfonepayrefund=0;
            $totalipfonepaynettotal=0;
            $totalipnettotal_collection=0;

            $miscell = 0;
            $grandtotalrecevied = 0;
            $totalgrandtotalrecevied = 0;
            $totalmiscel = 0;
            $department = $request->department ? $request->department : '';

            if (isset($result) && count($result) > 0) {
                foreach ($result as $k => $r) {
                    // Cash op and ip
                    $opcashbillDataSql = "select SUM(pbd.fldreceivedamt) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval NOT LIKE '%IP%'
                        and pbd.fldsave LIKE '%1%'
                        and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%')
                        and (pbd.fldbillno  LIKE '%CAS%'  OR  pbd.fldbillno  LIKE '%REG%'  OR pbd.fldbillno  LIKE '%PHM%' OR (pbd.fldbillno  LIKE '%CRE%' AND pbd.fldreceivedamt > 0))
                        ";
                        if(!empty($department)){
                            $opcashbillDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                        }
                    $opcashbillData = DB::select(
                        $opcashbillDataSql
                    );
                    $opcashbill = $opcashbillData ? $opcashbillData[0]->total : 0;

                    $opcashrefundDataSql = "select SUM(pbd.fldreceivedamt) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval NOT LIKE '%IP%'
                        and pbd.fldsave like '%1%'
                        and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%')
                        and pbd.fldbillno LIKE '%RET%'
                        ";
                        if(!empty($department)){
                            $opcashrefundDataSql .= " and pbd.fldcomp  ='" . $department . "' ";
                        }
                    $opcashrefundData = DB::select(
                        $opcashrefundDataSql
                    );
                    $opcashrefund = $opcashrefundData ? $opcashrefundData[0]->total : 0;

                    $opdepositDataSql = "select sum(fldreceivedamt) as totaldepo
                    from tblpatbilldetail
                    where fldbillno like '%dep%' and fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                    and flduserid LIKE '" . $r->flduserid . "' and
                    fldreceivedamt NOT LIKE '-%'
                    ";
                    if(!empty($department)){
                        $opdepositDataSql .= " and fldcomp   ='" . $department . "' ";
                    }
                    $opdepositData = DB::select($opdepositDataSql);
                    $totalopdeposit[] = $opdeposit = $opdepositData ? $opdepositData[0]->totaldepo : 0;

                    $opdepositrefDataSql = "select sum(fldreceivedamt) as totalrefund from tblpatbilldetail
                        where fldbillno like '%dep%' and fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and fldtime <='" . $request->eng_to_date . ' 23:59:59' . "' and flduserid LIKE '" . $r->flduserid . "'
                        and fldreceivedamt LIKE '-%'";
                    if(!empty($department)){
                        $opdepositrefDataSql .= " and fldcomp   ='" . $department . "' ";
                    }
                    $opdepositrefData = DB::select(
                        $opdepositrefDataSql
                    );
                    $totalopdepositref[] = $opdepositref = $opdepositrefData ? $opdepositrefData[0]->totalrefund : 0;

                    $opnettotal = $opcashbill + $opcashrefund;

                    $totalopnettotal += $opnettotal ?? 0;

                    $ipcashbillDataSql = "select SUM(pbd.fldreceivedamt) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval LIKE '%IP%'
                        and pbd.fldsave like '%1%'
                        and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%')
                        and (pbd.fldbillno  LIKE '%CAS%'  OR  pbd.fldbillno  LIKE '%PHM%' OR  pbd.fldbillno  LIKE '%REG%' OR (pbd.fldbillno  LIKE '%CRE%' AND pbd.fldreceivedamt > 0))
                        ";
                        if(!empty($department)){
                            $ipcashbillDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                        }
                    $ipcashbillData = DB::select(
                        $ipcashbillDataSql
                    );
                    $ipcashbill = $ipcashbillData ? $ipcashbillData[0]->total : 0;

                    $ipcashrefundDataSql = "select SUM(pbd.fldreceivedamt) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval  LIKE '%IP%'
                        and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%')
                        and pbd.fldbillno like '%RET%'
                        and pbd.fldsave like '%1%' ";
                        if(!empty($department)){
                            $ipcashrefundDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                        }
                    $ipcashrefundData = DB::select(
                        $ipcashrefundDataSql
                    );
                    //op collection ko net total + Ip collection ko net total = total bill collection
                    //total bill collection plus deposit - deposit refund = Gran total collection
                    $ipcashrefund = $ipcashrefundData ? $ipcashrefundData[0]->total : 0;

                    $ipnettotal = $ipcashbill + $ipcashrefund;
                    $totalipnettotal += $ipnettotal;

                    $opcreditbillDataSql = "select SUM(pbd.fldcurdeposit) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval NOT LIKE '%IP%'
                        and pbd.fldsave LIKE '%1%'
                        and (pbd.payment_mode LIKE 'Credit' OR pbd.payment_mode LIKE 'credit')
                        and (pbd.fldbilltype  LIKE '%Credit%')
                        and (pbd.fldbillno  LIKE '%CRE%')";
                    if(!empty($department)){
                        $opcreditbillDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                    }
                    $opcreditbillData = DB::select(
                        $opcreditbillDataSql
                    );
                    $opcreditbill = $opcreditbillData ? $opcreditbillData[0]->total : 0;

                    $opcreditrefundDataSql = "select SUM(pbd.fldcurdeposit) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval NOT LIKE '%IP%'
                        and pbd.fldsave like '%1%'
                        and pbd.fldbilltype LIKE '%CREDIT%'
                        and (pbd.payment_mode LIKE 'Credit' OR pbd.payment_mode LIKE 'credit')
                        and pbd.fldbillno LIKE '%RET%'";

                    if(!empty($department)){
                        $opcreditrefundDataSql .= " and pbd.fldcomp  ='" . $department . "' ";
                    }
                    $opcreditrefundData = DB::select(
                        $opcreditrefundDataSql
                    );
                    $opcreditrefund = $opcreditrefundData ? $opcreditrefundData[0]->total : 0;
                    $creditopnettotal = $opcreditbill + $opcreditrefund;

                    $totalcreditopnettotal += $creditopnettotal ?? 0;

                    $ipcreditbillDataSql = "select SUM(pbd.fldcurdeposit) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval LIKE '%IP%'
                        and pbd.fldsave like '%1%'
                        and (pbd.fldbilltype  LIKE '%Credit%')
                        and (pbd.payment_mode LIKE 'Credit' OR pbd.payment_mode LIKE 'credit')
                        and (pbd.fldbillno  LIKE '%CRE%')
                        ";
                    if(!empty($department)){
                        $ipcreditbillDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                    }
                    $ipcreditbillData = DB::select(
                        $ipcreditbillDataSql
                    );
                    $ipcreditbill = $ipcreditbillData ? $ipcreditbillData[0]->total : 0;

                    $ipcreditrefundDataSql = "select SUM(pbd.fldcurdeposit) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval  LIKE '%IP%'
                        and pbd.fldbillno like '%RET%'
                        and pbd.fldbilltype LIKE '%CREDIT%'
                        and (pbd.payment_mode LIKE 'Credit' OR pbd.payment_mode LIKE 'credit')
                        and pbd.fldsave like '%1%' ";
                        if(!empty($department)){
                            $ipcreditrefundDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                        }
                        // dd($ipcreditrefundDataSql);
                    $ipcreditrefundData = DB::select(
                        $ipcreditrefundDataSql
                    );
                    //op collection ko net total + Ip collection ko net total = total bill collection
                    //total bill collection plus deposit - deposit refund = Gran total collection
                    $ipcreditrefund = $ipcreditrefundData ? $ipcreditrefundData[0]->total : 0;

                    /* For op card */
                    $opcardbillDataSql = "select SUM(pbd.fldreceivedamt) as total
                    from tblpatbilldetail pbd
                    where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                    and pbd.flduserid LIKE '" . $r->flduserid . "'
                    and pbd.fldencounterval NOT LIKE '%IP%'
                    and pbd.fldsave LIKE '%1%'
                    and (pbd.payment_mode LIKE '%card%')
                    and (pbd.fldbillno  LIKE '%CAS%'  OR  pbd.fldbillno  LIKE '%REG%'  OR pbd.fldbillno  LIKE '%PHM%' )";

                    if(!empty($department)){
                        $opcardbillDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                    }

                    $opcardbillData = DB::select(
                        $opcardbillDataSql
                    );
                    $opcardbill = $opcardbillData ? $opcardbillData[0]->total : 0;
                    $opcardrefundDataSql = "select SUM(pbd.fldreceivedamt) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval NOT LIKE '%IP%'
                        and pbd.fldsave like '%1%'
                        and (pbd.payment_mode LIKE '%card%')
                        and pbd.fldbillno LIKE '%RET%'";

                        if(!empty($department)){
                            $opcardrefundDataSql .= " and pbd.fldcomp  ='" . $department . "' ";
                        }
                    $opcardrefundData = DB::select(
                        $opcardrefundDataSql
                    );
                    $opcardrefund = $opcardrefundData ? $opcardrefundData[0]->total : 0;
                    $opnetcardtotal=$opcardbill+$opcardrefund;
                    $alltotalopnetcard +=$opnetcardtotal ?? '0';
                    /*End for card */

                    /* For op fonepay */
                    $opfonepaybillDataSql = "select SUM(pbd.fldreceivedamt) as total
                    from tblpatbilldetail pbd
                    where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                    and pbd.flduserid LIKE '" . $r->flduserid . "'
                    and pbd.fldencounterval NOT LIKE '%IP%'
                    and pbd.fldsave LIKE '%1%'
                    and (pbd.payment_mode LIKE '%fonepay%')
                    and (pbd.fldbillno  LIKE '%CAS%'  OR  pbd.fldbillno  LIKE '%REG%'  OR pbd.fldbillno  LIKE '%PHM%' )";

                    if(!empty($department)){
                        $opfonepaybillDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                    }

                    $opfonepaybillData = DB::select(
                        $opfonepaybillDataSql
                    );
                    $opfonepaybill = $opfonepaybillData ? $opfonepaybillData[0]->total : 0;
                    $opfonepayrefundDataSql = "select SUM(pbd.fldreceivedamt) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval NOT LIKE '%IP%'
                        and pbd.fldsave like '%1%'
                        and (pbd.payment_mode LIKE '%fonepay%')
                        and pbd.fldbillno LIKE '%RET%'";

                        if(!empty($department)){
                            $opfonepayrefundDataSql .= " and pbd.fldcomp  ='" . $department . "' ";
                        }
                    $opfonepayrefundData = DB::select(
                        $opfonepayrefundDataSql
                    );
                    $opfonepayrefund = $opfonepayrefundData ? $opfonepayrefundData[0]->total : 0;
                    $opnetfonepaytotal=$opfonepaybill+$opfonepayrefund;
                    $alltotalopnetfonepay +=$opnetfonepaytotal ?? '0';
                     /*End for op fonepay */

                    /*for ip card */
                    $ipcardbillDataSql = "select SUM(pbd.fldreceivedamt) as total
                    from tblpatbilldetail pbd
                    where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                    and pbd.flduserid LIKE '" . $r->flduserid . "'
                    and pbd.fldencounterval LIKE '%IP%'
                    and pbd.fldsave like '%1%'
                    and (pbd.payment_mode LIKE '%card%')
                    and (pbd.fldbillno  LIKE '%CAS%'  OR  pbd.fldbillno  LIKE '%PHM%' OR  pbd.fldbillno  LIKE '%REG%')
                    ";
                    if(!empty($department)){
                        $ipcardbillDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                    }
                    $ipcardbillData = DB::select(
                        $ipcardbillDataSql
                    );
                    $ipcardbill = $ipcardbillData ? $ipcardbillData[0]->total : 0;
                    $ipcardrefundDataSql = "select SUM(pbd.fldreceivedamt) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval  LIKE '%IP%'
                        and (pbd.payment_mode LIKE '%card%')
                        and pbd.fldbillno like '%RET%'
                        and pbd.fldsave like '%1%' ";
                        if(!empty($department)){
                            $ipcardrefundDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                        }
                    $ipcardrefundData = DB::select(
                        $ipcardrefundDataSql
                    );
                    $ipcardrefund = $ipcardrefundData ? $ipcardrefundData[0]->total : 0;

                    $ipcardnettotal = $ipcardbill + $ipcardrefund;
                    $totalipcardnettotal += $ipcardnettotal;
                    /*end of ip card */

                    /*for ip fonepay */
                    $ipfonepaybillDataSql = "select SUM(pbd.fldreceivedamt) as total
                    from tblpatbilldetail pbd
                    where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                    and pbd.flduserid LIKE '" . $r->flduserid . "'
                    and pbd.fldencounterval LIKE '%IP%'
                    and pbd.fldsave like '%1%'
                    and (pbd.payment_mode LIKE '%fonepay%')
                    and (pbd.fldbillno  LIKE '%CAS%'  OR  pbd.fldbillno  LIKE '%PHM%' OR  pbd.fldbillno  LIKE '%REG%')
                    ";
                    if(!empty($department)){
                        $ipfonepaybillDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                    }
                    $ipfonepaybillData = DB::select(
                        $ipfonepaybillDataSql
                    );
                    $ipfonepaybill = $ipfonepaybillData ? $ipfonepaybillData[0]->total : 0;
                    $ipfonepayrefundDataSql = "select SUM(pbd.fldreceivedamt) as total
                        from tblpatbilldetail pbd
                        where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                        and pbd.flduserid LIKE '" . $r->flduserid . "'
                        and pbd.fldencounterval  LIKE '%IP%'
                        and (pbd.payment_mode LIKE '%fonepay%')
                        and pbd.fldbillno like '%RET%'
                        and pbd.fldsave like '%1%' ";
                        if(!empty($department)){
                            $ipfonepayrefundDataSql .= " and pbd.fldcomp   ='" . $department . "' ";
                        }
                    $ipfonepayrefundData = DB::select(
                        $ipfonepayrefundDataSql
                    );
                    $ipfonepayrefund = $ipfonepayrefundData ? $ipfonepayrefundData[0]->total : 0;

                    $ipfonepaynettotal = $ipfonepaybill + $ipfonepayrefund;
                    $totalipfonepaynettotal += $ipfonepaynettotal;
                    /*end of ip fonepay */

                    //nettotal for op collection
                    $opnettotal_collection=$opnettotal+$opnetcardtotal+$opnetfonepaytotal;
                    $ipnettotal_collection=$ipnettotal+$ipfonepaybill+$ipfonepaynettotal;

                    $creditipnettotal = $ipcreditbill + $ipcreditrefund;
                    $totalcreditipnettotal += $creditipnettotal;
                    //

                    // $totalbillcollection = $opnettotal + $ipnettotal;
                    $totalbillcollection = $opnettotal_collection + $ipnettotal_collection;

                    $grandtotal = $totalbillcollection + $opdeposit + $opdepositref;
                    // dump($grandtotalrecevied);
                    // dump($grandtotal);


                    $finaltotalbillcollection += $totalbillcollection;

                    $finalgrandtotal += $grandtotal;

                    $grandtotalreceviedSql = "select SUM(pbd.fldreceivedamt) as total from tblpatbilldetail pbd
                    where pbd.fldtime >= '" . $request->eng_from_date . ' 00:00:00' . "' and  pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                    and pbd.flduserid LIKE '" . $r->flduserid . "' and pbd.fldsave like '%1%' ";
                    // dd($grandtotalreceviedSql);
                    if(!empty($department)){
                        $grandtotalreceviedSql .= " and pbd.fldcomp   ='" . $department . "' ";
                    }
                    $grandtotalrecevieddata = DB::select(
                        $grandtotalreceviedSql
                    );

                    $grandtotalrecevied = $grandtotalrecevieddata ? $grandtotalrecevieddata[0]->total : 0;

                    $userDepartment = CogentUsers::where('flduserid', $r->flduserid)->with('hospitalDepartment')->first();
                    $userDept = $userDepartment && isset($userDepartment->hospitalDepartment) && count($userDepartment->hospitalDepartment) ? $userDepartment->hospitalDepartment[0]->name : "";

                    $html .= '<tr>';
                    $html .= '<td>' . $r->flduserid . '</td>';
                    $html .= '<td>' . $userDept . '</td>';
                    $totalopcashbill += $opcashbill;
                    $html .= $opcashbill? '<td>' . Helpers::numberFormat($opcashbill) . '</td>':'<td>'.''.'</td>';

                    $html .= $opcashrefund?'<td>' . Helpers::numberFormat($opcashrefund) . '</td>':'<td>'.''.'</td>';
                    $totalopcashrefund += $opcashrefund;

                    $html .=$opnettotal?'<td>' . Helpers::numberFormat($opnettotal) . '</td>':'<td>'.''.'</td>';

                    //

                    //for card+
                    $html .= $opcardbill?'<td>' . Helpers::numberFormat($opcardbill) . '</td>':'<td>'.''.'</td>';
                    $totalopcardbill += $opcardbill;
                    //for op card refund
                    $html .=$opcardrefund ? '<td>' . Helpers::numberFormat($opcardrefund) . '</td>':'<td>'.''.'</td>';
                    $totalopcardrefund += $opcardrefund;
                    $html .=$opnetcardtotal?'<td>' . Helpers::numberFormat($opnetcardtotal) . '</td>':'<td>'.''.'</td>';
                    //for 0p fonepay
                    $html .= $opfonepaybill?'<td>' . Helpers::numberFormat($opfonepaybill) . '</td>':'<td>'.''.'</td>';
                    $totalopfonepaybill += $opfonepaybill;
                    $html .= $opfonepayrefund?'<td>' . Helpers::numberFormat($opfonepayrefund) . '</td>':'<td>'.''.'</td>';
                    $totalopfonepayrefund += $opfonepayrefund;
                    $html .= $opnetfonepaytotal?'<td>' . Helpers::numberFormat($opnetfonepaytotal) . '</td>':'<td>'.''.'</td>';
                    $html .=$opnettotal_collection?'<td>' . Helpers::numberFormat($opnettotal_collection) . '</td>':'<td>'.''.'</td>';
                    $totalopnettotal_collection += $opnettotal_collection;
                    //

                    $totalopcreditbill += $opcreditbill;
                    $html .=$opcreditbill?'<td>' . Helpers::numberFormat($opcreditbill) . '</td>':'<td>'.''.'</td>';
                    $html .= $opcreditrefund?'<td>' . Helpers::numberFormat($opcreditrefund) . '</td>':'<td>'.''.'</td>';
                    $totalopcreditrefund += $opcreditrefund;
                    $html .=$creditopnettotal?'<td>' . Helpers::numberFormat($creditopnettotal) . '</td>':'<td>'.''.'</td>';
                    //

                    $html .=$ipcashbill?'<td>' . Helpers::numberFormat($ipcashbill) . '</td>':'<td>'.''.'</td>';
                    $totalipcashbill += $ipcashbill;
                    $html .= $ipcashrefund?'<td>' . Helpers::numberFormat($ipcashrefund) . '</td>':'<td>'.''.'</td>';
                    $totalipcashrefund += $ipcashrefund;
                    $html .= $ipnettotal?'<td>' . Helpers::numberFormat($ipnettotal) . '</td>':'<td>'.''.'</td>';


                    //for ip card
                    $html .= $ipcardbill?'<td>' . Helpers::numberFormat($ipcardbill) . '</td>':'<td>'.''.'</td>';
                    $totalipcardbill += $ipcardbill;
                    $html .=$ipcardrefund? '<td>' . Helpers::numberFormat($ipcardrefund) . '</td>':'<td>'.''.'</td>';
                    $totalipcardrefund += $ipcardrefund;
                    $html .= $ipcardnettotal?'<td>' . Helpers::numberFormat($ipcardnettotal) . '</td>':'<td>'.''.'</td>';
                    // for of fonepay
                    $html .= $ipfonepaybill?'<td>' . Helpers::numberFormat($ipfonepaybill) . '</td>':'<td>'.''.'</td>';
                    $totalipfonepaybill += $ipfonepaybill;
                    $html .= $ipfonepayrefund?'<td>' . Helpers::numberFormat($ipfonepayrefund) . '</td>':'<td>'.''.'</td>';
                    $totalipfonepayrefund += $ipfonepayrefund;
                    $html .= $ipfonepaynettotal?'<td>' . Helpers::numberFormat($ipfonepaynettotal) . '</td>':'<td>'.''.'</td>';
                    $html .= $ipnettotal_collection?'<td>' . Helpers::numberFormat($ipnettotal_collection) . '</td>':'<td>'.''.'</td>';
                    $totalipnettotal_collection += $ipnettotal_collection;

                    //
                    $html .=$ipcreditbill?'<td>' . Helpers::numberFormat($ipcreditbill) . '</td>':'<td>'.''.'</td>';
                    $totalipcreditbill += $ipcreditbill;
                    $html .= $ipcreditrefund?'<td>' . Helpers::numberFormat($ipcreditrefund) . '</td>':'<td>'.''.'</td>';
                    $totalipcreditrefund += $ipcreditrefund;
                    $html .= $creditipnettotal?'<td>' . Helpers::numberFormat($creditipnettotal) . '</td>':'<td>'.''.'</td>';

                    $html .=$opdeposit?'<td>' . Helpers::numberFormat($opdeposit) . '</td>':'<td>'.''.'</td>';
                    $html .= $opdepositref?'<td>' . Helpers::numberFormat($opdepositref) . '</td>':'<td>'.''.'</td>';


                    $html .=$grandtotal?'<td>' . Helpers::numberFormat($grandtotal) . '</td>':'<td>'.''.'</td>';
                    $miscell = $grandtotalrecevied - $grandtotal;
                    // $html .= $grandtotalrecevied?'<td>' . Helpers::numberFormat($miscell) . '</td>':'<td>'.''.'</td>';
                    $totalmiscel += $miscell;
                    $html .= $grandtotalrecevied?'<td>' . Helpers::numberFormat($grandtotalrecevied) . '</td>':'<td>'.''.'</td>';
                    $totalgrandtotalrecevied += $grandtotalrecevied;
                    $html .= '</tr>';
                }

                $html .= '<tr>';
                $html .= '<td colspan="2">Grand Total</td>';
                $html .=$totalopcashbill? '<td>' . Helpers::numberFormat($totalopcashbill) . '</td>':'<td>'.''.'</td>';
                $html .= $totalopcashrefund?'<td>' . Helpers::numberFormat($totalopcashrefund) . '</td>':'<td>'.''.'</td>';
                $html .= $totalopnettotal?'<td>' . Helpers::numberFormat($totalopnettotal) . '</td>':'<td>'.''.'</td>';

                // for ip card
                $html .= $totalopcardbill?'<td>' . Helpers::numberFormat($totalopcardbill) . '</td>':'<td>'.''.'</td>';
                $html .= $totalopcardrefund?'<td>' . Helpers::numberFormat($totalopcardrefund) . '</td>':'<td>'.''.'</td>';
                $html .= $alltotalopnetcard?'<td>' . Helpers::numberFormat($alltotalopnetcard) . '</td>':'<td>'.''.'</td>';
                //for ip foepay
                $html .= $totalopfonepaybill?'<td>' . Helpers::numberFormat($totalopfonepaybill) . '</td>':'<td>'.''.'</td>';
                $html .= $totalopfonepayrefund?'<td>' . Helpers::numberFormat($totalopfonepayrefund) . '</td>':'<td>'.''.'</td>';
                $html .= $alltotalopnetfonepay?'<td>' . Helpers::numberFormat($alltotalopnetfonepay) . '</td>':'<td>'.''.'</td>';
                $html .= $totalopnettotal_collection?'<td>' . Helpers::numberFormat($totalopnettotal_collection) . '</td>':'<td>'.''.'</td>';
                //
                $html .= $totalopcreditbill?'<td>' . Helpers::numberFormat($totalopcreditbill) . '</td>':'<td>'.''.'</td>';
                $html .= $totalopcreditrefund?'<td>' . Helpers::numberFormat($totalopcreditrefund) . '</td>':'<td>'.''.'</td>';
                $html .= $totalcreditopnettotal?'<td>' . Helpers::numberFormat($totalcreditopnettotal) . '</td>':'<td>'.''.'</td>';

                $html .= $totalipcashbill?'<td>' . Helpers::numberFormat($totalipcashbill) . '</td>':'<td>'.''.'</td>';
                $html .= $totalipcashrefund?'<td>' . Helpers::numberFormat($totalipcashrefund) . '</td>':'<td>'.''.'</td>';
                $html .= $totalipnettotal?'<td>' . Helpers::numberFormat($totalipnettotal) . '</td>':'<td>'.''.'</td>';
                //for op card
                $html .= $totalipcardbill?'<td>' . Helpers::numberFormat($totalipcardbill) . '</td>':'<td>'.''.'</td>';
                $html .= $totalipcardrefund?'<td>' . Helpers::numberFormat($totalipcardrefund) . '</td>':'<td>'.''.'</td>';
                $html .= $totalipcardnettotal?'<td>' . Helpers::numberFormat($totalipcardnettotal) . '</td>':'<td>'.''.'</td>';
                //for op fonepay
                $html .= $totalipfonepaybill?'<td>' . Helpers::numberFormat($totalipfonepaybill) . '</td>':'<td>'.''.'</td>';
                $html .= $totalipfonepayrefund?'<td>' . Helpers::numberFormat($totalipfonepayrefund) . '</td>':'<td>'.''.'</td>';
                $html .= $totalipfonepaynettotal?'<td>' . Helpers::numberFormat($totalipfonepaynettotal) . '</td>':'<td>'.''.'</td>';
                $html .= $totalipnettotal_collection?'<td>' . Helpers::numberFormat($totalipnettotal_collection) . '</td>':'<td>'.''.'</td>';
                //
                $html .= $totalipcreditbill?'<td>' . Helpers::numberFormat($totalipcreditbill) . '</td>':'<td>'.''.'</td>';
                $html .= $totalipcreditrefund?'<td>' . Helpers::numberFormat($totalipcreditrefund) . '</td>':'<td>'.''.'</td>';
                $html .= $totalcreditipnettotal?'<td>' . Helpers::numberFormat($totalcreditipnettotal) . '</td>':'<td>'.''.'</td>';

                $html .= !empty($totalopdeposit[0])?'<td>' . Helpers::numberFormat(array_sum($totalopdeposit)) . '</td>':'<td>'.''.'</td>';
                $html .= !empty($totalopdepositref[0])?'<td>' . Helpers::numberFormat(array_sum($totalopdepositref)) . '</td>':'<td>'.''.'</td>';

                $html .= $finalgrandtotal?'<td>' . Helpers::numberFormat($finalgrandtotal) . '</td>':'<td>'.''.'</td>';
                // $html .= $totalmiscel?'<td>' . Helpers::numberFormat($totalmiscel) . '</td>':'<td>'.''.'</td>';
                $html .= $totalgrandtotalrecevied?'<td>' . Helpers::numberFormat($totalgrandtotalrecevied) . '</td>':'<td>'.''.'</td>';

                $html .= '</tr>';
            }

            return $html;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return $e;
        }
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function exportUserWiseCardCollectionReport(Request $request)
    {
        try {

            $data['resultdata'] = UserCollectionController::generateUserWiseCardPdf($request);
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
            $data['eng_from_date'] = $request->eng_from_date;
            $data['eng_to_date'] = $request->eng_to_date;
            return view('billing::userwisecardcash.pdf.user_wise_pdf_report', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/ ;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

}
