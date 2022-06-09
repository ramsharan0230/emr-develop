<?php

namespace Modules\Eye\Http\Controllers;

use App\Encounter;
use App\ExamGeneral;
use App\EyeExam;
use App\IntracularPressure;
use App\PatFindings;
use App\VisualActivity;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class EyePdfController extends Controller
{
    public function opdSheetPdf($encounterId = null)
    {
        if ($encounterId == null) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', __('Please select encounter.'));
            return redirect()->back();
        }

        // $fldexam = EyeExam::where('fldencounterval', $encounterId)->where('fldsave', 1)->select('fldexam')->distinct()->get();
        // $get_report = [];

        // if ($fldexam) {
        //     foreach ($fldexam as $e => $exam) {
        //         $get_exam_report = EyeExam::select('fldreading')
        //             ->where('fldencounterval', $encounterId)
        //             ->where('fldsave', 1)
        //             ->where('fldexam', $exam->fldexam)
        //             ->get();

        //         $exam_name = $exam->fldexam;

        //         $get_report[$exam_name] = $get_exam_report;
        //     }
        // }

        // $data['exam_report'] = $get_report;
        $data['patientinfo'] = \App\Utils\Helpers::getPatientByEncounterId($encounterId);
        // auto_refraction
        $data['auto_reaction_spherical_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Auto Reaction',
            'fldexamtype'       => 'Spherical',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['auto_reaction_spherical_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Auto Reaction',
            'fldexamtype'       => 'Spherical',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['auto_reaction_cylindrical_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Auto Reaction',
            'fldexamtype'       => 'Cylindrical',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['auto_reaction_cylindrical_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Auto Reaction',
            'fldexamtype'       => 'Cylindrical',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['auto_reaction_axis_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Auto Reaction',
            'fldexamtype'       => 'Axis',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['auto_reaction_axis_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Auto Reaction',
            'fldexamtype'       => 'Axis',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        // Add
        $data['add_spherical_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Add',
            'fldexamtype'       => 'Spherical',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['add_spherical_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Add',
            'fldexamtype'       => 'Spherical',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['add_vision_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Add',
            'fldexamtype'       => 'Vision',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['add_vision_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Add',
            'fldexamtype'       => 'Vision',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        // Acceptance
        $data['acceptance_spherical_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Acceptance',
            'fldexamtype'       => 'Spherical',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['acceptance_spherical_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Acceptance',
            'fldexamtype'       => 'Spherical',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['acceptance_cylindrical_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Acceptance',
            'fldexamtype'       => 'Cylindrical',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['acceptance_cylindrical_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Acceptance',
            'fldexamtype'       => 'Cylindrical',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['acceptance_axis_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Acceptance',
            'fldexamtype'       => 'Axis',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['acceptance_axis_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Acceptance',
            'fldexamtype'       => 'Axis',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['acceptance_vision_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Acceptance',
            'fldexamtype'       => 'Vision',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['acceptance_vision_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Acceptance',
            'fldexamtype'       => 'Vision',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        // Previous Glass Precribtion (PGP)
        $data['PGP_spherical_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Previous Glass Precribtion (PGP)',
            'fldexamtype'       => 'Spherical',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['PGP_spherical_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Previous Glass Precribtion (PGP)',
            'fldexamtype'       => 'Spherical',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['PGP_cylindrical_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Previous Glass Precribtion (PGP)',
            'fldexamtype'       => 'Cylindrical',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['PGP_cylindrical_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Previous Glass Precribtion (PGP)',
            'fldexamtype'       => 'Cylindrical',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['PGP_axis_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Previous Glass Precribtion (PGP)',
            'fldexamtype'       => 'Axis',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['PGP_axis_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Previous Glass Precribtion (PGP)',
            'fldexamtype'       => 'Axis',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        // Color Vision
        $data['color_vision_axis_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Color Vision',
            'fldexamtype'       => 'No',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['color_vision_axis_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Color Vision',
            'fldexamtype'       => 'No',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        // Schicmers Test
        $data['schimers_test_type_I_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Schicmers Test',
            'fldexamtype'       => 'Type I',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['schimers_test_type_I_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Schicmers Test',
            'fldexamtype'       => 'Type I',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['schimers_test_type_II_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Schicmers Test',
            'fldexamtype'       => 'Type II',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['schimers_test_type_II_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Schicmers Test',
            'fldexamtype'       => 'Type II',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['schimers_test_type_III_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Schicmers Test',
            'fldexamtype'       => 'Type III',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['schimers_test_type_III_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'Schicmers Test',
            'fldexamtype'       => 'Type III',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        // K-Reading
        $data['k_reading_k_I_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'K-Reading',
            'fldexamtype'       => 'K1',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['k_reading_k_I_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'K-Reading',
            'fldexamtype'       => 'K1',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['k_reading_k_II_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'K-Reading',
            'fldexamtype'       => 'K2',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['k_reading_k_II_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'K-Reading',
            'fldexamtype'       => 'K2',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['k_reading_k_III_RE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'K-Reading',
            'fldexamtype'       => 'AXL',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['k_reading_k_III_LE'] = EyeExam::where([
            'fldencounterval'   => $encounterId,
            'fldexam'           => 'K-Reading',
            'fldexamtype'       => 'AXL',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['note'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'Note'])->select('fldreportquali')->first();
        $data['advice'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'Advice'])->select('fldreportquali')->first();
        $data['history_family'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'History Family'])->select('fldreportquali')->first();
        $data['history_past'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'History Past'])->select('fldreportquali')->first();
        $data['exam_left'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'On Examination Left'])->select('fldreportquali')->first();
        $data['exam_right'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'On Examination Right'])->select('fldreportquali')->first();
        $data['current_medication'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'Current Medication'])->select('fldreportquali')->first();
        $data['systemic_illiness'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'Systemic Illiness'])->select('fldreportquali')->first();
        $data['allergy'] = PatFindings::where('fldencounterval', $encounterId)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
        $data['complaint'] = ExamGeneral::where('fldencounterval', $encounterId)->where(['fldinput' => 'Presenting Symptoms', 'fldtype' => 'Qualitative'])->where('fldsave', 1)->get();


        // Unaided
        $data['unaided_distance_RE'] = VisualActivity::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'Unaided',
            'fldsubcategory'    => 'Distance',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['unaided_distance_LE'] = VisualActivity::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'Unaided',
            'fldsubcategory'    => 'Distance',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['aided_distance_RE'] = VisualActivity::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'Aided',
            'fldsubcategory'    => 'Distance',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['aided_distance_LE'] = VisualActivity::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'Aided',
            'fldsubcategory'    => 'Distance',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['pinhole_distance_RE'] = VisualActivity::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'Pinhole',
            'fldsubcategory'    => 'Distance',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        $data['pinhole_distance_LE'] = VisualActivity::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'Pinhole',
            'fldsubcategory'    => 'Distance',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading')
        ->first();

        // IntracularPressure
        $data['AT_RE'] = IntracularPressure::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'AT',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading', 'fldreadingprefix')
        ->first();

        $data['AT_LE'] = IntracularPressure::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'AT',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading', 'fldreadingprefix')
        ->first();

        $data['NCT_RE'] = IntracularPressure::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'NCT',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading', 'fldreadingprefix')
        ->first();

        $data['NCT_LE'] = IntracularPressure::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'NCT',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading', 'fldreadingprefix')
        ->first();

        $data['SA_RE'] = IntracularPressure::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'SA',
            'fldlocation'       => 'RE',
            'fldsave'           => 1
        ])
        ->select('fldreading', 'fldreadingprefix')
        ->first();

        $data['SA_LE'] = IntracularPressure::where([
            'fldencounterval'   => $encounterId,
            'fldcategory'       => 'SA',
            'fldlocation'       => 'LE',
            'fldsave'           => 1
        ])
        ->select('fldreading', 'fldreadingprefix')
        ->first();
        $data['eyeimage'] = \App\EyeImage::select('left_eye', 'right_eye')->where('fldencounterval', $encounterId)->first();

        // $tblEyeExam = EyeExam::where('fldencounterval', $encounterId)->get();
        // $eyeExamData = [];
        // foreach ($tblEyeExam as $eyeExam) {
        //     $formated_exam = str_replace(' ', '_', $eyeExam->fldexam);
        //     $eyeExamData[$formated_exam][$eyeExam->fldexamtype][$eyeExam->fldlocation] = $eyeExam->fldreading;
        // }

        // dd($eyeExamData);

        return view('eye::pdf.opd-sheet-pdf', $data)/*->setPaper('a4')->stream('opd-sheet.pdf')*/;
        // return view('eye::pdf.opd-sheet-pdf', $data);
    }
}
