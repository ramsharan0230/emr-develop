<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Otextraexaminationdetail extends Model
{
    use LogsActivity;
    protected $fillable = ['fldencounterval','fldvalue','fldtype','flditem'];
    protected static $logUnguarded = true;
}
