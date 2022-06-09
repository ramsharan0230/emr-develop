<?php

namespace App\Exports;

use App\Encounter;
use App\PatBillDetail;
use App\CogentUsers;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
// use Maatwebsite\Excel\Concerns\WithMapping;

class UserCollectionExport implements FromView,ShouldAutoSize
{
    public function __construct($request)
    {
        $this->department=$request->department;
        $this->eng_from_date=$request->eng_from_date;
        $this->eng_to_date=$request->eng_to_date;
    }



    public function view(): View
    {
        // try {
            $departments=$this->department;
            $result = PatBillDetail::select('flduserid', 'fldcomp')
            ->when($this->department != "" && $this->department != null, function ($q) use ($departments) {
                return $q->where('fldcomp',$departments);
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
            $department = $this->department ? $this->department : '';

            if (isset($result) && count($result) > 0) {
                foreach ($result as $k => $r) {
                    $opcashbillDataSql = "select SUM(pbd.fldreceivedamt) as total
                    from tblpatbilldetail pbd
                    where pbd.fldtime >='" . $this->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $this->eng_to_date . ' 23:59:59' . "'
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
                where pbd.fldtime >='" . $this->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $this->eng_to_date . ' 23:59:59' . "'
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
                    where fldbillno like '%dep%' and fldtime >='" . $this->eng_from_date . ' 00:00:00' . "' and fldtime <='" . $this->eng_to_date . ' 23:59:59' . "'
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
                        where fldbillno like '%dep%' and fldtime >='" . $this->eng_from_date . ' 00:00:00' . "' and fldtime <='" . $this->eng_to_date . ' 23:59:59' . "' and flduserid LIKE '" . $r->flduserid . "'
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
                where pbd.fldtime >= '" . $this->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $this->eng_to_date . ' 23:59:59' . "'
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
                where pbd.fldtime >='" . $this->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $this->eng_to_date . ' 23:59:59' . "'
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
                    where pbd.fldtime >='" . $this->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $this->eng_to_date . ' 23:59:59' . "'
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
                    where pbd.fldtime >='" . $this->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $this->eng_to_date . ' 23:59:59' . "'
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
                    where pbd.fldtime >='" . $this->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $this->eng_to_date . ' 23:59:59' . "'
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
                    where pbd.fldtime >='" . $this->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $this->eng_to_date . ' 23:59:59' . "'
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
                where pbd.fldtime >= '" . $this->eng_from_date . ' 00:00:00' . "' and pbd.fldtime <='" . $this->eng_to_date . ' 23:59:59' . "'
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
                    $html .= '<td>' . str_replace("&", "And", $userDept) . '</td>';
                    $totalopcashbill += $opcashbill;
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($opcashbill) . '</td>';

                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($opcashrefund) . '</td>';
                    $totalopcashrefund += $opcashrefund;

                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($opnettotal) . '</td>';

                    //
                    $totalopcreditbill += $opcreditbill;
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($opcreditbill) . '</td>';

                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($opcreditrefund) . '</td>';
                    $totalopcreditrefund += $opcreditrefund;

                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($creditopnettotal) . '</td>';
                    //

                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($ipcashbill) . '</td>';
                    $totalipcashbill  +=$ipcashbill ;
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($ipcashrefund) . '</td>';

                    $totalipcashrefund += $ipcashrefund;
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($ipnettotal) . '</td>';

                    //
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($ipcreditbill) . '</td>';
                    $totalipcreditbill += $ipcreditbill;
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($ipcreditrefund) . '</td>';

                    $totalipcreditrefund += $ipcreditrefund;
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($creditipnettotal) . '</td>';
                    //

                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($opdeposit) . '</td>';
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($opdepositref) . '</td>';


                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($grandtotal) . '</td>';
                    $miscell =  ($grandtotalrecevied - $grandtotal);
                    $html .= '<td>' .\App\Utils\Helpers::numberFormat($miscell) . '</td>';
                    $totalmiscel += $miscell;
                    $html .= '<td>' . \App\Utils\Helpers::numberFormat($grandtotalrecevied) . '</td>';
                    $totalgrandtotalrecevied += \App\Utils\Helpers::numberFormat($grandtotalrecevied);
                    $html .= '</tr>';
                }


                $html .= '<tr>';
                $html .= '<td colspan="2">Grand Total</td>';
                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalopcashbill) . '</td>';

                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalopcashrefund) . '</td>';

                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalopnettotal) . '</td>';

                //
                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalopcreditbill) . '</td>';

                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalopcreditrefund) . '</td>';

                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalcreditopnettotal) . '</td>';
                //

                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalipcashbill) . '</td>';

                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalipcashrefund) . '</td>';

                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalipnettotal) . '</td>';

                //
                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalipcreditbill) . '</td>';

                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalipcreditrefund) . '</td>';

                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalcreditipnettotal) . '</td>';
                //

                $html .= '<td>' . \App\Utils\Helpers::numberFormat(array_sum($totalopdeposit)). '</td>';
                $html .= '<td>' . \App\Utils\Helpers::numberFormat(array_sum($totalopdepositref)) . '</td>';


                $html .= '<td>' . \App\Utils\Helpers::numberFormat($finalgrandtotal) . '</td>';
                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalmiscel) . '</td>';
                $html .= '<td>' . \App\Utils\Helpers::numberFormat($totalgrandtotalrecevied) . '</td>';

                $html .= '</tr>';
            }
            return view('billing::excel.collection-report-export',compact('html'));
            // return $html;
        // } catch (\Exception $e) {
        //     return $e;
        // }
    }

}
