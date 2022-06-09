<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Otconsultant extends Model
{
    use LogsActivity;
    protected $fillable = ['fldencounterval','fldconsultant'];
    protected static $logUnguarded = true;
}
