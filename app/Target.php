<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Target extends Model
{
    use LogsActivity;
    protected $table = 'tbltarget';
    protected $primary = 'fldid';
    public $timestamps = false;
    protected static $logUnguarded = true;
}
