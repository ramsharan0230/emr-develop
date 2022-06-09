<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SubRadioQuali extends Model
{
    use LogsActivity;
    protected $table = "tblsubradioquali";
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';
}
