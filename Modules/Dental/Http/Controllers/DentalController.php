<?php

namespace Modules\Dental\Http\Controllers;

use App\CogentUsers;
use App\Encounter;
use App\User;
use App\Dental;
use App\Department;
use Carbon\Carbon;
use Session;
use App\Utils\Helpers;
use App\Utils\Options;
use App\PatientExam;
use App\Test;
use App\Exam;
use App\BillingSet;
use App\Complaints;
use App\PatTiming;

use App\PatientInfo;
use App\PatientDate;
use App\ExamGeneral;
use App\DiagnoGroup;
use App\PatFindings;
use App\Code;
use App\PatGeneral;
use App\Pathdosing;
use App\Confinement;
use App\CompExam;
use App\Examlimit;
use App\PatLabTest;
use App\PatRadioTest;
use App\PatPlanning;
use App\Consult;
use App\Departmentbed;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;


class DentalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        // dd(Session::all());
        $tab = '';
        if (isset($request->tab) and $request->tab != '') {
            $tab = $request->tab;
        }


        $data = array();
        $data = [
            'laboratory' => $laboratory = Test::get(),
            'complaint' => $complaint = Complaints::get(),
            'finding' => $finding = Exam::get(),
            'billingset' => $billingset = BillingSet::get(),
            'diagnosisgroup' => DiagnoGroup::select('fldgroupname')->distinct()->get(),
            'diagnosiscategory' => $this->getInitialDiagnosisCategory(),
            'patient_status_disabled' => 0,

        ];
        // dd($data); exit;
        $data['departments'] = DB::table('tbldepartment')
            ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
            ->where('tbldepartment.fldcateg', 'Patient Ward')
            ->where('tbldepartment.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
            ->select('tbldepartment.flddept')
            ->groupBy('tbldepartment.flddept')
            ->get();


        $encounter_id_session = Session::get('dental_encounter_id');
        if ($request->has('encounter_id') || $encounter_id_session) {
            if ($request->has('encounter_id'))
                $encounter_id = $request->get('encounter_id');
            else
                $encounter_id = $encounter_id_session;

            session(['dental_encounter_id' => $encounter_id]);

            $dataflag = array(
                'fldinside' => 1,
            );

            Encounter::where('fldencounterval', $encounter_id)->update($dataflag);

            $data['dental_exam'] = $this->_get_dental_exam_data($encounter_id);


            // dd($data['dental_exam']);
            $dentalData = Dental::select('fldteeth', 'fldvalue', 'fldhead', 'fldinput')->where('fldencounterval', $encounter_id)->get();
            // echo $dentalData; exit;
            $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
//            dd($dentalData);
            $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();
            // echo $current_user; exit;
            /*department change enabled*/
            /*if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
                if (!in_array($data['enpatient']->fldcurrlocat, $current_user->department->pluck('flddept')->toArray())) {
                    Session::flash('display_popup_error_success', true);
                    Session::flash('error_message', 'You are not authorized to view this patients information.');
                    Session::forget('dental_encounter_id');
                    return redirect()->route('admin.dashboard');
                }
            }*/

            $finalarray = array();
            if (isset($dentalData) and count($dentalData) > 0) {
                foreach ($dentalData as $dd) {
                    $finalarray[$dd->fldhead][] = array(
                        'fldteeth' => $dd->fldteeth,
                        'fldhead' => $dd->fldhead,
                        'fldinput' => $dd->fldinput,
                        'fldvalue' => $dd->fldvalue
                    );
                }

            }
            // dd($finalarray);
            $data['basic_info'] = (isset($finalarray['Basic Info'])) ? $finalarray['Basic Info'] : array();
            $data['dentalrest'] = (isset($finalarray['Dental Restoration'])) ? $finalarray['Dental Restoration'] : array();
            $data['ortho'] = (isset($finalarray['Orthodoxtic Finding'])) ? $finalarray['Orthodoxtic Finding'] : array();
            $data['dental_anamolies'] = (isset($finalarray['Dental Anamolies'])) ? $finalarray['Dental Anamolies'] : array();
            $data['cephalometric'] = (isset($finalarray['Cephalometric Finding'])) ? $finalarray['Cephalometric Finding'] : array();

            // dd($data['dentalrest']);
            // dd($finalarray);
            // $data['dental_data'] = $dentalData;
            //       dd($data['dental_data']);
            /*create last encounter id*/
            Helpers::dentalEncounterQueue($encounter_id);
            $encounterIds = Options::get('dental_last_encounter_id');

            $arrayEncounter = unserialize($encounterIds);

            $current_user = CogentUsers::where('id', \Auth::guard('admin_frontend')->user()->id)->with('department')->first();

/*department change enabled*/
            /*if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
                if (!in_array($data['enpatient']->fldcurrlocat, $current_user->department->pluck('flddept')->toArray())) {
                    Session::flash('display_popup_error_success', true);
                    Session::flash('error_message', 'You are not authorized to view this patients information.');
                    Session::forget('dental_encounter_id');
                    return redirect()->route('admin.dashboard');
                }
            }*/
            /*create last encounter id*/
            $data['examgeneral'] = ExamGeneral::where([
                    'fldencounterval' => $encounter_id,
                    'fldinput' => 'Presenting Symptoms',
                    'fldsave' => '1',
                ])->get();
            // echo $data['examgeneral']; exit;
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
            $data['patdiago'] = $patdiago = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();

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

            $data['body_height'] = $body_height = PatientExam::where('fldencounterval', $encounter_id)
                ->where('fldsave', 1)
                ->where('fldsysconst', 'body_height')
                ->orderBy('fldid', 'desc')
                ->first();

            if (isset($body_height)) {
                if ($body_height->fldrepquali <= 100) {
                    $data['heightrate'] = 'cm';
                    $data['height'] = $body_height->fldrepquali;
                } else {
                    $data['heightrate'] = 'm';
                    $data['height'] = round($body_height->fldrepquali / 100, 2);
                }
            } else {
                $data['heightrate'] = 'cm';
                $data['height'] = '';
            }

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
            $data['tab'] = $tab;

        }
        return view('dental::dental', $data);
    }

    public function resetDentalEncounter()
    {
        Session::forget('dental_encounter_id');
        return redirect()->route('dental');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_dental_encounter_number(Request $request)
    {
        // echo "sedfsdfds"; exit;
        $patient_id = $request->get('patient_id');
        $encounters = Encounter::select('fldencounterval')->where('fldpatientval', $patient_id)->orderBy('fldregdate', 'DESC')->get()->toArray();


        $html = '<select name="encounter_id" class="form-control">';
        if (!empty($encounters)) {
            foreach ($encounters as $en) {
                $html .= '<option value="' . $en['fldencounterval'] . '"> ' . $en['fldencounterval'] . '</option>';
            }
        }
        $html .= '</select>';


        return response()->json([
            'success' => [
                'options' => $html,
            ]
        ]);
    }


    public function dentalExamgeneral(Request $request)
    {
        // echo "here"; exit;
        $encounter_id = Session::get('dental_encounter_id');
        $time = date('Y-m-d H:i:s');
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $computer = \App\Utils\Helpers::getCompName();
        $tab = $request->fldtab;
        // echo $tab; exit;
        try {
            // dd($request->all());
            foreach ($request->all() as $key => $value) {
                $formated_key = ucwords(str_replace('_', ' ', $key));
               // echo $encounter_id; exit;
                \App\ExamGeneral::updateOrCreate([
                    'fldencounterval' => $encounter_id,

                    'fldinput' => $formated_key,
                    // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()

                ], [
                    'fldreportquali' => $value,
                    'flduserid' => $userid,
                    'fldtime' => $time,
                    'fldcomp' => $computer,
                    'fldsave' => '1',
                    // 'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                ]);


            }
            // return response()->json([
            //         'status' => TRUE,
            // ]);
        } catch (Exception $e) {
            dd($e);
            session()->flash('success_message', __('Failed to update eye data.'));
            return redirect()->route('dental');
        }

        session()->flash('success_message', __('messages.update', ['name' => 'Eye']));
        return redirect()->route('dental', ['tab' => $tab]);
    }

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
            /*return response()->json(['status' => 'error', 'data' => []]);*/
            return [];
        }
    }

    public function _get_dental_exam_data($encounter_id)
    {
        $tblOtherData = \App\ExamGeneral::where('fldencounterval', $encounter_id)
            ->whereIn('fldinput', [
                'Medical History', 'Dental History', 'Allergy History', 'Dental Notes', 'Diagnosis History', 'Dental Advice', 'Dental Extra Laboratory', 'Dental Procedures'
            ])->pluck('fldreportquali', 'fldinput');
            // dd($tblOtherData);
        $otherData = [];
        foreach ($tblOtherData as $key => $value) {
            $key = str_replace(' ', '_', $key);
            $otherData[strtolower($key)] = $value;
        }

        return compact('otherData');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function displayDynamicView(Request $request)
    {
        $teeth = $request->teeth;
        // echo $teeth; exit;
        $data['teeth'] = $teeth;
        $html = view('dental::dynamic-views.teeth-info', $data)->render();
        return $html;
    }

    public function teethData(Request $request)
    {
        $encounter_id = Session::get('dental_encounter_id');
        // echo $encounter_id; exit;
        $time = date('Y-m-d H:i:s');
        $userid = \App\Utils\Helpers::getCurrentUserName();
        $computer = \App\Utils\Helpers::getCompName();
        // dd($request);
        // basic_info[ims_teeh]
        Dental::where('fldencounterval', $encounter_id)->delete();
        // echo $request->Dental_Advice; exit;
        try {
            $examdata = ([
                'Dental_Advice' => $request->Dental_Advice,
                'Dental_Notes' => $request->Dental_Notes
            ]);
            if (isset($examdata) and !empty($examdata)) {
                foreach ($examdata as $key => $value) {
                    $formated_key = ucwords(str_replace('_', ' ', $key));

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
            }
            // dd($request); exit;
            foreach ($request->all() as $key => $value) {

                if ($key != '_token' && $key != 'Dental_Advice' && $key != 'Dental_Notes') {

                    $looparray = array_chunk($value, 2, true);
                    $loopindexarray = array_chunk($value, 2);
                    foreach ($looparray as $k => $part) {
                        foreach ($part as $kp => $pit) {
                            $data['teethname'] = $kp;
                        }

                        $formatted_head = ucwords(str_replace('_', ' ', $key));
                        $formated_key = ucwords(str_replace('_', ' ', str_replace("'", '', $data['teethname'])));

                        // $existingdata->delete();
                        Dental::insert([
                            'fldencounterval' => $encounter_id,
                            'fldinput' => $formated_key,
                            'fldhead' => $formatted_head,
                            'fldteeth' => $loopindexarray[$k][0],
                            'fldvalue' => $loopindexarray[$k][1],
                            'flduserid' => $userid,
                            'fldtime' => $time,
                            'fldcomp' => $computer,
                            'fldsave' => '1',
                            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                        ]);

                    }
                }
            }
        } catch (Exception $e) {
            dd($e);
            session()->flash('success_message', __('Failed to update dental data.'));
            return redirect()->route('dental');
        }

        session()->flash('success_message', __('messages.update', ['name' => 'Dental']));
        return redirect()->route('dental');
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function generateOpdSheet($id)
    {
        $encounter_id = $id;
        try {
            $encounter = Encounter::where('fldencounterval', $encounter_id)->first();
            $data['encounterData'] = $encounter;
            $data['patient'] = PatientInfo::where('fldpatientval', $encounter->fldpatientval)->first();
            $data['systolic_bp'] = DB::table('tblpatientexam')
                ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Systolic BP')
                 ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                ->orderBy('tblpatientexam.fldid', 'desc')->first();

            $data['diasioli_bp'] = DB::table('tblpatientexam')
                ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Diastolic BP')
                 ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                ->orderBy('tblpatientexam.fldid', 'desc')->first();

            $tblOtherData = \App\ExamGeneral::where('fldencounterval', $encounter_id)
                ->whereIn('fldinput', [
                    'Medical History', 'Dental History', 'Allergy History', 'Dental Notes', 'Diagnosis History', 'Dental Advice', 'Dental Extra Laboratory', 'Dental Procedures'
                ])->pluck('fldreportquali', 'fldinput');
            $otherData = [];
            foreach ($tblOtherData as $key => $value) {
                $key = str_replace(' ', '_', $key);
                $otherData[strtolower($key)] = $value;
            }

            $data['imdData'] = Dental::where('fldinput', 'Imd Click Muscle Pain')->where('fldencounterval', $encounter_id)->first();
            $data['softtissuelessonData'] = Dental::where('fldinput', 'Soft Tissue Lesion')->where('fldencounterval', $encounter_id)->first();
            $data['smoker'] = Dental::where('fldinput', 'Smoker')->where('fldencounterval', $encounter_id)->first();
            $data['periodentalData'] = Dental::where('fldinput', 'Periodental Diseases')->where('fldencounterval', $encounter_id)->first();
            $data['gingivalData'] = Dental::where('fldinput', 'Gingival Recession')->where('fldencounterval', $encounter_id)->first();

            $data['crownData'] = Dental::where('fldinput', 'Crown')->where('fldencounterval', $encounter_id)->first();
            $data['rctData'] = Dental::where('fldinput', 'Rcts')->where('fldencounterval', $encounter_id)->first();
            $data['fillingsData'] = Dental::where('fldinput', 'Fillings')->where('fldencounterval', $encounter_id)->first();
            $data['toothwearsData'] = Dental::where('fldinput', 'Tooth Wears')->where('fldencounterval', $encounter_id)->first();
            $data['extractionData'] = Dental::where('fldinput', 'Extraction')->where('fldencounterval', $encounter_id)->first();
            $data['impactTeethData'] = Dental::where('fldinput', 'Impacted Teeth')->where('fldencounterval', $encounter_id)->first();
            $data['impactTeethData'] = Dental::where('fldinput', 'Impacted Teeth')->where('fldencounterval', $encounter_id)->first();

            $data['hypodontiaTeethData'] = Dental::where('fldinput', 'Hypodontia')->where('fldencounterval', $encounter_id)->first();
            $data['sntData'] = Dental::where('fldinput', 'Super Numerary Teeth')->where('fldencounterval', $encounter_id)->first();
            $data['smallteethData'] = Dental::where('fldinput', 'Small Teeth')->where('fldencounterval', $encounter_id)->first();
            $data['malformedteethData'] = Dental::where('fldinput', 'Malformed Teeth')->where('fldencounterval', $encounter_id)->first();
            $data['sarData'] = Dental::where('fldinput', 'Short Abnormal Roots')->where('fldencounterval', $encounter_id)->first();
            $data['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();


            $data['orthodata'] = Dental::where('fldhead', 'Orthodoxtic Finding')->where('fldencounterval', $encounter_id)->where('fldvalue', '!=', NULL)->get();
            $data['cephalometricData'] = Dental::where('fldhead', 'Cephalometric Finding')->where('fldencounterval', $encounter_id)->where('fldvalue', '!=', NULL)->get();
            // dd($data['orthodata']);
            $data['otherdata'] = $otherData;
            $data['patdiago'] = $patdiago = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
            // dd($data['patdiago']);
            $data['patdrug'] = $patdrug = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
            return view('dental::pdf.opdsheet-dental', $data)/*->setPaper('a4')->stream('dental_opdsheet_encounter_' . $id . '.pdf')*/;

        } catch (Exception $e) {
            dd($e);
            session()->flash('success_message', __('Failed to generate dental OPD Sheet.'));
            return redirect()->route('dental');
        }
    }

    public function dentalHistory($id)
    {
        $encounter = Encounter::where('fldpatientval', $id)->get();
        // dd($encounter);
        $information = array();
        $patient_info = PatientInfo::select('fldptcontact', 'fldencrypt', 'fldptadddist', 'fldptaddvill', 'fldptnamefir', 'fldptnamelast', 'fldencrypt', 'fldptsex', 'fldptbirday', 'fldmidname', 'fldrank')
            ->where('fldpatientval', $id)
            ->first();
        try {
            if (isset($encounter) and count($encounter) > 0) {
                $dentalencounters = Dental::select('fldencounterval')->distinct()->get();
                // dd($dentalencounters);
                $dencounters = array();
                foreach ($dentalencounters as $dent) {
                    $dencounters[] = $dent->fldencounterval;
                }
                $dentenc = array();
                $formtype = array();
                foreach ($encounter as $k => $e) {

                    // dd($dencounters);
                    if (in_array($e->fldencounterval, $dencounters)) {
                        $dentenc[] = $e->fldencounterval;
                        $formtype[] = 'dental';
                    } else {

                        $formtype[] = 'normal';
                        $encounter_id = $e->fldencounterval;

                        $information[$k]['encounter_detail'] = $encounter_detail = Encounter::select('fldencounterval as col', 'fldpatientval', 'fldregdate', 'fldfollowdate', 'fldrank')
                            ->where('fldencounterval', $encounter_id)
                            ->first();

                        $information[$k]['patient_date'] = PatientDate::select('fldhead', 'fldtime', 'fldcomment')
                            ->where('fldencounterval', $encounter_id)
                            ->first();


                        $information[$k]['bed'] = PatTiming::select('fldid', 'flditem', 'fldfirsttime', 'fldsecondtime', 'fldsecondreport')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldtype', 'General Services')
                            ->where('fldfirstreport', 'Bed')
                            ->get();


                        $information[$k]['demographics'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldinput', 'Demographics')
                            ->get();


                        $information[$k]['triage_examinations'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldsave' => '1', 'fldinput' => 'Triage examinations'])
                            ->get();


                        $information[$k]['cause_of_admission'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Cause of Admission'])
                            ->get();


                        $patientExam = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldinput', 'OPD Examination')
                            ->where('fldsave', '1')
                            ->get();


                        $opdData = [];
                        foreach ($patientExam as $opdExam) {
                            $opdData['fldid'][] = $opdExam->fldid;
                            $opdData['fldtime'][] = $opdExam->fldtime;
                            $opdData['fldhead'][] = $opdExam->fldhead;
                            $opdData['fldrepquali'][] = json_decode($opdExam->fldrepquali, true);
                            $opdData['fldrepquanti'][] = $opdExam->fldrepquanti;
                            $opdData['fldtype '][] = $opdExam->fldtype;
                        }

                        $information[$k]['patientExam'] = $opdData;


                        $information[$k]['general_complaints'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'General Complaints'])
                            ->get();

                        $information[$k]['history_illness'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'History of Illness'])
                            ->get();

                        $information[$k]['past_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Past History'])
                            ->get();

                        $information[$k]['treatment_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Treatment History'])
                            ->get();

                        $information[$k]['medicated_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Medication History'])
                            ->get();

                        $information[$k]['family_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Family History'])
                            ->get();

                        $information[$k]['personal_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Personal History'])
                            ->get();

                        $information[$k]['surgical_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Surgical History'])
                            ->get();

                        $information[$k]['occupational_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Occupational History'])
                            ->get();

                        $information[$k]['social_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Social History'])
                            ->get();

                        $information[$k]['allergy_drugs'] = PatFindings::select('fldcode', 'fldcodeid')
                            ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Allergic Drugs', 'fldsave' => '1'])
                            ->get();

                        $information[$k]['provisinal_diagnosis'] = PatFindings::select('fldcode', 'fldcodeid')
                            ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Provisional Diagnosis', 'fldsave' => '1'])
                            ->get();

                        $information[$k]['initial_planning'] = ExamGeneral::select('flddetail', 'fldtime')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes', 'flditem' => 'Initial Planning'])
                            ->get();

                        $information[$k]['final_diagnosis'] = PatFindings::select('fldcode', 'fldcodeid')
                            ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Final Diagnosis', 'fldsave' => '1'])
                            ->get();

                        $information[$k]['prominent_symptoms'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Patient Symptoms', 'fldsave' => '1'])
                            ->get();

                        $information[$k]['procedures'] = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Procedures', 'fldreportquali' => 'Done'])
                            ->get();

                        $information[$k]['minor_procedure'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Minor Procedures', 'fldreportquali' => 'Done'])
                            ->get();

                        $information[$k]['consult'] = Consult::select('fldconsultname', 'fldconsulttime', 'fldstatus')
                            ->where('fldencounterval', $encounter_id)
                            ->get();

                        $information[$k]['equipment'] = PatTiming::select('flditem', 'fldfirsttime', 'fldsecondtime', 'fldsecondreport')
                            ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Equipment'])
                            ->get();

                        $information[$k]['planned'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Extra Procedures', 'fldreportquali' => 'Planned'])
                            ->get();


                        $information[$k]['mainDataForPatDosing'] = $mainDataForPatDosing = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'flditemtype')
                            ->where('fldencounterval', $encounter_id)
                            ->where('flditemtype', 'Medicines')
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldstatus', '=', 'Registered')
                                    ->orWhere('fldstatus', '=', 'Admitted')
                                    ->orWhere('fldstatus', '=', 'Recorded');
                            })
                            ->get();

                        $information[$k]['singleData'] = [];
                        foreach ($mainDataForPatDosing as $singlePatDosing) {
                            $information[$k]['singleData'] = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays')
                                ->where('fldencounterval', $encounter_id)
                                ->where('flditemtype', 'Medicines')
                                ->where('fldroute', $singlePatDosing->fldroute)
                                ->where('flditem', $singlePatDosing->flditem)
                                ->where('flddose', $singlePatDosing->flddose)
                                ->where('fldfreq', $singlePatDosing->fldfreq)
                                ->where(function ($query) {
                                    return $query
                                        ->orWhere('fldstatus', '=', 'Registered')
                                        ->orWhere('fldstatus', '=', 'Admitted')
                                        ->orWhere('fldstatus', '=', 'Recorded');
                                })
                                ->first();
                        }


                        $information[$k]['confinement'] = Confinement::select('fldid', 'flddelresult', 'flddeltype', 'fldbabypatno', 'flddeltime', 'flddelwt')
                            ->where('fldencounterval', $encounter_id)
                            ->get();


                        $fldexamid = CompExam::where('fldcategory', 'Essential examinations')
                            ->pluck('fldexamid');

                        $information[$k]['fldhead'] = PatientExam::select('fldhead as col')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldsave', '1')
                            ->whereIn('fldhead', $fldexamid)
                            ->distinct()
                            ->get();


                        $information[$k]['fldheadNotIn'] = PatientExam::select('fldhead as col')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldsave', '1')
                            ->whereNotIn('fldhead', $fldexamid)
                            ->distinct()
                            ->get();


                        $information[$k]['patientSerialValue'] = PatientExam::select('fldserialval as col')
                            ->where('fldserialval', 'like', '%')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Rec Examination', 'fldsave' => '1'])
                            ->distinct()
                            ->get();


                        $information[$k]['AntenatalExam3rd'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Antenatal Examination - 3RD TIMESTER', 'fldsave' => '1', 'fldinput' => 'Examination'])
                            ->get();


                        $information[$k]['Saturation'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'O2 Saturation', 'fldsave' => '1', 'fldinput' => 'Examination'])
                            ->with(['patientSubExam'])
                            ->get();


                        $information[$k]['OptionSaturation'] = Exam::select('fldoption')
                            ->where('fldexamid', 'O2 Saturation')
                            ->get();


                        $information[$k]['PulseRatePatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Pulse Rate', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->orderBy('fldid', 'DESC')
                            ->first();


                        $information[$k]['PulseRateExamLimit'] = Examlimit::select('fldunit')
                            ->where('fldexamid', 'Pulse Rate')
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldptsex', '=', 'Male')
                                    ->orWhere('fldptsex', '=', 'Both Sex');
                            })
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldagegroup', '=', 'Adolescent')
                                    ->orWhere('fldagegroup', '=', 'All Age');
                            })
                            ->first();


                        $information[$k]['SystolicBPPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Systolic BP', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->orderBy('fldid', 'DESC')
                            ->first();


                        $information[$k]['SystolicBPExamLimit'] = Examlimit::select('fldunit')
                            ->where('fldexamid', 'Systolic BP')
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldptsex', '=', 'Male')
                                    ->orWhere('fldptsex', '=', 'Both Sex');
                            })
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldagegroup', '=', 'Adolescent')
                                    ->orWhere('fldagegroup', '=', 'All Age');
                            })
                            ->orderBy('fldid', 'DESC')
                            ->first();


                        $information[$k]['DiastolicBPPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Diastolic BP', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->orderBy('fldid', 'DESC')
                            ->first();


                        $information[$k]['DiastolicBPExamLimit'] = Examlimit::select('fldunit')
                            ->where('fldexamid', 'Systolic BP')
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldptsex', '=', 'Male')
                                    ->orWhere('fldptsex', '=', 'Both Sex');
                            })
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldagegroup', '=', 'Adolescent')
                                    ->orWhere('fldagegroup', '=', 'All Age');
                            })
                            ->orderBy('fldid', 'DESC')
                            ->first();


                        $information[$k]['AnkleJerkPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Ankle Jerk', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();


                        $information[$k]['ADRProbabilityScalePatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'ADR Probability Scale (Naranjo)', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();


                        $information[$k]['AbdominalGirthPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Abdominal Girth', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();


                        $information[$k]['AbdomenExaminationPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Abdomen Examination', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();


                        $information[$k]['ActivityPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Activity', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();


                        $information[$k]['LocalExaminationPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Local Examination', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();


                        $information[$k]['BreastFeedingExaminationPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Breast Feeding', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();


                        $information[$k]['BodyHeightPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Body Height', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();


                        $information[$k]['AntenatalExamination2NdPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Antenatal Examination -2ND TRIMESTER', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        $information[$k]['PregnancyStatusPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Pregnancy Status', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();


                        $information[$k]['reportedPatLab'] = PatLabTest::where('fldencounterval', $encounter_id)
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldstatus', '=', 'Reported')
                                    ->orWhere('fldstatus', '=', 'Verified');
                            })
                            ->with(['patTestResults', 'subTest', 'testLimit'])
                            ->get();


                        $information[$k]['patRadioTest'] = PatRadioTest::where('fldencounterval', $encounter_id)
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldstatus', '=', 'Reported')
                                    ->orWhere('fldstatus', '=', 'Verified');
                            })
                            ->with(['radioData', 'radioSubTest'])
                            ->get();


                        $information[$k]['generalExamProgressCliniciansNurses'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                            ->where('fldencounterval', $encounter_id)
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('flditem', '=', 'Progress Note')
                                    ->orWhere('flditem', '=', 'Clinicians Note')
                                    ->orWhere('flditem', '=', 'Nurses Note');
                            })
                            ->get();


                        $information[$k]['IPMonitoringPatPlanning'] = PatPlanning::select('fldid', 'fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldplancategory', 'IP Monitoring')
                            ->get();


                        $information[$k]['ClinicianPlanPatPlanning'] = PatPlanning::select('fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldplancategory', 'Clinician Plan')
                            ->get();


                        $information[$k]['patGeneral'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldinput', 'Procedures')
                            ->where('fldreportquali', 'Planned')
                            ->get();


                        $information[$k]['DischargeExaminationspatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldsave', '1')
                            ->where('fldinput', 'Discharge examinations')
                            ->get();


                        $information[$k]['ConditionOfDischargeExamGeneral'] = ExamGeneral::select('flddetail', 'fldtime')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldinput', 'Notes')
                            ->where('flditem', 'Condition of Discharge')
                            ->get();


                        $information[$k]['DischargedLAMADeathReferAbsconderPatDosing'] = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'flditemtype')
                            ->where('fldencounterval', $encounter_id)
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldstatus', '=', 'Discharged')
                                    ->orWhere('fldstatus', '=', 'LAMA')
                                    ->orWhere('fldstatus', '=', 'Death')
                                    ->orWhere('fldstatus', '=', 'Absconder')
                                    ->orWhere('fldstatus', '=', 'Refer');
                            })
                            ->get();

                        $information[$k]['AdviceOfDischargeExamGeneral'] = ExamGeneral::select('flddetail', 'fldtime')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldinput', 'Notes')
                            ->where('flditem', 'Advice on Discharge')
                            ->get();

                        $information[$k]['present_symptoms'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'fldreportquanti', 'flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Presenting Symptoms', 'fldsave' => '1'])
                            ->get();

                        $information[$k]['systolic_bp'] = $systolic_bp = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Systolic BP')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();

                        $information[$k]['diasioli_bp'] = $diasioli_bp = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Diastolic BP')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();

                        $information[$k]['pulse'] = $pulse = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Pulse Rate')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();

                        $information[$k]['temperature'] = $temperature = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Pulse RatePulse Rate')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();

                        $information[$k]['respiratory_rate'] = $respiratory_rate = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Respiratory Rate')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();


                        $information[$k]['o2_saturation'] = $o2_saturation = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'O2 Saturation')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();
                    }
                }
                // $dentalencounters = Dental::select('fldencounterval')->distinct()->get();
                $data['dentalencounters'] = $dentenc;
                $data['patient'] = PatientInfo::where('fldpatientval', $id)->first();
                $data['dentencounters'] = implode(',', $dencounters);

                $data['patientinfo'] = $patient_info;
                $data['encounters'] = $information;
                $data['formtype'] = $formtype;
                return view('dental::pdf.history-dental', $data)/*->setPaper('a4')->stream('history.pdf')*/;
                // if($formtype == 'normal'){

                // }else{
                //     return view('dental::pdf.history-dental', $data)->setPaper('a4')->stream('dental_history_patient_'.$id.'.pdf');
                // }
            }

        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function dentalHistoryPdf($patientId)
    {
        $patient_id = $patientId;

        $information = array();
        $data['title'] = 'ALL ENCOUNTERS REPORT';
        if ($patient_id) {
            $patient_info = PatientInfo::select('fldptcontact', 'fldpatientval', 'fldencrypt', 'fldptadddist', 'fldptaddvill', 'fldptnamefir', 'fldptnamelast', 'fldencrypt', 'fldptsex', 'fldptbirday', 'fldmidname', 'fldrank')
                ->where('fldpatientval', $patient_id)
                ->first();

            $patient_encounter_ids = Encounter::select('fldencounterval')
                ->where('fldpatientval', $patient_id)
                ->orderBy('fldregdate', 'DESC')
                ->get();

            if ($patient_encounter_ids) {
                foreach ($patient_encounter_ids as $k => $encounter) {
                    $encounter_id = $encounter->fldencounterval;
                    $encounterId = $encounter->fldencounterval;
                    if (Dental::where('fldencounterval', $encounterId)->count() > 0) {
                        $information[$k]['systolic_bp'] = DB::table('tblpatientexam')
                            ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Systolic BP')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();
                        $information[$k]['encounter_detail'] = $encounter_detail = Encounter::select('fldencounterval as col', 'fldpatientval', 'fldregdate', 'fldfollowdate','fldrank')
                            ->where('fldencounterval', $encounter_id)

                            ->first();
                        $information[$k]['diasioli_bp'] = DB::table('tblpatientexam')
                            ->leftjoin('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Diastolic BP')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();

                        $tblOtherData = \App\ExamGeneral::where('fldencounterval', $encounter_id)
                            ->whereIn('fldinput', [
                                'Medical History', 'Dental History', 'Allergy History', 'Dental Notes', 'Diagnosis History', 'Dental Advice', 'Dental Extra Laboratory', 'Dental Procedures'
                            ])->pluck('fldreportquali', 'fldinput');
                        $otherData = [];
                        foreach ($tblOtherData as $key => $value) {
                            $key = str_replace(' ', '_', $key);
                            $otherData[strtolower($key)] = $value;
                        }

                        $information[$k]['imdData'] = Dental::where('fldinput', 'Imd Click Muscle Pain')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['softtissuelessonData'] = Dental::where('fldinput', 'Soft Tissue Lesion')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['smoker'] = Dental::where('fldinput', 'Smoker')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['periodentalData'] = Dental::where('fldinput', 'Periodental Diseases')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['gingivalData'] = Dental::where('fldinput', 'Gingival Recession')->where('fldencounterval', $encounter_id)->first();

                        $information[$k]['crownData'] = Dental::where('fldinput', 'Crown')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['rctData'] = Dental::where('fldinput', 'Rcts')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['fillingsData'] = Dental::where('fldinput', 'Fillings')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['toothwearsData'] = Dental::where('fldinput', 'Tooth Wears')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['extractionData'] = Dental::where('fldinput', 'Extraction')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['impactTeethData'] = Dental::where('fldinput', 'Impacted Teeth')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['impactTeethData'] = Dental::where('fldinput', 'Impacted Teeth')->where('fldencounterval', $encounter_id)->first();

                        $information[$k]['hypodontiaTeethData'] = Dental::where('fldinput', 'Hypodontia')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['sntData'] = Dental::where('fldinput', 'Super Numerary Teeth')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['smallteethData'] = Dental::where('fldinput', 'Small Teeth')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['malformedteethData'] = Dental::where('fldinput', 'Malformed Teeth')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['sarData'] = Dental::where('fldinput', 'Short Abnormal Roots')->where('fldencounterval', $encounter_id)->first();
                        $information[$k]['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();


                        $information[$k]['orthodata'] = Dental::where('fldhead', 'Orthodoxtic Finding')->where('fldencounterval', $encounter_id)->where('fldvalue', '!=', NULL)->get();
                        $information[$k]['cephalometricData'] = Dental::where('fldhead', 'Cephalometric Finding')->where('fldencounterval', $encounter_id)->where('fldvalue', '!=', NULL)->get();
                        // dd($data['orthodata']);
                        $information[$k]['otherdata'] = $otherData;
                        $information[$k]['patdiago'] = $patdiago = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
                        // dd($data['patdiago']);
                        $information[$k]['patdrug'] = $patdrug = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Allergic Drugs')->where('fldsave', 1)->get();
                        $information[$k]['form_type'] = 'dental';
                    } else {
                        $information[$k]['form_type'] = 'normal';
                        $information[$k]['encounter_detail'] = $encounter_detail = Encounter::select('fldencounterval as col', 'fldpatientval', 'fldregdate', 'fldfollowdate', 'fldrank')
                            ->where('fldencounterval', $encounter_id)
                            ->first();

                        $information[$k]['patient_date'] = PatientDate::select('fldhead', 'fldtime', 'fldcomment')
                            ->where('fldencounterval', $encounter_id)
                            ->first();


                        $information[$k]['bed'] = PatTiming::select('fldid', 'flditem', 'fldfirsttime', 'fldsecondtime', 'fldsecondreport')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldtype', 'General Services')
                            ->where('fldfirstreport', 'Bed')
                            ->get();


                        $information[$k]['demographics'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldinput', 'Demographics')
                            ->get();


                        $information[$k]['triage_examinations'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldsave' => '1', 'fldinput' => 'Triage examinations'])
                            ->get();


                        $information[$k]['cause_of_admission'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Cause of Admission'])
                            ->get();


                        $patientExam = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldinput', 'OPD Examination')
                            ->where('fldsave', '1')
                            ->get();


                        $opdData = [];
                        foreach ($patientExam as $opdExam) {
                            $opdData['fldid'][] = $opdExam->fldid;
                            $opdData['fldtime'][] = $opdExam->fldtime;
                            $opdData['fldhead'][] = $opdExam->fldhead;
                            $opdData['fldrepquali'][] = json_decode($opdExam->fldrepquali, true);
                            $opdData['fldrepquanti'][] = $opdExam->fldrepquanti;
                            $opdData['fldtype '][] = $opdExam->fldtype;
                        }

                        $information[$k]['patientExam'] = $opdData;
                        //dd($information[$k]['patientExam']);


                        // $exams = DB::table('tblexam')
                        //     ->join('tblpatientexam', 'tblpatientexam.fldhead', '=', 'tblexam.fldexamid')
                        //     ->select('tblpatientexam.*', 'tblexam.*')
                        //     ->where('tblpatientexam.fldencounterval', $encounter_id)
                        //     ->get();

                        // if ($exams) {
                        //     foreach ($exams as $ex) {
                        //         //select fldunit from tblexamlimit where fldexamid='ADR Probability Scale (Naranjo)' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='All Age')

                        //         $subexam = PatientSubExam::where(['fldheadid' => $ex->fldid, 'fldencounterval' => $encounter_id])->get();
                        //     }
                        // }


                        $information[$k]['general_complaints'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'General Complaints'])
                            ->get();

                        $information[$k]['history_illness'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'History of Illness'])
                            ->get();

                        $information[$k]['past_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Past History'])
                            ->get();

                        $information[$k]['treatment_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Treatment History'])
                            ->get();

                        $information[$k]['medicated_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Medication History'])
                            ->get();

                        $information[$k]['family_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Family History'])
                            ->get();

                        $information[$k]['personal_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Personal History'])
                            ->get();

                        $information[$k]['surgical_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Surgical History'])
                            ->get();

                        $information[$k]['occupational_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Occupational History'])
                            ->get();

                        $information[$k]['social_history'] = ExamGeneral::select('flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'History', 'flditem' => 'Social History'])
                            ->get();

                        $information[$k]['allergy_drugs'] = PatFindings::select('fldcode', 'fldcodeid')
                            ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Allergic Drugs', 'fldsave' => '1'])
                            ->get();

                        $information[$k]['provisinal_diagnosis'] = PatFindings::select('fldcode', 'fldcodeid')
                            ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Provisional Diagnosis', 'fldsave' => '1'])
                            ->get();

                        $information[$k]['initial_planning'] = ExamGeneral::select('flddetail', 'fldtime')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Notes', 'flditem' => 'Initial Planning'])
                            ->get();

                        $information[$k]['final_diagnosis'] = PatFindings::select('fldcode', 'fldcodeid')
                            ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Final Diagnosis', 'fldsave' => '1'])
                            ->get();

                        $information[$k]['prominent_symptoms'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Patient Symptoms', 'fldsave' => '1'])
                            ->get();

                        $information[$k]['procedures'] = PatGeneral::select('fldid', 'fldencounterval', 'fldnewdate', 'flditem', 'flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Procedures', 'fldreportquali' => 'Done'])
                            ->get();

                        $information[$k]['minor_procedure'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Minor Procedures', 'fldreportquali' => 'Done'])
                            ->get();

                        $information[$k]['consult'] = Consult::select('fldconsultname', 'fldconsulttime', 'fldstatus')
                            ->where('fldencounterval', $encounter_id)
                            ->get();

                        $information[$k]['equipment'] = PatTiming::select('flditem', 'fldfirsttime', 'fldsecondtime', 'fldsecondreport')
                            ->where(['fldencounterval' => $encounter_id, 'fldtype' => 'Equipment'])
                            ->get();

                        $information[$k]['planned'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Extra Procedures', 'fldreportquali' => 'Planned'])
                            ->get();


                        /*select fldid,fldroute,flditem,flddose,fldfreq,flddays,flditemtype from tblpatdosing where fldencounterval='1' and flditemtype='Medicines' and (fldstatus='Registered' or fldstatus='Admitted' or fldstatus='Recorded')*/
                        /*select fldid,fldroute,flditem,flddose,fldfreq,flddays,flditemtype from tblpatdosing where fldencounterval='1' and flditemtype='Medicines' and (fldstatus='Registered' or fldstatus='Admitted' or fldstatus='Recorded')*/
                        $information[$k]['mainDataForPatDosing'] = $mainDataForPatDosing = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'flditemtype')
                            ->where('fldencounterval', $encounter_id)
                            ->where('flditemtype', 'Medicines')
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldstatus', '=', 'Registered')
                                    ->orWhere('fldstatus', '=', 'Admitted')
                                    ->orWhere('fldstatus', '=', 'Recorded');
                            })
                            ->get();

                        $information[$k]['singleData'] = [];
                        foreach ($mainDataForPatDosing as $singlePatDosing) {
                            $information[$k]['singleData'] = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays')
                                ->where('fldencounterval', $encounter_id)
                                ->where('flditemtype', 'Medicines')
                                ->where('fldroute', $singlePatDosing->fldroute)
                                ->where('flditem', $singlePatDosing->flditem)
                                ->where('flddose', $singlePatDosing->flddose)
                                ->where('fldfreq', $singlePatDosing->fldfreq)
                                ->where(function ($query) {
                                    return $query
                                        ->orWhere('fldstatus', '=', 'Registered')
                                        ->orWhere('fldstatus', '=', 'Admitted')
                                        ->orWhere('fldstatus', '=', 'Recorded');
                                })
                                ->first();
                        }


                        // $delivery_profile = PatTiming::select()->where()->get();
                        // $essential_exam =PatTiming::select()->where()->get();


                        $information[$k]['confinement'] = Confinement::select('fldid', 'flddelresult', 'flddeltype', 'fldbabypatno', 'flddeltime', 'flddelwt')
                            ->where('fldencounterval', $encounter_id)
                            ->get();


                        /*select distinct(fldhead) as col from tblpatientexam where fldencounterval='1' and fldsave='1' and fldhead in(select fldexamid from tblcompexam where fldcomp=Helpers::getCompName() and fldcategory='Essential examinations')*/

                        $fldexamid = CompExam::where('fldcategory', 'Essential examinations')
                            ->pluck('fldexamid');

                        $information[$k]['fldhead'] = PatientExam::select('fldhead as col')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldsave', '1')
                            ->whereIn('fldhead', $fldexamid)
                            ->distinct()
                            ->get();


                        /*select distinct(fldhead) as col from tblpatientexam where fldencounterval='1' and fldsave='1' and fldhead not in(select fldexamid from tblcompexam where fldcomp=Helpers::getCompName() and fldcategory='Essential examinations')*/
                        $information[$k]['fldheadNotIn'] = PatientExam::select('fldhead as col')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldsave', '1')
                            ->whereNotIn('fldhead', $fldexamid)
                            ->distinct()
                            ->get();

                        /*select distinct(fldserialval) as col from tblpatientexam where fldserialval like '%' and fldencounterval='1' and fldinput='Rec Examination' and fldsave='1'*/
                        $information[$k]['patientSerialValue'] = PatientExam::select('fldserialval as col')
                            ->where('fldserialval', 'like', '%')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Rec Examination', 'fldsave' => '1'])
                            ->distinct()
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Antenatal Examination - 3RD TIMESTER' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['AntenatalExam3rd'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Antenatal Examination - 3RD TIMESTER', 'fldsave' => '1', 'fldinput' => 'Examination'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='O2 Saturation' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['Saturation'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'O2 Saturation', 'fldsave' => '1', 'fldinput' => 'Examination'])
                            ->with(['patientSubExam'])
                            ->get();

                        /*select fldoption from tblexam where fldexamid='O2 Saturation'*/
                        $information[$k]['OptionSaturation'] = Exam::select('fldoption')
                            ->where('fldexamid', 'O2 Saturation')
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Pulse Rate' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['PulseRatePatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Pulse Rate', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->orderBy('fldid', 'DESC')
                            ->first();

                        /*select fldunit from tblexamlimit where fldexamid='Pulse Rate' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='All Age')*/
                        $information[$k]['PulseRateExamLimit'] = Examlimit::select('fldunit')
                            ->where('fldexamid', 'Pulse Rate')
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldptsex', '=', 'Male')
                                    ->orWhere('fldptsex', '=', 'Both Sex');
                            })
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldagegroup', '=', 'Adolescent')
                                    ->orWhere('fldagegroup', '=', 'All Age');
                            })
                            ->first();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Systolic BP' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['SystolicBPPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Systolic BP', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->orderBy('fldid', 'DESC')
                            ->first();

                        /*select fldunit from tblexamlimit where fldexamid='Systolic BP' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='All Age')*/
                        $information[$k]['SystolicBPExamLimit'] = Examlimit::select('fldunit')
                            ->where('fldexamid', 'Systolic BP')
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldptsex', '=', 'Male')
                                    ->orWhere('fldptsex', '=', 'Both Sex');
                            })
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldagegroup', '=', 'Adolescent')
                                    ->orWhere('fldagegroup', '=', 'All Age');
                            })
                            ->orderBy('fldid', 'DESC')
                            ->first();


                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Diastolic BP' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['DiastolicBPPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Diastolic BP', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->orderBy('fldid', 'DESC')
                            ->first();


                        /*select fldunit from tblexamlimit where fldexamid='Diastolic BP' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='Diastolic BP')*/
                        $information[$k]['DiastolicBPExamLimit'] = Examlimit::select('fldunit')
                            ->where('fldexamid', 'Systolic BP')
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldptsex', '=', 'Male')
                                    ->orWhere('fldptsex', '=', 'Both Sex');
                            })
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldagegroup', '=', 'Adolescent')
                                    ->orWhere('fldagegroup', '=', 'All Age');
                            })
                            ->orderBy('fldid', 'DESC')
                            ->first();


                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Ankle Jerk' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['AnkleJerkPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Ankle Jerk', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='ADR Probability Scale (Naranjo)' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['ADRProbabilityScalePatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'ADR Probability Scale (Naranjo)', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Abdominal Girth' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['AbdominalGirthPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Abdominal Girth', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Abdomen Examination' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['AbdomenExaminationPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Abdomen Examination', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Activity' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['ActivityPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Activity', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Local Examination' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['LocalExaminationPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Local Examination', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Breast Feeding' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['BreastFeedingExaminationPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Breast Feeding', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Body Height' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['BodyHeightPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Body Height', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Antenatal Examination -2ND TRIMESTER' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['AntenatalExamination2NdPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Antenatal Examination -2ND TRIMESTER', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldhead='Pregnancy Status' and fldsave='1' and fldinput='Examination'*/
                        $information[$k]['PregnancyStatusPatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where(['fldencounterval' => $encounter_id, 'fldhead' => 'Pregnancy Status', 'fldinput' => 'Examination', 'fldsave' => '1'])
                            ->get();


                        /*select distinct(fldtestid) as col from tblpatlabtest where fldencounterval='1' and (fldstatus='Reported' or fldstatus='Verified')*/
                        /*
                         * select fldtype from tbltest where fldtestid='Differential Leucocytes Count'

                        select fldid,fldencounterval,fldtime_sample,fldsampletype,fldreportquali,fldtest_type from tblpatlabtest where fldencounterval='1' and fldtestid='Differential Leucocytes Count' and (fldstatus='Reported' or fldstatus='Verified')

                        select fldsubtest,fldreport,fldid,fldtestid,fldtanswertype from tblpatlabsubtest where fldtestid=1 and fldsave='1' and fldencounterval='1'

                        >>>>>ESR
                             select fldtype from tbltest where fldtestid='ESR'========

                             select fldid,fldencounterval,fldtime_sample,fldsampletype,fldreportquali,fldtest_type from tblpatlabtest where fldencounterval='1' and fldtestid='ESR' and (fldstatus='Reported' or fldstatus='Verified')

                            select fldencounterval,fldreportquanti,fldtestunit from tblpatlabtest where fldid=2 and fldencounterval='1'------------------------------------------

                            select fldencounterval,fldtestid,fldmethod from tblpatlabtest where fldid=2 and fldtest_type='Quantitative' and fldencounterval='1'------------------------------

                            select fldconvfactor as conv from tbltestlimit where fldtestid='ESR' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='All Age')------------------------------

                        >>>>>Haemoglobin (Hb%)

                        select fldtype from tbltest where fldtestid='Haemoglobin (Hb%)'------------------
                        select fldid,fldencounterval,fldtime_sample,fldsampletype,fldreportquali,fldtest_type from tblpatlabtest where fldencounterval='1' and fldtestid='Haemoglobin (Hb%)' and (fldstatus='Reported' or fldstatus='Verified')-------------------

                        select fldencounterval,fldreportquanti,fldtestunit from tblpatlabtest where fldid=3 and fldencounterval='1'--------------------

                        select fldencounterval,fldtestid,fldmethod from tblpatlabtest where fldid=3 and fldtest_type='Quantitative' and fldencounterval='1'----------------

                        2020-03-27 10:04:10.346 gb.db.mysql: 0x55fd78e188a0: select fldconvfactor as conv from tbltestlimit where fldtestid='Haemoglobin (Hb%)' and (fldptsex='Male' or fldptsex='Both Sex') and (fldagegroup='Adolescent' or fldagegroup='All Age')
                        */


                        $information[$k]['reportedPatLab'] = PatLabTest::where('fldencounterval', $encounter_id)
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldstatus', '=', 'Reported')
                                    ->orWhere('fldstatus', '=', 'Verified');
                            })
                            ->with(['patTestResults', 'subTest', 'testLimit'])
                            ->get();


                        /*select distinct(fldtestid) as col from tblpatradiotest where fldencounterval='1' and (fldstatus='Reported' or fldstatus='Verified')*/

                        /*
                         * select fldtype from tblradio where fldexamid='USG OF ABDOMEN AND PELVIS (MALE)'====

                        select fldtype from tblradio where fldexamid='USG OF ABDOMEN AND PELVIS (MALE)'====

                        select fldid,fldtime_report,fldreportquanti,fldreportquali,fldtest_type from tblpatradiotest where fldencounterval='1' and fldtestid='USG OF ABDOMEN AND PELVIS (MALE)' and (fldstatus='Reported' or fldstatus='Verified')

                        2020-03-27 10:04:10.349 gb.db.mysql: 0x55fd78e188a0: select fldsubtest,fldreport,fldid,fldtestid,fldtanswertype from tblpatradiosubtest where fldtestid=1 and fldsave='1' and fldencounterval='1'
                        */


                        $information[$k]['patRadioTest'] = PatRadioTest::where('fldencounterval', $encounter_id)
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldstatus', '=', 'Reported')
                                    ->orWhere('fldstatus', '=', 'Verified');
                            })
                            ->with(['radioData', 'radioSubTest'])
                            ->get();


                        /*select fldtime,flditem,fldreportquali,flddetail from tblexamgeneral where fldencounterval='1' and fldinput='Notes' and (flditem='Progress Note' or flditem='Clinicians Note' or flditem='Nurses Note')*/
                        $information[$k]['generalExamProgressCliniciansNurses'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'flddetail')
                            ->where('fldencounterval', $encounter_id)
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('flditem', '=', 'Progress Note')
                                    ->orWhere('flditem', '=', 'Clinicians Note')
                                    ->orWhere('flditem', '=', 'Nurses Note');
                            })
                            ->get();

                        /*select fldid,fldtime,fldproblem,fldsubjective,fldobjective,fldassess,fldplan from tblpatplanning where fldencounterval='1' and fldplancategory='IP Monitoring'*/
                        $information[$k]['IPMonitoringPatPlanning'] = PatPlanning::select('fldid', 'fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldplancategory', 'IP Monitoring')
                            ->get();

                        /*select fldid,fldtime,fldproblem,fldsubjective,fldobjective,fldassess,fldplan from tblpatplanning where fldencounterval='1' and fldplancategory='Clinician Plan'*/
                        $information[$k]['ClinicianPlanPatPlanning'] = PatPlanning::select('fldtime', 'fldproblem', 'fldsubjective', 'fldobjective', 'fldassess', 'fldplan')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldplancategory', 'Clinician Plan')
                            ->get();

                        /*select fldid,fldnewdate,flditem,flddetail from tblpatgeneral where fldencounterval='1' and fldinput='Procedures' and fldreportquali='Planned'*/
                        $information[$k]['patGeneral'] = PatGeneral::select('fldid', 'fldnewdate', 'flditem', 'flddetail')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldinput', 'Procedures')
                            ->where('fldreportquali', 'Planned')
                            ->get();

                        /*select fldid,fldtime,fldhead,fldrepquali,fldrepquanti,fldtype from tblpatientexam where fldencounterval='1' and fldsave='1' and fldinput='Discharge examinations'*/
                        $information[$k]['DischargeExaminationspatientExam'] = PatientExam::select('fldid', 'fldtime', 'fldhead', 'fldrepquali', 'fldrepquanti', 'fldtype')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldsave', '1')
                            ->where('fldinput', 'Discharge examinations')
                            ->get();

                        /*select flddetail,fldtime from tblexamgeneral where fldencounterval='1' and fldinput='Notes' and flditem='Condition of Discharge'*/
                        $information[$k]['ConditionOfDischargeExamGeneral'] = ExamGeneral::select('flddetail', 'fldtime')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldinput', 'Notes')
                            ->where('flditem', 'Condition of Discharge')
                            ->get();

                        /*select fldid,fldroute,flditem,flddose,fldfreq,flddays,flditemtype from tblpatdosing where fldencounterval='1' and (fldstatus='Discharged' or fldstatus='LAMA' or fldstatus='Death' or fldstatus='Refer' or fldstatus='Absconder')*/
                        $information[$k]['DischargedLAMADeathReferAbsconderPatDosing'] = Pathdosing::select('fldid', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', 'flditemtype')
                            ->where('fldencounterval', $encounter_id)
                            ->where(function ($query) {
                                return $query
                                    ->orWhere('fldstatus', '=', 'Discharged')
                                    ->orWhere('fldstatus', '=', 'LAMA')
                                    ->orWhere('fldstatus', '=', 'Death')
                                    ->orWhere('fldstatus', '=', 'Absconder')
                                    ->orWhere('fldstatus', '=', 'Refer');
                            })
                            ->get();

                        /*select flddetail,fldtime from tblexamgeneral where fldencounterval='1' and fldinput='Notes' and flditem='Advice on Discharge'*/
                        $information[$k]['AdviceOfDischargeExamGeneral'] = ExamGeneral::select('flddetail', 'fldtime')
                            ->where('fldencounterval', $encounter_id)
                            ->where('fldinput', 'Notes')
                            ->where('flditem', 'Advice on Discharge')
                            ->get();

                        $information[$k]['present_symptoms'] = ExamGeneral::select('fldtime', 'flditem', 'fldreportquali', 'fldreportquanti', 'flddetail')
                            ->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Presenting Symptoms', 'fldsave' => '1'])
                            ->get();


                        // $exams = DB::table('tblexam')
                        //     ->join('tblpatientexam', 'tblpatientexam.fldhead', '=', 'tblexam.fldexamid')
                        //     ->select('tblpatientexam.*', 'tblexam.*')
                        //     ->where('tblpatientexam.fldencounterval', $encounter_id)
                        //     ->get();

                        $information[$k]['systolic_bp'] = $systolic_bp = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Systolic BP')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();

                        $information[$k]['diasioli_bp'] = $diasioli_bp = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Diastolic BP')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();

                        $information[$k]['pulse'] = $pulse = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Pulse Rate')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();

                        $information[$k]['temperature'] = $temperature = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Pulse RatePulse Rate')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();

                        $information[$k]['respiratory_rate'] = $respiratory_rate = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'Respiratory Rate')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();


                        $information[$k]['o2_saturation'] = $o2_saturation = DB::table('tblpatientexam')
                            ->join('tblexamlimit', 'tblpatientexam.fldhead', '=', 'tblexamlimit.fldexamid')
                            ->select('tblpatientexam.*', 'tblexamlimit.fldhigh', 'tblexamlimit.fldlow', 'tblexamlimit.fldunit')
                            ->where('tblpatientexam.fldencounterval', $encounter_id)->where('tblpatientexam.fldsave', 1)->where('tblpatientexam.fldhead', 'O2 Saturation')
                            ->where('tblpatientexam.hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession())
                            ->orderBy('tblpatientexam.fldid', 'desc')->first();

                        //$information[$k]['drug'] = Drug::select('fldroute','fldstrunit','flddrug','fldcodename')->groupBy('fldroute','fldstrunit','flddrug','fldcodename')->get()->toArray();
                    }
                }
            }
            $data['patientinfo'] = $patient_info;
            $data['encounters'] = $information;
        }
        return view('dental::pdf.history-dental', $data)/*->setPaper('a4')->stream('history.pdf')*/;
    }
}
