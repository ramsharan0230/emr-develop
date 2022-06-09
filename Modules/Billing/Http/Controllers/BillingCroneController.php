<?php

namespace Modules\Billing\Http\Controllers;

use App\PatBillDetail;
use App\PatBilling;
use Illuminate\Routing\Controller;
use App\Utils\Helpers;

class BillingCroneController extends Controller
{
    public function billingFixes($token)
    {
        return "Already Done";
        if ($token === "crone-COGENT$$") {
            $patBillDetail = PatBillDetail::where('fldtime', '>=', '2021-08-19 00:00:00')
                ->where('fldtime', '<=', '2021-08-23 12:59:59')
                ->where('fldbillno', 'like', 'CAS%')
                ->where('fldbilltype', 'Cash')
                ->get();

            foreach ($patBillDetail->chunk(3) as $patdetailChunk) {
                foreach ($patdetailChunk as $patdetail) {
                    $patbill = [];
                    $patbill = PatBilling::where('fldbillno', 'like', $patdetail->fldbillno)->get();
                    $patbillTotalDiscount = $patbill->where('fldbillno', 'like', $patdetail->fldbillno)->sum('flddiscamt');
                    $patbillTotalTax = $patbill->where('fldbillno', 'like', $patdetail->fldbillno)->sum('fldtaxamt');
                    $patbillTotalItemAmount = $patbill->where('fldbillno', 'like', $patdetail->fldbillno)->sum('fldditemamt') + $patbillTotalDiscount - $patbillTotalTax;
                    $patbillTotalChargedAmount = $patbill->where('fldbillno', 'like', $patdetail->fldbillno)->sum('fldditemamt');

                    $patdetail->flditemamt = Helpers::numberFormat($patbillTotalItemAmount,'insert');
                    $patdetail->fldchargedamt = Helpers::numberFormat($patbillTotalChargedAmount,'insert');
                    $patdetail->fldtaxamt = Helpers::numberFormat($patbillTotalTax,'insert');
                    $patdetail->flddiscountamt =Helpers::numberFormat( $patbillTotalDiscount,'insert');
//                $patdetail->flditemamt = $patbillTotalAmount;
                    $patdetail->save();
                }
            }
            return "done";
        }
        return "Something went wrong";
    }
}
