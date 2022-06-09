<?php

namespace App\Http\Controllers;

use App\GlobalPatientSearch;
use App\PatientInfo;
use App\Utils\Options;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use Session;

/**
 * Class OutpatientController
 * @package Modules\Outpatient\Http\Controllers
 */
class PatientController extends Controller
{

    public function index()
    {
        $data = [];
        return view('outpatient::queue.index', $data);
    }

    public function consults(Request $request)
    {


        $data = array();
        $v = '';
        $data['sound'] = 'off';
        $status = 'Planned';
        $limit = config('constants.max_total_records');
        $from_date = Carbon::parse(config('constants.current_date'))->setTime(0, 0, 0);
        $to_date = Carbon::parse(config('constants.current_date'))->setTime(23, 59, 59);

        $all_consultants = DB::table('tblconsult')
            ->join('tblencounter', 'tblencounter.fldencounterval', '=', 'tblconsult.fldencounterval')
            ->join('tblpatientinfo', 'tblpatientinfo.fldpatientval', '=', 'tblencounter.fldpatientval')
            ->select('tblconsult.*', 'tblconsult.fldid as confldid', 'tblpatientinfo.*', 'tblencounter.*', 'tblconsult.flduserid as consultant')
            ->where('tblconsult.fldstatus', $status)
            // ->where('tblconsult.fldconsulttime', '>=', $from_date)
            // ->where('tblconsult.fldconsulttime', '<=', $to_date)
            ->orderBy('tblconsult.fldconsulttime', 'asc')
            ->limit($limit);


        $consult_name = [];
        if ($request->has('all_departments')) {
            $consult_name = $request->get('all_departments');

            $all_consultants->whereIn('tblconsult.fldconsultname', $consult_name);
        }


        $data['lists'] = $all_consultants->get();


        if ($data['lists']) {
            $set_limit = array();
            foreach ($data['lists'] as $k => $consult) {

                $set_limit[$k] = '';// $consult->confldid;
            }

            $v = session('lastest_fldencounterval');


            if (!empty($set_limit) && !empty($v)) {
                if ($set_limit != $v)
                    $data['sound'] = 'on';
                else
                    $data['sound'] = 'off';
            }
            //echo($data['sound']);
            Session::put('lastest_fldencounterval', $set_limit);
        }

        $data['consult_name'] = $consult_name;
        $data['departments'] = DB::table('tbldepartment')->get();

        return view('outpatient::queue.index', $data);
    }

    public function pharmacy(Request $request)
    {

        $data = array();
        $v = '';
        $data['sound'] = 'off';
        $status = 'Continue';
        $limit = config('constants.max_total_records');
        $from_date = Carbon::parse(config('constants.current_date'))->setTime(0, 0, 0);
        $to_date = Carbon::parse(config('constants.current_date'))->setTime(23, 59, 59);

        $all_consultants = DB::table('tblpatdosing')
            ->join('tblencounter', 'tblencounter.fldencounterval', '=', 'tblpatdosing.fldencounterval')
            ->join('tblpatientinfo', 'tblpatientinfo.fldpatientval', '=', 'tblencounter.fldpatientval')
            ->select('tblpatdosing.*', 'tblpatientinfo.*', 'tblencounter.*', 'tblpatdosing.flduserid as consultant')
            // ->where('tblpatdosing.fldsave_order', False)
            // ->where('tblpatdosing.fldsave', True)
            // ->where('tblpatdosing.fldorder', 'Request')
            // ->where('tblpatdosing.fldcurval', $status)
            // ->where('fldtime_order', '>=', $from_date)
            // ->where('fldtime_order', '<=', $to_date)
            ->groupBy('tblpatdosing.fldencounterval')
            ->limit($limit);


        $fldcomp_order = [];
        if ($request->has('all_departments')) {
            $fldcomp_order = $request->get('all_departments');

            $all_consultants->whereIn('tblpatdosing.fldcomp_order', $fldcomp_order);
        }


        $data['lists'] = $all_consultants->get();
        if ($data['lists']) {
            $set_limit = array();
            foreach ($data['lists'] as $k => $consult) {

                $set_limit[$k] = '';//$consult->confldid;
            }

            $v = session('lastest_fldencounterval');


            if (!empty($set_limit) && !empty($v)) {
                if ($set_limit != $v)
                    $data['sound'] = 'on';
                else
                    $data['sound'] = 'off';
            }
            // echo($data['sound']);
            Session::put('lastest_fldencounterval', $set_limit);
        }

        $data['fldcomp_order'] = $fldcomp_order;
        $data['departments'] = DB::table('tblmacaccess')->get();

        return view('outpatient::queue.pathology', $data);
    }


    public function laboratory(Request $request)
    {

        $data = array();
        $v = '';
        $data['sound'] = 'off';
        $status = 'Waiting';
        $limit = config('constants.max_total_records');
        $from_date = Carbon::parse(config('constants.current_date'))->setTime(0, 0, 0);
        $to_date = Carbon::parse(config('constants.current_date'))->setTime(23, 59, 59);

        $all_consultants = DB::table('tblpatbilling')
            ->join('tblencounter', 'tblencounter.fldencounterval', '=', 'tblpatbilling.fldencounterval')
            ->join('tblpatientinfo', 'tblpatientinfo.fldpatientval', '=', 'tblencounter.fldpatientval')
            ->select('tblpatbilling.*', 'tblpatientinfo.*', 'tblencounter.*', 'tblencounter.fldadmitlocat as department')
            // ->where('tblpatbilling.flditemtype', 'Diagnostic Tests')
            // ->where('tblpatbilling.fldsave', True)
            // ->where('tblpatbilling.fldsample', $status)
            // ->where('tblpatbilling.fldordtime', '>=', $from_date)
            // ->where('tblpatbilling.fldordtime', '<=', $to_date)
            ->groupBy('tblpatbilling.fldencounterval')
            ->limit($limit);


        $fldadmitlocat = [];
        if ($request->has('all_departments')) {
            $fldadmitlocat = $request->get('all_departments');
            if (in_array('Indoor', $fldadmitlocat)) {
                $indorlist = DB::table('tbldepartmentbed')->select('fldbed')
                    ->where('tbldepartmentbed.flddept', 'Indoor')->get()->toArray();
                $indorlistarr = array();
                if ($indorlist) {

                    foreach ($indorlist as $k => $indor) {
                        $indorlistarr[$k] = $indor->fldbed;
                    }
                }

                $all_dept = array_merge($indorlistarr, $fldadmitlocat);


                // $calendar = new \Fivedots\NepaliCalendar\Calendar();

                // // Get English to Nepali converted date
                // print_r($calendar->englishToNepali(2015,1,1));

                // // Get Nepali to English converted date
                // print_r($calendar->nepaliToEnglish(2071,9,17));
                // die();

                $all_consultants->whereIn('tblencounter.fldadmitlocat', $all_dept);
            } else {
                $all_consultants->whereIn('tblencounter.fldadmitlocat', $fldadmitlocat);
            }
        }

        $fldtarget = [];
        if ($request->has('all_targets')) {
            $fldtarget = $request->get('all_targets');

            $all_consultants->whereIn('tblpatbilling.fldtarget', $fldtarget);
        }


        $data['lists'] = $all_consultants->get();
        if ($data['lists']) {
            $set_limit = array();
            foreach ($data['lists'] as $k => $consult) {

                $set_limit[$k] = '';//$consult->confldid;
            }

            $v = session('lastest_fldencounterval');


            if (!empty($set_limit) && !empty($v)) {
                if ($set_limit != $v)
                    $data['sound'] = 'on';
                else
                    $data['sound'] = 'off';
            }
            // echo($data['sound']);
            Session::put('lastest_fldencounterval', $set_limit);
        }
        //dd($data['lists']);

        $data['fldadmitlocat'] = $fldadmitlocat;
        $data['fldtarget'] = $fldtarget;
        $data['departments'] = DB::table('tblencounter')->select('fldadmitlocat')->groupBy('fldadmitlocat')->get();
        $data['targets'] = DB::table('tblservicecost')->select('fldtarget')->where('fldtarget', 'like', 'comp%')->groupBy('fldtarget')->get();


        return view('outpatient::queue.laboratory', $data);
    }


    public function radiology(Request $request)
    {

        $data = array();
        $v = '';
        $data['sound'] = 'off';
        $status = 'Waiting';
        $limit = config('constants.max_total_records');
        $from_date = Carbon::parse(config('constants.current_date'))->setTime(0, 0, 0);
        $to_date = Carbon::parse(config('constants.current_date'))->setTime(23, 59, 59);

        $all_consultants = DB::table('tblpatbilling')
            ->join('tblencounter', 'tblencounter.fldencounterval', '=', 'tblpatbilling.fldencounterval')
            ->join('tblpatientinfo', 'tblpatientinfo.fldpatientval', '=', 'tblencounter.fldpatientval')
            ->select('tblpatbilling.*', 'tblpatientinfo.*', 'tblencounter.*', 'tblencounter.fldadmitlocat as department')
            // ->where('tblpatbilling.flditemtype', 'Radio Diagnostics')
            // ->where('tblpatbilling.fldsave', True)
            // ->where('tblpatbilling.fldsample', $status)
            // ->where('tblpatbilling.fldordtime', '>=', $from_date)
            // ->where('tblpatbilling.fldordtime', '<=', $to_date)
            ->groupBy('tblpatbilling.fldencounterval')
            ->limit($limit);


        $fldadmitlocat = [];
        if ($request->has('all_departments')) {
            $fldadmitlocat = $request->get('all_departments');
            if (in_array('Indoor', $fldadmitlocat)) {
                $indorlist = DB::table('tbldepartmentbed')->select('fldbed')
                    ->where('tbldepartmentbed.flddept', 'Indoor')->get()->toArray();
                $indorlistarr = array();
                if ($indorlist) {

                    foreach ($indorlist as $k => $indor) {
                        $indorlistarr[$k] = $indor->fldbed;
                    }
                }

                $all_dept = array_merge($indorlistarr, $fldadmitlocat);


                // $calendar = new \Fivedots\NepaliCalendar\Calendar();

                // // Get English to Nepali converted date
                // print_r($calendar->englishToNepali(2015,1,1));

                // // Get Nepali to English converted date
                // print_r($calendar->nepaliToEnglish(2071,9,17));
                // die();

                $all_consultants->whereIn('tblencounter.fldadmitlocat', $all_dept);
            } else {
                $all_consultants->whereIn('tblencounter.fldadmitlocat', $fldadmitlocat);
            }
        }

        $fldtarget = [];
        if ($request->has('all_targets')) {
            $fldtarget = $request->get('all_targets');

            $all_consultants->whereIn('tblpatbilling.fldtarget', $fldtarget);
        }

        //comp bhako matra target line


        $data['lists'] = $all_consultants->get();
        if ($data['lists']) {
            $set_limit = array();
            foreach ($data['lists'] as $k => $consult) {

                $set_limit[$k] = '';//$consult->confldid;
            }

            $v = session('lastest_fldencounterval');


            if (!empty($set_limit) && !empty($v)) {
                if ($set_limit != $v)
                    $data['sound'] = 'on';
                else
                    $data['sound'] = 'off';
            }
            //echo($data['sound']);
            Session::put('lastest_fldencounterval', $set_limit);
        }
        //dd($data['lists']);

        $data['fldadmitlocat'] = $fldadmitlocat;
        $data['fldtarget'] = $fldtarget;
        $data['departments'] = DB::table('tblencounter')->select('fldadmitlocat')->groupBy('fldadmitlocat')->get();
        $data['targets'] = DB::table('tblservicecost')->select('fldtarget')->where('fldtarget', 'like', 'comp%')->groupBy('fldtarget')->get();


        return view('outpatient::queue.radiology', $data);
    }

    public function searchPatient(Request $request){
        $html = '';
        try {
            $result = GlobalPatientSearch::select('fldptnamefir','fldmidname','fldptnamelast','fldptcontact','fldpatientval','fldrank')
                                    ->where('fldptnamefir','LIKE', '%'.$request->key.'%')
                                    ->orWhere('fldmidname','LIKE', '%'.$request->key.'%')
                                    ->orWhere('fldptnamelast','LIKE', '%'.$request->key.'%')
                                    ->orWhere('fldptcontact','LIKE', '%'.$request->key.'%')
                                    ->orWhere('fldencounterval','LIKE', '%'.$request->key.'%')
                                    ->orWhere('fldpatientval','LIKE', '%'.$request->key.'%')
                                    ->groupBy('fldpatientval')
                                    ->paginate(20);
            if(isset($result) and count($result) > 0){
                foreach($result as $key=>$b){
                    $user_rank = ((Options::get('system_patient_rank') == 1) && isset($b) && isset($b->fldrank)) ? $b->fldrank : '';
                    $patient_name = '<td>' . $user_rank . ' ' . $b->fldptnamefir . ' ' . $b->fldmidname . ' ' . $b->fldptnamelast . '</td>';
                    $html .='<div class="col-md-6">
                    <div class="profile-form form-group form-row align-items-center">
                        <label for="" class="control-label col-sm-3 mb-0">'.$patient_name.'</label>
                        <div class="col-sm-6">';
                        // <select name="patient_encounter" class="form-control patient_encounter">
                        // foreach($b->encounter as $k=>$encounter){
                                //     $html .='<option value="'.$encounter->fldencounterval.'">'.$encounter->fldencounterval.'</option>';
                                // }
                                // </select>
                        $html .='<input type="text" class="form-control" name="fldpatientval" id="fldpatientval" value="'.$b->fldpatientval.'" readonly>';
                    $html .='</div>
                            <div class="col-sm-2">
                                <a href="javascript:;"><i class="ri-check-fill"></i></a>
                            </div>
                        </div>
                        </div>';
                        // class="selectPatientEncounter"
                }
                $html .= '<div>'.$result->appends(request()->all())->links().'</div>';
            }else{
                $html .='<div class="col-md-6">
                            <div class="profile-form form-group form-row align-items-center">
                                <p>Search Result Not Found ... </p>
                            </div>
                        </div>';
            }
            return response()->json([
                'success' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => [
                    'status' => false,
                    'html' => $html,
                ]
            ]);
        }
    }
}
