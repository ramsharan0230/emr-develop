<?php

namespace Modules\ICU\Http\Controllers;

use App\ABG;
use App\BillingSet;
use App\Code;
use App\CogentUsers;
use App\Complaints;
use App\Consult;
use App\Departmentbed;
use App\DiagnoGroup;
use App\Encounter;
use App\Exam;
use App\GCS;
use App\ICU;
use App\Notes;
use App\NurseDosing;
use App\PatDosing;
use App\PatFindings;
use App\PatientExam;
use App\PatientFinding;
use App\PatientInfo;
use App\Pupils;
use App\Spine;
use App\Test;
use App\User;
use App\Utils\Helpers;
use App\Utils\Options;
use App\VentilatorParameter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PHPUnit\Exception;

class IcuGeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

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
        $encounter_id_session = Session::get('icu_general_encounter_id');
        $data['patient_status_disabled'] = 0;

        $data['encounter_no'] = null;
//        dd($request->all());
        if ($request->has('encounter_id') || $encounter_id_session) {

            if ($request->has('encounter_id'))
                $encounter_id = $request->get('encounter_id');
            else
                $encounter_id = $encounter_id_session;

            session(['icu_general_encounter_id' => $encounter_id]);

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
            $data['date'] = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date;


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
                $diagnocat = $this->getInitialDiagnosisCategory();
                $data['digno_group'] = $digno_group = DiagnoGroup::select('fldgroupname')->distinct()->get();

                $data['digno_group_list'] = $diagnocat;
                $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $encounter_id)->where('fldcode', '!=', null)->get();
                $data['allergicdrugs'] = Code::select('fldcodename')->whereNotIn('fldcodename', $patientallergicdrugs)->get();
                $data['medicines'] = PatDosing::select('flditem')->where('fldencounterval', $encounter_id)->where('flditemtype', 'Medicines')->get();

            }
            return view('icu::general', $data);
        }
        return view('icu::general', $data);

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
            // dd($data);
            return $data;
        } catch (\Exception $exception) {
            return [];
        }
    }

    public function getDiagnosisByGroup(Request $request)
    {
        $html = '';
        if ($request->get('term')) {
            $groupname = $request->get('term');
            $diagnosiscategories = DiagnoGroup::select('flditemname', 'fldcodeid')->where('fldgroupname', $groupname)->get();
            // dd($diagnosiscategories);
            if (isset($diagnosiscategories) and count($diagnosiscategories) > 0) {
                foreach ($diagnosiscategories as $dc) {
                    $html .= '<tr><td>';
                    $html .= '<input type="checkbox" class="dccat" name="dccat" value="' . $dc['fldcodeid'] . '" id="' . $dc['fldcodeid'] . '"/>';
                    $html .= '<label for="' . $dc['fldcodeid'] . '" class="remove_some_css"></label></td><td>' . $dc['fldcodeid'] . '</td><td>' . $dc['flditemname'] . '</td></tr>';
                }
            } else {
                $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
            }
        } else {
            $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
        }
        echo $html;
        exit;
    }

    public function getDiagnosisByCodes(Request $request)
    {
        $html = '';
        if ($request->get('term')) {

            $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
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
                    $html .= '<tr><td><input type="checkbox" class="diagnosissub" name="diagnosissub" id="' . $d . '" value="' . $d . '"/>';
                    $html .= '<label for="' . $d . '" class="remove_some_css"></label>';
                    $html .= '</td><td>' . $d . '</td</tr>';
                }
            } else {
                $html = '<tr colspan="2"><td>No Diagnosis Available for Diagnosis Code ' . $key . '</td></tr>';
            }
            echo $html;
        } else {
            echo $html = '<tr colspan="2"><td>No Diagnosis Available</td></tr>';
        }
    }

    public function getInitialDiagnosisCategoryAjaxs()
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
            $html = '<tr><td colspan="3">No Diagnosis Available</td></tr>';
        }
        echo $html;
    }

    function diagnosisStore(Request $request)
    {

        try {
            $mytime = Carbon::now();
            $data['fldencounterval'] = $request->encounter_id;
            $data['fldtype'] = 'Provisional Diagnosis';
            $data['fldcode'] = $request->diagnosissubname;
            $data['fldcodeid'] = $request->dccat;
            $data['flduserid'] = Helpers::getCurrentUserName();
            $data['fldtime'] = $mytime->toDateTimeString();
            $data['fldcomp'] = Helpers::getCompName();
            $data['fldsave'] = 1;
            $data['xyz'] = 0;
            $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();

            PatFindings::insert($data);
            $patdiago = PatFindings::where('fldencounterval', $request->encounter_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
            $html = '';
            if (isset($patdiago) and count($patdiago) > 0) {
                foreach ($patdiago as $key => $value) {
                    $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
                }
            }
            echo $html;
            exit;
        } catch (\Exception $e) {
            session()->flash('error_message', __('Error While Adding Diagnosis'));
            return redirect()->route('inpatient');
        }
    }

    public function getDiagnosisByCodeSearch(Request $request)
    {
        $html = '';
        // echo $request->get('term').'-'.$request->get('query'); exit;
        if ($request->get('term')) {

            $handle = fopen(storage_path('upload/icd10cm_order.csv'), 'r');
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
                    if ($request->get('query') != '') {
                        $searchtextlength = strlen($request->get('query'));
                        $compare = substr($d, 0, $searchtextlength);
                        // echo $compare; exit;
                        if (ucfirst($request->get('query')) == $compare) {

                            $html .= '<tr><td><input type="checkbox" class="diagnosissub" name="diagnosissub" value="' . $d . '"/></td><td>' . $d . '</td</tr>';
                        }
                    } else {
                        $html .= '<tr><td><input type="checkbox" class="diagnosissub" name="diagnosissub" value="' . $d . '"/></td><td>' . $d . '</td</tr>';
                    }

                }
            } else {
                $html = '<tr colspan="2"><td>No Diagnosis Available for Diagnosis Code ' . $key . '</td></tr>';
            }
            echo $html;
        } else {
            echo $html = '<tr colspan="2"><td>No Diagnosis Available</td></tr>';
        }
    }

    function finalDiagnosisStore(Request $request)
    {
        try {
            $mytime = Carbon::now();
            $data['fldencounterval'] = $request->encounter_id;
            $data['fldtype'] = 'Final Diagnosis';
            $data['fldcode'] = $request->diagnosissubname;
            $data['fldcodeid'] = $request->dccat;
            $data['flduserid'] = Helpers::getCurrentUserName();
            $data['fldtime'] = $mytime->toDateTimeString();
            $data['fldcomp'] = Helpers::getCompName();
            $data['fldsave'] = 1;
            $data['xyz'] = 0;
            $data['hospital_department_id'] = Helpers::getUserSelectedHospitalDepartmentIdSession();
            PatFindings::insert($data);
            $patdiago = PatFindings::where('fldencounterval', $request->encounter_id)->where('fldtype', 'Final Diagnosis')->where('fldsave', 1)->get();
            $html = '';
            if (isset($patdiago) and count($patdiago) > 0) {
                foreach ($patdiago as $key => $value) {
                    $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
                }
            }
            echo $html;
            exit;
        } catch (\Exception $e) {
            session()->flash('error_message', __('Error While Adding Diagnosis'));
            return redirect()->route('inpatient');
        }
    }

    function insert_allergydrugstore(Request $request)
    {

        try {
            $allergicdrugs = $request->allergydrugs;
            $mytime = Carbon::now();

            if (isset($allergicdrugs) && count($allergicdrugs) > 0) {
                foreach ($allergicdrugs as $ad) {
                    // echo $ad; exit;
                    $data['fldencounterval'] = $request->encounter_id;
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

                $patdrug = PatFindings::where('fldencounterval', $request->encounter_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
                $html = '';
                if (isset($patdrug) and count($patdrug) > 0) {
                    foreach ($patdrug as $value) {
                        $html .= '<option value="' . $value->fldid . '">' . $value->fldcode . '</option>';
                    }
                }
                echo $html;
                exit;
            } else {
                session()->flash('error_message', __('Error While Adding Allergic Drugs'));

                return redirect()->route('inpatient');
            }
        } catch (\Exception $e) {
            dd($e);
            session()->flash('error_message', __('Error While Adding Allergic Drugs'));
            return redirect()->route('inpatient');
        }
    }

    public function searchDrugs()
    {
        $html = '';
        // $patientallergicdrugs = '';
        $searchtext = $_GET['term'];
        $patient_id = $_GET['patient_id'];
        $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $patient_id)->where('fldcode', '!=', null)->get();
        $searchedDrugs = Code::where('fldcodename', 'like', $searchtext . '%')->whereNotIn('fldcodename', $patientallergicdrugs)->get();
        if (isset($searchedDrugs) and count($searchedDrugs) > 0) {
            foreach ($searchedDrugs as $ad) {
                $html .= '<li class="list-group-item"><input type="checkbox" id="' . $ad->fldcodename . '" value="' . $ad->fldcodename . '" class="fldcodename" name="allergydrugs[]" />';
                $html .= '<label class="remove_some_css" for="' . $ad->fldcodename . '"></label>' . $ad->fldcodename . '</li>';
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
        $patientallergicdrugs = PatFindings::select('fldcode')->where('fldencounterval', $patient_id)->where('fldcode', '!=', null)->get();
        $searchedDrugs = Code::select('fldcodename')->whereNotIn('fldcodename', $patientallergicdrugs)->get();
        if (isset($searchedDrugs) and count($searchedDrugs) > 0) {
            foreach ($searchedDrugs as $ad) {
                $html .= '<li class="list-group-item"><input type="checkbox" id="' . $ad->fldcodename . '" value="' . $ad->fldcodename . '" class="fldcodename" name="allergydrugs[]" />';
                $html .= '<label class="remove_some_css" for="' . $ad->fldcodename . '"></label>' . $ad->fldcodename . '</li>';
            }
        } else {
            $html = '<li class="list-group-item">No Drugs Available For' . $searchtext . '</li>';
        }
        echo $html;
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
            $patientval = $request->get('fldpatientval');

            DB::beginTransaction();
            $category = $request->get('category');
            $data = $request->get('data');
            // for bollus data insertion
//            if($category=='bollus'){
//
//
//            }


            if ($data) {
                foreach ($data as $k => $datum) {
                    $final_data = [
                        'fldhead' => $k,
                        'fldvalue' => $datum,
                    ];
                    $this->insertIcuData($encounter_no, $patientval, $category, $final_data);
                }
                DB::commit();
                return \response()->json('Data saved successfully');

            } else {
                return \response()->json('Data is empty,Please enter data');
            }

        } catch (\Exception $exception) {
            dd($exception);
            DB::rollBack();
            return redirect()->route('icu')->with('error_message', 'Unable to save record, Please try again');
        }
    }

    //Insert ICU data
    private function insertIcuData($encounter, $patientval, $category, $data)
    {

        try {

            $constant = [
                'fldencounterval' => $encounter,
                'fldpatientval' => $patientval,
                'fldcategory' => $category,
                'flduser' => Helpers::getCurrentUserName(),
                'created_at' => config('constants.current_date_time'),
                'updated_at' => config('constants.current_date_time'),
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

            ];
            ICU::create(array_merge($constant, $data));

        } catch (\Exception $exception) {
            dd($exception);
        }

    }


    public function insertVitals(Request $request)
    {
        try {

            $encounter_no = $request->get('encounter_id');

            if ($request->get('dbp') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Diastolic BP', 'fldrepquali' => $request->get('dbp')]);
            }

            if ($request->get('map') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'MAP', 'fldrepquali' => $request->get('map')]);
            }

            if ($request->get('air_entry') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Air Entry', 'fldrepquali' => $request->get('air_entry')]);
            }

            if ($request->get('icp') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'ICP', 'fldrepquali' => $request->get('icp')]);
            }

            if ($request->get('cvp') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'CVP', 'fldrepquali' => $request->get('cvp')]);
            }


            if ($request->get('spo') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'O2 Saturation', 'fldrepquali' => $request->get('spo')]);
            }
            if ($request->get('sbp') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Systolic BP', 'fldrepquali' => $request->get('sbp')]);
            }

            if ($request->get('temp') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Temperature (F)', 'fldrepquali' => $request->get('temp_val'), 'fldsysconst' => $request->get('temp')]);
            }
            if ($request->get('pulse') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Pulse Rate', 'fldrepquali' => $request->get('pulse')]);
            }

            if ($request->get('respiratory') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Respiratory', 'fldrepquali' => $request->get('respiratory')]);
            }

            if ($request->get('rhythm') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Rhythm', 'fldrepquali' => $request->get('rhythm')]);

            }

            if ($request->get('pressure') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Pressure', 'fldrepquali' => $request->get('pressure')]);
            }

            if ($request->get('tracheal_suctioning') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Tracheal Suctioning', 'fldrepquali' => $request->get('tracheal_suctioning')]);
            }

            if ($request->get('iv_site_check') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'IV Site Check', 'fldrepquali' => $request->get('iv_site_check')]);
            }

            if ($request->get('position') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'Position', 'fldrepquali' => $request->get('position')]);
            }
            if ($request->get('raas') != null) {
                $this->insertGcs($encounter_no, ['fldhead' => 'RAAS', 'fldrepquali' => $request->get('raas')]);
            }

            return \response()->json(['Vitals saved successfully']);

        } catch (\Exception $exception) {
            return \response()->json(['error', 'Something Went Wrong']);
        }


    }


    private function insertGcs($encounter_no, $mergeData)
    {

        try {
            $vitals = [

                'fldencounterval' => $encounter_no,

                'fldinput' => 'Examination',

                'fldtype' => 'Quantitative',

                'fldtime' => config('constants.current_date_time'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

            ];


            $test = GCS::create(array_merge($vitals, $mergeData));

            return \response()->json('Saved Successfully');

        } catch (Exception $exception) {
            dd($exception);
        }

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

    public function insertIntake(Request $request)
    {

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

    }

    /**
     * insertion of output data
     */
    public function insertOutputs(Request $request)
    {
        try {

            $encounter_no = $request->get('encounter_id');
            if ($request->get('urine') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Urine', 'fldreportquanti' => $request->get('urine')]);
            }
            if ($request->get('gastic_tube') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Gastic Tube', 'fldreportquanti' => $request->get('gastic_tube')]);
            }
            if ($request->get('drain') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Drain', 'fldreportquanti' => $request->get('drain'), 'fldreportquali' => $request->get('drain_value')]);
            }
            if ($request->get('urine_total') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Total', 'fldreportquanti' => $request->get('urine_total')]);
            }
            if ($request->get('extra') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Extra', 'fldreportquanti' => $request->get('extra')]);
            }
            if ($request->get('chest_tube') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Chest Tube', 'fldreportquanti' => $request->get('chest_tube')]);
            }

            if ($request->get('rectal_tube') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Rectal Tube', 'fldreportquanti' => $request->get('rectal_tube')]);
            }
            if ($request->get('dialysis') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Dialysis', 'fldreportquanti' => $request->get('dialysis')]);
            }
            if ($request->get('vomits') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Vomits', 'fldreportquanti' => $request->get('vomits')]);
            }

            if ($request->get('naso_gastric') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Naso Gastric', 'fldreportquanti' => $request->get('naso_gastric')]);
            }

            if ($request->get('maelena') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Maelena', 'fldreportquanti' => $request->get('maelena')]);
            }

            if ($request->get('output_others') != null) {
                $this->insertOutput($encounter_no, ['flditem' => 'Others', 'fldreportquanti' => $request->get('output_others')]);
            }
            return \response()->json('Saved Successfully');

        } catch (Exception $exception) {
            return \response()->json('Something went wrong');
        }


    }

    public function insertGcsData(Request $request)
    {
        try {

            $encounter_no = $request->get('encounter_id');
            $gcs = [
                'fldencounterval' => $encounter_no,

                'fldinput' => 'Examination',

                'fldtype' => 'Qualitative',

                'fldhead' => 'Glasgrow Coma Scale(GCS)',

                'fldsysconst' => 'Glassgrow_coma_scale',

                'fldrepquali' => json_encode([
                    'Eye Response' => $request->get('e'),

                    'Verbal Response' => ($request->get('v') && $request->get('v') == 'T') ? "1" : $request->get('v'),

                    'Motor Response' => $request->get('m'),

                ]),

                'fldrepquanti' => $request->get('total_gcs'),

                'fldtime' => config('constants.current_date_time'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

            ];

            GCS::insert($gcs);

            $pupils = [];
            if ($request->get('left_side_size')) {
                $pupils = [
                    'encounter_no' => $encounter_no,
                    'general_size' => $request->get('left_side_size') ? $request->get('left_side_size') : null,
                    'general_reaction' => $request->get('left_side_reaction') ? $request->get('left_side_reaction') : null,
                ];
            }

            Pupils::create($pupils);
            return \response()->json('Saved Successfully');

        } catch (Exception $exception) {
            return \response()->json('Something went wrong');
        }


    }

    public function insertVentilatorData(Request $request)
    {

        try {
            $encounter_no = $request->get('encounter_id');
            $ventilator_data = [
                'encounter_no' => $encounter_no,
                'ventilation_therapy' => $request->get('ventilation_therapy'),
                'ventilator_mode_and_adjustment' => $request->get('ventilator_mode_and_adjustment'),
                'volume_control' => $request->get('volume_control'),
                'pressure_control_and_support' => $request->get('pressure_control_and_support'),
                'positive_end_expirtatory' => $request->get('positive_end_expirtatory'),
                'peak_inspiratory_airway_pressure' => $request->get('peak_inspiratory_airway_pressure'),
                'mean_airway_pressure' => $request->get('mean_airway_pressure'),
                'tidal_volume' => $request->get('tidal_volume'),
                'respiratory_rate_ventilator' => $request->get('respiratory_rate_ventilator'),
                'expired_minute_ventilation' => $request->get('expired_minute_ventilation'),
                'inspiratory_rate_or_flowrate' => $request->get('inspiratory_rate_or_flowrate'),
                'sensitivity' => $request->get('sensitivity'),
                'inspiratory_rise_time' => $request->get('inspiratory_rise_time'),
                'flow_and_waveform' => $request->get('flow_and_waveform'),
                'fractional_inspiratory_oxygen' => $request->get('fractional_inspiratory_oxygen'),
                'dr_name' => $request->get('dr_name'),
                'time_of_setting_changed' => $request->get('time_of_setting_changed'),
                'humidifier_temp' => $request->get('humidifier_temp'),
                'endotracheal_tube_cuff' => $request->get('endotracheal_tube_cuff'),
                'administered_by' => $request->get('administered_by'),
                'flow_rate' => $request->get('flow_rate'),
                'oxygen_equipment_circuit_change' => $request->get('oxygen_equipment_circuit_change'),
                'fio2' => $request->get('fractional_oxygen'),

            ];
            VentilatorParameter::create($ventilator_data);
            return \response()->json('Saved successfully');

        } catch (Exception $exception) {
            return \response()->json('SOmething went wrong');
        }


    }

    public function insterbollus(Request $request)
    {

        try {
            $encounter_no = $request->get('encounter_id');
            $patientval = $request->get('fldpatientval');

            DB::beginTransaction();
            $category = $request->get('category');
            if ($request->get('bollus')) {
                foreach ($request->get('bollus') as $k => $v) {
                    $final_data = [
                        'fldhead' => 'bollus',
                        'medicine' => $v['medicine'],
                        'fldvalue' => $v['answer'],
                        'intravenous' => $v['intravenous'],
                        'intravenous_val' => $v['intravenous_val'],
                    ];

                    $this->insertIcuData($encounter_no, $patientval, $category, $final_data);
                }
                DB::commit();
                return \response()->json('Data saved successfully');
            } else {
                return \response()->json('Something went wrong');
            }
        } catch (\Exception $exception) {
            dd($exception);
            DB::rollBack();
            return redirect()->route('icu')->with('error_message', 'Unable to save record, Please try again');
        }

    }


    public function resetEncounter()
    {
        Session::forget('icu_general_encounter_id');
        return redirect()->route('icu-general');
    }


}
