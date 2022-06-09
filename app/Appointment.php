<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Appointment extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'tblappointment';

    protected static $logUnguarded = true;
}
