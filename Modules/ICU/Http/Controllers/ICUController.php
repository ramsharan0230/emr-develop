<?php

namespace Modules\ICU\Http\Controllers;

use App\ABG;
use App\BillingSet;
use App\BloodProducts;
use App\Bollus;
use App\Cardiovascular;
use App\ChestAndEye;
use App\CogentUsers;
use App\Complaints;
use App\Consult;
use App\Departmentbed;
use App\DiagnoGroup;
use App\Drugs;
use App\Encounter;
use App\Exam;
use App\Fallprevention;
use App\Gastrointestinal;
use App\GCS;
use App\GetDiagnosis;
use App\ICU;
use App\Isolation;
use App\Limbmovement;
use App\LinesandTubes;
use App\Neurologicalsafety;
use App\Notes;
use App\NurseDosing;
use App\Oxygenttherapy;
use App\PatDosing;
use App\PatientExam;
use App\PatientFinding;
use App\PatientInfo;
use App\Pupils;
use App\Respiratory;
use App\Routineandsafety;
use App\Skincare;
use App\Spine;
use App\Test;
use App\User;
use App\Utils\Helpers;
use App\Utils\Options;
use App\VAP;
use App\Ventilatorandoxygen;
use App\VentilatorParameter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Console\Helper\Helper;

class ICUController extends Controller
{
    public function index(Request $request)
    {

//        return redirect()->route('delete.notes','1');
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
        $encounter_id_session = Session::get('neuro_encounter_id');
        $data['patient_status_disabled'] = 0;

        $data['encounter_no'] = null;
//        dd($request->all());
        if ($request->has('encounter_id') || $encounter_id_session) {

            if ($request->has('encounter_id'))
                $encounter_id = $request->get('encounter_id');
            else
                $encounter_id = $encounter_id_session;

            session(['neuro_encounter_id' => $encounter_id]);

            /*create last encounter id*/
            Helpers::encounterQueue($encounter_id);
            $encounterIds = Options::get('last_encounter_id');

            $arrayEncounter = unserialize($encounterIds);
            /*create last encounter id*/
            $dataflag = array(
                'fldinside' => 1,
            );

            Encounter::where('fldencounterval', $encounter_id)->update($dataflag);

            $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();

            $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();

            /*department change enabled*/
            /*if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
                if (!in_array($data['enpatient']->fldcurrlocat, $current_user->department->pluck('flddept')->toArray())) {
                    Session::flash('display_popup_error_success', true);
                    Session::flash('error_message', 'You are not authorized to view this patients information.');
                    Session::forget('neuro_encounter_id');
                    return redirect()->route('admin.dashboard');
                }
            }*/

            $data['patient_status_disabled'] = $enpatient->fldadmission == "Discharged" ? 1 : 0;

            //            dd($enpatient);
            $data['enable_freetext'] = Options::get('free_text');
            $patient_id = $enpatient->fldpatientval;
            // echo $patient_id; exit;
            $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();

            $data['patient_id'] = $patient_id;


            $data['plannedConsultants'] = Consult::where('fldencounterval', $encounter_id)->where('fldstatus', 'Planned')->get();

            //select fldrepquanti from tblpatientexam where fldencounterval=$encounter_id and fldhead='Body Weight' and fldtype='Quantitative' and fldsave=$encounter_id
            $data['patientexam'] = $patientexam_findings = PatientExam::where('fldencounterval', $encounter_id)->where('fldinput', 'OPD Examination')->where('fldsave', 1)->get();


            $data['consultants'] = User::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();

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
            $data['encounter_no'] = $encounter_id ?? null;

            if ($encounter_id) {

                $data['Notes'] = Notes::whereDate('fldtime', Carbon::today())
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldinput', 'Notes')
                    ->get(); // confusion
                $data['patient_diagnosis'] = PatientFinding::where('fldencounterval', $encounter_id)
                    ->where('fldtype', 'Provisional Diagnosis')->get();

                $data['drugs_list'] = PatDosing::with('nursedosing')->where(
                    [
                        ['fldencounterval', $encounter_id],
                        ['fldlevel', 'Dispensed']
                    ])->Where([
                    ['fldroute', '!=', 'fluid'],
                    ['fldcurval', '!=', 'DisContinue'],
                    ['fldcurval', '!=', 'Cancelled']])
                    ->Where(
                        [
                            ['fldstarttime', '<', config('constants.current_date_time')],
                            ['fldendtime', '>', config('constants.current_date_time')]
                        ]
                    )->get();
                $data['drugs_list'] = $this->filterDrug($data['drugs_list'] ? $data['drugs_list'] : null);
                $data['fluid_list'] = PatDosing::where(
                    [
                        ['fldencounterval', $encounter_id],
                        ['fldroute', 'fluid'],
                        ['fldlevel', 'Dispensed']
                    ])
                    ->Where([['fldcurval', '!=', 'DisContinue'],
                        ['fldcurval', '!=', 'Cancelled']])
                    ->Where(
                        [
                            ['fldstarttime', '<', config('constants.current_date_time')],
                            ['fldendtime', '>', config('constants.current_date_time')]
                        ]
                    )
                    ->get();

                $data['fluid_particulars'] = NurseDosing::with('getName')->where([
                    ['fldencounterval', $encounter_id],
                    ['fldunit', 'ML/Hour'],
                ])->get();

            }

            //yaha chahi aru neuro form ko data halne


            return view('neuro::neuro_form', $data);
        }
        return view('neuro::neuro_form', $data);

    }

    public function resetEncounter()
    {
        Session::forget('neuro_encounter_id');
        return redirect()->route('neuro');
    }

    private function filterDrug($drugs)
    {
        if ($drugs) {
            try {
                $drugs = $drugs->filter(function ($drug) {
                    $totalNurseDose = $drug->nursedosing->sum('fldvalue');
                    return $drug->fldqtydisp != $totalNurseDose;
                });

                return $drugs;

            } catch (Exception $e) {
                return null;
            }
        } else {
            return null;
        }
    }

    public function store(Request $request)
    {


        $rules = [
            'encounter_id' => 'required',
        ];
        $request->validate($rules);

        try {
            $encounter_no = $request->get('encounter_id');

            //creating new patient
            $patient_profile = [

                'full_name' => $request->get('full_name'),

                'encounter_no' => $encounter_no,

                'address' => $request->get('address'),

                'sex' => $request->get('sex'),

                'age' => $request->get('age'),

                'created_at' => config('constants.current_date_time'),

                'updated_at' => config('constants.current_date_time'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            DB::beginTransaction();

//            $patient_id = PatientProfile::insertGetId($patient_profile);

            //inserting patient extra data
//            $patient_profile_extra = [
//
//                'patient_profile_id' => $patient_id ?? null,
//
//                'rank' => $request->get('rank'),
//
//                'user_name' => $request->get('user_name'),
//
//                'height' => $request->get('height'),
//
//                'weight' => $request->get('weight'),
//
//                'bmi' => $request->get('bmi'),
//
//                'status' => $request->get('status'),
//
//                'doreg' => $request->get('doreg'),
//
//                'location_bed_no' => $request->get('bed_no'),
//
//                'created_at' => config('constants.current_date_time'),
//                'updated_at' => config('constants.current_date_time'),
//                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
//            ];
//
//            PatientProfileExtra::updateOrCreate([
//                                                    'encounter_no' => $encounter_no,
//                                                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
//                                                ], $patient_profile_extra);
//        PatientProfileExtra::insert($patient_profile_extra);

            //insertion of GCS data
            $gcs = [
                'fldencounterval' => $encounter_no,

                'fldinput' => 'Examination',

                'fldtype' => 'Qualitative',

                'fldhead' => 'Glasgrow Coma Scale(GCS)',

                'fldsysconst' => 'Glassgrow_coma_scale',

                'fldrepquali' => json_encode([
                    'Eye Response' => $request->get('e'),

                    'Verbal Response' =>($request->get('v') && $request->get('v')=='T') ? "1" : $request->get('v'),

                    'Motor Response' => $request->get('m'),

                ]),

                'fldrepquanti' => $request->get('total_gcs'),

                'fldtime' => config('constants.current_date_time'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

            ];

            GCS::insert($gcs);

            //insertion of Vitals data
            if ($request->get('syst_bp') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Systolic BP', 'fldrepquali' => $request->get('syst_bp')]);
            }
            //insertion of Pulse rate data
            if ($request->get('pulse_rate') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Pulse Rate', 'fldrepquali' => $request->get('pulse_rate')]);
            }

            if ($request->get('dyst_bp') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Diastolic BP', 'fldrepquali' => $request->get('dyst_bp')]);
            }

            if ($request->get('spo') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'O2 Saturation', 'fldrepquali' => $request->get('spo')]);
            }

            if ($request->get('temp') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Temperature (F)', 'fldrepquali' => $request->get('temp')]);
            }
            if ($request->get('respiratory') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Respiratory', 'fldrepquali' => $request->get('respiratory')]);
            }

            /**
             * insertion of output data
             */
            if ($request->get('urine') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Urine', 'fldreportquanti' => $request->get('urine')]);
            }
            if ($request->get('evd') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'EVD', 'fldreportquanti' => $request->get('evd')]);
            }
            if ($request->get('drain') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Drain', 'fldreportquanti' => $request->get('drain')]);
            }
            if ($request->get('total') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Total', 'fldreportquanti' => $request->get('total')]);
            }
            if ($request->get('extra') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Extra', 'fldreportquanti' => $request->get('extra')]);
            }

            //insertion of pupils  and MAP,CVP, ETCO2 in tblneuro table
            $pupils = [
                'encounter_no' => $encounter_no,

                'right_side_size' => $request->get('right_side_size'),

                'left_side_size' => $request->get('right_side_size'),

                'right_side_reaction' => $request->get('right_side_reaction'),

                'left_side_reaction' => $request->get('right_side_reaction'),

                'map' => $request->get('map'),

                'cvp' => $request->get('cvp'),

                'etco' => $request->get('etco'),

                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

            ];

            Pupils::insert($pupils);

            //ventilator parameter insertion in hmis_ventilator_parameters
            $ventilator_parameter = [
                'encounter_no' => $encounter_no,

                'mode' => $request->get('mode'),

                'remarks' => $request->get('mode_remarks'),

                'fio2' => $request->get('fio'),

                'peep' => $request->get('peep'),

                'pressure_support' => $request->get('pressure_support'),

                'tidal_volume' => $request->get('tidal_volume'),

                'minute_volume' => $request->get('minute_volume'),

                'ie' => $request->get('ie'),

                'ventilator_extra' => $request->get('ventilator_extra'),

                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),

            ];

            VentilatorParameter::insert($ventilator_parameter);

            //insertion of vap in hmis_vap
            $vap = [

                'encounter_no' => $encounter_no,

                'sat' => $request->get('sat'),

                'sbt' => $request->get('sbt'),

                'are' => $request->get('are'),

                'ehb' => $request->get('ehb'),

                'vcc' => $request->get('vcc'),

                'sib' => $request->get('sib'),

                'sedation' => $request->get('sedation'),

                'grbs' => $request->get('grbs'),

                'regular_insulin' => $request->get('regular_insulin'),

                'et_suction' => $request->get('et_suction'),

                'oral_digestive' => $request->get('oral_digestive'),

                'oral_care' => $request->get('oral_care'),

                'prophylactic_probiotics' => $request->get('prophylactic_probiotics'),

                'stress_ulcer_prophylaxis' => $request->get('stress_ulcer_prophylaxis'),

                'et_cuff_pressure' => $request->get('et_cuff_pressure'),

                'et_length' => $request->get('et_length'),

                'nebulization' => $request->get('nebulization'),

                'nebulization_ains' => $request->get('nebulization_ains'),

                'nebulization_nac' => $request->get('nebulization_nac'),

                'nebulization_flohale' => $request->get('nebulization_flohale'),

                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

            ];

            VAP::insert($vap);

            //insertion of chest and eye data in hmis_chest_and_eye

            $chestAndEye = [

                'encounter_no' => $encounter_no,

                'chest_a' => $request->get('chest_a'),

                'chest_w' => $request->get('chest_w'),

                'chest_c' => $request->get('chest_c'),

                'eye_e' => $request->get('eye_e'),

                'eye_p' => $request->get('eye_p'),

                'eye_b' => $request->get('eye_b'),

                'eye_pp' => $request->get('eye_pp'),

                'lines_f' => $request->get('lines_f'),

                'lines_cvp' => $request->get('lines_cup'),

                'lines_t' => $request->get('lines_t'),

                'lines_w' => $request->get('lines_w'),

                'lines_evd' => $request->get('lines_evd'),

                'lines_tp' => $request->get('lines_tp'),

                'chest_physical_therapy' => $request->get('chest_physical_therapy'),

                'limb_physical_therapy' => $request->get('limb_physical_therapy'),

                'ambulation_physical_therapy' => $request->get('ambulation_physical_therapy'),

                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

            ];

            ChestAndEye::insert($chestAndEye);

            //insertion of Notes
            $note = [

                'flditem' => $request->get('note_by'),

                'flddetail' => strip_tags($request->get('message')),

                'fldtime' => config('constants.current_date_time'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

            ];
            Notes::insert($note);

            //insertion of abg
            $abg = [

                'encounter_no' => $encounter_no,

                'ph' => $request->get('ph'),

                'po' => $request->get('po'),

                'pco' => $request->get('pco'),

                'hco' => $request->get('hco'),

                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

            ];

            ABG::insert($abg);

            //insertion of spine data
            $image_data = [

                'patient_profile_id' => $patient_id ?? null,
                'encounter_no' => $encounter_no,
                'Cervical_Spine' => $request->get('Cervical_Spine'),
                'Thoracic_Spine' => $request->get('Thoracic_Spine'),
                'Lumber_Spine' => $request->get('Lumber_Spine'),
                'sacrococcygeal_Spine' => $request->get('sacrococcygeal_Spine'),
                'Right_Upper_Limbs' => $request->get('Right_Upper_Limbs'),
                'Right_lower_Limbs' => $request->get('Right_Lower_Limbs'),
                'Left_Upper_Limbs' => $request->get('Left_Upper_Limbs'),
                'Left_lower_Limbs' => $request->get('Left_Lower_Limbs'),

                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

            ];
            Spine::insert($image_data);

            DB::commit();
            return redirect()->route('neuro')->with('success_message', 'Record Saved successfully!!');

        } catch (\Exception $exception) {
            dd($exception);
            DB::rollBack();
            return redirect()->route('neuro')->with('error_message', 'Unable to save record, Please try again');
        }
    }

    private function insertGcs($encounter_no, $mergeData)
    {
        $vitals = [

            'fldencounterval' => $encounter_no,

            'fldinput' => 'Examination',

            'fldtype' => 'Quantitative',

            'fldhead' => 'Pulse Rate',

            'fldtime' => config('constants.current_date_time'),

            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

        ];

        GCS::insert(array_merge($vitals, $mergeData));
    }

    private function insertOutput($encounter_no, $mergeData)
    {
        $output = [

            'fldencounterval' => $encounter_no,

            'fldinput' => 'Extra',

            'fldtype' => 'Qualitative',

            'fldtime' => config('constants.current_date_time'),

            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

        ];

        Notes::insert(array_merge($output, $mergeData));
    }

    /*
     * This function is for search and auto-comlplete
     */
    public function autocomplete(Request $request)
    {
        try {
            $data = Drugs::select('flddrug')->where("flddrug", "LIKE", "%{$request->input('term')}%")
                ->pluck('flddrug')
                ->take(30);
            return response()->json($data);
        } catch (\Exception $exception) {
            $data['error'] = 'something went wrong please try again';
            return response()->json($data);
        }

    }

    /**
     * This function is to sstore the drugs via ajax request
     */
    public function storeDrugActivity(Request $request)
    {
        try {
            $drug_insert = [];
            //if it type of fluid
            if ($request->get('type') == 'fluid') {
                //dd($request->all());
                $drug_insert = [
                    'fldencounterval' => $request->get('encounter'),
                    'flddoseno' => $request->get('id'),
                    'fldvalue' => $request->get('value'),
                    'fldunit' => 'ML/Hour',
                    'fldstatus' => 'ongoing',
                    'fldfromtime' => config('constants.current_date_time'),
                    'fldtime' => config('constants.current_date_time'),
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ];

                if (count($drug_insert) > 0) {
                    $status = NurseDosing::insertGetId($drug_insert);
                }

                if ($status) {
                    $data = NurseDosing::where('fldid', $status)->first();
                    $name = PatDosing::where('fldid', $request->get('id'))->first();
                    $data['name'] = $name->flditem;
                }
                return response()->json(['success' => 'Added', 'data' => $data], 200);

            } else {
// if it is type of drug
                foreach ($request->get('drug') as $drug) {
                    $drug_insert[] = [
                        'fldencounterval' => $request->get('encounter'),
                        'flddoseno' => $drug['id'],
                        'fldunit' => 'tab',
                        'fldvalue' => $drug['quantity'],
                        'fldtime' => config('constants.current_date_time'),
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ];
                }
                if (count($drug_insert) > 0) {
                    NurseDosing::insert($drug_insert);
                }
                return response()->json(['success' => 'Added'], 200);
            }

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Cannot Add now'
            ], 422);
        }


    }

    public function getDiagnosis()
    {
        try {
            $diagnosis = GetDiagnosis::all();
            if ($diagnosis) {
                return response()->json($diagnosis);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'something went wrong'
            ], 422);
        }


    }

    /**
     * Function getting intitial diagnosis list from csv
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
            return response()->json(['status' => 'success', 'data' => $data]);

        } catch (\Exception $exception) {
            dd($exception);
            return response()->json(['status' => 'error', 'data' => []]);
        }

    }

    /**
     * Function for sending data from csv file
     */
    public function getDiagnosisByCode(Request $request)
    {
        if ($request->get('code')) {
            try {
                $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
                $key = $request->get('code');
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
                return response()->json($data);

            } catch (\Exception $exception) {
                return response()->json([
                    'error' => 'something went wrong'
                ], 422);
            }
        }
        return response()->json([
            'error' => 'Not Found!!'
        ], 422);

    }

    /**
     * function for inserting diagnosis data
     */
    public function insertDiagnosis(Request $request)
    {
        try {
            $data = [
                'fldcode' => $request->get('fldcode'),
                'fldtype' => $request->get('fldtype'),
                'fldcodeid' => $request->get('fldcodeid'),
                'flduserid' => Helpers::getCurrentUserName(),
                'fldcomp' =>  Helpers::getCompName(),
                'fldsave'=> 1,
                'xyz' => 0,
                'fldencounterval' => $request->get('encounter'),
                'fldtime' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];
            PatientFinding::insert($data);

            $diagnosis = PatientFinding::where('fldencounterval', $request->get('encounter'))
                ->where('fldtype', 'Provisional Diagnosis')
                //->where('fldsave','1')
                ->get();

            return response()->json($diagnosis);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => 'Not Found!!'
            ], 422);
        }
    }

    /**
     * function for removing the drugs
     */
    public function removeDrug(Request $request)
    {
        $id = $request->get('id');

        try {

            PatDosing::where('fldid', $id)->delete();

            $data = PatDosing::where('fldencounterval', $request->get('encounter'))->get();

            return response()->json($data);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => 'Error'
            ], 422);
        }
    }

    /**
     * function for removing the diagnosis
     */
    public function removeDiagnosis(Request $request)
    {
        $id = $request->get('id');

        try {

            PatientFinding::where('fldid', $id)->delete();

            $data = PatientFinding::where('fldencounterval', $request->get('encounter'))->get();

            return response()->json($data);

        } catch (\Exception $exception) {

            return response()->json([
                'error' => 'Error'
            ], 422);
        }
    }

    /**
     * function for storing notes to tblexamgeneral
     */
    public function storeNotes(Request $request)
    {
        $message = $request->get('message');
        if($message== null || $message=='')
        {
            return response(['error'=>'Cannot save empty note!']);
        }
        if(!$request->get('encounter'))
        {
            return response(['error'=>'Missing Encounter no!']);
        }
        try {
            if($message)
            {
                $note = [

                    'fldinput' => 'Notes',

                    'fldencounterval' => $request->get('encounter'),

                    'fldtype' => 'Qualitative',

                    'fldsave' => 1,
                    'fldcomp' => Helpers::getCompName(),

                    'flduserid' => Helpers::getCurrentUserName(),

                    'flditem' => $request->get('note_by'),

                    'flddetail' => strip_tags($request->get('message')),

                    'fldtime' => config('constants.current_date_time'),

                ];
                $status = Notes::insert($note);
            }
            $data = Notes::whereDate('fldtime', Carbon::today())->Where('fldencounterval', $request->get('encounter'))
                ->where('fldinput', 'Notes')
                ->get(); // confusion get('flditem','flddetail')
            if ($data) {
                return response($data);
            }
        } catch (Exception $exception) {
            return response(['error'=>'Something Went Wrong'],420);
        }


    }

    /**
     * function for storing notes to tblexamgeneral
     */
    public function deleteNotes(Request $request,$id)
    {
        if(!$id)
        {
            return response(['error'=>'Something Went Wrong!']);
        }
        if(!$request->get('encounter'))
        {
            return response(['error'=>'Missing Encounter no!']);
        }
        try {
            if($id)
            {
                Notes::where('fldid',$id)->delete();
            }
            $data = Notes::whereDate('fldtime', Carbon::today())->Where('fldencounterval', $request->get('encounter'))
                ->where('fldinput', 'Notes')
                ->get(); // confusion get('flditem','flddetail')
            if ($data) {
                return response($data);
            }
        } catch (Exception $exception) {
            return response(['error'=>'Something Went Wrong'],420);
        }


    }


    /**
     * Function for updating end time of Fluid
     */
    public function StopFluid(Request $request)
    {

        $id = NurseDosing::find($request->get('id'));
        if ($id) {
            try {

                $id->fldtotime = config('constants.current_date_time');
                $id->fldstatus = 'stopped';
                $status = $id->save();
                if ($status) {
                    $data = NurseDosing::with('getName')->where('fldid', $id->fldid)->first();
                    $data['name'] = $data->getName->flditem ?? null;
                }
                return response()->json(['status' => 'success', 'data' => $data], 200);

            } catch (Exception $exception) {

                return response()->json(['status' => 'error'], 422);
            }

        } else {
            return response()->json(['status' => 'error'], 422);
        }

    }


    public function keyGenerator()
    {
        $neuro_keys = [];

        /** PART : 6AM - 12AM */
        $starting_key = 5;
        for ($i = 0; $i < 7; $i++) {
            $am_pm = "AM";
            $temp_key_name = ($starting_key + 1) . $am_pm;
            $neuro_keys[] = [
                'time_key' => $temp_key_name,
                'time_key_label' => ($starting_key + 1) . ' ' . $am_pm,
                'from_time' => $starting_key < 10 ? Carbon::parse("0" . $starting_key . ":30:00")->format("H:i:s") : Carbon::parse($starting_key . ":30:00")->format("H:i:s"),
                'to_time' => ($starting_key + 1) < 10 ? Carbon::parse("0" . ($starting_key + 1) . ":30:00")->format("H:i:s") : Carbon::parse(($starting_key + 1) . ":30:00")->format("H:i:s"),
            ];
            $starting_key = $starting_key + 1;
        }

        /** PART : 1PM - 11PM */
        $starting_key = 0;
        $starting_time = 12;
        for ($i = 0; $i < 11; $i++) {
            $am_pm = "PM";
            $temp_key_name = ($starting_key + 1) . $am_pm;
            $neuro_keys[] = [
                'time_key' => $temp_key_name,
                'time_key_label' => ($starting_key + 1) . ' ' . $am_pm,
                'from_time' => $starting_time < 10 ? Carbon::parse("0" . $starting_time . ":30:00")->format("H:i:s") : Carbon::parse($starting_time . ":30:00")->format("H:i:s"),
                'to_time' => ($starting_time + 1) < 10 ? Carbon::parse("0" . ($starting_time + 1) . ":30:00")->format("H:i:s") : Carbon::parse(($starting_time + 1) . ":30:00")->format("H:i:s"),
            ];
            $starting_key = $starting_key + 1;
            $starting_time = $starting_time + 1;
        }

        /** PART : 12PM */
        $neuro_keys[] = [
            'time_key' => "12PM",
            'time_key_label' => "12 PM",
            'from_time' => Carbon::parse("23:30:00")->format("H:i:s"),
            'to_time' => Carbon::parse("00:30:00")->format("H:i:s")
        ];

        /** PART : 1AM - 5AM */
        $starting_key = 0;
        for ($i = 0; $i < 5; $i++) {
            $am_pm = "AM";
            $temp_key_name = ($starting_key + 1) . $am_pm;
            $neuro_keys[] = [
                'time_key' => $temp_key_name,
                'time_key_label' => ($starting_key + 1) . ' ' . $am_pm,
                'from_time' => $starting_key < 10 ? Carbon::parse("0" . $starting_key . ":30:00")->format("H:i:s") : Carbon::parse($starting_key . ":30:00")->format("H:i:s"),
                'to_time' => ($starting_key + 1) < 10 ? Carbon::parse("0" . ($starting_key + 1) . ":30:00")->format("H:i:s") : Carbon::parse(($starting_key + 1) . ":30:00")->format("H:i:s"),
            ];
            $starting_key = $starting_key + 1;
        }

        return $neuro_keys;
    }

    /**
     * FUcntions for generating PDF of Neuro Form
     */
    public function generateReport(Request $request, $encounter = null)
    {

        try {

            $data = [];

            if ($encounter == null || $request->get('report_date') == "") {
                Session::flash('error_message', "Record doesnt exists.");
                return redirect()->back();
            }

            $encounter = Encounter::where('fldencounterval', $encounter)->first();
            if (!$encounter) {
                Session::flash('error_message', "Record doesnt exists.");
                return redirect()->back();
            }

            // ->where('created_at', '>=', $from_date)
            // ->where('created_at', '<=', $to_date)

            $data['report_date'] = Helpers::dateNepToEng($request->get('report_date'))->full_date ?? null;
            $data['to'] = Helpers::dateNepToEng($request->get('report_date_to'))->full_date ?? null;

            //if data exists in encounter
            $data['encounter'] = $encounter ?? null;
            //for patient profile
            $data['patient_info'] = PatientInfo::where('fldpatientval', $encounter->fldpatientval)->first();

            $data['body_weight'] = $body_weight = PatientExam::where('fldencounterval', $encounter)->where('fldsave', 1)->where('fldsysconst', 'Body_Weight')->orderBy('fldid', 'desc')->first();
            //patient data from new table
//            $data['patient_info_extra'] = PatientProfileExtra::where('encounter_no', $encounter->fldencounterval)->first();

            //GCS RECORDS
            $gcs_records = GCS::select('fldrepquali', 'fldrepquanti', 'fldtime')->where('fldencounterval', $encounter->fldencounterval)
                ->where("fldinput", 'Examination')
                ->where("fldtype", 'Qualitative')
                ->where("fldsysconst", 'Glassgrow_coma_scale')
                ->whereDate('fldtime', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('fldtime', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('fldid', 'desc')  // BHAI ORDER BY DESC IS IMPORTANT HAI SINCE WE ARE FETCHING LATEST RECORDS
                ->get();

            //Pupils data
            $pupils = Pupils::where('encounter_no', $encounter->fldencounterval)
                ->whereDate('created_at', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('created_at', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('id', 'desc') // BHAI ORDER BY DESC IS IMPORTANT HAI SINCE WE ARE FETCHING LATEST RECORDS
                ->get();

            //Data for Muscle power
            $muscle = Spine::where('encounter_no', $encounter->fldencounterval)
                ->whereDate('created_at', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('created_at', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('id', 'desc') // BHAI ORDER BY DESC IS IMPORTANT HAI SINCE WE ARE FETCHING LATEST RECORDS ,, PLEASE DO SAME ON OTHER MODELS AS WELL ORDER BY DESC
                ->get();

            //Data for vitals
            $vitals = GCS::where([
                ['fldencounterval', $encounter->fldencounterval],
                ['fldinput', 'Examination'],
                ['fldtype', 'Quantitative'],
            ])->whereDate('fldtime', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('fldtime', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('fldid', 'desc')
                ->get();

            //Data for ABGs
            $abg = ABG::where('encounter_no', $encounter->fldencounterval)
                ->whereDate('created_at', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('created_at', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('id', 'desc')
                ->get();

            //Data for Ventilater Parameters
            $ventilator = VentilatorParameter::where('encounter_no', $encounter->fldencounterval)
                ->whereDate('created_at', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('created_at', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('id', 'desc')
                ->get();

            //Data for Intake
            $intakes = NurseDosing::with('getName')->where([
                ['fldencounterval', $encounter->fldencounterval],
                ['fldunit', 'mL/Hour'],
            ])->whereDate('fldtime', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('fldtime', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('fldid', 'desc')
                ->get();

//            dd( Helpers::dateNepToEng($request->get('report_date'))->full_date);
            $intakeyesterday = NurseDosing::with('getName')->where([
                ['fldencounterval', $encounter->fldencounterval],
                ['fldunit', 'mL/Hour'],
            ])
                ->whereDate('fldtime', '>=', Helpers::dateNepToEng( Carbon::parse($request->get('report_date'))->subDay('1')->format('Y-m-d'))->full_date)
                ->whereDate('fldtime', '<=',Helpers::dateNepToEng( Carbon::parse($request->get('report_date_to'))->subDay('1')->format('Y-m-d'))->full_date)
                ->orderBy('fldid', 'desc')
                ->get();



            //Data for Outputs
            $outputs = Notes::where([
                ['fldencounterval', $encounter->fldencounterval],
                ['fldinput', 'Extra'],
                ['fldtype', 'Qualitative'],
            ])->whereDate('fldtime', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('fldtime', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('fldid', 'desc')
                ->get();

            //Data for Outputs
            $outputsYesterday = Notes::where([
                ['fldencounterval', $encounter->fldencounterval],
                ['fldinput', 'Extra'],
                ['fldtype', 'Qualitative'],
            ])
                ->whereDate('fldtime', '>=', Helpers::dateNepToEng( Carbon::parse($request->get('report_date'))->subDay('1')->format('Y-m-d'))->full_date)
                ->whereDate('fldtime', '<=',Helpers::dateNepToEng( Carbon::parse($request->get('report_date_to'))->subDay('1')->format('Y-m-d'))->full_date)
                ->orderBy('fldid', 'desc')
                ->get();
//            dd($intakeyesterday);

            //Data for drugs
            $drugs = NurseDosing::with('getName')->where([
                ['fldencounterval', $encounter->fldencounterval],
                ['fldunit', 'tab'],
            ])->whereDate('fldtime', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('fldtime', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('fldid', 'desc')->get();

            //Data for Chest and EYe
            $chestEye = ChestAndEye::where([['encounter_no', $encounter->fldencounterval],])
                ->whereDate('created_at', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('created_at', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('id', 'desc')->get();

            //Data for Vaps
            $Vaps = VAP::where([['encounter_no', $encounter->fldencounterval],])
                ->whereDate('created_at', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('created_at', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('id', 'desc')->get();

            //Data for Notes
            $notes = Notes::where([
                ['fldencounterval', $encounter->fldencounterval],
                ['fldinput', 'Notes'],
            ])->whereDate('fldtime', '>=', Helpers::dateNepToEng($request->get('report_date'))->full_date)
                ->whereDate('fldtime', '<=', Helpers::dateNepToEng($request->get('report_date_to'))->full_date)
                ->orderBy('fldid', 'desc')->get();
//            dd($notes);
            $neuro_keys = $this->keyGenerator();
            $data['neuro_reports'] = [];
            foreach ($neuro_keys as $neuro_key) {

                /** TIMEWISE PROCESSING */
                $pupil_response = $this->processPupils($pupils, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']);
                $muscle_response = $this->processMuscle($muscle, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']);

                $vitals_response = $this->processVitals($vitals, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']);

                $abgs_response = $this->processAbgs($abg, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']);
                $ventilator_response = $this->processVentalator($ventilator, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']);
                $intakes_response = $this->processIntakes($intakes, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']);
                $outputs_response = $this->processOutputs($outputs, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']);
                $drugs_response = $this->processDrugs($drugs, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']);
                $chestEye_response = $this->processChestEye($chestEye, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']);
                $vaps_response = $this->processVaps($Vaps, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']);
                $notes_response = $this->processNotes($notes, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']);


                $data['neuro_reports'][] = [
                    'time_key' => $neuro_key['time_key'] ?? null,
                    'time_key_label' => $neuro_key['time_key_label'] ?? null,

                    // GCS RELATED KEYS
                    'gcs_eye_spontaneous' => $this->processGCS('Eye Response', 'Spontaneous', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_eye_to_speech' => $this->processGCS('Eye Response', 'To speech', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_eye_to_pain' => $this->processGCS('Eye Response', 'To pain', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_eye_none' => $this->processGCS('Eye Response', 'None', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_verbal_oriented' => $this->processGCS('Verbal Response', 'Oriented', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_verbal_confused' => $this->processGCS('Verbal Response', 'Confused', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_verbal_words' => $this->processGCS('Verbal Response', 'Words', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_verbal_sounds' => $this->processGCS('Verbal Response', 'Sounds', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_verbal_t' => $this->processGCS('Verbal Response', 'T', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_verbal_none' => $this->processGCS('Verbal Response', 'None', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_motor_obeys' => $this->processGCS('Motor Response', 'Obeys Command', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_motor_localizing' => $this->processGCS('Motor Response', 'localizing', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_motor_flexion' => $this->processGCS('Motor Response', 'Normal Flexion', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_motor_abnormal' => $this->processGCS('Motor Response', 'Abnormal Flexion', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_motor_extension' => $this->processGCS('Motor Response', 'Extension', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_motor_none' => $this->processGCS('Motor Response', 'None', $gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),
                    'gcs_total' => $this->processGCSTotal($gcs_records, $data['report_date'], $neuro_key['from_time'], $neuro_key['to_time']),

                    //PUPIL RELATED KEYS
                    'pupil_right_size' => $pupil_response->right_side_size ?? null,
                    'pupil_right_reaction' => $pupil_response->right_side_reaction ?? null,
                    'pupil_left_size' => $pupil_response->left_side_size ?? null,
                    'pupil_left_reaction' => $pupil_response->left_side_reaction ?? null,

                    //MAP CVP ETCO
                    'map' => $pupil_response->map ?? null,
                    'cvp' => $pupil_response->cvp ?? null,
                    'etco' => $pupil_response->etco ?? null,

                    //MUSCLE RESPONSE
                    'right_Upper_Limbs' => $muscle_response->Right_Upper_Limbs ?? null,
                    'right_lower_Limbs' => $muscle_response->Right_lower_Limbs ?? null,
                    'left_Upper_Limbs' => $muscle_response->Left_Upper_Limbs ?? null,
                    'left_lower_Limbs' => $muscle_response->Left_lower_Limbs ?? null,

                    //Vitals Response
                    'pulse_rate' => ($vitals_response) ? $vitals_response->where('fldhead', '=', 'Pulse Rate')->pluck('fldrepquali')->first() : null,
                    'systolic_bp' => ($vitals_response) ? $vitals_response->where('fldhead', '=', 'Systolic BP')->pluck('fldrepquali')->first() : null,
                    'diastolic_bp' => ($vitals_response) ? $vitals_response->where('fldhead', '=', 'Diastolic BP')->pluck('fldrepquali')->first() : null,
                    'respiratory_rate' => ($vitals_response) ? $vitals_response->where('fldhead', '=', 'Respiratory')->pluck('fldrepquali')->first() : null,
                    'saturation' => ($vitals_response) ? $vitals_response->where('fldhead', '=', 'O2 Saturation')->pluck('fldrepquali')->first() : null,
                    'temperature' => ($vitals_response) ? $vitals_response->where('fldhead', '=', 'Temperature (F)')->pluck('fldrepquali')->first() : null,

                    //ABGS Response
                    'ph' => $abgs_response->ph ?? null,
                    'po' => $abgs_response->po ?? null,
                    'pco' => $abgs_response->pco ?? null,
                    'hco' => $abgs_response->hco ?? null,

                    //Ventalator Response here
                    'mode' => $ventilator_response->mode ?? null,
                    'fio2' => $ventilator_response->fio2 ?? null,
                    'peep' => $ventilator_response->peep ?? null,
                    'pressure_support' => $ventilator_response->pressure_support ?? null,
                    'tidal_volume' => $ventilator_response->tidal_volume ?? null,
                    'minute_volume' => $ventilator_response->minute_volume ?? null,
                    'ie' => $ventilator_response->ie ?? null,
                    'remarks' => $ventilator_response->remarks ?? null,

                    //Intake response here
                    'intake_medicine' => $intakes_response->getName->flditem ?? null,
                    'intake_value' => $intakes_response->fldvalue ?? null,
                    'total_intake' => ($intakes_response) ? $intakes_response->where('fldunit', '=', 'mL/Hour')->sum('fldvalue') : null,

                    //just for testing purpose
//                   'test_intake' => [
//                        'intake_medicine' => $intakes_response->getName->flditem ??  null,
//                        'intake_value' => $intakes_response->fldvalue ??  null,
//                        'total_intake' => ($intakes_response) ? $intakes_response->where('fldunit','=','ML/Hour')->sum('fldvalue') : null,
//                    ] ?? null,

                    // confused all ko sum dekhaune ki only value

                    //Output response here
                    'urine' => ($outputs_response) ? $outputs_response->where('flditem', '=', 'Urine')->pluck('fldreportquanti')->first() : null,
                    'evd' => ($outputs_response) ? $outputs_response->where('flditem', '=', 'EVD')->pluck('fldreportquanti')->first() : null,
                    'drain' => ($outputs_response) ? $outputs_response->where('flditem', '=', 'Drain')->pluck('fldreportquanti')->first() : null,
                    'total' => ($outputs_response) ? $outputs_response->where('flditem', '=', 'Total')->pluck('fldreportquanti')->first() : null,
                    'extra' => ($outputs_response) ? $outputs_response->where('flditem', '=', 'Extra')->pluck('fldreportquanti')->first() : null,

                    //Drug response here
                    'drug_name' => $drugs_response->getName->flditem ?? null,
                    'drug_value' => $drugs_response->fldvalue ?? null,
                    'total' => ($drugs_response) ? $drugs_response->sum('fldvalue') : null,

                    //chest and eye resposnse here
                    'air_entry' => $chestEye_response->chest_a ?? null,
                    'wheeze' => $chestEye_response->chest_w ?? null,
                    'crackles' => $chestEye_response->chest_c ?? null,

                    //data for EYE & SKIN CARE
                    'eye_e' => ($chestEye_response && $chestEye_response->eye_e == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,
                    'eye_position' => $chestEye_response->eye_p ?? null,
                    'eye_pp' => $this->processNames($chestEye_response->eye_pp ?? null) ?? null,
                    'eye_b' => ($chestEye_response && $chestEye_response->eye_b == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,

                    //data for LINES & WOUND CARE
                    'lines_foley' => ($chestEye_response && $chestEye_response->lines_f == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,
                    'lines_cvp' => ($chestEye_response && $chestEye_response->lines_cvp == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,
                    'lines_tracheostomy' => ($chestEye_response && $chestEye_response->lines_t == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,
                    'lines_wound' => ($chestEye_response && $chestEye_response->lines_w == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,
                    'lines_evd' => ($chestEye_response && $chestEye_response->lines_evd == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,
                    'lines_thromboembolic_prophylaxis' => ($chestEye_response && $chestEye_response->lines_tp == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,

                    //Data  for PHYSICAL THERAPY
                    'chest_physiotherapy' => ($chestEye_response && $chestEye_response->chest_physical_therapy == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,
                    'limb_physiotherapy' => ($chestEye_response && $chestEye_response->limb_physical_therapy == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,
                    'ambulation' => $chestEye_response ? $chestEye_response->ambulation_physical_therapy : null,

                    //Data for Vaps here
                    'sat' => ($vaps_response && $vaps_response->sat == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,
                    'sbt' => ($vaps_response && $vaps_response->sbt == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,
                    'are' => ($vaps_response && $vaps_response->are == 'YES') ? $this->processNames($chestEye_response->eye_pp ?? null) : null,
                    'ehb' => $vaps_response->ehb ?? null,
                    'vcc' => $vaps_response->vcc ?? null,
                    'et_suction' => $vaps_response->et_suction ?? null,
                    'oral_digestive' => $vaps_response->oral_digestive ?? null,
                    'oral_care' => $vaps_response->oral_care ?? null,
                    'prophylactic_probiotics' => $vaps_response->prophylactic_probiotics ?? null,
                    'stress_ulcer_prophylaxis' => $vaps_response->stress_ulcer_prophylaxis ?? null,
                    'et_cuff_pressure' => $vaps_response->et_cuff_pressure ?? null,
                    'et_length' => $vaps_response->et_length ?? null,
                    'nebulization' => $vaps_response->nebulization ?? null,
                    'nebulization_ains' => $vaps_response->nebulization_ains ?? null,
                    'nebulization_nac' => $vaps_response->nebulization_nac ?? null,
                    'nebulization_flohale' => $vaps_response->nebulization_flohale ?? null,
                    'sib' => $vaps_response->sib ?? null,
                    'regular_insulin' => $vaps_response->regular_insulin ?? null,
                    'grbs' => $vaps_response->grbs ?? null,
                    'sedation' => $vaps_response->sedation ?? null,

                    //Data for Notes here
                    'nurses_notes' => ($notes_response) ? $notes_response->where('flditem', '=', 'Nurses Note')->pluck('flddetail')->first() : null,
                    'nutritionists_notes' => ($notes_response) ? $notes_response->where('flditem', '=', 'Nutritionists Note')->pluck('flddetail')->first() : null,
                    'physical_therapist_notes' => ($notes_response) ? $notes_response->where('flditem', '=', 'Physical Therapist Note')->pluck('flddetail')->first() : null,
                    'medical_officer_notes' => ($notes_response) ? $notes_response->where('flditem', '=', 'Medical Officer')->pluck('flddetail')->first() : null,
                    'attending_neurosurgeon_note' => ($notes_response) ? $notes_response->where('flditem', '=', 'Attending Neurosurgeon Note')->pluck('flddetail')->first() : null,
                    'additional_note' => ($notes_response) ? $notes_response->where('flditem', '=', 'Additional Note')->pluck('flddetail')->first() : null,

                ];
            }
            //usernmae
//            $data['username'] = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : null ;

            //TOTAL 24 HRS INTAKE:
            $data['total_intake'] = $this->totalIntake($intakes ?? null) ?? null;

            //TOTAL 24 HRS OUTPUT:
            $data['total_output'] = $this->totalOutput($outputs ?? null) ?? null;

            //24 hours balance
            $data['twentifour_hour_balance'] = $this->twentyFourHourBalance($data['total_intake'], $data['total_output']) ?? null;

            //Previous days balance

            $data['previous_days_balance'] = $this->previousDaysBalance($intakeyesterday, $outputsYesterday) ?? null;

            //Cumulative balance
            $data['cumulative_balance'] = $this->cumulativeBalance($data['twentifour_hour_balance'] ?? null, $data['previous_days_balance']) ?? null; //0 given because previous balance is unkown and it is dummy valeu

//            dd($data);
            return view('neuro::report.report_pdf', $data);

        } catch (\Exception $e) {
            dd($e);
            Session::flash('error_message', "Record doesnt exists.");
            return redirect()->back();
        }

    }

    /** function for calculating  //TOTAL 24 HRS INTAKE: */

    private function totalIntake($intake = null)
    {
        if ($intake->count() == 0) {
            return false;
        }
        return $intake->sum('fldvalue') ?? null;
    }


    /** function for calculating TOTAL 24 HRS OUTPUT:*/

    private function totalOutput($output = null)
    {
        if ($output->count() == 0) {
            return false;
        }
        return $output->sum('fldreportquanti') ?? null;
    }

    /** function calaulating total 24 hr Balance */
    private function twentyFourHourBalance($intake = null, $output = null)
    {
        if (!($intake && $output)) {
            return false;
        }
        //Total 24 hours intake − Total 24 hours output
//        $intake_sum = $intake->where('fldunit','=','ML/Hour')->sum('fldvalue');
//        $output_sum = $intake->where('fldunit','=','ML/Hour')->sum('fldvalue');
        return $final_intake = ($intake - $output) ?? null;

    }

    /** function for calculating Previous days balance */

    private function previousDaysBalance( $intakeyesterday, $outputyesrtday )
    {


        if (!($intakeyesterday && $outputyesrtday)) {
            return false;
        }

//        dd($intakeyesterday->where('fldunit','=','mL/Hour')->sum('fldvalue'));
        $yesterdayBalance = ($intakeyesterday->where('fldunit','=','mL/Hour')->sum('fldvalue'))-( $outputyesrtday->sum('fldreportquanti')) ?? null;
        return  $yesterdayBalance;
    }

    /** function for calculating Cumulative balance */

    private function cumulativeBalance($balance = null, $previous_day_balance = null)
    {
        // balance here is 24 hours balance
        if ($balance = null && $previous_day_balance = null) {
            return false;
        }

        return $cumulative_balance = ($balance + $previous_day_balance) ?? null; // $previous_day_balance
    }

    /** For creating abbrebiation of name e.g. PBB for Paawan Bahadur Bhandari */
    private function processNames($name = null)
    {
        if ($name) {
            try {
                $words = explode(" ", $name);
                $abb = "";

                foreach ($words as $w) {
                    $abb .= $w[0];
                }
                return $abb;
            } catch (\Exception $exception) {
                return false;
            }
        }
        return false;
    }

    /**
     * function for getting GCS
     */
    private function processGCS($gcs_category = null, $gcs_params = null, $gcs_records = null, $report_date = null, $from_time = null, $to_time = null)
    {
        try {

            if ($gcs_records->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');

            $gcs_result = $gcs_records->where('fldtime', ">", $from_time)->where('fldtime', "<", $to_time)->first();
            if (!$gcs_result) {
                return false;
            }

            if ($gcs_category == 'Eye Response') {

                $eye_response = json_decode($gcs_result->fldrepquali, TRUE);
                $eye_response_value = $eye_response['Eye Response'] ?? "NONE";
                if (config('constants.gcs_eye_response.' . $gcs_params) == $eye_response_value) {

                    return $eye_response['Eye Response']  ?? null;
                }

            } elseif ($gcs_category == 'Verbal Response') {

                $verbal_response = json_decode($gcs_result->fldrepquali, TRUE);
                $verbal_response_value = $verbal_response['Verbal Response'] ?? "NONE";
                if (config('constants.gcs_verbal_response.' . $gcs_params) == $verbal_response_value) {
                    return $verbal_response['Verbal Response'] ?? null;
                }

            } elseif ($gcs_category == 'Motor Response') {

                $motor_response = json_decode($gcs_result->fldrepquali, TRUE);
                $motor_response_value = $motor_response['Motor Response'] ?? "NONE";
                if (config('constants.gcs_motor_response.' . $gcs_params) == $motor_response_value) {
                    return $motor_response['Motor Response'] ?? null;
                }

            }

            return false;

        } catch (\Exception $e) {
            return false;
        }

    }

    private function processGCSTotal($gcs_records = null, $report_date = null, $from_time = null, $to_time = null)
    {
        try {

            if ($gcs_records->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');

            $gcs_result = $gcs_records->where('fldtime', ">", $from_time)->where('fldtime', "<", $to_time)->first();
            if (!$gcs_result) {
                return false;
            }

            return (int)$gcs_result->fldrepquanti;

        } catch (\Exception $e) {
            return false;
        }

    }

    private function processPupils($pupils = null, $report_date = null, $from_time = null, $to_time = null)
    {

        try {

            if ($pupils->count() == 0) {
                return null;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');
            $pupils_result = $pupils->where('created_at', ">", $from_time)->where('created_at', "<", $to_time)->first();
            if (!$pupils_result) {
                return null;
            }
            return $pupils_result;

        } catch (\Exception $e) {
            return null;
        }
    }

    /** function for procession muslce data */
    private function processMuscle($muscle = null, $report_date = null, $from_time = null, $to_time = null)
    {

        try {

            if ($muscle->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');
            $muscle_result = $muscle->where('created_at', ">", $from_time)->where('created_at', "<", $to_time)->first();
            if (!$muscle_result) {
                return false;
            }
            return $muscle_result;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**fucntion  for processing vitals*/
    private function processVitals($vitals = null, $report_date = null, $from_time = null, $to_time = null)
    {
        try {
            if ($vitals->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');
            $vitals_result = $vitals->where('fldtime', ">", $from_time)->where('fldtime', "<", $to_time);

            if ($vitals_result->count() == 0) {
                return false;
            }
            return $vitals_result;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Processing data for ABGs
     */
    private function processAbgs($abg = null, $report_date = null, $from_time = null, $to_time = null)
    {
        try {

            if ($abg->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');
            $abg_result = $abg->where('created_at', ">", $from_time)->where('created_at', "<", $to_time)->first();
            if (!$abg_result) {
                return false;
            }
            return $abg_result;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Processing data for Ventilator Parameters
     */
    private function processVentalator($ventilator = null, $report_date = null, $from_time = null, $to_time = null)
    {
        try {

            if ($ventilator->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');
            $ventilator_result = $ventilator->where('created_at', ">", $from_time)->where('created_at', "<", $to_time)->first();
            if (!$ventilator_result) {
                return false;
            }
            return $ventilator_result;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Prccessing data for Intakes
     */
    private function processIntakes($intakes = null, $report_date = null, $from_time = null, $to_time = null)
    {
        try {

            if ($intakes->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');
            $intakes_result = $intakes->where('fldtime', ">", $from_time)->where('fldtime', "<", $to_time)->first();
            if (!$intakes_result) {
                return false;
            }
            return $intakes_result;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Processing data for Outputs
     */
    private function processOutputs($outputs = null, $report_date = null, $from_time = null, $to_time = null)
    {
        try {

            if ($outputs->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');
            $outputs_result = $outputs->where('fldtime', ">", $from_time)->where('fldtime', "<", $to_time)->first();
            if (!$outputs_result) {
                return false;
            }
            return $outputs_result;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     *  Processing data for Drugs
     */
    private function processDrugs($drugs = null, $report_date = null, $from_time = null, $to_time = null)
    {
        try {

            if ($drugs->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');
            $drugs_result = $drugs->where('fldtime', ">", $from_time)->where('fldtime', "<", $to_time)->first();
            if (!$drugs_result) {
                return false;
            }
            return $drugs_result;


        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     *  Processing data for Chest and Eye
     */
    private function processChestEye($chestEye = null, $report_date = null, $from_time = null, $to_time = null)
    {
        try {

            if ($chestEye->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');
            $chestEye_result = $chestEye->where('created_at', ">", $from_time)->where('created_at', "<", $to_time)->first();

            if (!$chestEye_result) {
                return false;
            }
            return $chestEye_result;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     *  Processing data for Chest and Eye
     */
    private function processVaps($vaps = null, $report_date = null, $from_time = null, $to_time = null)
    {
        try {

            if ($vaps->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');
            $vaps_result = $vaps->where('created_at', ">", $from_time)->where('created_at', "<", $to_time)->first();

            if (!$vaps_result) {
                return false;
            }
            return $vaps_result;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     *  Processing data for Notes
     */
    private function processNotes($notes = null, $report_date = null, $from_time = null, $to_time = null)
    {
        try {

            if ($notes->count() == 0) {
                return false;
            }

            $from_time = Carbon::parse($report_date . " " . $from_time)->format('Y-m-d H:i:s');
            $to_time = Carbon::parse($report_date . " " . $to_time)->format('Y-m-d H:i:s');
            $notes_result = $notes->where('fldtime', ">", $from_time)->where('fldtime', "<", $to_time);
            if (!$notes_result) {
                return false;
            }
            return $notes_result;

        } catch (\Exception $e) {
            return false;
        }
    }


}
