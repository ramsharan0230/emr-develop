<?php

namespace Modules\Inpatient\Http\Controllers;

use App\BillingSet;
use App\Code;
use App\CogentUsers;
use App\Complaints;
use App\Consult;
use App\Department;
use App\Departmentbed;
use App\DiagnoGroup;
use App\Encounter;
use App\Exam;
use App\ExamGeneral;
use App\Examlimit;
use App\NurseDosing;
use App\Otchecklist;
use App\PatFindings;
use App\PatGeneral;
use App\Pathdosing;
use App\PatientExam;
use App\PatientInfo;
use App\PatSubGeneral;
use App\Preanaestheticevaluation;
use App\Referlist;
use App\User;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Utils\Permission;
use App\Year;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use PHPUnit\Exception;
use Session;
use Illuminate\Support\Facades\DB;

class InpatientController extends Controller
{

    public function dashboard () {
        return view('inpatient::inpatient-dashboard');
    }

    public function getInpatientView(Request $request)
    {
        if (Permission::checkPermissionFrontendAdmin('inpatient')) {


            $encounter_id_session = Session::get('inpatient_encounter_id');
            $data = [];


            $data['patient_status_disabled'] = 0;
            $data['cause_of_admission'] = NULL;

            $diagnocat = $this->getInitialDiagnosisCategory();
            $data['digno_group_list'] = array();
            if ($request->has('encounter_id') || $encounter_id_session) {
                if ($request->has('encounter_id'))
                    $encounter_id = $request->get('encounter_id');
                else
                    $encounter_id = $encounter_id_session;

                $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();

//                $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();

                /*department change enabled*/
                /*if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
                    if (!in_array($data['enpatient']->fldcurrlocat, $current_user->department->pluck('flddept')->toArray())) {
                        Session::flash('display_popup_error_success', true);
                        Session::flash('error_message', 'You are not authorized to view this patients information.');
                        return redirect()->route('admin.dashboard');
                    }
                }*/
                session(['inpatient_encounter_id' => $encounter_id]);

                /*create last encounter id*/
                Helpers::inpatientEncounterQueue($encounter_id);
                /*$encounterIds = Options::get('inpatient_last_encounter_id');

                $arrayEncounter = unserialize($encounterIds);*/
                /*create last encounter id*/

                //die('dd');
                $dataflag = array(
                    'fldinside' => 1,
                );

                Encounter::where('fldencounterval', $encounter_id)->update($dataflag);

                $patient_id = $enpatient->fldpatientval;
                $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $encounter_id)->where('fldcode', '!=', null)->get();

                $dischargeCheck = 0;

                if ($enpatient->fldadmission == "Discharged" || $enpatient->fldadmission == "LAMA" || $enpatient->fldadmission == "Death" || $enpatient->fldadmission == "Refer") {
                    //                dd($enpatient->fldadmission);
                    $dischargeCheck = 1;
                }

                $patients = PatientInfo::where('fldpatientval', $patient_id)->first();

                $pathFindingsPDAD = PatFindings::where('fldencounterval', $encounter_id)
                    ->orWhere('fldtype', 'Provisional Diagnosis')
                    ->orWhere('fldtype', 'Allergic Drugs')
                    ->where('fldsave', 1)
                    ->get();

                $data = [
                    'complaint' => Complaints::all(),
                    'patient_status_disabled' => $dischargeCheck,
                    'patient' => $patients,
                    'enpatient' => $enpatient,

                    'billingset' => $billingset = BillingSet::get(),
                    'patient_id' => $patient_id,
                    'enable_freetext' => Options::get('free_text'),
                    'patientexam' => $patientexam_findings = PatientExam::where('fldencounterval', $encounter_id)->where('fldinput', 'OPD Examination')->where('fldsave', '1')->get(),
                    'history' => $history = ExamGeneral::where('fldencounterval', $encounter_id)->where('fldinput', 'History')->where('flditem', 'History of Illness')->first(),
                    'notes' => $notes = ExamGeneral::where('fldencounterval', $encounter_id)->where('fldinput', 'Notes')->where('flditem', 'Initial Planning')->first(),
                    'patdiago' => $patdiago = $pathFindingsPDAD->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->all(),
                    'patdrug' => $patdrug = $pathFindingsPDAD->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->all(),
                    'examgeneral' => $examgeneral = ExamGeneral::where('fldencounterval', $encounter_id)->where('fldinput', 'Presenting Symptoms')->where('fldsave', 1)->get(),
                    'exam_systolic' => $exam_systolic = Exam::where('fldsysconst', 'BloodPressure_Systolic')->get(),
                    'systolic_bp' => $systolic_bp = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldhead', 'Systolic BP')->orderBy('fldid', 'desc')->first(),
                    'exam_diastolic' => $exam_diastolic = Exam::where('fldsysconst', 'BloodPressure_Diastolic')->get(),
                    'diasioli_bp' => $diasioli_bp = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldhead', 'Diastolic BP')->orderBy('fldid', 'desc')->first(),
                    'exam_pulse' => $exam_pulse = Exam::where('fldsysconst', 'Pulse_Rate')->first(),
                    'pulse' => $pulse = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldhead', 'Pulse Rate')->orderBy('fldid', 'desc')->first(),
                    'exam_temperature' => $exam_temperature = Exam::where('fldsysconst', 'Temperature_System')->first(),
                    'temperature' => $temperature = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldhead', 'Pulse RatePulse Rate')->orderBy('fldid', 'desc')->first(),
                    'exam_respiratory_rate' => $exam_respiratory_rate = Exam::where('fldsysconst', 'Respiration_Rate')->first(),
                    'respiratory_rate' => $respiratory_rate = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldhead', 'Respiratory Rate')->orderBy('fldid', 'desc')->first(),
                    'exam_o2_saturation' => $exam_o2_saturation = Exam::where('fldsysconst', 'Oxygen_Saturation')->first(),
                    'o2_saturation' => $o2_saturation = PatientExam::where('fldencounterval', $encounter_id)
                        ->where('fldsave', 1)
                        ->where('fldhead', 'O2 Saturation')
                        ->orderBy('fldid', 'desc')->first(),
                    'consults' => $consult = Consult::where('fldencounterval', $encounter_id)->first(),
                    'digno_group' => $digno_group = DiagnoGroup::select('fldgroupname')->distinct()->get(),

                    'digno_group_list' => $diagnocat,
                    'body_weight' => $body_weight = PatientExam::where('fldencounterval', $encounter_id)
                        ->where('fldsave', 1)
                        ->where('fldsysconst', 'Body_Weight')
                        ->orderBy('fldid', 'desc')
                        ->first(),
                    'examlimit_sys_bp' => Examlimit::where('fldexamid', 'Systolic BP')->first(),
                    'examlimit_dia_bp' => Examlimit::where('fldexamid', 'Diastolic BP')->first(),
                    'examlimit_respi' => Examlimit::where('fldexamid', 'Respiratory Rate')->first(),
                    'examlimit_saturation' => Examlimit::where('fldexamid', 'O2 Saturation')->first(),
                    'consultants' => CogentUsers::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get(),
                    'allergicdrugs' => Code::select('fldcodename')->whereNotIn('fldcodename', $patientallergicdrugs)->get(),
                    'cause_of_admission' => $cause_of_admission = ExamGeneral::where([
                        ['fldencounterval', $encounter_id],
                        ['fldinput', 'History'],
                        ['fldtype', 'Qualitative'],
                        ['flditem', 'Cause of Admission'],
                        ['fldsave', 1],
                    ])->first(),
                    'fluid_list' => Pathdosing::where([
                        ['fldencounterval', $encounter_id],
                        ['fldroute', 'fluid'],
                        ['fldlevel', 'Dispensed'],
                    ])->Where([
                        ['fldcurval', '!=', 'DisContinue'],
                        ['fldcurval', '!=', 'Cancelled'],
                    ])->get(),
                    'fluid_particulars' => NurseDosing::with('getName')->where([
                        ['fldencounterval', $encounter_id],
                        ['fldunit', 'ML/Hour'],
                    ])->get(),
                    'pat_findings' => $pat_findings = PatFindings::where([
                        ['fldencounterval', $encounter_id],
                        ['fldsave', 1],
                    ])->whereIn('fldtype', [
                        'Provisional Diagnosis',
                        'Final Diagnosis',
                        'Allergic Drugs',
                    ])->select('fldcode', 'fldid', 'fldtype')->get(),
                ];
                // dd($data['pat_findings']);
                $data['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();

                /*outcome refer list*/
                $data['referlist'] = Referlist::select('fldlocation')->get();

                if ($patients) {
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
                }

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
                $data['graphData'] = $this->_getGraphData($encounter_id);
                $data['inOutGraphData'] = $this->getInOutGraphData($request);
                $data['bloodPressureData'] = $this->getBloodPressureData($request);

                $data['preanaethestic'] = Preanaestheticevaluation::where('fldencounterval',$encounter_id)->first();
                $data['otchecklistdata'] = $otchecklistdata = Otchecklist::where('fldencounterval', $encounter_id)->first();
                $activeOtChecklistTab = "signin";
                $data['otchecklistcomp'] = 0;
                if (isset($otchecklistdata)) {
                    if ($otchecklistdata->fldsignoutcomp == 1) {
                        $activeOtChecklistTab = "signout";
                        $data['otchecklistcomp'] = 1;
                    } else {
                        if ($otchecklistdata->fldtimeoutcomp == 1) {
                            $activeOtChecklistTab = "signout";
                        } else {
                            if ($otchecklistdata->fldsignincomp == 1) {
                                $activeOtChecklistTab = "timeout";
                            }
                        }
                    }
                }
                $data['activeOtChecklistTab'] = $activeOtChecklistTab;

            }
            $data['departments'] = DB::table('tbldepartment')
                ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
                ->where('tbldepartment.fldcateg', 'Patient Ward')
                ->orWhere('tbldepartment.fldcateg', 'Emergency')
                ->select('tbldepartment.flddept')
                ->groupBy('tbldepartment.flddept')
                ->get();


            return view('inpatient::inPatient', $data);
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }

    private function _getGraphData($encounter_id)
    {
        $all_data = \App\PatientExam::select('fldhead', 'fldrepquali')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldtype' => 'Quantitative',
            ])
            ->where('fldhead', '!=', 'Systolic BP')
            ->where('fldhead', '!=', 'Diastolic BP')
            ->orderBy('fldtime', 'DESC')->get();

        $heads = [];
        $values = [];
        foreach ($all_data as $data) {
            if (!in_array($data->fldhead, $heads)) {
                $heads[] = $data->fldhead;
                $values[] = $data->fldrepquali;
            }
        }

        return compact('heads', 'values');
    }

    public function getInOutGraphData(Request $request)
    {
        $encounter_id = Session::get('inpatient_encounter_id');
        $today_date = Carbon::now()->format('Y-m-d');
        $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();

        if (!$request->has('chartParam') || ($request->has('chartParam') && $request->chartParam == "Year") || ($request->has('chartParam') && $request->chartParam == "Month")) {
            if ($request->chartParam == "Month") {
                $from_date = \Carbon\Carbon::now()->startOfMonth();
                $to_date = \Carbon\Carbon::now();
            } else {
                $from_date = $fiscal_year->fldfirst;
                $to_date = $fiscal_year->fldlast;
            }

            $inoutLists = ExamGeneral::select(\DB::raw('DATE(tblexamgeneral.fldtime) as date'), \DB::raw('ROUND(SUM(tblfoodcontent.fldfluid), 2) as total_fluid'), \DB::raw('ROUND(SUM(tblfoodcontent.fldenergy), 2) as total_energy'), 'tblexamgeneral.fldinput')
                ->leftJoin('tblfoodcontent', 'tblfoodcontent.fldfoodid', '=', 'tblexamgeneral.flditem')
                ->where([
                    'tblexamgeneral.fldencounterval' => $encounter_id,
                    'tblexamgeneral.fldsave' => '1',
                ])
                ->where([
                    ["tblexamgeneral.fldtime", ">=", $from_date],
                    ["tblexamgeneral.fldtime", "<=", $to_date],
                ])
                ->where(function ($query) {
                    $query->where('fldinput', 'Output Fluid');
                    $query->orWhere('fldinput', 'Input Food/Fluid');
                })
                ->groupBy(['date', 'fldinput'])
                ->get()
                ->groupBy(['date', 'fldinput']);

            $data['inputMlStatus'] = [];
            $data['outputMlStatus'] = [];
            $data['statusTitle'] = [];
            foreach ($inoutLists as $date_key => $inoutList) {
                array_push($data['statusTitle'], $date_key);
                if (array_key_exists('Input Food/Fluid', $inoutList->toArray())) {
                    array_push($data['inputMlStatus'], $inoutList['Input Food/Fluid'][0]->total_fluid);
                } else {
                    array_push($data['inputMlStatus'], null);
                }
                if (array_key_exists('Output Fluid', $inoutList->toArray())) {
                    array_push($data['outputMlStatus'], $inoutList['Output Fluid'][0]->total_fluid);
                } else {
                    array_push($data['outputMlStatus'], null);
                }
            }
        } elseif ($request->has('chartParam') && $request->chartParam == "Day") {
            $from_date = Carbon::parse($today_date)->setTime(00, 00, 00);
            $to_date = Carbon::parse($today_date)->setTime(23, 59, 59);
            $inoutLists = ExamGeneral::select('tblfoodcontent.fldfluid', 'tblfoodcontent.fldenergy', 'tblexamgeneral.fldinput', 'tblexamgeneral.fldtime')
                ->leftJoin('tblfoodcontent', 'tblfoodcontent.fldfoodid', '=', 'tblexamgeneral.flditem')
                ->where([
                    'tblexamgeneral.fldencounterval' => $encounter_id,
                    'tblexamgeneral.fldsave' => '1',
                ])
                ->where([
                    ["tblexamgeneral.fldtime", ">=", $from_date],
                    ["tblexamgeneral.fldtime", "<=", $to_date],
                ])
                ->where(function ($query) {
                    $query->where('fldinput', 'Output Fluid');
                    $query->orWhere('fldinput', 'Input Food/Fluid');
                })
                ->get();
            $data['inputMlStatus'] = [];
            $data['outputMlStatus'] = [];
            $data['statusTitle'] = [];
            foreach ($inoutLists as $date_key => $inoutList) {
                if ($inoutList->fldinput == 'Input Food/Fluid') {
                    array_push($data['inputMlStatus'], $inoutList->fldfluid);
                    array_push($data['inputMlStatus'], null);
                } else {
                    array_push($data['outputMlStatus'], $inoutList->fldfluid);
                    array_push($data['outputMlStatus'], null);
                }
            }
        }

        return $data;
    }

    public function getBloodPressureData(Request $request)
    {
        $encounter_id = Session::get('inpatient_encounter_id');
        $today_date = Carbon::now()->format('Y-m-d');
        $fiscal_year = $data['fiscal_year'] = Year::where('fldfirst', '<=', $today_date)->where('fldlast', '>=', $today_date)->first();
        if (!$request->has('chartParam') || ($request->has('chartParam') && $request->chartParam == "Year") || ($request->has('chartParam') && $request->chartParam == "Month")) {
            if ($request->chartParam == "Month") {
                $startTime = \Carbon\Carbon::now()->startOfMonth();
                $endTime = \Carbon\Carbon::now();
            } else {
                $startTime = $fiscal_year->fldfirst;
                $endTime = $fiscal_year->fldlast;
            }
        } else {
            $startTime = Carbon::parse($today_date)->setTime(00, 00, 00);
            $endTime = Carbon::parse($today_date)->setTime(23, 59, 59);
        }
        $all_data = PatientExam::select('fldhead', 'fldrepquali', 'fldtime')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldtype' => 'Quantitative',
            ])
            ->where([
                ["fldtime", ">=", $startTime],
                ["fldtime", "<=", $endTime],
            ])
            ->whereIn('fldhead', ['Systolic BP', 'Diastolic BP'])
            ->orderBy('fldtime', 'DESC')
            ->get();
        $data['systolicData'] = [];
        $data['diastolicData'] = [];
        $data['testDateData'] = [];
        foreach ($all_data as $testdata) {
            if ($testdata->fldhead == "Systolic BP") {
                array_push($data['systolicData'], $testdata->fldrepquali);
                array_push($data['diastolicData'], null);
            } else {
                array_push($data['diastolicData'], $testdata->fldrepquali);
                array_push($data['systolicData'], null);
            }
            array_push($data['testDateData'], $testdata->fldtime);
        }

        $startTime = Carbon::parse($today_date)->setTime(00, 00, 00);
        $endTime = Carbon::parse($today_date)->setTime(23, 59, 59);
        $data['avg_systolic'] = PatientExam::select('fldhead', 'fldrepquali', 'fldtime')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldtype' => 'Quantitative',
            ])
            ->where([
                ["fldtime", ">=", $startTime],
                ["fldtime", "<=", $endTime],
            ])
            ->where('fldhead', 'Systolic BP')
            ->orderBy('fldtime', 'DESC')
            ->avg('fldrepquali', 2);

        $data['avg_diastolic'] = PatientExam::select('fldhead', 'fldrepquali', 'fldtime')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldtype' => 'Quantitative',
            ])
            ->where([
                ["fldtime", ">=", $startTime],
                ["fldtime", "<=", $endTime],
            ])
            ->where('fldhead', 'Diastolic BP')
            ->orderBy('fldtime', 'DESC')
            ->avg('fldrepquali', 2);

        return $data;
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
                    $html .= '<tr><td>';
                    $html .= '<input type="checkbox" class="dccat" name="dccat" value="' . $dc['fldcodeid'] . '" id="' . $dc['fldcodeid'] . '"/>';
                    $html .= '<label for="' . $dc['fldcodeid'] . '" class="remove_some_css"></label></td><td>' . $dc['fldcodeid'] . '</td><td>' . $dc['flditemname'] . '</td></tr>';
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

    public function getInitialDiagnosisCategoryAjaxs()
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
                $html .= '<tr><td><input type="checkbox" class="dccat" name="dccat" id="' . $d['code'] . '" value="' . $d['code'] . '"/>';
                $html .= '<label for="' . $d['code'] . '" class="remove_some_css"></label>';
                $html .= '</td><td>' . $d['code'] . '</td><td>' . $d['name'] . '</td></tr>';
            }
        } else {
            $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
        }
        echo $html;
    }

    public function getDiagnosisByCodes(Request $request)
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
                    $html .= '<tr><td><input type="checkbox" class="diagnosissub" name="diagnosissub" id="' . $d . '" value="' . $d . '"/>';
                    $html .= '<label for="' . $d . '" class="remove_some_css"></label>';
                    $html .= '</td><td>' . $d . '</td</tr>';
                }
            } else {
                $html = '<tr colspan="2"><td>No Diagnosis Available for Diagnosis Code ' . $key . '</td></tr>';
            }
            echo $html;
        } else {
            echo $html = '<tr colspan="2"><td>No Diagnosis Available</td></tr>';
        }
    }

    function diagnosisStore(Request $request)
    {

        try {
            $mytime = Carbon::now();
            $data['fldencounterval'] = $request->encounter_id;
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
            $patdiago = PatFindings::where('fldencounterval', $request->encounter_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
            $html = '';
            if (isset($patdiago) and count($patdiago) > 0) {
                foreach ($patdiago as $key => $value) {
                    $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
                }
            }
            echo $html;
            exit;
        } catch (\Exception $e) {
            session()->flash('error_message', __('Error While Adding Diagnosis'));
            return redirect()->route('inpatient');
        }
    }


    // Provisional Dliagnosis Soft Delete
    public function deleteProvisional()
    {
        // $data = PatFindings::where([
        //     ['fldid', Input::get('fldid')],
        //     ['fldencounterval', Input::get('encounterId')],
        //     ['fldsave', 1],
        // ])->first();

        // $data->fldsave = 0;
        // $data->xyz     = 0;
        // $data->save();
        // return response()->json($data);
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
            // dd($e);
            $data['error'] = 1;

            return $data;
        }
    }

    // Final Dliagnosis Soft Delete
    public function deleteFinal()
    {
        // $data = PatFindings::where([
        //     ['fldid', Input::get('fldid')],
        //     ['fldencounterval', Input::get('encounterId')],
        //     ['fldsave', 1],
        // ])->first();

        // $data->fldsave = 0;
        // $data->xyz     = 0;
        // $data->save();
        // return response()->json($data);
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
            // dd($e);
            $data['error'] = 1;

            return $data;
        }
    }

    // Allergic Soft Delete
    public function deleteAllergic()
    {
        // $data = PatFindings::where([
        //     ['fldid', Input::get('fldid')],
        //     ['fldencounterval', Input::get('encounterId')],
        //     ['fldsave', 1],
        // ])->first();

        // $data->fldsave = 0;
        // $data->xyz     = 0;
        // $data->save();
        // return response()->json($data);

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
            // dd($e);
            $data['error'] = 1;

            return $data;
        }
    }

    public function getDiagnosisfreetext(Request $request)
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

        $html = view('inpatient::layouts.dynamic-views.diagnosis-freetext', $data)->render();
        return $html;
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

        $html = view('inpatient::layouts.dynamic-views.final-diagnosis-freetext', $data)->render();
        return $html;
    }

    public function saveDiagnosisCustom(Request $request)
    {
        try {
            // dd($request);
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

                return redirect()->route('inpatient');

            }

        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Diagnosis'));

            return redirect()->route('inpatient');
        }
    }


    public function getObstetricData(Request $request)
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
            ['fldtype', 'Provisional Diagnosis'],
            ['fldcodeid', 'Obstetrics'],
            ['fldcode', '!=', ''],
            ['fldencounterval', $encounter_id]
        ])->first();

        // dd($data);

        $html = view('inpatient::layouts.dynamic-views.obstetric-data', $data)->render();
        return $html;
    }

    public function saveObstetricRequest(Request $request)
    {
        // echo "here obstetric save function"; exit;
        //        return $request->all();
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
                $patData['flduserid'] = Helpers::getCurrentUserName(); //need check
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
            $patdiago = PatFindings::where('fldencounterval', $encounterId)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
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
            return redirect()->route('inpatient');
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

        $html = view('inpatient::layouts.dynamic-views.final-obstetric-data', $data)->render();
        return $html;
    }

    public function saveFinalObstetricRequest(Request $request)
    {
        // echo "here obstetric save function"; exit;
        //        return $request->all();
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
            $patdiago = PatFindings::where('fldencounterval', $encounterId)->where('fldtype', 'Final Diagnosis')->where('fldsave', 1)->get();
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
            return redirect()->route('inpatient');
        }
    }


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
                $html .= '<li class="list-group-item"><input type="checkbox" id="' . $ad->fldcodename . '" value="' . $ad->fldcodename . '" class="fldcodename" name="allergydrugs[]" />';
                $html .= '<label class="remove_some_css" for="' . $ad->fldcodename . '"></label>' . $ad->fldcodename . '</li>';
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
                $html .= '<li class="list-group-item"><input type="checkbox" id="' . $ad->fldcodename . '" value="' . $ad->fldcodename . '" class="fldcodename" name="allergydrugs[]" />';
                $html .= '<label class="remove_some_css" for="' . $ad->fldcodename . '"></label>' . $ad->fldcodename . '</li>';
            }
        } else {
            $html = '<li class="list-group-item">No Drugs Available For' . $searchtext . '</li>';
        }
        echo $html;
    }

    function insert_allergydrugstore(Request $request)
    {

        try {
            $allergicdrugs = $request->allergydrugs;
            $mytime = Carbon::now();

            if (isset($allergicdrugs) && count($allergicdrugs) > 0) {
                foreach ($allergicdrugs as $ad) {
                    // echo $ad; exit;
                    $data['fldencounterval'] = $request->encounter_id;
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

                $patdrug = PatFindings::where('fldencounterval', $request->encounter_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
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

                return redirect()->route('inpatient');
            }
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Allergic Drugs'));
            return redirect()->route('inpatient');
        }
    }

    function finalDiagnosisStore(Request $request)
    {
        try {
            $mytime = Carbon::now();
            $data['fldencounterval'] = $request->encounter_id;
            $data['fldtype'] = 'Final Diagnosis';
            $data['fldcode'] = $request->diagnosissubname;
            $data['fldcodeid'] = $request->dccat;
            $data['flduserid'] = Helpers::getCurrentUserName();
            $data['fldtime'] = $mytime->toDateTimeString();
            $data['fldcomp'] = Helpers::getCompName();
            $data['fldsave'] = 1;
            $data['xyz'] = 0;
            $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
            PatFindings::insert($data);
            $patdiago = PatFindings::where('fldencounterval', $request->encounter_id)->where('fldtype', 'Final Diagnosis')->where('fldsave', 1)->get();
            $html = '';
            if (isset($patdiago) and count($patdiago) > 0) {
                foreach ($patdiago as $key => $value) {
                    $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
                }
            }
            echo $html;
            exit;
        } catch (\Exception $e) {
            session()->flash('error_message', __('Error While Adding Diagnosis'));
            return redirect()->route('inpatient');
        }
    }

    public function getPhotographForm(Request $request)
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

        $html = view('inpatient::layouts.dynamic-views.photograph-form', $data)->render();
        return $html;
    }

    public function savePhotograph(Request $request)
    {
        // echo "here"; exit;
        $image = $request->image;
        if (isset($image)) {
            $data = $request->image;
            $image_array_1 = explode(";", $data);
            $image_array_2 = explode(",", $image_array_1[1]);
            $data = base64_decode($image_array_2[1]);

            $path = asset('uploads/outpatient/full/');
            $imageName = time() . '.png';
            File::put(public_path('uploads/outpatient/full/' . $imageName), $data);

            echo '<img src="' . $path . '/' . $imageName . '" class="img-thumbnail" />';
        }
    }


    public function resetEncounter()
    {
        Session::forget('inpatient_encounter_id');
        return redirect()->route('inpatient');
    }


    public function saveOtSignin(Request $request)
    {
        try {
            $data = [
                "fldencounterval" => $request->encounter_id,
                "fldpatientconfirm" => $request->fldpatientconfirm,
                "fldsitemarked" => $request->fldsitemarked,
                "fldanaesthesiachecked" => $request->fldanaesthesiachecked,
                "fldoxyfunct" => $request->fldoxyfunct,
                "fldhasallergy" => $request->fldhasallergy,
                "fldairwayrisk" => $request->fldairwayrisk,
                "fldbloodlossrisk" => $request->fldbloodlossrisk,
                "fldsigninuser" => Helpers::getCurrentUserName(),
                "fldsignintime" => now(),
                "fldsignincomp" => 1,
            ];
            $chkOtchecklistdata = Otchecklist::where('fldencounterval', $request->encounter_id)->first();
            if (isset($chkOtchecklistdata)) {
                Otchecklist::where('fldencounterval', $request->encounter_id)->update($data);
            } else {
                Otchecklist::create($data);
            }
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function saveOtTimeout(Request $request)
    {
        try {
            $data = [
                "fldencounterval" => $request->encounter_id,
                "fldconfirmteam" => $request->fldconfirmteam,
                "fldverbalconfirm" => $request->fldverbalconfirm,
                "fldsurgeonreview" => $request->fldsurgeonreview,
                "fldanaesthesianreview" => $request->fldanaesthesianreview,
                "fldnursingreview" => $request->fldnursingreview,
                "fldantibioticprophyloxis" => $request->fldantibioticprophyloxis,
                "fldimagingdisplay" => $request->fldimagingdisplay,
                "fldtimeoutuser" => Helpers::getCurrentUserName(),
                "fldtimeouttime" => now(),
                "fldtimeoutcomp" => 1,
            ];
            $chkOtchecklistdata = Otchecklist::where('fldencounterval', $request->encounter_id)->first();
            if (isset($chkOtchecklistdata)) {
                Otchecklist::where('fldencounterval', $request->encounter_id)->update($data);
            } else {
                Otchecklist::create($data);
            }
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function saveOtSignout(Request $request)
    {
        try {
            $data = [
                "fldencounterval" => $request->encounter_id,
                "fldprocrecord" => $request->fldprocrecord,
                "fldinstrucorrect" => $request->fldinstrucorrect,
                "fldspecimentlabelled" => $request->fldspecimentlabelled,
                "fldequipprobaddressed" => $request->fldequipprobaddressed,
                "fldkeyconcernreview" => $request->fldkeyconcernreview,
                "fldsignoutuser" => Helpers::getCurrentUserName(),
                "fldsignouttime" => now(),
                "fldsignoutcomp" => 1,
            ];
            $chkOtchecklistdata = Otchecklist::where('fldencounterval', $request->encounter_id)->first();
            if (isset($chkOtchecklistdata)) {
                Otchecklist::where('fldencounterval', $request->encounter_id)->update($data);
            } else {
                Otchecklist::create($data);
            }
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function getComponents()
    {
        $flditemid = Input::get('flditemid');
        $fldencounterval = Input::get('fldencounterval');
        $get_list_detail = PatSubGeneral::where([
            ['fldencounterval', $fldencounterval],
            ['flditemid', $flditemid]
        ])->select('fldreportquali')->get();
        return response()->json($get_list_detail);
    }

    public function getDetails()
    {
        $fldid = Input::get('fldid');
        $fldencounterval = Input::get('fldencounterval');
        $get_list_detail = PatGeneral::where([
            ['fldencounterval', $fldencounterval],
            ['fldid', $fldid]
        ])->select('flddetail')->get();
        return response()->json($get_list_detail);
    }

    public function savePreAnaethesticEvaluation(Request $request)
    {

        try {
            DB::beginTransaction();

            $data = [
                "fldencounterval" => $request->get('encounter_id'),
                "past_anaesthetic_date" => $request->get('past_anaesthetic_date') ? Helpers::dateNepToEng(Carbon::parse($request->get('past_anaesthetic_date'))->format('Y-m-d'))->full_date : null,
                "surgerical_procedure" => $request->get('surgerical_procedure'),
                "hospital_name" => $request->get('hospital_name'),
                "anesthesia_type" => $request->get('anesthesia_type'),
                "reaction" => $request->get('reaction'),
                "has_family_reaction" => $request->get('has_family_reaction'),
                "smoke" => $request->get('smoke'),
                "smoke_description" => $request->get('smoke_description'),
                "have_cough" => $request->get('have_cough'),
                "regular_medicine" => $request->get('regular_medicine'),
                "drug_user" => $request->get('drug_user'),
                "have_allergies" => $request->get('have_allergies'),
                "allergy_description" => $request->get('allergy_description'),
                "loose_teeth" => $request->get('loose_teeth'),
                "has_pregnancy" => $request->get('has_pregnancy'),
                "pregnancy_lmp" => $request->get('pregnancy_lmp'),
                "physical_examination" => $request->get('physical_examination'),
                "tmj" => $request->get('tmj'),
                "neck_mobility" => $request->get('neck_mobility'),
                "neck_mobility_two" => $request->get('neck_mobility_two'),
                "thyromental_distance" => $request->get('thyromental_distance'),
//                "rt_lung" => $request->get('rt_lung'),
//                "lt_lung" => $request->get('lt_lung'),
                "rate" => $request->get('rate'),
                "rhythm" => $request->get('rhythm'),
                "additional_sound" => $request->get('additional_sound'),
                "rt_air_entry" => $request->get('rt_air_entry'),
                "rt_rales" => $request->get('rt_rales'),
                "rt_ronchi" => $request->get('rt_ronchi'),
                "rt_wheeze" => $request->get('rt_wheeze'),
                "lt_air_entry" => $request->get('lt_air_entry'),
                "lt_rales" => $request->get('lt_rales'),
                "lt_ronchi" => $request->get('lt_ronchi'),
                "lt_wheeze" => $request->get('lt_wheeze'),
                "heamoglobin" => $request->get('heamoglobin'),
                "tc_dc" => $request->get('tc_dc'),
                "p" => $request->get('p'),
                "l" => $request->get('l'),
                "e" => $request->get('e'),
                "m" => $request->get('m'),
                "b" => $request->get('b'),
                "bt_ct" => $request->get('bt_ct'),
                "pt" => $request->get('pt'),
                "toal_protien" => $request->get('toal_protien'),
                "billiribin" => $request->get('billiribin'),
                "urine_analysis" => $request->get('urine_analysis'),
                "elctorlytes" => $request->get('elctorlytes'),
                "k" => $request->get('k'),
                "blood_urea" => $request->get('blood_urea'),
                "creatioin" => $request->get('creatioin'),
                "blood_sugar" => $request->get('blood_sugar'),
                "blood_group" => $request->get('blood_group'),
                "rh_factor" => $request->get('rh_factor'),
                "cross_matched_blood" => $request->get('cross_matched_blood'),
                "x_ray" => $request->get('x_ray'),
                "x_ray_findings" => $request->get('x_ray_findings'),
                "ecg" => $request->get('ecg'),
                "ecg_finding" => $request->get('ecg_finding'),
                "echocardiograohy" => $request->get('echocardiograohy'),
                "echocardiograohy_finding" => $request->get('echocardiograohy_finding'),
                "asa_grading" => $request->get('asa_grading'),
            ];
            Preanaestheticevaluation::create($data);
            DB::commit();
            return response()->json(['message'=> 'Data Saved Successfully']);
//            $laboratory_data = [
//
//                "heamoglobin" => $request->get('heamoglobin'),
//                "tc_dc" => $request->get('tc_dc'),
//                "p" => $request->get('p'),
//                "l" => $request->get('l'),
//                "e" => $request->get('e'),
//                "m" => $request->get('m'),
//                "b" => $request->get('b'),
//                "bt_ct" => $request->get('bt_ct'),
//                "pt" => $request->get('pt'),
//                "toal_protien" => $request->get('toal_protien'),
//                "billiribin" => $request->get('billiribin'),
//                "urine_analysis" => $request->get('urine_analysis'),
//                "elctorlytes" => $request->get('elctorlytes'),
//                "k" => $request->get('k'),
//                "blood_urea" => $request->get('blood_urea'),
//                "creatioin" => $request->get('creatioin'),
//                "blood_sugar" => $request->get('blood_sugar'),
//                "blood_group" => $request->get('blood_group'),
//                "rh_factor" => $request->get('rh_factor'),
//                "cross_matched_blood" => $request->get('cross_matched_blood'),
//                "x_ray" => $request->get('x_ray'),
//                "x_ray_findings" => $request->get('x_ray_findings'),
//                "ecg" => $request->get('ecg'),
//                "ecg_finding" => $request->get('ecg_finding'),
//                "echocardiograohy" => $request->get('echocardiograohy'),
//                "echocardiograohy_finding" => $request->get('echocardiograohy_finding'),
//                "asa_grading" => $request->get('asa_grading'),
//                "encounter_id" => $request->get('encounter_id'),
//
//            ];



        } catch (Exception $exception) {
            DB::rollBack();
//            return response()->json(['error'=>'Something Went Wrong']);
            dd($exception);
        }
    }

}
