<?php

namespace Modules\ConsultDiagnostic\Http\Controllers;

use App\Exam;
use App\PatientExam;
use App\Encounter;
use App\PatientInfo;
use App\ExamQuali;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Utils\Permission;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ExaminationReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayExaminationForm()
    {
        if (Permission::checkPermissionFrontendAdmin('examination-report')) {
            Helpers::jobRecord('fmExamAll', 'Examination Report');
            $data['comp'] = Helpers::getCompName();
            $data['exams'] = Exam::select('fldexamid')->where('fldtype', 'LIKE', '%')->get();
            return view('consultdiagnostic::examination', $data);
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
    public function listExamByCat(Request $request)
    {
        $type = $request->section;
        // echo $type; exit;
        $result = PatientExam::select('fldhead')->where('fldinput', 'LIKE', $type)->where('fldsave', 1)->distinct()->get();
        $html = '<option value="%">%</option>';
        if (isset($result) and count($result) > 0) {
            foreach ($result as $r) {
                $html .= '<option value="' . $r->fldhead . '">' . $r->fldhead . '</option>';
            }
        }
        echo $html;
        exit;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function listSubExam(Request $request)
    {
        $exam = $request->exam;
        // echo $type; exit;
        $result = ExamQuali::select('fldsubexam')->where('fldexamid', 'LIKE', $exam)->get();
        $html = '<option value="%">%</option>';
        if (isset($result) and count($result) > 0) {
            foreach ($result as $r) {
                $html .= '<option value="' . $r->fldhead . '">' . $r->fldhead . '</option>';
            }
        }
        echo $html;
        exit;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchExam(Request $request)
    {
        // echo "here"; exit;
        $html = '';
        $fdate = $request->fdate;
        $tdate = $request->tdate;
        $comp = $request->comp;
        $section = $request->section;
        $gender = $request->gender;
        $fage = $request->fage;
        $tage = $request->tage;
        $exam = $request->exam;
        $sexam = $request->sexam;
        $ntype = $request->ntype;
        $extext = $request->extext;
        $agefrom = $fage ?? 0 * 365;
        $ageto = $tage ?? 0 * 365;
        $searchtext = $request->txtsearch;
        // echo $section; exit;
        // echo $fdate
        try {
            if ($searchtext == 1) {

                if ($sexam != '' && $extext != '') {
                    if ($fage != '' and $tage != '') {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "' and p.fldptbirday like '%' and DATEDIFF(t.fldtime, p.fldptbirday)>=" . $agefrom . " and DATEDIFF(t.fldtime, p.fldptbirday)<" . $ageto . ")) and t.fldid in(select pr.fldtestid from tblpatradiosubtest as pr where pr.fldsubtest like '" . $sexam . "' and lower(pr.fldreport) like '" . $extext . "')"
                        );
                    } else {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "')) and t.fldid in(select pr.fldtestid from tblpatradiosubtest as pr where pr.fldsubtest like '" . $sexam . "' and lower(pr.fldreport) like '" . $extext . "')"
                        );
                    }

                } else {
                    if ($fage != '' and $tage != '') {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "' and p.fldptbirday like '%' and DATEDIFF(t.fldtime, p.fldptbirday)>=" . $agefrom . " and DATEDIFF(t.fldtime, p.fldptbirday)<" . $ageto . "))"
                        );
                    } else {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "'))"
                        );
                    }

                }

            } else {

                if ($sexam != '') {
                    // echo "if ma"; exit;
                    if ($fage != '' and $tage != '') {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval  from tblpatientinfo as p where p.fldptsex like '" . $gender . "' and p.fldptbirday like '%' and DATEDIFF(t.fldtime, p.fldptbirday)>=" . $agefrom . " and DATEDIFF(t.fldtime, p.fldptbirday)<" . $ageto . ")) and t.fldid in(select pr.fldtestid from tblpatradiosubtest as pr where pr.fldsubtest like '" . $sexam . "')"
                        );
                    } else {
                        // echo "if ko else ma"; exit;
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "')) and t.fldid in(select pr.fldtestid from tblpatradiosubtest as pr where pr.fldsubtest like '" . $sexam . "' )"
                        );
                    }

                } else {
                    // echo "else ko else ma"; exit;
                    if ($fage != '' and $tage != '') {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval  from tblpatientinfo as p where p.fldptsex like '" . $gender . "' and p.fldptbirday like '%' and DATEDIFF(t.fldtime, p.fldptbirday)>=" . $agefrom . " and DATEDIFF(t.fldtime, p.fldptbirday)<" . $ageto . "))"
                        );
                    } else {
                        // echo
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval  from tblpatientinfo as p where p.fldptsex like '" . $gender . "'))"
                        );
                    }

                }

            }

            if (isset($result) and count($result) > 0) {
                foreach ($result as $k => $data) {
                    $sn = $k + 1;
                    $encounter = Encounter::where('fldencounterval', $data->fldencounterval)->first();
                    $patientdata = PatientInfo::where('fldpatientval', $encounter->fldpatientval)->first();
                    // $age = '';
                    $abnormalhtml = '';
                    $bday = $patientdata->fldptbirday;
                    $diff = (date('Y') - date('Y', strtotime($bday)));
                    $age = $diff;

                    if ($data->fldabnormal == 0) {
                        $abnormalhtml = '<i style="color:green" class="fas fa-square"></i>';
                    } elseif ($data->fldabnormal == 1) {
                        $abnormalhtml = '<i style="color:red" class="fas fa-square"></i>';
                    } else {
                        $abnormalhtml = '';
                    }

                    $html .= '<tr>';
                    $html .= '<td>' . $sn . '</td>';
                    $html .= '<td>' . $data->fldencounterval . '</td>';
                    $user_rank = ((Options::get('system_patient_rank') == 1) && isset($encounter) && isset($encounter->fldrank)) ? $encounter->fldrank : '';
                    $html .= '<td>' . $user_rank . ' ' . $patientdata->fldptnamefir . ' ' . $patientdata->fldmidname . ' ' . $patientdata->fldptnamelast . '</td>';
                    $html .= '<td>' . $age . 'Yr' . '</td>';
                    $html .= '<td>' . $patientdata->fldptsex . '</td>';
                    $html .= '<td>' . $patientdata->fldpatientval . '</td>';
                    $html .= '<td>' . $encounter->fldregdate . '</td>';
                    $html .= '<td>' . $encounter->fldcomp . '</td>';
                    $html .= '<td>' . $data->fldhead . '</td>';
                    $html .= '<td>' . $abnormalhtml . '</td>';
                    $html .= '</tr>';
                }
            } else {
                $html .= '<tr><td colspan="11">No Data Available</td></tr>';
            }
            return $html;
        } catch (\Exception $e) {
            dd($e);
        }


    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function exportExamReport(Request $request)
    {
        // echo "here"; exit;
        // dd($request->all());
        $html = '';
        $fdate = $request->get('from_date');
        $tdate = $request->get('to_date');
        $comp = $request->get('ex_comp');
        $section = $request->get('section');
        $gender = $request->get('gender');
        $fage = $request->get('exam_age_from');
        $tage = $request->get('exam_age_to');
        $exam = $request->get('diagnostic_exam');
        $sexamvalue = $request->get('diagnostic_sub_exam');
        $sexam = (isset($sexamvalue)) ? $request->get('diagnostic_sub_exam') : '';
        $ntype = $request->get('normal_type');
        $extext = $request->get('ex_text');
        $agefrom = $fage * 365;
        $ageto = $tage * 365;
        $searhcVal = $request->get('enable_txtSearch');
        $searchtext = (isset($searhcVal)) ? $request->get('enable_txtSearch') : '';
        // echo $section; exit;
        // echo $fdate
        try {
            if ($searchtext == 1) {

                if ($sexam != '' && $extext != '') {
                    if ($fage != '' and $tage != '') {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "' and p.fldptbirday like '%' and DATEDIFF(t.fldtime, p.fldptbirday)>=" . $agefrom . " and DATEDIFF(t.fldtime, p.fldptbirday)<" . $ageto . ")) and t.fldid in(select pr.fldtestid from tblpatradiosubtest as pr where pr.fldsubtest like '" . $sexam . "' and lower(pr.fldreport) like '" . $extext . "')"
                        );
                    } else {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "')) and t.fldid in(select pr.fldtestid from tblpatradiosubtest as pr where pr.fldsubtest like '" . $sexam . "' and lower(pr.fldreport) like '" . $extext . "')"
                        );
                    }

                } else {
                    if ($fage != '' and $tage != '') {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "' and p.fldptbirday like '%' and DATEDIFF(t.fldtime, p.fldptbirday)>=" . $agefrom . " and DATEDIFF(t.fldtime, p.fldptbirday)<" . $ageto . "))"
                        );
                    } else {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "'))"
                        );
                    }

                }

            } else {

                if ($sexam != '') {
                    // echo "if ma"; exit;
                    if ($fage != '' and $tage != '') {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval  from tblpatientinfo as p where p.fldptsex like '" . $gender . "' and p.fldptbirday like '%' and DATEDIFF(t.fldtime, p.fldptbirday)>=" . $agefrom . " and DATEDIFF(t.fldtime, p.fldptbirday)<" . $ageto . ")) and t.fldid in(select pr.fldtestid from tblpatradiosubtest as pr where pr.fldsubtest like '" . $sexam . "')"
                        );
                    } else {
                        // echo "if ko else ma"; exit;
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval from tblpatientinfo as p where p.fldptsex like '" . $gender . "')) and t.fldid in(select pr.fldtestid from tblpatradiosubtest as pr where pr.fldsubtest like '" . $sexam . "' )"
                        );
                    }

                } else {
                    // echo "else ko else ma"; exit;
                    if ($fage != '' and $tage != '') {
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval  from tblpatientinfo as p where p.fldptsex like '" . $gender . "' and p.fldptbirday like '%' and DATEDIFF(t.fldtime, p.fldptbirday)>=" . $agefrom . " and DATEDIFF(t.fldtime, p.fldptbirday)<" . $ageto . "))"
                        );
                    } else {
                        // echo
                        $result = DB::select(
                            "select t.fldid,t.fldencounterval,t.fldhead,t.fldtime,t.fldcomp,t.fldtype,t.fldabnormal from tblpatientexam as t where t.fldtime>='" . $fdate . "' and t.fldtime<='" . $tdate . "' and t.fldhead like '" . $exam . "' and t.fldinput like '" . $section . "' and t.fldabnormal like '" . $ntype . "' and t.fldcomp like '" . $comp . "' and t.fldsave='1' and t.fldencounterval in(select e.fldencounterval from tblencounter as e where e.fldpatientval in(select p.fldpatientval  from tblpatientinfo as p where p.fldptsex like '" . $gender . "'))"
                        );
                    }

                }

            }
            // dd($result);
            $data['from'] = $request->get('from_date');
            $data['to'] = $request->get('to_date');

            $data['result'] = $result;

            return view('consultdiagnostic::pdf.examination-report', $data)/*->setPaper('a4')->stream('examination-report.pdf')*/ ;

        } catch (\Exception $e) {
            dd($e);
        }
    }

}
