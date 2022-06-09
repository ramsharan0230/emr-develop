<?php

namespace Modules\ExtraReceipt\Http\Controllers;

use App\Banks;
use App\BillingSet;
use App\CogentUsers;
use App\Encounter;
use App\Entry;
use App\PatBilling;
use App\PatientExam;
use App\PatientInfo;
use App\ServiceCost;
use App\User;
use App\Utils\Helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Session;
use Auth;

class ExtraReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $encounter_id_session = Session::get('billing_encounter_id');
        $data['patient_status_disabled'] = 0;
        $data['html'] = '';
        $data['total'] = $data['discount'] = 0;
        if ($request->has('encounter_id') || $encounter_id_session) {
            if ($request->has('encounter_id'))
                $encounter_id = $request->get('encounter_id');
            else
                $encounter_id = $encounter_id_session;

            session(['billing_encounter_id' => $encounter_id]);

            /*create last encounter id*/
            Helpers::moduleEncounterQueue('billing_encounter_id', $encounter_id);
            /*$encounterIds = Options::get('billing_last_encounter_id');

            $arrayEncounter = unserialize($encounterIds);*/
            /*create last encounter id*/

            $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->first();
            if (!$enpatient) {
                Session::forget('billing_encounter_id');
                return redirect()->back()->with('error', 'Encounter not found.');
            }
            $data['patient_status_disabled'] = $enpatient->fldadmission == "Discharged" ? 1 : 0;

            $patient_id = $enpatient->fldpatientval;
            $data['patient'] = $patient = PatientInfo::where('fldpatientval', $patient_id)->first();
            $data['patient_id'] = $patient_id;
            $data['consultants'] = User::where('fldopconsult', 1)->orwhere('fldipconsult', 1)->get();
            $data['refer_by'] = CogentUsers::where('fldreferral', 1)->where('status', 'active')->get();

            if ($patient) {
                $end = Carbon::parse($patient->fldptbirday ? $patient->fldptbirday :'') ?? null;
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
            }

            $data['body_weight'] = $body_weight = PatientExam::where('fldencounterval', $encounter_id)->where('fldsave', 1)->where('fldsysconst', 'Body_Weight')->orderBy('fldid', 'desc')->first();
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

                    $data['bmi'] =round($body_weight->fldrepquali / $divide_bmi, 2); // (weight in kg)/(height in m^2) with unit kg/m^2.
                }
            }
            $data['billingset'] = $billingset = BillingSet::get();
        }
        $data['banks'] = Banks::all();
        return view('extrareceipt::index', $data);
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws \Throwable
     */
    public function getItemsByServiceOrInventory(Request $request)
    {
        $item_type = $request->item_type;
        $billingMode = $request->billingMode;
        $html = "";
        if ($item_type == "pharmacy") {
            $data['services'] = Entry::select('fldstockno', 'fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcategory')->where('fldstatus', 1)->orderBy('fldstockid', 'ASC')->get();
            $html = view('extrareceipt::dynamic-views.pharmacy-data', $data)->render();
        } elseif ($item_type == "service") {
            $data['services'] = ServiceCost::select('flditemname', 'fldreport', 'flditemtype', 'flditemcost', 'fldcode', 'fldid')->where('fldstatus', 'Active')->where('fldgroup', $billingMode)->orderBy('flditemname', 'ASC')->get();
            $html = view('extrareceipt::dynamic-views.service-data', $data)->render();
        }
        return $html;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveServiceCosting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_type_payment' => 'required',
            'encounter_id_payment' => 'required',
            'serviceItem' => 'required',
            'serviceItem.*' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => FALSE,
                'message' => "Validation error"
            ]);
        }
        $serviceData = [
            'fldencounterval' => $request->encounter_id_payment,
            'fldbillingmode' => $request->billing_type_payment,
            'flditemrate' => 0,
            'flditemqty' => 1,
            'fldtaxper' => 0,
            'flddiscper' => 0,
            'fldtaxamt' => 0,
            'flddiscamt' => 0,
            'fldorduserid' => Auth::guard('admin_frontend')->user()->flduserid,
            'fldordtime' => date("Y-m-d H:i:s"),
            'fldordcomp' => NULL,
            'flduserid' => NULL,
            'fldtime' => NULL,
            'fldcomp' => NULL,
            'fldsave' => '0',
            'fldbillno' => NULL,
            'fldparent' => 0,
            'fldprint' => '0',
            'fldstatus' => 'Punched',
            'fldalert' => '1',
            'fldtarget' => NULL,
            'fldpayto' => NULL,
            'fldrefer' => NULL,
            'fldreason' => NULL,
            'fldretbill' => NULL,
            'fldretqty' => 0,
            'fldsample' => 'Waiting',
            'xyz' => '0'
        ];
        $itemtotal = 0;
        $returnData = [];
        foreach ($request->serviceItem as $service) {
            $itemDetails = ServiceCost::where('fldid', $service)->first();
            $serviceData['flditemtype'] = $itemDetails->flditemtype;
            $serviceData['flditemno'] = $itemDetails->fldid;
            $serviceData['flditemname'] = $itemDetails->flditemname;
            $serviceData['flditemrate'] = $itemDetails->flditemcost;
            $serviceData['fldditemamt'] = $itemDetails->flditemcost;
            $returnData['total'] = $itemtotal + $serviceData['fldditemamt'];

            PatBilling::insert($serviceData);
        }

        $returnData['tableData'] = $this->itemHtml($request->encounter_id_payment);
        $returnData['total'] = PatBilling::where('fldencounterval', $request->encounter_id_payment)->where('fldstatus', 'Punched')->sum('fldditemamt');
        $returnData['discount'] = PatBilling::where('fldencounterval', $request->encounter_id_payment)->where('fldstatus', 'Punched')->sum('flddiscamt');

        return response()->json([
            'status' => TRUE,
            'message' => $returnData
        ]);
    }

    /**
     * @param $encounter
     * @return array|string
     * @throws \Throwable
     */
    public function itemHtml($encounter)
    {
        $data['serviceData'] = PatBilling::where('fldencounterval', $encounter)->where('fldstatus', 'Punched')->get();
        $data['total'] = PatBilling::where('fldencounterval', $encounter)->where('fldstatus', 'Punched')->sum('fldditemamt');
        $html = view('extrareceipt::dynamic-views.service-item-list', $data)->render();
        return $html;
    }

}
