<?php

namespace Modules\Laboratory\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\PatRadioTest;
use App\Utils\Helpers;
use DB;
use Excel;
use App\Exports\TopRadioTestExport;

class TopRadioTestController extends Controller
{
    public function index(Request $request) {
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;
        $top_lab_test=collect();
        if($eng_from_date){
            $top_lab_test=PatRadioTest::select(
                DB::raw('count(fldencounterval) as test_count'),'fldtestid'
            )
            ->whereDate('fldtime_sample','>=', $eng_from_date)->whereDate('fldtime_sample','<=', $eng_to_date)
            ->whereIn('fldstatus', ['Reported','Verified'])
            ->groupby('fldtestid')
            ->orderBy('test_count','desc')
            ->get();
		}
        
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        return view('laboratory::top-radio-test.index',compact('top_lab_test','date'));
    }

    public function pdfTopRadioTestReport(Request $request){
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $top_lab_test=collect();
        if($eng_from_date){
            $top_lab_test=PatRadioTest::select(
                DB::raw('count(fldencounterval) as test_count'),'fldtestid'
            )
            ->whereDate('fldtime_sample','>=', $eng_from_date)->whereDate('fldtime_sample','<=', $eng_to_date)
            ->whereIn('fldstatus', ['Reported','Verified'])
            ->groupby('fldtestid')
            ->orderBy('test_count','desc')
            ->get();
		}
        return view('laboratory::top-radio-test.pdf.top-radio-test-pdf',compact('top_lab_test','userid'));
    }

    public function exportTopRadioTestReport(Request $request){
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;

        $export = new TopRadioTestExport($eng_from_date,$eng_to_date);
        ob_end_clean();
        ob_start();
        
        return Excel::download($export, 'top-radio-test-report.xlsx');
    }
}
