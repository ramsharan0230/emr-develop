<?php

namespace Modules\Physiotherapy\Http\Controllers;

use App\ExamGeneral;
use App\PatFindings;
use App\PatientExam;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class DiagnosisController extends Controller
{

    public function saveDiagnosisCustom (Request $request) {
        try {

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

                $latestpatfindings = PatFindings::orderBy('fldid', 'DESC')->first();
                $html = '';
//                $patdiagno = PatFindings::where('fldtype', $request->fldinput)->where('fldencounterval', $request->encounter)->where('fldsave', 1)->get();
                // dd($patdiagno); exit;
                $patdiagno = $latestpatfindings;
                if (isset($latestpatfindings) and !empty($latestpatfindings)) {
//                    foreach ($patdiagno as $key => $pat) {
//                        $html .= '<option value="' . $pat->fldid . '">' . $pat->fldcode . '</option>';
//
//                    }
                    $html .= '<option value="' . $latestpatfindings->fldid . '">' . $latestpatfindings->fldcode . '</option>';
                }
                return response()->json([
                    'html' => $html
                ]);
            } else {
                session()->flash('error_message', __('Error While Adding Diagnosis'));

                return response()->json([
                    'error' => 'data not found'
                ]);

            }

        } catch (\Exception $e) {
//            dd($e);
//            session()->flash('error_message', __('Error While Adding Diagnosis'));

            return response()->json([
                'error' => 'exception error'
            ]);
        }
    }

    public function DeleteDiagnosisItem(Request $request) {

        try {

            $ids = $request->ids;
            $diagnosistypemessage = $request->diagnosistypemessage;
            // echo $ids; exit;
            $finalids = explode(',', $ids);
            foreach ($finalids as $id) {
                $datas = array(
                    'fldsave' => 0,
                    'xyz' => 0
                );
                PatFindings::where('fldid', $id)->update($datas);
            }

            return response()->json([
                'delete_success_message' => $diagnosistypemessage
            ]);

        } catch (\Exception $e) {
            // dd($e);

            return response()->json([
                'error' => 'exception error'
            ]);
        }
    }

    function diagnosisStore(Request $request)
    {

        try {

            $diagnosistype = $request->diagnosis_type;

            $mytime = Carbon::now();
            $data['fldencounterval'] = $request->encounter_id;
            $data['fldtype'] = $diagnosistype.' '.'Diagnosis';
            $data['fldcode'] = $request->diagnosissubname;
            $data['fldcodeid'] = $request->dccat;
            $data['flduserid'] = Helpers::getCurrentUserName();
            $data['fldtime'] = $mytime->toDateTimeString();
            $data['fldcomp'] = Helpers::getCompName();
            $data['fldsave'] = 1;
            $data['xyz'] = 0;
            $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

            PatFindings::insert($data);
            $patdiago = PatFindings::where('fldencounterval', $request->encounter_id)->where('fldtype', 'Provisional Diagnosis Physiotherapy')->where('fldsave', 1)->get();
            $latestpatfindings = PatFindings::where('fldtype', $diagnosistype.' '.'Diagnosis')->where('fldsave', 1)->orderBy('fldid', 'DESC')->first();

            $html = '';
            if (isset($latestpatfindings) and !empty($latestpatfindings)) {
//                foreach ($patdiago as $key => $value) {
//                    $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
//                }
                $html .= '<option value="' . $latestpatfindings->fldid . '">' . $latestpatfindings->fldcode . '</option>';
            }
            return response()->json([
                'html' => $html,
                'diagnosistype' =>  $diagnosistype
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'exception error'
            ]);
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
            $latestpatfindings = PatFindings::where('fldencounterval', $request->encounter_id)->where('fldtype', 'Final Diagnosis')->where('fldsave', 1)->orderBy('fldid', 'DESC')->first();
            $html = '';
            if (isset($latestpatfindings) and !empty($latestpatfindings)) {
//                foreach ($patdiago as $key => $value) {
//                    $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
//                }
                $html .= '<option value="' . $latestpatfindings->fldid . '">' . $latestpatfindings->fldcode . '</option>';
            }
            return response()->json([
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'exception error'
            ]);
        }
    }

    public function saveObstetricRequest(Request $request)
    {
        // echo "here obstetric save function"; exit;
        //        return $request->all();
        try {

            $diagnosistype = $request->diagnosis_type_obs;

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
                $patData['fldtype'] = $diagnosistype.' '.'Diagnosis';
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
                $patData['fldtype'] = $diagnosistype.' '.'Diagnosis';
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
            $latestpatfindings = PatFindings::where('fldencounterval', $encounterId)->where('fldtype', $diagnosistype.' '.'Diagnosis')->where('fldsave', 1)->orderBy('fldid', 'DESC')->first();
            $html = '';
            if (isset($latestpatfindings) and !empty($latestpatfindings)) {
//                foreach ($patdiago as $key => $value) {
//                    $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
//                }
                $html .= '<option value="' . $latestpatfindings->fldid . '">' . $latestpatfindings->fldcode . '</option>';
            }
            return response()->json([
                'html' => $html,
                'diagnosistype' => $diagnosistype
            ]);
        } catch (\Exception $e) {
//            dd($e);
            return response()->json([
                'error' => 'exception error'
            ]);
        }
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
            $latestpatfindings = PatFindings::where('fldencounterval', $encounterId)->where('fldtype', 'Final Diagnosis')->where('fldsave', 1)->orderBy('fldid', 'DESC')->first();
            dd($latestpatfindings);
            $html = '';
            if (isset($latestpatfindings) and !empty($latestpatfindings)) {
//                foreach ($patdiago as $key => $value) {
//                    $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
//                }
                $html .= '<option value="' . $latestpatfindings->fldid . '">' . $latestpatfindings->fldcode . '</option>';
            }
            return response()->json([
                'html' => $html
            ]);
        } catch (\Exception $e) {

//            dd($e);
            return response()->json([
                'error' => 'exception error'
            ]);
        }
    }

}
