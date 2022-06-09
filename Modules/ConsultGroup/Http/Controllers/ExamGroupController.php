<?php

namespace Modules\ConsultGroup\Http\Controllers;

use App\Exam;
use App\GroupExam;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * Class ExamGroupController
 * @package Modules\ConsultActivity\Http\Controllers
 */
class ExamGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['examinationList'] = Exam::select('fldexamid')->where('fldtype', 'LIKE', '%')->get();
        $data['groupNameList'] = GroupExam::select('fldid', 'fldgroupname', 'fldexamid')->orderBy('fldgroupname', 'Asc')->groupBy('fldgroupname')->get();

        return view('consultgroup::exam-group', $data);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function refreshExamList(Request $request)
    {
        $groupNameList = GroupExam::select('fldid', 'fldgroupname', 'fldexamid')
            ->where('fldgroupname', 'LIKE', $request->group_dropdown)
            ->get();

        $html = '';
        $count = 1;
        if (count($groupNameList)) {
            foreach ($groupNameList as $gn) {
                $html .= '<tr>
                                <td>' . $count . '</td>
                                <td>' . $gn->fldgroupname . '</td>
                                <td>' . $gn->fldexamid . '</td>';

                $html .= "<td><a href=\"javascript:;\" onclick=\"ExamGroup.deleteExamGroup($gn->fldid)\"><i class=\"fa fa-trash text-danger\"></i></a></td>";
                $html .= '</tr>';
                $count++;
            }
        }
        return $html;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function refreshExamAdd(Request $request)
    {
        try {
            if ($request->group_name != "") {
                $insertData['fldgroupname'] = $request->group_name;
            } else {
                $insertData['fldgroupname'] = $request->group_dropdown;
            }

            $insertData['fldexamid'] = $request->exam_name;

            GroupExam::insert($insertData);

            $groupNameList = GroupExam::select('fldgroupname', 'fldexamid')
                ->where('fldgroupname', 'LIKE', $request->group_dropdown)
                ->get();

            $html = '';
            $count = 1;
            if (count($groupNameList)) {
                foreach ($groupNameList as $gn) {
                    $html .= '<tr>
                                <td>' . $count . '</td>
                                <td>' . $gn->fldgroupname . '</td>
                                <td>' . $gn->fldexamid . '</td>';

                    $html .= "<td><a href=\"javascript:;\" onclick=\"ExamGroup.deleteExamGroup('$gn->fldgroupname', '$gn->fldexamid')\"><i class=\"fa fa-trash text-danger\"></i></a></td>";
                    $html .= '</tr>';
                    $count++;
                }
            }
            return $html;
        } catch (\GearmanException $e) {

        }

    }

    /**
     * @param Request $request
     * @return string
     */
    public function refreshExamDelete(Request $request)
    {
        try {
            $groupExam = GroupExam::where('fldid', $request->fldid)
                ->first();
            $groupName = $groupExam->fldgroupname;


            GroupExam::where('fldid', $request->fldid)->delete();
            $groupNameList = GroupExam::select('fldid', 'fldgroupname', 'fldexamid')
                ->where('fldgroupname', 'LIKE', $groupName)
                ->where('fldid', '!=', $request->fldid)
                ->get();
            $html = '';
            $count = 1;
            if (count($groupNameList)) {
                foreach ($groupNameList as $gn) {
                    $html .= '<tr>
                                <td>' . $count . '</td>
                                <td>' . $gn->fldgroupname . '</td>
                                <td>' . $gn->fldexamid . '</td>';

                    $html .= "<td><a href=\"javascript:;\" onclick=\"ExamGroup.deleteExamGroup($gn->fldid)\"><i class=\"fa fa-trash text-danger\"></i></a></td>";
                    $html .= '</tr>';
                    $count++;
                }
            }
            return $html;
        } catch (\GearmanException $e) {

        }
    }

    public function exportExamGroup(Request $request)
    {
        $groupNameList = GroupExam::select('fldid', 'fldgroupname', 'fldexamid');

        if ($request->group_name != "") {
            $groupNameList->where('fldgroupname', 'LIKE', $request->group_name);
        }
        $result = $groupNameList->get();
        $html = '';
        $count = 1;
        if (count($result)) {
            foreach ($result as $gn) {
                $html .= '<tr>
                                <td>' . $count . '</td>
                                <td>' . $gn->fldgroupname . '</td>
                                <td>' . $gn->fldexamid . '</td>';

                $html .= '</tr>';
                $count++;
            }
        }
        $data['html'] = $html;

        return view('consultgroup::pdf.exam-pdf', $data)/*->setPaper('a4')->stream('exam-group.pdf')*/;
    }
}
