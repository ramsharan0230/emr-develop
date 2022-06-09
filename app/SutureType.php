<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SutureType extends Model
{
    use LogsActivity;
    protected $table = 'tblsuturetype';
    protected $primaryKey = 'fldid';
    public $timestamps = false;
    protected static $logUnguarded = true;
}
