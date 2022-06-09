<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountLedgerMap extends Model
{
    protected $table = "discount_account_map";

    protected $guarded = [];

    public $timestamps  =false;

    public function ledger()
    {
        return $this->belongsTo(AccountLedger::class, 'ledger_id', 'AccountId');
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_name', 'fldtype');
    }
}
