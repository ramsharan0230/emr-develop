<?php

namespace Modules\Dispensar\Http\Controllers;

use App\Admissionrequest;
use App\Encounter;
use App\PatBillDetail;
use App\PatBilling;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;

class DepositCreditCleranceController extends Controller
{
    public function index(Request $request)
    {
        $encounter_id_session = Session::get('encounter_id_deposit_form');
        $data = [
            'enpatient' => [],
            'previousDeposit' => 0,
            'deposit_for' => ['Credit Clearance'],
            'departments' => \DB::table('tbldepartment')
                ->join('tbldepartmentbed', 'tbldepartment.flddept', '=', 'tbldepartmentbed.flddept')
                ->where('tbldepartment.fldcateg', 'Patient Ward')
                ->select('tbldepartment.flddept')
                ->groupBy('tbldepartment.flddept')
                ->get(),
            'admissions_requests' => Admissionrequest::where('fldstatus', 'unread')->get(),
        ];
        if ($request->has('encounter_id') || $encounter_id_session) {
            if ($request->has('encounter_id'))
                $encounter_id = $request->get('encounter_id');
            else
                $encounter_id = $encounter_id_session;

            session(['encounter_id_deposit_form' => $encounter_id]);

            $data['enpatient'] = Encounter::where('fldencounterval', $encounter_id)
                ->with(['patientInfo'])
                ->with(['consultant'])
                ->with(['patBill' => function ($query) {
                    return $query->select('fldencounterval', 'fldditemamt');
                }])
                ->with(['patBillDetails' => function ($query2) {
                    return $query2->select('fldencounterval', 'fldreceivedamt');
                }])
                ->first();

            $data['admissions'] = Encounter::where('fldencounterval', $encounter_id)->get();

            // $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
            //     ->where('fldencounterval', $encounter_id)
            //     ->orderBy('fldtime', 'DESC')
            //     ->first();

            // $previousDeposit = \App\PatBillDetail::where('fldencounterval', $encounter_id)->where('fldbillno', 'like', '%DEP%')->sum('flditemamt');
            // $previousDeposit -= \App\PatBillDetail::where('fldencounterval', $encounter_id)->where('fldbilltype', 'Credit')->where('fldbillno', 'not like', '%DEP%')->sum('flditemamt');

            // yo final bvk le bhaneko
            $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
                ->where('fldencounterval', $encounter_id)
                ->where('fldbilltype', 'Credit')
                ->orderBy('fldid', 'DESC')
                ->where('fldcomp',Helpers::getCompName())
                ->first();

            $data['expenses'] = PatBilling::select('fldid', 'fldtime', 'flditemtype', 'flditemname', 'flditemrate', 'fldtaxper', 'flddiscper', 'flditemqty', 'fldditemamt', 'fldbillno')
                ->where('fldencounterval', $encounter_id)
                ->whereNotNull('flditemtype')
                ->where('fldsave', 1)
                ->where('fldcomp',Helpers::getCompName())
                ->get();

            $data['invoices'] = PatBillDetail::select('fldid', 'fldtime', 'fldbillno', 'flditemamt', 'fldtaxamt', 'flddiscountamt', 'fldreceivedamt', 'fldcurdeposit', 'fldbilltype')
                ->where('fldencounterval', $encounter_id)
                ->where('fldcomp',Helpers::getCompName())
                ->get();

            $data['previousDeposit'] = $previousDeposit ?  $previousDeposit->fldcurdeposit : 0;
            $data['admissions_requests'] = Admissionrequest::where('fldstatus', 'unread')->get();
        }
        return view('dispensar::deposit-form-credit-clerance', $data);
    }

    public function reset()
    {
        Session::forget('encounter_id_deposit_form');
        return redirect()->route('deposit.credit');
    }

    public function saveDeposit(Request $request)
    {
        $encounterId = $request->get('encounterId');
        $chkAdmitted = Encounter::where('fldencounterval', $encounterId)->first();

        $deposit_for = $request->get('deposit_for');
        $received_amount = $request->get('received_amount');
        $payment_mode = $request->get('payment_mode');
        $expected_payment_date = $request->get('expected_payment_date');
        $cheque_number = $request->get('cheque_number');
        $other_reason = $request->get('other_reason');
        $bank_name = $request->get('bank_name');
        $office_name = $request->get('office_name');
        $payment_mode = $request->get('payment_mode');
        $fonepaylog_id = $request->get('fonepaylog_id');

        $datetime = date('Y-m-d H:i:s');
        $userid = Helpers::getCurrentUserName();
        $computer = Helpers::getCompName();
        $department = Helpers::getUserSelectedHospitalDepartmentIdSession();

        $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
            ->where([
                'fldencounterval' => $encounterId,
                'fldbilltype' => 'Credit',
            ])->where('fldcomp',Helpers::getCompName())->orderBy('fldid', 'DESC')
            ->first();
        $previousDeposit = $previousDeposit ? $previousDeposit->fldcurdeposit : 0;
        $currentDeposit = $previousDeposit + $received_amount;

        \DB::beginTransaction();
        try {
            $new_bill_number = Helpers::getNextAutoId('InvoiceNo', TRUE);
            $dateToday = \Carbon\Carbon::now();
            $year = \App\Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')->first();
            $billNumberGeneratedString = "DEP-{$year->fldname}-{$new_bill_number}" . Options::get('hospital_code');

            $encounterData = Encounter::select('fldcurrlocat', 'flddisctype')->where('fldencounterval', $encounterId)->first();
            $patbilldetail = [
                'fldencounterval' => $encounterId,
                'fldbillno' => $billNumberGeneratedString,
                'fldprevdeposit' => $previousDeposit,
                'flditemamt' => 0,
                'fldpayitemname' => $deposit_for,
                'fldtaxamt' => 0,
                'flddiscountamt' => 0,
                'flddiscountgroup' => $encounterData->flddisctype,
                'fldchargedamt' => 0,
                'fldreceivedamt' => $received_amount,
                'fldcurdeposit' => $currentDeposit,
                'fldbilltype' => 'Credit',
                'flduserid' => $userid,
                'fldtime' => $datetime,
                'fldcomp' => $computer,
                'fldbill' => "Invoice",
                'fldsave' => '1',
                'xyz' => '0',
                'hospital_department_id' => $department,
                'tblofficename' => $office_name,
                'tblexpecteddate' => $expected_payment_date,
                'payment_mode' => $payment_mode,
            ];
            if ($payment_mode == "cheque") {
                $patbilldetail['fldchequeno'] = $cheque_number;
                $patbilldetail['fldbankname'] = $bank_name;
            }elseif ($payment_mode == 'credit') {
                // $patbilldetail['fldcurdeposit'] = '-' . $fldchargedamt;
            }

            $patDetailsData = \App\PatBillDetail::create($patbilldetail);
            Helpers::logStack(["Pat bill detail created", "Event"], ['current_data' => $patbilldetail]);
            $patDetailsData['location'] = $encounterData->fldcurrlocat;

            #Fonepayment Log Update with Bill Number
            $fonepaylogdata['fldbillno'] = $billNumberGeneratedString;
            \App\Fonepaylog::where('id',$request->get('fonepaylog_id'))->update($fonepaylogdata);
            #End Fonepayment Log Update with Bill Number

            \App\Services\MaternalisedService::insertMaternalisedFiscalDeposit($encounterId,$billNumberGeneratedString,$payment_mode);

            \App\Services\DepartmentRevenueService::inserRevenueOrReturn($patDetailsData);
            $previousData = Encounter::where([['fldencounterval', $encounterId]])->first();
            Encounter::where([['fldencounterval', $encounterId]])->update(['fldcashdeposit' => $currentDeposit]);
            Helpers::logStack(["Encounter updated", "Event"], ['current_data' => ['fldcashdeposit' => $currentDeposit], 'previous_data' => $previousData]);

            \DB::commit();
            return response()->json([
                'status' => TRUE,
                'message' => 'Successfully saved deposit.',
                'data' => [
                    'previousDeposit' => $previousDeposit,
                    'billno' => $billNumberGeneratedString,
                    'receivedAmount' => $received_amount,
                ]
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            Helpers::logStack([$e->getMessage() . ' in deposite credit clearence save deposite', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => 'Failed to save deposit.',
            ]);
        }
    }

    public function printBill(Request $request)
    {
        $depositDetail = \App\PatBillDetail::select('fldbillno', 'fldtime', 'fldbilltype', 'fldprevdeposit', 'fldreceivedamt', 'fldcurdeposit', 'fldencounterval', 'fldpayitemname','payment_mode')
            ->where('fldbillno', $request->get('fldbillno'))
            ->first();

        // $depositBilling = \App\PatBilling::select('flditemname', 'flditemqty', 'flditemrate')
        //     ->where('fldbillno', $request->get('fldbillno'))
        //     ->get();

        $encounterData = \App\Encounter::select('fldpatientval', 'fldencounterval', 'fldbillingmode')
            ->with([
                'patientInfo:fldpatientval,fldptnamefir,fldmidname,fldptnamelast,fldptsex,fldptadddist,fldptaddvill,fldmunicipality,fldwardno,fldptbirday,fldptcontact',
            ])->where('fldencounterval', $depositDetail->fldencounterval)
            ->first();

        $billno = $request->get('fldbillno');
        $countdata = \App\PatBillCount::where('fldbillno', $billno)->pluck('fldcount')->first();
        $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != '') ? $countdata + 1 : 1;

        if (isset($countdata) and $countdata != '') {
            \App\PatBillCount::where('fldbillno', $billno)->update($updatedata);
            Helpers::logStack(["Pat bill count updated", "Event"], ['current_data' => $updatedata, 'previous_data' => $countdata]);
        } else {
            $insertdata['fldbillno'] = $billno;
            $insertdata['fldcount'] = $count;
            \App\PatBillCount::insert($insertdata);
            Helpers::logStack(["Pat bill count created", "Event"], ['current_data' => $insertdata]);
        }

        return view('dispensar::pdf.bill-print', [
            'depositDetail' => $depositDetail,
            'encounterinfo' => $encounterData,
            'billCount' => $count,
            // 'depositBilling' => $depositBilling
        ]);
    }
}
