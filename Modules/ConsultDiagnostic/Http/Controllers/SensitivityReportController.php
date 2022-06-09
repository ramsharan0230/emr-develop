<?php

namespace Modules\ConsultDiagnostic\Http\Controllers;

use App\Exam;
use App\PatientExam;
use App\ExamQuali;
use App\TestQuali;
use App\Sampletype;
use App\SensitivityDrug;
use App\PatLabSubTable;
use App\Encounter;
use App\PatientInfo;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Utils\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SensitivityReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displaySensitivityForm()
    {
        if (Permission::checkPermissionFrontendAdmin('sensitivity-report')) {
            Helpers::jobRecord('fmSensiReport', 'Sensitivity Report');
            // $data['comp'] = Helpers::getCompName();
            // $data['exams'] = Exam::select('fldexamid')->where('fldtype','LIKE', '%')->get();
            $data['organism'] = TestQuali::select('fldsubtest')->where('fldtanswertype', 'Drug Sensitivity')->orWhere('fldtanswertype', 'Who Sensitivity')->distinct()->get();
            $data['drugs'] = SensitivityDrug::select('flclass')->get();
            $data['specimen'] = Sampletype::select('fldsampletype')->get();
            $currentdate = Helpers::dateEngToNepdash(date('Y-m-d'));
        
            $data['date'] = $currentdate->year.'-'.$currentdate->month.'-'.$currentdate->date;
            return view('consultdiagnostic::sensitivity', $data);
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }




    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchSensiTest(Request $request)
    {
        // echo "here"; exit;
        $html = '';

    	$fromdate = Helpers::dateNepToEng($request->fdate);
        $todate = Helpers::dateNepToEng($request->tdate);
        $fdate = $fromdate->year.'-'.$fromdate->month.'-'.$fromdate->date;
        $tdate = $todate->year.'-'.$todate->month.'-'.$todate->date;
    	$specimen = $request->specimen;
    	$organism = $request->organism;
    	$drug = $request->drug;
    	$resultval = $request->result;
    	$status = $request->status;
    	if($status == 'Verified'){

            $compcolumn = 'fldtime_verify';
        } elseif ($status == 'Reported') {
            $compcolumn = 'fldtime_report';
        } else {
            $compcolumn = 'fldtime_sample';
        }
        try {
            $result = DB::table('tblpatlabsubtable as st')->select('st.fldvariable', 'st.fldvalue as svalue', 'pt.' . $compcolumn . '', 'pt.fldencounterval', 'pt.fldid', 'pt.fldsampletype', 'pt.fldmethod', 'pst.fldsubtest as growth')
                ->join('tblpatlabsubtest as pst', 'st.fldsubtestid', '=', 'pst.fldid')
                ->join('tblpatlabtest as pt','pst.fldtestid','=','pt.fldid')
                ->whereBetween('pt.'.$compcolumn.'', [$fdate, $tdate])
                ->where('pst.fldtanswertype','Drug Sensitivity')
                ->orWhere('pst.fldtanswertype','WHO Sensitivity')
                ->whereRaw('LOWER(pt.fldsampletype) LIKE ?',$specimen)
                ->whereRaw('LOWER(pst.fldsubtest) LIKE ?',$organism)
                ->whereRaw('LOWER(st.fldvariable) LIKE ?',$drug)
                ->where('st.fldvalue','LIKE',$resultval)
                ->get();
            // echo $result; exit;
            // SELECT tblpatlabtest.fldtime_report,tblpatlabtest.fldencounterval,tblpatlabtest.fldencounterval,tblpatlabtest.fldencounterval,tblpatlabtest.fldid,tblpatlabtest.fldsampletype,tblpatlabtest.fldmethod,tblpatlabsubtest.fldsubtest,tblpatlabsubtable.fldvariable,tblpatlabsubtable.fldvalue as svalue FROM (tblpatlabsubtable inner join tblpatlabsubtest on tblpatlabsubtable.fldsubtestid=tblpatlabsubtest.fldid) inner join tblpatlabtest on tblpatlabsubtest.fldtestid=tblpatlabtest.fldid WHERE tblpatlabtest.fldtime_report>='2019-07-31 00:00:00' and tblpatlabtest.fldtime_report<='2020-07-07 23:59:59.999' and lower(tblpatlabtest.fldsampletype) like '%' and (tblpatlabsubtest.fldtanswertype='Drug Sensitivity' or tblpatlabsubtest.fldtanswertype='WHO Sensitivity') and lower(tblpatlabsubtest.fldsubtest) like '%' and lower(tblpatlabsubtable.fldvariable) like '%' and tblpatlabsubtable.fldvalue like '%'
            if (isset($result) and count($result) > 0) {
                foreach ($result as $r) {
                    $encounter = Encounter::where('fldencounterva', $r->fldencounterval)->first();
                    $patientinfo = PatientInfo::where(' fldpatientval', $encounter->fldpatientval)->first();
                    $html .= '<tr>';
                    $html .= '<td>' . $r->$compcolumn . '</td>';
                    $html .= '<td>' . $r->fldencounterval . '</td>';
                    $user_rank = ((Options::get('system_patient_rank') == 1) && isset($encounter) && isset($encounter->fldrank)) ? $encounter->fldrank : '';
                    $html .= '<td>' . $user_rank . ' ' . $patientinfo->fldptnamefir. ' ' . $patientinfo->fldmidname . ' ' . $patientinfo->fldptnamelast . '</td>';
                    $html .= '<td>' . $patientinfo->fldptsex . '</td>';
                    $html .= '<td>' . $r->fldsampletype . '</td>';
                    $html .= '<td>' . $r->fldmethod . '</td>';
                    $html .= '<td>' . $r->growth . '</td>';
                    $html .= '<td>' . $r->fldvariable . '</td>';
                    $html .= '<td>' . $r->fldvalue . '</td>';
                    $html .= '</tr>';
                }
            }
            echo $html;
            exit;
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
