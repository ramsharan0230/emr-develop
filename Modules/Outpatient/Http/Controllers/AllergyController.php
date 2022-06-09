<?php

namespace Modules\Outpatient\Http\Controllers;

use App\BillingSet;
use App\Complaints;
use App\Consult;
use App\Encounter;
use App\Exam;
use App\ExamGeneral;
use App\PatFindings;
use App\Pathdosing;
use App\PatientExam;
use App\PatientInfo;
use App\Test;
use App\Code;
use App\DiagnoGroup;
use App\Utils\Helpers;
use Carbon\Carbon;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AllergyController extends Controller
{
    function insert_allergydrugstore(Request $request)
    {
        // dd($request);
        try {
            $allergicdrugs = $request->allergydrugs; 
            // dd($allergicdrugs);
            $mytime = Carbon::now();

            if (isset($allergicdrugs) && count($allergicdrugs) > 0) {
                foreach ($allergicdrugs as $ad) {
                    // echo $ad; exit;
                    $data['fldencounterval'] = $request->patient_id;
                    $data['fldtype'] = 'Allergic Drugs';
                    $data['fldcode'] = $ad;
                    $data['fldcodeid'] = NULL;
                    $data['flduserid'] = Helpers::getCurrentUserName();
                    $data['fldtime'] = $mytime->toDateTimeString();
                    $data['fldcomp'] = Helpers::getCompName();
                    $data['fldsave'] = 1;
                    $data['xyz'] = 0;
                    $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                    PatFindings::insert($data);
                }
                $patdrug = PatFindings::where('fldencounterval', $request->patient_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
                $html = '';
                if(isset($patdrug) and count($patdrug) > 0){
                    foreach($patdrug as $value){
                        $html .='<option value="'.$value->fldid.'">'.$value->fldcode.'</option>';
                    }
                }
                echo $html; exit;
                // session()->flash('success_message', __('Allergic Drugs Added.'));

                // return redirect()->back();
            } else {
                session()->flash('error_message', __('Error While Adding Allergic Drugs'));

                return redirect()->back();
            }
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Allergic Drugs'));

            return redirect()->back();
        }
    }

    function deletepatfinding()
    {
        // echo $_POST['ids']; exit;
        try {

             $ids = $_POST['ids'];
             // echo $ids; exit;
             $finalids = explode(',', $ids);
            foreach($finalids as $id){
                $datas = array(
                'fldsave' => 0,
                'xyz' => 0
                );
                PatFindings::where('fldid', $id)->update($datas);
            } 
            
            $data['error'] = 0;

            return $data;
        } catch (\Exception $e) {
            // dd($e);
            $data['error'] = 1;
           
            return $data;
        }
    }

    public function searchDrugs()
    {
        $html = '';
        // $patientallergicdrugs = '';
        $searchtext = $_GET['term'];
        $patient_id = $_GET['patient_id'];
        $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $patient_id)->where('fldcode','!=',null)->get();
        $searchedDrugs = Code::where('fldcodename', 'like', $searchtext . '%')->whereNotIn('fldcodename', $patientallergicdrugs)->get();
        if (isset($searchedDrugs) and count($searchedDrugs) > 0) {
            foreach ($searchedDrugs as $ad) {
                $html .= '<li class="list-group-item"><input type="checkbox" value="' . $ad->fldcodename . '" class="fldcodename" name="allergydrugs[]" /> ' . $ad->fldcodename . '</li>';
            }
        } else {
            $html = '<li class="list-group-item">No Drugs Available For' . $searchtext . '</li>';
        }
        echo $html;
    }
    public function getAllDrugs()
    {
        $html = '';
        $patient_id = $_GET['patient_id'];
        // $patientallergicdrugs = '';
        $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $patient_id)->where('fldcode','!=',null)->get();
        $searchedDrugs = Code::select('fldcodename')->whereNotIn('fldcodename', $patientallergicdrugs)->get();
        if (isset($searchedDrugs) and count($searchedDrugs) > 0) {
            foreach ($searchedDrugs as $ad) {
                $html .= '<li class="list-group-item"><input type="checkbox" value="' . $ad->fldcodename . '" class="fldcodename" name="allergydrugs[]" />  ' . $ad->fldcodename . '</li>';
            }
        } else {
            $html = '<li class="list-group-item">No Drugs Available For' . $searchtext . '</li>';
        }
        echo $html;
    }

     public function getAllergyfreetext(Request $request){
        // echo $request->encounterId; exit;
        $request->validate([
            'encounterId' => 'required',
        ]);
        $encounter_id = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounterId'] = $request->encounterId;
        $data['encounter'] = Encounter::select('fldpatientval', 'flduserid', 'fldrank')
            ->where('fldencounterval', $request->encounterId)
            ->with('patientInfo')
            ->get();
        
        $html = view('outpatient::dynamic-views.allergy-freetext', $data)->render();
        return $html;
    }

    public function saveAllergyCustom(Request $request){
        try {
            // dd($request); 
            if($request->custom_allergy !=''){
                $mytime = Carbon::now();
                $data['fldencounterval'] = $request->encounter;
                $data['fldtype'] = $request->fldinput;
                $data['fldcode'] = $request->custom_allergy;
                $data['fldcodeid'] = NULL;
                $data['flduserid'] =Helpers::getCurrentUserName();
                $data['fldtime'] = $mytime->toDateTimeString();
                $data['fldcomp'] = Helpers::getCompName();
                $data['fldsave'] = 1;
                $data['xyz'] = 0;
                $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
                PatFindings::insert($data);
                $html = '';
                $patdrug = PatFindings::where('fldtype',$request->fldinput)->where('fldencounterval', $request->encounter)->where('fldsave', 1)->get();
                // dd($patdrug); exit;
                if(isset($patdrug) and !empty($patdrug)){
                    foreach ($patdrug as $key => $pat) {
                        $html .= '<option value="'.$pat->fldid.'">'.$pat->fldcode.'</option>';

                    }
                }
                echo $html; exit;
            }
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Allergic Drugs'));

            return redirect()->back();
        }
    }

}
