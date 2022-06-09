<?php

namespace Modules\Inpatient\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\PatientExam;
use App\ExamGeneral;
use App\Utils\Helpers;
use Exception;
use Session;

class GeneralController extends Controller
{
    public function getSymptomsList()
    {
        return response()->json(
            \App\Symptoms::select('fldsymptom')->get()
        );
    }

    public function getPatientSymptoms(Request $request)
    {
        $encounter_id = Session::get('inpatient_encounter_id');
        $data = ExamGeneral::select('fldid', 'fldtime', 'fldtype', 'flditem', 'fldreportquali', 'fldreportquanti', 'flddetail', 'flduserid')
                ->where([
                    'fldencounterval' => $encounter_id,
                    'fldinput' => 'Patient Symptoms',
                    'fldsave' => '1',
                ])->get();
        foreach ($data as &$d) {
            $d->fldtime = explode(' ', $d->fldtime)[0];
            $d->fldreportquali = ($d->fldreportquali) ?: '';
        }
        return response()->json($data);
    }

    public function saveSymptoms(Request $request)
    {

        try {
            $encounter_id = Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();

            $symptoms = $request->get('symptoms');
            $data = [
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Patient Symptoms',
                'fldtype' => 'Qualitative',
                'fldreportquali' => NULL,
                'fldreportquanti' => '0',
                'flddetail' => NULL,
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'flduptime' => NULL,
                'xyz' => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            $ret_symptoms = [];
            foreach ($symptoms as $sy) {
                $ret_id = ExamGeneral::insertGetId($data + [
                    'flditem' => $sy,
                ]);
                $ret_symptoms[] = [
                    'fldid' => $ret_id,
                    'flditem' => $sy,
                ];
            }

            return response()->json([
                'status'=> TRUE,
                'data' => [
                    'date' => date('Y-m-d'),
                    'symptoms' => $ret_symptoms,
                ],
                'message' => 'Successfully saved information.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to save information.',
            ]);
        }
        // in loop save multiple
        // INSERT INTO `tblexamgeneral` ( `fldencounterval`, `fldinput`, `fldtype`, `flditem`, `fldreportquali`, `fldreportquanti`, `flddetail`, `flduserid`, `fldtime`, `fldcomp`, `fldsave`, `flduptime`, `xyz` ) VALUES ( '1', 'Patient Symptoms', 'Qualitative', 'Abnormal movements highly suggestive of pseudoseizure', NULL, 0, NULL, 'admin', '2020-04-02 20:09:13.162', 'comp01', '1', NULL, '0' )
    }

    public function updateSymptoms(Request $request)
    {
        try {
            ExamGeneral::where([['fldid', $request->get('fldid')]])
                ->update([
                    'flddetail' => $request->get('flddetail'),
                ]);

            return response()->json([
                'status'=> TRUE,
                'message' => __('messages.update', ['name' => 'Data']),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to update information.',
            ]);
        }
    }

    public function changeSymptomStatus(Request $request)
    {
        try {
            ExamGeneral::where('fldid', $request->get('fldid'))
                ->update([
                    'fldreportquali' => $request->get('fldreportquali'),
                ]);
            return response()->json([
                'status' => TRUE,
                'message' => __('messages.update', ['name' => 'Status'])
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => False,
                'message' => 'Failed to update status.'
            ]);
        }
        // UPDATE `tblexamgeneral` SET `fldreportquali` = 'Mild', `xyz` = '0' WHERE `fldid` = 964
    }

    public function deleteSymptoms(Request $request)
    {
        try {
            ExamGeneral::where([
                    'fldid' => $request->get('fldid'),
                ])
                ->delete();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully deleted.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => False,
                'message' => 'Failed to delete.'
            ]);
        }
        // DELETE FROM `tblexamgeneral` WHERE fldid='959'
    }

    public function getStatus(Request $request)
    {
        $encounter_id = Session::get('inpatient_encounter_id');
        return response()->json(
            PatientExam::select('fldrepquali')
                ->where([
                    'fldencounterval' => $encounter_id,
                    'fldhead' => $request->get('fldhead'),
                    'fldtype' => 'Qualitative',
                    'fldsave' => '1',
                ])->latest('fldtime')
                ->first()
        );
        // select fldrepquali from tblpatientexam where fldencounterval='1' and fldhead='Hepatic Status' and fldtype='Qualitative' and fldsave='1'
    }

    public function saveStatus(Request $request)
    {
        try {
            $encounter_id = Session::get('inpatient_encounter_id');
            $time = date('Y-m-d H:i:s');
            $userid = \Auth::guard('admin_frontend')->user()->flduserid;
            $computer = \App\Utils\Helpers::getCompName();

            $fldid = PatientExam::insertGetId([
                'fldencounterval' => $encounter_id,
                'fldserialval' => NULL,
                'fldinput' => 'General Parameters',
                'fldtype' => 'Qualitative',
                'fldhead' => $request->get('fldhead'),
                'fldsysconst' => NULL,
                'fldmethod' => 'Regular',
                'fldrepquali' => $request->get('status'),
                'fldrepquanti' => '0',
                'fldfilepath' => NULL,
                'flduserid' => $userid,
                'fldtime' => $time,
                'fldcomp' => $computer,
                'fldsave' => '1',
                'fldabnormal' => '0',
                'flduptime' => NULL,
                'xyz' => '0',
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            return response()->json([
                'status'=> TRUE,
                'message' => 'Successfully saved status.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'=> FALSE,
                'message' => 'Failed to save status.',
            ]);
        }
        // INSERT INTO `tblpatientexam` ( `fldencounterval`, `fldserialval`, `fldinput`, `fldtype`, `fldhead`, `fldsysconst`, `fldmethod`, `fldrepquali`, `fldrepquanti`, `fldfilepath`, `flduserid`, `fldtime`, `fldcomp`, `fldsave`, `fldabnormal`, `flduptime`, `xyz` ) VALUES ( '1', NULL, 'General Parameters', 'Qualitative', 'Hepatic Status', NULL, 'Regular', 'Normal', 0, NULL, 'admin', '2020-04-02 20:11:39.258', 'comp01', '1', '0', NULL, '0' )
    }

    public function resolveSymptom(Request $request)
    {
        try {
            $fldid = $request->get('fldid');
            $report = ExamGeneral::where('fldid', $fldid)->first();

            $time1 = strtotime($report->fldtime);
            $time2 = strtotime(date('Y-m-d H:i:s'));
            $difference = round(abs($time2 - $time1) / 3600,2);

            ExamGeneral::where('fldid', $request->get('fldid'))
                ->update([
                    'fldreportquanti' => $difference,
                ]);
            return response()->json([
                'status' => TRUE,
                'hours' => $difference,
                'message' => 'Successfully resolved symptom.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => False,
                'message' => 'Failed to resolve symptom.'
            ]);
        }
        // UPDATE `tblexamgeneral` SET `fldreportquanti` = 0, `xyz` = '0' WHERE `fldid` = 965
    }

}
