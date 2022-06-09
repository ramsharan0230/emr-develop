<?php

namespace App\Exports;

use App\Encounter;
use App\PatBillDetail;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\DB;

class DishchargeBillExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $comp,string $from_date, string $to_date, string $serviceType)
    {
        $this->comp = $comp;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->serviceType = $serviceType;
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
        $data['comp'] = $comp = $this->comp;
        $data['serviceType'] = $serviceType = $this->serviceType;
        $from_date = Helpers::dateNepToEng($this->from_date);
        $data['finalfrom'] = $finalfrom = $from_date->year . '-' . $from_date->month . '-' . $from_date->date . " 00:00:00";
        $to_date = Helpers::dateNepToEng($this->to_date);
        $data['finalto'] = $finalto = $to_date->year . '-' . $to_date->month . '-' . $to_date->date . " 23:59:59";
        $results = Encounter::select(DB::raw('tblencounter.fldencounterval,tblencounter.fldpatientval,tblencounter.flddod,tblencounter.flddoa,
                        GROUP_CONCAT(tblpatbilldetail.fldbillno) as fldbillno,GROUP_CONCAT(tblpatbilldetail.remarks) as reason,
                        SUM(tblpatbilldetail.fldprevdeposit) as deposit_amt,SUM(tblpatbilldetail.flditemamt) as item_amt,
                        SUM(tblpatbilldetail.fldtaxamt) as tax_amt,SUM(tblpatbilldetail.flddiscountamt) as discount_amt,tblpatbilldetail.fldreceivedamt,
                        SUM(tblpatbilldetail.fldcurdeposit) as current_deposit'))
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
                        ->when($comp != "%", function ($q) use ($comp) {
                            return $q->where('tblpatbilldetail.fldcomp', 'like', $comp);
                        })
                        ->when($serviceType == "pharmacy", function ($q){
                            return $q->where(function ($query) {
                                $query->orwhere('tblpatbilldetail.fldbillno', 'LIKE', 'PHM%');
                                $query->orwhere('tblpatbilldetail.fldpayitemname', 'LIKE', '%Pharmacy Deposit%');
                            });
                            // return $q->where('tblpatbilldetail.fldbillno', 'like', "PHM%");
                        })
                        ->when($serviceType == "service", function ($q){
                            return $q->where('tblpatbilldetail.fldbillno', 'not like', "PHM%");
                        })
                        ->groupBy('tblencounter.fldencounterval')
                        ->get();
        $data['results'] = $results;
        $html = "";
        foreach ($results as $key => $r) {
            if($comp != "%") {
                $compQuery = "and fldcomp like ".$comp;
            }else{
                $compQuery = "";
            }
            if($serviceType == "pharmacy"){
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

            // $depositDataSql = "select sum(fldreceivedamt) as totaldepo,GROUP_CONCAT(fldbillno) as fldbillno from tblpatbilldetail
            //             where fldbillno like '%dep%' and fldencounterval like '".$r->fldencounterval."' and
            //             (".$payItemNameQuery.") ".$serviceQuery." ".$compQuery;
            $depositData = DB::select(
                $depositDataSql
            );
            $deposit = $depositData ? $depositData[0]->totaldepo : 0;
            $depositBills = $depositData ? $depositData[0]->fldbillno : "";
            $depositBills = explode(",",$depositBills);
            $depositrefDataSql = "select sum(fldreceivedamt) as totalrefund,GROUP_CONCAT(fldbillno) as fldbillno from tblpatbilldetail where fldbillno like '%dep%' and (".$depositRefundQuery.")  and fldencounterval like '".$r->fldencounterval."'";
            $depositrefData = DB::select(
                $depositrefDataSql
            );
            $depositref = $depositrefData ? $depositrefData[0]->totalrefund : 0;
            $depositrefBills = $depositrefData ? $depositrefData[0]->fldbillno : "";
            $depositrefBills = explode(",",$depositrefBills);

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

            $html .= '<tr>
                        <td>' . ++$key . '</td>
                        <td>' . $r->fldencounterval .'</td>
                        <td>' . $r->patientInfo->fldrankfullname .'</td>
                        <td>' . implode("<br>",array_unique($depositBills)) . '</td>
                        <td>' . implode("<br>",array_unique($invoiceBill)) . '</td>
                        <td>' . implode("<br>",array_unique($depositrefBills)) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat($deposit) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat($totNetBillAmt) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat($adjustmentAmt) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat($r->discount_amt). '</td>
                        <td>' . \App\Utils\Helpers::numberFormat($depositref) . '</td>
                        <td>' . \App\Utils\Helpers::numberFormat($remainingRefund) . '</td>
                        <td>' . \Carbon\Carbon::parse($r->flddoa)->format('Y-m-d') . '</td>
                        <td>' . \Carbon\Carbon::parse($r->flddod)->format('Y-m-d') . '</td>
                        <td>' . $r->reason . '</td>';

            $html .= '</tr>';
        }
        $data['html'] = $html;
        return view('reports::dischargeBillsReport.discharge-bills-excel',$data);
    }

}
