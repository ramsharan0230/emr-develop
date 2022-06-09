<?php

namespace Modules\Inpatient\Http\Controllers;

use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Exception;

class DataEntryMenuController extends Controller
{

    /**
     * Triage examinations functions
     */
    public function saveTriageExam(Request $request)
    {
        try {
            $examtype = 'Qualitative';
            $qualitative = $request->get('qualitative');
            $quantitative = $request->get('quantitative');
            $examinationid = $request->get('examinationid');
            $abnormal = '0';
            $encounter_id = $request->get('encounterId') ?: \Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();

            $fldid = \App\PatientExam::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldserialval' => NULL,
                'fldinput' => 'Triage examinations',
                'fldtype' => $examtype,
                'fldhead' => $examinationid,
                'fldsysconst' => $examinationid,
                'fldmethod' => 'Regular',
                'fldrepquali' => $qualitative,
                'fldrepquanti' => $quantitative,
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
            $examinationtype = $request->get('examinationtype');
            $observation = ($examinationtype === 'Clinical Scale') ? $quantitative : $qualitative;

            return response()->json([
                'status' => TRUE,
                'data' => [
                    'fldid' => $fldid,
                    'examination' => $examinationid,
                    'abnormal' => $abnormal,
                    'quantative' => $observation,
                    'time' => $time,
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
                'view_data' => (string)view('inpatient::layouts.modaltriage', compact('examid', 'formated_que', 'header', 'type')),
            ];
        } elseif ($type == 'No Selection') {
            $header = 'Enter Quantitative Value';
            $ret_data = [
                'modal' => TRUE,
                'view_data' => (string)view('inpatient::layouts.modaltriage', compact('examid', 'header', 'type')),
            ];
        } elseif ($type == 'Left and Right') {
            $header = 'Left and Right Examination Report';
            $ret_data = [
                'modal' => TRUE,
                'view_data' => (string)view('inpatient::layouts.modaltriage', compact('examid', 'header', 'type')),
            ];
        } elseif ($type == 'Single Selection') {
            $header = 'Single Selection Report';
            $options = \App\ExamOption::select('fldanswer')
                ->where('fldexamid', $examid)
                ->orderBy('fldindex')
                ->get();
            $ret_data = [
                'modal' => TRUE,
                'view_data' => (string)view('inpatient::layouts.modaltriage', compact('examid', 'header', 'type', 'options')),
                'options' => $options,
            ];
        } else {
            $ret_data = [
                'modal' => TRUE,
                'view_data' => (string)view('inpatient::layouts.modaltriage', compact('examid', 'type')),
            ];
        }

        return response()->json($ret_data);
    }

    /**
     * Clinical Demographics functions
     */
    public function getClinicalDemographics(Request $request)
    {
        $encounterId = $request->get('encounterId') ?: \Session::get('inpatient_encounter_id');
        return response()->json(
            \App\ExamGeneral::select('fldid', 'flditem', 'fldreportquali')->where([
                'fldencounterval' => $encounterId,
                'fldinput' => 'Demographics',
            ])->orderBy('fldid')->get()
        );
    }

    public function reportClinicalDemographics(Request $request)
    {
        $encounterId = $request->get('encounterId') ?: \Session::get('inpatient_encounter_id');
        $patientinfo = \App\Utils\Helpers::getPatientByEncounterId($encounterId);
        $all_data = \App\ExamGeneral::select('fldid', 'flditem', 'fldreportquali')->where([
            'fldencounterval' => $encounterId,
            'fldinput' => 'Demographics',
        ])->orderBy('fldid')->get();

        return \Barryvdh\DomPDF\Facade::loadView('inpatient::pdf.demographics', compact('patientinfo', 'all_data'))
            ->stream('ClinicalDemographics_ report.pdf');
    }

    public function saveClinicalDemographics(Request $request)
    {
        try {
            $flditem = $request->get('flditem');
            $fldreportquali = $request->get('fldreportquali');

            $encounter_id = $request->get('encounterId') ?: \Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();

            $patientExamId = \App\ExamGeneral::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Demographics',
                'fldtype' => 'Qualitative',
                'flditem' => $flditem,
                'fldreportquali' => $fldreportquali,
                'fldreportquanti' => 0,
                'flddetail' => NULL,
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'flduptime' => NULL,
                'xyz' => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            return response()->json([
                'status' => TRUE,
                'data' => [
                    'flditem' => $flditem,
                    'fldreportquali' => $fldreportquali,
                ],
                'message' => 'Successfully saved Information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save Information.',
            ]);
        }
    }


    /**
     * Patient Image functions
     */
    public function getPatientImage(Request $request)
    {
        $fldcateg = $request->get('fldcateg');
        $data = \App\PatImageData::select('fldtitle', 'fldpic', 'fldkeyword', 'flddetail', 'fldid')->where([
            'fldencounterval' => $request->get('encounterId') ?: \Session::get('inpatient_encounter_id'),
            'fldsave' => '1',
            'fldcateg' => $fldcateg,
        ])->get();

        $dataForView = array();
        foreach ($data as &$d) {
            $dataPrepare['fldtitle'] = $d->fldtitle;
            $word = "data:image/jpeg;base64,";
            if (strpos($d->fldpic, $word) !== false) {
                $dataPrepare['fldpic'] = $d->fldpic;
            } else {
                $dataPrepare['fldpic'] = "data:image/jpeg;base64," . $d->fldpic;
            }

            $dataPrepare['fldkeyword'] = $d->fldkeyword;
            $dataPrepare['flddetail'] = $d->flddetail;
            $dataPrepare['fldid'] = $d->fldid;
            // $d->fldpic = "data:image/jpeg;base6 4,".base64_encode($d->fldpic);
            array_push($dataForView, $dataPrepare);
        }

        return $dataForView;

    }

    /**
     * Encode array from latin1 to utf8 recursively
     * @param $dat
     * @return array|string
     */
    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
        if (is_string($dat)) {
            return utf8_encode($dat);
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) $ret[$i] = self::convert_from_latin1_to_utf8_recursively($d);

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

            return $dat;
        } else {
            return $dat;
        }
    }

    public function savePatientImage(Request $request)
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'fldcateg' => 'required',
                'fldtitle' => 'required',
                'fldkey' => 'required',
                'flddetail' => 'required',
                'fldimage' => 'required',
            ], [
                'fldcateg.required' => 'The Category is required.',
                'fldtitle.required' => 'The Title is required.',
                'fldkey.required' => 'The Key is required.',
                'flddetail.required' => 'The Detail is required.',
                'fldimage.required' => 'The Image is required.',
            ]);
            if ($validator->fails()) {
                $errors = 'Error while saving information' . PHP_EOL;
                foreach ($validator->getMessageBag()->messages() as $key => $value)
                    $errors .= $value[0] . PHP_EOL;

                return [
                    'status' => FALSE,
                    'message' => $errors,
                ];
            }

            $fldcateg = $request->get('fldcateg');
            $fldtitle = $request->get('fldtitle');
            $fldkey = $request->get('fldkey');
            $flddetail = $request->get('flddetail');

            if (isset($request->webcam)) {
                $image = $request->fldimage;
            } else {
                $image = $request->file('fldimage')->getPathName();

                $image = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image));
            }

            $encounter_id = $request->get('encounterId') ?: \Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();

            $fldid = \App\PatImageData::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldcateg' => $fldcateg,
                'fldtitle' => $fldtitle,
                'fldpic' => $image,
                'fldkeyword' => $fldkey,
                'flddetail' => $flddetail,
                'fldlink' => NULL,
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'flduptime' => NULL,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);


            return response()->json([
                'status' => TRUE,
                'data' => [
                    'fldid' => $fldid,
                    'fldtitle' => $fldtitle,
                    'fldpic' => $image,
                    'fldkeyword' => $fldkey,
                    'flddetail' => $flddetail,
                ],
                'message' => 'Successfully saved Information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save Information.',
            ]);
        }
    }

    public function updatePatientImage(Request $request)
    {
        try {
            $fldid = $request->get('fldid');
            $update_info = [
                'fldtitle' => $request->get('fldtitle'),
                'fldkeyword' => $request->get('fldkey'),
                'flddetail' => $request->get('flddetail')
            ];

            if ($request->webcam === '1') {
                $update_info['fldpic'] = $request->fldimage;
            } else {
                $image = $request->file('fldimage')->getPathName();
                $image = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image));

                $update_info['fldpic'] = $image;
            }

            \App\PatImageData::where([['fldid', $fldid]])->update($update_info);

            return response()->json([
                'status' => TRUE,
                'data' => $update_info,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
//            return $e;
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to update Information.',
            ]);
        }
    }
}
