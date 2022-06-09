<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TransactionMasterPost extends Model
{
    use LogsActivity;

    protected $table = 'transaction_master_post';

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
}
