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
use App\Exports\UserCollectionExport;
use Session;
use Excel;

class CollectionReportController extends Controller
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

        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id', $user->id)->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        } else {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')->distinct('hospital_department_id')->with(['departmentData', 'departmentData.branchData'])->get();
        }

        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['hospital_departments'] = HospitalDepartment::where('status', 'active')->with('branchData')->get();
       // dd($data['hospital_departments']);
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('billing::collection', $data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchCollectionBillingDetail(Request $request)
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
                        and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%fonepay%' or pbd.payment_mode LIKE '%card%')
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
                        and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%fonepay%' or pbd.payment_mode LIKE '%card%')
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
                        and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%fonepay%' or pbd.payment_mode LIKE '%card%')
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
                        and (pbd.payment_mode LIKE '%cash%' or pbd.payment_mode LIKE '%cheque%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%credit%' or pbd.payment_mode LIKE '%fonepay%' or pbd.payment_mode LIKE '%card%')
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

                    // Credit op and ip
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

                    $creditipnettotal = $ipcreditbill + $ipcreditrefund;
                    $totalcreditipnettotal += $creditipnettotal;
                    //

                    $totalbillcollection = $opnettotal + $ipnettotal;

                    $grandtotal = $totalbillcollection + $opdeposit + $opdepositref;


                    $finaltotalbillcollection += $totalbillcollection;

                    $finalgrandtotal += $grandtotal;

                    $grandtotalreceviedSql = "select SUM(pbd.fldreceivedamt) as total from tblpatbilldetail pbd where pbd.fldtime >= '" . $request->eng_from_date . ' 00:00:00' . "' and  pbd.fldtime <='" . $request->eng_to_date . ' 23:59:59' . "' and pbd.flduserid LIKE '" . $r->flduserid . "' and pbd.fldsave like '%1%' ";
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
                    $totalipcashbill += $ipcashbill;
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
                    $miscell = $grandtotalrecevied - $grandtotal;

                    $totalmiscel += $miscell;
                    $html .= '<td>' .  Helpers::numberFormat($grandtotalrecevied) . '</td>';
                     $totalgrandtotalrecevied +=  $grandtotalrecevied;
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

                $html .= '<td>' . Helpers::numberFormat(array_sum($totalopdeposit)) . '</td>';
                $html .= '<td>' . Helpers::numberFormat(array_sum($totalopdepositref)) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($finalgrandtotal) . '</td>';

                $html .= '<td>' . Helpers::numberFormat($totalgrandtotalrecevied) . '</td>';

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
    public function exportUserCollectionReport(Request $request)
    {
        try {

            $data['resultdata'] = UserCollectionController::generatePdf($request);
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
            $data['eng_from_date'] = $request->eng_from_date;
            $data['eng_to_date'] = $request->eng_to_date;
            return view('billing::pdf.user-collection', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/ ;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    public function exportUserCollectionExcelReport(Request $request)
    {
        $export = new UserCollectionExport($request);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'user_collection.xlsx');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function exportBillingReport(Request $request)
    {
        try {

            // Helpers::jobRecord('fmSampReport', 'Laboratory Report');
            $from_date = Helpers::dateNepToEng($request->from_date);
            $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date;
            $to_date = Helpers::dateNepToEng($request->to_date);
            $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date;
            $search_type = $request->search_type;
            $search_text = $request->search_type_text;
            $department = $request->department;
            $search_name = $request->seach_name;
            $cash_credit = $request->cash_credit;
            $billingmode = $request->billing_mode;
            $report_type = $request->report_type;
            $item_type = $request->item_type;

            if ($search_type == 'enc' and $search_text != '') {
                $searchquery = 'and pbd.fldencounterval like "' . $search_text . '"';
            } else if ($search_type == 'user' and $search_text != '') {
                $searchquery = 'and pbd.flduserid like "' . $search_text . '"';
            } else if ($search_type == 'invoice' and $search_text != '') {
                $searchquery = 'and pbd.fldbillno like "' . $search_text . '"';
            } else {
                $searchquery = '';
            }

            if ($department != '%') {
                $departmentquery = 'where pbd.hospital_department_id =' . $department;
            } else {
                $departmentquery = '';
            }

            if ($search_name != '') {
                $searchnamequery = 'and pbd.fldencounterval in (select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptnamefir like "' . $search_name . '%"))';
            } else {
                $searchnamequery = '';
            }
            if ($cash_credit != '%') {
                $cashquery = 'and pbd.fldbilltype like "' . $cash_credit . '"';
            } else {
                $cashquery = '';
            }

            if ($billingmode != '%') {
                $billingmodequery = 'and pbd.fldencounterval in(select e.fldencounterval from tblencounter as e WHERE e.fldbillingmode like "' . $billingmode . '")';
            } else {
                $billingmodequery = '';
            }

            if ($report_type != '%') {
                $reporttypequery = 'and pbd.fldbillno like "' . $report_type . '%"';
            } else {
                $reporttypequery = '';
            }

            if ($item_type != '%') {
                $itemtypequery = 'and pbd.fldbillno in(select pb.fldbillno from tblpatbilling as pb where pb.flditemtype like "' . $item_type . '")';
            } else {
                $itemtypequery = '';
            }
            $sql = 'select pbd.fldtime,pbd.fldbillno,pbd.fldencounterval,pbd.fldprevdeposit,pbd.flditemamt,pbd.fldtaxamt,pbd.flddiscountamt,pbd.fldchargedamt,pbd.fldreceivedamt,pbd.fldcurdeposit,pbd.flduserid,pbd.fldbilltype,pbd.fldtaxamt,pbd.flddiscountamt,pbd.fldbankname,pbd.fldchequeno,pbd.fldtaxgroup,pbd.flddiscountgroup,pbd.hospital_department_id from tblpatbilldetail as pbd where pbd.fldtime>="' . $finalfrom . '" and pbd.fldtime<="' . $finalto . '"' . $departmentquery . $searchquery . $searchnamequery . $reporttypequery . $cashquery . $itemtypequery . $billingmodequery;

            $result = DB::select(
                $sql
            );
            $data['result'] = $result;
            $data['from_date'] = $finalfrom;
            $data['to_date'] = $finalto;

            return view('billing::pdf.billing-report', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/ ;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    public function generateInvoice(Request $request)
    {
        try {
            $countdata = PatBillCount::where('fldbillno', $request->billno)->first();
            $updatedata['fldcount'] = $countdata->fldcount + 1;
            PatBillCount::where('fldid', $countdata->fldid)->update($updatedata);
            $data['billdetail'] = $billdetail = PatBillDetail::where('fldbillno', $request->billno)->first();
            $data['itemdata'] = PatBilling::where('fldbillno', $request->billno)->get();
            $data['enpatient'] = Encounter::where('fldencounterval', $billdetail->fldencounterval)->with('patientInfo')->first();
            return view('billing::pdf.billing-invoice', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/ ;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }

    public function arrayPaginator($array, $request)
    {
        $page = $request->get('page', 1);
        $perPage = 10;
        $offset = ($page * $perPage) - $perPage;
        // echo $query; exit;
        return new LengthAwarePaginator(
            array_slice($array, $offset, $perPage, true),
            count($array),
            $perPage,
            $page,
            ['path' => $request->url()]
        );
    }
}
