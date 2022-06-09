<?php

namespace Modules\Reports\Http\Controllers;

use App\Encounter;
use App\HospitalDepartment;
use App\PatientInfo;
use App\Utils\Helpers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\PatBillDetail;
use App\HospitalDepartmentUsers;
use App\PatBilling;
use Auth;
use Carbon\Carbon;
use App\Patientcreditcolor;
use DB;

class PatientLedgerReportController extends Controller
{
    public function index(Request $request) {
        return view('reports::patient-ledger-report.index');
    }

    public function searchPatientEncounter(Request  $request){
        try{
            $encounter_list = Encounter::select('fldencounterval','fldregdate')->where('fldpatientval',$request->key)->orderBy('fldregdate','desc')->get();
            $patient_info = PatientInfo::where('fldpatientval',$request->key)->first();

            foreach ($encounter_list as $list){
                $totalToRefund =0;
                $totalPharmacyReturn = 0;
                $totalServiceReturn = 0;
                $totalCredit = 0;
                $data = 0;
                $date = Carbon::parse($list->fldregdate)->format('Y-m-d');
                $nepali_date = Helpers::dateEngToNepdash($date)->full_date;
                $nepali_date = Helpers::changeNepaliDateFormat($nepali_date);
                $list->nepali_date = $nepali_date;
                $totalPharmacy = PatBillDetail::where('fldencounterval', $list->fldencounterval)
                    ->where('fldbillno', 'LIKE', 'PHM%')
                    ->where(function($query) {
                        $query->where('payment_mode','Cash')
                            ->orWhere('payment_mode', 'Card');
                    })

                    ->where('fldsave', 1)->sum('fldreceivedamt');
                $totalService = PatBillDetail::where('fldencounterval', $list->fldencounterval)
                    ->where(function($query) {
                        $query->where('fldbillno', 'LIKE', 'CAS%')
                            ->orWhere('fldbillno', 'LIKE', 'REG%');
                    })
                    ->where(function($query) {
                        $query->where('payment_mode','Cash')
                            ->orWhere('payment_mode', 'Card');
                    })
                    ->where('fldsave', 1)->sum('fldreceivedamt');
                $totalReturn = PatBillDetail::where('fldencounterval', $list->fldencounterval)
                    ->where('fldbillno', 'LIKE', 'RET%')
                    ->get();
                $totalPharmacyReturn = PatBilling::
                where('fldencounterval', $list->fldencounterval)->
                where('fldbillno', 'LIKE', 'RET%')
                    ->where('fldretbill', 'LIKE', 'PHM%')
                    ->sum('fldditemamt');
                $totalServiceReturn = PatBilling::
                where('fldencounterval', $list->fldencounterval)->
                where('fldbillno', 'LIKE', 'RET%')
                    ->where('fldretbill', 'LIKE', 'CAS%')
                    ->sum('fldditemamt');

                $list->totalPharmacy =  \App\Utils\Helpers::numberFormat($totalPharmacy + $totalPharmacyReturn ) ?? '0';
                $totalCasCredit = PatBillDetail::where('fldencounterval', $list->fldencounterval)
                    ->where('payment_mode','Credit')
                    ->where(function($query) {
                        $query->where('fldbillno', 'LIKE', 'CAS%')
                            ->orWhere('fldbillno', 'LIKE', 'REG%')
                            ->orWhere('fldbillno', 'LIKE', 'CRE%')
                            ->orWhere('fldbillno', 'LIKE', 'PHM%');
                    })
                    ->where('fldsave', 1)->get();
                foreach ($totalCasCredit as $credit){
                    if($credit->fldcurdeposit>0){
                        $totalToRefund += $credit->fldcurdeposit;
                    }else{
                        $totalCredit +=$credit->fldcurdeposit;
                    }
                }
                $totalCreditClearance = PatBillDetail::where('fldencounterval', $list->fldencounterval)
                    ->where('fldbillno', 'LIKE', 'CRE%')
                    ->where('fldpayitemname','Credit Clearance')
                    ->where('fldsave', 1)->sum('fldcurdeposit');
                $depositCreditClearance =PatBillDetail::where('fldencounterval', $list->fldencounterval)
                    ->where('fldbillno', 'LIKE', 'DEP%')
                    ->where('fldpayitemname','Credit Clearance')
                    ->where('fldsave', 1)->sum('fldreceivedamt');
                $list->totalReturn = $totalReturn;
                $list->totalService = \App\Utils\Helpers::numberFormat($totalService + $totalServiceReturn  ) ?? '0';
                $list->totalServiceReturn = \App\Utils\Helpers::numberFormat($totalServiceReturn   ) ?? '0';
                $list->totalPharmacyReturn= \App\Utils\Helpers::numberFormat($totalPharmacyReturn   ) ?? '0';
                $list->totalCredit = \App\Utils\Helpers::numberFormat($totalCredit + $depositCreditClearance) ?? '0';
                $list->totalCreditClearance = \App\Utils\Helpers::numberFormat($totalCreditClearance + $depositCreditClearance) ?? '0';
                $totalDeposit = PatBillDetail::where('fldencounterval', $list->fldencounterval)->where('fldbillno', 'LIKE', 'DEP%')
                    ->where('fldpayitemname','<>','Deposit Refund')
                    ->where('fldpayitemname','<>','Pharmacy Deposit Refund')
                    ->where('fldpayitemname','<>','Credit Clearance')
                    ->sum('fldreceivedamt');
                $list->totalDeposit = \App\Utils\Helpers::numberFormat($totalDeposit) ?? '0';
                $totalDepositRefund = PatBillDetail::where('fldencounterval', $list->fldencounterval)->where('fldbillno', 'LIKE', 'DEP%')
                    ->where(function($query) {
                        $query->where('fldpayitemname','Deposit Refund')
                            ->orWhere('fldpayitemname','Pharmacy Deposit Refund');
                    })
                    ->sum('fldreceivedamt');
                $list->totalDepositRefund = \App\Utils\Helpers::numberFormat($totalDepositRefund) ?? '0';
                $list->totalToRefund = \App\Utils\Helpers::numberFormat($totalToRefund + $totalDepositRefund) ?? '0';
            }
            return response()->json([
                'status' => true,
                'encounter_data' => $encounter_list,
                'patient_data' => $patient_info,
                'message' => 'Patient info listed successful.',

            ], 200);
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'data' => null,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getBillData(Request  $request){
        try {
            $total = 0;
            $datas = PatBilling::
            where('fldsave', 1)
                ->where('fldencounterval', $request->encounterval)
                ->where('fldbillno', $request->billno)
                ->get();
            $html = '';


            $html .= '<table id="patient-ledger-bill-table" data-show-columns="true"
                           data-search="true"
                           data-pagination="true"
                           data-resizable="true"
                           data-show-toggle="true"
                              data-search-align="left"
                           >
                        <thead class="thead-light">
                             <th>SN.</th>
                            <th>Bill no.</th>
                            <th>Original Bill no.</th>
                            <th>Item Name</th>
                            <th>Rate</th>
                            <th>Qty</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Total Amount</th>
                            <th>Received Amount</th>
                            <th>User</th>
                        </thead>
                        <tbody >';
            foreach ($datas as $i => $data) {
                $i = $i + 1;
                $html .= '<tr>
                            <td>' . $i . '</td>';
                $html .= '<td >' . $data->fldbillno . '</td>';
                if($data->fldretbill){
                    $html .= '<td >' . $data->fldretbill . '</td>';
                }else{
                    $html .= '<td >N/A</td>';
                }
                $html .= '  <td>' . $data->flditemname . '</td>';
                $html .= '  <td>' . \App\Utils\Helpers::numberFormat($data->flditemrate) . '</td>';
                $html .= '  <td>' . $data->flditemqty . '</td>';
                $html .= '  <td>' . \App\Utils\Helpers::numberFormat($data->flddiscamt) . '</td>';
                $html .= '  <td>' . \App\Utils\Helpers::numberFormat($data->fldtaxamt) . '</td>';
                $html .= '  <td>' . \App\Utils\Helpers::numberFormat($data->flditemrate * $data->flditemqty) . '</td>';
                $html .= '  <td>' . $data->fldditemamt . '</td>';
                $html .= '  <td>' . $data->flduserid . '</td>';
                $html .= '</tr>';
                $total += $data->flditemrate * $data->flditemqty;
            }

            $html .= '</tbody></table>';
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html,
                    'total'=>\App\Utils\Helpers::numberFormat($total,'insert')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'status' => false,
                    'message'=>$e->getMessage()
                ]
            ]);
        }
    }

    public function getPatientData(Request  $request){
        try {
            $datas = PatBillDetail::select('fldencounterval', 'fldbillno', 'fldpayitemname', 'fldprevdeposit', 'flditemamt', 'flddiscountamt', 'fldreceivedamt', 'fldtaxamt', 'fldcurdeposit', 'fldcomp', 'payment_mode','flduserid')
                ->where('fldsave', 1)
                ->where('fldencounterval', $request->encounterval)
                ->get();
            $html = '';
            $html .='<table id="patient-ledger-table"
                            data-show-columns="true"
                           data-search="true"
                           data-pagination="true"
                           data-resizable="true"
                              data-search-align="left"
                           data-show-toggle="true">
                        <thead class="thead-light">
                             <th>SN.</th>
                            <th>Bill no.</th>
                            <th>Pay Item Name</th>
                            <th>Prev Deposit</th>
                            <th>ItemAmt</th>
                            <th>DiscAmt</th>
                            <th>TaxAmt</th>
                            <th>RecvAmt</th>
                            <th>CurrDeposit</th>
                            <th>Payment Mode</th>
                            <th>User</th>
                            <th>Department</th>
                        </thead>
                        <tbody >';
            foreach ($datas as $i=>$data) {
                $department = HospitalDepartment::where('fldcomp',$data->fldcomp)->first();
                $i = $i + 1;
                $html .= '<tr>
                            <td>' . $i . '</td>';
                if (strpos($data->fldbillno, 'DEP') !== false) {
                    $html .= '<td  data-value="'.$data->fldencounterval.'">' . $data->fldbillno . '</td>';
                }else{
                    $html .= '<td class="list-bill-detail" data-value="'.$data->fldencounterval.'">' . $data->fldbillno . '</td>';
                }
                $html .= '  <td>' . $data->fldpayitemname . '</td>';
                $html .= '  <td>' . \App\Utils\Helpers::numberFormat($data->fldprevdeposit) . '</td>';
                $html .= '  <td>' . \App\Utils\Helpers::numberFormat($data->flditemamt) . '</td>';
                $html .= '  <td>' . \App\Utils\Helpers::numberFormat($data->flddiscountamt) . '</td>';
                $html .= '  <td>' . \App\Utils\Helpers::numberFormat($data->fldtaxamt) . '</td>';
                $html .= '  <td>' . \App\Utils\Helpers::numberFormat($data->fldreceivedamt) . '</td>';
                $html .= '  <td>' . \App\Utils\Helpers::numberFormat($data->fldcurdeposit) . '</td>';
                $html .= '  <td>' . $data->payment_mode . '</td>';
                $html .= '  <td>' . $data->flduserid . '</td>';
                $html .= '  <td>' . $department->name . '</td>';

                $html .= '</tr>';
            }
            $html .=' </tbody></table>';
            return response()->json([
                'data' => [
                    'status' => true,
                    'html' => $html
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'status' => false,
                    'message'=>$e->getMessage()
                ]
            ]);
        }
    }

}
