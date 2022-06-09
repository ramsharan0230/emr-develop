<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ReportGroup extends Model
{
    use LogsActivity;
    protected $table = 'tblreportgroup';
    public $timestamps = false;
    protected $primaryKey = 'fldid';
    protected $guarded = [];
    protected static $logUnguarded = true;
}
