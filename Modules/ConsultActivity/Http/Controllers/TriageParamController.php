<?php

namespace Modules\ConsultActivity\Http\Controllers;

use App\Exam;
use App\MacAccess;
use App\Pathocategory;
use App\Symptoms;
use App\Test;
use App\Triage;
use App\Utils\Helpers;
use App\Utils\Permission;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class TriageParamController
 * @package Modules\ConsultActivity\Http\Controllers
 */
class TriageParamController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function triageParameter()
    {
        if (Permission::checkPermissionFrontendAdmin('traige-parameters')) {
            Helpers::jobRecord('fmTriageSetting', 'Triage Parameters');

            return view('consultactivity::triage-parameter');
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function compDetail(Request $request)
    {
        $request->validate([
            'tp_comp' => 'required'
        ]);
        $compName = MacAccess::select('fldcompname')
            ->where('fldcomp', $request->tp_comp)
            ->first();
        return $compName->fldcompname;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function getComplaintsList(Request $request)
    {
        $data['triage'] = Triage::select('flid', 'fldchild', 'fldcategory', 'fldrelation', 'fldvalquali', 'flddiagnounit', 'fldbaserate', 'fldhitrate', 'fldfalserate')
            ->where('flddiagnotype', $request->triage_type)
            ->where('fldparent', $request->tp_color_code)
            /*->where('fldcomp', $request->tp_comp)*/
            ->get();
        //        triage-list
        //        return $data;
        $html = view('consultactivity::dynamic-views.complaints-list', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function getExamList(Request $request)
    {
        $data['triage'] = Triage::select('flid', 'fldchild', 'fldcategory', 'fldrelation', 'fldvalquali', 'flddiagnounit', 'fldbaserate', 'fldhitrate', 'fldfalserate')
            ->where('flddiagnotype', $request->triage_type)
            ->where('fldparent', $request->tp_color_code)
            /*->where('fldcomp', $request->tp_comp)*/
            ->get();
        //        triage-list
        //        return $data;
        $html = view('consultgroup::dynamic-views.exam-list', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function getLabList(Request $request)
    {
        $data['triage'] = Triage::select('flid', 'fldchild', 'fldcategory', 'fldrelation', 'fldvalquali', 'flddiagnounit', 'fldbaserate', 'fldhitrate', 'fldfalserate')
            ->where('flddiagnotype', $request->triage_type)
            ->where('fldparent', $request->tp_color_code)
            /*->where('fldcomp', $request->tp_comp)*/
            ->get();

        $html = view('consultactivity::dynamic-views.lab-list', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function addComplaints(Request $request)
    {
        $data['tp_color_code'] = $request->tp_color_code;
        $data['complaintClass'] = Pathocategory::select('flclass')
            ->where('fldcategory', 'Symptom')
            ->get();
        $html = view('consultactivity::dynamic-views.complaint-sish', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function paramComplaints(Request $request)
    {
        $symptoms = Symptoms::select('fldsymptom')->where('fldcategory', 'LIKE', $request->target)->get();
        $html = '<option value=""></option>';
        if (count($symptoms)) {
            foreach ($symptoms as $smptom) {
                $html .= "<option value='$smptom->fldsymptom'>$smptom->fldsymptom</option>";
            }
        }
        return $html;
    }

    /**
     * @param Request $request
     */
    public function sishComplaintAdd(Request $request)
    {
        $request->validate([
            'tp_color_code' => 'required',
            'complaint_param' => 'required',
            'complaint_class' => 'required',
        ]);
        try {
            $dataInsert['fldparent'] = $request->tp_color_code;
            $dataInsert['flddiagnotype'] = 'Symptom';
            $dataInsert['fldchild'] = $request->complaint_param;
            $dataInsert['fldcategory'] = $request->complaint_class;
            $dataInsert['fldrelation'] = $request->complaint_comparision;
            $dataInsert['fldvalquali'] = $request->complaint_unit;
            $dataInsert['fldvalquanti'] = $request->complaint_range_number;
            $dataInsert['flddiagnounit'] = $request->complaint_unit;
            $dataInsert['fldtype'] = 'Qualitative'; //how to check
            $dataInsert['fldbaserate'] = $request->complaint_base_rate;
            $dataInsert['fldhitrate'] = $request->complaint_hit_rate;
            $dataInsert['fldfalserate'] = $request->complaint_false_alarm_rate;
            $dataInsert['fldcomp'] = null;

            Triage::insert($dataInsert);
            $data['triage'] = Triage::select('flid', 'fldchild', 'fldcategory', 'fldrelation', 'fldvalquali', 'flddiagnounit', 'fldbaserate', 'fldhitrate', 'fldfalserate')
                ->where('flddiagnotype', 'Symptom')
                ->where('fldparent', $request->tp_color_code)
                /*->where('fldcomp', $request->tp_comp)*/
                ->get();

            $html = view('consultactivity::dynamic-views.complaints-list', $data)->render();
            return $html;

        } catch (\GearmanException $e) {
            return response()->json(['status' => 'false', 'message'=> __('messages.error')]);
        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function ComplaintDelete(Request $request)
    {
        Triage::where('flid', $request->flid)->delete();

        $data['triage'] = Triage::select('flid', 'fldchild', 'fldcategory', 'fldrelation', 'fldvalquali', 'flddiagnounit', 'fldbaserate', 'fldhitrate', 'fldfalserate')
            ->where('flddiagnotype', $request->triage_type)
            ->where('fldparent', $request->tp_color_code)
            /*->where('fldcomp', $request->tp_comp)*/
            ->get();

        $html = view('consultactivity::dynamic-views.complaints-list', $data)->render();
        return $html;
    }


    /**
     * @param Request $request
     * @return string
     */
    public function paramExam(Request $request)
    {
        $exams = Exam::select('fldexamid')->where('fldtype', 'LIKE', $request->target)->get();
        $html = '<option value=""></option>';
        if (count($exams)) {
            foreach ($exams as $exam) {
                $html .= "<option value='$exam->fldexamid'>$exam->fldexamid</option>";
            }
        }
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function addExam(Request $request)
    {
        $data['tp_color_code'] = $request->tp_color_code;
        $data['examClass'] = Pathocategory::select('flclass')
            ->where('fldcategory', 'Exam')
            ->get();
        $html = view('consultgroup::dynamic-views.exam-sish', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     */
    public function sishExamAdd(Request $request)
    {

        $request->validate([
            'tp_color_code' => 'required',
            'exam_param' => 'required',
            'exam_class' => 'required',
        ]);
        try {
            $category = Exam::select('fldcategory')->where('fldexamid', $request->exam_param)->first();

            $dataInsert['fldparent'] = $request->tp_color_code;
            $dataInsert['flddiagnotype'] = 'Exam';
            $dataInsert['fldchild'] = $request->exam_param;
            $dataInsert['fldcategory'] = $category->fldcategory;
            $dataInsert['fldrelation'] = $request->exam_comparision;
            $dataInsert['fldvalquali'] = $request->exam_unit;
            $dataInsert['fldvalquanti'] = $request->exam_range_number;
            $dataInsert['flddiagnounit'] = $request->exam_unit;
            $dataInsert['fldtype'] = $request->exam_class; //how to check
            $dataInsert['fldbaserate'] = $request->exam_base_rate;
            $dataInsert['fldhitrate'] = $request->exam_hit_rate;
            $dataInsert['fldfalserate'] = $request->exam_false_alarm_rate;
            $dataInsert['fldcomp'] = null;

            Triage::insert($dataInsert);
            $data['triage'] = Triage::select('flid', 'fldchild', 'fldcategory', 'fldrelation', 'fldvalquali', 'flddiagnounit', 'fldbaserate', 'fldhitrate', 'fldfalserate')
                ->where('flddiagnotype', "Exam")
                ->where('fldparent', $request->tp_color_code)
                /*->where('fldcomp', $request->tp_comp)*/
                ->get();

            return $html = view('consultgroup::dynamic-views.exam-list', $data)->render();

        } catch (\GearmanException $e) {
            return response()->json(['status' => 'false', 'message'=> __('messages.error')]);
        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function sishExamDelete(Request $request)
    {
        Triage::where('flid', $request->flid)->delete();

        $data['triage'] = Triage::select('flid', 'fldchild', 'fldcategory', 'fldrelation', 'fldvalquali', 'flddiagnounit', 'fldbaserate', 'fldhitrate', 'fldfalserate')
            ->where('flddiagnotype', $request->triage_type)
            ->where('fldparent', $request->tp_color_code)
            /*->where('fldcomp', $request->tp_comp)*/
            ->get();

        $html = view('consultgroup::dynamic-views.exam-list', $data)->render();
        return $html;
    }


    /**
     * @param Request $request
     * @return string
     */
    public function paramLab(Request $request)
    {
        $exams = Test::select('fldtestid')->where('fldtype', 'LIKE', $request->target)->get();
        $html = '<option value=""></option>';
        if (count($exams)) {
            foreach ($exams as $exam) {
                $html .= "<option value='$exam->fldtestid'>$exam->fldtestid</option>";
            }
        }
        return $html;
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function addLab(Request $request)
    {
        $data['tp_color_code'] = $request->tp_color_code;
        $data['examClass'] = Pathocategory::select('flclass')
            ->where('fldcategory', 'Test')
            ->get();

        $html = view('consultactivity::dynamic-views.laboratory-sish', $data)->render();
        return $html;
    }

    /**
     * @param Request $request
     */
    public function sishLabAdd(Request $request)
    {
        $request->validate([
            'tp_color_code' => 'required',
            'lab_param' => 'required',
            'lab_class' => 'required',
        ]);
        try {

            $category = Test::select('fldcategory')->where('fldtestid', $request->lab_param)->first();

            $dataInsert['fldparent'] = $request->tp_color_code;
            $dataInsert['flddiagnotype'] = 'Test';
            $dataInsert['fldchild'] = $request->lab_param;
            $dataInsert['fldcategory'] = $category->fldcategory;
            $dataInsert['fldrelation'] = $request->lab_comparision;
            $dataInsert['fldvalquali'] = $request->lab_unit;
            $dataInsert['fldvalquanti'] = $request->lab_range_number;
            $dataInsert['flddiagnounit'] = $request->lab_unit;
            $dataInsert['fldtype'] = $request->lab_class; //how to check
            $dataInsert['fldbaserate'] = $request->lab_base_rate;
            $dataInsert['fldhitrate'] = $request->lab_hit_rate;
            $dataInsert['fldfalserate'] = $request->lab_false_alarm_rate;
            $dataInsert['fldcomp'] = null;

            Triage::insert($dataInsert);
            $data['triage'] = Triage::select('flid', 'fldchild', 'fldcategory', 'fldrelation', 'fldvalquali', 'flddiagnounit', 'fldbaserate', 'fldhitrate', 'fldfalserate')
                ->where('flddiagnotype', "Test")
                ->where('fldparent', $request->tp_color_code)
                /*->where('fldcomp', $request->tp_comp)*/
                ->get();

            $html = view('consultactivity::dynamic-views.lab-list', $data)->render();
            return $html;

        } catch (\GearmanException $e) {
            return response()->json(['status' => 'false', 'message'=> __('messages.error')]);
        }
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function sishLabDelete(Request $request)
    {
        Triage::where('flid', $request->flid)->delete();

        $data['triage'] = Triage::select('flid', 'fldchild', 'fldcategory', 'fldrelation', 'fldvalquali', 'flddiagnounit', 'fldbaserate', 'fldhitrate', 'fldfalserate')
            ->where('flddiagnotype', $request->triage_type)
            ->where('fldparent', $request->tp_color_code)
            /*->where('fldcomp', $request->tp_comp)*/
            ->get();

        $html = view('consultactivity::dynamic-views.lab-list', $data)->render();
        return $html;
    }
}
