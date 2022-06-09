<?php

namespace Modules\Inpatient\Http\Controllers;

use App\ExamGeneral;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Session;
use Exception;

class PresentController extends Controller
{
    // Cause of Admission
    public function postCauseDetail(Request $request) {
        // dd($request->fldid);
         try{
            $fldid = $request->fldid;
            if($fldid == Null){
                $data = array(
                    'fldencounterval' => $request->fldencounterval,
                    'flddetail' => $request->flddetail,
                    'fldinput' => 'History',
                    'fldtype' => 'Qualitative',
                    'flditem' => 'Cause of Admission',
                    'fldreportquali' => NULL,
                    'fldreportquanti' => 0,
                    'flduserid' => $request->flduserid,
                    'fldtime' => now(),
                    'fldcomp' => $request->fldcomp,
                    'fldsave' => 1,
                    'flduptime' => now(),
                    'xyz' => 0,
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                );
             }else{
                $data = array(
                    'flddetail' => $request->flddetail,
                    'fldreportquali' => NULL,
                    'fldreportquanti' => 0,
                    'flduserid' => $request->flduserid,
                    'fldcomp' => $request->fldcomp,
                    'fldsave' => 1,
                    'flduptime' => now(),
                    'xyz' => 0,
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                );
            }
            $doOrCreate = ExamGeneral::updateOrCreate(
                                [
                                    'fldid' => $request->fldid
                                ],$data);
            if($doOrCreate){
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Successfully Saved.',
                ]);
            }else{
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed To Save.',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function getCauseDetail()
    {
        $fldencounterval = Input::get('fldencounterval');
        $get_cause_of_admission = ExamGeneral::where([
            'fldencounterval' => $fldencounterval,
            'fldinput' => 'History',
            'fldtype' => 'Qualitative',
            'flditem' => 'Cause of Admission',
            'fldsave' => 1
        ])->first();
        return response()->json($get_cause_of_admission);
    }

    public function get_related_history() {
        $related_history = Input::get('patentHistory');
        $fldencounterval = Input::get('fldencounterval');
        // dd($related_history);
        $get_history_detail = ExamGeneral::where([
            ['fldencounterval', $fldencounterval],
            ['fldinput', 'History'],
            ['fldsave', 1],
            ['flditem', $related_history]
        ])->select('flddetail', 'flditem', 'fldid')->first();
        return response()->json($get_history_detail);
    }

    function deleteComplaint($fldid) {
        $data = array(
            'fldsave' => 0,
        );
        ExamGeneral::where('fldid', $fldid)->update($data);
        return response()->json([
            'success' => 'true'
        ]);
    }

    function insertComplaintDetail(Request $request)
    {
        try {
            $fldid = $request->fldid;
            $data  = array(
                'flddetail' => $request->flddetail,
                'fldtime'   => now(),
                'flduptime' => now()
            );
            $checkifexit = ExamGeneral::where('fldid', $fldid)->first();
            if($checkifexit == null){
                return redirect()->route('inpatient')->with('error_message', 'Failed To Update Complaint.');
            }
            ExamGeneral::where([['fldid', $fldid],])->update($data);
            Session::flash('display_popup_error_success', true);
            return redirect()->route('inpatient')->with('success_message', 'Complaint update Successfully.');
        } catch (\Exception $e) {
            Session::flash('display_popup_error_success', true);
            return redirect()->route('inpatient')->with('error_message', 'Sorry! something went wrong');
        }

    }

    public function insertComplaintDuration(Request $request)
    {
       try{
            $fldid = $request->fldid;
            $checkifexist = ExamGeneral::where('fldid', $fldid)->first();
            if($checkifexist == null){
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Match Did Not Found.',
                ]);
            }

            $hours = $request->value;
            if ($request->type == 'Days')
                $hours = $request->value * 24;
            if ($request->type == 'Weeks')
                $hours = $request->value * 7 * 24;
            if ($request->type == 'Months')
                $hours = $request->value * 30 * 24;
            if ($request->type == 'Years')
                $hours = $request->value * 365 * 24;

            $update = ExamGeneral::where('fldid', $fldid)->update([
                'fldreportquanti' => $hours
            ]);

            if($update){
                return response()->json([
                    'status' => TRUE,
                    'message' => __('messages.update', ['name' => 'Duration']),
                ]);
            }else{
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed To Update Duration.',
                ]);
            }
       } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
       }
    }

    public function insertComplaintSide(Request $request)
    {
        try{
            $fldid = $request->fldid;
            $checkifexist = ExamGeneral::where('fldid', $fldid)->first();
            if($checkifexist == null){
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Match Did Not Found.',
                ]);
            }

            $update = ExamGeneral::where('fldid', $fldid)->update([
                'fldreportquali' => $request->side
            ]);

            if($update){
                return response()->json([
                    'status' => TRUE,
                    'message' => __('messages.update', ['name' => 'Side']),
                ]);
            }else{
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Failed To Update Side.',
                ]);
            }
       } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
       }
    }

    function insertComplain(Request $request) {
        try {
            $hours = $request->duration;
            if ($request->duration_type == 'Days')
                $hours = $request->duration * 24;
            if ($request->duration_type == 'Weeks')
                $hours = $request->duration * 7 * 24;
            if ($request->duration_type == 'Months')
                $hours = $request->duration * 30 * 24;
            if ($request->duration_type == 'Years')
                $hours = $request->duration * 365 * 24;

            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldinput'        => 'Presenting Symptoms',
                'fldtype'         => 'Qualitative',
                'flditem'         => $request->flditem,
                'fldreportquali'  => $request->fldreportquali,
                'fldreportquanti' => $hours,
                'flddetail'       => '',
                'flduserid'       => $request->flduserid,
                'fldtime'         => now(),
                'fldcomp'         => $request->fldcomp,
                'fldsave'         => 1,
                'flduptime'       => NULL,
                'xyz'             => 0,
            );

            $latest_id = ExamGeneral::insertGetId($data);
            if($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Complaint update Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id
                    ]
                ]);
            }else{
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('inpatient');
        }
    }

    public function insertPresentHistory(Request $request) {
        try {
            if($request->fldid == Null){
                $data = array(
                    'fldencounterval' => $request->fldencounterval,
                    'flddetail' => $request->flddetail,
                    'fldinput' => 'History',
                    'fldtype' => 'Qualitative',
                    'flditem' => $request->flditem,
                    'fldreportquali' => NULL,
                    'fldreportquanti' => 0,
                    'flduserid' => $request->flduserid,
                    'fldtime' => now(),
                    'fldcomp' => $request->fldcomp,
                    'fldsave' => 1,
                    'flduptime' => now(),
                    'xyz' => 0,
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                );
            }else{
                $data = array(
                    'flddetail' => $request->flddetail,
                    'fldreportquali' => NULL,
                    'fldreportquanti' => 0,
                    'flduserid' => $request->fldid,
                    'fldcomp' => $request->fldcomp,
                    'fldsave' => 1,
                    'flduptime' => now(),
                    'xyz' => 0,
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                );
            }

            $latest_id = ExamGeneral::updateOrCreate(
                                        [
                                            'fldid' => $request->fldid,
                                            'flditem' =>$request->flditem
                                        ], $data);
            if($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Complaint update Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id->fldid
                    ]
                ]);
            }else{
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }

        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('inpatient');
        }
    }
}
