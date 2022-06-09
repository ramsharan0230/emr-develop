<?php

namespace Modules\DepartmentWiseReport\Http\Controllers;

use App\PatBillDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use App\Utils\Helpers;
use App\PatBilling;
use Illuminate\Support\Facades\DB;
use Excel;
use App\Exports\DepartmentWiseReportExport;

class DepartmentWiseReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $date = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;

        $fromdate = $request->eng_from_date ?: $date;
        $todate = $request->eng_to_date ?: $date;
        $data['date'] = $date;
        $user = \Auth::guard("admin_frontend")->user();

        $finalfrom = $fromdate." 00:00:00";
        $finalto = $todate." 23:59:59.999";

        $opdepositData = PatBillDetail::selectRaw("sum(fldreceivedamt) as totaldepo")
            ->where('fldbillno', 'like', '%dep%')
            ->where('fldtime', '>=', $finalfrom)
            ->where('fldtime', '<=', $finalto)
            ->where(function($query) {
                $query->where('fldpayitemname', 'like', '%admission deposit%')
                    ->orWhere('fldpayitemname', 'like', '%op deposit%')
                    ->orWhere('fldpayitemname', 'like', '%re deposit%')
                    ->orWhere('fldpayitemname', 'like', '%blood bank%')
                    ->orWhere('fldpayitemname', 'like', '%gate pass%')
                    ->orWhere('fldpayitemname', 'like', '%post-up%');
            })
            ->where("fldcomp", Helpers::getCompName())
            ->get();
        $totalopdeposit = $opdepositData ? $opdepositData[0]->totaldepo : 0;
        $data['deposit'] =  $totalopdeposit;

        $opdepositrefData = PatBillDetail::selectRaw("sum(fldreceivedamt) as totalrefund")
            ->where('fldbillno', 'like', '%dep%')
            ->where('fldtime', '>=', $finalfrom)
            ->where('fldtime', '<=', $finalto)
            ->where('fldpayitemname', 'like', '%deposit refund%')
            ->where("fldcomp", Helpers::getCompName())
            ->get();
        $opdepositref = $opdepositrefData ? $opdepositrefData[0]->totalrefund : 0;
        $data['deposit_refund'] = ($opdepositref != NULL) ? $opdepositref : 0;

        $previousdepositData = PatBillDetail::selectRaw("sum(fldprevdeposit) as prevamt")
            ->where('fldbillno', 'like', '%CAS%')
            ->where('fldtime', '>=', $finalfrom)
            ->where('fldtime', '<=', $finalto)
            ->where('fldpayitemname', 'like', '%Discharge Clearence%')
            ->where('fldreceivedamt', '>', '0')
            ->where("fldcomp", Helpers::getCompName())
            ->get();
        $previousdeposit = $previousdepositData ? $previousdepositData[0]->prevamt : 0;
        $data['Previous_Deposit_of_Discharge_Clearence'] = ($previousdeposit != NULL) ? $previousdeposit : 0;

        $dischargeclearanceData = PatBillDetail::selectRaw("sum(fldreceivedamt) as dischargeamt")
            ->where('fldtime', '>=', $finalfrom)
            ->where('fldtime', '<=', $finalto)
            ->where('fldpayitemname', 'like', '%Discharge Clearence%')
            ->where('fldreceivedamt', '>=', '0')
            ->where("fldcomp", Helpers::getCompName())
            ->get();
        $dischargeclerance = $dischargeclearanceData ? $dischargeclearanceData[0]->dischargeamt : 0;
        $data['Received_Deposit_of_Discharge_Clearence'] = ($dischargeclerance != NULL) ? $dischargeclerance : 0;

        $rev_amount_sumData = PatBillDetail::selectRaw("sum(fldreceivedamt) as totalrefund")
            ->where('fldtime', '>=', $finalfrom)
            ->where('fldtime', '<=', $finalto)
            ->where("fldcomp", Helpers::getCompName())
            ->get();
        $rev_amount_sum = $rev_amount_sumData ? $rev_amount_sumData[0]->totalrefund : 0;
        $data['rev_amount_sum'] = ($rev_amount_sum != NULL) ? $rev_amount_sum : 0;

        $data['reports'] = PatBilling::leftJoin('tblservicecost AS s', 's.flditemname', '=', 'tblpatbilling.flditemname')
            ->selectRaw("s.fldreport  AS dept,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%' ) then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END)) AS IP_Cash_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%' ) then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END)) AS OP_Cash_Amount,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CRE%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else 0.00 END)) AS IP_Credit_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CRE%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END)) AS OP_Credit_Amount,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.flddiscamt) else '0.00' END)) AS IP_Discount_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.flddiscamt) else '0.00' END)) AS OP_Discount_Amount,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.fldtaxamt) else '0.00' END)) AS IP_Tax_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.fldtaxamt) else '0.00' END)) AS OP_Tax_Amount,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END)) AS IP_Return_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END)) AS OP_Return_Amount,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.fldtaxamt) else '0.00' END)) AS IP_Return_Tax_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.fldtaxamt) else '0.00' END)) AS OP_Return_Tax_Amount")
            ->where([
                ["tblpatbilling.fldtime", ">=", "$fromdate 00:00:00"],
                ["tblpatbilling.fldtime", "<=", "$todate 23:59:59.99"],
                ["tblpatbilling.fldsave", 1],
            ])
            ->where("tblpatbilling.fldcomp", Helpers::getCompName())
            // ->whereNotNull('s.fldreport')
            ->groupBy('s.fldreport')
            ->get();

$comp = Helpers::getCompName();
            $opitemamoutsumsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
            where fldtime >= '" . $fromdate . " 00:00:00'
            and fldtime <='" . $todate . " 23:59:59.99'
            and fldsave = 1
            and (fldencounterval  NOT LIKE '%IP%')
            and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
            and fldcomp = '".$comp."' ";


            $opitemamoutsumData = DB::select(
                $opitemamoutsumsql
            );
            //op collection
            $data['OP_patbilling'] =   $opitemamoutsumData ? $opitemamoutsumData[0]->totaldepo : 0;


            $ipitemamoutsumsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
            where fldtime >= '" . $fromdate . " 00:00:00'
            and fldtime <='" . $todate . " 23:59:59.99'
            and fldsave = 1
            and (fldencounterval  LIKE 'IP%')
            and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
            and fldcomp = 'comp01' ";


            $ipitemamoutsumData = DB::select(
                    $ipitemamoutsumsql
                );
                //ipcolleciton
            $data['IP_patbilling'] =   $ipitemamoutsumData ? $ipitemamoutsumData[0]->totaldepo : 0;


        return view('departmentwisereport::index', $data);
    }



    public function excel(Request $request)
    {
        $fromdate = $request->eng_from_date ?: date('Y-m-d');
        $todate = $request->eng_to_date ?: date('Y-m-d');
        $export = new DepartmentWiseReportExport($fromdate, $todate);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'DepartmentWiseReportExport.xlsx');
    }
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function pdf(Request $request)
    {
        $fromdate = $request->eng_from_date ?: date('Y-m-d');
        $todate = $request->eng_to_date ?: date('Y-m-d');

        $data['eng_from_date'] = $fromdate;
        $data['eng_to_date'] = $todate;
        $data['nep_from_date'] = Helpers::dateEngToNepdash($fromdate)->full_date;
        $data['nep_to_date'] = Helpers::dateEngToNepdash($todate)->full_date;
        $user = \Auth::guard("admin_frontend")->user();

        $finalfrom = $fromdate." 00:00:00";
        $finalto = $todate." 23:59:59.999";

        $opdepositData = PatBillDetail::selectRaw("sum(fldreceivedamt) as totaldepo")
            ->where('fldbillno', 'like', '%dep%')
            ->where('fldtime', '>=', $finalfrom)
            ->where('fldtime', '<=', $finalto)
            ->where(function($query) {
                $query->where('fldpayitemname', 'like', '%admission deposit%')
                    ->orWhere('fldpayitemname', 'like', '%op deposit%')
                    ->orWhere('fldpayitemname', 'like', '%re deposit%')
                    ->orWhere('fldpayitemname', 'like', '%blood bank%')
                    ->orWhere('fldpayitemname', 'like', '%gate pass%')
                    ->orWhere('fldpayitemname', 'like', '%post-up%');
            })
            ->where("fldcomp", Helpers::getCompName())
            ->get();
        $totalopdeposit = $opdepositData ? $opdepositData[0]->totaldepo : 0;
        $data['deposit'] =  $totalopdeposit;

        $opdepositrefData = PatBillDetail::selectRaw("sum(fldreceivedamt) as totalrefund")
            ->where('fldbillno', 'like', '%dep%')
            ->where('fldtime', '>=', $finalfrom)
            ->where('fldtime', '<=', $finalto)
            ->where('fldpayitemname', 'like', '%deposit refund%')
            ->where("fldcomp", Helpers::getCompName())
            ->get();
        $opdepositref = $opdepositrefData ? $opdepositrefData[0]->totalrefund : 0;
        $data['deposit_refund'] = ($opdepositref != NULL) ? $opdepositref : 0;

        $previousdepositData = PatBillDetail::selectRaw("sum(fldprevdeposit) as prevamt")
            ->where('fldbillno', 'like', '%CAS%')
            ->where('fldtime', '>=', $finalfrom)
            ->where('fldtime', '<=', $finalto)
            ->where('fldpayitemname', 'like', '%Discharge Clearence%')
            ->where('fldreceivedamt', '>', '0')
            ->where("fldcomp", Helpers::getCompName())
            ->get();
        $previousdeposit = $previousdepositData ? $previousdepositData[0]->prevamt : 0;
        $data['Previous_Deposit_of_Discharge_Clearence'] = ($previousdeposit != NULL) ? $previousdeposit : 0;

        $dischargeclearanceData = PatBillDetail::selectRaw("sum(fldreceivedamt) as dischargeamt")
            ->where('fldtime', '>=', $finalfrom)
            ->where('fldtime', '<=', $finalto)
            ->where('fldpayitemname', 'like', '%Discharge Clearence%')
            ->where('fldreceivedamt', '>=', '0')
            ->where("fldcomp", Helpers::getCompName())
            ->get();
        $dischargeclerance = $dischargeclearanceData ? $dischargeclearanceData[0]->dischargeamt : 0;
        $data['Received_Deposit_of_Discharge_Clearence'] = ($dischargeclerance != NULL) ? $dischargeclerance : 0;

        $rev_amount_sumData = PatBillDetail::selectRaw("sum(fldreceivedamt) as totalrefund")
            ->where('fldtime', '>=', $finalfrom)
            ->where('fldtime', '<=', $finalto)
            ->where("fldcomp", Helpers::getCompName())
            ->get();
        $rev_amount_sum = $rev_amount_sumData ? $rev_amount_sumData[0]->totalrefund : 0;
        $data['rev_amount_sum'] = ($rev_amount_sum != NULL) ? $rev_amount_sum : 0;

        $data['reports'] = PatBilling::leftJoin('tblservicecost AS s', 's.flditemname', '=', 'tblpatbilling.flditemname')
            ->selectRaw("s.fldreport  AS dept,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END)) AS IP_Cash_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END)) AS OP_Cash_Amount,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CRE%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else 0.00 END)) AS IP_Credit_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CRE%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END)) AS OP_Credit_Amount,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.flddiscamt) else '0.00' END)) AS IP_Discount_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.flddiscamt) else '0.00' END)) AS OP_Discount_Amount,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.fldtaxamt) else '0.00' END)) AS IP_Tax_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.fldtaxamt) else '0.00' END)) AS OP_Tax_Amount,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END)) AS IP_Return_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END)) AS OP_Return_Amount,
                (sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.fldtaxamt) else '0.00' END)) AS IP_Return_Tax_Amount,
                (sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.fldtaxamt) else '0.00' END)) AS OP_Return_Tax_Amount")
            ->where([
                ["tblpatbilling.fldtime", ">=", "$fromdate 00:00:00"],
                ["tblpatbilling.fldtime", "<=", "$todate 23:59:59.999"],
                ["tblpatbilling.fldsave", 1],
            ])
            ->where("tblpatbilling.fldcomp", Helpers::getCompName())
            // ->whereNotNull('s.fldreport')
            ->groupBy('s.fldreport')
            ->get();

        $file = "Department-Wise-Report-" . $fromdate . "-to-" . $todate . ".pdf";
        return view('departmentwisereport::pdf', $data);
        // return PDF::loadView('departmentwisereport::pdf', $data)->setPaper('a4')->stream($file);
    }
}
