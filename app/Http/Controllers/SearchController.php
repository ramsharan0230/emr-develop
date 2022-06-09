<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchPatient(Request $request){
        try{
        if(isset($request->key) && $request->key != ''){
            return \DB::table('meta_data_patient')->select('fldpatientval','MetaData')
                ->where('fullname', 'like',  $request->key . '%')
                ->orWhere('fldpatientval', 'like',  $request->key . '%')
                ->orWhere('fldencounterval', 'like',  $request->key . '%')
                ->orWhere('fldptcontact', 'like',  $request->key . '%')
                ->get();
        }else{
            return [
                'status' =>false
            ];
        }
        } catch (\Exception $e){
            return response()->json([
                'error' => [
                    'status' => false,
                    'message' => $e->getMessage(),
                ]
            ]);
        }

    }

    public function getPatient(Request $request){
        try{
            if(isset($request->patient_val) && $request->patient_val != ''){
                $result =  \DB::table('meta_data_patient')->select('fldpatientval',\DB::raw('count(fldencounterval) AS total_encounter'),'patient_detail','fldencounterval','fldptbirday')->where('fldpatientval', $request->patient_val)->groupBy('fldpatientval')->orderBy('fldtime', 'DESC')->get();

                $html = '';
                $html .='<table id="search-patient-table"
                           data-resizable="true"
                           data-show-toggle="true">
                        <thead class="thead-light">
                            <th>S.N.</th>
                            <th>Patient Id</th>
                            <th>Patient Details</th>
                            <th>Total Encounter</th>
                            <th>Actions</th>
                        </thead>
                        <tbody >';





                if(isset($result) and count($result) > 0) {

                    foreach ($result as $k => $data)
                        $date = $data->fldptbirday;
                    $age = \Carbon\Carbon::parse($date)->age;
                        $sn = $k+1;
                        $html .='<tr>';
                        $html .='<td> '.$sn.' </td>';
                        $html .='<td>'.$data->fldpatientval.'</td>';
                        $html .='<td>'.str_replace('/',$age.' Y '.'/ ',$data->patient_detail).'</td>';
                    $html .='<td>'.$data->total_encounter.'</td>';
                        $html .='<td> <div class="dropdown" id="search-dropdown">
                                                    <button onclick = "triggerButton()" class="btn btn-primary dropdown-toggle dropdown-toggle trigger_button"
                                                        type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        Action
                                                    </button>
  <div id="search-dropdown-menu" class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    if (\App\Utils\Permission::checkPermissionFrontendAdmin('cashier-form')){
                        $html.='<a class="dropdown-item" href="'.\URL::to('/billing/service?req_segment=billing&encounter_id=&patient_details='.$data->fldpatientval).'">Cashier form </a>';
                    }

                    if (\App\Utils\Permission::checkPermissionFrontendAdmin('dispensing-form')){
                        $html.=' <a class="dropdown-item" href="'.\URL::to('/dispensingForm?req_segment=dispensingForm&encounter_id=&patient_details='.$data->fldpatientval).'">Dispensing form </a>';
                    }

                    if (\App\Utils\Permission::checkPermissionFrontendAdmin('deposit-form')){
                        $html.='    <a class="dropdown-item" href="'.\URL::to('/depositForm?encounter_id='.$data->fldencounterval).'">Deposit Form</a>';
                    }
                    $html.= '<a class="dropdown-item" href="'.\URL::to('/registrationform?encounter_id='.$data->fldencounterval).'">Registration Form</a>
    <a class="dropdown-item" href="'.\URL::to('/billing/service/billing-report?encounter_id='.$data->fldencounterval).'">
Billing Report Form</a>
  </div>
</div></td>';
                        $html .='</tr>';

                    }
                }
                $html .=' </tbody></table>';
                return response()->json([
                    'success' => [
                        'status' => true,
                        'html' => $html,
                    ]
                ]);
            }
         catch (\Exception $e){
            return response()->json([
                'error' => [
                    'status' => false,
                    'message' => $e->getMessage(),
                ]
            ]);
        }

    }


}
