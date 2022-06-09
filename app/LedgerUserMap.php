<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LedgerUserMap extends Model
{
    protected $table = 'user_ledger_map';

    public $timestamps = false;

    protected $guarded = [];

    public function user_ledger_map()
    {
        return $this->hasOne(AccountLedger::class, 'AccountId', 'ledger_id');
    }
}
