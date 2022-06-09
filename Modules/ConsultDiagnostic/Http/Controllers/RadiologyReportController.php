<?php

namespace Modules\ConsultDiagnostic\Http\Controllers;

use App\Exam;
use App\PatientExam;
use App\PatientInfo;
use App\Encounter;
use App\ExamQuali;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class RadiologyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayRadiologyForm()
    {
    	Helpers::jobRecord('fmExamAll', 'Radiology Report');
    	$data['comp'] = Helpers::getCompName();
    	$data['exams'] = Exam::select('fldexamid')->where('fldtype','LIKE', '%')->get();
        $currentdate = Helpers::dateEngToNepdash(date('Y-m-d'));

        $data['date'] = $currentdate->year.'-'.$currentdate->month.'-'.$currentdate->date;
        return view('consultdiagnostic::radiology', $data);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listExamByCat(Request $request)
    {
    	$type = $request->section;
    	// echo $type; exit;
    	$result = PatientExam::select('fldhead')->where('fldinput','LIKE',$type)->where('fldsave',1)->distinct()->get();
    	$html ='<option value="%">%</option>';
    	if(isset($result) and count($result) > 0){
    		foreach($result as $r){
    			$html .='<option value="'.$r->fldhead.'">'.$r->fldhead.'</option>';
    		}
    	}
    	echo $html; exit;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listSubExam(Request $request)
    {
    	$exam = $request->exam;
    	// echo $type; exit;
    	$result = ExamQuali::select('fldsubexam')->where('fldexamid','LIKE',$exam)->get();
    	$html ='<option value="%">%</option>';
    	if(isset($result) and count($result) > 0){
    		foreach($result as $r){
    			$html .='<option value="'.$r->fldhead.'">'.$r->fldhead.'</option>';
    		}
    	}
    	echo $html; exit;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchRadiology(Request $request)
    {
        // dd($request->all());
    	// echo "here"; exit;
    	$fromdate = Helpers::dateNepToEng($request->fdate);
        $todate = Helpers::dateNepToEng($request->tdate);
        $fdate = $fromdate->year.'-'.$fromdate->month.'-'.$fromdate->date;
        $tdate = $todate->year.'-'.$todate->month.'-'.$todate->date;
    	$evaluation = $request->evaluation;
    	$section = $request->section;
    	$gender = $request->gender;
    	$fage = $request->fage;
    	$tage = $request->tage;
    	$exam = $request->exam;
    	$sexam = $request->sexam;
    	$ttype = $request->ttype;
        $sttype = $request->sttype;
    	$extext = $request->extext;
    	$agefrom = $fage * 365;
        $ageto   = $tage * 365;
        $status = $request->status;
        $txtsearch = $request->txSearch;
        // echo $status; exit;
        if($status == 'Reported' || $status == 'CheckIn' || $status == 'Ordered' || $status == 'Appointment'){
            $comparecolumn = 'fldtime_report';
        }else{
            $comparecolumn = 'fldtime_verify';
        }

        $html = '';
        // echo $status; exit;
        try{


            if($gender !=''){
                $genderquery = "and prt.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."'))";
            }else{
                $genderquery = '';
            }
            // echo $section; exit;
            if($section !='%'){
                $sectionquery = "and prt.fldtestid in(select r.fldexamid from tblradio as r where r.fldcategory like '".$section."')";
            }else{
                $sectionquery = '';
            }

            if($evaluation !='%'){
                $evaluationquery = "and prt.fldsampletype like '".$evaluation."'";
            }else{
                $evaluationquery = '';
            }

            if($ttype !='%'){
                $abnormalquery = "and prt.fldabnormal='".$ttype."'";
            }else{
                $abnormalquery = '';
            }
            if($extext !=''){
                $searchquery = "and lower(prst.fldreport) like '".$extext."'";
            }else{
                $searchquery = '';
            }

            if($sttype !='%'){
                $sabnormalquery = "and prst.fldabnormal='".$sttype."'";
            }else{
                $sabnormalquery = '';
            }
            if($sexam !='%'){
                $sexamquery = " and prt.fldid in(select prst.fldtestid from tblpatradiosubtest as prst where prst.fldsubtest like '".$sexam."' ".$searchquery." ".$sabnormalquery.")";
            }else{
                $sexamquery = '';
            }

            if($exam !='%'){
                $examquery = "and prt.fldtestid LIKE '".$exam."'";
            }else{
                $examquery = '';
            }
            $comparequery = "and prt.".$comparecolumn." <='".$tdate."'";

            if($status == 'CheckIn' || $status == 'Appointment' || $status == 'Ordered'){

                $statusquery = "and prt.fldstatus='".$status."'";

            }else{
                $statusquery = "";
            }
            // echo $statusquery; exit;
            if($txtsearch  == 1){
                $sql = "select prt.fldid, prt.fldencounterval, prt.fldtestid, prt.".$comparecolumn.", prt.fldstatus, prt.fldtest_type from tblpatradiotest as prt where prt.".$comparecolumn." >= '".$fdate."' ".$comparequery." ".$examquery." ".$genderquery." ".$sectionquery." ".$evaluationquery." ".$abnormalquery." ".$statusquery." and prt.fldreportquali IS NULL";
            }else{
                $sql = "select prt.fldid, prt.fldencounterval, prt.fldtestid, prt.".$comparecolumn.", prt.fldstatus, prt.fldtest_type from tblpatradiotest as prt where prt.".$comparecolumn." >= '".$fdate."' ".$comparequery." ".$examquery." ".$genderquery." ".$sectionquery." ".$evaluationquery." ".$abnormalquery." ".$sexamquery." ".$statusquery."";
            }
            // echo $sql; exit;
            $result  = DB::select(
                   $sql
                );
             // dd($result);
             if(isset($result) and count($result) > 0){
                foreach($result as $k=>$r){
                    $encounter = Encounter::where('fldencounterval', $r->fldencounterval)->first();
                    $patient = PatientInfo::where('fldpatientval',$encounter->fldpatientval)->first();
                    // $bday = $patient->fldptbirday;
                    // $diff = (date('Y') - date('Y',strtotime($bday)));
                    // $age = $diff;
                    $sn = $k+1;
                    $html .='<tr>';
                    $html .='<td>'.$sn.'</td>';
                    $html .='<td>'.$r->fldencounterval.'</td>';
                    $user_rank = ((Options::get('system_patient_rank') == 1) && isset($encounter) && isset($encounter->fldrank)) ? $encounter->fldrank : '';
                    $html .= '<td>' . $user_rank . ' ' . $patient->fldptnamefir . ' ' . $patient->fldmidname . ' ' . $patient->fldptnamelast . '</td>';
                    $html .='<td>'.$patient->fldagestyle.'</td>';
                    // $html .='<td>'.$age.'Yr'.'</td>';
                    $html .='<td>'.$patient->fldptsex.'</td>';
                    $html .='<td>'.$r->fldtestid.'</td>';
                    $html .='<td>'.$r->$comparecolumn.'</td>';
                    $html .='<td>'.$status.'</td>';
                    $html .='<td>'.$r->$comparecolumn.'</td>';

                }
             }else{
                $html .= "<tr><td colspan='9'>No Data Found</td></tr>";
             }
             echo $html; exit;
            // select fldid,fldencounterval,fldencounterval,fldid,fldencounterval,fldtestid,fldtime_report,fldstatus,fldid,fldtest_type,fldid from tblpatradiotest where fldtime_report>='2019-07-17 00:00:00' and fldtime_report<='2020-07-08 23:59:59.999' and fldtestid like '%'

            // select fldid,fldencounterval,fldencounterval,fldid,fldencounterval,fldtestid,fldtime_report,fldstatus,fldid,fldtest_type,fldid from tblpatradiotest where fldtime_report>='2019-07-17 00:00:00' and fldtime_report<='2020-07-08 23:59:59.999' and fldtestid like 'test' and fldencounterval in(select fldencounterval from tblencounter where fldpatientval in(select fldpatientval from tblpatientinfo where fldptsex like 'Male')) and fldtestid in(select fldexamid from tblradio where fldcategory like 'Neuroscience') and fldsampletype like 'evaluation' and fldabnormal='0' and fldid in(select fldtestid from tblpatradiosubtest where fldsubtest like 'subtest' and lower(fldreport) like 'text' and fldabnormal='1')

            // select fldid,fldencounterval,fldencounterval,fldid,fldencounterval,fldtestid,fldtime_report,fldstatus,fldid,fldtest_type,fldid from tblpatradiotest where fldtime_report>='2020-07-08 00:00:00' and fldtime_report<='2020-07-08 23:59:59.999' and fldtestid like '%' and fldtestid in(select fldexamid from tblradio where fldcategory like 'Neuroscience') and lower(fldreportquali) like 'ttext'

            // select fldid,fldencounterval,fldencounterval,fldid,fldencounterval,fldtestid,fldtime_report,fldstatus,fldid,fldtest_type,fldid from tblpatradiotest where fldtime_report>='2020-07-08 00:00:00' and fldtime_report<='2020-07-08 23:59:59.999' and fldtestid like '%' and fldreportquali IS NULL


	        // select fldid,fldencounterval,fldencounterval,fldid,fldencounterval,fldencounterval,fldhead,fldtime,fldcomp,fldid,fldtype,fldid,fldabnormal from tblpatientexam where fldtime>='2020-05-15 00:00:00' and fldtime<='2020-05-15 23:59:59.999' and fldhead like '%' and fldinput like '%' and fldabnormal like '1' and fldcomp like '%' and fldsave='1' and fldencounterval in(select fldencounterval from tblencounter where fldpatientval in(select fldpatientval from tblpatientinfo where fldptsex like '%' and fldptbirday like '%' and DATEDIFF(tblpatientexam.fldtime, tblpatientinfo.fldptbirday)>=0 and DATEDIFF(tblpatientexam.fldtime, tblpatientinfo.fldptbirday)<12775)) and fldid in(select fldtestid from tblpatradiosubtest where fldsubtest like 12775 and lower(fldreport) like 'dfd')

	        // select fldid,fldencounterval,fldencounterval,fldid,fldencounterval,fldencounterval,fldhead,fldtime,fldcomp,fldid,fldtype,fldid,fldabnormal from tblpatientexam where fldtime>='2020-05-15 00:00:00' and fldtime<='2020-05-15 23:59:59.999' and fldhead like '%' and fldinput like '%' and fldabnormal like '1' and fldcomp like '%' and fldsave='1' and fldencounterval in(select fldencounterval from tblencounter where fldpatientval in(select fldpatientval from tblpatientinfo where fldptsex like '%' and fldptbirday like '%' and DATEDIFF(tblpatientexam.fldtime, tblpatientinfo.fldptbirday)>=0 and DATEDIFF(tblpatientexam.fldtime, tblpatientinfo.fldptbirday)<12775)) and fldid in(select fldtestid from tblpatradiosubtest where fldsubtest like 12775 and fldreport IS NULL)
	        // dd($resul t);
        }catch(\Exception $e){
        	dd($e);
        }


    }

    // exportRadiologyReport

    public function exportRadiologyReport(Request $request){
        $fromdate = Helpers::dateNepToEng($request->get('radiology_from_date'));
        $todate = Helpers::dateNepToEng($request->get('radiology_to_date'));
        $fdate = $fromdate->year.'-'.$fromdate->month.'-'.$fromdate->date;
        $tdate = $todate->year.'-'.$todate->month.'-'.$todate->date;
        // $fdate = $request->get('radiology_from_date');
        // $tdate = $request->get('radiology_to_date');
        $evaluation = $request->get('radiology_evaluation');
        $section = $request->get('radiology_section');
        $gender = $request->get('radiology_gender');
        $fage = $request->get('radiology_age_from');
        $tage = $request->get('radiology_age_to');
        $exam = $request->get('radiology_exam');
        $sexam = $request->get('radiology_sub_exam');
        $ttype = $request->get('rtest_normal_type');
        $sttype = $request->get('rstest_normal_type');
        $extext = $request->get('radiology_ex_text');
        $agefrom = $fage * 365;
        $ageto   = $tage * 365;
        $status = $request->get('radiology_status');
        $txtsearch = $request->get('enable_txtSearch');
        if($status == 'Reported' || $status == 'CheckIn' || $status == 'Ordered' || $status == 'Appointment'){
            $comparecolumn = 'fldtime_report';
        }else{
            $comparecolumn = 'fldtime_verify';
        }

        try{


            if($gender !=''){
                $genderquery = "and prt.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."'))";
            }else{
                $genderquery = '';
            }
            // echo $section; exit;
            if($section !='%'){
                $sectionquery = "and prt.fldtestid in(select r.fldexamid from tblradio as r where r.fldcategory like '".$section."')";
            }else{
                $sectionquery = '';
            }

            if($evaluation !='%'){
                $evaluationquery = "and prt.fldsampletype like '".$evaluation."'";
            }else{
                $evaluationquery = '';
            }

            if($ttype !='%'){
                $abnormalquery = "and prt.fldabnormal='".$ttype."'";
            }else{
                $abnormalquery = '';
            }
            if($extext !=''){
                $searchquery = "and lower(prst.fldreport) like '".$extext."'";
            }else{
                $searchquery = '';
            }

            if($sttype !='%'){
                $sabnormalquery = "and prst.fldabnormal='".$sttype."'";
            }else{
                $sabnormalquery = '';
            }
            if($sexam !='%'){
                $sexamquery = " and prt.fldid in(select prst.fldtestid from tblpatradiosubtest as prst where prst.fldsubtest like '".$sexam."' ".$searchquery." ".$sabnormalquery.")";
            }else{
                $sexamquery = '';
            }

            if($exam !='%'){
                $examquery = "and prt.fldtestid LIKE '".$exam."'";
            }else{
                $examquery = '';
            }
            if($status == 'CheckIn' || $status == 'Appointment' || $status == 'Ordered'){

                $statusquery = "and prt.fldstatus='".$status."'";

            }else{
                $statusquery = "";
            }
            if($txtsearch  == 1){
                $sql = "select prt.fldid, prt.fldencounterval, prt.fldtestid, prt.".$comparecolumn.", prt.fldstatus, prt.fldtest_type from tblpatradiotest as prt where prt.".$comparecolumn." >= '".$fdate."' and prt.".$comparecolumn." <='".$tdate."' ".$examquery." ".$genderquery." ".$sectionquery." ".$evaluationquery." ".$abnormalquery." ".$statusquery." and prt.fldreportquali IS NULL";
            }else{
                $sql = "select prt.fldid, prt.fldencounterval, prt.fldtestid, prt.".$comparecolumn.", prt.fldstatus, prt.fldtest_type from tblpatradiotest as prt where prt.".$comparecolumn." >= '".$fdate."' and prt.".$comparecolumn." <='".$tdate."' ".$examquery." ".$genderquery." ".$sectionquery." ".$evaluationquery." ".$abnormalquery." ".$sexamquery." ".$statusquery."";
            }


            // echo $sql; exit;
            $result  = DB::select(
                   $sql
                );
            $data['from_date'] = $fdate;
            $data['to_date'] = $tdate;
            $data['result'] = $result;
            $data['status'] = $status;
            $data['comparecolumn'] = $comparecolumn;
            return view('consultdiagnostic::pdf.radiology-report', $data)/*->stream('radiology-report.pdf')*/;
        }catch(\Exception $e){
            dd($e);
        }
    }


}
