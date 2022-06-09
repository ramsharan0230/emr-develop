<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionView extends Model
{
    protected $table = 'transaction_view';

    protected $fillable = [];

    public function accountLedger()
    {
        return $this->belongsTo('App\AccountLedger', 'AccountNo', 'AccountNo');
    }
}
