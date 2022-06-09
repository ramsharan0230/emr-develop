<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Year extends Model
{
    use LogsActivity;
    protected $table = 'tblyear';
    public $timestamps = false;
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldname';
    protected $keyType = 'string';
    protected $fillable = ['fldname', 'fldfirst', 'fldlast', 'hospital_department_id'];
}
