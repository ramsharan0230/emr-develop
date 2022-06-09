<?php

namespace Modules\Billing\Http\Controllers;

use App\Encounter;
use App\Http\Controllers\Controller;
use App\OtGroupSubCategory;
use App\PatBillCount;
use App\PatBillDetail;
use App\PatBilling;
use App\PatBillingShare;
use App\PatientCredential;
use App\PatientInfo;
use App\Patsubs;
use App\ServiceCost;
use App\UserShare;
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
use Illuminate\View\View;
use App\Services\TpBillService;
use App\Consult;

class BillingFunctionsController extends Controller
{
    protected $discountMode;
    private $ssf_patient_id;

    public function __construct(DiscountModeController $discountMode)
    {
        $this->discountMode = $discountMode;
    }
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function changeRate(Request $request)
    {
        $patbilling = PatBilling::where('fldid', $request->fldid)->first();
        $encounter = $patbilling->fldencounterval;
        $totalAmount = $request->new_rate * $patbilling->flditemqty;
        $totalDiscount = ($totalAmount * $patbilling->flddiscper) / 100;
        $tax = (($totalAmount - $totalDiscount) * $patbilling->fldtaxper) / 100;
        $updateData = [
            'flditemrate' => $request->new_rate,
            'fldditemamt' => Helpers::numberFormat(($totalAmount + $tax - $totalDiscount), 'insert'),
            'fldtaxamt' => Helpers::numberFormat($tax, 'insert'),
            'flddiscamt' => Helpers::numberFormat($totalDiscount,'insert'),
        ];
        $patbilling->update($updateData);
        $this->updateShareValue($request->fldid);
        $returnData['tableData'] = $this->itemHtml($encounter, $request->temp_checked);
        $returnData['tptableData'] = $this->tpItemHtml($encounter, $request->temp_checked);
        $computer = Helpers::getCompName();
        /**check if temporary or credit item must be displayed*/
        if ($request->temp_checked === 'no') {
            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->Where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
        } else {
            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
        }
        $returnData['depositAmount'] = Helpers::totalDepositAmountReceived($encounter);
        $returnData['tpAmount'] = Helpers::getTpAmount($encounter);
        $returnData['remainingAmount'] = Helpers::numberFormat($returnData['depositAmount']-$returnData['tpAmount'],'insert');
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
    public function changeQuantity(Request $request)
    {
        $patbilling = PatBilling::where('fldid', $request->fldid)->first();
        $encounter = $patbilling->fldencounterval;
        $totalAmount = $request->new_quantity * $patbilling->flditemrate;
        $totalDiscount = ($totalAmount * $patbilling->flddiscper) / 100;
        $tax = (($totalAmount - $totalDiscount) * $patbilling->fldtaxper) / 100;
        $updateData = [
            'flditemqty' => $request->new_quantity,
            'fldditemamt' => Helpers::numberFormat(($totalAmount + $tax - $totalDiscount),'insert'),
            'fldtaxamt' => Helpers::numberFormat($tax,'insert'),
            'flddiscamt' => Helpers::numberFormat($totalDiscount, 'insert'),
        ];

        $patbilling->update($updateData);

        #Update in tbltpbills with tempbillnumber
            if($patbilling->fldtempbillno !='' or !is_null($patbilling->fldtempbillno)){
                TpBillService::updateTpBillItems($patbilling);
            }
        #End Update in tbltpbills with tempbillnumber
        $this->updateShareValue($request->fldid);
        $computer = Helpers::getCompName();
        $returnData['tableData'] = $this->itemHtml($encounter, $request->temp_checked);
        $returnData['tptableData'] = $this->tpItemHtml($encounter, $request->temp_checked);
        /**check if temporary or credit item must be displayed*/
        if ($request->temp_checked === 'no') {
            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->Where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
        } else {
            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
               ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
        }
        $returnData['depositAmount'] = Helpers::totalDepositAmountReceived($encounter);
        $returnData['tpAmount'] = Helpers::getTpAmount($encounter);
        $returnData['remainingAmount'] = Helpers::numberFormat($returnData['depositAmount']-$returnData['tpAmount'],'insert');
        return response()->json([
            'status' => TRUE,
            'message' => $returnData
        ]);
    }

    public function updateShareValue($pat_billing_id){
        $patbilling = PatBilling::select('flditemqty','fldditemamt')->where('fldid',$pat_billing_id)->first();
        $patbillingshare = PatBillingShare::where('pat_billing_id',$pat_billing_id)->get();

        if($patbillingshare){
            foreach($patbillingshare as $share){
                $sharepercent = $share->usersharepercent;
                $shareAmount = ($sharepercent/100)*$patbilling->fldditemamt;
                $data = [
                    'share' =>  Helpers::numberFormat($shareAmount, 'insert'),
                    'total_amount' =>  Helpers::numberFormat($patbilling->fldditemamt,  'insert'),
                     'tax_amt' =>  Helpers::numberFormat((15/100)*$shareAmount,  'insert'),
                     'shareqty' => $patbilling->flditemqty
                 ];

                 PatBillingShare::where('id',$share->id)->update($data);
            }
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function changeDiscount(Request $request)
    {
        $patbilling = PatBilling::where('fldid', $request->fldid)->first();
        $encounter = $patbilling->fldencounterval;
        $totalAmount = $patbilling->flditemqty * $patbilling->flditemrate;
        $totalDiscount = ($totalAmount * $request->new_discount) / 100;
        $tax = (($totalAmount - $totalDiscount) * $patbilling->fldtaxper) / 100;
        $updateData = [
            'flddiscper' =>  Helpers::numberFormat($request->new_discount, 'insert'),
            'fldtaxamt' =>  Helpers::numberFormat($tax, 'insert'),
            'flddiscamt' =>  Helpers::numberFormat($totalDiscount, 'insert'),
            'fldditemamt' =>  Helpers::numberFormat(($totalAmount + $tax - $totalDiscount), 'insert'),
        ];

        PatBilling::where('fldid', $request->fldid)->update($updateData);
        $this->updateShareValue($request->fldid);
        $returnData['tableData'] = $this->itemHtml($encounter, $request->temp_checked);
        $returnData['tptableData'] = $this->tpItemHtml($encounter, $request->temp_checked);
        $computer = Helpers::getCompName();
        /**check if temporary or credit item must be displayed*/
        if ($request->temp_checked === 'no') {
            $returnData['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->Where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $returnData['subtotal'] =  Helpers::numberFormat($returnData['subtotal'], 'insert');
            $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $returnData['total'] =  Helpers::numberFormat($returnData['total'], 'insert');
            $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $returnData['discount'] =  Helpers::numberFormat($returnData['discount'],'insert');
            $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
            $returnData['tax'] =  Helpers::numberFormat($returnData['tax'],  'insert');
        } else {
            $returnData['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $returnData['subtotal'] =  Helpers::numberFormat($returnData['subtotal'],  'insert');
            $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $returnData['total'] =  Helpers::numberFormat($returnData['total'], 'insert');
            $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $returnData['discount'] =  Helpers::numberFormat($returnData['discount'], 'insert');
            $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
            $returnData['tax'] =  Helpers::numberFormat($returnData['tax'], 'insert');
        }
        $returnData['depositAmount'] =  Helpers::numberFormat(Helpers::totalDepositAmountReceived($encounter),'insert');
        $returnData['tpAmount'] =  Helpers::numberFormat(Helpers::getTpAmount($encounter),'insert');
        $returnData['remainingAmount'] =  Helpers::numberFormat($returnData['depositAmount']-$returnData['tpAmount'],'insert');
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
    public function changeDiscountBulk(Request $request)
    {
        $fldids = $request->get('fldids');
        if(isset($fldids)){
            $new_discount = $request->get('new_discount');
            $computer = Helpers::getCompName();
            foreach ($fldids as $fldid) {
                $patbilling = PatBilling::where('fldid', $fldid)->first();
                $encounter = $patbilling->fldencounterval;
                $totalAmount = $patbilling->flditemqty * $patbilling->flditemrate;
                $disAmountInsert = ($new_discount / 100) * $totalAmount;
                $taxableamount = $totalAmount-$disAmountInsert;
                $tax = $taxableamount * $patbilling->fldtaxper / 100;
                $totalAmountInsert = ($taxableamount + $tax);
                // dd($disAmountInsert);
                $updateData = [
                    'flddiscper' => $new_discount,
                    'flddiscamt' =>  Helpers::numberFormat($disAmountInsert, 'insert'),
                    'fldditemamt' =>  Helpers::numberFormat($totalAmountInsert, 'insert'),
                    'fldtaxamt' =>  Helpers::numberFormat($tax,'insert'),
                ];
                // dd($updateData);

                PatBilling::where('fldid', $fldid)->update($updateData);
                $this->updateShareValue($fldid);
            }

            $returnData['tableData'] = $this->itemHtml($encounter, $request->temp_checked);
            $returnData['tptableData'] = $this->tpItemHtml($encounter, $request->temp_checked);
            /**check if temporary or credit item must be displayed*/
            if ($request->temp_checked === 'no') {
                $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                    ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                    ->Where('fldstatus', 'Punched')
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                     ->where('fldtempbillno', '=', NULL)

                    ->where('fldditemamt', '>=', 0)
                    ->first()->subtotal;
                $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                    ->Where('fldstatus', 'Punched')
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                     ->where('fldtempbillno', '=', NULL)

                    ->where('fldditemamt', '>=', 0)
                    ->sum('fldditemamt');
                $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                    ->Where('fldstatus', 'Punched')
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                     ->where('fldtempbillno', '=', NULL)

                    ->where('fldditemamt', '>=', 0)
                    ->sum('flddiscamt');
                $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                    ->where('fldstatus', 'Punched')
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                     ->where('fldtempbillno', '=', NULL)

                    ->where('fldditemamt', '>=', 0)
                    ->sum('fldtaxamt');
            } else {
                $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                    ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                          $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                    })
                     ->where('fldtempbillno', '=', NULL)

                    ->where('fldditemamt', '>=', 0)
                    ->first()->subtotal;
                $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                          $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                    })
                     ->where('fldtempbillno', '=', NULL)

                    ->where('fldditemamt', '>=', 0)
                    ->sum('fldditemamt');
                $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                          $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                    })
                     ->where('fldtempbillno', '=', NULL)

                    ->where('fldditemamt', '>=', 0)
                    ->sum('flddiscamt');
                $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                          $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                    })
                     ->where('fldtempbillno', '=', NULL)

                    ->where('fldditemamt', '>=', 0)
                    ->sum('fldtaxamt');
            }
            // dd($returnData);
            $returnData['depositAmount'] =  Helpers::numberFormat(Helpers::totalDepositAmountReceived($encounter),'insert');
            $returnData['tpAmount'] =  Helpers::numberFormat(Helpers::getTpAmount($encounter),'insert');
            $returnData['remainingAmount'] =  Helpers::numberFormat($returnData['depositAmount']-$returnData['tpAmount'],'insert');
            return response()->json([
                'status' => TRUE,
                'message' => $returnData
            ]);
        }else{
            return response()->json([
                'status' => FALSE
            ]);
        }

    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function changeDiscountPercentBulk(Request $request)
    {
        $fldids = $request->get('fldids');
        $new_discount = $request->get('new_discount');
        // echo $new_discount; exit;
        foreach ($fldids as $fldid) {
            $patbilling = PatBilling::where('fldid', $fldid)->first();
            $encounter = $patbilling->fldencounterval;
            $totalAmount = ($patbilling->flditemqty * $patbilling->flditemrate);
            $finalTotalAmount = $totalAmount - ($new_discount / 100 * $totalAmount);
            $tax = $finalTotalAmount * $patbilling->fldtaxper / 100;

            $disAmountInsert = $new_discount / 100 * $totalAmount;
            $totalAmountInsert = ($finalTotalAmount + $tax);

            $updateData = [
                'flddiscper' => $new_discount,
                'flddiscamt' => Helpers::numberFormat($disAmountInsert,  'insert'),
                'fldditemamt' => Helpers::numberFormat($totalAmountInsert,  'insert'),
                'fldtaxamt' => Helpers::numberFormat($tax, 'insert'),
            ];

            PatBilling::where('fldid', $fldid)->update($updateData);
            $this->updateShareValue($fldid);
        }
        $computer = Helpers::getCompName();
        $returnData['tableData'] = $this->itemHtml($encounter, $request->temp_checked);
        $returnData['tptableData'] = $this->tpItemHtml($encounter, $request->temp_checked);
        /**check if temporary or credit item must be displayed*/
        if ($request->temp_checked === 'no') {
            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->Where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
        } else {
            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->where(function ($query) {
                    $query->orWhere('fldstatus', 'Punched')
                        ->orWhere('fldstatus', 'Waiting');
                })
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
        }
        $returnData['discount_percent'] = $request->get('new_discount');
        return response()->json([
            'status' => TRUE,
            'message' => $returnData
        ]);
    }

    /**
     * get doctor list form patbilling itemname and itemtype
     */
    public function getDoctorList($pat_id, $category)
    {
        // echo $pat_id.'/'.$category; exit;
        $patbilling = PatBilling::where('fldid', $pat_id)->first();
        // dd($patbilling);
        $doctors = UserShare::where([
            ['flditemtype', $patbilling->flditemtype],
            ['billing_mode', $patbilling->fldbillingmode],
            ['category','LIKE', $category]
        ])
            ->where(function ($query) use ($patbilling) {
                $query->where('flditemname', $patbilling->flditemname)
                    ->orWhere('flditemname', 'all');
            })
            ->with('user')->get();
        // dd($doctors);
        return $doctors;
    }

    /**
     * @param Request $request
     * @return false|RedirectResponse
     */
    public function createUserCashBilling(Request $request)
    {
        $datetime = date('Y-m-d H:i:s');
        $time = date('H:i:s');
        $userid = Auth::guard('admin_frontend')->user()->flduserid;
        $computer = Helpers::getCompName();
        $fiscalYear = Helpers::getNepaliFiscalYearRange();
        $startdate = Helpers::dateNepToEng($fiscalYear['startdate'])->full_date . ' 00:00:00';
        $enddate = Helpers::dateNepToEng($fiscalYear['enddate'])->full_date . '23:59:59';

        $dob = $request->get('eng_from_date');

        $formatData = Patsubs::first();
        if (!$formatData) {
            $formatData = new Patsubs();
        }

        $billingMode = $request->get('billing_mode');
        $fldptadmindate = Helpers::dateEngToNepdash(date('Y-m-d'))->full_date . ' ' . $time;

        $claim_code = '';

        DB::beginTransaction();
        try {
            $encounterID = Helpers::getNextAutoId('EncounterID', TRUE);
            if (Options::get('reg_seperate_num') == 'Yes' && !empty($request->get('department_seperate_num'))) {

                $today_date = Carbon::now()->format('Y-m-d');
                $current_fiscalyr = Year::select('fldname')->where([
                    ['fldfirst', '<=', $today_date],
                    ['fldlast', '>=', $today_date],
                ])->first();
                $current_fiscalyr = ($current_fiscalyr) ? $current_fiscalyr->fldname : '';

                $formatedEncId = $request->get('department_seperate_num') . $current_fiscalyr . '-' . $formatData->fldencid . str_pad($encounterID, $formatData->fldenclen, '0', STR_PAD_LEFT);
            } else {
                $formatedEncId = $formatData->fldencid . str_pad($encounterID, $formatData->fldenclen, '0', STR_PAD_LEFT);
            }
                // if patient ID already exists
            if($request->get('search-patient-no')){
                $patientExist = PatientInfo::where('fldpatientval',$request->get('search-patient-no'))->first();
                if($patientExist){
                    $patientId = $patientExist->fldpatientval;

                    $fldvisit = Encounter::where('fldpatientval', $patientId)->whereBetween('fldregdate', [$startdate, $enddate])->count() > 0 ? 'OLD' : 'NEW';

                    Encounter::insert([
                        'fldencounterval' => $formatedEncId,
                        'fldpatientval' => $patientId,
                        'fldadmitlocat' => '',
                        'fldcurrlocat' => $request->get('department'),

                        'flddisctype' => $request->get('discount_scheme'),
                        'fldcashcredit' => '0.00',
                        'fldadmission' => 'Registered',
                        'fldregdate' => $datetime,
                        'fldbillingmode' => $billingMode,
                        'fldcomp' => $computer,
                        'fldvisit' => $fldvisit,
                        'xyz' => '0',
                        'fldinside' => '0',
                        'fldclaimcode' => $claim_code,
                        'created_by' => $userid,
                        'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
                    ]);
                    DB::commit();
                    if ($request->form_to_redirect === "dispensing") {
                        $session_key = $request->get('session_key', 'dispensing_form_encounter_id');
                        session([$session_key => $formatedEncId]);
                        return redirect()->route('dispensingForm');
                    }else{
                        $session_key = $request->get('session_key', 'billing_encounter_id');
                        session([$session_key => $formatedEncId]);
                        return redirect()->back();

                    }
                }


            }


            $patientId = Helpers::getNextAutoId('PatientNo', TRUE);
            $first_name = $request->get('first_name');
            $last_name = $request->get('last_name');

            PatientInfo::insert([
                'fldpatientval' => $patientId,
                'fldptnamefir' => $first_name,
                'fldptnamelast' => $last_name,
                'fldptsex' => $request->get('gender'),
                'fldptaddvill' => $request->get('tole'),
                'fldptadddist' => $request->get('district'),
                'fldptcontact' => $request->get('contact'),
                'fldptbirday' => $dob,
                'fldptadmindate' => $fldptadmindate,
                'flduserid' => $userid,
                'fldtime' => $datetime,
                'xyz' => '0',
                'flddiscount' => $request->discount_scheme,
                'fldtitle' => $request->get('title'),
                'fldmidname' => $request->get('middle_name'),
                'fldcountry' => $request->get('country'),
                'fldprovince' => $request->get('province'),
                'fldmunicipality' => $request->get('municipality'),
                'fldwardno' => $request->get('wardno'),
                'fldnationalid' => $request->get('national_id'),
                'fldpannumber' => $request->get('pan_number'),
                'fldcitizenshipno' => $request->get('citizenship_no'),
                'fldbloodgroup' => $request->get('blood_group'),

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            // patient credential
            $username = "{$first_name}.{$last_name}";
            $username = strtolower($username);
            $username = Helpers::getUniquePatientUsetname($username);

            PatientCredential::insert([
                'fldpatientval' => $patientId,
                'fldusername' => $username,
                'fldpassword' => Helpers::encodePassword($username),
                'fldstatus' => 'Active',
                'fldconsultant' => $request->get('consultant'),
                'flduserid' => $userid,
                'fldtime' => $datetime,
                'fldcomp' => $computer,
                'xyz' => '0',
            ]);


            $fldvisit = Encounter::where('fldpatientval', $patientId)->whereBetween('fldregdate', [$startdate, $enddate])->count() > 0 ? 'OLD' : 'NEW';


            Encounter::insert([
                'fldencounterval' => $formatedEncId,
                'fldpatientval' => $patientId,
                'fldadmitlocat' => '',
                'fldcurrlocat' => $request->get('department'),

                'flddisctype' => $request->get('discount_scheme'),
                'fldcashcredit' => '0.00',
                'fldadmission' => 'Registered',

                'fldregdate' => $datetime,
                'fldbillingmode' => $billingMode,
                'fldcomp' => $computer,
                'fldvisit' => $fldvisit,
                'xyz' => '0',
                'fldinside' => '0',
                'created_by' => Helpers::getCurrentUserName(),
                'fldclaimcode' => $claim_code,

                'hospital_department_id' => Helpers::getUserSelectedHospitalDepartmentIdSession()
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return FALSE;
        }

        if ($request->form_to_redirect === "dispensing") {
            $session_key = $request->get('session_key', 'dispensing_form_encounter_id');
            session([$session_key => $formatedEncId]);
            return redirect()->route('dispensingForm');
        }

        $session_key = $request->get('session_key', 'billing_encounter_id');
        session([$session_key => $formatedEncId]);
        return redirect()->back();


    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeDiscountMode(Request $request)
    {
        try {
            $computer = Helpers::getCompName();
            $encounter = $request->encounterId;

            $patbillingData = PatBilling::where('fldencounterval',$request->encounterId)
                            ->where('fldsave','0')
                            ->where(function ($query) {
                                $query->orWhere('flditemtype', '!=', 'Surgicals')
                                    ->orWhere('flditemtype', '!=', 'Medicines')
                                    ->orWhere('flditemtype', '!=', 'Extra Items');
                            })
                            ->where('fldstatus', 'Punched')
                            ->whereNull('fldbillno')
                            ->whereNull('fldtempbillno')
                            ->with('serviceCost')
                            ->with('noDiscount')
                            ->where('fldcomp', $computer)
                            ->get();
            if(isset($patbillingData) and count($patbillingData)){
                foreach($patbillingData as $patbill){
                    $totalAmt = ($patbill->flditemrate * $patbill->flditemqty);
                    $serviceData['flddiscamt'] = 0;
                    if ($patbill->serviceCost->discount == 1 and is_null($patbill->noDiscount)) {
                        $discountModeRaw = $this->discountMode->checkDiscountMode($request->discount, $patbill->flditemname);

                        $discountMode = $discountModeRaw->getData();

                        if ($discountMode->is_fixed) {
                            $serviceData['flddiscper'] = $discountMode->discountPercent;
                            $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountPercent) / 100,'insert');
                        } elseif ($discountMode->is_fixed === false && $discountMode->discountArray) {
                            $serviceData['flddiscper'] = $discountMode->discountArray->fldpercent;
                            $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArray->fldpercent) / 100,'insert');
                        } else {
                            if ($discountMode->discountArrayMain && $patbill->flditemtype === "Diagnostic Tests") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldlab;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldlab) / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $patbill->flditemtype === "Radio Diagnostics") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldradio;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldradio) / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $patbill->flditemtype === "Procedures") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldproc;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldproc) / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $patbill->flditemtype === "Equipment") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldequip;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldequip) / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $patbill->flditemtype === "General Services") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldservice;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldservice) / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $patbill->flditemtype === "Others") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldother;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldother) / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $patbill->flditemtype === "Medicine") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldmedicine;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldmedicine) / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $patbill->flditemtype === "Surgical") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldsurgical;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldsurgical) / 100,'insert');
                            } elseif ($discountMode->discountArrayMain && $patbill->flditemtype === "Extra Item") {
                                $serviceData['flddiscper'] = $discountMode->discountArrayMain->fldextra;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(($totalAmt * $discountMode->discountArrayMain->fldextra) / 100,'insert');
                            } else {
                                $serviceData['flddiscper'] = 0;
                                $serviceData['flddiscamt'] = Helpers::numberFormat(0,'insert');
                            }
                        }

                    }
                    $serviceData['fldditemamt'] = Helpers::numberFormat(($totalAmt - $serviceData['flddiscamt']),'insert');
                    $taxAmtCalculation = ($serviceData['fldditemamt'] * $patbill->fldtaxper / 100);
                    $serviceData['fldtaxamt'] = Helpers::numberFormat($taxAmtCalculation,'insert');
                    $serviceData['fldtaxamt'] = Helpers::numberFormat(($serviceData['fldtaxamt'] * $patbill->flditemqty),'insert');
                    $serviceData['fldditemamt'] = Helpers::numberFormat(($totalAmt - $serviceData['flddiscamt'] + $serviceData['fldtaxamt']),'insert');


                    $serviceData['fldditemamt'] = Helpers::numberFormat(($totalAmt - $serviceData['flddiscamt'] + $serviceData['fldtaxamt']),'insert');
                    $serviceData['discount_mode'] = $request->discount;
                    $patbill->update($serviceData);
                }
            }
            $returnData['tableData'] = $this->itemHtml($encounter, $request->temp_checked);
            $returnData['tptableData'] = $this->tpItemHtml($encounter, $request->temp_checked);
            if ($request->temp_checked === 'no') {
                $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                    ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                    ->Where('fldstatus', 'Punched')
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                    ->where(function ($query) {
                        $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                        ->orWhere('fldtempbillno', '=', NULL)
                        ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                        ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->first()->subtotal;
                $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                    ->Where('fldstatus', 'Punched')
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                    ->where(function ($query) {
                        $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                        ->orWhere('fldtempbillno', '=', NULL)
                        ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                        ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->sum('fldditemamt');
                $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                    ->Where('fldstatus', 'Punched')
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                    ->where(function ($query) {
                        $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                        ->orWhere('fldtempbillno', '=', NULL)
                        ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                        ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->sum('flddiscamt');
                $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                    ->where('fldstatus', 'Punched')
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                        $query->orWhere('flditemtype', '!=', 'Surgicals')
                            ->orWhere('flditemtype', '!=', 'Medicines')
                            ->orWhere('flditemtype', '!=', 'Extra Items');
                    })
                    ->where(function ($query) {
                        $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                        ->orWhere('fldtempbillno', '=', NULL)
                        ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                        ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->sum('fldtaxamt');
            } else {
                $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                    ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                          $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                    })
                    ->where(function ($query) {
                        $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                        ->orWhere('fldtempbillno', '=', NULL)
                        ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                        ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->first()->subtotal;
                $returnData['total'] = PatBilling::where('fldencounterval', $encounter)
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                          $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                    })
                    ->where(function ($query) {
                        $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                        ->orWhere('fldtempbillno', '=', NULL)
                        ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                        ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->sum('fldditemamt');
                $returnData['discount'] = PatBilling::where('fldencounterval', $encounter)
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                          $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                    })
                    ->where(function ($query) {
                        $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                        ->orWhere('fldtempbillno', '=', NULL)
                        ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                        ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->sum('flddiscamt');
                $returnData['tax'] = PatBilling::where('fldencounterval', $encounter)
                    ->where(function ($query) {
                        $query->orWhere('fldstatus', 'Punched')
                            ->orWhere('fldstatus', 'Waiting');
                    })
                    ->where('fldcomp', $computer)
                    ->where(function ($query) {
                          $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                            ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                    })
                    ->where(function ($query) {
                        $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                        ->orWhere('fldtempbillno', '=', NULL)
                        ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                        ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
                    })
                    ->where('fldditemamt', '>=', 0)
                    ->sum('fldtaxamt');
            }
            // dd($returnData);
            $returnData['depositAmount'] = Helpers::numberFormat(Helpers::totalDepositAmountReceived($encounter),'insert');
            $returnData['tpAmount'] = Helpers::numberFormat(Helpers::getTpAmount($encounter),'insert');
            $returnData['remainingAmount'] = Helpers::numberFormat(($returnData['depositAmount']-$returnData['tpAmount']),'insert');
            return response()->json([
                'status' => true,
                'message' => $returnData
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false
            ]);
        }
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function displayInvoice(Request $request)
    {
        // dd($request['encounter_id']);
        $data['depositDetail'] = [];
        $encounter_id = $request['encounter_id'];
        $data['enpatient'] = Encounter::where('fldencounterval', $request['encounter_id'])->with('patientInfo')->first();
        $parentBillNo = NULL;

        $data['billNo'] = $billno = $request['invoice_number'];
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
            if (!empty($cashbill) && isset($cashbill[0])) {
                $billno = $cashbill[0]->fldbillno;
                $parentBillNo = $cashbill[0]->fldretbill;
            }

            if ($parentBillNo)
                $data['parentBillDetail'] = PatBilling::where('fldbillno', $parentBillNo)->get();

            $data['patbillingDetails'] = PatBillDetail::where('fldencounterval', $request['encounter_id'])->where('fldbillno', $billno)->first();
            $data['billCount'] = PatBillCount::where('fldbillno', $billno)->count();
        }


        $data['enpatient'] = Encounter::where('fldencounterval', $encounter_id)->with('patientInfo')->first();
        $getCategory = PatBilling::select('flditemtype')
            ->where('fldencounterval', $encounter_id)
            ->where('fldstatus', 'Cleared')
            ->where('fldbillno', $billno)
            ->groupBy('flditemtype')
            ->get();

        if ($getCategory) {
            foreach ($getCategory as $k => $billing) {
                $data['eachpatbilling'][$k]['category'] = $billing->flditemtype;

                $patbillData = PatBilling::where('tblpatbilling.flditemtype', $billing->flditemtype)
                    ->select('tblpatbilling.*','sc.fldbillitem')
                    ->join('tblservicecost as sc','sc.flditemname','tblpatbilling.flditemname')
                    ->where('tblpatbilling.fldencounterval', $encounter_id)
                    ->where('tblpatbilling.fldstatus', 'Cleared')
                    ->where('tblpatbilling.fldbillno', $billno)
                    ->get();
                $data['eachpatbilling'][$k]['details'] = $patbillData;
                $data['eachpatbilling'][$k]['total'] = $patbillData->sum('fldditemamt');
                $data['patbillingdetail'][$k] = PatbillDetail::where('fldtempbillno', $billing->fldtempbillno)->first();
            }

            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter_id)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where('fldbillno', $billno)
                ->where('fldstatus', 'Cleared')
                ->first()->subtotal;
            $data['total'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldbillno', $billno)
                ->where('fldstatus', 'Cleared')
                ->sum('fldditemamt');
            $data['discount'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldbillno', $billno)
                ->where('fldstatus', 'Cleared')
                ->sum('flddiscamt');
            $data['tax'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldbillno', $billno)
                ->where('fldstatus', 'Cleared')
                ->sum('fldtaxamt');

            $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
                ->where('fldencounterval', $encounter_id)
                ->where('fldcomp','=',Helpers::getCompName())
                ->orderBy('fldid', 'DESC')
                ->first();

            $data['previousDeposit'] = $previousDeposit->fldcurdeposit ?? 0;
        }

        // get parent detail if return bill
        if ($parentBillNo) {
            $getCategory_parent = PatBilling::select('flditemtype')
                ->where('fldencounterval', $encounter_id)
                ->where('fldstatus', 'Cleared')
                ->where('fldbillno', $parentBillNo)
                ->groupBy('flditemtype')
                ->get();
            if ($getCategory_parent) {
                foreach ($getCategory_parent as $k => $billing) {
                    $data['eachpatbilling_parent'][$k]['category'] = $billing->flditemtype;

                    $parentpatbillData = PatBilling::where('flditemtype', $billing->flditemtype)
                        ->where('fldencounterval', $encounter_id)
                        ->where('fldstatus', 'Cleared')
                        ->where('fldbillno', $parentBillNo)
                        ->get();
                    $data['eachpatbilling_parent'][$k]['details'] = $parentpatbillData;
                    $data['eachpatbilling_parent'][$k]['total'] = $parentpatbillData->sum('fldditemamt');
                    $data['patbillingdetail_parent'][$k] = PatbillDetail::where('fldtempbillno', $billing->fldtempbillno)->first();
                }

                $data['parent_subtotal'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                    ->where('fldstatus', 'Cleared')
                    ->first()->subtotal;
                $data['parent_total'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldbillno', $parentBillNo)
                    ->where('fldstatus', 'Cleared')
                    ->sum('fldditemamt');
                $data['parent_discount'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldbillno', $parentBillNo)
                    ->where('fldstatus', 'Cleared')
                    ->sum('flddiscamt');
                $data['parent_tax'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldbillno', $parentBillNo)
                    ->where('fldstatus', 'Cleared')
                    ->sum('fldtaxamt');

                $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldcomp','=',Helpers::getCompName())
                    ->orderBy('fldid', 'DESC')
                    ->first();

                $data['parent_previousDeposit'] = $previousDeposit->fldcurdeposit ?? 0;

                $data['parent_patbillingDetails'] = PatBillDetail::where('fldencounterval', $encounter_id)->where('fldbillno', $parentBillNo)->first();
                $data['parent_billCount'] = PatBillCount::where('fldbillno', $parentBillNo)->count();
            }
        }
        $ref_doctor_pat = PatBilling::with('referUserdetail')
        ->where('fldencounterval',$encounter_id)
        ->where('fldcomp','=',Helpers::getCompName())
        ->where('fldrefer','!=', NULL)->first();
        if($ref_doctor_pat){
            $data['referable_doctor'] = (($ref_doctor_pat->fldrefer && $ref_doctor_pat->referUserdetail) ? $ref_doctor_pat->referUserdetail->fldfullname :'' );
        }else{
            $ref_doctor_consult = Consult::with('user')->where('fldencounterval',$encounter_id)->where('flduserid','!=',NULL)->first();
            if($ref_doctor_consult){
                $data['referable_doctor'] = (($ref_doctor_consult->flduserid && $ref_doctor_consult->user) ? $ref_doctor_consult->user->fldfullname :'' );
            }
        }
        if(isset($request->billtype) && $request->billtype == 'discharge'){
            $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
                ->where('fldencounterval', $encounter_id)
                ->where('fldcomp','=',Helpers::getCompName())
                ->orderBy('fldid', 'DESC')
                ->first();

            $data['previousDeposit'] = $previousDeposit->fldcurdeposit ?? 0;

            $data['depositDetail'] = PatBillDetail::where('fldbillno', 'LIKE', '%DEP%')
            ->where('fldencounterval', $encounter_id)
            ->where('fldcomp','=',Helpers::getCompName())
            ->get();
            // dd($depositDetail)
            if ($request->print_preview === 'detailed') {
                return view('billing::invoice-detailed', $data);
            }
            return view('billing::invoice.discharge-invoice', $data);

        }else{
            if ($request->print_preview === 'detailed') {
                return view('billing::invoice-detailed', $data);
            }
            return view('billing::invoice', $data);

        }


    }


    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function displayInvoiceService(Request $request)
    {
        $encounter_id = $request['encounter_id'];
        $data['enpatient'] = Encounter::where('fldencounterval', $request['encounter_id'])->with('patientInfo')->first();
        $parentBillNo = NULL;

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
                $parentBillNo = $cashbill[0]->fldretbill;
            }

            if ($parentBillNo)
                $data['parentBillDetail'] = PatBilling::where('fldbillno', $parentBillNo)->get();

            $data['patbillingDetails'] = PatBillDetail::where('fldencounterval', $request['encounter_id'])->where('fldbillno', $billno)->first();
            $data['billCount'] = PatBillCount::where('fldbillno', $billno)->count();
        }

        //        session()->forget('billing_encounter_id');

        $data['enpatient'] = Encounter::where('fldencounterval', $encounter_id)->with('patientInfo')->first();
        $getCategory = PatBilling::select('flditemtype')
            ->where('fldencounterval', $encounter_id)
            ->where('fldstatus', 'Cleared')
            ->where('fldbillno', $billno)
            ->groupBy('flditemtype')
            ->get();

        if ($getCategory) {
            foreach ($getCategory as $k => $billing) {
                $data['eachpatbilling'][$k]['category'] = $billing->flditemtype;

                $patbillData = PatBilling::where('flditemtype', $billing->flditemtype)
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldstatus', 'Cleared')
                    ->where('fldbillno', $billno)
                    ->get();
                $data['eachpatbilling'][$k]['details'] = $patbillData;
                $data['eachpatbilling'][$k]['total'] = $patbillData->sum('fldditemamt');
                $data['patbillingdetail'][$k] = PatbillDetail::where('fldtempbillno', $billing->fldtempbillno)->first();
            }

            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter_id)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where('fldstatus', 'Cleared')
                ->first()->subtotal;
            $data['total'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldbillno', $billno)
                ->where('fldstatus', 'Cleared')
                ->sum('fldditemamt');
            $data['discount'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldbillno', $billno)
                ->where('fldstatus', 'Cleared')
                ->sum('flddiscamt');
            $data['tax'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldbillno', $billno)
                ->where('fldstatus', 'Cleared')
                ->sum('fldtaxamt');

            $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
                ->where('fldencounterval', $encounter_id)
                ->where('fldcomp','=',Helpers::getCompName())
                ->orderBy('fldtime', 'DESC')
                ->first();

            $data['previousDeposit'] = $previousDeposit->fldcurdeposit ?? 0;
        }

        // get parent detail if return bill
        if ($parentBillNo) {
            $getCategory_parent = PatBilling::select('flditemtype')
                ->where('fldencounterval', $encounter_id)
                ->where('fldstatus', 'Cleared')
                ->where('fldbillno', $parentBillNo)
                ->groupBy('flditemtype')
                ->get();
            if ($getCategory_parent) {
                foreach ($getCategory_parent as $k => $billing) {
                    $data['eachpatbilling_parent'][$k]['category'] = $billing->flditemtype;

                    $parentpatbillData = PatBilling::where('flditemtype', $billing->flditemtype)
                        ->where('fldencounterval', $encounter_id)
                        ->where('fldstatus', 'Cleared')
                        ->where('fldbillno', $parentBillNo)
                        ->get();
                    $data['eachpatbilling_parent'][$k]['details'] = $parentpatbillData;
                    $data['eachpatbilling_parent'][$k]['total'] = $parentpatbillData->sum('fldditemamt');
                    $data['patbillingdetail_parent'][$k] = PatbillDetail::where('fldtempbillno', $billing->fldtempbillno)->first();
                }

                $data['parent_subtotal'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                    ->where('fldstatus', 'Cleared')
                    ->first()->subtotal;
                $data['parent_total'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldbillno', $parentBillNo)
                    ->where('fldstatus', 'Cleared')
                    ->sum('fldditemamt');
                $data['parent_discount'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldbillno', $parentBillNo)
                    ->where('fldstatus', 'Cleared')
                    ->sum('flddiscamt');
                $data['parent_tax'] = PatBilling::where('fldencounterval', $encounter_id)
                    ->where('fldbillno', $parentBillNo)
                    ->where('fldstatus', 'Cleared')
                    ->sum('fldtaxamt');

                $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldcomp','=',Helpers::getCompName())
                    ->orderBy('fldtime', 'DESC')
                    ->first();

                $data['parent_previousDeposit'] = $previousDeposit->fldcurdeposit ?? 0;

                $data['parent_patbillingDetails'] = PatBillDetail::where('fldencounterval', $encounter_id)->where('fldbillno', $parentBillNo)->first();
                $data['parent_billCount'] = PatBillCount::where('fldbillno', $parentBillNo)->count();
            }
        }

        return view('billing::invoice-cashier-return-form', $data);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function displayInvoiceBilling(Request $request)
    {
        $encounter_id = $request['encounter_id'];
        $data['enpatient'] = Encounter::where('fldencounterval', $request['encounter_id'])->with('patientInfo')->first();

        $billno = $request['invoice_number'];
        $data['patbillingDetails'] = PatbillDetail::where('fldencounterval', $request['encounter_id'])
            ->where('fldbillno', $billno)
            ->whereNull('fldbillno')
            ->first();

        $data['invoice_title'] = 'Invoice';
        if ($data['patbillingDetails']) {
            $cashbill = PatBilling::where('tblpatbilling.fldencounterval', $request['encounter_id'])
                ->select('tblpatbilling.*','sc.fldbillitem')
                ->join('tblservicecost as sc','sc.flditemname','tblpatbilling.flditemname')
                ->where('tblpatbilling.fldstatus', 'Cleared')->with('referUserdetail');
            if ($request['invoice_number']) {
                $cashbill->where('fldbillno', "like", $request['invoice_number']);
            } else {
                $cashbill->where('fldbillno', 'like', "REG-%");
            }


            $data['patbilling'] = $cashbill->get();

            // $data['billCount'] = PatBillCount::where('fldbillno', $billno)->count();

            $data['invoice_title'] = $data['patbillingDetails']['fldbilltype'] === 'Cash' ? 'Invoice' : 'Credit Bill';
        } else {
            $cashbillQuery = PatBilling::where('tblpatbilling.fldencounterval', $request['encounter_id'])
                ->select('tblpatbilling.*','sc.fldbillitem')
                ->join('tblservicecost as sc','sc.flditemname','tblpatbilling.flditemname')
                ->where('tblpatbilling.fldstatus', 'Cleared')->with('referUserdetail');
            if ($request['invoice_number']) {
                $cashbillQuery->where('fldbillno', $request['invoice_number']);
            } else {
                $cashbillQuery->where('fldbillno', 'like', "REG-%");
            }

            $cashbill = $cashbillQuery->get();

            $data['patbilling'] = $cashbill;
            if (!empty($cashbill) && isset($cashbill[0]->fldbillno)) {
                $billno = $cashbill[0]->fldbillno;
            }

            $data['patbillingDetails'] = PatBillDetail::where('fldencounterval', $request['encounter_id'])->where('fldbillno', $billno)->first();
            // $data['billCount'] = PatBillCount::where('fldbillno', $billno)->count();
        }
        $countdata = PatBillCount::where('fldbillno', $billno)->pluck('fldcount')->first();
        $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != '') ? $countdata + 1 : 1;

        if (isset($countdata) and $countdata != '') {
            PatBillCount::where('fldbillno', $billno)->update($updatedata);
        } else {
            $insertdata['fldbillno'] = $billno;
            $insertdata['fldcount'] = 1;
            PatBillCount::insert($insertdata);
        }
        $data['billCount'] = $count;
        // session()->forget('billing_encounter_id');


        $data['enpatient'] = Encounter::where('fldencounterval', $encounter_id)->with('patientInfo')->first();
        $getCategory = PatBilling::select('flditemtype')
            ->where('fldencounterval', $encounter_id)
            ->where('fldstatus', 'Cleared')
            ->where('fldbillno', $billno)
            ->groupBy('flditemtype')
            ->get();

        if ($getCategory) {
            foreach ($getCategory as $k => $billing) {
                $data['eachpatbilling'][$k]['category'] = $billing->flditemtype;

                $patbillData = PatBilling::where('flditemtype', $billing->flditemtype)
                    ->where('fldencounterval', $encounter_id)
                    ->where('fldstatus', 'Cleared')
                    ->where('fldbillno', $billno)
                    ->get();
                $data['eachpatbilling'][$k]['details'] = $patbillData;
                $data['eachpatbilling'][$k]['total'] = $patbillData->sum('fldditemamt');
                $data['patbillingdetail'][$k] = PatbillDetail::where('fldbillno', $billing->fldbillno)->first();
            }

            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter_id)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where('fldstatus', 'Cleared')
                ->first()->subtotal;
            $data['total'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldbillno', $billno)
                ->where('fldstatus', 'Cleared')
                ->sum('fldditemamt');
            $data['discount'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldbillno', $billno)
                ->where('fldstatus', 'Cleared')
                ->sum('flddiscamt');
            $data['tax'] = PatBilling::where('fldencounterval', $encounter_id)
                ->where('fldbillno', $billno)
                ->where('fldstatus', 'Cleared')
                ->sum('fldtaxamt');

            $data['remarks'] = PatBilling::select('fldreason')->where('fldencounterval', $encounter_id)
                ->where('fldbillno', $billno)
                ->where('fldstatus', 'Cleared')->first();

            $previousDeposit = \App\PatBillDetail::select('fldcurdeposit')
                ->where('fldencounterval', $encounter_id)
                ->where('fldcomp','=',Helpers::getCompName())
                ->orderBy('fldtime', 'DESC')
                ->first();

            $data['previousDeposit'] = $previousDeposit->fldcurdeposit ?? 0;
        }

        if ($data['patbillingDetails'] == NULL)
            return redirect()->back()->with('error_message', 'Invalid bill number!');
        // $html = view('billing::invoice-cashier-form-direct-print', $data)->render();
        // return $html;
        return view('billing::invoice-cashier-form', $data);
    }



    /**
     * @param $encounter
     * @return array|string
     * @throws Throwable
     */
    public function itemHtml($encounter, $is_temp = 'no')
    {
         $computer = Helpers::getCompName();
        if ($is_temp === 'no') {
            $data['serviceData'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                 ->where('fldtempbillno', '=', NULL)

                ->with('serviceCost')
                ->where('fldditemamt', '>=', 0)
                ->orderBy('fldid', 'DESC')
                ->get();
            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $data['subtotal'] = Helpers::numberFormat($data['subtotal'],  'insert');

            $data['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');

            $data['total'] = Helpers::numberFormat($data['total'],  'insert');
            $data['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');

            $data['discount'] = Helpers::numberFormat($data['discount'], 'insert');

            $data['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
            $data['tax'] = Helpers::numberFormat($data['tax'], 'insert');
        } else {
            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->orWhere('fldstatus', 'Punched')
                ->orWhere('fldstatus', 'Waiting')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $data['subtotal'] = Helpers::numberFormat($data['subtotal'],'insert');
            $data['serviceData'] = PatBilling::where('fldencounterval', $encounter)
                ->orWhere('fldstatus', 'Punched')
                ->orWhere('fldstatus', 'Waiting')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                 ->where('fldtempbillno', '=', NULL)
                // ->where(function ($query) {
                //     $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                //     ->orWhere('fldtempbillno', '=', NULL)
                //     ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                //     ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');


                // })
                ->with('serviceCost')
                ->where('fldditemamt', '>=', 0)
                ->orderBy('fldid', 'DESC')
                ->get();
            $data['total'] = PatBilling::where('fldencounterval', $encounter)
                ->orWhere('fldstatus', 'Punched')
                ->orWhere('fldstatus', 'Waiting')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $data['total'] = Helpers::numberFormat($data['total'],  'insert');
            $data['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->orWhere('fldstatus', 'Punched')
                ->orWhere('fldstatus', 'Waiting')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $data['discount'] = Helpers::numberFormat($data['discount'], 'insert');
            $data['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->orWhere('fldstatus', 'Punched')
                ->orWhere('fldstatus', 'Waiting')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })
                 ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
            $data['tax'] = Helpers::numberFormat($data['tax'], 'insert');
        }
        $html = view('billing::dynamic-views.service-item-list', $data)->render();
        return $html;
    }

    /**
     * @param $encounter
     * @return array|string
     * @throws Throwable
     */
    public function tpItemHtml($encounter, $is_temp = 'no')
    {
         $computer = Helpers::getCompName();
        if ($is_temp === 'no') {
            $data['serviceData'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                ->where('fldtempbillno', '=', NULL)
                ->with('serviceCost')
                ->where('fldditemamt', '>=', 0)
                ->orderBy('fldid', 'DESC')
                ->get();
            $data['serviceTpData'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where(function ($query) {
                    $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                    ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                    ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
                })
                ->where('fldditemamt', '>=', 0)
                ->where('fldstatus', 'Punched')
                ->with('serviceCost')
                ->with('noDiscount')
                ->orderBy('fldid', 'DESC')->get();
            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $data['subtotal'] = Helpers::numberFormat($data['subtotal'], 'insert');

            $data['total'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');

            $data['total'] = Helpers::numberFormat($data['total'], 'insert');
            $data['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');

            $data['discount'] = Helpers::numberFormat($data['discount'],'insert');

            $data['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldstatus', 'Punched')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
            $data['tax'] = Helpers::numberFormat($data['tax'],'insert');
        } else {
            $data['subtotal'] = PatBilling::where('fldencounterval', $encounter)
                ->select(DB::raw('sum(flditemrate*flditemqty) AS subtotal'))
                ->orWhere('fldstatus', 'Punched')
                ->orWhere('fldstatus', 'Waiting')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->first()->subtotal;
            $data['subtotal'] = Helpers::numberFormat($data['subtotal'], 'insert');
            $data['serviceData'] = PatBilling::where('fldencounterval', $encounter)
                ->orWhere('fldstatus', 'Punched')
                ->orWhere('fldstatus', 'Waiting')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                ->where('fldtempbillno', '=', NULL)

                ->with('serviceCost')
                ->where('fldditemamt', '>=', 0)
                ->orderBy('fldid', 'DESC')
                ->get();
            $data['serviceTpData'] = PatBilling::where('fldencounterval', $encounter)
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                    $query->orWhere('flditemtype', '!=', 'Surgicals')
                        ->orWhere('flditemtype', '!=', 'Medicines')
                        ->orWhere('flditemtype', '!=', 'Extra Items');
                })
                ->where(function ($query) {
                    $query->where('fldtempbillno', 'NOT LIKE', '%PHM%')
                    ->orWhere('fldtempbillno', 'LIKE', '%TP-%')
                    ->orWhere('fldtempbillno', 'NOT LIKE', '%TPPHM%');
                })
                ->where('fldditemamt', '>=', 0)
                ->where('fldstatus', 'Punched')
                ->with('serviceCost')
                ->with('noDiscount')
                ->orderBy('fldid', 'DESC')->get();
            $data['total'] = PatBilling::where('fldencounterval', $encounter)
                ->orWhere('fldstatus', 'Punched')
                ->orWhere('fldstatus', 'Waiting')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('fldditemamt');
            $data['total'] = Helpers::numberFormat($data['total'], 'insert');
            $data['discount'] = PatBilling::where('fldencounterval', $encounter)
                ->orWhere('fldstatus', 'Punched')
                ->orWhere('fldstatus', 'Waiting')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');

                })
                ->where('fldtempbillno', '=', NULL)

                ->where('fldditemamt', '>=', 0)
                ->sum('flddiscamt');
            $data['discount'] = Helpers::numberFormat($data['discount'],  'insert');
            $data['tax'] = PatBilling::where('fldencounterval', $encounter)
                ->orWhere('fldstatus', 'Punched')
                ->orWhere('fldstatus', 'Waiting')
                ->where('fldcomp', $computer)
                ->where(function ($query) {
                      $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                        ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
                })

                ->where('fldditemamt', '>=', 0)
                ->sum('fldtaxamt');
            $data['tax'] = Helpers::numberFormat($data['tax'], 'insert');
        }
        $html = view('billing::dynamic-views.service-tp-item-list', $data)->render();
        return $html;
    }

    public function displayReturnBilling(Request $request)
    {
        try {
            $returnBillno = $request->get('invoice_number');
            $countdata = PatBillCount::where('fldbillno', $returnBillno)->pluck('fldcount')->first();
            $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != '') ? $countdata + 1 : 1;

            if (isset($countdata) and $countdata != '') {
                PatBillCount::where('fldbillno', $returnBillno)->update($updatedata);
            } else {
                $insertdata['fldbillno'] = $returnBillno;
                $insertdata['fldcount'] = 1;
                PatBillCount::insert($insertdata);
            }

            $data['patbillingDetails'] = $billdetail = PatBillDetail::where('fldbillno', $returnBillno)->first();
            $data['itemdata'] = PatBilling::where('tblpatbilling.fldbillno', $returnBillno)
                                ->with('serviceCost')
                                ->get();

            $data['enpatient'] = Encounter::where('fldencounterval', $billdetail->fldencounterval)->with('patientInfo')->first();
            $data['billCount'] = $count;
            $data['patbillingDetails'] = $billdetail = PatBillDetail::where('fldbillno', $returnBillno)->first();
            if ($data['itemdata']->isNotEmpty()) {
                $oldBillNo = $data['itemdata'][0]->fldretbill;
                if ($oldBillNo) {
                    $data['orginalitemdata'] = PatBilling::where('tblpatbilling.fldbillno', $oldBillNo)
                                            ->with('serviceCost')
                                            ->get();
                    $data['remainingitemdata'] = PatBilling::where('fldretbill', $oldBillNo)->get();
                $data['orginaltotal'] = $data['orginalitemdata']->sum('fldditemamt');

                    $data['remainingtotal'] = $data['remainingitemdata']->sum('fldditemamt');
                }
            }
            return view('billing::pdf.return-billing-invoice', $data);
        } catch (\Exception $e) {
            \Log::info($e);
            throw new \Exception(__('messages.error'));
        }
    }

    public function displayReturnBillingView(Request $request)
    {
        try {
            $returnBillno = $request->get('invoice_number');
            $countdata = PatBillCount::where('fldbillno', $returnBillno)->pluck('fldcount')->first();
            $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != '') ? $countdata + 1 : 1;

            if (isset($countdata) and $countdata != '') {
                PatBillCount::where('fldbillno', $returnBillno)->update($updatedata);
            } else {
                $insertdata['fldbillno'] = $returnBillno;
                $insertdata['fldcount'] = 1;
                PatBillCount::insert($insertdata);
            }

            $data['patbillingDetails'] = $billdetail = PatBillDetail::where('fldbillno', $returnBillno)->first();
            $data['itemdata'] = PatBilling::where('fldbillno', $returnBillno)->get();
            $data['enpatient'] = Encounter::where('fldencounterval', $billdetail->fldencounterval)->with('patientInfo')->first();
            $data['billCount'] = $count;
            $data['patbillingDetails'] = $billdetail = PatBillDetail::where('fldbillno', $returnBillno)->first();
            if ($data['itemdata']->isNotEmpty()) {
                $oldBillNo = $data['itemdata'][0]->fldretbill;
                if ($oldBillNo) {
                    $data['orginalitemdata'] = PatBilling::where('fldbillno', $oldBillNo)->get();
                    $data['remainingitemdata'] = PatBilling::where('fldretbill', $oldBillNo)->get();
                    $data['orginaltotal'] = Helpers::numberFormat($data['orginalitemdata']->sum('fldditemamt'));
                    $data['remainingtotal'] = Helpers::numberFormat($data['remainingitemdata']->sum('fldditemamt'));
                }
            }

            $invoicebill =  view('billing::ajax-views.ajax-billing-return-billing-invoice', $data)->render();
            return response([
                'invoicebill' => $invoicebill,
                'route' => route('billing.displayReturnBilling.print'),
                'billno' => $request->invoice_number,
             ]);
        } catch (\Exception $e) {
            \Log::info($e);
            throw new \Exception(__('messages.error'));
        }
    }


    public function displayReturnBillingPrint(Request $request)
    {
        try {
            $returnBillno = $request->get('invoice_number');
            $countdata = PatBillCount::where('fldbillno', $returnBillno)->pluck('fldcount')->first();
            $updatedata['fldcount'] = $count = (isset($countdata) and $countdata != '') ? $countdata + 1 : 1;

            if (isset($countdata) and $countdata != '') {
                PatBillCount::where('fldbillno', $returnBillno)->update($updatedata);
            } else {
                $insertdata['fldbillno'] = $returnBillno;
                $insertdata['fldcount'] = 1;
                PatBillCount::insert($insertdata);
            }

            $data['patbillingDetails'] = $billdetail = PatBillDetail::where('fldbillno', $returnBillno)->first();
            $data['itemdata'] = PatBilling::where('fldbillno', $returnBillno)->get();
            $data['enpatient'] = Encounter::where('fldencounterval', $billdetail->fldencounterval)->with('patientInfo')->first();
            $data['billCount'] = $count;
            $data['patbillingDetails'] = $billdetail = PatBillDetail::where('fldbillno', $returnBillno)->first();
            if ($data['itemdata']->isNotEmpty()) {
                $oldBillNo = $data['itemdata'][0]->fldretbill;
                if ($oldBillNo) {
                    $data['orginalitemdata'] = PatBilling::where('fldbillno', $oldBillNo)->get();
                    $data['remainingitemdata'] = PatBilling::where('fldretbill', $oldBillNo)->get();
                    $data['orginaltotal'] = Helpers::numberFormat($data['orginalitemdata']->sum('fldditemamt'));
                    $data['remainingtotal'] = Helpers::numberFormat($data['remainingitemdata']->sum('fldditemamt'));
                }
            }

            $invoicebill =  view('billing::ajax-views.ajax-billing-return-billing-invoice', $data)->render();
            return response(
                [
                    'printview' => $invoicebill,

                ]);
        } catch (\Exception $e) {
            \Log::info($e);
            throw new \Exception(__('messages.error'));
        }
    }


    public function getDoctorShare(Request $request)
    {
        $share_amt = 0;
        $item_amt = 0;
        $patbillshare = PatBillingShare::where([
            'pat_billing_id' => $request->patbillid,
            'type' => $request->type,
            'user_id' => $request->userid
        ])->first();
        if($patbillshare){
            $share_amt = Helpers::numberFormat($patbillshare->share,'insert');
            $item_amt = Helpers::numberFormat($patbillshare->total_amount,'insert');
        }

        return response([
            'success' => true,
            'share_amt' => $share_amt,
            'item_amt' =>  $item_amt
        ]);
    }

    public function getOTDoctorShare(Request $request)
    {
        $patbilling = PatBilling::where('fldid', $request->patbillid)->first();
        $patbillingshares = PatBillingShare::where([
            'pat_billing_id' => $request->patbillid,
            'type' => "OT Dr. Group",
            'ot_group_sub_category_id' => $request->ot_group_sub_category_id,
        ])->get();
        $usershares = UserShare::where([
            'category' => "OT Dr. Group",
            'ot_group_sub_category_id' => $request->ot_group_sub_category_id,
            'billing_mode' => $patbilling->fldbillingmode,
        ])->with(['user:id,firstname,middlename,lastname', 'sub_category'])->get();
        $otGroupSubCategory = OtGroupSubCategory::where('id', $request->ot_group_sub_category_id)->first();
        $html = "";
        foreach ($usershares as $usershare) {
            $value = "";

            foreach ($patbillingshares as $patbillingshare) {
                if ($patbillingshare->user_id == $usershare->flduserid) {
                    $shareamt = Helpers::numberFormat($patbillingshare->share,'insert');

                    $itmamt = Helpers::numberFormat($patbillingshare->total_amount,'insert');
                    $othershareamt = $patbillingshare->usersharepercent;
                    $value = $patbillingshare->usersharepercent;
                }
            }

            $html .= '<tr data-user="' . $usershare->ot_group_sub_category_id . '">
                        <td>
                            <input type="hidden" class="form-control userid" name="shares[' . $request->patbillid . '][OT Dr. Group][' . $usershare->flduserid . '][userid]" value="' . $usershare->flduserid . '">
                            <input type="hidden" class="form-control userid" name="shares[' . $request->patbillid . '][OT Dr. Group][' . $usershare->flduserid . '][ot_group_sub_category_id]" value="' . $usershare->ot_group_sub_category_id . '">
                            <input type="text" class="form-control" readonly name="shares[' . $request->patbillid . '][OT Dr. Group][' . $usershare->flduserid . '][name]" value="' . $usershare->user->fldfullname . ' (' . $otGroupSubCategory->name . ')">
                        </td>
                        <td>
                            <input type="number" step="any" class="form-control shareper" name="shares[' . $request->patbillid . '][OT Dr. Group][' . $usershare->flduserid . '][sharevalue]" value="' . $value . '" placeholder="Share Percent %">
                        </td>
                    </tr>';
        }
        return response([
            'success' => true,
            'html' => $html
        ]);
    }

    public function getPackageItemList(Request $request){
        try{
            if(isset($request->tp) and $request->tp == '1'){
                $data['serviceData'] = $encounterData = PatBilling::where('fldencounterval',$request->encounter)
                        ->where('package_name',$request->packagename)
                        ->where('fldcomp',Helpers::getCompName())
                        ->where(function ($query) {
                                $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                                ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                                ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
        
                        })
                        ->where('fldtempbillno', '!=', NULL)
                        ->where('fldbillno', '=', NULL)
                        ->with('serviceCost')
                        ->where('fldditemamt', '>=', 0)
                        ->orderBy('fldid', 'DESC')
                        ->get();
                // dd($data['serviceData']);
            }else{
                $data['serviceData'] = $encounterData = PatBilling::where('fldencounterval',$request->encounter)
                        ->where('package_name',$request->packagename)
                        ->where('fldcomp',Helpers::getCompName())
                        ->where(function ($query) {
                                $query->where('flditemtype', 'NOT LIKE', '%Surgicals%')
                                ->orWhere('flditemtype', 'NOT LIKE', '%Medicines%')
                                ->orWhere('flditemtype', 'NOT LIKE', '%Extra Items%');
        
                        })
                        ->where('fldtempbillno', '=', NULL)
                        ->where('fldbillno', '=', NULL)
                        ->with('serviceCost')
                        ->where('fldditemamt', '>=', 0)
                        ->orderBy('fldid', 'DESC')
                        ->get();
            }
            

            $data['packagename'] = $request->packagename;
            $data['html'] = view('billing::dynamic-views.package-item-list', $data)->render();
            
            return response([
                'success' => true,
                'message' => $data
            ]);
        }catch(\Exception $e){
            dd($e);
            return response([
                'success' => false
            ]);
        }
    }

}
