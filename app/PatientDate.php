<?php

namespace App;

// use App\Utils\Helpers;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;

class PatientDate extends Model
{
    use LogsActivity;
    protected $table = 'tblpatientdate';

    protected $guarded = [];
    protected $primaryKey = 'fldid';
    public $timestamps = false;
    protected static $logUnguarded = true;

    public function encounter()
    {
        return $this->hasOne(Encounter::class, 'fldencounterval', 'fldencounterval');
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
