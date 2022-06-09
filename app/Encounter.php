<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class Encounter extends Model
{
    use LogsActivity;
    protected $table = 'tblencounter';
    public $timestamps = false;
    protected $primaryKey = 'fldencounterval';
    protected $keyType = 'string';
    protected static $logUnguarded = true;
    protected $appends = ['fldcurrlocastyle'];

    public function getFldcurrlocastyleAttribute()
    {
        $fldcurrlocat = $this->fldcurrlocat;
        if ($this->currentBedDepartment)
            $fldcurrlocat .= "/{$this->currentBedDepartment->flddept}";
        return $fldcurrlocat;
    }

    public function currentBedDepartment()
    {
        return $this->hasOne(Departmentbed::class, 'fldbed', 'fldcurrlocat');
    }

    public function patientInfo()
    {
        return $this->hasOne(PatientInfo::class, 'fldpatientval', 'fldpatientval');
    }

    public function consultant()
    {
        return $this->hasOne(Consult::class, 'fldencounterval', 'fldencounterval');
    }

    public function allConsultant()
    {
        return $this->hasMany(Consult::class, 'fldencounterval', 'fldencounterval');
    }

    public static function getEncounterPatient($encounterId)
    {
        return Encounter::where('fldencounterval', $encounterId)->first();
    }

    public static function getAllEncounterPatient($patientId)
    {
        return Encounter::where('fldpatientval', $patientId)->get();
    }

    public function patLabTests()
    {
        return $this->hasMany(PatLabTest::class, 'fldencounterval', 'fldencounterval');
    }

    public function currentDepartment()
    {
        return $this->hasOne(Department::class, 'flddept', 'fldcurrlocat');
    }

    public function departmentBed()
    {
        return $this->hasOne(Departmentbed::class, 'fldencounterval', 'fldencounterval');
    }

    public function patBill()
    {
        return $this->hasMany(PatBilling::class, 'fldencounterval', 'fldencounterval');
    }

    public function patBillDetails()
    {
        return $this->hasMany(PatBillDetail::class, 'fldencounterval', 'fldencounterval');
    }

    public function PatFindings()
    {
        return $this->hasMany(PatFindings::class, 'fldencounterval', 'fldencounterval');
    }

    public function PatDosing()
    {
        return $this->hasMany(PatDosing::class, 'fldencounterval', 'fldencounterval');
    }

    public function PatPlanning()
    {
        return $this->hasMany(PatPlanning::class, 'fldencounterval', 'fldencounterval');
    }

    public function user()
    {
        return $this->belongsTo('App\CogentUsers', 'flduserid', 'flduserid');
    }

    public function room()
    {
        return $this->hasOne(Department::class,'flddept','fldadmitlocat');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //         // echo
    //        if(count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) > 0){
    //           //do nothing
    //        }else{
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //        }

    //     });
    // }
}
