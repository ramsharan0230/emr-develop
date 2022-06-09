<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class Confinement extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $table = 'tblconfinement';
    protected static $logUnguarded = true;
    protected $primaryKey = 'fldid';


    protected $casts = [
        'flddelnurse' => 'array'
    ];

    public function child()
    {
    	return $this->hasOne(PatientInfo::class, 'fldpatientval', 'fldbabypatno');
    }

    public function encounter()
    {
        return $this->hasOne(Encounter::class, 'fldencounterval', 'fldencounterval');
    }

    public function patientinfo()
    {
        return $this->hasOne(PatientInfo::class, 'fldpatientval', 'fldbabypatno');
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
