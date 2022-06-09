<?php

namespace Modules\Discharge\Http\Controllers;

use App\Anasethesia;
use App\BillingSet;
use App\CogentUsers;
use App\Complaints;
use App\Encounter;
use App\PatTiming;
use App\Department;
use App\Exam;
use App\ExamGeneral;
use App\Examlimit;
use App\MacAccess;
use App\PatBilling;
use App\PatFindings;
use App\PatientSubExam;
use App\PatLabTest;
use App\PatLabSubTest;
use App\PatRadioTest;
use App\PatRadioSubTest;
use App\PatGeneral;
use App\HospitalDepartment;
use App\PatSubGeneral;
use App\Pathdosing;
use App\PatientExam;
use App\PatientInfo;
use App\Procedure;
use App\Discharge;
use App\Departmentbed;
use App\Settings;
use App\Drug;
use App\Surgical;
use App\Code;
use App\Otextraexaminationdetail;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Utils\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\DiagnoGroup;
use App\Referlist;
use App\PatientDate;
use Auth;
use Cache;
use Session;

class DischargeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
//        dd($request->all());
        // echo "discharge"; exit;

            try {
                $encounter_id_session = Session::get('discharge_encounter_id');
                $data = [];
                $data['patient_status_disabled'] = 0;
                $data['diagnosisgroup'] = Cache::remember('diagno_set', 60 * 60 * 24, function () {
                    return DiagnoGroup::select('fldgroupname')->distinct()->get();
                });
                // $data['consultants'] = CogentUsers::->where(function ($queryNested) {
                //     $queryNested->orWhere('fldopconsult', '1')
                //         ->orWhere('fldipconsult', '1')
                // })->where('status','active')->get();
                $data['consultants'] = CogentUsers::where('status', 'active')
                ->where('fldcategory','!=','Anaesthetists')
                ->where(function ($queryNested) {
                    $queryNested->orWhere('fldopconsult', '1')
                        ->orWhere('fldipconsult', '1');
                })
                ->get();
                
                $data['anaesthetists'] = CogentUsers::where('status', 'active')
                ->where(function ($queryNested) {
                    $queryNested->orWhere('fldopconsult', '1')
                        ->orWhere('fldipconsult', '1');
                })
                ->where('fldcategory','Anaesthetists')
                ->get();
                $diagnocat = $this->getInitialDiagnosisCategory();
                $data['diagnosiscategory'] = $diagnocat;
                // dd($data['diagnosiscategory']);
                $patFindingMultiple = PatFindings::where('fldencounterval', $encounter_id_session)
                    ->where(function ($queryNested) {
                        $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                            ->orWhere('fldtype', 'Allergic Drugs');
                    })
                    ->where('fldsave', 1)
                    ->get();
                $data['patdiago'] = $patdiago = $patFindingMultiple->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->where('fldencounterval', $encounter_id_session)->all();
                // dd($data['patdiago']);
                $data['patdrug'] = $patdrug = $patFindingMultiple->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->where('fldencounterval', $encounter_id_session)->all();
                $data['enpatient'] = Encounter::where('fldencounterval', $encounter_id_session)->first();
                $data['dischargeDepartment'] = Department::select('flddept')->where('fldcateg','Patient Ward')->orWhere('fldcateg','Emergency')->get();
                // echo count($data['dischargeDepartment']); exit;
                // dd($data);
                // $user = Auth::guard('admin_frontend')->user();
                // if (isset($user->user_is_superadmin) && count($user->user_is_superadmin)) {
                //     $userdept = HospitalDepartmentUsers::select('hospital_department_id')->groupBy('hospital_department_id')->get();

                //     if(isset($userdept) and count($userdept) > 0){
                //         foreach($userdept as $ud){
                //             $dept = HospitalDepartment::where('id',$ud->hospital_department_id)->where('status','active')->first();
                //             $branch = HospitalBranch::where('id',$dept->branch_id)->first();
                //             $deptdata[] = array(
                //                 'department_id'=>$dept->id,
                //                 'department_name'=>$dept->name,
                //                 'branch_name' =>$branch->name
                //             );
                //         }
                //     }
                // }else{
                //     $userdept = HospitalDepartmentUsers::select('hospital_department_id')->where('user_id',$user->id)->get();

                //     if(isset($userdept) and count($userdept) > 0){
                //         foreach($userdept as $ud){
                //             $dept = HospitalDepartment::where('id',$ud->hospital_department_id)->where('status','active')->first();
                //             $branch = HospitalBranch::where('id',$dept->branch_id)->first();
                //             $deptdata[] = array(
                //                 'department_id'=>$dept->id,
                //                 'department_name'=>$dept->name,
                //                 'branch_name' =>$branch->name
                //             );
                //         }
                //     }
                // }

                // $data['departments'] = $deptdata;
                if ($request->has('encounter_id') || $encounter_id_session) {
                    $data['departments'] = DB::table('tbldepartment')
                        ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
                        ->where('tbldepartment.fldcateg', 'Patient Ward')
                        ->orWhere('tbldepartment.fldcateg', 'Emergency')
                        ->select('tbldepartment.flddept')
                        ->groupBy('tbldepartment.flddept')
                        ->get();

                    if ($request->has('encounter_id'))
                        $encounter_id = $request->get('encounter_id');
                    else
                        $encounter_id = $encounter_id_session;

                    $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();

                    $data['enpatient'] = $enpatient;
                    $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();

                    /*department change enabled*/
                    /*if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
                        if (!in_array($data['enpatient']->fldcurrlocat, $current_user->department->pluck('flddept')->toArray())) {
                            Session::flash('display_popup_error_success', true);
                            Session::flash('error_message', 'You are not authorized to view this patients information.');
                            return redirect()->route('admin.dashboard');
                        }
                    }*/

                    session(['discharge_encounter_id' => $encounter_id]);
                    /*create last encounter id*/
                    Helpers::dischargeEncounterQueue($encounter_id);
                    $encounterIds = Options::get('discharge_last_encounter_id');
                    $arrayEncounter = unserialize($encounterIds);
                    /*create last encounter id*/

                    $dataflag = array(
                        'fldinside' => 1,
                    );

                    Encounter::where('fldencounterval', $encounter_id)->update($dataflag);

                    $diagnocat = $this->getInitialDiagnosisCategory();
                    $data['diagnosiscategory'] = $diagnocat;
                    $data['billingset'] = $billingset = BillingSet::get();

                    // dd($data);
                    $patient_id = $enpatient->fldpatientval;
                    $data = [
                        'patient_status_disabled' => $enpatient->fldadmission == "Discharged" ? 1 : 0,
                        'patient' => PatientInfo::where('fldpatientval', $patient_id)->first(),
                        'enpatient' => $enpatient,
                        'patient_id' => $patient_id,
                        'billingset' => $billingset = BillingSet::get(),
                        'enable_freetext' => 1,
                        'new_proc_refere' => CogentUsers::select('flduserid', 'username')->where('fldreferral', 1)->get(),
                        'new_proc_payable' => CogentUsers::select('flduserid', 'username')->where('fldpayable', 1)->get(),
                        'variables' => Procedure::all(),
                        'consultants' => CogentUsers::where('status', 'active')
                            ->where('fldcategory','!=','Anaesthetists')
                            ->where(function ($queryNested) {
                                $queryNested->orWhere('fldopconsult', '1')
                                    ->orWhere('fldipconsult', '1');
                            })
                            ->get(),
                        'other_items' => PatBilling::where([
                            'fldencounterval' => $encounter_id,
                            'fldsave' => 0,
                            'fldprint' => 0,
                            'flditemtype' => 'Other Items'
                        ])->where('fldordcomp', 'like', '%' . Helpers::getCompName() . '%')
                            ->where('fldstatus', '!=', 'Punched')
                            ->get(),
                        'pat_findings' => $pat_findings = PatFindings::where([
                            ['fldencounterval', $encounter_id],
                            ['fldsave', 1],
                        ])->whereIn('fldtype', [
                            'Provisional Diagnosis',
                            'Final Diagnosis',
                            'Allergic Drugs',
                        ])->select('fldcode', 'fldid', 'fldtype')->get(),
                    ];
                    $diagnocat = $this->getInitialDiagnosisCategory();
                    $data['digno_group_list'] = $diagnocat;
                    $patients = PatientInfo::where('fldpatientval', $patient_id)->first();
                    $end = Carbon::parse($patients->fldptbirday);
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
                    $patFindingMultiple = PatFindings::where('fldencounterval', $encounter_id)
                        ->where(function ($queryNested) {
                            $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                                ->orWhere('fldtype', 'Allergic Drugs');
                        })
                        ->where('fldsave', 1)
                        ->get();
                    $data['patdiago'] = $patdiago = $patFindingMultiple->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->where('fldencounterval', $encounter_id)->all();

                    $data['past_patdiagno'] = $past_patdiagno = PatFindings::join('tblencounter', 'tblpatfindings.fldencounterval', '=', 'tblencounter.fldencounterval')
                        ->where(['tblencounter.fldpatientval' => $patient_id, 'tblpatfindings.fldsave' => 1])
                        ->whereIn('tblpatfindings.fldtype', ['Provisional Diagnosis', 'Final Diagnosis'])
                        ->where('tblencounter.fldencounterval', '!=', $encounter_id)
                        ->select('tblpatfindings.fldtype', 'tblpatfindings.fldcode')
                        ->get();
                    $data['progress_note'] = ExamGeneral::where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes'])->whereIn('flditem', ['Progress Note'])->get();
                    $data['clinic_note'] = ExamGeneral::where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes'])->whereIn('flditem', ['Clinicians Note'])->get();
                    $data['nurse_note'] = ExamGeneral::where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes'])->whereIn('flditem', ['Nurses Note'])->get();
                    $data['dischargeDepartment'] = Department::select('flddept')->get();
                    $data['diagnosisgroup'] = Cache::remember('diagno_set', 60 * 60 * 24, function () {
                        return DiagnoGroup::select('fldgroupname')->distinct()->get();
                    });
                    $data['consultants'] = CogentUsers::where('status', 'active')
                    ->where('fldcategory','!=','Anaesthetists')
                    ->where(function ($queryNested) {
                        $queryNested->orWhere('fldopconsult', '1')
                            ->orWhere('fldipconsult', '1');
                    })
                    ->get();
                    
                    $data['anaesthetists'] = CogentUsers::where('status', 'active')
                    ->where(function ($queryNested) {
                        $queryNested->orWhere('fldopconsult', '1')
                            ->orWhere('fldipconsult', '1');
                    })
                    ->where('fldcategory','Anaesthetists')
                    ->get();
                    // dd($data['painmanagement']);
                    if (isset($body_height) && isset($body_weight)) {
                        $hei = ($body_height->fldrepquali / 100); //changing in meter
                        $divide_bmi = ($hei * $hei);
                        if ($divide_bmi > 0) {
                            $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                        }
                    }
                    $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));

                    $data['date'] = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
                }
                $diagnocat = $this->getInitialDiagnosisCategory();
                $data['diagnosiscategory'] = $diagnocat;
                return view('discharge::index', $data);
            } catch (\Exception $e) {
                dd($e);
            }


    }

    /**
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getInitialDiagnosisCategory()
    {
        try {
            $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
            $data = [];
            while ($csvLine = fgetcsv($handle, 1000, ";")) {
                if (isset($csvLine[1]) && strlen($csvLine[1]) == 3) {
                    $data[] = [
                        'code' => trim($csvLine[1]),
                        'name' => trim($csvLine[3]),
                    ];
                }
            }
            //sort($data);
            usort($data, function ($a, $b) {
                return $a['name'] <=> $b['name'];
            });
            // dd($data);
            return $data;
        } catch (\Exception $exception) {
            return [];
        }
    }


    /** FUnction for getting admitted patient department wise by anish */

    public function getPatientDepartmentWise(Request $request)
    {

        try {

            $department = $request->get('department') ?? null;
            // echo $department; exit;
            if (!$department || $department == null) {
                return false;
            }
            $patients_array = DB::select(DB::raw("SELECT tblencounter.fldencounterval, tbldepartmentbed.fldbed,  tblpatientinfo.fldptnamefir, tblpatientinfo.fldmidname, tblpatientinfo.fldptnamelast, tblpatientinfo.fldptsex,tblpatientinfo.fldptbirday, TIMESTAMPDIFF(YEAR, tblpatientinfo.fldptbirday, CURDATE()) AS age
                            FROM tblencounter
                            JOIN tbldepartmentbed on tbldepartmentbed.fldencounterval =tblencounter.fldencounterval
                            JOIN tblpatientinfo on tblpatientinfo.fldpatientval = tblencounter.fldpatientval
                            WHERE tblencounter.fldadmission='Admitted'
                            AND tbldepartmentbed.flddept = '$department'
                            "));

            // dd($patients_array);
            return \response(['patients' => $patients_array]);

        } catch (\Exception $exception) {
            dd($exception);
            return \response(['error' => 'Something went wrong']);
            dd($exception);
        }


    }

    //Diagnosis ko data
    public function getDiagnosis(Request $request)
    {
        try {
            $encounter_id = $request->get('encounter_id') ?? null;
            if (!$encounter_id || $encounter_id == null) {
                return false;
            }
            // yo bhaneko opd ko diagnosis same garne
            $patFindingMultiple = PatFindings::where('fldencounterval', $encounter_id)
                ->where(function ($queryNested) {
                    $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                        ->orWhere('fldtype', 'Allergic Drugs');
                })
                ->where('fldsave', 1)
                ->get();
            $patdiago = $patFindingMultiple->where('fldtype', 'Provisional Diagnosis')->
            where('fldsave', 1)->where('fldencounterval', $encounter_id)->all();
            // dd($patdiago);
            $html = '';
            if (isset($patdiago) and count($patdiago) > 0) {

                foreach ($patdiago as $diag) {

                    $html .= $diag->fldcode . ', ';
                }

            }else{
                $html = 'No Diagnosis Available';
            }

            return \response($html);

        } catch (\Exception $exception) {
            dd($exception);
            return \response(['error' => 'Something went wrong']);

        }
    }

    //Complants ko data
    public function getComplaints(Request $request)
    {
        try {
            $encounter_id = $request->get('encounter_id') ?? null;
            if (!$encounter_id || $encounter_id == null) {
                return false;
            }
            //Complaint fetch gareko chha
//            $complaints = $complaint = Cache::remember('conplaints_list', 60 * 60 * 24, function () {
//                return Complaints::get();
//            });

            $complaints = ExamGeneral::where([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Presenting Symptoms',
                'fldsave' => '1',
            ])->get();
            $html = '';
            if ($complaints->count() > 0) {

                foreach ($complaints as $complaint) {

                    $html .= $complaint->flditem . '-' . $complaint->fldreportquali . '-' . $complaint->fldreportquanti . 'hr' . ', ';

                }
            }
            else{
                $html = 'No Complaints Available';
            }
            return \response($html);

        } catch (\Exception $exception) {
            return \response(['error' => 'Something went wrong']);
            dd($exception);
        }
    }

//examination ko data
    public function getonExamination(Request $request)
    {
        try {
            $encounter_id = $request->get('encounter_id') ?? null;
            if (!$encounter_id || $encounter_id == null) {
                return false;
            }
            $patientexam = PatientExam::where('fldencounterval', $encounter_id)->where('fldinput', 'OPD Examination')->where('fldsave', 1)->get();
            $html = '';
            if ($patientexam->count() > 0) {

                foreach ($patientexam as $pat) {


                    $html .= $pat->fldhead . '-' . strip_tags($pat->fldrepquali) . ', ';

                }
            }else{
                $html = 'No Examination Available';
            }


//            $result_clinical_finding = json_decode($pexam->fldrepquali);
//            if (json_last_error() === JSON_ERROR_NONE) {
//                $oResult= "";
//                if (is_array($result_clinical_finding) || is_object($result_clinical_finding)){
//                    foreach ($result_clinical_finding as $key => $val) {
//                        $oResult .= $key . ': ' . $val . ', ';
//                    }
//                }
//            } else {
//                $oResult = $pexam->fldrepquali;
//            }
//
//            if($oResult != ''){
//                $observationResult=$oResult;
//            }else{
//                $observationResult=$pexam->fldrepquali;
//            }
            return \response($html);

        } catch (\Exception $exception) {
            return \response(['error' => 'Something went wrong']);
            dd($exception);
        }

    }

    //surgerical Note
    public function operationPerformed(Request $request)
    {
        try {
            $encounter_id = $request->get('encounter_id') ?? null;
            if (!$encounter_id || $encounter_id == null) {
                return false;
            }
            $operationPerformed = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali')->where([
                ['fldencounterval', $encounter_id],
                ['fldinput', 'Procedures'],
                ['fldreportquali', '=', 'Done'],
                ['fldstatus', 'Cleared']
            ])->get();
            $html = '';
            if ($operationPerformed->count() > 0) {

                foreach ($operationPerformed as $operation) {


                    $html .= $operation->flditem . ', ';

                }
            }else{
                $html = 'No Notes Available';
            }
            return \response($html);

        } catch (\Exception $exception) {
            return \response(['error' => 'Something went wrong']);
            dd($exception);
        }

    }

    //medicine ko data
    public function medicine(Request $request)
    {
        try {
            $encounter_id = $request->get('encounter_id') ?? null;
            if (!$encounter_id || $encounter_id == null) {
                return false;
            }
            $medicines = $data['currentData'] = Pathdosing::select('fldid', 'fldstarttime', 'fldroute', 'flditem', 'flditemtype', 'flddose', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
                ->where('fldencounterval', $encounter_id)
                ->where('fldsave_order', '1')
                ->where('flditemtype', 'Medicines')
                ->where('flddispmode', 'IPD')
                ->where('fldstarttime', '<=', Carbon::now()->toDateTimeString())
                ->get();
            $html = '';
            if ($medicines->count() > 0) {

                foreach ($medicines as $medicine) {


                    $html .= $medicine->flditem . '-Dose:' . $medicine->flddose . '-Freq:' . $medicine->fldfreq . '-Days:' . $medicine->flddays . ', ';

                }
            }else{
                $html = 'No Medicine Available';
            }
            return \response($html);


        } catch (\Exception $exception) {
            return \response(['error' => 'Something went wrong']);
            dd($exception);
        }

    }

// past history ko data fetch gareko
    public function pastHistory(Request $request)
    {

        try {
            $encounter_id = $request->get('encounter_id') ?? null;
            if (!$encounter_id || $encounter_id == null) {
                return false;
            }
            $get_history_detail = ExamGeneral::where([
                ['fldencounterval', $encounter_id],
                ['fldinput', 'History'],
                ['fldsave', 1],
                ['flditem', '=', 'Past History']
            ])->select('flddetail')->first();
            $html = '';
            if ($get_history_detail !='') {

//                foreach ($get_history_detail as $histry)
//                {
                $html .= $get_history_detail->flddetail . ', ';

//                }
            }
            else{
                $html = 'No History Available';
            }
//            dd($html);
            return \response($html);


        } catch (\Exception $exception) {

            return \response(['error' => 'Something went wrong']);
            dd($exception);
        }

    }

    public function getPatientProfile(Request $request)
    {
        try {
            // dd($request->all());
            $data = [];
            $data['patient_status_disabled'] = 0;
            if ($request->has('encounter_id'))
                $encounter_id = $request->get('encounter_id');
            $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();


            $data['enpatient'] = Encounter::where('fldencounterval', $encounter_id)->first();


            $data['enpatient'] = $enpatient;
//            $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();

            // dd($data);
            $patient_id = $enpatient->fldpatientval;
            $data = [
                'patient_status_disabled' => $enpatient->fldadmission == "Discharged" ? 1 : 0,
                'patient' => PatientInfo::where('fldpatientval', $patient_id)->first(),
                'enpatient' => $enpatient,
                'patient_id' => $patient_id,
                'billingset' => $billingset = BillingSet::get(),
                'enable_freetext' => Options::get('free_text'),
//                'enable_freetext' => 1,
//                'new_proc_refere' => CogentUsers::select('flduserid', 'username')->where('fldreferral', 1)->get(),
//                'new_proc_payable' => CogentUsers::select('flduserid', 'username')->where('fldpayable', 1)->get(),
//                'variables' => Procedure::all(),
//                'consultants' => CogentUsers::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get(),
//                'other_items' => PatBilling::where([
//                    'fldencounterval' => $encounter_id,
//                    'fldsave' => 0,
//                    'fldprint' => 0,
//                    'flditemtype' => 'Other Items'
//                ])->where('fldordcomp', 'like', '%' . Helpers::getCompName() . '%')
//                    ->where('fldstatus', '!=', 'Punched')
//                    ->get(),
//                'pat_findings' => $pat_findings = PatFindings::where([
//                    ['fldencounterval', $encounter_id],
//                    ['fldsave', 1],
//                ])->whereIn('fldtype', [
//                    'Provisional Diagnosis',
//                    'Final Diagnosis',
//                    'Allergic Drugs',
//                ])->select('fldcode', 'fldid', 'fldtype')->get(),
            ];
            $html = '';
            if($data['billingset']->count() > 0)
            {
                foreach ($data['billingset'] as $billing){
                    if($billing->fldsetname == $enpatient->fldbillingmode){
                        $selected = 'selected="selected"';
                    }else{
                        $selected = '';
                    } 
                    $html .= '<option value="' . $billing->fldsetname .'"'.$selected.'> ' . $billing->fldsetname . '</option>';
                }


            }
            $data['billing'] = $html;
            $patients = PatientInfo::where('fldpatientval', $patient_id)->first();
            $end = Carbon::parse($patients->fldptbirday);
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

            // dd($data['painmanagement']);
            if (isset($body_height) && isset($body_weight)) {
                $hei = ($body_height->fldrepquali / 100); //changing in meter
                $divide_bmi = ($hei * $hei);
                if ($divide_bmi > 0) {
                    $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                }
            }
             #Diagnosis
            $patFindingMultiple = PatFindings::where('fldencounterval', $encounter_id)
                ->where(function ($queryNested) {
                    $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                        ->orWhere('fldtype', 'Allergic Drugs');
                })
                ->where('fldsave', 1)
                ->get();
            $patdiago = PatFindings::where('fldencounterval', $encounter_id)
                ->where(function ($queryNested) {
                    $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                        ->orWhere('fldtype', 'Final Diagnosis');
                })
                ->where('fldsave', 1)
                ->get();



            // dd($patdiago);
            $diagnosishtml = '';
            if (isset($patdiago) and count($patdiago) > 0) {

                foreach ($patdiago as $diag) {

                    $diagnosishtml .= '<option value="'.$diag->fldid.'">'.$diag->fldcode.'</option>';
                }

            }else{
                $diagnosishtml = '';
            }
            $data['diagnosishtml'] = $diagnosishtml;
            #End Diagnosis
            $drughtml = '';
            $patdrug = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();

            if(isset($patdrug) and count($patdrug) > 0){
                foreach ($patdrug as $pd) {

                    $drughtml .= '<option value="'.$pd->fldid.'">'.$pd->fldcode.'</option>';
                }
            }else{
                $drughtml = '';
            }
            $data['drughtml'] = $drughtml;

            $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $encounter_id)->where('fldcode', '!=', null)->get();
            $allergicdrugs = Code::select('fldcodename')->whereNotIn('fldcodename', $patientallergicdrugs)->get();
            $allergicdrugshtml = '';
            if(isset($allergicdrugs) and count($allergicdrugs) > 0){
                foreach($allergicdrugs as $drug){
                    $allergicdrugshtml .= '<li class="list-group-item"><input type="checkbox" value="'.$drug->fldcodename.'" class="fldcodename" name="allergydrugs[]"/>&nbsp; '.$drug->fldcodename.'</li>';
                }
            }else{
                $allergicdrugshtml = '';
            }
            $data['allergicdrugshtml'] = $allergicdrugshtml;
            #complaints
            $complaints = ExamGeneral::where([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Presenting Symptoms',
                'fldsave' => '1',
            ])->get();
            $complaintshtml = '';
            if (count($complaints) > 0) {

                foreach ($complaints as $complaint) {

                    $complaintshtml .= $complaint->flditem . '-' . $complaint->fldreportquali . '-' . $complaint->fldreportquanti . 'hr' . ', ';
                }
            }
            else{
                $complaintshtml = '';
            }
            $data['complaintshtml'] = $complaintshtml;
            #End Complaints
            #On Examination
            $patientexam = PatientExam::where('fldencounterval', $encounter_id)->where('fldinput', 'OPD Examination')->where('fldsave', 1)->get();
            $onexaminationhtml = '';
            if (count($patientexam) > 0) {

                foreach ($patientexam as $pat) {


                    $onexaminationhtml .= $pat->fldhead . '-' . strip_tags($pat->fldrepquali) . ', ';

                }
            }else{
                $onexaminationhtml = '';
            }
            $data['onexaminationhtml'] = $onexaminationhtml;
            #End On Examination

            #Surgical Note
            $operationPerformed = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali')->where([
                ['fldencounterval', $encounter_id],
                ['fldinput', 'Procedures'],
                ['fldreportquali', '=', 'Done'],
                ['fldstatus', 'Cleared']
            ])->get();
            $surgicalnotehtml = '';
            if (count($operationPerformed) > 0) {

                foreach ($operationPerformed as $operation) {


                    $surgicalnotehtml .= $operation->flditem . ', ';

                }
            }else{
                $surgicalnotehtml = '';
            }
            $data['surgicalnotehtml'] = $surgicalnotehtml;
            #End Surgical Note

            #Medication
            $medicines = $data['currentData'] = Pathdosing::select('fldid', 'fldstarttime', 'fldroute', 'flditem', 'flditemtype', 'flddose', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
                ->where('fldencounterval', $encounter_id)
                ->where('fldsave_order', '1')
                ->where('flditemtype', 'Medicines')
                ->where('flddispmode', 'IPD')
                ->where('fldstarttime', '<=', Carbon::now()->toDateTimeString())
                ->get();
            $medicationhtml = '';
            if (count($medicines) > 0) {

                foreach ($medicines as $medicine) {


                    $medicationhtml .= $medicine->flditem . '-Dose:' . $medicine->flddose . '-Freq:' . $medicine->fldfreq . '-Days:' . $medicine->flddays . ', ';

                }
            }else{
                $medicationhtml = '';
            }
            $data['medicationhtml'] = $medicationhtml;
            #End Medication

            #Past History
            $get_history_detail = ExamGeneral::where([
                ['fldencounterval', $encounter_id],
                ['fldinput', 'History'],
                ['flditem', '=', 'Initial Planning']
            ])->select('flddetail')->first();
            $pasthistoryhtml = '';
            if ($get_history_detail !='') {

//                foreach ($get_history_detail as $histry)
//                {
                $pasthistoryhtml .= $get_history_detail->flddetail . ', ';

//                }
            }
            else{
                $pasthistoryhtml = '';
            }
            $data['pasthistoryhtml'] = $pasthistoryhtml;
            #End Past History

            #Operation Performed
            $operationPerformed = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali')->where([
                ['fldencounterval', $encounter_id],
                ['fldinput', 'Procedures'],
                ['fldreportquali', '=', 'Done'],
                ['fldstatus', 'Cleared']
            ])->get();
            $operationperformedhtml = '';
            if (count($operationPerformed) > 0) {

                foreach ($operationPerformed as $operation) {


                    $operationperformedhtml .= $operation->flditem . ', ';

                }
            }else{
                $operationperformedhtml = '';
            }
            $procedurehtml = '';
            $operationdatedata = Discharge::where('fldtype','Operation Date')->where('fldencounterval',$encounter_id)->first();
            if(isset($operationdatedata)){
                $operationdate = json_decode($operationdatedata->fldvalue);
            }else{
                $operationdate = array();
            }
            

            $operationproceduredata = Discharge::where('fldtype','Operative Procedures')->where('fldencounterval',$encounter_id)->first();
            if(isset($operationproceduredata)){
                $operationprocedures = json_decode($operationproceduredata->fldvalue);
            }else{
                $operationprocedures = array();
            }
            
            if(isset($operationdate) and !empty($operationdate)){
                foreach($operationdate as $opd){
                    $procedurehtml .='<div class="form-group"><label for="" class="label-bold">Date Of Operation:</label>';
                    $procedurehtml .='<input type="text" name="operation_date[]" class="form-control proc_date"  autocomplete="off" value="'.$opd.'">';
                    $procedurehtml .='</div>';
                }
                
            }

            if(isset($operationprocedures) and !empty($operationprocedures)){
                foreach($operationprocedures as $pro){
                    $procedurehtml .='<div class="form-group"><label for="" class="label-bold">Operative Procedure:</label>';
                    $procedurehtml .='<input type="text" name="operative_procedures[]" id="operative_procedures" class="form-control" value="'.$pro.'">';
                    $procedurehtml .='</div>';
                }
                
            }
            $data['procedurehtml'] = $procedurehtml;
            $data['operationperformedhtml'] = $operationperformedhtml;
            #End Operation PErformed
            $data['bed_number'] = \App\Utils\Helpers::getBedNumber($encounter_id);
            $data['patientdepartment'] = $enpatient->fldcurrlocat;

            $data['dischargedata'] = $this->_general_discharge_data($encounter_id);
            // dd($data);
            return \response($data);

        } catch (\Exception $e) {
                dd($e);
        }

    }

    public function resetEncounter()
    {
        Session::forget('discharge_encounter_id');
        return redirect()->route('discharge');
    }

    public function listLabTest(Request $request){

        try{
            // $labtest = PatLabTest::where('fldencounterval', $request->encounter_id)->whereNotIn('fldstatus',['Waiting','Sampled','Ordered'])->get();
            $labtest = DB::table('tblpatlabtest as pt')
                    // ->join('tbltestlimit as tl','tl.fldtestid','=','pt.fldtestid')
                    ->where('pt.fldencounterval',$request->encounter_id)
                    ->whereNotIn('fldstatus',['Waiting','Sampled','Ordered'])
                    ->groupBy('pt.fldid')
                    ->get();
            // dd($labtest);
            // dd($labtest);
            // echo $labtest; exit;
            $html = '';
            // if(isset($labtest) and count($labtest) > 0){
            //      $html .='<ul class="list-group" id="allergy-javascript-search">';
            //     foreach($labtest as $test){
            //         $html .='<li class="list-group-item"><label><input type="checkbox" class="test-list" name="tests[]" value="'.$test->fldid.'">'.'&nbsp;'.$test->fldtestid.' ('.$test->fldsiunit.')'.' ('.$test->fldtime_report.')</label></li>';
            //     }
            //     $html .='</ul>';
            // }else{
            //     $html .='<ul class="list-group" id="allergy-javascript-search"><li class="list-group-item">No Tests Available</li></ul>';
            // }

            if(isset($labtest) and count($labtest) > 0){

                foreach($labtest as $test){
                    $unit = DB::table('tbltestlimit')->select('fldsiunit')->where('fldtestid',$test->fldtestid)->first();
                    if(!is_null($unit) and $unit->fldsiunit !=''){
                        $labunit = $unit->fldsiunit;
                    }else{
                        $labunit = '';
                    }
                    $result = (isset($test->fldreportquali) and $test->fldreportquali !='') ? $test->fldreportquali : $test->fldreportquanti;
                    $html .='<tr>';
                    $html .='<td><input type="checkbox" class="test-list" name="tests[]" value="'.$test->fldid.'"></td>';
                    $html .='<td><label>'.$test->fldtestid.'</label></td>';
                    $html .='<td>'.$labunit.'</td>';
                    $html .='<td>'.$result.'</td>';
                    $html .='<td>'.$test->fldtime_report.'</td>';
                    $html .='</tr>';
                }

            }
            echo $html;
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function getLabDetails(Request $request){
        try{
            $labtests = $request->tests;
            // dd($labtests);
            $html = '';
            if(isset($labtests) and count($labtests) > 0){
                foreach($labtests as $tests){
                    $test = PatLabTest::where('fldid',$tests)->first();
                    if($test->fldreportquali == $test->fldreportquanti){
                        $value = $test->fldreportquali;
                    }else{
                        $value = $test->fldreportquali.','.$test->fldreportquanti;
                    }
                    $html .= $test->fldtestid.':'.$value.' | ';
                    // $subtest = PatLabSubTest::where('fldtestid',$tests)->get();
                    // $subtestvalue = array();
                    // if(isset($subtest) and count($subtest)){
                    //     foreach($subtest as $st){
                    //         $subtestvalue[] = $st->fldsubtest.':'.$test->fldreportquali;
                    //     }
                    //     $html .= $test->fldtestid.'='.(is_array($subtestvalue) ? implode(',', $subtestvalue)  : $subtestvalue).' | ';
                    // }else{
                    //     $html .= $test->fldtestid.':'.$value.' | ';
                    // }

                }
            }
            echo $html;
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function listRadioTest(Request $request){
        try{
            $radiotest = PatRadioTest::where('fldencounterval', $request->encounter_id)->whereNotIn('fldstatus',['Waiting','Sampled','Ordered'])->get();
            // dd($radiotest);
            $html = '';
            // if(isset($radiotest) and count($radiotest) > 0){
            //      $html .='<ul class="list-group" id="allergy-javascript-search">';
            //     foreach($radiotest as $test){
            //         $html .='<li class="list-group-item"><td><input type="checkbox" class="test-list" name="tests[]" value="'.$test->fldid.'">'.$test->fldtestid.'</td></li>';
            //     }
            //     $html .='</ul>';
            // }else{
            //     $html .='<ul class="list-group" id="allergy-javascript-search"><li class="list-group-item">No Tests Available</li></ul>';
            // }

            if(isset($radiotest) and count($radiotest) > 0){
                foreach($radiotest as $test){
                    $html .='<tr>';
                    $html .='<td><label><input type="checkbox" class="test-list" name="radiotests[]" value="'.$test->fldid.'">'.'&nbsp;'.$test->fldtestid.'</label></td>';
                    $html .='<td>'.$test->fldtime_report.'</td>';
                    $html .='</tr>';
                }

            }
            echo $html;
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function getRadioDetails(Request $request){
        try{
            $radiotests = $request->tests;
            // dd($radiotests);
            $html = '';
            if(isset($radiotests) and count($radiotests) > 0){
                foreach($radiotests as $tests){
                    $test = PatRadioTest::where('fldid',$tests)->first();
                    if($test->fldreportquali == $test->fldreportquanti){
                        $value = strip_tags($test->fldreportquali);
                    }else{
                        $value = strip_tags($test->fldreportquali).','.strip_tags($test->fldreportquanti);
                    }

                    $subtest = PatRadioSubTest::where('fldtestid',$tests)->get();
                    $subtestvalue = array();
                    if(isset($subtest) and count($subtest)){
                        foreach($subtest as $st){
                            $subtestvalue[] = $st->fldsubtest.':'.$st->fldreport;
                        }
                        $html .= $test->fldtestid.'='.explode(',', $subtestvalue).' | ';
                    }else{
                        $html .= $test->fldtestid.':'.$value.' | ';
                    }

                }
            }
            echo $html;
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function listDoctors(Request $request){
        // dd($request->all());
        try{
            $department = Department::select('fldid')->where('flddept',$request->department)->where('fldcateg','Patient Ward')->orWhere('fldcateg','Emergency')->first();
            $doctors = DB::table('department_users as du')
                    ->select(DB::raw("CONCAT(u.firstname,' ',u.lastname) as full_name"))
                    ->join('users as u','u.id','=','du.user_id')
                    ->where('du.department_id',$department)
                    ->where('u.fldopconsult','1')
                    ->where('u.fldipconsult','1')
                    ->get();

            $html = '';
            if(isset($doctors) and count($doctors) > 0){
                $html .='<ul class="list-group" id="allergy-javascript-search">';
                foreach($doctors as $r){
                    $html .='<li class="list-group-item"><label><input type="checkbox" name="doctors[]" value="'.$r->full_name.'">'.$r->full_name.'</label></li>';
                }
                $html .='</ul>';
            }
            echo $html;
        }catch(\Exception $e){
            dd($e);
        }
    }



    public function saveDischarge(Request $request){
        // dd($request->all());
        try{
            $encounter = $request->encounter_id;
            // dd($request ->all());
            $dsichargedata = Discharge::where('fldencounterval',$encounter)->delete();
            foreach($request->all() as $key=>$value){

                if ($key != 'encounter_id') {
                    // echo $value; exit;
                    if(!is_null($value)){
                        $formated_key = ucwords(str_replace('_', ' ', $key));
                        $data['fldtype'] = $formated_key;
                        if($formated_key == 'Operation Date' || $formated_key == 'Operative Procedures'){
                            $data['fldvalue']= json_encode($value);
                        }else{
                            $data['fldvalue'] = $value;
                        }
                        $data['fldencounterval'] = $encounter;

                         Discharge::create($data);
                    }
                }
            }

            #Insert the datas in all other tables to maintain record of the patient
                
            # ENd Of Insertion of the datas in all other tables to maintain record of the patient
            $encounterdata = Discharge::where('fldencounterval',$encounter)->get();
            if(isset($encounterdata) and count($encounterdata)){
                $currentLoc = Encounter::select('fldcurrlocat')
                ->where('fldencounterval', $encounter)
                ->first();

                $pattiming = PatTiming::where('fldencounterval', $encounter)
                    ->where('fldtype', 'General Services')
                    ->where('fldfirstreport', 'Bed')
                    ->where('fldfirstsave', 1)
                    ->where('fldsecondsave', 0)
                    ->get();

                if (count($pattiming)) {
                    $patData['fldsecondreport'] = $currentLoc->fldcurrlocat;
                    $patData['fldseconduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                    $patData['fldsecondtime'] = date("Y-m-d H:i:s");
                    $patData['fldsecondcomp'] = Helpers::getCompName();
                    $patData['fldsecondsave'] = 1;
                    $patData['xyz'] = 0;

                    PatTiming::where([
                        'fldtype' => 'General Services',
                        'fldfirstreport' => 'Bed',
                        'fldfirstsave' => 1,
                        'fldsecondsave' => 0,
                    ])->update($patData);
                    // \App\Departmentbed::where('fldencounterval', $fldencounterval)->update([
                    //     'fldencounterval' => NULL,
                    // ]);
                    // \App\Encounter::where('fldencounterval', $fldencounterval)->update([
                    //     'flddod' => date('Y-m-d H:i:s'),
                    // ]);
                }

                //Departmentbed::where('fldencounterval', $encounter)->update(['fldencounterval' => NULL]);

                $bedsoccu = Departmentbed::where('fldencounterval', $encounter)->get();
                if($bedsoccu){
                    foreach($bedsoccu as $b){
                         Departmentbed::where('fldbed', $b->fldbed)->update(['fldencounterval' => NULL]);
                    }
                }

                if(isset($encounterdata->discharge_date) and $encounterdata->discharge_date !=''){
                    $encounterData['flddod'] = $encounterdata->discharge_date;
                }else{
                    $encounterData['flddod'] = date("Y-m-d H:i:s");
                }
                
                
               // $encounterData['fldadmission'] = ($request->patient_status !='') ? $request->patient_status : 'Discharged';
                $encounterData['xyz'] = 0;
                $encounterData['fldcurrlocat'] = null;

                Encounter::where('fldencounterval', $encounter)->update($encounterData);
                /*new changes*/


                $data = array(
                    'fldencounterval' => $encounter,
                    'fldhead' => 'Discharged',
                    'fldcomment' => $request->patient_condition,
                    'flduserid' => Auth::guard('admin_frontend')->user()->flduserid, //admin
                    'fldtime' => now(), //'2020-02-23 11:13:27.709'
                    'fldcomp' => Helpers::getCompName(), //comp01
                    'fldsave' => 1,
                    'flduptime' => Null,
                    'xyz' => 0,
                );
                $latest_id = PatientDate::insertGetId($data);
            }
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function save(Request $request){
        try{
            $encounter = $request->encounter_id;
            // dd($request ->all());
            $dsichargedata = Discharge::where('fldencounterval',$encounter)->delete();
            foreach($request->all() as $key=>$value){

                if ($key != 'encounter_id') {
                    // echo $value; exit;
                    if(!is_null($value)){
                        $formated_key = ucwords(str_replace('_', ' ', $key));
                        $data['fldtype'] = $formated_key;
                        if($formated_key == 'Operation Date' || $formated_key == 'Operative Procedures'){
                            $data['fldvalue']= json_encode($value);
                        }else{
                            $data['fldvalue'] = $value;
                        }
                        
                        $data['fldencounterval'] = $encounter;

                         Discharge::create($data);
                    }
                }
                #Update Patient Status
                if($key == 'discharge_english_date' and $value !=''){
                     $encounterData['flddod'] = $value;
                }
                   // $encounterData['fldadmission'] = $request->patient_status;
                    $encounterData['xyz'] = 0;
                    $encounterData['fldcurrlocat'] = null;

                    Encounter::where('fldencounterval', $encounter)->update($encounterData);
                    /*new changes*/


                    
                #End Patient Status
                if($key == 'complaints' and $value !=''){
                    $complaindata['fldencounterval'] = $encounter;
                    $complaindata['fldinput'] = 'Presenting Symptoms';
                    $complaindata['fldsave'] = '1';
                    $complaindata['fldreportquali'] = $value;
                    ExamGeneral::create($complaindata);
                }
                $exmageneralarray = array(['course_in_hospital','special_instruction','diet','consult_note','advice']);
                if(in_array($key, $exmageneralarray) and $value !=''){
                    $courseData['fldencounterval'] = $encounter;
                    $courseData['fldinput'] = ucwords(str_replace('_', ' ', $key));
                    $courseData['fldsave'] = '1';
                    $courseData['flddetail'] = $value;
                    ExamGeneral::create($courseData);
                }
                if($key == 'past_history' and $value !=''){
                    $historydata['fldencounterval'] = $encounter;
                    $historydata['fldinput'] = 'History';
                    $historydata['flditem'] = 'Initial Planning';
                    $historydata['flddetail'] = $value;
                    ExamGeneral::create($historydata);
                }

                if($key == 'on_examination' and $value !=''){
                    $examinationdata['fldencounterval'] = $encounter;
                    $examinationdata['fldinput'] = 'OPD Examination';
                    $examinationdata['fldsave'] = '1';
                    $examinationdata['flddetail'] = $value;
                    PatientExam::create($examinationdata);
                }

                if($key == 'surgerical_note' and $value !=''){
                    $surgericaldata = array(
                        'flddetail' => $value,
                        'fldsave' => 1, //1
                        'flduptime' => now(),
                        'xyz' => 0,
                    );
                    $checkifexist = \App\PatGeneral::where([
                                        'fldencounterval' => $encounter
                                    ])->first();
                    if ($checkifexist == null) {
                        
                    }else{
                        $latest_id = $checkifexist->update($surgericaldata);
                    }
                    
                }
                
                
                
            }
            $data = array(
                    'fldencounterval' => $encounter,
                    'fldhead' => $request->patient_status,
                    'fldcomment' => '',
                    'flduserid' => Auth::guard('admin_frontend')->user()->flduserid, //admin
                    'fldtime' => now(), //'2020-02-23 11:13:27.709'
                    'fldcomp' => Helpers::getCompName(), //comp01
                    'fldsave' => 1,
                    'flduptime' => Null,
                    'xyz' => 0,
                );
            PatientDate::insertGetId($data);
            
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function dischargeCertificate(Request $request){
        try{
            // dd($request->all());
            $encounter_id = $request->encounter_id;
            $patdiago = PatFindings::where('fldencounterval', $encounter_id)
                ->where(function ($queryNested) {
                    $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                        ->orWhere('fldtype', 'Final Diagnosis');
                })
                ->where('fldsave', 1)
                ->get();
            $patdrug = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
            $data['diagnosis'] = $patdiago;
            $data['allergicdrugs'] = $patdrug;
            $data['encounter_id'] = $encounter_id;
            $data['result'] = $this->_general_discharge_data($encounter_id);
            // dd($data['result']);

            $data['user'] = Auth::guard('admin_frontend')->user()->firstname.' '.Auth::guard('admin_frontend')->user()->middlename.' '.Auth::guard('admin_frontend')->user()->lastname.' ('.Auth::guard('admin_frontend')->user()->fldcategory.')';
            $data['nmcnumber'] = Auth::guard('admin_frontend')->user()->nmc;

            return view('discharge::pdf.discharge-certificate', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/;
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function _general_discharge_data($encounter_id)
    {
        $tblgeneralData = \App\Discharge::where('fldencounterval', $encounter_id)
            ->get();

        $othergeneralData = [];
        foreach ($tblgeneralData as $key => $value) {
            $key = str_replace(' ', '_', $key);
            $othergeneralData[strtolower(str_replace(' ', '_', $value->fldtype))] = $value->fldvalue;
        }

        return compact('othergeneralData');
    }

    public  function deleteDiagnosis(Request  $request){
        // dd($request->all());
        if(!$request->ids){
            return \response()->json(['error' => 'Please select diagnosis to delete']);
        }
        try {
//                $finalids = implode(',', $request->ids);
            foreach($request->ids as $id){
                $datas = array(
                    'fldsave' => 0,
                    'xyz' => 0
                );
                PatFindings::where('fldid', $id)->update($datas);
            }


            $patdiagno = PatFindings::where('fldencounterval', $request->encounter)
                ->where(function ($queryNested) {
                    $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                        ->orWhere('fldtype', 'Final Diagnosis');
                })
                ->where('fldsave', 1)
                ->get();
            $html='';
            if (isset($patdiagno) and !empty($patdiagno)) {
                foreach ($patdiagno as $key => $pat) {
                    $html .= '<option value="' . $pat->fldid . '">' . $pat->fldcode . '</option>';

                }
            }
            return \response()->json(['message' => 'Deleted !','html' => $html]);
        } catch (\Exception $e) {
            dd($e);
            return \response()->json(['error' => 'Something Went Wrong']);
        }
    }
    public function getFinalDiagnosisfreetext(Request $request)
    {
        // echo $request->encounterId; exit;
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();

        $html = view('discharge::modal.freetext-diagnosis', $data)->render();
        return $html;
    }

    public function saveDiagnosisCustom(Request $request)
    {
        try {
            if ($request->custom_diagnosis != '') {
                $mytime = Carbon::now();
                $data['fldencounterval'] = $request->encounter;
                $data['fldtype'] = $request->fldinput;
                $data['fldcode'] = $request->custom_diagnosis;
                $data['fldcodeid'] = 'Other';
                $data['flduserid'] = Helpers::getCurrentUserName();
                $data['fldtime'] = $mytime->toDateTimeString();
                $data['fldcomp'] = Helpers::getCompName();
                $data['fldsave'] = 1;
                $data['xyz'] = 0;
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                PatFindings::insert($data);

                $html = '';
                $patdiagno = PatFindings::where('fldencounterval', $request->encounter)
                        ->where(function ($queryNested) {
                            $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                                ->orWhere('fldtype', 'Final Diagnosis');
                        })
                        ->where('fldsave', 1)
                        ->get();
                // dd($patdiagno); exit;
                if (isset($patdiagno) and !empty($patdiagno)) {
                    foreach ($patdiagno as $key => $pat) {
                        $html .= '<option value="' . $pat->fldid . '">' . $pat->fldcode . '</option>';

                    }
                }
                echo $html;
                exit;
            } else {
                session()->flash('error_message', __('Error While Adding Diagnosis'));

                return redirect()->route('discharge');

            }

        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Diagnosis'));

            return redirect()->route('discharge');
        }
    }

    public function getFinalObstetricData(Request $request)
    {
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();
        $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
        // dd($enpatient);
        $patient_id = $enpatient->fldpatientval;
        // echo $patient_id; exit;
        $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
        $totaldays = ExamGeneral::select('fldreportquanti')->where([
            ['fldencounterval', $encounter_id],
            ['fldinput', 'Obstetrics'],
            ['flditem', 'Gestation'],
            ['fldtype', 'Quantitative']
        ])->first();
        if (isset($totaldays->fldreportquanti) and $totaldays->fldreportquanti != '') {
            $totalweek = $totaldays->fldreportquanti / 7;
            if (is_float($totalweek)) {
                $exactweek = explode('.', $totalweek);
                $days = $totaldays->fldreportquanti - ($exactweek[0] * 7);
                $data['gestationweek'] = $exactweek[0];
                $data['gestationdays'] = $days;
            } else {
                $data['gestationweek'] = $totalweek;
                $data['gestationdays'] = 0;
            }
        }


        $data['gravida'] = ExamGeneral::select('fldreportquanti')->where([
            ['fldencounterval', $encounter_id],
            ['fldinput', 'Obstetrics'],
            ['flditem', 'Gravida'],
            ['fldtype', 'Quantitative']
        ])->first();
        $data['parity'] = ExamGeneral::select('fldreportquanti')->where([
            ['fldencounterval', $encounter_id],
            ['fldinput', 'Obstetrics'],
            ['flditem', 'Parity'],
            ['fldtype', 'Quantitative']
        ])->first();
        $data['abortion'] = ExamGeneral::select('fldreportquanti')->where([
            ['fldencounterval', $encounter_id],
            ['fldinput', 'Obstetrics'],
            ['flditem', 'Abortion'],
            ['fldtype', 'Quantitative']
        ])->first();
        $data['living'] = ExamGeneral::select('fldreportquanti')->where([
            ['fldencounterval', $encounter_id],
            ['fldinput', 'Obstetrics'],
            ['flditem', 'Living'],
            ['fldtype', 'Quantitative']
        ])->first();
        $data['presentation'] = ExamGeneral::select('fldreportquali')->where([
            ['fldencounterval', $encounter_id],
            ['fldinput', 'Obstetrics'],
            ['flditem', 'Presentation'],
            ['fldtype', 'Qualitative']
        ])->first();

        $data['labor_status'] = ExamGeneral::select('fldreportquali')->where([
            ['fldencounterval', $encounter_id],
            ['fldinput', 'Obstetrics'],
            ['flditem', 'Labor Status'],
            ['fldtype', 'Qualitative']
        ])->first();
        $data['past_pregnancy'] = ExamGeneral::select('fldreportquali')->where([
            ['fldencounterval', $encounter_id],
            ['fldinput', 'Obstetrics'],
            ['flditem', 'Past Pregnancy'],
            ['fldtype', 'Qualitative']
        ])->first();
        $data['lmp'] = ExamGeneral::select('fldreportquali')->where([
            ['fldencounterval', $encounter_id],
            ['fldinput', 'Obstetrics'],
            ['flditem', 'Last Menstrual Period'],
            ['fldtype', 'Qualitative']
        ])->first();
        $data['edd'] = ExamGeneral::select('fldreportquali')->where([
            ['fldencounterval', $encounter_id],
            ['fldinput', 'Obstetrics'],
            ['flditem', 'Expected Delivery Date'],
            ['fldtype', 'Qualitative']
        ])->first();
        $data['patfinding'] = PatFindings::select('fldid', 'fldcode')->where([
            ['fldtype', 'Final Diagnosis'],
            ['fldcodeid', 'Obstetrics'],
            ['fldcode', '!=', ''],
            ['fldencounterval', $encounter_id]
        ])->first();

        // dd($data);

        $html = view('discharge::modal.obstetric', $data)->render();
        return $html;
    }

    public function saveFinalObstetricRequest(Request $request)
    {
        // echo "here obstetric save function"; exit;
        try {

            $mytime = Carbon::now();
            $encounterId = $request->encounter;

            #For Quantitative Data
            $quandata = array('Gravida', 'Parity', 'Abortion', 'Living', 'Gestation');
            foreach ($quandata as $qd) {
                $qddata = ExamGeneral::select('fldid')->where([
                    ['fldencounterval', $encounterId],
                    ['fldinput', 'Obstetrics'],
                    ['fldtype', 'Quantitative'],
                    ['flditem', $qd]
                ])->first();
                $rvalue = strtolower($qd);
                if ($qd == 'Gestation') {
                    $totaldays = (7 * $request->gestationweek) + $request->gestationdays;
                    $quantitativeUpdatedata['fldreportquanti'] = $totaldays;
                    $quantitativeUpdatedata['fldreportquali'] = $totaldays;
                } else {
                    $quantitativeUpdatedata['fldreportquanti'] = $request->$rvalue;
                    $quantitativeUpdatedata['fldreportquali'] = $request->$rvalue;
                }
                $quantitativeUpdatedata['fldencounterval'] = $encounterId;
                $quantitativeUpdatedata['fldinput'] = 'Obstetrics';
                $quantitativeUpdatedata['fldtype'] = 'Quantitative';
                $quantitativeUpdatedata['flditem'] = $qd;
                $quantitativeUpdatedata['flddetail'] = NULL;
                $quantitativeUpdatedata['flduserid'] = Helpers::getCurrentUserName();


                $quantitativeUpdatedata['fldcomp'] = Helpers::getCompName();
                $quantitativeUpdatedata['fldsave'] = 1;
                $quantitativeUpdatedata['xyz'] = 0;
                if (isset($qddata) and $qddata != '') {
                    $quantitativeUpdatedata['flduptime'] = $mytime->toDateTimeString();
                    ExamGeneral::where('fldid', $qddata->fldid)->update($quantitativeUpdatedata);
                } else {
                    $quantitativeUpdatedata['fldtime'] = $mytime->toDateTimeString();
                    ExamGeneral::insert($quantitativeUpdatedata);
                }
            }

            #For Qualitative Data
            $qualidata = array('Last Menstrual Period', 'Expected Delivery Date', 'Presentation', 'Labor Status', 'Past Pregnancy');
            foreach ($qualidata as $qld) {
                $qldata = ExamGeneral::select('fldid')->where([
                    ['fldencounterval', $encounterId],
                    ['fldinput', 'Obstetrics'],
                    ['fldtype', 'Qualitative'],
                    ['flditem', $qld]
                ])->first();
                if ($qld == 'Last Menstrual Period') {
                    $rvalue = 'lmp_ad';
                } elseif ($qld == 'Expected Delivery Date') {
                    $rvalue = 'edd_ad';
                } elseif ($qld == 'Presentation') {
                    $rvalue = 'presentation';
                } elseif ($qld == 'Labor Status') {
                    $rvalue = 'labor_status';
                } elseif ($qld == 'Past Pregnancy') {
                    $rvalue = 'pastpreg';
                } else {
                    $rvalue = '';
                }

                if ($rvalue != '') {
                    $qualitativeUpdatedata['fldencounterval'] = $encounterId;
                    $qualitativeUpdatedata['fldinput'] = 'Obstetrics';
                    $qualitativeUpdatedata['fldtype'] = 'Qualitative';
                    $qualitativeUpdatedata['flditem'] = $qld;
                    $qualitativeUpdatedata['fldreportquanti'] = 0;
                    $qualitativeUpdatedata['fldreportquali'] = $request->$rvalue;
                    $qualitativeUpdatedata['flddetail'] = NULL;
                    $qualitativeUpdatedata['flduserid'] = Helpers::getCurrentUserName();
                    $qualitativeUpdatedata['fldtime'] = $mytime->toDateTimeString();

                    $qualitativeUpdatedata['fldcomp'] = Helpers::getCompName();
                    $qualitativeUpdatedata['fldsave'] = 1;
                    $qualitativeUpdatedata['xyz'] = 0;
                    if (isset($qldata) and $qldata != '') {
                        //update
                        $qualitativeUpdatedata['flduptime'] = $mytime->toDateTimeString();
                        ExamGeneral::where('fldid', $qldata->fldid)->update($qualitativeUpdatedata);
                    } else {
                        //insert
                        $qualitativeUpdatedata['fldtime'] = $mytime->toDateTimeString();
                        ExamGeneral::insert($qualitativeUpdatedata);
                    }
                }
            }

            # For Patfinding And Patient Exam
            if ($request->patfinding != 0) {
                #tblpatfindings ma insert garne

                $patData['fldencounterval'] = $request->encounter;
                $patData['fldtype'] = 'Final Diagnosis';
                $patData['fldcode'] = $request->obsdesc;
                $patData['fldcodeid'] = 'Obstetrics';
                $patData['flduserid'] = Helpers::getCurrentUserName(); //need check
                $patData['fldtime'] = $mytime->toDateTimeString();
                $patData['fldcomp'] = Helpers::getCompName();
                $patData['fldsave'] = 1;
                $patData['xyz'] = 0;

                PatFindings::where('fldid', $request->patfinding)->update($patData, ['timestamps' => false]);
            } else {
                #tblpatfindings ma insert garne
                $patData['fldencounterval'] = $request->encounter;
                $patData['fldtype'] = 'Final Diagnosis';
                $patData['fldcode'] = $request->obsdesc;
                $patData['fldcodeid'] = 'Obstetrics';
                $patData['flduserid'] = Helpers::getCurrentUserName(); //need check
                $patData['fldtime'] = $mytime->toDateTimeString();
                $patData['fldcomp'] = Helpers::getCompName();
                $patData['fldsave'] = 1;
                $patData['xyz'] = 0;
                PatFindings::insert($patData);

                #tblpatientexam ma insert garaune
                $patExam['fldencounterval'] = $request->encounter;
                $patExam['fldserialval'] = NULL;
                $patExam['fldinput'] = 'General Parameters';
                $patExam['fldtype'] = 'Qualitative';
                $patExam['fldhead'] = 'Pregnancy Status'; //need check
                $patExam['fldsysconst'] = NULL;
                $patExam['fldmethod'] = 'Regular';
                $patExam['fldrepquali'] = '3rd trimester';
                $patExam['fldrepquanti'] = 0;
                $patExam['fldfilepath'] = NULL;
                $patExam['flduserid'] = Helpers::getCurrentUserName();
                $patExam['fldtime'] = $mytime->toDateTimeString();
                $patExam['fldcomp'] = Helpers::getCompName();
                $patExam['fldsave'] = 1;
                $patExam['fldabnormal'] = 0;
                $patExam['flduptime'] = NULL;
                $patExam['xyz'] = 0;
                PatientExam::insert($patExam);
            }
            $patdiago = PatFindings::where('fldencounterval', $encounter_id)
                ->where(function ($queryNested) {
                    $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                        ->orWhere('fldtype', 'Final Diagnosis');
                })
                ->where('fldsave', 1)
                ->get();
            $html = '';
            if (isset($patdiago) and count($patdiago) > 0) {
                foreach ($patdiago as $key => $value) {
                    $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
                }
            }
            echo $html;
            exit;
        } catch (\GearmanException $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Obstetric Diagnosis'));
            return redirect()->route('discharge');
        }
    }

    function diagnosisStore(Request $request)
    {
        // dd($request->all());
        // echo "here store"; exit;
        try {
            $mytime = Carbon::now();
            $data['fldencounterval'] = $request->patient_id;
            $data['fldtype'] = 'Provisional Diagnosis';
            $data['fldcode'] = $request->diagnosissubname;
            $data['fldcodeid'] = $request->dccat;
            $data['flduserid'] = Helpers::getCurrentUserName();
            $data['fldtime'] = $mytime->toDateTimeString();
            $data['fldcomp'] = Helpers::getCompName();
            $data['fldsave'] = 1;
            $data['xyz'] = 0;
            $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
            $fldid = PatFindings::insertGetId($data);

            $patdiago = PatFindings::where('fldencounterval', $request->patient_id)
                ->where(function ($queryNested) {
                    $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                        ->orWhere('fldtype', 'Final Diagnosis');
                })
                ->where('fldsave', 1)
                ->get();
            $html = '';
            if(isset($patdiago) and count($patdiago) > 0){
                foreach ($patdiago as $key => $value) {
                    $html.='<option value="'.$value->fldid.'">'.$value->fldcode.'</option>';
                }
            }
            echo $html; exit;

        } catch (\Exception $e) {
           // dd($e);
            session()->flash('error_message', __('Error While Adding Diagnosis'));

            return redirect()->back();
        }
    }

    public function medicineRequest(Request $request)
    {
        if(!$request->encounterId || !$request->department){
            return  \response()->json('Please check encounter and department');
        }
        $request->validate([
            'encounterId' => 'required',
        ]);
        $data['extraOrder'] = ExamGeneral::where('fldencounterval', $request->encounterId)->get();
        $data['encounterId'] = $request->encounterId;
        $data['department'] = $request->department;
        $data['settings'] = Settings::where('fldindex', 'comp01:LowDeposit/Pharmacy')
            ->first();

        $data['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldadmission', 'fldcurrlocat', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldencrypt,fldptsex,fldptaddvill,fldptadddist,fldmidname,fldrank')
            ->first();

        $data['currentData'] = Pathdosing::select('fldid', 'fldstarttime', 'fldroute', 'flditem', 'flditemtype', 'flddose', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldsave_order', '1')
            ->where('flditemtype', 'Medicines')
            ->where('flddispmode', 'IPD')
            ->where('fldstarttime', '<=', Carbon::now()->toDateTimeString())
            ->get();


        $data['patFindings'] = PatFindings::select('fldcode as col')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldsave', '1')
            ->where(function ($query) {
                return $query
                    ->orWhere('fldtype', '=', 'Final Diagnosis')
                    ->orWhere('fldtype', '=', 'Provisional Diagnosis');
            })
            ->distinct()
            ->get();

        $encounter = Encounter::where('fldencounterval', $request->encounterId)->first();

        $data['newOrdersPathDosing'] = Pathdosing::select('fldid', 'fldstarttime', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'flduserid_order', 'fldid', 'fldid', 'fldcomment')
            ->where('fldencounterval', $request->encounterId)
            ->where('fldsave_order', 0)
            ->where('fldstatus', $encounter->fldadmission ?? "")
            ->where('fldorder', 'Request')
            ->where('fldcurval', 'Continue')
            ->get();

        //        $encounters = Encounter::where('fldpatientval', $data['encounterData']->patientInfo->fldpatientval)->pluck('fldencounterval');

        $data['newOrders'] = Drug::select('fldroute')->distinct()->orderby('fldroute', 'ASC')->get();
        $data['newOrdersSurgcat'] = Surgical::select('fldsurgcateg')->distinct()->orderby('fldsurgcateg', 'ASC')->get();

        if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
            $data['departments'] = $current_user->department->unique('flddept')->pluck('flddept')->toArray();
        } else {
            $data['departments'] = Department::pluck('flddept')->toArray();
        }

        //$data['macAddress'] = MacAccess::select('fldcompname')->where('fldcomp', Helpers::getCompName())->get();
        $html = view('discharge::modal.pharmacy-data-discharge', $data)->render();
        return \response()->json(['html' => $html,'department' => $data['department']]);
    }

    public function saveNewOrder(Request $request)
    {
        /*if (Helpers::checkIfDischarged($request->encounter)) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! patient already discharged');
            return false;
        }*/

        try {
            //            select tblmedbrand.fldpackvol as fldpackvol,tbldrug.fldstrength as fldstrength from tblmedbrand inner join tbldrug on tblmedbrand.flddrug=tbldrug.flddrug where tblmedbrand.fldbrandid=&1

            if ($request->pharnmacy_qty || $request->pharnmacy_qty != 0) {
                $quantity = $request->pharnmacy_qty;
            } else {
                $calculateStrengthVol = \DB::table('tblmedbrand')
                    ->select('tblmedbrand.fldpackvol as fldpackvol', 'tbldrug.fldstrength as fldstrength')
                    ->where('tblmedbrand.fldbrandid', $request->itemName)
                    ->join('tbldrug', 'tblmedbrand.flddrug', '=', 'tbldrug.flddrug')
                    ->first();

                switch ($request->pharnmacy_freq) {
                    case "PRN":
                        $frequency = 3;
                        break;
                    case "SOS":
                    case "stat":
                    case "AM":
                    case "HS":
                    case "Pre":
                    case "Post":
                    case "Tapering":
                        $frequency = 1;
                        break;
                    case "Hourly":
                        $frequency = 24;
                        break;
                    case "Alt day":
                        $frequency = 1 / 2;
                        break;
                    case "Weekly":
                        $frequency = 1 / 7;
                        break;
                    case "Biweekly":
                        $frequency = 1 / 14;
                        break;
                    case "Triweekly":
                        $frequency = 1 / 21;
                        break;
                    case "Monthly":
                        $frequency = 1 / 30;
                        break;
                    case "Yearly":
                        $frequency = 1 / 365;
                        break;
                    default:
                        $frequency = $request->pharnmacy_freq;
                        break;
                }

                $quantity = ($request->pharnmacy_dose * $frequency * $request->pharnmacy_day) / ($calculateStrengthVol->fldpackvol * $calculateStrengthVol->fldstrength);
            }

            $encounter = Encounter::where('fldencounterval', $request->encounter)->first();

            $data = [
                'fldencounterval' => $request->encounter,
                'flditemtype' => 'Medicines',
                'fldroute' => $request->route,
                'flditem' => $request->itemName,
                'flddose' => $request->pharnmacy_dose ?? 0,
                'fldfreq' => $request->pharnmacy_freq ?? 0,
                'flddays' => $request->pharnmacy_day ?? 0,
                'fldqtydisp' => ceil($quantity),
                'fldqtyret' => 0,
                'fldprescriber' => NULL,
                'fldregno' => NULL,
                'fldlevel' => 'Requested',
                'flddispmode' => 'IPD',
                'fldorder' => 'Request',
                'fldcurval' => 'Continue',
                'fldstarttime' => date("Y-m-d H:i:s"),
                'fldendtime' => NULL,
                'fldtaxper' => 0,
                'flddiscper' => 0,
                'flduserid_order' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'fldtime_order' => date("Y-m-d H:i:s"),
                'fldcomp_order' => $request->request_department_pharmacy,
                'fldsave_order' => '0',
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'fldtime' => NULL,
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => '1',
                'fldlabel' => '0',
                'fldstatus' => $encounter->fldadmission ?? "",
                'flduptime' => NULL,
                'xyz' => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            /*
             * if data is ortho or msurg reset data to match how the data is inserted
             * only quantity is inserted
             */
            if ($request->med_ortho_msurge == "Yes") {
                $data['flddose'] = 0;
                $data['fldfreq'] = 0;
                $data['flddays'] = 0;
                $data['fldqtydisp'] = $request->pharnmacy_qty;
            }

            Pathdosing::insert($data);
            $dataHtml['dosedata'] = Pathdosing::select('fldid', 'fldstarttime', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'flduserid_order', 'fldid', 'fldid', 'fldstarttime', 'fldcomment')
                ->where('fldencounterval', $request->encounter)
                ->where('fldsave_order', 0)
                ->where('fldstatus', $encounter->fldadmission ?? "")
                ->where('fldorder', 'Request')
                ->where('fldcurval', 'Continue')
                ->get();
                $item='';
                if($dataHtml['dosedata']){
                    foreach ($dataHtml['dosedata'] as $medicine){
                        $item .= $medicine->flditem . '-Dose:' . $medicine->flddose . '-Freq:' . $medicine->fldfreq . '-Days:' . $medicine->flddays . ', ';

                    }

                }
            $html = view('outpatient::dynamic-views.pharmacy-list-order', $dataHtml)->render();
//            return $html;
            return \response()->json(['html' => $html,'item' =>$item]);
        } catch (\GearmanException $e) {
            return $e;
        }
    }

    public function displayFollowupForm(Request $request)
    {
        // echo "here"; exit;
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();
        $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
        // dd($data['enpatient'])
        if(isset($enpatient->fldfollowdate) and !is_null($enpatient->fldfollowdate)){
            // echo "here0"; exit;
            $datetime = explode(' ', $enpatient->fldfollowdate);
            $date = Helpers::dateEngToNepdash($datetime[0]);
            $data['date'] = $date->full_date;
            // dd($data['date']);
            // $data['date'] = $datetime[0];
            $data['time'] = $datetime[1];
            // dd($data);
            $now = time(); // or your date as well
            $your_date = strtotime($datetime[0]);
            $datediff = $now - $your_date;

            $data['days'] = abs(round($datediff / (60 * 60 * 24)));
        }else{
            $date = Helpers::dateEngToNepdash(date('Y-m-d'));
            $data['date'] = $date->full_date;
            // $data['date'] = date('Y-m-d');
            // dd($data['date']);
        }



        $patient_id = $enpatient->fldpatientval;
        // echo $patient_id; exit;
        $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();

        // dd($data['currentdate']->full_date);

        $html = view('discharge::modal.followup-form', $data)->render();
        return $html;
    }
}



