<?php

namespace Modules\Radiology\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\PatBilling;
use App\Utils\Helpers;

class RadioAppointmentController extends Controller
{
    public function index(Request $request)
    {
        return view('radiology::tests.appointment', [
            'tests' => $this->_getPatRadioTest($request),
            'radiotemplates' => \App\RadioTemplate::all(),
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
        $showall = $request->get('showall');
        $name = $request->get('name');
        $rank = $request->get('rank');
        $unit = $request->get('unit');
        $from_date = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d');
        $to_date = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');

        $tests = PatBilling::select('tblpatbilling.fldencounterval', 'tblpatbilling.fldid', 'tblpatbilling.fldreason', 'tblpatradiotest.fldreportquali', 'tblpatradiotest.fldcomment', 'tblpatradiotest.fldtestid', 'tblpatradiotest.fldid AS tblpatradiotestid', 'fldsample', 'tblradio.fldcategory', 'tblpatradiotest.flduserid_report', 'tblpatradiotest.fldnewdate', 'tblpatradiotest.fldinside', 'tblpatradiotest.fldroomno')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptaddvill,fldptadddist,fldptcontact,fldptsex,fldptbirday,fldrank',
                'encounter.consultant:fldencounterval,fldconsultname,flduserid',
                'encounter.consultant.user:flduserid,firstname,middlename,lastname',
            ])->leftJoin('tblgroupradio', 'tblpatbilling.flditemname', '=', 'tblgroupradio.fldgroupname')
            ->leftJoin('tblpatradiotest', function($join) {
                $join->on('tblpatbilling.fldencounterval', '=', 'tblpatradiotest.fldencounterval');
                $join->on('tblgroupradio.fldtestid','=', 'tblpatradiotest.fldtestid');
            })->leftJoin('tblradio', 'tblradio.fldexamid', '=', 'tblpatradiotest.fldtestid')
            ->where([
                ['flditemtype', 'Radio Diagnostics'],
                ['fldsave', '1'],
                // ['flditemqty', '>', 'fldretqty'],
            ])->whereNotNull('tblpatradiotest.fldid')
            ->where(function($query) {
                $query->where('fldsample', 'Waiting');
                $query->orWhere('fldsample', 'Sampled');
            })->where(function($query) use ($from_date, $to_date) {
                $query->where(function($query) use ($from_date, $to_date) {
                    $query->where('fldnewdate', ">=", "$from_date 00:00:00");
                    $query->where('fldnewdate', "<=", "$to_date 23:59:59.999");
                })->orWhere(function($query) use ($from_date, $to_date) {
                    $query->where('fldordtime', ">=", "$from_date 00:00:00");
                    $query->where('fldordtime', "<=", "$to_date 23:59:59.999");
                });
            })->whereRaw('flditemqty>fldretqty')
            ->orderBy('fldid', 'ASC')
            ->groupBy('tblpatbilling.fldencounterval', 'tblpatbilling.fldid');

        if ($encounter_id)
            $tests = $tests->where('tblpatbilling.fldencounterval', $encounter_id);
        // if ($from_date)
        //     $tests = $tests->where('fldtime', ">=", "$from_date 00:00:00");
        // if ($to_date)
        //     $tests = $tests->where('fldtime', "<=", "$to_date 23:59:59.999");
        if ($name)
            $tests = $tests->whereHas('encounter.patientInfo', function($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name . '%');
            });
        if ($category)
            $tests = $tests->whereIn('flditemname', Helpers::getWhereInForRadioCategory($category));
        if ($rank)
            $tests = $tests->whereHas('encounter', function($q) use ($rank) {
                $q->where('fldrank', 'like', '%' . $rank . '%');
            });
        if ($unit)
            $tests = $tests->whereHas('encounter.patientInfo', function($q) use ($unit) {
                $q->where('fldunit', 'like', '%' . $unit . '%');
            });

        return $tests->get();
    }

    public function schedule(Request $request)
    {
        try {
            $date = $request->get('date') ? Helpers::dateNepToEng($request->get('date'))->full_date . ' ' . date('H:i:s') : NULL;
            \App\PatRadioTest::where('fldid', $request->get('fldid'))->update([
                'fldnewdate' => $date,
                'fldstatus' => 'Appointment',
            ]);
            PatBilling::where('fldid', $request->get('fldbillingid'))->update([
               'fldsample' => 'Appointment', 
            ]);

            return response()->json([
                'status' => TRUE,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
            ]);
        }
    }

    public function inside(Request $request)
    {
        try {
            \App\PatRadioTest::where('fldid', $request->get('fldid'))->update([
                'fldroomno' => $request->get('roomno'),
                'fldinside' => TRUE,
            ]);

            return response()->json([
                'status' => TRUE,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
            ]);
        }
    }
}
