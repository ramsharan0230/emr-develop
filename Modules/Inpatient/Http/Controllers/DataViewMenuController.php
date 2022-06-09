<?php

namespace Modules\Inpatient\Http\Controllers;

use App\Otchecklist;
use App\Preanaestheticevaluation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DataViewMenuController extends Controller
{
    private function _format_datetime($datetime, $returndatetime = FALSE)
    {
        $datetime = explode(' ', $datetime);
        $englishdate = $datetime[0];
        $nepalidate = \App\Utils\Helpers::dateEngToNep(str_replace('-', '/', $englishdate));
        $nepalidate = "{$nepalidate->year}-{$nepalidate->month}-{$nepalidate->date}";

        $isset_time = isset($datetime[1]);
        $time = ($isset_time) ? substr($datetime[1], 0, -3) : '';
        if ($returndatetime)
            return ($isset_time) ? "$nepalidate $time" : $nepalidate;

        return compact('englishdate', 'nepalidate', 'time');
    }

    private function _get_patient_info($encounter_id)
    {
        return \App\Utils\Helpers::getPatientByEncounterId($encounter_id);
    }

    private function _format_data($all_data, $time_column = 'fldtime')
    {
        $ret_data = [];
        foreach ($all_data as $data) {
            $date_detail = $this->_format_datetime($data->{$time_column});
            $data->time = $date_detail['time'];
            $ret_data[$date_detail['nepalidate']][] = $data;
        }

        return $ret_data;
    }

    public function transitions(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);

        $transitions = \App\PatTiming::select('fldid', 'flditem', 'fldsecondreport', 'fldfirsttime', 'fldfirstuserid', 'fldsecondtime', 'fldseconduserid', 'fldcomment')
            ->whereNotNull('flditem')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldtype' => 'General Services',
                'fldfirstreport' => 'Bed',
                'fldsecondsave' => '1',
            ])->get();
        foreach ($transitions as &$data) {
            $data->duration = round((strtotime($data->fldsecondtime) - strtotime($data->fldfirsttime)) / 3600) . " Hours";
            $data->fldsecondtime = $this->_format_datetime($data->fldsecondtime, TRUE);
            $data->fldfirsttime = $this->_format_datetime($data->fldfirsttime, TRUE);
        }
        return view('inpatient::pdf.transitions', compact('patientinfo', 'transitions'));
    }

    public function symtoms(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);


        $symtoms_raw = \App\ExamGeneral::select('fldid', 'flditem', 'fldreportquali', 'flddetail', 'fldtime')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Presenting Symptoms',
                'fldsave' => '1',
            ])->orderBy('fldtime')->get();
        $symtoms = $this->_format_data($symtoms_raw);

        return view('inpatient::pdf.symtoms', compact('patientinfo', 'symtoms'));
    }

    public function poInputs(Request $request)
    {

        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);

        $foods_raw = \App\ExamGeneral::select('fldid', 'fldencounterval', 'fldtype', 'flditem', 'fldtime', 'fldreportquanti', 'fldreportquali', 'fldfluid', 'fldenergy', 'fldprotein', 'fldsugar', 'fldlipid', 'fldmineral', 'fldfibre', 'fldcalcium', 'fldphosphorous', 'fldiron', 'fldcarotene', 'fldthiamine', 'fldriboflavin', 'fldniacin', 'fldpyridoxine', 'fldfreefolic', 'fldtotalfolic', 'fldvitaminc')
            ->join('tblfoodcontent', 'tblfoodcontent.fldfoodid', '=', 'tblexamgeneral.flditem')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Input Food/Fluid',

            ])->orderBy('fldtime')->get();
        $foods = $this->_format_data($foods_raw);

        return view('inpatient::pdf.poInputs', compact('patientinfo', 'foods'));
    }

    public function exams(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);

        $exams_raw = \App\PatientExam::select('fldid', 'tblpatientexam.fldtype', 'fldtime', 'fldcomp', 'fldmethod', 'fldhead', 'fldrepquanti', 'fldrepquali', 'fldinput', 'fldabnormal', 'tblexam..fldoption')
            ->join('tblexam', 'tblexam.fldexamid', '=', 'tblpatientexam.fldhead')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldsave' => '1',
            ])->orderBy('fldtime')->get();
        $exams = $this->_format_data($exams_raw);

        return view('inpatient::pdf.exams', compact('patientinfo', 'exams'));
    }

    public function laboratory(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);

        $laboratory = \App\PatLabTest::select('fldtime_sample', 'fldid', 'fldsampletype', 'fldtest_type', 'fldmethod', 'fldtestid', 'fldencounterval', 'fldreportquali', 'fldreportquanti', 'fldabnormal', 'fldsampleid', 'flduserid_verify', 'fldcomment', 'fldstatus')
            ->with([
                'testLimit:fldtestid,fldsilow,fldsihigh,fldsiunit',
                'subTest:fldtestid,fldsubtest,fldtanswertype,fldreport,fldabnormal,fldsampleid,fldid',
                'test:fldtestid,fldcategory',
                'subTest.quantity_range',
                'subTest.subtables',
            ])->where([
                'fldencounterval' => $encounter_id,
                'flvisible' => 'Visible',
            ]);
        if (\App\Utils\Options::get('show_verified') == '1')
            $laboratory->where('fldstatus', 'Verified');
        else
            $laboratory->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            });
        $laboratory = $laboratory->orderBy('fldtime_sample')->get();

        // $laboratory->map(function ($lab) {
        //     $lab->answers = \App\PatLabSubTest::select('fldsubtest', 'fldreport', 'fldid', 'fldtestid', 'fldtanswertype')
        //         ->where([
        //             'fldtestid' => $lab->fldid,
        //             'fldsave' => '1',
        //             'fldencounterval' => $lab->fldencounterval,
        //         ])->get();
        // });

        $laboratory = $this->_format_data($laboratory, 'fldtime_sample');
        return view('inpatient::pdf.laboratory', compact('patientinfo', 'laboratory'));
    }

    public function radiology(Request $request)
    {
        // select fldid,fldtest_type,fldtestid,fldtime_report,fldabnormal,fldstatus,fldmethod,fldsampletype from tblpatradiotest where fldsave_report=&1 and fldencounterval=&2 and flvisible=&3 and fldtestid in(select fldexamid from tblradio where fldcategory=&4)", True, encid, "Visible", res1!fldcategory
        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);

        $categories = \DB::select("SELECT DISTINCT(fldcategory) AS fldcategory FROM tblradio WHERE fldexamid IN(SELECT fldtestid FROM tblpatradiotest WHERE fldsave_report=? AND fldencounterval=? AND flvisible=?)", ['1', $encounter_id, 'Visible']);

        $radiologies = [];
        foreach ($categories as $category) {
            $radio = \DB::select("SELECT fldid,fldtest_type,fldtestid,fldtime_report,fldabnormal,fldstatus,fldmethod,fldsampletype FROM tblpatradiotest WHERE fldsave_report=? AND fldencounterval=? AND flvisible=? AND fldtestid IN(SELECT fldexamid FROM tblradio WHERE fldcategory=?)", ['1', $encounter_id, 'Visible', $category->fldcategory]);
            if ($radio) {
                $radiologies[] = $radio[0];
            }
        }

        return view('inpatient::pdf.radiology', compact('patientinfo', 'radiologies'));
    }

    public function diagnosis(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);

        $diagnosis_data = [];
        foreach ($this->_range_generate($patientinfo->fldregdate) as $date) {
            $parent_data = \DB::select("select distinct(fldparent) from tblpathosymp where fldchild in(select flditem from tblexamgeneral where (fldinput=? or fldinput=?) and fldsave=? and fldencounterval=? and fldtime<=?)", ["", "", "1", $encounter_id, $date]);

            $nepalidate = $this->_format_datetime($date);
            $diagnosis_data[$nepalidate['nepalidate']] = [];
            if ($parent_data) {
                foreach ($parent_data as $data) {
                    $diagnosis_data[$date] += \DB::select("select fldchild from tblpathosymp where fldchild in(select flditem from tblexamgeneral where (fldinput=&1 or fldinput=&2) and fldsave=&3 and fldencounterval=&4 and fldtime<=&5) and fldparent=&6", ["", "", "1", $encounter_id, $date, $data->fldparent]);
                }
            }
        }

        return view('inpatient::pdf.diagnosis', compact('patientinfo', 'diagnosis_data'));
    }

    private function _range_generate($from_date, $to_date = '')
    {
        if ($to_date == '')
            $to_date = date('Y-m-d');
        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod(new \DateTime($from_date), $interval, new \DateTime($to_date));

        $ret_data = [];
        foreach ($daterange as $date) {
            $ret_data[] = $date->format("Y-m-d");
        }

        return $ret_data;
    }

    public function notes(Request $request)
    {
        //         Notes

        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);


        $notes_raw = \App\ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldinput' => 'Notes',
            ])->orderBy('fldtime')->get();
        $notes = $this->_format_data($notes_raw);

        return view('inpatient::pdf.notes', compact('patientinfo', 'notes'));
    }

    public function medDosing(Request $request)
    {
        //         Med Dosing

        // res = modDatabase.$syConn.Exec("select fldtime,flddoseno,fldvalue,fldunit from tblnurdosing where fldencounterval=&1 and fldtime>=&2 and fldtime<=&3", encid, modDate.StartSqlDate($dt), modDate.EndSqlDate($dt))
        // For Each res
        // rs = modDatabase.$syConn.Exec("select fldid,fldroute,flditem,flddose,fldfreq,flddays,flditemtype from tblpatdosing where fldid=&1", res!flddoseno)

        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);

        $medDosing_raw = \App\NurseDosing::select('tblnurdosing.fldtime', 'flddoseno', 'fldvalue', 'fldunit', 'tblpatdosing.fldid', 'fldroute', 'flditem', 'flddose', 'tblpatdosing.fldfreq', 'flddays', 'flditemtype')
            ->leftJoin('tblpatdosing', 'tblpatdosing.fldid', '=', 'tblnurdosing.flddoseno')
            ->where([
                'tblnurdosing.fldencounterval' => $encounter_id,
            ])->orderBy('tblnurdosing.fldtime')->get();
        $medDosing = $this->_format_data($medDosing_raw);

        return view('inpatient::pdf.medDosing', compact('patientinfo', 'medDosing'));
    }

    public function progress(Request $request)
    {

        //         Progress
        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);

        $progress_raw = \App\PatPlanning::select('fldid', 'fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan', 'fldencounterval')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldplancategory' => 'IP Monitoring',
            ])->orderBy('fldtime')->get();
        $progress_raw->map(function ($prog) {
            $prog->exams = \App\PatientExam::select('fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti')
                ->where([
                    'fldinput' => "IP Monitoring:{$prog->fldid}",
                    'fldsave' => '1',
                    'fldencounterval' => $prog->fldencounterval,
                ])->get();
        });
        $progress = $this->_format_data($progress_raw);

        return view('inpatient::pdf.progress', compact('patientinfo', 'progress'));
    }

    public function planning(Request $request)
    {

        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);


        $planning_raw = \App\PatPlanning::select('fldid', 'fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldplancategory' => 'Clinician Plan',
            ])->orderBy('fldtime')->get();
        $planning = $this->_format_data($planning_raw);

        return view('inpatient::pdf.planning', compact('patientinfo', 'planning'));
    }

    public function medReturn(Request $request)
    {

        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);


        $medReturn = \App\Pathdosing::select('tblpatdosing.fldid', 'tblpatdosing.fldroute', 'tblpatdosing.flditem', 'tblpatdosing.flddose', 'tblpatdosing.fldfreq', 'tblpatdosing.flddays', 'tblpatdosing.fldqtydisp', 'tblpatdosing.fldqtyret', 'tblpatdosing.fldcurval', \DB::raw('COALESCE(sum(tblnurdosing.fldvalue), 0) AS fldqtyadmin'))
            ->leftJoin('tblnurdosing', 'tblnurdosing.flddoseno', '=', 'tblpatdosing.fldid')
            ->where([
                'tblpatdosing.fldencounterval' => $encounter_id,
                'tblpatdosing.fldsave_order' => '1',
                'tblpatdosing.flditemtype' => 'Medicines',
            ])
            ->groupBy('tblpatdosing.fldid', 'tblpatdosing.fldroute', 'tblpatdosing.flditem', 'tblpatdosing.flddose', 'tblpatdosing.fldfreq', 'tblpatdosing.flddays', 'tblpatdosing.fldqtydisp', 'tblpatdosing.fldqtyret', 'tblpatdosing.fldcurval')
            ->orderBy('tblpatdosing.flditem')->get();

        return view('inpatient::pdf.medReturn', compact('patientinfo', 'medReturn'));
    }

    public function nurActivity(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $patientinfo = $this->_get_patient_info($encounter_id);

        $poIntakes = \App\ExtraDosing::select('fldid', 'fldcategory', 'flditem', 'flddose', 'fldfreq')
            ->where([
                'fldencounterval' => $encounter_id,
                'fldstatus' => 'Continue',
                'fldsave' => '1',
            ])->get();

        return view('inpatient::pdf.nurActivity', compact('patientinfo', 'poIntakes'));
    }

    public function otChecklistsReport(Request $request)
    {
        $data['patientinfo'] = $this->_get_patient_info($request->encounter_id);
        $data['otchecklistdata'] = Otchecklist::where('fldencounterval', $request->encounter_id)->with('signinuser', 'timeoutuser', 'signoutuser')->first();
        return view('inpatient::pdf.otChecklists', $data);
    }


    public function preAnaethesticEvaluationReport(Request $request)
    {
        $data['patientinfo'] = $this->_get_patient_info($request->encounter_id);
        $data['preanaethestic'] = Preanaestheticevaluation::where('fldencounterval', $request->encounter_id)->first();
        return view('inpatient::pdf.preAnaethesticReport', $data);
    }


}
