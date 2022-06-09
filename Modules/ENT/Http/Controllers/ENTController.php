<?php

namespace Modules\ENT\Http\Controllers;

use App\Audiogram;
use App\AudiogramRequest;
use App\AudiometricMasking;
use App\BillingSet;
use App\Code;
use App\CogentUsers;
use App\Complaints;
use App\Departmentbed;
use App\DiagnoGroup;
use App\Encounter;
use App\EntImage;
use App\Exam;
use App\ExamGeneral;
use App\PatFindings;
use App\PatientExam;
use App\PatientInfo;
use App\Test;
use App\User;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use Illuminate\Support\Facades\Validator;

class ENTController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        try {
            $data = [
                'laboratory' => $laboratory = Test::get(),

                'finding' => $finding = Exam::get(),
                'billingset' => $billingset = BillingSet::get(),
                'diagnosisgroup' => DiagnoGroup::select('fldgroupname')->distinct()->get(),
                'diagnosiscategory' => Helpers::getInitialDiagnosisCategory(),
                'patient_status_disabled' => 0,
                'chiefComplationDuration' => Helpers::getChiefComplationDuration(),
                'chiefComplationQuali' => Helpers::getChiefComplationQuali(),
            ];
            $data['complaint'] = $complaint = Cache::remember('conplaints_list', 60 * 60 * 24, function () {
                    return Complaints::get();
                });
            // dd($data['diagnosiscategory']);
            $data['departments'] =  DB::table('tbldepartment')
                ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
                ->where('tbldepartment.fldcateg', 'Patient Ward')
                ->select('tbldepartment.flddept')
                ->groupBy('tbldepartment.flddept')
                ->get();

            $encounter_id_session = Session::get('ent_encounter_id');
            if ($request->has('encounter_id') || $encounter_id_session) {
                if ($request->has('encounter_id'))
                    $encounter_id = $request->get('encounter_id');
                else
                    $encounter_id = $encounter_id_session;

                session(['ent_encounter_id' => $encounter_id]);
                $data['exam'] = $this->_get_exam_data($encounter_id);

                /*create last encounter id*/
                Helpers::entEncounterQueue($encounter_id);
                $encounterIds = Options::get('ent_last_encounter_id');

                $arrayEncounter = unserialize($encounterIds);
                /*create last encounter id*/

                $dataflag = array(
                    'fldinside' => 1,
                );

                Encounter::where('fldencounterval', $encounter_id)->update($dataflag);

                $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();

                $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();

                $data['patient_status_disabled'] = $enpatient->fldadmission == "Discharged" ? 1 : 0;
                $data['examgeneral'] = ExamGeneral::where([
                    'fldencounterval' => $encounter_id,
                    'fldinput' => 'Presenting Symptoms',
                    'fldsave' => '1',
                ])->get();

                foreach ($data['examgeneral'] as &$general) {
                    if ($general->fldreportquanti <= 24)
                        $general->fldreportquanti = "{$general->fldreportquanti} hr";
                    elseif ($general->fldreportquanti > 24 && $general->fldreportquanti <= 720)
                        $general->fldreportquanti = round($general->fldreportquanti / 24, 2) . " Days";
                    elseif ($general->fldreportquanti > 720 && $general->fldreportquanti < 8760)
                        $general->fldreportquanti = round($general->fldreportquanti / 720, 2) . " Months";
                    elseif ($general->fldreportquanti >= 8760)
                        $general->fldreportquanti = round($general->fldreportquanti / 8760) . " Years";
                }

                $patient_id = $enpatient->fldpatientval;
                $data['enable_freetext'] = Options::get('free_text');
                $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
                $data['patient_id'] = $patient_id;
                $data['consultants'] = User::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();
                $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $encounter_id)->where('fldcode', '!=', null)->get();
                $data['allergicdrugs'] = Code::select('fldcodename')->whereNotIn('fldcodename', $patientallergicdrugs)->get();
                $data['patdrug'] = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();

                $end = Carbon::parse($patient->fldptbirday);
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

                if (isset($body_height) && isset($body_weight)) {
                    $hei = ($body_height->fldrepquali / 100); //changing in meter
                    $divide_bmi = ($hei * $hei);
                    if ($divide_bmi > 0) {

                        $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                    }
                }
                $data['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();

                $data['patdiago'] = $patdiago = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
                if (isset($body_height) && isset($body_weight)) {
                    $hei = ($body_height->fldrepquali / 100); //changing in meter
                    $divide_bmi = ($hei * $hei);
                    if ($divide_bmi > 0) {

                        $data['bmi'] = round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                    }
                }
            }

            return view('ent::index', $data);
        } catch (\GearmanException $e) {
        }
    }

    public function resetEncounter()
    {
        Session::forget('ent_encounter_id');
        return redirect()->route('ent');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $encounter_id = Session::get('ent_encounter_id');
        if (!$encounter_id)
            return redirect()->route('ent')->with('error_message', __('Failed to update ent data.'));

        $inputs = $request->all();
        $exams = [
            'Color_Vision',
            'Previous_Glass_Precribtion_(PGP)',
            'Auto_Reaction',
            'Add',
            'Acceptance',
            'Schicmers_Test',
            'K-Reading',
        ];
        $time = date('Y-m-d H:i:s');
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $computer = \App\Utils\Helpers::getCompName();

        try {
            \DB::beginTransaction();

            // Note and advice
            foreach ($inputs['examgeneral'] as $key => $value) {
                $formated_key = ucwords($key);
                \App\ExamGeneral::updateOrCreate([
                    'fldencounterval' => $encounter_id,
                    'fldinput' => $formated_key
                ], [
                    'fldreportquali' => $value,
                    'flduserid' => $userid,
                    'fldtime' => $time,
                    'fldcomp' => $computer,
                    'fldsave' => '1',
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ]);
            }

            $image_ear = '';
            if ($request->image_ear != "") {
                $image_ear = $request->image_ear;
            }

            $image_nose = '';
            if ($request->image_nose != "") {
                $image_nose = $request->image_nose;
            }

            $image_throat = '';
            if ($request->image_throat != "") {
                $image_throat = $request->image_throat;
            }

            $image_tongue = '';
            if ($request->image_tongue != "") {
                $image_tongue = $request->image_tongue;
            }

            if ($request->image_ear != "" || $request->image_nose != "" || $request->image_throat != "" || $request->image_tongue != "") {
                EntImage::updateOrCreate(
                    ['fldencounterval' => $encounter_id],
                    ['image_ear' => $image_ear,
                     'image_nose' => $image_nose,
                     'image_throat' => $image_throat,
                     'image_tongue' => $image_tongue
                    ]
                );
            }

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();

            session()->flash('error_message', __('Failed to update ent data.'));
            return redirect()->route('ent');
        }

        session()->flash('success_message', __('Successfully updated ent data.'));
        return redirect()->route('ent');
    }

    private function _get_exam_data($encounter_id)
    {
        $tblOtherData = \App\ExamGeneral::where('fldencounterval', $encounter_id)
            ->whereIn('fldinput', [
                'Systemic Illiness', 'Current Medication', 'History Past', 'History Family', 'On Examination Right', 'On Examination Left', 'note', 'advice', 'Procedure',
            ])->pluck('fldreportquali', 'fldinput');

        $otherData = [];
        foreach ($tblOtherData as $key => $value) {
            $key = str_replace(' ', '_', $key);
            $otherData[strtolower($key)] = $value;
        }

        $EntImage = EntImage::where('fldencounterval', $encounter_id)->first();

        $audiogramData = [];
        // $audiogramData = Audiogram::where('encounter_id',$encounter_id)->first();
        $maskingData = [];
        // if(isset($audiogramData)){
        //     $audiometricMaskingData = AudiometricMasking::where('audiogram_id', $audiogramData->id)->get();
        //     foreach ($audiometricMaskingData as $masking) {
        //         $maskingData[$masking->masking_type][$masking->ear_side][$masking->frequency_key] = $masking->frequency_value;
        //     }
        // }

        $audiogram_requested = AudiogramRequest::where("encouter_id",$encounter_id)->with('user')->get();

        return compact('maskingData','otherData','audiogramData','EntImage','audiogram_requested');
    }

    public function examgeneral(Request $request)
    {
        $encounter_id = Session::get('ent_encounter_id');
        $time = date('Y-m-d H:i:s');
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $computer = \App\Utils\Helpers::getCompName();

        try {
            foreach ($request->all() as $key => $value) {
                $formated_key = ucwords(str_replace('_', ' ', $key));
                \App\ExamGeneral::updateOrCreate([
                    'fldencounterval' => $encounter_id,
                    'fldinput' => $formated_key,
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ], [
                    'fldreportquali' => $value,
                    'flduserid' => $userid,
                    'fldtime' => $time,
                    'fldcomp' => $computer,
                    'fldsave' => '1',
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ]);
            }
        } catch (Exception $e) {
            session()->flash('success_message', __('Failed to update ent data.'));
            return redirect()->route('ent');
        }

        session()->flash('success_message', __('Successfully updated ent data.'));
        return redirect()->route('ent');
    }

    public function saveAudiogram(Request $request){
        $rules = array(
            'audiometer' => 'required',
            'tester' => 'required',
            'remarks' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Session::flash('display_popup_error_success', true);
            Session::flash('success_message', 'Failed to update audiogram data.');
            return redirect()->route('ent');
        }

        try {
            \DB::beginTransaction();

            $audiogram_request = AudiogramRequest::where('id',$request->audiogram_request_id)->first();
            $audiogram_request->examined_date = date('Y-m-d H:i:s');
            $audiogram_request->examined_by = Auth::guard("admin_frontend")->id();
            $audiogram_request->status = "Done";
            $audiogram_request->save();

            $audiogram_data = Audiogram::where('audiogram_request_id',$request->audiogram_request_id)->first();
            $data = array(
                'audiogram_request_id' => $request->audiogram_request_id,
                'audiometer' => $request->audiometer,
                'tester' => $request->tester,
                'remarks' => $request->remarks
            );
            if(isset($audiogram_data)){
                $audiogram = Audiogram::where('audiogram_request_id',$request->audiogram_request_id)->first();
                $audiogram->update($data);
                $audiogram = $audiogram->id;
            }else{
                $audiogram = Audiogram::insertGetId($data);
            }

            if(isset($request->exam)){
                foreach($request->exam as $mask_key => $audiometric_maskings){
                    if($audiometric_maskings){
                        foreach($audiometric_maskings as $ear_key => $ear_side){
                            if(isset($ear_side)){
                                foreach($ear_side as $frequency_key => $frequency){
                                    if(isset($frequency)){
                                        $masking_data = AudiometricMasking::where([
                                            ['audiogram_id',$audiogram],
                                            ['masking_type',$mask_key],
                                            ['ear_side',$ear_key],
                                            ['frequency_key',$frequency_key]
                                        ])->first();
                                        if(isset($masking_data)){
                                            $masking_data->update(['frequency_value' => $frequency]);
                                        }else{
                                            AudiometricMasking::insert([
                                                'audiogram_id' => $audiogram,
                                                'masking_type' => $mask_key,
                                                'ear_side' => $ear_key,
                                                'frequency_key' => $frequency_key,
                                                'frequency_value' => $frequency
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            // Session::flash('display_popup_error_success', true);
            // Session::flash('success_message', 'Failed to update audiogram data.');
            // return redirect()->route('ent');
            return response()->json([
                'result' => [
                    'status' => false
                ]
            ]);
        }

        $requestData = AudiogramRequest::where('id',$request->audiogram_request_id)->with('user','examiner')->first();
        // Session::flash('display_popup_error_success', true);
        // Session::flash('success_message', 'Successfully updated audiogram data.');
        // return redirect()->route('ent');
        return response()->json([
            'result' => [
                'status' => true,
                'audiogram_request_id' => $request->audiogram_request_id,
                'audiogram_request' => $requestData
            ]
        ]);
    }

    public function requestAudiogram(Request $request){
        $audiogram_request = new AudiogramRequest();
        $audiogram_request->encouter_id = Session::get('ent_encounter_id');
        $audiogram_request->requested_date = date('Y-m-d H:i:s');
        $audiogram_request->requested_by = Auth::guard("admin_frontend")->id();
        $audiogram_request->comments = $request->audiogram_request;
        $audiogram_request->status = "Requested";
        $audiogram_request->created_at = date('Y-m-d H:i:s');
        $audiogram_request->save();
        $routeName = route('ent.audiogram.report',$audiogram_request->id);
        $rowview = '<tr id="req_' . $audiogram_request->id . '">
                          <td>' . $audiogram_request->requested_date . '</td>
                          <td>' . $audiogram_request->user->getFullNameAttribute() . '</td>
                          <td>' . $audiogram_request->comments . '</td>
                          <td>' . $audiogram_request->status . '</td>
                          <td>
                            <i class="fa fa-arrow-circle-right perform-audiogram" data-request="'. $audiogram_request->id .'" aria-hidden="true"></i>
                            <a href="'.$routeName.'" target="_blank"><i class="fas fa-file audiogram-report" aria-hidden="true"></i></a>
                          </td>
                       </tr>';
        return response()->json([
            'success' => [
                'rowview' => $rowview
            ]
        ]);
    }

    public function performAudiogram(Request $request){
        $audiogramRequestData = AudiogramRequest::where('id',$request->requestid)->with('user','examiner')->first();
        $audiogramData = null;
        $maskingData = [];
        if(isset($audiogramRequestData)){
            $audiogramData = Audiogram::where('audiogram_request_id',$audiogramRequestData->id)->first();
        }
        if(isset($audiogramData)){
            $audiometricMaskingData = AudiometricMasking::where('audiogram_id', $audiogramData->id)->get();
            if(count($audiometricMaskingData)>0){
                foreach ($audiometricMaskingData as $masking) {
                    $maskingData[$masking->masking_type][$masking->ear_side][$masking->frequency_key] = $masking->frequency_value;
                }
            }
        }
        return response()->json([
            'success' => [
                'audiogramRequestData' => $audiogramRequestData,
                'audiogramData' => $audiogramData,
                'maskingData' => $maskingData
            ]
        ]);
    }

    public function saveComment(Request $request){
        $encounter_id = Session::get('ent_encounter_id');
        try {
            \DB::beginTransaction();
            $entImageData = EntImage::where("fldencounterval",$encounter_id)->first();
            if(!$entImageData){
                $entImageData = new EntImage();
                $entImageData->fldencounterval = Session::get('ent_encounter_id');
            }
            if($request->commentType == "ear"){
                $entImageData->comment_ear = $request->comment;
            }
            if($request->commentType == "nose"){
                $entImageData->comment_nose = $request->comment;
            }
            if($request->commentType == "throat"){
                $entImageData->comment_throat = $request->comment;
            }
            if($request->commentType == "tongue"){
                $entImageData->comment_tongue = $request->comment;
            }
            $entImageData->save();

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();

            return response()->json([
                'success' => [
                    'status' => false
                ]
            ]);
        }
        return response()->json([
            'success' => [
                'status' => true
            ]
        ]);
    }

    public function audiogramreport($id,Request $request){
        $data['audiogramRequestData'] = AudiogramRequest::where('id',$id)->with('encounter')->first();
        // dd($data['audiogramRequestData']->toArray());
        return view('ent::pdf.audiogram-report',$data);
    }
}
