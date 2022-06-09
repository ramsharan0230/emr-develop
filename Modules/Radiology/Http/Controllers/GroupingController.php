<?php

namespace Modules\Radiology\Http\Controllers;

use App\Radio;
use App\RadioGroup;
use App\Radiolimit;
use App\ServiceCost;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class GroupingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('radiology::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('radiology::create');
    }

    public function selectServiceCostFromGroup(Request $request) {
        $fldgroup = $request->fldgroup;
        $flditemtype = 'Radio Diagnostics';
        $response = array();
        try {
            $html = '<option value="">--select group --</option>';
            if($fldgroup) {
                $servicecosts = ServiceCost::where(['flditemtype' => $flditemtype, 'fldstatus' => 'Active'])
                                            ->whereIn('fldgroup',[$fldgroup,"%"])
                                            ->get();

                if(count($servicecosts) > 0) {
                    foreach($servicecosts as $servicecost) {
                        $html .= '<option value="'.$servicecost->flditemname.'">'.$servicecost->flditemname.'</option>';
                    }

                }
            }

            $response['message'] = "success";
            $response['html'] = $html;

        } catch(\Exception $e) {

            $response['message'] = 'error';
            $response['messagedetail'] = 'something went wrong';
        }

        return json_encode($response);
    }

    public function selectExamidFromDatatype(Request $request) {
        $fldtype = $request->fldtype;
        $response = array();
        try {
            $html = '<option value="">--select test--</option>';
            if($fldtype) {
                $radios = Radio::where(['fldtype' => $fldtype])->get();

                if(count($radios) > 0) {
                    foreach($radios as $radio) {
                        $html .= '<option value="'.$radio->fldexamid  .'">'.$radio->fldexamid  .'</option>';
                    }

                }
            }

            $response['message'] = "success";
            $response['html'] = $html;

        } catch(\Exception $e) {

            $response['message'] = 'error';
            $response['messagedetail'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function testMethodFromExamId(Request $request) {
        $fldexamid = $request->fldexamid;
        $response = array();
        try {
            $html = '<option value="Regular">Regular</option>';
            if($fldexamid) {
                $radiolimitmethods = Radiolimit::select('fldmethod')->where(['fldexamid' => $fldexamid])->groupBy('fldmethod')->get();

                if(count($radiolimitmethods) > 0) {
                    foreach($radiolimitmethods as $radiolimitmethod) {
                        $html .= '<option value="'.$radiolimitmethod->fldmethod  .'">'.$radiolimitmethod->fldmethod  .'</option>';
                    }

                }
            }

            $response['message'] = "success";
            $response['html'] = $html;

        } catch(\Exception $e) {

            $response['message'] = 'error';
            $response['messagedetail'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function addRadioGroup(Request $request) {
        $radiogroupdata = [];
        $radiogroupdata['fldgroupname'] = $request->fldgroupname;
        $radiogroupdata['fldtesttype'] = $request->fldtesttype;
        $radiogroupdata['fldtestid'] = $request->fldtestid;
        $radiogroupdata['fldptsex'] = $request->fldptsex;
        $radiogroupdata['fldactive'] = $request->fldactive;
        $response = array();
        try {
            RadioGroup::Insert($radiogroupdata);

            $radiogroups = RadioGroup::where('fldgroupname', $radiogroupdata['fldgroupname'])->get();
            $html = '<thead><tr><td></td><td>TestName</td><td>Type</td><td>Method</td><td>Gender</td><td class="text-center">Action</td></thead>';
            $html .= '<tbody>';
            if(count($radiogroups) > 0) {
                foreach($radiogroups as $k=>$radiogroup) {
                    $html .= '<tr><td>'.++$k.'</td><td>'.$radiogroup->fldtestid.'</td><td>'.$radiogroup->fldtesttype.'</td><td>'.$radiogroup->fldactive.'</td><td>'.$radiogroup->fldptsex.'</td><td class="text-center"><button title="delete '. $radiogroup->fldtestid .'" class="deleteradiogroup btn btn-danger btn-sm" data-href="'. route("radiology.grouping.deleteradiogroup", $radiogroup->fldid) .'"><i class="fa fa-trash"></i></button></td></tr>';
                }
            }

            $html .= '</tbody>';
            $response['message'] = 'success';
            $response['html'] = $html;
        } catch(\Exception $e) {

            $response['message'] = 'error';
            $response['messagedetail'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function deleteRadioGroup($fldid) {
        $response = array();
        try {

            $radiogroup = RadioGroup::find($fldid);

            if($radiogroup) {
                $radiogroup->delete();
                $response['message'] = 'success';
            }
        } catch(\Exception $e) {
            $response['error'] = $e->getMessage();
//            $response['error'] = "something went wrong";
            $response['message'] = "error";
        }

        return json_encode($response);
    }

    public function loadTestsOnGroupChange(Request $request) {
       $fldgroupname = $request->fldgroupname;
        $response = array();
        try {

            $radiogroups = RadioGroup::where('fldgroupname', $fldgroupname)->get();
            $html = '<thead><tr><td></td><td>TestName</td><td>Type</td><td>Method</td><td>Gender</td><td class="text-center">Action</td></thead>';
            $html .= '<tbody>';
            if(count($radiogroups) > 0) {
                foreach($radiogroups as $k=>$radiogroup) {
                    $html .= '<tr><td>'.++$k.'</td><td>'.$radiogroup->fldtestid.'</td><td>'.$radiogroup->fldtesttype.'</td><td>'.$radiogroup->fldactive.'</td><td>'.$radiogroup->fldptsex.'</td><td class="text-center"><button title="delete '. $radiogroup->fldtestid .'" class="deleteradiogroup btn btn-danger btn-sm" data-href="'. route("radiology.grouping.deleteradiogroup", $radiogroup->fldid) .'"><i class="fa fa-trash"></i></button></td></tr>';
                }
            }

            $html .= '</tbody>';
            $response['message'] = 'success';
            $response['html'] = $html;
        } catch(\Exception $e) {

            $response['message'] = 'error';
            $response['messagedetail'] = $e->getMessage();
        }

        return json_encode($response);
    }

    public function exportRadioGroups() {
        $data = [];
        $radiogroups = RadioGroup::select('fldgroupname')->groupby('fldgroupname')->orderBy('fldgroupname', 'ASC')->get();
        $data['radiogroups'] = $radiogroups;

        $pdf = view('radiology::layouts.pdf.radiogroupspdf', $data);
        // $pdf->setpaper('a4');
        return $pdf;
    }

    public function displayGrouping()
    {
        return view('radiology::layouts.modal.grouping');
    }
}
