<?php

namespace Modules\Xray\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\PatBilling;
use App\Utils\Helpers;

class XrayController extends Controller
{
    public function index(Request $request)
    {
        return view('xray::index', [
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
        $status = $request->get('status');
        $from_date = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d');
        $to_date = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');

        $tests = PatBilling::select('tblpatbilling.fldencounterval', 'tblpatbilling.fldid', 'tblpatbilling.fldreason', 'tblpatradiotest.fldreportquali', 'tblpatradiotest.fldcomment', 'tblgroupradio.fldtestid', 'tblpatradiotest.fldid AS tblpatradiotestid', 'fldsample', 'tblradio.fldcategory', 'tblpatradiotest.flduserid_report')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldptaddvill,fldptadddist,fldptcontact,fldptsex,fldptbirday,fldrank',
                'encounter.consultant:fldencounterval,fldconsultname,flduserid',
                'encounter.consultant.user:flduserid,firstname,middlename,lastname',
                'pat_billing_shares.user',
            ])
	        ->join('tblgroupradio', 'tblpatbilling.flditemname', '=', 'tblgroupradio.fldgroupname')
            ->leftJoin('tblpatradiotest', function($join) {
                $join->on('tblpatbilling.fldencounterval', '=', 'tblpatradiotest.fldencounterval');
                $join->on('tblgroupradio.fldtestid','=', 'tblpatradiotest.fldtestid');
            })->leftJoin('tblradio', 'tblradio.fldexamid', '=', 'tblgroupradio.fldtestid')
//             ->whereNotNull('tblpatradiotest.fldid')
            ->where([
                ['flditemtype', 'Radio Diagnostics'],
                // ['fldsave', '1'],
                // ['flditemqty', '>', 'fldretqty'],
            ])->where(function($query) {
                $query->where('fldsave', '1')
                    ->orWhere(function($q) {
                        $q->where('fldsave', '0')->whereNotNull('fldtempbillno');
                    });
            })->whereRaw('flditemqty>fldretqty')
            ->orderBy('fldid', 'ASC')
            ->groupBy('tblpatbilling.fldencounterval', 'tblpatbilling.fldid');

        if ($showall)
            $tests = $tests->where(function($query) {
                $query->where('fldsample', 'Waiting');
                $query->orWhere('fldsample', 'Reported');
                $query->orWhere('fldsample', 'CheckIn');
                $query->orWhere('fldsample', 'Appointment');
                $query->orWhere('fldsample', 'Sampled');
            });
        elseif ($status) {
            if ($status == 'Waiting') {
                $tests = $tests->where(function($query) {
                    $query->where('fldsample', 'Waiting');
                    $query->orWhere('fldsample', 'Sampled');
                });
            } elseif ($status == 'Appointment') {
                $tests = $tests->where('fldsample', 'Appointment');
            } elseif ($status == 'CheckIn') {
                $tests = $tests->where('fldsample', 'CheckIn');
            } elseif ($status == 'Reported') {
                $tests = $tests->where('fldsample', 'Reported');
            } else {
                $tests = $tests->where(function($query) {
                    $query->where('fldsample', 'CheckIn');
                    $query->orWhere('fldsample', 'Reported');
                });
            }
        }
        else
            $tests = $tests->where(function($query) {
                // $query->orWhere('fldsample', 'Appointment');
                $query->where('fldsample', 'Waiting');
                $query->orWhere('fldsample', 'Sampled');
            });

        if ($status == 'Appointment') {
            $tests = $tests->where('fldnewdate', ">=", "$from_date 00:00:00")
                        ->where('fldnewdate', "<=", "$to_date 23:59:59.999");
        } else {
            $tests = $tests->where('fldtime', ">=", "$from_date 00:00:00")
                        ->where('fldtime', "<=", "$to_date 23:59:59.999");
        }


        if ($encounter_id)
            $tests = $tests->where('tblpatbilling.fldencounterval', $encounter_id);
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

    public function changeStatus(Request $request)
    {
        \DB::beginTransaction();
        try {
            $fldid = $request->get('fldid');
            $tblpatradiotestid = $request->get('tblpatradiotestid');

            $updateData = [
                'fldstatus' => 'CheckIn',
                'fldinside' => FALSE,
                'fldsave_report' => 1,
                'fldtime_report' => date('Y-m-d H:i:s'),
                'flduserid_report' => \Auth::guard('admin_frontend')->user()->flduserid,
                'fldcomp_report' => \App\Utils\Helpers::getCompName(),
            ];

            PatBilling::where([
                'fldid' => $fldid,
            ])->update([
                'fldsample' => 'CheckIn'
            ]);

            if ($tblpatradiotestid == NULL) {
                $patbilling = \App\PatBilling::where('fldid', $fldid)->first();
                $groupRadio = \App\RadioGroup::where('fldgroupname', $patbilling->flditemname)->first();
                $tblpatradiotestid = \App\PatRadioTest::insertGetId([
                    'fldencounterval' => $patbilling->fldencounterval,
                    'fldstatus' => "Sampled",
                    'fldmethod' => $groupRadio->fldactive,
                    'fldtestid' => $groupRadio->fldtestid,
                    'flvisible' => "Visible",
                    'fldtest_type' => $groupRadio->fldtesttype,
                ]);
            }


            \App\PatRadioTest::where([
                'fldid' => $tblpatradiotestid,
            ])->update($updateData);
            \App\PatRadioTest::where([
                'fldid' => $tblpatradiotestid,
            ])->whereNull('fldreportquali')->update(array_merge([
                'fldreportquali' => 'DONE',
            ], $updateData));

            \DB::commit();
            $updateData['tblpatradiotestid'] = $tblpatradiotestid;
            return response()->json([
                'success' => TRUE,
                'data' => $updateData,
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => FALSE
            ]);
        }
    }

    public function savecomment(Request $request)
    {
        try {
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();
            $isverify = $request->get('isverify');
            $updateData = [
                'fldreportquali' => $request->get('comment'),
                'fldstatus' => 'Reported',
                'fldsave_report' => 1,
                'flduserid_report' => $userid,
                'fldcomp_report' => $computer,
                'fldtime_report' => $time,
            ];
            if ($isverify == 'true') {
                $updateData['fldstatus'] = 'Verified';
                $updateData['fldsave_verify'] = 1;
                $updateData['flduserid_verify'] = $userid;
                $updateData['fldcomp_verify'] = $computer;
                $updateData['fldtime_verify'] = $time;
            }

            \App\PatRadioTest::where([
                'fldid' => $request->get('fldid'),
            ])->update($updateData);
            PatBilling::where([
                'fldid' => $request->get('fldbillingid'),
            ])->update([
                'fldsample' => $updateData['fldstatus']
            ]);

            return response()->json([
                'success' => TRUE,
                'data' => $updateData,
            ]);
        } catch (Exception $e) {            
            return response()->json([
                'success' => FALSE
            ]);
        }
    }

    public function saveAppointment(Request $request)
    {
        \DB::beginTransaction();
        try {
            $updateData = [
                'fldstatus' => 'CheckIn',
                'fldsave_report' => 1,
                'fldtime_report' => date('Y-m-d H:i:s'),
                'flduserid_report' => \Auth::guard('admin_frontend')->user()->flduserid,
                'fldcomp_report' => \App\Utils\Helpers::getCompName(),
            ];

            PatBilling::where([
                'fldid' => $request->get('fldid'),
            ])->update([
                'fldsample' => 'CheckIn'
            ]);
            \App\PatRadioTest::where([
                'fldid' => $request->get('tblpatradiotestid'),
                'flddate' => $request->get('date') ? Helpers::dateNepToEng($request->get('date'))->full_date : '',
            ])->update($updateData);

            \DB::commit();
            return response()->json([
                'success' => TRUE,
                'data' => $updateData,
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => FALSE,
            ]);
        }
        
    }
}
