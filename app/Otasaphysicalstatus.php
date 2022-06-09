<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Otasaphysicalstatus extends Model
{
    use LogsActivity;
    protected $fillable = ['fldencounterval','fldvalue','fldtype'];
    protected static $logUnguarded = true;
}
