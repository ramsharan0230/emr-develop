<?php

namespace Modules\Neuro\Http\Controllers;

use App\BillingSet;
use App\CogentUsers;
use App\Complaints;
use App\Consult;
use App\Departmentbed;
use App\DiagnoGroup;
use App\Encounter;
use App\Exam;
use App\ExamGeneral;
use App\GCS;
use Cache;
use App\OpdNeuro;
use App\PatientExam;
use App\PatientInfo;
use App\Test;
use App\User;
use App\Utils\Helpers;
use App\Utils\Options;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OpdNeuroController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function home(){
        return view('neuro::opd.opdneuro');

    }

    public function index(Request  $request)
    {


        try {

            $data = array();
            $data['departments'] = DB::table('tbldepartment')
                ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
                ->where('tbldepartment.fldcateg', 'Patient Ward')
                ->select('tbldepartment.flddept')
                ->groupBy('tbldepartment.flddept')
                ->get();



            $data['laboratory'] = $laboratory = Test::get();
            $data['complaint'] = $complaint = Complaints::get();
            $data['finding'] = $finding = Exam::get();
            $data['billingset'] = $billingset = BillingSet::get();
            $data['diagnosisgroup'] = DiagnoGroup::select('fldgroupname')->distinct()->get();
            $diagnocat = $this->getInitialDiagnosisCategory();
            $data['diagnosiscategory'] = $diagnocat;
            $encounter_id_session = Session::get('opdneuro_encounter_id');
            $data['patient_status_disabled'] = 0;
//
            $data['encounter_no'] = null;
////        dd($request->all());
            if ($request->has('encounter_id') || $encounter_id_session) {
//
                if ($request->has('encounter_id'))
                    $encounter_id = $request->get('encounter_id');
                else
                    $encounter_id = $encounter_id_session;
//
                session(['opdneuro_encounter_id' => $encounter_id]);
//
                /*create last encounter id*/
                Helpers::encounterQueue($encounter_id);
                $encounterIds = Options::get('last_encounter_id');
//
                $arrayEncounter = unserialize($encounterIds);
                /*create last encounter id*/
                $dataflag = array(
                    'fldinside' => 1,
                );
//
                Encounter::where('fldencounterval', $encounter_id)->update($dataflag);
//
                $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
//
                $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
//
//                /*department change enabled*/
//                /*if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
//                    if (!in_array($data['enpatient']->fldcurrlocat, $current_user->department->pluck('flddept')->toArray())) {
//                        Session::flash('display_popup_error_success', true);
//                        Session::flash('error_message', 'You are not authorized to view this patients information.');
//                        Session::forget('neuro_encounter_id');
//                        return redirect()->route('admin.dashboard');
//                    }
//                }*/
//
                $data['patient_status_disabled'] = $enpatient->fldadmission == "Discharged" ? 1 : 0;
//
//                //            dd($enpatient);
                $data['enable_freetext'] = Options::get('free_text');
                $patient_id = $enpatient->fldpatientval;
//                // echo $patient_id; exit;
                $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
//
                $data['patient_id'] = $patient_id;
//
//
                $data['plannedConsultants'] = Consult::where('fldencounterval', $encounter_id)->where('fldstatus', 'Planned')->get();
//
//                //select fldrepquanti from tblpatientexam where fldencounterval=$encounter_id and fldhead='Body Weight' and fldtype='Quantitative' and fldsave=$encounter_id
                $data['patientexam'] = $patientexam_findings = PatientExam::where('fldencounterval', $encounter_id)->where('fldinput', 'OPD Examination')->where('fldsave', 1)->get();
//
//
                $data['consultants'] = User::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();



                // For complaints

                $data['complaint'] = $complaint = Cache::remember('conplaints_list', 60 * 60 * 24, function () {
                    return Complaints::get();
                });

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


//
                $end = Carbon::parse($patient->fldptbirday);
                $now = Carbon::now();
//
//
                $length = $end->diffInDays($now);
//
                if ($length < 1) {
//
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
                $data['encounter_no'] = $encounter_id ?? null;
//
                return view('neuro::opd.opdneuro', $data);
            }

        }catch (\Exception $exception)
        {
          dd($exception);
        }

    }


    public function resetEncounter()
    {
        Session::forget('opdneuro_encounter_id');
        return redirect()->route('opdneuro.home');
    }


    /**
     * Function getting intitial diagnosis list from csv
     */
    public function getInitialDiagnosisCategory()
    {
        try {
            $handle = fopen(storage_path('app/public/data/icd10cm_order.csv'), 'r');
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
            return response()->json(['status' => 'success', 'data' => $data]);

        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'data' => []]);
        }

    }


    /** Function for creating oPD neuro record */

    public function store(Request  $request)
    {

        try {
            DB::beginTransaction();


            #sabai data insert gareko
            $input = 'Examination';
            $fldtype = 'Quantitative';
            $fldencounterval =   $request->get('encounter_id') ?? null;
            $hospital_department_id = Helpers::getUserSelectedHospitalDepartmentIdSession() ?? null;
            $flduserid = Helpers::getCurrentUserName() ?? null;
            $fldcomp = Helpers::getCompName() ?? null;
            $fldsave         = True;
            $xyz             = False;


            //insertion of Vitals data
            if ($request->get('syst_bp') != null) {
                $this->insertVitals($fldencounterval, ['fldhead' => 'Systolic BP', 'fldrepquali' => $request->get('syst_bp')]);
            }
            //insertion of Pulse rate data
            if ($request->get('pulse_rate') != null) {
                $this->insertVitals($fldencounterval, ['fldhead' => 'Pulse Rate', 'fldrepquali' => $request->get('pulse_rate')]);
            }

            if ($request->get('dyst_bp') != null) {
                $this->insertVitals($fldencounterval, ['fldhead' => 'Diastolic BP', 'fldrepquali' => $request->get('dyst_bp')]);
            }

            if ($request->get('spo') != null) {
                $this->insertVitals($fldencounterval, ['fldhead' => 'O2 Saturation', 'fldrepquali' => $request->get('spo')]);
            }

            if ($request->get('temp') != null) {
                $this->insertVitals($fldencounterval, ['fldhead' => 'Temperature (F)', 'fldrepquali' => $request->get('temp')]);
            }
            if ($request->get('respiratory') != null) {
                $this->insertVitals($fldencounterval, ['fldhead' => 'Respiratory', 'fldrepquali' => $request->get('respiratory')]);
            }




            #GCS insert gareko
            $gcs = [
                'fldencounterval' => $request->encounter_id,
                'fldinput' => 'Examination',
                'fldtype' => 'Qualitative',
                'fldhead' => 'Glasgrow Coma Scale(GCS)',
                'fldsysconst' => 'Glassgrow_coma_scale',
                'fldrepquali' => json_encode([
                    'Eye Response' => $request->get('gcs_e'),
                    'Verbal Response' => $request->get('gcs_v'),
                    'Motor Response' => $request->get('gcs_m'),
                ]),

                'fldrepquanti' =>($request->get('gcs_e'))+ ($request->get('gcs_v')) + ($request->get('gcs_m')) ?? null,

                'fldtime' => config('constants.current_date_time')
            ];

            $detail = PatientExam::where('fldencounterval', $request->encounter_id)->where('fldinput', 'Examination')->where('fldhead', 'Glasgrow Coma Scale(GCS)')->orderBy('fldid', 'DESC')->first();

            if (!empty($detail))
                PatientExam::where([['fldid', $detail->fldid]])->update($gcs);
            else
                $gcs['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
            PatientExam::insert($gcs);



            $data =[
                #MMSE both input ra radio wala
                ['fldencounterval'=>$fldencounterval, 'fldinput' =>$input, 'fldtype' => $fldtype,
                    'hospital_department_id' => $hospital_department_id,'flduserid' => $flduserid, 'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave, 'xyz' => $xyz, 'fldhead' => 'MMSE', 'fldmethod' => 'Regular', 'fldrepquali'=> $request->get('mmse'),
                    'fldrepquanti'=> $request->get('mmse'),'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>null,
                    ],
//
                ['fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => $fldtype,
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'MMSE',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=> $request->get('mmse_radio'),
                    'fldrepquanti'=> $request->get('mmse_radio'),
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>null,
                    ],

                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Cranial Nerves',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('cranial_nerves_left'),
                                                'RIGHT:'=>$request->get('cranial_nerves_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
                ],


                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Nystagmus',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('nystagmus_left'),
                        'RIGHT:'=>$request->get('nystagmus_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],

                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Upper Proximal Motor Power',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('proxim_right'),
                        'RIGHT:'=>$request->get('proxim_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Upper Distal Motor Power',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('distal_left'),
                        'RIGHT:'=>$request->get('distal_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Sensory Function C5',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('c_five_left'),
                        'RIGHT:'=>$request->get('c_five_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Sensory Function C6',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('c_six_left'),
                        'RIGHT:'=>$request->get('c_six_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Sensory Function C7',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('c_seven_left'),
                        'RIGHT:'=>$request->get('c_seven_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Sensory Function C8',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('c_eight_left'),
                        'RIGHT:'=>$request->get('c_eight_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Finger-Nose Test',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('finger_nose_left'),
                        'RIGHT:'=>$request->get('finger_nose_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Bicep Jerk',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('bicep_jerk_left'),
                        'RIGHT:'=>$request->get('bicep_jerk_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Tricep Jerk',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('tricep_jerk_left'),
                        'RIGHT:'=>$request->get('tricep_jerk_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Tricep Jerk',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('tricep_jerk_left'),
                        'RIGHT:'=>$request->get('tricep_jerk_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Supinator Jerk',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('supi_jerk_left'),
                        'RIGHT:'=>$request->get('supi_jerk_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'FLAIR',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('flair_left'),
                        'RIGHT:'=>$request->get('flair_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'FABER',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('faber_left'),
                        'RIGHT:'=>$request->get('faber_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Lower Proximal Motor Power',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('proxim_left_heel'),
                        'RIGHT:'=>$request->get('proxim_right_heel') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Lower Distal Motor Power',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('distal_left_heel'),
                        'RIGHT:'=>$request->get('distal_right_heel') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'EHL',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('ehl_left'),
                        'RIGHT:'=>$request->get('ehl_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'FHL',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('fhl_left'),
                        'RIGHT:'=>$request->get('fhl_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Sensory Function L2',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('l_two_left'),
                        'RIGHT:'=>$request->get('l_two_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Sensory Function L3',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('l_three_left'),
                        'RIGHT:'=>$request->get('l_three_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Sensory Function L4',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('l_four_left'),
                        'RIGHT:'=>$request->get('l_four_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Sensory Function L5',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('l_five_left'),
                        'RIGHT:'=>$request->get('l_five_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Sensory Function S1',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('s_one_left'),
                        'RIGHT:'=>$request->get('s_one_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Sensory Function S2',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('s_two_left'),
                        'RIGHT:'=>$request->get('s_two_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Heel-Shin Test',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('heel_shin_left'),
                        'RIGHT:'=>$request->get('heel_shin_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Ankle Jerk',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('ankel_jerk_left'),
                        'RIGHT:'=>$request->get('ankel_jerk_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Straight Leg Raise Test',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('slr_left'),
                        'RIGHT:'=>$request->get('slr_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Plantar Response',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('planter_response_left'),
                        'RIGHT:'=>$request->get('planter_response_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Ankle Jerk',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('knee_jerk_left'),
                        'RIGHT:'=>$request->get('knee_jerk_right') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
                    'fldoption'=>'Left and Right',
//                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Cervical Spine',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>$request->get('cervical_Spine'),
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
//                    'fldoption'=>'Left and Right',
                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Thoracic Spine',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>$request->get('thoracic_Spine'),
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
//                    'fldoption'=>'Left and Right',
                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Lumbar Spine',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>$request->get('lumber_Spine'),
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
//                    'fldoption'=>'Left and Right',
                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Sacrococcygeal Spine',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>$request->get('sacrococcygeal_spine'),
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
//                    'fldoption'=>'Left and Right',
                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Upper Limn ',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('left_upper_limbs'),
                        'RIGHT:'=>$request->get('right_upper_limbs') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
//                    'fldoption'=>'Left and Right',
                    'fldoption'=>'Single Selection',
                ],
                [
                    'fldencounterval'=>$fldencounterval,
                    'fldinput' =>$input,
                    'fldtype' => 'Qualitative',
                    'hospital_department_id' => $hospital_department_id,
                    'flduserid' => $flduserid,
                    'fldcomp' => $fldcomp,
                    'fldsave' => $fldsave,
                    'xyz' => $xyz,
                    'fldhead' => 'Lower Limb ',
                    'fldmethod' => 'Regular',
                    'fldrepquali'=>json_encode(['LEFT:'=>$request->get('left_lower_limbs'),
                        'RIGHT:'=>$request->get('right_lower_limbs') ]) ,
                    'fldrepquanti'=> false,
                    'fldtime' => config('constants.current_date_time'),
//                    'fldoption'=>'Left and Right',
                    'fldoption'=>'Single Selection',
                ],
            ];

            PatientExam::insert($data);
            DB::commit();

            return redirect()->back()->with('success_message', 'Information saved successfully');

        }catch (\Exception $exception){
            DB::rollBack();
            dd($exception);
            return redirect()->back()->with('error_message', 'Unable to save record, Please try again');
        }






    }


    private function insertVitals($fldencounterval, $mergeData)
    {
        $vitals = [

            'fldencounterval' => $fldencounterval,

            'fldinput' => 'Examination',

            'fldtype' => 'Quantitative',
            'fldsysconst' => NULL,
            'fldmethod' => 'Manual',
            'fldfilepath' => NULL,

            'flduserid' => Helpers::getCurrentUserName() ?? null, //admin
            'fldtime' => now(),
            'fldcomp' => Helpers::getCompName() ?? null, // comp1
            'fldsave' => 1, //1
            'fldabnormal' => 1, //1
            'flduptime' => NULL, //null
            'xyz' => 0, // 0
            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

        ];


        PatientExam::insert(array_merge($vitals, $mergeData));
    }

}
