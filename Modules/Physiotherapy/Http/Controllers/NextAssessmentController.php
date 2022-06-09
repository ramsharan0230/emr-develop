<?php

namespace Modules\Physiotherapy\Http\Controllers;

use App\Consult;
use App\Encounter;
use App\ExamGeneral;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Session;

class NextAssessmentController extends Controller
{
    public function insertNextAssessment(Request $request) {
        try {

            $encounterId = $request->fldencounterval;
            $encounterdata = Encounter::where('fldencounterval',$encounterId)->first();
            $hospital_deparment_id = Helpers::getUserSelectedHospitalDepartmentIdSession();
            $data = array(
                'fldencounterval' =>  $encounterId,
                'fldinput'        => 'Next Assessment',
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
                'hospital_department_id' => $hospital_deparment_id
            );
            $latest_id = ExamGeneral::insertGetId($data);


            $datetime = $request->date;

            $edata['fldfollowdate'] = $datetime;
            $edata['xyz'] = 0;
            Encounter::where([['fldencounterval',$encounterId]])->update($edata);

            $cdata['fldencounterval'] = $encounterId;
            $cdata['fldconsultname'] = $encounterdata->fldcurrlocat;
            $cdata['fldconsulttime'] = $datetime;
            $cdata['fldcomment'] = '';
            $cdata['fldstatus'] = 'Planned';
            $cdata['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
            $cdata['fldbillingmode'] = $encounterdata->fldbillingmode;
            $cdata['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
            $cdata['fldtime'] = date('Y-m-d H:i:s');
            $cdata['fldcomp'] = Helpers::getCompName()??'';
            $cdata['fldsave'] = 1;
            $cdata['xyz'] = 0;
            $cdata['fldcategory'] = 'Physiotherapy';
            $cdata['hospital_department_id'] = $hospital_deparment_id;
            $cdata['is_refer'] = 0;
            Consult::insert($cdata);

            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Next Assessment update Successfully.');
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
//            dd($e);
            return response()->json([
                'error' => [
                    'message' => 'exception error'
                ]
            ]);
        }
    }
}
