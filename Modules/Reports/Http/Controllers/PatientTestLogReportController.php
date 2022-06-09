<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\PatBillDetail;
use App\HospitalDepartmentUsers;
use App\PatBilling;
use Auth;
use Carbon\Carbon;
use App\Patientcreditcolor;
use App\Utils\Helpers;
use DB;
use Excel;
use App\Exports\PatientTestLogExport;

class PatientTestLogReportController extends Controller
{
    public function index(Request $request) {
        $patient_id=$request->patient_id;
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;
        $query=DB::table('tblpatlabtest as plt')
                                ->select(
                                    "plt.fldid",
                                    "pi.fldpatientval",
                                    "plt.fldencounterval",
                                    "pi.fldptnamefir",
                                    "pi.fldptnamelast",
                                    "plt.fldsampleid",
                                    "plt.fldtestid",
                                    "plt.fldtime_sample",
                                    "plt.flduserid_sample",
                                    "plt.fldtime_report",
                                    "plt.flduserid_report",
                                    "plt.fldtime_verify",
                                    "plt.flduserid_verify"
                                    )
                                ->join('tblencounter as en','plt.fldencounterval','=','en.fldencounterval')
                                ->join('tblpatientinfo as pi','en.fldpatientval','=','pi.fldpatientval');
        if($patient_id){
            $query->where('pi.fldpatientval','like', '%'.$patient_id.'%');
        }
        if($eng_from_date){
			$query=	$query->whereDate('plt.fldtime_sample','>=', $eng_from_date)->whereDate('plt.fldtime_sample','<=', $eng_to_date);
		}
        $records=$query->whereRaw(DB::raw('fldid in (SELECT MAX(fldid) from tblpatlabtest GROUP BY fldencounterval)'))->orderBy('plt.fldencounterval','desc')->paginate(25);
        $datevalue = Helpers::dateEngToNepdash(date('Y-m-d'));
        $date = $datevalue->year . '-' . $datevalue->month . '-' . $datevalue->date;
        // dd($records);
        return view('reports::patient-test-log-report.index',compact('records','patient_id','date'));
    }

    public function patientTestLogPdfReport(Request $request){
        ini_set("memory_limit", "10056M");
        $patient_id=$request->patient_id;
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;
        $query=DB::table('tblpatlabtest as plt')
                                ->select(
                                    "plt.fldid",
                                    "pi.fldpatientval",
                                    "plt.fldencounterval",
                                    "pi.fldptnamefir",
                                    "pi.fldptnamelast",
                                    "plt.fldsampleid",
                                    "plt.fldtestid",
                                    "plt.fldtime_sample",
                                    "plt.flduserid_sample",
                                    "plt.fldtime_report",
                                    "plt.flduserid_report",
                                    "plt.fldtime_verify",
                                    "plt.flduserid_verify"
                                    )
                                ->join('tblencounter as en','plt.fldencounterval','=','en.fldencounterval')
                                ->join('tblpatientinfo as pi','en.fldpatientval','=','pi.fldpatientval');
        if($patient_id){
            $query->where('pi.fldpatientval','like', '%'.$patient_id.'%');
        }
        if($eng_from_date){
			$query=	$query->whereDate('plt.fldtime_sample','>=', $eng_from_date)->whereDate('plt.fldtime_sample','<=', $eng_to_date);
		}
        $records=$query->whereRaw(DB::raw('fldid in (SELECT MAX(fldid) from tblpatlabtest GROUP BY fldencounterval)'))->orderBy('plt.fldencounterval','desc')->get();
        return view('reports::patient-test-log-report.pdf.patient-test-log-pdf-report',compact('records'));
    }

    public function patientTestLogExportReport(Request $request){
        $patient_id=$request->patient_id;
        $eng_from_date=$request->eng_from_date;
        $eng_to_date=$request->eng_to_date;

        $export = new PatientTestLogExport($patient_id='',$eng_from_date='',$eng_to_date='');
        ob_end_clean();
        ob_start();
        
        return Excel::download($export, 'patient-test-log-report.xlsx');
    }
}
