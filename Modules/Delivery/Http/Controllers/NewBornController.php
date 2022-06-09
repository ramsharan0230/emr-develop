<?php

namespace Modules\Delivery\Http\Controllers;

use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Utils\Helpers;

class NewBornController extends Controller
{
    // fixed
    public function getExaminations()
    {
        return response()->json(
            \App\DepartmentExam::select('fldexamid', 'fldtype', 'fldsysconst', 'fldtanswertype')
                ->where('flddept', 'Baby Examination')
                ->get()
        );
    }

    public function getChildren()
    {
        return response()->json(
            \App\Confinement::select('e.fldencounterval')->join('tblencounter AS e', 'e.fldpatientval', '=', 'tblconfinement.fldbabypatno')->where('tblconfinement.fldencounterval', \Session::get('delivery_encounter_id'))->get()
        );
    }

    public function getChildData(Request $request)
    {
        $encounter_id = $request->get('fldencounterval');
        $baby_data = \App\Encounter::select('fldencounterval', 'fldpatientval', 'fldrank')
            ->where('fldencounterval', $encounter_id)
            ->with('patientInfo:fldpatientval,fldptbirday,fldptsex,fldrank')
            ->first();
        $baby_data->patientInfo->birthhours = \Carbon\Carbon::parse($baby_data->patientInfo->fldptbirday)->diffInHours(\Carbon\Carbon::now()) . "Hrs";

        // exams
        $baby_data->examinations = \App\PatientExam::where([
            'fldencounterval' => $encounter_id,
            'fldsave' => '1',
        ])->get();

        return response()->json(
            $baby_data
        );
    }

    public function changedob(Request $request)
    {
        try{
            $date = $request->get('date');
            $date = \App\Utils\Helpers::dateNepToEng($date)->full_date . ' ' . date('H:i:s');

            \App\PatientInfo::where([
                'fldpatientval' => \App\Encounter::where('fldencounterval', $request->get('encid'))->first()->fldpatientval,
            ])->update([
                'fldptbirday' => $date,
            ]);
            $age = \Carbon\Carbon::parse($date)->diffInHours(\Carbon\Carbon::now()) . "Hrs";

            return response()->json([
                'status'=> TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
                'age' => $age,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update data.',
            ]);
        }
    }

    public function addExamination(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'encounter_id' => 'required',
            'examtype' => 'required',
            'qualitative' => 'required',
            'quantative' => 'required|numeric',
            'examinationid' => 'required',
        ], [
            'encounter_id.required' => 'The Category is required.',
            'examtype.required' => 'The Title is required.',
            'qualitative.required' => 'The Key is required.',
            'quantative.required' => 'The Detail is required.',
            'examinationid.required' => 'The Image is required.',
        ]);
        if($validator->fails()) {
            $errors = 'Error while saving information' . PHP_EOL;
            foreach ($validator->getMessageBag()->messages() as $key => $value)
                $errors .= $value[0] . PHP_EOL;

            return [
                'status' => FALSE,
                'message' => $errors,
            ];
        }

        try {
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = Helpers::getCompName();

            $fldinput = "Baby Examination";
            $encounter_id = $request->get('encounter_id');
            $examtype = $request->get('examtype');
            $qualitative = $request->get('qualitative');
            $quantative = $request->get('quantative', '0');
            $examinationid = $request->get('examinationid');
            $abnormal = 0;

            $fldid = \App\PatientExam::insertGetId([
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
                ],
                'message' => __('messages.success', ['name' => 'Examination']),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to save examinations.',
            ], 200);
        }
    }

    public function getModalContent(Request $request)
    {
        $type = $request->get('type');
        $examid = $request->get('examid');

        $ret_data = [];
        if ($type == 'No Selection') {
            $header = 'Enter Quantitative Value';
            $ret_data = [
                'view_data' => (string) view('delivery::layouts.newborn-modal', compact('examid', 'header', 'type')),
            ];
        } elseif ($type == 'Left and Right') {
            $header = 'Left and Right Examination Report';
            $ret_data = [
                'view_data' => (string) view('delivery::layouts.newborn-modal', compact('examid', 'header', 'type')),
            ];
        } elseif ($type == 'Single Selection') {
            $header = 'Single Selection Report';
            $options = \App\DepartmentExamOption::select('fldanswer')
                ->where('fldexamid', $examid)
                ->orderBy('fldindex')
                ->get();
            $ret_data = [
                'view_data' => (string) view('delivery::layouts.newborn-modal', compact('examid', 'header', 'type', 'options')),
                'options' => $options,
            ];
        } else {
            $ret_data = [
                'view_data' => (string) view('delivery::layouts.newborn-modal', compact('examid', 'type')),
            ];
        }

        return response()->json($ret_data);
    }

    public function examReport(Request $request)
    {
        $encounter_id = $request->get('fldencounterval');
        $patientinfo = \App\Utils\Helpers::getPatientByEncounterId($encounter_id);
        $exam_data = \App\PatientExam::where([
            'fldencounterval' => $encounter_id,
            'fldsave' => '1',
        ])->get();

        foreach ($exam_data as &$data) {
            $data->fldtime = $this->_format_datetime($data->fldtime);
        }

        return \Barryvdh\DomPDF\Facade::loadView('delivery::layouts.examReport', compact('patientinfo', 'exam_data'))
            ->stream('exam_report.pdf');
    }

    public function birthcertificate(Request $request)
    {
        $encounter_id = $request->get('fldencounterval');
        $childdata = \App\Confinement::select('flddeltime', 'flddelwt', 'fldencounterval', 'fldbabypatno')
            ->with('patientinfo:fldpatientval,fldptbirday,fldptsex')
            ->where('fldbabypatno', \App\Encounter::where('fldencounterval', $encounter_id)->first()->fldpatientval)
            ->first();
        $childdata->flddeltimenepali = \App\Utils\Helpers::dateEngToNepdash(explode(' ', $childdata->patientinfo->fldptbirday)[0])->full_date;
        $motherinfo = \App\Encounter::select('fldptnamefir', 'fldptnamelast', 'fldptguardian', 'fldptaddvill', 'fldptadddist')
            ->join('tblpatientinfo', 'tblpatientinfo.fldpatientval', '=', 'tblencounter.fldpatientval')
            ->where('fldencounterval', $childdata->fldencounterval)
            ->first();

        if(Options::get('birth_certificate_template')) {
            $certificate = strtr(Options::get('birth_certificate_template'), [
                '{$gender}' => $childdata->patientinfo->fldptsex,
                '{$wife}' => $motherinfo->fldptnamefir.' '.$motherinfo->fldptnamelast ,
                '{$guardian}' => $motherinfo->fldptguardian ?: '-',
                '{$address}' =>  $motherinfo->fldptaddvill.' '.$motherinfo->fldptadddist,
                '{$date}' => $childdata->flddeltimenepali,
            ]);
        }
//        dd($certificate);

        return \Barryvdh\DomPDF\Facade::loadView('delivery::layouts.birthcertificate', compact('motherinfo', 'childdata','certificate'))
            ->stream('birthcertificate.pdf');
    }

    private function _format_datetime($datetime, $returndatetime = FALSE)
    {
        $datetime = explode(' ', $datetime);
        $englishdate = $datetime[0];
        $nepalidate = \App\Utils\Helpers::dateEngToNep(str_replace('-', '/', $englishdate));
        $nepalidate = "{$nepalidate->year}-{$nepalidate->month}-{$nepalidate->date}";

        $time = substr($datetime[1], 0, -3);
        return "$nepalidate $time";
    }
}
