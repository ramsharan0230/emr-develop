<?php

namespace App\Exports;

use App\PatLabTest;
use App\Utils\Helpers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PatientCreditExport implements FromView,ShouldAutoSize
{
    public function __construct($department,$patient_name,$patient_id,$encounter_id,$patient_number,$amount_type)
    {
        $this->department=$department;
        $this->patient_name=$patient_name;
        $this->patient_id=$patient_id;
        $this->encounter_id=$encounter_id;
        $this->patient_number=$patient_number;
        $this->amount_type=$amount_type;
    }

    public function view(): View
    {

        $query=DB::table('tblpatbilldetail as tptbd')
                                ->select(
                                    'tptbd.fldid',
                                    'tptbd.fldtime',
                                    'tptbd.fldbillno',
                                    'tpi.fldpatientval',
                                    'tptbd.fldencounterval',
                                    'tpi.fldptnamefir',
                                    'tpi.fldptnamelast',
                                    'tpi.fldptbirday',
                                    'tpi.fldptsex',
                                    'tpi.fldptcontact',
                                    'tptbd.fldcurdeposit'
                                    )
                                ->join('tblencounter as tent','tptbd.fldencounterval','=','tent.fldencounterval')
                                ->join('tblpatientinfo as tpi','tpi.fldpatientval','=','tent.fldpatientval')
                                ->where('tptbd.fldbilltype','Credit');
        if($this->department){
            $query->where('tptbd.fldcomp', $this->department);
        }
        if($this->patient_name){
            $query->whereRaw('concat(tpi.fldptnamefir," ",tpi.fldptnamelast) like ?', "%{$this->patient_name}%");
        }
        if($this->patient_id){
            $query->where('tpi.fldpatientval', 'like', '%'.$this->patient_id.'%');
        }
        if($this->encounter_id){
            $query->where('tptbd.fldencounterval', 'like', '%'.$this->encounter_id.'%');
        }
        if($this->patient_number){
            $query->where('tpi.fldptcontact', $this->patient_number);
        }
        if($this->amount_type==0){
            $query->whereRaw('tptbd.fldid in (SELECT MAX(fldid) from tblpatbilldetail where fldcurdeposit < 0  GROUP BY fldencounterval)');
        }
        if($this->amount_type==1){
            $query->whereRaw('tptbd.fldid in (SELECT MAX(fldid) from tblpatbilldetail where fldcurdeposit > 0  GROUP BY fldencounterval)');
        }
        if($this->amount_type!=0){
            $query->whereRaw('tptbd.fldid in (SELECT MAX(fldid) from tblpatbilldetail where fldcurdeposit < 0  GROUP BY fldencounterval)');
        }
        $patient_credit_report=$query
        ->orderBy('tptbd.fldencounterval','desc')
        ->get();
        $amount_type=$this->amount_type;
        return view('reports::patient-credit-report.excel.patient-credit-excel-report',compact('patient_credit_report','amount_type'));
    }

}
