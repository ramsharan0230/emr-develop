<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AccountServiceCostMap extends Model
{
    protected $table = 'account_service_cost_map';

    use LogsActivity;

    protected $guarded = [];

    protected static $logUnguarded = true;
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function accountGroup()
    {
        return $this->belongsTo(AccountGroup::class, 'sub_group_id', 'GroupId');
    }

    public function accountNum()
    {
        return $this->belongsTo(AccountLedger::class, 'sub_group_id', 'GroupId');
    }

    public function entry()
    {
        return $this->belongsTo('App\Entry', 'flditemname', 'fldstockid');
    }
}
