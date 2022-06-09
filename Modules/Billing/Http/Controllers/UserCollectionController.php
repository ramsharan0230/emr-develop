<?php

namespace Modules\Billing\Http\Controllers;

use App\CogentUsers;
use App\PatBillDetail;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Utils\Helpers;

class UserCollectionController extends Controller
{
    public static function generatePdf($request)
    {
        try {
            $result = PatBillDetail::select('flduserid', 'fldcomp')
            ->when($request->department != "" && $request->department != null, function ($q) use ($request) {
                return $q->where('fldcomp',$request->department);
            })->groupBy('flduserid')->get();

            $html = '';

            $totalopcashbill =   $totalopdeposit = $totalopdepositref =   array();
            $totalipnettotal =0;
            $finaltotalbillcollection=0;
            $finalgrandtotal =0;
            $totalopcashbill=0;
            $totalopcashrefund=0;
            $totalopnettotal=0;
            $totalipcashbill  = 0;
            $totalipcashrefund =0;

            $totalopcreditbill = $totalcreditopdeposit = $totalcreditopdepositref = array();
            $totalcreditipnettotal = 0;
            $totalopcreditbill = 0;
            $totalopcreditrefund = 0;
            $totalcreditopnettotal = 0;
            $totalipcreditbill = 0;
            $totalipcreditrefund = 0;

            $miscell = 0;
            $grandtotalrecevied = 0;
            $totalgrandtotalrecevied=0;
            $totalmiscel=0;
            $department = $request->department ? $request->department : '';

            if (isset($result) && count($result) > 0) {
                foreach ($result as $k => $r) {
                    $opcashbillDataSql = "select SUM(pbd.fldreceivedamt) as total
                    from tblpatbilldetail pbd
                    where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                    and pbd.flduserid LIKE '" . $r->flduserid . "'
                    and pbd.fldencounterval NOT LIKE '%IP%'
                    and pbd.fldsave LIKE '%1%'
                    and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%fonepay%' or pbd.payment_mode LIKE '%card%')
                    and (pbd.fldbillno  LIKE '%CAS%'  OR  pbd.fldbillno  LIKE '%REG%'  OR pbd.fldbillno  LIKE '%PHM%' OR (pbd.fldbillno  LIKE '%CRE%' AND pbd.fldreceivedamt > 0))
                    ";
                if(!empty($department)){
                    $opcashbillDataSql .= " and pbd.fldcomp  ='" . $department . "' ";
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
                and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%fonepay%' or pbd.payment_mode LIKE '%card%')
                and pbd.fldbillno LIKE '%RET%'
                ";
                if(!empty($department)){
                    $opcashrefundDataSql .= " and pbd.fldcomp  ='" . $department . "' ";
                }
                $opcashrefundData =  DB::select(
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
                    $opdepositDataSql .= " and fldcomp  ='" . $department . "' ";
                }
                $opdepositData = DB::select(
                    $opdepositDataSql
                );
                $totalopdeposit[] = $opdeposit = $opdepositData ? $opdepositData[0]->totaldepo : 0;

                $opdepositrefDataSql = "select sum(fldreceivedamt) as totalrefund from tblpatbilldetail
                        where fldbillno like '%dep%' and fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and fldtime <='" . $request->eng_to_date . ' 23:59:59' . "' and flduserid LIKE '" . $r->flduserid . "'
                        and fldreceivedamt LIKE '-%'";
                if(!empty($department)){
                    $opdepositrefDataSql .= " and fldcomp  ='" . $department . "' ";
                }
                $opdepositrefData = DB::select(
                    $opdepositrefDataSql
                );
                $totalopdepositref[] = $opdepositref = $opdepositrefData ? $opdepositrefData[0]->totalrefund : 0;
                $opnettotal = $opcashbill + $opcashrefund;
                $totalopnettotal += $opnettotal ?? 0;

                $ipcashbillDataSql = "select SUM(pbd.fldreceivedamt) as total
                from tblpatbilldetail pbd
                where pbd.fldtime >= '" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                and pbd.flduserid LIKE '" . $r->flduserid . "'
                and pbd.fldencounterval LIKE '%IP%'
                and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%fonepay%' or pbd.payment_mode LIKE '%card%')
                and (pbd.fldbillno  LIKE '%CAS%'  OR  pbd.fldbillno  LIKE '%PHM%' OR  pbd.fldbillno  LIKE '%REG%' OR (pbd.fldbillno  LIKE '%CRE%' AND pbd.fldreceivedamt > 0))
                ";
                if(!empty($department)){
                    $ipcashbillDataSql .= " and pbd.fldcomp  ='" . $department . "' ";
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
                and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%fonepay%' or pbd.payment_mode LIKE '%card%')
                and pbd.fldbillno like '%RET%'
                and pbd.fldsave like '%1%' ";
                if(!empty($department)){
                    $ipcashrefundDataSql .= " and fldcomp  ='" . $department . "' ";
                }
                $ipcashrefundData =  DB::select(
                    $ipcashrefundDataSql
                );
                //op collection ko net total + Ip collection ko net total = total bill collection
                //total bill collection plus deposit - deposit refund = Gran total collection
                $ipcashrefund = $ipcashrefundData ?  $ipcashrefundData[0]->total : 0;

                $ipnettotal = $ipcashbill + $ipcashrefund;
                $totalipnettotal += $ipnettotal;

                    // Credit op and ip
                $opcreditbillDataSql = "select SUM(pbd.fldcurdeposit) as total
                    from tblpatbilldetail pbd
                    where pbd.fldtime >='" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                    and pbd.flduserid LIKE '" . $r->flduserid . "'
                    and pbd.fldencounterval NOT LIKE '%IP%'
                    and pbd.fldsave LIKE '%1%'
                    and (pbd.fldbilltype  LIKE '%Credit%')
                    and (pbd.payment_mode LIKE 'Credit' OR pbd.payment_mode LIKE 'credit')
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
                $ipcreditrefundData = DB::select(
                    $ipcreditrefundDataSql
                );
                //op collection ko net total + Ip collection ko net total = total bill collection
                //total bill collection plus deposit - deposit refund = Gran total collection
                $ipcreditrefund = $ipcreditrefundData ? $ipcreditrefundData[0]->total : 0;

                $creditipnettotal = $ipcreditbill + $ipcreditrefund;
                $totalcreditipnettotal += $creditipnettotal;
                //

                $totalbillcollection = $opnettotal + $ipnettotal;

                $grandtotal =  $totalbillcollection + $opdeposit + $opdepositref;


                $finaltotalbillcollection += $totalbillcollection;

                $finalgrandtotal += $grandtotal ;

                $grandtotalreceviedSql = "select SUM(pbd.fldreceivedamt) as total
                from tblpatbilldetail pbd
                where pbd.fldtime >= '" . $request->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "'
                and pbd.flduserid LIKE '" . $r->flduserid . "'

                and pbd.fldsave like '%1%' ";

                if(!empty($department)){
                    $grandtotalreceviedSql .= " and pbd.fldcomp  ='" . $department . "' ";
                }

                $grandtotalrecevieddata =  DB::select(
                    $grandtotalreceviedSql
                );

                $grandtotalrecevied =  $grandtotalrecevieddata ?  $grandtotalrecevieddata[0]->total : 0;

                $userDepartment = CogentUsers::where('flduserid', $r->flduserid)->with('hospitalDepartment')->first();
                $userDept = $userDepartment && isset($userDepartment->hospitalDepartment) && count($userDepartment->hospitalDepartment) ? $userDepartment->hospitalDepartment[0]->name : "";

                    $html .= '<tr>';
                    $html .= '<td>' . $r->flduserid . '</td>';
                    $html .= '<td>' . $userDept . '</td>';
                    $totalopcashbill += $opcashbill;
                    $html .= '<td>' . Helpers::numberFormat($opcashbill) . '</td>';

                    $html .= '<td>' . Helpers::numberFormat($opcashrefund) . '</td>';
                    $totalopcashrefund += $opcashrefund;

                    $html .= '<td>' . Helpers::numberFormat($opnettotal) . '</td>';

                    //
                    $totalopcreditbill += $opcreditbill;
                    $html .= '<td>' . Helpers::numberFormat($opcreditbill) . '</td>';

                    $html .= '<td>' . Helpers::numberFormat($opcreditrefund) . '</td>';
                    $totalopcreditrefund += $opcreditrefund;

                    $html .= '<td>' . Helpers::numberFormat($creditopnettotal) . '</td>';
                    //

                    $html .= '<td>' . Helpers::numberFormat($ipcashbill) . '</td>';
                    $totalipcashbill  +=$ipcashbill ;
                    $html .= '<td>' . Helpers::numberFormat($ipcashrefund) . '</td>';

                    $totalipcashrefund += $ipcashrefund;
                    $html .= '<td>' . Helpers::numberFormat($ipnettotal) . '</td>';

                    //
                    $html .= '<td>' . Helpers::numberFormat($ipcreditbill) . '</td>';
                    $totalipcreditbill += $ipcreditbill;
                    $html .= '<td>' . Helpers::numberFormat($ipcreditrefund) . '</td>';

                    $totalipcreditrefund += $ipcreditrefund;
                    $html .= '<td>' . Helpers::numberFormat($creditipnettotal) . '</td>';
                    //

                    $html .= '<td>' . Helpers::numberFormat($opdeposit) . '</td>';
                    $html .= '<td>' . Helpers::numberFormat($opdepositref) . '</td>';


                    $html .= '<td>' . Helpers::numberFormat($grandtotal) . '</td>';
                    $miscell =  ($grandtotalrecevied - $grandtotal);
                    $html .= '<td>' .Helpers::numberFormat($miscell) . '</td>';
                    $totalmiscel += $miscell;
                    $html .= '<td>' . Helpers::numberFormat($grandtotalrecevied) . '</td>';
                    $totalgrandtotalrecevied += $grandtotalrecevied;
                    $html .= '</tr>';
                }


                $html .= '<tr>';
                $html .= '<td colspan="2">Grand Total</td>';
                $html .= '<td>' . Helpers::numberFormat($totalopcashbill) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($totalopcashrefund) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($totalopnettotal) . '</td>';

                //
                $html .= '<td>' . Helpers::numberFormat($totalopcreditbill) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($totalopcreditrefund) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($totalcreditopnettotal) . '</td>';
                //

                $html .= '<td>' . Helpers::numberFormat($totalipcashbill) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($totalipcashrefund) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($totalipnettotal) . '</td>';

                //
                $html .= '<td>' . Helpers::numberFormat($totalipcreditbill) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($totalipcreditrefund) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($totalcreditipnettotal) . '</td>';
                //

                $html .= '<td>' . Helpers::numberFormat(array_sum($totalopdeposit)). '</td>';
                $html .= '<td>' . Helpers::numberFormat(array_sum($totalopdepositref)) . '</td>';


                $html .= '<td>' . Helpers::numberFormat($finalgrandtotal) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalmiscel) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalgrandtotalrecevied) . '</td>';

                $html .= '</tr>';
            }

            return $html;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function generateUserWiseCardPdf($request){
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
                    $opdepositrefData = DB::select($opdepositrefDataSql);
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
                    $ipcashbillData = DB::select($ipcashbillDataSql);
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


                    $finaltotalbillcollection += $totalbillcollection;

                    $finalgrandtotal += $grandtotal;

                    $grandtotalreceviedSql = "select SUM(pbd.fldreceivedamt) as total from tblpatbilldetail pbd where pbd.fldtime >= '" . $request->eng_from_date . ' 00:00:00' . "' and  pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "' and pbd.flduserid LIKE '" . $r->flduserid . "' and pbd.fldsave like '%1%' ";
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
                    $html .= '<td>' . Helpers::numberFormat($opcashbill) . '</td>';

                    $html .= '<td>' . Helpers::numberFormat($opcashrefund) . '</td>';
                    $totalopcashrefund += $opcashrefund;

                    $html .= '<td>' . Helpers::numberFormat($opnettotal) . '</td>';

                    //

                    //for card+
                    $html .= '<td>' . Helpers::numberFormat($opcardbill) . '</td>';
                    $totalopcardbill += $opcardbill;
                    //for op card refund
                    $html .= '<td>' . Helpers::numberFormat($opcardrefund) . '</td>';
                    $totalopcardrefund += $opcardrefund;
                    $html .= '<td>' . Helpers::numberFormat($opnetcardtotal) . '</td>';
                    //for 0p fonepay
                    $html .= '<td>' . Helpers::numberFormat($opfonepaybill) . '</td>';
                    $totalopfonepaybill += $opfonepaybill;
                    $html .= '<td>' . Helpers::numberFormat($opfonepayrefund) . '</td>';
                    $totalopfonepayrefund += $opfonepayrefund;
                    $html .= '<td>' . Helpers::numberFormat($opnetfonepaytotal) . '</td>';
                    $html .= '<td>' . Helpers::numberFormat($opnettotal_collection) . '</td>';
                    $totalopnettotal_collection += $opnettotal_collection;
                    //

                    $totalopcreditbill += $opcreditbill;
                    $html .= '<td>' . Helpers::numberFormat($opcreditbill) . '</td>';
                    $html .= '<td>' . Helpers::numberFormat($opcreditrefund) . '</td>';
                    $totalopcreditrefund += $opcreditrefund;
                    $html .= '<td>' . Helpers::numberFormat($creditopnettotal) . '</td>';
                    //

                    $html .= '<td>' . Helpers::numberFormat($ipcashbill) . '</td>';
                    $totalipcashbill += $ipcashbill;
                    $html .= '<td>' . Helpers::numberFormat($ipcashrefund) . '</td>';
                    $totalipcashrefund += $ipcashrefund;
                    $html .= '<td>' . Helpers::numberFormat($ipnettotal) . '</td>';


                    //for ip card
                    $html .= '<td>' . Helpers::numberFormat($ipcardbill) . '</td>';
                    $totalipcardbill += $ipcardbill;
                    $html .= '<td>' . Helpers::numberFormat($ipcardrefund) . '</td>';
                    $totalipcardrefund += $ipcardrefund;
                    $html .= '<td>' . Helpers::numberFormat($ipcardnettotal) . '</td>';
                    // for of fonepay
                    $html .= '<td>' . Helpers::numberFormat($ipfonepaybill) . '</td>';
                    $totalipfonepaybill += $ipfonepaybill;
                    $html .= '<td>' . Helpers::numberFormat($ipfonepayrefund) . '</td>';
                    $totalipfonepayrefund += $ipfonepayrefund;
                    $html .= '<td>' . Helpers::numberFormat($ipfonepaynettotal) . '</td>';
                    $html .= '<td>' . Helpers::numberFormat($ipnettotal_collection) . '</td>';
                    $totalipnettotal_collection += $ipnettotal_collection;

                    //
                    $html .= '<td>' . Helpers::numberFormat($ipcreditbill) . '</td>';
                    $totalipcreditbill += $ipcreditbill;
                    $html .= '<td>' . Helpers::numberFormat($ipcreditrefund) . '</td>';
                    $totalipcreditrefund += $ipcreditrefund;
                    $html .= '<td>' . Helpers::numberFormat($creditipnettotal) . '</td>';

                    $html .= '<td>' . Helpers::numberFormat($opdeposit) . '</td>';
                    $html .= '<td>' . Helpers::numberFormat($opdepositref) . '</td>';


                    $html .= '<td>' . Helpers::numberFormat($grandtotal) . '</td>';
                    $miscell = $grandtotalrecevied - $grandtotal;
                    $html .= '<td>' . Helpers::numberFormat($miscell) . '</td>';
                    $totalmiscel += $miscell;
                    $html .= '<td>' . Helpers::numberFormat($grandtotalrecevied) . '</td>';
                    $totalgrandtotalrecevied += $grandtotalrecevied;
                    $html .= '</tr>';
                }

                $html .= '<tr>';
                $html .= '<td colspan="2">Grand Total</td>';
                $html .= '<td>' . Helpers::numberFormat($totalopcashbill) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalopcashrefund) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalopnettotal) . '</td>';

                // for ip card
                $html .= '<td>' . Helpers::numberFormat($totalopcardbill) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalopcardrefund) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($alltotalopnetcard) . '</td>';
                //for ip foepay
                $html .= '<td>' . Helpers::numberFormat($totalopfonepaybill) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalopfonepayrefund) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($alltotalopnetfonepay) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalopnettotal_collection) . '</td>';
                //
                $html .= '<td>' . Helpers::numberFormat($totalopcreditbill) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalopcreditrefund) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalcreditopnettotal) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($totalipcashbill) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalipcashrefund) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalipnettotal) . '</td>';
                //for op card
                $html .= '<td>' . Helpers::numberFormat($totalipcardbill) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalipcardrefund) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalipcardnettotal) . '</td>';
                //for op fonepay
                $html .= '<td>' . Helpers::numberFormat($totalipfonepaybill) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalipfonepayrefund) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalipfonepaynettotal) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalipnettotal_collection) . '</td>';
                //
                $html .= '<td>' . Helpers::numberFormat($totalipcreditbill) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalipcreditrefund) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalcreditipnettotal) . '</td>';

                $html .= '<td>' . Helpers::numberFormat(array_sum($totalopdeposit)) . '</td>';
                $html .= '<td>' . Helpers::numberFormat(array_sum($totalopdepositref)) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($finalgrandtotal) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalmiscel) . '</td>';
                $html .= '<td>' . Helpers::numberFormat($totalgrandtotalrecevied) . '</td>';

                $html .= '</tr>';
            }

            return $html;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return $e;
        }
    }
}
