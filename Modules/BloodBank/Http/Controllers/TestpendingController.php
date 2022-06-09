<?php

namespace Modules\BloodBank\Http\Controllers;

use App\BagMaster;
use App\ComponentDetais;
use App\Department;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class TestpendingController extends Controller
{
    public function index()
    {
        $data ['hospitalbranches'] = \App\HospitalBranch::select('name', 'id')->where('status', 'active')->get();
        $data['dates'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;
        $data['departments'] = Department::select('fldid', 'flddept')->distinct('flddept')->get();
        $data['component_details'] = ComponentDetais::with('component', 'component.bloodbag', 'component.bloodbag.donor', 'component.bloodbag.branch')->get();
//        dd($data);

        return view('bloodbank::test-pending', $data);
    }

    public function search(Request $request)
    {

        $from_date = $request->from_date ? \App\Utils\Helpers::dateNepToEng($request->from_date)->full_date : '';
        $to_date = $request->to_date ? \App\Utils\Helpers::dateNepToEng($request->to_date)->full_date : '';

        if (!$from_date || !$to_date) {
            return \response()->json(['error' => 'Please enter From date and to date']);
        }

        try {
            $components = ComponentDetais::with('component', 'component.bloodbag', 'component.bloodbag.donor', 'component.bloodbag.branch')
                ->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<', $to_date);
//                ->get(); //confusion chha
            if($request->code){
             $components->where('component_name',$request->code);
            }
            $components = $components->get();
            $html = '';
            if ($components) {
                foreach ($components as $component) {
                    $html .= '<tr data-id=' . $component->id . '>';
                    $html.='<td align="center">'. $component->component_name.'</td>';
                    $html.='<td align="center">'. ( $component->component->bloodbag->branch ? $component->component->bloodbag->branch->name :'' ).'</td>';
                    $html.='<td align="center">'. (  $component->component ? $component->component->bag_no :'').'</td>';
                    $html.='<td align="center"></td>';
                    $html.='<td align="center">'.($component->component->bloodbag->donor ? $component->component->bloodbag->donor->fullname :'').'</td>';
                    $html.='<td align="center">'.($component->component->bloodbag->donor ? ($component->component->bloodbag->donor->dob ? \Carbon\Carbon::parse($component->component->bloodbag->donor->dob)->age.'/' :'') :'') . ( $component->component->bloodbag->donor ? $component->component->bloodbag->donor->gender :'').'</td>';
                    $html .= '</tr>';
                }

                return \response()->json($html);
            }
            else{
                return \response()->json(['error','No data available']);
            }

        }catch (\Exception $exception){
            dd($exception);
        }


    }

}
