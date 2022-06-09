<?php

namespace Modules\Inpatient\Http\Controllers;

use App\ExamGeneral;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Session;
use Exception;

class NotesController extends Controller
{
    public function postInsertNotes(Request $request) {
        try {
           $data = array(
               'fldencounterval' => $request->fldencounterval,
               'fldinput'        => 'Notes',
               'fldtype'         => 'Qualitative',
               'flditem'         => $request->flditem,
               'fldreportquali'  => $request->fldreportquali,
               'fldreportquanti' => 0,
               'flddetail'       => $request->flddetail,
               'flduserid'       => $request->flduserid, //admin
               'fldtime'         => now(), //'2020-02-23 11:13:27.709'
               'fldcomp'         => $request->fldcomp, //comp01
               'fldsave'         => 1, //1
               'flduptime'       => now(), // null ????
               'xyz'             => 0,
               'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
           );
           $latest_id = ExamGeneral::insertGetId($data);
           if ($latest_id) {
               Session::flash('display_popup_error_success', true);
               Session::flash('success_message', 'Complaint update Successfully.');
               return response()->json([
                    'success' => [
                        'id'   => $latest_id
                    ]
               ]);
           }else {
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
           return redirect()->route('patient');
       }
    }

    public function postUpdateNotes(Request $request) {
        try {
            if(!empty($request->flduptime)){
              $updated_date = $request->flduptime;
            }else{
              $updated_date = now();
            }
            $data = array(
               'fldencounterval' => $request->fldencounterval,
               'fldinput'        => 'Notes',
               'fldtype'         => 'Qualitative',
               'flditem'         => $request->flditem,
               'fldreportquali'  => $request->fldreportquali,
               'fldreportquanti' => 0,
               'flddetail'       => $request->flddetail,
               'flduserid'       => $request->flduserid, //admin
               'fldtime'         => $request->fldtime, //'2020-02-23 11:13:27.709'
               'fldcomp'         => $request->fldcomp, //comp01
               'fldsave'         => 1, //1
               'flduptime'       => $updated_date, // null ????
               'xyz'             => 0,
            );
            $table = ExamGeneral::where([
                'fldid' => $request->fldid,
                'fldencounterval' => $request->fldencounterval,
                'fldinput' => 'Notes'
            ])->first();
            $latest_id = $table->update($data);
            if ($latest_id) {
               Session::flash('display_popup_error_success', true);
               Session::flash('success_message', 'Complaint update Successfully.');
               return response()->json([
                   'success' => [
                       'id'   => $latest_id
                   ]
               ]);
            }else {
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
           return redirect()->route('patient');
       }
    }

    public function listOnSelect() {
        $fldid = Input::get('value');
        // dd($related_history);
        $get_list_detail = ExamGeneral::where([
            ['fldid', $fldid],
            ['fldencounterval', Input::get('fldencounterval')],
            ['fldinput', 'Notes'],
            ['fldsave', 1],
        ])->select('flddetail', 'fldreportquali', 'fldtime', 'fldid')->get();
        return response()->json($get_list_detail);
    }

    public function listOnDate() {
        $related_list = Input::get('list_date');
        $from = $related_list . ' 00:00:00';
        $to = $related_list . ' 23:59:59.99';
        $get_list_detail = ExamGeneral::whereBetween('flduptime', [$from, $to])->where([
            ['fldencounterval', Input::get('fldencounterval')],
            ['fldinput', 'Notes'],
            ['fldsave', 1]
        ])->select('flditem', 'flduptime', 'fldid')->get();
        return response()->json($get_list_detail);
    }

    public function listAll() {
        $get_list_detail = ExamGeneral::where([
            ['fldencounterval', Input::get('fldencounterval')],
            ['fldinput', 'Notes'],
            ['fldsave', 1]
        ])->select('flditem', 'flduptime', 'fldid');

        if (Input::get('date') == 'today') {
            $date = date('Y-m-d');
            $get_list_detail->whereBetween('flduptime', ["$date 00:00:00", "$date 23:59:59.999"]);
        }

        return response()->json($get_list_detail->get());
    }

    public function postReferePatient(Request $request) {
        return response()->json("success");
    }
}
