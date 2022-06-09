<?php

namespace Modules\Laboratory\Http\Controllers;

use App\PatLabTest;
use App\SampleTracking;
use App\Test;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SampleTrackingController extends Controller
{
    public function index()
    {
        $date = date('Y-m-d');
        $data['sampleId'] = PatLabTest::where([
            ["fldtime_report", ">=", "$date 00:00:00"],
            ["fldtime_report", "<=", "$date 23:59:59.999"],
        ])
            ->where('fldsampleid', '!=', null)
            ->with(['test', 'patientEncounter', 'patientEncounter.patientInfo', 'tracking'])
            ->groupBy('fldsampleid')
            ->limit(10)
            ->get();

        $data['testNames'] = Test::select('fldcategory')->groupBy('fldcategory')->get();
        //        dd($data);
        return view('laboratory::sample-tracking', $data);
    }

    public function createUpdateSampleTrackIn(Request $request)
    {
        $insertData['sample_id'] = $request->sampleId;
        $insertData['test_category'] = $request->testCategory;
        if ($request->inout == "in") {
            $insertData['sample_in'] = 1;
            $insertData['sample_in_time'] = now();
            $insertData['in_user_id'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
            $idSampleTracking = SampleTracking::insertGetId($insertData);

            return response()->json([
                'tracking_id' => $idSampleTracking,
                'status' => 'Success',
            ]);
        }


    }

    public function createUpdateSampleTrackOut(Request $request)
    {
        $insertData['sample_out'] = 1;
        $insertData['sample_out_time'] = now();
        $insertData['out_user_id'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
        $idSampleTracking = SampleTracking::where('id', $request->trackingId)->update($insertData);
        return response()->json([
            'tracking_id' => $idSampleTracking,
            'status' => 'Success',
        ]);
    }

}
