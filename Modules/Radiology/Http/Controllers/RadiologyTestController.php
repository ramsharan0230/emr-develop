<?php

namespace Modules\Radiology\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade as PDF;

use App\Utils\Helpers;
use App\PatRadioTest;
use App\PatBilling;
use App\Utils\Options;

class RadiologyTestController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'categories' => Helpers::getPathoCategory('Radio'),
            'specimens' => Helpers::getSampleTypes(),
            'all_users' => \App\User::select('flduserid', 'fldusername')->where('fldpayable', 1)->get(),
            'visibilities' => Helpers::getVisibilities(),
            'methods' => Helpers::getMethods(),
        ];

        $category = $request->get('category');
        $has_date = $request->get('has_date');
        $encounterId = $request->get('encounterId');
        $name = $request->get('name');


        if ($request->segment(2) == 'reporting') {
            $time_column = 'fldtime_report';
            $patients = PatRadioTest::select(\DB::raw('distinct(fldencounterval) AS fldencounterval'), 'fldcomp_report', 'fldcomp_report AS fldordcomp', 'flduserid_report', 'fldtime_report')
                ->with([
                    'encounter:fldencounterval,fldpatientval,fldrank',
                    'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank',
                    'macaccess:fldcomp,fldcompname',
                ])->where([
                    // 'fldcomp_report' => Helpers::getCompName(),
                ])->where(function($query) {
                    $query->where('fldstatus', 'Ordered');
                    $query->orWhere('fldstatus', 'Sampled');
                })->whereIn('fldtestid', Helpers::getWhereInForRadioCategory($category, TRUE));
        } else {
            $time_column = 'fldtime';
            $patients = PatBilling::select(\DB::raw('distinct(fldencounterval) AS fldencounterval'), 'fldcomp', 'fldorduserid AS flduserid_report', 'fldordtime AS fldtime_report')
                ->with([
                    'encounter:fldencounterval,fldpatientval,fldrank',
                    'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank',
                    'macaccess:fldcomp,fldcompname',
                ])->where([
                    ['flditemtype', '=', 'Radio Diagnostics'],
                    ['fldsample', '=', 'Waiting'],
                    ['fldsave', '=', '1'],
                    // ['fldtarget' , 'like', Helpers::getCompName()],
                    ['flditemqty' , '>', 'fldretqty'],
                ])->whereIn('flditemname', Helpers::getWhereInForRadioCategory($category));
        }

        $fromdate = $request->get('fromdate') ? Helpers::dateNepToEng($request->get('fromdate'))->full_date : date('Y-m-d');
        $todate = $request->get('todate') ? Helpers::dateNepToEng($request->get('todate'))->full_date : date('Y-m-d');

        $patients = $patients->where([
            [$time_column, ">=", "$fromdate 00:00:00"],
            [$time_column, "<=", "$todate 23:59:59.999"],
        ]);
        if ($name)
            $patients->whereHas('encounter.patientInfo', function($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name. '%');
            });
        if ($encounterId)
            $patients->where('fldencounterval', $encounterId);
        $data['patients'] = $patients->take(100)->get();
        $data['date'] = $request->get('date') ?: Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        if ($request->isMethod('post'))
            return response()->json($data['patients']);

        return view('radiology::tests.sampling', $data);
    }

    public function samplingPatientReport(Request $request)
    {
        $tests = PatBilling::select('flditemname', 'fldtime', 'fldid', 'fldencounterval')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldmidname,fldrank'
            ])->where([
                ['flditemtype', '=', 'Radio Diagnostics'],
                ['fldsample', '=', 'Waiting'],
                ['fldsave', '=', '1'],
                // ['fldtarget', 'like', Helpers::getCompName()],
                ['flditemqty', '>', 'fldretqty'],
            ])->whereIn('flditemname', Helpers::getWhereInForRadioCategory($request->get('category')))->get();

        $category = $request->get('category', '%');
        $final_test_data = [];

        try {
            foreach ($tests as $key => $test) {
                if (!isset($final_test_data[$test->fldencounterval])) {
                    $name = '';
                    $age = '';
                    $sex = '';
                    if ($test->encounter) {
                        $user_rank = ((Options::get('system_patient_rank') == 1) && isset($test->encounter) && isset($test->encounter->fldrank)) ? $test->encounter->fldrank : '';
                        $name = $user_rank . ' ' . $test->encounter->patientInfo->fldptnamefir . ' ' . $test->encounter->patientInfo->fldmidname . ' ' . $test->encounter->patientInfo->fldptnamelast;
                        $age = $test->encounter->patientInfo->fldagestyle;
                        $sex = $test->encounter->patientInfo->fldptsex;
                    }
                    $final_test_data[$test->fldencounterval] = [
                        'encounterid' => $test->fldencounterval,
                        'name' => $name,
                        'age' => $age,
                        'sex' => $sex,
                    ];
                }
                $final_test_data[$test->fldencounterval]['tests'][] = $test->flditemname;
            }
        } catch (ErrorException  $e) {
        }

        return view('laboratory::pdf.sampling-patient-report', [
            'tests' => $final_test_data,
            'category' => $category,
        ])/*->setPaper('a4')->stream('lab_sampling_patient_report.pdf')*/;
    }

    public function getTests(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $test_data = PatBilling::select('flditemname', 'fldtime', 'fldid', 'fldencounterval')
            ->where([
                ['flditemtype', '=', 'Radio Diagnostics'],
                ['fldsample', '=', 'Waiting'],
                ['fldencounterval', '=', $encounter_id],
                ['fldsave', '=', '1'],
                ['fldtarget' , 'like', Helpers::getCompName()],
                ['flditemqty' , '>', 'fldretqty'],
            ])->get();

        $encounter_data = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
            ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldmidname,fldrank')
            ->where('fldencounterval', $encounter_id)
            ->first();

        $message = "Successfully added data.";
        $status = TRUE;
        $tests = $this->_addPatRadioTest($test_data, $encounter_id);
        if ($tests === FALSE) {
            $message = "Failed to add data.";
            $status = FALSE;
        }

        return response()->json(compact('tests', 'encounter_data', 'message', 'status'));
    }

    public function getPatRadioTest(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $showall = $request->get('showall');
        $tests = $this->_getPatRadioTest($encounter_id, $showall);
        $encounter_data = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
            ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldmidname,fldrank')
            ->where('fldencounterval', $encounter_id)
            ->first();
        return response()->json(compact('tests', 'encounter_data'));
    }

    public function getPatRadioTestPdf(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $tests = $this->_getPatRadioTest($encounter_id);
        $patientinfo = \App\Utils\Helpers::getPatientByEncounterId($encounter_id);
        return view('radiology::pdf.patRadioTest', compact('tests', 'patientinfo'));
    }

    private function _getPatRadioTest($encounterId, $showall = FASLE)
    {
        // select fldid,fldchk,fldtestid,fldreportquali,flvisible,fldmethod,fldreportquanti,fldtest_type,fldsampletype,fldcondition,fldcomment,fldtime_report,fldencounterval,fldstatus,fldabnormal,fldpacstudy from tblpatradiotest where (fldstatus='Ordered' or fldstatus='Sampled') and fldencounterval='E1024' and fldcomp_report='comp01'
        return PatRadioTest::select('fldid', 'fldchk', 'fldtestid', 'fldreportquali', 'flvisible', 'fldmethod', 'fldreportquanti', 'fldtest_type', 'fldsampletype', 'fldcondition', 'fldcomment', 'fldtime_report', 'flduserid_report', 'fldencounterval', 'fldstatus', 'fldabnormal', 'fldpacstudy')->where([
                'fldencounterval' => $encounterId,
                // 'fldcomp_report' => Helpers::getCompName(),
            ])->where(function($query) use ($showall) {
                $query->where('fldstatus', 'Ordered');
                $query->orWhere('fldstatus', 'Sampled');
                $query->orWhere('fldstatus', 'Waiting');
                if ($showall)
                    $query->orWhere('fldstatus', 'Reported');
            })->get();
    }

    public function updateRadioTest(Request $request)
    {
        try {
            $fldid = $request->get('fldid');
            $column = $request->get('column');
            $value = $request->get('value');
            PatRadioTest::where('fldid', $fldid)->update([
                $column => $value,
            ]);

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully updated test data.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FASLE,
                'message' => 'Failed to update test data.'
            ]);
        }
    }

    private function _addPatRadioTest($testids, $encounter_id)
    {
        // $testids = $request->get('fldid');
        try {
            $ret_data = [];
            $computer = Helpers::getCompName();
            \DB::beginTransaction();
            // $encounter_id = $request->get('encounterId');

            foreach ($testids as $test) {
                $testid = $test->fldid;
                $bill = PatBilling::select('flditemqty', 'fldretqty', 'fldbillno', 'fldrefer', 'fldreason', 'flditemname')
                    ->where([
                        ['fldid', '=', $testid],
                        ['flditemqty', '>', '0'],
                    ])->first();

                if (!$bill) {
                    return response()->json([
                        'status' => FASLE,
                        'message' => 'Failed to save information.'
                    ]);
                }

                $groups = \App\GroupRadio::where([
                    'fldgroupname' => $bill->flditemname,
                ])->get();

                foreach ($groups as $group) {
                    $fldid = PatRadioTest::insertGetId([
                        'fldencounterval' => $encounter_id,
                        'fldtestid' => $group->fldtestid,
                        'fldmethod' => $group->fldactive,
                        'fldgroupid' => $testid,
                        'fldstatus' => 'Ordered',
                        'flvisible' => 'Visible',
                        'fldtest_type' => $group->fldtesttype,
                        'fldbillno' => $bill->fldbillno,
                        'fldcomp_report' => $computer,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ]);

                    $ret_data[] = [
                        'fldid' => $fldid,
                        'flditemname' => $bill->flditemname,
                        'fldtestid' => $group->fldtestid,
                    ];
                }

                PatBilling::where([['fldid', $testid]])->update([
                    'fldsample' => 'Sampled',
                    'fldpayto' => NULL,
                    'xyz' => '0',
                ]);
            }

            \DB::commit();
            return $ret_data;
        } catch (Exception $e) {
            \DB::rollBack();
            return FASLE;
        }
    }

    public function updateTest(Request $request)
    {
        try {
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $time = date('Y-m-d H:i:s');
            $computer = Helpers::getCompName();

            PatRadioTest::whereIn('fldid', $request->get('testids'))
                // ->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                ->update([
                    'fldsampleid' => $request->get('fldsampleid'),
                    'fldstatus' => 'Sampled',
                    'fldrefername' => $request->get('fldrefername'),
                    'fldcomment' => $request->get('fldcomment'),
                    'fldtime_start' => $request->get('fldtime_start'),
                    'fldrefername' => $request->get('fldrefername'),
                    'flduserid_sample' => $userid,
                    'fldtime_sample' => $time,
                    'fldcomp_sample' => $computer,
                    'fldsave_sample' => '1',
                    'flduptime_sample' => $time,
                    'xyz' => '0',
                ]);

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully updated test data.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FASLE,
                'message' => 'Failed to update test data.'
            ]);
        }
    }



    public function getModalContent(Request $request)
    {
        $fldid = $request->get('fldid');
        $testid = $request->get('fldtestid');
        $module = $request->get('module');

        $radio = \App\Radio::where('fldexamid', $testid)->first();
        $type = $radio->fldoption;

        $ret_data = [];
        if ($type == 'Fixed Components') {
            $header = 'Enter Componenets';
            //  select fldid,fldsubtest,fldreport,fldtanswertype from tblpatradiosubtest where fldtestid='4586'
            $options = \App\SubRadioQuali::select('fldsubexam', 'fldanswertype')->where('fldexamid', $testid)->get();
            $values = \App\PatRadioSubTest::select('fldid', 'fldsubtest', 'fldreport', 'fldabnormal')->where('fldtestid', $fldid)->get()->toArray();
            $values = array_combine(array_column($values, 'fldsubtest'), $values);

            foreach ($options as &$opt) {
                if (isset($values[$opt->fldsubexam])) {
                    $val = $values[$opt->fldsubexam];
                    $opt->fldid = $val['fldid'];
                    $opt->fldreport = $val['fldreport'];
                    $opt->fldabnormal = $val['fldabnormal'];
                }
            }

            $ret_data = [
                'modal' => TRUE,
                'view_data' => (string) view('radiology::layouts.modal', compact('testid', 'options', 'header', 'type', 'fldid', 'module')),
            ];
        } else {
            $header = 'Qualitative Report';
            $data = PatRadioTest::select('fldreportquali', 'fldabnormal')->where('fldid', $fldid)->first();
            $ret_data = [
                'modal' => TRUE,
                'view_data' => (string) view('radiology::layouts.modal', compact('testid', 'header', 'type', 'fldid', 'data', 'module')),
            ];
        }

        return response()->json($ret_data);
    }

    public function updateRadioObservation(Request $request)
    {
        try {
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $time = date('Y-m-d H:i:s');
            $computer = Helpers::getCompName();

            $fldoption = $request->get('fldoption');
            $fldid = $request->get('fldid');
            $fldabnormal = $request->get('fldabnormal');
            $observation = $request->get('observation');
            $fldsubtest = $request->get('fldsubtest');
            $fldtanswertype = $request->get('fldtanswertype');

            $updateData = [
                'fldstatus' => 'Reported',
                'fldtime_report' => $time,
                'fldcomp_report' => $computer,
                'flduserid_report' => $userid,
                'fldsave_report' => '1',
            ];
            if ($request->get('isverify') == 'true') {
                $updateData['fldstatus'] = 'Verified';
                $updateData['fldtime_verify'] = $time;
                $updateData['fldcomp_verify'] = $computer;
                $updateData['flduserid_verify'] = $userid;
                $updateData['fldsave_verify'] = '1';
            }
            if ($fldoption == 'Fixed Components') {
                foreach ($fldid as $key => $id) {
                    if ($id) {
                        \App\PatRadioSubTest::where([['fldid', $id]])->update([
                            'fldreport' => (isset($observation[$id])) ? $observation[$id] : NULL,
                            'fldabnormal' => (isset($fldabnormal[$id]) && $fldabnormal[$id] == '1') ? '1' : '0',
                        ]);
                    } else {
                        \App\PatRadioSubTest::where([['fldid', $id]])->insert([
                            'fldencounterval' => $request->get('fldencounterval'),
                            'fldtestid' => $request->get('fldtestid'),
                            'fldsubtest' => (isset($fldsubtest[$key])) ? $fldsubtest[$key] : NULL,
                            'fldtanswertype' => (isset($fldtanswertype[$key])) ? $fldtanswertype[$key] : NULL,
                            'fldreport' => (isset($observation[$key])) ? $observation[$key] : NULL,
                            'fldabnormal' => (isset($fldabnormal[$key]) && $fldabnormal[$key] == '1') ? '1' : '0',
                            'fldsave' => '0',
                            'fldchk' => '0',
                            'fldorder' => '0',
                            'fldfilepath' => NULL,
                            'xyz' => '0',
                        ]);
                    }
                }

                PatRadioTest::where('fldid', $fldid)->update($updateData);
            } else {
                $updateData['fldreportquali'] = $observation;
                $updateData['fldabnormal'] = ($fldabnormal == '1');
                PatRadioTest::where('fldid', $fldid)->update($updateData);
            }

            if (Options::get('radio_report_text_message')) {
                $encounter = \App\Encounter::where('fldencounterval', $request->get('fldencounterval'))
                    ->with(['patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldrank'])
                    ->first();

                $text = strtr(Options::get('radio_report_text_message'), [
                    '{$name}' => $encounter->patientInfo->fldfullname,
                    '{$systemname}' => isset(Options::get('siteconfig')['system_name'])?Options::get('siteconfig')['system_name'] : '',
                ]);
                (new \Modules\AdminEmailTemplate\Http\Controllers\AdminSmsTemplateController())->sendSms([
                    'text' => $text,
                    'to' => $encounter->patientInfo->fldptcontact,
                ]);
            }

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully updated test data.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FASLE,
                'message' => 'Failed to update test data.'
            ]);
        }

    }

    public function addComment(Request $request)
    {
        try {
            $fldid = $request->fldid;
            $comment = $request->lab_comment;
            PatRadioTest::where([
                'fldid' => $fldid,
                // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ])->update([
                'fldcomment' => $comment,
            ]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully updated information.',
            ]);
        } catch (\GearmanException $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    public function addCondition(Request $request)
    {
        try {
            $fldid = $request->fldid;
            $condition = $request->condition;
            PatRadioTest::where([
                'fldid' => $fldid,
                // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ])->update([
                'fldcondition' => $condition,
            ]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully updated information.',
            ]);
        } catch (\GearmanException $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    public function getPacUrl(Request $request)
    {
        $host = Options::get('pac_server_host');
        $port = Options::get('pac_server_port');
        $encID = $request->get('encounterId');
        $encshaencryption = sha1($encID);
        $finalencryption = Helpers::GetTextBreakString($encshaencryption);

        if($host == '' || $encID =='' || $port =='')
            return response()->json([
                'message' => 'Update Settings for DICOM in Device Settings.',
                'status' => FALSE,
            ]);

        $url = "http://".$host.":".$port."/app/explorer.html#patient?uuid=".$finalencryption;
        return response()->json([
            'message' => $url,
            'status' => TRUE,
        ]);
    }

    public function radioHistory(Request $request)
    {
        $encounterId = $request->get('encounterId');
        $patientinfo = Helpers::getPatientByEncounterId($encounterId);

        $patient_encounter_ids = \App\Encounter::select('fldencounterval')
            ->where('fldpatientval', $patientinfo->fldpatientval)
            ->orderBy('fldregdate', 'DESC')
            ->get();
        $patient_encounter_ids = $patient_encounter_ids->pluck('fldencounterval')->toArray();

        $raw_tests = PatRadioTest::select('fldid', 'fldchk', 'fldtestid', 'fldreportquali', 'flvisible', 'fldmethod', 'fldreportquanti', 'fldtest_type', 'fldsampletype', 'fldcondition', 'fldcomment', 'fldtime_report', 'flduserid_report', 'fldencounterval', 'fldstatus', 'fldabnormal', 'fldpacstudy')->whereIn('fldencounterval', $patient_encounter_ids)
            ->where([
                // 'fldcomp_report' => Helpers::getCompName(),
            ])->where(function($query) {
                $query->where('fldstatus', 'Ordered');
                $query->orWhere('fldstatus', 'Sampled');
            })->get();
        $all_tests = [];
        foreach ($raw_tests as $test) {
            $all_tests[$test->fldencounterval][] = $test;
        }

        return view('radiology::pdf.history', compact('patientinfo', 'all_tests'));
    }
}
