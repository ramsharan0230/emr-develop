<?php

namespace Modules\Billing\Http\Controllers;

use App\Banks;
use App\BillingSet;
use App\Encounter;
use App\Entry;
use App\Fiscalyear;
use App\Http\Controllers\Controller;
use App\OtGroupSubCategory;
use App\PatBillCount;
use App\PatBillDetail;
use App\PatBilling;
use App\PatBillingShare;
use App\PatientInfo;
use App\ServiceCost;
use App\ServiceGroup;
use App\Services\DepartmentRevenueService;
use App\Services\PatBillingShareService;
use App\TaxGroup;
use App\Utils\Helpers;
use App\UserShare;
use App\Utils\Options;
use App\Year;
use Auth;
use Carbon\Carbon;
use CogentHealth\Ssf\Claim\Claim;
use CogentHealth\Ssf\Claim\ClaimItem;
use CogentHealth\Ssf\Ssf;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Log;
use Session;
use Throwable;
use App\Services\MaternalisedService;
Use App\PatInsuranceDetails;
use App\Services\UserService;
use App\Services\TpBillService;

/**
 * Class BillingController
 * @package Modules\Billing\Http\Controllers
 */
class BillingController extends Controller
{
    protected $discountMode;
    private $ssf_patient_id;

    public function __construct(DiscountModeController $discountMode)
    {
        $this->discountMode = $discountMode;
    }

    /**
     * Display a listing of the resource.
     * @return Application|Factory|RedirectResponse|View
     */
    public function index(Request $request)
    {
        $encounter_id_session = Session::get('billing_encounter_id');
        $data['patient_status_disabled'] = 0;
        $data['html'] = '';
        $data['tphtml'] = '';
        $data['total'] = $data['discount'] = $data['serviceData'] = $data['tax'] = $data['subtotal'] = 0;
        $data['discountMode'] = '';
        $data['discounts'] = Helpers::getDiscounts();
        $data['addGroup'] = $addGroupList = ServiceGroup::groupBy('fldgroup')->get();
        $data['totalAmountReceivedByEncounter'] = '';
        if ($request->has('patient_details') && $request->get('patient_details') != "") {
            $enpatient = Encounter::select('fldpatientval', 'fldencounterval','fldregdate','fldclaimcode')->where('fldpatientval', $request->get('patient_details'))->with('patientInfo')->orderBy('fldregdate', 'DESC')->first();

            if(\App\Utils\Helpers::checkRedirectLastEncounter() == "Yes"){

                if($enpatient){
                    $regdate = $enpatient->fldregdate;
                    $registerdate = strtotime($regdate);
                    $withfollowup = strtotime("+". Options::get('followup_days')." day", $registerdate);
                    $followup_check = Options::get('followup_check');
                    if(is_array($followup_check) and in_array('Cashier',$followup_check)){
                        if(date('Y-m-d') > date('Y-m-d', $withfollowup) && ($enpatient->fldadmission !='Discharged'  && $enpatient->fldadmission !='Recorded')  && substr($enpatient->fldencounterval, 0, 2) != 'IP'){
                            Session::forget('billing_encounter_id');
                            return redirect()->route('billing.display.form')->with('error_message', "Followup Date exceed please generate new encounter");

                        }
                    }


                }
            }
            if ($enpatient) {
                $regdate = $enpatient->fldregdate;
                $registerdate = strtotime($regdate);
                $withfollowup = strtotime("+". Options::get('followup_days')." day", $registerdate);
                $followup_check = Options::get('followup_check');
                if(is_array($followup_check) and in_array('Cashier',$followup_check)){
                    if(date('Y-m-d') > date('Y-m-d', $withfollowup) && ($enpatient->fldadmission !='Discharged'  && $enpatient->fldadmission !='Recorded')  && substr($enpatient->fldencounterval, 0, 2) != 'IP'){
                        Session::forget('billing_encounter_id');
                        return redirect()->route('billing.display.form')->with('error_message', "Followup Date exceed please generate new encounter");

                    }
                }

                session(['billing_encounter_id' => $enpatient->fldencounterval]);
                $encounter_id_session = $enpatient->fldencounterval;
            }else{
                return redirect()->route('billing.display.form')->with('error_message', 'Patient not found.');
            }
        }

        if ($request->has('encounter_id') || $encounter_id_session) {
            if ($request->get('encounter_id') != "") {
                $encounter_id = $request->get('encounter_id');
            } else {
                $encounter_id = $encounter_id_session;
            }
            $eencounterDetail = Encounter::where('fldencounterval', $encounter_id)->first();
            if (!Encounter::where('fldencounterval', $encounter_id)->first()) {
                return redirect()->route('billing.display.form')->with('error_message', 'Patient not found.');
            }
            $enpatient = Encounter::select('fldpatientval', 'fldencounterval','fldregdate')->where('fldencounterval', $encounter_id)->with('patientInfo')->orderBy('fldregdate', 'DESC')->first();
            $regdate = $eencounterDetail->fldregdate;
            $registerdate = strtotime($regdate);
            $withfollowup = strtotime("+". Options::get('followup_days')." day", $registerdate);
            $followup_check = Options::get('followup_check');

            if(is_array($followup_check) and in_array('Cashier',$followup_check)){
                if(date('Y-m-d') > date('Y-m-d', $withfollowup) && ($enpatient->fldadmission !='Discharged'  && $enpatient->fldadmission !='Recorded')  && substr($enpatient->fldencounterval, 0, 2) != 'IP'){
                    Session::forget('billing_encounter_id');
                    return redirect()->route('billing.display.form')->with('error_message', "Followup Date exceed please generate new encounter");

                }
            }


            if (Helpers::checkIfDischarged($encounter_id)) {
                Session::forget('billing_encounter_id');
                return redirect()->route('billing.display.form')->with('error_message', 'Patient already discharged.');
            }

            $encounterDetailObj = new EncounterDetailsController();
            $encounterData = $encounterDetailObj->getEncounterData($encounter_id, $data['addGroup']);
            $data['patbillingdata']  = $encounterData['dataList']['serviceData'];
            // dd($data['patbillingdata']);
            $data = $data + $encounterData['dataArray'];
            $dataList = $encounterData['dataList'];

            $data['serviceDataPackage'] = $encounterData['dataList'] && is_countable($encounterData['dataList']['serviceData']) && count($encounterData['dataList']['serviceData']) ? $encounterData['dataList']['serviceData'][0]->package_name : [];

            $data['total'] = $encounterData['dataArray']['total'];
            $data['discount'] = $encounterData['dataArray']['discount'];
            $data['tax'] = $encounterData['dataArray']['tax'];
            $data['subtotal'] = $encounterData['dataArray']['subtotal'];

            $data['html'] = view('billing::dynamic-views.service-item-list', $dataList)->render();
            $data['tphtml'] = view('billing::dynamic-views.service-tp-item-list', $dataList)->render();
        }

        $data['banks'] = Banks::all();
        $data['countPatbillData'] = isset($dataList['serviceData']) ? count(is_countable($dataList['serviceData']) ? $dataList['serviceData'] : []) : 0;
        // dd($data['countPatbillData']);
        $data['billingset'] = $billingset = BillingSet::get();

        $data['billingModes'] = Helpers::getBillingModes();
        $data['genders'] = Helpers::getGenders();
        $data['surnames'] = Helpers::getSurnames();
        $data['countries'] = Helpers::getCountries();

        return view('billing::billing', $data);
    }

    /**
     * @param Request $request
     * @return array|string
     * @throws Throwable
     */
    public function getItemsByServiceOrInventory(Request $request)
    {
        $item_type = $request->item_type;
        $billingMode = $request->billingMode;
        $html = "";
        if ($item_type == "pharmacy") {
            $data['services'] = Entry::select('fldstockno', 'fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcategory')
                ->where('fldstatus', 1)
                ->orderBy('fldstockid', 'ASC')
                ->get();
            $html = view('billing::dynamic-views.pharmacy-data', $data)->render();
        } elseif ($item_type == "service") {
            $data['services'] = ServiceCost::select('flditemname', 'fldreport', 'flditemtype', 'flditemcost', 'fldcode', 'fldid', 'category')
                ->where('fldstatus', 'Active')
                ->where(function ($query) use ($billingMode) {
                    $query->orWhere('fldgroup', $billingMode)
                        ->orWhere('fldgroup', '%');
                })
                ->orderBy('flditemname', 'ASC')->get();
            $html = view('billing::dynamic-views.service-data', $data)->render();
        }
        return $html;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function deleteServiceCosting(Request $request)
    {
        try{
            $patbilling = PatBilling::where('fldid', $request->fldid)->where('fldsample','!=','Sampled')->first();
            // dd($patbilling);
            $computer = Helpers::getCompName();
            $encounter = $patbilling->fldencounterval;
            $data['addGroup'] = $addGroupList = ServiceGroup::groupBy('fldgroup')->get();
            if(isset($patbilling) and ($patbilling->package_name !='' or !is_null($patbilling->package_name))){
                #Update in tbltpbills with tempbillnumber
                if($patbilling->fldtempbillno !='' or !is_null($patbilling->fldtempbillno)){
                    TpBillService::updateDeletedTpBillItems($request->fldid);
                }
                #End Update in tbltpbills with tempbillnumber
                $groupdata = \App\ServiceGroup::where('fldgroup',$patbilling->package_name)->get();
                $edittablegroupdata = \App\ServiceGroup::where('fldgroup',$patbilling->package_name)->where('price_editable','1')->get();
                if(count($groupdata) == count($edittablegroupdata)){
                    // echo "eta"; exit;
                    PatBilling::where('fldencounterval',$encounter)->where('fldsample','!=','Sampled')->where('package_name',$patbilling->package_name)->delete();
                }else{
                    // echo "here"; exit;
                    $patbilling->delete();
                }
                $returnData['package_name'] = '1';
            }else{
                #Update in tbltpbills with tempbillnumber
                if($patbilling->fldtempbillno !='' or !is_null($patbilling->fldtempbillno)){
                    TpBillService::updateDeletedTpBillItems($request->fldid);
                }
                #End Update in tbltpbills with tempbillnumber
                $returnData['package_name'] = '0';
                $patbilling->delete();
            }

            $encounterDetailObj = new EncounterDetailsController();
            $encounterData = $encounterDetailObj->getEncounterData($encounter, $data['addGroup']);
            $dataList = $encounterData['dataList'];
            $returnData['tableData'] = view('billing::dynamic-views.service-item-list', $dataList)->render();
            $returnData['tptableData'] = view('billing::dynamic-views.service-tp-item-list', $dataList)->render();
            /**check if temporary or credit item must be displayed*/
            $subtotal = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where(function ($query) use ($request) {
                    if ($request->temp_checked === 'no') {
                        $query->where('fldstatus', 'Punched');
                    } else {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    }
                })
                ->where('fldtempbillno', '=', NULL)
                ->where(function ($query) {
                    $query->where('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $returnData['subtotal'] = number_format($subtotal, 2, '.', '');
            $total = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) use ($request) {
                    if ($request->temp_checked === 'no') {
                        $query->where('fldstatus', 'Punched');
                    } else {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    }
                })
                ->where('fldtempbillno', '=', NULL)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $returnData['total'] = number_format($total, 2, '.', '');

            $discount = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) use ($request) {
                    if ($request->temp_checked === 'no') {
                        $query->where('fldstatus', 'Punched');
                    } else {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    }
                })
                ->where('fldtempbillno', '=', NULL)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $returnData['discount'] = Helpers::numberFormat($discount,'insert');

            $tax = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) use ($request) {
                    if ($request->temp_checked === 'no') {
                        $query->where('fldstatus', 'Punched');
                    } else {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    }
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldtempbillno', '=', NULL)
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
            $returnData['tax'] = Helpers::numberFormat($tax,'insert');
            $returnData['depositAmount'] = Helpers::totalDepositAmountReceived($encounter);
            $returnData['tpAmount'] = Helpers::getTpAmount($encounter);
            $returnData['remainingAmount'] = $returnData['depositAmount']-$returnData['tpAmount'];
            return response()->json([
                'status' => TRUE,
                'message' => $returnData
            ]);
        }catch(\Exception $e){
            dd($e);
        }
        
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function finalPayment(Request $request)
    {

        try {
            $claim_code = null; //claim code from ssf.
            DB::beginTransaction();
            if (!is_null($request->__encounter_id) || $request->__encounter_id != '') {
                if ($request->has('share_check') && !PatBillingShare::whereIn('pat_billing_id', $request->share_check)->first()){
                    return redirect()->back()->with('error_message', 'Doctor share not given');
                }

                $dateToday = Carbon::now();
                $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')
                    ->first();

                // if billing mode is ssf
                // get ssf item price
                $encounter = Encounter::where('fldencounterval', $request->__encounter_id)->first();
                $billing_mode = strtolower($encounter->fldbillingmode);

                if ($billing_mode === "ssf") {

                    // get patient ssf number.
                    $ssf_number = $encounter->patientInfo->ssf_number;

                    // get patient uuid
                    $ssf_patient_info = Ssf::getPatientDetailById($ssf_number);
                    $result = json_decode($ssf_patient_info->getContent(), true);
                    if (!$result['success']) {
                        // if curl error
                        goto endssf;
                    }
                    $this->ssf_patient_id = $result['data']['entry'][0]['resource']['id'];

                    // get ssf item code.
                    $ssf_items = [];
                }

                if ($billing_mode === "health insurance" || $billing_mode === "healthinsurance" || $billing_mode === "hi") {
                    $claim_code = $encounter->fldclaimcode;
                    // $allowedamount = PatInsuranceDetails::where('fldencounterval',$encounter->fldencounterval)->first();

                    // if($allowedamount->fldallowedamt > $request->received_amt){
                    //     dd('ok');
                    // }

                }

                endssf:

                $patbillingids = [];
                if ($request->payment_mode == 'Credit') {
                        $patbilling = PatBilling::where(['fldencounterval' => $request->__encounter_id])
                        ->where(function ($query) {
                            $query->orWhere('flditemtype', '!=', 'Surgicals')
                                ->orWhere('flditemtype', '!=', 'Medicines')
                                ->orWhere('flditemtype', '!=', 'Extra Items');
                        })
                        ->where(function ($query) {
                            $query->orWhere('fldstatus', 'Punched')->orWhere('fldstatus', 'Waiting');
                        })
                        ->where('fldsave', '=', 0)
                        ->where('fldditemamt', '>=', 0)
                        ->get();

                    if (is_countable($patbilling) && count($patbilling) == 0) {
                        return redirect()->back()->with('error_message', 'No items for this patient.');
                    }
                    // get claim code
                    // $claim_code = $this->getClaimCode($patbilling);
                    $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
                    $billNumberGeneratedString = "CRE-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');

                    $total = PatBilling::where('fldencounterval', $request->__encounter_id)
                        ->where(function ($query) {
                            $query->orWhere('flditemtype', '!=', 'Surgicals')
                                ->orWhere('flditemtype', '!=', 'Medicines')
                                ->orWhere('flditemtype', '!=', 'Extra Items');
                        })
                        ->where('fldditemamt', '>=', 0)
                        ->where('fldstatus', 'Punched')->sum('fldditemamt');
                    $discount = PatBilling::where('fldencounterval', $request->__encounter_id)
                        ->where(function ($query) {
                            $query->orWhere('flditemtype', '!=', 'Surgicals')
                                ->orWhere('flditemtype', '!=', 'Medicines')
                                ->orWhere('flditemtype', '!=', 'Extra Items');
                        })
                        ->where('fldditemamt', '>=', 0)
                        ->where('fldstatus', 'Punched')->sum('flddiscamt');
                    $tax = PatBilling::where('fldencounterval', $request->__encounter_id)
                        ->where(function ($query) {
                            $query->orWhere('flditemtype', '!=', 'Surgicals')
                                ->orWhere('flditemtype', '!=', 'Medicines')
                                ->orWhere('flditemtype', '!=', 'Extra Items');
                        })
                        ->where('fldditemamt', '>=', 0)
                        ->where('fldstatus', 'Punched')->sum('fldtaxamt');


                    $depositdata = \App\PatBillDetail::where('fldencounterval', $request->__encounter_id)->orderBy('fldid', 'DESC')->where('fldbilltype','Credit')->whereNotNull('fldcurdeposit')->where('fldcomp',Helpers::getCompName())->get();
                    $currentdeposit = (isset($depositdata) and $depositdata->isNotEmpty()) ? $depositdata[0]->fldcurdeposit : 0;

                    // echo $billNumberGeneratedString; exit;
                    if ($patbilling) {
                        foreach ($patbilling as $bill) {
                            $patbillingids[] = $bill->fldid;
                            $updateDataPatBilling = [
                                'fldbillno' => $billNumberGeneratedString,
                                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                                'fldtime' => date("Y-m-d H:i:s"),
                                'fldsave' => 1,
                                'fldstatus' => 'Cleared',
                                'xyz' => 0,
                                // 'fldtempbillno' => $billNumberGeneratedString,
                                'fldtempbilltransfer' => 0,
                                'fldcomp' => Helpers::getCompName(),
                                'claim_code' => $claim_code,
                                'fldbillingmode' => $request->get('billing_mode'),
                                'discount_mode' => $request->get('discount_mode'),
                            ];
                            $bill->update($updateDataPatBilling);

                            $tempconsultdata = \App\TempConsult::where('pat_billing_id',$bill->fldid)->first();
                            if(isset($tempconsultdata) and !is_null($tempconsultdata)){
                                $user = \App\CogentUsers::where('id',$tempconsultdata->flddoctor)->first();
                                // dd()
                                if(isset($tempconsultdata) and !empty($tempconsultdata)){
                                    $consultdata['fldencounterval'] = $tempconsultdata->fldencounterval;
                                    $consultdata['fldconsultname'] = $tempconsultdata->flddept;
                                    $consultdata['fldconsulttime'] = now();
                                    $consultdata['fldstatus'] = 'Planned';
                                    $consultdata['flduserid'] = $user->username;
                                    $consultdata['fldbillingmode'] = $request->get('billing_mode');
                                    $consultdata['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                                    $consultdata['fldtime'] = now();
                                    $consultdata['fldcomp'] = Helpers::getCompName();
                                    $consultdata['fldsave'] = '1';
                                    $consultdata['hospital_department_id'] = '1';

                                    \App\Consult::create($consultdata);
                                    $tempconsultdata->delete();
                                }
                            }


                        }


                        /*insert pat bill details*/

                        $insertDataPatDetail = [
                            'fldencounterval' => $request->__encounter_id,
                            'fldchargedamt' => Helpers::numberFormat($total,'insert'),
                            'flditemamt' => Helpers::numberFormat($total + $discount - $tax,'insert'), //actual price without discount and tax
                            'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
                            'flddiscountamt' => Helpers::numberFormat($discount,'insert'),
                            'fldreceivedamt' => Helpers::numberFormat($request->received_amount,'insert'),
                            'fldbilltype' => $request->payment_mode,
                            'fldcurdeposit' => Helpers::numberFormat($request->received_amount - $total + $currentdeposit,'insert'),
                            // 'fldcurdeposit' => $currentdeposit - (($total- $request->received_amount)*(-1)),
                            'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                            'fldtime' => date("Y-m-d H:i:s"),
                            'fldbillno' => $billNumberGeneratedString,
                            'fldsave' => 1,
                            'xyz' => 0,
                            'fldbill' => 'CREDIT INVOICE',
                            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                            // 'fldtempbillno' => $billNumberGeneratedString,
                            'fldtempbilltransfer' => 0,
                            'claim_code' => $claim_code,
                            'fldprevdeposit' => Helpers::numberFormat($currentdeposit,'insert'),
                            'tblexpecteddate' => $request->get('expected_payment_date') . " {date('H:i:s)}",
                            'fldcomp' => Helpers::getCompName(),
                            'flddiscountgroup' => $request->get('discount_mode'),
                            'remarks' => $request->get('remarks') ?? '',
                            'payment_mode' => "Credit",
                            // 'fldcurdeposit' => $currentdeposit-$request->received_amount,
                        ];

                        if (strtolower($request->payment_mode) == 'credit') {
                            $insertDataPatDetail['fldchargedamt'] = Helpers::numberFormat(abs($currentdeposit - $total),'insert');
                        }

                        PatbillDetail::create($insertDataPatDetail);

                        /*insert pat bill count*/
                        // PatBillCount::create(['fldtempbillno' => $billNumberGeneratedString, 'fldbillno' => $billNumberGeneratedString, 'fldcount' => 1]);
                    }

                    Session::flash('billing_credit', true);
                } else {
                    $total = 0;
                    $discount = 0;
                    $tax = 0;
                    $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
                    $billNumberGeneratedString = "CAS-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');
                    $patbilling = PatBilling::where(['fldencounterval' => $request->__encounter_id])
                        ->where(function ($query) {
                            $query->orWhere('fldstatus', 'Punched')->orWhere('fldstatus', 'Waiting');
                        })
                        ->where(function ($query) {
                            $query->orWhere('flditemtype', '!=', 'Surgicals')
                                ->orWhere('flditemtype', '!=', 'Medicines')
                                ->orWhere('flditemtype', '!=', 'Extra Items');
                        })
                        ->where('fldditemamt', '>=', 0)
                        ->where('fldsave', '=', 0)
                        ->where('fldtempbillno', null)
                        ->get();
                    if (is_countable($patbilling) && count($patbilling) == 0) {
                        return redirect()->back()->with('error_message', 'No items for this patient.');
                    }
            //        $claim_code = $this->getClaimCode($patbilling);



                    if ($patbilling) {
                        foreach ($patbilling as $bill) {
                            $patbillingids[] = $bill->fldid;
                            $updateDataPatBilling = [
                                'fldbillno' => $billNumberGeneratedString,
                                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                                'fldtime' => date("Y-m-d H:i:s"),
                                'fldsave' => 1,
                                'fldstatus' => 'Cleared',
                                'xyz' => 0,
                                'claim_code' => $claim_code,
                                'fldbillingmode' => $request->get('billing_mode'),
                                'discount_mode' => $request->get('discount_mode'),
                            ];
                            $bill->update($updateDataPatBilling);
                            // if()
                            $tempconsultdata = \App\TempConsult::where('pat_billing_id',$bill->fldid)->first();
                            if(isset($tempconsultdata) and !is_null($tempconsultdata)){
                                $user = \App\CogentUsers::where('id',$tempconsultdata->flddoctor)->first();
                                if(isset($tempconsultdata) and !empty($tempconsultdata)){
                                    $consultdata['fldencounterval'] = $tempconsultdata->fldencounterval;
                                    $consultdata['fldconsultname'] = $tempconsultdata->flddept;
                                    $consultdata['fldconsulttime'] = now();
                                    $consultdata['fldstatus'] = 'Planned';
                                    $consultdata['flduserid'] = $user->username;
                                    $consultdata['fldbillingmode'] = $request->get('billing_mode');
                                    $consultdata['fldorduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                                    $consultdata['fldtime'] = now();
                                    $consultdata['fldcomp'] = Helpers::getCompName();
                                    $consultdata['fldsave'] = '1';
                                    $consultdata['hospital_department_id'] = '1';

                                    \App\Consult::create($consultdata);
                                    $tempconsultdata->delete();
                                }
                            }

                        }

                        /**check if billing data has credit bill data*/
                    if ($request->is_credit_checked === 'no') {
                        // echo "checked"; exit;
//                        $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
//
//                        $billNumberGeneratedString = "CAS-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');
                        // echo $billNumberGeneratedString; exit;
                        $total = PatBilling::where('fldencounterval', $request->__encounter_id)
                            ->where('fldstatus', 'Cleared')
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })
                            ->where('fldditemamt', '>=', 0)
                            // ->where('fldtempbillno', '!=',null)
                            ->where('fldtempbillno', null)
                            ->where('fldbillno','LIKE',$billNumberGeneratedString)
                            ->sum('fldditemamt');
                        // echo $total; exit;
                        $discount = PatBilling::where('fldencounterval', $request->__encounter_id)
                            ->where('fldstatus', 'Cleared')
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })
                            ->where('fldditemamt', '>=', 0)
                            // ->where('fldtempbillno', '!=',null)
                            ->where('fldbillno','LIKE',$billNumberGeneratedString)
                            ->sum('flddiscamt');
                        $tax = PatBilling::where('fldencounterval', $request->__encounter_id)
                            ->where('fldstatus', 'Cleared')
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })
                            ->where('fldditemamt', '>=', 0)
                            // ->where('fldtempbillno', '!=',null)
                            ->where('fldtempbillno', null)
                            ->where('fldbillno','LIKE',$billNumberGeneratedString)
                            ->sum('fldtaxamt');

                            $totalammt = PatBilling::where('fldencounterval', $request->__encounter_id)
                            ->where(function ($query) {
                                $query->orWhere('fldstatus', 'Cleared')
                                    ->orWhere('fldstatus', 'Waiting');
                            })
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })
                            ->where('fldditemamt', '>=', 0)
                            ->where('fldtempbillno', null)
                            ->where('fldbillno','LIKE',$billNumberGeneratedString)
                            ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();

                            $taxableamount = PatBilling::where('fldencounterval', $request->__encounter_id)
                            ->where(function ($query) {
                                $query->orWhere('fldstatus', 'Cleared')
                                    ->orWhere('fldstatus', 'Waiting');
                            })
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })

                            ->where('fldtaxper', '>', 0)
                            ->where('fldtempbillno', null)
                            ->where('fldbillno','LIKE',$billNumberGeneratedString)
                            ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();

                    } else {
                        // echo "yes checked"; exit;
//                        $billNumber = Helpers::getNextAutoId('InvoiceNo', TRUE);
//                        $billNumberGeneratedString = "CAS-$year->fldname-$billNumber->fldvalue" . Options::get('hospital_code');
                        $total = PatBilling::where('fldencounterval', $request->__encounter_id)
                            ->where(function ($query) {
                                $query->orWhere('fldstatus', 'Cleared')
                                    ->orWhere('fldstatus', 'Waiting');
                            })
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })
                            ->where('fldditemamt', '>=', 0)
                            ->where('fldtempbillno', null)
                            ->where('fldbillno','LIKE',$billNumberGeneratedString)
                            ->sum('fldditemamt');
                       $totalammt = PatBilling::where('fldencounterval', $request->__encounter_id)
                            ->where(function ($query) {
                                $query->orWhere('fldstatus', 'Cleared')
                                    ->orWhere('fldstatus', 'Waiting');
                            })
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })
                            ->where('fldditemamt', '>=', 0)
                            ->where('fldtempbillno', null)
                            ->where('fldbillno','LIKE',$billNumberGeneratedString)
                            ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();

                        $discount = PatBilling::where('fldencounterval', $request->__encounter_id)
                            ->where(function ($query) {
                                $query->orWhere('fldstatus', 'Cleared')
                                    ->orWhere('fldstatus', 'Waiting');
                            })
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })
                            ->where('fldditemamt', '>=', 0)
                            ->where('fldtempbillno', null)
                            ->where('fldbillno','LIKE',$billNumberGeneratedString)
                            ->sum('flddiscamt');
                        $taxableamount = PatBilling::where('fldencounterval', $request->__encounter_id)
                            ->where(function ($query) {
                                $query->orWhere('fldstatus', 'Cleared')
                                    ->orWhere('fldstatus', 'Waiting');
                            })
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })

                            ->where('fldtaxper', '>', 0)
                            ->where('fldtempbillno', null)
                            ->where('fldbillno','LIKE',$billNumberGeneratedString)
                            ->select(DB::raw('sum(flditemrate*flditemqty) as total'))->get();
                        $tax = PatBilling::where('fldencounterval', $request->__encounter_id)
                            ->where(function ($query) {
                                $query->orWhere('fldstatus', 'Cleared')
                                    ->orWhere('fldstatus', 'Waiting');
                            })
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })
                            ->where('fldditemamt', '>=', 0)
                            ->where('fldtempbillno', null)
                            ->where('fldbillno','LIKE',$billNumberGeneratedString)
                            ->sum('fldtaxamt');
                    }
                        /*insert pat bill details*/
                        //                        $finalTotal = $total - $discount + $tax;
                        /* if ($request->payment_mode === "Cash") {
                             $request->received_amount = Helpers::numberFormat($total,'insert');
                         }*/
                         $subtotal = PatBilling::where('fldencounterval', $request->__encounter_id)
                                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                                ->where(function ($query) {
                                    $query->orWhere('fldstatus', 'Cleared')
                                        ->orWhere('fldstatus', 'Waiting');
                                })
                                ->where('fldtempbillno', NULL)
                                ->where('fldbillno','LIKE',$billNumberGeneratedString)
                                ->where('fldcomp','=',Helpers::getCompName())
                                ->first()->subtotal;
                        if (strpos($request->__encounter_id, 'IP') !== false){
                            // echo "sdfsdf"; exit;


                            $discount = PatBilling::where('fldencounterval', $request->__encounter_id)
                                ->where('fldditemamt', '>=', 0)
                                ->where('fldtempbillno', NULL)
                                ->where('fldcomp','=',Helpers::getCompName())
                                ->where('fldbillno','LIKE',$billNumberGeneratedString)
                                ->where(function ($query) {
                                    $query->orWhere('fldstatus', 'Cleared')
                                        ->orWhere('fldstatus', 'Waiting');
                                })
                                ->sum('flddiscamt');
                            $tax = PatBilling::where('fldencounterval', $request->__encounter_id)
                                ->where('fldditemamt', '>=', 0)
                                ->where('fldtempbillno', NULL)
                                ->where('fldbillno','LIKE',$billNumberGeneratedString)
                                ->where('fldcomp','=',Helpers::getCompName())
                                ->where(function ($query) {
                                    $query->orWhere('fldstatus', 'Cleared')
                                        ->orWhere('fldstatus', 'Waiting');
                                })
                                ->sum('fldtaxamt');
                            $receivedAmt = $subtotal-$discount+$tax;
                        }else{
                            $receivedAmt = $request->received_amount;
                        }
                        // echo $receivedAmt; exit;
                        $insertDataPatDetail = [
                            'fldencounterval' => $request->__encounter_id,
                            'fldbillno' => $billNumberGeneratedString,
                            'flditemamt' => Helpers::numberFormat($subtotal,'insert'), //actual price without discount and tax
                            'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
                            'flddiscountamt' => Helpers::numberFormat($discount,'insert'),
                            'fldreceivedamt' => Helpers::numberFormat($receivedAmt,'insert'),
                            'fldbilltype' => 'Cash',
//                            'fldbilltype' => "Cash", // for future; all except credit will be cash
                            'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                            'fldtime' => date("Y-m-d H:i:s"),
                            'flddiscountgroup' => $request->get('discount_mode'),
                            'fldbill' => 'INVOICE',
                            'fldsave' => 1,
                            'xyz' => 0,
                            'fldchargedamt' => Helpers::numberFormat($receivedAmt,'insert'),
                            'fldcomp' => Helpers::getCompName(),
                            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                            'claim_code' => $claim_code,
                            'fldprevdeposit' => Helpers::numberFormat(0,'insert'),
                            'remarks' => $request->get('remarks') ?? '',
                           'payment_mode' => $request->payment_mode, // insert weather it is cash, cheque, fonepay etc
                        ];

                        if ($request->payment_mode === "Cheque") {
                            $insertDataPatDetail['fldchequeno'] = $request->cheque_number;
                            $insertDataPatDetail['fldbankname'] = $request->bank_name;
                        }

                        /*if ($request->sent_to == "office") {
                            $insertDataPatDetail['tblofficename'] = $request->office_name;
                        }*/

                        if ($request->payment_mode === "other") {
                            $insertDataPatDetail['tblreason'] = $request->other_reason;
                        }

                        $patDetailsData = PatBillDetail::create($insertDataPatDetail);

                        $fonepaylogdata['fldbillno'] = $billNumberGeneratedString;
                        \App\Fonepaylog::where('id',$request->get('fonepaylog_id'))->update($fonepaylogdata);
                        $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $request->__encounter_id)->first();
                        $patDetailsData['location'] = $encounterData->fldcurrlocat;
                        DepartmentRevenueService::inserRevenueOrReturn($patDetailsData);
                        /*insert pat bill count*/
                        // PatBillCount::create(['fldbillno' => $billNumberGeneratedString, 'fldcount' => 1]);
                    }

                    /*insert pat bill count*/

                    //this is for fiscal year common function
                    MaternalisedService::insertMaternalisedFiscal($request->__encounter_id,$billNumberGeneratedString,$request->payment_mode);

                }

                if ($patbillingids) {
                    PatBillingShare::whereIn('pat_billing_id', $patbillingids)->update([
                        'status' => TRUE,
                    ]);
                }
                DB::commit();

                Session::flash('display_generated_invoice', true);
                Session::flash('invoice_number', $billNumberGeneratedString);
                Session::flash('last_encounter_id', $request->__encounter_id);
                session()->forget('billing_encounter_id');
                return redirect()->route('billing.display.form');
            } else {
                DB::rollBack();
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e->getMessage());
            dd($e);
            return redirect()->back()->with('error_message', __('messages.error'));
        }
    }

    public function getClaimCode($patbillings)
    {
        try {
            $total_ssf_item_cost = 0;
            foreach ($patbillings as $service) {

                $service_cost = ServiceCost::where([
                    ['fldid', $service->flditemno],
                    ['flditemtype', $service->fldbillingmode]
                ])->first();

                // $ssf_item_code = SsfService::getCodeByItemName($ssf_item_name);
                $ssf_item_code = "";

                // prepare for claim submission
                $claim_item = new ClaimItem([
                    'category' => 'service',
                    'quantity' => $service->flditemqty,
                    'item_code' => $ssf_item_code,
                    'unit_price' => Helpers::numberFormat($service->flditemrate,'insert')
                ]);

                $ssf_items[] = $claim_item->get();
                $total_ssf_item_cost = $service->flditemqty * $service->flditemrate;
            }

            // claim submission
            $data = [
                'patient_id' => $this->ssf_patient_id,
                'billable_period_start' => '2021-03-27T15:24:14+05:45',
                'billable_period_end' => '2021-03-27T16:04:22+05:45',
                'created_at' => '2021-03-29T12:53:39+05:45',
                'total_amount' => Helpers::numberFormat($total_ssf_item_cost,'insert'),
                'items' => $ssf_items
            ];

            $claim = new Claim($data);

            $result = Ssf::claimSubmission($claim);
            $result = json_decode($result->getContent(), true);
            if ($result['success'] == true) {
                $claim_code = $result['data']['id'];
                \Log::info('Claim code created:');
                \Log::info($claim_code);
                return $claim_code;
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return '';
        }
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function displayInvoice(Request $request)
    {
        $data['enpatient'] = Encounter::where('fldencounterval', $request['encounter_id'])->with('patientInfo')->first();

        $billno = $request['invoice_number'];
        $data['patbillingDetails'] = PatbillDetail::where('fldencounterval', $request['encounter_id'])
            ->where('fldtempbillno', $billno)
            ->first();

        $data['invoice_title'] = 'Invoice';
        if ($data['patbillingDetails']) {
            $cashbill = PatBilling::where('fldencounterval', $request['encounter_id'])
                ->where('fldstatus', 'Waiting');
            if ($request['invoice_number']) {
                $cashbill->where('fldtempbillno', "like", $request['invoice_number']);
            } else {
                $cashbill->where('fldbillno', 'like', "REG-%");
            }
            $data['invoice_title'] = 'Credit Bill';

            $data['patbilling'] = $cashbill->get();

            $data['billCount'] = PatBillCount::where('fldtempbillno', $billno)->count();
        } else {
            $cashbillQuery = PatBilling::where('fldencounterval', $request['encounter_id'])
                ->where('fldstatus', 'Cleared');
            if ($request['invoice_number']) {
                $cashbillQuery->where('fldbillno', $request['invoice_number']);
            } else {
                $cashbillQuery->where('fldbillno', 'like', "REG-%");
            }

            $cashbill = $cashbillQuery->get();

            $data['patbilling'] = $cashbill;
            if (!empty($cashbill)) {
                $billno = $cashbill[0]->fldbillno;
            }

            $data['patbillingDetails'] = PatBillDetail::where('fldencounterval', $request['encounter_id'])->where('fldbillno', $billno)->first();
            $data['billCount'] = PatBillCount::where('fldbillno', $billno)->count();
        }

        session()->forget('billing_encounter_id');
        return view('billing::invoice', $data);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function addGroupTest(Request $request)
    {
        try {
            $encounterData = \App\Encounter::select('fldcurrlocat')
                ->where('fldencounterval', $request->__encounter_id)
                ->first();

            $patientDepartment = "OP";
            if ($encounterData) {
                $department = \App\Departmentbed::select('fldbed', 'flddept')
                    ->with('department:flddept,fldcateg')
                    ->where('fldbed', $encounterData->fldcurrlocat)
                    ->first();
                if ($department && $department->department) {
                    if ($department->department->fldcateg == 'Patient Ward' || $department->department->fldcateg == 'Emergency') {
                        $patientDepartment = "IP";
                    } else {
                        $patientDepartment = "OP";
                    }
                }
            }

            $testGroupItems = ServiceGroup::where('fldgroup', $request->groupTest)->get();

            $serviceData = [
                'fldencounterval' => $request->__encounter_id,
                'fldbillingmode' => $request->__billing_mode,
                'flditemrate' => Helpers::numberFormat(0,'insert'),
                'flditemqty' => 1,
                'fldtaxper' => Helpers::numberFormat(0,'insert'),
                'fldtaxamt' => Helpers::numberFormat(0,'insert'),
                'fldditemamt' => Helpers::numberFormat(0,'insert'),
                'flddiscamt' => Helpers::numberFormat(0,'insert'),
                'flddiscper' => Helpers::numberFormat(0,'insert'),
                'fldopip' => $patientDepartment,
                'fldorduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                'fldordtime' => date("Y-m-d H:i:s"),
                'fldordcomp' => NULL,
                'flduserid' => NULL,
                'fldtime' => NULL,
                'fldcomp' => Helpers::getCompName(),
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
                'xyz' => '0',
                'discount_mode' => $request->discountMode,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            if($request->has('serviceItem')){
                $serviceData['package_name'] = $request->serviceItem;
            }
            $discountCal = new DiscountModeController();

            foreach ($testGroupItems as $service) {
                $itemDetails = ServiceCost::where('flditemname', $service->flditemname)->first();
                $serviceData['flditemtype'] = $itemDetails->flditemtype;
                $serviceData['flditemno'] = $itemDetails->fldid;
                $serviceData['flditemname'] = $itemDetails->flditemname;
                $serviceData['flditemrate'] = $itemDetails->flditemcost;

                if ($itemDetails) {
                    /**if service package have discount*/
                    $serviceDataDiscount = $discountCal->calculateDiscount($service, $itemDetails, $request);
                    $serviceData['flddiscper'] = $serviceDataDiscount['flddiscper'];
                    $serviceData['flddiscamt'] = $serviceDataDiscount['flddiscamt'];
                    $serviceData['fldditemamt'] = $serviceDataDiscount['fldditemamt'];

                }
                PatBilling::insert($serviceData);
            }
            return redirect()->route('billing.display.form');
        } catch (Exception $e) {
                        dd($e);
        }
        return redirect()->route('billing.display.form');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveServiceCosting(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            //            'billing_type_payment' => 'required',
            'encounter_id_payment' => 'required',
            'serviceItem' => 'required',
        ]);
        try {
            DB::beginTransaction();
            if ($validator->fails()) {
                return response()->json([
                    'status' => FALSE,
                    'message' => $validator->errors()
                ]);
            }

            $encounterData = \App\Encounter::select('fldcurrlocat')
                ->where('fldencounterval', $request->encounter_id_payment)
                ->first();

            $patientDepartment = "OP";
            $computer = Helpers::getCompName();
            if ($encounterData) {
                $department = \App\Departmentbed::select('fldbed', 'flddept')
                    ->with('department:flddept,fldcateg')
                    ->where('fldbed', $encounterData->fldcurrlocat)
                    ->first();
                if ($department && $department->department) {
                    if ($department->department->fldcateg == 'Patient Ward' || $department->department->fldcateg == 'Emergency') {
                        $patientDepartment = "IP";
                    } else {
                        $patientDepartment = "OP";
                    }
                }
            }

            $serviceData = [
                'fldencounterval' => $request->encounter_id_payment,
                'fldbillingmode' => $request->billingMode,
                'flditemrate' => Helpers::numberFormat(0,'insert'),
                'flditemqty' => 1,
                'fldtaxper' => Helpers::numberFormat(0,'insert'),
                'fldtaxamt' => Helpers::numberFormat(0,'insert'),
                'fldorduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                'fldordtime' => date("Y-m-d H:i:s"),
                'fldopip' => $patientDepartment,
                'fldordcomp' => NULL,
                'flduserid' => NULL,
                'fldtime' => NULL,
                'fldcomp' => Helpers::getCompName(),
                'fldsave' => 0,
                'fldbillno' => NULL,
                'fldparent' => 0,
                'fldprint' => 0,
                'fldstatus' => 'Punched',
                'fldalert' => 1,
                'fldtarget' => NULL,
                'fldpayto' => NULL,
                'fldrefer' => NULL,
                'fldreason' => NULL,
                'fldretbill' => NULL,
                'fldretqty' => 0,
                'fldsample' => 'Waiting',
                'xyz' => 0,
                'discount_mode' => $request->discountMode,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ];

            //            $customerDetails = Encounter::where('fldencounterval', $request->encounter_id_payment)->first();

            $itemtotal = 0;
            $returnData = [];
            // foreach ($request->serviceItem as $service) {

                $itemDetails = ServiceCost::where('flditemname', $request->serviceItem)->with('userPay')->first();
                // dd($itemDetails);
                $nodiscount = \App\NoDiscount::where('flditemname',$request->serviceItem)->first();
                if ($itemDetails) {
                    $serviceData['flditemtype'] = $itemDetails->flditemtype;
                    $serviceData['flditemno'] = $itemDetails->fldid;
                    $serviceData['flditemname'] = $itemDetails->flditemname;
                    $serviceData['flditemrate'] = Helpers::numberFormat($itemDetails->flditemcost,'insert');
                    $totalAmt = ($itemDetails->flditemcost * 1);
                    $returnData['total'] = $itemtotal + $totalAmt;
                    $returnData['total'] = Helpers::numberFormat($returnData['total'], 'insert');
                    /**calculate discount*/
                    $serviceData['flddiscper'] = 0;
                    $serviceData['flddiscamt'] = Helpers::numberFormat(0,'insert');
                    if ($request->discountMode != null and $itemDetails->discount == 1 and is_null($nodiscount)) {
                        $discountModeRaw = $this->discountMode->checkDiscountMode($request->discountMode, $itemDetails->flditemname);

                        $discountMode = $discountModeRaw->getData();

                        if ($discountMode->is_fixed) {
                            $serviceData['flddiscper'] = $discountMode->discountPercent;
                            $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountPercent) / 100,'insert');
                        } elseif ($discountMode->is_fixed === false && $discountMode->discountArray) {
                            $serviceData['flddiscper'] = $discountMode->discountArray->fldpercent;
                            $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArray->fldpercent) / 100,'insert');
                        } else {
                            if ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Diagnostic Tests") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldlab;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldlab)/ 100, 'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Radio Diagnostics") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldradio;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldradio) / 100, 'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Procedures") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldproc;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldproc) / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Equipment") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldequip;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(Helpers::numberFormat($totalAmt * $discountMode->discountArrayMain->fldequip) / 100, 'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "General Services") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldservice;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldservice) / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Others") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldother;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldother) / 100, 'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Medicine") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldmedicine;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldmedicine) / 100, 'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Surgical") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldsurgical;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldsurgical) / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $itemDetails->flditemtype === "Extra Item") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldextra;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldextra) / 100,'insert');
                            } else {
                                $serviceData['flddiscper'] = 0;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(0,'insert');
                            }
                        }
                    }
                    $serviceData['fldditemamt'] =  Helpers::numberFormat($totalAmt - $serviceData['flddiscamt'],'insert');
                    if ($itemDetails->fldcode != null) {
                        $tax = TaxGroup::where('fldgroup', $itemDetails->fldcode)->first();
                        $serviceData['fldtaxper'] = $tax->fldtaxper;
                        $taxAmtCalculation = ($serviceData['fldditemamt'] * $tax->fldtaxper / 100);
                        $serviceData['fldtaxamt'] =  Helpers::numberFormat($taxAmtCalculation,'insert');
                    }
                    $serviceData['fldtaxamt'] =  Helpers::numberFormat(($serviceData['fldtaxamt'] * $serviceData['flditemqty']),  'insert');
                    $serviceData['fldditemamt'] =  Helpers::numberFormat(($totalAmt - $serviceData['flddiscamt'] + $serviceData['fldtaxamt']),  'insert');

                    $serviceData['fldtaxamt'] =  Helpers::numberFormat(($serviceData['fldtaxamt'] * $serviceData['flditemqty']), 'insert');

                    $serviceData['fldditemamt'] =  Helpers::numberFormat(($totalAmt - $serviceData['flddiscamt'] + $serviceData['fldtaxamt']),  'insert');
                   $pat_billing_id = PatBilling::insertGetId($serviceData);
                }

            // }

            $returnData['pat_billing_id'] = $pat_billing_id;
            $data['addGroup'] = $addGroupList = ServiceGroup::groupBy('fldgroup')->get();
            $encounterDetailObj = new EncounterDetailsController();
            $encounterData = $encounterDetailObj->getEncounterData($request->encounter_id_payment, $data['addGroup']);

            $dataList = $encounterData['dataList'];
            $returnData['tableData'] = view('billing::dynamic-views.service-item-list', $dataList)->render();
            /**check if temporary or credit item must be displayed*/
                $returnData['subtotal'] = PatBilling::where('fldencounterval', $request->encounter_id_payment)
                    ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                    ->where(function ($query) use ($request) {
                        if ($request->temp_checked === 'no') {
                            $query->where('fldstatus', 'Punched');
                        } else {
                            $query->orWhere('fldstatus', 'Punched')
                                ->orWhere('fldstatus', 'Waiting');
                        }
                    })
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                    ->where('fldcomp', $computer)
                    ->where('fldditemamt', '>=', 0)
                    ->first()->subtotal;
                $returnData['total'] = PatBilling::where('fldencounterval', $request->encounter_id_payment)
                    ->where(function ($query) use ($request) {
                        if ($request->temp_checked === 'no') {
                            $query->where('fldstatus', 'Punched');
                        } else {
                            $query->orWhere('fldstatus', 'Punched')
                                ->orWhere('fldstatus', 'Waiting');
                        }
                    })
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                    ->where('fldcomp', $computer)
                    ->where('fldditemamt', '>=', 0)
                    ->sum('fldditemamt');
            $returnData['total'] = Helpers::numberFormat($returnData['total'],'insert');
            $returnData['discount'] = PatBilling::where('fldencounterval', $request->encounter_id_payment)
                ->where(function ($query) use ($request) {
                    if ($request->temp_checked === 'no') {
                        $query->where('fldstatus', 'Punched');
                    } else {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    }
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $returnData['discount'] = Helpers::numberFormat($returnData['discount'],'insert');
            $returnData['tax'] = PatBilling::where('fldencounterval', $request->encounter_id_payment)
                ->where(function ($query) use ($request) {
                    if ($request->temp_checked === 'no') {
                        $query->where('fldstatus', 'Punched');
                    } else {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    }
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
            $returnData['tax'] = Helpers::numberFormat($returnData['tax'],'insert');
            DB::commit();

            $shareablesetup = Options::get('shareable_setup');
            if(isset($itemDetails->category) and !is_null($itemDetails->category) and in_array('OPD Consultation', $itemDetails->category)){

                if($shareablesetup == 'both' or $shareablesetup == 'popup'){
                    $returnData['opd_consultation'] = '1';
                    $departments = \App\Department::select('flddept','fldid')->where('fldstatus', '1')->where('fldcateg', 'Consultation')->get();
                    $doctors = UserService::getDoctors(['firstname', 'lastname', 'id'])->pluck('fldfullname', 'id');
                    $depthtml = '';
                    $dochtml = '';
                    if(isset($departments) and count($departments) > 0){
                        $depthtml .='<option value="">--Select Department--</option>';
                        foreach($departments as $dept){
                            $depthtml .='<option value="'.$dept->flddept.'" data-fldid="'.$dept->fldid.'">'.$dept->flddept.'</option>';
                        }
                    }

                    $returnData['departmenthtml'] = $depthtml;
                    // $returnData['doctorhtml'] = $dochtml;
                }else{
                    $returnData['opd_consultation'] = '0';
                }

            }else if($itemDetails->category != '1' and !is_null($itemDetails->category) and !in_array('referable', $itemDetails->category)){
                if($shareablesetup == 'both' or $shareablesetup == 'popup'){
                    $returnData['opd_consultation'] = '2';
                }else{
                    $returnData['opd_consultation'] = '0';
                }

            }else{
                $returnData['opd_consultation'] = '0';
            }



            // $tax = 0;
            if ($itemDetails->fldcode != null) {
                $returnData['taxper'] = $tax->fldtaxper;
            }
            $returnData['itemname'] = $itemDetails->flditemname;
            // dd($returnData);
            return response()->json([
                'status' => TRUE,
                'message' => $returnData
            ]);
        } catch (Exception $e) {
            dd($e);
            DB::rollBack();
            \Log::info($e->getMessage());
            return response()->json([
                'status' => FALSE,
                'message' => "Something went wrong"
            ]);
        }
    }

    public function updateDoctorShareReferral(Request $request)
    {
        $pat_billing_ids = $request->get('pat_billing_ids');
        $user_id = $request->get('user_id');
        $username = $request->get('username');
        if ($pat_billing_ids) {
            DB::beginTransaction();
            try {
                PatBillingShare::where('type', 'referable')->whereIn('pat_billing_id', $pat_billing_ids)->delete();
                PatBilling::whereIn('fldid', $pat_billing_ids)->update([
                    'fldrefer' => $username,
                ]);
                foreach ($pat_billing_ids as $pat_billing_id) {
                    $pat_billing_share = new PatBillingShare();
                    $pat_billing_share->user_id = $user_id;
                    $pat_billing_share->pat_billing_id = $pat_billing_id;
                    $pat_billing_share->status = FALSE;
                    $pat_billing_share->type = 'Referable';
                    $pat_billing_share->save();

                    $share_update = PatBillingShareService::calculateIndividualShareNew($pat_billing_share->pat_billing_id);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Helpers::logStack([$e->getMessage() . ' in billing update doctor share referral', "Error"]);
                return response()->json([
                    'status' => FALSE,
                    'message' => "Something went wrong."
                ]);
            }
        }

        return response()->json([
            'status' => TRUE,
            'message' => "Data updated successfully."
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveDoctorShare(Request $request)
    {
        $request->validate([
            'pat_billing_id' => 'required|exists:tblpatbilling,fldid',
        ]);
        DB::beginTransaction();
        try {
            PatBillingShare::where([
                ['type', '<>', 'referable'],
                ['pat_billing_id', $request->pat_billing_id]
            ])->delete();
            // $pat_billing = PatBilling::find($request->pat_billing_id);
            // if ($pat_billing->has('pat_billing_shares')) {
            //     $pat_billing->pat_billing_shares()->delete();
            // }
            


            foreach ($request->share_category as $share_category) {
                if (isset($share_category['doctor_ids'])) {
                    if ($share_category['type'] == "OT Dr. Group") {
                        // this doctor_ids is alias for ot sub group category id
                        foreach ($share_category['doctor_ids'] as $sub_category_id) {
                            // get doctor list of this particular sub category [ot_group_sub_categories table]

                            $sub_category = OtGroupSubCategory::find($sub_category_id);

                            foreach ($sub_category->user_shares as $doctor) {
                                // calculate share of doctor for particular service.
                                $pat_billing_share = new PatBillingShare();
                                $pat_billing_share->user_id = $doctor->flduserid;
                                $pat_billing_share->pat_billing_id = $request->pat_billing_id;
                                $pat_billing_share->type = $share_category['type'];
                                $pat_billing_share->status = FALSE;
                                $pat_billing_share->ot_group_sub_category_id = $sub_category_id;
                                $pat_billing_share->share =  Helpers::numberFormat(($doctor->flditemshare/100)*$patbilling->fldditemamt,'insert') ;
                                $pat_billing_share->total_amount = Helpers::numberFormat($patbilling->fldditemamt,'insert');
                                $pat_billing_share->shareqty = $patbilling->flditemqty;
                                $pat_billing_share->usersharepercent = $doctor->flditemshare;

                                $pat_billing_share->save();
                            }
                        }
                    } else {
                        foreach ($share_category['doctor_ids'] as $user_id) {
                            // calculate share of doctor for particular service.
                            $pat_billing_share = new PatBillingShare();
                            $pat_billing_share->user_id = $user_id;
                            $pat_billing_share->pat_billing_id = $request->pat_billing_id;
                            $pat_billing_share->type = $share_category['type'];
                            $pat_billing_share->status = FALSE;
                            $pat_billing_share->save();
                        }
                    }
                }
            }

            // calculate and save each individual share
            if (isset($pat_billing_share)) {
                $share_update = PatBillingShareService::calculateIndividualShareNew($pat_billing_share->pat_billing_id);
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in billing save doctor share', "Error"]);

            return response()->json([
                'status' => FALSE,
                'message' => "Something went wrong."
            ]);
        }
        $service = PatBilling::where('fldid',$request->pat_billing_id)->first();
        $newdoctors = $service->pat_billing_shares()->select('type', 'user_id', 'ot_group_sub_category_id')->get();
        $doctors = \App\Utils\Helpers::getPayableDoctorName($request->pat_billing_id);

        $data['addGroup'] = $addGroupList = ServiceGroup::groupBy('fldgroup')->get();
        $encounterDetailObj = new EncounterDetailsController();
        $encounterData = $encounterDetailObj->getEncounterData($request->encounter_id, $data['addGroup']);
        
        $dataList = $encounterData['dataList'];


        $data['html'] = view('billing::dynamic-views.service-item-list', $dataList)->render();
        $data['tphtml'] = view('billing::dynamic-views.service-tp-item-list', $dataList)->render();
        return response()->json([
            'status' => TRUE,
            'message' => "Data updated successfully.",
            'doctors' => $doctors,
            'existingdoctors' => $newdoctors,
            'view' => $data
        ]);
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updatePanNumber(Request $request)
    {
        $validated = $request->validate([
            'pan_number' => 'required|unique:tblpatientinfo,fldpannumber',
            'patientId' => 'required',
        ]);

        try {
            $dataInsert['fldpannumber'] = $request->pan_number;
            PatientInfo::where('fldpatientval', $request->patientId)->update($dataInsert);

            return response([
                'success' => true,
                'message' => 'Pan number updated successfully.'
            ]);
        } catch (Exception $e) {
            return response([
                'success' => false,
                'message' => __('messages.error')
            ]);
        }
    }


    /**
     * @return RedirectResponse
     */
    public function resetEncounter()
    {
        Session::forget('billing_encounter_id');
        return redirect()->route('billing.display.form');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function getDataTemporaryBilling(Request $request)
    {
            $returnData['serviceData'] = PatBilling::where('fldencounterval', $request->encounter_id)
                ->where(function ($query) use ($request) {
                    if ($request->show_temporary === 'yes') {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    } else {
                        $query->where('fldstatus', 'Punched');
                    }
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldditemamt', '>=', 0)
                ->get();
            $returnData['total'] = PatBilling::where('fldencounterval', $request->encounter_id)
                ->where(function ($query) use ($request) {
                    if ($request->show_temporary === 'yes') {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    } else {
                        $query->where('fldstatus', 'Punched');
                    }
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
        $returnData['total'] = Helpers::numberFormat($returnData['total'],'insert');
        $returnData['tax'] = PatBilling::where('fldencounterval', $request->encounter_id)
            ->where(function ($query) use ($request) {
                if ($request->show_temporary === 'yes') {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                } else {
                    $query->where('fldstatus', 'Punched');
                }
            })
            ->where(function ($query) {
                $query->orWhere('flditemtype', '!=', 'Surgicals')
                    ->orWhere('flditemtype', '!=', 'Medicines')
                    ->orWhere('flditemtype', '!=', 'Extra Items');
            })
            ->where('fldditemamt', '>=', 0)
            ->sum('fldtaxamt');
        $returnData['tax'] = Helpers::numberFormat($returnData['tax'],'insert');
        $returnData['discount'] = PatBilling::where('fldencounterval', $request->encounter_id)
            ->where(function ($query) use ($request) {
                if ($request->show_temporary === 'yes') {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                } else {
                    $query->where('fldstatus', 'Punched');
                }
            })
            ->where(function ($query) {
                $query->orWhere('flditemtype', '!=', 'Surgicals')
                    ->orWhere('flditemtype', '!=', 'Medicines')
                    ->orWhere('flditemtype', '!=', 'Extra Items');
            })
            ->where('fldditemamt', '>=', 0)
            ->sum('flddiscamt');
        $returnData['discount'] = Helpers::numberFormat($returnData['discount'],'insert');
        $returnData['tableData'] = view('billing::dynamic-views.service-item-list', $returnData)->render();

        return response()->json([
            'status' => TRUE,
            'message' => $returnData
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function getItemsByServiceOrInventorySelect(Request $request)
    {
        $data['item_type'] = $item_type = $request->item_type;
        $billingMode = $request->get('billingMode');

        if ($item_type === "package") {
            $data['services'] = ServiceGroup::groupBy('fldgroup')->where('billingmode', $billingMode)->get();
        } elseif ($item_type === "service") {
            $data['services'] = ServiceCost::select('flditemname','fldbillitem', 'fldreport', 'flditemtype', 'flditemcost', 'fldcode', 'fldid')
                ->where('fldstatus', 'Active')
                ->where(function ($query) use ($billingMode) {
                    $query->orWhere('fldgroup', $billingMode)
                        ->orWhere('fldgroup', '%');
                })
                ->orderBy('flditemname', 'ASC')->get();
        }
        $html = view('billing::dynamic-views.service-select', $data)->render();
        return response()->json([
            'status' => true,
            'html' => $html
        ]);
    }

    /**
     * @param $encounter
     * @return array|string
     * @throws Throwable
     */
    public function itemHtml($encounter, $is_temp = 'no')
    {
            $computer = Helpers::getCompName();
            $data['serviceData'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) use ($is_temp) {
                    if ($is_temp === 'no') {
                        $query->where('fldstatus', 'Punched');
                    } else {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    }
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->orderBy('fldid', 'DESC')
                ->get();
            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where(function ($query) use ($is_temp) {
                    if ($is_temp === 'no') {
                        $query->where('fldstatus', 'Punched');
                    } else {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    }
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $data['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) use ($is_temp) {
                    if ($is_temp === 'no') {
                        $query->where('fldstatus', 'Punched');
                    } else {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    }
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $data['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) use ($is_temp) {
                    if ($is_temp === 'no') {
                        $query->where('fldstatus', 'Punched');
                    } else {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    }
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $data['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) use ($is_temp) {
                    if ($is_temp === 'no') {
                        $query->where('fldstatus', 'Punched');
                    } else {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    }
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');

        $html = view('billing::dynamic-views.service-item-list', $data)->render();
        return $html;
    }

    function updatepatbillshare()
    {
        $billshare = PatBillingShare::where('type', '!=', 'OPD Consultation')->get();
        if (!empty($billshare)) {
            foreach ($billshare as $bill) {
                $billno = $bill->pat_billing_id;
                $patbill = PatBilling::where('fldid', $billno)->first();
                $itemname = $patbill->flditemname;
                $amount = $patbill->fldditemamt;
                $servicecost = ServiceCost::where('flditemname', $itemname)->first();
                $share = $servicecost->other_share;
                if (!empty($share)) {
                    $rate = ($share / 100) * $amount;
                    $tax = (15/100)*$rate;
                    $data = array(
                        'share' => Helpers::numberFormat($rate,'insert'),
                   'total_amount' => Helpers::numberFormat($amount,'insert'),
                        'hospitalshare' => Helpers::numberFormat($servicecost->hospital_share,'insert'),
                        'usersharepercent' => $share,
                        'tax_amt' =>Helpers::numberFormat($tax,'insert'),
                        'shareqty' =>$bill->shareqty
                    );
                    PatBillingShare::where('pat_billing_id', $billno)->update($data);
                }
            }
        }
    }

    function updatepatbillshareopdconsult()
    {
        $billshare = PatBillingShare::where('type', '=', 'OPD Consultation')->get();
        if (!empty($billshare)) {
            foreach ($billshare as $bill) {
                $billno = $bill->pat_billing_id;
                $patbill = PatBilling::where('fldid', $billno)->first();
                $itemname = $patbill->flditemname;
                $amount = $patbill->fldditemamt;
                $servicecost = ServiceCost::where('flditemname', $itemname)->first();
                $share = $servicecost->other_share;
                if (!empty($share)) {
                    $rate = ($share / 100) * $amount;
                    $tax = (15/100)*$rate;

                    $data = array(
                        'share' => Helpers::numberFormat($rate,'insert'),
                        'total_amount' => Helpers::numberFormat($amount,'insert'),
                        'hospitalshare' => Helpers::numberFormat($servicecost->hospital_share,'insert'),
                        'usersharepercent' => $share,
                        'tax_amt' => Helpers::numberFormat($tax,'insert'),
                        'shareqty' =>$bill->shareqty
                    );
                    PatBillingShare::where('pat_billing_id', $billno)->update($data);
                }
            }
        }
    }

    public function saveUserPay(Request $request){
        // dd($request->all());
        DB::beginTransaction();
        try {
            PatBillingShare::where([
                ['pat_billing_id', $request->pat_billing_id]
            ])->delete();

            \App\TempConsult::where([
                ['pat_billing_id', $request->pat_billing_id]
            ])->delete();
            $patbilling = PatBilling::where('fldid',$request->pat_billing_id)->with('serviceCost')->first();
            $user = \App\CogentUsers::where('username',$request->billing_doctors)->first();

            if(isset($patbilling) and !empty($patbilling)){
                foreach($patbilling->serviceCost->category as $type){
                    $pat_billing_share = new PatBillingShare();
                    $pat_billing_share->user_id = $user->id;
                    $pat_billing_share->pat_billing_id = $request->pat_billing_id;
                    $pat_billing_share->type = $type;
                    $pat_billing_share->status = FALSE;
                    $pat_billing_share->save();
                    if($type == 'OPD Consultation'){
                        $tempconsultdata['fldencounterval'] = $patbilling->fldencounterval;
                        $tempconsultdata['pat_billing_id'] = $request->pat_billing_id;
                        $tempconsultdata['flddept'] = $request->billing_department;
                        $tempconsultdata['flddoctor'] = $user->id;
                        $tempconsultdata['flduserid'] = Auth::guard('admin_frontend')->user()->flduserid;

                        $tempconsultdata['fldcomp'] = Helpers::getCompName();


                        \App\TempConsult::create($tempconsultdata);
                    }
                }

            }


            // calculate and save each individual share
            if (isset($pat_billing_share)) {
                $share_update = PatBillingShareService::calculateIndividualShareNew($pat_billing_share->pat_billing_id);
            }
            DB::commit();

            $doctors = \App\Utils\Helpers::getPayableDoctorName($request->pat_billing_id);
            return response()->json([
                'status' => TRUE,
                'message' => "Information Saved.",
                'doctors' => $doctors
            ]);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in billing save doctor share', "Error"]);

            return response()->json([
                'status' => FALSE,
                'message' => "Something went wrong."
            ]);
        }

    }

    public function getOpdData(Request $request){
        try{
            $consultdata = \App\TempConsult::where('pat_billing_id',$request->pat_billing_id)->first();
            $item = \App\PatBilling::select('flditemname')->where('fldid',$request->pat_billing_id)->first();
            $departments = \App\Department::select('flddept','fldid')->where('fldstatus', '1')->where('fldcateg', 'Consultation')->get();
            $doctors = UserService::getDoctors(['firstname', 'lastname', 'id'])->pluck('fldfullname', 'id');
            $depthtml = '';
            $dochtml = '';
            if(isset($departments) and count($departments) > 0){
                $depthtml .='<option value="">--Select Department--</option>';
                foreach($departments as $dept){
                    if(isset($consultdata) and $dept->flddept == $consultdata->flddept){
                        $selected = 'selected';
                    }else{
                        $selected = '';
                    }
                    $depthtml .='<option value="'.$dept->flddept.'" data-fldid="'.$dept->fldid.'" '.$selected.'>'.$dept->flddept.'</option>';
                }
            }
            if(isset($doctors) and count($doctors) > 0){
                $dochtml .='<option value="">--Select Doctors--</option>';
                foreach($doctors as $key=>$doctor){
                    if(isset($consultdata) and $key == $consultdata->flddoctor){
                        $selected = 'selected';
                    }else{
                        $selected = '';
                    }
                    $dochtml .='<option value='.$key.' '.$selected.'>'.$doctor.'</option>';
                }
            }

            $returnData['dochtml'] = $dochtml;
            $returnData['depthtml'] = $depthtml;
            $returnData['itemname'] = $item->flditemname;
            return response()->json([
                'status' => TRUE,
                'message' => $returnData
            ]);
        }catch(\Exception $e){
            dd($e);
        }
    }
}
