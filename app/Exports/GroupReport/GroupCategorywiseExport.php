<?php

namespace App\Exports\GroupReport;

use App\PatBilling;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class GroupCategorywiseExport implements FromView, WithDrawings, ShouldAutoSize
{
    protected $req;

    public function __construct(array $request)
    {
        $this->req = $request;
    }

    public function view(): View
    {
        $from_date = Helpers::dateNepToEng($this->req['from_date']);
            $alldata['finalfrom'] = $finalfrom = $from_date->year.'-'.$from_date->month.'-'.$from_date->date;
            $to_date = Helpers::dateNepToEng($this->req['to_date']);
            $alldata['finalto'] = $finalto = $to_date->year.'-'.$to_date->month.'-'.$to_date->date;
            $dateType = $this->req['dateType'];
            $alldata['itemRadio'] = $itemRadio = $this->req['itemRadio'];
            $alldata['billingmode'] = $billingmode = $this->req['billingmode'];
            $alldata['comp'] = $comp = $this->req['comp'];
            $alldata['selectedItem'] = $selectedItem = $this->req['selectedItem'];
            //
            $reports = [];
            $alldata['CasResults'] = $CasResults = PatBilling::select(
                DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
                SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
                SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
                SUM(tblpatbilling.flddiscamt) as tot_discamt,
                tblreportgroup.fldgroup"))
                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                ->where('tblpatbilling.fldsave',1)
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                            ->where('tblpatbilling.fldtime','<=',$finalto);
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                })
                ->when($comp != "%", function ($q) use ($comp){
                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                })
                ->when($billingmode != "%", function ($q) use ($billingmode){
                    return $q->where('tblpatbilling.fldbillingmode','like',$billingmode);
                })
                ->where('tblreportgroup.fldgroup','!=',null)
                ->where(function ($query) {
                    $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%CAS%');
                    $query->orwhere('tblpatbilling.fldbillno', 'LIKE', '%REG%');
                })
                ->groupBy('tblreportgroup.fldgroup')
                ->get()
                ->groupBy('fldgroup');
            $reports = array_merge($reports, array_keys($CasResults->toArray()));

            $alldata['CreResults'] = $CreResults = PatBilling::select(
                DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
                SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
                SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
                SUM(tblpatbilling.flddiscamt) as tot_discamt,
                tblreportgroup.fldgroup"))
                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                ->where('tblpatbilling.fldsave',1)
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                            ->where('tblpatbilling.fldtime','<=',$finalto);
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                })
                ->when($comp != "%", function ($q) use ($comp){
                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                })
                ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                ->where('tblreportgroup.fldgroup','!=',null)
                ->where('tblpatbilling.fldbillno', 'like',  '%CRE%')
                ->groupBy('tblreportgroup.fldgroup')
                ->get()
                ->groupBy('fldgroup');
            $reports = array_merge($reports, array_keys($CreResults->toArray()));

            $alldata['RetResults'] = $RetResults = PatBilling::select(
                DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
                SUM(tblpatbilling.flditemrate * tblpatbilling.flditemqty) as totalamount,
                SUM(tblpatbilling.fldtaxamt) as tot_taxamt,
                SUM(tblpatbilling.flddiscamt) as tot_discamt,
                tblreportgroup.fldgroup"))
                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                ->where('tblpatbilling.fldsave',1)
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                            ->where('tblpatbilling.fldtime','<=',$finalto);
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                })
                ->when($comp != "%", function ($q) use ($comp){
                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                })
                ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                ->where('tblreportgroup.fldgroup','!=',null)
                ->where('tblpatbilling.fldbillno', 'like',  '%RET%')
                ->groupBy('tblreportgroup.fldgroup')
                ->get()
                ->groupBy('fldgroup');
            $reports = array_merge($reports, array_keys($RetResults->toArray()));

            $alldata['ForNetResults'] = $ForNetResults = PatBilling::select(
                DB::raw("SUM(tblpatbilling.fldditemamt) as tot_itemamt,
                tblreportgroup.fldgroup"))
                ->leftJoin('tblreportgroup','tblreportgroup.flditemname','=','tblpatbilling.flditemname')
                ->where('tblpatbilling.fldsave',1)
                ->when(($finalfrom == $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilling.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto) && $dateType == "entry_date", function ($q) use ($finalfrom,$finalto){
                    return $q->where('tblpatbilling.fldtime','>=',$finalfrom)
                            ->where('tblpatbilling.fldtime','<=',$finalto);
                })
                ->when($itemRadio == "select_item", function ($q) use ($selectedItem){
                    return $q->where('tblreportgroup.fldgroup','like',$selectedItem);
                })
                ->when($comp != "%", function ($q) use ($comp){
                    return $q->where('tblpatbilling.fldcomp','like',$comp);
                })
                ->where('tblpatbilling.fldbillingmode','like',$billingmode)
                ->where('tblreportgroup.fldgroup','!=',null)
                ->groupBy('tblreportgroup.fldgroup')
                ->get()
                ->groupBy('fldgroup');
            $reports = array_merge($reports, array_keys($ForNetResults->toArray()));

                $opitemamoutsumsql = "select sum(fldditemamt) as totaldepo from tblpatbilling
                where fldtime >= '" . $finalfrom . "'
                and fldtime <='" . $finalto . "'
                and fldsave = 1
                and (fldencounterval  NOT LIKE '%IP%')
                and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
                and fldcomp = 'comp01' ";


                $opitemamoutsumData = DB::select(
                    $opitemamoutsumsql
                );
                //op collection
                $alldata['OP_patbilling'] =   $opitemamoutsumData ? $opitemamoutsumData[0]->totaldepo : 0;


                $opitemamoutdetailsumsql = "select sum(fldreceivedamt) as totaldepo from tblpatbilldetail
                where fldtime >= '" . $finalfrom . "'
                and fldtime <='" . $finalto . "'
                and fldsave = 1
                and (fldencounterval  NOT LIKE '%IP%')
                and  (fldbillno LIKE '%CAS%' or  fldbillno LIKE '%REG%' or  fldbillno LIKE '%RET%')
                and fldcomp = 'comp01' ";


                $opitemamoutsumDatadetail = DB::select(
                    $opitemamoutdetailsumsql
                );
                //OP_collection_patbilling
                $alldata['OP_collection_patbilling'] =   $opitemamoutsumDatadetail ? $opitemamoutsumDatadetail[0]->totaldepo : 0;



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
                $alldata['IP_Patbilling'] =   $ipitemamoutsumData ? $ipitemamoutsumData[0]->totaldepo : 0;

                $opdepositDataSql = "select sum(fldcurdeposit) as totaldepo from tblpatbilldetail
                                    where fldbillno like '%dep%'
                                    and fldtime >= '".$finalfrom."'
                                    and fldtime <='".$finalto."'
                                    and (fldpayitemname like '%admission deposit%'
                                    or fldpayitemname like 'op deposit'
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

                $alldata['deposit'] =  $totalopdeposit;


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
                $alldata['Previous_Deposit_of_Discharge_Clearence'] = ($previousdeposit != NULL) ? $previousdeposit : 0;


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
                $alldata['Received_Deposit_of_Discharge_Clearence'] = ($dischargeclerance != NULL) ? $dischargeclerance : 0;


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
                $alldata['deposit_refund'] = ($opdepositref != NULL) ? $opdepositref : 0;


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
                $alldata['patbilling_fldditemamt'] = ($patbilling_fldditemamt != NULL) ? $patbilling_fldditemamt : 0;

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
                $alldata['rev_amount_sum'] = ($rev_amount_sum != NULL) ? $rev_amount_sum : 0;


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
                $alldata['ipreturns'] =   $ipreturnsqlData ? $ipreturnsqlData[0]->totaldepo : 0;

            $alldata['reports'] = $reports = array_unique($reports);
            $alldata['certificate'] = "GROUP CATEGORY";
            return view('reports::groupreport.excel.export-categorywise-excel',$alldata);
    }

    public function drawings()
    {
        if (Options::get('brand_image')) {
            if (file_exists(public_path('uploads/config/' . Options::get('brand_image')))) {
                $drawing = new Drawing();
                $drawing->setName(isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '');
                $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan']) ? Options::get('siteconfig')['system_slogan'] : '');
                $drawing->setPath(public_path('uploads/config/' . Options::get('brand_image')));
                $drawing->setHeight(80);
                $drawing->setCoordinates('A2');
            } else {
                $drawing = [];
            }
        } else {
            $drawing = [];
        }
        return $drawing;
    }
}
