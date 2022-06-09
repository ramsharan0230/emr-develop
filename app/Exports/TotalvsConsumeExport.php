<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

use Illuminate\Support\Facades\DB;


class TotalvsConsumeExport implements FromView,ShouldAutoSize
{
    

    public function __construct(String $finalfrom, String $todate)
    {
        $this->finalfrom = $finalfrom;
        $this->todate =  $todate;

    }

 
    public function view(): View
    {
        $todate = $this->todate;
        $finalfrom = $this->finalfrom;

        $userid = \Auth::guard('admin_frontend')->user()->flduserid;

        $result = DB::table('tblencounter as e')
            ->selectRaw('*,sum(fldchargedamt) as total,count(fldbillno) as bills')
            ->join('tblpatientinfo as p','p.fldpatientval','e.fldpatientval')
            ->join('tblpatient_insurance_details as id','id.fldpatientval','p.fldpatientval')
            ->join('tblpatbilldetail as pb','pb.fldencounterval','e.fldencounterval')
            ->where('e.fldregdate','>=',$finalfrom . ' 00:00:00')
            ->where('e.fldregdate','<=',$todate . ' 23:59:59.99')
            ->groupby('e.fldencounterval')
            ->get();


        return view('hi::pdf.totalvsconsume-report-export',array('result' => $result,'fromdateeng' => $finalfrom, 'todateeng' => $todate, 'userid' => $userid)); 
    }
}
