<?php

namespace Modules\PatientHistory\Http\Controllers;

use App\ExamGeneral;
use App\NurseDosing;
use App\PathoSymp;
use App\PatRadioTest;
use App\Radio;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EncounterFunctionsController extends Controller
{
    public function transition(Request $request)
    {
        $transitions = \App\PatTiming::select('fldid', 'flditem', 'fldsecondreport', 'fldfirsttime', 'fldfirstuserid', 'fldsecondtime', 'fldseconduserid', 'fldcomment')
            ->where([
                'fldtype' => 'General Services',
                'fldfirstreport' => 'Bed',
                'fldsecondsave' => '1',
            ])
            ->where('fldencounterval', $request->encounter)
            ->get();

        foreach ($transitions as &$data) {
            $transitions->duration = round((strtotime($data->fldsecondtime) - strtotime($data->fldfirsttime)) / 3600) . " Hours";
            $transitions->fldsecondtime = $this->_format_datetime($data->fldsecondtime, TRUE);
            $transitions->fldfirsttime = $this->_format_datetime($data->fldfirsttime, TRUE);
        }
        $data['transitions'] = $transitions;
        return response()->json([
            'html' => view('patienthistory::dynamic-data.transition', $data)->render(),
            'status' => 'Success',
            'count' => count($transitions)
        ]);
    }

    public function symptoms(Request $request)
    {
        $symtoms_raw = \App\ExamGeneral::select('fldid', 'flditem', 'fldreportquali', 'flddetail', 'fldtime')
            ->where([
                'fldinput' => 'Presenting Symptoms',
                'fldsave' => '1',
            ])
            ->whereIn('fldencounterval', [$request->encounter])
            ->orderBy('fldtime')
            ->get();

        $data['symtoms'] = $this->_format_data($symtoms_raw);
        return response()->json([
            'html' => view('patienthistory::dynamic-data.symptoms', $data)->render(),
            'status' => 'Success',
            'count' => count($data['symtoms'])
        ]);
    }

    public function foods(Request $request)
    {
        $foods_raw = \App\ExamGeneral::select('fldid', 'fldencounterval', 'fldtype', 'flditem', 'fldtime', 'fldreportquanti', 'fldreportquali', 'fldfluid', 'fldenergy', 'fldprotein', 'fldsugar', 'fldlipid', 'fldmineral', 'fldfibre', 'fldcalcium', 'fldphosphorous', 'fldiron', 'fldcarotene', 'fldthiamine', 'fldriboflavin', 'fldniacin', 'fldpyridoxine', 'fldfreefolic', 'fldtotalfolic', 'fldvitaminc')
            ->join('tblfoodcontent', 'tblfoodcontent.fldfoodid', '=', 'tblexamgeneral.flditem')
            ->where([
                'fldinput' => 'Input Food/Fluid',
            ])
            ->whereIn('fldencounterval', [$request->encounter])
            ->orderBy('fldtime')
            ->limit(5)->get();

        $data['foods'] = $this->_format_data($foods_raw);
        return response()->json([
            'html' => view('patienthistory::dynamic-data.po_inputs', $data)->render(),
            'status' => 'Success',
            'count' => count($data['foods'])
        ]);
    }

    public function exam(Request $request)
    {
        $exams_raw = \App\PatientExam::select('fldid', 'tblpatientexam.fldtype', 'fldtime', 'fldcomp', 'fldmethod', 'fldhead', 'fldrepquanti', 'fldrepquali', 'fldinput', 'fldabnormal', 'tblexam..fldoption')
            ->join('tblexam', 'tblexam.fldexamid', '=', 'tblpatientexam.fldhead')
            ->where([
                'fldsave' => '1',
            ])
            ->whereIn('fldencounterval', [$request->encounter])
            ->orderBy('fldtime')
            ->limit(5)->get();
        $data['exams'] =  $this->_format_data($exams_raw);
        return response()->json([
            'html' => view('patienthistory::dynamic-data.exam', $data)->render(),
            'status' => 'Success',
            'count' => count($data['exams'])
        ]);
    }

    public function laboratory(Request $request)
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
            ->whereIn('fldencounterval', [$request->encounter]);
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

        $data['laboratory'] = $this->_format_data($laboratory, 'fldtime_sample');
        return response()->json([
            'html' => view('patienthistory::dynamic-data.laboratory', $data)->render(),
            'status' => 'Success',
            'count' => count($data['laboratory'])
        ]);
    }

    public function radiology(Request $request)
    {
        $patRadioTestData = PatRadioTest::where('fldsave_report', 1)
            ->where('flvisible', 'Visible')
            ->whereIn('fldencounterval', [$request->encounter])
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
                ->whereIn('fldencounterval', [$request->encounter])
                ->whereIn('fldtestid', $examId)
                ->get();
            if ($radio) {
                $radiologies[] = $radio[0];
            }
        }
        $data['radiologies'] = $radiologies;
        return response()->json([
            'html' => view('patienthistory::dynamic-data.radiology', $data)->render(),
            'status' => 'Success',
            'count' => count($radiologies)
        ]);
    }

    public function notes(Request $request)
    {
        $notes_raw = \App\ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
            ->where([
                'fldinput' => 'Notes',
            ])
            ->whereIn('fldencounterval', [$request->encounter])
            ->orderBy('fldtime')
            ->limit(5)->get();

        $data['notes']= $this->_format_data($notes_raw);
        return response()->json([
            'html' => view('patienthistory::dynamic-data.notes', $data)->render(),
            'status' => 'Success',
            'count' => count($data['notes'])
        ]);
    }

    public function medDosing(Request $request)
    {
        $medDosing_raw = \App\NurseDosing::select('tblnurdosing.fldtime', 'flddoseno', 'fldvalue', 'fldunit', 'tblpatdosing.fldid', 'fldroute', 'flditem', 'flddose', 'tblpatdosing.fldfreq', 'flddays', 'flditemtype')
            ->leftJoin('tblpatdosing', 'tblpatdosing.fldid', '=', 'tblnurdosing.flddoseno')
            ->whereIn('tblnurdosing.fldencounterval', [$request->encounter])
            ->orderBy('tblnurdosing.fldtime')
            ->limit(5)->get();

        $data['medDosing']= $this->_format_data($medDosing_raw);
        return response()->json([
            'html' => view('patienthistory::dynamic-data.med_dosing', $data)->render(),
            'status' => 'Success',
            'count' => count($data['medDosing'])
        ]);
    }

    public function progress(Request $request)
    {
        $progress_raw = \App\PatPlanning::select('fldid', 'fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan', 'fldencounterval')
            ->whereIn('fldencounterval', [$request->encounter])
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

        $data['progress']= $this->_format_data($progress_raw);
        return response()->json([
            'html' => view('patienthistory::dynamic-data.progress', $data)->render(),
            'status' => 'Success',
            'count' => count($data['progress'])
        ]);
    }

    public function nursActivity(Request $request)
    {
        $data['nur_activity']= \App\ExtraDosing::select('fldid', 'fldcategory', 'flditem', 'flddose', 'fldfreq')
            ->where([
                'fldstatus' => 'Continue',
                'fldsave' => '1',
            ])
            ->whereIn('fldencounterval', [$request->encounter])
            ->limit(5)->get();
        return response()->json([
            'html' => view('patienthistory::dynamic-data.nur_activity', $data)->render(),
            'status' => 'Success',
            'count' => count($data['nur_activity'])
        ]);
    }

    public function bladder(Request $request)
    {
        $data['bladder']= NurseDosing::with('getName', 'examgeneral')
            ->whereIn('fldencounterval', [$request->encounter])
            ->where('fldunit', 'mL/Hour')
            ->limit(5)->get();
        return response()->json([
            'html' => view('patienthistory::dynamic-data.bladder_irrigation', $data)->render(),
            'status' => 'Success',
            'count' => count($data['bladder'])
        ]);
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
