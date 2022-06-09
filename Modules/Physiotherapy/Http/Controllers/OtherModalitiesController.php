<?php

namespace Modules\Physiotherapy\Http\Controllers;

use App\ExamGeneral;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;
use Exception;

class OtherModalitiesController extends Controller
{

    public function insertOtherModalities(Request $request) {
        try {
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldinput'        => 'Other Modalities',
                'fldtype'         => 'Qualitative',
                'flditem'         => $request->flditem,
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
                Session::flash('success_message', 'Other Modalities update Successfully.');
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
                        'message' => 'Something went wrong.'
                    ]
                ]);
            }
        } catch (\Exception $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');

            return response()->json([
                'error' => [
                    'message' => 'exception error'
                ]
            ]);
        }
    }


}
