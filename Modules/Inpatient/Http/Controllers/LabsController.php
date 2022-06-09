<?php

namespace Modules\Inpatient\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\PatLabTest;

class LabsController extends Controller
{
    public function getQuantiQualiData(Request $request)
    {
        $encounter_id = \Session::get('inpatient_encounter_id');
        $types = $request->get('radio');
        $ret_data = PatLabTest::select('fldtestid')
            ->distinct()
            ->where([
                'fldencounterval' => $encounter_id,
                // 'fldtest_type' => $types,
                'flvisible' => 'Visible',
            ])->Where(function($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            })->get();

        return response()->json($ret_data);
    }

    public function getTestsData(Request $request)
    {
        $encounter_id = \Session::get('inpatient_encounter_id');
        $dataType = $request->get('dataType');
        $all_tests = PatLabTest::select('fldid', 'fldsampletype', 'fldtestid', 'fldstatus', 'fldsave_report', 'fldabnormal', 'fldtime_sample', 'fldtime_report', 'fldtest_type', 'fldreportquali')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldtestid' => $dataType,
            ])->Where(function($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            })->get();

        return response()->json($all_tests);        
    }
    
}
