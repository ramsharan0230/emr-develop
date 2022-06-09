<?php

namespace App\Exports;

use App\PatRadioTest;
use App\Utils\Helpers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TopRadioTestExport implements FromView,ShouldAutoSize
{
    public function __construct($eng_from_date,$eng_to_date)
    {
        $this->eng_from_date=$eng_from_date??'';
        $this->eng_to_date=$eng_to_date??'';
    }

    public function view(): View
    {
            $top_lab_test=PatRadioTest::select(
                DB::raw('count(fldencounterval) as test_count'),'fldtestid'
            )
            ->whereDate('fldtime_sample','>=', $this->eng_from_date)->whereDate('fldtime_sample','<=', $this->eng_to_date)
            ->whereIn('fldstatus', ['Reported','Verified'])
            ->groupby('fldtestid')
            ->orderBy('test_count','desc')
            ->get();
        return view('laboratory::top-radio-test.excel.top-radio-test-export',compact('top_lab_test'));
    }

}
