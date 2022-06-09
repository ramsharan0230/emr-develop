<?php

namespace Modules\Setting\Http\Controllers;

use App\Utils\Helpers;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FiscalYearController extends Controller
{
    function fiscalyear()
    {
        $data['year'] = Year::all();
        return view('setting::fiscalyear', $data);
    }

    public function addFiscalYear(Request $request)
    {
        $validated = $request->validate([
            'fiscal_label' => 'required',
            'eng_from_date' => 'required',
            'eng_to_date' => 'required',
        ]);
        try {
            $data = [
                'fldname' => $request->fiscal_label,
                'fldfirst' => $request->eng_from_date,
                'fldlast' => $request->eng_to_date . " 23:59:59",
                'hospital_department_id' =>Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            Year::create($data);
            Helpers::logStack(["Fiscal year created", "Event"], ['current_data' => $data]);
            return redirect()->back()->with('success_message', 'Successfully added fiscal year');
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in fiscal year create', "Error"]);
            return redirect()->back()->with('error_message', __('messages.error'));
        }

    }

    public function edit($fldname)
    {
        $data['year'] = Year::all();
        $data['yearEdit'] = Year::where('fldname', 'LIKE', decrypt($fldname))->first();

        return view('setting::fiscalyear-edit', $data);
    }

    function updatefiscal(Request $request)
    {
        $validated = $request->validate([
            'fiscal_label' => 'required',
            'eng_from_date' => 'required',
            'eng_to_date' => 'required',
        ]);

        try {
            $data = [
                'fldname' => $request->fiscal_label,
                'fldfirst' => $request->eng_from_date,
                'fldlast' => $request->eng_to_date . " 23:59:59",
                'hospital_department_id' =>Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            $year = Year::where('fldname', 'LIKE', $request->__fldname)->first();
            $year->update($data);
            Helpers::logStack(["Fiscal year updated", "Event"], ['current_data' => $data, 'previous_data' => $year]);
            return redirect()->route('fiscal.setting')->with('success_message', __('messages.update', ['name' => 'Fiscal Year']));
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in fiscal year update', "Error"]);
            return redirect()->route('fiscal.setting')->with('error_message', __('messages.error'));
        }

    }

    public function deletefiscalyear($fldname)
    {
        try {
            $year = Year::where('fldname', 'LIKE', decrypt($fldname))->first();
            if(!$year) {
                Helpers::logStack(["Fiscal year not found in fiscal year delete", "Error"]);
                Session::flash('error_message', 'Data not found.');
                return redirect()->route('fiscal.setting');
            }
            Year::where('fldname', 'LIKE', decrypt($fldname))->delete();
            Helpers::logStack(["Fiscal year deleted", "Event"], ['previous_data' => $year]);
            return redirect()->route('fiscal.setting')->with('success_message', 'Successfully deleted fiscal year');
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in fiscal year delete', "Error"]);
            Session::flash('error_message', $e->getMessage());
            return redirect()->route('fiscal.setting');
        }
    }
}
