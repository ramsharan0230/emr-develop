<?php


namespace Modules\Outpatient\Http\Controllers;

use App\Admissionrequest;
use App\BillingSet;
use App\Code;
use App\CogentUsers;
use App\Complaints;
use App\Consult;
use App\Departmentbed;
use App\DiagnoGroup;
use App\Encounter;
use App\Exam;
use App\PersonImage;
use App\ExamGeneral;
use App\ExamOption;
use App\Examlimit;
use App\HospitalDepartment;
use App\NurseDosing;
use App\PatFindings;
use App\Pathdosing;
use App\PatientExam;
use App\PatientInfo;
use App\Services\ImageUpload\Strategy\UploadWithAspectRatio;
use App\Test;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Utils\Permission;
use Carbon\Carbon;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Modules\Outpatient\Services\OutpatientImageUploader;
use PHPUnit\Exception;
use Session;
use Cache;
use App\PatGeneral;
use App\PatientDate;
use App\PatLabTest;
use App\PatPlanning;
use App\PatTiming;
use Illuminate\Support\Facades\Auth;


/**
 * Class OutpatientController
 * @package Modules\Outpatient\Http\Controllers
 */
class OutpatientController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (Permission::checkPermissionFrontendAdmin('outpatient-form')) {
            try {
                $data = array();
                $data['departments'] = DB::table('tbldepartment')
                ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
                ->where('tbldepartment.fldcateg', 'Patient Ward')
                ->select('tbldepartment.flddept')
                ->groupBy('tbldepartment.flddept')
                ->get();
                
                $data['laboratory'] = Cache::remember('test_list', 60 * 60 * 24, function () {
                    return $laboratory = Test::get();
                });
                $data['complaint'] = $complaint = Cache::remember('conplaints_list', 60 * 60 * 24, function () {
                    return Complaints::get();
                });
                // dd($data['complaint']);
                $data['finding'] = $finding = Cache::remember('exam_list', 60 * 60 * 24, function () {
                    return Exam::get();
                });
                $data['billingset'] = Cache::remember('billing_set', 60 * 60 * 24, function () {
                    return BillingSet::get();
                });
                $data['diagnosisgroup'] = Cache::remember('diagno_set', 60 * 60 * 24, function () {
                    return DiagnoGroup::select('fldgroupname')->distinct()->get();
                });
                $diagnocat = $this->getInitialDiagnosisCategory();
                $data['diagnosiscategory'] = $diagnocat;
                $encounter_id_session = Session::get('encounter_id');
                $data['patient_status_disabled'] = 0;
                if ($request->has('encounter_id') || $encounter_id_session) {
                    if ($request->has('encounter_id'))
                        $encounter_id = $request->get('encounter_id'); 
                    else
                        $encounter_id = $encounter_id_session;

                    
                    session(['encounter_id' => $encounter_id]);

                    /*create last encounter id*/
                    Helpers::encounterQueue($encounter_id);
                    /*$encounterIds = Options::get('last_encounter_id');

                    $arrayEncounter = unserialize($encounterIds);*/
                    /*create last encounter id*/
                    $dataflag = array(
                        'fldinside' => 1,
                    );

                    Encounter::where('fldencounterval', $encounter_id)->update($dataflag);
                    // echo  count(\Auth::guard('admin_frontend')->user()->user_is_superadmin); exit;
                    $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->with('patientInfo')->first();
                    /*$current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();*/

                    /*department change enabled*/
                    /*if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
                        if (!in_array($data['enpatient']->fldcurrlocat, $current_user->department->pluck('flddept')->toArray())) {
                            Session::flash('display_popup_error_success', true);
                            Session::flash('error_message', 'You are not authorized to view this patients information.');
                            Session::forget('encounter_id');
                            return redirect()->route('admin.dashboard');
                        }
                    }*/

                    $data['patient_status_disabled'] = $enpatient->fldadmission == "Discharged" ? 1 : 0;

                    //            dd($enpatient);
                    $data['enable_freetext'] = Options::get('free_text');
                    $data['patient_id'] = $patient_id = $enpatient->fldpatientval;
                    // echo $patient_id; exit;
                    $data['patient'] = $patient = $enpatient->patientInfo;

                    $data['plannedConsultants'] = Consult::where('fldencounterval', $encounter_id)->where('fldstatus', 'Planned')->get();

                    //select fldrepquanti from tblpatientexam where fldencounterval=$encounter_id and fldhead='Body Weight' and fldtype='Quantitative' and fldsave=$encounter_id
                    $data['patientexam'] = $patientexam_findings = PatientExam::where('fldencounterval', $encounter_id)->where('fldinput', 'OPD Examination')->where('fldsave', 1)->get();

                    $examGeneralMultiple = ExamGeneral::where('fldencounterval', $encounter_id)
                        ->where(function ($queryNested) {
                            $queryNested->orWhere('fldinput', 'History')
                                ->orWhere('fldinput', 'Notes')
                                ->orWhere('fldinput', 'Presenting Symptoms')
                                ->orWhere('flditem', 'Initial Planning');
                        })
                        ->orderBy('fldid', 'DESC')
                        ->get();

                    $data['history'] = $history = $examGeneralMultiple->where('fldinput', 'History')->where('flditem', 'Initial Planning')->where('fldencounterval', $encounter_id)->first();

                    $data['notes'] = $notes = $examGeneralMultiple->where('fldinput', 'Notes')->where('flditem', 'Initial Planning')->where('fldencounterval', $encounter_id)->first();
                    $data['sensitive'] = $notes = $examGeneralMultiple->where('fldinput', 'Sensitive Note')->where('fldencounterval', $encounter_id)->first();
                    $data['examgeneral'] = $examgeneral = $examGeneralMultiple->where('fldinput', 'Presenting Symptoms')->where('fldsave', 1)->where('fldencounterval', $encounter_id)->all();


                    $patFindingMultiple = PatFindings::where('fldencounterval', $encounter_id)
                        ->where(function ($queryNested) {
                            $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                                ->orWhere('fldtype', 'Allergic Drugs');
                        })
                        ->where('fldsave', 1)
                        ->get();
                    $data['patdiago'] = $patdiago = $patFindingMultiple->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->where('fldencounterval', $encounter_id)->all();
                    // dd($data['patdiago']);
                    $data['patdrug'] = $patdrug = $patFindingMultiple->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->where('fldencounterval', $encounter_id)->all();

                    // $patExamFldHead = DB::table('tblpatientexam')
                    //     ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                    //     ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                    //     ->where('tblpatientexam.fldencounterval', $encounter_id)
                    //     ->where('tblpatientexam.fldsave', 1)
                    //     ->orWhere('tblpatientexam.fldhead', 'Systolic BP')
                    //     ->orWhere('tblpatientexam.fldhead', 'Diastolic BP')
                    //     ->orWhere('tblpatientexam.fldhead', 'Pulse Rate')
                    //     ->orWhere('tblpatientexam.fldhead', 'Temperature (F)')
                    //     ->orWhere('tblpatientexam.fldhead', 'Respiratory Rate')
                    //     ->orWhere('tblpatientexam.fldhead', 'O2 Saturation')
                    //     ->orderBy('tblpatientexam.fldid', 'desc')
                    //     ->get();

                    $data['systolic_bp'] = $systolic_bp = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Systolic BP')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();


                    $data['diasioli_bp'] = $diasioli_bp = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Diastolic BP')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();

                    $data['pulse'] = $pulse = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Pulse Rate')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();

                    $data['temperature'] = $temperature = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Temperature (F)')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();

                    $data['respiratory_rate'] = $respiratory_rate = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Respiratory Rate')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();


                    $data['o2_saturation'] = $o2_saturation = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'O2 Saturation')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();

                    $examMultipel = Exam::where(function ($queryNested) {
                        $queryNested->orWhere('fldsysconst', 'BloodPressure_Diastolic')
                            ->orWhere('fldsysconst', 'Pulse_Rate')
                            ->orWhere('fldsysconst', 'Temperature_System')
                            ->orWhere('fldsysconst', 'Respiration_Rate')
                            ->orWhere('fldsysconst', 'Oxygen_Saturation')
                            ->orWhere('fldsysconst', 'BloodPressure_Systolic');
                    })
                        ->get();
                    $data['exam_systolic'] = $exam_systolic = $examMultipel->where('fldsysconst', 'BloodPressure_Systolic')->first();
                    //$data['systolic_bp'] = $systolic_bp = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldhead', 'Systolic BP')->orderBy('fldid', 'desc')->first();

                    $data['exam_diastolic'] = $exam_diastolic = $examMultipel->where('fldsysconst', 'BloodPressure_Diastolic')->first();
                    // $data['diasioli_bp'] = $diasioli_bp = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldhead', 'Diastolic BP')->orderBy('fldid', 'desc')->first();

                    $data['exam_pulse'] = $exam_pulse = $examMultipel->where('fldsysconst', 'Pulse_Rate')->first();
                    // $data['pulse'] = $pulse = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldhead', 'Pulse Rate')->orderBy('fldid', 'desc')->first();

                    $data['exam_temperature'] = $exam_temperature = $examMultipel->where('fldsysconst', 'Temperature_System')->first();
                    //$data['temperature'] = $temperature = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldhead', 'Temperature (F)')->orderBy('fldid', 'desc')->first();

                    $data['exam_respiratory_rate'] = $exam_respiratory_rate = $examMultipel->where('fldsysconst', 'Respiration_Rate')->first();
                    //$data['respiratory_rate'] = $respiratory_rate = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldhead', 'Respiratory Rate')->orderBy('fldid', 'desc')->first();

                    $data['exam_o2_saturation'] = $exam_o2_saturation = $examMultipel->where('fldsysconst', 'Oxygen_Saturation')->first();
                    //$data['o2_saturation'] = $o2_saturation = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldhead', 'O2 Saturation')->orderBy('fldid', 'desc')->first();

                    $data['consults'] = $consult = Consult::where('fldencounterval', $encounter_id)->first();

                    $examLimitMultiple = Examlimit::where(function ($queryNested) {
                        $queryNested->orWhere('fldexamid', 'Systolic BP')
                            ->orWhere('fldexamid', 'Diastolic BP')
                            ->orWhere('fldexamid', 'Respiratory Rate')
                            ->orWhere('fldexamid', 'O2 Saturation')
                            ->orWhere('fldexamid', 'Temperature(F)');
                    })
                        ->get();
                    $data['examlimit_sys_bp'] = $examLimitMultiple->where('fldexamid', 'Systolic BP')->first();
                    $data['examlimit_dia_bp'] = $examLimitMultiple->where('fldexamid', 'Diastolic BP')->first();
                    $data['examlimit_respi'] = $examLimitMultiple->where('fldexamid', 'Respiratory Rate')->first();
                    $data['examlimit_saturation'] = $examLimitMultiple->where('fldexamid', 'O2 Saturation')->first();
                    $data['pulse_rate_rate'] = $examLimitMultiple->where('fldexamid', 'Temperature(F)')->first();

                    $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $encounter_id)->where('fldcode', '!=', null)->get();

                    $data['allergicdrugs'] = Code::select('fldcodename')->whereNotIn('fldcodename', $patientallergicdrugs)->get();


                    $data['consultants'] = CogentUsers::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();
                    if ($patient) {
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
                    }

                    $heightWeight = PatientExam::where('fldencounterval', $encounter_id)
                        ->where('fldsave', 1)
                        ->where(function ($queryNested) {
                            $queryNested->orWhere('fldsysconst', 'body_Weight')
                                ->orWhere('fldsysconst', 'body_height');
                        })
                        ->orderBy('fldid', 'desc')
                        ->get();

                    if ($heightWeight) {
                        $data['body_weight'] = $body_weight = $heightWeight->where('fldsysconst', 'body_weight')->first();
                        $data['body_height'] = $body_height = $heightWeight->where('fldsysconst', 'body_height')->first();
                    } else {
                        $data['body_weight'] = "";
                        // dd($body_weight);
                        $data['body_height'] = "";
                    }


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
                        $divide_bmi = $hei * $hei;

                        if ($divide_bmi > 0) {
                            $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                            //                            dd($body_height);
                        }
                    }

                    $data['fluid_list'] = Pathdosing::where([
                        ['fldencounterval', $encounter_id],
                        ['fldroute', 'fluid'],
                        ['fldlevel', 'Dispensed']
                    ])
                        ->Where([
                            ['fldcurval', '!=', 'DisContinue'],
                            ['fldcurval', '!=', 'Cancelled']
                        ])
                        //                        ->Where(
                        //                            [
                        //                                ['fldstarttime','<', config('constants.current_date_time')],
                        //                                ['fldendtime','>',config('constants.current_date_time')]
                        //                            ]
                        //                        )
                        ->get();

                    $data['fluid_particulars'] = NurseDosing::with('getName')->where([
                        ['fldencounterval', $encounter_id],
                        ['fldunit', 'ML/Hour'],
                    ])->get();


                    /*$presentation = ExamGeneral::select('fldreportquanti')->where([
                        ['fldencounterval', $encounter_id],
                        ['fldinput', 'Obstetrics'],
                        ['flditem', 'Presentaion'],
                        ['fldtype', 'Qualitative']
                    ])->get();*/
                    // dd($presentation);
                    $patExamMultiple = PatientExam::where('fldencounterval', $encounter_id)
                        ->where(function ($queryNested) {
                            $queryNested->orWhere('fldhead', 'Pallor')
                                ->orWhere('fldhead', 'Icterus')
                                ->orWhere('fldhead', 'Cyanosis')
                                ->orWhere('fldhead', 'Clubbing')
                                ->orWhere('fldhead', 'Oedema')
                                ->orWhere('fldhead', 'Dehydration');
                        })
                        ->orderBy('fldid', 'DESC')
                        ->get();
                    $data['Pallor'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Pallor')->first();
                    $data['Icterus'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Icterus')->first();
                    $data['Cyanosis'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Cyanosis')->first();
                    $data['Clubbing'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Clubbing')->first();
                    $data['Oedema'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Oedema')->first();
                    $data['Dehydration'] = $patExamMultiple->where('fldencounterval', $encounter_id)->where('fldhead', 'Dehydration')->first();

                    $data['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();
                    $tab = $request->get('tab');
                    $data['tab'] = (isset($tab)) ? $tab : '';

                    $data['systolic_bp_range'] = DB::table('tblexamlimit')
                    ->select('fldhigh','fldlow')
                    ->where('fldexamid', 'Systolic BP')
                    ->first();


                    $data['diasioli_bp_range'] =  DB::table('tblexamlimit')
                    ->select('fldhigh','fldlow')
                    ->where('fldexamid',  'Diastolic BP')
                    ->first();

                    $data['pulse_range'] =   DB::table('tblexamlimit')

                    ->select('fldhigh','fldlow')
                    ->where('fldexamid', 'Pulse Rate')
                    ->first();

                    $data['temperature_range'] =  DB::table('tblexamlimit')
                    ->select('fldhigh','fldlow')
                    ->where('fldexamid', 'Temperature (F)')
                    ->first();

                    $data['respiratory_rate_range'] =  DB::table('tblexamlimit')
                    ->select('fldhigh','fldlow')
                    ->where('fldexamid', 'Respiratory Rate')
                    ->first();

                    $data['o2_saturation_range'] =  DB::table('tblexamlimit')
                    ->select('fldhigh','fldlow')
                    ->where('fldexamid', 'O2 Saturation')
                    ->first();

                    $data['grbs_range'] =  DB::table('tblexamlimit')
                    ->select('fldhigh','fldlow')
                    ->where('fldexamid', 'GRBS')
                    ->first();
                    return view('outpatient::outpatient_form', $data);
                }
                return view('outpatient::outpatient_form', $data);
            } catch (\GearmanException $e) {
            }
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }

    public function showOutpatientPreview($encounterId){
        $information['encounterId'] = $encounter_id = $encounterId;

        $information['certificate'] = 'OPD';

        $information['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate','fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();

        if(!Options::get('opd_pdf_options')){
            Helpers::contentsForOPDPDF();
        }

        $options      = unserialize(Options::get('opd_pdf_options'));
        $optionsArray = array_filter($options, function ($key) {
            return strpos($key, 'content_') === 0;
        }, ARRAY_FILTER_USE_KEY);

        $arrayKeyValue = array_values($optionsArray);

        foreach ($arrayKeyValue as $item) {
            if ($item == "Course of Treatment") {
                $information['CourseofTreatment'] = PatientDate::select('fldhead', 'fldtime', 'fldcomment')
                    ->where('fldencounterval', $encounter_id)
                    ->get();
            }
            if ($item == "Advice on Discharge") {
                $information['AdviceOfDischargeExamGeneral'] = ExamGeneral::select('flddetail', 'fldtime')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldinput', 'Notes')
                    ->where('flditem', 'Advice on Discharge')
                    ->get();
            }
            if ($item == "Bed Transitions") {
                $information['bed'] = PatTiming::select('fldid', 'flditem', 'fldfirsttime', 'fldsecondtime', 'fldsecondreport')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldtype', 'General Services')
                    ->where('fldfirstreport', 'Bed')
                    ->get();
            }

            if ($item == "Cause of Admission") {
                $information['cause_of_admission'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Cause of Admission'])
                    ->get();
            }
            if ($item == "Clinical Notes") {
                $information['generalExamProgressCliniciansNurses'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                    ->where('fldencounterval', $encounter_id)
                    ->where(function ($query) {
                        return $query
                            ->orWhere('flditem', '=', 'Progress Note')
                            ->orWhere('flditem', '=', 'Clinicians Note')
                            ->orWhere('flditem', '=', 'Nurses Note');
                    })
                    ->get();
            }
            if ($item == "Condition at Discharge") {
                $information['ConditionOfDischargeExamGeneral'] = ExamGeneral::select('flddetail', 'fldtime')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldinput', 'Notes')
                    ->where('flditem', 'Condition of Discharge')
                    ->get();
            }
            if ($item == "Consultations") {
                $information['Consultations'] = Consult::select('fldconsultname', 'fldconsulttime', 'fldstatus')
                    ->where('fldencounterval', $encounter_id)
                    ->get();
            }
            if ($item == "Demographics") {
                $information['demographics'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldinput', 'Demographics')
                    ->get();
            }
            if ($item == "Discharge examinations") {
                $information['DischargeExaminationspatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldsave', '1')
                    ->where('fldinput', 'Discharge examinations')
                    ->get();
            }
            if ($item == "Drug Allergy") {
                $information['allergy_drugs'] = PatFindings::select('fldcode', 'fldcodeid')
                    ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Allergic Drugs', 'fldsave' => '1'])
                    ->get();
            }
            if ($item == "Equipments Used") {
                $information['equipment'] = PatTiming::select('flditem', 'fldfirsttime', 'fldsecondtime', 'fldsecondreport')
                    ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Equipment'])
                    ->get();
            }
            if ($item == "Essential examinations") {
                $patientExam = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldinput', 'OPD Examination')
                    ->where('fldsave', '1')
                    ->get();

                $opdData = [];
                foreach ($patientExam as $opdExam) {
                    $opdData['fldid'][]        = $opdExam->fldid;
                    $opdData['fldtime'][]      = $opdExam->fldtime;
                    $opdData['fldhead'][]      = $opdExam->fldhead;
                    $opdData['fldrepquali'][]  = json_decode($opdExam->fldrepquali, true);
                    $opdData['fldrepquanti'][] = $opdExam->fldrepquanti;
                    $opdData['fldtype '][]     = $opdExam->fldtype;
                }
                $data['EssentialExaminations'] = $opdData;
            }

            if ($item == "Extra Procedures") {
                $information['planned'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Extra Procedures', 'fldreportquali' => 'Planned'])
                    ->get();
            }
            if ($item == "Final Diagnosis") {
                $information['final_diagnosis'] = PatFindings::select('fldcode', 'fldcodeid')
                    ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Final Diagnosis', 'fldsave' => '1'])
                    ->get();
            }
            if ($item == "IP Monitoring") {
                $information['IPMonitoringPatPlanning'] = PatPlanning::select('fldid', 'fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldplancategory', 'IP Monitoring')
                    ->get();
            }
            if ($item == "Initial Planning") {
                $information['initial_planning'] = ExamGeneral::select('fldinput','flddetail', 'fldtime')
                    ->where(['fldencounterval' => $encounter_id])
                    ->whereIn('flditem',['History of Illness','Initial Planning'])
                    ->get();


            }
            if ($item == "Laboratory Tests") {
                $information['reportedPatLab'] = PatLabTest::where('fldencounterval', $encounter_id)
                    ->where(function ($query) {
                        return $query
                            ->orWhere('fldstatus', '=', 'Reported')
                            ->orWhere('fldstatus', '=', 'Verified');
                    })
                    ->with(['patTestResults', 'subTest', 'testLimit'])
                    ->get();
            }
            if ($item == "Major Procedures") {
                $information['procedures'] = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Procedures', 'fldreportquali' => 'Done'])
                    ->get();
            }
            if ($item == "Medication History") {
                $information['medicated_history'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Medication History'])
                    ->get();
            }
            if ($item == "Medication Used") {
                $information['MedicationUsed'] = $mainDataForPatDosing = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'flditemtype', 'fldlevel')
                    ->where('fldencounterval', $encounter_id)
                    ->where('flditemtype', 'Medicines')
                    ->where(function ($query) {
                        return $query
                            ->orWhere('fldstatus', '=', 'Registered')
                            ->orWhere('fldstatus', '=', 'Admitted')
                            ->orWhere('fldstatus', '=', 'Recorded');
                    })
                    ->get();
            }
            if ($item == "Minor Procedures") {
                $information['minor_procedure'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Minor Procedures', 'fldreportquali' => 'Done'])
                    ->get();
            }
            if ($item == "Occupational History") {
                $information['occupational_history'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Occupational History'])
                    ->get();
            }
            if ($item == "Personal History") {
                $information['personal_history'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Personal History'])
                    ->get();
            }
            if ($item == "Planned Procedures") {
                $information['planned'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Extra Procedures', 'fldreportquali' => 'Planned'])
                    ->get();
            }
            if ($item == "Prominent Symptoms") {
                $information['present_symptoms'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'fldreportquanti', 'flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Patient Symptoms', 'fldsave' => '1'])
                    ->get();
            }
            if ($item == "Provisional Diagnosis") {
                $information['provisinal_diagnosis'] = PatFindings::select('fldcode', 'fldcodeid')
                    ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Provisional Diagnosis', 'fldsave' => '1'])
                    ->get();
            }
            if ($item == "Social History") {
                $information['social_history'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Social History'])
                    ->get();
            }
            if ($item == "Surgical History") {
                $information['surgical_history'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Surgical History'])
                    ->get();
            }
            if ($item == "Triage examinations") {
                $information['triage_examinations'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                    ->where(['fldencounterval' => $encounter_id, 'fldsave' => '1', 'fldinput' => 'Triage examinations'])
                    ->get();
            }
        }     
        return view('outpatient::outpatient_preview',$information);
    }

    /* show data when click on finish button */
    public function showFinishData(Request $request){
        $data['plannedConsultants'] = Helpers::getPlannedConsultants($request->url_segment_first);
        return view('outpatient::modal.finish-show-data-boxLabel-modal',$data);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save_height(Request $request)
    {
        $height = round($request->get('height'));
        $encounter_id = $request->get('encounter_id');


        $data = array(
            'fldencounterval' => $encounter_id,
            'fldrepquali' => $height,
            'fldinput' => 'OPD Examination',
            'fldtype' => 'Quantitative',
            'fldhead' => 'Birth Height',
            'fldmethod' => 'Regular',
            'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => 1,
            'fldsysconst' => 'body_height',
            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
        );

        $patient = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->first();

        if ($patient)
            PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->update($data);
        else {
            $patient_n = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 0)->where('fldsysconst', 'body_height')->first();
            if ($patient_n)
                PatientExam::where('fldencounterval', $encounter_id)->where('fldsysconst', 'body_height')->update($data);
            else
                PatientExam::insert($data);
        }

        $body_weight = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'Body_Weight')->orderBy('fldid', 'desc')->first();
        $body_height = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->orderBy('fldid', 'desc')->first();

        $bmi = '';

        if (isset($body_height) && isset($body_weight)) {
            $hei = ($body_height->fldrepquali / 100); //changing in meter
            $divide_bmi = $hei * $hei;
            if ($divide_bmi > 0) {

                $bmi = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
            }
        }

        return response()->json([
            'success' => [
                'options' => $height,

                'bmi' => ($bmi),
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function planned_consultant(Request $request)
    {

        $consult = Consult::where('fldid', $request->fldid)->first();
        if (!empty($consult)) {
            $data = array(
                'fldcomment' => NULL,
                'fldstatus' => 'Done',
                'fldsave' => 1,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'xyz' => '0'
            );
            Consult::where('fldid', $request->fldid)->update($data);


            $dataflag = array(

                'fldinside' => 0,


            );

            Encounter::where('fldencounterval', $consult->fldencounterval)->update($dataflag);


            return response()->json([
                'success' => [
                    'msg' => 'saved',
                ]
            ]);
        } else {
            return response()->json([
                'error' => [
                    'msg' => 'unsaved',
                ]
            ]);
        }
    }


    public function close_finish(Request $request)
    {

        $fldencounterval = $request->encounter_id;
        $req_segment = request()->headers->get('referer');
        $req_segment = explode('/', $req_segment);
        $req_segment = $req_segment[count($req_segment) - 1];
        $dataflag = array(
            'fldinside' => 0,
        );

        if ($req_segment == 'patient') {
            $route = route('reset.encounter');
        }

        if ($req_segment == 'inpatient') {
            $route = route('reset.inpatient.encounter');
        }

        if ($req_segment == 'majorprocedure') {
            $route = route('major.reset.encounter');
        }

        if ($req_segment == 'eye') {
            $route = route('eye.reset.encounter');
        }

        if ($req_segment == 'dental') {
            $route = route('dental.reset.encounter');
        }

        if ($req_segment == 'emergency') {
            $route = route('emergency.reset.encounter');
        }

        if ($req_segment == 'delivery') {
            $route = route('delivery.reset.encounter');
        }

        if ($req_segment == 'physiotherapy') {
            $route = route('physiotherapy.reset.encounter');
        }

        Encounter::where('fldencounterval', $fldencounterval)->update($dataflag);


        return response()->json([
            'success' => [
                'msg' => 'saved',
                'redirectto' => $route

            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgeurl(Request $request)
    {
        //$patient->
        $age = \Carbon\Carbon::parse($request->date)->age;
        $encounter_id = $request->encounter_id;
        // echo $request->date; exit;
        $data = array(
            'fldptbirday' => $request->date,
            'flduptime' => now(), //'2020-02-23 11:13:27.709'

        );

        $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
        // dd($enpatient);
        $patient_id = $enpatient->fldpatientval;


        PatientInfo::where('fldpatientval', $patient_id)->update($data);

        return response()->json([
            'success' => [
                'age' => $age,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save_weight(Request $request)
    {


        $weight = round($request->get('weight'));
        $encounter_id = $request->get('encounter_id');

        //         if (Helpers::checkIfDischarged($encounter_id)) {
        //             Session::flash('display_popup_error_success', true);
        //             Session::flash('error_message', 'Sorry! patient already discharged');
        // //            return redirect()->back();
        //             return false;
        //         }

        $data = array(
            'fldencounterval' => $encounter_id,
            'fldrepquali' => $weight,
            'fldinput' => 'OPD Examination',
            'fldtype' => 'Quantitative',
            'fldhead' => 'Birth Weight',
            'fldmethod' => 'Regular',
            'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => 1,
            'fldsysconst' => 'body_weight',
            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
        );
        $patient = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_weight')->first();

        if ($patient)
            PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_weight')->update($data);
        else {
            $patient_n = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 0)->where('fldsysconst', 'body_weight')->first();
            if ($patient_n)
                PatientExam::where('fldencounterval', $encounter_id)->where('fldsysconst', 'body_weight')->update($data);
            else
                PatientExam::insert($data);
        }


        $body_weight = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'Body_Weight')->orderBy('fldid', 'desc')->first();
        $body_height = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->orderBy('fldid', 'desc')->first();

        $bmi = '';

        if (isset($body_height) && isset($body_weight)) {
            $hei = ($body_height->fldrepquali / 100); //changing in meter
            $divide_bmi = $hei * $hei;
            if ($divide_bmi > 0) {

                $bmi = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
            }
        }

        return response()->json([
            'success' => [
                'options' => $weight,

                'bmi' => round($bmi, 2),
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save_consultant(Request $request)
    {
        $user_consult = $request->get('user_consult');
        $encounter_id = $request->get('encounter_id');

        $data = array(
            'flduserid' => $user_consult,
        );

        Encounter::where([['fldencounterval', $encounter_id]])->update($data);


        return response()->json([
            'success' => [
                'options' => $user_consult,
            ]
        ]);
    }

    public function save_refer_by(Request $request)
    {
        $user_refer_by = $request->get('user_consult');
        $encounter_id = $request->get('encounter_id');

        $data = array(
            'fldreferfrom' => $user_refer_by,
        );

        Encounter::where([['fldencounterval', $encounter_id]])->update($data);


        return response()->json([
            'success' => [
                'options' => $user_refer_by,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changebirthday(Request $request)
    {
        $end = Carbon::parse($request->get('birthday'));
        $now = Carbon::now();

        $length = $end->diffInDays($now);

        if ($length < 1) {

            $data['years'] = 'Hours';
            $data['hours'] = $end->diffInHours($now);
        }


        if ($length > 0 && $length <= 30)
            $years = 'Days';

        if ($length > 30 && $length <= 365)
            $years = 'Months';

        if ($length > 365)
            $years = 'Years';

        return response()->json([
            'success' => [
                'options' => $years,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_encounter_number(Request $request)
    {
        // echo "sedfsdfds"; exit;
        $patient_id = $request->get('patient_id');
        if (ctype_alpha(str_replace(' ', '', $patient_id)) === false) {
            //'Name must contain letters and spaces only';
            $encounters = Encounter::select('fldencounterval')->where('fldpatientval', $patient_id)->orderBy('fldregdate', 'DESC')->get()->toArray();


            $html = '<select name="encounter_id" class="form-control">';
            if (!empty($encounters)) {
                foreach ($encounters as $en) {
                    $html .= '<option value="' . $en['fldencounterval'] . '"> ' . $en['fldencounterval'] . '</option>';
                }
            }
            $html .= '</select>';
        } else {
            $patientname = $patient_id . '%';
            $encounters = DB::table('tblencounter')
                ->join('tblpatientinfo', 'tblencounter.fldpatientval', '=', 'tblpatientinfo.fldpatientval')
                ->where('tblpatientinfo.fldptnamefir', 'LIKE', $patientname)
                ->orwhere('tblpatientinfo.fldptnamelast', 'LIKE', $patientname)
                ->select('tblencounter.fldencounterval', 'tblencounter.fldrank', 'tblpatientinfo.*')
                ->orderBy('fldregdate', 'DESC')->get()->toArray();
            // dd($encounters);


            $html = '<select name="encounter_id" class="form-control">';
            if (!empty($encounters)) {
                foreach ($encounters as $en) {
                    $user_rank = ((Options::get('system_patient_rank') == 1) && isset($en) && isset($en->fldrank)) ? $en->fldrank : '';
                    $html .= '<option value="' . $en->fldencounterval . '"> ' . $user_rank . ' ' . $en->fldptnamefir . ' ' . $en->fldmidname . ' ' . $en->fldptnamelast . ' (' . $en->fldencounterval . ')</option>';
                }
            }
            $html .= '</select>';
        }


        return response()->json([
            'success' => [
                'options' => $html,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_latest_encounter_number(Request $request)
    {
        $patient_id = $request->get('patient_id');
        if (ctype_alpha(str_replace(' ', '', $patient_id)) === false) {
            //'Name must contain letters and spaces only';
            $encounters = Encounter::select('fldencounterval')->where('fldpatientval', $patient_id)->orderBy('fldregdate', 'DESC')->first();
        } else {
            $patientname = $patient_id . '%';
            $encounters = DB::table('tblencounter')
                ->join('tblpatientinfo', 'tblencounter.fldpatientval', '=', 'tblpatientinfo.fldpatientval')
                ->where('tblpatientinfo.fldptnamefir', 'LIKE',$patientname)
                ->orwhere('tblpatientinfo.fldptnamelast', 'LIKE', $patientname)
                ->select('tblencounter.fldencounterval', 'tblencounter.fldrank', 'tblpatientinfo.*')
                ->orderBy('fldregdate', 'DESC')->first();
        }
        if (isset($encounters)) {
            $latest_encounter = $encounters->fldencounterval;
        } else {
            $latest_encounter = 0;
        }
        return response()->json([
            'success' => [
                'latest_encounter' => $latest_encounter,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function number_save(Request $request)
    {

        $data = array(
            'fldencounterval' => $request->get('fldencounterval'),
            'fldserialval' => NULL,
            'fldinput' => 'OPD Examination',
            'fldtype' => $request->get('fldtype'),
            'fldhead' => $request->get('flditem'),
            'fldsysconst' => $request->get('fldsysconst'),
            'fldmethod' => 'Regular',
            'fldrepquali' => $request->get('content'),
            'fldrepquanti' => 0,
            'fldfilepath' => NULL,
            'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => 1,
            'fldabnormal' => 0,
            'flduptime' => NULL,
            'xyz' => 0,
        );

        $tab = $request->tab;
        PatientExam::insert($data);

        return redirect()->route('patient', ['tab' => $tab]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function scale_save(Request $request)
    {
        // dd($request);

        $data = array(
            'fldencounterval' => $request->get('fldencounterval'),
            'fldserialval' => NULL,
            'fldinput' => 'OPD Examination',
            'fldtype' => $request->get('fldtype'),
            'fldhead' => $request->get('flditem'),
            'fldsysconst' => $request->get('fldsysconst'),
            'fldmethod' => 'Regular',
            'fldrepquali' => $request->has('content') ? array_sum($request->get('content')) : null,
            'fldrepquanti' => 0,
            'fldfilepath' => NULL,
            'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => 1,
            'fldabnormal' => 0,
            'flduptime' => NULL,
            'xyz' => 0,
        );

        // dd($data);


        PatientExam::insert($data);

        return redirect()->route('patient');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function text_save(Request $request)
    {
        //dd($request);
        $data = array(
            'fldencounterval' => $request->get('fldencounterval'),
            'fldserialval' => NULL,
            'fldinput' => 'OPD Examination',
            'fldtype' => $request->get('fldtype'),
            'fldhead' => $request->get('flditem'),
            'fldsysconst' => $request->get('fldsysconst'),
            'fldmethod' => 'Regular',
            'fldrepquali' => strip_tags($request->get('box_content')),
            'fldrepquanti' => 0,
            'fldfilepath' => NULL,
            'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => 1,
            'fldabnormal' => 0,
            'flduptime' => NULL,
            'xyz' => 0,
            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
        );

        $tab = $request->tab;
        PatientExam::insert($data);

        return redirect()->route('patient', ['tab' => $tab]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lnrsave(Request $request)
    {

        $data = array(
            'fldencounterval' => $request->get('fldencounterval'),
            'fldserialval' => NULL,
            'fldinput' => 'OPD Examination',
            'fldtype' => $request->get('fldtype'),
            'fldhead' => $request->get('flditem'),
            'fldsysconst' => $request->get('fldsysconst'),
            'fldmethod' => 'Regular',
            'fldrepquali' => '{"LEFT":"' . $request->get('left') . '","RIGHT":"' . $request->get('right') . '"}',
            'fldrepquanti' => 0,
            'fldfilepath' => NULL,
            'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => 1,
            'fldabnormal' => 0,
            'flduptime' => NULL,
            'xyz' => 0,
            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
        );

        PatientExam::insert($data);

        return redirect()->route('patient');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_content(Request $request)
    {
        $type = $request->get('type');
        $item = $request->get('item');

        $on_array = ['Fixed Components', 'Left and Right', 'No Selection', 'Text Addition', 'Text Table'];
        if (in_array($type, $on_array)) {
            return response()->json([
                'success' => [
                    'type' => 'own',
                ]
            ]);
        } else {

            if ($type == 'Single Selection') {
                $examoptions = ExamOption::where('fldexamid', $item)->where('fldanswertype', $type)->get();
                $html = '<select name="content" class="form-control">';
                if (!empty($examoptions)) {
                    foreach ($examoptions as $opt) {
                        $html .= '<option value="' . $opt[' fldanswer'] . '"> ' . $opt['    fldanswer'] . '</option>';
                    }
                }
                $html .= '</select>';
            }

            if ($type == 'Clinical Scale') {
                $html = '';
                $examoptions = ExamOption::select('fldscalegroup')->where('fldexamid', $item)->where('fldanswertype', $type)->groupBy('fldscalegroup')->get();
                $options = array();

                if ($examoptions) {
                    foreach ($examoptions as $k => $opt) {
                        $options[$k]['question'] = $opt->fldscalegroup;
                        $options[$k]['examoptions_answer'] = ExamOption::where('fldexamid', $item)->where('fldanswertype', $type)->where('fldscalegroup', $opt->fldscalegroup)->get();
                    }
                }

                //dd($options);
                $html = '<form action="">
                <div class=" row">';
                if ($options) {
                    $count = 1;
                    $html2 = '';
                    foreach ($options as $option) {
                        if ($option['examoptions_answer']) {
                            $html2 .= '<option value="">Select Option</option>';
                            foreach ($option['examoptions_answer'] as $ans) {
                                $html2 .= '<option value="' . $ans->fldscale . '">' . $ans->fldanswer . '</option>';
                            }
                        }


                        $html .= ' <label class="col-sm-6 col-form-label">' .
                            $option['question']
                            . '</label>
                                        <div class="col-sm-4">
                                            <select class="form-control examanswer" name="content[]" count="' . $count . '">' .
                            $html2 . '
                                            </select>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="number" class="form-control scaleindex-' . $count . '" placeholder="0">
                                        </div>
                                        ';

                        $html2 = '';
                        $count++;
                    }
                }
                $html .= ' </div> </form>';
            }

            return response()->json([
                'success' => [
                    'options' => $html,
                ]
            ]);
        }
    }


    // getting all the Notes,History,Presenting Symptoms with below query
    // select fldid,fldencounterval,fldtype,flditem,fldreportquanti,fldreportquali,fldid,fldid,fldtime,flddetail from tblexamgeneral where fldencounterval=$encounter_id and fldinput='Presenting Symptoms' and fldsave=$encounter_id


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save_note_tabs(Request $request)
    {
        if (isset($request->box_content) && !empty($request->box_content))
            $content = $request->box_content;
        else
            $content = $request->content;


        $data = array(
            'fldencounterval' => $request->fldencounterval,
            'fldinput' => $request->fldinput,
            'fldtype' => 'Qualitative',
            'flditem' => 'Initial Planning',
            'fldreportquali' => NULL,
            'fldreportquanti' => 0,
            'flddetail' => $content,
            'flduserid' => $request->flduserid,
            'fldtime' => now(),
            'fldcomp' => $request->fldcomp,
            'fldsave' => $request->test,
            'flduptime' => $request->test,
            'xyz' => $request->test
        );

        $detail = ExamGeneral::where('fldencounterval', $request->fldencounterval)->where('fldinput', $request->fldinput)->orderBy('fldid', 'DESC')->first();
        if (!empty($detail)) {
            ExamGeneral::where([['fldid', $detail->fldid]])->update($data);
        } else {
            $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
            ExamGeneral::insert($data);
        }

        return response()->json([


            'success' => [
                'msg' => $request->fldinput,

            ]
        ]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    function insert_complaint(Request $request)
    {
        try {
            $hours = $request->duration;
            if ($request->duration_type == 'Days')
                $hours = $request->duration * 24;

            if ($request->duration_type == 'Weeks')
                $hours = $request->duration * 7 * 24;

            if ($request->duration_type == 'Months')
                $hours = $request->duration * 30 * 24;

            if ($request->duration_type == 'Years')
                $hours = $request->duration * 365 * 24;

            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldinput' => 'Presenting Symptoms',
                'fldtype' => 'Qualitative',
                'flditem' => $request->flditem,
                'fldreportquali' => $request->fldreportquali, //'On/Off'
                'fldreportquanti' => $hours, //hr ma convert garne
                'flddetail' => '', // pachi edit ma free writing gareko store jun user bhanne coulmn ma dekhcha
                'flduserid' => Helpers::getCurrentUserName(),
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => 1, //1
                'flduptime' => NULL, // null ????
                'xyz' => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );

            $latest_id = ExamGeneral::insertGetId($data);
            $details = ExamGeneral::where('fldid', $latest_id)->first();
            $deleteRoute = route('delete_complaint', $details->fldid);
            if ($details->fldreportquanti <= 24)
                $dayType = $details->fldreportquanti . 'hr';

            if ($details->fldreportquanti > 24 && $details->fldreportquanti <= 720)
                $dayType = round($details->fldreportquanti / 24, 2) . 'Days';

            if ($details->fldreportquanti > 720 && $details->fldreportquanti < 8760)
                $dayType = round($details->fldreportquanti / 720, 2) . 'Months';

            if ($details->fldreportquanti >= 8760)
                $dayType = round($details->fldreportquanti / 8760) . 'Years';
            $data['fldreportquanti'] = $dayType;
            $data['id'] = $latest_id;

            $rowview = '<tr id="com_' . $details->fldid . '">
                          <td>' . ++$request->latest_table_row_counter . '</td>
                          <td>' . $details->flditem . '</td>
                          <td>'
                . $dayType .
                '</td>
                          <td>' . $details->fldreportquali . '</td>
                          <td><a href="javascript:;" permit_user="' . $details->flduserid . '" class="delete_complaints" url="' . $deleteRoute . '" ><i class="far fa-trash-alt"></i></a></td>
                          <td><a href="javascript:void(0);" permit_user="' . $details->flduserid . '" data-toggle="modal"  data-toggle="modal"  data-target="#edit_complaint" old_complaint_detail="' . $details->flddetail . '" class="clicked_edit_complaint" clicked_flag_val="' . $details->fldid . '">
                          <i class="fas fa-edit"></i></a></td>
                          <td>' . $details->fldtime . '</td>
                          <td class="detail_' . $details->fldid . '">' . strip_tags($details->flddetail) . '</td>
                       </tr>';

            if ($latest_id) {
                Session::flash('display_popup_error_success', true);

                Session::flash('success_message', 'Complaint Inserted Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id,
                        'row' => $rowview,
                        'data' => $data,
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            dd($e);
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            // return redirect()->route('complaint');
            return response()->json([
                'error' => [
                    'message' => __('messages.error')
                ]
            ]);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function insert_complaint_detail(Request $request)
    {
        try {
            $fldid = $request->fldid;
            $data = array(
                'flddetail' => $request->flddetail, // pachi edit ma free writing gareko store jun user bhanne coulmn ma dekhcha
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'flduptime' => now()
            );
            ExamGeneral::where([['fldid', $fldid]])->update($data);
            return response()->json([
                'success' => [
                    'message' => 'Inserted Successfully.',
                    'detail' => strip_tags($request->flddetail),
                    'id' => $request->fldid,
                ]
            ]);
        } catch (\GearmanException $e) {
            return response()->json([
                'error' => [
                    'message' => __('messages.error')
                ]
            ]);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function insert_finding_detail(Request $request)
    {

        $fldid = $request->fldid;
        $data = array(
            'flddetail' => $request->flddetail, // pachi edit ma free writing gareko store jun user bhanne coulmn ma dekhcha
            'fldtime' => now(), //'2020-02-23 11:13:27.709'
            'flduptime' => now(),
        );
        $tab = $request->tab;
        PatientExam::where('fldid', $fldid)->update($data);

        return redirect()->route('patient', ['tab' => $tab]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function insert_essential_exam(Request $request)
    {

//            dd($request->essential);
        // dd($request->essential);
        //        INSERT INTO `tblpatientexam` ( `fldencounterval`, `fldserialval`, `fldinput`, `fldtype`, `fldhead`, `fldsysconst`, `fldmethod`, `fldrepquali`, `fldrepquanti`, `fldfilepath`, `flduserid`, `fldtime`, `fldcomp`, `fldsave`, `fldabnormal`, `flduptime`, `xyz` )
        //        VALUES ( $encounter_id, NULL, 'Examination', 'Quantitative', 'Diastolic BP', NULL, 'Manual', '7', 7, NULL, 'admin', '2020-02-23 11:58:00.016', 'comp01', $encounter_id, $encounter_id, NULL, '0' )

        if ($request->essential) {
            foreach ($request->essential as $req) {
                $lastest_sample = PatientExam::orderBy('fldid', 'desc')->limit(1)->get();
                if ($lastest_sample)
                    $latest_id = $lastest_sample[0]->fldid + 1;
                else
                    $latest_id = 1;
                $info = explode(':', $req);
                if($info[1] !=0){
                    $data = array(
                        'fldid' => $latest_id,
                        'fldencounterval' => $request->fldencounterval,
                        'fldserialval' => NULL,
                        'fldinput' => 'Examination',
                        'fldtype' => 'Quantitative',
                        'fldhead' => $info[0],
                        'fldsysconst' => NULL,
                        'fldmethod' => 'Manual',
                        'fldrepquali' => $info[1], //7
                        'fldrepquanti' => $info[1], //7
                        'fldfilepath' => NULL, //null
                        'flduserid' => $request->flduserid, //admin
                        'fldtime' => now(),
                        'fldcomp' => $request->fldcomp, // comp1
                        'fldsave' => 1, //1
                        'fldabnormal' => 1, //1
                        'flduptime' => NULL, //null
                        'xyz' => 0, // 0
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    );

                    PatientExam::insert($data);
                }

            }
        }


        $lastest_sam = PatientExam::orderBy('fldid', 'desc')->limit(1)->get();

        // dd($id);


        if ($lastest_sam[0]->fldid == $latest_id) {
            return response()->json([


                'success' => [
                    'id' => $latest_id,
                ]
            ]);
        } else {
            return response()->json([
                'error' => [
                    'message' => __('messages.error')
                ]

            ]);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function insert_finding(Request $request)
    {
        try {
            $data = array(

                'fldencounterval' => $request->fldencounterval,
                'fldserialval' => NULL,
                'fldinput' => 'OPD Examination',
                'fldtype' => $request->fldtype,
                'fldhead' => $request->fldhead,
                'fldsysconst' => NULL,
                'fldmethod' => 'Regular',
                'fldrepquali' => $request->fldrepquali,
                'fldrepquanti' => 0,
                'fldfilepath' => NULL,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'fldtime' => now(),
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => 1,
                'fldabnormal' => 0,
                'flduptime' => NULL,
                'xyz' => 0,
            );


            $latest_id = PatientExam::insertGetId($data);


            if ($latest_id) {
                Session::flash('display_popup_error_success', true);

                Session::flash('success_message', 'Finding update Successfully.');
                return response()->json([


                    'success' => [
                        'id' => $latest_id,

                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]

                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('patient');
        }
    }


    /**
     * @param $fldid
     * @return \Illuminate\Http\JsonResponse
     */
    function delete_complaint($fldid)
    {

        //UPDATE `tblexamgeneral` SET `fldsave` = '0' WHERE `fldid` = 944

        $data = array(
            'fldsave' => 0,

        );
        ExamGeneral::where('fldid', $fldid)->update($data);

        return response()->json([


            'success' => 'true'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function update_abnormal(Request $request)
    {

        //dd($request);
        $data = array(
            'fldabnormal' => $request->status,
        );
        $tab = $request->tab;
        PatientExam::where('fldid', $request->fldid)->update($data);
        Session::flash('display_popup_error_success', true);

        Session::flash('success_message', 'Finding update Successfully.');

        return redirect()->route('patient', ['tab' => $tab]);
    }


    /**
     * @param $fldid
     * @return \Illuminate\Http\JsonResponse
     */
    function delete_finding($fldid)
    {

        //UPDATE `tblexamgeneral` SET `fldsave` = '0' WHERE `fldid` = 944

        $data = array(
            'fldsave' => 0,
        );
        PatientExam::where('fldid', $fldid)->update($data);

        return response()->json([
            'success' => 'true'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeDrugActivity(Request $request)
    {
        try {
            $drug_insert = [];
            //if it type of fluid
            if ($request->get('type') == 'fluid') {

                $drug_insert = [
                    'fldencounterval' => $request->get('encounter'),
                    'flddoseno' => $request->get('id'),
                    'fldvalue' => $request->get('value'),
                    'fldunit' => 'ML/Hour',
                    'fldstatus' => 'ongoing',
                    'fldfromtime' => config('constants.current_date_time'),
                    'fldtime' => config('constants.current_date_time'),
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ];


                $status = NurseDosing::insertGetId($drug_insert);
                //dd($drug_insert);

                if ($status) {
                    $data = NurseDosing::where('fldid', $status)->first();
                    $name = PathDosing::where('fldid', $request->get('id'))->first();
                    $data['name'] = $name->flditem;
                }
                return response()->json(['success' => 'Added', 'data' => $data], 200);
            } else {
                // if it is type of drug
                foreach ($request->get('drug') as $drug) {
                    //                    dd('drug');
                    $drug_insert[] = [
                        'fldencounterval' => $request->get('encounter'),
                        'flddoseno' => $drug['id'],
                        'fldunit' => 'tab',
                        'fldvalue' => $drug['quantity'],
                        'fldtime' => config('constants.current_date_time'),
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ];
                }
                if (count($drug_insert) > 0) {
                    NurseDosing::insert($drug_insert);
                }
                return response()->json(['success' => 'Added'], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Cannot Add now'
            ], 422);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function StopFluid(Request $request)
    {

        $id = NurseDosing::find($request->get('id'));
        if ($id) {
            try {

                $id->fldtotime = config('constants.current_date_time');
                $id->fldstatus = 'stopped';
                $status = $id->save();
                if ($status) {
                    $data = NurseDosing::with('getName')->where('fldid', $id->fldid)->first();
                    $data['name'] = $data->getName->flditem ?? null;
                }
                return response()->json(['status' => 'success', 'data' => $data], 200);
            } catch (Exception $exception) {

                return response()->json(['status' => 'error'], 422);
            }
        } else {
            return response()->json(['status' => 'error'], 422);
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

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function opdHistory(Request $request)
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

        $html = view('outpatient::dynamic-views.opdhistory-data', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function getPhotographForm(Request $request)
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

        $html = view('outpatient::dynamic-views.photograph-form', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function savePhotographss(Request $request)
    {


        try {
            //            $mytime = Carbon::now();
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                $uploader = new OutpatientImageUploader(new UploadWithAspectRatio());

                $data['fldpic'] = $uploader->saveOriginalImage($image);
                // echo $data
                $data['fldcateg'] = $request->fldinput;
                $encounter = Encounter::where('fldencounterval', $request->encounterID)->first();
                $data['fldname'] = $encounter->fldpatientval;
                // $data['']

            }

            session()->flash('success_message', __('Allergy Drug Added Successfully.'));

            return redirect()->route('patient');
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Allergic Drugs'));

            return redirect()->route('patient');
        }
    }

    /**
     * @param Request $request
     */
    public function savePhotograph(Request $request)
    {
        // echo "here"; exit;
        $image = $request->image;
        try {
            if (isset($image)) {
                $encounter = Encounter::where('fldencounterval', $request->encounterID)->first();
                $imagedata = PersonImage::where('fldname', $encounter->fldpatientval)->first();
                if (isset($imagedata) and !empty($imagedata)) {
                    $timecolumn = 'fldtime';
                } else {
                    $timecolumn = 'flduptime';
                }
                $data = array(

                    'fldcateg' => $request->fldinput,
                    'fldname' => $encounter->fldpatientval,
                    'fldpic' => $image,
                    'fldlink' => NULL,
                    'flduserid' => \Auth::guard('admin_frontend')->user()->flduserid,
                    $timecolumn => now(),
                    'fldcomp' => \App\Utils\Helpers::getCompName(), // comp1
                    'fldsave' => 1,
                );
                if (isset($imagedata) and !empty($imagedata)) {
                    PersonImage::where('fldname', $encounter->fldpatientval)->update($data);
                } else {
                    PersonImage::insert($data);
                }
                // dd($data);
                return '<img src="' . $image . '" class="img-thumbnail" height="200" width="200"/>';
                exit;
            } else {
                return 'error';
            }
        } catch (Exception $e) {
            dd($e);
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetEncounter()
    {
        Session::forget('encounter_id');
        return redirect()->route('patient');
    }

    public function save_billingmode(Request $request)
    {
        $billingmode = $request->get('billingmode');
        $encounter_id = $request->get('encounter_id');


        $data = array(

            'fldbillingmode' => $billingmode,


        );


        Encounter::where([['fldencounterval', $encounter_id]])->update($data);


        return response()->json([
            'success' => [
                'options' => $billingmode,

            ]
        ]);
    }

    public function updateInside(Request $request)
    {

        $flag = $request->get('flag');
        $encounter_id = $request->get('encounter_id');


        $data = array(

            'fldinside' => $flag,


        );


        Encounter::where('fldencounterval', $encounter_id)->update($data);


        return response()->json([
            'success' => [
                'options' => $flag,

            ]
        ]);
    }

    public function get_essential_exam()
    {
        $encounter_id = Input::get('fldencounterval');
        $data['systolic_bp'] = $systolic_bp = DB::table('tblpatientexam')
            ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Systolic BP')
            ->orderBy('tblpatientexam.fldid', 'desc')->first();

        $data['diasioli_bp'] = $diasioli_bp = DB::table('tblpatientexam')
            ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Diastolic BP')
            ->orderBy('tblpatientexam.fldid', 'desc')->first();

        $data['pulse'] = $pulse = DB::table('tblpatientexam')
            ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Pulse Rate')
            ->orderBy('tblpatientexam.fldid', 'desc')->first();

        $data['temperature'] = $temperature = DB::table('tblpatientexam')
            ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Temperature (F)')
            ->orderBy('tblpatientexam.fldid', 'desc')->first();

        $data['respiratory_rate'] = $respiratory_rate = DB::table('tblpatientexam')
            ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Respiratory Rate')
            ->orderBy('tblpatientexam.fldid', 'desc')->first();

        $data['o2_saturation'] = $o2_saturation = DB::table('tblpatientexam')
            ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'O2 Saturation')
            ->orderBy('tblpatientexam.fldid', 'desc')->first();

        $data['grbs'] = $grbs = DB::table('tblpatientexam')
            ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'GRBS')
            ->orderBy('tblpatientexam.fldid', 'desc')->first();

        return response()->json($data);
    }

    function check_vitals(Request $request)
    {
        $vital = $request->vital;
        $value = $request->value;
        if ($vital == 'sys_bp') {
            $data = DB::table('tblexamlimit')
                ->select('fldhigh')
                ->where('fldexamid', 'Systolic BP')
                ->first();
        }
        if ($vital == 'dia_bp') {
            $data = DB::table('tblexamlimit')
                ->select('fldhigh')
                ->where('fldexamid',  'Diastolic BP')
                ->first();
        }
        if ($vital == 'pulse_rate') {
            $data = DB::table('tblexamlimit')

                ->select('fldhigh')
                ->where('fldexamid', 'Pulse Rate')
                ->first();
        }
        if ($vital == 'pulse_rate_rate') {
            $data = DB::table('tblexamlimit')
                ->select('fldhigh')
                ->where('fldexamid', 'Temperature (F)')
                ->first();
        }
        if ($vital == 'respi') {
            $data = DB::table('tblexamlimit')
                ->select('fldhigh')
                ->where('fldexamid', 'Respiratory Rate')
                ->first();
        }
        if ($vital == 'saturation') {
            $data = DB::table('tblexamlimit')
                ->select('fldhigh')
                ->where('fldexamid', 'O2 Saturation')
                ->first();
        }
        if ($vital == 'gcs') {
            $data = DB::table('tblexamlimit')
                ->select('fldhigh')
                ->where('fldexamid', 'GRBS')
                ->first();
        }

        if(!empty($data)){
            if ($value >= $data->fldhigh) {
                return response()->json([
                    'success' => [
                        'message' => 'highline'
                    ]

                ]);
            }
            if ($value <= $data->fldhigh) {
                return response()->json([
                    'success' => [
                        'message' => 'lowline'
                    ]

                ]);
            }
        }


    }

    public function setroomno(Request $request)
    {
        $user_id = Auth::guard('admin_frontend')->user()->id;
        if ($request->room_no != "") {
            $dataflag = array(
                'room_no' => $request->room_no,
            );
            // dd($user_id);

            CogentUsers::where('id', $user_id)->update($dataflag);
            session(['room_no' => $request->room_no]);
        }
    }

    public function setHospitalDepartment(Request $request)
    {
        if ($request->selected_hospital_department != "") {
            $hospital_department = HospitalDepartment::find($request->selected_hospital_department);
            Session::forget('encounter_id');
            Session::put('selected_user_hospital_department', $hospital_department);
        }
    }

    public function admitPatient(Request  $request){
        if(!$request->encounter_id){
            return response()->json(['error' =>'Please enter encounter']);
        }
        $exists = Admissionrequest::where('fldencounterval',$request->encounter_id)->first();
        if($exists){
            return response()->json(['error' =>'Request for this patient has been sent already.']);
        }
        try {
            $data = [
                'fldencounterval' =>$request->encounter_id,
                'fldpatientval' =>$request->patient_id,
                'fldbill' =>$request->bill,
                'fldconsultant' =>$request->consultant,
                'fldstatus' =>'unread',
                'message' =>$request->message,
                'flduserid' =>Helpers::getCurrentUserName(),
            ];
            Admissionrequest::create($data);
            return  response()->json(['success' =>true,'message' => 'Request sent successfully']);
        }catch (Exception $exception){
            return response()->json(['error' =>'Something went wrong']);
        }
    }
}
