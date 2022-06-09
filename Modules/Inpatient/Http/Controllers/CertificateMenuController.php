<?php

namespace Modules\Inpatient\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;


use App\Consult;
use App\Encounter;
use App\ExamGeneral;
use App\PatFindings;
use App\PatGeneral;
use App\Pathdosing;
use App\PatientDate;
use App\PatientExam;
use App\PatLabTest;
use App\PatRadioTest;
use App\PatPlanning;
use App\PatTiming;
use App\Utils\Options;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Routing\Controller;


class CertificateMenuController extends Controller
{
    public function getDischargePaperOptions()
    {
        $encounter_id = \Session::get('inpatient_encounter_id');
        $exams = PatientExam::select('fldhead AS col')->distinct()->where([
                'fldencounterval' => $encounter_id,
                'fldsave' => '1',
                'fldinput' => 'Examination',
            ])->get();
        $labs = PatLabTest::select('fldtestid AS col')->distinct()->where([
                'fldencounterval' => $encounter_id,
            ]);
        if (\App\Utils\Options::get('show_verified') == '1')
            $labs->where('fldstatus', 'Verified');
        else
            $labs->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            });
        $labs = $labs->get();
        $radios = PatRadioTest::select('fldtestid AS col')->distinct()->where([
                'fldencounterval' => $encounter_id,
            ]);
        if (\App\Utils\Options::get('show_verified') == '1')
            $radios->where('fldstatus', 'Verified');
        else
            $radios->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            });
        $radios = $radios->get();

        return response()->json(compact('exams', 'labs', 'radios'));
    }

    public function dischargepaper(Request $request)
    {
        return view('inpatient::pdf.dischargepaper', $this->_get_data_data($request))
            ->stream('ipd_complete.pdf');
    }

    private function _format_datetime($datetime, $returndatetime = FALSE)
    {
        $datetime = explode(' ', $datetime);
        $englishdate = $datetime[0];
        $nepalidate = \App\Utils\Helpers::dateEngToNep(str_replace('-', '/', $englishdate));
        $nepalidate = "{$nepalidate->year}-{$nepalidate->month}-{$nepalidate->date}";

        $time = substr($datetime[1], 0, -3);
        if ($returndatetime)
            return "$nepalidate $time";

        return compact('englishdate', 'nepalidate', 'time');
    }

    private function _get_data_data($request)
    {
        $all_inpput = $request->all();
        $encounter_id = \Session::get('inpatient_encounter_id');
        $patientinfo = \App\Utils\Helpers::getPatientByEncounterId($encounter_id);

        $course_of_treatments = \App\PatientDate::select('fldhead', 'fldtime', 'fldcomment')->where('fldencounterval', $encounter_id)->get();
        foreach ($course_of_treatments as &$treatment) {
            $treatment->fldtime = $this->_format_datetime($treatment->fldtime, TRUE);
        }

        $exams = PatientExam::select('fldid', 'tblpatientexam.fldtype', 'fldtime', 'fldhead', 'fldrepquanti', 'fldrepquali', 'fldinput', 'fldoption')
            ->join('tblexam', 'tblexam.fldexamid', '=', 'tblpatientexam.fldhead')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldsave' => '1',
            ])->where(function($query) {
                $query->where('fldinput', 'Triage examinations');
                $query->orWhere('fldinput', 'OPD examinations');
            })->orderBy('fldtime')->get();
        $triage_exams = [];
        $opd_exams = [];
        foreach ($exams as $exam) {
            if ($exam->fldinput == 'Triage examinations')
                $triage_exams[] = $exam;
            elseif ($exam->fldinput == 'OPD examinations')
                $opd_exams[] = $exam;
        }

        $examgeneral_raw = ExamGeneral::select('flddetail', 'flditem')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'History',
            ])->where(function($query) {
                $query->where('flditem', 'Cause of Admission');
                $query->orWhere('flditem', 'General Complaints');
                $query->orWhere('flditem', 'History of Illness');
                $query->orWhere('flditem', 'Past History');
                $query->orWhere('flditem', 'Treatment History');
                $query->orWhere('flditem', 'Medication History');
                $query->orWhere('flditem', 'Family History');
                $query->orWhere('flditem', 'Personal History');
                $query->orWhere('flditem', 'Surgical History');
                $query->orWhere('flditem', 'Occupational History');
                $query->orWhere('flditem', 'Social History');
            })->get();
        $examgeneral_data = [];
        foreach ($examgeneral_raw as $examgeneral) {
            $key = strtolower(implode('_', explode(' ', $examgeneral->flditem)));
            $examgeneral_data[$key] = $examgeneral->flddetail;
        }

        $presenting_complaints = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'fldreportquanti', 'flddetail')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Presenting Symptoms',
                'fldsave' => '1',
            ])->get();

        $diagnosis_raw = PatFindings::select('fldcode', 'fldcodeid', 'fldtype')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldsave' => '1',
            ])->where(function($query) {
                $query->where('fldtype', 'Allergic Drugs');
                $query->orWhere('fldtype', 'Provisional Diagnosis');
                $query->orWhere('fldtype', 'Final Diagnosis');
            })->get();
        $diagnosis_data = [];
        foreach ($diagnosis_raw as $diagnosis) {
            $key = strtolower(implode('_', explode(' ', $diagnosis->fldtype)));
            $diagnosis_data[$key][] = $diagnosis;
        }

        $advice = ExamGeneral::select('flddetail')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Notes',
                'flditem' => 'Initial Planning',
            ])->first();

        $demographics = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Demographics',
            ])->first();

        $patient_symptoms = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Patient Symptoms',
                'fldsave' => '1',
            ])->get();

        $extra_procedures = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Extra Procedures',
                'fldreportquali' => 'Planned',
            ])->get();

        $medicines = Pathdosing::select('tblpatdosing.fldid', 'tblpatdosing.fldroute', 'tblpatdosing.flditem', 'tblpatdosing.flddose', 'tblpatdosing.fldfreq', 'tblpatdosing.flddays', 'tblpatdosing.flditemtype', \DB::raw('COUNT(tblnurdosing.fldid) AS fldcount'))
            ->leftJoin('tblnurdosing', 'tblnurdosing.flddoseno', '=', 'tblpatdosing.fldid')
            ->where([
                'tblpatdosing.fldencounterval' => $encounter_id,
                'tblpatdosing.flditemtype' => 'Medicines',
            ])->where(function($query) {
                $query->where('tblpatdosing.fldstatus', 'Registered');
                $query->orWhere('tblpatdosing.fldstatus', 'Admitted');
                $query->orWhere('tblpatdosing.fldstatus', 'Recorded');
            })->groupBy('tblpatdosing.fldid', 'tblpatdosing.fldroute', 'tblpatdosing.flditem', 'tblpatdosing.flddose', 'tblpatdosing.fldfreq', 'tblpatdosing.flddays', 'tblpatdosing.flditemtype')->get();

        $delivery_profile = \App\Confinement::select('tblconfinement.fldid','tblconfinement.flddelresult','tblconfinement.flddeltype','tblconfinement.fldbabypatno','tblconfinement.flddeltime','tblconfinement.flddelwt', 'pi.fldptsex')
            ->join('tblpatientinfo AS pi', 'pi.fldpatientval', '=', 'c.fldbabypatno')
            ->where('fldencounterval', $encounter_id)
            ->get();

        $laboratory = PatLabTest::select('fldid', 'fldsampletype', 'fldmethod', 'fldtestid', 'fldencounterval', 'fldreportquanti')
            ->where([
                'fldencounterval' => $encounter_id,
                'flvisible' => 'Visible',
            ]);
        if (\App\Utils\Options::get('show_verified') == '1')
            $laboratory->where('fldstatus', 'Verified');
        else
            $laboratory->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            });
        $laboratory = $laboratory->orderBy('fldtime_sample')->get();
        $laboratory->map(function($lab) {
            $lab->answers = \App\PatLabSubTable::select('fldsubtest', 'fldreport')
                ->where([
                    'fldtestid' => $lab->fldid,
                    'fldsave' => '1',
                    'fldencounterval' => $lab->fldencounterval,
                ])->get();
        });

        $radio_tests = PatRadioTest::select(\DB::raw('distinct(fldtestid) AS fldtestid'), 'fldid', 'fldencounterval')
            ->where([
                'fldencounterval' => $encounter_id,
            ]);
        if (\App\Utils\Options::get('show_verified') == '1')
            $radio_tests->where('fldstatus', 'Verified');
        else
            $radio_tests->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            });
        $radio_tests = $radio_tests->get();
        $radio_tests->map(function($test) {
            $test->answers = \App\PatRadioSubTest::select('fldsubtest', 'fldreport', 'fldid', 'fldtestid', 'fldtanswertype')
                ->where([
                    'fldtestid' => $test->fldid,
                    'fldsave' => '1',
                    'fldencounterval' => $test->fldencounterval,
                ])->get();
        });

        $patGeneral = \App\PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
            ->where('fldencounterval', $encounter_id)
            ->where('fldinput', 'Procedures')
            ->where('fldreportquali', 'Planned')
            ->get();
        $DischargeExaminationspatientExam = \App\PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
            ->where('fldencounterval', $encounter_id)
            ->where('fldsave', '1')
            ->where('fldinput', 'Discharge examinations')
            ->get();
        $ConditionOfDischargeExamGeneral = \App\ExamGeneral::select('flddetail', 'fldtime')
            ->where('fldencounterval', $encounter_id)
            ->where('fldinput', 'Notes')
            ->where('flditem', 'Condition of Discharge')
            ->get();
        $DischargedLAMADeathReferAbsconderPatDosing = \App\Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'flditemtype')
            ->where('fldencounterval', $encounter_id)
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Discharged')
                    ->orWhere('fldstatus', '=', 'LAMA')
                    ->orWhere('fldstatus', '=', 'Death')
                    ->orWhere('fldstatus', '=', 'Absconder')
                    ->orWhere('fldstatus', '=', 'Refer');
            })->get();
        $AdviceOfDischargeExamGeneral = \App\ExamGeneral::select('flddetail', 'fldtime')
            ->where('fldencounterval', $encounter_id)
            ->where('fldinput', 'Notes')
            ->where('flditem', 'Advice on Discharge')
            ->get();

        $major_procedures = \App\PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'flddetail')
            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Procedures', 'fldreportquali' => 'Done'])
            ->get();
        $extra_procedures = \App\PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Extra Procedures', 'fldreportquali' => 'Planned'])
            ->get();

        return compact(
            'patientinfo', 'course_of_treatments', 'demographics', 'triage_exams', 'opd_exams', 'examgeneral_data', 'presenting_complaints', 'diagnosis_data', 'advice', 'patient_symptoms' , 'extra_procedures', 'medicines', 'laboratory', 'radio_tests', 'delivery_profile', 'patGeneral', 'DischargeExaminationspatientExam', 'ConditionOfDischargeExamGeneral', 'DischargedLAMADeathReferAbsconderPatDosing', 'AdviceOfDischargeExamGeneral', 'major_procedures', 'extra_procedures'
        );
    }


    /*pooja code*/
    public function generatePdf($encounterId,$certificate)
    {
        $information['encounterId'] = $encounter_id = $encounterId;

        $information['certificate'] = $certificate .' Certificate';

        $information['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate', 'flddod', 'fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();
        if($certificate == 'discharge'){
            
                
         
                $information['AdviceOfDischargeExamGeneral'] = ExamGeneral::select('flddetail', 'fldtime')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldinput', 'Notes')
                    ->where('flditem', 'Advice on Discharge')
                    ->get();
          
                $information['bed'] = PatTiming::select('fldid', 'flditem', 'fldfirsttime', 'fldsecondtime', 'fldsecondreport')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldtype', 'General Services')
                    ->where('fldfirstreport', 'Bed')
                    ->get();
           
                $information['cause_of_admission'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Cause of Admission'])
                    ->get();
           
                $information['generalExamProgressCliniciansNurses'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                    ->where('fldencounterval', $encounter_id)
                    ->where(function ($query) {
                        return $query
                            ->orWhere('flditem', '=', 'Progress Note')
                            ->orWhere('flditem', '=', 'Clinicians Note')
                            ->orWhere('flditem', '=', 'Nurses Note');
                    })
                    ->get();
            
                $information['ConditionOfDischargeExamGeneral'] = ExamGeneral::select('flddetail', 'fldtime')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldinput', 'Notes')
                    ->where('flditem', 'Condition of Discharge')
                    ->get();
            
                $information['Consultations'] = Consult::select('fldconsultname', 'fldconsulttime', 'fldstatus')
                    ->where('fldencounterval', $encounter_id)
                    ->get();
           
                $information['demographics'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldinput', 'Demographics')
                    ->get();
           
                $information['DischargeExaminationspatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldsave', '1')
                    ->where('fldinput', 'Discharge examinations')
                    ->get();
            
                $information['allergy_drugs'] = PatFindings::select('fldcode', 'fldcodeid')
                    ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Allergic Drugs', 'fldsave' => '1'])
                    ->get();
           
                $information['equipment'] = PatTiming::select('flditem', 'fldfirsttime', 'fldsecondtime', 'fldsecondreport')
                    ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Equipment'])
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
                $data['EssentialExaminations'] = $opdData;
           
                $information['planned'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Extra Procedures', 'fldreportquali' => 'Planned'])
                    ->get();
            
                $information['final_diagnosis'] = PatFindings::select('fldcode', 'fldcodeid')
                    ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Final Diagnosis', 'fldsave' => '1'])
                    ->get();
            
                $information['IPMonitoringPatPlanning'] = PatPlanning::select('fldid', 'fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldplancategory', 'IP Monitoring')
                    ->get();
           
                $information['initial_planning'] = ExamGeneral::select('flddetail', 'fldtime')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes', 'flditem' => 'Initial Planning'])
                    ->get();
           
                $information['reportedPatLab'] = PatLabTest::where('fldencounterval', $encounter_id)
                    ->with(['patTestResults', 'subTest', 'testLimit']);
                if (\App\Utils\Options::get('show_verified') == '1')
                    $information['reportedPatLab']->where('fldstatus', 'Verified');
                else
                    $information['reportedPatLab']->where(function ($query) {
                        $query->where('fldstatus', 'Reported');
                        $query->orWhere('fldstatus', 'Verified');
                    });
                $information['reportedPatLab'] =$information['reportedPatLab']->get();
            
                $information['procedures'] = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Procedures', 'fldreportquali' => 'Done'])
                    ->get();
            
                $information['medicated_history'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Medication History'])
                    ->get();

                   
           
                $information['MedicationUsed'] = $mainDataForPatDosing = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'flditemtype','fldlevel')
                ->where('fldencounterval', $encounter_id)
                ->where('flditemtype', 'Medicines')
                ->where(function ($query) {
                    return $query
                        ->orWhere('fldstatus', '=', 'Registered')
                        ->orWhere('fldstatus', '=', 'Admitted')
                        ->orWhere('fldstatus', '=', 'Recorded');
                })
                ->get();
        
           
                $information['minor_procedure'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Minor Procedures', 'fldreportquali' => 'Done'])
                    ->get();
            
                $information['occupational_history'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Occupational History'])
                    ->get();
            
                $information['personal_history'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Personal History'])
                    ->get();
            
                $information['planned'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Extra Procedures', 'fldreportquali' => 'Planned'])
                    ->get();
            
                $information['present_symptoms'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'fldreportquanti', 'flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Patient Symptoms', 'fldsave' => '1'])
                    ->get();
            
                $information['provisinal_diagnosis'] = PatFindings::select('fldcode', 'fldcodeid')
                    ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Provisional Diagnosis', 'fldsave' => '1'])
                    ->get();
           
                $information['social_history'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Social History'])
                    ->get();
           
                $information['surgical_history'] = ExamGeneral::select('flddetail')
                    ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Surgical History'])
                    ->get();
           
                $information['triage_examinations'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                    ->where(['fldencounterval' => $encounter_id, 'fldsave' => '1', 'fldinput' => 'Triage examinations'])
                    ->get();
          

        }else{
            $options      = unserialize(Options::get($certificate.'_pdf_options'));
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
                    $information['initial_planning'] = ExamGeneral::select('flddetail', 'fldtime')
                        ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes', 'flditem' => 'Initial Planning'])
                        ->get();
                }
                if ($item == "Laboratory Tests") {
                    $information['reportedPatLab'] = PatLabTest::where('fldencounterval', $encounter_id)
                        ->with(['patTestResults', 'subTest', 'testLimit']);
                    if (\App\Utils\Options::get('show_verified') == '1')
                        $information['reportedPatLab']->where('fldstatus', 'Verified');
                    else
                        $information['reportedPatLab']->where(function ($query) {
                            $query->where('fldstatus', 'Reported');
                            $query->orWhere('fldstatus', 'Verified');
                        });
                    $information['reportedPatLab'] = $information['reportedPatLab']->get();
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
                    $information['MedicationUsed'] = $mainDataForPatDosing = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'flditemtype','fldlevel')
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
        }

       
        // dd($information['MedicationUsed']);
        return view('outpatient::pdf.discharge', $information)/*->setPaper('a4')->stream($certificate.'.pdf')*/;
    } 

    private function _getAllMedicine($encounter_id)
    {
       
        $allMedicines = \App\PatDosing::select('fldid', 'fldroute', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'fldlabel', 'flditem', 'flduserid_order', 'flddiscper', 'fldtaxper', 'fldstarttime', 'fldstock', 'fldvatamt', 'fldvatper')
            ->with('medicine', 'medicine.medbrand', 'medicine.medbrand.label')
            ->where('fldencounterval', $encounter_id);
       
            $allMedicines = $allMedicines->where([
                'fldsave_order' => '1',
            ])->where(function ($query) {
                $query->where('flditemtype', 'Medicines');
                $query->orWhere('flditemtype', 'Surgicals');
                $query->orWhere('flditemtype', 'Extra Items');
            });
       

        return $allMedicines->get();
    }
}
