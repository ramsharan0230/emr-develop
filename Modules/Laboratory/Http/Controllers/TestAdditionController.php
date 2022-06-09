<?php

namespace Modules\Laboratory\Http\Controllers;

use App\BillingSet;
use App\EmailTemplate;
use App\Encounter;
use App\PatBilling;
use App\PatientExam;
use App\PatientInfo;
use App\PatLabTest;
use App\User;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Milon\Barcode\DNS2D;
use Session;

class TestAdditionController extends Controller
{

    public function index(Request $request)
    {
        $data = $this->_get_option_data();
        $data['billingset'] = BillingSet::get();
        $data['refer_by'] = \App\CogentUsers::where('fldreferral', 1)->get();

        $encounter_id_session = Session::get('lab_addition_encounter_id');
        if ($request->has('encounter_id') || $encounter_id_session) {

            if ($request->has('encounter_id'))
                $encounter_id = $request->get('encounter_id');
            else
                $encounter_id = $encounter_id_session;

            session(['lab_addition_encounter_id' => $encounter_id]);


            /*create last encounter id*/
            Helpers::moduleEncounterQueue('lab_addition_encounter_id', $encounter_id);
            //            $encounterIds = Options::get('lab_addition_last_encounter_id');

            //            $arrayEncounter = unserialize($encounterIds);
            /*create last encounter id*/

            $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)
                ->with('PatFindings:fldencounterval,fldcode')
                ->first();

            if (!$enpatient) {
                $request->session()->forget('lab_addition_encounter_id');
                return redirect()->back()->with('error_message', "Patient not found");
            }

            $data['patient_status_disabled'] = $enpatient->fldadmission == "Discharged" ? 1 : 0;

            if ($enpatient->fldcurrlocat)
                $data['next_sample_id'] = Helpers::getDepartmentByLocation($enpatient->fldcurrlocat) . $data['next_sample_id'];

            $patient_id = $enpatient->fldpatientval;
            $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
            //            dd($enpatient);
            $data['patient_id'] = $patient_id;
            $data['consultants'] = User::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();
            $end = Carbon::parse($patient->fldptbirday);
            $now = Carbon::now();

            $length = $end->diffInDays($now);
            if ($length < 1) {
                $data['years'] = 'Hours';
                $data['hours'] = $end->diffInHours($now);
            }

            if ($length > 0 && $length <= 30)
                $data['years'] = 'Days';
            if ($length > 30 && $length <= 365)
                $data['years'] = 'Months';
            if ($length > 365)
                $data['years'] = 'Years';

            $data['body_weight'] = $body_weight = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_weight')->orderBy('fldid', 'desc')->first();
            // dd($body_weight);
            $data['body_height'] = $body_height = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->orderBy('fldid', 'desc')->first();

            if (isset($body_height)) {
                if ($body_height->fldrepquali <= 100) {
                    $data['heightrate'] = 'cm';
                    $data['height'] = $body_height->fldrepquali;
                } else {
                    $data['heightrate'] = 'm';
                    $data['height'] = $body_height->fldrepquali / 100;
                }
            } else {
                $data['heightrate'] = 'cm';
                $data['height'] = '';
            }


            $data['bmi'] = '';

            if (isset($body_height) && isset($body_weight)) {
                $hei = ($body_height->fldrepquali / 100); //changing in meter
                $divide_bmi = ($hei * $hei);
                if ($divide_bmi > 0) {

                    $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                }
            }

            $compname = Helpers::getCompName();
            $data['billings'] = PatBilling::where([
                ['flditemtype', '=', 'Diagnostic Tests'],
                ['fldsample', '=', 'Waiting'],
                ['fldencounterval', '=', $encounter_id],
                ['fldsave', '=', '1'],
                // ['fldtarget', '=', $compname],
                ['flditemqty', '>', 'fldretqty'],
            ])->get();
            $data['labtests'] = PatLabTest::select('fldid', 'fldchk', 'fldtestid', 'fldmethod', 'fldtime_sample', 'fldsampleid', 'fldsampletype', 'fldbillno', 'fldcondition', 'fldtest_type', 'fldrefername', 'fldcomment', 'fldencounterval', 'fldtime_start')
                ->with('test:fldtestid,fldvial')
                ->where([
                    'fldencounterval' => $encounter_id,
                    // 'fldcomp_sample' => $compname,
                ])->where(function ($query) {
                    $query->where('fldstatus', 'Ordered');
                    // $query->orWhere('fldstatus', 'Sampled');
                })->get();
        }

        return view('laboratory::tests.addition', $data);
    }

    public function addTest(Request $request)
    {
        $testids = $request->get('fldid');
        $time = date('Y-m-d H:i:s');
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $computer = \App\Utils\Helpers::getCompName();
        try {
            $retdata = [];
            \DB::beginTransaction();
            foreach ($testids as $testid) {
                $bill = PatBilling::select('flditemqty', 'fldretqty', 'fldbillno', 'fldrefer', 'fldreason', 'flditemname')
                    ->where([
                        ['fldid', '=', $testid],
                        ['flditemqty', '>', '0'],
                    ])->first();

                if (!$bill) {
                    return response()->json([
                        'status' => FALSE,
                        'message' => 'Failed to save test data.'
                    ]);
                }

                $group = \App\GroupTest::where([
                    'fldgroupname' => $bill->flditemname,
                ])->first();
                $test_detail = \App\Test::where('fldtestid', $group->fldtestid)->first();

                $fldid = PatLabTest::insertGetId([
                    'fldencounterval' => Session::get('lab_addition_encounter_id'),
                    'fldtestid' => $group->fldtestid,
                    'fldmethod' => $group->fldactive,
                    'fldgroupid' => $testid,
                    'fldsampleid' => NULL,
                    'fldsampletype' => $test_detail->fldspecimen,
                    'fldreportquali' => NULL,
                    'fldreportquanti' => 0,
                    'fldfilepath' => NULL,
                    'fldtestunit' => NULL,
                    'fldstatus' => 'Ordered',
                    'fldprint' => 0,
                    'fldabnormal' => 0,
                    'fldrefername' => NULL,
                    'fldcondition' => NULL,
                    'fldcomment' => NULL,
                    'flvisible' => 'Visible',
                    'fldtest_type' => $group->fldtesttype,
                    'fldbillno' => $bill->fldbillno,
                    'fldchk' => 0,
                    'fldorder' => 0,
                    'flduserid_sample' => $userid,
                    'fldtime_sample' => $time,
                    'fldcomp_sample' => $computer,
                    'fldsave_sample' => 0,
                    'flduptime_sample' => NULL,
                    'flduserid_start' => NULL,
                    'fldtime_start' => NULL,
                    'fldcomp_start' => NULL,
                    'fldsave_start' => 0,
                    'flduptime_start' => NULL,
                    'flduserid_report' => NULL,
                    'fldtime_report' => NULL,
                    'fldcomp_report' => NULL,
                    'fldsave_report' => 0,
                    'flduptime_report' => NULL,
                    'flduserid_verify' => NULL,
                    'fldtime_verify' => NULL,
                    'fldcomp_verify' => NULL,
                    'fldsave_verify' => 0,
                    'flduptime_verify' => NULL,
                    'xyz' => 0,
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ]);

                Helpers::logStack(["Order of bill no. " . $bill->fldbillno, "Lab", ], ['encounter_id' => Session::get('lab_addition_encounter_id'), 'bill_no' => $bill->fldbillno]);

                PatBilling::where([['fldid', $testid]])->update([
                    'fldpayto' => NULL,
                    'fldsample' => 'Sampled',
                    'xyz' => '0',
                ]);
                $group->fldid = $fldid;
                $group->fldsampletype = $test_detail->fldspecimen;
                $group->fldvial = $test_detail->fldvial;

                $retdata[] = $group;
            }
            \DB::commit();

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved test data.',
                'data' => $retdata,
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            dd($e);
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save test data.'
            ]);
        }
    }

    public function deleteTest(Request $request)
    {
        $testids = $request->get('fldid');
        try {
            \DB::beginTransaction();
            foreach ($testids as $testid) {
                $bill = PatBilling::where('fldid', $testid)->first();

                if (!$bill) {
                    return response()->json([
                        'status' => FALSE,
                        'message' => 'Failed to save test data.'
                    ]);
                }

                $userid = \Auth::guard('admin_frontend')->user()->flduserid;
                $time = date('Y-m-d H:i:s');
                $computer = \App\Utils\Helpers::getCompName();

                PatBilling::insert([
                    'fldencounterval' => $bill->fldencounterval,
                    'fldbillingmode' => $bill->fldbillingmode,
                    'flditemtype' => $bill->flditemtype,
                    'flditemno' => $bill->flditemno,
                    'flditemname' => $bill->flditemname,
                    'flditemrate' => $bill->flditemrate,
                    'flditemqty' => "-{$bill->flditemqty}",
                    'fldtaxper' => $bill->fldtaxper,
                    'flddiscper' => $bill->flddiscper,
                    'fldtaxamt' => $bill->fldtaxamt,
                    'flddiscamt' => $bill->flddiscamt,
                    'fldditemamt' => "-{$bill->fldditemamt}",
                    'fldorduserid' => $userid,
                    'fldordtime' => $time,
                    'fldordcomp' => $computer,
                    'flduserid' => $userid,
                    'fldtime' => $time,
                    'fldcomp' => $computer,
                    'fldsave' => '1',
                    'fldbillno' => NULL,
                    'fldparent' => $testid,
                    'fldprint' => '0',
                    'fldstatus' => 'Done',
                    'fldalert' => '1',
                    'fldtarget' => $computer,
                    'fldpayto' => NULL,
                    'fldrefer' => NULL,
                    'fldreason' => 'AutoReturn',
                    'fldretbill' => $bill->fldbillno,
                    'fldretqty' => '0',
                    'fldsample' => 'Waiting',
                    'xyz' => '0',
                ]);

                $bill->fldretqty = 1;
                $bill->fldsample = 'Removed';
                $bill->xyz = '0';
                $bill->save();
            }
            \DB::commit();

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully removed bill.',
            ]);
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to remove bill.'
            ]);
        }
    }

    public function deleteTestData(Request $request)
    {
        try {
            $fldid = $request->get('fldid');
            PatLabTest::where('fldid', $fldid)->delete();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully deleted test.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to delete test.'
            ]);
        }
    }

    public function updateTest(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'testids' => 'required',
            'fldsampleid' => 'required',
            // 'fldsampletype' => 'required',
            // 'fldrefername' => 'required',
            'fldtime_start' => 'required',
        ], [
            'testids.required' => 'Please select sample to update.',
        ]);
        if ($validator->fails()) {
            $errors = 'Error while saving information' . PHP_EOL;
            foreach ($validator->getMessageBag()->messages() as $key => $value)
                $errors .= $value[0] . PHP_EOL;

            return [
                'status' => FALSE,
                'message' => $errors,
            ];
        }

        $fldsampleid = $request->get('fldsampleid');
        $count = PatLabTest::where([
            ['fldsampleid', '=', $fldsampleid],
            ['fldencounterval', '!=', $request->get('fldencounterval')],
        ])->count();

        if ($count > 0)
            return response()->json([
                'status' => FALSE,
                'message' => 'Duplicate sample id. PLease enter unique sample id.',
            ]);

        try {
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $time = date('Y-m-d H:i:s');
            $computer = Helpers::getCompName();
            Helpers::getNextAutoId('LabSampleNo', TRUE, TRUE);

            $fldtime_start = $request->get('fldtime_start');
            $fldtime_start = ($fldtime_start) ? Helpers::dateNepToEng($fldtime_start)->full_date . ' ' . $request->get('fldtime') . ':00' : NULL;
            $update_data = array_filter([
                'fldsampleid' => $request->get('fldsampleid'),
                'fldsampletype' => $request->get('fldsampletype'),
                'fldrefername' => $request->get('fldrefername'),
                'fldcondition' => $request->get('fldcondition'),
                'fldcomment' => $request->get('fldcomment'),
                'fldbillno' => $request->get('fldbillno'),
                'fldtime_start' => $fldtime_start,
            ]);

            if ($update_data) {
                $add_data = [
                    'fldstatus' => 'Sampled',
                    'flduserid_sample' => $userid,
                    'fldtime_sample' => $time,
                    'fldcomp_sample' => $computer,
                    'fldsave_sample' => '1',
                    'flduptime_sample' => $time,
                    'xyz' => '0',
                ];
                PatLabTest::whereIn('fldid', $request->get('testids'))
                    // ->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                    ->update($update_data + $add_data);

                Helpers::logStack(["Collected Sample of bill no. " . $request->get('fldbillno'), "Lab", ], ['encounter_id' => $request->get('fldencounterval'), 'bill_no' => $request->get('fldbillno')]);

                $all_data = \App\PatLabTest::select('fldencounterval', 'fldtestid')
                    ->whereIn('fldid', $request->get('testids'))
                    ->get()->toArray();
                if ($all_data) {
                    $encounter_id = $all_data[0]['fldencounterval'];
                    $tests = array_column($all_data, 'fldtestid');

                    \App\PatBilling::where([
                        'fldencounterval' => $encounter_id,
                        'flditemtype' => 'Diagnostic Tests',
                    ])->whereIn('flditemname', $tests)->update([
                        'fldsample' => $add_data['fldstatus'],
                    ]);
                }

                return response()->json([
                    'status' => TRUE,
                    'message' => __('messages.update', ['name' => 'Data']),
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update test data.'
            ]);
        }

        return response()->json([
            'status' => FASLE,
            'message' => 'Nothing to update.'
        ]);
    }

    public function updateTestWorksheet(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'testids' => 'required',
            'fldsampleid' => 'required',
            // 'fldsampletype' => 'required',
            // 'fldrefername' => 'required',
            'fldtime_start' => 'required',
        ], [
            'testids.required' => 'Please select sample to update.',
        ]);
        if ($validator->fails()) {
            $errors = 'Error while saving information' . PHP_EOL;
            foreach ($validator->getMessageBag()->messages() as $key => $value)
                $errors .= $value[0] . PHP_EOL;

            return [
                'status' => FALSE,
                'message' => $errors,
            ];
        }

        $fldsampleid = $request->get('fldsampleid');

        try {
            $pdfData['encounter_data'] = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
                ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldmidname,fldrank')
                ->where('fldencounterval', $request->fldencounterval)
                ->first();

            $pdfData['barcodeData'] = DNS2D::getBarcodeHTML($pdfData['encounter_data']->fldpatientval, 'QRCODE', 3, 3);

            if ($request->generate_worksheet != 'false') {
                $pdfData['specimen'] = $request->get('fldsampletype');
                $pdfData['testsData'] = PatLabTest::select('tbltest.fldcategory', 'tblpatlabtest.fldsampleid', 'tblpatlabtest.fldtestid', 'tblpatlabtest.fldmethod', 'tblpatlabtest.fldsampletype', 'tblpatlabtest.flduserid_report', 'tblpatlabtest.flduserid_verify')
                    ->join('tbltest', 'tbltest.fldtestid', 'tblpatlabtest.fldtestid')
                    ->where([
                        'fldencounterval' => $request->fldencounterval,
                        //                        'fldcomp_sample' => Helpers::getCompName()
                    ])->where(function ($query) {
                        $query->where('fldstatus', 'Ordered');
                        $query->orWhere('fldstatus', 'Sampled');
                    })
                    ->whereIn('fldid', $request->get('testids'))
                    ->orderBy('tblpatlabtest.fldsampleid', 'ASC')
                    ->get();

                if (Options::get('worksheet_print_mode') == 'Continuous') {
                    return view('laboratory::pdf.sampling-A', $pdfData);
                    // ->setPaper('a4')
                    // ->stream('lab_sampling_' . $fldsampleid . '.pdf');
                } else {
                    return view('laboratory::pdf.sampling-A', $pdfData);
                    // ->setPaper('a4')
                    // ->stream('lab_sampling_' . $fldsampleid . '.pdf');
                }

            }

        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update test data.'
            ]);
        }
    }

    public function updateTestBarcode(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'testids' => 'required',
            'fldsampleid' => 'required',
            // 'fldsampletype' => 'required',
            // 'fldrefername' => 'required',
            'fldtime_start' => 'required',
        ], [
            'testids.required' => 'Please select sample to update.',
        ]);
        if ($validator->fails()) {
            $errors = 'Error while saving information' . PHP_EOL;
            foreach ($validator->getMessageBag()->messages() as $key => $value)
                $errors .= $value[0] . PHP_EOL;

            return [
                'status' => FALSE,
                'message' => $errors,
            ];
        }

        $fldsampleid = $request->get('fldsampleid');

        try {
            $pdfData['encounter_data'] = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
                ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldmidname,fldrank')
                ->where('fldencounterval', $request->fldencounterval)
                ->first();

            //            $pdfData['barcodeData'] = DNS2D::getBarcodeHTML($pdfData['encounter_data']->fldpatientval, 'QRCODE', 3, 3);

            if ($request->generate_barcode != 'false') {
                $pdfData['testsData'] = PatLabTest::select('fldencounterval', 'fldsampleid', 'fldtestid')
                    ->where([
                        'fldencounterval' => $request->fldencounterval,
                        //                        'fldcomp_sample' => Helpers::getCompName()
                    ])->where(function ($query) {
                        $query->where('fldstatus', 'Ordered');
                        $query->orWhere('fldstatus', 'Sampled');
                    })
                    ->whereIn('fldid', $request->get('testids'))
                    ->get();

                return view('laboratory::pdf.barcode', $pdfData);
                /* ->setPaper('a4')
                 ->stream('lab_sampling_' . $fldsampleid . '.pdf');*/

            }

        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update test data.'
            ]);
        }
    }

    public function updateSpecimen(Request $request)
    {
        $fldsampleid = $request->get('fldsampleid');
        try {
            PatLabTest::whereIn('fldid', $request->get('testids'))
                // ->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                // ->whereNotNull('fldsampletype')
                ->update([
                    'fldsampletype' => $request->get('specimen'),
                ]);

            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update test data.'
            ]);
        }
    }

    private function _get_option_data()
    {
        return [
            'specimens' => Helpers::getSampleTypes(),
            'conditions' => Helpers::getTestCondition(),
            'next_sample_id' => Helpers::getNextAutoId('LabSampleNo', FALSE, TRUE),
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
            'time' => date('h:i'),
        ];
    }

    private function _get_modal_name($type)
    {
        $modelName = ($type === 'specimen') ? "\App\Sampletype" : "\App\TestCondition";
        return $modelName;
    }

    private function _get_field_name($type)
    {
        $modelName = ($type === 'specimen') ? "fldsampletype" : "fldtestcondition";
        return $modelName;
    }

    public function getSelectOptions()
    {
        return response()->json($this->_get_option_data());
    }

    public function addVariable(Request $request)
    {
        $type = $request->get('type');
        $flditem = $request->get('flditem');

        if ($flditem == '' || !in_array($type, ['specimen', 'condition'])) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Invalid data. Please refresh page and try again.'
            ]);
        }

        try {
            $field = $this->_get_field_name($type);
            $data = [
                $field => $flditem,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $modelName = $this->_get_modal_name($type);
            $modelName::insert($data);

            return [
                'status' => TRUE,
                'data' => $data,
                'message' => 'Successfully saved data.'
            ];
        } catch (Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to save data.'
            ];
        }
    }

    public function deleteVariable(Request $request)
    {
        $type = $request->get('type');
        $flditem = $request->get('flditem');

        if ($flditem == '' || !in_array($type, ['specimen', 'condition'])) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Invalid data. Please refresh page and try again.'
            ]);
        }

        try {
            $field = $this->_get_field_name($type);
            $data = [
                $field => $flditem,
            ];
            $modelName = $this->_get_modal_name($type);
            $modelName::where($data)->delete();

            return [
                'status' => TRUE,
                'message' => 'Successfully deleted data.'
            ];
        } catch (Exception $e) {
            return [
                'status' => FALSE,
                'message' => 'Failed to delete data.'
            ];
        }
    }

    public function resetEncounter()
    {
        Session::forget('lab_addition_encounter_id');
        return redirect()->back();
    }

    public function lastEncounter()
    {
        $encounterIds = Options::get('lab_addition_encounter_id');
        // dd($encounterIds)
        $data['arrayEncounter'] = array_reverse(unserialize($encounterIds));
        $html = view('menu::menu-dynamic-views.last-encounter-laboratory-addition', $data)->render();
        return $html;
    }

    public function setLastEncounter(Request $request)
    {
        session(['lab_addition_encounter_id' => $request->lastEncounter ?? 0]);
        return redirect()->back();
    }

    public function getTemplateBody(Request $request)
    {
        $templateData = EmailTemplate::select('description')->where('id', $request->id)->first();

        return [
            'status' => TRUE,
            'htmlData' => $templateData->description
        ];
    }
}
