<?php

namespace Modules\Billing\Http\Controllers;

use App\AutoId;
use App\Encounter;
use App\PatBilling;
use App\PatBillingShare;
use App\Services\PatBillingShareService;
use App\Utils\Helpers;
use App\Year;
use Carbon\Carbon;
use DB;
use App\Services\TpBillService;
use Illuminate\Http\Request;

class TpBillingController extends BillingController
{
    public function tpBill(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $computer = Helpers::getCompName();
            $patbilling = PatBilling::where(['fldencounterval' => $request->__encounter_id])
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->where('fldtempbillno', '=', null)
                ->where('fldstatus', 'Punched')->with('userPay')->get();
            // dd($patbilling);
            if (is_countable($patbilling) && count($patbilling) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items for tp bill.'
                ]);
            }

            // get claim code
            $dateToday = Carbon::now();
            $year = Year::whereRaw('"' . $dateToday . '" between `fldfirst` and `fldlast`')
                ->first();

//lock table until commit is done for multiple tp bill generated
            $billNumber = AutoId::where('fldtype', 'TempBillAutoId')->sharedLock()->first();
            $billNumberGeneratedString = "TP-$year->fldname-$billNumber->fldvalue";
            $new_bill_number = $billNumber->fldvalue + 1;

            $billNumber->update(['fldvalue' => $new_bill_number]);

            // echo $billNumberGeneratedString; exit;
            if ($patbilling) {
                foreach ($patbilling as $bill) {
                    $updateDataPatBilling = [
                        'fldtempbillno' => $billNumberGeneratedString,
                        'fldordcomp' => Helpers::getCompName(),
                        'flduserid' => \Auth::guard('admin_frontend')->user()->flduserid,
                        'fldtime' => date("Y-m-d H:i:s"),
                    ];
                    if(!is_null($bill->userPay) and $bill->userPay->category == 'Referable'){
                        $updateDataPatBilling['fldrefer'] = $request->referal_username;

                        $userid = \App\CogentUsers::select('id')->where('username',$request->referal_username)->first();
                        $pat_billing_share = new PatBillingShare();
                        $pat_billing_share->user_id = $userid->id;
                        $pat_billing_share->pat_billing_id = $bill->fldid;
                        $pat_billing_share->status = FALSE;
                        $pat_billing_share->type = 'Referable';
                        $pat_billing_share->save();

                        $share_update = PatBillingShareService::calculateIndividualShareNew($pat_billing_share->pat_billing_id);

                    }
                    $bill->update($updateDataPatBilling);
                }
            }

            /*insert tblpatbillings details in tbltpbills*/
                $tpbills = PatBilling::where(['fldencounterval' => $request->__encounter_id])
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })
                            ->where('fldcomp', $computer)
                            ->where('fldditemamt', '>=', 0)
                            ->where('fldtempbillno', '=', $billNumberGeneratedString)
                            ->where('fldstatus', 'Punched')
                            ->get();
                TpBillService::saveTpBillItems($tpbills);
            /*End tblpatbillings details in tbltpbills*/

            $encounter = $request->__encounter_id;

            $returnData['tableData'] = $this->itemHtml($encounter);
            /**check if temporary or credit item must be displayed*/

            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->where('fldtempbillno', '=', null)
                ->first()->subtotal;
            $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->where('fldtempbillno', '=', null)
                ->sum('flddiscamt');
            $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldcomp', $computer)
                ->where('fldditemamt', '>=', 0)
                ->where('fldtempbillno', '=', null)
                ->sum('fldtaxamt');

            $returnData['tp_bill_no'] = $billNumberGeneratedString;
            $returnData['invoice_html'] = $this->displayInvoiceBill($encounter,$returnData['tp_bill_no']);
            // dd($returnData);
            session(['last_tp_bill_number' => $billNumberGeneratedString]);
            DB::commit();
            return response()->json([
                'status' => TRUE,
                'message' => $returnData
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e->getMessage());
            return response([
                'success' => false,
            ]);
        }

    }

    public function displayInvoice(Request $request)
    {
        $computer = Helpers::getCompName();
        $data['last_tp_bill_number'] = $request->get('tp_bill_no');

        $data['enpatient'] = Encounter::where('fldencounterval', $request->encounter_id)->with('patientInfo')->first();

        $data['patbilling'] = PatBilling::where(['tblpatbilling.fldencounterval' => $request->encounter_id])
            ->select('tblpatbilling.*','sc.fldbillitem')
            ->join('tblservicecost as sc','sc.flditemname','tblpatbilling.flditemname')
            ->where(function ($query) {
                $query->orWhere('tblpatbilling.flditemtype', '!=', 'Surgicals')
                    ->orWhere('tblpatbilling.flditemtype', '!=', 'Medicines')
                    ->orWhere('tblpatbilling.flditemtype', '!=', 'Extra Items');
            })
            ->where('tblpatbilling.fldcomp', $computer)
            ->where('tblpatbilling.fldditemamt', '>=', 0)
            ->where('tblpatbilling.fldtempbillno', '=', $data['last_tp_bill_number'])
            ->where('tblpatbilling.fldstatus', 'Punched')
            ->with(['pat_billing_shares', 'pat_billing_shares.user'])
            ->get();

        $data['show_tp'] = '0';
        \Session::forget('last_tp_bill_number');

        return view('billing::invoice-tp', $data);
    }

    public function displayInvoiceBill($encounter,$billnumber)
    {
        $computer = Helpers::getCompName();
        $data['last_tp_bill_number'] = $billnumber;

        $data['enpatient'] = Encounter::where('fldencounterval', $encounter)->with('patientInfo')->first();

        $data['patbilling'] = PatBilling::where(['fldencounterval' => $encounter])
            ->where(function ($query) {
                $query->orWhere('flditemtype', '!=', 'Surgicals')
                    ->orWhere('flditemtype', '!=', 'Medicines')
                    ->orWhere('flditemtype', '!=', 'Extra Items');
            })
            ->where('fldcomp', $computer)
            ->where('fldditemamt', '>=', 0)
            ->where('fldtempbillno', '=', $data['last_tp_bill_number'])
            ->where('fldstatus', 'Punched')
            ->with(['pat_billing_shares', 'pat_billing_shares.user'])
            ->get();


        \Session::forget('last_tp_bill_number');
        $html = view('billing::invoice-tp-print', $data)->render();
        return $html;

    }

    public function listBill(Request $request)
    {
        //        select * from `tblpatbilling` where (`fldencounterval` = 'IP77-78-61') and (`flditemtype` != 'Surgicals' or `flditemtype` != 'Medicines' or `flditemtype` != 'Extra Items') and `fldditemamt` >= 0 and `fldtempbillno` = 'TP-78-79-43' and `fldstatus` = 'Punched'
        $requestData['toDate'] = date('Y-m-d') . ' 23:59:59';
        $requestData['fromDate'] = date('Y-m-d') . ' 00:00:00';
        $requestData['billno'] = '';
        $requestData['encounter'] = '';

        if ($request->has('eng_from_date') && $request->has('eng_to_date') && $request->get('eng_from_date') != '' && $request->get('eng_to_date') != '') {
            $requestData['fromDate'] = $request->eng_from_date . ' 00:00:00';
            $requestData['toDate'] = $request->eng_to_date . ' 23:59:59';
        }

        if ($request->has('billno')) {
            $requestData['billno'] = $request->billno;
        }
        if ($request->has('encounter')) {
            $requestData['encounter'] = $request->encounter;
        }
        $computer = Helpers::getCompName();
        $tpBillList = PatBilling::select('fldencounterval', 'fldtempbillno')
            ->where('fldditemamt', '>=', 0)

            ->where(function($query){
                $query->orWhere('fldstatus','like','Punched')
                    ->orWhere('fldstatus','like','Cleared');
            })

            ->where(function ($query) use ($requestData) {

                $query->where('fldtime', '>', $requestData['fromDate'])
                    ->where('fldtime', '<', $requestData['toDate'])
                    ->where('fldtempbillno','!=','');
                if ($requestData['billno'] != '') {
                    $query->where('fldtempbillno', 'like', $requestData['billno']);
                }

                if ($requestData['encounter'] != '') {
                    $query->where('fldencounterval', '=', $requestData['encounter']);
                }
            })
            ->groupBy('fldtempbillno')
            ->with(['encounter', 'encounter.patientInfo'])
            ->get();
//            dd($tpBillList);
        return view('billing::tp-bill.list', compact('tpBillList'));
    }

    public function listItems(Request $request)
    {
        $tpBillList = PatBilling::select('fldencounterval', 'fldtempbillno', 'flditemname', 'fldditemamt', 'flditemtype')
            ->where('fldditemamt', '>=', 0)
            ->where('fldstatus', 'like', 'Punched')
            ->where('fldtempbillno', 'like', $request->tpBillNumber)
            ->get();

        $html = '';
        $encounter = '';
        $billNumber = '';
        if ($tpBillList) {
            $encounter = $tpBillList->first()->fldencounterval;
            $billNumber = $tpBillList->first()->fldtempbillno;
            foreach ($tpBillList as $key => $list) {
                $html .= '<tr>';
                $html .= '<td>' . ++$key . '</td>';
                $html .= '<td>' . $list->flditemtype . '</td>';
                $html .= '<td>' . $list->flditemname . '</td>';
                $html .= '<td>' . Helpers::numberFormat($list->fldditemamt) . '</td>';
                $html .= '</tr>';
            }
        }

        return response()->json([
            'success' => true,
            'encounter' => $encounter,
            'billNumber' => $billNumber,
            'html' => $html
        ]);
    }

    public function printInvoice(Request $request)
    {

        $data['enpatient'] = Encounter::where('fldencounterval', $request->encounter_id)->with('patientInfo')->first();

        $data['invoice_title'] = 'Invoice';

        $cashbillQuery = PatBilling::select('fldid','fldencounterval', 'fldordtime','fldtempbillno', 'flditemname', 'fldditemamt', 'flditemtype', 'fldtime', 'flditemname', 'flditemqty', 'flditemrate', 'fldtaxamt','flditemno','flddiscamt')
            ->where('fldditemamt', '>=', 0)
            ->where(function($query){
                $query->orWhere('fldstatus','like','Punched')
                    ->orWhere('fldstatus','like','Cleared');
            })
            ->where('fldtempbillno', 'like', $request->tpBillNumber)
            ->with(['pat_billing_shares', 'pat_billing_shares.user'])
            ->with('serviceCost')
            ->get();

        $data['patbilling'] = $cashbillQuery;
        $data['tpitems'] = \App\Tpbill::where('fldencounterval',$request->encounter_id)->where('fldtempbillno',$request->tpBillNumber)->get();
        $data['show_tp'] = '1';
        return view('billing::invoice-tp', $data);
    }
}
