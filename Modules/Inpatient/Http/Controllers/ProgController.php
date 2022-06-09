<?php

namespace Modules\Inpatient\Http\Controllers;

use App\CompExam;
use App\Encounter;
use App\Examlimit;
use App\ExamOption;

use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Exception;
use App\PatPlanning;
use App\PatientExam;

class ProgController extends Controller
{
    public function getTime(Request $request)
    {
        $date = $request->get('date');
        $date = ($date) ?: date('Y-m-d');

        $encounter_id = \Session::get('inpatient_encounter_id');
        $times = PatPlanning::select('fldid', 'fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
            ->where([
                ['fldencounterval', $encounter_id],
                ['fldplancategory', 'IP Monitoring'],
                ['fldtime', '>=', "$date 00:00:00"],
                ['fldtime', '<=', "$date 23:59:59.999"],
            ])->get();

        $ret_data = [];
        foreach ($times as $fluid) {
            $fluid->time = explode(' ', $fluid->fldtime)[1];
            $ret_data[] = $fluid;
        }

        return response()->json($ret_data);
    }

    public function getTimeData(Request $request)
    {
        $fldid = $request->get('fldid');
        $ret_data = PatPlanning::select('fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
            ->where([
                'fldid' => $fldid,
            ])->first();

        $ret_data->exams = PatientExam::select('fldhead', 'fldabnormal', 'fldrepquali', 'fldrepquanti', 'fldid', 'fldtime', 'flduserid', 'fldcomp')
            ->where([
                'fldinput' => "IP Monitoring:$fldid",
                'fldsave' => '1',
            ])->get();

        return response()->json($ret_data);
    }

    public function addTime()
    {
        try {
            $encounter_id = \Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = Helpers::getCompName();

            $timeId = PatPlanning::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldplancategory' => 'IP Monitoring',
                'fldproblem' => NULL,
                'fldsubjective' => NULL,
                'fldobjective' => NULL,
                'fldassess' => NULL,
                'fldplan' => 'L',
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'flduptime' => NULL,
                'xyz' => '0',
            ]);

            $time = explode(' ', $time)[1];
            return response()->json([
                'status' => TRUE,
                'data' => [
                    'fldid' => $timeId,
                    'time' => $time,
                ],
                'message' => 'Successfully saved Time.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save Time.',
            ]);
        }
    }

    public function addTextData(Request $request)
    {
        $preset_types = [
            'Problems' => 'fldsubjective',
            'Treatment' => 'fldobjective',
            'Input/Output' => 'fldassess',
            'PlanningText' => 'fldplan',
            'PlanningDrop' => 'fldproblem',
        ];
        try {
            $type = $request->get('type');
            if (!isset($preset_types[$type]))
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Invalid data.',
                ]);

            $encounter_id = \Session::get('inpatient_encounter_id');
            $fldId = $request->get('fldid');

            PatPlanning::where([
                'fldencounterval' => $encounter_id,
                'fldid' => $fldId
            ])->update([
                $preset_types[$type] => $request->get('text'),
            ]);

            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved Data.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save Data.',
            ]);
        }
    }

    public function getExaminationSelectData()
    {
        $exams = CompExam::select('tblcompexam.fldid', 'tblcompexam.fldtype', 'tblcompexam.fldexamid', 'tblexam.fldoption')
            ->join('tblexam', 'tblcompexam.fldexamid', '=', 'tblexam.fldexamid')
            ->where([
                'tblcompexam.fldcomp' => Helpers::getCompName(),
                'tblcompexam.fldcategory' => 'Nursing examinations',
            ])->get();
        return response()->json($exams);
    }

    public function getExaminationSelectOptions(Request $request)
    {
        return response()->json(
            ExamOption::select('fldanswer')->where([
                'fldexamid' => $request->get('examid'),
            ])->orderBy('fldindex')->get()
        );
    }

    public function saveExaminationData(Request $request)
    {
        try {
            $text = $request->get('text');
            $examtype = $text ? 'Qualitative' : 'Quantitative';
            $quantity = $request->get('quantity');
            $examinationid = $request->get('examinationid');
            $fldid = $request->get('fldid');

            $encounter_id = \Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = Helpers::getCompName();

            $patientExamId = PatientExam::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldserialval' => NULL,
                'fldinput' => "IP Monitoring:$fldid",
                'fldtype' => $examtype,
                'fldhead' => $examinationid,
                'fldsysconst' => NULL,
                'fldmethod' => 'Regular',
                'fldrepquali' => $text,
                'fldrepquanti' => ($examtype === 'Quantitative') ? $quantity : '0',
                'fldfilepath' => NULL,
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'fldabnormal' => '0',
                'flduptime' => NULL,
                'xyz' => '0',
            ]);

            return response()->json([
                'status' => TRUE,
                'data' => [
                    'fldhead' => $examinationid,
                    'fldabnormal' => '0',
                    'fldrepquali' => $text,
                    'fldrepquanti' => ($examtype === 'Quantitative') ? $quantity : '0',
                    'fldid' => $patientExamId,
                    'fldtime' => $time,
                    'flduserid' => $userid,
                    'fldcomp' => $computer,
                ],
                'message' => 'Successfully saved examinations.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save examinations.',
            ]);
        }
    }

    public function getEssentialList(Request $request)
    {
        $encounter_id = $request->get('encounterId') ?: \Session::get('inpatient_encounter_id');
        return response()->json(
            PatientExam::select('fldid', 'fldtime', 'fldtype', 'fldhead', 'fldabnormal', 'fldrepquanti')->where([
                'fldencounterval' => $encounter_id,
                'fldsave' => '1',
                'fldhead' => $request->get('fldhead'),
            ])->orderBy('fldid')->get()
        );
    }

    public function essenceLineChart(Request $request)
    {
        $encounter_id = $request->get('encounterId') ?: \Session::get('inpatient_encounter_id');
        $data['patientVal'] = PatientExam::select('fldid', 'fldtime', 'fldtype', 'fldhead', 'fldabnormal', 'fldrepquanti')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldsave' => '1',
                'fldhead' => $request->get('fldhead'),
            ])->orderBy('fldid')->get();

        $data['chartData'][] = ['Date', 'Observation'];
        if (count($data['patientVal'])) {
            foreach ($data['patientVal'] as $patient) {
                array_push($data['chartData'], [$patient->fldtime, $patient->fldrepquanti]);
            }
        }

        return response()->json([
            'status' => TRUE,
            'dataCount' => count($data['chartData']),
            'data' => $data['chartData'],
        ]);
    }

    public function saveEssential(Request $request)
    {
        try {
            $fldrepquali = $request->get('fldrepquali');
            if (!is_numeric($fldrepquali)) {
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Enter numeric value.',
                ]);
            }

            $fldsysconsts = [
                'Systolic BP' => 'BloodPressure_Systolic',
                'Diastolic BP' => 'BloodPressure_Diastolic',
                'O2 Saturation' => 'Oxygen_Saturation',
                'Respiratory Rate' => 'Respiration_Rate',
                'Temperature(F)' => 'Temperature_System',
                'Fatal Heart Rate' => 'Fetal_Heart_Rate',
                'Pulse Rate' => 'Pulse_Rate',
            ];

            $fldabnormal = '1';
            $fldhead = $request->get('fldhead');
            $testLimit = Examlimit::where('fldexamid', $fldhead)->first();

            if ($testLimit && (($fldrepquali >= $testLimit->fldlow) && ($fldrepquali <= $testLimit->fldhigh)))
                $fldabnormal = '0';

            $encounter_id = $request->get('encounterId') ?: \Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = Helpers::getCompName();

            $fldid = PatientExam::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldserialval' => NULL,
                'fldinput' => 'Examination',
                'fldtype' => 'Quantitative',
                'fldhead' => $fldhead,
                'fldsysconst' => NULL,
                'fldmethod' => 'Manual',
                'fldrepquali' => $fldrepquali,
                'fldrepquanti' => $fldrepquali,
                'fldfilepath' => NULL,
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'fldabnormal' => $fldabnormal,
                'flduptime' => NULL,
                'xyz' => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            return response()->json([
                'status' => TRUE,
                'data' => [
                    'fldid' => $fldid,
                    'fldhead' => $fldhead,
                    'fldabnormal' => $fldabnormal,
                    'fldrepquali' => $fldrepquali,
                    'fldtime' => $time,
                ],
                'message' => 'Successfully saved Data.',
            ]);
        } catch (Exception $e) {
            \Log::info($e);
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save Data.',
            ]);
        }
    }

    public function changeColor(Request $request)
    {
        $colors = [
            'red' => '16711680',
            'yellow' => '16776960',
            'green' => '65280',
            'blue' => '255',
            'black' => '0',
        ];
        try {
            $encounter_id = $request->get('encounterId') ?: \Session::get('inpatient_encounter_id');
            Encounter::where([['fldencounterval', $encounter_id]])
                ->update([
                    'fldheight' => $colors[$request->get('color')]
                ]);
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

    public function getColor(Request $request)
    {
        $colors = [
            '16711680' => 'red',
            '16776960' => 'yellow',
            '65280' => 'green',
            '255' => 'blue',
            '0' => 'black',
            'red' => 'red',
            'yellow' => 'yellow',
            'green' => 'green',
            'blue' => 'blue',
            'black' => 'black',
        ];
        try {
            $encounter_id = $request->get('encounterId') ?: \Session::get('inpatient_encounter_id');
            $data = Encounter::where('fldencounterval', $encounter_id)->select('fldheight')->first();
            $color = isset($colors[$data->fldheight]) ? $colors[$data->fldheight] : '#fff0';
            return response()->json($color);
        } catch (Exception $e) {
        }
    }

    public function readtextfile(Request $request)
    {
        $file = $request->file('fldtext')->getPathName();
        $txt_file = file_get_contents($file);
        $rows = explode("\n", $txt_file);

        return response()->json($rows);
    }

    public function getEssentialExamination(Request $request){
        try {
            $exams = CompExam::select('fldexamid','fldtype','fldid')
                            ->where('fldcomp',Helpers::getCompName())
                            ->where('fldcategory','Essential Examinations')
                            ->with('examLimit:fldexamid,fldunit')
                            ->get();
            $html = "";
            foreach($exams as $exam){
                if($exam->examLimit != null){
                    $examUnit = $exam->examLimit->fldunit;
                }else{
                    $examUnit = "";
                }
                $html .= '<tr data-unit="'.$examUnit.'">
                            <td class="table2-td">'.$exam->fldexamid.'</td>
                            <td class="table2-td"></td>
                        </tr>';

            }
            return response()->json([
                'status' => TRUE,
                'html' => $html,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
            ]);
        }
    }
}
