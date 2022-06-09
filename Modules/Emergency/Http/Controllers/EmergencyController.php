<?php

namespace Modules\Emergency\Http\Controllers;

use App\BillingSet;
use App\Code;
use App\CogentUsers;
use App\CompExam;
use App\Complaints;
use App\Confinement;
use App\Consult;
use App\Department;
use App\Departmentbed;
use App\DiagnoGroup;
use App\Encounter;
use App\Exam;
use App\ExamGeneral;
use App\Examlimit;
use App\ExamOption;
use App\NurseDosing;
use App\PatBilling;
use App\PatFindings;
use App\PatGeneral;
use App\Pathdosing;
use App\PatientDate;
use App\PatientExam;
use App\PatientInfo;
use App\PatLabTest;
use App\PatPlanning;
use App\PatRadioTest;
use App\PatTiming;
use App\Referlist;
use App\ServiceCost;
use App\Test;
use App\User;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Modules\Billing\Http\Controllers\BillingInsertDataPrepareController;
use Session;

class EmergencyController extends Controller
{

    public function index(Request $request)
    {
        try {
            $data = array();

            $data['laboratory'] = $laboratory = Test::get();
            $data['complaint'] = $complaint = Complaints::get();
            $data['finding'] = $finding = Exam::get();
            $data['billingset'] = $billingset = BillingSet::get();
            $data['diagnosisgroup'] = DiagnoGroup::select('fldgroupname')->distinct()->get();
            $diagnocat = $this->getInitialDiagnosisCategory();
            $data['diagnosiscategory'] = $diagnocat;
            $data['refeere_location'] = Referlist::select('fldlocation')->get();

            $encounter_id_session = Session::get('emergency_encounter_id');

            // $admission = Encounter::where(['fldencounterval' => Session::get('emergency_encounter_id'), 'fldadmission' => 'Admitted'])->get();


            $data['patient_status_disabled'] = 0;

            if ($request->has('encounter_id') || $encounter_id_session) {
                if ($request->has('encounter_id'))
                    $encounter_id = $request->get('encounter_id');
                else
                    $encounter_id = $encounter_id_session;

                session(['emergency_encounter_id' => $encounter_id]);

                /*create last encounter id*/
                Helpers::emergencyEncounterQueue($encounter_id);
                //                $encounterIds = Options::get('emergency_last_encounter_id');

                //                $arrayEncounter = unserialize($encounterIds);
                /*create last encounter id*/

                $dataflag = array(
                    'fldinside' => 1,
                );

                Encounter::where('fldencounterval', $encounter_id)->update($dataflag);

                $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();

                $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();

                /*department change enabled*/
                /*if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
                    if (!in_array($data['enpatient']->fldcurrlocat, $current_user->department->pluck('flddept')->toArray())) {
                        Session::flash('display_popup_error_success', true);
                        Session::flash('error_message', 'You are not authorized to view this patients information.');
                        Session::forget('emergency_encounter_id');
                        return redirect()->route('admin.dashboard');
                    }
                }*/

                $data['patient_status_disabled'] = $enpatient->fldadmission == "Discharged" ? 1 : 0;


                $data['enable_freetext'] = Options::get('free_text');

                $patient_id = $enpatient->fldpatientval;

                $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();

                $data['patient_id'] = $patient_id;


                $intraige = 0;
                $traige = $enpatient->fldheight;
                if (empty($traige))
                    $intraige = 1;

                $data['intraige'] = $intraige;
                $data['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();


                $data['plannedConsultants'] = Consult::where('fldencounterval', $encounter_id)->where('fldstatus', 'Planned')->get();

                $data['departments'] = DB::table('tbldepartment')
                    ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
                    ->where('tbldepartment.fldcateg', 'Patient Ward')
                    ->select('tbldepartment.flddept')
                    ->groupBy('tbldepartment.flddept')
                    ->get();


                $data['patientexam'] = $patientexam_findings = PatientExam::where('fldencounterval', $encounter_id)->where('fldinput', 'OPD Examination')->where('fldsave', 1)->get();

                $data['history'] = $history = ExamGeneral::where('fldencounterval', $encounter_id)->where('fldinput', 'History')->where('flditem', 'Initial Planning')->orderBy('fldid', 'DESC')->first();

                $data['past_history'] = $past_history = ExamGeneral::join('tblencounter', 'tblexamgeneral.fldencounterval', '=', 'tblencounter.fldencounterval')
                    ->where(['tblencounter.fldpatientval' => $patient_id, 'tblexamgeneral.fldsave' => 1, 'tblexamgeneral.fldinput' => 'History', 'tblexamgeneral.flditem' => 'Initial Planning'])
                    ->where('tblencounter.fldencounterval', '!=', $encounter_id)
                    ->orderBy('tblexamgeneral.fldid', 'DESC')
                    ->get();


                $data['advice'] = $advice = ExamGeneral::where('fldencounterval', $encounter_id)->where('fldinput', 'Notes')->where('flditem', 'Initial Planning')->orderBy('fldid', 'DESC')->first();

                $data['patdiago'] = $patdiago = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();

                $data['past_patdiagno'] = $past_patdiagno = PatFindings::join('tblencounter', 'tblpatfindings.fldencounterval', '=', 'tblencounter.fldencounterval')
                    ->where(['tblencounter.fldpatientval' => $patient_id, 'tblpatfindings.fldsave' => 1])
                    ->whereIn('tblpatfindings.fldtype', ['Provisional Diagnosis', 'Final Diagnosis'])
                    ->where('tblencounter.fldencounterval', '!=', $encounter_id)
                    ->select('tblpatfindings.fldtype', 'tblpatfindings.fldcode')
                    ->get();
                $data['progress_note'] = ExamGeneral::where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes'])->whereIn('flditem', ['Progress Note'])->get();
                $data['clinic_note'] = ExamGeneral::where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes'])->whereIn('flditem', ['Clinicians Note'])->get();
                $data['nurse_note'] = ExamGeneral::where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes'])->whereIn('flditem', ['Nurses Note'])->get();


                $data['patdrug'] = $patdrug = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
                // dd($data['patdrug']);
                $data['examgeneral'] = $examgeneral = ExamGeneral::where('fldencounterval', $encounter_id)->where('fldinput', 'Presenting Symptoms')->where('fldsave', 1)->get();

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

                //  dd($data);
                $data['exam_systolic'] = $exam_systolic = Exam::where('fldsysconst', 'BloodPressure_Systolic')->get();


                $data['exam_diastolic'] = $exam_diastolic = Exam::where('fldsysconst', 'BloodPressure_Diastolic')->get();

                $data['exam_pulse'] = $exam_pulse = Exam::where('fldsysconst', 'Pulse_Rate')->first();

                $data['exam_temperature'] = $exam_temperature = Exam::where('fldsysconst', 'Temperature_System')->first();

                $data['exam_respiratory_rate'] = $exam_respiratory_rate = Exam::where('fldsysconst', 'Respiration_Rate')->first();

                $data['exam_o2_saturation'] = $exam_o2_saturation = Exam::where('fldsysconst', 'Oxygen_Saturation')->first();

                $data['consults'] = $consult = Consult::where('fldencounterval', $encounter_id)->first();

                $data['examlimit_sys_bp'] = Examlimit::where('fldexamid', 'Systolic BP')->first();
                $data['examlimit_dia_bp'] = Examlimit::where('fldexamid', 'Diastolic BP')->first();
                $data['examlimit_respi'] = Examlimit::where('fldexamid', 'Respiratory Rate')->first();
                $data['examlimit_saturation'] = Examlimit::where('fldexamid', 'O2 Saturation')->first();
                $data['pulse_rate_rate'] = Examlimit::where('fldexamid', 'Temperature(F)')->first();

                $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $encounter_id)->where('fldcode', '!=', null)->get();

                $data['allergicdrugs'] = Code::select('fldcodename')->whereNotIn('fldcodename', $patientallergicdrugs)->get();

                $data['consultants'] = User::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();

                $end = Carbon::parse(($patient->fldptbirday ?? null) ? $patient->fldptbirday : '');
                $now = Carbon::now();


                $length = $end->diffInDays($now) ?? null;

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


                $data['body_weight'] = $body_weight = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'Body_Weight')->orderBy('fldid', 'desc')->first();

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


                $data['fluid_list'] = Pathdosing::where(
                    [
                        ['fldencounterval', $encounter_id],
                        ['fldroute', 'fluid'],
                        ['fldlevel', 'Dispensed']
                    ]
                )
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


                $presentation = ExamGeneral::select('fldreportquanti')->where([
                    ['fldencounterval', $encounter_id],
                    ['fldinput', 'Obstetrics'],
                    ['flditem', 'Presentaion'],
                    ['fldtype', 'Qualitative']
                ])->get();


                $data['inserted_pain'] = ExamGeneral::select('fldreportquali')->where('fldencounterval', $encounter_id)
                    ->where('flditem', 'Pain Type')->first();

                $data['Pallor'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Pallor')->orderBy('fldid', 'DESC')->first();
                $data['Icterus'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Icterus')->orderBy('fldid', 'DESC')->first();
                $data['Cyanosis'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Cyanosis')->orderBy('fldid', 'DESC')->first();
                $data['Clubbing'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Clubbing')->orderBy('fldid', 'DESC')->first();
                $data['Oedema'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Oedema')->orderBy('fldid', 'DESC')->first();
                $data['Dehydration'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Dehydration')->orderBy('fldid', 'DESC')->first();
                $data['gcs'] = PatientExam::select('fldrepquali')->where('fldencounterval', $encounter_id)
                    ->where('fldhead', 'Glasgrow Coma Scale(GCS)')->first();


                return view('emergency::index', $data);
            }

            return view('emergency::index', $data);
        } catch (\GearmanException $e) {
        }
    }

    // IndexFunction End

    // Cheif Complaints Start Here =======================================

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
            $deleteRoute = route('delete_complaint_emergency', $details->fldid);
            if ($details->fldreportquanti <= 24)
                $dayType = $details->fldreportquanti . 'hr';

            if ($details->fldreportquanti > 24 && $details->fldreportquanti <= 720)
                $dayType = round($details->fldreportquanti / 24, 2) . 'Days';

            if ($details->fldreportquanti > 720 && $details->fldreportquanti < 8760)
                $dayType = round($details->fldreportquanti / 720, 2) . 'Months';

            if ($details->fldreportquanti >= 8760)
                $dayType = round($details->fldreportquanti / 8760) . 'Years';

            $rowview = '<tr>
                          <td></td>
                          <td>' . $details->flditem . '</td>
                          <td>'
                . $dayType .
                '</td>
                          <td>' . $details->fldreportquali . '</td>
                          <td><a href="javascript:;" permit_user="' . $details->flduserid . '" class="delete_complaints text-danger" url="' . $deleteRoute . '" ><i class="far fa-trash-alt"></i></a></td>
                          <td><a href="javascript:;" permit_user="' . $details->flduserid . '" data-toggle="modal" data-target="#edit_complaint_emergency" old_complaint_detail="' . $details->flddetai . '" class="clicked_edit_complaint" clicked_flag_val="' . $details->fldid . '">
                          <i class="ri-edit-2-fill"></i></a></td>
                          <td>' . $details->fldtime . '</td>
                          <td>' . $details->flduserid . '</td>
                          <td>' . strip_tags($details->flddetail) . '</td>
                       </tr>';

            if ($latest_id) {
                Session::flash('display_popup_error_success', true);

                Session::flash('success_message', 'Complaint Inserted Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id,
                        'row' => $rowview
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
            // return redirect()->route('complaint');
            return response()->json([
                'error' => [
                    'message' => __('messages.error')
                ]
            ]);
        }
    }

    public function getAllNotes()
    {
        $encounter_id = \Session::get('emergency_encounter_id');
        $progress_note = ExamGeneral::where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes'])->whereIn('flditem', ['Progress Note'])->get();
        $clinic_note = ExamGeneral::where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes'])->whereIn('flditem', ['Clinicians Note'])->get();
        $nurse_note = ExamGeneral::where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes'])->whereIn('flditem', ['Nurses Note'])->get();

        $html = '';
        if (isset($progress_note)) :
            $html .= '<div class="col-md-12">';
            $html .= ' <h3 class="all-note-title">Progress Note</h3>';
            foreach ($progress_note as $p) :


                $html .= '<p class="all-note-paragraph">{{ strip_tags($p->flddetail) }}</p>';

            endforeach;
            $html .= '</div>';
        endif;
        if (isset($clinic_note)) :
            $html .= '<div class="col-md-12">';
            $html .= '<h3 class="all-note-title">Clinicians Note</h3>';
            foreach ($clinic_note as $c) :


                $html .= '<p class="all-note-paragraph">{{ strip_tags($c->flddetail) }}</p>';

            endforeach;
            $html .= '</div>';
        endif;
        if (isset($nurse_note)) :
            $html .= '<div class="col-md-12">';
            $html .= '<h3 class="all-note-title">Nurses Note</h3>';
            foreach ($nurse_note as $n) :


                $html .= '<p class="all-note-paragraph">{{ strip_tags($n->flddetail) }}</p>';

            endforeach;
            $html .= '</div>';
        endif;

        return response()->json($html);
    }

    function insert_complaint_detail(Request $request)
    {
        try {
            $fldid = $request->fldid;
            $data = array(
                'flddetail' => $request->flddetail, // pachi edit ma free writing gareko store jun user bhanne coulmn ma dekhcha
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'flduptime' => now()
            );
            $updated = tap(ExamGeneral::where([['fldid', $fldid]]))->update($data)->first();
//           ExamGeneral::where([['fldid', $fldid]])->update($data);
//            $updated = ExamGeneral::select('fldid','flddetail','flduserid')->where('fldid', $fldid)->first();

            if ($updated) {
                return response()->json([
                    'success' => [
                        'message' => 'Process Successfully.',
                        'detail' => strip_tags($updated->flddetail),
                        'id' => $updated->fldid,
                        'user' => $updated->flduserid,
                    ]
                ]);
            } else {
                return response()->json(['message' => 'Something went wrong.']);
            }

        } catch (\GearmanException $e) {
            return response()->json([
                'error' => [
                    'message' => __('messages.error')
                ]
            ]);
        }
    }

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

    // Chef Complaints End Here ==========================================
    // ===================================================================

    // History Tabs Start Here ===========================================

    public function save_note_tabs(Request $request)
    {
        $data = array(
            'fldencounterval' => $request->fldencounterval,
            'fldinput' => $request->fldinput,
            'fldtype' => 'Qualitative',
            'flditem' => 'Initial Planning',
            'fldreportquali' => NULL,
            'fldreportquanti' => 0,
            'flddetail' => $request->content,
            'flduserid' => Helpers::getCurrentUserName(),
            'fldtime' => now(), //'2020-02-23 11:13:27.709'
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => True,
            'flduptime' => '',
            'xyz' => False
        );

        $detail = ExamGeneral::where([
            ['fldencounterval', $request->fldencounterval],
            ['fldinput', $request->fldinput]
        ])->orderBy('fldid', 'DESC')->first();

        if (!empty($detail))
            ExamGeneral::where([['fldid', $detail->fldid]])->update($data);
        else
            $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
        ExamGeneral::insert($data);

        return response()->json([
            'success' => [
                'msg' => $request->fldinput,
            ]
        ]);
    }

    // History Tabs End Here =============================================
    // ===================================================================

    // Essential Exam Start Here =========================================

    public function insert_essential_exam(Request $request)
    {
        if ($request->essential) {

            foreach ($request->essential as $req) {
                $lastest_sample = PatientExam::orderBy('fldid', 'desc')->limit(1)->get();
                if ($lastest_sample)
                    $latest_id = $lastest_sample[0]->fldid + 1;
                else
                    $latest_id = 1;


                $info = explode(':', $req);
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
                    'flduserid' => Helpers::getCurrentUserName(),
                    'fldtime' => now(), //'2020-02-23 11:13:27.709'
                    'fldcomp' => Helpers::getCompName(),
                    'fldsave' => 1, //1
                    'fldabnormal' => 1, //1
                    'flduptime' => NULL, //null
                    'xyz' => 0, // 0
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                );
                PatientExam::insert($data);
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

    // Essential Exam End Here ===========================================
    // ===================================================================

    // Allergy Start Here ================================================

    // FreeTextForm
    public function getAllergyfreetext(Request $request)
    {
        // echo $request->encounterId; exit;
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();

        $html = view('emergency::dynamic-views.allergy-freetext', $data)->render();
        return $html;
    }

    // FreeTextSave
    public function saveAllergyCustom(Request $request)
    {
        try {
            // dd($request);
            if ($request->custom_allergy != '') {
                $mytime = Carbon::now();
                $data['fldencounterval'] = $request->encounter;
                $data['fldtype'] = $request->fldinput;
                $data['fldcode'] = $request->custom_allergy;
                $data['fldcodeid'] = NULL;
                $data['flduserid'] = Helpers::getCurrentUserName();
                $data['fldtime'] = $mytime->toDateTimeString();
                $data['fldcomp'] = Helpers::getCompName();
                $data['fldsave'] = 1;
                $data['xyz'] = 0;
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                PatFindings::insert($data);
                $html = '';
                $patdrug = PatFindings::where('fldtype', $request->fldinput)->where('fldencounterval', $request->encounter)->where('fldsave', 1)->get();
                // dd($patdrug); exit;
                if (isset($patdrug) and !empty($patdrug)) {
                    foreach ($patdrug as $key => $pat) {
                        $html .= '<option value="' . $pat->fldid . '">' . $pat->fldcode . '</option>';
                    }
                }
                echo $html;
                exit;
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            session()->flash('error_message', __('Error While Adding Allergic Drugs'));

            return redirect()->back();
        }
    }

    // Allergic Save
    public function insert_allergydrugstore(Request $request)
    {
        try {

            $allergicdrugs = $request->allergydrugs;
            $mytime = Carbon::now();

            if (isset($allergicdrugs) && count($allergicdrugs) > 0) {
                foreach ($allergicdrugs as $ad) {
                    // echo $ad; exit;
                    $data['fldencounterval'] = $request->patient_id;
                    $data['fldtype'] = 'Allergic Drugs';
                    $data['fldcode'] = $ad;
                    $data['fldcodeid'] = NULL;
                    $data['flduserid'] = Helpers::getCurrentUserName();
                    $data['fldtime'] = $mytime->toDateTimeString();
                    $data['fldcomp'] = Helpers::getCompName();
                    $data['fldsave'] = 1;
                    $data['xyz'] = 0;
                    $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

                    PatFindings::insert($data);
                }

                $patdrug = PatFindings::where('fldencounterval', $request->patient_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
                $html = '';
                if (isset($patdrug) and count($patdrug) > 0) {
                    foreach ($patdrug as $value) {
                        $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
                    }
                }
                echo $html;
                exit;
            } else {
                session()->flash('error_message', __('Error While Adding Allergic Drugs'));

                return redirect()->route('emergency');
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            session()->flash('error_message', __('Error While Adding Allergic Drugs'));

            return redirect()->route('emergency');
        }
    }

    // Search Drugs
    public function searchDrugs()
    {
        $html = '';
        // $patientallergicdrugs = '';
        $searchtext = $_GET['term'];
        $patient_id = $_GET['patient_id'];
        $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $patient_id)->where('fldcode', '!=', null)->get();
        $searchedDrugs = Code::where('fldcodename', 'like', $searchtext . '%')->whereNotIn('fldcodename', $patientallergicdrugs)->get();
        if (isset($searchedDrugs) and count($searchedDrugs) > 0) {
            foreach ($searchedDrugs as $ad) {
                $html .= '<li class="list-group-item"><input type="checkbox" value="' . $ad->fldcodename . '" class="fldcodename" name="allergydrugs[]" /> ' . $ad->fldcodename . '</li>';
            }
        } else {
            $html = '<li class="list-group-item">No Drugs Available For' . $searchtext . '</li>';
        }
        echo $html;
    }

    public function getAllDrugs()
    {
        $html = '';
        $patient_id = $_GET['patient_id'];
        // $patientallergicdrugs = '';
        $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $patient_id)->where('fldcode', '!=', null)->get();
        $searchedDrugs = Code::select('fldcodename')->whereNotIn('fldcodename', $patientallergicdrugs)->get();
        if (isset($searchedDrugs) and count($searchedDrugs) > 0) {
            foreach ($searchedDrugs as $ad) {
                $html .= '<li class="list-group-item"><input type="checkbox" value="' . $ad->fldcodename . '" class="fldcodename" name="allergydrugs[]" />  ' . $ad->fldcodename . '</li>';
            }
        } else {
            $html = '<li class="list-group-item">No Drugs Available For' . $searchtext . '</li>';
        }
        echo $html;
    }

    // Delete Drugs
    public function deletepatfinding()
    {
        // echo $_POST['ids']; exit;
        try {
            $ids = $_POST['ids'];
            // echo $ids; exit;
            $finalids = explode(',', $ids);
            foreach ($finalids as $id) {
                $datas = array(
                    'fldsave' => 0,
                    'xyz' => 0
                );
                PatFindings::where('fldid', $id)->update($datas);
            }

            $data['error'] = 0;

            return $data;
        } catch (\Exception $e) {
            // \Log::ine($th->getMessage());
            $data['error'] = 1;

            return $data;
        }
    }

    // Allergy End Here ==================================================
    // ===================================================================

    // Diagnosis Start Here ==============================================

    public function getDiagnosisfreetext(Request $request)
    {
        // echo $request->encounterId; exit;
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();

        $html = view('emergency::dynamic-views.diagnosis-freetext', $data)->render();
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
                $patdiagno = PatFindings::where('fldtype', $request->fldinput)->where('fldencounterval', $request->encounter)->where('fldsave', 1)->get();
                // dd($patdrug); exit;
                if (isset($patdiagno) and !empty($patdiagno)) {
                    foreach ($patdiagno as $key => $pat) {
                        $html .= '<option value="' . $pat->fldid . '">' . $pat->fldcode . '</option>';
                    }
                }
                echo $html;
                exit;
            } else {
                session()->flash('error_message', __('Error While Adding Diagnosis'));

                return redirect()->route('emergency');
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            session()->flash('error_message', __('Error While Adding Diagnosis'));

            return redirect()->route('emergency');
        }
    }

    public function getObstetricData(Request $request)
    {
        //Obstetric Queries
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
            ['fldtype', 'Provisional Diagnosis'],
            ['fldcodeid', 'Obstetrics'],
            ['fldcode', '!=', ''],
            ['fldencounterval', $encounter_id]
        ])->first();

        $html = view('emergency::dynamic-views.obstetric-data', $data)->render();
        return $html;
    }

    public function saveObstetricRequest(Request $request)
    {
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
                $patData['fldtype'] = 'Provisional Diagnosis';
                $patData['fldcode'] = $request->obsdesc;
                $patData['fldcodeid'] = 'Obstetrics';
                $patData['flduserid'] = Helpers::getCurrentUserName();
                $patData['fldtime'] = $mytime->toDateTimeString();
                $patData['fldcomp'] = Helpers::getCompName();
                $patData['fldsave'] = 1;
                $patData['xyz'] = 0;

                PatFindings::where('fldid', $request->patfinding)->update($patData, ['timestamps' => false]);
            } else {
                #tblpatfindings ma insert garne

                $patData['fldencounterval'] = $request->encounter;
                $patData['fldtype'] = 'Provisional Diagnosis';
                $patData['fldcode'] = $request->obsdesc;
                $patData['fldcodeid'] = 'Obstetrics';
                $patData['flduserid'] = Helpers::getCurrentUserName();
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
            session()->flash('success_message', __('Obstetric Diagnosis Added.'));

            return redirect()->route('emergency');
        } catch (\GearmanException $e) {
            session()->flash('error_message', __('Error While Adding Obstetric Diagnosis'));

            return redirect()->route('emergency');
        }
    }

    // Diagnosis Save
    function diagnosisStore(Request $request)
    {

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
            PatFindings::insert($data);

            $patdiago = PatFindings::where('fldencounterval', $request->patient_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
            $html = '';
            if (isset($patdiago) and count($patdiago) > 0) {
                foreach ($patdiago as $key => $value) {
                    $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
                }
            }
            echo $html;
            exit;
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            session()->flash('error_message', __('Error While Adding Diagnosis'));

            return redirect()->route('emergency');
        }
    }

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
            /*return response()->json(['status' => 'error', 'data' => []]);*/
            return [];
        }
    }

    public function getDiagnosisByCode(Request $request)
    {
        $html = '';
        if ($request->get('term')) {

            $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
            $key = $request->get('term');
            $data = [];
            $parent_category = "";
            while ($csvLine = fgetcsv($handle, 1000, ";")) {
                if (substr($csvLine[1], 0, strlen($key)) == $key) {
                    if (strlen($csvLine[1]) == 3) {
                        $parent_category = $csvLine[3];
                    } else {
                        $data[$csvLine[1]] = $csvLine[3];
                    }
                }
            }
            if (count($data) < 1) {
                $data[$key] = $parent_category;
            }

            sort($data);
            if (isset($data) and count($data) > 0) {

                foreach ($data as $d) {
                    $html .= '<tr><td><input type="checkbox" class="diagnosissub" name="diagnosissub" value="' . $d . '"/></td><td>' . $d . '</td</tr>';
                }
            } else {
                $html = '<tr colspan="2"><td>No Diagnosis Available for Diagnosis Code ' . $key . '</td></tr>';
            }
            echo $html;
        } else {
            echo $html = '<tr colspan="2"><td>No Diagnosis Available</td></tr>';
        }
    }

    public function getDiagnosisByGroup(Request $request)
    {
        $html = '';
        if ($request->get('term')) {
            $groupname = $request->get('term');
            $diagnosiscategories = DiagnoGroup::select('flditemname', 'fldcodeid')->where('fldgroupname', $groupname)->get();
            // dd($diagnosiscategories);
            if (isset($diagnosiscategories) and count($diagnosiscategories) > 0) {
                foreach ($diagnosiscategories as $dc) {
                    $html .= '<tr><td><input type="checkbox" class="dccat" name="dccat" value="' . $dc['fldcodeid'] . '"/></td><td>' . $dc['fldcodeid'] . '</td><td>' . $dc['flditemname'] . '</td></tr>';
                }
            } else {
                $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
            }
        } else {
            $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
        }
        echo $html;
        exit;
    }

    public function getInitialDiagnosisCategoryAjax()
    {
        $html = '';

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
        if (isset($data) and count($data) > 0) {
            foreach ($data as $d) {
                $html .= '<tr><td><input type="checkbox" class="dccat" name="dccat" value="' . $d['code'] . '"/></td><td>' . $d['code'] . '</td><td>' . $d['name'] . '</td></tr>';
            }
        } else {
            $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
        }
        echo $html;
    }

    public function resetEncounter()
    {
        Session::forget('emergency_encounter_id');
        return redirect()->route('emergency');
    }

    // Diagnosis End Here ================================================
    // ===================================================================

    // Examination Start Here ============================================

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
            'flduserid' => Helpers::getCurrentUserName(),
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => 1,
            'fldabnormal' => 0,
            'flduptime' => NULL,
            'xyz' => 0,
        );

        $id = PatientExam::insertGetId($data);
        $html = '';
        if ($id) {
            $created = PatientExam::where('fldid', $id)->first();
            if($created){

                $html .= "<tr data-fldid='.$created->id.'>
                    <td>" . $created->fldhead . "</td>
                    <td><a href='javascript:;' data-toggle='modal'data-target='#findingnormalflag' class='clicked_flag' clicked_flag_val='. $created->fldid.'><i @if($created->fldabnormal == 0 ) style='color:green'@elseif($created->fldabnormal == 1) style='color:red'
                     @endif class='fas fa-square'></i></a></td> <td>".strip_tags($created->fldrepquali)."</td><td><a href='javascript:;' permit_user='{{ $created->flduserid }}'class='delete_finding text-danger'url=".route('emergency_delete_finding',$created->fldid)."> <i class='ri-delete-bin-5-fill'> </a></td>
                    <td>". $created->fldtime."</td></tr>";
                return response()->json(['html' => $html]);

            }
        }else{

            return  response()->json(['error'=>"Something went wrong"]);
        }

//        return redirect()->route('emergency');
    }

    public function scale_save(Request $request)
    {

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
            'flduserid' => Helpers::getCurrentUserName(),
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => 1,
            'fldabnormal' => 0,
            'flduptime' => NULL,
            'xyz' => 0,
        );

        $id = PatientExam::insertGetId($data);
        $html = '';
        if ($id) {
            $created = PatientExam::where('fldid', $id)->first();
            if($created){

                $html .= "<tr data-fldid='.$created->id.'>
                    <td>" . $created->fldhead . "</td>
                    <td><a href='javascript:;' data-toggle='modal'data-target='#findingnormalflag' class='clicked_flag' clicked_flag_val='. $created->fldid.'><i @if($created->fldabnormal == 0 ) style='color:green'@elseif($created->fldabnormal == 1) style='color:red'
                     @endif class='fas fa-square'></i></a></td> <td>".strip_tags($created->fldrepquali)."</td><td><a href='javascript:;' permit_user='{{ $created->flduserid }}'class='delete_finding text-danger'url=".route('emergency_delete_finding',$created->fldid)."> <i class='ri-delete-bin-5-fill'> </a></td>
                    <td>". $created->fldtime."</td></tr>";
                return response()->json(['html' => $html]);

            }
        }else{

            return  response()->json(['error'=>"Something went wrong"]);
        }
    }

    public function text_save(Request $request)
    {
        $data = array(
            'fldencounterval' => $request->get('fldencounterval'),
            'fldserialval' => NULL,
            'fldinput' => 'OPD Examination',
            'fldtype' => $request->get('fldtype'),
            'fldhead' => $request->get('flditem'),
            'fldsysconst' => $request->get('fldsysconst'),
            'fldmethod' => 'Regular',
            'fldrepquali' => $request->get('box_content'),
            'fldrepquanti' => 0,
            'fldfilepath' => NULL,
            'flduserid' => Helpers::getCurrentUserName(),
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => 1,
            'fldabnormal' => 0,
            'flduptime' => NULL,
            'xyz' => 0,
            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
        );

        PatientExam::insert($data);
        return redirect()->route('emergency');
    }

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
            'flduserid' => Helpers::getCurrentUserName(),
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => 1,
            'fldabnormal' => 0,
            'flduptime' => NULL,
            'xyz' => 0,
            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
        );

        $id = PatientExam::insertGetId($data);
        $html = '';
        if ($id) {
            $created = PatientExam::where('fldid', $id)->first();
            if($created){

                $html .= "<tr data-fldid='.$created->id.'>
                    <td>" . $created->fldhead . "</td>
                    <td><a href='javascript:;' data-toggle='modal'data-target='#findingnormalflag' class='clicked_flag' clicked_flag_val='. $created->fldid.'><i @if($created->fldabnormal == 0 ) style='color:green'@elseif($created->fldabnormal == 1) style='color:red'
                     @endif class='fas fa-square'></i></a></td> <td>".strip_tags($created->fldrepquali)."</td><td><a href='javascript:;' permit_user='{{ $created->flduserid }}'class='delete_finding text-danger'url=".route('emergency_delete_finding',$created->fldid)."> <i class='ri-delete-bin-5-fill'> </a></td>
                    <td>". $created->fldtime."</td></tr>";
                return response()->json(['html' => $html]);

            }
        }else{

            return  response()->json(['error'=>"Something went wrong"]);
        }
//        PatientExam::insert($data);
//        return redirect()->route('emergency');
    }

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

    // Examination End Here ==============================================
    // ===================================================================

    // Save Referre

    public function referre_location(Request $request)
    {
        try {
            //update fldreferrefrom
            $table = Encounter::where([
                ['fldencounterval', $request->fldencounterval],

            ])
                ->update(
                    array(
                        'fldreferfrom' => $request->fldreferreform
                    )
                );

            if ($table) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Location update Successfully.');
                return response()->json([
                    'success' => [
                        'message' => $request->fldreferreform . " Updated Successfully"
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => 'Something went wrong or ' . $request->fldreferreform . ' Already Exist.'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            session()->flash('error_message', __('Error While Adding Location'));

            return response()->json([
                'error' => [
                    'message' => __('messages.error')
                ]
            ]);
        }
    }

    // Note
    public function getRelatedNote()
    {
        $flditem = Input::get('flditem');
        // dd($related_history);
        $getRelatedNote = ExamGeneral::where([
            ['fldencounterval', Input::get('fldencounterval')],
            ['fldinput', 'Notes'],
            ['flditem', $flditem],
            ['fldsave', 1],
        ])->select('flddetail', 'fldid')->get();
        return response()->json($getRelatedNote);
    }

    public function updateNote(Request $request)
    {
        try {
            if ($request->fldid != Null) {
                //update
                $table = ExamGeneral::where([
                    ['fldid', $request->fldid],

                ])
                    ->update(
                        array(
                            'flddetail' => $request->flddetail
                        )
                    );

                if ($table) {
                    Session::flash('display_popup_error_success', true);
                    return response()->json([
                        'success' => [
                            'message' => "Note Updated Successfully"
                        ]
                    ]);
                } else {
                    Session::flash('display_popup_error_success', true);
                    return response()->json([
                        'error' => [
                            'message' => __('messages.error')
                        ]
                    ]);
                }
            } else {
                $data = array(
                    'fldencounterval' => $request->fldencounterval,
                    'fldinput' => 'Notes',
                    'fldtype' => 'Qualitative',
                    'flditem' => $request->flditem,
                    'fldreportquali' => Null,
                    'fldreportquanti' => 0,
                    'flddetail' => $request->flddetail,
                    'flduserid' => Helpers::getCurrentUserName(),
                    'fldtime' => now(),
                    'fldcomp' => Helpers::getCompName(),
                    'fldsave' => 1, //1
                    'flduptime' => now(), // null ????
                    'xyz' => 0,
                );
                $latest_id = ExamGeneral::insertGetId($data);
                if ($latest_id) {
                    Session::flash('display_popup_error_success', true);
                    return response()->json([
                        'success' => [
                            'message' => 'Note Save Successfully.'
                        ]
                    ]);
                } else {
                    Session::flash('display_popup_error_success', true);
                    return response()->json([
                        'error' => [
                            'message' => __('messages.error')
                        ]
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'message' => __('messages.error')
                ]
            ]);
        }
    }

    // Department Bed

    public function getRelatedBed()
    {
        $flddept = Input::get('flddept');
        $data['get_related_data'] = Departmentbed::where([
            'flddept' => $flddept
            // 'fldencounterval' => null
        ])->select('fldbed', 'fldencounterval', 'flddept')->get();

        $html = view('emergency::dynamic-views.related-bed', $data)->render();

        return response()->json([
            'status' => TRUE,
            'message' => 'Successfully.',
            'html' => $html
        ]);
    }

    function update_abnormal(Request $request)
    {

        //dd($request);
        $data = array(
            'fldabnormal' => $request->status,
            // 'updated_at' => config('constants.current_date_time')

        );
        PatientExam::where('fldid', $request->fldid)->update($data);
        Session::flash('display_popup_error_success', true);

        Session::flash('success_message', 'Finding update Successfully.');

        return redirect()->route('emergency');
    }

    public function postDepartmentBed(Request $request)
    {
                // dd($request->all());
                $admitFileNo = '';
                try {
                if ($request->fldbed != null || $request->fldencounterval != null || $request->fldcurrlocat != null) {
                // update
                // if ($request->holdbed)
                // DepartmentBed::where('fldencounterval', $request->fldencounterval)->update(array('fldhold' => TRUE));
                // else
                // DepartmentBed::where([
                // 'fldencounterval' => $request->fldencounterval,
                // 'fldhold' => FALSE,
                // ])->update(array('fldencounterval' => Null));



                $encounterDetail = Encounter::where('fldencounterval', $request->fldencounterval)->first();
                $datatiminginsert = [
                'fldencounterval' => $request->fldencounterval,
                'fldtype' => 'General Services',
                'flditem' => $request->fldbed, //current department of patitne, //transfer hunda department change huncha current location indoor or emergency linda
                'fldfirstreport' => 'Bed',
                'fldfirstuserid' => Auth::guard('admin_frontend')->user()->flduserid,
                'fldfirsttime' => date("Y-m-d H:i:s"),
                'fldfirstcomp' => Helpers::getCompName(),
                'fldfirstsave' => 1,
                'fldsecondreport' => NULL,
                'fldseconduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                'fldsecondcomp' => Helpers::getCompName(),
                'fldsecondsave' => 1,
                'fldsecondtime' => date("Y-m-d H:i:s"),
                'fldcomment' => NULL,
                'xyz' => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ];
                PatTiming::insert($datatiminginsert);



                $admitFileNo = Helpers::getNextAutoId('AdmitFileNo', TRUE);
                // PatientInfo::where([['fldpatientval', $encounterDetail->fldpatientval]])->update([
                // 'fldadmitfile' => $admitFileNo,
                // 'fldptguardian' => $request->get('fldptguardian'),
                // 'fldrelation' => $request->get('fldrelation'),
                // ]);
                //test



                Encounter::where('fldencounterval', $request->fldencounterval)->update(['fldcurrlocat' => $request->fldbed]);



                //transfer nnull the current bed of the encounter departmentbed
                $bedsoccu = Departmentbed::where('fldencounterval', $request->fldencounterval)->get();
                if ($bedsoccu) {
                foreach ($bedsoccu as $b) {
                Departmentbed::where('fldbed', $b->fldbed)->update(['fldencounterval' => NULL]);
                }




                Departmentbed::where('fldbed', $request->fldbed)->update(array('fldencounterval' => $request->fldencounterval));



                $patient_data_array = [
                'fldencounterval' => $request->fldencounterval,
                'fldhead' => 'Location Update',
                'fldcomment' => $request->fldbed,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                'fldtime' => now(),
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => 1,
                'flduptime' => NULL,
                'xyz' => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()



                ];
                PatientDate::insert($patient_data_array);
                \App\Consult::where([
                'fldencounterval' => $request->fldencounterval,
                ])->whereNull('flduserid')->update([
                'flduserid' => $request->consultant
                ]);



                $getBedDetails = Departmentbed::where('fldencounterval', $request->fldencounterval)->first();
                $getBeddeparment = Department::where('flddept', $getBedDetails->flddept)->first();
                if(isset($getBeddeparment) && $getBeddeparment->fldhead != 0){
                $updateData = BillingInsertDataPrepareController::preparePatBillData($request, $getBeddeparment->fldhead);
                PatBilling::insert($updateData);




                }



                Session::flash('display_popup_error_success', true);
                return response()->json([
                'success' => [
                'message' => "Bed Updated Successfully"
                ]
                ]);
                } elseif ($request->fldencounterval != null || $request->fldcurrlocat != null) {
                Encounter::where('fldencounterval', $request->fldencounterval)->update(['fldcurrlocat' => $request->fldcurrlocat]);



                Session::flash('display_popup_error_success', true);
                return response()->json([
                'success' => [
                'message' => "Location Updated Successfully"
                ]
                ]);
                } else {
                Session::flash('display_popup_error_success', true);
                return response()->json([
                'error' => [
                'message' => __('messages.error')
                ]
                ]);
                }
                }
                } catch (\Exception $e) {



                return response()->json([
                'error' => [
                'message' => 'Failed.'
                ]
                ]);
                }
    }

    public function updateDepartmentBed(Request $request)
    {

//         fldcurrlocat: Covid ICU
// fldbed: CI-04
// fldencounterval: IP78-79-1604
// consultant: anurag.jha
// fldptguardian: JITENDRA BDR SINGH
// fldrelation: Brother
// admitted: Admitted
// fldbillingmode: General
// discountMode: GENERAL
        $admitFileNo = '';
        try {
            if ($request->fldbed != null || $request->fldencounterval != null || $request->fldcurrlocat != null) {
                $encounterDetail = Encounter::where('fldencounterval', $request->fldencounterval)->first();
                $datatiminginsert = [
                    'fldencounterval' => $request->fldencounterval,
                    'fldtype' => 'General Services',
                    'flditem' => $request->fldbed, //current department of patitne, //transfer hunda department change huncha current location indoor or emergency linda
                    'fldfirstreport' => 'Bed',
                    'fldfirstuserid' => Auth::guard('admin_frontend')->user()->flduserid,
                    'fldfirsttime' => date("Y-m-d H:i:s"),
                    'fldfirstcomp' => Helpers::getCompName(),
                    'fldfirstsave' => 1,
                    'fldsecondreport' => NULL,
                    'fldseconduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                    'fldsecondcomp' => Helpers::getCompName(),
                    'fldsecondsave' => 1,
                    'fldsecondtime' => date("Y-m-d H:i:s"),
                    'fldcomment' => NULL,
                    'xyz' => 0,
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ];
                PatTiming::insert($datatiminginsert);

                $admitFileNo = Helpers::getNextAutoId('AdmitFileNo', TRUE);
                PatientInfo::where([['fldpatientval', $encounterDetail->fldpatientval]])->update([
                    'fldadmitfile' => $admitFileNo,
                    'fldptguardian' => $request->get('fldptguardian'),
                    'fldrelation' => $request->get('fldrelation'),
                ]);

                Encounter::where('fldencounterval', $request->fldencounterval)->update([
                    'fldcurrlocat' => $request->fldbed,
                    'fldadmitlocat' => $request->fldcurrlocat,
                    'fldadmission' => 'Admitted',
                    'xyz' => 0,
                    'flddoa' => now(),
                    'flduserid' => $request->consultant
                ]);

                \App\Consult::where([
                    'fldencounterval' => $request->fldencounterval,
                ])->whereNull('fldconsultname')->update([
                    'fldconsultname' => $request->fldcurrlocat
                ]);

                //transfer nnull the current bed of the encounter departmentbed
                $bedsoccu = Departmentbed::where('fldencounterval', $request->fldencounterval)->get();
                if ($bedsoccu) {
                    // foreach ($bedsoccu as $b) {
                    //     Departmentbed::where('fldbed', $b->fldbed)->update(['fldencounterval' => NULL]);
                    // }

                    Departmentbed::where('fldbed', $request->fldbed)->update(
                        array('fldencounterval' => $request->fldencounterval)
                    );

                    $patient_data_array = [
                        'fldencounterval' => $request->fldencounterval,
                        'fldhead' => $request->admitted,
                        'fldcomment' => $request->fldbed,
                        'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                        'fldtime' => now(),
                        'fldcomp' => Helpers::getCompName(),
                        'fldsave' => 1,
                        'flduptime' => NULL,
                        'xyz' => 0,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

                    ];
                    PatientDate::insert($patient_data_array);

                    $getBedDetails = Departmentbed::where('fldencounterval', $request->fldencounterval)->first();
                    $getBeddeparment = Department::where('flddept', $getBedDetails->flddept)->first();
                    if(isset($getBeddeparment) &&  $getBeddeparment->fldhead != 0){
                        $updateData = BillingInsertDataPrepareController::preparePatBillData($request, $getBeddeparment->fldhead);
                        PatBilling::insert($updateData);


                    }


                    Session::flash('display_popup_error_success', true);
                    return response()->json([
                        'success' => [
                            'message' => "Bed Updated Successfully"
                        ]
                    ]);
                } elseif ($request->fldencounterval != null || $request->fldcurrlocat != null) {
                    Encounter::where('fldencounterval', $request->fldencounterval)->update(['fldcurrlocat' => $request->fldcurrlocat]);

                    Session::flash('display_popup_error_success', true);
                    return response()->json([
                        'success' => [
                            'message' => "Location Updated Successfully"
                        ]
                    ]);
                } else {
                    Session::flash('display_popup_error_success', true);
                    return response()->json([
                        'error' => [
                            'message' => __('messages.error')
                        ]
                    ]);
                }
            }
        } catch (\Exception $e) {
dd($e);
            return response()->json([
                'error' => [
                    'message' => 'Failed.'
                ]
            ]);
        }
    }

    // Get Department Location
    public function getDepartmentLocation()
    {
        $fldencounterval = Input::get('fldencounterval');
        $get_related_data = Encounter::where('fldencounterval', $fldencounterval)->select('fldcurrlocat')->first();
        return response()->json($get_related_data);
    }

    // update patient register
    public function postPatientAdmission(Request $request)
    {
        try {
            if ($request->fldencounterval != '') {
                if ($request->fldadmission == 1) {

                    $fldadmission = 'Admitted';
                } else {

                    $fldadmission = 'Registered';
                }

                $current_date = config('constants.current_date_time');
                // update
                Encounter::where('fldencounterval', $request->fldencounterval)->update(['fldadmission' => $fldadmission, 'flddoa' => $current_date]);
                $patient_data_array = [
                    'fldencounterval' => $request->fldencounterval,
                    'fldhead' => 'Admitted',
                    'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                    'fldtime' => date("Y-m-d H:i:s"),
                    'fldcomp' => Helpers::getCompName(),
                    'fldsave' => 1,
                    'flduptime' => NULL,
                    'xyz' => 0,
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),

                ];
                PatientDate::insert($patient_data_array);

                Session::flash('display_popup_error_success', true);

                return response()->json([
                    'success' => [
                        'message' => "Patient Profile Updated Successfully",
                        'data' => $request->fldadmission
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                return response()->json([
                    'error' => [
                        'message' => 'Please select the encounter id'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return response()->json([
                'error' => [
                    'message' => 'Failed.'
                ]
            ]);
        }
    }

    // get Patient Status
    public function getPatientAdmittionStatus()
    {
        $fldencounterval = Input::get('fldencounterval');
        $get_related_data = Encounter::where('fldencounterval', $fldencounterval)->select('fldadmission')->first();
        return response()->json($get_related_data);
    }

    // Triage Color

    public function changeColor(Request $request)
    {
        try {
            $encounter_id = \Session::get('emergency_encounter_id');
            if ($encounter_id == '') {
                return response()->json([
                    'status' => False,
                    'message' => 'Encounter Id Required.',
                ]);
            }

            //  dd($request->color);
            $data = array(
                'fldheight' => $request->color
            );
            Encounter::where([
                ['fldencounterval', $encounter_id],

            ])->update($data);

            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update Data.',
            ]);
        }
    }

    public function getColor()
    {
        try {
            $encounter_id = \Session::get('emergency_encounter_id');
            $data = \DB::table('tblencounter')->where('fldencounterval', $encounter_id)->select('fldheight')->first();
            $color = isset($data->fldheight) ? $data->fldheight : '#fff0';
            return response()->json($color);
        } catch (Exception $e) {
        }
    }


    public function save_pain(Request $request)
    {
        $data = array(
            'fldencounterval' => $request->fldencounterval,
            'fldinput' => 'Emergency',
            'fldtype' => 'Qualitative',
            'flditem' => 'Pain Type',
            'fldreportquali' => $request->pain,
            'fldreportquanti' => 0,
            'flddetail' => '',
            'flduserid' => Helpers::getCurrentUserName(),
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName(),
            'fldsave' => True,
            'xyz' => False
        );

        $detail = ExamGeneral::where('fldencounterval', $request->fldencounterval)->where('fldinput', 'Emergency')->where('flditem', 'Pain Type')->orderBy('fldid', 'DESC')->first();

        if (!empty($detail))
            ExamGeneral::where([['fldid', $detail->fldid]])->update($data);
        else
            $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
        ExamGeneral::insert($data);

        return response()->json([
            'success' => [
                'msg' => $request->pain,
            ]
        ]);
    }

    public function insert_general_exam(Request $request)
    {
        if (!empty($request->Pallor)) {
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldinput' => 'OPD Examination',
                'fldtype' => 'Qualitative',
                'fldhead' => 'Pallor',
                'fldsysconst' => 'Pallor',
                'fldrepquali' => $request->Pallor,
                // 'fldabnormal' => $request->Pallor_normal,
                'flduserid' => Helpers::getCurrentUserName(),
                'fldtime' => now(),
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => True,
                'xyz' => False
            );
            $detail = PatientExam::where('fldencounterval', $request->fldencounterval)->where('fldinput', 'OPD Examination')->orwhere('fldinput', 'Examination')->where('fldhead', 'Pallor')->orderBy('fldid', 'DESC')->first();

            if (!empty($detail)) {
                PatientExam::where([['fldid', $detail->fldid]])->update($data);
            } else {
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                PatientExam::insert($data);
            }
        }

        if (!empty($request->Icterus)) {
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldinput' => 'OPD Examination',
                'fldtype' => 'Qualitative',
                'fldhead' => 'Icterus',
                'fldsysconst' => 'Icterus',

                'fldrepquali' => $request->Icterus,
                'fldabnormal' => $request->Icterus_normal,

                'flduserid' => Helpers::getCurrentUserName(),
                'fldtime' => now(),
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => True,
                'xyz' => False
            );

            $detail = PatientExam::where('fldencounterval', $request->fldencounterval)->where('fldinput', 'OPD Examination')->where('fldhead', 'Icterus')->orderBy('fldid', 'DESC')->first();

            if (!empty($detail)) {
                PatientExam::where([['fldid', $detail->fldid]])->update($data);
            } else {
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                PatientExam::insert($data);
            }
        }

        if (!empty($request->Cyanosis)) {
            $data = array(

                'fldencounterval' => $request->fldencounterval,
                'fldinput' => 'OPD Examination',
                'fldtype' => 'Qualitative',
                'fldhead' => 'Cyanosis',
                'fldsysconst' => 'Cyanosis',

                'fldrepquali' => $request->Cyanosis,
                'fldabnormal' => $request->Cyanosis_normal,
                'flduserid' => Helpers::getCurrentUserName(),
                'fldtime' => now(),
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => True,
                'xyz' => False
            );

            $detail = PatientExam::where('fldencounterval', $request->fldencounterval)->where('fldinput', 'OPD Examination')->where('fldhead', 'Cyanosis')->orderBy('fldid', 'DESC')->first();
            if (!empty($detail)) {
                PatientExam::where([['fldid', $detail->fldid]])->update($data);
            } else {
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                PatientExam::insert($data);
            }
        }

        if (!empty($request->Clubbing)) {
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldinput' => 'OPD Examination',
                'fldtype' => 'Qualitative',
                'fldhead' => 'Clubbing',
                'fldsysconst' => 'Clubbing',

                'fldrepquali' => $request->Clubbing,
                'fldabnormal' => $request->Clubbing_normal,

                'flduserid' => Helpers::getCurrentUserName(),
                'fldtime' => now(),
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => True,
                'xyz' => False
            );

            $detail = PatientExam::where('fldencounterval', $request->fldencounterval)->where('fldinput', 'OPD Examination')->where('fldhead', 'Clubbing')->orderBy('fldid', 'DESC')->first();

            if (!empty($detail)) {
                PatientExam::where([['fldid', $detail->fldid]])->update($data);
            } else {
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                PatientExam::insert($data);
            }
        }

        if (!empty($request->Oedema)) {
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldinput' => 'OPD Examination',
                'fldtype' => 'Qualitative',
                'fldhead' => 'Oedema',
                'fldsysconst' => 'Oedema',

                'fldrepquali' => $request->Oedema,
                'fldabnormal' => $request->Oedema_normal,

                'flduserid' => Helpers::getCurrentUserName(),
                'fldtime' => now(),
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => True,
                'xyz' => False
            );

            $detail = PatientExam::where('fldencounterval', $request->fldencounterval)->where('fldinput', 'OPD Examination')->where('fldhead', 'Oedema')->orderBy('fldid', 'DESC')->first();

            if (!empty($detail)) {
                PatientExam::where([['fldid', $detail->fldid]])->update($data);
            } else {
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                PatientExam::insert($data);
            }
        }

        if (!empty($request->Dehydration)) {
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldserialval' => NULL,
                'fldinput' => 'OPD Examination',
                'fldtype' => 'Quanlitative',
                'fldhead' => 'Dehydration',

                'fldrepquali' => $request->Dehydration,
                'fldabnormal' => $request->Dehydration_normal,

                'flduserid' => Helpers::getCurrentUserName(),
                'fldtime' => now(),
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => True,
                'xyz' => False
            );

            $detail = PatientExam::where('fldencounterval', $request->fldencounterval)->where('fldinput', 'OPD Examination')->where('fldhead', 'Dehydration')->orderBy('fldid', 'DESC')->first();

            if (!empty($detail)) {
                PatientExam::where([['fldid', $detail->fldid]])->update($data);
            } else {
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                PatientExam::insert($data);
            }
        }


        return response()->json([
            'success' => [
                'msg' => 'inserted',
            ]
        ]);
    }

    public function insert_gcs(Request $request)
    {
        $gcs = [
            'fldencounterval' => $request->fldencounterval,
            'fldinput' => 'Examination',
            'fldtype' => 'Qualitative',
            'fldhead' => 'Glasgrow Coma Scale(GCS)',
            'fldsysconst' => 'Glassgrow_coma_scale',
            'fldrepquali' => json_encode([
                'Eye Response' => $request->get('e'),
                'Verbal Response' => $request->get('v'),
                'Motor Response' => $request->get('m'),
            ]),

            'fldrepquanti' => $request->get('total_gcs'),

            'fldtime' => config('constants.current_date_time')
        ];

        $detail = PatientExam::where('fldencounterval', $request->fldencounterval)->where('fldinput', 'Examination')->where('fldhead', 'Glasgrow Coma Scale(GCS)')->orderBy('fldid', 'DESC')->first();

        if (!empty($detail))
            PatientExam::where([['fldid', $detail->fldid]])->update($gcs);
        else
            $gcs['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
        PatientExam::insert($gcs);


        return response()->json([
            'success' => [
                'msg' => 'inserted',
            ]
        ]);
    }

    public function generatePdf($encounter_id)
    {


        $information = array();
        $data['title'] = 'ENCOUNTER REPORT';

        $patient_encounter_ids = Encounter::select('fldencounterval', 'fldpatientval', 'fldrank')
            ->where('fldencounterval', $encounter_id)
            ->orderBy('fldregdate', 'DESC')
            ->first();

        //  dd($patient_encounter_ids);

        $patient_id = $patient_encounter_ids->fldpatientval;

        $patient_info = PatientInfo::select('fldptcontact', 'fldpatientval', 'fldencrypt', 'fldptadddist', 'fldptaddvill', 'fldptnamefir', 'fldptnamelast', 'fldencrypt', 'fldptsex', 'fldptbirday', 'fldmidname', 'fldrank')
            ->where('fldpatientval', $patient_id)
            ->first();


        $k = 0;

        if ($patient_encounter_ids) {


            $information[$k]['encounter_detail'] = $encounter_detail = Encounter::select('fldencounterval as col', 'fldpatientval', 'fldregdate', 'fldfollowdate', 'fldrank')
                ->where('fldencounterval', $encounter_id)
                ->first();

            $information[$k]['patient_date'] = PatientDate::select('fldhead', 'fldtime', 'fldcomment')
                ->where('fldencounterval', $encounter_id)
                ->first();


            $information[$k]['bed'] = PatTiming::select('fldid', 'flditem', 'fldfirsttime', 'fldsecondtime', 'fldsecondreport')
                ->where('fldencounterval', $encounter_id)
                ->where('fldtype', 'General Services')
                ->where('fldfirstreport', 'Bed')
                ->get();


            $information[$k]['demographics'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                ->where('fldencounterval', $encounter_id)
                ->where('fldinput', 'Demographics')
                ->get();


            $information[$k]['triage_examinations'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                ->where(['fldencounterval' => $encounter_id, 'fldsave' => '1', 'fldinput' => 'Triage examinations'])
                ->get();


            $information[$k]['cause_of_admission'] = ExamGeneral::select('flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Cause of Admission'])
                ->get();


            $patientExam = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                ->where('fldencounterval', $encounter_id)
                ->where('fldinput', 'OPD Examination')
                ->where('fldsave', '1')
                ->get();


            $opdData = [];
            foreach ($patientExam as $opdExam) {
                $opdData['fldid'][] = $opdExam->fldid;
                $opdData['fldtime'][] = $opdExam->fldtime;
                $opdData['fldhead'][] = $opdExam->fldhead;
                $opdData['fldrepquali'][] = json_decode($opdExam->fldrepquali, true);
                $opdData['fldrepquanti'][] = $opdExam->fldrepquanti;
                $opdData['fldtype '][] = $opdExam->fldtype;
            }

            $information[$k]['patientExam'] = $opdData;


            $information[$k]['general_complaints'] = ExamGeneral::select('flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'General Complaints'])
                ->get();

            $information[$k]['history_illness'] = ExamGeneral::select('flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'History of Illness'])
                ->get();

            $information[$k]['past_history'] = ExamGeneral::select('flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Past History'])
                ->get();

            $information[$k]['treatment_history'] = ExamGeneral::select('flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Treatment History'])
                ->get();

            $information[$k]['medicated_history'] = ExamGeneral::select('flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Medication History'])
                ->get();

            $information[$k]['family_history'] = ExamGeneral::select('flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Family History'])
                ->get();

            $information[$k]['personal_history'] = ExamGeneral::select('flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Personal History'])
                ->get();

            $information[$k]['surgical_history'] = ExamGeneral::select('flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Surgical History'])
                ->get();

            $information[$k]['occupational_history'] = ExamGeneral::select('flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Occupational History'])
                ->get();

            $information[$k]['social_history'] = ExamGeneral::select('flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Social History'])
                ->get();

            $information[$k]['allergy_drugs'] = PatFindings::select('fldcode', 'fldcodeid')
                ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Allergic Drugs', 'fldsave' => '1'])
                ->get();

            $information[$k]['provisinal_diagnosis'] = PatFindings::select('fldcode', 'fldcodeid')
                ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Provisional Diagnosis', 'fldsave' => '1'])
                ->get();

            $information[$k]['initial_planning'] = ExamGeneral::select('flddetail', 'fldtime')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes', 'flditem' => 'Initial Planning'])
                ->get();

            $information[$k]['final_diagnosis'] = PatFindings::select('fldcode', 'fldcodeid')
                ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Final Diagnosis', 'fldsave' => '1'])
                ->get();

            $information[$k]['prominent_symptoms'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Patient Symptoms', 'fldsave' => '1'])
                ->get();

            $information[$k]['procedures'] = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Procedures', 'fldreportquali' => 'Done'])
                ->get();

            $information[$k]['minor_procedure'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Minor Procedures', 'fldreportquali' => 'Done'])
                ->get();

            $information[$k]['consult'] = Consult::select('fldconsultname', 'fldconsulttime', 'fldstatus')
                ->where('fldencounterval', $encounter_id)
                ->get();

            $information[$k]['equipment'] = PatTiming::select('flditem', 'fldfirsttime', 'fldsecondtime', 'fldsecondreport')
                ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Equipment'])
                ->get();

            $information[$k]['planned'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Extra Procedures', 'fldreportquali' => 'Planned'])
                ->get();


            $information[$k]['mainDataForPatDosing'] = $mainDataForPatDosing = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'flditemtype')
                ->where('fldencounterval', $encounter_id)
                ->where('flditemtype', 'Medicines')
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Registered')
                        ->orWhere('fldstatus', '=', 'Admitted')
                        ->orWhere('fldstatus', '=', 'Recorded');
                })
                ->get();

            $information[$k]['singleData'] = [];
            foreach ($mainDataForPatDosing as $singlePatDosing) {
                $information[$k]['singleData'] = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays')
                    ->where('fldencounterval', $encounter_id)
                    ->where('flditemtype', 'Medicines')
                    ->where('fldroute', $singlePatDosing->fldroute)
                    ->where('flditem', $singlePatDosing->flditem)
                    ->where('flddose', $singlePatDosing->flddose)
                    ->where('fldfreq', $singlePatDosing->fldfreq)
                    ->where(function ($query) {
                        return $query
                            ->orWhere('fldstatus', '=', 'Registered')
                            ->orWhere('fldstatus', '=', 'Admitted')
                            ->orWhere('fldstatus', '=', 'Recorded');
                    })
                    ->first();
            }


            $information[$k]['confinement'] = Confinement::select('fldid', 'flddelresult', 'flddeltype', 'fldbabypatno', 'flddeltime', 'flddelwt')
                ->where('fldencounterval', $encounter_id)
                ->get();


            // $fldexamid = CompExam::where('fldcomp', Helpers::getCompName())
            //     ->where('fldcategory', 'Essential examinations')
            //     ->pluck('fldexamid');

            $fldexamid = CompExam::where('fldcategory', 'Essential examinations')
                ->pluck('fldexamid');

            $information[$k]['fldhead'] = PatientExam::select('fldhead as col')
                ->where('fldencounterval', $encounter_id)
                ->where('fldsave', '1')
                ->whereIn('fldhead', $fldexamid)
                ->distinct()
                ->get();


            $information[$k]['fldheadNotIn'] = PatientExam::select('fldhead as col')
                ->where('fldencounterval', $encounter_id)
                ->where('fldsave', '1')
                ->whereNotIn('fldhead', $fldexamid)
                ->distinct()
                ->get();


            $information[$k]['patientSerialValue'] = PatientExam::select('fldserialval as col')
                ->where('fldserialval', 'like', '%')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Rec Examination', 'fldsave' => '1'])
                ->distinct()
                ->get();


            $information[$k]['AntenatalExam3rd'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Antenatal Examination - 3RD TIMESTER', 'fldsave' => '1', 'fldinput' => 'Examination'])
                ->get();


            $information[$k]['Saturation'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'O2 Saturation', 'fldsave' => '1', 'fldinput' => 'Examination'])
                ->with(['patientSubExam'])
                ->get();


            $information[$k]['OptionSaturation'] = Exam::select('fldoption')
                ->where('fldexamid', 'O2 Saturation')
                ->get();


            $information[$k]['PulseRatePatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Pulse Rate', 'fldinput' => 'Examination', 'fldsave' => '1'])
                ->orderBy('fldid', 'DESC')
                ->first();


            $information[$k]['PulseRateExamLimit'] = Examlimit::select('fldunit')
                ->where('fldexamid', 'Pulse Rate')
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldptsex', '=', 'Male')
                        ->orWhere('fldptsex', '=', 'Both Sex');
                })
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldagegroup', '=', 'Adolescent')
                        ->orWhere('fldagegroup', '=', 'All Age');
                })
                ->first();


            $information[$k]['SystolicBPPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Systolic BP', 'fldinput' => 'Examination', 'fldsave' => '1'])
                ->orderBy('fldid', 'DESC')
                ->first();


            $information[$k]['SystolicBPExamLimit'] = Examlimit::select('fldunit')
                ->where('fldexamid', 'Systolic BP')
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldptsex', '=', 'Male')
                        ->orWhere('fldptsex', '=', 'Both Sex');
                })
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldagegroup', '=', 'Adolescent')
                        ->orWhere('fldagegroup', '=', 'All Age');
                })
                ->orderBy('fldid', 'DESC')
                ->first();


            $information[$k]['DiastolicBPPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Diastolic BP', 'fldinput' => 'Examination', 'fldsave' => '1'])
                ->orderBy('fldid', 'DESC')
                ->first();


            $information[$k]['DiastolicBPExamLimit'] = Examlimit::select('fldunit')
                ->where('fldexamid', 'Systolic BP')
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldptsex', '=', 'Male')
                        ->orWhere('fldptsex', '=', 'Both Sex');
                })
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldagegroup', '=', 'Adolescent')
                        ->orWhere('fldagegroup', '=', 'All Age');
                })
                ->orderBy('fldid', 'DESC')
                ->first();


            $information[$k]['reportedPatLab'] = PatLabTest::where('fldencounterval', $encounter_id)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Reported')
                        ->orWhere('fldstatus', '=', 'Verified');
                })
                ->with(['patTestResults', 'subTest', 'testLimit'])
                ->get();


            $information[$k]['patRadioTest'] = PatRadioTest::where('fldencounterval', $encounter_id)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Reported')
                        ->orWhere('fldstatus', '=', 'Verified');
                })
                ->with(['radioData', 'radioSubTest'])
                ->get();


            /*select fldtime,flditem,fldreportquali,flddetail from tblexamgeneral where fldencounterval='1' and fldinput='Notes' and (flditem='Progress Note' or flditem='Clinicians Note' or flditem='Nurses Note')*/
            $information[$k]['generalExamProgressCliniciansNurses'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                ->where('fldencounterval', $encounter_id)
                ->where(function ($query) {
                    return $query
                        ->orWhere('flditem', '=', 'Progress Note')
                        ->orWhere('flditem', '=', 'Clinicians Note')
                        ->orWhere('flditem', '=', 'Nurses Note');
                })
                ->get();

            /*select fldid,fldtime,fldproblem,fldsubjective,fldobjective,fldassess,fldplan from tblpatplanning where fldencounterval='1' and fldplancategory='IP Monitoring'*/
            $information[$k]['IPMonitoringPatPlanning'] = PatPlanning::select('fldid', 'fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
                ->where('fldencounterval', $encounter_id)
                ->where('fldplancategory', 'IP Monitoring')
                ->get();

            /*select fldid,fldtime,fldproblem,fldsubjective,fldobjective,fldassess,fldplan from tblpatplanning where fldencounterval='1' and fldplancategory='Clinician Plan'*/
            $information[$k]['ClinicianPlanPatPlanning'] = PatPlanning::select('fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
                ->where('fldencounterval', $encounter_id)
                ->where('fldplancategory', 'Clinician Plan')
                ->get();

            /*select fldid,fldnewdate,flditem,flddetail from tblpatgeneral where fldencounterval='1' and fldinput='Procedures' and fldreportquali='Planned'*/
            $information[$k]['patGeneral'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                ->where('fldencounterval', $encounter_id)
                ->where('fldinput', 'Procedures')
                ->where('fldreportquali', 'Planned')
                ->get();

            /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldsave='1' and fldinput='Discharge examinations'*/
            $information[$k]['DischargeExaminationspatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                ->where('fldencounterval', $encounter_id)
                ->where('fldsave', '1')
                ->where('fldinput', 'Discharge examinations')
                ->get();

            /*select flddetail,fldtime from tblexamgeneral where fldencounterval='1' and fldinput='Notes' and flditem='Condition of Discharge'*/
            $information[$k]['ConditionOfDischargeExamGeneral'] = ExamGeneral::select('flddetail', 'fldtime')
                ->where('fldencounterval', $encounter_id)
                ->where('fldinput', 'Notes')
                ->where('flditem', 'Condition of Discharge')
                ->get();

            /*select fldid,fldroute,flditem,flddose,fldfreq,flddays,flditemtype from tblpatdosing where fldencounterval='1' and (fldstatus='Discharged' or fldstatus='LAMA' or fldstatus='Death' or fldstatus='Refer' or fldstatus='Absconder')*/
            $information[$k]['DischargedLAMADeathReferAbsconderPatDosing'] = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'flditemtype')
                ->where('fldencounterval', $encounter_id)
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Discharged')
                        ->orWhere('fldstatus', '=', 'LAMA')
                        ->orWhere('fldstatus', '=', 'Death')
                        ->orWhere('fldstatus', '=', 'Absconder')
                        ->orWhere('fldstatus', '=', 'Refer');
                })
                ->get();

            /*select flddetail,fldtime from tblexamgeneral where fldencounterval='1' and fldinput='Notes' and flditem='Advice on Discharge'*/
            $information[$k]['AdviceOfDischargeExamGeneral'] = ExamGeneral::select('flddetail', 'fldtime')
                ->where('fldencounterval', $encounter_id)
                ->where('fldinput', 'Notes')
                ->where('flditem', 'Advice on Discharge')
                ->get();

            $information[$k]['present_symptoms'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'fldreportquanti', 'flddetail')
                ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Presenting Symptoms', 'fldsave' => '1'])
                ->get();


            $information[$k]['systolic_bp'] = $systolic_bp = DB::table('tblpatientexam')
                ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Systolic BP')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();

            $information[$k]['diasioli_bp'] = $diasioli_bp = DB::table('tblpatientexam')
                ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Diastolic BP')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();

            $information[$k]['pulse'] = $pulse = DB::table('tblpatientexam')
                ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Pulse Rate')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();

            $information[$k]['temperature'] = $temperature = DB::table('tblpatientexam')
                ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Pulse RatePulse Rate')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();

            $information[$k]['respiratory_rate'] = $respiratory_rate = DB::table('tblpatientexam')
                ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Respiratory Rate')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();


            $information[$k]['o2_saturation'] = $o2_saturation = DB::table('tblpatientexam')
                ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'O2 Saturation')
                ->orderBy('tblpatientexam.fldid', 'desc')->first();


            //$information[$k]['drug'] = Drug::select('fldroute','fldstrunit','flddrug','fldcodename')->groupBy('fldroute','fldstrunit','flddrug','fldcodename')->get()->toArray();
            $information[$k]['inserted_pain'] = ExamGeneral::select('fldreportquali')->where('fldencounterval', $encounter_id)
                ->where('flditem', 'Pain Type')->first();

            $information[$k]['Pallor'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Pallor')->first();
            $information[$k]['Icterus'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Icterus')->first();
            $information[$k]['Cyanosis'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Cyanosis')->first();
            $information[$k]['Clubbing'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Clubbing')->first();
            $information[$k]['Oedema'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Oedema')->first();
            $information[$k]['Dehydration'] = PatientExam::where('fldencounterval', $encounter_id)->where('fldhead', 'Dehydration')->first();
            $information[$k]['gcs'] = PatientExam::select('fldrepquali')->where('fldencounterval', $encounter_id)
                ->where('fldhead', 'Glasgrow Coma Scale(GCS)')->first();
            $information[$k]['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();
        }

        $data['patientinfo'] = $patient_info;
        $data['encounters'] = $information;
        //dd($data);


        return view('emergency::pdf.history', $data)/*->setPaper('a4')->stream('ER Sheet.pdf')*/ ;
    }

    function check_vital_emergency(Request $request)
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
                ->where('fldexamid', 'Diastolic BP')
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
                ->where('fldexamid', 'Temperature(F)')
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
        if (!empty($data)) {
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

    public function getEncounterRelatedBed($encounterVal)
    {
        $get_encounter_related_data = Departmentbed::where([
            'fldencounterval' => $encounterVal
        ])->select('fldbed', 'fldencounterval', 'flddept')->get();

        return response()->json([
            'data' => $get_encounter_related_data,
            'message' => 'Successfully.',
            'success' => true,
        ]);
    }


}
