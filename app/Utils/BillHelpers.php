<?php
namespace App\Utils;

use App\PatBilling;
use App\PatBillingShare;

class BillHelpers
{
    public static function getBillReferals($billno)
    {
        $patBillingShares = '';
        if($billno !=''){
            $pillbillingids = PatBilling::select('fldid')
             ->when($billno != '', function ($query) use ($billno){
                                        return   $query->Where('fldbillno', $billno)->orWhere('fldtempbillno', $billno);
             })
            ->get()->pluck('fldid')->toArray();
            

            $patBillingShares = PatBillingShare::select('user_id')
                ->whereIn('pat_billing_id', $pillbillingids)
                ->whereIn('type', ['OPD Consultation', 'referable'])
                ->with('user:id,fldcategory,firstname,middlename,lastname')
                ->get();

            $patBillingShares = $patBillingShares->map(function($c) {
                return $c->user ? $c->user->fldtitlefullname : '';
            })->toArray();
            $patBillingShares = implode(", ", array_unique(array_filter($patBillingShares)));
            return $patBillingShares;
        }
        return $patBillingShares;
    }

    public static function getBillPayables($billno)
    {
        $retPatBillingShares = '';
        if($billno !=''){
            $pillbillingids = PatBilling::select('fldid')
            ->when($billno != '', function ($query) use ($billno){
                                        return   $query->Where('fldbillno', $billno)->orWhere('fldtempbillno', $billno);
             })->get()->pluck('fldid')->toArray();

            $patBillingShares = PatBillingShare::select('user_id', 'pat_billing_id', 'type')
                ->whereIn('pat_billing_id', $pillbillingids)
                // ->whereNotIn('type', ['OPD Consultation', 'referable'])
                ->whereNotIn('type', ['referable'])
                ->with('user:id,fldcategory,firstname,middlename,lastname')
                ->get();

            $retPatBillingShares = [];
            foreach ($patBillingShares as $patBillingShare) {
                $retPatBillingShares[$patBillingShare->pat_billing_id][] = $patBillingShare->user ? "{$patBillingShare->user->fldtitlefullname} [{$patBillingShare->type}]" : '';
            }

            return $retPatBillingShares;
        }
        return $retPatBillingShares;
        
    }

   

}
