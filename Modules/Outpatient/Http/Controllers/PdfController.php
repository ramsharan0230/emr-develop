<?php

namespace Modules\Outpatient\Http\Controllers;

use App\Consult;
use App\Encounter;
use App\ExamGeneral;
use App\PatFindings;
use App\PatGeneral;
use App\Pathdosing;
use App\PatientDate;
use App\PatientExam;
use App\PatLabTest;
use App\PatPlanning;
use App\PatTiming;
use App\NurseDosing;
use App\Utils\Helpers;
use App\Utils\Options;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Routing\Controller;

class PdfController extends Controller
{
    public function generatePdf($encounterId)
    {
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
        // dd($options);
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
        // dd($information);
        return view('outpatient::pdf.opd', $information)/*->setPaper('a4')->stream('opd.pdf')*/;
    }
}
