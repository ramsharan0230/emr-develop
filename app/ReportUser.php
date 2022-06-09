<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ReportUser extends Model
{
    use LogsActivity;
    protected $table = 'tblreportuser';
    public $timestamps = false;
    protected $primaryKey = 'fldid';
    protected $guarded = [];
    protected static $logUnguarded = true;
}
