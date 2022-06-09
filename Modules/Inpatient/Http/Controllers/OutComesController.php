<?php

namespace Modules\Inpatient\Http\Controllers;

use App\Departmentbed;
use App\Encounter;
use App\PatientDate;
use App\PatTiming;
use App\Utils\Helpers;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Session;
use App\Utils\Options;

class OutComesController extends Controller
{
    public function insertDischargePatient(Request $request)
    {
        try {
            /*new changes*/
            $fldencounterval = $request->fldencounterval;
            $currentLoc = Encounter::select('fldcurrlocat')
                ->where('fldencounterval', $fldencounterval)
                ->first();

            $pattiming = PatTiming::where('fldencounterval', $fldencounterval)
                ->where('fldtype', 'General Services')
                ->where('fldfirstreport', 'Bed')
                ->where('fldfirstsave', 1)
                ->where('fldsecondsave', 0)
                ->get();

            if (count($pattiming)) {
                $patData['fldsecondreport'] = $currentLoc->fldcurrlocat;
                $patData['fldseconduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                $patData['fldsecondtime'] = date("Y-m-d H:i:s");
                $patData['fldsecondcomp'] = Helpers::getCompName();
                $patData['fldsecondsave'] = 1;
                $patData['xyz'] = 0;

                PatTiming::where([
                    'fldtype' => 'General Services',
                    'fldfirstreport' => 'Bed',
                    'fldfirstsave' => 1,
                    'fldsecondsave' => 0,
                ])->update($patData);
                // \App\Departmentbed::where('fldencounterval', $fldencounterval)->update([
                //     'fldencounterval' => NULL,
                // ]);
                // \App\Encounter::where('fldencounterval', $fldencounterval)->update([
                //     'flddod' => date('Y-m-d H:i:s'),
                // ]);
            }

            Departmentbed::where('fldencounterval', $fldencounterval)->update(['fldencounterval' => NULL]);
            $encounterData['flddod'] = date("Y-m-d H:i:s");
            $encounterData['fldadmission'] = 'Discharged';
            $encounterData['xyz'] = 0;
            $encounterData['fldcurrlocat'] = null;

            Encounter::where('fldencounterval', $fldencounterval)->update($encounterData);
            /*new changes*/


            $data = array(
                'fldencounterval' => $fldencounterval,
                'fldhead' => 'Discharged',
                'fldcomment' => $request->fldhead,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid, //admin
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'fldcomp' => Helpers::getCompName(), //comp01
                'fldsave' => 1,
                'flduptime' => Null,
                'xyz' => 0,
            );
            $latest_id = PatientDate::insertGetId($data);


            if (Options::get('low_deposit_text_message')) {
                $encounter = \App\Encounter::where('fldencounterval', $fldencounterval)
                    ->with(['patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldrank'])
                    ->first();
                $text = strtr(Options::get('low_deposit_text_message'), [
                    '{$name}' => $encounter->patientInfo->fldfullname,
                    '{$systemname}' => isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '',
                ]);
                (new \Modules\AdminEmailTemplate\Http\Controllers\AdminSmsTemplateController())->sendSms([
                    'text' => $text,
                    'to' => $encounter->patientInfo->fldptcontact,
                ]);
            }


            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Complaint update Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            dd($e);
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('patient');
        }
    }

    public function insertLamaPatient(Request $request)
    {
        try {
            /*new changes*/
            $currentLoc = Encounter::select('fldcurrlocat')
                ->where('fldencounterval', $request->fldencounterval)
                ->first();

            $pattiming = PatTiming::where('fldencounterval', $request->fldencounterval)
                ->where('fldtype', 'General Services')
                ->where('fldfirstreport', 'Bed')
                ->where('fldfirstsave', 1)
                ->where('fldsecondsave', 0)
                ->get();

            if (count($pattiming)) {
                $patData['fldsecondreport'] = $currentLoc->fldcurrlocat;
                $patData['fldseconduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                $patData['fldsecondtime'] = date("Y-m-d H:i:s");
                $patData['fldsecondcomp'] = Helpers::getCompName();
//                $patData['fldsecondcomp'] = Helpers::getCompName();
                $patData['fldsecondsave'] = 1;
                $patData['xyz'] = 0;

                PatTiming::where([
                    'fldtype' => 'General Services',
                    'fldfirstreport' => 'Bed',
                    'fldfirstsave' => 1,
                    'fldsecondsave' => 0,
                ])->update($patData);

            }
            Departmentbed::where('fldbed', 'LIKE', $currentLoc->fldcurrlocat)->update(['fldencounterval' => NULL]);

            $encounterData['flddod'] = date("Y-m-d H:i:s");
            $encounterData['fldadmission'] = 'LAMA';
            $encounterData['xyz'] = 0;
            $encounterData['fldcurrlocat'] = null;

            Encounter::where('fldencounterval', $request->fldencounterval)->update($encounterData);
            /*new changes*/


            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldhead' => 'LAMA',
                'fldcomment' => $request->fldhead,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid, //admin
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'fldcomp' => Helpers::getCompName(), //comp01
                'fldsave' => 1,
                'flduptime' => Null,
                'xyz' => 0,
            );
            $latest_id = PatientDate::insertGetId($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Complaint update Successfully.');
                return response()->json([
                    'success' => [
                        'type' => 'LAMA',
                        'id' => $latest_id,
                    ]
                ]);
            } else {
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

    public function insertDeathPatient(Request $request)
    {
        try {
            /*new changes*/
            $currentLoc = Encounter::select('fldcurrlocat')
                ->where('fldencounterval', $request->fldencounterval)
                ->first();

            $pattiming = PatTiming::where('fldencounterval', $request->fldencounterval)
                ->where('fldtype', 'General Services')
                ->where('fldfirstreport', 'Bed')
                ->where('fldfirstsave', 1)
                ->where('fldsecondsave', 0)
                ->get();

            if (count($pattiming)) {
                $patData['fldsecondreport'] = $currentLoc->fldcurrlocat;
                $patData['fldseconduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                $patData['fldsecondtime'] = date("Y-m-d H:i:s");
                $patData['fldsecondcomp'] = Helpers::getCompName();
                $patData['fldsecondsave'] = 1;
                $patData['xyz'] = 0;

                PatTiming::where([
                    'fldtype' => 'General Services',
                    'fldfirstreport' => 'Bed',
                    'fldfirstsave' => 1,
                    'fldsecondsave' => 0,
                ])->update($patData);
            }
            Departmentbed::where('fldbed', 'LIKE', $currentLoc->fldcurrlocat)->update(['fldencounterval' => NULL]);

            $encounterData['flddod'] = date("Y-m-d H:i:s");
            $encounterData['fldadmission'] = 'Death';
            $encounterData['xyz'] = 0;
            $encounterData['fldcurrlocat'] = null;

            Encounter::where('fldencounterval', $request->fldencounterval)->update($encounterData);
            /*new changes*/

            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldhead' => 'Death',
                'fldcomment' => $request->fldhead,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid, //admin
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'fldcomp' => Helpers::getCompName(), //comp01
                'fldsave' => 1,
                'flduptime' => Null,
                'xyz' => 0,
            );
            $latest_id = PatientDate::insertGetId($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Complaint update Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id,
                        'type' => 'Death',
                    ]
                ]);
            } else {
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

    public function insertReferePatient(Request $request)
    {
        try {

            /*new changes*/
            $currentLoc = Encounter::select('fldcurrlocat')
                ->where('fldencounterval', $request->fldencounterval)
                ->first();

            $pattiming = PatTiming::where('fldencounterval', $request->fldencounterval)
                ->where('fldtype', 'General Services')
                ->where('fldfirstreport', 'Bed')
                ->where('fldfirstsave', 1)
                ->where('fldsecondsave', 0)
                ->get();

            if (count($pattiming)) {
                $patData['fldsecondreport'] = $currentLoc->fldcurrlocat;
                $patData['fldseconduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                $patData['fldsecondtime'] = date("Y-m-d H:i:s");
                $patData['fldsecondcomp'] = Helpers::getCompName();
                $patData['fldsecondsave'] = 1;
                $patData['xyz'] = 0;

                PatTiming::where([
                    'fldtype' => 'General Services',
                    'fldfirstreport' => 'Bed',
                    'fldfirstsave' => 1,
                    'fldsecondsave' => 0,
                ])->update($patData);


            }
            //Departmentbed::where('fldbed', 'LIKE', $currentLoc->fldcurrlocat)->update(['fldencounterval' => NULL]);
            $bedsoccu = Departmentbed::where('fldencounterval', $request->fldencounterval)->get();
            if($bedsoccu){
                foreach($bedsoccu as $b){
                     Departmentbed::where('fldbed', $b->fldbed)->update(['fldencounterval' => NULL]);
                }
            }
            $encounterData['flddod'] = date("Y-m-d H:i:s");
            $encounterData['fldadmission'] = 'Refer';
            $encounterData['xyz'] = 0;
            $encounterData['fldcurrlocat'] = null;

            Encounter::where('fldencounterval', $request->fldencounterval)->update($encounterData);
            /*new changes*/


            // return "get data";
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'fldhead' => 'Refer',
                'fldcomment' => $request->fldcomment,
                'flduserid' => $request->flduserid, //admin
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'fldcomp' => $request->fldcomp, //comp01
                'fldsave' => 1,
                'flduptime' => Null,
                'xyz' => 0,
            );
            $latest_id = PatientDate::insertGetId($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Complaint update Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id
                    ]
                ]);
            } else {
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
}
