<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PatientInfoOld extends Model
{
    use LogsActivity;
    protected $table = 'tblpatientinfo_old';
    public $timestamps = false;
    protected $primaryKey = 'fldpatientval';
    protected static $logUnguarded = true;

    public function municipality()
    {
        return $this->belongsTo(Municipal::class, 'fldmunicipality', 'fldpality');
    }

    public function getFldptsexAttribute($value)
    {
        return ucwords(strtolower($value));
    }
}
