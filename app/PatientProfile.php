<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PatientProfile extends Model
{
    use LogsActivity;
    protected $table='patient_profile_neuro';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected static $logUnguarded = true;

    public function gcs()
    {
        return $this->hasOne(GCS::class,'patient_profile_id');
    }

    public function vital_pupils()
    {
        return $this->hasOne(VitalPupils::class,'patient_profile_id');
    }

    public function ventilator_parameter()
    {
        return $this->hasOne(VentilatorParameter::class,'patient_profile_id');
    }
    public function drug()
    {
        return $this->hasOne(ChestAndEye::class, 'patient_profile_id');
    }

    public function drug_note()
    {
        return $this->hasOne(DrugNote::class,'patient_profile_id');
    }
    public function diagnosis()
    {
        return $this->hasOne(Diagnosis::class,'patient_profile_id');
    }
    public function abg()
    {
        return $this->hasOne(ABG::class,'patient_profile_id');
    }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::addGlobalScope('hospital_department_id', function (Builder $builder) {
    //        if(count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) > 0){
    //           //do nothing
    //        }else{
    //         $builder->where('hospital_department_id',Helpers::getUserSelectedHospitalDepartmentIdSession());
    //        }
    //     });
    // }
}
