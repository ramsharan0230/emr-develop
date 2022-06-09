<?php

namespace App;

// use App\Utils\Helpers;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class PatientCredential extends Authenticatable
{
    use Notifiable, LogsActivity;

    protected $table = 'tblpatientcredential';
    protected $guarded = ['id'];
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $hidden = [
        'remember_token',
    ];
    protected static $logUnguarded = true;

    public function patientInfo()
    {
        return $this->hasOne(PatientInfo::class, 'fldpatientval', 'fldpatientval');
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
