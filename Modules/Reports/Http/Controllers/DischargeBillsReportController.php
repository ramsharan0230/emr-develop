<?php

namespace Modules\Reports\Http\Controllers;

use App\Encounter;
use App\Exports\DishchargeBillExport;
use App\PatBillDetail;
use App\Utils\Helpers;
use Carbon\Carbon;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class DischargeBillsReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        $data['hospital_department'] = Helpers::getDepartmentAndComp();
        return view('reports::dischargeBillsReport.discharge-bills-report', $data);
    }

    public function getRefreshData(Request $request)
    {
        $from_date = Helpers::dateNepToEng($request->from_date);
        $data['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
        $to_date = Helpers::dateNepToEng($request->to_date);
        $data['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
        $results = Encounter::select(DB::raw('tblencounter.fldencounterval,tblencounter.fldpatientval,tblencounter.flddod,tblencounter.flddoa,
                        GROUP_CONCAT(tblpatbilldetail.fldbillno) as fldbillno,GROUP_CONCAT(tblpatbilldetail.remarks) as reason,
                        SUM(tblpatbilldetail.fldprevdeposit) as deposit_amt,SUM(tblpatbilldetail.flditemamt) as item_amt,
                        SUM(tblpatbilldetail.fldtaxamt) as tax_amt,SUM(tblpatbilldetail.flddiscountamt) as discount_amt,
                        tblpatbilldetail.fldreceivedamt,SUM(tblpatbilldetail.fldcurdeposit) as current_deposit'))
                        ->leftJoin('tblpatbilldetail','tblpatbilldetail.fldencounterval','=','tblencounter.fldencounterval')
                        ->when(($finalfrom == $finalto) && $finalfrom != "" && $finalto != "", function ($q) use ($finalfrom) {
                            return $q->where(DB::raw("(STR_TO_DATE(tblencounter.flddod,'%Y-%m-%d'))"),$finalfrom);
                        })
                        ->when(($finalfrom != $finalto) && $finalfrom != "", function ($q) use ($finalfrom) {
                            return $q->where('tblencounter.flddod', '>=', $finalfrom);
                        })
                        ->when(($finalfrom != $finalto) && $finalto != "", function ($q) use ($finalto) {
                            return $q->where('tblencounter.flddod', "<=", $finalto);
                        })
                        ->when($request->comp != "%", function ($q) use ($request) {
                            return $q->where('tblpatbilldetail.fldcomp', 'like', $request->comp);
                        })
                        ->when($request->serviceType == "pharmacy", function ($q){
                            return $q->where(function ($query) {
                                $query->orwhere('tblpatbilldetail.fldbillno', 'LIKE', 'PHM%');
                                $query->orwhere('tblpatbilldetail.fldpayitemname', 'LIKE', '%Pharmacy Deposit%');
                            });
                            // return $q->where('tblpatbilldetail.fldbillno', 'like', "PHM%");
                        })
                        ->when($request->serviceType == "service", function ($q){
                            return $q->where('tblpatbilldetail.fldbillno', 'not like', "PHM%");
                        })
                        ->groupBy('tblencounter.fldencounterval');

        $results = ($request->has('isExport')) ? $results->get() : $results->paginate(15);

        $data['results'] = $results;
        $html = "";
        $html_pdf = "";
        $sn = 0;
        foreach ($results as $key => $r) {
            if($request->comp != "%") {
                $compQuery = "and fldcomp like ".$request->comp;
            }else{
                $compQuery = "";
            }
            if($request->serviceType == "pharmacy"){
                $serviceQuery = "";
                // $serviceQuery = "fldbillno like 'PHM%'";
                $payItemNameQuery = "fldpayitemname like '%Pharmacy Deposit%'";
                $depositDataSql = "select sum(fldreceivedamt) as totaldepo,GROUP_CONCAT(fldbillno) as fldbillno
                        from tblpatbilldetail where fldencounterval like
                        '".$r->fldencounterval."' and (fldpayitemname like 'Pharmacy Deposit' or fldpayitemname like 'pharmacy deposit')".$serviceQuery." ".$compQuery;
                $depositRefundQuery = "fldpayitemname like 'Pharmacy Deposit Refund' or fldpayitemname like 'pharmacy deposit refund'";

            }else{
                $serviceQuery = " and fldbillno not like 'PHM%'";
                $payItemNameQuery = "fldpayitemname like '%admission deposit%' or
                fldpayitemname like 'op deposit' or fldpayitemname like '%re deposit%' or fldpayitemname
                like '%blood bank%'or fldpayitemname like '%gate pass%' or fldpayitemname like
                '%post-up%'";
                $depositDataSql = "select sum(fldreceivedamt) as totaldepo,GROUP_CONCAT(fldbillno) as fldbillno
                        from tblpatbilldetail where fldbillno like '%dep%' and fldencounterval like
                        '".$r->fldencounterval."' and (".$payItemNameQuery.") ".$serviceQuery." ".$compQuery;
                $depositRefundQuery = "fldpayitemname like 'deposit refund' or fldpayitemname like 'Deposit Refund'";
            }

            // $depositDataSql = "select sum(fldreceivedamt) as totaldepo,GROUP_CONCAT(fldbillno) as fldbillno
            //             from tblpatbilldetail where fldbillno like '%dep%' and fldencounterval like
            //             '".$r->fldencounterval."' and (".$payItemNameQuery.") ".$serviceQuery." ".$compQuery;
            $depositData = DB::select(
                $depositDataSql
            );

            $deposit = $depositData ? $depositData[0]->totaldepo : 0;
            $depositBills = $depositData ? $depositData[0]->fldbillno : "";
            $depositBillsView = explode(",",$depositBills);
            $depositrefDataSql = "select sum(fldreceivedamt) as totalrefund,GROUP_CONCAT(fldbillno) as fldbillno
                        from tblpatbilldetail where fldbillno like '%dep%' and (".$depositRefundQuery.") and fldencounterval like '".$r->fldencounterval."'";
            $depositrefData = DB::select(
                $depositrefDataSql
            );
            $depositref = $depositrefData ? $depositrefData[0]->totalrefund : 0;
            $depositrefBills = $depositrefData ? $depositrefData[0]->fldbillno : "";
            $depositrefBillsView = explode(",",$depositrefBills);

            $explodes = explode(",",$r->fldbillno);
            $invoiceBill = [];
            $totNetBillAmt = $r->item_amt - $r->discount_amt + $r->tax_amt;
            foreach($explodes as $explode){
                if (!str_starts_with($explode, 'DEP')) {
                    array_push($invoiceBill,$explode);
                }
            }
            if($deposit < $totNetBillAmt){
                $adjustmentAmt = $totNetBillAmt - $deposit;
            }else{
                $adjustmentAmt = 0;
            }
            if($deposit > ($totNetBillAmt - $r->discount_amt)){
                $remainingRefund = $deposit - $totNetBillAmt - $r->discount_amt;
            }else{
                $remainingRefund = 0;
            }
            //dd($invoiceBill);
            $html .= '<tr>
                        <td>' . ++$key . '</td>
                        <td>' . '<ul><li>'.$r->fldpatientval.'/'.$r->fldencounterval.'</li><li>'.$r->patientInfo->fldrankfullname.'</li></ul>' .'</td>
                        <td>' . implode("<br>",array_unique($depositBillsView)) . '</td>
                        <td>' . implode("<br>",array_unique($invoiceBill)) . '</td>
                        <td>' . implode("<br>",array_unique($depositrefBillsView)) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($deposit)) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($totNetBillAmt)) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($adjustmentAmt)) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($r->discount_amt)). '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($depositref)) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($remainingRefund)) . '</td>
                        <td>' . \Carbon\Carbon::parse($r->flddoa)->format('Y-m-d') . '</td>
                        <td>' . \Carbon\Carbon::parse($r->flddod)->format('Y-m-d') . '</td>
                        <td>' . $r->reason . '</td>';

            $html .= '</tr>';


            $html_pdf .= '<tr>
                        <td>' . ++$sn . '</td>
                        <td>' . $r->fldencounterval .'</td>
                        <td class="text-left">' . $r->patientInfo->fldrankfullname .'</td>
                        <td class="text-left">' . $depositBills . '</td>
                        <td class="text-left">' . implode(",",array_unique($invoiceBill)) . '</td>
                        <td class="text-left">' . $depositrefBills . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($deposit)) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($totNetBillAmt)) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($adjustmentAmt)) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($r->discount_amt)). '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($depositref)) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat(($remainingRefund)) . '</td>
                        <td>' . \Carbon\Carbon::parse($r->flddoa)->format('Y-m-d') . '</td>
                        <td>' . \Carbon\Carbon::parse($r->flddod)->format('Y-m-d') . '</td>
                        <td>' . $r->reason . '</td>';

            $html_pdf .= '</tr>';
        }
        $data['html_pdf'] = $html_pdf;
        if (!$request->has('isExport')) {
            $html .= '<tr><td colspan="14">' . $results->appends(request()->all())->links() . '</td></tr>';
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html
                ]
            ]);
        } else {
            return view('reports::dischargeBillsReport.discharge-bills-pdf', $data);
        }
    }

    public function getExportData(Request $request){
        $export = new DishchargeBillExport($request->comp, $request->from_date, $request->to_date, $request->serviceType);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'DischargeBillReport.xlsx');
    }

}
