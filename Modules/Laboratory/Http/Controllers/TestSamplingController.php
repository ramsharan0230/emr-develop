<?php

namespace Modules\Laboratory\Http\Controllers;

use App\CogentUsers;
use App\Consult;
use App\GroupTest;
use App\Utils\Options;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Utils\Helpers;
use App\PatLabTest;
use App\PatBilling;
use App\ServiceCost;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

class TestSamplingController extends Controller
{
    public function index(Request $request)
    {
        $fromdate = $request->get('fromdate') ? Helpers::dateNepToEng($request->get('fromdate'))->full_date : date('Y-m-d');
        $todate = $request->get('todate') ? Helpers::dateNepToEng($request->get('todate'))->full_date : date('Y-m-d');
        $encounterId = $request->get('encounterId');
        $department = $request->get('department');
        $name = $request->get('name');
        $rejected = $request->get('rejected');
        
        $whereInFlditemname = Helpers::getWhereInForCategory($request->get('category'));

        $patients = PatBilling::select('fldencounterval', 'fldordcomp', 'fldordtime','fldtime','fldorduserid', 'fldbillno', 'fldtempbillno')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldrank',
                'encounter.consultant:fldencounterval,fldconsultname',
            ])->where(function($query) {
                $query->where('fldsave', '1')
                    ->orWhere(function($q) {
                        $q->where('fldsave', '0')->whereNotNull('fldtempbillno');
                    });
            })->WhereRaw("flditemqty>fldretqty")
            ->groupBy('fldencounterval');

        if ($whereInFlditemname)
            $patients = $patients->whereIn('flditemname', Helpers::getWhereInForCategory($request->get('category')));
        if ($encounterId)
            $patients = $patients->where('fldencounterval', 'LIKE', $encounterId . '%');
        if ($name)
            $patients = $patients->whereHas('encounter.patientInfo', function($q) use ($name) {
                $q->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', '%' . $name . '%');
            });
        if ($department) {
            $patients = $patients->whereHas('encounter.consultant', function($query) use ($department) {
                $query->whereIn('fldconsultname', \App\Department::select('flddept')->where('fldcateg', $department)->get()->pluck('flddept')->toArray());
            });
        }
        if($rejected){
            $patients = $patients->where([
                ['flditemtype', '=', 'Diagnostic Tests'],
                ['fldsample', '=', 'Rejected'],
                // ['fldsave', '=', '1'],
                // ['flditemqty', '>', 'fldretqty'],
                ["fldtime", ">=", "$fromdate 00:00:00"],
                ["fldtime", "<=", "$todate 23:59:59.999"],
            ])->orderBy('fldtime','desc')->paginate(50);
        }else{
            $patients = $patients->where([
                ['flditemtype', '=', 'Diagnostic Tests'],
                ['fldsample', '=', 'Waiting'],
                // ['fldsave', '=', '1'],
                // ['flditemqty', '>', 'fldretqty'],
                ["fldtime", ">=", "$fromdate 00:00:00"],
                ["fldtime", "<=", "$todate 23:59:59.999"],
            ])->orderBy('fldordtime','desc')->paginate(50);
        }
        $patientsData = $patients;

        if($request->ajax())
            return response()->json(
                (string) view('laboratory::tests.samplingPatientList', [
                    'patients' => $patientsData
                ])
            );

        $userRefer = \App\CogentUsers::where('fldreferral', 1)->get();
        $data = [
            'categories' => Helpers::getPathoCategory('Test'),
            'specimens' => Helpers::getSampleTypes(),
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
            'time' => date('h:i'),
            'all_users' => $userRefer,
            'next_sample_id' => Helpers::getNextAutoId('LabSampleNo', FALSE, TRUE),
            'refer_by' => $userRefer,
            'patients' => $patientsData,
        ];

        return view('laboratory::tests.sampling', $data);
    }

    public function getTests(Request $request)
    {
        $rejected_checkbox=$request->get('rejected_checkbox');
        $encounter_id = $request->get('encounter_id');
        $tests = PatBilling::select('flditemname', 'fldtime', 'fldid', 'fldencounterval', 'flditemqty', 'fldretqty', 'fldbillno', 'fldrefer', 'fldreason', 'flditemtype')
            ->where(function($query) {
                $query->where('fldsave', '1')
                    ->orWhere(function($q) {
                        $q->where('fldsave', '0')->whereNotNull('fldtempbillno');
                    });
            });
            if($rejected_checkbox){
                $tests=$tests->where([
                    ['flditemtype', '=', 'Diagnostic Tests'],
                    ['fldsample', '=', 'Rejected'],
                    ['fldencounterval', '=', $encounter_id],
                    ['flditemqty', '>', 'fldretqty'],
                ]);
            }else{
                $tests=$tests->where([
                    ['flditemtype', '=', 'Diagnostic Tests'],
                    ['fldsample', '=', 'Waiting'],
                    ['fldencounterval', '=', $encounter_id],
                    // ['fldsave', '=', '1'],
                    // ['fldtarget' , 'like', Helpers::getCompName()],
                    ['flditemqty', '>', 'fldretqty'],
                ]);
            }
        $tests=$tests->whereIn('flditemname', Helpers::getWhereInForCategory($request->get('category')))->get();

        $encounter_data = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
            ->with([
                'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptsex,fldptbirday,fldptcontact,fldprovince,fldptadddist,fldmunicipality,fldwardno,fldptcontact,fldtime,fldrank',
                'PatFindings:fldencounterval,fldcode',
            ])->where('fldencounterval', $encounter_id)
            ->first();

        //referral doctor
        $referable_doctor='';
        $ref_doctor_pat  = PatBilling::where('fldencounterval',$encounter_id)->where('fldrefer','!=', NULL)->first();
        if($ref_doctor_pat){
            $referable_doctor = (($ref_doctor_pat->fldrefer) ? $ref_doctor_pat->fldrefer :'' );
        }else{
            $ref_doctor_consult  = Consult::where('fldencounterval',$encounter_id)->where('flduserid','!=',NULL)->first();
            if($ref_doctor_consult){
                $referable_doctor = (($ref_doctor_consult->flduserid) ? $ref_doctor_consult->flduserid :'' );
            }
        }


        $autoid = $this->_getAutoId($encounter_data->fldcurrlocat);
        $message = "Successfully added data.";
        $status = TRUE;
        $test_data = $this->_addPatLabTest($tests, $encounter_id);
        if ($test_data === FALSE) {
            $message = "Failed to add data.";
            $status = FALSE;
        }

        return response()->json(compact('test_data', 'encounter_data', 'message', 'status', 'autoid','referable_doctor'));
    }

    public function getPatLabTest(Request $request)
    {
        $encounterid = $request->get('encounterid');
        $patlabtest = PatLabTest::select('fldid', 'fldgroupid', 'fldtestid', 'fldtime_sample', 'fldsampleid', 'fldsampletype', 'fldtest_type', 'fldrefername', 'fldcomment', 'fldencounterval', 'fldtime_start', 'fldbillno', 'flduptime_sample')
            ->with('bill:fldid,flditemname', 'test:fldtestid,fldvial')
            ->where([
                'fldencounterval' => $encounterid,
                // 'fldcomp_sample' => Helpers::getCompName()
            ]);
        if ($request->get('showall') == 'false') {
            $patlabtest = $patlabtest/*->whereNull('fldtime_sample')
                ->whereNull('fldsampleid')*/
            ->where('fldstatus', 'Ordered');
        } else {
            $patlabtest = $patlabtest/*->whereNotNull('fldtime_sample')
                ->whereNotNull('fldsampleid')*/
            ->where(function ($query) {
                $query->where('fldstatus', 'Ordered');
                $query->orWhere('fldstatus', 'Sampled');
            });
        }

        $patlabtest = $patlabtest->get();
        $encounter_data = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
            ->with('patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldrank')
            ->where('fldencounterval', $encounterid)
            ->first();

        //referral doctor
        $referable_doctor='';
        $ref_doctor_pat  = PatBilling::where('fldencounterval',$encounterid)->where('fldrefer','!=', NULL)->first();
        if($ref_doctor_pat){
            $referable_doctor = (($ref_doctor_pat->fldrefer) ? $ref_doctor_pat->fldrefer :'' );
        }else{
            $ref_doctor_consult  = Consult::where('fldencounterval',$encounterid)->where('flduserid','!=',NULL)->first();
            if($ref_doctor_consult){
                $referable_doctor = (($ref_doctor_consult->flduserid) ? $ref_doctor_consult->flduserid :'' );
            }
        }
        return response()->json(compact('patlabtest', 'encounter_data','referable_doctor'));
    }

    private function _addPatLabTest($testids, $encounter_id)
    {
        // dd($testids);
        // $testids = $request->get('fldid');
        try {
            $ret_data = [];
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $time = date('Y-m-d H:i:s');
            $computer = Helpers::getCompName();
            $hospitalid = Helpers::getUserSelectedHospitalDepartmentIdSession();

            \DB::beginTransaction();
            foreach ($testids as $test) {
                $flditemqty = $test->flditemqty ?: 1;
                $testid = $test->fldid;
                $bill = $test;

                if (!$bill) {
                    return response()->json([
                        'status' => FALSE,
                        'message' => 'Failed to save information.'
                    ]);
                }

                $groups = \App\GroupTest::where([
                    'fldgroupname' => $bill->flditemname,
                ])->get();

                // $encounter_id = $request->get('encounterid') ?: \Session::get('lab_addition_encounter_id');

                foreach ($groups as $group) {
                    $test_detail = \App\Test::where('fldtestid', $group->fldtestid)->first();
                    $patlabtestdata = [
                        'fldencounterval' => $encounter_id,
                        'fldtestid' => $group->fldtestid,
                        'fldmethod' => $group->fldactive,
                        'fldgroupid' => $testid,
                        'fldsampleid' => NULL,
                        'fldsampletype' => isset($test_detail->fldspecimen)?$test_detail->fldspecimen:"",
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
                        'hospital_department_id' => $hospitalid,
                    ];

                    for ($i=0; $i < $flditemqty; $i++) {
                        $patLabTest = PatLabTest::create($patlabtestdata);
                        $fldid = $patLabTest->fldid;
                        
                        $ret_data[] = [
                            'patbillingid' => $bill->fldid,
                            'fldid' => $fldid,
                            'flditemname' => $bill->flditemname,
                            'fldtestid' => $group->fldtestid,
                            'fldsampletype' => isset($test_detail->fldspecimen)?$test_detail->fldspecimen:"",
                            'fldvial' => isset($test_detail->fldvial)?$test_detail->fldvial:"",
                        ];
                    }
                }
                Helpers::logStack(["Order of bill no. " . $bill->fldbillno, "Lab", ], ['encounter_id' => $bill->fldencounterval, 'bill_no' => $bill->fldbillno]);
                // PatBilling::where([['fldid', $testid]])->update([
                //     'fldsample' => 'Sampled',
                //     'fldpayto' => NULL,
                //     'xyz' => '0',
                // ]);
            }
            \DB::commit();

            return  $ret_data;
        } catch (Exception $e) {
            \DB::rollBack();
            return FALSE;
        }
    }

    public function updateTest(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'testids' => 'required',
            'fldsampleid' => 'required',
            // 'fldsampletype' => 'required',
            // 'fldrefername' => 'required',
            // 'fldtime_start' => 'required',
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

            $options_sample_type=Options::get('sample_type');
            if($options_sample_type=='Daily'){
                $autoid = Helpers::getNextAutoIdSympleType('DailySampleNo',TRUE);
            }elseif($options_sample_type=='Monthly'){
                $autoid = Helpers::getNextAutoIdSympleType('MonthlySampleNo',TRUE);
            }elseif($options_sample_type=='Yearly'){
                $autoid = Helpers::getNextAutoIdSympleType('YearlySampleNo',TRUE);
            }

            $req_time = $request->get('fldtime');
            $req_time = strlen($req_time) == 5 ? "$req_time:00" : $req_time;
            $date = $request->get('fldtime_start');
            $date = ($date) ? Helpers::dateNepToEng($date)->full_date . ' ' . $req_time : NULL;
            $update_data = array_filter([
                'fldsampleid' => $fldsampleid,
                // 'fldsampletype' => $request->get('fldsampletype'),
                'fldrefername' => $request->get('fldrefername'),
                'fldcomment' => $request->get('fldcomment'),
                'fldtime_start' => $date,
            ]);

            if ($update_data) {
                $update_data += [
                    'fldstatus' => 'Sampled',
                    'flduserid_sample' => $userid,
                    'fldtime_sample' => $time,
                    'fldcomp_sample' => $computer,
                    'fldsave_sample' => '1',
                    'flduptime_sample' => $time,
                    'xyz' => '0',
                    'fldsamplelocation' => $request->get('fldsamplelocation'),
                ];
                $update = PatLabTest::whereIn('fldid', $request->get('testids'))
                    // ->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                    ->whereNotNull('fldsampletype')
                    ->update($update_data);

                $patlabtest = PatLabTest::where('fldid', $request->get('testids')[0] ?? 0)->first() ?? null;
                if($patlabtest) Helpers::logStack(["Collected Sample of bill no. ".$patlabtest->fldbillno, "Lab", ], ['encounter_id' => $patlabtest->fldencounterval, 'bill_no' => $patlabtest->fldbillno]);


                $patbillingids = $request->get('patbillingids', []);
                $patbillingids = array_filter($patbillingids);
                $encounter_id = $request->get('fldencounterval');
                if ($patbillingids) {
                    \App\PatBilling::where([
                        ['fldencounterval', $encounter_id,],
                        ['flditemtype', 'Diagnostic Tests',],
                    ])->whereIn('fldid', $patbillingids)->update([
                        'fldsample' => $update_data['fldstatus'],
                    ]);
                } else {
                    $all_data = \App\PatLabTest::select('fldencounterval', 'fldtestid')
                        ->whereIn('fldid', $request->get('testids'))
                        ->get()->toArray();

                    if ($all_data) {
                        $tests = array_column($all_data, 'fldtestid');
                        foreach ($tests as $test) {
                            \App\PatBilling::where([
                                ['fldencounterval', $encounter_id,],
                                ['flditemtype', 'Diagnostic Tests',],
                                ['flditemname', 'like', "%$test%"],
                            ])->update([
                                'fldsample' => $update_data['fldstatus'],
                            ]);
                        }
                    }
                }

                if($update) {
                    \App\PatLabSubTest::whereIn('fldtestid', $request->get('testids'))->update([
                        'fldsampleid' => $fldsampleid,
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
            'status' => FALSE,
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
            // 'fldtime_start' => 'required',
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

        // $fldsampleid = $request->get('fldsampleid');
        $pdfData['fldsampleid'] = $request->get('fldsampleid');

        try {

            $pdfData['encounter_data'] = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
                ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldrank')
                ->where('fldencounterval', $request->fldencounterval)
                ->first();

            $pdfData['barcodeData'] = DNS2D::getBarcodeHTML($pdfData['encounter_data']->fldpatientval, 'QRCODE', 3, 3);

            if ($request->generate_worksheet != 'false') {
                $pdfData['specimen'] = $request->get('fldsampletype');
                $pdfData['refer_by'] = CogentUsers::where('username',$request->fldrefername)->first();
                // $pdfData['collectedAt'] = "2020-20-20 20:20:20";
                // $pdfData['testsData'] = PatLabTest::select('tbltest.fldcategory', 'tblpatlabtest.fldtestid', 'tblpatlabtest.fldmethod','tblpatlabtest.fldtime_sample')
                //     ->join('tbltest', 'tbltest.fldtestid', 'tblpatlabtest.fldtestid')
                //     ->where([
                //         'fldencounterval' => $request->fldencounterval,
                //        // 'fldcomp_sample' => Helpers::getCompName()
                //     ])->where(function ($query) {
                //         $query->where('fldstatus', 'Ordered');
                //         $query->orWhere('fldstatus', 'Sampled');
                //     })
                //     ->whereIn('fldid', $request->get('testidsprint'))
                //     ->get();
                $rawTestData = PatLabTest::select('tbltest.fldcategory', 'tblpatlabtest.fldsampleid', 'tblpatlabtest.fldtestid', 'tblpatlabtest.fldmethod', 'tblpatlabtest.fldsampletype', 'tblpatlabtest.flduserid_report', 'tblpatlabtest.flduserid_verify', 'tblpatlabtest.fldgroupid', 'tblpatlabtest.fldtime_sample')
                    ->leftJoin('tbltest', 'tbltest.fldtestid', 'tblpatlabtest.fldtestid')
                    ->where([
                        'fldencounterval' => $request->fldencounterval,
                    ])->where(function ($query) {
                        $query->where('fldstatus', 'Ordered');
                        $query->orWhere('fldstatus', 'Sampled');
                    })->with('testGroup')
                    ->whereIn('fldid', $request->get('testids'))
                    ->orderBy('tblpatlabtest.fldsampleid', 'ASC')
                    ->get();
                    $pdfData['fldtime_sample']=$rawTestData[0]??'';
                $testData = [];
                foreach ($rawTestData as $key => $value) {
                    if ($value->testGroup) {
                        $testData[$value->testGroup->fldtestid][] = $value;
                    } else
                        $testData[] = $value;
                }
                $pdfData['testData'] = $testData;
                if (Options::get('worksheet_print_mode') == 'Continuous') {
                    return view('laboratory::pdf.sampling-A', $pdfData);
                } else {
                    return view('laboratory::pdf.sampling-B', $pdfData);
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
            // 'fldsampleid' => 'required',
            // 'fldsampletype' => 'required',
            // 'fldrefername' => 'required',
            // 'fldtime_start' => 'required',
        ], [
            'testids.required' => 'Please select sample to print baarcode.',
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
        // $fldsampleid = $request->get('fldsampleid');

        try {
            $pdfData['encounter_data'] = \App\Encounter::select('fldencounterval', 'fldcurrlocat', 'fldpatientval', 'fldrank')
                ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldrank')
                ->where('fldencounterval', $request->fldencounterval)
                ->first();


            if ($request->generate_barcode != 'false') {
                $pdfData['testsData'] = PatLabTest::select('fldencounterval', 'fldsampleid', 'fldtestid')
                    ->where([
                        'fldencounterval' => $request->fldencounterval,
                       // 'fldcomp_sample' => Helpers::getCompName()
                    ])->where(function ($query) {
                        $query->where('fldstatus', 'Ordered');
                        $query->orWhere('fldstatus', 'Sampled');
                    })
                    ->whereIn('fldid', $request->get('testids'))
                    ->get();
                return view('laboratory::pdf.barcode', $pdfData);

            }

        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update test data.'
            ]);
        }
    }

    public function getAutoId(Request $request)
    {
        $current_sampleid_value=$request->current_sampleid_value;
        $options_sample_type=Options::get('sample_type');
        if($options_sample_type=='Daily'){
            return Helpers::getAutoIncrementIdSympleType('DailySampleNo',$current_sampleid_value);
        }elseif($options_sample_type=='Monthly'){
           return Helpers::getAutoIncrementIdSympleType('MonthlySampleNo',$current_sampleid_value);
        }elseif($options_sample_type=='Yearly'){
            return Helpers::getAutoIncrementIdSympleType('YearlySampleNo',$current_sampleid_value);
        }else{
            return $this->_getAutoId($request->get('location'));
        }
    }

    private function _getAutoId($location)
    {   
        $options_sample_type=Options::get('sample_type');
        if($options_sample_type=='Daily'){
            $autoid = Helpers::getNextAutoIdSympleType('DailySampleNo',FALSE);
        }elseif($options_sample_type=='Monthly'){
            $autoid = Helpers::getNextAutoIdSympleType('MonthlySampleNo',FALSE);
        }elseif($options_sample_type=='Yearly'){
            $autoid = Helpers::getNextAutoIdSympleType('YearlySampleNo',FALSE);
        }else{
            $autoid = Helpers::getNextAutoId('LabSampleNo', FALSE, TRUE);
            if ($location)
                $autoid = Helpers::getDepartmentByLocation($location) . $autoid;
        }
        return $autoid;
    }

    private function _getTestGroupData($modulename, $type)
    {
        $data = [];
        if ($modulename == 'radio') {
            if ($type == 'test')
                $data = \App\Radio::select('fldexamid', 'fldtype')->get();
            elseif ($type == 'group')
                $data = \App\ServiceCost::select('flditemname AS fldexamid', \DB::raw('"Qualitative" AS fldtype'))->where([
                    'flditemtype' => 'Radio Diagnostics',
                    // 'fldgroup' => 'fldgroup',
                ])->get();
        } elseif ($modulename == 'lab') {
            if ($type == 'test')
                $data = \App\Test::select('fldtestid AS fldexamid', 'fldtype')->get();
            elseif ($type == 'group')
                $data = \App\ServiceCost::select('flditemname AS fldexamid', \DB::raw('"Qualitative" AS fldtype'))->where([
                    'flditemtype' => 'Diagnostic Tests',
                    // 'fldgroup' => 'fldgroup',
                ])->get();
        }
        return $data;
    }

    public function getTestGroupList(Request $request)
    {
        $modulename = $request->get('modulename');
        $type = $request->get('type');
        $billingmode = $request->get('billingmode');

        $data = [];
        /*
            Radio:-
            1. test
            select fldexamid from tblradio where fldtype like '%'
            2. group
            select flditemname as col from tblservicecost where flditemtype='Radio Diagnostics' and (fldgroup like 'General' or fldgroup='%')

            Lab:-
            1. test
            select fldtestid from tbltest where fldtype like '%'
            2. group
            select fldtestid,fldtesttype,fldactive from tblgrouptest where fldgroupname=&1", xitem
        */
        if ($modulename == 'radio') {
            if ($type == 'test')
                $data = \App\Radio::select('fldexamid', 'fldtype')->get();
            elseif ($type == 'group')
                $data = \App\ServiceCost::select('flditemname AS fldexamid', \DB::raw('"Qualitative" AS fldtype'))->where([
                    'flditemtype' => 'Radio Diagnostics',
                    'fldgroup' => $billingmode
                ])->get();
        } elseif ($modulename == 'lab') {

            if ($type == 'test')
                $data = \App\Test::select('fldtestid AS fldexamid', 'fldtype')->get();
            elseif ($type == 'group')
                $data = \App\GroupTest::select('fldgroupname AS fldexamid', 'fldtesttype AS fldtype')->where([
                    // 'flditemtype' => 'Diagnostic Tests',
                    ["fldgroupname", "like", "%{$billingmode}%"]
                ])->groupBy('fldgroupname')->get();
        }

        return response()->json($data);
    }

    public function saveTestGroupList(Request $request)
    {
        $modulename = $request->get('modulename');
        $type = $request->get('type');
        $encounterId = $request->get('encounterId');
        $testids = $request->get('testids');
        $modalLocation = ($modulename == 'lab') ? '\App\PatLabTest' : '\App\PatRadioTest';
        $computer = Helpers::getCompName();
        $time = date('Y-m-d H:i:s');

        $encounter_data = \App\Encounter::select('fldpatientval', 'fldrank')
            ->with('patientInfo:fldpatientval,fldptsex,fldrank')
            ->where('fldencounterval', $encounterId)
            ->first();
        $gender = $encounter_data->patientInfo->fldptsex;

        \DB::beginTransaction();
        try {
            $ret_data = [];
            $flvisible = 'Visible';
            foreach ($testids as $testid) {
                $data = [];
                $diff_data = [];
                $fldmethod = 'Regular';
                $fldtest_type = 'Qualitative';
                if ($modulename == 'radio') {
                    $diff_data = [
                        'fldcomp_report' => $computer,
                        'fldstatus' => 'Waiting',
                    ];
                    if ($type == 'test') {
                        $data = \App\Radio::select('fldexamid', 'fldtype')->where('fldexamid', $testid)->first();
                        $fldtest_type = $data->fldtype;
                    } elseif ($type == 'group') {
                        $data = \App\ServiceCost::select('flditemname AS fldexamid', \DB::raw('"Qualitative" AS fldtype'))->where([
                            'flditemtype' => 'Radio Diagnostics',
                            'flditemname' => $testid,
                        ])->first();
                        $group = \App\GroupRadio::select('fldtestid', 'fldtesttype', 'fldactive')
                            ->where('fldgroupname', $testid)
                            ->where(function ($query) {
                                $query->where('fldptsex', 'Both Sex');
                                $query->orWhere('fldptsex', $gender ?? null);
                            })->get();

                        foreach ($group as $g) {
                            $fldtestid = $g->fldtestid;
                            $fldmethod = $g->fldactive;
                            $fldtest_type = $g->fldtesttype;
                            $insert_data = [
                                    'fldencounterval' => $encounterId,
                                    'fldtestid' => $fldtestid,
                                    'fldmethod' => $fldmethod,
                                    'fldgroupid' => '0',
                                    'fldreportquanti' => '0',
                                    'fldprint' => '0',
                                    'fldabnormal' => '0',
                                    'flvisible' => $flvisible,
                                    'fldtest_type' => $fldtest_type,
                                    'fldchk' => '0',
                                    'fldorder' => '0',
                                    'fldsave_report' => '0',
                                    'fldsave_verify' => '0',
                                    'xyz' => '0',
                                ] + $diff_data;

                            $fldid = $modalLocation::insertGetId($insert_data);
                            $ret_data[] = [
                                'fldid' => $fldid,
                                'fldsampletype' => isset($insert_data['fldsampletype']) ? $insert_data['fldsampletype'] : '',
                                'fldvial' => isset($fldvial) ? $fldvial : '',
                                'fldtestid' => $fldtestid,
                                'fldmethod' => $fldmethod,
                                'flvisible' => $flvisible,
                                // 'fldtime_report' => $time,
                            ];
                        }
                        $fldmethod = $group->fldactive;
                        $fldtest_type = $group->fldtesttype;
                    }
                } elseif ($modulename == 'lab') {
                    $diff_data = [
                        'fldcomp_sample' => $computer,
                        'fldsave_sample' => '0',
                        'fldsave_start' => '0',
                        'fldstatus' => 'Ordered',
                        // 'fldtime_report' => $time,
                    ];
                    if ($type == 'test') {
                        $data = \App\Test::select('fldtestid AS fldexamid', 'fldtype', 'fldspecimen', 'fldvial', 'fldtestid')->where('fldtestid', $testid)->with('subtests')->first();
                        if ($data) {
                            $fldtest_type = $data->fldtype;
                            $diff_data['fldsampletype'] = $data->fldspecimen;
                            $fldvial = $data->fldvial;
                        }
                    } elseif ($type == 'group') {
                        $group = \App\GroupTest::select('fldtestid', 'fldtesttype', 'fldactive')
                            ->with('test:fldtestid,fldspecimen,fldvial')
                            ->where('fldgroupname', $testid)
                            ->get();

                        foreach ($group as $g) {
                            $fldtestid = $g->fldtestid;
                            $fldmethod = $g->fldactive;
                            $fldtest_type = $g->fldtesttype;
                            $insert_data = [
                                    'fldencounterval' => $encounterId,
                                    'fldtestid' => $fldtestid,
                                    'fldmethod' => $fldmethod,
                                    'fldgroupid' => '0',
                                    'fldreportquanti' => '0',
                                    'fldprint' => '0',
                                    'fldabnormal' => '0',
                                    'flvisible' => $flvisible,
                                    'fldtest_type' => $fldtest_type,
                                    'fldchk' => '0',
                                    'fldorder' => '0',
                                    'fldsave_report' => '0',
                                    'fldsave_verify' => '0',
                                    'xyz' => '0',
                                ] + $diff_data;

                            $fldid = $modalLocation::insertGetId($insert_data);
                            $ret_data[] = [
                                'fldid' => $fldid,
                                'fldtestid' => $fldtestid,
                                'fldmethod' => $fldmethod,
                                'flvisible' => $flvisible,
                                'fldsampletype' => ($g->test) ? $g->test->fldspecimen : '',
                                'fldvial' => ($g->test->fldvial) ? $g->test->fldvial : '',
                                // 'fldtime_report' => $time,
                            ];
                        }
                    }
                }

                if ($data && $type != 'group') {
                    $insert_data = [
                            'fldencounterval' => $encounterId,
                            'fldtestid' => $data->fldexamid,
                            'fldmethod' => $fldmethod,
                            'fldgroupid' => '0',
                            'fldreportquanti' => '0',
                            'fldprint' => '0',
                            'fldabnormal' => '0',
                            'flvisible' => $flvisible,
                            'fldtest_type' => $fldtest_type,
                            'fldchk' => '0',
                            'fldorder' => '0',
                            'fldsave_report' => '0',
                            'fldsave_verify' => '0',
                            'xyz' => '0',
                        ] + $diff_data;

                    $fldid = $modalLocation::insertGetId($insert_data);
                    if ($data->subtests && $modulename == 'lab'&& $data->fldtestid != 'Culture & Sensitivity' ) {
                        $patsubtests = [];
                        foreach ($data->subtests as $subtest) {
                            $patsubtests[] = [
                                'fldencounterval' => $encounterId,
                                'fldtestid' => $fldid,
                                'fldsubtest' => $subtest->fldsubtest,
                                'fldtanswertype' => $subtest->fldtanswertype,
                                'fldreport' => NULL,
                                'fldabnormal' => '0',
                                'fldsave' => '0',
                                'fldchk' => '1',
                                'fldorder' => '0',
                                'xyz' => '0',
                            ];
                        }
                        if ($patsubtests)
                            \App\PatLabSubTest::insert($patsubtests);
                    }
                    $ret_data[] = [
                        'fldid' => $fldid,
                        'fldsampletype' => isset($insert_data['fldsampletype']) ? $insert_data['fldsampletype'] : '',
                        'fldvial' => isset($fldvial) ? $fldvial : '',
                        'fldtestid' => $data->fldexamid,
                        'fldmethod' => $fldmethod,
                        'flvisible' => $flvisible,
                        // 'fldtime_report' => $time,
                    ];
                }
            }

            \DB::commit();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved information.',
                'data' => $ret_data,
            ]);
        } catch (Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save information.'
            ]);
        }
    }

    public function getTestPdf(Request $request)
    {
        $encounterid = $request->encounterid;
        $patlabtest = PatLabTest::select('fldid', 'fldgroupid', 'fldtestid', 'fldtime_sample', 'fldsampleid', 'fldsampletype', 'fldtest_type', 'fldrefername', 'fldcomment', 'fldencounterval', 'fldtime_start', 'fldbillno')
            ->with('bill:fldid,flditemname', 'test:fldtestid,fldvial')
            ->where([
                'fldencounterval' => $encounterid,
                // 'fldcomp_sample' => Helpers::getCompName()
            ]);
        if ($request->get('showall') == 'false') {
            $patlabtest = $patlabtest->whereNull('fldtime_sample')
                ->whereNull('fldsampleid')
                ->where(function ($query) {
                    $query->where('fldstatus', 'Ordered');
                    $query->orWhere('fldstatus', 'Sampled');
                });
        } else {
            $patlabtest = $patlabtest->whereNotNull('fldtime_sample')
                ->whereNotNull('fldsampleid')
                ->where('fldstatus', 'Reported');
        }
        $data['tests'] = $patlabtest->get();

        return view('laboratory::pdf.test-pdf', $data);
    }

    public function samplingPatientReport(Request $request)
    {
        $tests = PatBilling::select('flditemname', 'fldtime', 'fldid', 'fldencounterval')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptsex,fldptbirday,fldptadddist,fldrank'
            ])->where([
                ['flditemtype', '=', 'Diagnostic Tests'],
                ['fldsample', '=', 'Waiting'],
                // ['fldsave', '=', '1'],
                // ['fldtarget', 'like', Helpers::getCompName()],
                ['flditemqty', '>', 'fldretqty'],
            ])->where(function($query) {
                $query->where('fldsave', '1')
                    ->orWhere(function($q) {
                        $q->where('fldsave', '0')->whereNotNull('fldtempbillno');
                    });
            })->whereIn('flditemname', Helpers::getWhereInForCategory($request->get('category')))->paginate(50);

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
                        $name = $user_rank . ' ' . $test->encounter->patientInfo->fldptnamefir . ' ' . $test->encounter->patientInfo->fldptnamelast;
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
        ]);
    }
}
