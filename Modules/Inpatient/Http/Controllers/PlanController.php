<?php

namespace Modules\Inpatient\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\PatPlanning;
use App\Utils\Helpers;
use Exception;
use Session;

class PlanController extends Controller
{
    public function getPlans(Request $request)
    {
        $date = $request->get('date');
        if (!$date)
            $date = date('Y-m-d');

        $encounter_id = Session::get('inpatient_encounter_id');
        $plans = PatPlanning::select('fldid', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
            ->where([
                ["fldencounterval", $encounter_id],
                ["fldplancategory", "Clinician Plan"],
                ["fldsave", "1"],
                ["fldtime", ">=", "$date 00:00:00"],
                ["fldtime", "<=", "$date 23:59:59.999"],
            ]);

        return response()->json(
            $plans->get()
        );        
    }

    public function saveUpdatePlan(Request $request)
    {
        $fldid = $request->get('fldid');
        $data = [
            'fldproblem' => $request->get('fldproblem'),
            'fldsubjective' => $request->get('fldsubjective'),
            'fldobjective' => $request->get('fldobjective'),
            'fldassess' => $request->get('fldassess'),
            'fldplan' => $request->get('fldplan'),
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'fldproblem' => 'required',
        ], [
            'fldproblem.required' => 'The Problem is required.',
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
            if ($fldid) {
                PatPlanning::where([
                    'fldid' => $fldid,
                ])->update($data);
            } else {
                $encounter_id = Session::get('inpatient_encounter_id');
                $time = date('Y-m-d H:i:s');
                $userid = \Auth::guard('admin_frontend')->user()->flduserid;
                $computer = \App\Utils\Helpers::getCompName();

                $fldid = PatPlanning::insertGetId([
                    'fldencounterval' => $encounter_id,
                    'fldplancategory' => 'Clinician Plan',
                    'flduserid' => $userid,
                    'fldtime' => $time,
                    'fldcomp' => $computer,
                    'fldsave' => '1',
                    'flduptime' => NULL,
                    'xyz' => '0',
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ] + $data);
            }
            return response()->json([
                'status'=> TRUE,
                'data' => $data + ['fldid' => $fldid],
                'message' => 'Successfully saved Information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to save Information.',
            ]);            
        }
    }

}
