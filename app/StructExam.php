<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class StructExam extends Model
{
    use LogsActivity;
    protected $table = 'tblstructexam';
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldheadcode';
    protected $keyType = 'string';
}
