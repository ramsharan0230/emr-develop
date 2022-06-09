<?php

namespace Modules\Eye\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\CompExam;
use App\Confinement;
use App\Consult;
use App\Drug;
use App\Encounter;
use App\Exam;
use App\ExamGeneral;
use App\Examlimit;
use App\PatFindings;
use App\PatGeneral;
use App\PatLabTest;
use App\PatPlanning;
use App\PatRadioTest;
use App\PatTiming;
use App\Pathdosing;
use App\PatientDate;
use App\PatientExam;
use App\PatientInfo;
use App\Utils\Helpers;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Session;
use App\EyeExam;

use App\IntracularPressure;
use App\VisualActivity;


class HistoryPdfController extends Controller
{
    public function historypdf($patientId)
    {
        $patient_id = $patientId;

        $information = array();
        $data['title'] = 'ALL ENCOUNTERS REPORT';

        if ($patient_id) {

            $patient_info = PatientInfo::select('fldptcontact','fldpatientval', 'fldencrypt', 'fldptadddist', 'fldptaddvill', 'fldptnamefir', 'fldptnamelast', 'fldencrypt', 'fldptsex', 'fldptbirday', 'fldmidname', 'fldrank')
                ->where('fldpatientval', $patient_id)
                ->first();

            $patient_encounter_ids = Encounter::select('fldencounterval','fldrank')
                ->where('fldpatientval', $patient_id)
                ->orderBy('fldregdate', 'DESC')
                ->get();


            if ($patient_encounter_ids) {
                foreach ($patient_encounter_ids as $k => $encounter) {
                    $encounter_id = $encounter->fldencounterval;
                    $encounterId = $encounter->fldencounterval;

                    if (EyeExam::where('fldencounterval', $encounterId)->count() > 0) {
                        $information[$k]['eyeimage'] = \App\EyeImage::select('left_eye', 'right_eye')->where('fldencounterval', $encounterId)->first();

                        $information[$k]['encounter_detail'] = $encounter_detail = Encounter::select('fldencounterval as col', 'fldpatientval', 'fldregdate', 'fldfollowdate','fldrank')
                            ->where('fldencounterval', $encounter_id)
                            ->first();
                        $information[$k]['form_type'] = 'eye';
                        // auto_refraction
                        $information[$k]['auto_reaction_spherical_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Auto Reaction',
                            'fldexamtype'       => 'Spherical',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['auto_reaction_spherical_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Auto Reaction',
                            'fldexamtype'       => 'Spherical',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['auto_reaction_cylindrical_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Auto Reaction',
                            'fldexamtype'       => 'Cylindrical',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['auto_reaction_cylindrical_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Auto Reaction',
                            'fldexamtype'       => 'Cylindrical',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['auto_reaction_axis_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Auto Reaction',
                            'fldexamtype'       => 'Axis',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['auto_reaction_axis_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Auto Reaction',
                            'fldexamtype'       => 'Axis',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        // Add
                        $information[$k]['add_spherical_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Add',
                            'fldexamtype'       => 'Spherical',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['add_spherical_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Add',
                            'fldexamtype'       => 'Spherical',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['add_vision_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Add',
                            'fldexamtype'       => 'Vision',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['add_vision_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Add',
                            'fldexamtype'       => 'Vision',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        // Acceptance
                        $information[$k]['acceptance_spherical_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Acceptance',
                            'fldexamtype'       => 'Spherical',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['acceptance_spherical_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Acceptance',
                            'fldexamtype'       => 'Spherical',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['acceptance_cylindrical_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Acceptance',
                            'fldexamtype'       => 'Cylindrical',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['acceptance_cylindrical_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Acceptance',
                            'fldexamtype'       => 'Cylindrical',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['acceptance_axis_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Acceptance',
                            'fldexamtype'       => 'Axis',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['acceptance_axis_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Acceptance',
                            'fldexamtype'       => 'Axis',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['acceptance_vision_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Acceptance',
                            'fldexamtype'       => 'Vision',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['acceptance_vision_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Acceptance',
                            'fldexamtype'       => 'Vision',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        // Previous Glass Precribtion (PGP)
                        $information[$k]['PGP_spherical_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Previous Glass Precribtion (PGP)',
                            'fldexamtype'       => 'Spherical',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['PGP_spherical_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Previous Glass Precribtion (PGP)',
                            'fldexamtype'       => 'Spherical',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['PGP_cylindrical_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Previous Glass Precribtion (PGP)',
                            'fldexamtype'       => 'Cylindrical',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['PGP_cylindrical_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Previous Glass Precribtion (PGP)',
                            'fldexamtype'       => 'Cylindrical',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['PGP_axis_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Previous Glass Precribtion (PGP)',
                            'fldexamtype'       => 'Axis',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['PGP_axis_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Previous Glass Precribtion (PGP)',
                            'fldexamtype'       => 'Axis',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        // Color Vision
                        $information[$k]['color_vision_axis_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Color Vision',
                            'fldexamtype'       => 'No',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['color_vision_axis_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Color Vision',
                            'fldexamtype'       => 'No',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        // Schicmers Test
                        $information[$k]['schimers_test_type_I_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Schicmers Test',
                            'fldexamtype'       => 'Type I',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['schimers_test_type_I_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Schicmers Test',
                            'fldexamtype'       => 'Type I',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['schimers_test_type_II_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Schicmers Test',
                            'fldexamtype'       => 'Type II',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['schimers_test_type_II_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Schicmers Test',
                            'fldexamtype'       => 'Type II',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['schimers_test_type_III_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Schicmers Test',
                            'fldexamtype'       => 'Type III',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['schimers_test_type_III_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'Schicmers Test',
                            'fldexamtype'       => 'Type III',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        // K-Reading
                        $information[$k]['k_reading_k_I_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'K-Reading',
                            'fldexamtype'       => 'K1',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['k_reading_k_I_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'K-Reading',
                            'fldexamtype'       => 'K1',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['k_reading_k_II_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'K-Reading',
                            'fldexamtype'       => 'K2',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['k_reading_k_II_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'K-Reading',
                            'fldexamtype'       => 'K2',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['k_reading_k_III_RE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'K-Reading',
                            'fldexamtype'       => 'AXL',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['k_reading_k_III_LE'] = EyeExam::where([
                            'fldencounterval'   => $encounterId,
                            'fldexam'           => 'K-Reading',
                            'fldexamtype'       => 'AXL',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['note'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'Note'])->select('fldreportquali')->first();
                        $information[$k]['advice'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'Advice'])->select('fldreportquali')->first();
                        $information[$k]['history_family'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'History Family'])->select('fldreportquali')->first();
                        $information[$k]['history_past'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'History Past'])->select('fldreportquali')->first();
                        $information[$k]['exam_left'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'On Examination Left'])->select('fldreportquali')->first();
                        $information[$k]['exam_right'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'On Examination Right'])->select('fldreportquali')->first();
                        $information[$k]['current_medication'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'Current Medication'])->select('fldreportquali')->first();
                        $information[$k]['systemic_illiness'] = ExamGeneral::where(['fldencounterval' => $encounterId, 'fldinput' => 'Systemic Illiness'])->select('fldreportquali')->first();
                        $information[$k]['allergy'] = PatFindings::where('fldencounterval', $encounterId)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
                        $information[$k]['complaint'] = ExamGeneral::where('fldencounterval', $encounterId)->where(['fldinput' => 'Presenting Symptoms', 'fldtype' => 'Qualitative'])->where('fldsave', 1)->get();


                        // Unaided
                        $information[$k]['unaided_distance_RE'] = VisualActivity::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'Unaided',
                            'fldsubcategory'    => 'Distance',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['unaided_distance_LE'] = VisualActivity::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'Unaided',
                            'fldsubcategory'    => 'Distance',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['aided_distance_RE'] = VisualActivity::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'Aided',
                            'fldsubcategory'    => 'Distance',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['aided_distance_LE'] = VisualActivity::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'Aided',
                            'fldsubcategory'    => 'Distance',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['pinhole_distance_RE'] = VisualActivity::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'Pinhole',
                            'fldsubcategory'    => 'Distance',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        $information[$k]['pinhole_distance_LE'] = VisualActivity::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'Pinhole',
                            'fldsubcategory'    => 'Distance',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading')->first();

                        // IntracularPressure
                        $information[$k]['AT_RE'] = IntracularPressure::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'AT',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading', 'fldreadingprefix')->first();

                        $information[$k]['AT_LE'] = IntracularPressure::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'AT',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading', 'fldreadingprefix')->first();

                        $information[$k]['NCT_RE'] = IntracularPressure::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'NCT',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading', 'fldreadingprefix')->first();

                        $information[$k]['NCT_LE'] = IntracularPressure::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'NCT',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading', 'fldreadingprefix')->first();

                        $information[$k]['SA_RE'] = IntracularPressure::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'SA',
                            'fldlocation'       => 'RE',
                            'fldsave'           => 1
                        ])->select('fldreading', 'fldreadingprefix')->first();

                        $information[$k]['SA_LE'] = IntracularPressure::where([
                            'fldencounterval'   => $encounterId,
                            'fldcategory'       => 'SA',
                            'fldlocation'       => 'LE',
                            'fldsave'           => 1
                        ])->select('fldreading', 'fldreadingprefix')->first();
                    } else {
                        $information[$k]['form_type'] = 'normal';
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
                            $opdData['fldid'][]        = $opdExam->fldid;
                            $opdData['fldtime'][]      = $opdExam->fldtime;
                            $opdData['fldhead'][]      = $opdExam->fldhead;
                            $opdData['fldrepquali'][]  = json_decode($opdExam->fldrepquali, true);
                            $opdData['fldrepquanti'][] = $opdExam->fldrepquanti;
                            $opdData['fldtype '][]     = $opdExam->fldtype;
                        }

                        $information[$k]['patientExam'] = $opdData;
                        //dd($information[$k]['patientExam']);


                        // $exams = DB::table('tblexam')
                        //     ->join('tblpatientexam', 'tblpatientexam.fldhead', '=', 'tblexam.fldexamid')
                        //     ->select('tblpatientexam.*', 'tblexam.*')
                        //     ->where('tblpatientexam.fldencounterval', $encounter_id)
                        //     ->get();

                        // if ($exams) {
                        //     foreach ($exams as $ex) {
                        //         //select fldunit from tblexamlimit where fldexamid='ADR Probability Scale (Naranjo)' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='All Age')

                        //         $subexam = PatientSubExam::where(['fldheadid' => $ex->fldid, 'fldencounterval' => $encounter_id])->get();
                        //     }
                        // }


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


                        /*select fldid,fldroute,flditem,flddose,fldfreq,flddays,flditemtype from tblpatdosing where fldencounterval='1' and flditemtype='Medicines' and (fldstatus='Registered' or fldstatus='Admitted' or fldstatus='Recorded')*/
                        /*select fldid,fldroute,flditem,flddose,fldfreq,flddays,flditemtype from tblpatdosing where fldencounterval='1' and flditemtype='Medicines' and (fldstatus='Registered' or fldstatus='Admitted' or fldstatus='Recorded')*/
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


                        // $delivery_profile = PatTiming::select()->where()->get();
                        // $essential_exam =PatTiming::select()->where()->get();


                        $information[$k]['confinement'] = Confinement::select('fldid', 'flddelresult', 'flddeltype', 'fldbabypatno', 'flddeltime', 'flddelwt')
                            ->where('fldencounterval', $encounter_id)
                            ->get();


                        /*select distinct(fldhead) as col from tblpatientexam where fldencounterval='1' and fldsave='1' and fldhead in(select fldexamid from tblcompexam where fldcomp=Helpers::getCompName() and fldcategory='Essential examinations')*/

                        $fldexamid = CompExam::where('fldcategory', 'Essential examinations')
                            ->pluck('fldexamid');

                        $information[$k]['fldhead'] = PatientExam::select('fldhead as col')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldsave', '1')
                            ->whereIn('fldhead', $fldexamid)
                            ->distinct()
                            ->get();


                        /*select distinct(fldhead) as col from tblpatientexam where fldencounterval='1' and fldsave='1' and fldhead not in(select fldexamid from tblcompexam where fldcomp=Helpers::getCompName() and fldcategory='Essential examinations')*/
                        $information[$k]['fldheadNotIn'] = PatientExam::select('fldhead as col')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldsave', '1')
                            ->whereNotIn('fldhead', $fldexamid)
                            ->distinct()
                            ->get();

                        /*select distinct(fldserialval) as col from tblpatientexam where fldserialval like '%' and fldencounterval='1' and fldinput='Rec Examination' and fldsave='1'*/
                        $information[$k]['patientSerialValue'] = PatientExam::select('fldserialval as col')
                            ->where('fldserialval', 'like', '%')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Rec Examination', 'fldsave' => '1'])
                            ->distinct()
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Antenatal Examination - 3RD TIMESTER' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['AntenatalExam3rd'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Antenatal Examination - 3RD TIMESTER', 'fldsave' => '1', 'fldinput' => 'Examination'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='O2 Saturation' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['Saturation'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'O2 Saturation', 'fldsave' => '1', 'fldinput' => 'Examination'])
                            ->with(['patientSubExam'])
                            ->get();

                        /*select fldoption from tblexam where fldexamid='O2 Saturation'*/
                        $information[$k]['OptionSaturation'] = Exam::select('fldoption')
                            ->where('fldexamid', 'O2 Saturation')
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Pulse Rate' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['PulseRatePatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Pulse Rate', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->orderBy('fldid', 'DESC')
                            ->first();

                        /*select fldunit from tblexamlimit where fldexamid='Pulse Rate' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='All Age')*/
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

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Systolic BP' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['SystolicBPPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Systolic BP', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->orderBy('fldid', 'DESC')
                            ->first();

                        /*select fldunit from tblexamlimit where fldexamid='Systolic BP' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='All Age')*/
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


                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Diastolic BP' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['DiastolicBPPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Diastolic BP', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->orderBy('fldid', 'DESC')
                            ->first();


                        /*select fldunit from tblexamlimit where fldexamid='Diastolic BP' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='Diastolic BP')*/
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


                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Ankle Jerk' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['AnkleJerkPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Ankle Jerk', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='ADR Probability Scale (Naranjo)' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['ADRProbabilityScalePatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'ADR Probability Scale (Naranjo)', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Abdominal Girth' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['AbdominalGirthPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Abdominal Girth', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Abdomen Examination' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['AbdomenExaminationPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Abdomen Examination', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Activity' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['ActivityPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Activity', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Local Examination' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['LocalExaminationPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Local Examination', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Breast Feeding' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['BreastFeedingExaminationPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Breast Feeding', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Body Height' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['BodyHeightPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Body Height', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Antenatal Examination -2ND TRIMESTER' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['AntenatalExamination2NdPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Antenatal Examination -2ND TRIMESTER', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Pregnancy Status' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['PregnancyStatusPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Pregnancy Status', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();


                        /*select distinct(fldtestid) as col from tblpatlabtest where fldencounterval='1' and (fldstatus='Reported' or fldstatus='Verified')*/
                        /*
                         * select fldtype from tbltest where fldtestid='Differential Leucocytes Count'

                        select fldid,fldencounterval,fldtime_sample,fldsampletype,fldreportquali,fldtest_type from tblpatlabtest where fldencounterval='1' and fldtestid='Differential Leucocytes Count' and (fldstatus='Reported' or fldstatus='Verified')

                        select fldsubtest,fldreport,fldid,fldtestid,fldtanswertype from tblpatlabsubtest where fldtestid=1 and fldsave='1' and fldencounterval='1'

                        >>>>>ESR
                             select fldtype from tbltest where fldtestid='ESR'========

                             select fldid,fldencounterval,fldtime_sample,fldsampletype,fldreportquali,fldtest_type from tblpatlabtest where fldencounterval='1' and fldtestid='ESR' and (fldstatus='Reported' or fldstatus='Verified')

                            select fldencounterval,fldreportquanti,fldtestunit from tblpatlabtest where fldid=2 and fldencounterval='1'------------------------------------------

                            select fldencounterval,fldtestid,fldmethod from tblpatlabtest where fldid=2 and fldtest_type='Quantitative' and fldencounterval='1'------------------------------

                            select fldconvfactor as conv from tbltestlimit where fldtestid='ESR' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='All Age')------------------------------

                        >>>>>Haemoglobin (Hb%)

                        select fldtype from tbltest where fldtestid='Haemoglobin (Hb%)'------------------
                        select fldid,fldencounterval,fldtime_sample,fldsampletype,fldreportquali,fldtest_type from tblpatlabtest where fldencounterval='1' and fldtestid='Haemoglobin (Hb%)' and (fldstatus='Reported' or fldstatus='Verified')-------------------

                        select fldencounterval,fldreportquanti,fldtestunit from tblpatlabtest where fldid=3 and fldencounterval='1'--------------------

                        select fldencounterval,fldtestid,fldmethod from tblpatlabtest where fldid=3 and fldtest_type='Quantitative' and fldencounterval='1'----------------

                        2020-03-27 10:04:10.346 gb.db.mysql: 0x55fd78e188a0: select fldconvfactor as conv from tbltestlimit where fldtestid='Haemoglobin (Hb%)' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='All Age')
                        */


                        $information[$k]['reportedPatLab'] = PatLabTest::where('fldencounterval', $encounter_id)
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldstatus', '=', 'Reported')
                                    ->orWhere('fldstatus', '=', 'Verified');
                            })
                            ->with(['patTestResults', 'subTest', 'testLimit'])
                            ->get();


                        /*select distinct(fldtestid) as col from tblpatradiotest where fldencounterval='1' and (fldstatus='Reported' or fldstatus='Verified')*/

                        /*
                         * select fldtype from tblradio where fldexamid='USG OF ABDOMEN AND PELVIS (MALE)'====

                        select fldtype from tblradio where fldexamid='USG OF ABDOMEN AND PELVIS (MALE)'====

                        select fldid,fldtime_report,fldreportquanti,fldreportquali,fldtest_type from tblpatradiotest where fldencounterval='1' and fldtestid='USG OF ABDOMEN AND PELVIS (MALE)' and (fldstatus='Reported' or fldstatus='Verified')

                        2020-03-27 10:04:10.349 gb.db.mysql: 0x55fd78e188a0: select fldsubtest,fldreport,fldid,fldtestid,fldtanswertype from tblpatradiosubtest where fldtestid=1 and fldsave='1' and fldencounterval='1'
                        */


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


                        // $exams = DB::table('tblexam')
                        //     ->join('tblpatientexam', 'tblpatientexam.fldhead', '=', 'tblexam.fldexamid')
                        //     ->select('tblpatientexam.*', 'tblexam.*')
                        //     ->where('tblpatientexam.fldencounterval', $encounter_id)
                        //     ->get();

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
                    }
                }
            }

            $data['patientinfo'] = $patient_info;
            $data['encounters']  = $information;
        }

        return view('eye::pdf.history', $data)/*->setPaper('a4')->stream('history.pdf')*/;
    }
}
