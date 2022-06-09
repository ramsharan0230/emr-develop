<?php

namespace App\Exports;

use App\Encounter;
use App\Utils\Helpers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PatientTestLogExport implements FromView,ShouldAutoSize
{
    public function __construct($patient_id,$eng_from_date,$eng_to_date)
    {
        $this->patient_id=$patient_id??'';
        $this->eng_from_date=$eng_from_date??'';
        $this->eng_to_date=$eng_to_date??'';
    }

    // public function headings(): array
    // {
    //     return [
    //         'Name On Card',
    //         'Card No.',
    //         'Exp Month',
    //         'Exp. Year',
    //         'CVV',
    //     ];
    // }

    public function view(): View
    {
        ini_set("memory_limit", "10056M");
        $query=DB::table('tblpatlabtest as plt')
            ->select(
                "plt.fldid",
                "pi.fldpatientval",
                "plt.fldencounterval",
                "pi.fldptnamefir",
                "pi.fldptnamelast",
                "plt.fldsampleid",
                "plt.fldtestid",
                "plt.fldtime_sample",
                "plt.flduserid_sample",
                "plt.fldtime_report",
                "plt.flduserid_report",
                "plt.fldtime_verify",
                "plt.flduserid_verify"
                )
            ->join('tblencounter as en','plt.fldencounterval','=','en.fldencounterval')
            ->join('tblpatientinfo as pi','en.fldpatientval','=','pi.fldpatientval');
                if($this->patient_id){
                $query->where('pi.fldpatientval','like', '%'.$this->patient_id.'%');
            }
            if($this->eng_from_date){
            $query=	$query->whereDate('plt.fldtime_sample','>=', $this->eng_from_date)->whereDate('plt.fldtime_sample','<=', $this->eng_to_date);
            }
            $records=$query->whereRaw(DB::raw('fldid in (SELECT MAX(fldid) from tblpatlabtest GROUP BY fldencounterval)'))->orderBy('plt.fldencounterval','desc')->get();
        return view('reports::patient-test-log-report.excel.patient-test-log-export',compact('records'));
    }

}
