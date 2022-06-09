<?php

namespace Modules\ConsultDiagnostic\Http\Controllers;

use App\Exam;
use App\PatientExam;
use App\ExamQuali;
use App\Pathocategory;
use App\Sampletype;
use App\TestMethod;
use App\Test;
use App\TestQuali;
use App\PatientInfo;
use App\Encounter;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class LaboratoryReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayLaboratoryForm()
    {
    	Helpers::jobRecord('fmSampReport', 'Laboratory Report');
    	$data['comp'] = Helpers::getCompName();
        $data['sectionlist'] = Pathocategory::select('flclass')->where('fldcategory','LIKE','test')->get();
        $data['specimen'] = Sampletype::select('fldsampletype')->get();
        $data['methods'] = TestMethod::select('fldmethod')->where('fldcateg','LIKE','Test')->orderby('fldmethod','ASC')->get();
        $data['tests'] = Test::select('fldtestid')->where('fldtype','LIKE','%')->orderby('fldtestid','ASC')->get();
        $data['subtests'] = TestQuali::select('fldsubtest')->where('fldtestid','%')->orderby('fldsubtest','ASC')->distinct()->get();
    	$data['exams'] = Exam::select('fldexamid')->where('fldtype','LIKE', '%')->get();
        $currentdate = Helpers::dateEngToNepdash(date('Y-m-d'));
        
        $data['date'] = $currentdate->year.'-'.$currentdate->month.'-'.$currentdate->date;
        // dd($data);
        return view('consultdiagnostic::laboratory', $data);
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listTestByCat(Request $request)
    {
    	$type = $request->section;
    	// select fldtestid from tbltest where fldcategory like 'BIOCHEMISTRY' and fldtype like '%'
    	$result = Test::select('fldtestid')->where('fldcategory','LIKE',$type)->where('fldtype','LIKE','%')->orderby('fldtestid','ASC')->get();
        // echo count($result); exit;
    	$html ='<option value="%">%</option>';
    	if(isset($result) and count($result) > 0){
    		foreach($result as $r){
    			$html .='<option value="'.$r->fldtestid.'">'.$r->fldtestid.'</option>';
    		}
    	}
    	echo $html; exit;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listSubTest(Request $request)
    {
        
    	$test = $request->test;

    	$result = TestQuali::select('fldsubtest')->where('fldtestid',$test)->orderby('fldsubtest','ASC')->distinct()->get();
    	$html ='<option value="%">%</option>';
    	if(isset($result) and count($result) > 0){
    		foreach($result as $r){
    			$html .='<option value="'.$r->fldsubtest.'">'.$r->fldsubtest.'</option>';
    		}
    	}
    	echo $html; exit;
    }

    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchLabTest(Request $request)
    {
        // dd($request->all());
    	// echo $request->gender; exit;
        $html = '';
        $fromdate = Helpers::dateNepToEng($request->fdate);
        $todate = Helpers::dateNepToEng($request->tdate);
    	$fdate = $fromdate->year.'-'.$fromdate->month.'-'.$fromdate->date;
    	$tdate = $todate->year.'-'.$todate->month.'-'.$todate->date;
        
    	$method = $request->method;
    	$section = $request->section;
    	$gender = $request->gender;
    	$fage = $request->fage;
    	$tage = $request->tage;
    	$test = $request->test;
    	$stest = $request->stest;
    	$ttype = $request->ttype;
        $sttype = $request->sttype;
        $status = $request->status;
        // echo $status; exit;
        // $condition = ($request->condition == '') ? '%' : $request->condition;
        $condition = $request->condition;

        $specimen = $request->specimen;
    	// $extext = ($request->extext == '') ? '%' : $request->extext;
        $extext = $request->extext;
    	
        $agefrom = $fage * 365;
        $ageto   = $tage * 365;
        // echo $section; exit;
        $searchtext = $request->txSearch;
        if($status == 'Sampled'){
            $comparecolumn = 'fldtime_sample';
            $statusquery = 'and pl.fldstatus="Sampled"';
        }elseif($status == 'Verified'){
            $comparecolumn = 'fldtime_verify';
            $statusquery = 'and pl.fldstatus="Verified"';
        }elseif($status == 'Reported'){
            $comparecolumn = 'fldtime_report';
            $statusquery = 'and pl.fldstatus="Reported"';
        }elseif($status == 'Not Done'){
            $comparecolumn = "fldtime_report";
            $statusquery = 'and pl.fldstatus="Not Done"';
        }else{
            $statusquery = '';
            $comparecolumn = '';
        }

        try{
            
            if($fage !=0){
                $agequery = "and fldptbirday like '%' and DATEDIFF(pl.fldtime_sample,p.fldptbirday)>=".$agefrom." and DATEDIFF(pl.fldtime_sample,p.fldptbirday)<".$ageto."";
            }else{
                $agequery = '';
            }

            if($condition !=''){
                $conditionquery = "and lower(pl.fldcondition) like '".$condition."'";
            }else{
                $conditionquery = '';
            }

            
            // echo $section; exit;
            if($section !='%'){
                $sectionquery = "and pl.fldtestid in(select t.fldtestid from tbltest as t where t.fldcategory like '".$section."')";
            }else{
                $sectionquery = "";
            }

            if($stest !='%'){
                if($extext !=''){
                    $searchtextquery = "and lower(pst.fldreport) like '".$extext."'";
                }else{
                    $searchtextquery = '';
                }
                $stestquery = "and pl.fldid in(select pst.fldtestid from tblpatlabsubtest as pst where pst.fldsubtest like '".$stest."' ".$searchtextquery." and pst.fldabnormal='".$sttype."')";
            }else{
                if($extext !=''){
                    $searchtextquery = "and lower(pl.fldreportquali) like '".$extext."'";
                }else{
                    $searchtextquery = '';
                }
                $stestquery = "";
            }
            // echo $sectionquery; exit;

        	if($searchtext == 1){
                // echo "here"; exit;
                if($stest !='%'){
                    // $sql = "select pl.fldid,pl.fldencounterval,pl.fldtestid,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' ".$stestquery." and pl.fldreportquali IS NULL";
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' ".$searchtextquery." ";


                }else if($test !='%') {
                    // $sql = "select pl.fldid,pl.fldencounterval,pl.fldtestid,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' ".$stestquery." and pl.fldreportquali IS NULL"; 
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' ".$searchtextquery.""; 
                    // echo $sql; exit;
                }else if($section !='%'){
                    // $sql = "select pl.fldid,pl.fldencounterval,pl.fldtestid, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' and pl.fldreportquali IS NULL";
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' ";
                }else{
                    if($agefrom != 0){
                        // $sql = "select pl.fldid,pl.fldencounterval,pl.fldtestid, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' and fldptbirday like '%' and DATEDIFF(pl.fldtime_sample,p.fldptbirday)>=".$agefrom." and DATEDIFF(pl.fldtime_sample,p.fldptbirday)<".$ageto.")) ".$sectionquery." ".$conditionquery." and pl.fldreportquali IS NULL";
                        $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' and fldptbirday like '%' and DATEDIFF(pl.fldtime_sample,p.fldptbirday)>=".$agefrom." and DATEDIFF(pl.fldtime_sample,p.fldptbirday)<".$ageto.")) ".$sectionquery." ".$conditionquery." ";
                    }else{
                        // $sql = "select pl.fldid,pl.fldencounterval,pl.fldtestid, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."')) ".$sectionquery." ".$conditionquery." and pl.fldreportquali IS NULL";
                        $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."')) ".$sectionquery." ".$conditionquery."";
                    }
                    
                }
            }else{
                

                if($stest !='%'){
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' and fldptbirday like '%' ".$agequery.")) ".$sectionquery."  ".$conditionquery." and pl.fldabnormal='".$ttype."'".$stestquery;
                }elseif($test !='%'){
                    // if($extext !=''){
                    //      $searchtextquery = "and lower(pl.fldreportquali) like '".$extext."'";
                    // }else{
                    //     $searchtextquery = '';
                    // }
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." ".$searchtextquery." and pl.fldabnormal='".$ttype."'";
                    // select fldid,fldencounterval,fldencounterval,fldid,fldencounterval,fldtestid,fldsampleid,fldsampletype,fldtime_report,fldstatus,fldid,fldtest_type,fldid,fldabnormal from tblpatlabtest where fldtime_report>='2019-07-26 00:00:00' and fldtime_report<='2020-07-05 23:59:59.999' and fldtestid like 'Total Leucocytes Count' and lower(fldsampletype) like 'blood' and fldmethod like '%' and fldencounterval in(select fldencounterval from tblencounter where fldpatientval in(select fldpatientval from tblpatientinfo where fldptsex like 'Male'))
                    // echo $sql; exit;
                }elseif($section !='%'){
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' and fldptbirday like '%' ".$agequery.")) and pl.fldtestid in(select t.fldtestid from tbltest as t where t.fldcategory like '".$section."')";
                }else{
                    if($agefrom !=0){
                        $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' and fldptbirday like '%' and DATEDIFF(pl.fldtime_sample,p.fldptbirday)>=".$agefrom." and DATEDIFF(pl.fldtime_sample,p.fldptbirday)<".$ageto." ))";

                    }else{
                        // echo "hewrfesd"; exit;
                        $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ))";
                    }
                    

                    
                }
                
            }
           
            $finalsql = $sql.$statusquery." GROUP BY pl.fldencounterval";
            // echo $finalsql; exit;
            // $testSQL = $sql
            // echo $sql; exit;
            // $sql = "select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '%'";
            // $result1 = DB::select($sql);
            // print_r($result1); exit;
             $result = DB::select(
                   $finalsql
                );
             // dd($result);
             if(isset($result) and count($result) > 0){
                foreach($result as $k=>$r){
                    
                    $encounter = Encounter::where('fldencounterval', $r->fldencounterval)->first();
                    $patient = PatientInfo::where('fldpatientval',$encounter->fldpatientval)->first();
                    $bday = $patient->fldptbirday;
                    $diff = (date('Y') - date('Y',strtotime($bday)));
                    $age = $diff;
                    $sn = $k+1;
                    $html .='<tr>';
                    $html .='<td>'.$sn.'</td>';
                    $html .='<td>'.$r->fldencounterval.'</td>';
                    $user_rank = ((Options::get('system_patient_rank') == 1) && isset($encounter) && isset($encounter->fldrank)) ? $encounter->fldrank : '';
                    $html .= '<td>' . $user_rank . ' ' . $patient->fldptnamefir . ' ' . $patient->fldmidname . ' ' . $patient->fldptnamelast . '</td>';
                    $html .='<td>'.$age.'Yr'.'</td>';
                    $html .='<td>'.$patient->fldptsex.'</td>';
                    
                     $html .='<td>'.$r->tests.'</td>';
                    $html .='<td>'.$r->fldsampleid.'</td>';
                    $html .='<td>'.$r->fldsampletype.'</td>';
                    $html .='<td>'.$r->$comparecolumn.'</td>';

                }
             }else{
                $html .= "<tr><td colspan='9'>No Data Found</td></tr>";
             }
             echo $html; exit;
        }catch(\Exception $e){
        	dd($e);
        }
        
    	
    }

    public function exportLabReport(Request $request){
        $html = '';
        // dd($request->all());
        $fromdate = Helpers::dateNepToEng($request->lab_from_date);
        $todate = Helpers::dateNepToEng($request->lab_to_date);
        $fdate = $fromdate->year.'-'.$fromdate->month.'-'.$fromdate->date;
        $tdate = $todate->year.'-'.$todate->month.'-'.$todate->date;
        $method = $request->get('lab_method');
        $section = $request->get('lab_section');
        $gender = $request->get('lab_gender');
        $fage = $request->get('lab_age_from');
        $tage = $request->get('lab_age_to');
        $test = $request->get('lab_test');
        $stest = $request->get('lab_sub_test');
        $ttype = $request->get('test_normal_type');
        $sttype = $request->get('subtest_normal_type');
        $status = $request->get('status');
        // $condition = ($request->condition == '') ? '%' : $request->condition;
        $condition = $request->get('lab_condition');

        $specimen = $request->get('lab_specimen');
        // $extext = ($request->extext == '') ? '%' : $request->extext;
        $extext = $request->get('lab_ex_text');
        
        $agefrom = $fage * 365;
        $ageto   = $tage * 365;
        // echo $section; exit;
        $searchtext = $request->get('enable_txtSearch');
        if($status == 'Sampled'){
            $comparecolumn = 'fldtime_sample';
        }elseif($status == 'Verified'){
            $comparecolumn = 'fldtime_verify';
        }else{
            $comparecolumn = 'fldtime_report';
        }
        try{
            
            if($fage !=0){
                $agequery = "and fldptbirday like '%' and DATEDIFF(pl.fldtime_sample,p.fldptbirday)>=".$agefrom." and DATEDIFF(pl.fldtime_sample,p.fldptbirday)<".$ageto."";
            }else{
                $agequery = '';
            }

            if($condition !=''){
                $conditionquery = "and lower(pl.fldcondition) like '".$condition."'";
            }else{
                $conditionquery = '';
            }

            
            // echo $section; exit;
            if($section !='%'){
                $sectionquery = "and pl.fldtestid in(select t.fldtestid from tbltest as t where t.fldcategory like '".$section."')";
            }else{
                $sectionquery = "";
            }

            if($stest !='%'){
                if($extext !=''){
                    $searchtextquery = "and lower(pst.fldreport) like '".$extext."'";
                }else{
                    $searchtextquery = '';
                }
                $stestquery = "and pl.fldid in(select pst.fldtestid from tblpatlabsubtest as pst where pst.fldsubtest like '".$stest."' ".$searchtextquery." and pst.fldabnormal='".$sttype."')";
            }else{
                if($extext !=''){
                    $searchtextquery = "and lower(pl.fldreportquali) like '".$extext."'";
                }else{
                    $searchtextquery = '';
                }
                $stestquery = "";
            }
            // echo $sectionquery; exit;

            if($searchtext == 1){
                // echo "here"; exit;
                if($stest !='%'){
                    // $sql = "select pl.fldid,pl.fldencounterval,pl.fldtestid,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' ".$stestquery." and pl.fldreportquali IS NULL";
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' ".$searchtextquery." ";


                }else if($test !='%') {
                    // $sql = "select pl.fldid,pl.fldencounterval,pl.fldtestid,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' ".$stestquery." and pl.fldreportquali IS NULL"; 
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' ".$searchtextquery.""; 
                    // echo $sql; exit;
                }else if($section !='%'){
                    // $sql = "select pl.fldid,pl.fldencounterval,pl.fldtestid, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' and pl.fldreportquali IS NULL";
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." and pl.fldabnormal='".$ttype."' ";
                }else{
                    if($agefrom != 0){
                        // $sql = "select pl.fldid,pl.fldencounterval,pl.fldtestid, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' and fldptbirday like '%' and DATEDIFF(pl.fldtime_sample,p.fldptbirday)>=".$agefrom." and DATEDIFF(pl.fldtime_sample,p.fldptbirday)<".$ageto.")) ".$sectionquery." ".$conditionquery." and pl.fldreportquali IS NULL";
                        $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' and fldptbirday like '%' and DATEDIFF(pl.fldtime_sample,p.fldptbirday)>=".$agefrom." and DATEDIFF(pl.fldtime_sample,p.fldptbirday)<".$ageto.")) ".$sectionquery." ".$conditionquery." ";
                    }else{
                        // $sql = "select pl.fldid,pl.fldencounterval,pl.fldtestid, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."')) ".$sectionquery." ".$conditionquery." and pl.fldreportquali IS NULL";
                        $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests, pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."')) ".$sectionquery." ".$conditionquery."";
                    }
                    
                }
            }else{
                

                if($stest !='%'){
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' and fldptbirday like '%' ".$agequery.")) ".$sectionquery."  ".$conditionquery." and pl.fldabnormal='".$ttype."' ".$stestquery." and pst.fldabnormal='".$sttype."')";
                }elseif($test !='%'){
                    // if($extext !=''){
                    //      $searchtextquery = "and lower(pl.fldreportquali) like '".$extext."'";
                    // }else{
                    //     $searchtextquery = '';
                    // }
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ".$agequery.")) ".$sectionquery." ".$conditionquery." ".$searchtextquery." and pl.fldabnormal='".$ttype."'";
                    // select fldid,fldencounterval,fldencounterval,fldid,fldencounterval,fldtestid,fldsampleid,fldsampletype,fldtime_report,fldstatus,fldid,fldtest_type,fldid,fldabnormal from tblpatlabtest where fldtime_report>='2019-07-26 00:00:00' and fldtime_report<='2020-07-05 23:59:59.999' and fldtestid like 'Total Leucocytes Count' and lower(fldsampletype) like 'blood' and fldmethod like '%' and fldencounterval in(select fldencounterval from tblencounter where fldpatientval in(select fldpatientval from tblpatientinfo where fldptsex like 'Male'))
                    // echo $sql; exit;
                }elseif($section !='%'){
                    $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' and fldptbirday like '%' ".$agequery.")) and pl.fldtestid in(select t.fldtestid from tbltest as t where t.fldcategory like '".$section."')";
                }else{
                    if($agefrom !=0){
                        $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' and fldptbirday like '%' and DATEDIFF(pl.fldtime_sample,p.fldptbirday)>=".$agefrom." and DATEDIFF(pl.fldtime_sample,p.fldptbirday)<".$ageto." ))";

                    }else{
                        // echo "hewrfesd"; exit;
                        $sql = "select pl.fldid,pl.fldencounterval,group_concat(pl.fldtestid) as tests,pl.fldsampleid,pl.fldsampletype,pl.".$comparecolumn.",pl.fldstatus,pl.fldtest_type,pl.fldabnormal from tblpatlabtest as pl where pl.".$comparecolumn.">='".$fdate."' and pl.".$comparecolumn."<='".$tdate."' and pl.fldtestid like '".$test."' and lower(pl.fldsampletype) like '".$specimen."' and pl.fldmethod like '".$method."' and pl.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '".$gender."' ))";
                    }
                    

                    
                }
                
            }
            $finalsql = $sql."GROUP BY pl.fldencounterval";
            // echo $sql; exit;
             $result  = DB::select(
                   $finalsql
                );
             $data['result'] = $result;
             $data['from_date'] = $request->get('lab_from_date');
             $data['to_date'] = $request->get('lab_to_date');
             $data['comparecolumn'] = $comparecolumn;
             return view('consultdiagnostic::pdf.laboratory-report', $data)/*->setPaper('a4')->stream('laboratory-report.pdf')*/;
            
        }catch(\Exception $e){
            dd($e);
        }
    }

    private function getLabDischargeData(Request $request, $appendData = FALSE)
    {
        $fromdate = $request->get('from_date') ? Helpers::dateNepToEng($request->get('from_date'))->full_date : date('Y-m-d');
        $todate = $request->get('to_date') ? Helpers::dateNepToEng($request->get('to_date'))->full_date : date('Y-m-d');
        $from_age = $request->get('from_age');
        $to_age = $request->get('to_age');
        $method = $request->get('method');
        $specimen = $request->get('specimen');
        $condition = $request->get('condition');
        $test = $request->get('test');
        $test_type = $request->get('test_type');
        $status = $request->get('status');
        $gender = $request->get('gender');
        $section = $request->get('section');
        $sub_test = $request->get('sub_test');
        
        $patient_name = $request->get('patient_name');
        $patient_no = $request->get('patient_no');
        $encounter_no = $request->get('encounter_no');
        $sample_no = $request->get('sample_no');
        $bed_no = $request->get('bed_no');
        $department = $request->get('department');
        $billno = $request->get('bill_no');
        // $sttype = $request->get('sttype');
        $timecolumn = 'tblpatbilling.fldtime';

        // if ($status == 'Sampled')
        //     $timecolumn = 'tblpatlabtest.fldtime_sample';
        // elseif ($status == 'Verified')
        //     $timecolumn = 'tblpatlabtest.fldtime_verify';
        // elseif ($status == 'Reported')
        //     $timecolumn = 'tblpatlabtest.fldtime_report';
        // elseif ($status == 'Not Done')
        //     $timecolumn = "tblpatlabtest.fldtime_report";

        $where = [
            ["tblpatbilling.flditemtype", "Diagnostic Tests"],
            ["{$timecolumn}", ">=", "$fromdate 00:00:00"],
            ["{$timecolumn}", "<=", "$todate 23:59:59.999"],
        ];

        if ($test && $test != '%')
            $where[] = ["tblpatlabtest.fldtestid", $test];
        if ($condition && $condition != '%')
            $where[] = ["tblpatlabtest.fldcondition", $condition];
        if ($specimen && $specimen != '%')
            $where[] = ["tblpatlabtest.fldsampletype", $specimen];
        if ($method && $method != '%')
            $where[] = ["tblpatlabtest.fldmethod", $method];
        if ($test_type && $test_type != '%')
            $where[] = ["tblpatlabtest.fldabnormal", $test_type];
        if ($status && $status != '%')
            $where[] = ["tblpatbilling.fldsample", $status];
        if ($gender && $gender != '%')
            $where[] = ["tblpatientinfo.fldptsex", $gender];
        if ($patient_no)
            $where[] = ["tblencounter.fldpatientval", "like", "%{$patient_no}%"];
        if ($encounter_no)
            $where[] = ["tblencounter.fldencounterval", "like", "%{$encounter_no}%"];
        if ($sample_no)
            $where[] = ["tblpatlabtest.fldsampleid", "like", "%{$sample_no}%"];
        if ($bed_no)
            $where[] = ["tbldepartmentbed.fldbed", "like", "%{$bed_no}%"];
        if ($department)
            $where[] = ["tbldepartmentbed.flddept", "like", "%{$department}%"];

        $data = \App\PatBilling::select("tblpatbilling.fldtime", "tblpatbilling.fldid", "tblpatbilling.fldtime", "tblpatlabtest.fldprint", "tblpatbilling.fldencounterval", "tblgrouptest.fldtestid", "tblpatlabtest.fldsampleid", "tbldepartmentbed.fldbed", "tblpatlabtest.fldsampletype", "tblpatbilling.fldsample", "tblpatlabtest.fldtest_type", "tblpatlabtest.fldabnormal", "tblpatientinfo.fldptnamefir", "tblpatientinfo.fldptnamelast", "tblpatientinfo.fldmidname", "tblpatientinfo.fldptbirday", "tblpatientinfo.fldptsex", DB::raw("coalesce(tblpatbilling.fldbillno, tblpatbilling.fldtempbillno) AS fldbillno"))
            ->join("tblencounter", "tblencounter.fldencounterval", "=", "tblpatbilling.fldencounterval")
            ->join("tblpatientinfo", "tblencounter.fldpatientval", "=", "tblpatientinfo.fldpatientval")
            ->leftJoin("tblgrouptest", "tblpatbilling.flditemname", "=", "tblgrouptest.fldgroupname")
            ->leftJoin("tbldepartmentbed", "tblpatbilling.fldencounterval", "=", "tbldepartmentbed.fldencounterval")
            ->leftJoin("tblpatlabtest", function($join) {
                $join->on("tblpatlabtest.fldtestid", "=", "tblgrouptest.fldtestid")
                    ->on("tblpatlabtest.fldencounterval", "=", "tblpatbilling.fldencounterval");
            })->where($where)
            ->whereNotNull("tblgrouptest.fldtestid")
            ->groupBy("tblpatbilling.fldtime", "tblpatbilling.fldid", "tblpatbilling.fldtime", "tblpatlabtest.fldprint", "tblpatbilling.fldencounterval", "tblgrouptest.fldtestid", "tblpatlabtest.fldsampleid", "tbldepartmentbed.fldbed", "tblpatlabtest.fldsampletype", "tblpatbilling.fldsample", "tblpatlabtest.fldtest_type", "tblpatlabtest.fldabnormal", "tblpatientinfo.fldptnamefir", "tblpatientinfo.fldptnamelast", "tblpatientinfo.fldmidname", "tblpatientinfo.fldptbirday", "tblpatientinfo.fldptsex");

        if ($from_age) {
            $from_age *= 365;
            $data = $data->whereRaw("DATEDIFF(tblpatbilling.fldtime,tblpatientinfo.fldptbirday)>={$from_age}");
        }
        if ($to_age) {
            $to_age *= 365;
            $data = $data->whereRaw("DATEDIFF(tblpatbilling.fldtime,tblpatientinfo.fldptbirday)<{$to_age}");
        }
        if ($section && $section != '%')
            $data = $data->whereRaw("tblpatlabtest.fldtestid in (select t.fldtestid from tbltest as t where t.fldcategory like '{$section}')");
        if ($sub_test && $sub_test != '%')
            $data = $data->whereRaw("tblpatlabtest.fldid in (select pst.fldtestid from tblpatlabsubtest as pst where pst.fldsubtest like '{$sub_test}')");
        if ($patient_name)
            $data = $data->where(\DB::raw('CONCAT_WS(" ", tblpatientinfo.fldptnamefir, tblpatientinfo.fldmidname, tblpatientinfo.fldptnamelast)'), 'like', "%{$patient_name}%");
        if ($billno)
            $data = $data->where(function($query) use ($billno) {
                $query->orWhere("tblpatbilling.fldbillno", $billno)->orWhere("tblpatbilling.fldtempbillno", $billno);
            });

        if ($patient_name)
            $data = $data->where(\DB::raw('CONCAT_WS(" ", fldptnamefir, fldmidname, fldptnamelast)'), 'like', "%{$patient_name}%");

        $data = $data->get();
        if ($appendData == FALSE)
            return $data;
        
        return [
            'data' => $data,
            'fromdate' => $fromdate,
            'todate' => $todate,
            'section' => $section ?: '%',
        ];
    }

    public function displayLabDischargeForm(Request $request)
    {
    	Helpers::jobRecord('fmSampReport', 'Laboratory Discharge Report');
        return view('consultdiagnostic::lab-discharge', [
            'comp' => Helpers::getCompName(),
            'sectionlist' => Pathocategory::select('flclass')->where('fldcategory','LIKE','test')->get(),
            'specimen' => Sampletype::select('fldsampletype')->get(),
            'methods' => TestMethod::select('fldmethod')->where('fldcateg','LIKE','Test')->orderby('fldmethod','ASC')->get(),
            'tests' => Test::select('fldtestid')->where('fldtype','LIKE','%')->orderby('fldtestid','ASC')->get(),
            'subtests' => TestQuali::select('fldsubtest')->where('fldtestid','%')->orderby('fldsubtest','ASC')->distinct()->get(),
            'exams' => Exam::select('fldexamid')->where('fldtype','LIKE', '%')->get(),
            'date' => Helpers::dateEngToNepdash(date('Y-m-d'))->full_date,
            'departments' => Helpers::getDepartments(),
            'allLabData' => $this->searchLabDischargeForm($request),
        ]);
    }

    public function searchLabDischargeForm(Request $request)
    {
        $data = $this->getLabDischargeData($request);
        $html = '';
        foreach ($data as $index => $d) {
            $name = implode(' ', array_filter([$d->fldptnamefir, $d->fldmidname, $d->fldptnamelast])) . "<br>";
            $name .= "EncId: {$d->fldencounterval}<br>";
            $name .= Helpers::getAgeDetail($d->fldptbirday) . "/" . $d->fldptsex . "<br>";
            $name .= "{$d->fldptcontact}";
            $timecolumn = 'fldtime';
            $html .= "<tr class='tr-green'>";

            if (isset($d->fldsample) && $d->fldsample == 'Verified') {
                $html .= "<tr class='tr-orange' >";
                // $timecolumn = 'fldtime_verify';
            } elseif (isset($d->fldsample) && $d->fldsample == 'Reported') {
                $html .= "<tr class='tr-yellow'>";
                // $timecolumn = 'fldtime_report';
            } elseif (isset($d->fldsample) && $d->fldsample == 'Not Done') {
                $html .= "<tr class='tr-blue'>";
                // $timecolumn = "fldtime_report";
            } elseif (isset($d->fldsample) && $d->fldsample == 'Sampled') {
                $html .= "<tr class='tr-red'>";
                // $timecolumn = 'fldtime_sample';
            }

            $html .= "<td>" . ($index+1) . "</td>";
            $html .= "<td>{$name}</td>";
            $html .= "<td>{$d->fldbed}</td>";
            $html .= "<td>{$d->fldtestid}</td>";
            $html .= "<td>{$d->fldsampleid}</td>";
            $html .= "<td>{$d->fldbillno}</td>";
            $html .= "<td>{$d->fldtime}</td>";
            $html .= "<td>" . ($d->fldprint ? "Y" : "N") . "</td>";
            $html .= "<td>{$d->$timecolumn}</td>";
        }

        if ($request->ajax())
            return response()->json([
                "html" => $html,
            ]);

        return $html;
    }

    public function exportLabDischargeForm(Request $request)
    {
        return view('consultdiagnostic::pdf.discharge-laboratory-report', $this->getLabDischargeData($request, TRUE))/*->setPaper('a4')->stream('laboratory-report.pdf')*/;
    }
   
}
