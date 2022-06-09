<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Tpbill extends Model
{
    use LogsActivity;

    protected $table = 'tbltpbills';
    protected $fillable = ['fldid','fldencounterval','fldbillingmode','flditemtype','flditemno','flditemname','flditemrate','fldtaxper','flddiscper','flditemoldqty','fldnewqty','fldtaxamt','flddiscamt','fldditemamt','fldstatus','updated_by','flduserid','fldcomp','fldtempbillno','discount_mode','claim_code','package_name','hospital_department_id'];
}
