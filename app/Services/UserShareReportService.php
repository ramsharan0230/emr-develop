<?php

namespace App\Services;

use DB;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Support\Collection;

class UserShareReportService
{
    private static $page_limit = 1;

    public static function query()
    {
        $data = DB::table('tblpatbilling AS pb')
            ->join('pat_billing_shares AS pbs', 'pb.fldid', '=', 'pbs.pat_billing_id')
            ->join('users as usr', 'pbs.user_id', '=', 'usr.id')
            ->select(DB::raw("usr.firstname, usr.middlename, usr.lastname, 
        pb.fldid, pb.fldencounterval, pb.fldbillno ,
         pb.fldbillingmode, pb.flditemtype, pb.flditemname, pb.fldditemamt,
          pb.fldorduserid, pb.fldordtime, pb.fldstatus, pbs.id
        AS pat_billing_share_id, pbs.type, pbs.user_id, pbs.share, 
        ((pbs.hospitalshare/100) * pb.fldditemamt)
        AS hospital_payment,
       pbs.share
        AS doctor_payment,
        pb.fldditemamt
        AS share_amount,
        pbs.tax_amt
        AS amount_after_share_tax
        "))
            ->whereRaw("pbs.type COLLATE utf8mb4_unicode_ci = pb.fldsave = 1");

        return $data;
    }
}
