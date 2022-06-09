<?php

namespace Modules\QueueManagement\Http\Controllers;

use App\PatBilling;
use App\Pathdosing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use Session;

class QueueManagementController extends Controller
{
    /*public function index()
    {
        $data = [];
        return view('queuemanagement::index', $data);
    }*/

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
            ->where('tblconsult.fldconsulttime', '>=', $from_date)
            ->where('tblconsult.fldconsulttime', '<=', $to_date)
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

                $set_limit[$k] = ''; //$consult->confldid;
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

        return view('queuemanagement::index', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pharmacy(Request $request)
    {
        $data = array();
        $v = '';
        $data['sound'] = 'off';
        $status = 'Continue';
        $limit = config('constants.max_total_records');
        $from_date = Carbon::parse(config('constants.current_date'))->setTime(0, 0, 0);
        $to_date = Carbon::parse(config('constants.current_date'))->setTime(23, 59, 59);

        if ($request->has('all_departments')) {
            $fldcomp_order = $request->get('all_departments');
            $data['lists'] = Pathdosing::select('*', 'flduserid as consultant')
                ->where('tblpatdosing.fldsave_order', False)
                ->where('tblpatdosing.fldsave', True)
                ->where('tblpatdosing.fldorder', 'Request')
                ->where('tblpatdosing.fldcurval', $status)
                ->where('fldtime_order', '>=', $from_date)
                ->where('fldtime_order', '<=', $to_date)
                ->whereIn('tblpatdosing.fldcomp_order', $fldcomp_order)
                ->with('encounter', 'encounter.patientInfo')
                ->groupBy('tblpatdosing.fldencounterval')->limit($limit)->get();
        } else {
            $data['lists'] = Pathdosing::select('*', 'flduserid as consultant')
                ->where('tblpatdosing.fldsave_order', False)
                ->where('tblpatdosing.fldsave', True)
                ->where('tblpatdosing.fldorder', 'Request')
                ->where('tblpatdosing.fldcurval', $status)
                ->where('fldtime_order', '>=', $from_date)
                ->where('fldtime_order', '<=', $to_date)
                ->with('encounter', 'encounter.patientInfo')
                ->groupBy('tblpatdosing.fldencounterval')->limit($limit)->get();
        }
        if ($data['lists']) {
            $set_limit = array();
            foreach ($data['lists'] as $k => $consult) {

                $set_limit[$k] = ''; //$consult->confldid;
            }

            $v = Session::get('lastest_fldencounterval');


            if (!empty($set_limit) && !empty($v)) {
                if ($set_limit != $v)
                    $data['sound'] = 'on';
                else
                    $data['sound'] = 'off';
            }
            // echo($data['sound']);
            Session::put('lastest_fldencounterval', $set_limit);
        }

        $data['fldcomp_order'] = $fldcomp_order ?? [];
        $data['departments'] = DB::table('tblmacaccess')->get();

        return view('queuemanagement::pathology', $data);
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function getDynamicDataPharmacy(Request $request)
    {
        $data = array();
        $v = '';
        $data['sound'] = 'off';
        $status = 'Continue';
        $limit = config('constants.max_total_records');
        $from_date = Carbon::parse(config('constants.current_date'))->setTime(0, 0, 0);
        $to_date = Carbon::parse(config('constants.current_date'))->setTime(23, 59, 59);


        if ($request->has('all_departments')) {
            $fldcomp_order = $request->get('all_departments');
            $data['lists'] = Pathdosing::select('*', 'flduserid as consultant')
                ->where('tblpatdosing.fldsave_order', False)
                ->where('tblpatdosing.fldsave', True)
                ->where('tblpatdosing.fldorder', 'Request')
                ->where('tblpatdosing.fldcurval', $status)
                ->where('fldtime_order', '>=', $from_date)
                ->where('fldtime_order', '<=', $to_date)
                ->whereIn('tblpatdosing.fldcomp_order', $fldcomp_order)
                ->with('encounter', 'encounter.patientInfo')
                ->groupBy('tblpatdosing.fldencounterval')->limit($limit)->get();
        } else {
            $data['lists'] = Pathdosing::select('*', 'flduserid as consultant')
                ->where('tblpatdosing.fldsave_order', False)
                ->where('tblpatdosing.fldsave', True)
                ->where('tblpatdosing.fldorder', 'Request')
                ->where('tblpatdosing.fldcurval', $status)
                ->where('fldtime_order', '>=', $from_date)
                ->where('fldtime_order', '<=', $to_date)
                ->with('encounter', 'encounter.patientInfo')
                ->groupBy('tblpatdosing.fldencounterval')->limit($limit)->get();
        }

        if ($data['lists']) {
            $set_limit = array();
            foreach ($data['lists'] as $k => $consult) {

                $set_limit[$k] = ''; //$consult->confldid;
            }

            $v = Session::get('lastest_fldencounterval');


            if (!empty($set_limit) && !empty($v)) {
                if ($set_limit != $v)
                    $data['sound'] = 'on';
                else
                    $data['sound'] = 'off';
            }
            // echo($data['sound']);
            Session::put('lastest_fldencounterval', $set_limit);
        }

        $html = view('queuemanagement::dynamic-views.pharmacy', $data)->render();
        return $html;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function laboratory(Request $request)
    {

        $data = array();
        $v = '';
        $data['sound'] = 'off';
        $status = 'Waiting';
        $limit = config('constants.max_total_records');
        $from_date = Carbon::parse(config('constants.current_date'))->setTime(0, 0, 0);
        $to_date = Carbon::parse(config('constants.current_date'))->setTime(23, 59, 59);

        $all_consultants = PatBilling::select('*')
            ->where('tblpatbilling.flditemtype', 'Diagnostic Tests')
            ->where('tblpatbilling.fldsave', True)
            ->where('tblpatbilling.fldsample', $status)
            ->where('tblpatbilling.fldordtime', '>=', $from_date)
            ->where('tblpatbilling.fldordtime', '<=', $to_date)
            ->groupBy('tblpatbilling.fldencounterval')
            ->with('encounter', 'encounter.patientInfo')
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
                $set_limit[$k] = ''; //$consult->confldid;
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


        return view('queuemanagement::laboratory', $data);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDynamicDataLaboratory(Request $request)
    {

        $data = array();
        $v = '';
        $data['sound'] = 'off';
        $status = 'Waiting';
        $limit = config('constants.max_total_records');
        $from_date = Carbon::parse(config('constants.current_date'))->setTime(0, 0, 0);
        $to_date = Carbon::parse(config('constants.current_date'))->setTime(23, 59, 59);

        $all_consultants = PatBilling::select('*')
            ->where('tblpatbilling.flditemtype', 'Diagnostic Tests')
            ->where('tblpatbilling.fldsave', True)
            ->where('tblpatbilling.fldsample', $status)
            ->where('tblpatbilling.fldordtime', '>=', $from_date)
            ->where('tblpatbilling.fldordtime', '<=', $to_date)
            ->groupBy('tblpatbilling.fldencounterval')
            ->with('encounter', 'encounter.patientInfo')
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
                $set_limit[$k] = ''; //$consult->confldid;
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

        $html = view('queuemanagement::dynamic-views.laboratory', $data)->render();
        return $html;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function radiology(Request $request)
    {
        //  DB::enableQueryLog();

        $data = array();
        $v = '';
        $data['sound'] = 'off';
        $status = 'Waiting';
        $limit = config('constants.max_total_records');
        $from_date = Carbon::parse(config('constants.current_date'))->setTime(0, 0, 0);
        $to_date = Carbon::parse(config('constants.current_date'))->setTime(23, 59, 59);


        // $all_consultants = DB::table('tblpatbilling')
        //     ->join('tblencounter', 'tblencounter.fldencounterval', '=', 'tblpatbilling.fldencounterval')
        //     ->join('tblpatientinfo', 'tblpatientinfo.fldpatientval', '=', 'tblencounter.fldpatientval')
        //     ->join('tblgroupradio', 'tblpatbilling.flditemname', '=', 'tblgroupradio.fldgroupname')
        //     ->select('tblpatbilling.*', 'tblpatbilling.fldroomno', 'tblpatbilling.fldinside as patfldinside', 'tblpatientinfo.*', 'tblencounter.*', 'tblencounter.fldcurrlocat as department')
        //     ->where('tblpatbilling.flditemtype', 'Radio Diagnostics')
        //     ->where('tblpatbilling.fldsave', True)
        //     ->whereIn('tblpatbilling.fldsample', ['Sampled','Waiting'])
        //     ->where('tblpatbilling.fldordtime', '>=', $from_date)
        //     ->where('tblpatbilling.fldordtime', '<=', $to_date)
        //     ->orderBy('tblpatbilling.fldinside')
        //     ->groupBy('tblpatbilling.fldencounterval')
        //     ->limit($limit);

        $all_consultants = PatBilling::select('tblpatbilling.fldencounterval','tblpatbilling.fldordtime', 'tblpatbilling.fldid', 'tblpatbilling.fldreason', 'tblpatradiotest.fldreportquali', 'tblpatradiotest.fldcomment', 'tblpatradiotest.fldtestid', 'tblpatradiotest.fldid AS tblpatradiotestid', 'fldsample', 'tblradio.fldcategory', 'tblpatradiotest.flduserid_report', 'tblpatradiotest.fldnewdate', 'tblpatradiotest.fldinside as  patfldinside', 'tblpatradiotest.fldroomno')
            ->with([
                'encounter:fldencounterval,fldpatientval,fldrank,fldcurrlocat as department',
                'encounter.patientInfo:fldpatientval,fldptnamefir,fldptnamelast,fldptaddvill,fldptadddist,fldptcontact,fldptsex,fldptbirday,fldmidname,fldrank',
                'encounter.consultant:fldencounterval,fldconsultname,flduserid',
                'encounter.consultant.user:flduserid,firstname,middlename,lastname',
            ])->leftJoin('tblgroupradio', 'tblpatbilling.flditemname', '=', 'tblgroupradio.fldgroupname')
            ->leftJoin('tblpatradiotest', function ($join) {
                $join->on('tblpatbilling.fldencounterval', '=', 'tblpatradiotest.fldencounterval');
                $join->on('tblgroupradio.fldtestid', '=', 'tblpatradiotest.fldtestid');
            })->leftJoin('tblradio', 'tblradio.fldexamid', '=', 'tblpatradiotest.fldtestid')
            ->where([
                ['tblpatbilling.flditemtype', 'Radio Diagnostics'],
                ['tblpatbilling.fldsave', '1'],
                // ['flditemqty', '>', 'fldretqty'],
            ])->where(function ($query) {
                $query->whereIn('tblpatbilling.fldsample', ['Waiting','Sampled']);
               
            })->where(function ($query) use ($from_date, $to_date) {
                $query->where('tblpatbilling.fldordtime', ">=",  $from_date);
                $query->where('tblpatbilling.fldordtime', "<=", $to_date);
               
            })
            ->orderBy('tblpatradiotest.fldinside', 'DESC')
            ->groupBy('tblpatbilling.fldencounterval')
            ->limit($limit);

        $fldroomno = '';
        if ($request->has('fldroomno')) {
            $fldroomno = $request->get('fldroomno');
            // dd($room_no);
            if(!empty($fldroomno)){
                $all_consultants->where('tblpatradiotest.fldroomno', $fldroomno);
            }
            
        }


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

        $fldcategory = [];
        if ($request->has('all_radiocategory')) {
            $fldcategory = $request->get('all_radiocategory');

            $all_consultants->whereIn('tblradio.fldcategory', $fldcategory);
        }



        //dd($all_consultants);

        //comp bhako matra target line


        $data['lists'] = $all_consultants->get();

        //  $quries = DB::getQueryLog();
        //dd($quries);
       // dd($data['lists']);
        if ($data['lists']) {
            $set_limit = array();
            foreach ($data['lists'] as $k => $consult) {

                $set_limit[$k] = ''; //$consult->confldid;
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
        $data['fldcategory'] = $fldcategory;
        $data['fldroomno'] = $fldroomno;
        $data['departments'] = DB::table('tblencounter')->select('fldadmitlocat')->groupBy('fldadmitlocat')->get();
        $data['targets'] = DB::table('tblservicecost')->select('fldtarget')->where('fldtarget', 'like', 'comp%')->groupBy('fldtarget')->get();
        $data['radiotype'] = DB::table('tblradio')->select('fldcategory')->groupBy('fldcategory')->get();

        return view('queuemanagement::radiology', $data);
    }
}
