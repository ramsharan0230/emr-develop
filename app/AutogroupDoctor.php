<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AutogroupDoctor extends Model
{
    use LogsActivity;
    public $timestamps = true;
    protected $table = 'tblautogroupdoctor';
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';
}
