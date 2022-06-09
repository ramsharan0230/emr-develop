<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillConfig extends Model
{
    protected $table = 'bill_configuration';

    protected $primaryKey = 'BillId';

    public $timestamps = false;

    protected $guarded = [];
}
