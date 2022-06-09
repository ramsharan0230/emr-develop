<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Dynamicreport extends Model
{
    use LogsActivity;
    protected $table = 'dynamicreports';
    protected static $logUnguarded = true;
    protected $primaryKey = 'id';
    protected $guarded = ['fldreportname','fldreportslug','fldsidebarmodule','fldquery','fldlabels','fldconditions'];
}
