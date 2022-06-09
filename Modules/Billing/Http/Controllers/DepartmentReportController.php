<?php

namespace Modules\Billing\Http\Controllers;

use App\BillingSet;
use App\Department;
use App\Departmentbed;
use App\DepartmentRevenue;
use App\Encounter;
use App\Exports\DepartmentCollectionExport;
use App\Exports\DepartmentWiseRevenueExport;
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
use Maatwebsite\Excel\Facades\Excel;
use Session;

class DepartmentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        //        $data['dept_revs'] =$this->displayData();
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
        $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('billing::department', $data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchDepartmentCollectionBillingDetail(Request $request)
    {

        $data['from_date'] = $from_date = $request->eng_from_date;
        $data['to_date'] = $to_date = $request->eng_to_date;
        $reports = [];

        $data['CasResults'] = $CasResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,

            SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
            SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
            SUM(tblpatbilling.flddiscamt) as tot_discamt,
            tblservicecost.fldreport")
        )
            ->leftJoin('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')

            ->when($from_date, function ($q) use ($from_date, $to_date) {
                return $q->where('tblpatbilling.fldtime', '>=', $from_date . ' 00:00:00')
                    ->where('tblpatbilling.fldtime', '<=', $to_date . ' 23:59:59');
            })

            ->where(function ($query) use ($from_date) {

                $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%CAS%');
                $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%REG%');
            })

            ->whereNotIn('tblpatbilling.flditemtype',  ['Medicines', 'Surgicals', 'Extra Items'])
            ->where('tblpatbilling.fldsave', 1)
            ->where("tblpatbilling.fldcomp", Helpers::getCompName())
            ->groupBy('tblservicecost.fldreport')
            ->get()
            ->groupBy('fldreport');

        $reports = array_merge($reports, array_keys($CasResults->toArray()));


        $data['CreResults'] = $CreResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
            SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
            SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
            SUM(tblpatbilling.flddiscamt) as tot_discamt,
            tblservicecost.fldreport")
        )
            ->leftJoin('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')

            ->when($from_date, function ($q) use ($from_date, $to_date) {
                return $q->where('tblpatbilling.fldtime', '>=', $from_date . ' 00:00:00')
                    ->where('tblpatbilling.fldtime', '<=', $to_date . ' 23:59:59');
            })
            ->where(function ($query) use ($from_date) {

                $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%CRE%');
            })
            ->whereNotIn('tblpatbilling.flditemtype',  ['Medicines', 'Surgicals', 'Extra Items'])
            ->where('tblpatbilling.fldsave', 1)
            ->where("tblpatbilling.fldcomp", Helpers::getCompName())
            ->groupBy('tblservicecost.fldreport')
            ->get()
            ->groupBy('fldreport');
        $reports = array_merge($reports, array_keys($CreResults->toArray()));

        $data['RetResults'] = $RetResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
            SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
            SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
            SUM(tblpatbilling.flddiscamt) as tot_discamt,
            tblservicecost.fldreport")
        )
            ->leftJoin('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')

            ->when($from_date, function ($q) use ($from_date, $to_date) {
                return $q->where('tblpatbilling.fldtime', '>=', $from_date . ' 00:00:00')
                    ->where('tblpatbilling.fldtime', '<=', $to_date . ' 23:59:59');
            })
            ->where('tblpatbilling.fldbillno', 'like',  '%RET%')
            ->where("tblpatbilling.fldcomp", Helpers::getCompName())
            ->whereNotIn('tblpatbilling.flditemtype',  ['Medicines', 'Surgicals', 'Extra Items'])
            ->where('tblpatbilling.fldsave', 1)
            ->groupBy('tblservicecost.fldreport')
            ->get()
            ->groupBy('fldreport');

        $reports = array_merge($reports, array_keys($RetResults->toArray()));


        $data['ForNetResults'] = $ForNetResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
            tblservicecost.fldreport")
        )
            ->leftJoin('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')

            ->when($from_date, function ($q) use ($from_date, $to_date) {
                return $q->where('tblpatbilling.fldtime', '>=', $from_date . ' 00:00:00')
                    ->where('tblpatbilling.fldtime', '<=', $to_date . ' 23:59:59');
            })

            ->whereNotIn('tblpatbilling.flditemtype',  ['Medicines', 'Surgicals', 'Extra Items'])
            ->where('tblpatbilling.fldsave', 1)
            ->where("tblpatbilling.fldcomp", Helpers::getCompName())
            ->groupBy('tblservicecost.fldreport')
            ->get()
            ->groupBy('fldreport');
        $reports = array_merge($reports, array_keys($ForNetResults->toArray()));

        $comp = Helpers::getCompName();

        $opitemamoutsumsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
        where fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldsave = 1
        and (fldencounterval  NOT LIKE '%IP%')
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = '".$comp."' ";


        $opitemamoutsumData = DB::select(
            $opitemamoutsumsql
        );
        //op collection
        $data['OP_patbilling'] =   $opitemamoutsumData ? $opitemamoutsumData[0]->totaldepo : 0;


        $opitemamoutdetailsumsql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
        where fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldsave = 1
        and (fldencounterval  NOT LIKE '%IP%')
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = '".$comp."' ";


        $opitemamoutsumDatadetail = DB::select(
            $opitemamoutdetailsumsql
        );
        //OP_collection_patbilling
        $data['OP_collection_patbilling'] =   $opitemamoutsumDatadetail ? $opitemamoutsumDatadetail[0]->totaldepo : 0;



        $ipitemamoutsumsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
        where fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldsave = 1
        and (fldencounterval  LIKE 'IP%')
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = '".$comp."' ";


        $ipitemamoutsumData = DB::select(
                $ipitemamoutsumsql
            );
            //ipcolleciton
        $data['IP_Patbilling'] =   $ipitemamoutsumData ? $ipitemamoutsumData[0]->totaldepo : 0;

        $opdepositDataSql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
                            where fldbillno like '%dep%'
                            and fldtime >= '".$from_date . ' 00:00:00'."'
                            and fldtime <='".$to_date . ' 23:59:59'."'
                            and (fldpayitemname like '%admission deposit%'
                            or fldpayitemname like '%op deposit%'
                            or fldpayitemname like '%re deposit%'
                            or fldpayitemname like '%blood bank%'
                            or fldpayitemname like '%gate pass%'
                            or fldpayitemname like '%post-up%')

                            and fldcomp = '".$comp."'
                        ";


        $opdepositData = DB::select(
                $opdepositDataSql
            );


        $totalopdeposit = $opdepositData ? $opdepositData[0]->totaldepo : 0;

        $data['deposit'] =  $totalopdeposit;


        $previousdepositDataSql = "select sum(fldprevdeposit) as prevamt from tblpatbilldetail
        where  fldbillno like '%CAS%'
         and fldtime >= '" . $from_date . ' 00:00:00' . "'
         and fldtime <='" . $to_date .' 23:59:59' . "'
         and fldpayitemname like '%Discharge Clearence%'
         and fldreceivedamt > '0'
         and fldcomp = '".$comp."'
        ";


        $previousdepositData = DB::select(
        $previousdepositDataSql
            );
        $previousdeposit = $previousdepositData ? $previousdepositData[0]->prevamt : 0;
        $data['Previous_Deposit_of_Discharge_Clearence'] = ($previousdeposit != NULL) ? $previousdeposit : 0;


        $dischargeclearanceDataSql = "select sum(fldreceivedamt) as dischargeamt from tblpatbilldetail
        where  fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldpayitemname like '%Discharge Clearence%'
        and fldreceivedamt >= '0'
        and fldcomp = '".$comp."'
         ";


        $dischargeclearanceData = DB::select(
                $dischargeclearanceDataSql
        );
        $dischargeclerance = $dischargeclearanceData ? $dischargeclearanceData[0]->dischargeamt : 0;
        //Discharge Garda Leko amount
        $data['Received_Deposit_of_Discharge_Clearence'] = ($dischargeclerance != NULL) ? $dischargeclerance : 0;


        $opdepositrefDataSql = "select sum(fldreceivedamt) as totalrefund from tblpatbilldetail
        where fldbillno like '%dep%'
        and fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldpayitemname like '%deposit refund%'
        and fldcomp = '".$comp."'
        ";


        $opdepositrefData = DB::select(
            $opdepositrefDataSql
        );
        $opdepositref = $opdepositrefData ? $opdepositrefData[0]->totalrefund : 0;
        $data['deposit_refund'] = ($opdepositref != NULL) ? $opdepositref : 0;


        $patbilling_fldditemamtsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
        where fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldsave = 1
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = '".$comp."' ";

        $patbilling_fldditemamtData = DB::select(
            $patbilling_fldditemamtsql
        );
        $patbilling_fldditemamt = $patbilling_fldditemamtData ? $patbilling_fldditemamtData[0]->totaldepo : 0;
        $data['patbilling_fldditemamt'] = ($patbilling_fldditemamt != NULL) ? $patbilling_fldditemamt : 0;

        $rev_amount_sumSql = "select sum(fldreceivedamt) as totalrefund from tblpatbilldetail
        where  fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldcomp = '".$comp."'
        ";

        $rev_amount_sumData = DB::select(
            $rev_amount_sumSql
        );
        $rev_amount_sum = $rev_amount_sumData ? $rev_amount_sumData[0]->totalrefund : 0;
        // /Detail ko sum:
        $data['rev_amount_sum'] = ($rev_amount_sum != NULL) ? $rev_amount_sum : 0;


        $ipreturnsql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
        where fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldsave = 1
        and (fldencounterval LIKE '%IP%')
        and  (fldbillno LIKE '%RET%')
        and fldcomp = '".$comp."' ";


        $ipreturnsqlData = DB::select(
            $ipreturnsql
        );
        //ip returns
        $data['ipreturns'] =   $ipreturnsqlData ? $ipreturnsqlData[0]->totaldepo : 0;




        $data['reports'] = $reports = array_unique($reports);

        $html=  view('billing::pdf.department-new-report-view', $data)->render();

        return response()->json([
            'html'=>$html,
            'status'=>200
        ]);
    }

    public function searchCategoryWiseReport(Request $request)
    {
        $from_date = Helpers::dateNepToEng($request->from_date);
        $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date.' 00:00:00' ;
        $to_date = Helpers::dateNepToEng($request->to_date);
        $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date. ' 23:59:59' ;
        $reports = [];

        $data['CasResults'] = $CasResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
            SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
            SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
            SUM(tblpatbilling.flddiscamt) as tot_discamt,
            tblreportgroup.fldgroup"))
            ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')


            ->when($finalfrom, function ($q) use ($finalfrom,$finalto){
                return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                        ->where('tblpatbilling.fldtime','<=',$finalto);
            })

            ->where(function ($query) {
                $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%CAS%');
                $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%REG%');
            })
            ->where('tblreportgroup.fldgroup','!=',null)
            ->where('tblpatbilling.fldsave',1)
            ->where("tblpatbilling.fldcomp", Helpers::getCompName())
            ->groupBy('tblreportgroup.fldgroup')
            ->get()
            ->groupBy('fldgroup');
        $reports = array_merge($reports, array_keys($CasResults->toArray()));

        $data['CreResults'] = $CreResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
            SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
            SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
            SUM(tblpatbilling.flddiscamt) as tot_discamt,
            tblreportgroup.fldgroup"))
            ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')


            ->when($finalfrom, function ($q) use ($finalfrom,$finalto){
                return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                        ->where('tblpatbilling.fldtime','<=',$finalto);
            })
            ->where('tblreportgroup.fldgroup','!=',null)
            ->where('tblpatbilling.fldbillno', 'like',  '%CRE%')
            ->where('tblpatbilling.fldsave',1)
            ->where("tblpatbilling.fldcomp", Helpers::getCompName())
            ->groupBy('tblreportgroup.fldgroup')
            ->get()
            ->groupBy('fldgroup');
        $reports = array_merge($reports, array_keys($CreResults->toArray()));

        $data['RetResults'] = $RetResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
            SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
            SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
            SUM(tblpatbilling.flddiscamt) as tot_discamt,
            tblreportgroup.fldgroup"))
            ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')


            ->when($finalfrom, function ($q) use ($finalfrom,$finalto){
                return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                        ->where('tblpatbilling.fldtime','<=',$finalto);
            })
            ->where('tblreportgroup.fldgroup','!=',null)
            ->where('tblpatbilling.fldbillno', 'like',  '%RET%')
            ->where('tblpatbilling.fldsave',1)
            ->where("tblpatbilling.fldcomp", Helpers::getCompName())
            ->groupBy('tblreportgroup.fldgroup')
            ->get()
            ->groupBy('fldgroup');
        $reports = array_merge($reports, array_keys($RetResults->toArray()));

        $data['ForNetResults'] = $ForNetResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
            tblreportgroup.fldgroup"))
            ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')


            ->when($finalfrom, function ($q) use ($finalfrom,$finalto){
                return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                        ->where('tblpatbilling.fldtime','<=',$finalto);
            })
            ->where('tblreportgroup.fldgroup','!=',null)
            ->where('tblpatbilling.fldsave',1)
            ->where("tblpatbilling.fldcomp", Helpers::getCompName())
            ->groupBy('tblreportgroup.fldgroup')
            ->get()
            ->groupBy('fldgroup');
        $reports = array_merge($reports, array_keys($ForNetResults->toArray()));
        $comp = Helpers::getCompName();

        $opitemamoutsumsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
        where fldtime >= '" . $finalfrom . "'
        and fldtime <='" . $finalto . "'
        and fldsave = 1
        and (fldencounterval  NOT LIKE '%IP%')
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = '".$comp."' ";


        $opitemamoutsumData = DB::select(
            $opitemamoutsumsql
        );
        //op collection
        $data['OP_patbilling'] =   $opitemamoutsumData ? $opitemamoutsumData[0]->totaldepo : 0;


        $opitemamoutdetailsumsql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
        where fldtime >= '" . $finalfrom . "'
        and fldtime <='" . $finalto . "'
        and fldsave = 1
        and (fldencounterval  NOT LIKE '%IP%')
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = '".$comp."' ";


        $opitemamoutsumDatadetail = DB::select(
            $opitemamoutdetailsumsql
        );
        //OP_collection_patbilling
        $data['OP_collection_patbilling'] =   $opitemamoutsumDatadetail ? $opitemamoutsumDatadetail[0]->totaldepo : 0;



        $ipitemamoutsumsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
        where fldtime >= '" . $finalfrom . "'
        and fldtime <='" . $finalto . "'
        and fldsave = 1
        and (fldencounterval  LIKE 'IP%')
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = 'comp01' ";


        $ipitemamoutsumData = DB::select(
                $ipitemamoutsumsql
            );
            //ipcolleciton
        $data['IP_Patbilling'] =   $ipitemamoutsumData ? $ipitemamoutsumData[0]->totaldepo : 0;

        $opdepositDataSql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
                            where fldbillno like '%dep%'
                            and fldtime >= '".$finalfrom."'
                            and fldtime <='".$finalto."'
                            and (fldpayitemname like '%admission deposit%'
                            or fldpayitemname like '%op deposit%'
                            or fldpayitemname like '%re deposit%'
                            or fldpayitemname like '%blood bank%'
                            or fldpayitemname like '%gate pass%'
                            or fldpayitemname like '%post-up%')

                            and fldcomp = 'comp01'
                        ";


        $opdepositData = DB::select(
                $opdepositDataSql
            );


        $totalopdeposit = $opdepositData ? $opdepositData[0]->totaldepo : 0;

        $data['deposit'] =  $totalopdeposit;


        $previousdepositDataSql = "select sum(fldprevdeposit) as prevamt from tblpatbilldetail
        where  fldbillno like '%CAS%'
         and fldtime >= '" . $finalfrom . "'
         and fldtime <='" . $finalto . "'
         and fldpayitemname like '%Discharge Clearence%'
         and fldreceivedamt > '0'
         and fldcomp = 'comp01'
        ";


        $previousdepositData = DB::select(
        $previousdepositDataSql
            );
        $previousdeposit = $previousdepositData ? $previousdepositData[0]->prevamt : 0;
        $data['Previous_Deposit_of_Discharge_Clearence'] = ($previousdeposit != NULL) ? $previousdeposit : 0;


        $dischargeclearanceDataSql = "select sum(fldreceivedamt) as dischargeamt from tblpatbilldetail
        where  fldtime >= '" . $finalfrom . "'
        and fldtime <='" . $finalto . "'
        and fldpayitemname like '%Discharge Clearence%'
        and fldreceivedamt >= '0'
        and fldcomp = 'comp01'
         ";


        $dischargeclearanceData = DB::select(
                $dischargeclearanceDataSql
        );
        $dischargeclerance = $dischargeclearanceData ? $dischargeclearanceData[0]->dischargeamt : 0;
        //Discharge Garda Leko amount
        $data['Received_Deposit_of_Discharge_Clearence'] = ($dischargeclerance != NULL) ? $dischargeclerance : 0;


        $opdepositrefDataSql = "select sum(fldreceivedamt) as totalrefund from tblpatbilldetail
        where fldbillno like '%dep%'
        and fldtime >= '" . $finalfrom . "'
        and fldtime <='" . $finalto . "'
        and fldpayitemname like '%deposit refund%'
        and fldcomp = 'comp01'
        ";


        $opdepositrefData = DB::select(
            $opdepositrefDataSql
        );
        $opdepositref = $opdepositrefData ? $opdepositrefData[0]->totalrefund : 0;
        $data['deposit_refund'] = ($opdepositref != NULL) ? $opdepositref : 0;


        $patbilling_fldditemamtsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
        where fldtime >= '" . $finalfrom . "'
        and fldtime <='" . $finalto . "'
        and fldsave = 1
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = 'comp01' ";

        $patbilling_fldditemamtData = DB::select(
            $patbilling_fldditemamtsql
        );
        $patbilling_fldditemamt = $patbilling_fldditemamtData ? $patbilling_fldditemamtData[0]->totaldepo : 0;
        $data['patbilling_fldditemamt'] = ($patbilling_fldditemamt != NULL) ? $patbilling_fldditemamt : 0;

        $rev_amount_sumSql = "select sum(fldreceivedamt) as totalrefund from tblpatbilldetail
        where  fldtime >= '" . $finalfrom . "'
        and fldtime <='" . $finalto . "'
        and fldcomp = 'comp01'
        ";

        $rev_amount_sumData = DB::select(
            $rev_amount_sumSql
        );
        $rev_amount_sum = $rev_amount_sumData ? $rev_amount_sumData[0]->totalrefund : 0;
        // /Detail ko sum:
        $data['rev_amount_sum'] = ($rev_amount_sum != NULL) ? $rev_amount_sum : 0;


        $ipreturnsql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
        where fldtime >= '" . $finalfrom . "'
        and fldtime <='" . $finalto . "'
        and fldsave = 1
        and (fldencounterval LIKE '%IP%')
        and  (fldbillno LIKE '%RET%')
        and fldcomp = 'comp01' ";


        $ipreturnsqlData = DB::select(
            $ipreturnsql
        );
        //ip returns
        $data['ipreturns'] =   $ipreturnsqlData ? $ipreturnsqlData[0]->totaldepo : 0;




        $data['reports'] = $reports = array_unique($reports);

        $html=  view('billing::pdf.category-wise-report', $data)->render();

        return response()->json([
            'html'=>$html,
            'status'=>200
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function exportDepartmentCollectionReportBck(Request $request)
    {
        try {
            $result = PatBillDetail::select('fldcomp')->whereBetween('fldtime', [$request->eng_from_date, $request->eng_to_date])->distinct('fldcomp')->get();
            $data['result'] = $result;
            $data['from_date'] = $request->from_date;
            $data['to_date'] = $request->to_date;
            $data['eng_from_date'] = $request->eng_from_date;
            $data['eng_to_date'] = $request->eng_to_date;
            return view('billing::pdf.department-collection-report', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/;
        } catch (\Exception $e) {
            \Log::log($e->getMessage());
        }
    }

    public function exportDepartmentCollectionReport(Request $request)
    {
        try {
            $result = PatBillDetail::select('fldcomp')
                ->whereBetween('fldtime', [$request->eng_from_date . ' 00:00:00', $request->eng_to_date . ' 23:59:59'])
                ->groupBy('fldcomp')->get();
            $data['result'] = $result;
            $data['fromdate'] = $request->from_date;
            $data['todate'] = $request->to_date;
            $data['eng_from_date'] = $request->eng_from_date;
            $data['eng_to_date'] = $request->eng_to_date;
            $export = new DepartmentCollectionExport($data);
            ob_end_clean();
            ob_start();
            return Excel::download($export, 'departmentcollection.xlsx');
        } catch (\Exception $e) {
        }
    }

    public function exportDepartmentWiseRevenueReport(Request $request)
    {
        try {

            $result = Encounter::query()->select('fldcurrlocat')->distinct('fldcurrlocat')->get();

            $data['result'] = $result;
            $data['fromdate'] = $request->eng_from_date;
            $data['todate'] = $request->eng_to_date;


            $export = new DepartmentWiseRevenueExport($data);
            ob_end_clean();
            ob_start();

            return Excel::download($export, 'departmentwiserevenuereport.xlsx');
        } catch (\Exception $e) {
            //            dd($e->getMessage());
        }
    }

    public function exportDepartmentWiseReport(Request $request)
    {
        $totalopdeposit = 0;
        $opdepositref = 0;
        $data['from_date'] = $from_date = $request->eng_from_date;
        $data['to_date'] = $to_date = $request->eng_to_date;
        $departments = [];
        $data['CasResults'] = $CasResults = PatBilling::select(
            DB::raw("SUM(dept_revenue.flditemamt) as tot_itmamt,
        SUM(dept_revenue.fldtaxamt) as tot_taxamt,
        SUM(dept_revenue.flddiscountamt) as tot_disamt,
        SUM(dept_revenue.fldchargedamt) as tot_chargedamt,
        SUM(dept_revenue.fldreceivedamt) as tot_receivedamt,
        dept_revenue.location,dept_revenue.flddepartment")
        )
            ->leftJoin('dept_revenue', 'dept_revenue.pat_details_id', '=', 'tblpatbilling.fldid')
            ->when($from_date == $to_date, function ($q) use ($from_date) {
                return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"), $from_date);
            })
            ->when($from_date != $to_date, function ($q) use ($from_date, $to_date) {
                return $q->where('tblpatbilling.fldtime', '>=', $from_date . ' 00:00:00')
                    ->where('tblpatbilling.fldtime', '<=', $to_date . ' 23:59:59');
            })
            ->where('tblpatbilling.fldbillno', 'like',  '%CAS%')
            ->where('tblpatbilling.fldsave', 1)
            ->groupBy('dept_revenue.flddepartment')
            ->get()
            ->groupBy('flddepartment');
        // dd($data['CasResults']);

        $departments = array_merge($departments, array_keys($CasResults->toArray()));

        $data['RetResults'] = $RetResults = PatBilling::select(
            DB::raw("SUM(dept_revenue.flditemamt) as tot_itmamt,
        dept_revenue.location,dept_revenue.flddepartment")
        )
            ->leftJoin('dept_revenue', 'dept_revenue.pat_details_id', '=', 'tblpatbilling.fldid')
            ->when($from_date == $to_date, function ($q) use ($from_date) {
                return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"), $from_date);
            })
            ->when($from_date != $to_date, function ($q) use ($from_date, $to_date) {
                return $q->where('tblpatbilling.fldtime', '>=', $from_date . ' 00:00:00')
                    ->where('tblpatbilling.fldtime', '<=', $to_date . ' 23:59:59');
            })
            ->where('tblpatbilling.fldbillno', 'like',  '%RET%')
            ->where('tblpatbilling.fldsave', 1)
            ->groupBy('dept_revenue.flddepartment')
            ->get()
            ->groupBy('flddepartment');
        $departments = array_merge($departments, array_keys($RetResults->toArray()));

        //For deposit
        $data['DepResults'] = $DepResults = PatBilling::select(
            DB::raw("SUM(dept_revenue.flditemamt) as tot_itmamt,
            dept_revenue.location,dept_revenue.flddepartment")
        )
            ->leftJoin('dept_revenue', 'dept_revenue.pat_details_id', '=', 'tblpatbilling.fldid')
            ->leftJoin('tblpatbilldetail', 'tblpatbilldetail.fldbillno', '=', 'tblpatbilling.fldbillno')
            ->when($from_date == $to_date, function ($q) use ($from_date) {
                return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"), $from_date);
            })
            ->when($from_date != $to_date, function ($q) use ($from_date, $to_date) {
                return $q->where('tblpatbilling.fldtime', '>=', $from_date . ' 00:00:00')
                    ->where('tblpatbilling.fldtime', '<=', $to_date . ' 23:59:59');
            })
            ->where('tblpatbilling.fldbillno', 'like',  '%DEP%')
            ->where(function ($query) use ($from_date) {

                $query->orwhere('tblpatbilldetail.fldpayitemname', 'LIKE', '%admission deposit%');
                $query->orwhere('tblpatbilldetail.fldpayitemname', 'LIKE', '%op deposit%');
                $query->orwhere('tblpatbilldetail.fldpayitemname', 'LIKE', '%re deposit%');
                $query->orwhere('tblpatbilldetail.fldpayitemname', 'LIKE', '%blood bank%');
                $query->orwhere('tblpatbilldetail.fldpayitemname', 'LIKE', '%gate pass%');
                $query->orwhere('tblpatbilldetail.fldpayitemname', 'LIKE', '%post-up%');
            })

            ->where('tblpatbilling.fldsave', 1)
            ->groupBy('dept_revenue.flddepartment')
            ->get()
            ->groupBy('flddepartment');


        $opdepositDataSql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
            where fldbillno like '%dep%' and fldtime
            >= '" . $from_date . ' 00:00:00' . "' and ' fldtime
            <=" . $to_date .
            ' 23:59:59' . "'  and
(fldpayitemname like '%admission deposit%' or fldpayitemname like '%op deposit%' or fldpayitemname like '%re deposit%'
or fldpayitemname like '%blood bank%'or fldpayitemname like '%gate pass%' or fldpayitemname like '%post-up%')";


        $opdepositData = DB::select(
            $opdepositDataSql
        );

        $totalopdeposit = $opdepositData ? $opdepositData[0]->totaldepo : 0;

        $data['deposit'] = ($totalopdeposit != NULL) ? $totalopdeposit : 0;

        $opdepositrefDataSql = "select sum(fldreceivedamt) as totalrefund from
             tblpatbilldetail where fldbillno like '%dep%' and fldtime
             >= '" . $from_date . ' 00:00:00' . "' and ' fldtime
             <=" . $to_date .
            ' 23:59:59' . "'
            and fldpayitemname like '%deposit refund%'";


        $opdepositrefData = DB::select(
            $opdepositrefDataSql
        );
        $opdepositref = $opdepositrefData ? $opdepositrefData[0]->totalrefund : 0;
        $data['deposit_refund'] = ($opdepositref != NULL) ? $opdepositref : 0;






        $departments = array_merge($departments, array_keys($DepResults->toArray()));
        $data['departments'] = $departments = array_unique($departments);
        // dd($data['departments']);
        return view('billing::pdf.department-wise-report', $data);
    }

    public function exportNewReport(Request $request)
    {
        $data['from_date'] = $from_date = $request->eng_from_date;
        $data['to_date'] = $to_date = $request->eng_to_date;
        $reports = [];

        $data['CasResults'] = $CasResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
            SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
            SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
            SUM(tblpatbilling.flddiscamt) as tot_discamt,
            tblservicecost.fldreport")
        )
            ->leftJoin('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')

            ->when($from_date, function ($q) use ($from_date, $to_date) {
                return $q->where('tblpatbilling.fldtime', '>=', $from_date . ' 00:00:00')
                    ->where('tblpatbilling.fldtime', '<=', $to_date . ' 23:59:59');
            })

            ->where(function ($query) use ($from_date) {

                $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%CAS%');
                $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%REG%');
            })

            ->whereNotIn('tblpatbilling.flditemtype',  ['Medicines', 'Surgicals', 'Extra Items'])
            ->where('tblpatbilling.fldsave', 1)
            ->where('tblpatbilling.fldcomp', 'like',  '%comp01%')
            ->groupBy('tblservicecost.fldreport')
            ->get()
            ->groupBy('fldreport');

        $reports = array_merge($reports, array_keys($CasResults->toArray()));


        $data['CreResults'] = $CreResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
            SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
            SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
            SUM(tblpatbilling.flddiscamt) as tot_discamt,
            tblservicecost.fldreport")
        )
            ->leftJoin('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')

            ->when($from_date, function ($q) use ($from_date, $to_date) {
                return $q->where('tblpatbilling.fldtime', '>=', $from_date . ' 00:00:00')
                    ->where('tblpatbilling.fldtime', '<=', $to_date . ' 23:59:59');
            })
            ->where(function ($query) use ($from_date) {

                $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%CRE%');
            })
            ->whereNotIn('tblpatbilling.flditemtype',  ['Medicines', 'Surgicals', 'Extra Items'])
            ->where('tblpatbilling.fldsave', 1)
            ->where('tblpatbilling.fldcomp', 'like',  '%comp01%')
            ->groupBy('tblservicecost.fldreport')
            ->get()
            ->groupBy('fldreport');
        $reports = array_merge($reports, array_keys($CreResults->toArray()));

        $data['RetResults'] = $RetResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
            SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
            SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
            SUM(tblpatbilling.flddiscamt) as tot_discamt,
            tblservicecost.fldreport")
        )
            ->leftJoin('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')

            ->when($from_date, function ($q) use ($from_date, $to_date) {
                return $q->where('tblpatbilling.fldtime', '>=', $from_date . ' 00:00:00')
                    ->where('tblpatbilling.fldtime', '<=', $to_date . ' 23:59:59');
            })
            ->where('tblpatbilling.fldbillno', 'like',  '%RET%')
            ->where('tblpatbilling.fldcomp', 'like',  '%comp01%')
            ->whereNotIn('tblpatbilling.flditemtype',  ['Medicines', 'Surgicals', 'Extra Items'])
            ->where('tblpatbilling.fldsave', 1)
            ->groupBy('tblservicecost.fldreport')
            ->get()
            ->groupBy('fldreport');

        $reports = array_merge($reports, array_keys($RetResults->toArray()));


        $data['ForNetResults'] = $ForNetResults = PatBilling::select(
            DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
            tblservicecost.fldreport")
        )
            ->leftJoin('tblservicecost', 'tblservicecost.flditemname', '=', 'tblpatbilling.flditemname')

            ->when($from_date, function ($q) use ($from_date, $to_date) {
                return $q->where('tblpatbilling.fldtime', '>=', $from_date . ' 00:00:00')
                    ->where('tblpatbilling.fldtime', '<=', $to_date . ' 23:59:59');
            })

            ->whereNotIn('tblpatbilling.flditemtype',  ['Medicines', 'Surgicals', 'Extra Items'])
            ->where('tblpatbilling.fldsave', 1)
            ->where('tblpatbilling.fldcomp', 'like',  '%comp01%')
            ->groupBy('tblservicecost.fldreport')
            ->get()
            ->groupBy('fldreport');
        $reports = array_merge($reports, array_keys($ForNetResults->toArray()));

        $opitemamoutsumsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
        where fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldsave = 1
        and (fldencounterval  NOT LIKE '%IP%')
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = 'comp01' ";


        $opitemamoutsumData = DB::select(
            $opitemamoutsumsql
        );
        //op collection
        $data['OP_patbilling'] =   $opitemamoutsumData ? $opitemamoutsumData[0]->totaldepo : 0;


        $opitemamoutdetailsumsql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
        where fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldsave = 1
        and (fldencounterval  NOT LIKE '%IP%')
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = 'comp01' ";


        $opitemamoutsumDatadetail = DB::select(
            $opitemamoutdetailsumsql
        );
        //OP_collection_patbilling
        $data['OP_collection_patbilling'] =   $opitemamoutsumDatadetail ? $opitemamoutsumDatadetail[0]->totaldepo : 0;



        $ipitemamoutsumsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
        where fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldsave = 1
        and (fldencounterval  LIKE 'IP%')
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = 'comp01' ";


        $ipitemamoutsumData = DB::select(
                $ipitemamoutsumsql
            );
            //ipcolleciton
        $data['IP_Patbilling'] =   $ipitemamoutsumData ? $ipitemamoutsumData[0]->totaldepo : 0;

        $opdepositDataSql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
                            where fldbillno like '%dep%'
                            and fldtime >= '".$from_date . ' 00:00:00'."'
                            and fldtime <='".$to_date . ' 23:59:59'."'
                            and (fldpayitemname like '%admission deposit%'
                            or fldpayitemname like '%op deposit%'
                            or fldpayitemname like '%re deposit%'
                            or fldpayitemname like '%blood bank%'
                            or fldpayitemname like '%gate pass%'
                            or fldpayitemname like '%post-up%')

                            and fldcomp = 'comp01'
                        ";


        $opdepositData = DB::select(
                $opdepositDataSql
            );


        $totalopdeposit = $opdepositData ? $opdepositData[0]->totaldepo : 0;

        $data['deposit'] =  $totalopdeposit;


        $previousdepositDataSql = "select sum(fldprevdeposit) as prevamt from tblpatbilldetail
        where  fldbillno like '%CAS%'
         and fldtime >= '" . $from_date . ' 00:00:00' . "'
         and fldtime <='" . $to_date .' 23:59:59' . "'
         and fldpayitemname like '%Discharge Clearence%'
         and fldreceivedamt > '0'
         and fldcomp = 'comp01'
        ";


        $previousdepositData = DB::select(
        $previousdepositDataSql
            );
        $previousdeposit = $previousdepositData ? $previousdepositData[0]->prevamt : 0;
        $data['Previous_Deposit_of_Discharge_Clearence'] = ($previousdeposit != NULL) ? $previousdeposit : 0;


        $dischargeclearanceDataSql = "select sum(fldreceivedamt) as dischargeamt from tblpatbilldetail
        where  fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldpayitemname like '%Discharge Clearence%'
        and fldreceivedamt >= '0'
        and fldcomp = 'comp01'
         ";


        $dischargeclearanceData = DB::select(
                $dischargeclearanceDataSql
        );
        $dischargeclerance = $dischargeclearanceData ? $dischargeclearanceData[0]->dischargeamt : 0;
        //Discharge Garda Leko amount
        $data['Received_Deposit_of_Discharge_Clearence'] = ($dischargeclerance != NULL) ? $dischargeclerance : 0;


        $opdepositrefDataSql = "select sum(fldreceivedamt) as totalrefund from tblpatbilldetail
        where fldbillno like '%dep%'
        and fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldpayitemname like '%deposit refund%'
        and fldcomp = 'comp01'
        ";


        $opdepositrefData = DB::select(
            $opdepositrefDataSql
        );
        $opdepositref = $opdepositrefData ? $opdepositrefData[0]->totalrefund : 0;
        $data['deposit_refund'] = ($opdepositref != NULL) ? $opdepositref : 0;


        $patbilling_fldditemamtsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
        where fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldsave = 1
        and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
        and fldcomp = 'comp01' ";

        $patbilling_fldditemamtData = DB::select(
            $patbilling_fldditemamtsql
        );
        $patbilling_fldditemamt = $patbilling_fldditemamtData ? $patbilling_fldditemamtData[0]->totaldepo : 0;
        $data['patbilling_fldditemamt'] = ($patbilling_fldditemamt != NULL) ? $patbilling_fldditemamt : 0;

        $rev_amount_sumSql = "select sum(fldreceivedamt) as totalrefund from tblpatbilldetail
        where  fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldcomp = 'comp01'
        ";

        $rev_amount_sumData = DB::select(
            $rev_amount_sumSql
        );
        $rev_amount_sum = $rev_amount_sumData ? $rev_amount_sumData[0]->totalrefund : 0;
        // /Detail ko sum:
        $data['rev_amount_sum'] = ($rev_amount_sum != NULL) ? $rev_amount_sum : 0;


        $ipreturnsql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
        where fldtime >= '" . $from_date . ' 00:00:00' . "'
        and fldtime <='" . $to_date .' 23:59:59' . "'
        and fldsave = 1
        and (fldencounterval LIKE '%IP%')
        and  (fldbillno LIKE '%RET%')
        and fldcomp = 'comp01' ";


        $ipreturnsqlData = DB::select(
            $ipreturnsql
        );
        //ip returns
        $data['ipreturns'] =   $ipreturnsqlData ? $ipreturnsqlData[0]->totaldepo : 0;




        $data['reports'] = $reports = array_unique($reports);


        return view('billing::pdf.department-new-report', $data);
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
            $sql = 'select pbd.fldtime,pbd.fldbillno,pbd.fldencounterval,pbd.fldprevdeposit,pbd.flditemamt,pbd.fldtaxamt,pbd.flddiscountamt,pbd.fldchargedamt,pbd.fldreceivedamt,pbd.fldcurdeposit,pbd.flduserid,pbd.fldbilltype,pbd.fldtaxamt,pbd.flddiscountamt,pbd.fldbankname,pbd.fldchequeno,pbd.fldtaxgroup,pbd.flddiscountgroup,pbd.hospital_department_id from tblpatbilldetail as pbd where pbd.fldtime>="' . $finalfrom . '" and pbd.fldtime<="' . $finalto . '"' . $departmentquery . $searchquery . $searchnamequery . $reporttypequery . '' . $cashquery . $itemtypequery . $billingmodequery;

            $result = DB::select(
                $sql
            );
            $data['result'] = $result;
            $data['from_date'] = $finalfrom;
            $data['to_date'] = $finalto;

            return view('billing::pdf.billing-report', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/;
        } catch (\Exception $e) {
            \Log::log($e->getMessage());
        }
    }

    public function generateInvoice(Request $request)
    {
        try {
            $countdata = PatBillCount::where('fldbillno', $request->billno)->first();
            $updatedata['fldcount'] = isset($countdata) ? $countdata->fldcount + 1 : '1';
            PatBillCount::where('fldid', $countdata->fldid)->update($updatedata);
            $data['billdetail'] = $billdetail = PatBillDetail::where('fldbillno', $request->billno)->first();
            $data['itemdata'] = PatBilling::where('fldbillno', $request->billno)->get();
            $data['enpatient'] = Encounter::where('fldencounterval', $billdetail->fldencounterval)->with('patientInfo')->first();
            return view('billing::pdf.billing-invoice', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/;
        } catch (\Exception $e) {
            \Log::log($e->getMessage());
        }
    }

    public function arrayPaginator($array, $request)
    {
        $page = $request->get('page', 1);
        $perPage = 20;
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


    //Department wise collection modified by anish

    public function displayData()
    {
        try {
            $locations = DepartmentRevenue::get()->pluck('location');
            foreach ($locations as $location) {
                $isdepartment = Departmentbed::where('fldbed', $location)->first();
                if (!empty($isdepartment)) {
                    $department = $isdepartment->flddept;
                } else {
                    $department = $location;
                }
                $departments[] = $department;
            }
            //            dd($departments);
            $data = HospitalDepartment::whereIn('name', $departments)->get()->pluck('fldcomp');
            $bills = PatBillDetail::with('patBill')->whereIn('fldcomp', $data)->get();
            return $data;
        } catch (\Exception $exception) {
            dd($exception);
        }
    }
}
