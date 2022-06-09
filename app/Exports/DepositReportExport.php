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

class DepositReportExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date,string $to_date, string $lastStatus, int $expense, int $payment,string $type)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->lastStatus = $lastStatus;
        $this->expense = $expense;
        $this->payment = $payment;
        $this->type = $type;
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
        $from_date = Helpers::dateNepToEng($this->from_date);
        $data['finalfrom'] = $finalfrom = $from_date->full_date . " 00:00:00";
        $to_date = Helpers::dateNepToEng($this->to_date);
        $data['finalto'] = $finalto = $to_date->full_date . " 23:59:59";
        $data['last_status'] = $last_status = $this->lastStatus;
	    $data['type'] = $type = $this->type;
        $data['expense'] = $expense = $this->expense;
        $data['payment'] = $payment = $this->payment;

        $data['depositData'] = $depositData = PatBillDetail::select("tblencounter.fldregdate as fldregdate", "tblencounter.fldcurrlocat as fldcurrlocat", "tblencounter.fldpatientval as fldpatientval", "tblencounter.fldadmission as fldadmission", "tblencounter.fldadmitlocat as fldadmitlocat", "tblencounter.fldencounterval as fldencounterval", 'tblpatbilldetail.fldreceivedamt as fldcashdeposit','tblpatbilldetail.fldpayitemname as deposittype','tblpatbilldetail.fldbillno as depositbillno','tblpatbilldetail.fldtime as depositdate')
                ->join('tblencounter','tblencounter.fldencounterval','=','tblpatbilldetail.fldencounterval')
                ->when(($finalfrom == $finalto), function ($q) use ($finalfrom) {
                    return $q->where(DB::raw("(STR_TO_DATE(tblpatbilldetail.fldtime,'%Y-%m-%d'))"),$finalfrom);
                })
                ->when(($finalfrom != $finalto), function ($q) use ($finalfrom,$finalto) {
                    return $q->where('tblpatbilldetail.fldtime', '>=', $finalfrom)
                            ->where('tblpatbilldetail.fldtime', '<=', $finalto);
                })
                ->when($last_status !== "%", function ($q) use ($last_status) {
                    return $q->where('tblencounter.fldadmission', $last_status);
                })
		        ->when(isset($type) && $type != 'All', function ($q) use ($type){
			        return $q->where('tblpatbilldetail.fldpayitemname',$type);
		        })
		        ->where('tblpatbilldetail.fldpayitemname','!=','Discharge Clearance')
		        ->where('tblpatbilldetail.fldpayitemname','!=','Credit Clearance')
                ->where('tblpatbilldetail.fldbillno','like','DEP%')
                ->with('patientInfo')
                ->groupBy('tblpatbilldetail.fldencounterval')
                ->get();

        return view('dispensar::deposit-report-excel',$data);
    }

}
