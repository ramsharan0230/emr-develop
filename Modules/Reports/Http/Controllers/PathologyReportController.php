<?php

namespace Modules\Reports\Http\Controllers;

use App\BillingSet;
use App\Exports\OrderVsReciveExport;
use App\HospitalDepartment;
use App\Order;
use App\PatLabTest;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class PathologyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [];
        $test = array();
        $from = $request->from_date;
        $to = $request->to_date;
        $billingmode = BillingSet::select('fldsetname')->get();
        $categories = PatLabTest::select('tbltest.fldcategory', 'tblpatlabtest.fldtestid')
            ->join('tbltest', 'tbltest.fldtestid', '=', 'tblpatlabtest.fldtestid')
            ->groupBy('tbltest.fldcategory')
            ->get();

        $data['billingmode'] = $billingmode;
        $data['categories'] = $categories;
        // if($request){
        //     $this->generatereport($from,$to);
        // }

        return view('reports::pathology_report', $data);
    }

    function generatereport(Request $request)
    {


        $test = array();
        $from_date = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d', strtotime('-30 days'));
        $to_date = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');
        $category = $request->category;
        $data['billingmode'] = $billingmodes = BillingSet::select('fldsetname')->where('status', 1)->pluck('fldsetname');
        if (!empty($category)) {
            $categories = PatLabTest::select('tbltest.fldcategory', 'tblpatlabtest.fldtestid')
                ->join('tbltest', 'tbltest.fldtestid', '=', 'tblpatlabtest.fldtestid')
                ->where('tbltest.fldcategory', $category)
                ->groupBy('tbltest.fldtestid')
                ->get();
        } else {
            $categories = PatLabTest::select('tbltest.fldcategory', 'tblpatlabtest.fldtestid')
                ->join('tbltest', 'tbltest.fldtestid', '=', 'tblpatlabtest.fldtestid')
                ->groupBy('tbltest.fldtestid')
                ->get();
        }


        if (!empty($from_date) && !empty($to_date)) {

            if ($categories) {
                if ($billingmodes) {
                    foreach ($billingmodes as $bill) {
                        foreach ($categories as $k => $category) {



                            $test[$category->fldcategory][$category->fldtestid][$bill] = PatLabTest::join('tblencounter', 'tblencounter.fldencounterval', '=', 'tblpatlabtest.fldencounterval')
                                ->where('tblpatlabtest.fldtestid', $category->fldtestid)
                                ->where('tblencounter.fldbillingmode', 'like', '%' . $bill . '%')
                                ->where('tblpatlabtest.fldtime_sample', '>=', $from_date)
                                ->where('tblpatlabtest.fldtime_sample', '<=', $to_date)
                                ->count();
                        }
                    }
                }
            }
        } else {
            if ($categories) {
                if ($billingmodes) {
                    foreach ($billingmodes as $bill) {
                        foreach ($categories as $k => $category) {


                            $test[$category->fldcategory][$category->fldtestid][$bill] = PatLabTest::join('tblencounter', 'tblencounter.fldencounterval', '=', 'tblpatlabtest.fldencounterval')
                                ->where('tblpatlabtest.fldtestid', $category->fldtestid)
                                ->where('tblencounter.fldbillingmode', 'like', '%' . $bill . '%')
                                ->count();
                        }
                    }
                }
            }
        }
        //    dd($test);





        // dd($test);
        $data['alltest'] = $test;

        $data['from_date'] = Helpers::dateEngToNep(date("Y/m/d", strtotime($from_date)))->full_date;
        $data['to_date'] = Helpers::dateEngToNep(date("Y/m/d", strtotime($to_date)))->full_date;
        // dd($data);

        // return PDF::loadView('reports::pathology_count', $data)->setPaper('a4')->stream('pathology-report.pdf');


        return view('reports::pathology_count', $data);
    }
}
