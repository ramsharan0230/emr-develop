<?php

namespace Modules\Radiology\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\PatBilling;
use App\Utils\Helpers;

class RadioVerifyController extends Controller
{
    public function index(Request $request)
    {
        return view('radiology::tests.verification', [
            'tests' => $this->_getPatRadioTest($request),
            // 'radiotemplates' => \App\RadioTemplate::all(),
            'categories' => Helpers::getPathoCategory('Radio'),
        ]);
    }

    public function getPatientTest(Request $request)
    {
        $tests = $this->_getPatRadioTest($request);
        return response()->json(compact('tests'));
    }

    private function _getPatRadioTest($request)
    {
        $category = $request->get('category');
        $encounter_id = $request->get('encounter_id');
        $name = $request->get('name');
        $rank = $request->get('rank');
        $unit = $request->get('unit');
        $status = $request->get('status');
        $from_date = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d');
        $to_date = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');

        $tests = \App\PatRadioTest::select('tblpatradiotest.fldstatus', 'fldcomment', 'tblpatradiotest.fldencounterval', 'tblpatradiotest.fldtestid', 'flduserid_report', 'tblpatradiotest.fldid', 'fldreportquali', 'tblpatbilling.fldreason')->with([
                'radio:fldexamid,fldcategory',
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptaddvill,fldptadddist,fldptcontact,fldptsex,fldptbirday,fldrank',
                'encounter.consultant:fldencounterval,fldconsultname,flduserid',
                'encounter.consultant.user:flduserid,firstname,middlename,lastname',
            ])->leftJoin('tblgroupradio', 'tblpatradiotest.fldtestid', '=', 'tblgroupradio.fldtestid')
            ->leftJoin('tblpatbilling', function($join) {
                $join->on('tblpatbilling.fldencounterval', '=', 'tblpatradiotest.fldencounterval');
                $join->on('tblgroupradio.fldtestid','=', 'tblpatradiotest.fldtestid');
            })->where([
                ['fldsave_report', '1'],
                // ['tblpatradiotest.fldstatus', 'Reported'],
            ])->orderBy('fldid', 'ASC')
            ->groupBy('fldid');

        if ($status == 'Verified') {
            $tests = $tests->where('tblpatradiotest.fldstatus', $status);

            if ($from_date)
                $tests = $tests->where('fldtime_verify', ">=", "$from_date 00:00:00");
            if ($to_date)
                $tests = $tests->where('fldtime_verify', "<=", "$to_date 23:59:59.999");
        }
        else {
            $tests = $tests->where('tblpatradiotest.fldstatus', 'Reported');

            if ($from_date)
                $tests = $tests->where('fldtime_report', ">=", "$from_date 00:00:00");
            if ($to_date)
                $tests = $tests->where('fldtime_report', "<=", "$to_date 23:59:59.999");
        }

        if ($encounter_id)
            $tests = $tests->where('fldencounterval', $encounter_id);
        if ($name)
            $tests = $tests->whereHas('encounter.patientInfo', function($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name . '%');
            });
        if ($category)
            $tests = $tests->whereIn('fldtestid', \App\Radio::where('fldcategory', 'like', $category)->pluck('fldexamid')->toArray());
        if ($rank)
            $tests = $tests->whereHas('encounter.patientInfo', function($q) use ($rank) {
                $q->where('fldrank', 'like', '%' . $rank . '%');
            });
        if ($unit)
            $tests = $tests->whereHas('encounter.patientInfo', function($q) use ($unit) {
                $q->where('fldunit', 'like', '%' . $unit . '%');
            });

        return $tests->get();
    }

    public function changeStatus(Request $request)
    {
        \DB::beginTransaction();
        try {
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();

            \App\PatRadioTest::where([
                'fldid' => $request->get('fldid'),
            ])->update([
                'fldstatus' => 'Verified',
                'fldsave_verify' => 1,
                'flduserid_verify' => $userid,
                'fldcomp_verify' => $computer,
                'fldtime_verify' => $time,
            ]);

            \DB::commit();
            return response()->json([
                'success' => TRUE
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => FALSE
            ]);
        }
    }
    public function getModalContent(Request $request)
    {
        $fldid = $request->get('fldid');
        $testid = $request->get('fldtestid');

        $radio = \App\Radio::where('fldexamid', $testid)->first();
        $type = $radio->fldoption;

        $ret_data = [];
        if ($type == 'Fixed Components') {
            //  select fldid,fldsubtest,fldreport,fldtanswertype from tblpatradiosubtest where fldtestid='4586'
            $data = \App\SubRadioQuali::select('fldsubexam', 'fldanswertype')->where('fldexamid', $testid)->get();
            $values = \App\PatRadioSubTest::select('fldid', 'fldsubtest', 'fldreport', 'fldabnormal')->where('fldtestid', $fldid)->get()->toArray();
            $values = array_combine(array_column($values, 'fldsubtest'), $values);

            foreach ($data as &$opt) {
                if (isset($values[$opt->fldsubexam])) {
                    $val = $values[$opt->fldsubexam];
                    $opt->fldid = $val['fldid'];
                    $opt->fldreport = $val['fldreport'];
                    $opt->fldabnormal = $val['fldabnormal'];
                }
            }
        } else
            $data = PatRadioTest::select('fldreportquali', 'fldabnormal')->where('fldid', $fldid)->first();

        return response()->json([
            'type' => $type,
            'data' => $data,
        ]);
    }
}