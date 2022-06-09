<?php

namespace App\Services;

use App\Tpbill;
use App\PatBilling;
use App\ServiceCost;
use App\Utils\Helpers;
use Auth;

class TpBillService
{
    public static function saveTpBillItems($tpbills)
    {
        try {
            if(isset($tpbills) and count($tpbills) > 0){
                foreach($tpbills as $billitem){
                    $tpdata['fldid'] = $billitem->fldid;
                    $tpdata['fldencounterval'] = $billitem->fldencounterval;
                    $tpdata['fldbillingmode'] = $billitem->fldbillingmode;
                    $tpdata['flditemtype'] = $billitem->flditemtype;
                    $tpdata['flditemno'] = $billitem->flditemno;
                    $tpdata['flditemname'] = $billitem->flditemname;
                    $tpdata['flditemoldqty'] = $billitem->flditemqty;
                    $tpdata['flditemrate'] = Helpers::numberFormat($billitem->flditemrate,'insert');
                    $tpdata['fldtaxper'] = $billitem->fldtaxper;
                    $tpdata['flddiscper'] = $billitem->flddiscper;
                    $tpdata['fldtaxamt'] = Helpers::numberFormat($billitem->fldtaxamt,'insert');
                    $tpdata['flddiscamt'] = Helpers::numberFormat($billitem->flddiscamt,'insert');
                    $tpdata['fldditemamt'] = Helpers::numberFormat($billitem->fldditemamt,'insert');

                    $tpdata['flduserid'] = $billitem->flduserid;

                    $tpdata['fldcomp'] = $billitem->fldcomp;

                    $tpdata['fldbillno'] = $billitem->fldbillno;

                    $tpdata['fldstatus'] = $billitem->fldstatus;

                    $tpdata['hospital_department_id'] = $billitem->hospital_department_id;

                    $tpdata['fldtempbillno'] = $billitem->fldtempbillno;
                    $tpdata['discount_mode'] = $billitem->discount_mode;

                    $tpdata['claim_code'] = $billitem->claim_code;
                    $tpdata['package_name'] = $billitem->package_name;

                    Tpbill::create($tpdata);
                }
            }
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in tbltpbill insertion', "Error"]);
        }
    }

    public static function updateTpBillItems($patbilling){
        try{
            // dd($patbilling);
            $tpbilling = Tpbill::where('fldid',$patbilling->fldid)->first();
            // dd($tpbilling);
            if(!is_null($tpbilling->fldnewqty)){
                $tpdata['flditemoldqty'] = $tpbilling->fldnewqty;
            }
            $tpdata['fldnewqty'] = $patbilling->flditemqty;
            $tpdata['fldtaxamt'] = Helpers::numberFormat($patbilling->fldtaxamt,'insert');
            $tpdata['flddiscamt'] = Helpers::numberFormat($patbilling->flddiscamt,'insert');
            $tpdata['fldditemamt'] = Helpers::numberFormat($patbilling->fldditemamt,'insert');
            $tpdata['updated_by'] = Auth::guard('admin_frontend')->user()->flduserid;
            // dd($tpdata);
            $tpbilling->update($tpdata);
        }catch(\Exception $e){
            dd($e);
            Helpers::logStack([$e->getMessage() . ' in tbltpbill update', "Error"]);
        }
    }

    public static function updateDeletedTpBillItems($fldid){
        try{
            $tpbilling = Tpbill::where('fldid',$fldid)->first();
            if(isset($tpbilling)){
                $tpdata['fldstatus'] = 'Deleted';
                $tpdata['updated_by'] = Auth::guard('admin_frontend')->user()->flduserid;
                $tpbilling->update($tpdata);
            }

        }catch(\Exception $e){
            dd($e);
            Helpers::logStack([$e->getMessage() . ' in tbltpbill update', "Error"]);
        }
    }

}
