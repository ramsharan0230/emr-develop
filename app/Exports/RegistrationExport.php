<?php

namespace App\Exports;

use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class RegistrationExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct($name, $from_date, $to_date, $department)
    {
        $this->name = $name;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->department = $department;
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
        $department = $this->department;
        $name = $this->name;
        $from_date = $this->from_date ? Helpers::dateNepToEng($this->from_date)->full_date : date('Y-m-d');
        $to_date = $this->to_date ? Helpers::dateNepToEng($this->to_date)->full_date : date('Y-m-d');
   
        $patients = \App\Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'flduserid')
            ->with([
                'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist',
                'patientInfo.credential:fldpatientval,fldusername,fldpassword',
                'consultant:fldencounterval,fldorduserid',
                'consultant.userRefer:flduserid,firstname,middlename,lastname'
            ])->where([
                ['fldregdate', '>=', "{$from_date} 00:00:00"],
                ['fldregdate', '<=', "{$to_date} 23:59:59"],
            ])
            ->orderBy('fldregdate', 'DESC');

        if ($name) {
            $name = $name;
            $patients = $patients->whereHas('patientInfo', function ($q) use ($name) {
               
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name . '%');
            });
        }

        if ($department)
            $patients = $patients->where('fldcurrlocat', $department);
        $patients = $patients->get();

        return view('registration::registration-excel', compact('name', 'from_date', 'to_date', 'department', 'patients'));
    }

}
