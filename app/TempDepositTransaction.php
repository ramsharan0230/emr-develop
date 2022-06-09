<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TempDepositTransaction extends Model
{
    use LogsActivity;
    protected $table = 'temp_deposit_transaction';

    protected $guarded = [];

    protected static $logUnguarded = true;

    public $timestamps = false;

    protected $primaryKey = 'TranId';

    public function branch()
    {
        return $this->belongsTo('App\HospitalDepartment', 'BranchId', 'id');
    }

    public function accountLedger()
    {
        return $this->belongsTo('App\AccountLedger', 'AccountNo', 'AccountNo');
    }

    public function accountLedgerDiscount()
    {
        return $this->belongsTo('App\AccountLedger', 'DisAccountNo', 'AccountNo');
    }

    public function patbill()
    {
        return $this->belongsTo('App\PatBilling', 'fldid');
    }
}
