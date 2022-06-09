<?php

namespace Modules\Outpatient\Http\Controllers;

use App\Encounter;
use App\ExamGeneral;
use App\Utils\Helpers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EegController extends Controller
{
    public function saveEeg(Request $request)
    {
        try {
            $insertData = [
                'fldencounterval' => $request->encounter,
                'flddetail' => $request->eegData,
                'flditem' => 'EEG',
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => 1,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid ?? 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $insertData = ExamGeneral::create($insertData);
            return response()->json([
                'success' => true,
                'insertDataId' => $insertData->fldid
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'insertData' => []
            ]);
        }

    }

    public function print($encounter, $fldid)
    {
        $data['encounterData'] = Encounter::where('fldencounterval', $encounter)->with('patientInfo')->first();
        $data['examData'] = ExamGeneral::where('fldid', $fldid)->where('fldencounterval', $encounter)->first();

        return view("outpatient::pdf.eeg", $data);
    }
}
