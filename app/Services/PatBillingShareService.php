<?php

namespace App\Services;

use App\PatBilling;
use App\ServiceCost;
use App\UserShare;
use App\Utils\Helpers;

class PatBillingShareService
{
    public static function calculateIndividualShare($pat_billing_id)
    {
        try {
            $bill = PatBilling::find($pat_billing_id);
            $bill_shares = $bill->pat_billing_shares;
            $item_type = $bill->flditemtype;
            $item_name = $bill->flditemname;

            $total_shares = 0;
            // get users involved in share
            $user_shares = [];
            foreach ($bill_shares as $bill_share) {
                $type = $bill_share->type;
                $user_shares[] = UserShare::where([
                    ['flduserid', $bill_share->user_id],
                    ['flditemtype', $item_type],
                    ['category', $type]
                ])
                    ->where(function ($query) use ($item_name) {
                        $query->where('flditemname', $item_name)
                            ->orWhere('flditemname', 'all');
                    })
                    ->first();
            }

            if (count($user_shares) > 0) {
                $total_shares = collect($user_shares)->sum('flditemshare');

                // calculate share for each individual
                foreach ($bill_shares as $bill_share) {
                    $individual_share = UserService::getShareForService($bill_share->user_id, $item_type, $item_name, $bill_share->type);
                    $individual_share_tax = UserService::getShareTaxForService($bill_share->user_id, $item_type, $item_name, $bill_share->type);

                    $new_share = $individual_share;
                    $new_share_tax = $individual_share_tax;
                    if ($total_shares != 0) {
                        $new_share = (($individual_share / $total_shares) * 100);
                        $patBillOtherShare = ServiceCost::where('flditemname', 'like', $bill->flditemname)->first()->other_share;
                        $shareAmount = (($new_share*$patBillOtherShare)/100);
                    }

                    if ($new_share_tax != 0) {
                        $new_share_tax = (($individual_share_tax * $new_share) / 100);
                    }

                    // update bill's share
                    $bill_share->share = Helpers::numberFormat($shareAmount,'insert');

                    $bill_share->tax_amt = Helpers::numberFormat($new_share_tax,'insert');
                    $bill_share->save();
                }
            }
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in pat billing share calculate individual share', "Error"]);
        }
        return 1;
    }

    public static function calculateIndividualShareNew($pat_billing_id)
    {
        try {
            $bill = PatBilling::find($pat_billing_id);
            $bill_shares = $bill->pat_billing_shares;
            $item_type = $bill->flditemtype;
            $item_name = $bill->flditemname;
            $item_qty = $bill->flditemqty;

            $total_shares = 0;
            // get users involved in share
            $user_shares = [];
            foreach ($bill_shares as $bill_share) {
                $type = $bill_share->type;
                $user_shares[] = UserShare::where([
                    ['flduserid', $bill_share->user_id],
                    ['flditemtype', $item_type],
                    ['category', $type]
                ])
                    ->where(function ($query) use ($item_name) {
                        $query->where('flditemname', $item_name)
                            ->orWhere('flditemname', 'all');
                    })
                    ->first();
            }

            if (count($user_shares) > 0) {
                $total_shares = collect($user_shares)->sum('flditemshare');

                // calculate share for each individual
                foreach ($bill_shares as $bill_share) {
                    $individual_share = UserService::getShareForService($bill_share->user_id, $item_type, $item_name, $bill_share->type);

                    $individual_share_tax = UserService::getShareTaxForService($bill_share->user_id, $item_type, $item_name, $bill_share->type);
                    $shareAmount = 0;
                    // 55/100*flditemamout

                    $shareAmount  =  ($individual_share/100)*$bill->fldditemamt;
                    $new_share_tax = $individual_share_tax;
                    if ($new_share_tax != 0) {
                            $new_share_tax = ($individual_share_tax * $shareAmount) / 100;
                        }



                        $patBillOtherShare = ServiceCost::where('flditemname', 'like', $bill->flditemname)->first();

                    // update bill's share
                    $bill_share->share =  Helpers::numberFormat($shareAmount,'insert');
                    $bill_share->total_amount = Helpers::numberFormat($bill->fldditemamt,'insert');
                    $bill_share->hospitalshare = Helpers::numberFormat($patBillOtherShare->hospital_share,'insert');
                    $bill_share->usersharepercent = $individual_share;
                    $bill_share->tax_amt = Helpers::numberFormat($new_share_tax,'insert');
                    $bill_share->shareqty = $item_qty;
                    $bill_share->save();
                }
            }
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in pat billing share calculate individual share new', "Error"]);
        }
        return 1;
    }


}
