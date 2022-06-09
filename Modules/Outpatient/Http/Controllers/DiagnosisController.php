<?php

namespace Modules\Outpatient\Http\Controllers;

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
use App\Nepalicalendar;
use App\Code;
use App\DiagnoGroup;
use App\Utils\Helpers;
use Carbon\Carbon;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class DiagnosisController extends Controller
{
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

    function diagnosisStore(Request $request)
    {
        // echo "here store"; exit;
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
            $fldid = PatFindings::insertGetId($data);

            $patdiago = PatFindings::where('fldencounterval', $request->patient_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
            $html = '';
            if(isset($patdiago) and count($patdiago) > 0){
                foreach ($patdiago as $key => $value) {
                    $html.='<option value="'.$value->fldid.'">'.$value->fldcode.'</option>';
                }
            }
            echo $html; exit;
            
        } catch (\Exception $e) {
           // dd($e);
            session()->flash('error_message', __('Error While Adding Diagnosis'));

            return redirect()->back();
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
        if (isset($data) and count($data) > 0) {
            foreach ($data as $d) {
                $html .= '<tr><td><input type="checkbox" class="dccat" name="dccat" value="' . $d['code'] . '"/></td><td>' . $d['code'] . '</td><td>' . $d['name'] . '</td></tr>';
            }
        } else {
            $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
        }
        echo $html;
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

    public function getDiagnosisByCodeSearch(Request $request)
    {
        $html = '';
        // echo $request->get('term').'-'.$request->get('query'); exit;
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
                    if($request->get('query') !=''){
                        $searchtextlength = strlen($request->get('query'));
                        $compare  = substr($d, 0, $searchtextlength);
                        // echo $compare; exit;
                        if(ucfirst($request->get('query')) == $compare){
                            
                            $html .= '<tr><td><input type="checkbox" class="diagnosissub" name="diagnosissub" value="' . $d . '"/></td><td>' . $d . '</td</tr>';
                        }
                    }else{
                        $html .= '<tr><td><input type="checkbox" class="diagnosissub" name="diagnosissub" value="' . $d . '"/></td><td>' . $d . '</td</tr>';
                    }
                    
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
            $diagnosiscategories = DiagnoGroup::select('flditemname', 'fldcodeid')->where('fldgroupname', $groupname)->get();
            // dd($diagnosiscategories);
            if (isset($diagnosiscategories) and count($diagnosiscategories) > 0) {
                foreach ($diagnosiscategories as $dc) {
                    $html .= '<tr><td><input type="checkbox" class="dccat" name="dccat" value="' . $dc['fldcodeid'] . '"/></td><td>' . $dc['fldcodeid'] . '</td><td>' . $dc['flditemname'] . '</td></tr>';
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

    public function getObstetricData(Request $request)
    {
        // echo "here obstetric data"; exit;
        //Obstetric Queries
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
        // dd($data['gravida']);
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

        $html = view('outpatient::dynamic-views.obstetric-data', $data)->render();
        return $html;
    }

    public function getEdddate(Request $request)
    {
        $finaldate = '';
        $totaldays = 211;
        $lmpdatedate = $request->date;
        $finaldate = date('d-m-Y', strtotime($lmpdatedate . ' + ' . $totaldays . ' days'));
        return $finaldate;
    }

    public function saveObstetricRequest(Request $request)
    {
        // echo "here obstetric save function"; exit;
       // return $request->all();
        // dd($request);
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

                #tblpatientexam ma insert garaune
                // $patExam['fldencounterval'] = $request->encounter;
                // $patExam['fldserialval'] = NULL;
                // $patExam['fldinput'] = 'General Parameters';
                // $patExam['fldtype'] = 'Qualitative';
                // $patExam['fldhead'] = 'Pregnancy Status'; //need check
                // $patExam['fldsysconst'] = NULL;
                // $patExam['fldmethod'] = 'Regular';
                // $patExam['fldrepquali'] = '3rd trimester';
                // $patExam['fldrepquanti'] = 0;
                // $patExam['fldfilepath'] = NULL;
                // $patExam['flduserid'] = Helpers::getCurrentUserName();
                // $patExam['fltime'] = $mytime->toDateTimeString();
                // $patExam['fldcomp'] = Helpers::getCompName();
                // $patExam['fldsave'] = 1;
                // $patExam['fldabnormal'] = 0;
                // $patExam['flduptime'] = NULL;
                // $patExam['xyz'] = 0;
                // PatientExam::insert($patExam);
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
            if(isset($patdiago) and count($patdiago) > 0){
                foreach ($patdiago as $key => $value) {
                    $html.='<option value="'.$value->fldid.'">'.$value->fldcode.'</option>';
                }
            }
            echo $html; exit;
        } catch (\GearmanException $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Obstetric Diagnosis'));

            return redirect()->back();
        }
    }

    public function englishtonepali(Request $request)
    {

        $value = $request->date;
        $cal = new Nepalicalendar();
        list($y, $m, $d) = explode("-", $value);
        $date = $cal->eng_to_nep($y, $m, $d);

        if($date['month'] <= 9){
            $month = '0'.$date['month'];
        }else{
            $month = $date['month'];
        }

        if($date['date'] <= 9){
            $days = '0'.$date['date'];
        }else{
            $days = $date['date'];
        }
        $nepalidate = $date['year'] . '-' . $month . '-' . $days;
        echo $nepalidate;
        exit;
    }

    
    public function nepalitoenglish(Request $request)
    {

        $value = $request->date;
        // echo $value; exit;
        $cal = new Nepalicalendar();
        list($y, $m, $d) = explode("-", $value);
        $date = $cal->nep_to_eng($y, $m, $d);


        $nepalidate = $date['date'].'-'.$date['month'].'-'.$date['year'];
        echo $nepalidate;
        exit;
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

        $html = view('outpatient::dynamic-views.diagnosis-freetext', $data)->render();
        return $html;
    }

    public function saveDiagnosisCustom(Request $request)
    {
        try {
            if($request->custom_diagnosis !=''){
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
                $patdiagno = PatFindings::where('fldtype',$request->fldinput)->where('fldencounterval', $request->encounter)->where('fldsave', 1)->get();
                // dd($patdrug); exit;
                if(isset($patdiagno) and !empty($patdiagno)){
                    foreach ($patdiagno as $key => $pat) {
                        $html .= '<option value="'.$pat->fldid.'">'.$pat->fldcode.'</option>';

                    }
                }
                echo $html; exit;

            }else{
                session()->flash('error_message', __('Error While Adding Diagnosis'));

                return redirect()->back();
            }
            
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Diagnosis'));

            return redirect()->back();
        }
    }


}
