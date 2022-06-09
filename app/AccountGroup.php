<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AccountGroup extends Model
{
    use LogsActivity;
    protected $table = 'account_group';

    protected $guarded = [];

    protected static $logUnguarded = true;
    public $timestamps = false;
    protected $primaryKey = 'GroupId';

    public function account_ledger()
    {
        return $this->hasMany('App\AccountLedger', 'GroupId', 'GroupId');
    }

    public function children()
    {
        return $this->hasMany('App\AccountGroup', 'ParentId', 'GroupId');
    }

    public function accountServiceMap()
    {
        return $this->hasMany(AccountServiceCostMap::class, 'sub_group_id', 'GroupId');
    }
}
