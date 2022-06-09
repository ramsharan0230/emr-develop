<?php

namespace Modules\BillingMaster\Http\Controllers;

use App\TaxGroup;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class TaxGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data['tax_list'] = $this->generateTalList();
        return view('billingmaster::tax.list', $data);
    }

    public function taxGroupStore(Request $request)
    {

        $validatedData = $request->validate([
            'tax_group' => 'required',
            'tax' => 'required',
        ]);
        $if_exist = TaxGroup::where('fldgroup',$request->tax_group)->where('fldtaxper',$request->tax)->first();
        if($if_exist){
            return  \response()->json(['error' => 'Cannot add,Tax group exists']);
        }

        try {
            TaxGroup::create(['fldgroup' => $request->tax_group, 'fldtaxper' => $request->tax, 'hospital_department_id' =>Helpers::getUserSelectedHospitalDepartmentIdSession()]);
            $html = $this->generateTalList();
            return response()->json([
                'success' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return response()->json([
                'success' => [
                    'status' => false,
                ]
            ]);
        }
    }

    public function taxGroupDelete(Request $request)
    {
        try {
            TaxGroup::where('fldgroup', $request->tax_group)->delete();

            $html = $this->generateTalList();
            return response()->json([
                'success' => [
                    'status' => true,
                    'html' => $html,
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => [
                    'status' => false,
                ]
            ]);
        }
    }

    public function generateTalList()
    {
        $tax = TaxGroup::all();
        $html = '';
        if ($tax) {
            foreach ($tax as $key => $type) {
                $html .= "<tr>";
                $html .= "<td>" . ++$key . "</td>";
                $html .= "<td>$type->fldgroup</td>";
                $html .= "<td>$type->fldtaxper</td>";
                $html .= "<td><a href='javascript:;' onclick='taxGroup.deleteTaxGroup(\"".$type->fldgroup."\")'><i class='fas fa-trash text-danger'></i></a></td>";
                $html .= "</tr>";
            }
        }
        return $html;
    }

}
