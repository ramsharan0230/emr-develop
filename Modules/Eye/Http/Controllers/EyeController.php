<?php

namespace Modules\Eye\Http\Controllers;

use App\CogentUsers;
use App\EyeImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\BillingSet;
use App\Complaints;
use App\DiagnoGroup;
use App\Encounter;
use App\Exam;
use App\PatientExam;
use App\PatientInfo;
use App\Test;
use App\User;
use App\ExamGeneral;
use App\Code;
use Cache;
use App\Department;
use App\Departmentbed;
use App\PatFindings;
use Illuminate\Support\Facades\DB;
use App\Utils\Helpers;
use App\Utils\Options;

use Carbon\Carbon;

use Illuminate\Routing\Controller;

use Session;

class EyeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $data = [
                'laboratory' => $laboratory = Test::get(),
                
                'finding' => $finding = Exam::get(),
                'billingset' => $billingset = BillingSet::get(),
                'diagnosisgroup' => DiagnoGroup::select('fldgroupname')->distinct()->get(),
                'diagnosiscategory' => Helpers::getInitialDiagnosisCategory(),
                'patient_status_disabled' => 0,
                'chiefComplationDuration' => Helpers::getChiefComplationDuration(),
                'chiefComplationQuali' => Helpers::getChiefComplationQuali(),
            ];
            $data['complaint'] = $complaint = Cache::remember('conplaints_list', 60 * 60 * 24, function () {
                    return Complaints::get();
                });
            // dd($data['diagnosiscategory']);
            $data['departments'] =  DB::table('tbldepartment')
                ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
                ->where('tbldepartment.fldcateg', 'Patient Ward')
                ->select('tbldepartment.flddept')
                ->groupBy('tbldepartment.flddept')
                ->get();



            $encounter_id_session = Session::get('eye_encounter_id');
            if ($request->has('encounter_id') || $encounter_id_session) {
                if ($request->has('encounter_id'))
                    $encounter_id = $request->get('encounter_id');
                else
                    $encounter_id = $encounter_id_session;

                session(['eye_encounter_id' => $encounter_id]);
                $data['exam'] = $this->_get_exam_data($encounter_id);

                /*create last encounter id*/
                Helpers::eyeEncounterQueue($encounter_id);
                $encounterIds = Options::get('eye_last_encounter_id');

                $arrayEncounter = unserialize($encounterIds);
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
                        Session::forget('eye_encounter_id');
                        return redirect()->route('admin.dashboard');
                    }
                }*/

                $data['patient_status_disabled'] = $enpatient->fldadmission == "Discharged" ? 1 : 0;
                $data['examgeneral'] = ExamGeneral::where([
                    'fldencounterval' => $encounter_id,
                    'fldinput' => 'Presenting Symptoms',
                    'fldsave' => '1',
                ])->get();

                foreach ($data['examgeneral'] as &$general) {
                    if ($general->fldreportquanti <= 24)
                        $general->fldreportquanti = "{$general->fldreportquanti} hr";
                    elseif ($general->fldreportquanti > 24 && $general->fldreportquanti <= 720)
                        $general->fldreportquanti = round($general->fldreportquanti / 24, 2) . " Days";
                    elseif ($general->fldreportquanti > 720 && $general->fldreportquanti < 8760)
                        $general->fldreportquanti = round($general->fldreportquanti / 720, 2) . " Months";
                    elseif ($general->fldreportquanti >= 8760)
                        $general->fldreportquanti = round($general->fldreportquanti / 8760) . " Years";
                }

                $patient_id = $enpatient->fldpatientval;
                $data['enable_freetext'] = Options::get('free_text');
                $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
                $data['patient_id'] = $patient_id;
                $data['consultants'] = User::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();
                $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $encounter_id)->where('fldcode', '!=', null)->get();
                $data['allergicdrugs'] = Code::select('fldcodename')->whereNotIn('fldcodename', $patientallergicdrugs)->get();
                $data['patdrug'] = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();

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
                $data['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();

                $data['patdiago'] = $patdiago = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
                if (isset($body_height) && isset($body_weight)) {
                    $hei = ($body_height->fldrepquali / 100); //changing in meter
                    $divide_bmi = ($hei * $hei);
                    if ($divide_bmi > 0) {

                        $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                    }
                }
            }

            return view('eye::index', $data);
        } catch (\GearmanException $e) {
        }
    }

    public function resetEncounter()
    {
        Session::forget('eye_encounter_id');
        return redirect()->route('eye');
    }

    public function store(Request $request)
    {
        $encounter_id = Session::get('eye_encounter_id');
        if (!$encounter_id)
            return redirect()->route('eye')->with('error_message', __('Failed to update eye data.'));

        $inputs = $request->all();
        $exams = [
            'Color_Vision',
            'Previous_Glass_Precribtion_(PGP)',
            'Auto_Reaction',
            'Add',
            'Acceptance',
            'Schicmers_Test',
            'K-Reading',
        ];
        $time = date('Y-m-d H:i:s');
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $computer = \App\Utils\Helpers::getCompName();

        try {
            \DB::beginTransaction();

            // Visual Acuity
            if (isset($inputs['exam']['Visual_Activity'])) {
                foreach ($inputs['exam']['Visual_Activity'] as $fldcategory => $category) {
                    if ($category) {
                        foreach ($category as $fldsubcategroy => $subcategpry) {
                            if ($subcategpry) {
                                foreach ($subcategpry as $fldlocation => $fldreading) {
                                    \App\VisualActivity::updateOrCreate([
                                        'fldencounterval' => $encounter_id,
                                        'fldcategory' => $fldcategory,
                                        'fldsubcategory' => $fldsubcategroy,
                                        'fldlocation' => $fldlocation
                                    ], [
                                        'fldreading' => $fldreading,
                                        'flduserid' => $userid,
                                        'fldtime' => $time,
                                        'fldcomp' => $computer,
                                        'fldsave' => '1',
                                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            // Intracular Pressure
            if (isset($inputs['exam']['Intracular_Pressure'])) {
                foreach ($inputs['exam']['Intracular_Pressure'] as $fldcategory => $category) {
                    if ($category) {
                        foreach ($category as $fldlocation => $fldreading) {
                            \App\IntracularPressure::updateOrCreate([
                                'fldencounterval' => $encounter_id,
                                'fldcategory' => $fldcategory,
                                'fldlocation' => $fldlocation
                            ], [
                                'fldreadingprefix' => $fldreading['readingprefix'],
                                'fldreading' => $fldreading['reading'],
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'fldsave' => '1',
                                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                            ]);
                        }
                    }
                }
            }

            // Other exams
            foreach ($exams as $exam) {
                $formated_exam = str_replace("_", " ", $exam);
                if (!isset($inputs['exam'][$exam]))
                    continue;

                foreach ($inputs['exam'][$exam] as $fldexamtype => $examtype) {
                    if ($examtype) {
                        foreach ($examtype as $fldlocation => $fldreading) {
                            \App\EyeExam::updateOrCreate([
                                'fldencounterval' => $encounter_id,
                                'fldexam' => $formated_exam,
                                'fldexamtype' => $fldexamtype,
                                'fldlocation' => $fldlocation
                            ], [
                                'fldreading' => $fldreading,
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'fldsave' => '1',
                                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                            ]);
                        }
                    }
                }
            }

            // Note and advice
            foreach ($inputs['examgeneral'] as $key => $value) {
                $formated_key = ucwords($key);
                \App\ExamGeneral::updateOrCreate([
                    'fldencounterval' => $encounter_id,
                    'fldinput' => $formated_key
                ], [
                    'fldreportquali' => $value,
                    'flduserid' => $userid,
                    'fldtime' => $time,
                    'fldcomp' => $computer,
                    'fldsave' => '1',
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ]);
            }

            $leftEye = '';
            if ($request->left_eye != "") {
                /*$dataLeftEye = explode(',', $request->left_eye);
                $content = base64_decode($dataLeftEye[1]);*/
                $leftEye = $request->left_eye;
            }

            $rightEye = '';
            if ($request->right_eye != "") {
                $rightEye = $request->right_eye;
            }

            if ($request->left_eye != "" || $request->right_eye != "") {
                EyeImage::updateOrCreate(
                    ['fldencounterval' => $encounter_id],
                    ['left_eye' => $leftEye, 
                     'right_eye' => $rightEye,
                     'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ]
                );
            }

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();

            session()->flash('error_message', __('Failed to update eye data.'));
            return redirect()->route('eye');
        }

        session()->flash('success_message', __('Successfully updated eye data.'));
        return redirect()->route('eye');
    }

    private function _get_exam_data($encounter_id)
    {
        $tblVisualActivity = \App\VisualActivity::where('fldencounterval', $encounter_id)->get();
        $tblIntracularPressure = \App\IntracularPressure::where('fldencounterval', $encounter_id)->get();
        $tblEyeExam = \App\EyeExam::where('fldencounterval', $encounter_id)->get();
        $EyeImage = \App\EyeImage::select('left_eye', 'right_eye')->where('fldencounterval', $encounter_id)->first();
        $tblOtherData = \App\ExamGeneral::where('fldencounterval', $encounter_id)
            ->whereIn('fldinput', [
                'Systemic Illiness', 'Current Medication', 'History Past', 'History Family', 'On Examination Right', 'On Examination Left', 'note', 'advice', 'Procedure',
            ])->pluck('fldreportquali', 'fldinput');

        $otherData = [];
        foreach ($tblOtherData as $key => $value) {
            $key = str_replace(' ', '_', $key);
            $otherData[strtolower($key)] = $value;
        }

        $visualActivityData = [];
        foreach ($tblVisualActivity as $visualActivity) {
            $visualActivityData[$visualActivity->fldcategory][$visualActivity->fldsubcategory][$visualActivity->fldlocation] = $visualActivity->fldreading;
        }
        $intracularPressureData = [];
        foreach ($tblIntracularPressure as $intracularPressure) {
            $intracularPressureData[$intracularPressure->fldcategory][$intracularPressure->fldlocation]['readingprefix'] = $intracularPressure->fldreadingprefix;
            $intracularPressureData[$intracularPressure->fldcategory][$intracularPressure->fldlocation]['reading'] = $intracularPressure->fldreading;
        }

        $eyeExamData = [];
        foreach ($tblEyeExam as $eyeExam) {
            $formated_exam = str_replace(' ', '_', $eyeExam->fldexam);
            $eyeExamData[$formated_exam][$eyeExam->fldexamtype][$eyeExam->fldlocation] = $eyeExam->fldreading;
        }

        return compact('visualActivityData', 'intracularPressureData', 'eyeExamData', 'otherData', 'EyeImage');
    }

    public function examgeneral(Request $request)
    {
        $encounter_id = Session::get('eye_encounter_id');
        $time = date('Y-m-d H:i:s');
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $computer = \App\Utils\Helpers::getCompName();

        try {
            foreach ($request->all() as $key => $value) {
                $formated_key = ucwords(str_replace('_', ' ', $key));
                \App\ExamGeneral::updateOrCreate([
                    'fldencounterval' => $encounter_id,
                    'fldinput' => $formated_key
                ], [
                    'fldreportquali' => $value,
                    'flduserid' => $userid,
                    'fldtime' => $time,
                    'fldcomp' => $computer,
                    'fldsave' => '1',
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ]);
            }
        } catch (Exception $e) {
            session()->flash('success_message', __('Failed to update eye data.'));
            return redirect()->route('eye');
        }

        session()->flash('success_message', __('Successfully updated eye data.'));
        return redirect()->route('eye');
    }
}
