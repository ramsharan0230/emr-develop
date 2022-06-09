<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class UnsampledTestExport implements FromView,ShouldAutoSize
{
    public function __construct($eng_from_date,$eng_to_date,$status,$patient_id,$encounter_id)
    {
        $this->eng_from_date=$eng_from_date;
        $this->eng_to_date=$eng_to_date;
        $this->patient_id=$patient_id;
        $this->encounter_id=$encounter_id;
        $this->status=$status;
    }

    public function view(): View
    {
        $query=\DB::table('tbl_unsampled_test as tut')
                    ->select(
                        'tut.bill_no',
                        'tpb.fldbillno',
                        'tut.testid',
                        'tut.encounter_id',
                        'tut.user_id',
                        'tut.fldmethod',
                        'tut.fldstatus',
                        'tut.fldgroupid',
                        'tut.date',
                        'tpi.fldpatientval',
                        'tpi.fldptnamefir',
                        'tpi.fldmidname',
                        'tpi.fldptnamelast',
                        'tpi.fldptbirday',
                        'tpi.fldptsex',
                        'tpi.fldptcontact'
                        )
                    ->join('tblencounter as tent','tut.encounter_id','=','tent.fldencounterval')
                    ->join('tblpatbilling as tpb','tpb.fldid','=','tut.fldgroupid')
                    ->join('tblpatientinfo as tpi','tpi.fldpatientval','=','tent.fldpatientval')
                    ->whereDate('tut.date','>=', "$this->eng_from_date 00:00:00")->whereDate('tut.date','<=', "$this->eng_to_date 23:59:59.999");
            if($this->encounter_id){
            $query = $query->where('tut.encounter_id',$this->encounter_id);
            }
            if($this->patient_id){
            $query = $query->where('tpi.fldpatientval',$this->patient_id);
            }
            if($this->status){
                $query = $query->where('tut.fldstatus',$this->status);
            }
            $unsampled_test=$query->groupBy('tut.encounter_id')->get();
            $eng_from_date=$this->eng_from_date;
            $eng_to_date=$this->eng_to_date;
        return view('laboratory::excel.unsampled_request_excel',compact('unsampled_test','eng_from_date','eng_to_date'));
    }

}
