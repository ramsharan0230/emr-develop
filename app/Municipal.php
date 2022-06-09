<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Municipal extends Model
{
    use LogsActivity;
    protected $table = 'tblmunicipals';
    public $timestamps = false;
    public $fillable = ['fldprovince','flddistrict','fldpality'];
    protected $primaryKey = 'fldid';
    protected static $logUnguarded = true;
}
