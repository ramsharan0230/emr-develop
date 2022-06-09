<?php

namespace Modules\MajorProcedure\Http\Controllers;

use App\PatientExam;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class MajorProcedureExaminationController extends Controller
{
    public function getExaminationLists(Request $request)
    {
        $flddept = $request->get('flddept');
        return response()->json(
            \App\DepartmentExam::select('fldexamid', 'fldtype', 'fldsysconst', 'fldtanswertype')
                ->where('flddept', $flddept)
                ->get()
        );
    }


    public function getModalContent(Request $request)
    {
        $type = $request->get('ans_type');
        $examid = $request->get('examid');
        $type1 = $request->get('type');
        $depttype = $request->get('depttype');

        $ret_data = [];
        if ($type == 'No Selection') {
            $header = 'Enter Quantitative Value';
            $ret_data = [
                'view_data' => (string) view('majorprocedure::layouts.modal.examinationModal', compact('examid', 'header', 'type', 'type1', 'depttype')),
            ];
        } elseif ($type == 'Left and Right') {
            $header = 'Left and Right Examination Report';
            $ret_data = [
                'view_data' => (string) view('majorprocedure::layouts.modal.examinationModal', compact('examid', 'header', 'type', 'type1', 'depttype')),
            ];
        } elseif ($type == 'Single Selection') {
            $header = 'Single Selection Report';
            $options = \App\DepartmentExamOption::select('fldanswer')
                ->where('fldexamid', $examid)
                ->orderBy('fldindex')
                ->get();
            $ret_data = [
                'view_data' => (string) view('majorprocedure::layouts.modal.examinationModal', compact('examid', 'header', 'type', 'options', 'type1', 'depttype')),
                'options' => $options,
            ];
        } else {
            $ret_data = [
                'view_data' => (string) view('majorprocedure::layouts.modal.examinationModal', compact('examid', 'type', 'type1', 'depttype')),
            ];
        }

        return response()->json($ret_data);
    }

    public function getPatientExaminations(Request $request)
    {
        $encounter_id = \Session::get('major_procedure_encounter_id');
        $fldinput = $request->get('fldinput') . " Exam";

        $get_related_examination = PatientExam::select('fldid', 'fldtype', 'fldhead', 'fldabnormal', 'fldrepdate', 'fldsave', 'fldencounterval', 'fldrepquali', 'fldsysconst')
        ->where([
            'fldencounterval' => $encounter_id,
            'fldinput' => $fldinput,
            'fldsave' => '1',
        ])->get();

        return response()->json($get_related_examination);
    }

    public function savePatientExaminations(Request $request)
    {
        try {
            $encounter_id = \Session::get('major_procedure_encounter_id');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = Helpers::getCompName();


            $fldinput = $request->get('fldinput') . " Exam";
            $examtype = $request->get('examtype');
            $qualitative = $request->get('qualitative');
            $quantative = $request->get('quantative', '0');
            $examinationid = $request->get('examinationid');
            $abnormal = $request->get('abnormalVal');

            $fldid = PatientExam::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldserialval' => NULL,
                'fldinput' => $fldinput,
                'fldtype' => $examtype,
                'fldhead' => $examinationid,
                'fldsysconst' => NULL,
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
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            return response()->json([
                'status'=> TRUE,
                'data' => [
                    'examination' => $examinationid,
                    'abnormal' => $abnormal,
                    'qualitative' => $qualitative,
                    'quantative' => $quantative,
                    'fldid' => $fldid,
                    'time' => $time,
                    'userid' => $userid,
                    'computer' => $computer,
                    'tabName' => $request->get('fldinput'),
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

    public function changeExamStatus(Request $request)
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
                'message' => 'Failed to update data.',
            ]);
        }
    }

    public function geerateReport(Request $request)
    {
        $encounterId = $request->get('encounterId');
        $flddept = $request->get('flddept') . " Delivery Exam";

        $all_data = PatientExam::select('fldid', 'fldtype', 'fldhead', 'fldabnormal', 'fldtime', 'fldsave', 'fldencounterval', 'fldrepquali', 'fldsysconst')
            ->where([
                'fldencounterval' => $encounterId,
                'fldinput' => $flddept,
                'fldsave' => '1',
            ])->get();
        $patientinfo = \App\Utils\Helpers::getPatientByEncounterId($encounterId);

        return \Barryvdh\DomPDF\Facade::loadView('delivery::pdf.report', compact('patientinfo', 'all_data', 'flddept'))
            ->stream('ipd_symtoms.pdf');
    }
}
