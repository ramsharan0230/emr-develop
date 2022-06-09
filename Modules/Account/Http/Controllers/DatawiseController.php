<?php

namespace Modules\Account\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class DatawiseController extends Controller
{
    public function index()
    {
        $moduleName = \Request::segment(3);
        $categories = NULL;
        if ($moduleName == 'majorprocedure')
            $categories = \App\ServiceCost::select('flditemname AS item')->where([
                'flditemtype' => 'Procedures',
                'fldtarget' => 'Major',
            ])->get();
        elseif ($moduleName == 'extraprocedure')
            $categories = \App\GroupProc::select('fldprocname AS item')->distinct()->get();
        elseif ($moduleName == 'radiologylist')
            $categories = \App\Radio::select('fldexamid AS item')->get();

        $data = [
            'moduleName' => $moduleName,
            'categories' => $categories,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
        ];
        return view('account::planreport', $data);
    }

    public function getPatientList(Request $request)
    {
        $moduleName = $request->get('moduleName');
        $flditemtype = ($moduleName == 'radiologylist') ? 'Radio Diagnostics' : 'Procedures';
        $where = [
            ['fldsave', '=', '1'],
            ['fldsample', '=', 'Waiting'],
            ['fldparent', '=', '0'],
            ['flditemqty', '>', 'fldretqty'],
            ['flditemtype', '=', $flditemtype],
        ];

        $data = \App\PatBilling::select('fldencounterval', 'flditemname')->where($where);
        if ($moduleName == 'majorprocedure' || $moduleName == 'extraprocedure') {
            $fldtarget = ($moduleName == 'majorprocedure') ? 'Major' : 'Extra';
            $data = $data->whereIn('flditemname', \App\ServiceCost::where('fldtarget', $fldtarget)->get()->pluck('flditemname'));
        }

        return response()->json(
            $data->get()
        );
    }

    public function getPatientDetail(Request $request)
    {
        $moduleName = $request->get('moduleName');
        $date = $request->get('date');
        $category = $request->get('category');
        $encounterId = $request->get('encounterId');

        $modelName = NULL;
        $columns = NULL;
        $categoryColumn = NULL;
        $where = [
            ["fldnewdate", ">=", "$date 00:00:00"],
            ["fldnewdate", "<=", "$date 23:59:59.999"],
            ["fldstatus", "=", "Sampled"],
            ['fldreportquali', '<>', 'Done'],
        ];
        if ($moduleName == 'radiologylist') {
            $categoryColumn = 'fldtestid';
            $modelName = '\App\PatRadioTest';
            $columns = ['fldencounterval', 'fldtestid', 'fldnewdate', 'fldid', 'flduserid_report'];
            $where[] = ["fldsave_report", "=", "0"];
        } else {
            $categoryColumn = 'flditem';
            $modelName = '\App\PatGeneral';
            $columns = ['fldencounterval', 'flditem AS fldtestid', 'fldid', 'fldnewdate', 'flduserid'];
        }

        if ($category)
            $where[] = [$categoryColumn, "like", $category];
        if ($encounterId)
            $where[] = ['fldencounterval', "=", $encounterId];

        return response()->json(
            $modelName::select($columns)->with(
                'encounter:fldencounterval,fldpatientval',
                'encounter.patientInfo:fldpatientval,fldptsex,fldptcontact,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldrank',
                'encounter.consultant:fldconsultname')->where($where)->get()
        );
    }

    public function patientDetailReport(Request $request)
    {
        $moduleName = $request->get('moduleName');
        $date = $request->get('date');
        $category = $request->get('category');

        $modelName = NULL;
        $columns = NULL;
        $categoryColumn = NULL;
        $where = [
            ["fldnewdate", ">=", "$date 00:00:00"],
            ["fldnewdate", "<=", "$date 23:59:59.999"],
            ["fldstatus", "=", "Sampled"],
            ['fldreportquali', '<>', 'Done'],
        ];
        if ($moduleName == 'radiologylist') {
            $categoryColumn = 'fldtestid';
            $modelName = '\App\PatRadioTest';
            $columns = ['fldencounterval', 'fldtestid', 'fldnewdate', 'fldid', 'flduserid_report'];
            $where[] = ["fldsave_report", "=", "0"];
        } else {
            $categoryColumn = 'flditem';
            $modelName = '\App\PatGeneral';
            $columns = ['fldencounterval', 'flditem AS fldtestid', 'fldid', 'fldnewdate', 'flduserid'];
        }

        if ($category)
            $where[] = [$categoryColumn, "like", $category];
        $all_data = $modelName::select($columns)->with(
                'encounter:fldencounterval,fldpatientval',
                'encounter.patientInfo:fldpatientval,fldptsex,fldptcontact,fldptnamefir,fldmidname,fldptnamelast,fldptbirday,fldrank',
                'encounter.consultant:fldconsultname')->where($where)->get();
        return view('account::patientDetailReportPdf', compact('all_data', 'modelName'));
    }
}
