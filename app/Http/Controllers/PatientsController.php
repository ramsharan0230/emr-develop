<?php

namespace App\Http\Controllers;

use App\BillingSet;
use App\Complaints;
use App\Consult;
use App\Encounter;
use App\Exam;
use App\ExamGeneral;
use App\PatFindings;
use App\Pathdosing;
use App\PatientExam;
use App\PatientInfo;
use App\Test;
use App\Code;
use App\DiagnoGroup;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class PatientsController extends Controller
{

    //


    public function index()
    {

        $data = array();
        $patient_id = 1;
        $data['patient_id'] = $patient_id;
        $data['laboratory'] = $laboratory = Test::get();
        $data['complaint'] = $complaint = Complaints::get();
        $data['finding'] = $finding = Exam::get();
        $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $patient_id)->first();
        $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
        //select fldrepquanti from tblpatientexam where fldencounterval='1' and fldhead='Body Weight' and fldtype='Quantitative' and fldsave='1'
        $data['patientexam'] = $patientexam_findings = PatientExam::where('fldencounterval', $patient_id)->where('fldinput', 'OPD Examination')->where('fldsave', '1')->get();
        $data['billingset'] = $billingset = BillingSet::get();
        $data['history'] = $history = ExamGeneral::where('fldencounterval', $patient_id)->where('fldinput', 'History')->where('flditem', 'History of Illness')->get();
        $data['notes'] = $notes = ExamGeneral::where('fldencounterval', $patient_id)->where('fldinput', 'Notes')->where('flditem', 'Initial Planning')->get();
        $data['patdiago'] = $patdiago = PatFindings::where('fldencounterval', $patient_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
        $data['patdrug'] = $patdrug = PatFindings::where('fldencounterval', $patient_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
        $data['examgeneral'] = $examgeneral = ExamGeneral::where('fldencounterval', $patient_id)->where('fldinput', 'Presenting Symptoms')->where('fldsave', 1)->get();
        $data['exam_systolic'] = $exam_systolic = Exam::where('fldsysconst', 'BloodPressure_Systolic')->get();
        $data['systolic_bp'] = $systolic_bp = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'Systolic BP')->orderBy('fldid', 'desc')->first();
        $data['exam_diastolic'] = $exam_diastolic = Exam::where('fldsysconst', 'BloodPressure_Diastolic')->get();
        $data['diasioli_bp'] = $diasioli_bp = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'Diastolic BP')->orderBy('fldid', 'desc')->first();
        $data['exam_pulse'] = $exam_pulse = Exam::where('fldsysconst', 'Pulse_Rate')->first();
        $data['pulse'] = $pulse = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'Pulse Rate')->orderBy('fldid', 'desc')->first();
        //dd($data['pulse']);
        $data['exam_temperature'] = $exam_temperature = Exam::where('fldsysconst', 'Temperature_System')->first();
        $data['temperature'] = $temperature = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'Pulse RatePulse Rate')->orderBy('fldid', 'desc')->first();
        $data['exam_respiratory_rate'] = $exam_respiratory_rate = Exam::where('fldsysconst', 'Respiration_Rate')->first();
        $data['respiratory_rate'] = $respiratory_rate = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'Respiratory Rate')->orderBy('fldid', 'desc')->first();
        $data['exam_o2_saturation'] = $exam_o2_saturation = Exam::where('fldsysconst', 'Oxygen_Saturation')->first();

        $data['o2_saturation'] = $o2_saturation = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'O2 Saturation')->first();
        $data['consults'] = $consult = Consult::where('fldencounterval', $patient_id)->first();


        // $diagnosis = $this->readExcel();
        // echo $diagnosis; exit;
        $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $patient_id)->get();
        //       dd($patientallergicdrugs);
        $data['allergicdrugs'] = Code::select('fldcodename')->whereNotIn('fldcodename', $patientallergicdrugs)->get();
        //dd($data['allergicdrugs']);
        $data['diagnosisgroup'] = DiagnoGroup::select('fldgroupname')->distinct()->get();
        $diagnocat = $this->getInitialDiagnosisCategory();
        // dd($diagnocat);
        $data['diagnosiscategory'] = $diagnocat;
        $data['o2_saturation'] = $o2_saturation = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'O2 Saturation')->orderBy('fldid', 'desc')->first();
        $data['consults'] = $consult = Consult::where('fldencounterval', $patient_id)->first();

        //select fldhead,fldrepquanti,fldrepquali,fldtype,fldinput,fldabnormal from tblpatientexam where fldid=1022 and fldencounterval='1'
        //INSERT INTO `tblexamgeneral` ( `fldencounterval`, `fldinput`, `fldtype`, `flditem`, `fldreportquali`, `fldreportquanti`, `flddetail`, `flduserid`, `fldtime`, `fldcomp`, `fldsave`, `flduptime`, `xyz` ) VALUES ( '1', 'Presenting Symptoms', 'Qualitative', 'Abnormal movements highly suggestive of pseudoseizure', 'Right Side', 9240, NULL, 'admin', '2020-02-06 15:15:12.986', 'comp01', '1', NULL, '0' )
        //select fldid,fldencounterval,fldtype,flditem,fldreportquanti,fldreportquali,fldid,fldid,fldtime,flddetail from tblexamgeneral where fldencounterval='1' and fldinput='Presenting Symptoms' and fldsave='1'
        //UPDATE `tblexamgeneral` SET `flddetail` = 'test', `flduptime` = '2020-02-06 15:17:44.384', `xyz` = '0' WHERE `fldid` = 944
        return view('frontend/outpatient_form_new', $data);

        //INSERT INTO `tblactivity` ( `fldfrmname`, `fldcategory`, `fldactivity`, `fldcomment`, `flduserid`, `fldtime`, `fldcomp`, `fldsave`, `fldhostmac` ) VALUES ( 'fmPatCliNew', 'Patient Data', 'EncounterID', '1', 'admin', '2020-02-06 15:09:43.164', 'comp01', '1', '50:5b:c2:ee:97:25' )
        //select fldvalue from tblsettings where fldindex='comp01:BillingGroup/DefaultMode'
        //select fldvalue from tblsettings where fldindex='comp01:BillingGroup/ScheduleMode'
        //select fldvalue from tblsettings where fldindex='comp01:BillingGroup/FixMode'
    }

    public function getData(Request $request)
    {
        $data = array();
        $patient_id = $request->patient_id;

        $encounter_id =

            $data['laboratory'] = $laboratory = Test::get();
        $data['complaint'] = $complaint = Complaints::get();
        $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $patient_id)->first();
        $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
        //select fldrepquanti from tblpatientexam where fldencounterval='1' and fldhead='Body Weight' and fldtype='Quantitative' and fldsave='1'
        $data['patientexam'] = $patientexam = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', '1')->first();
        $data['billingset'] = $billingset = BillingSet::get();
        $data['history'] = $history = ExamGeneral::where('fldencounterval', $patient_id)->where('fldinput', 'History')->where('flditem', 'History of Illness')->get();
        $data['notes'] = $notes = ExamGeneral::where('fldencounterval', $patient_id)->where('fldinput', 'Notes')->where('flditem', 'Initial Planning')->get();
        $data['patdiago'] = $patdiago = PatFindings::where('fldencounterval', $patient_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
        $data['patdrug'] = $patdrug = PatFindings::where('fldencounterval', $patient_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
        $data['examgeneral'] = $examgeneral = ExamGeneral::where('fldencounterval', $patient_id)->where('fldinput', 'Presenting Symptoms')->where('fldsave', 1)->get();
        $data['exam_systolic'] = $exam_systolic = Exam::where('fldsysconst', 'BloodPressure_Systolic')->get();
        $data['systolic_bp'] = $systolic_bp = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'Systolic BP')->first();
        $data['exam_diastolic'] = $exam_diastolic = Exam::where('fldsysconst', 'BloodPressure_Diastolic')->get();
        $data['diasioli_bp'] = $diasioli_bp = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'Diastolic BP')->first();
        $data['exam_pulse'] = $exam_pulse = Exam::where('fldsysconst', 'Pulse_Rate')->first();
        $data['pulse'] = $pulse = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'Pulse Rate')->first();
        $data['exam_temperature'] = $exam_temperature = Exam::where('fldsysconst', 'Temperature_System')->first();
        $data['temperature'] = $temperature = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'Pulse RatePulse Rate')->first();
        $data['exam_respiratory_rate'] = $exam_respiratory_rate = Exam::where('fldsysconst', 'Respiration_Rate')->first();
        $data['respiratory_rate'] = $respiratory_rate = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'Respiratory Rate')->first();
        $data['exam_o2_saturation'] = $exam_o2_saturation = Exam::where('fldsysconst', 'Oxygen_Saturation')->first();
        $data['o2_saturation'] = $o2_saturation = PatientExam::where('fldencounterval', $patient_id)->where('fldsave', 1)->where('fldhead', 'O2 Saturation')->first();
        $data['consults'] = $consult = Consult::where('fldencounterval', $patient_id)->first();

        //select fldhead,fldrepquanti,fldrepquali,fldtype,fldinput,fldabnormal from tblpatientexam where fldid=1022 and fldencounterval='1'
        //INSERT INTO `tblexamgeneral` ( `fldencounterval`, `fldinput`, `fldtype`, `flditem`, `fldreportquali`, `fldreportquanti`, `flddetail`, `flduserid`, `fldtime`, `fldcomp`, `fldsave`, `flduptime`, `xyz` ) VALUES ( '1', 'Presenting Symptoms', 'Qualitative', 'Abnormal movements highly suggestive of pseudoseizure', 'Right Side', 9240, NULL, 'admin', '2020-02-06 15:15:12.986', 'comp01', '1', NULL, '0' )
        //select fldid,fldencounterval,fldtype,flditem,fldreportquanti,fldreportquali,fldid,fldid,fldtime,flddetail from tblexamgeneral where fldencounterval='1' and fldinput='Presenting Symptoms' and fldsave='1'
        //UPDATE `tblexamgeneral` SET `flddetail` = 'test', `flduptime` = '2020-02-06 15:17:44.384', `xyz` = '0' WHERE `fldid` = 944
        return view('frontend/outpatient_form', $data);

        //INSERT INTO `tblactivity` ( `fldfrmname`, `fldcategory`, `fldactivity`, `fldcomment`, `flduserid`, `fldtime`, `fldcomp`, `fldsave`, `fldhostmac` ) VALUES ( 'fmPatCliNew', 'Patient Data', 'EncounterID', '1', 'admin', '2020-02-06 15:09:43.164', 'comp01', '1', '50:5b:c2:ee:97:25' )
        //select fldvalue from tblsettings where fldindex='comp01:BillingGroup/DefaultMode'
        //select fldvalue from tblsettings where fldindex='comp01:BillingGroup/ScheduleMode'
        //select fldvalue from tblsettings where fldindex='comp01:BillingGroup/FixMode'
    }


    // getting all the Notes,History,Presenting Symptoms with below query
    // select fldid,fldencounterval,fldtype,flditem,fldreportquanti,fldreportquali,fldid,fldid,fldtime,flddetail from tblexamgeneral where fldencounterval='1' and fldinput='Presenting Symptoms' and fldsave='1'


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


            session()->flash('success_message', __('Diagnosis Added Successfully.'));

            return redirect()->route('patient');
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Diagnosis'));

            return redirect()->route('patient');
        }
    }



    function insert_allergydrugstore(Request $request)
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
                    PatFindings::insert($data);
                }

                session()->flash('success_message', __('Allergic Drugs Added.'));

                return redirect()->route('patient');
            } else {
                session()->flash('error_message', __('Error While Adding Allergic Drugs'));

                return redirect()->route('patient');
            }
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Allergic Drugs'));

            return redirect()->route('patient');
        }
    }


    public function searchDrugs()
    {
        $html = '';
        // $patientallergicdrugs = '';
        $searchtext = $_GET['term'];
        $patient_id = $_GET['patient_id'];
        $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $patient_id)->get();
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
        $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $patient_id)->get();
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
            if(isset($data) and count($data) > 0){
                foreach($data as $d){
                     $html .='<tr><td><input type="checkbox" class="dccat" name="dccat" value="'.$d['code'].'"/></td><td>'.$d['code'].'</td><td>'.$d['name'].'</td></tr>';
                }
            }else{
                 $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
            }
        echo $html;
    }

    public function getInitialDiagnosisCategoryByAlphabet()
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
            $diagnosiscategories = DiagnoGroup::select('flditemname', 'fldcodeid')->where('fldgroupname',$groupname)->get();
            // dd($diagnosiscategories);
            if(isset($diagnosiscategories) and count($diagnosiscategories) > 0){
                foreach($diagnosiscategories as $dc){
                    $html .= '<tr><td><input type="checkbox" class="dccat" name="dccat" value="'.$dc['fldcodeid'].'"/></td><td>'.$dc['fldcodeid'].'</td><td>'.$dc['flditemname'].'</td></tr>';
                }

            }else{
                 $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
            }
        } else {
             $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
        }
        echo $html; exit;
    }

    public function searchDiagnosis()
    {
        $html = '';
        $searchtext = $_GET['term'];
        // echo $searchtext; exit;


        $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
        $data = [];
        while ($csvLine = fgetcsv($handle, 1000, ";")) {
            // dd($csvLine);
            if (substr($csvLine[3], 0, strlen($searchtext)) == $searchtext) {
                $data[] = [
                    'code' => trim($csvLine[1]),
                    'name' => trim($csvLine[3]),
                ];
            }
        }

        if (count($data) > 1) {
            // alert('dherai aayo');
            sort($data);
            foreach ($data as $key => $value) {
                $html .= '<tr><td><input type="checkbox"  name="dccat" class="dccat" value="' . $value['code'] . '"></td><td>' . $value['code'] . '</td><td>' . $value['name'] . '</td></tr>';
            }
        } else {
            $html = '<tr><td>No Diagnosis Available For' . $searchtext . '</td></tr>';
        }
        echo $html;
    }

    function deletepatfinding($id)
    {
        // echo "here"; exit;
        try {
            $data = array(
                'fldsave' => 0,
                'xyz' => 0
            );
            PatFindings::where('fldid', $id)->update($data);
            // DB::table('tblpatfindings')->where('fldid', $id)->update(['fldsave'=>0,'xyz'=>0]);


            session()->flash('success_message', __('Patient Findings Deleted Successfully'));
            return redirect()->route('patient');
        } catch (\Exception $e) {
            // dd($e);
            session()->flash('error_message', __('Patient Findings Not Deleted'));
            return redirect()->route('patient');
        }
    }




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
            'flduserid' => $request->flduserid,
            'fldtime' => now(),
            'fldcomp' => $request->fldcomp,
            'fldsave' => $request->test,
            'flduptime' => $request->test,
            'xyz' => $request->test
        );
        ExamGeneral::insert($data);

        return redirect()->route('patient');
    }


    function insert_complaint(Request $request)
    {
        //dd($request);
        $hours = $request->duration;

        if ($request->duration_type == 'Days')
            $hours = $request->duration * 24;

        if ($request->duration_type == 'Weeks')
            $hours = $request->duration * 7 * 24;

        if ($request->duration_type == 'Months')
            $hours = $request->duration * 30 * 24;

        if ($request->duration_type == 'Years')
            $hours = $request->duration * 364 * 24;


        $lastest_sample = ExamGeneral::orderBy('fldid', 'desc')->limit(1)->get();
        if ($lastest_sample)
            $latest_id = $lastest_sample[0]->fldid + 1;
        else
            $latest_id = 1;

        $data = array(
            'fldid' => $latest_id,
            'fldencounterval' => $request->fldencounterval,
            'fldinput' => 'Presenting Symptoms',
            'fldtype' => 'Qualitative',
            'flditem' => $request->flditem,
            'fldreportquali' => $request->fldreportquali, //'On/Off'
            'fldreportquanti' => $hours, //hr ma convert garne
            'flddetail' => '', // pachi edit ma free writing gareko store jun user bhanne coulmn ma dekhcha
            'flduserid' => $request->flduserid, //admin
            'fldtime' => now(), //'2020-02-23 11:13:27.709'
            'fldcomp' => $request->fldcomp, //comp01
            'fldsave' => 1, //1
            'flduptime' => NULL, // null ????
            'xyz' => 0,
        );



        ExamGeneral::insert($data);

        $lastest_sam = ExamGeneral::orderBy('fldid', 'desc')->limit(1)->get();

        // dd($id);


        if ($lastest_sam[0]->fldid == $latest_id) {
            return response()->json([
                'success' => [
                    'id' => $latest_id,
                    'name' => $request->get('fldsampletype')
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

    function insert_complaint_detail(Request $request)
    {
        $fldid = $request->fldid;
        $data = array(
            'flddetail' => $request->flddetail, // pachi edit ma free writing gareko store jun user bhanne coulmn ma dekhcha
            'fldtime' => now(), //'2020-02-23 11:13:27.709'
            'flduptime' => now(),
        );
        ExamGeneral::where('fldid', $fldid)->update($data);

        return redirect()->route('patient');
    }

    function insert_essential_exam(Request $request)
    {
        // dd($request->essential);
        //        INSERT INTO `tblpatientexam` ( `fldencounterval`, `fldserialval`, `fldinput`, `fldtype`, `fldhead`, `fldsysconst`, `fldmethod`, `fldrepquali`, `fldrepquanti`, `fldfilepath`, `flduserid`, `fldtime`, `fldcomp`, `fldsave`, `fldabnormal`, `flduptime`, `xyz` )
        //        VALUES ( '1', NULL, 'Examination', 'Quantitative', 'Diastolic BP', NULL, 'Manual', '7', 7, NULL, 'admin', '2020-02-23 11:58:00.016', 'comp01', '1', '1', NULL, '0' )

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
                    'flduserid' => $request->flduserid, //admin
                    'fldtime' => now(),
                    'fldcomp' => $request->fldcomp, // comp1
                    'fldsave' => 1, //1
                    'fldabnormal' => 1, //1
                    'flduptime' => NULL, //null
                    'xyz' => 0, // 0
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

    function insert_finding(Request $request)
    {
        //dd($request);

        $lastest_sample = PatientExam::orderBy('fldid', 'desc')->limit(1)->get();
        if ($lastest_sample)
            $latest_id = $lastest_sample[0]->fldid + 1;
        else
            $latest_id = 1;

        $data = array(
            'fldid' => $latest_id,
            'fldencounterval' => $request->fldencounterval,
            'fldserialval' => NULL,
            'fldinput' => 'OPD Examination',
            'fldtype' => $request->fldtype,
            'fldhead' => $request->fldhead,
            'fldsysconst' => NULL,
            'fldmethod' => 'Regular',
            'fldrepquali' => $request->fldrepquali,
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



        PatientExam::insert($data);

        $lastest_sam = PatientExam::orderBy('fldid', 'desc')->limit(1)->get();

        //dd($lastest_sam);


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

    function update_abnormal(Request $request)
    {
        //dd($request);
        $data = array(
            'fldabnormal' => $request->status,
            'updated_at' => config('constants.current_date_time')

        );
        PatientExam::where('fldid', $request->fldid)->update($data);

        return redirect()->route('patient');
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
}
