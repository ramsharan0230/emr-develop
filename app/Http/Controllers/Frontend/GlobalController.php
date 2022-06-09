<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GlobalController extends Controller
{
    public function getExamObservationModal(Request $request)
    {
    	$fldid = $request->get('fldid');
    	$examtable = $request->get('examtable') ?: 'tblexam';
    	$examoptiontable = ($examtable == 'tblexam') ? 'tblexamoption' : 'tbldeptexamoption';
    	$answertype = ($examtable == 'tbldeptexam') ? 'fldtanswertype AS fldoption' : 'fldoption';

    	$patient_exam = \App\PatientExam::where('fldid', $fldid)->first();
    	$exam = \DB::table($examtable)->select('fldexamid', 'fldtype', $answertype)->where('fldexamid', $patient_exam->fldhead)->first();

    	$type = ($exam) ? $exam->fldoption : '';
    	$examid =  ($exam) ? $exam->fldexamid : '';

    	$header = '';
    	$ret_data = [];
    	$options = [];
        if ($type == 'Clinical Scale') {
            $header = 'Clinical Scale';
            $questions = \DB::table($examoptiontable)
                ->select('fldanswertype', 'fldanswer', 'fldscale', 'fldscalegroup')
                ->where('fldexamid', $examid)
                ->get();

            $options = [];
            foreach ($questions as $que) {
                $options[$que->fldscalegroup]['options'][$que->fldanswer] = $que->fldscale;
            }
        } elseif ($type == 'Single Selection') {
            $header = 'Single Selection Report';
            $options = \DB::table($examoptiontable)
                ->select('fldanswer')
                ->where('fldexamid', $examid)
                ->orderBy('fldindex')
                ->get();
        } elseif ($type == 'No Selection')
            $header = 'Enter Quantitative Value';
        elseif ($type == 'Left and Right')
            $header = 'Left and Right Examination Report';
        else
            $header = 'Single Selection Report';

		return response()->json(
			(string) view('frontend.common.examObservation', compact('examid', 'header', 'type', 'options', 'patient_exam', 'fldid'))
		);
    }

    public function updateExamObservation(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'fldid' => 'required',
        ], [
            'fldid.required' => 'The Problem is required.',
        ]);
        if($validator->fails()) {
            $errors = 'Error while updating information' . PHP_EOL;
            foreach ($validator->getMessageBag()->messages() as $key => $value)
                $errors .= $value[0] . PHP_EOL;

            return [
                'status' => FALSE,
                'message' => $errors,
            ];
        }

    	try {
	    	\App\PatientExam::where('fldid', $request->get('fldid'))->update([
	    		'fldrepquali' => $request->get('qualitative'),
	    		'fldrepquanti' => $request->get('quantative'),
	    	]);

	    	return response()->json([
	    		'status' => TRUE,
	    		'message' => __('messages.update', ['name' => 'Data']),
	    	]);
    	} catch (Exception $e) {}

    	return response()->json([
    		'status' => FALSE,
    		'message' => 'Faild to update data.',
    	]);
    }
}
