<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class IpEventController extends Controller
{
    public function index(Request $request)
    {
        $date_from = $request->get('date_from') ?: date('Y-m-d');
        $date_to = $request->get('date_to') ?: date('Y-m-d');

        $all_data = [];
        if ($request->isMethod('post'))
            $all_data = $this->_get_ipevents($request, $date_from, $date_to);

        if ($request->get('type') == 'Export') {
            $date_range = $this->_format_datetime($date_from) . " to " . $this->_format_datetime($date_to);
            return \Barryvdh\DomPDF\Facade::loadView('reports::ipevent.report', compact('all_data', 'date_range'))
                ->download('ip_events.pdf');
        }

        $departments = \DB::table('tbldepartmentbed')->select('flddept')->distinct()->get();
        return view('reports::ipevent.index', compact('departments', 'all_data', 'date_from', 'date_to'));
    }

    private function _get_ipevents($request, $date_from, $date_to)
    {
        $flddept = $request->get('flddept');
        $status = $request->get('status');
        $gender = $request->get('gender');
        $age_from = $request->get('age_from');
        $age_to = $request->get('age_to');

        $raws = \DB::table('tblencounter AS e')
            ->select('e.fldencounterval', 'e.fldadmission','e.fldrank', 'pi.fldptnamefir' , 'pi.fldmidname', 'pi.fldptnamelast', 'pi.fldencrypt', 'pi.fldptaddvill', 'pi.fldptadddist', 'pi.fldptbirday', 'pi.fldptsex', 'pd.fldhead', 'e.fldregdate', 'db.fldbed', 'pd.fldtime')
            ->join('tblpatientinfo AS pi', 'pi.fldpatientval', '=', 'e.fldpatientval')
            ->join('tblpatientdate AS pd', 'pd.fldencounterval', '=', 'e.fldencounterval')
            ->join('tbldepartmentbed AS db', 'db.fldencounterval', '=', 'e.fldencounterval')
            ->where([
                ["pd.fldtime", ">=", "$date_from 00:00:00"],
                ["pd.fldtime", "<=", "$date_to 23:59:59.999"],
            ]);

        if ($flddept)
            $raws->where('db.flddept', $flddept);
        if ($status)
            $raws->where('pd.fldhead', $status);
        if ($gender)
            $raws->where('pi.fldptsex', $gender);

        $raws = $raws->orderBy('e.fldencounterval')->orderBy('pd.fldtime')->get();

        $all_data = [];
        foreach ($raws as &$raw) {
            $raw->age = $this->_get_age($raw->fldptbirday);
            $raw->fldtime = $this->_format_datetime($raw->fldtime);
            $raw->fldregdate = $this->_format_datetime($raw->fldregdate);
            $all_data[$raw->fldencounterval] = $raw;
        }
        return array_values($all_data);
    }

    private function _format_datetime($datetime, $returndatetime = FALSE)
    {
        $datetime = explode(' ', $datetime);
        $englishdate = $datetime[0];
        $nepalidate = \App\Utils\Helpers::dateEngToNep(str_replace('-', '/', $englishdate));
        $nepalidate->month = str_pad($nepalidate->month, 2, 0, STR_PAD_LEFT);
        $nepalidate = "{$nepalidate->year}-{$nepalidate->month}-{$nepalidate->date}";
        
        if ($returndatetime) {
            $time = substr($datetime[1], 0, -3);
            return "$nepalidate $time";
        }

        return $nepalidate;
    }

    private function _get_age($dob)
    {
        $dob = new \DateTime($dob);
        $now = new \DateTime();
        $interval = $now->diff($dob);

        $age = $interval->y;

        if ($age == 0) {
            $age = "{$interval->m}Mon";
        } else if ($interval->m !== 0) {
            $age += round($interval->m*0.12, 1);
            $age .= "Yr";
        }
        return $age;
    }
}
