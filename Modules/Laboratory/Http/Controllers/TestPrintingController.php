<?php

namespace Modules\Laboratory\Http\Controllers;

use App\Consult;
use App\PatBilling;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Helpers;
use App\Utils\Options;
use Modules\AdminEmailTemplate\Http\Controllers\AdminEmailTemplateController;

class TestPrintingController extends Controller
{
    public function index(Request $request)
    {
        $category_id = $request->get('category_id');
        $data['categories'] = Helpers::getPathoCategory('Test');

        $data['has_saved_report'] = FALSE;
        $data['is_verified'] = FALSE;
        $data['date'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        $data['selects'] = [];
        $data['patients'] = $this->_getPatient($request);

        if ($request->ajax())
            return response()->json($data['patients']);

        if ($request->isMethod('post')) {
            $type = $request->get('type', 'encounter');
            $showall = $request->get('showall');
            $field = ($type == 'encounter') ? 'fldencounterval' : 'fldsampleid';
            $value = $request->get('encounter_sample');
            $encounter_id = ($type == 'encounter') ? $value : '';
            $status = $request->get('status');

            $samples = \App\PatLabTest::select('fldencounterval', 'fldid', 'fldchk', 'fldsave_report', 'fldtest_type', 'fldtestid', 'fldabnormal', 'fldstatus', 'flduserid_sample', 'flduserid_report', 'flduserid_verify', 'fldrefername', 'fldcondition', 'fldsampleid', 'fldsampletype', 'fldtime_sample', 'fldtime_report', 'fldprint', 'fldorder', 'fldcomment', 'fldreportquali')
                ->where([
                    [$field, '=', $value],
                    ['fldsave_report', '=', '1'],
                    // ['fldprint', '=', '0'],
                    ['flvisible', '=', 'Visible'],
                ]);

            $new = $request->get('new');
            $printed = $request->get('printed');
            if (!$new && $printed)
                $samples->where('fldprint', '1');
            elseif ($new && !$printed)
                $samples->where('fldprint', '0');

            // if ($request->segment(3) == 'verify' && $status != 'verified')
            //     $samples->whereNull('flduserid_verify');

            if ($category_id)
                $samples->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $category_id)->pluck('fldtestid')->toArray());

            /* if ($showall !== '1')
                $samples->where(function ($query) {
                    $query->where('fldstatus', 'Reported');
                    $query->orWhere('fldstatus', 'Verified');
                    $query->orWhere('fldstatus', 'Not Done');
                });
            else */if ($status)
                $samples->where('fldstatus', $status);

            $data['samples'] = $samples->with([
                'testLimit',
                'subTest',
                'test:fldtestid,fldoption',
                'subTest.subtables',
            ])->get();

            if (!$encounter_id && $data['samples']->isNotEmpty())
                $encounter_id = $data['samples']->toArray()[0]['fldencounterval'];

            $data['encounter_data'] = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
                ->with([
                    'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldmidname,fldrank',
                    'PatFindings:fldencounterval,fldcode',
                ])
                ->where('fldencounterval', $encounter_id)
                ->first();
            $data['has_saved_report'] = \App\PatReport::where('fldencounterval', $encounter_id)->get()->count() > 0;
            $data['is_verified'] = \App\PatLabTest::where([
                    'fldencounterval' => $encounter_id,
                    'fldstatus' => 'Verified',
                ])->get()->count() > 0;
        }
        return view('laboratory::tests.printing', $data);
    }

    public function _getPatient(Request $request)
    {
        $fromdate = $request->get('fromdate') ? Helpers::dateNepToEng($request->get('fromdate'))->full_date : date('Y-m-d');
        $todate = $request->get('todate') ? Helpers::dateNepToEng($request->get('todate'))->full_date : date('Y-m-d');
        $encounterId = $request->get('encounterId');
        $category_id = $request->get('category_id');
        $status = $request->get('status');
        $name = $request->get('name');

        $patients = \App\PatLabTest::select('fldencounterval', 'fldcomp_sample', 'fldsampleid', 'flduserid_report', 'fldtime_report')
            ->with([
                'patientEncounter:fldencounterval,fldpatientval,fldrank',
                'patientEncounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldmidname,fldrank',
                'patientEncounter.consultant:fldencounterval,fldconsultname',
            ])->where([
                ['fldsave_report', '=', '1'],
                // ['fldprint', '=', '0'],
                ['flvisible', '=', 'Visible'],
                ["fldtime_report", ">=", "$fromdate 00:00:00"],
                ["fldtime_report", "<=", "$todate 23:59:59.999"],
            ])
            ->groupBy('fldencounterval');

        if ($request->segment(3) == 'verify' && !$status)
            $patients = $patients->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Not Done');
                $query->orWhere('fldstatus', 'Verified');
            });
        elseif ($status)
            $patients = $patients->where('fldstatus', $status);
        else
            $patients = $patients->where(function ($query) {
                $query->orWhere('fldstatus', 'Verified');
                $query->orWhere('fldstatus', 'Not Done');
            });

        if ($request->segment(3) == 'printing') {
            $new = $request->get('new', 'new');
            $printed = $request->get('printed');
            if (!$new && $printed)
                $patients->where('fldprint', '1');
            elseif ($new && !$printed)
                $patients->where('fldprint', '0');
        }

        if ($encounterId)
            $patients = $patients->where('fldencounterval', $encounterId);
        if ($category_id)
            $patients->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $category_id)->pluck('fldtestid')->toArray());
        if ($name)
            $patients->whereHas('patientEncounter.patientInfo', function ($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name . '%');
            });

        return $patients->orderBy('fldtime_report','desc')->get();
    }

    public function searchPatient(Request $request)
    {
        $data = \App\PatientInfo::select('fldpatientval', 'fldptnamefir', 'fldptnamelast', 'fldptsex', 'fldptaddvill', 'fldptadddist', 'fldptcontact', 'fldptbirday', 'fldptcode', 'fldmidname', 'fldrank');

        if ($request->get('fldptsex'))
            $data->where('fldptsex', 'like', $request->get('fldptsex') . '%');
        if ($request->get('fldptnamefir'))
            $data->where('fldptnamefir', 'like', $request->get('fldptnamefir') . '%');
        if ($request->get('fldptnamelast'))
            $data->where('fldptnamelast', 'like', $request->get('fldptnamelast') . '%');
        if ($request->get('fldptaddvill'))
            $data->where('fldptaddvill', 'like', $request->get('fldptaddvill') . '%');
        if ($request->get('fldptadddist'))
            $data->where('fldptadddist', 'like', $request->get('fldptadddist') . '%');
        if ($request->get('fldptcontact'))
            $data->where('fldptcontact', 'like', $request->get('fldptcontact') . '%');
        if ($request->get('fldptcode'))
            $data->where('fldptcode', 'like', $request->get('fldptcode') . '%');

        return response()->json($data->get());
    }

    public function verifyReport(Request $request)
    {
        // UPDATE `tblpatlabtest` SET `fldstatus` = 'Verified', `flduserid_verify` = 'admin', `fldcomp_verify` = 'comp01', `fldsave_verify` = '1', `flduptime_verify` = '2020-05-29 15:58:19.674', `xyz` = '0' WHERE `fldid` = 64691
        try {
            $verify = $request->get('verify');
            if ($verify == 1) {
                $update_data = [
                    'fldstatus' => 'Verified',
                    'flduserid_verify' => \Auth::guard('admin_frontend')->user()->flduserid,
                    'fldcomp_verify' => Helpers::getCompName(),
                    'fldsave_verify' => $verify,
                    'fldtime_verify' => date('Y-m-d H:i:s'),
                    'xyz' => 0,
                ];
            } else {
                $update_data = [
                    'fldstatus' => 'Reported',
                    'flduserid_verify' => NULL,
                    'fldcomp_verify' => NULL,
                    'fldsave_verify' => NULL,
                    'fldtime_verify' => NULL,
                    'xyz' => 0,
                ];
            }

            $fldids = $request->get('fldid');
            if (!is_array($fldids))
                $fldids = [$fldids];

            $all_data = \App\PatLabTest::select('fldencounterval', 'fldtestid', 'fldid', 'fldreportquali')
                ->whereIn('fldid', $fldids)
                ->with([
                    'subTest:fldtestid,fldreport',
                    'test:fldtestid,fldcategory,fldoption,fldtype',
                    'testgroup:fldtestid,fldgroupname'
                ]);
            if ($verify == 1)
                $all_data->whereNull('flduserid_verify');
            $all_data = $all_data->get();

            $emptyTest = [];
            $dataForPatBilling = [];
            foreach ($all_data as $data) {
                $fldencounterval = $data->fldencounterval;
                $fldtestid = $data->fldtestid;

                $hasAllSubtestData = false;
                if ($data->subtest) {
                    $allSubtestData = $data->subtest->toArray();
                    $initialLenght = count($allSubtestData);
                    $allSubtestData = array_filter(array_column($allSubtestData, 'fldreport'));
                    $hasAllSubtestData = ($initialLenght !== 0 && ($initialLenght == count($allSubtestData)));
                }

                if (!$data->fldreportquali && !$hasAllSubtestData) {
                    $emptyTest[] = "EncounterId: {$fldencounterval}. Testname: {$fldtestid}";
                    $index = array_search($data->fldid, $fldids);
                    if ($index)
                        unset($fldids[$index]);

                    continue;
                }
                $dataForPatBilling[$fldencounterval][] = ($data->testgroup) ? $data->testgroup->fldgroupname : "";
            }

            $all_data = null;
            if ($request->get('verify') == 1) {
                \App\PatLabTest::whereNull('flduserid_verify')->whereIn('fldid', $fldids)->update($update_data);
                $patlabtest = \App\PatLabTest::whereNull('flduserid_verify')->where('fldid', $fldids[0] ?? 0)->first();
                if(isset($patlabtest)){
                    Helpers::logStack(["Verify of bill no. " . $patlabtest->fldbillno, "Lab", ], ['encounter_id' => $patlabtest->fldencounterval, 'bill_no' => $patlabtest->fldbillno]);
                }
                $all_data = \App\PatLabTest::select('fldencounterval', 'fldtestid')
                    ->whereNull('flduserid_verify')
                    ->whereIn('fldid', $fldids)
                    ->get()
                    ->toArray();
            } else {
                \App\PatLabTest::whereIn('fldid', $fldids)->update($update_data);
                $patlabtest = \App\PatLabTest::whereNull('flduserid_verify')->where('fldid', $fldids[0] ?? 0)->first();
                if(isset($patlabtest)){
                    Helpers::logStack(["Report Dispatch of bill no. " . $patlabtest->fldbillno, "Lab", ], ['encounter_id' => $patlabtest->fldencounterval, 'bill_no' => $patlabtest->fldbillno]);
                }
                $all_data = \App\PatLabTest::select('fldencounterval', 'fldtestid')
                    ->whereIn('fldid', $fldids)
                    ->get()->toArray();
            }

            if ($dataForPatBilling) {
                foreach ($dataForPatBilling as $encounter_id => $tests) {
                    \App\PatBilling::where([
                        'fldencounterval' => $encounter_id,
                        'flditemtype' => 'Diagnostic Tests',
                    ])->whereIn('flditemname', $tests)->update([
                        'fldsample' => $update_data['fldstatus'],
                    ]);
                }
            }

            $message = __('messages.update', ['name' => 'Data']);
            if (!empty($emptyTest))
                $message .= "<br>" . implode('<br>', $emptyTest);

            return response()->json([
                'status' => empty($emptyTest),
                'message' => $message,
                'updatedIds' => $fldids,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.' . $e->getMessage(),
            ]);
        }
    }

    public function printReport(Request $request)
    {
        $encounter_id = NULL;
        if ($request->type == 'encounter') {
            $encounter_id = $request->get('encounter_sample');
        } else {
            $sample_id = $request->get('encounter_sample');
        }

        $field = ($encounter_id) ? 'fldencounterval' : 'fldsampleid';
        $value = ($encounter_id) ?: $sample_id;
        $tests = $request->get('test');
        $status = $request->get('status');
        $showall = $request->get('showall');

        $samples = \App\PatLabTest::select('tblpatlabtest.fldencounterval', 'tblpatlabtest.fldid', 'tblpatlabtest.fldchk', 'tblpatlabtest.fldsave_report', 'tblpatlabtest.fldtest_type', 'tblpatlabtest.fldtestid', 'tblpatlabtest.fldabnormal', 'tblpatlabtest.fldstatus', 'tblpatlabtest.flduserid_sample', 'tblpatlabtest.flduserid_report', 'tblpatlabtest.flduserid_verify', 'tblpatlabtest.fldrefername', 'tblpatlabtest.fldcondition', 'tblpatlabtest.fldsampleid', 'tblpatlabtest.fldsampletype', 'tblpatlabtest.fldtime_sample', 'tblpatlabtest.fldtime_report', 'tblpatlabtest.fldprint', 'tblpatlabtest.fldorder', 'tblpatlabtest.fldcomment', 'tblpatlabtest.fldreportquali', 'tblpatlabtest.fldtime_verify', 'tblpatlabtest.fldmethod', 'tblpathocategory.order_by', 'tbltest.fldorder AS testorder', 'tblpatlabtest.fldgroupid')
            ->where([
                [$field, '=', $value],
                ['fldsave_report', '=', '1'],
                // ['fldprint', '=', '0'],
                ['flvisible', '=', 'Visible'],
            ]);

        if ($showall != '1') {
            if ($status)
                $samples->where('fldstatus', $status);
            else
                $samples->where(function ($query) {
                    $query->where('fldstatus', 'Reported');
                    $query->orWhere('fldstatus', 'Not Done');
                    $query->orWhere('fldstatus', 'Verified');
                });
        }

        if ($tests)
            $samples->whereIn('tblpatlabtest.fldid', $tests);

        if ($request->get('report_category_id'))
            $samples->whereIn('tblpatlabtest.fldtestid', \App\Test::where('fldcategory', 'like', $request->get('report_category_id'))->pluck('fldtestid')->toArray());
        $sample2 = $samples;
        $markprinted = $request->get('markprinted');
        if ($markprinted)
            $sample2->update([
                'fldprint' => '1',
            ]);

        $new = $request->get('new');
        $printed = $request->get('printed');
        if ($markprinted || (!$new && $printed))
            $samples->where('fldprint', '1');
        elseif ($new && !$printed)
            $samples->where('fldprint', '0');

        $samples = $samples->with([
            'bill:fldid,flditemname',
            'testLimit:fldtestid,fldsilow,fldsihigh,fldsiunit',
            'subTest:fldtestid,fldsubtest,fldtanswertype,fldreport,fldabnormal,fldsampleid,fldid',
            'test:fldtestid,fldcategory,fldoption',
            'test.testoptions:fldtestid,fldanswer',
            'subTest.quantity_range',
            'subTest.subtables',
            'refrename:username,firstname,middlename,lastname',
        ])->join('tbltest', 'tbltest.fldtestid', '=', 'tblpatlabtest.fldtestid')
            ->join('tblpathocategory', 'tblpathocategory.flclass', '=', 'tbltest.fldcategory')
            ->orderBy('tblpathocategory.order_by', 'ASC')
            ->orderBy('tbltest.fldorder', 'ASC')
            ->get();

        $data['fldrefername'] = implode(', ', array_filter($samples->unique('refrename.fldfullname')->pluck('refrename.fldfullname')->toArray()));
        $data['sampleid'] = implode(', ', $samples->unique('fldsampleid')->pluck('fldsampleid')->toArray());
        $data['reportUsers'] = array_unique($samples->pluck('flduserid_report')->toArray());
        $data['verifyUsers'] = array_unique($samples->pluck('flduserid_verify')->toArray());

        $data['sampleTime'] = ($samples->pluck('fldtime_sample')->toArray()) ? max($samples->pluck('fldtime_sample')->toArray()): '';
        $data['reportTime'] = ($samples->pluck('fldtime_report')->toArray()) ? max($samples->pluck('fldtime_report')->toArray()): '';
        $data['verifyTime'] = ($samples->pluck('fldtime_verify')->toArray()) ? max($samples->pluck('fldtime_verify')->toArray()): '';

        if (!$encounter_id && $samples->isNotEmpty())
            $encounter_id = $samples->toArray()[0]['fldencounterval'];

        $finalSample = [];
        foreach ($samples as $sample) {
            $fldsampletype = "Specimen";
            // $fldsampletype = $sample->fldsampletype;
            if ($sample->test) {
                $groupname = '';
                if ($sample->bill && $sample->bill->flditemname) {
                    $groupname = $sample->bill->flditemname;
                    $billingindex = strrpos($groupname, '(');
                    if ($billingindex)
                        $groupname = substr($groupname, 0, $billingindex);
                }
                $finalSample[$sample->test->fldcategory][$fldsampletype][$groupname][] = $sample;
            }
        }
        $data['samples'] = $finalSample;
        $ref_doctor_pat  = PatBilling::with('referUserdetail')->where('fldencounterval',$encounter_id)->where('fldrefer','!=', NULL)->first();
        if($ref_doctor_pat){
            $data['referable_doctor'] = (($ref_doctor_pat->fldrefer && $ref_doctor_pat->referUserdetail) ? $ref_doctor_pat->referUserdetail->fldtitlefullname :'' );
        }else{
            $ref_doctor_consult  = Consult::with('user')->where('fldencounterval',$encounter_id)->where('flduserid','!=',NULL)->first();
            if($ref_doctor_consult){
                $data['referable_doctor'] = (($ref_doctor_consult->flduserid && $ref_doctor_consult->user) ? $ref_doctor_consult->user->fldtitlefullname :'' );
            }
        }

        $data['encounter_data'] = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank', 'fldregdate')
            ->with([
                'patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldemail,fldmidname,fldrank,fldptcontact,fldptaddvill,fldmunicipality,fldwardno',
            ])->where('fldencounterval', $encounter_id)
            ->first();
        $pcr_check_test=array_keys($data['samples'][$sample->test->fldcategory]['Specimen']);
        $pcr_check_test=strtolower($pcr_check_test[0]);
        if (strpos($pcr_check_test, 'pcr') !== false) {
            $view = "laboratory::pdf.pcr_lab";
        }else{
            $view = "laboratory::pdf.lab";
            if (Options::get('lab_page_break') == '1'){
                $view = "laboratory::pdf.lab-one-page";
            }
        }        
        

        $testids=$request->test;
        //for reported user
        $patlabtest_prepared=\App\PatLabTest::select('flduserid_report',\DB::raw('COUNT(flduserid_report) as report_count'))
                            ->whereIn('fldid',$testids)
                            ->groupBy('flduserid_report')
                            ->orderBy('report_count', 'DESC')
                            ->get();
        $data['user_prepared_first']=\App\CogentUsers::select('fldcategory','firstname','middlename','lastname','nhbc','signature_image','signature_title')
                                    ->where('username',$patlabtest_prepared[0]->flduserid_report)
                                    ->first();
        $data['user_prepared_second']=\App\CogentUsers::select('fldcategory','firstname','middlename','lastname','nhbc','signature_image','signature_title')
                                    ->where('username',$patlabtest_prepared[1]->flduserid_report??$patlabtest_prepared[0]->flduserid_report)
                                    ->first();

        //for verified user
        $patlabtest_verified=\App\PatLabTest::select('flduserid_verify',\DB::raw('COUNT(flduserid_verify) as verified_count'))
                            ->whereIn('fldid',$testids)
                            ->groupBy('flduserid_verify')
                            ->orderBy('verified_count', 'DESC')
                            ->get();
        $data['user_verified_first']=\App\CogentUsers::select('fldcategory','firstname','middlename','lastname','nhbc','signature_image','signature_title')
                                    ->where('username',$patlabtest_verified[0]->flduserid_verify)
                                    ->first();
        $data['user_verified_second']=\App\CogentUsers::select('fldcategory','firstname','middlename','lastname','nhbc','signature_image','signature_title')
                                    ->where('username',$patlabtest_verified[1]->flduserid_verify??$patlabtest_verified[0]->flduserid_verify)
                                    ->first();
        if (isset($request->email_report)) {
            if (!file_exists(storage_path('report/pdf')))
                mkdir(storage_path('report/pdf'), 0777, true);

            $pdfName = $data['encounter_data']->patientInfo->fldptnamefir . '-' . $data['encounter_data']->fldencounterval . '.pdf';

            PDF::loadView($view, $data)->setPaper('a4')->save(storage_path('report/pdf/' . $pdfName));
            $user_rank = ((Options::get('system_patient_rank') == 1) && isset($data['encounter_data']) && isset($data['encounter_data']->fldrank)) ? $data['encounter_data']->fldrank : '';
            $emailData = [
                'template_id' => 1,
                'email' => $data['encounter_data']->patientInfo->fldemail,
                'full_name' => $user_rank . ' ' . $data['encounter_data']->patientInfo->fldptnamefir . ' ' . $data['encounter_data']->patientInfo->fldmidname . ' ' . $data['encounter_data']->patientInfo->fldptnamelast
            ];

            $email = new AdminEmailTemplateController();
            $email->sendEmail(storage_path('report/pdf/' . $pdfName), $emailData);
            unlink(storage_path('report/pdf/' . $pdfName));
        } else {
            /*IF EMAIL IS SELECTED THEN DO NOT DOWNLOAD PDF FILE*/
            return view($view, $data)/*->setPaper('a4')->stream('lab.pdf')*/ ;
        }
    }

    public function saveReport(Request $request)
    {
        if ($request->type == 'encounter') {
            $encounter_id = $request->get('encounter_sample');
        } else {
            $sample_id = $request->get('encounter_sample');
        }

        $field = ($encounter_id) ? 'fldencounterval' : 'fldsampleid';
        $value = ($encounter_id) ?: $sample_id;

        $samples = \App\PatLabTest::select('fldencounterval', 'fldid', 'fldchk', 'fldsave_report', 'fldtest_type', 'fldtestid', 'fldabnormal', 'fldstatus', 'flduserid_sample', 'flduserid_report', 'flduserid_verify', 'fldrefername', 'fldcondition', 'fldsampleid', 'fldsampletype', 'fldtime_sample', 'fldtime_report', 'fldprint', 'fldorder', 'fldcomment', 'fldreportquali')
            ->where([
                [$field, '=', $value],
                ['fldsave_report', '=', '1'],
                // ['fldprint', '=', '0'],
                ['flvisible', '=', 'Visible'],
            ])->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Not Done');
                $query->orWhere('fldstatus', 'Verified');
            });
        if ($request->get('lab_cat_id'))
            $samples->whereIn('fldtestid', \App\Test::where('fldcategory', 'like', $request->get('lab_cat_id'))->pluck('fldtestid')->toArray());

        $data['samples'] = $samples->with(['testLimit', 'subTest', 'test', 'subTest.quantity_range'])->get();

        if (!$encounter_id && $data['samples']->isNotEmpty())
            $encounter_id = $data['samples']->toArray()[0]['fldencounterval'];

        $data['encounter_data'] = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
            ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldemail,fldmidname,fldrank')
            ->where('fldencounterval', $encounter_id)
            ->first();

        if (!file_exists(storage_path('report/pdf')))
            mkdir(storage_path('report/pdf'), 0777, true);

        $pdfName = $data['encounter_data']->patientInfo->fldptnamefir . '-' . $data['encounter_data']->fldencounterval . '.pdf';

        PDF::loadView('laboratory::pdf.lab', $data)->setPaper('a4')->save(storage_path('report/pdf/' . $pdfName));
        $fileLocation = storage_path('report/pdf/' . $pdfName);
        $fp = fopen($fileLocation, 'rb');
        $content = fread($fp, filesize($fileLocation));
        $content = addslashes($content);
        fclose($fp);
        unlink(storage_path('report/pdf/' . $pdfName));

        try {
            \App\PatReport::insert([
                'fldencounterval' => $encounter_id,
                'fldcateg' => 'Diagnostic Tests',
                'fldtitle' => $request->get('fldtitle'),
                'flddetail' => NULL,
                'fldpic' => mb_convert_encoding($content, 'UTF-8', 'UTF-8'),
                'fldlink' => NULL,
                'flduserid' => \Auth::guard('admin_frontend')->user()->flduserid,
                'fldtime' => date('Y-m-d H:i:s'),
                'fldcomp' => \App\Utils\Helpers::getCompName(),
                'fldsave' => '1',
                'flduptime' => NULL,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save information.',
            ]);
        }
    }

    public function commentAdd(Request $request)
    {
        try {
            \App\PatLabTest::where([
                'fldid' => $request->get('fldid'),
            ])->update([
                'fldcomment' => $request->get('comment')
            ]);

            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    public function changeQuantity(Request $request)
    {
        try {
            $samples = \App\PatLabTest::select('fldencounterval', 'fldid', 'fldsampleid', 'fldtestid', 'fldstatus', 'fldsave_report', 'fldabnormal', 'flvisible', 'fldsampletype', 'fldmethod', 'fldtest_type', 'fldcondition', 'fldcomment', 'fldtime_sample', 'fldtime_report', 'fldreportquali', 'fldreportquanti')
                ->where([
                    ['fldid', '=', $request->quantity_fldid],
                    // ['fldstatus', '=', 'Sampled'],
                    // ['fldcomp_sample', 'like', Helpers::getCompName()],
                ])->with('testLimit')->first();

            $updateData = [
                'fldreportquali' => $request->quantity_update,
                'updated_by' => \Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'updated_time' => date("Y-m-d H:i:s"),
            ];

            if (is_numeric($request->quantity_update)) {
                $updateData['fldreportquanti'] = $request->quantity_update;
            }

            $abnormal = false;
            if ($samples->testLimit->isNotEmpty() && $samples->testLimit[0] && ($samples->testLimit[0]->fldsilow > $request->quantity_update || $samples->testLimit[0]->fldsihigh < $request->quantity_update)) {
                $updateData['fldabnormal'] = 1;
                $abnormal = true;
            }

            \App\PatLabTest::where([
                'fldid' => $request->quantity_fldid,
                // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ])->update($updateData);

            return response()->json([
                'status' => TRUE,
                'quantity' => $request->quantity_update,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    public function printReport1(){
        return view("laboratory::pdf.lab1");
    }

    public function qrPrint(Request $request)
    {
        try {        
            $request_data = decrypt($request->get('key'));
            $encounter_id = NULL;
            if ($request_data['type'] == 'encounter') {
                $encounter_id = $request_data['encounter_sample'];
            } else {
                $sample_id = $request_data['encounter_sample'];
            }

            $field = ($encounter_id) ? 'fldencounterval' : 'fldsampleid';
            $value = ($encounter_id) ?: $sample_id;
            $tests = $request_data['test'];
            $status = $request_data['status'];
            $showall = $request_data['showall']??'';

            $samples = \App\PatLabTest::select('tblpatlabtest.fldencounterval', 'tblpatlabtest.fldid', 'tblpatlabtest.fldchk', 'tblpatlabtest.fldsave_report', 'tblpatlabtest.fldtest_type', 'tblpatlabtest.fldtestid', 'tblpatlabtest.fldabnormal', 'tblpatlabtest.fldstatus', 'tblpatlabtest.flduserid_sample', 'tblpatlabtest.flduserid_report', 'tblpatlabtest.flduserid_verify', 'tblpatlabtest.fldrefername', 'tblpatlabtest.fldcondition', 'tblpatlabtest.fldsampleid', 'tblpatlabtest.fldsampletype', 'tblpatlabtest.fldtime_sample', 'tblpatlabtest.fldtime_report', 'tblpatlabtest.fldprint', 'tblpatlabtest.fldorder', 'tblpatlabtest.fldcomment', 'tblpatlabtest.fldreportquali', 'tblpatlabtest.fldtime_verify', 'tblpatlabtest.fldmethod', 'tblpathocategory.order_by', 'tbltest.fldorder AS testorder', 'tblpatlabtest.fldgroupid')
                ->where([
                    [$field, '=', $value],
                    ['fldsave_report', '=', '1'],
                    // ['fldprint', '=', '0'],
                    ['flvisible', '=', 'Visible'],
                ]);

            if ($showall != '1') {
                if ($status)
                    $samples->where('fldstatus', $status);
                else
                    $samples->where(function ($query) {
                        $query->where('fldstatus', 'Reported');
                        $query->orWhere('fldstatus', 'Not Done');
                        $query->orWhere('fldstatus', 'Verified');
                    });
            }

            if ($tests)
                $samples->whereIn('tblpatlabtest.fldid', $tests);

            if ($request_data['report_category_id'])
                $samples->whereIn('tblpatlabtest.fldtestid', \App\Test::where('fldcategory', 'like', $request_data['report_category_id'])->pluck('fldtestid')->toArray());
            $sample2 = $samples;
            $markprinted = $request_data['markprinted']??'';
            if ($markprinted)
                $sample2->update([
                    'fldprint' => '1',
                ]);

            $new = $request_data['new']??'';
            $printed = $request_data['printed']??'';
            if ($markprinted || (!$new && $printed))
                $samples->where('fldprint', '1');
            elseif ($new && !$printed)
                $samples->where('fldprint', '0');

            $samples = $samples->with([
                'bill:fldid,flditemname',
                'testLimit:fldtestid,fldsilow,fldsihigh,fldsiunit',
                'subTest:fldtestid,fldsubtest,fldtanswertype,fldreport,fldabnormal,fldsampleid,fldid',
                'test:fldtestid,fldcategory,fldoption',
                'test.testoptions:fldtestid,fldanswer',
                'subTest.quantity_range',
                'subTest.subtables',
                'refrename:username,firstname,middlename,lastname',
            ])->join('tbltest', 'tbltest.fldtestid', '=', 'tblpatlabtest.fldtestid')
                ->join('tblpathocategory', 'tblpathocategory.flclass', '=', 'tbltest.fldcategory')
                ->orderBy('tblpathocategory.order_by', 'ASC')
                ->orderBy('tbltest.fldorder', 'ASC')
                ->get();
            if ($samples->isEmpty()){
                return "Invalid url!!!";
            }
            $data['fldrefername'] = implode(', ', array_filter($samples->unique('refrename.fldfullname')->pluck('refrename.fldfullname')->toArray()));
            $data['sampleid'] = implode(', ', $samples->unique('fldsampleid')->pluck('fldsampleid')->toArray());
            $data['reportUsers'] = array_unique($samples->pluck('flduserid_report')->toArray());
            $data['verifyUsers'] = array_unique($samples->pluck('flduserid_verify')->toArray());

            $data['sampleTime'] = ($samples->pluck('fldtime_sample')->toArray()) ? max($samples->pluck('fldtime_sample')->toArray()): '';
            $data['reportTime'] = ($samples->pluck('fldtime_report')->toArray()) ? max($samples->pluck('fldtime_report')->toArray()): '';
            $data['verifyTime'] = ($samples->pluck('fldtime_verify')->toArray()) ? max($samples->pluck('fldtime_verify')->toArray()): '';

            if (!$encounter_id && $samples->isNotEmpty())
                $encounter_id = $samples->toArray()[0]['fldencounterval'];

            $finalSample = [];
            foreach ($samples as $sample) {
                $fldsampletype = "Specimen";
                // $fldsampletype = $sample->fldsampletype;
                if ($sample->test) {
                    $groupname = '';
                    if ($sample->bill && $sample->bill->flditemname) {
                        $groupname = $sample->bill->flditemname;
                        $billingindex = strrpos($groupname, '(');
                        if ($billingindex)
                            $groupname = substr($groupname, 0, $billingindex);
                    }
                    $finalSample[$sample->test->fldcategory][$fldsampletype][$groupname][] = $sample;
                }
            }
            $data['samples'] = $finalSample;
            $ref_doctor_pat  = PatBilling::with('referUserdetail')->where('fldencounterval',$encounter_id)->where('fldrefer','!=', NULL)->first();
            if($ref_doctor_pat){
                $data['referable_doctor'] = (($ref_doctor_pat->fldrefer && $ref_doctor_pat->referUserdetail) ? $ref_doctor_pat->referUserdetail->fldtitlefullname :'' );
            }else{
                $ref_doctor_consult  = Consult::with('user')->where('fldencounterval',$encounter_id)->where('flduserid','!=',NULL)->first();
                if($ref_doctor_consult){
                    $data['referable_doctor'] = (($ref_doctor_consult->flduserid && $ref_doctor_consult->user) ? $ref_doctor_consult->user->fldtitlefullname :'' );
                }
            }

            $data['encounter_data'] = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank', 'fldregdate')
                ->with([
                    'patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldemail,fldmidname,fldrank,fldptcontact,fldptaddvill,fldmunicipality,fldwardno',
                ])->where('fldencounterval', $encounter_id)
                ->first();
            $pcr_check_test=array_keys($data['samples'][$sample->test->fldcategory]['Specimen']);
            $pcr_check_test=strtolower($pcr_check_test[0]);
            $view = "laboratory::pdf.pcr_lab";
            // if (strpos($pcr_check_test, 'pcr') !== false) {
            // }else{
            //     $view = "laboratory::pdf.lab";
            //     if (Options::get('lab_page_break') == '1'){
            //         $view = "laboratory::pdf.lab-one-page";
            //     }
            // }        
            

            // $testids=$request_data['test'];
            // //for reported user
            // $patlabtest_prepared=\App\PatLabTest::select('flduserid_report',\DB::raw('COUNT(flduserid_report) as report_count'))
            //                     ->whereIn('fldid',$testids)
            //                     ->groupBy('flduserid_report')
            //                     ->orderBy('report_count', 'DESC')
            //                     ->get();
            // $data['user_prepared_first']=\App\CogentUsers::select('fldcategory','firstname','middlename','lastname','nhbc','signature_image','signature_title')
            //                             ->where('username',$patlabtest_prepared[0]->flduserid_report)
            //                             ->first();
            // $data['user_prepared_second']=\App\CogentUsers::select('fldcategory','firstname','middlename','lastname','nhbc','signature_image','signature_title')
            //                             ->where('username',$patlabtest_prepared[1]->flduserid_report??$patlabtest_prepared[0]->flduserid_report)
            //                             ->first();

            // //for verified user
            // $patlabtest_verified=\App\PatLabTest::select('flduserid_verify',\DB::raw('COUNT(flduserid_verify) as verified_count'))
            //                     ->whereIn('fldid',$testids)
            //                     ->groupBy('flduserid_verify')
            //                     ->orderBy('verified_count', 'DESC')
            //                     ->get();
            // $data['user_verified_first']=\App\CogentUsers::select('fldcategory','firstname','middlename','lastname','nhbc','signature_image','signature_title')
            //                             ->where('username',$patlabtest_verified[0]->flduserid_verify)
            //                             ->first();
            // $data['user_verified_second']=\App\CogentUsers::select('fldcategory','firstname','middlename','lastname','nhbc','signature_image','signature_title')
            //                             ->where('username',$patlabtest_verified[1]->flduserid_verify??$patlabtest_verified[0]->flduserid_verify)
            //                             ->first();
            // if (isset($request_data['email_report'])) {
            //     if (!file_exists(storage_path('report/pdf')))
            //         mkdir(storage_path('report/pdf'), 0777, true);

            //     $pdfName = $data['encounter_data']->patientInfo->fldptnamefir . '-' . $data['encounter_data']->fldencounterval . '.pdf';

            //     PDF::loadView($view, $data)->setPaper('a4')->save(storage_path('report/pdf/' . $pdfName));
            //     $user_rank = ((Options::get('system_patient_rank') == 1) && isset($data['encounter_data']) && isset($data['encounter_data']->fldrank)) ? $data['encounter_data']->fldrank : '';
            //     $emailData = [
            //         'template_id' => 1,
            //         'email' => $data['encounter_data']->patientInfo->fldemail,
            //         'full_name' => $user_rank . ' ' . $data['encounter_data']->patientInfo->fldptnamefir . ' ' . $data['encounter_data']->patientInfo->fldmidname . ' ' . $data['encounter_data']->patientInfo->fldptnamelast
            //     ];

            //     $email = new AdminEmailTemplateController();
            //     $email->sendEmail(storage_path('report/pdf/' . $pdfName), $emailData);
            //     unlink(storage_path('report/pdf/' . $pdfName));
            // } else {
                /*IF EMAIL IS SELECTED THEN DO NOT DOWNLOAD PDF FILE*/
                return view($view, $data)/*->setPaper('a4')->stream('lab.pdf')*/ ;
            // }
        } catch (\Exception $e) {
           return 'invalid url!!!';
        }
    }
}
