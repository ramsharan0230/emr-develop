<?php

namespace Modules\MajorProcedure\Http\Controllers;

use App\Anasethesia;
use App\BillingSet;
use App\CogentUsers;
use App\Encounter;
use App\Department;
use App\PatBilling;
use App\Preanaestheticevaluation;
use App\ServiceCost;
use App\PatFindings;
use App\PatGeneral;
use App\PatSubGeneral;
use App\Pathdosing;
use App\PatientExam;
use App\PatientInfo;
use App\Procedure;
use App\Otdetail;
use App\Otconsultant;
use App\Otextraexaminationdetail;
use App\Otasaphysicalstatus;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Utils\Permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\DiagnoGroup;
use App\Otchecklist;
use Cache;
use PHPUnit\Exception;
use Session;

class MajorProcedureController extends Controller
{
    public function getMajorProcedureIndex(Request $request)
    {
        if (Permission::checkPermissionFrontendAdmin('ot')) {
            try {
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
//                        'new_proc_refere' => CogentUsers::select('flduserid', 'username')->where('fldreferral', 1)->get(),
//                        'new_proc_payable' => CogentUsers::select('flduserid', 'username')->where('fldpayable', 1)->get(),
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
                    $patGeneralData = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali', 'flddetail', 'fldtime', 'fldstatus')
                        ->where(['fldinput' => 'Procedures', 'fldencounterval' => $encounter_id])
                        ->with('pathSubGeneral')
                        ->get();
                    /*$totalPatGeneral = [];
                    if ($patGeneralData) {
                        foreach ($patGeneralData as $patkey => $patGeneral) {
                            $patGeneralPlanned = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'fldreportquali', 'flddetail', 'fldtime', 'fldstatus', 'fldsave', 'fldbillingmode')->where('fldid', $patGeneral->fldid)->first();
                            $totalPatGeneral[$patkey] = $patGeneralPlanned;
                        }
                    }*/

                    $data['proceduremajor'] = $patGeneralData;
                     $data['diagnosiscategory'] = $diagnocat;
                    $patFindingMultiple = PatFindings::where('fldencounterval', $encounter_id)
                        ->where(function ($queryNested) {
                            $queryNested->orWhere('fldtype', 'Provisional Diagnosis')
                                ->orWhere('fldtype', 'Allergic Drugs');
                        })
                        ->where('fldsave', 1)
                        ->get();
                    $data['patdiago'] = $patdiago = $patFindingMultiple->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->where('fldencounterval', $encounter_id)->all();
                    // dd($data['patdiago']);
                    $data['patdrug'] = $patdrug = $patFindingMultiple->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->where('fldencounterval', $encounter_id)->all();
                    $data['patientexam'] = $patientexam_findings = PatientExam::where('fldencounterval', $encounter_id)->where('fldinput', 'OPD Examination')->where('fldsave', 1)->get();
                    $data['shiftdepartments'] = Department::where('fldcateg', 'Patient Ward')->get();
                    $data['systolic_bp'] = $systolic_bp = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Systolic BP')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();

                    $data['diasioli_bp'] = $diasioli_bp = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Diastolic BP')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();

                    $data['pulse'] = $pulse = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Pulse Rate')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();

                    $data['temperature'] = $temperature = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Temperature (F)')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();

                    $data['respiratory_rate'] = $respiratory_rate = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Respiratory Rate')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();


                    $data['o2_saturation'] = $o2_saturation = DB::table('tblpatientexam')
                        ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                        ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                        ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'O2 Saturation')
                        ->orderBy('tblpatientexam.fldid', 'desc')->first();
                    $data['minor_procedure'] = ServiceCost::select('fldbillitem')->where('flditemtype','Procedures')->where('fldtarget', 'Minor')->where('fldgroup','General')->get();
                    $data['preanaesthesia'] = $this->_get_preanaesthesia_data($encounter_id);
                    $physicaldata = Otextraexaminationdetail::select('fldvalue')->where('fldtype','Pre Anaesthesia')->where('flditem','Physical Status')->get();
                    $pdata = array();
                    if(isset($physicaldata) and count($physicaldata) > 0){
                        foreach($physicaldata as $pd){
                            $pdata[] = $pd->fldvalue;
                        }
                    }

                    $data['pre_anaes_physicalstatus'] = $pdata;
                    $anaesdata = Otextraexaminationdetail::select('fldvalue')->where('fldtype','Pre Anaesthesia')->where('flditem','Anaesthesia')->get();
                    $andata = array();
                    if(isset($anaesdata) and count($anaesdata) > 0){
                        foreach($anaesdata as $an){
                            $andata[] = $an->fldvalue;
                        }
                    }
                    $data['pre_anaes_anaesthesia'] = $andata;
                    $data['intraoperative'] = $this->_get_intraoperative_data($encounter_id);
                    $intraphysicaldata = Otextraexaminationdetail::select('fldvalue')->where('fldtype','Intra Operative')->where('flditem','Physical Status')->get();
                    $ipdata = array();
                    if(isset($intraphysicaldata) and count($intraphysicaldata) > 0){
                        foreach($intraphysicaldata as $ipd){
                            $ipdata[] = $ipd->fldvalue;
                        }
                    }

                    $data['intra_physicalstatus'] = $ipdata;
                     $intraanaesdata = Otextraexaminationdetail::select('fldvalue')->where('fldtype','Intra Operative')->where('flditem','Anaesthesia')->get();
                    $intrandata = array();
                    if(isset($intraanaesdata) and count($intraanaesdata) > 0){
                        foreach($intraanaesdata as $intan){
                            $intrandata[] = $intan->fldvalue;
                        }
                    }
                     $data['intra_anaesthesia'] = $intrandata;
                     // dd($data['intra_anaesthesia']);
                    $monitoring = Otextraexaminationdetail::select('fldvalue')->where('fldtype','Intra Operative')->where('flditem','Intra Operative Monitoring')->get();
                    $mdata = array();
                    if(isset($monitoring) and count($monitoring) > 0){
                        foreach($monitoring as $m){
                            $mdata[] = $m->fldvalue;
                        }
                    }
                    $data['intra_monitoring'] = $mdata;

                    $otconsultants = Otconsultant::select('fldconsultant')->where('fldencounterval',$encounter_id)->get();
                    $condata = array();
                    if(isset($otconsultants) and count($otconsultants) > 0){
                        foreach($otconsultants as $otc){
                            $condata[] = $otc->fldconsultant;
                        }
                    }
                    $data['intra_consultants'] = $condata;
                    // dd($data['intra_consultants']);
                    $patients = PatientInfo::where('fldpatientval', $patient_id)->first();
                    $end = Carbon::parse($patients->fldptbirday ? $patients->fldptbirday :'') ?? null;
                    $now = Carbon::now();
                    $length = $end->diffInDays($now) ?? null;

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

                    if (isset($body_height) && isset($body_weight)) {
                        $hei = ($body_height->fldrepquali / 100); //changing in meter
                        $divide_bmi = ($hei * $hei);
                        if ($divide_bmi > 0) {
                            $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                        }
                    }
                    $data['diagnosisgroup'] = Cache::remember('diagno_set', 60 * 60 * 24, function () {
                        return DiagnoGroup::select('fldgroupname')->distinct()->get();
                    });
                    $data['preanaethestic'] = Preanaestheticevaluation::where('fldencounterval',$encounter_id)->first();
                    $data['otchecklistdata'] = $otchecklistdata = Otchecklist::where('fldencounterval',$encounter_id)->first();
                    $activeOtChecklistTab = "signin";
                    $data['otchecklistcomp'] = 0;
                    if(isset($otchecklistdata)){
                        if($otchecklistdata->fldsignoutcomp == 1){
                            $activeOtChecklistTab = "signout";
                            $data['otchecklistcomp'] = 1;
                        }else{
                            if($otchecklistdata->fldtimeoutcomp == 1){
                                $activeOtChecklistTab = "signout";
                            }else{
                                if($otchecklistdata->fldsignincomp == 1){
                                    $activeOtChecklistTab = "timeout";
                                }
                            }
                        }
                    }
                    $data['activeOtChecklistTab'] = $activeOtChecklistTab;
                }

                $data['new_proc_refere'] = CogentUsers::select('flduserid', 'username','firstname','middlename','lastname')->where('fldreferral', 1)->get();
                $data['new_proc_payable'] = CogentUsers::select('flduserid', 'username','firstname','middlename','lastname')->where('fldpayable', 1)->get();
                return view('majorprocedure::major-procedure', $data);
            } catch (\GearmanException $e) {
            }
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }

    // pre operative
    // Discussion
    public function preOperativeDiscussion(Request $request)
    {
        try {
            $flditemid = $request->flditemid;
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'flditemid' => $flditemid,
                'fldchapter' => 'Discussion Panel',
                'fldreportquali' => $request->fldreportquali,
                'fldreportquanti' => 0,
                'fldreport' => Null,
                'flduserid' => Helpers::getCurrentUserName(), //admin
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'fldcomp' => $request->fldcomp, //comp01
                'fldsave' => 1, //1
                'flduptime' => Null,
                'xyz' => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );
            $latest_id = PatSubGeneral::insertGetId($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'message' => 'Successfully Inserted',
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => 'Faild To insert.',
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return response()->json([
                'error' => [
                    'message' => __('messages.error'),
                ]
            ]);
        }
    }

    public function getPreOperativeDiscussion()
    {
        $fldencounterval = Input::get('fldencounterval');
        $flditemid = Input::get('flditemid');
        $get_list_detail = PatSubGeneral::where([
            ['fldencounterval', $fldencounterval],
            ['fldchapter', 'Discussion Panel'],
            ['flditemid', $flditemid]
        ])->select('fldreportquali')->get();
        return response()->json($get_list_detail);
    }


    public function preOperativeDiscussionTxtArea(Request $request)
    {
        try {
            $flditemid = $request->flditemid;
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'flditemid' => $flditemid,
                'fldchapter' => 'Pre-Operative Discussion',
                'fldreportquali' => Null,
                'fldreportquanti' => 0,
                'fldreport' => $request->fldreport,
                'flduserid' => Helpers::getCurrentUserName(), //admin
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'fldcomp' => $request->fldcomp, //comp01
                'fldsave' => 1, //1
                'flduptime' => Null,
                'xyz' => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );
            $latest_id = PatSubGeneral::insertGetId($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'message' => 'Successfully Inserted',
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => 'Faild To Insert.'
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return response()->json([
                'error' => [
                    'message' => __('messages.error'),
                ]
            ]);
        }
    }

    public function getPreOperativeDiscussionTxtArea()
    {
        $fldencounterval = Input::get('fldencounterval');
        $flditemid = Input::get('flditemid');
        $get_list_detail = PatSubGeneral::where([
            ['fldencounterval', $fldencounterval],
            ['fldchapter', 'Pre-Operative Discussion'],
            ['flditemid', $flditemid]
        ])->orderBy('fldid', 'DESC')->select('fldreport')->first();
        return response()->json($get_list_detail);
    }

    public function getPatFinding()
    {
        $fldencounterval = Input::get('fldencounterval');
        $get_list_detail = PatFindings::where([
            ['fldencounterval', $fldencounterval],
            ['fldsave', 1]
        ])
            ->where('fldtype', 'Final Diagnosis')
            ->select('fldcode')->get();
        return response()->json($get_list_detail);
    }

    // Clinical Note
    public function insertClinicalIndication(Request $request)
    {
        try {
            $flditemid = $request->flditemid;
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'flditemid' => $flditemid,
                'fldchapter' => $request->fldchapter,
                'fldreportquali' => $request->fldreportquali,
                'fldreportquanti' => 0,
                'fldreport' => Null,
                'flduserid' => Helpers::getCurrentUserName(), //admin
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'fldcomp' => $request->fldcomp, //comp01
                'fldsave' => 1, //1
                'flduptime' => Null,
                'xyz' => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );
            $latest_id = PatSubGeneral::insertGetId($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('majorprocedure');
        }
    }

    public function getClinicalIndication()
    {
        $fldencounterval = Input::get('fldencounterval');
        $flditemid = Input::get('flditemid');
        $fldchapter = Input::get('fldchapter');
        $get_list_detail = PatSubGeneral::where([
            ['fldencounterval', $fldencounterval],
            ['fldchapter', $fldchapter],
            ['flditemid', $flditemid]
        ])->select('fldreportquali', 'fldtime', 'fldid')->get();
        return response()->json($get_list_detail);
    }

    public function insertClinicalNote(Request $request)
    {
        try {
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'flditemid' => $request->flditemid,
                'fldchapter' => $request->fldchapter,
                'fldreportquali' => $request->fldreportquali,
                'fldreportquanti' => 0,
                'fldreport' => $request->fldreport,
                'flduserid' => Helpers::getCurrentUserName(), //admin
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'fldcomp' => $request->fldcomp, //comp01
                'fldsave' => 1, //1
                'flduptime' => Null,
                'xyz' => 0,
            );
            $table = PatSubGeneral::where([
                                            'fldid' => $request->fldid,
                                            'flditemid' => $request->flditemid,
                                            'fldencounterval' => $request->fldencounterval
                                        ])->first();
            $latest_id = $table->update($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('majorprocedure');
        }
    }

    public function getClinicalNote()
    {
        $fldencounterval = Input::get('fldencounterval');
        $fldid = Input::get('fldid');
        $flditemid = Input::get('flditemid');
        $fldchapter = Input::get('fldchapter');
        $get_list_detail = PatSubGeneral::where([
            ['fldid', $fldid],
            ['fldencounterval', $fldencounterval],
            ['fldchapter', $fldchapter],
            ['flditemid', $flditemid]
        ])->select('fldid', 'fldreport', 'fldreportquali', 'fldchapter')->first();
        return response()->json($get_list_detail);
    }

    public function resetEncounter()
    {
        Session::forget('major_procedure_encounter_id');
        return redirect()->route('majorprocedure');
    }

    public function updateNewProcedure(Request $request)
    {
        try {
            $fldid = $request->fldid;
            dd($request->all());
            $form_data = $this->_get_form_data($request);
            // $data = array(
            //     'fldencounterval' => $request->fldencounterval,
            //     'fldnewdate' => $request->fldnewdate,
            //     'fldreportquali' => $request->fldreportquali,
            //     'flduserid' => Helpers::getCurrentUserName(), //admin
            //     'fldcomp' => $request->fldcomp, //comp01
            //     'fldsave' => 1, //1
            //     'flduptime' => now(),
            //     'xyz' => 0,
            // );
            $table = PatGeneral::where([['fldid', $fldid]])->first();
            $latest_id = $table->update($form_data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('majorprocedure');
        }
    }

    private function _get_form_data($request)
    {
        $date = $request->get('fldnewdate');
        $date = Helpers::dateNepToEng($date);
        return [
            'fldencounterval' => $request->fldencounterval,
            'fldnewdate' => $date->full_date,
            'fldreportquali' => $request->fldreportquali,
            'flduserid' => Helpers::getCurrentUserName(), //admin
            'fldcomp' => $request->fldcomp, //comp01
            'fldsave' => 1, //1
            'flduptime' => now(),
            'xyz' => 0,
        ];
    }

    public function getSelectedData()
    {
        $fldid = Input::get('fldid');
        $fldencounterval = Input::get('fldencounterval');
        $get_list_detail = PatGeneral::where([
            ['fldencounterval', $fldencounterval],
            ['fldid', $fldid]
        ])->select('fldid', 'fldnewdate', 'flditem', 'fldreportquali', 'fldtime', 'fldsave', 'fldbillingmode', 'flddetail', 'fldstatus')->get();
        return response()->json($get_list_detail);
    }

    public function reloadTable()
    {
        $fldencounterval = Input::get('fldencounterval');
        $get_list_detail = PatGeneral::where('fldencounterval', $fldencounterval)
            ->select('fldid', 'fldnewdate', 'flditem', 'fldreportquali', 'fldtime', 'fldsave', 'fldbillingmode', 'flddetail', 'fldstatus')->get();
        return response()->json($get_list_detail);
    }

    public function insertNewProcedureFreeText(Request $request)
    {
        try {
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'flditemid' => $request->flditemid,
                'fldchapter' => 'Components',
                'fldreportquali' => $request->fldreportquali,
                'fldreportquanti' => 0,
                'fldreport' => Null,
                'fldtime' => now(),
                'flduserid' => Helpers::getCurrentUserName(), //admin
                'fldcomp' => $request->fldcomp, //comp01
                'fldsave' => 1, //1
                'flduptime' => Null,
                'xyz' => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );
            $latest_id = PatSubGeneral::insertGetId($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('majorprocedure');
        }
    }

    public function getComponents()
    {
        $flditemid = Input::get('flditemid');
        $fldencounterval = Input::get('fldencounterval');
        $get_list_detail = PatSubGeneral::where([
            ['fldencounterval', $fldencounterval],
            ['flditemid', $flditemid]
        ])->select('fldreportquali')->get();
        return response()->json($get_list_detail);
    }

    public function insertVariables(Request $request)
    {
        try {
            $data = array(
                'flditem' => $request->flditem,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );
            $latest_id = Procedure::insertGetId($data);
            $latest_variable = Procedure::where('fldid', $latest_id)->first();
            $var_row = '<option value="' . $latest_variable->flditem . '">' . $latest_variable->flditem . '</option>';

            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'message' => 'Successfully Added Variable',
                        'appendVariable' => $var_row
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('majorprocedure');
        }
    }

    public function getVariables()
    {
        $get_list_detail = Procedure::all();
        return response()->json($get_list_detail);
    }

    public function deleteVariables(Request $request)
    {
        try {
            $variables = $request->id;
            foreach ($variables as $var) {
                $latest_id = Procedure::where('fldid', $var)->delete();
            }
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'message' => 'Successfully Deleted Variable'
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('majorprocedure');
        }
    }

    public function insertDetails(Request $request)
    {
        try {
            $data = array(
                'flddetail' => $request->flddetail,
                'fldsave' => 1, //1
                'flduptime' => now(),
                'xyz' => 0,
            );
            $checkifexist = PatGeneral::where([
                                                'fldid' => $request->fldid,
                                                'fldencounterval' => $request->fldencounterval
                                            ])->first();
            if ($checkifexist == null) {
                return response()->json([
                    'error' => [
                        'message' => "Match Do Not Found",
                    ]
                ]);
            }
            $latest_id = $checkifexist->update($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'message' => 'Inserted Successfully',
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('majorprocedure');
        }
    }

    public function getDetails()
    {
        $fldid = Input::get('fldid');
        $fldencounterval = Input::get('fldencounterval');
        $get_list_detail = PatGeneral::where([
            ['fldencounterval', $fldencounterval],
            ['fldid', $fldid]
        ])->select('flddetail')->get();
        return response()->json($get_list_detail);
    }

    public function getInitialProcedureCategoryAjaxs()
    {
        $html = '';
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
        if (isset($data) and count($data) > 0) {
            foreach ($data as $d) {
                $html .= '<tr><td><input type="checkbox" class="dccat" name="dccat" id="' . $d['code'] . '" value="' . $d['code'] . '"/>';
                $html .= '<label for="' . $d['code'] . '" class="remove_some_css"></label>';
                $html .= '</td><td>' . $d['code'] . '</td><td>' . $d['name'] . '</td></tr>';
            }
        } else {
            $html = '<tr><td colspan="3">No Procedure Available</td></tr>';
        }

        echo $html;
    }

    public function getProcedureByCodes(Request $request)
    {
        $html = '';
        if ($request->get('term')) {

            $handle = fopen(storage_path('upload/icd10pcs_order.csv'), 'r');
            $key = $request->get('term');
            $data = [];
            $parent_category = "";
            while ($csvLine = fgetcsv($handle, 1000, ";")) {
                if (substr($csvLine[1], 0, strlen($key)) == $key) {
                    if (strlen($csvLine[1]) == 3) {
                        $parent_category = $csvLine[3];
                    } else {
                        $data[$csvLine[1]] = $csvLine[3];
                    }
                }
            }
            if (count($data) < 1) {
                $data[$key] = $parent_category;
            }

            sort($data);
            if (isset($data) and count($data) > 0) {

                foreach ($data as $d) {
                    $html .= '<tr><td><input type="checkbox" class="procedureOnText" name="procedureOnText" id="' . $d . '" value="' . $d . '"/>';
                    $html .= '<label for="' . $d . '" class="remove_some_css"></label>';
                    $html .= '</td><td style="width:100%;">' . $d . '</td</tr>';
                }
            } else {
                $html = '<tr colspan="2"><td>No Procedure Available for Procedure Code ' . $key . '</td></tr>';
            }
            echo $html;
        } else {
            echo $html = '<tr colspan="2"><td>No Procedure Available</td></tr>';
        }
    }

    public function insertAnaesthesiaVariables(Request $request)
    {
        try {
            $data = array(
                'flditem' => $request->flditem,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );
            $latest_id = Anasethesia::insertGetId($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('majorprocedure');
        }
    }

    public function getAnaesthesiaVariables()
    {
        $get_list_detail = Anasethesia::all();
        return response()->json($get_list_detail);
    }

    public function deleteAnaesthesiaVariables(Request $request)
    {
        try {
            $table = Anasethesia::where('fldid', $request->fldid);
            $latest_id = $table->delete();
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'message' => "Inserted Successfully"
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('majorprocedure');
        }
    }

    public function insertPersonnel(Request $request)
    {
        try {
            $data = array(
                'fldencounterval' => $request->fldencounterval,
                'flditemid' => $request->flditemid,
                'fldchapter' => $request->fldchapter,
                'fldreportquali' => $request->fldreportquali,
                'fldreportquanti' => 0,
                'fldreport' => $request->fldreport,
                'fldtime' => now(),
                'flduserid' => Helpers::getCurrentUserName(), //admin
                'fldcomp' => $request->fldcomp, //comp01
                'fldsave' => 1, //1
                'flduptime' => Null,
                'xyz' => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );
            $latest_id = PatSubGeneral::insertGetId($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('majorprocedure');
        }
    }

    public function getData()
    {
        $flditemid = Input::get('flditemid');
        $fldencounterval = Input::get('fldencounterval');
        $fldchapter = ['Anaesthetist', 'Assistant', 'Count Nurse', 'Scrub Nurse', 'Surgeon'];
        $get_list_detail = PatSubGeneral::whereIn('fldchapter', $fldchapter)
            ->where([
                ['fldencounterval', $fldencounterval],
                ['flditemid', $flditemid]
            ])
            ->select('fldtime', 'fldreportquali', 'fldreport', 'fldchapter')->get();
        return response()->json($get_list_detail);
    }

    public function getSelectedItems()
    {
        $fldid = Input::get('fldid');
        $get_list_detail = PatBilling::where('fldid', $fldid)->select('fldtaxper', 'flddiscamt', 'flditemrate', 'flditemno', 'flddiscper', 'fldorduserid', 'fldordcomp', 'fldordtime', 'fldalert', 'fldtarget', 'fldtaxamt')->get();
        return response()->json($get_list_detail);
    }

    public function insertOtherItems(Request $request)
    {
        try {

            // calcluting itemAmt
            $fldditemamt = 0;
            if ($request->flddiscper !== "0") {
                $afterDisc = $request->flddiscamt / 100 * $request->flditemrate;
                $totalamt = $afterDisc * $request->flditemqty;
            }
            if (isset($totalamt)) {
                if ($request->fldtaxamt !== "0") {
                    $includingTax = $request->fldtaxamt / 100 * $totalamt;
                    $fldditemamt = $includingTax;
                } else {
                    $fldditemamt = $totalamt;
                }
            } else {
                if ($request->fldtaxamt !== "0") {
                    $includingTax = $request->fldtaxamt / 100 * $request->flditemrate;
                    $fldditemamt = $includingTax;
                } else {
                    $fldditemamt = $request->flditemrate * $request->flditemqty;
                }
            }

            $data = array(
                "fldencounterval" => $request->fldencounterval,
                "fldbillingmode" => 'General',
                "flditemtype" => 'Other Items',
                "flditemno" => $request->flditemno,
                "flditemname" => $request->flditemname,
                "flditemrate" => $request->flditemrate,
                "flditemqty" => $request->flditemqty,
                "fldtaxper" => $request->fldtaxper,
                "flddiscper" => $request->flddiscper,
                "fldtaxamt" => $request->fldtaxamt,
                "flddiscamt" => $request->flddiscamt,
                "fldditemamt" => $fldditemamt,
                "fldorduserid" => $request->fldorduserid,
                "fldordtime" => $request->fldordtime,
                "fldordcomp" => $request->fldordcomp,
                "flduserid" => Null,
                "fldtime" => Null,
                "fldcomp" => Null,
                "fldsave" => 0,
                "fldbillno" => Null,
                "fldparent" => 0,
                "fldprint" => 0,
                "fldstatus" => "Punched",
                "fldalert" => $request->fldalert,
                "fldtarget" => $request->fldtarget,
                "fldretqty" => 0,
                "fldsample" => "Waiting",
                "xyz" => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            );
            $latest_id = PatBilling::insertGetId($data);
            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Saved Successfully.');
                return response()->json([
                    'success' => [
                        'id' => $latest_id
                    ]
                ]);
            } else {
                Session::flash('display_popup_error_success', true);
                Session::flash('error_message', 'Sorry! something went wrong');
                return response()->json([
                    'error' => [
                        'message' => __('messages.error')
                    ]
                ]);
            }
        } catch (\GearmanException $e) {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return redirect()->route('majorprocedure');
        }
    }

    public function getOtherItemsData()
    {
        $fldencounterval = Input::get('fldencounterval');
        $get_list_detail = PatBilling::where([
            'fldencounterval' => $fldencounterval,
            'fldsave' => 0,
            'fldprint' => 0,
            'flditemtype' => 'Other Items',
            'fldstatus' => 'Punched'
        ])
            ->where('fldordcomp', 'like', '%' . Helpers::getCompName() . '%')
            ->select('fldid', 'fldordtime', 'flditemtype', 'flditemno', 'flditemname', 'flditemrate', 'fldtaxper', 'flddiscper', 'fldditemamt')
            ->get();
        return response()->json($get_list_detail);
    }

    // Pre-Operative Phramacy
    public function getAllPhramacy()
    {
        $get_patdosing = Pathdosing::where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
            ->where('fldfreq', '!=', 'stat')
            ->where('fldfreq', '!=', 'PRN')
            ->orderBy('fldid', 'DESC')
            ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->get();
        return response()->json($get_patdosing);
    }

    // Post-Operative Phramacy
    public function getAllPhramacyPostOp()
    {
        $get_patdosing = Pathdosing::where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
            ->where('fldfreq', '!=', 'stat')
            ->where('fldfreq', '!=', 'PRN')
            ->orderBy('fldid', 'DESC')
            ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->get();
        return response()->json($get_patdosing);
    }

    // Operation Phramacy
    public function getAllPhramacyOperation()
    {
        $get_patdosing = Pathdosing::where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
            ->where('fldfreq', '!=', 'stat')
            ->where('fldfreq', '!=', 'PRN')
            ->orderBy('fldid', 'DESC')
            ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->get();
        return response()->json($get_patdosing);
    }

    // Anaesthesia Phramacy
    public function getAllPhramacyAnaesthesia()
    {
        $get_patdosing = Pathdosing::where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldsave_order' => 1,
            'flditemtype' => 'Medicines',
            'flddispmode' => 'IPD',
        ])->where('fldroute', '!=', 'fluid')
            ->where('fldfreq', '!=', 'stat')
            ->where('fldfreq', '!=', 'PRN')
            ->orderBy('fldid', 'DESC')
            ->select('fldid', 'fldstarttime', 'fldroute', 'flddose', 'flditem', 'fldfreq', 'flddays', 'fldcurval', 'fldstatus')
            ->get();
        return response()->json($get_patdosing);
    }

    // Examination
    // Pre-Operative, Post-Operative, Operation, Anaeshtesia
    // public function getExaminationList()
    // {
    //     $get_patientexam = PatientExam::where([
    //         'fldencounterval' => Input::get('fldencounterval'),
    //         'fldinput' => Input::get('fldinput'),
    //         'fldsave' => 1
    //     ])
    //         ->select('fldid', 'fldhead')
    //         ->get();
    //     return response()->json($get_patientexam);
    // }

    public function getExaminationData()
    {
        $get_patientexam = PatientExam::where([
            'fldencounterval' => Input::get('fldencounterval'),
            'fldid' => Input::get('fldid'),
            'fldsave' => 1
        ])
            ->select('fldhead', 'fldabnormal', 'fldrepquali', 'fldtime', 'flduserid', 'fldcomp')
            ->get();
        return response()->json($get_patientexam);
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

    // /**
    //  * @return array|\Illuminate\Http\JsonResponse
    //  */
    // public function savePreAnaesthesia(Request $request){
    //     dd($request->all());
    //     try{
    //         $encounter = $request->fldencounterval;
    //         // $data = array();
    //         // $pdata = array();
    //         // $adata = array();
    //        foreach($request->all() as $key=>$value){

    //             if ($key != '_token' && $key != 'physical_status' && $key != 'anaesthesia_type' && $key != 'fldtype' && $key != 'fldencounterval') {
    //                 if(!is_null($value)){
    //                     $formated_key = ucwords(str_replace('_', ' ', $key));
    //                     \App\Otdetail::updateOrCreate([
    //                         'fldencounterval' => $encounter,
    //                         'fldtype' =>'Pre Anaesthesia',
    //                         // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
    //                     ], [
    //                         'flditem' => $formated_key,
    //                         'fldvalue' => $value,
    //                     ]);
    //                     // $data['fldtype'] = 'Pre Anaeshtesia';
    //                     // $data['flditem'] = $formated_key;
    //                     // $data['fldvalue'] = $value;
    //                     // $data['fldencounterval'] = $encounter;
    //                     // Otdetail::create($data);
    //                 }

    //             }
    //        }

    //             $physical_status = $request->physical_status;
    //             if(isset($physical_status) and !empty($physical_status)){
    //                 foreach($physical_status as $ps){
    //                     \App\Otasaphysicalstatus::updateOrCreate([
    //                         'fldencounterval' => $request->fldencounterval,
    //                         'fldtype' => 'Pre Anaesthesia',
    //                         // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
    //                     ], [
    //                         'fldvalue' => $ps
    //                     ]);
    //                     // $
    //                     // $pdata['fldtype'] = 'Pre Anaesthesia';
    //                     // $pdata['fldvalue'] = $ps;
    //                     // $pdata['fldencounterval'] = $request->fldencounterval;
    //                     // // dd($pdata);
    //                     // Otasaphysicalstatus::create($pdata);
    //                 }
    //             }

    //             $anaesthesia_type = $request->anaesthesia_type;
    //            if(isset($anaesthesia_type) and !empty($anaesthesia_type)){
    //                 foreach($anaesthesia_type as $at){
    //                     if(!is_null($at)){
    //                         \App\Otanaesthesia::updateOrCreate([
    //                             'fldencounterval' => $request->fldencounterval,
    //                             'fldtype' => 'Pre Anaesthesia',
    //                             // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
    //                         ], [
    //                             'fldvalue' => $at
    //                         ]);
    //                         // $adata['fldtype'] = 'Pre Anaeshtesia';
    //                         // $adata['fldvalue'] = $at;
    //                         // $adata['fldencounterval'] = $request->fldencounterval;
    //                         //  Otanaesthesia::create($adata);
    //                     }

    //                 }

    //            }

    //     }catch(\Exception $e){
    //         dd($e);
    //     }
    // }

    /**
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function savePreAnaesthesia(Request $request){
        // dd($request->all());
        try{
            $encounter = $request->fldencounterval;
            // $data = array();
            // $pdata = array();
            // $adata = array();
           foreach($request->all() as $key=>$value){

                if ($key != '_token' && $key != 'physical_status' && $key != 'anaesthesia_type' && $key != 'fldtype' && $key != 'fldencounterval') {
                    if(!is_null($value)){
                        $formated_key = ucwords(str_replace('_', ' ', $key));

                        $data['fldtype'] = 'Pre Anaesthesia';
                        $data['flditem'] = $formated_key;
                        $data['fldvalue'] = $value;
                        $data['fldencounterval'] = $encounter;
                        $currentdata = Otdetail::where('fldencounterval',$encounter)->where('fldtype','Pre Anaesthesia')->where('flditem',$formated_key)->first();
                        // dd($currentdata);
                        if($currentdata){
                            $currentdata->update($data);
                        }else{
                            Otdetail::create($data);
                        }

                    }

                }
           }

                $physical_status = $request->physical_status;
                $currentstatusdata = Otextraexaminationdetail::where('fldtype','Pre Anaesthesia')->where('flditem','Physical Status')->where('fldencounterval',$encounter)->delete();
                if(isset($physical_status) and !empty($physical_status)){

                    foreach($physical_status as $ps){

                        // $
                        $pdata['fldtype'] = 'Pre Anaesthesia';
                        $pdata['fldvalue'] = $ps;
                        $pdata['flditem'] = 'Physical Status';
                        $pdata['fldencounterval'] = $request->fldencounterval;
                        // $currentstatusdata = Otasaphysicalstatus::where('fldtype','Pre Anaesthesia')->where('fldencounterval',$encounter)->where('fldvalue',$ps)->delete();
                        Otextraexaminationdetail::create($pdata);

                    }
                }

                $anaesthesia_type = $request->anaesthesia_type;
                $currentanaesdata = Otextraexaminationdetail::where('fldtype','Pre Anaesthesia')->where('flditem','Anaesthesia')->where('fldencounterval',$encounter)->delete();
               if(isset($anaesthesia_type) and !empty($anaesthesia_type)){

                    foreach($anaesthesia_type as $at){
                        if(!is_null($at)){
                            $adata['fldtype'] = 'Pre Anaesthesia';
                            $adata['fldvalue'] = $at;
                            $adata['flditem'] = 'Anaesthesia';
                            $adata['fldencounterval'] = $request->fldencounterval;
                            // $currentanaesdata = Otanaesthesia::where('fldtype','Pre Anaesthesia')->where('fldencounterval',$encounter)->where('fldvalue',$at)->delete();
                            Otextraexaminationdetail::create($adata);
                        }

                    }

               }

        }catch(\Exception $e){
            dd($e);
        }
    }

    /**
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function saveIntraOperativeDetail(Request $request){
        // dd($request->all());
        try{
            $encounter = $request->fldencounterval;
            // $data = array();
            // $pdata = array();
            // $adata = array();
           foreach($request->all() as $key=>$value){

                if ($key != '_token' && $key != 'revised_pac' && $key != 'consultant_name' && $key != 'intraoperative_monitoring' && $key != 'fldencounterval' && $key != 'anaesthesia_type') {
                    if(!is_null($value)){
                        $formated_key = ucwords(str_replace('_', ' ', $key));

                        $data['fldtype'] = 'Intra Operative';
                        $data['flditem'] = $formated_key;
                        $data['fldvalue'] = $value;
                        $data['fldencounterval'] = $encounter;
                        $currentdata = Otdetail::where('fldencounterval',$encounter)->where('fldtype','Intra Operative')->where('flditem',$formated_key)->first();
                        // dd($currentdata);
                        if($currentdata){
                            $currentdata->update($data);
                        }else{
                            Otdetail::create($data);
                        }

                    }

                }
           }

                $physical_status = $request->revised_pac;
                $currentstatusdata = Otextraexaminationdetail::where('fldtype','Intra Operative')->where('flditem','Physical Status')->where('fldencounterval',$encounter)->delete();
                if(isset($physical_status) and !empty($physical_status)){

                    foreach($physical_status as $ps){

                        // $
                        $pdata['fldtype'] = 'Intra Operative';
                        $pdata['fldvalue'] = $ps;
                        $pdata['flditem'] = 'Physical Status';
                        $pdata['fldencounterval'] = $request->fldencounterval;

                        Otextraexaminationdetail::create($pdata);

                    }
                }

                $anaesthesia_type = $request->anaesthesia_type;
                $currentanaesdata = Otextraexaminationdetail::where('fldtype','Intra Operative')->where('flditem','Anaesthesia')->where('fldencounterval',$encounter)->delete();
               if(isset($anaesthesia_type) and !empty($anaesthesia_type)){

                    foreach($anaesthesia_type as $at){
                        if(!is_null($at)){
                            $adata['fldtype'] = 'Intra Operative';
                            $adata['fldvalue'] = $at;
                            $adata['flditem'] = 'Anaesthesia';
                            $adata['fldencounterval'] = $request->fldencounterval;

                            Otextraexaminationdetail::create($adata);
                        }

                    }

               }
               $operative_monitoring = $request->intraoperative_monitoring;
               $currentanaesdata = Otextraexaminationdetail::where('fldtype','Intra Operative')->where('flditem','Intra Operative Monitoring')->where('fldencounterval',$encounter)->delete();
               if(isset($operative_monitoring) and !empty($operative_monitoring)){

                    foreach($operative_monitoring as $om){
                        if(!is_null($om)){
                            $omdata['fldtype'] = 'Intra Operative';
                            $omdata['fldvalue'] = $om;
                            $omdata['flditem'] = 'Intra Operative Monitoring';
                            $omdata['fldencounterval'] = $request->fldencounterval;

                            Otextraexaminationdetail::create($omdata);
                        }

                    }

               }

               $consultants = $request->consultant_name;
               if(isset($consultants) and !empty($consultants)){
                $deleteconsults = Otconsultant::where('fldencounterval', $encounter)->delete();
                    foreach($consultants as $c){
                       $cdata['fldencounterval'] = $encounter;
                       $cdata['fldconsultant'] = $c;
                       Otconsultant::create($cdata);
                    }
               }

        }catch(\Exception $e){
            dd($e);
        }
    }

    public function _get_preanaesthesia_data($encounter_id)
    {
        $tblOtherData = \App\Otdetail::where('fldencounterval', $encounter_id)->where('fldtype','Pre Anaesthesia')
            ->whereIn('flditem', [
                'Comorbidities', 'Anaesthesia Procedure', 'Airway Examination', 'Airway Issue', 'Acceptance', 'Stratification Risk', 'Advice','Extra Anaesthesia', 'Peripheral Nerve Block Detail'
            ])->pluck('fldvalue','flditem');
        // dd($tblOtherData);
        $otherData = [];
        foreach ($tblOtherData as $key => $value) {
            $key = str_replace(' ', '_', $key);
            $otherData[strtolower($key)] = $value;
        }

        return compact('otherData');
    }
    public function _get_intraoperative_data($encounter_id)
    {
        $tblOtherData = \App\Otdetail::where('fldencounterval', $encounter_id)->where('fldtype','Intra Operative')
            ->whereIn('flditem', [
                'Shifted To', 'Post Operative Analgesia', 'Recovery', 'Incomplete Recovery Detail', 'Peripheral Nerve Block Detail', 'Condition Before Shifting', 'Patient Change Condition', 'Eventful Changes In Monitoring', 'Surgery Performed', 'Resident Name', 'Intraoperative Events', 'Post Operative Events','Extra Anaesthesia'
            ])->pluck('fldvalue', 'flditem');
        $otherData = [];
        foreach ($tblOtherData as $key => $value) {
            $key = str_replace(' ', '_', $key);
            $otherData[strtolower($key)] = $value;
        }

        return compact('otherData');
    }

    public function getOtChecklistData(Request $request){

    }

    public function saveOtSignin(Request $request){
        try{
            $data = [
                "fldencounterval" => $request->encounter_id,
                "fldpatientconfirm" => $request->fldpatientconfirm,
                "fldsitemarked" => $request->fldsitemarked,
                "fldanaesthesiachecked" => $request->fldanaesthesiachecked,
                "fldoxyfunct" => $request->fldoxyfunct,
                "fldhasallergy" => $request->fldhasallergy,
                "fldairwayrisk" => $request->fldairwayrisk,
                "fldbloodlossrisk" => $request->fldbloodlossrisk,
                "fldsigninuser" => Helpers::getCurrentUserName(),
                "fldsignintime" => now(),
                "fldsignincomp" => 1,
            ];
            $chkOtchecklistdata = Otchecklist::where('fldencounterval',$request->encounter_id)->first();
            if(isset($chkOtchecklistdata)){
                Otchecklist::where('fldencounterval', $request->encounter_id)->update($data);
            }else{
                Otchecklist::create($data);
            }
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function saveOtTimeout(Request $request){
        try{
            $data = [
                "fldencounterval" => $request->encounter_id,
                "fldconfirmteam" => $request->fldconfirmteam,
                "fldverbalconfirm" => $request->fldverbalconfirm,
                "fldsurgeonreview" => $request->fldsurgeonreview,
                "fldanaesthesianreview" => $request->fldanaesthesianreview,
                "fldnursingreview" => $request->fldnursingreview,
                "fldantibioticprophyloxis" => $request->fldantibioticprophyloxis,
                "fldimagingdisplay" => $request->fldimagingdisplay,
                "fldtimeoutuser" => Helpers::getCurrentUserName(),
                "fldtimeouttime" => now(),
                "fldtimeoutcomp" => 1,
            ];
            $chkOtchecklistdata = Otchecklist::where('fldencounterval',$request->encounter_id)->first();
            if(isset($chkOtchecklistdata)){
                Otchecklist::where('fldencounterval', $request->encounter_id)->update($data);
            }else{
                Otchecklist::create($data);
            }
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function saveOtSignout(Request $request){
        try{
            $data = [
                "fldencounterval" => $request->encounter_id,
                "fldprocrecord" => $request->fldprocrecord,
                "fldinstrucorrect" => $request->fldinstrucorrect,
                "fldspecimentlabelled" => $request->fldspecimentlabelled,
                "fldequipprobaddressed" => $request->fldequipprobaddressed,
                "fldkeyconcernreview" => $request->fldkeyconcernreview,
                "fldsignoutuser" => Helpers::getCurrentUserName(),
                "fldsignouttime" => now(),
                "fldsignoutcomp" => 1,
            ];
            $chkOtchecklistdata = Otchecklist::where('fldencounterval',$request->encounter_id)->first();
            if(isset($chkOtchecklistdata)){
                Otchecklist::where('fldencounterval', $request->encounter_id)->update($data);
            }else{
                Otchecklist::create($data);
            }
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => FALSE,
                'message' => __('messages.error'),
            ]);
        }
    }

    public function savePreAnaethesticEvaluation(Request $request)
    {

        try {
            DB::beginTransaction();

            $data = [
                "fldencounterval" => $request->get('encounter_id'),
                "past_anaesthetic_date" => $request->get('past_anaesthetic_date') ? Helpers::dateNepToEng($request->get('past_anaesthetic_date'))->full_date : null,
                "surgerical_procedure" => $request->get('surgerical_procedure'),
                "hospital_name" => $request->get('hospital_name'),
                "anesthesia_type" => $request->get('anesthesia_type'),
                "reaction" => $request->get('reaction'),
                "has_family_reaction" => $request->get('has_family_reaction'),
                "smoke" => $request->get('smoke'),
                "smoke_description" => $request->get('smoke_description'),
                "have_cough" => $request->get('have_cough'),
                "regular_medicine" => $request->get('regular_medicine'),
                "drug_user" => $request->get('drug_user'),
                "have_allergies" => $request->get('have_allergies'),
                "allergy_description" => $request->get('allergy_description'),
                "loose_teeth" => $request->get('loose_teeth'),
                "has_pregnancy" => $request->get('has_pregnancy'),
                "pregnancy_lmp" => $request->get('pregnancy_lmp'),
                "physical_examination" => $request->get('physical_examination'),
                "tmj" => $request->get('tmj'),
                "neck_mobility" => $request->get('neck_mobility'),
                "neck_mobility_two" => $request->get('neck_mobility_two'),
                "thyromental_distance" => $request->get('thyromental_distance'),
//                "rt_lung" => $request->get('rt_lung'),
//                "lt_lung" => $request->get('lt_lung'),
                "rate" => $request->get('rate'),
                "rhythm" => $request->get('rhythm'),
                "additional_sound" => $request->get('additional_sound'),
                "rt_air_entry" => $request->get('rt_air_entry'),
                "rt_rales" => $request->get('rt_rales'),
                "rt_ronchi" => $request->get('rt_ronchi'),
                "rt_wheeze" => $request->get('rt_wheeze'),
                "lt_air_entry" => $request->get('lt_air_entry'),
                "lt_rales" => $request->get('lt_rales'),
                "lt_ronchi" => $request->get('lt_ronchi'),
                "lt_wheeze" => $request->get('lt_wheeze'),
                "heamoglobin" => $request->get('heamoglobin'),
                "tc_dc" => $request->get('tc_dc'),
                "p" => $request->get('p'),
                "l" => $request->get('l'),
                "e" => $request->get('e'),
                "m" => $request->get('m'),
                "b" => $request->get('b'),
                "bt_ct" => $request->get('bt_ct'),
                "pt" => $request->get('pt'),
                "toal_protien" => $request->get('toal_protien'),
                "billiribin" => $request->get('billiribin'),
                "urine_analysis" => $request->get('urine_analysis'),
                "elctorlytes" => $request->get('elctorlytes'),
                "k" => $request->get('k'),
                "blood_urea" => $request->get('blood_urea'),
                "creatioin" => $request->get('creatioin'),
                "blood_sugar" => $request->get('blood_sugar'),
                "blood_group" => $request->get('blood_group'),
                "rh_factor" => $request->get('rh_factor'),
                "cross_matched_blood" => $request->get('cross_matched_blood'),
                "x_ray" => $request->get('x_ray'),
                "x_ray_findings" => $request->get('x_ray_findings'),
                "ecg" => $request->get('ecg'),
                "ecg_finding" => $request->get('ecg_finding'),
                "echocardiograohy" => $request->get('echocardiograohy'),
                "echocardiograohy_finding" => $request->get('echocardiograohy_finding'),
                "asa_grading" => $request->get('asa_grading'),
            ];
            Preanaestheticevaluation::create($data);
            DB::commit();
            return response()->json(['message'=> 'Data Saved Successfully']);
//            $laboratory_data = [
//
//                "heamoglobin" => $request->get('heamoglobin'),
//                "tc_dc" => $request->get('tc_dc'),
//                "p" => $request->get('p'),
//                "l" => $request->get('l'),
//                "e" => $request->get('e'),
//                "m" => $request->get('m'),
//                "b" => $request->get('b'),
//                "bt_ct" => $request->get('bt_ct'),
//                "pt" => $request->get('pt'),
//                "toal_protien" => $request->get('toal_protien'),
//                "billiribin" => $request->get('billiribin'),
//                "urine_analysis" => $request->get('urine_analysis'),
//                "elctorlytes" => $request->get('elctorlytes'),
//                "k" => $request->get('k'),
//                "blood_urea" => $request->get('blood_urea'),
//                "creatioin" => $request->get('creatioin'),
//                "blood_sugar" => $request->get('blood_sugar'),
//                "blood_group" => $request->get('blood_group'),
//                "rh_factor" => $request->get('rh_factor'),
//                "cross_matched_blood" => $request->get('cross_matched_blood'),
//                "x_ray" => $request->get('x_ray'),
//                "x_ray_findings" => $request->get('x_ray_findings'),
//                "ecg" => $request->get('ecg'),
//                "ecg_finding" => $request->get('ecg_finding'),
//                "echocardiograohy" => $request->get('echocardiograohy'),
//                "echocardiograohy_finding" => $request->get('echocardiograohy_finding'),
//                "asa_grading" => $request->get('asa_grading'),
//                "encounter_id" => $request->get('encounter_id'),
//
//            ];



        } catch (Exception $exception) {
            DB::rollBack();
//            return response()->json(['error'=>'Something Went Wrong']);
            dd($exception);
        }
    }
}


