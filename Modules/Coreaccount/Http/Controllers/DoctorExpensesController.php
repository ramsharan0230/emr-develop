<?php

namespace Modules\Coreaccount\Http\Controllers;

use App\AccountLedger;
use App\AutoId;
use App\CogentUsers;
use App\HospitalDepartmentUsers;
use App\LedgerUserMap;
use App\PatBillingShare;
use App\TempDoctorExpenses;
use App\TransactionMaster;
use App\Utils\Options;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Session;

class DoctorExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        $data['doctors'] = CogentUsers::whereHas('user_ledger')->paginate(50);
        return view('coreaccount::doctor-fraction.doctor-fraction-list', $data);
    }

    /**
     * @return Application|Factory|View
     */
    public function mapDoctorLedger()
    {
        $data['ledgers'] = AccountLedger::all();
        $data['doctors'] = CogentUsers::whereDoesntHave('user_ledger')->get();

        return view('coreaccount::doctor-fraction.doctor-fraction-map', $data);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'doctor' => 'required',
            'ledger' => 'required',
        ]);
        try {
            $doctorArray = [
                'user_id' => $request->doctor,
                'ledger_id' => $request->ledger
            ];

            LedgerUserMap::insert($doctorArray);
            Session::flash('success', 'Records updated successfully.');
            return redirect()->route('transaction.map.doctor');
        } catch (Exception $exception) {
            Session::flash('error', __('messages.error'));
            return redirect()->route('transaction.map.doctor');
        }
    }

    public function insertTemp(Request $request, $id)
    {
        $startTime = Carbon::parse($request->from_date)->setTime(00, 00, 00);
        $endTime = Carbon::parse($request->to_date)->setTime(23, 59, 59);

        $patbillData = PatBillingShare::where('user_id', $id)
            ->where('created_at', '>=', $startTime)
            ->where('created_at', '<=', $endTime)
            ->where('sync', 0)
            ->with(['user.user_ledger.user_ledger_map'])
            ->get();

        $totalShare = 0;

        $groupIDTax = AccountLedger::select('AccountNo', 'GroupId')->where('AccountId', Options::get('ledger_tax_doctor_fraction'))->first();
        foreach ($patbillData->chunk(100) as $patbills) {
            foreach ($patbills as $patbill) {
                $groupID = AccountLedger::select('GroupId')->where('AccountNo', $patbill->user->user_ledger->user_ledger_map->AccountNo)->first();
                $insertData = [
                    'pat_bill_share_id' => $patbill->id,
                    'AccountNo' => $patbill->user->user_ledger->user_ledger_map->AccountNo,
                    'GroupId' => $groupID->GroupId,
                    'BranchId' => null,
                    'VoucherNo' => null,
                    'VoucherCode' => null,
                    'TranAmount' => ($patbill->share - $patbill->tax_amt),
                    'TranDate' => $patbill->created_at,
                    'TranDateNep' => null,
                    'BillNo' => null,
                    'ChequeNo' => null,
                    'Narration' => "Doctor share",
                    'Remarks' => 'Doctor share',
                    'sync' => 0,
                    'user_id' => $patbill->user_id,
                    'share_type' => 'share',
                ];

                if ($patbill->tax_amt != 0) {
                    $insertDataTax = [
                        'pat_bill_share_id' => $patbill->id,
                        'AccountNo' => $groupIDTax->AccountNo,
                        'GroupId' => $groupIDTax->GroupId,
                        'BranchId' => null,
                        'VoucherNo' => null,
                        'VoucherCode' => null,
                        'TranAmount' => $patbill->tax_amt,
                        'TranDate' => $patbill->created_at,
                        'TranDateNep' => null,
                        'BillNo' => null,
                        'ChequeNo' => null,
                        'Narration' => "Doctor Tax",
                        'Remarks' => 'Doctor tax',
                        'sync' => 0,
                        'user_id' => $patbill->user_id,
                        'share_type' => 'tax',
                    ];
                    TempDoctorExpenses::create($insertDataTax);
                }

                $patbill->sync = 1;
                $patbill->save();

                TempDoctorExpenses::create($insertData);

            }
        }

        return response()->json([
            "success" => __('messages.data_success', ['name' => 'Sync'])
        ]);
    }

    public function syncTransaction($user_id)
    {
        $data['accounts'] = AccountLedger::all();
        $data['ledgers'] = $ledgers = TempDoctorExpenses::where('user_id', $user_id)->where('sync', 0)->with('accountLedger')->get();
        $data['doctor_id'] = $user_id;

        if (is_countable($ledgers) && count($ledgers) < 1) {
            return redirect()->back()->with('error_message', "No data to sync.");
        }

        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')
                ->where('user_id', $user->id)
                ->distinct('hospital_department_id')
                ->with(['departmentData', 'departmentData.branchData'])
                ->get();
        } else {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')
                ->distinct('hospital_department_id')
                ->with(['departmentData', 'departmentData.branchData'])
                ->get();
        }

        return view('coreaccount::doctor-fraction.sync-transaction', $data);
    }

    public function syncTransactionAll()
    {
        $data['accounts'] = AccountLedger::all();
        $data['ledgers'] = $ledgers = TempDoctorExpenses::where('sync', 0)->with('accountLedger')->get();

        if (is_countable($ledgers) && count($ledgers) < 1) {
            return redirect()->back()->with('error_message', "No data to sync.");
        }

        $user = Auth::guard('admin_frontend')->user();
        if (count(Auth::guard('admin_frontend')->user()->user_is_superadmin) == 0) {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')
                ->where('user_id', $user->id)
                ->distinct('hospital_department_id')
                ->with(['departmentData', 'departmentData.branchData'])
                ->get();
        } else {
            $data['hospital_department'] = HospitalDepartmentUsers::select('hospital_department_id')
                ->distinct('hospital_department_id')
                ->with(['departmentData', 'departmentData.branchData'])
                ->get();
        }

        return view('coreaccount::doctor-fraction.sync-transaction-all', $data);
    }

    public function insertTransactionMaster(Request $request)
    {
        $validated = $request->validate([
            'accountId.*' => 'required',
            'voucher_entry.*' => 'required',
            'amount.*' => 'required',
        ]);

        if (abs((int)$request->amount[0]) != (TempDoctorExpenses::select('TranAmount')->whereIn('id', $request->share_tax_id)->pluck('TranAmount'))->sum()) {
            return redirect()->back()->with('error_message', "Transaction amount mismatch");
        }

        try {
            DB::beginTransaction();
            $newAutoIdForTransaction = AutoId::where('fldtype', 'transactionVoucher')->first();
            if ($newAutoIdForTransaction) {
                $newTransactionNumber = $newAutoIdForTransaction->fldvalue;
                $newTransactionNumberUpdate = $newAutoIdForTransaction->fldvalue + 1;
                AutoId::where('fldtype', 'transactionVoucher')->where('fldtype', 'transactionVoucher')->update(['fldvalue' => $newTransactionNumberUpdate]);
            } else {
                $newTransactionNumber = 1;
                $newTransactionNumberUpdate = 1;
                AutoId::create(['fldtype' => 'transactionVoucher', 'fldvalue' => $newTransactionNumberUpdate]);
            }
            $voucherInitial = '';

            $voucher_entry = $request->voucher_entry[0];
            if ($request->voucher_entry[0] === "Journal") {
                $voucherInitial = 'JV-';
            }
            if ($request->voucher_entry[0] === "Payment") {
                $voucherInitial = 'PV-';
            }
            if ($request->voucher_entry[0] === "Receipt") {
                $voucherInitial = 'RV-';
            }
            if ($request->voucher_entry[0] === "Contra") {
                $voucherInitial = 'CV-';
            }

            /**insert entry except form temp table*/
            $insertDataNonTemp['VoucherNo'] = $voucherInitial . $newTransactionNumber;

            $groupID = AccountLedger::select('GroupId')->where('AccountNo', $request->accountId[0])->first();

            $insertDataNonTemp['AccountNo'] = $request->accountId[0];
            $insertDataNonTemp['GroupId'] = $groupID->GroupId;
            $insertDataNonTemp['VoucherCode'] = $voucher_entry;
            $insertDataNonTemp['TranAmount'] = $request->amount[0];
            $insertDataNonTemp['TranDate'] = isset($request->TranDate) ? $request->TranDate : date('Y-m-d');
            $insertDataNonTemp['ChequeNo'] = $request->cheque_number;
            $insertDataNonTemp['Narration'] = $request->Narration[0];
            $insertDataNonTemp['Remarks'] = $request->remarks[0];
            $insertDataNonTemp['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
            $insertDataNonTemp['CreatedDate'] = date("Y-m-d H:i:s");

            TransactionMaster::create($insertDataNonTemp);
            /**insert entry except form temp table*/
            $insertData = [];

            for ($i = 0, $iMax = count($request->share_tax_id); $i < $iMax; $i++) {

                $insertData['VoucherNo'] = $voucherInitial . $newTransactionNumber;

                $tempData = TempDoctorExpenses::where('id', $request->share_tax_id[$i])->first();
                $groupID = AccountLedger::select('GroupId')->where('AccountNo', $tempData->AccountNo)->first();

                $insertData['AccountNo'] = $tempData->AccountNo;
                $insertData['GroupId'] = $groupID->GroupId;
                $insertData['VoucherCode'] = $voucher_entry;
                $insertData['TranAmount'] = $tempData->TranAmount;
                /**because all transactions are credit*/
                $insertData['TranDate'] = isset($tempData->TranDate) ? $tempData->TranDate : date('Y-m-d');
                $insertData['ChequeNo'] = $tempData->cheque_number;
                $insertData['Narration'] = $tempData->Narration;
                $insertData['Remarks'] = $request->remarks[0];
                $insertData['CreatedBy'] = Auth::guard('admin_frontend')->user()->flduserid ?? 0;
                $insertData['CreatedDate'] = date("Y-m-d H:i:s");

                TransactionMaster::create($insertData);

                if (isset($request->share_tax_id[$i])) {
                    TempDoctorExpenses::where('id', $request->share_tax_id[$i])->update(['sync' => 1]);
                }
            }
            DB::commit();
            return redirect()->route('transaction.view.doctor')->with('success_message', __('messages.success', ['name' => 'Transaction']));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_message', __('messages.error'));
        }
    }

    public function syncAllDoctorsData(Request $request)
    {
        $doctorIds = CogentUsers::whereHas('user_ledger')->pluck('id');
        $startTime = Carbon::parse($request->from_date)->setTime(00, 00, 00);
        $endTime = Carbon::parse($request->to_date)->setTime(23, 59, 59);

        $patbillData = PatBillingShare::whereIn('user_id', $doctorIds)
            ->where('created_at', '>=', $startTime)
            ->where('created_at', '<=', $endTime)
            ->where('sync', 0)
            ->with(['user.user_ledger.user_ledger_map'])
            ->get();

        try {
            $groupIDTax = AccountLedger::select('AccountNo', 'GroupId')->where('AccountId', Options::get('ledger_tax_doctor_fraction'))->first();
            foreach ($patbillData->chunk(100) as $patbills) {
                foreach ($patbills as $patbill) {
                    $groupID = AccountLedger::select('GroupId')->where('AccountNo', $patbill->user->user_ledger->user_ledger_map->AccountNo)->first();
                    $insertData = [
                        'pat_bill_share_id' => $patbill->id,
                        'AccountNo' => $patbill->user->user_ledger->user_ledger_map->AccountNo,
                        'GroupId' => $groupID->GroupId,
                        'BranchId' => null,
                        'VoucherNo' => null,
                        'VoucherCode' => null,
                        'TranAmount' => ($patbill->share - $patbill->tax_amt),
                        'TranDate' => $patbill->created_at,
                        'TranDateNep' => null,
                        'BillNo' => null,
                        'ChequeNo' => null,
                        'Narration' => "Doctor share",
                        'Remarks' => 'Doctor share',
                        'sync' => 0,
                        'user_id' => $patbill->user_id,
                        'share_type' => 'share',
                    ];

                    if ($patbill->tax_amt != 0) {
                        $insertDataTax = [
                            'pat_bill_share_id' => $patbill->id,
                            'AccountNo' => $groupIDTax->AccountNo,
                            'GroupId' => $groupIDTax->GroupId,
                            'BranchId' => null,
                            'VoucherNo' => null,
                            'VoucherCode' => null,
                            'TranAmount' => $patbill->tax_amt,
                            'TranDate' => $patbill->created_at,
                            'TranDateNep' => null,
                            'BillNo' => null,
                            'ChequeNo' => null,
                            'Narration' => "Doctor Tax",
                            'Remarks' => 'Doctor tax',
                            'sync' => 0,
                            'user_id' => $patbill->user_id,
                            'share_type' => 'tax',
                        ];
                        TempDoctorExpenses::create($insertDataTax);
                    }

                    $patbill->sync = 1;
                    $patbill->save();

                    TempDoctorExpenses::create($insertData);

                }
            }

            return response()->json([
                "success" => __('messages.data_success', ['name' => 'Sync'])
            ]);
        } catch (Exception $exception) {

        }

    }
}
