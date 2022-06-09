<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Radiolimit extends Model
{
    use LogsActivity;
    protected  $table = 'tblradiolimit';
    protected $primaryKey = 'fldid';
    protected static $logUnguarded = true;
    public $timestamps = false;
}
