<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AccountLedger extends Model
{
    use LogsActivity;
    protected $table = 'account_ledger';
    public $timestamps = false;
    protected $guarded = [];
    protected $primaryKey = 'AccountId';
    protected static $logUnguarded = true;

    public function account_group()
    {
        return $this->belongsTo('App\AccountGroup', 'GroupId', 'GroupId');
    }
    public function account_user()
    {
        return $this->hasOne(LedgerUserMap::class, 'ledger_id', 'AccountId');
    }
    public function discount_ledger_map()
    {
        return $this->hasOne(DiscountLedgerMap::class, 'ledger_id', 'AccountId');
    }

}
