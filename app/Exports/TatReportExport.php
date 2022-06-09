<?php

namespace App\Exports;

use App\Utils\Options;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings; 
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TatReportExport implements FromView,WithDrawings,ShouldAutoSize
{
    public function __construct($category = null,$from = null,$to = null)
    {
        $this->category = $category;
        $this->from = $from;
        $this->to = $to;
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
        $category = $this->category;
        $from = $this->from;
        $to = $this->to;

        $tests = \App\PatLabTest::select('fldsampleid', 'fldencounterval', 'fldgroupid', 'fldtestid', 'fldtime_start', 'fldtime_verify', 'fldtime_sample', 'fldtime_report', \DB::raw("Floor(Hour(TIMEDIFF(fldtime_report, fldtime_sample)) / 24) AS day"),  \DB::raw("(Hour(TIMEDIFF(fldtime_report, fldtime_sample)) % 24) AS hour"),  \DB::raw("(Minute(TIMEDIFF(fldtime_report, fldtime_sample)) % 24) AS minute"))
            ->whereNotNull('fldtime_report')->where([
                ["fldtime_sample", ">=", "$from 00:00:00"],
                ["fldtime_sample", "<=", "$to 23:59:59.999"],
            ])->with(['patientEncounter:fldencounterval,fldpatientval','patientEncounter.patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist','patbill:fldid,fldtime']);

        if ($category)
            $tests = $tests->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $category)->pluck('fldtestid')->toArray());

        return view('laboratory::tests.tatreportexcel', [
            'tests' => $tests->get(),
            'from' => $from,
            'to' => $to
        ]);
    }

}
