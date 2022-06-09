<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RadioTemplate extends Model
{
    use LogsActivity;
    protected $table = 'tblradiotemplate';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';
}
