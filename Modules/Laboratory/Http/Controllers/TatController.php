<?php

namespace Modules\Laboratory\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Utils\Helpers;
use DB;
use Excel;
use App\Exports\TatReportExport;

class TatController extends Controller
{
    public function index(Request $request)
    {
        $to = date('Y-m-d');
        $from = date('Y-m-d', strtotime("$to - 30 day"));
        /*
            select fldsampleid as enc, fldencounterval, fldtestid,fldtime_sample,fldtime_report, Floor(Hour(TIMEDIFF(fldtime_report, fldtime_sample)) / 24) as ret, (Hour(TIMEDIFF(fldtime_report, fldtime_sample)) % 24), (Minute(TIMEDIFF(fldtime_report, fldtime_sample)) % 24) from tblpatlabtest where fldtime_report is not null" & test & " and fldtime_sample>= &2 and fldtime_sample<=&3", $rData = modDatabase.$syConn.Exec(sql, cmbtest.Text, modDate.StartSqlDate(dtfir.value), modDate.EndSqlDate(dtlast.value))
        */

        $category = $request->get('category');
        $from = $request->get('from') ? Helpers::dateNepToEng($request->get('from'))->full_date : $from;
        $to = $request->get('to') ? Helpers::dateNepToEng($request->get('to'))->full_date : $to;

        $tests = \App\PatLabTest::select('fldsampleid', 'fldencounterval', 'fldgroupid', 'fldtestid', 'fldtime_start', 'fldtime_verify', 'fldtime_sample', 'fldtime_report', \DB::raw("Floor(Hour(TIMEDIFF(fldtime_report, fldtime_sample)) / 24) AS day"),  \DB::raw("(Hour(TIMEDIFF(fldtime_report, fldtime_sample)) % 24) AS hour"),  \DB::raw("(Minute(TIMEDIFF(fldtime_report, fldtime_sample)) % 24) AS minute"))
            ->whereNotNull('fldtime_report')->where([
                ["fldtime_sample", ">=", "$from 00:00:00"],
                ["fldtime_sample", "<=", "$to 23:59:59.999"],
            ])->with(['patientEncounter:fldencounterval,fldpatientval','patientEncounter.patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist','patbill:fldid,fldtime']);
        
        if ($category)
            $tests = $tests->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $category)->pluck('fldtestid')->toArray());

        if ($request->ajax()) {
            return response()->json(
                (string) view('laboratory::tests.tatdata', [
                    'tests' => $tests->paginate(50)
                ])
            );
        }

        return view('laboratory::tests.tat', [
            'categories' => Helpers::getPathoCategory('Test'),
            'tests' => $tests->paginate(50),
            'from' => Helpers::dateEngToNepdash($from)->full_date,
            'to' => Helpers::dateEngToNepdash($to)->full_date,
        ]);
    }

    public function report(Request $request)
    {
        $to = date('Y-m-d');
        $from = date('Y-m-d', strtotime("$to - 30 day"));
        $category = $request->get('category');
        $from = $request->get('from') ? Helpers::dateNepToEng($request->get('from'))->full_date : $from;
        $to = $request->get('to') ? Helpers::dateNepToEng($request->get('to'))->full_date : $to;

        $tests = \App\PatLabTest::select('fldsampleid', 'fldencounterval', 'fldgroupid', 'fldtestid', 'fldtime_start', 'fldtime_verify', 'fldtime_sample', 'fldtime_report', \DB::raw("Floor(Hour(TIMEDIFF(fldtime_report, fldtime_sample)) / 24) AS day"),  \DB::raw("(Hour(TIMEDIFF(fldtime_report, fldtime_sample)) % 24) AS hour"),  \DB::raw("(Minute(TIMEDIFF(fldtime_report, fldtime_sample)) % 24) AS minute"))
            ->whereNotNull('fldtime_report')->where([
                ["fldtime_sample", ">=", "$from 00:00:00"],
                ["fldtime_sample", "<=", "$to 23:59:59.999"],
            ])->with(['patientEncounter:fldencounterval,fldpatientval','patientEncounter.patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldptsex,fldptcontact,fldptaddvill,fldptadddist','patbill:fldid,fldtime']);

        if ($category)
            $tests = $tests->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $category)->pluck('fldtestid')->toArray());

        return view('laboratory::tests.tatreport', [
            'tests' => $tests->get(),
            'from' => $from,
            'to' => $to
        ]);
    }

    public function reportexcel(Request $request)
    {
        $to = date('Y-m-d');
        $from = date('Y-m-d', strtotime("$to - 30 day"));
        $category = $request->get('category');
        $from = $request->get('from') ? Helpers::dateNepToEng($request->get('from'))->full_date : $from;
        $to = $request->get('to') ? Helpers::dateNepToEng($request->get('to'))->full_date : $to;
        
        $export = new TatReportExport($category, $from, $to);
        ob_end_clean();
        ob_start();
        return Excel::download($export, 'TatReport.xlsx');
    }
}
