<?php
namespace App\Utils;

class RegistrationHelpers
{
    public static function getBillConsulataionName($billno)
    {
        $consultation = \App\Consult::select('flduserid')
            ->with('user:flduserid,fldcategory,firstname,middlename,lastname')
            ->where('fldbillno', $billno)->get();

        $consultation = $consultation->map(function($c) {
            return $c->user ? $c->user->fldtitlefullname : '';
        })->toArray();
        $consultation = implode(", ", array_unique(array_filter($consultation)));
        return $consultation;
    }
}
