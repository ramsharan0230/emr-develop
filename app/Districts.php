<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Districts extends Model
{
    use LogsActivity;
    protected $table = 'tbldistrict';
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';
}
