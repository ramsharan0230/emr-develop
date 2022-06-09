<?php

namespace Modules\PatientHistory\Http\Controllers;

use App\ExamGeneral;
use App\NurseDosing;
use App\PathoSymp;
use App\PatRadioTest;
use App\Radio;
use Illuminate\Routing\Controller;

class PatientFunctionsController extends Controller
{
    public function transition($encounters)
    {
        $transitions = \App\PatTiming::select('fldid', 'flditem', 'fldsecondreport', 'fldfirsttime', 'fldfirstuserid', 'fldsecondtime', 'fldseconduserid', 'fldcomment')
            ->where('fldsecondsave', 1)
            ->where('fldtype', 'LIKE', 'General Services')
            ->where('fldfirstreport', 'LIKE', 'Bed')
            ->whereIn('fldencounterval', $encounters)
            ->limit(5)->get();

        foreach ($transitions as &$data) {
            $transitions->duration = round((strtotime($data->fldsecondtime) - strtotime($data->fldfirsttime)) / 3600) . " Hours";
            $transitions->fldsecondtime = $this->_format_datetime($data->fldsecondtime, TRUE);
            $transitions->fldfirsttime = $this->_format_datetime($data->fldfirsttime, TRUE);
        }
        return $transitions;
    }

    public function symptoms($encounters)
    {
        $symtoms_raw = \App\ExamGeneral::select('fldid', 'flditem', 'fldreportquali', 'flddetail', 'fldtime')
            ->where('fldsave' , '1')
            ->where('fldinput', 'LIKE', 'Presenting Symptoms')
            ->whereIn('fldencounterval', $encounters)
            ->orderBy('fldtime')
            ->limit(5)->get();

        return $this->_format_data($symtoms_raw);
    }

    public function foods($encounters)
    {
        $foods_raw = \App\ExamGeneral::select('fldid', 'fldencounterval', 'fldtype', 'flditem', 'fldtime', 'fldreportquanti', 'fldreportquali', 'fldfluid', 'fldenergy', 'fldprotein', 'fldsugar', 'fldlipid', 'fldmineral', 'fldfibre', 'fldcalcium', 'fldphosphorous', 'fldiron', 'fldcarotene', 'fldthiamine', 'fldriboflavin', 'fldniacin', 'fldpyridoxine', 'fldfreefolic', 'fldtotalfolic', 'fldvitaminc')
            ->join('tblfoodcontent', 'tblfoodcontent.fldfoodid', '=', 'tblexamgeneral.flditem')
            ->where('fldinput', 'Input Food/Fluid')
            ->whereIn('fldencounterval', $encounters)
            ->orderBy('fldtime')
            ->limit(5)->get();

        return $this->_format_data($foods_raw);
    }

    public function exam($encounters)
    {
        $exams_raw = \App\PatientExam::select('fldid', 'tblpatientexam.fldtype', 'fldtime', 'fldcomp', 'fldmethod', 'fldhead', 'fldrepquanti', 'fldrepquali', 'fldinput', 'fldabnormal', 'tblexam.fldoption')
            ->join('tblexam', 'tblexam.fldexamid', '=', 'tblpatientexam.fldhead')
            ->where('tblpatientexam.fldsave', 1)
            ->whereIn('tblpatientexam.fldencounterval', $encounters)
            ->orderBy('fldtime')
            ->limit(5)->get();
        return $this->_format_data($exams_raw);
    }

    public function laboratory($encounters)
    {
        $laboratory = \App\PatLabTest::select('fldtime_sample', 'fldid', 'fldsampletype', 'fldtest_type', 'fldmethod', 'fldtestid', 'fldencounterval', 'fldreportquali', 'fldreportquanti', 'fldabnormal', 'fldsampleid', 'flduserid_verify', 'fldcomment', 'fldstatus')
            ->with([
                'testLimit:fldtestid,fldsilow,fldsihigh,fldsiunit',
                'subTest:fldtestid,fldsubtest,fldtanswertype,fldreport,fldabnormal,fldsampleid,fldid',
                'test:fldtestid,fldcategory',
                'subTest.quantity_range',
                'subTest.subtables',
            ])->where([
                'flvisible' => 'Visible',
            ])
            ->whereIn('fldencounterval', $encounters);
        if (\App\Utils\Options::get('show_verified') == '1') {
            $laboratory->where('fldstatus', 'Verified');
        } else {
            $laboratory->where(function ($query) {
                $query->where('fldstatus', 'Reported');
                $query->orWhere('fldstatus', 'Verified');
            });
        }
        $laboratory = $laboratory->orderBy('fldtime_sample')
            ->limit(5)->get();

        return $this->_format_data($laboratory, 'fldtime_sample');
    }

    public function radiology($encounters)
    {
        $patRadioTestData = PatRadioTest::where('fldsave_report', 1)
            ->where('flvisible', 'Visible')
            ->whereIn('fldencounterval', $encounters)
            ->pluck('fldtestid');

        $categories = Radio::whereIn('fldexamid', $patRadioTestData)
            ->distinct('fldcategory')
            ->limit(5)->get();

        $radiologies = [];
        foreach ($categories as $category) {
            $examId = Radio::where('fldcategory', $category->fldcategory)->pluck('fldexamid');

            $radio = PatRadioTest::select('fldid', 'fldtest_type', 'fldtestid', 'fldtime_report', 'fldabnormal', 'fldstatus', 'fldmethod', 'fldsampletype')
                ->where('fldsave_report', 1)
                ->where('flvisible', 'Visible')
                ->whereIn('fldencounterval', $encounters)
                ->whereIn('fldtestid', $examId)
                ->get();
            if ($radio) {
                $radiologies[] = $radio[0];
            }
        }
        return $radiologies;
    }

    public function notes($encounters)
    {
        $notes_raw = \App\ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
            ->where([
                'fldinput' => 'Notes',
            ])
            ->whereIn('fldencounterval', $encounters)
            ->orderBy('fldtime')
            ->limit(5)->get();

        return $this->_format_data($notes_raw);
    }

    public function medDosing($encounters)
    {
        $medDosing_raw = \App\NurseDosing::select('tblnurdosing.fldtime', 'flddoseno', 'fldvalue', 'fldunit', 'tblpatdosing.fldid', 'fldroute', 'flditem', 'flddose', 'tblpatdosing.fldfreq', 'flddays', 'flditemtype')
            ->leftJoin('tblpatdosing', 'tblpatdosing.fldid', '=', 'tblnurdosing.flddoseno')
            ->whereIn('tblnurdosing.fldencounterval', $encounters)
            ->orderBy('tblnurdosing.fldtime')
            ->limit(5)->get();

        return $this->_format_data($medDosing_raw);
    }

    public function progress($encounters)
    {
        $progress_raw = \App\PatPlanning::select('fldid', 'fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan', 'fldencounterval')
            ->whereIn('fldencounterval', $encounters)
            ->where([
                'fldplancategory' => 'IP Monitoring',
            ])
            ->orderBy('fldtime')
            ->limit(5)->get();

        $progress_raw->map(function ($prog) {
            $prog->exams = \App\PatientExam::select('fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti')
                ->where([
                    'fldinput' => "IP Monitoring:{$prog->fldid}",
                    'fldsave' => '1',
                    'fldencounterval' => $prog->fldencounterval,
                ])->get();
        });

        return $this->_format_data($progress_raw);
    }

    public function nursActivity($encounters)
    {
        return \App\ExtraDosing::select('fldid', 'fldcategory', 'flditem', 'flddose', 'fldfreq')
            ->where([
                'fldstatus' => 'Continue',
                'fldsave' => '1',
            ])
            ->whereIn('fldencounterval', $encounters)
            ->limit(5)->get();
    }

    public function bladder($encounters)
    {
        return NurseDosing::with('getName','examgeneral')
            ->whereIn('fldencounterval', $encounters)
            ->where('fldunit','mL/Hour')
            ->limit(5)->get();
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

    public function diagnosis()
    {
        /**diagnosis*/
        $data['diagnosis_data'] = [];
        foreach ($this->_range_generate($data['patientDetails']->fldregdate) as $date) {
            $examData = ExamGeneral::select('flditem')
                ->where('fldsave', 1)
                ->where('fldinput', '')
                ->whereIn('fldencounterval', $data['encounters']->pluck('fldencounterval'))
                ->pluck('flditem');

            $pathoData = PathoSymp::whereIn('fldchild', $examData)->get();

            if ($pathoData) {
                foreach ($pathoData as $data) {

                    $examDataInner = ExamGeneral::select('flditem')
                        ->where('fldsave', 1)
                        ->where('fldsave', $date)
                        ->where('fldparent', $data->fldparent)
                        ->where('fldinput', '')
                        ->whereIn('fldencounterval', $data['encounters']->pluck('fldencounterval'))
                        ->pluck('flditem');

                    $data['diagnosis_data'][$date] += PathoSymp::select('fldchild')
                        ->whereIn('fldchild', $examDataInner)
                        ->get();

                }
            }
        }
    }
}
