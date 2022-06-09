<?php

namespace App\Exports;

use App\Encounter;
use App\PatBillDetail;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\PatBilling;

class DepartmentWiseReportExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $fromdate,string $todate)
    {
        $this->fromdate = $fromdate;
        $this->todate = $todate;
    }

    public function drawings()
    {
        if(Options::get('brand_image')){
            if(file_exists(public_path('uploads/config/'.Options::get('brand_image')))){
                $drawing = new Drawing();
                $drawing->setName(isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name']:'');
                $drawing->setDescription(isset(Options::get('siteconfig')['system_slogan'])?Options::get('siteconfig')['system_slogan']:'');
                $drawing->setPath(public_path('uploads/config/'.Options::get('brand_image')));
                $drawing->setHeight(80);
                $drawing->setCoordinates('B2');
            }else{
                $drawing = [];
            }
        }else{
            $drawing = [];
        }
        return $drawing;
    }

    public function view(): View
    {
        $fromdate = $this->fromdate;
        $todate = $this->todate;
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
                sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END) AS IP_Cash_Amount,
                sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END) AS OP_Cash_Amount,
                sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CRE%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else 0.00 END) AS IP_Credit_Amount,
                sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CRE%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END) AS OP_Credit_Amount,
                sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.flddiscamt) else '0.00' END) AS IP_Discount_Amount,
                sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.flddiscamt) else '0.00' END) AS OP_Discount_Amount,
                sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.fldtaxamt) else '0.00' END) AS IP_Tax_Amount,
                sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'CAS%' or tblpatbilling.fldbillno like 'REG%') then (tblpatbilling.fldtaxamt) else '0.00' END) AS OP_Tax_Amount,
                sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END) AS IP_Return_Amount,
                sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.flditemrate * tblpatbilling.flditemqty) else '0.00' END) AS OP_Return_Amount,
                sum(case when (tblpatbilling.fldencounterval like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.fldtaxamt) else '0.00' END) AS IP_Return_Tax_Amount,
                sum(case when (tblpatbilling.fldencounterval not like 'IP%') AND (tblpatbilling.fldbillno like 'RET%') then (tblpatbilling.fldtaxamt) else '0.00' END) AS OP_Return_Tax_Amount")
            ->where([
                ["tblpatbilling.fldtime", ">=", "$fromdate 00:00:00"],
                ["tblpatbilling.fldtime", "<=", "$todate 23:59:59.999"],
                ["tblpatbilling.fldsave", 1],
            ])
            ->where("tblpatbilling.fldcomp", Helpers::getCompName())
            // ->whereNotNull('s.fldreport')
            ->groupBy('s.fldreport')
            ->get();

        return view('departmentwisereport::excel',$data);
    }

}
