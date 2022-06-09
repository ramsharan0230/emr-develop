<?php

namespace Modules\Technologistlab\Http\Controllers;

use App\ServiceCost;
use App\Test;
use App\TestGroup;
use App\TestLimit;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade as PDF;

class LabGroupingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('technologistlab::index');
    }

    public function selectServiceCostFromGroup(Request $request)
    {
        $fldgroup = $request->fldgroup;
        $flditemtype = 'Diagnostic Tests';
        $response = array();
        try {
            $html = '<option value="">--select group --</option>';
            if ($fldgroup) {
                $servicecosts = ServiceCost::where(['flditemtype' => $flditemtype, 'fldgroup' => $fldgroup, 'fldstatus' => 'Active'])->get();

                if (count($servicecosts) > 0) {
                    foreach ($servicecosts as $servicecost) {
                        $html .= '<option value="' . $servicecost->flditemname . '">' . $servicecost->flditemname . '</option>';
                    }

                }
            }

            $response['message'] = "success";
            $response['html'] = $html;

        } catch (\Exception $e) {

            $response['message'] = 'error';
            $response['messagedetail'] = 'something went wrong';
        }

        return json_encode($response);
    }

    public function selectExamidFromDatatype(Request $request)
    {
        $fldtype = $request->fldtype;
        $response = array();
        try {
            $html = '<option value="">--select test--</option>';
            if ($fldtype) {
                $tests = Test::where(['fldtype' => $fldtype])->get();

                if (count($tests) > 0) {
                    foreach ($tests as $test) {
                        $html .= '<option value="' . $test->fldtestid . '">' . $test->fldtestid . '</option>';
                    }

                }
            }

            $response['message'] = "success";
            $response['html'] = $html;

        } catch (\Exception $e) {

            $response['message'] = 'error';
            $response['messagedetail'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function testMethodFromExamId(Request $request)
    {
        $fldtestid = $request->fldexamid;
        $response = array();
        try {
            $html = '<option value="Regular">Regular</option>';
            if ($fldtestid) {
                $radiolimitmethods = TestLimit::select('fldmethod')->where(['fldtestid' => $fldtestid])->groupBy('fldmethod')->get();

                if (count($radiolimitmethods) > 0) {
                    foreach ($radiolimitmethods as $radiolimitmethod) {
                        $html .= '<option value="' . $radiolimitmethod->fldmethod . '">' . $radiolimitmethod->fldmethod . '</option>';
                    }

                }
            }

            $response['message'] = "success";
            $response['html'] = $html;

        } catch (\Exception $e) {

            $response['message'] = 'error';
            $response['messagedetail'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function addTestGroup(Request $request)
    {
        $testgroupdata = [];
        $testgroupdata['fldgroupname'] = $request->fldgroupname;
        $testgroupdata['fldtesttype'] = $request->fldtesttype;
        $testgroupdata['fldtestid'] = $request->fldtestid;
        $testgroupdata['fldactive'] = $request->fldactive;
        $testgroupdata['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

        $response = array();
        try {
            TestGroup::Insert($testgroupdata);

            $testgroups = TestGroup::where('fldgroupname', $testgroupdata['fldgroupname'])->get();
            $html = '';
            if (count($testgroups) > 0) {
                foreach ($testgroups as $k => $testgroup) {
                    $html .= '<tr><td>' . ++$k . '</td><td>' . $testgroup->fldtestid . '</td><td>' . $testgroup->fldtesttype . '</td><td>' . $testgroup->fldactive . '</td><td></td><td class="text-center"><button title="delete ' . $testgroup->fldtestid . '" class="deletetestgroup btn btn-danger btn-sm" data-href="' . route("technologylab.grouping.deletetestgroup", $testgroup->fldid) . '"><i class="fa fa-trash"></i></button></td></tr>';
                }
            }
            $response['message'] = 'success';
            $response['html'] = $html;
        } catch (\Exception $e) {

            $response['message'] = 'error';
            $response['messagedetail'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function deleteTestGroup($fldid)
    {
        $response = array();
        try {

            $testgroup = TestGroup::find($fldid);

            if ($testgroup) {
                $testgroup->delete();
                $response['message'] = 'success';
            }
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
//            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function loadTestsOnGroupChange(Request $request)
    {
        $fldgroupname = $request->fldgroupname;
        $response = array();
        try {
            $testgroups = TestGroup::where('fldgroupname', $fldgroupname)->get();
            $html = '';
            if (count($testgroups) > 0) {
                foreach ($testgroups as $k => $testgroup) {
                    $html .= '<tr><td>' . ++$k . '</td><td>' . $testgroup->fldtestid . '</td><td>' . $testgroup->fldtesttype . '</td><td>' . $testgroup->fldactive . '</td><td>' . $testgroup->fldptsex . '</td><td class="text-center"><button title="delete ' . $testgroup->fldtestid . '" class="deletetestgroup btn btn-danger btn-sm" data-href="' . route("technologylab.grouping.deletetestgroup", $testgroup->fldid) . '"><i class="fa fa-trash"></i></button></td></tr>';
                }
            }

            $response['message'] = 'success';
            $response['html'] = $html;
        } catch (\Exception $e) {

            $response['message'] = 'error';
            $response['messagedetail'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function exportLabGroups()
    {
        $data = [];
        $labgroups = TestGroup::select('fldgroupname')->groupby('fldgroupname')->orderBy('fldgroupname', 'ASC')->get();
        $data['labgroups'] = $labgroups;

        $pdf = view('technologistlab::layouts.pdf.testgrouppdf', $data);
//        $pdf->setpaper('a4');

        return $pdf;
    }

    public function displayForm()
    {
        return view('technologistlab::layouts.modal.labgrouping');
    }
}
