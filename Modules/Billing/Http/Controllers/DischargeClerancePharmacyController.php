<?php

namespace Modules\Billing\Http\Controllers;

use App\AutoId;
use App\BillRemark;
use App\Consult;
use App\Department;
use App\Departmentbed;
use App\Encounter;
use App\Fiscalyear;
use App\PatBillCount;
use App\PatBillDetail;
use App\PatBilling;
use App\PatientDate;
use App\PatTiming;
use App\ServiceCost;
use App\Utils\Helpers;
use App\Utils\Options;
use App\Year;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\AdminEmailTemplate\Http\Controllers\AdminSmsTemplateController;
use Session;
use App\Services\MaternalisedService;

class DischargeClerancePharmacyController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function dischargeClearance(Request $request)
    {
        $data['subtotal'] = $data['total'] = $data['discount'] = $data['tax'] = $data['bedcharge'] = 0;
        if ($request->has('encounter_id')) {

            $encounter_id = $request->encounter_id;
            $data['bedcharge'] = 0;
            $data['bedname'] = '';
            $data['enpatient'] = $enpatient = Encounter::where('fldencounterval', $encounter_id)->with('patientInfo')->first();
            $getBedDetails = Departmentbed::where('fldencounterval', $encounter_id)->first();
            if (isset($getBedDetails)) {
                $getBeddeparment = Department::where('flddept', $getBedDetails->flddept)->first();
                if (isset($getBeddeparment)) {
                    if (isset($getBeddeparment->fldhead) && $getBeddeparment->fldhead != "0") {
                        $servicecost = ServiceCost::where([
                            'flditemtype' => "General Services",
                            'flditemname' => $getBeddeparment->fldhead
                        ])->first();
                        $data['bedname'] = $getBeddeparment->fldhead;
                    }
                }
            }



            if (isset($enpatient) && $enpatient->patientInfo) {
                $date = $enpatient->flddoa;
                $doa =  $enpatient->flddoa;
                $datework = \Carbon\Carbon::createFromDate($date);
                $now = \Carbon\Carbon::now();
                $noofdays = $datework->diffInDays($now);
            } else {
                $doa = 0;
                $noofdays = 0;
            }

            $getCategory = PatBilling::select('flditemtype')
                ->where('fldencounterval', $encounter_id)
                ->where('fldditemamt', '>=', 0)
                ->where('fldtempbillno', 'LIKE', '%TPPHM%')
                ->where('fldcomp', '=', Helpers::getCompName())
                ->whereIn('fldstatus', ['Waiting', 'Punched'])
                ->groupBy('flditemtype')->get();


            if ($getCategory) {
                // echo "here"; exit;
                foreach ($getCategory as $k => $billing) {
                    $data['eachpatbilling'][$k]['category'] = $billing->flditemtype;


                    $haveautobill = PatBilling::where('flditemname', $data['bedname'])
                        ->where('fldencounterval', $enpatient->fldencounterval)
                        ->where('fldcomp', '=', Helpers::getCompName())
                        ->first();

                    if ($haveautobill) {
                        $this->updateautobillingbedcharge($haveautobill->fldid, $noofdays);
                    }


                    $patbillData = PatBilling::where('flditemtype', $billing->flditemtype)
                        ->where('fldencounterval', $encounter_id)
                        ->where('fldditemamt', '>=', 0)
                        ->where('fldtempbillno', 'LIKE', '%TPPHM%')
                        ->where('fldcomp', '=', Helpers::getCompName())
                        ->whereIn('fldstatus', ['Waiting', 'Punched'])
                        ->get();

                    $data['eachpatbilling'][$k]['details'] = $patbillData;
                    $data['eachpatbilling'][$k]['total'] =  $patbillData->sum('fldditemamt') + $patbillData->sum('flddiscamt');
                    $data['patbillingdetail'][$k] = PatbillDetail::where('fldtempbillno', $billing->fldtempbillno)
                        ->where('fldcomp', '=', Helpers::getCompName())
                        ->first();
                }

                // dd($data);
                $data['subtotal'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                    ->where('fldstatus', 'Waiting')
                    ->where('fldcomp', '=', Helpers::getCompName())
                    ->first()->subtotal;
                $data['total'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', 'LIKE', '%TPPHM%')
                    ->where('fldcomp', '=', Helpers::getCompName())
                    ->whereIn('fldstatus', ['Waiting', 'Punched'])
                    ->sum('fldditemamt');
                $data['discount'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', 'LIKE', '%TPPHM%')
                    ->where('fldcomp', '=', Helpers::getCompName())
                    ->whereIn('fldstatus', ['Waiting', 'Punched'])
                    ->sum('flddiscamt');
                $data['tax'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldditemamt', '>=', 0)
                    ->where('fldtempbillno', 'LIKE', '%TPPHM%')
                    ->where('fldcomp', '=', Helpers::getCompName())
                    ->whereIn('fldstatus', ['Waiting', 'Punched'])
                    ->sum('fldtaxamt');
                $ref_doctor_pat  = PatBilling::with('referUserdetail')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldrefer', '!=', NULL)
                    ->first();
                if ($ref_doctor_pat) {
                    $data['referable_doctor'] = (($ref_doctor_pat->fldrefer && $ref_doctor_pat->referUserdetail) ? $ref_doctor_pat->referUserdetail->fldfullname : '');
                } else {
                    $ref_doctor_consult  = Consult::with('user')
                        ->where('fldencounterval', $encounter_id)
                        ->first();
                    if ($ref_doctor_consult) {
                        $data['referable_doctor'] = (($ref_doctor_consult->flduserid && $ref_doctor_consult->user) ? $ref_doctor_consult->user->fldfullname : '');
                    }
                }


                $Deposit = \App\PatBillDetail::where('fldencounterval', $encounter_id)
                    ->where('fldbillno', 'like', '%DEP%')
                    ->where('fldpayitemname', 'Pharmacy Deposit')
                    ->where('fldcomp', '=', Helpers::getCompName())
                    ->sum('fldreceivedamt');




                $data['remaining_deposit'] =  $rd = \App\PatBillDetail::select('fldcurdeposit')
                    ->where('fldencounterval', $request->encounter_id)
                    ->where('fldbilltype', '=', 'Credit')
                    ->where('fldcomp', '=', Helpers::getCompName())
                    ->orderBy('fldid', 'DESC')
                    ->first();
                if ($rd)
                    $data['previousDeposit'] =  $rd->fldcurdeposit;
                else
                    $data['previousDeposit'] =  0;
            }
        }


        return view('billing::dischargeClearance-pharmacy', $data);
    }


    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function finalPaymentDischarge(Request $request)
    {
        try {
            DB::beginTransaction();
            $patbilling = PatBilling::where([['fldencounterval', $request->encounter_id]])
                ->where('fldditemamt', '>=', 0)
                ->where('fldtempbillno', 'LIKE', '%TPPHM%')
                ->where('fldcomp', '=', Helpers::getCompName())
                ->whereIn('fldstatus', ['Waiting', 'Punched'])->get();
            $encounterDetail = Encounter::where('fldencounterval', $request->encounter_id)->first();
            $dateToday = Carbon::now();
            $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')
                ->first();

            $billNumber = AutoId::where('fldtype', 'InvoiceNo')->first();

            $new_bill_number = $billNumber->fldvalue + 1;
            $billNumber->update(['fldvalue' => $new_bill_number]);
            $billNumberGeneratedString = "PHM-$year->fldname-$new_bill_number" . Options::get('hospital_code');

            $total = 0;
            $discount = 0;

            if ($patbilling) {
                $itemamount = 0;
                foreach ($patbilling as $bill) {
                    $discountamount = ($request->discountpercentage*$bill->flditemrate*$bill->flditemqty)/100;

                    $taxamt = ((($bill->flditemrate*$bill->flditemqty)-$discountamount)*$bill->fldtaxper)/100;
                    $finalitemamount = ($bill->flditemrate*$bill->flditemqty)-$discountamount+$taxamt;
                    $updateDataPatBilling = [
                        'fldbillno' => $billNumberGeneratedString,
                        'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                        'fldtime' => date("Y-m-d H:i:s"),
                        'fldsave' => 1,
                        'fldstatus' => 'Cleared',
                        'xyz' => 0,
                        'flddiscper' => $request->discountpercentage,
                        'flddiscamt' => Helpers::numberFormat($discountamount,'insert'),
                        'fldtaxamt' => Helpers::numberFormat($taxamt,'insert'),
                        'fldditemamt' => Helpers::numberFormat($finalitemamount,'insert')

                    ];
                    $bill->update($updateDataPatBilling);

                    $itemamount += $bill->flditemrate * $bill->flditemqty;
                    $total += $bill->flditemrate * $bill->flditemqty;
                    $discount += $bill->flddiscamt;
                }

                /*insert pat bill details*/
                //                $patbilldetailtemp = PatbillDetail::where('fldtempbillno', $patbilling[0]->fldtempbillno)->first();
                if (!empty($request->discountAmount)) {
                    $totaldis = $request->discountAmount;
                } else {
                    $totaldis = $discount;
                }

                if (!empty($request->nettotal)) {
                    $nettotal = $request->nettotal;
                } else {
                    $nettotal = $total;
                }

                $tax = $request->tax;

                $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
                    ->where('fldencounterval', $request->encounter_id)
                    ->where('fldbilltype', '=', 'Credit')
                    ->where('fldcomp', '=', Helpers::getCompName())
                    ->orderBy('fldid', 'DESC')
                    ->first();

                if (isset($previousDeposit) and !empty($previousDeposit)) {
                    $currentdeposit = $previousDeposit->fldcurdeposit;
                } else {
                    $currentdeposit = 0;
                }


                if($request->received_amount > 0 and $request->payment_mode !='Credit'){
                    $mode = $request->payment_mode;
                }else{
                    $mode = 'Credit';
                }
                $insertDataPatDetail = [
                    'fldencounterval' => $request->encounter_id,
                    'fldpayitemname' => 'Discharge Clearence',
                    'fldbillno' => $billNumberGeneratedString,
                    'remarks' => $request->discharge_remark,
                    'flditemamt' =>  Helpers::numberFormat($itemamount,'insert'),
                    'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
                    'flddiscountamt' => Helpers::numberFormat($request->discountAmount,'insert'),
                    'fldreceivedamt' => Helpers::numberFormat($request->received_amount,'insert'),
                    'fldbilltype' => 'Credit',
                    'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                    'fldtime' => date("Y-m-d H:i:s"),
                    'fldbill' => 'INVOICE',
                    'fldsave' => 1,
                    'xyz' => 0,
                    'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                    'fldcomp' => Helpers::getCompName(),
                    'fldprevdeposit' => Helpers::numberFormat($request->curdeposit,'insert'),
                    'fldcurdeposit' => Helpers::numberFormat($request->cur_deposit,'insert'),
                    'fldchargedamt' => Helpers::numberFormat(abs($itemamount -  $totaldis + $tax),'insert'),
                    'flddiscountgroup' =>  $encounterDetail ? $encounterDetail->flddisctype : '',
                    'payment_mode' => $mode


                ];



                if ($request->payment_mode === "cheque") {
                    $insertDataPatDetail['fldchequeno'] = $request->cheque_number;
                    $insertDataPatDetail['fldbankname'] = $request->bank_name;
                }

                if ($request->payment_mode === "other") {
                    $insertDataPatDetail['tblreason'] = $request->other_reason;
                }

                if ($request->payment_mode === "credit" || $request->payment_mode === "Credit") {
                    $insertDataPatDetail['fldprevdeposit'] = Helpers::numberFormat($currentdeposit,'insert');
                    $amt = $itemamount - $totaldis + $tax;
                    $fldchargedamt = $currentdeposit - $amt;
                    $insertDataPatDetail['fldchargedamt'] = Helpers::numberFormat(abs($fldchargedamt),'insert'); //;abs($currentdeposit- $itemamount -  $totaldis + $tax);
                    $insertDataPatDetail['tblexpecteddate'] = $request->expected_payment_date;
                }

                $patDetailsData = PatBillDetail::create($insertDataPatDetail);

                #Fonepay payment log for Bill Number
                $fonepaylogdata['fldbillno'] = $billNumberGeneratedString;
                \App\Fonepaylog::where('id',$request->get('fonepaylog_id'))->update($fonepaylogdata);
                #End Fonepay payment log for Bill Number
                \App\Services\DepartmentRevenueService::inserRevenueOrReturn($patDetailsData);

                /*insert pat bill count*/

                $updatepatbillcount = [
                    'fldbillno' => $billNumberGeneratedString,
                    'fldcount' => 1

                ];
                PatBillCount::where('fldtempbillno', $patbilling[0]->fldtempbillno)
                    ->update($updatepatbillcount);



                if (!empty($request->discharge_remark)) {
                    $insertbillremark = [
                        'fldbillno' => $billNumberGeneratedString,
                        'fldbillremark' => $request->discharge_remark

                    ];
                    BillRemark::create($insertbillremark);
                }

                MaternalisedService::insertMaternalisedFiscalPharmacy($request->encounter_id,$billNumberGeneratedString,$request->payment_mode);

                // $this->insertDischargePatientFirst($request);
            }

            DB::commit();

            Session::flash('display_generated_invoice', true);
            Session::flash('billing_encounter_id', $request->encounter_id);
            Session::flash('receive_amtphar_bill', $request->received_amount);
            Session::flash('billing_printids', 1);
            Session::flash('payment_mode', $mode);
            Session::flash('invoice_number', $billNumberGeneratedString);


            return response()->json([
                'status' => TRUE,
                'invoice_number' => $billNumberGeneratedString,
                'routingurltoprint' => 'billing/service/discharge/printPharamcy?receive_amt=' . $request->received_amount . '&printIds=&fldencounterval=' . $request->get('encounter_id') . '&printIds=&billNumberGeneratedString=' . $billNumberGeneratedString .'&payment_mode='.$mode
            ]);
        } catch (\Exception $e) {

            DB::rollBack();
            dd($e);
            return redirect()->back();
        }
    }
    private function _getAllMedicine($type = 'ordered', $encounterId)
    {
        // echo $type; exit;
        // echo $encounterId; exit;\
        // echo $type; exit;
        $encounter_id = $encounterId;
        $allMedicines = \App\PatDosing::select('fldid', 'fldroute', 'flddose', 'fldfreq', 'flddays', 'fldqtydisp', 'fldlabel', 'flditem', 'flduserid_order', 'flddiscper', 'fldtaxper', 'fldstarttime', 'fldstock', 'fldvatamt', 'fldvatper', 'flditemtype')
            ->with('medicineBySetting', 'medicineBySetting.medbrand', 'medicineBySetting.medbrand.label')
            ->where('fldencounterval', $encounter_id);
        if ($type == 'ordered') {
            $allMedicines->where([
                'fldsave_order' => '0',
                'fldcurval' => 'Continue',
            ]);
        } else {
            $allMedicines->where([
                'fldsave_order' => '1',
            ])
                ->where(function ($query) {
                    $query->orWhere('flditemtype', 'Medicines');
                    $query->orWhere('flditemtype', 'Surgicals');
                    $query->orWhere('flditemtype', 'Extra Items');
                });
        }
        return $allMedicines->get();
    }

    public function printPharmacy(Request $request)
    {
        $medicines = $this->_getAllMedicine('discharge', $request->get('fldencounterval'));

        $encounterinfo = \App\Utils\Helpers::getPatientByEncounterId($request->get('fldencounterval'));
        $printIds = explode(',', $request->get('printIds'));
        $paymentmode = $request->get('payment_mode');

        $dispensemedicine = PatBilling::where('fldbillno', $request->get('billNumberGeneratedString'))
            ->where('fldencounterval', $request->get('fldencounterval'))
            ->where('fldstatus', 'Cleared')
            ->where('fldsave', '1')
            ->get();
        Options::update('disable_dispensing', 0);
        $billNumberGeneratedString = $request->get('billNumberGeneratedString');
        $time = date('Y-m-d');

        $depositDetail = PatBillDetail::where('fldbillno', 'LIKE', '%DEP%')
            ->where('fldencounterval', $request->get('fldencounterval'))
            ->where('fldcomp', '=', Helpers::getCompName())
            ->get();


        return view('dispensar::pdf.dispense-print', compact('encounterinfo', 'medicines', 'printIds', 'billNumberGeneratedString', 'time', 'paymentmode', 'dispensemedicine', 'depositDetail'));
    }

    public function finalPaymentDischargeRefundDeposit(Request $request)
    {
        try {


            $dateToday = Carbon::now();
            $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')
                ->first();

            $billNumber = AutoId::where('fldtype', 'InvoiceNo')->first();

            $new_bill_number = $billNumber->fldvalue + 1;
            $billNumber->update(['fldvalue' => $new_bill_number]);
            $billNumberGeneratedString = "DEP-$year->fldname-$new_bill_number" . Options::get('hospital_code');



            $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
                ->where('fldencounterval', $request->encounter_id)
                ->where('fldbilltype', '=', 'Credit')
                ->where('fldcomp', '=', Helpers::getCompName())
                ->orderBy('fldid', 'DESC')
                ->first();

            if (isset($previousDeposit) and !empty($previousDeposit)) {
                $currentdeposit = $previousDeposit->fldcurdeposit;
            } else {
                $currentdeposit = 0;
            }

            $encounterDetail = Encounter::where('fldencounterval', $request->encounter_id)->first();


            $insertDataPatDetail = [
                'fldencounterval' => $request->encounter_id,
                'fldpayitemname' => 'Pharmacy Deposit Refund',
                'fldbillno' => $billNumberGeneratedString,
                'fldprevdeposit' =>  Helpers::numberFormat($currentdeposit,'insert'),
                'flditemamt' =>  Helpers::numberFormat(0,'insert'),
                'fldtaxamt' => Helpers::numberFormat(0,'insert'),
                'flddiscountamt' => Helpers::numberFormat(0,'insert'),
                'fldchargedamt' =>  Helpers::numberFormat(0,'insert'),
                'fldreceivedamt' => '-' . Helpers::numberFormat($currentdeposit,'insert'),
                'fldbilltype' => 'Credit',
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                'fldtime' => date("Y-m-d H:i:s"),
                'fldbill' => 'INVOICE',
                'fldsave' => 1,
                'xyz' => 0,
                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                'fldcomp' => Helpers::getCompName(),
                'fldcurdeposit' => Helpers::numberFormat(($currentdeposit - $currentdeposit),'insert'),
                'flddiscountgroup' =>  $encounterDetail ? $encounterDetail->flddisctype : ''

            ];


            $patDetailsData = PatBillDetail::create($insertDataPatDetail);
            \App\Services\DepartmentRevenueService::inserRevenueOrReturn($patDetailsData);

            $insertbillremark = [
                'fldbillno' => $billNumberGeneratedString,
                'fldbillremark' => 'Pharmacy Deposit Refund'

            ];
            BillRemark::create($insertbillremark);


            MaternalisedService::insertMaternalisedFiscalDeposit($request->encounter_id,$billNumberGeneratedString,'cash');


            Session::flash('print_preview', $request->print_preview);

            Session::flash('billing_encounter_id', $request->encounter_id);
            Session::flash('receive_amtphar_bill', $request->received_amount);
            Session::flash('billing_printids', 1);
            // Session::flash('payment_mode', $mode);
            Session::flash('invoice_number', $billNumberGeneratedString);

            return response()->json([
                'status' => TRUE,
                'invoice_number' => $billNumberGeneratedString,
                'billing_encounter_id' => $request->encounter_id,
                'display_generated_invoice' => true,
                'print_preview' => $request->print_preview,
            ]);
        } catch (\Exception $e) {

            return redirect()->back();
        }
    }


    /**
     * @param Request $request
     * @return string
     */
    public function insertDischargePatient(Request $request)
    {
        try {
            /*new changes*/
            $fldencounterval = $request->encounter_id;
            $currentLoc = Encounter::select('fldcurrlocat')
                ->where('fldencounterval', $fldencounterval)
                ->first();

            $pattiming = PatTiming::where('fldencounterval', $fldencounterval)
                ->where('fldtype', 'General Services')
                ->where('fldfirstreport', 'Bed')
                ->where('fldfirstsave', 1)
                ->where('fldsecondsave', 0)

                ->get();

            if (count($pattiming)) {
                $patData['fldsecondreport'] = $currentLoc->fldcurrlocat;
                $patData['fldseconduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                $patData['fldsecondtime'] = date("Y-m-d H:i:s");
                $patData['fldsecondcomp'] = Helpers::getCompName();
                $patData['fldsecondsave'] = 1;
                $patData['xyz'] = 0;

                PatTiming::where([
                    'fldtype' => 'General Services',
                    'fldfirstreport' => 'Bed',
                    'fldfirstsave' => 1,
                    'fldsecondsave' => 0,
                ])->update($patData);
            }

            Departmentbed::where('fldencounterval', $fldencounterval)->update(['fldencounterval' => NULL]);

            $encounterData['xyz'] = 0;


            Encounter::where('fldencounterval', $fldencounterval)->update($encounterData);
            /*new changes*/


            $data = array(
                'fldencounterval' => $fldencounterval,
                'fldhead' => 'Discharged',
                'fldcomment' => $request->discharge_remark,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid, //admin
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'fldcomp' => Helpers::getCompName(), //comp02
                'fldsave' => 1,
                'flduptime' => Null,
                'xyz' => 0,
            );
            $latest_id = PatientDate::insertGetId($data);


            if (Options::get('low_deposit_text_message')) {
                $encounter = Encounter::where('fldencounterval', $fldencounterval)
                    ->with(['patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldrank'])
                    ->first();
                $text = strtr(Options::get('low_deposit_text_message'), [
                    '{$name}' => $encounter->patientInfo->fldfullname,
                    '{$systemname}' => isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '',
                ]);
                (new AdminSmsTemplateController())->sendSms([
                    'text' => $text,
                    'to' => $encounter->patientInfo->fldptcontact,
                ]);
            }


            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Complaint update Successfully.');
                return $latest_id;
            }

            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return __('messages.error');
        } catch (\Exception $e) {
            //                        dd($e);
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return 'Sorry! something went wrong';
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function insertDischargePatientFirst(Request $request)
    {
        try {
            /*new changes*/
            $fldencounterval = $request->encounter_id;
            $currentLoc = Encounter::select('fldcurrlocat')
                ->where('fldencounterval', $fldencounterval)
                ->first();

            $pattiming = PatTiming::where('fldencounterval', $fldencounterval)
                ->where('fldtype', 'General Services')
                ->where('fldfirstreport', 'Bed')
                ->where('fldfirstsave', 1)
                ->where('fldsecondsave', 0)

                ->get();

            if (count($pattiming)) {
                $patData['fldsecondreport'] = $currentLoc->fldcurrlocat;
                $patData['fldseconduserid'] = Auth::guard('admin_frontend')->user()->flduserid;
                $patData['fldsecondtime'] = date("Y-m-d H:i:s");
                $patData['fldsecondcomp'] = Helpers::getCompName();
                $patData['fldsecondsave'] = 1;
                $patData['xyz'] = 0;

                PatTiming::where([
                    'fldtype' => 'General Services',
                    'fldfirstreport' => 'Bed',
                    'fldfirstsave' => 1,
                    'fldsecondsave' => 0,
                ])->update($patData);
            }

            Departmentbed::where('fldencounterval', $fldencounterval)->update(['fldencounterval' => NULL]);

            $encounterData['xyz'] = 0;
            $encounterData['fldcurrlocat'] = null;

            Encounter::where('fldencounterval', $fldencounterval)->update($encounterData);
            /*new changes*/


            $data = array(
                'fldencounterval' => $fldencounterval,
                'fldhead' => 'Discharged',
                'fldcomment' => $request->discharge_remark,
                'flduserid' => Auth::guard('admin_frontend')->user()->flduserid, //admin
                'fldtime' => now(), //'2020-02-23 11:13:27.709'
                'fldcomp' => Helpers::getCompName(), //comp02
                'fldsave' => 1,
                'flduptime' => Null,
                'xyz' => 0,
            );
            $latest_id = PatientDate::insertGetId($data);


            if (Options::get('low_deposit_text_message')) {
                $encounter = Encounter::where('fldencounterval', $fldencounterval)
                    ->with(['patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldrank'])
                    ->first();
                $text = strtr(Options::get('low_deposit_text_message'), [
                    '{$name}' => $encounter->patientInfo->fldfullname,
                    '{$systemname}' => isset(Options::get('siteconfig')['system_name']) ? Options::get('siteconfig')['system_name'] : '',
                ]);
                (new AdminSmsTemplateController())->sendSms([
                    'text' => $text,
                    'to' => $encounter->patientInfo->fldptcontact,
                ]);
            }


            if ($latest_id) {
                Session::flash('display_popup_error_success', true);
                Session::flash('success_message', 'Complaint update Successfully.');
                return $latest_id;
            }

            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return __('messages.error');
        } catch (\Exception $e) {
            //                        dd($e);
            Session::flash('display_popup_error_success', true);
            Session::flash('error_message', 'Sorry! something went wrong');
            return 'Sorry! something went wrong';
        }
    }

    public function print(Request $request)
    {
        $medicines = $this->_getAllMedicine('discharge', $request->get('fldencounterval'));

        $encounterinfo = \App\Utils\Helpers::getPatientByEncounterId($request->get('fldencounterval'));
        $printIds = array();
        $paymentmode = $request->get('payment_mode');

        $dispensemedicine = PatBilling::where('fldbillno', $request->get('billno'))
            ->where('fldencounterval', $request->get('fldencounterval'))
            ->where('fldstatus', 'Cleared')
            ->where('fldsave', '1')
            ->get();
        Options::update('disable_dispensing', 0);
        $billNumberGeneratedString = $request->get('billno');
        $time = date('Y-m-d');

        $depositDetail = PatBillDetail::where('fldbillno', 'LIKE', '%DEP%')
            ->where('fldencounterval', $request->get('fldencounterval'))
            ->where('fldcomp', '=', Helpers::getCompName())
            ->get();
        /**count*/
        $countdata = PatBillCount::where('fldbillno', $billNumberGeneratedString)->pluck('fldcount')->first();
        $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != '') ? $countdata + 1 : 1;

        if (isset($countdata) and $countdata != '') {
            PatBillCount::where('fldbillno', $billNumberGeneratedString)->update($updatedata);
        } else {
            $insertdata['fldbillno'] = $billNumberGeneratedString;
            $insertdata['fldcount'] = 1;
            PatBillCount::insert($insertdata);
        }
        $data['billCount'] = $count;
        $discharge_invoice_title = 'Invoice (COPY OF ORIGINAL) Print-' . $count;
        return view('dispensar::pdf.dispense-print', compact('encounterinfo', 'medicines', 'printIds', 'billNumberGeneratedString', 'time', 'paymentmode', 'dispensemedicine', 'depositDetail','discharge_invoice_title'));
    }

    // yo chahi print jasle insert n bill katcha
    public function printDis(Request $request)
    {



        $encounter_id = $request->encounter_id;

        $time = date('Y-m-d H:i:s');
        $userid = \Auth::guard('admin_frontend')->user()->flduserid;
        $computer = \App\Utils\Helpers::getCompName();
        $departmentId = Helpers::getUserSelectedHospitalDepartmentIdSession();
        $fldremark = $request->discharge_remark;

        $patbillings = [];


        $taxtotal = 0;
        $discounttotal = 0;
        $itemtotal = 0;
        $chargedAmount = 0;
        $outofstockitem = array();
        \DB::beginTransaction();
        $instockmedicineitem = array();
        $billNumberGeneratedString = '';
        $opip = ($request->get('opip') == 'OP') ? 'OP' : 'IP';
        try {

            if (isset($medicines) and count($medicines) > 0) {
                // $medicinesCount = count($medicines);
                $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
                $dateToday = \Carbon\Carbon::now();
                $year = \App\Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();
                $billNumberGeneratedString = "PHM-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');
                if ($request->get('payment_mode') == 'Credit') {

                    foreach ($medicines as $key => $medicine) {
                        $currentStock = $medicine->medicineBySetting ? $medicine->medicineBySetting->fldqty : 0;
                        if ($currentStock > 0 && ($currentStock - $medicine->fldqtydisp) >= 0) {

                            $discount = '0';
                            $itemrate = ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldsellpr : 0;
                            if ($discountpercentage)
                                $discount = ($itemrate * $medicine->fldqtydisp * $discountpercentage) / 100;
                            else
                                $discount = $medicine->flddiscamt;

                            \App\PatDosing::where('fldid', $medicine->fldid)->update([
                                'fldlevel' => 'Dispensed',
                                'fldendtime' => $time,
                                'fldsave_order' => '1',
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'xyz' => '0',
                            ]);
                            $updatedvalue = ($medicine->medicineBySetting->fldqty) - ($medicine->fldqtydisp);
                            \App\Entry::where([
                                'fldstockno' => $medicine->medicineBySetting->fldstockno
                            ])->update([
                                'fldqty' => $updatedvalue,
                                'fldsav' => '1',
                                'xyz' => '0',
                            ]);

                            $tax = $medicine->fldtaxamt;
                            $discount = $discount;
                            $taxtotal += $tax;
                            $discounttotal += $discount;
                            $itemamount = ($itemrate * $medicine->fldqtydisp) + $tax - $discount;
                            $itemtotal += $itemrate * $medicine->fldqtydisp;
                            $chargedAmount += $itemamount;
                            $patbillings[] = [
                                'fldencounterval' => $encounter_id,
                                'fldbillingmode' => $request->get('fldbillingmode'),
                                'flditemtype' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldcategory : '',
                                'flditemno' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldstockno : NULL,
                                'flditemname' => $medicine->flditem,
                                'flditemrate' => Helpers::numberFormat($itemrate,'insert'),
                                'flditemqty' => $medicine->fldqtydisp,
                                'fldtaxper' => $medicine->fldtaxper,
                                'flddiscper' => $discountpercentage ?: Helpers::numberFormat($medicine->flddiscper,'insert'),
                                'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
                                'flddiscamt' => Helpers::numberFormat($discount,'insert'),
                                'fldditemamt' => Helpers::numberFormat($itemamount,'insert'),
                                'fldopip' => $opip,
                                'fldorduserid' => $userid,
                                'fldordtime' => $time,
                                'fldordcomp' => $computer,
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'fldsave' => '1',
                                'fldbillno' => $billNumberGeneratedString,
                                'fldparent' => $medicine->fldid,
                                'fldprint' => '0',
                                'fldstatus' => 'Cleared',
                                'fldalert' => '1',
                                'fldtarget' => NULL,
                                'fldpayto' => NULL,
                                'fldrefer' => NULL,
                                'fldreason' => NULL,
                                'fldretbill' => NULL,
                                'fldretqty' => 0,
                                'fldsample' => 'Waiting',
                                'xyz' => '0',
                                'fldvatamt' => Helpers::numberFormat(0,'insert'),
                                'fldvatper' => Helpers::numberFormat(0,'insert'),
                                'hospital_department_id' => $departmentId,
                                'discount_mode' => $request->get('discountmode'),
                            ];
                        }
                    }
                    \App\PatBilling::insert($patbillings);
                    if ($billNumberGeneratedString != '') {
                        $depositdata = \App\PatBillDetail::where('fldencounterval', $encounter_id)->orderBy('fldid', 'DESC')->whereNotNull('fldcurdeposit')->get();

                        if (is_countable($depositdata) && count($depositdata)) {
                            $currentdeposit = $depositdata[0]->fldcurdeposit;
                        } else {
                            $currentdeposit = 0;
                        }
                        $taxtotal = $taxtotal ?: 0;
                        $discounttotal = $discounttotal ?: 0;
                        $chargedamt = $request->get('sub_total') + $taxtotal - $request->get('discountamt');
                        $insertDataPatDetail = [
                            'fldencounterval' => $encounter_id,
                            'flditemamt' => Helpers::numberFormat($request->get('sub_total'),'insert'),
                            'fldtaxamt' => Helpers::numberFormat($request->get('tax_amt'),'insert'),
                            'flddiscountamt' => Helpers::numberFormat($request->get('discountamt'),'insert'),
                            'fldreceivedamt' => Helpers::numberFormat(0,'insert'),
                            'fldchargedamt' => Helpers::numberFormat($chargedamt,'insert'),
                            'fldbilltype' => 'Credit',
                            'flduserid' => Auth::guard('admin_frontend')->user()->flduserid,
                            'fldtime' => date("Y-m-d H:i:s"),
                            'fldbillno' => $billNumberGeneratedString,
                            'fldchequeno' => $request->get('cheque_number'),
                            'fldbankname' => $request->get('bankname'),
                            'fldbill' => 'Invoice',
                            'fldcomp' => $computer,
                            'fldsave' => 1,
                            'remarks' => $request->get('fldremark'),
                            'xyz' => 0,
                            'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession(),
                            'tblexpecteddate' => $request->get('expecteddate') . " {date('H:i:s)}",
                            'flddiscountgroup' => $request->get('discountmode'),
                            'fldcurdeposit' => Helpers::numberFormat(abs($currentdeposit - $chargedamt),'insert'),
                            'fldprevdeposit' => Helpers::numberFormat($currentdeposit,'insert'),
                        ];
                        $tempPatBillData = \App\PatBillDetail::create($insertDataPatDetail);

                        $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $encounter_id)->first();

                        $flddepartment = null;
                        if ($encounterData->fldcurrlocat) {
                            $chkbed = Departmentbed::where('fldbed', $encounterData->fldcurrlocat)->first();
                            if ($chkbed) {
                                $flddepartment = $chkbed->flddept;
                            } else {
                                $chkdepart = Department::where('flddept', $encounterData->fldcurrlocat)->first();
                                if ($chkdepart) {
                                    $flddepartment = $chkdepart->flddept;
                                }
                            }
                        }

                        $deptRevenueData = [
                            'pat_details_id' => $tempPatBillData->fldid,
                            'fldencounterval' => $encounter_id,
                            'fldbillno' => $billNumberGeneratedString,
                            'flditemamt' => Helpers::numberFormat($request->get('sub_total'),'insert'),
                            'fldtaxamt' => Helpers::numberFormat($request->get('tax_amt'),'insert'),
                            'fldtaxgroup' => NULL,
                            'flddiscountamt' => Helpers::numberFormat($request->get('discountamt'),'insert'),
                            'flddiscountgroup' => $request->get('discountmode'),
                            'fldchargedamt' => Helpers::numberFormat($request->get('receive_amt'),'insert'),
                            'fldreceivedamt' => Helpers::numberFormat(0,'insert'),
                            'tblreason' => NULL,
                            'form_type' => 'Pharmacy Credit Billing',
                            'hospital_department_id' => $departmentId,
                            "location" => $encounterData->fldcurrlocat,
                            'bill_type' => 'Credit',
                            'xyz' => 0,
                            'flddepartment' => $flddepartment
                        ];
                        DepartmentRevenue::insert($deptRevenueData);
                        $encounterDetail = Encounter::select('fldcashcredit')->where('fldencounterval', $encounter_id)->first();


                        if ($fldremark) {
                            \App\Dispenseremark::insert([
                                'fldencounterval' => $encounter_id,
                                'fldbillno' => $billNumberGeneratedString,
                                'fldtime' => $time,
                                'fldremark' => $fldremark,
                                'hospital_department_id' => $departmentId,
                            ]);
                        }
                    }
                } else {

                    foreach ($medicines as $key => $medicine) {
                        $currentStock = isset($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldqty : 0;
                        if ($currentStock > 0 && ($currentStock - $medicine->fldqtydisp) >= 0) {

                            $discount = '0';
                            $itemrate = ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldsellpr : 0;
                            if ($discountpercentage)
                                $discount = ($itemrate * $medicine->fldqtydisp * $discountpercentage) / 100;
                            else
                                $discount = $medicine->flddiscamt;

                            \App\PatDosing::where('fldid', $medicine->fldid)->update([
                                'fldlevel' => 'Dispensed',
                                'fldendtime' => $time,
                                'fldsave_order' => '1',
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'xyz' => '0',
                            ]);
                            $updatedvalue = ($medicine->medicineBySetting->fldqty) - ($medicine->fldqtydisp);
                            \App\Entry::where([
                                'fldstockno' => $medicine->medicineBySetting->fldstockno
                            ])->update([
                                'fldqty' => $updatedvalue,
                                'fldsav' => '1',
                                'xyz' => '0',
                            ]);

                            $tax = (($itemrate * $medicine->fldqtydisp) - $discount) * ($medicine->fldtaxper / 100);
                            $discount = $discount;
                            $taxtotal += $tax;
                            $discounttotal += $discount;
                            $itemamount = ($itemrate * $medicine->fldqtydisp) + $tax - $discount;
                            $itemtotal += $itemrate * $medicine->fldqtydisp;
                            $chargedAmount += $itemamount;
                            $patbillings[] = [
                                'fldencounterval' => $encounter_id,
                                'fldbillingmode' => $request->get('fldbillingmode'),
                                'flditemtype' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldcategory : '',
                                'flditemno' => ($medicine->medicineBySetting) ? $medicine->medicineBySetting->fldstockno : NULL,
                                'flditemname' => $medicine->flditem,
                                'flditemrate' => Helpers::numberFormat($itemrate,'insert'),
                                'flditemqty' => $medicine->fldqtydisp,
                                'fldtaxper' => $medicine->fldtaxper,
                                'flddiscper' => $discountpercentage ? $medicine->flddiscper :'',
                                'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
                                'flddiscamt' => Helpers::numberFormat($discount,'insert'),
                                'fldditemamt' => Helpers::numberFormat($itemamount,'insert'),
                                'fldopip' => $opip,
                                'fldorduserid' => $userid,
                                'fldordtime' => $time,
                                'fldordcomp' => $computer,
                                'flduserid' => $userid,
                                'fldtime' => $time,
                                'fldcomp' => $computer,
                                'fldsave' => '1',
                                'fldbillno' => $billNumberGeneratedString,
                                'fldparent' => $medicine->fldid,
                                'fldprint' => '0',
                                'fldstatus' => 'Cleared',
                                'fldalert' => '1',
                                'fldtarget' => NULL,
                                'fldpayto' => NULL,
                                'fldrefer' => NULL,
                                'fldreason' => NULL,
                                'fldretbill' => NULL,
                                'fldretqty' => 0,
                                'fldsample' => 'Waiting',
                                'xyz' => '0',
                                'fldvatamt' => Helpers::numberFormat(0,'insert'),
                                'fldvatper' => 0,
                                'hospital_department_id' => $departmentId,
                                'discount_mode' => $request->get('discountmode'),
                            ];
                        }
                    }
                    \App\PatBilling::insert($patbillings);
                    if ($billNumberGeneratedString != '' && $patbillings) {
                        $depositdata = \App\PatBillDetail::where('fldencounterval', $encounter_id)->orderBy('fldid', 'DESC')->whereNotNull('fldcurdeposit')->get();
                        if (is_countable($depositdata) and count($depositdata)) {
                            $currentdeposit = $depositdata[0]->fldcurdeposit;
                        } else {
                            $currentdeposit = 0;
                        }
                        $chargedamt = $request->get('sub_total') + $taxtotal - $request->get('discountamt');
                        $patDetailsData = \App\PatBillDetail::create([
                            'fldencounterval' => $encounter_id,
                            'fldbillno' => $billNumberGeneratedString,
                            'flditemamt' => Helpers::numberFormat($request->get('sub_total'),'insert'),
                            'fldtaxamt' => Helpers::numberFormat($request->get('tax_amt'),'insert'),
                            'flddiscountamt' => Helpers::numberFormat($request->get('discountamt'),'insert'),
                            'fldreceivedamt' => Helpers::numberFormat($request->get('receive_amt'),'insert'),
                            'fldchargedamt' => Helpers::numberFormat($request->get('receive_amt'),'insert'),
                            'fldbilltype' => 'Cash',
                            'flduserid' => $userid,
                            'fldchequeno' => $request->get('cheque_number'),
                            'fldbankname' => $request->get('bankname'),
                            'fldtime' => $time,
                            'fldcomp' => $computer,
                            'fldsave' => '1',
                            'remarks' => $request->get('fldremark'),
                            'xyz' => '0',
                            'hospital_department_id' => $departmentId,
                            'fldbill' => 'Invoice',
                            'flddiscountgroup' => $request->get('discountmode'),
                            'fldcurdeposit' => Helpers::numberFormat(0,'insert'),
                            'fldprevdeposit' => Helpers::numberFormat(0,'insert'),
                            'tblexpecteddate' => $request->get('expecteddate') . " {date('H:i:s)}",
                        ]);

                        $encounterData = Encounter::select('fldcurrlocat')->where('fldencounterval', $encounter_id)->first();

                        $patDetailsData['location'] = $encounterData->fldcurrlocat;
                        $patDetailsData['bill_type'] = 'Cash';
                        \App\Services\DepartmentRevenueService::inserRevenueOrReturn($patDetailsData, 'Pharmacy Billing');

                        \App\PatBillCount::create(['fldbillno' => $billNumberGeneratedString, 'fldcount' => 1]);

                        if ($fldremark) {
                            \App\Dispenseremark::insert([
                                'fldencounterval' => $encounter_id,
                                'fldbillno' => $billNumberGeneratedString,
                                'fldtime' => $time,
                                'fldremark' => $fldremark,
                                'hospital_department_id' => $departmentId,
                            ]);
                        }
                    }
                }
                Options::update('disable_dispensing', 0);
                \DB::commit();
            } else {
                Options::update('disable_dispensing', 0);
                return redirect()->back()->with('error_message', "Please reorder the medicines to create invoice");
            }

            if ($billNumberGeneratedString != '' && $patbillings) {
                $enpatient = Encounter::with('currentDepartment:flddept,fldcateg')->where('fldencounterval', $encounter_id)->first();

                $discount = Discount::where('fldtype', $enpatient->flddisctype)->first();
                if (isset($discount) and $discount->fldmode == 'FixedPercent') {
                    $meddiscount = $discount->fldpercent;
                    $surgicaldiscount = $discount->fldpercent;
                    $extradiscount = $discount->fldpercent;
                } elseif (isset($discount) and $discount->fldmode == 'CustomValues') {
                    $meddiscount = $discount->fldmedicine;
                    $surgicaldiscount = $discount->fldsurgical;
                    $extradiscount = $discount->fldextra;
                } else {
                    $meddiscount = 0;
                    $surgicaldiscount = 0;
                    $extradiscount = 0;
                }
                $encounterinfo = \App\Utils\Helpers::getPatientByEncounterId($encounter_id);
                $trans = Helpers::getTranslationForLabel(strpos($encounterinfo->fldcurrlocat, 'OPD'));
                $printIds = explode(',', $request->get('printIds'));
                $paymentmode = $request->get('payment_mode');
                $discountamount = $request->get('discountamt');
                $subtotal = $request->get('sub_total');
                $taxtotal = $request->get('tax_amt');
                $total = $subtotal - ($discountamount + $taxtotal);
                $dispensemedicine = PatBilling::where('fldbillno', $billNumberGeneratedString)
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldstatus', 'Cleared')
                    ->where('fldsave', '1')
                    ->get();
                Options::update('disable_dispensing', 0);
                return view('dispensar::pdf.dispense-print', compact('encounterinfo', 'medicines', 'receive_amt', 'trans', 'printIds', 'billNumberGeneratedString', 'time', 'paymentmode', 'discountamount', 'subtotal', 'taxtotal', 'discounttotal', 'total', 'meddiscount', 'surgicaldiscount', 'extradiscount', 'dispensemedicine'));
            } else {
                Options::update('disable_dispensing', 0);
                return redirect()->back()->with('error_message', "Medicine Out Of Stock");
            }
        } catch (\Exception $e) {
            Options::update('disable_dispensing', 0);
            \DB::rollBack();
            die('Someting went wrong. Please try again.');
        }
    }

    //yo chahi preview ko lagi
    public function displayInvoicePreview(Request $request)
    {
        $encounter_id = $request['encounter_id'];
        $data['enpatient'] = Encounter::where('fldencounterval', $request['encounter_id'])->with('patientInfo')->first();

        $data['patbillingDetails'] = $patbillingDetails =  []; //PatBillDetail::where('fldencounterval', $encounter_id)->orderby('fldid','DESC')->first();

        $data['invoice_title'] = 'Invoice';
        $getCategory = PatBilling::select('flditemtype')
            ->where('fldencounterval', $encounter_id)
            ->where('fldstatus', 'Punched')
            ->where('fldcomp', '=', Helpers::getCompName())
            ->groupBy('flditemtype')
            ->get();

        if ($getCategory) {
            foreach ($getCategory as $k => $billing) {
                $data['eachpatbilling'][$k]['category'] = $billing->flditemtype;

                $patbillData = PatBilling::where('flditemtype', $billing->flditemtype)
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldstatus', 'Punched')
                    ->get();
                $data['eachpatbilling'][$k]['details'] = $patbillData;
                $data['eachpatbilling'][$k]['total'] = $patbillData->sum('fldditemamt');
                $data['patbillingdetail'][$k] = PatbillDetail::where('fldtempbillno', $billing->fldtempbillno)
                    ->where('fldcomp', '=', Helpers::getCompName())->first();
            }

            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter_id)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', '=', Helpers::getCompName())
                ->first()->subtotal;
            $data['total'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldcomp', '=', Helpers::getCompName())
                ->where('fldstatus', 'Punched')
                ->sum('fldditemamt');
            $data['discount'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldcomp', '=', Helpers::getCompName())
                ->where('fldstatus', 'Punched')
                ->sum('flddiscamt');
            $data['tax'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldcomp', '=', Helpers::getCompName())
                ->where('fldstatus', 'Punched')
                ->sum('fldtaxamt');



            $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
                ->where('fldencounterval', $encounter_id)
                ->where('fldcomp', '=', Helpers::getCompName())
                ->orderBy('fldid', 'DESC')
                ->first();

            $data['previousDeposit'] = $previousDeposit->fldcurdeposit ?? 0;


        }
        $data['billCount'] = 0;
        $data['depositDetail'] = PatBillDetail::where('fldbillno', 'LIKE', '%DEP%')
            ->where('fldencounterval', $encounter_id)
            ->where('fldcomp', '=', Helpers::getCompName())
            ->get();
        $data['discount_mode'] = PatBilling::select('discount_mode')
            ->where('fldencounterval', $encounter_id)
            ->where('fldcomp', '=', Helpers::getCompName())
            ->where('fldstatus', 'Punched')
            ->first();

        $ref_doctor_pat  = PatBilling::with('referUserdetail')
            ->where('fldencounterval', $encounter_id)
            ->where('fldrefer', '!=', NULL)
            ->where('fldcomp', '=', Helpers::getCompName())
            ->first();
        if ($ref_doctor_pat) {
            $data['referable_doctor'] = (($ref_doctor_pat->fldrefer && $ref_doctor_pat->referUserdetail) ? $ref_doctor_pat->referUserdetail->fldfullname : '');
        } else {
            $ref_doctor_consult  = Consult::with('user')->where('fldencounterval', $encounter_id)->first();
            if ($ref_doctor_consult) {
                $data['referable_doctor'] = (($ref_doctor_consult->flduserid && $ref_doctor_consult->user) ? $ref_doctor_consult->user->fldfullname : '');
            }
        }

        $data['pagefrom'] = 'pharmacy';

        if (isset($request->billtype) && $request->billtype == 'discharge') {
            if ($request->print_preview === 'detailed') {
                return view('billing::invoice-detailed', $data);
            }
            return view('billing::invoice.discharge-invoice', $data);
        } else {
            if ($request->print_preview === 'detailed') {
                return view('billing::invoice-detailed', $data);
            }

            return view('billing::invoice.discharge-invoice', $data);
        }

    }

    private function updateautobillingbedcharge($fldid, $qty)
    {
        $patbilling = PatBilling::where('fldid', $fldid)->first();

        $totalAmount = $qty * $patbilling->flditemrate;
        $totalDiscount = ($totalAmount * $patbilling->flddiscper) / 100;
        $tax = (($totalAmount - $totalDiscount) * $patbilling->fldtaxper) / 100;
        $updateData = [
            'flditemqty' => $qty,
            'fldditemamt' => Helpers::numberFormat(($totalAmount + $tax - $totalDiscount),'insert'),
            'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
            'flddiscamt' => Helpers::numberFormat($totalDiscount,'insert'),
        ];
        $patbilling->update($updateData);
    }
}
