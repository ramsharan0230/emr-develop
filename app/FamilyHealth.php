<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class FamilyHealth extends Model
{
    use LogsActivity;
    protected $table = 'hmis_family_health';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;

    public function patientInfo()
    {
        return $this->hasOne(PatientInfo::class, 'fldpatientval', 'patient_no')->select('fldpatientval','fldptbirday','fldptsex');
    }
}
