<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Relation extends Model
{
    use LogsActivity;
    protected $table = 'tblrelations';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';
}
