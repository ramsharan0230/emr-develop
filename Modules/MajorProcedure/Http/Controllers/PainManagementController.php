<?php

namespace Modules\MajorProcedure\Http\Controllers;

use App\Anasethesia;
use App\BillingSet;
use App\CogentUsers;
use App\Encounter;
use App\Department;
use App\PatBilling;
use App\PatFindings;
use App\PatGeneral;
use App\PatSubGeneral;
use App\Pathdosing;
use App\PatientExam;
use App\PatientInfo;
use App\Procedure;
use App\Otdetail;
use App\Otextraexaminationdetail;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Utils\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\DiagnoGroup;
use Cache;
use Session;

class PainManagementController extends Controller
{
	public function index(Request $request){
		
			try{
				 $encounter_id_session = Session::get('major_procedure_encounter_id');
                $data = [];
                $data['patient_status_disabled'] = 0;
                $data['digno_group'] = Cache::remember('diagno_set', 60 * 60 * 24, function () {
                    return DiagnoGroup::select('fldgroupname')->distinct()->get();
                });
                $diagnocat = $this->getInitialDiagnosisCategory();
                $data['diagnosiscategory'] = $diagnocat;
                $patFindingMultiple = PatFindings::where('fldencounterval', $encounter_id_session)
                    ->where(function ($queryNested) {
                        $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                            ->orWhere('fldtype', 'Allergic Drugs');
                    })
                    ->where('fldsave', 1)
                    ->get();
                $data['patdiago'] = $patdiago = $patFindingMultiple->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->where('fldencounterval', $encounter_id_session)->all();
                // dd($data['patdiago']);
                $data['patdrug'] = $patdrug = $patFindingMultiple->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->where('fldencounterval', $encounter_id_session)->all();
                if ($request->has('encounter_id') || $encounter_id_session) {
                    $data['departments'] = DB::table('tbldepartment')
                        ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
                        ->where('tbldepartment.fldcateg', 'Patient Ward')
                        ->orWhere('tbldepartment.fldcateg', 'Emergency')
                        ->select('tbldepartment.flddept')
                        ->groupBy('tbldepartment.flddept')
                        ->get();

                    if ($request->has('encounter_id'))
                        $encounter_id = $request->get('encounter_id');
                    else
                        $encounter_id = $encounter_id_session;

                    $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();

                    $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();

                    /*department change enabled*/
                    /*if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
                        if (!in_array($data['enpatient']->fldcurrlocat, $current_user->department->pluck('flddept')->toArray())) {
                            Session::flash('display_popup_error_success', true);
                            Session::flash('error_message', 'You are not authorized to view this patients information.');
                            return redirect()->route('admin.dashboard');
                        }
                    }*/

                    session(['major_procedure_encounter_id' => $encounter_id]);
                    /*create last encounter id*/
                    Helpers::MajorencounterQueue($encounter_id);
                    $encounterIds = Options::get('major_procedure_last_encounter_id');
                    $arrayEncounter = unserialize($encounterIds);
                    /*create last encounter id*/

                    $dataflag = array(
                        'fldinside' => 1,
                    );

                    Encounter::where('fldencounterval', $encounter_id)->update($dataflag);
                    

                    $data['billingset'] = $billingset = BillingSet::get();
                    
                    // dd($data);
                    $patient_id = $enpatient->fldpatientval;
                    $data = [
                        'patient_status_disabled' => $enpatient->fldadmission == "Discharged" ? 1 : 0,
                        'patient' => PatientInfo::where('fldpatientval', $patient_id)->first(),
                        'enpatient' => $enpatient,
                        'patient_id' => $patient_id,
                        'billingset' => $billingset = BillingSet::get(),
                        'enable_freetext' => 1,
                        'new_proc_refere' => CogentUsers::select('flduserid', 'username')->where('fldreferral', 1)->get(),
                        'new_proc_payable' => CogentUsers::select('flduserid', 'username')->where('fldpayable', 1)->get(),
                        'variables' => Procedure::all(),
                        'consultants' => CogentUsers::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get(),
                        'other_items' => PatBilling::where([
                            'fldencounterval' => $encounter_id,
                            'fldsave' => 0,
                            'fldprint' => 0,
                            'flditemtype' => 'Other Items'
                        ])->where('fldordcomp', 'like', '%' . Helpers::getCompName() . '%')
                            ->where('fldstatus', '!=', 'Punched')
                            ->get(),
                    ];
                    $patients = PatientInfo::where('fldpatientval', $patient_id)->first();
                    $end = Carbon::parse($patients->fldptbirday);
                    $now = Carbon::now();
                    $length = $end->diffInDays($now);

                    if ($length < 1) {
                        $data['years'] = 'Hours';
                        $data['hours'] = $end->diffInHours($now);
                    }
                    if ($length > 0 && $length <= 30)
                        $data['years'] = 'Days';
                    if ($length > 30 && $length <= 365)
                        $data['years'] = 'Months';
                    if ($length > 365)
                        $data['years'] = 'Years';

                    $data['body_weight'] = $body_weight = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_weight')->orderBy('fldid', 'desc')->first();
                    // dd($body_weight);
                    $data['body_height'] = $body_height = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'body_height')->orderBy('fldid', 'desc')->first();

                    if (isset($body_height)) {
                        if ($body_height->fldrepquali <= 100) {
                            $data['heightrate'] = 'cm';
                            $data['height'] = $body_height->fldrepquali;
                        } else {
                            $data['heightrate'] = 'm';
                            $data['height'] = $body_height->fldrepquali / 100;
                        }
                    } else {
                        $data['heightrate'] = 'cm';
                        $data['height'] = '';
                    }

                    $data['bmi'] = '';
                    $data['comorbidities'] = Otdetail::select('fldvalue')->where('fldencounterval',$encounter_id)->where('fldtype','Pre Anaesthesia')->where('flditem','Comorbidities')->first();
                    $data['paindata'] = $this->_get_pain_data($encounter_id);
                    $painmanagement = Otextraexaminationdetail::select('fldvalue')->where('fldtype','Pain Management')->where('flditem','Pain')->get();
                    $padata = array();
                    if(isset($painmanagement) and count($painmanagement) > 0){
                        foreach($painmanagement as $pm){
                            $pdata[]=$pm->fldvalue;
                        }
                    }
                    $data['painmanagement'] = $pdata;
                    // dd($data['painmanagement']);
                    if (isset($body_height) && isset($body_weight)) {
                        $hei = ($body_height->fldrepquali / 100); //changing in meter
                        $divide_bmi = ($hei * $hei);
                        if ($divide_bmi > 0) {
                            $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                        }
                    }
                }
                return view('majorprocedure::pain-management', $data);
			}catch(\Exception $e){
				dd($e);
			}
		
	}
	/**
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getInitialDiagnosisCategory()
    {
        try {
            $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
            $data = [];
            while ($csvLine = fgetcsv($handle, 1000, ";")) {
                if (isset($csvLine[1]) && strlen($csvLine[1]) == 3) {
                    $data[] = [
                        'code' => trim($csvLine[1]),
                        'name' => trim($csvLine[3]),
                    ];
                }
            }
            //sort($data);
            usort($data, function ($a, $b) {
                return $a['name'] <=> $b['name'];
            });
            // dd($data);
            return $data;
        } catch (\Exception $exception) {
            return [];
        }
    }
    /**
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function savePaindetail(Request $request)
    {
        // dd($request->all());
        try{
            $encounter = $request->fldencounterval;
            foreach($request->all() as $key=>$value){
                if ($key != 'pain_management' && $key !='fldencounterval') {
                    if(!is_null($value)){
                        $formated_key = ucwords(str_replace('_', ' ', $key));
                        
                        $data['fldtype'] = 'Pain Management';
                        $data['flditem'] = $formated_key;
                        $data['fldvalue'] = $value;
                        $data['fldencounterval'] = $encounter;
                        $currentdata = Otdetail::where('fldencounterval',$encounter)->where('fldtype','Pain Management')->where('flditem',$formated_key)->first();
                        // dd($currentdata);
                        if($currentdata){
                            $currentdata->update($data);
                        }else{
                            Otdetail::create($data);
                        }
                        
                    }
                  }  
                
           }
           $pain_management = $request->pain_management;
            $currentdata = Otextraexaminationdetail::where('fldtype','Pain Management')->where('flditem','Pain')->where('fldencounterval',$encounter)->delete();
            if(isset($pain_management) and !empty($pain_management)){
                
                foreach($pain_management as $p){
                    
                    // $
                    $pdata['fldtype'] = 'Pain Management';
                    $pdata['fldvalue'] = $p;
                    $pdata['flditem'] = 'Pain';
                    $pdata['fldencounterval'] = $request->fldencounterval;
                    
                    Otextraexaminationdetail::create($pdata);
                    
                }
            }
        }catch(\Exception $e){
            dd($e);
        }
    }
    public function _get_pain_data($encounter_id)
    {
        $tblOtherData = \App\Otdetail::where('fldencounterval', $encounter_id)->where('fldtype','Pain Management')
            ->whereIn('flditem', [
                'Pain Score', 'Follow Up Date'
            ])->pluck('fldvalue', 'flditem');
        $otherData = [];
        foreach ($tblOtherData as $key => $value) {
            $key = str_replace(' ', '_', $key);
            $otherData[strtolower($key)] = $value;
        }

        return compact('otherData');
    }
}