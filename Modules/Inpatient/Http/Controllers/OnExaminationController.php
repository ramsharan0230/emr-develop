<?php

namespace Modules\Inpatient\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\PatientExam;
use App\Utils\Helpers;
use Exception;

class OnExaminationController extends Controller
{
    public function getExaminationsOptions()
    {
        return response()->json([
            'data' => \App\Exam::select('fldexamid', 'fldtype', 'fldoption')->get(),
        ]);
    }

    public function getExaminations(Request $request)
    {
        $date = $request->get('date');
        if ($date) {
            if ($date == 'all')
                $date = NULL;
            elseif ($date == 'today')
                $date = date('Y-m-d');
        } else
            $date = date('Y-m-d');

        $encounter_id = $request->get('encounterId') ?: \Session::get('inpatient_encounter_id');
        $fldexamid = $request->get('examid', 'Examination');

        $examinations = PatientExam::select('tblpatientexam.fldid', 'tblpatientexam.fldtype', 'tblpatientexam.fldhead', 'tblpatientexam.fldabnormal', 'tblpatientexam.fldtime', 'tblpatientexam.fldencounterval', 'tblpatientexam.flduserid', 'tblpatientexam.fldcomp', 'tblpatientexam.fldrepquali', 'tblpatientexam.fldrepquanti', 'tblexam.fldoption')
            ->join('tblexam', 'tblexam.fldexamid', '=', 'tblpatientexam.fldhead')
            ->where([
                "tblpatientexam.fldencounterval" => $encounter_id,
                "tblpatientexam.fldinput" => $fldexamid,
                "tblpatientexam.fldsave" => "1",
            ]);
        if ($date) {
            $examinations->where([
                ["tblpatientexam.fldtime", ">=", "$date 00:00:00"],
                ["tblpatientexam.fldtime", "<=", "$date 23:59:59.999"],
            ]);
        }

        return response()->json([
            'examinations'=> $examinations->get(),
        ], 200);
    }

    public function getModalContent(Request $request)
    {
        $type = $request->get('type');
        $examid = $request->get('examid');

        $ret_data = [];
        if ($type == 'Clinical Scale') {
            $questions = \App\ExamOption::select('fldanswertype', 'fldanswer', 'fldscale', 'fldscalegroup')
                ->where('fldexamid', $examid)
                ->get();

            $formated_que = [];
            foreach ($questions as $que) {
                $formated_que[$que->fldscalegroup]['options'][$que->fldanswer] = $que->fldscale;
            }

            $header = 'Clinical Scale';
            $ret_data = [
                'modal' => TRUE,
                'view_data' => (string) view('inpatient::layouts.modal', compact('examid', 'formated_que', 'header', 'type')),
            ];
        } elseif ($type == 'No Selection') {
            $header = 'Enter Quantitative Value';
            $ret_data = [
                'modal' => TRUE,
                'view_data' => (string) view('inpatient::layouts.modal', compact('examid', 'header', 'type')),
            ];
        } elseif ($type == 'Left and Right') {
            $header = 'Left and Right Examination Report';
            $ret_data = [
                'modal' => TRUE,
                'view_data' => (string) view('inpatient::layouts.modal', compact('examid', 'header', 'type')),
            ];
        } elseif ($type == 'Single Selection') {
            $header = 'Single Selection Report';
            $options = \App\ExamOption::select('fldanswer')
                ->where('fldexamid', $examid)
                ->orderBy('fldindex')
                ->get();
            $ret_data = [
                'modal' => TRUE,
                'view_data' => (string) view('inpatient::layouts.modal', compact('examid', 'header', 'type', 'options')),
                'options' => $options,
            ];
        } else {
            $ret_data = [
                'modal' => TRUE,
                'view_data' => (string) view('inpatient::layouts.modal', compact('examid', 'type')),
            ];
        }

        return response()->json($ret_data);
    }

    public function saveExamination(Request $request)
    {
        $encounter_id = \Session::get('inpatient_encounter_id');
        try {
            $examtype = $request->get('examtype');
            $qualitative = $request->get('qualitative');
            $quantative = $request->get('quantative', '0');
            $examinationid = $request->get('examinationid');
            $abnormal = $request->get('abnormalVal');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();

            $patientExamId = PatientExam::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldserialval' => NULL,
                'fldinput' => 'Examination',
                'fldtype' => $examtype,
                'fldhead' => $examinationid,
                'fldsysconst' => $examinationid,
                'fldmethod' => 'Regular',
                'fldrepquali' => $qualitative,
                'fldrepquanti' => $quantative,
                'fldfilepath' => NULL,
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'fldabnormal' => $abnormal,
                'flduptime' => NULL,
                'xyz' => '0',
            ]);

            return response()->json([
                'status'=> TRUE,
                'data' => [
                    'examination' => $examinationid,
                    'abnormal' => $abnormal,
                    'qualitative' => $qualitative,
                    'quantative' => $quantative,
                    'fldid' => $patientExamId,
                    'time' => $time,
                    'userid' => $userid,
                    'computer' => $computer,
                ],
                'message' => 'Successfully saved examinations.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to save examinations.',
            ], 200);
        }
    }

    public function deleteExamination(Request $request)
    {
        try {
            PatientExam::where('fldid', '=', $request->get('fldid'))
                ->update([
                    'fldsave' => '0',
                ]);
            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully deleted Examination data.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to delete Examination data.',
            ], 200);
        }
    }


    public function getWeights(Request $request)
    {
        $encounter_id = \Session::get('inpatient_encounter_id');
        $weights = PatientExam::select('fldid', 'fldtime', 'fldrepquanti')
            ->where([
                "fldencounterval" => $encounter_id,
                'fldhead' => 'Body Weight',
                "fldsave" => "1",
                "fldtype" => "Quantitative",
            ])
            ->get();

        return response()->json([
            'weights'=> $weights,
        ], 200);
    }

    public function saveWeight(Request $request)
    {
        $encounter_id = \Session::get('inpatient_encounter_id');
        try {
            $weight = $request->get('weight');
            $time = $request->get('date') ? $request->get('date') . " 00:00:00" : date('Y-m-d H:i:s');
            PatientExam::insert([
                'fldencounterval' => $encounter_id,
                'fldserialval' => NULL,
                'fldinput' => 'General Parameters',
                'fldtype' => 'Quantitative',
                'fldhead' => 'Body Weight',
                'fldsysconst' => NULL,
                'fldmethod' => 'Regular',
                'fldrepquali' => $weight,
                'fldrepquanti' => $weight,
                'fldfilepath' => NULL,
                'flduserid' => \Auth::guard('admin_frontend')->user()->flduserid,
                'fldtime' => $time,
                'fldcomp' => \App\Utils\Helpers::getCompName(),
                'fldsave' => '1',
                'fldabnormal' => '0',
                'flduptime' => NULL,
                'xyz' => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);
            return response()->json([
                'status'=> TRUE,
                'data' => [
                    'fldrepquanti' => $weight,
                    'fldtime' => $time,
                ],
                'message' => 'Successfully saved weight.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to save weight.',
            ], 200);
        }
    }

    public function changeOnExamStatus(Request $request)
    {
        try{
            PatientExam::where([
                'fldid' => $request->get('fldid'),
            ])->update([
                'fldabnormal' => $request->get('fldabnormal'),
            ]);

            return response()->json([
                'status'=> TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }
}
