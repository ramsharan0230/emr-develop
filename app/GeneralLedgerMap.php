<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralLedgerMap extends Model
{
    protected $table = "account_general_map";

    protected $guarded = [];

    public $timestamps  =false;

    public function ledger()
    {
        return $this->belongsTo(AccountLedger::class, 'ledger_id', 'AccountId');
    }

}
