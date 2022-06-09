<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatBillingSharesReport extends Model
{
    protected $table = "pat_billing_shares_report";

    public function user()
    {
        return $this->belongsTo(CogentUsers::class, 'user_id', 'id');
    }
    public function encounter()
    {
        return $this->belongsTo(Encounter::class, 'fldencounterval', 'fldencounterval');
    }
}
