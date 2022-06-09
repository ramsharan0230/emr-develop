<?php

namespace App\Exports;

use App\Encounter;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DispensingRemarkReportExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct($encounter_id, $name, $phone, $remark, $from_date, $to_date)
    {
        $this->encounter_id = $encounter_id;
        $this->name = $name;
        $this->phone = $phone;
        $this->remark = $remark;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
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
        $encounter_id = $this->encounter_id;
        $name = $this->name;
        $phone = $this->phone;
        $remark = $this->remark;
        $from_date = $this->from_date ? Helpers::dateNepToEng($this->from_date)->full_date : date('Y-m-d');
        $to_date = $this->to_date ? Helpers::dateNepToEng($this->to_date)->full_date : date('Y-m-d');

        $remarks = \App\Dispenseremark::select('fldid', 'fldencounterval', 'fldbillno', 'fldtime', 'fldremark')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptcontact,fldptsex,fldptbirday,fldrank',
            ])->where('fldtime', ">=", "{$from_date} 00:00:00")
            ->where('fldtime', "<=", "{$to_date} 23:59:59.999");

        if ($encounter_id)
            $remarks->where('fldencounterval', $encounter_id);
        if ($remark)
            $remarks->where('fldremark', 'like', "%{$remark}%");
        if ($name)
            $remarks->whereHas('encounter.patientInfo', function($q) use ($name) {
               
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', "%{$name}%");
            });
        if ($phone)
            $remarks->whereHas('encounter.patientInfo', function($q) use ($phone) {
              
                $q->where('fldptcontact', 'like', "%{$phone}%");
            });
        $remarks = $remarks->get();

        $from_date = Helpers::dateEngToNepdash($from_date)->full_date;
        $to_date = Helpers::dateEngToNepdash($to_date)->full_date;

        return view('dispensar::remark-report-excel', compact('encounter_id', 'name', 'phone', 'remark', 'from_date', 'to_date', 'remarks'));
    }

}
