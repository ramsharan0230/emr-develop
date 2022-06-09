<?php

namespace App\Exports;

use App\Utils\Helpers;
use App\Utils\Options;
use App\Encounter;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PatientFollowListExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct($consultant, $from_date, $to_date, $department)
    {
        $this->consultant = $consultant;
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
        $consultant = $this->consultant;
        $from_date = $this->from_date ? Helpers::dateNepToEng($this->from_date)->full_date : date('Y-m-d');
        $to_date = $this->to_date ? Helpers::dateNepToEng($this->to_date)->full_date : date('Y-m-d');

        $patients= Encounter::select('fldencounterval', 'fldpatientval', 'fldcurrlocat', 'flduserid', 'fldregdate', 'created_by','fldfollowdate')
        ->whereNotNull('fldfollowdate')
        // ->whereHas('allConsultant', function ($query) {
        //     $query->where('fldcomment','!=','Follow Up');
        // })
        ->with([
            'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist',
            'allConsultant'=>function($query){
                $query->where('fldcomment','!=','Follow Up');
            }
        ])->whereHas('patientInfo')->orderBy('fldfollowdate', 'DESC');

        if ($consultant) {
            $patients = $patients->where('flduserid', $consultant);
        }
        if ($from_date) {
            $patients = $patients->where('fldfollowdate', '>=', $from_date . " 00:00:00");
        }
        if ($to_date) {
            $patients = $patients->where('fldfollowdate', '<=', $to_date . " 23:59:59");
        }
        if ($department) {
            $patients = $patients->where(function($q) use ($department) {
                                $q->where('fldadmitlocat', $department)
                                ->orWhere('fldcurrlocat', $department);
                        });
        }

        $data['patients'] = $patients->get();
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['patients_counts'] = $patients->count();
        return view('patient::patientfollowlist-excel',$data);
    }

}
