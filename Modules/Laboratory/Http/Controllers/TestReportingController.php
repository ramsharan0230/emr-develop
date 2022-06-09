<?php

namespace Modules\Laboratory\Http\Controllers;

use App\Encounter;
use App\Test;
use App\PcrForm;
use App\CogentUsers;
use App\TestOption;
use App\Utils\Helpers;
use App\Utils\Options;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Notifications\NearExpiryMedicine;

/**
 * Class TestReportingController
 * @package Modules\Laboratory\Http\Controllers
 */
class TestReportingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = [
            'categories' => Helpers::getPathoCategory('Test'),
            'labTestPatients' => $this->_getAllTest($request),
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
        ];
        return view('laboratory::tests.reporting', $data);
    }

    private function _getAllTest(Request $request)
    {
        $fromdate = $request->get('fromdate') ? Helpers::dateNepToEng($request->get('fromdate'))->full_date : date('Y-m-d');
        $todate = $request->get('todate') ? Helpers::dateNepToEng($request->get('todate'))->full_date : date('Y-m-d');
        $encounterId = $request->get('encounterId');
        $category = $request->get('category');
        $name = $request->get('name');
        $showOtherLocation = $request->get('showOtherLocation', 'false');

        $data = \App\PatLabTest::select("fldencounterval", "fldsampleid", "flduserid_sample", "fldtime_sample")
            ->with([
                'patientEncounter:fldencounterval,fldpatientval,fldrank',
                'patientEncounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank',
                'patientEncounter.consultant:fldencounterval,fldconsultname',
            ]);
        if(Options::get('test_receiving')=='Yes'){
            $data->where([
                ['fldstatus', '=', 'Received'],
                ['fldsave_sample', '=', '1'],
                ["fldtime_sample", ">=", "$fromdate 00:00:00"],
                ["fldtime_sample", "<=", "$todate 23:59:59.999"],
                // ['fldcomp_sample', 'like', Helpers::getCompName()],
            ]);
        }else{
            $data->where([
                ['fldstatus', '=', 'Sampled'],
                ['fldsave_sample', '=', '1'],
                ["fldtime_sample", ">=", "$fromdate 00:00:00"],
                ["fldtime_sample", "<=", "$todate 23:59:59.999"],
                // ['fldcomp_sample', 'like', Helpers::getCompName()],
            ]);
        }
        if ($showOtherLocation == 'false')
            $data = $data->where(function($query) {
                $query->where('fldsamplelocation', 'Hospital')->orWhere('fldsamplelocation', '')->orWhereNull('fldsamplelocation');
            });
        if ($category)
            $data = $data->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $category)->pluck('fldtestid')->toArray());
        if ($name)
            $data = $data->whereHas('patientEncounter.patientInfo', function ($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name . '%');
            });
        if ($encounterId)
            $data = $data->where('fldencounterval', 'LIKE', $encounterId . '%');


        return $data->groupBy('fldencounterval')->orderBy('fldtime_sample','desc')
            ->get();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLabTestPatient(Request $request)
    {
        return response()->json($this->_getAllTest($request));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientDetail(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $sample_id = $request->get('sample_id');
        $field = ($encounter_id) ? 'fldencounterval' : 'fldsampleid';
        $value = ($encounter_id) ?: $sample_id;
        $showOtherLocation = $request->get('showOtherLocation', 'false');
        // dump($field);
        // dd($value);

        $samples = \App\PatLabTest::select('fldencounterval', 'fldid', 'fldsampleid', 'fldtestid', 'fldstatus', 'fldsave_report', 'fldabnormal', 'flvisible', 'fldsampletype', 'fldmethod', 'fldtest_type', 'fldcondition', 'fldcomment', 'fldtime_sample', 'fldtime_report', 'fldreportquali', 'fldreportquanti')
            ->where([
                [$field, '=', $value],
                // ['fldstatus', '=', 'Sampled'],
                // ['fldcomp_sample', 'like', Helpers::getCompName()],
            ]);

        if ($showOtherLocation == 'false')
            $samples = $samples->where(function($query) {
                $query->where('fldsamplelocation', 'Hospital')->orWhere('fldsamplelocation', '')->orWhereNull('fldsamplelocation');
            });
        if ($request->get('category_id'))
            $samples = $samples->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $request->get('category_id'))->pluck('fldtestid')->toArray());

        if((Options::get('test_receiving')=='Yes')){
            if ($request->get('showall') == 'false') {
                $samples = $samples->whereNull('fldcondition')
                    ->where('fldstatus', 'Received');
            } else {
                $samples = $samples->where(function ($query) {
                    $query->where('fldstatus', 'Received')
                        ->orWhere('fldstatus', 'Reported')
                        ->orWhere('fldstatus', 'Not Done');
                });
            }
        }else{
            if ($request->get('showall') == 'false') {
                $samples = $samples->whereNull('fldcondition')
                    ->where('fldstatus', 'Sampled');
            } else {
                $samples = $samples->where(function ($query) {
                    $query->where('fldstatus', 'Sampled')
                        ->orWhere('fldstatus', 'Reported')
                        ->orWhere('fldstatus', 'Not Done');
                });
            }
        }

        $samples = $samples->with([
            'testLimit',
            'test:fldtestid,fldoption',
            'test.testoptions:fldtestid,fldanswer,fldanswertype',
            'test.methods:fldtestid,fldmethod',
        ])->get();

        if (!$encounter_id && $samples->isNotEmpty())
            $encounter_id = $samples->toArray()[0]['fldencounterval'];

        $encounter_data = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
            ->with([
                'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldrank',
                'PatFindings:fldencounterval,fldcode',
            ])->where('fldencounterval', $encounter_id)
            ->first();

        $gender = ($encounter_data && $encounter_data->patientInfo && $encounter_data->patientInfo->fldptsex) ? $encounter_data->patientInfo->fldptsex : NULL;
        foreach ($samples as &$sample) {
            if ($sample->fldtest_type == 'Quantitative')
                $sample->refrance_range_helper = \App\Utils\LaboratoryHelpers::getQuantitativeTestLimit($sample->fldtestid, $gender);
        }

        return response()->json(
            compact('encounter_data', 'samples')
        );
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function sampleReport(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $patientinfo = \App\Utils\Helpers::getPatientByEncounterId($encounter_id);
        $samples = \App\PatLabTest::select('fldid', 'fldsampleid', 'fldtestid', 'fldstatus', 'fldsave_report', 'fldabnormal', 'flvisible', 'fldsampletype', 'fldmethod', 'fldtest_type', 'fldcondition', 'fldcomment', 'fldtime_sample', 'fldtime_report')
            ->where([
                ['fldencounterval', '=', $encounter_id],
                ['fldstatus', '=', 'Sampled'],
                // ['fldcomp_sample', 'like', Helpers::getCompName()],
            ]);
        if ($request->get('category_id'))
            $samples = $samples->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $request->get('category_id'))->pluck('fldtestid')->toArray());
        $samples = $samples->get();

        return \Barryvdh\DomPDF\Facade::loadView('laboratory::layouts.testReportingSamplePdf', compact(['patientinfo', 'samples', 'encounter_id']))
            ->download('ipd_transition.pdf');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request)
    {
        try {
            \App\PatLabTest::where([
                'fldid' => $request->get('fldid'),
                // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ])->update([
                'fldabnormal' => $request->get('fldabnormal'),
            ]);

            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Exception|\GearmanException|string
     */
    public function changeQuantity(Request $request)
    {
        try {
            $fldid = $request->fldid;
            $fldoption = $request->fldoption;
            $quantity = $request->quantity;
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();

            $samples = \App\PatLabTest::select('fldencounterval', 'fldid', 'fldsampleid', 'fldtestid', 'fldstatus', 'fldsave_report', 'fldabnormal', 'flvisible', 'fldsampletype', 'fldmethod', 'fldtest_type', 'fldcondition', 'fldcomment', 'fldtime_sample', 'fldtime_report', 'fldreportquali', 'fldreportquanti')
                ->where([
                    ['fldid', '=', $fldid],
                    // ['fldstatus', '=', 'Sampled'],
                    // ['fldcomp_sample', 'like', Helpers::getCompName()],
                ])->with('testLimit')->first();

            $updateData = [
                'fldreportquali' => $quantity,
                'fldabnormal' => 0,
                'fldstatus' => 'Reported',
                'fldtime_report' => $time,
                'fldsave_report' => '1',
                'fldcomp_report' => $computer,
                'flduserid_report' => $userid,
                'fldtestunit' => $request->fldtestunit,
            ];
            if (is_numeric($quantity)) {
                $updateData['fldreportquanti'] = $quantity;
            }

            $abnormal = false;
            if ($samples->testLimit->isNotEmpty() && $samples->testLimit[0] && ($samples->testLimit[0]->fldsilow > $quantity || $samples->testLimit[0]->fldsihigh < $quantity)) {
                $updateData['fldabnormal'] = 1;
                $abnormal = true;
            }

            // if (!$abnormal) {
            //     $updateData['fldstatus'] = 'Verified';
            //     $updateData['fldtime_verify'] = $time;
            //     $updateData['fldsave_verify'] = 1;
            //     $updateData['fldcomp_verify'] = $computer;
            //     $updateData['flduserid_verify'] = $userid;
            // }
            \App\PatLabTest::where([
                'fldid' => $fldid,
                // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ])->update($updateData);
            
            if($fldid){
                $pat_lab_test_notification=\App\PatLabTest::where([
                    'fldid' => $fldid
                ])->first();
                if($pat_lab_test_notification) Helpers::logStack(["Report Dispatch of bill no. " . $pat_lab_test_notification->fldbillno, "Lab", ], ['encounter_id' => $pat_lab_test_notification->fldencounterval, 'bill_no' => $pat_lab_test_notification->fldbillno]);
                $message = Options::get('lab_reporting_notification_message')??'';
                $pat_lab_test_notification=[
                    'fldid' => $pat_lab_test_notification->fldid ??'',
                    'fldencounterval' => $pat_lab_test_notification->fldencounterval??'',
                    'fldtestid' => $pat_lab_test_notification->fldtestid??'',
                    'fldmethod' => $pat_lab_test_notification->fldmethod??'',
                    'fldgroupid' => $pat_lab_test_notification->fldgroupid??'',
                    'fldsampleid' => $pat_lab_test_notification->fldsampleid??'',
                    'fldsampletype' => $pat_lab_test_notification->fldsampletype??'',
                    'fldreportquali' => $pat_lab_test_notification->fldreportquali??'',
                    'fldreportquanti' => $pat_lab_test_notification->fldreportquanti??'',
                    'fldtestunit' => $pat_lab_test_notification->fldtestunit??'',
                    'fldstatus' => $pat_lab_test_notification->fldstatus??'',
                    'fldcomment' => $pat_lab_test_notification->fldcomment??'',
                    'fldtest_type' => $pat_lab_test_notification->fldtest_type??'',
                    'fldbillno' => $pat_lab_test_notification->fldbillno??'',
                    'flduserid_sample' => $pat_lab_test_notification->flduserid_sample??'',
                    'fldtime_sample' => $pat_lab_test_notification->fldtime_sample??'',
                    'fldcomp_sample' => $pat_lab_test_notification->fldcomp_sample??'',
                    'message' => $message??'',
                ];

                $users = \Auth::guard('admin_frontend')->user();
                $users->notify(new NearExpiryMedicine($users, $pat_lab_test_notification));
                
            }
            if (Options::get('lab_report_text_message')) {
                $encounter = \App\Encounter::where('fldencounterval', $samples->fldencounterval)
                    ->with(['patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank'])
                    ->first();
                $text = strtr(Options::get('lab_report_text_message'), [
                    '{$name}' => $encounter->patientInfo->fldfullname,
                    '{$systemname}' => isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '',
                ]);
                (new \Modules\AdminEmailTemplate\Http\Controllers\AdminSmsTemplateController())->sendSms([
                    'text' => $text,
                    'to' => $encounter->patientInfo->fldptcontact,
                ]);
            }

            return response()->json([
                'status' => TRUE,
                'abnormal' => $abnormal,
                'report_date' => $time,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (\GearmanException $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    public function saveQualitativeData(Request $request)
    {
        try {
            $fldid = $request->fldid;
            $qualitative = $request->qualitative;
            $quantative = $request->quantative;
            $examOption = $request->examOption;
            $examid = $request->examid;
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();
            $report_date = date("Y-m-d H:i:s");

            $patlabtest = \App\PatLabTest::where('fldid', $fldid)->with(['testgroup:fldtestid,fldgroupname'])->first();
            $updateData = [
                'fldnormal' => $request->get('isNormal'),
                'fldstatus' => 'Reported',
                'fldsave_report' => '1',
                'fldcomp_report' => $computer,
            ];

            if ($patlabtest && !empty($patlabtest->flduserid_report)) {
                $updateData['updated_by'] = $report_date;
                $updateData['updated_time'] = $userid;
                // $updateData[]
            } else {
                $updateData['fldtime_report'] = $report_date;
                $updateData['flduserid_report'] = $userid;
            }
            if ($examid == 'Culture & Sensitivity') {
                if (is_array($qualitative)) {
                    foreach ($qualitative as $data) {
                        \App\PatLabSubTest::where('fldid', $data['subtestid'])->update([
                            'fldreport' => NULL,
                            'fldabnormal' => $data['abnormal'] == 'true',
                            'fldsave' => '1',
                            'xyz' => '0'
                        ]);
                    }
                } else
                    $updateData['fldreportquali'] = $qualitative;
                \App\PatLabTest::where([
                    'fldid' => $fldid,
                    // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ])->update($updateData);
            } elseif ($examOption == 'Fixed Components') {
                $fldencounterval = $request->fldencounterval;
                $insert_data = [];
                $abnormal = FALSE;
                foreach ($qualitative as $key => $subtest) {
                    if ($subtest['abnormal'] == 'true')
                        $abnormal = TRUE;
                    $insert_data[] = [
                        'fldencounterval' => $fldencounterval,
                        'fldtestid' => $fldid,
                        'fldsubtest' => $subtest['fldsubtest'],
                        'fldtanswertype' => $subtest['fldanswertype'],
                        'fldreport' => isset($subtest['answer']) ? $subtest['answer'] : '',
                        'fldabnormal' => $subtest['abnormal'] == 'true',
                        'fldsave' => 1,
                        'fldchk' => 1,
                        'fldorder' => 0,
                        'fldfilepath' => NULL,
                        'xyz' => 0,
                        'fldsampleid' => $patlabtest->fldsampleid,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ];
                }

                \App\PatLabSubTest::where([
                    'fldencounterval' => $fldencounterval,
                    'fldtestid' => $fldid,
                ])->delete();
                \App\PatLabSubTest::insert($insert_data);

                // if (!$abnormal) {
                //     $updateData['fldstatus'] = 'Verified';
                //     $updateData['fldtime_verify'] = $report_date;
                //     $updateData['fldsave_verify'] = 1;
                //     $updateData['fldcomp_verify'] = $computer;
                //     $updateData['flduserid_verify'] = $userid;
                // }
                \App\PatLabTest::where([
                    'fldid' => $fldid,
                    // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ])->update($updateData);
            } else {
                $updateData['fldreportquanti'] = $quantative;
                $updateData['fldreportquali'] = $qualitative;
                $updateData['fldtime_report'] = $report_date;
                \App\PatLabTest::where([
                    'fldid' => $fldid,
                    // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ])->update($updateData);
            }

            if ($patlabtest) {
                Helpers::logStack(["Report Dispatch of bill no. " . $patlabtest->fldbillno, "Lab", ], ['encounter_id' => $patlabtest->fldencounterval, 'bill_no' => $patlabtest->fldbillno]);
                $groupname = ($patlabtest->testgroup) ? $patlabtest->testgroup->fldgroupname : "";
                \App\PatBilling::where([
                    'fldencounterval' => $patlabtest->fldencounterval,
                    'flditemtype' => 'Diagnostic Tests',
                    'flditemname' => $groupname,
                ])->update([
                    'fldsample' => $updateData['fldstatus'],
                ]);
            }
            // if(isset($patlabtest)){
            //     $pcr_check_test=strtolower($patlabtest->fldtestid);
            //     if ((strpos($pcr_check_test, 'covid') !== false) || strpos($pcr_check_test, 'pcr') !== false) {
            //         $this->updateTosyncIMU($patlabtest->fldencounterval,$fldid);
            //     }
            // }
            
            return response()->json([
                'status' => TRUE,
                'report_date' => $report_date,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (\GearmanException $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    // private function updateTosyncIMU($encounter_id,$fldid){
    //     $pcr_patient=PcrForm::where('fldencounterval',$encounter_id)->first();
    //     $get_patient_dob=\DB::table('tblencounter as tec')
    //                         ->join('tblpatientinfo as tpi', 'tec.fldpatientval', '=', 'tpi.fldpatientval')
    //                         ->select('tpi.fldptbirday')
    //                         ->where('tec.fldencounterval',$encounter_id)
    //                         ->first();
    //     $patlabtest = \App\PatLabTest::where('fldid', $fldid)->first();
    //     $get_lab_result=strtolower($patlabtest->fldreportquali);
    //     if($patlabtest->fldreportquali=="positive"){
    //         $lab_result=3;
    //     }else{
    //         $lab_result=4;
    //     }
    //     if (isset($get_patient_dob->fldptbirday)){
    //         $date = $get_patient_dob->fldptbirday;
    //         $date = \Carbon\Carbon::parse($date)->diff(\Carbon\Carbon::now())->format('%y, %m, %d , %h');
    //         $date = explode(', ', $date);

    //         if ($date[0] > 0)
    //             $date = 0;
    //         elseif ($date[1] > 0)
    //             $date = 1;
    //         elseif ($date[2] > 0)
    //             $date = 2;
    //     }
    //     if(isset($pcr_patient)){
            // $apiURL    = config( 'app.minio_url' );
			// $imuUsername    = config( 'app.minio_username' );
			// $imuPasaword    = config( 'app.minio_password' );
			// $postInput = array(
				// 'name'                  => $pcr_patient->fldname,
				// 'age'                   => $pcr_patient->fldage,
				// 'age_unit'              => $date,
				// 'sex'                   => $pcr_patient->fldsex,
			// 	'token'                 => $pcr_form->fldtoken,
			// 	'sample_token'          => $pcr_form->fldsampletoken,
				// 'emergency_contact_one' => $pcr_patient->fldcontact,
				// 'sample_collected_date' => $patlabtest->fldtime_sample,
				// 'province_id'           => $pcr_patient->fldprovince,
				// 'district_id'           => $pcr_patient->flddistrict,
				// 'municipality_id'       => $pcr_patient->fldmunicipality,
				// 'occupation'            => $pcr_patient->fldoccupation,
				// 'caste'                 => $pcr_patient->fldcaste,
				// 'ward'                  => $pcr_patient->fldward,
				// 'tole'                  => $pcr_patient->fldtole,
			// 	'sample_type'           => $request->sample_type,
			// 	'service_for'           => $request->service_for,
			// 	'service_type'          => $request->service_type,
			// 	'infection_type'        => $request->infection_type,
			// 	'travelled'             => $request->travelled,
				// 'registered_at'         => $pcr_patient->fldregisteredate,
				// 'lab_result'            => $lab_result,
			// 	'lab_test_date'         => $request->fldtime_sample,
			// 	'lab_test_time'         => $request->lab_test_time,
			// 	'lab_received_date'     => $request->lab_receive_date
			);
			// $client    = new \GuzzleHttp\Client();
			// $response  = $client->request( 'POST', $apiURL, [
			// 	'auth'        => [ $imuUsername, $imuPasaword ],
			// 	'form_params' => $postInput
			// ] );

			// return response()->json(['status'=>true,'message'=>'message successfully']
        // );
    //     }
    // }


    public function saveQualitativeDataUpdate(Request $request)
    {
        try {
            $fldid = $request->fldid;
            $qualitative = $request->qualitative;
            $quantative = $request->quantative;
            $examOption = $request->examOption;
            $examid = $request->examid;
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();
            $report_date = date("Y-m-d H:i:s");

            $updateData = [
                'fldtime_report' => $report_date,
                'fldstatus' => 'Reported',
                'fldsave_report' => '1',
                'fldcomp_report' => $computer,
                'flduserid_report' => $userid,
                'updated_by' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'updated_time' => date("Y-m-d H:i:s")
            ];
            if ($examid == 'Culture & Sensitivity') {
                \App\PatLabTest::where([
                    'fldid' => $fldid,

                    // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ])->update($updateData);
            } elseif ($examOption == 'Fixed Components') {
                $fldencounterval = $request->fldencounterval;
                $insert_data = [];
                $abnormal = FALSE;
                foreach ($qualitative as $key => $subtest) {
                    if ($subtest['abnormal'] == 'true') {
                        $abnormal = TRUE;
                    }

                    $insert_data[] = [
                        'fldencounterval' => $fldencounterval,
                        'fldtestid' => $fldid,
                        'fldsubtest' => $subtest['fldsubtest'],
                        'fldtanswertype' => $subtest['fldanswertype'],
                        'fldreport' => isset($subtest['answer']) ? $subtest['answer'] : '',
                        'fldabnormal' => $subtest['abnormal'] == 'true',
                        'fldsave' => 1,
                        'fldchk' => 1,
                        'fldorder' => 0,
                        'fldfilepath' => NULL,
                        'xyz' => 0,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ];
                }

                \App\PatLabSubTest::where('fldencounterval', $fldencounterval)->delete();
                \App\PatLabSubTest::insert($insert_data);

                // if (!$abnormal) {
                //     $updateData['fldstatus'] = 'Verified';
                //     $updateData['fldtime_verify'] = $report_date;
                //     $updateData['fldsave_verify'] = 1;
                //     $updateData['fldcomp_verify'] = $computer;
                //     $updateData['flduserid_verify'] = $userid;
                // }
                \App\PatLabTest::where([
                    'fldid' => $fldid
                    // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ])->update($updateData);
            } else {
                \App\PatLabTest::where([
                    'fldid' => $fldid,
                    // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ])->update([
                    'fldreportquanti' => $quantative,
                    'fldreportquali' => $qualitative,
                    'fldtime_report' => $report_date,
                    'fldstatus' => 'Reported',
                    'fldsave_report' => '1',
                    'updated_by' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                    'updated_time' => date("Y-m-d H:i:s")
                ]);
            }
            $patlabtest = \App\PatLabTest::where('fldid', $fldid)->first() ?? null;
            if($patlabtest) Helpers::logStack(["Report Dispatch of bill no. " . $patlabtest->fldbillno, "Lab", ], ['encounter_id' => $patlabtest->fldencounterval, 'bill_no' => $patlabtest->fldbillno]);



            return response()->json([
                'status' => TRUE,
                'report_date' => $report_date,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (\GearmanException $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    public function displayQualitativeForm(Request $request)
    {
        $data['examid'] = $examid = $request->testId;
        $fldid = $request->get('fldid');

        $test = Test::select('fldoption', 'fldcategory', 'fldtestid')
            ->where([
                ['fldtestid', $examid],
                ['fldtype', 'Qualitative'],
            ])->with('testoptions:fldtestid,fldanswer')->first();

        $data['options'] = $options = TestOption::where('fldanswertype', $test->fldoption)->where('fldtestid', $examid)->get();
        $type = $data['type'] = $test->fldoption;
        $category = $test->fldcategory;

        $patient_exam = \App\PatLabTest::select('fldreportquali', 'fldreportquanti', 'fldencounterval')->where('fldid', $fldid)->first();
        $fldencounterval = $patient_exam->fldencounterval;

        if ($examid == 'Culture & Sensitivity') {
            $header = $examid;
            $culture = \App\PatLabTest::select('fldreportquali', 'fldreportquanti', 'fldencounterval', 'fldid')
                ->where('fldid', $fldid)
                ->with('subTest', 'subTest.subtables')
                ->first();

            $testoptions = [];
            if ($culture->subtest()->count() == 0) {
                $testoptions = \App\TestOption::select('fldanswer')->where([
                    'fldtestid' => $examid,
                    'fldanswertype' => 'Single Selection',
                ])->get()->pluck('fldanswer');
            }

            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection', compact('test', 'fldid', 'examid', 'culture', 'header', 'type', 'fldencounterval', 'testoptions', 'category')),
                'category' => $category,
                'type' => $type,
            ];
        } elseif ($type == 'Clinical Scale') {
            $questions = TestOption::select('fldanswertype', 'fldanswer', 'fldscale', 'fldscalegroup')
                ->where('fldtestid', $examid)
                ->get();

            $formated_que = [];
            foreach ($questions as $que) {
                $formated_que[$que->fldscalegroup]['options'][$que->fldanswer] = $que->fldscale;
            }

            $header = $type;
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection', compact('test', 'fldid', 'examid', 'formated_que', 'header', 'type', 'fldencounterval', 'category')),
                'category' => $category,
                'type' => $type,
            ];
        } elseif ($type == 'Fixed Components' || $type == 'Custom Components') {
            $pat_answers = \App\PatLabSubTest::select('fldsubtest', 'fldtanswertype', 'fldreport', 'fldabnormal')->where('fldtestid', $fldid)->get()->toArray();
            $pat_answers = array_combine(array_column($pat_answers, 'fldsubtest'), $pat_answers);

            $all_options = $testQuali = \App\TestQuali::select('fldid', 'fldtestid', 'fldsubtest', 'fldtanswertype')
                ->with([
                    'subtests' => function($query) use ($examid) {
                        $query->select('fldid', 'fldsubtest', 'fldanswer', 'fldanswertype')->where('fldtestid', $examid);
                    },
                    'templates:id,title',
                ])->where('fldtestid', $examid)
                ->get();

            $all_options = array_combine(array_column($all_options->toArray(), 'fldsubtest'), $all_options->toArray());
            foreach ($all_options as $option) {
                $fldsubtest = $option['fldsubtest'];
                if ($testQuali->where('fldid', $option['fldid'])->all()) {
                    foreach($testQuali->where('fldid', $option['fldid'])->all() as $template_single){
                        $all_options[$fldsubtest]['templates_details'][] = $template_single->templates;
                    }
                }
                if (isset($pat_answers[$fldsubtest])) {
                    $all_options[$fldsubtest]['pat_answers'] = $pat_answers[$fldsubtest]['fldreport'];
                    $all_options[$fldsubtest]['pat_abnormal'] = $pat_answers[$fldsubtest]['fldabnormal'];
                }
            }
            $final_tests = $all_options;

            $header = $type;
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection', compact('test', 'fldid', 'examid', 'header', 'type', 'final_tests', 'fldencounterval', 'category')),
                'category' => $category,
                'type' => $type,
            ];
        } elseif ($type == 'No Selection') {
            $header = 'Enter Qualitative Value';
            $old_data = $patient_exam->fldreportquali;
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection', compact('test', 'fldid', 'examid', 'header', 'type', 'patient_exam', 'fldencounterval', 'category')),
                'category' => $category,
                'type' => $type,
            ];
        } elseif ($type == 'Left and Right') {
            $header = 'Left and Right Examination Report';
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection', compact('test', 'fldid', 'examid', 'header', 'type', 'patient_exam', 'fldencounterval', 'category')),
                'category' => $category,
                'type' => $type,
            ];
        } elseif ($type == 'Single Selection') {
            $header = 'Single Selection Report';
            //  =TestOption::where('fldanswertype', $test->fldoption)->where('fldtestid', $examid)->get();
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection', compact('test', 'fldid', 'examid', 'header', 'type', 'options', 'patient_exam', 'fldencounterval', 'category')),
                'category' => $category,
                'type' => $type,
                'options' => $options,
            ];
        } else {
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection', compact('test', 'fldid', 'examid', 'type', 'patient_exam', 'fldencounterval', 'category')),
                'category' => $category,
                'type' => $type,
            ];
        }
        return response()->json($data);
    }

    public function displayQualitativeFormUpdate(Request $request)
    {
        $data['examid'] = $examid = $request->testId;
        $fldid = $request->get('fldid');

        $test = Test::select('fldoption')->where('fldtestid', $examid)->where('fldtype', 'Qualitative')->first();
        $data['options'] = $options = TestOption::where('fldanswertype', $test->fldoption)->where('fldtestid', $examid)->get();
        $type = $data['type'] = $test->fldoption;

        $patient_exam = \App\PatLabTest::select('fldreportquali', 'fldreportquanti', 'fldencounterval')->where('fldid', $fldid)->first();
        $fldencounterval = $patient_exam->fldencounterval;

        if ($examid == 'Culture & Sensitivity') {
            $header = $examid;
            $culture = \App\PatLabTest::select('fldreportquali', 'fldreportquanti', 'fldencounterval', 'fldid')
                ->where('fldid', $fldid)
                ->with('subTest', 'subTest.subtables')
                ->first();

            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection-update', compact('fldid', 'examid', 'culture', 'header', 'type', 'fldencounterval')),
            ];
        } elseif ($type == 'Clinical Scale') {
            $questions = TestOption::select('fldanswertype', 'fldanswer', 'fldscale', 'fldscalegroup')
                ->where('fldtestid', $examid)
                ->get();

            $formated_que = [];
            foreach ($questions as $que) {
                $formated_que[$que->fldscalegroup]['options'][$que->fldanswer] = $que->fldscale;
            }

            $header = $type;
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection-update', compact('fldid', 'examid', 'formated_que', 'header', 'type', 'fldencounterval')),
            ];
        } elseif ($type == 'Fixed Components') {
            $pat_answers = \App\PatLabSubTest::select('fldsubtest', 'fldtanswertype', 'fldreport', 'fldabnormal')->where('fldtestid', $fldid)->get()->toArray();
            $pat_answers = array_combine(array_column($pat_answers, 'fldsubtest'), $pat_answers);

            $all_options = \App\TestQuali::select('fldsubtest', 'fldtanswertype')
                ->with('subtests:fldsubtest,fldanswer,fldanswertype')
                ->where('fldtestid', $examid)
                ->get()
                ->toArray();
            $all_options = array_combine(array_column($all_options, 'fldsubtest'), $all_options);
            foreach ($all_options as $option) {
                $fldsubtest = $option['fldsubtest'];
                if (isset($pat_answers[$fldsubtest])) {
                    $all_options[$fldsubtest]['pat_answers'] = $pat_answers[$fldsubtest]['fldreport'];
                    $all_options[$fldsubtest]['pat_abnormal'] = $pat_answers[$fldsubtest]['fldabnormal'];
                }
            }
            $final_tests = $all_options;

            $header = $type;
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection-update', compact('fldid', 'examid', 'header', 'type', 'final_tests', 'fldencounterval')),
            ];
        } elseif ($type == 'No Selection') {
            $header = 'Enter Qualitative Value';
            $old_data = isset($examdetail) && $examdetail ? $examdetail->fldreportquali : null;
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection-update', compact('fldid', 'examid', 'header', 'type', 'patient_exam', 'fldencounterval')),
            ];
        } elseif ($type == 'Left and Right') {
            $header = 'Left and Right Examination Report';
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection-update', compact('fldid', 'examid', 'header', 'type', 'patient_exam', 'fldencounterval')),
            ];
        } elseif ($type == 'Single Selection') {
            $header = 'Single Selection Report';
            //  =TestOption::where('fldanswertype', $test->fldoption)->where('fldtestid', $examid)->get();
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection-update', compact('fldid', 'examid', 'header', 'type', 'options', 'patient_exam', 'fldencounterval')),
                'options' => $options,
            ];
        } else {
            $data = [
                'view_data' => (string)view('laboratory::dynamic-forms.single-selection-update', compact('fldid', 'examid', 'type', 'patient_exam', 'fldencounterval')),
            ];
        }
        return $data['view_data'];
    }

    public function addComment(Request $request)
    {
        try {
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();
            $report_date = date("Y-m-d H:i:s");

            $fldid = $request->fldid;
            $comment = $request->lab_comment;
            $patlabtest = \App\PatLabTest::where([
                'fldid' => $fldid,
            ])->with('subTest')->first();

            $patlabtest->fldcomment = $comment;
            if ($patlabtest->subTest->count() == 0 && !($patlabtest->fldreportquali || $patlabtest->fldreportquanti)) {
                $patlabtest->fldstatus = 'Not Done';
                $patlabtest->fldsave_report = '1';
                $patlabtest->fldtime_report = $report_date;
                $patlabtest->fldcomp_report = $computer;
                $patlabtest->flduserid_report = $userid;
            }
            $patlabtest->save();

            Helpers::logStack(["Sample report not done of bill no. " . $patlabtest->fldbillno, "Lab", ], ['encounter_id' => $patlabtest->fldencounterval, 'bill_no' => $patlabtest->fldbillno]);

            
            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (\GearmanException $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    public function addAllComment(Request $request)
    {
        try {
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();
            $report_date = date("Y-m-d H:i:s");

            $fldids = $request->get('testids');
            $comment = $request->get('comment');
            $patlabtests = \App\PatLabTest::whereIn('fldid', $fldids)
                ->whereNull('fldcomment')
                ->with('subTest')
                ->get();

            foreach ($patlabtests as $patlabtest) {
                $patlabtest->fldcomment = $comment;
                if ($patlabtest->subTest->count() == 0 && !($patlabtest->fldreportquali || $patlabtest->fldreportquanti)) {
                    $patlabtest->fldstatus = 'Not Done';
                    $patlabtest->fldsave_report = '1';
                    $patlabtest->fldtime_report = $report_date;
                    $patlabtest->fldcomp_report = $computer;
                    $patlabtest->flduserid_report = $userid;
                }
                $patlabtest->save();
                Helpers::logStack(["Sample report not done of bill no. " . $patlabtest->fldbillno, "Lab", ], ['encounter_id' => $patlabtest->fldencounterval, 'bill_no' => $patlabtest->fldbillno]);
            }

            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
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
            \App\PatLabTest::where([
                'fldid' => $fldid,
                // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ])->update([
                'fldcondition' => $condition,
            ]);
            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (\GearmanException $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    public function loadPdf(Request $request)
    {
        $encounter_id = $request->encounter_id;
        /*copied from getPatientDetail*/
        $field = 'fldencounterval';

        $samples = \App\PatLabTest::select('fldencounterval', 'fldid', 'fldsampleid', 'fldtestid', 'fldstatus', 'fldsave_report', 'fldabnormal', 'flvisible', 'fldsampletype', 'fldmethod', 'fldtest_type', 'fldcondition', 'fldcomment', 'fldtime_sample', 'fldtime_report', 'fldreportquali', 'fldreportquanti')
            ->with([
                'testLimit:fldtestid,fldsilow,fldsihigh,fldsiunit',
                'subTest:fldtestid,fldsubtest,fldtanswertype,fldreport,fldabnormal,fldsampleid,fldid',
                'test:fldtestid,fldcategory',
                'subTest.quantity_range',
                'subTest.subtables',
            ])->where([
                [$field, '=', $encounter_id],
                // ['fldstatus', '=', 'Sampled'],
                // ['fldcomp_sample', 'like', Helpers::getCompName()],
            ])->where(function($query) {
                $query->where('fldstatus', 'Sampled')->orWhere('fldstatus', 'Reported')->orWhere('fldstatus', 'Not Done');
            })->where(function($query) {
                $query->where('fldsamplelocation', 'Hospital')->orWhere('fldsamplelocation', '')->orWhereNull('fldsamplelocation');
            })->with(['testLimit'])->get();

        $information['patientinfo'] = Helpers::getPatientByEncounterId($encounter_id);
        if (Options::get('worksheet_print_mode') == 'Continuous') {
            $information['samples'] = $samples;
            return $pdfString = view('laboratory::pdf.pdf-continuous', $information)/*->setPaper('a4')->stream('laboratory-' . $encounter_id . '.pdf')*/ ;
        } else {
            $information['samples'] = [];

            foreach ($samples as $sample) {
                if ($sample->test)
                    $information['samples'][$sample->test->fldcategory][] = $sample;
            }

            return $pdfString = view('laboratory::pdf.pdf', $information)/*->setPaper('a4')->stream('laboratory-' . $encounter_id . '.pdf')*/ ;
        }

    }

    public function historyPdf(Request $request)
    {
        $encounter_id = $request->encounter_id;
        $information['patientinfo'] = Helpers::getPatientByEncounterId($encounter_id);
        $encounter = \App\Encounter::where('fldpatientval', $information['patientinfo']->fldpatientval)->pluck('fldencounterval');

        /*copied from getPatientDetail*/
        $field = 'fldencounterval';
        $information['encounterId'] = $value = $encounter_id;

        // select fldid,fldsampletype,fldtest_type,fldtime_sample,fldmethod,fldtestid from tblpatlabtest where fldencounterval=&1 and (fldstatus=&2 or fldstatus=&3) and flvisible=&4", encid, "Reported", "Verified", "Visible

        $raw_samples = \App\PatLabTest::select('fldencounterval', 'fldid', 'fldsampleid', 'fldtestid', 'fldstatus', 'fldsave_report', 'fldabnormal', 'flvisible', 'fldsampletype', 'fldmethod', 'fldtest_type', 'fldcondition', 'fldcomment', 'fldtime_sample', 'fldtime_report', 'fldreportquali', 'fldreportquanti')
            ->where([
                // ['fldencounterval', $encounter_id],
                ['flvisible', '=', 'Visible'],
                // ['fldcomp_sample', 'like', Helpers::getCompName()],
            ])->whereIn($field, $encounter)
            ->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            })->with(['testLimit'])
            ->orderBy('fldencounterval', 'DESC')
            ->orderBy('tblpatlabtest.fldtime_report','DESC')
            ->get();
        $samples = [];
        foreach ($raw_samples as $sample) {
            $samples[$sample->fldencounterval][] = $sample;
        }
        $information['samples'] = $samples;

        return $pdfString = view('laboratory::pdf.pdf-history', $information)/*->setPaper('a4')->stream('laboratory-' . $encounter_id . '.pdf')*/ ;
    }

    public function generateAllPdfData($category = null)
    {
        $samples = \App\PatLabTest::select('fldencounterval', 'fldid', 'fldsampleid', 'fldtestid', 'fldstatus', 'fldsave_report', 'fldabnormal', 'flvisible', 'fldsampletype', 'fldmethod', 'fldtest_type', 'fldcondition', 'fldcomment', 'fldtime_sample', 'fldtime_report', 'fldreportquali', 'fldreportquanti')
            ->where([
                ['fldstatus', '=', 'Sampled'],
                ['fldcomp_sample', 'like', Helpers::getCompName()],
            ]);
        if ($category)
            $samples->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $category)->pluck('fldtestid')->toArray());

        $samples = $samples->with(['testLimit', 'patientEncounter', 'patientEncounter.patientInfo'])
            ->get();

        $data['pdfData'] = $samples;
        $data['category'] = $category ?? '%';

        return $pdfString = view('laboratory::pdf.pdf-category', $data)/*->setPaper('a4')->stream('laboratory-' . $category . '.pdf')*/ ;
    }


    /**
     * Patient Image functions
     */
    public function getPatientImage(Request $request)
    {
        $data = \App\PatientImage::select('fldtitle', 'fldpic', 'fldkeyword', 'flddetail', 'fldid')->where([
            'fldencounterval' => $request->get('encounterId'),
            'fldsave' => '1',
            'fldtestid' => $request->get('labtestid'),
        ])->get();

        $dataForView = array();
        foreach ($data as &$d) {
            $dataPrepare['fldtitle'] = $d->fldtitle;
            $dataPrepare['fldpic'] = "data:image/jpeg;base64," . $d->fldpic;
            $dataPrepare['fldkeyword'] = $d->fldkeyword;
            $dataPrepare['flddetail'] = $d->flddetail;
            $dataPrepare['fldid'] = $d->fldid;
            array_push($dataForView, $dataPrepare);
        }

        return $dataForView;
    }

    public function savePatientImage(Request $request)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'fldtitle' => 'required',
                'fldkey' => 'required',
                'flddetail' => 'required',
                'fldimage' => 'required',
            ], [
                'fldtitle.required' => 'The Title is required.',
                'fldkey.required' => 'The Key is required.',
                'flddetail.required' => 'The Detail is required.',
                'fldimage.required' => 'The Image is required.',
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

            $fldencounterval = $request->get('fldencounterval');
            $fldtestid = $request->get('fldtestid');
            $fldtitle = $request->get('fldtitle');
            $fldkey = $request->get('fldkey');
            $flddetail = $request->get('flddetail');

            $image = $request->file('fldimage')->getPathName();
            $image = base64_encode(file_get_contents($image));

            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();

            $fldid = \App\PatientImage::insertGetId([
                'fldencounterval' => $fldencounterval,
                'fldtestid' => $fldtestid,
                'fldpic' => $image,
                'fldtitle' => $fldtitle,
                'fldkeyword' => $fldkey,
                'flddetail' => $flddetail,
                'fldlink' => NULL,
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'flduptime' => NULL,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            $image = "data:image/jpeg;base64," . $image;
            return response()->json([
                'status' => TRUE,
                'data' => [
                    'fldid' => $fldid,
                    'fldtitle' => $fldtitle,
                    'fldpic' => $image,
                    'fldkeyword' => $fldkey,
                    'flddetail' => $flddetail,
                ],
                'message' => 'Successfully saved Information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save Information.',
            ]);
        }
    }

    public function updatePatientImage(Request $request)
    {
        try {
            $fldid = $request->get('fldid');
            $update_info = [
                'fldtitle' => $request->get('fldtitle'),
                'fldkeyword' => $request->get('fldkey'),
                'flddetail' => $request->get('flddetail')
            ];

            $image = $request->file('fldimage');
            if ($image) {
                $image = $request->file('fldimage')->getPathName();
                $image = base64_encode(file_get_contents($image));

                $update_info['fldpic'] = $image;
            }

            \App\PatientImage::where([['fldid', $fldid]])->update($update_info);
            if (isset($update_info['fldpic']))
                $update_info['fldpic'] = "data:image/jpeg;base64," . $image;

            return response()->json([
                'status' => TRUE,
                'data' => $update_info,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update Information.',
            ]);
        }
    }

    public function getTestGraphData(Request $request)
    {
        if (!$request->ajax())
            return view('laboratory::tests.testGraphData');

        $encounter_id = $request->get('encounter_id');
        $testid = $request->get('testid');

        $all_encounters = Encounter::select('fldpatientval')->where('fldencounterval', $encounter_id)->first();
        $all_encounters = Encounter::select('fldencounterval')->where('fldpatientval', $all_encounters->fldpatientval)->get()->toArray();
        $all_encounters = array_column($all_encounters, 'fldencounterval');

        $all_data = \App\PatLabTest::select("fldtime_sample", "fldreportquanti")->whereIn('fldencounterval', $all_encounters)->where('fldtestid', $testid)->get();
        $ret_data[] = ["Date", "Observation"];
        foreach ($all_data as $data)
            $ret_data[] = [$data->fldtime_sample, $data->fldreportquanti];

        return response()->json([
            'data' => $ret_data,
            'test_name' => $request->get('testid'),
            'dataCount' => count($ret_data),
        ]);
    }

    // culture
    public function getCultureComponents(Request $request)
    {
        // select distinct(fldsubtest) from tbltestquali where fldtestid='Culture & Sensitivity'
        return response()->json([
            'testquali' => \App\TestQuali::where('fldtestid', 'Culture & Sensitivity')->select('fldsubtest')->orderBy('fldtestid')->distinct()->get(),
            'selectedids' => \App\PatLabSubTest::where('fldtestid', $request->get('fldtestid'))->orderBy('fldtestid')->get()->pluck('fldsubtest'),
        ]);
    }

    public function saveCultureComponents(Request $request)
    {
        $components = $request->get('components');
        if ($components) {
            $testquali = \App\TestQuali::select('fldsubtest', 'fldtanswertype')
                ->where('fldtestid', 'Culture & Sensitivity')
                ->whereIn('fldsubtest', $components)
                ->get()
                ->pluck('fldtanswertype', 'fldsubtest')->toArray();
            $test = \App\PatLabTest::select('fldencounterval', 'fldid', 'fldreportquali')->where('fldid', $request->get('testid'))->with('subTest:fldtestid,fldsubtest')->first();
            $test->fldreportquali = NULL;
            $test->save();

            $subtests = $test->subTest->pluck('fldsubtest')->toArray();

            try {
                $insert_data = [];
                foreach ($components as $component) {
                    if (in_array($component, $subtests))
                        continue;

                    $insert_data[] = [
                        'fldencounterval' => $test->fldencounterval,
                        'fldtestid' => $test->fldid,
                        'fldsubtest' => $component,
                        'fldtanswertype' => $testquali[$component],
                        'fldreport' => NULL,
                        'fldabnormal' => '0',
                        'fldsave' => '0',
                        'fldchk' => '0',
                        'fldorder' => '0',
                        'fldfilepath' => NULL,
                        'xyz' => '0',
                    ];
                }

                \App\PatLabSubTest::insert($insert_data);
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully save components.'
                ]);
            } catch (Exception $e) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed to save components.'
                ]);
            }
        } else {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save components.'
            ]);
        }
    }

    public function getCultureComponentSubtests(Request $request)
    {
        $subtest = \App\PatLabSubTest::select('fldid', 'fldtestid', 'fldsubtest')
            ->where('fldid', $request->get('fldid'))
            ->with('pattest:fldid,fldtestid')
            ->first();

        return response()->json(
            \App\SubTestQuali::select('fldanswertype', 'fldanswer', 'fldscale', 'fldscalegroup')
                ->where([
                    'fldsubtest' => $subtest->fldsubtest,
                    'fldtestid' => $subtest->pattest->fldtestid,
                ])->orderBy('fldindex')->get()
        );
    }

    public function saveCultureSubtables(Request $request)
    {
        try {
            $fldtestid = $request->get('fldtestid');
            $fldsubtestid = $request->get('fldsubtestid');
            $subtables = $request->get('subtables');

            $insert = [];
            foreach ($subtables as $subtable) {
                $insert[] = \App\PatLabSubTable::create([
                    'fldtestid' => $fldtestid,
                    'fldsubtestid' => $fldsubtestid,
                    'fldtype' => 'Drug Sensitivity',
                    'fldvariable' => $subtable['fldvariable'],
                    'fldvalue' => $subtable['fldvalue'],
                    'fldcolm2' => $subtable['comment'],
                    'xyz' => '0',
                ]);
            }
            // if ($insert) {
            // \App\PatLabSubTable::insert($insert);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully added Drug Sensitivity.',
                'all_data' => $insert,
            ]);
            // }
        } catch (Exception $e) {
        }

        return response()->json([
            'status' => FALSE,
            'message' => 'Failed to add Drug Sensitivity.'
        ]);
    }

    public function deleteSubtables(Request $request)
    {
        try {
            \App\PatLabSubTable::where('fldid', $request->get('fldid'))->delete();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully deleted data.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to delete data.',
            ]);
        }
    }

    public function deleteCultureComponent(Request $request)
    {
        try {
            \App\PatLabSubTest::where('fldid', $request->get('fldid'))->delete();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully deleted data.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to delete data.',
            ]);
        }
    }

    public function updateSpecimen(Request $request)
    {
        try {
            $fldid = $request->get('fldid');
            $fldsampletype = $request->get('fldsampletype');

            \App\PatLabTest::where('fldid', $fldid)->update([
                'fldsampletype' => $fldsampletype,
            ]);

            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update Information.',
            ]);
        }
    }

    public function updateMethod(Request $request)
    {
        try {
            $fldid = $request->get('fldid');
            $fldmethod = $request->get('fldmethod');

            \App\PatLabTest::where('fldid', $fldid)->update([
                'fldmethod' => $fldmethod,
            ]);

            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update Information.',
            ]);
        }
    }

    public function deleteTest(Request $request)
    {
        try {
            \App\PatLabTest::where('fldid', $request->get('fldid'))->update([
                'flddeleted' => TRUE,
            ]);
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully deleted data.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to delete data.',
            ]);
        }
    }
}
