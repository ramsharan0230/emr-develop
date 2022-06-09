<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use LogsActivity;
    protected $table = 'tblpattiming';
    protected $primaryKey = 'fldid';
    protected static $logUnguarded = true;
}
