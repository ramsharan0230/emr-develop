<?php

namespace App\Exports;

use App\Encounter;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DepositReportExportNew implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct(string $from_date,string $to_date, string $lastStatus, string $deposit)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->lastStatus = $lastStatus;
        $this->deposit = $deposit;
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
        $data['deposit'] = $deposit = $this->deposit;

        $data['depositData'] = $depositData = Encounter::select("fldpttype","fldregdate","fldcurrlocat","fldpatientval","fldadmission","fldadmitlocat","fldencounterval","fldcashdeposit","fldcashcredit")
            ->when(($finalfrom == $finalto), function ($q) use ($finalfrom) {
                return $q->where(DB::raw("(STR_TO_DATE(fldregdate,'%Y-%m-%d'))"),$finalfrom);
            })
            ->when(($finalfrom != $finalto), function ($q) use ($finalfrom,$finalto) {
                return $q->where('fldregdate', '>=', $finalfrom)
                        ->where('fldregdate', '<=', $finalto);
            })
            ->when($last_status != "%", function ($q) use ($last_status){
                return $q->where('fldadmission',$last_status);
            })
            ->when($deposit == "Positive", function ($q){
                return $q->where('fldcashdeposit','>',0);
            })
            ->when($deposit == "Negative", function ($q){
                return $q->where('fldcashdeposit','<',0);
            })
            ->with('patientInfo','patBillDetails')
            ->get();
        //dd($data);
//        dd($data['depositData'][0]->patientInfo);
//        ,'patBill.parentDetail'
        return view('dispensar::deposit-report-excel-new',$data);
    }

}
