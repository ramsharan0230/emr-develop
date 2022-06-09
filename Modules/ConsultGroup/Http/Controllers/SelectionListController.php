<?php

namespace Modules\ConsultGroup\Http\Controllers;

use App\CostGroup;
use App\ServiceCost;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SelectionListController extends Controller
{
    public function index()
    {
        return view('consultgroup::selection-list');
    }

    public function getGroupName(Request $request)
    {
        $itemType = 'Diagnostic Tests';
        if ($request->test_name == "Radio") {
            $itemType = 'Radio Diagnostics';
        }
        $data['costGroup'] = CostGroup::select('fldgroup')
            ->where('flditemtype', $request->test_name)
            ->distinct('fldgroup')
            ->get();

        $data['serviceCost'] = ServiceCost::select('flditemname')
            ->where('flditemtype', $itemType)
            ->where('fldstatus', 'Active')
            ->where(function ($query) {
                return $query
                    ->orWhere('fldgroup', 'LIKE', '%')
                    ->orWhere('fldgroup', '=', '%');
            })
            ->get();

        $html['costgroupSelect'] = '<option value=""></option>';
        if (count($data['costGroup'])) {
            foreach ($data['costGroup'] as $cg) {
                $html['costgroupSelect'] .= "<option value='$cg->fldgroup'>$cg->fldgroup</option>";
            }
        }

        $html['serviceCostSelect'] = '<option value=""></option>';
        if (count($data['serviceCost'])) {
            foreach ($data['serviceCost'] as $sc) {
                $html['serviceCostSelect'] .= "<option value='$sc->flditemname'>$sc->flditemname</option>";
            }
        }

        return $html;
    }

    public function displayTableList(Request $request)
    {
//        select fldid,flditemtype,flditemname from tblcostgroup where fldgroup='ANC'
        $costgroup = CostGroup::select('fldid', 'flditemtype', 'flditemname')->where('fldgroup', $request->group_name)->get();

        $html = '';
        $count = 1;
        if (count($costgroup)) {
            foreach ($costgroup as $cg) {
                $html .= "<tr>";
                $html .= "<td>$count</td>";
                $html .= "<td>$cg->flditemtype</td>";
                $html .= "<td>$cg->flditemname</td>";
                $html .= "<td><a href='javascript:;' onclick='selectionList.deleteSelectionItem($cg->fldid)'><i class='fa fa-trash text-danger'></i></a></td>";
                $html .= "</tr>";
                $count++;
            }
        }

        return $html;
    }

    public function addSelection(Request $request)
    {
        try {
            if ($request->group_name_free != "") {
                $insertData['fldgroup'] = $request->group_name_free;
            } else {
                $insertData['fldgroup'] = $request->group_name;
            }

            $insertData['flditemtype'] = $request->test_name;
            $insertData['flditemname'] = $request->item_name;

            CostGroup::insert($insertData);

            $costgroup = CostGroup::select('fldid', 'flditemtype', 'flditemname')->where('fldgroup', $insertData['fldgroup'])->get();

            $html = '';
            $count = 1;
            if (count($costgroup)) {
                foreach ($costgroup as $cg) {
                    $html .= "<tr>";
                    $html .= "<td>$count</td>";
                    $html .= "<td>$cg->flditemtype</td>";
                    $html .= "<td>$cg->flditemname</td>";
                    $html .= "<td><a href='javascript:;' onclick='selectionList.deleteSelectionItem($cg->fldid)'><i class='fa fa-trash text-danger'></i></a></td>";
                    $html .= "</tr>";
                    $count++;
                }
            }

            return $html;
        } catch (\GearmanException $e) {

        }
    }

    public function deleteSelection(Request $request)
    {
        try {
            CostGroup::where('fldid', $request->fldid)
                ->delete();

            $costgroup = CostGroup::select('fldid', 'flditemtype', 'flditemname')->where('fldgroup', $request->group_name)->get();

            $html = '';
            $count = 1;
            if (count($costgroup)) {
                foreach ($costgroup as $cg) {
                    $html .= "<tr>";
                    $html .= "<td>$count</td>";
                    $html .= "<td>$cg->flditemtype</td>";
                    $html .= "<td>$cg->flditemname</td>";
                    $html .= "<td><a href='javascript:;' onclick='selectionList.deleteSelectionItem($cg->fldid)'><i class='fa fa-trash text-danger'></i></a></td>";
                    $html .= "</tr>";
                    $count++;
                }
            }

            return $html;
        } catch (\GearmanException $e) {

        }
    }

    public function selectionExport(Request $request)
    {
        $costgroup = CostGroup::select('fldid', 'fldgroup', 'flditemtype', 'flditemname')->where('fldgroup', $request->group_name)->get();

        $html = '';
        $count = 1;
        if (count($costgroup)) {
            foreach ($costgroup as $cg) {
                $html .= "<tr>";
                $html .= "<td>$count</td>";
                $html .= "<td>$cg->flditemtype</td>";
                $html .= "<td>$cg->fldgroup</td>";
                $html .= "<td>$cg->flditemname</td>";
                $html .= "</tr>";
                $count++;
            }
        }
        $data['html'] = $html;

        return $pdfString = view('consultgroup::pdf.selection-pdf', $data)/*->setPaper('a4')->stream('selection-group.pdf')*/;
    }
}
