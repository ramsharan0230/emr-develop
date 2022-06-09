<?php

namespace Modules\Menu\Http\Controllers;

use App\Encounter;
use App\Exam;
use App\Examlimit;
use App\MacAccess;
use App\NurseDosing;
use App\PatientExam;
use App\PatientInfo;
use App\PatientSubExam;
use App\PatLabSubTest;
use App\PatLabTest;
use App\PatRadioSubTest;
use App\PatRadioTest;
use App\Radio;
use App\TestLimit;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PHPUnit\Exception;


class DataviewController extends Controller
{
    /**
     * @return array|string
     * @throws \Throwable
     */
    public function displaySampleForm(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        //select distinct(fldsampleid) from tblpatlabtest where fldsave_report='1' and fldencounterval='1'
        $data['samplewise'] = PatLabTest::select('fldsampleid')->where(['fldsave_report' => '1', 'fldencounterval' => $encounter_id])->distinct()->get();
        $data['encounter_id'] = $encounter_id;
        $html = view('menu::menu-dynamic-views.data-view-labotory-form', $data)->render();
        return $html;
    }

    public function downloadSampleLab(Request $request)
    {
        $encounter_id = $request->get('encounter_id');
        $sample_id = $request->get('sample_id');


        $encounterDetail = Encounter::select('fldregdate', 'fldpatientval', 'fldrank')
            ->where('fldencounterval', $encounter_id)
            ->first();

        $patientInfo = PatientInfo::select('fldptsex', 'fldpatientval', 'fldptbirday', 'fldptcontact', 'fldencrypt', 'fldptnamefir', 'fldptnamelast', 'fldptaddvill', 'fldptadddist', 'fldmidname', 'fldrank')
            ->where('fldpatientval', $encounterDetail->fldpatientval)
            ->first();


        $labtests = PatLabTest::select('fldid', 'fldtest_type', 'fldtestid', 'fldsampleid', 'fldtime_sample', 'fldreportquanti', 'fldtestunit', 'fldabnormal', 'fldstatus', 'fldencounterval', 'fldreportquanti', 'fldtestunit')
            ->where(['fldsave_report' => '1', 'fldsampleid' => $sample_id, 'fldencounterval' => $encounter_id, 'flvisible' => 'Visible'])->get();

        $data = array();
        $data['encounter'] = $encounterDetail;
        $data['patientinfo'] = $patientInfo;
        $data['sampleId'] = $sample_id;
        $data['labTests'] = [];
        if ($labtests) {
            foreach ($labtests as $k => $tests) {
                $data['labTests'][$k] = $tests;
                $sublabtests = PatLabSubTest::select('fldsubtest', 'fldreport', 'fldid', 'fldtestid', 'fldtanswertype')
                    ->where(['fldtestid' => $tests->fldid, 'fldsave' => '1', 'fldencounterval' => $encounter_id])->get();
                $data['subTests'][$k]['sub'] = $sublabtests;
                $testLimit = TestLimit::select('fldconvfactor as conv', 'fldsiunit as unit')->where('fldtestid', $tests->fldtestid)
                    ->where(function ($query) {
                        return $query
                            ->orWhere('fldptsex', '=', 'Male')
                            ->orWhere('fldptsex', '=', 'Both Sex');
                    })
                    ->where(function ($query) {
                        return $query
                            ->orWhere('fldagegroup', '=', 'Adolescent')
                            ->orWhere('fldagegroup', '=', 'All Age');
                    })
                    ->first();

                $data['limits'][$k] = $testLimit;
            }
        }

        // dd($data);
        return view('menu::pdf.samplelab', $data)/*->setPaper('a4')->stream('history.pdf')*/ ;
    }


    public function downloadcompleteLab(Request $request, $encounterId, $form_signature = 'opd')
    {
        //dd($encounterId);

        $encounter_id = $encounterId;


        //select fldid,fldsampletype,fldtest_type,fldtime_sample,fldmethod,fldtestid from tblpatlabtest where fldencounterval='1' and fldtime_sample>='2019-09-13 00:00:00' and fldtime_sample<='2019-09-13 23:59:59.999' and (fldstatus='Reported' or fldstatus='Verified') and flvisible='Visible'

        $encounterDetail = Encounter::select('fldregdate', 'fldpatientval', 'fldrank')
            ->where('fldencounterval', $encounter_id)
            ->first();

        $patientInfo = PatientInfo::select('fldptsex', 'fldpatientval', 'fldptbirday', 'fldptcontact', 'fldencrypt', 'fldptnamefir', 'fldptnamelast', 'fldptaddvill', 'fldptadddist', 'fldmidname', 'fldrank')
            ->where('fldpatientval', $encounterDetail->fldpatientval)
            ->first();

        $timetest = PatLabTest::select('fldtime_sample')
            ->where('fldencounterval', $encounter_id)
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Reported')
                    ->orWhere('fldstatus', '=', 'Verified');
            })
            ->where('flvisible', 'Visible')->first();

        $timesample = $timetest->fldtime_sample ?? false;

        $labtests = PatLabTest::select('fldid', 'fldtest_type', 'fldtestid', 'fldsampletype', 'fldsampleid', 'fldtime_sample', 'fldreportquanti', 'fldtestunit', 'fldabnormal', 'fldstatus', 'fldencounterval', 'fldreportquanti', 'fldtestunit')
            ->where(['fldencounterval' => $encounter_id, 'flvisible' => 'Visible'])
            ->where(function ($query) use ($timesample) {
                return $query
                    ->where('fldtime_sample', '>=', $timesample)
                    ->where('fldtime_sample', '<=', $timesample);
            })
            ->where(function ($query) {
                return $query
                    ->orWhere('fldstatus', '=', 'Reported')
                    ->orWhere('fldstatus', '=', 'Verified');
            })
            ->get();

        $data = array();
        $data['encounter'] = $encounterDetail;
        $data['patientinfo'] = $patientInfo;
        $data['timesample'] = $timesample;
        $data['labTests'] = [];

        if ($labtests) {
            foreach ($labtests as $k => $tests) {
                $data['labTests'][$k] = $tests;
                $sublabtests = PatLabSubTest::select('fldsubtest', 'fldreport', 'fldid', 'fldtestid', 'fldtanswertype')
                    ->where(['fldtestid' => $tests->fldid, 'fldsave' => '1', 'fldencounterval' => $encounter_id])->get();
                $data['subTests'][$k]['sub'] = $sublabtests;
                $testLimit = TestLimit::select('fldconvfactor as conv', 'fldsiunit as unit')->where('fldtestid', $tests->fldtestid)
                    ->where(function ($query) {
                        return $query
                            ->orWhere('fldptsex', '=', 'Male')
                            ->orWhere('fldptsex', '=', 'Both Sex');
                    })
                    ->where(function ($query) {
                        return $query
                            ->orWhere('fldagegroup', '=', 'Adolescent')
                            ->orWhere('fldagegroup', '=', 'All Age');
                    })
                    ->first();

                $data['limits'][$k] = $testLimit;
            }
        }

        // dd($data);
        $data['form_signature'] = \App\Utils\Helpers::getModuleNameForSignature($request);
        return view('menu::pdf.completelab', $data)/*->setPaper('a4')->stream('laboratory.pdf')*/ ;
    }

    public function downloadExamination(Request $request, $encounterId, $form_signature = 'opd')
    {

        $encounter_id = $encounterId;


        $encounterDetail = Encounter::select('fldregdate', 'fldpatientval', 'fldrank')
            ->where('fldencounterval', $encounter_id)
            ->first();

        $patientInfo = PatientInfo::select('fldptsex', 'fldpatientval', 'fldptbirday', 'fldptcontact', 'fldencrypt', 'fldptnamefir', 'fldptnamelast', 'fldptaddvill', 'fldptadddist', 'fldmidname', 'fldrank')
            ->where('fldpatientval', $encounterDetail->fldpatientval)
            ->first();

        $examTime = PatientExam::select('fldtime')->where(['fldencounterval' => $encounter_id, 'fldsave' => '1'])->distinct('fldtime')->get();


        $compname = MacAccess::select('fldcompname')->where('fldcomp', Helpers::getCompName())->first();
        $data = array();
        $data['encounter'] = $encounterDetail;
        $data['patientinfo'] = $patientInfo;
        $data['examination'] = [];
        if ($examTime) {
            foreach ($examTime as $k => $time) {
                $datetimenew = date('Y-m-d', strtotime($time->fldtime));
                $startTime = Carbon::parse($datetimenew)->setTime(00, 00, 00);
                $endTime = Carbon::parse($datetimenew)->setTime(23, 99, 99, 999);
                $examinations = PatientExam::select('fldid', 'fldtype', 'fldtime', 'fldinput', 'fldhead', 'fldcomp', 'fldmethod', 'fldrepquanti', 'fldrepquali', 'fldabnormal')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldtime', '>=', $startTime)
                    ->where('fldtime', '<=', $endTime)
                    ->where('fldsave', '1')
                    ->get();

                if ($examinations) {
                    foreach ($examinations as $exam) {
                        $data['examination'][$k] = $exam;
                        $data['subexam'][$k]['sub'] = PatientSubExam::select('fldsubtexam', 'fldreport', 'fldtanswertype', 'fldid', 'fldheadid')
                            ->where(['fldheadid' => $exam->fldid, 'fldencounterval' => $encounter_id])
                            ->get();
                        $data['examoption'][$k] = Exam::select('fldoption')
                            ->where('fldexamid', $exam->fldhead)
                            ->first();

                        $data['examlimit'][$k] = Examlimit::select('fldunit')->where('fldexamid', 'Pulse Rate')
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldptsex', '=', 'Male')
                                    ->orWhere('fldptsex', '=', 'Both Sex');
                            })
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldagegroup', '=', 'Adolescent')
                                    ->orWhere('fldagegroup', '=', 'All Age');
                            })
                            ->first();

                        $data['comname'][$k] = $compname;
                    }
                }
            }
        }

        // $data['examination'][$k] =  PatientExam::select('fldid','fldtype','fldtime','fldinput','fldhead','fldcomp','fldmethod','fldrepquanti','fldrepquali','fldabnormal')
        // ->where(['fldencounterval'=>$encounter_id , 'fldtime >='=>'2020-01-17 00:00:00' , 'fldtime <='=>'2020-01-17 23:59:59.999' , 'fldsave'=>'1'])
        // ->get();

        // dd($data);
        $data['form_signature'] = \App\Utils\Helpers::getModuleNameForSignature($request);
        return view('menu::pdf.dataview-examination', $data)/*->setPaper('a4')->stream('examination.pdf')*/ ;
    }

    public function downloadRadology(Request $request, $encounterId, $form_signature = 'opd')
    {
        $encounter_id = $encounterId;


        $encounterDetail = Encounter::select('fldregdate', 'fldpatientval', 'fldrank')
            ->where('fldencounterval', $encounter_id)
            ->first();

        $patientInfo = PatientInfo::select('fldptsex', 'fldpatientval', 'fldptbirday', 'fldptcontact', 'fldencrypt', 'fldptnamefir', 'fldptnamelast', 'fldptaddvill', 'fldptadddist', 'fldmidname', 'fldrank')
            ->where('fldpatientval', $encounterDetail->fldpatientval)
            ->first();

        $data = array();
        $data['encounter'] = $encounterDetail;
        $data['patientinfo'] = $patientInfo;

        $fldexamid = PatRadioTest::where(['fldsave_report' => '1', 'fldencounterval' => '1', 'flvisible' => 'Visible'])->pluck('fldtestid');

        $radios = Radio::select('fldcategory')->whereIn('fldexamid', $fldexamid)->distinct()->get();

        if ($radios) {
            foreach ($radios as $rod) {
                $exam = Radio::where('fldcategory', $rod->fldcategory)->pluck('fldexamid');

                $pattest = PatRadioTest::select('fldid', 'fldtest_type', 'fldtestid', 'fldtime_report', 'fldabnormal', 'fldstatus', 'fldmethod', 'fldsampletype')
                    ->where(['fldsave_report' => '1', 'fldencounterval' => '1', 'flvisible' => 'Visible'])->whereIn('fldtestid', $exam)->get();

                if ($pattest) {
                    foreach ($pattest as $pat) {
                        $patradiotest = PatRadioTest::select('fldreportquali', 'fldreportquanti', 'fldtestid', 'fldtest_type', 'fldabnormal', 'fldencounterval')
                            ->where(['fldid' => $pat->fldid, 'fldencounterval' => $encounter_id])
                            ->first();

                        $patradiotestsub = PatRadioSubTest::select('fldsubtest', 'fldreport', 'fldid', 'fldtestid', 'fldtanswertype')
                            ->where(['fldtestid' => $pat->fldid, 'fldsave' => '1', 'fldencounterval' => $encounter_id])
                            ->get();

                        $radiooption = RadioOption::select('fldanswer')
                            ->where(['fldexamid' => 'USG OF ABDOMEN AND PELVIS (MALE)', 'fldanswertype' => 'Text Reference'])->orderBy('fldindex')->get();
                    }
                }
            }
        }


        $data['form_signature'] = \App\Utils\Helpers::getModuleNameForSignature($request);

        // dd($data);
        return view('menu::pdf.dataview-radio', $data)/*->setPaper('a4')->stream('radiology.pdf')*/ ;


    }


    public function deliveryReport($encounterId)
    {

    }

    //For Bladder Irrigation

    public function bladderIrrigation($encounterId)
    {

        try {

            $encounter_id = $encounterId;
            if (!$encounter_id) {
                return redirect()->back();
            }
            $data['intakes'] = NurseDosing::with('getName', 'examgeneral')
                ->where('fldencounterval', $encounter_id)
                ->where('fldunit', 'mL/Hour')
                ->get();

            $data ['patientinfo'] = \App\Utils\Helpers::getPatientByEncounterId($encounter_id);

            return view('menu::pdf.bladder-irrigation', $data);
        } catch (Exception $exception) {

        }

    }

}
