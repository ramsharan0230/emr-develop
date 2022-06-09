<?php

namespace Modules\Delivery\Http\Controllers;

use App\BillingSet;
use App\CogentUsers;
use App\Confinement;
use App\Consult;
use App\Department;
use App\Departmentbed;
use App\Encounter;

use App\PatientExam;
use App\PatientInfo;
use App\PatFindings;
use App\ExamGeneral;

use App\User;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Utils\Permission;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Session;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */


    public function index(Request $request)
    {
        // echo "here"; exit;
        if (Permission::checkPermissionFrontendAdmin('delivery-form')) {
            try {
                $data = array();
                $data['diagnosisgroup'] = \App\DiagnoGroup::select('fldgroupname')->distinct()->get();
                $data['diagnosiscategory'] = Helpers::getInitialDiagnosisCategory();

                $data['departments'] = DB::table('tbldepartment')
                    ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
                    ->where('tbldepartment.fldcateg', 'Patient Ward')
                    ->select('tbldepartment.flddept')
                    ->groupBy('tbldepartment.flddept')
                    ->get();

                $encounter_id_session = Session::get('delivery_encounter_id');
                $data['patient_status_disabled'] = 0;
                $data['disableClass'] = 0;
                if ($request->has('encounter_id') || $encounter_id_session) {

                    if ($request->has('encounter_id'))
                        $encounter_id = $request->get('encounter_id');
                    else
                        $encounter_id = $encounter_id_session;


                    $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();

                    /*department change enabled*/
                    /*if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
                        if (!in_array($data['enpatient']->fldcurrlocat, $current_user->department->pluck('flddept')->toArray())) {
                            Session::flash('display_popup_error_success', true);
                            Session::flash('error_message', 'You are not authorized to view this patients information.');
                            return redirect()->route('admin.dashboard');
                        }
                    }*/

                    $patient_id = $enpatient->fldpatientval;
                    $data['patient_id'] = $patient_id;

                    $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
                    $data['disableClass'] = 1;
                    $data['enable_freetext'] = Options::get('free_text');
                    // echo $data['enable_freetext']; exit;
                    $data['patient_status_disabled'] = $enpatient->fldadmission == "Discharged" ? 1 : 0;

                    if ($patient->fldptsex != 'Female') {
                        $data['message'] = "Please enter a Female encounterid";
                        Session::flash('error_message', 'Please enter female encounter.');
                        Session::forget('delivery_encounter_id');
                        return redirect()->route('delivery');
                    }


                    session(['delivery_encounter_id' => $encounter_id]);

                    /*create last encounter id*/
                    Helpers::deliveryEncounterQueue($encounter_id);
                    /*$encounterIds = Options::get('delivery_last_encounter_id');

                    $arrayEncounter = unserialize($encounterIds);*/
                    /*create last encounter id*/

                    $end = Carbon::parse( $patient->fldptbirday ? $patient->fldptbirday : '') ?? null;
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

                    $data['deliveries'] = \App\Confinement::select('fldid', 'flddeltype', 'flddelresult', 'flddelwt', 'flddelphysician', 'flddeltime', 'fldbabypatno', 'fldcomment')
                        ->where('fldencounterval', $encounter_id)
                        ->with('child:fldptsex,fldpatientval')
                        ->get();
                    $data['patdiago'] = $patdiago = PatFindings::where('fldencounterval', $encounter_id)->where('fldtype', 'Provisional Diagnosis')->where('fldsave', 1)->get();
                    $data['enbed'] = Departmentbed::where('fldencounterval', $encounter_id)->orderBy('fldbed', 'DESC')->first();

                    $data['qddata'] = ExamGeneral::where([
                            'fldencounterval' => $encounter_id,
                            'fldinput' => 'Obstetrics',
                            'fldtype' => 'Quantitative',
                        ])->count() == 0;
                }

                $data['delivered_types'] = Helpers::getDeliveredTypeList();
                $data['delivered_babies'] = Helpers::getDeliveredBabyList();
                $data['complications'] = Helpers::getComplicationList();
                $data['genders'] = Helpers::getGenders();
                $data['billingset'] = $billingset = BillingSet::get();
                $data['consultants'] = CogentUsers::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();
                $data['nurses'] = CogentUsers::select('username', 'firstname', 'middlename', 'lastname')->where('fldnursing', 1)->get();

                return view('delivery::index', $data);
            } catch (\GearmanException $e) {
            }
        } else {
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'You are not authorized for this action.');
            return redirect()->route('admin.dashboard');
        }
    }

    public function _get_medicine_data($request)
    {
        $medicines = \App\Pathdosing::select('fldid', 'fldstarttime', 'fldroute', 'flditem', 'flddose', 'fldfreq', 'flddays', \DB::raw('(fldqtydisp-fldqtyret) as qty'), 'fldlabel', 'fldcurval')
            ->where([
                'fldencounterval' => $request->get('fldencounterval'),
                'fldsave_order' => 1,
                'flditemtype' => 'Medicines'
            ]);

        if ($request->get('date') == 'today') {
            $medicines->where([
                'fldcomp_order' => Helpers::getCompName(),
                'flddispmode' => 'Delivery',
            ]);
        }

        return $medicines->get();
    }

    public function getAllMedicine(Request $request)
    {
        return response()->json($this->_get_medicine_data($request));
    }

    public function pharmacyReport(Request $request)
    {
        $patientinfo = \App\Utils\Helpers::getPatientByEncounterId($request->get('fldencounterval'));
        $medicines = $this->_get_medicine_data($request);

        return \Barryvdh\DomPDF\Facade::loadView('delivery::layouts.examReport', compact('patientinfo', 'medicines'))
            ->stream('exam_report.pdf');
    }


    public function resetEncounter()
    {
        Session::forget('delivery_encounter_id');
        return redirect()->route('delivery');
    }

    public function deliveryReport($encounterId)
    {

        $information = array();
        $information['encounterId'] = $encounter_id = $encounterId;

        $information['certificate'] = 'Delivery';

        $information['encounterData'] = Encounter::select('fldpatientval', 'flduserid', 'fldregdate', 'fldrank')
            ->where('fldencounterval', $encounterId)
            ->with('patientInfo')
            ->first();

        $pre_delivery = PatientExam::select('fldid', 'fldhead', 'fldtime')->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Pre Delivery Exam', 'fldsave' => 1])->get();
        $on_delivery = PatientExam::select('fldid', 'fldhead', 'fldtime')->where(['fldencounterval' => $encounter_id, 'fldinput' => 'On Delivery Exam', 'fldsave' => 1])->get();
        $post_delivery = PatientExam::select('fldid', 'fldhead', 'fldtime')->where(['fldencounterval' => $encounter_id, 'fldinput' => 'Post Delivery Exam', 'fldsave' => 1])->get();
        $delivery_result = Confinement::select('fldid', 'flddelresult', 'flddeltype', 'fldbabypatno', 'flddeltime', 'flddelwt', 'fldcomment')->where('fldencounterval', $encounter_id)->get();

        $information['pre_delivery'] = $pre_delivery;
        $information['on_delivery'] = $on_delivery;
        $information['post_delivery'] = $post_delivery;
        $information['delivery_result'] = $delivery_result;


        return view('inpatient::pdf.deliveryreport', $information)/*->setPaper('a4')->stream('delivery.pdf')*/ ;
    }
}
